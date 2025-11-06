<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'survey';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Public routes
$route['survey'] = 'survey/index';
$route['survey/mulai'] = 'survey/mulai';
$route['survey/proses'] = 'survey/proses';
$route['survey/terima-kasih'] = 'survey/terima_kasih';

// Admin routes
$route['admin'] = 'admin/dashboard/index';
$route['admin/login'] = 'admin/auth/login';
$route['admin/logout'] = 'admin/auth/logout';
$route['admin/dashboard'] = 'admin/dashboard/index';

// Pertanyaan routes
$route['admin/pertanyaan'] = 'admin/pertanyaan/index';
$route['admin/pertanyaan/add'] = 'admin/pertanyaan/add';
$route['admin/pertanyaan/edit/(:num)'] = 'admin/pertanyaan/edit/$1';
$route['admin/pertanyaan/delete/(:num)'] = 'admin/pertanyaan/delete/$1';
$route['admin/pertanyaan/import'] = 'admin/pertanyaan/import';
$route['admin/pertanyaan/export'] = 'admin/pertanyaan/export';

// Kategori routes
$route['admin/kategori'] = 'admin/kategori/index';
$route['admin/kategori/add'] = 'admin/kategori/add';
$route['admin/kategori/edit/(:num)'] = 'admin/kategori/edit/$1';
$route['admin/kategori/delete/(:num)'] = 'admin/kategori/delete/$1';

// Laporan routes
$route['admin/laporan'] = 'admin/laporan/index';
$route['admin/laporan/detail/(:num)'] = 'admin/laporan/detail/$1';
$route['admin/laporan/export'] = 'admin/laporan/export';
$route['admin/laporan/statistik'] = 'admin/laporan/statistik';
