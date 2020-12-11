<?php
class User_model extends CI_Model
{
	var $tbl_user = 'tbl_users';
	var $tbl_mahasiswa = 'tbl_users_mahasiswa';
	var $tbl_dosen = 'tbl_users_dosen';
	var $tbl_tendik = 'tbl_users_tendik';




	function cek_login($username, $password)
	{
		$query = "SELECT userId, password, roleId, name,
		case
		when roleId = '2' then (select nip_nik from tbl_users_dosen where id_user = userId)
		when roleId = '3' then (select npm from tbl_users_mahasiswa where id_user = userId)
		when roleId = '4' then (select nip_nik from tbl_users_tendik where id_user = userId)
		end akun
		FROM `tbl_users`
		where (case
		when roleId = '2' then (select nip_nik from tbl_users_dosen where id_user = userId)
		when roleId = '3' then (select npm from tbl_users_mahasiswa where id_user = userId)
		when roleId = '4' then (select nip_nik from tbl_users_tendik where id_user = userId)
		end) = '$username' and is_active = '1'";

		//$this->db->where(array('email' => $email, 'is_active' => 1));
		$query = $this->db->query($query);

		$user= $query->row();

		if(!empty($user)){
            if(verifyHashedPassword($password, $user->password)){
                return $user;
            } else {
                return array();
            }
        } else {
            return array();
        }
	}

	function cek_email($email)
	{
		$this->db->where('email', $email);
		$query = $this->db->get($this->tbl_user);

		$user= $query->row();

		if(!empty($user)){
            return 1;
        } else {
            return 0;
        }
	}

	function cek_npm($npm)
	{
		$this->db->where('npm', $npm);
		$query = $this->db->get($this->tbl_mahasiswa);

		$user= $query->row();

		if(!empty($user)){
            return 1;
        } else {
            return 0;
        }
	}

	function addNewUser($userInfo, $data_mhs)
    {
        $this->db->trans_start();
        $this->db->insert($this->tbl_user, $userInfo);
        
		$insert_id = $this->db->insert_id();
		
		$data_mhs['id_user'] = $insert_id;
		if($userInfo['roleId'] == 3)
		{
			$this->db->insert($this->tbl_mahasiswa, $data_mhs);
			//$this->db->error(); 
		}
        
        $this->db->trans_complete();
        
        return $insert_id;
	}

	function update($data, $userId)
	{
	    $this->db->where('userId', $userId);
	    $this->db->update($this->tbl_user, $data);
	}

	function update_mahasiswa($data, $userId)
	{
	    $this->db->where('id_user', $userId);
	    $this->db->update($this->tbl_mahasiswa, $data);
	}

	function select_by_ID($ID)
	{
		$this->db->where('userId', $ID);
		$query = $this->db->get($this->tbl_user);
		return $query;
	}

	function select_biodata_by_ID($ID, $idTbl)
	{
		if($idTbl == 2) $tbl = $this->tbl_dosen;
		if($idTbl == 3) $tbl = $this->tbl_mahasiswa;
		if($idTbl == 4) $tbl = $this->tbl_tendik;
		
		$this->db->select($tbl.'.*, '.$this->tbl_user.'.*');
		$this->db->from($tbl);
		$this->db->join($this->tbl_user, $this->tbl_user.'.userId = '.$tbl.'.id_user');
		$this->db->where($this->tbl_user.'.userId', $ID);
		$query = $this->db->get();
		
		return $query;
	}

	function select_list_dosen()
	{
		$this->db->select($this->tbl_dosen.'.*, '.$this->tbl_user.'.*');
		$this->db->from($this->tbl_dosen);
		$this->db->join($this->tbl_user, $this->tbl_user.'.userId = '.$this->tbl_dosen.'.id_user');
		$this->db->order_by('name', 'ASC');
		$query = $this->db->get();
		
		return $query->result();
	}

	function get_dosen_name($id)
	{
		$this->db->select($this->tbl_dosen.'.gelar_depan, '.$this->tbl_dosen.'.gelar_belakang, '.$this->tbl_user.'.name');
		$this->db->from($this->tbl_dosen);
		$this->db->join($this->tbl_user, $this->tbl_user.'.userId = '.$this->tbl_dosen.'.id_user');
		$this->db->where($this->tbl_dosen.".id_user", $id);
		$this->db->order_by('name', 'ASC');
		$query = $this->db->get();
		
		return $query->row();
	}

