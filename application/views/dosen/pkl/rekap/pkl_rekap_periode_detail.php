

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-note icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <?php
                                    $data_dosen = $this->user_model->get_dosen_data($this->session->userdata('userId'));
                                    
                                    if($data_dosen->jurusan != NULL){
                                    switch($data_dosen->jurusan)
                                        {
                                            case "0":
                                            $jur_dosen = "Dokter MIPA";
                                            break;
                                            case "1":
                                            $jur_dosen = "Kimia";
                                            break;
                                            case "2":
                                            $jur_dosen = "Biologi";
                                            break;
                                            case "3":
                                            $jur_dosen = "Matematika";
                                            break;
                                            case "4":
                                            $jur_dosen = "Fisika";
                                            break;
                                            case "5":
                                            $jur_dosen = "Ilmu Komputer";
                                            break;
                                        }
                                    }
                                    else{
                                        $jur_dosen = "";
                                    }

                                    $id_user = $this->session->userdata('userId');
                                    ?>

                                    <div>Detail Rekap Periode KP/PKL Mahasiswa Jurusan <?php echo $jur_dosen; ?>
                                        <div class="page-title-subheading">
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div> <!-- app-page-title -->
                        <?php
                        // debug
                        //echo "<pre>";
                        //print_r($biodata);
                        //echo "</pre>";
                        if(!empty($_GET['status']) && $_GET['status'] == 'sukses') {

                            echo '<div class="alert alert-success fade show" role="alert">Biodata Anda sudah diperbarui, jangan lupa untuk memperbarui <a href="javascript:void(0);" class="alert-link">Akun</a> sebelum menggunakan layanan.</div>';
                        }
                        ?>
                        <?php 
                        // if ($akun->ttd == NULL){
                        //     echo "<script>
                        //     alert('Silahkan Lengkapi Informasi Akun & Biodata Anda Terlebih Dahulu');
                        //     window.location.href='biodata';
                        //     </script>";
                        // } 
                        
                        ?>
                        
                         <div class="main-card mb-3 card">
                                <div class="card-body">
                                <div class="table-responsive">
                                    <table class="mb-0 table table-striped" id="example">
                                        <thead>
                                        <tr>
                                            <!-- <th>No</th> -->
                                            <th style="width: 20%;">NPM</th>
                                            <th style="width: 20%;">Nama</th>
                                            <th style="width: 40%;">Lokasi</th>
                                            <th style="width: 10%;">KP/PKL</th>
                                            <th style="width: 10%;">Seminar</th>
                                            <!-- <th>Persentase</th> -->
                                            
                                           
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if(empty($pkl))
                                        {
                                            echo "<tr><td colspan='6'>Data tidak tersedia</td></tr>";
                                        }
                                        else
                                        {
                                           
                                            foreach($pkl as $row) {
                                        ?>
                                            <tr>
                                                <td class="align-top">
                                                    <?php echo $row->npm?>
                                                </td>
                                                <td class="align-top">
                                                    <?php echo $this->user_model->get_mahasiswa_name($row->npm); ?>
                                                </td>
                                                <td class="align-top">
                                                   <?php 
                                                    $lokasi = $this->pkl_model->get_lokasi_pkl_by_id($row->id_lokasi);
                                                    echo $lokasi->lokasi;
                                                   ?>
                                                </td>
                                                <td class="align-top">
                                                    <a style="color: white;" class="btn-wide btn btn-primary" href="<?php echo site_url("dosen/rekap-pkl/periode/detail/pkl?id=".$this->encrypt->encode($row->pkl_id))?>"><b>Lihat</b></a>
                                                </td>
                                                <td class="align-top">
                                                   <?php 
                                                        //cek seminar
                                                        $seminar = $this->pkl_model->get_pkl_seminar_by_pkl_id($row->pkl_id);
                                                        if(!empty($seminar)){
                                                   ?>
                                                    <a style="color: white;" class="btn-wide btn btn-info" href="<?php echo site_url("dosen/rekap-pkl/periode/detail/seminar?id=".$this->encrypt->encode($row->pkl_id))?>"><b>Lihat</b></a>
                                                    <?php } else{ echo "-"; }?>
                                                </td>
                                               
                                                
                                            </tr>
                                        <?php
                                            }
                                        }
                                        ?>
                                    
                                        
                                        </tbody>
                                    </table>
                                </div>
                                </div>
                            </div>
<script src="<?php echo site_url("assets/scripts/jquery_3.4.1_jquery.min.js") ?>"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js"></script>
<script src="<?php echo site_url("assets/scripts/dataTables.bootstrap4.min.js") ?>"></script>
<script src="<?php echo site_url("assets/scripts/DataTables-1.10.21/jquery.dataTables.min.js") ?>"></script>

<script type="text/javascript">

$(document).ready(function(){
    $('#example').DataTable( {
        "order": [[ 3, "desc" ]]
    } );
    
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
    $(".passingIDKoor").click(function () {
                var id = $(this).attr('data-id');
                $("#IDKoor").val( id );

            });
      
</script>
                        