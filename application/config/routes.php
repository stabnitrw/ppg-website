<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'home';
$route['404_override'] = 'home/not_found';
$route['translate_uri_dashes'] = TRUE;

// Publik
$route['kontak'] = 'home/kontak';
$route['halaman/(:any)'] = 'home/halaman/$1';
$route['berita'] = 'berita/index';
$route['berita/kategori/(:any)'] = 'berita/kategori/$1';
$route['berita/(:any)'] = 'berita/detail/$1';
$route['pengumuman'] = 'berita/pengumuman';
$route['agenda'] = 'berita/agenda';
$route['profil'] = 'profil/index';
$route['profil/(:any)'] = 'profil/section/$1';
$route['lapor-diri'] = 'lapor_diri/index';

// Admin
$route['admin'] = 'admin/dashboard';
$route['admin/login'] = 'admin/auth/login';
$route['admin/logout'] = 'admin/auth/logout';
