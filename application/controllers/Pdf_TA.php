<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Pdf_TA extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('fpdf');
        $this->load->model('user_model');
		$this->load->model('ta_model');
    }

    function get_month($month){
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
    
    function set_pdf(){
        $id = $this->input->get('id');
        $jenis = $this->input->get('jenis');
        $ta = $this->ta_model->get_ta_by_id($id);
        $pembimbing = $this->ta_model->get_pembimbing_ta($id);
        $penguji = $this->ta_model->get_penguji_ta($id);

        $npm = $ta->npm;
        // echo $pb->name;
        // echo "<pre>";
        // print_r($pb1);
        
        
        $jurusan = $this->ta_model->get_jurusan($npm);
        

        switch($jenis)
        {
            case "pengajuan_bimbingan":
                $this->pengajuan_bimbingan($ta,$jurusan);
                break;
            case "form_verifikasi":
                $this->form_verifikasi($ta,$jurusan);
                break;
        }
       
    }

    function pengajuan_bimbingan($ta,$jurusan){
 
        $jurusan_upper = strtoupper($jurusan);
        $pb_data = $this->ta_model->get_dosen_pb1($ta->id_pengajuan);
        $pa_data = $this->ta_model->get_dosen_pa_detail($ta->id_pengajuan);
        $mhs_data = $this->ta_model->get_mahasiswa_detail($ta->npm);
        $pb = $pb_data[0];
        $pa = $pa_data[0];
        $mhs = $mhs_data[0];

        //ttd
        $ttd_pa = $this->ta_model->get_ttd_approval($ta->id_pengajuan,'Pembimbing Akademik');
        $ttd_pa = $ttd_pa[0];
        $ttd_pb1 = $this->ta_model->get_ttd_approval($ta->id_pengajuan,'Pembimbing Utama');
        $ttd_pb1 = $ttd_pb1[0];

        $blank_image = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVQYV2NgYAAAAAMAAWgmWQ0AAAAASUVORK5CYII=";

        $tanggal = date("d");
        $bulan = $this->get_month(date("F"));
        $tahun = date("Y");

        $pdf = new FPDF('P','mm','A4');
        $numPage = '';
        $kode = '';
        $spasi = 6;
        $pdf->number_footer(0);
        $pdf->setting_page_footer($numPage, $kode);
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
            $pdf->Cell(50, $spasi,$pdf->Image("$ta->ttd",$pdf->GetX()+5, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');
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

    function form_verifikasi($ta,$jurusan){
        
        $surat1 = $this->ta_model->get_surat($ta->id_pengajuan);
        $mhs_data = $this->ta_model->get_mahasiswa_detail($ta->npm);
        $admin_data = $this->ta_model->get_admin_detail($ta->id_pengajuan);
        $mhs = $mhs_data[0];
        $admin = $admin_data[0];
        $surat = $surat1[0];
        $b =  substr("$surat->created_at",0,10);
        $a = explode('-',$b);
        $tgl = $a[2].'-'.$a[1].'-'.$a[0];
        $c = substr("$surat->updated_at",0,10);
        $d = explode('-',$c);
        $e =  $this->get_month($d[1]);
        $tgl_acc = $d[2].' '.$e.' '.$d[0];

        $pdf = new FPDF('P','mm','A4');
        $numPage = '';
        $kode = '';
        $spasi = 6;
        $bullet = chr(149);

        $jurusan_upper = strtoupper($jurusan);
        $pdf->number_footer(0);
        $pdf->setting_page_footer($numPage, $kode);
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
                $pdf->MultiCell(150, $spasi, "Telah menyelesaikan minimal 110 SKS, dengan IPK > 2,00",0,'J',false);
                $pdf->Ln(1);
            }
            else{
                $pdf->MultiCell(150, $spasi, "Telah menyelesaikan minimal 100 SKS, dengan IPK > 2,00",0,'J',false);
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

            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"Bandar Lampung, ".$tgl_acc, 0, 0, 'L');
            $pdf->Ln(7);

            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"Mengetahui", 0, 0, 'L');
            $pdf->Ln(5);

            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"Administrasi,", 0, 0, 'L');
            $pdf->Ln(5);

            //ttd
            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(30, $spasi,$pdf->Image("$admin->ttd",$pdf->GetX()-3, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');

            $pdf->Ln(26);
            $pdf->Cell(90, $spasi,'', 0, 0, 'L');
            $pdf->Cell(30, $spasi,$admin->name, 0, 0, 'L');
            $pdf->Ln(5);

            $pdf->Cell(90, $spasi,"", 0, 0, 'L');
            $pdf->Cell(30, $spasi,"NIP. ".$admin->nip_nik, 0, 0, 'L');
            $pdf->Ln(10);
            

            $pdf->Output();
        }
    }
}


?>