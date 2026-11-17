<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Berita extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('News_model', 'Category_model'));
        $this->load->library('form_validation');
    }

    public function index()
    {
        $this->load->library('pagination');
        $f = array(
            'type'   => $this->input->get('type', TRUE),
            'status' => $this->input->get('status', TRUE),
            'q'      => trim((string) $this->input->get('q', TRUE)),
        );
        $f['type'] = array_key_exists($f['type'], news_types()) ? $f['type'] : '';
        $f['status'] = in_array($f['status'], array('draft', 'published'), TRUE) ? $f['status'] : '';
        $per = (int) setting('admin_per_page', 15);
        $page = max(1, (int) $this->input->get('page'));
        $total = $this->News_model->count_admin($f);
        $this->pagination->initialize(array('base_url' => site_url('admin/berita'), 'total_rows' => $total, 'per_page' => $per));
        $this->render('news/index', array(
            'page_title' => 'Berita & Pengumuman',
            'items'      => $this->News_model->list_admin($f, $per, ($page - 1) * $per),
            'total'      => $total,
            'f'          => $f,
            'pagination' => $this->pagination->create_links(),
        ));
    }

    public function tambah()
    {
        $this->form();
    }

    public function edit($id = 0)
    {
        $item = $this->News_model->get($id);
        if (!$item) {
            show_404();
        }
        $this->form($item);
    }

    private function form($item = null)
    {
        $fv = $this->form_validation;
        $fv->set_rules('title', 'Judul', 'trim|required|max_length[200]');
        $fv->set_rules('slug', 'Slug URL', 'trim|max_length[200]|alpha_dash');
        $fv->set_rules('type', 'Jenis', 'required|in_list[berita,pengumuman,agenda]');
        $fv->set_rules('category_id', 'Kategori', 'integer');
        $fv->set_rules('excerpt', 'Ringkasan', 'trim|max_length[300]');
        $fv->set_rules('content', 'Isi', 'required');
        $fv->set_rules('status', 'Status', 'required|in_list[draft,published]');
        $fv->set_rules('published_at', 'Tanggal Terbit', 'trim');
        $fv->set_rules('event_date', 'Tanggal Kegiatan', 'trim' . ($this->input->post('type') === 'agenda' ? '|required' : ''));
        $fv->set_rules('event_location', 'Lokasi', 'trim|max_length[150]');

        $error = '';
        if ($fv->run()) {
            $old = $item ? $item['thumbnail'] : null;
            $thumb = $this->upload_image('thumbnail', 'news', $old);
            if ($thumb === FALSE) {
                $error = 'Gambar: ' . $this->upload_error;
            } else {
                if ($this->input->post('remove_thumbnail') && $thumb === $old) {
                    $thumb = null;
                }
                $p = $this->input->post();
                $slug_src = $p['slug'] !== '' ? $p['slug'] : $p['title'];
                $pub = strtotime((string) $p['published_at']);
                $data = array(
                    'title'          => $p['title'],
                    'slug'           => $this->News_model->unique_slug($slug_src, $item ? $item['id'] : 0),
                    'type'           => $p['type'],
                    'category_id'    => (int) $p['category_id'] ?: null,
                    'excerpt'        => $p['excerpt'] !== '' ? $p['excerpt'] : excerpt($p['content'], 250),
                    'content'        => $this->security->xss_clean($p['content']),
                    'thumbnail'      => $thumb,
                    'status'         => $p['status'],
                    'published_at'   => $pub ? date('Y-m-d H:i:s', $pub) : date('Y-m-d H:i:s'),
                    'event_date'     => $p['type'] === 'agenda' && strtotime($p['event_date']) ? date('Y-m-d', strtotime($p['event_date'])) : null,
                    'event_location' => $p['type'] === 'agenda' ? ($p['event_location'] ?: null) : null,
                );
                if ($item) {
                    $this->News_model->update($item['id'], $data);
                    if ($old && $old !== $thumb) {
                        $this->cleanup_file('news', $old, 'news', 'thumbnail');
                    }
                    $this->session->set_flashdata('success', 'Berita berhasil diperbarui.');
                } else {
                    $data['author_id'] = $this->user['id'];
                    $this->News_model->insert($data);
                    $this->session->set_flashdata('success', 'Berita berhasil ditambahkan.');
                }
                redirect('admin/berita');
            }
        }
        $this->render('news/form', array(
            'page_title' => $item ? 'Edit Berita' : 'Tambah Berita',
            'item'       => $item,
            'categories' => $this->Category_model->all(),
            'error'      => $error,
            'editor'     => TRUE,
        ));
    }

    public function hapus($id = 0)
    {
        $this->require_post();
        $item = $this->News_model->get($id);
        if ($item) {
            $this->News_model->delete($id);
            $this->cleanup_file('news', $item['thumbnail'], 'news', 'thumbnail');
            $this->session->set_flashdata('success', 'Berita "' . $item['title'] . '" telah dihapus.');
        }
        redirect('admin/berita');
    }

    /** Endpoint upload gambar dari editor (AJAX). */
    public function unggah_gambar()
    {
        $this->require_post();
        $name = $this->upload_image('image', 'content');
        $this->output->set_content_type('application/json');
        if (!$name) {
            $this->output->set_status_header(422)->set_output(json_encode(array('error' => $name === FALSE ? $this->upload_error : 'File tidak ditemukan.')));
            return;
        }
        $this->output->set_output(json_encode(array('url' => upload_url('content', $name))));
    }
}
