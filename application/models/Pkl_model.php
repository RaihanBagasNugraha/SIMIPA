<?php 
class Pkl_model extends CI_Model
{

    function get_pkl_kajur($iduser)
    {
        $result = $this->db->query("SELECT pkl_periode.* FROM pkl_periode,tbl_users_dosen WHERE tbl_users_dosen.id_user = $iduser AND tbl_users_dosen.jurusan = pkl_periode.jurusan ORDER BY pkl_periode.tahun ")->result();
		return $result;
    }

    function insert_pkl_periode($jurusan,$periode,$tahun)
    {
        $this->db->query("INSERT INTO pkl_periode(jurusan, periode, tahun) VALUES ($jurusan,$periode,$tahun)");
		$insert_id = $this->db->insert_id();
		return $insert_id;
    }

    function insert_pkl_periode_meta($id_periode,$timeline,$start,$end)
    {
        $this->db->query("INSERT INTO pkl_periode_meta(id_periode, timeline, date_start, date_end) VALUES ($id_periode,$timeline,'$start','$end')");
    }

    function get_pkl_kajur_by_id($id_pkl)
    {
        $result = $this->db->query("SELECT * FROM pkl_periode WHERE id_pkl = $id_pkl")->row();
		return $result;
    }

    function get_pkl_periode_meta($id_pkl)
    {
        $result = $this->db->query("SELECT pkl_periode_meta.*,pkl_periode_timeline.keterangan FROM pkl_periode_meta,pkl_periode,pkl_periode_timeline WHERE pkl_periode.id_pkl = $id_pkl AND pkl_periode.id_pkl = pkl_periode_meta.id_periode AND pkl_periode_timeline.id = pkl_periode_meta.timeline")->result();
		return $result;
    }

    function get_pkl_periode_meta_row($id_pkl,$timeline)
    {
        $result = $this->db->query("SELECT * FROM pkl_periode_meta WHERE id_periode = $id_pkl AND timeline = $timeline")->row();
		return $result;
    }

    function pkl_periode_meta_update($id_pkl,$start,$end)
    {
        $this->db->query("UPDATE pkl_periode_meta SET date_start='$start',date_end='$end' WHERE id = $id_pkl"); 
    }

    function update_pkl_periode($id_pkl,$periode,$tahun)
    {
        $this->db->query("UPDATE pkl_periode SET periode=$periode,tahun=$tahun WHERE id_pkl = $id_pkl");
    }

    function delete_pkl_periode($id_pkl)
    {
        $this->db->query("DELETE FROM pkl_periode WHERE id_pkl = $id_pkl");
    }

    function delete_pkl_periode_meta($id_pkl)
    {
        $this->db->query("DELETE FROM pkl_periode_meta WHERE id_periode = $id_pkl");
    }

    function cek_pkl_periode($periode,$tahun,$jurusan)
    {
        $result = $this->db->query("SELECT * FROM pkl_periode WHERE periode = $periode AND tahun = $tahun AND jurusan = $jurusan")->row();
		return $result;
    }

    function insert_pkl_lokasi($data)
    {
        $this->db->insert('pkl_lokasi', $data);	
    }

    function get_lokasi_pkl($id)
    {
        $result = $this->db->query("SELECT * FROM pkl_lokasi WHERE id_pkl = $id")->result();
		return $result;
    }

    function get_lokasi_pkl_by_id($id_lokasi)
    {
        $result = $this->db->query("SELECT * FROM pkl_lokasi WHERE id = $id_lokasi")->row();
		return $result;   
    }

    function get_lokasi_pkl_not_full($id)
    {
        $result = $this->db->query("SELECT * FROM pkl_lokasi WHERE maks_kuota != isi_kuota AND id_pkl = $id")->result();
		return $result;
    }

    function delete_pkl_lokasi($where)
    {
        $this->db->query("DELETE FROM pkl_lokasi WHERE id = $where");
    }

    function update_pkl_lokasi($id_lokasi,$lokasi,$kuota,$alamat)
    {
        $this->db->query("UPDATE pkl_lokasi SET lokasi='$lokasi',alamat='$alamat',maks_kuota=$kuota WHERE id = $id_lokasi");
    }

    function select_active_kp($npm)
    {
        $this->db->where('npm =', $npm);
		$this->db->where('status !=', '6');
		$query = $this->db->get('pkl_mahasiswa');
		return $query->result();
    }

