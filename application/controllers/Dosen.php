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
		$this->load->library('encrypt');

		
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
		// $fill = 0;
		// $null = 0;

		// if($pb1 != NULL){$fill++;}
		// if($pb2 != NULL){$fill++;}
		// if($pb3 != NULL){$fill++;}
		// if($ps1 != NULL){$fill++;}
		// if($ps2 != NULL){$fill++;}
		// if($ps3 != NULL){$fill++;}

		// if($pb1 == '0'){$null++;}
		// if($pb2 == '0'){$null++;}
		// if($pb3 == '0'){$null++;}
		// if($ps1 == '0'){$null++;}
		// if($ps2 == '0'){$null++;}
		// if($ps3 == '0'){$null++;}

		// $check = $fill - $null;

		$dosenid = $this->session->userdata('userId');

		// if($check == '1'){
		// 	$status = "kajur_acc";
		// 	$this->ta_model->approve_ta($id,$ttd,$status,$dosenid);
		// }
		// else{
			$status = "kajur";
			$this->ta_model->approve_ta($id,$ttd,$status,$dosenid);
		// }	
		
		//send email
		if(!empty($alter)){
			$config = Array(  
				'protocol' => 'smtp',  
				'smtp_host' => 'ssl://smtp.googlemail.com',  
				'smtp_port' => 465,  
				'smtp_user' => 'irishia02@gmail.com',   
				'smtp_pass' => 'bagas123',   
				'mailtype' => 'html',   
				'charset' => 'iso-8859-1'  
			);  
			
			foreach($alter as $row){
				$this->load->library('email', $config);
				$this->email->set_newline("\r\n");  
				$this->email->from('simipa@gmail.com', 'SIMIPA');   
				$this->email->to($row->email);   
				$this->email->subject('Approve Tema Penelitian Fakultas Matematika dan Ilmu Pengetahuan Alam');   
				$this->email->message("
				Kepada Yth. $row->nama
				<br>
				Untuk Melakukan Approval Tema Penelitian Mahasiswa Fakultas Matematika Dan Ilmu Pengetahuan Alam Sebagai $row->status Silahkan Klik Link Berikut :<br>
				http://localhost/simipa/approval/ta?token=$row->token
				<br><br>
				Terimakasih.
				
				");
				if (!$this->email->send()) {  
					echo "error";   
				   }else{  
					  
				}   
			}
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
		}
		else{
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
			$id_ta = $data['id_ta'];

			$komisi = $this->ta_model->get_komisi_alter_seminar_id($id_ta);

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

			redirect(site_url("dosen/tugas-akhir/seminar/koordinator"));
		}

		elseif($status == 'kajur'){
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

		$alter = $this->ta_model->get_komisi_seminar_alter_id($id);

		//send email
		if(!empty($alter)){
			$config = Array(  
				'protocol' => 'smtp',  
				'smtp_host' => 'ssl://smtp.googlemail.com',  
				'smtp_port' => 465,  
				'smtp_user' => 'irishia02@gmail.com',   
				'smtp_pass' => 'bagas123',   
				'mailtype' => 'html',   
				'charset' => 'iso-8859-1'  
			);  
			
			foreach($alter as $row){
					$this->load->library('email', $config);
					$this->email->set_newline("\r\n");  
					$this->email->from('simipa@gmail.com', 'SIMIPA');   
					$this->email->to($row->email);   
					$this->email->subject('Penilaian Seminar/Sidang Fakultas Matematika dan Ilmu Pengetahuan Alam');   
					$this->email->message("
					Kepada Yth. $row->nama
					<br>
					Untuk Melakukan Penilaian Seminar/Sidang Mahasiswa Fakultas Matematika Dan Ilmu Pengetahuan Alam Sebagai $row->status Silahkan Klik Link Berikut :<br>
					http://localhost/simipa/approval/seminar?token=$row->token
					<br><br>
					Terimakasih.
					
					");
					if (!$this->email->send()) {  
						echo "error";   
					}else{  
						
					}   
				}
			}

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
		$data['ta'] = $this->ta_model->get_ta_rekap($this->session->userdata('userId'));
		// print_r($data);
		// $jml = count($data);

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/koordinator/rekap/rekap_ta',$data);
		
		$this->load->view('footer_global');
	}

	function rekap_seminar_koor()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['seminar'] = $this->ta_model->get_seminar_rekap_koor($this->session->userdata('userId'));
		// print_r($data);
		// $jml = count($data);

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/koordinator/rekap/rekap_seminar',$data);
		
		$this->load->view('footer_global');
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

		if($tipe != "Sidang Komprehensif"){
			$jml = count($data['ujian_komponen']);
			$jml2 = count($data['skripsi_komponen']);
		}
		else{
			$jml = count($data['ujian_komponen_kompre']);
			$jml2 = count($data['skripsi_komponen_kompre']);
		}
		
		
		$ujian = 0;
		$skripsi = 0;

		//check
		if($tipe != "Sidang Komprehensif"){
			for($i=0; $i<$jml; $i++){
				$ujian += $data['ujian_nilai'][$i];
			}
			for($i=0; $i<$jml2; $i++){
				$skripsi += $data['skripsi_nilai'][$i];
			}
		}
		else{
			for($i=0; $i<$jml; $i++){
				$ujian += $data['ujian_nilai_kompre'][$i];
			}
			for($i=0; $i<$jml2; $i++){
				$skripsi += $data['skripsi_nilai_kompre'][$i];
			}
		}

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
	
		if($data['tipe'] != "Sidang Komprehensif"){
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
		}
		else{
			for($i=0; $i<$jml; $i++){
				$data_ujian = array(
					'id_komponen' => $lastid,
					'unsur' => 'Ujian',
					'attribut' => $data['ujian_komponen_kompre'][$i],
					'persentase' => "100",
				);
				$this->ta_model->komposisi_nilai_meta_save($data_ujian);
	
			}
	
			for($i=0; $i<$jml2; $i++){
				$data_ujian = array(
					'id_komponen' => $lastid,
					'unsur' => $data['jenis'],
					'attribut' => $data['skripsi_komponen_kompre'][$i],
					'persentase' => "100",
				);
				$this->ta_model->komposisi_nilai_meta_save($data_ujian);
			}
		}

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

		if($tipe != "Sidang Komprehensif"){
			$jml = count($data['ujian_komponen']);
			$jml2 = count($data['skripsi_komponen']);
		}
		else{
			$jml = count($data['ujian_komponen_kompre']);
			$jml2 = count($data['skripsi_komponen_kompre']);
		}

		$ujian = 0;
		$skripsi = 0;
		//check
		if($tipe != "Sidang Komprehensif"){
			for($i=0; $i<$jml; $i++){
				$ujian += $data['ujian_nilai'][$i];
			}
			for($i=0; $i<$jml2; $i++){
				$skripsi += $data['skripsi_nilai'][$i];
			}
		}
		else{
			for($i=0; $i<$jml; $i++){
				$ujian += $data['ujian_nilai_kompre'][$i];
			}
			for($i=0; $i<$jml2; $i++){
				$skripsi += $data['skripsi_nilai_kompre'][$i];
			}
		}


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
			
			if($data['tipe'] != "Sidang Komprehensif"){
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
		}
		else{
			for($i=0; $i<$jml; $i++){
				$data_ujian = array(
					'id_komponen' => $id,
					'unsur' => 'Ujian',
					'attribut' => $data['ujian_komponen_kompre'][$i],
					'persentase' => "100",
				);
				$this->ta_model->komposisi_nilai_meta_save($data_ujian);
	
			}
	
			for($i=0; $i<$jml2; $i++){
				$data_ujian = array(
					'id_komponen' => $id,
					'unsur' => $data['jenis'],
					'attribut' => $data['skripsi_komponen_kompre'][$i],
					'persentase' => "100",
				);
				$this->ta_model->komposisi_nilai_meta_save($data_ujian);
			}
		}


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

	function nilai_seminar_koor_approve_add()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);

		$id = $data['id'];
		$ttd = $data['ttd'];
		$status = "Koordinator";
		$user_id = $this->session->userdata('userId');

		$data = array(
			'status' => $status,
			'saran' => '',
			'ket' => $user_id,
			'id_seminar' => $id,
			'ttd' => $ttd,
		);
		$this->ta_model->insert_nilai_seminar_koor($data);

		//update seminar
		$this->ta_model->update_nilai_seminar_koor($id);
		redirect(site_url("dosen/tugas-akhir/nilai-seminar/koordinator"));

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
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['ta'] = $this->ta_model->get_ta_by_id($id);
		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/kaprodi/tema_ta/tema_ta_approve',$data);
		
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

		$this->load->view('header_global', $header);
		$this->load->view('dosen/header');

		$this->load->view('dosen/verifikasi_ta/verifikasi_ta_nilai',$data);
		
		$this->load->view('footer_global');
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


	function encrypt($string)
	{
		$result = $this->encrypt->encode($string);
		return $result;
	}
	function decrypt($string)
	{
		$result = $this->encrypt->decode($string);
		return $result;
	}

	
	

}
