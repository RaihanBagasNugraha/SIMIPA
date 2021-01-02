

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-note icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    
                                    <div>Tambah User
                                        <div class="page-title-subheading">
                                           Tambah / Edit Informasi User
                                        </div>
                                    </div>
                                </div>
                                <div class="page-title-actions">
                                <?php 
                                    $jns = $this->uri->segment(3);
                                    
                                ?>
                                    <a data-toggle = "modal" data-id="" class="passing" >
                                        <button type="button" class="btn mb-2 btn-success btn-md"  data-toggle="modal" data-target="#add_user">
                                            <span class="btn-icon-wrapper"><i class="fa fa-plus" aria-hidden="true"></i>
                                            </span>  Tambah User 
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
                                <?php if ($this->session->flashdata('error')) : ?>
                                    <div class="alert alert-danger" role="alert">
                                    <!-- <h4 class="alert-heading"></h4> -->
                                    <p class="mb-0"><?php echo $this->session->flashdata('error'); ?></p>
                                    </div>
                                <?php endif; ?>
                                <div class="table-responsive">
                                    <table class="mb-0 table table-striped" id="example">
                                        <thead>
                                        <tr>
                                            <th style="width: 5%;">No</th>
                                            <th style="width: 20%;">Nama/NIP</th>
                                            <th style="width: 10%;">Email</th>
                                            <th style="width: 10%;">Jurusan/Unit Kerja</th>
                                            <th style="width: 20%;">Keterangan</th>
                                            <th style="width: 10%;">Aksi</th>
                                        </tr>
                                      
                                        </thead>
                                        <tbody>
                                        <?php
                                        
                                        if(empty($user))
                                        {
                                            echo "<tr><td colspan='8'>Data tidak tersedia</td></tr>";
                                        }
                                        else
                                        {
                                            $no = 0;
                                            foreach($user as $row) {
                                                
                                        ?>
                                        <tr>
                                            <td class="align-top"><?php echo ++$no; ?></td>
                                            <td class="align-top"><?php echo $row->gelar_depan." ".$row->name.", ".$row->gelar_belakang."<br><b>".$row->nip_nik."</b>" ?></td>                 
                                            <td class="align-top"><?php echo $row->email; ?></td>
                                            <td class="align-top">
                                            <?php 
                                                if($jns == 'dosen'){
                                                    if($row->jurusan == '' || $row->jurusan == null){
                                                        $row->jurusan = '-1';
                                                    }
                                                    echo empty($this->jurusan_model->get_jurusan_id($row->jurusan)) ? "" : $this->jurusan_model->get_jurusan_id($row->jurusan)->nama ; 
                                                }elseif($jns == 'tendik'){
                                                    if($row->unit_kerja == '' || $row->unit_kerja == null){
                                                        $row->unit_kerja = '-1';
                                                    }
                                                    echo empty($this->jurusan_model->get_unit_kerja_id($row->unit_kerja)) ? "" : $this->jurusan_model->get_unit_kerja_id($row->unit_kerja)->nama ; 
                                                }
                                                
                                            ?>
                                            </td>
                                            <td class="align-top">
                                            <?php 
                                                if($jns == 'dosen'){
                                                    if($row->pangkat_gol == null || $row->pangkat_gol == ''){
                                                        $row->pangkat_gol = 0;
                                                    }
                                                    if($row->fungsional == null || $row->fungsional == ''){
                                                        $row->fungsional = 0;
                                                    }
                                                    echo "<b>NIDN : </b>".$row->nidn;
                                                    echo "<br>";
                                                    echo empty($this->user_model->get_pangkat_gol_by_id($row->pangkat_gol)) ? "<b>Pangkat Gol. </b>: -" : "<b>Pangkat Gol. : </b>".$this->user_model->get_pangkat_gol_by_id($row->pangkat_gol)->pangkat." ".$this->user_model->get_pangkat_gol_by_id($row->pangkat_gol)->golongan." ".$this->user_model->get_pangkat_gol_by_id($row->pangkat_gol)->ruang;
                                                    echo "<br>";
                                                    echo  empty($this->user_model->get_jabfung_gol_by_id($row->fungsional)) ? "<b>Jab. Fungsional </b>: -" : "<b>Jab. Fungsional : </b>".$this->user_model->get_jabfung_gol_by_id($row->fungsional)->nama;
                                                    echo "<br>";
                                                    echo  "<b>ID SINTA : </b>".$row->id_sinta;
                                                }elseif($jns == 'tendik'){

                                                    if($row->pangkat_gol == null || $row->pangkat_gol == ''){
                                                        $row->pangkat_gol = 0;
                                                    }

                                                    echo empty($this->user_model->get_pangkat_gol_by_id($row->pangkat_gol)) ? "<b>Pangkat Gol. </b>: -" : "<b>Pangkat Gol. : </b>".$this->user_model->get_pangkat_gol_by_id($row->pangkat_gol)->pangkat." ".$this->user_model->get_pangkat_gol_by_id($row->pangkat_gol)->golongan." ".$this->user_model->get_pangkat_gol_by_id($row->pangkat_gol)->ruang;

                                                }
                                            ?></td>
                                            <td class="align-top">
                                            <?php if($jns == 'dosen'){ ?>
                                                <a data-toggle = "modal" 
                                                    data-id="<?php echo $row->userId ?>"
                                                    data-nip="<?php echo $row->nip_nik ?>"
                                                    data-nama="<?php echo $row->name ?>" 
                                                    data-gdepan="<?php echo $row->gelar_depan ?>"
                                                    data-gbelakang="<?php echo $row->gelar_belakang ?>"
                                                    data-email="<?php echo $row->email ?>"
                                                    data-hp="<?php echo $row->mobile ?>"
                                                    data-jur="<?php echo $row->jurusan ?>"
                                                    data-nidn="<?php echo $row->nidn ?>"
                                                    data-sinta="<?php echo $row->id_sinta ?>"
                                                    data-pkt="<?php echo $row->pangkat_gol ?>"
                                                    data-fung="<?php echo $row->fungsional ?>"
                                                
                                                class="passing1" >
                                                    <button type="button" class="btn mb-2 btn-primary btn-md"  data-toggle="modal" data-target="#edit_user">
                                                        <span class="btn-icon-wrapper"><i class="fa fa-edit" aria-hidden="true"></i>
                                                        </span> 
                                                    </button>
                                                </a>
                                            <?php }elseif($jns == 'tendik'){ ?>
                                                <a data-toggle = "modal" 
                                                    data-id="<?php echo $row->userId ?>"
                                                    data-nip="<?php echo $row->nip_nik ?>"
                                                    data-nama="<?php echo $row->name ?>" 
                                                    data-gdepan="<?php echo $row->gelar_depan ?>"
                                                    data-gbelakang="<?php echo $row->gelar_belakang ?>"
                                                    data-email="<?php echo $row->email ?>"
                                                    data-hp="<?php echo $row->mobile ?>"
                                                    data-jur="<?php echo $row->unit_kerja ?>"
                                                    data-pkt="<?php echo $row->pangkat_gol ?>"
                                                
                                                class="passing1" >
                                                    <button type="button" class="btn mb-2 btn-primary btn-md"  data-toggle="modal" data-target="#edit_user">
                                                        <span class="btn-icon-wrapper"><i class="fa fa-edit" aria-hidden="true"></i>
                                                        </span> 
                                                    </button>
                                                </a>

                                            <?php } ?>   
                                                <a data-toggle = "modal" data-id="<?php echo $row->userId ?>" class="passing2" >
                                                    <button type="button" class="btn mb-2 btn-danger btn-md"  data-toggle="modal" data-target="#delete_user">
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

    $(".passing1").click(function () {
        var id = $(this).attr('data-id');
        var nama = $(this).attr('data-nama');
        var nip = $(this).attr('data-nip');
        var gdepan = $(this).attr('data-gdepan');
        var gbelakang = $(this).attr('data-gbelakang');
        var email = $(this).attr('data-email');
        var hp= $(this).attr('data-hp');
        var jur = $(this).attr('data-jur');
        var nidn = $(this).attr('data-nidn');
        var sinta = $(this).attr('data-sinta');
        var pkt = $(this).attr('data-pkt');
        var fung = $(this).attr('data-fung');
        $("#id_user_edit").val(id);
        $("#nama_edit").val(nama);
        $("#nip_edit").val(nip);
        $("#gdepan_edit").val(gdepan);
        $("#gbelakang_edit").val(gbelakang);
        $("#email_edit").val(email);
        $("#hp_edit").val(hp);
        $("#jur_edit").val(jur);
        $("#nidn_edit").val(nidn);
        $("#sinta_edit").val(sinta);
        $("#pkt_edit").val(pkt);
        $("#fung_edit").val(fung);
    }); 

    $(".passing2").click(function () {
        var id = $(this).attr('data-id');
        $("#id_user_del").val(id);
    }); 

     
</script>