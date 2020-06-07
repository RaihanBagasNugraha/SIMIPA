<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mahasiswa extends CI_Controller {
    
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
		    if($this->session->userdata('state') <> 3) {
		        echo "<script>alert('Akses ditolak!!');javascript:history.back();</script>";
		    }
		} else {
		    redirect(site_url('?access=ditolak'));
		}
	}

	public function index()
	{
		redirect(site_url("mahasiswa/kelola-akun"));
	}

	public function akun()
	{
		$data['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$this->load->view('header_global', $data);
		$this->load->view('mahasiswa/header');
		
		$this->load->view('mahasiswa/akun', $data);

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
		redirect(site_url("mahasiswa/kelola-akun?status=sukses"));
	}

	public function biodata()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['biodata'] = $this->user_model->select_biodata_by_ID($this->session->userdata('userId'), 3)->row();

		$this->load->view('header_global', $header);
		$this->load->view('mahasiswa/header');
		
		$this->load->view('mahasiswa/biodata', $data);

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

		$this->user_model->update_mahasiswa($data_akademik, $this->session->userdata('userId'));

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
		redirect(site_url("mahasiswa/kelola-biodata?status=sukses"));
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
	function tugas_akhir()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['ta'] = $this->ta_model->selet_ta_by_npm($this->session->userdata('username'));
		$data['status_ta'] = $this->ta_model->select_active_ta($this->session->userdata('username'));
		$this->load->view('header_global', $header);
		$this->load->view('mahasiswa/header');

		$this->load->view('mahasiswa/tema_ta', $data);
		
		//$this->load->view('mahasiswa/tugas_akhir', $data);

        $this->load->view('footer_global');
	}

	function form_tugas_akhir()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['biodata'] = $this->user_model->select_biodata_by_ID($this->session->userdata('userId'), 3)->row();
		$data['status_ta'] = $this->ta_model->select_active_ta($this->session->userdata('username'));
		
		if($this->input->get('aksi') == "ubah")
		{
			$ta = $this->ta_model->select_ta_by_id($this->input->get('id'), $this->session->userdata('username'));
			$data_ta = $ta[0];
		}
		else
		{
			$data_ta = array(
				'id_pengajuan' => null,
				'judul1' => null,
				'judul2' => null,
				'ipk' => null,
				'sks' => null,
				'toefl' => null,
				'pembimbing1' => null,
				'bidang_ilmu' => null,
				'ttd' => null
			);
		}

		$data['data_ta'] = $data_ta;

		$this->load->view('header_global', $header);
		$this->load->view('mahasiswa/header');

		$this->load->view('mahasiswa/form_tema_ta', $data);
		
		//$this->load->view('mahasiswa/tugas_akhir', $data);

        $this->load->view('footer_global');
	}

	function form_tugas_akhir_lampiran()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['lampiran'] = $this->ta_model->select_lampiran_by_ta($this->input->get('id'), $this->session->userdata('username'));
		$data['status_ta'] = $this->ta_model->select_active_ta($this->session->userdata('username'));
		

		$this->load->view('header_global', $header);
		$this->load->view('mahasiswa/header');

		$this->load->view('mahasiswa/form_lampiran_ta', $data);

        $this->load->view('footer_global');
	}

	public function tambah_berkas_ta()
	{
		echo "<pre>";
		print_r($_POST);
		print_r($_FILES);

		$data = array(
			'id_pengajuan' => $this->input->post('id_pengajuan'),
			'nama_berkas' => $this->input->post('nama_berkas'),
			'jenis_berkas' => $this->input->post('jenis_berkas')
		);

		if(!empty($_FILES)) {
			$file = $_FILES['file']['tmp_name']; 
			$sourceProperties = getimagesize($file);
			$fileNewName = $this->session->userdata('username').$this->input->post('jenis_berkas').$this->input->post('id_pengajuan');
			$folderPath = "assets/uploads/berkas-ta/";
			$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

			$data['file'] = $folderPath. $fileNewName. ".". $ext;
			move_uploaded_file($file, $folderPath. $fileNewName. ".". $ext);
		}
		
		$this->ta_model->insert_lampiran($data);
		redirect(site_url("mahasiswa/tugas-akhir/tema/lampiran?id=".$this->input->post('id_pengajuan')."&status=sukses"));
		
	}

	function add_tugas_akhir()
	{
		//echo "<pre>";
		//print_r($this->input->post());
		//echo $this->session->userdata('username');

		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);
		$ttd = $data['ttd'];
		unset($data['pen_color']);
		$aksi = $data['aksi'];
		unset($data['aksi']);

		$data['npm'] = $this->session->userdata('username');
		if($aksi == "ubah") {
			$where = $data['id_pengajuan'];
			unset($data['id_pengajuan']);
			$this->ta_model->update($data, $where);
		} else {
			$data2 = array(
				'status_slug' => 'Mahasiswa',
				'id_user' => $this->session->userdata('userId'),
				'ttd' => $ttd,
			);
			$this->ta_model->insert($data, $data2);
		}
		

		
		redirect(site_url("mahasiswa/tugas-akhir/tema"));
		
	}

	//raihan
	function hapus_berkas_ta()
	{
		$id = $this->input->post('id_berkas');
		$id_pengajuan = $this->input->post('id_pengajuan');
		$file = $this->input->post('file_berkas');
		
		$data = array("id" => $id);
		$this->ta_model->delete_lampiran($data);
		unlink($file);
	    
	    redirect(site_url("mahasiswa/tugas-akhir/tema/lampiran?id=".$id_pengajuan));
	}

	function hapus_data_ta()
	{
		$id = $this->input->post('id_ta');
		
		$data = array("id_pengajuan" => $id);
		$this->ta_model->delete_ta($data);
		$this->ta_model->delete_approval_ta($data);
		$this->ta_model->delete_berkas_ta($id);
		$this->ta_model->delete_lampiran($data);
		
	    
	    redirect(site_url("mahasiswa/tugas-akhir/tema"));
	}
	
	function ajukan_data_ta()
	{
		$id = $this->input->post('id_ta');
		
		$data = array("id_pengajuan" => $id);
		$where = $data['id_pengajuan'];

		$this->ta_model->ajukan_ta($id);
		redirect(site_url("mahasiswa/tugas-akhir/tema"));
	}

	function ajukan_data_ta_perbaikan()
	{
		$id = $this->input->post('id_perbaikan');
		
		$data = array("id_pengajuan" => $id);
		$where = $data['id_pengajuan'];

		$this->ta_model->ajukan_ta_perbaikan($id);
		redirect(site_url("mahasiswa/tugas-akhir/tema"));
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

	// Manajemen Seminar

	function seminar()
	{
		$ket = $this->ta_model->selet_ta_by_npm($this->session->userdata('username'));
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		// echo "<pre>";
		// print_r($data);
		// echo $data['0']['status'];
		if(empty($ket)){
			echo "<script type='text/javascript'>alert('Silahkan Ajukan Tema Terlebih Dahulu');window.location = ('tema') </script>";
		}
		else{ 
			if ($ket[0]->status == 4){
				$data['seminar'] = $this->ta_model->select_seminar_by_npm($this->session->userdata('username'));
				$this->load->view('header_global', $header);
				$this->load->view('mahasiswa/header');

				$this->load->view('mahasiswa/seminar/seminar_ta',$data);
				$this->load->view('footer_global');
				
			}
			else{
				echo "<script type='text/javascript'>alert('Pengajuan Tema Belum Disetujui');window.location = ('tema') </script>";
			}
			
		}		
		
	}

	function form_seminar()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['biodata'] = $this->user_model->select_biodata_by_ID($this->session->userdata('userId'), 3)->row();
		$data_ta = $this->ta_model->get_approved_ta($this->session->userdata('username'));
		$data['ta'] = $data_ta[0];
		// $data['status_ta'] = $this->ta_model->select_active_ta($this->session->userdata('username'));

		if($this->input->get('aksi') == "ubah")
		{
			$ta = $this->ta_model->select_seminar_by_id($this->input->get('id'));
			$data_seminar = $ta[0];
		}
		else
		{
			$data_seminar = array(
				'id' => null,
				'id_tugas_akhir' => null,
				'jenis' => null,
				'tgl_pelaksanaan' => null,
				'waktu_pelaksanaan' => null,
				'tempat' => null,
				'ipk' => null,
				'sks' => null,
				'toefl' => null,
			);
		}

		$data['data_seminar'] = $data_seminar;

		$this->load->view('header_global', $header);
		$this->load->view('mahasiswa/header');

		$this->load->view('mahasiswa/seminar/form_seminar_ta',$data);
	
		$this->load->view('footer_global');
	}

	function lampiran_seminar()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['lampiran'] = $this->ta_model->select_lampiran_by_seminar($this->input->get('id'));
		// $data['status_ta'] = $this->ta_model->select_active_ta($this->session->userdata('username'));
		
		// print_r($data);
		$this->load->view('header_global', $header);
		$this->load->view('mahasiswa/header');

		$this->load->view('mahasiswa/seminar/form_lampiran_seminar',$data);

        $this->load->view('footer_global');
	} 

	function tambah_berkas_seminar()
	{
		// $data = $this->input->post();
		// echo "<pre>";
		// print_r($_POST);
		// print_r($_FILES);

		$data = array(
			'id_seminar' => $this->input->post('id_seminar'),
			'jenis_berkas' => $this->input->post('jenis_berkas')
		);

		if(!empty($_FILES)) {
			$file = $_FILES['file']['tmp_name']; 
			$sourceProperties = getimagesize($file);
			$fileNewName = $this->session->userdata('username').$this->input->post('jenis_berkas').$this->input->post('id_seminar');
			$folderPath = "assets/uploads/berkas-seminar/";
			$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

			$data['file'] = $folderPath. $fileNewName. ".". $ext;
			move_uploaded_file($file, $folderPath. $fileNewName. ".". $ext);
		}

		$this->ta_model->insert_lampiran_seminar($data);
		redirect(site_url("mahasiswa/tugas-akhir/seminar/lampiran?id=".$this->input->post('id_seminar')."&status=sukses"));
	}

	function hapus_berkas_seminar()
	{
		// $data = $this->input->post();
		// echo "<pre>";
		// print_r($data);	
		$id = $this->input->post('id_berkas');
		$id_seminar = $this->input->post('id_seminar');
		$file = $this->input->post('file_berkas');
		
		$data = array("id" => $id);
		$this->ta_model->delete_lampiran_seminar($data);
		unlink($file);
	    
	    redirect(site_url("mahasiswa/tugas-akhir/seminar/lampiran?id=".$id_seminar));
	}

	function add_seminar()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);

		$ttd = $data['ttd'];
		$aksi = $data['aksi'];

		$pb1 = $data['Pembimbing_1'];
		$pb2 = $data['Pembimbing_2'];
		$pb3 = $data['Pembimbing_3'];
		$ps1 = $data['Penguji_1'];
		$ps2 = $data['Penguji_2'];
		$ps3 = $data['Penguji_3'];

		
		if($aksi == "ubah") {
			$data_seminar = array(
				'id_tugas_akhir' => $data['id_tugas_akhir'],
				'jenis' => $data['jenis'],
				'tgl_pelaksanaan' => $data['tanggal'],
				'waktu_pelaksanaan' => $data['waktu'],
				'tempat' => $data['tempat'],
				'ipk' => $data['ipk'],
				'sks' => $data['sks'],
				'toefl' => $data['toefl'],				
			);

			$data_approval = array(
				'ttd' => $ttd,	
			);
			// echo "<pre>";
			// print_r($data);
			$where = $data['id_seminar'];
			$this->ta_model->update_seminar($data_seminar,$data_approval, $where);
		} else {
			$data_seminar = array(
				'id_tugas_akhir' => $data['id_tugas_akhir'],
				'jenis' => $data['jenis'],
				'tgl_pelaksanaan' => $data['tanggal'],
				'waktu_pelaksanaan' => $data['waktu'],
				'tempat' => $data['tempat'],
				'ipk' => $data['ipk'],
				'sks' => $data['sks'],
				'toefl' => $data['toefl'],
				'status' => '-1',
				'keterangan' => NULL
				
			);

			$data_approval = array(
				'status_slug' => 'Mahasiswa',
				'id_user' => $this->session->userdata('userId'),
				'ttd' => $ttd,	
			);
			$insert_id = $this->ta_model->insert_seminar($data_seminar, $data_approval);
			// echo $insert_id;
			
			if($pb1 != NULL){
				$result = $this->db->query('SELECT id_user FROM `tbl_users_dosen` WHERE nip_nik = '.$pb1)->row()->id_user;

				$data_approval = array(
					'id_pengajuan' => $insert_id,
					'status_slug' => 'Pembimbing Utama',
					'id_user' => $result,
					'ttd' => '',	
				);

				$this->ta_model->insert_approval_seminar($data_approval);
			}

			if($pb2 != NULL){
				$result = $this->db->query('SELECT id_user FROM `tbl_users_dosen` WHERE nip_nik = '.$pb2)->row()->id_user;

				$data_approval = array(
					'id_pengajuan' => $insert_id,
					'status_slug' => 'Pembimbing 2',
					'id_user' => $result,
					'ttd' => '',	
				);

				$this->ta_model->insert_approval_seminar($data_approval);
			}

			if($pb3 != NULL){
				$result = $this->db->query('SELECT id_user FROM `tbl_users_dosen` WHERE nip_nik = '.$pb3)->row()->id_user;

				$data_approval = array(
					'id_pengajuan' => $insert_id,
					'status_slug' => 'Pembimbing 3',
					'id_user' => $result,
					'ttd' => '',	
				);

				$this->ta_model->insert_approval_seminar($data_approval);
			}

			if($ps1 != NULL){
				$result = $this->db->query('SELECT id_user FROM `tbl_users_dosen` WHERE nip_nik = '.$ps1)->row()->id_user;

				$data_approval = array(
					'id_pengajuan' => $insert_id,
					'status_slug' => 'Penguji 1',
					'id_user' => $result,
					'ttd' => '',	
				);

				$this->ta_model->insert_approval_seminar($data_approval);
			}

			if($ps2 != NULL){
				$result = $this->db->query('SELECT id_user FROM `tbl_users_dosen` WHERE nip_nik = '.$ps2)->row()->id_user;

				$data_approval = array(
					'id_pengajuan' => $insert_id,
					'status_slug' => 'Penguji 2',
					'id_user' => $result,
					'ttd' => '',	
				);

				$this->ta_model->insert_approval_seminar($data_approval);
			}

			if($ps3 != NULL){
				$result = $this->db->query('SELECT id_user FROM `tbl_users_dosen` WHERE nip_nik = '.$ps3)->row()->id_user;

				$data_approval = array(
					'id_pengajuan' => $insert_id,
					'status_slug' => 'Penguji 3',
					'id_user' => $result,
					'ttd' => '',	
				);

				$this->ta_model->insert_approval_seminar($data_approval);
			}

			
		}
		redirect(site_url("mahasiswa/tugas-akhir/seminar"));
		
	}
	
	function hapus_data_seminar()
	{
		$id = $this->input->post('id_seminar');
		// echo "<pre>";
		// print_r($data);

		$data = array("id" => $id);
		$data2 = array("id_pengajuan" => $id);
		$data3 = array("id_seminar" => $id);
		$this->ta_model->delete_seminar($data);
		$this->ta_model->delete_approval_seminar($data2);
		$this->ta_model->delete_berkas_seminar($id);
		$this->ta_model->delete_lampiran_seminar($data3);
		
	    
	    redirect(site_url("mahasiswa/tugas-akhir/seminar"));
	}

	function ajukan_data_seminar()
	{
		$id = $this->input->post('id_seminar');

		$data = array("id" => $id);
		$where = $data['id'];

		$this->ta_model->ajukan_seminar($id);
		redirect(site_url("mahasiswa/tugas-akhir/seminar"));	
	}

}
