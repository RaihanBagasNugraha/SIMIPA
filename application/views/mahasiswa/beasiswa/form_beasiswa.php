

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-file icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <div>Data Beasiswa
                                        <div class="page-title-subheading">Silahkan isi Data.
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
                        $seg = $this->uri->segment(3);
                        $cek = $this->layanan_model->cek_beasiswa_by_id($seg);
                        ?>
                        <div class="row">
                        <div class="col-md-12">
                         <div class="main-card mb-3 card">
                                <div class="card-header">Form Daftar Beasiswa</div>
                                <div class="card-body">
                                <?php if(!empty($cek)){ ?>
                                    <form method="post" action="<?php echo site_url('mahasiswa/simpan_beasiswa') ?>" >
                                        <?php if($this->input->get("aksi") == 'ubah'){ ?>
                                            <input type="hidden" name="id_beasiswa_mhs" value="<?php echo $data_bea['id'] ?>" class='form-control' id="">
                                        <?php } ?>

                                        <input type="hidden" required name="id_beasiswa" value="<?php echo $beasiswa->id ?>" class='form-control' id="">
                                        <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-3 col-form-label"><b>Nama Beasiswa</b></label>
                                            <div class="col-sm-9">
                                                <input type="text" required name="nama" value="<?php echo $beasiswa->nama ?>" readonly placeholder='cth: Beasiswa Djarum' class='form-control' id="">
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-3 col-form-label"><b>Tahun Beasiswa</b></label>
                                            <div class="col-sm-9">
                                                <input type="text" required name="tahun" value="<?php echo $beasiswa->tahun ?>" readonly placeholder='cth: Beasiswa Djarum' class='form-control' id="">
                                            </div>
                                        </div>
                                        
                                        <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-3 col-form-label"><b>IPK</b></label>
                                            <div class="col-sm-3">
                                                <input value="<?php echo $data_bea['ipk'] ?>" required name="ipk" class="form-control input-mask-trigger" placeholder='cth: 3.5' data-inputmask="'mask': '9.99'" im-insert="true">
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-3 col-form-label"><b>Tahun Akademik</b></label>
                                            <div class="col-sm-9">
                                                <input type="text" required name="tahun_akademik" value="<?php echo $beasiswa->tahun_akademik ?>" readonly placeholder='cth: Beasiswa Djarum' class='form-control' id="">
                                            </div>
                                        </div>

                                        

                                        <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-3 col-form-label"><b>Nomor Rekening</b></label>
                                            <div class="col-sm-6">
                                                <input type="text" required name="no_rek" value="<?php echo $data_bea['rekening'] ?>" placeholder='Nomor Rekening Aktif' class='form-control' id="">
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-3 col-form-label"><b>Status Menikah</b></label>
                                            <div class="col-sm-3">
                                                <select name="menikah" required class = 'form-control' id="">
                                                    <option value="">-- Status Menikah --</option>
                                                    <option value="Menikah" <?php echo $data_bea['status_menikah'] == 'Menikah' ? 'selected' : ""; ?>>Menikah</option>
                                                    <option value="Belum Menikah" <?php echo $data_bea['status_menikah'] == 'Belum Menikah' ? 'selected' : ""; ?>>Belum Menikah</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-3 col-form-label"><b>Nama Orang Tua/Wali</b></label>
                                            <div class="col-sm-9">
                                                <input type="text" required name="ortu" value="<?php echo $data_bea['nama_ortu'] ?>" placeholder='Nama Orang Tua / Wali' class='form-control' id="">
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-3 col-form-label"><b>Pekerjaan Orang Tua/Wali</b></label>
                                            <div class="col-sm-9">
                                                <input type="text" required name="pekerjaan" value="<?php echo $data_bea['pekerjaan_ortu'] ?>" placeholder='cth: Wiraswasta' class='form-control' id="">
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-3 col-form-label"><b>Penghasilan Orang Tua/Wali</b></label>
                                            <div class="col-sm-6">
                                                <input type="text" required name="penghasilan" value="<?php echo $data_bea['penghasilan_ortu'] ?>" placeholder='Penghasilan Per Bulan cth: Rp. 1.500.000,-' class='form-control' id="">
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-3 col-form-label"><b>Jumlah Tanggungan Orang Tua/Wali</b></label>
                                            <div class="col-sm-3">
                                                <input type="text" required name="tanggungan" value="<?php echo $data_bea['tanggungan_ortu'] ?>" placeholder='cth: 2' class='form-control' id="">
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-3 col-form-label"><b>Alamat Orang Tua/Wali</b></label>
                                            <div class="col-sm-9">
                                                <textarea name="alamat" class='form-control' required value="" placeholder ='Alamat Orang Tua / Wali'><?php echo $data_bea['alamat_ortu'] ?></textarea>
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-3 col-form-label"><b>Nama Kakak/Adik</b></label>
                                            <div class="col-sm-9">
                                                <input type="text" required name="nama_kakak" value="<?php echo $data_bea['nama_saudara'] ?>" placeholder='Nama Kakak/Adik Yang Sedang Meneirma Beasiswa di Unila atau Tanda: - (Jika Tidak Ada)' class='form-control' id="">
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-3 col-form-label"><b>Jenis Beasiswa</b></label>
                                            <div class="col-sm-9">
                                                <input type="text" required name="jenis_kakak" value="<?php echo $data_bea['beasiswa_saudara'] ?>" placeholder='Jenis Beasiswa Kakak/Adik Yang Sedang Meneirma Beasiswa di Unila atau Tanda: - (Jika Tidak Ada)' class='form-control' id="">
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-3 col-form-label"><b>Fakultas Kakak/Adik</b></label>
                                            <div class="col-sm-9">
                                                <input type="text" required name="fakultas_kakak" value="<?php echo $data_bea['fakultas_saudara'] ?>" placeholder='Fakultas Kakak/Adik Yang Sedang Meneirma Beasiswa di Unila atau Tanda: - (Jika Tidak Ada)' class='form-control' id="">
                                            </div>
                                        </div>

                                        <div class="position-relative row form-group"><label for="ttd" class="col-sm-3 col-form-label"><b>Tanda Tangan Digital</b></label>
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
                                            <?php if($this->input->get('aksi') == "ubah") echo "Ubah"; else echo "Simpan" ?> Data
                                        </button>
                                            </div>
                                        </div>
                                    </form>
                                <?php } else{ echo '<div class="alert alert-danger fade show" role="alert">Beasiswa Tidak Ditemukan</div>
                                                    ';}?>    
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

                        