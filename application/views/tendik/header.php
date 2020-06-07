
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
                                
                                <!-- Menu Admin Fakultas -->
                                
                                <li class="app-sidebar__heading">Admin Fakultas</li>
                                <li <?php if($this->uri->segment(2) == "tugas-akhir") echo 'class="mm-active"' ?>>
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-note2"></i>
                                        Verifikasi Berkas
                                        
                                    </a>
                                    
                                </li>
                                
                                <!-- Menu Admin Jurusan -->
                                
                                <li class="app-sidebar__heading">Admin Jurusan</li>
                                <li <?php if($this->uri->segment(2) == "verifikasi-berkas") echo 'class="mm-active"' ?>>
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-note"></i>
                                        <?php echo "Verifikasi Berkas" ?>
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="<?php echo site_url("tendik/verifikasi-berkas") ?>" <?php if($this->uri->segment(2) == "verifikasi-berkas" && $this->uri->segment(3) != "seminar") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon pe-7s-note2"></i>
                                                Tema
                                                
                                            </a>
                                        </li> 
                                        <li>
                                            <a href="<?php echo site_url("tendik/verifikasi-berkas/seminar") ?>" <?php if($this->uri->segment(2) == "verifikasi-berkas" && $this->uri->segment(3) == "seminar") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon pe-7s-note2"></i>
                                                Seminar
                                                
                                            </a>
                                        </li>    
                                    </ul>
                                    
                                </li>
                                

                                <!-- Menu Staf Laboran -->
                                
                                <li class="app-sidebar__heading">Staf Laboran</li>
                                <li <?php if($this->uri->segment(2) == "tugas-akhir") echo 'class="mm-active"' ?>>
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-note2"></i>
                                        Verifikasi Bebas Lab
                                        
                                    </a>
                                    
                                </li>

                                <!-- Menu Staf Ruang Baca -->
                                
                                <li class="app-sidebar__heading">Staf Ruang Baca</li>
                                <li <?php if($this->uri->segment(2) == "tugas-akhir") echo 'class="mm-active"' ?>>
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-note2"></i>
                                        Verifikasi Bebas Ruang Baca
                                        
                                    </a>
                                    
                                </li>

                               
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