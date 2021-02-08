<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Pdfta extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('fpdf');
        $this->load->model('user_model');
        $this->load->model('ta_model');
        $this->load->model('pkl_model');
    }

    function get_month($month)
    {
        $bulan = "";
        
        if($month == '01' || $month == 'January'){
            $bulan = "Januari";
        }
        
        elseif($month == '02' || $month == 'February'){
            $bulan = "Februari";
        }
        
         elseif($month == '03' || $month == 'March'){
            $bulan = "Maret";
        }
        
         elseif($month == '04'  || $month == 'April'){
            $bulan = "April";
        }
        
         elseif($month == '05'  || $month == 'May'){
            $bulan = "Mei";
        }
        
         elseif($month == '06'  || $month == 'June'){
            $bulan = "Juni";
        }
        
         elseif($month == '07'  || $month == 'July'){
            $bulan = "Juli";
        }
        
         elseif($month == '08'  || $month == 'August'){
            $bulan = "Agustus";
        }
        
         elseif($month == '09'  || $month == 'September'){
            $bulan = "September";
        }
        
         elseif($month == '10'  || $month == 'October'){
            $bulan = "Oktober";
        }
        
         elseif($month == '11'  || $month == 'November'){
            $bulan = "November";
        }
        
        else{
            $bulan = "Desember";
        }
        
        return $bulan;
    }

    function get_day($day){
        $hari = "";
        
        switch ($day){
            case "Monday":
            $hari = "Senin";
            break;
            case "Tuesday":
            $hari = "Selasa";
            break;
            case "Wednesday":
            $hari = "Rabu";
            break;
            case "Thursday":
            $hari = "Kamis";
            break;
            case "Friday":
            $hari = "Jum'at";
            break;
            case "Saturday":
            $hari = "Sabtu";
            break;
            case "Sunday":
            $hari = "Minggu";
            break;
        }
        
        return $hari;
    }

    function komisi_number($komisi)
    {
        $no = 0;
        
        switch ($komisi){
            case "Pembimbing Utama":
                $no = 0;
            break;
            case "Pembimbing 2":
                $no = 1;
            break;
            case "Pembimbing 3":
                $no = 2;
            break;
            case "Penguji 1":
                $no = 3;
            break;
            case "Penguji 2":
                $no = 4;
            break;
            case "Penguji 3":
                $no = 5;
            break;
        }
        
        return $no;
    }

    function huruf_mutu($nilai)
    {
        if($nilai >= 76){
            $hm = "A";
        }
        elseif($nilai >= 71 && $nilai <= 76)
        {
            $hm = "B+";
        }
        elseif($nilai >= 66 && $nilai <= 71)
        {
            $hm = "B";
        }
        elseif($nilai >= 61 && $nilai <= 66)
        {
            $hm = "C+";
        }
        elseif($nilai >= 56 && $nilai <= 61)
        {
            $hm = "C";
        }
        elseif($nilai >= 50 && $nilai <= 56)
        {
            $hm = "BL";
        }
        else
        {
            $hm = "BL";
        }

        return $hm;
    }

    function komisi_kompre($jml,$pbb)
    {
        $status = "";
        if($jml == 2)
        {
            switch($pbb)
            {
                case "Pembimbing Utama":
                    $status = "Ketua Penguji";
                    break;
                case "Pembimbing 2":
                    $status = "Sekretaris Penguji";
                    break;
                case "Penguji 1":
                    $status = "Penguji Utama";
                    break;  
                case "Penguji 2":
                    $status = "Penguji Pembahas"; // ganti
                    break;        
            }
        }
        else{
            switch($pbb)
            {
                case "Pembimbing Utama":
                    $status = "Ketua Penguji";
                    break;
                case "Penguji 1":
                case "Penguji 2":
                case "Penguji 3":
                    $status = "Penguji Pembahas";
                    break;    
            }
        }

        return $status;
    }
    
    function set_pdf_seminar()
    {
        $id = $this->input->get('id');
        $jenis = $this->input->get('jenis');
        $seminar = $this->ta_model->get_seminar_id($id);
        $ta_seminar = $this->ta_model->get_ta_by_id($seminar->id_tugas_akhir);

        $npm = $ta_seminar->npm;
        $jurusan = $this->ta_model->get_jurusan($npm);

        switch($jenis)
        {
            case "pengajuan_seminar":
                $this->pengajuan_seminar($seminar,$jurusan,$ta_seminar);
                break;
            case "verifikasi_seminar":
                $this->verifikasi_seminar($seminar,$jurusan,$ta_seminar);
                break;
            case "undangan_seminar":
                $this->undangan_seminar($seminar,$jurusan,$ta_seminar);
                break; 
            case "undangan_seminar_dosen":
                $user = $this->input->get('user');
                $this->undangan_seminar_dosen($seminar,$jurusan,$ta_seminar,$user);
                break; 
            case "undangan_dosen_alter":
                $status = $this->input->get('status');
                $this->undangan_dosen($seminar,$jurusan,$ta_seminar,$status);
                break;   
            case "penilaian_seminar":
                $this->penilaian_seminar($seminar,$jurusan,$ta_seminar);
                break;    
            case "berita_acara":
                $this->berita_acara($seminar,$jurusan,$ta_seminar);
                break;   
            //ta
            case "pengajuan_seminar_ta":
                $this->pengajuan_seminar_ta($seminar,$jurusan,$ta_seminar);
                break; 
            //kompre
            case "pengajuan_seminar_kompre":
                $this->pengajuan_seminar_kompre($seminar,$jurusan,$ta_seminar);
                break;  
            case "penilaian_kompre":
                $this->penilaian_kompre($seminar,$jurusan,$ta_seminar);
                break;   
            case "berita_acara_kompre":
                $this->berita_acara_kompre($seminar,$jurusan,$ta_seminar);
                break;                              
        }

    }

    function set_pdf()
    {
        $id = $this->input->get('id');
        $jenis = $this->input->get('jenis');
        $ta = $this->ta_model->get_ta_by_id($id);
        $pembimbing = $this->ta_model->get_pembimbing_ta($id);
        $penguji = $this->ta_model->get_penguji_ta($id);

        // echo "<pre>";
        // print_r($seminar);
        // echo $seminar->id;
        $npm = $ta->npm;
        $jurusan = $this->ta_model->get_jurusan($npm);
            
        switch($jenis)
        {
            case "pengajuan_bimbingan":
                $this->pengajuan_bimbingan($ta,$jurusan);
                break;
            case "form_verifikasi":
                $this->form_verifikasi($ta,$jurusan);
                break;
            case "form_penetapan":
                $this->form_penetapan($ta,$jurusan);
                break;  
            case "verifikasi_ta":
                $this->verifikasi_ta($ta,$jurusan);
                break;     
            case "verifikasi_ta_nilai":
                $this->verifikasi_ta_nilai($ta,$jurusan);
                break;           
        }
       
    }

    function pengajuan_bimbingan($ta,$jurusan)
    {
 
        $jurusan_upper = strtoupper($jurusan);
        $pb = $this->ta_model->get_dosen_pb1($ta->id_pengajuan);
        $pa = $this->ta_model->get_dosen_pa_detail($ta->id_pengajuan);
        $mhs = $this->ta_model->get_mahasiswa_detail($ta->npm);

        //ttd
        if($ta->status <= 1 && $ta->status != -2){
            $ttd_pa = $this->ta_model->get_ttd_approval($ta->id_pengajuan,'Pembimbing Akademik');
            // $ttd_pa = $ttd_pa[0];
        }
        if($ta->status >= 2 || $ta->status == -2){
            $ttd_pa = $this->ta_model->get_ttd_approval($ta->id_pengajuan,'Pembimbing Akademik');
            $ttd_pb1 = $this->ta_model->get_ttd_approval($ta->id_pengajuan,'Pembimbing Utama');
            // $ttd_pb1 = $ttd_pb1[0];
        }

        switch($jurusan){
            case "Ilmu Komputer":
                $numPage = '/SOP/MIPA/7.5/II/11/002';
            break;
            case "Kimia":
                $numPage = '/SOP/MIPA/7.1/II/11';
            break;
            case "Fisika":
                $numPage = '/SOP/MIPA/17.04/II/11/002';
            break;
            case "Biologi":
                $numPage = '/SOP/MIPA/2/II/11 Pengajuan Tema Penelitian';
            break;
            case "Matematika":
                $numPage = '/PM/MIPA/2/13';
            break;
        }
        
        $kode = 0;
        $type = 'Single';
        
        $blank_image = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVQYV2NgYAAAAAMAAWgmWQ0AAAAASUVORK5CYII=";

        $tanggal = date("d");
        $bulan = $this->get_month(date("F"));
        $tahun = date("Y");

        $pdf = new FPDF('P','mm','A4');
       
        $spasi = 6;
        $pdf->number_footer(0);
        $pdf->setting_page_footer($numPage, $kode, $type);
        $pdf->set_header_jur($jurusan_upper);
        $pdf->set_header($jurusan);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);

        // membuat halaman baru
        $pdf->AddPage();
        $pdf->SetFont('Times','B',11);
        $pdf->Ln(5);
        $pdf->MultiCell(150, $spasi, "FORMULIR PENGAJUAN\nTEMA PENELITIAN DAN PEMBIMBING/PEMBAHAS ".strtoupper($ta->jenis)."\nJURUSAN ".$jurusan_upper." FMIPA UNIVERSITAS LAMPUNG",1,'C',false); //GANTI
        $pdf->SetFont('Times','',11);
        $pdf->Ln(8);
        $pdf->SetWidths(array(45,5, 50, 12, 5, 50));
        $pdf->SetAligns(array('L','C','L','L','C','L'));
        $pdf->RowNoBorder(array('NAMA',':',$mhs->name,'NPM',':',$mhs->npm));

        $pdf->Ln(8);
        $pdf->Cell(45, $spasi,"PROGRAM STUDI", 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(50, $spasi,$jurusan, 0, 0, 'L');
        $pdf->Cell(60, $spasi,"20".substr($ta->npm,0,2), 0, 0, 'L'); 

        $pdf->Ln(15);
        $pdf->Cell(45, $spasi,"Mengajukan Tema Penelitian ".$ta->jenis." Dengan", 0,0, 'L');
        $pdf->Ln(10);
        $pdf->SetWidths(array(45,5, 100));
        $pdf->SetAligns(array('L','C','J'));
        $pdf->RowNoBorder(array('JUDUL',':',"1. ".$ta->judul1));
        $pdf->Ln(8);
        $pdf->SetWidths(array(45,5, 100));
        $pdf->SetAligns(array('L','C','J'));
        $pdf->RowNoBorder(array('','',"2. ".$ta->judul2));
        $pdf->Ln(8);

        //ttd
        if($ta->status <= 1 && $ta->status != -2){
            $pdf->SetWidths(array(45,5, 50, 12, 5, 50));
            $pdf->SetAligns(array('L','C','L','L','C','L'));
            $pdf->RowNoBorder(array('PEMBIMBING UTAMA',':',$pb->name,'NIP',':',$pb->nip_nik));
            $pdf->Ln(8);   
            $pdf->Cell(45, $spasi,"TTD PEMBIMBING", 0, 0, 'L');
            $pdf->Cell(5, $spasi,':', 0, 0, 'C');
            $pdf->Cell(50, $spasi,"", 0, 0, 'L');
            $pdf->Ln(30);
        }
        elseif($ta->status >= 2 || $ta->status == -2){
            $pdf->SetWidths(array(45,5, 50, 12, 5, 50));
            $pdf->SetAligns(array('L','C','L','L','C','L'));
            $pdf->RowNoBorder(array('PEMBIMBING UTAMA',':',$pb->name,'NIP',':',$pb->nip_nik));
            $pdf->Ln(8);   
            $pdf->Cell(45, $spasi,"TTD PEMBIMBING", 0, 0, 'L');
            $pdf->Cell(5, $spasi,':', 0, 0, 'C');
            $pdf->Cell(50, $spasi,$pdf->Image("$ttd_pb1->ttd",$pdf->GetX(), $pdf->GetY()-3,40,0,'PNG'), 0, 0, 'L');
            $pdf->Ln(30);
        }

        $pdf->Cell(90, $spasi,"", 0, 0, 'L');
        $pdf->Cell(150, $spasi,"Bandar Lampung, ".$tanggal." ".$bulan." ".$tahun."", 0, 0, 'L');
        $pdf->Ln(8);       
        
        $pdf->Cell(45, $spasi,"Mengetahui", 0, 0, 'L');
        $pdf->Ln(5);
        $pdf->Cell(90, $spasi,"Dosen Pembimbing Akademik,", 0, 0, 'L');
        $pdf->Cell(30, $spasi,"Pengusul,", 0, 0, 'L');
        $pdf->Ln(5);
        

        //ttd
        if($ta->status == "0"){
            $pdf->Cell(90, $spasi,$pdf->Image("$blank_image",$pdf->GetX()-3, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L'); 
            $pdf->Cell(30, $spasi,$pdf->Image("$ta->ttd",$pdf->GetX()-3, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');
        }
        elseif($ta->status >= 1 ||$ta->status == -2 ){
            $pdf->Cell(90, $spasi,$pdf->Image($ttd_pa->ttd,$pdf->GetX()+1, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L'); 
            $pdf->Cell(30, $spasi,$pdf->Image("$ta->ttd",$pdf->GetX()-3, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');
        }

        // $pdf->Image("$ta->ttd",80,200,30,0,'PNG');
        $pdf->Ln(26);
        $pdf->Cell(90, $spasi,$pa->gelar_depan.". ".$pa->name.", ".$pa->gelar_belakang, 0, 0, 'L');
        $pdf->Cell(30, $spasi,$mhs->name, 0, 0, 'L');
        $pdf->Ln(5);

        $pdf->Cell(90, $spasi,"NIP. ".$pa->nip_nik, 0, 0, 'L');
        $pdf->Cell(30, $spasi,"NPM. ".$mhs->npm, 0, 0, 'L');
        $pdf->Ln(10);
        
        $pdf->Output('I','pengajuan_bimbingan.pdf');
    }

    function form_verifikasi($ta,$jurusan)
    {
        
        $surat = $this->ta_model->get_surat($ta->id_pengajuan);
        $mhs= $this->ta_model->get_mahasiswa_detail($ta->npm);
        $admin = $this->ta_model->get_admin_detail($ta->id_pengajuan);

        if($ta->status == 7 || $ta->status == 8 || $ta->status == 4){
            $koor_approve  = $this->ta_model->get_ttd_approval($ta->id_pengajuan,'Koordinator');
            $koor_data = $this->user_model->get_dosen_data($koor_approve->id_user);
        }
        
        
            $b =  substr("$surat->created_at",0,10);
            $a = explode('-',$b);
            $tgl = $a[2].'-'.$a[1].'-'.$a[0];
        if($ta->status > 3){
            $c = substr("$surat->updated_at",0,10);
            $d = explode('-',$c);
            $e =  $this->get_month($d[1]);
            $tgl_acc = $d[2].' '.$e.' '.$d[0];
        }
        else{
            $tgl_acc = "";
        }

        switch($jurusan){
            case "Ilmu Komputer":
                $numPage = '/SOP/MIPA/7.5/II/11/002';
            break;
            case "Kimia":
                $numPage = '/SOP/MIPA/7.1/II/11';
            break;
            case "Fisika":
                $numPage = '/SOP/MIPA/17.04/II/11/002';
            break;
            case "Biologi":
                $numPage = '/SOP/MIPA/2/II/11 Pengajuan Tema Penelitian';
            break;
            case "Matematika":
                $numPage = '/PM/MIPA/2/13';
            break;
        }
        
        $kode = 1;
        $type = 'Single';

        $pdf = new FPDF('P','mm','A4');
        $spasi = 6;
        $bullet = chr(149);

        $jurusan_upper = strtoupper($jurusan);
        $pdf->number_footer(0);
        $pdf->setting_page_footer($numPage, $kode,$type);
        $pdf->set_header_jur($jurusan_upper);
        $pdf->set_header($jurusan);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);

        if($ta->jenis == "Skripsi"){
            $pdf->AddPage('P');
            $pdf->Ln(5);
            $pdf->SetFont('Times','B',11);
            $pdf->MultiCell(150, $spasi, "FORM VERIFIKASI BERKAS PERSYARATAN ".strtoupper($ta->jenis)."\nPENGAJUAN TEMA PENELITIAN DAN PEMBIMBING/PEMBAHAS",1,'C',false); 
            $pdf->SetFont('Times','',11);
            $pdf->Ln(10);

            $pdf->Cell(45, $spasi,"Nama Mahasiswa", 0, 0, 'L');
            $pdf->Cell(5, $spasi,':', 0, 0, 'C');
            $pdf->Cell(50, $spasi,$mhs->name, 0, 0, 'L');
            $pdf->Ln(8);
            $pdf->Cell(45, $spasi,"NPM", 0, 0, 'L');
            $pdf->Cell(5, $spasi,':', 0, 0, 'C');
            $pdf->Cell(50, $spasi,$mhs->npm, 0, 0, 'L');
            $pdf->Ln(8);
            $pdf->Cell(45, $spasi,"Fakultas/Jurusan", 0, 0, 'L');
            $pdf->Cell(5, $spasi,':', 0, 0, 'C');
            $pdf->Cell(50, $spasi,"Matematika dan Ilmu Pengetahuan Alam / ".$jurusan, 0, 0, 'L');
            $pdf->Ln(8);
            $pdf->Cell(45, $spasi,"Tanggal Masuk Berkas", 0, 0, 'L');
            $pdf->Cell(5, $spasi,':', 0, 0, 'C');
            $pdf->Cell(50, $spasi, $tgl, 0, 0, 'L');
            $pdf->Ln(8);

            $pdf->Cell(45, $spasi,"Kelengkapan Persyaratan", 0, 0, 'L');
            $pdf->Cell(5, $spasi,':', 0, 0, 'C');
            $pdf->Ln(8);
            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            //transkrip
            $pdf->MultiCell(150, $spasi, "Transkrip Akademik (1 lembar) yang telah ditandatangani oleh Pembantu Dekan 1 dan telah diberi cap stempel fakultas, dengan ketentuan telah lulus semua mata kuliah wajib dan pilihan yang mendukung topik skripsi",0,'J',false);
            $pdf->Ln(1);
            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            //sks
            if($jurusan == "Kimia"){
                $pdf->Cell(83, $spasi,"Telah menyelesaikan minimal 110 SKS, dengan IPK ", 0, 0, 'L');
                $pdf->SetFont("Symbol");
                $pdf->Cell(5, $spasi,chr(179)." 2,00", 0, 2, 'L');
                $pdf->SetFont('Times','',11);
                $pdf->Ln(1);
            }
            else{
                $pdf->Cell(83, $spasi,"Telah menyelesaikan minimal 100 SKS, dengan IPK ", 0, 0, 'L');
                $pdf->SetFont("Symbol");
                $pdf->Cell(5, $spasi,chr(179)." 2,00", 0, 2, 'L');
                $pdf->SetFont('Times','',11);
                // $pdf->MultiCell(150, $spasi, "Telah menyelesaikan minimal 100 SKS, dengan IPK > 2,00",0,'J',false);
                $pdf->Ln(1);
            }
            //ktm
            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "Terdaftar sebagai mahasiswa, yang dibuktikan dengan 1 lembar fotocopy KTM",0,'J',false);
            $pdf->Ln(1);
            //form pengajuan tema
            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "Mengajukan permohonan kepada Ketua Jurusan dengan mengisi Form PENGAJUAN TEMA PENELITIAN DAN PEMBIMBING/PENGUJI",0,'J',false);
            $pdf->Ln(1);
            //fotocopy ukt
            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "Fotocopy bukti lunas pembayaran SPP dari Semester 1 sampai dengan terakhir (1 lembar)",0,'J',false);
            $pdf->Ln(1);
            //krs
            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "KRS terakhir (1 lembar) yang telah ditandatangani oleh Pembimbing Akademik dan Pembantu Dekan 1 serta telah diberi cap stempel fakultas",0,'J',false);
            $pdf->Ln(1);
            //amplop
            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "Semua berkas dimasukkan kedalam map warna kuning.",0,'J',false);
            $pdf->Ln(10);

        }

        elseif($ta->jenis == "Tesis"){
            //edit
            // if($jurusan == "Matematika"){
                $pdf->AddPage('P');
                $pdf->Ln(5);
                $pdf->SetFont('Times','B',11);
                $pdf->MultiCell(150, $spasi, "FORM VERIFIKASI BERKAS PERSYARATAN ".strtoupper($ta->jenis)."\nPENGAJUAN TEMA PENELITIAN DAN PEMBIMBING/PEMBAHAS",1,'C',false); 
                $pdf->SetFont('Times','',11);
                $pdf->Ln(10);
                $pdf->Cell(45, $spasi,"Nama Mahasiswa", 0, 0, 'L');
                $pdf->Cell(5, $spasi,':', 0, 0, 'C');
                $pdf->Cell(50, $spasi,$mhs->name, 0, 0, 'L');
                $pdf->Ln(8);
                $pdf->Cell(45, $spasi,"NPM", 0, 0, 'L');
                $pdf->Cell(5, $spasi,':', 0, 0, 'C');
                $pdf->Cell(50, $spasi,$mhs->npm, 0, 0, 'L');
                $pdf->Ln(8);
                $pdf->Cell(45, $spasi,"Fakultas/Jurusan", 0, 0, 'L');
                $pdf->Cell(5, $spasi,':', 0, 0, 'C');
                $pdf->Cell(50, $spasi,"Matematika dan Ilmu Pengetahuan Alam / ".$jurusan, 0, 0, 'L');
                $pdf->Ln(8);
                $pdf->Cell(45, $spasi,"Tanggal Masuk Berkas", 0, 0, 'L');
                $pdf->Cell(5, $spasi,':', 0, 0, 'C');
                $pdf->Cell(50, $spasi,"$tgl", 0, 0, 'L');
                $pdf->Ln(8);
                
                $pdf->Cell(45, $spasi,"Kelengkapan Persyaratan", 0, 0, 'L');
                $pdf->Cell(5, $spasi,':', 0, 0, 'C');
                $pdf->Ln(8);
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Transkrip Akademik (1 lembar) yang telah ditandatangani oleh Wakil Dekan Bidang Akademik dan Kerjasama dan telah diberi cap stempel fakultas, dengan ketentuan telah lulus semua mata kuliah wajib dan pilihan yang mendukung topik tesis",0,'J',false);
                $pdf->Ln(1);
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->Cell(100, $spasi,"Telah menyelesaikan minimal  seluruh mata kuliah di semester ke-1 (11 SKS), dengan IPK ", 0,2, 'L');
                    $pdf->SetFont("Symbol");
                    $pdf->Cell(12, $spasi,chr(179)." 3,00 ", 0, 0, 'L');
                    $pdf->SetFont('Times','',11);
                    $pdf->Cell(20, $spasi,", dan atau sedang mengambil seluruh mata kuliah di semester ke-2 (9-12 SKS)", 0, 2, 'L');
                $pdf->Ln(1);
                // $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                // $pdf->MultiCell(150, $spasi, "Telah menyelesaikan minimal  seluruh mata kuliah di semester ke-1 (11 SKS) dengan IPK > 3,00, dan atau sedang mengambil seluruh mata kuliah di semester ke-2 (9-12 SKS) ",0,'J',false);
                // $pdf->Ln(1);
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Terdaftar sebagai mahasiswa, yang dibuktikan dengan fotocopy KTM (1 lembar)",0,'J',false);
                $pdf->Ln(1);
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Mengajukan permohonan kepada Ketua Program Studi Magister Matematika dengan mengisi Form. PENGAJUAN TEMA PENELITIAN DAN PEMBIMBING/PENGUJI",0,'J',false);
                $pdf->Ln(1);
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Fotocopy bukti lunas pembayaran SPP dari Semester 1 sampai dengan terakhir (1 lembar)",0,'J',false);
                $pdf->Ln(1);
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Semua berkas dimasukkan kedalam map warna kuning.",0,'J',false);
                $pdf->Ln(5);
            // }
        }

        elseif($ta->jenis == "Tugas Akhir"){
            $pdf->AddPage('P');
            $pdf->Ln(5);
            $pdf->SetFont('Times','B',11);
            $pdf->MultiCell(150, $spasi, "FORM VERIFIKASI BERKAS PERSYARATAN ".strtoupper($ta->jenis)."\nPENGAJUAN TEMA PENELITIAN DAN PEMBIMBING/PEMBAHAS",1,'C',false); 
            $pdf->SetFont('Times','',11);
            $pdf->Ln(10);

            $pdf->Cell(45, $spasi,"Nama Mahasiswa", 0, 0, 'L');
            $pdf->Cell(5, $spasi,':', 0, 0, 'C');
            $pdf->Cell(50, $spasi,$mhs->name, 0, 0, 'L');
            $pdf->Ln(8);
            $pdf->Cell(45, $spasi,"NPM", 0, 0, 'L');
            $pdf->Cell(5, $spasi,':', 0, 0, 'C');
            $pdf->Cell(50, $spasi,$mhs->npm, 0, 0, 'L');
            $pdf->Ln(8);
            $pdf->Cell(45, $spasi,"Fakultas/Jurusan", 0, 0, 'L');
            $pdf->Cell(5, $spasi,':', 0, 0, 'C');
            $pdf->Cell(50, $spasi,"Matematika dan Ilmu Pengetahuan Alam / ".$jurusan, 0, 0, 'L');
            $pdf->Ln(8);
            $pdf->Cell(45, $spasi,"Tanggal Masuk Berkas", 0, 0, 'L');
            $pdf->Cell(5, $spasi,':', 0, 0, 'C');
            $pdf->Cell(50, $spasi, $tgl, 0, 0, 'L');
            $pdf->Ln(8);

            $pdf->Cell(45, $spasi,"Kelengkapan Persyaratan", 0, 0, 'L');
            $pdf->Cell(5, $spasi,':', 0, 0, 'C');
            $pdf->Ln(8);
            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            //transkrip
            $pdf->MultiCell(150, $spasi, "Transkrip Akademik (1 lembar) yang telah ditandatangani oleh Pembantu Dekan 1 dan telah diberi cap stempel fakultas, dengan ketentuan telah lulus semua mata kuliah wajib dan pilihan yang mendukung topik skripsi",0,'J',false);
            $pdf->Ln(1);
            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');

             //sks
                $pdf->Cell(83, $spasi,"Telah menyelesaikan minimal 100 SKS, dengan IPK ", 0, 0, 'L');
                $pdf->SetFont("Symbol");
                $pdf->Cell(5, $spasi,chr(179)." 2,00", 0, 2, 'L');
                $pdf->SetFont('Times','',11);
                // $pdf->MultiCell(150, $spasi, "Telah menyelesaikan minimal 100 SKS, dengan IPK > 2,00",0,'J',false);
                $pdf->Ln(1);

            //ktm
            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "Terdaftar sebagai mahasiswa, yang dibuktikan dengan 1 lembar fotocopy KTM",0,'J',false);
            $pdf->Ln(1);
            //form pengajuan tema
            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "Mengajukan permohonan kepada Ketua Jurusan dengan mengisi Form PENGAJUAN TEMA PENELITIAN DAN PEMBIMBING/PENGUJI",0,'J',false);
            $pdf->Ln(1);
            //fotocopy ukt
            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "Fotocopy bukti lunas pembayaran SPP dari Semester 1 sampai dengan terakhir (1 lembar)",0,'J',false);
            $pdf->Ln(1);
            //krs
            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "KRS terakhir (1 lembar) yang telah ditandatangani oleh Pembimbing Akademik dan Pembantu Dekan 1 serta telah diberi cap stempel fakultas",0,'J',false);
            $pdf->Ln(1);
            //amplop
            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "Semua berkas dimasukkan kedalam map warna kuning.",0,'J',false);
            $pdf->Ln(10);
        }
        else{
            $pdf->AddPage('P');
            $pdf->Ln(5);
            $pdf->SetFont('Times','B',11);
            $pdf->MultiCell(150, $spasi, "FORM VERIFIKASI BERKAS PERSYARATAN ".strtoupper($ta->jenis)."\nPENGAJUAN TEMA PENELITIAN DAN PEMBIMBING/PEMBAHAS",1,'C',false); 
            $pdf->SetFont('Times','',11);
            $pdf->Ln(10);
        }

        $pdf->Cell(90, $spasi,"", 0, 0, 'L');
        $pdf->Cell(30, $spasi,"Bandar Lampung, ".$tgl_acc, 0, 0, 'L');
      
        
        $pdf->Ln(7);

        if($ta->jenis == "Skripsi"){
            if($ta->status < 3){
                $pdf->Cell(45, $spasi,"Mengetahui", 0, 0, 'L');
                $pdf->Ln(5);

                $pdf->Cell(90, $spasi,"Koordinator Seminar,", 0, 0, 'L');
                $pdf->Cell(30, $spasi,"Administrasi,", 0, 0, 'L');
                $pdf->Ln(5);

                //ttd
                $pdf->Cell(90, $spasi,"", 0, 0, 'L');
                $pdf->Cell(30, $spasi,"", 0, 0, 'L');

                $pdf->Ln(26);
                $pdf->Cell(90, $spasi,'', 0, 0, 'L');
                $pdf->Cell(30, $spasi,'', 0, 0, 'L');
                $pdf->Ln(5);

                $pdf->Cell(90, $spasi,"NIP. ", 0, 0, 'L');
                $pdf->Cell(30, $spasi,"NIP. ", 0, 0, 'L');
                $pdf->Ln(10);
            }
            elseif($ta->status < 7 && $ta->status != 4 && $ta->status >= 3){
                $pdf->Cell(45, $spasi,"Mengetahui", 0, 0, 'L');
                $pdf->Ln(5);

                $pdf->Cell(90, $spasi,"Koordinator Seminar,", 0, 0, 'L');
                $pdf->Cell(30, $spasi,"Administrasi,", 0, 0, 'L');
                $pdf->Ln(5);

                //ttd
                $pdf->Cell(90, $spasi,"", 0, 0, 'L');
                $pdf->Cell(30, $spasi,$pdf->Image("$admin->ttd",$pdf->GetX()-3, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');

                $pdf->Ln(26);
                $pdf->Cell(90, $spasi,'', 0, 0, 'L');
                $pdf->Cell(30, $spasi,$admin->gelar_depan." ".$admin->name.", ".$admin->gelar_belakang, 0, 0, 'L');
                $pdf->Ln(5);

                $pdf->Cell(90, $spasi,"NIP. ", 0, 0, 'L');
                $pdf->Cell(30, $spasi,"NIP. ".$admin->nip_nik, 0, 0, 'L');
                $pdf->Ln(10);
            }
            else{
                $pdf->Cell(45, $spasi,"Mengetahui", 0, 0, 'L');
                $pdf->Ln(5);

                $pdf->Cell(90, $spasi,"Koordinator Seminar,", 0, 0, 'L');
                $pdf->Cell(30, $spasi,"Administrasi,", 0, 0, 'L');
                $pdf->Ln(5);

                //ttd
                $pdf->Cell(90, $spasi,$pdf->Image("$koor_approve->ttd",$pdf->GetX()-3, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');
                $pdf->Cell(30, $spasi,$pdf->Image("$admin->ttd",$pdf->GetX()-3, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');

                $pdf->Ln(25);
                $pdf->Cell(90, $spasi,$koor_data->gelar_depan." ".$koor_data->name.", ".$koor_data->gelar_belakang, 0, 0, 'L');
                $pdf->Cell(30, $spasi,$admin->gelar_depan." ".$admin->name.", ".$admin->gelar_belakang, 0, 0, 'L');
                $pdf->Ln(5);

                $pdf->Cell(90, $spasi,"NIP. ".$koor_data->nip_nik, 0, 0, 'L');
                $pdf->Cell(30, $spasi,"NIP. ".$admin->nip_nik, 0, 0, 'L');
                $pdf->Ln(10);

            }
        }
        else{
            if($ta->status < 3){
                $pdf->Cell(45, $spasi,"Mengetahui", 0, 0, 'L');
                $pdf->Ln(5);

                $pdf->Cell(90, $spasi,"Ketua Program Studi,", 0, 0, 'L');
                $pdf->Cell(30, $spasi,"Administrasi,", 0, 0, 'L');
                $pdf->Ln(5);

                //ttd
                $pdf->Cell(90, $spasi,"", 0, 0, 'L');
                $pdf->Cell(30, $spasi,"", 0, 0, 'L');

                $pdf->Ln(26);
                $pdf->Cell(90, $spasi,'', 0, 0, 'L');
                $pdf->Cell(30, $spasi,'', 0, 0, 'L');
                $pdf->Ln(5);

                $pdf->Cell(90, $spasi,"NIP. ", 0, 0, 'L');
                $pdf->Cell(30, $spasi,"NIP. ", 0, 0, 'L');
                $pdf->Ln(10);
            }
            elseif($ta->status < 7 && $ta->status != 4 ){
                $pdf->Cell(45, $spasi,"Mengetahui", 0, 0, 'L');
                $pdf->Ln(5);

                $pdf->Cell(90, $spasi,"Ketua Program Studi,", 0, 0, 'L');
                $pdf->Cell(30, $spasi,"Administrasi,", 0, 0, 'L');
                $pdf->Ln(5);

                //ttd
                $pdf->Cell(90, $spasi,"", 0, 0, 'L');
                $pdf->Cell(30, $spasi,$pdf->Image("$admin->ttd",$pdf->GetX()-3, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');

                $pdf->Ln(26);
                $pdf->Cell(90, $spasi,'', 0, 0, 'L');
                $pdf->Cell(30, $spasi,$admin->gelar_depan." ".$admin->name.", ".$admin->gelar_belakang, 0, 0, 'L');
                $pdf->Ln(5);

                $pdf->Cell(90, $spasi,"NIP. ", 0, 0, 'L');
                $pdf->Cell(30, $spasi,"NIP. ".$admin->nip_nik, 0, 0, 'L');
                $pdf->Ln(10);
            }
            else{
                $kaprodi = $this->ta_model->get_ttd_approval($ta->id_pengajuan,'Ketua Program Studi');
                $kaprodi_data = $this->user_model->get_dosen_data($kaprodi->id_user);

                $pdf->Cell(45, $spasi,"Mengetahui", 0, 0, 'L');
                $pdf->Ln(5);

                $pdf->Cell(90, $spasi,"Ketua Program Studi,", 0, 0, 'L');
                $pdf->Cell(30, $spasi,"Administrasi,", 0, 0, 'L');
                $pdf->Ln(5);

                //ttd
                $pdf->Cell(90, $spasi,$pdf->Image("$kaprodi->ttd",$pdf->GetX()-3, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');
                $pdf->Cell(30, $spasi,$pdf->Image("$admin->ttd",$pdf->GetX()-3, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');

                $pdf->Ln(25);
                $pdf->Cell(90, $spasi,$kaprodi_data->gelar_depan." ".$kaprodi_data->name.", ".$kaprodi_data->gelar_belakang, 0, 0, 'L');
                $pdf->Cell(30, $spasi,$admin->gelar_depan." ".$admin->name.", ".$admin->gelar_belakang, 0, 0, 'L');
                $pdf->Ln(5);

                $pdf->Cell(90, $spasi,"NIP. ".$kaprodi_data->nip_nik, 0, 0, 'L');
                $pdf->Cell(30, $spasi,"NIP. ".$admin->nip_nik, 0, 0, 'L');
                $pdf->Ln(10);

            }
        }

        $pdf->Output('I','form_verifikasi.pdf');
    }

    function form_penetapan($ta,$jurusan)
    {

        $mhs = $this->ta_model->get_mahasiswa_detail($ta->npm);
        $komisi = $this->ta_model->get_komisi($ta->id_pengajuan);

        $tgl_acc = $this->ta_model->get_tgl_acc($ta->id_pengajuan);

        if($ta->status < 7 ){
            $kajur_approve= $this->ta_model->get_ttd_approval($ta->id_pengajuan,'Ketua Jurusan');
            $kajur_data = $this->user_model->get_dosen_data($kajur_approve->id_user);
        }

        $koor_approve  = $this->ta_model->get_ttd_approval($ta->id_pengajuan,'Koordinator');

        $koor_data = $this->user_model->get_dosen_data($koor_approve->id_user);
        // print_r($kajur_data);

        switch($jurusan){
            case "Ilmu Komputer":
                $numPage = '/SOP/MIPA/7.5/II/11/002';
            break;
            case "Kimia":
                $numPage = '/SOP/MIPA/7.1/II/11';
            break;
            case "Fisika":
                $numPage = '/SOP/MIPA/17.04/II/11/002';
            break;
            case "Biologi":
                $numPage = '/SOP/MIPA/2/II/11 Pengajuan Tema Penelitian';
            break;
            case "Matematika":
                $numPage = '/PM/MIPA/2/13';
            break;
        }
        
        $kode = 2;
        $type = 'Single';

        $pdf = new FPDF('P','mm','A4');
        $spasi = 6;
        $bullet = chr(149);

        $jurusan_upper = strtoupper($jurusan);
        $pdf->number_footer(0);
        $pdf->setting_page_footer($numPage, $kode,$type);
        $pdf->set_header_jur($jurusan_upper);
        $pdf->set_header($jurusan);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);

        if($ta->jenis != ""){
            $pdf->AddPage();
            $pdf->page_type('');
            $pdf->SetFont('Times','B',11);
            $pdf->MultiCell(150, $spasi, "FORMULIR PENETAPAN\nTEMA PENELITIAN DAN PEMBIMBING/PEMBAHAS ".strtoupper($ta->jenis)."\nJURUSAN ".$jurusan_upper." FMIPA UNIVERSITAS LAMPUNG",1,'C',false); 
            $pdf->SetFont('Times','',11);
            $pdf->Ln(3);
            $pdf->Cell(150, $spasi,"NO : ".$ta->no_penetapan, 0, 0, 'C');
            $pdf->Ln(7);

            $pdf->SetWidths(array(45,5, 60, 12, 5, 50));
            $pdf->SetAligns(array('L','C','L','L','C','L'));
            $pdf->RowNoBorder(array('NAMA',':',$mhs->name,'NPM',':',$mhs->npm));
            $pdf->Ln(2);
            $pdf->Cell(45, $spasi,"PROGRAM STUDI", 0, 0, 'L');
            $pdf->Cell(5, $spasi,':', 0, 0, 'C');
            $pdf->Cell(50, $spasi,$jurusan, 0, 0, 'L');
            $pdf->Ln(8);

            $pdf->Cell(45, $spasi,"Menyetujui Tema Penelitian ".$ta->jenis." Dengan", 0,0, 'L');
            $pdf->Ln(8);
            $pdf->SetWidths(array(45,5, 100));
            $pdf->SetAligns(array('L','C','J'));
                if($ta->judul_approve == '1'){
                    $pdf->RowNoBorder(array('JUDUL',':',$ta->judul1));
                }
                else{
                    $pdf->RowNoBorder(array('JUDUL',':',$ta->judul2));
                }
            $pdf->Ln();

            $pdf->Cell(45, $spasi,"Dan Menetapkan", 0,0, 'L');
            $pdf->Ln(8);
            $pdf->SetWidths(array(45,5, 60, 12, 5, 50));
            $pdf->SetAligns(array('L','C','L','L','C','L'));

            //komisi pembimbing & penguji
            $jml_kom = count($komisi);
            if($jml_kom > 5){
                $pdf->SetFont('Times','',9);
            }
            
            foreach($komisi as $kom){
                $gelar = $this->user_model->get_gelar_dosen_nip($kom->nip_nik);
                if(empty($gelar)){
                    $g_depan = "";
                    $g_belakang = "";
                }
                else{
                    $g_depan = $gelar->gelar_depan;
                    $g_belakang = $gelar->gelar_belakang;
                }

                $pdf->RowNoBorder(array(strtoupper($kom->status),':',$g_depan." ".$kom->nama.", ".$g_belakang,'NIP',':',$kom->nip_nik));
                // $pdf->Ln(1);
                $pdf->Cell(45, $spasi,"TANDA TANGAN", 0, 0, 'L');
                $pdf->Cell(5, $spasi,':', 0, 0, 'C');

                $image = $kom->ttd;
                if($image == NULL){
                    $image = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVQYV2NgYAAAAAMAAWgmWQ0AAAAASUVORK5CYII=";
                }

                $pdf->Cell(50, $spasi,$pdf->Image("$image",$pdf->GetX(), $pdf->GetY(),25,0,'PNG'), 0, 0, 'L');
                $pdf->Ln(15);
            }

            $pdf->SetFont('Times','',11);
            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"Bandar Lampung, ".$tgl_acc, 0, 0, 'L');
            $pdf->Ln(5);

            $pdf->Cell(45, $spasi,"Menyetujui", 0, 0, 'L');
            $pdf->Ln(5);
            $pdf->Cell(90, $spasi,"Ketua Jurusan,", 0, 0, 'L');
            if($ta->jenis != "Skripsi"){
                $pdf->Cell(30, $spasi,"Ketua Program Studi", 0, 0, 'L');
            }
            else{
                $pdf->Cell(30, $spasi,"Koordinator ".$ta->jenis, 0, 0, 'L');
            }
            $pdf->Ln(5);

            if($ta->status < 7){
                $pdf->Cell(90, $spasi,$pdf->Image("$kajur_approve->ttd",$pdf->GetX(), $pdf->GetY(),33,0,'PNG'), 0, 0, 'L');
            }
            else{
                $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            }

            $pdf->Cell(30, $spasi,$pdf->Image("$koor_approve->ttd",$pdf->GetX(), $pdf->GetY(),33,0,'PNG'), 0, 0, 'L');
            $pdf->Ln(20);

            if($ta->status < 7){
                $pdf->Cell(90, $spasi,$kajur_data->gelar_depan." ".$kajur_data->name.", ".$kajur_data->gelar_belakang, 0, 0, 'L');
                $pdf->Cell(30, $spasi,$koor_data->gelar_depan." ".$koor_data->name.", ".$koor_data->gelar_belakang, 0, 0, 'L');
                $pdf->Ln(5);
                $pdf->Cell(90, $spasi,"NIP. ".$kajur_data->nip_nik, 0, 0, 'L');
                $pdf->Cell(30, $spasi,"NIP. ".$koor_data->nip_nik, 0, 0, 'L');
                $pdf->Ln(10);
            }
            else{
                $pdf->Cell(90, $spasi,"", 0, 0, 'L');
                $pdf->Cell(30, $spasi,$koor_data->gelar_depan." ".$koor_data->name.", ".$koor_data->gelar_belakang, 0, 0, 'L');
                $pdf->Ln(5);
                $pdf->Cell(90, $spasi,"NIP. ", 0, 0, 'L');
                $pdf->Cell(30, $spasi,"NIP. ".$koor_data->nip_nik, 0, 0, 'L');
                $pdf->Ln(10);
            }

            $pdf->Output('I','form_penetapan.pdf');
        }

    }

    function pengajuan_seminar($seminar,$jurusan,$ta_seminar)
    {
        $mhs = $this->ta_model->get_mahasiswa_detail($ta_seminar->npm);
        $komisi = $this->ta_model->get_komisi($ta_seminar->id_pengajuan);
        $pb = $this->ta_model->get_dosen_pb1($ta_seminar->id_pengajuan);
        $pa = $this->ta_model->get_dosen_pa_detail($ta_seminar->id_pengajuan);
        $blank_image = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVQYV2NgYAAAAAMAAWgmWQ0AAAAASUVORK5CYII=";
        switch($jurusan)
        {
            case "Doktor MIPA":
            $kajur = $this->user_model->get_kajur(0);
            break;
            case "Kimia":
            $kajur = $this->user_model->get_kajur(1);
            $numPage = '/SOP/MIPA/7.1/II/12';
            break;
            case "Biologi":
            $kajur = $this->user_model->get_kajur(2);
            $numPage = '/SOP/FMIPA/7.2/IV/01';
            break;
            case "Matematika":
            $kajur = $this->user_model->get_kajur(3);
            $numPage = '/PM/MIPA/3/08';
            break;
            case "Fisika":
            $kajur = $this->user_model->get_kajur(4);
            $numPage = '/SOP/MIPA/17.04/II/12/001';
            break;
            case "Ilmu Komputer":
            $kajur = $this->user_model->get_kajur(5);
            $numPage = '/SOP/MIPA/7.5/II/11/002';
            break;
        }

        $kode = 0;
        $type = 'Single';

        //ttd
        if($seminar->status >= 1){
        $ttd_pa = $this->ta_model->get_ttd_approval_seminar($seminar->id,'Pembimbing Akademik');
        }
        if($seminar->status >= 2){
        $ttd_pb1 = $this->ta_model->get_ttd_approval_seminar($seminar->id,'Pembimbing Utama');
        }
        if($seminar->status == 4 || $seminar->status >= 8){
            $kajur_approve  = $this->ta_model->get_ttd_approval_seminar($seminar->id,'Ketua Jurusan');
            $kajur_data = $this->user_model->get_dosen_data($kajur_approve->id_user);
        }
        
        $date = substr("$seminar->created_at",0,10);
        $date = explode('-',$date);

        $bulan = $this->get_month($date[1]);
        $jurusan_upper = strtoupper($jurusan);

        $pdf = new FPDF('P','mm','A4');
        $spasi = 6;
        $pdf->number_footer(0);
        $pdf->setting_page_footer($numPage, $kode,$type);
        $pdf->set_header_jur($jurusan_upper);
        $pdf->set_header($jurusan);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);

        $pdf->AddPage();
        $pdf->SetFont('Times','B',11);
        //nomor surat
        $pdf->MultiCell(150, $spasi, "FORMULIR PENGAJUAN ".strtoupper($seminar->jenis)."\nJURUSAN ".$jurusan_upper." FMIPA UNIVERSITAS LAMPUNG",1,'C',false);
        $pdf->SetFont('Times','',11);
        $pdf->Ln(3);
        $pdf->Cell(150, $spasi,"NO:". $seminar->no_form, 0, 0, 'C');
        $pdf->Ln(9);
        $pdf->Cell(45, $spasi,"Mahasiswa berikut telah layak melaksanakan ".$seminar->jenis." Penelitian", 0,0, 'L');
        $pdf->Ln(9);

        //nama
        $pdf->SetWidths(array(45,5, 60, 12, 5, 50));
        $pdf->SetAligns(array('L','C','L','L','C','L'));
        $pdf->RowNoBorder(array('NAMA',':',$mhs->name,'NPM',':',$mhs->npm));
        $pdf->Ln(5);

        //Judul
        $pdf->SetWidths(array(45,5, 100));
        $pdf->SetAligns(array('L','C','J'));
        if($ta_seminar->judul_approve == "1"){
            $pdf->RowNoBorder(array('JUDUL',':',$ta_seminar->judul1));
        }
        else{
            $pdf->RowNoBorder(array('JUDUL',':',$ta_seminar->judul2));
        }
        $pdf->Ln(4);

        //komisi
        foreach($komisi as $kom){
            $pdf->SetWidths(array(45,5, 60, 12, 5, 50));
            $pdf->SetAligns(array('L','C','L','L','C','L'));
            $gelar = $this->user_model->get_gelar_dosen_nip($kom->nip_nik);
                if(empty($gelar)){
                    $g_depan = "";
                    $g_belakang = "";
                }
                else{
                    $g_depan = $gelar->gelar_depan;
                    $g_belakang = $gelar->gelar_belakang;
                }

            $pdf->RowNoBorder(array($kom->status_slug,':',$g_depan." ".$kom->nama.", ".$g_belakang,'NIP',':',$kom->nip_nik));
            $pdf->Ln(4);
        }

        $pdf->Ln(1);
        $pdf->Cell(90, $spasi,"", 0, 0, 'L');
        $pdf->Cell(150, $spasi,"Bandar Lampung, ".$date[2]." ".$bulan." ".$date[0]."", 0, 0, 'L');
        $pdf->Ln(7);

        $pdf->Cell(45, $spasi,"Mengetahui", 0, 0, 'L');
        $pdf->Ln(5);
        $pdf->Cell(90, $spasi,"Dosen Pembimbing Akademik,", 0, 0, 'L');
        $pdf->Cell(30, $spasi,"Pembimbing Utama,", 0, 0, 'L');
        $pdf->Ln(5);

         //ttd
        if($seminar->status == 1){
            $pdf->Cell(90, $spasi,$pdf->Image($ttd_pa->ttd,$pdf->GetX(), $pdf->GetY(),40,0,'PNG'), 0, 0, 'L'); 
            $pdf->Cell(30, $spasi,$pdf->Image("$blank_image",$pdf->GetX()-3, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');
        }
        elseif($seminar->status >= 2){
            $pdf->Cell(90, $spasi,$pdf->Image($ttd_pa->ttd,$pdf->GetX(), $pdf->GetY(),40,0,'PNG'), 0, 0, 'L'); 
            $pdf->Cell(30, $spasi,$pdf->Image($ttd_pb1->ttd,$pdf->GetX(), $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');
        }

        $pdf->Ln(25);
        $pdf->Cell(90, $spasi,$pa->gelar_depan." ".$pa->name." ".$pa->gelar_belakang, 0, 0, 'L');
        $pdf->Cell(30, $spasi,$pb->gelar_depan." ".$pb->name." ".$pb->gelar_belakang, 0, 0, 'L');
        $pdf->Ln(5);

        $pdf->Cell(90, $spasi,"NIP. ".$pa->nip_nik, 0, 0, 'L');
        $pdf->Cell(30, $spasi,"NIP. ".$pb->nip_nik, 0, 0, 'L');
        $pdf->Ln(10);

        if($seminar->status != 4 && $seminar->status <= 7){
            $pdf->Cell(150, $spasi,"Menyetujui", 0, 0, 'C');
            $pdf->Ln(5);
            $pdf->Cell(150, $spasi,"Ketua Jurusan ".$jurusan, 0, 0, 'C');
            $pdf->Ln(30);

            $pdf->Cell(150, $spasi,'', 0, 0, 'C');
            $pdf->Ln(5);
            $pdf->Cell(150, $spasi,"NIP. ", 0, 0, 'C');
        }
        else{
            $pdf->Cell(150, $spasi,"Menyetujui", 0, 0, 'C');
            $pdf->Ln(5);
            $pdf->Cell(150, $spasi,"Ketua Jurusan ".$jurusan, 0, 0, 'C');
            $pdf->Ln(5);

            $pdf->Cell(150, $spasi,$pdf->Image("$kajur_approve->ttd",$pdf->GetX()+55, $pdf->GetY(),33,0,'PNG'), 0, 0, 'C');
            $pdf->Ln(25);
            $pdf->Cell(150, $spasi,$kajur_data->gelar_depan." ".$kajur_data->name.", ".$kajur_data->gelar_belakang, 0, 0, 'C');
            $pdf->Ln(5);
            $pdf->Cell(150, $spasi,"NIP. ".$kajur_data->nip_nik, 0, 0, 'C');
        }

        $pdf->Output('I','pengajuan_seminar.pdf');

    }

    function pengajuan_seminar_ta($seminar,$jurusan,$ta_seminar)
    {
        switch($jurusan)
        {
            case "Doktor MIPA":
            $kajur = $this->user_model->get_kajur(0);
            break;
            case "Kimia":
            $kajur = $this->user_model->get_kajur(1);
            $numPage = '/SOP/MIPA/7.1/II/12';
            break;
            case "Biologi":
            $kajur = $this->user_model->get_kajur(2);
            $numPage = '/SOP/FMIPA/7.2/IV/01';
            break;
            case "Matematika":
            $kajur = $this->user_model->get_kajur(3);
            $numPage = '/PM/MIPA/3/08';
            break;
            case "Fisika":
            $kajur = $this->user_model->get_kajur(4);
            $numPage = '/SOP/MIPA/17.04/II/12/001';
            break;
            case "Ilmu Komputer":
            $kajur = $this->user_model->get_kajur(5);
            $numPage = '/SOP/MIPA/7.5/II/11/002';
            break;
        }
        $mhs = $this->ta_model->get_mahasiswa_detail($ta_seminar->npm);
        $komisi = $this->ta_model->get_komisi_seminar($seminar->id);
        $pa = $this->ta_model->get_dosen_pa_detail($ta_seminar->id_pengajuan);

        $date = substr("$seminar->created_at",0,10);
        $date = explode('-',$date);

        $kode = 3;
        $type = 'Fixed';
        $jurusan_upper = strtoupper($jurusan);

        $pdf = new FPDF('P','mm','A4');
        $spasi = 6;
        $pdf->number_footer(0);
        $pdf->setting_page_footer($numPage, $kode,$type);
        $pdf->set_header_jur($jurusan_upper);
        $pdf->set_header($jurusan);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);
        
        $pdf->AddPage('P');
        $pdf->SetFont('Times','B',11);
        $pdf->MultiCell(150, $spasi, "FORMULIR PENGAJUAN TUGAS AKHIR\nJURUSAN ".$jurusan_upper." FMIPA UNIVERSITAS LAMPUNG",1,'C',false);
        $pdf->SetFont('Times','',11);
        $pdf->Ln(3);

        $pdf->Cell(150, $spasi,"NO:".$seminar->no_form, 0, 0, 'C');
        $pdf->Ln(7);
        $pdf->Cell(45, $spasi,"Kepada Yth.", 0,0, 'L');
        $pdf->Ln(5);
        $pdf->Cell(45, $spasi,"Ketua Jurusan ".$jurusan, 0,0, 'L');
        $pdf->Ln(8);
        $pdf->Cell(45, $spasi,"Mahasiswa berikut telah layak melaksanakan Seminar Tugas Akhir :", 0,0, 'L');
        $pdf->Ln(9);

        $pdf->SetWidths(array(45,5, 100));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('NAMA / NPM',':',"$mhs->name / $mhs->npm"));
        $pdf->Ln(3);
        $pdf->SetWidths(array(45,5, 100));
        $pdf->SetAligns(array('L','C','L'));
        if($ta_seminar->judul_approve == 1){
            $pdf->RowNoBorder(array('JUDUL',':',$ta_seminar->judul1));
        }
        else{
            $pdf->RowNoBorder(array('JUDUL',':',$ta_seminar->judul2));
        }
        $pdf->Ln(4);

        foreach($komisi as $kom){
            $gelar = $this->user_model->get_gelar_dosen_nip($kom->nip_nik);
                if(empty($gelar)){
                    $g_depan = "";
                    $g_belakang = "";
                }
                else{
                    $g_depan = $gelar->gelar_depan;
                    $g_belakang = $gelar->gelar_belakang;
                }

            $pdf->SetWidths(array(45,5, 60, 12, 5, 50));
            $pdf->SetAligns(array('L','C','L','L','C','L'));
            $pdf->RowNoBorder(array($kom->status_slug,':',$g_depan." ".$kom->nama.", ".$g_belakang));
            $pdf->Ln(4);
        }
        //verifikator
        $verifikator = $this->ta_model->get_dosen_verifikator($ta_seminar->id_pengajuan);
            $gelarv = $this->user_model->get_gelar_dosen_nip($verifikator->nip_nik);
            if(empty($gelarv)){
                $g_depanv = "";
                $g_belakangv = "";
            }
            else{
                $g_depanv = $gelarv->gelar_depan;
                $g_belakangv = $gelarv->gelar_belakang;
            }
          $pdf->SetWidths(array(45,5, 60, 12, 5, 50));
          $pdf->SetAligns(array('L','C','L','L','C','L'));
          $pdf->RowNoBorder(array('Dosen Verifikasi',':',$g_depanv." ".$verifikator->nama." ".$g_belakangv));
          $pdf->Ln(2);  
        $bulan = $this->get_month($date[1]);
        $pdf->Cell(90, $spasi,"", 0, 0, 'L');
        $pdf->Cell(150, $spasi,"Bandar Lampung, ".$date[2]." ".$bulan." ".$date[0]."", 0, 0, 'L');
        $pdf->Ln(7);

        $pdf->Cell(150, $spasi,"Meyetujui", 0, 0, 'C');
        $pdf->Ln(7);
        
        foreach($komisi as $kom){
            $pdf->Cell(90, $spasi,"$kom->status_slug", 0, 0, 'L');    
        }   
        $pdf->Ln(5);

        foreach($komisi as $kom){
            if($kom->ttd != ""){
                $pdf->Cell(90, $spasi,$pdf->Image($kom->ttd,$pdf->GetX(), $pdf->GetY(),40,0,'PNG'), 0, 0, 'L'); 
            }
        }
        
        $pdf->Ln(25);
        foreach($komisi as $kom){
            $gelar = $this->user_model->get_gelar_dosen_nip($kom->nip_nik);
                if(empty($gelar)){
                    $g_depan = "";
                    $g_belakang = "";
                }
                else{
                    $g_depan = $gelar->gelar_depan;
                    $g_belakang = $gelar->gelar_belakang;
                }

            $pdf->Cell(90, $spasi,$g_depan." ".$kom->nama.", ".$g_belakang, 0, 0, 'L');   
        } 
        $pdf->Ln(5);
        foreach($komisi as $kom){
            $pdf->Cell(90, $spasi,"NIP.".$kom->nip_nik, 0, 0, 'L'); 
        }

        $pdf->Ln(10);
  
        if($seminar->status < 1){
            $pdf->Cell(150, $spasi,"Mengetahui", 0, 0, 'C');
            $pdf->Ln(7);
            $pdf->Cell(90, $spasi,"Ketua Program Studi", 0, 0, 'L');
            $pdf->Cell(90, $spasi,"Pembimbing Akademik", 0, 0, 'L');
            $pdf->Ln(5);

            $pdf->Ln(25);
            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Ln(5);

            $pdf->Cell(90, $spasi,"NIP. ", 0, 0, 'L');
            $pdf->Cell(90, $spasi,"NIP. ", 0, 0, 'L');
        }
        elseif($seminar->status >= 1 && $seminar->status < 7){
            $ttd_pa = $this->ta_model->get_ttd_approval($ta_seminar->id_pengajuan,'Pembimbing Akademik');
            $pdf->Cell(150, $spasi,"Mengetahui", 0, 0, 'C');
            $pdf->Ln(7);
            $pdf->Cell(90, $spasi,"Ketua Program Studi", 0, 0, 'L');
            $pdf->Cell(90, $spasi,"Pembimbing Akademik", 0, 0, 'L');
            $pdf->Ln(5);

            $pdf->Cell(90, $spasi,"", 0, 0, 'L'); 
            $pdf->Cell(90, $spasi,$pdf->Image($ttd_pa->ttd,$pdf->GetX(), $pdf->GetY(),40,0,'PNG'), 0, 0, 'L'); 
            $pdf->Ln(25);
            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(90, $spasi,$pa->gelar_depan." ".$pa->name.", ".$pa->gelar_belakang, 0, 0, 'L');
            $pdf->Ln(5);

            $pdf->Cell(90, $spasi,"NIP. ", 0, 0, 'L');
            $pdf->Cell(90, $spasi,"NIP. ".$pa->nip_nik, 0, 0, 'L');
        }
        elseif($seminar->status >= 1 || $seminar->status >= 7){
            $ttd_pa = $this->ta_model->get_ttd_approval($ta_seminar->id_pengajuan,'Pembimbing Akademik');

            $kaprodi_approve  = $this->ta_model->get_ttd_approval_seminar($seminar->id,'Ketua Program Studi');
            $kaprodi_data = $this->user_model->get_dosen_data($kaprodi_approve->id_user);

            $pdf->Cell(150, $spasi,"Mengetahui", 0, 0, 'C');
            $pdf->Ln(7);
            $pdf->Cell(90, $spasi,"Ketua Program Studi", 0, 0, 'L');
            $pdf->Cell(90, $spasi,"Pembimbing Akademik", 0, 0, 'L');
            $pdf->Ln(5);

            $pdf->Cell(90, $spasi,$pdf->Image($kaprodi_approve->ttd,$pdf->GetX(), $pdf->GetY(),40,0,'PNG'), 0, 0, 'L'); 
            $pdf->Cell(90, $spasi,$pdf->Image($ttd_pa->ttd,$pdf->GetX(), $pdf->GetY(),40,0,'PNG'), 0, 0, 'L'); 
            $pdf->Ln(25);
            $pdf->Cell(90, $spasi,$kaprodi_data->name, 0, 0, 'L');
            $pdf->Cell(90, $spasi,$pa->gelar_depan." ".$pa->name.", ".$pa->gelar_belakang, 0, 0, 'L');
            $pdf->Ln(5);

            $pdf->Cell(90, $spasi,"NIP. ".$kaprodi_data->nip_nik, 0, 0, 'L');
            $pdf->Cell(90, $spasi,"NIP. ".$pa->nip_nik, 0, 0, 'L');
        }


        $pdf->Output('I','pengajuan_seminar_ta.pdf');
    }

    function pengajuan_seminar_kompre($seminar,$jurusan,$ta_seminar)
    {
        $mhs = $this->ta_model->get_mahasiswa_detail($ta_seminar->npm);
        $komisi = $this->ta_model->get_komisi($ta_seminar->id_pengajuan);
        $pb = $this->ta_model->get_dosen_pb1($ta_seminar->id_pengajuan);
        $pa = $this->ta_model->get_dosen_pa_detail($ta_seminar->id_pengajuan);

        //ttd
        if($seminar->status >= 1){
            $ttd_pa = $this->ta_model->get_ttd_approval_seminar($seminar->id,'Pembimbing Akademik');
        }
        if($seminar->status >= 2){
            $ttd_pb1 = $this->ta_model->get_ttd_approval_seminar($seminar->id,'Pembimbing Utama');
        }
        if($seminar->status == 4){
            $kajur_approve  = $this->ta_model->get_ttd_approval_seminar($seminar->id,'Ketua Jurusan');
            $kajur_data = $this->user_model->get_dosen_data($kajur_approve->id_user);
        }

        switch($jurusan)
        {
            case "Doktor MIPA":           
            break;
            case "Kimia":
            $numPage = '/SOP/MIPA/7.1/II/12';
            break;
            case "Biologi":
            $numPage = '/SOP/FMIPA/7.2/IV/01';
            break;
            case "Matematika":
            $numPage = '/PM/MIPA/3/08';
            break;
            case "Fisika":
            $numPage = '/SOP/MIPA/17.04/II/12/001';
            break;
            case "Ilmu Komputer":
            $numPage = '/SOP/MIPA/7.5/II/11/002';
            break;
        }

        $kode = 0;
        $type = 'Single';

        $date = substr("$seminar->created_at",0,10);
        $date = explode('-',$date);

        $bulan = $this->get_month($date[1]);
        $jurusan_upper = strtoupper($jurusan);

        $pdf = new FPDF('P','mm','A4');
        $spasi = 6;
        $pdf->number_footer(0);
        $pdf->setting_page_footer($numPage, $kode,$type);
        $pdf->set_header_jur($jurusan_upper);
        $pdf->set_header($jurusan);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);

        $pdf->AddPage();
        $pdf->SetFont('Times','B',11);
        //nomor surat
        $pdf->MultiCell(150, $spasi, "FORMULIR PENGAJUAN UJIAN ".strtoupper($ta_seminar->jenis)."\nJURUSAN ".$jurusan_upper." FMIPA UNIVERSITAS LAMPUNG",1,'C',false);
        $pdf->SetFont('Times','',11);
        $pdf->Ln(3);
        $pdf->Cell(150, $spasi,"NO:". $seminar->no_form, 0, 0, 'C');
        $pdf->Ln(9);
        $pdf->Cell(45, $spasi,"Kepada Yth. Ketua Jurusan ".$jurusan.",", 0,0, 'L');
        $pdf->Ln(7);
        $pdf->Cell(45, $spasi,"Mahasiswa berikut telah layak melaksanakan Ujian ".$ta_seminar->jenis, 0,0, 'L');
        $pdf->Ln(9);

         //nama
         $pdf->SetWidths(array(45,5, 60, 12, 5, 50));
         $pdf->SetAligns(array('L','C','L','L','C','L'));
         $pdf->RowNoBorder(array('NAMA',':',$mhs->name,'NPM',':',$mhs->npm));
         $pdf->Ln(5);
 
         //Judul
         $pdf->SetWidths(array(45,5, 100));
         $pdf->SetAligns(array('L','C','J'));
         if($ta_seminar->judul_approve == "1"){
             $pdf->RowNoBorder(array('JUDUL',':',$ta_seminar->judul1));
         }
         else{
             $pdf->RowNoBorder(array('JUDUL',':',$ta_seminar->judul2));
         }
         $pdf->Ln(4);

        //komisi
        foreach($komisi as $kom){
            $pdf->SetWidths(array(45,5, 60, 12, 5, 50));
            $pdf->SetAligns(array('L','C','L','L','C','L'));
            $pdf->RowNoBorder(array($kom->status_slug,':',$kom->nama,'NIP',':',$kom->nip_nik));
            $pdf->Ln(4);
        }

        $pdf->Ln(1);
        $pdf->Cell(90, $spasi,"", 0, 0, 'L');
        $pdf->Cell(150, $spasi,"Bandar Lampung, ".$date[2]." ".$bulan." ".$date[0]."", 0, 0, 'L');
        $pdf->Ln(7);

        // if($ta_seminar->jenis == 'Skripsi'){

        // }
        $pdf->Cell(45, $spasi,"Mengetahui", 0, 0, 'L');
        $pdf->Ln(5);
        $pdf->Cell(90, $spasi,"Dosen Pembimbing Akademik,", 0, 0, 'L');
        $pdf->Cell(30, $spasi,"Pembimbing Utama,", 0, 0, 'L');
        $pdf->Ln(5);

        //ttd
        if($seminar->status == 1){
            $pdf->Cell(90, $spasi,$pdf->Image($ttd_pa->ttd,$pdf->GetX(), $pdf->GetY(),40,0,'PNG'), 0, 0, 'L'); 
            $pdf->Cell(30, $spasi,'', 0, 0, 'L');
        }
        elseif($seminar->status >= 2){
            $pdf->Cell(90, $spasi,$pdf->Image($ttd_pa->ttd,$pdf->GetX(), $pdf->GetY(),40,0,'PNG'), 0, 0, 'L'); 
            $pdf->Cell(30, $spasi,$pdf->Image($ttd_pb1->ttd,$pdf->GetX(), $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');
        }

        $pdf->Ln(25);
        $pdf->Cell(90, $spasi,$pa->gelar_depan." ".$pa->name.", ".$pa->gelar_belakang, 0, 0, 'L');
        $pdf->Cell(30, $spasi,$pb->gelar_depan." ".$pb->name.", ".$pb->gelar_belakang, 0, 0, 'L');
        $pdf->Ln(5);

        $pdf->Cell(90, $spasi,"NIP. ".$pa->nip_nik, 0, 0, 'L');
        $pdf->Cell(30, $spasi,"NIP. ".$pb->nip_nik, 0, 0, 'L');
        $pdf->Ln(20);

        $pdf->Output('I','pengajuan_seminar_kompre.pdf');

    }

    function verifikasi_seminar($seminar,$jurusan,$ta_seminar)
    {
        $surat = $this->ta_model->get_surat_seminar($seminar->id);
        $mhs= $this->ta_model->get_mahasiswa_detail($ta_seminar->npm);
        $admin = $this->ta_model->get_admin_seminar_detail($seminar->id);
        $b =  substr("$surat->created_at",0,10);
        $a = explode('-',$b);
        $tgl = $a[2].'-'.$a[1].'-'.$a[0];
      
        if($seminar->status < 3){
            $tgl_acc = "";
        }
        else{
            $c = substr("$surat->updated_at",0,10);
            $d = explode('-',$c);
            $e =  $this->get_month($d[1]);
            $tgl_acc = $d[2].' '.$e.' '.$d[0];
        }
        

        if($seminar->status == 7 || $seminar->status == 4 || $seminar->status == 10 || $seminar->status >= 7){
            $koor_approve  = $this->ta_model->get_ttd_approval_seminar($seminar->id,'Koordinator');
            $koor_data = $this->user_model->get_dosen_data($koor_approve->id_user);
        }



        switch($jurusan)
        {
            case "Doktor MIPA":
            break;
            case "Kimia":
            $numPage = '/SOP/MIPA/7.1/II/12';
            break;
            case "Biologi":
            $numPage = '/SOP/FMIPA/7.2/IV/01';
            break;
            case "Matematika":
            $numPage = '/PM/MIPA/3/08';
            break;
            case "Fisika":
            $numPage = '/SOP/MIPA/17.04/II/12/001';
            break;
            case "Ilmu Komputer":
            $numPage = '/SOP/MIPA/7.5/II/11/002';
            break;
        }
        if($seminar->jenis == "Seminar Tugas Akhir"){
            $kode = 4;
        }
        else{
            $kode = 1;
        }

        $type = 'Fixed';

        $pdf = new FPDF('P','mm','A4');
        $spasi = 6;
        $bullet = chr(149);

        $jurusan_upper = strtoupper($jurusan);
        $pdf->number_footer(0);
        $pdf->setting_page_footer($numPage, $kode,$type);
        $pdf->set_header_jur($jurusan_upper);
        $pdf->set_header($jurusan);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);

        $pdf->AddPage('P');
        $pdf->SetFont('Times','B',11);
        if($seminar->jenis != "Sidang Komprehensif"){
            $pdf->MultiCell(150, $spasi, "FORM VERIFIKASI BERKAS PERSYARATAN\nPENGAJUAN ".strtoupper($seminar->jenis)."",1,'C',false); 
        }
        else{
            $pdf->MultiCell(150, $spasi, "FORM VERIFIKASI BERKAS PERSYARATAN\nPENGAJUAN UJIAN ".strtoupper($ta_seminar->jenis)."",1,'C',false); 
        }
        $pdf->SetFont('Times','',11);
        $pdf->Ln(3);
        $pdf->Cell(150, $spasi,"NO:".$seminar->no_form, 0, 0, 'C');
        $pdf->Ln(6);

        $pdf->SetWidths(array(40,5, 60));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Nama Mahasiswa',':',$mhs->name));
        $pdf->Ln(1);

        $pdf->SetWidths(array(40,5, 60));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('NPM',':',$mhs->npm));
        $pdf->Ln(1);

        $pdf->SetWidths(array(40,5, 100));
        $pdf->SetAligns(array('L','C','L'));
        if($ta_seminar->judul_approve == 1){
            $pjg_judul = strlen($ta_seminar->judul1);
            if($pjg_judul > 100){
                $pdf->SetFont('Times','',10);
            }
            $pdf->RowNoBorder(array('Judul',':',$ta_seminar->judul1));
            $pdf->SetFont('Times','',11);
        }
        elseif($ta_seminar->judul_approve == 2){
            $pjg_judul = strlen($ta_seminar->judul1);
            if($pjg_judul > 100){
                $pdf->SetFont('Times','',10);
            }
            $pdf->RowNoBorder(array('Judul',':',$ta_seminar->judul2));
            $pdf->SetFont('Times','',11);
        }
        $pdf->Ln(1);

        $pdf->SetWidths(array(40,5, 100));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Fakultas / Jurusan',':','Matematika dan Ilmu Pengetahuan Alam / '.$jurusan));
        $pdf->Ln(1);

        $pdf->SetWidths(array(40,5, 100));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Tanggal Masuk Berkas',':',$tgl));
        $pdf->Ln(1);

        $pdf->Cell(50, $spasi,"Waktu Pelaksanaan", 0, 0, 'L');
        $pdf->Cell(55, $spasi,":", 0, 0, 'L');
        $pdf->Ln(6);
        $tgls = explode("-",$seminar->tgl_pelaksanaan);
        $pdf->SetWidths(array(40,5, 30));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('   Tanggal Seminar',':',$tgls[2].'-'.$tgls[1].'-'.$tgls[0]));
        $pdf->Ln(1);

        $pdf->SetWidths(array(40,5, 100));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array("   Waktu & Ruang",':',$seminar->waktu_pelaksanaan.' WIB / '.$seminar->tempat));
        // $pdf->Ln(2);

        $pdf->Cell(40, $spasi,"Kelengkapan Persyaratan", 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Ln(5);

        // seminar usul
        if($seminar->jenis == "Seminar Usul")
        {
            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "Mengisi formulir pengajuan ".$seminar->jenis,0,'J',false);
            $pdf->Ln(1);
            // KTM KIMIA
            if($jurusan == "Kimia"){
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Terdaftar sebagai mahasiswa, yang dibuktikan dengan fotocopy KTM 1 lembar.",0,'J',false);
                $pdf->Ln(1);    
            }
    
            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "Transkrip Akademik (1 lembar) yang telah di tandatangani oleh Wakil Dekan 1 dan telah diberi cap stempel fakultas, dengan ketentuan telah lulus semua mata kuliah wajib dan pilihan.",0,'J',false);
            $pdf->Ln(1);

            if($ta_seminar->jenis == "Tesis"){
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->Cell(100, $spasi,"Telah lulus semua matakuliah wajib dan pilihan yang mendukung topik tesis, minimal 23 SKS, ", 0,2, 'L');
                // $pdf->Ln(4);
                // $pdf->Cell(5, $spasi,"", 0, 0, 'L');
                    $pdf->Cell(20, $spasi,"dengan IPK ", 0, 0, 'L');
                    $pdf->SetFont("Symbol");
                    $pdf->Cell(12, $spasi,chr(179)." 3,00 ", 0, 0, 'L');
                    $pdf->SetFont('Times','',11);
                    $pdf->Cell(20, $spasi,"(semester ke-1 dan ke-2) dan atau sedang mengambil seluruh mata kuliah", 0, 2, 'L');
                    $pdf->Ln(0);
                    $pdf->Cell(5, $spasi,"", 0, 0, 'L');
                    $pdf->Cell(20, $spasi,"di semester ke-3 (9-12 SKS).", 0, 2, 'L');
                    // $pdf->Ln(1);
                $pdf->Ln(1);
            }
            elseif($ta_seminar->jenis == "Disertasi"){

            }
            else{
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->Cell(83, $spasi,"Telah menyelesaikan minimal 110 SKS, dengan IPK ", 0, 0, 'L');
                    $pdf->SetFont("Symbol");
                    $pdf->Cell(5, $spasi,chr(179)." 2,00", 0, 2, 'L');
                    $pdf->SetFont('Times','',11);
                    $pdf->Ln(1);
                $pdf->Ln(1);
            }
           

            //bimbingan kimia
            if($jurusan == "Kimia"){
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Menyerahkan 1 lembar fotocopy Jadwal Konsultasi Pembimbing (minimal 10 kali pertemuan).",0,'J',false);
                $pdf->Ln(1);    
            }

            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "Fotocopy bukti lunas pembayaran SPP  terakhir (1 lembar).",0,'J',false);
            $pdf->Ln(1);
            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "KRS terakhir (1 lembar) yang telah di tandatangani oleh Pembimbing Akademik dan Wakil Dekan 1 serta telah diberi cap stempel fakultas.",0,'J',false);
            $pdf->Ln(1);

            // DRAFT SKRIPSI
            if($jurusan == "Kimia"){
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Draft Skripsi 3 eksemplar yang sudah lengkap, ditandatangani oleh Pembimbing I dan II.",0,'J',false);
                $pdf->Ln(1);
            }

            if($jurusan == "Biologi"){
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Draft proposal penelitian (4 eksemplar) yang sudah lengkap dan ditandatangani oleh Pembimbing Skripsi dan Ketua Jurusan.",0,'J',false);
                $pdf->Ln(1);    
            }

            if($jurusan == "Ilmu Komputer" || $jurusan == "Fisika" || $jurusan == "Matematika"){
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Draft usul penelitian yang sudah lengkap dan di tandatangani oleh pembimbing I dan II.",0,'J',false);
                $pdf->Ln(1);
            }

            // KEHADIRAN SEMINAR
            if($jurusan == "Ilmu Komputer" || $jurusan == "Fisika"){
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Pernah mengikuti seminar usul/hasil minimal 5 kali untuk yang akan seminar usul.",0,'J',false);
                $pdf->Ln(1);
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Pernah mengikuti seminar usul/hasil minimal 10 kali untuk yang akan seminar hasil.",0,'J',false);
                $pdf->Ln(1);
            }
                
            if($jurusan == "Matematika"){
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Pernah mengikuti seminar usul minimal 10 kali (ditandai dengan menunjukkan Buku Kendali Akademik).",0,'J',false);
                $pdf->Ln(1);    
            }
                
            if($jurusan == "Kimia"){
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Telah mengikuti ".$jenis." Penelitian minimal 15 kali (ditandai dengan menunjukkan dan memfotocopy 1 lembar Buku Kendali Akademik).",0,'J',false);
                $pdf->Ln(1);
            }
                
            if($jurusan == "Biolgi"){
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Pernah mengikuti ".$jenis." minimal 10 kali dalam 1 tahun, (ditandai dengan menunjukkan Buku Monitoring Kegiatan Akademik Mahasiswa).",0,'J',false);
                $pdf->Ln(1);
            }

            //toefl
            if($jurusan == "Kimia"){
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Bukti sertifikat TOEFL, ditandai dengan menunjukkan dan memfotocopy 1 lembar.",0,'J',false);
                $pdf->Ln(1);
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Menyerahkan 1 lembar fotocopy Piagam PROPTI.",0,'J',false);
                $pdf->Ln(1);
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Menyerahkan 1 lembar pengisian Database pendaftaran di WEB Kimia (nama mahasiswa tertera).",0,'J',false);
                $pdf->Ln(1);
            }
                
            if($jurusan == "Biolgi"){
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Fotocopy bukti pengajuan tema penelitian dan pembimbing/pembahas (1 lembar).",0,'J',false);
                $pdf->Ln(1);
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Mendaftar paling lambat 1 (satu) minggu sebelum pelaksanaan seminar hasil.",0,'J',false);
                $pdf->Ln(1);
            }
                
            if($jurusan == "Ilmu Komputer" || $jurusan == "Fisika" || $jurusan == "Matematika" || $jurusan == "Biologi"){
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Fotocopy sertifikat TOEFL (1 lembar).",0,'J',false);
                $pdf->Ln(1);
            }

            //warna map

            if($jurusan == "Kimia"){
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Semua berkas dimasukkan kedalam map warna KUNING.",0,'J',false);
                $pdf->Ln(1);
            }
                
            if($jurusan == "Biologi"){
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Semua berkas dimasukkan ke dalam stofmap folio warna MERAH MUDA.",0,'J',false);
                $pdf->Ln(1);
            }
                
            if($jurusan == "Matematika"){
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Semua berkas dimasukkan ke dalam map warna MERAH.",0,'J',false);
                $pdf->Ln(1);
            }
                
            if($jurusan == "Ilmu Komputer" || $jurusan == "Fisika"){
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Semua berkas dimasukkan ke dalam map warna MERAH untuk seminar usul, HIJAU untuk seminar hasil",0,'J',false);
                $pdf->Ln(1);
            }
        }

        elseif($seminar->jenis == "Seminar Hasil")
        {
            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "Mengisi formulir pengajuan ".$seminar->jenis,0,'J',false);
            $pdf->Ln(1);
           // KTM KIMIA
            if($jurusan == "Kimia"){
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Terdaftar sebagai mahasiswa, yang dibuktikan dengan fotocopy KTM 1 lembar.",0,'J',false);
                $pdf->Ln(1);
                
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Transkrip Akademik (1 lembar) yang telah di tandatangani oleh Wakil Dekan 1 dan telah diberi cap stempel fakultas, dengan ketentuan telah lulus semua mata kuliah wajib dan pilihan yang mendukung topik skripsi.",0,'J',false);
                $pdf->Ln(1);

                if($ta_seminar->jenis == "Tesis"){
                    $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                    $pdf->Cell(100, $spasi,"Telah lulus semua matakuliah wajib dan pilihan yang mendukung topik tesis, minimal 30 SKS, ", 0,2, 'L');
                    // $pdf->Ln(4);
                    // $pdf->Cell(5, $spasi,"", 0, 0, 'L');
                        $pdf->Cell(20, $spasi,"dengan IPK ", 0, 0, 'L');
                        $pdf->SetFont("Symbol");
                        $pdf->Cell(12, $spasi,chr(179)." 3,00 ", 0, 2, 'L');
                        $pdf->SetFont('Times','',11);
                        // $pdf->Ln(1);
                    $pdf->Ln(1);
                }
                elseif($ta_seminar->jenis == "Disertasi"){
    
                }
                else{
                    $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                    $pdf->Cell(83, $spasi,"Telah menyelesaikan minimal 110 SKS, dengan IPK ", 0, 0, 'L');
                        $pdf->SetFont("Symbol");
                        $pdf->Cell(5, $spasi,chr(179)." 2,00", 0, 2, 'L');
                        $pdf->SetFont('Times','',11);
                        $pdf->Ln(1);
                    $pdf->Ln(1);
                }
            }
                
            if($jurusan != "Kimia"){
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Transkrip Akademik (1 lembar) yang telah di tandatangani oleh Wakil Dekan 1 dan telah diberi cap stempel fakultas, dengan ketentuan telah lulus semua mata kuliah wajib dan pilihan yang mendukung topik skripsi.",0,'J',false);
                $pdf->Ln(1);
    
                if($ta_seminar->jenis == "Tesis"){
                    $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                    $pdf->Cell(100, $spasi,"Telah lulus semua matakuliah wajib dan pilihan yang mendukung topik tesis, minimal 30 SKS, ", 0,2, 'L');
                    // $pdf->Ln(4);
                    // $pdf->Cell(5, $spasi,"", 0, 0, 'L');
                        $pdf->Cell(20, $spasi,"dengan IPK ", 0, 0, 'L');
                        $pdf->SetFont("Symbol");
                        $pdf->Cell(12, $spasi,chr(179)." 3,00 ", 0, 2, 'L');
                        $pdf->SetFont('Times','',11);
                        // $pdf->Ln(1);
                    $pdf->Ln(1);
                }
                elseif($ta_seminar->jenis == "Disertasi"){
    
                }
                else{
                    $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                    $pdf->Cell(83, $spasi,"Telah menyelesaikan minimal 110 SKS, dengan IPK ", 0, 0, 'L');
                        $pdf->SetFont("Symbol");
                        $pdf->Cell(5, $spasi,chr(179)." 2,00", 0, 2, 'L');
                        $pdf->SetFont('Times','',11);
                        $pdf->Ln(1);
                    $pdf->Ln(1);
                }
            }
                
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Fotocopy bukti lunas pembayaran SPP  terakhir (1 lembar).",0,'J',false);
                $pdf->Ln(1);
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "KRS terakhir (1 lembar) yang telah di tandatangani oleh Pembimbing Akademik dan Wakil Dekan 1 serta telah diberi cap stempel fakultas.",0,'J',false);
                $pdf->Ln(1);
                
                //bimbingan kimia
            if($jurusan == "Kimia"){
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Menyerahkan 1 lembar fotocopy Jadwal Konsultasi Pembimbing (minimal 10 kali pertemuan).",0,'J',false);
                $pdf->Ln(1);    
            }
                
                
                // KEHADIRAN SEMINAR
            if($jurusan == "Ilmu Komputer" || $jurusan == "Fisika"){
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Pernah mengikuti seminar usul/hasil minimal 5 kali untuk yang akan seminar usul.",0,'J',false);
                $pdf->Ln(1);
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Pernah mengikuti seminar usul/hasil minimal 10 kali untuk yang akan seminar hasil.",0,'J',false);
                $pdf->Ln(1);
            }
                
            if($jurusan == "Matematika"){
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Pernah mengikuti seminar hasil minimal 10 kali (ditandai dengan menunjukkan Buku Kendali Akademik).",0,'J',false);
                $pdf->Ln(1);    
            }
                
            if($jurusan == "Kimia"){
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Telah mengikuti ".$jenis." Penelitian minimal 15 kali (ditandai dengan menunjukkan dan memfotocopy 1 lembar Buku Kendali Akademik).",0,'J',false);
                $pdf->Ln(1);
            }
                
            if($jurusan == "Biologi"){
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Pernah mengikuti ".$jenis." minimal 10 kali dalam 1 tahun, (ditandai dengan menunjukkan Buku Monitoring Kegiatan Akademik Mahasiswa).",0,'J',false);
                $pdf->Ln(1);
                
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Fotocopy berita acara dan rekapitulasi penilaian seminar usul (1 rangkap).",0,'J',false);
                $pdf->Ln(1);
            }
                
                // DRAFT SKRIPSI
            if($jurusan == "Kimia"){
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Draft Skripsi 3 eksemplar yang sudah lengkap, ditandatangani oleh Pembimbing I dan II.",0,'J',false);
                $pdf->Ln(1);
            }
                
            if($jurusan == "Biologi"){
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Draft proposal penelitian (4 eksemplar) yang sudah lengkap dan ditandatangani oleh Pembimbing Skripsi dan Ketua Jurusan.",0,'J',false);
                $pdf->Ln(1);    
            }
                
            if($jurusan == "Ilmu Komputer" || $jurusan == "Fisika" || $jurusan == "Matematika"){
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Draft usul penelitian yang sudah lengkap dan di tandatangani oleh pembimbing I dan II.",0,'J',false);
                $pdf->Ln(1);
            }
                
                //toefl
            if($jurusan == "Kimia"){
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Bukti sertifikat TOEFL, ditandai dengan menunjukkan dan memfotocopy 1 lembar.",0,'J',false);
                $pdf->Ln(1);
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Menyerahkan 1 lembar fotocopy Berita Acara Usul Penelitian.",0,'J',false);
                $pdf->Ln(1);
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Menyerahkan 1 lembar pengisian Database pendaftaran di WEB Kimia (nama mahasiswa tertera).",0,'J',false);
                $pdf->Ln(1);
            }
                
            if($jurusan == "Biologi"){
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Fotocopy bukti pengajuan tema penelitian dan pembimbing/pembahas (1 lembar).",0,'J',false);
                $pdf->Ln(1);
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Draft publikasi artikel ilmiah hasil penelitian yang telah disetujui oleh Pembimbing Skripsi (1 rangkap).",0,'J',false);
                $pdf->Ln(1);
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Mendaftar paling lambat 1 (satu) minggu sebelum pelaksanaan seminar hasil.",0,'J',false);
                $pdf->Ln(1);
            }
                
            if($jurusan == "Ilmu Komputer" || $jurusan == "Fisika" || $jurusan == "Matematika" || $jurusan == "Biologi"){
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Fotocopy sertifikat TOEFL (1 lembar).",0,'J',false);
                $pdf->Ln(1);
            }
                //warna map
                
            if($jurusan == "Kimia"){
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Semua berkas dimasukkan kedalam map warna HIJAU.",0,'J',false);
                $pdf->Ln(1);
            }
                
            if($jurusan == "Biologi"){
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Semua berkas dimasukkan ke dalam stofmap folio warna HIJAU.",0,'J',false);
                $pdf->Ln(1);
            }
                
            if($jurusan == "Matematika"){
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Semua berkas dimasukkan ke dalam map warna HIJAU.",0,'J',false);
                $pdf->Ln(1);
            }
                
            if($jurusan == "Ilmu Komputer" || $jurusan == "Fisika"){
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Semua berkas dimasukkan ke dalam map warna MERAH untuk seminar usul, HIJAU untuk seminar hasil",0,'J',false);
                $pdf->Ln(1);
            }
        }

        elseif($seminar->jenis == "Seminar Tugas Akhir")
        {
            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "Mengisi formulir pengajuan Seminar Tugas Akhir",0,'J',false);
            $pdf->Ln(1);

            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "Transkrip Akademik (1 lembar) yang telah di tandatangani oleh Wakil Dekan 1 dan telah diberi cap stempel fakultas, dengan ketentuan telah lulus semua mata kuliah wajib dan pilihan yang mendukung topik skripsi.",0,'J',false);
            $pdf->Ln(1);

            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->Cell(83, $spasi,"Telah menyelesaikan minimal 110 SKS, dengan IPK ", 0, 0, 'L');
                $pdf->SetFont("Symbol");
                $pdf->Cell(5, $spasi,chr(179)." 2,00", 0, 2, 'L');
                $pdf->SetFont('Times','',11);
                $pdf->Ln(1);
            $pdf->Ln(1);

            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "Fotocopy bukti lunas pembayaran SPP terakhir (1 lembar)",0,'J',false);
            $pdf->Ln(1);

            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "KRS terakhir (1 lembar) yang telah ditandatangani oleh Pembimbing Akademik dan Pembantu Dekan 1 serta telah diberi cap stempel fakultas",0,'J',false);
            $pdf->Ln(1);

            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "Telah Melaksanakan Verifikasi Program Tugas Akhir (ditandai dengan menunjukkan Berkas Verifikasi)",0,'J',false);
            $pdf->Ln(1);

            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "Draft Tugas Akhir yang sudah lengkap dan ditandatangani oleh pembimbing utama.",0,'J',false);
            $pdf->Ln(1);

            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "Pernah mengikuti Seminar Tugas Akhir minimal 10 kali (ditandai dengan menunjukkan Buku Kendali Akademik).",0,'J',false);
            $pdf->Ln(1);

            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "TOEFL",0,'J',false);
            $pdf->Ln(1);

            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "Semua berkas dimasukkan ke dalam map warna BIRU",0,'J',false);
            $pdf->Ln(1);
        }

        elseif($seminar->jenis == "Sidang Komprehensif")
        {
            $spasi = 4;
            $pdf->SetFont('Times','',9);
            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "Draft AkhirTesis/Skripsi 3 buah.",0,'J',false);
            // $pdf->Ln(1);

            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "Transkrip Akademik (1 lembar) yang telah di tandatangani oleh Wakil Dekan 1 dan telah diberi cap stempel fakultas, dengan ketentuan telah lulus semua mata kuliah.",0,'J',false);
            // $pdf->Ln(1);

            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->Cell(115, $spasi,"Jumlah SKS wajib dalam program studi telah dipenuhi (minimal 140 SKS), dengan IPK", 0, 0, 'L');
                $pdf->SetFont("Symbol");
                $pdf->Cell(5, $spasi,chr(179)." 2,00.", 0, 2, 'L');
                $pdf->SetFont('Times','',11);
                $pdf->Ln(1);
            // $pdf->Ln(1);
            $pdf->SetFont('Times','',9);
            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "KRS semester terakhir (masing-masing 1 lembar).",0,'J',false);
            // $pdf->Ln(1);

            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "Surat keterangan tidak ada nilai MK Wajib yang belum lulus.",0,'J',false);
            // $pdf->Ln(1);

            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "Fotocopy berita acara seminar hasil (1 lembar).",0,'J',false);
            // $pdf->Ln(1);

            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "Bukti publikasi (1 lembar).",0,'J',false);
            // $pdf->Ln(1);

            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "Bukti lunas pembayaran SPP semester terakhir (1 lembar).",0,'J',false);
            // $pdf->Ln(1);

            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "Bukti bebas peminjaman alat laboratorium dengan tandatangan semua Kepala Laboratorium dan telah ditandatangani oleh Pembantu Dekan I dan telah diberi stempel fakultas, paling lama 1 (satu) bulan sebelum ujian skripsi (1 lembar).",0,'J',false);
            // $pdf->Ln(1);

            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "Bukti bebas peminjaman buku dari UPT Perpustakaan Unila (1 lembar).",0,'J',false);
            // $pdf->Ln(1);

            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "Foto copy bukti bebas peminjaman buku dari ruang baca FMIPA Unila (1 lembar).",0,'J',false);
            // $pdf->Ln(1);

            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "Bukti bebas peminjaman skripsi / buku dari Jurusan (1 lembar).",0,'J',false);

            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "Fotocopy bukti telah lulus TOEFL (minimal 450) (1 lembar).",0,'J',false);

            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "Formulir pengajuan ujian Skripsi (1 lembar) yang telah ditandatangani Pembimbing Utama, Pembimbing Akademik, dan Ketua Jurusan.",0,'J',false);
 
            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "Formulir Undangan Ujian Skripsi (3 lembar).",0,'J',false);

            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "Formulir Penilaian Ujian Skripsi (3 lembar).",0,'J',false);
 
            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "Formulir Berita Acara Ujian Skripsi (2 lembar).",0,'J',false);

            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "Pas photo terakhir berwarna 4 x 6 cm (1 lembar).",0,'J',false);

            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "Buku kendali akademik yang telah diisi lengkap dan ditandatangani Pembimbing Akademik, Pembimbing Skripsi, dan Koordinator Seminar Usul / Hasil.",0,'J',false);
            
            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "Mendaftar paling lambat1 (satu) minggu sebelum ujian skripsi dilaksanakan.",0,'J',false);
            
            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "Semua berkas dimasukkan ke dalam map warna BIRU.",0,'J',false);
            
            $pdf->SetFont('Times','',11);
        }

        
        $pdf->Cell(90, $spasi,"", 0, 0, 'L');
        $pdf->Cell(30, $spasi,"Bandar Lampung, ".$tgl_acc, 0, 0, 'L');
        $pdf->Ln(2);

            if($seminar->status < 3){
                $pdf->Cell(45, $spasi,"Mengetahui", 0, 0, 'L');
                $pdf->Ln(5);

                if($seminar->jenis != "Seminar Tugas Akhir" && $ta_seminar->jenis == "Skripsi"){
                    $pdf->Cell(90, $spasi,"Koordinator Seminar,", 0, 0, 'L');
                }
                else{
                    $pdf->Cell(90, $spasi,"Ketua Program Studi,", 0, 0, 'L');
                }
                
                $pdf->Cell(30, $spasi,"Administrasi,", 0, 0, 'L');
                $pdf->Ln(5);

                //ttd
                $pdf->Cell(90, $spasi,"", 0, 0, 'L');
                $pdf->Cell(30, $spasi,"", 0, 0, 'L');

                $pdf->Ln(15);
                $pdf->Cell(90, $spasi,'', 0, 0, 'L');
                $pdf->Cell(30, $spasi,'', 0, 0, 'L');
                $pdf->Ln(5);

                $pdf->Cell(90, $spasi,"NIP. ", 0, 0, 'L');
                $pdf->Cell(30, $spasi,"NIP. ", 0, 0, 'L');
                $pdf->Ln(10);
            }
            elseif($seminar->status < 7 && $seminar->status != 4 ){
                $pdf->Cell(45, $spasi,"Mengetahui", 0, 0, 'L');
                $pdf->Ln(5);

                if($seminar->jenis != "Seminar Tugas Akhir" && $ta_seminar->jenis == "Skripsi"){
                    $pdf->Cell(90, $spasi,"Koordinator Seminar,", 0, 0, 'L');
                }
                else{
                    $pdf->Cell(90, $spasi,"Ketua Program Studi,", 0, 0, 'L');
                }
                
                $pdf->Cell(30, $spasi,"Administrasi,", 0, 0, 'L');
                $pdf->Ln(5);

                //ttd
                $pdf->Cell(90, $spasi,"", 0, 0, 'L');
                $pdf->Cell(30, $spasi,$pdf->Image("$admin->ttd",$pdf->GetX(), $pdf->GetY()-5,30,0,'PNG'), 0, 0, 'L');

                $pdf->Ln(15);
                $pdf->Cell(90, $spasi,'', 0, 0, 'L');
                $pdf->Cell(30, $spasi,$admin->gelar_depan." ".$admin->name.", ".$admin->gelar_belakang, 0, 0, 'L');
                $pdf->Ln(5);

                $pdf->Cell(90, $spasi,"NIP. ", 0, 0, 'L');
                $pdf->Cell(30, $spasi,"NIP. ".$admin->nip_nik, 0, 0, 'L');
                $pdf->Ln(10);
            }
            else{
                $pdf->Cell(45, $spasi,"Mengetahui", 0, 0, 'L');
                $pdf->Ln(5);

                if($seminar->jenis != "Seminar Tugas Akhir" && $ta_seminar->jenis == "Skripsi"){
                    $pdf->Cell(90, $spasi,"Koordinator Seminar,", 0, 0, 'L');
                }
                else{
                    $pdf->Cell(90, $spasi,"Ketua Program Studi,", 0, 0, 'L');
                }

                $pdf->Cell(30, $spasi,"Administrasi,", 0, 0, 'L');
                $pdf->Ln(5);

                //ttd
                $pdf->Cell(90, $spasi,$pdf->Image("$koor_approve->ttd",$pdf->GetX(), $pdf->GetY()-5,30,0,'PNG'), 0, 0, 'L');
                $pdf->Cell(30, $spasi,$pdf->Image("$admin->ttd",$pdf->GetX(), $pdf->GetY()-5,30,0,'PNG'), 0, 0, 'L');

                $pdf->Ln(15);
                $pdf->Cell(90, $spasi,$koor_data->gelar_depan." ".$koor_data->name.", ".$koor_data->gelar_belakang, 0, 0, 'L');
                $pdf->Cell(30, $spasi,$admin->gelar_depan." ".$admin->name.", ".$admin->gelar_belakang, 0, 0, 'L');
                $pdf->Ln(5);

                $pdf->Cell(90, $spasi,"NIP. ".$koor_data->nip_nik, 0, 0, 'L');
                $pdf->Cell(30, $spasi,"NIP. ".$admin->nip_nik, 0, 0, 'L');
                $pdf->Ln(10);
            }
        

        $pdf->Output('I','form_verifikasi_seminar.pdf');
    }

    function undangan_seminar($seminar,$jurusan,$ta_seminar)
    {

        $date = strtotime($seminar->tgl_pelaksanaan);
        $date = date('l', $date);
        $hari = $this->get_day($date);

        switch($jurusan)
        {
            case "Doktor MIPA":
            $numPage = '';
            break;
            case "Kimia":
            $numPage = '/SOP/MIPA/7.1/II/12';
            break;
            case "Biologi":
            $numPage = '/SOP/FMIPA/7.2/IV/01';
            break;
            case "Matematika":
            $numPage = '/PM/MIPA/3/08';
            break;
            case "Fisika":
            $numPage = '/SOP/MIPA/17.04/II/12/001';
            break;
            case "Ilmu Komputer":
            $numPage = '/SOP/MIPA/7.5/II/11/002';
            break;
        }
        
        $kode = 3;
        $type = 'Fixed';
        $mhs = $this->ta_model->get_mahasiswa_detail($ta_seminar->npm);
        $komisi = $this->ta_model->get_komisi($ta_seminar->id_pengajuan);
        $jml_pbb = $this->ta_model->count_pembimbing_seminar($ta_seminar->id_pengajuan)->count;

        if($seminar->status == 7 || $seminar->status == 4 || $seminar->status == 10){
            $koor_approve  = $this->ta_model->get_ttd_approval_seminar($seminar->id,'Koordinator');
            $koor_data = $this->user_model->get_dosen_data($koor_approve->id_user);
        }
        if($seminar->status == 4 || $seminar->status == 10){
            $kajur_approve  = $this->ta_model->get_ttd_approval_seminar($seminar->id,'Ketua Jurusan');
            $kajur_data = $this->user_model->get_dosen_data($kajur_approve->id_user);
        }

        

        $dates = substr("$seminar->created_at",0,10);
        $dates = explode('-',$dates);
        $bulan = $this->get_month($dates[1]);

        $pdf = new FPDF('P','mm','A4');
        $spasi = 6;
        $bullet = chr(149);

        $jurusan_upper = strtoupper($jurusan);
        $pdf->number_footer(0);
        $pdf->setting_page_footer($numPage, $kode,$type);
        $pdf->set_header_jur($jurusan_upper);
        $pdf->set_header($jurusan);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);

        foreach($komisi as $kom){
            $pdf->AddPage('P');
            $pdf->SetFont('Times','B',11);
            $pdf->MultiCell(150, $spasi, "UNDANGAN SEMINAR ".strtoupper($seminar->jenis)."\nJURUSAN ".$jurusan_upper." FMIPA UNIVERSITAS LAMPUNG",1,'C',false); 
            $pdf->SetFont('Times','',11);
            $pdf->Ln(3);
            $pdf->Cell(150, $spasi,"NO: ".$seminar->no_undangan, 0, 0, 'C');
            $pdf->Ln(15);

            $pdf->Cell(150, $spasi,"Kepada Yth.", 0, 0, 'L');
            $pdf->Ln(8);

            $gelar = $this->user_model->get_gelar_dosen_nip($kom->nip_nik);
            if(empty($gelar)){
                $g_depan = "";
                $g_belakang = "";
            }
            else{
                $g_depan = $gelar->gelar_depan;
                $g_belakang = $gelar->gelar_belakang;
            }

            $pdf->Cell(150, $spasi,"Bapak/Ibu/Sdr/i ".$g_depan." ".$kom->nama.", ".$g_belakang, 0, 0, 'L');
            $pdf->Ln(8);

            $pdf->Cell(150, $spasi,"Di Tempat", 0, 0, 'L');
            $pdf->Ln(15);
            $pdf->Cell(150, $spasi,"Dengan Hormat,", 0, 0, 'L');
            $pdf->Ln(8);
            if($seminar->jenis == "Sidang Komprehensif"){
                    $pdf->MultiCell(150, $spasi, "Bersama ini kami mengundang Bapak/Ibu/Sdr/I sebagai ".$this->komisi_kompre($jml_pbb,$kom->status_slug).", untuk menghadiri Ujian $ta_seminar->jenis Komprehensif mahasiswa berikut:",0,'J',false);
                    $pdf->Ln(10);
            }
            else{
                $pdf->MultiCell(150, $spasi, 'Bersama ini kami mengundang Bapak/Ibu/Sdr/i, untuk menghadiri '.$seminar->jenis.' penelitian oleh mahasiswa berikut sebagai',0,'J',false);
            
            $pdf->Ln(4);
            
            $pdf->SetFont('Times','B',11);
            $pdf->Cell(150, $spasi,"$kom->status_slug", 0, 0, 'L');
            $pdf->Ln(10);
            }
            $pdf->SetFont('Times','',11);
            $pdf->SetWidths(array(30,5, 100));
            $pdf->SetAligns(array('L','C','J'));
            $pdf->RowNoBorder(array('Nama / NPM',':',$mhs->name.' / '.$mhs->npm));
            $pdf->Ln(4);
            $pdf->SetWidths(array(30,5, 100));
            $pdf->SetAligns(array('L','C','J'));
            if($ta_seminar->judul_approve == 1){
                $pdf->RowNoBorder(array('Judul ',':',$ta_seminar->judul1));
            }
            elseif($ta_seminar->judul_approve == 2){
                $pdf->RowNoBorder(array('Judul ',':',$ta_seminar->judul2));
            }
            $pdf->Ln(4);
            if($seminar->jenis == "Sidang Komprehensif"){
                $pdf->Cell(150, $spasi,"Pelaksanaan Ujian $ta_seminar->jenis", 0, 0, 'L');
            }
            else{
                $pdf->Cell(150, $spasi,'Pelaksanaan '.$seminar->jenis.' :', 0, 0, 'L');
            }
            
            $pdf->Ln(8);

            $pdf->SetWidths(array(30,5, 100));
            $pdf->SetAligns(array('L','C','J'));
            $tgl = explode('-',$seminar->tgl_pelaksanaan);
            $pdf->RowNoBorder(array('Hari / Tanggal',':',$hari.' / '.$tgl[2].'-'.$tgl[1].'-'.$tgl[0]));
            $pdf->Ln(4);

            $pdf->SetWidths(array(30,5, 100));
            $pdf->SetAligns(array('L','C','J'));
            $pdf->RowNoBorder(array('Waktu',':',$seminar->waktu_pelaksanaan.' WIB'));
            $pdf->Ln(4);
            
            $pdf->SetWidths(array(30,5, 100));
            $pdf->SetAligns(array('L','C','J'));
            $pdf->RowNoBorder(array('Tempat',':',$seminar->tempat));

            $pdf->Ln(7);
            $pdf->Cell(90, $spasi,"Mengetahui,", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"Bandar Lampung, ".$dates[2]." ".$bulan." ".$dates[0]."", 0, 0, 'L');
            $pdf->Ln(7);

            $pdf->Cell(90, $spasi,"Mengetahui,", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"Menyetujui,", 0, 0, 'L');
            $pdf->Ln(5);

            if($seminar->status == 2){
                $pdf->Cell(90, $spasi,"Ketua Jurusan ".$jurusan, 0, 0, 'L');
                if($ta_seminar->jenis != "Skripsi" && $seminar->jenis != "Sidang Komprehensif"){
                    $pdf->Cell(30, $spasi,"Ketua Program Studi", 0, 0, 'L');
                }
                elseif($seminar->jenis == "Sidang Komprehensif"){
                    $pdf->Cell(30, $spasi,"Koordinator Ujian ".$ta_seminar->jenis, 0, 0, 'L');
                }
                else{
                    $pdf->Cell(30, $spasi,"Koordinator ".$seminar->jenis, 0, 0, 'L');
                }
                $pdf->Ln(20);

                $pdf->Cell(90, $spasi,'', 0, 0, 'L');
                $pdf->Cell(30, $spasi,'', 0, 0, 'L');
                $pdf->Ln(5);
                $pdf->Cell(90, $spasi,"NIP. ", 0, 0, 'L');
                $pdf->Cell(30, $spasi,"NIP. ", 0, 0, 'L');
                $pdf->Ln(5);
            }

            if($seminar->status == 3){
                $pdf->Cell(90, $spasi,"Ketua Jurusan ".$jurusan, 0, 0, 'L');
                if($ta_seminar->jenis != "Skripsi" && $seminar->jenis != "Sidang Komprehensif"){
                    $pdf->Cell(30, $spasi,"Ketua Program Studi", 0, 0, 'L');
                }
                elseif($seminar->jenis == "Sidang Komprehensif"){
                    $pdf->Cell(30, $spasi,"Koordinator Ujian ".$ta_seminar->jenis, 0, 0, 'L');
                }
                else{
                    $pdf->Cell(30, $spasi,"Koordinator ".$seminar->jenis, 0, 0, 'L');
                }
                $pdf->Ln(20);

                $pdf->Cell(90, $spasi,'', 0, 0, 'L');
                $pdf->Cell(30, $spasi,'', 0, 0, 'L');
                $pdf->Ln(5);
                $pdf->Cell(90, $spasi,"NIP. ", 0, 0, 'L');
                $pdf->Cell(30, $spasi,"NIP. ", 0, 0, 'L');
                $pdf->Ln(5);
            }

            elseif($seminar->status == 7){
                $pdf->Cell(90, $spasi,"Ketua Jurusan ".$jurusan, 0, 0, 'L');
                if($ta_seminar->jenis != "Skripsi" && $seminar->jenis != "Sidang Komprehensif"){
                    $pdf->Cell(30, $spasi,"Ketua Program Studi", 0, 0, 'L');
                }
                elseif($seminar->jenis == "Sidang Komprehensif"){
                    $pdf->Cell(30, $spasi,"Koordinator Ujian ".$ta_seminar->jenis, 0, 0, 'L');
                }
                else{
                    $pdf->Cell(30, $spasi,"Koordinator ".$seminar->jenis, 0, 0, 'L');
                }
                $pdf->Ln(5);

                //ttd
                $pdf->Cell(90, $spasi,"", 0, 0, 'L');
                $pdf->Cell(30, $spasi,$pdf->Image("$koor_approve->ttd",$pdf->GetX(), $pdf->GetY(),33,0,'PNG'), 0, 0, 'L');

                $pdf->Ln(20);
                $pdf->Cell(90, $spasi,'', 0, 0, 'L');
                $pdf->Cell(30, $spasi,$koor_data->gelar_depan." ".$koor_data->name.", ".$koor_data->gelar_belakang, 0, 0, 'L');
                $pdf->Ln(5);
                $pdf->Cell(90, $spasi,"NIP. ", 0, 0, 'L');
                $pdf->Cell(30, $spasi,"NIP. ".$koor_data->nip_nik, 0, 0, 'L');
                $pdf->Ln(5);
            }

            elseif($seminar->status == 4 || $seminar->status == 10){
                $pdf->Cell(90, $spasi,"Ketua Jurusan ".$jurusan, 0, 0, 'L');
                if($ta_seminar->jenis != "Skripsi" && $seminar->jenis != "Sidang Komprehensif"){
                    $pdf->Cell(30, $spasi,"Ketua Program Studi", 0, 0, 'L');
                }
                elseif($seminar->jenis == "Sidang Komprehensif"){
                    $pdf->Cell(30, $spasi,"Koordinator Ujian ".$ta_seminar->jenis, 0, 0, 'L');
                }
                else{
                    $pdf->Cell(30, $spasi,"Koordinator ".$seminar->jenis, 0, 0, 'L');
                }
                $pdf->Ln(5);

                //ttd
                $pdf->Cell(90, $spasi,$pdf->Image("$kajur_approve->ttd",$pdf->GetX(), $pdf->GetY(),33,0,'PNG'), 0, 0, 'L');
                $pdf->Cell(30, $spasi,$pdf->Image("$koor_approve->ttd",$pdf->GetX(), $pdf->GetY(),33,0,'PNG'), 0, 0, 'L');

                $pdf->Ln(20);
                $pdf->Cell(90, $spasi,$kajur_data->gelar_depan." ".$kajur_data->name.", ".$kajur_data->gelar_belakang, 0, 0, 'L');
                $pdf->Cell(30, $spasi,$koor_data->gelar_depan." ".$koor_data->name.", ".$koor_data->gelar_belakang, 0, 0, 'L');
                $pdf->Ln(5);
                $pdf->Cell(90, $spasi,"NIP. ".$kajur_data->nip_nik, 0, 0, 'L');
                $pdf->Cell(30, $spasi,"NIP. ".$koor_data->nip_nik, 0, 0, 'L');
                $pdf->Ln(5);
            }


        }
        
        $pdf->Output('I','undangan_seminar.pdf');

    }

    function undangan_seminar_dosen($seminar,$jurusan,$ta_seminar,$user)
    {
        $date = strtotime($seminar->tgl_pelaksanaan);
        $date = date('l', $date);
        $hari = $this->get_day($date);

        switch($jurusan)
        {
            case "Doktor MIPA":
            $numPage = '';
            break;
            case "Kimia":
            $numPage = '/SOP/MIPA/7.1/II/12';
            break;
            case "Biologi":
            $numPage = '/SOP/FMIPA/7.2/IV/01';
            break;
            case "Matematika":
            $numPage = '/PM/MIPA/3/08';
            break;
            case "Fisika":
            $numPage = '/SOP/MIPA/17.04/II/12/001';
            break;
            case "Ilmu Komputer":
            $numPage = '/SOP/MIPA/7.5/II/11/002';
            break;
        }
        
        $kode = 3;
        $type = 'Fixed';
        $mhs = $this->ta_model->get_mahasiswa_detail($ta_seminar->npm);
        $kom = $this->ta_model->get_komisi_by_id($ta_seminar->id_pengajuan,$user);
        $jml_pbb = $this->ta_model->count_pembimbing_seminar($ta_seminar->id_pengajuan)->count;
        if($seminar->status == 7 || $seminar->status == 4){
            $koor_approve  = $this->ta_model->get_ttd_approval_seminar($seminar->id,'Koordinator');
            $koor_data = $this->user_model->get_dosen_data($koor_approve->id_user);
        }
        if($seminar->status == 4){
            $kajur_approve  = $this->ta_model->get_ttd_approval_seminar($seminar->id,'Ketua Jurusan');
            $kajur_data = $this->user_model->get_dosen_data($kajur_approve->id_user);
        }

        

        $dates = substr("$seminar->created_at",0,10);
        $dates = explode('-',$dates);
        $bulan = $this->get_month($dates[1]);

        $pdf = new FPDF('P','mm','A4');
        $spasi = 6;
        $bullet = chr(149);

        $jurusan_upper = strtoupper($jurusan);
        $pdf->number_footer(0);
        $pdf->setting_page_footer($numPage, $kode,$type);
        $pdf->set_header_jur($jurusan_upper);
        $pdf->set_header($jurusan);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);


            $pdf->AddPage('P');
            $pdf->SetFont('Times','B',11);
            $pdf->MultiCell(150, $spasi, "UNDANGAN SEMINAR ".strtoupper($seminar->jenis)."\nJURUSAN ".$jurusan_upper." FMIPA UNIVERSITAS LAMPUNG",1,'C',false); 
            $pdf->SetFont('Times','',11);
            $pdf->Ln(3);
            $pdf->Cell(150, $spasi,"NO: ".$seminar->no_undangan, 0, 0, 'C');
            $pdf->Ln(15);

            $pdf->Cell(150, $spasi,"Kepada Yth.", 0, 0, 'L');
            $pdf->Ln(8);

            $gelar = $this->user_model->get_gelar_dosen_nip($kom->nip_nik);
                if(empty($gelar)){
                    $g_depan = "";
                    $g_belakang = "";
                }
                else{
                    $g_depan = $gelar->gelar_depan;
                    $g_belakang = $gelar->gelar_belakang;
                }

            $pdf->Cell(150, $spasi,"Bapak/Ibu/Sdr/i ".$g_depan.", ".$kom->nama.", ".$g_belakang, 0, 0, 'L');
            $pdf->Ln(8);

            $pdf->Cell(150, $spasi,"Di Tempat", 0, 0, 'L');
            $pdf->Ln(15);
            $pdf->Cell(150, $spasi,"Dengan Hormat,", 0, 0, 'L');
            $pdf->Ln(8);
            if($seminar->jenis == "Sidang Komprehensif"){
                $pdf->MultiCell(150, $spasi, "Bersama ini kami mengundang Bapak/Ibu/Sdr/I sebagai ".$this->komisi_kompre($jml_pbb,$kom->status_slug).", untuk menghadiri Ujian $ta_seminar->jenis Komprehensif mahasiswa berikut:",0,'J',false);
                $pdf->Ln(10);
            }
            else{
                $pdf->MultiCell(150, $spasi, 'Bersama ini kami mengundang Bapak/Ibu/Sdr/i, untuk menghadiri '.$seminar->jenis.' penelitian oleh mahasiswa berikut sebagai',0,'J',false);
            
            $pdf->Ln(4);
            
            $pdf->SetFont('Times','B',11);
            $pdf->Cell(150, $spasi,"$kom->status_slug", 0, 0, 'L');
            $pdf->Ln(10);
            }
            $pdf->SetFont('Times','',11);
            $pdf->SetWidths(array(30,5, 100));
            $pdf->SetAligns(array('L','C','J'));
            $pdf->RowNoBorder(array('Nama / NPM',':',$mhs->name.' / '.$mhs->npm));
            $pdf->Ln(4);
            $pdf->SetWidths(array(30,5, 100));
            $pdf->SetAligns(array('L','C','J'));
            if($ta_seminar->judul_approve == 1){
                $pdf->RowNoBorder(array('Judul ',':',$ta_seminar->judul1));
            }
            elseif($ta_seminar->judul_approve == 2){
                $pdf->RowNoBorder(array('Judul ',':',$ta_seminar->judul2));
            }
            $pdf->Ln(4);
            $pdf->Cell(150, $spasi,'Pelaksanaan '.$seminar->jenis.' :', 0, 0, 'L');
            $pdf->Ln(8);

            $pdf->SetWidths(array(30,5, 100));
            $pdf->SetAligns(array('L','C','J'));
            $tgl = explode('-',$seminar->tgl_pelaksanaan);
            $pdf->RowNoBorder(array('Hari / Tanggal',':',$hari.' / '.$tgl[2].'-'.$tgl[1].'-'.$tgl[0]));
            $pdf->Ln(4);

            $pdf->SetWidths(array(30,5, 100));
            $pdf->SetAligns(array('L','C','J'));
            $pdf->RowNoBorder(array('Waktu',':',$seminar->waktu_pelaksanaan.' WIB'));
            $pdf->Ln(4);
            
            $pdf->SetWidths(array(30,5, 100));
            $pdf->SetAligns(array('L','C','J'));
            $pdf->RowNoBorder(array('Tempat',':',$seminar->tempat));

            $pdf->Ln(7);
            $pdf->Cell(150, $spasi,"Bandar Lampung, ".$dates[2]." ".$bulan." ".$dates[0]."", 0, 0, 'R');
            $pdf->Ln(7);

            $pdf->Cell(90, $spasi,"Mengetahui,", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"Menyetujui,", 0, 0, 'L');
            $pdf->Ln(5);

            if($seminar->status == 3){
                $pdf->Cell(90, $spasi,"Ketua Jurusan ".$jurusan, 0, 0, 'L');
                if($ta_seminar->jenis != "Skripsi" && $seminar->jenis != "Sidang Komprehensif"){
                    $pdf->Cell(30, $spasi,"Ketua Program Studi", 0, 0, 'L');
                }
                elseif($seminar->jenis == "Sidang Komprehensif"){
                    $pdf->Cell(30, $spasi,"Koordinator Ujian ".$ta_seminar->jenis, 0, 0, 'L');
                }
                else{
                    $pdf->Cell(30, $spasi,"Koordinator ".$seminar->jenis, 0, 0, 'L');
                }
                $pdf->Ln(20);

                $pdf->Cell(90, $spasi,'', 0, 0, 'L');
                $pdf->Cell(30, $spasi,'', 0, 0, 'L');
                $pdf->Ln(5);
                $pdf->Cell(90, $spasi,"NIP. ", 0, 0, 'L');
                $pdf->Cell(30, $spasi,"NIP. ", 0, 0, 'L');
                $pdf->Ln(5);
            }

            elseif($seminar->status == 7){
                $pdf->Cell(90, $spasi,"Ketua Jurusan ".$jurusan, 0, 0, 'L');
                if($ta_seminar->jenis != "Skripsi" && $seminar->jenis != "Sidang Komprehensif"){
                    $pdf->Cell(30, $spasi,"Ketua Program Studi", 0, 0, 'L');
                }
                elseif($seminar->jenis == "Sidang Komprehensif"){
                    $pdf->Cell(30, $spasi,"Koordinator Ujian ".$ta_seminar->jenis, 0, 0, 'L');
                }
                else{
                    $pdf->Cell(30, $spasi,"Koordinator ".$seminar->jenis, 0, 0, 'L');
                }
                $pdf->Ln(5);

                //ttd
                $pdf->Cell(90, $spasi,"", 0, 0, 'L');
                $pdf->Cell(30, $spasi,$pdf->Image("$koor_approve->ttd",$pdf->GetX(), $pdf->GetY(),33,0,'PNG'), 0, 0, 'L');

                $pdf->Ln(20);
                $pdf->Cell(90, $spasi,'', 0, 0, 'L');
                $pdf->Cell(30, $spasi,$koor_data->gelar_depan." ".$koor_data->name.", ".$koor_data->gelar_belakang, 0, 0, 'L');
                $pdf->Ln(5);
                $pdf->Cell(90, $spasi,"NIP. ", 0, 0, 'L');
                $pdf->Cell(30, $spasi,"NIP. ".$koor_data->nip_nik, 0, 0, 'L');
                $pdf->Ln(5);
            }

            elseif($seminar->status == 4){
                $pdf->Cell(90, $spasi,"Ketua Jurusan ".$jurusan, 0, 0, 'L');
                if($ta_seminar->jenis != "Skripsi" && $seminar->jenis != "Sidang Komprehensif"){
                    $pdf->Cell(30, $spasi,"Ketua Program Studi", 0, 0, 'L');
                }
                elseif($seminar->jenis == "Sidang Komprehensif"){
                    $pdf->Cell(30, $spasi,"Koordinator Ujian ".$ta_seminar->jenis, 0, 0, 'L');
                }
                else{
                    $pdf->Cell(30, $spasi,"Koordinator ".$seminar->jenis, 0, 0, 'L');
                }
                $pdf->Ln(5);

                //ttd
                $pdf->Cell(90, $spasi,$pdf->Image("$kajur_approve->ttd",$pdf->GetX(), $pdf->GetY(),33,0,'PNG'), 0, 0, 'L');
                $pdf->Cell(30, $spasi,$pdf->Image("$koor_approve->ttd",$pdf->GetX(), $pdf->GetY(),33,0,'PNG'), 0, 0, 'L');

                $pdf->Ln(20);
                $pdf->Cell(90, $spasi,$kajur_data->gelar_depan." ".$kajur_data->name.", ".$kajur_data->gelar_belakang, 0, 0, 'L');
                $pdf->Cell(30, $spasi,$koor_data->gelar_depan." ".$koor_data->name.", ".$koor_data->gelar_belakang, 0, 0, 'L');
                $pdf->Ln(5);
                $pdf->Cell(90, $spasi,"NIP. ".$kajur_data->nip_nik, 0, 0, 'L');
                $pdf->Cell(30, $spasi,"NIP. ".$koor_data->nip_nik, 0, 0, 'L');
                $pdf->Ln(5);
            }

        
        $pdf->Output('I','undangan_seminar.pdf');
    }

    function undangan_dosen($seminar,$jurusan,$ta_seminar,$status)
    {
        $date = strtotime($seminar->tgl_pelaksanaan);
        $date = date('l', $date);
        $hari = $this->get_day($date);

        switch($jurusan)
        {
            case "Doktor MIPA":
            $numPage = '';
            break;
            case "Kimia":
            $numPage = '/SOP/MIPA/7.1/II/12';
            break;
            case "Biologi":
            $numPage = '/SOP/FMIPA/7.2/IV/01';
            break;
            case "Matematika":
            $numPage = '/PM/MIPA/3/08';
            break;
            case "Fisika":
            $numPage = '/SOP/MIPA/17.04/II/12/001';
            break;
            case "Ilmu Komputer":
            $numPage = '/SOP/MIPA/7.5/II/11/002';
            break;
        }

        switch($status)
        {
            case "pb2":
            $status = 'Pembimbing 2';
            break;
            case "pb3":
            $status = 'Pembimbing 3';
            break;
            case "ps1":
            $status = 'Penguji 1';
            break;
            case "ps2":
            $status = 'Penguji 2';
            break;
            case "ps3":
            $status = 'Penguji 3';
            break;
        }
        
        $kode = 3;
        $type = 'Fixed';
        $mhs = $this->ta_model->get_mahasiswa_detail($ta_seminar->npm);
        $kom = $this->ta_model->get_komisi_by_status_slug($ta_seminar->id_pengajuan,$status);
        $jml_pbb = $this->ta_model->count_pembimbing_seminar($ta_seminar->id_pengajuan)->count;
        if($seminar->status == 7 || $seminar->status == 4){
            $koor_approve  = $this->ta_model->get_ttd_approval_seminar($seminar->id,'Koordinator');
            $koor_data = $this->user_model->get_dosen_data($koor_approve->id_user);
        }
        if($seminar->status == 4){
            $kajur_approve  = $this->ta_model->get_ttd_approval_seminar($seminar->id,'Ketua Jurusan');
            $kajur_data = $this->user_model->get_dosen_data($kajur_approve->id_user);
        }

        

        $dates = substr("$seminar->created_at",0,10);
        $dates = explode('-',$dates);
        $bulan = $this->get_month($dates[1]);

        $pdf = new FPDF('P','mm','A4');
        $spasi = 6;
        $bullet = chr(149);

        $jurusan_upper = strtoupper($jurusan);
        $pdf->number_footer(0);
        $pdf->setting_page_footer($numPage, $kode,$type);
        $pdf->set_header_jur($jurusan_upper);
        $pdf->set_header($jurusan);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);


            $pdf->AddPage('P');
            $pdf->SetFont('Times','B',11);
            $pdf->MultiCell(150, $spasi, "UNDANGAN SEMINAR ".strtoupper($seminar->jenis)."\nJURUSAN ".$jurusan_upper." FMIPA UNIVERSITAS LAMPUNG",1,'C',false); 
            $pdf->SetFont('Times','',11);
            $pdf->Ln(3);
            $pdf->Cell(150, $spasi,"NO: ".$seminar->no_undangan, 0, 0, 'C');
            $pdf->Ln(15);

            $pdf->Cell(150, $spasi,"Kepada Yth.", 0, 0, 'L');
            $pdf->Ln(8);

            $gelar = $this->user_model->get_gelar_dosen_nip($kom->nip_nik);
                if(empty($gelar)){
                    $g_depan = "";
                    $g_belakang = "";
                }
                else{
                    $g_depan = $gelar->gelar_depan;
                    $g_belakang = $gelar->gelar_belakang;
                }

            $pdf->Cell(150, $spasi,"Bapak/Ibu/Sdr/i ". $g_depan." ".$kom->nama.", ".$g_belakang, 0, 0, 'L');
            $pdf->Ln(8);

            $pdf->Cell(150, $spasi,"Di Tempat", 0, 0, 'L');
            $pdf->Ln(15);
            $pdf->Cell(150, $spasi,"Dengan Hormat,", 0, 0, 'L');
            $pdf->Ln(8);
            if($seminar->jenis == "Sidang Komprehensif"){
                $pdf->MultiCell(150, $spasi, "Bersama ini kami mengundang Bapak/Ibu/Sdr/I sebagai ".$this->komisi_kompre($jml_pbb,$kom->status_slug).", untuk menghadiri Ujian $ta_seminar->jenis Komprehensif mahasiswa berikut:",0,'J',false);
                $pdf->Ln(10);
            }
            else{
                $pdf->MultiCell(150, $spasi, 'Bersama ini kami mengundang Bapak/Ibu/Sdr/i, untuk menghadiri '.$seminar->jenis.' penelitian oleh mahasiswa berikut sebagai',0,'J',false);
            
            $pdf->Ln(4);
            
            $pdf->SetFont('Times','B',11);
            $pdf->Cell(150, $spasi,"$kom->status_slug", 0, 0, 'L');
            $pdf->Ln(10);
            }
            $pdf->SetFont('Times','',11);
            $pdf->SetWidths(array(30,5, 100));
            $pdf->SetAligns(array('L','C','J'));
            $pdf->RowNoBorder(array('Nama / NPM',':',$mhs->name.' / '.$mhs->npm));
            $pdf->Ln(4);
            $pdf->SetWidths(array(30,5, 100));
            $pdf->SetAligns(array('L','C','J'));
            if($ta_seminar->judul_approve == 1){
                $pdf->RowNoBorder(array('Judul ',':',$ta_seminar->judul1));
            }
            elseif($ta_seminar->judul_approve == 2){
                $pdf->RowNoBorder(array('Judul ',':',$ta_seminar->judul2));
            }
            $pdf->Ln(4);
            $pdf->Cell(150, $spasi,'Pelaksanaan '.$seminar->jenis.' :', 0, 0, 'L');
            $pdf->Ln(8);

            $pdf->SetWidths(array(30,5, 100));
            $pdf->SetAligns(array('L','C','J'));
            $tgl = explode('-',$seminar->tgl_pelaksanaan);
            $pdf->RowNoBorder(array('Hari / Tanggal',':',$hari.' / '.$tgl[2].'-'.$tgl[1].'-'.$tgl[0]));
            $pdf->Ln(4);

            $pdf->SetWidths(array(30,5, 100));
            $pdf->SetAligns(array('L','C','J'));
            $pdf->RowNoBorder(array('Waktu',':',$seminar->waktu_pelaksanaan.' WIB'));
            $pdf->Ln(4);
            
            $pdf->SetWidths(array(30,5, 100));
            $pdf->SetAligns(array('L','C','J'));
            $pdf->RowNoBorder(array('Tempat',':',$seminar->tempat));

            $pdf->Ln(7);
            $pdf->Cell(150, $spasi,"Bandar Lampung, ".$dates[2]." ".$bulan." ".$dates[0]."", 0, 0, 'R');
            $pdf->Ln(7);

            $pdf->Cell(90, $spasi,"Mengetahui,", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"Menyetujui,", 0, 0, 'L');
            $pdf->Ln(5);

            if($seminar->status == 3){
                $pdf->Cell(90, $spasi,"Ketua Jurusan ".$jurusan, 0, 0, 'L');
                if($ta_seminar->jenis != "Skripsi" && $seminar->jenis != "Sidang Komprehensif"){
                    $pdf->Cell(30, $spasi,"Ketua Program Studi", 0, 0, 'L');
                }
                elseif($seminar->jenis == "Sidang Komprehensif"){
                    $pdf->Cell(30, $spasi,"Koordinator Ujian ".$ta_seminar->jenis, 0, 0, 'L');
                }
                else{
                    $pdf->Cell(30, $spasi,"Koordinator ".$seminar->jenis, 0, 0, 'L');
                }
                $pdf->Ln(20);

                $pdf->Cell(90, $spasi,'', 0, 0, 'L');
                $pdf->Cell(30, $spasi,'', 0, 0, 'L');
                $pdf->Ln(5);
                $pdf->Cell(90, $spasi,"NIP. ", 0, 0, 'L');
                $pdf->Cell(30, $spasi,"NIP. ", 0, 0, 'L');
                $pdf->Ln(5);
            }

            elseif($seminar->status == 7){
                $pdf->Cell(90, $spasi,"Ketua Jurusan ".$jurusan, 0, 0, 'L');
                if($ta_seminar->jenis != "Skripsi" && $seminar->jenis != "Sidang Komprehensif"){
                    $pdf->Cell(30, $spasi,"Ketua Program Studi", 0, 0, 'L');
                }
                elseif($seminar->jenis == "Sidang Komprehensif"){
                    $pdf->Cell(30, $spasi,"Koordinator Ujian ".$ta_seminar->jenis, 0, 0, 'L');
                }
                else{
                    $pdf->Cell(30, $spasi,"Koordinator ".$seminar->jenis, 0, 0, 'L');
                }
                $pdf->Ln(5);

                //ttd
                $pdf->Cell(90, $spasi,"", 0, 0, 'L');
                $pdf->Cell(30, $spasi,$pdf->Image("$koor_approve->ttd",$pdf->GetX(), $pdf->GetY(),33,0,'PNG'), 0, 0, 'L');

                $pdf->Ln(20);
                $pdf->Cell(90, $spasi,'', 0, 0, 'L');
                $pdf->Cell(30, $spasi,$koor_data->gelar_depan." ".$koor_data->name.", ".$koor_data->gelar_belakang, 0, 0, 'L');
                $pdf->Ln(5);
                $pdf->Cell(90, $spasi,"NIP. ", 0, 0, 'L');
                $pdf->Cell(30, $spasi,"NIP. ".$koor_data->nip_nik, 0, 0, 'L');
                $pdf->Ln(5);
            }

            elseif($seminar->status == 4){
                $pdf->Cell(90, $spasi,"Ketua Jurusan ".$jurusan, 0, 0, 'L');
                if($ta_seminar->jenis != "Skripsi" && $seminar->jenis != "Sidang Komprehensif"){
                    $pdf->Cell(30, $spasi,"Ketua Program Studi", 0, 0, 'L');
                }
                elseif($seminar->jenis == "Sidang Komprehensif"){
                    $pdf->Cell(30, $spasi,"Koordinator Ujian ".$ta_seminar->jenis, 0, 0, 'L');
                }
                else{
                    $pdf->Cell(30, $spasi,"Koordinator ".$seminar->jenis, 0, 0, 'L');
                }
                $pdf->Ln(5);

                //ttd
                $pdf->Cell(90, $spasi,$pdf->Image("$kajur_approve->ttd",$pdf->GetX(), $pdf->GetY(),33,0,'PNG'), 0, 0, 'L');
                $pdf->Cell(30, $spasi,$pdf->Image("$koor_approve->ttd",$pdf->GetX(), $pdf->GetY(),33,0,'PNG'), 0, 0, 'L');

                $pdf->Ln(20);
                $pdf->Cell(90, $spasi,$kajur_data->gelar_depan." ".$kajur_data->name.", ".$kajur_data->gelar_belakang, 0, 0, 'L');
                $pdf->Cell(30, $spasi,$koor_data->gelar_depan." ".$koor_data->name.", ".$koor_data->gelar_belakang, 0, 0, 'L');
                $pdf->Ln(5);
                $pdf->Cell(90, $spasi,"NIP. ".$kajur_data->nip_nik, 0, 0, 'L');
                $pdf->Cell(30, $spasi,"NIP. ".$koor_data->nip_nik, 0, 0, 'L');
                $pdf->Ln(5);
            }

        
        $pdf->Output('I','undangan_seminar.pdf');
    }

    function penilaian_seminar($seminar,$jurusan,$ta_seminar)
    {
        switch($jurusan)
        {
            case "Doktor MIPA":
            $numPage = '';
            break;
            case "Kimia":
            $numPage = '/SOP/MIPA/7.1/II/12';
            break;
            case "Biologi":
            $numPage = '/SOP/FMIPA/7.2/IV/01';
            break;
            case "Matematika":
            $numPage = '/PM/MIPA/3/08';
            break;
            case "Fisika":
            $numPage = '/SOP/MIPA/17.04/II/12/001';
            break;
            case "Ilmu Komputer":
            $numPage = '/SOP/MIPA/7.5/II/11/002';
            break;
        }

        $kode = 4;
        $type = 'Fixed';
        $mhs = $this->ta_model->get_mahasiswa_detail($ta_seminar->npm);
        $komisi = $this->ta_model->get_komisi_seminar_check($seminar->id);

        $pdf = new FPDF('P','mm','A4');
        $spasi = 6;
        $bullet = chr(149);

        $jurusan_upper = strtoupper($jurusan);
        $pdf->number_footer(0);
        $pdf->setting_page_footer($numPage, $kode,$type);
        $pdf->set_header_jur($jurusan_upper);
        $pdf->set_header($jurusan);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);

        foreach($komisi as $kom){
            $nilai1 = $this->ta_model->get_komponen_nilai_seminar_ujian($seminar->id,$kom->status);
            $nilai2 = $this->ta_model->get_komponen_nilai_seminar_ta($seminar->id,$kom->status);
            $unsur = $this->ta_model->get_unsur_distinct($nilai1[0]->id_komponen);

            $kom_data = $this->ta_model->get_komisi_seminar_data($seminar->id,$kom->status);

            $pdf->AddPage('P');
            $pdf->SetFont('Times','B',11);
            $pdf->MultiCell(150, $spasi, "FORMULIR PENILAIAN SEMINAR ".strtoupper($seminar->jenis)." PENELITIAN\nJURUSAN ".$jurusan_upper." FMIPA UNIVERSITAS LAMPUNG",1,'C',false); 
            $pdf->SetFont('Times','',11);
            $pdf->Ln(5);

            //nama judul
            $pdf->SetWidths(array(30,5, 100));
            $pdf->SetAligns(array('L','C','J'));
            $pdf->RowNoBorder(array('Nama / NPM',':',$mhs->name.' / '.$mhs->npm));
            $pdf->Ln(5);
            $pdf->SetWidths(array(30,5, 100));
            $pdf->SetAligns(array('L','C','J'));
            if($ta_seminar->judul_approve == 1){
                $pdf->RowNoBorder(array('Judul ',':',$ta_seminar->judul1));
            }
            elseif($ta_seminar->judul_approve == 2){
                $pdf->RowNoBorder(array('Judul ',':',$ta_seminar->judul2));
            }
            $pdf->Ln(5);  

            //tables
            $pdf->Cell(80,10,'Aspek yang dinilai',1,0,'C',0);
            $pdf->Cell(20,10,'Nilai',1,0,'C',0);
            $pdf->Cell(30,10,'Persentase',1,0,'C',0);
            $pdf->Cell(20,10,'NA',1,0,'C',0);

            //nilai 1
            $pdf->Ln();
            $pdf->Cell(80,7,"1. ".strtoupper($unsur[0]->unsur),1,0,'L',0);
            $pdf->Cell(20,7,'',1,0,'C',0);
            $pdf->Cell(30,7,'',1,0,'C',0);
            $pdf->Cell(20,7,'',1,0,'C',0);
            $n = 1;
            $nilai1_total = 0;
            foreach($nilai1 as $nilai_ujian){
                $pdf->Ln();
                $pdf->Cell(80,7,"       $n. ".$nilai_ujian->attribut,1,0,'L',0);
                $pdf->Cell(20,7,$nilai_ujian->nilai,1,0,'C',0);
                $pdf->Cell(30,7,$nilai_ujian->persentase." %",1,0,'C',0);
                $n1 = $nilai_ujian->nilai * ($nilai_ujian->persentase / 100);
                $pdf->Cell(20,7,$n1,1,0,'C',0);
                $nilai1_total += $n1;
                $n++;
            }

            //nilai 2
            $pdf->Ln();
            $pdf->Cell(80,7,"2. ".strtoupper($unsur[1]->unsur),1,0,'L',0);
            $pdf->Cell(20,7,'',1,0,'C',0);
            $pdf->Cell(30,7,'',1,0,'C',0);
            $pdf->Cell(20,7,'',1,0,'C',0);
            $n = 1;
            $nilai2_total = 0;
            foreach($nilai2 as $nilai_ta){
                $pdf->Ln();
                $pdf->Cell(80,7,"       $n. ".$nilai_ta->attribut,1,0,'L',0);
                $pdf->Cell(20,7,$nilai_ta->nilai,1,0,'C',0);
                $pdf->Cell(30,7,$nilai_ta->persentase." %",1,0,'C',0);
                $n2 = $nilai_ta->nilai * ($nilai_ta->persentase / 100);
                $pdf->Cell(20,7,$n2,1,0,'C',0);
                $nilai2_total += $n2;
                $n++;
            }

            //total
            $pdf->Ln();
            $pdf->Cell(130,10,'NILAI TOTAL',1,0,'C',0);
            $pdf->Cell(20,10,$nilai1_total + $nilai2_total,1,0,'C',0);
            $pdf->Ln();

            $pdf->Ln(5);
            $pdf->SetWidths(array(30,5,100));
            $pdf->SetAligns(array('L','C','J'));
            $pdf->RowNoBorder(array('Saran',':',$kom->saran));
            $pdf->Ln(20);

            $tgl = explode("-",substr("$kom->updated_at",0,10));
            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(120, $spasi,'Bandar Lampung, '.$tgl[2].' '.$this->get_month($tgl[1]).' '.$tgl[0], 0, 0, 'L');
            $pdf->Ln(7);

            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(120, $spasi,"$kom->status", 0, 0, 'L');
            $pdf->Ln(5);

             //ttd
            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(30, $spasi,$pdf->Image("$kom->ttd",$pdf->GetX(), $pdf->GetY(),33,0,'PNG'), 0, 0, 'L');

            $gelar = $this->user_model->get_gelar_dosen_nip($kom_data->nip_nik);
                if(empty($gelar)){
                    $g_depan = "";
                    $g_belakang = "";
                }
                else{
                    $g_depan = $gelar->gelar_depan;
                    $g_belakang = $gelar->gelar_belakang;
                }

            $pdf->Ln(25);
            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(30, $spasi,$g_depan." ".$kom_data->nama.", ".$g_belakang, 0, 0, 'L');
            $pdf->Ln(5);
            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"NIP. ".$kom_data->nip_nik, 0, 0, 'L');
            $pdf->Ln(5);

        }

            $pdf->Output('I','form_penilaian_seminar.pdf');
    }

    function penilaian_kompre($seminar,$jurusan,$ta_seminar)
    {
        switch($jurusan)
        {
            case "Doktor MIPA":
            $numPage = '';
            break;
            case "Kimia":
            $numPage = '/SOP/MIPA/7.1/II/12';
            break;
            case "Biologi":
            $numPage = '/SOP/FMIPA/7.2/IV/01';
            break;
            case "Matematika":
            $numPage = '/PM/MIPA/3/08';
            break;
            case "Fisika":
            $numPage = '/SOP/MIPA/17.04/II/12/001';
            break;
            case "Ilmu Komputer":
            $numPage = '/SOP/MIPA/7.5/II/11/002';
            break;
        }

        $kode = 4;
        $type = 'Fixed';
        $mhs = $this->ta_model->get_mahasiswa_detail($ta_seminar->npm);
        $jml_pbb = $this->ta_model->count_pembimbing_seminar($ta_seminar->id_pengajuan)->count;

        $pdf = new FPDF('P','mm','A4');
        $spasi = 6;
        $bullet = chr(149);

        $jurusan_upper = strtoupper($jurusan);
        $pdf->number_footer(0);
        $pdf->setting_page_footer($numPage, $kode,$type);
        $pdf->set_header_jur($jurusan_upper);
        $pdf->set_header($jurusan);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);

        if($seminar->status < 3 ){
            $komisi = $this->ta_model->get_komisi($ta_seminar->id_pengajuan);
            $komponen_ujian = $this->ta_model->get_komponen_meta_attribut_ujian($mhs->jurusan,$seminar->jenis);
            $komponen_skripsi = $this->ta_model->get_komponen_meta_attribut_skripsi($mhs->jurusan,$seminar->jenis);
            $unsur = $this->ta_model->get_unsur_distinct($komponen_ujian[0]->id_komponen);

            foreach($komisi as $kom){
                    $pdf->AddPage('P');
                    $pdf->SetFont('Times','B',11);
                    $pdf->MultiCell(150, $spasi, "FORMULIR PENILAIAN SEMINAR ".strtoupper($seminar->jenis)." PENELITIAN\nJURUSAN ".$jurusan_upper." FMIPA UNIVERSITAS LAMPUNG",1,'C',false); 
                    $pdf->SetFont('Times','',11);
                    $pdf->Ln(5);
    
                    //nama judul
                    $pdf->SetWidths(array(30,5, 100));
                    $pdf->SetAligns(array('L','C','J'));
                    $pdf->RowNoBorder(array('Nama / NPM',':',$mhs->name.' / '.$mhs->npm));
                    $pdf->Ln(5);
                    $pdf->SetWidths(array(30,5, 100));
                    $pdf->SetAligns(array('L','C','J'));
                    if($ta_seminar->judul_approve == 1){
                        $pdf->RowNoBorder(array('Judul ',':',$ta_seminar->judul1));
                    }
                    elseif($ta_seminar->judul_approve == 2){
                        $pdf->RowNoBorder(array('Judul ',':',$ta_seminar->judul2));
                    }
                    $pdf->Ln(5);  
        
                    //tables
                    $pdf->Cell(80,10,'Aspek yang dinilai',1,0,'C',0);
                    $pdf->Cell(20,10,'Nilai',1,0,'C',0);
                    $pdf->Cell(30,10,'Persentase',1,0,'C',0);
                    $pdf->Cell(20,10,'NA',1,0,'C',0);
                   
    
                    //komponen ujian
                    $pdf->Ln();
                    $pdf->Cell(80,7,"1. ".strtoupper($unsur[0]->unsur),1,0,'L',0);
                    $pdf->Cell(20,7,'',1,0,'C',0);
                    $pdf->Cell(30,7,'',1,0,'C',0);
                    $pdf->Cell(20,7,'',1,0,'C',0);

                    $n = 1;
                    foreach($komponen_ujian as $kom_ujian){
                        $pdf->Ln();
                        $pdf->Cell(80,7,"       $n. ".$kom_ujian->attribut,1,0,'L',0);
                        $pdf->Cell(20,7,"",1,0,'C',0);
                        $n++;
                    }

                    //komponen skripsi
                    $pdf->Ln();
                    $pdf->Cell(130,7,"2. ".strtoupper($unsur[1]->unsur),1,0,'L',0);
                    $pdf->Cell(20,7,'',1,0,'C',0);
                    $n = 1;
                    foreach($komponen_skripsi as $kom_skripsi){
                      $pdf->Ln();
                      $pdf->Cell(130,7,"       $n. ".$kom_skripsi->attribut,1,0,'L',0);
                      $pdf->Cell(20,7,"",1,0,'C',0);
                      $n++;
                    }    

                //total
                $pdf->Ln();
                $pdf->Cell(130,10,'NILAI TOTAL',1,0,'C',0);
                $pdf->Cell(20,10,"",1,0,'C',0);
                $pdf->Ln();
    
                $pdf->Ln(5);
                $pdf->SetWidths(array(30,5,100));
                $pdf->SetAligns(array('L','C','J'));
                $pdf->RowNoBorder(array('Saran',':'));
                $pdf->Ln(20);
    
                $pdf->Cell(90, $spasi,"", 0, 0, 'L');
                $pdf->Cell(120, $spasi,'Bandar Lampung, ', 0, 0, 'L');
                $pdf->Ln(7);
    
                $pdf->Cell(90, $spasi,"", 0, 0, 'L');
                $pdf->Cell(120, $spasi,$this->komisi_kompre($jml_pbb,$kom->status), 0, 0, 'L');
                $pdf->Ln(5);
    
                 //ttd
                $pdf->Cell(90, $spasi,"", 0, 0, 'L');
                $pdf->Cell(30, $spasi,"", 0, 0, 'L');

                $gelar = $this->user_model->get_gelar_dosen_nip($kom->nip_nik);
                if(empty($gelar)){
                    $g_depan = "";
                    $g_belakang = "";
                }
                else{
                    $g_depan = $gelar->gelar_depan;
                    $g_belakang = $gelar->gelar_belakang;
                }

                $pdf->Ln(25);
                $pdf->Cell(90, $spasi,"", 0, 0, 'L');
                $pdf->Cell(30, $spasi,$g_depan." ".$kom->nama.", ".$g_belakang, 0, 0, 'L');
                $pdf->Ln(5);
                $pdf->Cell(90, $spasi,"", 0, 0, 'L');
                $pdf->Cell(30, $spasi,"NIP. ".$kom->nip_nik, 0, 0, 'L');
                $pdf->Ln(5);
            }
           
        }
        else{
            $komisi = $this->ta_model->get_komisi_seminar_check($seminar->id);

            foreach($komisi as $kom){
                $nilai1 = $this->ta_model->get_komponen_nilai_seminar_ujian($seminar->id,$kom->status);
                $nilai2 = $this->ta_model->get_komponen_nilai_seminar_ta($seminar->id,$kom->status);
                $unsur = $this->ta_model->get_unsur_distinct($nilai1[0]->id_komponen);
    
                $kom_data = $this->ta_model->get_komisi_seminar_data($seminar->id,$kom->status);
    
                $pdf->AddPage('P');
                $pdf->SetFont('Times','B',11);
                $pdf->MultiCell(150, $spasi, "FORMULIR PENILAIAN SEMINAR ".strtoupper($seminar->jenis)." PENELITIAN\nJURUSAN ".$jurusan_upper." FMIPA UNIVERSITAS LAMPUNG",1,'C',false); 
                $pdf->SetFont('Times','',11);
                $pdf->Ln(5);
    
                //nama judul
                $pdf->SetWidths(array(30,5, 100));
                $pdf->SetAligns(array('L','C','J'));
                $pdf->RowNoBorder(array('Nama / NPM',':',$mhs->name.' / '.$mhs->npm));
                $pdf->Ln(5);
                $pdf->SetWidths(array(30,5, 100));
                $pdf->SetAligns(array('L','C','J'));
                if($ta_seminar->judul_approve == 1){
                    $pdf->RowNoBorder(array('Judul ',':',$ta_seminar->judul1));
                }
                elseif($ta_seminar->judul_approve == 2){
                    $pdf->RowNoBorder(array('Judul ',':',$ta_seminar->judul2));
                }
                $pdf->Ln(5);  
    
                //tables
                $pdf->Cell(80,10,'Aspek yang dinilai',1,0,'C',0);
                $pdf->Cell(20,10,'Nilai',1,0,'C',0);
                $pdf->Cell(30,10,'Persentase',1,0,'C',0);
                $pdf->Cell(20,10,'NA',1,0,'C',0);
               
    
                //nilai 1
                $pdf->Ln();
                $pdf->Cell(80,7,"1. ".strtoupper($unsur[0]->unsur),1,0,'L',0);
                $pdf->Cell(20,7,'',1,0,'C',0);
                $pdf->Cell(30,7,'',1,0,'C',0);
                $pdf->Cell(20,7,'',1,0,'C',0);
                $x = 0;
                $i = 1;
                $nilai1_total = 0;
                foreach($nilai1 as $nilai_ujian){
                    $pdf->Ln();
                    $pdf->Cell(80,7,"       $i. ".$nilai_ujian->attribut,1,0,'L',0);
                    $pdf->Cell(20,7,$nilai_ujian->nilai,1,0,'C',0);
                    $pdf->Cell(30,7,$nilai_ujian->persentase." %",1,0,'C',0);
                    $n1 = $nilai_ujian->nilai * ($nilai_ujian->persentase / 100);
                    $pdf->Cell(20,7,$n1,1,0,'C',0);
                    $nilai1_total += $n1;
                    $i++;
                    if($nilai_ujian->persentase == 100){
                        $x += 0;
                    }
                    else{
                        $x += 1;
                    }
                }
    
                //nilai 2
                $pdf->Ln();
                $pdf->Cell(80,7,"2. ".strtoupper($unsur[1]->unsur),1,0,'L',0);
                $pdf->Cell(20,7,'',1,0,'C',0);
                $pdf->Cell(30,7,'',1,0,'C',0);
                $pdf->Cell(20,7,'',1,0,'C',0);
                $j = 1;
                $nilai2_total = 0;
                foreach($nilai2 as $nilai_ta){
                    $pdf->Ln();
                    $pdf->Cell(80,7,"       $j. ".$nilai_ta->attribut,1,0,'L',0);
                    $pdf->Cell(20,7,$nilai_ta->nilai,1,0,'C',0);
                    $pdf->Cell(30,7,$nilai_ta->persentase." %",1,0,'C',0);
                    $n2 = $nilai_ta->nilai * ($nilai_ta->persentase / 100);
                    $pdf->Cell(20,7,$n2,1,0,'C',0);
                    $nilai2_total += $n2;
                    $j++;
                    if($nilai_ujian->persentase == 100){
                        $x += 0;
                    }
                    else{
                        $x += 1;
                    }
                }

                $nilai_total = ($nilai1_total + $nilai2_total);
                if($x == 0){
                    $n_total = ($i-1)+($j-1);
                    $nilai_total = $nilai_total / $n_total;
                }
    
                //total
                $pdf->Ln();
                if($x == 0){
                    $pdf->Cell(130,10,'Rata - rata',1,0,'C',0);
                }
                else{
                    $pdf->Cell(130,10,'Nilai Total',1,0,'C',0);
                }
                $pdf->Cell(20,10,round($nilai_total,2),1,0,'C',0);
                $pdf->Ln();
    
                $pdf->Ln(5);
                $pdf->SetWidths(array(30,5,100));
                $pdf->SetAligns(array('L','C','J'));
                $pdf->RowNoBorder(array('Saran',':',$kom->saran));
                $pdf->Ln(20);
    
                $tgl = explode("-",substr("$kom->updated_at",0,10));
                $pdf->Cell(90, $spasi,"", 0, 0, 'L');
                $pdf->Cell(120, $spasi,'Bandar Lampung, '.$tgl[2].' '.$this->get_month($tgl[1]).' '.$tgl[0], 0, 0, 'L');
                $pdf->Ln(7);
    
                $pdf->Cell(90, $spasi,"", 0, 0, 'L');
                $pdf->Cell(120, $spasi,$this->komisi_kompre($jml_pbb,$kom->status), 0, 0, 'L');
                $pdf->Ln(5);
    
                 //ttd
                $pdf->Cell(90, $spasi,"", 0, 0, 'L');
                $pdf->Cell(30, $spasi,$pdf->Image("$kom->ttd",$pdf->GetX(), $pdf->GetY(),33,0,'PNG'), 0, 0, 'L');
    
                $gelar = $this->user_model->get_gelar_dosen_nip($kom_data->nip_nik);
                if(empty($gelar)){
                    $g_depan = "";
                    $g_belakang = "";
                }
                else{
                    $g_depan = $gelar->gelar_depan;
                    $g_belakang = $gelar->gelar_belakang;
                }

                $pdf->Ln(25);
                $pdf->Cell(90, $spasi,"", 0, 0, 'L');
                $pdf->Cell(30, $spasi,$g_depan." ".$kom_data->nama.", ".$g_belakang, 0, 0, 'L');
                $pdf->Ln(5);
                $pdf->Cell(90, $spasi,"", 0, 0, 'L');
                $pdf->Cell(30, $spasi,"NIP. ".$kom_data->nip_nik, 0, 0, 'L');
                $pdf->Ln(5);
    
            }
        }
      

            $pdf->Output('I','form_penilaian_kompre.pdf');
    }

    function berita_acara($seminar,$jurusan,$ta_seminar)
    {
        switch($jurusan)
        {
            case "Doktor MIPA":
            $numPage = '';
            break;
            case "Kimia":
            $numPage = '/SOP/MIPA/7.1/II/12';
            break;
            case "Biologi":
            $numPage = '/SOP/FMIPA/7.2/IV/01';
            break;
            case "Matematika":
            $numPage = '/PM/MIPA/3/08';
            break;
            case "Fisika":
            $numPage = '/SOP/MIPA/17.04/II/12/001';
            break;
            case "Ilmu Komputer":
            $numPage = '/SOP/MIPA/7.5/II/11/002';
            break;
        }

        $kode = 8;
        $type = '';
        $date = strtotime($seminar->tgl_pelaksanaan);
        $date = date('l', $date);
        $hari = $this->get_day($date);

        $dates = substr("$seminar->created_at",0,10);
        $dates = explode('-',$dates);
        $bulan = $this->get_month($dates[1]);

        $mhs = $this->ta_model->get_mahasiswa_detail($ta_seminar->npm);
        $komisi_seminar = $this->ta_model->get_komisi($ta_seminar->id_pengajuan);
        $jml_pbb = $this->ta_model->count_pembimbing_seminar($ta_seminar->id_pengajuan)->count;
        $id_komponen = $this->ta_model->get_id_komponen_seminar($seminar->id)->id_komponen;
        $komponen_nilai = $this->ta_model->select_komponen_seminar_id($id_komponen);

        if($ta_seminar->jenis == "Skripsi"){
            $bobot1 = explode("#",$komponen_nilai->bobot);
            if($jml_pbb == 2){
                $bobot = explode("-",$bobot1[0]);
            }
            else{   
                $bobot = explode("-",$bobot1[1]);
            } 
        }
        else{
            $bobot = explode("-",$komponen_nilai->bobot);
        }

        //nilai
        $komisi = $this->ta_model->get_komisi_seminar_check($seminar->id);
        $jml_kom = count($komisi);

        if($ta_seminar->jenis == "Skripsi"){
            $j = 0;
            $nilai_pbb = 0;
            foreach ($komisi_seminar as $kom){

                $nilai_angka = $this->ta_model->get_komponen_nilai_seminar_all($seminar->id,$kom->status);
                $nilai_angka2 = 0;
                foreach($nilai_angka as $nil_angka)
                {
                    $total_angka = $nil_angka->nilai * ($nil_angka->persentase / 100);
                    $nilai_angka2 += $total_angka;
                }
                $na_angka = $nilai_angka2 * ($bobot[$j] / 100);
                $nilai_pbb += $na_angka;
                $j++;
            }
        }
        elseif($ta_seminar->jenis == "Tugas Akhir"){
            $j = 0;
            $nilai_pbb = 0;
            foreach ($komisi_seminar as $kom){

                $nilai_angka = $this->ta_model->get_komponen_nilai_seminar_all($seminar->id,$kom->status);
                $nilai_angka2 = 0;
                foreach($nilai_angka as $nil_angka)
                {
                    $total_angka = $nil_angka->nilai * ($nil_angka->persentase / 100);
                    $nilai_angka2 += $total_angka;
                }
                $na_angka = $nilai_angka2 * ($bobot[$j] / 100);
                $nilai_pbb += $na_angka;
                $j++;
            }
        }
        else{
            $nilai_pbb = 0;
            foreach ($komisi_seminar as $kom){
            
                $nilai_angka = $this->ta_model->get_komponen_nilai_seminar_all($seminar->id,$kom->status);
                $nilai_angka2 = 0;
                foreach($nilai_angka as $nil_angka)
                {
                    $total_angka = $nil_angka->nilai * ($nil_angka->persentase / 100);
                    $nilai_angka2 += $total_angka;
                }
                $na_angka = $nilai_angka2 * ($bobot[$this->komisi_number($kom->status)] / 100);
                $nilai_pbb += $na_angka;
            }
        }
        
        $nilai_pbb = round($nilai_pbb,2);
        
        $pdf = new FPDF('P','mm','A4');
        $spasi = 6;

        $jurusan_upper = strtoupper($jurusan);
        $pdf->number_footer(0);
        $pdf->setting_page_footer($numPage, $kode,$type);
        $pdf->set_header_jur($jurusan_upper);
        $pdf->set_header($jurusan);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);

        $pdf->AddPage('P');
        $pdf->SetFont('Times','B',11);
        $pdf->MultiCell(150, $spasi, "FORMULIR BERITA ACARA ".strtoupper($seminar->jenis)."\nJURUSAN ".$jurusan_upper." FMIPA UNIVERSITAS LAMPUNG",1,'C',false); 
        $pdf->SetFont('Times','',11);
        $pdf->Ln(5);
        $pdf->Cell(150, $spasi,"NO: ".$seminar->no_form, 0, 0, 'C');
        $pdf->Ln(9);

        $pdf->MultiCell(150, $spasi, "Pada hari ".$hari." tanggal ".$dates[2]." ".$bulan." ".$dates[0]." pukul ".$seminar->waktu_pelaksanaan." WIB, telah dilaksanakan ".$seminar->jenis." penelitian oleh mahasiswa:",0,'J',false);
        $pdf->Ln(5);

        $pdf->SetWidths(array(30,5, 100));
        $pdf->SetAligns(array('L','C','J'));
        $pdf->RowNoBorder(array('Nama / NPM',':',$mhs->name.' / '.$mhs->npm));
        $pdf->Ln(5);
        $pdf->SetWidths(array(30,5, 100));
        $pdf->SetAligns(array('L','C','J'));
        if($ta_seminar->judul_approve == 1){
            $pdf->RowNoBorder(array('Judul ',':',$ta_seminar->judul1));
        }
        elseif($ta_seminar->judul_approve == 2){
            $pdf->RowNoBorder(array('Judul ',':',$ta_seminar->judul2));
        }
        $pdf->Ln(5);  

        $pdf->Cell(80, $spasi,"Nilai Angka      :  ".$nilai_pbb, 0, 0, 'L');
        $pdf->Cell(80, $spasi,"Huruf Mutu       :  ".$this->huruf_mutu($nilai_pbb), 0, 0, 'L');
        
        $pdf->Ln(8);  

        $pdf->Cell(80, $spasi,"Dengan rekapitulasi sebagai berikut : ", 0, 0, 'L');
        $pdf->Ln(8);  

        $pdf->Cell(50,5,'Nama',1,0,'C',0);
        $pdf->Cell(40,5,'Status',1,0,'C',0);
        $pdf->Cell(15,5,'Nilai',1,0,'C',0);
        $pdf->Cell(10,5,'%',1,0,'C',0);
        $pdf->Cell(13,5,'NA',1,0,'C',0);
        $pdf->Cell(22,5,'Paraf',1,0,'C',0);
        $pdf->Ln();

        if($ta_seminar->jenis == "Skripsi")
        {
            $i = 0;
            // $nilai_rekap = 0;
            foreach ($komisi_seminar as $kom){

                $nilai = $this->ta_model->get_komponen_nilai_seminar_all($seminar->id,$kom->status);
                $ttd = $this->ta_model->get_seminar_nilai_check_by_status($seminar->id,$kom->status);
                $nilai2 = 0;
                foreach($nilai as $nil)
                {
                    $total = $nil->nilai * ($nil->persentase / 100);
                    $nilai2 += $total;
                }

                $pdf->SetWidths(array(50,40, 15,10,13,22));
                $pdf->SetAligns(array('C','C','C'));
                $na = $nilai2 * ($bobot[$i] / 100);
                $na = round($na,2);

                $gelar = $this->user_model->get_gelar_dosen_nip($kom->nip_nik);
                if(empty($gelar)){
                    $g_depan = "";
                    $g_belakang = "";
                }
                else{
                    $g_depan = $gelar->gelar_depan;
                    $g_belakang = $gelar->gelar_belakang;
                }

                $pdf->Row(array(" \n".$g_depan." ".$kom->nama.", ".$g_belakang."\n  "," \n$kom->status","\n ".$nilai2,"\n ".$bobot[$i],"\n ".$na,$pdf->Image("$ttd->ttd",$pdf->GetX()+126, $pdf->GetY(),25,0,'PNG'))); 
                // $nilai_rekap += $na;
                $i++;
            }
        }
        elseif($ta_seminar->jenis == "Tugas Akhir")
        {
            $i = 0;
            // $nilai_rekap = 0;
            foreach ($komisi_seminar as $kom){

                $nilai = $this->ta_model->get_komponen_nilai_seminar_all($seminar->id,$kom->status);
                $ttd = $this->ta_model->get_seminar_nilai_check_by_status($seminar->id,$kom->status);
                $nilai2 = 0;
                foreach($nilai as $nil)
                {
                    $total = $nil->nilai * ($nil->persentase / 100);
                    $nilai2 += $total;
                }

                $pdf->SetWidths(array(50,40, 15,10,13,22));
                $pdf->SetAligns(array('C','C','C','C','C','C'));
                $na = $nilai2 * ($bobot[$i] / 100);
                $na = round($na,2);

                $gelar = $this->user_model->get_gelar_dosen_nip($kom->nip_nik);
                if(empty($gelar)){
                    $g_depan = "";
                    $g_belakang = "";
                }
                else{
                    $g_depan = $gelar->gelar_depan;
                    $g_belakang = $gelar->gelar_belakang;
                }

                $pdf->Row(array(" \n".$g_depan." ".$kom->nama.", ".$g_belakang."\n  "," \n$kom->status","\n ".$nilai2,"\n ".$bobot[$i],"\n ".$na,$pdf->Image("$ttd->ttd",$pdf->GetX()+126, $pdf->GetY(),25,0,'PNG'))); 
                // $nilai_rekap += $na;
                $i++;
            }
        }
        else{
            // $nilai_rekap = 0;
            foreach ($komisi_seminar as $kom){

                $nilai = $this->ta_model->get_komponen_nilai_seminar_all($seminar->id,$kom->status);
                $ttd = $this->ta_model->get_seminar_nilai_check_by_status($seminar->id,$kom->status);
                $nilai2 = 0;
                foreach($nilai as $nil)
                {
                    $total = $nil->nilai * ($nil->persentase / 100);
                    $nilai2 += $total;
                }

                $pdf->SetWidths(array(50,40, 15,10,13,22));
                $pdf->SetAligns(array('C','C','C'));
                $na = $nilai2 * ($bobot[$this->komisi_number($kom->status)] / 100);
                $na = round($na,2);

                $gelar = $this->user_model->get_gelar_dosen_nip($kom->nip_nik);
                if(empty($gelar)){
                    $g_depan = "";
                    $g_belakang = "";
                }
                else{
                    $g_depan = $gelar->gelar_depan;
                    $g_belakang = $gelar->gelar_belakang;
                }

                $pdf->Row(array(" \n".$g_depan." ".$kom->nama.", ".$g_belakang."\n  "," \n$kom->status","\n ".$nilai2,"\n ".$bobot[$this->komisi_number($kom->status)],"\n ".$na,$pdf->Image("$ttd->ttd",$pdf->GetX()+126, $pdf->GetY(),25,0,'PNG'))); 
                // $nilai_rekap += $na;
            }
        }
        $pdf->Cell(115,9,'Total',1,0,'C',0);
        $pdf->Cell(13,9,$nilai_pbb,1,0,'C',0);
        $pdf->Cell(22,9,'',1,0,'C',0);
        $pdf->Ln();

        if($seminar->status == 8){
            $pdf->Ln(5);
            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(120, $spasi,'Bandar Lampung, ', 0, 0, 'L');
            $pdf->Ln(7);

            $pdf->Cell(90, $spasi,"Mengetahui,", 0, 0, 'L');
            $pdf->Cell(45, $spasi,"Menyetujui,", 0, 0, 'L');
            $pdf->Ln(5);
            $pdf->Cell(90, $spasi,"Ketua Jurusan", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"Koordinator Seminar", 0, 0, 'L');
            $pdf->Ln(20);

            $pdf->Cell(90, $spasi,'', 0, 0, 'L');
            $pdf->Cell(30, $spasi,'', 0, 0, 'L');
            $pdf->Ln(5);

            $pdf->Cell(90, $spasi,"NIP. ", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"NIP. ", 0, 0, 'L');
            $pdf->Ln();
        }

        elseif($seminar->status == 9){
            $koor = $this->ta_model->ttd_nilai_seminar_koor($seminar->id);
            $koor_data = $this->user_model->get_dosen_data($koor->ket);

            $pdf->Ln(5);
            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(120, $spasi,'Bandar Lampung, ', 0, 0, 'L');
            $pdf->Ln(7);

            $pdf->Cell(90, $spasi,"Mengetahui,", 0, 0, 'L');
            $pdf->Cell(45, $spasi,"Menyetujui,", 0, 0, 'L');
            $pdf->Ln(5);
            $pdf->Cell(90, $spasi,"Ketua Jurusan", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"Koordinator Seminar", 0, 0, 'L');
            $pdf->Ln(5);

            //ttd
            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(30, $spasi,$pdf->Image("$koor->ttd",$pdf->GetX(), $pdf->GetY(),33,0,'PNG'), 0, 0, 'L');

            $pdf->Ln(20);
            $pdf->Cell(90, $spasi,'', 0, 0, 'L');
            $pdf->Cell(30, $spasi,$koor_data->gelar_depan." ".$koor_data->name.", ".$koor_data->gelar_belakang, 0, 0, 'L');
            $pdf->Ln(5);

            $pdf->Cell(90, $spasi,"NIP. ", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"NIP. ".$koor_data->nip_nik, 0, 0, 'L');
            $pdf->Ln();
        }

        elseif($seminar->status == 10){
            $koor = $this->ta_model->ttd_nilai_seminar_koor($seminar->id);
            $koor_data = $this->user_model->get_dosen_data($koor->ket);

            $kajur = $this->ta_model->ttd_nilai_seminar_kajur($seminar->id);
            $kajur_data = $this->user_model->get_dosen_data($kajur->ket);

            $ba_date = explode("-",substr($kajur->created_at,0,10));
            $ba_bulan = $this->get_month($ba_date[1]);

            $pdf->Ln(5);
            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(120, $spasi,'Bandar Lampung, '.$ba_date[2]." ".$ba_bulan." ".$ba_date[0], 0, 0, 'L');
            $pdf->Ln(7);

            $pdf->Cell(90, $spasi,"Mengetahui,", 0, 0, 'L');
            $pdf->Cell(45, $spasi,"Menyetujui,", 0, 0, 'L');
            $pdf->Ln(5);
            $pdf->Cell(90, $spasi,"Ketua Jurusan", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"Koordinator Seminar", 0, 0, 'L');
            $pdf->Ln(5);

            //ttd
            $pdf->Cell(90, $spasi,$pdf->Image("$kajur->ttd",$pdf->GetX(), $pdf->GetY(),33,0,'PNG'), 0, 0, 'L');
            $pdf->Cell(30, $spasi,$pdf->Image("$koor->ttd",$pdf->GetX(), $pdf->GetY(),33,0,'PNG'), 0, 0, 'L');

            $pdf->Ln(20);
            $pdf->Cell(90, $spasi,$kajur_data->gelar_depan." ".$kajur_data->name.", ".$kajur_data->gelar_belakang, 0, 0, 'L');
            $pdf->Cell(30, $spasi,$koor_data->gelar_depan." ".$koor_data->name.", ".$koor_data->gelar_belakang, 0, 0, 'L');
            $pdf->Ln(5);

            $pdf->Cell(90, $spasi,"NIP. ".$kajur_data->nip_nik, 0, 0, 'L');
            $pdf->Cell(30, $spasi,"NIP. ".$koor_data->nip_nik, 0, 0, 'L');
            $pdf->Ln();
        } 

        if($ta_seminar->jenis == "Tugas Akhir"){
            $pdf->AddPage('P');
            $pdf->SetFont('Times','B',11);
            $pdf->MultiCell(150, $spasi, "FORMULIR REKAPITULASI PENILAIAN TUGAS AKHIR\nJURUSAN ".$jurusan_upper." FMIPA UNIVERSITAS LAMPUNG",1,'C',false); 
            $pdf->SetFont('Times','',11);
            $pdf->Ln(9);

            $pdf->SetWidths(array(30,5, 100));
            $pdf->SetAligns(array('L','C','J'));
            $pdf->RowNoBorder(array('Nama / NPM',':',$mhs->name.' / '.$mhs->npm));
            $pdf->Ln(5);
            $pdf->SetWidths(array(30,5, 100));
            $pdf->SetAligns(array('L','C','J'));
            if($ta_seminar->judul_approve == 1){
                $pdf->RowNoBorder(array('Judul ',':',$ta_seminar->judul1));
            }
            elseif($ta_seminar->judul_approve == 2){
                $pdf->RowNoBorder(array('Judul ',':',$ta_seminar->judul2));
            }
            $pdf->Ln(5);  
    
            $pdf->Cell(50,5,'Nama Penguji',1,0,'C',0);
            $pdf->Cell(40,5,'Status',1,0,'C',0);
            $pdf->Cell(15,5,'Nilai',1,0,'C',0);
            $pdf->Cell(10,5,'%',1,0,'C',0);
            $pdf->Cell(13,5,'NA',1,0,'C',0);
            $pdf->Cell(22,5,'Paraf',1,0,'C',0);
            $pdf->Ln();
           
            //table nilai
                //pb 1
                $nilai = $this->ta_model->get_komponen_nilai_seminar_all($seminar->id,"Pembimbing Utama");
                $ttd = $this->ta_model->get_seminar_nilai_check_by_status($seminar->id,"Pembimbing Utama");
                $pb_ta = $this->ta_model->get_komisi_ta_by_status($seminar->id_tugas_akhir,"Pembimbing Utama");

                $pdf->SetWidths(array(50,40, 15,10,13,22));
                $pdf->SetAligns(array('C','C','C'));
                $na_pb = $nilai_pbb * (60 / 100); // persentase 60%
                $na_pb = round($na_pb,2);
                $pdf->Row(array(" \n"."$pb_ta->nama"."\n  "," \nPembimbing Utama","\n ".$nilai_pbb,"\n 60","\n ".$na_pb,$pdf->Image("$ttd->ttd",$pdf->GetX()+126, $pdf->GetY(),25,0,'PNG'))); 

                // dosen verifikasi
                $verifikator = $this->ta_model->get_dosen_verifikator($ta_seminar->id_pengajuan);

                $pdf->SetWidths(array(50,40, 15,10,13,22));
                $pdf->SetAligns(array('C','C','C'));
                $na_dv = $verifikator->nilai * (40 / 100); // persentase 40%
                $na_dv = round($na_dv,2);
                $pdf->Row(array(" \n"."$verifikator->nama"."\n  "," \n Dosen Verifikasi","\n ".$verifikator->nilai,"\n 40","\n ".$na_dv,$pdf->Image("$verifikator->ttd",$pdf->GetX()+126, $pdf->GetY(),25,0,'PNG'))); 

                $nilai_ta_total = $na_pb + $na_dv;

                //nilai
                $pdf->Ln(5);
                $pdf->SetWidths(array(45,5,45, 30, 5, 50));
                $pdf->SetAligns(array('L','C','L','L','C','L'));
                $pdf->RowNoBorder(array('Total Nilai',':',$nilai_ta_total,'Huruf Mutu',':',$this->huruf_mutu($nilai_ta_total)));
                $pdf->Ln(5);

                if($seminar->status == 8){
                    $pdf->Ln(5);
                    $pdf->Cell(90, $spasi,"", 0, 0, 'L');
                    $pdf->Cell(120, $spasi,'Bandar Lampung, ', 0, 0, 'L');
                    $pdf->Ln(7);
        
                    $pdf->Cell(90, $spasi,"Mengetahui,", 0, 0, 'L');
                    $pdf->Cell(45, $spasi,"Menyetujui,", 0, 0, 'L');
                    $pdf->Ln(5);
                    $pdf->Cell(90, $spasi,"Ketua Jurusan", 0, 0, 'L');
                    $pdf->Cell(30, $spasi,"Ketua Program Studi", 0, 0, 'L');
                    $pdf->Ln(20);
        
                    $pdf->Cell(90, $spasi,'', 0, 0, 'L');
                    $pdf->Cell(30, $spasi,'', 0, 0, 'L');
                    $pdf->Ln(5);
        
                    $pdf->Cell(90, $spasi,"NIP. ", 0, 0, 'L');
                    $pdf->Cell(30, $spasi,"NIP. ", 0, 0, 'L');
                    $pdf->Ln();
                }
        
                elseif($seminar->status == 9){
                    $koor = $this->ta_model->ttd_nilai_seminar_koor($seminar->id);
                    $koor_data = $this->user_model->get_dosen_data($koor->ket);
        
                    $pdf->Ln(5);
                    $pdf->Cell(90, $spasi,"", 0, 0, 'L');
                    $pdf->Cell(120, $spasi,'Bandar Lampung, ', 0, 0, 'L');
                    $pdf->Ln(7);
        
                    $pdf->Cell(90, $spasi,"Mengetahui,", 0, 0, 'L');
                    $pdf->Cell(45, $spasi,"Menyetujui,", 0, 0, 'L');
                    $pdf->Ln(5);
                    $pdf->Cell(90, $spasi,"Ketua Jurusan", 0, 0, 'L');
                    $pdf->Cell(30, $spasi,"Ketua Program Studi", 0, 0, 'L');
                    $pdf->Ln(5);
        
                    //ttd
                    $pdf->Cell(90, $spasi,"", 0, 0, 'L');
                    $pdf->Cell(30, $spasi,$pdf->Image("$koor->ttd",$pdf->GetX(), $pdf->GetY(),33,0,'PNG'), 0, 0, 'L');
        
                    $pdf->Ln(20);
                    $pdf->Cell(90, $spasi,'', 0, 0, 'L');
                    $pdf->Cell(30, $spasi,$koor_data->gelar_depan." ".$koor_data->name.", ".$koor_data->gelar_belakang, 0, 0, 'L');
                    $pdf->Ln(5);
        
                    $pdf->Cell(90, $spasi,"NIP. ", 0, 0, 'L');
                    $pdf->Cell(30, $spasi,"NIP. ".$koor_data->nip_nik, 0, 0, 'L');
                    $pdf->Ln();
                }
        
                elseif($seminar->status == 10){
                    $koor = $this->ta_model->ttd_nilai_seminar_koor($seminar->id);
                    $koor_data = $this->user_model->get_dosen_data($koor->ket);
        
                    $kajur = $this->ta_model->ttd_nilai_seminar_kajur($seminar->id);
                    $kajur_data = $this->user_model->get_dosen_data($kajur->ket);
        
                    $pdf->Ln(5);
                    $pdf->Cell(90, $spasi,"", 0, 0, 'L');
                    $pdf->Cell(120, $spasi,'Bandar Lampung, '.$ba_date[2]." ".$ba_bulan." ".$ba_date[0], 0, 0, 'L');
                    $pdf->Ln(7);
        
                    $pdf->Cell(90, $spasi,"Mengetahui,", 0, 0, 'L');
                    $pdf->Cell(45, $spasi,"Menyetujui,", 0, 0, 'L');
                    $pdf->Ln(5);
                    $pdf->Cell(90, $spasi,"Ketua Jurusan", 0, 0, 'L');
                    $pdf->Cell(30, $spasi,"Ketua Program Studi", 0, 0, 'L');
                    $pdf->Ln(5);
        
                    //ttd
                    $pdf->Cell(90, $spasi,$pdf->Image("$kajur->ttd",$pdf->GetX(), $pdf->GetY(),33,0,'PNG'), 0, 0, 'L');
                    $pdf->Cell(30, $spasi,$pdf->Image("$koor->ttd",$pdf->GetX(), $pdf->GetY(),33,0,'PNG'), 0, 0, 'L');
        
                    $pdf->Ln(20);
                    $pdf->Cell(90, $spasi,$kajur_data->gelar_depan." ".$kajur_data->name.", ".$kajur_data->gelar_belakang, 0, 0, 'L');
                    $pdf->Cell(30, $spasi,$koor_data->gelar_depan." ".$koor_data->name.", ".$koor_data->gelar_belakang, 0, 0, 'L');
                    $pdf->Ln(5);
        
                    $pdf->Cell(90, $spasi,"NIP. ".$kajur_data->nip_nik, 0, 0, 'L');
                    $pdf->Cell(30, $spasi,"NIP. ".$koor_data->nip_nik, 0, 0, 'L');
                    $pdf->Ln();
                } 
        }

        $pdf->Output('I','berita_acara.pdf');
    }

    function berita_acara_kompre($seminar,$jurusan,$ta_seminar)
    {
        switch($jurusan)
        {
            case "Doktor MIPA":
            $numPage = '';
            break;
            case "Kimia":
            $numPage = '/SOP/MIPA/7.1/II/12';
            break;
            case "Biologi":
            $numPage = '/SOP/FMIPA/7.2/IV/01';
            break;
            case "Matematika":
            $numPage = '/PM/MIPA/3/08';
            break;
            case "Fisika":
            $numPage = '/SOP/MIPA/17.04/II/12/001';
            break;
            case "Ilmu Komputer":
            $numPage = '/SOP/MIPA/7.5/II/11/002';
            break;
        }

        $kode = 4;
        $type = '';
        $date = strtotime($seminar->tgl_pelaksanaan);
        $date = date('l', $date);
        $hari = $this->get_day($date);

        $dates = substr("$seminar->created_at",0,10);
        $dates = explode('-',$dates);
        $bulan = $this->get_month($dates[1]);

        $mhs = $this->ta_model->get_mahasiswa_detail($ta_seminar->npm);
        $komisi_seminar = $this->ta_model->get_komisi($ta_seminar->id_pengajuan);
        $jml_pbb = $this->ta_model->count_pembimbing_seminar($ta_seminar->id_pengajuan)->count;
        $id_komponen = $this->ta_model->get_id_komponen_seminar($seminar->id)->id_komponen;
        $komponen_nilai = $this->ta_model->select_komponen_seminar_id($id_komponen);
        
        if($ta_seminar->jenis == "Skripsi"){
            $bobot1 = explode("#",$komponen_nilai->bobot);
            if($jml_pbb == 2){
                $bobot = explode("-",$bobot1[0]);
            }
            else{   
                $bobot = explode("-",$bobot1[1]);
            } 
        }
        else{
            $bobot = explode("-",$komponen_nilai->bobot);
        }

        //nilai
        $komisi = $this->ta_model->get_komisi_seminar_check($seminar->id);
        $jml_kom = count($komisi);

        if($ta_seminar->jenis == "Skripsi"){
            $j = 0;
            $nilai_pbb = 0;
            foreach ($komisi_seminar as $kom){

                $nilai_angka = $this->ta_model->get_komponen_nilai_seminar_all($seminar->id,$kom->status);
                $nilai_angka2 = 0;
                foreach($nilai_angka as $nil_angka)
                {
                    $total_angka = $nil_angka->nilai * ($nil_angka->persentase / 100);
                    $nilai_angka2 += $total_angka;
                }
                if($nil_angka->persentase == 100 ){
                    $jml_nilai = count($nilai_angka);
                    $nilai_angka2 = $nilai_angka2 / $jml_nilai; 
                }
                $na_angka = $nilai_angka2 * ($bobot[$j] / 100);
                $nilai_pbb += $na_angka;
                $j++;
            }
        }
        else{
            $nilai_pbb = 0;
            foreach ($komisi_seminar as $kom){
            
                $nilai_angka = $this->ta_model->get_komponen_nilai_seminar_all($seminar->id,$kom->status);
                $nilai_angka2 = 0;
                foreach($nilai_angka as $nil_angka)
                {
                    $total_angka = $nil_angka->nilai * ($nil_angka->persentase / 100);
                    $nilai_angka2 += $total_angka;
                }
                if($nil_angka->persentase == 100 ){
                    $jml_nilai = count($nilai_angka);
                    $nilai_angka2 = $nilai_angka2 / $jml_nilai; 
                }
                $na_angka = $nilai_angka2 * ($bobot[$this->komisi_number($kom->status)] / 100);
                $nilai_pbb += $na_angka;
            }
        }
        
        $nilai_pbb = round($nilai_pbb,2);

        $pdf = new FPDF('P','mm','A4');
        $spasi = 6;

        $jurusan_upper = strtoupper($jurusan);
        $pdf->number_footer(0);
        $pdf->setting_page_footer($numPage, $kode,$type);
        $pdf->set_header_jur($jurusan_upper);
        $pdf->set_header($jurusan);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);

        $pdf->AddPage('P');
        $pdf->SetFont('Times','B',11);
        $pdf->MultiCell(150, $spasi, "FORMULIR BERITA ACARA UJIAN ".strtoupper($ta_seminar->jenis)."\nJURUSAN ".$jurusan_upper." FMIPA UNIVERSITAS LAMPUNG",1,'C',false); 
        $pdf->SetFont('Times','',11);
        $pdf->Ln(5);
        $pdf->Cell(150, $spasi,"NOMOR : ".$seminar->no_form, 0, 0, 'C');
        $pdf->Ln(9);

        $pdf->MultiCell(150, $spasi, "Pada hari ".$hari." tanggal ".$dates[2]." ".$bulan." ".$dates[0]." pukul ".$seminar->waktu_pelaksanaan." WIB, telah dilaksanakan Ujian ".$ta_seminar->jenis." mahasiswa:",0,'J',false);
        $pdf->Ln(5);
        $pdf->SetWidths(array(30,5, 50, 12, 5, 50));
        $pdf->SetAligns(array('L','C','L','L','C','L'));
        $pdf->RowNoBorder(array('NAMA',':',$mhs->name,'NPM',':',$mhs->npm));
        $pdf->Ln(5);
        $pdf->SetWidths(array(30,5, 100));
        $pdf->SetAligns(array('L','C','J'));
        if($ta_seminar->judul_approve == 1){
            $pdf->RowNoBorder(array('Judul ',':',$ta_seminar->judul1));
        }
        elseif($ta_seminar->judul_approve == 2){
            $pdf->RowNoBorder(array('Judul ',':',$ta_seminar->judul2));
        }
        $pdf->Ln(5);  

        if($seminar->status == 8){
            $pdf->MultiCell(150, $spasi, "Dinyatakan LULUS/LULUS BERSYARAT/TIDAK LULUS dengan nilai sebagai berikut:",0,'J',false);
        }
        else{
            $ket = $this->ta_model->get_seminar_sidang_kompre_id_seminar($seminar->id)->ket;
            switch($ket)
            {
                case "0":
                    $lulus = "TIDAK LULUS";
                    break;
                case "1":
                    $lulus = "LULUS";
                    break;
                case "2":
                    $lulus = "LULUS BERSYARAT";
                    break;    
            }
            $pdf->MultiCell(150, $spasi, "Dinyatakan $lulus dengan nilai sebagai berikut:",0,'J',false);

        }
        $pdf->Cell(80, $spasi,"Nilai Angka      :  ".$nilai_pbb, 0, 0, 'L');
        $pdf->Cell(80, $spasi,"Huruf Mutu       :  ".$this->huruf_mutu($nilai_pbb), 0, 0, 'L');

        $pdf->Ln(8); 
        $pdf->Cell(80, $spasi,"Rekapitulasi Nilai : ", 0, 0, 'L');
        $pdf->Ln(8);  

        $pdf->Cell(50,5,'Nama',1,0,'C',0);
        $pdf->Cell(40,5,'Status',1,0,'C',0);
        $pdf->Cell(15,5,'Skor',1,0,'C',0);
        $pdf->Cell(10,5,'%',1,0,'C',0);
        $pdf->Cell(13,5,'Nilai',1,0,'C',0);
        $pdf->Cell(22,5,'Paraf',1,0,'C',0);
        $pdf->Ln();

        
        if($ta_seminar->jenis == "Skripsi")
        {
            $i = 0;
            // $nilai_rekap = 0;
            foreach ($komisi_seminar as $kom){

                $nilai = $this->ta_model->get_komponen_nilai_seminar_all($seminar->id,$kom->status);
                $ttd = $this->ta_model->get_seminar_nilai_check_by_status($seminar->id,$kom->status);
                $nilai2 = 0;
                foreach($nilai as $nil)
                {
                    $total = $nil->nilai * ($nil->persentase / 100);
                    $nilai2 += $total;
                }
                if($nil_angka->persentase == 100 ){
                    $nilai2 = $nilai2 / count($nilai);
                    $nilai2 = round($nilai2,2);
                }
                $pdf->SetWidths(array(50,40, 15,10,13,22));
                $pdf->SetAligns(array('C','C','C'));
                $na = $nilai2 * ($bobot[$i] / 100);
                $na = round($na,2);

                $gelar = $this->user_model->get_gelar_dosen_nip($kom->nip_nik);
                if(empty($gelar)){
                    $g_depan = "";
                    $g_belakang = "";
                }
                else{
                    $g_depan = $gelar->gelar_depan;
                    $g_belakang = $gelar->gelar_belakang;
                }

                $pdf->Row(array(" \n".$g_depan." ".$kom->nama.", ".$g_belakang."\n  "," \n".$this->komisi_kompre($jml_pbb,$kom->status),"\n ".$nilai2,"\n ".$bobot[$i],"\n ".$na,$pdf->Image("$ttd->ttd",$pdf->GetX()+126, $pdf->GetY(),25,0,'PNG'))); 
                // $nilai_rekap += $na;
                $i++;
            }
        }
        else{
            // $nilai_rekap = 0;
            foreach ($komisi_seminar as $kom){

                $nilai = $this->ta_model->get_komponen_nilai_seminar_all($seminar->id,$kom->status);
                $ttd = $this->ta_model->get_seminar_nilai_check_by_status($seminar->id,$kom->status);
                $nilai2 = 0;
                foreach($nilai as $nil)
                {
                    $total = $nil->nilai * ($nil->persentase / 100);
                    $nilai2 += $total;
                }
                if($nil_angka->persentase == 100 ){
                    $nilai2 = $nilai2 / count($nilai);
                    $nilai2 = round($nilai2,2);
                }
                $pdf->SetWidths(array(50,40, 15,10,13,22));
                $pdf->SetAligns(array('C','C','C'));
                $na = $nilai2 * ($bobot[$this->komisi_number($kom->status)] / 100);
                $na = round($na,2);

                $gelar = $this->user_model->get_gelar_dosen_nip($kom->nip_nik);
                if(empty($gelar)){
                    $g_depan = "";
                    $g_belakang = "";
                }
                else{
                    $g_depan = $gelar->gelar_depan;
                    $g_belakang = $gelar->gelar_belakang;
                }

                $pdf->Row(array(" \n".$g_depan." ".$kom->nama.", ".$g_belakang."\n  "," \n".$this->komisi_kompre($jml_pbb,$kom->status),"\n ".$nilai2,"\n ".$bobot[$this->komisi_number($kom->status)],"\n ".$na,$pdf->Image("$ttd->ttd",$pdf->GetX()+126, $pdf->GetY(),25,0,'PNG'))); 
                // $nilai_rekap += $na;
            }
        }

        $pdf->Cell(115,9,'Total',1,0,'C',0);
        $pdf->Cell(13,9,$nilai_pbb,1,0,'C',0);
        $pdf->Cell(22,9,'',1,0,'C',0);
        $pdf->Ln();

        $pbb_utama = $this->ta_model->get_komisi_by_status_slug($ta_seminar->id_pengajuan,"Pembimbing Utama");
        $pbb_utama_ttd = $this->ta_model->get_seminar_nilai_check_by_status($seminar->id,"Pembimbing Utama");

        if($seminar->status == 8 || $seminar->status == 9){
            $pdf->Ln(5);
            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(120, $spasi,'Bandar Lampung, ', 0, 0, 'L');
            $pdf->Ln(7);

            $pdf->Cell(90, $spasi,"Mengetahui,", 0, 0, 'L');
            $pdf->Cell(45, $spasi,"Menyetujui,", 0, 0, 'L');
            $pdf->Ln(5);
            $pdf->Cell(90, $spasi,"Ketua Jurusan", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"Ketua Tim Penguji", 0, 0, 'L');
            $pdf->Ln(5);

              //ttd
              $pdf->Cell(90, $spasi,"", 0, 0, 'L');
              $pdf->Cell(30, $spasi,$pdf->Image("$pbb_utama_ttd->ttd",$pdf->GetX()+5, $pdf->GetY(),25,0,'PNG'), 0, 0, 'L');
              $pdf->Ln(15);

              $gelar = $this->user_model->get_gelar_dosen_nip($pbb_utama->nip_nik);
              if(empty($gelar)){
                  $g_depan = "";
                  $g_belakang = "";
              }
              else{
                  $g_depan = $gelar->gelar_depan;
                  $g_belakang = $gelar->gelar_belakang;
              }

            $pdf->Cell(90, $spasi,'', 0, 0, 'L');
            $pdf->Cell(30, $spasi,$g_depan." ".$pbb_utama->nama.", ".$g_belakang, 0, 0, 'L');
            $pdf->Ln(5);

            $pdf->Cell(90, $spasi,"NIP. ", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"NIP. ".$pbb_utama->nip_nik, 0, 0, 'L');
            $pdf->Ln();
        }
        if($seminar->status == 10){
            $kajur = $this->ta_model->ttd_nilai_seminar_kajur($seminar->id);
            $kajur_data = $this->user_model->get_dosen_data($kajur->ket);

            $tgl = $this->ta_model->get_seminar_sidang_kompre_id_seminar($seminar->id)->created_at;
            $tgl = explode("-",substr($tgl,0,10));
            $bulan = $this->get_month($tgl[1]);
            $pdf->Ln(5);
            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(120, $spasi,'Bandar Lampung, '.$tgl[2].' '.$bulan.' '.$tgl[0], 0, 0, 'L');
            $pdf->Ln(7);

            $pdf->Cell(90, $spasi,"Mengetahui,", 0, 0, 'L');
            $pdf->Cell(45, $spasi,"Menyetujui,", 0, 0, 'L');
            $pdf->Ln(5);
            $pdf->Cell(90, $spasi,"Ketua Jurusan", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"Ketua Tim Penguji", 0, 0, 'L');
            $pdf->Ln(5);

              //ttd
              $pdf->Cell(90, $spasi,$pdf->Image("$kajur->ttd",$pdf->GetX()+5, $pdf->GetY(),25,0,'PNG'), 0, 0, 'L');
              $pdf->Cell(30, $spasi,$pdf->Image("$pbb_utama_ttd->ttd",$pdf->GetX()+5, $pdf->GetY(),25,0,'PNG'), 0, 0, 'L');
              $pdf->Ln(15);

            $gelar = $this->user_model->get_gelar_dosen_nip($pbb_utama->nip_nik);
              if(empty($gelar)){
                  $g_depan = "";
                  $g_belakang = "";
              }
              else{
                  $g_depan = $gelar->gelar_depan;
                  $g_belakang = $gelar->gelar_belakang;
              }

            $pdf->Cell(90, $spasi,$kajur_data->gelar_depan." ".$kajur_data->name.", ".$kajur_data->gelar_belakang, 0, 0, 'L');
            $pdf->Cell(30, $spasi,$g_depan.$pbb_utama->nama.$g_belakang, 0, 0, 'L');
            $pdf->Ln(5);

            $pdf->Cell(90, $spasi,"NIP. ".$kajur_data->nip_nik, 0, 0, 'L');
            $pdf->Cell(30, $spasi,"NIP. ".$pbb_utama->nip_nik, 0, 0, 'L');
            $pdf->Ln();
        }


        $pdf->Output('I','berita_acara_kompre.pdf');


    }

    function verifikasi_ta($ta,$jurusan)
    {
        switch($jurusan)
        {
            case "Doktor MIPA":
            $numPage = '';
            break;
            case "Kimia":
            $numPage = '/SOP/MIPA/7.1/II/12';
            break;
            case "Biologi":
            $numPage = '/SOP/FMIPA/7.2/IV/01';
            break;
            case "Matematika":
            $numPage = '/PM/MIPA/3/08';
            break;
            case "Fisika":
            $numPage = '/SOP/MIPA/17.04/II/12/001';
            break;
            case "Ilmu Komputer":
            $numPage = '/SOP/MIPA/7.5/II/12/004';
            break;
        }

        $kode = 1;
        $type = 'Fixed';
        $mhs = $this->ta_model->get_mahasiswa_detail($ta->npm);

        $pdf = new FPDF('P','mm','A4');
        $spasi = 6;

        $jurusan_upper = strtoupper($jurusan);
        $pdf->number_footer(0);
        $pdf->setting_page_footer($numPage, $kode,$type);
        $pdf->set_header_jur($jurusan_upper);
        $pdf->set_header($jurusan);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);

        $pdf->AddPage('P');
        $pdf->SetFont('Times','B',11);
        $pdf->MultiCell(150, $spasi, "FORMULIR PENGAJUAN VERIFIKASI PROGRAM TUGAS AKHIR\nJURUSAN ".$jurusan_upper." FMIPA UNIVERSITAS LAMPUNG",1,'C',false);
        $pdf->SetFont('Times','',11);
        $pdf->Ln(5);
        $pdf->Cell(45, $spasi,"Kepada Yth.", 0,0, 'L');
        $pdf->Ln(5);
        $pdf->Cell(45, $spasi,"Ketua Jurusan ".$jurusan, 0,0, 'L');
        $pdf->Ln(8);
        $pdf->Cell(45, $spasi,"Mahasiswa berikut telah layak melaksanakan Verifikasi Program Tugas Akhir :", 0,0, 'L');
        $pdf->Ln(9);
        $pdf->SetWidths(array(45,5, 100));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('NAMA / NPM',':',"$mhs->name/$mhs->npm"));
        $pdf->Ln(3);
        $pdf->SetWidths(array(45,5, 100));
        $pdf->SetAligns(array('L','C','L'));
        if($ta->judul_approve == 1){
            $pdf->RowNoBorder(array('JUDUL',':',$ta->judul1));
        }
        else{
            $pdf->RowNoBorder(array('JUDUL',':',$ta->judul2));
        }
        $pdf->Ln(3);

        //bidang
        $bidang = $this->ta_model->get_bidang_ilmu_id($ta->bidang_ilmu);
        $pdf->SetWidths(array(45,5, 50,40,50));
        $pdf->SetAligns(array('L','C','L','L','L'));
        $pdf->RowNoBorder(array('BIDANG',':', $bidang->nama));
        $pdf->Ln();

        //komisi
        $komisi = $this->ta_model->get_komisi($ta->id_pengajuan);
        foreach($komisi as $kom){
            $pdf->SetWidths(array(45,5, 60, 12, 5, 50));
            $pdf->SetAligns(array('L','C','L','L','C','L'));
            $gelar = $this->user_model->get_gelar_dosen_nip($kom->nip_nik);
                if(empty($gelar)){
                    $g_depan = "";
                    $g_belakang = "";
                }
                else{
                    $g_depan = $gelar->gelar_depan;
                    $g_belakang = $gelar->gelar_belakang;
                }

            $pdf->RowNoBorder(array($kom->status,':',$g_depan." ".$kom->nama.", ".$g_belakang));
            $pdf->Ln(4);
        }
        //verifikator
        $verifikator = $this->ta_model->get_dosen_verifikator($ta->id_pengajuan);
            $gelarv = $this->user_model->get_gelar_dosen_nip($verifikator->nip_nik);
            if(empty($gelarv)){
                $g_depanv = "";
                $g_belakangv = "";
            }
            else{
                $g_depanv = $gelarv->gelar_depan;
                $g_belakangv = $gelarv->gelar_belakang;
            }
            $pdf->SetWidths(array(45,5, 60, 12, 5, 50));
            $pdf->SetAligns(array('L','C','L','L','C','L'));
            $pdf->RowNoBorder(array('Dosen Verifikasi',':',$g_depanv." ".$verifikator->nama.", ".$g_belakangv));
            $pdf->Ln(4);

        $created = $this->ta_model->get_created_verifikasi_ta($ta->id_pengajuan);    
        if(!empty($created)){
             $date = explode("-",substr($created->created,0,10)); 
            $bulan = $this->get_month($date[1]);
            $pdf->Cell(150, $spasi,"Bandar Lampung, ".$date[2]." ".$bulan." ".$date[0]."", 0, 0, 'R');
        }
        else
        {
            $pdf->Cell(150, $spasi,"Bandar Lampung, ", 0, 0, 'R');
        }
       
        $pdf->Ln(8);
        if($verifikator->ket == 1){
            $pdf->Cell(150, $spasi,"Menyetujui", 0, 0, 'C');
            $pdf->Ln(5);
            $pdf->Cell(150, $spasi,"Pembimbing I", 0, 0, 'C');
            $pdf->Ln(25);
            $pdf->Cell(150, $spasi,"", 0, 0, 'C');
            $pdf->Ln(5);
            $pdf->Cell(150, $spasi,"NIP.", 0, 0, 'C');

            $pdf->Ln(7);
            $pdf->Cell(150, $spasi,"Mengetahui", 0, 0, 'C');
            $pdf->Ln(7);

            $pdf->Cell(90, $spasi,"Ketua Program Studi", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"Pembimbing Akademik", 0, 0, 'L');
            $pdf->Ln(30);

            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"", 0, 0, 'L');
            $pdf->Ln(5);

            $pdf->Cell(90, $spasi,"NIP. ", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"NIP. ", 0, 0, 'L');
        }
        elseif($verifikator->ket == 2){
            $pb =  $this->ta_model->get_verifikasi_ta_approval_status($ta->id_pengajuan,'Pembimbing Utama');
            $pb_data = $this->user_model->get_dosen_data($pb->id_user);

            $pdf->Cell(150, $spasi,"Menyetujui,", 0, 0, 'C');
            $pdf->Ln(5);
            $pdf->Cell(150, $spasi,"Pembimbing I", 0, 0, 'C');
            $pdf->Ln(5);
            $pdf->Cell(90, $spasi,$pdf->Image("$pb->ttd",$pdf->GetX()+60, $pdf->GetY(),33,0,'PNG'), 0, 0, 'C');
            $pdf->Ln(25);
            $pdf->Cell(150, $spasi,$pb_data->gelar_depan." ".$pb_data->name.", ".$pb_data->gelar_belakang, 0, 0, 'C');
            $pdf->Ln(5);
            $pdf->Cell(150, $spasi,"NIP.".$pb_data->nip_nik, 0, 0, 'C');

            $pdf->Ln(10);
            $pdf->Cell(150, $spasi,"Mengetahui,", 0, 0, 'C');
            $pdf->Ln(10);

            $pdf->Cell(90, $spasi,"Ketua Program Studi", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"Pembimbing Akademik", 0, 0, 'L');
            $pdf->Ln(30);

            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"", 0, 0, 'L');
            $pdf->Ln(5);

            $pdf->Cell(90, $spasi,"NIP. ", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"NIP. ", 0, 0, 'L');

        }
        elseif($verifikator->ket == 3){
            $pb =  $this->ta_model->get_verifikasi_ta_approval_status($ta->id_pengajuan,'Pembimbing Utama');
            $pb_data = $this->user_model->get_dosen_data($pb->id_user);
            $pa =  $this->ta_model->get_verifikasi_ta_approval_status($ta->id_pengajuan,'Pembimbing Akademik');
            $pa_data = $this->user_model->get_dosen_data($pa->id_user);

            $pdf->Cell(150, $spasi,"Menyetujui,", 0, 0, 'C');
            $pdf->Ln(5);
            $pdf->Cell(150, $spasi,"Pembimbing I", 0, 0, 'C');
            $pdf->Ln(5);
            $pdf->Cell(90, $spasi,$pdf->Image("$pb->ttd",$pdf->GetX()+60, $pdf->GetY(),33,0,'PNG'), 0, 0, 'C');
            $pdf->Ln(25);
            $pdf->Cell(150, $spasi,$pb_data->gelar_depan." ".$pb_data->name.", ".$pb_data->gelar_belakang, 0, 0, 'C');
            $pdf->Ln(5);
            $pdf->Cell(150, $spasi,"NIP.".$pb_data->nip_nik, 0, 0, 'C');

            $pdf->Ln(10);
            $pdf->Cell(150, $spasi,"Mengetahui,", 0, 0, 'C');
            $pdf->Ln(10);

            $pdf->Cell(90, $spasi,"Ketua Program Studi", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"Pembimbing Akademik", 0, 0, 'L');
            $pdf->Ln(5);

            //ttd
            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(30, $spasi,$pdf->Image("$pa->ttd",$pdf->GetX(), $pdf->GetY(),33,0,'PNG'), 0, 0, 'L');

            $pdf->Ln(25);
            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(30, $spasi,$pa_data->gelar_depan." ".$pa_data->name.", ".$pa_data->gelar_belakang, 0, 0, 'L');
            $pdf->Ln(5);

            $pdf->Cell(90, $spasi,"NIP. ", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"NIP. ".$pa_data->nip_nik, 0, 0, 'L');

        }
        elseif($verifikator->ket == 4 || $verifikator->ket == 5){
            $pb =  $this->ta_model->get_verifikasi_ta_approval_status($ta->id_pengajuan,'Pembimbing Utama');
            $pb_data = $this->user_model->get_dosen_data($pb->id_user);
            $pa =  $this->ta_model->get_verifikasi_ta_approval_status($ta->id_pengajuan,'Pembimbing Akademik');
            $pa_data = $this->user_model->get_dosen_data($pa->id_user);
            $kaprodi =  $this->ta_model->get_verifikasi_ta_approval_status($ta->id_pengajuan,'Ketua Program Studi');
            $kaprodi_data = $this->user_model->get_dosen_data($kaprodi->id_user);

            $pdf->Cell(150, $spasi,"Menyetujui,", 0, 0, 'C');
            $pdf->Ln(5);
            $pdf->Cell(150, $spasi,"Pembimbing I", 0, 0, 'C');
            $pdf->Ln(5);
            $pdf->Cell(90, $spasi,$pdf->Image("$pb->ttd",$pdf->GetX()+60, $pdf->GetY(),33,0,'PNG'), 0, 0, 'C');
            $pdf->Ln(25);
            $pdf->Cell(150, $spasi,$pb_data->gelar_depan." ".$pb_data->name.", ".$pb_data->gelar_belakang, 0, 0, 'C');
            $pdf->Ln(5);
            $pdf->Cell(150, $spasi,"NIP.".$pb_data->nip_nik, 0, 0, 'C');

            $pdf->Ln(10);
            $pdf->Cell(150, $spasi,"Mengetahui,", 0, 0, 'C');
            $pdf->Ln(10);

            $pdf->Cell(90, $spasi,"Ketua Program Studi", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"Pembimbing Akademik", 0, 0, 'L');
            $pdf->Ln(5);

            //ttd
            $pdf->Cell(90, $spasi,$pdf->Image("$kaprodi->ttd",$pdf->GetX(), $pdf->GetY(),33,0,'PNG'), 0, 0, 'L');
            $pdf->Cell(30, $spasi,$pdf->Image("$pa->ttd",$pdf->GetX(), $pdf->GetY(),33,0,'PNG'), 0, 0, 'L');

            $pdf->Ln(25);
            $pdf->Cell(90, $spasi,$kaprodi_data->gelar_depan." ".$kaprodi_data->name.", ".$kaprodi_data->gelar_belakang, 0, 0, 'L');
            $pdf->Cell(30, $spasi,$pa_data->gelar_depan." ".$pa_data->name.", ".$pa_data->gelar_belakang, 0, 0, 'L');
            $pdf->Ln(5);

            $pdf->Cell(90, $spasi,"NIP. ".$kaprodi_data->nip_nik, 0, 0, 'L');
            $pdf->Cell(30, $spasi,"NIP. ".$pa_data->nip_nik, 0, 0, 'L');

        }

        $pdf->Output('I','form_verifikasi_ta.pdf');

    }

    function verifikasi_ta_nilai($ta,$jurusan)
    {
        switch($jurusan)
        {
            case "Doktor MIPA":
            $numPage = '';
            break;
            case "Kimia":
            $numPage = '/SOP/MIPA/7.1/II/12';
            break;
            case "Biologi":
            $numPage = '/SOP/FMIPA/7.2/IV/01';
            break;
            case "Matematika":
            $numPage = '/PM/MIPA/3/08';
            break;
            case "Fisika":
            $numPage = '/SOP/MIPA/17.04/II/12/001';
            break;
            case "Ilmu Komputer":
            $numPage = '/SOP/MIPA/7.5/II/12/004';
            break;
        }

        $kode = 2;
        $type = 'Fixed';
        $mhs = $this->ta_model->get_mahasiswa_detail($ta->npm);
        $bidang = $this->ta_model->get_bidang_ilmu_id($ta->bidang_ilmu);

        $pdf = new FPDF('P','mm','A4');
        $spasi = 6;
        
        $jurusan_upper = strtoupper($jurusan);
        $pdf->number_footer(0);
        $pdf->setting_page_footer($numPage, $kode,$type);
        $pdf->set_header_jur($jurusan_upper);
        $pdf->set_header($jurusan);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);

        $pdf->AddPage('P');
        $pdf->SetFont('Times','B',11);
        $pdf->MultiCell(150, $spasi, "FORMULIR PENGAJUAN VERIFIKASI PROGRAM TUGAS AKHIR\nBIDANG APLIKASI BERBASIS WEB ".strtoupper($bidang->nama),1,'C',false);
        $pdf->SetFont('Times','',11);
        $pdf->Ln(5);
        $pdf->SetWidths(array(45,5, 100));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Nama / NPM',':',"$mhs->name / $mhs->npm"));
        $pdf->Ln(3);
        $pdf->SetWidths(array(45,5, 100));
        $pdf->SetAligns(array('L','C','L'));
        if($ta->judul_approve == 1){
            $pdf->RowNoBorder(array('Judul',':',$ta->judul1));
        }
        else{
            $pdf->RowNoBorder(array('Judul',':',$ta->judul2));
        }
        
        $pdf->Ln(3);
        $pdf->Cell(90, $spasi,'Kriteria Penilaian Verifikasi Tugas Akhir :', 0, 0, 'L');
        $pdf->Ln(6);

        //tabel
        $pdf->SetFont('Times','',10);
        $pdf->Cell(6,5,' ','LTR',0,'L',0); 
        $pdf->Cell(84,5,' ','LTR',0,'L',0); 
        $pdf->Cell(60,5,'Pertemuan Ke-',1,0,'C',0);
        $pdf->Ln();
        $pdf->Cell(6,5,'No.','LBR',0,'C',0);
        $pdf->Cell(84,5,'Pertanyaan Penguasaan','LBR',0,'C',0); 
        $pdf->Cell(6,5,'1','LBR',0,'C',0);
        $pdf->Cell(6,5,'2','LBR',0,'C',0);
        $pdf->Cell(6,5,'3','LBR',0,'C',0);
        $pdf->Cell(6,5,'4','LBR',0,'C',0);
        $pdf->Cell(6,5,'5','LBR',0,'C',0);
        $pdf->Cell(6,5,'6','LBR',0,'C',0);
        $pdf->Cell(6,5,'7','LBR',0,'C',0);
        $pdf->Cell(6,5,'8','LBR',0,'C',0);
        $pdf->Cell(6,5,'9','LBR',0,'C',0);
        $pdf->Cell(6,5,'10','LBR',0,'C',0);
        $pdf->Ln();

        $pdf->Cell(6,5,'1.',1,0,'C',0);
        $pdf->Cell(144,5,'Wajib',1,0,'L',0);
        $pdf->Ln();
        
        $nilai_wajib = $this->ta_model->get_verifikasi_program_ta_pertemuan_wajib($ta->id_pengajuan);
        $i= 1;
        $v = "V";
        foreach($nilai_wajib as $wajib)
        {
            $pdf->SetWidths(array(6,84,6,6,6,6,6,6,6,6,6,6));
            $pdf->SetAligns(array('L'));
            $pdf->Row(array('',"$i.".$wajib->komponen,$wajib->pertemuan == 1 ? $v : "",$wajib->pertemuan == 2 ? $v : "",$wajib->pertemuan == 3 ? $v : "",$wajib->pertemuan == 4 ? $v : "",$wajib->pertemuan == 5 ? $v : "",$wajib->pertemuan == 6 ? $v : "",$wajib->pertemuan == 7 ? $v : "",$wajib->pertemuan == 8 ? $v : "",$wajib->pertemuan == 9 ? $v : "",$wajib->pertemuan == 10 ? $v : ""));
            $i++;
        }

        $pdf->Cell(6,5,'2.',1,0,'C',0);
        $pdf->Cell(144,5,'Konten Program',1,0,'L',0);
        $pdf->Ln();

        $nilai_konten = $this->ta_model->get_verifikasi_program_ta_pertemuan_konten($ta->id_pengajuan);
        $j= 1;
        foreach($nilai_konten as $konten)
        {
            $pdf->SetWidths(array(6,84,6,6,6,6,6,6,6,6,6,6));
            $pdf->SetAligns(array('L'));
            $pdf->Row(array('',"$j.".$konten->komponen,$konten->pertemuan == 1 ? $v : "",$konten->pertemuan == 2 ? $v : "",$konten->pertemuan == 3 ? $v : "",$konten->pertemuan == 4 ? $v : "",$konten->pertemuan == 5 ? $v : "",$konten->pertemuan == 6 ? $v : "",$konten->pertemuan == 7 ? $v : "",$konten->pertemuan == 8 ? $v : "",$konten->pertemuan == 9 ? $v : "",$konten->pertemuan == 10 ? $v : ""));
            $j++;
        }

        $verifikator = $this->ta_model->get_dosen_verifikator($ta->id_pengajuan);
            $gelar = $this->user_model->get_gelar_dosen_nip($verifikator->nip_nik);
                if(empty($gelar)){
                    $g_depan = "";
                    $g_belakang = "";
                }
                else{
                    $g_depan = $gelar->gelar_depan;
                    $g_belakang = $gelar->gelar_belakang;
                }

        $pdf->Ln(7);
        $pdf->SetWidths(array(45,5, 100));
        $pdf->SetAligns(array('L','C','L'));
        if($verifikator->nilai != 0){
            $pdf->RowNoBorder(array('Nilai Verifikasi Tugas Akhir',':',$verifikator->nilai));
            $pdf->Ln(3);
            $pdf->SetWidths(array(45,5, 100));
            $pdf->SetAligns(array('L','C','L'));
            $pdf->RowNoBorder(array('Dengan Huruf',':',$this->huruf_mutu($verifikator->nilai)));
            $pdf->Ln(3);
        }
        else{
            $pdf->RowNoBorder(array('Nilai Verifikasi Tugas Akhir',':','......'));
            $pdf->Ln(3);
            $pdf->SetWidths(array(45,5, 100));
            $pdf->SetAligns(array('L','C','L'));
            $pdf->RowNoBorder(array('Dengan Huruf',':','......'));
            $pdf->Ln(3);
        }
        
        $date = explode("-",substr($verifikator->nilai_date,0,10));
        $bulan = $this->get_month($date[1]);
        $pdf->SetFont('Times','',11);
        $pdf->Cell(90, $spasi,"", 0, 0, 'L');
        $pdf->Cell(150, $spasi,'Bandar Lampung, '.$date[2].' '.$bulan.' '.$date[0], 0, 0, 'L');
        $pdf->Ln(7);
        $pdf->Cell(90, $spasi,"", 0, 0, 'L');
        $pdf->Cell(150, $spasi,"Dosen Verifikasi", 0, 0, 'L');
        $pdf->Ln(5);

        $pdf->Cell(90, $spasi,"", 0, 0, 'L');
        $pdf->Cell(30, $spasi,$pdf->Image("$verifikator->ttd",$pdf->GetX(), $pdf->GetY(),33,0,'PNG'), 0, 0, 'L');

        $pdf->Ln(25);
        $pdf->Cell(90,$spasi,"", 0, 0, 'L');
        $pdf->Cell(30, $spasi,$g_depan." ".$verifikator->nama.", ".$g_belakang, 0, 0, 'L');
        $pdf->Ln(5);
        $pdf->Cell(90, $spasi,"", 0, 0, 'L');
        $pdf->Cell(30, $spasi,"NIP. ".$verifikator->nip_nik, 0, 0, 'L');
        
        
        $pdf->Output('I','form_nilai_verifikasi_ta.pdf');   
    }

    //pkl
    function set_pdf_pkl(){
        $id = $this->input->get('id');
        $pkl = $this->pkl_model->select_pkl_by_id_pkl($id);

        $npm = $pkl->npm;
        $jurusan = $this->ta_model->get_jurusan($npm);
        $jenis = $this->input->get('jenis');

        switch($jenis){
            case "form_pengajuan":
                $this->form_pengajuan_kp($pkl,$jurusan);
                break;
            case "form_verifikasi":
                $this->form_verifikasi_kp($pkl,$jurusan);
                break;
            case "form_permohonan":
                $this->form_permohonan_kp($pkl,$jurusan);
                break;
        }
    }

    function set_pdf_pkl_seminar(){
        $id = $this->input->get('id');
        $seminar = $this->pkl_model->get_seminar_by_id($id);
        $pkl = $this->pkl_model->select_pkl_by_id_pkl($seminar->pkl_id);

        $npm = $pkl->npm;
        $jurusan = $this->ta_model->get_jurusan($npm);
        $jenis = $this->input->get('jenis');

        switch($jenis){
            case "form_pengajuan_seminar_kp":
                $this->form_pengajuan_kp_seminar($pkl,$seminar,$jurusan);
                break;
            case "form_verifikasi_seminar_kp":
                $this->form_verifikasi_kp_seminar($pkl,$seminar,$jurusan);
                break;
            case "undangan_seminar_kp":
                $this->undangan_seminar_kp($pkl,$seminar,$jurusan);
                break;
            case "form_penilaian_kp":
                $this->form_penilaian_kp($pkl,$seminar,$jurusan);
                break;
            case "berita_acara_kp":
                $this->berita_acara_kp($pkl,$seminar,$jurusan);
                break;
        }
    }

    function form_pengajuan_kp($pkl,$jurusan){
        $jurusan_upper = strtoupper($jurusan);
        $mhs = $this->ta_model->get_mahasiswa_detail($pkl->npm);
        $pa = $this->user_model->get_pa_by_npm($pkl->npm);

        switch($jurusan){
            case "Ilmu Komputer":
                $numPage = '';
            break;
            case "Kimia":
                $numPage = '';
            break;
            case "Fisika":
                $numPage = '';
            break;
            case "Biologi":
                $numPage = '';
            break;
            case "Matematika":
                $numPage = '';
            break;
        }

        $kode = 0;
        $type = 'Single';

        $waktu = substr($pkl->created_at,0,10);
        $waktu = explode("-",$waktu);
        $tanggal = $waktu[2];
        $bulan = $this->get_month($waktu[1]);
        $tahun = $waktu[0];

        $pdf = new FPDF('P','mm','A4');
       
        $spasi = 6;
        $pdf->number_footer(0);
        $pdf->setting_page_footer($numPage, $kode, $type);
        $pdf->set_header_jur($jurusan_upper);
        $pdf->set_header($jurusan);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);

        $pdf->AddPage();
        $pdf->SetFont('Times','B',11);
        $pdf->Ln(5);
        $pdf->MultiCell(150, $spasi, "FORMULIR PENDAFTARAN KERJA PRAKTIK/PRAKTIK KERJA LAPANGAN \nJURUSAN ".$jurusan_upper." FMIPA UNIVERSITAS LAMPUNG",0,'C',false);
        $pdf->SetFont('Times','',11);
        $pdf->Ln(5);
        $pdf->Cell(45, $spasi,"Kepada Yth.", 0,0, 'L');
        $pdf->Ln(5);
        $pdf->Cell(45, $spasi,"Ketua Jurusan ".$jurusan, 0,0, 'L');
        $pdf->Ln(5);
        $pdf->Cell(45, $spasi,"c.q. Koordinator Kerja Praktik/Praktik Kerja Lapangan", 0,0, 'L');
        $pdf->Ln(5);
        $pdf->Cell(45, $spasi,"di Tempat", 0,0, 'L');
        $pdf->Ln(9);

        $pdf->Cell(45, $spasi,"Dengan hormat,", 0,0, 'L');
        $pdf->Ln(5);
        $pdf->Cell(45, $spasi,"Berkenaan Dengan Pelaksanaan KP/PKL, dengan ini saya :", 0,0, 'L');
        $pdf->Ln(9);

        $pdf->SetWidths(array(45,5, 150));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Nama',':',$mhs->name));
        $pdf->Ln(1);
        $pdf->SetWidths(array(45,5, 150));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Npm',':',$mhs->npm));
        $pdf->Ln(1);
        $pdf->SetWidths(array(45,5, 150));
        $pdf->SetAligns(array('L','C','L'));
        $strata = substr($mhs->npm,2,1);
        if($strata == 1 || $strata == 5){
            $str = "S1";
            $jur = $jurusan;
        }
        elseif($strata == 0){
            $str = "D3";
            $jur = "Manajemen Informatika";
        }
        $pdf->RowNoBorder(array('Program Studi',':',$str." ".$jur));
        $periode = $this->pkl_model->get_pkl_kajur_by_id($pkl->id_periode);
        $pdf->Ln(9);
        $pdf->MultiCell(150, $spasi, "Mengajukan permohonan melaksanakan KP/PKL Periode $periode->periode Tahun $periode->tahun dengan syarat terlampir.",0,'L',false);
        $pdf->Ln(9);

        $pdf->Cell(90, $spasi,"", 0, 0, 'L');
        $pdf->Cell(150, $spasi,"Bandar Lampung, ".$tanggal[1]." ".$bulan." ".$tahun."", 0, 0, 'L');
        $pdf->Ln(8);  

        $pdf->Cell(45, $spasi,"Mengetahui", 0, 0, 'L');
        $pdf->Ln(5);
        $pdf->Cell(90, $spasi,"Dosen Pembimbing Akademik,", 0, 0, 'L');
        $pdf->Cell(30, $spasi,"Mahasiswa", 0, 0, 'L');
        $pdf->Ln(5);

        //ttd
        if($pkl->status >= 1){
            // $pa = $this->user_model->get_pa_by_npm($pkl->npm);
            $ttd_pa = $this->pkl_model->get_ttd_approval($pkl->pkl_id,'Pembimbing Akademik');
            $pdf->Cell(90, $spasi,$pdf->Image($ttd_pa->ttd,$pdf->GetX()+1, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');
        }
        else{
            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
        }
        $pdf->Cell(90, $spasi,$pdf->Image($pkl->ttd,$pdf->GetX()+1, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');
        
        $pdf->Ln(26);
        $pdf->Cell(90, $spasi,$pa->gelar_depan." ".$pa->name.", ".$pa->gelar_belakang, 0, 0, 'L');
        $pdf->Cell(30, $spasi,$mhs->name, 0, 0, 'L');
        $pdf->Ln(5);

        $pdf->Cell(90, $spasi,"NIP. ".$pa->nip_nik, 0, 0, 'L');
        $pdf->Cell(30, $spasi,"NPM. ".$mhs->npm, 0, 0, 'L');
        $pdf->Ln(10);


        $pdf->Output('I','pengajuan_kp.pdf');
    }

    function form_verifikasi_kp($pkl,$jurusan)
    {
        $jurusan_upper = strtoupper($jurusan);
        $mhs = $this->ta_model->get_mahasiswa_detail($pkl->npm);

        switch($jurusan){
            case "Ilmu Komputer":
                $numPage = '';
            break;
            case "Kimia":
                $numPage = '';
            break;
            case "Fisika":
                $numPage = '';
            break;
            case "Biologi":
                $numPage = '';
            break;
            case "Matematika":
                $numPage = '';
            break;
        }

        $kode = 1;
        $type = 'Single';

        $waktu = substr($pkl->created_at,0,10);
        $waktu = explode("-",$waktu);
        $tanggal = $waktu[2];
        $bulan = $this->get_month($waktu[1]);
        $tahun = $waktu[0];
        $bullet = chr(149);

        $pdf = new FPDF('P','mm','A4');
       
        $spasi = 6;
        $pdf->number_footer(0);
        $pdf->setting_page_footer($numPage, $kode, $type);
        $pdf->set_header_jur($jurusan_upper);
        $pdf->set_header($jurusan);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);

        $pdf->AddPage();
        $pdf->SetFont('Times','B',11);
        $pdf->Ln(5);
        $pdf->MultiCell(150, $spasi, "FORM VERIFIKASI BERKAS PERSYARATAN \nPENGAJUAN KERJA PRAKTIK/PRAKTIK KERJA LAPANGAN",0,'C',false);
        $pdf->SetFont('Times','',11);
        $pdf->Ln(8);
        $pdf->SetWidths(array(45,5, 150));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Nama Mahasiswa',':',$mhs->name));
        $pdf->Ln(1);
        $pdf->SetWidths(array(45,5, 150));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Npm',':',$mhs->npm));
        $pdf->Ln(1);
        $strata = substr($mhs->npm,2,1);
        if($strata == 1 || $strata == 5){
            $str = "S1";
            $jur = $jurusan;
        }
        elseif($strata == 0){
            $str = "D3";
            $jur = "Manajemen Informatika";
        }
        $pdf->SetWidths(array(45,5, 150));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Program Studi',':',$str." ".$jur));
        $pdf->Ln(1);
        $pdf->SetWidths(array(45,5, 150));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Fakultas/Jurusan',':','Matematika dan Ilmu Pengetahuan Alam/'.$jurusan));
        $pdf->Ln(1);
        $surat = $this->pkl_model->get_surat_pkl($pkl->pkl_id);
        $surat = substr($surat->created_at,0,10);
        $surat = explode("-",$surat);
        $pdf->SetWidths(array(45,5, 150));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Tanggal Masuk Berkas',':',$surat[2]."-".$surat[1]."-".$surat[0]));
        $pdf->Ln(8);

        $pdf->Cell(45, $spasi,"Persayaratan Kerja Praktik yang harus dipenuhi oleh mahasiswa", 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Ln(8);

        $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
        if($jurusan == "Kimia" || ($jurusan == "Ilmu Komputer" && $strata == 0)){
            $pdf->MultiCell(150, $spasi, "Telah menyelesaikan minimal 80 SKS, dengan IPK 2,00.",0,'J',false);
            $pdf->Ln(1);
        }
        else{
            $pdf->Cell(83, $spasi,"Telah menyelesaikan minimal 100 SKS, dengan IPK ", 0, 0, 'L');
            $pdf->SetFont("Symbol");
            $pdf->Cell(5, $spasi,chr(179)." 2,00", 0, 2, 'L');
            $pdf->SetFont('Times','',11);
            $pdf->Ln(1);
        }

        $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
        $pdf->MultiCell(150, $spasi, "Menyerahkan 1 lembar Formulir Kerja Praktik/Praktik Kerja Lapangan yang sudah ditandatangani oleh pembimbing akademik.",0,'J',false);
        $pdf->Ln(1);

        $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
        $pdf->MultiCell(150, $spasi, "Menyerahkan 1 lembar Transkrip Akademik yang telah ditandatangani oleh Wakil Dekan 1 dan telah diberi cap fakultas.",0,'J',false);
        $pdf->Ln(1);

        $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
        $pdf->MultiCell(150, $spasi, "Menyerahkan 1 lembar KRS terakhir yang telah ditandatangani oleh Pembantu Dekan 1 dan telah diberi cap fakultas.",0,'J',false);
        $pdf->Ln(1);

        $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
        $pdf->MultiCell(150, $spasi, "Menyerahkan 1 lembar foto copy bukti pembayaran SPP terakhir.",0,'J',false);
        $pdf->Ln(1);

        if($jurusan == "Biologi"){
            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "Menyerahkan 1 lembar Formulir Surat permohonan Izin Praktik Kerja Lapangan.",0,'J',false);
            $pdf->Ln(1);
        }

        $pdf->Cell(5, $spasi,"Catatan : ", 0, 0, 'L');
        $pdf->Ln(5);
        $pdf->Cell(5, $spasi,"", 0, 0, 'L');
        $pdf->Cell(5, $spasi,"1.", 0, 0, 'L');
        $pdf->MultiCell(150, $spasi, "Mahasiswa yang melaksanakan kerja praktik wajib mematuhi semua jadwal dan tata tertib pelaksanaan kerja praktik sesuai dengan persyaratan yang telah ditetapkan oleh jurusan maupun instansi/perusahaan tempat KP/PKL.",0,'J',false);
        $pdf->Ln(1);
        $pdf->Cell(5, $spasi,"", 0, 0, 'L');
        $pdf->Cell(5, $spasi,"2.", 0, 0, 'L');
        $pdf->MultiCell(150, $spasi, "Bagi mahasiswa yang tidak memenuhi persyaratan untuk melaksanakan kerja praktik, maka kerja praktik mahasiswa bersangkutan dapat dibatalkan oleh jurusan.",0,'J',false);
        $pdf->Ln(9);

        $surat2 = $this->pkl_model->get_surat_pkl($pkl->pkl_id);
        if($surat2->updated_at != NULL){
            $surat2 = substr($surat2->updated_at,0,10);
            $surat2 = explode("-",$surat2);
            $tgl_verifikasi = $surat2[2]." ".$this->get_month($surat2[1])." ".$surat2[0];
        }
        else{
            $tgl_verifikasi = "";
        }

        $pdf->Cell(90, $spasi,"", 0, 0, 'L');
        $pdf->Cell(150, $spasi,"Bandar Lampung, ".$tgl_verifikasi, 0, 0, 'L');
        $pdf->Ln(8);  


        $pdf->Cell(90, $spasi,"", 0, 0, 'L');
        $pdf->Cell(30, $spasi,"Pemeriksa Berkas", 0, 0, 'L');
        $pdf->Ln(5);

        //ttd
        if($pkl->status >= 2){
            $ttd_admin = $this->pkl_model->get_ttd_approval($pkl->pkl_id,'Administrasi');
            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(30, $spasi,$pdf->Image("$ttd_admin->ttd",$pdf->GetX()-3, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');
        
            $admin = $this->user_model->get_tendik_name($ttd_admin->id_user);
            $pdf->Ln(26);
            $pdf->Cell(90, $spasi,'', 0, 0, 'L');
            $pdf->Cell(30, $spasi,$admin->gelar_depan." ".$admin->name.", ".$admin->gelar_belakang, 0, 0, 'L');
            $pdf->Ln(5);

            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"NIP. ".$admin->nip_nik, 0, 0, 'L');
            $pdf->Ln(10);
        }
        else{
            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(30, $spasi,'', 0, 0, 'L');

            $pdf->Ln(26);
            $pdf->Cell(90, $spasi,'', 0, 0, 'L');
            $pdf->Cell(30, $spasi,'', 0, 0, 'L');
            $pdf->Ln(5);

            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"NIP. ",0, 0, 'L');
            $pdf->Ln(10);
        }

        $pdf->Output('I','verifikasi_kp.pdf');
    }

    function form_permohonan_kp($pkl,$jurusan)
    {
        $jurusan_upper = strtoupper($jurusan);
        $mhs = $this->ta_model->get_mahasiswa_detail($pkl->npm);

        switch($jurusan){
            case "Ilmu Komputer":
                $numPage = '';
            break;
            case "Kimia":
                $numPage = '';
            break;
            case "Fisika":
                $numPage = '';
            break;
            case "Biologi":
                $numPage = '';
            break;
            case "Matematika":
                $numPage = '';
            break;
        }

        $kode = 2;
        $type = 'Single';

        $waktu = substr($pkl->created_at,0,10);
        $waktu = explode("-",$waktu);
        $tanggal = $waktu[2];
        $bulan = $this->get_month($waktu[1]);
        $tahun = $waktu[0];
        $bullet = chr(149);

        $pdf = new FPDF('P','mm','A4');
       
        $spasi = 6;
        $pdf->number_footer(0);
        $pdf->setting_page_footer($numPage, $kode, $type);
        $pdf->set_header_jur($jurusan_upper);
        $pdf->set_header($jurusan);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);

        $pdf->AddPage();
        $pdf->SetFont('Times','',11);
        $pdf->SetWidths(array(30,5, 70,50));
        $pdf->SetAligns(array('L','L','L','R'));
        $pdf->RowNoBorder(array('Nomor',':',$pkl->no_penetapan,$tanggal." ".$bulan." 2020"));
        // $pdf->Ln(8);
        $pdf->SetFont('Times','',11);
        $pdf->SetWidths(array(30,5, 70));
        $pdf->SetAligns(array('L','L','L'));
        $pdf->RowNoBorder(array('Lampiran',':',"1 Lembar"));
        // $pdf->Ln(8);
        $strata = substr($mhs->npm,2,1);
        if($strata == 1 || $strata == 5){
            $str = "S1";
            $jur = $jurusan;
        }
        elseif($strata == 0){
            $str = "D3";
            $jur = "Manajemen Informatika";
        }

        $pdf->SetFont('Times','',11);
        $pdf->SetWidths(array(30,5, 150));
        $pdf->SetAligns(array('L','L','L'));
        $pdf->RowNoBorder(array('Perihal',':',"Permohonan Suat Izin KP/PKL Mahasiswa ".$str." ".$jur));
        $pdf->Ln(5);
        $pdf->Cell(150, $spasi,"Kepada Yth. Dekan FMIPA Universitas Lampung", 0,0, 'L');
        $pdf->Ln(5);
        $pdf->Cell(45, $spasi,"Di Tempat", 0,0, 'L');
        $pdf->Ln(9);

        $pdf->Cell(150, $spasi,"Dengan Hormat,", 0,0, 'L');
        $pdf->Ln(5);
        $pdf->MultiCell(150, $spasi, "Berkenaan dengan pelaksanaan KP/PKL bagi mahasiswa Jurusan Ilmu Komputer yang rencananya akan dilaksakan di",0,'L',false);
        $pdf->Ln(5);

        //tempat
        $lokasi = $this->pkl_model->get_lokasi_pkl_by_id($pkl->id_lokasi);
        $pdf->SetFont('Times','',11);
        $pdf->SetWidths(array(45,5,110));
        $pdf->SetAligns(array('L','L','L'));
        $pdf->RowNoBorder(array('Nama Instansi/Perusahaan',':',$lokasi->lokasi));

        $pdf->SetFont('Times','',11);
        $pdf->SetWidths(array(45,5, 110));
        $pdf->SetAligns(array('L','L','L'));
        $pdf->RowNoBorder(array('Alamat',':',$lokasi->alamat));

        //mulai
        $date_start = $this->pkl_model->date_start($pkl->id_periode);
        $date_start = substr($date_start->date_start,0,10);
        $date_start = explode("-",$date_start);
        $date_start = $date_start[2]." ".$this->get_month($date_start[1])." ".$date_start[0];
        //selesai
        $date_end = $this->pkl_model->date_end($pkl->id_periode);
        $date_end = substr($date_end->date_start,0,10);
        $date_end = explode("-",$date_end);
        $date_end = $date_end[2]." ".$this->get_month($date_end[1])." ".$date_end[0];

        $pdf->SetFont('Times','',11);
        $pdf->SetWidths(array(45,5, 110));
        $pdf->SetAligns(array('L','L','L'));
        $pdf->RowNoBorder(array('Waktu Pelaksanaan',':',$date_start." - ".$date_end));

        $pdf->Ln(5);
        $pdf->MultiCell(150, $spasi, "Mohon dibuatkan surat pengantar permohonan izin tempat KP/PKL ke instansi/perusahaan tersebut untuk mahasiswa berikut:",0,'L',false);
        $pdf->Ln(5);

        //tabel
        $pdf->Cell(20,10,'No',1,0,'C',0);
        $pdf->Cell(60,10,'NPM',1,0,'C',0);
        $pdf->Cell(80,10,'Nama',1,0,'C',0);
        //loop
        $list = $this->pkl_model->get_mahasiswa_pkl_by_lok_no_per($pkl->id_lokasi,$pkl->no_penetapan,$pkl->id_periode);
        $no = 0;
        foreach($list as $lst)
        {
            $pdf->Ln();
            $pdf->Cell(20,10,++$no,1,0,'C',0);
            $pdf->Cell(60,10,$lst->npm,1,0,'C',0);
            $pdf->Cell(80,10,$this->user_model->get_mahasiswa_name($lst->npm),1,0,'C',0);
        }

        $pdf->Ln(12);
        $pdf->MultiCell(150, $spasi, "Demikian permohonan kami, atas perhatian dan kerjasamanya diucapkan terima kasih.",0,'L',false);
        $pdf->Ln(5);

        if($strata == 1 || $strata == 5)
        {
            $ttd_kajur = $this->pkl_model->get_ttd_approval($pkl->pkl_id,'Ketua Jurusan'); 
            $kajur_data = $this->user_model->get_dosen_data($ttd_kajur->id_user);


            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"Ketua Jurusan", 0, 0, 'L');
            $pdf->Ln(5);

            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(90, $spasi,$pdf->Image($ttd_kajur->ttd,$pdf->GetX()+1, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');
            
            $pdf->Ln(26);
            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(30, $spasi,$kajur_data->gelar_depan." ".$kajur_data->name.", ".$kajur_data->gelar_belakang, 0, 0, 'L');
            $pdf->Ln(5);

            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"NIP.".$kajur_data->nip_nik, 0, 0, 'L');
            $pdf->Ln(10);
        }
        elseif($strata == 0)
        {
            $ttd_kajur = $this->pkl_model->get_ttd_approval($pkl->pkl_id,'Ketua Jurusan'); 
            $ttd_kaprodi = $this->pkl_model->get_ttd_approval($pkl->pkl_id,'Ketua Program Studi'); 
            $kajur_data = $this->user_model->get_dosen_data($ttd_kajur->id_user);
            $kaprodi_data = $this->user_model->get_dosen_data($ttd_kaprodi->id_user);

            $pdf->Cell(90, $spasi,"Ketua Jurusan", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"Ketua Program Studi", 0, 0, 'L');
            $pdf->Ln(5);

            
            $pdf->Cell(90, $spasi,$pdf->Image($ttd_kajur->ttd,$pdf->GetX()+1, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');
            $pdf->Cell(90, $spasi,$pdf->Image($ttd_kaprodi->ttd,$pdf->GetX()+1, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');

            $pdf->Ln(26);
            
            $pdf->Cell(90, $spasi,$kajur_data->gelar_depan." ".$kajur_data->name.", ".$kajur_data->gelar_belakang, 0, 0, 'L');
            $pdf->Cell(30, $spasi,$kaprodi_data->gelar_depan." ".$kaprodi_data->name.", ".$kaprodi_data->gelar_belakang, 0, 0, 'L');
            $pdf->Ln(5);

            $pdf->Cell(90, $spasi,"NIP.".$kajur_data->nip_nik, 0, 0, 'L');
            $pdf->Cell(30, $spasi,"NIP.".$kaprodi_data->nip_nik, 0, 0, 'L');
            $pdf->Ln(10);
        }

        $pdf->Output('I','permohonan_kp.pdf');
    }

    function form_pengajuan_kp_seminar($pkl,$seminar,$jurusan)
    {
        $jurusan_upper = strtoupper($jurusan);
        $mhs = $this->ta_model->get_mahasiswa_detail($pkl->npm);
        $pbb = $this->user_model->get_dosen_name($pkl->pembimbing);
        $pbl = $this->pkl_model->get_pb_lapangan($pkl->pkl_id);
        // $pa = $this->user_model->get_pa_by_npm($pkl->npm);

        switch($jurusan){
            case "Ilmu Komputer":
                $numPage = '';
            break;
            case "Kimia":
                $numPage = '';
            break;
            case "Fisika":
                $numPage = '';
            break;
            case "Biologi":
                $numPage = '';
            break;
            case "Matematika":
                $numPage = '';
            break;
        }

        $kode = 0;
        $type = 'Single';

        $waktu = substr($pkl->created_at,0,10);
        $waktu = explode("-",$waktu);
        $tanggal = $waktu[2];
        $bulan = $this->get_month($waktu[1]);
        $tahun = $waktu[0];

        $pdf = new FPDF('P','mm','A4');
       
        $spasi = 6;
        $pdf->number_footer(0);
        $pdf->setting_page_footer($numPage, $kode, $type);
        $pdf->set_header_jur($jurusan_upper);
        $pdf->set_header($jurusan);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);

        $pdf->AddPage();
        $pdf->SetFont('Times','B',11);
        $pdf->Ln(5);
        $pdf->MultiCell(170, $spasi, "FORMULIR PENGAJUAN SEMINAR KERJA PRAKTIK/PRAKTIK KERJA LAPANGAN \nJURUSAN ".$jurusan_upper." FMIPA UNIVERSITAS LAMPUNG",0,'C',false);
        $pdf->SetFont('Times','',11);
        $pdf->Ln(5);
        $pdf->Cell(45, $spasi,"Kepada Yth.", 0,0, 'L');
        $pdf->Ln(5);
        $pdf->Cell(45, $spasi,"Ketua Jurusan ".$jurusan, 0,0, 'L');
        $pdf->Ln(12);

        $pdf->Cell(45, $spasi,"Mahasiswa berikut telah layak melaksanakan Seminar KP/PKL :", 0,0, 'L');
        $pdf->Ln(9);

        $pdf->SetWidths(array(30,5, 150));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Nama / NPM',':',$mhs->name." / ".$mhs->npm));
        $pdf->Ln(1);
        $pdf->SetWidths(array(30,5, 120));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Judul KP/PKL',':',$seminar->judul));
        $pdf->Ln(3);
        $pdf->SetWidths(array(50,5, 120));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Dosen Pembimbing',':',$pbb->gelar_depan." ".$pbb->name.", ".$pbb->gelar_belakang));
        $pdf->Ln(1);
        $pdf->SetWidths(array(50,5, 120));
        $pdf->SetAligns(array('L','C','L'));
        if(!empty($pbl)){
            $pdf->RowNoBorder(array('Pembimbing Lapangan',':',$pbl->nama));
        }
        else{
            $pdf->RowNoBorder(array('Pembimbing Lapangan',':','-'));
        }
        $pdf->Ln(1);

        $pdf->Ln(9);
        $pdf->Cell(90, $spasi,"", 0, 0, 'L');
        $pdf->Cell(150, $spasi,"Bandar Lampung, ".$tanggal[1]." ".$bulan." ".$tahun."", 0, 0, 'L');
        $pdf->Ln(8);  

        $pdf->Cell(90, $spasi,"Mengetahui,", 0, 0, 'L');
        $pdf->Cell(30, $spasi,"Menyetujui,", 0, 0, 'L');
        $pdf->Ln(5);
        $pdf->Cell(90, $spasi,"Koordinator KP/PKL", 0, 0, 'L');
        $pdf->Cell(30, $spasi,"Dosen Pembimbing KP/PKL", 0, 0, 'L');
        $pdf->Ln(5);
        
        //ttd
        if($seminar->status >= 1 && $seminar->status < 3){
            $ttd_pb = $this->pkl_model->get_ttd_approval_seminar($seminar->seminar_id,'Dosen Pembimbing');
            $pdf->Cell(90, $spasi,'', 0, 0, 'L');
            $pdf->Cell(30, $spasi,$pdf->Image($ttd_pb->ttd,$pdf->GetX()+1, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');

            $pb_data = $this->user_model->get_dosen_data($ttd_pb->id_user);
            $pdf->Ln(26);
            $pdf->Cell(90, $spasi,'', 0, 0, 'L');
            $pdf->Cell(30, $spasi,$pb_data->gelar_depan." ".$pb_data->name.", ".$pb_data->gelar_belakang, 0, 0, 'L');
            $pdf->Ln(5);
    
            $pdf->Cell(90, $spasi,"NIP. ", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"NIP. ".$pb_data->nip_nik, 0, 0, 'L');
            $pdf->Ln(10);
        }
        elseif($seminar->status >= 3 ){
            $ttd_pb = $this->pkl_model->get_ttd_approval_seminar($seminar->seminar_id,'Dosen Pembimbing');
            $ttd_koor = $this->pkl_model->get_ttd_approval_seminar($seminar->seminar_id,'Koordinator');

            $pdf->Cell(90, $spasi,$pdf->Image($ttd_koor->ttd,$pdf->GetX()+1, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');
            $pdf->Cell(30, $spasi,$pdf->Image($ttd_pb->ttd,$pdf->GetX()+1, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');
        
            $pb_data = $this->user_model->get_dosen_data($ttd_pb->id_user);
            $koor_data = $this->user_model->get_dosen_data($ttd_koor->id_user);
            $pdf->Ln(26);
            $pdf->Cell(90, $spasi,$koor_data->gelar_depan." ".$koor_data->name.", ".$koor_data->gelar_belakang, 0, 0, 'L');
            $pdf->Cell(30, $spasi,$pb_data->gelar_depan." ".$pb_data->name.", ".$pb_data->gelar_belakang, 0, 0, 'L');
            $pdf->Ln(5);
    
            $pdf->Cell(90, $spasi,"NIP. ".$koor_data->nip_nik, 0, 0, 'L');
            $pdf->Cell(30, $spasi,"NIP. ".$pb_data->nip_nik, 0, 0, 'L');
            $pdf->Ln(10);
        }
        else{
            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"", 0, 0, 'L');
            $pdf->Ln(26);
            $pdf->Cell(90,$spasi,"", 0, 0, 'L');
            $pdf->Cell(30,$spasi,"", 0, 0, 'L');
            $pdf->Ln(5);
    
            $pdf->Cell(90, $spasi,"NIP. ", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"NIP. ", 0, 0, 'L');
            $pdf->Ln(10);
        }
        

        $pdf->Output('I','pengajuan_seminar_kp.pdf');
    }

    function form_verifikasi_kp_seminar($pkl,$seminar,$jurusan)
    {
        $jurusan_upper = strtoupper($jurusan);
        $mhs = $this->ta_model->get_mahasiswa_detail($pkl->npm);

        switch($jurusan){
            case "Ilmu Komputer":
                $numPage = '';
            break;
            case "Kimia":
                $numPage = '';
            break;
            case "Fisika":
                $numPage = '';
            break;
            case "Biologi":
                $numPage = '';
            break;
            case "Matematika":
                $numPage = '';
            break;
        }

        $kode = 0;
        $type = 'Single';

        $waktu = substr($pkl->created_at,0,10);
        $waktu = explode("-",$waktu);
        $tanggal = $waktu[2];
        $bulan = $this->get_month($waktu[1]);
        $tahun = $waktu[0];
        $bullet = chr(149);

        $pdf = new FPDF('P','mm','A4');
       
        $spasi = 6;
        $pdf->number_footer(0);
        $pdf->setting_page_footer($numPage, $kode, $type);
        $pdf->set_header_jur($jurusan_upper);
        $pdf->set_header($jurusan);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);

        $pdf->AddPage();
        $pdf->SetFont('Times','B',11);
        $pdf->Ln(5);
        $pdf->MultiCell(150, $spasi, "FORM VERIFIKASI BERKAS PERSYARATAN \nPENGAJUAN SEMINAR KERJA PRAKTIK/PRAKTIK KERJA LAPANGAN",0,'C',false);
        $pdf->SetFont('Times','',11);
        $pdf->Ln(8);
        $pdf->SetWidths(array(45,5, 150));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Nama Mahasiswa',':',$mhs->name));
        $pdf->Ln(1);
        $pdf->SetWidths(array(45,5, 150));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Npm',':',$mhs->npm));
        $pdf->Ln(1);
        $pdf->SetWidths(array(45,5, 150));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Fakultas/Jurusan',':','Matematika dan Ilmu Pengetahuan Alam/'.$jurusan));
        $surat = $this->pkl_model->get_surat_seminar_pkl($seminar->seminar_id);
        $surat = substr($surat->created_at,0,10);
        $surat = explode("-",$surat);
        $pdf->SetWidths(array(45,5, 150));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Tanggal Masuk Berkas',':',$surat[2]."-".$surat[1]."-".$surat[0]));
        $pdf->Ln(8);
        $pdf->Cell(45, $spasi,"Persayaratan Seminar Kerja Praktik yang harus dipenuhi oleh mahasiswa", 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Ln(8);
        $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
        $pdf->MultiCell(150, $spasi, "Menyerahkan 1 lembar Formulir Kerja Praktik/Praktik Kerja Lapangan yang sudah ditandatangani oleh pembimbing akademik.",0,'J',false);
        $pdf->Ln(1);
        $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
        $pdf->MultiCell(150, $spasi, "Terdaftar sebagai mahasiswa, yang dibuktikan dengan fotocopy KTM 1 lembar.",0,'J',false);
        $pdf->Ln(1);
        $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
        $pdf->MultiCell(150, $spasi, "Menyerahkan 1 lembar fotocopy bukti pembayaran SPP terakhir.",0,'J',false);
        $pdf->Ln(1);
        $strata = substr($mhs->npm,2,1);
        $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
        if($strata == 1 || $strata == 5){
            $pdf->MultiCell(150, $spasi, "Telah mengambil mata kuliah dengan beban 100 SKS, dengan IPK 2,00",0,'J',false);
        }
        elseif($strata == 0){
            $pdf->MultiCell(150, $spasi, "Telah mengambil mata kuliah dengan beban 80 SKS, dengan IPK 2,00",0,'J',false);
        }
        $pdf->Ln(1);
        $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
        $pdf->MultiCell(150, $spasi, "Menyerahkan 1 lembar Transkrip akademik yang telah ditandatangani oleh Wakil Dekan 1 dan telah diberi cap fakultas.",0,'J',false);
        $pdf->Ln(1);
        $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
        $pdf->MultiCell(150, $spasi, "Menyerahkan  1 lembar fotocopy Jadwal Konsultasi Pembimbing praktik kerja lapangan.",0,'J',false);
        $pdf->Ln(1);
        $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
        $pdf->MultiCell(150, $spasi, "Telah mengikuti seminar KP/PKL minimal 5 kali (ditandai dengan menunjukkan dan memfotocopy 1 lembar (Buku Kendali Akademik).",0,'J',false);
        $pdf->Ln(9);

        $surat2 = $this->pkl_model->get_surat_seminar_pkl($seminar->seminar_id);
        if($surat2->updated_at != NULL){
            $surat2 = substr($surat2->updated_at,0,10);
            $surat2 = explode("-",$surat2);
            $tgl_verifikasi = $surat2[2]." ".$this->get_month($surat2[1])." ".$surat2[0];
        }
        else{
            $tgl_verifikasi = "";
        }

        $pdf->Cell(90, $spasi,"", 0, 0, 'L');
        $pdf->Cell(150, $spasi,"Bandar Lampung, ".$tgl_verifikasi, 0, 0, 'L');
        $pdf->Ln(8);  

        $pdf->Cell(90, $spasi,"", 0, 0, 'L');
        $pdf->Cell(30, $spasi,"Pemeriksa Berkas", 0, 0, 'L');
        $pdf->Ln(5);

          //ttd
        if($pkl->status >= 2){
            $ttd_admin =$this->pkl_model->get_ttd_approval_seminar($seminar->seminar_id,'Administrasi');
            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(30, $spasi,$pdf->Image("$ttd_admin->ttd",$pdf->GetX()-3, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');
        
            $admin = $this->user_model->get_tendik_name($ttd_admin->id_user);
            $pdf->Ln(26);
            $pdf->Cell(90, $spasi,'', 0, 0, 'L');
            $pdf->Cell(30, $spasi,$admin->gelar_depan." ".$admin->name.", ".$admin->gelar_belakang, 0, 0, 'L');
            $pdf->Ln(5);

            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"NIP. ".$admin->nip_nik, 0, 0, 'L');
            $pdf->Ln(10);
        }
        else{
            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(30, $spasi,'', 0, 0, 'L');

            $pdf->Ln(26);
            $pdf->Cell(90, $spasi,'', 0, 0, 'L');
            $pdf->Cell(30, $spasi,'', 0, 0, 'L');
            $pdf->Ln(5);

            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"NIP. ",0, 0, 'L');
            $pdf->Ln(10);
        }


        $pdf->Output('I','verifikasi_seminar_kp.pdf');
    }

    function undangan_seminar_kp($pkl,$seminar,$jurusan)
    {
        $jurusan_upper = strtoupper($jurusan);
        $mhs = $this->ta_model->get_mahasiswa_detail($pkl->npm);
        $pbb = $this->user_model->get_dosen_name($pkl->pembimbing);

        switch($jurusan){
            case "Ilmu Komputer":
                $numPage = '';
            break;
            case "Kimia":
                $numPage = '';
            break;
            case "Fisika":
                $numPage = '';
            break;
            case "Biologi":
                $numPage = '';
            break;
            case "Matematika":
                $numPage = '';
            break;
        }

        $kode = 0;
        $type = 'Single';

        $waktu = substr($pkl->created_at,0,10);
        $waktu = explode("-",$waktu);
        $tanggal = $waktu[2];
        $bulan = $this->get_month($waktu[1]);
        $tahun = $waktu[0];
        $bullet = chr(149);

        $pdf = new FPDF('P','mm','A4');
       
        $spasi = 6;
        $pdf->number_footer(0);
        $pdf->setting_page_footer($numPage, $kode, $type);
        $pdf->set_header_jur($jurusan_upper);
        $pdf->set_header($jurusan);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);

        $pdf->AddPage();
        $pdf->SetFont('Times','B',11);
        $pdf->Ln(5);
        $pdf->MultiCell(150, $spasi, "UNDANGAN SEMINAR KERJA PRAKTIK/PRAKTIK KERJA LAPANGAN \nJURUSAN ".$jurusan_upper." FMIPA UNIVERSITAS LAMPUNG",0,'C',false);
        $pdf->SetFont('Times','',11);
        $pdf->Ln(3);
        $pdf->MultiCell(150, $spasi, "$seminar->no_form",0,'C',false);
        $pdf->Cell(45, $spasi,"Kepada Yth.", 0,0, 'L');
        $pdf->Ln(5);
        $pdf->Cell(45,$spasi,"Bapak/Ibu/Sdr/i ".$pbb->gelar_depan." ".$pbb->name.", ".$pbb->gelar_belakang, 0,0, 'L');
        $pdf->Ln(5);
        $pdf->Cell(45, $spasi,"di Tempat", 0,0, 'L');
        $pdf->Ln(9);

        $pdf->Cell(45, $spasi,"Dengan hormat,", 0,0, 'L');
        $pdf->Ln(5);
        $pdf->MultiCell(150, $spasi, "Bersama ini kami mengundang Bapak/Ibu/Sdr/i, untuk menghadiri Seminar KP/PKL mahasiswa berikut sebagai Dosen Pembimbing :",0,'L',false);
        $pdf->Ln(5);

        $pdf->SetWidths(array(45,5, 150));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Nama/NPM',':',$mhs->name."/".$mhs->npm));
        $pdf->Ln(1);
        $pdf->SetWidths(array(45,5, 120));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Judul',':',$seminar->judul));
        $pdf->Ln(5);

        $pdf->Cell(45, $spasi,"Pelaksanaan Seminar KP/PKL   :", 0,0, 'L');
        $pdf->Ln(7);

        $date = strtotime($seminar->tgl_pelaksanaan);
        $date = date('l', $date);
        $hari = $this->get_day($date);
        $tgl = explode("-",$seminar->tgl_pelaksanaan);

        $pdf->SetWidths(array(50,5, 150));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Hari/Tanggal',':',$hari."/".$tgl[2]."-".$tgl[1]."-".$tgl[0]));
        $pdf->Ln(1);
        $pdf->SetWidths(array(50,5, 35,15,5, 50));
        $pdf->SetAligns(array('L','C','L','L','C','L'));
        $pdf->RowNoBorder(array('Waktu/Tempat',':',$seminar->waktu_pelaksanaan,"Ruang",":",$seminar->tempat));

        $pdf->Ln(9); 
        $tgl1 = substr($seminar->updated_at,0,10);
        $tgl2 = explode("-",$tgl1);
        $bulan = $this->get_month($tgl2[1]);
        $pdf->Cell(90, $spasi,"", 0, 0, 'L');
        $pdf->Cell(150, $spasi,"Bandar Lampung, ".$tgl2[2]." ".$bulan." ".$tgl2[0]."", 0, 0, 'L');
        $pdf->Ln(8);  

        $pdf->Cell(90, $spasi,"Mengetahui,", 0, 0, 'L');
        $pdf->Cell(30, $spasi,"Menyetujui,", 0, 0, 'L');
        $pdf->Ln(5);
        $strata = substr($mhs->npm,2,1);
        if($strata == 0){
            $pdf->Cell(90, $spasi,"Ketua Program Studi", 0, 0, 'L');
        }
        else{
            $pdf->Cell(90, $spasi,"Ketua Jurusan", 0, 0, 'L');
        }
        $pdf->Cell(30, $spasi,"Koordinator KP/PKL", 0, 0, 'L');
        $pdf->Ln(5);

         //ttd
         if($pkl->status >= 3 && $pkl->status <= 4){
            //koor
            $ttd_koor = $this->pkl_model->get_ttd_approval_seminar($seminar->seminar_id,'Koordinator');
            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(30, $spasi,$pdf->Image($ttd_koor->ttd,$pdf->GetX()+1, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');
        
            $koor_data = $this->user_model->get_dosen_data($ttd_koor->id_user);
            $pdf->Ln(26);
            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(30, $spasi,$koor_data->gelar_depan." ".$koor_data->name.", ".$koor_data->gelar_belakang, 0, 0, 'L');
            $pdf->Ln(5);

            $pdf->Cell(90, $spasi,"NIP. ", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"NIP. ".$koor_data->nip_nik, 0, 0, 'L');
            $pdf->Ln(10);
        }
        elseif($pkl->status >= 4){
            //koor dan kajur
            $ttd_koor = $this->pkl_model->get_ttd_approval_seminar($seminar->seminar_id,'Koordinator');
            $ttd_kajur = $this->pkl_model->get_ttd_approval_seminar($seminar->seminar_id,'Ketua Jurusan');
            $pdf->Cell(90, $spasi,$pdf->Image($ttd_kajur->ttd,$pdf->GetX()+1, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');
            $pdf->Cell(30, $spasi,$pdf->Image($ttd_koor->ttd,$pdf->GetX()+1, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');
        
            $koor_data = $this->user_model->get_dosen_data($ttd_koor->id_user);
            $kajur_data = $this->user_model->get_dosen_data($ttd_kajur->id_user);
            $pdf->Ln(26);
            $pdf->Cell(90, $spasi,$kajur_data->gelar_depan." ".$kajur_data->name.", ".$kajur_data->gelar_belakang, 0, 0, 'L');
            $pdf->Cell(30, $spasi,$koor_data->gelar_depan." ".$koor_data->name.", ".$koor_data->gelar_belakang, 0, 0, 'L');
            $pdf->Ln(5);

            $pdf->Cell(90, $spasi,"NIP. ".$kajur_data->nip_nik, 0, 0, 'L');
            $pdf->Cell(30, $spasi,"NIP. ".$koor_data->nip_nik, 0, 0, 'L');
            $pdf->Ln(10);
        }
        else{
            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"", 0, 0, 'L');

            $pdf->Ln(26);
            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"", 0, 0, 'L');
            $pdf->Ln(5);

            $pdf->Cell(90, $spasi,"NIP. ", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"NIP. ", 0, 0, 'L');
            $pdf->Ln(10);
        }


        $pdf->Output('I','undangan_seminar_kp.pdf');
    }

    function form_penilaian_kp($pkl,$seminar,$jurusan)
    {
        $jurusan_upper = strtoupper($jurusan);
        $mhs = $this->ta_model->get_mahasiswa_detail($pkl->npm);

        switch($jurusan){
            case "Ilmu Komputer":
                $numPage = '';
            break;
            case "Kimia":
                $numPage = '';
            break;
            case "Fisika":
                $numPage = '';
            break;
            case "Biologi":
                $numPage = '';
            break;
            case "Matematika":
                $numPage = '';
            break;
        }

        $kode = 0;
        $type = 'Single';

        $pdf = new FPDF('P','mm','A4');
       
        $spasi = 6;
        $pdf->number_footer(0);
        $pdf->setting_page_footer($numPage, $kode, $type);
        $pdf->set_header_jur($jurusan_upper);
        $pdf->set_header($jurusan);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);

        $pdf->AddPage();
        $pdf->SetFont('Times','B',11);
        $pdf->Ln(5);
        $pdf->MultiCell(150, $spasi, "LEMBAR PENILAIAN KERJA PRAKTIK/PRAKTIK KERJA LAPANGAN \nDOSEN PEMBIMBING",1,'C',false);
        $pdf->SetFont('Times','',11);
        $pdf->Ln(5);
        $pdf->SetWidths(array(45,5, 150));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Nama / NPM',':',$mhs->name." / ".$mhs->npm));
        $pdf->Ln(1);
        $pdf->SetWidths(array(45,5, 120));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Judul KP/PKL',':',$seminar->judul));
        $pdf->Ln(1);
        $pdf->SetWidths(array(45,5, 120));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Tempat KP/PKL',':',$this->pkl_model->get_lokasi_pkl_by_id($pkl->id_lokasi)->lokasi));
        $pdf->Ln(5);

          //tables
          $pdf->Cell(80,10,'Aspek yang dinilai',1,0,'C',0);
          $pdf->Cell(20,10,'Nilai',1,0,'C',0);
          $pdf->Cell(30,10,'Persentase',1,0,'C',0);
          $pdf->Cell(20,10,'NA',1,0,'C',0);

          //nilai 1
          $pdf->Ln();
          $pdf->Cell(80,7,"1. Seminar",1,0,'L',0);
          $pdf->Cell(20,7,'',1,0,'C',0);
          $pdf->Cell(30,7,'',1,0,'C',0);
          $pdf->Cell(20,7,'',1,0,'C',0);
          $kom_seminar = $this->pkl_model->get_nilai_pkl_by_id($seminar->seminar_id,"Seminar"); 
          $n = 1;
          $nilai_seminar_total = 0;
          foreach($kom_seminar as $nilai_seminar){
              $pdf->Ln();
              $pdf->Cell(80,7,"       $n. ".$nilai_seminar->attribut,1,0,'L',0);
              $pdf->Cell(20,7,$nilai_seminar->nilai,1,0,'C',0);
              $pdf->Cell(30,7,$nilai_seminar->persentase." %",1,0,'C',0);
              $n1 = $nilai_seminar->nilai * ($nilai_seminar->persentase / 100);
              $pdf->Cell(20,7,$n1,1,0,'C',0);
              $nilai_seminar_total += $n1;
              $n++;
          }

          //nilai 2
          $pdf->Ln();
          $pdf->Cell(80,7,"2. Laporan",1,0,'L',0);
          $pdf->Cell(20,7,'',1,0,'C',0);
          $pdf->Cell(30,7,'',1,0,'C',0);
          $pdf->Cell(20,7,'',1,0,'C',0);
          $kom_laporan = $this->pkl_model->get_nilai_pkl_by_id($seminar->seminar_id,"Laporan"); 
          $n = 1;
          $nilai_laporan_total = 0;
          foreach($kom_laporan as $nilai_laporan){
              $pdf->Ln();
              $pdf->Cell(80,7,"       $n. ".$nilai_laporan->attribut,1,0,'L',0);
              $pdf->Cell(20,7,$nilai_laporan->nilai,1,0,'C',0);
              $pdf->Cell(30,7,$nilai_laporan->persentase." %",1,0,'C',0);
              $n2 = $nilai_laporan->nilai * ($nilai_laporan->persentase / 100);
              $pdf->Cell(20,7,$n2,1,0,'C',0);
              $nilai_laporan_total += $n2;
              $n++;
          }

          //total
          $pdf->Ln();
          $pdf->Cell(130,10,'NILAI TOTAL',1,0,'C',0);
          $pdf->Cell(20,10,$nilai_seminar_total + $nilai_laporan_total,1,0,'C',0);
          $pdf->Ln();

          $pbb_approve = $this->pkl_model->get_nilai_approval_pkl($seminar->seminar_id,"Dosen Pembimbing");
          $pbb_data = $this->user_model->get_dosen_data($pbb_approve->id_user);
          //tanggal
          $tgl = explode("-",substr($pbb_approve->created_at,0,10));
          $bulan = $this->get_month($tgl[1]);
          $pdf->Ln(8);
          $pdf->Cell(90, $spasi,"", 0, 0, 'L');
          $pdf->Cell(150, $spasi,"Bandar Lampung, ".$tgl[2]." ".$bulan." ".$tgl[0]."", 0, 0, 'L');
          $pdf->Ln(8);

          $pdf->Cell(90, $spasi,"", 0, 0, 'L');
          $pdf->Cell(30, $spasi,"Dosen Pembimbing", 0, 0, 'L');
          $pdf->Ln(5);

            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(90, $spasi,$pdf->Image($pbb_approve->ttd,$pdf->GetX()+1, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');
        
            $pdf->Ln(26);
            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(30, $spasi,$pbb_data->gelar_depan." ".$pbb_data->name.", ".$pbb_data->gelar_belakang, 0, 0, 'L');
            $pdf->Ln(5);

            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"NIP. ".$pbb_data->nip_nik, 0, 0, 'L');
            $pdf->Ln(10);


        $pdf->Output('I','form_penilaian_kp.pdf');
    }

    function berita_acara_kp($pkl,$seminar,$jurusan)
    {
        $jurusan_upper = strtoupper($jurusan);
        $mhs = $this->ta_model->get_mahasiswa_detail($pkl->npm);

        switch($jurusan){
            case "Ilmu Komputer":
                $numPage = '';
            break;
            case "Kimia":
                $numPage = '';
            break;
            case "Fisika":
                $numPage = '';
            break;
            case "Biologi":
                $numPage = '';
            break;
            case "Matematika":
                $numPage = '';
            break;
        }

        $kode = 0;
        $type = 'Single';

        $date = strtotime($seminar->tgl_pelaksanaan);
        $date = date('l', $date);
        $hari = $this->get_day($date);

        $dates = substr("$seminar->created_at",0,10);
        $dates = explode('-',$dates);
        $bulan = $this->get_month($dates[1]);

        $pdf = new FPDF('P','mm','A4');
       
        $spasi = 6;
        $pdf->number_footer(0);
        $pdf->setting_page_footer($numPage, $kode, $type);
        $pdf->set_header_jur($jurusan_upper);
        $pdf->set_header($jurusan);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);

        $pdf->AddPage();
        $pdf->SetFont('Times','B',11);
        $pdf->Ln(5);
        $pdf->MultiCell(150, $spasi, "FORMULIR BERITA ACARA \nPENILAIAN KEGIATAN KP/PKL",1,'C',false);
        $pdf->SetFont('Times','',11);
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi, "Nomor : ".$seminar->no_form,0,'C',false);
        $pdf->Ln(5);
        $pdf->MultiCell(150, $spasi, "Pada hari ".$hari." tanggal ".$dates[2]." ".$bulan." ".$dates[0]." pukul ".$seminar->waktu_pelaksanaan." WIB, telah dilaksanakan kegiatan seminar KP/PKL sebagai evaluasi akhir pelaksanaan KP/PKL oleh mahasiswa:",0,'J',false);
        $pdf->Ln(5);
        $pdf->SetWidths(array(45,5, 150));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Nama / NPM',':',$mhs->name." / ".$mhs->npm));
        $pdf->Ln(1);
        $pdf->SetWidths(array(45,5, 120));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Judul KP/PKL',':',$seminar->judul));
        $pdf->Ln(3);

        //pembimbing
        $pbb_data = $this->user_model->get_dosen_data($pkl->pembimbing);
        $pdf->SetWidths(array(45,5, 120));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Dosen Pembimbing',':',$pbb_data->gelar_depan." ".$pbb_data->name.", ".$pbb_data->gelar_belakang));
        $pdf->Ln(1);
        //pbl 
        $pbl_data = $this->pkl_model->get_pb_lapangan($pkl->pkl_id);
        $pdf->SetWidths(array(45,5, 120));
        $pdf->SetAligns(array('L','C','L'));
        if(!empty($pbl_data)){
            $pdf->RowNoBorder(array('Pembimbing Lapangan',':',$pbl_data->nama));
        }
        else{
            $pdf->RowNoBorder(array('Pembimbing Lapangan',':',""));
        }
       
        $pdf->Ln(5);

        $pdf->Cell(40,5,'Penilai',1,0,'C',0);
        $pdf->Cell(70,5,'Nama/NIP',1,0,'C',0);
        $pdf->Cell(15,5,'Nilai',1,0,'C',0);
        $pdf->Cell(10,5,'%',1,0,'C',0);
        $pdf->Cell(15,5,'NA',1,0,'C',0);
        $pdf->Ln();
        $komponen = $this->pkl_model->get_seminar_komponen($mhs->jurusan);
        $bobot = explode("#",$komponen->bobot);
        if(!empty($pbl_data)){
            $bobot_pbb = $bobot[0];
            $bobot_pbl = $bobot[1];
        }
        else{
            $bobot_pbb = 100;
            $bobot_pbl = 0;
        }
       

        if($seminar->status >= 8){
        //dosen pbb
        $nilai_pbb = $this->pkl_model->get_pkl_nilai_status($seminar->seminar_id,$pkl->pkl_id,'Dosen Pembimbing');
        $na_pbb = $nilai_pbb->nilai * ($bobot_pbb/100);
        $pdf->SetWidths(array(40,70,15,10,15));
        $pdf->SetAligns(array('C','L','C','C','C'));
        $pdf->Row(array("\nDosen\nPembimbing","\n".$pbb_data->gelar_depan." ".$pbb_data->name.", ".$pbb_data->gelar_belakang."\nNIP.".$pbb_data->nip_nik,"\n".$nilai_pbb->nilai,"\n".$bobot_pbb,"\n".$na_pbb)); 
        }
        else{
        //nilai
        $nilai_pb = $this->pkl_model->get_nilai_seminar($seminar->seminar_id);
        $tot_nilai_pb = 0;
            foreach($nilai_pb as $nil){
                $tot_nilai_pb += $nil->nilai * ($nil->persentase/100);
            }
        $tot_nilai_pb = round($tot_nilai_pb,2);

        $na_pb = $tot_nilai_pb * ($bobot_pbb/100);
        //dosen pbb
        $pdf->SetWidths(array(40,70,15,10,15));
        $pdf->SetAligns(array('C','L','C','C','C'));
        $pdf->Row(array("\nDosen\nPembimbing","\n".$pbb_data->gelar_depan." ".$pbb_data->name.", ".$pbb_data->gelar_belakang."\nNIP.".$pbb_data->nip_nik,"\n".$tot_nilai_pb,"\n".$bobot_pbb,"\n".$na_pb)); 
        }

        if($seminar->status >= 8){
         //dosen pbl
            //pb not null
            if(!empty($pbl_data)){
            $nilai_pbl = $this->pkl_model->get_pkl_nilai_status($seminar->seminar_id,$pkl->pkl_id,'Pembimbing Lapangan');
            $na_pbl = $nilai_pbl->nilai * ($bobot_pbl/100);
            $pdf->SetWidths(array(40,70,15,10,15));
            $pdf->SetAligns(array('C','L','C','C','C'));
            $pdf->Row(array("\nPembimbing\nlapangan","\n".$pbl_data->nama."\nNIP/NIK.".$pbl_data->nip_nik,"\n".$nilai_pbl->nilai,"\n".$bobot_pbl,"\n".$na_pbl)); 
            }
            else{ //pb null
                // $nilai_pbl = $this->pkl_model->get_pkl_nilai_status($seminar->seminar_id,$pkl->pkl_id,'Pembimbing Lapangan');
                $na_pbl = 0;
                $pdf->SetWidths(array(40,70,15,10,15));
                $pdf->SetAligns(array('C','L','C','C','C'));
                $pdf->Row(array("\nPembimbing\nlapangan","\n".""."\nNIP/NIK."."","\n"."0","\n".$bobot_pbl,"\n".$na_pbl)); 
            }
        }
        else{
         //dosen pbl
         if(!empty($pbl_data)){
            $pdf->SetWidths(array(40,70,15,10,15));
            $pdf->SetAligns(array('C','L','C','C','C'));
            $pdf->Row(array("\nPembimbing\nlapangan","\n".$pbl_data->nama."\nNIP/NIK.".$pbl_data->nip_nik,"\n","\n".$bobot_pbl,"\n")); 
         }
         else{
            $pdf->SetWidths(array(40,70,15,10,15));
            $pdf->SetAligns(array('C','L','C','C','C'));
            $pdf->Row(array("\nPembimbing\nlapangan","\n".""."\nNIP/NIK."."","\n","\n".$bobot_pbl,"\n")); 
         }
        }

         if($seminar->status >= 8){
            //dosen koordinator
            $koor_approve = $this->pkl_model->get_nilai_approval_pkl($seminar->seminar_id,"Koordinator");
            $koor_data = $this->user_model->get_dosen_data($koor_approve->id_user);
            $nilai_k = $this->pkl_model->get_pkl_nilai_status($seminar->seminar_id,$pkl->pkl_id,'Koordinator')->nilai;
            $pdf->SetWidths(array(40,70,25,15));
            $pdf->SetAligns(array('C','L','C','C'));
            $pdf->Row(array("\nKoordinator","\n".$koor_data->gelar_depan." ".$koor_data->name.", ".$koor_data->gelar_belakang."\nNIP.".$koor_data->nip_nik,"\nPengurangan","\n".$nilai_k)); 

            //total Nilai
            $total_nilai = ($na_pbb + $na_pbl) - $nilai_k;
            $pdf->SetWidths(array(135,15));
            $pdf->SetAligns(array('R','C'));
            $pdf->Row(array("\nTotal Nilai ","\n".$total_nilai)); 

            //Huruf Mutu
            $total_nilai = ($na_pbb + $na_pbl) - $nilai_k;
            $pdf->SetWidths(array(135,15));
            $pdf->SetAligns(array('R','C'));
            $pdf->Row(array("\nHuruf Mutu ","\n".$this->huruf_mutu($total_nilai))); 
         }
         else{
            //dosen koordinator
            $pdf->SetWidths(array(40,70,25,15));
            $pdf->SetAligns(array('C','L','C','C'));
            $pdf->Row(array("\nKoordinator",".....\nNIP.","\nPengurangan","\n")); 

            //total Nilai
            $pdf->SetWidths(array(135,15));
            $pdf->SetAligns(array('R','C'));
            $pdf->Row(array("\nTotal Nilai ","\n -")); 

            //Huruf Mutu
            $pdf->SetWidths(array(135,15));
            $pdf->SetAligns(array('R','C'));
            $pdf->Row(array("\nHuruf Mutu ","\n -")); 
         }

          $pdf->Ln(8);
          if($seminar->status != 9){
            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(150, $spasi,"Bandar Lampung, ", 0, 0, 'L');
          }
          elseif($seminar->status == 9){
            $kajur_approve = $this->pkl_model->get_nilai_approval_pkl($seminar->seminar_id,"Ketua Jurusan");

            $tgl_ba = explode("-",substr($kajur_approve->created_at,0,10));
            $tanggal_ba = $tgl_ba[2];
            $bulan_ba = $this->get_month($tgl_ba[1]);
            $tahun_ba = $tgl_ba[0];
            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(150, $spasi,"Bandar Lampung, ".$tanggal_ba." ".$bulan_ba." ".$tahun_ba."", 0, 0, 'L');
          }
          $pdf->Ln(8);

          $pdf->Cell(90, $spasi,"Mengetahui,", 0, 0, 'L');
          $pdf->Cell(45, $spasi,"Menyetujui", 0, 0, 'L');
          $pdf->Ln(5);

          $pdf->Cell(90, $spasi,"Ketua Jurusan ".$jurusan, 0, 0, 'L');
          $pdf->Cell(30, $spasi,"Koordinator KP/PKL", 0, 0, 'L');
          $pdf->Ln(5);

          if($seminar->status < 8){
            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
        
            $pdf->Ln(26);
            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"", 0, 0, 'L');
            $pdf->Ln(5);

            $pdf->Cell(90, $spasi,"NIP.", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"NIP. ", 0, 0, 'L');
            $pdf->Ln(10);
          }
          elseif($seminar->status == 8){
            $koor_approve = $this->pkl_model->get_nilai_approval_pkl($seminar->seminar_id,"Koordinator");
            $koor_data = $this->user_model->get_dosen_data($koor_approve->id_user);

            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(90, $spasi,$pdf->Image($koor_approve->ttd,$pdf->GetX()+1, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');
        
            $pdf->Ln(26);
            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(30, $spasi,$koor_data->gelar_depan." ".$koor_data->name.", ".$koor_data->gelar_belakang, 0, 0, 'L');
            $pdf->Ln(5);

            $pdf->Cell(90, $spasi,"NIP. ", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"NIP. ".$koor_data->nip_nik, 0, 0, 'L');
            $pdf->Ln(10);
          }
          elseif($seminar->status > 8 )
          {
            $kajur_approve = $this->pkl_model->get_nilai_approval_pkl($seminar->seminar_id,"Ketua Jurusan");
            $koor_approve = $this->pkl_model->get_nilai_approval_pkl($seminar->seminar_id,"Koordinator");
            $koor_data = $this->user_model->get_dosen_data($koor_approve->id_user);
            $kajur_data = $this->user_model->get_dosen_data($kajur_approve->id_user);

            $pdf->Cell(90, $spasi,$pdf->Image($kajur_approve->ttd,$pdf->GetX()+1, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');
            $pdf->Cell(90, $spasi,$pdf->Image($koor_approve->ttd,$pdf->GetX()+1, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');
        
            $pdf->Ln(26);
            $pdf->Cell(90, $spasi,$kajur_data->gelar_depan." ".$kajur_data->name.", ".$kajur_data->gelar_belakang, 0, 0, 'L');
            $pdf->Cell(30, $spasi,$koor_data->gelar_depan." ".$koor_data->name.", ".$koor_data->gelar_belakang, 0, 0, 'L');
            $pdf->Ln(5);

            $pdf->Cell(90, $spasi,"NIP. ".$kajur_data->nip_nik, 0, 0, 'L');
            $pdf->Cell(30, $spasi,"NIP. ".$koor_data->nip_nik, 0, 0, 'L');
            $pdf->Ln(10);
          }

        $pdf->Output('I','berita_acara_kp.pdf');
    }

}

?>