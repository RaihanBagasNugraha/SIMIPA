

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-note icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                  
                                    <div>Data Prestasi Mahasiswa
                                        <div class="page-title-subheading">
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="page-title-actions">
                                    <a href="<?php echo site_url("mahasiswa/prestasi/surat-tugas") ?>" class="btn-shadow btn btn-success">
                                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fa fa-briefcase"></i>
                                            </span>
                                            Tambah Surat Tugas
                                    </a>

                                    <a href="<?php echo site_url("mahasiswa/prestasi/form-prestasi") ?>" class="btn-shadow btn btn-success">
                                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fa fa-star"></i>
                                            </span>
                                            Tambah Data Prestasi
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
                        
                         <div class="main-card mb-3 card">
                                <div class="card-body">
                                <div class="table-responsive">
                                    <table class="mb-0 table table-striped" id="example">
                                        <thead>
                                        <tr>
                                            <th style="width: 5%;">#</th>
                                            <th style="width: 25%;">Anggota</th>
                                            <th style="width: 25%;">Keterangan</th>
                                            <th style="width: 20%;">Surat Tugas</th>
                                            <th style="width: 10%;">Sertifikat</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if(empty($prestasi))
                                        {
                                            echo "<tr><td colspan='6'>Data tidak tersedia</td></tr>";
                                        }
                                        else
                                        {
                                            $n = 0;
                                            foreach($prestasi as $row) {
                                            $mahasiswa = $this->layanan_model->get_prestasi_anggota_by_id_npm($row->id_prestasi,$this->session->userdata('username'));
                                        ?>
                                        <tr>
                                            <td class="align-top">
                                                <?php echo ++$n ?>
                                            </td>

                                            <td class="align-top">
                                                <?php 
                                                    $anggota = $this->layanan_model->get_prestasi_anggota_by_id($row->id_prestasi);
                                                    if(!empty($anggota)){
                                                        foreach($anggota as $agt){
                                                            $mhs = $this->user_model->get_mahasiswa_data_npm($agt->anggota_npm);
                                                            if(!empty($mhs)){
                                                                echo "<li>".$agt->anggota_npm."-".$mhs->name."</li>";
                                                            }else{
                                                                echo "<li>".$agt->anggota_npm."</li>";
                                                            }
                                                        }
                                                    }else{
                                                        $anggota_surat = $this->layanan_model->get_surat_tugas_anggota($row->id_layanan);
                                                        echo "<li>".$anggota_surat[0]->ketua."-".$this->user_model->get_mahasiswa_data_npm($anggota_surat[0]->ketua)->name."</li>";
                                                        foreach($anggota_surat as $ags){
                                                            $mhs2 = $this->user_model->get_mahasiswa_data_npm($ags->npm);
                                                            if(!empty($mhs2)){
                                                                echo "<li>".$ags->npm."-".$mhs2->name."</li>";
                                                            }else{
                                                                echo "<li>".$ags->npm."</li>";
                                                            }
                                                        }
                                                    }
                                                        
                                                ?>
                                            </td>

                                            <td class="align-top">
                                                <?php 
                                                if($row->id_layanan != 0){
                                                    $keterangan = $this->layanan_model->get_keterangan_form($row->id_layanan);
                                                    
                                                    if(!empty($keterangan)){
                                                        foreach($keterangan as $ket){
                                                            echo "<b>$ket->nama : </b>$ket->meta_value<br>";
                                                        }
                                                    }
                                                    if($mahasiswa->sertifikat != null){
                                                        echo "<b>Jenis : </b>".$row->jenis."<br>";
                                                        echo "<b>Kegiatan : </b>".$row->kegiatan."<br>"; 
                                                        echo "<b>Tahun : </b>".$row->tahun."<br>";
                                                        echo "<b>Penyelenggara : </b>".$row->penyelenggara."<br>"; 
                                                        echo "<b>Tingkat : </b>".$row->tingkat."<br>"; 
                                                        echo "<b>Kategori : </b>".$row->kategori."<br>"; 
                                                        echo "<b>Capaian : </b>".$mahasiswa->capaian."<br>";
                                                    }
                                                }else{
                                                    echo "<b>Jenis : </b>".$row->jenis."<br>";
                                                    echo "<b>Kegiatan : </b>".$row->kegiatan."<br>"; 
                                                    echo "<b>Tahun : </b>".$row->tahun."<br>";
                                                    echo "<b>Penyelenggara : </b>".$row->penyelenggara."<br>"; 
                                                    echo "<b>Tingkat : </b>".$row->tingkat."<br>"; 
                                                    echo "<b>Kategori : </b>".$row->kategori."<br>"; 
                                                    echo "<b>Capaian : </b>".$mahasiswa->capaian."<br>";
                                                }
                                                 ?>
                                            </td>

                                            <td class="align-top">
                                               <?php 
                                                    if($row->id_layanan != 0){
                                                        $layanan = $this->layanan_model->get_form_mhs_id($row->id_layanan);
                                                    if(!empty($layanan)){
                                                        if($layanan->status == 0 && ($layanan->tingkat == null || $layanan->tingkat == "")){
                                                            echo "<span style='color:white;background-color:#d9534f' class='btn-sm'>Belum Diajukan</span>";
                                                        }elseif($layanan->status == 0 && ($layanan->tingkat != null || $layanan->tingkat != "") ){
                                                            echo "<span style='color:white;background-color:#f0ad4e' class='btn-sm'>Menunggu</span>";
                                                        }elseif($layanan->status == 1){
                                                            echo "<span style='color:white;background-color:#0275d8' class='btn-sm'>Verifikasi</span>";
                                                        }elseif($layanan->status == 2){
                                                            echo "<span style='color:white;background-color:#5cb85c' class='btn-sm'>Selesai</span>";
                                                        }elseif($layanan->status == 3){
                                                            echo "<span style='color:white;background-color:#d9534f' class='btn-sm'>Ditolak</span>";
                                                            echo "<br>";
                                                            echo "<b><i><span style='color:red'>Catatan : ".$layanan->keterangan."<span></i></b>";
                                                        }
                                                        echo "<br><br>";
                                                        if($layanan->status != 3){
                                                            $approval = $this->layanan_model->get_approval_layanan($layanan->id);
                                                            if(!empty($approval)){
                                                                foreach($approval as $app){
                                                                    // echo "<ul>";
                                                                    echo "<li><b>Disetujui ".$this->layanan_model->get_approver_by_id($app->approver_id)->nama." : </b></li>";
                                                                    // echo "<br>";
                                                                    if($app->approver_id > 9){
                                                                        echo substr($app->created_at,0,10);
                                                                    }else{
                                                                        if($app->updated_at == ""||$app->updated_at == null){
                                                                            echo "<span style='color:red'>Menunggu</span>";
                                                                        }else{
                                                                            echo substr($app->updated_at,0,10);
                                                                        }
                                                                        echo "<br><br>";
                                                                    }
                                                                    
                                                                }
                                                            }
                                                        }

                                                        
                                                        if(($layanan->tingkat == null || $layanan->tingkat == "") && $layanan->status < 1){
                                                ?>
                                                    <a href="<?php echo site_url("/mahasiswa/prestasi/surat-tugas-form/ajukan?id=".$this->encrypt->encode($row->id_layanan))  ?> " class="btn-wide mb-2 btn btn-primary btn-sm"><i class="fa fa-upload" aria-hidden="true"></i></a>
                                                    <!-- &emsp; -->
                                                    <a data-toggle = "modal" data-id="<?php echo $row->id_layanan ?>" class="passingID" >
                                                        <span type="button" class="btn-wide btn mb-2 btn-danger btn-sm "  data-toggle="modal" data-target="#delFormMhs">
                                                            <i class="fa fa-trash" aria-hidden="true"></i> 
                                                        </span>
                                                    </a>
                                                <?php
                                                    }//ditolak
                                                    elseif($layanan->status == 3){
                                                ?>
                                                        <a href="<?php echo site_url("/mahasiswa/layanan-fakultas/kemahasiswaan/unduh?id=".$layanan->id."&layanan=".$layanan->id_layanan_fakultas) ?>" class="btn-wide mb-2 btn btn-success btn-sm"><i class="fa fa-download" aria-hidden="true"></i></a>
                                                        <a href="<?php echo site_url("/mahasiswa/prestasi/surat-tugas-form/ajukan?id=".$this->encrypt->encode($layanan->id)."&aksi=perbaiki")  ?> " class="btn-wide mb-2 btn btn-primary btn-sm"><i class="fa fa-wrench" aria-hidden="true"></i></a>
                                                        <!-- &emsp; -->
                                                        <a data-toggle = "modal" data-id="<?php echo $layanan->id ?>"  class="passingID" >
                                                            <span type="button" class="btn-wide btn mb-2 btn-danger btn-sm "  data-toggle="modal" data-target="#delFormMhs">
                                                                <i class="fa fa-trash" aria-hidden="true"></i> 
                                                            </span>
                                                        </a>
                                                <?php
                                                    }else{ ?>
                                                        <a href="<?php echo site_url("/mahasiswa/layanan-fakultas/kemahasiswaan/unduh?id=".$layanan->id."&layanan=".$layanan->id_layanan_fakultas) ?>" class="btn-wide mb-2 btn btn-success btn-sm"><i class="fa fa-download" aria-hidden="true"></i></a>
                                                <?php 
                                                    }
                                                    }
                                                }else{
                                                    echo "-";
                                                }
                                               ?>
                                            </td>

                                            <td class="align-top">


                                            </td>
                                            <td class="align-top">
                                                <?php if($row->id_layanan == 0){ ?>
                                                    <a href="<?php echo base_url($mahasiswa->sertifikat) ?>" download class="btn-wide mb-2 btn btn-success btn-sm"><i class="fa fa-download" aria-hidden="true"></i></a>
                                                <?php }else{
                                                     if($layanan->status == 2 && $mahasiswa->sertifikat == null){
                                                ?>
                                                    <a href="<?php echo site_url("/mahasiswa/prestasi/form-prestasi?aksi=unggah&id=".$this->encrypt->encode($row->id_prestasi))  ?> " class="btn-wide mb-2 btn btn-danger btn-sm">Sertifikat <br><i class="fa fa-upload" aria-hidden="true"></i></a>
                                                <?php 
                                                   }elseif($layanan->status != 2 && $mahasiswa->sertifikat == null){
                                                       echo "-";
                                                    }else{ ?>
                                                    <a href="<?php echo base_url($mahasiswa->sertifikat) ?>" download class="btn-wide mb-2 btn btn-success btn-sm"><i class="fa fa-download" aria-hidden="true"></i></a>
                                                <?php
                                                   }
                                                } ?>
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
                var jns = $(this).attr('data-jns');
                $("#ID").val( id );
                $("#Jns").val( jns );
            });              
     
</script>