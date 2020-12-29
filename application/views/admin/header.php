<?php 

?>

<ul class="vertical-nav-menu">
                                <li class="app-sidebar__heading">Profil</li>
                                <li>
                                    <a href="<?php echo site_url('#') ?>" <?php if($this->uri->segment(2) == "kelola-akun") echo 'class="mm-active"' ?> >
                                        <i class="metismenu-icon pe-7s-user"></i>
                                        Akun
                                    </a>
                                </li>
                                <!-- <li>
                                    <a href="<?php echo site_url('#') ?>" <?php if($this->uri->segment(2) == "kelola-biodata") echo 'class="mm-active"' ?> >
                                        <i class="metismenu-icon pe-7s-id"></i>
                                        Biodata
                                    </a>
                                </li> -->

                                <li class="app-sidebar__heading">MENU</li>
                                <li <?php if($this->uri->segment(2) == "mahasiswa") echo 'class="mm-active"' ?> >
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-users"></i>
                                        Registrasi
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                        <a href="<?php echo site_url("admin/mahasiswa/registrasi") ?>" <?php if($this->uri->segment(2) == "mahasiswa" && $this->uri->segment(3) == "registrasi") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Registrasi
                                            </a>
                                        </li>

                                    </ul>
                                </li>

                               
                                <li <?php if($this->uri->segment(2) == "struktural") echo 'class="mm-active"' ?> >
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-network"></i>
                                       Struktural
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                        <a href="<?php echo site_url("admin/struktural/tugas") ?>" <?php if($this->uri->segment(2) == "struktural" && $this->uri->segment(3) == "tugas") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Tugas Tambahan
                                            </a>
                                        </li>

                                    </ul>
                                </li>


                                <!-- <li <?php if($this->uri->segment(2) == "form-layanan") echo 'class="mm-active"' ?>>
                                        <a href="#">
                                            <i class="metismenu-icon pe-7s-note"></i>
                                            Form Layanan
                                            <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                        </a>
                                    <ul>
                                        <li>
                                            <a href="<?php echo site_url("admin/form-layanan/atribut") ?>" <?php if($this->uri->segment(2) == "form-layanan" && $this->uri->segment(3) == "atribut") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon pe-7s-note2"></i>
                                                Tambah Atribut
                                            </a>
                                        </li> 
                                    </ul>
                                            
                                </li> -->
                             
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