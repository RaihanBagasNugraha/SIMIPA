

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
                                <?php if(empty($status_kp)) { ?>
                                <div class="page-title-actions">
                                    <a href="<?php echo site_url("mahasiswa/pkl/pkl-home/form") ?>" class="btn-shadow btn btn-success">
                                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-file fa-w-20"></i>
                                            </span>
                                            Form Pengajuan KP/PKL
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
                                            <th style="width: 10%;">TAHUN</th>
                                            <th style="width: 5%;">PERIODE</th>
                                            <th style="width: 20%;">LOKASI</th>
                                            <th style="width: 20%;">DOSEN<br>PEMBIMBING</th>
                                            <th style="width: 30%;">LAMPIRAN</th>
                                            <th style="width: 10%;">STATUS</th>
                                            <th style="width: 10%;">AKSI</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if(empty($kp))
                                        {
                                            echo "<tr><td colspan='6'>Data tidak tersedia</td></tr>";
                                        }
                                        else
                                        {
                                            foreach($kp as $row) {
                                                $periode_data = $this->pkl_model->get_pkl_kajur_by_id($row->id_periode);
                                        ?>
                                        <tr>
                                            <td class="align-top">
                                                <?php echo $periode_data->tahun; ?>
                                            </td>
                                            <td class="align-top">
                                                <?php echo $periode_data->periode; ?>
                                            </td>
                                            
                                            <td class="align-top">
                                                <?php echo $this->pkl_model->get_lokasi_pkl_by_id($row->id_lokasi)->lokasi; ?>
                                            </td>
                                            <td class="align-top">
                                                <?php 
                                                $dosen_pmb = $this->user_model->get_dosen_name($row->pembimbing);
                                                echo $row->pembimbing == NULL ? "<i>(Belum Disetujui)</i>" : $dosen_pmb->gelar_depan." ".$dosen_pmb->name.", ".$dosen_pmb->gelar_belakang; 
                                                ?>
                                            </td>
                                           
                                            <td class="align-top">
                                                <?php 
                                                    $lampiran = $this->pkl_model->select_lampiran_by_pkl($row->pkl_id, $this->session->userdata('username')); 
                                                    if(empty($lampiran)) {
                                                        echo "<i>(Belum ada, silakan lengkapi berkas lampiran)</i>";
                                                    } else {
                                                        $approval = $this->pkl_model->get_approval_koor_by_pkl_id($row->pkl_id);
                                                        if(!empty($approval)){
                                                            if($approval->file != NULL){
                                                                echo "<li><a href='".base_url($approval->file)."' download>".$approval->nama_file."</a></li>";
                                                            }
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
                                                    $status = "Approval Pembimbing Akademik";
                                                    break;
                                                    case "2":
                                                    $status = "Approval Staff Administrasi";
                                                    break;
                                                    case "3":
                                                    $status = "Approval Koordinator";
                                                    break;
                                                    case "4":
                                                    $status = "Approval Ketua Prodi";
                                                    break;
                                                    case "5":
                                                    $status = "Perbaiki";
                                                    break;
                                                    case "6":
                                                    $status = "Ditolak";
                                                    break;
                                                    case "7":
                                                    $status = "Approval Koordinator";
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
                                                <a data-toggle = "modal" data-id="<?php echo $row->pkl_id ?>" class="passingID2" >
                                                            <button type="button" class="btn-wide mb-1 btn btn-primary btn-sm btn-block"  data-toggle="modal" data-target="#Ajukanpkl">
                                                                Ajukan 
                                                            </button>
                                                </a>
                                            <?php } ?>
                                            <?php if($row->status == -1) { ?>
                                                <a href="<?php echo site_url("mahasiswa/pkl/pkl-home/form?aksi=ubah&id=".$this->encrypt->encode($row->pkl_id)) ?>" class="btn-wide mb-2 btn btn-warning btn-sm btn-block">Ubah
                                                </a>
                                                <a data-toggle = "modal" data-id="<?php echo $row->pkl_id ?>" class="passingID" >
                                                            <button type="button" class="btn mb-2 btn-wide btn-danger btn-sm btn-block"  data-toggle="modal" data-target="#delPengajuanPkl">
                                                                Hapus 
                                                            </button>
                                                </a>
                                                <a href="<?php echo site_url("mahasiswa/pkl/pkl-home/lampiran?id=".$this->encrypt->encode($row->pkl_id)) ?>" class="btn-wide mb-2 btn btn-focus btn-sm btn-block">Unggah Lampiran
                                                </a>
                                            <?php } 
                                            elseif($row->status == 5){ ?>
                                                    <a data-toggle = "modal" data-id="<?php echo $row->pkl_id ?>" ket_status="<?php echo $ket[1] ?>" class="passingIDPerbaikan" >
                                                            <button type="button" class="btn-wide mb-1 btn btn-primary btn-sm btn-block"  data-toggle="modal" data-target="#AjukanPerbaikanPkl">
                                                                Ajukan Perbaikan
                                                            </button>
                                                    </a>

                                                    <a href="<?php echo site_url("mahasiswa/pkl/pkl-home/form?aksi=ubah&id=".$this->encrypt->encode($row->pkl_id)) ?>" class="btn-wide mb-2 btn btn-warning btn-sm btn-block">Ubah
                                                    </a>

                                                    <a href="<?php echo site_url("mahasiswa/pkl/pkl-home/lampiran?id=".$this->encrypt->encode($row->pkl_id)) ?>" class="btn-wide mb-2 btn btn-focus btn-sm btn-block">Unggah Lampiran
                                                    </a>

                                            <?php    
                                            }
                                            elseif($row->status == 3){ 
                                                $approval = $this->pkl_model->get_approval_koor_by_pkl_id($row->pkl_id);

                                                if($approval->status == 1 && $approval->file != NULL){
                                            ?>
                                                 <a data-toggle = "modal" data-id="<?php echo $approval->approval_id ?>" class="passingIDInstansi" >
                                                    <button type="button" class="btn-wide mb-1 btn btn-primary btn-sm btn-block"  data-toggle="modal" data-target="#AjukanInstansi">
                                                            Ajukan
                                                    </button>
                                                </a>
                                                <a href="<?php echo site_url("mahasiswa/pkl/pkl-home/lampiran?aksi=lampiran&id=".$this->encrypt->encode($row->pkl_id)) ?>" class="btn-wide mb-2 btn btn-focus btn-sm btn-block">Unggah Form Penerimaan Instansi</a>
                                                <?php } elseif($approval->status == 1){ ?>
                                                    <a href="<?php echo site_url("mahasiswa/pkl/pkl-home/lampiran?aksi=lampiran&id=".$this->encrypt->encode($row->pkl_id)) ?>" class="btn-wide mb-2 btn btn-focus btn-sm btn-block">Unggah Form Penerimaan Instansi</a>
                                            <?php
                                                }
                                                else{echo "Menunggu Verifikasi Berkas";}
                                            }
                                            elseif($row->status == 4 || $row->status == 6){echo"Selesai";}
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

    $(".passingIDInstansi").click(function () {
                var id = $(this).attr('data-id');
                $("#IDInstansi").val( id );
    });  
              
     
</script>