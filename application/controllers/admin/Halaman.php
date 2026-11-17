<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Halaman extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Page_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $this->render('pages/index', array('page_title' => 'Halaman & Profil', 'items' => $this->Page_model->all()));
    }

    public function tambah()
    {
        $this->form();
    }

    public function edit($id = 0)
    {
        $item = $this->Page_model->get($id);
        if (!$item) {
            show_404();
        }
        $this->form($item);
    }

    private function form($item = null)
    {
        $this->form_validation->set_rules('title', 'Judul', 'trim|required|max_length[200]');
        $this->form_validation->set_rules('content', 'Isi', 'required');
        if (!$item || !$item['is_system']) {
            $this->form_validation->set_rules('slug', 'Slug URL', 'trim|required|max_length[100]|alpha_dash');
        }
        $error = '';
        if ($this->form_validation->run()) {
            $slug = ($item && $item['is_system']) ? $item['slug'] : url_title($this->input->post('slug'), '-', TRUE);
            $old = $item ? $item['image'] : null;
            $image = $this->upload_image('image', 'pages', $old);
            if ($this->Page_model->slug_exists($slug, $item ? $item['id'] : 0)) {
                $error = 'Slug URL sudah digunakan halaman lain.';
            } elseif ($image === FALSE) {
                $error = 'Gambar: ' . $this->upload_error;
            } else {
                if ($this->input->post('remove_image') && $image === $old) {
                    $image = null;
                }
                $data = array(
                    'title'   => $this->input->post('title'),
                    'slug'    => $slug,
                    'content' => $this->security->xss_clean($this->input->post('content')),
                    'image'   => $image,
                );
                if ($item) {
                    $this->Page_model->update($item['id'], $data);
                    if ($old && $old !== $image) {
                        $this->cleanup_file('pages', $old, 'pages', 'image');
                    }
                } else {
                    $this->Page_model->insert($data);
                }
                $this->session->set_flashdata('success', 'Halaman "' . $data['title'] . '" berhasil disimpan.');
                redirect('admin/halaman');
            }
        }
        $this->render('pages/form', array(
            'page_title' => $item ? 'Edit Halaman' : 'Tambah Halaman',
            'item'       => $item,
            'error'      => $error,
            'editor'     => TRUE,
        ));
    }

    public function hapus($id = 0)
    {
        $this->require_post();
        $item = $this->Page_model->get($id);
        if ($item && !$item['is_system']) {
            $this->Page_model->delete($id);
            $this->cleanup_file('pages', $item['image'], 'pages', 'image');
            $this->session->set_flashdata('success', 'Halaman dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Halaman sistem tidak dapat dihapus.');
        }
        redirect('admin/halaman');
    }
}
