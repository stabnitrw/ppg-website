<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Public_Controller
{
    public function index()
    {
        $this->load->model(array('News_model', 'Page_model', 'Profile_model'));
        $pages = $this->Page_model->by_slugs(array('tentang', 'sambutan'));
        $leaders = $this->Profile_model->active(1);
        $this->render('home', array(
            'page_title'   => '',
            'about'        => isset($pages['tentang']) ? $pages['tentang'] : null,
            'welcome'      => isset($pages['sambutan']) ? $pages['sambutan'] : null,
            'leader'       => $leaders ? $leaders[0] : null,
            'news'         => $this->News_model->latest('berita', 4),
            'announcements'=> $this->News_model->latest('pengumuman', 4),
            'agenda'       => $this->News_model->upcoming_agenda(4),
            'slides'       => $this->db->select('title, slug, thumbnail, published_at')->where(array('status' => 'published', 'thumbnail IS NOT NULL' => NULL, 'published_at <=' => date('Y-m-d H:i:s')))
                ->order_by('published_at', 'DESC')->limit(5)->get('news')->result_array(),
        ));
    }

    public function kontak()
    {
        $this->render('contact', array('page_title' => 'Kontak'));
    }

    public function halaman($slug = '')
    {
        $this->load->model('Page_model');
        $page = $this->Page_model->by_slug($slug);
        if (!$page) {
            return $this->page_404();
        }
        $this->render('page', array('page_title' => $page['title'], 'page' => $page, 'meta_description' => excerpt($page['content'], 155)));
    }

    public function not_found()
    {
        $this->page_404();
    }
}
