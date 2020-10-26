

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
                        
                        if(!empty($_GET['status']) && $_GET['status'] == 'duplikat_user') {
                            $id_duplikat = $_GET['id'];
                            $id_duplikat = $this->encrypt->decode($id_duplikat);
                            $data_duplikat = $this->user_model->get_dosen_data($id_duplikat);
                            echo '<div class="alert alert-danger fade show" role="alert"> Terdapat user dengan tugas yang sama dan status yang masih aktif : <a href="javascript:void(0);" class="alert-link">'.$data_duplikat->name.'</a></div>';
                        }
                        $id_user = $this->session->userdata('userId');
                        ?>
                        
                         <div class="main-card mb-3 card">
                                <div class="card-header">Form Kelola Biodata</div>
                                <div class="card-body">
                                    <form method="post" action="<?php echo site_url('dosen/ubah-data-biodata') ?>" >
                                
                                        <div class="position-relative row form-group">
                                            <label for="jurusan" class="col-sm-2 col-form-label">Jurusan</label>
                                            <div class="col-sm-10">
                                            <select name="jurusan" class="input-lg form-control" required>
                                            <option value = "">-- Pilih Jurusan --</option>
                                            <?php
                                            $list_jurusan = $this->jurusan_model->get_jurusan_all();
                                            // print_r($biodata);
                                            foreach ($list_jurusan as $row) {
                                                if($biodata->jurusan == $row->id_jurusan) $select = "selected";
                                                else $select = "";
                                                echo "<option ".$select." value='".$row->id_jurusan."'>".$row->nama."</option>";
                                            }
                                            ?>
                                            </select>
                                            
                                            </div>    
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label for="jurusan" class="col-sm-2 col-form-label">NIDN</label>
                                            <div class="col-sm-10">
                                                <input name="nidn" id="nidn" value="<?php echo $biodata->nidn ?>" type="text" placeholder="NIDN" class="form-control">                                    
                                            </div>    
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label for="jurusan" class="col-sm-2 col-form-label">ID SINTA</label>
                                            <div class="col-sm-10">
                                                <input name="id_sinta" id="sinta" value="<?php echo $biodata->id_sinta ?>" type="text" placeholder="ID SINTA" class="form-control">                                    
                                            </div>    
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label for="jurusan" class="col-sm-2 col-form-label">Jabatan Fungsional</label>
                                            <div class="col-sm-10">
                                            <select name="jabfung" class="input-lg form-control">
                                            <option value = "">-- Pilih Jabatan Fungsional --</option>
                                            <?php
                                            $list_jabfung = $this->user_model->get_jabfung_all();
                                            // print_r($biodata);
                                            foreach ($list_jabfung as $row) {
                                                if($biodata->fungsional == $row->id_fungsional) $select = "selected";
                                                else $select = "";
                                                echo "<option ".$select." value='".$row->id_fungsional."'>".$row->nama."</option>";
                                            }
                                            ?>
                                            </select>
                                            
                                            </div>    
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label for="jurusan" class="col-sm-2 col-form-label">Pangkat Golongan</label>
                                            <div class="col-sm-10">
                                            <select name="pangkat" class="input-lg form-control">
                                            <option value = "">-- Pilih Pangkat Golongan--</option>
                                            <?php
                                            $list_pangkat = $this->user_model->get_pangkat_all();
                                            // print_r($biodata);
                                            foreach ($list_pangkat as $row) {
                                                if($biodata->pangkat_gol == $row->id_pangkat_gol) $select = "selected";
                                                else $select = "";
                                                echo "<option ".$select." value='".$row->id_pangkat_gol."'>".$row->pangkat." (".$row->golongan."".$row->ruang.")</option>";
                                            }
                                            ?>
                                            </select>
                                            
                                            </div>    
                                        </div>

                                        <div class="divider"></div>
                                        <div class="position-relative row form-group">
                                            <label for="jurusan" class="col-sm-2 col-form-label">Gelar Depan</label>
                                            <div class="col-sm-10">
                                                <input name="gelar_depan" id="gelar" value="<?php echo $biodata->gelar_depan ?>" type="text" placeholder="Gelar Depan" class="form-control">                                    
                                            </div>    
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label for="jurusan" class="col-sm-2 col-form-label">Gelar Belakang</label>
                                            <div class="col-sm-10">
                                                <input name="gelar_belakang" id="gelar" value="<?php echo $biodata->gelar_belakang ?>" type="text" placeholder="Gelar Belakang" class="form-control">                                    
                                            </div>    
                                        </div>
                                        
                                        <div class="divider"></div>
                                        <div class="position-relative row form-group">
                                            

                                        <label for="jenkel" class="col-sm-2 col-form-label">Jenis Kelamin</label>
                                            <div class="col-sm-10">
                                            <select name="jenkel" class=" form-control">
                                            <option value = "">-- Pilih Jenis Kelamin --</option>
                                            <option value="Laki-laki" <?php if($biodata->jenis_kelamin == "Laki-laki") echo "selected" ?>>Laki-laki</option>
                                            <option value="Perempuan" <?php if($biodata->jenis_kelamin == "Perempuan") echo "selected" ?>>Perempuan</option>
                                            </select>
                                            </div>
                                        </div>
                                        <div class="position-relative row form-group">
                                            <label for="agama" class="col-sm-2 col-form-label">Agama</label>
                                            <div class="col-sm-10">
                                                <select name="agama" class=" form-control">
                                                <option value = "">-- Pilih Agama --</option>
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
                                                <option value = "">-- Pilih Provinsi --</option>
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
                                                <option value = "" >-- Pilih Kota/Kabupaten --</option>
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
                                                <option value = "">-- Pilih Kecamatan --</option>
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
                                                <option value = "">-- Pilih Kelurahan/Desa --</option>
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
                                        <label for="tgs_tmbh" class="col-sm-6 col-form-label"><b>Tugas Tambahan Aktif</b> &emsp;
                                            <a data-toggle = "modal" id="<?php echo $id_user; ?>"  class="passingIDtgs" >
                                                <button type="button" class="btn-wide mb-1 btn btn-success btn-sm "  data-toggle="modal" data-target="#tambahtugas">
                                                    Tambah
                                                </button>
                                            </a>
                                        </label>
                                        </div>
                                        <?php $tugas_tambah = $this->user_model->get_tugas_tambahan_id($id_user); ?>
                                       
                                        <table class="mb-0 table table-striped">
                                        <?php  foreach($tugas_tambah as $tgs){ ?>
                                                <tr>
                                                    <td>Tugas Tambahan</td>
                                                    <td>:</td>
                                                    <td><?php echo $this->user_model->get_tugas_tambahan_detail($tgs->tugas)->nama ?></td>
                                                </tr>   
                                                <?php if($tgs->tugas == '14') {?>
                                                    <tr>
                                                        <td>Prodi</td>
                                                        <td>:</td>
                                                        <td><?php echo $this->user_model->get_prodi_id($tgs->prodi)->nama ?></td>
                                                    </tr>   
                                                <?php } ?>
                                                <?php if($tgs->tugas == '12' || $tgs->tugas == '13' || $tgs->tugas == '17' || $tgs->tugas == '18' || $tgs->tugas == '19') {?>
                                                    <tr>
                                                        <td>Jurusan</td>
                                                        <td>:</td>
                                                        <td><?php echo $this->user_model->get_jurusan_id($tgs->jurusan_unit)->nama ?></td>
                                                    </tr>   
                                                <?php }if($tgs->tugas == '15' || $tgs->tugas == '16' ){ ?>
                                                    <tr>
                                                        <td>Laboratorium</td>
                                                        <td>:</td>
                                                        <td><?php echo $this->user_model->get_lab_by_id($tgs->jurusan_unit)->nama_lab ?></td>
                                                    </tr>   
                                                <?php } ?>
                                                <tr>
                                                    <td>Periode</td>
                                                    <td>:</td>
                                                    <td><?php echo $tgs->periode ?></td>
                                                </tr>  
                                                <tr >
                                                    <td>Status</td>
                                                    <td>:</td>
                                                    <td><b><?php echo $tgs->aktif == 1 ? "Aktif" : "Nonaktif" ?></b></td>
                                                </tr>  
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    
                                                    <td style="align:left;">
                                                        <?php if($tgs->aktif != 0) { ?>
                                                            <a data-toggle = "modal" data-id="<?php echo $tgs->id ?>" ket="0" class="passingIDhapus" >
                                                                    <button type="button" class="btn-small mb-1 btn btn-warning btn-lg"  data-toggle="modal" data-target="#nonaktiftugas">
                                                                        Nonaktif
                                                                    </button>
                                                            </a>
                                                        <?php } ?>
                                                        <a data-toggle = "modal" data-id="<?php echo $tgs->id ?>" ket="1" class="passingIDhapus" >
                                                                <button type="button" class="btn-small mb-1 btn btn-danger btn-lg"  data-toggle="modal" data-target="#nonaktiftugas">
                                                                    Hapus
                                                                </button>
                                                        </a>
                                                    </td>
                                                </tr>   
                                                <?php } ?>
                                        </table>
                                        
                                        
                                        <!-- <div class="position-relative row form-group">
                                        <label for="tgs_tambahan" class="col-sm-2 col-form-label">Tugas Tambahan</label>
                                            <div class="col-sm-10">
                                            <select name="tugas_tambahan" class="input-lg form-control">
                                            <option>-- Pilih Pangkat --</option>
                                            <?php
                                            $list_tugas = $this->user_model->get_tugas_tambahan_all();
                                            // print_r($biodata);
                                            foreach ($list_tugas as $row) {
                                                if($biodata->pangkat_gol == $row->id_pangkat_gol) $select = "selected";
                                                else $select = "";
                                                echo "<option ".$select." value='".$row->id_tugas_tambahan."'>".$row->nama."</option>";
                                            }
                                            ?>
                                            </select>
                                            </div>
                                        </div> -->
                                  
                                        
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
        url: "<?php echo site_url('dosen/ambil_data') ?>",
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
        $(".passingIDtgs").click(function () {
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