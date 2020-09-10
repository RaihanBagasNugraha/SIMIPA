
<div class="app-page-title">
                        <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-file icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <div>Approval Tema Penelitian
                                        <div class="page-title-subheading">Setujui Atau Tolak Pengajuan Tema Penelitian
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <?php
                            // debug
                            //echo "<pre>";
                            //print_r($data_ta);
                            //echo "</pre>";
                            if(!empty($_GET['status']) && $_GET['status'] == 'sukses') {

                                echo '<div class="alert alert-success fade show" role="alert">Biodata Anda sudah diperbarui, jangan lupa untuk memperbarui <a href="javascript:void(0);" class="alert-link">Akun</a> sebelum menggunakan layanan.</div>';
                            }
                        ?>

                        <div class="row">
                        <div class="col-md-12">
                         <div class="main-card mb-3 card">
                                <div class="card-header">Approval Tema Penelitian</div>
                                <div class="card-body">
                                <form method="post" action="<?php echo site_url('dosen/simpan-pengajuan-ta') ?>" >
                                    <input value="<?php echo $ta->id_pengajuan ?>" type = "hidden" required name="id_pengajuan" id="id_pengajuan">
                                    <input value="<?php echo $ta->jenis ?>" type = "hidden" required name="jenis" id="jenis">
                                    <input value="<?php echo $aksi ?>" type = "hidden" required name="aksi" id="aksi">

                                    <input value="" readonly required name="pb2_alter_nip" type="hidden" class="form-control" >
                                    <input value="" readonly required name="pb2_alter_nama" type="hidden" class="form-control" >
                                    <input value="" readonly required name="pb2_alter_email" type="hidden" class="form-control" >
                                    <input value="" readonly required name="pb3_alter_nip" type="hidden" class="form-control" >
                                    <input value="" readonly required name="pb3_alter_nama" type="hidden" class="form-control" >
                                    <input value="" readonly required name="pb3_alter_email" type="hidden" class="form-control" >
                                    <input value="" readonly required name="ps1_alter_nip" type="hidden" class="form-control" >
                                    <input value="" readonly required name="ps1_alter_nama" type="hidden" class="form-control" >
                                    <input value="" readonly required name="ps1_alter_email" type="hidden" class="form-control" >
                                    <input value="" readonly required name="ps2_alter_nip" type="hidden" class="form-control" >
                                    <input value="" readonly required name="ps2_alter_nama" type="hidden" class="form-control" >
                                    <input value="" readonly required name="ps2_alter_email" type="hidden" class="form-control" >
                                    <input value="" readonly required name="ps3_alter_nip" type="hidden" class="form-control" >
                                    <input value="" readonly required name="ps3_alter_nama" type="hidden" class="form-control" >
                                    <input value="" readonly required name="ps3_alter_email" type="hidden" class="form-control" >
                                    

                                    <!-- No Penetapan -->
                                    <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">No Penetapan</label>
                                            <div class="col-sm-1">
                                                <?php 
                                                    $year = date("Y");
                                                    $jurusanid = $this->user_model->get_jurusan($ta->npm);
                                                    $ta_jenis = $ta->jenis;
                                                    if($jurusanid == '0'){
                                                        $no = "/UN26.17.07/$ta_jenis/";
                                                    }
                                                    elseif($jurusanid == '1'){
                                                        $no = "/UN26.17.03/$ta_jenis/";
                                                    }
                                                    elseif($jurusanid == '2'){
                                                        $no = "/UN26.17.02/$ta_jenis/";
                                                    }
                                                    elseif($jurusanid == '3'){
                                                        $no = "/UN26.17.05/$ta_jenis/";
                                                    }
                                                    elseif($jurusanid == '4'){
                                                        $no = "/UN26.17.04/$ta_jenis/";
                                                    }
                                                    elseif($jurusanid == '5'){
                                                        $no = "/UN26.17.06/$ta_jenis/";
                                                    }
                                                    $nomor = $no.$year;
                                                    // $no_penetapan = substr($ta->no_penetapan,0,1);
                                                    $no_penetapan = explode("/",$ta->no_penetapan);
                                                ?>
                                                <input type="text" value="<?php echo $no_penetapan[0]; ?>" required name="no_penetapan" class="form-control" >
                                                <input type="hidden" value="<?php echo $nomor ?>" required name="nomor" id="nomor">
                                            </div>
                                            <?php echo "<h4>$nomor</h4>" ?>
                                    </div>

                                    <!-- NPM -->
                                    <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">Npm</label>
                                            <div class="col-sm-3">
                                                <input value="<?php echo $ta->npm ?>" required name="npm" class="form-control input-mask-trigger" readonly >
                                            </div>
                                    </div>

                                    <!-- NAMA -->
                                    <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">Nama</label>
                                            <div class="col-sm-9">
                                                <input value="<?php echo $this->user_model->get_mahasiswa_name($ta->npm) ?>" required name="nama" class="form-control input-mask-trigger" readonly >
                                            </div>
                                    </div>

                                    <!-- Judul -->
                                    <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-3 col-form-label" ><input class="col-sm-3 col-form-label" checked="checked" type="radio" name="judul" value="1" id="other"> Judul Utama &nbsp;&nbsp;<label class="btn btn-secondary" onClick="edit_judul_1();">Ubah</label></label>
                                            <div class="col-sm-9">
                                                <textarea required name="judul1" class="form-control" readonly placeholder="Judul Utama" id="inputother"><?php echo $ta->judul1 ?></textarea>
                                            </div>
                                    </div>
                                    <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-3 col-form-label"><input class="col-sm-3 col-form-label" type="radio" name="judul" value="2" id="other2">Judul Alternatif &nbsp;&nbsp;<label class="btn btn-secondary" onClick="edit_judul_2();">Ubah</label></label>
                                            <div class="col-sm-9">
                                                <textarea required name="judul2" class="form-control" readonly placeholder="Judul Alternatif" id="inputother2"><?php echo $ta->judul2 == NULL ? "-":$ta->judul2; ?></textarea>
                                            </div>
                                    </div>

                                    <!-- IPK -->
                                    <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">IPK</label>
                                            <div class="col-sm-3">
                                                <input value="<?php echo $ta->ipk ?>" required name="ipk" class="form-control input-mask-trigger" readonly data-inputmask="'mask': '9.99'" im-insert="true">
                                            </div>
                                    </div>

                                    <!-- SKS -->
                                    <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">Jumlah SKS</label>
                                            <div class="col-sm-3">
                                                <input value="<?php echo $ta->ipk ?>" required name="sks" class="form-control input-mask-trigger" readonly data-inputmask="'mask': '999'" im-insert="true">
                                            </div>
                                    </div>

                                     <!-- TOEFL -->
                                    <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">Nilai TOEFL</label>
                                            <div class="col-sm-3">
                                                <input value="<?php echo $ta->toefl ?>" name="toefl" class="form-control input-mask-trigger" readonly data-inputmask="'mask': '999'" im-insert="true">
                                            </div>
                                    </div>

                                    <br>
                                <?php if($ta->jenis != "Tugas Akhir") {?>      
                                    <h5>Komisi Pembimbing</h5>
                                  
                                    <!-- Pembimbing 1 -->
                                    <div class="position-relative row form-group">
                                            <label for="dosen_pb1" class="col-sm-3 col-form-label">Pembimbing Utama</label>
                                            <div class="col-sm-9">
                                                <select required name="pembimbing1" class=" form-control">
                                                <option value="">-- Pilih Dosen Pembimbing Utama --</option>
                                                <?php
                                                $list = $this->user_model->select_list_dosen();
                                                foreach ($list as $row) {
                                                    $nama_dosen = "";
                                                    if($row->gelar_depan != "") $nama_dosen .= $row->gelar_depan." ";
                                                    $nama_dosen .= $row->name;
                                                    if($row->gelar_belakang != "") $nama_dosen .= " ".$row->gelar_belakang;
                                                    $select = "";
                                                    if($ta->pembimbing1 == $row->id_user) $select = "selected";
                                                    
                                                    echo "<option ".$select." value='".$row->id_user."'>".$nama_dosen."</option>";
                                                    }
                                                ?>

                                                </select>
                                            </div>    
                                    </div>

                                     <!-- Pembimbing 2 -->
                                     <div class="position-relative row form-group" >
                                            <label for="dosen_pb2" class="col-sm-3 col-form-label">Pembimbing 2 &nbsp;&nbsp;<label class="btn btn-secondary" onClick="add_pb2();" id="add_pb2"><i class="fas fa-plus"></i></label></label>
                                            <div class="col-sm-9">
                                                <select name="pembimbing2" class=" form-control" id="pb2">
                                                <option value="">-- Pilih Dosen Pembimbing 2 --</option>
                                                <?php
                                                $list = $this->user_model->select_list_dosen();
                                                foreach ($list as $row) {
                                                    $nama_dosen = "";
                                                    if($row->gelar_depan != "") $nama_dosen .= $row->gelar_depan." ";
                                                    $nama_dosen .= $row->name;
                                                    if($row->gelar_belakang != "") $nama_dosen .= " ".$row->gelar_belakang;
                                                    $select = "";
                                                    // if($ta->pembimbing1 == $row->id_user) $select = "selected";
                                                    
                                                    echo "<option ".$select." value='".$row->id_user."'>".$nama_dosen."</option>";
                                                    }
                                                ?>

                                                </select>
                                                <input type="hidden" name="pembimbing2" value="" id="pb2d" disabled = "true"/>
                                            </div>
                                    </div>

                                    <!-- Pembimbing 2 Alternatif -->
                                    <div class="position-relative row form-group" id="pb2_alt_nip" style="display:none">
                                            <label for="dosen_pb2" class="col-sm-3 col-form-label">Nip / Nik</label>
                                            <div class="col-sm-9">
                                                <input value="" name="pb2_alter_nip" class="form-control" placeholder = "NIP Dosen">
                                            </div>
                                    </div>

                                     <!-- Pembimbing 2 Alternatif -->
                                     <div class="position-relative row form-group" id="pb2_alt_nama" style="display:none">
                                            <label for="dosen_pb2" class="col-sm-3 col-form-label">Nama</label>
                                            <div class="col-sm-9">
                                                <input value="" name="pb2_alter_nama" class="form-control" placeholder = "Nama Lengkap dan Gelar">
                                            </div>
                                    </div>

                                     <!-- Pembimbing 2 Alternatif -->
                                     <div class="position-relative row form-group" id="pb2_alt_email" style="display:none">
                                            <label for="dosen_pb2" class="col-sm-3 col-form-label">Email</label>
                                            <div class="col-sm-9">
                                                <input value="" name="pb2_alter_email" class="form-control" type = "email" placeholder = "Email Dosen Untuk Mengirim Permintaan Approval">
                                            </div>
                                    </div>

                                    <!-- Pembimbing 3 -->
                                    <div class="position-relative row form-group">
                                            <label for="dosen_pb3" class="col-sm-3 col-form-label">Pembimbing 3 &nbsp;&nbsp;<label class="btn btn-secondary" onClick="add_pb3();" id="add_pb3"><i class="fas fa-plus"></i></label></label>
                                            <div class="col-sm-9">
                                                <select name="pembimbing3" class=" form-control" id="pb3">
                                                <option value="">-- Pilih Dosen Pembimbing 3 --</option>
                                                <?php
                                                $list = $this->user_model->select_list_dosen();
                                                foreach ($list as $row) {
                                                    $nama_dosen = "";
                                                    if($row->gelar_depan != "") $nama_dosen .= $row->gelar_depan." ";
                                                    $nama_dosen .= $row->name;
                                                    if($row->gelar_belakang != "") $nama_dosen .= " ".$row->gelar_belakang;
                                                    $select = "";
                                                    // if($ta->pembimbing1 == $row->id_user) $select = "selected";
                                                    
                                                    echo "<option ".$select." value='".$row->id_user."'>".$nama_dosen."</option>";
                                                    }
                                                ?>

                                                </select>
                                                <input type="hidden" name="pembimbing3" value="" id="pb3d" disabled = "true"/>
                                            </div>
                                    </div>

                                    <!-- Pembimbing 3 Alternatif -->
                                    <div class="position-relative row form-group" id="pb3_alt_nip" style="display:none">
                                            <label for="dosen_pb2" class="col-sm-3 col-form-label">Nip / Nik</label>
                                            <div class="col-sm-9">
                                                <input value="" name="pb3_alter_nip" class="form-control" placeholder = "NIP Dosen">
                                            </div>
                                    </div>

                                     <!-- Pembimbing 3 Alternatif -->
                                     <div class="position-relative row form-group" id="pb3_alt_nama" style="display:none">
                                            <label for="dosen_pb2" class="col-sm-3 col-form-label">Nama</label>
                                            <div class="col-sm-9">
                                                <input value="" name="pb3_alter_nama" class="form-control" placeholder = "Nama Lengkap dan Gelar">
                                            </div>
                                    </div>

                                    <!-- Pembimbing 3 Alternatif -->
                                    <div class="position-relative row form-group" id="pb3_alt_email" style="display:none">
                                            <label for="dosen_pb2" class="col-sm-3 col-form-label">Email</label>
                                            <div class="col-sm-9">
                                                <input value="" name="pb3_alter_email" class="form-control" type = "email" placeholder = "Email Dosen Untuk Mengirim Permintaan Approval">
                                            </div>
                                    </div>

                                    <br>
                                    <h5>Komisi Pembahas</h5>
                                    
                                    <!-- Pembahas 1 -->
                                    <div class="position-relative row form-group">
                                            <label for="dosen_ps1" class="col-sm-3 col-form-label">Pembahas 1 &nbsp;&nbsp;<label class="btn btn-secondary" onClick="add_ps1();" id="add_ps1"><i class="fas fa-plus"></i></label></label>
                                            <div class="col-sm-9">
                                                <select name="pembahas1" class=" form-control" id="ps1">
                                                <option value="">-- Pilih Dosen Pembahas 1 --</option>
                                                <?php
                                                $list = $this->user_model->select_list_dosen();
                                                foreach ($list as $row) {
                                                    $nama_dosen = "";
                                                    if($row->gelar_depan != "") $nama_dosen .= $row->gelar_depan." ";
                                                    $nama_dosen .= $row->name;
                                                    if($row->gelar_belakang != "") $nama_dosen .= " ".$row->gelar_belakang;
                                                    $select = "";
                                                    // if($ta->pembimbing1 == $row->id_user) $select = "selected";
                                                    
                                                    echo "<option ".$select." value='".$row->id_user."'>".$nama_dosen."</option>";
                                                    }
                                                ?>

                                                </select>
                                                <input type="hidden" name="pembahas1" value="" id="ps1d" disabled = "true"/>
                                            </div>    
                                    </div>

                                    <!-- Pembahas 1 Alternatif -->
                                    <div class="position-relative row form-group" id="ps1_alt_nip" style="display:none">
                                            <label for="dosen_pb2" class="col-sm-3 col-form-label">Nip / Nik</label>
                                            <div class="col-sm-9">
                                                <input value="" name="ps1_alter_nip" class="form-control" placeholder = "NIP Dosen">
                                            </div>
                                    </div>

                                     <!-- Pembahas 1 Alternatif -->
                                     <div class="position-relative row form-group" id="ps1_alt_nama" style="display:none">
                                            <label for="dosen_pb2" class="col-sm-3 col-form-label">Nama</label>
                                            <div class="col-sm-9">
                                                <input value="" name="ps1_alter_nama" class="form-control" placeholder = "Nama Lengkap dan Gelar">
                                            </div>
                                    </div>

                                    <!-- Pembahas 1 Alternatif -->
                                    <div class="position-relative row form-group" id="ps1_alt_email" style="display:none">
                                            <label for="dosen_pb2" class="col-sm-3 col-form-label">Email</label>
                                            <div class="col-sm-9">
                                                <input value="" name="ps1_alter_email" class="form-control" type = "email" placeholder = "Email Dosen Untuk Mengirim Permintaan Approval">
                                            </div>
                                    </div>

                                     <!-- Pembahas 2 -->
                                     <div class="position-relative row form-group">
                                            <label for="dosen_ps2" class="col-sm-3 col-form-label">Pembahas 2 &nbsp;&nbsp;<label class="btn btn-secondary" onClick="add_ps2();" id="add_ps2"><i class="fas fa-plus"></i></label></label>
                                            <div class="col-sm-9">
                                                <select name="pembahas2" class="form-control" id="ps2">
                                                <option value="">-- Pilih Dosen Pembahas 2 --</option>
                                                <?php
                                                $list = $this->user_model->select_list_dosen();
                                                foreach ($list as $row) {
                                                    $nama_dosen = "";
                                                    if($row->gelar_depan != "") $nama_dosen .= $row->gelar_depan." ";
                                                    $nama_dosen .= $row->name;
                                                    if($row->gelar_belakang != "") $nama_dosen .= " ".$row->gelar_belakang;
                                                    $select = "";
                                                    // if($ta->pembimbing1 == $row->id_user) $select = "selected";
                                                    
                                                    echo "<option ".$select." value='".$row->id_user."'>".$nama_dosen."</option>";
                                                    }
                                                ?>

                                                </select>
                                                <input type="hidden" name="pembahas2" value="" id="ps2d" disabled = "true"/>
                                            </div>
                                    </div>

                                     <!-- Pembahas 2 Alternatif -->
                                     <div class="position-relative row form-group" id="ps2_alt_nip" style="display:none">
                                            <label for="dosen_pb2" class="col-sm-3 col-form-label">Nip / Nik</label>
                                            <div class="col-sm-9">
                                                <input value="" name="ps2_alter_nip" class="form-control" placeholder = "NIP Dosen">
                                            </div>
                                    </div>

                                     <!-- Pembahas 2 Alternatif -->
                                     <div class="position-relative row form-group" id="ps2_alt_nama" style="display:none">
                                            <label for="dosen_pb2" class="col-sm-3 col-form-label">Nama</label>
                                            <div class="col-sm-9">
                                                <input value="" name="ps2_alter_nama" class="form-control" placeholder = "Nama Lengkap dan Gelar">
                                            </div>
                                    </div>

                                    <!-- Pembahas 2 Alternatif -->
                                    <div class="position-relative row form-group" id="ps2_alt_email" style="display:none">
                                            <label for="dosen_pb2" class="col-sm-3 col-form-label">Email</label>
                                            <div class="col-sm-9">
                                                <input value="" name="ps2_alter_email" class="form-control" type = "email" placeholder = "Email Dosen Untuk Mengirim Permintaan Approval">
                                            </div>
                                    </div>

                                    <!-- Pembahas 3 -->
                                    <div class="position-relative row form-group">
                                            <label for="dosen_ps3" class="col-sm-3 col-form-label">Pembahas 3 &nbsp;&nbsp;<label class="btn btn-secondary" onClick="add_ps3();" id="add_ps3"><i class="fas fa-plus"></i></label></label>
                                            <div class="col-sm-9">
                                                <select name="pembahas3" class=" form-control" id="ps3">
                                                <option value="">-- Pilih Dosen Pembahas 3 --</option>
                                                <?php
                                                $list = $this->user_model->select_list_dosen();
                                                foreach ($list as $row) {
                                                    $nama_dosen = "";
                                                    if($row->gelar_depan != "") $nama_dosen .= $row->gelar_depan." ";
                                                    $nama_dosen .= $row->name;
                                                    if($row->gelar_belakang != "") $nama_dosen .= " ".$row->gelar_belakang;
                                                    $select = "";
                                                    // if($ta->pembimbing1 == $row->id_user) $select = "selected";
                                                    
                                                    echo "<option ".$select." value='".$row->id_user."'>".$nama_dosen."</option>";
                                                    }
                                                ?>

                                                </select>
                                                <input type="hidden" name="pembahas3" value="" id="ps3d" disabled = "true"/>
                                            </div>
                                    </div>

                                    <!-- Pembahas 3 Alternatif -->
                                    <div class="position-relative row form-group" id="ps3_alt_nip" style="display:none">
                                            <label for="dosen_pb2" class="col-sm-3 col-form-label">Nip / Nik</label>
                                            <div class="col-sm-9">
                                                <input value="" name="ps3_alter_nip" class="form-control" placeholder = "NIP Dosen">
                                            </div>
                                    </div>

                                     <!-- Pembahas 3 Alternatif -->
                                     <div class="position-relative row form-group" id="ps3_alt_nama" style="display:none">
                                            <label for="dosen_pb2" class="col-sm-3 col-form-label">Nama</label>
                                            <div class="col-sm-9">
                                                <input value="" name="ps3_alter_nama" class="form-control" placeholder = "Nama Lengkap dan Gelar">
                                            </div>
                                    </div>

                                    <!-- Pembahas 3 Alternatif -->
                                    <div class="position-relative row form-group" id="ps3_alt_email" style="display:none">
                                            <label for="dosen_pb2" class="col-sm-3 col-form-label">Email</label>
                                            <div class="col-sm-9">
                                                <input value="" name="ps3_alter_email" class="form-control" type = "email" placeholder = "Email Dosen Untuk Mengirim Permintaan Approval">
                                            </div>
                                    </div>

                                    <?php } else { ?>

                                        <h5>Komisi Pembimbing</h5>
                                  
                                        <!-- Pembimbing 1 -->
                                        <div class="position-relative row form-group">
                                                <label for="dosen_pb1" class="col-sm-3 col-form-label">Pembimbing Utama</label>
                                                <div class="col-sm-9">
                                                    <select required name="pembimbing1" class=" form-control">
                                                    <option value="">-- Pilih Dosen Pembimbing Utama --</option>
                                                    <?php
                                                    $list = $this->user_model->select_list_dosen();
                                                    foreach ($list as $row) {
                                                        $nama_dosen = "";
                                                        if($row->gelar_depan != "") $nama_dosen .= $row->gelar_depan." ";
                                                        $nama_dosen .= $row->name;
                                                        if($row->gelar_belakang != "") $nama_dosen .= " ".$row->gelar_belakang;
                                                        $select = "";
                                                        if($ta->pembimbing1 == $row->id_user) $select = "selected";
                                                        
                                                        echo "<option ".$select." value='".$row->id_user."'>".$nama_dosen."</option>";
                                                        }
                                                    ?>

                                                    </select>
                                                </div>    
                                        </div>

                                        <h5>Komisi Pembahas</h5>
                                    
                                    <!-- Pembahas 1 -->
                                    <div class="position-relative row form-group">
                                            <label for="dosen_ps1" class="col-sm-3 col-form-label">Pembahas &nbsp;&nbsp;<label class="btn btn-secondary" onClick="add_ps1();" id="add_ps1"><i class="fas fa-plus"></i></label></label>
                                            <div class="col-sm-9">
                                                <select name="pembahas1" class=" form-control" id="ps1">
                                                <option value="">-- Pilih Dosen Pembahas --</option>
                                                <?php
                                                $list = $this->user_model->select_list_dosen();
                                                foreach ($list as $row) {
                                                    $nama_dosen = "";
                                                    if($row->gelar_depan != "") $nama_dosen .= $row->gelar_depan." ";
                                                    $nama_dosen .= $row->name;
                                                    if($row->gelar_belakang != "") $nama_dosen .= " ".$row->gelar_belakang;
                                                    $select = "";
                                                    // if($ta->pembimbing1 == $row->id_user) $select = "selected";
                                                    
                                                    echo "<option ".$select." value='".$row->id_user."'>".$nama_dosen."</option>";
                                                    }
                                                ?>

                                                </select>
                                                <input type="hidden" name="pembahas1" value="" id="ps1d" disabled = "true"/>
                                            </div>    
                                    </div>

                                    <!-- Pembahas 1 Alternatif -->
                                    <div class="position-relative row form-group" id="ps1_alt_nip" style="display:none">
                                            <label for="dosen_pb2" class="col-sm-3 col-form-label">Nip / Nik</label>
                                            <div class="col-sm-9">
                                                <input value="" name="ps1_alter_nip" class="form-control" placeholder = "NIP Dosen">
                                            </div>
                                    </div>

                                     <!-- Pembahas 1 Alternatif -->
                                     <div class="position-relative row form-group" id="ps1_alt_nama" style="display:none">
                                            <label for="dosen_pb2" class="col-sm-3 col-form-label">Nama</label>
                                            <div class="col-sm-9">
                                                <input value="" name="ps1_alter_nama" class="form-control" placeholder = "Nama Lengkap dan Gelar">
                                            </div>
                                    </div>

                                    <!-- Pembahas 1 Alternatif -->
                                    <div class="position-relative row form-group" id="ps1_alt_email" style="display:none">
                                            <label for="dosen_pb2" class="col-sm-3 col-form-label">Email</label>
                                            <div class="col-sm-9">
                                                <input value="" name="ps1_alter_email" class="form-control" type = "email" placeholder = "Email Dosen Untuk Mengirim Permintaan Approval">
                                            </div>
                                    </div>

                                     <!-- verifikator -->
                                     <div class="position-relative row form-group">
                                            <label for="dosen_ps2" class="col-sm-3 col-form-label">Dosen Verifikasi </label>
                                            <div class="col-sm-9">
                                                <select name="pembahas2" class="form-control" id="ps2">
                                                <option value="">-- Pilih Dosen Verifikasi --</option>
                                                <?php
                                                $list = $this->user_model->select_list_dosen();
                                                foreach ($list as $row) {
                                                    $nama_dosen = "";
                                                    if($row->gelar_depan != "") $nama_dosen .= $row->gelar_depan." ";
                                                    $nama_dosen .= $row->name;
                                                    if($row->gelar_belakang != "") $nama_dosen .= " ".$row->gelar_belakang;
                                                    $select = "";
                                                    // if($ta->pembimbing1 == $row->id_user) $select = "selected";
                                                    
                                                    echo "<option ".$select." value='".$row->id_user."'>".$nama_dosen."</option>";
                                                    }
                                                ?>

                                                </select>
                                                <input type="hidden" name="pembahas2" value="" id="ps2d" disabled = "true"/>
                                            </div>
                                    </div>
                                    <input value="" readonly required name="pembimbing2" type="hidden" class="form-control" >
                                    <input value="" readonly required name="pembimbing3" type="hidden" class="form-control" >
                                    <input value="" readonly required name="pembahas3" type="hidden" class="form-control" >
                                    
                                    <?php } ?>

                                    <!-- TTD -->
                                    <div class="position-relative row form-group"><label for="ttd" class="col-sm-3 col-form-label">Tanda Tangan Digital</label>
                                            <div class="col-sm-4">
                                            <canvas style="border: 1px solid #aaa; height: 200px; background-color: #efefef;" id="signature-pad" class="signature-pad col-sm-12" height="200px"></canvas>
                                            <small class="form-text text-muted">Silahkan tanda tangan pada canvas di atas, jangan lupa Klik <b>Oke</b> sebelum menyimpan data.</small>
                                            </div>
                                            <div class="col-sm-5">
                                            <div role="group" class="btn-group btn-group btn-group-toggle"  style="margin-bottom: 10px;">
                                                    <label class="btn btn-dark">
                                                        <input type="radio" name="pen_color" class="pen_color" value="0" checked>
                                                        Hitam
                                                    </label>
                                                    <label class="btn btn-primary">
                                                        <input type="radio" name="pen_color" class="pen_color" value="1">
                                                        Biru
                                                    </label>
                                                    
                                                </div>
                                                
                                            </a>
                                            <a id="clear" class="mb-2 btn btn-light"  onclick="document.getElementById('output').value = ''">Hapus
                                            </a>
                                            <a id="preview" class="mb-2 btn btn-light">Oke
                                            </a>
                                            <input style="background-color: #efefef;" type="text" class="form-control readonly" required placeholder="Klik Oke setelah tanda tangan di canvas." name="ttd" id="output" value="">
                                            <input type="hidden" name="aksi" value="<?php if(!empty($this->input->get("aksi"))) echo $this->input->get("aksi") ?>">
                                            </div>
                                    
                                        </div>

                                    <div class="position-relative row form-group">
                                            <div class="col-sm-9 offset-sm-3">
                                            <button value="<?php if($this->input->get('aksi') == "ubah") echo "ubah"; ?>" type="submit" class="btn-shadow btn btn-info">
                                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fa fa-save fa-w-20"></i>
                                            </span>
                                            <?php if($this->input->get('aksi') == "ubah") echo "Ubah"; else echo "Simpan" ?> Data
                                        </button>
                                            </div>
                                    </div>
                                
                                </form>



                    

