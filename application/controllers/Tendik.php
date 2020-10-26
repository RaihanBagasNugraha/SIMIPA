<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tendik extends CI_Controller {
    
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
		
		if($this->session->has_userdata('username')) {
		    if($this->session->userdata('state') <> 4) {
		        echo "<script>alert('Akses ditolak!!');javascript:history.back();</script>";
		    }
		} else {
		    redirect(site_url('?access=ditolak'));
		}
	}

	public function index()
	{
		redirect(site_url("tendik/kelola-akun"));
	}

	public function akun()
	{
		$data['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$this->load->view('header_global', $data);
		$this->load->view('tendik/header');
		
		$this->load->view('tendik/akun', $data);

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
		redirect(site_url("tendik/kelola-akun?status=sukses"));
	}

	public function biodata()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['biodata'] = $this->user_model->select_biodata_by_ID($this->session->userdata('userId'), 4)->row();

		$this->load->view('header_global', $header);
		$this->load->view('tendik/header');
		
		$this->load->view('tendik/biodata', $data);

        $this->load->view('footer_global');
	}

	public function ubah_biodata()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);
		//echo "<pre>";
		//print_r($_POST);
		//echo $this->session->userdata('userId');
		$data_tendik= array(
			'unit_kerja' => $this->input->post('unit'),
			'pangkat_gol' => $this->input->post('pangkat')
		);

		$this->user_model->update_tendik($data_tendik, $this->session->userdata('userId'));

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
		redirect(site_url("tendik/kelola-biodata?status=sukses"));
	}

	//add raihan
	function tugas_tambahan()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);

		$iduser = $data['iduser'];
		$tugas = $data['tugas_tambahan'];
		$prodi = $data['prodi'];
		$jurusan = $data['jurusan'];
		$periode = $data['periode'];
		$status = $data['status_tgs'];

		if($jurusan == ""){
			$jurusan = 0;
		}
		if($prodi == ""){
			$prodi = 0;
		}

		$check = $this->user_model->check_tugas_tambahan($iduser,$tugas,$jurusan,$prodi,$status);

		if(!empty($check)){
			redirect(site_url("tendik/kelola-biodata?status=duplikat"));
		}
		else{
			$data_tugas = array(
				'id_user' => $iduser,
				'tugas' => $tugas,
				'jurusan_unit' => $jurusan,
				'prodi' => $prodi,
				'periode' => $periode,
				'aktif' => $status,
			);
	
			$this->user_model->insert_tugas_tambah($data_tugas);		
			redirect(site_url("tendik/kelola-biodata?status=sukses"));
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
		redirect(site_url("tendik/kelola-biodata?status=sukses"));

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
		$data['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$this->load->view('header_global', $data);
		$this->load->view('tendik/header');

		$this->load->view('tendik/tema_ta');
		
		//$this->load->view('tendik/tugas_akhir', $data);

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
		$this->load->view('tendik/header');

		$this->load->view('tendik/form_tema_ta', $data);
		
		//$this->load->view('tendik/tugas_akhir', $data);

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
	function verifikasi_berkas()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['ta'] = $this->ta_model->get_verifikasi_berkas($this->session->userdata('userId'));
		$data['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();

		$this->load->view('header_global', $header);
		$this->load->view('tendik/header');

		$this->load->view('tendik/verifikasi_berkas',$data);	
		$this->load->view('footer_global');
	}

	function verifikasi_berkas_approve()
	{
		$data = $this->input->post();

		// echo "<pre>";
		// print_r($data);
		
		$id = $data['id_pengajuan'];
		$ttd = $data['ttd'];
		$no = $data['no_penetapan'];
		$nomor = $data['nomor'];
		$update = $data['update'];

		$no_penetapan = $no.$nomor;

		$dosenid = $this->session->userdata('userId');

		$this->ta_model->staff_surat($id,$no_penetapan);
		$this->ta_model->approve_berkas_ta($id,$dosenid,$ttd,$no_penetapan,$update);
		
		redirect(site_url("tendik/verifikasi-berkas"));
	}

	function verifikasi_berkas_decline()
	{
		$id = $this->input->post('id_ta');
		$keterangan = $this->input->post('keterangan');
		$dosenid = $this->session->userdata('userId');
		$ket = "admin###".$keterangan;
		// echo $id;
		// echo $keterangan;
		// echo $dosenid;
		$this->ta_model->decline_berkas_ta($id,$dosenid,$ket);
		redirect(site_url("tendik/verifikasi-berkas"));
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
		$this->load->view('tendik/header');

		$this->load->view('tendik/approve_tema_ta',$data);
		
		$this->load->view('footer_global');
	}
	
	//seminar
	function verifikasi_berkas_seminar()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['seminar'] = $this->ta_model->get_verifikasi_berkas_seminar($this->session->userdata('userId'));
		$data['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();

		$this->load->view('header_global', $header);
		$this->load->view('tendik/header');

		$this->load->view('tendik/seminar/verifikasi_berkas_seminar',$data);	
		$this->load->view('footer_global');
	}

	function verifikasi_berkas_seminar_decline()
	{
		$id = $this->input->post('id_seminar');
		$keterangan = $this->input->post('keterangan');
		$status = $this->input->post('status');
		$dosenid = $this->session->userdata('userId');

		// echo $id;
		// echo $keterangan;
		// echo $status;
		$this->ta_model->decline_seminar($id,$dosenid,$status,$keterangan);
		redirect(site_url("tendik/verifikasi-berkas/seminar"));
	}

	function verifikasi_berkas_seminar_approve()
	{
		$data = $this->input->post();
		$id = $data['id'];
		$ttd = $data['ttd'];
		$form1 = $data['no_form'];
		$form2 = $data['no_form_2'];
		$undangan1 = $data['no_undangan'];
		$undangan2 = $data['no_undangan_2'];

		$no_form = $form1.$form2;
		$no_undangan = $undangan1.$undangan2;

		$dosenid = $this->session->userdata('userId');

		// echo "<pre>";
		// print_r($data);
		$this->ta_model->staff_surat_seminar($id,$no_form);
		$this->ta_model->approve_berkas_seminar($id,$dosenid,$ttd,$no_form,$no_undangan);
		redirect(site_url("tendik/verifikasi-berkas/seminar"));
	}

	function seminar_aksi()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();

		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);
		
		// echo "<pre>";
		// print_r($id);
		$seminar = $this->ta_model->get_seminar_by_id($id);
		$data['seminar'] = $seminar[0];

		// print_r($data);
		$this->load->view('header_global', $header);
		$this->load->view('tendik/header');

		$this->load->view('tendik/seminar/approve_seminar',$data);
		
		$this->load->view('footer_global');
	}

	function verifikasi_berkas_pkl()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['pkl'] = $this->pkl_model->get_approve_tendik_lokasi($this->session->userdata('userId'));


		// print_r($data);
		$this->load->view('header_global', $header);
		$this->load->view('tendik/header');

		$this->load->view('tendik/pkl/approve_pkl',$data);
		
		$this->load->view('footer_global');
	}

	function verifikasi_berkas_pkl_perbaiki()
	{
		// $data = $this->input->post();
		// print_r($data);
		$id = $this->input->post('pkl_id');
		$status = $this->input->post('status');
		$keterangan = $this->input->post('keterangan');
		$periode = $this->input->post('periode');
		$id_alamat = $this->input->post('id_alamat');
		$ket = $keterangan."$#$".$status;
		
		$this->pkl_model->perbaikan_pkl($id,$ket);
		redirect(site_url("/tendik/verifikasi-berkas/pkl/approve?periode=$periode&id=$id_alamat"));
	}

	function verifikasi_berkas_pkl_setujui()
	{
		$status = $this->input->get('status');
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['pkl'] = $this->pkl_model->select_pkl_by_id_pkl($id);
		$data['status'] = $status;

		$this->load->view('header_global', $header);
		$this->load->view('tendik/header');

		$this->load->view('tendik/pkl/approve_pkl_ttd',$data);
		
		$this->load->view('footer_global');
	}

	function verifikasi_berkas_pkl_save()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);

		// echo $data['id'][1];
		$jml = $data['jumlah'];
		$status = $data['status'];
		$user_id = $this->session->userdata('userId');
		$ttd = $data['ttd'];
		$no_surat = $data['no_penetapan'].$data['nomor'];
		$id_lokasi = $data['id_lokasi'];

		for($i=1;$i<=$jml;$i++){
			//add no_surat pkl_mahasiswa
			$this->pkl_model->pkl_add_no_surat($data['id'][$i],$no_surat);

			//staff_surat
			$this->pkl_model->update_surat($data['id'][$i],$no_surat);

			$this->pkl_model->pkl_approve_setujui($status,$data['id'][$i],$user_id,$ttd);
		}
		//insert to pkl_mahasiswa_approval_koor
		$approval = array(
			"lokasi_id" => $id_lokasi,
			"no_penetapan" => $no_surat
		);
		$this->pkl_model->insert_mhs_app_koor_tendik($approval);

		redirect(site_url("/tendik/verifikasi-berkas/pkl"));
	}

	function verifikasi_berkas_pkl_list()
	{
		$periode = $this->input->get('periode');
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['pkl'] = $this->pkl_model->get_lokasi_pkl_by_id($id);
		$data['jml'] = $this->pkl_model->get_jml_mahasiswa_lokasi_daftar_tendik($id,$this->session->userdata('userId'))->jml;
		$data['periode'] = $periode;

		$this->load->view('header_global', $header);
		$this->load->view('tendik/header');

		$this->load->view('tendik/pkl/approve_pkl_list',$data);
		
		$this->load->view('footer_global');
	}

	function verifikasi_berkas_pkl_approve()
	{
		$input = $this->input->post();
		// echo "<pre>";
		// print_r($input);
		$jml = $input['jumlah'];
		for($i=1;$i<=$jml;$i++)
		{
			$data['pkl'][$i] = $this->pkl_model->select_pkl_by_id_pkl($input['id'][$i]);
		}
			$data['status'] = "admin";
			$data['jml'] = $jml;
			$data['lokasi'] = $this->pkl_model->get_lokasi_pkl_by_id($input['lokasi']);
		
		// print_r($data);

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$this->load->view('header_global', $header);
		$this->load->view('tendik/header');

		$this->load->view('tendik/pkl/approve_pkl_ttd',$data);
		
		$this->load->view('footer_global');
	}

	function verifikasi_berkas_pkl_seminar()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['seminar'] = $this->pkl_model->get_seminar_tendik($this->session->userdata('userId'));
		// $data['jml'] = $this->pkl_model->get_jml_mahasiswa_lokasi_daftar_tendik($id,$this->session->userdata('userId'))->jml;
		// $data['periode'] = $periode;

		$this->load->view('header_global', $header);
		$this->load->view('tendik/header');

		$this->load->view('tendik/pkl/seminar/approve_pkl_seminar',$data);
		
		$this->load->view('footer_global');
	}

	function verifikasi_berkas_pkl_seminar_perbaiki()
	{
		$id = $this->input->post('seminar_id');
		$status = $this->input->post('status');
		$keterangan = $this->input->post('keterangan')."$#$".$status;
		// echo "<pre>";
		// print_r($input);

		$this->pkl_model->perbaiki_seminar_pkl($id,$keterangan,$status);
		redirect(site_url("/tendik/verifikasi-berkas/seminar-pkl"));		
	}

	function verifikasi_berkas_pkl_seminar_form()
	{
		$status = $this->input->get('status');
		$id = $this->input->get('id');
		$id = $this->encrypt->decode($id);

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['seminar'] = $this->pkl_model->get_seminar_by_id($id);
		$data['pkl'] = $this->pkl_model->select_pkl_by_id_pkl($data['seminar']->pkl_id);
		$data['status'] = $status;

		$this->load->view('header_global', $header);
		$this->load->view('tendik/header');

		$this->load->view('tendik/pkl/seminar/approve_pkl_seminar_ttd',$data);
		
		$this->load->view('footer_global');
	}

	function verifikasi_berkas_pkl_seminar_form_setujui()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($input);

		$seminar_id = $data['seminar_id'];
		$status = $data['status'];
		$ttd = $data['ttd'];
		$nomor_surat = $data['no_penetapan'].$data['nomor'];
		$sts = 2;
		$id_user = $this->session->userdata('userId');
		//set seminar status
		$this->pkl_model->update_seminar_pkl($seminar_id,$sts);
		//no surat
		$this->pkl_model->update_seminar_surat_pkl($seminar_id,$nomor_surat);
		//update staff surat
		$this->pkl_model->update_seminar_staff_surat_pkl($seminar_id,$nomor_surat);
		//insert ttd
		$data_approval = array(
			"seminar_id" => $seminar_id,
			"status_slug" => "Administrasi",
			"id_user" => $id_user,
			"ttd" => $ttd
		);
		$this->pkl_model->input_approval_seminar($data_approval);
		redirect(site_url("/tendik/verifikasi-berkas/seminar-pkl"));
	}

	function tambah_atribut()
	{
		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();

		$this->load->view('header_global', $header);
		$this->load->view('tendik/header');

		$this->load->view('tendik/fakultas/atribut/tambah_atribut');
		
		$this->load->view('footer_global');
	}

	function tambah_atribut_add()
	{
		$jns = $this->input->post('jns_layanan');

		if($jns == 'Akademik'){
			$form = $this->input->post('form');
		}
		elseif($jns == 'Umum dan Keuangan'){
			$form = $this->input->post('form2');
		}
		elseif($jns == 'Kemahasiswaan'){
			$form = $this->input->post('form3');
		}

		$header['akun'] = $this->user_model->select_by_ID($this->session->userdata('userId'))->row();
		$data['jenis'] = $jns;
		$data['form'] = $form;

		$this->load->view('header_global', $header);
		$this->load->view('tendik/header');

		$this->load->view('tendik/fakultas/atribut/tambah_atribut_tambah',$data);
		
		$this->load->view('footer_global');

	}

	function tambah_atribut_save()
	{
		$data = $this->input->post();
		// echo "<pre>";
		// print_r($data);

		$id_form = $data['id_form'];
		$nama = $data['nama'];

		$i = 0;
		foreach($nama as $row){
			$slug = str_replace(" ","-",strtolower($data['nama'][$i]));

			$input = array(
				"id_layanan" => $id_form,
				"slug" => $slug,
				"nama" => $data['nama'][$i],
				"placeholder" => $data['placeholder'][$i],
				"tipe" => $data['tipe'][$i],
				"pilihan" => $data['pilihan'][$i]
			);
			//input atribut layanan
			$this->layanan_model->insert_atribut_layanan($input);
			$i++;
		}
		redirect(site_url("/tendik/atribut-form/atribut"));
		

	}

}
