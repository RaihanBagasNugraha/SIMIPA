

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
                        ?>
                        <div class="row">
                        <div class="col-md-12">
                         <div class="main-card mb-3 card">
                                <div class="card-header">Form Pengajuan Tema Penelitian</div>
                                <div class="card-body">
                                <?php if(empty($status_ta) || ($this->input->get('aksi') == 'ubah' && !empty($this->input->get('id')))) { ?>  
                                    <form method="post" action="<?php echo site_url('mahasiswa/simpan-pengajuan-seminar') ?>" >
                                    <input value="<?php echo  $data_seminar['id']; ?>" type="hidden" required name="id_seminar" id="id_seminar">
                                    <input value="<?php echo $ta->id_pengajuan ?>" type="hidden" required name="id_tugas_akhir" id="id_tugas_akhir">
                                        <!-- <?php 
                                        // echo "<pre>";
                                        // print_r($data_seminar);
                                        
                                        
                                        ?> -->

                                        <div class="position-relative row form-group">
                                            <label for="npm" class="col-sm-3 col-form-label"><b>Npm</b></label>
                                            <div class="col-sm-3">
                                                <input value="<?php echo $biodata->npm ?>" readonly required name="npm" class="form-control input-mask-trigger" >
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label for="nama" class="col-sm-3 col-form-label"><b>Nama</b></label>
                                            <div class="col-sm-9">
                                                <input value="<?php echo $this->user_model->get_mahasiswa_name($biodata->npm); ?>" readonly required name="nama" class="form-control input-mask-trigger" >
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-3 col-form-label"><b>Judul</b></label>
                                            <div class="col-sm-9">
                                                <?php if($ta->judul_approve == '1'){ ?>
                                                    <textarea required name="judul" class="form-control" placeholder="Ketik judul utama penelitian di sini..." readonly><?php echo $ta->judul1 ?></textarea>
                                                <?php } elseif($ta->judul_approve == '2'){?>
                                                    <textarea required name="judul" class="form-control" placeholder="Ketik judul utama penelitian di sini..." readonly><?php echo $ta->judul2 ?></textarea>
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label for="jenis" class="col-sm-3 col-form-label"><b>Jenis</b></label>
                                            <div class="col-sm-9">
                                            <?php 
                                            $jenis = $data_seminar['jenis']; 
                                            $select = "selected";
                                            
                                            ?>
                                                <select required name="jenis" class=" form-control">
                                                <option value="">[Jenis Seminar]</option>
                                                    <option value="Seminar Tugas Akhir" <?php if($jenis == 'Seminar Tugas Akhir' && $jenis != NULL){echo $select;} ?>>Seminar Tugas Akhir (D3)</option>
                                                    <option value="Seminar Usul" <?php if($jenis == 'Seminar Usul' && $jenis != NULL){echo $select;} ?>>Seminar Usul</option>
                                                    <option value="Seminar Hasil" <?php if($jenis == 'Seminar Hasil' && $jenis != NULL){echo $select;} ?>>Seminar Hasil</option>
                                                    <option value="Sidang Komprehensif" <?php if($jenis == 'Sidang Komprehensif' && $jenis != NULL){echo $select;} ?>>Sidang Komprehensif</option>
                                                </select>  
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label for="tanggal" class="col-sm-3 col-form-label"><b>Tanggal</b></label>
                                            <div class="col-sm-3">
                                                <input value="<?php echo $data_seminar['tgl_pelaksanaan']; ?>" type="date" required name="tanggal" class="form-control input-mask-trigger" >
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label for="jenis" class="col-sm-3 col-form-label"><b>Waktu</b></label>
                                            <div class="col-sm-3">
                                                <select required name="waktu" class=" form-control">
                                                <option value="">[Pilih Waktu]</option>
                                                    <?php
                                                        $waktu = "";
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
                                            <label for="tanggal" class="col-sm-3 col-form-label"><b>Tempat</b></label>
                                            <div class="col-sm-9">
                                                <input value="<?php echo $data_seminar['tempat']; ?>" required name="tempat" class="form-control input-mask-trigger" >
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label"><b>IPK</b></label>
                                            <div class="col-sm-3">
                                                <input value="<?php echo $data_seminar['ipk'] ?>" required name="ipk" class="form-control input-mask-trigger" data-inputmask="'mask': '9.99'" im-insert="true">
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label"><b>Jumlah SKS</b></label>
                                            <div class="col-sm-3">
                                                <input value="<?php echo $data_seminar['sks'] ?>" required name="sks" class="form-control input-mask-trigger" data-inputmask="'mask': '999'" im-insert="true">
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label"><b>Nilai TOEFL</b></label>
                                            <div class="col-sm-3">
                                                <input value="<?php echo $data_seminar['toefl'] ?>" name="toefl" class="form-control input-mask-trigger" data-inputmask="'mask': '999'" im-insert="true">
                                            </div>
                                        </div>

                                        <br>
                                        <h5><b>Komisi Pembimbing</b></h5>
                                        <input value="" readonly required name="Pembimbing_1" type="hidden" class="form-control input-mask-trigger" >
                                        <input value="" readonly required name="Pembimbing_2" type="hidden" class="form-control input-mask-trigger" >
                                        <input value="" readonly required name="Pembimbing_3" type="hidden" class="form-control input-mask-trigger" >
                                        <input value="" readonly required name="Penguji_1" type="hidden" class="form-control input-mask-trigger" >
                                        <input value="" readonly required name="Penguji_2" type="hidden" class="form-control input-mask-trigger" >
                                        <input value="" readonly required name="Penguji_3" type="hidden" class="form-control input-mask-trigger" >
                                        <?php 
                                        //komisi
                                        $komisi_pembimbing = $this->ta_model->get_pembimbing_ta($ta->id_pengajuan); 
                                            
                                                foreach($komisi_pembimbing as $kom) {
                                        ?>

                                        <div class="position-relative row form-group">
                                            <label for="nama" class="col-sm-3 col-form-label"><b><?php echo $kom->status; ?></b></label>
                                            <div class="col-sm-9">
                                                <input value="<?php echo $kom->nama; ?>" readonly required name="<?php echo "nama ".$kom->status; ?>" class="form-control input-mask-trigger" >
                                                <input value="<?php echo $kom->nip_nik; ?>" readonly required name="<?php echo $kom->status; ?>" type="hidden" class="form-control input-mask-trigger" >
                                            </div>
                                        </div>

                                        <?php } ?>

                                        <br>
                                        <h5><b>Komisi Penguji</b></h5>

                                        <?php $komisi_penguji = $this->ta_model->get_penguji_ta($ta->id_pengajuan); 
                                                foreach($komisi_penguji as $kom) {
                                        ?>

                                        <div class="position-relative row form-group">
                                            <label for="nama" class="col-sm-3 col-form-label"><b><?php echo $kom->status; ?></b></label>
                                            <div class="col-sm-9">
                                                <input value="<?php echo $kom->nama; ?>" readonly required name="<?php echo "nama ".$kom->status; ?>" class="form-control input-mask-trigger" >
                                                <input value="<?php echo $kom->nip_nik; ?>" readonly required name="<?php echo $kom->status; ?>" type="hidden" class="form-control input-mask-trigger" >
                                            </div>
                                        </div>

                                        <?php } ?>


                                        <div class="position-relative row form-group"><label for="ttd" class="col-sm-3 col-form-label"><b>Tanda Tangan Digital</b></label>
                                            <div class="col-sm-4">
                                            <canvas style="border: 1px solid #aaa; height: 200px; background-color: #efefef;" id="signature-pad" class="signature-pad col-sm-12" height="200px"></canvas>
                                            <small class="form-text text-muted">Silahkan tanda tangan pada canvas di atas, jangan lupa Klik <b>Oke</b> sebelum menyimpan data.</small>
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
                                            <a id="preview" class="mb-2 btn btn-light">Oke
                                            </a>
                                            <input style="background-color: #efefef;" type="text" class="form-control readonly" required placeholder="Klik Oke setelah tanda tangan di canvas." name="ttd" id="output" value="">
                                            <input type="hidden" name="aksi" value="<?php if(!empty($this->input->get("aksi"))) echo $this->input->get("aksi") ?>">
                                            </div>
                                    
                                        </div>

                                        <div class="position-relative row form-group">
                                            <div class="col-sm-9 offset-sm-3">
                                            <button value="<?php if($this->input->get('aksi') == "ubah") echo "ubah"; ?>" type="submit" class="btn-shadow btn btn-info">
                                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fa fa-save fa-w-20"></i>
                                            </span>
                                            <?php if($this->input->get('aksi') == "ubah") echo "Ubah"; else echo "Simpan" ?> Data
                                        </button>
                                            </div>
                                        </div>

                                    
                                    </form>
                                                <?php } else {
                                                    echo '<div class="alert alert-danger fade show" role="alert">Masih terdapat <b>Pengajuan Tema</b> dengan status Aktif.</div>
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
    
    $ttd = $this->ta_model->get_seminar_approval_id($data_seminar['id']);
    $ttd_img = json_encode($ttd);
    
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

                        