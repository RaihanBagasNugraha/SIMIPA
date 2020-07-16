

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-note icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <div>Kelola Seminar
                                        <div class="page-title-subheading">Lorem ipsum.
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
                                            <th>Jenis</th>
                                            <th>Npm</th>
                                            <th>Pelaksanaan</th>
                                            <th>Judul</th>
                                            
                                            <th>Berkas Lampiran</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if(empty($pa) && empty($approve))
                                        {
                                            echo "<tr><td colspan='6'>Data tidak tersedia</td></tr>";
                                        }
                                        else
                                        {
                                            foreach($pa as $row) {
                                                ?>
                                                    <tr>
                                                        <td class="align-top"><?php echo $row->jenis;?>                                                     
                                                        </td>
                                                        <td class="align-top"><?php echo $row->npm;?>
                                                        </td>
                                                        <td class="align-top"><?php echo "$row->tempat<br>$row->tgl_pelaksanaan<br>$row->waktu_pelaksanaan<br>";  ?>
                                                        </td>
                                                        <td class="align-top"><?php 
                                                        if($row->judul_approve == 1){echo $row->judul1;}
                                                        else{echo $row->judul2;}
                                                        ?>                                                         
                                                        </td>
                                                        
                                                        <td class="align-top">
                                                            <?php 
                                                                $lampiran = $this->ta_model->select_lampiran_by_seminar($row->id);
                                                                if(empty($lampiran)) {
                                                                    echo "<i>(Belum ada)</i>";
                                                                } else {
                                                                    echo "<ul style='margin-left: -20px;'>";
                                                                    foreach($lampiran as $rw) {
                                                                        $nama_berkas = $this->ta_model->get_berkas_name($rw->jenis_berkas);
                                                                        echo "<li><a href='".base_url($rw->file)."' download>".$nama_berkas."</a></li>";
                                                                    }
                    
                                                                    echo "</ul>";
                                                                }
                                                            ?>
                                                        </td>
                                                        <td class="align-top"><b>Pembimbing<br>Akademik</b></td>
                                                        <td class="align-top">
                                                        <a href="<?php echo site_url("dosen/tugas-akhir/seminar/approve-seminar/form?status=pa&id=".$this->encrypt->encode($row->id)) ?>" class="btn-wide mb-1 btn btn-primary btn-sm btn-block">Setujui
                                                        </a>

                                                
                                                        <a data-toggle = "modal" data-id="<?php echo $row->id."#$#$"."pa" ?>" class="passingIDPa" >
                                                            <button type="button" class="btn mb-2 btn-wide btn-danger btn-sm btn-block"  data-toggle="modal" data-target="#seminarTolak">
                                                                Perbaiki <?php  ?>
                                                            </button>
                                                        </a> 
                                                        </td>
                                                    </tr>
                                                <?php
                                                    }
                                            foreach($approve as $row) {
                                                        ?>
                                                            <tr>
                                                            <!-- <?php 
                                                            echo "<pre>";
                                                            print_r($approve);
                                                            ?> -->
                                                                <td class="align-top"><?php echo $row->jenis;?>                                                     
                                                                </td>
                                                                <td class="align-top"><?php echo $row->npm;?>
                                                                </td>
                                                                <td class="align-top"><?php echo "$row->tempat<br>$row->tgl_pelaksanaan<br>$row->waktu_pelaksanaan<br>";  ?>
                                                                </td>
                                                                <td class="align-top"><?php 
                                                                if($row->judul_approve == 1){echo $row->judul1;}
                                                                else{echo $row->judul2;}
                                                                ?>                                                         
                                                                </td>
                                                                
                                                                <td class="align-top">
                                                                    <?php 
                                                                        $lampiran = $this->ta_model->select_lampiran_by_seminar($row->id);
                                                                        if(empty($lampiran)) {
                                                                            echo "<i>(Belum ada, silakan lengkapi berkas lampiran)</i>";
                                                                        } else {
                                                                            echo "<ul style='margin-left: -20px;'>";
                                                                            foreach($lampiran as $rw) {
                                                                                $nama_berkas = $this->ta_model->get_berkas_name($rw->jenis_berkas);
                                                                                echo "<li><a href='".base_url($rw->file)."' download>".$nama_berkas."</a></li>";
                                                                            }
                            
                                                                            echo "</ul>";
                                                                        }
                                                                    ?>
                                                                </td>
                                                                <td class="align-top"><b><?php echo $row->status_slug ?></b></td>
                                                                <td class="align-top">
                                                                <?php 
                                                                    if($row->status_slug == "Pembimbing Utama"){
                                                                        $status = "pb1";
                                                                    }
                                                                    elseif($row->status_slug == "Penguji 1"){
                                                                        $status = "ps1";
                                                                    }
                                                                
                                                                ?>
                                                                

                                                                <a href="<?php echo site_url('dosen/tugas-akhir/seminar/approve-seminar/form?status='.$status.'&id='.$this->encrypt->encode($row->id)) ?>" class="btn-wide mb-1 btn btn-primary btn-sm btn-block">Setujui
                                                                </a>
                                                                
                                                               <?php if($row->status_slug == "Pembimbing Utama"){?>
                                                                    <a data-toggle = "modal" data-id="<?php echo $row->id."#$#$".$status ?>" class="passingIDP" >
                                                                        <button type="button" class="btn mb-2 btn-wide btn-danger btn-sm btn-block"  data-toggle="modal" data-target="#seminarTolak">
                                                                            Perbaiki <?php  ?>
                                                                        </button>
                                                                    </a> 
                                                                <?php } ?>
                                                                </td>
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
$(document).ready(function() {
    $('#example').DataTable();
} );

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

</script>

<script>
    $(".passingIDPa").click(function () {
                var id = $(this).attr('data-id');
                var data = id.split("#$#$");
                $("#ID").val( data[0] );
                $("#status").val( data[1] );

            });

    $(".passingIDP").click(function () {
                var id = $(this).attr('data-id');
                var data = id.split("#$#$");
                $("#ID").val( data[0] );
                $("#status").val( data[1] );

            });        
      
</script>
                        