    function selet_kp_by_npm($npm)
	{
        $result = $this->db->query("SELECT * FROM pkl_mahasiswa WHERE pkl_mahasiswa.npm = $npm AND pkl_mahasiswa.id_periode IN (SELECT id_pkl FROM pkl_periode WHERE pkl_periode.id_pkl = pkl_mahasiswa.id_periode )")->result();
		return $result; 
	}

    function select_pkl_by_id($id, $npm)
	{
        $result = $this->db->query("SELECT * FROM pkl_mahasiswa WHERE pkl_mahasiswa.pkl_id = $id AND pkl_mahasiswa.npm = $npm AND (pkl_mahasiswa.status = '-1' OR status = '5') AND pkl_mahasiswa.id_periode IN (SELECT id_pkl FROM pkl_periode WHERE pkl_periode.id_pkl = pkl_mahasiswa.id_periode )");
		return $query->result_array();
    }

    function select_pkl_by_id_pkl($id)
	{
		$result = $this->db->query("SELECT * FROM pkl_mahasiswa WHERE pkl_id = $id")->row();
		return $result;
    }
    
    function get_pkl_periode($year,$jurusan)
    {
        $result = $this->db->query("SELECT * FROM pkl_periode,pkl_periode_meta WHERE pkl_periode.tahun = $year AND pkl_periode.jurusan = $jurusan AND pkl_periode.id_pkl = pkl_periode_meta.id_periode AND NOW() BETWEEN pkl_periode_meta.date_start  AND DATE_ADD(pkl_periode_meta.date_end,INTERVAL 1 Day ) AND pkl_periode_meta.timeline = 1")->row();
		return $result;
    }

