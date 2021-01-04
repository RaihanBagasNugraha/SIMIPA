

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-note icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    
                                    <div>List Struktural Tugas Tambahan
                                        <div class="page-title-subheading">
                                        Tugas Tambahan : 
                                             <?php 
                                                $tgs = $this->input->get('tugas');
                                                echo $this->user_model->get_tugas_by_id($tgs)->nama;
                                             ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="page-title-actions">
                                    <a data-toggle = "modal" data-id="" class="passing" >
                                        <button type="button" class="btn mb-2 btn-success btn-md"  data-toggle="modal" data-target="#add_tugas_user">
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

                            echo '<div class="alert alert-success fade show" role="alert">Biodata Anda sudah diperbarui, jangan lupa untuk memperbarui <a href="javascript:void(0);" class="alert-link">Akun</a> sebelum menggunakan layanan.</div>';
                        }
                        if(!empty($_GET['status']) && $_GET['status'] == 'duplikat') {

                            echo '<div class="alert alert-danger fade show" role="alert">Terdapat Tugas Tambahan User Yang Sama Dengan Status Yang Aktif</div>';
                        }
                        
                        if(!empty($_GET['status']) && $_GET['status'] == 'duplikat_user') {
                            $id_duplikat = $_GET['id'];
                            $id_duplikat = $this->encrypt->decode($id_duplikat);
                            $data_duplikat = $this->user_model->get_dosen_data($id_duplikat);
                            if(!empty($data_duplikat)){
                                $dup_name = $data_duplikat->name;
                            }else{
                                $tendik = $this->user_model->get_tendik_name($id_duplikat);
                                $dup_name =  $tendik->gelar_depan." ".$tendik->name.", ".$tendik->gelar_belakang;
                            }
                            echo '<div class="alert alert-danger fade show" role="alert"> Terdapat user dengan tugas yang sama dan status yang masih aktif : <a href="javascript:void(0);" class="alert-link">'.$dup_name.'</a></div>';
                        }
                        ?>
                            
                         <div class="main-card mb-3 card">
                                <div class="card-body">
                                <div class="table-responsive">
                                    <table class="mb-0 table table-striped" data-page-length='100' id="example">
                                        <thead>
                                        <tr>
                                            <th style="width: 15%;">Periode</th>
                                            <th style="width: 25%;">Nama</th>
                                            <th style="width: 20%;">Keterangan</th>
                                            <th style="width: 15%;">Status</th>
                                            <th style="width: 15%;">Aksi</th>
                                        </tr>
                                      
                                        </thead>
                                        <tbody>
                                        <?php
                                        if(empty($tugas))
                                        {
                                            echo "<tr><td colspan='8'>Data tidak tersedia</td></tr>";
                                        }
                                        else
                                        {
                                            $no = 0;
                                            foreach($tugas as $tg){
                                             
                                        ?>  
                                        
                                           <?php  
                                                $user = $this->user_model->get_tugas_periode_user($tgs,$tg->periode);
                                                foreach($user as $row){
                                           ?>
                                           <tr>
                                                <td><?php echo $tg->periode; ?></td>
                                                <td><?php echo $this->user_model->select_by_ID($row->id_user)->row()->name; ?></td>
                                                <td>
                                                <?php 
                                                    if($row->jurusan_unit == 0 && $row->prodi == 0 ){
                                                        echo "-";
                                                    }
                                                    elseif($row->tugas == 15){

                                                    }else{
                                                        echo "<b>Jurusan : </b>".$this->jurusan_model->get_jurusan_id($row->jurusan_unit)->nama;
                                                        echo "<br>";
                                                        if($row->prodi != 0){
                                                            echo "<b>Prodi : </b>".$this->jurusan_model->get_prodi_id($row->prodi)->nama;
                                                        }
                                                    }
                                                
                                                ?>
                                                </td>
                                                <td>
                                                <?php 
                                                if($row->aktif == 1){
                                                    echo "<span class='btn-primary btn'>Aktif</span>";
                                                }else{
                                                    echo "<span class='btn-danger btn'>Nonaktif</span>";
                                                } ?></td>
                                                <td>
                                                <?php 
                                                if($row->aktif == 1){
                                                ?>
                                                    <a data-toggle = "modal" data-id="<?php echo $row->id ?>" class="passing1" >
                                                        <button type="button" class="btn mb-2 btn-warning btn-md"  data-toggle="modal" data-target="#nonaktif_tugas_user"> Nonaktifkan 
                                                        </button>
                                                    </a>
                                                <?php
                                                }else{ ?>
                                                    <a data-toggle = "modal" data-id="<?php echo $row->id ?>" class="passing" >
                                                        <button type="button" class="btn mb-2 btn-danger btn-md"  data-toggle="modal" data-target="#hapus_tugas_user">  Hapus
                                                        </button>
                                                    </a>
                                                <?php
                                                }
                                                
                                                ?>
                                                </td>
                                            </tr>    
                                           <?php 
                                                } 
                                            ?>
                                           
                                        
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

    $("select").select2({
        theme: "bootstrap"
    });
} );

</script>

<script>
    $(".passingID").click(function () {
        var id = $(this).attr('data-id');
        $("#id_mhs").val(id);
    }); 

    $(".passing1").click(function () {
        var id = $(this).attr('data-id');
        $("#id_tugas").val(id);
    }); 

    $(".passing").click(function () {
        var id = $(this).attr('data-id');
        $("#id_tugas2").val(id);
    }); 

     
</script>