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

    //mhs tema ta
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
    
    //mhs seminar
$route['mahasiswa/tugas-akhir/seminar'] = 'mahasiswa/seminar';
$route['mahasiswa/tugas-akhir/seminar/form'] = 'mahasiswa/form_seminar';
$route['mahasiswa/tugas-akhir/seminar/lampiran'] = 'mahasiswa/lampiran_seminar';
$route['mahasiswa/tambah_berkas_seminar'] = 'mahasiswa/tambah_berkas_seminar';
$route['mahasiswa/hapus-berkas-seminar'] = 'mahasiswa/hapus_berkas_seminar';
$route['mahasiswa/simpan-pengajuan-seminar'] = 'mahasiswa/add_seminar';
$route['mahasiswa/hapus-data-seminar'] = 'mahasiswa/hapus_data_seminar';
$route['mahasiswa/ajukan-data-seminar'] = 'mahasiswa/ajukan_data_seminar';
$route['mahasiswa/ajukan-perbaikan-seminar'] = 'mahasiswa/ajukan_perbaikan_seminar';

    //mhs verif ta
$route['mahasiswa/tugas-akhir/verifikasi-ta'] = 'mahasiswa/verifikasi_ta';
$route['mahasiswa/tugas-akhir/verifikasi-ta/ajukan'] = 'mahasiswa/verifikasi_ta_ajukan';

    //mhs bimbingan
$route['mahasiswa/tugas-akhir/bimbingan'] = 'mahasiswa/bimbingan';

    //mhs lembaga kemahasiswaan
$route['mahasiswa/mahasiswa-add-lk'] = 'mahasiswa/add_lk';
$route['mahasiswa/mahasiswa-update-lk'] = 'mahasiswa/update_lk';

    //mhs kp pkl
$route['mahasiswa/pkl/pkl-home'] = 'mahasiswa/pkl_home';
$route['mahasiswa/pkl/pkl-home/form'] = 'mahasiswa/pkl_home_form';
$route['mahasiswa/pkl/pkl-home/form/add'] = 'mahasiswa/pkl_home_form_add';
$route['mahasiswa/pkl/pkl-home/delete'] = 'mahasiswa/pkl_home_delete';
$route['mahasiswa/pkl/pkl-home/lampiran/delete-instansi'] = 'mahasiswa/pkl_home_delete_lampiran_instansi';
$route['mahasiswa/pkl/pkl-home/lampiran'] = 'mahasiswa/pkl_home_lampiran';
$route['mahasiswa/pkl/pkl-home/lampiran/upload'] = 'mahasiswa/pkl_home_lampiran_upload';
$route['mahasiswa/pkl/pkl-home/lampiran/delete'] = 'mahasiswa/pkl_home_lampiran_delete';
$route['mahasiswa/pkl/pkl-home/ajukan'] = 'mahasiswa/pkl_home_ajukan';
$route['mahasiswa/pkl/pkl-home/perbaikan'] = 'mahasiswa/pkl_home_ajukan_perbaikan';
$route['mahasiswa/pkl/pkl-home/berkas-instansi'] = 'mahasiswa/pkl_home_ajukan_berkas_instansi';
$route['mahasiswa/pkl/pkl-home/pb-lapangan'] = 'mahasiswa/pkl_home_pb_lapangan';
$route['mahasiswa/pkl/pkl-home/pb-lapangan-ubah'] = 'mahasiswa/pkl_home_pb_lapangan_ubah';
$route['mahasiswa/pkl/seminar'] = 'mahasiswa/pkl_seminar';
$route['mahasiswa/pkl/seminar/form'] = 'mahasiswa/pkl_seminar_form';
$route['mahasiswa/pkl/seminar/form/add'] = 'mahasiswa/pkl_seminar_form_add';
$route['mahasiswa/pkl/seminar/delete'] = 'mahasiswa/pkl_seminar_delete';
$route['mahasiswa/pkl/seminar/lampiran'] = 'mahasiswa/pkl_seminar_lampiran';
$route['mahasiswa/pkl/seminar/lampiran/upload'] = 'mahasiswa/pkl_seminar_lampiran_upload';
$route['mahasiswa/pkl/seminar/lampiran/delete'] = 'mahasiswa/pkl_seminar_lampiran_delete';
$route['mahasiswa/pkl/seminar/ajukan'] = 'mahasiswa/pkl_seminar_ajukan';
$route['mahasiswa/pkl/seminar/perbaikan'] = 'mahasiswa/pkl_seminar_ajukan_perbaikan';