	function get_tendik_name($id)
	{
		$this->db->select($this->tbl_tendik.'.gelar_depan, '.$this->tbl_tendik.'.gelar_belakang, '.$this->tbl_user.'.name,'.$this->tbl_tendik.'.nip_nik');
		$this->db->from($this->tbl_tendik);
		$this->db->join($this->tbl_user, $this->tbl_user.'.userId = '.$this->tbl_tendik.'.id_user');
		$this->db->where($this->tbl_tendik.".id_user", $id);
		$this->db->order_by('name', 'ASC');
		$query = $this->db->get();
		
		return $query->row();
	}
	
	//raihan
	function get_mahasiswa_name($npm)
	{
		$result = $this->db->query('SELECT tbl_users.name FROM tbl_users, tbl_users_mahasiswa  WHERE tbl_users_mahasiswa.npm ='.$npm.' AND tbl_users_mahasiswa.id_user = tbl_users.userId')->row()->name;
		return $result;
	}

	function get_jurusan($npm)
	{
		$result = $this->db->query('SELECT jurusan.id_jurusan FROM jurusan, tbl_users_mahasiswa WHERE tbl_users_mahasiswa.npm ='.$npm.' AND tbl_users_mahasiswa.jurusan = jurusan.id_jurusan')->row()->id_jurusan;
		return $result;
	}

	function get_jurusan_nama($npm)
	{
		$result = $this->db->query('SELECT jurusan.nama FROM jurusan, tbl_users_mahasiswa WHERE tbl_users_mahasiswa.npm ='.$npm.' AND tbl_users_mahasiswa.jurusan = jurusan.id_jurusan')->row()->nama;
		return $result;
	}

	function get_jurusan_tendik($id_user)
	{
		$result = $this->db->query("SELECT jurusan.id_jurusan FROM jurusan, tbl_users_tendik WHERE tbl_users_tendik.id_user = $id_user AND tbl_users_tendik.unit_kerja = jurusan.id_jurusan")->row()->id_jurusan;
		return $result;
	}

	function get_dosen_by_nip($nip)
	{
		$this->db->select('*');
		$this->db->from('tbl_users_dosen');
		$this->db->join('tbl_users', 'tbl_users_dosen.nip_nik = '.$nip);
		$this->db->where('tbl_users_dosen.id_user', 'tbl_users.userId');
		$query = $this->db->get();
		
		return $query->row();
	}

	function get_dosen_id($nip)
	{
		$result = $this->db->query('SELECT id_user FROM tbl_users_dosen WHERE nip_nik ='.$nip)->row()->id_user;
		return $result;
	}

	function get_dosen_data($id)
	{
		$result = $this->db->query('SELECT * FROM tbl_users_dosen, tbl_users WHERE tbl_users_dosen.id_user ='.$id.' AND tbl_users.userId = tbl_users_dosen.id_user');
		return $result->row();
	}

	function get_mahasiswa_data($id)
	{
		$result = $this->db->query("SELECT * FROM tbl_users_mahasiswa, tbl_users WHERE tbl_users_mahasiswa.id_user = $id AND tbl_users.userId = tbl_users_mahasiswa.id_user");
		return $result->row();
	}

	
	function get_tendik_data($id)
	{
		$result = $this->db->query('SELECT * FROM tbl_users_tendik, tbl_users WHERE tbl_users_tendik.id_user ='.$id.' AND tbl_users.userId = tbl_users_tendik.id_user');
		return $result->row();
	}

	function get_mahasiswa_data_npm($npm)
	{
		$result = $this->db->query("SELECT * FROM tbl_users_mahasiswa a,tbl_users b WHERE a.npm = $npm AND a.id_user = b.userId");
		return $result->row();	
	}

	function get_dosen_pa_by_npm($npm)
	{
		$result = $this->db->query("SELECT b.*,c.* FROM tbl_users_mahasiswa a, tbl_users_dosen b, tbl_users c WHERE a.npm = $npm AND a.dosen_pa = b.id_user AND b.id_user = c.userId");
		return $result->row();
	}

	function get_kajur_by_npm($npm)
    {
        $result = $this->db->query("SELECT c.*,d.* FROM tbl_users_tugas a, tbl_users_mahasiswa b,tbl_users_dosen c, tbl_users d WHERE a.tugas = 12 AND b.npm = $npm AND b.jurusan = a.jurusan_unit AND a.aktif = 1 AND a.id_user = c.id_user AND c.id_user = d.userId");
		return $result->row();
    }

