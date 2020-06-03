<?php
class Jurusan_model extends CI_Model
{
	function select_all()
	{
	    $this->db->where('fakultas', '1');
		$this->db->order_by('id_jurusan', 'ASC');
		$query = $this->db->get('jurusan');
		return $query;
	}

	function select_prodi_by_jur($jur)
	{
		$this->db->where('jurusan', $jur);
		$this->db->order_by('id_prodi', 'ASC');
		$query = $this->db->get('prodi')->result();
		return $query;
	}
	
	
}