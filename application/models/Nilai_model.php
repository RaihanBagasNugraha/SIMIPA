<?php
class Nilai_model extends CI_Model
{
	function select_by_user_ID($user_ID)
	{
		$this->db->order_by('tes_tgl', 'DESC');
		$this->db->where('user_ID', $user_ID);
		$query = $this->db->get('nilai');
		return $query;
	}
	
	function select_by_ID($ID)
	{
		$this->db->where('ID', $ID);
		$query = $this->db->get('nilai');
		return $query;
	}

	function insert($data)
	{
		$this->db->insert_batch('peserta', $data);
	}
	
	function replace($data)
	{
	    $this->db->replace('nilai', $data);
	}
	
	function update($data, $where)
	{
	    $this->db->where('ID', $where);
	    $this->db->update('nilai', $data);
	}
	
	function delete($data)
	{
		$this->db->delete('nilai', $data);
	}

	function get_by_faculty($faculty)
	{
		//$sql = "SELECT left(a.user_ID, 2) as angkatan, count(*) as jumlah, max((a.lis+a.str+a.rv)*10/3) as max_skor, avg((a.lis+a.str+a.rv)*10/3) as avg_skor, min((a.lis+a.str+a.rv)*10/3) as min_skor FROM `nilai` a, peserta b WHERE a.user_ID = b.ID AND b.fakultas='".$faculty."' GROUP BY angkatan ORDER BY angkatan DESC";
		$sql = "SELECT left(ID, 2) as angkatan, count(*) as jumlah FROM peserta WHERE fakultas='".$faculty."' AND jenjang = 'd3/s1' GROUP BY angkatan ORDER BY angkatan DESC";
		//echo $sql;dashboard/
		$query = $this->db->query($sql);
		return $query->result();
	}
	
	function get_by_pasca()
	{
		//$sql = "SELECT left(a.user_ID, 2) as angkatan, count(*) as jumlah, max((a.lis+a.str+a.rv)*10/3) as max_skor, avg((a.lis+a.str+a.rv)*10/3) as avg_skor, min((a.lis+a.str+a.rv)*10/3) as min_skor FROM `nilai` a, peserta b WHERE a.user_ID = b.ID AND b.fakultas='".$faculty."' GROUP BY angkatan ORDER BY angkatan DESC";
		$sql = "SELECT left(ID, 2) as angkatan, count(*) as jumlah FROM peserta WHERE jenjang = 'pasca/umum' GROUP BY angkatan ORDER BY angkatan DESC";
		//echo $sql;dashboard/
		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_by_faculty_year($faculty, $year)
	{
		//$sql = "SELECT ID, nama, fakultas, jurusan_ps, jenjang, DATE_FORMAT(DATE('1899-12-30') + INTERVAL tgl_lahir DAY, '%d/%m/%Y') as normal_tgl_lahir FROM `peserta` WHERE fakultas='".$faculty."' AND LEFT(ID, 2) = '".$year."' AND jenjang = 'd3/s1' ORDER BY ID ASC";
		$sql = "SELECT ID, nama, fakultas, jurusan_ps, jenjang, tgl_lahir as normal_tgl_lahir FROM `peserta` WHERE fakultas='".$faculty."' AND LEFT(ID, 2) = '".$year."' AND jenjang = 'd3/s1' ORDER BY ID ASC";
		//echo $sql;
		$query = $this->db->query($sql);
		return $query->result();
	}
	
	function get_by_pasca_year($year)
	{
		//$sql = "SELECT ID, nama, fakultas, jurusan_ps, jenjang, DATE_FORMAT(DATE('1899-12-30') + INTERVAL tgl_lahir DAY, '%d/%m/%Y') as normal_tgl_lahir FROM `peserta` WHERE LEFT(ID, 2) = '".$year."' AND jenjang = 'pasca/umum' ORDER BY ID ASC";
		$sql = "SELECT ID, nama, fakultas, jurusan_ps, jenjang, tgl_lahir as normal_tgl_lahir FROM `peserta` WHERE LEFT(ID, 2) = '".$year."' AND jenjang = 'pasca/umum' ORDER BY ID ASC";
		//echo $sql;
		$query = $this->db->query($sql);
		return $query->result();
	}
}
