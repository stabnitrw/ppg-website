<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Berita extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('News_model', 'Category_model'));
    }

    public function index()
    {
        $this->listing('berita', 'Berita Terkini', 'berita');
    }

    public function pengumuman()
    {
        $this->listing('pengumuman', 'Pengumuman', 'pengumuman');
    }

    public function agenda()
    {
        $this->listing('agenda', 'Agenda Akademik', 'agenda');
    }

    public function kategori($slug = '')
    {
        $cat = $this->Category_model->by_slug($slug);
        if (!$cat) {
            return $this->page_404();
        }
        $this->listing('', 'Kategori: ' . $cat['name'], 'berita/kategori/' . $cat['slug'], $cat);
    }

    private function listing($type, $title, $base, $category = null)
    {
        $this->load->library('pagination');
        $q = trim((string) $this->input->get('q', TRUE));
        $per_page = max(1, (int) setting('news_per_page', 9));
        $page = max(1, (int) $this->input->get('page'));
        $filters = array('type' => $type, 'q' => mb_substr($q, 0, 100), 'category_id' => $category ? $category['id'] : 0);
        $total = $this->News_model->count_public($filters);

        $this->pagination->initialize(array('base_url' => site_url($base), 'total_rows' => $total, 'per_page' => $per_page));

        $this->render('news_list', array(
            'page_title'   => $title,
            'type'         => $type,
            'base'         => $base,
            'category'     => $category,
            'q'            => $q,
            'total'        => $total,
            'items'        => $this->News_model->list_public($filters, $per_page, ($page - 1) * $per_page),
            'categories'   => $this->Category_model->with_counts(),
            'agenda_side'  => $this->News_model->upcoming_agenda(3),
            'pagination'   => $this->pagination->create_links(),
        ));
    }

    public function detail($slug = '')
    {
        $item = $this->News_model->get_published($slug);
        if (!$item) {
            return $this->page_404();
        }
        $viewed = (array) $this->session->userdata('viewed_news');
        if (!in_array($item['id'], $viewed)) {
            $this->News_model->add_view($item['id']);
            $viewed[] = $item['id'];
            $this->session->set_userdata('viewed_news', array_slice($viewed, -50));
        }
        $this->render('news_detail', array(
            'page_title'       => $item['title'],
            'meta_description' => $item['excerpt'] ? $item['excerpt'] : excerpt($item['content'], 155),
            'meta_image'       => $item['thumbnail'] ? upload_url('news', $item['thumbnail']) : '',
            'item'             => $item,
            'related'          => $this->News_model->related($item),
            'categories'       => $this->Category_model->with_counts(),
        ));
    }
}
