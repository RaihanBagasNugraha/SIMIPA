

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
                        $seg = $this->uri->segment(3);
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
                                            <th style="width: 25%;">Jenis</th>
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
                                                 echo $this->layanan_model->get_layanan_fakultas_by_id($row->id_layanan_fakultas)->nama;
                                               ?>
                                            </td>

                                            <td class="align-top">
                                                <?php 
                                                    echo "<li><a href='".site_url('/mahasiswa/layanan-fakultas/'.$seg.'/unduh?id='.$row->id.'&layanan='.$row->id_layanan_fakultas)."'>".$this->layanan_model->get_layanan_fakultas_by_id($row->id_layanan_fakultas)->nama."</a></li>";
                                                   $lampiran = $this->layanan_model->get_lampiran_layanan_list($row->id); 
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
                                                <a data-toggle = "modal" data-id="<?php echo $row->id ?>" class="verifikasi">
                                                    <button style="width: 80px;" type="button" class="mb-2 btn btn-primary btn-sm"  data-toggle="modal" data-target="#verifikasimasuk">
                                                        Verifikasi
                                                    </button>
                                                </a>
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
        $("#IDMasuk").val( id );
    });    
    // $(".tolak").click(function () {
    //     var id = $(this).attr('data-id');
    //     $("#IDtolak").val( id );
    // });                               
     
</script>