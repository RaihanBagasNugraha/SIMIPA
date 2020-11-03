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
        $this->load->helper('download');
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

    function penyebut($nilai) {
		$nilai = abs($nilai);
		$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
		$temp = "";
		if ($nilai < 12) {
			$temp = " ". $huruf[$nilai];
		} else if ($nilai <20) {
			$temp = $this->penyebut($nilai - 10). " belas";
		} else if ($nilai < 100) {
			$temp = $this->penyebut($nilai/10)." puluh". $this->penyebut($nilai % 10);
		} else if ($nilai < 200) {
			$temp = " seratus" . $this->penyebut($nilai - 100);
		} else if ($nilai < 1000) {
			$temp = $this->penyebut($nilai/100) . " ratus" . $this->penyebut($nilai % 100);
		} else if ($nilai < 2000) {
			$temp = " seribu" . penyebut($nilai - 1000);
		} else if ($nilai < 1000000) {
			$temp =$this-> penyebut($nilai/1000) . " ribu" . $this->penyebut($nilai % 1000);
		} else if ($nilai < 1000000000) {
			$temp = $this->penyebut($nilai/1000000) . " juta" . $this->penyebut($nilai % 1000000);
		} else if ($nilai < 1000000000000) {
			$temp = $this->penyebut($nilai/1000000000) . " milyar" . $this->penyebut(fmod($nilai,1000000000));
		} else if ($nilai < 1000000000000000) {
			$temp = $this->penyebut($nilai/1000000000000) . " trilyun" . $this->penyebut(fmod($nilai,1000000000000));
		}     
		return $temp;
    }
    
    function convert_terbilang($nilai) {
		if($nilai<0) {
            $hasil1 = $this->penyebut($nilai);
			$hasil = "minus ". trim($hasil1);
		} else {
            $hasil1 = $this->penyebut($nilai);
			$hasil = trim($hasil1);
		}     		
		return $hasil;
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

        if($level == 1 || $level == 5){
            $ta = "Skripsi";
        }
        elseif($level == 2){
            $ta = "Tesis";
        }
        elseif($level == 3){
            $ta = "Disertasi";            
        }
        else{
            $ta = "Tugas Akhir";
        }
        
        $result = array(
                'jurusan' => $str_jur,
                'prodi' => $str_prod,
                'semester' => $sem,
                'strata' => $strata,
                'ta' => $ta
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
            //akademik
            case "1":
            //alih studi
            $this->form_1($data,$meta);
            case "2":
            //Form Bebas Laboratorium  --> Menu baru
            $this->form_2($data,$meta);
            case "3":
            //Form Bukti penyerahan TA
            $this->form_3($data,$meta);
            case "4":
            //Form Hapus Mata Kuliah
            $this->form_4($data,$meta);
            case "5":
            //Form Wisuda
            $this->form_5($data,$meta);
            case "6":
            //Form Cuti
            $this->form_6($data,$meta);
            case "7":
            //Form perpanjangan masa studi
            $this->form_7($data,$meta);
            case "8":
            //Form perpanjangan masa studi d3
            $this->form_8();
            case "9":
            //Form studi terbimbing
            $this->form_9($data,$meta);
            case "10":
            //Form ttd dekan
            $this->form_10($data,$meta);
            case "11":
            //Form ttd khs
            $this->form_11($data,$meta);
            case "12":
            //Form ttd krs
            $this->form_12($data,$meta);
            case "13":
            //Form ttd transkrip
            $this->form_13($data,$meta);
            case "46":
            //Form akreditasi
            $this->form_46($data,$meta);
            case "14":
            //Form bebas ruang baca
            $this->form_14($data,$meta);
            case "15":
            //Form bebas sanksi akademik
            $this->form_15($data,$meta);
            case "16":
            //Form ket lulus
            $this->form_16($data,$meta);
            case "17":
            //Form toefl
            $this->form_17($data,$meta);
            case "18":
            //Form pengunduran diri
            $this->form_18($data,$meta);
            case "19":
            //Form pengganti ijazah
            $this->form_19($data,$meta);
            case "20":
            //Form pengganti transkrip
            $this->form_20($data,$meta);
            case "21":
            //Form Tanda Terima Pembayaran Legalisir Ijasah
            $this->form_21($data,$meta);
            case "22":
            //Form Tanda Terima Pembayaran Legalisir Transkrip
            $this->form_22($data,$meta);
            case "23":
            //Form Tanda Terima transkrip matahari
            $this->form_23($data,$meta);

            //kemahasiswaan
            case "24":
            //Form kehilangan ktm
            $this->form_24($data,$meta);
            case "25":
            //Form legalisir sk bidikmisi
            $this->form_25($data,$meta);
            case "26":
            //Form Beasiswa Lengkap
            $this->form_26($data,$meta);
            case "27":
            //Form aktif kuliah non asn
            $this->form_27($data,$meta);
            case "28":
            //Form aktif kuliah asn
            $this->form_28($data,$meta);
            case "29":
            //Form kehilangan bukti ukt
            $this->form_29($data,$meta);
            case "30":
            //Form belum memiliki ktm
            $this->form_30($data,$meta);
            case "31":
            //Form membuat ktm
            $this->form_31($data,$meta);
            case "32":
            //Form tugas kelompok --> menu baru
            $this->form_32($data,$meta);
            case "33":
            //Form tugas individu --> menu baru
            $this->form_33($data,$meta);
            case "34":
            //Form perubahan nama no rek
            $this->form_34($data,$meta);
            case "35":
            //Form Kuisioner Alumni --> menu baru
            $this->form_35($data,$meta);
            case "36":
            //Form tidak menerima beasiswa
            $this->form_36($data,$meta);
            case "37":
            //Form legalisir ktm
            $this->form_37($data,$meta);

            //umum dan keuangan
            case "38":
            //Form peminjaman gedung dan alat
            $this->form_38($data,$meta);
            case "39":
            //Surat Izin Pelaksanaan Penelitian di Luar Jam Kerja
            $this->form_39($data,$meta);
            case "40":
            //Form Izin Pelaksanaan Penelitian di Luar Jam Kerja
            $this->form_40($data,$meta);
            case "41":
            //Form keringanan spp
            $this->form_41($data,$meta);
            case "42":
            //Form keterlambatan spp
            $this->form_42($data,$meta);
            case "43":
            //Form pembebasan spp
            $this->form_43($data,$meta);
            case "44":
            //Form verifikasi layanan cap fakultas
            $this->form_44($data,$meta);
            case "45":
            //Form wawancara
            $this->form_45($data,$meta);
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
        if(empty($cek_atr)){
            for($m=0;$m<=99;$m++){
                $attr[$m] = "";
            }
        }
        else{
            foreach($meta as $metas)
            {
                $attr[$n] = $metas->meta_value;
                $n++;
            }
        }
       
        $count = count($meta);

        $numPage = '/PM/MIPA/I/22';
        $spasi = 6;
        $kode = 0;
        $type = '';
        $mhs = $this->user_model->get_mahasiswa_data_npm($data->npm);
        $pa = $this->user_model->get_dosen_pa_by_npm($data->npm);
        $kajur = $this->user_model->get_kajur_by_npm($data->npm);
        $jurusan = $this->ta_model->get_jurusan($data->npm);
        $status = $this->get_prodi_from_npm($data->npm);       
        
        if(empty($kajur)){
            $kajur_name = "";
            $kajur_nip = "";
        }
        else{
            $kajur_name = $kajur->gelar_depan." ".$kajur->name.", ".$kajur->gelar_belakang;
            $kajur_nip = $kajur->nip_nik;
        }

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
        $pdf->Cell(60, $spasi, $kajur_name);
        $pdf->Cell(30, $spasi, "");
        $pdf->Cell(60, $spasi, $pa->gelar_depan." ".$pa->name.", ".$pa->gelar_belakang,0,1);
        $pdf->Cell(60, $spasi, "NIP. ".$kajur_nip);
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
        $pdf->RowNoBorder(array('Alamat Lengkap',':',$mhs->jalan." Kelurahan ".$almt->desa." Kecamatan ".$almt->kecamatan." ".$almt->kabupaten." Provinsi ".$almt->provinsi." ".$mhs->kode_pos));
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
        $pdf->RowNoBorder(array('Alamat Lengkap',':',$mhs->jalan." Kelurahan ".$almt->desa." Kecamatan ".$almt->kecamatan." ".$almt->kabupaten." Provinsi ".$almt->provinsi." ".$mhs->kode_pos));
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
        $pdf->RowNoBorder(array('Alamat Lengkap',':',$mhs->jalan." Kelurahan ".$almt->desa." Kecamatan ".$almt->kecamatan." ".$almt->kabupaten." Provinsi ".$almt->provinsi." ".$mhs->kode_pos));
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
        Form Name: bebas lab
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
        if(empty($cek_atr)){
            for($m=0;$m<=99;$m++){
                $attr[$m] = "";
            }
        }
        else{
            foreach($meta as $metas)
            {
                $attr[$n] = $metas->meta_value;
                $n++;
            }
        }
       
        $count = count($meta);

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

    function form_3($data,$meta)
    {
         /* ****************************************
        Form Name: penyerahan skripsi
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
        if(empty($cek_atr)){
            for($m=0;$m<=99;$m++){
                $attr[$m] = "";
            }
        }
        else{
            foreach($meta as $metas)
            {
                $attr[$n] = $metas->meta_value;
                $n++;
            }
        }
       
        $count = count($meta);

        $mhs = $this->user_model->get_mahasiswa_data_npm($data->npm);
        $status = $this->get_prodi_from_npm($data->npm);     
        $jurusan = $this->ta_model->get_jurusan($data->npm);

        $sm = "Hasil";
        if($status['strata'] == 'D3'){
            $draf = "Tugas Akhir";
            $sm = "Tugas Akhir";
        }elseif($status['strata'] == 'S2'){
            $draf = "Tesis";
        }elseif($status['strata'] == 'S3'){
            $draf = "Disertasi";
        }else{
            $draf = "Skripsi";
        }
        $kode = 0;
        $type = '';
        $numPage = '/PM/MIPA/I/31';
        $spasi = 6;

        $pdf = new FPDF('P','mm',array(210,330));
        $pdf->setting_page_footer($numPage, $kode, $type);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(15);
        $pdf->AddPage();
        $pdf->SetFont('Times','BU',16);
        $pdf->Cell(150, $spasi, "BUKTI PENYERAHAN ".strtoupper($draf),0,1,'C');
        $pdf->SetFont('Times','',12);
        $pdf->Ln(5);
        $pdf->MultiCell(150, $spasi,'Yang bertanda tangan dibawah ini telah menerima '.$draf.' dan CD (dalam format PDF) hasil penelitian mahasiswa berikut:', 0, 'L');
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
        $pdf->SetWidths(array(45, 5, 100));
        $pdf->SetAligns(array('L','C','L'));

        //judul
        //get judul
        $judul = $this->ta_model->get_latest_ta_npm($data->npm);
        $pdf->SetWidths(array(45, 5, 100));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Judul '.$draf,':', $judul->judul_approve == 1 ? $judul->judul1 : $judul->judul2));
        $pdf->Ln(5);

        $pdf->SetSpacing($spasi);
        $pdf->SetAligns(array('C','L','L','L','L'));
        $pdf->SetWidths(array(8,35.5,35.5,41,30));
        $pdf->SetBoldFont(array('B','B','B','B','B'));
        $pdf->SubHeaderNoBack(array('No', 'Tanggal Terima', 'Nama Penerima', 'Jabatan', 'Tanda Tangan'));
        $pdf->SetBoldFont(array(''));

        //get petugas & kajur & kasubag akadmeik
        $petugas = $this->layanan_model->get_tugas_tambahan_user('Petugas Perpustakaan');
        $kajur = $this->user_model->get_kajur_by_npm($data->npm);
        $kasubag = $this->layanan_model->get_tugas_tambahan_user('Kepala Sub Bagian Akademik');

        $num = 0;
        $pdf->Row(array(++$num,"",empty($petugas) ? "" :  $petugas->gelar_depan." ".$petugas->name.", ".$petugas->gelar_belakang,"Petugas Perpustakaan FMIPA",''));
        $pdf->Row(array(++$num,"",empty($kajur) ? "" :  $kajur->gelar_depan." ".$kajur->name.", ".$kajur->gelar_belakang,'Ketua Jurusan '.$jurusan."\n ",''));
        
        if($status['strata'] == 'S2' || $status['strata'] == 'S3'){
            $pdf->Row(array(++$num,"","","Direktur Pascasarjana Unila\n ",''));
        }

        $pdf->Row(array(++$num,"","","Petugas Perpustakaan Unila\n ",''));

        if(empty($kasubag)){
            $kasubag_nama = "";
            $kasubag_nip = "";
        }
        else{
            $kasubag_nama = $kasubag->gelar_depan." ".$kasubag->name.", ".$kasubag->gelar_belakang;
            $kasubag_nip = $kasubag->nip_nik;
        }
        
        $y_now = $pdf->GetY();
        $pdf->SetFont('Times','',12);
        $pdf->Ln(5);
        $pdf->SetX(110);
        $pdf->MultiCell(80, $spasi, "Bandar Lampung, ...........................\na.n. Kepala Bagian Tata Usaha,\nKasubbag Akademik,\n\n\n\n\n".$kasubag_nama."\nNIP. ".$kasubag_nip, 0, 'L');

        $pdf->SetFont('Times','',10);
        $pdf->SetY($y_now);
        $pdf->Ln(5);
        $pdf->Cell(75, $spasi, 'Catatan bentuk penerimaan berkas '.$draf.':',0,1,'L');
        $pdf->SetWidths(array(3,72));
        $pdf->SetAligns(array('L','L'));
        $pdf->SetSpacing($spasi);
        $pdf->RowNoBorder(array("-", 'No.1 dan 2 berupa satu eksemplar '.$draf.' dan satu keping CD yang berisi data lulusan, file '.$draf.', file draft jurnal (dalam format .pdf)'));
        $pdf->RowNoBorder(array("-", 'No. 3 berupa upload '.$draf.' di Digital Library UPT Perpustakaan Unila.'));

        // LEMBAR VERIFIKASI
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
        $pdf->Row(array('1',"Surat Bukti Penyerahan Buku Laporan Akhir/Skripsi/Tesis/Disertasi hasil unduhan dari website FMIPA (Asli dan copy)",'3 lembar.',''));
        $pdf->Row(array('2',"Sudah ditandatangani oleh : \n1.   Petugas Ruang Baca FMIPA;\n2.   Pejabat jurusan yang bersangkutan;\n3.   Petugas Perpustakaan UNILA
        ",'',''));

        $pdf->Output('I','form_bukti_penyerahan.pdf');
    }

    function form_4($data,$meta)
    {
         /* ****************************************
        Form Name: hapus matkul
        Created By: 198701282018031001
        Created Date: 2019/08/02
        Last Modification: 2019/08/06
        * *****************************************/

        /*Edit by   :1617051107
        date        :30/01/2020 */

        /*Edit by   :1617051088
        date        :27/10/2020 */
    
        $cek_atr = $this->layanan_model->select_layanan_atribut_by_id($data->id_layanan_fakultas);
        $n=0;
        if(empty($cek_atr)){
            for($m=0;$m<=99;$m++){
                $attr[$m] = "";
            }
        }
        else{
            foreach($meta as $metas)
            {
                $attr[$n] = $metas->meta_value;
                $n++;
            }
        }
       
        $count = count($meta);
  
        $mhs = $this->user_model->get_mahasiswa_data_npm($data->npm);
        $status = $this->get_prodi_from_npm($data->npm);     
        $jurusan = $this->ta_model->get_jurusan($data->npm);
        $pa = $this->user_model->get_dosen_pa_by_npm($data->npm);
        $kajur = $this->user_model->get_kajur_by_npm($data->npm);

        $numPage = '/PM/MIPA/I/11';
        $spasi = 5;
        $kode = 0;
        $type = '';

        $pdf = new FPDF('P','mm',array(210,330));
        $pdf->setting_page_footer($numPage, $kode, $type);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);
        $pdf->AddPage();
        $pdf->Ln(5);

        $pdf->SetFont('Times','',12);
        $pdf->Cell(17, $spasi, 'Lampiran');
        $pdf->Cell(3, $spasi, ':');
        $pdf->Cell(140, $spasi, "Satu Berkas",0, 1, 'L');
        $pdf->Cell(17, $spasi, 'Perihal');
        $pdf->Cell(3, $spasi, ':');
        $pdf->SetFont('Times','B',12);
        $pdf->Cell(140, $spasi, "Penghapusan Mata Kuliah",0, 1, 'L');
        $pdf->Ln(10);
        $pdf->SetFont('Times','',12);
        $pdf->MultiCell(150, $spasi,'Kepada Yth.', 0, 'L');
        $pdf->SetFont('Times','B',12);
        $pdf->MultiCell(150, $spasi,'Wakil Dekan Bidang Akademik dan Kerjasama', 0, 'L');
        $pdf->SetFont('Times','',12);
        $pdf->MultiCell(150, $spasi,'Fakultas MIPA Universitas Lampung', 0, 'L');
        $pdf->MultiCell(150, $spasi,'di Bandar Lampung', 0, 'L');
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
        $pdf->Cell(45, $spasi,'Pembimbing Akademik', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $pa->gelar_depan." ".$pa->name.", ".$pa->gelar_belakang, 0, 1, 'L');
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Dengan ini mengajukan permohonan penghapusan mata kuliah untuk pembuatan Transkrip Matahari sebagai berikut:', 0, 'J');
        $pdf->Ln(5);

        $pdf->SetWidths(array(10,30,45,15,22,13,30));
        $pdf->SetAligns(array('C','C','C','C','C','C','C'));
        $pdf->SetBoldFont(array('B','B','B','B','B','B','B'));
        $pdf->SetSpacing(6);
        $pdf->Row(array("NO", "Kopel", "Mata Kuliah", "SMT","Tahun","SKS", "Keterangan"));
        $pdf->SetBoldFont(array());
        $pdf->SetAligns(array('C','C','L','C','L','C','L'));
        $no = 1;
        $j = 0;
        for($j=0;$j<$count;$j+=6){
            $pdf->Row(array($no, $attr[$j], $attr[$j+1], $attr[$j+2],$attr[$j+3], $attr[$j+4], $attr[$j+5]));
            $no++;
        }

        $pdf->Ln(5);
        $pdf->MultiCell(150, $spasi,'Demikian permohonan ini, atas perhatian dan perkenan Bapak diucapkan terima kasih.', 0, 'J');
        $pdf->Ln(5);
        $pdf->SetX(115);
        $pdf->Cell(60, $spasi,'Bandar Lampung, '.$this->convert_date($data->created_at),0,1);
        $pdf->Cell(90, $spasi,'Menyetujui,',0,1);
        $pdf->Cell(85, $spasi,'Pembimbing Akademik,');
        $pdf->Cell(60, $spasi,'Pemohon,',0,1);
        $pdf->Cell(85, $spasi,'');
        $pdf->Cell(60, $spasi,$pdf->Image("$data->ttd",$pdf->GetX()-3, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');
        $pdf->Ln(20);
        $pdf->Cell(85, $spasi,$pa->gelar_depan." ".$pa->name.", ".$pa->gelar_belakang);
        $pdf->Cell(60, $spasi, $mhs->name,0,1);
        $pdf->Cell(85, $spasi, "NIP. ".$pa->nip_nik);
        $pdf->Cell(60, $spasi, "NPM. ".$mhs->npm,0,1);
        $pdf->Ln(5);

        $y_box = $pdf->GetY();
        $pdf->Cell(60, $spasi, "Menyetujui,",0,1);

        if($status['strata'] == 'S3'){
            //kaprodi doktor
            $kaprodi_dok = $this->layanan_model->get_kaprodi_doktor();
            if(empty($kaprodi_dok)){
                $kpr_nama = "";
                $kpr_nip = "";
            }
            else{
                $kpr_nama = $kaprodi_dok->gelar_depan." ".$kaprodi_dok->name.", ".$kaprodi_dok->gelar_belakang;
                $kpr_nip = $kaprodi_dok->nip_nik;
            }
            $pdf->Cell(60, $spasi, "Ketua Program Doktor FMIPA,",0,1);
            $pdf->Ln(20);
            $pdf->Cell(60, $spasi, $kpr_nama, 0,1);
            $pdf->Cell(60, $spasi, "NIP. ".$kpr_nip,0,1);
        }
        else {
            $pdf->Cell(60, $spasi, "Ketua Jurusan ".$jurusan.",",0,1);
            $pdf->Ln(20);
            $pdf->Cell(60, $spasi, $kajur->gelar_depan." ".$kajur->name.", ".$kajur->gelar_belakang, 0,1);
            $pdf->Cell(60, $spasi, "NIP. ".$kajur->nip_nik,0,1);
        }

        $pdf->SetY($y_box);
        $pdf->SetX(100);
        $pdf->SetFont('Times','',10);
        $pdf->MultiCell(80, $spasi,"Catatan Wk. Dekan Bid. Akademik dan Kerjasama:\n\n\n\n\n\n\n\n", 1, 'L');
        $pdf->Ln(10);
        $pdf->SetFont('Times','I',10);
        $pdf->MultiCell(150, $spasi-1.5,"Lampiran Surat Permohonan dengan:", 0, 'L');
        $pdf->MultiCell(150, $spasi-1.5,"1. Foto Copy Berita Acara Ujian Tugas Akhir/Skripsi/Tesis/Disertasi;", 0, 'L');
        $pdf->MultiCell(150, $spasi-1.5,"2. Transkrip Terakhir;", 0, 'L');
        $pdf->MultiCell(150, $spasi-1.5,"3. Surat Keterangan Nilai.", 0, 'L');

        // LEMBAR VERIFIKASI
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
            $pdf->Row(array('1',"Fotocopy Berita Acara Ujian Laporan Akhir / Skripsi / Tesis / Disertasi",'1 lbr',''));
            $pdf->Row(array('2',"Surat Keterangan Nilai dari Kajur (asli)",'1 lbr',''));
            $pdf->Row(array('3',"Fotocopy ijazah terakhir dilegalisir",'1 lbr',''));
            $pdf->Row(array('4',"Transkrip nilai yang sudah disahkan Wakil Dekan I (lingkari MK yang akan dihapus)",'1 lbr',''));
            $pdf->Row(array('5',"Surat permohonan penghapusan Mata Kuliah yang disetujui Kajur dan Dosen PA",'1 lbr',''));

            $pdf->SetLeftMargin(30);
            $pdf->Ln(10);
            $pdf->SetFont('Times','BU',14);
            $pdf->Cell(150, $spasi, "TANDA TERIMA PESANAN",0,1,'C');
            $pdf->SetFont('Times','',12);
            $pdf->Ln(5);
            $pdf->Cell(45, $spasi,'Nama Lengkap', 0, 0, 'L');
            $pdf->Cell(5, $spasi,':', 0, 0, 'C');
            $pdf->Cell(100, $spasi,$mhs->name, 0, 1, 'L');
            $pdf->Cell(45, $spasi,'NPM', 0, 0, 'L');
            $pdf->Cell(5, $spasi,':', 0, 0, 'C');
            $pdf->Cell(100, $spasi, $mhs->npm, 0, 1, 'L');
            $pdf->Cell(45, $spasi,'Jurusan/Program Studi', 0, 0, 'L');
            $pdf->Cell(5, $spasi,':', 0, 0, 'C');
            $pdf->Cell(100, $spasi, $status['jurusan'].'/'.$status['prodi'], 0, 1, 'L');
            $pdf->Ln(2);
            $pdf->SetWidths(array(10,70,30,40));
            $pdf->SetAligns(array('C','C','C','C'));
            $pdf->SetBoldFont(array('B','B','B','B'));
            $pdf->SetSpacing($spasi);
            $pdf->Row(array("NO", "JENIS DOKUMEN", "JUMLAH", "KETERANGAN"));
            $pdf->SetBoldFont(array());
            $pdf->SetAligns(array('C','L','C'));
            $pdf->Row(array("1.", "Transkrip Matahari", "5 Lembar", ""));
            $pdf->Ln(5);
            $pdf->SetX(110);
            $pdf->MultiCell(80, $spasi, "Bandar Lampung, ...........................\nPetugas Yang Menerima,\n\n\n\n\n.............................................\nNIP. ....................................", 0, 'L');


        $pdf->Output('I','form_hapus_matkul.pdf');
    }

    function form_5($data,$meta)
    {
        /* ****************************************
        Form Name: fowm wisuda
        Created By: 198701282018031001
        Created Date: 2019/08/02
        Last Modification: 2019/08/06
        * *****************************************/

        /*Edit by   :1617051107
        date        :30/01/2020 */

        /*Edit by   :1617051088
        date        :27/10/2020 */

        $cek_atr = $this->layanan_model->select_layanan_atribut_by_id($data->id_layanan_fakultas);
        $n=0;
        if(empty($cek_atr)){
            for($m=0;$m<=99;$m++){
                $attr[$m] = "";
            }
        }
        else{
            foreach($meta as $metas)
            {
                $attr[$n] = $metas->meta_value;
                $n++;
            }
        }
       
        $count = count($meta);
  
        $mhs = $this->user_model->get_mahasiswa_data_npm($data->npm);
        $status = $this->get_prodi_from_npm($data->npm);     
        $jurusan = $this->ta_model->get_jurusan($data->npm);
        $pa = $this->user_model->get_dosen_pa_by_npm($data->npm);
        $kajur = $this->user_model->get_kajur_by_npm($data->npm);

        $numPage = '/PM/MIPA/I/18';
        $spasi = 4.1;
        $spasi2 = 6;

        $kode = 0;
        $type = '';

        $pdf = new FPDF('P','mm',array(210,330));
        $pdf->setting_page_footer($numPage, $kode, $type);
        $pdf->setting_no_header(array(2,3,5,6));
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);

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
        $pdf->SetSpacing($spasi);
        $pdf->SetFont('Times','',10);
        $pdf->Row(array('1',"Formulir Pendaftaran Wisuda hasil unduhan dari Website BAK Unila",'1 lembar',''));
        $pdf->Row(array('2',"Print Out Biodata On Line 
        Terdiri atas : data mahasiswa, data Pribadi, data Disertasi/Tesis/Skripsi/ Tugas Akhir serta meng-upload pas foto hitam putih) yang telah di isi dengan benar dan lengkap, serta telah di validasi pihak Fakultas 
        ",'1 lembar',''));
        $pdf->Row(array('3',"Foto hitam putih 3x4 cm dan ditempel pada tempat yang telah disediakan",'6 lembar',''));
        $pdf->Row(array('4',"Kartu Tanda Mahasiswa (KTM)",'Asli',''));
        $pdf->Row(array('5',"Foto copy Kartu Tanda Penduduk (e-KTP)",'1 lembar',''));
        $pdf->Row(array('6',"Foto copy ijazah terakhir",'1 lembar',''));
        $pdf->Row(array('7',"7.     Bukti pembayaran UKT asli, dari Semester I s.d terakhir",'1 set',''));
        $pdf->Row(array('8',"Nilai  EPT yang telah dilegalisir Pusat Bahasa Unila, dengan ketentuan :\n a.  Berlaku 1 tahun sejak dikeluarkan sertifikat  s.d. pelaksanaan wisuda;\n b. Nilai/skor minimal  450.
        ",'1 lembar',''));
        $pdf->Row(array('9',"9.     Surat Keterangan Bebas Pinjaman dari Perpustakaan Unila",'Asli',''));
        $pdf->Row(array('10',"Surat Bukti Penyerahan Disertasi/Tesis/Skripsi/Tugas Akhir dari Perpustakaan Unila ",'Asli',''));
        $pdf->Row(array('11',"Judul Disertasi/Tesis/Skripsi/Tugas Akhir yang disetujui dan ditandatangani oleh Komisi Pembimbing dan Ketua Jurusan/Kaprodi",'1 lembar',''));
        $pdf->Row(array('12',"Fotocopy halaman Pengesahan Disertasi/Tesis/Skripsi/ Tugas Akhir",'1 lembar',''));
        $pdf->Row(array('13',"Fotocopy Berita Acara Ujian Disertasi/Tesis/Skripsi/Tugas Akhir",'1 lembar',''));
        $pdf->Row(array('14',"Transkrip Nilai Matahari",'1 lembar',''));
        $pdf->Row(array('15',"Surat  Pernyataan mematuhi Tata Tertib Upacara Wisuda di atas materai Rp.6.000,-",'1 lembar',''));
        $pdf->Row(array('16',"Arsip Fakultas : fotocopy persyaratan no. 1,2, 4-15, (no.7, telah dilegalisir)\n - masukkan dalam map warna : Merah = D-3;  Biru = S-1,  Kuning = S-2;  Hijau = S-3 
        ",'1 set',''));

        $pdf->SetLeftMargin(30);
        $pdf->AddPage();
        $spasi = 6;
        $pdf->SetFont('Times','B',16);
        $pdf->Ln(5);
        $pdf->Cell(150, $spasi, "FOTO WISUDAWAN (untuk FAKULTAS)",0,1,'C');
        $pdf->Ln(5);
        $pdf->SetFont('Times','B',14);

        $pdf->Cell(150, $spasi, "PERIODE ".strtoupper($attr[0])." T.A. ".$attr[1],0,1,'C');
        $pdf->Cell(150, $spasi, "FAKULTAS MATEMATIKA DAN ILMU PENGETAHUAN ALAM",0,1,'C');
        $pdf->Cell(150, $spasi, "UNIVERSITAS LAMPUNG",0,1,'C');
        $pdf->Cell(150, $spasi, "PROGRAM SARJANA DAN DIPLOMA",0,1,'C');
        $pdf->Ln(10);

        $pdf->SetFont('Times','',8);
        $pdf->Cell(48, $spasi, "", "LRT", 0);
        $pdf->Cell(3, $spasi, "");
        $pdf->Cell(48, $spasi, "", "LRT", 0);
        $pdf->Cell(3, $spasi, "");
        $pdf->Cell(48, $spasi, "", "LRT", 1);

        $pdf->Cell(2, $spasi, "", "L", 0);
        $pdf->Cell(10, $spasi, "Nama", 0, 0);
        $pdf->Cell(34, $spasi, substr(": ".$mhs->name, 0, 28), 0, 0);
        $pdf->Cell(2, $spasi, "", "R", 0);
        $pdf->Cell(3, $spasi, "");
        $pdf->Cell(2, $spasi, "", "L", 0);
        $pdf->Cell(10, $spasi, "Nama", 0, 0);
        $pdf->Cell(34, $spasi, substr(": ".$mhs->name, 0, 28), 0, 0);
        $pdf->Cell(2, $spasi, "", "R", 0);
        $pdf->Cell(3, $spasi, "");
        $pdf->Cell(2, $spasi, "", "L", 0);
        $pdf->Cell(10, $spasi, "Nama", 0, 0);
        $pdf->Cell(34, $spasi, substr(": ".$mhs->name, 0, 28), 0, 0);
        $pdf->Cell(2, $spasi, "", "R", 1);

        $pdf->Cell(2, $spasi, "", "L", 0);
        $pdf->Cell(10, $spasi, "NPM", 0, 0);
        $pdf->Cell(34, $spasi, ": ".$mhs->npm, 0, 0);
        $pdf->Cell(2, $spasi, "", "R", 0);
        $pdf->Cell(3, $spasi, "");
        $pdf->Cell(2, $spasi, "", "L", 0);
        $pdf->Cell(10, $spasi, "NPM", 0, 0);
        $pdf->Cell(34, $spasi, ": ".$mhs->npm, 0, 0);
        $pdf->Cell(2, $spasi, "", "R", 0);
        $pdf->Cell(3, $spasi, "");
        $pdf->Cell(2, $spasi, "", "L", 0);
        $pdf->Cell(10, $spasi, "NPM", 0, 0);
        $pdf->Cell(34, $spasi, ": ".$mhs->npm, 0, 0);
        $pdf->Cell(2, $spasi, "", "R", 1);

        $pdf->Cell(2, $spasi, "", "L", 0);
        $pdf->Cell(10, $spasi, "Fakultas", 0, 0);
        $pdf->Cell(34, $spasi, ": MIPA", 0, 0);
        $pdf->Cell(2, $spasi, "", "R", 0);
        $pdf->Cell(3, $spasi, "");
        $pdf->Cell(2, $spasi, "", "L", 0);
        $pdf->Cell(10, $spasi, "Fakultas", 0, 0);
        $pdf->Cell(34, $spasi, ": MIPA", 0, 0);
        $pdf->Cell(2, $spasi, "", "R", 0);
        $pdf->Cell(3, $spasi, "");
        $pdf->Cell(2, $spasi, "", "L", 0);
        $pdf->Cell(10, $spasi, "Fakultas", 0, 0);
        $pdf->Cell(34, $spasi, ": MIPA", 0, 0);
        $pdf->Cell(2, $spasi, "", "R", 1);

        $pdf->Cell(2, $spasi, "", "L", 0);
        $pdf->Cell(10, $spasi, "Prodi", 0, 0);
        $pdf->Cell(34, $spasi, substr(": ".$status['prodi'], 0, 28), 0, 0);
        $pdf->Cell(2, $spasi, "", "R", 0);
        $pdf->Cell(3, $spasi, "");
        $pdf->Cell(2, $spasi, "", "L", 0);
        $pdf->Cell(10, $spasi, "Prodi", 0, 0);
        $pdf->Cell(34, $spasi, substr(": ".$status['prodi'], 0, 28), 0, 0);
        $pdf->Cell(2, $spasi, "", "R", 0);
        $pdf->Cell(3, $spasi, "");
        $pdf->Cell(2, $spasi, "", "L", 0);
        $pdf->Cell(10, $spasi, "Prodi", 0, 0);
        $pdf->Cell(34, $spasi, substr(": ".$status['prodi'], 0, 28), 0, 0);
        $pdf->Cell(2, $spasi, "", "R", 1);

        $pdf->Cell(48, $spasi, "", "LRB", 0);
        $pdf->Cell(3, $spasi, "");
        $pdf->Cell(48, $spasi, "", "LRB", 0);
        $pdf->Cell(3, $spasi, "");
        $pdf->Cell(48, $spasi, "", "LRB", 1);

        $pdf->Cell(48, $spasi, "", "LR", 0);
        $pdf->Cell(3, $spasi, "");
        $pdf->Cell(48, $spasi, "", "LR", 0);
        $pdf->Cell(3, $spasi, "");
        $pdf->Cell(48, $spasi, "", "LR", 1);

        $pdf->Cell(2, $spasi, "", "L", 0);
        $pdf->Cell(44, $spasi, "Tempel Foto 3x4 cm di sini", 0, 0);
        $pdf->Cell(2, $spasi, "", "R", 0);
        $pdf->Cell(3, $spasi, "");
        $pdf->Cell(2, $spasi, "", "L", 0);
        $pdf->Cell(44, $spasi, "Tempel Foto 3x4 cm di sini", 0, 0);
        $pdf->Cell(2, $spasi, "", "R", 0);
        $pdf->Cell(3, $spasi, "");
        $pdf->Cell(2, $spasi, "", "L", 0);
        $pdf->Cell(44, $spasi, "Tempel Foto 3x4 cm di sini", 0, 0);
        $pdf->Cell(2, $spasi, "", "R", 1);

        $pdf->Cell(48, 50, "", "LR", 0);
        $pdf->Cell(3, 50, "");
        $pdf->Cell(48, 50, "", "LR", 0);
        $pdf->Cell(3, 50, "");
        $pdf->Cell(48, 50, "", "LR", 1);

        $pdf->Cell(48, $spasi, "", "LRB", 0);
        $pdf->Cell(3, $spasi, "");
        $pdf->Cell(48, $spasi, "", "LRB", 0);
        $pdf->Cell(3, $spasi, "");
        $pdf->Cell(48, $spasi, "", "LRB", 1);

        $pdf->Ln(10);
        $pdf->AddPage();
        $spasi = 6;
        $pdf->SetFont('Times','B',16);
        $pdf->Ln(5);
        $pdf->Cell(150, $spasi, "FOTO WISUDAWAN (untuk UNIVERSITAS)",0,1,'C');
        $pdf->Ln(5);
        $pdf->SetFont('Times','B',14);
        $pdf->Cell(150, $spasi, "PERIODE ".strtoupper($attr[0])." T.A. ".$attr[1],0,1,'C');
        $pdf->Cell(150, $spasi, "FAKULTAS MATEMATIKA DAN ILMU PENGETAHUAN ALAM",0,1,'C');
        $pdf->Cell(150, $spasi, "UNIVERSITAS LAMPUNG",0,1,'C');
        $pdf->Cell(150, $spasi, "PROGRAM SARJANA DAN DIPLOMA",0,1,'C');
        $pdf->Ln(10);

        $pdf->SetFont('Times','',8);
        $pdf->Cell(48, $spasi, "", "LRT", 0);
        $pdf->Cell(3, $spasi, "");
        $pdf->Cell(48, $spasi, "", "LRT", 0);
        $pdf->Cell(3, $spasi, "");
        $pdf->Cell(48, $spasi, "", "LRT", 1);

        $pdf->Cell(2, $spasi, "", "L", 0);
        $pdf->Cell(10, $spasi, "Nama", 0, 0);
        $pdf->Cell(34, $spasi, substr(": ".$mhs->name, 0, 28), 0, 0);
        $pdf->Cell(2, $spasi, "", "R", 0);
        $pdf->Cell(3, $spasi, "");
        $pdf->Cell(2, $spasi, "", "L", 0);
        $pdf->Cell(10, $spasi, "Nama", 0, 0);
        $pdf->Cell(34, $spasi, substr(": ".$mhs->name, 0, 28), 0, 0);
        $pdf->Cell(2, $spasi, "", "R", 0);
        $pdf->Cell(3, $spasi, "");
        $pdf->Cell(2, $spasi, "", "L", 0);
        $pdf->Cell(10, $spasi, "Nama", 0, 0);
        $pdf->Cell(34, $spasi, substr(": ".$mhs->name, 0, 28), 0, 0);
        $pdf->Cell(2, $spasi, "", "R", 1);

        $pdf->Cell(2, $spasi, "", "L", 0);
        $pdf->Cell(10, $spasi, "NPM", 0, 0);
        $pdf->Cell(34, $spasi, ": ".$mhs->npm, 0, 0);
        $pdf->Cell(2, $spasi, "", "R", 0);
        $pdf->Cell(3, $spasi, "");
        $pdf->Cell(2, $spasi, "", "L", 0);
        $pdf->Cell(10, $spasi, "NPM", 0, 0);
        $pdf->Cell(34, $spasi, ": ".$mhs->npm, 0, 0);
        $pdf->Cell(2, $spasi, "", "R", 0);
        $pdf->Cell(3, $spasi, "");
        $pdf->Cell(2, $spasi, "", "L", 0);
        $pdf->Cell(10, $spasi, "NPM", 0, 0);
        $pdf->Cell(34, $spasi, ": ".$mhs->npm, 0, 0);
        $pdf->Cell(2, $spasi, "", "R", 1);

        $pdf->Cell(2, $spasi, "", "L", 0);
        $pdf->Cell(10, $spasi, "Fakultas", 0, 0);
        $pdf->Cell(34, $spasi, ": MIPA", 0, 0);
        $pdf->Cell(2, $spasi, "", "R", 0);
        $pdf->Cell(3, $spasi, "");
        $pdf->Cell(2, $spasi, "", "L", 0);
        $pdf->Cell(10, $spasi, "Fakultas", 0, 0);
        $pdf->Cell(34, $spasi, ": MIPA", 0, 0);
        $pdf->Cell(2, $spasi, "", "R", 0);
        $pdf->Cell(3, $spasi, "");
        $pdf->Cell(2, $spasi, "", "L", 0);
        $pdf->Cell(10, $spasi, "Fakultas", 0, 0);
        $pdf->Cell(34, $spasi, ": MIPA", 0, 0);
        $pdf->Cell(2, $spasi, "", "R", 1);

        $pdf->Cell(2, $spasi, "", "L", 0);
        $pdf->Cell(10, $spasi, "Prodi", 0, 0);
        $pdf->Cell(34, $spasi, substr(": ".$status['prodi'], 0, 28), 0, 0);
        $pdf->Cell(2, $spasi, "", "R", 0);
        $pdf->Cell(3, $spasi, "");
        $pdf->Cell(2, $spasi, "", "L", 0);
        $pdf->Cell(10, $spasi, "Prodi", 0, 0);
        $pdf->Cell(34, $spasi, substr(": ".$status['prodi'], 0, 28), 0, 0);
        $pdf->Cell(2, $spasi, "", "R", 0);
        $pdf->Cell(3, $spasi, "");
        $pdf->Cell(2, $spasi, "", "L", 0);
        $pdf->Cell(10, $spasi, "Prodi", 0, 0);
        $pdf->Cell(34, $spasi, substr(": ".$status['prodi'], 0, 28), 0, 0);
        $pdf->Cell(2, $spasi, "", "R", 1);

        $pdf->Cell(48, $spasi, "", "LRB", 0);
        $pdf->Cell(3, $spasi, "");
        $pdf->Cell(48, $spasi, "", "LRB", 0);
        $pdf->Cell(3, $spasi, "");
        $pdf->Cell(48, $spasi, "", "LRB", 1);

        $pdf->Cell(48, $spasi, "", "LR", 0);
        $pdf->Cell(3, $spasi, "");
        $pdf->Cell(48, $spasi, "", "LR", 0);
        $pdf->Cell(3, $spasi, "");
        $pdf->Cell(48, $spasi, "", "LR", 1);

        $pdf->Cell(2, $spasi, "", "L", 0);
        $pdf->Cell(44, $spasi, "Tempel Foto 3x4 cm di sini", 0, 0);
        $pdf->Cell(2, $spasi, "", "R", 0);
        $pdf->Cell(3, $spasi, "");
        $pdf->Cell(2, $spasi, "", "L", 0);
        $pdf->Cell(44, $spasi, "Tempel Foto 3x4 cm di sini", 0, 0);
        $pdf->Cell(2, $spasi, "", "R", 0);
        $pdf->Cell(3, $spasi, "");
        $pdf->Cell(2, $spasi, "", "L", 0);
        $pdf->Cell(44, $spasi, "Tempel Foto 3x4 cm di sini", 0, 0);
        $pdf->Cell(2, $spasi, "", "R", 1);

        $pdf->Cell(48, 50, "", "LR", 0);
        $pdf->Cell(3, 50, "");
        $pdf->Cell(48, 50, "", "LR", 0);
        $pdf->Cell(3, 50, "");
        $pdf->Cell(48, 50, "", "LR", 1);

        $pdf->Cell(48, $spasi, "", "LRB", 0);
        $pdf->Cell(3, $spasi, "");
        $pdf->Cell(48, $spasi, "", "LRB", 0);
        $pdf->Cell(3, $spasi, "");
        $pdf->Cell(48, $spasi, "", "LRB", 1);

        $pdf->Ln(10);

        $pdf->AddPage();
        $pdf->SetLeftMargin(20);
        $pdf->SetFont('Times','B',14);
        $pdf->Ln(1);
        $spasi = 5;
        $pdf->Cell(170, $spasi, "PERSYARATAN PENDAFTARAN WISUDA UNILA",0,1,'C');
        $pdf->Cell(170, $spasi, "PERIODE ".strtoupper($attr[0])." T.A. ".$attr[1],0,1,'C');
        $pdf->SetFont('Times','',11);
        $pdf->Ln(5);
        $pdf->MultiCell(170, $spasi,'Yang bertanda tangan di bawah ini:', 0, 'L');
        $pdf->Ln(2);
        $pdf->Cell(70, $spasi,'Nama (sesuai Ijazah SLTA/Tanpa Gelar)', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(95, $spasi,$mhs->name, 0, 1, 'L');
        $pdf->Cell(70, $spasi,'NPM', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(95, $spasi, $mhs->npm, 0, 1, 'L');
        $pdf->Cell(70, $spasi,'Fakultas/Jurusan', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(95, $spasi, "MIPA/".$status['jurusan'], 0, 1, 'L');
        $pdf->Cell(70, $spasi,'Program Studi', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(95, $spasi, $status['prodi'], 0, 1, 'L');
        $pdf->Ln(2);
        $pdf->MultiCell(170, $spasi,'Memohon didaftar sebagai Calon Peserta Wisuda Universitas Lampung. Saya lampirkan persyaratan sebagai berikut :', 0, 'J');
        $pdf->Ln(2);
        $pdf->SetFont('Times','',11);
        $pdf->SetWidths(array(8,142,20));
        $pdf->SetAligns(array('C','L','C'));
        $pdf->SetBoldFont(array('','','B'));
        $pdf->SetSpacing(4.5);
        $pdf->Row(array("1", "Print Out Biodata On Line (terdiri atas : data mahasiswa, data Pribadi, data Disertasi/Tesis/Skripsi/ Tugas Akhir serta mengupload pas foto hitam putih) yang telah di isi dengan benar dan lengkap, serta telah di validasi pihak Fakultas", "(1 lembar)"));
        $pdf->Row(array("2", "Foto  hitam putih 3x4 cm dan ditempel pada tempat yang telah disediakan baik untuk Tingkat Universitas maupun Fakultas (diatas foto, tulis :  Nama, NPM, Fak/ Prodi )", "(6 lembar)"));
        $pdf->Row(array("3", "KTM", "(Asli)"));
        $pdf->Row(array("4", "Foto Copy Ijazah Terakhir", "(1 lembar)"));
        $pdf->Row(array("5", "Bukti Pembayaran UKT Asli dan fotocopy yang telah dilegalisir Wakil Dekan Bidang Umum dan Keuangan, dari Semester 1 s.d terakhir", "(1 set)"));
        $pdf->Row(array("6", "Nilai  EPT yang telah dilegalisir Pusat Bahasa Unila (berlaku untuk semua Pendaftar Calon Wisudawan sesuai SK. Rektor No: 78/H26/DT/2008).\nBerlaku 1 tahun sejak dikeluarkan Sertifikat  s.d. Pelaksanaan Wisuda;\nSkor sebelum angkatan 2007 minimal 400;\nSkor mulai angkatan 2007 dan seterusnya  minimal  450", "(1 lembar)"));
        $pdf->Row(array("7", "Surat Keterangan Bebas Pinjaman dari Perpustakaan Unila", "(Asli)"));
        $pdf->Row(array("8", "Surat Bukti Penyerahan Disertasi/Tesis/Skripsi/ Tugas Akhir dari Perpustakaan Unila", "(Asli)"));
        $pdf->Row(array("9", "Judul Disertasi/Tesis/Skripsi/ Tugas Akhir yang disetujui dan ditandatangani oleh Komisi Pembimbing dan Ketua Jurusan / Kaprodi", "(1 lembar)"));
        $pdf->Row(array("10", "Fotocopy Pengesahan Disertasi/Tesis/Skripsi/ Tugas Akhir, Kecuali bagi:\na. Fakultas Kedokteran diganti dengan F.Copy SK. Yudisium\nb. Jurusan Akuntansi S1 Fakultas Ekonomi diganti dengan Berita Acara Pendadaran", "(1 lembar)"));
        $pdf->Row(array("11", "Transkrip Nilai Matahari ", "(2 lembar)"));
        $pdf->Row(array("12", "Surat Pernyataan mengikuti Tata Tertib Upacara Wisuda di atas Materai Rp.6.000,-", "(1 lembar)"));
        $pdf->Row(array("13", "Arsip Fakultas : fotocopy persyaratan no. 1 dan no. 3 s/d no. 12, kecuali no.2 sesuai dengan ketentuan di atas atau disesuaikan dengan kebutuhan fakultas ybs.", "(1 set)"));

        $pdf->SetFont('Times','',11);
        $pdf->Ln(2);
        $pdf->MultiCell(170, $spasi,'Saya menyatakan bahwa semua keterangan/lampiran pernyataan yang diberikan seperti di atas adalah benar, apabila ternyata tidak benar, saya bersedia dibatalkan mengikuti Wisuda dan menerima sanksi akademik yang berlaku.', 0, 'J');
        $pdf->Ln(2);
        $pdf->SetX(130);
        $pdf->Cell(28, $spasi,'Bandar Lampung, '.$this->convert_date($data->created_at),0,1);
        $pdf->Cell(170, $spasi,'Menyetujui,',0,1);
        $pdf->Cell(58, $spasi,'Wakil Dekan');
        $pdf->Cell(60, $spasi,'Telah diperiksa kebenarannya oleh');
        $pdf->Cell(52, $spasi,'',0,1);
        $pdf->Cell(58, $spasi,'Bidang Akademik dan Kerjasama,');
        $pdf->Cell(60, $spasi,'Kasubbag Akademik FMIPA,');
        $pdf->Cell(52, $spasi,'Mhs. yang Mendaftar,',0,1);
        $pdf->Cell(118, $spasi,'');
        $pdf->Cell(60, $spasi,$pdf->Image("$data->ttd",$pdf->GetX()-3, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');
        $pdf->Ln(15);
        //wd, kasubag
        $wd2 = $this->layanan_model->get_tugas_tambahan_user('Wakil Dekan Bidang Akademik');
        $kasubag = $this->layanan_model->get_tugas_tambahan_user('Kepala Sub Bagian Akademik');
        if(empty($wd2)){$wd2_nip = "";}else{$wd2_nip = $wd2->nip_nik;}
        if(empty($kasubag)){$kasubag_nip = "";}else{$kasubag_nip = $kasubag->nip_nik;}

        $pdf->SetFont('Times','B',11);
        $pdf->Cell(58, $spasi, empty($wd2) ? "": $wd2->gelar_depan." ".$wd2->name.", ".$wd2->gelar_belakang );
        $pdf->Cell(60, $spasi, empty($kasubag) ? "": $kasubag->gelar_depan." ".$kasubag->name.", ".$kasubag->gelar_belakang);
        $pdf->Cell(52, $spasi, $mhs->name,0,1);
        $pdf->SetFont('Times','',11);
        $pdf->Cell(58, $spasi, "NIP. ".$wd2_nip);
        $pdf->Cell(60, $spasi, "NIP. ".$kasubag_nip);
        $pdf->Cell(52, $spasi, "NPM. ".$mhs->npm,0,1);

        $pdf->AddPage();
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);
        $pdf->SetFont('Times','B',14);
        $spasi = 6;
        $pdf->Ln(5);
        $pdf->Cell(150, $spasi, "SURAT PERNYATAAN",0,1,'C');
        $pdf->Cell(150, $spasi, "TATA TERTIB WISUDAWAN",0,1,'C');
        $pdf->SetFont('Times','',12);
        $pdf->Ln(10);
        $pdf->MultiCell(150, $spasi,'Yang bertanda tangan di bawah ini:', 0, 'J');
        $pdf->Ln(2);
        $pdf->Cell(45, $spasi,'Nama Lengkap', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$mhs->name, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'NPM', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $mhs->npm, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Fakultas', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, "Matematika dan Ilmu Pengetahuan Alam", 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Jurusan/Program Studi', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $status['jurusan'].'/'.$status['prodi'], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'No. Telepon/HP', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $mhs->mobile, 0, 1, 'L');

        $almt = $this->wilayah_model->get_desa_by_id($mhs->kelurahan_desa);

        $pdf->SetWidths(array(45, 5, 100));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Alamat Lengkap',':',$mhs->jalan." Kelurahan ".$almt->desa." Kecamatan ".$almt->kecamatan." ".$almt->kabupaten." Provinsi ".$almt->provinsi." ".$mhs->kode_pos));
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Dengan ini berjanji:', 0, 'J');
        $pdf->Ln(2);
        $pdf->SetWidths(array(8,142));
        $pdf->SetAligns(array('C','J'));
        $pdf->SetSpacing($spasi);
        $pdf->RowNoBorder(array('1.',"Akan patuh dan ta'at mengikuti upacara wisuda dari awal hingga akhir sesuai dengan peraturan dan ketentuan yang berlaku;"));
        $pdf->RowNoBorder(array('2.',"Wajib mengikuti gladi bersih dengan baik dan tertib;"));
        $pdf->RowNoBorder(array('3.',"Datang 15 menit sebelum upacara dimulai;"));
        $pdf->RowNoBorder(array('4.',"Memakai pakaian/atribut wisuda sesuai dengan ketentuan;"));
        $pdf->RowNoBorder(array('5.',"Bagi yang terlambat tidak diperkenankan mengikuti upacara wisuda, tetapi dapat mengambil Ijazah di Fakultas melalui Kasubbag Akademik;"));
        $pdf->RowNoBorder(array('6.',"Mengikuti tiap mata acara sesuai dengan aba-aba pembawa acara dengan tertib, hikmat, dan teratur;"));
        $pdf->RowNoBorder(array('7.',"Selama upacara berlangsung:"));
        $pdf->SetLeftMargin(40);
        $pdf->SetWidths(array(8,132));
        $pdf->SetAligns(array('C','J'));
        $pdf->RowNoBorder(array('a.',"tidak diperbolehkan membawa tustel/kamera/makanan dalam ruangan;"));
        $pdf->RowNoBorder(array('b.',"tidak diperkenankan hilir mudik;"));
        $pdf->RowNoBorder(array('c.',"tidak diperkenankan menggunakan/menghidupkan HP;"));
        $pdf->RowNoBorder(array('d.',"tidak diperkenankan membawa anak dibawah usia 12 tahun."));
        $pdf->Ln(5);
        $pdf->SetX(115);
        $pdf->Cell(65, $spasi,'Bandar Lampung, '.$this->convert_date($data->created_at),0,1);
        $pdf->Cell(75, $spasi,'');
        $pdf->Cell(65, $spasi,'Yang membuat Pernyataan,',0,1);
        $y_materai = $pdf->GetY();
        $pdf->Ln(20);
        $pdf->Cell(75, $spasi,'');
        $pdf->Cell(65, $spasi, $mhs->name,0,1);
        $pdf->SetX(115);
        $pdf->Cell(65, $spasi, "NPM. ".$mhs->npm,0,1);
        $pdf->Ln(5);
        $pdf->SetXY(105, $y_materai+3);
        $pdf->SetFont('Times','I',9);
        $pdf->MultiCell(20, 7, "Materai\nRp. 6000,-", 1, 'C');

        $pdf->AddPage();
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);
        $pdf->SetFont('Times','BU',16);
        $spasi = 6;
        $pdf->Ln(5);
        $pdf->Cell(150, $spasi, "TANDA TERIMA FORM WISUDA",0,1,'C');
        $pdf->SetFont('Times','',12);
        $pdf->Ln(10);
        $pdf->MultiCell(150, $spasi,'Sudah terima  Form Wisuda  berikut kelengkapannya dari mahasiswa:', 0, 'J');
        $pdf->Ln(2);
        $pdf->Cell(45, $spasi,'Nama Lengkap', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$mhs->name, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'NPM', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $mhs->npm, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Fakultas', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, "Matematika dan Ilmu Pengetahuan Alam", 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Jurusan/Program Studi', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $status['jurusan'].'/'.$status['prodi'], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'No. Telepon/HP', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $mhs->mobile, 0, 1, 'L');
        $pdf->SetWidths(array(45, 5, 100));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Alamat Lengkap',':',$mhs->jalan." Kelurahan ".$almt->desa." Kecamatan ".$almt->kecamatan." ".$almt->kabupaten." Provinsi ".$almt->provinsi." ".$mhs->kode_pos));
        $pdf->Ln(5);
        $pdf->SetX(110);
        $pdf->MultiCell(80, $spasi, "Bandar Lampung, ...........................\nPetugas Yang Menerima,\n\n\n\n\n.........................................................\nNIP/NIK. ........................................", 0, 'L');
        
        $pdf->Output('I','form_pendaftaran_wisuda.pdf');
    }

    function form_6($data,$meta)
    {
          /* ****************************************
        Form Name: fowm cuti
        Created By: 198701282018031001
        Created Date: 2019/08/02
        Last Modification: 2019/08/06
        * *****************************************/

        /*Edit by   :1617051107
        date        :30/01/2020 */

        /*Edit by   :1617051088
        date        :27/10/2020 */

        $cek_atr = $this->layanan_model->select_layanan_atribut_by_id($data->id_layanan_fakultas);
        $n=0;
        if(empty($cek_atr)){
            for($m=0;$m<=99;$m++){
                $attr[$m] = "";
            }
        }
        else{
            foreach($meta as $metas)
            {
                $attr[$n] = $metas->meta_value;
                $n++;
            }
        }
       
        $count = count($meta);
  
        $mhs = $this->user_model->get_mahasiswa_data_npm($data->npm);
        $status = $this->get_prodi_from_npm($data->npm);     
        $jurusan = $this->ta_model->get_jurusan($data->npm);

        $numPage = '/PM/MIPA/I/18';
        $spasi = 4.1;
        $spasi2 = 6;

        $kode = 0;
        $type = '';

        $pdf = new FPDF('P','mm',array(210,330));
        $pdf->setting_page_footer($numPage, $kode, $type);
        $pdf->setting_no_header(array(2));
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);

        $pdf->AddPage();
        $pdf->Ln(5);
        $pdf->SetFont('Times','',12);
        $pdf->Cell(17, $spasi, 'Nomor');
        $pdf->Cell(3, $spasi, ':');
        $pdf->Cell(70, $spasi, "          /UN26.17/DT/".date('Y'));
        $pdf->Cell(60, $spasi, 'Bandar Lampung, ..........................',0, 1, 'L');
        $pdf->Cell(17, $spasi, 'Lampiran');
        $pdf->Cell(3, $spasi, ':');
        $pdf->Cell(140, $spasi, "Satu Berkas",0, 1, 'L');
        $pdf->Cell(17, $spasi, 'Perihal');
        $pdf->Cell(3, $spasi, ':');
        $pdf->Cell(140, $spasi, "Permohonan Cuti",0, 1, 'L');
        $pdf->Ln(10);
        $pdf->SetFont('Times','B',12);
        $pdf->MultiCell(150, $spasi,'Yth. Rektor Universitas Lampung', 0, 'L');
        $pdf->MultiCell(150, $spasi,'di Bandar Lampung', 0, 'L');
        $pdf->Ln(10);
        $pdf->SetFont('Times','',12);
        $pdf->MultiCell(150, $spasi,'Bersama ini kami sampaikan berkas permohonan Cuti Akademik mahasiswa:', 0, 'L');
        $pdf->Ln(2);
        $pdf->Cell(45, $spasi,'Nama', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$mhs->name, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'NPM', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $mhs->npm, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Jurusan', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $status['jurusan'], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Fakultas', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,'Matematika dan Ilmu Pengetahuan Alam', 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Semester', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $status['semester'], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Lama Cuti', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $attr[0], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Tahun Akademik', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $attr[1], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'No. Telepon/HP', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $mhs->mobile, 0, 1, 'L');
        $almt = $this->wilayah_model->get_desa_by_id($mhs->kelurahan_desa);
        $pdf->SetWidths(array(45, 5, 100));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Alamat Lengkap',':',$mhs->jalan." Kelurahan ".$almt->desa." Kecamatan ".$almt->kecamatan." ".$almt->kabupaten." Provinsi ".$almt->provinsi." ".$mhs->kode_pos));
        $pdf->MultiCell(150, $spasi,'Sebagai bahan pertimbangan kami lampirkan:', 0, 'J');
        $pdf->Ln(2);
        $pdf->SetWidths(array(8,142));
        $pdf->SetAligns(array('C','J'));
        $pdf->SetSpacing($spasi);
        $pdf->RowNoBorder(array('1.',"Permohonan cuti dari mahasiswa yang bersangkutan;"));
        $pdf->RowNoBorder(array('2.',"Foto copy KTM;"));
        $pdf->RowNoBorder(array('3.',"Foto copy Pembayaran SPP terakhir."));
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Demikian permohonan kami, atas perhatian Bapak kami ucapkan terima kasih.', 0, 'J');
        $pdf->Ln(10);
        $pdf->SetX(120);
        $pdf->Cell(28, $spasi,'Dekan,',0,1);
        $pdf->Ln(20);
        $pdf->SetFont('Times','B',12);
        $pdf->SetX(120);

        $dekan = $this->layanan_model->get_tugas_tambahan_user('Dekan');
        $pdf->Cell(68, $spasi, $dekan->gelar_depan." ".$dekan->name.", ".$dekan->gelar_belakang,0,1);
        $pdf->SetFont('Times','',12);
        $pdf->SetX(120);
        $pdf->Cell(68, $spasi, "NIP. ".$dekan->nip_nik,0,1);

        $pdf->AddPage('P');
        $pdf->SetX(20);
        $pdf->Cell(17, $spasi, 'Lampiran');
        $pdf->Cell(3, $spasi, ':');
        $pdf->Cell(80, $spasi, "Satu Berkas",0,1,'L');
        $pdf->SetX(20);
        $pdf->Cell(17, $spasi, 'Perihal');
        $pdf->Cell(3, $spasi, ':');
        $pdf->Cell(140, $spasi, "Permohonan Cuti",0, 1, 'L');
        $pdf->Ln(5);
        $pdf->SetFont('Times','B',12);
        $pdf->MultiCell(150, $spasi,'Kepada Yth.', 0, 'L');
        $pdf->MultiCell(150, $spasi,'Dekan FMIPA Universitas Lampung', 0, 'L');
        $pdf->MultiCell(150, $spasi,'di Bandar Lampung', 0, 'L');
        $pdf->Ln(5);
        $pdf->SetFont('Times','',12);
        $pdf->MultiCell(150, $spasi,'Dengan hormat,', 0, 'L');
        $pdf->Ln(5);
        $pdf->MultiCell(150, $spasi,'Saya yang bertanda tangan di bawah ini:', 0, 'L');
        $pdf->Ln(2);
        $pdf->Cell(45, $spasi,'Nama', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$mhs->name, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'NPM', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $mhs->npm, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Jurusan', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $status['jurusan'], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Semester', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $status['semester'], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'No. Telepon/HP', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $mhs->mobile, 0, 1, 'L');
        $pdf->SetWidths(array(45, 5, 100));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Alamat Lengkap',':',$mhs->jalan." Kelurahan ".$almt->desa." Kecamatan ".$almt->kecamatan." ".$almt->kabupaten." Provinsi ".$almt->provinsi." ".$mhs->kode_pos));

        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Mengajukan permohonan Cuti Akademik:', 0, 'J');
        $pdf->Cell(45, $spasi,'Lama Cuti', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $attr[0], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Tahun Akademik', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $attr[1], 0, 1, 'L');
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Bersama ini saya lampirkan berkas yang diperlukan:', 0, 'J');
        $pdf->Ln(2);
        $pdf->SetWidths(array(8,142));
        $pdf->SetAligns(array('C','J'));
        $pdf->SetSpacing($spasi);
        $pdf->RowNoBorder(array('1.',"Foto copy KTM;"));
        $pdf->RowNoBorder(array('2.',"Foto copy Pembayaran SPP terakhir."));
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Demikian permohonan saya, atas perhatian Bapak kami ucapkan terima kasih.', 0, 'J');
        $pdf->Ln(5);
        $pdf->SetX(110);
        $pdf->Cell(60, $spasi,'Bandar Lampung, '.$this->convert_date($data->created_at),0,1);
        $pdf->Cell(90, $spasi,'Mengetahui,',0,1);
        $pdf->Cell(80, $spasi,'Orangtua Mahasiswa,');
        $pdf->Cell(60, $spasi,'Pemohon,',0,1);
        $y_materai = $pdf->GetY();
        $pdf->Ln(20);
        $pdf->Cell(80, $spasi,$attr[2]);
        $pdf->Cell(60, $spasi, $mhs->name,0,1);
        $pdf->SetX(110);
        $pdf->Cell(60, $spasi, "NPM. ".$mhs->npm,0,1);
        $pdf->Ln(5);
        $pdf->Cell(60, $spasi, "Menyetujui,");
        $pdf->Cell(20, $spasi, "");
        $pdf->Cell(60, $spasi, "Mengetahui,",0,1);
        $pdf->Cell(60, $spasi, "Ketua Jurusan ".$jurusan.",");
        $pdf->Cell(20, $spasi, "");
        $pdf->Cell(60, $spasi, "Pembimbing Akademik,",0,1);
        $pdf->Ln(20);

        $pa = $this->user_model->get_dosen_pa_by_npm($data->npm);
        $kajur = $this->user_model->get_kajur_by_npm($data->npm);
        if(empty($kajur)){
            $kajur_name = "";
            $kajur_nip = "";
        }
        else{
            $kajur_name = $kajur->gelar_depan." ".$kajur->name.", ".$kajur->gelar_belakang;
            $kajur_nip = $kajur->nip_nik;            
        }
        $pdf->Cell(60, $spasi, $kajur_name);
        $pdf->Cell(20, $spasi, "");
        $pdf->Cell(60, $spasi, $pa->gelar_depan." ".$pa->name.", ".$pa->gelar_belakang,0,1);
        $pdf->Cell(60, $spasi, "NIP. ".$kajur_nip);
        $pdf->Cell(20, $spasi, "");
        $pdf->Cell(60, $spasi, "NIP. ".$pa->nip_nik,0,1);
        $pdf->SetXY(100, $y_materai+3);
        $pdf->SetFont('Times','I',9);
        $pdf->MultiCell(20, 7, "Materai\nRp. 6000,-", 1, 'C');

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
        $pdf->Row(array('1',"Surat Permohonan Cuti Akademik beserta alasan (tandatangan di atas meterai Rp.6.000,-",'1 lbr.',''));
        $pdf->Row(array('2',"Copy pembayaran SPP/UKT semester berjalan terlegalisir",'1 lbr.',''));
        $pdf->Row(array('3',"Copy : KTM (dilegalisir) & e-KTP dalam satu lembar A4",'1 lbr.',''));

        $pdf->Output('I','form_cuti.pdf');
    }

    function form_7($data,$meta)
    {
        /* ****************************************
        Form Name: form perpanjangan studi
        Created By: 198701282018031001
        Created Date: 2019/08/02
        Last Modification: 2019/08/06
        * *****************************************/

        /*Edit by   :1617051107
        date        :30/01/2020 */

        /*Edit by   :1617051088
        date        :27/10/2020 */

        $cek_atr = $this->layanan_model->select_layanan_atribut_by_id($data->id_layanan_fakultas);
        $n=0;
        if(empty($cek_atr)){
            for($m=0;$m<=99;$m++){
                $attr[$m] = "";
            }
        }
        else{
            foreach($meta as $metas)
            {
                $attr[$n] = $metas->meta_value;
                $n++;
            }
        }
       
        $count = count($meta);
  
        $mhs = $this->user_model->get_mahasiswa_data_npm($data->npm);
        $status = $this->get_prodi_from_npm($data->npm);     
        $jurusan = $this->ta_model->get_jurusan($data->npm);
        $pa = $this->user_model->get_dosen_pa_by_npm($data->npm);
        $kajur = $this->user_model->get_kajur_by_npm($data->npm);
        $pb_id = $this->ta_model->get_ta_aktif_npm($data->npm);
        $pb = $this->user_model->get_dosen_data($pb_id->pembimbing1);

        $numPage = '/PM/MIPA/I/25';
        $spasi = 4.5;

        $kode = 0;
        $type = '';

        $pdf = new FPDF('P','mm',array(210,330));
        $pdf->setting_page_footer($numPage, $kode, $type);
        $pdf->setting_no_header(array(1));

        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(15);
        $pdf->AddPage();
        $pdf->SetFont('Times','',12);
        $pdf->SetX(20);
        $pdf->Cell(17, $spasi, 'Perihal');
        $pdf->Cell(3, $spasi, ':');
        $pdf->Cell(140, $spasi, "Permohonan Perpanjangan Studi",0, 1, 'L');
        $pdf->SetX(20);
        $pdf->Cell(17, $spasi, 'Lampiran');
        $pdf->Cell(3, $spasi, ':');
        $pdf->Cell(80, $spasi, "Satu Berkas",0, 1, 'L');
        $pdf->Ln(5);
        $pdf->MultiCell(150, $spasi,'Kepada Yth.', 0, 'L');
        $pdf->SetFont('Times','B',12);
        $pdf->MultiCell(150, $spasi,'Dekan FMIPA Universitas Lampung', 0, 'L');
        $pdf->SetFont('Times','',12);
        $pdf->MultiCell(150, $spasi,'di Bandar Lampung', 0, 'L');
        $pdf->Ln(5);
        $pdf->MultiCell(150, $spasi,'Dengan hormat,', 0, 'L');
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Saya yang bertanda tangan di bawah ini:', 0, 'L');
        $pdf->Ln(2);

        $pdf->Cell(45, $spasi,'Nama', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$mhs->name, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'NPM', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $mhs->npm, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Fakultas', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,'Matematika dan Ilmu Pengetahuan Alam', 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Jurusan/Program Studi', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $status['jurusan'].'/'.$status['prodi'], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Semester', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $status['semester'], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Jumlah SKS', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $attr[0], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'IPK', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $attr[1], 0, 1, 'L');
        $almt = $this->wilayah_model->get_desa_by_id($mhs->kelurahan_desa);
        $pdf->SetWidths(array(45, 5, 100));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Alamat Lengkap',':',$mhs->jalan." Kelurahan ".$almt->desa." Kecamatan ".$almt->kecamatan." ".$almt->kabupaten." Provinsi ".$almt->provinsi." ".$mhs->kode_pos));
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Mengajukan permohonan Perpanjangan Masa Studi selama satu semester.  Jika dalam satu semester, saya belum menyelesaikan Tugas Akhir, maka saya bersedia diputus studi (DO Akademik) dari FMIPA Universitas Lampung.', 0, 'J');
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Sebagai bahan pertimbangan bersama ini saya lampirkan:', 0, 'J');
        $pdf->Ln(2);

        $pdf->SetWidths(array(8,142));
        $pdf->SetAligns(array('C','J'));
        $pdf->SetSpacing($spasi);
        $sm = "Hasil";
        if($status['strata'] == 'D3'){
            $draf = "Tugas Akhir";
            $sm = "Tugas Akhir";
        }elseif($status['strata'] == 'S2'){
            $draf = "Tesis";
        }elseif($status['strata'] == 'S3'){
            $draf = "Disertasi";
        }else{
            $draf = "Skripsi";
        }
        $pdf->RowNoBorder(array('1.',"Fotocopy Berita Acara Seminar ".$sm.";"));
        $pdf->RowNoBorder(array('2.',"Transkrip Akademik terakhir;"));
        $pdf->RowNoBorder(array('3.',"Fotocopy KTM;"));

        $pdf->RowNoBorder(array('4.',"Fotocopy draft ".$draf.";"));
        $pdf->RowNoBorder(array('5.',"Foto Copy Bukti Pembayaran UKT terakhir."));
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Demikian surat permohonan saya, atas perhatian dan kebijaksanaan Bapak saya ucapkan terima kasih.', 0, 'J');
        $pdf->Ln(5);
        $pdf->SetX(115);
        $pdf->Cell(65, $spasi,'Bandar Lampung, '.$this->convert_date($data->created_at),0,1);
        $pdf->Cell(85, $spasi,'');
        $pdf->Cell(65, $spasi,'Mahasiswa Pemohon,',0,1);
        $y_materai = $pdf->GetY();
        $pdf->Ln(20);
        $pdf->Cell(85, $spasi,'');
        $pdf->Cell(65, $spasi, $mhs->name,0,1);
        $pdf->SetX(115);
        $pdf->Cell(65, $spasi, "NPM. ".$mhs->npm,0,1);
        $pdf->Ln(5);
        $pdf->Cell(60, $spasi, "");
        $pdf->Cell(25, $spasi, "Mengetahui,", 0, 0, "C");
        $pdf->Cell(60, $spasi, "",0,1);
        $pdf->Cell(60, $spasi, "Dosen Pembimbing Akademik,");
        $pdf->Cell(25, $spasi, "");
        $pdf->Cell(60, $spasi, "Pembimbing Utama ".$draf.",",0,1);
        $pdf->Ln(20);
        $pdf->Cell(60, $spasi, $pa->gelar_depan." ".$pa->name.", ".$pa->gelar_belakang);
        $pdf->Cell(25, $spasi, "");
        $pdf->Cell(60, $spasi, $pb->gelar_depan." ".$pb->name.", ".$pb->gelar_belakang,0,1);
        $pdf->Cell(60, $spasi, "NIP. ".$pa->nip_nik);
        $pdf->Cell(25, $spasi, "");
        $pdf->Cell(60, $spasi, "NIP. ".$pb->nip_nik,0,1);
        $pdf->Ln(5);
        $pdf->Cell(55, $spasi, "");
        $pdf->Cell(40, $spasi, "Ketua Jurusan ".$jurusan.",",0,0,'C');
        $pdf->Cell(50, $spasi, "",0,1);
        $pdf->Ln(20);
        $pdf->Cell(55, $spasi, "");
        $pdf->Cell(40, $spasi,  $kajur->gelar_depan." ".$kajur->name.", ".$kajur->gelar_belakang,0,0,'C');
        $pdf->Cell(50, $spasi, "",0,1);
        $pdf->Cell(55, $spasi, "");
        $pdf->Cell(40, $spasi, "NIP. ". $kajur->nip_nik,0,0,'C');
        $pdf->Cell(50, $spasi, "",0,1);
        $pdf->SetXY(105, $y_materai+3);
        $pdf->SetFont('Times','I',9);
        $pdf->MultiCell(20, 7, "Materai\nRp. 6000,-", 1, 'C');

        $spasi = 6;
        $pdf->AddPage();
        $pdf->Ln(5);
        $pdf->SetFont('Times','',12);
        $pdf->Cell(17, $spasi, 'Nomor');
        $pdf->Cell(3, $spasi, ':');
        $pdf->Cell(70, $spasi, "          /UN26.17/DT/".date('Y'));
        $pdf->Cell(60, $spasi, 'Bandar Lampung, ..........................',0, 1, 'L');
        $pdf->Cell(17, $spasi, 'Lampiran');
        $pdf->Cell(3, $spasi, ':');
        $pdf->Cell(140, $spasi, "Satu Berkas",0, 1, 'L');
        $pdf->Cell(17, $spasi, 'Perihal');
        $pdf->Cell(3, $spasi, ':');
        $pdf->Cell(140, $spasi, "Permohonan Perpanjangan Masa Studi",0, 1, 'L');
        $pdf->Ln(10);
        $pdf->SetFont('Times','B',12);
        $pdf->MultiCell(150, $spasi,'Yth. Wakil Rektor Bidang Akademik', 0, 'L');
        $pdf->MultiCell(150, $spasi,'Universitas Lampung', 0, 'L');
        $pdf->Ln(10);
        $pdf->SetFont('Times','',12);
        $pdf->MultiCell(150, $spasi,'Sehubungan dengan permohonan perpanjangan masa studi mahasiswa:', 0, 'L');
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

        $wd2 = $this->user_model->get_wd_akademik();
        if(empty($wd2)){
            $wd2_name = "";
            $wd2_nip = "";
        }
        else{
            $wd2_name = $wd2->gelar_depan." ".$wd2->name.", ".$wd2->gelar_belakang;
            $wd2_nip = $wd2->nip_nik;
        }

        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Maka dengan ini kami kirimkan persyaratan permohonan:', 0, 'J');
        $pdf->Ln(2);
        $pdf->SetWidths(array(8,142));
        $pdf->SetAligns(array('C','J'));
        $pdf->SetSpacing($spasi);
        $pdf->RowNoBorder(array('1.',"Permohonan perpanjangan masa studi dari mahasiswa yang bersangkutan;"));
        $pdf->RowNoBorder(array('2.',"Fotocopy Berita Acara Seminar ".$sm.";"));
        $pdf->RowNoBorder(array('3.',"Fotocopy draft ".$draf.";"));
        $pdf->RowNoBorder(array('4.',"Transkrip Akademik terakhir;"));
        $pdf->RowNoBorder(array('5.',"Fotocopy KTM;"));
        $pdf->RowNoBorder(array('6.',"Foto Copy Bukti Pembayaran UKT terakhir."));
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Untuk diproses serta dapat diterbitkan Surat Izin Perpanjangan Masa Studi bagi mahasiswa yang bersangkutan.', 0, 'J');
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Demikian permohonan kami, atas perhatian Bapak kami ucapkan terima kasih.', 0, 'J');
        $pdf->Ln(10);
        $pdf->SetX(120);
        $pdf->Cell(28, $spasi,'a.n. Dekan,',0,1);
        $pdf->SetX(120);
        $pdf->Cell(28, $spasi,'Wakil Dekan Bidang',0,1);
        $pdf->SetX(120);
        $pdf->Cell(28, $spasi,'Akademik dan Kerjasama',0,1);
        $pdf->Ln(20);
        $pdf->SetFont('Times','B',12);
        $pdf->SetX(120);
        $pdf->Cell(68, $spasi, $wd2_name,0,1);
        $pdf->SetFont('Times','',12);
        $pdf->SetX(120);
        $pdf->Cell(68, $spasi, "NIP. ".$wd2_nip,0,1);

        //LEMBAR VERIFIKASI
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
        $pdf->Row(array('1',"Surat Permohonan Perpanjangan Masa Studi dari mahasiswa yang bersangkutan ditandatangani di atas meterai Rp.6.000,-, diketahui; Dosen PA, Pembimbing Tugas Akhir/Skripsi/Tesis/Disertasi, Kajur",'1 lbr.',''));
        $pdf->Row(array('2',"Transkrip Akademik",'1 lbr.',''));
        $pdf->Row(array('3',"Copy Berita Acara Seminar Laporan Tugas Akhir/Skripsi/Tesis/Disertasi",'1 lbr.',''));
        $pdf->Row(array('4',"Copy draft Laporan Tugas Akhir/Skripsi/Tesis/Disertasi",'1 lbr.',''));
        $pdf->Row(array(5,"Copy : KTM (dilegalisir) & e-KTP dalam satu lembar A4",'1 lbr.',''));
        $pdf->Row(array('6',"Copy pembayaran SPP/UKT semester berjalan terlegalisir",'1 lbr.',''));

        $pdf->Output('I','form_perpanjangan_masa_studi.pdf');
    }

    function form_8()
    {
        force_download(FCPATH.'/assets/layanan/form-studi-lanjut.doc', null);
    }

    function form_9($data,$meta)
    {
        /* ****************************************
        Form Name: form studi terbimbing
        Created By: 198701282018031001
        Created Date: 2019/08/02
        Last Modification: 2019/08/06
        * *****************************************/

        /*Edit by   :1617051107
        date        :30/01/2020 */

        /*Edit by   :1617051088
        date        :27/10/2020 */

        $cek_atr = $this->layanan_model->select_layanan_atribut_by_id($data->id_layanan_fakultas);
        $n=0;
        if(empty($cek_atr)){
            for($m=0;$m<=99;$m++){
                $attr[$m] = "";
            }
        }
        else{
            foreach($meta as $metas)
            {
                $attr[$n] = $metas->meta_value;
                $n++;
            }
        }
       
        $count = count($meta);
  
        $mhs = $this->user_model->get_mahasiswa_data_npm($data->npm);
        $status = $this->get_prodi_from_npm($data->npm);     
        $jurusan = $this->ta_model->get_jurusan($data->npm);
        $pa = $this->user_model->get_dosen_pa_by_npm($data->npm);
        $kajur = $this->user_model->get_kajur_by_npm($data->npm);

        $numPage = '/SOP/MIPA/I/05';
        $spasi = 4.5;
        $spasi2 = 6;
        $kode = 0;
        $type = '';

        $pdf = new FPDF('P','mm',array(210,330));
        $pdf->setting_page_footer($numPage, $kode, $type);
        $pdf->setting_no_header(array(1,3));

        $pdf->set_header("header jurusan");
        $pdf->set_header_jur(strtoupper($jurusan));
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(15);
        $pdf->AddPage();
        $pdf->Ln(5);
        $pdf->SetFont('Times','',12);
        $pdf->Cell(17, $spasi, 'Perihal');
        $pdf->Cell(3, $spasi, ':');
        $pdf->SetFont('Times','B',12);
        $pdf->Cell(140, $spasi, "Studi Terbimbing",0, 1, 'L');
        $pdf->Ln(10);
        $pdf->MultiCell(150, $spasi,'Kepada Yth.', 0, 'L');
        $pdf->SetFont('Times','B',12);
        $pdf->MultiCell(150, $spasi,'Ketua Jurusan '.$jurusan, 0, 'L');
        $pdf->MultiCell(150, $spasi,'FMIPA Universitas Lampung', 0, 'L');
        $pdf->SetFont('Times','',12);
        $pdf->MultiCell(150, $spasi,'di Tempat', 0, 'L');
        $pdf->Ln(10);
        $pdf->SetFont('Times','',12);
        $pdf->MultiCell(150, $spasi,'Dengan hormat,', 0, 'L');
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Yang bertandatangan dibawah ini:', 0, 'L');
        $pdf->Ln(2);

        $pdf->Cell(45, $spasi,'Nama', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$attr[0], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'NIP', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $attr[1], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Dosen Pj. Mata Kuliah', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $attr[2], 0, 1, 'L');
        $pdf->Ln(2);

        $pdf->MultiCell(150, $spasi,'Dengan ini memberikan izin kepada mahasiswa berikut:', 0, 'L');
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
        $pdf->Cell(45, $spasi,'Semester', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $status['semester'], 0, 1, 'L');
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Untuk mengikuti kuliah terbimbing:', 0, 'J');
        $pdf->Ln(2);

        $pdf->Cell(45, $spasi,'Mata Kuliah*', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $attr[2], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Kopel / SKS', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $attr[3]." / ".$attr[4], 0, 1, 'L');
        $pdf->Ln(2);

        $pdf->MultiCell(150, $spasi,'dikarenakan mahasiswa tersebut sedang menyelesaikan penyusunan Tugas Akhir/Skripsi sesuai dengan Peraturan Akademik yang berlaku di Universitas Lampung.', 0, 'J');
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Demikian yang dapat saya sampaikan, atas perhatian dan kerjasama Bapak diucapkan terima kasih.', 0, 'J');
        $pdf->Ln(10);
        $pdf->SetX(115);
        $pdf->Cell(65, $spasi,'Bandar Lampung, '.$this->convert_date($data->created_at),0,1);
        $pdf->Cell(150, $spasi, "Mengetahui,", 0, 1);
        $pdf->Cell(60, $spasi, "Dosen Pembimbing Akademik,");
        $pdf->Cell(25, $spasi, "");
        $pdf->Cell(60, $spasi, "Mahasiswa Pemohon,",0,1);
        $pdf->Cell(85, $spasi, "");
        $pdf->Cell(60, $spasi,$pdf->Image("$data->ttd",$pdf->GetX()-3, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');
        $pdf->Ln(20);
        $pdf->Cell(60, $spasi, $pa->gelar_depan." ".$pa->name.", ".$pa->gelar_belakang);
        $pdf->Cell(25, $spasi, "");
        $pdf->Cell(60, $spasi, $mhs->name,0,1);
        $pdf->Cell(60, $spasi, "NIP. ".$pa->nip_nik);
        $pdf->Cell(25, $spasi, "");
        $pdf->Cell(60, $spasi, "NPM. ".$mhs->npm,0,1);
        $pdf->Ln(5);
        $pdf->Cell(55, $spasi, "");
        $pdf->Cell(40, $spasi, "Menyetujui,",0,0,'C');
        $pdf->Cell(50, $spasi, "",0,1);
        $pdf->Cell(55, $spasi, "");
        $pdf->Cell(40, $spasi, "Dosen Pj. Mata Kuliah,",0,0,'C');
        $pdf->Cell(50, $spasi, "",0,1);
        $pdf->Ln(20);
        $pdf->Cell(55, $spasi, "");
        $pdf->Cell(40, $spasi, $attr[0],0,0,'C');
        $pdf->Cell(50, $spasi, "",0,1);
        $pdf->Cell(55, $spasi, "");
        $pdf->Cell(40, $spasi, "NIP. ".$attr[1],0,0,'C');
        $pdf->Cell(50, $spasi, "",0,1);
        $pdf->Ln(13);
        $pdf->SetFont('Times','B',10);
        $pdf->Cell(150, $spasi, "Catatan:",0,1,'L');
        $pdf->SetFont('Times','',10);
        $pdf->SetWidths(array(3,138));
        $pdf->SetAligns(array('C','L'));
        $pdf->SetSpacing(3.5);
        $pdf->RowNoBorder(array('*',"mata kuliah yang diajukan dinyatakan tidak lulus dengan huruf  mutu \"E\", sesuai dengan Peraturan Akademik Universitas lampung Tahun 2016 Pasal 26 Ayat (3) dan Pasal 54 ayat (2)"));


        $spasi = 5;
        $pdf->AddPage();
        $pdf->Ln(5);
        $pdf->SetFont('Times','',12);
        $pdf->Cell(17, $spasi, 'Nomor');
        $pdf->Cell(3, $spasi, ':');
        $pdf->Cell(70, $spasi, "........../UN26.17..../DT/20....");
        $pdf->Cell(60, $spasi, 'Bandar Lampung, ..........................',0, 1, 'L');
        $pdf->Cell(17, $spasi, 'Lampiran');
        $pdf->Cell(3, $spasi, ':');
        $pdf->Cell(140, $spasi, "1 Berkas",0, 1, 'L');
        $pdf->Cell(17, $spasi, 'Perihal');
        $pdf->Cell(3, $spasi, ':');
        $pdf->SetFont('Times','B',12);
        $pdf->Cell(140, $spasi, "Permohonan Studi Terbimbing",0, 1, 'L');
        $pdf->Ln(10);
        $pdf->MultiCell(150, $spasi,'Kepada Yth.', 0, 'L');
        $pdf->SetFont('Times','B',12);
        $pdf->MultiCell(150, $spasi,'Dekan FMIPA Universitas Lampung', 0, 'L');
        $pdf->SetFont('Times','',12);
        $pdf->MultiCell(150, $spasi,'di Tempat', 0, 'L');
        $pdf->Ln(10);
        $pdf->SetFont('Times','',12);
        $pdf->MultiCell(150, $spasi,'Dengan hormat,', 0, 'L');
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Sehubungan dengan pemberian izin pelaksanaan studi terbimbing dari Dosen Pj. Mata Kuliah yang bersangkutan untuk mahasiswa berikut:', 0, 'L');
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
        $pdf->Cell(45, $spasi,'Semester', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $status['semester'], 0, 1, 'L');
        $pdf->Ln(2);

        $pdf->MultiCell(150, $spasi,'dan dikarenakan mahasiswa tersebut sedang menyelesaikan penyusunan Tugas Akhir/Skripsi, mohon kiranya Bapak/Ibu berkenan memproses permohonan studi terbimbing tersebut sesuai dengan Peraturan Akademik yang berlaku di Universitas Lampung.', 0, 'J');
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Sebagai bahan pertimbangan, berikut kami lampirkan:', 0, 'J');
        $pdf->Ln(2);
        $pdf->SetWidths(array(8,142));
        $pdf->SetAligns(array('C','J'));
        $pdf->SetSpacing($spasi);
        $pdf->RowNoBorder(array('1.',"Surat permohonan studi terbimbing dari mahasiswa bersangkutan;"));
        $pdf->RowNoBorder(array('2.',"Transkrip Akademik terakhir;"));
        $pdf->RowNoBorder(array('3.',"KHS yang memuat huruf mutu dari mata kuliah yang akan diajukan;"));
        $pdf->RowNoBorder(array('4.',"Fotocopy bukti pembayaran UKT terakhir;"));
        $pdf->RowNoBorder(array('5.',"Fotocopy berita acara Seminar Hasil / Seminar Tugas Akhir."));
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Demikian permohonan kami, atas perhatian Bapak kami ucapkan terima kasih.', 0, 'J');
        $pdf->Ln(10);

        $pdf->SetX(120);
        $pdf->Cell(28, $spasi,"Ketua Jurusan ".$jurusan.",",0,1);
        $pdf->Ln(20);
        $pdf->SetFont('Times','B',12);
        $pdf->SetX(120);
        //kajur
        if(empty($kajur)){
            $kajur_name = "";
            $kajur_nip = "";
        }
        else{
            $kajur_name = $kajur->gelar_depan." ".$kajur->name.", ".$kajur->gelar_belakang;
            $kajur_nip = $kajur->nip_nik;
        }

        $pdf->Cell(68, $spasi, $kajur_name,0,1);
        $pdf->SetFont('Times','',12);
        $pdf->SetX(120);
        $pdf->Cell(68, $spasi, "NIP. ".$kajur_nip,0,1);
        $pdf->Ln(30);
        $pdf->SetFont('Times','B',10);
        $pdf->Cell(150, $spasi, "Catatan:",0,1,'L');
        $pdf->SetFont('Times','',10);
        $pdf->SetWidths(array(3,138));
        $pdf->SetAligns(array('C','L'));
        $pdf->SetSpacing(3.5);
        $pdf->RowNoBorder(array('*',"mata kuliah yang diajukan dinyatakan tidak lulus dengan huruf  mutu \"E\", sesuai dengan Peraturan Akademik Universitas lampung Tahun 2016 Pasal 26 Ayat (3) dan Pasal 54 ayat (2)"));

        $pdf->Output('I','form_studi_terbimbing.pdf');
    }

    function form_10($data,$meta)
    {
         /* ****************************************
        Form Name: form ttd dekan
        Created By: 198701282018031001
        Created Date: 2019/08/02
        Last Modification: 2019/08/06
        * *****************************************/

        /*Edit by   :1617051107
        date        :30/01/2020 */

        /*Edit by   :1617051088
        date        :27/10/2020 */

        $cek_atr = $this->layanan_model->select_layanan_atribut_by_id($data->id_layanan_fakultas);
        $n=0;
        if(empty($cek_atr)){
            for($m=0;$m<=99;$m++){
                $attr[$m] = "";
            }
        }
        else{
            foreach($meta as $metas)
            {
                $attr[$n] = $metas->meta_value;
                $n++;
            }
        }
       
        $count = count($meta);

        $numPage = '';
        $kode = 0;
        $type = '';
        $spasi = 6;
        $mhs = $this->user_model->get_mahasiswa_data_npm($data->npm);
        $status = $this->get_prodi_from_npm($data->npm);     
        $jurusan = $this->ta_model->get_jurusan($data->npm);

        $pdf = new FPDF('P','mm',array(210,330));
        $pdf->setting_page_footer($numPage, $kode, $type);

        // LEMBAR VERIFIKASI
        $pdf->AddPage('P');
        $pdf->SetFillColor(205,205,205);
        $pdf->SetLeftMargin(10);
        $pdf->SetFont('Times','',12);
        $pdf->Ln(1);

        // CAP FAKULTAS
        $pdf->Ln(5);
        // Title
        $pdf->TableTitle('VERIFIKASI PERSYARATAN LAYANAN');
        // Header
        $pdf->TableHeader1(array('MIPA/'.$jurusan, $mhs->name));
        $pdf->TableHeader2(array('TANDA TANGAN DEKAN', $mhs->npm));
        // SubHeader
        $pdf->SetWidths(array(8,69,13,13,20,30,22,15));
        $pdf->SubHeader(array('No', 'Jenis Persyaratan', 'Sah', 'Benar', 'Lengkap', 'Tgl Penyelesaian', 'Verifikasi', 'Paraf'));

        // Isi
        $pdf->SetWidths(array(8,69,13,13,20,30,22,15));
        $pdf->SetAligns(array('C','L'));
        $pdf->SetSpacing(5);
        $pdf->Row(array('1',"Skripsi",'','','','','',''));
        $pdf->Row(array('2',"Tanda Tangan Kajur",'','','','','',''));
        $pdf->Row(array('3',"Tanda Tangan Pembimbing dan Penguji",'','','','','',''));
        $pdf->Row(array('4',"Surat Keterangan Keabsahan Tanda Tangan Tesis/Skripsi/TA",'','','','','',''));
        $pdf->SetLeftMargin(5);
        $pdf->Ln(5);
        $pdf->Cell(200, $spasi, "------------------------------------------------------------------------------------------------------------------------------------------",0,1,'C');
        $pdf->Ln(5);
        $pdf->SetLeftMargin(10);

        // Title
        $pdf->TableTitle('VERIFIKASI PERSYARATAN LAYANAN');
        // Header
        $pdf->TableHeader1(array('MIPA/'.$jurusan, $mhs->name));
        $pdf->TableHeader2(array('TANDA TANGAN DEKAN', $mhs->npm));
        // SubHeader
        $pdf->SetWidths(array(8,69,13,13,20,30,22,15));
        $pdf->SubHeader(array('No', 'Jenis Persyaratan', 'Sah', 'Benar', 'Lengkap', 'Tgl Penyelesaian', 'Verifikasi', 'Paraf'));

        // Isi
        $pdf->SetWidths(array(8,69,13,13,20,30,22,15));
        $pdf->SetAligns(array('C','L'));
        $pdf->SetSpacing(5);
        $pdf->Row(array('1',"Dokumen yang telah ditandatangani oleh pejabat yang berwenang\n ",'','','','','',''));

        $pdf->Output('I','form_ttd_dekan.pdf');
    }

    function form_11($data,$meta)
    {
         /* ****************************************
        Form Name: form khs
        Created By: 198701282018031001
        Created Date: 2019/08/02
        Last Modification: 2019/08/06
        * *****************************************/

        /*Edit by   :1617051107
        date        :30/01/2020 */

        /*Edit by   :1617051088
        date        :27/10/2020 */

        $cek_atr = $this->layanan_model->select_layanan_atribut_by_id($data->id_layanan_fakultas);
        $n=0;
        if(empty($cek_atr)){
            for($m=0;$m<=99;$m++){
                $attr[$m] = "";
            }
        }
        else{
            foreach($meta as $metas)
            {
                $attr[$n] = $metas->meta_value;
                $n++;
            }
        }
       
        $count = count($meta);

        $numPage = '/PM/MIPA/I/......';
        $kode = 0;
        $type = '';
        $spasi = 6;
        $mhs = $this->user_model->get_mahasiswa_data_npm($data->npm);
        $status = $this->get_prodi_from_npm($data->npm);     
        $jurusan = $this->ta_model->get_jurusan($data->npm);

        $pdf = new FPDF('P','mm',array(210,330));
        $pdf->setting_page_footer($numPage, $kode, $type);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);
        $pdf->AddPage();
        $pdf->Ln(5);
        $pdf->SetFont('Times','BU',14);
        $pdf->Cell(150, $spasi, "BUKTI PENERIMAAN PENANDATANGANAN DOKUMEN",0,1,'C');
        $pdf->SetFont('Times','',12);
        $pdf->Ln(10);
        $pdf->Cell(45, $spasi,'Nama Lengkap', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$mhs->name, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'NPM', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $mhs->npm, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Jurusan/Program Studi', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $status['jurusan'].'/'.$status['prodi'], 0, 1, 'L');
        $pdf->Ln(5);
        $pdf->SetWidths(array(10,70,30,40));
        $pdf->SetAligns(array('C','C','C','C'));
        $pdf->SetBoldFont(array('B','B','B','B'));
        $pdf->SetSpacing($spasi);
        $pdf->Row(array("NO", "JENIS DOKUMEN", "JUMLAH", "KETERANGAN"));
        $pdf->SetBoldFont(array());
        $pdf->SetAligns(array('C','L','C'));
        $pdf->Row(array("1.", "Kartu Hasil Studi (KHS)", "3 Lembar", ""));
        $pdf->Ln(10);

        $pdf->SetX(110);
        $pdf->MultiCell(80, $spasi, "Bandar Lampung, ...........................\nPetugas Yang Menerima,\n\n\n\n\nSugiharto\nNIP. 196903172008101001", 0, 'L');

        //LEMBAR VERIFIKASI
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
        $pdf->Row(array('1',"Print out KRS, KHS, Transkrip mahasiswa dari SIAKAD",'@ max. 5 lbr.',''));
        $pdf->Row(array('2',"Untuk KRS dan/ KHS harus sudah ditandatangani oleh dosen Pembimbing Akademik",'',''));

        $pdf->Output('I','form_ttd_khs.pdf');
    }

    function form_12($data,$meta)
    {
         /* ****************************************
        Form Name: form krs
        Created By: 198701282018031001
        Created Date: 2019/08/02
        Last Modification: 2019/08/06
        * *****************************************/

        /*Edit by   :1617051107
        date        :30/01/2020 */

        /*Edit by   :1617051088
        date        :27/10/2020 */

        $cek_atr = $this->layanan_model->select_layanan_atribut_by_id($data->id_layanan_fakultas);
        $n=0;
        if(empty($cek_atr)){
            for($m=0;$m<=99;$m++){
                $attr[$m] = "";
            }
        }
        else{
            foreach($meta as $metas)
            {
                $attr[$n] = $metas->meta_value;
                $n++;
            }
        }
       
        $count = count($meta);

        $numPage = '/PM/MIPA/I/......';
        $kode = 0;
        $type = '';
        $spasi = 6;
        $mhs = $this->user_model->get_mahasiswa_data_npm($data->npm);
        $status = $this->get_prodi_from_npm($data->npm);     
        $jurusan = $this->ta_model->get_jurusan($data->npm);

        $pdf = new FPDF('P','mm',array(210,330));
        $pdf->setting_page_footer($numPage, $kode, $type);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);
        $pdf->AddPage();
        $pdf->Ln(5);
        $pdf->SetFont('Times','BU',14);
        $pdf->Cell(150, $spasi, "BUKTI PENERIMAAN PENANDATANGANAN DOKUMEN",0,1,'C');
        $pdf->SetFont('Times','',12);
        $pdf->Ln(10);
        $pdf->Cell(45, $spasi,'Nama Lengkap', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$mhs->name, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'NPM', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $mhs->npm, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Jurusan/Program Studi', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $status['jurusan'].'/'.$status['prodi'], 0, 1, 'L');
        $pdf->Ln(5);
        $pdf->SetWidths(array(10,70,30,40));
        $pdf->SetAligns(array('C','C','C','C'));
        $pdf->SetBoldFont(array('B','B','B','B'));
        $pdf->SetSpacing($spasi);
        $pdf->Row(array("NO", "JENIS DOKUMEN", "JUMLAH", "KETERANGAN"));
        $pdf->SetBoldFont(array());
        $pdf->SetAligns(array('C','L','C'));
        $pdf->Row(array("1.", "Kartu Rencana Studi (KRS)", "3 Lembar", ""));
        $pdf->Ln(10);
        $pdf->SetX(110);
        $pdf->MultiCell(80, $spasi, "Bandar Lampung, ...........................\nPetugas Yang Menerima,\n\n\n\n\nSugiharto\nNIP. 196903172008101001", 0, 'L');
    
        //LEMBAR VERIFIKASI
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
        $pdf->Row(array('1',"Print out KRS, KHS, Transkrip mahasiswa dari SIAKAD",'@ max. 5 lbr.',''));
        $pdf->Row(array('2',"Untuk KRS dan/ KHS harus sudah ditandatangani oleh dosen Pembimbing Akademik",'',''));

        $pdf->Output('I','form_ttd_krs.pdf');
    }
    // limit edit header
    function form_13($data,$meta)
    {
         /* ****************************************
        Form Name: form krs
        Created By: 198701282018031001
        Created Date: 2019/08/02
        Last Modification: 2019/08/06
        * *****************************************/

        /*Edit by   :1617051107
        date        :31/01/2020 */

        /*Edit by   :1617051088
        date        :27/10/2020 */

        $cek_atr = $this->layanan_model->select_layanan_atribut_by_id($data->id_layanan_fakultas);
        $n=0;
        if(empty($cek_atr)){
            for($m=0;$m<=99;$m++){
                $attr[$m] = "";
            }
        }
        else{
            foreach($meta as $metas)
            {
                $attr[$n] = $metas->meta_value;
                $n++;
            }
        }
       
        $count = count($meta);

        $numPage = '/PM/MIPA/I/......';
        $kode = 0;
        $type = '';
        $spasi = 6;
        $mhs = $this->user_model->get_mahasiswa_data_npm($data->npm);
        $status = $this->get_prodi_from_npm($data->npm);     
        $jurusan = $this->ta_model->get_jurusan($data->npm);

        $pdf = new FPDF('P','mm',array(210,330));
        $pdf->setting_page_footer($numPage, $kode, $type);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);
        $pdf->AddPage();
        $pdf->Ln(5);
        $pdf->SetFont('Times','BU',14);
        $pdf->Cell(150, $spasi, "BUKTI PENERIMAAN PENANDATANGANAN DOKUMEN",0,1,'C');
        $pdf->SetFont('Times','',12);
        $pdf->Ln(10);
        $pdf->Cell(45, $spasi,'Nama Lengkap', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$mhs->name, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'NPM', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $mhs->npm, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Jurusan/Program Studi', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $status['jurusan'].'/'.$status['prodi'], 0, 1, 'L');
        $pdf->Ln(5);
        $pdf->SetWidths(array(10,70,30,40));
        $pdf->SetAligns(array('C','C','C','C'));
        $pdf->SetBoldFont(array('B','B','B','B'));
        $pdf->SetSpacing($spasi);
        $pdf->Row(array("NO", "JENIS DOKUMEN", "JUMLAH", "KETERANGAN"));
        $pdf->SetBoldFont(array());
        $pdf->SetAligns(array('C','L','C'));
        $pdf->Row(array("1.", "Transkrip Akademik", "3 Lembar", ""));
        $pdf->Ln(10);
        $pdf->SetX(110);
        $pdf->MultiCell(80, $spasi, "Bandar Lampung, ...........................\nPetugas Yang Menerima,\n\n\n\n\nSugiharto\nNIP. 196903172008101001", 0, 'L');
    
        //LEMBAR VERIFIKASI
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
        $pdf->Row(array('1',"Print out KRS, KHS, Transkrip mahasiswa dari SIAKAD",'@ max. 5 lbr.',''));
        $pdf->Row(array('2',"Untuk KRS dan/ KHS harus sudah ditandatangani oleh dosen Pembimbing Akademik",'',''));

        $pdf->Output('I','form_ttd_transkrip.pdf');
    }

    function form_46($data,$meta)
    {
        /* ****************************************
        Form Name: form akreditasi
        Created By: 198701282018031001
        Created Date: 2019/08/02
        Last Modification: 2019/08/06
        * *****************************************/

        /*Edit by   :1617051107
        date        :25/10/2020 */

        /*Edit by   :1617051088
        date        :27/10/2020 */


        $cek_atr = $this->layanan_model->select_layanan_atribut_by_id($data->id_layanan_fakultas);
        $n=0;
        if(empty($cek_atr)){
            for($m=0;$m<=99;$m++){
                $attr[$m] = "";
            }
        }
        else{
            foreach($meta as $metas)
            {
                $attr[$n] = $metas->meta_value;
                $n++;
            }
        }
       
        $count = count($meta);

        $numPage = '/PM/MIPA/I/15';
        $kode = 0;
        $type = '';
        $spasi = 6;
        $mhs = $this->user_model->get_mahasiswa_data_npm($data->npm);
        $status = $this->get_prodi_from_npm($data->npm);     
        $jurusan = $this->ta_model->get_jurusan($data->npm);

        $pdf = new FPDF('P','mm',array(210,330));
        $pdf->setting_page_footer($numPage, $kode, $type);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);

        $pdf->AddPage();
        $pdf->SetFont('Times','BU',16);
        $pdf->Ln(5);
        $pdf->Cell(150, $spasi, "SURAT KETERANGAN",0,1,'C');
        $pdf->SetFont('Times','',12);
        $pdf->Cell(150, $spasi, "Nomor: ............/UN26.17.01/DT/20..",0,1,'C');
        $pdf->Ln(10);

        $pdf->MultiCell(150, $spasi,'Yang bertandatangan di bawah ini, Dekan Fakultas Matematika dan Ilmu Pengetahuan Alam, Universitas Lampung menerangkan bahwa Program Studi Kimia (S-1) Jurusan Kimia FMIPA Unila :', 0, 'J');
        $pdf->Ln(2);
        $pdf->SetFont('Times','B',12);
        $pdf->Cell(45, $spasi,'- Terakreditasi B', 0, 1, 'L');
        $pdf->SetFont('Times','',12);
        $pdf->MultiCell(150,$spasi,'SK BAN-PT No.06420/Ak-VIII-S1-017/ULBKHM/V/2004 tanggal 7 Mei 2004; berlaku tanggal 7 Mei 2004 sampai dengan 7 Mei 2009;',0,'J');
        $pdf->MultiCell(150,$spasi,'Tanggal 8 Mei 2009 s.d. 7 Oktober 2010 program studi tersebut sedang dalam proses pengajuan re-Akreditasi;',0,'J');
        $pdf->Ln(2);
        $pdf->SetFont('Times','B',12);
        $pdf->Cell(45, $spasi,'- Terakreditasi B', 0, 1, 'L');
        $pdf->SetFont('Times','',12);
        $pdf->MultiCell(150, $spasi,'SK BAN-PT No.020/BAN-PT/Ak-XIII/S1/X/2010 tanggal 8 Oktober 2010; berlaku tanggal 8 Oktober 2010 sampai dengan 8 Oktober 2015;',0,'J');
        $pdf->Ln(2);
        $pdf->SetFont('Times','B',12);
        $pdf->Cell(45, $spasi,'- Terakreditasi B', 0, 1, 'L');
        $pdf->SetFont('Times','',12);
        $pdf->MultiCell(150, $spasi,'SK BAN-PT No.1074/SK/BAN-PT/Akred/S/IX/2015 tanggal 19 September 2015; berlaku tanggal 19 September 2015 sampai dengan 19 September 2020;',0,'J');
        $pdf->SetFont('Times','B',12);
        $pdf->Ln(2);
        $pdf->Cell(45, $spasi,'- Terakreditasi A', 0, 1, 'L');
        $pdf->SetFont('Times','',12);
        $pdf->MultiCell(150, $spasi,'SK BAN-PT No.0109/SK/BAN-PT/Akred/S/I/2017 tanggal 10 Januari 2017, berlaku tanggal 10 Januari 2017 sampai dengan Januari 2022;',0,'J');

        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Untuk keadaan pada saat pengajuan re-Akreditasi Program Studi, maka kami',0,'J');
        $pdf->Cell(82, $spasi,'menyatakan bahwa Program Studi Kimia (S-1) ',0,0,'L');
        $pdf->SetFont('Times','BU',12);
        $pdf->Cell(6, $spasi,'Terakreditasi B.', 0, 1, 'L');
        $pdf->SetFont('Times','',12);
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Demikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.', 0, 'J');
        $pdf->Ln(10);
        $pdf->SetX(110);
        $pdf->MultiCell(80, $spasi, "Bandar Lampung, ...........................\nDekan,", 0, 'L');
        $pdf->Ln(20);
        $pdf->SetFont('Times','B',12);
        $pdf->SetX(110);
        $dekan = $this->layanan_model->get_tugas_tambahan_user('Dekan');
        if(empty($dekan)){
            $dekan_name = "";
            $dekan_nip = "";
        }
        else{
            $dekan_name = $dekan->gelar_depan." ".$dekan->name.", ".$dekan->gelar_belakang;
            $dekan_nip = $dekan->nip_nik;
        }
        $pdf->MultiCell(80, $spasi, $dekan_name, 0, 'L');
        $pdf->SetFont('Times','',12);
        $pdf->SetX(110);
        $pdf->MultiCell(80, $spasi, "NIP. ".$dekan_nip, 0, 'L');
    
        $pdf->Output('I','form_akreditasi.pdf');
    }

    function form_14($data,$meta)
    {
        /* ****************************************
        Form Name: Bebas ruang baca Fmipa
        Created By: 198701282018031001
        Created Date: 2019/08/02
        Last Modification: 2019/08/06
        * *****************************************/

        /*Edit by   :1617051107
        date        :31/01/2020 */
        /*Edit by   :16170511088
        date        :17/10/2020 */

        $cek_atr = $this->layanan_model->select_layanan_atribut_by_id($data->id_layanan_fakultas);
        $n=0;
        if(empty($cek_atr)){
            for($m=0;$m<=99;$m++){
                $attr[$m] = "";
            }
        }
        else{
            foreach($meta as $metas)
            {
                $attr[$n] = $metas->meta_value;
                $n++;
            }
        }
       
        $count = count($meta);

        $numPage = '/PM/MIPA/I/29';
        $kode = 0;
        $type = '';
        $spasi = 6;
        $mhs = $this->user_model->get_mahasiswa_data_npm($data->npm);
        $status = $this->get_prodi_from_npm($data->npm);     
        $jurusan = $this->ta_model->get_jurusan($data->npm);

        $pdf = new FPDF('P','mm',array(210,330));
        $pdf->setting_page_footer($numPage, $kode, $type);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);

        $pdf->AddPage();
        $pdf->SetFont('Times','B',14);
        $pdf->Ln(5);
        $pdf->Cell(150, $spasi, "SURAT KETERANGAN",0,1,'C');
        $pdf->SetFont('Times','BU',14);
        $pdf->Cell(150, $spasi, "BEBAS RUANG BACA FMIPA",0,1,'C');
        $pdf->SetFont('Times','',12);

        $pdf->Ln(10);
        $pdf->MultiCell(150, $spasi,'Yang bertanda tangan di bawah ini menerangkan bahwa mahasiswa:', 0, 'L');
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
        $pdf->Cell(45, $spasi,'Semester', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $status['semester'], 0, 1, 'L');
        $almt = $this->wilayah_model->get_desa_by_id($mhs->kelurahan_desa);
        $pdf->SetWidths(array(45, 5, 100));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Alamat Lengkap',':',$mhs->jalan." Kelurahan ".$almt->desa." Kecamatan ".$almt->kecamatan." ".$almt->kabupaten." Provinsi ".$almt->provinsi." ".$mhs->kode_pos));
        $pdf->Cell(45, $spasi,'No. Telepon/HP', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $mhs->mobile, 0, 1, 'L');
        $pdf->Ln(2);

        $pdf->MultiCell(150, $spasi,'Dinyatakan Bebas dari administrasi pada Perpustakaan/Ruang Baca Fakultas Matematika dan Ilmu Pengetahuan Alam Universitas Lampung.', 0, 'J');
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.', 0, 'J');
        $pdf->Ln(10);
        $pdf->SetX(110);
        $pdf->Cell(70, $spasi,'Bandar Lampung, ...............................',0,1);
        $pdf->Cell(60, $spasi,'Mengetahui,',0,1);
        $pdf->Cell(60, $spasi, "a.n. Kabag Tata Usaha",0,1);
        $pdf->Cell(60, $spasi, "Kasubbag. Akademik,",0);
        $pdf->Cell(20, $spasi, "",0);
        $pdf->Cell(70, $spasi, "Petugas Ruang Baca,",0,1);
        $pdf->Ln(20);

        $kasubag = $this->layanan_model->get_tugas_tambahan_user('Kepala Sub Bagian Akademik');
        $petugas = $this->layanan_model->get_tugas_tambahan_user('Petugas Perpustakaan');

        if(empty($kasubag)){
            $kasubag_nama = "";
            $kasubag_nip = "";
        }
        else{
            $kasubag_nama = $kasubag->gelar_depan." ".$kasubag->name.", ".$kasubag->gelar_belakang;
            $kasubag_nip = $kasubag->nip_nik;
        }
        if(empty($petugas)){
            $petugas_nama = "";
            $petugas_nip = "";
        }
        else{
            $petugas_nama = $petugas->gelar_depan." ".$petugas->name.", ".$petugas->gelar_belakang;
            $petugas_nip = $petugas->nip_nik;
        }

        $pdf->SetFont('Times','B',12);
        $pdf->Cell(80, $spasi, $kasubag_nama);
        //$pdf->Cell(80, $spasi, 'Sugiharto',0,1);
        $pdf->Cell(70, $spasi, $petugas_nama,0,1);

        $pdf->SetFont('Times','',12);
        $pdf->Cell(80, $spasi, "NIP. ".$kasubag_nip);
        //$pdf->Cell(80, $spasi, "NIP. 196903172008101001",0,1);
        $pdf->Cell(70, $spasi, "NIP. ".$petugas_nip,0,1);

        //LEMBAR VERIFIKASI
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
        $pdf->SetSpacing($spasi);
        $pdf->Row(array('1',"Formulir Bebas Ruang Baca FMIPA yang sudah ditandatangani oleh Petugas Ruang Baca FMIPA (Asli dan copy)",'1+4 lbr.',''));

        $pdf->Output('I','form_bebas_ruang_baca.pdf');
    }

    function form_15($data,$meta)
    {
        /* ****************************************
        Form Name: Bebas Sanksi Akademik
        Layanan Akademik

        * *****************************************/
        /*Edit by   :1617051107
        date        :31/01/2020 */
        /*Edit by   :1617051088
        date        :28/10/2020 */

        $cek_atr = $this->layanan_model->select_layanan_atribut_by_id($data->id_layanan_fakultas);
        $n=0;
        if(empty($cek_atr)){
            for($m=0;$m<=99;$m++){
                $attr[$m] = "";
            }
        }
        else{
            foreach($meta as $metas)
            {
                $attr[$n] = $metas->meta_value;
                $n++;
            }
        }
       
        $count = count($meta);

        $numPage = '/PM/MIPA/I/22';
        $kode = 0;
        $type = '';
        $spasi = 6;
        $mhs = $this->user_model->get_mahasiswa_data_npm($data->npm);
        $status = $this->get_prodi_from_npm($data->npm);     
        $jurusan = $this->ta_model->get_jurusan($data->npm);

        $pdf = new FPDF('P','mm',array(210,330));
        $pdf->setting_page_footer($numPage, $kode, $type);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);

        $pdf->AddPage();
        $pdf->SetFont('Times','B',16);
        $pdf->Ln(5);
        $pdf->Cell(150, $spasi, "SURAT KETERANGAN",0,1,'C');
        $pdf->SetFont('Times','BU',16);
        $pdf->Cell(150, $spasi, "BEBAS SANKSI AKADEMIK",0,1,'C');
        $pdf->SetFont('Times','',12);
        $pdf->Cell(150, $spasi, "Nomor: ............/UN26.17/DT/".date("Y"),0,1,'C');
        $pdf->Ln(10);
        $pdf->MultiCell(150, $spasi,'Dekan Fakultas Matematika dan Ilmu Pengetahuan Alam Universitas Lampung menerangkan bahwa:', 0, 'J');
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
        $pdf->Cell(45, $spasi,'Semester', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $status['semester'], 0, 1, 'L');
        $almt = $this->wilayah_model->get_desa_by_id($mhs->kelurahan_desa);
        $pdf->SetWidths(array(45, 5, 100));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Alamat Lengkap',':',$mhs->jalan." Kelurahan ".$almt->desa." Kecamatan ".$almt->kecamatan." ".$almt->kabupaten." Provinsi ".$almt->provinsi." ".$mhs->kode_pos));
        $pdf->Cell(45, $spasi,'No. Telepon/HP', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $mhs->mobile, 0, 1, 'L');
        $pdf->Ln(2);
        
        $pdf->MultiCell(150, $spasi,'Adalah benar mahasiswa Fakultas Matematika dan Ilmu Pengetahuan Alam (FMIPA) Universitas Lampung yang saat ini terdaftar di Semester '.$status['semester'].' Tahun Akademik '.$attr[0].' pada Program Studi '.$status['prodi'].' Jurusan '.$status['jurusan'].' tidak sedang menerima Sanksi Akademik karena melanggar Kode Etik dan Tata Pergaulan mahasiswa, serta Peraturan Akademik di Universitas Lampung.', 0, 'J');
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Surat keterangan ini dibuat untuk keperluan:', 0, 'J');
        $pdf->MultiCell(150, $spasi,$attr[1], 0, 'J');

        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Demikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.', 0, 'J');
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

        $dekan = $this->layanan_model->get_tugas_tambahan_user('Dekan');
        if(empty($dekan)){
            $dekan_name = "";
            $dekan_nip = "";
        }
        else{
            $dekan_name = $dekan->gelar_depan." ".$dekan->name.", ".$dekan->gelar_belakang;
            $dekan_nip = $dekan->nip_nik;
        }

        $pdf->SetX(112);
        $pdf->Cell(28, $spasi,'Dekan,',0,1);
        $pdf->Ln(20);
        $pdf->SetFont('Times','B',12);
        $pdf->SetX(112);
        $pdf->Cell(68, $spasi, $dekan_name,0,1);
        $pdf->SetFont('Times','',12);
        $pdf->SetX(112);
        $pdf->Cell(68, $spasi, "NIP. ".$dekan_nip,0,1);

        //LEMBAR VERIFIKASI
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
        $pdf->Row(array('1',"Melengkapi data pada formulir Surat Keterangan Bebas Sanksi Akademik",'1 lbr.',''));
        $pdf->Row(array('2',"Copy pembayaran SPP/UKT semester berjalan terlegalisir",'1 lbr.',''));
        $pdf->Row(array('3',"Copy : KTM (dilegalisir) & e-KTP dalam satu lembar A4",'1 lbr.',''));

        $pdf->Output('I','form_bebas_sanksi.pdf');
    }

    function form_16($data,$meta)
    {
        /* ****************************************
        Form Name: Keterangan Lulus
        Layanan Akademik

        * *****************************************/
        /*Edit by   :1617051107
        date        :31/01/2020 */
        /*Edit by   :1617051088
        date        :28/10/2020 */

        $cek_atr = $this->layanan_model->select_layanan_atribut_by_id($data->id_layanan_fakultas);
        $n=0;
        if(empty($cek_atr)){
            for($m=0;$m<=99;$m++){
                $attr[$m] = "";
            }
        }
        else{
            foreach($meta as $metas)
            {
                $attr[$n] = $metas->meta_value;
                $n++;
            }
        }
       
        $count = count($meta);

        $numPage = '/PM/MIPA/I/17';
        $kode = 0;
        $type = '';
        $spasi = 5.5;
        $mhs = $this->user_model->get_mahasiswa_data_npm($data->npm);
        $status = $this->get_prodi_from_npm($data->npm);     
        $jurusan = $this->ta_model->get_jurusan($data->npm);

        $pdf = new FPDF('P','mm',array(210,330));
        $pdf->setting_page_footer($numPage, $kode, $type);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);

        $pdf->AddPage();
        $pdf->SetFont('Times','BU',16);
        $pdf->Ln(5);
        $pdf->Cell(150, $spasi, "SURAT KETERANGAN LULUS",0,1,'C');
        $pdf->SetFont('Times','',12);
        $pdf->Cell(150, $spasi, "Nomor: ............/UN26.17/DT/".date("Y"),0,1,'C');
        $pdf->Ln(10);
        $pdf->MultiCell(150, $spasi,'Dekan Fakultas Matematika dan Ilmu Pengetahuan Alam Universitas Lampung menerangkan bahwa:', 0, 'J');
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
        $pdf->Cell(45, $spasi,'Tempat/Tanggal Lahir', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $mhs->tempat_lahir.'/'.$this->convert_date($mhs->tanggal_lahir), 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Agama', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$this->user_model->get_agama_by_id($mhs->agama)->agama, 0, 1, 'L');
        $almt = $this->wilayah_model->get_desa_by_id($mhs->kelurahan_desa);
        $pdf->SetWidths(array(45, 5, 100));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Alamat Lengkap',':',$mhs->jalan." Kelurahan ".$almt->desa." Kecamatan ".$almt->kecamatan." ".$almt->kabupaten." Provinsi ".$almt->provinsi." ".$mhs->kode_pos));
        $pdf->Cell(45, $spasi,'No. Telepon/HP', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $mhs->mobile, 0, 1, 'L');

        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Adalah benar Mahasiswa Fakultas Matematika dan Ilmu Pengetahuan Alam Universitas Lampung:', 0, 'J');
        $pdf->Ln(2);
        $pdf->Cell(45, $spasi,'Lulus Ujian Pada', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $this->convert_date($attr[0]), 0, 1, 'L');
        $pdf->Cell(45, $spasi,'IPK', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $attr[1], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Predikat Kelulusan', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $attr[2], 0, 1, 'L');

        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Demikian Surat Keterangan Lulus ini dibuat dengan sebenarnya dan diberikan kepada yang bersangkutan dikarenakan Ijazah Asli masih dalam proses, untuk dapat dipergunakan sebagaimana mestinya.', 0, 'J');
        $pdf->Ln(10);
        $y_foto = $pdf->GetY();
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
        $dekan = $this->layanan_model->get_tugas_tambahan_user('Dekan');
        if(empty($dekan)){
            $dekan_name = "";
            $dekan_nip = "";
        }
        else{
            $dekan_name = $dekan->gelar_depan." ".$dekan->name.", ".$dekan->gelar_belakang;
            $dekan_nip = $dekan->nip_nik;
        }
        $pdf->Cell(28, $spasi,'Dekan,',0,1);
        $pdf->Ln(20);
        $pdf->SetFont('Times','B',12);
        $pdf->SetX(112);
        $pdf->Cell(68, $spasi, $dekan_name,0,1);
        $pdf->SetFont('Times','',12);
        $pdf->SetX(112);
        $pdf->Cell(68, $spasi, "NIP. ".$dekan_nip,0,1);

        $pdf->SetY($y_foto);
        $w_img = 30;
        if($mhs->foto != null || $mhs->foto != ''){
            $img_info = getimagesize($mhs->foto);
            $pdf->Cell($w_img, $img_info[1]*$w_img/$img_info[0], $pdf->Image($mhs->foto,$pdf->GetX(),$y_foto,$w_img), 1, 1, 'C',false);
        }
        else{
            $img_unavailable = "assets/images/unnamed.png";
            $img_info = getimagesize($img_unavailable);
            $pdf->Cell($w_img, $img_info[1]*$w_img/$img_info[0], $pdf->Image($img_unavailable,$pdf->GetX(),$y_foto,$w_img), 1, 1, 'C',false);
        }

        //LEMBAR VERIFIKASI
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
        $pdf->Row(array('1',"Melengkapi data pada formulir SKL, ditempel pashoto asli 3x4 cm (2 buah)",'1 lbr.',''));
        $pdf->Row(array('2',"Fotocopy Kartu Tanda Penduduk (e-KTP) di atas kertas A4",'1 lbr.',''));
        $pdf->Row(array('3',"Fotocopy ijazah terakhir",'1 lbr.',''));
        $pdf->Row(array('4',"Fotocopy Berita Acara Ujian Disertasi/Tesis/Skripsi/Tugas Akhir",'1 lbr.',''));
        $pdf->Row(array('5',"Transkrip Nilai Matahari",'1 lbr.',''));
        $pdf->Row(array('6',"Bukti Pendaftaran wisuda/tanda terima dokumen",'1 lbr.',''));

        $pdf->AddPage();
        $pdf->SetLeftMargin(30);
        $pdf->Ln(5);
        $pdf->SetFont('Times','BU',14);
        $pdf->Cell(150, $spasi, "TANDA TERIMA PESANAN",0,1,'C');
        $pdf->SetFont('Times','',12);
        $pdf->Ln(10);
        $pdf->Cell(45, $spasi,'Nama Lengkap', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$mhs->name, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'NPM', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $mhs->npm, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Jurusan/Program Studi', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $status['jurusan'].'/'.$status['prodi'], 0, 1, 'L');
        $pdf->Ln(5);
        $pdf->SetWidths(array(10,70,30,40));
        $pdf->SetAligns(array('C','C','C','C'));
        $pdf->SetBoldFont(array('B','B','B','B'));
        $pdf->SetSpacing($spasi);
        $pdf->Row(array("NO", "JENIS DOKUMEN", "JUMLAH", "KETERANGAN"));
        $pdf->SetBoldFont(array());
        $pdf->SetAligns(array('C','L','C'));
        $pdf->Row(array("1.", "Surat Keterangan Lulus (SKL)", "5 Lembar", ""));
        $pdf->Ln(10);
        $pdf->SetX(110);
        $pts = $this->layanan_model->get_tugas_tambahan_user('Petugas Akademik');
        if(empty($pts)){
            $pts_name = "";
            $pts_nip = "";
        }
        else{
            $pts_name = $pts->gelar_depan." ".$pts->name.", ".$pts->gelar_belakang;
            $pts_nip = $pts->nip_nik;
        }
        $pdf->MultiCell(80, $spasi, "Bandar Lampung, ...........................\nPetugas Yang Menerima,\n\n\n\n\n".$pts_name."\nNIP. ".$pts_nip, 0, 'L');

        
        $pdf->Ln(10);
        $pdf->SetLeftMargin(10);
        $pdf->SetFont('Courier','',8);
        $pdf->SetX(0);
        $pdf->Cell(210, 5, "-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+ potong di sini +-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+--+-+-+-+-+-+-+-+-", 0, 1, "C");
        $pdf->Ln(10);

         // CAP FAKULTAS
         $pdf->Ln(10);
         // Title
         $pdf->TableTitle('VERIFIKASI PERSYARATAN LAYANAN');
         // Header
         $pdf->TableHeader1(array('MIPA/'.$jurusan, $mhs->name));
         $pdf->TableHeader2(array('CAP FAKULTAS', $mhs->npm));
         // SubHeader
         $pdf->SetWidths(array(8,69,13,13,20,30,22,15));
         $pdf->SubHeader(array('No', 'Jenis Persyaratan', 'Sah', 'Benar', 'Lengkap', 'Tgl Penyelesaian', 'Verifikasi', 'Paraf'));
 
         // Isi
         $pdf->SetWidths(array(8,69,13,13,20,30,22,15));
         $pdf->SetAligns(array('C','L'));
         $pdf->SetSpacing(5);
         $pdf->Row(array('1',"Dokumen yang telah ditandatangani oleh pejabat yang berwenang\n ",'','','','','',''));

        $pdf->Output('I','form_ket_lulus.pdf');
    }

    function form_17($data,$meta)
    {
        /* ****************************************
        Form Name: Test Toefl
        Layanan Akademik

        * *****************************************/
        /*Edit by   :1617051107
        date        :31/01/2020 */
        /*Edit by   :1617051088
        date        :28/10/2020 */
        $cek_atr = $this->layanan_model->select_layanan_atribut_by_id($data->id_layanan_fakultas);
        $n=0;
        if(empty($cek_atr)){
            for($m=0;$m<=99;$m++){
                $attr[$m] = "";
            }
        }
        else{
            foreach($meta as $metas)
            {
                $attr[$n] = $metas->meta_value;
                $n++;
            }
        }
       
        $count = count($meta);

        $numPage = '/PM/MIPA/I/15';
        $kode = 0;
        $type = '';
        $spasi = 6;
        $mhs = $this->user_model->get_mahasiswa_data_npm($data->npm);
        $status = $this->get_prodi_from_npm($data->npm);     
        $jurusan = $this->ta_model->get_jurusan($data->npm);

        $pdf = new FPDF('P','mm',array(210,330));
        $pdf->setting_page_footer($numPage, $kode, $type);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);

        $pdf->AddPage();
        $pdf->SetFont('Times','BU',16);
        $pdf->Ln(5);
        $pdf->Cell(150, $spasi, "SURAT KETERANGAN TEST TOEFL",0,1,'C');
        $pdf->SetFont('Times','',12);
        $pdf->Cell(150, $spasi, "Nomor: ............/UN26.17.01/DT/".date("Y"),0,1,'C');
        $pdf->Ln(10);
        $pdf->MultiCell(150, $spasi,'Yang bertanda tangan di bawah ini Kepala Bagian Tata Usaha Fakultas Matematika dan Ilmu Pengetahuan Alam Universitas Lampung menerangkan bahwa:', 0, 'L');
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
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Adalah benar mahasiswa yang bersangkutan masih terdaftar sebagai mahasiswa di FMIPA Universitas Lampung dan akan mengikuti TEST TOEFL, untuk keperluan:', 0, 'J');
        $pdf->Ln(2);
        $pdf->Cell(45, $spasi,'Wisuda Periode', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $attr[0], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Bulan', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $attr[1], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Tahun', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $attr[2], 0, 1, 'L');
        $pdf->Ln(2);

        $pdf->MultiCell(150, $spasi,'Demikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.', 0, 'J');
        $pdf->Ln(10);
        $pdf->SetX(110);
        $pdf->MultiCell(80, $spasi, "Bandar Lampung, ...........................\na.n. Kabag. Tata Usaha,\nKasubbag Akademik,", 0, 'L');
        $pdf->Ln(20);
        $pdf->SetFont('Times','B',12);
        $pdf->SetX(110);

        $pts = $this->layanan_model->get_tugas_tambahan_user('Kepala Sub Bagian Akademik');
        if(empty($pts)){
            $pts_name = "";
            $pts_nip = "";
        }
        else{
            $pts_name = $pts->gelar_depan." ".$pts->name.", ".$pts->gelar_belakang;
            $pts_nip = $pts->nip_nik;
        }

        $pdf->MultiCell(80, $spasi,$pts_name, 0, 'L');
        $pdf->SetFont('Times','',12);
        $pdf->SetX(110);
        $pdf->MultiCell(80, $spasi, "NIP. ".$pts_nip, 0, 'L');

        //LEMBAR VERIFIKASI
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
        $pdf->Row(array('1',"Surat Keterangan mengikuti Test TOEFL dari mahasiswa bersangkutan",'4 lbr.',''));
        $pdf->Row(array('2',"Copy pembayaran SPP/UKT semester berjalan terlegalisir",'1 lbr.',''));

        $pdf->Output('I','form_toefl.pdf');

    }

    function form_18($data,$meta)
    {
         /* ****************************************
        Form Name: pengunduran diri
        Layanan Akademik

        * *****************************************/
        /*Edit by   :1617051107
        date        :31/01/2020 */
        /*Edit by   :1617051088
        date        :28/10/2020 */

        $cek_atr = $this->layanan_model->select_layanan_atribut_by_id($data->id_layanan_fakultas);
        $n=0;
        if(empty($cek_atr)){
            for($m=0;$m<=99;$m++){
                $attr[$m] = "";
            }
        }
        else{
            foreach($meta as $metas)
            {
                $attr[$n] = $metas->meta_value;
                $n++;
            }
        }
       
        $count = count($meta);

        $numPage = '/PM/MIPA/I/26';
        $kode = 0;
        $type = '';
        $spasi = 4.5;
        $spasi2= 6;
        $mhs = $this->user_model->get_mahasiswa_data_npm($data->npm);
        $status = $this->get_prodi_from_npm($data->npm);     
        $jurusan = $this->ta_model->get_jurusan($data->npm);
        $pa = $this->user_model->get_dosen_pa_by_npm($data->npm);
        $kajur = $this->user_model->get_kajur_by_npm($data->npm);

        $pdf = new FPDF('P','mm',array(210,330));
        $pdf->setting_page_footer($numPage, $kode, $type);
        $pdf->setting_no_header(array(1));
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);

        $pdf->AddPage();
        $pdf->SetFont('Times','',12);
        $pdf->SetX(20);
        $pdf->Cell(17, $spasi, 'Lampiran');
        $pdf->Cell(3, $spasi, ':');
        $pdf->Cell(140, $spasi, "Satu Berkas",0, 1, 'L');
        $pdf->SetX(20);
        $pdf->Cell(17, $spasi, 'Perihal');
        $pdf->Cell(3, $spasi, ':');
        $pdf->SetFont('Times','B',12);
        $pdf->Cell(140, $spasi, "Pengunduran Diri Sebagai Mahasiswa Unila",0, 1, 'L');
        $pdf->Ln(5);
        $pdf->MultiCell(150, $spasi,'Kepada Yth.', 0, 'L');
        $pdf->MultiCell(150, $spasi,'Dekan FMIPA Universitas Lampung', 0, 'L');
        $pdf->MultiCell(150, $spasi,'di Bandar Lampung', 0, 'L');
        $pdf->Ln(5);
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
        $pdf->Cell(45, $spasi,'Tahun Akademik', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $attr[0], 0, 1, 'L');
        $almt = $this->wilayah_model->get_desa_by_id($mhs->kelurahan_desa);
        $pdf->SetWidths(array(45, 5, 100));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Alamat Lengkap',':',$mhs->jalan." Kelurahan ".$almt->desa." Kecamatan ".$almt->kecamatan." ".$almt->kabupaten." Provinsi ".$almt->provinsi." ".$mhs->kode_pos));
        $pdf->Cell(45, $spasi,'No. Telepon/HP', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $mhs->mobile, 0, 1, 'L');
        $pdf->Ln(2);

        $pdf->MultiCell(150, $spasi,'Dengan ini mengajukan permohonan Pengunduran Diri sebagai mahasiswa FMIPA Universitas Lampung, dengan dasar pertimbangan/alasan:', 0, 'J');
        $pdf->SetFont('Times','B',12);
        $pdf->MultiCell(150, $spasi,$attr[1], 0, 'J');
        $pdf->Ln(2);
        $pdf->SetFont('Times','',12);
        $pdf->MultiCell(150, $spasi,'Sehubungan dengan hal tersebut, mohon untuk dapat diberikan Surat Keterangan beserta capaian hasil studi saya selama berkuliah di FMIPA Unila ini.', 0, 'J');
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Sebagai bahan pertimbangan saya lampirkan:', 0, 'J');
        $pdf->Ln(2);

        $pdf->SetWidths(array(8,142));
        $pdf->SetAligns(array('C','J'));
        $pdf->SetSpacing($spasi);
        $pdf->RowNoBorder(array('1.',"Transkrip Akademik yang sudah disahkan Wakil Dekan Bidang Akademik dan Kerjasama;"));
        $pdf->RowNoBorder(array('2.',"Kartu Hasil Studi (KHS) semester terakhir;"));
        $pdf->RowNoBorder(array('3.',"Surat Keterangan Bebas Pinjam Buku Perpustakaan Unila;"));
        $pdf->RowNoBorder(array('4.',"Surat Keterangan Bebas Ruang Baca Fakultas;"));
        $pdf->RowNoBorder(array('5.',"Surat Keterangan Bebas Laboratorium;"));
        $pdf->RowNoBorder(array('6.',"Fotocopy Bukti Pembayaran SPP/UKT terakhir yang telah dilegalisir;"));
        $pdf->RowNoBorder(array('7.',"KTM asli dan dua (2) lembar fotocopy yang telah dilegalisir."));
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Demikian permohonan ini disampaikan, atas perhatian dan perkenan Bapak saya ucapkan terima kasih.', 0, 'J');
        $pdf->Ln(5);
        $pdf->SetX(110);
        $pdf->Cell(60, $spasi,'Bandar Lampung, '.$this->convert_date($data->created_at),0,1);
        $pdf->Cell(90, $spasi,'Mengetahui,',0,1);
        $pdf->Cell(80, $spasi,'Orangtua Mahasiswa,');
        $pdf->Cell(60, $spasi,'Pemohon,',0,1);
        $y_materai = $pdf->GetY();
        $pdf->Ln(20);

        $pdf->Cell(80, $spasi,$attr[2]);
        $pdf->Cell(60, $spasi, $mhs->name,0,1);
        $pdf->SetX(110);
        $pdf->Cell(60, $spasi, "NPM. ".$mhs->npm,0,1);
        $pdf->Ln(5);

        //kajur pa 
         if(empty($kajur)){
            $kajur_name = "";
            $kajur_nip = "";
        }
        else{
            $kajur_name = $kajur->gelar_depan." ".$kajur->name.", ".$kajur->gelar_belakang;
            $kajur_nip = $kajur->nip_nik;
        }

        $pdf->Cell(60, $spasi, "Ketua Jurusan ".$jurusan.",");
        $pdf->Cell(20, $spasi, "");
        $pdf->Cell(60, $spasi, "Pembimbing Akademik,",0,1);
        $pdf->Ln(20);
        $pdf->Cell(60, $spasi, $kajur_name);
        $pdf->Cell(20, $spasi, "");
        $pdf->Cell(60, $spasi, $pa->gelar_depan." ".$pa->name.", ".$pa->gelar_belakang,0,1);
        $pdf->Cell(60, $spasi, "NIP. ".$kajur_nip);
        $pdf->Cell(20, $spasi, "");
        $pdf->Cell(60, $spasi, "NIP. ".$pa->nip_nik,0,1);
        $pdf->SetXY(100, $y_materai+3);
        $pdf->SetFont('Times','I',9);
        $pdf->MultiCell(20, 7, "Materai\nRp. 6000,-", 1, 'C');

        //LEMBAR VERIFIKASI
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
        $pdf->SetSpacing($spasi2);
        $pdf->Row(array('1',"Surat Permohonan Pengunduran Diri mahasiswa yang bersangkutan beserta alasannya, ditandatangani di atas meterai Rp.6.000,-, diketahui; Dosen PA, Kajur",'1 lbr.',''));
        $pdf->Row(array('2',"Surat Keterangan Bebas Pinjam Buku Perpustakaan Unila",'1 lbr.',''));
        $pdf->Row(array('3',"Surat Keterangan Bebas Ruang Baca FMIPA",'1 lbr.',''));
        $pdf->Row(array('4',"Surat Keterangan Bebas Laboratorium FMIPA",'1 lbr.',''));
        $pdf->Row(array('5',"Transkrip nilai yang sudah disahkan Wakil Dekan I",'1 lbr.',''));
        $pdf->Row(array('6',"Copy pembayaran SPP/UKT semester berjalan terlegalisir",'1 lbr.',''));
        $pdf->Row(array('7',"Copy : KTM (dilegalisir) & e-KTP dalam satu lembar A4",'1 lbr.',''));
        $pdf->Row(array('8',"KTM asli",'1 lbr.',''));

        $pdf->Output('I','form_pengunduran_diri.pdf');
    }

    function form_19($data,$meta)
    {
        /* ****************************************
        Form Name: PENGGANTI IJAZAH RUSAK/HILANG
        Layanan Akademik

        * *****************************************/
        /*Edit by   :1617051107
        date        :31/01/2020 */
        /*Edit by   :1617051088
        date        :31/01/2020 */

        $cek_atr = $this->layanan_model->select_layanan_atribut_by_id($data->id_layanan_fakultas);
        $n=0;
        if(empty($cek_atr)){
            for($m=0;$m<=99;$m++){
                $attr[$m] = "";
            }
        }
        else{
            foreach($meta as $metas)
            {
                $attr[$n] = $metas->meta_value;
                $n++;
            }
        }
       
        $count = count($meta);

        $numPage = '/PM/MIPA/I/.....';
        $kode = 0;
        $type = '';
        $spasi= 6;
        $mhs = $this->user_model->get_mahasiswa_data_npm($data->npm);
        $status = $this->get_prodi_from_npm($data->npm);     
        $jurusan = $this->ta_model->get_jurusan($data->npm);

        $pdf = new FPDF('P','mm',array(210,330));
        $pdf->setting_page_footer($numPage, $kode, $type);
        $pdf->setting_no_header(array(1));
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);

        $pdf->AddPage();
        $pdf->SetFont('Times','',12);
        $pdf->Ln(5);
        $pdf->SetX(20);
        $pdf->Cell(17, $spasi, 'Perihal');
        $pdf->Cell(3, $spasi, ':');
        $pdf->SetFont('Times','B',12);
        $pdf->Cell(140, $spasi, "Permohonan  Pengantian Ijazah yang Rusak/Hilang",0, 1, 'L');
        $pdf->Ln(10);
        $pdf->MultiCell(150, $spasi,'Kepada Yth.', 0, 'L');
        $pdf->MultiCell(150, $spasi,'Dekan FMIPA Universitas Lampung', 0, 'L');
        $pdf->MultiCell(150, $spasi,'di Bandar Lampung', 0, 'L');
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
        $pdf->Cell(45, $spasi,'Strata', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $status['strata'], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Tahun Lulus', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, date_format(date_create($attr[0]), "Y"), 0, 1, 'L');
        $pdf->Cell(45, $spasi,'IPK', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $attr[1], 0, 1, 'L');
        $almt = $this->wilayah_model->get_desa_by_id($mhs->kelurahan_desa);
        $pdf->SetWidths(array(45, 5, 100));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Alamat Lengkap',':',$mhs->jalan." Kelurahan ".$almt->desa." Kecamatan ".$almt->kecamatan." ".$almt->kabupaten." Provinsi ".$almt->provinsi." ".$mhs->kode_pos));
        $pdf->Cell(45, $spasi,'No. Telepon/HP', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $mhs->mobile, 0, 1, 'L');
        $pdf->Ln(2);

        $pdf->MultiCell(150, $spasi,'Dengan ini mengajukan permohonan penggantian kembali ijazah yang telah rusak/hilang akibat kelalaian saya.', 0, 'J');
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Bersama ini saya lampirkan:', 0, 'J');
        $pdf->Ln(2);
        $pdf->SetWidths(array(8,142));
        $pdf->SetAligns(array('C','J'));
        $pdf->SetSpacing($spasi);
        $pdf->RowNoBorder(array('1.',"Surat keterangan kehilangan/kerusakan dari kepolisian;"));
        $pdf->RowNoBorder(array('2.',"Fotocopy ijazah sebelumnya (dilegalisir)."));
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Demikian permohonan ini disampaikan, atas perhatian dan perkenan Bapak/Ibu saya ucapkan terima kasih.', 0, 'J');
        $pdf->Ln(10);
        $pdf->SetX(110);
        $pdf->Cell(60, $spasi,'Bandar Lampung, '.$this->convert_date($data->created_at),0,1);
        $pdf->SetX(110);
        $pdf->Cell(60, $spasi,'Hormat Saya,',0,1);
        $pdf->SetX(110);
        $pdf->Cell(60, $spasi,$pdf->Image("$data->ttd",$pdf->GetX()-3, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');
        $pdf->Ln(20);
        $pdf->SetX(110);
        $pdf->Cell(60, $spasi, $mhs->name,0,1);
        $pdf->SetX(110);
        $pdf->Cell(60, $spasi, "NPM. ".$mhs->npm,0,1);
        $pdf->Ln(5);

        //LEMBAR VERIFIKASI
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
        $pdf->Row(array('1',"Surat Permohonan beserta alasan (bermeterai Rp.6.000,-)",'1 lbr.',''));
        $pdf->Row(array('2',"Copy Ijazah/Transkrip, Copy Surat Keterangan Kehilangan dari Kepolisian",'1 lbr.',''));
        $pdf->Row(array('3',"Copy e-KTP",'1 lbr.',''));

        $pdf->Output('I','form_pengganti_ijazah.pdf');
    }

    function form_20($data,$meta)
    {
        /* ****************************************
        Form Name: Pengganti Transkrip
        Layanan Akademik

        * *****************************************/
        /*Edit by   :1617051107
        date        :31/01/2020 */
        /*Edit by   :1617051088
        date        :28/10/2020 */

        $cek_atr = $this->layanan_model->select_layanan_atribut_by_id($data->id_layanan_fakultas);
        $n=0;
        if(empty($cek_atr)){
            for($m=0;$m<=99;$m++){
                $attr[$m] = "";
            }
        }
        else{
            foreach($meta as $metas)
            {
                $attr[$n] = $metas->meta_value;
                $n++;
            }
        }
       
        $count = count($meta);

        $numPage = '/PM/MIPA/I/.....';
        $kode = 0;
        $type = '';
        $spasi= 6;
        $mhs = $this->user_model->get_mahasiswa_data_npm($data->npm);
        $status = $this->get_prodi_from_npm($data->npm);     
        $jurusan = $this->ta_model->get_jurusan($data->npm);

        $pdf = new FPDF('P','mm',array(210,330));
        $pdf->setting_page_footer($numPage, $kode, $type);
        $pdf->setting_no_header(array(1));
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);

        $pdf->AddPage();
        $pdf->SetFont('Times','',12);
        $pdf->Ln(5);
        $pdf->SetX(20);
        $pdf->Cell(17, $spasi, 'Perihal');
        $pdf->Cell(3, $spasi, ':');
        $pdf->SetFont('Times','B',12);
        $pdf->Cell(140, $spasi, "Permohonan  Pengantian Ijazah yang Rusak/Hilang",0, 1, 'L');
        $pdf->Ln(10);
        $pdf->MultiCell(150, $spasi,'Kepada Yth.', 0, 'L');
        $pdf->MultiCell(150, $spasi,'Dekan FMIPA Universitas Lampung', 0, 'L');
        $pdf->MultiCell(150, $spasi,'di Bandar Lampung', 0, 'L');
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
        $pdf->Cell(45, $spasi,'Strata', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $status['strata'], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Tahun Lulus', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, date_format(date_create($attr[0]), "Y"), 0, 1, 'L');
        $pdf->Cell(45, $spasi,'IPK', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $attr[1], 0, 1, 'L');
        $almt = $this->wilayah_model->get_desa_by_id($mhs->kelurahan_desa);
        $pdf->SetWidths(array(45, 5, 100));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Alamat Lengkap',':',$mhs->jalan." Kelurahan ".$almt->desa." Kecamatan ".$almt->kecamatan." ".$almt->kabupaten." Provinsi ".$almt->provinsi." ".$mhs->kode_pos));
        $pdf->Cell(45, $spasi,'No. Telepon/HP', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $mhs->mobile, 0, 1, 'L');
        $pdf->Ln(2);

        $pdf->MultiCell(150, $spasi,'Dengan ini mengajukan permohonan penggantian Transkrip Kelulusan yang telah rusak/hilang akibat kelalaian saya.', 0, 'J');
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Bersama ini saya lampirkan:', 0, 'J');
        $pdf->Ln(2);
        $pdf->SetWidths(array(8,142));
        $pdf->SetAligns(array('C','J'));
        $pdf->SetSpacing($spasi);
        $pdf->RowNoBorder(array('1.',"Surat keterangan kehilangan/kerusakan dari kepolisian;"));
        $pdf->RowNoBorder(array('2.',"Fotocopy ijazah (dilegalisir);"));
        $pdf->RowNoBorder(array('3.',"Fotocopy Transkrip Kelulusan sebelumnya (dilegalisir)."));
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Demikian permohonan ini disampaikan, atas perhatian dan perkenan Bapak/Ibu saya ucapkan terima kasih.', 0, 'J');
        $pdf->Ln(10);
        $pdf->SetX(110);
        $pdf->Cell(60, $spasi,'Bandar Lampung, '.$this->convert_date($data->created_at),0,1);
        $pdf->SetX(110);
        $pdf->Cell(60, $spasi,'Hormat Saya,',0,1);
        $pdf->SetX(110);
        $pdf->Cell(60, $spasi,$pdf->Image("$data->ttd",$pdf->GetX()-3, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');
        $pdf->Ln(20);
        $pdf->SetX(110);
        $pdf->Cell(60, $spasi, $mhs->name,0,1);
        $pdf->SetX(110);
        $pdf->Cell(60, $spasi, "NPM. ".$mhs->npm,0,1);
        $pdf->Ln(5);

        //LEMBAR VERIFIKASI
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
        $pdf->Row(array('1',"Surat Permohonan beserta alasan (bermeterai Rp.6.000,-)    ",'1 lbr.',''));
        $pdf->Row(array('2',"Copy Ijazah/Transkrip, Copy Surat Keterangan Kehilangan dari Kepolisian",'1 lbr.',''));
        $pdf->Row(array('3',"Copy e-KTP",'1 lbr.',''));

        $pdf->Output('I','form_pengganti_transkrip.pdf');
        
    }

    function form_21($data,$meta)
    {
        /* ****************************************
        Form Name: LEGALISIR IJAZAH
        Layanan Akademik

        * *****************************************/
        /*Edit by   :1617051107
        date        :31/01/2020 */
        /*Edit by   :1617051088
        date        :28/10/2020 */

        $cek_atr = $this->layanan_model->select_layanan_atribut_by_id($data->id_layanan_fakultas);
        $n=0;
        if(empty($cek_atr)){
            for($m=0;$m<=99;$m++){
                $attr[$m] = "";
            }
        }
        else{
            foreach($meta as $metas)
            {
                $attr[$n] = $metas->meta_value;
                $n++;
            }
        }
       
        $count = count($meta);

        $numPage = '/PM/MIPA/I/19';
        $kode = 0;
        $type = '';
        $spasi= 6;
        $mhs = $this->user_model->get_mahasiswa_data_npm($data->npm);
        $status = $this->get_prodi_from_npm($data->npm);     
        $jurusan = $this->ta_model->get_jurusan($data->npm);

        $pdf = new FPDF('P','mm',array(210,330));
        $pdf->setting_page_footer($numPage, $kode, $type);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);

        $pdf->AddPage();
        $pdf->SetFont('Times','B',14);
        $pdf->Ln(5);
        $pdf->Cell(150, $spasi, "TANDA TERIMA",0,1,'C');
        $pdf->SetFont('Times','BU',14);
        $pdf->Cell(150, $spasi, "PEMBAYARAN LEGALISIR IJAZAH",0,1,'C');
        $pdf->Ln(10);
        $pdf->SetFont('Times','',12);
        $pdf->MultiCell(150, $spasi,'Sudah terima dari:', 0, 'L');
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
        $pdf->Cell(45, $spasi,'Tanggal Lulus', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, date_format(date_create($attr[0]), "d/m/Y"), 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Jumlah', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->SetFont('Times','B',12);
        $pdf->Cell(100, $spasi, "10 lembar", 0, 1, 'L');
        $pdf->SetFont('Times','',12);
        $pdf->Cell(45, $spasi,'Jumlah Uang', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, "Rp. 15.000,-", 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Terbilang', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, "(Lima Belas Ribu Rupiah)", 0, 1, 'L');
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Untuk pembayaran legalisir IJAZAH alumni Fakultas Matematika dan Ilmu Pengetahuan Alam Universitas Lampung.', 0, 'J');
        $pdf->Ln(10);

        $pdf->SetX(110);
        $pdf->Cell(70, $spasi,'Bandar Lampung, ...............................',0,1);
        $pdf->Cell(60, $spasi, "Penyetor,",0);
        $pdf->Cell(20, $spasi, "",0);
        $pdf->Cell(70, $spasi, "Petugas Penerima,",0,1);
        $pdf->Cell(60, $spasi,$pdf->Image("$data->ttd",$pdf->GetX()-3, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');
        $pdf->Ln(20);
        $pdf->SetFont('Times','B',12);
        $pdf->Cell(80, $spasi,$mhs->name);
        $pdf->SetFont('Times','',12);
        $pdf->Cell(70, $spasi, ".........................................",0,1);
        $pdf->SetFont('Times','',12);
        $pdf->Cell(80, $spasi, "NPM. ".$mhs->npm);
        $pdf->Cell(70, $spasi, ".........................................",0,1);
        $pdf->Ln(20);
        $pdf->Cell(150, $spasi, "NB: Biaya legalisir sebesar Rp. 1.500,-/Lembar",0,1);

        //LEMBAR VERIFIKASI
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
        $pdf->Row(array('1',"Melakukan pembayaran di Bank BNI (untuk ijazah dan transkrip)",'Max 10 lbr.',''));
        $pdf->Row(array('2',"Menunjukkan Asli; Ijasah / Transkrip / SKL / Piagam",'',''));
        $pdf->Row(array('3',"Menyerahkan Biodata",'1 set.',''));

        $pdf->Output('I','form_legalisir_ijazah.pdf');
    }

    function form_22($data,$meta)
    {
        /* ****************************************
        Form Name: LEGALISIR Transkrip
        Layanan Akademik

        * *****************************************/
        /*Edit by   :1617051107
        date        :31/01/2020 */
        /*Edit by   :1617051088
        date        :28/10/2020 */

        $cek_atr = $this->layanan_model->select_layanan_atribut_by_id($data->id_layanan_fakultas);
        $n=0;
        if(empty($cek_atr)){
            for($m=0;$m<=99;$m++){
                $attr[$m] = "";
            }
        }
        else{
            foreach($meta as $metas)
            {
                $attr[$n] = $metas->meta_value;
                $n++;
            }
        }
       
        $count = count($meta);
        $numPage = '/PM/MIPA/I/19';
        $kode = 0;
        $type = '';
        $spasi= 6;
        $mhs = $this->user_model->get_mahasiswa_data_npm($data->npm);
        $status = $this->get_prodi_from_npm($data->npm);     
        $jurusan = $this->ta_model->get_jurusan($data->npm);

        $pdf = new FPDF('P','mm',array(210,330));
        $pdf->setting_page_footer($numPage, $kode, $type);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);

        $pdf->AddPage();
        $pdf->SetFont('Times','B',14);
        $pdf->Ln(5);
        $pdf->Cell(150, $spasi, "TANDA TERIMA",0,1,'C');
        $pdf->SetFont('Times','BU',14);
        $pdf->Cell(150, $spasi, "PEMBAYARAN LEGALISIR TRANSKRIP AKADEMIK",0,1,'C');
        $pdf->Ln(10);
        $pdf->SetFont('Times','',12);
        $pdf->MultiCell(150, $spasi,'Sudah terima dari:', 0, 'L');
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
        $pdf->Cell(45, $spasi,'Tanggal Lulus', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, date_format(date_create($attr[0]), "d/m/Y"), 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Jumlah', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->SetFont('Times','B',12);
        $pdf->Cell(100, $spasi, "10 lembar", 0, 1, 'L');
        $pdf->SetFont('Times','',12);
        $pdf->Cell(45, $spasi,'Jumlah Uang', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, "Rp. 15.000,-", 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Terbilang', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, "(Lima Belas Ribu Rupiah)", 0, 1, 'L');
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Untuk pembayaran legalisir TRANSKRIP AKADEMIK alumni Fakultas Matematika dan Ilmu Pengetahuan Alam Universitas Lampung.', 0, 'J');
        $pdf->Ln(10);

        $pdf->SetX(110);
        $pdf->Cell(70, $spasi,'Bandar Lampung, ...............................',0,1);
        $pdf->Cell(60, $spasi, "Penyetor,",0);
        $pdf->Cell(20, $spasi, "",0);
        $pdf->Cell(70, $spasi, "Petugas Penerima,",0,1);
        $pdf->Cell(60, $spasi,$pdf->Image("$data->ttd",$pdf->GetX()-3, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');
        $pdf->Ln(20);
        $pdf->SetFont('Times','B',12);
        $pdf->Cell(80, $spasi,$mhs->name);
        $pdf->SetFont('Times','',12);
        $pdf->Cell(70, $spasi, ".........................................",0,1);
        $pdf->SetFont('Times','',12);
        $pdf->Cell(80, $spasi, "NPM. ".$mhs->npm);
        $pdf->Cell(70, $spasi, ".........................................",0,1);
        $pdf->Ln(20);
        $pdf->Cell(150, $spasi, "NB: Biaya legalisir sebesar Rp. 1.500,-/Lembar",0,1);

        //LEMBAR VERIFIKASI
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
        $pdf->Row(array('1',"Melakukan pembayaran di Bank BNI (untuk ijazah dan transkrip)",'Max 10 lbr.',''));
        $pdf->Row(array('2',"Menunjukkan Asli; Ijasah / Transkrip / SKL / Piagam",'',''));
        $pdf->Row(array('3',"Menyerahkan Biodata",'1 set.',''));

        $pdf->Output('I','form_legalisir_transkrip.pdf');
    }

    function form_23($data,$meta)
    {
        /* ****************************************
        Form Name: Cetak Transkrip Matahari
        Layanan Akademik

        * *****************************************/
        /*Edit by   :1617051107
        date        :31/01/2020 */
        /*Edit by   :1617051088
        date        :28/10/2020 */

        $cek_atr = $this->layanan_model->select_layanan_atribut_by_id($data->id_layanan_fakultas);
        $n=0;
        if(empty($cek_atr)){
            for($m=0;$m<=99;$m++){
                $attr[$m] = "";
            }
        }
        else{
            foreach($meta as $metas)
            {
                $attr[$n] = $metas->meta_value;
                $n++;
            }
        }
       
        $count = count($meta);

        $numPage = '/PM/MIPA/I/13';
        $kode = 0;
        $type = '';
        $spasi= 6;
        $mhs = $this->user_model->get_mahasiswa_data_npm($data->npm);
        $status = $this->get_prodi_from_npm($data->npm);     
        $jurusan = $this->ta_model->get_jurusan($data->npm);

        $pdf = new FPDF('P','mm',array(210,330));
        $pdf->setting_page_footer($numPage, $kode, $type);
        $pdf->setting_no_header(array(1));
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);

        $pdf->AddPage();
        $pdf->Ln(5);
        $pdf->SetFont('Times','BU',14);
        $pdf->Cell(150, $spasi, "TANDA TERIMA PESANAN",0,1,'C');
        $pdf->SetFont('Times','',12);
        $pdf->Ln(10);

        $pdf->Cell(45, $spasi,'Nama Lengkap', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$mhs->name, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'NPM', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $mhs->npm, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Jurusan/Program Studi', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $status['jurusan'].'/'.$status['prodi'], 0, 1, 'L');
        $pdf->Ln(5);
        $pdf->SetWidths(array(10,70,30,40));
        $pdf->SetAligns(array('C','C','C','C'));
        $pdf->SetBoldFont(array('B','B','B','B'));
        $pdf->SetSpacing($spasi);
        $pdf->Row(array("NO", "JENIS DOKUMEN", "JUMLAH", "KETERANGAN"));
        $pdf->SetBoldFont(array());
        $pdf->SetAligns(array('C','L','C'));
        $pdf->Row(array("1.", "Transkrip Matahari", "5 Lembar", ""));
        $pdf->Ln(10);
        $pdf->SetX(110);
        $pdf->MultiCell(80, $spasi, "Bandar Lampung, ...........................\nPetugas Yang Menerima,\n\n\n\n\n.............................................\nNIP. ....................................", 0, 'L');

        //LEMBAR VERIFIKASI
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
        $pdf->Row(array('1',"Fotocopy Berita Acara Ujian Laporan Akhir / Skripsi / Tesis / Disertasi",'1 lbr.',''));
        $pdf->Row(array('2',"Surat Keterangan Nilai dari Kajur (asli)",'1 lbr.',''));
        $pdf->Row(array('3',"Fotocopy ijazah terakhir dilegalisir",'1 lbr.',''));
        $pdf->Row(array('4',"Transkrip nilai yang sudah disahkan Wakil Dekan I (lingkari MK yang akan dihapus)",'1 lbr.',''));
        $pdf->Row(array('5',"Surat permohonan penghapusan Mata Kuliah yang disetujui Kajur dan Dosen PA",'1 lbr.',''));

        $pdf->Output('I','form_cetak_transkrip_matahari.pdf');
    }

    function form_24($data,$meta)
    {
        /*Edit by   :1617051107
        date        :31/01/2020 */
        /*Edit by   :1617051088
        date        :29/10/2020 */

        $cek_atr = $this->layanan_model->select_layanan_atribut_by_id($data->id_layanan_fakultas);
        $n=0;
        if(empty($cek_atr)){
            for($m=0;$m<=99;$m++){
                $attr[$m] = "";
            }
        }
        else{
            foreach($meta as $metas)
            {
                $attr[$n] = $metas->meta_value;
                $n++;
            }
        }
       
        $count = count($meta);

        $numPage = '/PM/MIPA/III/23';
        $kode = 0;
        $type = '';
        $spasi= 6;
        $mhs = $this->user_model->get_mahasiswa_data_npm($data->npm);
        $status = $this->get_prodi_from_npm($data->npm);     
        $jurusan = $this->ta_model->get_jurusan($data->npm);

        $pdf = new FPDF('P','mm',array(210,330));
        $pdf->setting_page_footer($numPage, $kode, $type);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);
        $pdf->AddPage();
        $pdf->SetFont('Times','BU',16);
        $pdf->Ln(5);
        $pdf->Cell(150, $spasi, "SURAT KETERANGAN",0,1,'C');
        $pdf->SetFont('Times','',12);
        $pdf->Cell(150, $spasi, "Nomor: ............/UN26.17/KM/20".date("Y"),0,1,'C');
        $pdf->Ln(10);

        $wd3 = $this->layanan_model->get_tugas_tambahan_user('Wakil Dekan Bidang Kemahasiswaan');
        if(empty($wd3)){
            $wd3_name = "";
            $wd3_nip = "";
            $wd3_pangkat = "";
        }
        else{
            $wd3_name = $wd3->gelar_depan." ".$wd3->name.", ".$wd3->gelar_belakang;
            $wd3_nip = $wd3->nip_nik;
            if($wd3->pangkat_gol == NULL || $wd3->pangkat_gol == "" ){
                $wd3_pangkat = "";
            }
            else{
                $wd3_pkt = $this->user_model->get_pangkat_gol_by_id($wd3->pangkat_gol);
                $wd3_pangkat = $wd3_pkt->pangkat."/".$wd3_pkt->golongan." ".$wd3_pkt->ruang;
            }
            
        }

        $pdf->MultiCell(150, $spasi,'Yang bertanda tangan dibawah ini:', 0, 'L');
        $pdf->Ln(2);
        $pdf->Cell(45, $spasi,'Nama', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$wd3_name, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'NIP', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$wd3_nip, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Pangkat/Golongan', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$wd3_pangkat, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Jabatan', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, 'Wakil Dekan Bidang Kemahasiswaan dan Alumni', 0, 1, 'L');
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Menerangkan yang sebenarnya bahwa:', 0, 'L');
        $pdf->Ln(2);
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
        $pdf->Cell(45, $spasi,'Semester', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $status['semester'], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Tempat/Tanggal Lahir', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $mhs->tempat_lahir.'/'.$this->convert_date($mhs->tanggal_lahir), 0, 1, 'L');
        $almt = $this->wilayah_model->get_desa_by_id($mhs->kelurahan_desa);
        $pdf->SetWidths(array(45, 5, 100));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Alamat Lengkap',':',$mhs->jalan." Kelurahan ".$almt->desa." Kecamatan ".$almt->kecamatan." ".$almt->kabupaten." Provinsi ".$almt->provinsi." ".$mhs->kode_pos));
        $pdf->Ln(2);

        $pdf->MultiCell(150, $spasi,'Adalah benar mahasiswa tersebut masih aktif kuliah di Fakultas Matematika dan Ilmu Pengetahuan Alam Universitas Lampung, dan mahasiswa tersebut telah kehilangan: Kartu Tanda Mahasiswa (KTM).', 0, 'J');
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Demikian surat keterangan ini dibuat semoga dapat digunakan sebagai mana mestinya.', 0, 'J');
        $pdf->Ln(10);
        $pdf->SetX(92);
        $pdf->Cell(28, $spasi,'Dikeluarkan di');
        $pdf->Cell(5, $spasi,':');
        $pdf->SetFont('Times','B',12);
        $pdf->Cell(35, $spasi,'Bandar Lampung',0,1);
        $pdf->SetFont('Times','',12);
        $pdf->SetX(92);
        $pdf->Cell(28, $spasi,'Pada tanggal');
        $pdf->Cell(5, $spasi,':');
        $pdf->Cell(35, $spasi,'...............................',0,1);
        $pdf->SetX(92);
        $pdf->Cell(28, $spasi,'a.n. Dekan,',0,1);
        $pdf->SetX(92);
        $pdf->Cell(88, $spasi,'Wakil Dekan Bidang Kemahasiswaan dan Alumni,',0,1);
        $pdf->Ln(20);
        $pdf->SetFont('Times','B',12);
        $pdf->SetX(92);
        $pdf->Cell(68, $spasi, $wd3_name,0,1);
        $pdf->SetFont('Times','',12);
        $pdf->SetX(92);
        $pdf->Cell(68, $spasi, "NIP. ".$wd3_nip,0,1);

        //LEMBAR VERIFIKASI
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
        $pdf->SetSpacing(5);
        $pdf->Row(array('1',"Surat Keterangan Kehilangan KTM yang telah di download di website.\n(3 lembar)",'',''));
        $pdf->Row(array('2',"Bukti Pembayaran UKT terakhir.\n(3 lembar)",'',''));

        $pdf->Output('I','form_kehilangan_ktm.pdf');
    }

    function form_25($data,$meta)
    {
        /*Edit by   :1617051107
        date        :31/01/2020 */
        /*Edit by   :1617051088
        date        :29/10/2020 */

        $cek_atr = $this->layanan_model->select_layanan_atribut_by_id($data->id_layanan_fakultas);
        $n=0;
        if(empty($cek_atr)){
            for($m=0;$m<=99;$m++){
                $attr[$m] = "";
            }
        }
        else{
            foreach($meta as $metas)
            {
                $attr[$n] = $metas->meta_value;
                $n++;
            }
        }
       
        $count = count($meta);

        $numPage = '/PM/MIPA/......';
        $kode = 0;
        $type = '';
        $spasi= 6;
        $mhs = $this->user_model->get_mahasiswa_data_npm($data->npm);
        $status = $this->get_prodi_from_npm($data->npm);     
        $jurusan = $this->ta_model->get_jurusan($data->npm);

        $pdf = new FPDF('P','mm',array(210,330));
        $pdf->setting_page_footer($numPage, $kode, $type);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);
        $pdf->AddPage();
        $pdf->Ln(5);
        $pdf->SetFont('Times','BU',14);
        $pdf->Cell(150, $spasi, "BUKTI PENERIMAAN PENANDATANGANAN DOKUMEN",0,1,'C');
        $pdf->SetFont('Times','',12);
        $pdf->Ln(10);
        $pdf->Cell(45, $spasi,'Nama Lengkap', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$mhs->name, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'NPM', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $mhs->npm, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Jurusan/Program Studi', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $status['jurusan'].'/'.$status['prodi'], 0, 1, 'L');
        $pdf->Ln(5);
        $pdf->SetWidths(array(10,70,30,40));
        $pdf->SetAligns(array('C','C','C','C'));
        $pdf->SetBoldFont(array('B','B','B','B'));
        $pdf->SetSpacing($spasi);
        $pdf->Row(array("NO", "JENIS DOKUMEN", "JUMLAH", "KETERANGAN"));
        $pdf->SetBoldFont(array());
        $pdf->SetAligns(array('C','L','C'));
        $pdf->Row(array("1.", "Surat Keterangan (SK) Bidikmisi", "3 Lembar", ""));
        $pdf->Ln(10);
        $pdf->SetX(110);
        $pdf->MultiCell(80, $spasi, "Bandar Lampung, ...........................\nPetugas Yang Menerima,\n\n\n\n\n.............................................\nNIP. ....................................", 0, 'L');

        //LEMBAR VERIFIKASI
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
        $pdf->SetSpacing(5);
        $pdf->Row(array('1',"Bukti Penerimaan Penandatanganan Dokumen",'',''));
        $pdf->Row(array('2',"Surat Keterangan (SK) Bidikmisi yang telah dicetak oleh mahasiswa bersangkutan\n(5 lembar)",'',''));

        $pdf->Output('I','form_legalisir_sk_bidikmisi.pdf');
    }

    function form_26($data,$meta)
    {
        /*Edit by   :1617051107
        date        :25/02/2020 */
        /*Edit by   :1617051107
        date        :29/10/2020 */

        $cek_atr = $this->layanan_model->select_layanan_atribut_by_id($data->id_layanan_fakultas);
        $n=0;
        if(empty($cek_atr)){
            for($m=0;$m<=99;$m++){
                $attr[$m] = "";
            }
        }
        else{
            foreach($meta as $metas)
            {
                $attr[$n] = $metas->meta_value;
                $n++;
            }
        }
       
        $count = count($meta);

        $numPage = '/PM/MIPA/III/19';
        $kode = 0;
        $type = '';
        $spasi= 5;
        $mhs = $this->user_model->get_mahasiswa_data_npm($data->npm);
        $status = $this->get_prodi_from_npm($data->npm);     
        $jurusan = $this->ta_model->get_jurusan($data->npm);
        $pa = $this->user_model->get_dosen_pa_by_npm($data->npm);
        $kajur = $this->user_model->get_kajur_by_npm($data->npm);

        $pdf = new FPDF('P','mm',array(210,330));
        $pdf->setting_page_footer($numPage, $kode, $type);
        $pdf->setting_no_header(array(1));
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);
        
        $pdf->AddPage();
        $pdf->SetFont('Times','',12);
        $pdf->Cell(17, $spasi, 'Lampiran');
        $pdf->Cell(3, $spasi, ':');
        $pdf->Cell(150, $spasi, "Satu Berkas",0, 1, 'L');
        // $pdf->SetX(20);
        $pdf->Cell(17, $spasi, 'Perihal');
        $pdf->Cell(3, $spasi, ':');
        $pdf->SetFont('Times','B',12);
        $pdf->Cell(150, $spasi, "Permohonan Beasiswa",0, 1, 'L');
        $pdf->Ln(5);
        $pdf->SetFont('Times','',12);
        $pdf->MultiCell(170, $spasi,'Kepada Yth.', 0, 'L');
        $pdf->SetFont('Times','B',12);
        $pdf->MultiCell(170, $spasi,'Rektor Universitas Lampung', 0, 'L');
        $pdf->SetFont('Times','',12);
        $pdf->MultiCell(170, $spasi,'c.q. Wakil Rektor Bidang Kemahasiswaan dan Alumni', 0, 'L');
        $pdf->MultiCell(170, $spasi,'di Bandar Lampung', 0, 'L');
        $pdf->Ln(5);
        $pdf->SetFont('Times','',12);
        $pdf->MultiCell(170, $spasi,'Dengan hormat,', 0, 'L');
        $pdf->MultiCell(170, $spasi,'Yang bertanda tangan di bawah ini:', 0, 'L');
        $pdf->Ln(2);

        $pdf->Cell(8, $spasi, '1.', 0, 0, 'R');
        $pdf->Cell(65, $spasi,'Nama', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(82, $spasi,$mhs->name, 0, 1, 'L');
        $pdf->Cell(8, $spasi, '2.', 0, 0, 'R');
        $pdf->Cell(65, $spasi,'Tempat/Tanggal Lahir', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(82, $spasi,$mhs->tempat_lahir."/".$this->convert_date($mhs->tanggal_lahir), 0, 1, 'L');
        $almt = $this->wilayah_model->get_desa_by_id($mhs->kelurahan_desa);
        $pdf->SetWidths(array(8, 65, 5, 82));
        $pdf->SetAligns(array('R','L','C','L'));
        $pdf->RowNoBorder(array('3.','Alamat Lengkap',':',$mhs->jalan." Kelurahan ".$almt->desa." Kecamatan ".$almt->kecamatan." ".$almt->kabupaten." Provinsi ".$almt->provinsi." ".$mhs->kode_pos));
        $pdf->Cell(8, $spasi, '4.', 0, 0, 'R');
        $pdf->Cell(65, $spasi,'Nomor Pokok Mahasiswa (NPM)', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(82, $spasi, $mhs->npm, 0, 1, 'L');
        $pdf->Cell(8, $spasi, '5.', 0, 0, 'R');
        $pdf->Cell(65, $spasi,'Nomor Rekening', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(82, $spasi, $attr[4], 0, 1, 'L');
        $pdf->Cell(8, $spasi, '6.', 0, 0, 'R');
        $pdf->Cell(65, $spasi,'Status', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(82, $spasi, $attr[5], 0, 1, 'L');
        $pdf->Cell(8, $spasi, '7.', 0, 0, 'R');
        $pdf->Cell(65, $spasi,'Nama Orang Tua/Wali', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(82, $spasi, $attr[6], 0, 1, 'L');
        $pdf->Cell(8, $spasi, '8.', 0, 0, 'R');
        $pdf->Cell(65, $spasi,'Pekerjaan Orang Tua/Wali', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(82, $spasi, $attr[7], 0, 1, 'L');
        $pdf->Cell(8, $spasi, '9.', 0, 0, 'R');
        $pdf->Cell(65, $spasi,'Penghasilan Orang Tua/Wali', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(82, $spasi, $attr[8], 0, 1, 'L');
        $pdf->Cell(8, $spasi, '10.', 0, 0, 'R');
        $pdf->Cell(65, $spasi,'Jumlah Tanggungan Orang Tua/Wali', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(82, $spasi, $attr[9], 0, 1, 'L');
        $pdf->SetWidths(array(8,65, 5, 82));
        $pdf->SetAligns(array('R','L','C','L'));
        $pdf->RowNoBorder(array('11.','Alamat Orang Tua/Wali',':',$attr[10]));
        $pdf->Cell(8, $spasi, '12.', 0, 0, 'R');
        $pdf->Cell(65, $spasi,'Jurusan/Program Studi', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(82, $spasi, $status['jurusan'].'/'.$status['prodi'], 0, 1, 'L');
        $pdf->Cell(8, $spasi, '13.', 0, 0, 'R');
        $pdf->Cell(65, $spasi,'Fakultas/Semester', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(82, $spasi,'Matematika dan Ilmu Pengetahuan Alam/'.$status['semester'], 0, 1, 'L');
        $pdf->Cell(8, $spasi, '14.', 0, 0, 'R');
        $pdf->Cell(65, $spasi,'Indeks Prestasi Komulatif (IPK)', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(82, $spasi, $attr[2], 0, 1, 'L');
        $pdf->Cell(8, $spasi, '15.', 0, 0, 'R');
        $pdf->Cell(65, $spasi,'Kakak/Adik sedang Menerima Beasiswa: ', 0, 0, 'L');
        $pdf->Cell(97, $spasi, '', 0, 1, 'L');
        $pdf->Cell(13, $spasi, 'a.', 0, 0, 'R');
        $pdf->Cell(60, $spasi,'Nama', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(82, $spasi, $attr[11], 0, 1, 'L');
        $pdf->Cell(13, $spasi, 'b.', 0, 0, 'R');
        $pdf->Cell(60, $spasi,'Jenis Beasiswa', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(82, $spasi, $attr[12], 0, 1, 'L');
        $pdf->Cell(13, $spasi, 'c.', 0, 0, 'R');
        $pdf->Cell(60, $spasi,'Fakultas', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(82, $spasi, $attr[13], 0, 1, 'L');
        $pdf->Ln(2);
        $pdf->MultiCell(170, $spasi,'Mohon bantuan untuk mendapatkan Beasiswa '.$attr[0]." tahun ".$attr[1].".", 0, 'J');
        $pdf->Ln(2);
        $pdf->MultiCell(170, $spasi,'Demikian permohonan saya ini. Atas perhatian dan bantuan Bapak, saya ucapkan terima kasih.', 0, 'J');
        $pdf->Ln(5);
        $pdf->SetX(120);
        $pdf->Cell(60, $spasi,'Bandar Lampung, ............................',0,1);
        $pdf->SetX(120);
        $pdf->Cell(60, $spasi,'Hormat Saya,',0,1);
        $pdf->SetX(120);
        $pdf->Cell(60, $spasi,$pdf->Image("$data->ttd",$pdf->GetX()-3, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');
        $pdf->Ln(20);
        $pdf->SetX(120);
        $pdf->Cell(60, $spasi, $mhs->name,0,1);
        $pdf->SetX(120);
        $pdf->Cell(60, $spasi, "NPM. ".$mhs->npm,0,1);

        // Page 2
        $pdf->AddPage();
        $spasi = 6;
        $pdf->SetFont('Times','B',16);
        $pdf->Ln(5);
        $pdf->Cell(170, $spasi+1, "SURAT PERNYATAAN",0,1,'C');
        $pdf->SetFont('Times','BU',16);
        $pdf->Cell(170, $spasi+1, "KEASLIAN DOKUMEN PERSYARATAN BEASISWA",0,1,'C');
        $pdf->SetFont('Times','',12);
        $pdf->Ln(10);
        $pdf->MultiCell(170, $spasi,'Saya yang bertanda tangan di bawah ini:', 0, 'L');
        $pdf->Ln(2);
        $pdf->Cell(45, $spasi,'Nama Lengkap', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(120, $spasi,$mhs->name, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'NPM', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(120, $spasi, $mhs->npm, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Semester', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(120, $spasi, $status['semester'], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Program Studi', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(120, $spasi, $status['prodi'], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Fakultas', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(120, $spasi, "Matematika dan Ilmu Pengetahuan Alam", 0, 1, 'L');
        $pdf->Ln(2);
        $pdf->MultiCell(170, $spasi,"Dengan ini menyatakan dengan sesungguhnya bahwa:", 0, 'J');
        $pdf->Ln(2);
        $pdf->SetWidths(array(8,162));
        $pdf->SetAligns(array('C','J'));
        $pdf->RowNoBorder(array('1.',"Transkrip yang saya lampirkan sebagai persyaratan pengajuan Beasiswa sesuai dengan yang tercantum dalam SIAKAD (bukan hasil scanning);"));
        $pdf->RowNoBorder(array('2.',"Semua Dokumen yang dilampirkan sesuai dengan aslinya, tidak dipalsukan (bukan hasil scanning), dan hanya untuk mengajukan beasiswa ".$attr[0]." tahun ".$attr[1]."."));
        $pdf->Ln(2);
        $pdf->MultiCell(170, $spasi,"Apabila surat pernyataan ini tidak benar, maka saya bersedia diberi sanksi Akademik yang ditetapkan oleh Pimpinan Fakultas atau Pimpinan Universitas Lampung.", 0, 'J');
        $pdf->Ln(2);
        $pdf->MultiCell(170, $spasi,'Demikian surat pernyataan ini saya buat dengan sebenarnya, untuk dapat dipergunakan sebagaimana mestinya.', 0, 'J');
        $pdf->Ln(10);
        $pdf->SetX(125);
        $pdf->Cell(65, $spasi,'Bandar Lampung, '.$this->convert_date($data->created_at),0,1);
        $pdf->Cell(150, $spasi,'Menyetujui,',0,1);
        $pdf->Cell(105, $spasi,'a.n. Dekan');
        $pdf->Cell(65, $spasi,'Yang membuat pernyataan,',0,1);
        $pdf->Cell(170, $spasi,'Wakil Dekan Bidang Kemahasiswaan dan Alumni',0,1);
        $y_materai = $pdf->GetY();

        $wd3 = $this->layanan_model->get_tugas_tambahan_user('Wakil Dekan Bidang Kemahasiswaan');
        if(empty($wd3)){
            $wd3_name = "";
            $wd3_nip = "";
            $wd3_pangkat = "";
        }
        else{
            $wd3_name = $wd3->gelar_depan." ".$wd3->name.", ".$wd3->gelar_belakang;
            $wd3_nip = $wd3->nip_nik;
            if($wd3->pangkat_gol == NULL || $wd3->pangkat_gol == "" ){
                $wd3_pangkat = "";
            }
            else{
                $wd3_pkt = $this->user_model->get_pangkat_gol_by_id($wd3->pangkat_gol);
                $wd3_pangkat = $wd3_pkt->pangkat."/".$wd3_pkt->golongan." ".$wd3_pkt->ruang;
            }
            
        }

        $pdf->Ln(20);
        $pdf->Cell(105, $spasi, $wd3_name);
        $pdf->Cell(65, $spasi, $mhs->name,0,1);
        $pdf->Cell(105, $spasi, "NIP. ".$wd3_nip);
        $pdf->Cell(65, $spasi, "NPM. ".$mhs->npm,0,1);
        $pdf->Cell(50, $spasi, "",0,1);
        $pdf->SetXY(115, $y_materai+3);
        $pdf->SetFont('Times','I',9);
        $pdf->MultiCell(20, 7, "Materai\nRp. 6000,-", 1, 'C');

        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);
        $pdf->AddPage();
        $pdf->SetFont('Times','B',16);
        $pdf->Ln(5);
        $pdf->Cell(150, $spasi, "SURAT KETERANGAN",0,1,'C');
        $pdf->SetFont('Times','BU',16);
        $pdf->Cell(150, $spasi, "LAYAK MENERIMA BEASISWA",0,1,'C');
        $pdf->SetFont('Times','',12);
        $pdf->Ln(10);
        $pdf->MultiCell(150, $spasi,'Yang bertanda tangan dibawah ini Pembimbing Akademik:', 0, 'L');
        $pdf->Ln(2);
        $pdf->Cell(45, $spasi,'Nama', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$pa->gelar_depan." ".$pa->name.", ".$pa->gelar_belakang, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'NIP', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$pa->nip_nik, 0, 1, 'L');
        //pangkat pa
        $pa_pkt = $this->user_model->get_pangkat_gol_by_id($pa->pangkat_gol);
        $pa_jbf = $this->user_model->get_pangkat_gol_by_id($pa->fungsional);
        if(!empty($pa_pkt)){
            $pa_pangkat = $pa_pkt->pangkat."(Gol.".$pa_pkt->golongan." ".$pa_pkt->ruang.")";
        }
        else{
            $pa_pangkat = "";
        }
        if(!empty($pa_jabfung)){
            $pa_jabfung =  $pa_jbf->nama;
        }
        else{
            $pa_jabfung = '';
        }
        
        $pdf->Cell(45, $spasi,'Pangkat/Golongan', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$pa_pangkat, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Jabatan', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$pa_jabfung, 0, 1, 'L');
        $pdf->Ln(2);

        $pdf->MultiCell(150, $spasi,'Menerangkan yang sebenarnya bahwa:', 0, 'L');
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
        $pdf->Cell(45, $spasi,'IPK', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $attr[2], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Semester', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $status['semester'], 0, 1, 'L');

        $pdf->SetWidths(array(45, 5, 100));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Alamat Lengkap',':',$mhs->jalan." Kelurahan ".$almt->desa." Kecamatan ".$almt->kecamatan." ".$almt->kabupaten." Provinsi ".$almt->provinsi." ".$mhs->kode_pos));
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Adalah benar mahasiswa bimbingan saya dan sepengetahuan saya mahasiswa tersebut layak untuk menerima Beasiswa: '.$attr[0].' tahun '.$attr[1].".", 0, 'J');
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Demikian surat keterangan ini dibuat dengan sebenarnya.', 0, 'J');
        $pdf->Ln(10);
        $pdf->SetX(115);
        $pdf->Cell(65, $spasi,'Bandar Lampung, .............................',0,1);
        $pdf->SetX(115);
        $pdf->Cell(65, $spasi,'Dosen Pembimbing Akademik,',0,1);
        $pdf->Ln(20);
        $pdf->SetX(115);
        $pdf->Cell(65, $spasi, $pa->gelar_depan." ".$pa->name.", ".$pa->gelar_belakang,0,1);
        $pdf->SetX(115);
        $pdf->Cell(65, $spasi, "NIP. ".$pa->nip_nik,0,1);


        // Page 4
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);
        $pdf->AddPage();
        $pdf->SetFont('Times','B',16);
        $pdf->Ln(5);
        $pdf->Cell(150, $spasi, "SURAT KETERANGAN",0,1,'C');
        $pdf->Cell(150, $spasi, "TIDAK SEDANG MENERIMA DAN",0,1,'C');
        $pdf->SetFont('Times','BU',16);
        $pdf->Cell(150, $spasi, "TIDAK SEDANG MENGAJUKAN BEASISWA",0,1,'C');
        $pdf->SetFont('Times','',12);
        $pdf->Cell(150, $spasi, "Nomor: ............/UN26.17/KM/".date("Y"),0,1,'C');
        $pdf->Ln(10);
        $pdf->MultiCell(150, $spasi,'Yang bertanda tangan dibawah ini:', 0, 'L');
        $pdf->Ln(2);
        $pdf->Cell(45, $spasi,'Nama', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$wd3_name, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'NIP', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $wd3_nip, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Pangkat/Golongan', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$wd3_pangkat, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Jabatan', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, 'Wakil Dekan Bidang Kemahasiswaan dan Alumni', 0, 1, 'L');
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Menerangkan yang sebenarnya bahwa:', 0, 'L');
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
        $pdf->Cell(45, $spasi,'Semester', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $status['semester'], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Tahun Akademik', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $attr[3], 0, 1, 'L');
        $pdf->SetWidths(array(45, 5, 100));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Alamat Lengkap',':',$mhs->jalan." Kelurahan ".$almt->desa." Kecamatan ".$almt->kecamatan." ".$almt->kabupaten." Provinsi ".$almt->provinsi." ".$mhs->kode_pos));
        $pdf->Cell(45, $spasi,'No. HP', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$mhs->mobile, 0, 1, 'L');
        $pdf->Ln(2);

        $pdf->MultiCell(150, $spasi,'Adalah benar mahasiswa Fakultas Matematika dan Ilmu Pengetahuan Alam Universitas Lampung, dan mahasiswa tersebut tidak sedang menerima dan tidak sedang mengajukan  beasiswa dari pihak manapun dan surat keterangan ini di buat untuk keperluan mendapatkan beasiswa '.$attr[0]." tahun ".$attr[1].".", 0, 'J');
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Demikian surat keterangan ini dibuat agar dapat digunakan sebagai mana mestinya.', 0, 'J');
        $pdf->Ln(10);
        $pdf->SetX(92);
        $pdf->Cell(28, $spasi,'Dikeluarkan di');
        $pdf->Cell(5, $spasi,':');
        $pdf->SetFont('Times','B',12);
        $pdf->Cell(35, $spasi,'Bandar Lampung',0,1);
        $pdf->SetFont('Times','',12);
        $pdf->SetX(92);
        $pdf->Cell(28, $spasi,'Pada tanggal');
        $pdf->Cell(5, $spasi,':');
        $pdf->Cell(35, $spasi,'...............................',0,1);
        $pdf->SetX(92);
        $pdf->Cell(28, $spasi,'a.n. Dekan,',0,1);
        $pdf->SetX(92);
        $pdf->Cell(88, $spasi,'Wakil Dekan Bidang Kemahasiswaan dan Alumni,',0,1);
        $pdf->Ln(20);
        $pdf->SetFont('Times','B',12);
        $pdf->SetX(92);
        $pdf->Cell(68, $spasi, $wd3_name,0,1);
        $pdf->SetFont('Times','',12);
        $pdf->SetX(92);
        $pdf->Cell(68, $spasi, "NIP. ".$wd3_nip,0,1);

        //LEMBAR VERIFIKASI
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
        $pdf->SetSpacing(5);
        $pdf->Row(array('1',"Surat Permohonan Beasiswa dari mahasiswa ybs.\n(3 Lembar)",'',''));
        $pdf->Row(array('2',"Transkrip terakhir yang sudah ditandatangani oleh Wakil Dekan Bidang Akademik dan Kerjasama.\n(3 lembar)",'',''));
        $pdf->Row(array('3',"Surat Pernyataan tentang Keaslian Dokumen Persyaratan Beasiswa.\n(3 lembar)",'',''));
        $pdf->Row(array('4',"Surat Keterangan Layak Menerima Beasiswa.\n(3 lembar)",'',''));
        $pdf->Row(array('5',"Surat Keterangan sedang Tidak Menerima dan Mengajukan Beasiswa.\n(3 lembar)",'',''));
        $pdf->Row(array('6',"Berkas Persyaratan Lainnya.\n(3 lembar)",'',''));

        $pdf->Output('I','form_beasiswa_lengkap.pdf');

    }

    function form_27($data,$meta)
    {
        /*Edit by   :1617051107
        date        :31/01/2020 */
        /*Edit by   :1617051088
        date        :30/10/2020 */

        $cek_atr = $this->layanan_model->select_layanan_atribut_by_id($data->id_layanan_fakultas);
        $n=0;
        if(empty($cek_atr)){
            for($m=0;$m<=99;$m++){
                $attr[$m] = "";
            }
        }
        else{
            foreach($meta as $metas)
            {
                $attr[$n] = $metas->meta_value;
                $n++;
            }
        }
       
        $count = count($meta);

        $numPage = '/PM/MIPA/III/23';
        $kode = 0;
        $type = '';
        $spasi= 6;
        $mhs = $this->user_model->get_mahasiswa_data_npm($data->npm);
        $status = $this->get_prodi_from_npm($data->npm);     
        $jurusan = $this->ta_model->get_jurusan($data->npm);

        $pdf = new FPDF('P','mm',array(210,330));
        $pdf->setting_page_footer($numPage, $kode, $type);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);

        $pdf->AddPage();
        $pdf->SetFont('Times','BU',16);
        $pdf->Ln(5);
        $pdf->Cell(150, $spasi, "SURAT KETERANGAN MASIH KULIAH",0,1,'C');
        $pdf->SetFont('Times','',12);
        $pdf->Cell(150, $spasi, "Nomor: ............/UN26.17/KM/".date("Y"),0,1,'C');
        $pdf->Ln(10);
        $pdf->MultiCell(150, $spasi,'Yang bertanda tangan dibawah ini:', 0, 'L');
        $pdf->Ln(2);
        $pdf->Cell(45, $spasi,'Nama', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');

        $wd3 = $this->layanan_model->get_tugas_tambahan_user('Wakil Dekan Bidang Kemahasiswaan');
        if(empty($wd3)){
            $wd3_name = "";
            $wd3_nip = "";
            $wd3_pangkat = "";
        }
        else{
            $wd3_name = $wd3->gelar_depan." ".$wd3->name.", ".$wd3->gelar_belakang;
            $wd3_nip = $wd3->nip_nik;
            if($wd3->pangkat_gol == NULL || $wd3->pangkat_gol == "" ){
                $wd3_pangkat = "";
            }
            else{
                $wd3_pkt = $this->user_model->get_pangkat_gol_by_id($wd3->pangkat_gol);
                $wd3_pangkat = $wd3_pkt->pangkat."/".$wd3_pkt->golongan." ".$wd3_pkt->ruang;
            }
            
        }

        $pdf->Cell(100, $spasi,$wd3_name, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'NIP', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$wd3_nip, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Pangkat/Golongan', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$wd3_pangkat, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Jabatan', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, 'Wakil Dekan Bidang Kemahasiswaan dan Alumni', 0, 1, 'L');
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Menerangkan yang sebenarnya bahwa:', 0, 'L');

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
        $pdf->Cell(45, $spasi,'Semester', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $status['semester'], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Tahun Akademik', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $attr[0], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Tempat/Tanggal Lahir', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$mhs->tempat_lahir.'/'.$this->convert_date($mhs->tanggal_lahir), 0, 1, 'L');
        $almt = $this->wilayah_model->get_desa_by_id($mhs->kelurahan_desa);
        $pdf->SetWidths(array(45, 5, 100));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Alamat Lengkap',':',$mhs->jalan." Kelurahan ".$almt->desa." Kecamatan ".$almt->kecamatan." ".$almt->kabupaten." Provinsi ".$almt->provinsi." ".$mhs->kode_pos));
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Adalah benar mahasiswa tersebut masih aktif kuliah di Fakultas Matematika dan Ilmu Pengetahuan Alam Universitas Lampung.', 0, 'J');
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Demikian surat keterangan ini di buat dengan sebenarnya dan dipergunakan untuk keperluan '.$attr[1].". Atas perhatian dan kerjasamanya diucapkan terima kasih.", 0, 'J');
        $pdf->Ln(10);

        $pdf->SetX(92);
        $pdf->Cell(28, $spasi,'Dikeluarkan di');
        $pdf->Cell(5, $spasi,':');
        $pdf->SetFont('Times','B',12);
        $pdf->Cell(35, $spasi,'Bandar Lampung',0,1);
        $pdf->SetFont('Times','',12);
        $pdf->SetX(92);
        $pdf->Cell(28, $spasi,'Pada tanggal');
        $pdf->Cell(5, $spasi,':');
        $pdf->Cell(35, $spasi,'...............................',0,1);
        $pdf->SetX(92);
        $pdf->Cell(28, $spasi,'a.n. Dekan,',0,1);
        $pdf->SetX(92);
        $pdf->Cell(88, $spasi,'Wakil Dekan Bidang Kemahasiswaan dan Alumni,',0,1);
        $pdf->Ln(20);
        $pdf->SetFont('Times','B',12);
        $pdf->SetX(92);
        $pdf->Cell(68, $spasi, $wd3_name,0,1);
        $pdf->SetFont('Times','',12);
        $pdf->SetX(92);
        $pdf->Cell(68, $spasi, "NIP. ".$wd3_nip,0,1);

        //LEMBAR VERIFIKASI
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
        $pdf->SetSpacing(5);
        $pdf->Row(array('1',"Surat Keterangan Masih Kuliah yang telah di download dari Website FMIPA unila .\n(3 lembar)",'',''));
        $pdf->Row(array('2',"Fotocopy Bukti Pembayaran UKT Terakhir.\n(3 lembar)",'',''));
        $pdf->Row(array('3',"Fotocopy KTM.\n(3 lembar)",'',''));

        $pdf->Output('I','form_keterangan_kuliah_non_asn.pdf');
    }

    function form_28($data,$meta)
    {
        /*Edit by   :1617051107
        date        :31/01/2020 */
        /*Edit by   :1617051088
        date        :30/10/2020 */

        $cek_atr = $this->layanan_model->select_layanan_atribut_by_id($data->id_layanan_fakultas);
        $n=0;
        if(empty($cek_atr)){
            for($m=0;$m<=99;$m++){
                $attr[$m] = "";
            }
        }
        else{
            foreach($meta as $metas)
            {
                $attr[$n] = $metas->meta_value;
                $n++;
            }
        }
       
        $count = count($meta);

        $numPage = '/PM/MIPA/III/23';
        $kode = 0;
        $type = '';
        $spasi= 6;
        $mhs = $this->user_model->get_mahasiswa_data_npm($data->npm);
        $status = $this->get_prodi_from_npm($data->npm);     
        $jurusan = $this->ta_model->get_jurusan($data->npm);

        $pdf = new FPDF('P','mm',array(210,330));
        $pdf->setting_page_footer($numPage, $kode, $type);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);

        $pdf->AddPage();
        $pdf->SetFont('Times','BU',16);
        $pdf->Ln(5);
        $pdf->Cell(150, $spasi, "SURAT KETERANGAN MASIH KULIAH",0,1,'C');
        $pdf->SetFont('Times','',12);
        $pdf->Cell(150, $spasi, "Nomor: ............/UN26.17/KM/".date("Y"),0,1,'C');
        $pdf->Ln(10);
        $pdf->MultiCell(150, $spasi,'Yang bertanda tangan dibawah ini:', 0, 'L');
        $pdf->Ln(2);

        $wd3 = $this->layanan_model->get_tugas_tambahan_user('Wakil Dekan Bidang Kemahasiswaan');
        if(empty($wd3)){
            $wd3_name = "";
            $wd3_nip = "";
            $wd3_pangkat = "";
        }
        else{
            $wd3_name = $wd3->gelar_depan." ".$wd3->name.", ".$wd3->gelar_belakang;
            $wd3_nip = $wd3->nip_nik;
            if($wd3->pangkat_gol == NULL || $wd3->pangkat_gol == "" ){
                $wd3_pangkat = "";
            }
            else{
                $wd3_pkt = $this->user_model->get_pangkat_gol_by_id($wd3->pangkat_gol);
                $wd3_pangkat = $wd3_pkt->pangkat."/".$wd3_pkt->golongan." ".$wd3_pkt->ruang;
            }
            
        }
        $pdf->Cell(45, $spasi,'Nama', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$wd3_name, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'NIP', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$wd3_nip, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Pangkat/Golongan', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$wd3_pangkat, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Jabatan', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, 'Wakil Dekan Bidang Kemahasiswaan dan Alumni', 0, 1, 'L');
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Menerangkan yang sebenarnya bahwa:', 0, 'L');
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
        $pdf->Cell(45, $spasi,'Semester', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $status['semester'], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Tahun Akademik', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $attr[0], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Tempat/Tanggal Lahir', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$mhs->tempat_lahir.'/'.$this->convert_date($mhs->tanggal_lahir), 0, 1, 'L');
        $almt = $this->wilayah_model->get_desa_by_id($mhs->kelurahan_desa);
        $pdf->SetWidths(array(45, 5, 100));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Alamat Lengkap',':',$mhs->jalan." Kelurahan ".$almt->desa." Kecamatan ".$almt->kecamatan." ".$almt->kabupaten." Provinsi ".$almt->provinsi." ".$mhs->kode_pos));
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Adalah benar mahasiswa tersebut masih aktif kuliah di Fakultas Matematika dan Ilmu Pengetahuan Alam Universitas Lampung, dan orang tua mahasiswa tersebut adalah:', 0, 'J');
        $pdf->Ln(2);
        $pdf->Cell(45, $spasi,'Nama', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $attr[1], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Pekerjaan', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $attr[2], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Instansi', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$attr[3], 0, 1, 'L');
        $pdf->SetWidths(array(45, 5, 100));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Alamat',':',$attr[4]));
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,"Demikian surat keterangan ini di buat dengan sebenarnya dan apabila dikemudian hari surat keterangan ini tidak benar yang mengakibatkan kerugian terhadap Negara Republik Indonesia maka saya bersedia menanggung kerugiannya.", 0, 'J');
        $pdf->Ln(10);

        $pdf->SetX(92);
        $pdf->Cell(28, $spasi,'Dikeluarkan di');
        $pdf->Cell(5, $spasi,':');
        $pdf->SetFont('Times','B',12);
        $pdf->Cell(35, $spasi,'Bandar Lampung',0,1);
        $pdf->SetFont('Times','',12);
        $pdf->SetX(92);
        $pdf->Cell(28, $spasi,'Pada tanggal');
        $pdf->Cell(5, $spasi,':');
        $pdf->Cell(35, $spasi,'...............................',0,1);
        $pdf->SetX(92);
        $pdf->Cell(28, $spasi,'a.n. Dekan,',0,1);
        $pdf->SetX(92);
        $pdf->Cell(88, $spasi,'Wakil Dekan Bidang Kemahasiswaan dan Alumni,',0,1);
        $pdf->Ln(20);
        $pdf->SetFont('Times','B',12);
        $pdf->SetX(92);
        $pdf->Cell(68, $spasi, $wd3_name,0,1);
        $pdf->SetFont('Times','',12);
        $pdf->SetX(92);
        $pdf->Cell(68, $spasi, "NIP. ".$wd3_nip,0,1);

        //LEMBAR VERIFIKASI
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
        $pdf->SetSpacing(5);
        $pdf->Row(array('1',"Surat Keterangan Masih Kuliah yang telah di download dari Website FMIPA unila .\n(3 lembar)",'',''));
        $pdf->Row(array('2',"Fotocopy Bukti Pembayaran UKT Terakhir.\n(3 lembar)",'',''));
        $pdf->Row(array('3',"Fotocopy KTM.\n(3 lembar)",'',''));

        $pdf->Output('I','form_keterangan_kuliah_asn.pdf');
    }

    function form_29($data,$meta)
    {
        /*Edit by   :1617051107
        date        :31/01/2020 */
        /*Edit by   :1617051088
        date        :30/10/2020 */

        $cek_atr = $this->layanan_model->select_layanan_atribut_by_id($data->id_layanan_fakultas);
        $n=0;
        if(empty($cek_atr)){
            for($m=0;$m<=99;$m++){
                $attr[$m] = "";
            }
        }
        else{
            foreach($meta as $metas)
            {
                $attr[$n] = $metas->meta_value;
                $n++;
            }
        }
       
        $count = count($meta);

        $numPage = '/PM/MIPA/III/23';
        $kode = 0;
        $type = '';
        $spasi= 6;
        $mhs = $this->user_model->get_mahasiswa_data_npm($data->npm);
        $status = $this->get_prodi_from_npm($data->npm);     
        $jurusan = $this->ta_model->get_jurusan($data->npm);

        $pdf = new FPDF('P','mm',array(210,330));
        $pdf->setting_page_footer($numPage, $kode, $type);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);

        $pdf->AddPage();
        $pdf->SetFont('Times','BU',16);
        $pdf->Ln(5);
        $pdf->Cell(150, $spasi, "SURAT KETERANGAN",0,1,'C');
        $pdf->SetFont('Times','',12);
        $pdf->Cell(150, $spasi, "Nomor: ............/UN26.17/KM/".date("Y"),0,1,'C');
        $pdf->Ln(10);
        $pdf->MultiCell(150, $spasi,'Yang bertanda tangan dibawah ini:', 0, 'L');
        $pdf->Ln(2);

        $wd3 = $this->layanan_model->get_tugas_tambahan_user('Wakil Dekan Bidang Kemahasiswaan');
        if(empty($wd3)){
            $wd3_name = "";
            $wd3_nip = "";
            $wd3_pangkat = "";
        }
        else{
            $wd3_name = $wd3->gelar_depan." ".$wd3->name.", ".$wd3->gelar_belakang;
            $wd3_nip = $wd3->nip_nik;
            if($wd3->pangkat_gol == NULL || $wd3->pangkat_gol == "" ){
                $wd3_pangkat = "";
            }
            else{
                $wd3_pkt = $this->user_model->get_pangkat_gol_by_id($wd3->pangkat_gol);
                $wd3_pangkat = $wd3_pkt->pangkat."/".$wd3_pkt->golongan." ".$wd3_pkt->ruang;
            }
            
        }
        $pdf->Cell(45, $spasi,'Nama', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$wd3_name, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'NIP', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$wd3_nip, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Pangkat/Golongan', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$wd3_pangkat, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Jabatan', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, 'Wakil Dekan Bidang Kemahasiswaan dan Alumni', 0, 1, 'L');
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Menerangkan yang sebenarnya bahwa:', 0, 'L');
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
        $pdf->Cell(45, $spasi,'Semester', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $status['semester'], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Tahun Akademik', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $attr[0], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Tempat/Tanggal Lahir', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$mhs->tempat_lahir.'/'.$this->convert_date($mhs->tanggal_lahir), 0, 1, 'L');
        $almt = $this->wilayah_model->get_desa_by_id($mhs->kelurahan_desa);
        $pdf->SetWidths(array(45, 5, 100));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Alamat Lengkap',':',$mhs->jalan." Kelurahan ".$almt->desa." Kecamatan ".$almt->kecamatan." ".$almt->kabupaten." Provinsi ".$almt->provinsi." ".$mhs->kode_pos));

        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Adalah benar mahasiswa tersebut masih aktif kuliah di Fakultas Matematika dan Ilmu Pengetahuan Alam Universitas Lampung, dan mahasiswa tersebut telah kehilangan Bukti Pembayaran UKT Semester '.$attr[0].'.', 0, 'J');
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Demikian surat keterangan ini dibuat semoga dapat digunakan sebagai mana mestinya.', 0, 'J');
        $pdf->Ln(10);
        $pdf->SetX(92);
        $pdf->Cell(28, $spasi,'Dikeluarkan di');
        $pdf->Cell(5, $spasi,':');
        $pdf->SetFont('Times','B',12);
        $pdf->Cell(35, $spasi,'Bandar Lampung',0,1);
        $pdf->SetFont('Times','',12);
        $pdf->SetX(92);
        $pdf->Cell(28, $spasi,'Pada tanggal');
        $pdf->Cell(5, $spasi,':');
        $pdf->Cell(35, $spasi,'...............................',0,1);
        $pdf->SetX(92);
        $pdf->Cell(28, $spasi,'a.n. Dekan,',0,1);
        $pdf->SetX(92);
        $pdf->Cell(88, $spasi,'Wakil Dekan Bidang Kemahasiswaan dan Alumni,',0,1);
        $pdf->Ln(20);
        $pdf->SetFont('Times','B',12);
        $pdf->SetX(92);
        $pdf->Cell(68, $spasi, $wd3_name,0,1);
        $pdf->SetFont('Times','',12);
        $pdf->SetX(92);
        $pdf->Cell(68, $spasi, "NIP. ".$wd3_nip,0,1);

        //LEMBAR VERIFIKASI
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
        $pdf->SetSpacing(5);
        $pdf->Row(array('1',"Surat Keterangan Kehilangan Bukti Pembayaran UKT yang telah di download di website.\n(3 lembar)",'',''));
        $pdf->Row(array('2',"Foto copy KTM.\n(3 lembar)",'',''));

        $pdf->Output('I','form_kehilangan_bukti_ukt.pdf');   
    }

    function form_30($data,$meta)
    {
        /*Edit by   :1617051107
        date        :31/01/2020 */
        /*Edit by   :1617051088
        date        :30/10/2020 */

        $cek_atr = $this->layanan_model->select_layanan_atribut_by_id($data->id_layanan_fakultas);
        $n=0;
        if(empty($cek_atr)){
            for($m=0;$m<=99;$m++){
                $attr[$m] = "";
            }
        }
        else{
            foreach($meta as $metas)
            {
                $attr[$n] = $metas->meta_value;
                $n++;
            }
        }
       
        $count = count($meta);

        $numPage = '/PM/MIPA/III/23';
        $kode = 0;
        $type = '';
        $spasi= 6;
        $mhs = $this->user_model->get_mahasiswa_data_npm($data->npm);
        $status = $this->get_prodi_from_npm($data->npm);     
        $jurusan = $this->ta_model->get_jurusan($data->npm);

        $pdf = new FPDF('P','mm',array(210,330));
        $pdf->setting_page_footer($numPage, $kode, $type);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);

        $pdf->AddPage();
        $pdf->SetFont('Times','BU',16);
        $pdf->Ln(5);
        $pdf->Cell(150, $spasi, "SURAT KETERANGAN",0,1,'C');
        $pdf->SetFont('Times','',12);
        $pdf->Cell(150, $spasi, "Nomor: ............/UN26.17/KM/".date("Y"),0,1,'C');
        $pdf->Ln(10);

        $wd3 = $this->layanan_model->get_tugas_tambahan_user('Wakil Dekan Bidang Kemahasiswaan');
        if(empty($wd3)){
            $wd3_name = "";
            $wd3_nip = "";
            $wd3_pangkat = "";
        }
        else{
            $wd3_name = $wd3->gelar_depan." ".$wd3->name.", ".$wd3->gelar_belakang;
            $wd3_nip = $wd3->nip_nik;
            if($wd3->pangkat_gol == NULL || $wd3->pangkat_gol == "" ){
                $wd3_pangkat = "";
            }
            else{
                $wd3_pkt = $this->user_model->get_pangkat_gol_by_id($wd3->pangkat_gol);
                $wd3_pangkat = $wd3_pkt->pangkat."/".$wd3_pkt->golongan." ".$wd3_pkt->ruang;
            }
            
        }
        $pdf->Cell(45, $spasi,'Nama', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$wd3_name, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'NIP', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$wd3_nip, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Pangkat/Golongan', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$wd3_pangkat, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Jabatan', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, 'Wakil Dekan Bidang Kemahasiswaan dan Alumni', 0, 1, 'L');
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Menerangkan yang sebenarnya bahwa:', 0, 'L');
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
        $pdf->Cell(45, $spasi,'Semester', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $status['semester'], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Tahun Akademik', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $attr[0], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Tempat/Tanggal Lahir', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$mhs->tempat_lahir.'/'.$this->convert_date($mhs->tanggal_lahir), 0, 1, 'L');
        $almt = $this->wilayah_model->get_desa_by_id($mhs->kelurahan_desa);
        $pdf->SetWidths(array(45, 5, 100));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Alamat Lengkap',':',$mhs->jalan." Kelurahan ".$almt->desa." Kecamatan ".$almt->kecamatan." ".$almt->kabupaten." Provinsi ".$almt->provinsi." ".$mhs->kode_pos));

        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Adalah benar mahasiswa Fakultas Matematika dan Ilmu Pengetahuan Alam Universitas Lampung dan  mahasiswa tersebut belum mememiliki Kartu Tanda Mahasiswa (KTM).', 0, 'J');
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Demikian surat keterangan ini dibuat semoga dapat digunakan sebagai mana mestinya.', 0, 'J');
        $pdf->Ln(10);
        $pdf->SetX(92);
        $pdf->Cell(28, $spasi,'Dikeluarkan di');
        $pdf->Cell(5, $spasi,':');
        $pdf->SetFont('Times','B',12);
        $pdf->Cell(35, $spasi,'Bandar Lampung',0,1);
        $pdf->SetFont('Times','',12);
        $pdf->SetX(92);
        $pdf->Cell(28, $spasi,'Pada tanggal');
        $pdf->Cell(5, $spasi,':');
        $pdf->Cell(35, $spasi,'...............................',0,1);
        $pdf->SetX(92);
        $pdf->Cell(28, $spasi,'a.n. Dekan,',0,1);
        $pdf->SetX(92);
        $pdf->Cell(88, $spasi,'Wakil Dekan Bidang Kemahasiswaan dan Alumni,',0,1);
        $pdf->Ln(20);
        $pdf->SetFont('Times','B',12);
        $pdf->SetX(92);
        $pdf->Cell(68, $spasi, $wd3_name,0,1);
        $pdf->SetFont('Times','',12);
        $pdf->SetX(92);
        $pdf->Cell(68, $spasi, "NIP. ".$wd3_nip,0,1);

        //LEMBAR VERIFIKASI
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
        $pdf->SetSpacing(5);
        $pdf->Row(array('1',"Surat Keterangan Belum Memiliki KTM yang telah di download di website.\n(3 lembar)",'','','','','',''));
        $pdf->Row(array('2',"Bukti Pembayaran UKT terakhir.\n(3 lembar)",'','','','','',''));

        $pdf->Output('I','form_belum_memiliki_ktm.pdf');   
    }

    function form_31($data,$meta)
    {
        /*Edit by   :1617051107
        date        :31/01/2020 */
        /*Edit by   :1617051088
        date        :31/01/2020 */

        $cek_atr = $this->layanan_model->select_layanan_atribut_by_id($data->id_layanan_fakultas);
        $n=0;
        if(empty($cek_atr)){
            for($m=0;$m<=99;$m++){
                $attr[$m] = "";
            }
        }
        else{
            foreach($meta as $metas)
            {
                $attr[$n] = $metas->meta_value;
                $n++;
            }
        }
       
        $count = count($meta);
        $numPage = '/PM/MIPA/III/23';
        $kode = 0;
        $type = '';
        $spasi= 6;
        $mhs = $this->user_model->get_mahasiswa_data_npm($data->npm);
        $status = $this->get_prodi_from_npm($data->npm);     
        $jurusan = $this->ta_model->get_jurusan($data->npm);

        $pdf = new FPDF('P','mm',array(210,330));
        $pdf->setting_page_footer($numPage, $kode, $type);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);
        $pdf->AddPage();
        $pdf->SetFont('Times','BU',16);
        $pdf->Ln(5);
        $pdf->Cell(150, $spasi, "SURAT KETERANGAN",0,1,'C');
        $pdf->SetFont('Times','',12);
        $pdf->Cell(150, $spasi, "Nomor: ............/UN26.17/KM/".date("Y"),0,1,'C');
        $pdf->Ln(10);
        $pdf->MultiCell(150, $spasi,'Yang bertanda tangan dibawah ini:', 0, 'L');
        $pdf->Ln(2);
        $wd3 = $this->layanan_model->get_tugas_tambahan_user('Wakil Dekan Bidang Kemahasiswaan');
        if(empty($wd3)){
            $wd3_name = "";
            $wd3_nip = "";
            $wd3_pangkat = "";
        }
        else{
            $wd3_name = $wd3->gelar_depan." ".$wd3->name.", ".$wd3->gelar_belakang;
            $wd3_nip = $wd3->nip_nik;
            if($wd3->pangkat_gol == NULL || $wd3->pangkat_gol == "" ){
                $wd3_pangkat = "";
            }
            else{
                $wd3_pkt = $this->user_model->get_pangkat_gol_by_id($wd3->pangkat_gol);
                $wd3_pangkat = $wd3_pkt->pangkat."/".$wd3_pkt->golongan." ".$wd3_pkt->ruang;
            }
            
        }
        $pdf->Cell(45, $spasi,'Nama', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$wd3_name, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'NIP', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$wd3_nip, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Pangkat/Golongan', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$wd3_pangkat, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Jabatan', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, 'Wakil Dekan Bidang Kemahasiswaan dan Alumni', 0, 1, 'L');
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Menerangkan yang sebenarnya bahwa:', 0, 'L');
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
        $pdf->Cell(45, $spasi,'Semester', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $status['semester'], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Tempat/Tanggal Lahir', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$mhs->tempat_lahir.'/'.$this->convert_date($mhs->tanggal_lahir), 0, 1, 'L');
        $almt = $this->wilayah_model->get_desa_by_id($mhs->kelurahan_desa);
        $pdf->SetWidths(array(45, 5, 100));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Alamat Lengkap',':',$mhs->jalan." Kelurahan ".$almt->desa." Kecamatan ".$almt->kecamatan." ".$almt->kabupaten." Provinsi ".$almt->provinsi." ".$mhs->kode_pos));

        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Adalah benar mahasiswa tersebut masih aktif kuliah di Fakultas Matematika dan Ilmu Pengetahuan Alam Universitas Lampung, dan bermaksud untuk membuat KTM/ATM yang hilang/rusak atas nama yang bersangkutan.', 0, 'J');
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Demikian surat keterangan ini dibuat semoga dapat digunakan sebagai mana mestinya.', 0, 'J');
        $pdf->Ln(10);
        $pdf->SetX(92);
        $pdf->Cell(28, $spasi,'Dikeluarkan di');
        $pdf->Cell(5, $spasi,':');
        $pdf->SetFont('Times','B',12);
        $pdf->Cell(35, $spasi,'Bandar Lampung',0,1);
        $pdf->SetFont('Times','',12);
        $pdf->SetX(92);
        $pdf->Cell(28, $spasi,'Pada tanggal');
        $pdf->Cell(5, $spasi,':');
        $pdf->Cell(35, $spasi,'...............................',0,1);
        $pdf->SetX(92);
        $pdf->Cell(28, $spasi,'a.n. Dekan,',0,1);
        $pdf->SetX(92);
        $pdf->Cell(88, $spasi,'Wakil Dekan Bidang Kemahasiswaan dan Alumni,',0,1);
        $pdf->Ln(20);
        $pdf->SetFont('Times','B',12);
        $pdf->SetX(92);
        $pdf->Cell(68, $spasi, $wd3_name,0,1);
        $pdf->SetFont('Times','',12);
        $pdf->SetX(92);
        $pdf->Cell(68, $spasi, "NIP. ".$wd3_nip,0,1);

        //LEMBAR VERIFIKASI
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
        $pdf->SetSpacing(5);
        $pdf->Row(array('1',"Surat Keterangan Membuat KTM yang telah di download di website.\n(3 lembar)",'',''));
        $pdf->Row(array('2',"Surat Keterangan Kehilangan KTM (jika KTM hilang) yang telah ditandatangani oleh WD3.\n(3 lembar)",'',''));
        $pdf->Row(array('3',"Bukti Pembayaran UKT terakhir.\n(3 lembar)",'',''));
        $pdf->Row(array('4',"Surat Keterangan dari Kepolisian.\n(3 lembar)",'',''));

        $pdf->Output('I','form_ket_membuat_ktm.pdf');
    }

    function form_32()
    {
        //ke menu prestasi
    }

    function form_33()
    {
        //ke menu prestasi
    }

    function form_34($data,$meta)
    {
        /*Edit by   :1617051107
        date        :31/01/2020 */
        /*Edit by   :1617051088
        date        :30/10/2020 */

        $cek_atr = $this->layanan_model->select_layanan_atribut_by_id($data->id_layanan_fakultas);
        $n=0;
        if(empty($cek_atr)){
            for($m=0;$m<=99;$m++){
                $attr[$m] = "";
            }
        }
        else{
            foreach($meta as $metas)
            {
                $attr[$n] = $metas->meta_value;
                $n++;
            }
        }
       
        $count = count($meta);
        $numPage = '/PM/MIPA/III/...';
        $kode = 0;
        $type = '';
        $spasi= 6;
        $mhs = $this->user_model->get_mahasiswa_data_npm($data->npm);
        $status = $this->get_prodi_from_npm($data->npm);     
        $jurusan = $this->ta_model->get_jurusan($data->npm);

        $pdf = new FPDF('P','mm',array(210,330));
        $pdf->setting_page_footer($numPage, $kode, $type);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);
        $pdf->AddPage();
        $pdf->SetFont('Times','BU',16);
        $pdf->Ln(5);
        $pdf->Cell(150, $spasi, "SURAT KETERANGAN",0,1,'C');
        $pdf->SetFont('Times','',12);
        $pdf->Cell(150, $spasi, "Nomor: ............/UN26.17/KM/".date("Y"),0,1,'C');
        $pdf->Ln(10);
        $pdf->MultiCell(150, $spasi,'Yang bertanda tangan dibawah ini:', 0, 'L');
        $pdf->Ln(2);
        $wd3 = $this->layanan_model->get_tugas_tambahan_user('Wakil Dekan Bidang Kemahasiswaan');
        if(empty($wd3)){
            $wd3_name = "";
            $wd3_nip = "";
            $wd3_pangkat = "";
        }
        else{
            $wd3_name = $wd3->gelar_depan." ".$wd3->name.", ".$wd3->gelar_belakang;
            $wd3_nip = $wd3->nip_nik;
            if($wd3->pangkat_gol == NULL || $wd3->pangkat_gol == "" ){
                $wd3_pangkat = "";
            }
            else{
                $wd3_pkt = $this->user_model->get_pangkat_gol_by_id($wd3->pangkat_gol);
                $wd3_pangkat = $wd3_pkt->pangkat."/".$wd3_pkt->golongan." ".$wd3_pkt->ruang;
            }
            
        }
        $pdf->Cell(45, $spasi,'Nama', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$wd3_name, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'NIP', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$wd3_nip, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Pangkat/Golongan', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$wd3_pangkat, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Jabatan', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, 'Wakil Dekan Bidang Kemahasiswaan dan Alumni', 0, 1, 'L');
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Menerangkan yang sebenarnya bahwa:', 0, 'L');
        $pdf->Ln(2);
        // SubHeader
        $pdf->SetWidths(array(10,70,70));
        $pdf->SubHeaderNoBack(array('NO', 'SEMULA TERTULIS', 'DIRALAT MENJADI'));

        // Isi
        $pdf->SetSpacing($spasi);
        $pdf->SetAligns(array('C','L','L'));
        $pdf->Row(array('1',"Nama: ".$attr[0]."\nNPM: ".$attr[1]."\nNo Rekening: ".$attr[2]."\nNo Urut: ".$attr[3]."\nBeasiswa: ".$attr[4],"Nama: ".$attr[5]."\nNPM: ".$attr[6]."\nNo Rekening: ".$attr[7]."\nNo Urut: ".$attr[8]."\nBeasiswa: ".$attr[9]));
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Adalah benar mahasiswa tersebut masih kuliah di Fakultas Matematika dan Ilmu Pengetahuan Alam Universitas Lampung dan mahasiswa tersebut mendapatkan Beasiswa.', 0, 'J');
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Demikian surat pengantar ini dibuat dengan sebenarnya dan semoga dapat digunakan sebagai mana mestinya.', 0, 'J');
        $pdf->Ln(10);
        $pdf->SetX(92);
        $pdf->Cell(28, $spasi,'Dikeluarkan di');
        $pdf->Cell(5, $spasi,':');
        $pdf->SetFont('Times','B',12);
        $pdf->Cell(35, $spasi,'Bandar Lampung',0,1);
        $pdf->SetFont('Times','',12);
        $pdf->SetX(92);
        $pdf->Cell(28, $spasi,'Pada tanggal');
        $pdf->Cell(5, $spasi,':');
        $pdf->Cell(35, $spasi,'...............................',0,1);
        $pdf->SetX(92);
        $pdf->Cell(28, $spasi,'a.n. Dekan,',0,1);
        $pdf->SetX(92);
        $pdf->Cell(88, $spasi,'Wakil Dekan Bidang Kemahasiswaan dan Alumni,',0,1);
        $pdf->Ln(20);
        $pdf->SetFont('Times','B',12);
        $pdf->SetX(92);
        $pdf->Cell(68, $spasi, $wd3_name,0,1);
        $pdf->SetFont('Times','',12);
        $pdf->SetX(92);
        $pdf->Cell(68, $spasi, "NIP. ".$wd3_nip,0,1);

        //LEMBAR VERIFIKASI
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
        $pdf->SetSpacing($spasi);
        $pdf->Row(array('1',"Foto copy KTM.",'',''));
        $pdf->Row(array('2',"Foto copy Slip SPP terakhir.",'',''));
        $pdf->Row(array('3',"Foto copy Nomor Rekening terbaru.",'',''));

        $pdf->Output('I','form_perubahan_rekening.pdf');
    }

    function form_35($data,$meta)
    {
        //menu baru
    }

    function form_36($data,$meta)
    {
        /*Edit by   :1617051107
        date        :31/01/2020 */
        /*Edit by   :1617051088
        date        :30/10/2020 */

        $cek_atr = $this->layanan_model->select_layanan_atribut_by_id($data->id_layanan_fakultas);
        $n=0;
        if(empty($cek_atr)){
            for($m=0;$m<=99;$m++){
                $attr[$m] = "";
            }
        }
        else{
            foreach($meta as $metas)
            {
                $attr[$n] = $metas->meta_value;
                $n++;
            }
        }
       
        $count = count($meta);
        $numPage = '/PM/MIPA/III/23';
        $kode = 0;
        $type = '';
        $spasi= 6;
        $mhs = $this->user_model->get_mahasiswa_data_npm($data->npm);
        $status = $this->get_prodi_from_npm($data->npm);     
        $jurusan = $this->ta_model->get_jurusan($data->npm);

        $pdf = new FPDF('P','mm',array(210,330));
        $pdf->setting_page_footer($numPage, $kode, $type);
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);

        $pdf->AddPage();
        $pdf->SetFont('Times','B',16);
        $pdf->Ln(5);
        $pdf->Cell(150, $spasi+1, "SURAT KETERANGAN",0,1,'C');
        $pdf->Cell(150, $spasi+1, "TIDAK SEDANG MENERIMA DAN",0,1,'C');
        $pdf->SetFont('Times','BU',16);
        $pdf->Cell(150, $spasi+1, "TIDAK SEDANG MENGAJUKAN BEASISWA",0,1,'C');
        $pdf->SetFont('Times','',12);
        $pdf->Cell(150, $spasi, "Nomor: ............/UN26.17/KM/".date("Y"),0,1,'C');
        $pdf->Ln(10);
        $pdf->MultiCell(150, $spasi,'Yang bertanda tangan dibawah ini:', 0, 'L');
        $pdf->Ln(2);
        $wd3 = $this->layanan_model->get_tugas_tambahan_user('Wakil Dekan Bidang Kemahasiswaan');
        if(empty($wd3)){
            $wd3_name = "";
            $wd3_nip = "";
            $wd3_pangkat = "";
        }
        else{
            $wd3_name = $wd3->gelar_depan." ".$wd3->name.", ".$wd3->gelar_belakang;
            $wd3_nip = $wd3->nip_nik;
            if($wd3->pangkat_gol == NULL || $wd3->pangkat_gol == "" ){
                $wd3_pangkat = "";
            }
            else{
                $wd3_pkt = $this->user_model->get_pangkat_gol_by_id($wd3->pangkat_gol);
                $wd3_pangkat = $wd3_pkt->pangkat."/".$wd3_pkt->golongan." ".$wd3_pkt->ruang;
            }
            
        }
        $pdf->Cell(45, $spasi,'Nama', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$wd3_name, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'NIP', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$wd3_nip, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Pangkat/Golongan', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$wd3_pangkat, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Jabatan', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, 'Wakil Dekan Bidang Kemahasiswaan dan Alumni', 0, 1, 'L');
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Menerangkan yang sebenarnya bahwa:', 0, 'L');
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
        $pdf->Cell(45, $spasi,'Semester', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $status['semester'], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Tahun Akademik', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $attr[0], 0, 1, 'L');
        $almt = $this->wilayah_model->get_desa_by_id($mhs->kelurahan_desa);
        $pdf->SetWidths(array(45, 5, 100));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Alamat Lengkap',':',$mhs->jalan." Kelurahan ".$almt->desa." Kecamatan ".$almt->kecamatan." ".$almt->kabupaten." Provinsi ".$almt->provinsi." ".$mhs->kode_pos));
        $pdf->Cell(45, $spasi,'No. Telepon/HP', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $mhs->mobile, 0, 1, 'L');
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Adalah benar mahasiswa Fakultas Matematika dan Ilmu Pengetahuan Alam Universitas Lampung, dan  mahasiswa tersebut tidak sedang menerima dan tidak sedang mengajukan  beasiswa dari pihak manapun dan surat keterangan ini di buat untuk keperluan mendapatkan '.$attr[1].".", 0, 'J');
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Demikian surat keterangan ini dibuat semoga dapat digunakan sebagai mana mestinya.', 0, 'J');
        $pdf->Ln(10);
        $pdf->SetX(92);
        $pdf->Cell(28, $spasi,'Dikeluarkan di');
        $pdf->Cell(5, $spasi,':');
        $pdf->SetFont('Times','B',12);
        $pdf->Cell(35, $spasi,'Bandar Lampung',0,1);
        $pdf->SetFont('Times','',12);
        $pdf->SetX(92);
        $pdf->Cell(28, $spasi,'Pada tanggal');
        $pdf->Cell(5, $spasi,':');
        $pdf->Cell(35, $spasi,'...............................',0,1);
        $pdf->SetX(92);
        $pdf->Cell(28, $spasi,'a.n. Dekan,',0,1);
        $pdf->SetX(92);
        $pdf->Cell(88, $spasi,'Wakil Dekan Bidang Kemahasiswaan dan Alumni,',0,1);
        $pdf->Ln(20);
        $pdf->SetFont('Times','B',12);
        $pdf->SetX(92);
        $pdf->Cell(68, $spasi, $wd3_name,0,1);
        $pdf->SetFont('Times','',12);
        $pdf->SetX(92);
        $pdf->Cell(68, $spasi, "NIP. ".$wd3_nip,0,1);

        //LEMBAR VERIFIKASI
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
        $pdf->SetSpacing(5);
        $pdf->Row(array('1',"Formulir Surat Keterangan Sedang Tidak Menerima Beasiswa hasil unduhan dari Website FMIPA Unila.\n(3 lembar)",'',''));
        $pdf->Row(array('2',"Fotocopy Bukti Pembayaran UKT terakhir.\n(1 lembar)",'',''));
        $pdf->Row(array('3',"Fotocopy KTM.\n(1 lembar)",'',''));

        $pdf->Output('I','form_tidak_menerima_beasiswa.pdf');
    }

    function form_37($data,$meta)
    {
        $cek_atr = $this->layanan_model->select_layanan_atribut_by_id($data->id_layanan_fakultas);
        $n=0;
        if(empty($cek_atr)){
            for($m=0;$m<=99;$m++){
                $attr[$m] = "";
            }
        }
        else{
            foreach($meta as $metas)
            {
                $attr[$n] = $metas->meta_value;
                $n++;
            }
        }
       
        $count = count($meta);
        $numPage = '';
        $kode = 0;
        $type = '';
        $spasi= 6;
        $mhs = $this->user_model->get_mahasiswa_data_npm($data->npm);
        $status = $this->get_prodi_from_npm($data->npm);     
        $jurusan = $this->ta_model->get_jurusan($data->npm);

        $pdf = new FPDF('P','mm',array(210,330));
        $pdf->setting_page_footer($numPage, $kode, $type);
        // LEMBAR VERIFIKASI
        $pdf->AddPage('P');
        $pdf->SetFillColor(205,205,205);
        $pdf->SetLeftMargin(10);
        $pdf->SetFont('Times','',12);
        $pdf->Ln(1);

        // CAP FAKULTAS
        $pdf->Ln(5);
        // Title
        $pdf->TableTitle('VERIFIKASI PERSYARATAN LAYANAN');
        // Header
        $pdf->TableHeader1(array('MIPA/'.$jurusan, $mhs->name));
        $pdf->TableHeader2(array('CAP FAKULTAS', $mhs->npm));
        // SubHeader
        $pdf->SetWidths(array(8,69,13,13,20,30,22,15));
        $pdf->SubHeader(array('No', 'Jenis Persyaratan', 'Sah', 'Benar', 'Lengkap', 'Tgl Penyelesaian', 'Verifikasi', 'Paraf'));

        // Isi
        $pdf->SetWidths(array(8,69,13,13,20,30,22,15));
        $pdf->SetAligns(array('C','L'));
        $pdf->SetSpacing(5);
        $pdf->Row(array('1',"Mendownload Form Layanan Legalisir Kartu Tanda Mahasiswa (KTM) di website FMIPA",'','','','','',''));
        $pdf->Row(array('2',"Fotocopy Kartu Tanda Mahasiswa (KTM) mahasiswa yang bersangkutan\n(maksimal 5 lembar)",'','','','','',''));
        $pdf->Row(array('3',"Menunjukan Kartu Tanda Mahasiswa (KTM) yang asli",'','','','','',''));
        $pdf->Row(array('4',"Fotocopy Bukti Pembayaran UKT terakhir yang sudah dilegalisir\n(1 lembar)",'','','','','',''));

        $pdf->Ln(5);
        // Title
        $pdf->TableTitle('VERIFIKASI PERSYARATAN LAYANAN');
        // Header
        $pdf->TableHeader1(array('MIPA/'.$jurusan, $mhs->name));
        $pdf->TableHeader2(array('CAP FAKULTAS', $mhs->npm));
        // SubHeader
        $pdf->SetWidths(array(8,69,13,13,20,30,22,15));
        $pdf->SubHeader(array('No', 'Jenis Persyaratan', 'Sah', 'Benar', 'Lengkap', 'Tgl Penyelesaian', 'Verifikasi', 'Paraf'));

        // Isi
        $pdf->SetWidths(array(8,69,13,13,20,30,22,15));
        $pdf->SetAligns(array('C','L'));
        $pdf->SetSpacing(5);
        $pdf->Row(array('1',"Dokumen yang telah ditandatangani oleh pejabat yang berwenang\n ",'','','','','',''));

        $pdf->Output('I','form_legalisir_ktm.pdf');
    }

    function form_38($data,$meta)
    {
        /*
         Form Peminjaman Gedung Dan Alat
        */

        /*Edit by   :1617051107
        date        :31/01/2020 */
         /*Edit by   :1617051088
        date        :30/10/2020 */

        $cek_atr = $this->layanan_model->select_layanan_atribut_by_id($data->id_layanan_fakultas);
        $n=0;
        if(empty($cek_atr)){
            for($m=0;$m<=99;$m++){
                $attr[$m] = "";
            }
        }
        else{
            foreach($meta as $metas)
            {
                $attr[$n] = $metas->meta_value;
                $n++;
            }
        }
        $count = count($meta);

        $numPage = '';
        $kode = 0;
        $type = '';
        $spasi= 6;
        $mhs = $this->user_model->get_mahasiswa_data_npm($data->npm);
        $status = $this->get_prodi_from_npm($data->npm);     
        $jurusan = $this->ta_model->get_jurusan($data->npm);

        $pdf = new FPDF('P','mm',array(210,330));
        $pdf->setting_page_footer($numPage, $kode, $type);
        $pdf->setting_no_header(array(1));
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);
        $pdf->AddPage();
        $pdf->SetFont('Times','',12);
        $pdf->Ln(5);
        $pdf->MultiCell(110, $spasi,'Kepada : Yth. Wakil Dekan Bidang Umum dan Keuangan',0,'L');
        $pdf->SetLeftMargin(46);
        $pdf->MultiCell(110, $spasi,'Fakultas MIPA Universitas Lampung', 0, 'L');
        $pdf->SetLeftMargin(30);
        $pdf->Ln(10);

        $pdf->MultiCell(150, $spasi,'Saya yang bertanda tangan di bawah ini:', 0, 'L');
        $pdf->SetLeftMargin(35);
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
        $pdf->Cell(45, $spasi,'Tahun Akademik', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $attr[0], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'UKM', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $attr[1], 0, 1, 'L');
        $pdf->SetLeftMargin(30);
        $pdf->Ln(2);
        $pdf->MultiCell(150, $spasi,'Dengan hormat saya mengajukan permohonan peminjaman gedung dan alat  dalam rangka '.$attr[2].' '.$attr[3]." pada :");
        $pdf->Ln(2);
        $pdf->SetLeftMargin(35);
        $pdf->Cell(45,$spasi,'Hari',0,0,'L');
        $pdf->Cell(5,$spasi,':',0,0,'C');
        $pdf->Cell(100,$spasi, $attr[4],0,1,'L');
        $pdf->Cell(45,$spasi,'Materi',0,0,'L');
        $pdf->Cell(5,$spasi,':',0,0,'C');
        $pdf->SetLeftMargin(30);
        $pdf->Cell(100,$spasi, $attr[5],0,1,'L');
        $pdf->Ln(2);
        $pdf->Cell(45,$spasi,'Bersama surat ini saya lampirkan :',0,1,'L');
        $pdf->SetLeftMargin(35);
        $pdf->Cell(5,$spasi,'1.',0,0,'L');
        $pdf->Cell(5,$spasi,'Foto Copy Kartu Mahasiswa',0,1,'L');
        $pdf->Cell(5,$spasi,'2.',0,0,'L');
        $pdf->Cell(5,$spasi,'Surat Permohonan Peminjaman',0,1,'L');
        $pdf->SetLeftMargin(30);
        $pdf->Ln(2);
        $pdf->MultiCell(150,$spasi,'Dengan permohonan saya, atas perhatian Ibu saya ucapkan terima kasih.',0,'J');
        $pdf->Ln(10);
        $pdf->SetX(120);
        $pdf->Cell(40, $spasi,'Bandar Lampung, '.$this->convert_date($data->created_at),0,1);
        $pdf->SetX(120);
        $pdf->Cell(35, $spasi,'Pemohon,',0,1);
        $pdf->SetX(120);
        $pdf->Cell(60, $spasi,$pdf->Image("$data->ttd",$pdf->GetX()-3, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');
        $pdf->Ln(20);
        $pdf->SetX(120);
        $pdf->Cell(60, $spasi, $mhs->name,0,1);
        $pdf->SetX(120);
        $pdf->Cell(60, $spasi, "NPM. ".$mhs->npm,0,1);

        //LEMBAR VERIFIKASI
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
        $pdf->SetSpacing($spasi);
        $pdf->Row(array('1',"Foto Copy Kartu Mahasiswa",'1 lbr.',''));
        $pdf->Row(array('2',"Surat Permohonan Peminjaman",'1 lbr.',''));

        $pdf->Output('I','form_legalisir_ktm.pdf');
    }

    function form_39($data,$meta)
    {
        /*
         SURAT IZIN PELAKSANAAN PENELITIAN DI LUAR JAM KERJA
        edit by : Fuad yudhi yahya
        */

        /*Edit by   :1617051107
        date        :31/01/2020 */
        /*Edit by   :1617051088
        date        :30/10/2020 */

        $cek_atr = $this->layanan_model->select_layanan_atribut_by_id($data->id_layanan_fakultas);
        $n=0;
        if(empty($cek_atr)){
            for($m=0;$m<=99;$m++){
                $attr[$m] = "";
            }
        }
        else{
            foreach($meta as $metas)
            {
                $attr[$n] = $metas->meta_value;
                $n++;
            }
        }
       
        $count = count($meta);

        $numPage = '/PM/MIPA/II.B/11';
        $nomor ='    /UN26.17/KU/'.date("Y");
        $spasi = 4.5;
        $spasi2 = 6;
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
        $pdf->SetLeftMargin(20);
        $pdf->SetTopMargin(20);
        $pdf->AddPage();
        $pdf->SetFont('Times','',11);
        $pdf->Cell(17, $spasi,'Perihal');
        $pdf->Cell(3, $spasi, ':');
        $pdf->SetFont('Times','I',11);
        $pdf->MultiCell(170,$spasi,'Permohonan izin pelaksanaan penelitian di luar jam kerja');
        $pdf->Ln(2);
        $pdf->SetFont('Times','',11);
        $pdf->Cell(110, $spasi,'Kepada Yth.',0,1,'L');
        $pdf->SetFont('Times','B',11);
        $pdf->MultiCell(110, $spasi,'Dekan FMIPA Universitas Lampung', 0, 'L');
        $pdf->SetFont('Times','',11);
        $pdf->MultiCell(110, $spasi,'di Tempat',0,'L');
        $pdf->Ln(2);
        $pdf->SetLeftMargin(20);
        $pdf->MultiCell(170, $spasi,'Saya yang bertanda tangan di bawah ini:', 0, 'L');
        $pdf->SetLeftMargin(30);
        $pdf->Cell(45, $spasi,'Nama', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$mhs->name, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'NPM', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $mhs->npm, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Fakultas/Jurusan', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,'Matematika dan Ilmu Pengetahuan Alam / '.$status['jurusan'], 0, 1, 'L');
        $almt = $this->wilayah_model->get_desa_by_id($mhs->kelurahan_desa);
        $pdf->SetWidths(array(45, 5, 85));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Alamat Lengkap',':',$mhs->jalan." Kelurahan ".$almt->desa." Kecamatan ".$almt->kecamatan." ".$almt->kabupaten." Provinsi ".$almt->provinsi." ".$mhs->kode_pos));
        $pdf->Cell(45, $spasi,'No. HP', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$mhs->mobile, 0, 1, 'L');
        $pdf->SetLeftMargin(20);
        $pdf->Ln(2);
        $pdf->MultiCell(170, $spasi,'Saat ini sedang melaksanakan penelitian untuk menyusun '.$status['ta'].' dengan :');
        $pdf->SetLeftMargin(30);
        //skripsi
        $ta = $this->ta_model->get_ta_aktif_npm($data->npm);
        if(empty($ta)){
            //judul
            $judul = "";
            //pembimbing
            $pb_name = "";
            $pb_nip = "";
        }
        else{
            //judul
            if($ta->judul_approve == 1){
                $judul = $ta->judul1;
            }
            else{
                $judul = $ta->judul2;
            }

            //pembimbing utama
            $pb = $this->user_model->get_dosen_data($ta->pembimbing1);
            $pb_name = $pb->gelar_depan." ".$pb->name.", ".$pb->gelar_belakang;
            $pb_nip = $pb->nip_nik;
        }
        $pdf->Cell(45, $spasi,'Judul '.$status['ta'], 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->MultiCell(110, $spasi,$judul);
        $pdf->Cell(45, $spasi,'Pembimbing Utama', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(5,$spasi,$pb_name,0,1,'L');
        $pdf->SetLeftMargin(20);
        $pdf->Ln(2);

        $pdf->MultiCell(170,$spasi,'Berdasarkan hal tersebut, dengan ini saya mohon agar dapat diberikan izin melaksanakan penelitian di luar jam kerja di Laboratorium '.$attr[0]." Pada :");
        $pdf->SetLeftMargin(30);
        $pdf->Cell(45, $spasi,'Tanggal Pelaksanaan', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,''.$this->convert_date($attr[1]).' s/d '.$this->convert_date($attr[2]), 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Waktu Pelaksanaan', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $attr[3].' s/d '.$attr[4], 0, 1, 'L');
        $pdf->SetLeftMargin(20);
        $pdf->Ln(2);

        $pdf->MultiCell(170,$spasi,'Dalam melaksanakan penelitian tersebut, saya bersedia untuk :');
        $pdf->Cell(5,$spasi,'1.',0,0,'L');
        $pdf->MultiCell(170,$spasi,'Berkoodinasi dengan petugas keamanan fakultas, terkait keamanan selama melaksanakan kegiatan tersebut',0,'L');
        $pdf->Cell(5,$spasi,'2.',0,0,'L');
        $pdf->MultiCell(170,$spasi,'Berkoodinasi dengan penjaga gedung dan petugas laboratorium terkait selama melaksanakan kegiatan tersebut',0,'L');
        $pdf->Cell(5,$spasi,'3.',0,0,'L');
        $pdf->MultiCell(170,$spasi,'Mentaati setiap peraturan yang berlaku di laboratorium tersebut',0,'L');
        $pdf->Cell(5,$spasi,'4.',0,0,'L');
        $pdf->MultiCell(170,$spasi,'Bertanggung jawab terhadap keamanan, ketertiban, dan kebersihan di laboratorium tersebut',0,'L');
        $pdf->Cell(5,$spasi,'5.',0,0,'L');
        $pdf->MultiCell(170,$spasi,'Tidak membawa teman yang berasal dari luar Jurusan '.$jurusan.' FMIPA Unila',0,'L');
        $pdf->Ln(2);

        $pdf->Cell(45,$spasi,'Sebagai bahan pertimbangan, berikut saya lampirkan  :',0,1,'L');
        $pdf->Cell(5,$spasi,'1.',0,0,'L');
        $pdf->Cell(5,$spasi,'Fotocopy KTM ',0,1,'L');
        $pdf->Cell(5,$spasi,'2.',0,0,'L');
        $pdf->Cell(5,$spasi,'Fotocopy Berita Acara Seminar Usul ',0,1,'L');
        $pdf->Cell(25,$spasi,'masing-masing');
        $pdf->SetFont('Times','BI',11);
        $pdf->Cell(14.5,$spasi, '1 (satu)');
        $pdf->SetFont('Times','',11);
        $pdf->Cell(7,$spasi, 'lembar.',0,1,'L');
        $pdf->Ln(3);

        $pdf->MultiCell(170,$spasi,'Demikian surat permohonan ini saya buat, atas perhatian dan perkenan dari Bapak, saya ucapkan terima kasih.',0,'J');
        $pdf->SetX(133);
        $pdf->MultiCell(70, $spasi, 'Bandar Lampung, '.$this->convert_date($data->created_at), 0, 'L');
        $pdf->SetX(133);
        $pdf->Cell(35, $spasi,'Hormat Saya,',0,1);
        $pdf->SetX(133);
        $pdf->Cell(60, $spasi,$pdf->Image("$data->ttd",$pdf->GetX()-3, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');
        $pdf->Ln(20);
        $pdf->SetX(133);
        $pdf->Cell(60, $spasi, $mhs->name,0,1);
        $pdf->SetX(133);
        $pdf->Cell(60, $spasi, "NPM. ".$mhs->npm,0,1);
        $pdf->Ln(10);

        $pdf->SetX(85);
        $pdf->SetFont('Times','B',11);
        $pdf->Cell(35, $spasi,'MENGETAHUI,',0,1,'C');
        $pdf->SetFont('Times','',11);
        
        //kajur
        if(empty($kajur)){
            $kajur_name = "";
            $kajur_nip = "";
        }
        else{
            $kajur_name = $kajur->gelar_depan." ".$kajur->name.", ".$kajur->gelar_belakang;
            $kajur_nip = $kajur->nip_nik;
        }

        //kalab
        $kalab = $this->layanan_model->get_kalab_by_nama($attr[0]);
        if(empty($kalab)){
            $kalab_name = "";
            $kalab_nip = "";
        }
        else{
            $kalab_name = $kalab->gelar_depan." ".$kalab->name.", ".$kalab->gelar_belakang;
            $kalab_nip = $kalab->nip_nik;
        }

        //tabel
        $pdf->SetLeftMargin(20);
        $pdf->SetWidths(array(10,45,48,37,30));
        $pdf->SetAligns(array('C','C','C','C','C'));
        $pdf->SetSpacing(5);
        $pdf->Row(array("NO", "Jabatan", "Nama", "NIP", "Tanda Tangan"));
        $pdf->SetBoldFont(array());
        $pdf->SetAligns(array('L','L','L','L','L','L','L','L'));
        $pdf->Row(array('1.','Pembimbing Utama',$pb_name, $pb_nip." "," "));

        $pdf->Row(array('2.','Kepala Lab. '.$attr[0],$kalab_name,$kalab_nip." "," "));

        $pdf->Row(array('3.','Pembimbing Akademik',$pa->gelar_depan." ".$pa->name.", ".$pa->gelar_belakang, $pa->nip_nik." "," "));

        $pdf->Row(array('4.','Ketua Jurusan '.$jurusan,$kajur_name,$kajur_nip." "," "));

        $pdf->SetLeftMargin(30);
        $pdf->Ln(5);

        //LEMBAR VERIFIKASI
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
        $pdf->SetSpacing($spasi2);
        $pdf->Row(array('1',"Fotocopy KTM",'1 lbr.',''));
        $pdf->Row(array('2',"Fotocopy Berita Acara Seminar Usul",'1 lbr.',''));


        $pdf->Output('I','surat_izin_penelitian.pdf');
    }

    function form_40($data,$meta)
    {
        /*
         FORM IZIN PELAKSANAAN PENELITIAN DI LUAR JAM KERJA
        */

        /*Edit by   :1617051107
        date        :31/01/2020 */

        $cek_atr = $this->layanan_model->select_layanan_atribut_by_id($data->id_layanan_fakultas);
        $n=0;
        if(empty($cek_atr)){
            for($m=0;$m<=99;$m++){
                $attr[$m] = "";
            }
        }
        else{
            foreach($meta as $metas)
            {
                $attr[$n] = $metas->meta_value;
                $n++;
            }
        }
       
        $count = count($meta);

        $numPage = '/PM/MIPA/II/05';
        $nomor ='    /UN26.17/KU/'.date("Y");

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
        // $pdf->setting_no_header(array(1));
        $pdf->SetLeftMargin(20);
        $pdf->SetTopMargin(20);

        $pdf->AddPage();
        $pdf->SetFont('Times','B',16);
        $pdf->Cell(190, $spasi+1, "SURAT IZIN",0,1,'C');
        $pdf->SetFont('Times','BU',16);
        $pdf->Cell(190, $spasi+1, "PELAKSANAAN PENELITIAN DI LUAR JAM KERJA",0,1,'C');
        $pdf->SetFont('Times','',12);
        $pdf->SetX(75);
        $pdf->Cell(10, $spasi, 'Nomor',0,0,'C');
        $pdf->Cell(5, $spasi, ':');
        $pdf->Cell(5, $spasi, $nomor);
        $pdf->Ln(10);
        $pdf->SetLeftMargin(20);
        $pdf->SetFont('Times','',12);
        $pdf->Cell(32, $spasi,'Berdasarkan surat ');
        $pdf->SetFont('Times','I',12);
        $pdf->Cell(100, $spasi,'Permohonan izin pelaksanaan penelitian di luar jam kerja');
        $pdf->SetFont('Times','',12);
        $pdf->Cell(40,$spasi,'dari mahasiswa berikut :', 0,1, 'L');
        $pdf->SetLeftMargin(35);
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
         //skripsi
         $ta = $this->ta_model->get_ta_aktif_npm($data->npm);
         if(empty($ta)){
             //judul
             $judul = "";
             //pembimbing
             $pb_name = "";
             $pb_nip = "";
         }
         else{
             //judul
             if($ta->judul_approve == 1){
                 $judul = $ta->judul1;
             }
             else{
                 $judul = $ta->judul2;
             }
 
             //pembimbing utama
             $pb = $this->user_model->get_dosen_data($ta->pembimbing1);
             $pb_name = $pb->gelar_depan." ".$pb->name.", ".$pb->gelar_belakang;
             $pb_nip = $pb->nip_nik;
         }

        $pdf->Cell(45, $spasi,'Judul Penelitian', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->MultiCell(110, $spasi, $judul);
        $pdf->Cell(45, $spasi,'Dosen Pembimbing 1', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $pb_name, 0, 1, 'L');
        $almt = $this->wilayah_model->get_desa_by_id($mhs->kelurahan_desa);
        $pdf->SetWidths(array(45, 5, 85));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Alamat Lengkap',':',$mhs->jalan." Kelurahan ".$almt->desa." Kecamatan ".$almt->kecamatan." ".$almt->kabupaten." Provinsi ".$almt->provinsi." ".$mhs->kode_pos));
        $pdf->Cell(45, $spasi,'No. HP', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $mhs->mobile, 0, 1, 'L');
        $pdf->SetLeftMargin(20);
        $pdf->Ln(2);
        $pdf->MultiCell(170, $spasi,'maka dengan ini Wakil Dekan Bidang Umum dan Keuangan FMIPA Unila memberikan izin kepada mahasiswa tersebut untuk melaksanakan penelitian di luar jam kerja yang dilaksanakan di : ');
        $pdf->SetLeftMargin(35);
        // $pdf->Ln(1);
        $pdf->Cell(45, $spasi,'Laboratorium', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $attr[0],0,1,'L');
        $pdf->Cell(45, $spasi,'Jurusan/Program Studi', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $status['jurusan'].'/'.$status['prodi'], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Tanggal Pelaksanaan', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,''.$this->convert_date($attr[1]).' s/d '.$this->convert_date($attr[2]), 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Waktu Pelaksanaan', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $attr[3].' s/d '.$attr[4], 0, 1, 'L');
        $pdf->SetLeftMargin(20);
        $pdf->Ln(2);
        $pdf->MultiCell(170, $spasi,'segala macam kegiatan yang menyangkut izin yang diberikan menjadi tanggung jawab dari mahasiswa yang bersangkutan, dan pemberian izin ini akan dibatalkan jika mahasiswa yang bersangkutan melanggar ketentuan yang berlaku di laboratorium tersebut maupun yang dikeluarkan oleh pihak fakultas.',0,'J');
        $pdf->Ln(2);
        $pdf->MultiCell(170, $spasi,'Demikian izin ini diberikan, agar dapat dipergunakan sebagaimana mestinya. Atas perhatian dan kerjasama yang baik kami ucapkan terima kasih',0,'J');
        $pdf->Ln(5);
        $pdf->SetX(115);
        $pdf->Cell(35, $spasi,'Bandar Lampung');
        $pdf->Cell(5, $spasi,':');
        $pdf->Cell(35, $spasi,'...............................',0,1);
        $pdf->SetX(115);
        $pdf->Cell(28, $spasi,'a.n. Dekan,',0,1);
        $pdf->SetX(115);
        $pdf->Cell(88, $spasi,'Wakil Dekan Bidang Umum dan Keuangan,',0,1);
        $pdf->Ln(15);
        $pdf->SetFont('Times','B',12);
        $pdf->SetX(115);

        $wd2 = $this->layanan_model->get_tugas_tambahan_user('Wakil Dekan Bidang Umum dan Keuangan');
        if(empty($wd2)){
            $wd2_name = "";
            $wd2_nip = "";
            $wd2_pangkat = "";
        }
        else{
            $wd2_name = $wd2->gelar_depan." ".$wd2->name.", ".$wd2->gelar_belakang;
            $wd2_nip = $wd2->nip_nik;
            if($wd2->pangkat_gol == NULL || $wd2->pangkat_gol == "" ){
                $wd2_pangkat = "";
            }
            else{
                $wd2_pkt = $this->user_model->get_pangkat_gol_by_id($wd2->pangkat_gol);
                $wd2_pangkat = $wd2_pkt->pangkat."/".$wd2_pkt->golongan." ".$wd2_pkt->ruang;
            }
            
        }

        $pdf->Cell(68, $spasi,$wd2_name,0,1,'L');
        $pdf->SetFont('Times','',12);
        $pdf->SetX(115);
        $pdf->Cell(68, $spasi, "NIP. ".$wd2_nip);
        $pdf->Ln(5);
        $pdf->Cell(50,$spasi,'Tembusan',0,1,'L');
        $pdf->Cell(5,$spasi,'1. ',0,0,'L');
        $pdf->Cell(45,$spasi,'Dekan',0,1,'L');
        $pdf->Cell(5,$spasi,'2. ',0,0,'L');
        $pdf->Cell(26,$spasi,'Ketua Jurusan ',0,0,'L');
        $pdf->Cell(5,$spasi,$jurusan,0,1,'L');
        $pdf->Cell(5,$spasi,'3. ',0,0,'L');
        $pdf->Cell(36,$spasi,'Ketua Laboratorium ',0,0,'L');
        $pdf->Cell(5,$spasi,$attr[0],0,1,'L');
        $pdf->Cell(5,$spasi,'4. ',0,0,'L');
        $pdf->Cell(45,$spasi,'Petugas Keamanan Fakultas. ',0,1,'L');

        //LEMBAR VERIFIKASI
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

        // SubHeader
        $pdf->SetWidths(array(8,90,35,35));
        $pdf->SubHeader(array('No', 'Jenis Persyaratan', 'Jumlah', 'Verifikasi'));

        // Isi
        $pdf->SetAligns(array('C','L'));
        $pdf->SetSpacing($spasi);
        $pdf->Row(array('1',"Surat Pemberian Izin Melaksanakan Penelitian di luar jam kerja yang telah didownload dari website",'2 lbr.',''));
        $pdf->Row(array('2',"Surat Permohonan dari Mahasiswa bersangkutan",'1 lbr.',''));
        $pdf->Row(array('3',"Fotocopy KTM",'1 lbr.',''));
        $pdf->Row(array('4',"Fotocopy Berita Acara Seminar Usul",'1 lbr.',''));
        $pdf->Row(array('5',"Fotocopy Bukti Pembayaran UKT terakhir",'1 lbr.',''));

        $pdf->Output('I','form_izin_penelitian.pdf');
    }

    function form_41($data,$meta)
    {
        /*
         Form Keringanan Pembayaran SPP
        */

        /*Edit by   :1617051107
        date        :31/01/2020 */
        /*Edit by   :1617051088
        date        :31/10/2020 */

        $cek_atr = $this->layanan_model->select_layanan_atribut_by_id($data->id_layanan_fakultas);
        $n=0;
        if(empty($cek_atr)){
            for($m=0;$m<=99;$m++){
                $attr[$m] = "";
            }
        }
        else{
            foreach($meta as $metas)
            {
                $attr[$n] = $metas->meta_value;
                $n++;
            }
        }
       
        $count = count($meta);

        $numPage = '/PM/MIPA/II.B/11';
        $nomor ='    /UN26.17/KU/'.date("Y");

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
        $pdf->SetLeftMargin(20);
        $pdf->SetTopMargin(20);

        $pdf->AddPage();
        $pdf->SetFont('Times','',12);
        $pdf->Cell(17, $spasi,'Perihal');
        $pdf->Cell(3, $spasi, ':');
        $pdf->MultiCell(80,$spasi,'Permohonan Keringanan Pembayaran UKT Semester '.$attr[0]." TA ".$attr[1],0,'L');
        $pdf->Ln(5);
        $pdf->Cell(110, $spasi,'Kepada Yth.',0,1,'L');
        $pdf->SetFont('Times','B',12);
        $pdf->MultiCell(110,$spasi,'Wakil Dekan Bidang Umum dan Keuangan', 0, 'L');
        $pdf->SetLeftMargin(30);
        $pdf->SetFont('Times','',12);
        $pdf->MultiCell(110, $spasi,'FMIPA Universitas Lampung', 0, 'L');
        $pdf->MultiCell(110, $spasi,'di Bandar Lampung',0,'L');
        $pdf->SetLeftMargin(30);
        $pdf->Ln(5);
        $pdf->MultiCell(150, $spasi,'Saya yang bertanda tangan di bawah ini:', 0, 'L');
        $pdf->Ln(2);
        $pdf->SetLeftMargin(40);
        $pdf->Cell(45, $spasi,'Nama', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$mhs->name, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'NPM', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $mhs->npm, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Jurusan/Program Studi', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $status['jurusan'].'/'.$status['prodi'], 0, 1, 'L');
        $almt = $this->wilayah_model->get_desa_by_id($mhs->kelurahan_desa);
        $pdf->SetWidths(array(45, 5, 85));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Alamat Lengkap',':',$mhs->jalan." Kelurahan ".$almt->desa." Kecamatan ".$almt->kecamatan." ".$almt->kabupaten." Provinsi ".$almt->provinsi." ".$mhs->kode_pos));
        $pdf->SetLeftMargin(30);

        //skripsi
        $ta = $this->ta_model->get_ta_aktif_npm($data->npm);
        if(empty($ta)){
            //judul
            $judul = "";
            //pembimbing
            $pb_name = "";
            $pb_nip = "";
        }
        else{
            //judul
            if($ta->judul_approve == 1){
                $judul = $ta->judul1;
            }
            else{
                $judul = $ta->judul2;
            }

            //pembimbing utama
            $pb = $this->user_model->get_dosen_data($ta->pembimbing1);
            $pb_name = $pb->gelar_depan." ".$pb->name.", ".$pb->gelar_belakang;
            $pb_nip = $pb->nip_nik;
        }

        $pdf->Ln(5);
        $pdf->MultiCell(150, $spasi,'Bahwa saya telah melaksanakan Ujian '.$status['ta'].' dan menyelesaikan penulisan '.$status['ta'].' dengan judul "'.$judul.'" dan belum menyerahkan '.$status['ta'].' tersebut kepada pihak Universitas, Fakultas, dan Jurusan yang merupakan syarat untuk wisuda.',0,'J');
        $pdf->Ln(5);
        $pdf->MultiCell(150, $spasi,'Sehubugan dengan hal tersebut dengan ini saya mohon dapat diberi keringanan dalam membayar SPP/UKT pada Semester '.$attr[0]." TA ".$attr[1]." Sebagai bahan pertimbangan bersama ini saya lampirkan: ",0,'J');
        $pdf->Cell(5,$spasi,'1.',0,0,'L');
        $pdf->Cell(5,$spasi,'Transkrip Akademik terakhir;',0,1,'L');
        $pdf->Cell(5,$spasi,'2.',0,0,'L');
        $pdf->Cell(5,$spasi,'Foto kopi Bukti Pembayaran SPP/UKT semester 1 s/d semester terakhir (legalisir);',0,1,'L');
        $pdf->Cell(5,$spasi,'3.',0,0,'L');
        $pdf->MultiCell(145,$spasi,'Foto kopi Berita Acara seminar usul/proposal (seminar 1) atau seminar hasil (seminar 2) atau Ujian '.$status['ta'].' dalam penyusunan '.$status['ta'].' dan dinyatakan lulus;',0,'L');
        $pdf->Cell(5,$spasi,'4.',0,0,'L');
        $pdf->Cell(5,$spasi,'Foto kopi KTM (legalisir);',0,1,'L');
        $pdf->Cell(27,$spasi,'masing-masing');
        $pdf->SetFont('Times','BI',12);
        $pdf->Cell(13,$spasi, '2 (dua)');
        $pdf->SetFont('Times','',12);
        $pdf->Cell(7,$spasi, 'lembar.',0,1,'L');
        $pdf->Ln(5);
        $pdf->MultiCell(150,$spasi,'Demikian permohonan ini untuk dapat diproses sesuai dengan ketentuan yang berlaku. Atas perhatian dan bantuan Bapak/Ibu, saya ucapkan terima kasih.',0,'J');
        $pdf->Ln(5);
        $pdf->SetX(120);
        $pdf->MultiCell(70, $spasi, 'Bandar Lampung, '.$this->convert_date($data->created_at), 0, 'L');
        $pdf->SetX(120);
        $pdf->Cell(35, $spasi,'Hormat Saya,',0,1);
        $pdf->SetX(120);
        $pdf->Cell(60, $spasi,$pdf->Image("$data->ttd",$pdf->GetX()-3, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');
        $pdf->Ln(20);
        $pdf->SetX(120);
        $pdf->Cell(60, $spasi, $mhs->name,0,1);
        $pdf->SetX(120);
        $pdf->Cell(60, $spasi, "NPM. ".$mhs->npm,0,1);

        // page 2
        $pdf->SetLeftMargin(20);
        $pdf->AddPage();


        $pdf->SetFont('Times','',12);
        $pdf->SetLeftMargin(20);
        $pdf->Cell(17, $spasi, 'Nomor');
        $pdf->Cell(3, $spasi, ':');
        $pdf->Cell(50, $spasi, $nomor,0, 0, 'L');
        $pdf->SetX(130);
        $pdf->MultiCell(70, $spasi, 'Bandar Lampung, ..........................', 0, 'L');
        $pdf->Cell(17, $spasi, 'Lampiran');
        $pdf->Cell(3, $spasi, ':');
        $pdf->Cell(5, $spasi, "1 (satu) berkas",0, 1, 'L');
        $pdf->Cell(17, $spasi, 'Perihal');
        $pdf->Cell(3, $spasi, ':');
        $pdf->MultiCell(80,$spasi,'Permohonan Keringanan Pembayaran UKT Semester '.$attr[0]." TA ".$attr[1],0,'L');
        $pdf->Ln(2);
        $pdf->Cell(110, $spasi,'Kepada Yth.',0,1,'L');
        $pdf->SetFont('Times','B',12);
        $pdf->MultiCell(170, $spasi,'Wakil Rektor Bidang Umum dan Keuangan', 0, 'L');
        $pdf->SetFont('Times','',12);
        $pdf->MultiCell(170, $spasi,'Universitas Lampung', 0, 'L');
        $pdf->MultiCell(170, $spasi,'di Bandar Lampung', 0, 'L');
        $pdf->SetLeftMargin(20);
        $pdf->Ln(2);
        $pdf->MultiCell(170, $spasi,'Dengan ini kami sampaikan permohonan keringanan Pembayaran UKT Semester '.$attr[0]." Tahun Akademik ".$attr[1].", atas nama: ",0,'J');

        // tabel
        $pdf->SetLeftMargin(22);
        $pdf->SetWidths(array(10,25,25,28,28,28,28));
        $pdf->SetAligns(array('C','C','C','C','C','C','C'));
        $pdf->SetSpacing(6);
        $pdf->Row(array("NO", "Nama", "NPM", "Jurusan/Prodi", "100%", "Keringanan ...%", "Yang Dibayarkan"));
        $pdf->SetBoldFont(array());
        $pdf->SetAligns(array('C','L','L','L','L','L','L'));
        $pdf->Row(array('1.', $mhs->name, $mhs->npm, $status['jurusan'].'/'.$status['prodi'], 'Rp. '.number_format($attr[2])," "," "));
        $pdf->SetLeftMargin(20);
        $pdf->Ln(5);

        $pdf->MultiCell(170, $spasi,'Berdasarkan Surat Keputusan Rektor Nomor 355/UN26/KU/2020 tanggal 17 Januari 2020, Permohonan Keringanan Pembayaran SPP/UKT diajukan bagi mahasiswa yang sedang menyusun atau menyelesaikan '.$status['ta'].' namun belum menyerahkan '.$status['ta'].' kepada pihak Unila, sehingga diberikan keringanan sebesar .....% dari besaran kelompok SPP/UKT yang dibayarkan, yaitu sebesar Rp. '.number_format($attr[2],0,',','.').' ('.$this->convert_terbilang($attr[2]).' rupiah ).');
        $pdf->Ln(5);
        $pdf->Cell(45,$spasi,'Sebagai bahan pertimbangan bersama ini kami lampirkan :',0,1,'L');

        $pdf->Cell(5,$spasi,'1.',0,0,'L');
        $pdf->MultiCell(150,$spasi,'Surat permohonan keringanan pembayaran SPP/UKT dari mahasiswa bersangkutan;',0,'L');
        $pdf->Cell(5,$spasi,'2.',0,0,'L');
        $pdf->Cell(5,$spasi,'Transkrip Akademik terakhir;',0,1,'L');
        $pdf->Cell(5,$spasi,'3.',0,0,'L');
        $pdf->MultiCell(150,$spasi,'Foto kopi Bukti Pembayaran SPP/UKT  semester 1 s/d semester terakhir (legalisir);',0,'L');
        $pdf->Cell(5,$spasi,'4.',0,0,'L');
        $pdf->MultiCell(160,$spasi,'Foto kopi Berita Acara seminar usul/proposal (seminar 1) atau seminar hasil (seminar 2) atau ujian skripsi dalam penyusunan '.$status['ta'].' dan dinyatakan lulus;',0,'L');
        $pdf->Cell(5,$spasi,'5.',0,0,'L');
        $pdf->Cell(5,$spasi,'Foto kopi KTM (legalisir);',0,1,'L');
        $pdf->Cell(27,$spasi,'masing-masing');
        $pdf->SetFont('Times','BI',12);
        $pdf->Cell(13,$spasi, '2 (dua)');
        $pdf->SetFont('Times','',12);
        $pdf->Cell(7,$spasi, 'lembar.',0,1,'L');
        $pdf->Ln(2);
        $pdf->MultiCell(150,$spasi,'Atas perhatian dan bantuan Bapak/Ibu, saya ucapkan terima kasih.',0,'J');
        $pdf->Ln(2);

        $wd2 = $this->layanan_model->get_tugas_tambahan_user('Wakil Dekan Bidang Umum dan Keuangan');
        if(empty($wd2)){
            $wd2_name = "";
            $wd2_nip = "";
            $wd2_pangkat = "";
        }
        else{
            $wd2_name = $wd2->gelar_depan." ".$wd2->name.", ".$wd2->gelar_belakang;
            $wd2_nip = $wd2->nip_nik;
            if($wd2->pangkat_gol == NULL || $wd2->pangkat_gol == "" ){
                $wd2_pangkat = "";
            }
            else{
                $wd2_pkt = $this->user_model->get_pangkat_gol_by_id($wd2->pangkat_gol);
                $wd2_pangkat = $wd2_pkt->pangkat."/".$wd2_pkt->golongan." ".$wd2_pkt->ruang;
            }
            
        }

        $pdf->SetX(120);
        $pdf->Cell(35, $spasi,'a.n. Dekan ,',0,1);
        $pdf->SetX(120);
        $pdf->Cell(35, $spasi,'Wakil Dekan Bidang Umum dan Keuangan,',0,1);
        $pdf->Ln(20);
        $pdf->SetX(120);
        $pdf->Cell(105, $spasi, $wd2_name,0,1,'L');

        $pdf->SetX(120);
        $pdf->Cell(105, $spasi, "NIP. ".$wd2_nip);
        $pdf->Ln();
        $pdf->Cell(50,$spasi,'Tembusan',0,1,'L');
        $pdf->Cell(5,$spasi,'1. ',0,0,'L');
        $pdf->Cell(45,$spasi,'Dekan FMIPA Unila;',0,1,'L');
        $pdf->Cell(5,$spasi,'2. ',0,0,'L');
        $pdf->Cell(45,$spasi,'Kepala BUK Unila. ',0,1,'L');

        //LEMBAR VERIFIKASI
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
        // SubHeader
        $pdf->SetWidths(array(8,90,35,35));
        $pdf->SubHeader(array('No', 'Jenis Persyaratan', 'Jumlah', 'Verifikasi'));

        // Isi
        $pdf->SetAligns(array('C','L'));
        $pdf->SetSpacing($spasi);
        $pdf->Row(array('1',"Surat permohonan keringanan pembayaran UKT dari mahasiswa bersangkutan.\n",'2 Lembar',''));
        $pdf->Row(array('2',"Transkrip Akademik terakhir .\n",'2 lembar',''));
        $pdf->Row(array('3',"Foto kopi Bukti Pembayaran SPP/UKT  semester 1 s/d semester terakhir (legalisir).\n",'2 lembar',''));
        $pdf->Row(array('4',"Foto kopi Berita Acara seminar usul/proposal (seminar 1) atau seminar hasil (seminar 2) atau ujian skripsi dalam penyusunan tugas akhir/skripsi/tesis/disertasi dan dinyatakan lulus.\n",'2 lembar',''));
        $pdf->Row(array('5',"Foto kopi KTM (legalisir).",'1 Lembar',''));


        $pdf->Output('I','form_keringanan_spp.pdf');
    }

    function form_42($data,$meta)
    {
        /*
         Form Permohonan Pembayaran SPP
        */

        /*Edit by   :1617051107
        date        :31/01/2020 */
        /*Edit by   :1617051088
        date        :31/10/2020 */

        $cek_atr = $this->layanan_model->select_layanan_atribut_by_id($data->id_layanan_fakultas);
        $n=0;
        if(empty($cek_atr)){
            for($m=0;$m<=99;$m++){
                $attr[$m] = "";
            }
        }
        else{
            foreach($meta as $metas)
            {
                $attr[$n] = $metas->meta_value;
                $n++;
            }
        }
       
        $count = count($meta);

        $numPage = '/PM/MIPA/II.B/10';
        $nomor ='    /UN26.17/KU/'.date("Y");

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
        $pdf->SetLeftMargin(20);
        $pdf->SetTopMargin(20);
        $pdf->AddPage();
        $pdf->SetFont('Times','',12);
        $pdf->Cell(17, $spasi, 'Nomor');
        $pdf->Cell(3, $spasi, ':');
        $pdf->Cell(50, $spasi, $nomor,0, 0, 'L');
        $pdf->SetX(130);
        $pdf->MultiCell(70, $spasi, 'Bandar Lampung, ..........................', 0, 'L');
        $pdf->Cell(17, $spasi, 'Lampiran');
        $pdf->Cell(3, $spasi, ':');
        $pdf->Cell(5, $spasi, "1 (satu) berkas",0, 1, 'L');
        $pdf->Cell(17, $spasi, 'Perihal');
        $pdf->Cell(3, $spasi, ':');
        $pdf->MultiCell(80,$spasi,'Permohonan Pembayaran UKT Semester '.$attr[0]." TA ".$attr[1],0,'L');
        $pdf->Ln(2);
        $pdf->Cell(110, $spasi,'Kepada Yth.',0,1,'L');
        $pdf->SetFont('Times','B',12);
        $pdf->MultiCell(110,$spasi,'Wakil Dekan Bidang Umum dan Keuangan', 0, 'L');
        $pdf->SetLeftMargin(20);
        $pdf->SetFont('Times','',12);
        $pdf->MultiCell(110, $spasi,'FMIPA Universitas Lampung', 0, 'L');
        $pdf->MultiCell(110, $spasi,'di Bandar Lampung',0,'L');
        $pdf->SetLeftMargin(20);
        $pdf->Ln(5);
        $pdf->MultiCell(160, $spasi,'Bersama ini kami sampaikan surat permohonan pembayaran SPP atas nama: ',0,'J');
        $pdf->Ln(2);

        // tabel
        $pdf->SetLeftMargin(22);
        $pdf->SetWidths(array(10,60,30,30,33));
        $pdf->SetAligns(array('C','C','C','C','C'));
        $pdf->SetSpacing(6);
        $pdf->Row(array("No", "Nama/NPM", "Jurusan","Program Studi", "Ket."));
        $pdf->SetBoldFont(array());
        $pdf->SetAligns(array('L','L','L','L','L'));
        $pdf->Row(array('1.', $mhs->name.'/'.$mhs->npm, $status['jurusan'],$status['prodi'], 'Semester '.$status['semester']));
        $pdf->SetLeftMargin(20);
        $pdf->Ln(5);
        $pdf->MultiCell(170, $spasi,'Dikarenakan yang bersangkutan belum membayar UKT Semester '.$attr[0]." TA ".$attr[1].", untuk itu kami mohon agar mahasiswa tersebut diberi kesempatan untuk membayar UKT untuk semester tersebut. Sebagai bahan pertimbangan bersama ini kami lampirkan:",0,'J');
        $pdf->SetLeftMargin(22);
        $pdf->Cell(5,$spasi,'1.',0,0,'L');
        $pdf->Cell(5,$spasi,'Foto Kopi Bukti Pembayaran Pembayaran SPP Sebelumnya;',0,1,'L');
        $pdf->Cell(5,$spasi,'2.',0,0,'L');
        $pdf->Cell(5,$spasi,'Surat Keterangan Aktif Kuliah dari mahasiswa bersangkutan;',0,1,'L');
        $pdf->Cell(5,$spasi,'3.',0,0,'L');
        $pdf->Cell(5,$spasi,'Fotokopi KTM.',0,1,'L');
        $pdf->SetLeftMargin(20);
        $pdf->Ln(5);
        $pdf->MultiCell(150,$spasi,'Demikian atas perhatian dan kerjasama yang baik kami ucapkan terima kasih.',0,'J');
        
        $tu = $this->layanan_model->get_tugas_tambahan_user('Kepala Bagian Tata Usaha');
        if(empty($tu)){
            $tu_name = "";
            $tu_nip = "";
            $tu_pangkat = "";
        }
        else{
            $tu_name = $tu->gelar_depan." ".$tu->name.", ".$tu->gelar_belakang;
            $tu_nip = $tu->nip_nik;
            if($tu->pangkat_gol == NULL || $tu->pangkat_gol == "" ){
                $tu_pangkat = "";
            }
            else{
                $tu_pkt = $this->user_model->get_pangkat_gol_by_id($tu->pangkat_gol);
                $tu_pangkat = $tu_pkt->pangkat."/".$tu_pkt->golongan." ".$tu_pkt->ruang;
            }
            
        }
        
        $pdf->Ln(10);
        $pdf->SetX(130);
        $pdf->Cell(35, $spasi,'Kabag. Tata Usaha,',0,1);
        $pdf->Ln(20);
        $pdf->SetX(130);
        $pdf->Cell(105, $spasi, $tu_name,0,1,'L');
        $pdf->SetX(130);
        $pdf->Cell(105, $spasi, "NIP. ".$tu_nip);

        $pdf->Ln(40);
        $pdf->Cell(50,$spasi,'Tembusan',0,1,'L');
        $pdf->Cell(5,$spasi,'1. ',0,0,'L');
        $pdf->Cell(45,$spasi,'Dekan FMIPA Unila;',0,1,'L');
        $pdf->Cell(5,$spasi,'2. ',0,0,'L');
        $pdf->Cell(45,$spasi,'Kepala BUK Unila. ',0,1,'L');

        //LEMBAR VERIFIKASI
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
        // SubHeader
        $pdf->SetWidths(array(8,90,35,35));
        $pdf->SubHeader(array('No', 'Jenis Persyaratan', 'Jumlah', 'Verifikasi'));

        // Isi
        $pdf->SetAligns(array('C','L'));
        $pdf->SetSpacing($spasi);
        $pdf->Row(array('1',"Surat permohonan keterlambatan pembayaran UKT dari mahasiswa bersangkutan;\n",'2 Lembar',''));
        $pdf->Row(array('2',"Foto kopi Bukti Pembayaran SPP/UKT  terakhir (legalisir);\n",'2 lembar',''));
        $pdf->Row(array('3',"Foto kopi KTM (legalisir);\n",'2 lembar',''));

        $pdf->Output('I','form_keterlambatan_spp.pdf');
    }

    function form_43($data,$meta)
    {
        /*
         Form BEBAS PEMBAYARAN UKT SPP
        */

        /*Edit by   :1617051107
        date        :31/01/2020 */
        /*Edit by   :1617051088
        date        :31/01/2020 */

        $cek_atr = $this->layanan_model->select_layanan_atribut_by_id($data->id_layanan_fakultas);
        $n=0;
        if(empty($cek_atr)){
            for($m=0;$m<=99;$m++){
                $attr[$m] = "";
            }
        }
        else{
            foreach($meta as $metas)
            {
                $attr[$n] = $metas->meta_value;
                $n++;
            }
        }
       
        $count = count($meta);

        $numPage = '/PM/MIPA/II.B/12';
        $nomor ='    /UN26.17/KU/'.date("Y");

        $spasi = 6;
        $kode = 0;
        $type = '';
        $mhs = $this->user_model->get_mahasiswa_data_npm($data->npm);
        $pa = $this->user_model->get_dosen_pa_by_npm($data->npm);
        $kajur = $this->user_model->get_kajur_by_npm($data->npm);
        $jurusan = $this->ta_model->get_jurusan($data->npm);
        $status = $this->get_prodi_from_npm($data->npm);       
        
        $pdf = new FPDF('P','mm',array(210,330));
        $pdf->setting_page_footer($numPage, $kode, $type);
        $pdf->setting_no_header(array(1));
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(15);
        $pdf->AddPage();
        $pdf->SetFont('Times','',12);
        $pdf->Cell(17, $spasi,'Perihal');
        $pdf->Cell(3, $spasi, ':');
        $pdf->MultiCell(80,$spasi,'Permohonan Pembebasan Pembayaran UKT Semester '.$attr[1]." TA ".$attr[2],0,'L');
        $pdf->Ln(3);
        $pdf->Cell(110, $spasi,'Kepada Yth.',0,1,'L');
        $pdf->SetFont('Times','B',12);
        $pdf->MultiCell(110,$spasi,'Wakil Dekan Bidang Umum dan Keuangan', 0, 'L');
        $pdf->SetLeftMargin(30);
        $pdf->SetFont('Times','',12);
        $pdf->MultiCell(110, $spasi,'FMIPA Universitas Lampung', 0, 'L');
        $pdf->MultiCell(110, $spasi,'di Bandar Lampung',0,'L');
        $pdf->SetLeftMargin(30);
        $pdf->Ln(3);
        $pdf->MultiCell(150, $spasi,'Saya yang bertanda tangan di bawah ini:', 0, 'L');
        $pdf->Ln(2);
        $pdf->SetLeftMargin(40);
        $pdf->Cell(45, $spasi,'Nama', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi,$mhs->name, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'NPM', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $mhs->npm, 0, 1, 'L');
        $pdf->Cell(45, $spasi,'Jurusan/Program Studi', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $status['jurusan'].'/'.$status['prodi'], 0, 1, 'L');
        $almt = $this->wilayah_model->get_desa_by_id($mhs->kelurahan_desa);
        $pdf->SetWidths(array(45, 5, 85));
        $pdf->SetAligns(array('L','C','L'));
        $pdf->RowNoBorder(array('Alamat Lengkap',':',$mhs->jalan." Kelurahan ".$almt->desa." Kecamatan ".$almt->kecamatan." ".$almt->kabupaten." Provinsi ".$almt->provinsi." ".$mhs->kode_pos));
        $pdf->SetLeftMargin(30);
        $pdf->Ln(3);
        //skripsi
        $ta = $this->ta_model->get_ta_aktif_npm($data->npm);
        if(empty($ta)){
            //judul
            $judul = "";
            //pembimbing
            $pb_name = "";
            $pb_nip = "";
        }
        else{
            //judul
            if($ta->judul_approve == 1){
                $judul = $ta->judul1;
            }
            else{
                $judul = $ta->judul2;
            }

            //pembimbing utama
            $pb = $this->user_model->get_dosen_data($ta->pembimbing1);
            $pb_name = $pb->gelar_depan." ".$pb->name.", ".$pb->gelar_belakang;
            $pb_nip = $pb->nip_nik;
        }

        $pdf->MultiCell(150, $spasi,'Bahwa saya telah melaksanakan Ujian Skripsi dan menyelesaikan penulisan skripsi dengan judul "'.$judul.'" dan telah menyerahkan '.$status['ta'].' tersebut kepada pihak Universitas, Fakultas, dan Jurusan yang merupakan syarat untuk wisuda.');
        $pdf->Ln(3);
        $pdf->MultiCell(150, $spasi,'Sehubugan dengan hal tersebut dengan ini saya mohon dapat dapat dibebaskan dari kewajiban membayar SPP/UKT pada Semester '.$attr[1]." TA ".$attr[2]." Sebagai bahan pertimbangan bersama ini saya lampirkan: ");
        $pdf->Cell(5,$spasi,'1.',0,0,'L');
        $pdf->MultiCell(150,$spasi,'Transkrip Akademik terakhiryang sudah memuat nilai ujian '.$status['ta'].';',0,'L');
        $pdf->Cell(5,$spasi,'2.',0,0,'L');
        $pdf->Cell(5,$spasi,'Foto kopi Bukti Pembayaran SPP/UKT  semester 1 s/d semester terakhir (legalisir);',0,1,'L');
        $pdf->Cell(5,$spasi,'3.',0,0,'L');
        $pdf->MultiCell(145,$spasi,'Foto kopi Berita Acara ujian '.$status['ta'].' dan dinyatakan lulus;',0,'L');
        $pdf->Cell(5,$spasi,'4.',0,0,'L');
        $pdf->Cell(5,$spasi,'Foto kopi KTM (legalisir);',0,1,'L');
        $pdf->Cell(5,$spasi,'5.',0,0,'L');
        $pdf->Cell(5,$spasi,'Fotokopi Bebas Perpustakaan Universitas;',0,1,'L');
        $pdf->Cell(5,$spasi,'6.',0,0,'L');
        $pdf->Cell(5,$spasi,'Fotokopi Bukti Penyerahan '.$status['ta'].'.',0,1,'L');
        $pdf->Cell(27,$spasi,'masing-masing');
        $pdf->SetFont('Times','BI',12);
        $pdf->Cell(13,$spasi, '2 (dua)');
        $pdf->SetFont('Times','',12);
        $pdf->Cell(7,$spasi, 'lembar.',0,1,'L');

        $pdf->Ln(3);
        $pdf->MultiCell(150,$spasi,'Demikian permohonan ini untuk dapat diproses sesuai dengan ketentuan yang berlaku. Atas perhatian dan bantuan Ibu, saya ucapkan terima kasih.',0,'J');
        $pdf->Ln(3);
        $pdf->SetX(120);
        $pdf->MultiCell(70, $spasi, 'Bandar Lampung, '.$this->convert_date($attr[0]), 0, 'L');
        $pdf->SetX(120);
        $pdf->Cell(35, $spasi,'Hormat Saya,',0,1);
        $pdf->SetX(120);
        $pdf->Cell(60, $spasi,$pdf->Image("$data->ttd",$pdf->GetX()-3, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');
        $pdf->Ln(20);
        $pdf->SetX(120);
        $pdf->Cell(60, $spasi, $mhs->name,0,1);
        $pdf->SetX(120);
        $pdf->Cell(60, $spasi, "NPM. ".$mhs->npm,0,1);

        // page 2
        $pdf->SetLeftMargin(25);
        $pdf->AddPage();

        $pdf->SetFont('Times','',12);

        $pdf->Cell(17, $spasi, 'Nomor');
        $pdf->Cell(3, $spasi, ':');
        $pdf->Cell(50, $spasi, $nomor,0, 0, 'L');
        $pdf->SetX(125);
        $pdf->MultiCell(70, $spasi, 'Bandar Lampung, ..........................', 0, 'L');
        $pdf->Cell(17, $spasi, 'Lampiran');
        $pdf->Cell(3, $spasi, ':');
        $pdf->Cell(5, $spasi, "1 (satu) berkas",0, 1, 'L');
        $pdf->Cell(17, $spasi, 'Perihal');
        $pdf->Cell(3, $spasi, ':');
        $pdf->MultiCell(80,$spasi,'Permohonan Pembebasan Pembayaran UKT Semester '.$attr[1]." TA ".$attr[2],0,'L');
        $pdf->Ln(2);
        $pdf->Cell(110, $spasi,'Kepada Yth.',0,1,'L');
        $pdf->SetFont('Times','B',12);
        $pdf->MultiCell(170, $spasi,'Wakil Rektor Bidang Umum dan Keuangan', 0, 'L');
        $pdf->SetFont('Times','',12);
        $pdf->MultiCell(170, $spasi,'Universitas Lampung', 0, 'L');
        $pdf->MultiCell(170, $spasi,'di Bandar Lampung', 0, 'L');
        $pdf->Ln(2);
        $pdf->MultiCell(160, $spasi,'Dengan ini kami laporkan bahwa mahasiswa kami :',0,'J');
        $pdf->Ln(2);
        // tabel
        $pdf->SetLeftMargin(27);
        $pdf->SetWidths(array(10,60,25,30,30));
        $pdf->SetAligns(array('C','C','C','C','C'));
        $pdf->SetSpacing(6);
        $pdf->Row(array("NO", "Nama", "NPM", "Jurusan", "Tgl.Lulus"));
        $pdf->SetBoldFont(array());
        $pdf->SetAligns(array('L','L','L','L','L','L','L','L'));
        $pdf->Row(array('1.', $mhs->name, $mhs->npm, $status['jurusan']," " .$this->convert_date($attr[0])));
        $pdf->SetLeftMargin(25);
        $pdf->Ln(5);

        $pdf->MultiCell(160, $spasi,'Berdasarkan Surat Keputusan Rektor Nomor 355/UN26/KU/2020 tanggal 17 Januari 2020, Permohonan Pembebasan Pembayaran SPP/UKT diajukan bagi mahasiswa yang telah menyelesaikan Ujian '.$status['ta'].' dan telah menyerahkan '.$status['ta'].' kepada pihak Universitas, Fakultas, dan Jurusan, maka dengan ini kami mohon kepada Bapak/Ibu dapat membebaskan mahasiswa tersebut dari kewajiban membayar SPP/UKT untuk Semester '.$attr[1]." TA ".$attr[2].".");
        $pdf->Ln(5);
        $pdf->Cell(45,$spasi,'Sebagai bahan pertimbangan bersama ini kami lampirkan :',0,1,'L');

        $pdf->Cell(5,$spasi,'1.',0,0,'L');
        $pdf->MultiCell(150,$spasi,'Surat permohonan pembebasan pembayaran UKT dari mahasiswa bersangkutan;',0,'L');
        $pdf->Cell(5,$spasi,'2.',0,0,'L');
        $pdf->MultiCell(150,$spasi,'Transkrip Akademik terakhir yang sudah memuat nilai ujian '.$status['ta'].';',0,'L');
        $pdf->Cell(5,$spasi,'3.',0,0,'L');
        $pdf->MultiCell(150,$spasi,'Foto kopi Bukti Pembayaran SPP/UKT  semester 1 s/d semester terakhir (legalisir);',0,'L');
        $pdf->Cell(5,$spasi,'4.',0,0,'L');
        $pdf->MultiCell(150,$spasi,'Foto kopi Berita Acara ujian '.$status['ta'].' dan dinyatakan lulus;',0,'L');
        $pdf->Cell(5,$spasi,'5.',0,0,'L');
        $pdf->Cell(5,$spasi,'Foto kopi KTM (legalisir);',0,1,'L');
        $pdf->Cell(5,$spasi,'6.',0,0,'L');
        $pdf->Cell(5,$spasi,'Fotokopi Bebas Perpustakaan Universitas;',0,1,'L');
        $pdf->Cell(5,$spasi,'7.',0,0,'L');
        $pdf->Cell(5,$spasi,'Fotokopi Bukti Penyerahan '.$status['ta'].'.',0,1,'L');
        $pdf->Cell(27,$spasi,'masing-masing');
        $pdf->SetFont('Times','BI',12);
        $pdf->Cell(14.5,$spasi, '2 (dua)');
        $pdf->SetFont('Times','',12);
        $pdf->Cell(7,$spasi, 'lembar.',0,1,'L');
        $pdf->Ln(5);
        $pdf->MultiCell(150,$spasi,'Atas perhatian dan bantuan Bapak, saya ucapkan terima kasih.',0,'J');
        $pdf->Ln(5);
        $pdf->SetX(100);
        $wd2 = $this->layanan_model->get_tugas_tambahan_user('Wakil Dekan Bidang Umum dan Keuangan');
        if(empty($wd2)){
            $wd2_name = "";
            $wd2_nip = "";
            $wd2_pangkat = "";
        }
        else{
            $wd2_name = $wd2->gelar_depan." ".$wd2->name.", ".$wd2->gelar_belakang;
            $wd2_nip = $wd2->nip_nik;
            if($wd2->pangkat_gol == NULL || $wd2->pangkat_gol == "" ){
                $wd2_pangkat = "";
            }
            else{
                $wd2_pkt = $this->user_model->get_pangkat_gol_by_id($wd2->pangkat_gol);
                $wd2_pangkat = $wd2_pkt->pangkat."/".$wd2_pkt->golongan." ".$wd2_pkt->ruang;
            }
            
        }
        $pdf->Cell(35, $spasi,'a.n. Dekan,',0,1);
        $pdf->SetX(100);
        $pdf->Cell(35, $spasi,'Wakil Dekan Bidang Umum dan Keuangan,',0,1);
        $pdf->Ln(20);
        $pdf->SetX(100);
        $pdf->Cell(105, $spasi, $wd2_name,0,1,'L');
        $pdf->SetX(100);
        $pdf->Cell(105, $spasi, "NIP. ".$wd2_nip);
        $pdf->Ln(5);
        $pdf->Cell(50,$spasi,'Tembusan',0,1,'L');
        $pdf->Cell(5,$spasi,'1. ',0,0,'L');
        $pdf->Cell(45,$spasi,'Dekan FMIPA Unila;',0,1,'L');
        $pdf->Cell(5,$spasi,'2. ',0,0,'L');
        $pdf->Cell(45,$spasi,'Kepala BUK Unila. ',0,1,'L');
        $pdf->Ln(2);

        //LEMBAR VERIFIKASI
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
        // SubHeader
        $pdf->SetWidths(array(8,90,35,35));
        $pdf->SubHeader(array('No', 'Jenis Persyaratan', 'Jumlah', 'Verifikasi'));

        // Isi
        $pdf->SetAligns(array('C','L'));
        $pdf->SetSpacing($spasi);
        $pdf->Row(array('1',"Surat permohonan pembebasan pembayaran UKT dari mahasiswa bersangkutan.\n",'2 Lembar',''));
        $pdf->Row(array('2',"Transkrip Akademik terakhir yang sudah memuat nilai ujian ".$status['ta'].".\n",'2 lembar',''));
        $pdf->Row(array('3',"Foto kopi Bukti Pembayaran UKT  semester 1 s/d semester terakhir (legalisir).\n",'2 lembar',''));
        $pdf->Row(array('4',"Foto kopi Berita Acara ujian ".$status['ta']." dan dinyatakan lulus.\n",'2 lembar',''));
        $pdf->Row(array('5',"Foto kopi KTM (legalisir).",'1 lembar',''));
        $pdf->Row(array('6',"Fotokopi Bebas Perpustakaan Universitas.\n",'2 lembar',''));
        $pdf->Row(array('7',"Fotokopi Bukti Penyerahan ".$status['ta'].".\n",'2 lembar',''));

        $pdf->Output('I','form_keterlambatan_spp.pdf');
    }

    function form_44($data,$meta)
    {
        $cek_atr = $this->layanan_model->select_layanan_atribut_by_id($data->id_layanan_fakultas);
        $n=0;
        if(empty($cek_atr)){
            for($m=0;$m<=99;$m++){
                $attr[$m] = "";
            }
        }
        else{
            foreach($meta as $metas)
            {
                $attr[$n] = $metas->meta_value;
                $n++;
            }
        }
       
        $count = count($meta);

        $numPage = '/PM/MIPA/II.B/12';
        $nomor ='    /UN26.17/KU/'.date("Y");

        $spasi = 6;
        $kode = 0;
        $type = '';
        $mhs = $this->user_model->get_mahasiswa_data_npm($data->npm);
        $jurusan = $this->ta_model->get_jurusan($data->npm);
        $status = $this->get_prodi_from_npm($data->npm);       
        
        $pdf = new FPDF('P','mm',array(210,330));
        $pdf->setting_page_footer($numPage, $kode, $type);
        $pdf->setting_no_header(array(1, 5));

        // LEMBAR VERIFIKASI
        $pdf->AddPage('P');
        $pdf->SetFillColor(205,205,205);
        $pdf->SetLeftMargin(10);
        $pdf->SetFont('Times','',12);
        $pdf->Ln(1);

        // CAP FAKULTAS
        $pdf->Ln(5);
        // Title
        $pdf->TableTitle('VERIFIKASI PERSYARATAN LAYANAN');
        // Header
        $pdf->TableHeader1(array('MIPA/'.$jurusan, $mhs->name));
        $pdf->TableHeader2(array('CAP FAKULTAS', $mhs->npm));
        // SubHeader
        $pdf->SetWidths(array(8,69,13,13,20,30,22,15));
        $pdf->SubHeader(array('No', 'Jenis Persyaratan', 'Sah', 'Benar', 'Lengkap', 'Tgl Penyelesaian', 'Verifikasi', 'Paraf'));

        // Isi
        $pdf->SetWidths(array(8,69,13,13,20,30,22,15));
        $pdf->SetAligns(array('C','L'));
        $pdf->SetSpacing(5);
        $pdf->Row(array('1',"Dokumen yang telah ditandatangani oleh pejabat yang berwenang\n ",'','','','','',''));
        // CAP FAKULTAS
        $pdf->Ln(5);
        // Title
        $pdf->TableTitle('VERIFIKASI PERSYARATAN LAYANAN');
        // Header
        $pdf->TableHeader1(array('MIPA/'.$jurusan, $mhs->name));
        $pdf->TableHeader2(array('CAP FAKULTAS', $mhs->npm));
        // SubHeader
        $pdf->SetWidths(array(8,69,13,13,20,30,22,15));
        $pdf->SubHeader(array('No', 'Jenis Persyaratan', 'Sah', 'Benar', 'Lengkap', 'Tgl Penyelesaian', 'Verifikasi', 'Paraf'));

        // Isi
        $pdf->SetWidths(array(8,69,13,13,20,30,22,15));
        $pdf->SetAligns(array('C','L'));
        $pdf->SetSpacing(5);
        $pdf->Row(array('1',"Dokumen yang telah ditandatangani oleh pejabat yang berwenang\n ",'','','','','',''));
        // CAP FAKULTAS
        $pdf->Ln(5);
        // Title
        $pdf->TableTitle('VERIFIKASI PERSYARATAN LAYANAN');
        // Header
        $pdf->TableHeader1(array('MIPA/'.$jurusan, $mhs->name));
        $pdf->TableHeader2(array('CAP FAKULTAS', $mhs->npm));
        // SubHeader
        $pdf->SetWidths(array(8,69,13,13,20,30,22,15));
        $pdf->SubHeader(array('No', 'Jenis Persyaratan', 'Sah', 'Benar', 'Lengkap', 'Tgl Penyelesaian', 'Verifikasi', 'Paraf'));

        // Isi
        $pdf->SetWidths(array(8,69,13,13,20,30,22,15));
        $pdf->SetAligns(array('C','L'));
        $pdf->SetSpacing(5);
        $pdf->Row(array('1',"Dokumen yang telah ditandatangani oleh pejabat yang berwenang\n ",'','','','','',''));
        // CAP FAKULTAS
        $pdf->Ln(5);
        // Title
        $pdf->TableTitle('VERIFIKASI PERSYARATAN LAYANAN');
        // Header
        $pdf->TableHeader1(array('MIPA/'.$jurusan,$mhs->name));
        $pdf->TableHeader2(array('CAP FAKULTAS', $mhs->npm));
        // SubHeader
        $pdf->SetWidths(array(8,69,13,13,20,30,22,15));
        $pdf->SubHeader(array('No', 'Jenis Persyaratan', 'Sah', 'Benar', 'Lengkap', 'Tgl Penyelesaian', 'Verifikasi', 'Paraf'));

        // Isi
        $pdf->SetWidths(array(8,69,13,13,20,30,22,15));
        $pdf->SetAligns(array('C','L'));
        $pdf->SetSpacing(5);
        $pdf->Row(array('1',"Dokumen yang telah ditandatangani oleh pejabat yang berwenang\n ",'','','','','',''));
        // CAP FAKULTAS
        $pdf->Ln(5);
        // Title
        $pdf->TableTitle('VERIFIKASI PERSYARATAN LAYANAN');
        // Header
        $pdf->TableHeader1(array('MIPA/'.$jurusan,$mhs->name));
        $pdf->TableHeader2(array('CAP FAKULTAS', $mhs->npm));
        // SubHeader
        $pdf->SetWidths(array(8,69,13,13,20,30,22,15));
        $pdf->SubHeader(array('No', 'Jenis Persyaratan', 'Sah', 'Benar', 'Lengkap', 'Tgl Penyelesaian', 'Verifikasi', 'Paraf'));

        // Isi
        $pdf->SetWidths(array(8,69,13,13,20,30,22,15));
        $pdf->SetAligns(array('C','L'));
        $pdf->SetSpacing(5);
        $pdf->Row(array('1',"Dokumen yang telah ditandatangani oleh pejabat yang berwenang\n ",'','','','','',''));

        $pdf->Output('I','form_verifikasi_persyaratan.pdf');
    }

    function form_45($data,$meta)
    {
        $cek_atr = $this->layanan_model->select_layanan_atribut_by_id($data->id_layanan_fakultas);
        $n=0;
        if(empty($cek_atr)){
            for($m=0;$m<=99;$m++){
                $attr[$m] = "";
            }
        }
        else{
            foreach($meta as $metas)
            {
                $attr[$n] = $metas->meta_value;
                $n++;
            }
        }
       
        $count = count($meta);

        $numPage = '';
        $nomor ='';

        $spasi = 6;
        $kode = 0;
        $type = '';
        $mhs = $this->user_model->get_mahasiswa_data_npm($data->npm);
        $pa = $this->user_model->get_dosen_pa_by_npm($data->npm);
        $kajur = $this->user_model->get_kajur_by_npm($data->npm);
        $jurusan = $this->ta_model->get_jurusan($data->npm);
        $status = $this->get_prodi_from_npm($data->npm);       
        
        $pdf = new FPDF('P','mm',array(210,330));
        $pdf->setting_page_footer($numPage, $kode, $type);
        $pdf->setting_no_header(array(1));
        $pdf->SetLeftMargin(30);
        $pdf->SetTopMargin(20);
        $pdf->AddPage();
        $pdf->SetFont('Times','',12);
        $pdf->Ln(5);
        $pdf->Cell(15, $spasi,'Kepada');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->MultiCell(135,$spasi,'Yth. '.$attr[0]." Fakultas MIPA Universitas Lampung" ,0,'L');

        $pdf->Ln(10);
        $pdf->MultiCell(150, $spasi,'Yang bertanda tangan di bawah ini saya:', 0, 'L');
        $pdf->Ln(5);
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
        $pdf->Cell(45, $spasi,'Tahun Akademik', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $attr[1], 0, 1, 'L');
        $pdf->Cell(45, $spasi,'UKM', 0, 0, 'L');
        $pdf->Cell(5, $spasi,':', 0, 0, 'C');
        $pdf->Cell(100, $spasi, $attr[2], 0, 1, 'L');
        $pdf->Ln(2);

        $pdf->MultiCell(160, $spasi,'Dengan hormat saya mengajukan permohonan wawancara dalam rangka '.$attr[3]." ".$attr[4]." pada :",0,'L');
        $pdf->Ln(2);
        $pdf->SetLeftMargin(35);
        $pdf->Cell(45,$spasi,'Hari',0,0,'L');
        $pdf->Cell(5,$spasi,':',0,0,'C');
        $pdf->Cell(100,$spasi, $attr[5],0,1,'L');
        $pdf->Cell(45,$spasi,'Materi',0,0,'L');
        $pdf->Cell(5,$spasi,':',0,0,'C');
        $pdf->SetLeftMargin(30);
        $pdf->Cell(100,$spasi, $attr[6],0,1,'L');
        $pdf->Ln(2);
        $pdf->Cell(45,$spasi,'Bersama surat ini saya lampirkan :',0,1,'L');
        $pdf->SetLeftMargin(35);
        $pdf->Cell(5,$spasi,'1.',0,0,'L');
        $pdf->Cell(5,$spasi,'Foto Copy Kartu Mahasiswa',0,1,'L');
        $pdf->Cell(5,$spasi,'2.',0,0,'L');
        $pdf->Cell(5,$spasi,'Draf/materi wawancara',0,1,'L');
        $pdf->SetLeftMargin(30);
        $pdf->Ln(2);

        $pdf->MultiCell(150,$spasi,'Dengan permohonan saya, atas perhatian Bapak/Ibu saya ucapkan terima kasih.',0,'J');
        $pdf->Ln(10);
        $pdf->SetX(120);
        $pdf->Cell(40, $spasi,'Bandar Lampung, '.$this->convert_date($data->created_at),0,1);
        $pdf->SetX(120);
        $pdf->Cell(35, $spasi,'Pemohon,',0,1);
        $pdf->SetX(120);
        $pdf->Cell(60, $spasi,$pdf->Image("$data->ttd",$pdf->GetX()-3, $pdf->GetY(),40,0,'PNG'), 0, 0, 'L');
        $pdf->Ln(20);
        $pdf->SetX(120);
        $pdf->Cell(60, $spasi, $mhs->name,0,1);
        $pdf->SetX(120);
        $pdf->Cell(60, $spasi, "NPM. ".$mhs->npm,0,1);

        //LEMBAR VERIFIKASI
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
        $pdf->SetSpacing($spasi);
        $pdf->Row(array('1',"Fotocopy KTM",'1 lbr.',''));
        $pdf->Row(array('2',"Draf/materi wawancara",'1 lbr.',''));

        $pdf->Output('I','form_wawancara.pdf');
    }


    //bebas lab
    function layanan_fakultas_bebas_lab()
    {
        $id = $this->input->get('id');
        // echo $id;
        $lab = $this->layanan_model->get_bebas_lab_by_id($id);
        $lab_meta = $this->layanan_model->get_bebas_lab_meta_by_id($id);

        // echo $lab_meta[2]->id_meta;
        $this->form_bebas_lab($lab,$lab_meta);
    }

    function form_bebas_lab($lab,$lab_meta)
    {
        $numPage = '/PM/MIPA/I/22';
        $spasi = 4.6;
        $spasi2 = 6;
        $kode = 0;
        $type = '';
        $mhs = $this->user_model->get_mahasiswa_data_npm($lab->npm);
        $pa = $this->user_model->get_dosen_pa_by_npm($lab->npm);
        $kajur = $this->user_model->get_kajur_by_npm($lab->npm);
        $jurusan = $this->ta_model->get_jurusan($lab->npm);
        $status = $this->get_prodi_from_npm($lab->npm);     
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
            //ttd
            if($lab_meta[$n]->ttd_kalab != null){
                $ttd[$n] = $lab_meta[$n]->ttd_kalab;
            }
            else{
                $ttd[$n] = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVQYV2NgYAAAAAMAAWgmWQ0AAAAASUVORK5CYII=';
            }
            if($lab_meta[$n+1]->ttd_kalab != null){
                $ttd[$n+1] = $lab_meta[$n+1]->ttd_kalab;
            }
            else{
                $ttd[$n+1] = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVQYV2NgYAAAAAMAAWgmWQ0AAAAASUVORK5CYII=';
            }
            

            $pdf->Row(array("Kepala ".str_replace("Laboratorium","Lab.",$labs[$n]->nama_lab)."\n".$pdf->Image($ttd[$n],$pdf->GetX()+20, $pdf->GetY(),40,0,'PNG')."\n\n".$kpl_nama[$n]."\nNIP. ".$kpl_nip[$n], "Kepala ".str_replace("Laboratorium","Lab.",$labs[$n+1]->nama_lab)."\n".$pdf->Image($ttd[$n+1],$pdf->GetX()+100, $pdf->GetY(),40,0,'PNG')."\n\n".$kpl_nama[$n+1]."\nNIP. ".$kpl_nip[$n+1]));
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

        if($lab->ttd_dekan == null || $lab->ttd_dekan == ''){
            $ttd_dekan = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVQYV2NgYAAAAAMAAWgmWQ0AAAAASUVORK5CYII=";
        }
        else{
            $ttd_dekan = $lab->ttd_dekan;
        }

        $pdf->Ln(3);
        $y_now = $pdf->GetY();
        $pdf->SetFont('Times','',12);
        $pdf->SetX(100);
        $pdf->MultiCell(80, $spasi, "Bandar Lampung, ".$this->convert_date($lab->updated_at)."\na.n. Dekan,\nWakil Dekan Bid. Akademik dan Kerjasama\n\n".$pdf->Image("$ttd_dekan",$pdf->GetX(), $pdf->GetY()+6,40,0,'PNG')."\n\n".$wd2_name."\nNIP. ".$wd2_nip, 0, 'L');

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
         $pdf->Cell(100, $spasi,'2', 0, 1, 'L');
         $pdf->Cell(45, $spasi,'Jenis Layanan', 0, 0, 'L');
         $pdf->Cell(5, $spasi,':', 0, 0, 'C');
         $pdf->Cell(100, $spasi,'Bebas Laboratorium', 0, 1, 'L');
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
         $pdf->Cell(100, $spasi, $this->convert_date($lab->created_at), 0, 1, 'L');
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