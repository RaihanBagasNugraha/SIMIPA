
<div class="app-page-title">
                        <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-file icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <div>Approval Nilai Seminar KP/PKL
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
                        ?>

                        <div class="row">
                        <div class="col-md-12">
                         <div class="main-card mb-3 card">
                                <div class="card-header">Approval Nilai Seminar KP/PKL</div>
                                <div class="card-body">
                                <form method="post" action="<?php echo site_url('dosen/pkl/seminar-nilai/koordinator/save') ?>" >
                                    <input value="<?php echo $seminar->seminar_id ?>" type = "hidden" required name="seminar_id" id="id_seminar">
                                    <input value="<?php echo $seminar->pkl_id ?>" type = "hidden" required name="pkl_id" id="">

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

                                    <!-- NILAI -->
                                    <?php 
                                        //bobot
                                        $jurusan = $this->user_model->get_jurusan($pkl->npm);
                                        $bobot1 = $this->pkl_model->get_pkl_seminar_komponen_jurusan($jurusan);
                                        $bobot = explode("#",$bobot1->bobot);
                                    ?>
                                    <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label"><b>Penilaian</b></label>
                                    </div>

                                    <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">Dosen Pembimbing KP/PKL</label>
                                            <div class="col-sm-6">
                                            <?php $dosen_pmb = $this->user_model->get_dosen_name($pkl->pembimbing); ?>
                                                <input value="<?php echo $dosen_pmb->gelar_depan." ".$dosen_pmb->name.", ".$dosen_pmb->gelar_belakang; ?>" required name="pbb" class="form-control " readonly >
                                            </div>
                                    </div>

                                    <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">Nilai</label>
                                           
                                            <div class="col-sm-2">
                                            <?php 
                                                //nilai
                                                $nilai = $this->pkl_model->get_nilai_seminar($seminar->seminar_id);
                                                $tot_nilai = 0;
                                                foreach($nilai as $nil){
                                                    $tot_nilai += $nil->nilai * ($nil->persentase/100);
                                                }
                                               $total_nilai = round($tot_nilai,2);
                                            ?>
                                            <input value="<?php echo $total_nilai ?>" required name="nilai_pbb" class="form-control input-mask-trigger" readonly >
                                            </div>
                                           
                                    </div>

                                    <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">Dosen Pembimbing Lapangan</label>
                                            <div class="col-sm-6">
                                            <?php 
                                                 $pb_lp = $this->pkl_model->get_pb_lapangan($seminar->pkl_id);
                                                if(!empty($pb_lp)){
                                            ?>
                                                <input value="<?php echo $pb_lp->nama ?>" required name="pbl" class="form-control " readonly >
                                            <?php } else { ?>
                                                <input value="-" required name="pbl" class="form-control " readonly >
                                            <?php } ?>
                                            
                                            </div>
                                    </div>

                                    <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">Nilai</label>
                                           
                                            <div class="col-sm-2">
                                            <?php 
                                                 $pb_lp = $this->pkl_model->get_pb_lapangan($seminar->pkl_id);
                                                if(!empty($pb_lp)){
                                            ?>
                                                <input value="0" type="number" min="0" max="100" name="nilai_pbl" class="form-control input-mask-trigger" required placeholder="Nilai" >
                                            <?php } else { ?>
                                                <input value="0" type="number" min="0" max="100" name="nilai_pbl" class="form-control input-mask-trigger" required placeholder="Nilai" readonly>
                                            <?php } ?>
                                            </div>    
                                            
                                    </div>
      
                                    <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">Pengurangan Nilai Akhir</label>
                                            <div class="col-sm-2">
                                            <input value="0" type="number" min="0" max="100" name="pengurangan_koor" class="form-control input-mask-trigger" placeholder="Nilai" >
                                            </div>    
                                            <div class="col-sm-10">
                                            </div>    
                                            <div class="col-sm-3">
                                            </div>    
                                            <div class="col-sm-9">
                                            <small><span style="color:red" >*</span> Isi Jika Terdapat Pengurangan Nilai Akhir Oleh Koordinator KP/PKL</small>
                                            </div>    
                                            
                                    </div>
                                    

                                    <!-- TTD -->
                                    <div class="position-relative row form-group">
                                    <label for="ttd" class="col-sm-3 col-form-label">Tanda Tangan Digital</label>
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