                        <div class="app-page-title">
                            <div class="page-title-wrapper">
                                
                                <div class="page-title-actions text-left">
                                    <b>NRP <?php echo $personil->nrp ?> (<?php echo $personil->pangkat ?>)</b><br>
                                    <?php echo $personil->nama ?><br>
                                    <?php echo $personil->jabatan ?><br>
                                    <a style="margin-top: 5px;" href="<?php echo site_url('pimpinan') ?>" class="btn-shadow btn btn-success ">
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
                        <script src="https://nightly.datatables.net/js/jquery.dataTables.js"></script> -->
                        
                        <?php if(!empty($_GET)) {
                        
                        ?>
                        <div class="row">
                                <?php
                                if(sizeof($list_pelanggaran) > 0) {
                                    foreach($list_pelanggaran as $row) {
                                        
                                ?>
                                <div class="col-md-12">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body"><h5 class="card-title">Putusan sidang KKEP nomor: (<?php echo $row->no_putusan  ?>)</h5>
                                                <ul class="list-group">
                                                    <li class="list-group-item-success list-group-item">Tanggal <?php echo $row->tgl_no_putusan  ?></li>
                                                    <li class="list-group-item-info list-group-item"><b>WAKTU & TEMPAT GAR</b><br><?php echo $row->waktu.", ".$row->tempat  ?></li>
                                                    <li class="list-group-item-warning list-group-item"><b>JENIS PELANGGARAN</b><br><?php echo $row->jenis_pelanggaran  ?></li>
                                                    <li class="list-group-item-danger list-group-item"><b>JENIS HUKUMAN</b><br><?php echo $row->jenis_hukuman  ?></li>
                                                    <li class="list-group-item-light list-group-item"><b>BATAS WAKTU</b><br><?php echo $row->batas_waktu ?></li>
                                                    <li class="list-group-item-primary list-group-item"><b>KETERANGAN</b><br><?php echo $row->keterangan  ?></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                    }
                                
                                }
                                
                                ?>
                                
                            </div>
                            
                        <?php } ?>
                       
                        