</div>
<script src="<?php echo site_url("assets/scripts/jquery_3.4.1_jquery.min.js") ?>"></script>
<script src="<?php echo site_url("assets/scripts/select2.full.js") ?>"></script>
<script type="text/javascript">
$(document).ready(function(){
    $(".readonly").on('keydown paste', function(e){
        e.preventDefault();
        $(this).blur();
    });
    
    $(".readonly").mousedown(function(e){
        e.preventDefault();
        $(this).blur();
        return false;
        });

    $("select").select2({
        theme: "bootstrap"
    });
    $.ajaxSetup({
        type:"POST",
        url: "<?php echo site_url('mahasiswa/ambil_data') ?>",
        cache: false,
    });

    $("#provinsi").change(function(){
        var value=$(this).val();
        
        if(value>0){
            $.ajax({
                data:{modul:'kabupaten',id:value},
                success: function(respond){
 
                    $("#kota-kabupaten").html(respond);
                }
            })
        }

    });

    $("#kota-kabupaten").change(function(){
        var value=$(this).val();
        
        if(value>0){
            $.ajax({
                data:{modul:'kecamatan',id:value},
                success: function(respond){
                    
                    $("#kecamatan").html(respond);
                }
            })
        }

    });

    $("#kecamatan").change(function(){
        var value=$(this).val();
        
        if(value>0){
            $.ajax({
                data:{modul:'kelurahan',id:value},
                success: function(respond){
                    
                    $("#kelurahan-desa").html(respond);
                }
            })
        }

    });
});

