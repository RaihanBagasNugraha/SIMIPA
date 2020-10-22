

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-note icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <?php
                                    $data_dosen = $this->user_model->get_dosen_data($this->session->userdata('userId'));
                                    
                                    if($data_dosen->jurusan != NULL){
                                    switch($data_dosen->jurusan)
                                        {
                                            case "0":
                                            $jur_dosen = "Dokter MIPA";
                                            break;
                                            case "1":
                                            $jur_dosen = "Kimia";
                                            break;
                                            case "2":
                                            $jur_dosen = "Biologi";
                                            break;
                                            case "3":
                                            $jur_dosen = "Matematika";
                                            break;
                                            case "4":
                                            $jur_dosen = "Fisika";
                                            break;
                                            case "5":
                                            $jur_dosen = "Ilmu Komputer";
                                            break;
                                        }
                                    }
                                    else{
                                        $jur_dosen = "";
                                    }

                                    $id_user = $this->session->userdata('userId');
                                    ?>

                                    <div>Rekap KP/PKL Mahasiswa Jurusan <?php echo $jur_dosen; ?>
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
                        <?php 
                        if ($akun->ttd == NULL){
                            echo "<script>
                            alert('Silahkan Lengkapi Informasi Akun & Biodata Anda Terlebih Dahulu');
                            window.location.href='biodata';
                            </script>";
                        } 
                        
                        ?>
                        
                         <div class="main-card mb-3 card">
                                <div class="card-body">
                                <div class="table-responsive">
                                    <table class="mb-0 table table-striped" id="example">
                                        <thead>
                                        <tr>
                                            <!-- <th>No</th> -->
                                            <th>Angkatan</th>
                                            <th>Strata</th>
                                            <th>Jumlah<br>Mahasiswa</th>
                                            <th>KP/PKL</th>
                                            <th>Detail</th>
                                            <!-- <th>Persentase</th> -->
                                            
                                           
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                      
                                            $n = 1;
                                            $tahun = date("y");
                                            for($i=$tahun; $i>=14; $i--) {
                                        ?>
                                            <tr>
                                                <td style="border-bottom:1pt solid black;"><b style="font-size: 20px;"><br><br>20<?php echo $i;?></b></td>
                                                <td style="border-bottom:1pt solid black;"><b>D3</b><br><br><b>S1</b></td>
                                                <td style="border-bottom:1pt solid black;"><b><?php echo $this->pkl_model->get_jml_mahasiswa_angkatan($id_user,$i,'0','0');?></b><br><br><b><?php echo $this->pkl_model->get_jml_mahasiswa_angkatan($id_user,$i,'1','5');?></b></td>
                                                <td style="border-bottom:1pt solid black;"><b><?php echo $this->pkl_model->get_jml_mahasiswa_pkl($id_user,$i,'0','0');?></b><br><br><b><?php echo $this->pkl_model->get_jml_mahasiswa_pkl($id_user,$i,'1','5');?></b></td>
                                                <td style="border-bottom:1pt solid black;"><a style="color: white;"  class="btn-wide btn btn-primary " href="<?php echo site_url("dosen/rekap-pkl/mahasiswa/detail?strata=d3&angkatan=$i")?>"><b>Lihat</b></a><br><br><a style="color: white;"  class="btn-wide btn btn-primary" href="<?php echo site_url("dosen/rekap-pkl/mahasiswa/detail?strata=s1&angkatan=$i")?>"><b>Lihat</b></a></td>
                                            </tr>
                                        <?php
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
    $('#example').DataTable( {
        "order": [[ 3, "desc" ]]
    } );
    
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

<script>
    $(".passingIDKoor").click(function () {
                var id = $(this).attr('data-id');
                $("#IDKoor").val( id );

            });
      
</script>
                        