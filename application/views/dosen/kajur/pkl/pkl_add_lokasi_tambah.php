

<div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-note icon-gradient bg-mean-fruit">
                                        </i>
                                    </div>
                                    <div>Tambah Lokasi PKL
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
                        $aksi = $_GET['aksi'];
                        $id_aksi = $_GET['id'];
                        ?>
                        
                        <div class="col-md-12">
                        <div class="card mb-4 widget-chart  card-btm-border card-shadow-success border-success">
                            <div class="text-left ml-1 mr-1 mt-2">
                                    <form method="post" action="<?php echo site_url("dosen/struktural/pkl/add-lokasi-pkl/aksi/save") ?>">
                                        <div class="form-row">
                                            <div class="col-md-8">
                                                <div class="position-relative form-group">
                                                <label for="lokasi"><b>Lokasi KP/PKL</b></label>
                                                    <input type="text" class="form-control" name="lokasi" value="" required placeholder="Lokasi KP/PKL Mahasiswa" />
                                                </div>
                                            </div>  
                                    
                                            <div class="col-md-3">
                                                <div class="position-relative form-group">
                                                <label for="kuota"><b>Kuota Lokasi</b><span style="color:black;">*</span></label>
                                                    <input type="number" class="form-control" name="kuota" value="" placeholder="Kuota Lokasi KP/PKL"/>
                                                    <input type="hidden" name="id_pkl" value=<?php echo $id;?> />
                                                    <input type="hidden" name="id_aksi" value=<?php echo $id_aksi;?> />
                                                    <input type="hidden" name="aksi" value=<?php echo $aksi;?> />
                                                </div>
                                            </div>

                                            <div class="col-md-1">
                                                <div class="position-relative form-group">
                                                    <label for="btnSubmit">&nbsp;</label>
                                                    <button id="btnSubmit" type="submit" class="btn-shadow btn btn-lg btn-primary">
                                                    
                                                        Simpan
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="col-md-11">
                                                <div class="position-relative form-group">
                                                <label for="lokasi"><b>Alamat Lokasi KP/PKL</b></label>
                                                <textarea name="alamat" required class="form-control" placeholder="Alamat lokasi KP/PKL..."></textarea>
                                                </div>
                                            </div>  

                                        </div>
                                    </form>  
                                    <p style="font-size:100%;">*) Isi jika lokasi KP/PKL memiliki kuota terbatas untuk mahasiswa. Kosongkan jika tidak terbatas.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="main-card mb-3 card card-btm-border card-shadow-primary border-primary">
                            
                            <div class="card-body">
                            
                           
                            <h5 class="card-title">Daftar Lokasi KP/PKL Tahun <?php echo $pkl->tahun ?> Periode <?php echo $pkl->periode ?></h5>
                            <style>
                            tbody th {
                                background-color: #f5f5f5;
                            }
                            </style>
                            <table data-page-length='50' id="example" style="width: 100%;" class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th style="width: 3%;">No</th>
                                            <th style="width: 20%;">Lokasi</th>
                                            <th style="width: 40%;">Alamat</th>
                                            <th style="width: 10%;">Kuota</th>
                                            <th style="width: 17%;">Aksi</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php  
                                            $list = $this->pkl_model->get_lokasi_pkl($pkl->id_pkl); 
                                            $no = 1;
                                            foreach($list as $row){
                                        ?>
                                            <tr>
                                                <td><?php echo $no; ?></td>
                                                <td><b><?php echo $row->lokasi; ?></b></td>
                                                <td><?php echo $row->alamat; ?></td>
                                                <td><?php echo $row->maks_kuota == "99" ? "∞": $row->maks_kuota." Orang" ; ?></td>
                                                <td>
                                                    <a data-toggle = "modal" data-id="<?php echo "$row->id";?>" data-alamat="<?php echo "$row->alamat";?>" data-aksi-id="<?php echo "$id_aksi";?>" data-lokasi="<?php echo "$row->lokasi";?>" data-kuota="<?php echo "$row->maks_kuota";?>" class="passingID2" ><span>
                                                        <button type="button" class="btn mb-1 btn-wide btn-warning btn-sm "  data-toggle="modal" data-target="#editlokasi"><i class="fa fa-edit" aria-hidden="true"></i></span> 
                                                            Ubah
                                                        </button>
                                                    </a>

                                                    <a data-toggle = "modal" data-id="<?php echo "$row->id";?>" data-aksi-id="<?php echo "$id_aksi";?>" class="passingID" ><span>
                                                        <button type="button" class="btn mb-1 btn-wide btn-danger btn-sm "  data-toggle="modal" data-target="#dellokasi"><i class="fa fa-times" aria-hidden="true"></i></span> 
                                                            Hapus 
                                                        </button>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php $no++; } ?>
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

$(document).ready(function() {
    $('#example').DataTable();
} );

</script>

<script>
        $(".passingID").click(function () {
                var id = $(this).attr('data-id');
                var id_lok = $(this).attr('data-aksi-id');
                $("#IDLokasi").val( id );
                $("#IDAksi").val( id_lok );
            });

        $(".passingID2").click(function () {
                var id = $(this).attr('data-id');
                var id_lok = $(this).attr('data-aksi-id');
                var lokasi = $(this).attr('data-lokasi');
                var kuota = $(this).attr('data-kuota');
                var alamat = $(this).attr('data-alamat');
                $("#IDLokasi2").val( id );
                $("#IDAksi2").val( id_lok );
                $("#Lokasi").val( lokasi );
                $("#Kuota").val( kuota );
                $("#Alamat").val( alamat );
            });
              
     
</script>