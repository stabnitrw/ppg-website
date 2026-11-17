<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profil extends Public_Controller
{
    private $sections = array(
        'sejarah'             => 'Sejarah & Latar Belakang',
        'visi-misi'           => 'Visi, Misi & Tujuan',
        'struktur-organisasi' => 'Struktur Organisasi',
        'pimpinan'            => 'Pimpinan Fakultas',
        'layanan'             => 'Program & Layanan',
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Page_model');
        $this->data['sections'] = $this->sections;
    }

    public function index()
    {
        $this->load->model('Profile_model');
        $pages = $this->Page_model->by_slugs(array('tentang', 'sambutan'));
        $leaders = $this->Profile_model->active(1);
        $this->render('profil/index', array(
            'page_title' => 'Profil Fakultas',
            'current'    => '',
            'about'      => isset($pages['tentang']) ? $pages['tentang'] : null,
            'welcome'    => isset($pages['sambutan']) ? $pages['sambutan'] : null,
            'leader'     => $leaders ? $leaders[0] : null,
        ));
    }

    public function section($slug = '')
    {
        if (!isset($this->sections[$slug])) {
            return $this->page_404();
        }
        $data = array('page_title' => $this->sections[$slug], 'current' => $slug);
        switch ($slug) {
            case 'struktur-organisasi':
                $this->load->model('Structure_model');
                $data['tree'] = $this->Structure_model->tree();
                break;
            case 'pimpinan':
                $this->load->model('Profile_model');
                $data['profiles'] = $this->Profile_model->active();
                break;
            default:
                $data['page'] = $this->Page_model->by_slug($slug);
        }
        $this->render('profil/' . str_replace('-', '_', $slug), $data);
    }
}
