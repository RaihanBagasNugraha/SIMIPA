

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-file icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <div>Pengajuan Tema Penelitian
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

                            echo '<div class="alert alert-success fade show" role="alert">Biodata Anda sudah diperbarui, jangan lupa untuk memperbarui <a href="javascript:void(0);" class="alert-link">Akun</a> sebelum menggunakan layanan.</div>';
                        }
                        $select = "selected";
                        ?>
                        <div class="row">
                        <div class="col-md-12">
                         <div class="main-card mb-3 card">
                                <div class="card-header">Form Pengajuan Seminar KP/PKL</div>
                                <div class="card-body">
                                <?php if(empty($status_pkl) || ($this->input->get('aksi') == 'ubah' && !empty($this->input->get('id')))) { ?>  
                                    <?php 
                                        $year = date("Y");
                                        $dates = date("Y-m-d");

                                        //check periode
                                        $periode_cek = $this->pkl_model->periode_seminar_kp($year,$jurusan);
                                        if(!empty($periode_cek)){
                                    ?>
                                    <form method="post" action="<?php echo site_url('mahasiswa/pkl/seminar/form/add') ?>" >
                                    <input value="<?php echo $data_pkl['seminar_id'] ?>" type="hidden" required name="seminar_id">
                                    <input value="<?php echo $periode_cek->id_pkl ?>" type="hidden" required name="id_periode" id="id_periode">
                                    <input value="<?php echo $pkl->pkl_id ?>" type="hidden" required name="pkl_id">
                                    <input value="<?php echo $this->session->userdata('username') ?>" type="hidden" required name="npm" id="id_pkl">
                                        <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-3 col-form-label">Tahun</label>
                                            <div class="col-sm-9">
                                                <input type="text" class = "form-control" name = "thn_show" required value="<?php echo $periode_cek->tahun ?>" disabled />
                                                <input type="hidden" class = "form-control" name = "tahun" required value="<?php echo $periode_cek->tahun ?>" />
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-3 col-form-label">Periode</label>
                                            <div class="col-sm-9">
                                                <input type="text" class = "form-control" name = "prd_show" required value="<?php echo $periode_cek->periode ?>" disabled />
                                                <input type="hidden" class = "form-control" name = "periode" required value="<?php echo $periode_cek->periode ?>" />
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">Judul</label>
                                            <div class="col-sm-9">
                                                <textarea required name="judul" class="form-control" placeholder="Judul Seminar KP/PKL"><?php echo $data_pkl['judul']?></textarea>
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">Tanggal Pelaksanaan</label>
                                            <div class="col-sm-9">
                                                <input value="<?php echo $data_pkl['tgl_pelaksanaan']?>" type="date" required name="tanggal" class="form-control">
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label for="jenis" class="col-sm-3 col-form-label">Waktu</label>
                                            <div class="col-sm-9">
                                                <select required name="waktu" class=" form-control">
                                                <option value="">[Pilih Waktu]</option>
                                                    <?php
                                                        $waktu = $data_pkl['waktu_pelaksanaan'];
                                                        for($hh=7; $hh<=17; $hh++) {
                                                            if($hh == 12) continue;
                                                            $num_padded = sprintf("%02d", $hh);
                                                    ?>
                                                    <option <?php if($waktu == $num_padded.":00:00") echo "selected"; ?> value="<?php echo $num_padded.":00:00"; ?>"><?php echo $num_padded.":00:00"; ?></option>
                                                    
                                                    <?php
                                                            if($hh == 17) break;
                                                    ?>
                                                            <option <?php if($waktu == $num_padded.":15:00") echo "selected"; ?> value="<?php echo $num_padded.":15:00"; ?>"><?php echo $num_padded.":15:00"; ?></option>
                                                            <option <?php if($waktu == $num_padded.":30:00") echo "selected"; ?> value="<?php echo $num_padded.":30:00"; ?>"><?php echo $num_padded.":30:00"; ?></option>
                                                            <option <?php if($waktu == $num_padded.":45:00") echo "selected"; ?> value="<?php echo $num_padded.":45:00"; ?>"><?php echo $num_padded.":45:00"; ?></option>
                                                        <?php
                                                        }
                                                    ?>
                                                </select>  
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">Tempat</label>
                                            <div class="col-sm-9">
                                                <input value="<?php echo $data_pkl['tempat']?>" placeholder="Tempat Pelaksanaan" type="text" required name="tempat" class="form-control">
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">IPK</label>
                                            <div class="col-sm-3">
                                                <input value="<?php echo $data_pkl['ipk']?>" required name="ipk" class="form-control input-mask-trigger" data-inputmask="'mask': '9.99'" im-insert="true">
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">Jumlah SKS</label>
                                            <div class="col-sm-3">
                                                <!-- <input value="<?php echo $data_pkl['sks']?>" required name="sks" class="form-control input-mask-trigger" data-inputmask="'mask': '999'" im-insert="true"> -->
                                                <input value="<?php echo $data_pkl['sks']?>" required name="sks" class="form-control input-mask-trigger" data-inputmask="'mask': '999'" im-insert="true">
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-3 col-form-label">Dosen Pembimbing</label>
                                            <div class="col-sm-9">
                                            <?php 
                                                $dosen_pmb = $this->user_model->get_dosen_name($pkl->pembimbing);
                                            ?>
                                                <input type="text" class = "form-control" name = "pembimbing" value="<?php echo $dosen_pmb->gelar_depan." ".$dosen_pmb->name.", ".$dosen_pmb->gelar_belakang ?>" disabled />
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-3 col-form-label">Lokasi KP/PKL</label>
                                            <div class="col-sm-9">
                                            <?php 
                                                $lokasi= $this->pkl_model->get_lokasi_pkl_by_id($pkl->id_lokasi);
                                            ?>
                                                <input type="text" class = "form-control" name = "lokasi" value="<?php echo $lokasi->lokasi ?>" disabled />
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group"><label for="ttd" class="col-sm-3 col-form-label">Tanda Tangan Digital</label>
                                            <div class="col-sm-4">
                                            <canvas style="border: 1px solid #aaa; height: 200px; background-color: #efefef;" id="signature-pad" class="signature-pad col-sm-12" height="200px"></canvas>
                                            
                                            <small class="form-text text-muted"> </small>
                                            </div>
                                            <div class="col-sm-5">
                                            <div role="group" class="btn-group btn-group btn-group-toggle"  style="margin-bottom: 10px;">
                                                    <label class="btn btn-dark">
                                                        <input type="radio" name="pen_color" class="pen_color" value="0" checked>
                                                        Hitam
                                                    </label>
                                                    <label class="btn btn-primary">
                                                        <input type="radio" name="pen_color" class="pen_color" value="1">
                                                        Biru
                                                    </label>
                                                    
                                                </div>
                                                
                                            </a>
                                            <a id="clear" class="mb-2 btn btn-light" onclick="document.getElementById('output').value = ''">Hapus
                                            </a>
                                            <!--<a id="preview2" class="mb-2 btn btn-light">Oke-->
                                            <!--</a>-->
                                            <input type="hidden" style="background-color: #efefef;" type="text" class="form-control readonly" required placeholder=" " name="ttd" id="output" value="">
                                            <input type="hidden" name="aksi" value="<?php if(!empty($this->input->get("aksi"))) echo $this->input->get("aksi") ?>">
                                            </div>
                                    
                                        </div>
                                
                                  
                                        
                                        <div class="position-relative row form-group">
                                            <div class="col-sm-9 offset-sm-3">
                                            <button id="preview" value="<?php if($this->input->get('aksi') == "ubah") echo "ubah"; ?>" type="submit" class="btn-shadow btn btn-info">
                                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fa fa-save fa-w-20"></i>
                                            </span>
                                            <?php if($this->input->get('aksi') == "ubah") echo "Ubah"; else echo "Simpan" ?> Data
                                        </button>
                                            </div>
                                        </div>
                                    </form>
                                    <?php }else{ echo '<div class="alert alert-danger fade show" role="alert">Waktu Pendaftaran Belum Dibuka Atau Sudah Berakhir</div>'; } ?>    
                                                <?php } else {
                                                    echo '<div class="alert alert-danger fade show" role="alert">Masih terdapat <b>Pengajuan PKL</b> dengan status Aktif.</div>
                                                    ';
                                                } ?>
                                            
                                </div>
                            </div>
                            </div> <!-- col-md -->

                            <!--
                            <div class="col-md-5">
                                <div class="main-card mb-3 card">
                                    <div class="card-header">Berkas Lampiran</div>
                                    <div class="card-body">
                                    <div class="table-responsive">
                                                    <table class="mb-0 table table-striped">
                                                        <thead>
                                                        <tr>
                                                            <th style="width: 10%;">#</th>
                                                            <th style="width: 60%;">Berkas</th>
                                                            <th style="width: 30%;">Aksi</th>
            
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td>1</td>
                                                            <td>KRS</td>
                                                            <td></td>
                                                            
                                                        </tr>
                                                        
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="divider"></div>
                                        <form method="post" action="<?php echo site_url('mahasiswa/ubah-data-akun') ?>" enctype="multipart/form-data" >
                                            <div class="position-relative row form-group">
                                                <div class="col-sm-12">
                                                    <select name="jalur_masuk" class=" form-control">
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
                                                <div class="col-sm-12"><input oninvalid="this.setCustomValidity('Anda belum memilih berkas!')" oninput="this.setCustomValidity('')" accept=".jpg, .jpeg, .png" name="file" id="file" type="file" class="form-control-file">
                                                        </div>
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

                            -->
                        </div> <!-- row -->


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

<script src="<?php echo site_url("assets/scripts/signature_pad.js") ?>"></script>
<script>
var canvas = document.getElementById('signature-pad');

var signaturePad = new SignaturePad(canvas);

<?php if($this->input->get('aksi') == 'ubah' && !empty($this->input->get('id'))) { 
    
    $ttd_img = "";
    
    ?>


<?php } ?>

$('#preview').click(function(){
  var data = signaturePad.toDataURL('image/png');
  $('#output').val(data);

 });

 $(".pen_color").change(function(){
    var radioValue = $("input[name='pen_color']:checked").val();
    if(radioValue == 1){
        signaturePad.penColor = 'rgb(0, 0, 255)'
    } else {
        signaturePad.penColor = 'rgb(0, 0, 0)'
    }
});

 

document.getElementById('clear').addEventListener('click', function () {
  signaturePad.clear();
});

document.getElementById('undo').addEventListener('click', function () {
	var data = signaturePad.toData();
  if (data) {
    data.pop(); // remove the last dot or line
    signaturePad.fromData(data);
  }
});

</script>

                        