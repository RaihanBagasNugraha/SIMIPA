

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-id icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <div>Kelola Biodata
                                        <div class="page-title-subheading">Silakan <span class="text-primary"><b>lengkapi biodata </b> dan <a href="javascript:void(0);" class="alert-link">Akun</a></span> Anda sebelum menggunakan layanan.
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div> <!-- app-page-title -->
                        <?php
                        // debug
                        //echo "<pre>";
                        //print_r($biodata);
                        //echo "</pre>";
                        if(!empty($_GET['status']) && $_GET['status'] == 'sukses') {

                            echo '<div class="alert alert-success fade show" role="alert">Biodata Anda sudah diperbarui, jangan lupa untuk memperbarui <a href="javascript:void(0);" class="alert-link">Akun</a> sebelum menggunakan layanan.</div>';
                        }
                        if(!empty($_GET['status']) && $_GET['status'] == 'duplikat') {

                            echo '<div class="alert alert-danger fade show" role="alert">Terdapat Tugas Tambahan Yang Sama Dengan Status Yang Aktif</div>';
                        }
                        $id_user = $this->session->userdata('userId');
                        ?>
                        
                         <div class="main-card mb-3 card">
                                <div class="card-header">Form Kelola Biodata</div>
                                <div class="card-body">
                                    <form method="post" action="<?php echo site_url('mahasiswa/ubah-data-biodata') ?>" >
                                
                                        <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-2 col-form-label">Program Studi</label>
                                            <div class="col-sm-10">
                                            <select required name="prodi" class="input-lg form-control">
                                            <option>-- Pilih Program Studi --</option>
                                            <?php
                                            $list_prodi = $this->jurusan_model->select_prodi_by_jur($biodata->jurusan);
                                            foreach ($list_prodi as $row) {
                                                if($biodata->prodi == $row->id_prodi) $select = "selected";
                                                else $select = "";
                                                echo "<option ".$select." value='".$row->id_prodi."'>".$row->nama."</option>";
                                            }
                                            ?>
                                            
                                        
                                            </select>
                                            
                                            
                                        </div>
                                        </div>
                                        <div class="position-relative row form-group">
                                            <label for="dosen_pa" class="col-sm-2 col-form-label">Dosen PA</label>
                                            <div class="col-sm-10">
                                                <select name="dosen_pa" class=" form-control" required>
                                                <option>-- Pilih Dosen Pembimbing Akademik --</option>
                                                <?php
                                                $list = $this->user_model->select_list_dosen();
                                                foreach ($list as $row) {
                                                    $nama_dosen = "";
                                                    if($row->gelar_depan != "") $nama_dosen .= $row->gelar_depan." ";
                                                    $nama_dosen .= $row->name;
                                                    if($row->gelar_belakang != "") $nama_dosen .= " ".$row->gelar_belakang;
                                                    if($biodata->dosen_pa == $row->id_user) $select = "selected";
                                                    else $select = "";
                                                    echo "<option ".$select." value='".$row->id_user."'>".$nama_dosen."</option>";
                                                    }
                                                ?>

                                                </select>
                                                
                                            
                                            </div>
                                        </div>
                                        <div class="position-relative row form-group">
                                            <label for="jalur_masuk" class="col-sm-2 col-form-label">Jalur Masuk</label>
                                            <div class="col-sm-10">
                                                <select name="jalur_masuk" class=" form-control">
                                                <option>-- Pilih Jalur Masuk --</option>
                                                <?php
                                                $list = $this->parameter_model->select_jalur_masuk();
                                                foreach ($list as $row) {
                                                    if($biodata->jalur_masuk == $row->id_jalur_masuk) $select = "selected";
                                                    else $select = "";
                                                    echo "<option ".$select." value='".$row->id_jalur_masuk."'>".$row->nama."</option>";
                                                    }
                                                ?>

                                                </select>
                                                
                                            
                                            </div>
                                        </div>
                                        <div class="position-relative row form-group">
                                            <label for="asal_sekolah" class="col-sm-2 col-form-label">Asal Sekolah</label>
                                            <div class="col-sm-10">
                                                <select name="asal_sekolah" class=" form-control">
                                                <option>-- Pilih Asal Sekolah --</option>
                                                <?php
                                                $list = $this->parameter_model->select_asal_sekolah();
                                                foreach ($list as $row) {
                                                    if($biodata->asal_sekolah == $row->id_asal_sekolah) $select = "selected";
                                                    else $select = "";
                                                    echo "<option ".$select." value='".$row->id_asal_sekolah."'>".$row->nama."</option>";
                                                    }
                                                ?>

                                                </select>
                                                
                                            
                                            </div>
                                        </div>
                                        <div class="position-relative row form-group"><label for="nama_sekolah" class="col-sm-2 col-form-label">Nama Sekolah</label>
                                            <div class="col-sm-10"><input name="nama_sekolah" id="nama_sekolah" value="<?php echo $biodata->nama_sekolah ?>" type="text" placeholder="Contoh: SMAN 1 Metro" class="form-control"></div>
                                        </div>
                                        <div class="divider"></div>
                                        <div class="position-relative row form-group">
                                            

                                            <label for="jenkel" class="col-sm-2 col-form-label">Jenis Kelamin</label>
                                            <div class="col-sm-10">
                                            <select name="jenkel" class=" form-control">
                                            <option>-- Pilih Jenis Kelamin --</option>
                                            <option value="Laki-laki" <?php if($biodata->jenis_kelamin == "Laki-laki") echo "selected" ?>>Laki-laki</option>
                                            <option value="Perempuan" <?php if($biodata->jenis_kelamin == "Perempuan") echo "selected" ?>>Perempuan</option>
                                            </select>
                                            </div>
                                        </div>
                                        <div class="position-relative row form-group">
                                            <label for="agama" class="col-sm-2 col-form-label">Agama</label>
                                            <div class="col-sm-10">
                                                <select name="agama" class=" form-control">
                                                <option>-- Pilih Agama --</option>
                                                <?php
                                                $list = $this->parameter_model->select_agama();
                                                foreach ($list as $row) {
                                                    if($biodata->agama == $row->id) $select = "selected";
                                                    else $select = "";
                                                    echo "<option ".$select." value='".$row->id."'>".$row->agama."</option>";
                                                    }
                                                ?>

                                                </select>
                                                
                                            
                                            </div>
                                        </div>
                                        <div class="position-relative row form-group">
                                            <label for="tempat_lahir" class="col-sm-2 col-form-label">Tempat Lahir</label>
                                            <div class="col-sm-4"><input name="tempat_lahir" required id="tempat_lahir" value="<?php echo $biodata->tempat_lahir ?>" type="text" placeholder="Contoh: Bandar Lampung" class="form-control"></div>
                                            <div class="col-sm-1"></div>
                                            <label for="tanggal_lahir" class="col-sm-2 col-form-label">Tanggal Lahir</label>
                                            <div class="col-sm-3"><input name="tanggal_lahir" required id="tanggal_lahir" value="<?php echo date_format(date_create($biodata->tanggal_lahir), "d-m-Y") ?>" type="text" data-inputmask-alias="datetime" placeholder="dd-mm-yyyy" data-inputmask-inputformat="dd-mm-yyyy" im-insert="false" class="form-control input-mask-trigger"></div>
                                        
                                        </div>
                                        <div class="position-relative row form-group"><label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                                            <div class="col-sm-10">
                                            <textarea name="jalan" id="jalan" class="form-control" placeholder="Contoh: Jl. Soekarno Hatta Gg. Bypass Raya No. 103 RT 01 RW 04"><?php echo $biodata->jalan ?></textarea>
                                            </div>
                                            
                                        </div>
                                        <div class="position-relative row form-group"><label for="alamat" class="col-sm-2 col-form-label"></label>
                                            <div class="col-sm-10">
                                                <select name="provinsi" id="provinsi" class="form-control">
                                                <option>-- Pilih Provinsi --</option>
                                                <?php
                                                $list = $this->wilayah_model->provinsi();
                                                foreach ($list as $row) {
                                                    if($biodata->provinsi == $row->id) $select = "selected";
                                                    else $select = "";
                                                    echo "<option ".$select." value='".$row->id."'>".$row->nama."</option>";
                                                    }
                                                ?>
                                                </select>
                                            </div>
                                            
                                        </div>
                                        <div class="position-relative row form-group"><label for="alamat" class="col-sm-2 col-form-label"></label>
                                            
                                            <div class="col-sm-10">
                                                <select name="kota_kabupaten" id="kota-kabupaten" class=" form-control">
                                                <option>-- Pilih Kota/Kabupaten --</option>
                                                <?php
                                                if(!empty($biodata->kota_kabupaten)) {
                                                    echo $this->wilayah_model->kabupaten_cek($biodata->provinsi, $biodata->kota_kabupaten);
                                                }
                                                ?>
                                                
                                                </select>
                                            </div>
                                            
                                            
                                        </div>
                                        <div class="position-relative row form-group"><label for="alamat" class="col-sm-2 col-form-label"></label>
                                            
                                            <div class="col-sm-10">
                                                <select name="kecamatan" id="kecamatan" class=" form-control">
                                                <option>-- Pilih Kecamatan --</option>
                                                <?php
                                                if(!empty($biodata->kecamatan)) {
                                                    echo $this->wilayah_model->kecamatan_cek($biodata->kota_kabupaten, $biodata->kecamatan);
                                                }
                                                ?>
                                                </select>
                                            </div>
                                            
                                        </div>
                                        <div class="position-relative row form-group"><label for="alamat" class="col-sm-2 col-form-label"></label>
                                            
                                            <div class="col-sm-10">
                                                <select name="kelurahan_desa" id="kelurahan-desa" class=" form-control">
                                                <option>-- Pilih Kelurahan/Desa --</option>
                                                <?php
                                                if(!empty($biodata->kelurahan_desa)) {
                                                    echo $this->wilayah_model->desa_cek($biodata->kecamatan, $biodata->kelurahan_desa);
                                                }
                                                ?>
                                                </select>
                                            </div>
                                            
                                        </div>
                                        <div class="position-relative row form-group"><label for="alamat" class="col-sm-2 col-form-label"></label>
                                            
                                            <div class="col-sm-3">
                                            <input name="kode_pos" id="kode_pos" value="<?php echo $biodata->kode_pos ?>" type="text" placeholder="Contoh: 35144" data-inputmask="'mask': '99999'" im-insert="true" class="form-control input-mask-trigger">
                                            </div>
                                            
                                            
                                        </div>

                                        <div class="divider"></div>
                                        
                                        <div class="position-relative row form-group">
                                        <label for="tgs_tmbh" class="col-sm-6 col-form-label"><b>Lembaga Kemahasiswaaan Aktif</b> &emsp;
                                            <a data-toggle = "modal" id="<?php echo $id_user; ?>"  class="passingIDlk" >
                                                <button type="button" class="btn-wide mb-1 btn btn-success btn-sm "  data-toggle="modal" data-target="#tambahlk">
                                                    Tambah
                                                </button>
                                            </a>
                                        </label>
                                        </div>

                                        <?php $lembaga_mhs = $this->user_model->get_lk_id($id_user); ?>
                                       
                                       <table class="mb-0 table table-striped">
                                       <?php  foreach($lembaga_mhs as $lk){ ?>
                                               <tr>
                                                   <td>Lembaga Kemahasiswaan</td>
                                                   <td>:</td>
                                                   <td><?php echo $this->user_model->get_lk_detail($lk->id_lk)->nama_lk ?></td>
                                               </tr>   

                                               <tr>
                                                   <td>Jabatan</td>
                                                   <td>:</td>
                                                   <?php 
                                                        $jab = $lk->jabatan;
                                                        switch($jab)
                                                        {
                                                            case "1":
                                                            $jabatan = "Ketua Umum";
                                                            break;
                                                            case "2":
                                                            $jabatan = "Wakil Ketua Umum";
                                                            break;
                                                            case "3":
                                                            $jabatan = "Sekretaris Umum";
                                                            break;
                                                            case "4":
                                                            $jabatan = "Bendahara Umum";
                                                            break;
                                                            case "5":
                                                            $jabatan = "Anggota";
                                                            break;
                                                        }
                                                   ?>
                                                   <td><?php echo $jabatan; ?></td>
                                               </tr>
                                             
                                               <tr>
                                                   <td>Periode</td>
                                                   <td>:</td>
                                                   <td><?php echo $lk->periode ?></td>
                                               </tr>  
                                               <tr >
                                                   <td>Status</td>
                                                   <td>:</td>
                                                   <td><b><?php echo $lk->aktif == 1 ? "Aktif" : "Nonaktif" ?></b></td>
                                               </tr>  
                                               <tr>
                                                   <td></td>
                                                   <td></td>
                                                   
                                                   <td style="align:left;">
                                                       <?php if($lk->aktif != 0) { ?>
                                                           <a data-toggle = "modal" data-id="<?php echo $lk->id ?>" ket="nonaktif" class="passingIDhapus" >
                                                                   <button type="button" class="btn-small mb-1 btn btn-warning btn-lg"  data-toggle="modal" data-target="#nonaktiflk">
                                                                       Nonaktif
                                                                   </button>
                                                           </a>
                                                       <?php } ?>
                                                       <a data-toggle = "modal" data-id="<?php echo $lk->id ?>" ket="hapus" class="passingIDhapus" >
                                                               <button type="button" class="btn-small mb-1 btn btn-danger btn-lg"  data-toggle="modal" data-target="#nonaktiflk">
                                                                   Hapus
                                                               </button>
                                                       </a>
                                                   </td>
                                               </tr>   
                                               <?php } ?>
                                       </table>
                                  
                                        
                                        <div class="position-relative row form-group">
                                            <div class="col-sm-10 offset-sm-2">
                                            <button type="submit" class="btn-shadow btn btn-info">
                                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fa fa-save fa-w-20"></i>
                                            </span>
                                            Simpan Data
                                        </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
<script src="<?php echo site_url("assets/scripts/jquery_3.4.1_jquery.min.js") ?>"></script>
<script src="<?php echo site_url("assets/scripts/select2.full.js") ?>"></script>
<script type="text/javascript">
$(document).ready(function(){
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

<script>
        $(".passingIDlk").click(function () {
                var ids = $(this).attr('id');
                $("#IDUser").val( ids );
        });

        $(".passingIDhapus").click(function () {
                var idh = $(this).attr('data-id');
                var kets = $(this).attr('ket');
                $("#IDTugas").val( idh );
                $("#Keterangan").val( kets );
        });
</script>
                        