

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-note icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    
                                    <div>Pengajuan Bebas Lab
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
                                            <th style="width: 5%;">#</th>
                                            <th style="width: 13%;">Waktu Pengajuan</th>
                                            <th style="width: 25%;">Npm<br>Nama</th>
                                            <th style="width: 12%;">Jurusan</th>
                                            <th style="width: 25%;">Lampiran</th>
                                            <th style="width: 20%;">Aksi</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if(empty($form))
                                        {
                                            echo "<tr><td colspan='6'>Data tidak tersedia</td></tr>";
                                        }
                                        else
                                        {
                                            $n = 0;
                                            $jml = count($form);
                                            foreach($form as $row) {
                                              
                                        ?>
                                        <tr>
                                            <td class="align-top">
                                                <?php echo ++$n; ?>
                                            </td>

                                            <td class="align-top">
                                                <?php 
                                                    $waktu = explode('-',substr($row->updated_at,0,10));
                                                    echo $waktu[2]."-".$waktu[1]."-".$waktu[0];
                                                    echo "<br>";
                                                    echo substr($row->updated_at,10);
                                                 ?>
                                            </td>

                                    
                                            <td class="align-top">
                                               <?php 
                                                   echo $row->npm;
                                                   echo "<br>";
                                                   echo $this->user_model->get_mahasiswa_name($row->npm);
                                               ?>
                                            </td>

                                            <td class="align-top">
                                               <?php 
                                                   echo $this->user_model->get_jurusan_nama($row->npm);
                                               ?>
                                            </td>

                                            <td class="align-top">
                                                <?php 
                                                   $lampiran = $this->layanan_model->get_lampiran_bebas_lab($row->id_bebas_lab); 
                                                   if(empty($lampiran)) {
                                                       echo "<i>(Belum ada, silakan lengkapi berkas lampiran)</i>";
                                                   } else {
                                                      
                                                       foreach($lampiran as $rw) {
                                                           echo "<li><a href='".base_url($rw->file)."' download>".$rw->nama_berkas."</a></li>";
                                                       }
       
                                                       echo "</ul>";
                                                   }
                                                ?>
                                            </td>

                                            <td class="align-top">
                                                    <a href="<?php echo site_url("dosen/bebas-lab/pengajuan/approve?id=".$this->encrypt->encode($row->id_meta)) ?>" class="btn-wide mb-2 btn btn-primary btn-sm">Verifikasi</a>
                                                    <!-- <a data-toggle = "modal" data-id="<?php echo $row->id_meta ?>" class="tolak">
                                                        <button style="width: 80px;" type="button" class="mb-2 btn btn-danger btn-sm"  data-toggle="modal" data-target="#tolaklab">
                                                            Tolak
                                                        </button>
                                                    </a> -->
                                                <?php 
                                                     
                                                ?>
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
    $(".tolak").click(function () {
        var id = $(this).attr('data-id');
        $("#IDtolak").val( id );
    });                               
     
</script>