<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends MY_Model
{
    protected $table = 'news_categories';
    protected $order = 'name ASC';

    public function with_counts()
    {
        return $this->db->select('c.*, COUNT(n.id) AS total')
            ->from('news_categories c')
            ->join('news n', "n.category_id = c.id AND n.status = 'published'", 'left')
            ->group_by('c.id')->order_by('c.name', 'ASC')->get()->result_array();
    }

    public function by_slug($slug)
    {
        return $this->db->get_where($this->table, array('slug' => $slug), 1)->row_array();
    }

    public function slug_exists($slug, $ignore_id = 0)
    {
        return $this->db->where('slug', $slug)->where('id !=', (int) $ignore_id)->count_all_results($this->table) > 0;
    }
}
