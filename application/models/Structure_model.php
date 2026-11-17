<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Structure_model extends MY_Model
{
    protected $table = 'organizational_structure';
    protected $order = 'sort_order ASC, id ASC';

    /** Susun data menjadi pohon hierarki dari satu query. */
    public function tree()
    {
        $rows = $this->all();
        $by_parent = array();
        foreach ($rows as $row) {
            $by_parent[(int) $row['parent_id']][] = $row;
        }
        return $this->build($by_parent, 0);
    }

    private function build(array $by_parent, $parent)
    {
        $out = array();
        if (!isset($by_parent[$parent])) {
            return $out;
        }
        foreach ($by_parent[$parent] as $row) {
            $row['children'] = $this->build($by_parent, (int) $row['id']);
            $out[] = $row;
        }
        return $out;
    }

    /** Daftar datar dengan level untuk tabel admin & pilihan atasan. */
    public function flat(array $tree = null, $level = 0)
    {
        $out = array();
        foreach ($tree === null ? $this->tree() : $tree as $node) {
            $node['level'] = $level;
            $children = $node['children'];
            unset($node['children']);
            $out[] = $node;
            $out = array_merge($out, $this->flat($children, $level + 1));
        }
        return $out;
    }

    public function descendant_ids($id)
    {
        $ids = array();
        foreach ($this->db->select('id')->where('parent_id', (int) $id)->get($this->table)->result_array() as $row) {
            $ids[] = (int) $row['id'];
            $ids = array_merge($ids, $this->descendant_ids($row['id']));
        }
        return $ids;
    }
}
