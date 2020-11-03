<?php 
class Layanan_model extends CI_Model
{
    var $tbl_layanan = 'layanan_fakultas';

    function select_layanan_by_bagian($bagian)
	{
		$result = $this->db->query("SELECT * FROM `layanan_fakultas` WHERE bagian LIKE '$bagian' ORDER BY nama");
		return $result->result();
    }

    function select_layanan_by_id($id)
    {
        $result = $this->db->query("SELECT * FROM `layanan_fakultas` WHERE id_layanan_fakultas = $id");
		return $result->row();
    }

    function select_layanan_atribut_by_id($id)
    {
        $result = $this->db->query("SELECT * FROM `layanan_fakultas_atribut` WHERE id_layanan = $id");
		return $result->result();
    }

    function insert_atribut_layanan($data)
    {
        $this->db->insert('layanan_fakultas_atribut', $data);	
    }

    function insert_layanan_fak_mhs($data)
    {
        $this->db->insert('layanan_fakultas_mahasiswa', $data);	
        $insert_id = $this->db->insert_id();
		return $insert_id;
    }  

    function insert_layanan_fak_mhs_meta($data)
    {
        $this->db->insert('layanan_fakultas_mahasiswa_meta', $data);	
    }  

    function get_form_mhs($npm)
    {
        $result = $this->db->query("SELECT * FROM `layanan_fakultas_mahasiswa` WHERE npm = $npm");
		return $result->result();
    }

    function get_form_mhs2($npm,$bagian)
    {
        $result = $this->db->query("SELECT layanan_fakultas_mahasiswa.* FROM layanan_fakultas_mahasiswa, layanan_fakultas WHERE layanan_fakultas_mahasiswa.npm = $npm AND layanan_fakultas_mahasiswa.id_layanan_fakultas = layanan_fakultas.id_layanan_fakultas AND layanan_fakultas.bagian LIKE '$bagian' order by layanan_fakultas_mahasiswa.created_at desc ");
		return $result->result();
    }

    function get_keterangan_form($id_layanan)
    {
        $result = $this->db->query("SELECT * FROM layanan_fakultas_mahasiswa_meta a, layanan_fakultas_atribut b WHERE a.id_layanan_fak_mhs = $id_layanan AND a.meta_key = b.id_atribut");
		return $result->result();
    }
    
    function delete_layanan_mhs($id)
	{
        $this->db->delete('layanan_fakultas_mahasiswa', array('id' => $id));
        $this->db->delete('layanan_fakultas_mahasiswa_meta', array('id_layanan_fak_mhs' => $id));
    }

    function select_layanan_fakultas_mhs_id($id)
    {
        $result = $this->db->query("SELECT * FROM `layanan_fakultas_mahasiswa` WHERE id = $id");
		return $result->row();
    }
    
    function select_layanan_fakultas_mhs_id_layanan($id)
    {
        $result = $this->db->query("SELECT * FROM `layanan_fakultas_mahasiswa_meta` WHERE id_layanan_fak_mhs = $id ORDER BY id");
		return $result->result();
    } 

    function get_tugas_tambahan_user($tugas)
    {
        $cek = $this->db->query("SELECT * FROM tugas_tambahan a, tbl_users_tugas b, tbl_users c WHERE a.nama LIKE '%$tugas%' AND a.id_tugas_tambahan = b.tugas AND b.id_user = c.userId")->row(); 
        if($cek->roleId == 2){
            $result = $this->db->query("SELECT c.*,d.* FROM tugas_tambahan a, tbl_users_tugas b, tbl_users_dosen c, tbl_users d WHERE a.nama LIKE '%$tugas%' AND a.id_tugas_tambahan = b.tugas AND b.aktif = 1 AND b.id_user = c.id_user AND c.id_user = d.userId");
        }
        elseif($cek->roleId == 4){
            $result = $this->db->query("SELECT c.*,d.* FROM tugas_tambahan a, tbl_users_tugas b, tbl_users_tendik c, tbl_users d WHERE a.nama LIKE '%$tugas%' AND a.id_tugas_tambahan = b.tugas AND b.aktif = 1 AND b.id_user = c.id_user AND c.id_user = d.userId");
        }     
		return $result->row();
    }

