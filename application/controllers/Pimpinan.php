<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pimpinan extends CI_Controller {
    
    public function __construct()
	{
		parent::__construct();
		$this->load->model('search_model');
		$this->load->model('pelanggaran_model');
		$this->load->model('personil_model');
	}

	
	public function index()
	{
	    if(!empty($_POST)) {
    	    $keyword = $this->input->post('keyword');
    	    $data['list_search'] = $this->search_model->select_query($keyword)->result();
	    } else {
	        $data['list_search'] = array();
	   }
	    
		$this->load->view('pimpinan/header');
		
		$this->load->view('pimpinan/beranda', $data);
		//echo "<pre>";
	    //print_r($data['list_search']);
	    //echo "</pre>";
		$this->load->view('pimpinan/footer');
	}
	
	public function detail_search()
	{
	    $data['list_pelanggaran'] = $this->pelanggaran_model->select_by_nrp($this->input->get('nrp'))->result();
	    $data['personil'] = $this->personil_model->select_by_nrp($this->input->get('nrp'))->row();
	    $this->load->view('pimpinan/header');
		$this->load->view('pimpinan/detail', $data);

		$this->load->view('pimpinan/footer');
	}
	
	
}
