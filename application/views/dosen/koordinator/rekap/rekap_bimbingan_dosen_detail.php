

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-note icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <?php
                                    $data_dosen = $this->user_model->get_dosen_data($this->session->userdata('userId'));
                                    $strata = $_GET['strata'];
                                    $jenis = $_GET['jenis'];
                                    switch($data_dosen->jurusan)
                                    {
                                        case "0":
                                        $jur_dosen = "Dokter MIPA";
                                        break;
                                        case "1":
                                        $jur_dosen = "Kimia";
                                        break;
                                        case "2":
                                        $jur_dosen = "Biologi";
                                        break;
                                        case "3":
                                        $jur_dosen = "Matematika";
                                        break;
                                        case "4":
                                        $jur_dosen = "Fisika";
                                        break;
                                        case "5":
                                        $jur_dosen = "Ilmu Komputer";
                                        break;
                                    }
                                    switch($jenis)
                                    {
                                        case "pb1":
                                        $status = "Pembimbing Utama";
                                        break;
                                        case "pb2":
                                        $status = "Pembimbing 2";
                                        break;
                                        case "pb3":
                                        $status = "Pembimbing 3";
                                        break;
                                        case "ps1":
                                        $status = "Penguji 1";
                                        break;
                                        case "ps2":
                                        $status = "Penguji 2";
                                        break;
                                        case "ps3":
                                        $status = "Penguji 3";
                                        break;
                                    }
                                    
                                    ?>
                                    <div>Daftar Bimbingan Mahasiswa
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
                                    <?php 
                                        $dosen = $this->user_model->get_dosen_data($id_dosen);
                                        echo "<h6><b>$dosen->name</b></h6>";
                                        // echo "<br>";
                                        echo "<h6><b>$dosen->nip_nik</b></h6>";
                                        echo "<br>";
                                        echo "<h7><b>Status : $status</b></h7>";
                                        echo "<br>";
                                        // echo "<br>";
                                        
                                    ?>
                                    <table class="mb-0 table table-striped" id="example">
                                        <thead>
                                        <tr>
                                            <th style="width:30%">Npm<br>Nama</th>
                                            <th style="width:50%">Judul</th>
                                            <th style="width:10%">Tgl Acc</th>
                                            <!-- <th style="width:10%">Status</th> -->
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if(empty($bimbingan))
                                        {
                                            echo "<tr><td colspan='6'>Data tidak tersedia</td></tr>";
                                        }
                                        else
                                        {
                                            foreach($bimbingan as $row) {
                                        ?>
                                            <tr>
                                                <td class="align-top"><?php echo $row->npm."<br>".$this->user_model->get_mahasiswa_name($row->npm);?></td>
                                                <td class="align-top"><?php echo $row->judul_approve == 1 ? $row->judul1 : $row->judul2 ?></td>
                                                <td class="align-top"><?php echo $this->ta_model->get_tgl_acc($row->id_tugas_akhir)  ?></td>
                                                <!-- <td class="align-top" style = "text-align:center">
                                                <?php
                                                    $status_mhs = $this->ta_model->get_bimbingan_dosen_detail_status($row->id_pengajuan);
                                                    if(!empty($status_mhs)){
                                                        echo "<span style=\"display:block;background-color:red;color:white;\">Lulus</span>";
                                                    }
                                                    else{
                                                        echo "<span style=\"display:block;background-color:blue;color:white;\">Aktif</span>";
                                                    }

                                                ?>
                                                </td> -->
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
    $('#example').DataTable(
        {
       
        } 
    );
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
                        