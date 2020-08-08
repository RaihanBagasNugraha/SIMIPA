<?php
class Ta_model extends CI_Model
{
	private $table = "tugas_akhir";
	private $table_seminar = "seminar_sidang"; //add raihan

	function select_ta_by_id($id, $npm)
	{
		$this->db->where(array('id_pengajuan' => $id, 'npm' => $npm));
		// $this->db->where(array('id_pengajuan' => $id, 'npm' => $npm, 'status' => -1));
		$this->db->where("(status='-1' OR status='5')");
		$query = $this->db->get($this->table);
		return $query->result_array();
	}

	function selet_ta_by_npm($npm)
	{
		$this->db->where(array('npm' => $npm));
		$query = $this->db->get($this->table);
		return $query->result();
	}

	function select_lampiran_by_ta($id, $username)
	{
		$this->db->select('a.*, b.nama');
		$this->db->from('tugas_akhir_berkas a');
		$this->db->join('jenis_berkas_lampiran b', 'a.jenis_berkas = b.id_jenis');
		$this->db->join('tugas_akhir c', 'a.id_pengajuan = c.id_pengajuan');
		$this->db->where(array('a.id_pengajuan' => $id, 'c.npm' => $username));
		$query = $this->db->get();
		
		return $query->result();
	}

	function select_active_ta($npm)
	{
		$this->db->where('npm =', $npm);
		$this->db->where('status !=', '6');
		$query = $this->db->get($this->table);
		return $query->result();
	}

	function insert($data, $data2)
	{
		$this->db->trans_start();
		$this->db->insert($this->table, $data);
		$insert_id = $this->db->insert_id();
		
		$data2['id_pengajuan'] = $insert_id;
		$this->db->insert('tugas_akhir_approval', $data2);

		$this->db->trans_complete();
		
		return $insert_id;
	}

	function insert_lampiran($data)
	{
		$this->db->trans_start();
		$this->db->insert('tugas_akhir_berkas', $data);
		$this->db->trans_complete();
		
	}
	//add raihan
	function get_ta_by_id($id)
	{
		
		$this->db->where(array('id_pengajuan' => $id));
		$query = $this->db->get($this->table);
		return $query->Row();
	}

	function update($data, $where)
	{
		$this->db->where('id_pengajuan', $where);
	    $this->db->update($this->table, $data);
	}
	// raihan

	function delete_lampiran($data)
	{
		$this->db->delete('tugas_akhir_berkas', $data);
	}

	function delete_lampiran_seminar($data)
	{
		$this->db->delete('seminar_sidang_berkas', $data);
	}
	
	function delete_ta($data)
	{
		$this->db->delete('tugas_akhir', $data);
	}

	function delete_approval_ta($data)
	{
		$this->db->delete('tugas_akhir_approval', $data);
	}
	
	function delete_berkas_ta($data)
	{
		$query = $this->db->query('SELECT file FROM tugas_akhir_berkas WHERE id_pengajuan ='.$data);
		$results = $query->result();

		foreach ($results as $row)
		{
			unlink($row->file);
		}
	}	

	function ajukan_ta($where)
	{
		$this->db->where('id_pengajuan', $where);
	    $this->db->update($this->table, array('status' => '0'));
	}

	function ajukan_ta_perbaikan($where,$status)
	{
		if($status == "pb1"){
			$this->db->where('id_pengajuan', $where);
	    	$this->db->update($this->table, array('status' => '1'));
		}
		elseif($status == "pa"){
			$this->db->where('id_pengajuan', $where);
	    	$this->db->update($this->table, array('status' => '0'));
		}
		elseif($status == "admin"){
			$this->db->where('id_pengajuan', $where);
	    	$this->db->update($this->table, array('status' => '2'));
		}
		
	}
	
	function get_approval_ta($id)
	{
		$this->db->where(array('pembimbing1' => $id));
		$this->db->where('status =', '1');
		$query = $this->db->get($this->table);
		return $query->result();
	}

	function get_approval_ta_kajur($id)
	{
		$query = $this->db->query('SELECT * FROM tugas_akhir JOIN tbl_users_mahasiswa, tbl_users_dosen WHERE tbl_users_dosen.id_user ='.$id.' AND tbl_users_mahasiswa.jurusan = tbl_users_dosen.jurusan AND tugas_akhir.status = 7 AND tugas_akhir.npm = tbl_users_mahasiswa.npm');
	
		return $query->result();
	}

	function get_approval_ta_kaprodi($id)
	{
		$query = $this->db->query("SELECT * FROM tugas_akhir JOIN tbl_users_mahasiswa, tbl_users_dosen WHERE tbl_users_dosen.id_user =$id AND tbl_users_mahasiswa.jurusan = tbl_users_dosen.jurusan AND tugas_akhir.status = 3 AND tugas_akhir.npm = tbl_users_mahasiswa.npm AND tugas_akhir.jenis != 'Skripsi' ");
	
		return $query->result();
	}

	function get_approval_seminar_kajur($id)
	{
		$query = $this->db->query('SELECT seminar_sidang.*, tugas_akhir.npm, tugas_akhir.judul1, tugas_akhir.judul2, tugas_akhir.judul_approve FROM tugas_akhir, seminar_sidang, tbl_users_mahasiswa, tbl_users_dosen WHERE tbl_users_dosen.id_user ='.$id.' AND tbl_users_mahasiswa.jurusan = tbl_users_dosen.jurusan AND seminar_sidang.status = 7 AND tugas_akhir.npm = tbl_users_mahasiswa.npm AND tugas_akhir.id_pengajuan = seminar_sidang.id_tugas_akhir');
	
		return $query->result();
	}

	function get_approval_ta_by_pa($id)
	{
		$this->db->select('*'); 
		$this->db->from('tugas_akhir');
		$this->db->join('tbl_users_mahasiswa', 'tbl_users_mahasiswa.npm = tugas_akhir.npm');
		$this->db->where('status =', '0');
		$this->db->where('tbl_users_mahasiswa.dosen_pa',$id);

		$query = $this->db->get();
		return $query->result();
	}

	function get_approval_ta_koordinator($id)
	{
		$query = $this->db->query("SELECT * FROM tugas_akhir JOIN tbl_users_mahasiswa, tbl_users_dosen WHERE tbl_users_dosen.id_user =$id AND tbl_users_mahasiswa.jurusan = tbl_users_dosen.jurusan AND tugas_akhir.status = 3 AND tugas_akhir.npm = tbl_users_mahasiswa.npm AND tugas_akhir.jenis = 'Skripsi' ");
	
		return $query->result();
	}

	function approve_ta($where,$ttd,$status,$dosenid)
	{
		$result = $this->db->query('SELECT pembimbing1 FROM tugas_akhir WHERE id_pengajuan ='.$where)->row()->pembimbing1;
		$nip = $this->db->query('SELECT nip_nik FROM tbl_users_dosen WHERE id_user ='.$dosenid)->row()->nip_nik;
		$nama = $this->db->query('SELECT name FROM tbl_users WHERE userId ='.$dosenid)->row()->name;


		$result2 = $this->db->query('SELECT * FROM tugas_akhir_approval WHERE id_pengajuan ='.$where.' AND ttd LIKE ""');
		$checks = $result2->result();
		$check = count($checks);

		if($status == 'pb1'){
			$this->db->where('id_pengajuan', $where);
			$this->db->update($this->table, array('status' => '2'));

			$data_approval = [
				'id_pengajuan' => $where,
				'status_slug'  => 'Pembimbing Utama',
				'id_user'  => $dosenid,
				'ttd'  => $ttd
			];

			$this->db->insert('tugas_akhir_approval', $data_approval);

			$data_komisi = [
				'id_tugas_akhir' => $where,
				'status'  => 'Pembimbing Utama',
				'nip_nik'  => $nip,
				'id_user'  => $dosenid,
				'nama'  => $nama,
			];

			$this->db->insert('tugas_akhir_komisi', $data_komisi);

			$data_surat = [
				'jenis' => '1',
				'id_jenis'  => $where,
			];

			$this->db->insert('staff_surat', $data_surat);

		}
		elseif($status == 'pa'){
			$this->db->where('id_pengajuan', $where);
			$this->db->update($this->table, array('status' => '1'));

			$data_approval = [
				'id_pengajuan' => $where,
				'status_slug'  => 'Pembimbing Akademik',
				'id_user'  => $dosenid,
				'ttd'  => $ttd
			];

			$this->db->insert('tugas_akhir_approval', $data_approval);
		}
		elseif($status == 'kajur'){
			$this->db->where('id_pengajuan', $where);
			$this->db->update($this->table, array('status' => '8'));

			$data_approval = [
				'id_pengajuan' => $where,
				'status_slug'  => 'Ketua Jurusan',
				'id_user'  => $dosenid,
				'ttd'  => $ttd
			];

			$this->db->insert('tugas_akhir_approval', $data_approval);
		}

		elseif($status == 'kajur_acc'){
			$this->db->where('id_pengajuan', $where);
			$this->db->update($this->table, array('status' => '4'));

			$data_approval = [
				'id_pengajuan' => $where,
				'status_slug'  => 'Ketua Jurusan',
				'id_user'  => $dosenid,
				'ttd'  => $ttd
			];

			$this->db->insert('tugas_akhir_approval', $data_approval);
		}

		elseif($status == 'pb2'){

			$this->db->where('id_pengajuan', $where);
			$this->db->where('status_slug', 'Pembimbing 2');
			$this->db->where('id_user', $dosenid);
			$this->db->update('tugas_akhir_approval', array('ttd' => $ttd));

			if($check == 1){
				$this->db->where('id_pengajuan', $where);
				$this->db->update('tugas_akhir', array('status' => '4'));
			}
		}

		elseif($status == 'pb3'){

			$this->db->where('id_pengajuan', $where);
			$this->db->where('status_slug', 'Pembimbing 3');
			$this->db->where('id_user', $dosenid);
			$this->db->update('tugas_akhir_approval', array('ttd' => $ttd));

			if($check == 1){
				$this->db->where('id_pengajuan', $where);
				$this->db->update('tugas_akhir', array('status' => '4'));
			}
		}

		elseif($status == 'ps1'){

			$this->db->where('id_pengajuan', $where);
			$this->db->where('status_slug', 'Penguji 1');
			$this->db->where('id_user', $dosenid);
			$this->db->update('tugas_akhir_approval', array('ttd' => $ttd));

			if($check == 1){
				$this->db->where('id_pengajuan', $where);
				$this->db->update('tugas_akhir', array('status' => '4'));
			}
		}

		elseif($status == 'ps2'){

			$this->db->where('id_pengajuan', $where);
			$this->db->where('status_slug', 'Penguji 2');
			$this->db->where('id_user', $dosenid);
			$this->db->update('tugas_akhir_approval', array('ttd' => $ttd));

			if($check == 1){
				$this->db->where('id_pengajuan', $where);
				$this->db->update('tugas_akhir', array('status' => '4'));
			}
		}

		elseif($status == 'ps3'){

			$this->db->where('id_pengajuan', $where);
			$this->db->where('status_slug', 'Penguji 3');
			$this->db->where('id_user', $dosenid);
			$this->db->update('tugas_akhir_approval', array('ttd' => $ttd));

			if($check == 1){
				$this->db->where('id_pengajuan', $where);
				$this->db->update('tugas_akhir', array('status' => '4'));
			}
		}
	}

