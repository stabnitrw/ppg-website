<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lapor_diri extends Public_Controller
{
    private $created_files = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Registration_model');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="invalid-feedback d-block">', '</div>');
    }

    public function index()
    {
        $this->show_form();
    }

    private function show_form($doc_errors = array(), $general_error = '')
    {
        $this->render('lapor_diri/form', array(
            'page_title'    => 'Lapor Diri Peserta PPG',
            'open'          => setting('lapor_diri_open', '1') === '1',
            'docs'          => doc_requirements(),
            'ppg_fields'    => $this->ppg_fields(),
            'doc_errors'    => $doc_errors,
            'general_error' => $general_error,
        ));
    }

    private function ppg_fields()
    {
        return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', setting('ppg_fields', 'Pendidikan Agama Buddha')))));
    }

    public function kirim()
    {
        if ($this->input->method() !== 'post') {
            redirect('lapor-diri');
        }
        if (setting('lapor_diri_open', '1') !== '1') {
            return $this->show_form();
        }
        $this->set_rules();
        if (!$this->form_validation->run()) {
            return $this->show_form(array(), 'Periksa kembali isian formulir yang ditandai.');
        }
        $student_id = trim($this->input->post('student_id'));
        if ($this->Registration_model->exists_student($student_id)) {
            return $this->show_form(array(), 'NIM / Nomor Peserta ' . $student_id . ' sudah melakukan lapor diri. Gunakan menu Cek Status untuk melihat status pendaftaran Anda.');
        }

        $docs = array();
        $errors = array();
        foreach (doc_requirements() as $key => $req) {
            $field = 'doc_' . $key;
            if (empty($_FILES[$field]['name'])) {
                if ($req['required']) {
                    $errors[$key] = $req['label'] . ' wajib diunggah.';
                }
                continue;
            }
            $doc = $this->store_document($field);
            if (is_string($doc)) {
                $errors[$key] = $doc;
            } else {
                $doc['doc_type'] = $key;
                $docs[] = $doc;
            }
        }
        if ($errors) {
            $this->rollback_files();
            return $this->show_form($errors, 'Terdapat dokumen yang belum valid. Silakan pilih ulang semua dokumen.');
        }

        $p = $this->input->post(NULL, TRUE);
        $id = $this->Registration_model->create(array(
            'student_id'      => $student_id,
            'nik'             => trim($p['nik']),
            'full_name'       => trim($p['full_name']),
            'birth_place'     => trim($p['birth_place']),
            'birth_date'      => $p['birth_date'],
            'gender'          => $p['gender'],
            'address'         => trim($p['address']),
            'phone'           => trim($p['phone']),
            'email'           => strtolower(trim($p['email'])),
            'ppg_category'    => $p['ppg_category'],
            'ppg_field'       => $p['ppg_field'],
            'last_education'  => $p['last_education'],
            'university'      => trim($p['university']),
            'study_program'   => trim($p['study_program']),
            'graduation_year' => (int) $p['graduation_year'],
            'gpa'             => $p['gpa'] !== '' ? (float) $p['gpa'] : null,
            'school_name'     => trim((string) $p['school_name']) ?: null,
            'ip_address'      => $this->input->ip_address(),
        ), $docs);

        if (!$id) {
            $this->rollback_files();
            return $this->show_form(array(), 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }
        $this->session->set_userdata('last_registration_id', $id);
        redirect('lapor-diri/sukses');
    }

    private function set_rules()
    {
        $fv = $this->form_validation;
        $fields = implode(',', $this->ppg_fields());
        $fv->set_rules('student_id', 'NIM / Nomor Peserta', 'trim|required|max_length[30]|regex_match[/^[A-Za-z0-9.\-\/]+$/]');
        $fv->set_rules('nik', 'NIK', 'trim|required|exact_length[16]|numeric');
        $fv->set_rules('full_name', 'Nama Lengkap', 'trim|required|min_length[3]|max_length[150]');
        $fv->set_rules('birth_place', 'Tempat Lahir', 'trim|required|max_length[80]');
        $fv->set_rules('birth_date', 'Tanggal Lahir', 'trim|required|callback_valid_birth_date');
        $fv->set_rules('gender', 'Jenis Kelamin', 'required|in_list[L,P]');
        $fv->set_rules('address', 'Alamat', 'trim|required|min_length[10]|max_length[500]');
        $fv->set_rules('phone', 'Nomor HP/WhatsApp', array('trim', 'required', 'regex_match[/^(\+62|62|0)[0-9]{8,13}$/]'));
        $fv->set_rules('email', 'Email', 'trim|required|valid_email|max_length[120]');
        $fv->set_rules('ppg_category', 'Jenis Program PPG', 'required|in_list[prajabatan,dalam_jabatan]');
        $fv->set_rules('ppg_field', 'Bidang Studi PPG', 'required|in_list[' . $fields . ']');
        $fv->set_rules('last_education', 'Pendidikan Terakhir', 'required|in_list[S1,D4,S2,S3]');
        $fv->set_rules('university', 'Perguruan Tinggi Asal', 'trim|required|max_length[150]');
        $fv->set_rules('study_program', 'Program Studi', 'trim|required|max_length[150]');
        $fv->set_rules('graduation_year', 'Tahun Lulus', 'trim|required|integer|greater_than_equal_to[1970]|less_than_equal_to[' . date('Y') . ']');
        $fv->set_rules('gpa', 'IPK', 'trim|numeric|greater_than_equal_to[0]|less_than_equal_to[4]');
        $fv->set_rules('school_name', 'Sekolah Tempat Mengajar', 'trim|max_length[150]' . ($this->input->post('ppg_category') === 'dalam_jabatan' ? '|required' : ''));
        $fv->set_rules('agreement', 'Pernyataan kebenaran data', 'required');
    }

    public function valid_birth_date($date)
    {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        if (!$d || $d->format('Y-m-d') !== $date || $date > date('Y-m-d', strtotime('-17 years')) || $date < '1940-01-01') {
            $this->form_validation->set_message('valid_birth_date', '{field} tidak valid.');
            return FALSE;
        }
        return TRUE;
    }

    /** Simpan dokumen ke storage privat. Return array data dokumen atau string pesan error. */
    private function store_document($field)
    {
        $tmp_dir = STORAGEPATH . 'documents/tmp/';
        if (!is_dir($tmp_dir)) {
            mkdir($tmp_dir, 0750, true);
        }
        $this->load->library('upload');
        $this->upload->initialize(array(
            'upload_path'      => $tmp_dir,
            'allowed_types'    => setting('doc_allowed_ext', 'pdf|jpg|jpeg|png'),
            'max_size'         => (int) setting('doc_max_size_kb', 2048),
            'encrypt_name'     => TRUE,
            'file_ext_tolower' => TRUE,
            'detect_mime'      => TRUE,
            'mod_mime_fix'     => TRUE,
        ), TRUE);
        if (!$this->upload->do_upload($field)) {
            return strip_tags($this->upload->display_errors());
        }
        $f = $this->upload->data();
        if (!$this->valid_content($f)) {
            @unlink($f['full_path']);
            return 'Isi file tidak sesuai dengan jenisnya.';
        }
        $hash = sha1_file($f['full_path']);
        $rel = substr($hash, 0, 2) . '/' . $hash . $f['file_ext'];
        $dest = STORAGEPATH . 'documents/' . $rel;
        if (!is_dir(dirname($dest))) {
            mkdir(dirname($dest), 0750, true);
        }
        if (is_file($dest)) {
            @unlink($f['full_path']);
        } else {
            rename($f['full_path'], $dest);
            $this->created_files[] = $dest;
        }
        return array(
            'original_name' => mb_substr($f['client_name'], 0, 200),
            'file_path'     => $rel,
            'file_hash'     => $hash,
            'file_size'     => (int) round($f['file_size'] * 1024),
            'mime_type'     => $f['file_type'],
        );
    }

    private function valid_content(array $f)
    {
        if ($f['file_ext'] === '.pdf') {
            $fh = fopen($f['full_path'], 'rb');
            $head = fread($fh, 5);
            fclose($fh);
            return $head === '%PDF-';
        }
        if (in_array($f['file_ext'], array('.jpg', '.jpeg', '.png'), TRUE)) {
            return (bool) @getimagesize($f['full_path']);
        }
        return TRUE;
    }

    private function rollback_files()
    {
        foreach ($this->created_files as $file) {
            @unlink($file);
        }
        $this->created_files = array();
    }

    public function sukses()
    {
        $id = (int) $this->session->userdata('last_registration_id');
        $reg = $id ? $this->Registration_model->get($id) : null;
        if (!$reg) {
            redirect('lapor-diri');
        }
        $this->render('lapor_diri/success', array(
            'page_title' => 'Lapor Diri Berhasil',
            'reg'        => $reg,
            'documents'  => $this->Registration_model->documents($id),
            'docs'       => doc_requirements(),
        ));
    }

    public function cek()
    {
        $result = null;
        $searched = FALSE;
        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('reg_number', 'Nomor Registrasi', 'trim|required|max_length[30]');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
            if ($this->form_validation->run()) {
                $searched = TRUE;
                $result = $this->Registration_model->find_by_reg($this->input->post('reg_number'), $this->input->post('email'));
            }
        }
        $this->render('lapor_diri/check', array(
            'page_title' => 'Cek Status Lapor Diri',
            'result'     => $result,
            'searched'   => $searched,
            'documents'  => $result ? $this->Registration_model->documents($result['id']) : array(),
            'docs'       => doc_requirements(),
        ));
    }
}
