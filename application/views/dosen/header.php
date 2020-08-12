<?php 

$biodata = $this->user_model->get_dosen_data($this->session->userdata('userId'));
// check role
$tb_kajur = $this->user_model->tugas_dosen_kajur_sekjur($this->session->userdata('userId'));
$tb_kaprodi = $this->user_model->tugas_dosen_kaprodi($this->session->userdata('userId'));
$tb_dekan = $this->user_model->tugas_dosen_dekan($this->session->userdata('userId'));
$tb_wd = $this->user_model->tugas_dosen_wakil_dekan($this->session->userdata('userId'));
$tb_koor = $this->user_model->tugas_dosen_koor($this->session->userdata('userId'));

?>
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


                                            $ver_pa = count($this->ta_model->get_verifikasi_ta_list_pa($this->session->userdata('userId')));
                                            $ver = count($this->ta_model->get_verifikasi_ta_list($this->session->userdata('userId')));
                                            $verifikasi_ta = $ver + $ver_pa;

                                            $ver_ta_nilai = count($this->ta_model->get_verifikasi_program_ta_dosen($this->session->userdata('userId')));
                                            $smr_nilai = count($this->ta_model->get_nilai_seminar($this->session->userdata('userId')));

                                            if($biodata->jurusan == "5"){
                                                $manajemen_ta = $persetujan_seminar + $persetujan_tema + $smr_nilai + $verifikasi_ta + $ver_ta_nilai;
                                            }
                                            else{
                                                $manajemen_ta = $persetujan_seminar + $persetujan_tema + $smr_nilai;
                                            }
                                           
                                        
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
                                        <?php if($biodata->jurusan == "5"){ ?>
                                        <li>
                                            <a href="<?php echo site_url("dosen/tugas-akhir/verifikasi-ta") ?>" <?php if($this->uri->segment(2) == "tugas-akhir" && $this->uri->segment(3) == "verifikasi-ta") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Persetujuan Verifikasi TA <span class="badge badge-danger"><?php echo $verifikasi_ta > 0 ? $verifikasi_ta : "" ?></span>
                                            </a>
                                        </li>
                                        <?php } ?>
                                        <li>
                                            <a href="#">
                                                <i class="metismenu-icon">
                                                </i>Kemajuan Bimbingan
                                            </a>
                                        </li>
                                        <li>
                                        <a href="<?php echo site_url("dosen/tugas-akhir/nilai-seminar") ?>" <?php if($this->uri->segment(2) == "tugas-akhir" && $this->uri->segment(3) == "nilai-seminar") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Nilai Seminar/Sidang <span class="badge badge-danger"><?php echo $smr_nilai > 0 ? $smr_nilai : "" ?></span>
                                            </a>
                                        </li>
                                        <?php if($biodata->jurusan == "5"){ ?>
                                            <li>
                                                <a href="<?php echo site_url("dosen/tugas-akhir/nilai-verifikasi-ta") ?>" <?php if($this->uri->segment(2) == "tugas-akhir" && $this->uri->segment(3) == "nilai-verifikasi-ta") echo 'class="mm-active"' ?>>
                                                    <i class="metismenu-icon">
                                                    </i>Nilai Verifikasi TA <span class="badge badge-danger"><?php echo $ver_ta_nilai > 0 ? $ver_ta_nilai : "" ?></span>
                                                </a>
                                            </li>
                                         <?php } ?>
                                       
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

                                <?php if(!empty($tb_koor)) { ?>
                                <!-- Menu Koordinator -->

                                <li class="app-sidebar__heading">Koordinator Tugas Akhir</li>
                                <li <?php if($this->uri->segment(2) == "tugas-akhir" && $this->uri->segment(4) == "koordinator") echo 'class="mm-active"' ?>>
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-pen"></i>
                                        <?php 
                                            $ta_koor = count($this->ta_model->get_approval_ta_koordinator($this->session->userdata('userId')));
                                            $smr_koor = count($this->ta_model->get_approval_seminar_koordinator($this->session->userdata('userId')));
                                            $nilai_smr_koor = count($this->ta_model->get_approval_nilai_seminar_koordinator($this->session->userdata('userId')));    

                                            if($biodata->jurusan == "5"){
                                                $validasi = $ta_koor + $smr_koor + $nilai_smr_koor;
                                            }
                                            else{
                                                $validasi = 0;
                                            }
                                        
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
                                        <li>
                                            <a href="<?php echo site_url("dosen/tugas-akhir/nilai-seminar/koordinator") ?>" <?php if($this->uri->segment(2) == "tugas-akhir" && $this->uri->segment(3) == "nilai-seminar" && $this->uri->segment(4) == "koordinator") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Nilai Seminar/Sidang <span class="badge badge-danger"><?php echo $nilai_smr_koor > 0 ? $nilai_smr_koor : "" ?></span>
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
                                            <a href="<?php echo site_url("dosen/koordinator/rekap/mahasiswa-ta") ?>" <?php if($this->uri->segment(2) == "koordinator" && $this->uri->segment(3) == "rekap" && $this->uri->segment(4) == "mahasiswa-ta") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Mahasiswa Tugas Akhir
                                            </a>
                                        </li>
                                       
                                    </ul>
                                </li>
                                <?php } ?>
                                <?php if(!empty($tb_kajur)) { ?>
                                <!-- Menu Kajur/Sekjur -->

                                <li class="app-sidebar__heading">Ketua/Sekretaris Jurusan</li>
                                <li <?php if($this->uri->segment(2) == "struktural" && $this->uri->segment(3) != "bidang-nilai" && $this->uri->segment(3) != "kaprodi" && $this->uri->segment(3) != "komposisi-nilai" ) echo 'class="mm-active"' ?>>
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-pen"></i>
                                        <?php 
                                            $ta_kajur = count($this->ta_model->get_approval_ta_kajur($this->session->userdata('userId')));
                                            $smr_kajur = count($this->ta_model->get_approval_seminar_kajur($this->session->userdata('userId')));
                                            $nilai_smr_kajur = count($this->ta_model->get_approval_nilai_seminar_kajur($this->session->userdata('userId')));
                                            $asese = $ta_kajur + $smr_kajur + $nilai_smr_kajur;
                                        
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
                                        <li>
                                            <a href="<?php echo site_url("dosen/struktural/nilai-seminar") ?>" <?php if($this->uri->segment(3) == "nilai-seminar" && $this->uri->segment(2) == "struktural") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Nilai Seminar/Sidang <span class="badge badge-danger"><?php echo $nilai_smr_kajur > 0 ? $nilai_smr_kajur : "" ?></span>
                                            </a>
                                        </li>
                                        <!-- <li>
                                            <a href="<?php echo site_url("dosen/struktural/komposisi-nilai") ?>" <?php if( $this->uri->segment(2) == "struktural" && $this->uri->segment(3) == "komposisi-nilai") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Komposisi Nilai
                                            </a>
                                        </li> -->
                                       
                                    </ul>
                                </li>

                                <li <?php if(($this->uri->segment(3) == "bidang-nilai" || $this->uri->segment(3) == "komposisi-nilai") && $this->uri->segment(2) == "struktural" ) echo 'class="mm-active"' ?>>
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-pen"></i>
                                        <?php 
                            
                                        ?>
                                        Bidang & Nilai
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="<?php echo site_url("dosen/struktural/bidang-nilai/bidang-jurusan") ?>" <?php if( $this->uri->segment(2) == "struktural" && $this->uri->segment(3) == "bidang-nilai" && $this->uri->segment(4) == "bidang-jurusan") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Bidang Ilmu Jurusan
                                            </a>
                                        </li>

                                        <li>
                                            <a href="<?php echo site_url("dosen/struktural/bidang-nilai/komposisi-nilai") ?>" <?php if( $this->uri->segment(2) == "struktural" && ($this->uri->segment(3) == "bidang-nilai" && $this->uri->segment(3) == "komposisi-nilai") || $this->uri->segment(4) == "komposisi-nilai") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Komposisi Nilai
                                            </a>
                                        </li>

                                        <li>
                                            <a href="<?php echo site_url("dosen/struktural/bidang-nilai/komposisi-ta") ?>" <?php if( $this->uri->segment(2) == "struktural" && $this->uri->segment(3) == "bidang-nilai" && $this->uri->segment(4) == "komposisi-ta") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Komposisi Nilai Verifikasi TA
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
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-config"></i>
                                        Pengaturan
                                    </a>
                                </li>
                                <?php } ?>
                                <?php if(!empty($tb_kaprodi)) { ?>
                                <!-- Menu Kaprodi -->

                                <li class="app-sidebar__heading">Ketua Program Studi</li>
                                <li <?php if($this->uri->segment(2) == "struktural" && $this->uri->segment(3) == "kaprodi") echo 'class="mm-active"' ?>>
                                    <a href="#">
                                        <?php 
                                        
                                            $tema_kaprodi =  count($this->ta_model->get_approval_ta_kaprodi($this->session->userdata('userId')));
                                            $smr_kaprodi = count($this->ta_model->get_approval_seminar_kaprodi($this->session->userdata('userId')));
                                            $ver_ta_kaprodi = count($this->ta_model->get_verifikasi_ta_list_kaprodi($this->session->userdata('userId')));
                                            $nilai_smr_kaprodi = count($this->ta_model->get_approval_nilai_seminar_kaprodi($this->session->userdata('userId')));

                                            $asese_kaprodi = $tema_kaprodi + $smr_kaprodi + $ver_ta_kaprodi + $nilai_smr_kaprodi;

                                        ?>
                                        <i class="metismenu-icon pe-7s-pen"></i>
                                        Asese <span class="badge badge-danger"><?php echo $asese_kaprodi > 0 ? $asese_kaprodi : "" ?></span>
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                    
                                        <li>
                                            <a href="<?php echo site_url("dosen/struktural/kaprodi/tugas-akhir") ?>" <?php if($this->uri->segment(3) == "kaprodi" && $this->uri->segment(4) == "tugas-akhir") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Tema <span class="badge badge-danger"><?php echo $tema_kaprodi > 0 ? $tema_kaprodi : "" ?></span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url("dosen/struktural/kaprodi/seminar-sidang") ?>" <?php if($this->uri->segment(3) == "kaprodi" && $this->uri->segment(4) == "seminar-sidang") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Seminar/Sidang <span class="badge badge-danger"><?php echo $smr_kaprodi > 0 ? $smr_kaprodi : "" ?></span>
                                            </a>
                                        </li>
                                    <?php if($biodata->jurusan == "5"){ ?>    
                                        <li>
                                            <a href="<?php echo site_url("dosen/struktural/kaprodi/verifikasi-tugas-akhir") ?>" <?php if($this->uri->segment(3) == "kaprodi" && $this->uri->segment(4) == "verifikasi-tugas-akhir") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Verifikasi Program TA <span class="badge badge-danger"><?php echo $ver_ta_kaprodi > 0 ? $ver_ta_kaprodi : "" ?></span>
                                            </a>
                                        </li>
                                    <?php } ?>
                                        <li>
                                            <a href="<?php echo site_url("dosen/struktural/kaprodi/nilai-seminar-sidang") ?>" <?php if($this->uri->segment(3) == "kaprodi" && $this->uri->segment(4) == "nilai-seminar-sidang") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Nilai Seminar/Sidang <span class="badge badge-danger"><?php echo $nilai_smr_kaprodi > 0 ? $nilai_smr_kaprodi : "" ?></span>
                                            </a>
                                        </li>
                                    
                                        
                                       
                                    </ul>
                                </li>
                                <?php } ?>
                                <?php if(!empty($tb_dekan)) { ?>
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
                                <?php } ?>
                                <?php if(!empty($tb_wd)) { ?>
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