	function approve_ta_alter($where,$ttd,$status,$token)
	{
		$result = $this->db->query('SELECT * FROM tugas_akhir_approval WHERE id_pengajuan ='.$where.' AND ttd LIKE ""');
		$checks = $result->result();
		$check = count($checks);

		$this->db->where('id_pengajuan', $where);
		$this->db->where('status_slug', $status);
		$this->db->update('tugas_akhir_approval', array('ttd' => $ttd));

		if($check == 1){
			$this->db->where('id_pengajuan', $where);
			$this->db->update('tugas_akhir', array('status' => '4'));
		}

		if($token != NULL){
			$this->db->where('id_tugas_akhir', $where);
			$this->db->where('status', $status);
			$this->db->where('token', $token);
			$this->db->update('tugas_akhir_komisi_alternatif', array('ket' => '1'));
		}
	}

	function decline_ta($where,$dosenid,$status,$keterangan)
	{
		if($status == 'pb1'){
			$this->db->where('id_pengajuan', $where);
			$this->db->update($this->table, array('status' => '5','keterangan_tolak' => $keterangan));

		}
		elseif($status == 'pa'){
			$this->db->where('id_pengajuan', $where);
			$this->db->update($this->table, array('status' => '5','keterangan_tolak' => $keterangan));

		}
		elseif($status == 'koor'){
			$this->db->where('id_pengajuan', $where);
			$this->db->update($this->table, array('status' => '6','no_penetapan' => NULL,'keterangan_tolak' => $keterangan));

		}
		
	}

	function get_dosen_pa($npm){
		$dosen_pa = $this->db->query('SELECT dosen_pa FROM tbl_users_mahasiswa WHERE npm ='.$npm)->row()->dosen_pa;

		return $dosen_pa;
	}
	
	
	
	function get_verifikasi_berkas($id) //tendik
	{
		// $this->db->select('*'); 
		// $this->db->from('tugas_akhir');
		// $this->db->join('tbl_users_tendik', 'tbl_users_tendik.id_user =', $id);
		// $this->db->join('tbl_users_mahasiswa', 'tugas_akhir.npm = tbl_users_mahasiswa.npm');
		// $this->db->where('tbl_users_tendik.unit_kerja = tbl_users_mahasiswa.jurusan');
		// $this->db->where('status =', '2');

		$query = $this->db->query('SELECT * FROM tugas_akhir JOIN tbl_users_tendik, tbl_users_mahasiswa WHERE tbl_users_tendik.id_user ='.$id.' AND tbl_users_tendik.unit_kerja = tbl_users_mahasiswa.jurusan AND tugas_akhir.npm = tbl_users_mahasiswa.npm AND tugas_akhir.status = 2');
		
		return $query->result();

	}

	function approve_berkas_ta($where,$dosenid,$ttd,$no_penetapan,$update) //tendik
	{
		$nip = $this->db->query('SELECT nip_nik FROM tbl_users_tendik WHERE id_user ='.$dosenid)->row()->nip_nik;
	
		$this->db->where('id_pengajuan', $where);
		$this->db->update($this->table, array('status' => '3','no_penetapan' => $no_penetapan,'keterangan_tolak' => NULL));

		$data_approval = [
			'id_pengajuan' => $where,
			'status_slug'  => 'Administrasi',
			'id_user'  => $dosenid,
			'ttd'  => $ttd
		];

		$this->db->insert('tugas_akhir_approval', $data_approval);
	}

	function staff_surat($id, $no_penetapan)
	{
		$this->db->where('jenis', '1');
		$this->db->where('id_jenis', $id);
		$this->db->update('staff_surat', array('nomor' => $no_penetapan));
	}

	function staff_surat_seminar($id, $no_form)
	{
		$this->db->where('jenis', '2');
		$this->db->where('id_jenis', $id);
		$this->db->update('staff_surat', array('nomor' => $no_form));
	}

	function decline_berkas_ta($where,$dosenid,$keterangan) //tendik
	{
		$ttd = $this->db->query('SELECT ttd FROM tbl_users WHERE userId ='.$dosenid)->row()->ttd;
		$nip = $this->db->query('SELECT nip_nik FROM tbl_users_tendik WHERE id_user ='.$dosenid)->row()->nip_nik;
	
		$this->db->where('id_pengajuan', $where);
		$this->db->update($this->table, array('status' => '5','keterangan_tolak' => $keterangan));
	}

	function approval_koordinator($id,$ttd,$dosenid,$no_penetapan,$judul_approve,$judul1,$judul2)//koor
	{
		$this->db->where('id_pengajuan', $id);
		$this->db->update($this->table, array('judul1' => $judul1,'judul2' => $judul2,'status' => '7','judul_approve' => $judul_approve,'no_penetapan' => $no_penetapan,'keterangan_tolak' => NULL));

		$data_approval = [
			'id_pengajuan' => $id,
			'status_slug'  => 'Koordinator',
			'id_user'  => $dosenid,
			'ttd'  => $ttd
		];

		$this->db->insert('tugas_akhir_approval', $data_approval);

	}

	function approval_koordinator_ta($id,$ttd,$dosenid,$no_penetapan,$judul_approve,$judul1,$judul2)//koor
	{
		$this->db->where('id_pengajuan', $id);
		$this->db->update($this->table, array('judul1' => $judul1,'judul2' => $judul2,'status' => '7','judul_approve' => $judul_approve,'no_penetapan' => $no_penetapan,'keterangan_tolak' => NULL));

		$data_approval = [
			'id_pengajuan' => $id,
			'status_slug'  => 'Koordinator',
			'id_user'  => $dosenid,
			'ttd'  => $ttd
		];

		$this->db->insert('tugas_akhir_approval', $data_approval);

	}


	function set_komisi($id,$pb1,$pb2,$pb3,$ps1,$ps2,$ps3) //koor
	{
		//pb1
		$nip_pb1 = $this->db->query('SELECT nip_nik FROM tbl_users_dosen WHERE id_user ='.$pb1)->row()->nip_nik;
		$nama1 = $this->db->query('SELECT name FROM tbl_users WHERE userId ='.$pb1)->row()->name;
		$this->db->where('id_tugas_akhir', $id);
		$this->db->where('status', 'Pembimbing 1');
		$this->db->update('tugas_akhir_komisi', array('nip_nik' => $nip_pb1,'id_user' => $pb1,'nama' => $nama1));

		//pb2
		if($pb2 != NULL){
			$nip_pb2 = $this->db->query('SELECT nip_nik FROM tbl_users_dosen WHERE id_user ='.$pb2)->row()->nip_nik;
			$nama = $this->db->query('SELECT name FROM tbl_users WHERE userId ='.$pb2)->row()->name;
			$data_komisi = [
				'id_tugas_akhir' => $id,
				'status'  => 'Pembimbing 2',
				'nip_nik'  => $nip_pb2,
				'id_user'  => $pb2,
				'nama' => $nama
			];

			$this->db->insert('tugas_akhir_komisi', $data_komisi);
		}

		//pb3
		if($pb3 != NULL){
			$nip_pb3 = $this->db->query('SELECT nip_nik FROM tbl_users_dosen WHERE id_user ='.$pb3)->row()->nip_nik;
			$nama = $this->db->query('SELECT name FROM tbl_users WHERE userId ='.$pb3)->row()->name;
			$data_komisi = [
				'id_tugas_akhir' => $id,
				'status'  => 'Pembimbing 3',
				'nip_nik'  => $nip_pb3,
				'id_user'  => $pb3,
				'nama' => $nama
			];

			$this->db->insert('tugas_akhir_komisi', $data_komisi);
		}

		//ps1
		if($ps1 != NULL){
			$nip_ps1 = $this->db->query('SELECT nip_nik FROM tbl_users_dosen WHERE id_user ='.$ps1)->row()->nip_nik;
			$nama = $this->db->query('SELECT name FROM tbl_users WHERE userId ='.$ps1)->row()->name;	
			$data_komisi = [
				'id_tugas_akhir' => $id,
				'status'  => 'Penguji 1',
				'nip_nik'  => $nip_ps1,
				'id_user'  => $ps1,
				'nama' => $nama
			];
		
			$this->db->insert('tugas_akhir_komisi', $data_komisi);
		}

		//ps2
		if($ps2 != NULL){
			$nip_ps2 = $this->db->query('SELECT nip_nik FROM tbl_users_dosen WHERE id_user ='.$ps2)->row()->nip_nik;
			$nama = $this->db->query('SELECT name FROM tbl_users WHERE userId ='.$ps2)->row()->name;	
			$data_komisi = [
				'id_tugas_akhir' => $id,
				'status'  => 'Penguji 2',
				'nip_nik'  => $nip_ps2,
				'id_user'  => $ps2,
				'nama' => $nama
			];
		
			$this->db->insert('tugas_akhir_komisi', $data_komisi);
		}

		//ps3
		if($ps3 != NULL){
			$nip_ps3 = $this->db->query('SELECT nip_nik FROM tbl_users_dosen WHERE id_user ='.$ps3)->row()->nip_nik;
			$nama = $this->db->query('SELECT name FROM tbl_users WHERE userId ='.$ps3)->row()->name;			
			$data_komisi = [
				'id_tugas_akhir' => $id,
				'status'  => 'Penguji 3',
				'nip_nik'  => $nip_ps3,
				'id_user'  => $ps3,
				'nama' => $nama
			];
				
			$this->db->insert('tugas_akhir_komisi', $data_komisi);
		}
	}

