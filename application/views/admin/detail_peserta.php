                        <div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-id icon-gradient bg-arielle-smile">
                                        </i>
                                    </div>
                                    <div>Detail Hasil Tes TOEFL Peserta
                                        <div class="page-title-subheading">
                                            Silakan ubah data peserta, jika terdapat ketidaksesuaian.
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

              <!--           <link href="https://nightly.datatables.net/css/jquery.dataTables.css" rel="stylesheet" type="text/css" />
                        <script src="https://nightly.datatables.net/js/jquery.dataTables.js"></script> -->
                        <style>
                            table.data-table thead th {
                              vertical-align: middle;
                              font-size: 10pt;
                              padding: 10px 4px 10px 3px;
                            }
                            
                            table.data-table tfoot th {
                              vertical-align: top;
                              padding: 10px 4px 10px 3px;
                            }
                            
                            table.data-table tbody th {
                              vertical-align: top;
                              text-align: right;
                              padding: 5px 4px 5px 3px;
                            }
                            
                            table.data-table tbody td {
                              vertical-align: top;
                            }
                            
                            .form-control {
                                margin-bottom: 4px;
                            }
                            
                            .aksi {
                                margin-bottom: 3px;
                            }
                            
                            
                        </style>
                        
                    
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="main-card mb-3 card">
                                    <div class="card-body">
                                        <h5 class="card-title">Data Peserta
                                        </h5>
                                        
                                        <form class="" method="post" action="<?php echo site_url("update-peserta") ?>">
                                            <div class="position-relative row form-group">
                                                <div class="col-sm-2">
                                                    <input value="<?php echo $peserta->ID ?>" name="NPM" id="nrp" placeholder="NPM" type="text" class="form-control form-control-sm" />
                                                </div>
                                                <div class="col-sm-3">
                                                    <input value="<?php echo $peserta->nama ?>" name="nama" id="nama" placeholder="Nama Lengkap" type="text" class="form-control form-control-sm" />
                                                </div>
                                                <div class="col-sm-2">
                                                    <input value="<?php echo $peserta->fakultas ?>" name="fakultas" id="fakultas" placeholder="Fakultas" type="text" class="form-control form-control-sm" />
                                                </div>
                                            
                                                <div class="col-sm-3">
                                                    <input value="<?php echo $peserta->jurusan_ps ?>" name="jurusan_ps" id="jurusan_ps" placeholder="Jurusan/PS" type="text" class="form-control form-control-sm" />
                                                </div>
                                                <div class="col-sm-2">
                                                    <button disabled class="btn-shadow btn btn-block btn-primary">
                                                        <span class="btn-icon-wrapper pr-2 opacity-7">
                                                            <i class="fas fa-paper-plane fa-w-20"></i>
                                                        </span>
                                                        UBAH DATA
                                                    </button>
                                                </div>

                                            </div>
                                                    
                                                </form>    
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-12">
                                <div class="main-card mb-3 card">
                                    <div class="card-body">
                                        <h5 class="card-title">Data Hasil Tes TOEFL
                                        </h5>
                                        
                                        <table id="example" width="100%" class="mb-0 table table-hover data-table table-responsive w-100 d-block d-md-table table-striped">
                                            <thead>
                                            <tr>
                                                <th width="5%">#</th>
                                                <th width="13%">Tgl Tes</th>
                                                <th width="33%">No Sertifikat</th>
                                                <th width="10%">Tes Ke</th>
                                                <th width="8%">Lis</th>
                                                <th width="8%">Str</th>
                                                <th width="8%">RV</th>
                                                <th width="8%">Skor</th>
                                              <!--  <th width="7%">Aksi</th> -->
                                            </tr>
                                            </thead>
                                           <!-- <tfoot>
                                                <tr>
                                                    <th colspan="8">
                                                        <a href="<?php echo site_url('add-item-pelanggaran/'.$this->uri->segment(2)) ?>" class="btn-shadow btn btn-sm btn-success">
                                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-plus fa-w-20"></i>
                                            </span>
                                            TAMBAH DATA
                                    </a>
                                                    </th>
                                                </tr>
                                            </tfoot> -->
                                            <tbody>
                                            <?php 
                                            $no = 0;
                                            foreach($nilai as $obj) {
                                                $no += 1;
                                                $date = date_create($obj->tes_tgl);
                                            ?>
                                            <tr>
                                                <td><?php echo $no ?></td>
                                                <td><?php echo date_format($date, "m/d/Y") ?></td>
                                                <td><?php echo $obj->sertifikat_NO."-".$obj->sertifikat_BLN."/UN26.33/TU.00.08/".date_format($date, "Y") ?></td>
                                                <td><?php echo $obj->tes_ke ?></td>
                                                <td><?php echo $obj->lis ?></td>
                                                <td><?php echo $obj->str ?></td>
                                                <td><?php echo $obj->rv ?></td>
                                                <td><?php echo $obj->skor ?></td>
                                             <!--   <td>
                                                   
                                                    <a data-toggle="modal" data-id="<?php echo $obj->ID."#$#$".$peserta->nama."#$#$".$peserta->ID ?>" class="passingID">
                                                       <button type="button" class="btn-transition btn btn-sm btn-outline-danger aksi" data-toggle="modal" data-target="#delPelanggaran">
                                                           <i class="nav-link-icon fa fa-trash"></i>
                                                       </button>
                                                    </a>
                                                    
                                                </td> -->
                                            </tr>
                                            <?php } ?>
                                            
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            
                        </div>
                        
                        <script>
                            $(document).on("click", ".passingID", function () {
                             var dataId = $(this).attr('data-id');
                             var data = dataId.split("#$#$");
                             $(".modal-body #pelanggaranID").val( data[0] );
                             $(".modal-body #pelanggaranJenis").text(data[3]);
                             $(".modal-body #pelanggaranpeserta").text(data[1]);
                             $(".modal-body #nrppeserta").val(data[2]);
                             //console.log("Tes");
                            });
                        </script>
                        
                        