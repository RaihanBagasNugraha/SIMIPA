

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-file icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <div>Tambah Periode KP/PKL
                                        <div class="page-title-subheading">
                                        </div>
                                    </div>
                                </div>
                                <?php $select = "selected"; ?>
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
                                <div class="card-header">Form Tambah KP/PKL</div>
                                <div class="card-body">
                                <!-- <?php if(empty($status_ta) || ($this->input->get('aksi') == 'ubah' && !empty($this->input->get('id')))) { ?>   -->
                                    <form method="post" action="<?php echo site_url('dosen/struktural/pkl/add-pkl/form/save') ?>" >
                                        <?php $jurusan = $this->user_model->get_dosen_jur($this->session->userdata('userId'));?>
                                        <input type="hidden" name="jurusan" value="<?php echo $jurusan->id_jurusan; ?>"/> 
                                        <input type="hidden" name="aksi" value=""/> 
                                        <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-3 col-form-label">Tahun</label>
                                            <div class="col-sm-9">
                                                <select required name="tahun" class= "form-control">
                                                <?php $tahun = date("Y"); ?>
                                                    <option value = "">-- Pilih Tahun --</option>
                                                    <option value = "<?php echo $tahun?>"><?php echo $tahun; ?></option>
                                                    <option value = "<?php echo $tahun+1; ?>"><?php echo $tahun+1;?></option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-3 col-form-label">Periode</label>
                                            <div class="col-sm-9">
                                                <select required name="periode" class= "form-control">
                                                    <option value = "">-- Pilih Periode --</option>
                                                    <option value = "1">1</option>
                                                    <option value = "2">2</option>
                                                </select>
                                            </div>
                                        </div>
                                        <br>
                                        <h6><b>Timeline KP/PKL</b><span style="color:red">*</span></h6>

                                        <!-- 1 -->
                                        <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-12 col-form-label"><b>1. Pendaftaran dan Pengiriman Surat permohonan KP</b></label>
                                            <br>
                                            <label for="prodi" class="col-sm-3 col-form-label">&emsp;Tanggal Mulai</label>
                                            <div class="col-sm-1">:</div>
                                            <div class="col-sm-8">
                                               <input name="1_start" type="date" class="form-control">
                                            </div>
                                            <label for="prodi" class="col-sm-3 col-form-label">&emsp;Tanggal Berakhir</label>
                                            <div class="col-sm-1">:</div>
                                            <div class="col-sm-8">
                                               <input name="1_end" type="date" class="form-control">
                                            </div>
                                        </div>

                                        <!-- 2 -->
                                        <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-12 col-form-label"><b>2. Pembekalan KP/PKL</b></label>
                                            <br>
                                            <label for="prodi" class="col-sm-3 col-form-label">&emsp;Tanggal Mulai</label>
                                            <div class="col-sm-1">:</div>
                                            <div class="col-sm-8">
                                               <input name="2_start" type="date" class="form-control">
                                               <input name="2_end" type="hidden" value=NULL class="form-control">
                                            </div>
                                        </div>

                                         <!-- 3 -->
                                         <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-12 col-form-label"><b>3. Mulai KP/PKL</b></label>
                                            <br>
                                            <label for="prodi" class="col-sm-3 col-form-label">&emsp;Tanggal Mulai</label>
                                            <div class="col-sm-1">:</div>
                                            <div class="col-sm-8">
                                               <input name="3_start" type="date" class="form-control">
                                               <input name="3_end" type="hidden" value=NULL class="form-control">
                                            </div>
                                        </div>

                                         <!-- 4 -->
                                         <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-12 col-form-label"><b>4. Batas akhir mengirim proposal rencana kerja</b></label>
                                            <br>
                                            <label for="prodi" class="col-sm-3 col-form-label">&emsp;Tanggal Mulai</label>
                                            <div class="col-sm-1">:</div>
                                            <div class="col-sm-8">
                                               <input name="4_start" type="date" class="form-control">
                                               <input name="4_end" type="hidden" value=NULL class="form-control">
                                            </div>
                                        </div>

                                        <!-- 5 -->
                                        <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-12 col-form-label"><b>5. Batas akhir mengirim Bab I</b></label>
                                            <br>
                                            <label for="prodi" class="col-sm-3 col-form-label">&emsp;Tanggal Mulai</label>
                                            <div class="col-sm-1">:</div>
                                            <div class="col-sm-8">
                                               <input name="5_start" type="date" class="form-control">
                                               <input name="5_end" type="hidden" value=NULL class="form-control">
                                            </div>
                                        </div>

                                        <!-- 6 -->
                                        <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-12 col-form-label"><b>6. Batas akhir mengirim Bab II</b></label>
                                            <br>
                                            <label for="prodi" class="col-sm-3 col-form-label">&emsp;Tanggal Mulai</label>
                                            <div class="col-sm-1">:</div>
                                            <div class="col-sm-8">
                                               <input name="6_start" type="date" class="form-control">
                                               <input name="6_end" type="hidden" value=NULL class="form-control">
                                            </div>
                                        </div>

                                        <!-- 7 -->
                                        <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-12 col-form-label"><b>7. Batas akhir mengirim Bab III</b></label>
                                            <br>
                                            <label for="prodi" class="col-sm-3 col-form-label">&emsp;Tanggal Mulai</label>
                                            <div class="col-sm-1">:</div>
                                            <div class="col-sm-8">
                                               <input name="7_start" type="date" class="form-control">
                                               <input name="7_end" type="hidden" value=NULL class="form-control">
                                            </div>
                                        </div>

                                        <!-- 8 -->
                                        <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-12 col-form-label"><b>8. Selesai KP/PKL</b></label>
                                            <br>
                                            <label for="prodi" class="col-sm-3 col-form-label">&emsp;Tanggal Mulai</label>
                                            <div class="col-sm-1">:</div>
                                            <div class="col-sm-8">
                                               <input name="8_start" type="date" class="form-control">
                                               <input name="8_end" type="hidden" value=NULL class="form-control">
                                            </div>
                                        </div>

                                        <!-- 9 -->
                                        <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-12 col-form-label"><b>9. Bimbingan Bab IV, V dan Seminar</b></label>
                                            <br>
                                            <label for="prodi" class="col-sm-3 col-form-label">&emsp;Tanggal Mulai</label>
                                            <div class="col-sm-1">:</div>
                                            <div class="col-sm-8">
                                               <input name="9_start" type="date" class="form-control">
                                            </div>
                                            <label for="prodi" class="col-sm-3 col-form-label">&emsp;Tanggal Berakhir</label>
                                            <div class="col-sm-1">:</div>
                                            <div class="col-sm-8">
                                               <input name="9_end" type="date" class="form-control">
                                            </div>
                                        </div>

                                        <!-- 10 -->
                                        <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-12 col-form-label"><b>10. Batas akhir mengirim file Laporan Lengkap (COVER s.d Bab V)</b></label>
                                            <br>
                                            <label for="prodi" class="col-sm-3 col-form-label">&emsp;Tanggal Mulai</label>
                                            <div class="col-sm-1">:</div>
                                            <div class="col-sm-8">
                                               <input name="10_start" type="date" class="form-control">
                                               <input name="10_end" type="hidden" value=NULL class="form-control">
                                            </div>
                                        </div>

                                        <!-- 11 -->
                                        <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-12 col-form-label"><b>11. Batas Akhir Seminar KP/PKL</b></label>
                                            <br>
                                            <label for="prodi" class="col-sm-3 col-form-label">&emsp;Tanggal Mulai</label>
                                            <div class="col-sm-1">:</div>
                                            <div class="col-sm-8">
                                               <input name="11_start" type="date" class="form-control">
                                               <input name="11_end" type="hidden" value=NULL class="form-control">
                                            </div>
                                        </div>

                                         <!-- 12 -->
                                         <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-12 col-form-label"><b>12. Batas Akhir Menyerahkan Laporan KP/PKL dalam bentuk Softcopy dan Hardcopy </b></label>
                                            <br>
                                            <label for="prodi" class="col-sm-3 col-form-label">&emsp;Tanggal Mulai</label>
                                            <div class="col-sm-1">:</div>
                                            <div class="col-sm-8">
                                               <input name="12_start" type="date" class="form-control">
                                               <input name="12_end" type="hidden" value=NULL class="form-control">
                                            </div>
                                        </div>
                                        <br>
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
                                                <!-- <?php } else {
                                                    echo '<div class="alert alert-danger fade show" role="alert">Masih terdapat <b>Pengajuan Tema</b> dengan status Aktif.</div>
                                                    ';
                                                } ?> -->
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

                        