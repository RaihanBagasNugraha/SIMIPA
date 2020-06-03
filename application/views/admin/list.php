                        <div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-server icon-gradient bg-happy-itmeo">
                                        </i>
                                    </div>
                                    <div>Data Peserta
                                    <?php
                                        $unit_kerja = "";
                                        switch($this->uri->segment(2))
                                        {
                                            case 'eb': $unit_kerja = "Fakultas Ekonomi dan Bisnis"; break;
                                            case 'hukum': $unit_kerja = "Fakultas Hukum"; break;
                                            case 'kip': $unit_kerja = "Fakultas Keguruan dan Ilmu Pendidikan"; break;
                                            case 'pertanian': $unit_kerja = "Fakultas Pertanian"; break;
                                            case 'teknik': $unit_kerja = "Fakultas Teknik"; break;
                                            case 'isip': $unit_kerja = "Fakultas Ilmu Sosial dan Ilmu Politik"; break;
                                            case 'mipa': $unit_kerja = "Fakultas Matematika dan Ilmu Pengetahuan Alam"; break;
                                            case 'kedokteran': $unit_kerja = "Fakultas Kedokteran"; break;
                                            case 'pasca': $unit_kerja = "Pascasarjana / Umum"; break;
                                        }

                                        echo $unit_kerja." Angkatan 20".$this->uri->segment(3);
                                    ?>
                                        <div class="page-title-subheading">Daftar Peserta Tes TOEFL.
                                        </div>
                                    </div>
                                </div>

                                <div class="page-title-actions">
                                    <a href="javascript:history.back()" class="btn-shadow btn btn-success">
                                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-arrow-left fa-w-20"></i>
                                            </span>
                                            KEMBALI
                                    </a>
                                </div>
                                
                            </div>
                        </div>
                        
                        <script src="https://code.jquery.com/jquery-3.1.1.js"></script>
<!--
    <link href="https://nightly.datatables.net/css/jquery.dataTables.css" rel="stylesheet" type="text/css" />
    <script src="https://nightly.datatables.net/js/jquery.dataTables.js"></script>-->
    <style>
        table#example thead th {
          vertical-align: middle;
          padding: 10px 8px 10px 6px;
        }
        
        table#example tfoot th {
          vertical-align: top;
          padding: 10px 8px 10px 6px;
        }
        
        table#example tbody th {
          vertical-align: top;
          text-align: right;
          
          
        }
        
        table#example tbody td {
          vertical-align: top;
          
        }
        
        .cek-list {
            margin-top: 4px;
        }
        
        .aksi {
            margin-bottom: 3px;
        }
    </style>
    
    <?php
    
    //echo "<pre>";
    //print_r($list_personil);
    //echo "</pre>";
    
    ?>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="main-card mb-3 card">
                                    <div class="card-body">
                                        <form method="post" action="<?php echo site_url('cetak') ?>">
                                        <table id="example" width="100%" class="mb-0 table table-hover table-responsive w-100 d-block d-md-table">
                                            <thead>
                                            <tr>
                                                <th width="7%">#</th>
                                              <!--  <th width="5%">&nbsp;</th> -->
                                                <th width="12%">NPM</th>
                                                <th width="28%">Nama</th>
                                                <th width="13%">Jurusan/Prodi</th>
                                                <th width="15%">Tgl Lahir</th>
                                                <th width="10%">Aksi</th>
                                            </tr>
                                            </thead>
                                        <!--    <tfoot>
                                                <th>&nbsp;</th>
                                                <th><input id="check-all" type="checkbox" class="cek-list"></th>
                                                <th colspan="5">
                                                    <button id="submit" type="submit" class="mb-2 mr-2 btn btn-info">
                                                        <span class="btn-icon-wrapper pr-2 opacity-7">
                                                            <i class="fas fa-print fa-w-20"></i>
                                                        </span>
                                                        CETAK PILIHAN
                                                    </button>
                                                </th>
                                            </tfoot> -->
                                            <tbody>
                                            <?php
                                            $no = 0;
                                            foreach($list_peserta as $obj) {
                                                $no += 1;
                                                
                                            ?>
                                            <tr>
                                                <td><?php echo $no ?></td>
                                            <!--    <td>
                                                <div class="custom-checkbox custom-control">
                                        
                                                <input id="exampleCustomCheckbox<?php echo $no ?>" name="cekNrp[]" value="" type="checkbox" class="custom-control-input cek-list">
                                                <label class="custom-control-label" for="exampleCustomCheckbox<?php echo $no ?>"></label></div>
                                                </td> -->
                                                <td>
                                                <?php echo $obj->ID ?>
                                                </td>
                                                <td>
                                                <?php echo $obj->nama ?>
                                                </td>
                                                <td>
                                                <?php echo $obj->jurusan_ps ?>
                                                </td>
                                                
                                                <td><?php 
                                                if(strpos($obj->normal_tgl_lahir, '/') ==  true)
                                                {
                                                    echo $obj->normal_tgl_lahir;
                                                }
                                                else
                                                {
                                                    
                                                
                                                $UNIX_DATE = ($obj->normal_tgl_lahir - 25569) * 86400;
                                                echo gmdate("d/m/Y", $UNIX_DATE); 
                                                }
                                                ?>
                                                </td>
                                                <td>
                                                    <a href="<?php echo site_url('detail-peserta/'.$obj->ID) ?>" class="border-0 btn-transition btn btn-outline-primary btn-sm">Detail</a> <!--<a href="#" class="btn-transition btn btn-sm btn-outline-alternate aksi"><i class="nav-link-icon fa fa-print"></i></a>-->
                                                </td>
                                            </tr>
                                            <?php
                                                
                                            }
                                            ?>
                                            
                                            </tbody>
                                        </table>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script>
                            $(document).ready( function () {
                                /*var table = $('.data-table').DataTable({
                                    "aoColumnDefs": [
                                        { "bSortable": false, "aTargets": [ 0, 1, 2, 3, 4, 5, 6 ] }
                                    ]
                                });
                                */
                                $('#check-all').click(function(){
                                    $('input:checkbox').not(this).prop('checked', this.checked);
                                });
                                
                                $("form").submit(function(){
                                //$("#submit").click(function(){
                                 if ($('input:checkbox').filter(':checked').length < 1){
                                        alert("Pilih minimal satu peserta yang akan dicetak.");
                                        //$('.modal').modal('show');
                                 return false;
                                 }
                                });
                            } );
                        </script>