</script>

<script type="text/javascript">
    function edit_judul_1() {
         if( document.getElementById("inputother").readOnly = true){
           document.getElementById("inputother").readOnly = false;
         }else{
            document.getElementById("inputother").readOnly = true;
         }
    }

    function edit_judul_2() {
         if( document.getElementById("inputother2").readOnly = true){
           document.getElementById("inputother2").readOnly = false;
         }else{
            document.getElementById("inputother2").readOnly = true;
         }
    }


    // function changeradioother() {
    //       var other = document.getElementById("other");
    //       other.value = document.getElementById("inputother").value;
    // }
        
    // function changeradioother2() {
    //       var other = document.getElementById("other2");
    //       other.value = document.getElementById("inputother2").value;
    // }
</script>

<script src="<?php echo site_url("assets/scripts/signature_pad.js") ?>"></script>
<script>
var canvas = document.getElementById('signature-pad');

var signaturePad = new SignaturePad(canvas);

<?php if($this->input->get('aksi') == 'ubah' && !empty($this->input->get('id'))) { 
    
    $ttd_img = json_encode($data_ta['ttd']);
    
    ?>


<?php } ?>

$('#preview').click(function(){
  var data = signaturePad.toDataURL('image/png');
  $('#output').val(data);

 });

 $(".pen_color").change(function(){
    var radioValue = $("input[name='pen_color']:checked").val();
    if(radioValue == 1){
        signaturePad.penColor = 'rgb(0, 0, 255)'
    } else {
        signaturePad.penColor = 'rgb(0, 0, 0)'
    }
});

 

