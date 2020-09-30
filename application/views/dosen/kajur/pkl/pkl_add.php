

<div class="app-page-title">
<div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-note icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <div>Tambah KP/PKL
                                        <div class="page-title-subheading">
                                        <?php $jur = $this->user_model->get_dosen_jur($this->session->userdata('userId')); echo "Jurusan $jur->nama";?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="page-title-actions">
                                    <a href="<?php echo site_url("dosen/struktural/pkl/add-pkl/form") ?>" class="btn-shadow btn btn-success">
                                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-file fa-w-20"></i>
                                            </span>
                                            Form Tambah KP/PKL
                                    </a>
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
                        if(!empty($_GET['status']) && $_GET['status'] == 'duplikat') {

                            echo '<div class="alert alert-danger fade show" role="alert">Terdapat Duplikasi Saat Mengisi Data</div>';
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
                                    <table class="mb-0 table table-striped"  id="example">
                                        <thead>
                                        <tr>
                                            <th style="width:10%">Nomor</th>
                                            <th style="width:40%">Tahun</th>
                                            <th style="width:20%">Periode</th>
                                            <th style="width:30%">Aksi</th>
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
                                            $no = 1;
                                            foreach($pkl as $row){     
                                        ?>
                                            <tr>
                                                <td class="align-top">
                                                    <?php echo $no;?>
                                                </td>
                                                <td class="align-top">
                                                    <?php echo $row->tahun;?>
                                                </td>
                                                <td class="align-top">
                                                    <?php echo $row->periode;?>
                                                </td>
                                                <td class="align-top">
                                                    <a href="<?php echo site_url("dosen/struktural/pkl/add-pkl/show?id=".$this->encrypt->encode("$row->id_pkl")) ?>" class="btn-wide mb-1 btn btn-success btn-sm "><span><i class="fa fa-eye" aria-hidden="true"></i></span> Lihat
                                                    </a>
                                                    <a href="<?php echo site_url("dosen/struktural/pkl/add-pkl/form?aksi=ubah&id=".$this->encrypt->encode("$row->id_pkl")) ?>" class="btn-wide mb-1 btn btn-warning btn-sm "><i class="fa fa-edit" aria-hidden="true"></i></span> Ubah
                                                    </a>
                                                    <a data-toggle = "modal" data-id="<?php echo "$row->id_pkl";?>" class="passingID" ><span>
                                                        <button type="button" class="btn mb-1 btn-wide btn-danger btn-sm "  data-toggle="modal" data-target="#delkp"><i class="fa fa-times" aria-hidden="true"></i></span> 
                                                            Hapus 
                                                        </button>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php
                                          $no++;  }     
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
    $('#example').DataTable();
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
    $(".passingID").click(function () {
                var id = $(this).attr('data-id');
                $("#ID").val( id );

            });
      
</script>
                        