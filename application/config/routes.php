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
$route['mahasiswa/tugas-akhir/biodata'] = 'mahasiswa/biodata';
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
$route['mahasiswa/ajukan-data-seminar'] = 'mahasiswa/ajukan_data_seminar';
$route['mahasiswa/ajukan-perbaikan-seminar'] = 'mahasiswa/ajukan_perbaikan_seminar';

$route['mahasiswa/tugas-akhir/verifikasi-ta'] = 'mahasiswa/verifikasi_ta';
$route['mahasiswa/tugas-akhir/verifikasi-ta/ajukan'] = 'mahasiswa/verifikasi_ta_ajukan';

$route['mahasiswa/tugas-akhir/bimbingan'] = 'mahasiswa/bimbingan';

$route['mahasiswa/mahasiswa-add-lk'] = 'mahasiswa/add_lk';
$route['mahasiswa/mahasiswa-update-lk'] = 'mahasiswa/update_lk';

//PDF TA
$route['mahasiswa/tugas-akhir/tema/form_pdf'] = 'pdfta/set_pdf';
$route['mahasiswa/tugas-akhir/seminar/form_pdf'] = 'pdfta/set_pdf_seminar';


// Dosen
$route['dosen/kelola-akun'] = 'dosen/akun';
$route['dosen/ubah-data-akun'] = 'dosen/ubah_akun';
$route['dosen/kelola-biodata'] = 'dosen/biodata';
$route['dosen/ubah-data-biodata'] = 'dosen/ubah_biodata';
$route['dosen/biodata-tugas-tambahan'] = 'dosen/tugas_tambahan';
$route['dosen/biodata-tugas-tambahan-hapus'] = 'dosen/tugas_tambahan_nonaktif';
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

$route['dosen/tugas-akhir/seminar'] = 'dosen/seminar';
$route['dosen/tugas-akhir/seminar/approve'] = 'dosen/seminar_approve';
$route['dosen/tolak-data-seminar'] = 'dosen/seminar_decline';
$route['dosen/tugas-akhir/seminar/approve-seminar/form'] = 'dosen/seminar_aksi';

$route['dosen/tugas-akhir/verifikasi-ta'] = 'dosen/verifikasi_ta_dosen';
$route['dosen/tugas-akhir/verifikasi-ta/form'] = 'dosen/verifikasi_ta_dosen_form';
$route['dosen/tugas-akhir/verifikasi-ta/form/save'] = 'dosen/verifikasi_ta_dosen_form_save';

$route['dosen/tugas-akhir/seminar/koordinator'] = 'dosen/seminar_koordinator';
$route['dosen/tugas-akhir/seminar/koordinator/decline'] = 'dosen/seminar_decline';
$route['dosen/tugas-akhir/seminar/koordinator/form'] = 'dosen/seminar_aksi_koor';
$route['dosen/tugas-akhir/seminar/koordinator/approve'] = 'dosen/seminar_approve';

$route['dosen/struktural/tema'] = 'dosen/tugas_akhir_struktural';
$route['dosen/struktural/tema/form'] = 'dosen/form_tugas_akhir_struktural';
$route['dosen/struktural/tema/form/approve'] = 'dosen/tugas_akhir_approve_struktural';

$route['dosen/struktural/seminar'] = 'dosen/seminar_struktural';
$route['dosen/struktural/seminar/form'] = 'dosen/form_seminar_struktural';
$route['dosen/struktural/seminar/approve'] = 'dosen/seminar_approve';

    //approval kaprodi
$route['dosen/struktural/kaprodi/tugas-akhir'] = 'dosen/tugas_akhir_kaprodi';
$route['dosen/struktural/kaprodi/tugas-akhir/form'] = 'dosen/tugas_akhir_kaprodi_form';
$route['dosen/struktural/kaprodi/tugas-akhir/approve'] = 'dosen/tugas_akhir_kaprodi_approve';

$route['dosen/struktural/kaprodi/seminar-sidang'] = 'dosen/seminar_sidang_kaprodi';
$route['dosen/struktural/kaprodi/seminar-sidang/form'] = 'dosen/seminar_aksi_koor';

$route['dosen/struktural/kaprodi/nilai-seminar-sidang'] = 'dosen/nilai_seminar_sidang_kaprodi';
$route['dosen/struktural/kaprodi/nilai-seminar-sidang/form'] = 'dosen/nilai_seminar_sidang_kaprodi_approve';

$route['dosen/struktural/kaprodi/verifikasi-tugas-akhir'] = 'dosen/tugas_akhir_kaprodi_verifikasi';
$route['dosen/struktural/kaprodi/verifikasi-tugas-akhir/form'] = 'dosen/tugas_akhir_kaprodi_verifikasi_form';

    //komposisi nilai kajur
$route['dosen/struktural/bidang-nilai/komposisi-nilai'] = 'dosen/komposisi_nilai';
$route['dosen/struktural/komposisi-nilai/add'] = 'dosen/komposisi_nilai_tambah';
$route['dosen/struktural/komposisi-nilai/save'] = 'dosen/komposisi_nilai_simpan';
$route['dosen/struktural/komposisi-nilai/komponen'] = 'dosen/komponen_nilai';
$route['dosen/struktural/komposisi-nilai/nonaktifkan'] = 'dosen/komposisi_nilai_nonaktif';
$route['dosen/struktural/komposisi-nilai/ubah'] = 'dosen/komposisi_nilai_ubah';
$route['dosen/struktural/komposisi-nilai/edit'] = 'dosen/komposisi_nilai_edit';

