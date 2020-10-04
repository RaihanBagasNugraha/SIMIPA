
<div class="app-page-title">
                        <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-file icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <div>Lihat KP/PKL
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
                           
                        ?>

                        <div class="row">
                        <div class="col-md-12">
                         <div class="main-card mb-3 card">
                                <div class="card-header">Detail KP/PKL</div>
                                <div class="card-body">

                                <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label"><b>Jurusan</b></label>
                                            <div class="col-sm-3">
                                            <?php 
                                                switch($pkl->jurusan){
                                                    case "0":
                                                    $jur = "Doktor MIPA";
                                                    break;
                                                    case "1":
                                                    $jur = "Kimia";
                                                    break;
                                                    case "2":
                                                    $jur = "Biologi";
                                                    break;
                                                    case "3":
                                                    $jur = "Matematika";
                                                    break;
                                                    case "4":
                                                    $jur = "Fisika";
                                                    break;
                                                    case "5":
                                                    $jur = "Ilmu Komputer";
                                                    break;
                                                }
                                            
                                            ?>
                                            <input value="<?php echo $jur?>" type = "text" name="tahun" readonly class="form-control">
                                            </div>
                                </div>         
  
                                <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label"><b>Tahun</b></label>
                                            <div class="col-sm-3">
                                            <!-- <?php print_r($pkl);?> -->
                                            <input value="<?php echo $pkl->tahun?>" type = "text" name="tahun" readonly class="form-control">
                                            </div>
                                </div>         

                                <div class="position-relative row form-group">
                                            <label class="col-sm-3 col-form-label"><b>Periode</b></label>
                                            <div class="col-sm-3">
                                            <input value="<?php echo $pkl->periode?>" type = "text" name="periode" readonly class="form-control">
                                            </div>
                                </div>      
                                <br>
                                <h6><b>Timeline KP/PKL</b></h6>
                                <table class="mb-0 table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Keterangan</th>
                                            <th>Waktu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                         $pkl_meta = $this->pkl_model->get_pkl_periode_meta($pkl->id_pkl); 
                                         foreach($pkl_meta as $row){
                                        ?>
                                        <tr>
                                            <td><?php echo $row->keterangan ?></td>
                                            <?php                                             
                                                
                                                $start = explode("-",$row->date_start);
                                                $date_start = $start[2]."-".$start[1]."-".$start[0];

                                                if($row->date_end != "NULL"){
                                                    $end = explode("-",$row->date_end);
                                                    $date_end = $end[2]."-".$end[1]."-".$end[0];
                                                }
                                                $date_end_cek = new DateTime($date_end);
                                                $date_end_cek = $date_end_cek->format("Y");
                                            ?>
                                            <td>
                                            <?php
                                                if($date_end_cek > "1"){
                                                    echo "<b>".$date_start." s.d. ".$date_end."</b>";
                                                }
                                                else{
                                                    echo "<b>".$date_start."</b>";
                                                }
                                            ?>
                                            </td>
                                            
                                        </tr>

                                        <?php } ?>
                                    </tbody>

                                </table>

                                <br>
                                <?php  $lokasi = $this->pkl_model->get_lokasi_pkl($pkl->id_pkl) ?>
                                <h6><b>Lokasi KP/PKL</b>  
                                <?php if(empty($lokasi)){ ?>
                                <a href="<?php echo site_url("dosen/struktural/pkl/add-lokasi-pkl") ?>" class="btn-wide mb-1 btn btn-success btn-sm "><span><i class="fa fa-plus" aria-hidden="true"></i></span>&nbsp;Tambah
                                </a>
                                <?php } ?>
                                </h6>
                                <?php if(!empty($lokasi)){ ?>
                                <table class="mb-0 table table-striped">
                                    <thead>
                                        <tr>
                                            <th style="width:40%">Lokasi</th>
                                            <th style="width:60%">Alamat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($lokasi as $lok){ ?>
                                            <tr>
                                                <td><?php echo $lok->lokasi; ?></td>
                                                <td><?php echo $lok->alamat; ?></td>
                                            </tr>    
                                        <?php } ?>
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