    function get_kaprodi_doktor()
    {
        $result = $this->db->query("SELECT c.*,d.* FROM tugas_tambahan a, tbl_users_tugas b, tbl_users_dosen c, tbl_users d WHERE a.id_tugas_tambahan = 14 AND a.id_tugas_tambahan = b.tugas AND b.aktif = 1 AND b.prodi = 12 AND b.id_user = c.id_user AND c.id_user = d.userId");
		return $result->row();
    }

    function get_kalab_by_nama($nama_lab)
    {
        $cek = $this->db->query("SELECT * FROM tugas_tambahan a, tbl_users_tugas b, tbl_users c, laboratorium d WHERE a.id_tugas_tambahan = 15 AND d.nama_lab LIKE '%$nama_lab%' AND a.id_tugas_tambahan = b.tugas AND d.id_lab = b.jurusan_unit AND b.id_user = c.userId")->row(); 
        if($cek->roleId == 2){
            $result = $this->db->query("SELECT d.*,e.* FROM tugas_tambahan a, laboratorium b, tbl_users_tugas c, tbl_users_dosen d, tbl_users e WHERE a.id_tugas_tambahan = 15 AND a.id_tugas_tambahan = c.tugas AND b.nama_lab LIKE '%$nama_lab%' AND b.id_lab = c.jurusan_unit AND c.id_user = d.id_user AND d.id_user = e.userId");
        }
        elseif($cek->roleId == 4){
            $result = $this->db->query("SELECT d.*,e.* FROM tugas_tambahan a, laboratorium b, tbl_users_tugas c, tbl_users_tendik d, tbl_users e WHERE a.id_tugas_tambahan = 15 AND a.id_tugas_tambahan = c.tugas AND b.nama_lab LIKE '%$nama_lab%' AND b.id_lab = c.jurusan_unit AND c.id_user = d.id_user AND d.id_user = e.userId");
        }     
		return $result->row();
    }

    function get_bebas_lab_npm($npm)
    {
        $result = $this->db->query("SELECT * FROM `bebas_lab` WHERE npm = $npm order by created_at desc");
		return $result->result();
    }

    function get_bebas_lab($npm)
    {
        $result = $this->db->query("SELECT *, b.status as status_lab, a.status as status_bebas FROM bebas_lab a, bebas_lab_meta b, laboratorium c WHERE a.npm = $npm AND a.id_bebas_lab = b.id_bebas_lab AND b.id_lab = c.id_lab order by b.id_meta");
		return $result->result();
    }

    function get_berkas_lab($npm)
    {
        $result = $this->db->query("SELECT * FROM bebas_lab_berkas a, bebas_lab b WHERE b.npm = $npm AND b.id_bebas_lab = a.id_bebas_lab");
		return $result->result();
    }

    function get_berkas_lab2($npm)
    {
        $result = $this->db->query("SELECT * FROM bebas_lab_berkas WHERE input_by = $npm AND id_bebas_lab IS NULL");
		return $result->result();
    }

    function get_berkas_lab_by_id($id)
    {
        $result = $this->db->query("SELECT * FROM bebas_lab_berkas WHERE id_bebas_lab = $id");
		return $result->result();
    }

    function insert_bebas_lab($data)
    {
        $this->db->insert('bebas_lab', $data);	
        $insert_id = $this->db->insert_id();
		return $insert_id;   
    }

    function insert_lampiran_lab($data)
    {
        $this->db->insert('bebas_lab_berkas', $data);
    }

    function delete_lampiran_lab($data)
	{
		$this->db->delete('bebas_lab_berkas', $data);
    }

    function delete_berkas_lab($data)
	{
		$query = $this->db->query("SELECT file FROM bebas_lab_berkas WHERE id_bebas_lab = $data");
		$results = $query->result();

		foreach ($results as $row)
		{
            unlink($row->file);
		}
	}

    function delete_lab($data)
	{
		$this->db->delete('bebas_lab', $data);
    }

    function delete_meta_lab($data)
	{
		$this->db->delete('bebas_lab_berkas', $data);
    }

    function update_bebas_id_berkas($where,$id)
	{
        $this->db->where('id', $where);
	    $this->db->update('bebas_lab_berkas', array('id_bebas_lab' => $id));
    }
    
    function insert_lab_meta($data)
    {
        $this->db->insert('bebas_lab_meta', $data);
        $insert_id = $this->db->insert_id();
		return $insert_id; 
    }

    function insert_berkas_lab_meta($data)
    {
        $this->db->insert('bebas_lab_meta_berkas', $data);
    }