//PDF TA
$route['mahasiswa/tugas-akhir/tema/form_pdf'] = 'pdfta/set_pdf';
$route['mahasiswa/tugas-akhir/seminar/form_pdf'] = 'pdfta/set_pdf_seminar';
$route['mahasiswa/tugas-akhir/pkl/form_pdf'] = 'pdfta/set_pdf_pkl';
$route['mahasiswa/tugas-akhir/seminar-pkl/form_pdf'] = 'pdfta/set_pdf_pkl_seminar';

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



    //rekap ta
    //koor
$route['dosen/koordinator/rekap/tugas-akhir'] = 'dosen/rekap_ta';
$route['dosen/koordinator/rekap/tugas-akhir/detail'] = 'dosen/rekap_ta_detail';
$route['dosen/koordinator/rekap/tugas-akhir/detail/ganti-pbb'] = 'dosen/rekap_ganti_pbb';
$route['dosen/koordinator/rekap/tugas-akhir/detail/ganti-ta'] = 'dosen/rekap_ganti_ta';
$route['dosen/koordinator/rekap/tugas-akhir/detail/ganti-pbb/save'] = 'dosen/rekap_ganti_pbb_save';

$route['dosen/koordinator/rekap/seminar'] = 'dosen/rekap_seminar_koor';
$route['dosen/koordinator/rekap/seminar/detail'] = 'dosen/rekap_seminar_koor_detail';
$route['dosen/koordinator/rekap/mahasiswa-ta'] = 'dosen/rekap_mahasiswa_ta';
$route['dosen/koordinator/rekap/mahasiswa-ta/detail'] = 'dosen/rekap_mahasiswa_ta_detail';
$route['dosen/koordinator/rekap/bimbingan-dosen'] = 'dosen/rekap_bimbingan_dosen';
$route['dosen/koordinator/rekap/bimbingan-dosen/detail'] = 'dosen/rekap_bimbingan_dosen_detail';

    //kaprodi
$route['dosen/kaprodi/rekap/tugas-akhir'] = 'dosen/rekap_ta';
$route['dosen/kaprodi/rekap/tugas-akhir/detail'] = 'dosen/rekap_ta_detail';
$route['dosen/kaprodi/rekap/tugas-akhir/detail/ganti-pbb'] = 'dosen/rekap_ganti_pbb';
$route['dosen/kaprodi/rekap/tugas-akhir/detail/ganti-ta'] = 'dosen/rekap_ganti_ta';
$route['dosen/kaprodi/rekap/tugas-akhir/detail/ganti-pbb/save'] = 'dosen/rekap_ganti_pbb_save';

$route['dosen/kaprodi/rekap/seminar'] = 'dosen/rekap_seminar_koor';
$route['dosen/kaprodi/rekap/seminar/detail'] = 'dosen/rekap_seminar_koor_detail';
$route['dosen/kaprodi/rekap/mahasiswa-ta'] = 'dosen/rekap_mahasiswa_ta';
$route['dosen/kaprodi/rekap/mahasiswa-ta/detail'] = 'dosen/rekap_mahasiswa_ta_detail';
$route['dosen/kaprodi/rekap/bimbingan-dosen'] = 'dosen/rekap_bimbingan_dosen';
$route['dosen/kaprodi/rekap/bimbingan-dosen/detail'] = 'dosen/rekap_bimbingan_dosen_detail';

    //kajur
$route['dosen/struktural/rekap/tugas-akhir'] = 'dosen/rekap_ta';
$route['dosen/struktural/rekap/tugas-akhir/detail'] = 'dosen/rekap_ta_detail';
$route['dosen/struktural/rekap/tugas-akhir/detail/ganti-pbb'] = 'dosen/rekap_ganti_pbb';
$route['dosen/struktural/rekap/tugas-akhir/detail/ganti-ta'] = 'dosen/rekap_ganti_ta';
$route['dosen/struktural/rekap/tugas-akhir/detail/ganti-pbb/save'] = 'dosen/rekap_ganti_pbb_save';

