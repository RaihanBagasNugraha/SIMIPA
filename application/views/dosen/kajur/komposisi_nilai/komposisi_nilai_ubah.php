
<div class="app-page-title">
                        <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-file icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <div>Ubah Komposisi Nilai
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
                            if(!empty($_GET['status']) && $_GET['status'] == 'kurang') {

                                echo '<div class="alert alert-danger fade show" role="alert">Persentase Total Kurang Dari 100 %</div>';
                            }
                            if(!empty($_GET['status']) && $_GET['status'] == 'lebih') {

                                echo '<div class="alert alert-danger fade show" role="alert">Persentase Total Lebih Dari 100 %</div>';
                            }

                           
                        ?>

                        <div class="row">
                        <div class="col-md-12">
                         <div class="main-card mb-3 card">
                                <div class="card-header">Ubah Komposisi Nilai</div>
                                <div class="card-body">
                                <form method="post" action="<?php echo site_url('dosen/struktural/komposisi-nilai/edit') ?>" >
                                    <input value="<?php echo $komponen->id ?>" type = "hidden" required name="id">

                                <div class="position-relative row form-group">
                                    <label class="col-sm-3 col-form-label">Jurusan</label>
                                            <div class="col-sm-3">
                                            <?php 
                                                switch($komponen->id_prodi)
                                                {
                                                    case "5":
                                                    $jrs = "Ilmu Komputer";
                                                    break;
                                                    case "4":
                                                    $jrs = "Fisika";
                                                    break;
                                                    case "3":
                                                    $jrs = "Matematika";
                                                    break;
                                                    case "2":
                                                    $jrs = "Biologi";
                                                    break;
                                                    case "1":
                                                    $jrs = "Kimia";
                                                    break;
                                                    case "0":
                                                    $jrs = "Doktor MIPA";
                                                    break;
                                                }
                                            
                                            
                                            ?>
                                    <input value="<?php echo $jrs?>" type = "text" name="nama_jurusan" readonly class="form-control">
                                    <input value="<?php echo $komponen->id_prodi?>" type = "hidden" name="jurusan" readonly class="form-control">
                                    </div>
                                </div>

                                <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label"><b>Waktu Pengisian :</b></label>
                                </div>

                                <!-- semester -->
                                <div class="position-relative row form-group">
                                    <label class="col-sm-3 col-form-label">Semester</label>
                                            <?php 
                                                $checked = "checked";
                                            ?>
                                            <div class="col-sm-3">
                                                <input type="radio" id="ganjil" name="semester" value="Ganjil" required <?php echo $komponen->semester == "Ganjil" ? $checked : "" ?>>
                                                <label for="ganjil">Ganjil</label><br>
                                                <input type="radio" id="genap" name="semester" value="Genap"  required <?php echo $komponen->semester == "Genap" ? $checked : "" ?>>
                                                <label for="genap">Genap</label><br>
                                            </div>
                                </div>

                                 <!-- thn akademik -->
                                 <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">Tahun Akademik</label>
                                            <div class="col-sm-3">
                                            <?php 
                                                $year = date("Y");
                                                $yearb = date("Y")-1;
                                                $yeara = date("Y")+1;
                                                $selected = "selected";

                                            ?>
                                                <select name="tahun_akademik" id="tahun_akademik" class=" form-control" required>
                                                    <option value="">[Tahun Akademik]</option>
                                                    <option value="<?php echo "$yearb/$year"?>" <?php echo $komponen->tahun_akademik == "$yearb/$year" ? $selected : "" ?> ><?php echo "$yearb/$year"?></option>
                                                    <option value="<?php echo "$year/$yeara"?>"  <?php echo $komponen->tahun_akademik == "$year/$yeara" ? $selected : "" ?>><?php echo "$year/$yeara"?></option>
                                                </select>
                                            </div>
                                    </div>

                                    <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label"><b>Seminar dan TA :</b></label>
                                    </div>
                                    <!-- Jenis -->
                                    <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">Jenis</label>
                                            <div class="col-sm-3">
                                                <input value="<?php echo $komponen->jenis?>" type = "text" name="jenis" readonly class="form-control">
                                            </div>
                                    </div>

                                     <!-- Tipe -->
                                     <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">Seminar</label>
                                            <div class="col-sm-3">
                                                <input value="<?php echo $komponen->tipe?>" type = "text" name="tipe" readonly class="form-control">
                                            </div>
                                    </div>
                                    
                                    
                                     <!-- komponen nilai -->
                                     <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label"><b>Komponen Nilai : </b></label>
                                            <div class="col-sm-8">
                                            <h6><b>Ujian/Presentasi</b></h6>                                     
                                            <ul class="container2">
                                                <li><input type="text" name="ujian_komponen[]" size="40" placeholder = "Aspek yang dinilai">&nbsp;&nbsp;<input type="number" name="ujian_nilai[]"placeholder = "%" min=0 max=100>&nbsp;&nbsp; <label class="btn btn-primary add_form_field_sub">+</label></li>
                                            </ul>
                                            <br>
                                            <h6><b>Tugas Akhir/Skripsi/Tesis/Disertasi</b></h6>                                     
                                            <ul class="container3">
                                                <li><input type="text" name="skripsi_komponen[]" size="40" placeholder = "Aspek yang dinilai">&nbsp;&nbsp;<input type="number" name="skripsi_nilai[]"placeholder = "%" min=0 max=100>&nbsp;&nbsp; <label class="btn btn-primary add_form_field_sub2">+</label></li>
                                            </ul>

                                            </div>                                                     
                                    </div>

                                    

                                        <!-- <div class="position-relative row form-group">
                                                <label class="col-sm-3 col-form-label"><b>Komponen Nilai</b></label>
                                                <div class="col-sm-8">
                                                <h5><b>Ujian/Presentasi</h5>                                     
                                                <ul class="container4">
                                                    <li><input type="text" name="ujian_komponen_kompre[]" size="40" placeholder = "Aspek yang dinilai">&nbsp;&nbsp;<input type="hidden" value = "0" name="ujian_nilai_kompre[]"placeholder = "%" min=0 max=100>&nbsp;&nbsp; <label class="btn btn-primary add_form_field_sub_kompre">+</label></li>
                                                </ul>
                                                <br>
                                                <h5><b>Tugas Akhir/Skripsi/Tesis/Disertasi</h5>                                     
                                                <ul class="container5">
                                                    <li><input type="text" name="skripsi_komponen_kompre[]" size="40" placeholder = "Aspek yang dinilai">&nbsp;&nbsp;<input type="hidden" value = "0" name="skripsi_nilai_kompre[]"placeholder = "%" min=0 max=100>&nbsp;&nbsp; <label class="btn btn-primary add_form_field_sub2_kompre">+</label></li>
                                                </ul>

                                                </div>                                                  
                                        </div> -->

                                   


                                    <div class="position-relative row form-group">
                                            <label class="col-sm-6 col-form-label"><b>Bobot Penilaian Pembimbing / Penguji :</b></label>
                                    </div>
                                     
                                    <?php if($komponen->jenis == "Skripsi") { 
                                         $bobot = (explode("#","$komponen->bobot"));
                                         $pb2 = (explode("-",$bobot[0])); 
                                         $pb1 = (explode("-",$bobot[1]));        
                                    ?>            
                                        <div class="row">  
                                        <div class="col-sm-6">
                                                <label class=""><b>2 Pembimbing :</b></label>
                                        </div>  

                                        <div class="col-sm-6">
                                            <label class=""><b>1 Pembimbing :</b></label>
                                        </div>  
                                     </div>

                                     <div class="row"> 
                                        <div class="col-sm-2">
                                                <label class="">Pembimbing 1</label>
                                                
                                        </div>
                                        <div class="col-sm-2">
                                                    <input value="<?php echo $pb2[0]?>" type = "number"name="skripsi_pb1_1" class=" form-control" min = 0 max = 100 placeholder = "%">
                                        </div>
                                        <div class="col-sm-2">
                                                   
                                        </div>

                                        <div class="col-sm-2">
                                            <label class="">Pembimbing 1</label>
                                            
                                        </div>

                                        <div class="col-sm-2">
                                                <input value="<?php echo $pb1[0]?>" type = "number" name="skripsi_pb1_2" class=" form-control" min = 0 max = 100 placeholder = "%">
                                        </div>
                                     </div>
                                    <br>
                                     <div class="row">
                                        <div class="col-sm-2">
                                            <label class="">Pembimbing 2</label>
                                                    
                                            </div>
                                            <div class="col-sm-2">
                                                        <input value="<?php echo $pb2[1]?>" type = "number" name="skripsi_pb2_1" class=" form-control" min = 0 max = 100 placeholder = "%">
                                            </div>
                                            <div class="col-sm-2">
                                                    
                                            </div>

                                            <div class="col-sm-2">
                                                <label class="">Penguji 1</label>
                                                
                                            </div>

                                            <div class="col-sm-2">
                                                    <input value="<?php echo $pb1[1]?>" type = "number" name="skripsi_ps1_2" class=" form-control" min = 0 max = 100 placeholder = "%">
                                            </div>
                                     </div>
                                     <br>
                                     <div class="row" >
                                        <div class="col-sm-2">
                                            <label class="">Penguji 1</label>
                                                    
                                            </div>
                                            <div class="col-sm-2">
                                                        <input value="<?php echo $pb2[2]?>" type = "number" name="skripsi_ps1_1" class=" form-control" min = 0 max = 100 placeholder = "%">
                                            </div>
                                            <div class="col-sm-2">
                                                    
                                            </div>

                                            <div class="col-sm-2">
                                                <label class="">Penguji 2</label>
                                                
                                            </div>

                                            <div class="col-sm-2">
                                                    <input value="<?php echo $pb1[2]?>" type = "number" name="skripsi_ps2_2" class=" form-control" min = 0 max = 100 placeholder = "%">
                                            </div>
                                     </div>
                                        
                                    <?php } elseif($komponen->jenis == "Tugas Akhir") { 
                                         $bobot = (explode("-","$komponen->bobot")); 
                                        ?>    
                                         <div class="row"> 
                                        <div class="col-sm-2">
                                                <label class="">Pembimbing</label>
                                                
                                        </div>
                                        <div class="col-sm-2">
                                                    <input value="<?php echo $bobot[0]?>" type = "number" name="ta_pb1" class=" form-control" min = 0 max = 100 placeholder = "%">
                                        </div>
                                        <div class="col-sm-2">
                                                   
                                        </div>
                                     </div>
                                    <br>
                                     <div class="row">
                                        <div class="col-sm-2">
                                            <label class="">Pembahas</label>
                                                    
                                            </div>
                                            <div class="col-sm-2">
                                                        <input value="<?php echo $bobot[1]?>" type = "number" name="ta_ps1" class=" form-control" min = 0 max = 100 placeholder = "%">
                                            </div>
                                            <div class="col-sm-2">
                                                    
                                            </div>
                                     </div>
                                                   
                                        </div>

                                    <?php } elseif($komponen->jenis == "Tesis") { 
                                        $bobot = (explode("-","$komponen->bobot")); 
                                    ?>
                                        <div class="row"> 
                                            <div class="col-sm-2">
                                                    <label class="">Pembimbing 1</label>
                                                    
                                            </div>
                                            <div class="col-sm-2">
                                                        <input value="<?php echo $bobot[0]?>" type = "number" name="tesis_pb1" class=" form-control" min = 0 max = 100 placeholder = "%">
                                            </div>
                                            <div class="col-sm-2">
                                                    
                                            </div>

                                            <div class="col-sm-2">
                                                <label class="">Penguji 1</label>
                                                
                                            </div>

                                            <div class="col-sm-2">
                                                    <input value="<?php echo $bobot[3]?>" type = "number" name="tesis_ps1" class=" form-control" min = 0 max = 100 placeholder = "%">
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <label class="">Pembimbing 2</label>
                                                        
                                                </div>
                                                <div class="col-sm-2">
                                                            <input value="<?php echo $bobot[1]?>" type = "number" name="tesis_pb2" class=" form-control" min = 0 max = 100 placeholder = "%">
                                                </div>
                                                <div class="col-sm-2">
                                                        
                                                </div>

                                                <div class="col-sm-2">
                                                    <label class="">Penguji 2</label>
                                                    
                                                </div>

                                                <div class="col-sm-2">
                                                        <input value="<?php echo $bobot[4]?>" type = "number" name="tesis_ps2" class=" form-control" min = 0 max = 100 placeholder = "%">
                                                </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <label class="">Pembimbing 3</label>
                                                        
                                                </div>
                                                <div class="col-sm-2">
                                                            <input value="<?php echo $bobot[2]?>" type = "number" name="tesis_pb3" class=" form-control" min = 0 max = 100 placeholder = "%">
                                                </div>
                                                <div class="col-sm-2">
                                                        
                                                </div>

                                                <div class="col-sm-2">
                                                    <label class="">Penguji 3</label>
                                                    
                                                </div>

                                                <div class="col-sm-2">
                                                        <input value="<?php echo $bobot[5]?>" type = "number"  name="tesis_ps3" class=" form-control" min = 0 max = 100 placeholder = "%">
                                                </div>
                                        </div>


                                    <?php } elseif($komponen->jenis == "Disertasi") { 
                                        $bobot = (explode("-","$komponen->bobot")); ?>
                                         <div class="row"> 
                                            <div class="col-sm-2">
                                                    <label class="">Pembimbing 1</label>
                                                    
                                            </div>
                                            <div class="col-sm-2">
                                                        <input value="<?php echo $bobot[0]?>" type = "number" name="disertasi_pb1" class=" form-control" min = 0 max = 100 placeholder = "%">
                                            </div>
                                            <div class="col-sm-2">
                                                    
                                            </div>

                                            <div class="col-sm-2">
                                                <label class="">Penguji 1</label>
                                                
                                            </div>

                                            <div class="col-sm-2">
                                                    <input value="<?php echo $bobot[3]?>" type = "number" name="disertasi_ps1" class=" form-control" min = 0 max = 100 placeholder = "%">
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <label class="">Pembimbing 2</label>
                                                        
                                                </div>
                                                <div class="col-sm-2">
                                                            <input value="<?php echo $bobot[1]?>" type = "number" name="disertasi_pb2" class=" form-control" min = 0 max = 100 placeholder = "%">
                                                </div>
                                                <div class="col-sm-2">
                                                        
                                                </div>

                                                <div class="col-sm-2">
                                                    <label class="">Penguji 2</label>
                                                    
                                                </div>

                                                <div class="col-sm-2">
                                                        <input value="<?php echo $bobot[4]?>" type = "number" name="disertasi_ps2" class=" form-control" min = 0 max = 100 placeholder = "%">
                                                </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <label class="">Pembimbing 3</label>
                                                        
                                                </div>
                                                <div class="col-sm-2">
                                                            <input value="<?php echo $bobot[2]?>" type = "number" name="disertasi_pb3" class=" form-control" min = 0 max = 100 placeholder = "%">
                                                </div>
                                                <div class="col-sm-2">
                                                        
                                                </div>

                                                <div class="col-sm-2">
                                                    <label class="">Penguji 3</label>
                                                    
                                                </div>

                                                <div class="col-sm-2">
                                                        <input value="<?php echo $bobot[5]?>" type = "number"  name="disertasi_ps3" class=" form-control" min = 0 max = 100 placeholder = "%">
                                                </div>
                                        </div>


                                    <?php } ?>    
                                   
                                     <br>
                                    <div class="position-relative row form-group">
                                            <div class="col-sm-9 offset-sm-3">
                                            <button value="<?php if($this->input->get('aksi') == "ubah") echo "ubah"; ?>" type="submit" class="btn-shadow btn btn-info">
                                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fa fa-save fa-w-20"></i>
                                            </span>
                                            <?php if($this->input->get('aksi') == "ubah") echo "Ubah"; else echo "Simpan" ?> Data
                                        </button>
                                            </div>
                                    </div>

                                  
                                
                                </form>



                    

