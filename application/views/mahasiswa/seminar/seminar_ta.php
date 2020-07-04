

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-note icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <div>Kelola Seminar
                                        <div class="page-title-subheading">
                                        </div>
                                    </div>
                                </div>
                                <?php if(empty($status_ta)) { ?>
                                <div class="page-title-actions">
                                    <a href="<?php echo site_url("mahasiswa/tugas-akhir/seminar/form") ?>" class="btn-shadow btn btn-success">
                                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-file fa-w-20"></i>
                                            </span>
                                            Form Pengajuan Seminar
                                    </a>
                                </div>
                                <?php } ?>
                                
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
                        elseif(!empty($_GET['status']) && $_GET['status'] == 'gagal') {

                            echo '<div class="alert alert-danger fade show" role="alert">Terdapat Duplikasi Data</div>';
                        }
                        elseif(!empty($_GET['status']) && $_GET['status'] == 'berhasil') {

                            echo '<div class="alert alert-success fade show" role="alert">Data Berhasil Ditambahkan</div>';
                        }
                        ?>
                        
                         <div class="main-card mb-3 card">
                                <div class="card-body">
                                <div class="table-responsive">
                                    <table class="mb-0 table table-striped" id="example">
                                        <thead>
                                        <tr>
                                            <th style="width: 12%;">JENIS</th>
                                            <th style="width: 14%;">PELAKSANAAN</th>
                                            <th style="width: 23%;">JUDUL</th>
                                            <th style="width: 15%;">PEMBIMBING</th>
                                            <th style="width: 15%;">PEMBAHAS</th>
                                            <th style="width: 15%;">LAMPIRAN</th>
                                            <th style="width: 15%;">STATUS</th>
                                            <th style="width: 7%;">AKSI</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if(empty($seminar))
                                        {
                                            echo "<tr><td colspan='6'>Data tidak tersedia</td></tr>";
                                        }
                                        else
                                        {
                                            foreach($seminar as $row) {
                                        ?>
                                        <?php $ta = $this->ta_model->get_ta_by_id($row->id_tugas_akhir) ?>
                                        <tr>
                                             <td class="align-top">
                                                <?php echo "<b>$row->jenis</b>"; ?>
                                             </td>
                                             <td class="align-top">
                                                <?php echo "$row->tempat<br>$row->tgl_pelaksanaan<br>$row->waktu_pelaksanaan<br>";  ?>
                                             </td>
                                             <td class="align-top">
                                                <?php 
                                                    if($ta->judul_approve == 1){
                                                        echo $ta->judul1;
                                                    }
                                                    elseif($ta->judul_approve == 2){
                                                        echo $ta->judul2;
                                                    }
                                                ?>
                                             </td>
                                             <td class="align-top">
                                                <?php 
                                                    $komisi_pembimbing = $this->ta_model->get_pembimbing_ta($row->id_tugas_akhir);

                                                    foreach($komisi_pembimbing as $kom) {
                                                        echo "<b>$kom->status</b><br>";
                                                        echo "$kom->nama<br>";
                                                        echo "$kom->nip_nik<br>";
                                                    }
                                                ?>
                                             </td>
                                             <td class="align-top">
                                                <?php 
                                                    $komisi_penguji = $this->ta_model->get_penguji_ta($row->id_tugas_akhir);

                                                    foreach($komisi_penguji as $kom) {
                                                        echo "<b>$kom->status</b><br>";
                                                        echo "$kom->nama<br>";
                                                        echo "$kom->nip_nik<br>";
                                                    }
                                                ?>
                                             </td>
                                             <td class="align-top">
                                                <?php 
                                                    $lampiran = $this->ta_model->select_lampiran_by_seminar($row->id);
                                                    if(empty($lampiran)) {
                                                        echo "<i>(Belum ada, silakan lengkapi berkas lampiran)</i>";
                                                    } else {
                                                        
                                                       
                                                        echo "<ul style='margin-left: -20px;'>";
                                                        if($row->jenis != 'Seminar Tugas Akhir'){
                                                            if($row->status >= 0){
                                                                echo "<li><a href=".site_url("mahasiswa/tugas-akhir/seminar/form_pdf?jenis=pengajuan_seminar&id=$row->id").">Form Pengajuan</a></li>";
                                                            }
                                                            if($row->status >= 3){
                                                                echo "<li><a href=".site_url("mahasiswa/tugas-akhir/seminar/form_pdf?jenis=verifikasi_seminar&id=$row->id").">Form Verifikasi</a></li>";
                                                            }
                                                            if($row->status == 7 || $row->status == 4){
                                                                echo "<li><a href=".site_url("mahasiswa/tugas-akhir/seminar/form_pdf?jenis=undangan_seminar&id=$row->id").">Undangan Seminar</a></li>";
                                                            }
                                                        }
                                                        foreach($lampiran as $rw) {
                                                            $nama_berkas = $this->ta_model->get_berkas_name($rw->jenis_berkas);
                                                            echo "<li><a href='".base_url($rw->file)."' download>".$nama_berkas."</a></li>";
                                                        }
        
                                                        echo "</ul>";
                                                    }
                                                ?>
                                             
                                             </td>
                                             <td class="align-top">
                                                <?php 
                                                if($row->status == '-1') {
                                                        echo '<i>Belum diajukan</i>';
                                                    }
                                                
                                                if($row->status == '0') {
                                                        echo '<i>Menunggu Approval</i>';
                                                    }

                                                if($row->status == '1') {
                                                        echo '<i>Approval Pembimbing Akademik</i>';
                                                    } 
                                                
                                                if($row->status == '2') {
                                                        echo '<i>Approval Pembimbing Utama & Pembimbing Akademik</i>';
                                                    }  

                                                if($row->status == '3') {
                                                        echo '<i>Berkas Diverifikasi</i>';
                                                    }   
                                                
                                                if($row->status == '4') {
                                                        echo '<i>Disetujui</i>';
                                                    }   
                                                    
                                                if($row->status == '5') {
                                                        echo '<i>Perbaiki</i>';
                                                        $ket = explode("#",$row->keterangan_tolak);
                                                        echo "<br><br>".$ket[1];
                                                        $ket_status = $ket[0];
                                                    }    
                                                        
                                                if($row->status == '6') {
                                                        echo '<i>Ditolak</i>';
                                                        $ket = explode("#",$row->keterangan_tolak);
                                                        echo "<br><br>".$ket[1];
                                                    }    
                                                if($row->status == '7'){
                                                        echo '<i>Approval Koordinator<br><br></i>';

                                                        echo '<i>Menunggu Acc Ketua Jurusan</i>';
                                                }            

                                                if($row->status == '9'){
                                                    echo '<i>Penilaian<br><br></i>';
                                                }      
                                                ?>
                                             </td>
                                             <td class="align-top">
                                                <?php
                                                if(!empty($lampiran) && $row->status == '-1') { ?>
                                                    <a data-toggle = "modal" data-id="<?php echo $row->id ?>" class="passingID2" >
                                                                <button type="button" class="btn-wide mb-1 btn btn-primary btn-sm btn-block"  data-toggle="modal" data-target="#AjukanSeminar">
                                                                    Ajukan <?php  ?>
                                                                </button>
                                                    </a>
                                                <?php } ?>

                                                <?php if($row->status == '-1') { ?>
                                                <a href="<?php echo site_url("mahasiswa/tugas-akhir/seminar/form?aksi=ubah&id=".$row->id) ?>" class="btn-wide mb-2 btn btn-warning btn-sm btn-block">Ubah
                                                </a>
                                                <a data-toggle = "modal" data-id="<?php echo $row->id ?>" class="passingID" >
                                                            <button type="button" class="btn mb-2 btn-wide btn-danger btn-sm btn-block"  data-toggle="modal" data-target="#delPengajuan">
                                                                Hapus 
                                                            </button>
                                                            </a>
                                                <a href="<?php echo site_url("mahasiswa/tugas-akhir/seminar/lampiran?id=".$row->id) ?>" class="btn-wide mb-2 btn btn-focus btn-sm btn-block">Unggah Lampiran
                                                </a>
                                                <?php } 
                                                elseif($row->status == 5){ ?>
                                                <a data-toggle = "modal" data-id="<?php echo $row->id ?>" ket_status = "<?php echo $ket_status ?>" class="passingIDPerbaikan" >
                                                            <button type="button" class="btn-wide mb-1 btn btn-primary btn-sm btn-block"  data-toggle="modal" data-target="#AjukanPerbaikan">
                                                                Ajukan <?php  ?>
                                                            </button>
                                                </a>

                                                <a href="<?php echo site_url("mahasiswa/tugas-akhir/seminar/form?aksi=ubah&id=".$row->id) ?>" class="btn-wide mb-2 btn btn-warning btn-sm btn-block">Ubah
                                                </a>

                                                <a href="<?php echo site_url("mahasiswa/tugas-akhir/seminar/lampiran?id=".$row->id) ?>" class="btn-wide mb-2 btn btn-focus btn-sm btn-block">Unggah Lampiran
                                                </a>

                                            <?php    
                                            }
                                                elseif($row->status == 4){echo "Selesai";}                                                
                                                else{echo "Menunggu";}  ?>
                                             
                                             
                                             
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
    $(".passingID").click(function () {
                var id = $(this).attr('data-id');
                $("#ID").val( id );

            });

     $(".passingID2").click(function () {
                var id = $(this).attr('data-id');
                $("#ID2").val( id );

            });      

     $(".passingIDPerbaikan").click(function () {
                var id = $(this).attr('data-id');
                var status = $(this).attr('ket_status');
                $("#IDPerbaikan").val( id );
                $("#Status").val( status );

            });                

              
     
</script>