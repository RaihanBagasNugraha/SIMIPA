

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-note icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <div>Persetujuan KP/PKL
                                        <div class="page-title-subheading">
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
                        $periode_al = $this->input->get('periode');
                        $id_alamat = $this->input->get('id');
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
                                <?php 
                                    $get_lokasi = $this->pkl_model->get_lokasi_pkl_by_id($pkl->lokasi_id);
                                    $periode = $this->pkl_model->get_pkl_kajur_by_id($get_lokasi->id_pkl);  ?>
                                <p style="font-size:110%;">Tahun / Periode : <?php echo $periode->tahun." / ".$periode->periode ?></p>
                                <p style="font-size:110%;">Lokasi : <?php echo $get_lokasi->lokasi ?><br>Alamat : <?php echo $get_lokasi->alamat ?></p>
                                <p style="font-size:110%;">Nomor Surat : <b><?php echo $pkl->no_penetapan ?></b></p>

                                <?php 
                                    if($pkl->status == 1){ ?>
                                        
                                        <div class="position-relative row form-group">
                                            <div class="col-sm-10">
                                            <p style="font-size:110%;">Berkas Persetujuan Instansi : <b><a style="width: 60px;" href="<?php echo base_url() ?>" class="mr-1 mb-1 btn btn-danger btn-sm disabled" download>Unduh</a></b></p>                                
                                            </div>
                                        </div>
                                <?php        
                                    }elseif($pkl->status == 2){ ?>
                                       <div class="position-relative row form-group">
                                            <div class="col-sm-10">
                                                <p style="font-size:110%;">Berkas Persetujuan Instansi : <b><a style="width: 60px;" href="<?php echo base_url($pkl->file) ?>" class="mr-1 mb-1 btn btn-success btn-sm" download>Unduh</a></b></p>
                                            </div>
                                        </div>
                                <?php 
                                    }
                                    elseif($pkl->status == 3){ ?>
                                    <div class="position-relative row form-group">
                                            <div class="col-sm-10">
                                                <p style="font-size:110%;">Berkas Persetujuan Instansi : <b><a style="width: 60px;" href="<?php echo base_url($pkl->file) ?>" class="mr-1 mb-1 btn btn-primary btn-sm" download>Unduh</a></b></p>
                                            </div>
                                    </div>
                                <?php
                                    }                               
                                ?>                         
                                <div class="table-responsive">
                                    <table class="mb-0 table table-striped" id="example">
                                        <thead>
                                        <tr>
                                            <th stlye="width:5%" >#</th>
                                            <th stlye="width:20%" >Npm/Nama</th>
                                            <th stlye="width:5%">IPK/SKS</th>
                                            <th stlye="width:20%">Tanggal</th>
                                            <th stlye="width:40 px">Berkas</th>
                                            <!-- <th>Status</th> -->
                                            <th stlye="width:10%">Aksi</th>
                                            <th stlye="width:10%">Status</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                       
                                        <?php
                                        if(empty($pkl))
                                        {
                                            echo "<tr><td colspan='6'>Data tidak tersedia</td></tr>";
                                        }
                                        else
                                        {
                                            $n = 1;
                                            $c = 0;
                                            $list = $this->pkl_model->get_mahasiswa_lokasi_daftar_koor($pkl->lokasi_id,$this->session->userdata('userId'),$pkl->no_penetapan);
                                            $jml = count($list);
                                            // echo $jml;
                                            foreach($list as $row){   
                                        ?>
                                            <tr>
                                             <td class="align-top">
                                                    <?php 
                                                        echo $n;
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
                                                        echo "<b>IPK </b>: ".$row->ipk;
                                                        echo "<br>";
                                                        echo "<b>SKS </b>: ".$row->sks;
                                                    ?>
                                                </td>
                                                <td class="align-top">
                                                    <?php 
                                                        $tgl_pengajuan = $this->pkl_model->get_surat_pkl($row->pkl_id); 
                                                        echo "<b>Tanggal Mengajukan : </b><br>".substr($row->created_at,0,16);
                                                        echo "<br>";
                                                        if(empty($tgl_pengajuan)){
                                                            echo "<b><i>Sedang Proses Verifikasi Berkas</i></b>";
                                                        }
                                                        else{
                                                            if($tgl_pengajuan->updated_at != NULL || $tgl_pengajuan->updated_at != ""){
                                                                echo "<b>Tanggal Verifikasi Berkas : </b><br>".substr($tgl_pengajuan->updated_at,0,16);
                                                            }
                                                            else{
                                                                echo "<b><i>Sedang Proses Verifikasi Berkas</i></b>";
                                                            }
                                                        }
                                                    //    echo $row->status;
                                                    ?>

                                                </td>

                                                <td class="align-top">
                                                    <?php 
                                                         $lampiran = $this->pkl_model->select_lampiran_by_pkl($row->pkl_id, $row->npm); 
                                                         if(empty($lampiran)) {
                                                             echo "<i>(Belum ada, silakan lengkapi berkas lampiran)</i>";
                                                         } else {
                                                         
                                                             foreach($lampiran as $rw) {
                                                                 echo "<li><a href='".base_url($rw->file)."' download>".$rw->nama_berkas."</a></li>";
                                                             }
             
                                                             echo "</ul>";
                                                         }
                                                         
                                                    ?>
                                                </td>
                                               
                                                <td class="align-top">
                                                <?php if(!empty($tgl_pengajuan) && $row->status == "2" ){ ?>
                                                    <a data-toggle = "modal" data-id="<?php echo $row->pkl_id ?>" data-lokasi="<?php echo $row->id_lokasi ?>" data-surat="<?php echo $row->no_penetapan ?>" data-alm="<?php echo $periode_al."#$#$".$id_alamat ?>" approval-id="<?php echo $pkl->approval_id ?>" class="passingID1" >
                                                            <button type="button" class="btn mb-2 btn-wide btn-info btn-sm btn-block"  data-toggle="modal" data-target="#PklKoorSetuju">
                                                                Setujui
                                                            </button>
                                                    </a> 
                                                    <a data-toggle = "modal" data-id="<?php echo $row->pkl_id."#$#$"."koor" ?>" data-add="<?php echo $periode_al."#$#$".$id_alamat ?>" class="passingID2" >
                                                            <button type="button" class="btn mb-2 btn-wide btn-danger btn-sm btn-block"  data-toggle="modal" data-target="#PklKoorTolak">
                                                                Tolak
                                                            </button>
                                                    </a> 
                                                <?php }
                                                elseif(!empty($tgl_pengajuan) && $row->status >= 3){ 
                                                    $dosen_pmb = $this->user_model->get_dosen_name($row->pembimbing);
                                                    echo "<b>Dosen Pembimbing :</b><br>";
                                                    echo $dosen_pmb->gelar_depan." ".$dosen_pmb->name.", ".$dosen_pmb->gelar_belakang;                                                   
                                                }else{ echo "-"; } ?>
                                                    
                                                </td>
                                                <td class="align-top">
                                                    <?php 
                                                    if($row->status == 7){
                                                        echo "<b>Disetujui</b>";
                                                        $c++;
                                                    }
                                                    elseif($row->status == 8){
                                                        echo "<b>Disetujui</b>";
                                                        $c = 0;
                                                    }
                                                    else{
                                                        echo "<b>Menunggu <br>Approval<br> Koordinator</b>";
                                                    } ?>
                                                </td>
                                               
                                            </tr>
                                        <?php
                                          $n++; 
                                            }
                                        }
                                        ?>
                                        </tbody>
                                        <tfoot>
                                            <td colspan="6">
                                            </td>
                                            <td>
                                            <?php if($jml == $c){$button = "";}else{$button = "disabled";} ?>
                                                <a href="<?php echo site_url("dosen/struktural/pkl/approve-pkl/setujui?status=kajur&id=".$this->encrypt->encode($pkl->approval_id)) ?>" class="btn-wide mb-1 btn btn-primary btn-sm btn-block <?php echo $button ?>" >Setujui</a>
                                            </td>
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
    $(".passingID1").click(function () {
                var id = $(this).attr('data-id');
                var lokasi = $(this).attr('data-lokasi');
                var al = $(this).attr('data-alm');
                var almt = al.split("#$#$");
                var srt = $(this).attr('data-surat');
                var approval= $(this).attr('approval-id');
                $("#ID_pkl").val( id );
                $("#Lokasi").val( lokasi );
                $("#periode1").val( almt[0] );
                $("#id_al1").val( almt[1] );
                $("#surat").val( srt );
                $("#approval_id").val( approval );
    });      

    $(".passingID2").click(function () {
                var id = $(this).attr('data-id');
                var al = $(this).attr('data-add');
                var data = id.split("#$#$");
                var almt = al.split("#$#$");
                $("#ID").val( data[0] );
                $("#status").val( data[1] );
                $("#periode").val( almt[0] );
                $("#id_al").val( almt[1] );
    });      

    $(".passingID3").click(function () {
                var id = $(this).attr('data-id');
                var al = $(this).attr('data-add');
                var almt = al.split("#$#$");
                $("#approval").val( id );
                $("#periode_almt").val( almt[0] );
                $("#id_almt").val( almt[1] );
    });      
       
</script>
                        