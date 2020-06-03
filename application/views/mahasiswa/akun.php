<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-user icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <div>Kelola Akun
                                        <div class="page-title-subheading">Silakan <span class="text-primary"><b>lengkapi akun </b> dan <a href="javascript:void(0);" class="alert-link">biodata</a></span> Anda sebelum menggunakan layanan.
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div> <!-- app-page-title -->
                        <?php
                        // debug
                        //echo "<pre>";
                        //print_r($akun);
                        //echo "</pre>";
                        if(!empty($_GET['status']) && $_GET['status'] == 'sukses') {

                            echo '<div class="alert alert-success fade show" role="alert">Akun Anda sudah diperbarui, jangan lupa untuk memperbarui <a href="javascript:void(0);" class="alert-link">Biodata</a> sebelum menggunakan layanan.</div>';
                        }
                        ?>
                        
                         <div class="main-card mb-3 card">
                                <div class="card-header">Form Kelola Akun</div>
                                <div class="card-body">
                                    <form method="post" action="<?php echo site_url('mahasiswa/ubah-data-akun') ?>" enctype="multipart/form-data" >
                                    <div class="position-relative row form-group"><label for="username" class="col-sm-2 col-form-label"><?php 
                                        if($this->session->userdata('state') == 3) 
                                            echo "NPM"; 
                                        else 
                                        echo "NIP/NIK"; 
                                        ?></label>
                                            <div class="col-sm-10"><input name="username" readonly id="username" type="text" value="<?php echo $this->session->userdata('username') ?>" class="form-control"></div>
                                        </div>
                                        <div class="position-relative row form-group"><label for="nama" class="col-sm-2 col-form-label">Nama</label>
                                            <div class="col-sm-10"><input name="nama" required id="nama" value="<?php echo $akun->name ?>" type="text" class="form-control"></div>
                                        </div>
                                        <div class="position-relative row form-group"><label for="hp" class="col-sm-2 col-form-label">No HP/WA</label>
                                            <div class="col-sm-10"><input name="hp" required id="hp" value="<?php echo $akun->mobile ?>" type="text" class="form-control"></div>
                                        </div>
                                        <div class="position-relative row form-group"><label for="exampleEmail" class="col-sm-2 col-form-label">Email</label>
                                            <div class="col-sm-10"><input name="email" required id="exampleEmail" value="<?php echo $akun->email ?>" type="email" class="form-control"></div>
                                        </div>
                                        <div class="position-relative row form-group">
                                            <label for="exampleEmail" class="col-sm-2 col-form-label">Foto</label>
                                            <div class="col-sm-2">
                                                <img src="<?php echo base_url($akun->foto) ?>" class="img-responsive center-block rounded" style="border: 1px solid #999; width: 120px; height: 160px; margin:0 auto;">
                                            </div>
                                            <div class="col-sm-2">
                                            <div class="custom-checkbox custom-control"><input type="checkbox" id="ganti_foto" class="custom-control-input"><label class="custom-control-label" for="ganti_foto">Ganti Pas Foto</label>
                                            </div>
                                            </div>
                                        </div>
                                        <div id="file_ganti_foto" class="position-relative row form-group"><label for="exampleFile" class="col-sm-2 col-form-label"></label>
                                            <div class="col-sm-10"><input oninvalid="this.setCustomValidity('Anda belum memilih berkas!')" oninput="this.setCustomValidity('')" accept=".jpg, .jpeg, .png" name="file" id="file" type="file" class="form-control-file">
                                                <small class="form-text text-muted">File gambar dengan ratio (w:h) 2:3 dan format JPG, JPEG, atau PNG.</small>
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group"><label for="ttd" class="col-sm-2 col-form-label">Tanda Tangan Digital</label>
                            
                                            <div class="col-sm-4">
                                            <img src='<?php if($akun->ttd == "")
                                                echo base_url('assets/images/no-signature.jpg');
                                            else
                                                echo $akun->ttd;
                                            ?>' class="col-sm-12" id='sign_prev'  style="border: 1px solid #aaa; height: 200px;" />
                                            
                                            </div>
                                            <div class="col-sm-4">
                                            <div class="custom-checkbox custom-control"><input type="checkbox" id="ganti_ttd" class="custom-control-input"><label class="custom-control-label" for="ganti_ttd">Ganti Tanda Tangan Digital</label>
                                            </div>
                                            </div>
                                        </div>

                                        <div id="canvas_ganti_ttd" class="position-relative row form-group"><label for="ttd" class="col-sm-2 col-form-label"></label>
                                            <div class="col-sm-4">
                                            <canvas style="border: 1px solid #aaa; height: 200px; background-color: #efefef;" id="signature-pad" class="signature-pad col-sm-12" height="200px"></canvas>
                                            <small class="form-text text-muted">Silakan tanda tangan pada canvas di atas.</small>
                                            </div>
                                            <div class="col-sm-6">
                                            <div role="group" class="btn-group btn-group btn-group-toggle"  style="margin-bottom: 10px;">
                                                    <label class="btn btn-dark">
                                                        <input type="radio" name="pen_color" class="pen_color" value="0" checked>
                                                        Pena Hitam
                                                    </label>
                                                    <label class="btn btn-primary">
                                                        <input type="radio" name="pen_color" class="pen_color" value="1">
                                                        Pena Biru
                                                    </label>
                                                    
                                                </div>
                                                <a id="undo" class="mb-2 btn btn-light">Batalkan
                                            </a>
                                            <a id="clear" class="mb-2 btn btn-light">Bersihkan
                                            </a>
                                            <a id="preview" class="mb-2 btn btn-light">Terapakan
                                            </a>
                                            <input type="hidden" name="output_ttd" id="output">
                                            </div>
                                    
                                        </div>
                                        
                                        <div class="position-relative row form-group"><label for="examplePassword" class="col-sm-2 col-form-label">Kata Sandi</label>
                                            <div class="col-sm-6"><input name="password" id="password" disabled type="password" class="form-control"></div>
                                            <div class="col-sm-2">
                                            <div class="custom-checkbox custom-control"><input type="checkbox" id="ganti_password" class="custom-control-input"><label class="custom-control-label" for="ganti_password">Ubah Kata Sandi</label>
                                            </div>
                                            </div>
                                        </div>
                                        
                                        <div class="position-relative row form-group">
                                            <div class="col-sm-10 offset-sm-2">
                                            <button type="submit" class="btn-shadow btn btn-info">
                                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fa fa-save fa-w-20"></i>
                                            </span>
                                            Simpan Data
                                        </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
