<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pimpinan extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Profile_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $this->render('profiles/index', array('page_title' => 'Profil Pimpinan', 'items' => $this->Profile_model->all()));
    }

    public function tambah()
    {
        $this->form();
    }

    public function edit($id = 0)
    {
        $item = $this->Profile_model->get($id);
        if (!$item) {
            show_404();
        }
        $this->form($item);
    }

    private function form($item = null)
    {
        $fv = $this->form_validation;
        $fv->set_rules('name', 'Nama', 'trim|required|max_length[150]');
        $fv->set_rules('position', 'Jabatan', 'trim|required|max_length[150]');
        $fv->set_rules('nip', 'NIP', 'trim|max_length[30]');
        $fv->set_rules('email', 'Email', 'trim|valid_email|max_length[120]');
        $fv->set_rules('education', 'Pendidikan', 'trim|max_length[200]');
        $fv->set_rules('bio', 'Biografi', 'trim|max_length[2000]');
        $fv->set_rules('sort_order', 'Urutan', 'trim|integer');
        $error = '';
        if ($fv->run()) {
            $old = $item ? $item['photo'] : null;
            $photo = $this->upload_image('photo', 'profiles', $old);
            if ($photo === FALSE) {
                $error = 'Foto: ' . $this->upload_error;
            } else {
                if ($this->input->post('remove_photo') && $photo === $old) {
                    $photo = null;
                }
                $p = $this->input->post(NULL, TRUE);
                $data = array(
                    'name'       => $p['name'],
                    'position'   => $p['position'],
                    'nip'        => $p['nip'] ?: null,
                    'email'      => $p['email'] ?: null,
                    'education'  => $p['education'] ?: null,
                    'bio'        => $p['bio'] ?: null,
                    'photo'      => $photo,
                    'sort_order' => (int) $p['sort_order'],
                    'is_active'  => !empty($p['is_active']) ? 1 : 0,
                );
                if ($item) {
                    $this->Profile_model->update($item['id'], $data);
                    if ($old && $old !== $photo) {
                        $this->cleanup_file('profiles', $old, 'faculty_profiles', 'photo');
                    }
                } else {
                    $this->Profile_model->insert($data);
                }
                $this->session->set_flashdata('success', 'Profil ' . $data['name'] . ' berhasil disimpan.');
                redirect('admin/pimpinan');
            }
        }
        $this->render('profiles/form', array('page_title' => $item ? 'Edit Profil' : 'Tambah Profil', 'item' => $item, 'error' => $error));
    }

    public function hapus($id = 0)
    {
        $this->require_post();
        $item = $this->Profile_model->get($id);
        if ($item) {
            $this->Profile_model->delete($id);
            $this->cleanup_file('profiles', $item['photo'], 'faculty_profiles', 'photo');
            $this->session->set_flashdata('success', 'Profil dihapus.');
        }
        redirect('admin/pimpinan');
    }
}
