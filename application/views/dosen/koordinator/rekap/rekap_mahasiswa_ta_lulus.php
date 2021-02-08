

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
                                    <div>Monitor Kelulusan Mahasiswa <?php echo strtoupper($strata); ?> Jurusan <?php echo $jur_dosen; ?>
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
                                            <th>Npm<br>Nama</th>
                                            <th>Judul</th>
                                            <th>Tgl Acc Tema</th>
                                            <th>Tgl Approval Nilai</th>
                                            <th>Berkas Lampiran</th>
                                            <th>Durasi TA</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if(empty($lulus))
                                        {
                                            echo "<tr><td colspan='6'>Data tidak tersedia</td></tr>";
                                        }
                                        else
                                        {
                                            foreach($lulus as $row) {
                                        ?>
                                            <?php if($strata != 'd3'){ ?>
                                            <tr>
                                                <td class="align-top"><?php echo $row->npm."<br>".$this->user_model->get_mahasiswa_name($row->npm);?></td>
                                                <td class="align-top"><?php echo $row->judul_approve == 1 ? $row->judul1 : $row->judul2; ?></td>
                                                <?php $lama_ta = $this->ta_model->lama_ta_mahasiswa_lulus($row->id_pengajuan);?>
                                                <?php $sidang =  $this->ta_model->get_mahasiswa_ta_rekap_lulus_detail_data($row->id_pengajuan); ?>
                                                <td class="align-top"><?php echo substr($lama_ta->acc,0,10);;  ?></td>
                                                <td class="align-top"><?php echo substr($sidang->updated_at,0,10); ?> </td>
                                                <td class="align-top">
                                                    <?php 
                                                        $lampiran = $this->ta_model->select_lampiran_by_seminar($sidang->id_seminar);

                                                        if(empty($lampiran)) {
                                                            echo "<i>(Belum ada, silakan lengkapi berkas lampiran)</i>";
                                                        } else {
                                                            echo "<ul style='margin-left: -20px;'>";
                                                                //berkas kompre
                                                                echo "<li><a href=".site_url("mahasiswa/tugas-akhir/seminar/form_pdf?jenis=penilaian_kompre&id=$sidang->id_seminar").">Form Penilaian</a></li>";
                                                                echo "<li><a href=".site_url("mahasiswa/tugas-akhir/seminar/form_pdf?jenis=berita_acara_kompre&id=$sidang->id_seminar").">Berita Acara</a></li>";
                                                                if($sidang->jenis == 'Sidang Komprehensif'){
                                                                    if($sidang->status >= 0 && $sidang->status != 5 && $sidang->status != 6){
                                                                        echo "<li><a href=".site_url("mahasiswa/tugas-akhir/seminar/form_pdf?jenis=pengajuan_seminar_kompre&id=$sidang->id_seminar").">Form Pengajuan</a></li>";
                                                                    }
                                                                    if($sidang->status >= 3 && $sidang->status != 5 && $sidang->status != 6){
                                                                        echo "<li><a href=".site_url("mahasiswa/tugas-akhir/seminar/form_pdf?jenis=verifikasi_seminar&id=$sidang->id_seminar").">Form Verifikasi</a></li>";
                                                                    }
                                                                    if($sidang->status == 7 || $sidang->status == 4 && $sidang->status != 5 && $sidang->status != 6){
                                                                        echo "<li><a href=".site_url("mahasiswa/tugas-akhir/seminar/form_pdf?jenis=undangan_seminar&id=$sidang->id_seminar").">Undangan Seminar</a></li>";
                                                                    }
                                                                }

                                                            echo "<br>";
                                                            echo "<b>Berkas Lampiran :</b>";
                                                            foreach($lampiran as $rw) {
                                                                // $nama_berkas = $this->ta_model->get_berkas_name($rw->jenis_berkas);
                                                                echo "<li><a href='".base_url($rw->file)."' download>".$rw->nama_berkas."</a></li>";
                                                            }
            
                                                            echo "</ul>";
                                                        }
                                                    
                                                    
                                                    ?>
                                                
                                                </td>
                                                
                                                <td class="align-top">
                                                   
                                                        <?php 
                                                             $date_now = date("Y-m-d");
                                                             $date_acc = date_create($lama_ta->acc);
                                                             $date_lulus=date_create($lama_ta->lulus);
                                                        
                                                             $diff=date_diff($date_acc,$date_lulus);
                                                             $convert = $diff->format("%a");
                                                             if($convert >= 90 && $convert <= 180){
                                                                $color2 = "yellow";
                                                             }
                                                             elseif($convert > 180){
                                                                $color2 = "red";
                                                             }
                                                             else{
                                                                 $color2 = "green";
                                                             }

                                                            // $convert = '5000'; // days you want to convert

                                                            $years = ($convert / 365) ; // days / 365 days
                                                            $years = floor($years); // Remove all decimals
                                                    
                                                            $month = ($convert % 365) / 30.5; // I choose 30.5 for Month (30,31) ;)
                                                            $month = floor($month); // Remove all decimals
                                                    
                                                            $days = ($convert % 365) % 30.5; // the rest of days
                                                    
                                                            // Echo all information set
                                                            if($years == 0 && $month != 0)
                                                            {
                                                                echo "<span style=\"display:block;background-color:$color2;color:white;\">".$month.' bulan, '.$days.' hari'."</span>"; 
                                                            }
                                                            elseif($years != 0 && $month == 0 )
                                                            {
                                                                echo "<span style=\"display:block;background-color:$color2;color:white;\">".$years.' tahun, '.$days.' hari'."</span>";
                                                            }
                                                            elseif($years == 0 && $month == 0)
                                                            {
                                                                echo "<span style=\"display:block;background-color:$color2;color:white;\">".$days.' hari'."</span>";
                                                            }
                                                            else{
                                                                echo "<span style=\"display:block;background-color:$color2;color:white;\">".$years.' tahun, '.$month.' bulan, '.$days.' hari'."</span>";
                                                            }
                                                            
                                                        
                                                        ?>
                                                </td>
                                            </tr>
                                            <?php } else {?>
                                            <tr>
                                                <td class="align-top"><?php echo $row->npm."<br>".$this->user_model->get_mahasiswa_name($row->npm);?></td>
                                                <td class="align-top"><?php echo $row->judul_approve == 1 ? $row->judul1 : $row->judul2; ?></td>
                                                <td class="align-top"><?php $tgl_acc = substr($this->ta_model->get_ta_acc_date($row->id_pengajuan)->created_at,0,10); echo "$tgl_acc"; ?></td>
                                                <td class="align-top"><?php $tgl_approve_nilai = substr($this->ta_model->get_smr_d3_nilai_date($row->id_pengajuan)->created_at,0,10); echo "$tgl_approve_nilai";  ?> </td>
                                                <td class="align-top">
                                                    <?php 
                                                    $smr_ta = $this->ta_model->get_ta_smr_id($row->id_pengajuan);
                                                    $lampiran = $this->ta_model->select_lampiran_by_seminar($smr_ta->id);
                                                    if(empty($lampiran)) {
                                                        echo "<i>(Belum ada, silakan lengkapi berkas lampiran)</i>";
                                                    } else {
                                                        echo "<ul style='margin-left: -20px;'>";
                                                            echo "<li><a href=".site_url("mahasiswa/tugas-akhir/tema/form_pdf?jenis=verifikasi_ta&id=$row->id_pengajuan").">Form Pengajuan Verifikasi TA</a></li>";
                                                            echo "<li><a href=".site_url("mahasiswa/tugas-akhir/tema/form_pdf?jenis=verifikasi_ta_nilai&id=$row->id_pengajuan").">Nilai Verifikasi TA</a></li>"; 
                                                            echo "<li><a href=".site_url("mahasiswa/tugas-akhir/seminar/form_pdf?jenis=pengajuan_seminar_ta&id=$smr_ta->id").">Form Pengajuan</a></li>";
                                                            echo "<li><a href=".site_url("mahasiswa/tugas-akhir/seminar/form_pdf?jenis=verifikasi_seminar&id=$smr_ta->id").">Form Verifikasi</a></li>";
                                                            echo "<li><a href=".site_url("mahasiswa/tugas-akhir/seminar/form_pdf?jenis=undangan_seminar&id=$smr_ta->id").">Undangan Seminar</a></li>";
                                                        
                                                        echo "<br>";
                                                            echo "<b>Berkas Lampiran :</b>";
                                                            foreach($lampiran as $rw) {
                                                                // $nama_berkas = $this->ta_model->get_berkas_name($rw->jenis_berkas);
                                                                echo "<li><a href='".base_url($rw->file)."' download>".$rw->nama_berkas."</a></li>";
                                                            }
            
                                                        echo "</ul>";
                                                    }
                                                    
                                                    ?>
                                                </td>
                                                <td class="align-top">
                                                   
                                                        <?php 
                                                             $date_now = date("Y-m-d");
                                                             $date_acc = date_create($tgl_acc);
                                                            //  $date_lulus=date_create($tgl_approve_nilai);
                                                             $date_lulus=date_create($smr_ta->tgl_pelaksanaan); //tgl kompre
                                                             $diff=date_diff($date_acc,$date_lulus);
                                                             $convert = $diff->format("%a");
                                                             if($convert >= 90 && $convert <= 180){
                                                                $color2 = "yellow";
                                                             }
                                                             elseif($convert > 180){
                                                                $color2 = "red";
                                                             }
                                                             else{
                                                                 $color2 = "green";
                                                             }

                                                            // $convert = '5000'; // days you want to convert

                                                            $years = ($convert / 365) ; // days / 365 days
                                                            $years = floor($years); // Remove all decimals
                                                    
                                                            $month = ($convert % 365) / 30.5; // I choose 30.5 for Month (30,31) ;)
                                                            $month = floor($month); // Remove all decimals
                                                    
                                                            $days = ($convert % 365) % 30.5; // the rest of days
                                                    
                                                            // Echo all information set
                                                            if($years == 0 && $month != 0)
                                                            {
                                                                echo "<span style=\"display:block;background-color:$color2;color:white;\">".$month.' bulan, '.$days.' hari'."</span>"; 
                                                            }
                                                            elseif($years != 0 && $month == 0 )
                                                            {
                                                                echo "<span style=\"display:block;background-color:$color2;color:white;\">".$years.' tahun, '.$days.' hari'."</span>";
                                                            }
                                                            elseif($years == 0 && $month == 0)
                                                            {
                                                                echo "<span style=\"display:block;background-color:$color2;color:white;\">".$days.' hari'."</span>";
                                                            }
                                                            else{
                                                                echo "<span style=\"display:block;background-color:$color2;color:white;\">".$years.' tahun, '.$month.' bulan, '.$days.' hari'."</span>";
                                                            }
                                                            
                                                        
                                                        ?>
                                                </td>
                                            </tr>
                                            
                                        <?php
                                                }
                                            
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
                        