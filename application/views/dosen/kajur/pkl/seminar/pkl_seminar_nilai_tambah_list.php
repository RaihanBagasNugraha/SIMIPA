

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-note icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <div>Komponen Penilaian Seminar KP/PKL
                                        <div class="page-title-subheading">
                                        </div>
                                       
                                    </div>
                                   
                                </div>
                                <?php if(empty($cek)){ ?>
                                    <div class="page-title-actions">
                                            <a href="<?php echo site_url("dosen/struktural/pkl/komponen-nilai-pkl/form") ?>" class="btn-shadow btn btn-success">
                                                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                        <i class="fas fa-file fa-w-20"></i>
                                                    </span>
                                                    Tambah Komponen Penilaian KP/PKL
                                            </a>
                                    </div>
                                <?php } ?>
                                
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
                                            <th style="width: 10%;">Tanggal Pengisian</th>
                                            <th style="width: 10%;">Status</th>
                                            <th style="width: 10%;">Aksi</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if(empty($komponen))
                                        {
                                            echo "<tr><td colspan='6'>Data tidak tersedia</td></tr>";
                                        }
                                        else
                                        {
                                            foreach($komponen as $row) {
                                              
                                        ?>
                                        <tr>
                                            <td class="align-top">
                                                <?php echo $row->created_at; ?>
                                            </td>
                                            <td class="align-top">
                                                <?php echo $row->status == 0 ? "Nonaktif" : "Aktif"; ?>
                                            </td>
                                            <td class="align-top">
                                                <?php 

                                                ?>
                                                <a href="<?php echo site_url("dosen/struktural/pkl/komponen-nilai-pkl/form?aksi=ubah&id=".$row->id) ?>" class="btn-wide mb-2 btn btn-primary btn-sm">Lihat/Ubah
                                                </a>

                                                <?php if($row->status == 1){ ?>
                                                <a data-toggle = "modal" data-id="<?php echo $row->id; ?>" class="passingID" >
                                                    <button type="button" class="btn mb-2 btn-wide btn-danger btn-sm "  data-toggle="modal" data-target="#nonaktifkomponenPkl">
                                                            Nonaktifkan
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
                $("#IDk").val( id );
            });

    $(".passingID2").click(function () {
                var id = $(this).attr('data-id');
                $("#ID2").val( id );

    });  

    $(".passingID4").click(function () {
                var id = $(this).attr('data-id');
                $("#ID4").val( id );

    });  

    $(".passingID5").click(function () {
                var id = $(this).attr('data-id');
                var dataPb = $(this).attr('data-pb');
                var data = dataPb.split("///");
                $("#ID5").val( id );
                $("#Nama").val( data[0] );
                $("#nip").val( data[1] );
                $("#email").val( data[2] );
                $("#telp").val( data[3] );

    });  

    $(".passingIDPerbaikan").click(function () {
                var id = $(this).attr('data-id');
                var status = $(this).attr('ket_status');
                $("#IDPerbaikan").val( id );
                $("#Status").val( status );
    });  

    $(".passingIDInstansi").click(function () {
                var id = $(this).attr('data-id');
                $("#IDInstansi").val( id );
    });  
              
     
</script>