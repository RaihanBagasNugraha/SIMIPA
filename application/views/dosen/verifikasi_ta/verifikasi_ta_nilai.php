
<div class="app-page-title">
                        <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-file icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <div>Verifikasi Program Tugas Akhir
                                        <div class="page-title-subheading">
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
                            $select = "selected";
                        ?>

                        <div class="row">
                        <div class="col-md-12">
                         <div class="main-card mb-3 card">
                                <div class="card-header">Verifikasi Program Tugas Akhir</div>
                                <div class="card-body">
                                <form method="post" action="<?php echo site_url('dosen/tugas-akhir/nilai-verifikasi-ta/save') ?>" >
                                    <input value="<?php echo $ta->id_pengajuan ?>" type = "hidden" required name="id_pengajuan" id="id">

                                    <!-- NPM -->
                                    <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label"><b>Npm</b></label>
                                            <div class="col-sm-3">
                                                <input value="<?php echo $ta->npm ?>" required name="npm" class="form-control input-mask-trigger" readonly >
                                            </div>
                                    </div>

                                     <!-- Nama -->
                                    <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label"><b>Nama</b></label>
                                            <div class="col-sm-3">
                                                <input value="<?php echo $this->user_model->get_mahasiswa_name($ta->npm) ?>" required name="name" class="form-control input-mask-trigger" readonly >
                                            </div>
                                    </div>

                                     <!-- Judul -->
                                     <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label"><b>Judul</b></label>
                                            <div class="col-sm-9">
                                            <?php if($ta->judul_approve == 1){?>
                                                    <textarea required name="judul" class="form-control" readonly placeholder="Judul Utama" id="inputother"><?php echo $ta->judul1 ?></textarea>
                                                <?php } elseif($ta->judul_approve == 2){?>
                                                    <textarea required name="judul" class="form-control" readonly placeholder="Judul Utama" id="inputother"><?php echo $ta->judul2 ?></textarea>
                                                <?php } ?>
                                            </div>
                                    </div>
                                    
                                    <table class="mb-0 table table-striped">
                                        <thead>
                                            <tr>
                                                <th style="width:90%">Pertanyaan Penguasaan</th>
                                                <th>Pertemuan Ke-</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan = "2"><b>1. Wajib</b></td>
                                            </tr>

                                        <?php foreach($wajib as $wj) {?>
                                        
                                            <tr>
                                            <?php $cek_wajib[$wj->id_verif] = $this->ta_model->get_pertemuan_verifikasi_ta($wj->id_verif,$ta->id_pengajuan)->pertemuan; ?>
                                            <!-- style="background-color:#FF0000" -->
                                            <?php if($cek_wajib[$wj->id_verif] != 0) {?>
                                                <td><?php echo $wj->komponen;?></td>
                                            <?php } else{?>
                                                <td style="background-color:#FF0000"><span style="color: #fff"><?php echo $wj->komponen;?></span></td>
                                            <?php }?>    
                                                
                                                <td>
                                                <?php if($cek_wajib[$wj->id_verif] != 0) { ?>
                                                    <select name="<?php echo $wj->id_verif ?>">
                                                        <?php for($i=0;$i<=10;$i++) { ?>
                                                            <option value=" <?php echo $i; ?>" <?php echo $i == $cek_wajib[$wj->id_verif] ? $select : "" ?> ><?php echo $i; ?></option>";
                                                        <?php }?>
                                                    </select>
                                                <?php } else {?>
                                                    <select name="<?php echo $wj->id_verif ?>">
                                                        <?php for($i=0;$i<=10;$i++) {
                                                            echo "<option value=$i>$i</option>";
                                                        }?>
                                                    </select>
                                                <?php } ?>    
                                                </td>
                                            </tr>
                                        <?php } ?>
                                            <tr>
                                                <td colspan = "2"><b>2. Konten Program</b></td>
                                                
                                            </tr>
                                        <?php foreach($konten as $kt) {?>
                                            <tr>
                                            <?php $cek_wajib[$kt->id_verif] = $this->ta_model->get_pertemuan_verifikasi_ta($kt->id_verif,$ta->id_pengajuan)->pertemuan; ?>
                                            <?php if($cek_wajib[$kt->id_verif] != 0) {?>
                                                <td><?php echo $kt->komponen;?></td>
                                            <?php } else{?>  
                                                <td style="background-color:#FF0000"><span style="color: #fff"><?php echo $kt->komponen;?></span></td>
                                            <?php }?>     
                                                <td>
                                                <?php if($cek_wajib[$kt->id_verif] != 0) { ?>
                                                    <select name="<?php echo $kt->id_verif ?>">
                                                        <?php for($i=0;$i<=10;$i++) { ?>
                                                            <option value=" <?php echo $i; ?>" <?php echo $i == $cek_wajib[$kt->id_verif] ? $select : "" ?> ><?php echo $i; ?></option>";
                                                        <?php }?>
                                                    </select>
                                                <?php } else {?>
                                                    <select name="<?php echo $kt->id_verif ?>">
                                                        <?php for($i=0;$i<=10;$i++) {
                                                            echo "<option value=$i>$i</option>";
                                                        }?>
                                                    </select>
                                                <?php } ?>  
                                                </td>
                                            </tr>
                                        <?php } ?>    

                                        </tbody>
                                    </table>

                                    <br>
                                    <p style="color: #FF0000">*Penilaian Dibuka Setelah Selesai Mengisi Pertemuan
                                    <br>
                                    <div class="position-relative row form-group">
                                            <div class="col-sm-9 offset-sm-9">
                                            <button value="<?php if($this->input->get('aksi') == "ubah") echo "ubah"; ?>" type="submit" class="btn-shadow btn btn-info">
                                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fa fa-save fa-w-20"></i>
                                            </span>
                                            <?php if($this->input->get('aksi') == "ubah") echo "Ubah"; else echo "Simpan Nilai" ?>
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