    function insert_pkl_mahasiswa($data)
    {
        $this->db->insert('pkl_mahasiswa', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    function insert_approval_pkl($data)
    {
        $this->db->insert('pkl_mahasiswa_approval', $data);
    }

    function select_lampiran_by_pkl($id, $username)
	{
		$this->db->select('a.*, b.nama');
		$this->db->from('pkl_mahasiswa_berkas a');
		$this->db->join('jenis_berkas_lampiran b', 'a.jenis_berkas = b.id_jenis');
		$this->db->join('pkl_mahasiswa c', 'a.id_pkl = c.pkl_id');
		$this->db->where(array('a.id_pkl' => $id, 'c.npm' => $username));
		$query = $this->db->get();
		
		return $query->result();
    }
    
    function update_pengajuan_pkl($data, $where, $data2)
	{
		$this->db->where('pkl_id', $where);
        $this->db->update('pkl_mahasiswa', $data);
        
        $userid = $this->session->userdata('userId');
        $this->db->where('pkl_id', $where);
        $this->db->where('status_slug', "Mahasiswa");
        $this->db->where('id_user', $userid);
        $this->db->update('pkl_mahasiswa_approval', $data2);
    }
    
    function delete_pengajuan_pkl($data)
	{
        $this->db->delete('pkl_mahasiswa', $data);
        $this->db->delete('pkl_mahasiswa_approval', $data);
    }
    
    function select_lampiran_by_kp($id, $username)
	{
		$this->db->select('a.*, b.nama');
		$this->db->from('pkl_mahasiswa_berkas a');
		$this->db->join('jenis_berkas_lampiran b', 'a.jenis_berkas = b.id_jenis');
		$this->db->join('pkl_mahasiswa c', 'a.id_pkl = c.pkl_id');
		$this->db->where(array('a.id_pkl' => $id, 'c.npm' => $username));
		$query = $this->db->get();
		
		return $query->result();
    }
    
    function insert_lampiran_kp($data)
	{
		$this->db->trans_start();
		$this->db->insert('pkl_mahasiswa_berkas', $data);
		$this->db->trans_complete();
    }
    
    function delete_lampiran_kp($data)
	{
		$this->db->delete('pkl_mahasiswa_berkas', $data);
    }
    
    function ajukan_pkl($where)
	{
		$this->db->where('pkl_id', $where);
	    $this->db->update('pkl_mahasiswa', array('status' => '0'));
    }

    function ajukan_perbaikan_pkl($id,$status)
    {
        if($status == "pa"){
            $this->db->where('pkl_id', $id);
            $this->db->update('pkl_mahasiswa', array('status' => '0'));
        }
        elseif($status == "admin"){
            $this->db->where('pkl_id', $id);
            $this->db->update('pkl_mahasiswa', array('status' => '1'));
        }
       
    }
    
    //dosen
    function get_approve_pa_pkl($iduser)
    {
        $result = $this->db->query("SELECT pkl_mahasiswa.* FROM pkl_mahasiswa,tbl_users_mahasiswa WHERE tbl_users_mahasiswa.dosen_pa = $iduser AND tbl_users_mahasiswa.npm = pkl_mahasiswa.npm AND pkl_mahasiswa.status = 0 ORDER BY pkl_mahasiswa.created_at DESC")->result();
		return $result;
    }

    function perbaikan_pkl($where,$ket)
	{
		$this->db->where('pkl_id', $where);
	    $this->db->update('pkl_mahasiswa', array('status' => '5', 'keterangan_tolak' => $ket));
    }

    function tolak_pkl($where,$ket)
	{
		$this->db->where('pkl_id', $where);
	    $this->db->update('pkl_mahasiswa', array('status' => '6', 'keterangan_tolak' => $ket));
    }

    function pkl_approve_setujui($status,$where,$user_id,$ttd)
    {
        if($status == "pa"){
            $this->db->where('pkl_id', $where);
            $this->db->update('pkl_mahasiswa', array('status' => '1'));
            
            $this->db->insert('pkl_mahasiswa_approval', array('pkl_id' => $where,'status_slug'=>"Pembimbing Akademik","id_user"=>$user_id,"ttd"=>$ttd));
        }

        elseif($status == "admin"){
            $this->db->where('pkl_id', $where);
            $this->db->update('pkl_mahasiswa', array('status' => '2'));
            
            $this->db->insert('pkl_mahasiswa_approval', array('pkl_id' => $where,'status_slug'=>"Administrasi","id_user"=>$user_id,"ttd"=>$ttd));
        }
    }


    //tendik
    function get_verifikasi_berkas_pkl($id)
	{
		$query = $this->db->query("SELECT * FROM pkl_mahasiswa,pkl_periode,tbl_users_tendik,tbl_users_mahasiswa WHERE tbl_users_tendik.id_user = $id AND tbl_users_tendik.unit_kerja = tbl_users_mahasiswa.jurusan AND tbl_users_mahasiswa.npm = pkl_mahasiswa.npm AND pkl_mahasiswa.id_periode = pkl_periode.id_pkl AND pkl_mahasiswa.status = 1 ORDER BY pkl_mahasiswa.created_at DESC");	
		return $query->result();
    }
    

    //koor
    function get_approve_koor_pkl($id)
	{
		$query = $this->db->query("SELECT pkl_mahasiswa.* FROM pkl_mahasiswa,tbl_users_mahasiswa, tbl_users_dosen, pkl_periode WHERE tbl_users_dosen.id_user = $id AND tbl_users_dosen.jurusan = tbl_users_mahasiswa.jurusan AND tbl_users_mahasiswa.npm = pkl_mahasiswa.npm AND pkl_mahasiswa.id_periode = pkl_periode.id_pkl AND pkl_mahasiswa.status = 2 ORDER BY pkl_mahasiswa.created_at DESC, id_lokasi");	
		return $query->result();
    }
    
    function get_approve_koor_lokasi($id)
    {
        $query = $this->db->query("SELECT pkl_lokasi.*,pkl_periode.* FROM pkl_periode, pkl_periode_meta, tbl_users_dosen, pkl_lokasi WHERE tbl_users_dosen.id_user = $id AND tbl_users_dosen.jurusan = pkl_periode.jurusan AND pkl_periode.id_pkl = pkl_periode_meta.id_periode AND pkl_periode_meta.timeline = 1 AND pkl_periode.id_pkl = pkl_lokasi.id_pkl ORDER BY pkl_periode.tahun DESC,pkl_periode.periode");	
		return $query->result();
    }

    function get_jml_mahasiswa_lokasi_daftar($id_lok)
    {
        $query = $this->db->query("SELECT COUNT(*) as jml FROM pkl_mahasiswa WHERE pkl_mahasiswa.id_lokasi = $id_lok AND pkl_mahasiswa.status = 2");	
		return $query->row();
    }

    function get_pkl_by_lokasi($id)
    {
        
    }


}
?>