	function set_komisi_ta($id,$pb1,$ps1,$ps2) //koor
	{
		//pb1
		$nip_pb1 = $this->db->query('SELECT nip_nik FROM tbl_users_dosen WHERE id_user ='.$pb1)->row()->nip_nik;
		$nama1 = $this->db->query('SELECT name FROM tbl_users WHERE userId ='.$pb1)->row()->name;
		$this->db->where('id_tugas_akhir', $id);
		$this->db->where('status', 'Pembimbing 1');
		$this->db->update('tugas_akhir_komisi', array('nip_nik' => $nip_pb1,'id_user' => $pb1,'nama' => $nama1));

		//ps1
		if($ps1 != NULL){
			$nip_ps1 = $this->db->query('SELECT nip_nik FROM tbl_users_dosen WHERE id_user ='.$ps1)->row()->nip_nik;
			$nama = $this->db->query('SELECT name FROM tbl_users WHERE userId ='.$ps1)->row()->name;	
			$data_komisi = [
				'id_tugas_akhir' => $id,
				'status'  => 'Penguji 1',
				'nip_nik'  => $nip_ps1,
				'id_user'  => $ps1,
				'nama' => $nama
			];
		
			$this->db->insert('tugas_akhir_komisi', $data_komisi);
		}

		//ps2
		if($ps2 != NULL){
			$nip_ps2 = $this->db->query('SELECT nip_nik FROM tbl_users_dosen WHERE id_user ='.$ps2)->row()->nip_nik;
			$nama = $this->db->query('SELECT name FROM tbl_users WHERE userId ='.$ps2)->row()->name;	

			$data_verif = [
				'id_ta' => $id,
				'id_dosen'  => $ps2,
				'nilai'  => "",
				'ket'  => "0",
				'nip_nik'  => $nip_ps2,
				'nama' => $nama,
				'ttd'  => ""
			];
		
			$this->db->insert('verifikasi_ta_nilai', $data_verif);
		}

	}

	function set_komisi_alter($id,$nip,$nama,$status)
	{
		$data_komisi = [
			'id_tugas_akhir' => $id,
			'status'  => $status,
			'nip_nik'  => $nip,
			'id_user'  => '',
			'nama' => $nama
		];
			
		$this->db->insert('tugas_akhir_komisi', $data_komisi);
	}

	function set_komisi_alter_access($id,$nip,$nama,$status,$email)
	{
		$keys = "raihanbagasnugraha";
		$date = date("Y-m-d H:i:s");
		$token = md5($keys.$nip.$status.$nama.$date);
		$data_komisi = [
			'id_tugas_akhir' => $id,
			'status'  => $status,
			'nip_nik'  => $nip,
			'nama' => $nama,
			'email' => $email,
			'token' => $token,
			'ket' => '0',
		];
			
		$this->db->insert('tugas_akhir_komisi_alternatif', $data_komisi);
	}

	// get komisi
	function get_pembimbing_ta($id)
	{
		$query = $this->db->query('SELECT * FROM tugas_akhir_komisi WHERE status LIKE "Pembimbing%" AND id_tugas_akhir ='.$id);
		return $query->result();
	} 

	function get_penguji_ta($id)
	{
		$query = $this->db->query('SELECT * FROM tugas_akhir_komisi WHERE status LIKE "Penguji%" AND id_tugas_akhir ='.$id);
		return $query->result();

	} 	

	function get_approval_ta_list($id)
	{
		$query = $this->db->query('SELECT tugas_akhir.*, tugas_akhir_approval.status_slug FROM tugas_akhir, tugas_akhir_approval WHERE tugas_akhir.id_pengajuan = tugas_akhir_approval.id_pengajuan AND tugas_akhir_approval.id_user ='.$id.' AND tugas_akhir_approval.ttd LIKE "" AND tugas_akhir.status = 8');
		return $query->result();
	}

	//Manajemen Seminar
	function select_seminar_by_npm($npm)
	{
		$query = $this->db->query('SELECT seminar_sidang.* FROM seminar_sidang, tugas_akhir WHERE seminar_sidang.id_tugas_akhir = tugas_akhir.id_pengajuan AND tugas_akhir.npm ='.$npm);
		return $query->result();
	}

	function select_lampiran_by_seminar($id)
	{
		$this->db->select('a.*, b.nama');
		$this->db->from('seminar_sidang_berkas a');
		$this->db->join('jenis_berkas_lampiran b', 'a.jenis_berkas = b.id_jenis');
		$this->db->join('seminar_sidang c', 'a.id_seminar = c.id');
		$this->db->where(array('c.id' => $id));
		$query = $this->db->get();
		
		return $query->result();
	}

	function insert_lampiran_seminar($data)
	{
		$this->db->trans_start();
		$this->db->insert('seminar_sidang_berkas', $data);
		$this->db->trans_complete();
		
	}

	function get_berkas_name($id)
	{
		$result = $this->db->query('SELECT nama FROM jenis_berkas_lampiran WHERE id_jenis ='.$id)->row()->nama;
		return $result;
	}

	function get_approved_ta($npm)
	{
		$this->db->where('npm =', $npm);
		$this->db->where('status =', '4');
		$query = $this->db->get($this->table);
		return $query->result();
	}

	function cek_seminar($id,$jenis)
	{
		$result = $this->db->query("SELECT * FROM seminar_sidang WHERE id_tugas_akhir = $id AND jenis = '$jenis' AND (status = 4 OR status != 6 )");
		return $result->result();
	}

	function insert_seminar($data_seminar, $data_approval)
	{
		$this->db->trans_start();
		$this->db->insert($this->table_seminar, $data_seminar);
		$insert_id = $this->db->insert_id();
		
		$data_approval['id_pengajuan'] = $insert_id;
		$this->db->insert('seminar_sidang_approval', $data_approval);

		$this->db->trans_complete();
		
		return $insert_id;
	}

	function select_seminar_by_id($id)
	{
		$this->db->where(array('id' => $id));
		// $this->db->where(array('id_pengajuan' => $id, 'npm' => $npm, 'status' => -1));
		$this->db->where("(status='-1' OR status='5')");
		$query = $this->db->get($this->table_seminar);
		return $query->result_array();
	}

	function get_seminar_id($id)
	{
		$this->db->where(array('id' => $id));
		$query = $this->db->get($this->table_seminar);
		return $query->Row();
	}

	function get_seminar_approval_id($id)
	{
		$result = $this->db->query('SELECT ttd FROM seminar_sidang_approval WHERE id_pengajuan ='.$id)->row()->ttd;
		return $result;
	}
	
	function update_seminar($data_seminar,$data_approval, $where)
	{
	    $this->db->where('id', $where);
		$this->db->update($this->table_seminar, $data_seminar);
		
		$this->db->where('id_pengajuan', $where);
		$this->db->where('status_slug', 'Mahasiswa');
	    $this->db->update('seminar_sidang_approval', $data_approval);
	}

	function delete_seminar($data)
	{
		$this->db->delete('seminar_sidang', $data);
	}

	function delete_approval_seminar($data)
	{
		$this->db->delete('seminar_sidang_approval', $data);
	}

	function delete_berkas_seminar($data)
	{
		$query = $this->db->query('SELECT file FROM seminar_sidang_berkas WHERE id_seminar ='.$data);
		$results = $query->result();

		foreach ($results as $row)
		{
			unlink($row->file);
		}
	}
	
	function ajukan_seminar($where)
	{
		$this->db->where('id', $where);
	    $this->db->update($this->table_seminar, array('status' => '0'));
	}

	function ajukan_seminar_perbaikan($where,$status)
	{
		if($status == "pa"){
			$this->db->where('id', $where);
	    	$this->db->update($this->table_seminar, array('status' => '0', 'keterangan_tolak' => NULL));
		}

		if($status == "pb1"){
			$this->db->where('id', $where);
	    	$this->db->update($this->table_seminar, array('status' => '1', 'keterangan_tolak' => NULL));
		}

		if($status == "admin"){
			$this->db->where('id', $where);
	    	$this->db->update($this->table_seminar, array('status' => '2', 'keterangan_tolak' => NULL));
		}
		
	}

	function insert_approval_seminar($data)
	{
		$this->db->insert('seminar_sidang_approval', $data);
	}

	function insert_approval_ta($data)
	{
		$this->db->insert('tugas_akhir_approval', $data);
	}

	// seminar dosen
	function get_approval_seminar_by_pa($id)
	{
		$query = $this->db->query('SELECT seminar_sidang.*,tugas_akhir.npm,tugas_akhir.judul1,tugas_akhir.judul2,tugas_akhir.judul_approve FROM seminar_sidang, tbl_users_mahasiswa, tugas_akhir WHERE seminar_sidang.id_tugas_akhir = tugas_akhir.id_pengajuan AND tbl_users_mahasiswa.npm = tugas_akhir.npm AND seminar_sidang.status = "0" AND tbl_users_mahasiswa.dosen_pa ='.$id);
		return $query->result();
	}

	function get_approval_seminar_list($id)
	{
		$query = $this->db->query('SELECT seminar_sidang.*, tugas_akhir.*, seminar_sidang_approval.status_slug, seminar_sidang.jenis as jenis FROM seminar_sidang, tugas_akhir, seminar_sidang_approval WHERE seminar_sidang.id_tugas_akhir = tugas_akhir.id_pengajuan AND seminar_sidang.id = seminar_sidang_approval.id_pengajuan AND seminar_sidang_approval.ttd LIKE "" AND seminar_sidang.status = "1" AND seminar_sidang_approval.id_user ='.$id);
		return $query->result();
	}
	
