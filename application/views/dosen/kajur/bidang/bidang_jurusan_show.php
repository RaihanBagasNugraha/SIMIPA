

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-note icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <div>Isi Bidang Ilmu Jurusan
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
                        $jurusan = $_GET['jurusan'];
                        $prodi = $_GET['prodi'];
                        ?>
                        
                         <div class="main-card mb-3 card">
                            <div class="card-header">Bidang Ilmu Jurusan 
                                    <?php 
                                        for($i=0;$i<=52;$i++){
                                            echo "&emsp;";
                                        }
                                    ?>
                                    <a data-toggle = "modal" jurusan="<?php echo $jurusan ?>" prodi="<?php echo $prodi ?>" class="passingIDTambah" >
                                            <button type="button" class="btn-wide mb-1 btn btn-success btn-sm btn-block"  data-toggle="modal" data-target="#tambahbidang">
                                                Tambah
                                            </button>
                                    </a>
                            </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="mb-0 table table-striped">
                                    <thead>
                                        <tr>
                                            <th style="width:90%">Nama Bidang</th>
                                            <th >Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($bidang as $row) { ?>
                                        <tr>
                                            <td><?php echo $row->nama; ?></td>
                                            <td>
                                            <a data-toggle = "modal" data-id="<?php echo $row->id ?>" jurusan="<?php echo $row->jurusan ?>" prodi="<?php echo $row->prodi ?>" class="passingID" >
                                                            <button type="button" class="btn-wide mb-1 btn btn-danger btn-sm btn-block"  data-toggle="modal" data-target="#deletebidang">
                                                                Hapus
                                                            </button>
                                            </a>
                                            
                                            </td>
                                        </tr>
                                    <?php } ?>    
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
                var jurusan = $(this).attr('jurusan');
                var prodi = $(this).attr('prodi');
                $("#ID").val( id );
                $("#Jur").val( jurusan );
                $("#Pro").val( prodi );

            });

    $(".passingIDTambah").click(function () {
                var jurusan = $(this).attr('jurusan');
                var prodi = $(this).attr('prodi');
                $("#Jurusan").val( jurusan );
                $("#Prodi").val( prodi );

            });
              
     
</script>