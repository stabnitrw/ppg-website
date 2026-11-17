<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengguna extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->require_admin();
        $this->load->library('form_validation');
    }

    public function index()
    {
        $this->render('users/index', array('page_title' => 'Pengguna Admin', 'items' => $this->User_model->all()));
    }

    public function tambah()
    {
        $this->form();
    }

    public function edit($id = 0)
    {
        $item = $this->User_model->get($id);
        if (!$item) {
            show_404();
        }
        $this->form($item);
    }

    private function form($item = null)
    {
        $fv = $this->form_validation;
        $fv->set_rules('name', 'Nama', 'trim|required|max_length[100]');
        $fv->set_rules('username', 'Username', 'trim|required|min_length[4]|max_length[50]|alpha_dash');
        $fv->set_rules('email', 'Email', 'trim|required|valid_email|max_length[120]');
        $fv->set_rules('role', 'Peran', 'required|in_list[admin,editor]');
        $fv->set_rules('password', 'Password', ($item ? '' : 'required|') . 'min_length[8]|max_length[72]|callback_strong_password');
        $fv->set_rules('password_confirm', 'Konfirmasi Password', 'matches[password]');
        $error = '';
        if ($fv->run()) {
            $id = $item ? $item['id'] : 0;
            $p = $this->input->post();
            $active = !empty($p['is_active']) ? 1 : 0;
            if ($this->User_model->exists('username', $p['username'], $id)) {
                $error = 'Username sudah digunakan.';
            } elseif ($this->User_model->exists('email', $p['email'], $id)) {
                $error = 'Email sudah digunakan.';
            } elseif ($item && $item['role'] === 'admin' && ($p['role'] !== 'admin' || !$active) && $this->User_model->count_admins() <= 1) {
                $error = 'Minimal harus ada satu Administrator aktif.';
            } elseif ($item && (int) $item['id'] === (int) $this->user['id'] && !$active) {
                $error = 'Anda tidak dapat menonaktifkan akun sendiri.';
            } else {
                $data = array('name' => $p['name'], 'username' => $p['username'], 'email' => strtolower($p['email']), 'role' => $p['role'], 'is_active' => $active);
                if ($p['password'] !== '') {
                    $data['password'] = password_hash($p['password'], PASSWORD_DEFAULT);
                    $data['failed_attempts'] = 0;
                    $data['locked_until'] = null;
                }
                $item ? $this->User_model->update($id, $data) : $this->User_model->insert($data);
                $this->session->set_flashdata('success', 'Akun ' . $data['username'] . ' berhasil disimpan.');
                redirect('admin/pengguna');
            }
        }
        $this->render('users/form', array('page_title' => $item ? 'Edit Pengguna' : 'Tambah Pengguna', 'item' => $item, 'error' => $error));
    }

    public function strong_password($pw)
    {
        if ($pw !== '' && (!preg_match('/[A-Za-z]/', $pw) || !preg_match('/\d/', $pw))) {
            $this->form_validation->set_message('strong_password', '{field} harus mengandung huruf dan angka.');
            return FALSE;
        }
        return TRUE;
    }

    public function hapus($id = 0)
    {
        $this->require_post();
        $item = $this->User_model->get($id);
        if (!$item) {
            redirect('admin/pengguna');
        }
        if ((int) $item['id'] === (int) $this->user['id']) {
            $this->session->set_flashdata('error', 'Anda tidak dapat menghapus akun sendiri.');
        } elseif ($item['role'] === 'admin' && $this->User_model->count_admins() <= 1) {
            $this->session->set_flashdata('error', 'Administrator terakhir tidak dapat dihapus.');
        } else {
            $this->User_model->delete($id);
            $this->session->set_flashdata('success', 'Akun ' . $item['username'] . ' dihapus.');
        }
        redirect('admin/pengguna');
    }
}