	function get_dekan()
	{
		$result = $this->db->query("SELECT b.*,c.* FROM tbl_users_tugas a,tbl_users_dosen b, tbl_users c WHERE a.tugas = 1 AND a.aktif = 1 AND a.id_user = b.id_user AND b.id_user = c.userId  ORDER BY created_at DESC");
		return $result->row();
	}

	function get_wd_akademik()
	{
		$result = $this->db->query("SELECT b.*,c.* FROM tbl_users_tugas a,tbl_users_dosen b, tbl_users c WHERE a.tugas = 2 AND a.aktif = 1 AND a.id_user = b.id_user AND b.id_user = c.userId  ORDER BY created_at DESC");
		return $result->row();
	}

	function get_kajur($id)
	{
		$result = $this->db->query('SELECT tbl_users_dosen.*, tbl_users.name, jurusan.nama FROM tbl_users_dosen, tbl_users, jurusan, tbl_users_tugas WHERE jurusan.id_jurusan = '.$id.' AND jurusan.id_jurusan = tbl_users_dosen.jurusan AND tbl_users_dosen.id_user = tbl_users.userId AND tbl_users_tugas.id_user = tbl_users_dosen.id_user AND tbl_users_tugas.jurusan_unit = tbl_users_dosen.jurusan AND tbl_users_tugas.tugas = 12');
		return $result->row();
	}

	function get_kalab($idlab)
	{
		$result = $this->db->query("SELECT * FROM laboratorium a, tbl_users_tugas b, tbl_users_dosen c, tbl_users d WHERE a.id_lab = $idlab AND b.tugas = 15 AND b.aktif = 1 AND b.jurusan_unit = a.id_lab AND b.id_user = c.id_user AND c.id_user = d.userId");
		return $result->row();
	}

	function get_dosen_jur($id)
	{
		$result = $this->db->query('SELECT jurusan.* FROM tbl_users_dosen, jurusan WHERE tbl_users_dosen.id_user ='.$id.' AND tbl_users_dosen.jurusan = jurusan.id_jurusan');
		return $result->row();
	}

	function get_dosen_prodi($id)
	{
		$result = $this->db->query("SELECT prodi.* FROM prodi, tbl_users_dosen WHERE tbl_users_dosen.id_user = $id AND tbl_users_dosen.jurusan = prodi.jurusan");
		return $result->result();
	}

	function get_jabfung_all()
	{
	    $this->db->select('*');
		$query = $this->db->get('fungsional')->result();
		return $query;
	}
	
	function get_pangkat_all()
	{
	    $this->db->select('*');
		$query = $this->db->get('pangkat_gol')->result();
		return $query;
	}

	function get_tugas_tambahan_all()
	{
	    $this->db->select('*');
		$query = $this->db->get('tugas_tambahan')->result();
		return $query;
	}

	function get_prodi_all()
	{
	    $this->db->select('*');
		$query = $this->db->get('prodi')->result();
		return $query;
	}

	function get_jurusan_all()
	{
	    $this->db->select('*');
		$query = $this->db->get('jurusan')->result();
		return $query;
	}

	function get_lab_all()
	{
		$this->db->select('*');
		$query = $this->db->get('laboratorium')->result();
		return $query;
	}

	function insert_tugas_tambah($data)
	{
		$this->db->insert('tbl_users_tugas', $data);
	}

	function get_tugas_tambahan_id($id)
	{
		$result = $this->db->query("SELECT * FROM `tbl_users_tugas` WHERE id_user = $id ORDER BY aktif DESC");
		return $result->result();
	}

	function get_tugas_tambahan_detail($id)
	{
		$result = $this->db->query("SELECT * FROM `tugas_tambahan` WHERE id_tugas_tambahan = $id");
		return $result->row();
	}

	function get_jurusan_id($id)
	{
		$result = $this->db->query("SELECT * FROM `jurusan` WHERE id_jurusan = $id");
		return $result->row();
	}

	function get_prodi_id($id)
	{
		$result = $this->db->query("SELECT * FROM `prodi` WHERE id_prodi = $id");
		return $result->row();
	}

	function update_tugas_tambahan($id,$ket)
	{
		if($ket == 0){
			$this->db->where('id', $id);
			$this->db->update('tbl_users_tugas', array('aktif' => '0'));
		}
		else{
			$this->db->delete('tbl_users_tugas', array('id' => $id));
		}
	
	}

