<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    public $settings = array();
    protected $data = array();
    protected $upload_error = '';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Setting_model');
        $this->settings = $this->Setting_model->get_all();
        $this->data['settings'] = $this->settings;
    }

    /**
     * Upload & optimasi gambar (resize + kompresi). Nama file = hash isi file untuk mencegah duplikasi.
     * Return: nama file baru, $old jika tidak ada file, atau FALSE jika gagal.
     */
    protected function upload_image($field, $folder, $old = null)
    {
        if (empty($_FILES[$field]['name'])) {
            return $old;
        }
        $dir = FCPATH . 'assets/uploads/' . $folder . '/';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $this->load->library('upload');
        $this->upload->initialize(array(
            'upload_path'      => $dir,
            'allowed_types'    => 'jpg|jpeg|png',
            'max_size'         => (int) setting('image_max_size_kb', 2048),
            'encrypt_name'     => TRUE,
            'file_ext_tolower' => TRUE,
            'detect_mime'      => TRUE,
        ), TRUE);
        if (!$this->upload->do_upload($field)) {
            $this->upload_error = strip_tags($this->upload->display_errors());
            return FALSE;
        }
        $file = $this->upload->data();
        $info = @getimagesize($file['full_path']);
        if (!$info) {
            @unlink($file['full_path']);
            $this->upload_error = 'File bukan gambar yang valid.';
            return FALSE;
        }
        $max_w = (int) setting('image_max_width', 1200);
        $this->load->library('image_lib');
        $this->image_lib->clear();
        $this->image_lib->initialize(array(
            'image_library'  => 'gd2',
            'source_image'   => $file['full_path'],
            'maintain_ratio' => TRUE,
            'width'          => min($info[0], $max_w),
            'height'         => $info[0] > $max_w ? (int) round($info[1] * $max_w / $info[0]) : $info[1],
            'quality'        => (int) setting('image_quality', 75),
        ));
        $this->image_lib->resize();

        $name = sha1_file($file['full_path']) . $file['file_ext'];
        if (is_file($dir . $name)) {
            @unlink($file['full_path']);
        } else {
            rename($file['full_path'], $dir . $name);
        }
        return $name;
    }

    /** Hapus file lama hanya jika tidak dipakai record lain. */
    protected function cleanup_file($folder, $file, $table, $column)
    {
        if (!$file || strpos($file, '/') !== FALSE) {
            return;
        }
        if ($this->db->where($column, $file)->count_all_results($table) === 0) {
            @unlink(FCPATH . 'assets/uploads/' . $folder . '/' . $file);
        }
    }
}

class Public_Controller extends MY_Controller
{
    protected function page_404()
    {
        $this->output->set_status_header(404);
        $this->render('not_found', array('page_title' => 'Halaman Tidak Ditemukan'));
    }

    protected function render($view, $data = array())
    {
        $data = array_merge($this->data, $data);
        $this->load->view('public/layout/header', $data);
        $this->load->view('public/' . $view, $data);
        $this->load->view('public/layout/footer', $data);
    }
}

class Admin_Controller extends MY_Controller
{
    protected $user;

    public function __construct()
    {
        parent::__construct();
        $id = (int) $this->session->userdata('admin_id');
        if (!$id) {
            redirect('admin/login');
        }
        $this->load->model('User_model');
        $this->user = $this->User_model->get($id);
        if (!$this->user || !$this->user['is_active']) {
            $this->session->sess_destroy();
            redirect('admin/login');
        }
        $this->data['user'] = $this->user;
    }

    protected function require_admin()
    {
        if ($this->user['role'] !== 'admin') {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke menu tersebut.');
            redirect('admin');
        }
    }

    protected function require_post()
    {
        if ($this->input->method() !== 'post') {
            show_error('Metode tidak diizinkan.', 405);
        }
    }

    protected function render($view, $data = array())
    {
        $data = array_merge($this->data, $data);
        $this->load->view('admin/layout/header', $data);
        $this->load->view('admin/' . $view, $data);
        $this->load->view('admin/layout/footer', $data);
    }
}
