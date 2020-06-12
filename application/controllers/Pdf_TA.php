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
        
        if($jenis == 'pengajuan_bimbingan'){
             $this->pengajuan_bimbingan($ta,$jurusan);
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
        $pdf->RowNoBorder(array('NAMA',':','RBN','NIP',':','16'));

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

        $pdf->SetWidths(array(45,5, 50, 12, 5, 50));
        $pdf->SetAligns(array('L','C','L','L','C','L'));
        $pdf->RowNoBorder(array('PEMBIMBING UTAMA',':',$pb->name,'NIP',':',$pb->nip_nik));
        $pdf->Ln(8);   
        $pdf->Cell(45, $spasi,"TTD PEMBIMBING", 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(50, $spasi,"", 0, 0, 'L');
        $pdf->Ln(30);

        $pdf->Cell(150, $spasi,"Bandar Lampung, ".$tanggal." ".$bulan." ".$tahun."", 0, 0, 'R');
        $pdf->Ln(8);

        $pdf->Cell(45, $spasi,"Mengetahui", 0, 0, 'L');
        $pdf->Ln(5);
        $pdf->Cell(90, $spasi,"Dosen Pembimbing Akademik,", 0, 0, 'L');
        $pdf->Cell(30, $spasi,"Pengusul,", 0, 0, 'L');
        $pdf->Ln(30);

        $pdf->Cell(90, $spasi,$pa->name, 0, 0, 'L');
        $pdf->Cell(30, $spasi,$mhs->name, 0, 0, 'L');
        $pdf->Ln(5);

        $pdf->Cell(90, $spasi,"NIP. ".$pa->nip_nik, 0, 0, 'L');
        $pdf->Cell(30, $spasi,"NPM. ".$mhs->npm, 0, 0, 'L');
        $pdf->Ln(10);


        $pdf->Output();
    }

    function print(){
        $this->load->view('pdf/test');
    }
}


?>