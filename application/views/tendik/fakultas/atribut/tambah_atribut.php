

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-file icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <div>Tambah Atribut Form Layanan
                                        <div class="page-title-subheading">Pilih Form Layanan
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
                                <div class="card-header">Form Layanan</div>
                                <div class="card-body">
                                    <form method="post" action="<?php echo site_url('tendik/atribut-form/atribut/tambah') ?>" >
                                    <input value="<?php echo $this->session->userdata('userId') ?>" type="hidden" required name="user_id">
                                        
                                        <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-3 col-form-label">Pilih Jenis Layanan</label>
                                            <div class="col-sm-9">
                                                <select required name="jns_layanan" id="jenis_layanan"  class = "form-control">
                                                    <option value = "">-- Jenis Layanan --</option>
                                                    <option value = "Akademik">Akademik</option>
                                                    <option value = "Umum dan Keuangan">Umum dan Keuangan</option>
                                                    <option value = "Kemahasiswaan">Kemahasiswaan</option>
                                                </select>
                                               
                                            </div>
                                        </div>

                                        <div style="display: none;" id="akademik" class="field">
                                            <div class="position-relative row form-group">
                                                <label for="prodi" class="col-sm-3 col-form-label">Form Layanan Akademik</label>
                                                <div class="col-sm-9">
                                                    <select name="form" id="id_form" class = "form-control">
                                                        <option value = "">-- Pilih Form --</option>
                                                        <?php 
                                                            $form = $this->layanan_model->select_layanan_by_bagian('Akademik');
                                                            foreach($form as $row){
                                                        ?>
                                                            <option value = "<?php echo $row->id_layanan_fakultas ?>"><?php echo $row->nama; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div style="display: none;" id="umum" class="field">
                                            <div class="position-relative row form-group">
                                                <label for="prodi" class="col-sm-3 col-form-label">Form Layanan Umum dan Keuangan</label>
                                                <div class="col-sm-9">
                                                    <select name="form2" id="id_form2" class = "form-control">
                                                        <option value = "">-- Pilih Form --</option>
                                                        <?php 
                                                            $form = $this->layanan_model->select_layanan_by_bagian('Umum dan Keuangan');
                                                            foreach($form as $row){
                                                        ?>
                                                            <option value = "<?php echo $row->id_layanan_fakultas ?>"><?php echo $row->nama; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div style="display: none;" id="kemahasiswaan" class="field">
                                            <div class="position-relative row form-group">
                                                <label for="prodi" class="col-sm-3 col-form-label">Form Layanan Kemahasiswaan</label>
                                                <div class="col-sm-9">
                                                    <select name="form3" id="id_form3" class = "form-control">
                                                        <option value = "">-- Pilih Form --</option>
                                                        <?php 
                                                            $form = $this->layanan_model->select_layanan_by_bagian('Kemahasiswaan');
                                                            foreach($form as $row){
                                                        ?>
                                                            <option value = "<?php echo $row->id_layanan_fakultas ?>"><?php echo $row->nama; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div style="display: none;" id="submit" class="field">
                                            <div class="position-relative row form-group">
                                                <div class="col-sm-9 offset-sm-3">
                                                <button id="preview" value="<?php if($this->input->get('aksi') == "ubah") echo "tambah"; ?>" type="submit" class="btn-shadow btn btn-info">
                                                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                        <i class="fa fa-save fa-w-20"></i>
                                                    </span>
                                                    <?php if($this->input->get('aksi') == "ubah") echo "Ubah"; else echo "Tambah" ?>
                                                </button>
                                                </div>
                                            </div>
                                        </div>

                                    </form>
                                   
                                            
                                </div>
                            </div>
                            </div> <!-- col-md -->

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

<script>
$(document).ready(function(){
    $('#jenis_layanan').on('change', function() {
          if(this.value == 'Akademik')
          {
            jQuery("#akademik").show();
            jQuery("#umum").hide();
            jQuery("#kemahasiswaan").hide();
          }
          else if(this.value == 'Umum dan Keuangan')
          {
            jQuery("#akademik").hide();
            jQuery("#umum").show();
            jQuery("#kemahasiswaan").hide();
          }
          else if(this.value == 'Kemahasiswaan')
          {
            jQuery("#akademik").hide();
            jQuery("#umum").hide();
            jQuery("#kemahasiswaan").show();
          }
          else{
            jQuery("#akademik").hide();
            jQuery("#umum").hide();
            jQuery("#kemahasiswaan").hide();
            jQuery("#submit").hide();
          }
        });
    $('#id_form').on('change', function() {
        if(this.value == ''){
            jQuery("#submit").hide();
        }
        else{
            jQuery("#submit").show();
        }
            
        });
    $('#id_form2').on('change', function() {
        if(this.value == ''){
            jQuery("#submit").hide();
        }
        else{
            jQuery("#submit").show();
        }
            
        });
    $('#id_form3').on('change', function() {
        if(this.value == ''){
            jQuery("#submit").hide();
        }
        else{
            jQuery("#submit").show();
        }
            
        });
    }); 
</script>

<script src="<?php echo site_url("assets/scripts/signature_pad.js") ?>"></script>
<script>
var canvas = document.getElementById('signature-pad');

var signaturePad = new SignaturePad(canvas);

<?php if($this->input->get('aksi') == 'ubah' && !empty($this->input->get('id'))) { 
    
    $ttd_img = json_encode('');
    
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

                        