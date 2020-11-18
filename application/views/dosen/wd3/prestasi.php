

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-note icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    
                                    <div>Prestasi Mahasiswa
                                        <div class="page-title-subheading">
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
                            
                         <div class="main-card mb-3 card">
                                <div class="card-body">
                                <div class="table-responsive">
                                    <table class="mb-0 table table-striped" id="example">
                                        <thead>
                                        <tr>
                                            <th style="width: 10%;" rowspan = "2">Tahun</th>
                                            <th style="width: 18%;text-align:center" colspan="2">Kimia</th>
                                            <th style="width: 18%;text-align:center" colspan="2">Biologi</th>
                                            <th style="width: 18%;text-align:center" colspan="2">Matematika</th>
                                            <th style="width: 18%;text-align:center" colspan="2">Fisika</th>
                                            <th style="width: 18%;text-align:center" colspan="2">Ilmu Komputer</th>
                                        </tr>
                                        <tr>
                                            <!-- <th>Nama<br>NIP/NIK</th> -->
                                            <!-- <th>Strata</th> -->
                                            <th style="text-align:center;font-size:12px">Akademik</th>
                                            <th style="text-align:center;font-size:12px">Non Akademik</th>
                                            <th style="text-align:center;font-size:12px">Akademik</th>
                                            <th style="text-align:center;font-size:12px">Non Akademik</th>
                                            <th style="text-align:center;font-size:12px">Akademik</th>
                                            <th style="text-align:center;font-size:12px">Non Akademik</th>
                                            <th style="text-align:center;font-size:12px">Akademik</th>
                                            <th style="text-align:center;font-size:12px">Non Akademik</th>
                                            <th style="text-align:center;font-size:12px">Akademik</th>
                                            <th style="text-align:center;font-size:12px">Non Akademik</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if(empty($tahun))
                                        {
                                            echo "<tr><td colspan='6'>Data tidak tersedia</td></tr>";
                                        }
                                        else
                                        {
                                            foreach($tahun as $row) {
                                              
                                        ?>
                                        <tr>
                                            <td class="align-top">
                                                <?php echo $row->tahun; ?>
                                            </td>

                                            <!-- kimia 1 -->
                                            <td class="align-top" style="text-align:center">
                                                <?php 
                                                    $kimia_akademik = $this->layanan_model->get_jumlah_prestasi_jurusan(1,"Akademik",$row->tahun);
                                                    $kimia_non_akademik = $this->layanan_model->get_jumlah_prestasi_jurusan(1,"Non Akademik",$row->tahun);
                                                ?>
                                                <a style="color: white;" href="<?php echo site_url("dosen/prestasi/akademik?jurusan=1&tahun=".$row->tahun)?>" class="btn-wide mb-2 btn btn-success btn-sm"><?php echo $kimia_akademik ?></a>
                                            </td>

                                            <td class="align-top" style="text-align:center">
                                                <a style="color: white;" href="<?php echo site_url("dosen/prestasi/non-akademik?jurusan=1&tahun=".$row->tahun)?>" class="btn-wide mb-2 btn btn-info btn-sm"><?php echo $kimia_non_akademik ?></a>
                                            </td>


                                            <!-- Biologi 2 -->
                                            <td class="align-top" style="text-align:center">
                                                <?php
                                                    $bio_akademik = $this->layanan_model->get_jumlah_prestasi_jurusan(2,"Akademik",$row->tahun);
                                                    $bio_non_akademik = $this->layanan_model->get_jumlah_prestasi_jurusan(2,"Non Akademik",$row->tahun);
                                                ?>
                                                <a style="color: white;" href="<?php echo site_url("dosen/prestasi/akademik?jurusan=2&tahun=".$row->tahun)?>" class="btn-wide mb-2 btn btn-success btn-sm"><?php echo $bio_akademik ?></a>
                                            </td>

                                            <td class="align-top" style="text-align:center">
                                                <a style="color: white;" href="<?php echo site_url("dosen/prestasi/non-akademik?jurusan=2&tahun=".$row->tahun)?>" class="btn-wide mb-2 btn btn-info btn-sm"><?php echo $bio_non_akademik ?></a>
                                            </td>

                                            <!-- MTK 3 -->
                                            <td class="align-top" style="text-align:center">
                                                <?php 
                                                    $mtk_akademik = $this->layanan_model->get_jumlah_prestasi_jurusan(3,"Akademik",$row->tahun);
                                                    $mtk_non_akademik = $this->layanan_model->get_jumlah_prestasi_jurusan(3,"Non Akademik",$row->tahun);
                                                ?>
                                                <a style="color: white;" href="<?php echo site_url("dosen/prestasi/akademik?jurusan=3&tahun=".$row->tahun)?>" class="btn-wide mb-2 btn btn-success btn-sm"><?php echo $mtk_akademik ?></a>
                                            </td>

                                            <td class="align-top" style="text-align:center">
                                                <a style="color: white;" href="<?php echo site_url("dosen/prestasi/non-akademik?jurusan=3&tahun=".$row->tahun)?>" class="btn-wide mb-2 btn btn-info btn-sm"><?php echo $mtk_non_akademik ?></a>
                                            </td>

                                            <!-- Fisika 4 -->
                                            <td class="align-top" style="text-align:center">
                                                <?php 
                                                    $fsk_akademik = $this->layanan_model->get_jumlah_prestasi_jurusan(4,"Akademik",$row->tahun);
                                                    $fsk_non_akademik = $this->layanan_model->get_jumlah_prestasi_jurusan(4,"Non Akademik",$row->tahun);
                                                ?>
                                                <a style="color: white;" href="<?php echo site_url("dosen/prestasi/akademik?jurusan=4&tahun=".$row->tahun)?>" class="btn-wide mb-2 btn btn-success btn-sm"><?php echo $fsk_akademik ?></a>
                                            </td>

                                            <td class="align-top" style="text-align:center">
                                                <a style="color: white;" href="<?php echo site_url("dosen/prestasi/non-akademik?jurusan=4&tahun=".$row->tahun)?>" class="btn-wide mb-2 btn btn-info btn-sm"><?php echo $fsk_non_akademik ?></a>
                                            </td>

                                            <!-- ilkom 5 -->
                                            <td class="align-top" style="text-align:center">
                                                <?php 
                                                   $ilkom_akademik = $this->layanan_model->get_jumlah_prestasi_jurusan(5,"Akademik",$row->tahun);
                                                   $ilkom_non_akademik = $this->layanan_model->get_jumlah_prestasi_jurusan(5,"Non Akademik",$row->tahun);                 
                                                ?>
                                                <a style="color: white;" href="<?php echo site_url("dosen/prestasi/akademik?jurusan=5&tahun=".$row->tahun)?>" class="btn-wide mb-2 btn btn-success btn-sm"><?php echo $ilkom_akademik ?></a>
                                            </td>

                                            <td class="align-top;" style="text-align:center;">
                                                <a style="color: white;" href="<?php echo site_url("dosen/prestasi/non-akademik?jurusan=5&tahun=".$row->tahun)?>" class="btn-wide mb-2 btn btn-info btn-sm"><?php echo $ilkom_non_akademik ?></a>
                                            </td>
                                        
                                          
                                        </tr>
                                        <?php
                                            }
                                        }
                                        ?>
                                        </tbody>
                                        <tfoot>
                                         
                                        </tfoot>
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
    $(".verifikasi").click(function () {
        var id = $(this).attr('data-id');
        $("#IDverif").val( id );
    });                               
     
</script>