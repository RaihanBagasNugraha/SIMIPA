

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-note icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <?php
                                    $data_dosen = $this->user_model->get_dosen_data($this->session->userdata('userId'));
                                    
                                    if($data_dosen->jurusan != NULL){
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
                                    }
                                    else{
                                        $jur_dosen = "";
                                    }

                                    $id_user = $this->session->userdata('userId');
                                    ?>

                                    <div>Rekap Mahasiswa Bimbingan Dosen Jurusan <?php echo $jur_dosen; ?>
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
                                           
                                            <th rowspan = "2"  class="align-top">Nama<br>NIP/NIK</th>
                                            <th rowspan = "2" class="align-top">Strata</th>
                                            <th colspan='3' style="text-align:center">Pembimbing</th>
                                            <th colspan='3' style="text-align:center">Penguji</th>
                                            <!-- style="width:100%" -->
                                            <!-- <th>Persentase</th> -->
                                        </tr>
                                        <tr>
                                            <!-- <th>Nama<br>NIP/NIK</th> -->
                                            <!-- <th>Strata</th> -->
                                            <th style="text-align:center">1</th>
                                            <th style="text-align:center">2</th>
                                            <th style="text-align:center">3</th>
                                            <th style="text-align:center">1</th>
                                            <th style="text-align:center">2</th>
                                            <th style="text-align:center">3</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if(empty($dosen))
                                        {
                                            echo "<tr><td colspan='6'>Data tidak tersedia</td></tr>";
                                        }
                                        else
                                        {
                                            foreach($dosen as $row) {
                                        ?>
                                            <tr>
                                               <td style="border-bottom:1pt solid black;">
                                               <?php 
                                                    echo $row->gelar_depan.$row->name.$row->gelar_belakang; 
                                                    echo "<br>";
                                                    echo $row->nip_nik;
                                               
                                               ?></td>
                                               <td style="border-bottom:1pt solid black;"><b>D3</b><br><br><b>S1</b><br><br><b>S2</b><br><br><b>S3</b></td>
                    <!-- Pbb 1-->              <td style="border-bottom:1pt solid black; text-align:center"><a style="color: blue;" href="<?php echo site_url("dosen/koordinator/rekap/bimbingan-dosen/detail?jenis=pb1&strata=d3&dosen=".$this->encrypt->encode($row->id_user))?>"><b><?php echo $this->ta_model->get_jml_bimbingan($row->id_user,'Pembimbing Utama','0','0')->jml; ?></a><br><br><a style="color: blue;" href="<?php echo site_url("dosen/koordinator/rekap/bimbingan-dosen/detail?jenis=pb1&strata=s1&dosen=".$this->encrypt->encode($row->id_user))?>"><?php echo $this->ta_model->get_jml_bimbingan($row->id_user,'Pembimbing Utama','1','5')->jml; ?></a><br><br><a style="color: blue;" href="<?php echo site_url("dosen/koordinator/rekap/bimbingan-dosen/detail?jenis=pb1&strata=s2&dosen=".$this->encrypt->encode($row->id_user))?>"><?php echo $this->ta_model->get_jml_bimbingan($row->id_user,'Pembimbing Utama','2','2')->jml; ?></a><br><br><a style="color: blue;" href="<?php echo site_url("dosen/koordinator/rekap/bimbingan-dosen/detail?jenis=pb1&strata=s3&dosen=".$this->encrypt->encode($row->id_user))?>"><?php echo $this->ta_model->get_jml_bimbingan($row->id_user,'Pembimbing Utama','3','3')->jml; ?></a></b></td>
                    <!-- Pbb 2-->              <td style="border-bottom:1pt solid black; text-align:center"><a style="color: blue;" href="<?php echo site_url("dosen/koordinator/rekap/bimbingan-dosen/detail?jenis=pb2&strata=d3&dosen=".$this->encrypt->encode($row->id_user))?>"><b><?php echo $this->ta_model->get_jml_bimbingan($row->id_user,'Pembimbing 2','0','0')->jml; ?></a><br><br><a style="color: blue;" href="<?php echo site_url("dosen/koordinator/rekap/bimbingan-dosen/detail?jenis=pb2&strata=s1&dosen=".$this->encrypt->encode($row->id_user))?>"><?php echo $this->ta_model->get_jml_bimbingan($row->id_user,'Pembimbing 2','1','5')->jml; ?></a><br><br><a style="color: blue;" href="<?php echo site_url("dosen/koordinator/rekap/bimbingan-dosen/detail?jenis=pb2&strata=s2&dosen=".$this->encrypt->encode($row->id_user))?>"><?php echo $this->ta_model->get_jml_bimbingan($row->id_user,'Pembimbing 2','2','2')->jml; ?></a><br><br><a style="color: blue;" href="<?php echo site_url("dosen/koordinator/rekap/bimbingan-dosen/detail?jenis=pb2&strata=s3&dosen=".$this->encrypt->encode($row->id_user))?>"><?php echo $this->ta_model->get_jml_bimbingan($row->id_user,'Pembimbing 2','3','3')->jml; ?></a></b></td>
                    <!-- Pbb 3-->              <td style="border-bottom:1pt solid black; text-align:center"><a style="color: blue;" href="<?php echo site_url("dosen/koordinator/rekap/bimbingan-dosen/detail?jenis=pb3&strata=d3&dosen=".$this->encrypt->encode($row->id_user))?>"><b><?php echo $this->ta_model->get_jml_bimbingan($row->id_user,'Pembimbing 3','0','0')->jml; ?></a><br><br><a style="color: blue;" href="<?php echo site_url("dosen/koordinator/rekap/bimbingan-dosen/detail?jenis=pb3&strata=s1&dosen=".$this->encrypt->encode($row->id_user))?>"><?php echo $this->ta_model->get_jml_bimbingan($row->id_user,'Pembimbing 3','1','5')->jml; ?></a><br><br><a style="color: blue;" href="<?php echo site_url("dosen/koordinator/rekap/bimbingan-dosen/detail?jenis=pb3&strata=s2&dosen=".$this->encrypt->encode($row->id_user))?>"><?php echo $this->ta_model->get_jml_bimbingan($row->id_user,'Pembimbing 3','2','2')->jml; ?></a><br><br><a style="color: blue;" href="<?php echo site_url("dosen/koordinator/rekap/bimbingan-dosen/detail?jenis=pb3&strata=s3&dosen=".$this->encrypt->encode($row->id_user))?>"><?php echo $this->ta_model->get_jml_bimbingan($row->id_user,'Pembimbing 3','3','3')->jml; ?></a></b></td>
                    <!-- Ps 1-->               <td style="border-bottom:1pt solid black; text-align:center"><a style="color: blue;" href="<?php echo site_url("dosen/koordinator/rekap/bimbingan-dosen/detail?jenis=ps1&strata=d3&dosen=".$this->encrypt->encode($row->id_user))?>"><b><?php echo $this->ta_model->get_jml_bimbingan($row->id_user,'Penguji 1','0','0')->jml; ?></a><br><br><a style="color: blue;" href="<?php echo site_url("dosen/koordinator/rekap/bimbingan-dosen/detail?jenis=ps1&strata=s1&dosen=".$this->encrypt->encode($row->id_user))?>"><?php echo $this->ta_model->get_jml_bimbingan($row->id_user,'Penguji 1','1','5')->jml; ?></a><br><br><a style="color: blue;" href="<?php echo site_url("dosen/koordinator/rekap/bimbingan-dosen/detail?jenis=ps1&strata=s2&dosen=".$this->encrypt->encode($row->id_user))?>"><?php echo $this->ta_model->get_jml_bimbingan($row->id_user,'Penguji 1','2','2')->jml; ?></a><br><br><a style="color: blue;" href="<?php echo site_url("dosen/koordinator/rekap/bimbingan-dosen/detail?jenis=ps1&strata=s3&dosen=".$this->encrypt->encode($row->id_user))?>"><?php echo $this->ta_model->get_jml_bimbingan($row->id_user,'Penguji 1','3','3')->jml; ?></a></b></td>
                    <!-- Ps 2-->               <td style="border-bottom:1pt solid black; text-align:center"><a style="color: blue;" href="<?php echo site_url("dosen/koordinator/rekap/bimbingan-dosen/detail?jenis=ps2&strata=d3&dosen=".$this->encrypt->encode($row->id_user))?>"><b><?php echo $this->ta_model->get_jml_bimbingan($row->id_user,'Penguji 2','0','0')->jml; ?></a><br><br><a style="color: blue;" href="<?php echo site_url("dosen/koordinator/rekap/bimbingan-dosen/detail?jenis=ps2&strata=s1&dosen=".$this->encrypt->encode($row->id_user))?>"><?php echo $this->ta_model->get_jml_bimbingan($row->id_user,'Penguji 2','1','5')->jml; ?></a><br><br><a style="color: blue;" href="<?php echo site_url("dosen/koordinator/rekap/bimbingan-dosen/detail?jenis=ps2&strata=s2&dosen=".$this->encrypt->encode($row->id_user))?>"><?php echo $this->ta_model->get_jml_bimbingan($row->id_user,'Penguji 2','2','2')->jml; ?></a><br><br><a style="color: blue;" href="<?php echo site_url("dosen/koordinator/rekap/bimbingan-dosen/detail?jenis=ps2&strata=s3&dosen=".$this->encrypt->encode($row->id_user))?>"><?php echo $this->ta_model->get_jml_bimbingan($row->id_user,'Penguji 2','3','3')->jml; ?></a></b></td>
                    <!-- Ps 3-->               <td style="border-bottom:1pt solid black; text-align:center"><a style="color: blue;" href="<?php echo site_url("dosen/koordinator/rekap/bimbingan-dosen/detail?jenis=ps3&strata=d3&dosen=".$this->encrypt->encode($row->id_user))?>"><b><?php echo $this->ta_model->get_jml_bimbingan($row->id_user,'Penguji 3','0','0')->jml; ?></a><br><br><a style="color: blue;" href="<?php echo site_url("dosen/koordinator/rekap/bimbingan-dosen/detail?jenis=ps3&strata=s1&dosen=".$this->encrypt->encode($row->id_user))?>"><?php echo $this->ta_model->get_jml_bimbingan($row->id_user,'Penguji 3','1','5')->jml; ?></a><br><br><a style="color: blue;" href="<?php echo site_url("dosen/koordinator/rekap/bimbingan-dosen/detail?jenis=ps3&strata=s2&dosen=".$this->encrypt->encode($row->id_user))?>"><?php echo $this->ta_model->get_jml_bimbingan($row->id_user,'Penguji 3','2','2')->jml; ?></a><br><br><a style="color: blue;" href="<?php echo site_url("dosen/koordinator/rekap/bimbingan-dosen/detail?jenis=ps3&strata=s3&dosen=".$this->encrypt->encode($row->id_user))?>"><?php echo $this->ta_model->get_jml_bimbingan($row->id_user,'Penguji 3','3','3')->jml; ?></a></b></td>
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
                        