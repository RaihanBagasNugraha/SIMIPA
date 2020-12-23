

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-note icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    
                                    <div>List Registrasi Mahasiswa
                                        <div class="page-title-subheading">
                                            Verifikasi Registrasi Mahasiswa
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

                            echo '<div class="alert alert-success fade show" role="alert">Data Berhasil Disimpan</div>';
                        }
                        ?>
                            
                         <div class="main-card mb-3 card">
                                <div class="card-body">
                                <div class="table-responsive">
                                    <table class="mb-0 table table-striped" id="example">
                                        <thead>
                                        <tr>
                                            <th style="width: 5%;">No</th>
                                            <th style="width: 10%;">NPM</th>
                                            <th style="width: 15%;">Nama</th>
                                            <th style="width: 10%;">Email</th>
                                            <th style="width: 15%;">Jurusan</th>
                                            <th style="width: 15%;">No HP/WA</th>
                                            <th style="width: 10%;">Status</th>
                                            <th style="width: 10%;">Aksi</th>
                                        </tr>
                                      
                                        </thead>
                                        <tbody>
                                        <?php
                                        if(empty($mahasiswa))
                                        {
                                            echo "<tr><td colspan='8'>Data tidak tersedia</td></tr>";
                                        }
                                        else
                                        {
                                            $no = 0;
                                            foreach($mahasiswa as $row) {
                                                $mhs_data = $this->user_model->get_mahasiswa_data($row->userId);
                                        ?>
                                        <tr>
                                            <td><?php echo ++$no; ?></td>
                                            <td><b><?php echo $mhs_data->npm ?></b></td> 
                                            <td><b><?php echo $mhs_data->name ?></b></td>
                                            <td><?php echo $row->email ?></td>   
                                            <td><b><?php echo $this->user_model->get_jurusan_id($mhs_data->jurusan)->nama; ?></b></td>    
                                            <td><?php echo $row->mobile ?></td>
                                            <td><?php if($row->roleId == 3){ echo "Mahasiswa"; }else{ echo "Alumni"; } ?></td> 
                                            <td>
                                                <a data-toggle = "modal" data-id="<?php echo $row->userId ?>" class="passingID" >
                                                    <button type="button" class="btn mb-2 btn-primary btn-sm"  data-toggle="modal" data-target="#verif_regis">
                                                        <span class="btn-icon-wrapper"><i class="fa fa-check" aria-hidden="true"></i>
                                                        </span>  
                                                    </button>
                                                </a>

                                                <a data-toggle = "modal" data-id="<?php echo $row->userId ?>" class="passingID2" >
                                                    <button type="button" class="btn mb-2 btn-danger btn-sm"  data-toggle="modal" data-target="#tolak_regis">
                                                        <span class="btn-icon-wrapper"><i class="fa fa-trash" aria-hidden="true"></i>
                                                        </span>  
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

    $(".passingID2").click(function () {
        var id = $(this).attr('data-id');
        $("#id_mhs2").val(id);
    }); 

     
</script>