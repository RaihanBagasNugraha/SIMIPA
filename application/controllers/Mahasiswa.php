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
		$this->load->model('pkl_model');
		$this->load->model('layanan_model');
		$this->load->library('pdf');
		$this->load->library('encrypt');
		// $this->load->library('encryption');
		
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
	//akun
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
// 		echo "<pre>";
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
		$biodata = $this->user_model->select_biodata_by_ID($this->session->userdata('userId'), 3)->row();
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['ta'] = $this->ta_model->selet_ta_by_npm($this->session->userdata('username'));
		$data['status_ta'] = $this->ta_model->select_active_ta($this->session->userdata('username'));

		if($biodata->prodi == NULL || $biodata->dosen_pa == NULL || $biodata->dosen_pa == "0"){
			echo "<script type='text/javascript'>alert('Silahkan Isi Biodata Terlebih Dahulu');window.location.href ='" . base_url() . "mahasiswa/biodata';</script>";
		}
		else{
			$this->load->view('header_global', $header);
			$this->load->view('mahasiswa/header');

			$this->load->view('mahasiswa/tema_ta', $data);

			$this->load->view('footer_global');
		}
	
		
	}

	function form_tugas_akhir()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['biodata'] = $this->user_model->select_biodata_by_ID($this->session->userdata('userId'), 3)->row();
		$data['status_ta'] = $this->ta_model->select_active_ta($this->session->userdata('username'));
		
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);

		if($this->input->get('aksi') == "ubah")
		{
			$ta = $this->ta_model->select_ta_by_id($id, $this->session->userdata('username'));

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
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['lampiran'] = $this->ta_model->select_lampiran_by_ta($id, $this->session->userdata('username'));
		$data['status_ta'] = $this->ta_model->select_active_ta($this->session->userdata('username'));
		

		$this->load->view('header_global', $header);
		$this->load->view('mahasiswa/header');

		$this->load->view('mahasiswa/form_lampiran_ta', $data);

        $this->load->view('footer_global');
	}

	public function tambah_berkas_ta()
	{
// 		echo "<pre>";
// 		print_r($_POST);
// 		print_r($_FILES);

		$file1 = file_get_contents($_FILES['file']['tmp_name']);
		$file1 = substr($file1,0,4);
		$size = $_FILES['file']['size'];
		$id = $this->encrypt->decode($this->input->post('id_pengajuan'));
		$data = array(
			'id_pengajuan' => $id,
			'nama_berkas' => $this->input->post('nama_berkas'),
			'jenis_berkas' => $this->input->post('jenis_berkas')
		);

		if(!empty($_FILES)) {
			if($file1 == '%PDF' && $size <= 1200000){
				$file = $_FILES['file']['tmp_name']; 
				$sourceProperties = getimagesize($file);
				$fileNewName = $this->session->userdata('username').$this->input->post('jenis_berkas').$id;
				$folderPath = "assets/uploads/berkas-ta/";
				$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

				$data['file'] = $folderPath. $fileNewName. ".". $ext;
				move_uploaded_file($file, $folderPath. $fileNewName. ".". $ext);

				$this->ta_model->insert_lampiran($data);
				redirect(site_url("mahasiswa/tugas-akhir/tema/lampiran?id=".$this->input->post('id_pengajuan')."&status=sukses"));
			}
			else{
				redirect(site_url("mahasiswa/tugas-akhir/tema/lampiran?id=".$this->input->post('id_pengajuan')."&status=gagal"));
			}

		}
		
		
		
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

		$npm = $this->session->userdata('username');
		$npm = substr($npm,2,1);
		switch($npm){
			case "0":
				$jenis = "Tugas Akhir";
				break;
			case "1":
			case "5":
				$jenis = "Skripsi";
				break;	
			case "2":
				$jenis = "Tesis";
				break;	
			case "3":
				$jenis = "Disertasi";
				break;	
		}
		// echo "<pre>";
		// print_r($data);
		$data['npm'] = $this->session->userdata('username');
		$data['jenis'] = $jenis;
		if($aksi == "ubah") {
			$where = $data['id_pengajuan'];
			unset($data['id_pengajuan']);
			$this->ta_model->update($data, $where);
			
			$id_user = $this->session->userdata('userId');
			$this->ta_model->update_ta_approve($ttd, $id_user, $where);
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
		$id_pengajuan = $this->encrypt->decode($id);
		$file = $this->input->post('file_berkas');
		
		$data = array("id" => $id);
		$this->ta_model->delete_lampiran($data);
		unlink($file);
	    
	    redirect(site_url("mahasiswa/tugas-akhir/tema"));
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
		$status = $this->input->post('status');

		$data = array("id_pengajuan" => $id);
		$where = $data['id_pengajuan'];

		$this->ta_model->ajukan_ta_perbaikan($id,$status);
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
		$ket = $this->ta_model->select_ta_by_npm_akses($this->session->userdata('username'));
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		// echo "<pre>";
		// print_r($data);
		if(empty($ket)){
			echo "<script type='text/javascript'>alert('Silahkan Ajukan Tema Terlebih Dahulu');window.location = ('tema') </script>";
		}
		else{
			if($ket->jenis != "Tugas Akhir"){
				if ($ket->status == 4){
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
			else{
				$ta = $this->ta_model->get_dosen_verifikator($ket->id_pengajuan);
				if($ket->status == 4){
					if($ta->ket == 5){
						$data['seminar'] = $this->ta_model->select_seminar_by_npm($this->session->userdata('username'));
						$this->load->view('header_global', $header);
						$this->load->view('mahasiswa/header');

						$this->load->view('mahasiswa/seminar/seminar_ta',$data);
						$this->load->view('footer_global');
					}
					else{
						echo "<script type='text/javascript'>alert('Verifikasi Program TA Belum Selesai');window.location = ('verifikasi-ta') </script>";
					}
				}
				else{
					echo "<script type='text/javascript'>alert('Pengajuan Tema Belum Disetujui');window.location = ('tema') </script>";
				}
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
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);
		if($this->input->get('aksi') == "ubah")
		{
			$ta = $this->ta_model->select_seminar_by_id($id);
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
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['lampiran'] = $this->ta_model->select_lampiran_by_seminar($id);
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

		$file1 = file_get_contents($_FILES['file']['tmp_name']);
		$file1 = substr($file1,0,4);
		$size = $_FILES['file']['size'];

		$id = $this->encrypt->decode($this->input->post('id_seminar'));
		$data = array(
			'id_seminar' => $id,
			'nama_berkas' => $this->input->post('nama_berkas'),
			'jenis_berkas' => $this->input->post('jenis_berkas')
		);

		if(!empty($_FILES)) {
			if($file1 == '%PDF' && $size <= 2100000){
				$file = $_FILES['file']['tmp_name']; 
				$sourceProperties = getimagesize($file);
				$fileNewName = $this->session->userdata('username').$this->input->post('jenis_berkas').$id;
				$folderPath = "assets/uploads/berkas-seminar/";
				$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

				$data['file'] = $folderPath. $fileNewName. ".". $ext;
				move_uploaded_file($file, $folderPath. $fileNewName. ".". $ext);

				$this->ta_model->insert_lampiran_seminar($data);
				redirect(site_url("mahasiswa/tugas-akhir/seminar/lampiran?id=".$this->input->post('id_seminar')."&status=sukses"));
			}
			else{
				redirect(site_url("mahasiswa/tugas-akhir/seminar/lampiran?id=".$this->input->post('id_seminar')."&status=gagal"));
			}
		}

		
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
	    
	    redirect(site_url("mahasiswa/tugas-akhir/seminar"));
	}

	function add_seminar()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);

		//check
		$npm = $data['npm'];
		$ttd = $data['ttd'];
		$aksi = $data['aksi'];

		$pb1 = $data['Pembimbing_Utama'];
		$pb2 = $data['Pembimbing_2'];
		$pb3 = $data['Pembimbing_3'];
		$ps1 = $data['Penguji_1'];
		$ps2 = $data['Penguji_2'];
		$ps3 = $data['Penguji_3'];

		$jenis = $data['jenis'];

		
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
			$cek = $this->ta_model->cek_seminar($data['npm'],$data['jenis']);
			// print_r($cek);
			// echo "aaa"
			if(!empty($cek)){
				redirect(site_url("mahasiswa/tugas-akhir/seminar?status=gagal"));
			}
			else{
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
				'keterangan_tolak' => NULL
				
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
			}
			if($jenis == "Seminar Tugas Akhir"){
				if($ps1 != NULL){
					$result = $this->db->query('SELECT id_user FROM `tbl_users_dosen` WHERE nip_nik = '.$ps1)->row();
					if(!empty($result)){
						$result = $result->id_user;
					}
					else{
						$result = 0;
					}
						$data_approval = array(
							'id_pengajuan' => $insert_id,
							'status_slug' => 'Penguji 1',
							'id_user' => $result,
							'ttd' => '',	
						);

						$this->ta_model->insert_approval_seminar($data_approval);
				}
			}

		}
		redirect(site_url("mahasiswa/tugas-akhir/seminar?status=berhasil"));
		
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

	function ajukan_perbaikan_seminar()
	{
		$id = $this->input->post('id_seminar');
		$status = $this->input->post('status');
		// echo $id;
		// echo $status;
		// $data = array("id_pengajuan" => $id);
		// $where = $data['id_pengajuan'];

		$this->ta_model->ajukan_seminar_perbaikan($id,$status);
		redirect(site_url("mahasiswa/tugas-akhir/seminar"));
	}

	//verifikasi program ta
	function verifikasi_ta()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$ket = $this->ta_model->get_latest_ta_npm($this->session->userdata('username'));

		if(empty($ket)){
				echo "<script type='text/javascript'>alert('Silahkan Ajukan Tema Terlebih Dahulu');window.location = ('tema') </script>";
			}
			else{ 
				if ($ket->status == 4){
					$data['ta'] = $this->ta_model->get_verifikasi_program_ta($this->session->userdata('username'));
		
					$this->load->view('header_global', $header);
					$this->load->view('mahasiswa/header');

					$this->load->view('mahasiswa/verifikasi-ta/verifikasi_ta',$data);

					$this->load->view('footer_global');
					
				}
				else{
					echo "<script type='text/javascript'>alert('Pengajuan Tema Belum Disetujui');window.location = ('tema') </script>";
				}
				
		}		
		

		
	}

	function verifikasi_ta_ajukan()
	{
		$id = $this->input->post('id');
		$ta = $this->ta_model->get_ta_by_id($id);

		$komponen = $this->ta_model->get_verifikasi_program_ta_komponen($ta->bidang_ilmu);

        if(!empty($komponen)){
            foreach ($komponen as $kom){
			$data = array(
				'id_komponen' => $kom->id,
				'id_tugas_akhir' => $id,
				'pertemuan' => "",	
			);
			$this->ta_model->insert_verifikasi_ta_pertemuan($data);
		    }

	    	$this->ta_model->update_verifikasi_ta_ket($id);

		    redirect(site_url("mahasiswa/tugas-akhir/verifikasi-ta?status=sukses"));
        }
        else{
         redirect(site_url("mahasiswa/tugas-akhir/verifikasi-ta?status=error"));   
        }
	
	}

	//bimbingan mahasiswa
	function bimbingan()
	{
		
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		// $data['lampiran'] = $this->ta_model->select_lampiran_by_seminar($this->input->get('id'));
		// $teks = $this->encryption->encode('Your data');
		// $this->encryption->decode('Your encrypted data');
		$data = array('planet' => 'aa');
		
		
		// echo $data;
		$this->load->view('header_global', $header);
		$this->load->view('mahasiswa/header');

		$this->load->view('mahasiswa/bimbingan',$data);

        $this->load->view('footer_global');
	}

	//lembaga kemahasiswaan
	function add_lk()
	{
		$data = $this->input->post();
// 		echo "<pre>";
// 		print_r($data);

		$iduser = $data['iduser'];
		$id_lk = $data['id_lk'];
		$jabatan = $data['jabatan_lk'];
		$aktif = $data['status_lk'];
		$periode = $data['periode_lk'];

		$check = $this->user_model->check_lk($periode,$id_lk,$jabatan);
		if(!empty($check)){
			// $mhs = $this->user_model->get_mahasiswa_data($check->id_);
			redirect(site_url("mahasiswa/kelola-biodata?status=duplikat&user=".$this->encrypt->encode($check->id_user)));
		}
		else{
		$data_lk = array(
			'id_user' => $iduser,
			'id_lk' => $id_lk,
			'periode' => $periode,
			'jabatan' => $jabatan,
			'aktif' => $aktif
		);

		$this->user_model->insert_lk_mhs($data_lk);		
		redirect(site_url("mahasiswa/kelola-biodata?status=sukses"));
		}
	}

	function update_lk()
	{
		$data = $this->input->post();
// 		echo "<pre>";
// 		print_r($data);

		$id = $data['id_tugas'];
		$ket = $data['ket'];

		$this->user_model->update_tugas_lk($id,$ket);	
		redirect(site_url("mahasiswa/kelola-biodata?status=sukses"));

	}


	//pkl mahasiswa
	function pkl_home()
	{	
		$biodata = $this->user_model->select_biodata_by_ID($this->session->userdata('userId'), 3)->row();
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['kp'] = $this->pkl_model->selet_kp_by_npm($this->session->userdata('username'));
		$data['status_kp'] = $this->pkl_model->select_active_kp($this->session->userdata('username'));

		if($biodata->prodi == NULL || $biodata->dosen_pa == NULL || $biodata->dosen_pa == "0"){
			echo "<script type='text/javascript'>alert('Silahkan Isi Biodata Terlebih Dahulu');window.location.href ='" . base_url() . "mahasiswa/biodata';</script>";
		}
		else{
			$this->load->view('header_global', $header);
			$this->load->view('mahasiswa/header');

			$this->load->view('mahasiswa/pkl/pkl_home',$data);

			$this->load->view('footer_global');
		}
	}

	function pkl_home_form()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['biodata'] = $this->user_model->select_biodata_by_ID($this->session->userdata('userId'), 3)->row();
		$data['status_pkl'] = $this->pkl_model->select_active_kp($this->session->userdata('username'));
		$data['jurusan'] = $this->user_model->get_jurusan($this->session->userdata('username'));
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);

		if($this->input->get('aksi') == "ubah")
		{
			$pkl = $this->pkl_model->select_pkl_by_id($id, $this->session->userdata('username'));
			$data_pkl = $pkl[0];
		}
		else
		{
			$data_pkl = array(
				'pkl_id' => null,
				'id_periode' => null,
				'id_lokasi' => null,
				'ipk' => null,
				'sks' => null,
				'keterangan_tolak' => null,
				'ttd' => null
			);
		}

		$data['data_pkl'] = $data_pkl;
		
			$this->load->view('header_global', $header);
			$this->load->view('mahasiswa/header');
			$this->load->view('mahasiswa/pkl/pkl_home_form',$data);
			$this->load->view('footer_global');
	}

	function pkl_home_form_add()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);
		$aksi = $data['aksi'];
		$id_pkl = $data['pkl_id'];

		if($aksi != "ubah"){
			$pkl_mhs = array(
				"npm" => $data['npm'],
				"id_periode" => $data['id_pkl'],
				"id_lokasi" => $data['lokasi'],
				"ipk" => $data['ipk'],
				"sks" => $data['sks'],
				"ttd" => $data['ttd']
			);
	
			//insert pkl_mahasiswa
			$insert_id = $this->pkl_model->insert_pkl_mahasiswa($pkl_mhs);
			//insert approval
			$pkl_approve = array(
				"pkl_id" => $insert_id,
				"status_slug" =>"Mahasiswa",
				"id_user" =>$this->session->userdata('userId'),
				"ttd" => $data['ttd']
			);
			$this->pkl_model->insert_approval_pkl($pkl_approve);
		}
		else{
			$pkl_mhs = array(
				"npm" => $data['npm'],
				"id_periode" => $data['id_pkl'],
				"id_lokasi" => $data['lokasi'],
				"ipk" => $data['ipk'],
				"sks" => $data['sks'],
				"ttd" => $data['ttd']
			);
			
			$pkl_approve = array(
				"ttd" => $data['ttd']
			);

			$this->pkl_model->update_pengajuan_pkl($pkl_mhs, $id_pkl, $pkl_approve);
		}
		redirect(site_url("mahasiswa/pkl/pkl-home"));
	}

	function pkl_home_delete()
	{
		$id = $this->input->post('id_pkl');
		// echo "<pre>";
		// print_r($data);

		$del = array("pkl_id"=>$id);

		$this->pkl_model->delete_pengajuan_pkl($del);
		//delete berkas dan lampiran
		$this->pkl_model->delete_berkas_kp($id);

		redirect(site_url("mahasiswa/pkl/pkl-home"));
	}

	function pkl_home_lampiran()
	{
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['lampiran'] = $this->pkl_model->select_lampiran_by_kp($id, $this->session->userdata('username'));
		$data['status_pkl'] = $this->pkl_model->select_active_kp($this->session->userdata('username'));
		
		$this->load->view('header_global', $header);
		$this->load->view('mahasiswa/header');

		$this->load->view('mahasiswa/pkl/pkl_home_lampiran', $data);

        $this->load->view('footer_global');
	}

	function pkl_home_lampiran_upload()
	{
		// $datas = $this->input->post();
		// echo "<pre>";
		// print_r($datas);
		if($this->input->post('aksi') == "lampiran")
		{
			if($this->input->post('jenis_berkas') == "16" ){
				$approval_id = $this->input->post('approval_id');
				$file1 = file_get_contents($_FILES['file']['tmp_name']);
				$file1 = substr($file1,0,4);
				$size = $_FILES['file']['size'];
				if(!empty($_FILES)) {
					if($file1 == '%PDF' && $size <= 1200000){
						$file = $_FILES['file']['tmp_name']; 
						$sourceProperties = getimagesize($file);
						$fileNewName = $this->session->userdata('username').$this->input->post('jenis_berkas').$approval_id;
						$folderPath = "assets/uploads/berkas-kp/lampiran_instansi/";
						$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
		
						$files = $folderPath. $fileNewName. ".". $ext;
						move_uploaded_file($file, $folderPath. $fileNewName. ".". $ext);
						$nama_berkas = $this->input->post('nama_berkas');

						$this->pkl_model->insert_lampiran_kp_instansi($approval_id,$files,$nama_berkas);
						redirect(site_url("mahasiswa/pkl/pkl-home/lampiran?aksi=lampiran&id=".$this->input->post('pkl_id')."&status=sukses"));
					}
					else{
						redirect(site_url("mahasiswa/pkl/pkl-home/lampiran?aksi=lampiran&id=".$this->input->post('pkl_id')."&status=gagal"));
					}
				}
				else{
					redirect(site_url("mahasiswa/pkl/pkl-home/lampiran?aksi=lampiran&id=".$this->input->post('pkl_id')."&status=gagal"));
				}

			}
			else{
				redirect(site_url("mahasiswa/pkl/pkl-home/lampiran?aksi=lampiran&id=".$this->input->post('pkl_id')."&status=kesalahan"));
			}
		}
		else{
		$file1 = file_get_contents($_FILES['file']['tmp_name']);
		$file1 = substr($file1,0,4);
		$size = $_FILES['file']['size'];
		$id = $this->encrypt->decode($this->input->post('pkl_id'));
		$data = array(
			'id_pkl' => $id,
			'nama_berkas' => $this->input->post('nama_berkas'),
			'jenis_berkas' => $this->input->post('jenis_berkas')
		);

		if(!empty($_FILES)) {
			if($file1 == '%PDF' && $size <= 1200000){
				$file = $_FILES['file']['tmp_name']; 
				$sourceProperties = getimagesize($file);
				$fileNewName = $this->session->userdata('username').$this->input->post('jenis_berkas').$id;
				$folderPath = "assets/uploads/berkas-kp/";
				$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

				$data['file'] = $folderPath. $fileNewName. ".". $ext;
				move_uploaded_file($file, $folderPath. $fileNewName. ".". $ext);

				$this->pkl_model->insert_lampiran_kp($data);
				redirect(site_url("mahasiswa/pkl/pkl-home/lampiran?id=".$this->input->post('pkl_id')."&status=sukses"));
			}
			else{
				redirect(site_url("mahasiswa/pkl/pkl-home/lampiran?id=".$this->input->post('pkl_id')."&status=gagal"));
			}
		}
		else{
			redirect(site_url("mahasiswa/pkl/pkl-home/lampiran?id=".$this->input->post('pkl_id')."&status=gagal"));
		}
	  }
	}

	function pkl_home_lampiran_delete()
	{
		$id = $this->input->post('id_berkas');
		$id_pkl = $this->input->post('id_pkl');
		$id_pkl= $this->encrypt->decode($id_pkl);
		$file = $this->input->post('file_berkas');
		
		$data = array("id" => $id);
		$this->pkl_model->delete_lampiran_kp($data);
		unlink($file);
	    
	    redirect(site_url("mahasiswa/pkl/pkl-home"));
	}

	function pkl_home_delete_lampiran_instansi()
	{
		$id = $this->input->post('approval_id');
		$instansi_file = $this->pkl_model->get_approval_koor_by_id($id);
	
		$this->pkl_model->delete_lampiran_kp_instansi($id);
		unlink($instansi_file->file);
	    
	    redirect(site_url("mahasiswa/pkl/pkl-home"));
	}

	function pkl_home_ajukan()
	{
		$id = $this->input->post('id_pkl');
		$data = array("pkl_id" => $id);
		$where = $id;

		$this->pkl_model->ajukan_pkl($id);
		redirect(site_url("mahasiswa/pkl/pkl-home"));
	}

	function pkl_home_ajukan_perbaikan()
	{
		$id = $this->input->post('id_pkl');
		$status = $this->input->post('status');
		// echo "<pre>";
		// print_r($id);
		// print_r($status);

		$this->pkl_model->ajukan_perbaikan_pkl($id,$status);
		redirect(site_url("mahasiswa/pkl/pkl-home"));
	}

	function pkl_home_ajukan_berkas_instansi()
	{
		$approval_id = $this->input->post('approval_id');
		// echo $approval_id;
		//approval id status > 2
		$status = 2;
		$this->pkl_model->approval_id_status($approval_id,$status);
		redirect(site_url("mahasiswa/pkl/pkl-home"));
	}

	function pkl_seminar()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$check = $this->pkl_model->check_pkl_done($this->session->userdata('username'));
		$check_pbl = $this->pkl_model->get_pb_lapangan_by_npm($this->session->userdata('username'));
		
		if(empty($check)){
			echo "<script type='text/javascript'>alert('Proses Pengajuan KP/PKL Belum Selesai');javascript:history.back();</script>";
		}
		else{
			$data['seminar'] = $this->pkl_model->get_pkl_seminar($check->pkl_id);
			$data['seminar_cek'] = $this->pkl_model->get_pkl_seminar_cek($check->pkl_id);
			$data['pkl'] = $this->pkl_model->check_pkl_done($this->session->userdata('username'));
			$this->load->view('header_global', $header);
			$this->load->view('mahasiswa/header');

			$this->load->view('mahasiswa/pkl/seminar/pkl_seminar',$data);

			$this->load->view('footer_global');
		}
	}

	function pkl_seminar_form()
	{
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['biodata'] = $this->user_model->select_biodata_by_ID($this->session->userdata('userId'), 3)->row();
		$data['status_pkl'] = $this->pkl_model->select_active_seminar_kp($id);
		$data['jurusan'] = $this->user_model->get_jurusan($this->session->userdata('username'));
		$data['pkl'] = $this->pkl_model->check_pkl_done($this->session->userdata('username'));

		if($this->input->get('aksi') == "ubah")
		{
			$pkl = $this->pkl_model->select_pkl_seminar_by_id($id);
			$pkl = $pkl[0];
		}
		else
		{
			$pkl = array(
				'seminar_id' => null,
				'judul' => null,
				'tgl_pelaksanaan' => null,
				'waktu_pelaksanaan' => null,
				'tempat' => null,
				'ipk' => null,
				'sks' => null,
				'ttd' => null,
			);
		}

		$data['data_pkl'] = $pkl;
		
			$this->load->view('header_global', $header);
			$this->load->view('mahasiswa/header');
			$this->load->view('mahasiswa/pkl/seminar/pkl_seminar_form',$data);
			$this->load->view('footer_global');
	}

	function pkl_seminar_form_add()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);

		$data_seminar = array(
			"pkl_id" => $this->input->post('pkl_id'),
			"judul" => $this->input->post('judul'),
			"tgl_pelaksanaan" => $this->input->post('tanggal'),
			"waktu_pelaksanaan" => $this->input->post('waktu'),
			"pkl_id" => $this->input->post('pkl_id'),
			"tempat" => $this->input->post('tempat'),
			"pkl_id" => $this->input->post('pkl_id'),
			"ipk" => $this->input->post('ipk'),
			"sks" => $this->input->post('sks'),
		);

		if($this->input->post('aksi') == "ubah"){
			$seminar_id = $this->input->post('seminar_id');
			//update pkl_seminar
			$this->pkl_model->update_seminar($seminar_id,$data_seminar);
			//input pkl approval
			$this->pkl_model->update_approval_seminar($seminar_id,'Mahasiswa',$this->input->post('ttd'));
		}
		else{
			//input pkl_seminar
			$insert_id = $this->pkl_model->input_seminar($data_seminar);

			$data_approval = array(
				"seminar_id" => $insert_id,
				"status_slug" => "Mahasiswa",
				"id_user"=> $this->session->userdata('userId'),
				"ttd"=>$this->input->post('ttd')
			);
			//input pkl approval
			$this->pkl_model->input_approval_seminar($data_approval);
		}

		redirect(site_url("/mahasiswa/pkl/seminar"));
	}

	function pkl_seminar_delete()
	{
		$id = $this->input->post('seminar_id');
		$del = array("seminar_id"=>$id);
		//delete
		$this->pkl_model->delete_seminar($del);

		//delete berkas dan lampiran
		$this->pkl_model->delete_berkas_seminar_kp($id);

		redirect(site_url("/mahasiswa/pkl/seminar"));
	}

	function pkl_seminar_lampiran()
	{
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['lampiran'] = $this->pkl_model->select_lampiran_seminar_kp($id);
		// $data['status_pkl'] = $this->pkl_model->select_active_kp($this->session->userdata('username'));
		
		$this->load->view('header_global', $header);
		$this->load->view('mahasiswa/header');

		$this->load->view('mahasiswa/pkl/seminar/pkl_seminar_lampiran',$data);

        $this->load->view('footer_global');
	}

	function pkl_seminar_lampiran_upload()
	{
		$file1 = file_get_contents($_FILES['file']['tmp_name']);
		$file1 = substr($file1,0,4);
		$size = $_FILES['file']['size'];
		$id = $this->encrypt->decode($this->input->post('seminar_id'));
		$data = array(
			'seminar_id' => $id,
			'nama_berkas' => $this->input->post('nama_berkas'),
			'jenis_berkas' => $this->input->post('jenis_berkas')
		);

		if(!empty($_FILES)) {
			if($file1 == '%PDF' && $size <= 1200000){
				$file = $_FILES['file']['tmp_name']; 
				$sourceProperties = getimagesize($file);
				$fileNewName = $this->session->userdata('username').$this->input->post('jenis_berkas').$id;
				$folderPath = "assets/uploads/berkas-kp-seminar/";
				$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

				$data['file'] = $folderPath. $fileNewName. ".". $ext;
				move_uploaded_file($file, $folderPath. $fileNewName. ".". $ext);

				$this->pkl_model->insert_lampiran_seminar_kp($data);
				redirect(site_url("mahasiswa/pkl/seminar/lampiran?id=".$this->input->post('seminar_id')."&status=sukses"));
			}
			else{
				redirect(site_url("mahasiswa/pkl/seminar/lampiran?id=".$this->input->post('seminar_id')."&status=gagal"));
			}
		}
		else{
			redirect(site_url("mahasiswa/pkl/seminar/lampiran?id=".$this->input->post('seminar_id')."&status=gagal"));
		}
	}

	function pkl_seminar_lampiran_delete()
	{
		$id = $this->input->post('id_berkas');
		$file = $this->input->post('file_berkas');
		
		$data = array("id" => $id);
		$this->pkl_model->delete_lampiran_seminar_kp($data);
		unlink($file);
	    
	    redirect(site_url("mahasiswa/pkl/seminar"));
	}

	function pkl_seminar_ajukan()
	{
		$seminar_id = $this->input->post('seminar_id');
		//status seminar > 0
		$status = 0;
		$this->pkl_model->update_seminar_pkl($seminar_id,$status);
		redirect(site_url("mahasiswa/pkl/seminar"));
	}

	function pkl_home_pb_lapangan()
	{
		$data = $this->input->post();
		$str = "kp/pklfmipa";
		$token = md5($str.$this->input->post('nama').$this->input->post('email'));
		$data['token'] = $token;

		$this->pkl_model->insert_pb_lapangan($data);

		$pkl_id = $this->input->post('pkl_id');
		// //send email
		// // $pkl_id = $this->pkl_model->get_seminar_by_id($id)->pkl_id;
		// //pb lapangan
		// $pbl = $this->pkl_model->get_pb_lapangan($pkl_id);
		// // echo "<pre>";
		// // print_r($data);

		// if(!empty($pbl)){
		// 	$config = Array(  
		// 		'protocol' => 'smtp',  
		// 		'smtp_host' => 'ssl://smtp.googlemail.com',  
		// 		'smtp_port' => 465,  
		// 		'smtp_user' => 'apps.fmipa.unila@gmail.com',   
		// 		'smtp_pass' => 'apps_fmipa 2020',   
		// 		'mailtype' => 'html',   
		// 		'charset' => 'iso-8859-1'  
		// 	);  
		// 		//send email
		// 			$this->load->library('email', $config);
		// 			$this->email->set_newline("\r\n");  
		// 			$this->email->from('apps.fmipa.unila@gmail.com', 'SIMIPA');   
		// 			$this->email->to($pbl->email);   
		// 			$this->email->subject('Penilaian Seminar KP/PKL Fakultas Matematika dan Ilmu Pengetahuan Alam');   
		// 			$this->email->message("
		// 			Kepada Yth. $pbl->nama
		// 			<br>
		// 			Untuk Melakukan Penilaian Seminar KP/PKL Mahasiswa Fakultas Matematika Dan Ilmu Pengetahuan Alam Sebagai Pembimbing Lapangan Silahkan Klik Link Berikut :<br>
		// 			http://apps.fmipa.unila.ac.id/simipa/approval/seminar?token=$token
		// 			<br><br>
		// 			Terimakasih.
					
		// 			");
					
		// 			if (!$this->email->send()) {  
		// 				echo $this->email->print_debugger();  
		// 			}else{  
						
		// 			}   
		// 	}

		redirect(site_url("mahasiswa/pkl/pkl-home"));
	}

	function pkl_home_pb_lapangan_ubah()
	{
		$id = $this->input->post('pkl_id');
		$str = "kp/pklfmipa_ubah";
		$token = md5($str.$this->input->post('nama').$this->input->post('email'));

		$data = array(
			"nama" => $this->input->post('nama'),
			"nip_nik" => $this->input->post('nip_nik'),
			"email" => $this->input->post('email'),
			"no_telp" => $this->input->post('no_telp'),
			"token" => $token,
		);
		$this->pkl_model->update_pb_lapangan($id,$data);

		//send email
		// // $pkl_id = $this->pkl_model->get_seminar_by_id($id)->pkl_id;
		// //pb lapangan
		// $pbl = $this->pkl_model->get_pb_lapangan($id);
		// // echo "<pre>";
		// // print_r($data);

		// if(!empty($pbl)){
		// 	$config = Array(  
		// 		'protocol' => 'smtp',  
		// 		'smtp_host' => 'ssl://smtp.googlemail.com',  
		// 		'smtp_port' => 465,  
		// 		'smtp_user' => 'apps.fmipa.unila@gmail.com',   
		// 		'smtp_pass' => 'apps_fmipa 2020',   
		// 		'mailtype' => 'html',   
		// 		'charset' => 'iso-8859-1'  
		// 	);  
		// 		//send email
		// 			$this->load->library('email', $config);
		// 			$this->email->set_newline("\r\n");  
		// 			$this->email->from('apps.fmipa.unila@gmail.com', 'SIMIPA');   
		// 			$this->email->to($pbl->email);   
		// 			$this->email->subject('Penilaian Seminar KP/PKL Fakultas Matematika dan Ilmu Pengetahuan Alam');   
		// 			$this->email->message("
		// 			Kepada Yth. $pbl->nama
		// 			<br>
		// 			Untuk Melakukan Penilaian Seminar KP/PKL Mahasiswa Fakultas Matematika Dan Ilmu Pengetahuan Alam Sebagai Pembimbing Lapangan Silahkan Klik Link Berikut :<br>
		// 			http://apps.fmipa.unila.ac.id/simipa/approval/seminar?token=$token
		// 			<br><br>
		// 			Terimakasih.
					
		// 			");
					
		// 			if (!$this->email->send()) {  
		// 				echo $this->email->print_debugger();  
		// 			}else{  
						
		// 			}   
		// 	}

		redirect(site_url("mahasiswa/pkl/pkl-home"));
	}

	
	function pkl_seminar_ajukan_perbaikan()
	{
		$id = $this->input->post('seminar_id');
		$status = $this->input->post('status');

		$this->pkl_model->ajukan_perbaikan_seminar_pkl($id,$status);
		redirect(site_url("mahasiswa/pkl/seminar"));
	}

	function layanan_fakultas()
	{
		$jenis = $this->uri->segment(3);
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		
		//get form
		if($jenis == 'umum-keuangan'){
			$jenis = 'Umum dan Keuangan';
		}
		$data['form'] = $this->layanan_model->get_form_mhs2($this->session->userdata('username'),$jenis);
		
		$this->load->view('header_global', $header);
		$this->load->view('mahasiswa/header');

		$this->load->view('mahasiswa/layanan/layanan_fakultas',$data);

        $this->load->view('footer_global');
	}

	function layanan_fakultas_form()
	{
		$jenis = $this->uri->segment(3);
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		// $data['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['biodata'] = $this->user_model->select_biodata_by_ID($this->session->userdata('userId'), 3)->row();
		
		if(($data['biodata']->prodi == NULL || $data['biodata']->prodi == "0") || ($data['biodata']->dosen_pa == NULL || $data['biodata']->dosen_pa == "0" ) 
		|| ($data['biodata']->jalur_masuk == NULL || $data['biodata']->jalur_masuk == "0") || ($data['biodata']->asal_sekolah == NULL || $data['biodata']->asal_sekolah == "0")
		|| ($data['biodata']->nama_sekolah == '') || ($header['akun']->tempat_lahir == NULL) || ($header['akun']->tanggal_lahir == NULL) || ($header['akun']->jalan == NULL)
		|| ($header['akun']->provinsi == NULL) || ($header['akun']->kota_kabupaten == NULL) || ($header['akun']->kecamatan == NULL) || ($header['akun']->kelurahan_desa == NULL)
		|| ($header['akun']->kode_pos == NULL) || ($header['akun']->foto == NULL || $header['akun']->foto == '')){
			echo "<script type='text/javascript'>alert('Silahkan Lengkapi Informasi Akun dan Biodata Terlebih Dahulu');window.location.href ='" . base_url() . "mahasiswa/biodata';</script>";
		}
		else{

		$this->load->view('header_global', $header);
		$this->load->view('mahasiswa/header');

		$this->load->view('mahasiswa/layanan/layanan_fakultas_form2',$data);

		$this->load->view('footer_global');
		}
	}

	function layanan_fakultas_form_layanan()
	{
		// $data = $this->input->post();
		// echo " $this->input->post('layanan')";
		// print_r($data);
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['layanan'] = $this->layanan_model->select_layanan_by_id($this->input->post('layanan'));
		$data['atribut'] = $this->layanan_model->select_layanan_atribut_by_id($this->input->post('layanan'));
		$jenis = $this->uri->segment(3);

		if($this->input->post('layanan') == 2){
			redirect(site_url("mahasiswa/layanan-fakultas/akademik/bebas-lab"));
		}
		elseif($this->input->post('layanan') == 32 || $this->input->post('layanan') == 33){
			redirect(site_url("mahasiswa/prestasi"));
		}
		elseif($this->input->post('layanan') == 26){
			redirect(site_url("mahasiswa/beasiswa"));
		}
		else{
			$this->load->view('header_global', $header);
			$this->load->view('mahasiswa/header');
		
			$this->load->view('mahasiswa/layanan/layanan_fakultas_form_atribut',$data);
		
			$this->load->view('footer_global');
		}
		
	}

	function get_random_code()
	{
		$characters ='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < 6; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		// $randomString = '5vv2pQ';
		//cek kode 
		$cek = $this->layanan_model->cek_kode_layanan($randomString);
		if(!empty($cek)){
			$this->get_random_code();
		}else{
			return $randomString;
		}
	}

	function layanan_fakultas_form_simpan()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);

		$jns = $data['jenis'];
		$npm = $this->session->userdata('username');
		$ttd = $data['ttd'];
		$approver = $data['approver'];
		$id_layanan = $data['id_layanan'];
		$form_selesai = array(3,5,6,7,8,10,11,12,13,18,19,20,21,22,23,25,26,35,37,38,44,45);

		if(in_array($id_layanan,$form_selesai)){
			$sts = '-1';
		}else{
			$sts = 0;
		}
		$id_last = $this->layanan_model->get_last_id_fak_mhs()->id;

		$data_layanan = array(
			"npm" => $npm,
			"id_layanan_fakultas" => $id_layanan,
			"ttd" => $ttd,
			"tingkat" => null,
			"status" => $sts
		);

		//input nama lab
		// if($id_layanan == 39){
		// 	//get id lab
		// 	$data_layanan['keterangan'] = "";
		// }
		//input layanan fak mhs
		$insert_id = $this->layanan_model->insert_layanan_fak_mhs($data_layanan);
		// update kode
		$kode = $this->get_random_code();
		$this->layanan_model->update_kode_layanan($insert_id,$kode);
		
		$atribut_id = $data['id_attribut'];
		foreach($atribut_id as $atr)
		{
			$meta_val = $data[$atr];
			$data_atr = array(
				"id_layanan_fak_mhs" => $insert_id,
				"meta_key" => $atr,
				"meta_value" => $meta_val,
			);
			//input layanan_fakultas_mahasiswa_meta
			$this->layanan_model->insert_layanan_fak_mhs_meta($data_atr);
		}

		if($id_layanan == 32 || $id_layanan == 33){
			$mhs_nama = $data['nama'];
			$n = 0;
			foreach($mhs_nama as $mhs){
				$data_tugas = array(
					"id_layanan_fakultas_mahasiswa" => $insert_id,
					"nama" => $mhs_nama[$n],
					"npm" => $data['npm'][$n],
					"jurusan" => $data['jurusan'][$n],
					"alamat" => $data['alamat'][$n] 
				);
				//input layanan_fakultas_tugas
				$this->layanan_model->insert_layanan_fak_tugas($data_tugas);
				$n++;
			}
		}

		if(in_array($id_layanan,$form_selesai)){
			redirect(site_url("mahasiswa/layanan-lacak"));
		}else{
			redirect(site_url("mahasiswa/layanan-fakultas/$jns"));
		}
	}

	function layanan_fakultas_delete()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);
		$id = $data['id_layanan'];
		$jns= $data['jenis'];

		//delete
		$this->layanan_model->delete_berkas_layanan($id);
		$this->layanan_model->delete_layanan_mhs($id);		

		redirect(site_url("mahasiswa/layanan-fakultas/$jns"));
	}

	function layanan_bebas_lab()
	{
		$jenis = $this->uri->segment(3);
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['form'] = $this->layanan_model->get_bebas_lab($this->session->userdata('username'));

		$this->load->view('header_global', $header);
		$this->load->view('mahasiswa/header');

		$this->load->view('mahasiswa/layanan/layanan_fakultas_bebas_lab',$data);

        $this->load->view('footer_global');
	}

	function layanan_bebas_lab_tambah()
	{
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);
		$seg = $this->uri->segment(5);
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		if($seg == 'tambah'){
			$data['lampiran'] = $this->layanan_model->get_berkas_lab2($this->session->userdata('username'));
		}
		else{
			$data['lampiran'] = $this->layanan_model->get_berkas_lab($this->session->userdata('username'));
		}

		$this->load->view('header_global', $header);
		$this->load->view('mahasiswa/header');

		$this->load->view('mahasiswa/layanan/layanan_fakultas_bebas_lab_form',$data);

        $this->load->view('footer_global');
	}

	function layanan_bebas_lab_form()
	{
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['lampiran'] = $this->layanan_model->get_berkas_lab($this->session->userdata('username'));

		$this->load->view('header_global', $header);
		$this->load->view('mahasiswa/header');

		$this->load->view('mahasiswa/layanan/layanan_fakultas_bebas_lab_form',$data);

        $this->load->view('footer_global');
	}

	function tambah_berkas_lab()
	{
		// print_r($_POST);
		// print_r($_FILES);
		$aksi = $this->input->post('aksi');

		if($aksi == 'tambah'){
			$file1 = file_get_contents($_FILES['file']['tmp_name']);
		$file1 = substr($file1,0,4);
		$size = $_FILES['file']['size'];

		$id = $this->encrypt->decode($this->input->post('id_bebas'));
		$input_by = $this->session->userdata('username'); 
		$data = array(
			'id_bebas_lab' => null,
			'nama_berkas' => $this->input->post('nama_berkas'),
			'jenis_berkas' => $this->input->post('jenis_berkas'),
			'input_by' => $input_by
		);

		if(!empty($_FILES)) {
			if($file1 == '%PDF' && $size <= 2100000){
				$file = $_FILES['file']['tmp_name']; 
				$sourceProperties = getimagesize($file);
				$fileNewName = $this->session->userdata('username').$this->input->post('jenis_berkas').$id;
				$folderPath = "assets/uploads/berkas-layanan/";
				$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

				$data['file'] = $folderPath. $fileNewName. ".". $ext;
				move_uploaded_file($file, $folderPath. $fileNewName. ".". $ext);

				$this->layanan_model->insert_lampiran_lab($data);
				redirect(site_url("mahasiswa/layanan-fakultas/akademik/bebas-lab/tambah?status=berhasil"));
			}
			else{
				redirect(site_url("mahasiswa/layanan-fakultas/akademik/bebas-lab/tambah?status=gagal"));
			}
		}
		else{
			redirect(site_url("mahasiswa/layanan-fakultas/akademik/bebas-lab/tambah?status=gagal"));
		}

		}
		else{

		$file1 = file_get_contents($_FILES['file']['tmp_name']);
		$file1 = substr($file1,0,4);
		$size = $_FILES['file']['size'];

		$id = $this->encrypt->decode($this->input->post('id_bebas'));
		$input_by = $this->session->userdata('username'); 
		$data = array(
			'id_bebas_lab' => $id,
			'nama_berkas' => $this->input->post('nama_berkas'),
			'jenis_berkas' => $this->input->post('jenis_berkas'),
			'input_by' => $input_by
		);

		if(!empty($_FILES)) {
			if($file1 == '%PDF' && $size <= 2100000){
				$file = $_FILES['file']['tmp_name']; 
				$sourceProperties = getimagesize($file);
				$fileNewName = $this->session->userdata('username').$this->input->post('jenis_berkas').$id;
				$folderPath = "assets/uploads/berkas-layanan/";
				$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

				$data['file'] = $folderPath. $fileNewName. ".". $ext;
				move_uploaded_file($file, $folderPath. $fileNewName. ".". $ext);

				$this->layanan_model->insert_lampiran_lab($data);
				redirect(site_url("mahasiswa/layanan-fakultas/akademik/bebas-lab/form?id=".$this->input->post('id_bebas')."&status=sukses"));
			}
			else{
				redirect(site_url("mahasiswa/layanan-fakultas/akademik/bebas-lab/form?id=".$this->input->post('id_bebas')."&status=gagal"));
			}
		}
		else{
			redirect(site_url("mahasiswa/layanan-fakultas/akademik/bebas-lab/form?id=".$this->input->post('id_bebas')."&status=gagal"));
		}
	  }
	}

	function hapus_berkas_lab()
	{
		// $data = $this->input->post();
		// echo "<pre>";
		// print_r($data);
		$id = $this->input->post('id_berkas');
		$id_bebas = $this->input->post('id_bebas');
		$id_bebas = $this->encrypt->decode($id);
		$file = $this->input->post('file_berkas');
		
		$data = array("id" => $id);
		$this->layanan_model->delete_lampiran_lab($data);
		unlink($file);
		redirect(site_url("mahasiswa/layanan-fakultas/akademik/bebas-lab"));
	    
	}

	function hapus_lab()
	{
		$id = $this->input->post('IDBebasLab');
		
		$data = array("id_bebas_lab" => $id);
		$this->layanan_model->delete_berkas_lab($id);
		$this->layanan_model->delete_lab($data);
		$this->layanan_model->delete_meta_lab($data);
		$this->layanan_model->delete_lampiran_lab($data);
		
	    redirect(site_url("mahasiswa/layanan-fakultas/akademik/bebas-lab"));
	}

	function layanan_bebas_lab_detail()
	{
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['lampiran'] = $this->layanan_model->get_berkas_lab($this->session->userdata('username'));

		$this->load->view('header_global', $header);
		$this->load->view('mahasiswa/header');

		$this->load->view('mahasiswa/layanan/layanan_fakultas_bebas_lab_detail',$data);

        $this->load->view('footer_global');
	}

	function simpan_data_lab()
	{
		$berkas_id = $this->input->post('ids');
		$npm =  $this->session->userdata('username');
		//add data di layanan_fakultas_mahasiswa
		$data_layanan = array(
			"npm" => $npm,
			"id_layanan_fakultas" => 2
		);
		$lay_id = $this->layanan_model->insert_layanan_fak_mhs($data_layanan);

		//tambah bebas_lab
		$data_lab = array(
			"npm" => $npm,
			"id_layanan_fakultas_mahasiswa" => $lay_id
		);
		$insert_id = $this->layanan_model->insert_bebas_lab($data_lab);

		//tambah bebas_lab_berkas
		$i=0;
		foreach($berkas_id as $berkas){
			$this->layanan_model->update_bebas_id_berkas($berkas_id[$i],$insert_id);
			$i++;
		}

		//tambah bebas_lab_meta
		$list_lab = $this->user_model->get_lab_all();
		foreach ($list_lab as $list){
			$data_meta = array(
				"id_bebas_lab" => $insert_id,
				"id_lab"=> $list->id_lab,
			);
			$id_meta = $this->layanan_model->insert_lab_meta($data_meta);
		}
		redirect(site_url("mahasiswa/layanan-fakultas/akademik/bebas-lab"));
	}

	function ajukan_bebas_lab()
	{
		$data = $this->input->post('id_meta');
		$status = 0;
		$keterangan = null;
		$this->layanan_model->update_bebas_meta($data,$status,$keterangan);
		redirect(site_url("mahasiswa/layanan-fakultas/akademik/bebas-lab"));
	}

	function layanan_bebas_ruang_baca()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['lampiran'] = $this->layanan_model->get_berkas_lab($this->session->userdata('username'));

		$this->load->view('header_global', $header);
		$this->load->view('mahasiswa/header');

		$this->load->view('mahasiswa/layanan/layanan_fakultas_bebas_lab_detail',$data);

        $this->load->view('footer_global');
	}

	function layanan_fakultas_ajukan()
	{
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);
		// echo $id;
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['lampiran'] = $this->layanan_model->get_lampiran_layanan($id);

		$this->load->view('header_global', $header);
		$this->load->view('mahasiswa/header');

		$this->load->view('mahasiswa/layanan/layanan_fakultas_form_ajukan',$data);

        $this->load->view('footer_global');
	}


	function layanan_fakultas_unggah()
	{
		// print_r($_POST);
		// print_r($_FILES);
		$aksi = $this->input->post('aksi');
		$jenis = $this->uri->segment(3);
		$file1 = file_get_contents($_FILES['file']['tmp_name']);
		$file1 = substr($file1,0,4);
		$size = $_FILES['file']['size'];

		$id = $this->encrypt->decode($this->input->post('id_lay'));
		$data = array(
			'id_layanan_fakultas_mahasiswa' => $id,
			'nama_berkas' => $this->input->post('nama_berkas'),
			'jenis_berkas' => $this->input->post('jenis_berkas'),
		);

		if(!empty($_FILES)) {
			if($file1 == '%PDF' && $size <= 2100000){
				$file = $_FILES['file']['tmp_name']; 
				$sourceProperties = getimagesize($file);
				$fileNewName = $this->session->userdata('username').$this->input->post('jenis_berkas').$id;
				$folderPath = "assets/uploads/berkas-layanan/layanan";
				$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

				$data['file'] = $folderPath.$fileNewName. ".". $ext;
				move_uploaded_file($file, $folderPath. $fileNewName. ".". $ext);

				$this->layanan_model->insert_lampiran_layanan($data);
				if($aksi == "perbaiki"){
					redirect(site_url("mahasiswa/layanan-fakultas/$jenis/ajukan?id=".$this->input->post('id_lay')."&aksi=".$aksi."&status=sukses"));
				}else{
					redirect(site_url("mahasiswa/layanan-fakultas/$jenis/ajukan?id=".$this->input->post('id_lay')."&status=sukses"));
				}
			}
			else{
				if($aksi == "perbaiki" ){
					redirect(site_url("mahasiswa/layanan-fakultas/$jenis/ajukan?id=".$this->input->post('id_lay')."&aksi=".$aksi."&status=gagal"));
				}else{
					redirect(site_url("mahasiswa/layanan-fakultas/$jenis/ajukan?id=".$this->input->post('id_lay')."&status=gagal"));
				}
			}
		}
		else{
			if($aksi == "perbaiki"){
				redirect(site_url("mahasiswa/layanan-fakultas/$jenis/ajukan?id=".$this->input->post('id_lay')."&aksi=".$aksi."&status=gagal"));
			}else{
				redirect(site_url("mahasiswa/layanan-fakultas/$jenis/ajukan?id=".$this->input->post('id_lay')."&status=gagal"));
			}
		}
	}

	function layanan_fakultas_hapus_berkas()
	{
		// $data = $this->input->post();
		// echo "<pre>";
		// print_r($data);

		$id = $this->input->post('id_berkas');
		$id_layanan = $this->input->post('id_layanan');
		$file = $this->input->post('file_berkas');
		$id_get = $this->input->get('id');
		$aksi = $this->input->post('aksi');

		$jenis = $this->uri->segment(3); 
		$data = array("id" => $id);
		$this->layanan_model->delete_lampiran_layanan($data);
		unlink($file);
		if($aksi == "perbaiki"){
			redirect(site_url("mahasiswa/layanan-fakultas/$jenis/ajukan?id=$id_get&aksi=$aksi"));
		}else{
			redirect(site_url("mahasiswa/layanan-fakultas/$jenis/ajukan?id=$id_get"));
		}
	}

	function layanan_fakultas_simpan()
	{
		// $data = $this->input->post();
		// echo "<pre>";
		// print_r($data);
		$id = $this->encrypt->decode($this->input->post('id_lay'));
		$aksi = $this->input->post('aksi');
		$seg = $this->uri->segment(3);
		if($aksi == "perbaiki"){
			$data_tolak = array(
				"status" => 0,
				"keterangan" => null
			);
			$this->layanan_model->update_layanan_fak_mhs($id,$data_tolak);
		}else{
			//input approver
			$data = $this->layanan_model->get_form_mhs_id($id);
			$layanan = $this->layanan_model->get_layanan_fakultas_by_id($data->id_layanan_fakultas);
			$this->layanan_model->insert_approver_mhs($id,$layanan->approver);
		}
		redirect(site_url("mahasiswa/layanan-lacak"));
	}

	function layanan_fakultas_lacak()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		
		//get form
		$data['form'] = $this->layanan_model->get_layanan_lacak($this->session->userdata('username'));
		
		$this->load->view('header_global', $header);
		$this->load->view('mahasiswa/header');

		$this->load->view('mahasiswa/layanan/layanan_lacak',$data);

        $this->load->view('footer_global');
	}

	//prestasi

	function prestasi()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['prestasi'] = $this->layanan_model->get_prestasi_by_npm($this->session->userdata('username'),$this->session->userdata('userId'));
		$this->load->view('header_global', $header);
		$this->load->view('mahasiswa/header');

		$this->load->view('mahasiswa/prestasi/prestasi',$data);

        $this->load->view('footer_global');
	}

	function prestasi_form()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		
		$this->load->view('header_global', $header);
		$this->load->view('mahasiswa/header');

		$this->load->view('mahasiswa/prestasi/tambah_prestasi');

        $this->load->view('footer_global');
	}

	function prestasi_surat_tugas()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		
		// $data['layanan'] = $this->layanan_model->select_layanan_by_id($this->input->post('layanan'));
		// $data['atribut'] = $this->layanan_model->select_layanan_atribut_by_id($this->input->post('layanan'));

		$this->load->view('header_global', $header);
		$this->load->view('mahasiswa/header');

		$this->load->view('mahasiswa/prestasi/surat_tugas');

        $this->load->view('footer_global');
	}

	function prestasi_surat_tugas_form()
	{
		$tugas = $this->input->post('jenis');
		if($tugas == 'Individu'){
			$id_layanan = 33;
		}elseif($tugas == 'Kelompok'){
			$id_layanan = 32;
		}

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['layanan'] = $this->layanan_model->select_layanan_by_id($id_layanan);
		$data['atribut'] = $this->layanan_model->select_layanan_atribut_by_id($id_layanan);
		$data['surat'] = $tugas;
		$data['id_layanan'] = $id_layanan;

		$this->load->view('header_global', $header);
		$this->load->view('mahasiswa/header');
		$this->load->view('mahasiswa/prestasi/surat_tugas_form',$data);
		$this->load->view('footer_global');
	}

	function prestasi_surat_tugas_form_simpan()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);

		$jns = $data['jenis'];
		$npm = $this->session->userdata('username');
		$ttd = null;
		// $approver = $data['approver'];
		$id_layanan = $data['id_layanan'];
		$data_layanan = array(
			"npm" => $npm,
			"id_layanan_fakultas" => $id_layanan,
			"ttd" => $ttd,
			"tingkat" => null
		);

		$insert_id = $this->layanan_model->insert_layanan_fak_mhs($data_layanan);

		$atribut_id = $data['id_attribut'];
		foreach($atribut_id as $atr)
		{
			$meta_val = $data[$atr];
			$data_atr = array(
				"id_layanan_fak_mhs" => $insert_id,
				"meta_key" => $atr,
				"meta_value" => $meta_val,
			);
			//input layanan_fakultas_mahasiswa_meta
			$this->layanan_model->insert_layanan_fak_mhs_meta($data_atr);
		}

		if($id_layanan == 32 || $id_layanan == 33){
			$mhs_npm = $data['npm'];
			$n = 0;
			foreach($mhs_npm as $mhs){
				$data_tugas = array(
					"id_layanan_fakultas_mahasiswa" => $insert_id,
					"nama" => null,
					"npm" => $mhs_npm[$n],
					"jurusan" => null,
					"alamat" => null 
				);
				//input layanan_fakultas_tugas
				$this->layanan_model->insert_layanan_fak_tugas($data_tugas);
				$n++;
			}
		}

		if($id_layanan == 32){
			$kategori = "Tim";
		}elseif($id_layanan == 33){
			$kategori = "Individu";
		}
		//insert prestasi
		$prestasi = array(
			// "npm" => $this->session->userdata('username'),
			// "kegiatan" => 
			"kategori" => $kategori,
			"insert_by" => $this->session->userdata('userId'),
			"id_layanan" => $insert_id
		);
		
		$insert_id2 = $this->layanan_model->insert_prestasi($prestasi);

		//input anggota
		// if($id_layanan == 32){
			$i = 0;
			//ketua yg mengisi
			// $anggota1 = array(
			// 	"id_prestasi" => $insert_id2,
			// 	"anggota_npm" => $data['npm'][$i]
			// );
			// $this->layanan_model->insert_prestasi_anggota($anggota1);
			foreach($mhs_npm as $mhs){
				$anggota = array(
					"id_prestasi" => $insert_id2,
					"anggota_npm" => $data['npm'][$i]
				);
				$this->layanan_model->insert_prestasi_anggota($anggota);
				$i++;
			}
			
		// }
		redirect(site_url("mahasiswa/prestasi"));
	}

	function prestasi_surat_tugas_form_ajukan()
	{
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);
		// echo $id;
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['lampiran'] = $this->layanan_model->get_lampiran_layanan($id);

		$this->load->view('header_global', $header);
		$this->load->view('mahasiswa/header');
		$this->load->view('mahasiswa/prestasi/surat_tugas_ajukan',$data);
		$this->load->view('footer_global');
	}

	function prestasi_surat_tugas_form_unggah()
	{
		// $data = $this->input->post();
		// echo "<pre>";
		// print_r($data);

		$aksi = $this->input->post('aksi');
		$jenis = $this->uri->segment(3);
		$file1 = file_get_contents($_FILES['file']['tmp_name']);
		$file1 = substr($file1,0,4);
		$size = $_FILES['file']['size'];

		$id = $this->encrypt->decode($this->input->post('id_lay'));
		$data = array(
			'id_layanan_fakultas_mahasiswa' => $id,
			'nama_berkas' => $this->input->post('nama_berkas'),
			'jenis_berkas' => $this->input->post('jenis_berkas'),
		);

		if(!empty($_FILES)) {
			if($file1 == '%PDF' && $size <= 2100000){
				$file = $_FILES['file']['tmp_name']; 
				$sourceProperties = getimagesize($file);
				$fileNewName = $this->session->userdata('username').$this->input->post('jenis_berkas').$id;
				$folderPath = "assets/uploads/berkas-layanan/layanan";
				$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

				$data['file'] = $folderPath.$fileNewName. ".". $ext;
				move_uploaded_file($file, $folderPath. $fileNewName. ".". $ext);

				$this->layanan_model->insert_lampiran_layanan($data);
				if($aksi == "perbaiki"){
					redirect(site_url("mahasiswa/prestasi/surat-tugas-form/ajukan?id=".$this->input->post('id_lay')."&aksi=".$aksi."&status=sukses"));
				}else{
					redirect(site_url("mahasiswa/prestasi/surat-tugas-form/ajukan?id=".$this->input->post('id_lay')."&status=sukses"));
				}
			}
			else{
				if($aksi == "perbaiki" ){
					redirect(site_url("mahasiswa/prestasi/surat-tugas-form/ajukan?id=".$this->input->post('id_lay')."&aksi=".$aksi."&status=gagal"));
				}else{
					redirect(site_url("mahasiswa/prestasi/surat-tugas-form/ajukan?id=".$this->input->post('id_lay')."&status=gagal"));
				}
			}
		}
		else{
			if($aksi == "perbaiki"){
				redirect(site_url("mahasiswa/layanan-fakultas/$jenis/ajukan?id=".$this->input->post('id_lay')."&aksi=".$aksi."&status=gagal"));
			}else{
				redirect(site_url("mahasiswa/layanan-fakultas/$jenis/ajukan?id=".$this->input->post('id_lay')."&status=gagal"));
			}
		}
		
	}

	function prestasi_form_save()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);

		$file1 = file_get_contents($_FILES['file']['tmp_name']);
		$file1 = substr($file1,0,4);
		$size = $_FILES['file']['size'];

		$prestasi = array(
			"jenis" => $data['jenis'],
			"tingkat" => $data['tingkat'],
			"tahun" => $data['tahun'],
			"kegiatan" => $data['kegiatan'],
			"penyelenggara" => $data['penyelenggara'],
			"kategori" => $data['kategori'],
			"insert_by" => $this->session->userdata('userId'),   
		);
		
		if(!empty($_FILES)) {
			if($file1 == '%PDF' && $size <= 2100000){
				$file = $_FILES['file']['tmp_name']; 
				$sourceProperties = getimagesize($file);
				$str = $this->session->userdata('username').date('H:i:s');
				$hash_name = md5($str);
				$fileNewName = $hash_name;
				$folderPath = "assets/uploads/berkas-sertifikat/";
				$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

				$sertifikat = $folderPath.$fileNewName. ".". $ext;
				move_uploaded_file($file, $folderPath. $fileNewName. ".". $ext);

				$insert_id = $this->layanan_model->insert_prestasi($prestasi);

				//input anggota
				// if($data['kategori'] == "Tim"){
					$i = 0;
					foreach($data['anggota'] as $anggota){
						$anggota = array(
							"id_prestasi" => $insert_id,
							"anggota_npm" => $data['anggota'][$i],
							"capaian" => $data['capaian'],
							"sertifikat" => $sertifikat
						);
						$this->layanan_model->insert_prestasi_anggota($anggota);
						$i++;
					}
				// }
				redirect(site_url("mahasiswa/prestasi"));
			}
			else{
				redirect(site_url("mahasiswa/prestasi/form-prestasi?status=gagal"));
			}
		}
		else{
			redirect(site_url("mahasiswa/prestasi/form-prestasi?status=gagal"));
		}	
	}

	function prestasi_surat_tugas_hapus_berkas()
	{
		$id = $this->input->post('id_berkas');
		$id_layanan = $this->input->post('id_layanan');
		$file = $this->input->post('file_berkas');
		$id_get = $this->input->get('id');
		$aksi = $this->input->post('aksi');

		$jenis = $this->uri->segment(3); 
		$data = array("id" => $id);
		$this->layanan_model->delete_lampiran_layanan($data);
		unlink($file);
		if($aksi == "perbaiki"){
			redirect(site_url("mahasiswa/prestasi/surat-tugas-form/ajukan?id=$id_get&aksi=$aksi"));
		}else{
			redirect(site_url("mahasiswa/prestasi/surat-tugas-form/ajukan?id=$id_get"));
		}
	}

	function prestasi_surat_tugas_simpan()
	{
		// $data = $this->input->post();
		// echo "<pre>";
		// print_r($data);
		$id = $this->encrypt->decode($this->input->post('id_lay'));
		$aksi = $this->input->post('aksi');
		$seg = $this->uri->segment(3);
		if($aksi == "perbaiki"){
			$data_tolak = array(
				"status" => 0,
				"keterangan" => null
			);
			$this->layanan_model->update_layanan_fak_mhs($id,$data_tolak);
		}else{
			//input approver
			$data = $this->layanan_model->get_form_mhs_id($id);
			$layanan = $this->layanan_model->get_layanan_fakultas_by_id($data->id_layanan_fakultas);
			$this->layanan_model->insert_approver_mhs($id,$layanan->approver);
		}
		redirect(site_url("mahasiswa/prestasi"));
	}

	function prestasi_surat_tugas_delete()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);
		$id = $data['id_layanan'];

		//delete
		$this->layanan_model->delete_berkas_layanan($id);
		$this->layanan_model->delete_layanan_mhs($id);		
		//delete prestasi
		$this->layanan_model->delete_prestasi_by_layanan($id);

		redirect(site_url("mahasiswa/prestasi"));
	}

	function prestasi_surat_tugas_sertifikat()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		
		$this->load->view('header_global', $header);
		$this->load->view('mahasiswa/header');

		$this->load->view('mahasiswa/prestasi/surat_tugas_sertifikat');

        $this->load->view('footer_global');
	}

	function prestasi_surat_tugas_sertifikat_unggah()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);
		$file1 = file_get_contents($_FILES['file']['tmp_name']);
		$file1 = substr($file1,0,4);
		$size = $_FILES['file']['size'];
		$id = $this->encrypt->decode($data['id']);

		$kategori = $this->layanan_model->get_prestasi_by_id($id)->kategori;

		$prestasi = array(
			"jenis" => $data['jenis'],
			"tingkat" => $data['tingkat'],
			"tahun" => $data['tahun'],
			"kegiatan" => $data['kegiatan'],
			"penyelenggara" => $data['penyelenggara']
		);
		$anggota['capaian'] = $data['capaian'];
		
		if(!empty($_FILES)) {
			if($file1 == '%PDF' && $size <= 2100000){
				$file = $_FILES['file']['tmp_name']; 
				$sourceProperties = getimagesize($file);
				$str = $this->session->userdata('username').date('H:i:s');
				$hash_name = md5($str);
				$fileNewName = $hash_name;
				$folderPath = "assets/uploads/berkas-sertifikat/";
				$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

				$anggota['sertifikat'] = $folderPath.$fileNewName. ".". $ext;
				move_uploaded_file($file, $folderPath. $fileNewName. ".". $ext);

				//update prestasi
				$this->layanan_model->update_prestasi($id,$prestasi);		

				//update sertifikat
				if($kategori == "Individu"){
					$this->layanan_model->update_prestasi_anggota_individu($anggota,$id,$this->session->userdata('username'));
				}elseif($kategori = "Tim"){
					$this->layanan_model->update_prestasi_anggota_tim($anggota,$id);
				}

				redirect(site_url("mahasiswa/prestasi"));
			}
			else{
				redirect(site_url("mahasiswa/prestasi/form-prestasi?status=gagal&aksi=unggah&id=".$data['id']));
			}
		}
		else{
			redirect(site_url("mahasiswa/prestasi/form-prestasi?status=gagal&aksi=unggah&id=".$data['id']));
		}	
	}

	//Beasiswa
	function beasiswa()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['beasiswa'] = $this->layanan_model->get_beasiswa_mhs();

		$this->load->view('header_global', $header);
		$this->load->view('mahasiswa/header');
		$this->load->view('mahasiswa/beasiswa/beasiswa',$data);
        $this->load->view('footer_global');
	}

	function tambah_beasiswa()
	{
		$id = $this->uri->segment(3);
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['beasiswa'] = $this->layanan_model->get_beasiswa_by_id($id);	
		$data['layanan'] = $this->layanan_model->select_layanan_by_id(26); //form beasiswa lengkap
		$data['atribut'] = $this->layanan_model->select_layanan_atribut_by_id(26); // form beasiswa lengkap

		$idb = $this->input->get('id');
		$idb = $this->encrypt->decode($idb);

		if($this->input->get('aksi') == "ubah")
		{
			$bea = $this->layanan_model->select_beasiswa($idb);

			$data_bea = $bea[0];
		}
		else
		{
			$data_bea = array(
				"ipk" => null,
				"rekening" => null,
				"status_menikah" => null,
				"nama_ortu" => null,
				"pekerjaan_ortu" => null,
				"penghasilan_ortu" => null,
				"tanggungan_ortu" => null,
				"alamat_ortu" => null,
				"nama_saudara" => null,
				"beasiswa_saudara" => null,
				"fakultas_saudara" => null,
			);
		}

		$data['data_bea'] = $data_bea;

		$this->load->view('header_global', $header);
		$this->load->view('mahasiswa/header');
		$this->load->view('mahasiswa/beasiswa/form_beasiswa2',$data);
        $this->load->view('footer_global');
	}

	function simpan_beasiswa()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);
		$aksi = $data['aksi'];

		if($aksi == 'ubah'){
			$bea_mhs = $data['id_beasiswa_mhs'];
			$beasiswa = array(
				"ipk" => $data['ipk'],
				"rekening" => $data['no_rek'],
				"status_menikah" => $data['menikah'],
				"nama_ortu" => $data['ortu'],
				"pekerjaan_ortu" => $data['pekerjaan'],
				"penghasilan_ortu" => $data['penghasilan'],
				"tanggungan_ortu" => $data['tanggungan'],
				"alamat_ortu" => $data['alamat'],
				"nama_saudara" => $data['nama_kakak'],
				"beasiswa_saudara" => $data['jenis_kakak'],
				"fakultas_saudara" => $data['fakultas_kakak'],
				"ttd" => $data['ttd'],
			);
			//update beasiswa mahasiswa
			$this->layanan_model->update_beasiswa_mhs($bea_mhs,$beasiswa);

		}else{
			$beasiswa = array(
				"id_beasiswa" => $data['id_beasiswa'],
				"npm" => $this->session->userdata('username'),
				"ipk" => $data['ipk'],
				"rekening" => $data['no_rek'],
				"status_menikah" => $data['menikah'],
				"nama_ortu" => $data['ortu'],
				"pekerjaan_ortu" => $data['pekerjaan'],
				"penghasilan_ortu" => $data['penghasilan'],
				"tanggungan_ortu" => $data['tanggungan'],
				"alamat_ortu" => $data['alamat'],
				"nama_saudara" => $data['nama_kakak'],
				"beasiswa_saudara" => $data['jenis_kakak'],
				"fakultas_saudara" => $data['fakultas_kakak'],
				"ttd" => $data['ttd'],
			);
			//insert beasiswa mahasiswa
			$this->layanan_model->insert_beasiswa_mhs($beasiswa);
		}
		redirect(site_url("mahasiswa/beasiswa?status=sukses"));	
	}

	function simpan_beasiswa2()
	{
		
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);

		//insert layanan
		$npm = $this->session->userdata('username');
		$ttd = $data['ttd'];
		$approver = $data['approver'];
		$id_layanan = $data['id_layanan'];
		$data_layanan = array(
			"npm" => $npm,
			"id_layanan_fakultas" => $id_layanan,
			"ttd" => $ttd,
			"tingkat" => null
		);

		//input layanan fak mhs
		$insert_id = $this->layanan_model->insert_layanan_fak_mhs($data_layanan);

		$atribut_id = $data['id_attribut'];
		foreach($atribut_id as $atr)
		{
			$meta_val = $data[$atr];
			$data_atr = array(
				"id_layanan_fak_mhs" => $insert_id,
				"meta_key" => $atr,
				"meta_value" => $meta_val,
			);
			//input layanan_fakultas_mahasiswa_meta
			$this->layanan_model->insert_layanan_fak_mhs_meta($data_atr);
		}

		//input beasiswa
		$beasiswa = array(
			"id_beasiswa" => $data['id_beasiswa'],
			"id_layanan_fakultas_mahasiswa" => $insert_id,
			"npm" => $npm
		);
		$this->layanan_model->insert_beasiswa_mhs($beasiswa);
		redirect(site_url("mahasiswa/beasiswa?status=sukses"));	

	}

	function hapus_beasiswa()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);
		$file = $this->layanan_model->get_berkas_by_beasiswa_mhs($data['id_beasiswa']);
		foreach($file as $fl)
		{
			unlink($fl->file);
		}
		//hapus tbl beasiswa
		$this->layanan_model->delete_beasiswa_mhs($data['id_beasiswa']);

		redirect(site_url("/mahasiswa/beasiswa"));
	}

	function lampiran_beasiswa()
	{
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['beasiswa'] = $this->layanan_model->get_beasiswa_mhs_by_npm_bea($this->session->userdata('username'),$id);	
		$data['lampiran'] = $this->layanan_model->get_lampiran_beasiswa($id);	

		$this->load->view('header_global', $header);
		$this->load->view('mahasiswa/header');
		$this->load->view('mahasiswa/beasiswa/lampiran_beasiswa',$data);
        $this->load->view('footer_global');
	}

	function tambah_berkas_beasiswa()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);
		
		$file1 = file_get_contents($_FILES['file']['tmp_name']);
		$file1 = substr($file1,0,4);
		$size = $_FILES['file']['size'];
		$id = $this->encrypt->decode($this->input->post('id_beasiswa'));
		$data = array(
			'id_beasiswa_mhs' => $id,
			'nama_berkas' => $this->input->post('nama_berkas'),
			'jenis_berkas' => $this->input->post('jenis_berkas')
		);

		if(!empty($_FILES)) {
			if($file1 == '%PDF' && $size <= 1200000){
				$file = $_FILES['file']['tmp_name']; 
				$sourceProperties = getimagesize($file);
				$fileNewName = $this->session->userdata('username').$this->input->post('jenis_berkas').$id;
				$folderPath = "assets/uploads/berkas-beasiswa/";
				$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

				$data['file'] = $folderPath. $fileNewName. ".". $ext;
				move_uploaded_file($file, $folderPath. $fileNewName. ".". $ext);

				$this->layanan_model->insert_lampiran_beasiswa($data);
				redirect(site_url("mahasiswa/beasiswa/lampiran?id=".$this->input->post('id_beasiswa')."&status=sukses"));
			}
			else{
				redirect(site_url("mahasiswa/beasiswa/lampiran?id=".$this->input->post('id_beasiswa')."&status=gagal"));
			}
		}
		else{
			redirect(site_url("mahasiswa/beasiswa/lampiran?id=".$this->input->post('id_beasiswa')."&status=gagal"));
		}
	}

	function hapus_berkas_beasiswa()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);

		$ids = $this->input->post('id');
		$id = $this->input->post('id_beasiswa');
		$file = $this->input->post('file_berkas');
		
		$data = array("id" => $id);
		$this->layanan_model->delete_lampiran_beasiswa($data);
		unlink($file);
		redirect(site_url("mahasiswa/beasiswa/lampiran?id=".$ids));
	}

	function ajukan_beasiswa()
	{
		$id = $this->input->post('id_beasiswa');
		$beasiswa = array(
			"status" => 1
		);
		//update beasiswa mahasiswa
		$this->layanan_model->update_beasiswa_mhs($id,$beasiswa);
		redirect(site_url("mahasiswa/beasiswa"));
	}

	function form_ajukan_beasiswa()
	{
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);
		// echo $id;
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['lampiran'] = $this->layanan_model->get_lampiran_layanan($id);

		$this->load->view('header_global', $header);
		$this->load->view('mahasiswa/header');

		$this->load->view('mahasiswa/beasiswa/form_ajukan_beasiswa',$data);

        $this->load->view('footer_global');
	}

	function beasiswa_unggah()
	{
		// print_r($_POST);
		// print_r($_FILES);
		$aksi = $this->input->post('aksi');
		$jenis = $this->uri->segment(3);
		$file1 = file_get_contents($_FILES['file']['tmp_name']);
		$file1 = substr($file1,0,4);
		$size = $_FILES['file']['size'];

		$id = $this->encrypt->decode($this->input->post('id_lay'));
		$data = array(
			'id_layanan_fakultas_mahasiswa' => $id,
			'nama_berkas' => $this->input->post('nama_berkas'),
			'jenis_berkas' => $this->input->post('jenis_berkas'),
		);

		if(!empty($_FILES)) {
			if($file1 == '%PDF' && $size <= 2100000){
				$file = $_FILES['file']['tmp_name']; 
				$sourceProperties = getimagesize($file);
				$fileNewName = $this->session->userdata('username').$this->input->post('jenis_berkas').$id;
				$folderPath = "assets/uploads/berkas-beasiswa/";
				$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

				$data['file'] = $folderPath.$fileNewName. ".". $ext;
				move_uploaded_file($file, $folderPath. $fileNewName. ".". $ext);

				$this->layanan_model->insert_lampiran_layanan($data);
				if($aksi == "perbaiki"){
					redirect(site_url("mahasiswa/beasiswa/ajukan?id=".$this->input->post('id_lay')."&aksi=".$aksi."&status=sukses"));
				}else{
					redirect(site_url("mahasiswa/beasiswa/ajukan?id=".$this->input->post('id_lay')."&status=sukses"));
				}
			}
			else{
				if($aksi == "perbaiki" ){
					redirect(site_url("mahasiswa/beasiswa/ajukan?id=".$this->input->post('id_lay')."&aksi=".$aksi."&status=gagal"));
				}else{
					redirect(site_url("mahasiswa/beasiswa/ajukan?id=".$this->input->post('id_lay')."&status=gagal"));
				}
			}
		}
		else{
			if($aksi == "perbaiki"){
				redirect(site_url("mahasiswa/beasiswa/ajukan?id=".$this->input->post('id_lay')."&aksi=".$aksi."&status=gagal"));
			}else{
				redirect(site_url("mahasiswa/beasiswa/ajukan?id=".$this->input->post('id_lay')."&status=gagal"));
			}
		}
	}

	function beasiswa_hapus_berkas()
	{
		// $data = $this->input->post();
		// echo "<pre>";
		// print_r($data);

		$id = $this->input->post('id_berkas');
		$id_layanan = $this->input->post('id_layanan');
		$file = $this->input->post('file_berkas');
		$id_get = $this->input->get('id');
		$aksi = $this->input->post('aksi');

		$jenis = $this->uri->segment(3); 
		$data = array("id" => $id);
		$this->layanan_model->delete_lampiran_layanan($data);
		unlink($file);
		if($aksi == "perbaiki"){
			redirect(site_url("mahasiswa/beasiswa/ajukan?id=$id_get&aksi=$aksi"));
		}else{
			redirect(site_url("mahasiswa/beasiswa/ajukan?id=$id_get"));
		}
	}

	function beasiswa_simpan()
	{
		// $data = $this->input->post();
		// echo "<pre>";
		// print_r($data);
		$id = $this->encrypt->decode($this->input->post('id_lay'));
		$aksi = $this->input->post('aksi');
		$seg = $this->uri->segment(3);
		if($aksi == "perbaiki"){
			$data_tolak = array(
				"status" => 0,
				"keterangan" => null
			);
			$this->layanan_model->update_layanan_fak_mhs($id,$data_tolak);
		}else{
			//input approver
			$data = $this->layanan_model->get_form_mhs_id($id);
			$layanan = $this->layanan_model->get_layanan_fakultas_by_id($data->id_layanan_fakultas);
			$this->layanan_model->insert_approver_mhs($id,$layanan->approver);
		}

		//beasiswa
		$beasiswa = $this->layanan_model->get_beasiswa_by_layanan($id);
		$beasiswa_dt = array(
			"status" => 1
		);
		//update beasiswa mahasiswa
		$this->layanan_model->update_beasiswa_mhs($beasiswa->id,$beasiswa_dt);

		redirect(site_url("mahasiswa/beasiswa"));
	}

	function struktur_organisasi()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['lk'] = $this->layanan_model->get_lk_mahasiswa2($this->session->userdata('userId'));
		$this->load->view('header_global', $header);
		$this->load->view('mahasiswa/header');

		$this->load->view('mahasiswa/lk/struktur',$data);

        $this->load->view('footer_global');
	}

	function proposal_kegiatan()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['proposal'] = $this->layanan_model->get_proposal_mahasiswa($this->session->userdata('userId'));

		$this->load->view('header_global', $header);
		$this->load->view('mahasiswa/header');

		$this->load->view('mahasiswa/lk/proposal',$data);

        $this->load->view('footer_global');
	}

	function proposal_kegiatan_form()
	{
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		
		if($this->input->get('aksi') == "ubah")
		{
			$prp = $this->layanan_model->select_proposal($id);
			$data_prp = $prp[0];
		}
		else
		{
			$data_prp = array(
				"id" => null,
				"id_lk" => null,
				"periode" => null,
				"tahun" => null,
				"nama" => null,
				"nilai" => null,
			);
		}

		$data['data_prp'] = $data_prp;


		$this->load->view('header_global', $header);
		$this->load->view('mahasiswa/header');

		$this->load->view('mahasiswa/lk/proposal_form',$data);

        $this->load->view('footer_global');
	}

	function simpan_proposal()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);
		$nilai = str_replace(",","",$data['usulan']);	 
		$prop = array(
			"id_lk" => $data['lk'],
			"periode" => $data['ta'],
			"tahun" => $data['tahun'],
			"nama" => $data['nama'],
			"nilai" => $nilai,
			"insert_by" => $this->session->userdata('username')
		);

		if($data['aksi'] == 'ubah'){
			//update
			$this->layanan_model->update_lk_proposal($data['id_proposal'],$prop);
		}else{
			//input
			$this->layanan_model->insert_lk_proposal($prop);
		}
		redirect(site_url("mahasiswa/proposal-kegiatan"));
	}

	function proposal_kegiatan_lampiran()
	{
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);
		$data['lampiran'] = $this->layanan_model->get_lampiran_proposal($id);
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$this->load->view('header_global', $header);
		$this->load->view('mahasiswa/header');

		$this->load->view('mahasiswa/lk/proposal_form_lampiran',$data);

        $this->load->view('footer_global');
	}

	function tambah_berkas_proposal()
	{
		// $data = $this->input->post();
		// echo "<pre>";
		// print_r($data);
		$file1 = file_get_contents($_FILES['file']['tmp_name']);
		$file1 = substr($file1,0,4);
		$size = $_FILES['file']['size'];

		$id = $this->encrypt->decode($this->input->post('id'));
		$data = array(
			'proposal_id' => $id,
			'nama_berkas' => $this->input->post('nama_berkas'),
			'jenis_berkas' => $this->input->post('jenis_berkas'),
		);

		if(!empty($_FILES)) {
			if($file1 == '%PDF'){
				$file = $_FILES['file']['tmp_name']; 
				$sourceProperties = getimagesize($file);
				$fileNewName = md5($this->session->userdata('username').$this->input->post('jenis_berkas').$id.date("H:i:s"));
				$folderPath = "assets/uploads/proposal-lk/";
				$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
				$data['file'] = $folderPath.$fileNewName. ".". $ext;
				move_uploaded_file($file, $folderPath. $fileNewName. ".". $ext);

				$this->layanan_model->insert_lampiran_proposal($data);
				redirect(site_url("mahasiswa/proposal-kegiatan/lampiran?id=".$this->input->post('id')."&status=sukses"));
			}
			else{
				redirect(site_url("mahasiswa/proposal-kegiatan/lampiran?id=".$this->input->post('id')."&status=gagal"));
			}
		}
		else{
			redirect(site_url("mahasiswa/proposal-kegiatan/lampiran?id=".$this->input->post('id')."&status=gagal"));
		}
	}
	
	function hapus_berkas_proposal()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);
		$id = $this->input->post('id_berkas');
		$id_prop = $this->input->post('id_prop');
		$id_get = $this->input->post('id');
		$file = $this->input->post('file_berkas');
		
		$data = array("id" => $id);
		$this->layanan_model->delete_lampiran_proposal($data);
		unlink($file);
	    
	    redirect(site_url("mahasiswa/proposal-kegiatan/lampiran?id=".$id_get));
	}

	function proposal_aksi()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);
		$aksi = $data['aksi'];
		$id = $data['id'];
		if($aksi == "hapus"){
			//berkas
			$file = $this->layanan_model->get_lk_proposal_berkas($id);
			foreach($file as $fl){
				unlink($fl->file);
			}
			//row
			$dt = array(
				"id" => $id
			);
			$this->layanan_model->delete_lk_proposal($dt);
		}else{
			$dt = array(
				"status" => 1,
				"keterangan" => null
			);
			// $status = 1;
			$this->layanan_model->update_lk_proposal($id,$dt);
		}
		redirect(site_url("mahasiswa/proposal-kegiatan"));
	}

	function laporan_kegiatan()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['proposal'] = $this->layanan_model->get_proposal_mahasiswa2($this->session->userdata('userId'));

		$this->load->view('header_global', $header);
		$this->load->view('mahasiswa/header');

		$this->load->view('mahasiswa/lk/laporan_kegiatan',$data);

        $this->load->view('footer_global');
	}

	function laporan_kegiatan_simpan()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);
		$dt = array(
			"status" => 3,
			"laporan" => $data['link']
		);
		$id = $data['id'];
		// $status = 1;
		$this->layanan_model->update_lk_proposal($id,$dt);
		redirect(site_url("mahasiswa/laporan-kegiatan"));
	}

}
