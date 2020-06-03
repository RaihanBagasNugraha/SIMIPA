
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Login - Sistem Informasi Terpadu FMIPA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no"
    />
    <meta name="description" content="ArchitectUI HTML Bootstrap 4 Dashboard Template">

    <!-- Disable tap highlight on IE -->
    <meta name="msapplication-tap-highlight" content="no">

<link href="<?php echo base_url('main.css') ?>" rel="stylesheet"></head>

<body>
<div class="app-container app-theme-white body-tabs-shadow">
        <div class="app-container">
            <div class="h-100">
                <div class="h-100 no-gutters row">
                    <div class="h-100 d-md-flex d-sm-block bg-white justify-content-center align-items-center col-md-12 col-lg-7">
                        <div class="mx-auto app-login-box col-sm-12 col-md-10 col-lg-9">
                            <div style="margin-top: 8px;" class="app-logo"></div>
                            <h4>
                                <div>Selamat datang,</div>
                                <span>Luangkan waktu Anda <span class="text-success">beberapa detik</span> untuk membuat akun.</span></h4>
                                <?php if ($this->session->flashdata('success')) : ?>
                <div class="alert alert-success" role="alert">
                  <!-- <h4 class="alert-heading"></h4> -->
                  <p class="mb-0"><?php echo $this->session->flashdata('success'); ?></p>
                </div>
              <?php endif; ?>
              <?php if ($this->session->flashdata('error')) : ?>
                <div class="alert alert-danger" role="alert">
                  <!-- <h4 class="alert-heading"></h4> -->
                  <p class="mb-0"><?php echo $this->session->flashdata('error'); ?></p>
                </div>
              <?php endif; ?>
                            <div>
                                <form id="signupForm" class="mx-auto" method="post" action="<?php echo site_url('registrasi-akun') ?>">
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group"><label for="email" class=""><span class="text-danger">*</span> Email</label><input required name="email" id="email" placeholder="Ketik email..." type="email"
                                                                                                                                                                                class="form-control"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group"><label for="exampleName" class="">Nama Lengkap</label><input name="nama" id="exampleName" placeholder="Ketik nama..." type="text" class="form-control"></div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="position-relative form-group"><label for="password" class=""><span class="text-danger">*</span> Kata Sandi</label><input required  name="password" id="password" placeholder="Ketik kata sandi..."
                                                                                                                                                                                      type="password" class="form-control"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group"><label for="examplePasswordRep" class=""><span class="text-danger">*</span> Ulangi Kata Sandi</label><input required  name="confirm_password" id="confirm_password"
                                                                                                                                                                                                placeholder="Ketik kembali kata sandi..." type="password"
                                                                                                                                                                                                class="form-control"></div>
                                                                                                                                                                                                
                                        </div>
                                        <div class="col-md-4">
                                            <div class="position-relative form-group"><label for="exampleEmail" class=""><span class="text-danger">*</span> NPM</label><input required name="npm" id="npm" placeholder="Ketik NPM..." type="text"
                                                                                                                                                                                class="form-control input-mask-trigger" data-inputmask="'mask': '9999999999'" im-insert="true"></div>
                                        </div>
                                        <!--
                                        <div class="col-md-4">
                                            <div class="position-relative form-group"><label for="exampleCustomSelect" class="">Jurusan</label><select required type="select" id="exampleCustomSelect" name="jurusan" class="custom-select">
                                                            <option value="">Pilih Jurusan</option>
                                                            <?php 
                                                            foreach($list_jurusan as $row) { 
                                                                echo "<option value='$row->id_jurusan'>$row->nama</option>";
                                                            }
                                                            ?>
                                                            
                                                        </select></div>                                                                                                                                
                                        </div>
                                        -->
                                        <div class="col-md-4">
                                            <div class="position-relative form-group"><label for="exampleEmail" class=""><span class="text-danger">*</span> No HP/WA</label><input required name="hp" id="hp" placeholder="Ketik No HP..." type="text"
                                                                                                                                                                                class="form-control input-mask-trigger" data-inputmask="'alias': 'numeric', 'prefix': '+'" im-insert="true"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="position-relative form-group"><label for="exampleCustomSelect2" class="">Status</label><select required type="select" id="exampleCustomSelect2" name="status" class="custom-select">
                                                            <option value="3">Mahasiswa</option>
                                                            <option value="5">Alumni</option>
            
                                                        </select></div>                                                                                                                                
                                        </div>
                                        <?php
                                        $kode_unik = rand(100000,999999);
                                        ?>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group"><label for="exampleEmail" class="">Kode Unik</label><input name="kode_unik" disabled id="kode_unik" value="<?php echo $kode_unik ?>" readonly type="text"
                                                                                                                                                                                class="form-control"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group"><label for="exampleName" class=""><span class="text-danger">*</span> Ketik Kode Unik</label><input required name="re_kode_unik" id="re_kode_unik" placeholder="Ketik kembali kode unik..." type="text" class="form-control"></div>
                                        </div>
                                    </div>
                                    <div class="mt-4 d-flex align-items-center"><h5 class="mb-0">Sudah memiliki akun? <a href="<?php echo site_url() ?>" class="text-primary">Masuk ke sistem</a></h5>
                                        <div class="ml-auto">
                                            <button type="submit" class="btn-wide btn-pill btn-shadow btn-hover-shine btn btn-primary btn-lg">Buat Akun</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="d-lg-flex d-none d-xs-none col-lg-5">
                        <div class="slider-light">
                            <div class="slick-slider slick-initialized">
                                <div>
                                    <div class="position-relative h-100 d-flex justify-content-center align-items-center bg-premium-dark" tabindex="-1">
                                        <div class="slide-img-bg" style="background-image: url('assets/images/mipa.jpg');"></div>
                                        <div class="slider-content"><h3>Untuk FMIPA yang lebih baik</h3>
                                            <p>SIMIPA: Sistem Informasi Terpadu Fakultas Matematika dan Ilmu Pengetahuan Alam Universitas Lampung</p></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
<script type="text/javascript" src="assets/scripts/main.js"></script>
</html>