</div>
<script src="<?php echo site_url("assets/scripts/jquery_3.4.1_jquery.min.js") ?>"></script>
<script src="<?php echo site_url("assets/scripts/select2.full.js") ?>"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
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
 $(document).ready(function() {
    var max_fields = 10;
    var wrapper = $(".container3");
    var wrapper_sub = $(".container2");
    var add_button = $(".add_form_field_sub2");
    var add_button_sub = $(".add_form_field_sub");

    var x = 1;

    $(add_button).click(function(e) {
        e.preventDefault();
        if (x < max_fields) {
            x++;
            $(wrapper).append('<li><input type="text" name="skripsi_komponen[]" size="40" placeholder = "Aspek yang dinilai">&nbsp;&nbsp;<input type="number" name="skripsi_nilai[]"placeholder = "%" min=0 max=100>&nbsp;&nbsp; <label class="btn btn-danger deletes" >-</label></li>'); //add input box
        } else {
            alert('You Reached the limits')
        }
    });

    $(add_button_sub).click(function(e) {
        e.preventDefault();
        if (x < max_fields) {
            x++;
            $(wrapper_sub).append('<li><input type="text" name="ujian_komponen[]" size="40" placeholder = "Aspek yang dinilai">&nbsp;&nbsp;<input type="number" name="ujian_nilai[]" placeholder = "%" min=0 max=100>&nbsp;&nbsp; <label class="btn btn-danger deletes" >-</label></li>'); //add input box
        } else {
            alert('You Reached the limits')
        }
    });


    $(wrapper_sub).on("click", ".deletes", function(e) {
        e.preventDefault();
        $(this).parent('li').remove();
        x--;
    })

    $(wrapper).on("click", ".deletes", function(e) {
        e.preventDefault();
        $(this).parent('li').remove();
        x--;
    })
});  
</script>