$route['dosen/struktural/rekap/seminar'] = 'dosen/rekap_seminar_koor';
$route['dosen/struktural/rekap/seminar/detail'] = 'dosen/rekap_seminar_koor_detail';
$route['dosen/struktural/rekap/mahasiswa-ta'] = 'dosen/rekap_mahasiswa_ta';
$route['dosen/struktural/rekap/mahasiswa-ta/detail'] = 'dosen/rekap_mahasiswa_ta_detail';
$route['dosen/struktural/rekap/bimbingan-dosen'] = 'dosen/rekap_bimbingan_dosen';
$route['dosen/struktural/rekap/bimbingan-dosen/detail'] = 'dosen/rekap_bimbingan_dosen_detail';
    //kajur pkl
$route['dosen/struktural/pkl/add-pkl'] = 'dosen/kajur_add_pkl';
$route['dosen/struktural/pkl/add-pkl/form'] = 'dosen/kajur_add_pkl_form';
$route['dosen/struktural/pkl/add-pkl/form/save'] = 'dosen/kajur_add_pkl_form_save';
$route['dosen/struktural/pkl/add-pkl/show'] = 'dosen/kajur_add_pkl_show';
$route['dosen/struktural/pkl/add-pkl/delete'] = 'dosen/kajur_add_pkl_delete';
$route['dosen/struktural/pkl/add-lokasi-pkl'] = 'dosen/kajur_add_lokasi_pkl';
$route['dosen/struktural/pkl/add-lokasi-pkl/aksi'] = 'dosen/kajur_add_lokasi_pkl_aksi';
$route['dosen/struktural/pkl/add-lokasi-pkl/aksi/save'] = 'dosen/kajur_add_lokasi_pkl_aksi_save';
$route['dosen/struktural/pkl/add-lokasi-pkl/aksi/delete'] = 'dosen/kajur_add_lokasi_pkl_aksi_delete';
$route['dosen/struktural/pkl/add-lokasi-pkl/aksi/edit'] = 'dosen/kajur_add_lokasi_pkl_aksi_edit';
$route['dosen/struktural/pkl/approve-pkl'] = 'dosen/kajur_approve_pkl';
$route['dosen/struktural/pkl/approve-pkl/setujui'] = 'dosen/kajur_approve_pkl_setujui';
$route['dosen/struktural/pkl/approve-pkl/save'] = 'dosen/kajur_approve_pkl_save';
$route['dosen/struktural/pkl/approve-pkl/list'] = 'dosen/kajur_approve_pkl_list';
$route['dosen/struktural/pkl/approve-seminar-pkl'] = 'dosen/kajur_approve_pkl_seminar_list';
$route['dosen/struktural/pkl/approve-seminar-pkl/form'] = 'dosen/kajur_approve_pkl_seminar_form';
$route['dosen/struktural/pkl/approve-seminar-pkl/save'] = 'dosen/kajur_approve_pkl_seminar_save';
$route['dosen/struktural/pkl/komponen-nilai-pkl'] = 'dosen/komponen_nilai_pkl_home';
$route['dosen/struktural/pkl/komponen-nilai-pkl/form'] = 'dosen/komponen_nilai_pkl';
$route['dosen/struktural/pkl/komponen-nilai-pkl/add'] = 'dosen/komponen_nilai_pkl_add';
$route['dosen/struktural/pkl/komponen-nilai-pkl/delete'] = 'dosen/komponen_nilai_pkl_delete';
$route['dosen/struktural/pkl/komponen-nilai-pkl/edit'] = 'dosen/komponen_nilai_pkl_edit';
$route['dosen/struktural/pkl/komponen-nilai-pkl/simpan'] = 'dosen/komponen_nilai_pkl_save';
$route['dosen/struktural/pkl/komponen-nilai-pkl/nonaktif'] = 'dosen/komponen_nilai_pkl_nonaktif';
$route['dosen/struktural/pkl/approve-seminar-nilai-pkl'] = 'dosen/kajur_approve_pkl_seminar_nilai';
$route['dosen/struktural/pkl/approve-seminar-nilai-pkl/form'] = 'dosen/kajur_approve_pkl_seminar_nilai_form';
$route['dosen/struktural/pkl/approve-seminar-nilai-pkl/save'] = 'dosen/kajur_approve_pkl_seminar_nilai_save';

    //dosen pkl
