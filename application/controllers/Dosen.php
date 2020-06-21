<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dosen extends CI_Controller {
    
    public function __construct()
	{
		parent::__construct();
		$this->load->model('wilayah_model');
		$this->load->model('parameter_model');
		$this->load->model('jurusan_model');
		$this->load->model('user_model');
		$this->load->model('ta_model');
		$this->load->library('pdf');
		
		if($this->session->has_userdata('username')) {
		    if($this->session->userdata('state') <> 2) {
		        echo "<script>alert('Akses ditolak!!');javascript:history.back();</script>";
		    }
		} else {
		    redirect(site_url('?access=ditolak'));
		}
	}

	public function index()
	{
		redirect(site_url("dosen/kelola-akun"));
	}

	public function akun()
	{
		$data['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$this->load->view('header_global', $data);
		$this->load->view('dosen/header');
		
		$this->load->view('dosen/akun', $data);

        $this->load->view('footer_global');
	}
	
	public function ubah_akun()
	{
		echo "<pre>";
		//print_r($_POST);
		//print_r($_FILES);

		$data = array(
			'name' => $this->input->post('nama'),
			'mobile' => $this->input->post('hp'),
			'email' => $this->input->post('email')
		);

		$this->session->set_userdata(array('name' => $this->input->post('nama')));

		if($this->input->post('output_ttd') != "")
			$data['ttd'] = $this->input->post('output_ttd');
		if(!empty($this->input->post('password')))
			$data['password'] = $this->input->post('password');

		if(!empty($_FILES)) {
			$file = $_FILES['file']['tmp_name']; 
			$sourceProperties = getimagesize($file);
			$fileNewName = $this->input->post('username');
			$folderPath = "assets/uploads/pas-foto/";
			$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

			$this->resize_crop_image(200, 300, $file, $folderPath. $fileNewName. ".". $ext);

			$data['foto'] = $folderPath. $fileNewName. ".". $ext;
		}
		//print_r($data);
		//echo $this->session->userdata('userId');
		$this->user_model->update($data, $this->session->userdata('userId'));
		redirect(site_url("dosen/kelola-akun?status=sukses"));
	}

	public function biodata()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['biodata'] = $this->user_model->select_biodata_by_ID($this->session->userdata('userId'), 3)->row();

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');
		
		$this->load->view('dosen/biodata', $data);

        $this->load->view('footer_global');
	}

	public function ubah_biodata()
	{
		//echo "<pre>";
		//print_r($_POST);
		//echo $this->session->userdata('userId');
		$data_akademik = array(
			'prodi' => $this->input->post('prodi'),
			'dosen_pa' => $this->input->post('dosen_pa'),
			'jalur_masuk' => $this->input->post('jalur_masuk'),
			'asal_sekolah' => $this->input->post('asal_sekolah'),
			'nama_sekolah' => $this->input->post('nama_sekolah')
		);

		$this->user_model->update_dosen($data_akademik, $this->session->userdata('userId'));

		$tgl_lahir = new DateTime($this->input->post('tanggal_lahir'));

		$data_akun = array(
			'jenis_kelamin' => $this->input->post('jenkel'),
			'agama' => $this->input->post('agama'),
			'tempat_lahir' => $this->input->post('tempat_lahir'),
			'tanggal_lahir' => $tgl_lahir->format('Y-m-d'),
			'jalan' => $this->input->post('jalan'),
			'provinsi' => $this->input->post('provinsi'),
			'kota_kabupaten' => $this->input->post('kota_kabupaten'),
			'kecamatan' => $this->input->post('kecamatan'),
			'kelurahan_desa' => $this->input->post('kelurahan_desa'),
			'kode_pos' => $this->input->post('kode_pos')
		);
		$this->user_model->update($data_akun, $this->session->userdata('userId'));
		redirect(site_url("dosen/kelola-biodata?status=sukses"));
	}

	function ambil_data(){

		$modul=$this->input->post('modul');
		$id=$this->input->post('id');
		
		if($modul=="kabupaten"){
			echo $this->wilayah_model->kabupaten($id);
		}
		else if($modul=="kecamatan"){
			echo $this->wilayah_model->kecamatan($id);
		}
		else if($modul=="kelurahan"){	
			echo $this->wilayah_model->desa($id);
		}
	}

	function resize_crop_image($max_width, $max_height, $source_file, $dst_dir, $quality = 80){
		$imgsize = getimagesize($source_file);
		$width = $imgsize[0];
		$height = $imgsize[1];
		$mime = $imgsize['mime'];
	 
		switch($mime){
			case 'image/gif':
				$image_create = "imagecreatefromgif";
				$image = "imagegif";
				break;
	 
			case 'image/png':
				$image_create = "imagecreatefrompng";
				$image = "imagepng";
				$quality = 7;
				break;
	 
			case 'image/jpeg':
				$image_create = "imagecreatefromjpeg";
				$image = "imagejpeg";
				$quality = 80;
				break;
	 
			default:
				return false;
				break;
		}
		 
		$dst_img = imagecreatetruecolor($max_width, $max_height);
		$src_img = $image_create($source_file);
		 
		$width_new = $height * $max_width / $max_height;
		$height_new = $width * $max_height / $max_width;
		//if the new width is greater than the actual width of the image, then the height is too large and the rest cut off, or vice versa
		if($width_new > $width){
			//cut point by height
			$h_point = (($height - $height_new) / 2);
			//copy image
			imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
		}else{
			//cut point by width
			$w_point = (($width - $width_new) / 2);
			imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
		}
		 
		$image($dst_img, $dst_dir, $quality);
	 
		if($dst_img)imagedestroy($dst_img);
		if($src_img)imagedestroy($src_img);
	}


	// Manajemen Tugas Akhir
	//raihan
	function tugas_akhir()
	{
		$data['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$this->load->view('header_global', $data);
		$this->load->view('dosen/header');

		$data['ta'] = $this->ta_model->get_approval_ta($this->session->userdata('userId'));
		$data['pa'] = $this->ta_model->get_approval_ta_by_pa($this->session->userdata('userId'));
		$data['approve'] = $this->ta_model->get_approval_ta_list($this->session->userdata('userId'));
		
		$this->load->view('dosen/tema_ta', $data);
		
		//$this->load->view('dosen/tugas_akhir', $data);

        $this->load->view('footer_global');
	}

	function tugas_akhir_struktural()
	{
		$data['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$this->load->view('header_global', $data);
		$this->load->view('dosen/header');

		$data['ta'] = $this->ta_model->get_approval_ta_kajur($this->session->userdata('userId'));
		
		$this->load->view('dosen/kajur/tema_ta', $data);
		
		//$this->load->view('dosen/tugas_akhir', $data);

        $this->load->view('footer_global');
	}

	function seminar_struktural()
	{
		$data['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$this->load->view('header_global', $data);
		$this->load->view('dosen/header');

		$data['seminar'] = $this->ta_model->get_approval_seminar_kajur($this->session->userdata('userId'));
		
		$this->load->view('dosen/kajur/seminar_ta',$data);
		
		//$this->load->view('dosen/tugas_akhir', $data);

        $this->load->view('footer_global');
	}

	function form_tugas_akhir()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['biodata'] = $this->user_model->select_biodata_by_ID($this->session->userdata('userId'), 3)->row();
		
		
		if($this->input->get('aksi') == "edit")
		{
			
			
		}
		else
		{
			$data_ta = array(
				'judul1' => null,
				'judul2' => null,
				'ipk' => null,
				'sks' => null,
				'toefl' => null,
				'pembimbing1' => null,
				'bidang_ilmu' => null
			);
		}

		$data['data_ta'] = $data_ta;

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/form_tema_ta', $data);
		
		//$this->load->view('dosen/tugas_akhir', $data);

        $this->load->view('footer_global');
	}
	

	
	public function print() {
	    //echo "<pre>";
	    //print_r($_POST);
	    //echo sizeof($_POST['cekNrp']);
	    
	    
	    
	    $pdf = new PDF('L', 'mm', array(210, 317));
	    $pdf->AddPage();
	    $pdf->SetFillColor(255,255,255);
	    $pdf->SetLeftMargin(15);
	    $pdf->SetFont('Arial', '', 11);

        $wt = array(12, 50, 30, 60, 40, 30, 35, 30);
        
        $pdf->SetWidths($wt);
        $pdf->SetSpacing(5);
        $pdf->SetAligns(array('C'));
        //$pdf->SetY(46);
        $no = 0;
        foreach($_POST['cekNrp'] as $row) {
            $no++;
	        $personil = $this->personil_model->select_by_nrp($row)->row();
	        $pelanggaran = $this->nilai_model->select_by_nrp($row)->result();
	        
	        //print_r($personil);
	        //print_r($pelanggaran);
	        //echo "<br>";
	        if(!empty($pelanggaran)) {
	            $idx = 1;
	            foreach($pelanggaran as $res) {
	                if($idx == 1) {
	                    $number = $no.".";
	                    $bio = $personil->nama."\n".$personil->pangkat." / ".$personil->nrp."\n\n".$personil->jabatan;
	                } else {
	                    $number = "";
	                    $bio = "";
	                }
	                
	                $pdf->Row(array(
    	                $number, 
    	                $bio,
    	                $res->tempat.", tanggal ".$res->waktu,
                        $res->jenis_pelanggaran,
                        $res->jenis_hukuman,
                        "Putusan sidang KKEP nomor:\n(".$res->no_putusan.")",
                        $res->batas_waktu,
                        $res->keterangan
    	           ));
    	           $idx++;
	            }
	            
	        } else {
	            $pdf->Row(array(
	                $no.".", 
	                $personil->nama."\n".$personil->pangkat." / ".$personil->nrp."\n\n".$personil->jabatan,
	                'NIHIL',
                    'NIHIL',
                    'NIHIL',
                    'NIHIL',
                    'NIHIL',
                    'NIHIL'
	           ));
	        }
	    }
        
        
	    $pdf->Output();
	    
	}
	//raihan
	function tugas_akhir_approve()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);

		$id = $data['id_pengajuan'];
		$ttd = $data['ttd'];
		$aksi = $data['aksi'];
		$status = $data['jenis'];
		$dosenid = $this->session->userdata('userId');

		$this->ta_model->approve_ta($id,$ttd,$status,$dosenid);

		redirect(site_url("dosen/tugas-akhir/tema"));
		
	}

	function tugas_akhir_approve_struktural()
	{
		$data = $this->input->post();

		// echo "<pre>";
		// print_r($data);

		$id = $data['id_pengajuan'];
		$ttd = $data['ttd'];
		

		$pb1 = $data['Pembimbing_1'];
		$pb2 = $data['Pembimbing_2'];
		$pb3 = $data['Pembimbing_3'];
		$ps1 = $data['Penguji_1'];
		$ps2 = $data['Penguji_2'];
		$ps3 = $data['Penguji_3'];
		$fill = 0;
		$null = 0;

		//check null nor not
		if($pb1 != NULL){$fill++;}
		if($pb2 != NULL){$fill++;}
		if($pb3 != NULL){$fill++;}
		if($ps1 != NULL){$fill++;}
		if($ps2 != NULL){$fill++;}
		if($ps3 != NULL){$fill++;}

		if($pb1 == '0'){$null++;}
		if($pb2 == '0'){$null++;}
		if($pb3 == '0'){$null++;}
		if($ps1 == '0'){$null++;}
		if($ps2 == '0'){$null++;}
		if($ps3 == '0'){$null++;}

		$check = $fill - $null;

		$dosenid = $this->session->userdata('userId');

		if($check == '1'){
			$status = "kajur_acc";
			$this->ta_model->approve_ta($id,$ttd,$status,$dosenid);
		}
		else{
			$status = "kajur";
			$this->ta_model->approve_ta($id,$ttd,$status,$dosenid);
		}	


		redirect(site_url("dosen/struktural/tema"));
	}

	function tugas_akhir_decline()
	{
		$id = $this->input->post('id_ta');
		$status = $this->input->post('status');
		$keterangan = $this->input->post('keterangan');
		$dosenid = $this->session->userdata('userId');
		$ket = $status."###".$keterangan;
		// echo "<pre>";
		// print_r($id);
		
		$data = array("id_pengajuan" => $id);
		$where = $data['id_pengajuan'];

		$this->ta_model->decline_ta($id,$dosenid,$status,$ket);
		redirect(site_url("dosen/tugas-akhir/tema"));
	}

	function tugas_akhir_koordinator_decline()
	{
		$id = $this->input->post('id_ta');
		$keterangan = $this->input->post('keterangan');
		$dosenid = $this->session->userdata('userId');
		$status = "koor";

		// echo "<pre>";
		// print_r($id);
		
		// $data = array("id_pengajuan" => $id);
		// $where = $data['id_pengajuan'];

		$this->ta_model->decline_ta($id,$dosenid,$status,$keterangan);
		redirect(site_url("dosen/tugas-akhir/tema/koordinator"));
	}

	function tugas_akhir_aksi()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();

		$id = $this->input->get('id');
		$aksi = $this->input->get('aksi');
		$jenis = $this->input->get('jenis');
		// echo "<pre>";
		// print_r($id);
		$data['ta'] = $this->ta_model->get_ta_by_id($id);
		$data['aksi'] = $aksi;
		$data['jenis'] = $jenis;

		// print_r($data);
		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/approve_tema_ta',$data);
		
		$this->load->view('footer_global');
		
	}

	function tugas_akhir_koordinator()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['ta'] = $this->ta_model->get_approval_ta_koordinator($this->session->userdata('userId'));

		// print_r($data);

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/koordinator/tema_ta_koordinator',$data);
		
		$this->load->view('footer_global');
	}

	function form_tugas_akhir_koordinator()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['ta'] = $this->ta_model->get_approval_ta_koordinator($this->session->userdata('userId'));

		$id = $this->input->get('id');
		$aksi = $this->input->get('aksi');

		$data['ta'] = $this->ta_model->get_ta_by_id($id);
		$data['aksi'] = $aksi;

		// print_r($data);
		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/koordinator/approve_tema_ta',$data);
		
		$this->load->view('footer_global');
	}

	function form_tugas_akhir_struktural()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();

		$id = $this->input->get('id');
		$aksi = $this->input->get('aksi');

		$data['ta'] = $this->ta_model->get_ta_by_id($id);

		// print_r($data);
		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/kajur/approve_tema_ta',$data);
		
		$this->load->view('footer_global');
	}

	function form_seminar_struktural()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();

		$id = $this->input->get('id');
		$seminar = $this->ta_model->get_seminar_by_id($id);
		$data['status'] = "kajur";
		$data['seminar'] = $seminar[0];

		// print_r($data);
		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/kajur/approve_seminar',$data);
		
		$this->load->view('footer_global');
	}

	function add_tugas_akhir()
	{
		//echo "<pre>";
		//print_r($this->input->post());
		//echo $this->session->userdata('username');

		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);
		$no = $data['no_penetapan'];
		$nomor = $data['nomor'];

		$dosenid = $this->session->userdata('userId');
		$no_penetapan = $no.$nomor;

		$id = $data['id_pengajuan'];
		$ttd = $data['ttd'];
		$judul_approve = $data['judul'];
		$judul1 = $data['judul1'];
		$judul2 = $data['judul2'];

		$pb1 = $data['pembimbing1'];
		$pb2 = $data['pembimbing2'];
		$pb3 = $data['pembimbing3'];
		$ps1 = $data['pembahas1'];
		$ps2 = $data['pembahas2'];
		$ps3 = $data['pembahas3'];

		// pb ps alt
		$pb2_nip = $data['pb2_alter_nip'];
		$pb2_nama = $data['pb2_alter_nama'];
		$pb3_nip = $data['pb3_alter_nip'];
		$pb3_nama = $data['pb3_alter_nama'];
		$ps1_nip = $data['ps1_alter_nip'];
		$ps1_nama = $data['ps1_alter_nama'];
		$ps2_nip = $data['ps2_alter_nip'];
		$ps2_nama = $data['ps2_alter_nama'];
		$ps3_nip = $data['ps3_alter_nip'];
		$ps3_nama = $data['ps3_alter_nama'];

		if($pb2 == NULL && ($pb2_nip != NULL && $pb2_nama != NULL)){
			$status = "Pembimbing 2";
			$this->ta_model->set_komisi_alter($id,$pb2_nip,$pb2_nama,$status);
		}
		if($pb3 == NULL && ($pb3_nip != NULL && $pb3_nama != NULL)){
			$status = "Pembimbing 3";
			$this->ta_model->set_komisi_alter($id,$pb3_nip,$pb3_nama,$status);
		}
		if($ps1 == NULL && ($ps1_nip != NULL && $ps1_nama != NULL)){
			$status = "Penguji 1";
			$this->ta_model->set_komisi_alter($id,$ps1_nip,$ps1_nama,$status);
		}
		if($ps2 == NULL && ($ps2_nip != NULL && $ps2_nama != NULL)){
			$status = "Penguji 2";
			$this->ta_model->set_komisi_alter($id,$ps2_nip,$ps2_nama,$status);
		}
		if($ps3 == NULL && ($ps3_nip != NULL && $ps3_nama != NULL)){
			$status = "Penguji 3";
			$this->ta_model->set_komisi_alter($id,$ps3_nip,$ps3_nama,$status);
		}

		$this->ta_model->approval_koordinator($id,$ttd,$dosenid,$no_penetapan,$judul_approve,$judul1,$judul2);
		$this->ta_model->set_komisi($id,$pb1,$pb2,$pb3,$ps1,$ps2,$ps3);

		if($pb2 != NULL && $pb2 != '0'){

			$data_approval = array(
				'id_pengajuan' => $id,
				'status_slug' => 'Pembimbing 2',
				'id_user' => $pb2,
				'ttd' => '',	
			);

			$this->ta_model->insert_approval_ta($data_approval);
		}

		if($pb3 != NULL && $pb3 != '0'){

			$data_approval = array(
				'id_pengajuan' => $id,
				'status_slug' => 'Pembimbing 3',
				'id_user' => $pb3,
				'ttd' => '',	
			);

			$this->ta_model->insert_approval_ta($data_approval);
		}

		if($ps1 != NULL && $ps1 != '0'){

			$data_approval = array(
				'id_pengajuan' => $id,
				'status_slug' => 'Penguji 1',
				'id_user' => $ps1,
				'ttd' => '',	
			);

			$this->ta_model->insert_approval_ta($data_approval);
		}

		if($ps2 != NULL && $ps2 != '0'){

			$data_approval = array(
				'id_pengajuan' => $id,
				'status_slug' => 'Penguji 2',
				'id_user' => $ps2,
				'ttd' => '',	
			);

			$this->ta_model->insert_approval_ta($data_approval);
		}

		if($ps3 != NULL && $ps3 != '0'){

			$data_approval = array(
				'id_pengajuan' => $id,
				'status_slug' => 'Penguji 3',
				'id_user' => $ps3,
				'ttd' => '',	
			);

			$this->ta_model->insert_approval_ta($data_approval);
		}

		redirect(site_url("dosen/tugas-akhir/tema/koordinator"));
		
	}

	//Manajemen Seminar
	function seminar()
	{
		$data['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$this->load->view('header_global', $data);
		$this->load->view('dosen/header');

		// $data['ta'] = $this->ta_model->get_approval_ta($this->session->userdata('userId'));
		$data['pa'] = $this->ta_model->get_approval_seminar_by_pa($this->session->userdata('userId'));
		$data['approve'] = $this->ta_model->get_approval_seminar_list($this->session->userdata('userId'));
		
		$this->load->view('dosen/seminar/seminar_ta',$data);
		
		//$this->load->view('dosen/tugas_akhir', $data);

        $this->load->view('footer_global');
	}

	function seminar_approve()
	{
		$data = $this->input->post();

		$id = $data['id'];
		$ttd = $data['ttd'];
		$status = $data['status'];
		$dosenid = $this->session->userdata('userId');
		// echo "<pre>";
		// print_r($data);

		$this->ta_model->approve_seminar($id,$ttd,$status,$dosenid);
		if($status == 'Koordinator'){
			redirect(site_url("dosen/tugas-akhir/seminar/koordinator"));
		}
		elseif($status == 'kajur'){
			redirect(site_url("dosen/struktural/seminar"));
		}
		else{
		redirect(site_url("dosen/tugas-akhir/seminar"));}
	}

	function seminar_decline()
	{
		$id = $this->input->post('id_seminar');
		$status = $this->input->post('status');
		$keterangan = $this->input->post('keterangan');
		$dosenid = $this->session->userdata('userId');
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);
		
		// $data = array("id" => $id);
		// $where = $data['id'];

		$this->ta_model->decline_seminar($id,$dosenid,$status,$keterangan);

		if($status == 'koor'){
			redirect(site_url("dosen/tugas-akhir/seminar/koordinator"));
		}
		elseif($status == 'admin'){
			redirect(site_url("tendik/verifikasi-berkas/seminar"));
		}
		else{
			redirect(site_url("dosen/tugas-akhir/seminar"));
		}

	}

	function seminar_aksi()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();

		$id = $this->input->get('id');
		$status = $this->input->get('status');
		// echo "<pre>";
		// print_r($id);
		$seminar = $this->ta_model->get_seminar_by_id($id);
		
		$data['status'] = $status;
		$data['seminar'] = $seminar[0];

		// print_r($data);
		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/seminar/approve_seminar',$data);
		
		$this->load->view('footer_global');
		
	}

	function seminar_aksi_koor()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();

		$id = $this->input->get('id');
		$status = $this->input->get('status');
		// echo "<pre>";
		// print_r($id);
		$seminar = $this->ta_model->get_seminar_by_id($id);
		
		$data['status'] = $status;
		$data['seminar'] = $seminar[0];

		// print_r($data);
		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/koordinator/approve_seminar',$data);
		
		$this->load->view('footer_global');
		
	}

	function seminar_koordinator()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['seminar'] = $this->ta_model->get_approval_seminar_koordinator($this->session->userdata('userId'));

		// print_r($data);

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/koordinator/seminar_koordinator',$data);
		
		$this->load->view('footer_global');
	}


	// rekap koordinator
	function rekap_ta()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['ta'] = $this->ta_model->get_ta_rekap($this->session->userdata('userId'));
		// print_r($data);
		// $jml = count($data);

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/koordinator/rekap/rekap_ta',$data);
		
		$this->load->view('footer_global');
	}
	
}
