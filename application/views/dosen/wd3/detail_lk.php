

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-note icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    
                                    <div>Detail Lembaga Kemahasiswaan 
                                        <div class="page-title-subheading">
                                            <b><?php echo $lk->nama_lk ?></b>
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

                            echo '<div class="alert alert-success fade show" role="alert">Berhasil Menyimpan Data</div>';
                        }
                        ?>
                            
                         <div class="main-card mb-3 card">
                                <div class="card-body">
                                <div class="table-responsive">
                                    <table class="mb-0 table table-striped" id="example">
                                        <thead>
                                        <tr>
                                            <th style="width: 5%;">No</th>
                                            <th style="width: 10%;">Periode</th>
                                            <th style="width: 50%;">Struktur Organisasi</th>
                                            <th style="width: 15%;">Verifikasi</th>
                                        </tr>
                                      
                                        </thead>
                                        <tbody>
                                        <?php
                                        if(empty($periode))
                                        {
                                            echo "<tr><td colspan='6'>Data tidak tersedia</td></tr>";
                                        }
                                        else
                                        {
                                            $no = 0;
                                            foreach($periode as $row) {
                                        ?>
                                        <tr>
                                            <td class="align-top"><?php echo ++$no; ?></td>
                                            <td class="align-top"><b><?php echo $row->periode; ?></b></td>
                                            <td class="align-top">
                                              <?php  
                                                    $tugas = $this->layanan_model->get_lk_by_periode_lk2($row->periode,$lk->id_lk);
                                                    $ketua = $this->layanan_model->get_jabatan_lk($lk->id_lk,$row->periode,1);
                                                    $wakil = $this->layanan_model->get_jabatan_lk($lk->id_lk,$row->periode,2);
                                                    $sekretaris = $this->layanan_model->get_jabatan_lk($lk->id_lk,$row->periode,3);
                                                    $bendahara = $this->layanan_model->get_jabatan_lk($lk->id_lk,$row->periode,4);
                                              ?>
                                              <b>Ketua : </b><?php echo empty($ketua) ? "-" : $this->user_model->get_mahasiswa_data($ketua->id_user)->name." (".$this->user_model->get_mahasiswa_data($ketua->id_user)->npm.")" ?><br>
                                              <b>Wakil : </b><?php echo empty($wakil) ? "-" : $this->user_model->get_mahasiswa_data($wakil->id_user)->name." (".$this->user_model->get_mahasiswa_data($wakil->id_user)->npm.")" ?><br>
                                              <b>Sekretaris : </b><?php echo empty($sekretaris) ? "-" : $this->user_model->get_mahasiswa_data($sekretaris->id_user)->name." (".$this->user_model->get_mahasiswa_data($sekretaris->id_user)->npm.")" ?><br>
                                              <b>Bendahara : </b><?php echo empty($bendahara) ? "-" : $this->user_model->get_mahasiswa_data($bendahara->id_user)->name." (".$this->user_model->get_mahasiswa_data($bendahara->id_user)->npm.")" ?><br>
                                            </td>
                                            <td class="align-top">
                                                <?php if(!empty($ketua) && !empty($wakil) && !empty($sekretaris) && !empty($bendahara)){ ?>
                                                    <?php if($tugas->verifikasi == 0){ ?>
                                                    <a data-toggle = "modal" data-id="<?php echo $row->periode ?>" data-lk="<?php echo $lk->id_lk ?>" class="passingID" >
                                                        <button type="button" class="btn mb-2 btn-success btn-sm "  data-toggle="modal" data-target="#VerifLk">
                                                            <span class="btn-icon-wrapper pr-2 opacity-7"><i class="fas fa-check fa-w-20"></i></span>
                                                        </button>
                                                    </a>
                                                    <?php } else {echo "<span style='color:green'>Diverifikasi</span>"; }?>
                                                <?php } else{echo "<span style='color:red'><i>Struktur Organisasi Belum Diisi Oleh Mahasiswa</i></span>";} ?>
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
<script src="<?php echo site_url("assets/scripts/jquery_3.4.1_jquery.min.js") ?>"></script>
<script src="<?php echo site_url("assets/scripts/select2.full.js") ?>"></script>
<script type="text/javascript">
$(document).ready(function(){
    $(".readonly").on('keydown paste', function(e){
        e.preventDefault();
        $(this).blur();
    });
    
    $(".readonly").mousedown(function(e){
        e.preventDefault();
        $(this).blur();
        return false;
        });

    $("select").select2({
        theme: "bootstrap"
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
    $(".passingID").click(function () {
        var id = $(this).attr('data-id');
        var lk = $(this).attr('data-lk');
        $("#periode").val(id);
        $("#ID3").val(lk);
    });     
     
</script>