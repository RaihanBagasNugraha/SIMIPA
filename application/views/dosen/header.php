
<ul class="vertical-nav-menu">
                                <li class="app-sidebar__heading">Profil</li>
                                <li>
                                    <a href="<?php echo site_url('dosen/kelola-akun') ?>" <?php if($this->uri->segment(2) == "kelola-akun") echo 'class="mm-active"' ?> >
                                        <i class="metismenu-icon pe-7s-user"></i>
                                        Akun
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('dosen/kelola-biodata') ?>" <?php if($this->uri->segment(2) == "kelola-biodata") echo 'class="mm-active"' ?> >
                                        <i class="metismenu-icon pe-7s-id"></i>
                                        Biodata
                                    </a>
                                </li>
                                <li class="app-sidebar__heading">Menu</li>
                                
                                
                                    <li <?php if($this->uri->segment(2) == "layanan-fakultas") echo 'class="mm-active"' ?>>
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-note2"></i>
                                        Manajemen KP/PKL
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="#" class="mm-active">
                                                <i class="metismenu-icon">
                                                </i>Persetujuan Tema
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="metismenu-icon">
                                                </i>Kemajuan Bimbingan
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="metismenu-icon">
                                                </i>Rekap Seminar
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                

                                <li <?php if($this->uri->segment(2) == "tugas-akhir" && $this->uri->segment(4) != "koordinator") echo 'class="mm-active"' ?>>
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-note"></i>
                                        <?php 
                                            $ta = count($this->ta_model->get_approval_ta($this->session->userdata('userId')));
                                            $ta_pa = count($this->ta_model->get_approval_ta_by_pa($this->session->userdata('userId')));
                                            $ta_approve = count($this->ta_model->get_approval_ta_list($this->session->userdata('userId')));
                                            $persetujan_tema = $ta+$ta_pa+$ta_approve;

                                            $smr_pa = count($this->ta_model->get_approval_seminar_by_pa($this->session->userdata('userId')));
                                            $smr = count($this->ta_model->get_approval_seminar_list($this->session->userdata('userId')));
                                            $persetujan_seminar = $smr + $smr_pa;

                                            $smr_rekap = count($this->ta_model->get_rekap_seminar($this->session->userdata('userId')));
                                            $manajemen_ta = $persetujan_seminar + $persetujan_tema + $smr_rekap;
                                        
                                        ?>
                                        Manajemen Tugas Akhir <span class="badge badge-danger"><?php echo $manajemen_ta > 0 ? $manajemen_ta : "" ?></span>
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="<?php echo site_url("dosen/tugas-akhir/tema") ?>" <?php if($this->uri->segment(2) == "tugas-akhir" && $this->uri->segment(3) == "tema") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Persetujuan Tema <span class="badge badge-danger"><?php echo $persetujan_tema > 0 ? $persetujan_tema : "" ?></span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url("dosen/tugas-akhir/seminar") ?>" <?php if($this->uri->segment(2) == "tugas-akhir" && $this->uri->segment(3) == "seminar") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Persetujuan Seminar <span class="badge badge-danger"><?php echo $persetujan_seminar > 0 ? $persetujan_seminar : "" ?></span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="metismenu-icon">
                                                </i>Kemajuan Bimbingan
                                            </a>
                                        </li>
                                        <li>
                                        <a href="<?php echo site_url("dosen/tugas-akhir/rekap-seminar") ?>" <?php if($this->uri->segment(2) == "tugas-akhir" && $this->uri->segment(3) == "rekap-seminar") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Rekap Seminar/Sidang <span class="badge badge-danger"><?php echo $smr_rekap > 0 ? $smr_rekap : "" ?></span>
                                            </a>
                                        </li>
                                       
                                    </ul>
                                </li>
                                
                                <li>
                                    <a href="#" <?php if($this->uri->segment(1) == "unggah-nilai") echo 'class="mm-active"' ?> >
                                        <i class="metismenu-icon pe-7s-display1"></i>
                                        Penelitian
                                    </a>
                                </li>
                                <li>
                                    <a href="#" <?php if($this->uri->segment(1) == "unggah-nilai") echo 'class="mm-active"' ?> >
                                        <i class="metismenu-icon pe-7s-gift"></i>
                                        Pengabdian Masyarakat
                                    </a>
                                </li>
                                <li>
                                    <a href="#" <?php if($this->uri->segment(1) == "unggah-nilai") echo 'class="mm-active"' ?> >
                                        <i class="metismenu-icon pe-7s-box1"></i>
                                        Penunjang
                                    </a>
                                </li>

                                <!-- Menu Koordinator -->

                                <li class="app-sidebar__heading">Koordinator Tugas Akhir</li>
                                <li <?php if($this->uri->segment(2) == "tugas-akhir" && $this->uri->segment(4) == "koordinator") echo 'class="mm-active"' ?>>
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-pen"></i>
                                        <?php 
                                            $ta_koor = count($this->ta_model->get_approval_ta_koordinator($this->session->userdata('userId')));
                                            $smr_koor = count($this->ta_model->get_approval_seminar_koordinator($this->session->userdata('userId')));

                                            $validasi = $ta_koor + $smr_koor;
                                        
                                        ?>
                                        Validasi <span class="badge badge-danger"><?php echo $validasi > 0 ? $validasi : "" ?></span>
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="<?php echo site_url("dosen/tugas-akhir/tema/koordinator") ?>" <?php if($this->uri->segment(2) == "tugas-akhir" && $this->uri->segment(3) == "tema" && $this->uri->segment(4) == "koordinator") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Tema <span class="badge badge-danger"><?php echo $ta_koor > 0 ? $ta_koor: "" ?></span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url("dosen/tugas-akhir/seminar/koordinator") ?>" <?php if($this->uri->segment(2) == "tugas-akhir" && $this->uri->segment(3) == "seminar" && $this->uri->segment(4) == "koordinator") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Seminar/Sidang <span class="badge badge-danger"><?php echo $smr_koor > 0 ? $smr_koor : "" ?></span>
                                            </a>
                                        </li>
                                       
                                    </ul>
                                </li>

                                <li >
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-graph1"></i>
                                        Kemajuan Tugas Akhir
                                        
                                    </a>
                                    
                                </li>

                                <li <?php if($this->uri->segment(2) == "koordinator" && $this->uri->segment(3) == "rekap") echo 'class="mm-active"' ?>>
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-copy-file"></i>
                                        <!-- <?php 
                                            $rekap = count($this->ta_model->get_ta_rekap($this->session->userdata('userId')));
                                        ?> -->
                                        Rekap 
                                        <!-- <span class="badge badge-danger"><?php echo $jml?></span> -->
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="#">
                                                <i class="metismenu-icon">
                                                </i>KP/PKL
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url("dosen/koordinator/rekap/tugas-akhir") ?>" <?php if($this->uri->segment(2) == "koordinator" && $this->uri->segment(3) == "rekap" && $this->uri->segment(4) == "tugas-akhir") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                 </i>Tugas Akhir <!--<span class="badge badge-danger"><?php echo $rekap > 0 ? $rekap : "" ?></span> -->
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url("dosen/koordinator/rekap/seminar") ?>" <?php if($this->uri->segment(2) == "koordinator" && $this->uri->segment(3) == "rekap" && $this->uri->segment(4) == "seminar") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Seminar
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="metismenu-icon">
                                                </i>Mahasiswa
                                            </a>
                                        </li>
                                       
                                    </ul>
                                </li>

                                <!-- Menu Kajur/Sekjur -->

                                <li class="app-sidebar__heading">Ketua/Sekretaris Jurusan</li>
                                <li <?php if($this->uri->segment(2) == "struktural") echo 'class="mm-active"' ?>>
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-pen"></i>
                                        <?php 
                                            $ta_kajur = count($this->ta_model->get_approval_ta_kajur($this->session->userdata('userId')));
                                            $smr_kajur = count($this->ta_model->get_approval_seminar_kajur($this->session->userdata('userId')));

                                            $asese = $ta_kajur + $smr_kajur;
                                        
                                        ?>

                                        Asese <span class="badge badge-danger"><?php echo $asese > 0 ? $asese : "" ?></span>
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="<?php echo site_url("dosen/struktural/tema") ?>" <?php if($this->uri->segment(3) == "tema" && $this->uri->segment(2) == "struktural") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Tema <span class="badge badge-danger"><?php echo $ta_kajur > 0 ? $ta_kajur : "" ?></span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url("dosen/struktural/seminar") ?>" <?php if($this->uri->segment(3) == "seminar" && $this->uri->segment(2) == "struktural") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Seminar/Sidang <span class="badge badge-danger"><?php echo $smr_kajur > 0 ? $smr_kajur : "" ?></span>
                                            </a>
                                        </li>
                                       
                                    </ul>
                                </li>

                                <li >
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-monitor"></i>
                                        Pemantauan
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="#" <?php if($this->uri->segment(2) == "tugas-akhir" && $this->uri->segment(3) == "tema") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Kemajuan Tugas Akhir
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="metismenu-icon">
                                                </i>Himpuan Mahasiswa
                                            </a>
                                        </li>
                                        
                                       
                                    </ul>
                                </li>

                                <li >
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-copy-file"></i>
                                        Rekap
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="#" >
                                                <i class="metismenu-icon">
                                                </i>KP/PKL
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="metismenu-icon">
                                                </i>Tugas Akhir
                                            </a>
                                        </li>
                                        
                                       
                                    </ul>
                                </li>
                                
                                <li>
                                    <a href="<?php echo site_url('keluar-sistem') ?>">
                                        <i class="metismenu-icon pe-7s-config"></i>
                                        Pengaturan
                                    </a>
                                </li>

                                <!-- Menu Dekan -->

                                <li class="app-sidebar__heading">Dekan</li>
                                <li >
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-monitor"></i>
                                        Pemantauan
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="#" <?php if($this->uri->segment(2) == "tugas-akhir" && $this->uri->segment(3) == "tema") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Kemajuan Tugas Akhir
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="metismenu-icon">
                                                </i>Lembaga Kemahasiswaan
                                            </a>
                                        </li>
                                        
                                       
                                    </ul>
                                </li>

                                <li >
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-copy-file"></i>
                                        Rekap
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="#">
                                                <i class="metismenu-icon">
                                                </i>Tugas Akhir
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="metismenu-icon">
                                                </i>Layanan Mahasiswa
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="metismenu-icon">
                                                </i>Lembaga Kemahasiswaan
                                            </a>
                                        </li>
                                        
                                       
                                    </ul>
                                </li>

                                <!-- WD 3 -->


                                <li class="app-sidebar__heading">Wakil Dekan III</li>
                                <li >
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-notebook"></i>
                                        Lmbg Kemahasiswaan
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="#" <?php if($this->uri->segment(2) == "tugas-akhir" && $this->uri->segment(3) == "tema") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Asese
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="metismenu-icon">
                                                </i>Pemantauan Progja
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="metismenu-icon">
                                                </i>Rekap Progja
                                            </a>
                                        </li>
                                        
                                       
                                    </ul>
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