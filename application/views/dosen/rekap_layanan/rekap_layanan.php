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
                                    <?php 
                                        $jns = $this->uri->segment(3); 
                                        switch($jns){
                                            case "akademik":
                                                $jns2 = "Akademik";
                                                break;
                                            case "kemahasiswaan":
                                                $jns2 = "Kemahasiswaan";
                                                break;
                                            case "umum-keuangan":
                                                $jns2 = "Umum Dan Keuangan";
                                                break;
                                        }    
                                    ?>
                                    <div>Rekap Layanan 
                                        <div class="page-title-subheading">
                                           Layanan <?php echo $jns2; ?>
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
                            
                            <form method="post" id= "form" action = "<?php echo site_url("dosen/rekap-layanan/".$jns); ?>">
                                        <div class="form-row">
                                            <div class="col-md-1">
                                                <div class="position-relative form-group">
                                                    <label for="bulan" class = ''><b>AWAL :</b></label>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="position-relative form-group">
                                                   <input type="text" required name='awal' required class='form-control tgl' placeholder='Tanggal Awal Form' value="<?php echo $awal != null ? $awal : '' ?>">
                                                </div>
                                            </div>

                                            <div class="col-md-1">
                                                
                                            </div>

                                            <div class="col-md-1">
                                                <div class="position-relative form-group">
                                                    <label for="bulan" class = ''><b>AKHIR :</b></label>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="position-relative form-group">
                                                   <input type="text" required name='akhir' required class='form-control tgl' placeholder='Tanggal Akhir Form' value="<?php echo $akhir != null ? $akhir : '' ?>">
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
                                    <table  id="example" data-page-length='100' style="width: 100%;" class="mb-0 table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th width="5%">#</th>
                                                <th width="80%">Nama Layanan</th>
                                                <th width="15%">Jumlah</th>
                                            </tr>
                                        </thead>           
                                        <tbody>
                                            <?php 
                                                $no = 0;
                                                $no2 = 0;
                                                $tot = 0;
                                                foreach($form as $row){
                                                    $jml = 0;
                                                    $layanan = $this->layanan_model->get_layanan_fakultas_by_id($form[$no]);
                                                    $jml = count($this->layanan_model->get_form_mhs_range($form[$no],$awal,$akhir));
                                                    $tot += $jml;
                                            ?>
                                            <tr>
                                                <td><?php echo ++$no2; ?></td>
                                                <td><?php echo $layanan->nama; ?></td>
                                                <?php 
                                                    if($jml == 0){
                                                        $btn = 'btn-danger';
                                                    }else{
                                                        $btn = 'btn-success';
                                                    }
                                                ?>
                                                <td><a href="<?php echo site_url("dosen/rekap-layanan/$jns/detail?form=".$form[$no]."&awal=$awal&akhir=$akhir") ?>" class="btn-wide mb-2 btn <?php echo $btn; ?> btn-sm "><?php echo $jml ?></a></td>
                                            </tr>
                                        <?php 
                                            $no++;
                                        } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="2">TOTAL</th>
                                                <th>
                                                    <input readonly type="text" name="total" value="<?php echo $tot; ?>" class="form-control ">
                                                </th>
                                            </tr>
                                        </tfoot>
                                       
                                    </table>
                                <?php  }else{echo "<i>Silahkan Pilih Tanggal Awal dan Akhir Form Layanan</i>";} ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

<script src="<?php echo site_url("assets/scripts/jquery_3.4.1_jquery.min.js") ?>"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js"></script>
<script src="<?php echo site_url("assets/scripts/dataTables.bootstrap4.min.js") ?>"></script>
<script src="<?php echo site_url("assets/scripts/DataTables-1.10.21/jquery.dataTables.min.js") ?>"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script type="text/javascript">
$(document).ready(function(){
    $(".readonly").on('keydown paste', function(e){
        e.preventDefault();
        $(this).blur();
    });

    $('.tgl').datepicker({
        dateFormat : 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
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