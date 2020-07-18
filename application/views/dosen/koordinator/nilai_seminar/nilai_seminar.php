

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-note icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <div>Approval Nilai Seminar
                                        <div class="page-title-subheading"><?php  ?>
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
                                            <th>Aksi</th>
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
                                            <tr>
                                                <td class="align-top"><b><?php echo $row->jenis;?><b></td>
                                                <td class="align-top"><?php echo $row->npm;?></td>
                                                <td class="align-top"><?php echo "$row->tempat<br>$row->tgl_pelaksanaan<br>$row->waktu_pelaksanaan<br>";  ?></td>
                                                <td class="align-top"><?php 
                                                        if($row->judul_approve == 1){echo $row->judul1;}
                                                        else{echo $row->judul2;}
                                                        ?>                         
                                                </td>
                                                <td class="align-top">
                                                <?php 
                                                // $lampiran = $this->ta_model->select_lampiran_by_seminar($row->id);
                                                   
                                                        echo "<ul style='margin-left: -20px;'>";
                                                       
                                                        echo "<li><a href=".site_url("mahasiswa/tugas-akhir/seminar/form_pdf?jenis=penilaian_seminar&id=$row->id").">Form Penilaian</a></li>";
                                                        

                                                        echo "<li><a href=".site_url("mahasiswa/tugas-akhir/seminar/form_pdf?jenis=berita_acara&id=$row->id").">Berita Acara</a></li>";
                                                            
                                                        echo "</ul>";
                                                               
                                                ?>

                                                </td>
                                                <td class="align-top"> 
                                                <?php if($row->jenis == "Seminar Tugas Akhir"){ ?>
                                                    <a href="<?php echo base_url("dosen/struktural/kaprodi/nilai-seminar-sidang/form/?id=".$this->encrypt->encode($row->id)) ?>" class="btn-wide mb-1 btn btn-primary btn-sm btn-block">Approve
                                                    </a>

                                                <?php } else {?>
                                                    <a href="<?php echo base_url("dosen/tugas-akhir/nilai-seminar/koordinator/form/?id=".$this->encrypt->encode($row->id)) ?>" class="btn-wide mb-1 btn btn-primary btn-sm btn-block">Approve
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
    $(".passingIDKoor").click(function () {
                var id = $(this).attr('data-id');
                $("#IDKoor").val( id );
            });
      
</script>
                        