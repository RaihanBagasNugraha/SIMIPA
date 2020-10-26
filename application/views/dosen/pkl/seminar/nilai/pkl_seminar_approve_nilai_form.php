
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
                                <form method="post" action="<?php echo site_url('dosen/pkl/approve-nilai-seminar/save') ?>" >
                                    <input value="<?php echo $seminar->seminar_id ?>" type = "hidden" required name="seminar_id" id="id_seminar">

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

                                    <!-- Judul -->
                                    <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">Judul</label>
                                            <div class="col-sm-9">
                                                <textarea class= "form-control" readonly><?php echo $seminar->judul ?></textarea>
                                            </div>
                                    </div>
                                    
                                    <?php $id_komp = $this->pkl_model->get_pkl_nilai_npm($pkl->npm)->id; ?>
                                    <input value="<?php echo  $id_komp ?>" type = "hidden" required name="komponen">
                                    <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label"><b>Komponen Penilaian</b></label>
                                    </div>
                                    <table class="mb-0 table table-striped"  id="example">
                                            <thead>
                                            <tr>
                                                <th style="width:60%">Aspek Yang Dinilai</th>
                                                <th style="width:20%">Persentase</th>
                                                <th style="width:20%">Nilai</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td class="align-top" colspan = "3">
                                                    1. Seminar
                                                </td> 
                                               
                                            </tr>
                                            <?php 
                                                $kom_seminar = $this->pkl_model->get_seminar_komponen_meta($id_komp,"Seminar"); 
                                                $s = 1;
                                                if(!empty($kom_seminar)){
                                                    foreach($kom_seminar as $kom){
                                            ?>
                                                <tr>
                                                <td class="align-top">
                                                    <?php echo "&emsp;&emsp;&emsp;$s. $kom->attribut" ?>
                                                </td> 
                                                <td class="align-top">
                                                    <?php echo $kom->persentase."%" ?>
                                                </td> 
                                                <td>
                                                    <input value="" required name="seminar[<?php echo $s ?>]" type="number" min="0" placeholder="Input Nilai" max="100" class="form-control input-mask-trigger">
                                                    <input value="<?php echo $kom->id ?>" required name="id_seminar[<?php echo $s ?>]" type="hidden" min="0" placeholder="Input Nilai" max="100" class="form-control input-mask-trigger">
                                                </td>
                                               
                                                </tr>
                                            <?php   
                                                $s++;         
                                                    }
                                                }
                                            ?>

                                            <tr>
                                                <td class="align-top" colspan = "3"> 
                                                    2. Laporan
                                                </td> 
                                                
                                            </tr>
                                            <?php 
                                                $kom_seminar = $this->pkl_model->get_seminar_komponen_meta($id_komp,"Laporan"); 
                                                $l = 1;
                                                if(!empty($kom_seminar)){
                                                
                                                    foreach($kom_seminar as $kom){
                                            ?>
                                                <tr>
                                                <td class="align-top">
                                                    <?php echo "&emsp;&emsp;&emsp;$l. $kom->attribut" ?>
                                                </td> 
                                                <td class="align-top">
                                                    <?php echo $kom->persentase."%" ?>
                                                </td> 

                                                <td>
                                                    <input value="" required name="laporan[<?php echo $l ?>]" type="number" min="0" placeholder="Input Nilai" max="100" class="form-control input-mask-trigger">
                                                    <input value="<?php echo $kom->id ?>" required name="id_laporan[<?php echo $l?>]" type="hidden" min="0" placeholder="Input Nilai" max="100" class="form-control input-mask-trigger">
                                                </td>
                                               
                                                </tr>
                                            <?php    
                                                $l++;        
                                                    }
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                    <input value="<?php echo $s-1 ?>" type = "hidden" required name="jml_seminar">
                                    <input value="<?php echo $l-1 ?>" type = "hidden" required name="jml_laporan">

                                    <br><br><br><br>
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