
<div class="app-page-title">
                        <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-file icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <div>Komponen Nilai
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
                                <div class="card-header">Komponen Nilai</div>
                                <div class="card-body">
                                 
                                <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label"><b>Tahun Akademik</b></label>
                                            <div class="col-sm-3">
                                            <input value="<?php echo $komponen->tahun_akademik?>" type = "text" name="thn" readonly class="form-control">
                                            </div>
                                </div>

                                <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label"><b>Semester</b></label>
                                            <div class="col-sm-3">
                                            <input value="<?php echo $komponen->semester?>" type = "text" name="smt" readonly class="form-control">
                                            </div>
                                </div>


                                <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label"><b>Jurusan</b></label>
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
                                            <input value="<?php echo $jrs?>" type = "text" name="jrs" readonly class="form-control">
                                            </div>
                                </div>

                                <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label"><b>Jenis</b></label>
                                            <div class="col-sm-3">
                                            <input value="<?php echo $komponen->jenis?>" type = "text" name="jns" readonly class="form-control">
                                            </div>
                                </div>

                                <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label"><b>Seminar</b></label>
                                            <div class="col-sm-3">
                                            <input value="<?php echo $komponen->tipe?>" type = "text" name="smr" readonly class="form-control">
                                            </div>
                                </div>

                                <table class="mb-0 table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Kategori</th>
                                            <th>Aspek Yang Dinilai</th>
                                            <th>Persentase</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($meta as $row) {?>
                                        <tr>
                                            <td><b><?php echo $row->unsur ?></b></td>
                                            <td><?php echo $row->attribut ?></td>
                                            <td><?php echo $row->persentase ?>%</td>
                                        </tr>
                                    <?php } ?>    
                                    </tbody>

                                </table>
                                <br>
                                <div class="position-relative row form-group">
                                        <label class="col-sm-3 col-form-label"><b>Bobot Penilaian :</b></label>
                                </div>

                                <?php 
                                    if ($komponen->jenis == "Tugas Akhir"){

                                    $bobot = (explode("-","$komponen->bobot"));    
                                ?>
                                
                                <table class="mb-0 table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Komisi</th>
                                            <th>Bobot Penilaian</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><b>Pembimbing</b></td>
                                            <td><?php echo $bobot[0] ?>%</td>
                                        </tr>  
                                        <tr>
                                            <td><b>Pembahas</b></td>
                                            <td><?php echo $bobot[1] ?>%</td>
                                        </tr>  
                                    </tbody>

                                </table>
                                <?php
                                    }
                               
                                    elseif ($komponen->jenis == "Skripsi"){

                                    $bobot = (explode("#","$komponen->bobot"));
                                    $pb2 = (explode("-",$bobot[0])); 
                                    $pb1 = (explode("-",$bobot[1]));    
                                ?>
                                <b>2 Pembimbing :</b>
                                <table class="mb-0 table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Komisi</th>
                                            <th>Bobot Penilaian</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><b>Pembimbing 1</b></td>
                                            <td><?php echo $pb2[0] ?>%</td>
                                        </tr>  
                                        <tr>
                                            <td><b>Pembimbing 2</b></td>
                                            <td><?php echo $pb2[1] ?>%</td>
                                        </tr> 
                                        <tr>
                                            <td><b>Penguji 1</b></td>
                                            <td><?php echo $pb2[2] ?>%</td>
                                        </tr>  
                                    </tbody>

                                </table>

                                <br><br>
                                <b>1 Pembimbing :</b>
                                <table class="mb-0 table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Komisi</th>
                                            <th>Bobot Penilaian</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><b>Pembimbing 1</b></td>
                                            <td><?php echo $pb1[0] ?>%</td>
                                        </tr>  
                                        <tr>
                                            <td><b>Penguji 1</b></td>
                                            <td><?php echo $pb1[1] ?>%</td>
                                        </tr> 
                                        <tr>
                                            <td><b>Penguji 2</b></td>
                                            <td><?php echo $pb1[2] ?>%</td>
                                        </tr>  
                                    </tbody>

                                </table>
                                <?php
                                    }
                                    elseif ($komponen->jenis == "Tesis"){
                                    $bobot = (explode("-","$komponen->bobot")); 
                                ?>
                                <table class="mb-0 table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Komisi</th>
                                            <th>Bobot Penilaian</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><b>Pembimbing 1</b></td>
                                            <td><?php echo $bobot[0] ?>%</td>
                                        </tr>  
                                        <tr>
                                            <td><b>Pembimbing 2</b></td>
                                            <td><?php echo $bobot[1] ?>%</td>
                                        </tr>  
                                        <tr>
                                            <td><b>Pembimbing 3</b></td>
                                            <td><?php echo $bobot[2] ?>%</td>
                                        </tr>  
                                        <tr>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td><b>Penguji 1</b></td>
                                            <td><?php echo $bobot[3] ?>%</td>
                                        </tr>  
                                        <tr>
                                            <td><b>Penguji 2</b></td>
                                            <td><?php echo $bobot[4] ?>%</td>
                                        </tr>  
                                        <tr>
                                            <td><b>Penguji 3</b></td>
                                            <td><?php echo $bobot[5] ?>%</td>
                                        </tr>  
                                    </tbody>

                                </table>

                                <?php
                                 }
                                 elseif ($komponen->jenis == "Disertasi"){
                                    $bobot = (explode("-","$komponen->bobot")); 
                                ?>
                                <table class="mb-0 table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Komisi</th>
                                            <th>Bobot Penilaian</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><b>Pembimbing 1</b></td>
                                            <td><?php echo $bobot[0] ?>%</td>
                                        </tr>  
                                        <tr>
                                            <td><b>Pembimbing 2</b></td>
                                            <td><?php echo $bobot[1] ?>%</td>
                                        </tr>  
                                        <tr>
                                            <td><b>Pembimbing 3</b></td>
                                            <td><?php echo $bobot[2] ?>%</td>
                                        </tr>  
                                        <tr>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td><b>Penguji 1</b></td>
                                            <td><?php echo $bobot[3] ?>%</td>
                                        </tr>  
                                        <tr>
                                            <td><b>Penguji 2</b></td>
                                            <td><?php echo $bobot[4] ?>%</td>
                                        </tr>  
                                        <tr>
                                            <td><b>Penguji 3</b></td>
                                            <td><?php echo $bobot[5] ?>%</td>
                                        </tr>  
                                    </tbody>

                                </table>
                                 <?php } ?>



                    

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