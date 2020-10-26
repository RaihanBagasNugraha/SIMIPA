<div class="app-page-title">
                        <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-file icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <div>Approval Seminar
                                        <div class="page-title-subheading">Setujui Atau Tolak Pengajuan Seminar
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
                                <form method="post" action="<?php echo site_url('approval/simpan-seminar') ?>" >
                                    <input value="<?php echo $smr->id ?>" type = "hidden" required name="id" id="id">
                                    <input value="<?php echo $_GET['token'] ?>" type = "hidden" required name="token" id="token">
                                    


                                    <!-- NPM -->
                                    <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label"><b>Npm</b></label>
                                            <div class="col-sm-3">
                                                <input value="<?php echo $smr->npm ?>" required name="npm" class="form-control input-mask-trigger" readonly >
                                            </div>
                                    </div>

                                     <!-- NAMA -->
                                     <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label"><b>Nama</b></label>
                                            <div class="col-sm-9">
                                                <input value="<?php echo $this->user_model->get_mahasiswa_name($smr->npm) ?>" required name="nama" class="form-control input-mask-trigger" readonly >
                                            </div>
                                    </div>

                                     <!-- Judul -->
                                     <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-3 col-form-label" ><b>Judul</b></label>
                                            <div class="col-sm-9">
                                                <?php if($smr->judul_approve == 1){?>
                                                    <textarea required name="judul" class="form-control" readonly placeholder="Judul Utama" id="inputother"><?php echo $smr->judul1 ?></textarea>
                                                <?php } elseif($smr->judul_approve == 2){?>
                                                    <textarea required name="judul" class="form-control" readonly placeholder="Judul Utama" id="inputother"><?php echo $smr->judul2 ?></textarea>
                                                <?php } ?>
                                            </div>
                                    </div>

                                    <!-- Status -->
                                    <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label"><b>Status</b></label>
                                            <div class="col-sm-9">
                                                <input value="<?php echo $smr->status ?>" required name="status" class="form-control" readonly >
                                            </div>
                                    </div>

                                     <!-- Lampiran -->
                                     <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label"><b>Lampiran</b></label>
                                            <div class="col-sm-3">
                                                <?php
                                                    switch($smr->status){
                                                        case "Pembimbing 2":
                                                            $sts = "pb2";
                                                        break;
                                                        case "Pembimbing 3":
                                                            $sts = "pb3";
                                                        break;
                                                        case "Penguji 1":
                                                            $sts = "ps1";
                                                        break;
                                                        case "Penguji 2":
                                                            $sts = "ps2";
                                                        break;
                                                        case "Penguji 3":
                                                            $sts = "ps3";
                                                        break;
                                                    }

                                                    $lampiran = $this->ta_model->select_lampiran_by_seminar($smr->id);
                                                    if(empty($lampiran)) {
                                                        echo "<i>(Belum ada, silakan lengkapi berkas lampiran)</i>";
                                                    } else {
                                                        echo "<ul style='margin-left: -20px;'>";
                                                            if($smr->jenis != 'Seminar Tugas Akhir'){
                                                                echo "<li><a href=".site_url("mahasiswa/tugas-akhir/seminar/form_pdf?jenis=undangan_dosen_alter&id=$smr->id&status=$sts").">Undangan Seminar</a></li>";

                                                            }
                                                            $draft = $this->ta_model->get_draft_seminar($smr->id); 
                                                            if(!empty($draft)){
                                                                echo "<li><a href='".base_url($draft->file)."' download>"."Draft Laporan"."</a></li>";
                                                            }   
        
                                                        echo "</ul>";
                                                    }
                                                ?>
                                            </div>
                                    </div>

                                    <br>
                                    <h5><b>Form Nilai</b></h5>                
                                    <br>
                                    <?php $komposisi = $this->ta_model->get_komponen_nilai_meta($smr->npm,$smr->jenis,$smr->seminar_jenis); ?>

                                    <?php if(!empty($komposisi)) {?> 
                                    <table class="mb-0 table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Aspek Yang Dinilai</th>
                                                <th>Persentase</th>
                                                <th>Nilai</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $n = 1;
                                            foreach($komposisi as $kom) {?>
                                            <tr>
                                                <td><?php echo $kom->attribut ?></td>
                                                <input value="<?php echo $kom->id?>" type = "hidden" min="0" max="100" name="<?php echo "attribut[$n]"?>" >
                                                <td><?php echo $kom->persentase ?> %</td>
                                                
                                                <td><input value="" type = "number" required min="0" max="100" style="width: 4em" name="<?php echo "nilai[$n]"?>" ></td>
                                            </tr>
                                            <?php $n++; } ?>
                                        </tbody>
                                    </table>
                                    

                                    <input value="<?php echo $n ?>" type = "hidden" name="jml" id="jml">
                                    <input value="<?php echo $komposisi[1]->id_komponen ?>" type = "hidden" name="id_komponen">  
                                   
                                    <br><br>            
                                    <!-- Saran -->
                                    <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label"><b>Saran</b></label>
                                            <div class="col-sm-9">
                                                <textarea name="saran" class="form-control" placeholder="Isi Jika Terdapat Saran / Masukan"></textarea>
                                            </div>
                                    </div>
                                    <?php } else { 
                                        echo "<h5><span style='color:white;background-color:red;'>&nbsp;Format Nilai Belum Diisi Oleh Ketua Jurusan. Harap Menunggu&nbsp;</span></h5>";    
                                    }
                                    ?>
                                    
                                    <!-- TTD -->
                                    <div class="position-relative row form-group"><label for="ttd" class="col-sm-3 col-form-label"><b>Tanda Tangan Digital</b></label>
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
                                            <!--<a id="preview" class="mb-2 btn btn-light">Oke-->
                                            <!--</a>-->
                                            <input type="hidden" style="background-color: #efefef;" type="text" class="form-control readonly" required placeholder=" " name="ttd" id="output" value="">
                                            <input type="hidden" name="aksi" value="<?php if(!empty($this->input->get("aksi"))) echo $this->input->get("aksi") ?>">
                                            </div>
                                    
                                        </div>

                                    <?php 
                                    $date_now = new DateTime();
                                    $date_smr = new DateTime("$smr->tgl_pelaksanaan");
                                    
                                    if($date_now >= $date_smr && !empty($komposisi)){
                                    ?>
                                    <div class="position-relative row form-group">
                                            <div class="col-sm-9 offset-sm-3">
                                            <button id="preview" value="<?php if($this->input->get('aksi') == "ubah") echo "ubah"; ?>" type="submit" class="btn-shadow btn btn-info">
                                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fa fa-save fa-w-20"></i>
                                            </span>
                                            <?php if($this->input->get('aksi') == "ubah") echo "Ubah"; else echo "Setujui Pengajuan" ?>
                                        </button>
                                            </div>
                                    </div>
                                    <?php } 
                                    else{
                                        echo "<h5><span style='color:white;background-color:red'>&nbsp;Waktu Penilaian Belum Dibuka&nbsp;</span></h5>";
                                    }
                                    
                                    ?>
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