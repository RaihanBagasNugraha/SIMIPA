

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-note icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    
                                    <div>List Unit Kerja Tendik
                                        <div class="page-title-subheading">
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="page-title-actions">
                                    <a data-toggle = "modal" data-id="" class="passing" >
                                        <button type="button" class="btn mb-2 btn-success btn-md"  data-toggle="modal" data-target="#add_unit">
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
                                
                                <?php if ($this->session->flashdata('success')) : ?>
                                    <div class="alert alert-success" role="alert">
                                    <!-- <h4 class="alert-heading"></h4> -->
                                    <p class="mb-0"><?php echo $this->session->flashdata('success'); ?></p>
                                    </div>
                                <?php endif; ?>

                                <div class="table-responsive">
                                    <table class="mb-0 table table-striped" data-page-length='100' id="example">
                                        <thead>
                                        <tr>
                                            <th style="width: 5%;">No</th>
                                            <th style="width: 40%;">Unit Kerja</th>
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
                                            <td><?php echo $row->nama; ?></td>
                                            <?php if($row->parent == null || $row->parent == '' ){ $row->parent = '-1'; } ?>
                                            <td><?php echo empty($this->jurusan_model->get_jurusan_id($row->parent)) ? "-" : $this->jurusan_model->get_jurusan_id($row->parent)->nama; ?></td>
                                            <td>
                                                <a data-toggle = "modal" data-id="<?php echo $row->id_unit_kerja ?>" data-nama="<?php echo $row->nama ?>" data-parent="<?php echo $row->parent ?>" class="passing2" >
                                                    <button type="button" class="btn mb-2 btn-primary btn-md"  data-toggle="modal" data-target="#edit_unit">
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
        var nama = $(this).attr('data-nama');
        var parent = $(this).attr('data-parent');
        $("#id_unit").val(id);
        $("#id_unit_nama").val(nama);
        $("#id_unit_parent").val(parent);
    }); 

     
</script>