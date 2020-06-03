

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-file icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <div>Pengajuan Tema Penelitian
                                        <div class="page-title-subheading">Jangan lupa untuk mengunggah berkas pendukung.
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
                                <div class="card-header">Form Pengajuan Tema Penelitian</div>
                                <div class="card-body">
                                <?php if(empty($status_ta) || ($this->input->get('aksi') == 'ubah' && !empty($this->input->get('id')))) { ?>  
                                    <form method="post" action="<?php echo site_url('#') ?>" >
                                        <!-- <?php 
                                        echo "<pre>";
                                        print_r($ta);
                                        echo $ta->npm;
                                        ?> -->

                                        <div class="position-relative row form-group">
                                            <label for="npm" class="col-sm-3 col-form-label">Npm</label>
                                            <div class="col-sm-3">
                                                <input value="<?php echo $biodata->npm ?>" readonly required name="npm" class="form-control input-mask-trigger" >
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label for="nama" class="col-sm-3 col-form-label">Nama</label>
                                            <div class="col-sm-9">
                                                <input value="<?php echo $this->user_model->get_mahasiswa_name($biodata->npm); ?>" readonly required name="nama" class="form-control input-mask-trigger" >
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-3 col-form-label">Judul</label>
                                            <div class="col-sm-9">
                                                <?php if($ta->judul_approve == '1'){ ?>
                                                    <textarea required name="judul" class="form-control" placeholder="Ketik judul utama penelitian di sini..." readonly><?php echo $ta->judul1 ?></textarea>
                                                <?php } elseif($ta->judul_approve == '2'){?>
                                                    <textarea required name="judul" class="form-control" placeholder="Ketik judul utama penelitian di sini..." readonly><?php echo $ta->judul2 ?></textarea>
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label for="jenis" class="col-sm-3 col-form-label">Jenis</label>
                                            <div class="col-sm-9">
                                                <select required name="jenis" class=" form-control">
                                                <option value="">-- Jenis Seminar --</option>
                                                    <option value="Seminar Tugas Akhir">Seminar Tugas Akhir</option>
                                                    <option value="Seminar Usul">Seminar Usul</option>
                                                    <option value="Seminar Hasil">Seminar Hasil</option>
                                                    <option value="Sidang Komprehensif">Sidang Komprehensif</option>
                                                </select>  
                                            </div>
                                        </div>

                                    
                                    </form>
                                                <?php } else {
                                                    echo '<div class="alert alert-danger fade show" role="alert">Masih terdapat <b>Pengajuan Tema</b> dengan status Aktif.</div>
                                                    ';
                                                } ?>
                                </div>
                            </div>
                            </div> <!-- col-md -->

                            <!--
                            <div class="col-md-5">
                                <div class="main-card mb-3 card">
                                    <div class="card-header">Berkas Lampiran</div>
                                    <div class="card-body">
                                    <div class="table-responsive">
                                                    <table class="mb-0 table table-striped">
                                                        <thead>
                                                        <tr>
                                                            <th style="width: 10%;">#</th>
                                                            <th style="width: 60%;">Berkas</th>
                                                            <th style="width: 30%;">Aksi</th>
            
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td>1</td>
                                                            <td>KRS</td>
                                                            <td></td>
                                                            
                                                        </tr>
                                                        
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="divider"></div>
                                        <form method="post" action="<?php echo site_url('mahasiswa/ubah-data-akun') ?>" enctype="multipart/form-data" >
                                            <div class="position-relative row form-group">
                                                <div class="col-sm-12">
                                                    <select name="jalur_masuk" class=" form-control">
                                                    <option>-- Pilih Jenis Berkas --</option>
                                                    <?php
                                                    $list = $this->parameter_model->select_jenis_berkas();
                                                    foreach ($list as $row) {
                                                        $select = "";
                                                        //if($biodata->jalur_masuk == $row->id_jalur_masuk) $select = "selected";
                                                        
                                                        echo "<option ".$select." value='".$row->id_jenis."'>".$row->nama."</option>";
                                                        }
                                                    ?>

                                                    </select>
                                                    
                                                
                                                </div>
                                            </div>
                                                
                                                <div class="position-relative row form-group">
                                                <div class="col-sm-12"><input oninvalid="this.setCustomValidity('Anda belum memilih berkas!')" oninput="this.setCustomValidity('')" accept=".jpg, .jpeg, .png" name="file" id="file" type="file" class="form-control-file">
                                                        </div>
                                                </div>

                                                               
                                                
                                                <div class="position-relative row form-group">
                                                    <div class="col-sm-12">
                                                    <button type="submit" class="btn-shadow btn btn-success">
                                                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                        <i class="fa fa-plus fa-w-20"></i>
                                                    </span>
                                                    Tambah Berkas
                                                </button>
                                                    </div>
                                                </div>
                                            </form>

                                            

                                               
                                    
                                    </div>
                                </div>
                            </div>

                            -->
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

                        