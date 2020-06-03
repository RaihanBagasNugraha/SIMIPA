                        <div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-hammer icon-gradient bg-deep-blue">
                                        </i>
                                    </div>
                                    <div>Form Pelanggaran Personil
                                        <div class="page-title-subheading"><?php if($this->uri->segment(1) == 'add-item-pelanggaran') echo 'Tambah Data'; else echo 'Ubah Data'; ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="page-title-actions">
                                    <a href="<?php echo site_url('ubah-pelanggaran/'.$this->uri->segment(2)) ?>" class="btn-shadow btn btn-success">
                                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-arrow-left fa-w-20"></i>
                                            </span>
                                            KEMBALI
                                    </a>
                                </div>
                                
                            </div>
                        </div>
                        
                    
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="main-card mb-3 card">
                                    <div class="card-body">
                                            
                                            <div class="position-relative row form-group">
                                                <label for="nrp" class="col-sm-2 col-form-label">NRP</label>
                                                <div class="col-sm-2">
                                                    <input value="<?php echo $personil->nrp ?>" required readonly name="nrp" id="nrp" type="text" class="form-control">
                                                </div>
                                                <label for="nama" class="col-sm-2 col-form-label">Nama Lengkap</label>
                                                <div class="col-sm-6">
                                                    <input value="<?php echo $personil->nama ?>" required readonly name="nama" id="nama" type="text" class="form-control">
                                                </div>
                                            </div>
                                            
                                            <div class="position-relative row form-group">
                                                <label for="pangkat" class="col-sm-2 col-form-label">Pangkat</label>
                                                <div class="col-sm-2">
                                                    <input value="<?php echo $personil->pangkat ?>" required readonly name="pangkat" id="pangkat" type="text" class="form-control">
                                                </div>
                                                <label for="jabatan" class="col-sm-2 col-form-label">Jabatan</label>
                                                <div class="col-sm-6">
                                                    <input value="<?php echo $personil->jabatan ?>" required readonly name="jabatan" id="jabatan" type="text" class="form-control">
                                                </div>
                                            </div>
      
                                    </div>
                                </div>
                            </div>
                            <?php
                            if($this->uri->segment(1) == 'add-item-pelanggaran') {
                                $ID = "";
                                $waktu = "";
                                $tempat = "";
                                $jenis_pelanggaran = "";
                                $jenis_hukuman = "";
                                $no_putusan = "";
                                $tgl_no_putusan = "";
                                $batas_waktu = "";
                                $keterangan = "";
                                $path_do = 'aksi-tambah-pelanggaran';
                            } else {
                                $ID = $pelanggaran->ID;
                                $waktu = $pelanggaran->waktu;
                                $tempat = $pelanggaran->tempat;
                                $jenis_pelanggaran = str_replace("<br />", "\n", $pelanggaran->jenis_pelanggaran);
                                $jenis_hukuman = str_replace("<br />", "\n", $pelanggaran->jenis_hukuman);
                                $no_putusan = $pelanggaran->no_putusan;
                                $tgl_no_putusan = $pelanggaran->tgl_no_putusan;
                                $batas_waktu = str_replace("<br />", "\n",$pelanggaran->batas_waktu);
                                $keterangan = str_replace("<br />", "\n",$pelanggaran->keterangan);
                                $path_do = 'aksi-ubah-pelanggaran';
                            }
                            
                            ?>
                            <div class="col-lg-12">
                                <div class="main-card mb-3 card">
                                    <div class="card-body">
                                        <form class="" method="post" action="<?php echo site_url($path_do) ?>">
                                            <input type="hidden" name="nrp" value="<?php echo $personil->nrp ?>"/>
                                            <input type="hidden" name="ID" value="<?php echo $ID ?>"/>

                                            <div class="position-relative row form-group"><label for="waktu" class="col-sm-3 col-form-label">Waktu</label>
                                                <div class="col-sm-6">
                                                    <input value="<?php echo $waktu ?>" required name="waktu" id="waktu" type="text" class="form-control">
                                                </div>
                                            </div>
                                            
                                            <div class="position-relative row form-group"><label for="tempat" class="col-sm-3 col-form-label">Tempat</label>
                                                <div class="col-sm-6">
                                                    <input value="<?php echo $tempat ?>" required name="tempat" id="tempat" type="text" class="form-control">
                                                </div>
                                            </div>
                                            
                                            <div class="position-relative row form-group"><label for="jenis" class="col-sm-3 col-form-label">Jenis Pelanggaran</label>
                                                <div class="col-sm-8">
                                                    <textarea rows="8" id="jenis" name="jenis" class="form-control"><?php echo $jenis_pelanggaran ?></textarea>
                                                </div>
                                            </div>
                                            
                                            <div class="position-relative row form-group"><label for="jenis_hukuman" class="col-sm-3 col-form-label">Jenis Hukuman</label>
                                                <div class="col-sm-8">
                                                    <textarea rows="8" id="jenis_hukuman" name="jenis_hukuman" class="form-control"><?php echo $jenis_hukuman ?></textarea>
                                                </div>
                                            </div>
                                            
                                            <div class="position-relative row form-group"><label for="no_putusan" class="col-sm-3 col-form-label"><b>No Putusan Hukuman</b></label>
                                                <div class="col-sm-6">
                                                    <input value="<?php echo $no_putusan ?>" required name="no_putusan" id="no_putusan" type="text" class="form-control">
                                                </div>
                                            </div>
                                            
                                            <div class="position-relative row form-group"><label for="tgl_no_putusan" class="col-sm-3 col-form-label"><b>Tanggal Putusan Hukuman</b></label>
                                                <div class="col-sm-6">
                                                    <input value="<?php echo $tgl_no_putusan ?>" required name="tgl_no_putusan" id="tgl_no_putusan" type="text" class="form-control">
                                                </div>
                                            </div>
                                            
                                            <div class="position-relative row form-group"><label for="batas" class="col-sm-3 col-form-label">Batas Waktu Pelaksanaan Hukuman</label>
                                                <div class="col-sm-8">
                                                    <textarea rows="5" id="batas" name="batas" class="form-control"><?php echo $batas_waktu ?></textarea>
                                                </div>
                                            </div>
                                            
                                            <div class="position-relative row form-group"><label for="ket" class="col-sm-3 col-form-label">Keterangan</label>
                                                <div class="col-sm-8">
                                                    <textarea rows="5" id="ket" name="ket" class="form-control"><?php echo $keterangan ?></textarea>
                                                </div>
                                            </div>
                                            
                                            
                                            <div class="position-relative row form-group">
                                                <div class="col-sm-9 offset-sm-3">
                                                    <button class="btn btn-primary">
                                                        <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-paper-plane fa-w-20"></i>
                                            </span>SIMPAN KE SERVER</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        