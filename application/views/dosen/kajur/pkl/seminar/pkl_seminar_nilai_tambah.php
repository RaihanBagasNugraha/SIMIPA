
<div class="app-page-title">
                        <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-file icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <div>Komponen Penilaian Seminar KP/PKL
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
                            if(!empty($_GET['status']) && $_GET['status'] == 'komponen') {

                                echo '<div class="alert alert-danger fade show" role="alert">Persentase Komponen Penilaian Lebih Dari 100%</div>';
                            }
                            if(!empty($_GET['status']) && $_GET['status'] == 'pembimbing') {

                                echo '<div class="alert alert-danger fade show" role="alert">Persentase Nilai Pembimbing Dan Komponen Harus Sama Dengan 100%</div>';
                            }
                            if(!empty($_GET['status']) && $_GET['status'] == 'berhasil') {

                                echo '<div class="alert alert-success fade show" role="alert">Komponen Penilaian Berhasil Ditambahkan</div>';
                            }
                            // $aksi = $_GET['aksi'];
                            // $ida = $_GET['id'];
                            if($komponen['status'] == 1 ){
                                $button = "";
                            }
                            elseif($komponen['status'] == 0){
                                $button = "disabled";
                            }
                            else{
                                $button = "";
                            }
                            
                        ?>

                         <div class="main-card mb-3 card">
                                <div class="card-header">Komponen Penilaian Seminar KP/PKL</div>
                                <div class="card-body">
                                <form method="post" action="<?php echo site_url('dosen/struktural/pkl/komponen-nilai-pkl/simpan') ?>" >
                                    <input value="<?php echo $komponen['id'] ?>" type = "hidden" required name="id">
                                    <input value="<?php echo $aksi ?>" type = "hidden" required name="aksi">
                                   
                                    <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">Jurusan</label>
                                            <div class="col-sm-3">
                                            <?php $jurusan = $this->user_model->get_dosen_jur($this->session->userdata('userId'))->nama ?>
                                                <input value="<?php echo $jurusan ?>" required name="" class="form-control" readonly >
                                            </div>
                                    </div>

                                    <div class="position-relative row form-group">
                                            <label class="col-sm-6 col-form-label"><b>Persentase Penilaian Dosen Pembimbing</b></label>
                                    </div>
                                    <?php $bobot = explode("#",$komponen['bobot']) ?>
                                    <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">Dosen Pembimbing KP/PKL</label>
                                            <div class="col-sm-2">
                                            <?php $jurusan = $this->user_model->get_dosen_jur($this->session->userdata('userId'))->nama ?>
                                                <input type = "number" placeholder = "%" min="0" max="100" value="<?php echo $komponen['bobot'] != null ? $bobot[0] : "" ?>" required name="pbb" class="form-control" >
                                            </div>
                                    </div>

                                    <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label">Dosen Pembimbing Lapangan</label>
                                            <div class="col-sm-2">
                                            <?php $jurusan = $this->user_model->get_dosen_jur($this->session->userdata('userId'))->nama ?>
                                                <input type = "number" placeholder = "%" min="0" max="100" value="<?php echo $komponen['bobot'] != null ? $bobot[1] : "" ?>" required name="pbl" class="form-control" >
                                            </div>
                                    </div>

                                    
                                    <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label"><b>Komponen Penilaian</b></label>
                                    </div>
                                    <table class="mb-0 table table-striped"  id="example">
                                            <thead>
                                            <tr>
                                                <th style="width:50%">Aspek Yang Dinilai</th>
                                                <th style="width:30%">Persentase</th>
                                                <th style="width:20%">Aksi</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td class="align-top" colspan = "2">
                                                <b>1. Seminar</b>
                                                </td> 
                                                <td class="align-top">
                                                    <a data-toggle = "modal" data-aksi="<?php echo $aksi ?>" data-ida ="<?php echo $ida ?>" data-id="<?php echo $komponen['id'] ?>" data-jenis="Seminar" class="passingID" >
                                                                <button type="button" class="btn mb-2 btn-wide btn-success btn-sm btn-block"  <?php echo $button ?>  data-toggle="modal" data-target="#komponensmr">
                                                                    Tambah Komponen
                                                                </button>
                                                    </a>
                                                </td> 
                                            </tr>
                                            <?php 
                                                $kom_seminar = $this->pkl_model->get_seminar_komponen_meta2($ida,"Seminar"); 
                                                $s = 1;
                                                if(!empty($kom_seminar)){
                                                    foreach($kom_seminar as $kom){
                                            ?>
                                                <tr>
                                                <td class="align-top">
                                                    <?php echo "&emsp;&emsp;&emsp;$s. $kom->attribut" ?>
                                                </td> 
                                                <td class="align-top">
                                                    <?php echo $kom->persentase."%" ?>
                                                </td> 
                                                <td class="align-top">
                                                    <!--<a data-toggle = "modal" data-id="<?php echo $kom->id ?>" data-aksi="<?php echo $aksi ?>" data-ida ="<?php echo $ida ?>" data-attr="<?php echo $kom->attribut ?>" data-per="<?php echo $kom->persentase ?>" class="passingIDUbah" >-->
                                                    <!--            <button type="button" class="btn mb-2 btn-wide btn-warning btn-sm"   <?php echo $button ?> data-toggle="modal" data-target="#komponensmrubah">-->
                                                    <!--                Ubah-->
                                                    <!--            </button>-->
                                                    <!--</a>-->
                                                    <a data-toggle = "modal" data-id="<?php echo $kom->id ?>" data-aksi="<?php echo $aksi ?>" data-ida ="<?php echo $ida ?>" data-jenis="Seminar" class="passingIDHapus" >
                                                                <button type="button" class="btn mb-2 btn-wide btn-danger btn-sm "  <?php echo $button ?> data-toggle="modal" data-target="#komponensmrhapus">
                                                                    Hapus
                                                                </button>
                                                    </a>
                                                </td> 
                                                </tr>
                                            <?php   
                                                $s++;         
                                                    }
                                                }
                                            ?>

                                            <tr>
                                                <td class="align-top" colspan = "2"> 
                                                    <b>2. Laporan</b>
                                                </td> 
                                                <td class="align-top">
                                                    <a data-toggle = "modal" data-aksi="<?php echo $aksi ?>" data-ida ="<?php echo $ida ?>" data-id="<?php echo $komponen['id'] ?>" data-jenis="Laporan" class="passingID" >
                                                                <button type="button" class="btn mb-2 btn-wide btn-success btn-sm btn-block"  <?php echo $button ?>  data-toggle="modal" data-target="#komponensmr">
                                                                    Tambah Komponen
                                                                </button>
                                                    </a>
                                                </td> 
                                            </tr>
                                            <?php 
                                                $kom_seminar = $this->pkl_model->get_seminar_komponen_meta2($ida,"Laporan"); 
                                                if(!empty($kom_seminar)){
                                                $l = 1;
                                                    foreach($kom_seminar as $kom){
                                            ?>
                                                <tr>
                                                <td class="align-top">
                                                    <?php echo "&emsp;&emsp;&emsp;$l. $kom->attribut" ?>
                                                </td> 
                                                <td class="align-top">
                                                    <?php echo $kom->persentase."%" ?>
                                                </td> 
                                                <td class="align-top">
                                                    <a data-toggle = "modal" data-id="<?php echo $kom->id ?>" data-attr="<?php echo $kom->attribut ?>" data-per="<?php echo $kom->persentase ?>" data-aksi="<?php echo $aksi ?>" data-ida ="<?php echo $ida ?>" class="passingIDUbah" >
                                                                <button type="button" class="btn mb-2 btn-wide btn-warning btn-sm"  <?php echo $button ?> data-toggle="modal" data-target="#komponensmrubah">
                                                                    Ubah
                                                                </button>
                                                    </a>
                                                    <a data-toggle = "modal" data-id="<?php echo $kom->id ?>" data-jenis="Seminar" data-aksi="<?php echo $aksi ?>" data-ida ="<?php echo $ida ?>" class="passingIDHapus" >
                                                                <button type="button" class="btn mb-2 btn-wide btn-danger btn-sm "  <?php echo $button ?> data-toggle="modal" data-target="#komponensmrhapus">
                                                                    Hapus
                                                                </button>
                                                    </a>
                                                </td> 
                                                </tr>
                                            <?php    
                                                $l++;        
                                                    }
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                    
                                    <div class="position-relative row form-group">
                                            <div class="col-sm-9 offset-sm-3">
                                            <button id="preview" value="<?php if($this->input->get('aksi') == "ubah") echo "ubah"; ?>" type="submit"  <?php echo $button ?> class="btn-shadow btn btn-info">
                                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fa fa-save fa-w-20"></i>
                                            </span>
                                            <?php if($this->input->get('aksi') == "ubah") echo "Ubah"; else echo "Simpan" ?>
                                        </button>
                                            </div>
                                    </div>
                                
                                </form>



                    

</div>
<script src="<?php echo site_url("assets/scripts/jquery_3.4.1_jquery.min.js") ?>"></script>
<script src="<?php echo site_url("assets/scripts/select2.full.js") ?>"></script>
<script type="text/javascript">

</script>
<script src="<?php echo site_url("assets/scripts/signature_pad.js") ?>"></script>
<script>
$(document).ready(function() {
    $('#example').DataTable( {
        "order": [[ 0, "desc" ]]
    } );
} );

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
    $(".passingID").click(function () {
                var id = $(this).attr('data-id');
                var jns = $(this).attr('data-jenis');
                var a = $(this).attr('data-aksi');
                var b = $(this).attr('data-ida');
                $("#IDJur").val( id );
                $("#Jenis").val( jns );
                $("#Aksi3").val( a );
                $("#Ida3").val( b );
            });
    $(".passingIDHapus").click(function () {
                var id = $(this).attr('data-id');
                var a = $(this).attr('data-aksi');
                var b = $(this).attr('data-ida');
                $("#IDMeta").val( id );
                $("#Aksi").val( a );
                $("#Ida").val( b );
            });
    $(".passingIDUbah").click(function () {
                var id = $(this).attr('data-id');
                var attr = $(this).attr('data-attr');
                var per = $(this).attr('data-per');
                var a = $(this).attr('data-aksi');
                var b = $(this).attr('data-ida');
                $("#IDMeta2").val( id );
                $("#Attr").val( attr );
                $("#Persentase").val( per );
                $("#Aksi2").val( a );
                $("#Ida2").val( b );
            });
</script>