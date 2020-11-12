

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-note icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <?php 
                                        $jenis = $this->uri->segment(3);
                                        if($jenis == "akademik"){
                                            $layanan = "Akademik";
                                        }elseif($jenis == "umum-keuangan"){$layanan = "Umum dan Keuangan";}
                                        elseif($jenis == "kemahasiswaan"){$layanan = "Kemahasiswaan";}
                                        else{$layanan= "";}
                                    
                                    ?>
                                    <div>Verifikasi Pengajuan Keluar Layanan <?php echo $layanan ?>
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
                                            <th style="width: 13%;">Waktu</th>
                                            <th style="width: 25%;">Npm<br>Nama</th>
                                            <th style="width: 12%;">Jurusan</th>
                                            <th style="width: 25%;">Form</th>
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
                                            foreach($form as $row) {
                                            // $data_layanan = $this->layanan_model->get_form_mhs_id($row->id_layanan_fakultas_mahasiswa);
                                        ?>
                                        <tr>
                                            <td class="align-top">
                                                <?php echo ++$n; ?>
                                            </td>
                                            <td class="align-top">
                                                <?php 

                                                    $waktu = explode('-',substr($row->created_at,0,10));
                                                    echo $waktu[2]."-".$waktu[1]."-".$waktu[0];
                                                    echo "<br>";
                                                    echo substr($row->created_at,10);
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
                                                echo "<li><a href='".site_url('/mahasiswa/layanan-fakultas/'.$jenis.'/unduh?id='.$row->id.'&layanan='.$row->id_layanan_fakultas)."'>".$this->layanan_model->get_layanan_fakultas_by_id($row->id_layanan_fakultas)->nama."</a></li>";
                                              ?>
                                            </td>
                                            <td class="align-top">
                                                <a data-toggle = "modal" data-id="<?php echo $row->id ?>" class="verifikasi">
                                                    <button style="width: 80px;" type="button" class="mb-2 btn btn-primary btn-sm"  data-toggle="modal" data-target="#verifikasisurat">
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
        var mhs = $(this).attr('data-mhs');
        $("#IDSurat").val( id );
        $("#IDLayanan").val( mhs );
    });                                
     
</script>