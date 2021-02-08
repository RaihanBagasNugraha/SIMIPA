

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
                                    <!--<a href="<?php echo site_url("mahasiswa/tugas-akhir/tema/form") ?>" class="btn-shadow btn btn-success">-->
                                    <!--        <span class="btn-icon-wrapper pr-2 opacity-7">-->
                                    <!--            <i class="fas fa-file fa-w-20"></i>-->
                                    <!--        </span>-->
                                    <!--        Form Pengajuan Tema-->
                                    <!--</a>-->
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
                        // if ($akun->ttd == NULL){
                        //     echo "<script>
                        //     alert('Silahkan Lengkapi Informasi Akun & Biodata Anda Terlebih Dahulu');
                        //     window.location.href='biodata';
                        //     </script>";
                        // } 
                        
                        ?>
                        
                         <div class="main-card mb-3 card">
                                <div class="card-body">       
                                <?php $periode = $this->pkl_model->get_pkl_kajur_by_id($pkl->id_pkl);  ?>
                                <p style="font-size:110%;">Tahun / Periode : <?php echo $periode->tahun." / ".$periode->periode ?></p>
                                <p style="font-size:110%;">Lokasi : <?php echo $pkl->lokasi ?><br>Alamat : <?php echo $pkl->alamat ?></p>                         
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
                                            $a = 0;
                                            $list = $this->pkl_model->get_mahasiswa_lokasi_daftar_tendik($pkl->id,$this->session->userdata('userId'));
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
                                                        if(empty($tgl_pengajuan) && $row->status != 5){
                                                            echo "<b><i>Sedang Proses Approval Pembimbing Akademik</i></b>";
                                                        }
                                                        elseif($row->status == 5){
                                                            echo "<b><i>Sedang Proses Perbaikan Berkas Pendukung</i></b>";
                                                            
                                                        }
                                                        else{
                                                            echo "<b>Tanggal Berkas : </b><br>".substr($tgl_pengajuan->created_at,0,16);
                                                            $a += 1;
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
                                                <?php if(!empty($tgl_pengajuan) && $row->status == "1" ){ ?>
                                                    <!-- <a href="<?php echo site_url("tendik/verifikasi-berkas/pkl/setujui?status=admin&id=".$this->encrypt->encode($row->pkl_id)) ?>" class="btn-wide mb-1 btn btn-primary btn-sm btn-block">Setujui
                                                    </a> -->
                                                    <a data-toggle = "modal" data-id="<?php echo $row->pkl_id ?>" periode="<?php echo $periode_al ?>" id_alamat="<?php echo $id_alamat ?>" data-status="admin" class="passingID" >
                                                            <button type="button" class="btn mb-2 btn-wide btn-danger btn-sm btn-block"  data-toggle="modal" data-target="#ApprovalperbaikiAdm">
                                                                Perbaiki
                                                            </button>
                                                    </a> 
                                                <?php }elseif(!empty($tgl_pengajuan) && $row->status == 3){ echo "<b>Disetujui</b>";}else{ echo "<b>Menunggu</b>"; } ?>
                                                    
                                                </td>
                                               
                                            </tr>
                                        <?php
                                          $n++;  }
                                        }
                                        ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="5"><i>Silahkan Lakukan Approval Setelah Verifikasi Berkas Selesai</i></td>
                                                <td> 
                                                <form id="form" method="post" action="<?php echo site_url('tendik/verifikasi-berkas/pkl/approve/ttd') ?>"> 
                                                    <input type="hidden" name="jumlah" value="<?php echo $jml ?>">
                                                    <input type="hidden" name="lokasi" value="<?php echo $pkl->id ?>">
                                                    <?php 
                                                    $b=1;
                                                    foreach($list as $row){ ?>
                                                        <input type="hidden" name="id[<?php echo $b ?>]" value = "<?php echo $row->pkl_id?>" >
                                                    <?php $b++; } ?>
                                                    
                                                     <?php if($jml == $a && $jml != 0){$button = "";}else{$button = "disabled";} ?>
                                                   <button type="submit" class="btn mb-2 btn-wide btn-primary btn-sm btn-block" <?php echo $button ?> id="btn-submit">
                                                        Setujui
                                                    </button>
                                                </form>
                                                </td>
                                                
                                            </tr>
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


    })   
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

    // $("#btn-submit").click(function(e){
    //     e.preventDefault()
    //     $("#form").submit()
    // });
});

</script>

<script>
   $(".passingID").click(function () {
                var id = $(this).attr('data-id');
                var status = $(this).attr('data-status');
                var periode = $(this).attr('periode');
                var id_alamat = $(this).attr('id_alamat');
                $("#ID").val( id );
                $("#status").val( status );
                $("#periode").val( periode );
                $("#id_alamat").val( id_alamat );
            });        


</script>
                        