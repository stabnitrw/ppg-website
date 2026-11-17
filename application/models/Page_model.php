<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page_model extends MY_Model
{
    protected $table = 'pages';
    protected $order = 'is_system DESC, title ASC';

    public function by_slug($slug)
    {
        return $this->db->get_where($this->table, array('slug' => $slug), 1)->row_array();
    }

    /** Ambil beberapa halaman sekaligus (1 query), diindeks berdasarkan slug. */
    public function by_slugs(array $slugs)
    {
        $out = array();
        foreach ($this->db->where_in('slug', $slugs)->get($this->table)->result_array() as $row) {
            $out[$row['slug']] = $row;
        }
        return $out;
    }

    public function slug_exists($slug, $ignore_id = 0)
    {
        return $this->db->where('slug', $slug)->where('id !=', (int) $ignore_id)->count_all_results($this->table) > 0;
    }
}
