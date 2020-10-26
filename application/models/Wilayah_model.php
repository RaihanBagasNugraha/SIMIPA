<?php
class Wilayah_model extends CI_Model
{
    function provinsi()
    {
        $this->db->order_by("nama", "ASC");
		$query = $this->db->get("wilayah_provinsi");
		return $query->result();
    }
	
	function kabupaten($provId)
    {
		$kabupaten = "";
        $this->db->order_by("nama", "ASC");
		$query = $this->db->get_where("wilayah_kabupaten", array('provinsi_id' => $provId));
		
		foreach($query->result() as $row)
		{
			$kabupaten .= "<option value='".$row->id."'>".$row->nama."</option>";
		}
		
		return $kabupaten;
	}

	function kabupaten_cek($provId, $kabId)
    {
		$kabupaten = "";
        $this->db->order_by("nama", "ASC");
		$query = $this->db->get_where("wilayah_kabupaten", array('provinsi_id' => $provId));
		
		foreach($query->result() as $row)
		{
			if($row->id == $kabId) $select = "selected";
			else $select = "";
			$kabupaten .= "<option ".$select." value='".$row->id."'>".$row->nama."</option>";
		}
		
		return $kabupaten;
	}

	
	function kecamatan($kabId)
    {
        $kecamatan = "";
        $this->db->order_by("nama", "ASC");
		$query = $this->db->get_where("wilayah_kecamatan", array('kabupaten_id' => $kabId));
		
		foreach($query->result() as $row)
		{
			$kecamatan .= "<option value='".$row->id."'>".$row->nama."</option>";
		}
		
		return $kecamatan;
	}

	function kecamatan_cek($kabId, $kecId)
    {
        $kecamatan = "";
        $this->db->order_by("nama", "ASC");
		$query = $this->db->get_where("wilayah_kecamatan", array('kabupaten_id' => $kabId));
		
		foreach($query->result() as $row)
		{
			if($row->id == $kecId) $select = "selected";
			else $select = "";
			$kecamatan .= "<option ".$select." value='".$row->id."'>".$row->nama."</option>";
		}
		
		return $kecamatan;
	}
	
	function desa($kecId)
    {
        $desa = "";
        $this->db->order_by("nama", "ASC");
		$query = $this->db->get_where("wilayah_desa", array('kecamatan_id' => $kecId));
		
		foreach($query->result() as $row)
		{
			$desa .= "<option value='".$row->id."'>".$row->nama."</option>";
		}
		
		return $desa;
	}
	
	function desa_cek($kecId, $desId)
    {
        $desa = "";
        $this->db->order_by("nama", "ASC");
		$query = $this->db->get_where("wilayah_desa", array('kecamatan_id' => $kecId));
		
		foreach($query->result() as $row)
		{
			if($row->id == $desId) $select = "selected";
			else $select = "";
			$desa .= "<option ".$select." value='".$row->id."'>".$row->nama."</option>";
		}
		
		return $desa;
	}
	
	function get_desa_by_id($desId)
	{
		$query = $this->db->query("SELECT a.nama as desa, b.nama as kecamatan, c.nama as kabupaten, d.nama as provinsi FROM wilayah_desa a, wilayah_kecamatan b, wilayah_kabupaten c, wilayah_provinsi d WHERE a.id = $desId AND a.kecamatan_id = b.id AND b.kabupaten_id = c.id AND c.provinsi_id = d.id");
		return $query->row();
	}
	
}