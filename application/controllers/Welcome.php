<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
    
    public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		
	}

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		if($this->session->has_userdata('username')) {
		    echo "<script>javascript:history.back();</script>";
		} else {
		$this->load->view('login');
		}
	}

	public function registrasi()
	{
		$this->load->model('jurusan_model');
		$data['list_jurusan'] = $this->jurusan_model->select_all()->result();
		$this->load->view('registration', $data);
	}
	
	public function lupa_password()
	{
		$this->load->view('forgot_password');
	}

	public function periksa_sandi()
	{
	    //echo "<pre>";
	    //print_r($_POST);
	    $username = $this->input->post('username');
	    $password = $this->input->post('password');
	    
	    $user = $this->user_model->cek_login($username, $password);
		
		//echo "<pre>";
		//print_r($user);
		
	    if($user) {
	        $newData = array(
			   'username' => $username,
			   'name' => $user->name,
			   'userId' => $user->userId,
			   'state' => $user->roleId
	        );
			$this->session->set_userdata($newData);

			//echo "<pre>";
			//print_r($newData);
			
	        if($user->roleId == 1) {
				// administrator
	            redirect(site_url("admin"));
	        } if($user->roleId == 2) {
				// dosen
				redirect(site_url("dosen"));
			} if($user->roleId == 3) {
				// mahasiswa
				redirect(site_url("mahasiswa"));
			} if($user->roleId == 4) {
				// staf
				redirect(site_url("tendik"));
			} if($user->roleId == 5) {
				// alumni
			}
			else {
				// wali
	            //redirect(site_url("data-peserta"));
	        }
	    } else {
	        redirect(site_url("?login=gagal"));
		}
		
	}

	public function aksi_registrasi()
	{
		//echo "<pre>";

		$nama = ucwords(strtolower($this->security->xss_clean($this->input->post('nama'))));
		$email = strtolower($this->security->xss_clean($this->input->post('email')));
		$password = $this->input->post('password');
		$npm = $this->input->post('npm');
		$hp = $this->security->xss_clean($this->input->post('hp'));
		$status = $this->security->xss_clean($this->input->post('status'));

		$userInfo = array(
			'email' => $email,
			'password' => getHashedPassword($password),
			'roleId' => $status, // Default Mahasiswa
			'name' => $nama,
			'mobile' => $hp,
			'createdBy' => 99, // form register
			'createdDtm' => date('Y-m-d H:i:s')
		);

		$strata = substr($npm, 2, 1);
		if($strata <= 2)
		{
			$jurusan = substr($npm, 5, 1);
			if($jurusan == 6) $jurusan = 2;
		}
		else
		{
			$jurusan = 0;
		}

		if($status == 3){
			$data_mhs = array(
				'jurusan' => $jurusan,
				'npm' => $npm
			);
		}
		
		elseif($status == 2 || $status == 1){
			$data_mhs = array(
				'nip_nik' => $npm
			);
		}
		

		// cek email
		$cek_email = $this->user_model->cek_email($email);
		if($cek_email)
		{
			$this->session->set_flashdata('error', 'Email Anda telah terdaftar!');
		}
		else
		{
			if($status == 3)
			{
				// cek npm
				$cek_npm = $this->user_model->cek_npm($npm);
			}
			elseif($status == 2 || $status == 1){
				// cek nip dosen
				$cek_npm = $this->user_model->cek_nip_dosen($npm);
				// $cek_npm = $this->user_model->cek_npm_alumni($npm);
			}
			if($cek_npm)
			{
				$this->session->set_flashdata('error', 'NPM Anda telah terdaftar!');
			}
			else
			{
				$result = $this->user_model->addNewUser($userInfo, $data_mhs);


				if ($result > 0) {
					$this->session->set_flashdata('success', 'Anda sudah terdaftar, silahkan tunggu email dari sistem untuk bisa login.');
				} else {
					$this->session->set_flashdata('error', 'Gagal Mendaftar!');
				}
			}

		}

		redirect(site_url('registrasi'));
	}
	
	public function logout() 
	{
	    $this->session->sess_destroy();
	    redirect(site_url());
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
		print_r($data);
		echo $this->session->userdata('userId');
		//$this->user_model->update($data, $this->session->userdata('userId'));
		//redirect(site_url("kelola-akun?status=sukses"));
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

	public function akun()
	{
		$this->load->view('header_global');
		if($this->session->userdata('state') == 3)
			$this->load->view('mahasiswa/header');
		
		$this->load->view('akun');

        $this->load->view('footer_global');
	}
}
