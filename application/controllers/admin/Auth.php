<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller
{
    const MAX_ATTEMPTS = 5;
    const LOCK_MINUTES = 15;

    public function login()
    {
        if ($this->session->userdata('admin_id')) {
            redirect('admin');
        }
        $this->load->library('form_validation');
        $this->load->model('User_model');
        $error = '';
        $this->form_validation->set_rules('login', 'Username / Email', 'trim|required|max_length[120]');
        $this->form_validation->set_rules('password', 'Password', 'required|max_length[100]');

        if ($this->form_validation->run()) {
            $user = $this->User_model->by_login($this->input->post('login'));
            $now = date('Y-m-d H:i:s');
            if ($user && $user['locked_until'] && $user['locked_until'] > $now) {
                $error = 'Akun dikunci sementara karena terlalu banyak percobaan gagal. Coba lagi dalam ' . self::LOCK_MINUTES . ' menit.';
            } elseif ($user && $user['is_active'] && password_verify($this->input->post('password'), $user['password'])) {
                $update = array('failed_attempts' => 0, 'locked_until' => null, 'last_login' => $now);
                if (password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
                    $update['password'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
                }
                $this->User_model->update($user['id'], $update);
                if (ENVIRONMENT === 'production') {
                    $this->session->sess_regenerate(TRUE);
                }
                $this->session->set_userdata(array('admin_id' => (int) $user['id'], 'admin_role' => $user['role']));
                redirect('admin');
            } else {
                if ($user) {
                    $attempts = (int) $user['failed_attempts'] + 1;
                    $data = array('failed_attempts' => $attempts);
                    if ($attempts >= self::MAX_ATTEMPTS) {
                        $data = array('failed_attempts' => 0, 'locked_until' => date('Y-m-d H:i:s', time() + self::LOCK_MINUTES * 60));
                    }
                    $this->User_model->update($user['id'], $data);
                }
                usleep(400000);
                $error = 'Username/email atau password salah, atau akun tidak aktif.';
            }
        }
        $this->load->view('admin/auth/login', array('settings' => $this->settings, 'error' => $error));
    }

    public function logout()
    {
        if ($this->input->method() !== 'post') {
            redirect('admin');
        }
        $this->session->sess_destroy();
        redirect('admin/login');
    }
}
