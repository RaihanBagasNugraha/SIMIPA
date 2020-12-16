

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-note icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <div>Kelola Laporan Kegiatan
                                        <div class="page-title-subheading">
                                            Unggah Laporan Kegiatan
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="page-title-actions">
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
                                            <th style="width: 15%;">Nama LK</th>
                                            <th style="width: 25%;">Nama Kegiatan</th>
                                            <th style="width: 15%;">Usulan</th>
                                            <th style="width: 20%;">Keterangan</th>
                                            <th style="width: 10%;">Status</th>
                                            <th style="width: 15%;">Aksi</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if(empty($proposal))
                                        {
                                            echo "<tr><td colspan='6'>Data tidak tersedia</td></tr>";
                                        }
                                        else
                                        {
                                            $no = 0;
                                            foreach($proposal as $row) {
                                        ?>
                                            <tr>
                                                <td class="align-top"><?php echo ++$no; ?></td>
                                                <td class="align-top"><b><?php echo $this->layanan_model->get_lk_by_id($row->id_lk)->nama_lk; ?></b></td>
                                                <td class="align-top"><?php echo $row->nama ?></td>
                                                <td class="align-top">Rp<?php echo number_format($row->nilai) ?></td>
                                                <td class="align-top">
                                                <?php 
                                                    echo "<b>Diajukan Oleh : </b>".$this->user_model->get_mahasiswa_name($row->insert_by)." / ".$row->insert_by;
                                                    echo "<br>";
                                                    echo "<b>Lampiran : </b>";
                                                    $lampiran = $this->layanan_model->get_lampiran_proposal($row->id);
                                                    if(empty($lampiran)) {
                                                        echo "<i>(Belum ada, silakan lengkapi berkas lampiran)</i>";
                                                    } else {
                                                       
                                                        foreach($lampiran as $rw) {
                                                            echo "<li><a href='".base_url($rw->file)."' download>".$rw->nama_berkas."</a></li>";
                                                        }
                                                        echo "</ul>";
                                                    }
                                                    if($row->status == '-1'){
                                                        echo "<br>";
                                                        echo "<b>Keterangan Tolak : </b>";
                                                        echo "<span style='color:red'>$row->keterangan</span>";
                                                    }
                                                    
                                                ?>
                                                </td>
                                                <td class="align-top">
                                                <?php  
                                                    if($row->status == 2){
                                                        echo "<span style='color:red'>Menunggu</span>";
                                                    }elseif($row->status == 3){
                                                        echo "<span style='color:blue'>Selesai</span>";
                                                    }
                                                ?>
                                                </td>
                                                <td class="align-top">
                                                <?php if($row->status == 2){ ?>
                                                    <a data-toggle = "modal" data-id="<?php echo $row->id ?>" class="passingID" >
                                                        <button type="button" class="btn mb-2 btn-wide btn-danger btn-sm btn-block"  data-toggle="modal" data-target="#unggahlap">
                                                            Unggah Laporan Kegiatan 
                                                        </button>
                                                    </a>
                                                <?php }else{ ?>
                                                    <a style="" href="<?php echo $row->laporan ?>" target="_blank" class="mr-1 mb-1 btn btn-info btn-sm" >Laporan Kegiatan
                                                <?php } ?>    
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
                $("#ID_lap").val( id );
            });       
</script>