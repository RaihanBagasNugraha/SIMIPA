

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-file icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    
                                    <div>Form Layanan <?php echo $layanan->bagian ?>
                                        <div class="page-title-subheading">Data Form Layanan
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
                        $jns = $this->uri->segment(3);
                        ?>
                        <div class="row">
                        <div class="col-md-12">
                         <div class="main-card mb-3 card">
                                <div class="card-header">Form Layanan <?php echo $layanan->bagian ?></div>
                                <div class="card-body">
                                    <form method="post" action="<?php echo site_url('mahasiswa/layanan-fakultas/'.$jns.'/form-simpan') ?>" >
                                    
                                        <!--jenis -->
                                        <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-3 col-form-label">Jenis Form</label>
                                            <div class="col-sm-9">
                                                <input type="text" class = "form-control" name = "layanan" required value="<?php echo $layanan->nama ?>" readonly />
                                                <input type="hidden" class = "form-control" name = "id_layanan"  value="<?php echo $layanan->id_layanan_fakultas ?>" /> 
                                                <input type="hidden" class = "form-control" name = "jenis"  value="<?php echo $jns ?>" />      
                                                <input type="hidden" class = "form-control" name = "approver"  value="<?php echo $layanan->approver ?>" />                                    
                                            </div>
                                        </div>

                                        <!--atribut-->
                                        <?php if(!empty($atribut)){ 
                                            $i = 0;
                                            foreach($atribut as $row){
                                                $tipe = $row->tipe;
                                        ?>
                                            <input type="hidden" name = "id_attribut[]" value="<?php echo $row->id_atribut ?>" />                            
                                            <div class="position-relative row form-group">
                                                <label for="prodi" class="col-sm-3 col-form-label"><b><?php echo $row->nama ?></b></label>
                                                <?php if($tipe == "text"){ ?>
                                                    <div class="col-sm-9">
                                                        <input type="text" class = "form-control" placeholder="<?php echo $row->placeholder == null ? "" : $row->placeholder ?>" name = "<?php echo $row->id_atribut ?>" value=""/>                                         
                                                    </div>  
                                                <?php } elseif($tipe == "textarea"){ ?>
                                                    <div class="col-sm-9">
                                                        <textarea name = "<?php echo $row->id_atribut ?>" class="form-control" placeholder="<?php echo $row->placeholder == null ? "" : $row->placeholder ?>" value="" ></textarea>
                                                    </div>
                                                <?php } elseif($tipe == "option"){ ?>
                                                    <div class="col-sm-9">
                                                        <select name = "<?php echo $row->id_atribut ?>" class = "form-control">
                                                            <option value = "">-- <?php echo $row->placeholder == null ? "Pilih" : $row->placeholder ?> --</option>
                                                            <?php 
                                                                $pilihan = explode("#", $row->pilihan);
                                                                $p = 0;
                                                                foreach($pilihan as $pil){
                                                            ?>
                                                                <option value="<?php echo $pilihan[$p] ?>"><?php echo $pilihan[$p] ?></option>
                                                        <?php $p++;
                                                        } ?>    
                                                        </select>
                                                         
                                                    </div>
                                                <?php } elseif($tipe == "date"){ ?>
                                                    <div class="col-sm-3">
                                                        <input type="text" class = "form-control tgl" placeholder="<?php echo $row->placeholder == null ? "" : $row->placeholder ?>" name = "<?php echo $row->id_atribut ?>" value=""/> 
                                                    </div>
                                                <?php }  ?>
                                            </div>
                                        <?php 
                                            $i++;
                                            if($layanan->id_layanan_fakultas == 4){
                                                if($i % 6 == 0){
                                                    echo "<br>";
                                                                                                    
                                                }
                                            }
                                            elseif($layanan->id_layanan_fakultas == 34){
                                                if($i % 5 == 0){
                                                    echo "<br>";
                                                                                                    
                                                }
                                            }    
                                        }
                                        //surat tugas individu
                                        if($layanan->id_layanan_fakultas == 32 || $layanan->id_layanan_fakultas == 33){
                                         ?>
                                            <div class="position-relative row form-group">
                                                <label for="prodi" class="col-sm-3 col-form-label"><label class="btn btn-primary" id='tambah_mhs'>Tambah Mahasiswa&emsp;<i class="fa fa-plus" aria-hidden="true"></i></label></label>
                                            </div>

                                            <div id="container">
                                               
                                            </div>
                                            
                                        <?php 
                                        echo "<br>";}
                                        } else{ 
                                        ?>
                                        <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-3 col-form-label">Keterangan</label>
                                            <div class="col-sm-9">
                                                <input type="text" readonly class = "form-control" placeholder="-" name = "" value=""/> 
                                            </div>
                                        </div>

                                        <?php } ?>
                                       
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
                                            <!--<a id="preview2" class="mb-2 btn btn-light">Oke-->
                                            <!--</a>-->
                                            <input type="hidden" style="background-color: #efefef;" type="text" class="form-control readonly" required placeholder="" name="ttd" id="output" value="">
                                            <input type="hidden" name="aksi" value="<?php if(!empty($this->input->get("aksi"))) echo $this->input->get("aksi") ?>">
                                            </div>
                                    
                                        </div>

                                        <div class="position-relative row form-group">
                                            <div class="col-sm-9 offset-sm-3">
                                            <button id="preview" value="<?php if($this->input->get('aksi') == "ubah") echo "ubah"; ?>" type="submit" class="btn-shadow btn btn-info">
                                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fa fa-save fa-w-20"></i>
                                            </span>
                                            <?php if($this->input->get('aksi') == "ubah") echo "Ubah"; else echo "Simpan" ?>
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
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script type="text/javascript">
$(document).ready(function(){
    $(".readonly").on('keydown paste', function(e){
        e.preventDefault();
        $(this).blur();
    });

    $('.tgl').datepicker({
        dateFormat : 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
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

<script>
$(document).ready(function(){
    var wrapper = $("#container");

    var i = 1;
    $('#tambah_mhs').click(function(){
        $(wrapper).append("<div class='position-relative row form-group field'><div class='col-sm-3'><input value='' type = 'text' placeholder ='Nama' name='nama[]' class='form-control'></div><div class='col-lg-3'><input value='' type = 'text' placeholder ='NPM' name='npm[]' class='form-control'></div><div class='col-lg-3'><select required name='jurusan[]' class = 'form-control'><option value = ''>-- Pilih Jurusan --</option><option value='Doktor MIPA' >Doktor MIPA</option><option value='Kimia' >Kimia</option><option value='Biologi' >Biologi</option><option value='Matematika' >Matematika</option><option value='Fisika' >Fisika</option><option value='Ilmu Komputer' >Ilmu Komputer</option></select></div><div class='col-lg-3'><input value='' type = 'text' placeholder ='Alamat' name='alamat[]' class='form-control'></div></div>");
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

    $( "#tambah_ttd" ).click(function() {
        jQuery("#ttd").show();
    });

});
</script>


                        