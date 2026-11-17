<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting_model extends CI_Model
{
    public function get_all()
    {
        $out = array();
        foreach ($this->db->get('website_settings')->result_array() as $row) {
            $out[$row['setting_key']] = $row['setting_value'];
        }
        return $out;
    }

    public function save(array $data)
    {
        foreach ($data as $key => $value) {
            $this->db->replace('website_settings', array('setting_key' => $key, 'setting_value' => $value));
        }
    }
}
