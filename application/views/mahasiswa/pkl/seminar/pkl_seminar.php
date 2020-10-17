

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-note icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <div>Kelola KP/PKL
                                        <div class="page-title-subheading">
                                        </div>
                                    </div>
                                </div>
                                <?php if(empty($seminar_cek)) { ?>
                                <div class="page-title-actions">
                                    <a href="<?php echo site_url("mahasiswa/pkl/seminar/form") ?>" class="btn-shadow btn btn-success">
                                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-file fa-w-20"></i>
                                            </span>
                                            Form Pengajuan Seminar KP/PKL
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
                        ?>
                        
                         <div class="main-card mb-3 card">
                                <div class="card-body">
                                <div class="table-responsive">
                                    <table class="mb-0 table table-striped" id="example">
                                        <thead>
                                        <tr>
                                            <th style="width: 10%;">TAHUN<br>PERIODE</th>
                                            <th style="width: 30%;">JUDUL</th>
                                            <th style="width: 20%;">PELAKSANAAN</th>
                                            <th style="width: 20%;">DOSEN<br>PEMBIMBING</th>
                                            <th style="width: 30%;">LAMPIRAN</th>
                                            <th style="width: 10%;">STATUS</th>
                                            <th style="width: 10%;">AKSI</th>
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
                                                
                                                // $periode_data = $this->pkl_model->get_pkl_kajur_by_id($row->id_periode);
                                        ?>
                                        <tr>
                                            <td class="align-top">
                                               <?php  
                                                $periode = $this->pkl_model->get_pkl_kajur_by_id($pkl->id_periode);
                                                echo "$periode->tahun / $periode->periode";
                                               ?>
                                            </td>
                                            <td class="align-top">
                                               <?php echo $row->judul; ?>
                                            </td>
                                            <td class="align-top">
                                                <?php
                                                    echo "$row->tempat<br>$row->tgl_pelaksanaan<br>$row->waktu_pelaksanaan<br>"
                                                ?>
                                            </td>
                                            <td class="align-top">
                                               <?php 
                                                    $dosen_pmb = $this->user_model->get_dosen_name($pkl->pembimbing);
                                                    echo $dosen_pmb->gelar_depan." ".$dosen_pmb->name.", ".$dosen_pmb->gelar_belakang;
                                                    echo "<br><br>";
                                                    echo "<b>Pembimbing Lapangan</b>";
                                                    $pb_lp = $this->pkl_model->get_pb_lapangan($row->pkl_id);
                                                    if(!empty($pb_lp)){
                                                        echo "<br>";
                                                        echo "$pb_lp->nama";
                                                    }
                                                    else{
                                                        echo "<br>";
                                                        echo "-";
                                                    }
                                                 
                                                ?>
                                               
                                            </td>
                                            <td class="align-top">
                                               <?php 
                                                $lampiran = $this->pkl_model->select_lampiran_seminar_kp($row->seminar_id);
                                                if(empty($lampiran)) {
                                                    echo "<i>(Belum ada, silakan lengkapi berkas lampiran)</i>";
                                                } else {
                                                    echo "<ul style='margin-left: -20px;'>";
                                                    //penilaian seminar
                                                    if($row->status == 9 ){
                                                        echo "<li><a href=".site_url("mahasiswa/tugas-akhir/seminar-pkl/form_pdf?jenis=form_penilaian_kp&id=$row->seminar_id").">Form Penilaian Seminar KP/PKL</a></li>"; 
                                                        echo "<li><a href=".site_url("mahasiswa/tugas-akhir/seminar-pkl/form_pdf?jenis=berita_acara_kp&id=$row->seminar_id").">Form Berita Acara</a></li>"; 
                                                    }

                                                    if($row->status >= 0 && $row->status != 6){
                                                        echo "<li><a href=".site_url("mahasiswa/tugas-akhir/seminar-pkl/form_pdf?jenis=form_pengajuan_seminar_kp&id=$row->seminar_id").">Form Pengajuan Seminar KP/PKL</a></li>"; 
                                                    }
                                                    if($row->status >= 2 && $row->status != 6){
                                                        echo "<li><a href=".site_url("mahasiswa/tugas-akhir/seminar-pkl/form_pdf?jenis=form_verifikasi_seminar_kp&id=$row->seminar_id").">Form Verifikasi Seminar KP/PKL</a></li>"; 
                                                    }
                                                    if($row->status >= 4 && $row->status != 6 && $row->status != 5){
                                                        echo "<li><a href=".site_url("mahasiswa/tugas-akhir/seminar-pkl/form_pdf?jenis=undangan_seminar_kp&id=$row->seminar_id").">Undangan KP/PKL</a></li>"; 
                                                    }

                                               

                                                    
                                                    foreach($lampiran as $rw) {
                                                        echo "<li><a href='".base_url($rw->file)."' download>".$rw->nama_berkas."</a></li>";
                                                    }
    
                                                    echo "</ul>";
                                                }
                                               ?>
                                            </td>
                                            <td class="align-top">
                                            <?php 
                                                switch($row->status){
                                                    case "-1":
                                                    $status = "Belum Diajukan";
                                                    break;
                                                    case "0":
                                                    $status = "Diajukan";
                                                    break;
                                                    case "1":
                                                    $status = "Approval Pembimbing KP/PKL";
                                                    break;
                                                    case "2":
                                                    $status = "Approval Staff Administrasi";
                                                    break;
                                                    case "3":
                                                    $status = "Approval Koordinator";
                                                    break;
                                                    case "4":
                                                    $status = "Approval Ketua Jurusan/Kaprodi";
                                                    break;
                                                    case "5":
                                                    $status = "Perbaiki";
                                                    break;
                                                    case "6":
                                                    $status = "Ditolak";
                                                    break;
                                                    case "7":
                                                    $status = "Penilaian Seminar";
                                                    break;
                                                    case "8":
                                                    $status = "Approval Nilai Oleh Koordinator";
                                                    break;
                                                    case "9":
                                                    $status = "Approval Nilai Oleh Ketua Jurusan";
                                                    break;
                                                }
                                                

                                                echo "<i>".$status."</i>"; 
                                                if($status == "Perbaiki" || $status == "Ditolak"){
                                                    echo "<br><br>";
                                                    $ket = explode("$#$", $row->keterangan_tolak);
                                                    echo "<b>$ket[0]</b>"; 
                                                }
                                                
                                                
                                                ?>
                                            </td>
                                            <td class="align-top">
                                                <?php if(!empty($lampiran) && $row->status == '-1') { ?>
                                                    <a data-toggle = "modal" data-id="<?php echo $row->seminar_id ?>" class="passingID2" >
                                                                <button type="button" class="btn-wide mb-1 btn btn-primary btn-sm btn-block"  data-toggle="modal" data-target="#AjukanSeminarpkl">
                                                                    Ajukan 
                                                                </button>
                                                    </a>
                                                <?php } ?>
                                                <?php if($row->status == -1) { ?>
                                                    <a href="<?php echo site_url("mahasiswa/pkl/seminar/form?aksi=ubah&id=".$this->encrypt->encode($row->seminar_id)) ?>" class="btn-wide mb-2 btn btn-warning btn-sm btn-block">Ubah
                                                    </a>
                                                    <a data-toggle = "modal" data-id="<?php echo $row->seminar_id ?>" class="passingID" >
                                                                <button type="button" class="btn mb-2 btn-wide btn-danger btn-sm btn-block"  data-toggle="modal" data-target="#delSeminarPkl">
                                                                    Hapus 
                                                                </button>
                                                    </a>
                                                    <a href="<?php echo site_url("mahasiswa/pkl/seminar/lampiran?id=".$this->encrypt->encode($row->seminar_id)) ?>" class="btn-wide mb-2 btn btn-focus btn-sm btn-block">Unggah Lampiran
                                                    </a>
                                                <?php } 
                                                elseif($row->status == 5){ ?>
                                                        <a data-toggle = "modal" data-id="<?php echo $row->seminar_id ?>" ket_status="<?php echo $ket[1] ?>" class="passingIDPerbaikan" >
                                                                <button type="button" class="btn-wide mb-1 btn btn-primary btn-sm btn-block"  data-toggle="modal" data-target="#AjukanSeminarPerbaikanPkl">
                                                                    Ajukan Perbaikan
                                                                </button>
                                                        </a>

                                                        <a href="<?php echo site_url("mahasiswa/pkl/seminar/form?aksi=ubah&id=".$this->encrypt->encode($row->seminar_id)) ?>" class="btn-wide mb-2 btn btn-warning btn-sm btn-block">Ubah
                                                        </a>

                                                        <a href="<?php echo site_url("mahasiswa/pkl/pkl-home/lampiran?id=".$this->encrypt->encode($row->seminar_id)) ?>" class="btn-wide mb-2 btn btn-focus btn-sm btn-block">Unggah Lampiran
                                                        </a>

                                                <?php    
                                                }
                                                elseif($row->status == 6 || $row->status == 9){echo "Selesai";}
                                                elseif($row->status == 4){echo "Pelaksanaan Seminar";}
                                                elseif($row->status == 0){echo "Menunggu";}

                                                else{echo "Menunggu";} ?>
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
            $("#SeminarID").val( id );
        });
    $(".passingID2").click(function () {
            var id = $(this).attr('data-id');
            $("#SmrID").val( id );
        });
    $(".passingIDPerbaikan").click(function () {
            var id = $(this).attr('data-id');
            var sts = $(this).attr('ket_status');
            $("#SemID").val( id );
            $("#Sts").val( sts );
        });
             
     
</script>