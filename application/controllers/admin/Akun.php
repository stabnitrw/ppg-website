<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Akun extends Admin_Controller
{
    public function index()
    {
        $this->load->library('form_validation');
        $fv = $this->form_validation;
        $fv->set_rules('name', 'Nama', 'trim|required|max_length[100]');
        $fv->set_rules('email', 'Email', 'trim|required|valid_email|max_length[120]');
        $fv->set_rules('current_password', 'Password Saat Ini', 'required');
        $fv->set_rules('new_password', 'Password Baru', 'min_length[8]|max_length[72]');
        $fv->set_rules('new_password_confirm', 'Konfirmasi Password Baru', 'matches[new_password]');
        $error = '';
        if ($fv->run()) {
            $p = $this->input->post();
            if (!password_verify($p['current_password'], $this->user['password'])) {
                $error = 'Password saat ini salah.';
            } elseif ($this->User_model->exists('email', $p['email'], $this->user['id'])) {
                $error = 'Email sudah digunakan akun lain.';
            } elseif ($p['new_password'] !== '' && (!preg_match('/[A-Za-z]/', $p['new_password']) || !preg_match('/\d/', $p['new_password']))) {
                $error = 'Password baru harus mengandung huruf dan angka.';
            } else {
                $data = array('name' => $p['name'], 'email' => strtolower($p['email']));
                if ($p['new_password'] !== '') {
                    $data['password'] = password_hash($p['new_password'], PASSWORD_DEFAULT);
                }
                $this->User_model->update($this->user['id'], $data);
                if (isset($data['password'])) {
                    if (ENVIRONMENT === 'production') {
                    $this->session->sess_regenerate(TRUE);
                }
                }
                $this->session->set_flashdata('success', 'Profil akun berhasil diperbarui.');
                redirect('admin/akun');
            }
        }
        $this->render('account', array('page_title' => 'Akun Saya', 'error' => $error));
    }
}
