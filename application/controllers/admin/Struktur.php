<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Struktur extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Structure_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $this->render('structure/index', array('page_title' => 'Struktur Organisasi', 'items' => $this->Structure_model->flat()));
    }

    public function tambah()
    {
        $this->form();
    }

    public function edit($id = 0)
    {
        $item = $this->Structure_model->get($id);
        if (!$item) {
            show_404();
        }
        $this->form($item);
    }

    private function form($item = null)
    {
        $fv = $this->form_validation;
        $fv->set_rules('position', 'Jabatan', 'trim|required|max_length[150]');
        $fv->set_rules('name', 'Nama Pejabat', 'trim|required|max_length[150]');
        $fv->set_rules('unit', 'Unit', 'trim|max_length[150]');
        $fv->set_rules('parent_id', 'Atasan', 'integer');
        $fv->set_rules('sort_order', 'Urutan', 'trim|integer');

        $blocked = $item ? array_merge(array((int) $item['id']), $this->Structure_model->descendant_ids($item['id'])) : array();
        $error = '';
        if ($fv->run()) {
            $parent = (int) $this->input->post('parent_id');
            if ($parent && (in_array($parent, $blocked, TRUE) || !$this->Structure_model->get($parent))) {
                $error = 'Atasan tidak valid.';
            } else {
                $p = $this->input->post(NULL, TRUE);
                $data = array(
                    'parent_id'  => $parent ?: null,
                    'position'   => $p['position'],
                    'name'       => $p['name'],
                    'unit'       => $p['unit'] ?: null,
                    'sort_order' => (int) $p['sort_order'],
                );
                $item ? $this->Structure_model->update($item['id'], $data) : $this->Structure_model->insert($data);
                $this->session->set_flashdata('success', 'Jabatan ' . $data['position'] . ' berhasil disimpan.');
                redirect('admin/struktur');
            }
        }
        $options = array_filter($this->Structure_model->flat(), function ($r) use ($blocked) {
            return !in_array((int) $r['id'], $blocked, TRUE);
        });
        $this->render('structure/form', array(
            'page_title' => $item ? 'Edit Jabatan' : 'Tambah Jabatan',
            'item'       => $item,
            'options'    => $options,
            'error'      => $error,
        ));
    }

    public function hapus($id = 0)
    {
        $this->require_post();
        $item = $this->Structure_model->get($id);
        if ($item) {
            $this->db->where('parent_id', (int) $id)->update('organizational_structure', array('parent_id' => $item['parent_id']));
            $this->Structure_model->delete($id);
            $this->session->set_flashdata('success', 'Jabatan dihapus. Bawahan dipindahkan ke atasan sebelumnya.');
        }
        redirect('admin/struktur');
    }
}
