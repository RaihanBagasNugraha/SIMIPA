

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-note icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <?php
                                    $data_dosen = $this->user_model->get_dosen_data($this->session->userdata('userId'));
                                    $strata = $_GET['strata'];
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
                                    
                                    ?>
                                    <div>List Mahasiswa <?php echo strtoupper($strata); ?> Jurusan <?php echo $jur_dosen; ?>
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
                        if ($akun->ttd == NULL){
                            echo "<script>
                            alert('Silahkan Lengkapi Informasi Akun & Biodata Anda Terlebih Dahulu');
                            window.location.href='biodata';
                            </script>";
                        } 
                        
                        ?>
                        
                         <div class="main-card mb-3 card">
                                <div class="card-body">
                                <div class="table-responsive">
                                    <table class="mb-0 table table-striped" id="example">
                                        <thead>
                                        <tr>
                                            <th>Npm</th>
                                            <th>Nama</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if(empty($mhs))
                                        {
                                            echo "<tr><td colspan='6'>Data tidak tersedia</td></tr>";
                                        }
                                        else
                                        {
                                            foreach($mhs as $row) {
                                        ?>
                                            <tr>
                                                <td><?php echo $row->npm ?></td>
                                                <td><?php echo $row->name ?></td>
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
$(document).ready(function() {
    $('#example').DataTable(
        {
       
        } 
    );
} );
$(document).ready(function(){
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
                        