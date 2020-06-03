                        <div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-users icon-gradient bg-love-kiss">
                                        </i>
                                    </div>
                                    <div>Kelola Pengguna
                                        <div class="page-title-subheading">Data pengguna sistem: Admin, Operator, Pimpinan.
                                        </div>
                                    </div>
                                </div>
                                <div class="page-title-actions">
                                    <a href="<?php echo site_url('tambah-pengguna') ?>" class="btn-shadow btn btn-success">
                                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-user-plus fa-w-20"></i>
                                            </span>
                                            TAMBAH PENGGUNA
                                    </a>
                                </div>
                            </div>
                        </div>
                       
                        <script src="https://code.jquery.com/jquery-3.1.1.js"></script>
<!-- 
                        <link href="https://nightly.datatables.net/css/jquery.dataTables.css" rel="stylesheet" type="text/css" />
                        <script src="https://nightly.datatables.net/js/jquery.dataTables.js"></script> -->
                        <style>
                            table.data-table thead th {
                              vertical-align: middle;
                              padding: 10px 4px 10px 3px;
                            }
                            
                            table.data-table tfoot th {
                              vertical-align: top;
                              padding: 10px 4px 10px 3px;
                            }
                            
                            table.data-table tbody th {
                              vertical-align: top;
                              text-align: right;
                              font-size: 10pt;
                              padding: 5px 4px 5px 3px;
                            }
                            
                            table.data-table tbody td {
                              vertical-align: top;
                              font-size: 10pt;
                              padding: 5px 4px 5px 3px;
                            }
                            
                            .aksi {
                                margin-bottom: 3px;
                            }
                            
                        </style>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="main-card mb-3 card">
                                    <div class="card-body">
                                        <table id="example" class="mb-0 table table-hover data-table table-responsive w-100 d-block d-md-table table-striped">
                                            <thead>
                                            <tr>
                                                <th style="width: 5%;">#</th>
                                                <th width="18%">Username</th>
                                                <th width="30%">Password</th>
                                                <th width="25%">Nama Lengkap</th>
                                                <th width="12%">Status</th>
                                                <th width="10%">Aksi</th>
                                            </tr>
                                            </thead>
                                            
                                            <tbody>
                                            <?php
                                            $no = 0;
                                            foreach($list_user as $obj) : 
                                                $no += 1;
                                            ?>    
                                            <tr>
                                                <th><?php echo $no ?></th>
                                                <td><?php echo $obj->username ?></td>
                                                <td><?php echo $obj->password ?></td>
                                                <td><?php echo $obj->nama_lengkap ?></td>
                                                <td><?php echo $obj->role ?></td>
                                                <td>
                                                    <a href="<?php echo site_url('ubah-pengguna/'.$obj->username) ?>" class="btn-transition btn btn-sm btn-outline-info aksi">
                                                        <i class="nav-link-icon fa fa-edit"></i>
                                                    </a> 
                                                 <!--   <a href="#" data-id="<?php echo $obj->nama_lengkap."#".$obj->username ?>" data-toggle="modal" data-target=".bd-example-modal-sm" class="btn-transition btn btn-sm btn-outline-danger aksi feed-id">
                                                        <i class="nav-link-icon fa fa-trash"></i>
                                                    </a> -->
                                                    <a data-toggle="modal" data-id="<?php echo $obj->username."#".$obj->nama_lengkap ?>" class="passingID">
                                                       <button type="button" class="btn-transition btn btn-sm btn-outline-danger aksi" data-toggle="modal" data-target="#delUser">
                                                           <i class="nav-link-icon fa fa-trash"></i>
                                                       </button>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php
                                            endforeach;
                                            ?>
                                            
                                            
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script>
                            $(document).on("click", ".passingID", function () {
                             var dataId = $(this).attr('data-id');
                             var data = dataId.split("#");
                             $(".modal-body #userID").val( data[0] );
                             $(".modal-body #userNama").text(data[1]);
                             //console.log("Tes");
                            });
                        </script>
                        
