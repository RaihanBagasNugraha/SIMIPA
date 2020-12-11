

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-note icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    
                                    <div>List Beasiswa Aktif
                                        <div class="page-title-subheading">
                                            Pilih Beasiswa Aktif
                                        </div>
                                    </div>
                                </div>
                                <div class="page-title-actions">
                                    <!-- <a href="<?php echo site_url("dosen/beasiswa") ?>" class="btn-shadow btn btn-success">
                                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-plus fa-w-20"></i>
                                            </span>
                                            Tambah Beasiswa
                                    </a> -->
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
                                            <th style="width: 5%;">No</th>
                                            <th style="width: 20%;">Nama Beasiswa</th>
                                            <th style="width: 15%;">Tahun Akademik</th>
                                            <th style="width: 50%;">Keterangan</th>
                                            <th style="width: 10%;">Pendaftar</th>
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
                                            <td class="align-top"><?php echo $row->tahun_akademik; ?></td>
                                            <td class="align-top">
                                            <?php 
                                                echo "<b>Semester : </b>".$row->semester;
                                                echo "<br>";
                                                echo "<b>Tahun : </b>".$row->tahun;
                                                echo "<br>";
                                                echo "<b>Penyelenggara : </b>".$row->penyelenggara;
                                            ?>
                                            </td>
                                            <td class="align-top">
                                            <a href="<?php echo site_url("dosen/beasiswa-detail/pendaftar/".$row->id) ?>" class="btn-shadow btn btn-info">
                                                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                        <i class="fas fa-eye fa-w-20"></i>
                                                    </span>
                                                    <?php echo $this->layanan_model->jml_pendaftar_beasiswa($row->id); ?>
                                            </a>
                                               
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
        $("#id_hapus_beasiswa").val(id);
    });    

    $(".passingID2").click(function () {
        var id = $(this).attr('data-id');
        var thn = $(this).attr('data-tahun');
        var ta = $(this).attr('data-ta');
        var smr = $(this).attr('data-smr');
        var png = $(this).attr('data-png');
        var nama = $(this).attr('data-nama');
        $("#id_edit_beasiswa").val(id);
        $("#tahun_edit_beasiswa").val(thn);
        $("#ta_edit_beasiswa").val(ta);
        $("#smr_edit_beasiswa").val(smr);
        $("#png_edit_beasiswa").val(png);
        $("#nama_edit_beasiswa").val(nama);
    });    

    $(".passingID3").click(function () {
        var id = $(this).attr('data-id');
        var sts = 'aktif';
        $("#id_aktif_beasiswa").val(id);
        $("#status").val(sts);
    });      

    $(".passingID4").click(function () {
        var id = $(this).attr('data-id');
        var sts = 'nonaktif';
        $("#id_aktif_beasiswa").val(id);
        $("#status").val(sts);
    });                               
     
</script>