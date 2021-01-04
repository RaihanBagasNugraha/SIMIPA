

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-note icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    
                                    <div>List Unit Prodi
                                        <div class="page-title-subheading">
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="page-title-actions">
                                    <a data-toggle = "modal" data-id="" class="passing" >
                                        <button type="button" class="btn mb-2 btn-success btn-md"  data-toggle="modal" data-target="#add_prodi">
                                            <span class="btn-icon-wrapper"><i class="fa fa-plus" aria-hidden="true"></i>
                                            </span>  Tambah 
                                        </button>
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

                            echo '<div class="alert alert-success fade show" role="alert">Data Berhasil Disimpan</div>';
                        }
                        ?>
                            
                         <div class="main-card mb-3 card">
                                <div class="card-body">
                                <div class="table-responsive">
                                    <table class="mb-0 table table-striped" data-page-length='100' id="example">
                                        <thead>
                                        <tr>
                                            <th style="width: 5%;">No</th>
                                            <th style="width: 40%;">Prodi</th>
                                            <th style="width: 40%;">Jurusan</th>
                                            <th style="width: 15%;">Aksi</th>
                                        </tr>
                                      
                                        </thead>
                                        <tbody>
                                        <?php
                                        if(empty($list))
                                        {
                                            echo "<tr><td colspan='8'>Data tidak tersedia</td></tr>";
                                        }
                                        else
                                        {
                                            $no = 0;
                                            foreach($list as $row) {
                                        ?>
                                        <tr>
                                            <td><?php echo ++$no; ?></td>
                                            <td><?php echo $this->jurusan_model->get_jurusan_id($row->jurusan)->nama; ?></td>
                                            <td><?php echo $row->nama; ?></td>
                                            <td>
                                                <a data-toggle = "modal" data-id="<?php echo $row->id_prodi ?>" data-nama="<?php echo $row->nama ?>" data-jur="<?php echo $row->jurusan ?>" class="passing2" >
                                                    <button type="button" class="btn mb-2 btn-primary btn-md"  data-toggle="modal" data-target="#edit_prodi">
                                                        <span class="btn-icon-wrapper"><i class="fa fa-edit" aria-hidden="true"></i>
                                                        </span>Ubah
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
<script type="text/javascript">
$(document).ready(function() {
    $('#example').DataTable();
} );

</script>

<script>
    $(".passingID").click(function () {
        var id = $(this).attr('data-id');
        $("#id_mhs").val(id);
    }); 

    $(".passing2").click(function () {
        var id = $(this).attr('data-id');
        var jur = $(this).attr('data-jur');
        var nama = $(this).attr('data-nama');
        $("#id_prodi").val(id);
        $("#id_prodi_jur").val(jur);
        $("#id_prodi_nama").val(nama);
    }); 

     
</script>