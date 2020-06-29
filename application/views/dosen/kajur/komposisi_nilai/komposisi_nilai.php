

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-note icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <div>Komposisi Nilai
                                        <div class="page-title-subheading">
                                        </div>
                                    </div>
                                </div>
                                <div class="page-title-actions">
                                    <a href="<?php echo site_url("dosen/struktural/komposisi-nilai/add") ?>" class="btn-shadow btn btn-success">
                                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-file fa-w-20"></i>
                                            </span>
                                            Tambah Komposisi Nilai
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
                                    <table class="mb-0 table table-striped"  id="example">
                                        <thead>
                                        <tr>
                                            <th>Nomor</th>
                                            <th>Jenis</th>
                                            <th>Tahun Akademik</th>
                                            <th>Komponen Nilai</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if(empty($nilai))
                                        {
                                            echo "<tr><td colspan='6'>Data tidak tersedia</td></tr>";
                                        }
                                        else
                                        {
                                            $no = 1;
                                            foreach($nilai as $row) {
                                                
                                        ?>
                                            <tr>
                                                <td class="align-top">
                                                    <?php echo $no++;?>
                                                </td>
                                                <td class="align-top">
                                                    <?php echo $row->jenis;?>
                                                </td>
                                                <td class="align-top">
                                                    <?php echo $row->tahun_akademik;?>
                                                </td>
                                                <td class="align-top">
                                                    <a href=<?php echo site_url("dosen/struktural/komposisi-nilai/komponen?id=$row->id") ?>><label class = "btn btn-primary"><?php echo "Komponen Nilai";?></label></a>
                                                </td>
                                                <td class="align-top">
                                                    <?php echo $row->status == 0 ? "Aktif" : "Nonaktif";?>
                                                </td>
                                                <td class="align-top">

                                                <?php if($row->status != 1){?>
                                                    <a href=<?php echo site_url("dosen/struktural/komposisi-nilai/ubah?id=$row->id") ?>><label class = "btn-wide mb-2 btn btn-warning btn-sm btn-block"><?php echo "Ubah";?></label></a>

                                                    <a data-toggle = "modal" data-id="<?php echo $row->id ?>" class="passingID" >
                                                        <button type="button" class="btn mb-2 btn-wide btn-danger btn-sm btn-block"  data-toggle="modal" data-target="#nonaktifkan">
                                                            Nonaktifkan 
                                                        </button>
                                                    </a>

                                                <?php } else{echo "-";}?>    
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
    $(".passingID").click(function () {
                var id = $(this).attr('data-id');
                $("#ID").val( id );

            });
      
</script>
                        