$route['dosen/struktural/bidang-nilai/bidang-jurusan'] = 'dosen/bidang_jurusan';
$route['dosen/struktural/bidang-nilai/bidang-jurusan/show'] = 'dosen/bidang_jurusan_show';
$route['dosen/struktural/bidang-nilai/bidang-jurusan/add'] = 'dosen/bidang_jurusan_add';
$route['dosen/struktural/bidang-nilai/bidang-jurusan/delete'] = 'dosen/bidang_jurusan_delete';

$route['dosen/struktural/bidang-nilai/komposisi-ta'] = 'dosen/komposisi_ta';
$route['dosen/struktural/bidang-nilai/komposisi-ta/add'] = 'dosen/komposisi_ta_add';
$route['dosen/struktural/bidang-nilai/komposisi-ta/save'] = 'dosen/komposisi_ta_save';
$route['dosen/struktural/bidang-nilai/komposisi-ta/delete'] = 'dosen/komposisi_ta_delete';

    //nilai seminar dosen
$route['dosen/tugas-akhir/nilai-seminar'] = 'dosen/nilai_seminar';
$route['dosen/tugas-akhir/nilai-seminar/add'] = 'dosen/nilai_seminar_add';
$route['dosen/tugas-akhir/nilai-seminar/save'] = 'dosen/nilai_seminar_save';

$route['dosen/tugas-akhir/nilai-seminar/koordinator'] = 'dosen/nilai_seminar_koor';
$route['dosen/tugas-akhir/nilai-seminar/koordinator/form'] = 'dosen/nilai_seminar_koor_approve';
$route['dosen/tugas-akhir/nilai-seminar/koordinator/approve'] = 'dosen/nilai_seminar_koor_approve_add';

$route['dosen/struktural/nilai-seminar'] = 'dosen/nilai_seminar_kajur';
$route['dosen/struktural/nilai-seminar/kajur/form'] = 'dosen/nilai_seminar_kajur_approve';
$route['dosen/struktural/nilai-seminar/kajur/approve'] = 'dosen/nilai_seminar_kajur_approve_add';

    //nilai verifikasi ta
$route['dosen/tugas-akhir/nilai-verifikasi-ta'] = 'dosen/nilai_verifikasi';
$route['dosen/tugas-akhir/nilai-verifikasi-ta/form'] = 'dosen/nilai_verifikasi_form';
$route['dosen/tugas-akhir/nilai-verifikasi-ta/save'] = 'dosen/nilai_verifikasi_save';
$route['dosen/tugas-akhir/nilai-verifikasi-ta/nilai'] = 'dosen/nilai_verifikasi_nilai';
$route['dosen/tugas-akhir/nilai-verifikasi-ta/verifikasi'] = 'dosen/nilai_verifikasi_verifikasi';



    //rekap koor
$route['dosen/koordinator/rekap/tugas-akhir'] = 'dosen/rekap_ta';
$route['dosen/koordinator/rekap/tugas-akhir/detail'] = 'dosen/rekap_ta_detail';
$route['dosen/koordinator/rekap/seminar'] = 'dosen/rekap_seminar_koor';
$route['dosen/koordinator/rekap/seminar/detail'] = 'dosen/rekap_seminar_koor_detail';
$route['dosen/koordinator/rekap/mahasiswa-ta'] = 'dosen/rekap_mahasiswa_ta';
$route['dosen/koordinator/rekap/mahasiswa-ta/detail'] = 'dosen/rekap_mahasiswa_ta_detail';
$route['dosen/koordinator/rekap/bimbingan-dosen'] = 'dosen/rekap_bimbingan_dosen';
$route['dosen/koordinator/rekap/bimbingan-dosen/detail'] = 'dosen/rekap_bimbingan_dosen_detail';



// Tendik
$route['tendik/kelola-akun'] = 'tendik/akun';
$route['tendik/ubah-data-akun'] = 'tendik/ubah_akun';
$route['tendik/kelola-biodata'] = 'tendik/biodata';
$route['tendik/biodata-tugas-tambahan'] = 'tendik/tugas_tambahan';
$route['tendik/biodata-tugas-tambahan-hapus'] = 'tendik/tugas_tambahan_nonaktif';
$route['tendik/ubah-data-biodata'] = 'tendik/ubah_biodata';
$route['tendik/verifikasi-berkas'] = 'tendik/verifikasi_berkas';

$route['tendik/verifikasi-berkas/biodata'] = 'tendik/akun';
$route['tendik/verifikasi-berkas/decline'] = 'tendik/verifikasi_berkas_decline';
$route['tendik/tugas-akhir/tema/approve-ta/form'] = 'tendik/tugas_akhir_aksi';
$route['tendik/tugas-akhir/tema/approve'] = 'tendik/verifikasi_berkas_approve';

$route['tendik/verifikasi-berkas/seminar'] = 'tendik/verifikasi_berkas_seminar';
$route['tendik/verifikasi-berkas/seminar/form'] = 'tendik/seminar_aksi';
$route['tendik/verifikasi-berkas/seminar/decline'] = 'tendik/verifikasi_berkas_seminar_decline';
$route['tendik/verifikasi-berkas/seminar/approve'] = 'tendik/verifikasi_berkas_seminar_approve';



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


$route['approval/ta?(:any)'] = 'approval/approval_alter';
$route['approval/simpan-ta'] = 'approval/simpan_data';
$route['email-test'] = 'approval/send';
$route['approval/seminar?(:any)'] = 'approval/approval_seminar';
$route['approval/simpan-seminar'] = 'approval/simpan_seminar';