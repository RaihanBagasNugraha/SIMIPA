
<div class="app-page-title">
                        <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-file icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <div>Approval Tema Penelitian
                                        <div class="page-title-subheading">Setujui Atau Tolak Pengajuan Tema Penelitian
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
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
                                <div class="card-header">Approval Tema Penelitian</div>
                                <div class="card-body">
                                <form method="post" action="<?php echo site_url('tendik/tugas-akhir/tema/approve') ?>" >
                                    <input value="<?php echo $ta->id_pengajuan ?>" type = "hidden" required name="id_pengajuan" id="id_pengajuan">
                                    <input value="<?php echo $aksi ?>" type = "hidden" required name="aksi" id="aksi">
                                    <input value="<?php echo $ta->updated_at ?>" type = "hidden" required name="update">
                                    
                                    <!-- No Penetapan -->
                                    <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">No Penetapan</label>
                                            <div class="col-sm-1">
                                                <?php 
                                                    $year = date("Y");
                                                    $jurusanid = $this->user_model->get_jurusan($ta->npm);
                                                    if($jurusanid == '0'){
                                                        $no = "/UN26.17.07/";
                                                    }
                                                    elseif($jurusanid == '1'){
                                                        $no = "/UN26.17.03/";
                                                    }
                                                    elseif($jurusanid == '2'){
                                                        $no = "/UN26.17.02/";
                                                    }
                                                    elseif($jurusanid == '3'){
                                                        $no = "/UN26.17.05/";
                                                    }
                                                    elseif($jurusanid == '4'){
                                                        $no = "/UN26.17.04/";
                                                    }
                                                    elseif($jurusanid == '5'){
                                                        $no = "/UN26.17.06/";
                                                    }
                                                    $nomor = $no.$year;
                                                    
                                                ?>
                                                <input type="text" value="<?php echo $ta->no_penetapan ?>" required name="no_penetapan" class="form-control" >
                                                <input type="hidden" value="<?php echo $nomor ?>" required name="nomor" id="nomor">
                                            </div>
                                            <?php echo "<h4>$nomor</h4>" ?>
                                    </div>

                                    <!-- NPM -->
                                    <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">Npm</label>
                                            <div class="col-sm-3">
                                                <input value="<?php echo $ta->npm ?>" required name="npm" class="form-control input-mask-trigger" readonly >
                                            </div>
                                    </div>

                                    <!-- NAMA -->
                                    <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">Nama</label>
                                            <div class="col-sm-9">
                                                <input value="<?php echo $this->user_model->get_mahasiswa_name($ta->npm) ?>" required name="nama" class="form-control input-mask-trigger" readonly >
                                            </div>
                                    </div>

                                    <!-- Judul -->
                                    <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-3 col-form-label" >Judul Utama</label>
                                            <div class="col-sm-9">
                                                <textarea required name="judul1" class="form-control" readonly placeholder="Judul Utama" id="inputother"><?php echo $ta->judul1 ?></textarea>
                                            </div>
                                    </div>
                                    <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-3 col-form-label">Judul Alternatif </label>
                                            <div class="col-sm-9">
                                                <textarea required name="judul2" class="form-control" readonly placeholder="Judul Alternatif" id="inputother2"><?php echo $ta->judul2 == NULL ? "-":$ta->judul2; ?></textarea>
                                            </div>
                                    </div>

                                    <!-- IPK -->
                                    <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">IPK</label>
                                            <div class="col-sm-3">
                                                <input value="<?php echo $ta->ipk ?>" required name="ipk" class="form-control input-mask-trigger" readonly data-inputmask="'mask': '9.99'" im-insert="true">
                                            </div>
                                    </div>

                                    <!-- SKS -->
                                    <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">Jumlah SKS</label>
                                            <div class="col-sm-3">
                                                <input value="<?php echo $ta->ipk ?>" required name="sks" class="form-control input-mask-trigger" readonly data-inputmask="'mask': '999'" im-insert="true">
                                            </div>
                                    </div>

                                     <!-- TOEFL -->
                                    <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">Nilai TOEFL</label>
                                            <div class="col-sm-3">
                                                <input value="<?php echo $ta->toefl ?>" name="toefl" class="form-control input-mask-trigger" readonly data-inputmask="'mask': '999'" im-insert="true">
                                            </div>
                                    </div>

                                    <!-- Dosen Pembimbing -->
                                    <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">Pembimbing Utama</label>
                                            <div class="col-sm-9">
                                            <?php $dosen = $this->user_model->get_dosen_name($ta->pembimbing1);?>
                                                <input value="<?php echo $ta->pembimbing1; ?>" type ="hidden" name="pembimbing1" class="form-control" readonly>
                                                <input value="<?php echo $dosen->name; ?>" name="pembimbing_name" class="form-control" readonly>
                                            </div>
                                    </div>

                                    
                                    <!-- Status -->
                                    <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">Status</label>
                                            <div class="col-sm-9">
                                                <input value="Staff Administrasi" class="form-control" readonly>
                                            </div>
                                    </div>


                                    <!-- TTD -->
                                    <div class="position-relative row form-group"><label for="ttd" class="col-sm-3 col-form-label">Tanda Tangan Digital</label>
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
                                            <?php if($this->input->get('aksi') == "ubah") echo "Ubah"; else echo "Setujui Pengajuan" ?>
                                        </button>
                                            </div>
                                    </div>
                                
                                </form>



                    

</div>
<script src="<?php echo site_url("assets/scripts/jquery_3.4.1_jquery.min.js") ?>"></script>
<script src="<?php echo site_url("assets/scripts/select2.full.js") ?>"></script>
<script type="text/javascript">

</script>
<script src="<?php echo site_url("assets/scripts/signature_pad.js") ?>"></script>
<script>
var canvas = document.getElementById('signature-pad');

var signaturePad = new SignaturePad(canvas);

<?php if($this->input->get('aksi') == 'ubah' && !empty($this->input->get('id'))) { 
    
    $ttd_img = json_encode($data_ta['ttd']);
    
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