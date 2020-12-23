
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
                    <div class="d-none d-lg-block col-lg-4">
                        <div class="slider-light">
                            <div class="slick-slider">
                                <div>
                                    <div class="position-relative h-100 d-flex justify-content-center align-items-center bg-plum-plate" tabindex="-1">
                                        <div class="slide-img-bg" style="background-image: url('assets/images/mipa.jpg');"></div>
                                        <div class="slider-content"><h3>Untuk FMIPA yang lebih baik</h3>
                                            <p>SIMIPA: Sistem Informasi Terpadu Fakultas Matematika dan Ilmu Pengetahuan Alam Universitas Lampung</p></div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <div class="h-100 d-flex bg-white justify-content-center align-items-center col-md-12 col-lg-8">
                        <div class="mx-auto app-login-box col-sm-12 col-md-10 col-lg-9">
                            <div class="app-logo"></div>
                            <h4 class="mb-0">
                                <span class="d-block">Selamat datang kembali,</span>
                                <span>Silakan masuk ke sistem menggunakan akun Anda.</span></h4>
                            <h6 class="mt-3">Belum punya akun (mahasiswa/alumni)? <a href="<?php echo site_url('registrasi') ?>" class="text-primary">Daftar sekarang</a></h6>
                            <?php if(isset($_GET['login']) && $_GET['login'] == 'gagal') { ?>
                                    <div class="alert alert-danger fade show" role="alert">Akun Anda belum diverifikasi atau Username/password salah — silakan cek kembali!</div>
                                    <?php } ?>
                                    <?php if(isset($_GET['access']) && $_GET['access'] == 'ditolak') { ?>
                                    <div class="alert alert-danger fade show" role="alert">Anda belum Login — silakan Login terlebih dahulu!</div>
                                    <?php }if(isset($_GET['verifikasi']) && $_GET['verifikasi'] == 'sukses') { ?>
                                        <div class="alert alert-success fade show" role="alert">Berhasil Verifikasi Akun!</div>
                                    <?php }?>
                                    <?php if(isset($_GET['reset']) && $_GET['reset'] == 'sukses') { ?>
                                        <div class="alert alert-success fade show" role="alert">Password Berhasil Diubah</div>
                                    <?php }?>
                            <div class="divider row"></div>
                            <div>
                                <form class="" method="post" action="<?php echo site_url('periksa-akses') ?>">
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group"><label for="exampleEmail" class="">Akun</label><input required name="username" id="exampleEmail" placeholder="Ketik NPM/NIP/NIK..." type="text" class="form-control">
                                            <small class="form-text text-muted">NPM (untuk mahasiswa), NIP (dosen dan staf PNS),<br>NIK (dosen dan staf Non-PNS)</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group"><label for="examplePassword" class="">Kata kunci</label><input required name="password" id="examplePassword" placeholder="Ketik Kata kunci..." type="password"
                                                                                                                                                   class="form-control"></div>
                                        </div>
                                    </div>
                                    <div class="divider row"></div>
                                    <div class="d-flex align-items-center">
                                        <div class="ml-auto"><a href="<?php echo site_url('lupa-kata-kunci') ?>" class="btn-lg btn btn-link">Lupa kata kunci</a>
                                            <button type="submit" class="btn btn-primary btn-lg">Masuk ke Sistem</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
<script type="text/javascript" src="assets/scripts/main.js"></script>
</html>
