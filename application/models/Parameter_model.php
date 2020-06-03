<?php
class Parameter_model extends CI_Model
{
	function select_agama()
	{
		$this->db->order_by('id', 'ASC');
		$query = $this->db->get('agama');
		return $query->result();
	}

	function select_asal_sekolah()
	{
		$this->db->order_by('id_asal_sekolah', 'ASC');
		$query = $this->db->get('asal_sekolah');
		return $query->result();
	}

	function select_jalur_masuk()
	{
		$this->db->order_by('id_jalur_masuk', 'ASC');
		$query = $this->db->get('jalur_masuk');
		return $query->result();
	}

	function select_jenis_berkas()
	{
		$this->db->order_by('nama', 'ASC');
		$query = $this->db->get('jenis_berkas_lampiran');
		return $query->result();
	}

	function select_bidang_ilmu($jur)
	{
		$this->db->where(array("jurusan" => $jur));
		$this->db->order_by('nama', 'ASC');
		$query = $this->db->get('bidang_ilmu');
		return $query->result();
	}
	
	
}