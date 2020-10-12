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

    function update_pkl_lokasi($id_lokasi,$lokasi,$alamat)
    {
        $this->db->query("UPDATE pkl_lokasi SET lokasi='$lokasi',alamat='$alamat' WHERE id = $id_lokasi");
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
		return $result->result_array();
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

    function insert_lampiran_kp_instansi($where,$data,$nama_file)
	{
		$this->db->where('approval_id', $where);
	    $this->db->update('pkl_mahasiswa_approval_koor', array('nama_file'=>$nama_file,'file' => $data));
    }
    
    function delete_lampiran_kp($data)
	{
		$this->db->delete('pkl_mahasiswa_berkas', $data);
    }

    function delete_lampiran_kp_instansi($where)
	{
		$this->db->where('approval_id', $where);
	    $this->db->update('pkl_mahasiswa_approval_koor', array('nama_file' => NULL,'file' => NULL));
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
	    $this->db->update('pkl_mahasiswa', array('status' => '6', 'no_penetapan'=>NULL,'keterangan_tolak' => $ket));
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

        elseif($status == "kaprodi"){
            $this->db->where('pkl_id', $where);
            $this->db->update('pkl_mahasiswa', array('status' => '7'));
            
            $this->db->insert('pkl_mahasiswa_approval', array('pkl_id' => $where,'status_slug'=>"Ketua Program Studi","id_user"=>$user_id,"ttd"=>$ttd));
        }

        elseif($status == "kajur"){
            $this->db->where('pkl_id', $where);
            $this->db->update('pkl_mahasiswa', array('status' => '8'));
            
            $this->db->insert('pkl_mahasiswa_approval', array('pkl_id' => $where,'status_slug'=>"Ketua Jurusan","id_user"=>$user_id,"ttd"=>$ttd));
        }
    }


    //tendik
    function get_verifikasi_berkas_pkl($id)
	{
		$query = $this->db->query("SELECT * FROM pkl_mahasiswa,pkl_periode,tbl_users_tendik,tbl_users_mahasiswa WHERE tbl_users_tendik.id_user = $id AND tbl_users_tendik.unit_kerja = tbl_users_mahasiswa.jurusan AND tbl_users_mahasiswa.npm = pkl_mahasiswa.npm AND pkl_mahasiswa.id_periode = pkl_periode.id_pkl AND pkl_mahasiswa.status = 1 ORDER BY pkl_mahasiswa.created_at DESC");	
		return $query->result();
    }

    function get_approve_tendik_lokasi($id)
    {
        $query = $this->db->query("SELECT pkl_lokasi.*,pkl_periode.* FROM pkl_periode, pkl_periode_meta, tbl_users_tendik, pkl_lokasi WHERE tbl_users_tendik.id_user = $id AND tbl_users_tendik.unit_kerja = pkl_periode.jurusan AND pkl_periode.id_pkl = pkl_periode_meta.id_periode AND pkl_periode_meta.timeline = 12 AND NOW() <= DATE_ADD(pkl_periode_meta.date_start,INTERVAL 1 Day ) AND pkl_periode.id_pkl = pkl_lokasi.id_pkl ORDER BY pkl_periode.tahun DESC,pkl_periode.periode");	
		return $query->result();
    }
    
    function get_jml_mahasiswa_lokasi_daftar_tendik($id_lok,$id_user)
    {
        $query = $this->db->query("SELECT COUNT(*) as jml FROM pkl_mahasiswa,tbl_users_tendik,pkl_periode,pkl_lokasi WHERE tbl_users_tendik.id_user = $id_user AND tbl_users_tendik.unit_kerja = pkl_periode.jurusan AND pkl_mahasiswa.id_lokasi = $id_lok AND pkl_lokasi.id = pkl_mahasiswa.id_lokasi AND pkl_lokasi.id_pkl = pkl_periode.id_pkl AND ((pkl_mahasiswa.status >= 0 AND pkl_mahasiswa.status != 6 AND pkl_mahasiswa.status < 2) OR pkl_mahasiswa.status = 5 )");	
		return $query->row();
    }

    function get_mahasiswa_lokasi_daftar_tendik($id_lok,$id_user)
    {
        $query = $this->db->query("SELECT * FROM pkl_mahasiswa,tbl_users_tendik,pkl_periode,pkl_lokasi WHERE tbl_users_tendik.id_user = $id_user AND tbl_users_tendik.unit_kerja = pkl_periode.jurusan AND pkl_mahasiswa.id_lokasi = $id_lok AND pkl_lokasi.id = pkl_mahasiswa.id_lokasi AND pkl_lokasi.id_pkl = pkl_periode.id_pkl AND ((pkl_mahasiswa.status >= 0 AND pkl_mahasiswa.status != 6 AND pkl_mahasiswa.status < 2) OR pkl_mahasiswa.status = 5 )");	
		return $query->result();
    }

    function insert_mhs_app_koor_tendik($data)
    {
        $this->db->trans_start();
		$this->db->insert('pkl_mahasiswa_approval_koor', $data);
		$this->db->trans_complete();
    }
    //end tendik

    //koor
    function get_approve_koor_pkl($id)
	{
		$query = $this->db->query("SELECT pkl_mahasiswa.* FROM pkl_mahasiswa,tbl_users_mahasiswa, tbl_users_dosen, pkl_periode WHERE tbl_users_dosen.id_user = $id AND tbl_users_dosen.jurusan = tbl_users_mahasiswa.jurusan AND tbl_users_mahasiswa.npm = pkl_mahasiswa.npm AND pkl_mahasiswa.id_periode = pkl_periode.id_pkl AND pkl_mahasiswa.status = 2 ORDER BY pkl_mahasiswa.created_at DESC, id_lokasi");	
		return $query->result();
    }
    
    function get_approve_koor_lokasi($id)
    {
        $query = $this->db->query("SELECT pkl_mahasiswa_approval_koor.*, pkl_periode.*, pkl_lokasi.* FROM pkl_periode, pkl_periode_meta, tbl_users_dosen, pkl_lokasi, pkl_mahasiswa_approval_koor WHERE tbl_users_dosen.id_user = $id AND tbl_users_dosen.jurusan = pkl_periode.jurusan AND pkl_periode.id_pkl = pkl_periode_meta.id_periode AND pkl_periode_meta.timeline = 12 AND NOW() <= DATE_ADD(pkl_periode_meta.date_start,INTERVAL 1 Day ) AND pkl_periode.id_pkl = pkl_lokasi.id_pkl AND pkl_lokasi.id = pkl_mahasiswa_approval_koor.lokasi_id ORDER BY pkl_mahasiswa_approval_koor.created_at ");	
		return $query->result();
    }

    function get_approve_koor_lokasi_kaprodi($id)
    {
        $query = $this->db->query("SELECT pkl_mahasiswa_approval_koor.*, pkl_periode.*, pkl_lokasi.* FROM pkl_periode, pkl_periode_meta, tbl_users_dosen, pkl_lokasi, pkl_mahasiswa_approval_koor WHERE tbl_users_dosen.id_user = $id AND tbl_users_dosen.jurusan = pkl_periode.jurusan AND pkl_periode.id_pkl = pkl_periode_meta.id_periode AND pkl_periode_meta.timeline = 12 AND NOW() <= DATE_ADD(pkl_periode_meta.date_start,INTERVAL 1 Day ) AND pkl_periode.id_pkl = pkl_lokasi.id_pkl AND pkl_lokasi.id = pkl_mahasiswa_approval_koor.lokasi_id ORDER BY pkl_mahasiswa_approval_koor.created_at ");	
		return $query->result();
    }

    function get_pkl_mahasiswa_approval_koor_id($id)
    {
        $query = $this->db->query("SELECT * FROM `pkl_mahasiswa_approval_koor` WHERE approval_id = $id");	
		return $query->row();
    }

    function get_jml_mahasiswa_lokasi_daftar_koor($id_lok,$id_user,$no_penetapan)
    {
        $query = $this->db->query("SELECT COUNT(*) as jml FROM pkl_mahasiswa,tbl_users_dosen,pkl_periode,pkl_lokasi WHERE tbl_users_dosen.id_user = $id_user AND tbl_users_dosen.jurusan = pkl_periode.jurusan AND pkl_mahasiswa.id_lokasi = $id_lok AND pkl_mahasiswa.no_penetapan = '$no_penetapan' AND pkl_lokasi.id = pkl_mahasiswa.id_lokasi AND pkl_lokasi.id_pkl = pkl_periode.id_pkl AND ((pkl_mahasiswa.status >= 2 AND pkl_mahasiswa.status != 6))");	
		return $query->row();
    }

    function cek_jml_approval_selesai($id_lok,$id_user,$no_penetapan)
    {
        $query = $this->db->query("SELECT COUNT(*) as jml FROM pkl_mahasiswa,tbl_users_dosen,pkl_periode,pkl_lokasi WHERE tbl_users_dosen.id_user = $id_user AND tbl_users_dosen.jurusan = pkl_periode.jurusan AND pkl_mahasiswa.id_lokasi = $id_lok AND pkl_mahasiswa.no_penetapan = '$no_penetapan' AND pkl_lokasi.id = pkl_mahasiswa.id_lokasi AND pkl_lokasi.id_pkl = pkl_periode.id_pkl AND ((pkl_mahasiswa.status >= 2 AND pkl_mahasiswa.status != 6 AND pkl_mahasiswa.status != 8))");	
		return $query->row();
    }

    function get_mahasiswa_lokasi_daftar_koor($id_lok, $id_user,$no_penetapan)
    {
        $query = $this->db->query("SELECT pkl_mahasiswa.* FROM pkl_mahasiswa,tbl_users_dosen,pkl_periode,pkl_lokasi WHERE tbl_users_dosen.id_user = $id_user AND tbl_users_dosen.jurusan = pkl_periode.jurusan AND pkl_mahasiswa.id_lokasi = $id_lok AND pkl_mahasiswa.no_penetapan = '$no_penetapan' AND pkl_lokasi.id = pkl_mahasiswa.id_lokasi AND pkl_lokasi.id_pkl = pkl_periode.id_pkl AND ((pkl_mahasiswa.status >= 2 AND pkl_mahasiswa.status != 6))");	
		return $query->result();
    }

    function check_approval_mahasiswa_koor($approval_id)
    {
        $query = $this->db->query("SELECT * FROM pkl_mahasiswa_approval_koor, pkl_mahasiswa WHERE pkl_mahasiswa_approval_koor.approval_id = $approval_id AND pkl_mahasiswa_approval_koor.lokasi_id = pkl_mahasiswa.id_lokasi AND pkl_mahasiswa_approval_koor.no_penetapan = pkl_mahasiswa.no_penetapan AND (pkl_mahasiswa.status < 3 AND pkl_mahasiswa.status != 6 AND pkl_mahasiswa.status != 5 )");	
		return $query->result();
    }

    function update_approval_mahasiswa_koor($approval_id)
    {
        $this->db->where('approval_id', $approval_id);
	    $this->db->update('pkl_mahasiswa_approval_koor', array('status' => '1'));
    }
    // function get_jml_mahasiswa_lokasi_daftar($id_lok)
    // {
    //     $query = $this->db->query("SELECT COUNT(*) as jml FROM pkl_mahasiswa WHERE pkl_mahasiswa.id_lokasi = $id_lok AND (pkl_mahasiswa.status >= 0 AND pkl_mahasiswa.status != 6)");	
	// 	return $query->row();
    // }

    // function get_mahasiswa_lokasi_daftar($id_lok)
    // {
    //     $query = $this->db->query("SELECT * FROM pkl_mahasiswa WHERE pkl_mahasiswa.id_lokasi = $id_lok AND (pkl_mahasiswa.status >= 0 AND pkl_mahasiswa.status != 6)");	
	// 	return $query->result();
    // }

    //end koor
    function save_surat_pa($data)
    {
        $this->db->trans_start();
		$this->db->insert('staff_surat', $data);
		$this->db->trans_complete();
    }

    function get_surat_pkl($id_pkl)
    {
        $query = $this->db->query("SELECT * FROM staff_surat WHERE jenis = 3 AND id_jenis = $id_pkl");	
		return $query->row();
    }

    function get_surat_seminar_pkl($id_pkl)
    {
        $query = $this->db->query("SELECT * FROM staff_surat WHERE jenis = 4 AND id_jenis = $id_pkl");	
		return $query->row();
    }

    function pkl_add_no_surat($where,$surat)
    {
        $this->db->where('pkl_id', $where);
	    $this->db->update('pkl_mahasiswa', array('no_penetapan' => $surat));
    }

    function update_surat($where,$surat)
    {
        $this->db->where('jenis', 3);
        $this->db->where('id_jenis', $where);
	    $this->db->update('staff_surat', array('nomor' => $surat));
    }

    function add_approval_pkl($data)
    {
		$this->db->insert('pkl_mahasiswa_approval_koor', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    function add_approval_pkl_meta($data)
    {
        $this->db->trans_start();
		$this->db->insert('pkl_mahasiswa_approval_koor_meta', $data);
        $this->db->trans_complete();
    }

    function approval_koor_pkl($where,$data)
    {
        $this->db->where('pkl_id', $where);
	    $this->db->update('pkl_mahasiswa', $data);
    }

    function approval_koor_pkl7($where)
    {
        $this->db->where('pkl_id', $where);
	    $this->db->update('pkl_mahasiswa', array('status' => '7'));
    }

    function approval_koor_pkl4($where)
    {
        $this->db->where('pkl_id', $where);
	    $this->db->update('pkl_mahasiswa', array('status' => '4'));
    }

    function get_approval_id_by_pkl_id($pkl_id)
    {
        $query = $this->db->query("SELECT * FROM pkl_mahasiswa_approval_koor_meta WHERE pkl_id = $pkl_id ");	
		return $query->row();
    }

    function get_approval_koor_by_id($approval_id)
    {
        $query = $this->db->query("SELECT * FROM pkl_mahasiswa_approval_koor WHERE approval_id = $approval_id");	
		return $query->row();
    }

    function get_approval_koor_by_pkl_id($pkl_id)
    {
        $query = $this->db->query("SELECT pkl_mahasiswa_approval_koor.* FROM pkl_mahasiswa_approval_koor_meta, pkl_mahasiswa_approval_koor WHERE pkl_mahasiswa_approval_koor_meta.pkl_id = $pkl_id AND pkl_mahasiswa_approval_koor_meta.approval_id = pkl_mahasiswa_approval_koor.approval_id ");	
		return $query->row();
    }

    function approval_id_status($where,$status)
    {
        $this->db->where('approval_id', $where);
	    $this->db->update('pkl_mahasiswa_approval_koor',array('status' => $status));
    }

    function select_pkl_approval_koor($approval_id)
    {   
        $query = $this->db->query("SELECT * FROM `pkl_mahasiswa_approval_koor_meta` WHERE approval_id = $approval_id");	
		return $query->result();
    }

    function get_pkl_mahasiswa_approval_kaprodi($id_user)
    {
        $query = $this->db->query("SELECT pkl_mahasiswa.* FROM pkl_mahasiswa,tbl_users_dosen,tbl_users_mahasiswa WHERE tbl_users_dosen.id_user = $id_user AND tbl_users_dosen.jurusan = tbl_users_mahasiswa.jurusan AND tbl_users_mahasiswa.npm = pkl_mahasiswa.npm AND pkl_mahasiswa.npm LIKE '__0%' AND pkl_mahasiswa.status = 4");	
		return $query->result();
    }

    function get_pkl_mahasiswa_approval_kajur($id_user)
    {
        $query = $this->db->query("SELECT pkl_mahasiswa.* FROM pkl_mahasiswa,tbl_users_dosen,tbl_users_mahasiswa WHERE tbl_users_dosen.id_user = $id_user AND tbl_users_dosen.jurusan = tbl_users_mahasiswa.jurusan AND tbl_users_mahasiswa.npm = pkl_mahasiswa.npm AND pkl_mahasiswa.status = 7");	
		return $query->result();
    }

    function get_ttd_approval($id_pkl,$status)
    {
        $query = $this->db->query("SELECT * FROM pkl_mahasiswa_approval WHERE pkl_id = $id_pkl AND status_slug = '$status' ");	
		return $query->row();
    }

    function get_ttd_approval_seminar($id_seminar,$status)
    {
        $query = $this->db->query("SELECT * FROM pkl_seminar_approval WHERE seminar_id = $id_seminar AND status_slug = '$status' ");	
		return $query->row();
    }

    function date_start($id_periode)
    {
        $query = $this->db->query("SELECT * FROM `pkl_periode_meta` WHERE id_periode = $id_periode AND timeline = 3");	
		return $query->row();
    }

    function date_end($id_periode)
    {
        $query = $this->db->query("SELECT * FROM `pkl_periode_meta` WHERE id_periode = $id_periode AND timeline = 8");	
		return $query->row();
    }

    function get_mahasiswa_pkl_by_lok_no_per($lok_id,$nomor_penetapan,$per_id)
    {
        $query = $this->db->query("SELECT pkl_mahasiswa.* FROM pkl_mahasiswa WHERE pkl_mahasiswa.id_lokasi = $lok_id AND pkl_mahasiswa.no_penetapan = '$nomor_penetapan' AND pkl_mahasiswa.id_periode = $per_id");	
		return $query->result();
    }

    function get_mahasiswa_list_approve_id($approval_id)
    {
        $query = $this->db->query("SELECT * FROM pkl_mahasiswa_approval_koor, pkl_mahasiswa_approval_koor_meta, pkl_mahasiswa WHERE pkl_mahasiswa_approval_koor.approval_id = $approval_id AND pkl_mahasiswa_approval_koor_meta.approval_id = pkl_mahasiswa_approval_koor.approval_id AND pkl_mahasiswa_approval_koor_meta.pkl_id = pkl_mahasiswa.pkl_id ");	
		return $query->result();
    }

    function get_lokasi_approval_id($approval_id)
    {
        $query = $this->db->query("SELECT * FROM pkl_mahasiswa_approval_koor, pkl_lokasi WHERE pkl_mahasiswa_approval_koor.approval_id = $approval_id AND pkl_lokasi.id = pkl_mahasiswa_approval_koor.lokasi_id ");	
		return $query->row();
    }
    //seminar

    function check_pkl_done($npm)
    {
        $query = $this->db->query("SELECT * FROM `pkl_mahasiswa` WHERE npm = $npm AND status = 8 ");	
		return $query->row();
    }

    function get_pkl_seminar($id_pkl)
    {
        $query = $this->db->query("SELECT * FROM `pkl_seminar` WHERE pkl_id = $id_pkl");	
		return $query->result();
    }

    function get_pkl_seminar_cek($id_pkl)
    {
        $query = $this->db->query("SELECT * FROM `pkl_seminar` WHERE pkl_id = $id_pkl AND status != 6");	
		return $query->result();
    }

    function select_active_seminar_kp($id)
    {
        $this->db->where('seminar_id', $id);
		$this->db->where('status !=', '6');
		$query = $this->db->get('pkl_seminar');
		return $query->result();
    }

    function select_pkl_seminar_by_id($id)
    {
        $this->db->where('seminar_id', $id);
		$query = $this->db->get('pkl_seminar');
		return $query->result_array();
    }

    function get_seminar_by_id($id)
    {
        $this->db->where('seminar_id', $id);
		$query = $this->db->get('pkl_seminar');
		return $query->row();
    }

    function periode_seminar_kp($tahun,$jurusan)
    {
        $query = $this->db->query("SELECT * FROM pkl_periode,pkl_periode_meta WHERE pkl_periode.tahun = $tahun AND pkl_periode.jurusan = $jurusan AND pkl_periode.id_pkl = pkl_periode_meta.id_periode AND NOW() <= DATE_ADD(pkl_periode_meta.date_start,INTERVAL 1 Day ) AND pkl_periode_meta.timeline = 11 ");	
		return $query->row();
    }

    function input_seminar($data)
    {
        $this->db->insert('pkl_seminar', $data);
        $insert_id = $this->db->insert_id();
		return $insert_id;
    }

    function input_approval_seminar($data)
    {
        $this->db->insert('pkl_seminar_approval', $data);
    }

    function select_lampiran_seminar_kp($id)
	{
		$this->db->select('a.*, b.nama');
		$this->db->from('pkl_seminar_berkas a');
		$this->db->join('jenis_berkas_lampiran b', 'a.jenis_berkas = b.id_jenis');
        $this->db->join('pkl_seminar c', 'a.seminar_id = c.seminar_id');
		$this->db->where(array('a.seminar_id' => $id));
		$query = $this->db->get();
		
		return $query->result();
    }

    function update_seminar($where,$data)
    {
	    $this->db->where('seminar_id', $where);
	    $this->db->update('pkl_seminar', $data);
    }

    function update_approval_seminar($where,$status,$ttd)
    {
        $this->db->where('seminar_id', $where);
        $this->db->where('status_slug', $status);
	    $this->db->update('pkl_seminar_approval',array("ttd"=>$ttd));
    }

    function delete_seminar($data)
    {
        $this->db->delete('pkl_seminar', $data);
        $this->db->delete('pkl_seminar_approval', $data);
    }

    function insert_lampiran_seminar_kp($data)
	{
		$this->db->trans_start();
		$this->db->insert('pkl_seminar_berkas', $data);
		$this->db->trans_complete();
    }

    function delete_lampiran_seminar_kp($data)
	{
		$this->db->delete('pkl_seminar_berkas', $data);
    }

    function delete_berkas_seminar_kp($data)
	{
		$query = $this->db->query('SELECT file FROM pkl_seminar_berkas WHERE seminar_id ='.$data);
		$results = $query->result();

		foreach ($results as $row)
		{
			unlink($row->file);
        }
        
        $this->db->delete('pkl_seminar_berkas', array("seminar_id"=>$data));
    }	
    
    function delete_berkas_kp($data)
	{
		$query = $this->db->query('SELECT file FROM pkl_mahasiswa_berkas WHERE id_pkl ='.$data);
		$results = $query->result();

		foreach ($results as $row)
		{
			unlink($row->file);
        }
        
        $this->db->delete('pkl_mahasiswa_berkas', array("id_pkl"=>$data));
	}	

    function update_seminar_pkl($where,$status)
    {
        $this->db->where('seminar_id', $where);
	    $this->db->update('pkl_seminar',array("status"=>$status));
    }

    function update_seminar_surat_pkl($where,$nomor)
    {
        $this->db->where('seminar_id', $where);
	    $this->db->update('pkl_seminar',array("no_form"=>$nomor));
    }

    function get_pb_lapangan($pkl_id)
    {
        $this->db->where('pkl_id', $pkl_id);
		$query = $this->db->get('pkl_mahasiswa_pb_lapangan');
		return $query->row();
    }

    function insert_pb_lapangan($data)
    {
        $this->db->trans_start();
		$this->db->insert('pkl_mahasiswa_pb_lapangan', $data);
		$this->db->trans_complete();
    }

    function update_pb_lapangan($where,$data)
    {
        $this->db->where('pkl_id', $where);
	    $this->db->update('pkl_mahasiswa_pb_lapangan',$data);
    }

    function get_pb_lapangan_by_npm($npm)
    {
        $query = $this->db->query("SELECT * FROM pkl_mahasiswa,pkl_mahasiswa_pb_lapangan WHERE pkl_mahasiswa.npm = $npm AND pkl_mahasiswa.pkl_id = pkl_mahasiswa_pb_lapangan.pkl_id ");	
		return $query->row();
    }

    function get_mahasiswa_pkl_bimbingan($user_id)
    {
        $query = $this->db->query("SELECT * FROM pkl_seminar, pkl_mahasiswa WHERE pkl_mahasiswa.pembimbing = $user_id AND pkl_mahasiswa.pkl_id = pkl_seminar.pkl_id AND pkl_seminar.status = 0");	
		return $query->result();
    }

    function perbaiki_seminar_pkl($where,$keterangan,$status)
    {
        if($status == "pbb"){
            $this->db->where('seminar_id', $where);
	        $this->db->update('pkl_seminar',array("status"=>5,"keterangan_tolak"=>$keterangan));
        }
        elseif($status == "admin"){
            $this->db->where('seminar_id', $where);
	        $this->db->update('pkl_seminar',array("status"=>5,"keterangan_tolak"=>$keterangan));
        }
        elseif($status == "koor"){
            $this->db->where('seminar_id', $where);
	        $this->db->update('pkl_seminar',array("status"=>6,"keterangan_tolak"=>$keterangan));
        }
        
    }

    function ajukan_perbaikan_seminar_pkl($where,$status)
    {
        if($status == "pbb"){
            $this->db->where('seminar_id', $where);
	        $this->db->update('pkl_seminar',array("status"=>0,"keterangan_tolak"=>NULL));
        }
        if($status == "admin"){
            $this->db->where('seminar_id', $where);
	        $this->db->update('pkl_seminar',array("status"=>1,"keterangan_tolak"=>NULL));
        }
    }

    function get_seminar_tendik($id_user)
    {
        $query = $this->db->query("SELECT pkl_seminar.*,pkl_mahasiswa.npm,pkl_mahasiswa.id_periode FROM pkl_seminar,tbl_users_tendik,pkl_mahasiswa,tbl_users_mahasiswa WHERE tbl_users_tendik.id_user = $id_user AND tbl_users_tendik.unit_kerja = tbl_users_mahasiswa.jurusan AND tbl_users_mahasiswa.npm = pkl_mahasiswa.npm AND pkl_mahasiswa.pkl_id = pkl_seminar.pkl_id AND pkl_seminar.status = 1");	
		return $query->result();
    }

    function get_seminar_by_npm($npm)
    {
        $query = $this->db->query("SELECT * FROM pkl_mahasiswa,pkl_seminar WHERE pkl_mahasiswa.npm = $npm AND pkl_mahasiswa.pkl_id = pkl_seminar.pkl_id AND pkl_seminar.status >= 0");	
		return $query->row();
    }

    function get_seminar_aktif_by_pkl_id($id)
    {
        $query = $this->db->query("SELECT * FROM `pkl_seminar` WHERE pkl_id = $id AND status >= 0");	
		return $query->row();
    }

    function update_seminar_staff_surat_pkl($where,$nomor)
    {
        $this->db->where('jenis', 4);
        $this->db->where('id_jenis', $where);
	    $this->db->update('staff_surat',array("nomor"=>$nomor));
    }

    function get_seminar_mahasiswa_koor($id_user)
    {
        $query = $this->db->query("SELECT * FROM pkl_seminar, tbl_users_dosen,pkl_mahasiswa,tbl_users_mahasiswa WHERE tbl_users_dosen.id_user = $id_user AND tbl_users_dosen.jurusan = tbl_users_mahasiswa.jurusan AND tbl_users_mahasiswa.npm = pkl_mahasiswa.npm AND pkl_mahasiswa.pkl_id = pkl_seminar.pkl_id AND pkl_seminar.status = 2");	
		return $query->result();
    }

    function approve_koor_seminar($id,$data)
    {
        $this->db->where('seminar_id', $id);
	    $this->db->update('pkl_seminar',$data);
    }

    function get_mahasiswa_pkl_seminar_kajur($id_user)
    {
        $query = $this->db->query("SELECT pkl_seminar.*,pkl_mahasiswa.id_periode,pkl_mahasiswa.id_lokasi FROM pkl_seminar, tbl_users_dosen, tbl_users_mahasiswa,pkl_mahasiswa WHERE tbl_users_dosen.id_user = $id_user AND tbl_users_dosen.jurusan = tbl_users_mahasiswa.jurusan AND tbl_users_mahasiswa.npm NOT LIKE '__0%' AND tbl_users_mahasiswa.npm = pkl_mahasiswa.npm AND pkl_mahasiswa.pkl_id = pkl_seminar.pkl_id AND pkl_seminar.status = 3");	
		return $query->result();
    }

    function get_mahasiswa_pkl_seminar_kaprodi($id_user)
    {
        $query = $this->db->query("SELECT pkl_seminar.*,pkl_mahasiswa.id_periode,pkl_mahasiswa.id_lokasi FROM pkl_seminar, tbl_users_dosen, tbl_users_mahasiswa,pkl_mahasiswa WHERE tbl_users_dosen.id_user = $id_user AND tbl_users_dosen.jurusan = tbl_users_mahasiswa.jurusan AND tbl_users_mahasiswa.npm LIKE '__0%' AND tbl_users_mahasiswa.npm = pkl_mahasiswa.npm AND pkl_mahasiswa.pkl_id = pkl_seminar.pkl_id AND pkl_seminar.status = 3");	
		return $query->result();
    }

}
?>