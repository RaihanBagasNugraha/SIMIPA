<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Excel_import extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('excel_import_model');
		$this->load->model('peserta_model');
		$this->load->model('nilai_model');
		$this->load->model('pejabat_model');
		$this->load->library('excel');
	}

	function index()
	{
		//$this->load->view('excel_import');
	}
	
	function fetch()
	{
	    /*
		$data = $this->excel_import_model->select();
		$output = '
		<h3 align="center">Total Data - '.$data->num_rows().'</h3>
		<table class="table table-striped table-bordered">
			<tr>
				<th>Customer Name</th>
				<th>Address</th>
				<th>City</th>
				<th>Postal Code</th>
				<th>Country</th>
			</tr>
		';
		foreach($data->result() as $row)
		{
			$output .= '
			<tr>
				<td>'.$row->CustomerName.'</td>
				<td>'.$row->Address.'</td>
				<td>'.$row->City.'</td>
				<td>'.$row->PostalCode.'</td>
				<td>'.$row->Country.'</td>
			</tr>
			';
		}
		$output .= '</table>';
		echo $output;
		*/
	}

	function import()
	{
		//echo "<pre>";
		//print_r($_POST);
		//print_r($_FILES);
		//print_r($_SESSION);
		$pejabat = $this->pejabat_model->select_now();
		//print_r($pejabat);

		
		if(isset($_FILES["file"]["name"]))
		{
			$path = $_FILES["file"]["tmp_name"];
			$object = PHPExcel_IOFactory::load($path);
			foreach($object->getWorksheetIterator() as $worksheet)
			{
				$highestRow = $worksheet->getHighestRow();
				$highestColumn = $worksheet->getHighestColumn();
				for($row=2; $row<=$highestRow; $row++)
				{
					$sertifikat_NO = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
					$sertifikat_BLN = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
					$nama = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
					$ID = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
					$fakultas = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
					$jurusan_ps = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
					$tgl_lahir = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
					$tes_ke = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
					$lis = strtolower(trim($worksheet->getCellByColumnAndRow(8, $row)->getValue()));
					$str = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
					$rv = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
					$skor = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
					
					if(!empty($ID)) 
					{
					//$data[]
					$data = array(
						'ID'		=>	$ID,
						'nama'		=>	$nama,
						'fakultas'	=>	$fakultas,
						'jurusan_ps'	=>	$jurusan_ps,
						'jenjang' => $this->input->post('jenjang'),
						'tgl_lahir'		=>	$tgl_lahir
					);
					$this->peserta_model->replace($data);
					
					
					
					$data2 = array(
					    'user_ID' => $ID,
					    'tes_tgl' => $this->input->post('tes_tgl'),
					    'tes_ttd' => $pejabat->ID,
					    'sertifikat_NO' => $sertifikat_NO,
					    'sertifikat_BLN' => $sertifikat_BLN,
					    'tes_ke' => $tes_ke,
					    'lis' => $lis,
					    'str' => $str,
						'rv' => $rv,
						'skor' => $skor,
						'kategori' => $this->input->post('kategori'),
						'user_add' => $this->session->userdata('username'),
						'verif_code' => md5(date('d-m-Y h:i:s').$ID.$tes_ke)
					);
					        
					$this->nilai_model->replace($data2);
					
					}
				}
			}
			//$this->excel_import_model->insert($data);
			
			//echo 'Data Imported successfully';
			redirect(site_url('data-peserta'));
		}	
		
		
	}
}

?>