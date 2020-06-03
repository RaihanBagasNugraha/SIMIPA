                        <div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-server icon-gradient bg-happy-itmeo">
                                        </i>
                                    </div>
                                    <div>Rekapitulasi <?php
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

                                        echo $unit_kerja;
                                    ?>
                                        <div class="page-title-subheading">Daftar Peserta Tes TOEFL
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
                        
              <!--          <script src="https://code.jquery.com/jquery-3.1.1.js"></script>

    <link href="https://nightly.datatables.net/css/jquery.dataTables.css" rel="stylesheet" type="text/css" />
    <script src="https://nightly.datatables.net/js/jquery.dataTables.js"></script>-->
    
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
                                                <th width="5%">#</th>
                                                <th width="20%">Tahun Angkatan</th>
                                                <th width="15%">Jumlah Mahasiswa</th>
                                               <!-- <th width="15%">Maksimum</th>
                                                <th width="15%">Rata-rata</th>
                                                <th width="15%">Minimum</th> -->
                                                <th width="15%">Aksi</th>
                                            </tr>
                                            </thead>
                                            <tfoot>
                                            <tr>
                                                <th>#</th>
                                                <th>Tahun Angkatan</th>
                                                <th>Jumlah Mahasiswa</th>
                                             <!--   <th>Maksimum</th>
                                                <th>Rata-rata</th>
                                                <th>Minimum</th> -->
                                                <th>Aksi</th>
                                            </tr>
                                            </tfoot>
                                            <tbody>
                                            <?php
                                            $no = 0;
                                            foreach($list as $obj) {
                                                $no++;
                                            ?>
                                            <tr>
                                                <td><?php echo $no ?></td>
                                                <td><?php echo "20".$obj->angkatan ?> </td>
                                                <td><?php echo $obj->jumlah ?> </td>
                                            <!--    <td><?php echo number_format($obj->max_skor, 2, ',', '.') ?> </td>
                                                <td><?php echo number_format($obj->avg_skor, 2, ',', '.') ?> </td>
                                                <td><?php echo number_format($obj->min_skor, 2, ',', '.') ?> </td> -->
                                                <td>
                                                    <a href="<?php echo site_url("data-peserta/".$this->uri->segment(2)."/".$obj->angkatan) ?>" class="border-0 btn-transition btn btn-outline-primary btn-sm">
                                                    Detail </i>
                                            </a>
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
                        
                    