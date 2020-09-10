

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-note icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <div>Verifikasi Program Tugas Akhir
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

                            echo '<div class="alert alert-success fade show" role="alert">Verifikasi-TA Diajukan</div>';
                        }
                        if(!empty($_GET['status']) && $_GET['status'] == 'error') {

                            echo '<div class="alert alert-danger fade show" role="alert">Komponen Penilaian Bidang Belum Diisi Ketua Jurusan</div>';
                        }
                        if(!empty($_GET['status']) && $_GET['status'] == 'berhasil') {

                            echo '<div class="alert alert-success fade show" role="alert">Verifikasi-TA Diajukan</div>';
                        }
                        ?>
                        
                         <div class="main-card mb-3 card">
                                <div class="card-body">
                                <div class="table-responsive">
                                    <table class="mb-0 table table-striped" id="example">
                                        <thead>
                                        <tr>
                                            <th style="width: 28%;">JUDUL</th>
                                            <th style="width: 19%;">VERIFIKATOR</th>
                                            <th style="width: 13%;">STATUS</th>
                                            <th style="width: 13%;">BERKAS</th>
                                            <th style="width: 7%;">AKSI</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if(empty($ta))
                                        {
                                            echo "<tr><td colspan='6'>Data tidak tersedia</td></tr>";
                                        }
                                        else {
                                        ?>
                                        <tr>
                                            <td class="align-top">
                                                <?php echo $ta->judul_approve == 1 ? $ta->judul1 : $ta->judul2 ?>
                                            </td>
                                            <td class="align-top">
                                                <?php 
                                                $gelarv = $this->user_model->get_gelar_dosen_nip($ta->nip_nik);

                                                if(empty($gelarv)){
                                                    $g_depanv = "";
                                                    $g_belakangv = "";
                                                }
                                                else{
                                                    $g_depanv = $gelarv->gelar_depan;
                                                    $g_belakangv = $gelarv->gelar_belakang;
                                                }

                                                echo "<b>".$g_depanv.$ta->nama.$g_depanv."</b>";
                                                echo "<br>";
                                                echo "$ta->nip_nik"; 
                                                
                                                ?>
                                            </td>
                                            <td class="align-top">
                                                <?php
                                                    if($ta->ket == 0){
                                                        echo "Menunggu";
                                                    }
                                                    elseif($ta->ket == 1){
                                                        echo "Menunggu Approval";
                                                    }
                                                    elseif($ta->ket == 2){
                                                        echo "ACC Pembimbing Utama";
                                                    }
                                                    elseif($ta->ket == 3){
                                                        echo "ACC Pembimbing Utama & Akademik";
                                                    }
                                                    elseif($ta->ket == 4){
                                                        echo "ACC Ketua Program Studi";
                                                    }
                                                    elseif($ta->ket == 5){
                                                        echo "Selesai";
                                                    }
                                                ?>
                                            </td>
                                            <td class="align-top">
                                                <?php
                                                    if($ta->ket == 0){
                                                        echo "-";
                                                    }
                                                    if($ta->ket >= 1){
                                                        echo "<li><a href=".site_url("mahasiswa/tugas-akhir/tema/form_pdf?jenis=verifikasi_ta&id=$ta->id_pengajuan").">Form Pengajuan Verifikasi TA</a></li>";   
                                                    }
                                                    if($ta->ket >= 5){
                                                        echo "<br>";
                                                        echo "<li><a href=".site_url("mahasiswa/tugas-akhir/tema/form_pdf?jenis=verifikasi_ta_nilai&id=$ta->id_pengajuan").">Nilai Verifikasi TA</a></li>";   
                                                    }
                                                ?>
                                            </td>
                                            <td class="align-top">
                                            <?php if($ta->ket == 0 ){?>
                                                <a data-toggle = "modal" data-id="<?php echo $ta->id_pengajuan ?>" class="passingID" >
                                                        <button type="button" class="btn-wide mb-1 btn btn-danger btn-sm btn-block"  data-toggle="modal" data-target="#Ajukan">
                                                            Ajukan
                                                        </button>
                                                </a>
                                            <?php } 
                                            elseif($ta->ket >= 1 && $ta->ket < 4){ echo "Menunggu"; } 
                                            elseif($ta->ket == 4) { 
                                                echo "Menunggu Penilaian Dosen Verifikasi";
                                            }
                                            else{echo "-";}
                                            ?>
                                            </td>
                                            
                                        </tr>
                                            <?php } ?>
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
                $("#ID").val( id );

            }); 
              
     
</script>