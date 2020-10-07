

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-file icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <div>Kelola Berkas Lampiran Tema Penelitian
                                        <div class="page-title-subheading">Jangan lupa untuk mengunggah berkas pendukung.
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div> <!-- app-page-title -->
                        <?php
                        // debug
                        //echo "<pre>";
                        //print_r($data_ta);
                        //echo "</pre>";
                        if(!empty($_GET['status']) && $_GET['status'] == 'sukses') {

                            echo '<div class="alert alert-success fade show" role="alert">Berkas lampiran telah diunggah.</div>';
                        }
                        elseif(!empty($_GET['status']) && $_GET['status'] == 'gagal') {

                            echo '<div class="alert alert-danger fade show" role="alert">Ukuran atau Format Berkas Tidak Sesuai</div>';
                        }
                        ?>
                        <div class="row">
                        <div class="col-md-8">
                         <div class="main-card mb-3 card">
                                <div class="card-body">
                                <div class="table-responsive">
                                                    <table class="mb-0 table table-striped">
                                                        <thead>
                                                        <tr>
                                                            <th style="width: 7%;">#</th>
                                                            <th style="width: 35%;">Nama Berkas</th>
                                                            <th style="width: 33%;">Jenis Berkas</th>
                                                            <th style="width: 25%;">Aksi</th>
            
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                        if(empty($lampiran))
                                                        {
                                                            echo "<tr><td colspan='4'>Data tidak tersedia</td></tr>";
                                                        }
                                                        else
                                                        {
                                                            $no = 0;
                                                            foreach($lampiran as $row) {
                                                        ?>
                                                        <tr>
                                                            <td><?php echo ++$no ?></td>
                                                            <td><?php echo $row->nama_berkas ?></td>
                                                            <td><?php echo $row->nama ?></td>
                                                            <td>
                                                            <a style="width: 60px;" href="<?php echo base_url($row->file) ?>" class="mr-1 mb-1 btn btn-info btn-sm" download>Unduh
                                                </a>
                                                            <a data-toggle = "modal" data-id="<?php echo $row->id."#$#$".$row->nama_berkas."#$#$".$row->id_pengajuan."#$#$".$row->file ?>" class="passingID">
                                                            <button style="width: 60px;" type="button" class="btn mb-1 btn-danger btn-sm aksi"  data-toggle="modal" data-target="#delBerkas">
                                                                Hapus
                                                            </button>
                                                            </a>
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
                            </div> <!-- col-md -->

                            
                            <div class="col-md-4">
                                <div class="main-card mb-3 card">
                                    <div class="card-header">Form Berkas Lampiran</div>
                                    <div class="card-body">
  
                                        <form method="post" action="<?php echo site_url('mahasiswa/tambah_berkas_ta') ?>" enctype="multipart/form-data" >
                                        <div class="position-relative row form-group">
                                            <div class="col-sm-12">
                                                <input name="nama_berkas" class="form-control" type="text" placeholder="Nama Berkas">
                                                <input type="hidden" name="id_pengajuan" value="<?php echo $this->input->get('id') ?>">
                                            </div>
                                        </div>
                                            <div class="position-relative row form-group">
                                                <div class="col-sm-12">
                                                    <select name="jenis_berkas" class=" form-control">
                                                    <option>-- Pilih Jenis Berkas --</option>
                                                    <?php
                                                    $list = $this->parameter_model->select_jenis_berkas();
                                                    foreach ($list as $row) {
                                                        $select = "";
                                                        //if($biodata->jalur_masuk == $row->id_jalur_masuk) $select = "selected";
                                                        
                                                        echo "<option ".$select." value='".$row->id_jenis."'>".$row->nama."</option>";
                                                        }
                                                    ?>

                                                    </select>
                                                    
                                                
                                                </div>
                                            </div>
                                                
                                                <div class="position-relative row form-group">
                                                <div class="col-sm-12"><input oninvalid="this.setCustomValidity('Anda belum memilih berkas!')" oninput="this.setCustomValidity('')" accept=".pdf" name="file" id="file" type="file" class="form-control-file">
                                                </div>
                                                <br>
                                                <div class="col-sm-12"><span style="color:red">*Max 1 MB/Format PDF</span></div>
                                                <br>
                                                <div class="col-sm-12"><span style="color:red">*Sesuaikan Nama Berkas Untuk Memudahkan Verifikasi</span></div>
                                                </div>

                                                               
                                                
                                                <div class="position-relative row form-group">
                                                    <div class="col-sm-12">
                                                    <button type="submit" class="btn-shadow btn btn-success">
                                                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                        <i class="fa fa-plus fa-w-20"></i>
                                                    </span>
                                                    Tambah Berkas
                                                </button>
                                                    </div>
                                                </div>
                                            </form>

                                            

                                               
                                    
                                    </div>
                                </div>
                            </div>

                            
                        </div> <!-- row -->
                        


<script src="<?php echo site_url("assets/scripts/jquery_3.4.1_jquery.min.js") ?>"></script>
<script src="<?php echo site_url("assets/scripts/select2.full.js") ?>"></script>

<script>
 $("select").select2({
        theme: "bootstrap"
    });
                            $(document).on("click", ".passingID", function () {
                             var dataId = $(this).attr('data-id');
                             var data = dataId.split("#$#$");
                             $(".modal-body #berkasID").val( data[0] );
                             $(".modal-body #berkasNama").text(data[1]);
                             $(".modal-body #pengajuanID").val(data[2]);
                             $(".modal-body #berkasFile").val(data[3]);
                             
                             //console.log("Tes");
                            });
                        </script>
