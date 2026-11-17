<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengaturan extends Admin_Controller
{
    private $text_keys = array(
        'site_name', 'site_short_name', 'institution_name', 'site_tagline', 'meta_description',
        'hero_title', 'hero_subtitle', 'visi', 'misi',
        'address', 'phone', 'whatsapp', 'email', 'office_hours', 'maps_embed', 'facebook', 'instagram', 'youtube',
        'stat_1_value', 'stat_1_label', 'stat_2_value', 'stat_2_label', 'stat_3_value', 'stat_3_label', 'stat_4_value', 'stat_4_label',
        'lapor_diri_info', 'ppg_fields', 'doc_requirements',
        'topbar_text', 'hero_badge', 'lapor_diri_period', 'lapor_diri_hours', 'stats_title', 'stats_subtitle', 'ppg_stages',
        'akreditasi_status', 'akreditasi_lembaga', 'akreditasi_sk', 'akreditasi_masa', 'service_links',
    );
    private $allowed_doc_ext = array('pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx');

    public function __construct()
    {
        parent::__construct();
        $this->require_admin();
        $this->load->library('form_validation');
    }

    public function index()
    {
        $fv = $this->form_validation;
        $fv->set_rules('site_name', 'Nama Website', 'trim|required|max_length[150]');
        $fv->set_rules('email', 'Email', 'trim|required|valid_email');
        $fv->set_rules('doc_max_size_kb', 'Maks. ukuran dokumen', 'required|is_natural_no_zero|less_than_equal_to[20480]');
        $fv->set_rules('image_max_size_kb', 'Maks. ukuran gambar', 'required|is_natural_no_zero|less_than_equal_to[10240]');
        $fv->set_rules('image_max_width', 'Lebar maks. gambar', 'required|is_natural_no_zero|greater_than_equal_to[400]|less_than_equal_to[3000]');
        $fv->set_rules('image_quality', 'Kualitas gambar', 'required|is_natural_no_zero|greater_than_equal_to[40]|less_than_equal_to[95]');
        $fv->set_rules('news_per_page', 'Berita per halaman', 'required|is_natural_no_zero|less_than_equal_to[50]');
        $fv->set_rules('admin_per_page', 'Data per halaman (admin)', 'required|is_natural_no_zero|less_than_equal_to[100]');
        $fv->set_rules('maps_embed', 'URL Google Maps', 'trim|max_length[1000]|callback_https_url');
        $fv->set_rules('doc_requirements', 'Persyaratan dokumen', 'trim|required');
        $fv->set_rules('ppg_fields', 'Bidang studi', 'trim|required');

        $error = '';
        if ($fv->run()) {
            $save = array();
            foreach ($this->text_keys as $k) {
                $save[$k] = trim(str_replace("\r\n", "\n", (string) $this->input->post($k, TRUE)));
            }
            foreach (array('doc_max_size_kb', 'image_max_size_kb', 'image_max_width', 'image_quality', 'news_per_page', 'admin_per_page') as $k) {
                $save[$k] = (string) (int) $this->input->post($k);
            }
            $ext = array_intersect($this->allowed_doc_ext, (array) $this->input->post('doc_allowed_ext'));
            $save['doc_allowed_ext'] = $ext ? implode('|', $ext) : 'pdf';
            $save['lapor_diri_open'] = $this->input->post('lapor_diri_open') ? '1' : '0';
            $save['ppg_fields'] = str_replace(',', ' ', $save['ppg_fields']);

            foreach (array('logo', 'logo_stabn', 'hero_image') as $k) {
                $old = setting($k);
                $file = $this->upload_image($k, 'settings', $old ?: null);
                if ($file === FALSE) {
                    $error = 'Upload ' . $k . ': ' . $this->upload_error;
                    break;
                }
                if ($this->input->post('remove_' . $k)) {
                    $file = null;
                }
                $save[$k] = (string) $file;
            }
            if (!$error) {
                $this->Setting_model->save($save);
                foreach (array('logo', 'logo_stabn', 'hero_image') as $k) {
                    if (setting($k) && setting($k) !== $save[$k]) {
                        $this->cleanup_file('settings', setting($k), 'website_settings', 'setting_value');
                    }
                }
                $this->session->set_flashdata('success', 'Pengaturan website berhasil disimpan.');
                redirect('admin/pengaturan' . ($this->input->post('tab') ? '#' . preg_replace('/[^a-z\-]/', '', $this->input->post('tab')) : ''));
            }
        }
        $this->render('settings', array(
            'page_title'      => 'Pengaturan Website',
            'error'           => $error,
            'allowed_doc_ext' => $this->allowed_doc_ext,
        ));
    }

    public function https_url($url)
    {
        if ($url !== '' && !preg_match('#^https://#i', $url)) {
            $this->form_validation->set_message('https_url', '{field} harus diawali https://');
            return FALSE;
        }
        return TRUE;
    }
}
