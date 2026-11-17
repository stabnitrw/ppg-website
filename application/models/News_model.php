<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News_model extends MY_Model
{
    protected $table = 'news';

    private function public_scope(array $f)
    {
        $this->db->from('news n')
            ->join('news_categories c', 'c.id = n.category_id', 'left')
            ->where('n.status', 'published')
            ->where('n.published_at <=', date('Y-m-d H:i:s'));
        if (!empty($f['type'])) {
            $this->db->where('n.type', $f['type']);
        }
        if (!empty($f['category_id'])) {
            $this->db->where('n.category_id', (int) $f['category_id']);
        }
        if (!empty($f['q'])) {
            $this->db->group_start()->like('n.title', $f['q'])->or_like('n.excerpt', $f['q'])->group_end();
        }
    }

    public function list_public(array $f, $limit, $offset = 0)
    {
        $this->public_scope($f);
        return $this->db->select('n.id, n.type, n.title, n.slug, n.excerpt, n.content, n.thumbnail, n.event_date, n.event_location, n.published_at, n.views, c.name AS category_name, c.slug AS category_slug')
            ->order_by('n.published_at', 'DESC')->limit($limit, $offset)->get()->result_array();
    }

    public function count_public(array $f)
    {
        $this->public_scope($f);
        return (int) $this->db->count_all_results();
    }

    public function latest($type, $limit)
    {
        return $this->list_public(array('type' => $type), $limit);
    }

    public function upcoming_agenda($limit)
    {
        $rows = $this->db->select('id, title, slug, excerpt, event_date, event_location')
            ->where(array('type' => 'agenda', 'status' => 'published', 'event_date >=' => date('Y-m-d')))
            ->order_by('event_date', 'ASC')->limit($limit)->get('news')->result_array();
        return $rows ? $rows : $this->db->select('id, title, slug, excerpt, event_date, event_location')
            ->where(array('type' => 'agenda', 'status' => 'published'))
            ->order_by('event_date', 'DESC')->limit($limit)->get('news')->result_array();
    }

    public function get_published($slug)
    {
        return $this->db->select('n.*, c.name AS category_name, c.slug AS category_slug, u.name AS author_name')
            ->from('news n')
            ->join('news_categories c', 'c.id = n.category_id', 'left')
            ->join('admin_users u', 'u.id = n.author_id', 'left')
            ->where(array('n.slug' => $slug, 'n.status' => 'published', 'n.published_at <=' => date('Y-m-d H:i:s')))
            ->limit(1)->get()->row_array();
    }

    public function related(array $news, $limit = 3)
    {
        $this->public_scope(array('type' => $news['type']));
        return $this->db->select('n.title, n.slug, n.thumbnail, n.published_at')
            ->where('n.id !=', $news['id'])
            ->order_by('n.published_at', 'DESC')->limit($limit)->get()->result_array();
    }

    public function add_view($id)
    {
        $this->db->set('views', 'views + 1', FALSE)->where('id', (int) $id)->update('news');
    }

    private function admin_scope(array $f)
    {
        $this->db->from('news n')->join('news_categories c', 'c.id = n.category_id', 'left')
            ->join('admin_users u', 'u.id = n.author_id', 'left');
        if (!empty($f['type'])) {
            $this->db->where('n.type', $f['type']);
        }
        if (!empty($f['status'])) {
            $this->db->where('n.status', $f['status']);
        }
        if (!empty($f['q'])) {
            $this->db->like('n.title', $f['q']);
        }
    }

    public function list_admin(array $f, $limit, $offset)
    {
        $this->admin_scope($f);
        return $this->db->select('n.id, n.type, n.title, n.slug, n.status, n.views, n.published_at, n.created_at, c.name AS category_name, u.name AS author_name')
            ->order_by('n.id', 'DESC')->limit($limit, $offset)->get()->result_array();
    }

    public function count_admin(array $f)
    {
        $this->admin_scope($f);
        return (int) $this->db->count_all_results();
    }

    public function count_by($type, $status = 'published')
    {
        return (int) $this->db->where(array('type' => $type, 'status' => $status))->count_all_results('news');
    }

    public function unique_slug($title, $ignore_id = 0)
    {
        $base = url_title(convert_accented_characters($title), '-', TRUE);
        $base = $base !== '' ? substr($base, 0, 200) : 'berita';
        $slug = $base;
        $i = 2;
        while ($this->db->where('slug', $slug)->where('id !=', (int) $ignore_id)->count_all_results('news') > 0) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}