	function update_dosen($data, $userId)
	{
	    $this->db->where('id_user', $userId);
	    $this->db->update($this->tbl_dosen, $data);
	}

	function check_tugas_tambahan($userId,$idtugas,$jurusan,$prodi,$status)
	{
		$result = $this->db->query("SELECT * FROM `tbl_users_tugas` WHERE id_user = $userId AND tugas = $idtugas AND (jurusan_unit = $jurusan AND prodi = $prodi) AND aktif = $status");
		return $result->row();
	}

	function check_tugas_tambahan_duplikat($id_tugas,$jurusan,$prodi,$aktif,$periode)
	{
		$result = $this->db->query("SELECT * FROM `tbl_users_tugas` WHERE tugas = $id_tugas AND periode = '$periode' and (jurusan_unit = $jurusan AND prodi = $prodi) AND aktif = $aktif");
		return $result->row();
	}

	function get_lab_by_id($id)
	{
		$result = $this->db->query("SELECT * FROM laboratorium WHERE id_lab = $id");
		return $result->row();
	}

	//tugas tambahan dosen
	function tugas_dosen_kajur($userId)
	{
		$result = $this->db->query("SELECT * FROM `tbl_users_tugas` WHERE (tugas = 12) AND aktif = 1 AND id_user = $userId");
		return $result->row();
	}
	
	function tugas_dosen_kaprodi($userId)
	{
		$result = $this->db->query("SELECT * FROM `tbl_users_tugas` WHERE tugas = 14 AND aktif = 1 AND id_user = $userId");
		return $result->row();
	}

	function tugas_dosen_dekan($userId)
	{
		$result = $this->db->query("SELECT * FROM `tbl_users_tugas` WHERE tugas = 1 AND aktif = 1 AND id_user = $userId");
		return $result->row();
	}

	function tugas_dosen_wakil_dekan($userId)
	{
		$result = $this->db->query("SELECT * FROM `tbl_users_tugas` WHERE (tugas = 2 OR tugas = 3 OR tugas = 4) AND aktif = 1 AND id_user = $userId");
		return $result->row();
	}

	function tugas_dosen_koor($userId)
	{
		$result = $this->db->query("SELECT * FROM `tbl_users_tugas` WHERE tugas = 17  AND aktif = 1 AND id_user = $userId");
		return $result->row();
	}

	function tugas_dosen_koor_kp($userId)
	{
		$result = $this->db->query("SELECT * FROM `tbl_users_tugas` WHERE tugas = 19  AND aktif = 1 AND id_user = $userId");
		return $result->row();
	}

	function tugas_tendik_admin_jurusan($userId)
	{
		$result = $this->db->query("SELECT * FROM `tbl_users_tugas` WHERE tugas = 18  AND aktif = 1 AND id_user = $userId");
		return $result->row();
	}

	function tugas_tendik_admin_fakultas($userId)
	{
		$result = $this->db->query("SELECT * FROM `tbl_users_tugas` WHERE tugas = 11  AND aktif = 1 AND id_user = $userId");
		return $result->row();
	}

	function tugas_tendik_laboratorium_pranata($userId)
	{
		$result = $this->db->query("SELECT * FROM `tbl_users_tugas` WHERE tugas = 16 AND aktif = 1 AND id_user = $userId");
		return $result->row();
	}

	function tugas_tendik_laboratorium($userId)
	{
		$result = $this->db->query("SELECT * FROM `tbl_users_tugas` WHERE tugas = 15 AND aktif = 1 AND id_user = $userId");
		return $result->row();
	}

	function tugas_tendik_kabag_tu($userId)
	{
		$result = $this->db->query("SELECT * FROM `tbl_users_tugas` WHERE tugas = 5 AND aktif = 1 AND id_user = $userId");
		return $result->row();
	}

	function tugas_tambahan_get($userId)
	{
		$result = $this->db->query("SELECT tugas FROM `tbl_users_tugas` WHERE id_user = $userId");
		return $result->result();
	}

	//tendik
	function get_unit_kerja_tendik()
	{
		$this->db->select('*');
		$query = $this->db->get('unit_kerja')->result();
		return $query;
	}

	function update_tendik($data, $userId)
	{
	    $this->db->where('id_user', $userId);
	    $this->db->update($this->tbl_tendik, $data);
	}


