

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-note icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <?php 
                                        $form = $this->input->get('form');
                                        $awal = $this->input->get('awal');
                                        $akhir = $this->input->get('akhir');
                                        $layanan = $this->layanan_model->get_layanan_fakultas_by_id($form);

                                        $bln_awal = explode("-",$awal);
                                        $bln_awal2 = $this->parameter_model->get_month($bln_awal[1]);
                                        $bln_akhir = explode("-",$akhir);
                                        $bln_akhir2 = $this->parameter_model->get_month($bln_akhir[1]);
                                    ?>
                                    <div>Detail Rekap Layanan
                                        <div class="page-title-subheading">
                                            Jumlah Pengajuan Layanan <b><?php echo $layanan->nama ?></b>                                           
                                        </div>
                                        <div class="page-title-subheading">
                                            Periode <?php echo "<b>".$bln_awal[2]." ".$bln_awal2." ".$bln_awal[0]."</b> - <b>".$bln_akhir[2]." ".$bln_akhir2." ".$bln_akhir[0]."</b>"; ?>                                           
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
                        //print_r($biodata);
                        //echo "</pre>";
                        if(!empty($_GET['status']) && $_GET['status'] == 'sukses') {

                            echo '<div class="alert alert-success fade show" role="alert">Biodata Anda sudah diperbarui, jangan lupa untuk memperbarui <a href="javascript:void(0);" class="alert-link">Akun</a> sebelum menggunakan layanan.</div>';
                        }
                        ?>
                        
                         <div class="main-card mb-3 card">
                                <div class="card-body">
                                <div class="table-responsive">
                                    <table class="mb-0 table table-striped" id="example">
                                        <thead>
                                        <tr>
                                            <th style="width: 5%;">#</th>
                                            <th style="width: 35%;">Jurusan</th>
                                            <th style="width: 15%;">Jumlah Pengajuan</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if(empty($jurusan))
                                        {
                                            echo "<tr><td colspan='6'>Data tidak tersedia</td></tr>";
                                        }
                                        else
                                        {
                                            $no = 0;
                                            foreach($jurusan as $row) {
                                        ?>
                                            <tr>
                                                <td class="align-top"><?php echo ++$no; ?></td>
                                                <td class="align-top"><?php echo $row->nama; ?></td>
                                                <?php 
                                                    $jml = count($this->layanan_model->get_form_mhs_range_jur($form,$awal,$akhir,$row->id_jurusan));
                                                    if($jml == 0){
                                                        $btn = 'btn-danger';
                                                    }else{
                                                        $btn = 'btn-success';
                                                    }
                                                ?>
                                                <td class="align-top"><span class="btn-wide mb-2 btn <?php echo $btn; ?> btn-sm "><?php echo $jml; ?></span></td>
                                            </tr>
                                        <?php
                                            }
                                        }
                                        ?>
                                        
                                        </tbody>
                                    </table>
                                </div>
                                </div>
                            </div>
<script src="<?php echo site_url("assets/scripts/jquery_3.4.1_jquery.min.js") ?>"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js"></script>
<script src="<?php echo site_url("assets/scripts/dataTables.bootstrap4.min.js") ?>"></script>
<script src="<?php echo site_url("assets/scripts/DataTables-1.10.21/jquery.dataTables.min.js") ?>"></script>
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

$(document).ready(function() {
    $('#example').DataTable();
} );

</script>

<script>
    $(".passingID").click(function () {
                var id = $(this).attr('data-id');
                $("#ID_progja").val( id );
            });
    $(".passingID2").click(function () {
                var id = $(this).attr('data-id');
                $("#ID_progja2").val( id );
            });        
</script>