    function get_berkas_lab_by_id2($id)
    {
        $result = $this->db->query("SELECT * FROM bebas_lab_berkas WHERE id = $id");
		return $result->row();
    }

    function get_berkas_lab_meta_by_id_meta($id)
    {
        $result = $this->db->query("SELECT * FROM bebas_lab_meta_berkas WHERE id_meta = $id");
		return $result->result();
    }

    function update_bebas_meta($where,$status,$ket)
	{
        $this->db->where('id_meta', $where);
	    $this->db->update('bebas_lab_meta', array('status' => $status,"keterangan_tolak"=>$ket));
    }

    function update_bebas_meta_pranata($where,$status,$ket)
	{
        $this->db->where('id_meta', $where);
	    $this->db->update('bebas_lab_meta', array('status' => $status,"keterangan_tolak"=>$ket));
    }

    function get_lab_pranata_user($iduser)
    {
        $result = $this->db->query("SELECT * FROM tbl_users_tugas WHERE id_user = $iduser AND tugas = 16 and aktif = 1");
		return $result->row();
    }

    function get_lab_pranata_form($idlab)
    {
        $result = $this->db->query("SELECT * FROM bebas_lab a, bebas_lab_meta b WHERE b.id_lab = $idlab AND b.status = 0 AND b.id_bebas_lab = a.id_bebas_lab ORDER BY b.updated_at");
		return $result->result();
    }

    function get_lampiran_bebas_lab($idlab)
    {
        $this->db->select('a.*, b.nama');
		$this->db->from('bebas_lab_berkas a');
		$this->db->join('jenis_berkas_lampiran b', 'a.jenis_berkas = b.id_jenis');
		$this->db->join('bebas_lab c', 'a.id_bebas_lab = c.id_bebas_lab');
		$this->db->where(array('c.id_bebas_lab' => $idlab));
		$query = $this->db->get();
		
		return $query->result();
    }

    function get_lab_kalab_user($iduser)
    {
        $result = $this->db->query("SELECT * FROM tbl_users_tugas WHERE id_user = $iduser AND tugas = 15 AND aktif = 1");
		return $result->row();
    }

    function get_lab_kalab_form($idlab)
    {
        $result = $this->db->query("SELECT * FROM bebas_lab a, bebas_lab_meta b WHERE b.id_lab = $idlab AND b.status = 1 AND b.id_bebas_lab = a.id_bebas_lab ORDER BY b.updated_at");
		return $result->result();
    }

    function get_kalab_bebas_lab_meta($idmeta)
    {
        $result = $this->db->query("SELECT * FROM bebas_lab a, bebas_lab_meta b WHERE b.id_meta = $idmeta AND b.id_bebas_lab = a.id_bebas_lab");
		return $result->row();
    }
    
    function get_lab_by_id($idlab)
    {
        $result = $this->db->query("SELECT * FROM `laboratorium` WHERE id_lab = $idlab");
		return $result->row();
    }

    function cek_bebas_lab_status($idbebas)
    {
        $result = $this->db->query("SELECT * FROM `bebas_lab_meta` WHERE id_bebas_lab = $idbebas AND status !=2");
		return $result->result();
    }

    function update_bebas_lab_status($where,$status)
	{
        $this->db->where('id_bebas_lab', $where);
	    $this->db->update('bebas_lab', array('status' => $status));
    }

    function approve_kalab($where,$data)
	{
        $this->db->where('id_meta', $where);
	    $this->db->update('bebas_lab_meta', $data);
    }

    function get_bebas_lab_wd1()
    {
        $result = $this->db->query("SELECT * FROM bebas_lab WHERE status = 1 ORDER BY updated_at");
		return $result->result();
    }

    function get_bebas_lab_by_id($id)
    {
        $result = $this->db->query("SELECT * FROM bebas_lab WHERE id_bebas_lab = $id");
		return $result->row();
    }

    function get_bebas_lab_meta_by_id($id)
    {
        $result = $this->db->query("SELECT * FROM bebas_lab_meta WHERE id_bebas_lab = $id");
		return $result->result();
    }

    function approve_bebas_lab_wd($where,$ttd)
	{
        $this->db->where('id_bebas_lab', $where);
	    $this->db->update('bebas_lab', array('status' => 2,'ttd_dekan'=>$ttd));
    }

}
?>