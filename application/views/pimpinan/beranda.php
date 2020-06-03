                        <div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-users icon-gradient bg-love-kiss">
                                        </i>
                                    </div>
                                    <div>Pencarian Pelanggaran Personil
                                        <div class="page-title-subheading">Silakan masukan NRP/Nama/Pangkat/Jabatan
                                        </div>
                                    </div>
                                </div>
                                <div class="page-title-actions">
                                    <form id="search-form" class="" method="post" action="">
                                        
                                        
                                        <div class="form-row">
                                            <div class="col-md-12">
                                                <div class="position-relative form-group"><input required name="keyword" id="keyword" placeholder="Ketik disini..." type="text" class="form-control"></div>
                                            </div>
                                            <div class="col-md-12">
                                                <button for="search-form" type="submit" class="btn-shadow btn btn-lg btn-primary btn-block">
                                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-search fa-w-20"></i>
                                            </span>
                                            MULAI CARI
                                            </button>    
                                            </div>
                                            </div>
                                        
                                    </form>
                                </div>
                            </div>
                        </div>
                      
                        <script src="https://code.jquery.com/jquery-3.1.1.js"></script>
 <!-- 
                        <link href="https://nightly.datatables.net/css/jquery.dataTables.css" rel="stylesheet" type="text/css" />
                        <script src="https://nightly.datatables.net/js/jquery.dataTables.js"></script> -->
                        
                        <?php if(!empty($_POST)) {
                        
                        ?>
                        <div class="row">
                                <?php
                                if(sizeof($list_search) > 0) {
                                    foreach($list_search as $row) {
                                ?>
                                <div id="<?php echo $row->nrp ?>" class="col-lg-6 col-xl-4 pnl">
                                    <div class="card mb-3 widget-content">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left">
                                                <div class="widget-heading">NRP <?php echo $row->nrp." (".$row->pangkat.")" ?></div>
                                                <div class="widget-subheading"><?php echo $row->nama ?></div>
                                            </div>
                                            <div class="widget-content-right">
                                                <?php
                                                $clr = "";
                                                if($row->jum == 0) $clr = "text-success";
                                                elseif($row->jum == 1) $clr = "text-primary";
                                                elseif($row->jum == 2) $clr = "text-info";
                                                elseif($row->jum == 3) $clr = "text-warning";
                                                else $clr = "text-danger";
                                                ?>
                                                <div class="widget-numbers <?php echo $clr ?>"><span><?php echo $row->jum ?></span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                    }
                                
                                }
                                
                                ?>
                                
                            </div>
                            
                        <?php } ?>
                        <script>
                            $(document).on("click", ".pnl", function () {
                             var nrp = $(this).attr('id');
                             location.href = "<?php echo site_url("pimpinan/detail_search/?nrp=") ?>" + nrp;
                             //console.log("Tes");
                            });
                        </script>
                        