	//gelar
	function get_gelar_dosen_id($id)
	{	
		$result = $this->db->query("SELECT gelar_depan, gelar_belakang FROM `tbl_users_dosen` WHERE id_user = $id");
		return $result->row();
	}

	function get_gelar_tendik_id($id)
	{	
		$result = $this->db->query("SELECT gelar_depan, gelar_belakang FROM `tbl_users_tendik` WHERE id_user = $id");
		return $result->row();
	}

	function get_gelar_dosen_nip($nip)
	{	
		$result = $this->db->query("SELECT gelar_depan, gelar_belakang FROM `tbl_users_dosen` WHERE nip_nik = $nip");
		return $result->row();
	}

	function get_gelar_tendik_nip($nip)
	{	
		$result = $this->db->query("SELECT gelar_depan, gelar_belakang FROM `tbl_users_tendik` WHERE nip_nik = $nip");
		return $result->row();
	}

	//lembaga kemahasiswaaan
	function get_lk_all()
	{
	    $this->db->select('*');
		$query = $this->db->get('lembaga_kemahasiswaan')->result();
		return $query;
	}

	function insert_lk_mhs($data)
	{
		$this->db->insert('tbl_users_tugas_mahasiswa', $data);
	}

	function get_lk_id($id_user)
	{
		$result = $this->db->query("SELECT * FROM `tbl_users_tugas_mahasiswa` WHERE id_user = $id_user ORDER BY aktif DESC");
		return $result->result();
	}

	function get_lk_detail($id)
	{
		$result = $this->db->query("SELECT * FROM `lembaga_kemahasiswaan` WHERE id_lk = $id");
		return $result->row();
	}

	function update_tugas_lk($id,$ket)
	{
		if($ket == 'nonaktif'){
			$this->db->where('id', $id);
			$this->db->update('tbl_users_tugas_mahasiswa', array('aktif' => '0'));
		}
		else{
			$this->db->delete('tbl_users_tugas_mahasiswa', array('id' => $id));
		}
	}

	function check_lk($periode,$id_lk,$jabatan)
	{
		$result = $this->db->query("SELECT * FROM `tbl_users_tugas_mahasiswa` WHERE periode = '$periode' AND id_lk = $id_lk AND jabatan = $jabatan");
		return $result->row();
	}

	function check_lk_mahasiswa($id_user)
	{
		$result = $this->db->query("SELECT * FROM tbl_users_tugas_mahasiswa WHERE id_user = $id_user and aktif = 1");
		return $result->row();
	}

	function get_pa_by_npm($npm)
	{
		$result = $this->db->query("SELECT b.*, c.* FROM tbl_users_mahasiswa a, tbl_users_dosen b, tbl_users c WHERE a.npm = $npm AND a.dosen_pa = b.id_user AND b.id_user = c.userId ");
		return $result->row();
	}

	function get_agama_by_id($id)
	{
		$result = $this->db->query("SELECT * FROM `agama` WHERE id = $id");
		return $result->row();
	}

	function get_pangkat_gol_by_id($id)
	{
		$result = $this->db->query("SELECT * FROM `pangkat_gol` WHERE id_pangkat_gol = $id");
		return $result->row();
	}

	function get_jabfung_gol_by_id($id)
	{
		$result = $this->db->query("SELECT * FROM `fungsional` WHERE id_fungsional = $id");
		return $result->row();
	}

	function get_role_id($id_user)
	{
		$result = $this->db->query("SELECT roleId FROM `tbl_users` WHERE userId = $id_user");
		return $result->row()->roleId;
	}

	/* ------------------------------------
	function select_all()
	{
	    $role = array("Operator", "Pimpinan");
	    $this->db->where_in('role', $role);
		$this->db->order_by('nama_lengkap', 'ASC');
		$query = $this->db->get('akun');
		return $query;
	}
	
	function select_by_ID($ID)
	{
		$this->db->where('username', $ID);
		$query = $this->db->get('akun');
		return $query;
	}
	
	function select_by_username_password($username, $password)
	{
		$this->db->where('username', $username);
		$this->db->where('password', md5($password));
		$query = $this->db->get('akun');
		return $query;
	}


	function insert($data)
	{
		$this->db->insert('akun', $data);
	}
	
	function replace($data)
	{
	    $this->db->replace('akun', $data);
	}
	
	function update($data, $where)
	{
	    $this->db->where('username', $where);
	    $this->db->update('akun', $data);
	}
	
	function delete($data)
	{
		$this->db->delete('akun', $data);
	}

	*/
}