$route['dosen/pkl/approve'] = 'dosen/pkl_approve';
$route['dosen/pkl/approve/perbaiki'] = 'dosen/pkl_approve_perbaiki';
$route['dosen/pkl/approve/setujui'] = 'dosen/pkl_approve_setujui';
$route['dosen/pkl/approve/setujui/save'] = 'dosen/pkl_approve_setujui_save';
$route['dosen/pkl/approve-seminar'] = 'dosen/pkl_approve_seminar';
$route['dosen/pkl/approve-seminar/perbaiki'] = 'dosen/pkl_approve_seminar_perbaiki';
$route['dosen/pkl/approve-seminar/form'] = 'dosen/pkl_approve_seminar_setujui';
$route['dosen/pkl/approve-seminar/save'] = 'dosen/pkl_approve_seminar_save';
$route['dosen/pkl/approve-nilai-seminar'] = 'dosen/pkl_approve_seminar_nilai';
$route['dosen/pkl/approve-nilai-seminar/form'] = 'dosen/pkl_approve_seminar_nilai_form';
$route['dosen/pkl/approve-nilai-seminar/save'] = 'dosen/pkl_approve_seminar_nilai_save';


    //koor pkl
$route['dosen/pkl/pengajuan/koordinator'] = 'dosen/pkl_approve_koor';
$route['dosen/pkl/pengajuan/koordinator-tolak'] = 'dosen/pkl_approve_koor_tolak';
$route['dosen/pkl/pengajuan/koordinator/approve'] = 'dosen/pkl_approve_koor_approve';
$route['dosen/pkl/pengajuan/koordinator-save'] = 'dosen/pkl_approve_koor_save';
$route['dosen/pkl/pengajuan/koordinator-setuju'] = 'dosen/pkl_approve_koor_setuju';
$route['dosen/pkl/seminar/koordinator'] = 'dosen/pkl_approve_seminar_koor';
$route['dosen/pkl/seminar/koordinator/tolak'] = 'dosen/pkl_approve_seminar_tolak';
$route['dosen/pkl/seminar/koordinator/form'] = 'dosen/pkl_approve_seminar_form';
$route['dosen/pkl/seminar/koordinator/save'] = 'dosen/pkl_approve_seminar_save_koor';
$route['dosen/pkl/seminar-nilai/koordinator'] = 'dosen/pkl_approve_seminar_nilai_koor';
$route['dosen/pkl/seminar-nilai/koordinator/form'] = 'dosen/pkl_approve_seminar_nilai_koor_form';
$route['dosen/pkl/seminar-nilai/koordinator/save'] = 'dosen/pkl_approve_seminar_nilai_koor_save';

    //kaprodi pkl
$route['dosen/struktural/kaprodi/pkl'] = 'dosen/pkl_approve_kaprodi';   
$route['dosen/struktural/kaprodi/pkl/setujui'] = 'dosen/pkl_approve_kaprodi_setujui';   
$route['dosen/struktural/kaprodi/pkl/save'] = 'dosen/pkl_approve_kaprodi_simpan';   
$route['dosen/struktural/kaprodi/seminar-pkl'] = 'dosen/pkl_approve_kaprodi_seminar';   
$route['dosen/struktural/kaprodi/seminar-pkl/form'] = 'dosen/pkl_approve_kaprodi_seminar_form';   
$route['dosen/struktural/kaprodi/seminar-pkl/save'] = 'dosen/pkl_approve_kaprodi_seminar_save';   

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

$route['tendik/verifikasi-berkas/pkl'] = 'tendik/verifikasi_berkas_pkl';
$route['tendik/verifikasi-berkas/pkl/perbaiki'] = 'tendik/verifikasi_berkas_pkl_perbaiki';
$route['tendik/verifikasi-berkas/pkl/setujui'] = 'tendik/verifikasi_berkas_pkl_setujui';
$route['tendik/verifikasi-berkas/pkl/save'] = 'tendik/verifikasi_berkas_pkl_save';
$route['tendik/verifikasi-berkas/pkl/approve'] = 'tendik/verifikasi_berkas_pkl_list';
$route['tendik/verifikasi-berkas/pkl/approve/ttd'] = 'tendik/verifikasi_berkas_pkl_approve';
$route['tendik/verifikasi-berkas/seminar-pkl'] = 'tendik/verifikasi_berkas_pkl_seminar';
$route['tendik/verifikasi-berkas/seminar-pkl/perbaiki'] = 'tendik/verifikasi_berkas_pkl_seminar_perbaiki';
$route['tendik/verifikasi-berkas/seminar-pkl/form'] = 'tendik/verifikasi_berkas_pkl_seminar_form';
$route['tendik/verifikasi-berkas/seminar-pkl/form/setujui'] = 'tendik/verifikasi_berkas_pkl_seminar_form_setujui';



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