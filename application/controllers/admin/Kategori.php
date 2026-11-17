<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Category_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $edit = $this->input->get('edit') ? $this->Category_model->get($this->input->get('edit')) : null;
        $this->render('categories', array(
            'page_title' => 'Kategori Berita',
            'items'      => $this->Category_model->with_counts(),
            'edit'       => $edit,
        ));
    }

    public function simpan()
    {
        $this->require_post();
        $id = (int) $this->input->post('id');
        $this->form_validation->set_rules('name', 'Nama Kategori', 'trim|required|max_length[80]');
        $this->form_validation->set_rules('description', 'Deskripsi', 'trim|max_length[255]');
        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', strip_tags(validation_errors()));
            redirect('admin/kategori' . ($id ? '?edit=' . $id : ''));
        }
        $name = $this->input->post('name');
        $slug = url_title(convert_accented_characters($name), '-', TRUE);
        if ($slug === '' || $this->Category_model->slug_exists($slug, $id)) {
            $this->session->set_flashdata('error', 'Nama kategori sudah digunakan atau tidak valid.');
            redirect('admin/kategori' . ($id ? '?edit=' . $id : ''));
        }
        $data = array('name' => $name, 'slug' => $slug, 'description' => $this->input->post('description'));
        if ($id && $this->Category_model->get($id)) {
            $this->Category_model->update($id, $data);
            $this->session->set_flashdata('success', 'Kategori berhasil diperbarui.');
        } else {
            $this->Category_model->insert($data);
            $this->session->set_flashdata('success', 'Kategori berhasil ditambahkan.');
        }
        redirect('admin/kategori');
    }

    public function hapus($id = 0)
    {
        $this->require_post();
        if ($this->Category_model->get($id)) {
            $this->Category_model->delete($id);
            $this->session->set_flashdata('success', 'Kategori dihapus. Berita terkait kini tanpa kategori.');
        }
        redirect('admin/kategori');
    }
}
