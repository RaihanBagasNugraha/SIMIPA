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

	//edit raihan
	function get_jurusan_all()
	{
		$this->db->where('fakultas', '1');
		$this->db->order_by('id_jurusan', 'ASC');
		$query = $this->db->get('jurusan')->result();
		return $query;
	}

	function get_unit_kerja_all()
	{
		$this->db->order_by('id_unit_kerja', 'ASC');
		$query = $this->db->get('unit_kerja')->result();
		return $query;
	}

	function get_prodi_all()
	{
		$this->db->order_by('id_prodi', 'ASC');
		$query = $this->db->get('prodi')->result();
		return $query;
	}

	function get_lab_all()
	{
		$this->db->order_by('id_lab', 'ASC');
		$query = $this->db->get('laboratorium')->result();
		return $query;
	}

	function get_prodi_id($id)
	{
		$result = $this->db->query("SELECT * FROM prodi WHERE id_prodi = $id");
		return $result->row();
	}

	function get_jurusan_id($id)
	{
		$result = $this->db->query("SELECT * FROM jurusan WHERE id_jurusan = $id");
		return $result->row();
	}

	function get_unit_kerja_id($id)
	{
		$result = $this->db->query("SELECT * FROM unit_kerja WHERE id_unit_kerja = $id");
		return $result->row();
	}
	
	//insert prodi baru
	function insert_prodi($data)
	{
		$this->db->trans_start();
		$this->db->insert('prodi', $data);	
		$this->db->trans_complete();
	}

	function edit_prodi($id,$data)
	{
		$this->db->where('id_prodi', $id);
	    $this->db->update('prodi', $data);
	}

	//insert jurusan baru
	function insert_jurusan($data)
	{
		$this->db->trans_start();
		$this->db->insert('jurusan', $data);	
		$this->db->trans_complete();
	}

	function edit_jurusan($id,$data)
	{
		$this->db->where('id_jurusan', $id);
	    $this->db->update('jurusan', $data);
	}
	
	function get_las_id_unit_kerja()
	{
		$result = $this->db->query("SELECT MAX(id_unit_kerja) as id FROM unit_kerja");
		return $result->row()->id;
	}

	function get_unit_kerja_by_nama($nama)
	{
		$result = $this->db->query("SELECT * FROM `unit_kerja` WHERE nama = '$nama'");
		return $result->row();
	}

	//insert lab
	function insert_lab($data)
	{
		$this->db->trans_start();
		$this->db->insert('laboratorium', $data);	
		$this->db->trans_complete();
	}

	function edit_lab($id,$data)
	{
		$this->db->where('id_lab', $id);
	    $this->db->update('laboratorium', $data);
	}

	//insert unit kerja
	function insert_unit($data)
	{
		$this->db->trans_start();
		$this->db->insert('unit_kerja', $data);	
		$this->db->trans_complete();
	}

	function edit_unit($id,$data)
	{
		$this->db->where('id_unit_kerja', $id);
	    $this->db->update('unit_kerja', $data);
	}

}