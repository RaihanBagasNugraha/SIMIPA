<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Layanan extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('fpdf');
        $this->load->model('user_model');
        $this->load->model('ta_model');
        $this->load->model('pkl_model');
        $this->load->model('layanan_model');
        $this->load->model('wilayah_model');
    }

    function convert_date($input)
    {
        $date = explode("-", substr($input,0,10));
        $d = (int) $date[2];
        $m = "";
        $y = $date[0];
        switch($date[1])
        {
            case '01': $m = "Januari"; break;
            case '02': $m = "Februari"; break;
            case '03': $m = "Maret"; break;
            case '04': $m = "April"; break;
            case '05': $m = "Mei"; break;
            case '06': $m = "Juni"; break;
            case '07': $m = "Juli"; break;
            case '08': $m = "Agustus"; break;
            case '09': $m = "September"; break;
            case '10': $m = "Oktober"; break;
            case '11': $m = "November"; break;
            case '12': $m = "Desember"; break;
        }
        
        return $d." ".$m." ".$y;
    }

    function get_prodi_from_npm($npm)
    {
        // 0517032014
        $y = substr($npm, 0, 2);
        $y_now = date('y');
        $interval = $y_now - $y;
        if(date('m') == 1)
        {
            $sem = $interval * 2 - 1;
        }
        elseif(date('m') > 1 && date('m') < 8)
        {
            $sem = $interval * 2;
        }
        else
        {
            $sem = $interval * 2 + 1;
        }
        
        $level = substr($npm, 2, 1);
        $jur = substr($npm, 5, 1);
        $pro = substr($npm, 6, 1);
        $str_jur = "";
        $str_prod = "";
        if($level == 5) $level = 1;
        $strata = "S".$level;
        
        if($jur == 1)
        {
            $str_jur = "Kimia";
            $str_prod = "S".$level." ".$str_jur;
        }
        elseif($jur == 2)
        {
            $str_jur = "Biologi";
            if($pro == 1) 
            {
                $str_prod = "S".$level." ".$str_jur;
            }
            elseif($pro == 2) 
            {
                $str_prod = "S".$level." ".$str_jur." Terapan";
            }
        }
        elseif($jur == 3)
        {
            $str_jur = "Matematika";
            if($pro == 1)
            {
                $str_prod = "S".$level." ".$str_jur;
            }
            elseif($pro == 2)
            {
                $str_prod = "S".$level." Ilmu Komputer";
            }
        }
        elseif($jur == 4)
        {
            $str_jur = "Fisika";
            $str_prod = "S".$level." ".$str_jur;
        }
        elseif($jur == 5)
        {
            $str_jur = "Ilmu Komputer";
            if($level == 0)
            {
                $str_prod = "D3 Manajemen Informatika";
                $strata = "D3";
            }
            else
            {
                $str_prod = "S".$level." ".$str_jur;
            }
        }
        elseif($jur == 6)
        {
            $str_prod = "Doktor MIPA";
        }
        
        $result = array(
                'jurusan' => $str_jur,
                'prodi' => $str_prod,
                'semester' => $sem,
                'strata' => $strata
            );
            
        return $result;
    }


    
    function layanan_fakultas_form_unduh()
	{
		$id = $this->input->get('id');
		$layanan = $this->input->get('layanan');
		$jenis = $this->uri->segment(3);

        $data = $this->layanan_model->select_layanan_fakultas_mhs_id($id);
        $meta = $this->layanan_model->select_layanan_fakultas_mhs_id_layanan($id);

        switch($layanan){
            case "1":
            //alih studi
            $this->form_1($data,$meta);
            case "2":
            //Form Bebas Laboratorium
            $this->form_2($data,$meta);
            case "3":
            //Form Bebas Laboratorium
            $this->form_3($data,$meta);

        }
    }
    
    function form_1($data,$meta)
    {
        /* ****************************************
        Form Name: Alih Studi
        Created By: 198701282018031001
        Created Date: 2019/08/02
        Last Modification: 2019/08/06
        * *****************************************/

        /*Edit by   :1617051107
        date        :30/01/2020 */

        /*Edit by   :1617051088
        date        :26/10/2020 */

        $cek_atr = $this->layanan_model->select_layanan_atribut_by_id($data->id_layanan_fakultas);
        $n=0;
        foreach($meta as $metas)
        {
            if(empty($cek_atr)){
                $attr[$n] = "";
            }
            else{
                $attr[$n] = $metas->meta_value;
            }
            $n++;
        }

        $numPage = '/PM/MIPA/I/22';
        $spasi = 6;
        $kode = 0;
        $type = '';
        $mhs = $this->user_model->get_mahasiswa_data_npm($data->npm);
        $pa = $this->user_model->get_dosen_pa_by_npm($data->npm);
        $kajur = $this->user_model->get_kajur_by_npm($data->npm);
        $jurusan = $this->ta_model->get_jurusan($data->npm);
        $status = $this->get_prodi_from_npm($data->npm);       
        
        $pdf = new FPDF('P','mm',array(210,330));
        //$pdf->setting_page_footer($numPage, $qrKode, $kode);
        $pdf->setting_page_footer($numPage, $kode, $type);
        $pdf->setting_no_header(array(1));
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);
        $pdf->AddPage();
        $pdf->SetFont('Times','',12);
        $pdf->SetX(110);
        $pdf->MultiCell(70, $spasi, 'Bandar Lampung, '.$this->convert_date($data->created_at), 0, 'L');
        $pdf->Ln(10);
        $pdf->SetFont('Times','B',12);
        $pdf->MultiCell(150, $spasi,'Kepada Yth.', 0, 'L');
        $pdf->MultiCell(150, $spasi,'Dekan FMIPA Universitas Lampung', 0, 'L');
        $pdf->MultiCell(150, $spasi,'di Bandar Lampung', 0, 'L');
        $pdf->Ln(10);
        $pdf->Ln(10);
        $pdf->SetFont('Times','',12);
        $pdf->MultiCell(150, $spasi,'Saya yang bertanda tangan di bawah ini:', 0, 'L');
        $pdf->Ln(2);
        $pdf->Cell(45, $spasi,'Nama', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$mhs->name, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'NPM', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $mhs->npm, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Jurusan/Program Studi', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $status['jurusan'].'/'.$status['prodi'], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Fakultas', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,'Matematika dan Ilmu Pengetahuan Alam', 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Semester', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $status['semester'], 0, 1, 'L');
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Dengan ini mengajukan permohonan untuk alih studi dari Program Studi '.$status['prodi'].' ke Program Studi '.$attr[0].' dengan alasan '.$attr[1], 0, 'J');
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Bersama ini saya lampirkan juga:', 0, 'J');
        $pdf->Ln(2);
        $pdf->SetWidths(array(8,142));
        $pdf->SetAligns(array('C','J'));
        $pdf->SetSpacing($spasi);
        $pdf->RowNoBorder(array('1.',"Transkrip akademik yang disahkan oleh pejabat berwenang"));
        $pdf->RowNoBorder(array('2.',"Fotocopy Bukti pembayaran SPP/UKT untuk semester yang sedang berjalan (dilegalisir)"));
        $pdf->RowNoBorder(array('3.',"Surat tidak melanggar tata tertib dari pimpinan fakultas/universitas"));
        $pdf->RowNoBorder(array('4.',"Surat tidak diputus studikan"));
        $pdf->RowNoBorder(array('5.',"Surat keterangan berkelakuan baik dari pimpinan fakultas/universitas"));
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Demikian permohonan ini disampaikan, atas perhatian dan perkenan Bapak/Ibu saya ucapkan terima kasih.', 0, 'J');
        $pdf->Ln(10);
        $pdf->SetX(120);
        $pdf->Cell(60, $spasi,'Hormat Saya,',0,0);
        $pdf->Ln(2);
        $pdf->SetX(120);
        $pdf->Cell(60, $spasi,$pdf->Image("$data->ttd",$pdf->GetX()-3, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');
        $pdf->Ln(23);
        $pdf->SetX(120);

        $pdf->Cell(60, $spasi, $mhs->name,0,1);
        $pdf->SetX(120);
        $pdf->Cell(60, $spasi, "NPM. ".$mhs->npm,0,1);
        $pdf->Ln(5);

        $pdf->Cell(150, $spasi, "Mengetahui,",0,1,'C');
        $pdf->Cell(60, $spasi, "Ketua Jurusan ".$jurusan.",");
        $pdf->Cell(30, $spasi, "");
        $pdf->Cell(60, $spasi, "Pembimbing Akademik,",0,1);
        $pdf->Ln(20);
        $pdf->Cell(60, $spasi, $kajur->gelar_depan." ".$kajur->name.", ".$kajur->gelar_belakang);
        $pdf->Cell(30, $spasi, "");
        $pdf->Cell(60, $spasi, $pa->gelar_depan." ".$pa->name.", ".$pa->gelar_belakang,0,1);
        $pdf->Cell(60, $spasi, "NIP. ".$kajur->nip_nik);
        $pdf->Cell(30, $spasi, "");
        $pdf->Cell(60, $spasi, "NIP. ".$pa->nip_nik,0,1);

        //surat tidak melanggar tata tertib
        $tahun = date("Y");
        $pdf->AddPage('P');
        $pdf->SetFont('Times','BU',14);
        $pdf->Ln(5);
        $pdf->Cell(150, $spasi, "SURAT KETERANGAN TIDAK MELANGGAR TATA TERTIB",0,1,'C');
        $pdf->SetFont('Times','',12);
        $pdf->Cell(150, $spasi, "Nomor: ............/UN26.17/DT/".$tahun,0,1,'C');
        $pdf->Ln(10);
        $pdf->MultiCell(150, $spasi,'Dengan ini Dekan FMIPA Universitas Lampung menerangkan bahwa:', 0, 'L');
        $pdf->Ln(2);
        $pdf->Cell(45, $spasi,'Nama Lengkap', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$mhs->name, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'NPM', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $mhs->npm, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Jurusan/Program Studi', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $status['jurusan'].'/'.$status['prodi'], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Strata', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$status['strata'], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Semester', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $status['semester'], 0, 1, 'L');
        $almt = $this->wilayah_model->get_desa_by_id($mhs->kelurahan_desa);
        $pdf->SetWidths(array(45, 5, 100));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Alamat Lengkap',':',$mhs->jalan." Kelurahan ".$almt->desa." Kecamatan ".$almt->kecamatan." Kabupaten/Kota ".$almt->kabupaten." Provinsi ".$almt->provinsi." ".$mhs->kode_pos));
        $pdf->Cell(45, $spasi,'No. Telepon/HP', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $mhs->mobile, 0, 1, 'L');
        $pdf->Ln(2);

        $pdf->MultiCell(150, $spasi,'Adalah sepengetahuan kami, selama kuliah tidak pernah melanggar tata tertib yang berlaku di Universitas Lampung maupun di Fakultas MIPA. Surat Keterangan ini dibuat sebagai syarat untuk pengajuan alih program.', 0, 'J');
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Demikian surat keterangan ini dibuat agar dapat dipergunakan sebagaimana mestinya.', 0, 'J');
        $pdf->Ln(10);
        $pdf->SetX(112);
        $pdf->Cell(28, $spasi,'Dikeluarkan di');
        $pdf->Cell(5, $spasi,':');
        $pdf->SetFont('Times','B',12);
        $pdf->Cell(35, $spasi,'Bandar Lampung',0,1);
        $pdf->SetFont('Times','',12);
        $pdf->SetX(112);
        $pdf->Cell(28, $spasi,'Pada tanggal');
        $pdf->Cell(5, $spasi,':');
        $pdf->Cell(35, $spasi,'...............................',0,1);
        $pdf->SetX(112);

        $dekan = $this->user_model->get_dekan();
        $pdf->Cell(28, $spasi,'Dekan,',0,1);
        $pdf->Ln(20);
        $pdf->SetFont('Times','B',12);
        $pdf->SetX(112);
        if(!empty($dekan))
        {
            $pdf->Cell(68, $spasi, $dekan->gelar_depan." ".$dekan->name.", ".$dekan->gelar_belakang,0,1);
            $pdf->SetFont('Times','',12);
            $pdf->SetX(112);
            $pdf->Cell(68, $spasi, "NIP. ".$dekan->nip_nik,0,1);
        }
        else{
            $pdf->Cell(68, $spasi, "",0,1);
            $pdf->SetFont('Times','',12);
            $pdf->SetX(112);
            $pdf->Cell(68, $spasi, "NIP. ",0,1);
        }
       

        $pdf->AddPage('P');
        $pdf->SetFont('Times','BU',14);
        $pdf->Ln(5);
        $pdf->Cell(150, $spasi, "SURAT KETERANGAN BERKELAKUAN BAIK",0,1,'C');
        $pdf->SetFont('Times','',12);
        $pdf->Cell(150, $spasi, "Nomor: ............/UN26.17/DT/".$tahun,0,1,'C');
        $pdf->Ln(10);
        $pdf->MultiCell(150, $spasi,'Dengan ini Dekan FMIPA Universitas Lampung menerangkan bahwa:', 0, 'L');
        $pdf->Ln(2);
        $pdf->Cell(45, $spasi,'Nama Lengkap', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$mhs->name, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'NPM', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $mhs->npm, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Jurusan/Program Studi', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $status['jurusan'].'/'.$status['prodi'], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Strata', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$status['strata'], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Semester', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $status['semester'], 0, 1, 'L');
        $pdf->SetWidths(array(45, 5, 100));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Alamat Lengkap',':',$mhs->jalan." Kelurahan ".$almt->desa." Kecamatan ".$almt->kecamatan." Kabupaten/Kota ".$almt->kabupaten." Provinsi ".$almt->provinsi." ".$mhs->kode_pos));
        $pdf->Cell(45, $spasi,'No. Telepon/HP', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $mhs->mobile, 0, 1, 'L');
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Adalah benar mahasiswa tersebut tercatat sebagai Mahasiswa di FMIPA Universitas Lampung. Berdasarkan data dari bagian akademik, yang bersangkutan selama menjadi mahasiswa memiliki perilaku yang baik. Surat Keterangan ini dibuat sebagai syarat untuk pengajuan alih program.', 0, 'J');
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Demikian surat keterangan ini dibuat agar dapat dipergunakan sebagaimana mestinya.', 0, 'J');
        $pdf->Ln(10);
        $pdf->SetX(112);
        $pdf->Cell(28, $spasi,'Dikeluarkan di');
        $pdf->Cell(5, $spasi,':');
        $pdf->SetFont('Times','B',12);
        $pdf->Cell(35, $spasi,'Bandar Lampung',0,1);
        $pdf->SetFont('Times','',12);
        $pdf->SetX(112);
        $pdf->Cell(28, $spasi,'Pada tanggal');
        $pdf->Cell(5, $spasi,':');
        $pdf->Cell(35, $spasi,'...............................',0,1);
        $pdf->SetX(112);
        $pdf->Cell(28, $spasi,'Dekan,',0,1);
        $pdf->Ln(20);
        $pdf->SetFont('Times','B',12);
        $pdf->SetX(112);
        if(!empty($dekan))
        {
            $pdf->Cell(68, $spasi, $dekan->gelar_depan." ".$dekan->name.", ".$dekan->gelar_belakang,0,1);
            $pdf->SetFont('Times','',12);
            $pdf->SetX(112);
            $pdf->Cell(68, $spasi, "NIP. ".$dekan->nip_nik,0,1);
        }
        else{
            $pdf->Cell(68, $spasi, "",0,1);
            $pdf->SetFont('Times','',12);
            $pdf->SetX(112);
            $pdf->Cell(68, $spasi, "NIP. ",0,1);
        }

        $pdf->AddPage('P');
        $pdf->SetFont('Times','BU',14);
        $pdf->Ln(5);
        $pdf->Cell(150, $spasi, "SURAT KETERANGAN TIDAK DIPUTUS STUDIKAN",0,1,'C');
        $pdf->SetFont('Times','',12);
        $pdf->Cell(150, $spasi, "Nomor: ............/UN26.17/DT/".$tahun,0,1,'C');
        $pdf->Ln(10);
        $pdf->MultiCell(150, $spasi,'Dengan ini Dekan FMIPA Universitas Lampung menerangkan bahwa:', 0, 'L');
        $pdf->Ln(2);
        $pdf->Cell(45, $spasi,'Nama Lengkap', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$mhs->name, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'NPM', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $mhs->npm, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Jurusan/Program Studi', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $status['jurusan'].'/'.$status['prodi'], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Strata', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$status['strata'], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Semester', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $status['semester'], 0, 1, 'L');
        $pdf->SetWidths(array(45, 5, 100));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Alamat Lengkap',':',$mhs->jalan." Kelurahan ".$almt->desa." Kecamatan ".$almt->kecamatan." Kabupaten/Kota ".$almt->kabupaten." Provinsi ".$almt->provinsi." ".$mhs->kode_pos));
        $pdf->Cell(45, $spasi,'No. Telepon/HP', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $mhs->mobile, 0, 1, 'L');
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Mahasiswa yang disebutkan diatas tidak pernah diputus studikan di Fakultas MIPA baik secara administratif ataupun hal yang lainnya. Surat Keterangan ini dibuat sebagai syarat untuk pengajuan alih program.', 0, 'J');
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Demikian surat keterangan ini dibuat agar dapat dipergunakan sebagaimana mestinya.', 0, 'J');
        $pdf->Ln(10);
        $pdf->SetX(112);
        $pdf->Cell(28, $spasi,'Dikeluarkan di');
        $pdf->Cell(5, $spasi,':');
        $pdf->SetFont('Times','B',12);
        $pdf->Cell(35, $spasi,'Bandar Lampung',0,1);
        $pdf->SetFont('Times','',12);
        $pdf->SetX(112);
        $pdf->Cell(28, $spasi,'Pada tanggal');
        $pdf->Cell(5, $spasi,':');
        $pdf->Cell(35, $spasi,'...............................',0,1);
        $pdf->SetX(112);
        $pdf->Cell(28, $spasi,'Dekan,',0,1);
        $pdf->Ln(20);
        $pdf->SetFont('Times','B',12);
        $pdf->SetX(112);
        if(!empty($dekan))
        {
            $pdf->Cell(68, $spasi, $dekan->gelar_depan." ".$dekan->name.", ".$dekan->gelar_belakang,0,1);
            $pdf->SetFont('Times','',12);
            $pdf->SetX(112);
            $pdf->Cell(68, $spasi, "NIP. ".$dekan->nip_nik,0,1);
        }
        else{
            $pdf->Cell(68, $spasi, "",0,1);
            $pdf->SetFont('Times','',12);
            $pdf->SetX(112);
            $pdf->Cell(68, $spasi, "NIP. ",0,1);
        }

        $pdf->AddPage('P');
        $pdf->SetFillColor(205,205,205);
        // Title
        $pdf->Ln(5);
        $pdf->SetFont('Times','B',16);
        $pdf->Cell(150, $spasi, "VERIFIKASI LAYANAN",0,1,'L');
        $pdf->SetFont('Times','',12);
        $pdf->Ln(5);
        $pdf->Cell(45, $spasi,'No. Jenis Layanan', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$data->id_layanan_fakultas, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Jenis Layanan', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$this->layanan_model->select_layanan_by_id($data->id_layanan_fakultas)->nama, 0, 1, 'L');
        $pdf->Ln(5);
        $pdf->Cell(45, $spasi,'Nama Lengkap', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$mhs->name, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'NPM', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $mhs->npm, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Jurusan', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $jurusan, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Tanggal Pengisian', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $this->convert_date($data->created_at), 0, 1, 'L');
        $pdf->Ln(6);
        
        // SubHeader
        $pdf->SetWidths(array(8,90,35,35));
        $pdf->SubHeader(array('No', 'Jenis Persyaratan', 'Jumlah', 'Verifikasi'));

        // Isi
        $pdf->SetAligns(array('C','L'));
        $pdf->Row(array('1',"Surat Permohonan Alih Program beserta alasan (tandatangan di atas materai Rp.6.000,-)",'1 lbr.',''));
        $pdf->Row(array('2',"Copy pembayaran SPP/UKT semester berjalan terlegalisir",'1 lbr.',''));
        $pdf->Row(array('3',"Transkrip nilai yang sudah disahkan Wakil Dekan I",'1 lbr.',''));
        $pdf->Row(array('4',"Surat Keterangan Tidak Melanggar Tata Tertib di FMIPA",'1 lbr.',''));
        $pdf->Row(array('5',"Surat Keterangan Berkelakuan Baik di FMIPA",'1 lbr.',''));
        $pdf->Row(array('6',"Surat Keterangan Tidak Diputus-Studikan dari FMIPA",'1 lbr.',''));
        $pdf->Row(array('7',"Copy : KTM (dilegalisir) & e-KTP dalam satu lembar A4",'1 lbr.',''));


        $pdf->Output('I','form_alih_studi.pdf');
    }

    function form_2($data,$meta)
    {
        /* ****************************************
        Form Name: Alih Studi
        Created By: 198701282018031001
        Created Date: 2019/08/02
        Last Modification: 2019/08/06
        * *****************************************/

        /*Edit by   :1617051107
        date        :30/01/2020 */

        /*Edit by   :1617051088
        date        :26/10/2020 */

        $cek_atr = $this->layanan_model->select_layanan_atribut_by_id($data->id_layanan_fakultas);
        $n=0;
        foreach($meta as $metas)
        {
            if(empty($cek_atr)){
                $attr[$n] = "";
            }
            else{
                $attr[$n] = $metas->meta_value;
            }
            $n++;
        }

        $numPage = '/PM/MIPA/I/22';
        $spasi = 4.6;
        $spasi2 = 6;
        $kode = 0;
        $type = '';
        $mhs = $this->user_model->get_mahasiswa_data_npm($data->npm);
        $pa = $this->user_model->get_dosen_pa_by_npm($data->npm);
        $kajur = $this->user_model->get_kajur_by_npm($data->npm);
        $jurusan = $this->ta_model->get_jurusan($data->npm);
        $status = $this->get_prodi_from_npm($data->npm);     
        $tahun = date("Y");

        $pdf = new FPDF('P','mm',array(210,330));
        //$pdf->setting_page_footer($numPage, $qrKode, $kode);
        $pdf->setting_page_footer($numPage, $kode, $type);
        //$pdf->setting_no_header(array(2));
        $pdf->SetLeftMargin(20);
        $pdf->SetTopMargin(20);
        $pdf->AddPage();
        $pdf->SetFont('Times','BU',14);
        $pdf->Cell(170, $spasi, "FORMULIR BEBAS LABORATORIUM",0,1,'C');
        $pdf->SetFont('Times','',12);
        $pdf->Cell(170, $spasi, "Nomor: ............/UN26.17/DT/".$tahun,0,1,'C');
        $pdf->Ln(3);
        $pdf->MultiCell(170, $spasi,'Dengan ini Dekan FMIPA Universitas Lampung menerangkan bahwa:', 0, 'L');
        $pdf->Ln(1);
        $pdf->Cell(45, $spasi,'Nama Lengkap', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$mhs->name, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'NPM', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $mhs->npm, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Jurusan/Program Studi', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $status['jurusan'].'/'.$status['prodi'], 0, 1, 'L');
        $pdf->Ln(1);
        $pdf->SetWidths(array(85,85));
        $pdf->SetAligns(array('L','L'));
        $pdf->SetSpacing($spasi);
        $labs = $this->user_model->get_lab_all();
        $count = count($labs);
        for($n=0;$n<$count;$n+=2)
        {
            $kpl_lab = $this->user_model->get_kalab($labs[$n]->id_lab);
            $kpl_lab2 = $this->user_model->get_kalab($labs[$n+1]->id_lab);
            if(!empty($kpl_lab)){
                $kpl_nama[$n] = $kpl_lab->gelar_depan." ".$kpl_lab->name.", ".$kpl_lab->gelar_belakang;
                $kpl_nip[$n] = $kpl_lab->nip_nik;      
            }
            else{
                $kpl_nama[$n] = "";
                $kpl_nip[$n] = "";
            }
            if(!empty($kpl_lab2))
            {
                $kpl_nama[$n+1] = $kpl_lab2->gelar_depan." ".$kpl_lab2->name.", ".$kpl_lab2->gelar_belakang;
                $kpl_nip[$n+1] = $kpl_lab2->nip_nik;
            }
            else{
                $kpl_nama[$n+1] = "";
                $kpl_nip[$n+1] = "";
            }
            $pdf->Row(array("Kepala ".str_replace("Laboratorium","Lab.",$labs[$n]->nama_lab)."\n\n\n".$kpl_nama[$n]."\nNIP. ".$kpl_nip[$n], "Kepala ".str_replace("Laboratorium","Lab.",$labs[$n+1]->nama_lab)."\n\n\n".$kpl_nama[$n+1]."\nNIP. ".$kpl_nip[$n+1]));
        }

        $wd2 = $this->user_model->get_wd_akademik();
        if(empty($wd2)){
            $wd2_name = "";
            $wd2_nip = "";
        }
        else{
            $wd2_name = $wd2->gelar_depan." ".$wd2->name.", ".$wd2->gelar_belakang;
            $wd2_nip = $wd2->nip_nik;
        }

        $pdf->Ln(3);
        $y_now = $pdf->GetY();
        $pdf->SetFont('Times','',12);
        $pdf->SetX(100);
        $pdf->MultiCell(80, $spasi, "Bandar Lampung, ...........................\na.n. Dekan,\nWakil Dekan Bid. Akademik dan Kerjasama\n\n\n\n".$wd2_name."\nNIP. ".$wd2_nip, 0, 'L');

        $pdf->SetFont('Times','',10);
        $pdf->SetY($y_now);
        $pdf->Cell(75, $spasi, 'Catatan:',0,1,'L');
        $pdf->SetWidths(array(5,70));
        $pdf->SetAligns(array('L','L'));
        $pdf->SetSpacing($spasi);
        $pdf->RowNoBorder(array("1)", 'Semua calon lulusan S1 harus mendapatkan Bebas Lab. dari semua lab yang ada di FMIPA (untuk D3 hanya lab. di jurusan masing-masing).  Tanda tangan semua kalab harus dilakukan sebelum ujian skripsi dan tanggal penandatangan'));
        $pdf->RowNoBorder(array("2)", 'Lampirkan fotocopy berita acara seminar hasil.'));

        // LEMBAR VERIFIKASI
        $pdf->AddPage('P');
        $pdf->SetFillColor(205,205,205);
        $pdf->SetLeftMargin(30);

         // Title
         $pdf->Ln(5);
         $pdf->SetFont('Times','B',16);
         $pdf->Cell(150, $spasi, "VERIFIKASI LAYANAN",0,1,'L');
         $pdf->SetFont('Times','',12);
         $pdf->Ln(5);
         $pdf->Cell(45, $spasi,'No. Jenis Layanan', 0, 0, 'L');
         $pdf->Cell(5, $spasi,':', 0, 0, 'C');
         $pdf->Cell(100, $spasi,$data->id_layanan_fakultas, 0, 1, 'L');
         $pdf->Cell(45, $spasi,'Jenis Layanan', 0, 0, 'L');
         $pdf->Cell(5, $spasi,':', 0, 0, 'C');
         $pdf->Cell(100, $spasi,$this->layanan_model->select_layanan_by_id($data->id_layanan_fakultas)->nama, 0, 1, 'L');
         $pdf->Ln(5);
         $pdf->Cell(45, $spasi,'Nama Lengkap', 0, 0, 'L');
         $pdf->Cell(5, $spasi,':', 0, 0, 'C');
         $pdf->Cell(100, $spasi,$mhs->name, 0, 1, 'L');
         $pdf->Cell(45, $spasi,'NPM', 0, 0, 'L');
         $pdf->Cell(5, $spasi,':', 0, 0, 'C');
         $pdf->Cell(100, $spasi, $mhs->npm, 0, 1, 'L');
         $pdf->Cell(45, $spasi,'Jurusan', 0, 0, 'L');
         $pdf->Cell(5, $spasi,':', 0, 0, 'C');
         $pdf->Cell(100, $spasi, $jurusan, 0, 1, 'L');
         $pdf->Cell(45, $spasi,'Tanggal Pengisian', 0, 0, 'L');
         $pdf->Cell(5, $spasi,':', 0, 0, 'C');
         $pdf->Cell(100, $spasi, $this->convert_date($data->created_at), 0, 1, 'L');
         $pdf->Ln(6);

        $pdf->SetWidths(array(8,90,35,35));
        $pdf->SubHeader(array('No', 'Jenis Persyaratan', 'Jumlah', 'Verifikasi'));

        // Isi
        $pdf->SetAligns(array('C','L'));
        $pdf->Row(array('1',"Formulir Bebas Laboratorium FMIPA yang sudah ditandatangani oleh seluruh pejabat Laboratorium FMIPA (Asli dan copy)",'1+4 Lbr.',''));
        $pdf->Row(array('2',"Copy Berita Acara Seminar Tugas Akhir/Skripsi/Tesis/ Disertasi",'1 lbr.',''));

        $pdf->Output('I','form_bebas_lab.pdf');
    }

}