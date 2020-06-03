

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-file icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <div>Pengajuan Tema Penelitian
                                        <div class="page-title-subheading">Jangan lupa untuk mengunggah berkas pendukung.
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
                        ?>
                        <div class="row">
                        <div class="col-md-7">
                         <div class="main-card mb-3 card">
                                <div class="card-header">Form Pengajuan Tema Penelitian</div>
                                <div class="card-body">
                                    <form method="post" action="<?php echo site_url('mahasiswa/ubah-data-biodata') ?>" >
                                
                                        <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-3 col-form-label">Judul Utama</label>
                                            <div class="col-sm-9">
                                            <textarea name="judul_1" class="form-control" placeholder="Ketik judul utama penelitian di sini..."></textarea>
                                            </div>
                                        </div>
                                        <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-3 col-form-label">Judul Alternatif</label>
                                            <div class="col-sm-9">
                                            <textarea name="judul_2" class="form-control" placeholder="Ketik judul alternatif di sini..."></textarea>
                                            </div>
                                        </div>
                                        <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">IPK</label>
                                            <div class="col-sm-3">
                                                <input required name="ipk" class="form-control input-mask-trigger" data-inputmask="'mask': '9.99'" im-insert="true">
                                            </div>
                                        </div>
                                        <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">Jumlah SKS</label>
                                            <div class="col-sm-3">
                                                <input required name="sks" class="form-control input-mask-trigger" data-inputmask="'mask': '999'" im-insert="true">
                                            </div>
                                        </div>
                                        <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">Nilai TOEFL</label>
                                            <div class="col-sm-3">
                                                <input name="toefl" class="form-control input-mask-trigger" data-inputmask="'mask': '999'" im-insert="true">
                                            </div>
                                        </div>
                                        <div class="position-relative row form-group">
                                            <label for="dosen_pa" class="col-sm-3 col-form-label">Pembimbing 1</label>
                                            <div class="col-sm-9">
                                                <select name="dosen_pa" class=" form-control">
                                                <option>-- Pilih Dosen Pembimbing Utama --</option>
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
                                            <label for="jalur_masuk" class="col-sm-3 col-form-label">Bidang Keilmuan</label>
                                            <div class="col-sm-9">
                                                <select name="jalur_masuk" class=" form-control">
                                                <option>-- Pilih Bidang Keilmuan --</option>
                                                <?php
                                                $list = $this->parameter_model->select_bidang_ilmu($biodata->jurusan);
                                                foreach ($list as $row) {
                                                    $select = "";
                                                    //if($biodata->jalur_masuk == $row->id_jalur_masuk) $select = "selected";
                                                   
                                                    echo "<option ".$select." value='".$row->id_jalur_masuk."'>".$row->nama."</option>";
                                                    }
                                                ?>

                                                </select>
                                                
                                            
                                            </div>
                                        </div>
                                        
                                
                                  
                                        
                                        <div class="position-relative row form-group">
                                            <div class="col-sm-9 offset-sm-3">
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
                            </div> <!-- col-md -->

                            <div class="col-md-5">
                                <div class="main-card mb-3 card">
                                    <div class="card-header">Berkas Lampiran</div>
                                    <div class="card-body">
                                    <div class="table-responsive">
                                                    <table class="mb-0 table table-striped">
                                                        <thead>
                                                        <tr>
                                                            <th style="width: 10%;">#</th>
                                                            <th style="width: 60%;">Berkas</th>
                                                            <th style="width: 30%;">Aksi</th>
            
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td>1</td>
                                                            <td>KRS</td>
                                                            <td></td>
                                                            
                                                        </tr>
                                                        
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="divider"></div>
                                        <form method="post" action="<?php echo site_url('mahasiswa/ubah-data-akun') ?>" enctype="multipart/form-data" >
                                            <div class="position-relative row form-group">
                                                <div class="col-sm-12">
                                                    <select name="jalur_masuk" class=" form-control">
                                                    <option>-- Pilih Jenis Berkas --</option>
                                                    <?php
                                                    $list = $this->parameter_model->select_jenis_berkas();
                                                    foreach ($list as $row) {
                                                        $select = "";
                                                        //if($biodata->jalur_masuk == $row->id_jalur_masuk) $select = "selected";
                                                        
                                                        echo "<option ".$select." value='".$row->id_jenis."'>".$row->nama."</option>";
                                                        }
                                                    ?>

                                                    </select>
                                                    
                                                
                                                </div>
                                            </div>
                                                
                                                <div class="position-relative row form-group">
                                                <div class="col-sm-12"><input oninvalid="this.setCustomValidity('Anda belum memilih berkas!')" oninput="this.setCustomValidity('')" accept=".jpg, .jpeg, .png" name="file" id="file" type="file" class="form-control-file">
                                                        </div>
                                                </div>

                                                               
                                                
                                                <div class="position-relative row form-group">
                                                    <div class="col-sm-12">
                                                    <button type="submit" class="btn-shadow btn btn-success">
                                                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                        <i class="fa fa-plus fa-w-20"></i>
                                                    </span>
                                                    Tambah Berkas
                                                </button>
                                                    </div>
                                                </div>
                                            </form>

                                            

                                               
                                    
                                    </div>
                                </div>
                            </div>
                        </div> <!-- row -->


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
                        