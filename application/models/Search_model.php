<?php
class Search_model extends CI_Model
{
	function select_query($keyword)
	{
		
		$sql = "SELECT a.nrp, a.nama, a.pangkat, (select count(b.nrp) from pelanggaran_temp b where b.nrp=a.nrp) as jum FROM personil a WHERE a.nrp LIKE '%".$keyword."%' OR a.nama LIKE '%".$keyword."%' OR a.pangkat LIKE '%".$keyword."%' OR a.jabatan LIKE '%".$keyword."%' ORDER BY a.nama ASC";
		//echo $sql;
		$query = $this->db->query($sql);
		return $query;
	}
	
	
}
