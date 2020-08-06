

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-note icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <?php
                                    $data_dosen = $this->user_model->get_dosen_data($this->session->userdata('userId'));

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
                                    
                                    ?>

                                    <div>Rekap Tema Penelitian Jurusan <?php echo $jur_dosen; ?>
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
                                    <table class="mb-0 table table-striped" id="example">
                                        <thead>
                                        <tr>
                                            <!-- <th>No</th> -->
                                            <th>Npm<br>Nama</th>
                                            <th>Judul</th>
                                            <th>Komisi<br>Pembimbing</th>
                                            <th>Komisi<br>Pembahas</th>
                                            <th>Berkas<br>Lampiran</th>
                                            <th>Tgl<br>Pengajuan</th>
                                            <th>Tgl<br>Acc</th>
                                            
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
                                            $n = 1;
                                            foreach($ta as $row) {
                                        ?>
                                            <tr>
                                                <!-- <td class="align-top">
                                                   <?php echo $n++; ?>
                                                </td> -->
                                                <td class="align-top">
                                                    <?php 
                                                        $name = $this->user_model->get_mahasiswa_name($row->npm);
                                                        echo "$row->npm<br>$name"; 
                                                    ?>
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
                                                            echo "<i>(Belum ada, silakan lengkapi berkas lampiran)</i>";
                                                        } else {
                                                            echo "<ul style='margin-left: -20px;'>";
                                                            echo "<li><a href=".site_url("mahasiswa/tugas-akhir/tema/form_pdf?jenis=pengajuan_bimbingan&id=$row->id_pengajuan").">Form Pengajuan</a></li>";
                                                            echo "<li><a href=".site_url("mahasiswa/tugas-akhir/tema/form_pdf?jenis=form_verifikasi&id=$row->id_pengajuan").">Form Verifikasi</a></li>";
                                                            echo "<li><a href=".site_url("mahasiswa/tugas-akhir/tema/form_pdf?jenis=form_penetapan&id=$row->id_pengajuan").">Form Penetapan</a></li>"; 
                                                            echo "<br>";
                                                            // foreach($lampiran as $rw) {
                                                            //     echo "<li><a href='".base_url($rw->file)."' download>".$rw->nama_berkas."</a></li>";
                                                            // }

                                                            echo "</ul>";
                                                        }
                                                    ?>   
                                                </td>
                                                <td class="align-top">
                                                    <?php echo substr($row->created_at,0,10);?>
                                                </td>
                                                <td class="align-top">
                                                    <?php echo  substr($this->ta_model->get_ta_acc_date($row->id_pengajuan)->created_at,0,10)?>
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
    $('#example').DataTable( {
        "order": [[ 3, "desc" ]]
    } );
    
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
                        