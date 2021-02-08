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
		$this->load->model('pkl_model');
		$this->load->model('layanan_model');
		$this->load->library('pdf');
		$this->load->library('encrypt');
		 // Load PHPMailer library
        // $this->load->library('phpmailer_lib');

		
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
		redirect(site_url("dosen/kelola-akun?status=sukses"));
	}

	public function biodata()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['biodata'] = $this->user_model->select_biodata_by_ID($this->session->userdata('userId'), 2)->row();

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');
		
		$this->load->view('dosen/biodata_dosen', $data);

        $this->load->view('footer_global');
	}
	//edit raihan
	public function ubah_biodata()
	{
		//echo "<pre>";
		//print_r($_POST);
		//echo $this->session->userdata('userId');
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);
		$data_dosen = array(
			'nidn' => $this->input->post('nidn'),
			'gelar_depan' => $this->input->post('gelar_depan'),
			'gelar_belakang' => $this->input->post('gelar_belakang'),
			'id_sinta' => $this->input->post('id_sinta'),
			'jurusan' => $this->input->post('jurusan'),
			'pangkat_gol' => $this->input->post('pangkat'),
			'fungsional' => $this->input->post('jabfung')
		);

		$this->user_model->update_dosen($data_dosen, $this->session->userdata('userId'));

		// $tgl_lahir = new DateTime($this->input->post('tanggal_lahir'));

		$data_akun = array(
			'jenis_kelamin' => $this->input->post('jenkel'),
			'agama' => $this->input->post('agama'),
			'tempat_lahir' => $this->input->post('tempat_lahir'),
			'tanggal_lahir' => $this->input->post('tanggal_lahir'),
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

	function tugas_tambahan()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);

		
		$iduser = $data['iduser'];
		$tugas = $data['tugas_tambahan'];
		$prodi = $data['prodi'];
		$lab = $data['lab'];
		$jurusan = $data['jurusan'];
		$periode = $data['periode'];
		$status = $data['status_tgs'];
		// echo $tugas;
		if($jurusan == ""){
			$jurusan = 0;
		}
		if($prodi == ""){
			$prodi = 0;
		}
		if($lab == ""){
			$lab = 0;
		}

		if($tugas == 16 || $tugas == 15)
		{
			$jur_unit = $lab;
		}
		elseif($tugas == 14)
		{
			$jur_prodi = $this->jurusan_model->get_prodi_id($prodi);
			$jur_unit = $jur_prodi->jurusan;
		}
		else{
			$jur_unit = $jurusan;
		}

		$check = $this->user_model->check_tugas_tambahan($iduser,$tugas,$jur_unit,$prodi,$status);
		// $tugas_double = array("16", "18", "11");
		if(!empty($check)){
			redirect(site_url("dosen/kelola-biodata?status=duplikat"));
		}
		else{
			if($tugas != 16 && $tugas != 18 && $tugas != 11){
				
				$check_double =  $this->user_model->check_tugas_tambahan_duplikat($tugas,$jur_unit,$prodi,$status,$periode);
				// $check_double =  $this->user_model->check_tugas_tambahan_duplikat($tugas,$jurusan,$prodi,$status,$periode);
				if(!empty($check_double)){
					$id_user_double = $check_double->id_user;
					redirect(site_url("dosen/kelola-biodata?status=duplikat_user&id=".$this->encrypt->encode($id_user_double)));
					// echo "1";
				}
				else{
						$data_tugas = array(
							'id_user' => $iduser,
							'tugas' => $tugas,
							'jurusan_unit' => $jur_unit,
							'prodi' => $prodi,
							'periode' => $periode,
							'aktif' => $status,
						);			
					$this->user_model->insert_tugas_tambah($data_tugas);
					// echo "2";
				}
			}
			else{
				// echo "3";
				$data_tugas = array(
					'id_user' => $iduser,
					'tugas' => $tugas,
					'jurusan_unit' => $jur_unit,
					'prodi' => $prodi,
					'periode' => $periode,
					'aktif' => $status,
				);		
	
			$this->user_model->insert_tugas_tambah($data_tugas);		
			}
			redirect(site_url("dosen/kelola-biodata?status=sukses"));
		}
	}

	function tugas_tambahan_nonaktif()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);

		$id = $data['id_tugas'];
		$ket = $data['ket'];

		$this->user_model->update_tugas_tambahan($id,$ket);	
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
		// $id = $this->encrypt->decode($id);
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
		$alter = $this->ta_model->get_komisi_alter_id($id);		

		$pb1 = $data['Pembimbing_1'];
		$pb2 = $data['Pembimbing_2'];
		$pb3 = $data['Pembimbing_3'];
		$ps1 = $data['Penguji_1'];
		$ps2 = $data['Penguji_2'];
		$ps3 = $data['Penguji_3'];
	

		$dosenid = $this->session->userdata('userId');

		$status = "kajur";
		
		//send email
// 		if(!empty($alter)){
// 			$config = Array(  
// 				'protocol' => 'smtp',  
// 				'smtp_host' => 'ssl://smtp.googlemail.com',  
// 				'smtp_port' => 465,  
// 				'smtp_user' => 'apps.fmipa.unila@gmail.com',   
// 				'smtp_pass' => 'apps_fmipa 2020',   
// 				'mailtype' => 'html',   
// 				'charset' => 'iso-8859-1'  
// 			);  
// 			$jml_email = count($alter);
// 			$n = 0;
// 			foreach($alter as $row){
// 				$this->load->library('email', $config);
// 				$this->email->set_newline("\r\n");  
// 				$this->email->from('apps.fmipa.unila@gmail.com', 'SIMIPA');   
// 				$this->email->to($row->email);//$row->email   
// 				$this->email->subject('Approve Tema Penelitian Fakultas Matematika dan Ilmu Pengetahuan Alam');   
// 				$this->email->message("
// 				Kepada Yth. $row->nama
// 				<br>
// 				Untuk Melakukan Approval Tema Penelitian Mahasiswa Fakultas Matematika Dan Ilmu Pengetahuan Alam Sebagai $row->status Silahkan Klik Link Berikut :<br>
// 				http://apps.fmipa.unila.ac.id/simipa/approval/ta?token=$row->token
// 				<br><br>
// 				Terimakasih.
				
// 				");
// 				if (!$this->email->send()) {  
// 					    $n = 0;
// 				   }else{  
// 					  $n++;
// 				}   
// 			}
            
// 		}
// 		else{
// 		    $jml_email = 0;
// 			$n = 0;
// 		}
		// check apakah email terkirim semua
// 		if($jml_email == $n)
// 		{
		    $this->ta_model->approve_ta($id,$ttd,$status,$dosenid);
		    redirect(site_url("dosen/struktural/tema"));
