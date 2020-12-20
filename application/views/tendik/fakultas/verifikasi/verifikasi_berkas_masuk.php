

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-note icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <?php 
                                        $jenis = $this->uri->segment(3);
                                        if($jenis == "akademik"){
                                            $layanan = "Akademik";
                                        }elseif($jenis == "umum-keuangan"){$layanan = "Umum dan Keuangan";}
                                        elseif($jenis == "kemahasiswaan"){$layanan = "Kemahasiswaan";}
                                        else{$layanan= "";}
                                    
                                    ?>
                                    <div>Verifikasi Pengajuan Masuk Layanan <?php echo $layanan ?>
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
                        $seg = $this->uri->segment(3);
                        ?>
                <div class="row">    
                    <div class="col-md-12">
                    <div class="card mb-4 widget-chart  card-btm-border card-shadow-primary border-primary">
                            <div class="text-left ml-1 mr-1 mt-2">
                            
                            <form method="post" id= "form" action = "<?php echo site_url("tendik/verifikasi-berkas-masuk-fakultas/".$this->uri->segment(3)); ?>">
                                        <div class="form-row">
                                            <div class="col-md-2">
                                                <div class="position-relative form-group">
                                                    <label for="bulan" class = ''><b>Kode Form :</b></label>
                                                </div>
                                            </div>

                                            <div class="col-md-8">
                                                <div class="position-relative form-group">
                                                    <input type="text" class='form-control' value='' placeholder='Masukkan kode untuk mengecek form pengajuan yang diajukan melalui loket' name='kode' required />
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="position-relative form-group">
                                                    <button type="submit" id='btnSubmit' class="btn-shadow btn-lg btn btn-primary ">
                                                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                                                <i class="fa fa-search fa-w-20"></i>
                                                            </span>
                                                        Cari
                                                    </button>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </form>  
                            </div>
                            
                        </div>

                         <div class="main-card mb-3 card">
                                <div class="card-body">
                                <div class="table-responsive">
                                    <table class="mb-0 table table-striped" id="example">
                                        <thead>
                                        <tr>
                                            <!-- <th style="width: 5%;">#</th> -->
                                            <th style="width: 13%;">Waktu Pengajuan</th>
                                            <th style="width: 25%;">Npm<br>Nama</th>
                                            <th style="width: 15%;">Jenis</th>
                                            <th style="width: 10%;">Kode</th>
                                            <th style="width: 25%;">Lampiran</th>
                                            <th style="width: 20%;">Aksi</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if(empty($form))
                                        {
                                            echo "<tr><td colspan='7'>Data tidak tersedia</td></tr>";
                                        }
                                        else
                                        {
                                            $n = 0;
                                            $jml = count($form);
                                            foreach($form as $row) {
                                        ?>
                                        <tr>
                                            <!-- <td class="align-top">
                                                <?php echo ++$n; ?>
                                            </td> -->

                                            <td class="align-top">
                                                <?php 
                                                if($cek == 1){
                                                    $waktu = explode('-',substr($row->created_at,0,10));
                                                    echo $waktu[2]."-".$waktu[1]."-".$waktu[0];
                                                    echo "<br>";
                                                    echo substr($row->created_at,10);
                                                }else{
                                                    $waktu = explode('-',substr($row->updated_at,0,10));
                                                    echo $waktu[2]."-".$waktu[1]."-".$waktu[0];
                                                    echo "<br>";
                                                    echo substr($row->updated_at,10);
                                                }
                                                    
                                                 ?>
                                            </td>

                                    
                                            <td class="align-top">
                                               <?php 
                                                   echo $row->npm;
                                                   echo "<br>";
                                                   echo $this->user_model->get_mahasiswa_name($row->npm);
                                               ?>
                                            </td>

                                            <td class="align-top">
                                               <?php 
                                                 echo $this->layanan_model->get_layanan_fakultas_by_id($row->id_layanan_fakultas)->nama;
                                               ?>
                                            </td>

                                            <td class="align-top">
                                               <?php echo "<b>$row->kode</b>"; ?>
                                            </td>

                                            <td class="align-top">
                                                <?php 
                                                 if($cek == 1){ 
                                                    echo "Loket";
                                                 }else{
                                                    echo "<li><a href='".site_url('/mahasiswa/layanan-fakultas/'.$seg.'/unduh?id='.$this->encrypt->encode($row->id).'&layanan='.$row->id_layanan_fakultas)."'>".$this->layanan_model->get_layanan_fakultas_by_id($row->id_layanan_fakultas)->nama."</a></li>";
                                                   $lampiran = $this->layanan_model->get_lampiran_layanan_list($row->id); 
                                                   if(empty($lampiran)) {
                                                       echo "<i>(Belum ada, silakan lengkapi berkas lampiran)</i>";
                                                   } else {
                                                      
                                                       foreach($lampiran as $rw) {
                                                           echo "<li><a href='".base_url($rw->file)."' download>".$rw->nama_berkas."</a></li>";
                                                       }
                                                       
       
                                                       echo "</ul>";
                                                   }
                                                }
                                                ?>
                                            </td>

                                            <td class="align-top">
                                            <?php  if($cek != 1){ ?>
                                                <a data-toggle = "modal" data-id="<?php echo $row->id ?>" class="verifikasi">
                                                    <button style="width: 80px;" type="button" class="mb-2 btn btn-primary btn-sm"  data-toggle="modal" data-target="#verifikasimasuk">
                                                        Verifikasi
                                                    </button>
                                                </a>
                                                
                                                <a data-toggle = "modal" data-id="<?php echo $row->id ?>" class="tolak">
                                                    <button style="width: 80px;" type="button" class="mb-2 btn btn-danger btn-sm"  data-toggle="modal" data-target="#tolakmasuk">
                                                        Tolak
                                                    </button>
                                                </a>
                                            <?php }else{ ?>
                                                <a data-toggle = "modal" data-id="<?php echo $row->id ?>" class="verifikasi2">
                                                    <button style="width: 80px;" type="button" class="mb-2 btn btn-primary btn-sm"  data-toggle="modal" data-target="#verifikasimasuk2">
                                                        Verifikasi
                                                    </button>
                                                </a>

                                            <?php } ?>
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
                    </div>
                </div>        
<script src="<?php echo site_url("assets/scripts/jquery_3.4.1_jquery.min.js") ?>"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js"></script>
<script src="<?php echo site_url("assets/scripts/dataTables.bootstrap4.min.js") ?>"></script>
<script src="<?php echo site_url("assets/scripts/DataTables-1.10.21/jquery.dataTables.min.js") ?>"></script>
<script type="text/javascript">
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

$(document).ready(function() {
    $('#example').DataTable();
} );

</script>

<script>
    $(".verifikasi").click(function () {
        var id = $(this).attr('data-id');
        $("#IDMasuk").val( id );
    });   
    $(".verifikasi2").click(function () {
        var id = $(this).attr('data-id');
        $("#IDMasuk2").val( id );
    });    
    $(".tolak").click(function () {
        var id = $(this).attr('data-id');
        $("#IDtolak").val( id );
    });                               
     
</script>