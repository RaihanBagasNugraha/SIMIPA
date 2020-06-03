
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>eSertifikat - UPT Bahasa Universitas Lampung</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no"
    />
    <meta name="description" content="ArchitectUI HTML Bootstrap 4 Dashboard Template">

    <!-- Disable tap highlight on IE -->
    <meta name="msapplication-tap-highlight" content="no">

<link href="<?php echo base_url('main.css') ?>" rel="stylesheet"></head>

<body>
<div class="app-container app-theme-white body-tabs-shadow">
        <div class="app-container">
            <div class="h-100 bg-plum-plate bg-animation">
                <div class="d-flex h-100 justify-content-center align-items-center">
                    <div class="mx-auto app-login-box col-md-8">
                        <!--<div class="app-logo-inverse mx-auto mb-3"></div> -->
                        <center><img src="<?php echo site_url('assets/images/logo_unila.png') ?>" style="height: 150px;"/></center>
                        <div class="modal-dialog w-100 mx-auto">
                            
                            <div class="modal-content">
                                <div class="modal-header">
                                    e-sertifikat UPT Bahasa Universitas Lampung
                                </div>
                                <div class="modal-body">
                                    <?php if(isset($_GET['login']) && $_GET['login'] == 'gagal') { ?>
                                    <div class="alert alert-danger fade show" role="alert">Username atau password Anda salah — silakan cek kembali!</div>
                                    <?php } ?>
                                    <?php if(isset($_GET['access']) && $_GET['access'] == 'ditolak') { ?>
                                    <div class="alert alert-danger fade show" role="alert">Anda belum Login — silakan Login terlebih dahulu!</div>
                                    <?php } ?>
                                    <form id="login-form" class="" method="post" action="<?php echo site_url("periksa-akses") ?>">
                                        
                                        
                                        <div class="form-row">
                                            <div class="col-md-12">
                                                <div class="position-relative form-group"><input required name="username" id="username" placeholder="Username here..." type="text" class="form-control"></div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="position-relative form-group"><input required name="password" id="examplePassword" placeholder="Password here..." type="password" class="form-control"></div>
                                            </div>
                                        </div>
                                    </form>
                                    
                                </div>
                                <div class="modal-footer clearfix">
                                    <div class="float-left"></div>
                                    <div class="float-right">
                                        <button type="submit" form="login-form" class="btn btn-primary btn-lg"><span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-unlock-alt fa-w-20"></i>
                                            </span> Masuk ke Sistem</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center text-white opacity-8 mt-3">Copyright © 2020 UPT Bahasa Universitas Lampung | All Rights Reserved.<br>Template by ArchitectUI 2019</div>
                    </div>
                </div>
            </div>
        </div>
</div>
<script type="text/javascript" src="<?php echo base_url('assets/scripts/main.87c0748b313a1dda75f5.js') ?>"></script>
</html>
