<?php 
// $tb_admin_jur = $this->user_model->tugas_tendik_admin_jurusan($this->session->userdata('userId'));
// $tb_admin_fak = $this->user_model->tugas_tendik_admin_fakultas($this->session->userdata('userId'));
// $tb_admin_labor = $this->user_model->tugas_tendik_laboratorium_pranata($this->session->userdata('userId'));
// $tb_admin_kabag_tu = $this->user_model->tugas_tendik_kabag_tu($this->session->userdata('userId'));
// $tb_admin_kabag_tu = $this->user_model->tugas_tendik_kabag_tu($this->session->userdata('userId'));

$tugas = $this->user_model->tugas_tambahan_get($this->session->userdata('userId'));
if(empty($tugas)){
    $tb=array();
}
else{
foreach($tugas as $row){
    $tb[] = $row->tugas;
}
}

?>

<ul class="vertical-nav-menu">
                                <li class="app-sidebar__heading">Profil</li>
                                <li>
                                    <a href="<?php echo site_url('tendik/kelola-akun') ?>" <?php if($this->uri->segment(2) == "kelola-akun") echo 'class="mm-active"' ?> >
                                        <i class="metismenu-icon pe-7s-user"></i>
                                        Akun
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('tendik/kelola-biodata') ?>" <?php if($this->uri->segment(2) == "kelola-biodata") echo 'class="mm-active"' ?> >
                                        <i class="metismenu-icon pe-7s-id"></i>
                                        Biodata
                                    </a>
                                </li>

                                <?php if(in_array(5,$tb)){ ?>
                                <!-- kabag tu -->

                                <li class="app-sidebar__heading">Kabag Tata Usaha</li>
                                <li <?php if($this->uri->segment(2) == "approval" && ($this->uri->segment(3) == "kabag")) echo 'class="mm-active"' ?>>
                                    <a href="#">
                                    <?php 
                                        $lf_kabag = count($this->layanan_model->get_approval_struktural_fakultas($this->session->userdata('userId'),5));

                                    ?>
                                        <i class="metismenu-icon pe-7s-notebook"></i>
                                        Layanan Fakultas <span class="badge badge-danger"><?php echo $lf_kabag > 0 ? $lf_kabag : "" ?></span>
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                        <a href="<?php echo site_url("tendik/approval/kabag") ?>" <?php if($this->uri->segment(2) == "approval" && $this->uri->segment(3) == "kabag") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Form Pegajuan Layanan <span class="badge badge-danger"><?php echo $lf_kabag > 0 ? $lf_kabag : "" ?></span>
                                            </a>
                                        </li>

                                    </ul>
                                </li>
                                <?php } ?>

                                <?php if(in_array(6,$tb)){ ?>
                                <!-- kasubag akademik -->

                                <li class="app-sidebar__heading">Kasubbag Akademik</li>
                                <li <?php if($this->uri->segment(2) == "approval" && ($this->uri->segment(3) == "kasubag-akademik")) echo 'class="mm-active"' ?>>
                                    <a href="#">
                                    <?php 
                                        $lf_kasubag1 = count($this->layanan_model->get_approval_struktural_fakultas($this->session->userdata('userId'),6));

                                    ?>
                                        <i class="metismenu-icon pe-7s-notebook"></i>
                                        Layanan Fakultas <span class="badge badge-danger"><?php echo $lf_kasubag1 > 0 ? $lf_kasubag1 : "" ?></span>
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                        <a href="<?php echo site_url("tendik/approval/kasubag-akademik") ?>" <?php if($this->uri->segment(2) == "approval" && $this->uri->segment(3) == "kasubag-akademik") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Form Pegajuan Layanan <span class="badge badge-danger"><?php echo $lf_kasubag1 > 0 ? $lf_kasubag1 : "" ?></span>
                                            </a>
                                        </li>

                                    </ul>
                                </li>
                                <?php } ?>

                                <?php if(in_array(7,$tb)){ ?>
                                <!-- kasubag umum -->

                                <li class="app-sidebar__heading">Kasubbag Umum</li>
                                <li <?php if($this->uri->segment(2) == "approval" && ($this->uri->segment(3) == "kasubag-umum")) echo 'class="mm-active"' ?>>
                                    <a href="#">
                                    <?php 
                                        $lf_kasubag2 = count($this->layanan_model->get_approval_struktural_fakultas($this->session->userdata('userId'),7));

                                    ?>
                                        <i class="metismenu-icon pe-7s-notebook"></i>
                                        Layanan Fakultas <span class="badge badge-danger"><?php echo $lf_kasubag2 > 0 ? $lf_kasubag2 : "" ?></span>
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                        <a href="<?php echo site_url("tendik/approval/kasubag-umum") ?>" <?php if($this->uri->segment(2) == "approval" && $this->uri->segment(3) == "kasubag-umum") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Form Pegajuan Layanan <span class="badge badge-danger"><?php echo $lf_kasubag2 > 0 ? $lf_kasubag2 : "" ?></span>
                                            </a>
                                        </li>

                                    </ul>
                                </li>
                                <?php } ?>

                                <?php if(in_array(8,$tb)){ ?>
                                <!-- kasubag kepegawaian -->

                                <li class="app-sidebar__heading">Kasubbag Kepegawaian</li>
                                <li <?php if($this->uri->segment(2) == "approval" && ($this->uri->segment(3) == "kasubag-kepegawaian")) echo 'class="mm-active"' ?>>
                                    <a href="#">
                                    <?php 
                                        $lf_kasubag3 = count($this->layanan_model->get_approval_struktural_fakultas($this->session->userdata('userId'),8));

                                    ?>
                                        <i class="metismenu-icon pe-7s-notebook"></i>
                                        Layanan Fakultas <span class="badge badge-danger"><?php echo $lf_kasubag3 > 0 ? $lf_kasubag3 : "" ?></span>
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                        <a href="<?php echo site_url("tendik/approval/kasubag-kepegawaian") ?>" <?php if($this->uri->segment(2) == "approval" && $this->uri->segment(3) == "kasubag-kepegawaian") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Form Pegajuan Layanan <span class="badge badge-danger"><?php echo $lf_kasubag3 > 0 ? $lf_kasubag3 : "" ?></span>
                                            </a>
                                        </li>

                                    </ul>
                                </li>
                                <?php } ?>

                                <?php if(in_array(9,$tb)){ ?>
                                <!-- kasubag kemahasiswaan -->

                                <li class="app-sidebar__heading">Kasubbag Kemahasiswaan</li>
                                <li <?php if($this->uri->segment(2) == "approval" && ($this->uri->segment(3) == "kasubag-kemahasiswaan")) echo 'class="mm-active"' ?>>
                                    <a href="#">
                                    <?php 
                                        $lf_kasubag4 = count($this->layanan_model->get_approval_struktural_fakultas($this->session->userdata('userId'),9));

                                    ?>
                                        <i class="metismenu-icon pe-7s-notebook"></i>
                                        Layanan Fakultas <span class="badge badge-danger"><?php echo $lf_kasubag4 > 0 ? $lf_kasubag4 : "" ?></span>
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                        <a href="<?php echo site_url("tendik/approval/kasubag-kemahasiswaan") ?>" <?php if($this->uri->segment(2) == "approval" && $this->uri->segment(3) == "kasubag-kemahasiswaan") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Form Pegajuan Layanan <span class="badge badge-danger"><?php echo $lf_kasubag4 > 0 ? $lf_kasubag4 : "" ?></span>
                                            </a>
                                        </li>

                                    </ul>
                                </li>
                                <?php } ?>

                                <?php if(in_array(11,$tb)){ ?>
                                <!-- Menu Admin Fakultas -->
                                
                                <li class="app-sidebar__heading">Admin Fakultas</li>
                                <li <?php if($this->uri->segment(2) == "verifikasi-berkas-masuk-fakultas") echo 'class="mm-active"' ?>>
                                    <a href="#">
                                        <?php 
                                            $vbm_aka = count($this->layanan_model->get_approval_cek_tendik('Akademik'));
                                            $vbm_umum = count($this->layanan_model->get_approval_cek_tendik('Umum dan Keuangan'));
                                            $vbm_kms = count($this->layanan_model->get_approval_cek_tendik('Kemahasiswaan'));

                                            // $vbm_aka2 = count($this->layanan_model->get_approval_cek_tendik2('Akademik'));
                                            // $vbm_umum2 = count($this->layanan_model->get_approval_cek_tendik2('Umum dan Keuangan'));
                                            // $vbm_kms2 = count($this->layanan_model->get_approval_cek_tendik2('Kemahasiswaan'));

                                            $vbm = $vbm_aka + $vbm_umum + $vbm_kms;
                                            // $vbm_aka_tot =  $vbm_aka +  $vbm_aka2;
                                            // $vbm_umum_tot =  $vbm_umum +  $vbm_umum2;
                                            // $vbm_kms_tot =  $vbm_kms +  $vbm_kms2;
                                        ?>
                                        <i class="metismenu-icon pe-7s-note2"></i>
                                        Verifikasi Berkas Masuk <span class="badge badge-danger"><?php echo $vbm > 0 ? $vbm : "" ?></span>
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="<?php echo site_url("tendik/verifikasi-berkas-masuk-fakultas/akademik") ?>" <?php if($this->uri->segment(2) == "verifikasi-berkas-masuk-fakultas" && $this->uri->segment(3) == "akademik") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon pe-7s-note2"></i>
                                                Akademik <span class="badge badge-danger"><?php echo $vbm_aka > 0 ? $vbm_aka : "" ?></span>
                                            </a>
                                        </li> 
                                        <li>
                                            <a href="<?php echo site_url("tendik/verifikasi-berkas-masuk-fakultas/umum-keuangan") ?>" <?php if($this->uri->segment(2) == "verifikasi-berkas-masuk-fakultas" && $this->uri->segment(3) == "umum-keuangan") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon pe-7s-note2"></i>
                                                Umum dan Keuangan <span class="badge badge-danger"><?php echo $vbm_umum > 0 ? $vbm_umum : "" ?></span>
                                            </a>
                                        </li> 
                                        <li>
                                            <a href="<?php echo site_url("tendik/verifikasi-berkas-masuk-fakultas/kemahasiswaan") ?>" <?php if($this->uri->segment(2) == "verifikasi-berkas-masuk-fakultas" && $this->uri->segment(3) == "kemahasiswaan") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon pe-7s-note2"></i>
                                                Kemahasiswaan <span class="badge badge-danger"><?php echo $vbm_kms > 0 ? $vbm_kms : "" ?></span>
                                            </a>
                                        </li> 
                                    </ul>
                                    
                                </li>
                                <li <?php if($this->uri->segment(2) == "verifikasi-berkas-fakultas") echo 'class="mm-active"' ?>>
                                    <a href="#">
                                        <?php 
                                            $vbk_aka = count($this->layanan_model->get_approval_keluar_tendik('Akademik'));
                                            $vbk_umum = count($this->layanan_model->get_approval_keluar_tendik('Umum dan Keuangan'));
                                            $vbk_kms = count($this->layanan_model->get_approval_keluar_tendik('Kemahasiswaan'));

                                            $vbk = $vbk_aka + $vbk_umum + $vbk_kms;
                                        ?>
                                        <i class="metismenu-icon pe-7s-note2"></i>
                                        Verifikasi Berkas Keluar <span class="badge badge-danger"><?php echo $vbk > 0 ? $vbk : "" ?></span>
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="<?php echo site_url("tendik/verifikasi-berkas-fakultas/akademik") ?>" <?php if($this->uri->segment(2) == "verifikasi-berkas-fakultas" && $this->uri->segment(3) == "akademik") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon pe-7s-note2"></i>
                                                Akademik <span class="badge badge-danger"><?php echo $vbk_aka > 0 ? $vbk_aka : "" ?></span>
                                            </a>
                                        </li> 
                                        <li>
                                            <a href="<?php echo site_url("tendik/verifikasi-berkas-fakultas/umum-keuangan") ?>" <?php if($this->uri->segment(2) == "verifikasi-berkas-fakultas" && $this->uri->segment(3) == "umum-keuangan") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon pe-7s-note2"></i>
                                                Umum dan Keuangan <span class="badge badge-danger"><?php echo $vbk_umum > 0 ? $vbk_umum : "" ?></span>
                                            </a>
                                        </li> 
                                        <li>
                                            <a href="<?php echo site_url("tendik/verifikasi-berkas-fakultas/kemahasiswaan") ?>" <?php if($this->uri->segment(2) == "verifikasi-berkas-fakultas" && $this->uri->segment(3) == "kemahasiswaan") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon pe-7s-note2"></i>
                                                Kemahasiswaan <span class="badge badge-danger"><?php echo $vbk_kms > 0 ? $vbk_kms : "" ?></span>
                                            </a>
                                        </li> 
                                    </ul>
                                    
                                </li>
                                <!-- <li <?php if($this->uri->segment(2) == "atribut-form") echo 'class="mm-active"' ?>>
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-note"></i>
                                        <?php 
                                        ?>
                                        Atribut Form Layanan
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="<?php echo site_url("tendik/atribut-form/atribut") ?>" <?php if($this->uri->segment(2) == "atribut-form" && $this->uri->segment(3) == "atribut") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon pe-7s-note2"></i>
                                                Tambah Atribut
                                            </a>
                                        </li> 
                                    </ul>
                                    
                                </li> -->
                                <?php } ?>

                                <?php if(in_array(18,$tb)){ ?>
                                <!-- Menu Admin Jurusan -->
                                
                                <li class="app-sidebar__heading">Admin Jurusan</li>
                                <li <?php if($this->uri->segment(2) == "verifikasi-berkas") echo 'class="mm-active"' ?>>
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-note"></i>
                                        <?php 
                                            $ta_admin = count($this->ta_model->get_verifikasi_berkas($this->session->userdata('userId')));
                                            $smr_admin = count($this->ta_model->get_verifikasi_berkas_seminar($this->session->userdata('userId')));
                                            $kp_smr_admin = count($this->pkl_model->get_seminar_tendik($this->session->userdata('userId')));


                                            $berkas = $ta_admin + $smr_admin + $kp_smr_admin;
                                        
                                        ?>
                                        Verifikasi Berkas <span class="badge badge-danger"><?php echo $berkas > 0 ? $berkas : "" ?></span>
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="<?php echo site_url("tendik/verifikasi-berkas") ?>" <?php if($this->uri->segment(2) == "verifikasi-berkas" && $this->uri->segment(3) != "seminar" && $this->uri->segment(3) != "pkl" && $this->uri->segment(3) != "seminar-pkl") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon pe-7s-note2"></i>
                                                Tema <span class="badge badge-danger"><?php echo $ta_admin > 0 ? $ta_admin : "" ?></span>
                                                
                                            </a>
                                        </li> 
                                        <li>
                                            <a href="<?php echo site_url("tendik/verifikasi-berkas/seminar") ?>" <?php if($this->uri->segment(2) == "verifikasi-berkas" && $this->uri->segment(3) == "seminar") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon pe-7s-note2"></i>
                                                Seminar <span class="badge badge-danger"><?php echo $smr_admin > 0 ? $smr_admin : "" ?></span>
                                                
                                            </a>
                                        </li>    
                                        <li>
                                            <a href="<?php echo site_url("tendik/verifikasi-berkas/pkl") ?>" <?php if($this->uri->segment(2) == "verifikasi-berkas" && $this->uri->segment(3) == "pkl") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon pe-7s-note2"></i>
                                                KP/PKL
                                                <!-- KP/PKL <span class="badge badge-danger"><?php echo $kp_admin > 0 ? $kp_admin : "" ?></span> -->
                                                
                                            </a>
                                        </li>    
                                        <li>
                                            <a href="<?php echo site_url("tendik/verifikasi-berkas/seminar-pkl") ?>" <?php if($this->uri->segment(2) == "verifikasi-berkas" && $this->uri->segment(3) == "seminar-pkl") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon pe-7s-note2"></i>
                                                Seminar KP/PKL <span class="badge badge-danger"><?php echo $kp_smr_admin > 0 ? $kp_smr_admin : "" ?></span>
                                                <!-- KP/PKL <span class="badge badge-danger"></span> -->
                                                
                                            </a>
                                        </li>    
                                    </ul>
                                    
                                </li>
                                <?php } ?>
                                
                                <?php if(in_array(16,$tb)){ ?>
                                <!-- Menu Staf Laboran -->
                                
                                <li class="app-sidebar__heading">Staf Laboran</li>
                                <li <?php if($this->uri->segment(2) == "bebas-lab") echo 'class="mm-active"' ?>>
                                    <a href="#">
                                    <?php 
                                        $lab_prn = $this->layanan_model->get_lab_pranata_user($this->session->userdata('userId'));
                                        
                                        // foreach($lab_prn as $lbp){
                                            $jml_frm_prn = count($this->layanan_model->get_lab_pranata_form($this->session->userdata('userId')));
                                        // }
                                        // $jml_frm_prn = 0;

                                        $verifikasi_pranata = $jml_frm_prn;
                                    
                                    ?>
                                        <i class="metismenu-icon pe-7s-note2"></i>
                                        Verifikasi Bebas Lab  <span class="badge badge-danger"><?php echo $verifikasi_pranata > 0 ? $verifikasi_pranata : "" ?></span>
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="<?php echo site_url("tendik/bebas-lab/pengajuan") ?>" <?php if($this->uri->segment(2) == "bebas-lab" && $this->uri->segment(3) == "pengajuan") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon pe-7s-note2"></i>
                                                Pengajuan Bebas Lab  <span class="badge badge-danger"><?php echo $jml_frm_prn > 0 ? $jml_frm_prn : "" ?></span>
                                            </a>
                                        </li>    
                                    </ul>
                                </li>
                                <?php } ?>

                                <?php if(in_array(20,$tb)){ ?>
                                <!-- petugas ruang baca -->

                                <li class="app-sidebar__heading">Petugas Perpustakaan</li>
                                <li <?php if($this->uri->segment(2) == "approval" && $this->uri->segment(3) == "perpustakaan") echo 'class="mm-active"' ?>>
                                    <a href="#">
                                    <?php 
                                        $lf_perpus = count($this->layanan_model->get_approval_perpustakaan_fakultas($this->session->userdata('userId')));
                                    ?>
                                        <i class="metismenu-icon pe-7s-notebook"></i>
                                        Layanan Akademik <span class="badge badge-danger"><?php echo $lf_perpus > 0 ? $lf_perpus : "" ?></span>
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                        <a href="<?php echo site_url("tendik/approval/perpustakaan") ?>" <?php if($this->uri->segment(2) == "approval" && $this->uri->segment(3) == "perpustakaan") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Pengajuan Bebas Ruang Baca <span class="badge badge-danger"><?php echo $lf_perpus > 0 ? $lf_perpus : "" ?></span>
                                            </a>
                                        </li>

                                    </ul>
                                </li>
                                <?php } ?>

                                <?php if(in_array(21,$tb)){ ?>
                                <!-- petugas akademik -->

                                <li class="app-sidebar__heading">Petugas Akademik</li>
                                <li <?php if($this->uri->segment(2) == "approval" && $this->uri->segment(3) == "petugas-akademik") echo 'class="mm-active"' ?>>
                                    <a href="#">
                                    <?php 
                                        $lf_pt_aka = count($this->layanan_model->get_approval_pt_akademik_fakultas($this->session->userdata('userId')));
                                    ?>
                                        <i class="metismenu-icon pe-7s-notebook"></i>
                                        Layanan Akademik <span class="badge badge-danger"><?php echo $lf_pt_aka > 0 ? $lf_pt_aka : "" ?></span>
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                        <a href="<?php echo site_url("tendik/approval/petugas-akademik") ?>" <?php if($this->uri->segment(2) == "approval" && $this->uri->segment(3) == "petugas-akademik") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Pengajuan Bebas Ruang Baca <span class="badge badge-danger"><?php echo $lf_pt_aka > 0 ? $lf_pt_aka : "" ?></span>
                                            </a>
                                        </li>

                                    </ul>
                                </li>

                                    
                                <?php } ?>

                               
                                <div class="divider"></div>
                                <li>
                                    <a href="<?php echo site_url('keluar-sistem') ?>">
                                        <i class="metismenu-icon pe-7s-power"></i>
                                        Keluar Sistem
                                    </a>
                                </li>
                               


                            </ul>
                        </div> <!-- app-sidebar__inner -->
                    </div> <!-- scrollbar-sidebar -->
                </div> <!-- app-sidebar sidebar-shadow -->
                
            <div class="app-main__outer">
                <div class="app-main__inner">