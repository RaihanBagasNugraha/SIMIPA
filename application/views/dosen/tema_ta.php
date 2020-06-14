

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-note icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <div>Kelola Tema Penelitian
                                        <div class="page-title-subheading">Lorem ipsum.
                                        </div>
                                    </div>
                                </div>
                                <div class="page-title-actions">
                                    <a href="<?php echo site_url("mahasiswa/tugas-akhir/tema/form") ?>" class="btn-shadow btn btn-success">
                                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-file fa-w-20"></i>
                                            </span>
                                            Form Pengajuan Tema
                                    </a>
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
                                    <table class="mb-0 table table-striped">
                                        <thead>
                                        <tr>
                                            <th>Judul</th>
                                            <th>Komisi Pembimbing</th>
                                            <th>Komisi Pembahas</th>
                                            <th>Berkas Lampiran</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                       
                                        <?php
                                        if(empty($ta) && empty($pa) && empty($approve))
                                        {
                                            echo "<tr><td colspan='6'>Data tidak tersedia</td></tr>";
                                        }
                                        else
                                        {
                                            foreach($ta as $row) {
                                               
                                        ?>
                                            <tr>
                                                <td class="align-top">
                                                    <?php echo $row->judul1 ?> 

                                                    <?php if($row->judul2 != NULL){ echo "<br><br>$row->judul2"; } ?>
                                                </td>
                                                <td class="align-top">
                                                    <?php 
                                                        $dosen_pmb = $this->user_model->get_dosen_name($row->pembimbing1);
                                                        echo $dosen_pmb->gelar_depan." ".$dosen_pmb->name.", ".$dosen_pmb->gelar_belakang;
                                                    ?>
                                                </td>
                                                <td class="align-top">-</td>
                                                <td class="align-top">
                                                    <?php
                                                        $lampiran = $this->ta_model->select_lampiran_by_ta($row->id_pengajuan, $row->npm);
                                                        if(empty($lampiran)) {
                                                            echo "<i>(Tidak Ada Lampiran)</i>";
                                                        } else {
                                                            echo "<ul style='margin-left: -20px;'>";
                                                            foreach($lampiran as $rw) {
                                                                echo "<li><a href='".base_url($rw->file)."' download>".$rw->nama_berkas."</a></li>";
                                                            }

                                                            echo "</ul>";
                                                        }
                                                    ?>
                                                </td>
                                                <td class="align-top">
                                                <?php 
                                                    $dosen_pa = $this->ta_model->get_dosen_pa($row->npm);
                                                    
                                                    if($row->pembimbing1 == $akun->userId){
                                                        echo "Pembimbing 1";
                                                    }
                                                    // elseif($dosen_pa == $akun->userId && $row->pembimbing1 != $akun->userId){
                                                    //     echo "Pembimbing Akademik";
                                                    // }
                                                    // elseif($dosen_pa == $row->pembimbing1 && $row->pembimbing1 == $akun->userId){
                                                    //     echo "Pembimbing 1,<br>Pembimbing Akademik";
                                                    // }
                                                 
                                                ?></td>
                                                <td class="align-top">

                                                <a href="<?php echo site_url("dosen/tugas-akhir/tema/approve-ta/form?jenis=pb1&aksi=setuju&id=".$row->id_pengajuan) ?>" class="btn-wide mb-1 btn btn-primary btn-sm btn-block">Setujui
                                                </a>

                                                <!-- <a href="<?php echo site_url("dosen/tugas-akhir/tema/approve-ta/form?aksi=tolak&id=".$row->id_pengajuan) ?>" class="btn mb-2 btn-wide btn-danger btn-sm btn-block">Tolak
                                                </a> -->

                                                <!-- <a data-toggle = "modal" data-id="<?php echo $row->id_pengajuan."#$#$"."pb1" ?>" class="passingID3" >
                                                            <button type="button" class="btn-wide mb-1 btn btn-primary btn-sm btn-block"  data-toggle="modal" data-target="#Approval">
                                                                Setujui <?php  ?>
                                                            </button>
                                                </a>-->
                                                
                                                <a data-toggle = "modal" data-id="<?php echo $row->id_pengajuan."#$#$"."pb1" ?>" class="passingID4" >
                                                            <button type="button" class="btn mb-2 btn-wide btn-danger btn-sm btn-block"  data-toggle="modal" data-target="#ApprovalTolak">
                                                                Perbaiki <?php  ?>
                                                            </button>
                                                </a> 
                                                </td>
                                            </tr>
                                        <?php
                                            }
                                            foreach($pa as $row) {
                                                
                                                ?>
                                                
                                                    <tr>
                                                        <td class="align-top">
                                                            <?php echo $row->judul1 ?> 
        
                                                            <?php if($row->judul2 != NULL){ echo "<br><br>$row->judul2"; } ?>
                                                        </td>
                                                        <td class="align-top">
                                                            <?php 
                                                                $dosen_pmb = $this->user_model->get_dosen_name($row->pembimbing1);
                                                                echo $dosen_pmb->gelar_depan." ".$dosen_pmb->name.", ".$dosen_pmb->gelar_belakang;
                                                            ?>
                                                        </td>
                                                        <td class="align-top">-</td>
                                                        <td class="align-top">
                                                            <?php
                                                                $lampiran = $this->ta_model->select_lampiran_by_ta($row->id_pengajuan, $row->npm);
                                                                if(empty($lampiran)) {
                                                                    echo "<i>(Tidak Ada Lampiran)</i>";
                                                                } else {
                                                                    echo "<ul style='margin-left: -20px;'>";
                                                                    foreach($lampiran as $rw) {
                                                                        echo "<li><a href='".base_url($rw->file)."' download>".$rw->nama_berkas."</a></li>";
                                                                    }
        
                                                                    echo "</ul>";
                                                                }
                                                            ?>
                                                        </td>
                                                        <td class="align-top">
                                                        <?php 
                                                            $dosen_pa = $this->ta_model->get_dosen_pa($row->npm);
                                                            
                                                            
                                                                echo "Pembimbing Akademik";
                                                            
                                                            // elseif($dosen_pa == $akun->userId && $row->pembimbing1 != $akun->userId){
                                                            //     echo "Pembimbing Akademik";
                                                            // }
                                                            // elseif($dosen_pa == $row->pembimbing1 && $row->pembimbing1 == $akun->userId){
                                                            //     echo "Pembimbing 1,<br>Pembimbing Akademik";
                                                            // }
                                                         
                                                        ?></td>
                                                        <td class="align-top">
        
                                                        <a href="<?php echo site_url("dosen/tugas-akhir/tema/approve-ta/form?jenis=pa&aksi=setuju&id=".$row->id_pengajuan) ?>" class="btn-wide mb-1 btn btn-primary btn-sm btn-block">Setujui
                                                        </a>

                                                
                                                        <a data-toggle = "modal" data-id="<?php echo $row->id_pengajuan."#$#$"."pa" ?>" class="passingID4" >
                                                            <button type="button" class="btn mb-2 btn-wide btn-danger btn-sm btn-block"  data-toggle="modal" data-target="#ApprovalTolak">
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
                                                                <td class="align-top">

                                                                    <?php 
                                                                    if($row->judul_approve == 1){
                                                                        echo $row->judul1; 
                                                                    } else {echo $row->judul2;} ?> 
                
                                                            
                                                                </td>
                                                                <td class="align-top">
                                                                <?php 
                                                                    $komisi_pembimbing = $this->ta_model->get_pembimbing_ta($row->id_pengajuan);

                                                                     foreach($komisi_pembimbing as $kom) {
                                                                        echo "<b>$kom->status</b><br>";
                                                                        echo "$kom->name<br>";
                                                                        echo "$kom->nip_nik<br>";
                                                                        }
                                                                ?>
                                                                </td>
                                                                <td class="align-top">
                                                                <?php 
                                                                     $komisi_penguji = $this->ta_model->get_penguji_ta($row->id_pengajuan);

                                                                    foreach($komisi_penguji as $kom) {
                                                                        echo "<b>$kom->status</b><br>";
                                                                        echo "$kom->name<br>";
                                                                        echo "$kom->nip_nik<br>";
                                                                        }
                                                                ?>        
                                                                </td>
                                                                <td class="align-top">
                                                                    <?php
                                                                        $lampiran = $this->ta_model->select_lampiran_by_ta($row->id_pengajuan, $row->npm);
                                                                        if(empty($lampiran)) {
                                                                            echo "<i>(Tidak Ada Lampiran)</i>";
                                                                        } else {
                                                                            echo "<ul style='margin-left: -20px;'>";
                                                                            foreach($lampiran as $rw) {
                                                                                echo "<li><a href='".base_url($rw->file)."' download>".$rw->nama_berkas."</a></li>";
                                                                            }
                
                                                                            echo "</ul>";
                                                                        }
                                                                    ?>
                                                                </td>
                                                                <td class="align-top">
                                                                <?php 
                                                                    echo "$row->status_slug";
                                                                    if($row->status_slug == 'Pembimbing Utama'){
                                                                        $ket = "pb1";
                                                                    }
                                                                    elseif($row->status_slug == 'Pembimbing 2'){
                                                                        $ket = "pb2";
                                                                    }
                                                                    elseif($row->status_slug == 'Pembimbing 3'){
                                                                        $ket = "pb3";
                                                                    }
                                                                    elseif($row->status_slug == 'Penguji 1'){
                                                                        $ket = "ps1";
                                                                    }
                                                                    elseif($row->status_slug == 'Penguji 2'){
                                                                        $ket = "ps2";
                                                                    }
                                                                    elseif($row->status_slug == 'Penguji 3'){
                                                                        $ket = "ps3";
                                                                    }
                                                                 
                                                                ?></td>
                                                                <td class="align-top">
                
                                                                <a href="<?php echo site_url("dosen/tugas-akhir/tema/approve-ta/form?jenis=$ket&id=".$row->id_pengajuan) ?>" class="btn-wide mb-1 btn btn-primary btn-sm btn-block">Setujui
                                                                </a>
        
                                                                <!-- <a data-toggle = "modal" data-id="<?php echo $row->id_pengajuan."#$#$"."kom" ?>" class="passingID5" >
                                                                    <button type="button" class="btn mb-2 btn-wide btn-danger btn-sm btn-block"  data-toggle="modal" data-target="#ApprovalTolakKomisi">
                                                                        Tolak <?php  ?>
                                                                    </button>
                                                                </a>  -->
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

</script>

<script>
    $(".passingID3").click(function () {
                var id = $(this).attr('data-id');
                var data = id.split("#$#$");
                $("#ID3").val( data[0] );
                $("#status").val( data[1] );

            });

    $(".passingID4").click(function () {
                var id = $(this).attr('data-id');
                var data = id.split("#$#$");
                $("#ID4").val( data[0] );
                $("#status2").val( data[1] );

    });      
       
</script>
                        