	function decline_seminar($where,$dosenid,$status,$keterangan)
	{
		
		if($status == 'pb1'){
			$this->db->where('id', $where);
			$this->db->update($this->table_seminar, array('status' => '5','keterangan_tolak' => $status."#".$keterangan));

		}
		elseif($status == 'pa'){
			$this->db->where('id', $where);
			$this->db->update($this->table_seminar, array('status' => '5','keterangan_tolak' => $status."#".$keterangan));

		}
		elseif($status == 'admin'){
			$this->db->where('id', $where);
			$this->db->update($this->table_seminar, array('status' => '5','keterangan_tolak' => $status."#".$keterangan));

		}
		elseif($status == 'koor'){
			$this->db->where('id', $where);
			$this->db->update($this->table_seminar, array('status' => '6','keterangan_tolak' => $status."#".$keterangan));

		}
		
	}

	function get_seminar_by_id($id)
	{
		$this->db->select('*');
		$this->db->from('tugas_akhir');
		$this->db->join('seminar_sidang', 'tugas_akhir.id_pengajuan = seminar_sidang.id_tugas_akhir');
		$this->db->where('seminar_sidang.id', $id);
		
		$query = $this->db->get();
		return $query->result();
	}

	function approve_seminar($where,$ttd,$status,$dosenid)
	{
		$jml = $this->db->query("SELECT  COUNT(*) as jml FROM `seminar_sidang_approval` WHERE id_pengajuan = $where AND (status_slug LIKE 'Pembimbing Utama' OR status_slug LIKE 'Penguji 1') AND ttd LIKE ''")->row()->jml;

		// return $query->row();

		if($status == 'pb1'){
			$this->db->where('id_pengajuan', $where);
			$this->db->where('status_slug', 'Pembimbing Utama');
			$this->db->where('id_user', $dosenid);
			$this->db->update('seminar_sidang_approval', array('ttd' => $ttd));

			if($jml == 1){
				$this->db->where('id', $where);
				$this->db->update('seminar_sidang', array('status' => '2'));

				$data_surat = [
					'jenis' => '2',
					'id_jenis'  => $where,
				];
	
				$this->db->insert('staff_surat', $data_surat);
			}
				

		}

		if($status == 'ps1'){
			$this->db->where('id_pengajuan', $where);
			$this->db->where('status_slug', 'Penguji 1');
			$this->db->where('id_user', $dosenid);
			$this->db->update('seminar_sidang_approval', array('ttd' => $ttd));

			if($jml == 1){
				$this->db->where('id', $where);
				$this->db->update('seminar_sidang', array('status' => '2'));

				$data_surat = [
					'jenis' => '2',
					'id_jenis'  => $where,
				];
	
				$this->db->insert('staff_surat', $data_surat);
			}
				

		}

		elseif($status == 'pa'){
			$data = [
				'id_pengajuan' => $where,
				'status_slug'  => 'Pembimbing Akademik',
				'id_user'  => $dosenid,
				'ttd'  => $ttd
			];

			$this->db->insert('seminar_sidang_approval', $data);

				$this->db->where('id', $where);
				$this->db->update('seminar_sidang', array('status' => '1'));

		}

		elseif($status == 'Koordinator'){
			$data = [
				'id_pengajuan' => $where,
				'status_slug'  => 'Koordinator',
				'id_user'  => $dosenid,
				'ttd'  => $ttd
			];

			$this->db->insert('seminar_sidang_approval', $data);

				$this->db->where('id', $where);
				$this->db->update('seminar_sidang', array('status' => '7'));

		}

		elseif($status == 'kajur'){
			$data = [
				'id_pengajuan' => $where,
				'status_slug'  => 'Ketua Jurusan',
				'id_user'  => $dosenid,
				'ttd'  => $ttd
			];

			$this->db->insert('seminar_sidang_approval', $data);

				$this->db->where('id', $where);
				$this->db->update('seminar_sidang', array('status' => '4'));
		}

		elseif($status == 'Kaprodi'){
			$data = [
				'id_pengajuan' => $where,
				'status_slug'  => 'Koordinator',
				'id_user'  => $dosenid,
				'ttd'  => $ttd
			];
			
			$this->db->insert('seminar_sidang_approval', $data);

			$data_kaprodi = [
				'id_pengajuan' => $where,
				'status_slug'  => 'Ketua Program Studi',
				'id_user'  => $dosenid,
				'ttd'  => $ttd
			];
			
			$this->db->insert('seminar_sidang_approval', $data_kaprodi);

				$this->db->where('id', $where);
				$this->db->update('seminar_sidang', array('status' => '7'));
		}
		
	}

	function get_verifikasi_berkas_seminar($id) //tendik
	{
		$query = $this->db->query('SELECT seminar_sidang.*, tugas_akhir.judul1, tugas_akhir.judul2, tugas_akhir.judul_approve, tugas_akhir.no_penetapan, tugas_akhir.npm FROM seminar_sidang, tugas_akhir, tbl_users_mahasiswa, tbl_users_tendik WHERE tbl_users_tendik.id_user ='.$id.' AND tbl_users_tendik.unit_kerja = tbl_users_mahasiswa.jurusan AND tugas_akhir.npm = tbl_users_mahasiswa.npm AND tugas_akhir.status = 4 AND seminar_sidang.id_tugas_akhir = tugas_akhir.id_pengajuan AND seminar_sidang.status = 2');	
		return $query->result();

	}

	function get_surat($id) // tendik ta
	{
		$query = $this->db->query('SELECT * FROM staff_surat WHERE jenis = 1 AND id_jenis ='.$id);
		
		return $query->row();
	}

	function get_surat_seminar($id) // tendik seminar
	{
		$query = $this->db->query('SELECT * FROM staff_surat WHERE jenis = 2 AND id_jenis ='.$id);
		
		return $query->row();
	}

	function decline_berkas_seminar($where,$dosenid,$keterangan) //tendik
	{
		$this->db->where('id', $where);
		$this->db->update($this->table_seminar, array('status' => '5','keterangan_tolak' => $keterangan));
	}

	function approve_berkas_seminar($id,$dosenid,$ttd,$no_form,$no_undangan) //tendik
	{
		$nip = $this->db->query('SELECT nip_nik FROM tbl_users_tendik WHERE id_user ='.$dosenid)->row()->nip_nik;
	
		$this->db->where('id', $id);
		$this->db->update($this->table_seminar, array('status' => '3','keterangan_tolak' => NULL,'no_form' => $no_form,'no_undangan' => $no_undangan));

		$data_approval = [
			'id_pengajuan' => $id,
			'status_slug'  => 'Administrasi',
			'id_user'  => $dosenid,
			'ttd'  => $ttd
		];

		$this->db->insert('seminar_sidang_approval', $data_approval);
	}

	function get_approval_seminar_koordinator($id)
	{
		$query = $this->db->query("SELECT seminar_sidang.*, tugas_akhir.npm, tugas_akhir.judul1, tugas_akhir.judul2, tugas_akhir.judul_approve  FROM seminar_sidang, tugas_akhir, tbl_users_mahasiswa, tbl_users_dosen WHERE seminar_sidang.id_tugas_akhir = tugas_akhir.id_pengajuan AND tugas_akhir.npm = tbl_users_mahasiswa.npm AND tbl_users_mahasiswa.jurusan = tbl_users_dosen.jurusan AND tbl_users_dosen.id_user =$id AND seminar_sidang.status = 3 AND tugas_akhir.jenis = 'Skripsi' ORDER BY seminar_sidang.jenis");
		return $query->result();
	}

	function get_approval_seminar_kaprodi($id)
	{
		$query = $this->db->query("SELECT seminar_sidang.*, tugas_akhir.npm, tugas_akhir.judul1, tugas_akhir.judul2, tugas_akhir.judul_approve  FROM seminar_sidang, tugas_akhir, tbl_users_mahasiswa, tbl_users_dosen WHERE seminar_sidang.id_tugas_akhir = tugas_akhir.id_pengajuan AND tugas_akhir.npm = tbl_users_mahasiswa.npm AND tbl_users_mahasiswa.jurusan = tbl_users_dosen.jurusan AND tbl_users_dosen.id_user =$id AND seminar_sidang.status = 3 AND tugas_akhir.jenis != 'Skripsi' ORDER BY seminar_sidang.jenis");
		return $query->result();
	}


	//PDF
	function get_jurusan($npm)
	{
		$result = $this->db->query('SELECT jurusan.nama FROM tbl_users_mahasiswa, jurusan WHERE tbl_users_mahasiswa.npm ='.$npm.' AND jurusan.id_jurusan = tbl_users_mahasiswa.jurusan')->row()->nama;
        return $result;
	}

	function get_dosen_pa_detail($id)
	{
		$query = $this->db->query('SELECT tbl_users_dosen.*, tbl_users.* FROM tugas_akhir, tbl_users_mahasiswa, tbl_users_dosen, tbl_users WHERE tugas_akhir.id_pengajuan = '.$id.' AND tugas_akhir.npm = tbl_users_mahasiswa.npm AND tbl_users_mahasiswa.dosen_pa = tbl_users_dosen.id_user AND tbl_users_dosen.id_user = tbl_users.userId');

		return $query->row();
	}

	function get_admin_detail($id)
	{
		$query = $this->db->query('SELECT tbl_users_tendik.*, tbl_users.name, tugas_akhir_approval.ttd FROM tugas_akhir_approval, tbl_users_tendik, tbl_users WHERE tugas_akhir_approval.id_pengajuan = '.$id.' AND tugas_akhir_approval.status_slug = "Administrasi" AND tbl_users_tendik.id_user = tugas_akhir_approval.id_user AND tugas_akhir_approval.id_user = tbl_users.userId');
		return $query->row();
	}

