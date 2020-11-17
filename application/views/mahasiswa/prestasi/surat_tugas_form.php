

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-file icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <div>Tambah Surat Tugas
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
                                <div class="card-header">Form Tambah Surat Tugas</div>
                                <div class="card-body">
                                <form method="post" action="<?php echo site_url('mahasiswa/prestasi/surat-tugas/simpan') ?>" >

                                    <div class="position-relative row form-group">
                                        <label for="prodi" class="col-sm-3 col-form-label">Jenis Surat Tugas</label>
                                        <div class="col-sm-6">
                                           <input type="text" class="form-control" value="<?php echo $surat ?>" readonly name="jenis" />
                                           <input type="hidden" class="form-control" value="<?php echo $id_layanan ?>" readonly name="id_layanan" />
                                        </div>
                                    </div>

                                    <!-- get meta -->
                                     <!--atribut-->
                                     <?php if(!empty($atribut)){ 
                                            $i = 0;
                                            foreach($atribut as $row){
                                                $tipe = $row->tipe;
                                        ?>
                                            <input type="hidden" name = "id_attribut[]" value="<?php echo $row->id_atribut ?>" />                            
                                            <div class="position-relative row form-group">
                                                <label for="prodi" class="col-sm-3 col-form-label"><?php echo $row->nama ?></label>
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
                                            
                                        }
                                        //surat tugas individu
                                        // if($layanan->id_layanan_fakultas == 32 || $layanan->id_layanan_fakultas == 33){
                                         ?>
                                            <!-- <div class="position-relative row form-group">
                                                <label for="prodi" class="col-sm-3 col-form-label"><label class="btn btn-primary" id='tambah_mhs'>Tambah Mahasiswa&emsp;<i class="fa fa-plus" aria-hidden="true"></i></label></label>
                                            </div>

                                            <div id="container">
                                               
                                            </div> -->
                                            
                                        <?php 
                                        // echo "<br>";}
                                        } else{ 
                                        ?>
                                        <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-3 col-form-label">Keterangan</label>
                                            <div class="col-sm-9">
                                                <input type="text" readonly class = "form-control" placeholder="-" name = "" value=""/> 
                                            </div>
                                        </div>

                                        <?php } ?>
                                  
                                    <div class="position-relative row form-group">
                                        <label for="prodi" class="col-sm-3 col-form-label"><label class="btn btn-primary" id='tambah_mhs'>Tambah Mahasiswa&emsp;<i class="fa fa-plus" aria-hidden="true"></i></label></label>
                                        </div>

                                        <div id="container">
                                               
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
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
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

    $('.tgl').datepicker({
            dateFormat : 'yy-mm-dd',
            changeMonth: true,
            changeYear: true
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
        $(wrapper).append("<div class='position-relative row form-group field'><div class='col-sm-3'><input value='' type = 'text' placeholder ='Nama' name='nama[]' class='form-control'></div><div class='col-lg-3'><input value='' type = 'text' placeholder ='NPM' name='npm[]' class='form-control'></div><div class='col-lg-3'><select required name='jurusan[]' class = 'form-control'><option value = ''>-- Pilih Jurusan --</option><option value='Doktor MIPA' >Doktor MIPA</option><option value='Kimia' >Kimia</option><option value='Biologi' >Biologi</option><option value='Matematika' >Matematika</option><option value='Fisika' >Fisika</option><option value='Ilmu Komputer' >Ilmu Komputer</option></select></div><div class='col-lg-3'><input value='' type = 'text' placeholder ='Alamat' name='alamat[]' class='form-control'></div></div>");
        i++;   
        
    });     
}); 

</script>
                        