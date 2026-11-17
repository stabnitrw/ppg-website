<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['page_query_string'] = TRUE;
$config['query_string_segment'] = 'page';
$config['use_page_numbers'] = TRUE;
$config['reuse_query_string'] = TRUE;
$config['num_links'] = 2;
$config['full_tag_open'] = '<nav aria-label="Navigasi halaman"><ul class="pagination justify-content-center flex-wrap">';
$config['full_tag_close'] = '</ul></nav>';
$config['first_link'] = '&laquo;';
$config['last_link'] = '&raquo;';
$config['next_link'] = '&rsaquo;';
$config['prev_link'] = '&lsaquo;';
$config['first_tag_open'] = $config['last_tag_open'] = $config['next_tag_open'] = $config['prev_tag_open'] = $config['num_tag_open'] = '<li class="page-item">';
$config['first_tag_close'] = $config['last_tag_close'] = $config['next_tag_close'] = $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
$config['cur_tag_open'] = '<li class="page-item active" aria-current="page"><span class="page-link">';
$config['cur_tag_close'] = '</span></li>';
$config['attributes'] = array('class' => 'page-link');
