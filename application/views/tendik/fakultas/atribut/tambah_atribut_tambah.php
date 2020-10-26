

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-file icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <div>Tambah Atribut Form Layanan
                                        <div class="page-title-subheading">Tambah Attribut
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
                                    <form method="post" action="<?php echo site_url('tendik/atribut-form/atribut/save') ?>" >

                                        <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-3 col-form-label">Jenis Layanan</label>
                                            <div class="col-sm-9">
                                                <input type="text" class = "form-control" name = "jenis" required value="<?php echo $jenis ?>" readonly />
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-3 col-form-label">Jenis Form</label>
                                            <div class="col-sm-9">
                                            <?php $forms = $this->layanan_model->select_layanan_by_id($form); ?>
                                                <input type="text" class = "form-control" name = "form" required value="<?php echo $forms->nama ?>" readonly />
                                                <input type="hidden" class = "form-control" name = "id_form" required value="<?php echo $form ?>" readonly />
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-3 col-form-label">Attribut &emsp;<label class="btn btn-primary" id='tambah_attr'>+</label> </label>
                                        </div>

                                        <div id="container">
                                           
                                        </div>

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
    var wrapper = $("#container");

    var i = 1;
    $('#tambah_attr').click(function(){
        $(wrapper).append("<div class='position-relative row form-group field'><div class='col-sm-3'>Nama</div><div class='col-lg-3'>Tipe</div><div class='col-lg-3'>Placeholder</div><div class='col-lg-3' style='display: none;'  id = 'opsih_"+i+"'>Pilihan</div></div>");
        $(wrapper).append('<div class="position-relative row form-group field"><div class="col-sm-3"><input value="" type = "text" placeholder ="Nama" name="nama[]" class="form-control" ></div><div class="col-lg-3"><select name="tipe[]" class = "form-control select_option" id ="opsi_'+i+'"><option value = "">-- Pilih Tipe --</option><option value = "text">Text</option><option value="text">Textarea</option><option value="date">Date</option><option value="option" >Option</option> </select></div><div class="col-lg-3"><input value="" type = "text" placeholder ="Placeholder" name="placeholder[]" class="form-control" ></div><div class="col-lg-3" id = "opsir_'+i+'" style="display: none;"><input value="" type = "text" placeholder ="Pilihan1#Pilihan2#Pilihan3" name="pilihan[]" class="form-control" ></div></div>');
        i++;   
        $(".select_option").on('change', function() {
            var id = $(this).attr('id');
            var id_split = id.split("_");
            // console.log('id = '+id);
            if(this.value == 'option')
            {
                jQuery("#opsir_"+id_split[1]).show();
                jQuery("#opsih_"+id_split[1]).show();
            }
            else{
                jQuery("#opsir_"+id_split[1]).hide();
                jQuery("#opsih_"+id_split[1]).hide();
            }
        });    
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

                        