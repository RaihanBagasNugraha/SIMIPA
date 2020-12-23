
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
                                        <!-- <div class="slider-content"><h3>Perfect Balance</h3>
                                            <p>ArchitectUI is like a dream. Some think it's too good to be true! Extensive collection of unified React Boostrap Components and Elements.</p></div> -->
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    
                    <div class="h-100 d-flex bg-white justify-content-center align-items-center col-md-12 col-lg-8">
                        <div class="mx-auto app-login-box col-sm-12 col-md-8 col-lg-6">
                        
                            <div class="app-logo"></div>
                            <h4>
                                <div>Lupa kata kunci Anda?</div>
                                <span>Ketik email untuk mengembalikan kata kunci Anda.</span></h4>
                            <div>
                            <?php if(isset($_GET['status']) && $_GET['status'] == 'gagal') { ?>
                                <div class="alert alert-danger fade show" role="alert">Email Salah Atau Tidak Terdaftar!</div>
                            <?php } if(isset($_GET['status']) && $_GET['status'] == 'kesalahan') { ?>
                                <div class="alert alert-danger fade show" role="alert">Terjadi Kesalahan Saat Mengirim Email</div>
                            <?php } if(isset($_GET['status']) && $_GET['status'] == 'sukses') { ?>
                                <div class="alert alert-success fade show" role="alert">Silahkan Cek Email</div>
                            <?php } ?>
                                <form class=""  method="post" action="<?php echo site_url('approval/lupa_password') ?>">
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <div class="position-relative form-group"><label for="exampleEmail" class="">Email</label><input name="email" id="exampleEmail" placeholder="Ketik Email Yang Telah Terdaftar" type="email" class="form-control" required></div>
                                        </div>
                                    </div>
                                    <div class="mt-4 d-flex align-items-center"><h6 class="mb-0"><a href="<?php echo site_url() ?>" class="text-primary">Masuk ke sistem</a></h6>
                                        <div class="ml-auto">
                                            <button class="btn btn-primary btn-lg">Kirim</button>
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
