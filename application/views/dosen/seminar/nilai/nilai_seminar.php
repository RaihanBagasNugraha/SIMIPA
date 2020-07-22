

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-note icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <div>Nilai Seminar/Sidang Penelitian
                                        <div class="page-title-subheading">
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
                        if(!empty($_GET['status']) && $_GET['status'] == 'null') {

                            echo '<div class="alert alert-danger fade show" role="alert">Komposisi nilai seminar ini belum diisi oleh ketua jurusan</div>';
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
                                            <th>Judul</th>
                                            <th>Pelaksanaan</th>
                                            <th>Berkas</th>
                                            <th>Status</th>
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
                                                <td class="align-top">
                                                    <?php echo "<b>$row->jenis</b>";?>
                                                </td>
                                                <td class="align-top">
                                                    <?php echo "$row->npm";?>
                                                </td>
                                                <td class="align-top">
                                                    <?php 
                                                        if($row->judul_approve == 1){
                                                            echo $row->judul1;
                                                        }
                                                        elseif($row->judul_approve == 2){
                                                            echo $row->judul2;
                                                        }
                                                    
                                                    ?>
                                                </td>
                                                <td class="align-top">
                                                    <?php echo "$row->tempat<br>$row->tgl_pelaksanaan<br>$row->waktu_pelaksanaan<br>";  ?>
                                                </td>
                                                <td class="align-top">
                                                    <?php 
                                                        $user = $this->session->userdata('userId');
                                                        $lampiran = $this->ta_model->select_lampiran_by_seminar($row->id);
                                                        if(empty($lampiran)) {
                                                            echo "<i>(Belum ada, silakan lengkapi berkas lampiran)</i>";
                                                        } else {
                                                            echo "<ul style='margin-left: -20px;'>";
                                                                
                                                            echo "<li><a href=".site_url("mahasiswa/tugas-akhir/seminar/form_pdf?jenis=undangan_seminar_dosen&id=$row->id&user=$user").">Undangan Seminar</a></li>";

                                                            $draft = $this->ta_model->get_draft_seminar($row->id); 
                                                            if(!empty($draft)){
                                                                echo "<li><a href='".base_url($draft->file)."' download>"."Draft Laporan"."</a></li>";
                                                            }   
                                                            

                                                            // foreach($lampiran as $rw) {
                                                            //     $nama_berkas = $this->ta_model->get_berkas_name($rw->jenis_berkas);
                                                            //     echo "<li><a href='".base_url($rw->file)."' download>".$nama_berkas."</a></li>";
                                                            // }
            
                                                            echo "</ul>";
                                                        }
                                                    ?>
                                                </td>
                                                <td class="align-top">
                                                    <?php 
                                                        echo $row->status;
                                                    ?>
                                                </td>
                                                <td class="align-top">
                                                    <?php 
                                                    switch($row->status){
                                                        case "Pembimbing Utama":
                                                        $status = "pb1";
                                                        break;
                                                        case "Pembimbing 2":
                                                        $status = "pb2";
                                                        break;
                                                        case "Pembimbing 3":
                                                        $status = "pb3";
                                                        break;
                                                        case "Penguji 1":
                                                        $status = "ps1";
                                                        break;
                                                        case "Penguji 2":
                                                        $status = "ps2";
                                                        break;
                                                        case "Penguji 3":
                                                        $status = "ps3";
                                                        break;
                                                    }

                                                    $date_now = new DateTime();
                                                    $date_smr = new DateTime("$row->tgl_pelaksanaan");
                                                    if($date_now >= $date_smr){
                                                    ?>
                                                        <a href="<?php echo site_url("dosen/tugas-akhir/nilai-seminar/add?id=$row->id&status=$status") ?>" class="btn-wide mb-1 btn btn-primary btn-sm btn-block">Nilai
                                                    <?php        
                                                    } else{echo "Menunggu";}?>

                                                   
                                                    </a>
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
       
      
</script>
                        