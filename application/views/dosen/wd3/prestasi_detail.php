

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-note icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <?php 
                                        $seg = $this->uri->segment(3);
                                        if($seg == "akademik"){
                                            $jns = "Akademik";
                                        }elseif($seg == "non-akademik"){
                                            $jns = "Non Akademik";
                                        }else{
                                            $jns = "";
                                        }

                                        $tahun = $this->input->get('tahun');
                                        $jurusan = $this->input->get('jurusan');
                                        
                                        switch($jurusan){
                                            case "1":
                                            $jur = "Kimia";
                                            break;
                                            case "2":
                                            $jur = "Biologi";
                                            break;
                                            case "3":
                                            $jur = "Matematika";
                                            break;
                                            case "4":
                                            $jur = "Fisika";
                                            break;
                                            case "5":
                                            $jur = "Ilmu Komputer";
                                            break;
                                            case "":
                                            $jur ="";
                                            break;
                                        }

                                    ?>
                                    <div>Prestasi <?php echo $jns; ?> Mahasiswa
                                        <div class="page-title-subheading">Jurusan <?php echo $jur ?> Tahun <?php echo $tahun ?>
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
                                            <th style="width: 10%;">Tingkat</th>
                                            <th style="width: 25%;">Kegiatan/<br>Penyelenggara</th>
                                            <th style="width: 10%;">Kategori</th>
                                            <th style="width: 30%;">Anggota</th>
                                            <th style="width: 15%;">Capaian</th>
                                            <th style="width: 5%;">Sertifikat</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if(empty($id_prestasi))
                                        {
                                            echo "<tr><td colspan='6'>Data tidak tersedia</td></tr>";
                                        }
                                        else
                                        {
                                            $n = 0;
                                            foreach($id_prestasi as $row) {
                                              $prestasi = $this->layanan_model->get_prestasi_by_id($row->id_prestasi);
                                        ?>
                                        <tr>
                                            <td class="align-top">
                                                <?php echo ++$n; ?>
                                            </td>

                                            <!-- tingkat -->
                                            <td class="align-top">
                                                <?php echo $prestasi->tingkat; ?>
                                            </td>

                                            <!-- kegiatan -->
                                            <td class="align-top">
                                                <?php 
                                                    echo "<b>$prestasi->kegiatan</b>/";
                                                    // echo "<br>";
                                                    echo $prestasi->penyelenggara;
                                                ?>
                                            </td>

                                            <!-- kategori -->
                                            <td class="align-top">
                                                <?php echo $prestasi->kategori; ?>
                                            </td>

                                            <!-- anggota -->
                                            <td class="align-top">
                                                <?php 
                                                    $anggota = $this->layanan_model->get_prestasi_anggota_by_id($row->id_prestasi);
                                                    foreach ($anggota as $agt){
                                                        $mhs = $this->user_model->get_mahasiswa_data_npm($agt->anggota_npm);
                                                        if(!empty($mhs)){
                                                            echo "<li>".$agt->anggota_npm."-".$mhs->name."</li>";
                                                        }else{
                                                            echo "<li>".$agt->anggota_npm."</li>";
                                                        }
                                                    }
                                                
                                                ?>
                                            </td>

                                            <!-- capaian -->
                                            <td class="align-top">
                                                <?php
                                                    if($prestasi->kategori == "Individu"){
                                                        foreach ($anggota as $cpn){
                                                            if($cpn->capaian == ""){
                                                                echo "<li> - </li>";
                                                            }else{
                                                                echo "<li>".$cpn->capaian."</li>";
                                                            }
                                                        } 
                                                    }else{
                                                        echo "<li>".$anggota[0]->capaian."</li>";
                                                    }
                                                ?>
                                            </td>

                                            <!-- sertifikat -->
                                            <td class="align-top">
                                                <?php 
                                                    if($prestasi->kategori == "Individu"){
                                                        foreach ($anggota as $srt){
                                                            if($srt->sertifikat == null){
                                                                echo "<li><p style='color:red'> Belum Unggah </p></li>";
                                                            }else{    
                                                ?>
                                                        <li><a href="<?php echo base_url($srt->sertifikat) ?>" download class="">Unduh</a></li>
                                                <?php 
                                                        }
                                                    }        
                                                    }else{?>
                                                        <li><a href="<?php echo base_url($anggota[0]->sertifikat) ?>" download class="">Unduh</a></li>
                                                <?php        
                                                    }
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
     
</script>