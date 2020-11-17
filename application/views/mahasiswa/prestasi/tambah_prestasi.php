

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-file icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <?php 
                                         $aksi = $this->input->get('aksi');
                                         if($aksi == 'unggah'){
                                             $form = 'mahasiswa/prestasi/form-prestasi-edit';
                                            
                                         }else{
                                             $form = 'mahasiswa/prestasi/form-prestasi-save';
                                            
                                         }
                                    ?>
                                    <div><?php echo $aksi == 'unggah' ? "Upload Sertifikat" : "Tambah Data Prestasi" ?>
                                        <div class="page-title-subheading"></div>
                                    </div>
                                </div>
                                
                            </div>
                        </div> <!-- app-page-title -->
                        <?php
                        // debug
                        //echo "<pre>";
                        //print_r($data_ta);
                        //echo "</pre>";
                        if(!empty($_GET['status']) && $_GET['status'] == 'gagal') {

                            echo '<div class="alert alert-danger fade show" role="alert">Format Atau Ukuran Berkas Tidak Sesuai</div>';
                        }
                       
                        ?>
                        <div class="row">
                        <div class="col-md-12">
                         <div class="main-card mb-3 card">
                                <div class="card-header"><?php echo $aksi == 'unggah' ? "Form Upload Sertifikat" : "Form Tambah Data Prestasi" ?></div>
                                <div class="card-body">
                                <form method="post" action="<?php echo site_url($form) ?>" enctype="multipart/form-data" >
                                    <?php 
                                        if($aksi == 'unggah'){
                                            $id = $this->input->get('id');
                                            echo "<input type='hidden' name='id' value='$id' />";
                                        }
                                    ?>
                                    <div class="position-relative row form-group">
                                        <label for="prodi" class="col-sm-3 col-form-label">Jenis Kegiatan</label>
                                        <div class="col-sm-3">
                                            <select name="jenis" required class=" form-control">
                                                <option value="">-- Pilih Jenis Kegiatan --</option>
                                                <option value="Akademik">Akademik</option>
                                                <option value="Non Akademik">Non Akademik</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="position-relative row form-group">
                                        <label for="prodi" class="col-sm-3 col-form-label">Tingkat</label>
                                        <div class="col-sm-6">
                                            <select name="tingkat" required class=" form-control">
                                                <option value="">-- Pilih Tingkat Kegiatan --</option>
                                                <option value="Lokal">Lokal (Universitas Lampung)</option>
                                                <option value="Wilayah">Wilayah (Kota/Kabupaten, Provinsi, Regional)</option>
                                                <option value="Nasional">Nasional</option>
                                                <option value="Internasional">Internasional</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="position-relative row form-group">
                                        <label for="prodi" class="col-sm-3 col-form-label">Tahun</label>
                                        <div class="col-sm-3">
                                            <input type="text" required class="form-control" name="tahun" value="" placeholder="cth: 2020" />
                                        </div>
                                    </div>

                                    <div class="position-relative row form-group">
                                        <label for="prodi" class="col-sm-3 col-form-label">Nama Kegiatan</label>
                                        <div class="col-sm-9">
                                            <input type="text" required class="form-control" name="kegiatan" value="" placeholder="cth: Gemastik" />
                                        </div>
                                    </div>

                                    <div class="position-relative row form-group">
                                        <label for="prodi" class="col-sm-3 col-form-label">Penyelenggara</label>
                                        <div class="col-sm-9">
                                            <input type="text" required class="form-control" name="penyelenggara" value="" placeholder="cth: Kementerian" />
                                        </div>
                                    </div>

                                    <div class="position-relative row form-group">
                                        <label for="prodi" class="col-sm-3 col-form-label">Status</label>
                                        <div class="col-sm-3">
                                            <select name="capaian" required class=" form-control">
                                                <option value="">-- Pilih Status --</option>
                                                <option value="Peserta">Peserta</option>
                                                <option value="Finalis">Finalis</option>
                                                <option value="Juara Favorit/Kategori">Juara Favorit/Kategori</option>
                                                <option value="Juara III">Juara III (Perunggu)</option>
                                                <option value="Juara II">Juara II (Perak)</option>
                                                <option value="Juara I">Juara I (Emas)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <?php 
                                        if($aksi != 'unggah'){
                                    ?>
                                    <div class="position-relative row form-group">
                                        <label for="prodi" class="col-sm-3 col-form-label">Kategori</label>
                                        <div class="col-sm-3">
                                            <select name="kategori" required class=" form-control" id="kategori">
                                                <option value="">-- Pilih Kategori --</option>
                                                <option value="Individu">Individu</option>
                                                <option value="Tim">Tim</option>
                                            </select>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <div class="position-relative row form-group" id="ketua" style="display:none">
                                        <label for="prodi" class="col-sm-3 col-form-label"><b>Ketua</b></label>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" name="ketua" value="<?php echo $this->session->userdata('username') ?>" readonly placeholder="NPM" />
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="btn btn-primary" id='tambah_mhs'>Tambah Anggota</label> 
                                        </div>
                                    </div>

                                    <div id="container">

                                    </div>

                                    <div class="position-relative row form-group">
                                        <label for="prodi" class="col-sm-3 col-form-label">Upload Sertifikat</label>
                                        <div class="col-sm-9">
                                            <input oninvalid="this.setCustomValidity('Anda belum memilih berkas!')" oninput="this.setCustomValidity('')" required accept=".pdf" name="file" id="file" type="file" class="form-control-file">
                                            <small style="color:red">*Berkas Diunggah Dalam Format PDF/Ukuran Maks 2Mb</small>
                                           
                                        </div>
                                    </div>
                                

                                    <div class="position-relative row form-group">
                                        <div class="col-sm-9 offset-sm-3">
                                        <button id="preview" value="<?php if($this->input->get('aksi') == "ubah") echo "ubah"; ?>" type="submit" class="btn-shadow btn btn-info">
                                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fa fa-save fa-w-20"></i>
                                            </span>
                                            <?php if($this->input->get('aksi') == "ubah") echo "Ubah"; else echo "Simpan" ?> Data
                                         </button>
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

<script>
 $(document).ready(function(){
    $('#kategori').on('change', function() {
        if (this.value == 'Tim'){
            jQuery("#ketua").show();
        }else {
            jQuery("#ketua").hide();
            jQuery('.field').remove();
        }
    });

    var wrapper = $("#container");
    var i = 1;
    $('#tambah_mhs').click(function(){
        $(wrapper).append("<div class='position-relative row form-group field'><div class='col-sm-3'>Anggota "+i+"</div><div class='col-lg-3'><input type='text' class='form-control' name='anggota[]' value='' placeholder='NPM "+i+"' /></div></div>");
        i++;   
        
    });  

    
}); 

</script>
                        