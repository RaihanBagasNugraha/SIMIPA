<?php 
// $tb_admin_jur = $this->user_model->tugas_tendik_admin_jurusan($this->session->userdata('userId'));
// $tb_admin_fak = $this->user_model->tugas_tendik_admin_fakultas($this->session->userdata('userId'));
// $tb_admin_labor = $this->user_model->tugas_tendik_laboratorium_pranata($this->session->userdata('userId'));
// $tb_admin_kabag_tu = $this->user_model->tugas_tendik_kabag_tu($this->session->userdata('userId'));
// $tb_admin_kabag_tu = $this->user_model->tugas_tendik_kabag_tu($this->session->userdata('userId'));

$tugas = $this->user_model->tugas_tambahan_get($this->session->userdata('userId'));
foreach($tugas as $row){
    $tb[] = $row->tugas;
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
                                <!-- Menu Kabag TU Fakultas -->
                                <li class="app-sidebar__heading">Kabag Tata Usaha</li>
                                
                                

                                <li <?php if($this->uri->segment(2) == "layanan-fakultas") echo 'class="mm-active"' ?>>
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-display2"></i>
                                        Rekap Layanan Fakultas
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                       
                                        <li>
                                            <a href="#">
                                                <i class="metismenu-icon">
                                                </i>Akademik
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="metismenu-icon">
                                                </i>Umum & Keuangan
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="metismenu-icon">
                                                </i>Kemahasiswaan
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <?php } ?>

                                <?php if(in_array(11,$tb)){ ?>
                                <!-- Menu Admin Fakultas -->
                                
                                <li class="app-sidebar__heading">Admin Fakultas</li>
                                <li <?php if($this->uri->segment(2) == "") echo 'class="mm-active"' ?>>
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-note2"></i>
                                        Verifikasi Berkas
                                        
                                    </a>
                                    
                                </li>
                                <li <?php if($this->uri->segment(2) == "atribut-form") echo 'class="mm-active"' ?>>
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
                                    
                                </li>
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
                                        $lab_prn = $this->layanan_model->get_lab_pranata_user($this->session->userdata('userId'))->jurusan_unit;
                                        $jml_frm_prn = count($this->layanan_model->get_lab_pranata_form($lab_prn));

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
                                <!-- Menu Staf Ruang Baca -->
                                
                                <li class="app-sidebar__heading">Staf Ruang Baca</li>
                                <li <?php if($this->uri->segment(2) == "") echo 'class="mm-active"' ?>>
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-note2"></i>
                                        Verifikasi Bebas Ruang Baca
                                        
                                    </a>
                                    
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