<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model
{
    protected $table = '';
    protected $order = 'id DESC';

    public function get($id)
    {
        return $this->db->get_where($this->table, array('id' => (int) $id), 1)->row_array();
    }

    public function all()
    {
        return $this->db->order_by($this->order)->get($this->table)->result_array();
    }

    public function insert(array $data)
    {
        $this->db->insert($this->table, $data);
        return (int) $this->db->insert_id();
    }

    public function update($id, array $data)
    {
        return $this->db->where('id', (int) $id)->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db->where('id', (int) $id)->delete($this->table);
    }

    public function count_all()
    {
        return (int) $this->db->count_all($this->table);
    }
}