<script>
$(document).ready(function() {
    var max_fields = 10;
    var wrapper_kompre = $(".container4");
    var wrapper_sub_kompre = $(".container5");
    var add_button = $(".add_form_field_sub2_kompre");
    var add_button_sub = $(".add_form_field_sub_kompre");

    var x = 1;

    $(add_button_sub).click(function(e) {
        e.preventDefault();
        if (x < max_fields) {
            x++;
            $(wrapper_kompre).append('<li><input type="text" name="skripsi_komponen_kompre[]" size="40" placeholder = "Aspek yang dinilai">&nbsp;&nbsp;<input type="hidden" value = "0" name="skripsi_nilai_kompre[]"placeholder = "%" min=0 max=100>&nbsp;&nbsp; <label class="btn btn-danger deletes" >-</label></li>'); //add input box
        } else {
            alert('You Reached the limits')
        }
    });

    $(add_button).click(function(e) {
        e.preventDefault();
        if (x < max_fields) {
            x++;
            $(wrapper_sub_kompre).append('<li><input type="text" name="ujian_komponen_kompre[]" size="40" placeholder = "Aspek yang dinilai">&nbsp;&nbsp;<input type="hidden" value = "0" name="ujian_nilai_kompre[]" size="10" placeholder = "%" min=0 max=100>&nbsp;&nbsp; <label class="btn btn-danger deletes" >-</label></li>'); //add input box
        } else {
            alert('You Reached the limits')
        }
    });


    $(wrapper_sub_kompre).on("click", ".deletes", function(e) {
        e.preventDefault();
        $(this).parent('li').remove();
        x--;
    })

    $(wrapper_kompre).on("click", ".deletes", function(e) {
        e.preventDefault();
        $(this).parent('li').remove();
        x--;
    })
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

</script>