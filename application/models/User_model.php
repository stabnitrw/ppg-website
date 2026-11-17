<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends MY_Model
{
    protected $table = 'admin_users';
    protected $order = 'role ASC, name ASC';

    public function by_login($login)
    {
        return $this->db->group_start()->where('username', $login)->or_where('email', $login)->group_end()
            ->limit(1)->get($this->table)->row_array();
    }

    public function exists($field, $value, $ignore_id = 0)
    {
        return $this->db->where($field, $value)->where('id !=', (int) $ignore_id)->count_all_results($this->table) > 0;
    }

    public function count_admins()
    {
        return (int) $this->db->where(array('role' => 'admin', 'is_active' => 1))->count_all_results($this->table);
    }
}
