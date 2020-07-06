<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Pdf_TA extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('fpdf');
        $this->load->model('user_model');
		$this->load->model('ta_model');
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
        }
       
    }

    function pengajuan_bimbingan($ta,$jurusan)
    {
 
        $jurusan_upper = strtoupper($jurusan);
        $pb = $this->ta_model->get_dosen_pb1($ta->id_pengajuan);
        $pa = $this->ta_model->get_dosen_pa_detail($ta->id_pengajuan);
        $mhs = $this->ta_model->get_mahasiswa_detail($ta->npm);

        //ttd
        if($ta->status >= 1){
            $ttd_pa = $this->ta_model->get_ttd_approval($ta->id_pengajuan,'Pembimbing Akademik');
            // $ttd_pa = $ttd_pa[0];
        }
        if($ta->status >= 2){
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
        $pdf->RowNoBorder(array('NAMA',':',$mhs->name,'NIP',':',$mhs->npm));

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
        if($ta->status <= 1 ){
            $pdf->SetWidths(array(45,5, 50, 12, 5, 50));
            $pdf->SetAligns(array('L','C','L','L','C','L'));
            $pdf->RowNoBorder(array('PEMBIMBING UTAMA',':',$pb->name,'NIP',':',$pb->nip_nik));
            $pdf->Ln(8);   
            $pdf->Cell(45, $spasi,"TTD PEMBIMBING", 0, 0, 'L');
            $pdf->Cell(5, $spasi,':', 0, 0, 'C');
            $pdf->Cell(50, $spasi,"", 0, 0, 'L');
            $pdf->Ln(30);
        }
        elseif($ta->status == 2){
            $pdf->SetWidths(array(45,5, 50, 12, 5, 50));
            $pdf->SetAligns(array('L','C','L','L','C','L'));
            $pdf->RowNoBorder(array('PEMBIMBING UTAMA',':',$pb->name,'NIP',':',$pb->nip_nik));
            $pdf->Ln(8);   
            $pdf->Cell(45, $spasi,"TTD PEMBIMBING", 0, 0, 'L');
            $pdf->Cell(5, $spasi,':', 0, 0, 'C');
            $pdf->Cell(50, $spasi,$pdf->Image("$ttd_pb1->ttd",$pdf->GetX(), $pdf->GetY()-3,40,0,'PNG'), 0, 0, 'L');
            $pdf->Ln(30);
        }

        $pdf->Cell(150, $spasi,"Bandar Lampung, ".$tanggal." ".$bulan." ".$tahun."", 0, 0, 'R');
        $pdf->Ln(8);       
        
        $pdf->Cell(45, $spasi,"Mengetahui", 0, 0, 'L');
        $pdf->Ln(5);
        $pdf->Cell(90, $spasi,"Dosen Pembimbing Akademik,", 0, 0, 'L');
        $pdf->Cell(30, $spasi,"Pengusul,", 0, 0, 'L');
        $pdf->Ln(5);
        

        //ttd
        if($ta->status == "0"){
            $pdf->Cell(90, $spasi,$pdf->Image($blank_image,$pdf->GetX(), $pdf->GetY(),60,0,'PNG'), 0, 0, 'L'); 
            $pdf->Cell(30, $spasi,$pdf->Image("$ta->ttd",$pdf->GetX()-3, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');
        }
        elseif($ta->status >= 1){
            $pdf->Cell(90, $spasi,$pdf->Image($ttd_pa->ttd,$pdf->GetX()+1, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L'); 
            $pdf->Cell(30, $spasi,$pdf->Image("$ta->ttd",$pdf->GetX()-3, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');
        }

        // $pdf->Image("$ta->ttd",80,200,30,0,'PNG');
        $pdf->Ln(26);
        $pdf->Cell(90, $spasi,$pa->name, 0, 0, 'L');
        $pdf->Cell(30, $spasi,$mhs->name, 0, 0, 'L');
        $pdf->Ln(5);

        $pdf->Cell(90, $spasi,"NIP. ".$pa->nip_nik, 0, 0, 'L');
        $pdf->Cell(30, $spasi,"NPM. ".$mhs->npm, 0, 0, 'L');
        $pdf->Ln(10);
        
        $pdf->Output();
    }

    function form_verifikasi($ta,$jurusan)
    {
        
        $surat = $this->ta_model->get_surat($ta->id_pengajuan);
        $mhs= $this->ta_model->get_mahasiswa_detail($ta->npm);
        $admin = $this->ta_model->get_admin_detail($ta->id_pengajuan);

        if($ta->status == 7 || $ta->status == 4){
            $koor_approve  = $this->ta_model->get_ttd_approval($ta->id_pengajuan,'Koordinator');
            $koor_data = $this->user_model->get_dosen_data($koor_approve->id_user);
        }
        
        $b =  substr("$surat->created_at",0,10);
        $a = explode('-',$b);
        $tgl = $a[2].'-'.$a[1].'-'.$a[0];
        $c = substr("$surat->updated_at",0,10);
        $d = explode('-',$c);
        $e =  $this->get_month($d[1]);
        $tgl_acc = $d[2].' '.$e.' '.$d[0];

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
            $pdf->Ln(18);

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
            if($jurusan == "Matematika"){
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
                $pdf->Cell(5, $spasi,':'.$tgl, 0, 0, 'C');
                $pdf->Ln(18);
                
                $pdf->Cell(45, $spasi,"Kelengkapan Persyaratan", 0, 0, 'L');
                $pdf->Cell(5, $spasi,':', 0, 0, 'C');
                $pdf->Ln(8);
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Transkrip Akademik (1 lembar) yang telah ditandatangani oleh Wakil Dekan Bidang Akademik dan Kerjasama dan telah diberi cap stempel fakultas, dengan ketentuan telah lulus semua mata kuliah wajib dan pilihan yang mendukung topik tesis",0,'J',false);
                $pdf->Ln(1);
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Telah menyelesaikan minimal  seluruh mata kuliah di semester ke-1 (11 SKS) dengan IPK > 3,00, dan atau sedang mengambil seluruh mata kuliah di semester ke-2 (9-12 SKS) ",0,'J',false);
                $pdf->Ln(1);
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
            }
        }

        $pdf->Cell(90, $spasi,"", 0, 0, 'L');
        $pdf->Cell(30, $spasi,"Bandar Lampung, ".$tgl_acc, 0, 0, 'L');
        $pdf->Ln(7);

        if($ta->status < 7 && $ta->status != 4 ){
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
            $pdf->Cell(30, $spasi,$admin->name, 0, 0, 'L');
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
            $pdf->Cell(90, $spasi,$koor_data->name, 0, 0, 'L');
            $pdf->Cell(30, $spasi,$admin->name, 0, 0, 'L');
            $pdf->Ln(5);

            $pdf->Cell(90, $spasi,"NIP. ".$koor_data->nip_nik, 0, 0, 'L');
            $pdf->Cell(30, $spasi,"NIP. ".$admin->nip_nik, 0, 0, 'L');
            $pdf->Ln(10);

        }
        

        $pdf->Output();
    }

    function form_penetapan($ta,$jurusan)
    {

        $mhs = $this->ta_model->get_mahasiswa_detail($ta->npm);
        $komisi = $this->ta_model->get_komisi($ta->id_pengajuan);

        $tgl_acc = $this->ta_model->get_tgl_acc($ta->id_pengajuan);

        if($ta->status != 7){
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
            $pdf->page_type('undangan');
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
            $pdf->Ln(8);

            $pdf->Cell(45, $spasi,"Dan Menetapkan", 0,0, 'L');
            $pdf->Ln(8);
            $pdf->SetWidths(array(45,5, 60, 12, 5, 50));
            $pdf->SetAligns(array('L','C','L','L','C','L'));

            //komisi pembimbing & penguji
            foreach($komisi as $kom){
                $pdf->RowNoBorder(array(strtoupper($kom->status),':',$kom->nama,'NIP',':',$kom->nip_nik));
                $pdf->Ln(1);
                $pdf->Cell(45, $spasi,"TANDA TANGAN", 0, 0, 'L');
                $pdf->Cell(5, $spasi,':', 0, 0, 'C');

                $image = $kom->ttd;
                if($image == NULL){
                    $image = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVQYV2NgYAAAAAMAAWgmWQ0AAAAASUVORK5CYII=";
                }

                $pdf->Cell(50, $spasi,$pdf->Image("$image",$pdf->GetX(), $pdf->GetY(),33,0,'PNG'), 0, 0, 'L');
                $pdf->Ln(20);
            }


            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"Bandar Lampung, ".$tgl_acc, 0, 0, 'L');
            $pdf->Ln(5);

            $pdf->Cell(45, $spasi,"Menyetujui", 0, 0, 'L');
            $pdf->Ln(5);
            $pdf->Cell(90, $spasi,"Ketua Jurusan,", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"Koordinator ", 0, 0, 'L');
            $pdf->Ln(5);

            if($ta->status != 7){
                $pdf->Cell(90, $spasi,$pdf->Image("$kajur_approve->ttd",$pdf->GetX(), $pdf->GetY(),33,0,'PNG'), 0, 0, 'L');
            }
            else{
                $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            }

            $pdf->Cell(30, $spasi,$pdf->Image("$koor_approve->ttd",$pdf->GetX(), $pdf->GetY(),33,0,'PNG'), 0, 0, 'L');
            $pdf->Ln(20);

            if($ta->status != 7){
                $pdf->Cell(90, $spasi,$kajur_data->name, 0, 0, 'L');
                $pdf->Cell(30, $spasi,$koor_data->name, 0, 0, 'L');
                $pdf->Ln(5);
                $pdf->Cell(90, $spasi,"NIP. ".$kajur_data->nip_nik, 0, 0, 'L');
                $pdf->Cell(30, $spasi,"NIP. ".$koor_data->nip_nik, 0, 0, 'L');
                $pdf->Ln(10);
            }
            else{
                $pdf->Cell(90, $spasi,"", 0, 0, 'L');
                $pdf->Cell(30, $spasi,$koor_data->name, 0, 0, 'L');
                $pdf->Ln(5);
                $pdf->Cell(90, $spasi,"NIP. ", 0, 0, 'L');
                $pdf->Cell(30, $spasi,"NIP. ".$koor_data->nip_nik, 0, 0, 'L');
                $pdf->Ln(10);
            }

           


            $pdf->Output();
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
        // $ttd_pa = $ttd_pa[0];
        }
        if($seminar->status >= 2){
        $ttd_pb1 = $this->ta_model->get_ttd_approval_seminar($seminar->id,'Pembimbing Utama');
        // $ttd_pb1 = $ttd_pb1[0];
        }
        if($seminar->status == 4){
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
        $pdf->Ln(8);

        //komisi
        foreach($komisi as $kom){
            $pdf->SetWidths(array(45,5, 60, 12, 5, 50));
            $pdf->SetAligns(array('L','C','L','L','C','L'));
            $pdf->RowNoBorder(array($kom->status_slug,':',$kom->nama,'NIP',':',$kom->nip_nik));
            $pdf->Ln(4);
        }

        $pdf->Ln(7);
        $pdf->Cell(150, $spasi,"Bandar Lampung, ".$date[2]." ".$bulan." ".$date[0]."", 0, 0, 'R');
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
        $pdf->Cell(90, $spasi,$pa->name, 0, 0, 'L');
        $pdf->Cell(30, $spasi,$pb->name, 0, 0, 'L');
        $pdf->Ln(5);

        $pdf->Cell(90, $spasi,"NIP. ".$pa->nip_nik, 0, 0, 'L');
        $pdf->Cell(30, $spasi,"NIP. ".$pb->nip_nik, 0, 0, 'L');
        $pdf->Ln(20);

        if($seminar->status != 4){
            $pdf->Cell(150, $spasi,"Menyetujui", 0, 0, 'C');
            $pdf->Ln(5);
            $pdf->Cell(150, $spasi,"Ketua Jurusan ".$kajur->nama, 0, 0, 'C');
            $pdf->Ln(30);

            $pdf->Cell(150, $spasi,$kajur->name, 0, 0, 'C');
            $pdf->Ln(5);
            $pdf->Cell(150, $spasi,"NIP. ".$kajur->nip_nik, 0, 0, 'C');
        }
        else{
            $pdf->Cell(150, $spasi,"Menyetujui", 0, 0, 'C');
            $pdf->Ln(5);
            $pdf->Cell(150, $spasi,"Ketua Jurusan ".$jurusan, 0, 0, 'C');
            $pdf->Ln(5);

            $pdf->Cell(150, $spasi,$pdf->Image("$kajur_approve->ttd",$pdf->GetX()+55, $pdf->GetY(),33,0,'PNG'), 0, 0, 'C');
            $pdf->Ln(25);
            $pdf->Cell(150, $spasi,$kajur_data->name, 0, 0, 'C');
            $pdf->Ln(5);
            $pdf->Cell(150, $spasi,"NIP. ".$kajur_data->nip_nik, 0, 0, 'C');
        }

        $pdf->Output();

    }

    function verifikasi_seminar($seminar,$jurusan,$ta_seminar)
    {
        $surat = $this->ta_model->get_surat_seminar($seminar->id);
        $mhs= $this->ta_model->get_mahasiswa_detail($ta_seminar->npm);
        $admin = $this->ta_model->get_admin_detail($ta_seminar->id_pengajuan);
        $b =  substr("$surat->created_at",0,10);
        $a = explode('-',$b);
        $tgl = $a[2].'-'.$a[1].'-'.$a[0];
        $c = substr("$surat->updated_at",0,10);
        $d = explode('-',$c);
        $e =  $this->get_month($d[1]);
        $tgl_acc = $d[2].' '.$e.' '.$d[0];

        if($seminar->status == 7 || $seminar->status == 4){
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

        $pdf->AddPage('P');
        $pdf->SetFont('Times','B',11);
        $pdf->MultiCell(150, $spasi, "FORM VERIFIKASI BERKAS PERSYARATAN\nPENGAJUAN ".strtoupper($seminar->jenis)."",1,'C',false); 
        $pdf->SetFont('Times','',11);
        $pdf->Ln(3);
        $pdf->Cell(150, $spasi,"NO:".$seminar->no_form, 0, 0, 'C');
        $pdf->Ln(6);

        $pdf->SetWidths(array(40,5, 60));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Nama Mahasiswa',':',$mhs->name));
        $pdf->Ln(2);

        $pdf->SetWidths(array(40,5, 60));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('NPM',':',$mhs->npm));
        $pdf->Ln(2);

        $pdf->SetWidths(array(40,5, 100));
        $pdf->SetAligns(array('L','C','L'));
        if($ta_seminar->judul_approve == 1){
            $pdf->RowNoBorder(array('Judul',':',$ta_seminar->judul1));
        }
        elseif($ta_seminar->judul_approve == 2){
            $pdf->RowNoBorder(array('Judul',':',$ta_seminar->judul2));
        }
        $pdf->Ln(2);

        $pdf->SetWidths(array(40,5, 100));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Fakultas / Jurusan',':','Matematika dan Ilmu Pengetahuan Alam / '.$jurusan));
        $pdf->Ln(2);

        $pdf->SetWidths(array(40,5, 100));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Tanggal Masuk Berkas',':',$tgl));
        $pdf->Ln(2);

        $pdf->Cell(50, $spasi,"Waktu Pelaksanaan Seminar", 0, 0, 'L');
        $pdf->Cell(55, $spasi,":", 0, 0, 'L');
        $pdf->Ln(8);
        $tgls = explode("-",$seminar->tgl_pelaksanaan);
        $pdf->SetWidths(array(40,5, 30));
        $pdf->SetAligns(array('R','C','L'));
        $pdf->RowNoBorder(array('Tanggal Seminar',':',$tgls[2].'-'.$tgls[1].'-'.$tgls[0]));
        $pdf->Ln(2);

        $pdf->SetWidths(array(40,5, 100));
        $pdf->SetAligns(array('R','C','L'));
        $pdf->RowNoBorder(array('Waktu & Ruang',':',$seminar->waktu_pelaksanaan.' WIB / '.$seminar->tempat));
        $pdf->Ln(2);

        $pdf->Cell(40, $spasi,"Kelengkapan Persyaratan", 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Ln(5);

        // seminar usul
        if($seminar->jenis == "Seminar Usul"){
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
            $pdf->MultiCell(150, $spasi, "Transkrip Akademik (1 lembar) yang telah di tandatangani oleh Wakil Dekan 1 dan telah diberi cap stempel fakultas, dengan ketentuan telah lulus semua mata kuliah wajib dan pilihan yang mendukung topik skripsi.",0,'J',false);
            $pdf->Ln(1);

            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->Cell(83, $spasi,"Telah menyelesaikan minimal 110 SKS, dengan IPK ", 0, 0, 'L');
                $pdf->SetFont("Symbol");
                $pdf->Cell(5, $spasi,chr(179)." 2,00", 0, 2, 'L');
                $pdf->SetFont('Times','',11);
                $pdf->Ln(1);
            $pdf->Ln(1);

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

        elseif($seminar->jenis == "Seminar Hasil"){
            $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
            $pdf->MultiCell(150, $spasi, "Mengisi formulir pengajuan ".$seminar->jenis,0,'J',false);
            $pdf->Ln(1);
           // KTM KIMIA
            if($jurusan == "Kimia"){
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Terdaftar sebagai mahasiswa, yang dibuktikan dengan fotocopy KTM 1 lembar.",0,'J',false);
                $pdf->Ln(1);
                
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Telah lulus semua mata kuliah wajib dan pilihan yang mendukung topik skripsi, dengan jumlah ≥ 120 SKS, dengan IPK ≥ 2,00.",0,'J',false);
                $pdf->Ln(1);
                
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Menyerahkan 1 lembar Transkrip Akademik yang telah ditandatangani oleh Wakil Dekan 1 dan telah diberi cap fakultas.",0,'J',false);
                $pdf->Ln(1);
            }
                
            if($jurusan != "Kimia"){
                $pdf->Cell(5, $spasi,$bullet, 0, 0, 'L');
                $pdf->MultiCell(150, $spasi, "Transkrip Akademik (1 lembar) yang telah di tandatangani oleh Wakil Dekan 1 dan telah diberi cap stempel fakultas, dengan ketentuan telah lulus semua mata kuliah wajib dan pilihan yang mendukung topik skripsi, minimal 110 SKS, dengan IPK > 2,00.",0,'J',false);
                $pdf->Ln(1);
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
        
        $pdf->Cell(90, $spasi,"", 0, 0, 'L');
        $pdf->Cell(30, $spasi,"Bandar Lampung, ".$tgl_acc, 0, 0, 'L');
        $pdf->Ln(2);

        if($seminar->status < 7 && $seminar->status != 4 ){
            $pdf->Cell(45, $spasi,"Mengetahui", 0, 0, 'L');
            $pdf->Ln(5);

            $pdf->Cell(90, $spasi,"Koordinator Seminar,", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"Administrasi,", 0, 0, 'L');
            $pdf->Ln(5);

            //ttd
            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(30, $spasi,$pdf->Image("$admin->ttd",$pdf->GetX()-3, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');

            $pdf->Ln(25);
            $pdf->Cell(90, $spasi,'', 0, 0, 'L');
            $pdf->Cell(30, $spasi,$admin->name, 0, 0, 'L');
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
            $pdf->Cell(90, $spasi,$koor_data->name, 0, 0, 'L');
            $pdf->Cell(30, $spasi,$admin->name, 0, 0, 'L');
            $pdf->Ln(5);

            $pdf->Cell(90, $spasi,"NIP. ".$koor_data->nip_nik, 0, 0, 'L');
            $pdf->Cell(30, $spasi,"NIP. ".$admin->nip_nik, 0, 0, 'L');
            $pdf->Ln(10);
        }

        $pdf->Output();
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
            $pdf->Cell(150, $spasi,"Bapak/Ibu/Sdr/i ".$kom->nama, 0, 0, 'L');
            $pdf->Ln(8);

            $pdf->Cell(150, $spasi,"Di Tempat", 0, 0, 'L');
            $pdf->Ln(15);
            $pdf->Cell(150, $spasi,"Dengan Hormat,", 0, 0, 'L');
            $pdf->Ln(8);
            $pdf->MultiCell(150, $spasi, 'Bersama ini kami mengundang Bapak/Ibu/Sdr/i, untuk menghadiri '.$seminar->jenis.' penelitian oleh mahasiswa berikut sebagai',0,'J',false);
            $pdf->Ln(4);
            
            $pdf->SetFont('Times','B',11);
            $pdf->Cell(150, $spasi,"$kom->status_slug", 0, 0, 'L');
            $pdf->Ln(10);
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
                $pdf->Cell(30, $spasi,"Koordinator ".$seminar->jenis, 0, 0, 'L');
                $pdf->Ln(30);

                $pdf->Cell(90, $spasi,'', 0, 0, 'L');
                $pdf->Cell(30, $spasi,'', 0, 0, 'L');
                $pdf->Ln(5);
                $pdf->Cell(90, $spasi,"NIP. ", 0, 0, 'L');
                $pdf->Cell(30, $spasi,"NIP. ", 0, 0, 'L');
                $pdf->Ln(5);
            }

            elseif($seminar->status == 7){
                $pdf->Cell(90, $spasi,"Ketua Jurusan ".$jurusan, 0, 0, 'L');
                $pdf->Cell(30, $spasi,"Koordinator ".$seminar->jenis, 0, 0, 'L');
                $pdf->Ln(5);

                //ttd
                $pdf->Cell(90, $spasi,"", 0, 0, 'L');
                $pdf->Cell(30, $spasi,$pdf->Image("$koor_approve->ttd",$pdf->GetX(), $pdf->GetY(),33,0,'PNG'), 0, 0, 'L');

                $pdf->Ln(25);
                $pdf->Cell(90, $spasi,'', 0, 0, 'L');
                $pdf->Cell(30, $spasi,$koor_data->name, 0, 0, 'L');
                $pdf->Ln(5);
                $pdf->Cell(90, $spasi,"NIP. ", 0, 0, 'L');
                $pdf->Cell(30, $spasi,"NIP. ".$koor_data->nip_nik, 0, 0, 'L');
                $pdf->Ln(5);
            }

            elseif($seminar->status == 4){
                $pdf->Cell(90, $spasi,"Ketua Jurusan ".$jurusan, 0, 0, 'L');
                $pdf->Cell(30, $spasi,"Koordinator ".$seminar->jenis, 0, 0, 'L');
                $pdf->Ln(5);

                //ttd
                $pdf->Cell(90, $spasi,$pdf->Image("$kajur_approve->ttd",$pdf->GetX(), $pdf->GetY(),33,0,'PNG'), 0, 0, 'L');
                $pdf->Cell(30, $spasi,$pdf->Image("$koor_approve->ttd",$pdf->GetX(), $pdf->GetY(),33,0,'PNG'), 0, 0, 'L');

                $pdf->Ln(25);
                $pdf->Cell(90, $spasi,$kajur_data->name, 0, 0, 'L');
                $pdf->Cell(30, $spasi,$koor_data->name, 0, 0, 'L');
                $pdf->Ln(5);
                $pdf->Cell(90, $spasi,"NIP. ".$kajur_data->nip_nik, 0, 0, 'L');
                $pdf->Cell(30, $spasi,"NIP. ".$koor_data->nip_nik, 0, 0, 'L');
                $pdf->Ln(5);
            }


        }
        
        $pdf->Output();

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
            $pdf->Cell(150, $spasi,"Bapak/Ibu/Sdr/i ".$kom->nama, 0, 0, 'L');
            $pdf->Ln(8);

            $pdf->Cell(150, $spasi,"Di Tempat", 0, 0, 'L');
            $pdf->Ln(15);
            $pdf->Cell(150, $spasi,"Dengan Hormat,", 0, 0, 'L');
            $pdf->Ln(8);
            $pdf->MultiCell(150, $spasi, 'Bersama ini kami mengundang Bapak/Ibu/Sdr/i, untuk menghadiri '.$seminar->jenis.' penelitian oleh mahasiswa berikut sebagai',0,'J',false);
            $pdf->Ln(4);
            
            $pdf->SetFont('Times','B',11);
            $pdf->Cell(150, $spasi,"$kom->status_slug", 0, 0, 'L');
            $pdf->Ln(10);
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
                $pdf->Cell(30, $spasi,"Koordinator ".$seminar->jenis, 0, 0, 'L');
                $pdf->Ln(30);

                $pdf->Cell(90, $spasi,'', 0, 0, 'L');
                $pdf->Cell(30, $spasi,'', 0, 0, 'L');
                $pdf->Ln(5);
                $pdf->Cell(90, $spasi,"NIP. ", 0, 0, 'L');
                $pdf->Cell(30, $spasi,"NIP. ", 0, 0, 'L');
                $pdf->Ln(5);
            }

            elseif($seminar->status == 7){
                $pdf->Cell(90, $spasi,"Ketua Jurusan ".$jurusan, 0, 0, 'L');
                $pdf->Cell(30, $spasi,"Koordinator ".$seminar->jenis, 0, 0, 'L');
                $pdf->Ln(5);

                //ttd
                $pdf->Cell(90, $spasi,"", 0, 0, 'L');
                $pdf->Cell(30, $spasi,$pdf->Image("$koor_approve->ttd",$pdf->GetX(), $pdf->GetY(),33,0,'PNG'), 0, 0, 'L');

                $pdf->Ln(25);
                $pdf->Cell(90, $spasi,'', 0, 0, 'L');
                $pdf->Cell(30, $spasi,$koor_data->name, 0, 0, 'L');
                $pdf->Ln(5);
                $pdf->Cell(90, $spasi,"NIP. ", 0, 0, 'L');
                $pdf->Cell(30, $spasi,"NIP. ".$koor_data->nip_nik, 0, 0, 'L');
                $pdf->Ln(5);
            }

            elseif($seminar->status == 4){
                $pdf->Cell(90, $spasi,"Ketua Jurusan ".$jurusan, 0, 0, 'L');
                $pdf->Cell(30, $spasi,"Koordinator ".$seminar->jenis, 0, 0, 'L');
                $pdf->Ln(5);

                //ttd
                $pdf->Cell(90, $spasi,$pdf->Image("$kajur_approve->ttd",$pdf->GetX(), $pdf->GetY(),33,0,'PNG'), 0, 0, 'L');
                $pdf->Cell(30, $spasi,$pdf->Image("$koor_approve->ttd",$pdf->GetX(), $pdf->GetY(),33,0,'PNG'), 0, 0, 'L');

                $pdf->Ln(25);
                $pdf->Cell(90, $spasi,$kajur_data->name, 0, 0, 'L');
                $pdf->Cell(30, $spasi,$koor_data->name, 0, 0, 'L');
                $pdf->Ln(5);
                $pdf->Cell(90, $spasi,"NIP. ".$kajur_data->nip_nik, 0, 0, 'L');
                $pdf->Cell(30, $spasi,"NIP. ".$koor_data->nip_nik, 0, 0, 'L');
                $pdf->Ln(5);
            }

        
        $pdf->Output();
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
            $pdf->Cell(150, $spasi,"Bapak/Ibu/Sdr/i ".$kom->nama, 0, 0, 'L');
            $pdf->Ln(8);

            $pdf->Cell(150, $spasi,"Di Tempat", 0, 0, 'L');
            $pdf->Ln(15);
            $pdf->Cell(150, $spasi,"Dengan Hormat,", 0, 0, 'L');
            $pdf->Ln(8);
            $pdf->MultiCell(150, $spasi, 'Bersama ini kami mengundang Bapak/Ibu/Sdr/i, untuk menghadiri '.$seminar->jenis.' penelitian oleh mahasiswa berikut sebagai',0,'J',false);
            $pdf->Ln(4);
            
            $pdf->SetFont('Times','B',11);
            $pdf->Cell(150, $spasi,"$kom->status_slug", 0, 0, 'L');
            $pdf->Ln(10);
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
                $pdf->Cell(30, $spasi,"Koordinator ".$seminar->jenis, 0, 0, 'L');
                $pdf->Ln(30);

                $pdf->Cell(90, $spasi,'', 0, 0, 'L');
                $pdf->Cell(30, $spasi,'', 0, 0, 'L');
                $pdf->Ln(5);
                $pdf->Cell(90, $spasi,"NIP. ", 0, 0, 'L');
                $pdf->Cell(30, $spasi,"NIP. ", 0, 0, 'L');
                $pdf->Ln(5);
            }

            elseif($seminar->status == 7){
                $pdf->Cell(90, $spasi,"Ketua Jurusan ".$jurusan, 0, 0, 'L');
                $pdf->Cell(30, $spasi,"Koordinator ".$seminar->jenis, 0, 0, 'L');
                $pdf->Ln(5);

                //ttd
                $pdf->Cell(90, $spasi,"", 0, 0, 'L');
                $pdf->Cell(30, $spasi,$pdf->Image("$koor_approve->ttd",$pdf->GetX(), $pdf->GetY(),33,0,'PNG'), 0, 0, 'L');

                $pdf->Ln(25);
                $pdf->Cell(90, $spasi,'', 0, 0, 'L');
                $pdf->Cell(30, $spasi,$koor_data->name, 0, 0, 'L');
                $pdf->Ln(5);
                $pdf->Cell(90, $spasi,"NIP. ", 0, 0, 'L');
                $pdf->Cell(30, $spasi,"NIP. ".$koor_data->nip_nik, 0, 0, 'L');
                $pdf->Ln(5);
            }

            elseif($seminar->status == 4){
                $pdf->Cell(90, $spasi,"Ketua Jurusan ".$jurusan, 0, 0, 'L');
                $pdf->Cell(30, $spasi,"Koordinator ".$seminar->jenis, 0, 0, 'L');
                $pdf->Ln(5);

                //ttd
                $pdf->Cell(90, $spasi,$pdf->Image("$kajur_approve->ttd",$pdf->GetX(), $pdf->GetY(),33,0,'PNG'), 0, 0, 'L');
                $pdf->Cell(30, $spasi,$pdf->Image("$koor_approve->ttd",$pdf->GetX(), $pdf->GetY(),33,0,'PNG'), 0, 0, 'L');

                $pdf->Ln(25);
                $pdf->Cell(90, $spasi,$kajur_data->name, 0, 0, 'L');
                $pdf->Cell(30, $spasi,$koor_data->name, 0, 0, 'L');
                $pdf->Ln(5);
                $pdf->Cell(90, $spasi,"NIP. ".$kajur_data->nip_nik, 0, 0, 'L');
                $pdf->Cell(30, $spasi,"NIP. ".$koor_data->nip_nik, 0, 0, 'L');
                $pdf->Ln(5);
            }

        
        $pdf->Output();
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
            $pdf->Cell(20,10,'Nlai',1,0,'C',0);
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

            $pdf->Ln(25);
            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"$kom_data->nama", 0, 0, 'L');
            $pdf->Ln(5);
            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"NIP. ".$kom_data->nip_nik, 0, 0, 'L');
            $pdf->Ln(5);

        }

            $pdf->Output();
    }

    function berita_acara($seminar,$jurusan,$ta_seminar)
    {

    }
}


?>