<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Admin_Controller
{
    public function index()
    {
        $this->load->model('News_model');
        $this->render('dashboard', array(
            'page_title'    => 'Dashboard',
            'total_news'    => $this->News_model->count_by('berita'),
            'total_announce'=> $this->News_model->count_by('pengumuman'),
            'total_agenda'  => $this->News_model->count_by('agenda'),
            'total_drafts'  => (int) $this->db->where('status', 'draft')->count_all_results('news'),
            'recent_news'   => $this->News_model->list_admin(array(), 5, 0),
        ));
    }
}
