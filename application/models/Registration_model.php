<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registration_model extends MY_Model
{
    protected $table = 'self_reporting';

    /** Simpan pendaftaran beserta dokumen dalam satu transaksi, lalu buat nomor registrasi. */
    public function create(array $data, array $docs)
    {
        $this->db->trans_start();
        $data['reg_number'] = 'TMP-' . bin2hex(random_bytes(8));
        $this->db->insert($this->table, $data);
        $id = (int) $this->db->insert_id();
        $reg = sprintf('PPG-%s-%05d', date('Y'), $id);
        $this->db->where('id', $id)->update($this->table, array('reg_number' => $reg));
        foreach ($docs as $doc) {
            $doc['self_reporting_id'] = $id;
            $this->db->insert('self_reporting_documents', $doc);
        }
        $this->db->trans_complete();
        return $this->db->trans_status() ? $id : 0;
    }

    private function scope(array $f)
    {
        if (!empty($f['q'])) {
            $this->db->group_start()
                ->like('full_name', $f['q'])->or_like('reg_number', $f['q'])
                ->or_like('student_id', $f['q'])->or_like('email', $f['q'])->or_like('nik', $f['q'])
                ->group_end();
        }
        if (!empty($f['status'])) {
            $this->db->where('status', $f['status']);
        }
        if (!empty($f['category'])) {
            $this->db->where('ppg_category', $f['category']);
        }
        if (!empty($f['from'])) {
            $this->db->where('created_at >=', $f['from'] . ' 00:00:00');
        }
        if (!empty($f['to'])) {
            $this->db->where('created_at <=', $f['to'] . ' 23:59:59');
        }
    }

    public function search(array $f, $limit = 0, $offset = 0)
    {
        $this->scope($f);
        $this->db->order_by('id', 'DESC');
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        return $this->db->get($this->table)->result_array();
    }

    public function count_search(array $f)
    {
        $this->scope($f);
        return (int) $this->db->count_all_results($this->table);
    }

    /** Untuk ekspor: kembalikan objek hasil agar diproses baris per baris (hemat memori). */
    public function export_query(array $f)
    {
        $this->scope($f);
        return $this->db->order_by('id', 'ASC')->get($this->table);
    }

    public function documents($id)
    {
        return $this->db->where('self_reporting_id', (int) $id)->order_by('id', 'ASC')
            ->get('self_reporting_documents')->result_array();
    }

    public function document($doc_id)
    {
        return $this->db->get_where('self_reporting_documents', array('id' => (int) $doc_id), 1)->row_array();
    }

    public function find_by_reg($reg_number, $email)
    {
        return $this->db->where('reg_number', strtoupper(trim($reg_number)))->where('email', strtolower(trim($email)))
            ->limit(1)->get($this->table)->row_array();
    }

    public function hash_in_use($hash, $exclude_reg_id = 0)
    {
        return $this->db->where('file_hash', $hash)->where('self_reporting_id !=', (int) $exclude_reg_id)
            ->count_all_results('self_reporting_documents') > 0;
    }

    public function status_counts()
    {
        $out = array_fill_keys(array_keys(registration_statuses()), 0);
        foreach ($this->db->select('status, COUNT(*) AS total')->group_by('status')->get($this->table)->result_array() as $r) {
            $out[$r['status']] = (int) $r['total'];
        }
        return $out;
    }

    public function recent($limit = 6)
    {
        return $this->db->select('id, reg_number, full_name, ppg_category, status, created_at')
            ->order_by('id', 'DESC')->limit($limit)->get($this->table)->result_array();
    }

    public function exists_student($student_id)
    {
        return $this->db->where('student_id', $student_id)->where('status !=', 'ditolak')->count_all_results($this->table) > 0;
    }
}
