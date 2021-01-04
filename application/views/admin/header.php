<?php 

?>

<ul class="vertical-nav-menu">
                                <li class="app-sidebar__heading">Profil</li>
                                <li>
                                    <a href="<?php echo site_url('admin/kelola-akun') ?>" <?php if($this->uri->segment(2) == "kelola-akun") echo 'class="mm-active"' ?> >
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
                                <li <?php if($this->uri->segment(2) == "mahasiswa" || $this->uri->segment(2) == "user") echo 'class="mm-active"' ?> >
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-users"></i>
                                        User
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                        <a href="<?php echo site_url("admin/mahasiswa/registrasi") ?>" <?php if($this->uri->segment(2) == "mahasiswa" && $this->uri->segment(3) == "registrasi") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Registrasi
                                            </a>
                                        </li>

                                        <li  <?php if($this->uri->segment(2) == "user") echo 'class="mm-active"' ?>>
                                            <a href="#">
                                                <i class="metismenu-icon">
                                                </i>Tambah User
                                                <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                            </a>
                                            <ul>
                                                <li>
                                                    <a href="<?php echo site_url("admin/user/dosen") ?>" <?php if($this->uri->segment(2) == "user" && $this->uri->segment(3) == "dosen") echo 'class="mm-active"' ?>>
                                                        <i class="metismenu-icon">
                                                        </i>Dosen
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo site_url("admin/user/tendik") ?>" <?php if($this->uri->segment(2) == "user" && $this->uri->segment(3) == "tendik") echo 'class="mm-active"' ?>>
                                                        <i class="metismenu-icon">
                                                        </i>Tendik
                                                    </a>
                                                </li>
                                            </ul>
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

                                <li <?php if($this->uri->segment(2) == "unit") echo 'class="mm-active"' ?> >
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-study"></i>
                                       Unit
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="<?php echo site_url("admin/unit/prodi") ?>" <?php if($this->uri->segment(2) == "unit" && $this->uri->segment(3) == 'prodi') echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Prodi
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url("admin/unit/jurusan") ?>" <?php if($this->uri->segment(2) == "unit" && $this->uri->segment(3) == 'jurusan') echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Jurusan
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url("admin/unit/lab") ?>" <?php if($this->uri->segment(2) == "unit" && $this->uri->segment(3) == 'lab') echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Lab
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url("admin/unit/unit-tendik") ?>" <?php if($this->uri->segment(2) == "unit" && $this->uri->segment(3) == 'unit-tendik') echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Unit Kerja Tendik
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li <?php if($this->uri->segment(2) == "layanan-fakultas") echo 'class="mm-active"' ?> >
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-note2"></i>
                                       Layanan Fakultas
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="<?php echo site_url("admin/layanan-fakultas/tambah-form") ?>" <?php if($this->uri->segment(2) == "layanan-fakultas" && $this->uri->segment(3) == 'tambah-form') echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Tambah Form
                                            </a>
                                        </li>

                                        <li>
                                            <a href="<?php echo site_url("admin/layanan-fakultas/form-atribut") ?>" <?php if($this->uri->segment(2) == "layanan-fakultas" && $this->uri->segment(3) == 'form-atribut') echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Atribut Form
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