<?php
class Pejabat_model extends CI_Model
{
	function select_all()
	{
		$this->db->order_by('ID', 'ASC');
		$query = $this->db->get('pejabat');
		return $query;
	}
	
	function select_now()
	{
		$this->db->order_by('ID', 'DESC');
		$query = $this->db->get('pejabat');
		return $query->row();
	}
	

	function insert($data)
	{
		//$this->db->insert_batch('personil', $data);
	}
	
	
}