	function get_admin_seminar_detail($id)
	{
		$query = $this->db->query("SELECT tbl_users_tendik.*, tbl_users.name, seminar_sidang_approval.ttd FROM seminar_sidang_approval, tbl_users_tendik, tbl_users WHERE seminar_sidang_approval.id_pengajuan = $id AND seminar_sidang_approval.status_slug = 'Administrasi' AND tbl_users_tendik.id_user = seminar_sidang_approval.id_user AND seminar_sidang_approval.id_user = tbl_users.userId");
		return $query->row();
	}

	function get_dosen_pb1($id)
	{
		$query = $this->db->query('SELECT tbl_users_dosen.*, tbl_users.name FROM tugas_akhir, tbl_users_dosen, tbl_users WHERE tugas_akhir.id_pengajuan ='.$id.' AND tugas_akhir.pembimbing1 = tbl_users_dosen.id_user AND tbl_users_dosen.id_user = tbl_users.userId');

		return $query->row();
	}

	function get_mahasiswa_detail($npm)
	{
		$query = $this->db->query('SELECT tbl_users_mahasiswa.*, tbl_users.name FROM tbl_users_mahasiswa, tbl_users WHERE tbl_users_mahasiswa.npm ='.$npm.' AND tbl_users_mahasiswa.id_user = tbl_users.userId');
		return $query->row();
	}

	function get_ttd_approval($id,$status_slug)
	{
		$this->db->select('*');
		$this->db->from('tugas_akhir_approval');
		$this->db->where('id_pengajuan', $id);
		$this->db->where('status_slug', $status_slug);

		$query = $this->db->get();
		return $query->row();
	}

	function get_ttd_approval_seminar($id,$status_slug)
	{
		$this->db->select('*');
		$this->db->from('seminar_sidang_approval');
		$this->db->where('id_pengajuan', $id);
		$this->db->where('status_slug', $status_slug);

		$query = $this->db->get();
		return $query->row();
	}

	function get_komisi($id)
	{
		$query = $this->db->query('SELECT * FROM tugas_akhir_approval, tugas_akhir_komisi WHERE tugas_akhir_approval.id_pengajuan = tugas_akhir_komisi.id_tugas_akhir AND (tugas_akhir_approval.status_slug LIKE "Pembimbing%" OR tugas_akhir_approval.status_slug LIKE "Penguji%") AND tugas_akhir_approval.status_slug NOT LIKE "Pembimbing Akademik" AND tugas_akhir_approval.id_user = tugas_akhir_komisi.id_user AND tugas_akhir_approval.status_slug = tugas_akhir_komisi.status AND tugas_akhir_approval.id_pengajuan ='.$id);
		return $query->result();
	}

	function get_komisi_seminar($id_seminar)
	{
		$query = $this->db->query("SELECT seminar_sidang_approval.*,tugas_akhir_komisi.* FROM seminar_sidang_approval, tugas_akhir_komisi, seminar_sidang WHERE seminar_sidang_approval.id_pengajuan = $id_seminar AND (seminar_sidang_approval.status_slug LIKE 'Pembimbing%' OR seminar_sidang_approval.status_slug LIKE 'Penguji%') AND seminar_sidang_approval.id_pengajuan = seminar_sidang.id AND seminar_sidang.id_tugas_akhir = tugas_akhir_komisi.id_tugas_akhir AND seminar_sidang_approval.status_slug = tugas_akhir_komisi.status");
		return $query->result();
	}

	function count_pembimbing_seminar($id)
	{
		$query = $this->db->query('SELECT COUNT(*) as count FROM tugas_akhir_approval, tugas_akhir_komisi WHERE tugas_akhir_approval.id_pengajuan = tugas_akhir_komisi.id_tugas_akhir AND (tugas_akhir_approval.status_slug LIKE "Pembimbing%") AND tugas_akhir_approval.status_slug NOT LIKE "Pembimbing Akademik" AND tugas_akhir_approval.id_user = tugas_akhir_komisi.id_user AND tugas_akhir_approval.status_slug = tugas_akhir_komisi.status AND tugas_akhir_approval.id_pengajuan ='.$id);
		return $query->row();
	}

	function get_komisi_by_id($id,$user)
	{
		$query = $this->db->query('SELECT * FROM tugas_akhir_approval, tugas_akhir_komisi WHERE tugas_akhir_approval.id_pengajuan = tugas_akhir_komisi.id_tugas_akhir AND (tugas_akhir_approval.status_slug LIKE "Pembimbing%" OR tugas_akhir_approval.status_slug LIKE "Penguji%") AND tugas_akhir_approval.status_slug NOT LIKE "Pembimbing Akademik" AND tugas_akhir_approval.id_user = tugas_akhir_komisi.id_user AND tugas_akhir_approval.id_pengajuan ='.$id.' AND tugas_akhir_approval.id_user ='.$user);
		return $query->row();
	}

	function get_komisi_by_status_slug($id,$status)
	{
		$query = $this->db->query("SELECT * FROM tugas_akhir_approval, tugas_akhir_komisi WHERE tugas_akhir_approval.id_pengajuan = $id AND tugas_akhir_approval.id_pengajuan = tugas_akhir_komisi.id_tugas_akhir AND tugas_akhir_komisi.status = '$status' AND tugas_akhir_komisi.status = tugas_akhir_approval.status_slug");
		return $query->row();
	}
	
	function get_tgl_acc($id)
	{
		$query = $this->db->query('SELECT * FROM tugas_akhir_approval WHERE status_slug = "Koordinator" AND id_pengajuan ='.$id)->row()->created_at;

		$date = explode('-',substr($query,0,10));
		$month = $date[1];       

		switch($month)
        {
			case "01":
            	$bulan = "Januari";
			break;
			case "02":
            	$bulan = "Februari";
			break;
			case "03":
            	$bulan = "Maret";
			break;
			case "04":
            	$bulan = "April";
			break;
			case "05":
            	$bulan = "Mei";
			break;
			case "06":
            	$bulan = "Juni";
			break;
			case "07":
            	$bulan = "Juli";
			break;
			case "08":
            	$bulan = "Agustus";
			break;
			case "09":
            	$bulan = "September";
			break;
			case "10":
            	$bulan = "Oktober";
			break;
			case "11":
            	$bulan = "November";
			break;
			case "12":
            	$bulan = "Desember";
            break;
		}
        
		$tgl = $date[2].' '.$bulan.' '.$date[0];
		return $tgl;
	}

	//nilai seminar dosen
	function get_nilai_seminar($id)
	{
		$query = $this->db->query('SELECT seminar_sidang.*, tugas_akhir.npm, tugas_akhir.judul1, tugas_akhir.judul2,tugas_akhir.judul_approve, tugas_akhir_komisi.status, tugas_akhir_komisi.nip_nik, tugas_akhir_komisi.id_user, tugas_akhir_komisi.nama FROM seminar_sidang_nilai_check,seminar_sidang, tugas_akhir, tugas_akhir_komisi WHERE tugas_akhir_komisi.id_user ='.$id.' AND tugas_akhir_komisi.id_tugas_akhir = tugas_akhir.id_pengajuan AND tugas_akhir.id_pengajuan = seminar_sidang.id_tugas_akhir AND seminar_sidang.status = 4 AND seminar_sidang_nilai_check.id_seminar = seminar_sidang.id AND seminar_sidang_nilai_check.status = tugas_akhir_komisi.status AND seminar_sidang_nilai_check.ket = 0');
		return $query->result();
	}

	//rekap koor
	function get_ta_rekap_detail_terima($id_user,$angkatan,$jenis,$npm1,$npm2)
	{
		$query = $this->db->query("SELECT tugas_akhir.* FROM tugas_akhir, tbl_users_dosen, tbl_users_mahasiswa WHERE tbl_users_dosen.id_user = $id_user AND tugas_akhir.npm = tbl_users_mahasiswa.npm AND tbl_users_dosen.jurusan = tbl_users_mahasiswa.jurusan AND (tugas_akhir.status = 4) AND tugas_akhir.jenis = '$jenis' AND tugas_akhir.npm LIKE '$angkatan%' AND (tugas_akhir.npm LIKE '__$npm1%' OR tugas_akhir.npm LIKE '__$npm2%') ORDER BY tugas_akhir.created_at");
		return $query->result();
	}

	function get_ta_rekap_detail_tolak($id_user,$angkatan,$jenis,$npm1,$npm2)
	{
		$query = $this->db->query("SELECT tugas_akhir.* FROM tugas_akhir, tbl_users_dosen, tbl_users_mahasiswa WHERE tbl_users_dosen.id_user = $id_user AND tugas_akhir.npm = tbl_users_mahasiswa.npm AND tbl_users_dosen.jurusan = tbl_users_mahasiswa.jurusan AND (tugas_akhir.status = 6) AND tugas_akhir.jenis = '$jenis' AND tugas_akhir.npm LIKE '$angkatan%' AND (tugas_akhir.npm LIKE '__$npm1%' OR tugas_akhir.npm LIKE '__$npm2%') ORDER BY tugas_akhir.created_at");
		return $query->result();
	}

	function get_ta_rekap($id)
	{
		$query = $this->db->query('SELECT tugas_akhir.* FROM tugas_akhir, tbl_users_dosen, tbl_users_mahasiswa WHERE tbl_users_dosen.id_user ='.$id.' AND tugas_akhir.npm = tbl_users_mahasiswa.npm AND tbl_users_dosen.jurusan = tbl_users_mahasiswa.jurusan AND (tugas_akhir.status = 4) ORDER BY tugas_akhir.created_at');
		return $query->result();
	}

	function get_seminar_rekap_koor($id)
	{
		$query = $this->db->query('SELECT seminar_sidang.*, tugas_akhir.npm, tugas_akhir.judul1, tugas_akhir.judul2, tugas_akhir.judul_approve FROM seminar_sidang, tugas_akhir, tbl_users_dosen, tbl_users_mahasiswa WHERE tbl_users_dosen.id_user ='.$id.' AND tugas_akhir.npm = tbl_users_mahasiswa.npm AND tbl_users_dosen.jurusan = tbl_users_mahasiswa.jurusan AND seminar_sidang.id_tugas_akhir = tugas_akhir.id_pengajuan AND seminar_sidang.status >= 4 ORDER BY seminar_sidang.created_at');
		return $query->result();
	}

