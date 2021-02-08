

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-note icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <div>Verifikasi Program Tugas Akhir
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
                        if(!empty($_GET['status']) && $_GET['status'] == 'error') {

                            echo '<div class="alert alert-danger fade show" role="alert">Kriteria Penilaian Belum Diisi Oleh Ketua Jurusan</div>';
                        }
                        ?>
                        <?php 
                        // if ($akun->ttd == NULL){
                        //     echo "<script>
                        //     alert('Silahkan Lengkapi Informasi Akun & Biodata Anda Terlebih Dahulu');
                        //     window.location.href='biodata';
                        //     </script>";
                        // } 
                        
                        ?>
                        
                         <div class="main-card mb-3 card">
                                <div class="card-body">
                                <div class="table-responsive">
                                    <table class="mb-0 table table-striped" id="example">
                                        <thead>
                                        <tr>
                                            <th>Judul</th>
                                            <th>Bidang Ilmu</th>
                                            <th>Nama/Npm</th>
                                            <th>Tanggal Pengajuan</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                       
                                        <?php
                                        if(empty($ta))
                                        {
                                            echo "<tr><td colspan='6'>Data tidak tersedia</td></tr>";
                                        }
                                        else
                                        {
                                            foreach($ta as $row) {
                                               
                                        ?>
                                            <tr>
                                                <td class="align-top">
                                                    <?php echo $row->judul_approve == 1 ? $row->judul1 : $row->judul2 ?>
                                                </td>
                                                <td class="align-top">
                                                    <?php echo $this->ta_model->get_bidang_ilmu_id($row->bidang_ilmu)->nama ?>
                                                </td>
                                                <td class="align-top">
                                                    <?php 
                                                    echo "<b>".$this->user_model->get_mahasiswa_name($row->npm)."</b>";
                                                    echo "<br>";
                                                    echo $row->npm;
                                                    ?>
                                                </td>
                                                <td class="align-top">
                                                    <?php echo substr($this->ta_model->get_created_verifikasi_ta($row->id_pengajuan)->created,0,10) ?>
                                                </td>
                                                
                                                <td class="align-top">
                                                    <?php echo $row->ket == 2 ? "Selesai" : "Penilaian" ?>
                                                </td>
                                                <td class="align-top">
                                                    <a href="<?php echo site_url("dosen/tugas-akhir/nilai-verifikasi-ta/form?id=".$this->encrypt->encode($row->id_pengajuan)) ?>" class="btn-wide mb-1 btn btn-primary btn-sm btn-block">Isi Pertemuan
                                                    </a>  
                                                    <?php $check = $this->ta_model->cek_verfikasi_ta_pertemuan($row->id_pengajuan);
                                                        if(empty($check)){
                                                    ?>
                                                        <a href="<?php echo site_url("dosen/tugas-akhir/nilai-verifikasi-ta/nilai?id=".$this->encrypt->encode($row->id_pengajuan)) ?>" class="btn-wide mb-1 btn btn-danger btn-sm btn-block">Nilai
                                                        </a>
                                                    <?php } ?>
                                                         
                                                </td>
                                        
                                        <?php }
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
    $(".passingID3").click(function () {
                var id = $(this).attr('data-id');
                var data = id.split("#$#$");
                $("#ID3").val( data[0] );
                $("#status").val( data[1] );

            });

    $(".passingID4").click(function () {
                var id = $(this).attr('data-id');
                var data = id.split("#$#$");
                $("#ID4").val( data[0] );
                $("#status2").val( data[1] );

    });      
       
</script>
                        