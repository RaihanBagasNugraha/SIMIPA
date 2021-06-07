

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-note icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    
                                    <div>List Beasiswa Aktif
                                        <div class="page-title-subheading">Pilih Beasiswa Aktif
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

                            echo '<div class="alert alert-success fade show" role="alert">Data Berhasil Disimpan</div>';
                        }
                        ?>
                            
                         <div class="main-card mb-3 card">
                                <div class="card-body">
                                <div class="table-responsive">
                                    <table class="mb-0 table table-striped" id="example">
                                        <thead>
                                        <tr>
                                            <th style="width: 5%;">No</th>
                                            <th style="width: 15%;">Beasiswa</th>
                                            <th style="width: 15%;">Penyelenggara</th>
                                            <th style="width: 38%;">Keterangan</th>
                                            <th style="width: 15%;">Status</th>
                                            <th style="width: 16%;">Aksi</th>
                                        </tr>
                                      
                                        </thead>
                                        <tbody>
                                        <?php
                                        if(empty($beasiswa))
                                        {
                                            echo "<tr><td colspan='6'>Data tidak tersedia</td></tr>";
                                        }
                                        else
                                        {
                                            $no = 0;
                                            foreach($beasiswa as $row) {
                                        ?>
                                        <tr>
                                            <td class="align-top"><?php echo ++$no; ?></td>  
                                            <td class="align-top"><?php echo $row->nama; ?></td>
                                            <td class="align-top"><?php echo $row->penyelenggara; ?></td>
                                            <td class="align-top">
                                                <?php  
                                                    $cek = $this->layanan_model->get_beasiswa_mhs_by_npm_bea($this->session->userdata('username'),$row->id);
                                                   
                                                    if(empty($cek)){
                                                        echo "Belum Mendaftar";
                                                    }else{
                                                        $cek_berkas = $this->layanan_model->get_lampiran_beasiswa($cek->id);
                                                        $form = $this->layanan_model->get_form_mhs_id($cek->id_layanan_fakultas_mahasiswa);
                                                        $keterangan = $this->layanan_model->get_keterangan_form($cek->id_layanan_fakultas_mahasiswa);
                                                        $m = 1;
                                                        if(!empty($keterangan)){
                                                            foreach($keterangan as $ket){
                                                                echo "<b>$m. $ket->nama : </b>$ket->meta_value<br>";
                                                                $m++;
                                                            }
                                                        }
                                                        
                                                    }
                                                ?>
                                            </td>
                                            <td class="align-top">
                                                <?php 
                                                       if(empty($cek)){
                                                            echo "-";
                                                       }else{
                                                            if($cek->status == 0){
                                                                echo "Belum Mendaftar";
                                                                echo "<br><br>";
                                                                if(empty($cek_berkas)){
                                                                    echo "<i>Silahkan Unggah Lampiran Pendukung</i>";
                                                                }
                                                            }elseif($cek->status == 1){
                                                                    echo "<i>Menunggu Persetujuan Pembimbing Akademik</i>";
                                                            }elseif($cek->status == 2){
                                                                    echo "<b>Terdaftar</b>";
                                                                    echo "<br><br>";
                                                                    echo "<i>Silahkan Unduh dan Tandatangani Form Beasiswa Kemudian Ajukan Melalui Loket Fakultas</i>";
                                                            }elseif($cek->status == 4){
                                                                echo "<i>Pengajuan Beasiswa Dinyatakan <b><span style='color:blue'>Diterima</span></b></i>";
                                                            }
                                                            elseif($cek->status == 3){
                                                                echo "<i>Pengajuan Beasiswa Dinyatakan <b><span style='color:red'>Belum Diterima</span></b></i>";
                                                            }
                                                            
                                                       }
                                                
                                                ?>
                                            </td>
                                            <td class="align-top">
                                            <?php  if(empty($cek)){  ?>
                                                <a href="<?php echo site_url("mahasiswa/tambah_beasiswa/$row->id") ?>" class="btn-shadow btn btn-success">
                                                        <span class="btn-icon-wrapper pr-2 opacity-7">
                                                            <i class="fas fa-paper-plane fa-w-20"></i>
                                                        </span>
                                                        Daftar
                                                </a>
                                            <?php } else { 
                                                if($cek->status == 0){
                                                    
                                                    if(!empty($cek_berkas)){ 
                                            ?>
                                                <!-- <a data-toggle = "modal" data-id="<?php //echo $cek->id ?>" class="passingID1" >
                                                    <button type="button" class="btn mb-2 btn-primary btn-sm btn-block"  data-toggle="modal" data-target="#AjukanBeasiswaMhs">
                                                        <span class="btn-icon-wrapper pr-2 opacity-7"><i class="fas fa-paper-plane fa-w-20"></i></span>Ajukan
                                                    </button>
                                                </a> -->

                                            <?php } ?>
                                                <!-- <a href="<?php //echo site_url("mahasiswa/tambah_beasiswa/$row->id?aksi=ubah&id=".$this->encrypt->encode($cek->id)) ?>" class="btn-shadow btn mb-2 btn-block btn-warning">
                                                        <span class="btn-icon-wrapper pr-2 opacity-7"><i class="fas fa-edit fa-w-20"></i></span>Ubah
                                                </a> -->

                                                <a href="<?php echo site_url("/mahasiswa/beasiswa/ajukan?id=".$this->encrypt->encode($cek->id_layanan_fakultas_mahasiswa))  ?> " class="btn-wide mb-2 btn btn-block btn-primary btn-sm">
                                                    <span class="btn-icon-wrapper pr-2 opacity-7"><i class="fas fa-paper-plane fa-w-20"></i></span>Daftar
                                                </a>

                                                <a data-toggle = "modal" data-id="<?php echo $cek->id_layanan_fakultas_mahasiswa ?>" class="passingID" >
                                                    <button type="button" class="btn mb-2 btn-danger btn-sm btn-block"  data-toggle="modal" data-target="#HapusBeasiswaMhs">
                                                        <span class="btn-icon-wrapper pr-2 opacity-7"><i class="fas fa-trash fa-w-20"></i></span>Hapus
                                                    </button>
                                                </a>

                                                <!-- <a href="<?php //echo site_url("mahasiswa/beasiswa/lampiran?id=".$this->encrypt->encode($cek->id)) ?>" class="btn-shadow btn btn-dark btn-block">
                                                        <span class="btn-icon-wrapper pr-2 opacity-7"><i class="fas fa-file fa-w-20"></i></span>Unggah Lampiran
                                                </a> -->

                                            <?php 
                                                }else{ 
                                                if($form->status != 2){
                                                    echo "-";
                                                }else{
                                            ?>
                                                <a href="<?php echo site_url("/mahasiswa/layanan-fakultas/kemahasiswaan/unduh?id=".$this->encrypt->encode($cek->id_layanan_fakultas_mahasiswa)."&layanan=26") ?>" class="btn-wide mb-2 btn btn-success btn-sm"><i class="fa fa-download" aria-hidden="true"></i></a>

                                            <?php       }
                                                    }
                                                } ?>
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
$(document).ready(function() {
    $('#example').DataTable();
} );

</script>

<script>
    $(".passingID").click(function () {
        var id = $(this).attr('data-id');
        $("#id_hapus_beasiswa_mhs").val(id);
    });    

     $(".passingID1").click(function () {
        var id = $(this).attr('data-id');
        $("#id_ajukan_beasiswa_mhs").val(id);
    });               
     
</script>