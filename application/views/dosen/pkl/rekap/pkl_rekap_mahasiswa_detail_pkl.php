
<div class="app-page-title">
                        <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-file icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <div>Detail KP/PKL Mahasiswa
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
                                <div class="card-header">Detail KP/PKL Mahasiswa</div>
                                <div class="card-body">

                                     <!-- tahun.periode-->
                                     <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">Tahun/Periode</label>
                                            <div class="col-sm-3">
                                            <?php  $periode = $this->pkl_model->get_pkl_kajur_by_id($pkl->id_periode); ?>
                                                <input value="<?php echo $periode->tahun."/".$periode->periode ?>" required name="npm" class="form-control input-mask-trigger" readonly >
                                            </div>
                                    </div>

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

                                    <!-- lokasi-->
                                    <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">Lokasi</label>
                                            <div class="col-sm-9">
                                            <?php  $lokasi = $this->pkl_model->get_lokasi_pkl_by_id($pkl->id_lokasi); ?>
                                                <input value="<?php echo $lokasi->lokasi ?>" required name="npm" class="form-control input-mask-trigger" readonly >
                                            </div>
                                    </div>

                                    <!-- pbb -->
                                    <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">Dosen Pembimbing</label>
                                            <div class="col-sm-9">
                                            <?php $dosen_pmb = $this->user_model->get_dosen_name($pkl->pembimbing); ?>
                                                <input value="<?php echo $dosen_pmb->gelar_depan." ".$dosen_pmb->name.", ".$dosen_pmb->gelar_belakang; ?>" required name="sks" class="form-control " readonly >
                                            </div>
                                    </div>

                                    <!-- pbl -->
                                    <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">Pembimbing Lapangan</label>
                                            <div class="col-sm-9">
                                            <?php  
                                                $pb_lp = $this->pkl_model->get_pb_lapangan($pkl->pkl_id); 
                                                if(!empty($pb_lp)){
                                            ?>
                                                <input value="<?php echo $pb_lp->nama; ?>" required name="sks" class="form-control " readonly >
                                            <?php } else{ ?>
                                                <input value="<?php echo "-" ?>" required name="sks" class="form-control " readonly >
                                            <?php } ?>
                                            </div>
                                    </div>

                                    <!-- nomor-->
                                    <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">Nomor Surat</label>
                                            <div class="col-sm-3">
                                                <input value="<?php echo $pkl->no_penetapan ?>" required name="npm" class="form-control input-mask-trigger" readonly >
                                            </div>
                                    </div>

                                     <!-- lampiran-->
                                     <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">Lampiran</label>
                                            <div class="col-sm-6">
                                                <?php 
                                                      $lampiran = $this->pkl_model->select_lampiran_by_pkl($pkl->pkl_id, $pkl->npm); 
                                                      if(empty($lampiran)) {
                                                          echo "<i>(Belum ada, silakan lengkapi berkas lampiran)</i>";
                                                      } else {
                                                          $approval = $this->pkl_model->get_approval_koor_by_pkl_id($pkl->pkl_id);
                                                          if(!empty($approval)){
                                                              if($approval->file != NULL){
                                                                  echo "<li><a href='".base_url($approval->file)."' download>".$approval->nama_file."</a></li>";
                                                              }
                                                          }
  
                                                          if($pkl->status >= 0 && $pkl->status != 6){
                                                              echo "<li><a href=".site_url("mahasiswa/tugas-akhir/pkl/form_pdf?jenis=form_pengajuan&id=$pkl->pkl_id").">Form Pengajuan KP/PKL</a></li>"; 
                                                          }
                                                          else{
                                                            echo "<li>Form Pengajuan KP/PKL</li>"; 
                                                          }
                                                          if($pkl->status >= 2 && $pkl->status != 6){
                                                              echo "<li><a href=".site_url("mahasiswa/tugas-akhir/pkl/form_pdf?jenis=form_verifikasi&id=$pkl->pkl_id").">Form Verifikasi Berkas</a></li>"; 
                                                          }
                                                          else{
                                                            echo "<li>Form Verifikasi Berkas</li>"; 
                                                          }
                                                          if($pkl->status >= 8){
                                                              echo "<li><a href=".site_url("mahasiswa/tugas-akhir/pkl/form_pdf?jenis=form_permohonan&id=$pkl->pkl_id").">Form Permohonan KP/PKL</a></li>"; 
                                                          }
                                                          else{
                                                            echo "<li>Form Permohonan KP/PKL</li>"; 
                                                          }
                                                          foreach($lampiran as $rw) {
                                                              echo "<li><a href='".base_url($rw->file)."' download>".$rw->nama_berkas."</a></li>";
                                                          }
          
                                                          echo "</ul>";
                                                      }
                                                ?>
                                            </div>
                                    </div>
                                    
                                



                    

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