document.getElementById('clear').addEventListener('click', function () {
  signaturePad.clear();
});

document.getElementById('undo').addEventListener('click', function () {
	var data = signaturePad.toData();
  if (data) {
    data.pop(); // remove the last dot or line
    signaturePad.fromData(data);
  }
});

</script>

<script>

//pb2
function add_pb2() {
    if( document.getElementById("pb2_alt_nama").style.display=='none' && document.getElementById("pb2_alt_nip").style.display=='none'){
           document.getElementById("pb2_alt_nama").style.display = '';
           document.getElementById("pb2_alt_nip").style.display = '';
           document.getElementById("pb2_alt_email").style.display = '';
           document.getElementById("pb2").disabled = true;
           document.getElementById("pb2d").disabled = false;
        //    document.getElementById("pb2").value = "My value";
    }else{
            document.getElementById("pb2_alt_nama").style.display = 'none';
            document.getElementById("pb2_alt_nip").style.display = 'none';
            document.getElementById("pb2_alt_email").style.display = 'none';
            document.getElementById("pb2").disabled = false;
            document.getElementById("pb2d").disabled = true;
         }
    }

$(document).ready(function(){
    $('#add_pb2').click(function(){
    $('#pb2').prop('selectedIndex',0);
    })
});

//pb3
function add_pb3() {
    if( document.getElementById("pb3_alt_nama").style.display=='none' && document.getElementById("pb3_alt_nip").style.display=='none'){
           document.getElementById("pb3_alt_nama").style.display = '';
           document.getElementById("pb3_alt_nip").style.display = '';
           document.getElementById("pb3_alt_email").style.display = '';
           document.getElementById("pb3").disabled = true;
           document.getElementById("pb3d").disabled = false;
    }else{
            document.getElementById("pb3_alt_nama").style.display = 'none';
            document.getElementById("pb3_alt_nip").style.display = 'none';
            document.getElementById("pb3_alt_email").style.display = 'none';
            document.getElementById("pb3").disabled = false;
            document.getElementById("pb3d").disabled = true;
         }
    }

