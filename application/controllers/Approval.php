<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Approval extends CI_Controller {

    public function __construct()
	{
        parent::__construct();
		// $this->load->model('wilayah_model');
		// $this->load->model('parameter_model');
		$this->load->model('jurusan_model');
		$this->load->model('user_model');
		$this->load->model('ta_model');
    }

    function approval_alter()
	{
        $token = $this->input->get('token');
        // echo $token;
        $check = $this->ta_model->cek_token($token);
        if(!empty($check)){
            $data_ta = $this->ta_model->get_komisi_alter($token);
            if(!empty($data_ta)){
                $data['ta'] = $this->ta_model->get_komisi_alter($token);
            
                $this->load->view('approval/header_global');
                $this->load->view('approval/header');
                $this->load->view('approval/approval',$data);
                $this->load->view('footer_global');
            }
            else{
                echo "<script>alert('Token Salah Atau Sudah Tidak Berlaku');javascript:history.back();</script>";
            }
           
        }
        else{
            echo "<script>alert('Token Salah Atau Sudah Tidak Berlaku');javascript:history.back();</script>";
        }
    }
    
    function simpan_data()
    {
        $data = $this->input->post();
		// echo "<pre>";
        // print_r($data);
        
        $id = $data['id_pengajuan'];
        $ttd = $data['ttd'];
        $status = $data['status'];
        $token = $data['token']; 
        // echo $id;
        // echo $ttd;
        // echo $status;

        $this->ta_model->approve_ta_alter($id,$ttd,$status,$token);
        
        echo "<script>window.alert('Approval Berhasil');
        window.location='apps.fmipa.unila.ac.id/simipa';</script>"; //ganti url
    }

    function send()  
    {  

        $config = Array(  
            'protocol' => 'smtp',  
            'smtp_host' => 'ssl://smtp.googlemail.com',  
            'smtp_port' => 465,  
            'smtp_user' => 'irishia02@gmail.com',   
            'smtp_pass' => 'bagas123',   
            'mailtype' => 'html',   
            'charset' => 'iso-8859-1'  
           );  
           $this->load->library('email', $config);  
           $this->email->set_newline("\r\n");  
           $this->email->from('simipa@gmail.com', 'Admin Re:Code');   
           $this->email->to('raihanbn02@gmail.com');   
           $this->email->subject('Percobaan email');   
           $this->email->message('Ini adalahs email percobaan untuk Tutorial CodeIgniter: Mengirim Email via Gmail SMTP menggunakan Email Library CodeIgniter @ recodeku.blogspot.com');  
           if (!$this->email->send()) {  
            show_error($this->email->print_debugger());   
           }else{  
            echo 'Success to send email';   
           }  
    }  


    
    function approval_seminar()
    {
        $token = $this->input->get('token');

        $check = $this->ta_model->cek_token_seminar($token);
        if(!empty($check)){
            $data['smr'] = $this->ta_model->get_komisi_seminar_alter($token);
            if(!empty($data['smr'])){
                $this->load->view('approval/header_global');
                $this->load->view('approval/header');
                $this->load->view('approval/approval_seminar',$data);
                $this->load->view('footer_global');
            }
            else{
                echo "<script>alert('Token Salah Atau Sudah Tidak Berlaku');javascript:history.back();</script>";
            }
            
        }
        else{
            echo "<script>alert('Token Salah Atau Sudah Tidak Berlaku');javascript:history.back();</script>";
        }
    }

    function simpan_seminar()
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
        $token = $data['token'];

        $counts = $this->ta_model->cek_seminar_nilai_fill($id);
		$count = count($counts);

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

        $this->ta_model->update_nilai_seminar_check($id,$status,$saran,$ttd);
		if($count == 1){
			$this->ta_model->seminar_sidang_nilai_dosen_update($id);
        }
        $this->ta_model->seminar_sidang_komisi_alter_update($id,$token);
        
        echo "<script>window.alert('Approval Berhasil');
        window.location='apps.fmipa.unila.ac.id/simipa';</script>"; //ganti url
    }

    
	function verifikasi_registrasi()
	{
		$token = $this->uri->segment(2);
        //get id from token
        $cek = $this->user_model->cek_token_regis($token);
        if(empty($cek)){
            echo "<script>alert('Token Salah Atau Sudah Tidak Berlaku');javascript:history.back();</script>";
        }else{
            $this->user_model->verifikasi_registrasi($cek->userId);
            //delete
            $this->user_model->delete_token($token);
            redirect(site_url('?verifikasi=sukses'));
        }
        
    }
    
    function lupa_password()
    {
        $data = $this->input->post();
        // echo "<pre>";
        // print_r($data);

        //cek email
        $cek = $this->user_model->get_user_by_email($data['email']);

        if(empty($cek)){
            redirect(site_url('lupa-kata-kunci?status=gagal'));
        }else{

            $token = md5($data['email'].'simipa_password'.date('Y-m-d H:i:s'));
            // echo $token;
            $data_reset = array(
				"email" => $data['email'],
				"token" => $token,
			);
			

			$config = Array(  
				'protocol' => 'smtp',  
				'smtp_host' => 'ssl://smtp.googlemail.com',  
				'smtp_port' => 465,  
				'smtp_user' => 'apps.fmipa.unila@gmail.com',   
				'smtp_pass' => 'apps_fmipa 2020',   
				'mailtype' => 'html',   
				'charset' => 'iso-8859-1'  
			);  

			$this->load->library('email', $config);
			$this->email->set_newline("\r\n");  
			$this->email->from('apps.fmipa.unila@gmail.com', 'SIMIPA');   
			$this->email->to($data['email']);//$row->email   
			$this->email->subject('Lupa Password SIMIPA');   
			$this->email->message("
			Silahkan Klik Link Berikut Untuk Reset Password Website SIMIPA <br>
			https://apps.fmipa.unila.ac.id/simipa/ganti-password/$token
			<br><br>
			Terimakasih.
			
			");
			if (!$this->email->send()) {  
                redirect(site_url('lupa-kata-kunci?status=kesalahan'));
			   }else{  
                $this->user_model->insert_mhs_reset_pass($data_reset);
                redirect(site_url('lupa-kata-kunci?status=sukses'));
			}   

        }
    }

    function ganti_password()
    {
        $token = $this->uri->segment(2);
        // echo $token;
        
        $cek = $this->user_model->cek_token_reset($token);
        if(empty($cek)){
            echo "<script>alert('Token Salah Atau Sudah Tidak Berlaku');javascript:history.back();</script>";
        }else{
            $data['user'] = $this->user_model->get_user_by_email($cek->email);
            $this->load->view('approval/header_global');
            $this->load->view('approval/header');
            $this->load->view('approval/reset_password',$data);
            $this->load->view('footer_global');
        }

    }

    function simpan_password()
    {
        $data = $this->input->post();
        // echo "<pre>";
        // print_r($data);
        //get user
        $cek = $this->user_model->get_from_token($data['token']);
        //update password
        $pass = array(
            "password" => getHashedPassword($data['password'])
        );
        //hapus
        $this->user_model->delete_token_reset($data['token']);
        $this->user_model->update_password_user($cek->userId,$pass);
        redirect(site_url('?reset=sukses'));
    }


}  






?>