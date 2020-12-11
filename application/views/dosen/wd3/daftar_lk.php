

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-note icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    
                                    <div>Daftar Lembaga Kemahasiswaan
                                        <div class="page-title-subheading">
                                            Tambah atau Edit LK
                                        </div>
                                    </div>
                                </div>
                                <div class="page-title-actions">
                                    <a href="<?php echo site_url("dosen/lk/tambah-lk") ?>" class="btn-shadow btn btn-success">
                                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-plus fa-w-20"></i>
                                            </span>
                                            Tambah LK
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
                                            <th style="width: 20%;">Nama LK</th>
                                            <th style="width: 15%;">Tingkat</th>
                                            <th style="width: 10%;">Aksi</th>
                                        </tr>
                                      
                                        </thead>
                                        <tbody>
                                        <?php
                                        if(empty($lk))
                                        {
                                            echo "<tr><td colspan='6'>Data tidak tersedia</td></tr>";
                                        }
                                        else
                                        {
                                            $no = 0;
                                            foreach($lk as $row) {
                                        ?>
                                        <tr>
                                            <td class="align-top"><?php echo ++$no; ?></td>
                                            <td class="align-top"><?php echo $row->nama_lk; ?></td>
                                            <td class="align-top">
                                            <?php 
                                                if($row->jurusan_lk == 0){
                                                    echo "Fakultas";
                                                }else{
                                                    switch($row->jurusan_lk){
                                                        case "1":
                                                        echo "Jurusan Kimia";
                                                        break;
                                                        case "2":
                                                        echo "Jurusan Biologi";
                                                        break;
                                                        case "3":
                                                        echo "Jurusan Matematika";
                                                        break;
                                                        case "4":
                                                        echo "Jurusan Fisika";
                                                        break;
                                                        case "5":
                                                        echo "Jurusan Ilmu Komputer";
                                                        break;    
                                                    }   
                                                }
                                            ?>
                                            </td>
                                            <td class="align-top">
                                                <a data-toggle = "modal" data-id="<?php echo $row->id_lk  ?>"  data-nama = "<?php echo $row->nama_lk ?>"  class="passingID" >
                                                    <button type="button" class="btn mb-2 btn-primary btn-sm "  data-toggle="modal" data-target="#EditLk">
                                                        <span class="btn-icon-wrapper pr-2 opacity-7"><i class="fas fa-edit fa-w-20"></i></span>
                                                    </button>
                                                </a>

                                                <a href="<?php echo site_url("dosen/lk/detail-lk?id=".$row->id_lk) ?>" class="">
                                                    <button type="button" class="btn mb-2 btn-success btn-sm ">
                                                        <span class="btn-icon-wrapper pr-2 opacity-7"><i class="fas fa-eye fa-w-20"></i></span>
                                                    </button>
                                                </a>

                                                <a data-toggle = "modal" data-id="<?php echo $row->id_lk  ?>" class="passingID2" >
                                                    <button type="button" class="btn mb-2 btn-danger btn-sm "  data-toggle="modal" data-target="#HapusLk">
                                                        <span class="btn-icon-wrapper pr-2 opacity-7"><i class="fas fa-trash fa-w-20"></i></span>
                                                    </button>
                                                </a>

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
        var nm = $(this).attr('data-nama');
        $("#ID").val(id);
        $("#nama").val(nm);
    });      

    $(".passingID2").click(function () {
        var id = $(this).attr('data-id');
        $("#ID2").val(id);
    });      
     
</script>