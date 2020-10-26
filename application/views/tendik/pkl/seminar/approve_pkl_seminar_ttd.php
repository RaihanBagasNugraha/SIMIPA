
<div class="app-page-title">
                        <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-file icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <div>Approval Seminar KP/PKL
                                        <div class="page-title-subheading">Setujui Atau Tolak Pengajuan Seminar KP/PKL
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
                                <div class="card-header">Approval Seminar KP/PKL</div>
                                <div class="card-body">
                                <form method="post" action="<?php echo site_url('tendik/verifikasi-berkas/seminar-pkl/form/setujui') ?>" >
                                    <input value="<?php echo $seminar->seminar_id ?>" type = "hidden" required name="seminar_id" id="id_seminar">
                                    <input value="<?php echo $seminar->pkl_id ?>" type = "hidden" required name="pkl_id" id="">
                                    <input value="<?php echo $status ?>" type = "hidden" required name="status" id="aksi">


                                    <!-- NPM -->
                                    <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">Npm</label>
                                            <div class="col-sm-3">
                                                <input value="<?php echo $pkl->npm ?>" required name="npm" class="form-control input-mask-trigger" readonly >
                                            </div>
                                    </div>

                                    <!-- NAMA -->
                                    <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">Nama</label>
                                            <div class="col-sm-9">
                                                <input value="<?php echo $this->user_model->get_mahasiswa_name($pkl->npm) ?>" required name="nama" class="form-control input-mask-trigger" readonly >
                                            </div>
                                    </div>

                                    <!-- IPK -->
                                    <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">IPK</label>
                                            <div class="col-sm-3">
                                                <input value="<?php echo $pkl->ipk ?>" required name="ipk" class="form-control input-mask-trigger" readonly data-inputmask="'mask': '9.99'" im-insert="true">
                                            </div>
                                    </div>

                                    <!-- SKS -->
                                    <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">Jumlah SKS</label>
                                            <div class="col-sm-3">
                                                <input value="<?php echo $pkl->sks ?>" required name="sks" class="form-control input-mask-trigger" readonly data-inputmask="'mask': '999'" im-insert="true">
                                            </div>
                                    </div>

                                    <!-- Status -->
                                    <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">Status</label>
                                            <div class="col-sm-9">
                                                <?php 
                                                switch($status){
                                                    case "admin":
                                                    $sts = "Administrasi";
                                                    break;
                                                }
                                                echo "<input class=\"form-control\" value=\"$sts\" readonly>"
                                                ?>
                                            </div>
                                    </div>

                                     <!-- no surat -->
                                     <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">Nomor Surat</label>
                                            <div class="col-sm-1">
                                                <?php 
                                                    $year = date("Y");
                                                    $jurusanid = $this->user_model->get_jurusan_tendik($this->session->userdata('userId'));
                                                    $ta_jenis = "DT";
                                                    if($jurusanid == '0'){
                                                        $no = "/UN26.17.07/$ta_jenis/";
                                                    }
                                                    elseif($jurusanid == '1'){
                                                        $no = "/UN26.17.03/$ta_jenis/";
                                                    }
                                                    elseif($jurusanid == '2'){
                                                        $no = "/UN26.17.02/$ta_jenis/";
                                                    }
                                                    elseif($jurusanid == '3'){
                                                        $no = "/UN26.17.05/$ta_jenis/";
                                                    }
                                                    elseif($jurusanid == '4'){
                                                        $no = "/UN26.17.04/$ta_jenis/";
                                                    }
                                                    elseif($jurusanid == '5'){
                                                        $no = "/UN26.17.06/$ta_jenis/";
                                                    }
                                                    $nomor = $no.$year;
                                                    
                                                ?>
                                               <input type="text" name="no_penetapan" value="" class="form-control" /> 
                                               <input type="hidden" value="<?php echo $nomor ?>" required name="nomor" id="nomor">
                                            </div>
                                            <?php echo "<h4>$nomor</h4>" ?>
                                    </div>


                                    <!-- TTD -->
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
                                            <!--<a id="preview" class="mb-2 btn btn-light">Oke-->
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