<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['registrasi'] = 'welcome/registrasi';
$route['registrasi-akun'] = 'welcome/aksi_registrasi';
$route['lupa-kata-kunci'] = 'welcome/lupa_password';
$route['kelola-akun'] = 'welcome/akun';
$route['ubah-data-akun'] = 'welcome/ubah_akun';
$route['keluar-sistem'] = 'welcome/logout';

// Mahasiswa
$route['mahasiswa/kelola-akun'] = 'mahasiswa/akun';
$route['mahasiswa/ubah-data-akun'] = 'mahasiswa/ubah_akun';
$route['mahasiswa/kelola-biodata'] = 'mahasiswa/biodata';
$route['mahasiswa/ubah-data-biodata'] = 'mahasiswa/ubah_biodata';
$route['mahasiswa/tugas-akhir/tema'] = 'mahasiswa/tugas_akhir';
$route['mahasiswa/tugas-akhir/tema/form'] = 'mahasiswa/form_tugas_akhir';
$route['mahasiswa/tugas-akhir/tema/lampiran'] = 'mahasiswa/form_tugas_akhir_lampiran';
$route['mahasiswa/simpan-pengajuan-ta'] = 'mahasiswa/add_tugas_akhir';
$route['mahasiswa/tambah_berkas_ta'] = 'mahasiswa/tambah_berkas_ta';
$route['mahasiswa/hapus-berkas-ta'] = 'mahasiswa/hapus_berkas_ta';

$route['mahasiswa/hapus-data-ta'] = 'mahasiswa/hapus_data_ta';
$route['mahasiswa/tugas-akhir/tema/ajukan'] = 'mahasiswa/ajukan_data_ta';
$route['mahasiswa/tugas-akhir/tema/ajukan-perbaikan'] = 'mahasiswa/ajukan_data_ta_perbaikan';
    
$route['mahasiswa/tugas-akhir/seminar'] = 'mahasiswa/seminar';
$route['mahasiswa/tugas-akhir/seminar/form'] = 'mahasiswa/form_seminar';
$route['mahasiswa/tugas-akhir/seminar/lampiran'] = 'mahasiswa/lampiran_seminar';
$route['mahasiswa/tambah_berkas_seminar'] = 'mahasiswa/tambah_berkas_seminar';
$route['mahasiswa/hapus-berkas-seminar'] = 'mahasiswa/hapus_berkas_seminar';
$route['mahasiswa/simpan-pengajuan-seminar'] = 'mahasiswa/add_seminar';
$route['mahasiswa/hapus-data-seminar'] = 'mahasiswa/hapus_data_seminar';

// Dosen
$route['dosen/kelola-akun'] = 'dosen/akun';
$route['dosen/ubah-data-akun'] = 'dosen/ubah_akun';
$route['dosen/kelola-biodata'] = 'dosen/biodata';
$route['dosen/ubah-data-biodata'] = 'dosen/ubah_biodata';
$route['dosen/tugas-akhir/tema'] = 'dosen/tugas_akhir';
$route['dosen/tugas-akhir/tema/form'] = 'dosen/form_tugas_akhir';

$route['dosen/tugas-akhir/tema/approve-ta/form'] = 'dosen/tugas_akhir_aksi';

$route['dosen/tugas-akhir/tema/approve'] = 'dosen/tugas_akhir_approve';
$route['dosen/tugas-akhir/tema/decline'] = 'dosen/tugas_akhir_decline';
$route['dosen/tugas-akhir/tema/koordinator/decline'] = 'dosen/tugas_akhir_koordinator_decline';
$route['dosen/tugas-akhir/biodata'] = 'dosen/akun';
$route['dosen/tugas-akhir/tema/koordinator'] = 'dosen/tugas_akhir_koordinator';
$route['dosen/tugas-akhir/tema/koordinator/form'] = 'dosen/form_tugas_akhir_koordinator';
$route['dosen/simpan-pengajuan-ta'] = 'dosen/add_tugas_akhir';

// Tendik
$route['tendik/kelola-akun'] = 'tendik/akun';
$route['tendik/ubah-data-akun'] = 'tendik/ubah_akun';
$route['tendik/kelola-biodata'] = 'tendik/biodata';
$route['tendik/ubah-data-biodata'] = 'tendik/ubah_biodata';
$route['tendik/verifikasi-berkas'] = 'tendik/verifikasi_berkas';

$route['tendik/verifikasi-berkas/biodata'] = 'tendik/akun';
$route['tendik/verifikasi-berkas/decline'] = 'tendik/verifikasi_berkas_decline';
$route['tendik/tugas-akhir/tema/approve-ta/form'] = 'tendik/tugas_akhir_aksi';
$route['tendik/tugas-akhir/tema/approve'] = 'tendik/verifikasi_berkas_approve';


$route['beranda'] = 'admin';
$route['admin'] = 'welcome';
$route['periksa-akses'] = 'welcome/periksa_sandi';
$route['keluar-sistem'] = 'welcome/logout';
$route['data-peserta'] = 'admin/list';
$route['data-peserta/(:any)'] = 'admin/data_unit/$1';
$route['data-peserta/(:any)/(:num)'] = 'admin/data_unit_detail/$1/$1';
//$route['cetak'] = 'word_example/print_word';
$route['cetak'] = 'admin/print2';
$route['update-personil'] = 'admin/edit_personil';
$route['aksi-tambah-pelanggaran'] = 'admin/aksi_tambah_pelanggaran';
$route['aksi-ubah-pelanggaran'] = 'admin/aksi_ubah_pelanggaran';
$route['aksi-tambah-pengguna'] = 'admin/aksi_tambah_pengguna';
$route['aksi-ubah-pengguna'] = 'admin/aksi_ubah_pengguna';
$route['unggah-nilai'] = 'admin/upload';
$route['hapus-pelanggaran'] = 'admin/hapus_pelanggaran';
$route['aksi-unggah'] = 'admin/do_upload';
$route['kelola-pengguna'] = 'admin/user';
$route['tambah-pengguna'] = 'admin/add_user';
$route['ubah-pengguna/(:any)'] = 'admin/edit_user/$1';
$route['hapus-pengguna'] = 'admin/hapus_pengguna';
$route['detail-peserta/(:num)'] = 'admin/detail_peserta/$1';
$route['add-item-pelanggaran/(:num)'] = 'admin/add_item_pelanggaran/$1';
$route['edit-item-pelanggaran/(:num)/(:num)'] = 'admin/edit_item_pelanggaran/$1/$1';
