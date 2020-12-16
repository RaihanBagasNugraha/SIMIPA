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
                                    
                                    <div>Rekap Progja
                                        <div class="page-title-subheading">
                                           Rekap Progja Lembaga Kemahasiswaan
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
                            
                            <form method="post" id= "form" action = "<?php echo site_url("dosen/lk/rekap-progja"); ?>">
                                        <div class="form-row">
                                            <div class="col-md-2">
                                                <div class="position-relative form-group">
                                                    <label for="bulan" class = ''><b>TAHUN AKADEMIK :</b></label>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <select name="ta" class='form-control' required id="">
                                                        <option value="">-- Pilih Tahun Akademik --</option>
                                                        <?php 
                                                           $per = $this->layanan_model->get_periode_tugas();
                                                           foreach($per as $per){
                                                        ?>
                                                            <option value="<?php echo $per->periode ?>" <?php echo $per->periode == $ta ? "selected" : "" ?>  ><?php echo $per->periode ?></option>
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
                                                <th width="5%">#</th>
                                                <th width="40%">Lembaga Kemahasiswaan</th>
                                                <th width="15%">Pagu Anggaran</th>
                                                <th width="15%">Detail</th>
                                            </tr>
                                           
                                        </thead>           
                                        <tbody>
                                            <?php 
                                              $no = 0;
                                              foreach($lk as $row){ 
                                            ?>
                                            <tr>
                                                <td class="align-top"><?php echo ++$no; ?></td>
                                                <td class="align-top"><?php echo $row->nama_lk; ?></td>
                                                <td class="align-top">
                                                    <?php $nilai = $this->layanan_model->get_nilai_proposal($row->id_lk,$ta);
                                                        if(empty($nilai)){
                                                            echo "Rp0";
                                                        }else{
                                                            echo "Rp".number_format($nilai->nilai);
                                                        }
                                                    ?>
                                                </td>
                                                <td class="align-top">
                                                    <a href="<?php echo site_url("dosen/lk/rekap-progja/detail?lk=$row->id_lk&periode=$ta") ?>" class="btn-wide mb-2 btn btn-success btn-sm ">Lihat</a>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                       
                                    </table>
                                <?php  }else{echo "<i>Silahkan Pilih Tahun Akademik Terlebih Dahulu</i>";} ?>
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