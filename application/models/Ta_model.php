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

	function ajukan_ta_perbaikan($where)
	{
		$this->db->where('id_pengajuan', $where);
	    $this->db->update($this->table, array('status' => '2'));
	}
	
	function get_approval_ta($id)
	{
		$this->db->where(array('pembimbing1' => $id));
		$this->db->where('status =', '0');
		$query = $this->db->get($this->table);
		return $query->result();
	}

	function get_approval_ta_by_pa($id)
	{
		$this->db->select('*'); 
		$this->db->from('tugas_akhir');
		$this->db->join('tbl_users_mahasiswa', 'tbl_users_mahasiswa.npm = tugas_akhir.npm');
		$this->db->where('status =', '1');
		$this->db->where('tbl_users_mahasiswa.dosen_pa',$id);

		$query = $this->db->get();
		return $query->result();
	}

	function get_approval_ta_koordinator($id)
	{
		$query = $this->db->query('SELECT * FROM tugas_akhir JOIN tbl_users_mahasiswa, tbl_users_dosen WHERE tbl_users_dosen.id_user ='.$id.' AND tbl_users_mahasiswa.jurusan = tbl_users_dosen.jurusan AND tugas_akhir.status = 3 AND tugas_akhir.npm = tbl_users_mahasiswa.npm');
	
		return $query->result();
	}

	function approve_ta($where,$ttd,$status,$dosenid)
	{
		$result = $this->db->query('SELECT pembimbing1 FROM tugas_akhir WHERE id_pengajuan ='.$where)->row()->pembimbing1;
		$nip = $this->db->query('SELECT nip_nik FROM tbl_users_dosen WHERE id_user ='.$dosenid)->row()->nip_nik;


		if($status == 'pb1'){
			$this->db->where('id_pengajuan', $where);
			$this->db->update($this->table, array('status' => '1'));

			$data_approval = [
				'id_pengajuan' => $where,
				'status_slug'  => 'Pembimbing Utama',
				'id_user'  => $dosenid,
				'ttd'  => $ttd
			];

			$this->db->insert('tugas_akhir_approval', $data_approval);

			$data_komisi = [
				'id_tugas_akhir' => $where,
				'status'  => 'Pembimbing 1',
				'nip_nik'  => $nip,
			];

			$this->db->insert('tugas_akhir_komisi', $data_komisi);

		}
		elseif($status == 'pa'){
			$this->db->where('id_pengajuan', $where);
			$this->db->update($this->table, array('status' => '2'));

			$data_approval = [
				'id_pengajuan' => $where,
				'status_slug'  => 'Pembimbing Akademik',
				'id_user'  => $dosenid,
				'ttd'  => $ttd
			];

			$this->db->insert('tugas_akhir_approval', $data_approval);

			// $data_komisi = [
			// 	'id_tugas_akhir' => $where,
			// 	'status'  => 'Pembimbing Akademik',
			// 	'nip_nik'  => $nip,
			// ];

			// $this->db->insert('tugas_akhir_komisi', $data_komisi);
		}
		
	}

	function decline_ta($where,$dosenid,$status,$keterangan)
	{
		$result = $this->db->query('SELECT pembimbing1 FROM tugas_akhir WHERE id_pengajuan ='.$where)->row()->pembimbing1;
		$ttd = $this->db->query('SELECT ttd FROM tbl_users WHERE userId ='.$dosenid)->row()->ttd;
		$nip = $this->db->query('SELECT nip_nik FROM tbl_users_dosen WHERE id_user ='.$dosenid)->row()->nip_nik;


		if($status == 'pb1'){
			$this->db->where('id_pengajuan', $where);
			$this->db->update($this->table, array('status' => '6','keterangan_tolak' => $keterangan));

		}
		elseif($status == 'pa'){
			$this->db->where('id_pengajuan', $where);
			$this->db->update($this->table, array('status' => '6','keterangan_tolak' => $keterangan));

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

	function approve_berkas_ta($where,$dosenid,$ttd,$no_penetapan) //tendik
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
		$this->db->update($this->table, array('judul1' => $judul1,'judul2' => $judul2,'status' => '4','judul_approve' => $judul_approve,'no_penetapan' => $no_penetapan,'keterangan_tolak' => NULL));

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
		
		$this->db->where('id_tugas_akhir', $id);
		$this->db->where('status', 'Pembimbing 1');
		$this->db->update('tugas_akhir_komisi', array('nip_nik' => $nip_pb1));

		//pb2
		if($pb2 != NULL){
			$nip_pb2 = $this->db->query('SELECT nip_nik FROM tbl_users_dosen WHERE id_user ='.$pb2)->row()->nip_nik;
		
			$data_komisi = [
				'id_tugas_akhir' => $id,
				'status'  => 'Pembimbing 2',
				'nip_nik'  => $nip_pb2,
			];

			$this->db->insert('tugas_akhir_komisi', $data_komisi);
		}

		//pb3
		if($pb3 != NULL){
			$nip_pb3 = $this->db->query('SELECT nip_nik FROM tbl_users_dosen WHERE id_user ='.$pb3)->row()->nip_nik;
		
			$data_komisi = [
				'id_tugas_akhir' => $id,
				'status'  => 'Pembimbing 3',
				'nip_nik'  => $nip_pb3,
			];

			$this->db->insert('tugas_akhir_komisi', $data_komisi);
		}

		//ps1
		if($ps1 != NULL){
			$nip_ps1 = $this->db->query('SELECT nip_nik FROM tbl_users_dosen WHERE id_user ='.$ps1)->row()->nip_nik;
				
			$data_komisi = [
				'id_tugas_akhir' => $id,
				'status'  => 'Penguji 1',
				'nip_nik'  => $nip_ps1,
			];
		
			$this->db->insert('tugas_akhir_komisi', $data_komisi);
		}

		//ps2
		if($ps2 != NULL){
			$nip_ps2 = $this->db->query('SELECT nip_nik FROM tbl_users_dosen WHERE id_user ='.$ps2)->row()->nip_nik;
				
			$data_komisi = [
				'id_tugas_akhir' => $id,
				'status'  => 'Penguji 2',
				'nip_nik'  => $nip_ps2,
			];
		
			$this->db->insert('tugas_akhir_komisi', $data_komisi);
		}

		//ps3
		if($ps3 != NULL){
			$nip_ps3 = $this->db->query('SELECT nip_nik FROM tbl_users_dosen WHERE id_user ='.$ps3)->row()->nip_nik;
						
			$data_komisi = [
				'id_tugas_akhir' => $id,
				'status'  => 'Penguji 3',
				'nip_nik'  => $nip_ps3,
			];
				
			$this->db->insert('tugas_akhir_komisi', $data_komisi);
		}
	}

	// get komisi
	function get_pembimbing_ta($id)
	{
		$query = $this->db->query('SELECT * FROM tugas_akhir_komisi, tbl_users_dosen, tbl_users WHERE tugas_akhir_komisi.id_tugas_akhir ='.$id.' AND tbl_users_dosen.nip_nik = tugas_akhir_komisi.nip_nik AND tbl_users.userId = tbl_users_dosen.id_user AND tugas_akhir_komisi.status LIKE "Pembimbing%"');
		return $query->result();
	} 

	function get_penguji_ta($id)
	{
		$query = $this->db->query('SELECT * FROM tugas_akhir_komisi, tbl_users_dosen, tbl_users WHERE tugas_akhir_komisi.id_tugas_akhir ='.$id.' AND tbl_users_dosen.nip_nik = tugas_akhir_komisi.nip_nik AND tbl_users.userId = tbl_users_dosen.id_user AND tugas_akhir_komisi.status LIKE "Penguji%"');
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

	function insert_approval_seminar($data)
	{
		$this->db->insert('seminar_sidang_approval', $data);
	}

	// seminar dosen
	function get_approval_seminar_by_pa($id)
	{
		$query = $this->db->query('SELECT seminar_sidang.*,tugas_akhir.npm,tugas_akhir.judul1,tugas_akhir.judul2,tugas_akhir.judul_approve FROM seminar_sidang, tbl_users_mahasiswa, tugas_akhir WHERE seminar_sidang.id_tugas_akhir = tugas_akhir.id_pengajuan AND tbl_users_mahasiswa.npm = tugas_akhir.npm AND seminar_sidang.status = "0" AND tbl_users_mahasiswa.dosen_pa ='.$id);
		return $query->result();
	}

	function get_approval_seminar_list($id)
	{
		$query = $this->db->query('SELECT seminar_sidang.*, tugas_akhir.*, seminar_sidang_approval.status_slug FROM seminar_sidang, tugas_akhir, seminar_sidang_approval WHERE seminar_sidang.id_tugas_akhir = tugas_akhir.id_pengajuan AND seminar_sidang.id = seminar_sidang_approval.id_pengajuan AND seminar_sidang_approval.ttd LIKE "" AND seminar_sidang.status = "1" AND seminar_sidang_approval.id_user ='.$id);
		return $query->result();
	}
	
	function decline_seminar($where,$dosenid,$status,$keterangan)
	{
		
		if($status == 'pb1'){
			$this->db->where('id_pengajuan', $where);
			$this->db->update($this->table, array('status' => '6','keterangan' => $keterangan));

		}
		elseif($status == 'pa'){
			$this->db->where('id', $where);
			$this->db->update($this->table_seminar, array('status' => '6','keterangan_tolak' => $keterangan));

		}
		elseif($status == 'koor'){
			$this->db->where('id_pengajuan', $where);
			$this->db->update($this->table, array('status' => '6','no_penetapan' => NULL,'keterangan' => $keterangan));

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
		$result = $this->db->query('SELECT * FROM seminar_sidang_approval WHERE id_pengajuan ='.$where.' AND ttd LIKE ""');
		$checks = $result->result();
		$check = count($checks);

		if($status == 'pb1'){
			$this->db->where('id_pengajuan', $where);
			$this->db->where('status_slug', 'Pembimbing Utama');
			$this->db->where('id_user', $dosenid);
			$this->db->update('seminar_sidang_approval', array('ttd' => $ttd));

			if($check == 1){
				$this->db->where('id', $where);
				$this->db->update('seminar_sidang', array('status' => '2'));
			}
		}
		elseif($status == 'pa'){
			$this->db->where('id', $where);
			$this->db->update($this->table_seminar, array('status' => '1'));

			$data_approval = [
				'id_pengajuan' => $where,
				'status_slug'  => 'Pembimbing Akademik',
				'id_user'  => $dosenid,
				'ttd'  => $ttd
			];

			$this->db->insert('seminar_sidang_approval', $data_approval);
		}
		elseif($status == 'pb2'){
			$this->db->where('id_pengajuan', $where);
			$this->db->where('status_slug', 'Pembimbing 2');
			$this->db->where('id_user', $dosenid);
			$this->db->update('seminar_sidang_approval', array('ttd' => $ttd));

			if($check == 1){
				$this->db->where('id', $where);
				$this->db->update('seminar_sidang', array('status' => '2'));
			}
		}
		elseif($status == 'pb3'){
			$this->db->where('id_pengajuan', $where);
			$this->db->where('status_slug', 'Pembimbing 3');
			$this->db->where('id_user', $dosenid);
			$this->db->update('seminar_sidang_approval', array('ttd' => $ttd));

			if($check == 1){
				$this->db->where('id', $where);
				$this->db->update('seminar_sidang', array('status' => '2'));
			}
		}
		elseif($status == 'ps1'){
			$this->db->where('id_pengajuan', $where);
			$this->db->where('status_slug', 'Penguji 1');
			$this->db->where('id_user', $dosenid);
			$this->db->update('seminar_sidang_approval', array('ttd' => $ttd));

			if($check == 1){
				$this->db->where('id', $where);
				$this->db->update('seminar_sidang', array('status' => '2'));
			}
		}
		elseif($status == 'ps2'){
			$this->db->where('id_pengajuan', $where);
			$this->db->where('status_slug', 'Penguji 2');
			$this->db->where('id_user', $dosenid);
			$this->db->update('seminar_sidang_approval', array('ttd' => $ttd));

			if($check == 1){
				$this->db->where('id', $where);
				$this->db->update('seminar_sidang', array('status' => '2'));
			}
		}
		elseif($status == 'ps3'){
			$this->db->where('id_pengajuan', $where);
			$this->db->where('status_slug', 'Penguji 3');
			$this->db->where('id_user', $dosenid);
			$this->db->update('seminar_sidang_approval', array('ttd' => $ttd));

			if($check == 1){
				$this->db->where('id', $where);
				$this->db->update('seminar_sidang', array('status' => '2'));
			}
		}
		
	}

	function get_verifikasi_berkas_seminar($id) //tendik
	{
		$query = $this->db->query('SELECT seminar_sidang.*, tugas_akhir.judul1, tugas_akhir.judul2, tugas_akhir.judul_approve, tugas_akhir.no_penetapan, tugas_akhir.npm FROM seminar_sidang, tugas_akhir, tbl_users_mahasiswa, tbl_users_tendik WHERE tbl_users_tendik.id_user ='.$id.' AND tbl_users_tendik.unit_kerja = tbl_users_mahasiswa.jurusan AND tugas_akhir.npm = tbl_users_mahasiswa.npm AND tugas_akhir.status = 4 AND seminar_sidang.id_tugas_akhir = tugas_akhir.id_pengajuan AND seminar_sidang.status = 2');
		
		return $query->result();

	}

	function decline_berkas_seminar($where,$dosenid,$keterangan) //tendik
	{
		$this->db->where('id', $where);
		$this->db->update($this->table_seminar, array('status' => '5','keterangan_tolak' => $keterangan));
	}

}