<script src="<?php echo site_url("assets/scripts/jquery_3.4.1_jquery.min.js") ?>"></script>
<script type="text/javascript">
$(document).ready(function(){
    $("#file_ganti_foto").hide();
    $("#file").prop("disabled", true);
    $("#ganti_foto").change(function() {
            //console.log("cek");
            if(this.checked) {
                $("#file_ganti_foto").show();
                $("#file").prop("disabled", false);
                $("#file").prop("required", true);
            } else {
                $("#file_ganti_foto").hide();
                $("#file").prop("disabled", true);
                $("#file").val(null);
            }
    });

    $("#canvas_ganti_ttd").hide();
    $("#ganti_ttd").change(function() {
            //console.log("cek");
            if(this.checked) {
                $("#canvas_ganti_ttd").show();
               
            } else {
                $("#canvas_ganti_ttd").hide();

            }
    });

    $("#ganti_password").change(function() {
        if(this.checked) {
            $("#password").prop("disabled", false);
            $("#password").prop("required", true);
        } else {
            $("#password").prop("disabled", true);
            $("#password").prop("required", false);
            $("#password").val("");
        }
    });
});
</script>
<script src="<?php echo site_url("assets/scripts/signature_pad.js") ?>"></script>
<script>
var canvas = document.getElementById('signature-pad');

var signaturePad = new SignaturePad(canvas);

$('#preview').click(function(){
  var data = signaturePad.toDataURL('image/png');
  $('#output').val(data);

  $("#sign_prev").show();
  $("#sign_prev").attr("src",data);
  // Open image in the browser
  //window.open(data);
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
                        