	function get_rekap_ta_jml($id_user,$angkatan,$strata1,$strata2)
	{
		$query = $this->db->query("SELECT COUNT(*) AS 'jml' FROM tugas_akhir, tbl_users_mahasiswa, tbl_users_dosen WHERE tbl_users_dosen.id_user = $id_user AND tbl_users_dosen.jurusan = tbl_users_mahasiswa.jurusan AND tbl_users_mahasiswa.npm = tugas_akhir.npm AND (tugas_akhir.status = 4) AND (tugas_akhir.npm LIKE '__$strata1%' OR tugas_akhir.npm LIKE '__$strata2%') AND tugas_akhir.npm LIKE '$angkatan%'");
		return $query->row();
	}

	function get_rekap_ta_jml_tolak($id_user,$angkatan,$strata1,$strata2)
	{
		$query = $this->db->query("SELECT COUNT(*) AS 'jml' FROM tugas_akhir, tbl_users_mahasiswa, tbl_users_dosen WHERE tbl_users_dosen.id_user = $id_user AND tbl_users_dosen.jurusan = tbl_users_mahasiswa.jurusan AND tbl_users_mahasiswa.npm = tugas_akhir.npm AND (tugas_akhir.status = 6) AND (tugas_akhir.npm LIKE '__$strata1%' OR tugas_akhir.npm LIKE '__$strata2%') AND tugas_akhir.npm LIKE '$angkatan%'");
		return $query->row();
	}

	function get_seminar_rekap_koor_jml($id_user,$angkatan,$npm1,$npm2,$seminar)
	{
		$query = $this->db->query("SELECT COUNT(*) AS 'jml' FROM seminar_sidang,tbl_users_mahasiswa,tbl_users_dosen,tugas_akhir WHERE tbl_users_dosen.id_user = $id_user AND tbl_users_dosen.jurusan = tbl_users_mahasiswa.jurusan AND tbl_users_mahasiswa.npm = tugas_akhir.npm AND tugas_akhir.status = 4 AND tugas_akhir.npm LIKE '$angkatan%' AND (tugas_akhir.npm LIKE '__$npm1%' OR tugas_akhir.npm LIKE '__$npm2%') AND tugas_akhir.id_pengajuan = seminar_sidang.id_tugas_akhir AND seminar_sidang.jenis = '$seminar' AND (seminar_sidang.status != 6 AND seminar_sidang.status != 5)");
		return $query->row();
	}

	function get_seminar_rekap_koor_detail($id_user,$angkatan,$npm1,$npm2,$seminar,$jenis)
	{
		$query = $this->db->query("SELECT * FROM seminar_sidang,tbl_users_mahasiswa,tbl_users_dosen,tugas_akhir WHERE tbl_users_dosen.id_user = $id_user AND tbl_users_dosen.jurusan = tbl_users_mahasiswa.jurusan AND tbl_users_mahasiswa.npm = tugas_akhir.npm AND tugas_akhir.status = 4 AND tugas_akhir.npm LIKE '$angkatan%' AND (tugas_akhir.npm LIKE '__$npm1%' OR tugas_akhir.npm LIKE '__$npm2%') AND tugas_akhir.jenis = '$jenis' AND tugas_akhir.id_pengajuan = seminar_sidang.id_tugas_akhir AND seminar_sidang.jenis = '$seminar'");
		return $query->result();
	}

	function get_ta_acc_date($id)
	{
		$query = $this->db->query('SELECT tugas_akhir_approval.created_at FROM tugas_akhir_approval, tugas_akhir WHERE tugas_akhir.id_pengajuan = tugas_akhir_approval.id_pengajuan AND tugas_akhir_approval.status_slug LIKE "%Koordinator%" AND tugas_akhir.id_pengajuan ='. $id);
		return $query->row();
	}

	function get_smr_acc_date($id)
	{
		$query = $this->db->query('SELECT seminar_sidang_approval.created_at FROM seminar_sidang_approval, seminar_sidang WHERE seminar_sidang.id = seminar_sidang_approval.id_pengajuan AND seminar_sidang_approval.status_slug LIKE "%Koordinator%" AND seminar_sidang.id ='. $id);
		return $query->row();
	}


	function get_komisi_alter($token)
	{
		$query = $this->db->query("SELECT tugas_akhir.*, tugas_akhir_komisi.* FROM tugas_akhir_komisi_alternatif, tugas_akhir_komisi, tugas_akhir WHERE tugas_akhir_komisi_alternatif.token = '$token' AND tugas_akhir_komisi_alternatif.id_tugas_akhir = tugas_akhir.id_pengajuan AND tugas_akhir_komisi_alternatif.status = tugas_akhir_komisi.status AND tugas_akhir_komisi_alternatif.id_tugas_akhir = tugas_akhir_komisi.id_tugas_akhir AND tugas_akhir.status = 8");
		return $query->row();
	}

	function get_komisi_seminar_alter($token)
	{
		$query = $this->db->query("SELECT tugas_akhir.*, seminar_sidang.*, tugas_akhir.jenis, seminar_sidang.jenis as seminar_jenis, tugas_akhir_komisi.status, tugas_akhir_komisi.nip_nik, tugas_akhir_komisi.nama FROM seminar_sidang_approval_alternatif, seminar_sidang, tugas_akhir_komisi, tugas_akhir WHERE seminar_sidang_approval_alternatif.token = '$token' AND seminar_sidang_approval_alternatif.id_seminar = seminar_sidang.id AND seminar_sidang.id_tugas_akhir = tugas_akhir.id_pengajuan AND tugas_akhir.id_pengajuan = tugas_akhir_komisi.id_tugas_akhir AND tugas_akhir_komisi.status = seminar_sidang_approval_alternatif.status AND seminar_sidang.status = 4");
		return $query->row();
	}

	function cek_token($token)
	{
		$query = $this->db->query("SELECT * FROM tugas_akhir_komisi_alternatif WHERE token = '$token' AND ket = 0");
		return $query->row();
	}

	function cek_token_seminar($token)
	{
		$query = $this->db->query("SELECT * FROM seminar_sidang_approval_alternatif WHERE token = '$token' AND ket = 0");
		return $query->row();
	}

	function get_komisi_alter_id($id)
	{
		$query = $this->db->query("SELECT * FROM `tugas_akhir_komisi_alternatif` WHERE id_tugas_akhir = $id AND ket = 0");
		return $query->result();
	}

	function get_komisi_seminar_alter_id($id)
	{
		$query = $this->db->query("SELECT * FROM `seminar_sidang_approval_alternatif` WHERE id_seminar = $id AND ket = 0");
		return $query->result();
	}

	function get_komisi_alter_seminar_id($id)
	{
		$query = $this->db->query("SELECT * FROM `tugas_akhir_komisi_alternatif` WHERE id_tugas_akhir = $id AND ket = 1");
		return $query->result();
	}

	function get_komposisi_nilai($dosenid)
	{
		$query = $this->db->query("SELECT seminar_sidang_komponen.* FROM seminar_sidang_komponen, tbl_users_dosen WHERE tbl_users_dosen.id_user = $dosenid AND tbl_users_dosen.jurusan = seminar_sidang_komponen.id_prodi");
		return $query->result();
	}

	function komposisi_nilai_save($data)
	{
		$this->db->trans_start();
		$this->db->insert('seminar_sidang_komponen', $data);
		$insert_id = $this->db->insert_id();

		$this->db->trans_complete();
		
		return $insert_id;
	}

	function komposisi_nilai_meta_save($data)
	{
		$this->db->trans_start();
		$this->db->insert('seminar_sidang_komponen_meta', $data);
		$this->db->trans_complete();	
	}

	function get_komposisi_nilai_meta_id($id)
	{
		$query = $this->db->query("SELECT * FROM seminar_sidang_komponen_meta WHERE id_komponen =".$id);
		return $query->result();
	}

	function get_komposisi_nilai_id($id)
	{
		$query = $this->db->query("SELECT * FROM seminar_sidang_komponen WHERE id =".$id);
		return $query->row();
	}
	

	function cek_komposisi_nilai($prodi,$jenis,$tipe)
	{
		$query = $this->db->query("SELECT * FROM seminar_sidang_komponen WHERE id_prodi = $prodi AND jenis = '$jenis' AND tipe = '$tipe' AND status = 0");
		return $query->row();
	}

	function nonaktifkan_komposisi($id)
	{
		$this->db->where('id', $id);
		$this->db->update('seminar_sidang_komponen', array('status' => '1'));
	}

	function delete_komposisi_meta($id)
	{
	    $this->db->delete('seminar_sidang_komponen_meta', array('id_komponen' => $id));
	}

	function update_komposisi($id,$data)
	{
		$this->db->where('id', $id);
	    $this->db->update("seminar_sidang_komponen", $data);
	}

	function get_tugas_akhir_seminar_id($id)
	{
		$query = $this->db->query("SELECT tugas_akhir.* FROM tugas_akhir, seminar_sidang WHERE seminar_sidang.id = $id AND seminar_sidang.id_tugas_akhir = tugas_akhir.id_pengajuan");
		return $query->row();
	}

	function get_komponen_nilai_meta($npm,$jenis,$tipe)
	{
		$query = $this->db->query("SELECT seminar_sidang_komponen_meta.* FROM seminar_sidang_komponen, seminar_sidang_komponen_meta, tbl_users_mahasiswa, tugas_akhir WHERE tbl_users_mahasiswa.npm = $npm AND tbl_users_mahasiswa.npm = tugas_akhir.npm AND tugas_akhir.jenis = '$jenis' AND tbl_users_mahasiswa.jurusan = seminar_sidang_komponen.id_prodi AND seminar_sidang_komponen.id = seminar_sidang_komponen_meta.id_komponen AND tugas_akhir.jenis = seminar_sidang_komponen.jenis AND seminar_sidang_komponen.tipe = '$tipe' AND seminar_sidang_komponen.status = 0 ORDER BY seminar_sidang_komponen_meta.id");
		return $query->result();
	}

