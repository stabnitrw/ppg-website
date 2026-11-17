<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile_model extends MY_Model
{
    protected $table = 'faculty_profiles';
    protected $order = 'sort_order ASC, id ASC';

    public function active($limit = 0)
    {
        $this->db->where('is_active', 1)->order_by($this->order);
        if ($limit) {
            $this->db->limit($limit);
        }
        return $this->db->get($this->table)->result_array();
    }
}
