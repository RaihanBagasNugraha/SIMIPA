

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-note icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    
                                    <div>Setujui Beasiswa
                                        <div class="page-title-subheading">
                                            Setujui atau Tolak Pengajuan Beasiswa Mahasiswa
                                        </div>
                                    </div>
                                </div>
                                <div class="page-title-actions">
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
                                <h5><b>Nama Beasiswa : </b><?php echo $beasiswa->nama ?></h5>
                                <div class="table-responsive">
                                    <table class="mb-0 table table-striped" id="example">
                                        <thead>
                                        <tr>
                                            <th style="width: 5%;">No</th>
                                            <th style="width: 20%;">Nama<br>NPM</th>
                                            <th style="width: 10%;">Jurusan</th>
                                            <th style="width: 35%;">Keterangan</th>
                                            <th style="width: 20%;">Lampiran</th>
                                            <th style="width: 10%;">Aksi</th>
                                        </tr>
                                      
                                        </thead>
                                        <tbody>
                                        <?php
                                        if(empty($pendaftar))
                                        {
                                            echo "<tr><td colspan='6'>Data tidak tersedia</td></tr>";
                                        }
                                        else
                                        {
                                            $no = 0;
                                            foreach($pendaftar as $row) {
                                        ?>
                                        <tr>
                                            <td class="align-top"><?php echo ++$no; ?></td>
                                            <td class="align-top">
                                                <?php 
                                                    echo $this->user_model->get_mahasiswa_name($row->npm);
                                                    echo "<br>";
                                                    echo $row->npm; 
                                                ?>
                                            </td>
                                            <td class="align-top">
                                                <?php 
                                                    echo $this->user_model->get_jurusan_nama($row->npm);
                                                ?>
                                            </td>
                                            <td class="align-top">
                                                <?php 
                                                    $keterangan = $this->layanan_model->get_keterangan_form($row->id_layanan_fakultas_mahasiswa);
                                                    $m = 1;
                                                    foreach($keterangan as $ket){
                                                        echo "<b>$m. $ket->nama : </b>$ket->meta_value<br>";
                                                        $m++;
                                                    }
                                                ?>
                                            </td>
                                            <td class="align-top">
                                                <?php 
                                                    // echo "<li><a href='".site_url('/mahasiswa/layanan-fakultas/kemahasiswaan/unduh?id='.$row->id_layanan_fakultas_mahasiswa.'&layanan=26')."'>Form Beasiswa Lengkap</a></li>";
                                                     $lampiran = $this->layanan_model->get_lampiran_layanan_list($row->id_layanan_fakultas_mahasiswa); 
                                                     if(empty($lampiran)) {
                                                         echo "<i>(Belum ada, silakan lengkapi berkas lampiran)</i>";
                                                     } else {
                                                        
                                                         foreach($lampiran as $rw) {
                                                             echo "<li><a href='".base_url($rw->file)."' download>".$rw->nama_berkas."</a></li>";
                                                         }
         
                                                         echo "</ul>";
                                                     }
                                                ?>
                                            </td>
                                            <td class="align-top">
                                            <?php if($row->status == 2){ ?>
                                                <a data-toggle = "modal" data-id="<?php echo $row->id  ?>" data-aksi="lulus" class="passingID" >
                                                    <button type="button" class="btn mb-2 btn-primary btn-sm btn-block"  data-toggle="modal" data-target="#LulusBeasiswa">
                                                        <span class="btn-icon-wrapper pr-2 opacity-7"><i class="fas fa-star fa-w-20"></i></span>Lulus
                                                    </button>
                                                </a>

                                                <a data-toggle = "modal" data-id="<?php echo $row->id  ?>" data-aksi="tolak" class="passingID2" >
                                                    <button type="button" class="btn mb-2 btn-danger btn-sm btn-block"  data-toggle="modal" data-target="#TolakBeasiswa">
                                                        <span class="btn-icon-wrapper pr-2 opacity-7"><i class="fas fa-trash fa-w-20"></i></span>Tolak
                                                    </button>
                                                </a>
                                            </td>
                                            <?php }elseif($row->status == 4) { ?>
                                                    <button type="button" class="btn mb-2 btn-success btn-sm btn-block" >
                                                        <span class="btn-icon-wrapper opacity-7"></i></span>Diterima
                                                    </button>
                                            <?php } ?>
                                                                               
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
        var ak = $(this).attr('data-aksi');
        $("#id_aksi_beasiswa").val(id);
        $("#aksi_ket").val(ak);
    });       

     $(".passingID2").click(function () {
        var id = $(this).attr('data-id');
        var ak = $(this).attr('data-aksi');
        $("#id_aksi_beasiswa2").val(id);
        $("#aksi_ket2").val(ak);
    });                       
     
</script>