

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-file icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <?php 
                                        $jns = $this->uri->segment(3);
                                        switch($jns){
                                            case "akademik":
                                            $layanan = "Akademik";
                                            break;
                                            case "kemahasiswaan":
                                            $layanan = "Kemahasiswaan";
                                            break;
                                            case "umum-keuangan":
                                            $layanan = "Umum dan Keuangan";
                                            break;
                                        }
                                    ?>
                                    <div>Form Layanan <?php echo $layanan ?>
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
                                <div class="card-header">Form Layanan <?php echo $layanan ?></div>
                                <div class="card-body">
                                    <form method="post" action="<?php echo site_url('#') ?>" >
                                    <input value="<?php echo $this->session->userdata('userId') ?>" type="hidden" required name="user_id">
                                        
                                        <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-3 col-form-label">Jenis Form</label>
                                            <div class="col-sm-9">
                                                <select required name="layanan" id="jenis_layanan"  class = "form-control">
                                                    <option value = "">-- Pilih Form Layanan --</option>
                                                    <?php 
                                                        $form_layanan = $this->layanan_model->select_layanan_by_bagian($layanan);
                                                        
                                                        foreach($form_layanan as $form){
                                                    ?>
                                                        <option value=<?php echo $form->id_layanan_fakultas ?>><?php echo $form->nama ?></option>
                                                    <?php } ?>    
                                                </select>
                                               
                                            </div>
                                        </div>

                                        <!-- <div class="position-relative row form-group">
                                            <label for="prodi" class="col-sm-3 col-form-label">Pembimbing Akademik</label>
                                            <div class="col-sm-9">
                                            <?php $pa = $this->user_model->get_dosen_data($biodata->dosen_pa); ?>
                                                <input value="<?php echo $pa->gelar_depan." ".$pa->name.", ".$pa->gelar_belakang;?>" type = "text" required name="pa" class="form-control" readonly > 
                                            </div>
                                        </div> -->

                                        <div id="container"></div>

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
                                            <input type="hidden" style="background-color: #efefef;" type="text" class="form-control readonly" required placeholder=" " name="ttd" id="output" value="">
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
    
    $('.field').remove();
    $('#jenis_layanan').on('change', function() {
        $('.field').remove();
        //akadmeik
        if (this.value =='1'){
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>Tujuan Program Studi</label><div class='col-sm-9'><input value='' type = 'text' placeholder ='Program Studi Tujuan' required name='tujuan' class='form-control' ></div></div>");
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>Alasan Pindah</label><div class='col-sm-9'><textarea value='' placeholder ='Alasan Pindah' required name='alasan' class='form-control'></textarea></div></div>");
        }
        else if(this.value =='2'){
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>Keterangan</label><div class='col-sm-9'><input value='' readonly type = 'text' placeholder ='-' required name='keterangan' class='form-control' ></div></div>");
        }
        else if(this.value =='3'){
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>Tema Penelitian</label><div class='col-sm-9'><a class='btn-wide mb-2 btn btn-info btn-sm' target='_blank' href='http://apps.fmipa.unila.ac.id/simipa/mahasiswa/tugas-akhir/tema'>Lihat</a></div></div>");
        }
        else if(this.value =='4'){
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>Mata Kuliah</label></div>");
            $(wrapper).append("<div class='position-relative row form-group field'><div class='col-sm-2'><input value='' type = 'text' placeholder ='Kopel' required name='kopel' class='form-control' ></div><div class='col-lg-3'><input value='' type = 'text' placeholder ='Nama Mata Kuliah' required name='nama_matkul' class='form-control' ></div><div class='col-lg-2'><select required name='semester' class = 'form-control'><option value = ''>Semester</option><option value='Ganjil'>Ganjil</option><option value='Genap'>Genap</option><option value='Pendek'>Pendek</option></select></div><div class='col-lg-2'><input value='' type = 'text' placeholder ='Tahun Ajaran' required name='ta' class='form-control' ></div><div class='col-lg-1'><input value='' type = 'text' placeholder ='SKS' required name='sks' class='form-control' ></div><div class='col-lg-2'><input value='' type = 'text' placeholder ='Keterangan' required name='ket_hapus' class='form-control' ></div></div>");
            $(wrapper).append("<div class='position-relative row form-group field'><div class='col-sm-2'><input value='' type = 'text' placeholder ='Kopel' name='kopel_2' class='form-control' ></div><div class='col-lg-3'><input value='' type = 'text' placeholder ='Nama Mata Kuliah' name='nama_matkul_2' class='form-control' ></div><div class='col-lg-2'><select name='semester_2' class = 'form-control'><option value = ''>Semester</option><option value='Ganjil'>Ganjil</option><option value='Genap'>Genap</option><option value='Pendek'>Pendek</option></select></div><div class='col-lg-2'><input value='' type = 'text' placeholder ='Tahun Ajaran' name='ta_2' class='form-control' ></div><div class='col-lg-1'><input value='' type = 'text' placeholder ='SKS' name='sks_2' class='form-control' ></div><div class='col-lg-2'><input value='' type = 'text' placeholder ='Keterangan' name='ket_hapus_2' class='form-control' ></div></div>");
            $(wrapper).append("<div class='position-relative row form-group field'><div class='col-sm-2'><input value='' type = 'text' placeholder ='Kopel' name='kopel_3' class='form-control' ></div><div class='col-lg-3'><input value='' type = 'text' placeholder ='Nama Mata Kuliah' name='nama_matkul_3' class='form-control' ></div><div class='col-lg-2'><select name='semester_3' class = 'form-control'><option value = ''>Semester</option><option value='Ganjil'>Ganjil</option><option value='Genap'>Genap</option><option value='Pendek'>Pendek</option></select></div><div class='col-lg-2'><input value='' type = 'text' placeholder ='Tahun Ajaran' name='ta_3' class='form-control' ></div><div class='col-lg-1'><input value='' type = 'text' placeholder ='SKS' name='sks_3' class='form-control' ></div><div class='col-lg-2'><input value='' type = 'text' placeholder ='Keterangan' name='ket_hapus_3' class='form-control' ></div></div>");
            $(wrapper).append("<div class='position-relative row form-group field'><div class='col-sm-2'><input value='' type = 'text' placeholder ='Kopel' name='kopel_4' class='form-control' ></div><div class='col-lg-3'><input value='' type = 'text' placeholder ='Nama Mata Kuliah' name='nama_matkul_4' class='form-control' ></div><div class='col-lg-2'><select name='semester_4' class = 'form-control'><option value = ''>Semester</option><option value='Ganjil'>Ganjil</option><option value='Genap'>Genap</option><option value='Pendek'>Pendek</option></select></div><div class='col-lg-2'><input value='' type = 'text' placeholder ='Tahun Ajaran' name='ta_4' class='form-control' ></div><div class='col-lg-1'><input value='' type = 'text' placeholder ='SKS' name='sks_4' class='form-control' ></div><div class='col-lg-2'><input value='' type = 'text' placeholder ='Keterangan' name='ket_hapus_4' class='form-control' ></div></div>");
            // var i = 1;
            // $("#add").click(function(){
            //     $(wrapper).append("<div class='position-relative row form-group field'><div class='col-sm-2'><input value='' type = 'text' placeholder ='Kopel' required name='kopel' class='form-control' ></div><div class='col-lg-3'><input value='' type = 'text' placeholder ='Nama Mata Kuliah' required name='nama_matkul' class='form-control' ></div><div class='col-lg-2'><select required name='semester' class = 'form-control'><option value = ''>Semester</option><option value='Ganjil'>Ganjil</option><option value='Genap'>Genap</option><option value='Pendek'>Pendek</option></select></div><div class='col-lg-2'><input value='' type = 'text' placeholder ='Tahun Ajaran' required name='ta' class='form-control' ></div><div class='col-lg-1'><input value='' type = 'text' placeholder ='SKS' required name='sks' class='form-control' ></div><div class='col-lg-2'><input value='' type = 'text' placeholder ='Keterangan' required name='ket_hapus' class='form-control' ></div></div>");
            //     i++;
            // });
            // $("#del").click(function(){
            //     $(".field2").remove();
            // });
        }
        else if(this.value =='5'){
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>Periode Wisuda</label><div class='col-sm-4'><input value='' type = 'text' placeholder ='ex: I (Satu)' required name='periode_wisuda' class='form-control' ></div></div>");
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>Tahun Akademik</label><div class='col-sm-4'><input value='' type = 'text' placeholder ='ex: 2020/2021' required name='ta_wisuda' class='form-control' ></div></div>");
        }
        else if(this.value =='6'){
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>Lama Cuti</label><div class='col-sm-4'><select required name='lama_cuti' class = 'form-control'><option value = '1 Semester'>1 Semester</option><option value='1 Semester' >2 Semester</option><option value='3 Semester' >3 Semester</option><option value='4 Semester'>4 Semester</option><option value='5 Semester'>5 Semester</option><option value='6 Semester'>6 Semester</option><option value='7 Semester'>7 Semester</option><option value='8 Semester'>8 Semester</option></select></div></div>");
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>Tahun Akademik</label><div class='col-sm-4'><input value='' type = 'text' placeholder ='ex: 2020/2021' required name='ta_cuti' class='form-control' ></div></div>");
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>Nama Orang Tua</label><div class='col-sm-6'><input value='' type = 'text' placeholder ='Nama Orang Tua' required name='ortu_cuti' class='form-control' ></div></div>");
        }
        else if(this.value =='7'){
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>Jumlah SKS</label><div class='col-sm-4'><input value='' type = 'text' placeholder ='ex: 145' required name='sks_perpanjang_studi' class='form-control' ></div></div>");
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>IPK</label><div class='col-sm-4'><input value='' type = 'text' placeholder ='ex: 3.5' required name='ipk_perpanjang_studi' class='form-control' ></div></div>");
        }
        else if(this.value =='8'){ //this
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>Form</label><div class='col-sm-9'><a class='btn-wide mb-2 btn btn-info btn-sm' href='#'>Unduh</a></div></div>");
        }
        else if(this.value =='9'){
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>Nama Dosen Pj. Mata Kuliah</label><div class='col-sm-6'><input value='' type = 'text' placeholder ='Nama Dosen' required name='nama_studi_terbimbing' class='form-control' ></div></div>");
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>NIP Dosen Pj. Mata Kuliah</label><div class='col-sm-6'><input value='' type = 'text' placeholder ='NIP Dosen' required name='nip_studi_terbimbing' class='form-control' ></div></div>");
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>Nama Mata Kuliah</label><div class='col-sm-6'><input value='' type = 'text' placeholder ='Nama Mata Kuliah' required name='matkul_studi_terbimbing' class='form-control' ></div></div>");
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>Kode Mata Kuliah</label><div class='col-sm-6'><input value='' type = 'text' placeholder ='Kode Mata Kuliah' required name='kode_studi_terbimbing' class='form-control' ></div></div>");
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>SKS Mata Kuliah</label><div class='col-sm-3'><input value='' type = 'text' placeholder ='ex: 3' required name='sks_studi_terbimbing' class='form-control' ></div></div>");
        }
        else if(this.value =='10'){
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>Keterangan</label><div class='col-sm-9'><input value='' readonly type = 'text' placeholder ='-' required name='keterangan' class='form-control' ></div></div>");
        }
        else if(this.value =='11'){
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>Keterangan</label><div class='col-sm-9'><input value='' readonly type = 'text' placeholder ='-' required name='keterangan' class='form-control' ></div></div>");
        }
        else if(this.value =='12'){
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>Keterangan</label><div class='col-sm-9'><input value='' readonly type = 'text' placeholder ='-' required name='keterangan' class='form-control' ></div></div>");
        }
        else if(this.value =='13'){
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>Keterangan</label><div class='col-sm-9'><input value='' readonly type = 'text' placeholder ='-' required name='keterangan' class='form-control' ></div></div>");
        }
        else if(this.value =='14'){
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>Keterangan</label><div class='col-sm-9'><input value='' readonly type = 'text' placeholder ='-' required name='keterangan' class='form-control' ></div></div>");
        }
        else if(this.value =='15'){
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>Tahun Akademik</label><div class='col-sm-4'><input value='' type = 'text' placeholder ='ex: 2020/2021' required name='ta_bebas_sanksi' class='form-control' ></div></div>");
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>Keperluan</label><div class='col-sm-9'><textarea value='' placeholder ='Keperluan' required name='keperluan_bebas_sanksi' class='form-control'></textarea></div></div>");
        }
        else if(this.value =='16'){
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>Tanggal Lulus Ujian</label><div class='col-sm-4'><input value='' type = 'text' id='tgl' placeholder='ex: 2020/08/28' required name='tanggal_lulus_ujian' class='form-control' ></div></div>");
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>IPK</label><div class='col-sm-2'><input value='' type = 'text' placeholder ='ex: 3.5' required name='ipk_lulus_ujian' class='form-control' ></div></div>");
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>Predikat Lulus</label><div class='col-sm-4'><input value='' type = 'text' placeholder ='ex: Cumlaude' required name='predikat_lulus_ujian' class='form-control' ></div></div>");
            $('#tgl').datepicker({
                dateFormat : 'yy-mm-dd',
                changeMonth: true,
                changeYear: true
            });
        
        }
        else if(this.value =='17'){
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>Periode Wisuda</label><div class='col-sm-1'><select required name='periode_toefl' class = 'form-control'><option value = 'I'>I</option><option value='II' >II</option><option value='III' >III</option><option value='IV'>IV</option><option value='V'>V</option><option value='VI'>VI</option></select></div></div>");
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>Periode Wisuda</label><div class='col-sm-4'><select required name='bulan_toefl' class = 'form-control'><option value = 'Januari'>Januari</option><option value='Februari' >Februari</option><option value='Maret' >Maret</option><option value='April'>April</option><option value='Mei'>Mei</option><option value='Juni'>Juni</option><option value='Juli'>Juli</option><option value='Agustus'>Agustus</option><option value='September'>September</option><option value='Oktober'>Oktober</option><option value='November'>November</option><option value='Desember'>Desember</option></select></div></div>");
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>Tahun Akademik</label><div class='col-sm-4'><input value='' type = 'text' placeholder ='ex: 2020' required name='tahun_toefl' class='form-control' ></div></div>");
        }
        else if(this.value =='18'){
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>Tahun Akademik</label><div class='col-sm-4'><input value='' type = 'text' placeholder ='ex: 2020/2021' required name='ta_pengunduran' class='form-control' ></div></div>");
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>Alasan Pengunduran Diri</label><div class='col-sm-9'><textarea value='' placeholder ='Alasan Pengunduran Diri' required name='alasan_pengunduran' class='form-control'></textarea></div></div>");
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>Nama Orang Tua</label><div class='col-sm-6'><input value='' type = 'text' placeholder ='Nama Orang Tua' required name='ortu_pengunduran' class='form-control' ></div></div>");
        }
        else if(this.value =='19'){
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>Tanggal Lulus</label><div class='col-sm-4'><input value='' type = 'text' id='tgl' placeholder='ex: 2020/08/28' required name='tgl_lulus' class='form-control' ></div></div>");
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>IPK</label><div class='col-sm-4'><input value='' type = 'text' placeholder ='ex: 3.5' required name='ipk_lulus' class='form-control' ></div></div>");
            $('#tgl').datepicker({
                dateFormat : 'yy-mm-dd',
                changeMonth: true,
                changeYear: true
            });
        }
        else if(this.value =='20'){
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>Tanggal Lulus</label><div class='col-sm-4'><input value='' type = 'text' id='tgl' placeholder='ex: 2020/08/28' required name='tgl_lulus' class='form-control' ></div></div>");
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>IPK</label><div class='col-sm-4'><input value='' type = 'text' placeholder ='ex: 3.5' required name='ipk_lulus' class='form-control' ></div></div>");
            $('#tgl').datepicker({
                dateFormat : 'yy-mm-dd',
                changeMonth: true,
                changeYear: true
            });
        }
        else if(this.value =='21'){
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>Tanggal Lulus</label><div class='col-sm-4'><input value='' type = 'text' id='tgl' placeholder='ex: 2020/08/28' required name='tgl_lulus' class='form-control' ></div></div>");
            $('#tgl').datepicker({
                dateFormat : 'yy-mm-dd',
                changeMonth: true,
                changeYear: true
            });
        }
        else if(this.value =='22'){
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>Tanggal Lulus</label><div class='col-sm-4'><input value='' type = 'text' id='tgl' placeholder='ex: 2020/08/28' required name='tgl_lulus' class='form-control' ></div></div>");
            $('#tgl').datepicker({
                dateFormat : 'yy-mm-dd',
                changeMonth: true,
                changeYear: true
            });
        }
        else if(this.value =='22'){
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>Keterangan</label><div class='col-sm-9'><input value='' readonly type = 'text' placeholder ='-' required name='keterangan' class='form-control' ></div></div>");
        }
        //umum dan keuangan
        else if(this.value =='38'){
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>Tahun Akademik</label><div class='col-sm-4'><input value='' type = 'text' placeholder ='ex: 2020/2021' required name='ta_pinjam' class='form-control' ></div></div>");
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>Nama UKM</label><div class='col-sm-9'><input value='' type = 'text' placeholder ='Nama UKM' required name='ukm_pinjam' class='form-control' ></div></div>");
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>Dalam Rangka</label><div class='col-sm-2'><select required name='rangka_pinjam' class = 'form-control'><option value = ''>-- Pilih --</option><option value='Kegiatan' >Kegiatan</option><option value='Tugas'>Tugas</option></select></div></div>");
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>Nama Kegiatan</label><div class='col-sm-9'><textarea value='' placeholder ='Nama Kegiatan/Rangka/Tugas' required name='kegiatan_pinjam' class='form-control'></textarea></div></div>");
        
            //end
        }
        else if(this.value =='40'){
            <?php  
                $ta = $this->ta_model->get_ta_aktif_npm($this->session->userdata('username'));
                if(!empty($ta)){
                    $pbb_data = $this->user_model->get_dosen_data($ta->pembimbing1);
                    $pbb =  $pbb_data->gelar_depan." ".$pbb_data->name.", ".$pbb_data->gelar_belakang;
                    if($ta->judul_approve == 1){
                        $judul = $ta->judul1;
                    }
                    elseif($ta->judul_approve == 2){
                        $judul = $ta->judul2;
                    }
                    else{
                        $judul = "-";
                    }
                }
                else{
                    $pbb = '-';
                }
                
            ?>
            var pbb = <?php echo json_encode($pbb) ?>;
            var judul = <?php echo json_encode($judul) ?>;
            $(wrapper).append('<div class="position-relative row form-group field"><label class="col-sm-3 col-form-label">Periode Wisuda</label><div class="col-sm-9"><select required name="nama_lab" class = "form-control"><option value="">-- Pilih Laboratorium --</option><option value="Kimia Dasar">Kimia Dasar</option><option value="Biokimia">Biokimia</option><option value="Instrumentasi dan Kimia Analitik">Instrumentasi dan Kimia Analitik</option><option value="Kimia Organik">Kimia Organik</option><option value="Kimia Fisik dan Anorganik">Kimia Fisik dan Anorganik</option><option value="Mikrobiologi">Mikrobiologi</option><option value="Zoologi">Zoologi</option><option value="Botani">Botani</option><option value="Bio Molekuler">Bio Molekuler</option><option value="Ekologi">Ekologi</option><option value="Fisika Dasar">Fisika Dasar</option><option value="Fisika Inti">Fisika Inti</option><option value="Elektronika Dasar">Elektronika Dasar</option><option value="Matematika dan Statistika Terapan">Matematika dan Statistika Terapan</option><option value="Komputasi Dasar">Komputasi Dasar</option><option value="Rekayasa Perangkat Lunak">Rekayasa Perangkat Lunak</option></select></div></div>');
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>Tanggal Mulai & Selesai</label><div class='col-sm-3'><input value='' required name='tgl_mulai' type = 'text' id='tgl' placeholder='ex: 2020/08/28' class='form-control' ></div><div class='col-sm-0'>s/d</div><div class='col-sm-3'><input value='' type = 'text' id='tgl1' placeholder='ex: 2020/08/28' required name='tgl_selesai' class='form-control' ></div></div>");
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>Waktu Mulai & Selesai</label><div class='col-sm-3'><input value='' placeholder ='ex: 09.00' type = 'text' required name='waktu_mulai' class='form-control' ></div><div class='col-sm-0'>s/d</div><div class='col-sm-3'><input value='' type = 'text' required name='waktu_selesai' placeholder ='ex: 17.00' class='form-control' ></div></div>");
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>Pembimbing Utama</label><div class='col-sm-9'><input value=\""+pbb+"\" readonly type = 'text' placeholder ='-' required name='pbb_ta' class='form-control' ></div></div>");
            $(wrapper).append("<div class='position-relative row form-group field'><label class='col-sm-3 col-form-label'>Judul Tema</label><div class='col-sm-9'><textarea value='' readonly placeholder ='Judul Tema' required name='judul_ta' class='form-control'>"+judul+"</textarea></div></div>");
            $('#tgl').datepicker({
                dateFormat : 'yy-mm-dd',
                changeMonth: true,
                changeYear: true
            });
            $('#tgl1').datepicker({
                dateFormat : 'yy-mm-dd',
                changeMonth: true,
                changeYear: true
            });
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

                        