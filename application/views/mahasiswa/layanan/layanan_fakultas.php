

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
                                    <div>Form Layanan <?php echo $layanan; ?>
                                        <div class="page-title-subheading">
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="page-title-actions">
                                    <a href="<?php echo site_url("mahasiswa/layanan-fakultas/$jns/form") ?>" class="btn-shadow btn btn-success">
                                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-file fa-w-20"></i>
                                            </span>
                                            Tambah Form
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
                                            <th style="width: 30%;">Nama Form</th>
                                            <th style="width: 10%;">Waktu</th>
                                            <th style="width: 30%;">Keterangan</th>
                                            <th style="width: 10%;">Status</th>
                                            <th style="width: 15%;">Aksi</th>
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
                                            foreach($form as $row) {
                                              
                                        ?>
                                        <tr>
                                            <td class="align-top">
                                                <?php echo ++$n; ?>
                                            </td>

                                            <td class="align-top">
                                                <?php echo $this->layanan_model->select_layanan_by_id($row->id_layanan_fakultas)->nama; ?>
                                            </td>

                                            <td class="align-top">
                                                <?php 
                                                    $wkt = explode("-",substr($row->created_at,0,10));
                                                    $waktu = $wkt[2]."-".$wkt[1]."-".$wkt[0];
                                                    echo $waktu; 
                                                ?>
                                            </td>

                                            <td class="align-top">
                                                <?php 
                                                $keterangan = $this->layanan_model->get_keterangan_form($row->id);
                                                $m = 1;
                                                if(!empty($keterangan)){
                                                    foreach($keterangan as $ket){
                                                        echo "<b>$m. $ket->nama : </b>$ket->meta_value<br>";
                                                        $m++;
                                                    }
                                                }else{ echo "-"; }
                                                ?>
                                            </td>

                                            <td class="align-top">
                                               <?php 
                                                if($row->status == 0){
                                                    echo "Menunggu";
                                                }elseif($row->status == 1){
                                                    echo "Fakultas";
                                                }elseif($row->status == 2){
                                                    echo "Selesai";
                                                }
                                               
                                               ?>
                                            </td>

                                            <td class="align-top">
                                            <?php 
                                            if(($row->tingkat == null || $row->tingkat == "") && $row->status < 1){
                                                //bebas lab
                                            if($row->id_layanan_fakultas == 2 ){

                                            ?>
                                            <a href="<?php echo site_url("/mahasiswa/layanan-fakultas/akademik/bebas-lab")  ?> " class="btn-wide mb-2 btn btn-primary btn-sm">Ajukan</a>
                                            <?php
                                                }else{
                                            ?>

                                                <a href="<?php echo site_url("/mahasiswa/layanan-fakultas/$jns/ajukan?id=".$this->encrypt->encode($row->id))  ?> " class="btn-wide mb-2 btn btn-primary btn-sm">Ajukan</a>
                                                &emsp;
                                                <a data-toggle = "modal" data-id="<?php echo $row->id ?>" data-jns="<?php echo $jns ?>" class="passingID" >
                                                    <span type="button" class="btn mb-2 btn-danger btn-sm "  data-toggle="modal" data-target="#delFormMhs">
                                                        <i class="fa fa-trash" aria-hidden="true"></i> 
                                                    </span>
                                                </a>
                                            <?php }
                                            }else{ ?>
                                                <a href="<?php echo site_url("/mahasiswa/layanan-fakultas/$jns/unduh?id=".$row->id."&layanan=".$row->id_layanan_fakultas) ?>"><i class="fa fa-download" aria-hidden="true"></i></a>
                                               
                                            <?php }?>
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
     
</script>