$(document).ready(function(){
    $('#add_pb3').click(function(){
    $('#pb3').prop('selectedIndex',0);
    })
});

//ps1
function add_ps1() {
    if( document.getElementById("ps1_alt_nama").style.display=='none' && document.getElementById("ps1_alt_nip").style.display=='none'){
           document.getElementById("ps1_alt_nama").style.display = '';
           document.getElementById("ps1_alt_nip").style.display = '';
           document.getElementById("ps1_alt_email").style.display = '';
           document.getElementById("ps1").disabled = true;
           document.getElementById("ps1d").disabled = false;
    }else{
            document.getElementById("ps1_alt_nama").style.display = 'none';
            document.getElementById("ps1_alt_nip").style.display = 'none';
            document.getElementById("ps1_alt_email").style.display = 'none';
            document.getElementById("ps1").disabled = false;
            document.getElementById("ps1d").disabled = true;
         }
    }

$(document).ready(function(){
    $('#add_ps1').click(function(){
    $('#ps1').prop('selectedIndex',0);
    })
});

//ps2
function add_ps2() {
    if( document.getElementById("ps2_alt_nama").style.display=='none' && document.getElementById("ps2_alt_nip").style.display=='none'){
           document.getElementById("ps2_alt_nama").style.display = '';
           document.getElementById("ps2_alt_nip").style.display = '';
           document.getElementById("ps2_alt_email").style.display = '';
           document.getElementById("ps2").disabled = true;
           document.getElementById("ps2d").disabled = false;
    }else{
            document.getElementById("ps2_alt_nama").style.display = 'none';
            document.getElementById("ps2_alt_nip").style.display = 'none';
            document.getElementById("ps2_alt_email").style.display = 'none';
            document.getElementById("ps2").disabled = false;
            document.getElementById("ps2d").disabled = true;
         }
    }

$(document).ready(function(){
    $('#add_ps2').click(function(){
    $('#ps2').prop('selectedIndex',0);
    })
});

//ps3
function add_ps3() {
    if( document.getElementById("ps3_alt_nama").style.display=='none' && document.getElementById("ps3_alt_nip").style.display=='none'){
           document.getElementById("ps3_alt_nama").style.display = '';
           document.getElementById("ps3_alt_nip").style.display = '';
           document.getElementById("ps3_alt_email").style.display = '';
           document.getElementById("ps3").disabled = true;
           document.getElementById("ps3d").disabled = false;
    }else{
            document.getElementById("ps3_alt_nama").style.display = 'none';
            document.getElementById("ps3_alt_nip").style.display = 'none';
            document.getElementById("ps3_alt_email").style.display = '';
            document.getElementById("ps3").disabled = false;
            document.getElementById("ps3d").disabled = true;
         }
    }

$(document).ready(function(){
    $('#add_ps3').click(function(){
    $('#ps3').prop('selectedIndex',0);
    })
});

</script>