// 		}
// 		else{
// 		    redirect(site_url("dosen/struktural/tema?status=error"));
// 		}
		
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

        $jenis = $this->ta_model->get_ta_by_id($id)->jenis;
		// echo "<pre>";
		// print_r($id);
		
		// $data = array("id_pengajuan" => $id);
		// $where = $data['id_pengajuan'];

		$this->ta_model->decline_ta($id,$dosenid,$status,$keterangan);
		if($jenis != 'Skripsi'){
		    redirect(site_url("dosen/struktural/kaprodi/tugas-akhir"));
		}
		else{
		    redirect(site_url("dosen/tugas-akhir/tema/koordinator"));    
		}
		
	}

	function tugas_akhir_aksi()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();

		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);

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
		$id = $this->encrypt->decode($id);
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
		$id = $this->encrypt->decode($id);

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
		$id = $this->encrypt->decode($id);
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
		$jenis = $data['jenis'];

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
		$pb2_email = $data['pb2_alter_email'];
		$pb3_nip = $data['pb3_alter_nip'];
		$pb3_nama = $data['pb3_alter_nama'];
		$pb3_email = $data['pb3_alter_email'];
		$ps1_nip = $data['ps1_alter_nip'];
		$ps1_nama = $data['ps1_alter_nama'];
		$ps1_email = $data['ps1_alter_email'];
		$ps2_nip = $data['ps2_alter_nip'];
		$ps2_nama = $data['ps2_alter_nama'];
		$ps2_email = $data['ps2_alter_email'];
		$ps3_nip = $data['ps3_alter_nip'];
		$ps3_nama = $data['ps3_alter_nama'];
		$ps3_email = $data['ps3_alter_email'];

        //send email
        

		if($pb2 == NULL && ($pb2_nip != NULL && $pb2_nama != NULL)){
			$status = "Pembimbing 2";
			$this->ta_model->set_komisi_alter($id,$pb2_nip,$pb2_nama,$status);
			$this->ta_model->set_komisi_alter_access($id,$pb2_nip,$pb2_nama,$status,$pb2_email);

			$data_approval = array(
				'id_pengajuan' => $id,
				'status_slug' => $status,
				'id_user' => '0',
				'ttd' => '',	
			);
			$this->ta_model->insert_approval_ta($data_approval);
		}
		if($pb3 == NULL && ($pb3_nip != NULL && $pb3_nama != NULL)){
			$status = "Pembimbing 3";
			$this->ta_model->set_komisi_alter($id,$pb3_nip,$pb3_nama,$status);
			$this->ta_model->set_komisi_alter_access($id,$pb3_nip,$pb3_nama,$status,$pb3_email);

			$data_approval = array(
				'id_pengajuan' => $id,
				'status_slug' => $status,
				'id_user' => '0',
				'ttd' => '',	
			);
			$this->ta_model->insert_approval_ta($data_approval);
		}
		if($ps1 == NULL && ($ps1_nip != NULL && $ps1_nama != NULL)){
			$status = "Penguji 1";
			$this->ta_model->set_komisi_alter($id,$ps1_nip,$ps1_nama,$status);
			$this->ta_model->set_komisi_alter_access($id,$ps1_nip,$ps1_nama,$status,$ps1_email);

			$data_approval = array(
				'id_pengajuan' => $id,
				'status_slug' => $status,
				'id_user' => '0',
				'ttd' => '',	
			);
			$this->ta_model->insert_approval_ta($data_approval);

		}
		if($ps2 == NULL && ($ps2_nip != NULL && $ps2_nama != NULL)){
			$status = "Penguji 2";
			$this->ta_model->set_komisi_alter($id,$ps2_nip,$ps2_nama,$status);
			$this->ta_model->set_komisi_alter_access($id,$ps2_nip,$ps2_nama,$status,$ps2_email);

			$data_approval = array(
				'id_pengajuan' => $id,
				'status_slug' => $status,
				'id_user' => '0',
				'ttd' => '',	
			);
			$this->ta_model->insert_approval_ta($data_approval);
		}
		if($ps3 == NULL && ($ps3_nip != NULL && $ps3_nama != NULL)){
			$status = "Penguji 3";
			$this->ta_model->set_komisi_alter($id,$ps3_nip,$ps3_nama,$status);
			$this->ta_model->set_komisi_alter_access($id,$ps3_nip,$ps3_nama,$status,$ps3_email);

			$data_approval = array(
				'id_pengajuan' => $id,
				'status_slug' => $status,
				'id_user' => '0',
				'ttd' => '',	
			);
			$this->ta_model->insert_approval_ta($data_approval);
		}
		
		$alter = $this->ta_model->get_komisi_alter_id($id);
		
		//send email
		if(!empty($alter)){
			$config = Array(  
				'protocol' => 'smtp',  
				'smtp_host' => 'ssl://smtp.googlemail.com',  
				'smtp_port' => 465,  
				'smtp_user' => 'apps.fmipa.unila@gmail.com',   
				'smtp_pass' => 'apps_fmipa 2020',   
				'mailtype' => 'html',   
				'charset' => 'iso-8859-1'  
			);  
			$jml_email = count($alter);
			$n = 0;
			foreach($alter as $row){
				$this->load->library('email', $config);
				$this->email->set_newline("\r\n");  
				$this->email->from('apps.fmipa.unila@gmail.com', 'SIMIPA');   
				$this->email->to($row->email);//$row->email   
				$this->email->subject('Approve Tema Penelitian Fakultas Matematika dan Ilmu Pengetahuan Alam');   
				$this->email->message("
				Kepada Yth. $row->nama
				<br>
				Untuk Melakukan Approval Tema Penelitian Mahasiswa Fakultas Matematika Dan Ilmu Pengetahuan Alam Sebagai $row->status Silahkan Klik Link Berikut :<br>
				http://apps.fmipa.unila.ac.id/simipa/approval/ta?token=$row->token
				<br><br>
				Terimakasih.
				
				");
				if (!$this->email->send()) { 
					    $n = 0;
				   }else{  
					  $n++;
				}   
			}
            
		}
		else{
		    $jml_email = 0;
			$n = 0;
		}
		
		// check apakah email terkirim semua
		if($jml_email == $n)
		{
    		 if($jenis == "Tugas Akhir"){
    			$this->ta_model->approval_koordinator_ta($id,$ttd,$dosenid,$no_penetapan,$judul_approve,$judul1,$judul2);
    			$this->ta_model->set_komisi_ta($id,$pb1,$ps1,$ps2);
    
    			if($ps1 != NULL && $ps1 != '0'){
    	
    				$data_approval = array(
    					'id_pengajuan' => $id,
    					'status_slug' => 'Penguji 1',
    					'id_user' => $ps1,
    					'ttd' => '',	
    				);
    	
    				$this->ta_model->insert_approval_ta($data_approval);
    			}
    
    			// $this->ta_model->approve_ta_kaprodi($id);
    			$data_approval = array(
    				'id_pengajuan' => $id,
    				'status_slug' => "Ketua Program Studi",
    				'id_user' => $dosenid,
    				'ttd' => $ttd,
    			);
    			$this->ta_model->insert_approve_ta_kaprodi($data_approval);
    		}
    
    		else{
    			$this->ta_model->approval_koordinator($id,$ttd,$dosenid,$no_penetapan,$judul_approve,$judul1,$judul2);
    			$this->ta_model->set_komisi($id,$pb1,$pb2,$pb3,$ps1,$ps2,$ps3);
    
    			if($jenis != 'Skripsi'){
    				$data_approval = array(
    					'id_pengajuan' => $id,
    					'status_slug' => "Ketua Program Studi",
    					'id_user' => $dosenid,
    					'ttd' => $ttd,
    				);
    				$this->ta_model->insert_approve_ta_kaprodi($data_approval);
    			}
    
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
    	
    		}
    		if($jenis != 'Skripsi'){
    		     redirect(site_url("dosen/struktural/kaprodi/tugas-akhir"));
    		}
    		else{
    		    redirect(site_url("dosen/tugas-akhir/tema/koordinator"));
    		}
		}
		else{
		    $this->ta_model->update_pbb_alternatif_ta($id);
		    
		    if($jenis != 'Skripsi'){
    		     redirect(site_url("dosen/struktural/kaprodi/tugas-akhir?status=error"));
    		}
    		else{
    		    redirect(site_url("dosen/tugas-akhir/tema/koordinator?status=error"));
    		}
		}
	
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
			$id_ta = $data['id_ta'];

			$komisi = $this->ta_model->get_komisi_alter_seminar_id($id_ta);
			if(!empty($komisi)){
				foreach($komisi as $kom)
				{
					$keys = "raihanbagasnugraha";
					$date = date("Y-m-d H:i:s");
					$token = md5($keys.$kom->nip_nik.$kom->status.$kom->nama.$date);

					$data_approval = array(
						'id_seminar' => $id,
						'status' => $kom->status,
						'nip_nik' => $kom->nip_nik,
						'nama' => $kom->nama,
						'email' => $kom->email,	
						'token' => $token	
					);
					$this->ta_model->insert_seminar_approval_alter($data_approval);
				}
			}

			redirect(site_url("dosen/tugas-akhir/seminar/koordinator"));
		}

		elseif($status == 'Kaprodi'){
			$id_ta = $data['id_ta'];

			$komisi = $this->ta_model->get_komisi_alter_seminar_id($id_ta);

			if(!empty($komisi)){
				foreach($komisi as $kom)
				{
					$keys = "raihanbagasnugraha";
					$date = date("Y-m-d H:i:s");
					$token = md5($keys.$kom->nip_nik.$kom->status.$kom->nama.$date);

					$data_approval = array(
						'id_seminar' => $id,
						'status' => $kom->status,
						'nip_nik' => $kom->nip_nik,
						'nama' => $kom->nama,
						'email' => $kom->email,	
						'token' => $token	
					);
					$this->ta_model->insert_seminar_approval_alter($data_approval);
				}
			}

			redirect(site_url("dosen/struktural/kaprodi/seminar-sidang"));
		}

		elseif($status == 'kajur'){

		$alter = $this->ta_model->get_komisi_seminar_alter_id($id);

		//send email
		if(!empty($alter)){
			$config = Array(  
				'protocol' => 'smtp',  
				'smtp_host' => 'ssl://smtp.googlemail.com',  
				'smtp_port' => 465,  
				'smtp_user' => 'apps.fmipa.unila@gmail.com',   
				'smtp_pass' => 'apps_fmipa 2020',   
				'mailtype' => 'html',   
				'charset' => 'iso-8859-1'  
			);  
			$jml_email = count($alter);
			$n = 0;
			foreach($alter as $row){
					$this->load->library('email', $config);
					$this->email->set_newline("\r\n");  
					$this->email->from('apps.fmipa.unila@gmail.com', 'SIMIPA');   
					$this->email->to($row->email);   
					$this->email->subject('Penilaian Seminar/Sidang Fakultas Matematika dan Ilmu Pengetahuan Alam');   
					$this->email->message("
					Kepada Yth. $row->nama
					<br>
					Untuk Melakukan Penilaian Seminar/Sidang Mahasiswa Fakultas Matematika Dan Ilmu Pengetahuan Alam Sebagai $row->status Silahkan Klik Link Berikut :<br>
					http://apps.fmipa.unila.ac.id/simipa/approval/seminar?token=$row->token
					<br><br>
					Terimakasih.
					
					");
					if (!$this->email->send()) {  
						$n = 0;   
					}else{  
						$n++;
					}   
				}
			}
			else{
    		    $jml_email = 0;
    			$n = 0;
		    }
		    // check apakah email terkirim semua
    		if($jml_email == $n)
    		{
    		    $data = $this->ta_model->get_komisi_seminar_id($id);

    			foreach($data as $row){
    				$data_cek = array(
    					'status' => $row->status,
    					'saran' => '',
    					'ket' => '0',
    					'id_seminar' => $id,	
    				);
    				$this->ta_model->insert_seminar_nilai_check($data_cek);
    			}
    		    redirect(site_url("dosen/struktural/seminar"));
    		}
    		else{
    		    redirect(site_url("dosen/struktural/seminar?status=gagal"));
    		}
		

			
		}
	
	    else{
	        redirect(site_url("dosen/tugas-akhir/seminar"));
	    }
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
		$id_ta = $this->ta_model->get_seminar_id($id)->id_tugas_akhir; 
        $jenis = $this->ta_model->get_ta_by_id($id_ta)->jenis; 
		if($status == 'koor'){
		    if($jenis != 'Skripsi'){
		        redirect(site_url("dosen/struktural/kaprodi/seminar-sidang"));
		    }
		    else{
		        redirect(site_url("dosen/tugas-akhir/seminar/koordinator"));
		    }
		    
		
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
		$id = $this->encrypt->decode($id);
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
		$id = $this->encrypt->decode($id);
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

	//nilai seminar dosen
	function nilai_seminar()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['seminar'] = $this->ta_model->get_nilai_seminar($this->session->userdata('userId'));
		// print_r($data);
		// $jml = count($data);

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/seminar/nilai/nilai_seminar',$data);
		
		$this->load->view('footer_global');
	}

	function nilai_seminar_add()
	{
		$id = $this->input->get('id');
		$status = $this->input->get('status');

		switch($status){
			case "pb1":
			$status = "Pembimbing Utama";
			break;
			case "pb2":
			$status = "Pembimbing 2";
			break;
			case "pb3":
			$status = "Pembimbing 3";
			break;
			case "ps1":
			$status = "Penguji 1";
			break;
			case "ps2":
			$status = "Penguji 2";
			break;
			case "ps3":
			$status = "Penguji 3";
			break;
		}

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['seminar'] = $this->ta_model->get_seminar_id($id);
		$data['ta'] = $this->ta_model->get_tugas_akhir_seminar_id($id);
		$data['status'] = $status;

		$meta = $this->ta_model->get_komponen_nilai_meta($data['ta']->npm,$data['ta']->jenis,$data['seminar']->jenis);

		if(!empty($meta)){
			$this->load->view('header_global', $header);
			$this->load->view('dosen/header');

			$this->load->view('dosen/seminar/nilai/add_nilai_seminar',$data);
			
			$this->load->view('footer_global');
		}
		else{
			redirect(site_url("dosen/tugas-akhir/nilai-seminar?status=null"));	
		}

		// print_r($data);
		// $jml = count($data);

	}


	// rekap koordinator
	function rekap_ta()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		// $data['ta'] = $this->ta_model->get_ta_rekap($this->session->userdata('userId'));
		// print_r($data);
		// $jml = count($data);

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/koordinator/rekap/rekap_ta_koor');
		
		$this->load->view('footer_global');
	}

	function rekap_ta_detail()
	{
		$detail = $this->input->get('detail');
		$jenis = $this->input->get('jenis');
		$angkatan = $this->input->get('angkatan');

		switch($jenis)
		{
			case "ta":
			$jenis = 'Tugas Akhir';
			$npm1 = $npm2 = "0";
			break;
			case "skripsi":
			$jenis = 'Skripsi';
			$npm1 = "1";
			$npm2 = "5";
			break;
			case "tesis":
			$jenis = 'Tesis';
			$npm1 = $npm2 = "2";
			break;
			case "disertasi":
			$jenis = 'Disertasi';
			$npm1 = $npm2 = "3";
			break;
		}

		if($detail == "diterima"){
			$data['ta'] = $this->ta_model->get_ta_rekap_detail_terima($this->session->userdata('userId'),$angkatan,$jenis,$npm1,$npm2);
		}
		else{
			$data['ta'] = $this->ta_model->get_ta_rekap_detail_tolak($this->session->userdata('userId'),$angkatan,$jenis,$npm1,$npm2);
		}

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		
		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/koordinator/rekap/rekap_ta_koor_detail',$data);
		
		$this->load->view('footer_global');
	}

	function rekap_seminar_koor()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		// $data['seminar'] = $this->ta_model->get_seminar_rekap_koor($this->session->userdata('userId'));
		// print_r($data);
		// $jml = count($data);

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/koordinator/rekap/rekap_seminar_koor');
		
		$this->load->view('footer_global');
	}

	function rekap_seminar_koor_detail()
	{
		$seminar = $this->input->get('seminar');
		$jenis = $this->input->get('jenis');
		$angkatan = $this->input->get('angkatan');

		switch($jenis)
		{
			case "ta":
			$jenis = 'Tugas Akhir';
			$npm1 = $npm2 = "0";
			break;
			case "skripsi":
			$jenis = 'Skripsi';
			$npm1 = "1";
			$npm2 = "5";
			break;
			case "tesis":
			$jenis = 'Tesis';
			$npm1 = $npm2 = "2";
			break;
			case "disertasi":
			$jenis = 'Disertasi';
			$npm1 = $npm2 = "3";
			break;
		}

		switch($seminar)
		{
			case "ta":
			$seminar = 'Seminar Tugas Akhir';
			break;
			case "usul":
			$seminar = 'Seminar Usul';
			break;
			case "hasil":
			$seminar = 'Seminar Hasil';
			break;
			case "kompre":
			$seminar = 'Sidang Komprehensif';
			break;
		}

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$data['seminar'] = $this->ta_model->get_seminar_rekap_koor_detail($this->session->userdata('userId'),$angkatan,$npm1,$npm2,$seminar,$jenis);

		$this->load->view('dosen/koordinator/rekap/rekap_seminar_koor_detail',$data);
		
		$this->load->view('footer_global');
	}

	function rekap_mahasiswa_ta()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		// $data['seminar'] = $this->ta_model->get_seminar_rekap_koor($this->session->userdata('userId'));
		// print_r($data);
		// $jml = count($data);

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/koordinator/rekap/rekap_mahasiswa_ta');
		
		$this->load->view('footer_global');
	}

	function rekap_mahasiswa_ta_detail()
	{
		$detail = $this->input->get('detail');
		$strata = $this->input->get('strata');
		$angkatan = $this->input->get('angkatan');

		switch($strata)
		{
			case "d3":
			$npm1 = $npm2 = "0";
			break;
			case "s1":
			$npm1 = "1";
			$npm2 = "5";
			break;
			case "s2":
			$npm1 = $npm2 = "2";
			break;
			case "s3":
			$npm1 = $npm2 = "3";
			break;
		}
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');
		if($detail == 'mahasiswa'){
			$data['mhs'] = $this->ta_model->get_mahasiswa_ta_rekap_mahasiswa_detail($this->session->userdata('userId'),$angkatan,$npm1,$npm2);
			$this->load->view('dosen/koordinator/rekap/rekap_mahasiswa_ta_mahasiswa',$data);
		}
		elseif($detail == 'ta'){
		    if($strata == 'd3'){
		        $data['ta'] = $this->ta_model->get_mahasiswa_ta_rekap_ta_detail_d3($this->session->userdata('userId'),$angkatan,$npm1,$npm2);
		    }
		    else{
		        $data['ta'] = $this->ta_model->get_mahasiswa_ta_rekap_ta_detail($this->session->userdata('userId'),$angkatan,$npm1,$npm2);
		    }
		    $this->load->view('dosen/koordinator/rekap/rekap_mahasiswa_ta_ta',$data);
		
		}
		elseif($detail == 'lulus'){
		    if($strata == 'd3'){
		        $data['lulus'] = $this->ta_model->get_mahasiswa_ta_rekap_lulus_detail_d3($this->session->userdata('userId'),$angkatan,$npm1,$npm2);
		    }
		    else{
		        $data['lulus'] = $this->ta_model->get_mahasiswa_ta_rekap_lulus_detail($this->session->userdata('userId'),$angkatan,$npm1,$npm2);
		    }
			
			$this->load->view('dosen/koordinator/rekap/rekap_mahasiswa_ta_lulus',$data);
		}
		$this->load->view('footer_global');
	}

	function rekap_bimbingan_dosen()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['dosen'] = $this->ta_model->get_bimbingan_dosen($this->session->userdata('userId'));

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/koordinator/rekap/rekap_bimbingan_dosen',$data);
		
		$this->load->view('footer_global');
	}

	function rekap_bimbingan_dosen_detail()
	{
		$id_user = $this->input->get('dosen');
		$id_user = $this->encrypt->decode($id_user);
		$jenis = $this->input->get('jenis');
		$strata = $this->input->get('strata');

		switch($strata)
		{
			case "d3":
			$npm1 = $npm2 = "0";
			break;
			case "s1":
			$npm1 = "1";
			$npm2 = "5";
			break;
			case "s2":
			$npm1 = $npm2 = "2";
			break;
			case "s3":
			$npm1 = $npm2 = "3";
			break;
		}
		switch($jenis)
		{
			case "pb1":
			$status = 'Pembimbing Utama';
			break;
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

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['bimbingan'] = $this->ta_model->get_bimbingan_dosen_detail($id_user,$status,$npm1,$npm2);
		$data['id_dosen'] = $id_user;

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/koordinator/rekap/rekap_bimbingan_dosen_detail',$data);
		
		$this->load->view('footer_global');
	}

	function rekap_ganti_pbb()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);
		$id_ta = $data['id_pengajuan'];

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$data['ta'] = $this->ta_model->get_ta_id_ganti_pbb($id_ta);

		$this->load->view('dosen/koordinator/rekap/rekap_ta_koor_detail_ganti_pbb',$data);

		$this->load->view('footer_global');
	}

	function rekap_ganti_ta()
	{
		$data = $this->input->post();
		$seg = $this->uri->segment(2);
		// echo "<pre>";
		// print_r($data);
		$id = $data['id_ta'];
		$ket = $data['keterangan'];

		$this->ta_model->rekap_ganti_ta($id,$ket);
		redirect(site_url("/dosen/$seg/rekap/tugas-akhir"));
	}

	function rekap_ganti_pbb_save()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);

		$no = $data['no_penetapan'];
		$nomor = $data['nomor'];
		$jenis = $data['jenis'];

		$dosenid = $this->session->userdata('userId');
		$no_penetapan = $no.$nomor;

		$npm = $data['npm'];

		$id_old = $data['id_pengajuan'];
		$ttd = $data['ttd'];
		$judul_approve = $data['judul'];
		$judul1 = $data['judul1'];
		$judul2 = $data['judul2'];

		$pb1_old = $data['pb1_old'];

		$pb1 = $data['pembimbing1'];
		$pb2 = $data['pembimbing2'];
		$pb3 = $data['pembimbing3'];
		$ps1 = $data['pembahas1'];
		$ps2 = $data['pembahas2'];
		$ps3 = $data['pembahas3'];

		// pb ps alt
		$pb2_nip = $data['pb2_alter_nip'];
		$pb2_nama = $data['pb2_alter_nama'];
		$pb2_email = $data['pb2_alter_email'];
		$pb3_nip = $data['pb3_alter_nip'];
		$pb3_nama = $data['pb3_alter_nama'];
		$pb3_email = $data['pb3_alter_email'];
		$ps1_nip = $data['ps1_alter_nip'];
		$ps1_nama = $data['ps1_alter_nama'];
		$ps1_email = $data['ps1_alter_email'];
		$ps2_nip = $data['ps2_alter_nip'];
		$ps2_nama = $data['ps2_alter_nama'];
		$ps2_email = $data['ps2_alter_email'];
		$ps3_nip = $data['ps3_alter_nip'];
		$ps3_nama = $data['ps3_alter_nama'];
		$ps3_email = $data['ps3_alter_email'];

		//copy ta
		$id_new = $this->ta_model->copy_row_ta($id_old,$pb1);
		//insert to tugas_akhir_ganti_pbb
		$insert_data = array(
			'npm' => $npm,
			'id_ta_old' => $id_old,
			'id_ta_new' => $id_new,		
		);
		$this->ta_model->tugas_akhir_ganti_pbb($insert_data);

		//pb 1 sama copy semua ttd
		if($pb1 == $pb1_old){
			//copy approval non pbb
			$this->ta_model->copy_row_ta_approval($id_old,$id_new);
		}
		//pb 1 berbeda ttd pb 1 null
		else{
			$this->ta_model->copy_row_ta_approval_non_pb1($id_old,$id_new);
			$this->ta_model->copy_row_ta_approval_pb1($id_old,$id_new,$pb1);
		}

		//copy surat
		$this->ta_model->copy_staff_surat_ta($id_old,$id_new);
		
		//update status ta old > -2
		$this->ta_model->update_ganti_pbb_ta_old($id_old);
		//insert tugas_akhir_komisi_pb1
		$nip_pb1 = $this->db->query('SELECT nip_nik FROM tbl_users_dosen WHERE id_user ='.$pb1)->row()->nip_nik;
		$nama_pb1 = $this->db->query('SELECT name FROM tbl_users WHERE userId ='.$pb1)->row()->name;
		$data_pbb1=array(
			'id_tugas_akhir' => $id_new,
			'status' => 'Pembimbing Utama',
			'nip_nik' => $nip_pb1,
			'id_user' => $pb1,
			'nama' => $nama_pb1,
		);
		$this->ta_model->insert_ta_komisi_pb1($data_pbb1);
		//copy berkas
		$this->ta_model->copy_berkas_ganti_pbb($id_old,$id_new);

		
		//insert tugas_akhir_komisi
		if($pb2 == NULL && ($pb2_nip != NULL && $pb2_nama != NULL)){
			$status = "Pembimbing 2";
			$this->ta_model->set_komisi_alter($id_new,$pb2_nip,$pb2_nama,$status);
			$this->ta_model->set_komisi_alter_access($id_new,$pb2_nip,$pb2_nama,$status,$pb2_email);

			$data_approval = array(
				'id_pengajuan' => $id_new,
				'status_slug' => $status,
				'id_user' => '0',
				'ttd' => '',	
			);
			$this->ta_model->insert_approval_ta($data_approval);
		}

		if($pb3 == NULL && ($pb3_nip != NULL && $pb3_nama != NULL)){
			$status = "Pembimbing 3";
			$this->ta_model->set_komisi_alter($id_new,$pb3_nip,$pb3_nama,$status);
			$this->ta_model->set_komisi_alter_access($id_new,$pb3_nip,$pb3_nama,$status,$pb3_email);

			$data_approval = array(
				'id_pengajuan' => $id_new,
				'status_slug' => $status,
				'id_user' => '0',
				'ttd' => '',	
			);
			$this->ta_model->insert_approval_ta($data_approval);
		}

		if($ps1 == NULL && ($ps1_nip != NULL && $ps1_nama != NULL)){
			$status = "Penguji 1";
			$this->ta_model->set_komisi_alter($id_new,$ps1_nip,$ps1_nama,$status);
			$this->ta_model->set_komisi_alter_access($id_new,$ps1_nip,$ps1_nama,$status,$ps1_email);

			$data_approval = array(
				'id_pengajuan' => $id_new,
				'status_slug' => $status,
				'id_user' => '0',
				'ttd' => '',	
			);
			$this->ta_model->insert_approval_ta($data_approval);

		}

		if($ps2 == NULL && ($ps2_nip != NULL && $ps2_nama != NULL)){
			$status = "Penguji 2";
			$this->ta_model->set_komisi_alter($id_new,$ps2_nip,$ps2_nama,$status);
			$this->ta_model->set_komisi_alter_access($id_new,$ps2_nip,$ps2_nama,$status,$ps2_email);

			$data_approval = array(
				'id_pengajuan' => $id_new,
				'status_slug' => $status,
				'id_user' => '0',
				'ttd' => '',	
			);
			$this->ta_model->insert_approval_ta($data_approval);
		}

		if($ps3 == NULL && ($ps3_nip != NULL && $ps3_nama != NULL)){
			$status = "Penguji 3";
			$this->ta_model->set_komisi_alter($id_new,$ps3_nip,$ps3_nama,$status);
			$this->ta_model->set_komisi_alter_access($id_new,$ps3_nip,$ps3_nama,$status,$ps3_email);

			$data_approval = array(
				'id_pengajuan' => $id_new,
				'status_slug' => $status,
				'id_user' => '0',
				'ttd' => '',	
			);
			$this->ta_model->insert_approval_ta($data_approval);
		}

		if($jenis == "Tugas Akhir"){
			$this->ta_model->approval_koordinator_ta($id_new,$ttd,$dosenid,$no_penetapan,$judul_approve,$judul1,$judul2);
			$this->ta_model->set_komisi_ta($id_new,$pb1,$ps1,$ps2);

			if($ps1 != NULL && $ps1 != '0'){
	
				$data_approval = array(
					'id_pengajuan' => $id_new,
					'status_slug' => 'Penguji 1',
					'id_user' => $ps1,
					'ttd' => '',	
				);
	
				$this->ta_model->insert_approval_ta($data_approval);
			}

			// $this->ta_model->approve_ta_kaprodi($id);
			$data_approval = array(
				'id_pengajuan' => $id_new,
				'status_slug' => "Ketua Program Studi",
				'id_user' => $dosenid,
				'ttd' => $ttd,
			);
			$this->ta_model->insert_approve_ta_kaprodi($data_approval);
		}
		else{
			$this->ta_model->approval_koordinator($id_new,$ttd,$dosenid,$no_penetapan,$judul_approve,$judul1,$judul2);
			$this->ta_model->set_komisi($id_new,$pb1,$pb2,$pb3,$ps1,$ps2,$ps3);

			if($jenis != 'Skripsi'){
				$data_approval = array(
					'id_pengajuan' => $id_new,
					'status_slug' => "Ketua Program Studi",
					'id_user' => $dosenid,
					'ttd' => $ttd,
				);
				$this->ta_model->insert_approve_ta_kaprodi($data_approval);
			}

			if($pb2 != NULL && $pb2 != '0'){

				$data_approval = array(
					'id_pengajuan' => $id_new,
					'status_slug' => 'Pembimbing 2',
					'id_user' => $pb2,
					'ttd' => '',	
				);
	
				$this->ta_model->insert_approval_ta($data_approval);
			}
	
			if($pb3 != NULL && $pb3 != '0'){
	
				$data_approval = array(
					'id_pengajuan' => $id_new,
					'status_slug' => 'Pembimbing 3',
					'id_user' => $pb3,
					'ttd' => '',	
				);
	
				$this->ta_model->insert_approval_ta($data_approval);
			}
	
			if($ps1 != NULL && $ps1 != '0'){
	
				$data_approval = array(
					'id_pengajuan' => $id_new,
					'status_slug' => 'Penguji 1',
					'id_user' => $ps1,
					'ttd' => '',	
				);
	
				$this->ta_model->insert_approval_ta($data_approval);
			}
	
			if($ps2 != NULL && $ps2 != '0'){
	
				$data_approval = array(
					'id_pengajuan' => $id_new,
					'status_slug' => 'Penguji 2',
					'id_user' => $ps2,
					'ttd' => '',	
				);
	
				$this->ta_model->insert_approval_ta($data_approval);
			}
	
			if($ps3 != NULL && $ps3 != '0'){
	
				$data_approval = array(
					'id_pengajuan' => $id_new,
					'status_slug' => 'Penguji 3',
					'id_user' => $ps3,
					'ttd' => '',	
				);
	
				$this->ta_model->insert_approval_ta($data_approval);
			}
		}
		$seg = $this->uri->segment(2);
		redirect(site_url("dosen/$seg/rekap/tugas-akhir"));
	}
	
	function komposisi_nilai()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();

		$data['nilai'] = $this->ta_model->get_komposisi_nilai($this->session->userdata('userId'));

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/kajur/komposisi_nilai/komposisi_nilai',$data);
		
		$this->load->view('footer_global');
	}

	function komposisi_nilai_tambah()
	{
		
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();

		$data['nilai'] = $this->ta_model->get_komposisi_nilai($this->session->userdata('userId'));

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/kajur/komposisi_nilai/komposisi_nilai_add',$data);
		
		$this->load->view('footer_global');
	}

	function komposisi_nilai_simpan()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);
		$tipe = $data['tipe'];

		// if($tipe != "Sidang Komprehensif"){
			$jml = count($data['ujian_komponen']);
			$jml2 = count($data['skripsi_komponen']);
		// }
		// else{
		// 	$jml = count($data['ujian_komponen_kompre']);
		// 	$jml2 = count($data['skripsi_komponen_kompre']);
		// }
		
		
		$ujian = 0;
		$skripsi = 0;

		//check
		// if($tipe != "Sidang Komprehensif"){
			for($i=0; $i<$jml; $i++){
				$ujian += $data['ujian_nilai'][$i];
			}
			for($i=0; $i<$jml2; $i++){
				$skripsi += $data['skripsi_nilai'][$i];
			}
		// }
		// else{
		// 	for($i=0; $i<$jml; $i++){
		// 		$ujian += $data['ujian_nilai_kompre'][$i];
		// 	}i
		// 	for($i=0; $i<$jml2; $i++){
		// 		$skripsi += $data['skripsi_nilai_kompre'][$i];
		// 	}
		// }

		if($tipe != "Sidang Komprehensif"){
			$persentase =  $ujian + $skripsi;
		}
		else{
			$persentase = 100;
		}
		
		$cek = $this->ta_model->cek_komposisi_nilai($data['jurusan'],$data['jenis'],$tipe);

		if(!empty($cek)){
			redirect(site_url("/dosen/struktural/komposisi-nilai/add?status=duplikat"));
		}
		else{

		if($data['jenis'] == "Skripsi"){
			$total1 = $data['skripsi_pb1_1'] + $data['skripsi_pb2_1'] + $data['skripsi_ps1_1'];
			$total2 = $data['skripsi_pb1_2'] + $data['skripsi_ps1_2'] + $data['skripsi_ps2_2']; 
			// echo $total1;
			// echo $total2;
			if($total1 < 100 || $total2 < 100){
				redirect(site_url("/dosen/struktural/komposisi-nilai/add?status=kurang"));
			}
			elseif($total1 > 100 || $total2 > 100){
				redirect(site_url("/dosen/struktural/komposisi-nilai/add?status=lebih"));
			}
			else{
				$bobot = $data['skripsi_pb1_1'].'-'.$data['skripsi_pb2_1'].'-'.$data['skripsi_ps1_1'].'#'.$data['skripsi_pb1_2'].'-'.$data['skripsi_ps1_2'].'-'.$data['skripsi_ps2_2'];
			}

		}

		elseif($data['jenis'] == "Tugas Akhir"){
			$total = $data['ta_pb1'] + $data['ta_ps1'];

			if($total < 100){
				redirect(site_url("/dosen/struktural/komposisi-nilai/add?status=kurang"));
			}
			elseif($total > 100){
				redirect(site_url("/dosen/struktural/komposisi-nilai/add?status=lebih"));
			}
			else{
				$bobot = $data['ta_pb1'].'-'.$data['ta_ps1'];
			}
		}

		elseif($data['jenis'] == "Tesis"){
			if($data['tesis_pb1'] == NULL){
				$data['tesis_pb1'] = 0;
			}
			if($data['tesis_pb2'] == NULL){
				$data['tesis_pb2'] = 0;
			}
			if($data['tesis_pb3'] == NULL){
				$data['tesis_pb3'] = 0;
			}
			if($data['tesis_ps1'] == NULL){
				$data['tesis_ps1'] = 0;
			}
			if($data['tesis_ps2'] == NULL){
				$data['tesis_ps2'] = 0;
			}
			if($data['tesis_ps3'] == NULL){
				$data['tesis_ps3'] = 0;
			}
			$total = $data['tesis_pb1'] + $data['tesis_pb2']+ $data['tesis_pb3'] + $data['tesis_ps1'] + $data['tesis_ps2'] + $data['tesis_ps3'];
			
			$bobot = $data['tesis_pb1'].'-'.$data['tesis_pb2'].'-'.$data['tesis_pb3'].'-'.$data['tesis_ps1'].'-'.$data['tesis_ps2'].'-'.$data['tesis_ps3'];


		}

		elseif($data['jenis'] == "Disertasi"){
			if($data['disertasi_pb1'] == NULL){
				$data['disertasi_pb1'] = 0;
			}
			if($data['disertasi_pb2'] == NULL){
				$data['disertasi_pb2'] = 0;
			}
			if($data['disertasi_pb3'] == NULL){
				$data['disertasi_pb3'] = 0;
			}
			if($data['disertasi_ps1'] == NULL){
				$data['disertasi_ps1'] = 0;
			}
			if($data['disertasi_ps2'] == NULL){
				$data['disertasi_ps2'] = 0;
			}
			if($data['disertasi_ps3'] == NULL){
				$data['disertasi_ps3'] = 0;
			}
			$total = $data['disertasi_pb1'] + $data['disertasi_pb2']+ $data['disertasi_pb3'] + $data['disertasi_ps1'] + $data['disertasi_ps2'] + $data['disertasi_ps3'];
			
			$bobot = $data['disertasi_pb1'].'-'.$data['disertasi_pb2'].'-'.$data['disertasi_pb3'].'-'.$data['disertasi_ps1'].'-'.$data['disertasi_ps2'].'-'.$data['disertasi_ps3'];


		}

		if($persentase < 100){
			redirect(site_url("/dosen/struktural/komposisi-nilai/add?status=kurang"));
		}
		elseif($persentase > 100){
			redirect(site_url("/dosen/struktural/komposisi-nilai/add?status=lebih"));
		}
		elseif($persentase = 100){
			$komponen_nilai = array(
				'id_prodi' => $data['jurusan'],
				'semester' => $data['semester'],
				'tahun_akademik' => $data['tahun_akademik'],
				'jenis' => $data['jenis'],
				'tipe' => $data['tipe'],
				'bobot' => $bobot,
				'status' => '0',	
			);
	
		$lastid = $this->ta_model->komposisi_nilai_save($komponen_nilai);
	
		// if($data['tipe'] != "Sidang Komprehensif"){
			for($i=0; $i<$jml; $i++){
	
				$data_ujian = array(
					'id_komponen' => $lastid,
					'unsur' => 'Ujian',
					'attribut' => $data['ujian_komponen'][$i],
					'persentase' => $data['ujian_nilai'][$i],
				);
				$this->ta_model->komposisi_nilai_meta_save($data_ujian);
	
			}
	
			for($i=0; $i<$jml2; $i++){
				$data_ujian = array(
					'id_komponen' => $lastid,
					'unsur' => $data['jenis'],
					'attribut' => $data['skripsi_komponen'][$i],
					'persentase' => $data['skripsi_nilai'][$i],
				);
				$this->ta_model->komposisi_nilai_meta_save($data_ujian);
			}
		// }
		// else{
		// 	for($i=0; $i<$jml; $i++){
		// 		$data_ujian = array(
		// 			'id_komponen' => $lastid,
		// 			'unsur' => 'Ujian',
		// 			'attribut' => $data['ujian_komponen_kompre'][$i],
		// 			'persentase' => "100",
		// 		);
		// 		$this->ta_model->komposisi_nilai_meta_save($data_ujian);
	
		// 	}
	
		// 	for($i=0; $i<$jml2; $i++){
		// 		$data_ujian = array(
		// 			'id_komponen' => $lastid,
		// 			'unsur' => $data['jenis'],
		// 			'attribut' => $data['skripsi_komponen_kompre'][$i],
		// 			'persentase' => "100",
		// 		);
		// 		$this->ta_model->komposisi_nilai_meta_save($data_ujian);
		// 	}
		// }

			redirect(site_url("dosen/struktural/bidang-nilai/komposisi-nilai"));
		}
		}
	}

	function komponen_nilai()
	{
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['komponen'] = $this->ta_model->get_komposisi_nilai_id($id);
		$data['meta'] = $this->ta_model->get_komposisi_nilai_meta_id($id);

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/kajur/komposisi_nilai/show_komponen_nilai',$data);
		
		$this->load->view('footer_global');
	}

	function komposisi_nilai_nonaktif()
	{
		$data = $this->input->post();

		$id = $data['id'];
		$this->ta_model->nonaktifkan_komposisi($id);

		redirect(site_url("dosen/struktural/bidang-nilai/komposisi-nilai"));
	}

	function komposisi_nilai_ubah()
	{
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['komponen'] = $this->ta_model->get_komposisi_nilai_id($id);
		$data['meta'] = $this->ta_model->get_komposisi_nilai_meta_id($id);

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/kajur/komposisi_nilai/komposisi_nilai_ubah',$data);
		
		$this->load->view('footer_global');
	}

	function komposisi_nilai_edit()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);

		$id = $data['id'];
		$tipe = $data['tipe'];

		// if($tipe != "Sidang Komprehensif"){
			$jml = count($data['ujian_komponen']);
			$jml2 = count($data['skripsi_komponen']);
		// }
		// else{
		// 	$jml = count($data['ujian_komponen_kompre']);
		// 	$jml2 = count($data['skripsi_komponen_kompre']);
		// }

		$ujian = 0;
		$skripsi = 0;
		//check
		// if($tipe != "Sidang Komprehensif"){
			for($i=0; $i<$jml; $i++){
				$ujian += $data['ujian_nilai'][$i];
			}
			for($i=0; $i<$jml2; $i++){
				$skripsi += $data['skripsi_nilai'][$i];
			}
		// }
		// else{
		// 	for($i=0; $i<$jml; $i++){
		// 		$ujian += $data['ujian_nilai_kompre'][$i];
		// 	}
		// 	for($i=0; $i<$jml2; $i++){
		// 		$skripsi += $data['skripsi_nilai_kompre'][$i];
		// 	}
		// }


		if($tipe != "Sidang Komprehensif"){
			$persentase =  $ujian + $skripsi;
		}
		else{
			$persentase = 100;
		}


		if($data['jenis'] == "Skripsi"){
			$total1 = $data['skripsi_pb1_1'] + $data['skripsi_pb2_1'] + $data['skripsi_ps1_1'];
			$total2 = $data['skripsi_pb1_2'] + $data['skripsi_ps1_2'] + $data['skripsi_ps2_2']; 
			
			if($total1 < 100 || $total2 < 100){
				redirect(site_url("/dosen/struktural/komposisi-nilai/ubah?id=$id&status=kurang"));
			}
			elseif($total1 > 100 || $total2 > 100){
				redirect(site_url("/dosen/struktural/komposisi-nilai/ubah?id=$id&status=lebih"));
			}
			else{
				$bobot = $data['skripsi_pb1_1'].'-'.$data['skripsi_pb2_1'].'-'.$data['skripsi_ps1_1'].'#'.$data['skripsi_pb1_2'].'-'.$data['skripsi_ps1_2'].'-'.$data['skripsi_ps2_2'];
			}
		}

		elseif($data['jenis'] == "Tugas Akhir"){
			$total = $data['ta_pb1'] + $data['ta_ps1'];

			if($total < 100){
				redirect(site_url("/dosen/struktural/komposisi-nilai/ubah?status=kurang"));
			}
			elseif($total > 100){
				redirect(site_url("/dosen/struktural/komposisi-nilai/ubah?status=lebih"));
			}
			else{
				$bobot = $data['ta_pb1'].'-'.$data['ta_ps1'];
			}
		}

		elseif($data['jenis'] == "Tesis"){
			if($data['tesis_pb1'] == NULL){
				$data['tesis_pb1'] = 0;
			}
			if($data['tesis_pb2'] == NULL){
				$data['tesis_pb2'] = 0;
			}
			if($data['tesis_pb3'] == NULL){
				$data['tesis_pb3'] = 0;
			}
			if($data['tesis_ps1'] == NULL){
				$data['tesis_ps1'] = 0;
			}
			if($data['tesis_ps2'] == NULL){
				$data['tesis_ps2'] = 0;
			}
			if($data['tesis_ps3'] == NULL){
				$data['tesis_ps3'] = 0;
			}
			$total = $data['tesis_pb1'] + $data['tesis_pb2']+ $data['tesis_pb3'] + $data['tesis_ps1'] + $data['tesis_ps2'] + $data['tesis_ps3'];
			$bobot = $data['tesis_pb1'].'-'.$data['tesis_pb2'].'-'.$data['tesis_pb3'].'-'.$data['tesis_ps1'].'-'.$data['tesis_ps2'].'-'.$data['tesis_ps3'];
		}

		elseif($data['jenis'] == "Disertasi"){
			if($data['disertasi_pb1'] == NULL){
				$data['disertasi_pb1'] = 0;
			}
			if($data['disertasi_pb2'] == NULL){
				$data['disertasi_pb2'] = 0;
			}
			if($data['disertasi_pb3'] == NULL){
				$data['disertasi_pb3'] = 0;
			}
			if($data['disertasi_ps1'] == NULL){
				$data['disertasi_ps1'] = 0;
			}
			if($data['disertasi_ps2'] == NULL){
				$data['disertasi_ps2'] = 0;
			}
			if($data['disertasi_ps3'] == NULL){
				$data['disertasi_ps3'] = 0;
			}
			$total = $data['disertasi_pb1'] + $data['disertasi_pb2']+ $data['disertasi_pb3'] + $data['disertasi_ps1'] + $data['disertasi_ps2'] + $data['disertasi_ps3'];
			$bobot = $data['disertasi_pb1'].'-'.$data['disertasi_pb2'].'-'.$data['disertasi_pb3'].'-'.$data['disertasi_ps1'].'-'.$data['disertasi_ps2'].'-'.$data['disertasi_ps3'];
			

		}

		if($persentase < 100){
			redirect(site_url("/dosen/struktural/komposisi-nilai/ubah?id=$id&status=kurang"));
		}
		elseif($persentase > 100){
			redirect(site_url("/dosen/struktural/komposisi-nilai/ubah?id=$id&status=lebih"));
		}
		elseif($persentase = 100){
			
			$komponen_nilai = array(
				'id_prodi' => $data['jurusan'],
				'semester' => $data['semester'],
				'tahun_akademik' => $data['tahun_akademik'],
				'jenis' => $data['jenis'],
				'tipe' => $tipe,
				'bobot' => $bobot,
				'status' => '0',	
			);
	
			$this->ta_model->update_komposisi($id,$komponen_nilai);
			$this->ta_model->delete_komposisi_meta($id);
			
			// if($data['tipe'] != "Sidang Komprehensif"){
			for($i=0; $i<$jml; $i++){
	
				$data_ujian = array(
					'id_komponen' => $id,
					'unsur' => 'Ujian',
					'attribut' => $data['ujian_komponen'][$i],
					'persentase' => $data['ujian_nilai'][$i],
				);
				$this->ta_model->komposisi_nilai_meta_save($data_ujian);
	
			}
	
			for($i=0; $i<$jml2; $i++){
				$data_ujian = array(
					'id_komponen' => $id,
					'unsur' => $data['jenis'],
					'attribut' => $data['skripsi_komponen'][$i],
					'persentase' => $data['skripsi_nilai'][$i],
				);
				$this->ta_model->komposisi_nilai_meta_save($data_ujian);
			}
		// }
		// else{
		// 	for($i=0; $i<$jml; $i++){
		// 		$data_ujian = array(
		// 			'id_komponen' => $id,
		// 			'unsur' => 'Ujian',
		// 			'attribut' => $data['ujian_komponen_kompre'][$i],
		// 			'persentase' => "100",
		// 		);
		// 		$this->ta_model->komposisi_nilai_meta_save($data_ujian);
	
		// 	}
	
		// 	for($i=0; $i<$jml2; $i++){
		// 		$data_ujian = array(
		// 			'id_komponen' => $id,
		// 			'unsur' => $data['jenis'],
		// 			'attribut' => $data['skripsi_komponen_kompre'][$i],
		// 			'persentase' => "100",
		// 		);
		// 		$this->ta_model->komposisi_nilai_meta_save($data_ujian);
		// 	}
		// }


			redirect(site_url("dosen/struktural/bidang-nilai/komposisi-nilai"));
		}

	}

	function nilai_seminar_save()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);
		
		$id = $data['id'];
		$status = $data['status'];
		$saran = $data['saran'];
		$id_komponen = $data['id_komponen'];
		$ttd = $data['ttd'];

		$jml = $data['jml'];

		$nilai = $data['nilai'];
		$attribut = $data['attribut'];

		$counts = $this->ta_model->cek_seminar_nilai_fill($id);
		$count = count($counts);

		// echo $count;
		for($i=1;$i<$jml;$i++)
		{
			$data = array(
				'id_seminar_sidang' => $id,
				'id_komponen' => $id_komponen,
				'komponen' => $attribut[$i],
				'nilai' => $nilai[$i],
				'status' => $status,
			);
			$this->ta_model->insert_seminar_nilai($data);
		}
		if($count == 1){
			$this->ta_model->seminar_sidang_nilai_dosen_update($id);
		}

		$this->ta_model->update_nilai_seminar_check($id,$status,$saran,$ttd);
		redirect(site_url("dosen/tugas-akhir/nilai-seminar"));
	}

	function nilai_seminar_koor()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['seminar'] = $this->ta_model->get_approval_nilai_seminar_koordinator($this->session->userdata('userId'));

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/koordinator/nilai_seminar/nilai_seminar',$data);
		
		$this->load->view('footer_global');
	}

	function nilai_seminar_sidang_kaprodi()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['seminar'] = $this->ta_model->get_approval_nilai_seminar_kaprodi($this->session->userdata('userId'));

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/koordinator/nilai_seminar/nilai_seminar',$data);
		
		$this->load->view('footer_global');
	}

	function nilai_seminar_koor_approve()
	{
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['seminar'] = $this->ta_model->get_seminar_id($id);
		$data['ta'] = $this->ta_model->get_tugas_akhir_seminar_id($id);

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/koordinator/nilai_seminar/nilai_seminar_approve',$data);
		
		$this->load->view('footer_global');

	}

	function nilai_seminar_sidang_kaprodi_approve()
	{
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['seminar'] = $this->ta_model->get_seminar_id($id);
		$data['ta'] = $this->ta_model->get_tugas_akhir_seminar_id($id);

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/koordinator/nilai_seminar/nilai_seminar_approve',$data);
		
		$this->load->view('footer_global');

	}

	function nilai_seminar_koor_approve_add()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);

		$id = $data['id'];
		$id_ta = $data['id_ta'];
		$ttd = $data['ttd'];
		$jenis = $data['jenis'];
		$jenis_ta = $data['jenis_ta'];
		$status = "Koordinator";
		$user_id = $this->session->userdata('userId');

		if($jenis_ta != "Skripsi"){
			$data_ta = array(
				'status' => "Ketua Program Studi",
				'saran' => '',
				'ket' => $user_id,
				'id_seminar' => $id,
				'ttd' => $ttd,
			);
			$this->ta_model->insert_nilai_seminar_koor($data_ta);
		}

		if($jenis == "Sidang Komprehensif"){
			$data_kompre = array(
				'npm' => $data['npm'],
				'id_ta' => $id_ta,
				'id_seminar' => $id,
				'ket' => $data['keterangan'],
			);
			$this->ta_model->insert_nilai_seminar_koor_kompre($data_kompre);
		}

		$data_koor = array(
			'status' => $status,
			'saran' => '',
			'ket' => $user_id,
			'id_seminar' => $id,
			'ttd' => $ttd,
		);
		$this->ta_model->insert_nilai_seminar_koor($data_koor);

		//update seminar
		$this->ta_model->update_nilai_seminar_koor($id);
		if($jenis_ta != "Skripsi"){
			redirect(site_url("dosen/struktural/kaprodi/nilai-seminar-sidang"));
		}
		else{
			redirect(site_url("dosen/tugas-akhir/nilai-seminar/koordinator"));
		}
		

	}

	function nilai_seminar_kajur()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['seminar'] = $this->ta_model->get_approval_nilai_seminar_kajur($this->session->userdata('userId'));

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/kajur/nilai_seminar/nilai_seminar',$data);
		
		$this->load->view('footer_global');
	}

	function nilai_seminar_kajur_approve()
	{
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['seminar'] = $this->ta_model->get_seminar_id($id);
		$data['ta'] = $this->ta_model->get_tugas_akhir_seminar_id($id);

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/kajur/nilai_seminar/nilai_seminar_approve',$data);
		
		$this->load->view('footer_global');
	}

	function nilai_seminar_kajur_approve_add()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);

		$id = $data['id'];
		$ttd = $data['ttd'];
		$jenis = $data['jenis'];
		$status = "Ketua Jurusan";
		$user_id = $this->session->userdata('userId');

		$data = array(
			'status' => $status,
			'saran' => '',
			'ket' => $user_id,
			'id_seminar' => $id,
			'ttd' => $ttd,
		);
		$this->ta_model->insert_nilai_seminar_kajur($data);

		if($jenis == "Sidang Komprehensif"){
			$this->ta_model->update_seminar_sidang_kompre_id_seminar($id);
		}

		//update seminar
		$this->ta_model->update_nilai_seminar_kajur($id);
		redirect(site_url("dosen/struktural/nilai-seminar"));
	}

	function bidang_jurusan()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['bidang'] = $this->user_model->get_dosen_prodi($this->session->userdata('userId'));

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/kajur/bidang/bidang_jurusan',$data);
		
		$this->load->view('footer_global');
	}

	function bidang_jurusan_show()
	{
		$jurusan = $this->input->get('jurusan');
		// $jurusan = $this->encrypt->decode($jurusan);

		$prodi = $this->input->get('prodi');
		// $prodi = $this->encrypt->decode($prodi);


		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['bidang'] = $this->parameter_model->select_bidang_ilmu($jurusan,$prodi);

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/kajur/bidang/bidang_jurusan_show',$data);
		
		$this->load->view('footer_global');
	}

	function bidang_jurusan_add()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);

		$prodi = $data['prodi'];
		$jurusan = $data['jurusan'];
		$nama = $data['nama'];

		$this->ta_model->insert_bidang_jurusan($data);
		redirect(site_url("dosen/struktural/bidang-nilai/bidang-jurusan/show?jurusan=$jurusan&prodi=$prodi"));
	}

	function bidang_jurusan_delete()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);
		$id = $data['id'];
		$prodi = $data['prodi'];
		$jurusan = $data['jurusan'];

		$this->ta_model->delete_bidang_jurusan($id);
		redirect(site_url("dosen/struktural/bidang-nilai/bidang-jurusan/show?jurusan=$jurusan&prodi=$prodi"));
	}

	function komposisi_ta()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['bidang'] = $this->parameter_model->get_bidang_ilmu_ta();

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/kajur/komposisi_ta/komposisi_ta',$data);
		
		$this->load->view('footer_global');
	}

	function komposisi_ta_add()
	{
		$id = $this->input->get('id');

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['wajib'] = $this->ta_model->get_verifikasi_ta_komponen_wajib($id);
		$data['konten'] = $this->ta_model->get_verifikasi_ta_komponen_konten($id);
		$data['bidang'] = $this->ta_model->get_bidang_ilmu_id($id);

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/kajur/komposisi_ta/komposisi_ta_add',$data);
		
		$this->load->view('footer_global');

	}

	function komposisi_ta_save()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);

		$id = $data['bidang'];
		// $ket = $data['ket'];
		// $komponen = $data['nama'];

		$this->ta_model->insert_komponen_ta($data);
		redirect(site_url("dosen/struktural/bidang-nilai/komposisi-ta/add?id=$id"));

	}

	function komposisi_ta_delete()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);

		$id = $data['id'];
		$bidang = $data['bidang'];

		$this->ta_model->delete_komponen_ta($id,$bidang);
		redirect(site_url("dosen/struktural/bidang-nilai/komposisi-ta/add?id=$bidang"));
	}

	function tugas_akhir_kaprodi(){
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['ta'] = $this->ta_model->get_approval_ta_kaprodi($this->session->userdata('userId'));
		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/kaprodi/tema_ta/tema_ta',$data);
		
		$this->load->view('footer_global');
	}

	function tugas_akhir_kaprodi_form()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['ta'] = $this->ta_model->get_approval_ta_koordinator($this->session->userdata('userId'));

		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);
		$aksi = $this->input->get('aksi');

		$data['ta'] = $this->ta_model->get_ta_by_id($id);
		$data['aksi'] = $aksi;

		// print_r($data);
		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/koordinator/approve_tema_ta',$data);
		
		$this->load->view('footer_global');
	}

	function tugas_akhir_kaprodi_approve()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);

		$id = $data['id_pengajuan'];
		$id_user = $data['id_user'];
		$ttd = $data['ttd'];

		$this->ta_model->approve_ta_kaprodi($id);

		$data_approval = array(
			'id_pengajuan' => $id,
			'status_slug' => "Ketua Program Studi",
			'id_user' => $id_user,
			'ttd' => $ttd,
		);
		$this->ta_model->insert_approve_ta_kaprodi($data_approval);
		redirect(site_url("dosen/struktural/kaprodi/tugas-akhir"));
	}

	function nilai_verifikasi()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['ta'] = $this->ta_model->get_verifikasi_program_ta_dosen($this->session->userdata('userId'));

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/verifikasi_ta/verifikasi_ta',$data);
		
		$this->load->view('footer_global');
	}

	function nilai_verifikasi_form()
	{
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();

		$data['ta'] = $this->ta_model->get_verifikasi_program_ta_nilai($id);
		$data['komponen'] = $this->ta_model->get_verifikasi_program_ta_komponen($data['ta']->bidang_ilmu);
		$data['wajib'] = $this->ta_model->get_verifikasi_program_ta_pertemuan_wajib($data['ta']->id_pengajuan);
		$data['konten'] = $this->ta_model->get_verifikasi_program_ta_pertemuan_konten($data['ta']->id_pengajuan);

		if(!empty($data['komponen'])){
			$this->load->view('header_global', $header);
			$this->load->view('dosen/header');

			$this->load->view('dosen/verifikasi_ta/verifikasi_ta_nilai',$data);
			
			$this->load->view('footer_global');
		}
		else{
			redirect(site_url("dosen/tugas-akhir/nilai-verifikasi-ta?status=error"));
		}
	}

	function nilai_verifikasi_save()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);
		$id = $data['id_pengajuan'];
		$pertemuan = $this->ta_model->get_verifikasi_program_ta_pertemuan($id);
		
		foreach ($pertemuan as $prt){
			 $day = $data[$prt->id_verif];
			 $this->ta_model->update_verifikasi_ta_pertemuan($day, $prt->id_verif);
		}
		redirect(site_url("dosen/tugas-akhir/nilai-verifikasi-ta"));
	}

	function nilai_verifikasi_nilai()
	{
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['ta'] = $this->ta_model->get_ta_by_id($id);

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/verifikasi_ta/verifikasi_ta_ttd',$data);
		
		$this->load->view('footer_global');
	}

	function nilai_verifikasi_verifikasi()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);

		$id = $data['id_pengajuan'];
		$id_encode = $data['id_encode'];
		$nilai = $data['nilai'];
		$ttd = $data['ttd'];
		$nilai_date = date("Y-m-d H:i:s",strtotime('+7 hours'));

		$cek = is_numeric($nilai);
		if($cek == 1){
			if($nilai <= 0 || $nilai >= 100){
				redirect(site_url("dosen/tugas-akhir/nilai-verifikasi-ta/nilai?id=$id_encode&status=error"));
			}
			else{
				$this->ta_model->update_verifikasi_ta_nilai($id, $nilai, $ttd, $nilai_date);
				redirect(site_url("dosen/tugas-akhir/nilai-verifikasi-ta"));
			}
		}
		else{
			redirect(site_url("dosen/tugas-akhir/nilai-verifikasi-ta/nilai?id=$id_encode&status=error"));
		}

	}

	function tugas_akhir_kaprodi_verifikasi()
	{

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['ta'] = $this->ta_model->get_verifikasi_ta_list_kaprodi($this->session->userdata('userId'));

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/kaprodi/verifikasi_ta/verifikasi_ta',$data);
		
		$this->load->view('footer_global');
	}

	function verifikasi_ta_dosen()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['ta'] = $this->ta_model->get_verifikasi_ta_list($this->session->userdata('userId'));
		$data['pa'] = $this->ta_model->get_verifikasi_ta_list_pa($this->session->userdata('userId'));

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/verifikasi_ta/verifikasi_ta_list',$data);
		
		$this->load->view('footer_global');
	}

	function verifikasi_ta_dosen_form()
	{
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);
		$status = $this->input->get('status');

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['ta'] = $this->ta_model->get_ta_by_id($id);
		$data['status'] = $status;

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/verifikasi_ta/verifikasi_ta_approve',$data);
		
		$this->load->view('footer_global');
	}

	function verifikasi_ta_dosen_form_save()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);	

		$id = $data['id_pengajuan'];
		$id_user = $data['id_user'];
		$status = $data['status'];
		$ttd = $data['ttd'];

		$data_approval = array(
			'id_ta' => $id,
			'status' => $status,
			'id_user' => $id_user,
			'ttd' => $ttd,
		);

		$this->ta_model->insert_approve_ta_verifikasi($data_approval);
		$this->ta_model->update_nilai_ta_verifikasi($status,$id);
		
		if($status == 'Ketua Program Studi'){
		    redirect(site_url("dosen/struktural/kaprodi/verifikasi-tugas-akhir"));
		}
		else
		{
		    redirect(site_url("dosen/tugas-akhir/verifikasi-ta"));    
		}
		

	}

	function tugas_akhir_kaprodi_verifikasi_form()
	{
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);
		$status = $this->input->get('status');

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['ta'] = $this->ta_model->get_ta_by_id($id);
		$data['status'] = $status;

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/verifikasi_ta/verifikasi_ta_approve',$data);
		
		$this->load->view('footer_global');
	}

	function seminar_sidang_kaprodi()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['seminar'] = $this->ta_model->get_approval_seminar_kaprodi($this->session->userdata('userId'));
		// print_r($data);

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/kaprodi/seminar/seminar_ta_kaprodi',$data);
		
		$this->load->view('footer_global');
	}
	
	//PKL
	function kajur_add_pkl()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['pkl'] = $this->pkl_model->get_pkl_kajur($this->session->userdata('userId'));

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/kajur/pkl/pkl_add',$data);
		
		$this->load->view('footer_global');
	}

	function kajur_add_pkl_form()
	{
		$id = $this->input->get('id');
		$aksi = $this->input->get('aksi');
		$id = $this->encrypt->decode($id);

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');
		if($aksi == "ubah"){
			$data['pkl'] = $this->pkl_model->get_pkl_kajur_by_id($id);
			$this->load->view('dosen/kajur/pkl/pkl_add_form_edit',$data);
		}
		else{
			$this->load->view('dosen/kajur/pkl/pkl_add_form');
		}
		$this->load->view('footer_global');
	}

	function kajur_add_pkl_form_save()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);
		$periode = $data['periode'];
		$tahun = $data['tahun'];
		$jurusan = $data['jurusan'];
		$aksi = $data['aksi'];

		if($aksi == "ubah"){
			$id = $data['ID'];

			//update table pkl_periode;
			$id_periode = $this->pkl_model->update_pkl_periode($id,$periode,$tahun);

			$n = 12;
			for($i=1;$i<=$n;$i++)
			{
				$start = $data[$i.'_start'];
				$end = $data[$i.'_end'];
				$id_meta = $data[$i.'_id'];
				$this->pkl_model->pkl_periode_meta_update($id_meta,$start,$end);
			}
			redirect(site_url("dosen/struktural/pkl/add-pkl")); 
		}
		else{
			//cek duplikat
			$cek = $this->pkl_model->cek_pkl_periode($periode,$tahun,$jurusan);

			if(empty($cek)){
				//insert table pkl_periode;
				$id_periode = $this->pkl_model->insert_pkl_periode($jurusan,$periode,$tahun);

				$n = 12;
				for($i=1;$i<=$n;$i++)
				{
					$start = $data[$i.'_start'];
					$end = $data[$i.'_end'];
					$this->pkl_model->insert_pkl_periode_meta($id_periode,$i,$start,$end);
				}
				redirect(site_url("dosen/struktural/pkl/add-pkl")); 
			}
			else{
				redirect(site_url("dosen/struktural/pkl/add-pkl?status=duplikat")); 
			}
			
		}
		
	}

	function kajur_add_pkl_show()
	{
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['pkl'] = $this->pkl_model->get_pkl_kajur_by_id($id);

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/kajur/pkl/pkl_add_show',$data);
		
		$this->load->view('footer_global');

	}
	
	function kajur_add_pkl_delete()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);
		$id = $data['id_pkl'];

		//delete pkl_periode
		$this->pkl_model->delete_pkl_periode($id);
		//delete pkl_periode_meta
		$this->pkl_model->delete_pkl_periode_meta($id);
		redirect(site_url("dosen/struktural/pkl/add-pkl")); 

	}

	function kajur_add_lokasi_pkl()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['pkl'] = $this->pkl_model->get_pkl_kajur($this->session->userdata('userId'));

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/kajur/pkl/pkl_add_lokasi',$data);
		
		$this->load->view('footer_global');
	}

	function kajur_add_lokasi_pkl_aksi()
	{
		$aksi = $this->input->get('aksi');
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['id'] = $id;
		$data['pkl'] = $this->pkl_model->get_pkl_kajur_by_id($id);
		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/kajur/pkl/pkl_add_lokasi_tambah',$data);
		
		$this->load->view('footer_global');
	}

	function kajur_add_lokasi_pkl_aksi_save()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);
		$lokasi = $data['lokasi'];
		$id_pkl = $data['id_pkl'];
		$alamat = $data['alamat'];

		$id_aksi = $data['id_aksi'];
		$aksi = $data['aksi'];

		$data_lokasi=array(
			"id_pkl" => $id_pkl,
			"lokasi" => $lokasi,
			"alamat" => $alamat,
		);
		$this->pkl_model->insert_pkl_lokasi($data_lokasi);
		redirect(site_url("/dosen/struktural/pkl/add-lokasi-pkl/aksi?aksi=$aksi&id=$id_aksi")); 

	}

	function kajur_add_lokasi_pkl_aksi_delete()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);

		$id_lokasi = $data['id_lokasi'];
		$aksi = $data['id_aksi'];

		$this->pkl_model->delete_pkl_lokasi($id_lokasi);
		redirect(site_url("/dosen/struktural/pkl/add-lokasi-pkl/aksi?aksi=tambah&id=$aksi"));
	}

	function kajur_add_lokasi_pkl_aksi_edit()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);

		$id_lokasi = $data['id_lokasi'];
		$id_aksi = $data['id_aksi'];
		$lokasi = $data['lokasi'];
		$alamat = $data['alamat'];

		$this->pkl_model->update_pkl_lokasi($id_lokasi,$lokasi,$alamat);
		redirect(site_url("/dosen/struktural/pkl/add-lokasi-pkl/aksi?aksi=tambah&id=$id_aksi"));
	}

	function kajur_approve_pkl()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['pkl'] = $this->pkl_model->get_pkl_mahasiswa_approval_kajur($this->session->userdata('userId'));

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/kajur/pkl/pkl_approve',$data);
		
		$this->load->view('footer_global');
	}

	function kajur_approve_pkl_setujui()
	{
		$status = $this->input->get('status');
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['pkl'] = $this->pkl_model->select_pkl_by_id_pkl($id);
		$data['status'] = $status;

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');
		$this->load->view('dosen/kajur/pkl/pkl_approve_ttd',$data);
		$this->load->view('footer_global');
	}

	function kajur_approve_pkl_save()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);
		$id = $data['id_pengajuan'];
		$status = $data['status'];
		$ttd = $data['ttd'];
		$user_id = $this->session->userdata('userId');

		$this->pkl_model->pkl_approve_setujui($status,$id,$user_id,$ttd);
		redirect(site_url("/dosen/struktural/pkl/approve-pkl"));
	}
	
	function pkl_approve()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['pkl'] = $this->pkl_model->get_approve_pa_pkl($this->session->userdata('userId'));

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/pkl/pkl_approve',$data);
		
		$this->load->view('footer_global');
	}

	function pkl_approve_perbaiki()
	{
		$id = $this->input->post('pkl_id');
		$status = $this->input->post('status');
		$keterangan = $this->input->post('keterangan');
		$ket = $keterangan."$#$".$status;

		$this->pkl_model->perbaikan_pkl($id,$ket);
		redirect(site_url("/dosen/pkl/approve"));
	}

	function pkl_approve_setujui()
	{
		$status = $this->input->get('status');
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['pkl'] = $this->pkl_model->select_pkl_by_id_pkl($id);
		$data['status'] = $status;

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/pkl/pkl_approve_ttd',$data);
		
		$this->load->view('footer_global');
	}

	function pkl_approve_setujui_save()
	{
		$data = $this->input->post();
		// print_r($data);
		$pkl_id = $data['id_pengajuan'];
		$status = $data['status'];
		$user_id = $this->session->userdata('userId');
		$ttd = $data['ttd'];

		if($status == "pa"){
			//save surat
			$data_surat_pa = array(
				"jenis" => 3,
				"id_jenis" => $pkl_id
			);
			$this->pkl_model->save_surat_pa($data_surat_pa);	
		}
		$this->pkl_model->pkl_approve_setujui($status,$pkl_id,$user_id,$ttd);
		redirect(site_url("/dosen/pkl/approve"));
	}

	//koor pkl
	function pkl_approve_koor()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['pkl'] = $this->pkl_model->get_approve_koor_lokasi($this->session->userdata('userId'));

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/koordinator/pkl/pkl_koordinator',$data);
		
		$this->load->view('footer_global');
	}

	function pkl_approve_koor_tolak()
	{
		$id = $this->input->post('pkl_id');
		$status = $this->input->post('status');
		$keterangan = $this->input->post('keterangan');
		$ket = $keterangan."$#$".$status;

		$periode = $this->input->post('periode');
		$id_al = $this->input->post('id_al');

		$this->pkl_model->tolak_pkl($id,$ket);
		redirect(site_url("/dosen/pkl/pengajuan/koordinator/approve?periode=$periode&id=$id_al"));
	}

	function pkl_approve_koor_approve()
	{
		$periode = $this->input->get('periode');
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);
		// echo $id;

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['pkl'] = $this->pkl_model->get_pkl_mahasiswa_approval_koor_id($id);
		$data['periode'] = $periode;

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/koordinator/pkl/pkl_koordinator_ttd',$data);
		
		$this->load->view('footer_global');
	}

	function pkl_approve_koor_save()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);
		$pkl_id = $data['pkl_id'];
		$pembimbing = $data['pembimbing'];
		$lokasi = $data['lokasi'];
		$approval_id = $data['approval_id'];

		$id_alm = $data['id_alamat'];
		$periode_alm = $data['periode_alamat'];

		//input approval_meta
		$data_app_meta = array(
			"approval_id" => $approval_id,
			"pkl_id" => $pkl_id
		);
		$this->pkl_model->add_approval_pkl_meta($data_app_meta);

		//update pembimbing & status
		$data_koor = array(
			"pembimbing" => $pembimbing,
			"status" => "3"
		);
		$this->pkl_model->approval_koor_pkl($pkl_id,$data_koor);

		//update status approval_pkl > 1
		//check
		$check = $this->pkl_model->check_approval_mahasiswa_koor($approval_id);
		if(empty($check)){
			//update status approval_pkl > 1
			$this->pkl_model->update_approval_mahasiswa_koor($approval_id);
		}

		redirect(site_url("/dosen/pkl/pengajuan/koordinator/approve?periode=$periode_alm&id=$id_alm"));
	}

	function pkl_approve_koor_setuju()
	{
		$approval_id = $this->input->post('approval_id');
		$periode = $this->input->post('periode_almt');
		$id = $this->input->post('id_almt');
		// $data = $this->input->post();
		// echo "<pre>";
		// print_r($data);

		// set status = 3
		$status = 3;
		$this->pkl_model->approval_id_status($approval_id,$status);

		//mahasiswa selain d3 set status > 7
		//set status pkl_mahasiswa > 4
		$list_mhs = $this->pkl_model->select_pkl_approval_koor($approval_id);
		foreach($list_mhs as $list)
		{
			//cek strata
			$npm = $this->pkl_model->select_pkl_by_id_pkl($list->pkl_id)->npm;
			$strata = substr($npm,2,1);
			if($strata == 1 || $strata == 5){
				$this->pkl_model->approval_koor_pkl7($list->pkl_id);
			}
			elseif($strata == 0){
				$this->pkl_model->approval_koor_pkl4($list->pkl_id);
			}
		}
		redirect(site_url("/dosen/pkl/pengajuan/koordinator/approve?periode=$periode&id=$id"));
	}

	function pkl_approve_kaprodi()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['pkl'] = $this->pkl_model->get_pkl_mahasiswa_approval_kaprodi($this->session->userdata('userId'));

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/kaprodi/pkl/pkl_approve',$data);
		
		$this->load->view('footer_global');
	}

	function pkl_approve_kaprodi_setujui()
	{
		$status = $this->input->get('status');
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['pkl'] = $this->pkl_model->select_pkl_by_id_pkl($id);
		$data['status'] = $status;

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');
		$this->load->view('dosen/kaprodi/pkl/pkl_approve_ttd',$data);
		$this->load->view('footer_global');
	}

	function pkl_approve_kaprodi_simpan()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);
		$id = $data['id_pengajuan'];
		$status = $data['status'];
		$ttd = $data['ttd'];
		$user_id = $this->session->userdata('userId');

		$this->pkl_model->pkl_approve_setujui($status,$id,$user_id,$ttd);
		redirect(site_url("/dosen/struktural/kaprodi/pkl"));
	}

	//seminar
	function pkl_approve_seminar()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['pkl'] = $this->pkl_model->get_mahasiswa_pkl_bimbingan($this->session->userdata('userId'));

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/pkl/seminar/pkl_seminar_approve',$data);
		
		$this->load->view('footer_global');
	}

	function pkl_approve_seminar_perbaiki()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);

		$id = $this->input->post('seminar_id');
		$status = $this->input->post('status');
		$keterangan = $this->input->post('keterangan')."$#$".$status;
		$this->pkl_model->perbaiki_seminar_pkl($id,$keterangan,$status);
		redirect(site_url("/dosen/pkl/approve-seminar"));
	}

	function pkl_approve_seminar_setujui()
	{
		$status = $this->input->get('status');
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['seminar'] = $this->pkl_model->get_seminar_by_id($id);
		$data['pkl'] = $this->pkl_model->select_pkl_by_id_pkl($data['seminar']->pkl_id);
		$data['status'] = $status;

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/pkl/seminar/pkl_seminar_approve_ttd',$data);
		
		$this->load->view('footer_global');
	}

	function pkl_approve_seminar_save()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);

		$seminar_id = $data['seminar_id'];
		$status = $data['status'];
		$ttd = $data['ttd'];
		$sts = 1;
		$id_user = $this->session->userdata('userId');
		//set seminar status
		$this->pkl_model->update_seminar_pkl($seminar_id,$sts);
		//insert ttd
		$data_approval = array(
			"seminar_id" => $seminar_id,
			"status_slug" => "Dosen Pembimbing",
			"id_user" => $id_user,
			"ttd" => $ttd
		);
		//insert surat
		$data_surat = array(
			"jenis" => 4,
			"id_jenis" => $seminar_id
		);

		$this->pkl_model->input_approval_seminar($data_approval);
		$this->pkl_model->save_surat_pa($data_surat);
		redirect(site_url("/dosen/pkl/approve-seminar"));
	}

	function pkl_approve_seminar_koor()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['seminar'] = $this->pkl_model->get_seminar_mahasiswa_koor($this->session->userdata('userId'));

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/koordinator/pkl/seminar/pkl_seminar_koordinator',$data);
		
		$this->load->view('footer_global');
	}

	function pkl_approve_seminar_tolak()
	{
		$id = $this->input->post('seminar_id');
		$status = $this->input->post('status');
		$keterangan = $this->input->post('keterangan')."$#$".$status;
		$this->pkl_model->perbaiki_seminar_pkl($id,$keterangan,$status);
		redirect(site_url("/dosen/pkl/seminar/koordinator"));
	}

	function pkl_approve_seminar_form()
	{
		$status = $this->input->get('status');
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['seminar'] = $this->pkl_model->get_seminar_by_id($id);
		$data['pkl'] = $this->pkl_model->select_pkl_by_id_pkl($data['seminar']->pkl_id);
		$data['status'] = $status;

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/koordinator/pkl/seminar/pkl_seminar_koordinator_ttd',$data);
		
		$this->load->view('footer_global');
	}
	
	function pkl_approve_seminar_save_koor()
	{
		$id = $this->input->post('seminar_id');
		$status = $this->input->post('status');
		$ttd = $this->input->post('ttd');
		$surat = $this->input->post('surat');
		$surat2 = $this->input->post('surat2');
		$no_surat = $surat."/".$surat2;
		$id_user = $this->session->userdata('userId');
		// echo $no_surat;
		//edit pkl_seminar
		$data=array(
			"status" => "3",
			"no_form" =>$no_surat
		);
		$this->pkl_model->approve_koor_seminar($id,$data);

		//edit staff_surat
		$this->pkl_model->update_seminar_staff_surat_pkl($id,$no_surat);

		//insert ttd
		$data_approval = array(
			"seminar_id" => $id,
			"status_slug" => "Koordinator",
			"id_user" => $id_user,
			"ttd" => $ttd
		);
		$this->pkl_model->input_approval_seminar($data_approval);

		redirect(site_url("/dosen/pkl/seminar/koordinator"));
	}

	function kajur_approve_pkl_seminar_list()
	{
	
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['seminar'] = $this->pkl_model->get_mahasiswa_pkl_seminar_kajur($this->session->userdata('userId'));
		// $data['pkl'] = $this->pkl_model->select_pkl_by_id_pkl($data['seminar']->pkl_id);
		// $data['status'] = $status;

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/kajur/pkl/seminar/pkl_seminar_approve_kajur',$data);
		
		$this->load->view('footer_global');
	}

	function kajur_approve_pkl_seminar_form()
	{
		$status = $this->input->get('status');
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['seminar'] = $this->pkl_model->get_seminar_by_id($id);
		$data['pkl'] = $this->pkl_model->select_pkl_by_id_pkl($data['seminar']->pkl_id);
		$data['status'] = $status;

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/kajur/pkl/seminar/pkl_seminar_approve_kajur_ttd',$data);
		
		$this->load->view('footer_global');
	}

	function kajur_approve_pkl_seminar_save()
	{
		// $data = $this->input->post();
		// echo "<pre>";
		// print_r($data);

		$id = $this->input->post('seminar_id');
		$status = $this->input->post('status');
		$ttd = $this->input->post('ttd');
		$id_user = $this->session->userdata('userId');

		//edit pkl_seminar
		$data=array(
			"status" => "4",
		);
		$this->pkl_model->approve_koor_seminar($id,$data);

		//insert ttd
		$data_approval = array(
			"seminar_id" => $id,
			"status_slug" => "Ketua Jurusan",
			"id_user" => $id_user,
			"ttd" => $ttd
		);
		$this->pkl_model->input_approval_seminar($data_approval);

		//send email
		// $pkl_id = $this->pkl_model->get_seminar_by_id($id)->pkl_id;
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
		// 			http://apps.fmipa.unila.ac.id/simipa/approval/seminar?token=
		// 			<br><br>
		// 			Terimakasih.
					
		// 			");
					
		// 			if (!$this->email->send()) {  
		// 				echo $this->email->print_debugger();  
		// 			}else{  
						
		// 			}   
		// 	}

		redirect(site_url("/dosen/struktural/pkl/approve-seminar-pkl"));

	}

	function pkl_approve_kaprodi_seminar()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['seminar'] = $this->pkl_model->get_mahasiswa_pkl_seminar_kaprodi($this->session->userdata('userId'));
		// $data['pkl'] = $this->pkl_model->select_pkl_by_id_pkl($data['seminar']->pkl_id);
		// $data['status'] = $status;

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/kaprodi/pkl/seminar/pkl_approve_seminar_kaprodi',$data);
		
		$this->load->view('footer_global');
	}

	function pkl_approve_kaprodi_seminar_form()
	{
		$status = $this->input->get('status');
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['seminar'] = $this->pkl_model->get_seminar_by_id($id);
		$data['pkl'] = $this->pkl_model->select_pkl_by_id_pkl($data['seminar']->pkl_id);
		$data['status'] = $status;

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/kaprodi/pkl/seminar/pkl_approve_seminar_kaprodi_ttd',$data);
		
		$this->load->view('footer_global');
	}

	function pkl_approve_kaprodi_seminar_save()
	{
		// $data = $this->input->post();
		// echo "<pre>";
		// print_r($data);

		$id = $this->input->post('seminar_id');
		$status = $this->input->post('status');
		$ttd = $this->input->post('ttd');
		$id_user = $this->session->userdata('userId');

		//edit pkl_seminar
		$data=array(
			"status" => "4"
		);
		$this->pkl_model->approve_koor_seminar($id,$data);

		//insert ttd
		$data_approval = array(
			"seminar_id" => $id,
			"status_slug" => "Ketua Program Studi",
			"id_user" => $id_user,
			"ttd" => $ttd
		);
		$this->pkl_model->input_approval_seminar($data_approval);

		//send email
		// $pkl_id = $this->pkl_model->get_seminar_by_id($id)->pkl_id;
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
		// 			http://apps.fmipa.unila.ac.id/simipa/approval/seminar?token=
		// 			<br><br>
		// 			Terimakasih.
					
		// 			");
					
		// 			if (!$this->email->send()) {  
		// 				echo $this->email->print_debugger();  
		// 			}else{  
						
		// 			}   
		// 	}

		redirect(site_url("/dosen/struktural/pkl/approve-seminar-pkl"));
	}

	function komponen_nilai_pkl_home()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$jurusan = $this->user_model->get_dosen_data($this->session->userdata('userId'))->jurusan;
		$data['komponen'] = $this->pkl_model->get_komponen_kajur($jurusan);
		$data['cek'] = $this->pkl_model->cek_komp_seminar($jurusan);

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/kajur/pkl/seminar/pkl_seminar_nilai_tambah_list',$data);
		
		$this->load->view('footer_global');	
	}


	function komponen_nilai_pkl()
	{
		$aksi = $this->input->get('aksi');
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		
		if($aksi == "ubah"){
			$id = $this->input->get('id');
			$komponen = $this->pkl_model->get_pkl_seminar_komponen2($id);
			$data_komponen = $komponen[0];
			$data['aksi'] = $aksi;
			$data['ida'] = $id;
		}
		else{
			$data_komponen = array(
				"id" => null,
				"jurusan" => null,
				"bobot" => null,
				"status" => 1
			);
			$data['aksi'] = null;
			$data['ida'] = null;
		}
		// $komponen = $this->pkl_model->get_pkl_seminar_komponen($this->session->userdata('userId'));

		// if(!empty($komponen)){
		// 	$data_komponen = $komponen[0];
		// }
		// else{
		// 	$data_komponen = array(
		// 		"id" => null,
		// 		"jurusan" => null,
		// 		"bobot" => null
		// 	);
		// }

		$data['komponen'] = $data_komponen;
		// print_r($data);
		
		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/kajur/pkl/seminar/pkl_seminar_nilai_tambah',$data);
		
		$this->load->view('footer_global');
	}

	function komponen_nilai_pkl_add()
	{
		$id = $this->input->post('jurusan');
		$jenis = $this->input->post('jenis');
		$komponen = $this->input->post('komponen');
		$persentase = $this->input->post('persentase');
		$aksi = $this->input->post('aksi');
		$ida = $this->input->post('ida');
		// echo $id;
		
		if($id == ""){
			$user = $this->session->userdata('userId');
			$jurusan = $this->user_model->get_dosen_jur($user)->id_jurusan;
			$data_komponen = array(
				"jurusan" => $jurusan,
				"bobot" => "0#0",
				"status" => "1"
			);
			$id = $this->pkl_model->insert_seminar_komponen($data_komponen);
			$aksi = "ubah";
			$ida = $id;
		}

		$data = array(
			"komponen" => $id,
			"unsur" => $jenis,
			"attribut" => $komponen,
			"persentase" => $persentase 
		);

		$jml1 = $this->pkl_model->jml_persen_meta($id)->jml;
		$jml = $jml1 + $persentase;
		if($jml >= 101){
			redirect(site_url("dosen/struktural/pkl/komponen-nilai-pkl/form?aksi=$aksi&id=$ida&status=komponen"));
		}
		else{
			$this->pkl_model->input_seminar_komponen_meta($data);
			redirect(site_url("dosen/struktural/pkl/komponen-nilai-pkl/form?aksi=$aksi&id=$ida"));
		}
	}

	function komponen_nilai_pkl_delete()
	{
		$id = $this->input->post('komponen_id');
		$aksi = $this->input->post('aksi');
		$ida = $this->input->post('ida');
		$this->pkl_model->delete_seminar_komponen_meta($id);
		redirect(site_url("dosen/struktural/pkl/komponen-nilai-pkl/form?aksi=$aksi&id=$ida"));
	}

	function komponen_nilai_pkl_nonaktif()
	{
		$id = $this->input->post('id');

		$this->pkl_model->nonaktif_seminar_komponen($id);
		redirect(site_url("dosen/struktural/pkl/komponen-nilai-pkl"));		
	}

	function komponen_nilai_pkl_edit()
	{
		$id = $this->input->post('id_meta');
		$komponen = $this->input->post('komponen');
		$persentase = $this->input->post('persentase');
		$aksi = $this->input->post('aksi');
		$ida = $this->input->post('ida');

		$data = array(
			"attribut" => $komponen,
			"persentase" => $persentase 
		);

		$this->pkl_model->update_seminar_komponen_meta($id,$data);
		redirect(site_url("dosen/struktural/pkl/komponen-nilai-pkl/form?aksi=$aksi&id=$ida"));
	}

	function komponen_nilai_pkl_save()
	{
		$aksi = $this->input->post('aksi');

		if($aksi == "ubah"){
			$id = $this->input->post('id');
			$pbb = $this->input->post('pbb');
			$pbl = $this->input->post('pbl');

			$jml = $pbb + $pbl;
			$jml2 = $this->pkl_model->jml_persen_meta($id)->jml;
			if($jml != 100 || $jml2 != 100){
				redirect(site_url("/dosen/struktural/pkl/komponen-nilai-pkl/form?aksi=$aksi&id=$id&status=pembimbing"));
			}
			else{
				$bobot = $pbb."#".$pbl;
				$this->pkl_model->update_seminar_komponen_bobot($id,$bobot);
				redirect(site_url("/dosen/struktural/pkl/komponen-nilai-pkl/form?aksi=$aksi&id=$id&status=berhasil"));
			}
		}
		else{
			$data = $this->input->post(); 
			$pbb = $data['pbb'];
			$pbl = $data['pbl'];

			$user = $this->session->userdata('userId');
			$jurusan = $this->user_model->get_dosen_jur($user)->id_jurusan;
			$data_komponen = array(
				"jurusan" => $jurusan,
				"bobot" => "$pbb#$pbl",
				"status" => "1"
			);
			$id = $this->pkl_model->insert_seminar_komponen($data_komponen);
			$aksi = "ubah";

			$jml = $pbb + $pbl;
			$jml2 = $this->pkl_model->jml_persen_meta($id)->jml;
			if($jml != 100 || $jml2 != 100){
				redirect(site_url("/dosen/struktural/pkl/komponen-nilai-pkl/form?aksi=$aksi&id=$id&status=pembimbing"));
			}
			else{
				redirect(site_url("/dosen/struktural/pkl/komponen-nilai-pkl/form?aksi=$aksi&id=$id&status=berhasil"));
			}
		}
		

	}

	function pkl_approve_seminar_nilai()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['seminar'] = $this->pkl_model->get_mahasiswa_seminar_nilai_pbb($this->session->userdata('userId'));
		$data['cek_nilai'] = $this->pkl_model->pkl_cek_nilai_ada($this->session->userdata('userId'));

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/pkl/seminar/nilai/pkl_seminar_approve_nilai',$data);
		
		$this->load->view('footer_global');
	}

	function pkl_approve_seminar_nilai_form()
	{
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['seminar'] = $this->pkl_model->get_seminar_by_id($id);
		$data['pkl'] = $this->pkl_model->select_pkl_by_id_pkl($data['seminar']->pkl_id);

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/pkl/seminar/nilai/pkl_seminar_approve_nilai_form',$data);
		
		$this->load->view('footer_global');

	}

	function pkl_approve_seminar_nilai_save()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);

		$id = $data['seminar_id'];
		$jml_s = $data['jml_seminar'];
		$jml_l = $data['jml_laporan'];
		$seminar = $data['seminar'];
		$id_seminar = $data['id_seminar'];
		$laporan = $data['laporan'];
		$id_laporan = $data['id_laporan'];
		$ttd = $data['ttd'];
		$user = $this->session->userdata('userId');
		$komponen = $data['komponen'];

		for($s=1;$s<=$jml_s;$s++){
			$data_s = array(
				"komponen_id" => $id_seminar[$s],
				"seminar_id"=> $id,
				"nilai"=> $seminar[$s],
				"status_slug"=> "Dosen Pembimbing",
			);
			$this->pkl_model->input_nilai_seminar($data_s);
		}

		for($l=1;$l<=$jml_l;$l++){
			$data_l = array(
				"komponen_id" => $id_laporan[$l],
				"seminar_id"=> $id,
				"nilai"=> $laporan[$l],
				"status_slug"=> "Dosen Pembimbing",
			);
			$this->pkl_model->input_nilai_seminar($data_l);
		}

		//ttd
		$data_ttd = array(
			"status_slug" => "Dosen Pembimbing",
			"id_user" => $user,
			"seminar_id" => $id,
			"ttd" => $ttd,
		);
		$this->pkl_model->insert_approval_nilai_seminar($data_ttd);

		//status > 7
		$status = 7;
		$this->pkl_model->update_seminar_pkl($id,$status);

		redirect(site_url("/dosen/pkl/approve-nilai-seminar"));
	}

	function pkl_approve_seminar_nilai_koor()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['seminar'] = $this->pkl_model->get_mahasiswa_seminar_nilai_koor($this->session->userdata('userId'));

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/koordinator/pkl/nilai/pkl_seminar_koordinator_nilai',$data);
		
		$this->load->view('footer_global');
	}

	function pkl_approve_seminar_nilai_koor_form()
	{
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['seminar'] = $this->pkl_model->get_seminar_by_id($id);
		$data['pkl'] = $this->pkl_model->select_pkl_by_id_pkl($data['seminar']->pkl_id);

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/koordinator/pkl/nilai/pkl_seminar_koordinator_nilai_ttd',$data);
		
		$this->load->view('footer_global');
	}

	function pkl_approve_seminar_nilai_koor_save()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);	

		$seminar_id = $data['seminar_id'];
		$pkl_id = $data['pkl_id'];
		$nilai_pbb = $data['nilai_pbb'];
		$nilai_pbl = $data['nilai_pbl'];

		$koor = $data['pengurangan_koor']; 
		$ttd = $data['ttd'];
		$user = $this->session->userdata('userId');

		//nilai pb
		$data_pb = array(
			"seminar_id" => $seminar_id,
			"pkl_id" => $pkl_id,
			"status_slug" => "Dosen Pembimbing",
			"nilai" => $nilai_pbb
		);
		$this->pkl_model->insert_nilai_pkl($data_pb);

		//nilai pbl
		$data_pbl = array(
			"seminar_id" => $seminar_id,
			"pkl_id" => $pkl_id,
			"status_slug" => "Pembimbing Lapangan",
			"nilai" => $nilai_pbl
		);
		$this->pkl_model->insert_nilai_pkl($data_pbl);

		//pengurangan koor
		$data_k = array(
			"seminar_id" => $seminar_id,
			"pkl_id" => $pkl_id,
			"status_slug" => "Koordinator",
			"nilai" => $koor
		);
		$this->pkl_model->insert_nilai_pkl($data_k);

		//ttd koor
		$data_k_approve = array(
			"status_slug" => "Koordinator",
			"id_user" => $user,
			"seminar_id" => $seminar_id,
			"ttd" => $ttd,
		);
		$this->pkl_model->insert_approval_nilai_seminar($data_k_approve);

		//status > 8
		$status = 8;
		$this->pkl_model->update_seminar_pkl($seminar_id,$status);

		redirect(site_url("/dosen/pkl/seminar-nilai/koordinator"));
	}

	function kajur_approve_pkl_seminar_nilai()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['seminar'] = $this->pkl_model->get_mahasiswa_seminar_nilai_kajur($this->session->userdata('userId'));

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/kajur/pkl/seminar/pkl_seminar_nilai_kajur',$data);
		
		$this->load->view('footer_global');
	}

	function kajur_approve_pkl_seminar_nilai_form()
	{
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['seminar'] = $this->pkl_model->get_seminar_by_id($id);
		$data['pkl'] = $this->pkl_model->select_pkl_by_id_pkl($data['seminar']->pkl_id);

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/kajur/pkl/seminar/pkl_seminar_nilai_kajur_ttd',$data);
		
		$this->load->view('footer_global');
	}

	function kajur_approve_pkl_seminar_nilai_save()
	{
		$ttd = $this->input->post('ttd');
		$seminar_id = $this->input->post('seminar_id');
		$user = $this->session->userdata('userId');

		//ttd koor
		$data_kajur = array(
			"status_slug" => "Ketua Jurusan",
			"id_user" => $user,
			"seminar_id" => $seminar_id,
			"ttd" => $ttd,
		);
		$this->pkl_model->insert_approval_nilai_seminar($data_kajur);

		//status > 8
		$status = 9;
		$this->pkl_model->update_seminar_pkl($seminar_id,$status);

		redirect(site_url("/dosen/struktural/pkl/approve-seminar-nilai-pkl"));
	}

	//rekap pkl
	function pkl_rekap_mahasiswa()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/pkl/rekap/pkl_rekap_mahasiswa');
		
		$this->load->view('footer_global');
	}

	function pkl_rekap_mahasiswa_detail()
	{
		$strata = $this->input->get('strata');
		$angkatan = $this->input->get('angkatan');

		if($strata == "d3"){
			$npm1 = 0;
			$npm2 = 0;
		}
		elseif($strata == "s1"){
			$npm1 = 1;
			$npm2 = 5;
		}

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['mahasiswa'] =  $this->pkl_model->get_mahasiswa_angkatan($this->session->userdata('userId'),$angkatan,$npm1,$npm2);
		
		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/pkl/rekap/pkl_rekap_mahasiswa_detail',$data);
		
		$this->load->view('footer_global');
	}

	function pkl_rekap_mahasiswa_detail_mahasiswa()
	{
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['pkl'] = $this->pkl_model->select_pkl_by_id_pkl($id);
		
		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/pkl/rekap/pkl_rekap_mahasiswa_detail_pkl',$data);
		
		$this->load->view('footer_global');

	}

	function pkl_rekap_periode()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['periode'] = $this->pkl_model->get_periode_pkl_rekap($this->session->userdata('userId'));

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/pkl/rekap/pkl_rekap_periode',$data);
		
		$this->load->view('footer_global');
	}

	function pkl_rekap_periode_timeline()
	{
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['meta'] = $this->pkl_model->get_pkl_periode_meta($id);
		$data['periode'] = $this->pkl_model->get_pkl_kajur_by_id($id);

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/pkl/rekap/pkl_rekap_periode_timeline',$data);
		
		$this->load->view('footer_global');
	}

	function pkl_rekap_periode_detail()
	{
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['pkl'] = $this->pkl_model->get_pkl_by_periode($id);

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/pkl/rekap/pkl_rekap_periode_detail',$data);
		
		$this->load->view('footer_global');
	}

	function pkl_rekap_mahasiswa_detail_seminar()
	{
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['pkl'] = $this->pkl_model->select_pkl_by_id_pkl($id);
		$data['seminar'] = $this->pkl_model->get_pkl_seminar_by_pkl_id($id);

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/pkl/rekap/pkl_rekap_periode_detail_seminar',$data);
		
		$this->load->view('footer_global');
	}

	function pkl_rekap_pembimbing()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['periode'] = $this->pkl_model->get_periode_pkl_rekap($this->session->userdata('userId'));

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/pkl/rekap/pkl_rekap_pembimbing',$data);
		
		$this->load->view('footer_global');
	}

	function pkl_rekap_pembimbing_detail()
	{
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['dosen'] = $this->pkl_model->get_dosen_jurusan($this->session->userdata('userId'));
		$data['pkl'] = $this->pkl_model->get_pkl_by_periode($id);

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/pkl/rekap/pkl_rekap_pembimbing_detail',$data);
		$this->load->view('footer_global');
	}

	function pkl_rekap_pembimbing_detail_bimbingan()
	{
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['lokasi'] = $this->pkl_model->get_lokasi_by_pembimbing_distinct($id);
		$data['id'] = $id;

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/pkl/rekap/pkl_rekap_pembimbing_detail_bimbingan',$data);
		$this->load->view('footer_global');
	}

	function bebas_lab()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['lab'] = $this->layanan_model->get_lab_kalab_user($this->session->userdata('userId'))->jurusan_unit;
		$data['form'] = $this->layanan_model->get_lab_kalab_form($data['lab']);

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/kalab/verifikasi_lab',$data);
		
		$this->load->view('footer_global');
	}

	function bebas_lab_approve()
	{
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['form'] = $this->layanan_model->get_kalab_bebas_lab_meta($id);

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/kalab/verifikasi_lab_approve',$data);
		
		$this->load->view('footer_global');
	}

	function bebas_lab_simpan()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);	

		$id_meta = $data['id_meta'];
		$id_bebas = $data['id_bebas_lab'];
		$ttd = $data['ttd'];
		$id_layanan = $data['id_layanan'];

		//update status dan ttd
		$kalab = array(
			"status" => 2,
			"ttd_kalab" => $ttd
		);
		$this->layanan_model->approve_kalab($id_meta,$kalab);

		//cek
		$cek = $this->layanan_model->cek_bebas_lab_status($id_bebas);
		if(empty($cek)){
			$status = 0;
			$this->layanan_model->update_bebas_lab_status($id_bebas,$status);

			//layanan fakultas status > 1 & add tingkat
			$tingkat = 2; // id approve wd 1
			$this->layanan_model->update_status_layanan($id_layanan,$status);
			$this->layanan_model->update_tingkat_layanan($id_layanan,$tingkat);
		}

		redirect(site_url("/dosen/bebas-lab/pengajuan"));
	}

	//layanan akademik wd 1
	function wd_layanan_akademik()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['form'] = $this->layanan_model->get_approval_wd1_fakultas($this->session->userdata('userId'));

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/approval/verifikasi_pengajuan',$data);
		
		$this->load->view('footer_global');
	}

	function wd_layanan_akademik_approve()
	{
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['form'] = $this->layanan_model->get_bebas_lab_by_id($id);

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/wd1/verifikasi_lab_approve',$data);
		
		$this->load->view('footer_global');
	}

	function wd_layanan_akademik_simpan()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);
		$id = $data['id_bebas_lab'];
		$ttd = $data['ttd'];
		$id_fak_mhs = $data['id_fak_mhs'];

		//update bebas lab
		$this->layanan_model->approve_bebas_lab_wd($id,$ttd);

		//get fak mhs
		$fak_mhs = $this->layanan_model->get_form_mhs_id($id_fak_mhs);
		//input surat
		//1 = akademik, 2 = umum, 3 = kemahasiswaan
		$surat = array(
			"layanan" => 1,
			"id_layanan_fakultas" => $fak_mhs->id_layanan_fakultas,
			"id_layanan_fakultas_mahasiswa" => $id_fak_mhs,
		);
		$this->layanan_model->input_surat_fakultas($surat);
		redirect(site_url("/dosen/wd-layanan-akademik/pengajuan"));
	}

	function layanan_fak_mhs()
	{
		$seg = $this->uri->segment(3);

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();

		switch($seg){
			case "dekan": //1
			$data['form'] = $this->layanan_model->get_approval_struktural_fakultas($this->session->userdata('userId'),1);
			break;
			case "wd1": //2
			$data['form'] = $this->layanan_model->get_approval_struktural_fakultas($this->session->userdata('userId'),2);
			break;
			case "wd2": // 3
			$data['form'] = $this->layanan_model->get_approval_struktural_fakultas($this->session->userdata('userId'),3);
			break;
			case "wd3": //4
			$data['form'] = $this->layanan_model->get_approval_struktural_fakultas($this->session->userdata('userId'),4);
			break;
			// case "kabag": //5
			// $data['form'] = $this->layanan_model->get_approval_struktural_fakultas($this->session->userdata('userId'),5);
			// break;
			// case "kasubag-akademik": //6
			// $data['form'] = $this->layanan_model->get_approval_struktural_fakultas($this->session->userdata('userId'),6);
			// break;
			// case "kasubag-umum": //7
			// $data['form'] = $this->layanan_model->get_approval_struktural_fakultas($this->session->userdata('userId'),7);
			// break;
			// case "kasubag-kepegawaian": //8
			// $data['form'] = $this->layanan_model->get_approval_struktural_fakultas($this->session->userdata('userId'),8);
			// break;
			// case "kasubag-kemahasiswaan": //9
			// $data['form'] = $this->layanan_model->get_approval_struktural_fakultas($this->session->userdata('userId'),9);
			// break;
			case "ketua-jurusan": //10
			$data['form'] = $this->layanan_model->get_approval_kajur_fakultas($this->session->userdata('userId'));
			break;
			case "kaprodi-s3": //10
			$data['form'] = $this->layanan_model->get_approval_kaprodi3_fakultas($this->session->userdata('userId'));
			break;
			case "sekjur": //11
			$data['form'] = $this->layanan_model->get_approval_sekjur_fakultas($this->session->userdata('userId'));
			break;
			case "kaprodi": //12
			$data['form'] = $this->layanan_model->get_approval_kaprodi_fakultas($this->session->userdata('userId'));
			break;
			// case "perpustakaan": //13
			// $data['form'] = $this->layanan_model->get_approval_perpustakaan_fakultas($this->session->userdata('userId'));
			// break;
			case "pembimbing-akademik": // 15
			$data['form'] = $this->layanan_model->get_approval_pa_fakultas($this->session->userdata('userId'));
			break;
			case "pembimbing-ta": // 16
			$data['form'] = $this->layanan_model->get_approval_pbb_fakultas($this->session->userdata('userId'));
			break;
			case "kalab": // 17
			$data['form'] = $this->layanan_model->get_approval_kalab_fakultas($this->session->userdata('userId'));
			break;
		}
		
		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/approval/verifikasi_pengajuan',$data);
		
		$this->load->view('footer_global');
	}

	function layanan_fak_mhs_approve()
	{
		$seg = $this->uri->segment(3);
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);

		switch($seg){
			case "dekan":
			$id_approver = 1;
			break;
			case "wd1":
			$id_approver = 2;
			break;
			case "wd2":
			$id_approver = 3;
			break;
			case "wd3":
			$id_approver = 4;
			break;
			// case "kabag":
			// $id_approver = 5;
			// break;
			// case "kasubag-akademik":
			// $id_approver = 6;
			// break;
			// case "kasubag-umum":
			// $id_approver = 7;
			// break;
			// case "kasubag-kepegawaian":
			// $id_approver = 8;
			// break;
			// case "kasubag-kemahasiswaan":
			// $id_approver = 9;
			// break;
			case "ketua-jurusan":
			$id_approver = 10;
			break;
			case "kaprodi-s3":
			$id_approver = 10;
			break;
			case "sekjur":
			$id_approver = 11;
			break;
			case "kaprodi":
			$id_approver = 12;
			break;
			// case "perpustakaan":
			// $id_approver = 13;
			// break;
			case "pembimbing-akademik":
			$id_approver = 15;
			break;
			case "pembimbing-ta":
			$id_approver = 16;
			break;
			case "kalab":
			$id_approver = 17;
			break;
			
		}

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['form'] = $this->layanan_model->get_form_mhs_id($id);
		$data['id_approver'] = $id_approver;

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/approval/verifikasi_approve',$data);
		
		$this->load->view('footer_global');
	}

	function layanan_fak_mhs_save()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);
		$seg = $this->uri->segment(3);
		$id_lay = $data['id_lay_fak'];
		$ttd = $data['ttd'];
		$approver = $data['id_approver'];
		$jenis = $data['jenis'];
		$approval = array(
			"id_layanan_mahasiswa" => $id_lay,
			"approver_id" => $approver ,
			"insert_by" => $this->session->userdata('userId'),
			"ttd" => $ttd
		);
		if($approver > 9){
			$this->layanan_model->insert_approval_layanan($approval);
		}
		else{
			//update
			$data_update=array(
				"insert_by"=>$this->session->userdata('userId'),
				"ttd" => $ttd
			);
			$this->layanan_model->update_approval_layanan($id_lay,$approver,$data_update);
		}
		
		//hapus tingkat
		$tingkat = $this->layanan_model->get_form_mhs_id($id_lay)->tingkat;
		if (strpos($tingkat, '#') !== false) {
			$approver2 = $approver."#";
		}else{
			$approver2 = $approver;
		}	
		$new_tingkat = str_replace("$approver2","",$tingkat);		

		$this->layanan_model->update_tingkat_layanan($id_lay,$new_tingkat);

		//ubah status menjadi 1
		$cek_tingkat = $this->layanan_model->get_form_mhs_id($id_lay);
		if($tingkat < 9){
			if($cek_tingkat->tingkat == "" || $cek_tingkat->tingkat == null){
				$status = 1;
				$this->layanan_model->update_status_layanan($id_lay,$status);
			}
		}

		//if bebas laboratorium
		if($cek_tingkat->id_layanan_fakultas == 2){
			//id bebas lab
			$id_lab = $this->layanan_model->get_id_bebas_lab($id_lay)->id_bebas_lab;

			//update bebas lab
			$this->layanan_model->approve_bebas_lab_wd($id_lab,$ttd);	
		}
		//if surat izin pelaksanaan penelitian
		if($cek_tingkat->id_layanan_fakultas == 39){
			if($cek_tingkat->tingkat == "" || $cek_tingkat->tingkat == null){
				$status = 2;
				$this->layanan_model->update_status_layanan($id_lay,$status);
			}
		}
		//if beasiswa lengkap
		if($cek_tingkat->id_layanan_fakultas == 26){
			if($cek_tingkat->tingkat == "" || $cek_tingkat->tingkat == null){
				$status = 2;
				$this->layanan_model->update_status_layanan($id_lay,$status);
			}
			//update status beasiswa mahasiswa
			$beasiswa = $this->layanan_model->get_beasiswa_by_layanan($cek_tingkat->id);
			$beasiswa_dt = array(
				"status" => 2
			);
			$this->layanan_model->update_beasiswa_mhs($beasiswa->id,$beasiswa_dt);
		}
		//if form hapus matkul
		if($cek_tingkat->id_layanan_fakultas == 4 && $approver == 2){
			$catatan = $data['catatan'];
			$catatan = array(
				"keterangan" => $catatan
			);
			$this->layanan_model->update_layanan_fak_mhs($id_lay,$catatan);
		}

		redirect(site_url("/dosen/approval/$seg"));		

	}

	//wd3 prestasi
	function prestasi()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['tahun'] = $this->layanan_model->get_tahun_prestasi_wd();

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/wd3/prestasi',$data);
		
		$this->load->view('footer_global');
	}

	function prestasi_detail()
	{
		$seg = $this->uri->segment(3);
		if($seg == "akademik"){
			$seg = "Akademik";
		}elseif($seg == "non-akademik"){
			$seg = "Non Akademik";
		}
		$tahun = $this->input->get('tahun');
		$jurusan = $this->input->get('jurusan');
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['id_prestasi'] = $this->layanan_model->get_prestasi_detail($tahun,$seg,$jurusan);

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/wd3/prestasi_detail',$data);
		
		$this->load->view('footer_global');
	}

	//wd3 beasiswa
	function beasiswa()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['beasiswa'] = $this->layanan_model->get_beasiswa_wd();

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/wd3/beasiswa',$data);
		
		$this->load->view('footer_global');
	}

	function tambah_beasiswa()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/wd3/tambah_beasiswa');
		
		$this->load->view('footer_global');
	}

	function simpan_beasiswa()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);
		$beasiswa = array(
			"nama" => $data['nama'],
			"tahun_akademik" => $data['ta'],
			"tahun" => $data['tahun'],
			"semester" => $data['semester'],
			"penyelenggara" => $data['penyelenggara'] 
		);
		//insert beasiswa
		$this->layanan_model->tambah_beasiswa($beasiswa);
		redirect(site_url("/dosen/beasiswa"));	
	}

	function hapus_beasiswa()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);
		$this->layanan_model->delete_beasiswa($data['id_beasiswa']);
		redirect(site_url("/dosen/beasiswa"));
	}

	function edit_beasiswa()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);
		$beasiswa = array(
			"nama" => $data['nama'],
			"tahun_akademik" => $data['ta'],
			"tahun" => $data['tahun'],
			"semester" => $data['semester'],
			"penyelenggara" => $data['penyelenggara'] 
		);
		//edit beasiswa
		$this->layanan_model->edit_beasiswa($data['id'],$beasiswa);
		redirect(site_url("/dosen/beasiswa"));	
	}

	function aktif_beasiswa()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);
		if($data['aksi'] == 'aktif'){
			$status = 1;
		}elseif($data['aksi'] == 'nonaktif'){
			$staus = 0;
		}
		$beasiswa = array(
			"status" => $status
		);
		//edit beasiswa
		$this->layanan_model->edit_beasiswa($data['id_beasiswa'],$beasiswa);
		redirect(site_url("/dosen/beasiswa"));	
	}

	function detail_beasiswa()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['beasiswa'] = $this->layanan_model->get_beasiswa_mhs();

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/wd3/list_beasiswa',$data);
		
		$this->load->view('footer_global');
	}

	function detail_beasiswa_pendaftar()
	{
		$seg = $this->uri->segment(4);
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['pendaftar'] = $this->layanan_model->get_pendaftar_beasiswa($seg);
		$data['beasiswa'] = $this->layanan_model->get_beasiswa_by_id($seg);
		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/wd3/list_pendaftar',$data);
		
		$this->load->view('footer_global');
	}

	function aksi_beasiswa()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);
		$id = $data['id_beasiswa'];
		$aksi = $data['aksi'];
		$seg = $data['seg'];
		if($aksi == "lulus"){
			$sts = 4;
		}elseif($aksi == "tolak"){
			$sts = 3;
		}
		$beasiswa_dt = array(
			"status" => $sts
		);
		//update beasiswa mahasiswa
		$this->layanan_model->update_beasiswa_mhs($id,$beasiswa_dt);
		redirect(site_url("/dosen/beasiswa-detail/pendaftar/$seg"));	
	}

	function mhs_beasiswa()
	{
		$thn = $this->input->post();
		// echo "<pre>";
		// print_r($thn);

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		
		if(!empty($thn)){
			$data['beasiswa'] = $this->layanan_model->get_beasiswa_by_tahun($thn['tahun']);
			$data['post'] = 1;
			$data['tahun'] = $thn['tahun'];
		}else{
			$data['beasiswa'] = null;
			$data['post'] = 0;
		}

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/wd3/mhs_beasiswa',$data);
		
		$this->load->view('footer_global');
	}

	function mhs_beasiswa_detail()
	{
		$jur = $this->input->get('jurusan');
		$bea = $this->input->get('beasiswa');
		$sts = $this->input->get('status');
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		if($sts == 'lulus'){
			$data['beasiswa'] = $this->layanan_model->get_mhs_beasiswa_jurusan($jur,$bea);
		}elseif($sts == 'tolak'){
			$data['beasiswa'] = $this->layanan_model->get_mhs_beasiswa_jurusan_tolak($jur,$bea);
		}
		
		$data['bea'] = $this->layanan_model->get_beasiswa_by_id($bea);

		$this->load->view('dosen/wd3/mhs_beasiswa_detail',$data);
		
		$this->load->view('footer_global');
	}

	function daftar_lk()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['lk'] = $this->user_model->get_lk_all();

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/wd3/daftar_lk',$data);
		
		$this->load->view('footer_global');
	}

	function tambah_lk()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/wd3/tambah_lk');
		
		$this->load->view('footer_global');
	}

	function simpan_lk()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($thn);
		$lk = array(
			"nama_lk" => $data['nama'],
			"jurusan_lk" => $data['tingkat']
		);
		$this->layanan_model->insert_lk($lk);
		redirect(site_url("/dosen/lk/daftar-lk?status=sukses"));	
	}

	function edit_lk()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);
		$lk = array(
			"nama_lk" => $data['nama'],
			"jurusan_lk" => $data['tingkat']
		);
		$id = $data['id'];
		$this->layanan_model->update_lk($id,$lk);
		redirect(site_url("/dosen/lk/daftar-lk?status=sukses"));
	}

	function hapus_lk()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);
		$this->layanan_model->hapus_lk($data);
		redirect(site_url("/dosen/lk/daftar-lk?status=sukses"));
	}

	function detail_lk()
	{
		$id = $this->input->get('id');
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$data['lk'] = $this->layanan_model->get_lk_by_id($id);
		$data['periode'] = $this->layanan_model->get_periode_lk($id);

		$this->load->view('dosen/wd3/detail_lk',$data);
		
		$this->load->view('footer_global');
	}

	function verif_lk()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);	
		$lk = array(
			"verifikasi" => 1
		);
		$id = $data['id_lk'];
		$periode = $data['periode'];
		$this->layanan_model->verif_lk($periode,$id,$lk);
		redirect(site_url("/dosen/lk/detail-lk?id=".$id));
	}

	function progja()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');
		$data['progja'] = $this->layanan_model->get_progja_approval();
		$this->load->view('dosen/wd3/progja',$data);
		
		$this->load->view('footer_global');
	}

	function aksi_prop()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);	
		if($data['aksi'] == 'setuju'){
			$status = 2;
			$this->layanan_model->update_status_lk_proposal($data['id'],$status);
		}else{
			$prop = array(
				"status" => '-1',
				"keterangan" => $data['keterangan']
			);
			$this->layanan_model->update_lk_proposal($data['id'],$prop);
		}
		redirect(site_url("/dosen/lk/progja"));
	}

	function rekap_progja()
	{
		$thn = $this->input->post();
		// echo "<pre>";
		// print_r($thn);

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		
		if(!empty($thn)){
			$data['lk'] = $this->user_model->get_lk_all();
			$data['post'] = 1;
			$data['ta'] = $thn['ta'];
		}else{
			$data['lk'] = null;
			$data['post'] = 0;
			$data['ta'] = 0;
		}

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/wd3/rekap_progja',$data);
		
		$this->load->view('footer_global');
	}

	function rekap_progja_detail()
	{
		$periode = $this->input->get('periode');
		$lk = $this->input->get('lk');
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');
		$data['progja'] = $this->layanan_model->get_lk_proposal_lk_periode($lk,$periode);
		$this->load->view('dosen/wd3/rekap_progja_detail',$data);
		
		$this->load->view('footer_global');
	}

	function rekap_layanan()
	{
		$date = $this->input->post();
		// echo "<pre>";
		// print_r($date);
		$jns = $this->uri->segment(3);
		if($jns == 'umum-keuangan'){
			$jns = 'umum dan keuangan';
		}
		
		$get_form = $this->layanan_model->get_form_by_jenis($jns);
		$form_id = array();
		$i = 0;
		foreach($get_form as $form){
			$form_id[$i] = $form->id_layanan_fakultas;
			$i++; 
		}

		if(!empty($date)){
			$data['form'] = $form_id;
			$data['post'] = 1;
			$data['awal'] = $date['awal'];
			$data['akhir'] = $date['akhir'];
		}else{
			$data['form'] = null;
			$data['post'] = 0;
			$data['awal'] = null;
			$data['akhir'] = null;
		}

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
	
		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');
		
		$this->load->view('dosen/rekap_layanan/rekap_layanan',$data);
		
		$this->load->view('footer_global');
	}

	function rekap_layanan_detail()
	{
		$form = $this->input->get('form');
		$awal = $this->input->get('awal');
		$akhir = $this->input->get('akhir');

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
	
		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');
		$data['jurusan'] = $this->user_model->get_jurusan_fak();
		$this->load->view('dosen/rekap_layanan/rekap_layanan_detail',$data);
		
		$this->load->view('footer_global');
	}

	function bimbingan()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');
		$data['dosen'] = $this->ta_model->get_bimbingan_dosen_user($this->session->userdata('userId'));
		$this->load->view('dosen/bimbingan/bimbingan',$data);
		
		$this->load->view('footer_global');
	}

	function bimbingan_detail()
	{
		$id_user = $this->input->get('dosen');
		$id_user = $this->encrypt->decode($id_user);
		$jenis = $this->input->get('jenis');
		$strata = $this->input->get('strata');

		switch($strata)
		{
			case "d3":
			$npm1 = $npm2 = "0";
			break;
			case "s1":
			$npm1 = "1";
			$npm2 = "5";
			break;
			case "s2":
			$npm1 = $npm2 = "2";
			break;
			case "s3":
			$npm1 = $npm2 = "3";
			break;
		}
		switch($jenis)
		{
			case "pb1":
			$status = 'Pembimbing Utama';
			break;
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

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['ta'] = $this->ta_model->get_bimbingan_dosen_detail($id_user,$status,$npm1,$npm2);
		$data['id_dosen'] = $id_user;

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/bimbingan/bimbingan_detail',$data);
		
		$this->load->view('footer_global');
	}

}
