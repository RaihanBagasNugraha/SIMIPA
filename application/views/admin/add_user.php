                        <div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-add-user icon-gradient bg-plum-plate">
                                        </i>
                                    </div>
                                    <div>Form Pengguna
                                        <div class="page-title-subheading"><?php if($this->uri->segment(1) == 'tambah-pengguna') echo 'Tambah Data'; else echo 'Ubah Data'; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="page-title-actions">
                                    <a href="<?php echo site_url('kelola-pengguna') ?>" class="btn-shadow btn btn-success">
                                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-arrow-left fa-w-20"></i>
                                            </span>
                                            KEMBALI
                                    </a>
                                </div>
                                
                            </div>
                        </div>
                        <?php
                            if($this->uri->segment(1) == 'tambah-pengguna') {
                                //$ID = "";
                                $username = "";
                                $password = "";
                                $nama = "";
                                $status = "";
                                $path_do = 'aksi-tambah-pengguna';
                            } else {
                                //$ID = $user->ID;
                                $username = $user->username;
                                $password = $user->password;
                                $nama = $user->nama_lengkap;
                                $status = $user->role;
                                $path_do = 'aksi-ubah-pengguna';
                            }
                            
                            ?>
                    
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="main-card mb-3 card">
                                    <div class="card-body">
                                        <form class="" method="post" action="<?php echo site_url($path_do) ?>" >
                                            <input type="hidden" name="username_lama" value="<?php echo $username ?>"/>
                                        
                                            
                                            <div class="position-relative row form-group"><label for="username" class="col-sm-2 col-form-label">Nama Akun</label>
                                                <div class="col-sm-6">
                                                    <input value="<?php echo $username ?>" required name="username" id="username" type="text" class="form-control">
                                                </div>
                                            </div>
                                            
                                            <div class="position-relative row form-group"><label for="password" class="col-sm-2 col-form-label">Kata Sandi</label>
                                                <div class="col-sm-6">
                                                    <input value="<?php echo $password ?>" required name="password" id="password" type="password" class="form-control">
                                                </div>
                                            </div>
                                            
                                            <div class="position-relative row form-group"><label for="name" class="col-sm-2 col-form-label">Nama Lengkap</label>
                                                <div class="col-sm-6">
                                                    <input value="<?php echo $nama ?>" required name="name" id="name" type="text" class="form-control">
                                                </div>
                                            </div>
                                            
                                            <div class="position-relative row form-group"><label for="state" class="col-sm-2 col-form-label">Status</label>
                                                <div class="col-sm-6">
                                                    <div class="position-relative form-check"><label class="form-check-label"><input <?php if($status == 'Operator') echo "checked" ?> name="state" type="radio" class="form-check-input" value="Operator"> Operator</label></div>
                                                    <div class="position-relative form-check"><label class="form-check-label"><input <?php if($status == 'Pimpinan') echo "checked" ?> name="state" type="radio" class="form-check-input" value="Pimpinan"> Pimpinan</label></div>
                                                    
                                                </div>
                                            </div>
                                            
                                            
                                            
                                            <div class="position-relative row form-group">
                                                <div class="col-sm-10 offset-sm-2">
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
                        