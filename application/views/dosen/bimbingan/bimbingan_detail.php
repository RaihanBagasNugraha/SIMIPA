

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-note icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <?php
                                    $data_dosen = $this->user_model->get_dosen_data($this->session->userdata('userId'));
                                    ?>
                                    <div>Daftar Mahasiswa Bimbingan Tugas Akhir
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
                                            <th width="10%" >Npm<br>Nama</th>
                                            <th width="30%" style="text-align:center">Judul</th>
                                            <th width="10%" style="text-align:center">Tgl Acc</th>
                                            <th width="10%" style="text-align:center">Seminar<br>TA</th>
                                            <th width="10%" style="text-align:center">Seminar<br>Usul</th>
                                            <th width="10%" style="text-align:center">Seminar<br>Hasil</th>
                                            <th width="10%" style="text-align:center">Sidang<br>Komprehensif</th>
                                            <th width="10%" style="text-align:center">Durasi TA</th>
                                            <!-- <th width="20%">Status TA Setelah Aktivitas Terakhir</th> -->
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if(empty($ta))
                                        {
                                            echo "<tr><td colspan='8'>Data tidak tersedia</td></tr>";
                                        }
                                        else
                                        {
                                            foreach($ta as $row) {
                                        ?>
                                            <tr>
                                                <td class="align-top"><?php echo $row->npm."<br>".$this->user_model->get_mahasiswa_name($row->npm);?></td>
                                                <td class="align-top"><?php echo $row->judul_approve == 1 ? $row->judul1 : $row->judul2; ?></td>
                                                <td class="align-top" style="text-align:center">
                                                    <?php $tgl_acc = substr($this->ta_model->get_ta_acc_date($row->id_pengajuan)->created_at,0,10); echo "$tgl_acc"; ?>
                                                </td>
                                                <td class="align-top" style="text-align:center">
                                                <?php $smr_ta = $this->ta_model->get_mahasiswa_ta_rekap_ta_detail_seminar($row->npm,'Seminar Tugas Akhir'); ?>
                                                <?php 
                                                    if(!empty($smr_ta)){
                                                        $tgl_ta = $smr_ta->tgl_pelaksanaan;
                                                        if($tgl_acc != ''){
                                                            $date_now = date("Y-m-d");
                                                            $date_acc = date_create($tgl_acc);
                                                            $date_ta=date_create($tgl_ta);
                                                            $diff=date_diff($date_acc,$date_ta);
                                                            if($diff->format("%a")>=90 && $diff->format("%a")<=180)
                                                            {
                                                                $color = "yellow";
                                                            }
                                                            elseif($diff->format("%a")>=180)
                                                            {
                                                                $color = "red";
                                                            }
                                                            else{
                                                                $color = "green";
                                                            }
                                                            echo "<span style=\"display:block;background-color:$color;color:white;\">$tgl_ta</span>";
                                                        }
                                                        else{
                                                            echo "$tgl_ta";
                                                        }
                                                        // echo $tgl_ta;
                                                        if($smr_ta->status == 10){
                                                            echo "<br><br>";
                                                            // echo "<li><a href=".site_url("mahasiswa/tugas-akhir/seminar/form_pdf?jenis=penilaian_seminar&id=$smr_hp->id").">Form Penilaian</a></li>";
                                                            echo "<a href=".site_url("mahasiswa/tugas-akhir/seminar/form_pdf?jenis=berita_acara&id=$smr_ta->id").">Berita Acara</a>";
                                                        }
                                                        else{
                                                            echo "<br><br>";
                                                            echo "<i>Penilaian Seminar</i>";
                                                        }
                                                    }
                                                    else{
                                                        echo "-";
                                                    }
                                                ?>
                                                </td>
                                                <td class="align-top" style="text-align:center">
                                                <?php $smr_up = $this->ta_model->get_mahasiswa_ta_rekap_ta_detail_seminar($row->npm,'Seminar Usul'); ?>
                                                <?php 
                                                    if(!empty($smr_up)){
                                                        $tgl_up = $smr_up->tgl_pelaksanaan;
                                                        if($tgl_acc != ''){
                                                            $date_now = date("Y-m-d");
                                                            $date_acc = date_create($tgl_acc);
                                                            $date_up=date_create($tgl_up);
                                                            $diff=date_diff($date_acc,$date_up);
                                                            if($diff->format("%a")>=90 && $diff->format("%a")<=180)
                                                            {
                                                                $color = "yellow";
                                                            }
                                                            elseif($diff->format("%a")>=180)
                                                            {
                                                                $color = "red";
                                                            }
                                                            else{
                                                                $color = "green";
                                                            }
                                                            echo "<span style=\"display:block;background-color:$color;color:white;\">$tgl_up</span>";
                                                        }
                                                        else{
                                                            echo "$tgl_up";
                                                        }

                                                        if($smr_up->status == 10){
                                                            echo "<br><br>";
                                                            // echo "<li><a href=".site_url("mahasiswa/tugas-akhir/seminar/form_pdf?jenis=penilaian_seminar&id=$smr_hp->id").">Form Penilaian</a></li>";
                                                            echo "<a href=".site_url("mahasiswa/tugas-akhir/seminar/form_pdf?jenis=berita_acara&id=$smr_up->id").">Berita Acara</a>";
                                                        }
                                                        else{
                                                            // echo $smr_up->status;
                                                            echo "<br><br>";
                                                            echo "<i>Penilaian Seminar</i>";
                                                        }
                                                    }
                                                    else{
                                                        echo "-";
                                                    }
                                                ?>
                                                </td>
                                                <td class="align-top" style="text-align:center">
                                                <?php $smr_hp = $this->ta_model->get_mahasiswa_ta_rekap_ta_detail_seminar($row->npm,'Seminar Hasil'); ?>
                                                <?php 
                                                    if(!empty($smr_hp)){
                                                        $tgl_hp = $smr_hp->tgl_pelaksanaan;
                                                        if(!empty($smr_up)){
                                                            $date_now = date("Y-m-d");
                                                            $date_up = date_create($tgl_up);
                                                            $date_hp = date_create($tgl_hp);
                                                            $diff=date_diff($date_up,$date_hp);
                                                            if($diff->format("%a")>=90 && $diff->format("%a")<=180)
                                                            {
                                                                $color = "yellow";
                                                               
                                                            }
                                                            elseif($diff->format("%a")>=180)
                                                            {
                                                                $color = "red";
                                                            }
                                                            else{
                                                                $color = "green";
                                                            }
                                                            echo "<span style=\"display:block;background-color:$color;color:white;\">$tgl_hp</span>";
                                                        }
                                                        else{
                                                            echo $tgl_hp;
                                                        }
                                                       
                                                        if($smr_hp->status == 10){
                                                            echo "<br><br>";
                                                            // echo "<li><a href=".site_url("mahasiswa/tugas-akhir/seminar/form_pdf?jenis=penilaian_seminar&id=$smr_hp->id").">Form Penilaian</a></li>";
                                                            echo "<a href=".site_url("mahasiswa/tugas-akhir/seminar/form_pdf?jenis=berita_acara&id=$smr_hp->id").">Berita Acara</a>";
                                                        }
                                                        else{
                                                            echo "<br><br>";
                                                            echo "<i>Penilaian Seminar</i>";
                                                        }
                                                    }
                                                    else{
                                                        echo "-";
                                                    }
                                                ?>
                                                </td>
                                                <td class="align-top" style="text-align:center">
                                                <?php $smr_kompre = $this->ta_model->get_mahasiswa_ta_rekap_ta_detail_seminar($row->npm,'Sidang Komprehensif'); ?>
                                                <?php 
                                                    if(!empty($smr_kompre)){
                                                        $tgl_kompre = $smr_kompre->tgl_pelaksanaan;
                                                        if(!empty($smr_hp)){
                                                            $date_now = date("Y-m-d");
                                                            $date_hp = date_create($tgl_hp);
                                                            $date_kompre = date_create($tgl_kompre);
                                                            $diff=date_diff($date_hp,$date_kompre);
                                                            if($diff->format("%a")>=90 && $diff->format("%a")<=180)
                                                            {
                                                                $color = "yellow";
                                                            }
                                                            elseif($diff->format("%a")>=180)
                                                            {
                                                                $color = "red";
                                                            }
                                                            else{
                                                                $color = "green";
                                                            }
                                                            echo "<span style=\"display:block;background-color:$color;color:white;\">$tgl_kompre</span>";
                                                        }
                                                        else{
                                                            echo $tgl_kompre;
                                                        }

                                                        
                                                        if($smr_kompre->status == 10){
                                                            echo "<br><br>";
                                                            // echo "<li><a href=".site_url("mahasiswa/tugas-akhir/seminar/form_pdf?jenis=penilaian_kompre&id=$smr_kompre->id").">Form Penilaian</a></li>";
                                                            echo "<a href=".site_url("mahasiswa/tugas-akhir/seminar/form_pdf?jenis=berita_acara_kompre&id=$smr_kompre->id").">Berita Acara</a>";
                                                        }
                                                        else{
                                                            echo "<br><br>";
                                                            echo "<i>Penilaian Seminar</i>";
                                                        }
                                                    }
                                                    else{
                                                        echo "-";
                                                    }
                                                ?>
                                                </td>
                                                <!-- <td class="align-top style="text-align:center"">
                                                    <?php 
                                                        // if($row->jenis != 'Tugas Akhir'){
                                                        //     $date_now = date("Y-m-d");
                                                        //     $date_acc = date_create("$tgl_acc");
                                                        //     $date_today=date_create($date_now);
                                                        //         if(!empty($smr_up)){
                                                        //             $date_up = date_create("$tgl_up");
                                                        //         }
                                                        //         if(!empty($smr_hp)){
                                                        //             $date_hp = date_create("$tgl_hp");
                                                        //         }
                                                        //         if(!empty($smr_kompre)){
                                                        //             $date_kompre = date_create("$tgl_kompre");
                                                        //         }
                                                        //     if(empty($smr_up) && empty($smr_hp) && empty($smr_kompre))
                                                        //     {
                                                        //         $diff=date_diff($date_acc,$date_today);
                                                        //         $total = $diff->format("%a");
                                                        //     }    
                                                        //     elseif(!empty($smr_up) && empty($smr_hp) && empty($smr_kompre))
                                                        //     {
                                                        //         $diff=date_diff($date_up,$date_today);
                                                        //         $total = $diff->format("%a");
                                                        //     }
                                                        //     elseif(!empty($smr_up) && !empty($smr_hp) && empty($smr_kompre))
                                                        //     {
                                                        //         $diff=date_diff($date_hp,$date_today);
                                                        //         $total = $diff->format("%a");
                                                        //     }
                                                        //     elseif(!empty($smr_up) && !empty($smr_hp) && !empty($smr_kompre))
                                                        //     {
                                                        //         $diff=date_diff($date_kompre,$date_today);
                                                        //         $total = $diff->format("%a");
                                                        //     }                                                                
                                                        // }
                                                        // else{
                                                        //     $date_now = date("Y-m-d");
                                                        //     $date_acc = date_create("$tgl_acc");
                                                        //     $date_today=date_create($date_now);
                                                        //         if(!empty($smr_ta)){
                                                        //             $date_ta = date_create("$tgl_ta");
                                                        //         }
                                                        //         if(empty($smr_ta))
                                                        //         {
                                                        //             $diff=date_diff($date_acc,$date_today);
                                                        //             $total = $diff->format("%a");
                                                        //         }        
                                                        //         elseif(!empty($smr_ta)){
                                                        //             $diff=date_diff($date_ta,$date_today);
                                                        //             $total = $diff->format("%a");
                                                        //         }
                                                        // }
                                                    
                                                        // if($diff->format("%R%a days") >= 90 && $diff->format("%R%a days") <= 180){
                                                        //     echo "<font style=\"padding: 2px 10px 5px 10px; color: #ffffff; background-color: #ff9800\">Sedang</font>";
                                                        // }
                                                        // elseif($diff->format("%R%a days") > 180){
                                                        //     echo "<font style=\"padding: 2px 10px 5px 10px; color: #ffffff; background-color: #f44336\">Lambat</font>";
                                                        // }
                                                        // else{
                                                        //     echo "<font style=\"padding: 2px 10px 5px 10px; color: #ffffff; background-color: #009432\">Cepat</font>";
                                                        // }
                                                        // echo $total;
                                                    
                                                    ?>
                                                
                                                
                                                </td> -->
                                                <td class="align-top" style="text-align:center">
                                                        <?php 
                                                             $date_now = date("Y-m-d");
                                                             $date_acc = date_create("$tgl_acc");
                                                             $date_today=date_create($date_now);
                                                        
                                                             $diff=date_diff($date_acc,$date_today);
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
                        