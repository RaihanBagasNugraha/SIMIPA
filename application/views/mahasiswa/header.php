<?php
$strata = substr($this->session->userdata('username'), 2, 1);
$strata_ta = "";
switch($strata)
{
    case 0: $strata_ta = "Tugas Akhir"; break;
    case 1:
    case 5: $strata_ta = "Skripsi"; break;
    case 2: $strata_ta = "Tesis"; break;
    case 3: $strata_ta = "Disertasi"; break;
}

//cek lk
$lk = $this->user_model->check_lk_mahasiswa($this->session->userdata('userId'));

$biodata = $this->user_model->get_mahasiswa_data($this->session->userdata('userId'));
?>
<ul class="vertical-nav-menu">
                                <li class="app-sidebar__heading">Profil</li>
                                <li>
                                    <a href="<?php echo site_url('mahasiswa/kelola-akun') ?>" <?php if($this->uri->segment(2) == "kelola-akun") echo 'class="mm-active"' ?> >
                                        <i class="metismenu-icon pe-7s-user"></i>
                                        Akun
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('mahasiswa/kelola-biodata') ?>" <?php if($this->uri->segment(2) == "kelola-biodata") echo 'class="mm-active"' ?> >
                                        <i class="metismenu-icon pe-7s-id"></i>
                                        Biodata
                                    </a>
                                </li>
                                <li class="app-sidebar__heading">Menu</li>
                                <li <?php if($this->uri->segment(2) == "layanan-fakultas" || $this->uri->segment(2) == "layanan-lacak") echo 'class="mm-active"' ?>>
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-display2"></i>
                                        Layanan Fakultas
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="<?php echo site_url('mahasiswa/layanan-lacak') ?>" <?php if($this->uri->segment(2) == "layanan-lacak") echo 'class="mm-active"' ?>  >
                                                <i class="metismenu-icon">
                                                </i>Lacak
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url('mahasiswa/layanan-fakultas/akademik') ?>" <?php if($this->uri->segment(2) == "layanan-fakultas" && $this->uri->segment(3) == "akademik" && $this->uri->segment(4) != "bebas-lab") echo 'class="mm-active"' ?> >
                                                <i class="metismenu-icon">
                                                </i>Akademik
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url('mahasiswa/layanan-fakultas/umum-keuangan') ?>" <?php if($this->uri->segment(2) == "layanan-fakultas" && $this->uri->segment(3) == "umum-keuangan") echo 'class="mm-active"' ?> >
                                                <i class="metismenu-icon">
                                                </i>Umum & Keuangan
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url('mahasiswa/layanan-fakultas/kemahasiswaan') ?>" <?php if($this->uri->segment(2) == "layanan-fakultas" && $this->uri->segment(3) == "kemahasiswaan") echo 'class="mm-active"' ?> >
                                                <i class="metismenu-icon">
                                                </i>Kemahasiswaan
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url('mahasiswa/layanan-fakultas/akademik/bebas-lab') ?>" <?php if($this->uri->segment(2) == "layanan-fakultas" && $this->uri->segment(4) == "bebas-lab") echo 'class="mm-active"' ?> >
                                                <i class="metismenu-icon">
                                                </i>Bebas Laboratorium
                                            </a>
                                        </li>
                                        
                                    </ul>
                                </li>


                                <?php if($strata < 2) { ?>
                                    <li <?php if($this->uri->segment(2) == "pkl") echo 'class="mm-active"' ?>>
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-note2"></i>
                                        Manajemen KP/PKL
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="<?php echo site_url('mahasiswa/pkl/pkl-home') ?>" <?php if($this->uri->segment(1) == "mahasiswa" && $this->uri->segment(2) == "pkl" && $this->uri->segment(3) == "pkl-home") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>KP/PKL
                                            </a>
                                        </li>
                                        <!-- <li>
                                            <a href="#">
                                                <i class="metismenu-icon">
                                                </i>Bimbingan
                                            </a>
                                        </li> -->
                                        <li>
                                            <a href="<?php echo site_url('mahasiswa/pkl/seminar') ?>" <?php if($this->uri->segment(1) == "mahasiswa" && $this->uri->segment(2) == "pkl" && $this->uri->segment(3) == "seminar") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Seminar
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <?php } ?>

                                <li <?php if($this->uri->segment(2) == "tugas-akhir") echo 'class="mm-active"' ?>>
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-note"></i>
                                        <?php echo "Manajemen ".$strata_ta ?>
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="<?php echo site_url("mahasiswa/tugas-akhir/tema") ?>" <?php if($this->uri->segment(2) == "tugas-akhir" && $this->uri->segment(3) == "tema") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Tema
                                            </a>
                                        </li>
                                        <!-- <li>
                                            <a href="<?php //echo site_url("mahasiswa/tugas-akhir/bimbingan") ?>" <?php //if($this->uri->segment(2) == "tugas-akhir" && $this->uri->segment(3) == "bimbingan") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Bimbingan
                                            </a>
                                        </li> -->
                                        <?php if($biodata->jurusan == "5" && $biodata->prodi == "11" ){ ?>
                                        <li>
                                        <a href="<?php echo site_url("mahasiswa/tugas-akhir/verifikasi-ta") ?>" <?php if($this->uri->segment(2) == "tugas-akhir" && $this->uri->segment(3) == "verifikasi-ta") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Verifikasi Program TA
                                            </a>
                                        </li>
                                        <?php } ?>
                                        <li>
                                        <a href="<?php echo site_url("mahasiswa/tugas-akhir/seminar") ?>" <?php if($this->uri->segment(2) == "tugas-akhir" && $this->uri->segment(3) == "seminar") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Seminar/Sidang
                                            </a>
                                        </li>
                                       
                                    </ul>
                                </li>
                                
                                <li>
                                    <a href="<?php echo site_url('mahasiswa/prestasi') ?>" <?php if($this->uri->segment(2) == "prestasi") echo 'class="mm-active"' ?> >
                                        <i class="metismenu-icon pe-7s-star"></i>
                                        Prestasi
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('mahasiswa/beasiswa') ?>" <?php if($this->uri->segment(2) == "beasiswa") echo 'class="mm-active"' ?> >
                                        <i class="metismenu-icon pe-7s-cash"></i>
                                        Beasiswa
                                    </a>
                                </li>

                                <!-- Menu Lembaga Kemahasiswaan -->
                                <?php if(!empty($lk)) {  ?>
                                <li class="app-sidebar__heading">Lembaga Kemahasiswaan</li>
                                <li>
                                    <a href="<?php echo site_url('mahasiswa/struktur-organisasi') ?>" <?php if($this->uri->segment(2) == "struktur-organisasi") echo 'class="mm-active"' ?> >
                                        <i class="metismenu-icon pe-7s-network"></i>
                                        Struktur Organisasi
                                    </a>
                                </li>

                                <li <?php if($this->uri->segment(2) == "proposal-kegiatan" || $this->uri->segment(2) == "laporan-kegiatan" ) echo 'class="mm-active"' ?>>
                                <a href="#"  >
                                        <i class="metismenu-icon pe-7s-notebook"></i>
                                        Program Kerja
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="<?php echo site_url('mahasiswa/proposal-kegiatan') ?>" <?php if($this->uri->segment(2) == "proposal-kegiatan") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Proposal Kegiatan
                                            </a>
                                        </li>
                                        <li>
                                        <a href="<?php echo site_url('mahasiswa/laporan-kegiatan') ?>" <?php if($this->uri->segment(2) == "laporan-kegiatan") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Laporan Kegiatan
                                            </a>
                                        </li>
                                        
                                       
                                    </ul>
                                </li>
                                <?php  } ?>
                                <!-- Lembaga Kemahasiswaan -->
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