	function get_komisi_seminar_id($id)
	{
		$query = $this->db->query("SELECT tugas_akhir_komisi.* FROM tugas_akhir_komisi, seminar_sidang WHERE seminar_sidang.id = $id AND seminar_sidang.id_tugas_akhir = tugas_akhir_komisi.id_tugas_akhir");
		return $query->result();
	}

	function insert_seminar_nilai_check($data)
	{
		$this->db->trans_start();
		$this->db->insert('seminar_sidang_nilai_check', $data);
		$this->db->trans_complete();	
	}

	function insert_seminar_nilai($data)
	{
		$this->db->trans_start();
		$this->db->insert('seminar_sidang_nilai', $data);
		// $this->db->insert('seminar_sidang_nilai_check', $data_cek);
		$this->db->trans_complete();	
	}

	function update_nilai_seminar_check($id,$status,$saran,$ttd)
	{
		$this->db->where('id_seminar', $id);
		$this->db->where('status', $status);
	    $this->db->update('seminar_sidang_nilai_check', array('saran' => $saran, 'ket' => '1','ttd' => $ttd));
	}

	function cek_seminar_nilai_fill($id)
	{
		$query = $this->db->query("SELECT * FROM seminar_sidang_nilai_check WHERE id_seminar = $id AND ket = 0");
		return $query->result();
	}

	function seminar_sidang_nilai_dosen_update($id)
	{
		$this->db->where('id', $id);
	    $this->db->update('seminar_sidang', array('status' => '8'));
	}

	function insert_seminar_approval_alter($data)
	{	
		$this->db->insert('seminar_sidang_approval_alternatif', $data);	
	}

	function seminar_sidang_komisi_alter_update($id,$token)
	{
		$this->db->where('id_seminar', $id);
		$this->db->where('token', $token);
	    $this->db->update('seminar_sidang_approval_alternatif', array('ket' => '1'));
	}

	function get_approval_nilai_seminar_koordinator($id)
	{
		$query = $this->db->query("SELECT seminar_sidang.*, tugas_akhir.npm, tugas_akhir.judul1, tugas_akhir.judul2, tugas_akhir.judul_approve, tugas_akhir.jenis as jenis_ta  FROM seminar_sidang, tugas_akhir, tbl_users_mahasiswa, tbl_users_dosen WHERE seminar_sidang.id_tugas_akhir = tugas_akhir.id_pengajuan AND tugas_akhir.npm = tbl_users_mahasiswa.npm AND tbl_users_mahasiswa.jurusan = tbl_users_dosen.jurusan AND tbl_users_dosen.id_user =$id AND seminar_sidang.status = 8  AND tugas_akhir.jenis = 'Skripsi' ORDER BY seminar_sidang.jenis");
		return $query->result();
	}

	function get_approval_nilai_seminar_kaprodi($id)
	{
		$query = $this->db->query("SELECT seminar_sidang.*, tugas_akhir.npm, tugas_akhir.judul1, tugas_akhir.judul2, tugas_akhir.judul_approve, tugas_akhir.jenis as jenis_ta  FROM seminar_sidang, tugas_akhir, tbl_users_mahasiswa, tbl_users_dosen WHERE seminar_sidang.id_tugas_akhir = tugas_akhir.id_pengajuan AND tugas_akhir.npm = tbl_users_mahasiswa.npm AND tbl_users_mahasiswa.jurusan = tbl_users_dosen.jurusan AND tbl_users_dosen.id_user =$id AND seminar_sidang.status = 8  AND tugas_akhir.jenis != 'Skripsi' ORDER BY seminar_sidang.jenis");
		return $query->result();
	}

	function get_approval_nilai_seminar_kajur($id)
	{
		$query = $this->db->query('SELECT seminar_sidang.*, tugas_akhir.npm, tugas_akhir.judul1, tugas_akhir.judul2, tugas_akhir.judul_approve  FROM seminar_sidang, tugas_akhir, tbl_users_mahasiswa, tbl_users_dosen WHERE seminar_sidang.id_tugas_akhir = tugas_akhir.id_pengajuan AND tugas_akhir.npm = tbl_users_mahasiswa.npm AND tbl_users_mahasiswa.jurusan = tbl_users_dosen.jurusan AND tbl_users_dosen.id_user ='.$id.' AND seminar_sidang.status = 9 ORDER BY seminar_sidang.jenis');
		return $query->result();
	}

	//pdf
	function get_komisi_seminar_check($id)
	{
		$query = $this->db->query("SELECT * FROM seminar_sidang_nilai_check WHERE id_seminar = $id AND ket = 1");
		return $query->result();
	}

	function get_komponen_nilai_seminar_ujian($id,$status)
	{
		$query = $this->db->query("SELECT * FROM seminar_sidang_nilai JOIN seminar_sidang_komponen_meta ON seminar_sidang_nilai.komponen = seminar_sidang_komponen_meta.id WHERE id_seminar_sidang = $id AND seminar_sidang_nilai.status = '$status' AND seminar_sidang_komponen_meta.unsur LIKE 'Ujian'");
		return $query->result();
	}

	function get_komponen_nilai_seminar_ta($id,$status)
	{
		$query = $this->db->query("SELECT * FROM seminar_sidang_nilai JOIN seminar_sidang_komponen_meta ON seminar_sidang_nilai.komponen = seminar_sidang_komponen_meta.id WHERE id_seminar_sidang = $id AND seminar_sidang_nilai.status = '$status' AND (seminar_sidang_komponen_meta.unsur LIKE 'Skripsi' OR seminar_sidang_komponen_meta.unsur LIKE 'Tugas Akhir' OR seminar_sidang_komponen_meta.unsur LIKE 'Tesis' OR seminar_sidang_komponen_meta.unsur LIKE 'Disertasi')");
		return $query->result();
	}

	function get_komponen_nilai_seminar_all($id,$status)
	{
		$query = $this->db->query("SELECT * FROM seminar_sidang_nilai JOIN seminar_sidang_komponen_meta ON seminar_sidang_nilai.komponen = seminar_sidang_komponen_meta.id WHERE id_seminar_sidang = $id AND seminar_sidang_nilai.status = '$status'");
		return $query->result();
	}

	function get_unsur_distinct($id_komponen)
	{
		$query = $this->db->query("SELECT DISTINCT unsur FROM `seminar_sidang_komponen_meta` WHERE id_komponen = $id_komponen");
		return $query->result();
	}

	function get_id_komponen_seminar($id_seminar)
	{
		$query = $this->db->query("SELECT DISTINCT id_komponen FROM `seminar_sidang_nilai` WHERE id_seminar_sidang = $id_seminar");
		return $query->row();
	}

	function select_komponen_seminar_id($id)
	{
		$query = $this->db->query("SELECT * FROM `seminar_sidang_komponen` WHERE id = $id");
		return $query->row();
	}

	function get_komisi_seminar_data($id,$status)
	{
		$query = $this->db->query("SELECT tugas_akhir_komisi.* FROM tugas_akhir_komisi, seminar_sidang WHERE seminar_sidang.id = $id AND seminar_sidang.id_tugas_akhir = tugas_akhir_komisi.id_tugas_akhir AND tugas_akhir_komisi.status = '$status'");
		return $query->row();
	}

	function get_seminar_nilai_check_by_status($id,$status)
	{
		$query = $this->db->query("SELECT * FROM seminar_sidang_nilai_check WHERE seminar_sidang_nilai_check.status = '$status' AND seminar_sidang_nilai_check.id_seminar = $id");
		return $query->row();	
	}

	function get_komisi_ta_by_status($id_ta,$status)
	{
		$query = $this->db->query("SELECT * FROM `tugas_akhir_komisi` WHERE id_tugas_akhir = $id_ta AND tugas_akhir_komisi.status = '$status'");
		return $query->row();
	}

	function insert_nilai_seminar_koor($data)
	{
		$this->db->insert('seminar_sidang_nilai_check', $data);
	}

	function insert_nilai_seminar_koor_kompre($data)
	{
		$this->db->insert('seminar_sidang_kompre', $data);
	}

	function insert_nilai_seminar_kajur($data)
	{
		$this->db->insert('seminar_sidang_nilai_check', $data);
	}

	function update_nilai_seminar_koor($where)
	{
		$this->db->where('id', $where);
	    $this->db->update($this->table_seminar, array('status' => '9'));
	}

	function update_nilai_seminar_kajur($where)
	{
		$this->db->where('id', $where);
	    $this->db->update($this->table_seminar, array('status' => '10'));
	}

	function ttd_nilai_seminar_koor($id_seminar)
	{
		$query = $this->db->query("SELECT * FROM `seminar_sidang_nilai_check` WHERE seminar_sidang_nilai_check.status = 'Koordinator' AND id_seminar = $id_seminar");
		return $query->row();		
	}

	function ttd_nilai_seminar_kajur($id_seminar)
	{
		$query = $this->db->query("SELECT * FROM `seminar_sidang_nilai_check` WHERE seminar_sidang_nilai_check.status = 'Ketua Jurusan' AND id_seminar = $id_seminar");
		return $query->row();		
	}

	function insert_bidang_jurusan($data)
	{
		$this->db->insert('bidang_ilmu', $data);
	}

	function delete_bidang_jurusan($id)
	{
		$this->db->delete('bidang_ilmu', array('id' => $id));
	}

	function get_verifikasi_ta_komponen_wajib($id)
	{
		$query = $this->db->query("SELECT * FROM verifikasi_ta_komponen WHERE bidang = $id AND ket LIKE 'Wajib'");
		return $query->result();	
	}

	function get_verifikasi_ta_komponen_konten($id)
	{
		$query = $this->db->query("SELECT * FROM verifikasi_ta_komponen WHERE bidang = $id AND ket LIKE 'Konten Program'");
		return $query->result();	
	}

	function get_bidang_ilmu_id($id)
	{
		$query = $this->db->query("SELECT * FROM bidang_ilmu WHERE id = $id");
		return $query->row();	
	}

	function insert_komponen_ta($data)
	{
		$this->db->insert('verifikasi_ta_komponen', $data);
	}

	function delete_komponen_ta($id,$bidang)
	{
		$this->db->delete('verifikasi_ta_komponen', array('id' => $id,'bidang' => $bidang));
	}

