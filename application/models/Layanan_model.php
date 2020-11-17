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

    function get_form_mhs_id($id)
    {
        $result = $this->db->query("SELECT * FROM `layanan_fakultas_mahasiswa` WHERE id = $id");
		return $result->row();
    }

    function get_form_mhs2($npm,$bagian)
    {
        $result = $this->db->query("SELECT layanan_fakultas_mahasiswa.* FROM layanan_fakultas_mahasiswa, layanan_fakultas WHERE layanan_fakultas_mahasiswa.npm = $npm AND layanan_fakultas_mahasiswa.id_layanan_fakultas = layanan_fakultas.id_layanan_fakultas AND layanan_fakultas.bagian LIKE '$bagian' ORDER BY `layanan_fakultas_mahasiswa`.`id` DESC");
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

    function approve_bebas_lab_fakultas($where,$update)
    {
        $this->db->where('id_bebas_lab', $where);
	    $this->db->update('bebas_lab', array('status' => 3,'updated_at'=>$update));
    }

    function input_surat_fakultas($data)
    {
        $this->db->insert('surat_layanan_fakultas', $data);
    }

    function get_surat_fakultas_by_layanan($no)
    {
        $result = $this->db->query("SELECT * FROM `surat_layanan_fakultas` WHERE layanan = $no AND status = 0");
		return $result->result();
    }

    function update_surat_fakultas($where,$data)
	{
        $this->db->where('id', $where);
	    $this->db->update('surat_layanan_fakultas', $data);
    }

    function get_no_surat_fakultas($id_fak_mhs)
    {
        $result = $this->db->query("SELECT * FROM `surat_layanan_fakultas` WHERE id_layanan_fakultas_mahasiswa = $id_fak_mhs")->row();
		return $result;
    }

    function get_layanan_approver()
    {
        $result = $this->db->query("SELECT * FROM layanan_fakultas_approver");
		return $result->result();
    }

    function insert_approver($where,$data)
    {
        $this->db->where('id_layanan_fakultas', $where);
	    $this->db->update('layanan_fakultas',array('approver' => $data));
    }

    function insert_approver_mhs($where,$data)
    {
        $this->db->where('id', $where);
	    $this->db->update('layanan_fakultas_mahasiswa',array('tingkat' => $data));
    }

    function get_layanan_fakultas_by_id($id)
    {
        $result = $this->db->query("SELECT * FROM `layanan_fakultas` WHERE id_layanan_fakultas = $id");
		return $result->row();
    }

    //mahasiswa
    function get_lampiran_layanan($id)
    {
        $result = $this->db->query("SELECT *,a.id as id_brk FROM layanan_fakultas_lampiran a, layanan_fakultas_mahasiswa b WHERE b.id = $id AND b.id = a.id_layanan_fakultas_mahasiswa");
		return $result->result();
    }

    function insert_lampiran_layanan($data)
    {
        $this->db->insert('layanan_fakultas_lampiran', $data);
    }

    function delete_lampiran_layanan($data)
	{
		$this->db->delete('layanan_fakultas_lampiran', $data);
    }

    function get_lampiran_layanan_list($idlay)
    {
        $this->db->select('a.*, b.nama');
		$this->db->from('layanan_fakultas_lampiran a');
		$this->db->join('jenis_berkas_lampiran b', 'a.jenis_berkas = b.id_jenis');
		$this->db->join('layanan_fakultas_mahasiswa c', 'a.id_layanan_fakultas_mahasiswa = c.id');
		$this->db->where(array('c.id' => $idlay));
		$query = $this->db->get();
		
		return $query->result();
    }

    function get_approval_struktural_fakultas($id_user,$kode)
    {
        $result = $this->db->query("SELECT a.* FROM layanan_fakultas_mahasiswa a WHERE a.tingkat LIKE '$kode%' AND a.id IN (SELECT id_layanan_mahasiswa FROM layanan_fakultas_approval WHERE approver_id = $kode AND ttd is null) order by a.updated_at");
        return $result->result();
    }

    //layanan kajur (kode 10)
    function get_approval_kajur_fakultas($id_user)
    {
        $result = $this->db->query("SELECT a.* FROM layanan_fakultas_mahasiswa a, tbl_users_mahasiswa b, tbl_users_dosen c WHERE a.tingkat LIKE '10%' AND c.id_user = $id_user AND c.jurusan = b.jurusan AND a.npm = b.npm AND b.npm NOT LIKE '__3%' AND a.id NOT IN (SELECT id_layanan_mahasiswa FROM layanan_fakultas_approval WHERE approver_id = 10) order by a.updated_at");
		return $result->result();
    }

    //layanan kaprodi s3 (kode 10)
    function get_approval_kaprodi3_fakultas($id_user)
    {
        $result = $this->db->query("SELECT a.* FROM layanan_fakultas_mahasiswa a, tbl_users_mahasiswa b, tbl_users_dosen c WHERE a.tingkat LIKE '10%' AND c.id_user = $id_user AND c.jurusan = b.jurusan AND a.npm = b.npm AND b.npm LIKE '__3%' AND a.id NOT IN (SELECT id_layanan_mahasiswa FROM layanan_fakultas_approval WHERE approver_id = 10) order by a.updated_at");
        return $result->result();
    }

    //layanan sekjur (kode 11)
    function get_approval_sekjur_fakultas($id_user)
    {
        $result = $this->db->query("SELECT a.* FROM layanan_fakultas_mahasiswa a, tbl_users_mahasiswa b, tbl_users_dosen c WHERE a.tingkat LIKE '11%' AND c.id_user = $id_user AND c.jurusan = b.jurusan AND a.npm = b.npm AND a.id NOT IN (SELECT id_layanan_mahasiswa FROM layanan_fakultas_approval WHERE approver_id = 11) order by a.updated_at");
        return $result->result();
    }

     //layanan kaprodi (kode 12)
     function get_approval_kaprodi_fakultas($id_user)
     {
         $result = $this->db->query("SELECT a.* FROM layanan_fakultas_mahasiswa a, tbl_users_mahasiswa b, tbl_users_tugas c WHERE a.tingkat LIKE '12%' AND c.id_user = $id_user AND c.tugas = 14 AND c.prodi = b.prodi AND a.npm = b.npm AND a.id NOT IN (SELECT id_layanan_mahasiswa FROM layanan_fakultas_approval WHERE approver_id = 12) order by a.updated_at");
         return $result->result();
     }

    //layanan ruang baca (kode 13)
    function get_approval_perpustakaan_fakultas($id_user)
    {
        $result = $this->db->query("SELECT a.* FROM layanan_fakultas_mahasiswa a WHERE a.tingkat LIKE '13%' AND a.id NOT IN (SELECT id_layanan_mahasiswa FROM layanan_fakultas_approval WHERE approver_id = 13) order by a.updated_at");
		return $result->result();
    }

     //layanan petugas akademik (kode 14)
     function get_approval_pt_akademik_fakultas($id_user)
     {
         $result = $this->db->query("SELECT a.* FROM layanan_fakultas_mahasiswa a WHERE a.tingkat LIKE '14%' AND a.id NOT IN (SELECT id_layanan_mahasiswa FROM layanan_fakultas_approval WHERE approver_id = 14) order by a.updated_at");
         return $result->result();
     }

    //layanan pa (kode 15)
    function get_approval_pa_fakultas($id_user)
    {
        $result = $this->db->query("SELECT * FROM layanan_fakultas_mahasiswa a, tbl_users_mahasiswa c WHERE a.tingkat LIKE '15%' AND c.dosen_pa = $id_user AND a.npm = c.npm AND a.id NOT IN (SELECT id_layanan_mahasiswa FROM layanan_fakultas_approval WHERE approver_id = 15) order by a.updated_at");
        return $result->result();
    }

     //layanan pb utama (kode 16)
    function get_approval_pbb_fakultas($id_user)
    {
        $result = $this->db->query("SELECT a.* FROM layanan_fakultas_mahasiswa a, tbl_users_mahasiswa c, tugas_akhir b, tbl_users_dosen d WHERE a.tingkat LIKE '16%' AND d.id_user = $id_user AND d.id_user = b.pembimbing1 AND b.npm = c.npm AND c.npm = a.npm AND a.id NOT IN (SELECT id_layanan_mahasiswa FROM layanan_fakultas_approval WHERE approver_id = 16) order by a.updated_at");
        return $result->result();
    }

    //layanan kalab (kode 17)
    function get_approval_kalab_fakultas($id_user)
    {
        $result = $this->db->query("SELECT a.* FROM layanan_fakultas_mahasiswa a, layanan_fakultas_mahasiswa_meta b, layanan_fakultas_atribut c, laboratorium d, tbl_users_tugas e WHERE a.tingkat LIKE '17%' AND a.id_layanan_fakultas = c.id_layanan AND c.nama LIKE '%lab%' AND c.id_atribut = b.meta_key AND a.id = b.id_layanan_fak_mhs AND d.nama_lab LIKE CONCAT('%',b.meta_value,'%') AND e.id_user = $id_user AND e.tugas = 15 AND e.jurusan_unit = d.id_lab");
        return $result->result();
    }

    //layanan tendik berkas masuk
    function get_approval_cek_tendik($bidang)
    {
        $result = $this->db->query("SELECT * FROM layanan_fakultas_mahasiswa a, layanan_fakultas b WHERE (a.tingkat LIKE '1' OR a.tingkat LIKE '2' OR a.tingkat LIKE '3' OR a.tingkat LIKE '4' OR a.tingkat LIKE '5' OR a.tingkat LIKE '6' OR a.tingkat LIKE '7' OR a.tingkat LIKE '8' OR a.tingkat LIKE '9') AND a.status = 0 AND b.id_layanan_fakultas = a.id_layanan_fakultas AND b.bagian LIKE '$bidang' AND a.id NOT IN (SELECT id_layanan_mahasiswa FROM layanan_fakultas_approval WHERE (approver_id LIKE '1' OR approver_id LIKE '2' OR approver_id LIKE '3' OR approver_id LIKE '4' OR approver_id LIKE '5' OR approver_id LIKE '6' OR approver_id LIKE '7' OR approver_id LIKE '8' OR approver_id LIKE '9')) order by a.updated_at");
		return $result->result();
    }

    //layanan tendik berkas keluar
    function get_approval_keluar_tendik($bidang)
    {
        $result = $this->db->query("SELECT * FROM layanan_fakultas_mahasiswa a, layanan_fakultas b WHERE a.status = 1 AND b.bagian = '$bidang' AND a.id_layanan_fakultas = b.id_layanan_fakultas order by a.updated_at");
		return $result->result();   
    }

    function insert_approval_layanan($data)
    {
        $this->db->insert('layanan_fakultas_approval', $data);
    }

    function update_approval_layanan($id_lay,$approver,$data)
	{
        $this->db->where('id_layanan_mahasiswa', $id_lay);
        $this->db->where('approver_id', $approver);
	    $this->db->update('layanan_fakultas_approval', $data);
    }

    function update_tingkat_layanan($where,$tingkat)
	{
        $this->db->where('id', $where);
	    $this->db->update('layanan_fakultas_mahasiswa', array('tingkat' => $tingkat));
    }

    function update_status_layanan($where,$status)
	{
        $this->db->where('id', $where);
	    $this->db->update('layanan_fakultas_mahasiswa', array('status' => $status));
    }

    function update_layanan_fak_mhs($where,$data)
    {
        $this->db->where('id', $where);
	    $this->db->update('layanan_fakultas_mahasiswa', $data);
    }

    function get_id_bebas_lab($id_layanan)
    {
        $result = $this->db->query("SELECT * FROM `bebas_lab` WHERE id_layanan_fakultas_mahasiswa = $id_layanan");
		return $result->row();   
    }
    
    function get_approval_layanan($id_layanan)
    {
        $result = $this->db->query("SELECT * FROM `layanan_fakultas_approval` WHERE id_layanan_mahasiswa = $id_layanan ORDER BY id_approval");
		return $result->result();
    }

    function get_approver_by_id($id_approver)
    {
        $result = $this->db->query("SELECT * FROM `layanan_fakultas_approver` WHERE id_nomor = $id_approver");
		return $result->row();
    }

    function get_approver_by_id_approver($id_layanan, $id_approver)
    {
        $result = $this->db->query("SELECT * FROM `layanan_fakultas_approval` WHERE id_layanan_mahasiswa = $id_layanan AND approver_id = $id_approver");
		return $result->row();
    }

    function delete_berkas_layanan($id)
    {
        $query = $this->db->query("SELECT * FROM `layanan_fakultas_lampiran` WHERE id_layanan_fakultas_mahasiswa = $id");
		$results = $query->result();

		foreach ($results as $row)
		{
            unlink($row->file);
		}
    }

    function get_layanan_lacak($npm)
    {
        $result = $this->db->query("SELECT * FROM layanan_fakultas_mahasiswa a, layanan_fakultas b WHERE a.npm = $npm AND ((a.status = 0 AND (a.tingkat IS NOT null AND a.tingkat != '' )) OR a.status = 1 ) AND a.id_layanan_fakultas = b.id_layanan_fakultas ORDER BY a.created_at asc,b.bagian,a.status");
		return $result->result();
    }

    function insert_layanan_fak_tugas($data)
	{
		$this->db->trans_start();
		$this->db->insert('layanan_fakultas_tugas', $data);
		$this->db->trans_complete();	
    }
    
    function get_layanan_fak_tugas_by_lay($id_layanan)
    {
        $result = $this->db->query("SELECT * FROM `layanan_fakultas_tugas` WHERE id_layanan_fakultas_mahasiswa = $id_layanan");
		return $result->result();
    }

    function insert_prestasi($data)
    {
        $this->db->insert('prestasi', $data);	
        $insert_id = $this->db->insert_id();
		return $insert_id;
    }

    function insert_prestasi_anggota($data)
    {
        $this->db->trans_start();
		$this->db->insert('prestasi_anggota', $data);
		$this->db->trans_complete();
    }

    function get_prestasi_by_npm($npm)
    {
        $result = $this->db->query("(SELECT * FROM prestasi WHERE npm = $npm) UNION (SELECT prestasi.* FROM prestasi, prestasi_anggota WHERE prestasi_anggota.anggota_npm = $npm AND prestasi_anggota.id_prestasi = prestasi.id_prestasi)");
		return $result->result();
    }

    function get_prestasi_anggota_by_id($id_prestasi)
    {
        $result = $this->db->query("SELECT * FROM `prestasi_anggota` WHERE id_prestasi = $id_prestasi");
		return $result->result();
    }

    function delete_prestasi_by_layanan($id_layanan)
	{
        $this->db->delete('prestasi', array('id_layanan' => $id_layanan));
    }

    function update_prestasi($where,$data)
    {
        $this->db->where('id_prestasi', $where);
	    $this->db->update('prestasi', $data);
    }

}
?>