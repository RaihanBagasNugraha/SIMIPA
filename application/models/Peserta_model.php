<?php
class Peserta_model extends CI_Model
{
	function select_all()
	{
		$this->db->order_by('nama', 'ASC');
		$query = $this->db->get('peserta');
		return $query;
	}
	
	function select_by_ID($ID)
	{
		$this->db->where('ID', $ID);
		$query = $this->db->get('peserta');
		return $query;
	}

	function insert($data)
	{
		$this->db->insert_batch('peserta', $data);
	}
	
	function replace($data)
	{
	    $this->db->replace('peserta', $data);
	}
	
	function update($data, $where)
	{
	    $this->db->where('nrp', $where);
	    $this->db->update('peserta', $data);
	}

	function count_by_faculty($faculty)
	{
		$this->db->select('*');
		$this->db->from('peserta');
		$this->db->where(array('fakultas' => $faculty, 'jenjang' => 'd3/s1'));
		$query = $this->db->get();
		return $query->num_rows();
	}

	function count_by_pasca()
	{
		$this->db->select('*');
		$this->db->from('peserta');
		$this->db->where('jenjang', 'pasca/umum');
		$query = $this->db->get();
		return $query->num_rows();
	}

	
}