	function get_dosen_verifikator($id)
	{
		$query = $this->db->query("SELECT * FROM verifikasi_ta_nilai WHERE id_ta = $id");
		return $query->row();	
	}

	function approve_ta_kaprodi($id)
	{
		$this->db->where('id_pengajuan', $id);
	    $this->db->update($this->table, array('status' => '7'));
	}

	function insert_approve_ta_kaprodi($data)
	{
		$this->db->insert('tugas_akhir_approval', $data);
	}

	function get_verifikasi_program_ta($npm)
	{
		$query = $this->db->query("SELECT * FROM verifikasi_ta_nilai, tugas_akhir WHERE tugas_akhir.npm = $npm AND tugas_akhir.jenis = 'Tugas Akhir' AND tugas_akhir.id_pengajuan = verifikasi_ta_nilai.id_ta AND tugas_akhir.status = 4");
		return $query->row();	
	}

	function get_verifikasi_program_ta_dosen($id)
	{
		$query = $this->db->query("SELECT * FROM verifikasi_ta_nilai, tugas_akhir WHERE id_dosen = $id AND tugas_akhir.jenis = 'Tugas Akhir' AND tugas_akhir.id_pengajuan = verifikasi_ta_nilai.id_ta AND tugas_akhir.status = 4 AND verifikasi_ta_nilai.ket = 4 ORDER BY verifikasi_ta_nilai.created_at");
		return $query->result();	
	}

	function get_verifikasi_program_ta_nilai($id)
	{
		$query = $this->db->query("SELECT * FROM verifikasi_ta_nilai, tugas_akhir WHERE tugas_akhir.id_pengajuan = $id AND tugas_akhir.jenis = 'Tugas Akhir' AND tugas_akhir.id_pengajuan = verifikasi_ta_nilai.id_ta AND tugas_akhir.status = 4 AND verifikasi_ta_nilai.ket = 4");
		return $query->row();	
	}

	function get_verifikasi_program_ta_komponen($bidang_id)
	{
		$query = $this->db->query("SELECT * FROM verifikasi_ta_komponen WHERE bidang = $bidang_id order by ket desc");
		return $query->result();	
	}

	function get_verifikasi_program_ta_pertemuan($id)
	{
		$query = $this->db->query("SELECT * FROM verifikasi_ta_pertemuan WHERE id_tugas_akhir = $id");
		return $query->result();	
	}

	function get_verifikasi_program_ta_pertemuan_wajib($id)
	{
		$query = $this->db->query("SELECT * FROM verifikasi_ta_pertemuan JOIN verifikasi_ta_komponen ON verifikasi_ta_pertemuan.id_komponen = verifikasi_ta_komponen.id WHERE verifikasi_ta_pertemuan.id_tugas_akhir = $id AND verifikasi_ta_komponen.ket = 'Wajib' ORDER BY verifikasi_ta_komponen.id");
		return $query->result();
	}

	function get_verifikasi_program_ta_pertemuan_konten($id)
	{
		$query = $this->db->query("SELECT * FROM verifikasi_ta_pertemuan JOIN verifikasi_ta_komponen ON verifikasi_ta_pertemuan.id_komponen = verifikasi_ta_komponen.id WHERE verifikasi_ta_pertemuan.id_tugas_akhir = $id AND verifikasi_ta_komponen.ket = 'Konten Program' ORDER BY verifikasi_ta_komponen.id");
		return $query->result();
	}

	function insert_verifikasi_ta_pertemuan($data)
	{	
		$this->db->insert('verifikasi_ta_pertemuan', $data);	
	}

	function update_verifikasi_ta_ket($id)
	{
		$this->db->where('id_ta', $id);
	    $this->db->update('verifikasi_ta_nilai', array('ket' => '1'));
	}

	function update_verifikasi_ta_pertemuan($pertemuan, $where)
	{
		$this->db->where('id_verif', $where);
	    $this->db->update('verifikasi_ta_pertemuan', array('pertemuan' => $pertemuan));
	}

	function get_created_verifikasi_ta($id)
	{
		$query = $this->db->query("SELECT created FROM `verifikasi_ta_pertemuan` WHERE id_tugas_akhir = $id LIMIT 1");
		return $query->row();
	}

	function get_pertemuan_verifikasi_ta($id_verif,$id_ta)
	{
		$query = $this->db->query("SELECT pertemuan FROM `verifikasi_ta_pertemuan` WHERE id_verif = $id_verif AND id_tugas_akhir = $id_ta");
		return $query->row();
	}

	function cek_verfikasi_ta_pertemuan($id)
	{
		$query = $this->db->query("SELECT * FROM `verifikasi_ta_pertemuan` WHERE id_tugas_akhir = $id AND pertemuan = 0");
		return $query->result();
	}

	function update_verifikasi_ta_nilai($id_ta, $nilai, $ttd, $nilai_date)
	{
		$this->db->where('id_ta', $id_ta);
	    $this->db->update('verifikasi_ta_nilai', array('nilai' => $nilai,'ket' => '5','ttd' => $ttd, 'nilai_date' => $nilai_date));
	}

	function get_verifikasi_ta_list($userid)
	{
		$query = $this->db->query("SELECT tugas_akhir.*, verifikasi_ta_nilai.* FROM tugas_akhir,verifikasi_ta_nilai,tbl_users_dosen WHERE tbl_users_dosen.id_user = $userid AND tugas_akhir.jenis = 'Tugas Akhir' AND tugas_akhir.pembimbing1 = tbl_users_dosen.id_user AND tugas_akhir.status = 4 AND tugas_akhir.id_pengajuan = verifikasi_ta_nilai.id_ta AND verifikasi_ta_nilai.ket = 1");
		return $query->result();
	}

	function get_verifikasi_ta_list_pa($userid)
	{
		$query = $this->db->query("SELECT tugas_akhir.*, verifikasi_ta_nilai.* FROM tugas_akhir,verifikasi_ta_nilai,tbl_users_dosen, tbl_users_mahasiswa WHERE tbl_users_dosen.id_user = $userid AND tugas_akhir.npm = tbl_users_mahasiswa.npm AND tbl_users_mahasiswa.dosen_pa = tbl_users_dosen.id_user AND tugas_akhir.status = 4 AND tugas_akhir.jenis = 'Tugas Akhir' AND tugas_akhir.id_pengajuan = verifikasi_ta_nilai.id_ta AND verifikasi_ta_nilai.ket = 2");
		return $query->result();
	}

	function get_verifikasi_ta_list_kaprodi($userid)
	{
		$query = $this->db->query("SELECT tugas_akhir.*, verifikasi_ta_nilai.* FROM tugas_akhir,verifikasi_ta_nilai,tbl_users_dosen, tbl_users_mahasiswa WHERE tbl_users_dosen.id_user = $userid AND tugas_akhir.npm = tbl_users_mahasiswa.npm AND tbl_users_mahasiswa.jurusan = tbl_users_dosen.jurusan AND tugas_akhir.jenis = 'Tugas Akhir' AND tugas_akhir.status = 4 AND tugas_akhir.id_pengajuan = verifikasi_ta_nilai.id_ta AND verifikasi_ta_nilai.ket = 3");
		return $query->result();
	}

	function insert_approve_ta_verifikasi($data)
	{	
		$this->db->insert('verifikasi_ta_approval', $data);	
	}

	function update_nilai_ta_verifikasi($status,$where)
	{	
		if($status == "Pembimbing Utama"){
			$this->db->where('id_ta', $where);
	    	$this->db->update('verifikasi_ta_nilai', array('ket' => '2'));
		}
		elseif($status == "Pembimbing Akademik"){
			$this->db->where('id_ta', $where);
	    	$this->db->update('verifikasi_ta_nilai', array('ket' => '3'));
		}
		elseif($status == "Ketua Program Studi"){
			$this->db->where('id_ta', $where);
	    	$this->db->update('verifikasi_ta_nilai', array('ket' => '4'));
		}
		
	}

	function get_verifikasi_ta_approval_status($id,$status)
	{
		$query = $this->db->query("SELECT * FROM verifikasi_ta_approval WHERE id_ta = $id AND verifikasi_ta_approval.status = '$status'");
		return $query->row();
	}

	function id_seminar_hasil($id)
	{
		$query = $this->db->query("SELECT * FROM seminar_sidang WHERE id_tugas_akhir = $id AND jenis = 'Seminar Hasil' AND seminar_sidang.status = 10");
		return $query->row();
	}

	function get_draft_seminar($id)
	{
		$query = $this->db->query("SELECT * FROM seminar_sidang_berkas WHERE id_seminar = $id AND jenis_berkas = 9");
		return $query->row();
	}

	function get_komponen_meta_attribut_ujian($prodi,$jenis)
	{
		$query = $this->db->query("SELECT seminar_sidang_komponen_meta.* FROM seminar_sidang_komponen, seminar_sidang_komponen_meta WHERE seminar_sidang_komponen.id_prodi = $prodi AND seminar_sidang_komponen.tipe = '$jenis' AND seminar_sidang_komponen.id = seminar_sidang_komponen_meta.id_komponen  AND unsur = 'Ujian'");
		return $query->result();
	}

	function get_komponen_meta_attribut_skripsi($prodi,$jenis)
	{
		$query = $this->db->query("SELECT seminar_sidang_komponen_meta.* FROM seminar_sidang_komponen, seminar_sidang_komponen_meta WHERE seminar_sidang_komponen.id_prodi = $prodi AND seminar_sidang_komponen.tipe = '$jenis' AND seminar_sidang_komponen.id = seminar_sidang_komponen_meta.id_komponen  AND unsur = 'Skripsi'");
		return $query->result();
	}

	function get_seminar_sidang_kompre_id_seminar($id)
	{
		$query = $this->db->query("SELECT * FROM `seminar_sidang_kompre` WHERE id_seminar = $id");
		return $query->row();
	}

	function update_seminar_sidang_kompre_id_seminar($id)
	{
		$this->db->where('id_seminar', $id);
		$this->db->update('seminar_sidang_kompre', array('status' => '1'));
	}
	

}
