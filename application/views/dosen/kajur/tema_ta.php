

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-note icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <div>Kelola Tema Penelitian
                                        <div class="page-title-subheading">Lorem ipsum.
                                        </div>
                                    </div>
                                </div>
                                <div class="page-title-actions">
                                    <a href="<?php echo site_url("mahasiswa/tugas-akhir/tema/form") ?>" class="btn-shadow btn btn-success">
                                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-file fa-w-20"></i>
                                            </span>
                                            Form Pengajuan Tema
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
                                    <table class="mb-0 table table-striped"  id="example">
                                        <thead>
                                        <tr>
                                            <th>Judul</th>
                                            <th>Komisi Pembimbing</th>
                                            <th>Komisi Pembahas</th>
                                            <th>Berkas Lampiran</th>
                                            <th>Jenis</th>
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
                                                <?php 
                                                        if($row->judul_approve == 1){
                                                            echo $row->judul1; 
                                                        } else {echo $row->judul2;} ?> 
                
                                                </td>
                                                <td class="align-top">
                                                <?php 
                                                    $komisi_pembimbing = $this->ta_model->get_pembimbing_ta($row->id_pengajuan);

                                                    foreach($komisi_pembimbing as $kom) {
                                                        $gelar = $this->user_model->get_gelar_dosen_nip($kom->nip_nik);
                                                        if(empty($gelar)){
                                                            $g_depan = "";
                                                            $g_belakang = "";
                                                        }
                                                        else{
                                                            $g_depan = $gelar->gelar_depan;
                                                            $g_belakang = $gelar->gelar_belakang;
                                                        }
    
                                                        echo "<b>$kom->status</b><br>";
                                                        echo $g_depan.$kom->nama.$g_belakang."<br>";
                                                        echo "$kom->nip_nik<br>";
                                                    }
                                                ?>
                                                </td>
                                                <td class="align-top">
                                                <?php 
                                                    $komisi_penguji = $this->ta_model->get_penguji_ta($row->id_pengajuan);

                                                    foreach($komisi_penguji as $kom) {
                                                        $gelar = $this->user_model->get_gelar_dosen_nip($kom->nip_nik);
                                                        if(empty($gelar)){
                                                            $g_depan = "";
                                                            $g_belakang = "";
                                                        }
                                                        else{
                                                            $g_depan = $gelar->gelar_depan;
                                                            $g_belakang = $gelar->gelar_belakang;
                                                        }
    
                                                        echo "<b>$kom->status</b><br>";
                                                        echo $g_depan.$kom->nama.$g_belakang."<br>";
                                                        echo "$kom->nip_nik<br>";
                                                    }
                                                ?>
                                                </td>
                                                <td class="align-top">
                                                    <?php
                                                        $lampiran = $this->ta_model->select_lampiran_by_ta($row->id_pengajuan, $row->npm);
                                                        if(empty($lampiran)) {
                                                            echo "<i>(Tidak Ada Lampiran)</i>";
                                                        } else {
                                                            $dosenid = $this->session->userdata('userId');
                                                            echo "<ul style='margin-left: -20px;'>";
                                                            echo "<li><a href=".site_url("mahasiswa/tugas-akhir/tema/form_pdf?jenis=pengajuan_bimbingan&id=$row->id_pengajuan").">Form Pengajuan</a></li>";
                                                            echo "<li><a href=".site_url("mahasiswa/tugas-akhir/tema/form_pdf?jenis=form_verifikasi&id=$row->id_pengajuan").">Form Verifikasi</a></li>"; 
                                                            echo "<li><a href=".site_url("mahasiswa/tugas-akhir/tema/form_pdf?jenis=form_penetapan&id=$row->id_pengajuan").">Form Penetapan</a></li>";
                                                            foreach($lampiran as $rw) {
                                                                echo "<li><a href='".base_url($rw->file)."' download>".$rw->nama_berkas."</a></li>";
                                                            }

                                                            echo "</ul>";
                                                        }
                                                    ?>
                                                </td>
                                                <td class="align-top">
                                                <?php 
                                                    echo "$row->jenis";
                                                ?></td>
                                                <td class="align-top">

                                                <a href="<?php echo site_url("dosen/struktural/tema/form?id=".$this->encrypt->encode($row->id_pengajuan)) ?>" class="btn-wide mb-1 btn btn-primary btn-sm btn-block">Setujui
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
    $(".passingIDKoor").click(function () {
                var id = $(this).attr('data-id');
                $("#IDKoor").val( id );

            });
      
</script>
                        