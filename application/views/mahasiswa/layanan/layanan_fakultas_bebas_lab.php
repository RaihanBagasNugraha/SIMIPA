

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-note icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <?php 
                                        $jns = $this->uri->segment(3);
                                        switch($jns){
                                            case "akademik":
                                            $layanan = "Akademik";
                                            break;
                                            case "kemahasiswaan":
                                            $layanan = "Kemahasiswaan";
                                            break;
                                            case "umum-keuangan":
                                            $layanan = "Umum dan Keuangan";
                                            break;
                                        }
                                    ?>
                                    <div>Form Layanan <?php echo $layanan; ?> Bebas Lab
                                        <div class="page-title-subheading">
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="page-title-actions">
                                    <a href="<?php echo site_url("mahasiswa/layanan-fakultas/$jns/bebas-lab/tambah") ?>" class="btn-shadow btn btn-success">
                                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-file fa-w-20"></i>
                                            </span>
                                            Tambah Pengajuan
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
                            
                         <div class="main-card mb-3 card">
                                <div class="card-body">
                                <div class="table-responsive">
                                    <table class="mb-0 table table-striped" id="example">
                                        <thead>
                                        <tr>
                                            <th style="width: 5%;">#</th>
                                            <th style="width: 30%;">Nama Laboratorium</th>
                                            <th style="width: 20%;">Status</th>
                                            <th style="width: 15%;">Waktu<br>Pengajuan/Approval</th>
                                            <th style="width: 30%;">Aksi</th>
                                            <!-- <th style="width: 15%;">Keterangan</th> -->
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if(empty($form))
                                        {
                                            echo "<tr><td colspan='6'>Data tidak tersedia</td></tr>";
                                        }
                                        else
                                        {
                                            $n = 0;
                                            $jml = count($form);
                                            foreach($form as $row) {
                                              
                                        ?>
                                        <tr>
                                            <td class="align-top">
                                                <?php echo ++$n; ?>
                                            </td>

                                            <td class="align-top">
                                                <?php 
                                                    // $waktu = explode('-',substr($row->created_at,0,10));
                                                    // echo $waktu[2]."-".$waktu[1]."-".$waktu[0];
                                                    echo $row->nama_lab;
                                                 ?>
                                            </td>

                                    
                                            <td class="align-top">
                                               <?php 
                                                    if($row->status_lab == '-1'){
                                                        echo "<span style='color:white' class='btn-wide mb-2 btn btn-dark btn-sm btn-block'>Belum Diajukan</span>";
                                                    }
                                                    elseif($row->status_lab == '0'){
                                                        echo "<span style='color:white' class='btn-wide mb-2 btn btn-warning btn-sm btn-block'>Diajukan</span>";
                                                    }
                                                    elseif($row->status_lab == '1'){
                                                        echo "<span style='color:white' class='btn-wide mb-2 btn btn-primary btn-sm btn-block'>Menunggu Approval Kepala Lab.</span>";
                                                    }
                                                    elseif($row->status_lab == '2' && $row->status_bebas != '2'){
                                                        echo "<span style='color:white' class='btn-wide mb-2 btn btn-success btn-sm btn-block'>Menunggu Approval Wakil Dekan</span>";
                                                    }
                                                    elseif($row->status_lab == '2' && $row->status_bebas == '2'){
                                                        echo "<span style='color:white' class='btn-wide mb-2 btn btn-info btn-sm btn-block'>Approval Wakil Dekan</span>";
                                                    }
                                                    elseif($row->status_lab == '3'){
                                                        echo "<span style='color:white' class='btn-wide mb-2 btn btn-danger btn-sm btn-block'>Tolak</span>";
                                                        echo "<b>Catatan :</b> $row->keterangan_tolak";
                                                        // echo "<br>";
                                                    }
                                               ?>
                                            </td>

                                            <td class="align-top">
                                            <?php 
                                                    if($row->updated_at == null){
                                                        echo "-";
                                                    }else{
                                                        $waktu = explode('-',substr($row->updated_at,0,10));
                                                        echo $waktu[2]."-".$waktu[1]."-".$waktu[0];
                                                    }
                                                    // echo $row->nama_lab;
                                                 ?>
                                            </td>

                                            <td class="align-top">
                                                <?php if($row->status == '-1' || $row->status == '3'){ ?>
                                                    <a href="<?php echo site_url("mahasiswa/layanan-fakultas/akademik/bebas-lab/form?id=".$this->encrypt->encode($row->id_meta)) ?>" class="btn-wide mb-2 btn btn-primary btn-sm">Lihat Lampiran</a>
                                                <?php 
                                                    $berkas = $this->layanan_model->get_berkas_lab_by_id($row->id_bebas_lab);
                                                    if(!empty($berkas)){
                                                ?>
                                                 <a data-toggle = "modal" data-id="<?php echo $row->id_meta ?>" data-nama="<?php echo $row->nama_lab ?>" class="passingIDA">
                                                    <button style="width: 60px;" type="button" class="mb-2 btn btn-success btn-sm"  data-toggle="modal" data-target="#Ajukanlab">
                                                       Ajukan
                                                    </button>
                                                </a>
                                                 
                                                <?php        
                                                    }
                                                 ?>
                                                <!-- <a data-toggle = "modal" data-id="<?php echo $row->id_meta ?>" class="passingIDs">
                                                    <button style="width: 60px;" type="button" class="mb-2 btn btn-danger btn-sm"  data-toggle="modal" data-target="#dellab">
                                                        Hapus
                                                    </button>
                                                </a> -->
                                                <?php } 
                                                elseif($row->status_lab == '2'){?>
                                                <a href="<?php echo site_url("/mahasiswa/unduh-bebas-lab?id=".$row->id_bebas_lab) ?>" class='btn-wide mb-2 btn btn-primary btn-sm'>Unduh &emsp;<i class="fa fa-download" aria-hidden="true"></i> </a>
                                                <?php
                                                }
                                                else{echo "<b>Menunggu</b>";} ?>
                                                
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
    $(".passingID").click(function () {
                var id = $(this).attr('data-id');
                var jns = $(this).attr('data-jns');
                $("#ID").val( id );
                $("#Jns").val( jns );
            });           
    $(".passingIDs").click(function () {
                var id = $(this).attr('data-id');
                $("#IDBebasLab").val( id );
            });   
    $(".passingIDA").click(function () {
                var id = $(this).attr('data-id');
                var nama = $(this).attr('data-nama');
                $("#IDMeta").val( id );
                $("#Nama").text( nama );
            });                      
     
</script>