<style>
.number-input {
    padding: 5px; 
    font-size: 14px; 
    height: 34px;
}
.sub-total {
    font-weight: bold;
}
.select-input {
    font-size: 14px;
}

</style>
                        <div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-note2 icon-gradient bg-mean-fruit"></i>
                                    </div>
                                    
                                    <div>Rekap Beasiswa
                                        <div class="page-title-subheading">
                                           Daftar Mahasiswa Yang Mengajukan atau Menerima Beasiswa
                                        </div>
                                       
                                    </div>
                                </div>
                              

                                <div class="page-title-actions">
                                   
                                </div>
                            </div>
                        </div> <!-- app-page-title -->
                        <?php
                            // debug
                            //echo "<pre>";
                            //print_r($akun);
                            //echo "</pre>";
                            if(!empty($_GET['status']) && $_GET['status'] == 'sukses') {

                                echo '<div class="alert alert-success fade show" role="alert">Data Berhasil Ditambahkan</div>';
                            }
                           
                        ?>
                <div class="row">    
                    <div class="col-md-12">
                        <div class="card mb-4 widget-chart  card-btm-border card-shadow-primary border-primary">
                            <div class="text-left ml-1 mr-1 mt-2">
                            
                            <form method="post" id= "form" action = "<?php echo site_url("dosen/beasiswa-mhs"); ?>">
                                        <div class="form-row">
                                            <div class="col-md-2">
                                                <div class="position-relative form-group">
                                                    <label for="bulan" class = ''><b>TAHUN BEASISWA :</b></label>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <select required name="tahun" class='form-control' id="">
                                                    <option value="">-- PILIH TAHUN --</option>
                                                        <?php  
                                                            $thn = $this->layanan_model->get_tahun_beasiswa();
                                                            
                                                            foreach($thn as $thn){
                                                                
                                                        ?>
                                                            <option value="<?php echo $thn->tahun ?>" <?php if($post == 1 && $tahun == $thn->tahun){echo "selected";} ?> ><?php echo $thn->tahun; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="position-relative form-group">
                                                    <button type="submit" id='btnSubmit' class="btn-shadow btn-lg btn btn-primary ">
                                                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                                                <i class="fa fa-save fa-w-20"></i>
                                                            </span>
                                                        Pilih
                                                    </button>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </form>  
                            </div>
                            
                        </div>
                        <div class="main-card mb-3 card card-btm-border card-shadow-success border-success"> 
                            <div class="card-body">
                                <div class="table-responsive">
                                <?php if($post == 1){ ?>
                                    <table  id="example" style="width: 100%;" class="mb-0 table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th rowspan = "2" width="5%">#</th>
                                                <th rowspan = "2" width="35%">List Beasiswa Tahun <?php echo $tahun ?></th>
                                                <th rowspan = "2" width="10%">Status</th>
                                                <th colspan='5' style="text-align:center" width="50%">Jurusan</th>
                                            </tr>
                                            <tr>
                                                <th width="10%">Kimia</th>
                                                <th width="10%">Biologi</th>
                                                <th width="10%">Matematika</th>
                                                <th width="10%">Fisika</th>
                                                <th width="10%">Ilmu Komputer</th>
                                            </tr>
                                        </thead>           
                                        <tbody>
                                            <?php
                                                $no = 0; 
                                                foreach($beasiswa as $row){  ?>                    
                                                <tr>
                                                    <td><?php echo ++$no; ?></td>
                                                    <td><b><?php echo $row->nama; ?></b></td>
                                                    <td>
                                                    <?php 
                                                        if($row->status == 1){
                                                            $sts = "Aktif";
                                                        }elseif($row->status == 0){
                                                            $sts =  "Nonaktif";
                                                        }
                                                    ?>
                                                    <a href="<?php echo site_url("dosen/beasiswa") ?>" class="btn-shadow btn btn-link btn-block" ><?php echo $sts ?></a>
                                                    </td>
                                                    
                                                    <!-- kimia 1 -->
                                                    <td>
                                                    <a href="<?php echo site_url("dosen/beasiswa-mhs/detail?jurusan=1&beasiswa=$row->id&status=lulus") ?>" class="btn-shadow btn btn-primary "><?php echo count($this->layanan_model->get_mhs_beasiswa_jurusan(1,$row->id)); ?></a>
                                                    <a href="<?php echo site_url("dosen/beasiswa-mhs/detail?jurusan=1&beasiswa=$row->id&status=tolak") ?>" class="btn-shadow btn btn-danger "><?php echo count($this->layanan_model->get_mhs_beasiswa_jurusan_tolak(1,$row->id)); ?></a>
                                                    </td> 
                                                    <!-- biologi 2 -->
                                                    <td>
                                                    <a href="<?php echo site_url("dosen/beasiswa-mhs/detail?jurusan=2&beasiswa=$row->id&status=lulus") ?>" class="btn-shadow btn btn-primary"><?php echo count($this->layanan_model->get_mhs_beasiswa_jurusan(2,$row->id)); ?></a>
                                                    <a href="<?php echo site_url("dosen/beasiswa-mhs/detail?jurusan=2&beasiswa=$row->id&status=tolak") ?>" class="btn-shadow btn btn-danger"><?php echo count($this->layanan_model->get_mhs_beasiswa_jurusan_tolak(2,$row->id)); ?></a>
                                                    </td> 
                                                    <!-- matematika 3 -->
                                                    <td>
                                                    <a href="<?php echo site_url("dosen/beasiswa-mhs/detail?jurusan=3&beasiswa=$row->id&status=lulus") ?>" class="btn-shadow btn btn-primary "><?php echo count($this->layanan_model->get_mhs_beasiswa_jurusan(3,$row->id)); ?></a>
                                                    <a href="<?php echo site_url("dosen/beasiswa-mhs/detail?jurusan=3&beasiswa=$row->id&status=tolak") ?>" class="btn-shadow btn btn-danger "><?php echo count($this->layanan_model->get_mhs_beasiswa_jurusan_tolak(3,$row->id)); ?></a>
                                                    </td> 
                                                    <!-- fisika 4 -->
                                                    <td>
                                                    <a href="<?php echo site_url("dosen/beasiswa-mhs/detail?jurusan=4&beasiswa=$row->id&status=lulus") ?>" class="btn-shadow btn btn-primary"><?php echo count($this->layanan_model->get_mhs_beasiswa_jurusan(4,$row->id)); ?></a>
                                                    <a href="<?php echo site_url("dosen/beasiswa-mhs/detail?jurusan=4&beasiswa=$row->id&status=tolak") ?>" class="btn-shadow btn btn-danger"><?php echo count($this->layanan_model->get_mhs_beasiswa_jurusan_tolak(4,$row->id)); ?></a>
                                                    </td> 
                                                    <!-- ilkom 5 -->
                                                    <td>
                                                    <a href="<?php echo site_url("dosen/beasiswa-mhs/detail?jurusan=5&beasiswa=$row->id&status=lulus") ?>" class="btn-shadow btn btn-primary"><?php echo count($this->layanan_model->get_mhs_beasiswa_jurusan(5,$row->id)); ?></a>
                                                    <a href="<?php echo site_url("dosen/beasiswa-mhs/detail?jurusan=5&beasiswa=$row->id&status=tolak") ?>" class="btn-shadow btn btn-danger"><?php echo count($this->layanan_model->get_mhs_beasiswa_jurusan_tolak(5,$row->id)); ?></a>
                                                    </td> 
                                                </tr>
                                            <?php } ?>             
                                        </tbody>
                                       
                                    </table>
                                <?php  }else{echo "<i>Silahkan Pilih Tahun Beasiswa Terlebih Dahulu</i>";} ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

<script src="<?php echo site_url("assets/scripts/jquery_3.4.1_jquery.min.js") ?>"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js"></script>
<script src="<?php echo site_url("assets/scripts/dataTables.bootstrap4.min.js") ?>"></script>
<script src="<?php echo site_url("assets/scripts/DataTables-1.10.21/jquery.dataTables.min.js") ?>"></script>
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