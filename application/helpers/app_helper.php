<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function e($str)
{
    return html_escape((string) $str);
}

function setting($key, $default = '')
{
    $ci = get_instance();
    return (isset($ci->settings[$key]) && $ci->settings[$key] !== '') ? $ci->settings[$key] : $default;
}

function upload_url($folder, $file)
{
    return base_url('assets/uploads/' . $folder . '/' . $file);
}

function logo_url($key = 'logo')
{
    $file = setting($key);
    if ($file) {
        return upload_url('settings', $file);
    }
    return base_url('assets/img/logo-stabn.png');
}

function tgl_indo($date, $with_time = FALSE)
{
    if (!$date || $date === '0000-00-00') {
        return '-';
    }
    $bulan = array(1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
    $ts = strtotime($date);
    $out = date('j', $ts) . ' ' . $bulan[(int) date('n', $ts)] . ' ' . date('Y', $ts);
    return $with_time ? $out . ', ' . date('H:i', $ts) . ' WIB' : $out;
}

function excerpt($html, $limit = 140)
{
    $text = trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags(preg_replace('/<\/(p|li|h\d|div)>|<br\s*\/?>/i', ' ', (string) $html)), ENT_QUOTES, 'UTF-8')));
    if (mb_strlen($text) <= $limit) {
        return $text;
    }
    return rtrim(mb_substr($text, 0, $limit)) . '…';
}

function news_types()
{
    return array('berita' => 'Berita', 'pengumuman' => 'Pengumuman', 'agenda' => 'Agenda Akademik');
}

function registration_statuses()
{
    return array(
        'baru'         => array('Baru', 'secondary'),
        'diverifikasi' => array('Sedang Diverifikasi', 'info'),
        'perlu_revisi' => array('Perlu Perbaikan', 'warning'),
        'diterima'     => array('Diterima', 'success'),
        'ditolak'      => array('Ditolak', 'danger'),
    );
}

function status_badge($status)
{
    $all = registration_statuses();
    $s = isset($all[$status]) ? $all[$status] : array($status, 'secondary');
    return '<span class="badge text-bg-' . $s[1] . '">' . e($s[0]) . '</span>';
}

/** Persyaratan dokumen dari pengaturan: baris "kode|Label|1" (1 = wajib). */
function doc_requirements()
{
    $items = array();
    foreach (preg_split('/\r\n|\r|\n/', setting('doc_requirements')) as $line) {
        $parts = array_map('trim', explode('|', $line));
        if (count($parts) >= 2 && preg_match('/^[a-z0-9_]+$/', $parts[0])) {
            $items[$parts[0]] = array('label' => $parts[1], 'required' => !isset($parts[2]) || $parts[2] === '1');
        }
    }
    return $items;
}

function is_active_menu($segment, $index = 1)
{
    return get_instance()->uri->segment($index) === $segment ? 'active' : '';
}

function format_bytes($bytes)
{
    if ($bytes < 1024) {
        return (int) $bytes . ' B';
    }
    return $bytes >= 1048576 ? round($bytes / 1048576, 2) . ' MB' : round($bytes / 1024) . ' KB';
}

function flash_alerts()
{
    $ci = get_instance();
    $out = '';
    foreach (array('success' => 'success', 'error' => 'danger') as $key => $class) {
        if ($msg = $ci->session->flashdata($key)) {
            $out .= '<div class="alert alert-' . $class . ' alert-dismissible fade show" role="alert" data-testid="flash-' . $key . '">'
                . e($msg) . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button></div>';
        }
    }
    return $out;
}

function avatar($name, $photo, $class = 'avatar')
{
    if ($photo) {
        return '<img src="' . e(upload_url('profiles', $photo)) . '" alt="' . e($name) . '" class="' . $class . '" loading="lazy">';
    }
    $clean = preg_replace('/\b(Dr|Prof|Drs|Dra|Ir|H|Hj)\.?\s+/i', '', strtok($name, ','));
    $words = preg_split('/\s+/', trim($clean));
    $ini = strtoupper(mb_substr($words[0], 0, 1) . (isset($words[1]) ? mb_substr($words[1], 0, 1) : ''));
    return '<div class="' . $class . ' avatar-initials" aria-hidden="true">' . e($ini) . '</div>';
}

function news_thumb($item, $class = '')
{
    if (!empty($item['thumbnail'])) {
        return '<img src="' . e(upload_url('news', $item['thumbnail'])) . '" alt="' . e($item['title']) . '" class="' . $class . '" loading="lazy">';
    }
    $icons = array('berita' => 'bi-newspaper', 'pengumuman' => 'bi-megaphone', 'agenda' => 'bi-calendar-event');
    $icon = isset($icons[$item['type']]) ? $icons[$item['type']] : 'bi-newspaper';
    return '<div class="thumb-placeholder ' . $class . '"><i class="bi ' . $icon . '"></i></div>';
}

function news_url($item)
{
    return site_url('berita/' . $item['slug']);
}

/** Ubah pengaturan multi-baris "a|b|c" menjadi array baris. */
function setting_lines($key)
{
    $rows = array();
    foreach (preg_split('/\r\n|\r|\n/', setting($key)) as $line) {
        if (trim($line) !== '') {
            $rows[] = array_map('trim', explode('|', $line));
        }
    }
    return $rows;
}

function link_url($url)
{
    return preg_match('#^https?://#i', $url) ? $url : site_url(ltrim($url, '/'));
}
