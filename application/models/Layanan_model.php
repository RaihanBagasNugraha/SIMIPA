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

}
?>