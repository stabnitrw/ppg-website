<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lapor_diri extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Registration_model');
    }

    private function filters()
    {
        $f = array(
            'q'        => trim((string) $this->input->get('q', TRUE)),
            'status'   => (string) $this->input->get('status', TRUE),
            'category' => (string) $this->input->get('category', TRUE),
            'from'     => (string) $this->input->get('from', TRUE),
            'to'       => (string) $this->input->get('to', TRUE),
        );
        if (!array_key_exists($f['status'], registration_statuses())) {
            $f['status'] = '';
        }
        if (!in_array($f['category'], array('prajabatan', 'dalam_jabatan'), TRUE)) {
            $f['category'] = '';
        }
        foreach (array('from', 'to') as $k) {
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $f[$k])) {
                $f[$k] = '';
            }
        }
        return $f;
    }

    public function index()
    {
        $this->load->library('pagination');
        $f = $this->filters();
        $per = (int) setting('admin_per_page', 15);
        $page = max(1, (int) $this->input->get('page'));
        $total = $this->Registration_model->count_search($f);
        $this->pagination->initialize(array('base_url' => site_url('admin/lapor-diri'), 'total_rows' => $total, 'per_page' => $per));
        $this->render('registrations/index', array(
            'page_title'    => 'Data Lapor Diri',
            'items'         => $this->Registration_model->search($f, $per, ($page - 1) * $per),
            'total'         => $total,
            'f'             => $f,
            'status_counts' => $this->Registration_model->status_counts(),
            'pagination'    => $this->pagination->create_links(),
            'export_query'  => http_build_query(array_filter($f)),
        ));
    }

    public function detail($id = 0)
    {
        $reg = $this->Registration_model->get($id);
        if (!$reg) {
            show_404();
        }
        $this->render('registrations/detail', array(
            'page_title' => 'Detail Lapor Diri',
            'reg'        => $reg,
            'documents'  => $this->Registration_model->documents($id),
            'docs'       => doc_requirements(),
        ));
    }

    public function update($id = 0)
    {
        $this->require_post();
        $reg = $this->Registration_model->get($id);
        if (!$reg) {
            show_404();
        }
        $status = $this->input->post('status');
        if (!array_key_exists($status, registration_statuses())) {
            $this->session->set_flashdata('error', 'Status tidak valid.');
        } else {
            $this->Registration_model->update($id, array(
                'status'      => $status,
                'public_note' => mb_substr(trim((string) $this->input->post('public_note', TRUE)), 0, 2000) ?: null,
                'admin_notes' => mb_substr(trim((string) $this->input->post('admin_notes', TRUE)), 0, 5000) ?: null,
            ));
            $this->session->set_flashdata('success', 'Status pendaftaran ' . $reg['reg_number'] . ' berhasil diperbarui.');
        }
        redirect('admin/lapor-diri/detail/' . (int) $id);
    }

    public function unduh($doc_id = 0)
    {
        $doc = $this->Registration_model->document($doc_id);
        $base = realpath(STORAGEPATH . 'documents');
        $path = $doc ? realpath($base . '/' . $doc['file_path']) : FALSE;
        if (!$doc || !$path || strpos($path, $base . DIRECTORY_SEPARATOR) !== 0 || !is_file($path)) {
            show_404();
        }
        $reg = $this->Registration_model->get($doc['self_reporting_id']);
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $name = preg_replace('/[^A-Za-z0-9_\-]/', '_', $reg['reg_number'] . '_' . $doc['doc_type']) . '.' . $ext;
        $inline = $this->input->get('lihat') && in_array($ext, array('pdf', 'jpg', 'jpeg', 'png'), TRUE);
        if (ob_get_level()) {
            ob_end_clean();
        }
        header('Content-Type: ' . $doc['mime_type']);
        header('Content-Disposition: ' . ($inline ? 'inline' : 'attachment') . '; filename="' . $name . '"');
        header('Content-Length: ' . filesize($path));
        header('X-Content-Type-Options: nosniff');
        header('Cache-Control: private, no-store');
        readfile($path);
        exit;
    }

    public function export()
    {
        $f = $this->filters();
        $query = $this->Registration_model->export_query($f);
        $statuses = registration_statuses();
        $cats = array('prajabatan' => 'PPG Prajabatan', 'dalam_jabatan' => 'PPG Dalam Jabatan');
        if (ob_get_level()) {
            ob_end_clean();
        }
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="lapor-diri-' . date('Ymd-His') . '.csv"');
        header('Cache-Control: no-store');
        $out = fopen('php://output', 'w');
        fwrite($out, "\xEF\xBB\xBF");
        fputcsv($out, array('No. Registrasi', 'NIM/No. Peserta', 'NIK', 'Nama Lengkap', 'Tempat Lahir', 'Tanggal Lahir', 'Jenis Kelamin', 'Alamat', 'No. HP', 'Email', 'Program', 'Bidang Studi', 'Pendidikan Terakhir', 'Perguruan Tinggi', 'Program Studi', 'Tahun Lulus', 'IPK', 'Sekolah Mengajar', 'Status', 'Catatan Peserta', 'Catatan Internal', 'Tanggal Daftar'), ';');
        while ($r = $query->unbuffered_row('array')) {
            fputcsv($out, array(
                $r['reg_number'], "'" . $r['student_id'], "'" . $r['nik'], $r['full_name'], $r['birth_place'], $r['birth_date'],
                $r['gender'] === 'L' ? 'Laki-laki' : 'Perempuan', $r['address'], "'" . $r['phone'], $r['email'],
                $cats[$r['ppg_category']], $r['ppg_field'], $r['last_education'], $r['university'], $r['study_program'],
                $r['graduation_year'], $r['gpa'], $r['school_name'], $statuses[$r['status']][0], $r['public_note'], $r['admin_notes'], $r['created_at'],
            ), ';');
        }
        fclose($out);
        exit;
    }

    public function hapus($id = 0)
    {
        $this->require_post();
        $this->require_admin();
        $reg = $this->Registration_model->get($id);
        if ($reg) {
            $docs = $this->Registration_model->documents($id);
            $this->Registration_model->delete($id);
            foreach ($docs as $d) {
                if (!$this->Registration_model->hash_in_use($d['file_hash'], $id)) {
                    @unlink(STORAGEPATH . 'documents/' . $d['file_path']);
                }
            }
            $this->session->set_flashdata('success', 'Data lapor diri ' . $reg['reg_number'] . ' beserta dokumennya telah dihapus.');
        }
        redirect('admin/lapor-diri');
    }
}
