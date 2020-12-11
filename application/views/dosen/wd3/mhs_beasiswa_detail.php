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
                        
                        <div class="main-card mb-3 card card-btm-border card-shadow-primary border-primary"> 
                            <div class="card-body">
                                <div class="table-responsive">
                               
                                    <table  id="example" style="width: 100%;" class="mb-0 table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th width="5%">#</th>
                                                <th width="20%">Nama</th>
                                                <th width="15%">NPM</th>
                                                <th width="20%">Status</th>
                                                <th width="20%">Keterangan</th>
                                            </tr>
                                        </thead>           
                                        <tbody>
                                            <?php 
                                            if(!empty($beasiswa)){
                                            $no = 0;
                                            foreach($beasiswa as $row){ ?>
                                                <tr>
                                                    <td><b><?php echo ++$no; ?></b></td>
                                                    <td><b><?php echo ++$no; ?></b></td>
                                                    <td><b><?php echo ++$no; ?></b></td>
                                                </tr>
                                            <?php }
                                            }  ?>         
                                        </tbody>
                                       
                                    </table>
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