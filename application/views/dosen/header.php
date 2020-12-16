<?php 

$biodata = $this->user_model->get_dosen_data($this->session->userdata('userId'));
// check role
$tb_kajur = $this->user_model->tugas_dosen_kajur($this->session->userdata('userId'));
$tb_kaprodi = $this->user_model->tugas_dosen_kaprodi($this->session->userdata('userId'));
$tb_dekan = $this->user_model->tugas_dosen_dekan($this->session->userdata('userId'));
$tb_wd = $this->user_model->tugas_dosen_wakil_dekan($this->session->userdata('userId'));
$tb_koor = $this->user_model->tugas_dosen_koor($this->session->userdata('userId'));
$tb_koor_kp = $this->user_model->tugas_dosen_koor_kp($this->session->userdata('userId'));

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
                                
                                
                                <li <?php if($this->uri->segment(2) == "approval" && ($this->uri->segment(3) == "pembimbing-akademik" || $this->uri->segment(3) == "pembimbing-ta")) echo 'class="mm-active"' ?>>
                                    <a href="#">
                                    <?php 
                                         $lf_pa = count($this->layanan_model->get_approval_pa_fakultas($this->session->userdata('userId')));
                                         $lf_pb = count($this->layanan_model->get_approval_pbb_fakultas($this->session->userdata('userId')));

                                         $lf_total = $lf_pa + $lf_pb;
                                    
                                    ?>
                                        <i class="metismenu-icon pe-7s-angle-down-circle"></i>
                                        Layanan Fakultas <span class="badge badge-danger"><?php echo $lf_total > 0 ? $lf_total : "" ?></span>
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="<?php echo site_url("dosen/approval/pembimbing-akademik") ?>"  <?php if($this->uri->segment(2) == "approval" && $this->uri->segment(3) == "pembimbing-akademik") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Pembimbing Akademik <span class="badge badge-danger"><?php echo $lf_pa > 0 ? $lf_pa : "" ?></span>
                                            </a>
                                        </li>

                                        <li>
                                            <a href="<?php echo site_url("dosen/approval/pembimbing-ta") ?>"  <?php if($this->uri->segment(2) == "approval" && $this->uri->segment(3) == "pembimbing-ta") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Pembimbing Tugas Akhir <span class="badge badge-danger"><?php echo $lf_pb > 0 ? $lf_pb : "" ?></span>
                                            </a>
                                        </li>
                                       
                                    </ul>
                                </li>

                                <li <?php if($this->uri->segment(2) == "pkl" && $this->uri->segment(4) != "koordinator") echo 'class="mm-active"' ?>>
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-note2"></i>
                                        <?php 
                                            $pa_kp = count($this->pkl_model->get_approve_pa_pkl($this->session->userdata('userId')));
                                            $seminar_kp = count( $this->pkl_model->get_mahasiswa_pkl_bimbingan($this->session->userdata('userId')));
                                            $seminar_nilai_kp = count($this->pkl_model->get_mahasiswa_seminar_nilai_pbb($this->session->userdata('userId')));
                                            $manajemen_kp = $pa_kp + $seminar_kp + $seminar_nilai_kp;
                                        ?>
                                        Manajemen KP/PKL  <span class="badge badge-danger"><?php echo $manajemen_kp > 0 ? $manajemen_kp : "" ?></span>
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="<?php echo site_url("dosen/pkl/approve") ?>"  <?php if($this->uri->segment(2) == "pkl" && $this->uri->segment(3) == "approve") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Persetujuan KP/PKL  <span class="badge badge-danger"><?php echo $pa_kp > 0 ? $pa_kp : "" ?></span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url("dosen/pkl/approve-seminar") ?>"  <?php if($this->uri->segment(2) == "pkl" && $this->uri->segment(3) == "approve-seminar") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Persetujuan Seminar  <span class="badge badge-danger"><?php echo $seminar_kp > 0 ? $seminar_kp : "" ?></span>
                                            </a>
                                        </li>
                                        <li>
                                        <a href="<?php echo site_url("dosen/pkl/approve-nilai-seminar") ?>"  <?php if($this->uri->segment(2) == "pkl" && $this->uri->segment(3) == "approve-nilai-seminar") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Penilaian Seminar  <span class="badge badge-danger"><?php echo $seminar_nilai_kp > 0 ? $seminar_nilai_kp : "" ?></span>
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

                                <?php if(in_array(1,$tb)){ ?>
                                <!-- Menu Dekan -->
                                <li class="app-sidebar__heading">Dekan</li>
                                <li <?php if($this->uri->segment(2) == "approval" && ($this->uri->segment(2) == "dekan")) echo 'class="mm-active"' ?>>
                                    <a href="#">
                                    <?php 
                                         $lf_dekan = count($this->layanan_model->get_approval_struktural_fakultas($this->session->userdata('userId'),1));
                                    
                                    ?>
                                        <i class="metismenu-icon pe-7s-angle-down-circle"></i>
                                        Layanan Fakultas <span class="badge badge-danger"><?php echo $lf_dekan > 0 ? $lf_dekan : "" ?></span>
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="<?php echo site_url("dosen/approval/dekan") ?>"  <?php if($this->uri->segment(2) == "approval" && $this->uri->segment(3) == "dekan") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Form Pegajuan Layanan <span class="badge badge-danger"><?php echo $lf_dekan > 0 ? $lf_dekan : "" ?></span>
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
                                                </i>Lembaga Kemahasiswaan
                                            </a>
                                        </li>
                                        
                                       
                                    </ul>
                                </li>

                                <li >
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-copy-file"></i>
                                        Monitor
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

                                <?php if(in_array(2,$tb)){ ?>
                                <!-- WD 1 -->

                                <li class="app-sidebar__heading">Wakil Dekan I</li>
                                <li <?php if($this->uri->segment(2) == "approval" && ($this->uri->segment(3) == "wd1")) echo 'class="mm-active"' ?>>
                                    <a href="#">
                                    <?php 
                                        $lf_wd1 = count($this->layanan_model->get_approval_struktural_fakultas($this->session->userdata('userId'),2));

                                    ?>
                                        <i class="metismenu-icon pe-7s-notebook"></i>
                                        Layanan Fakultas <span class="badge badge-danger"><?php echo $lf_wd1 > 0 ? $lf_wd1 : "" ?></span>
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                        <a href="<?php echo site_url("dosen/approval/wd1") ?>" <?php if($this->uri->segment(2) == "approval" && $this->uri->segment(3) == "wd1") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Form Pegajuan Layanan <span class="badge badge-danger"><?php echo $lf_wd1 > 0 ? $lf_wd1 : "" ?></span>
                                            </a>
                                        </li>

                                    </ul>
                                </li>
                                <?php } ?>

                                <?php if(in_array(3,$tb)){ ?>
                                <!-- WD 2 -->

                                <li class="app-sidebar__heading">Wakil Dekan II</li>
                                <li <?php if($this->uri->segment(2) == "approval" && ($this->uri->segment(3) == "wd2")) echo 'class="mm-active"' ?>>
                                    <a href="#">
                                    <?php 
                                        $lf_wd2 = count($this->layanan_model->get_approval_struktural_fakultas($this->session->userdata('userId'),3));

                                    ?>
                                        <i class="metismenu-icon pe-7s-notebook"></i>
                                        Layanan Fakultas <span class="badge badge-danger"><?php echo $lf_wd2 > 0 ? $lf_wd2 : "" ?></span>
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                        <a href="<?php echo site_url("dosen/approval/wd2") ?>" <?php if($this->uri->segment(2) == "approval" && $this->uri->segment(3) == "wd2") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Form Pegajuan Layanan <span class="badge badge-danger"><?php echo $lf_wd2 > 0 ? $lf_wd2 : "" ?></span>
                                            </a>
                                        </li>

                                    </ul>
                                </li>
                                <?php } ?>

                                <?php if(in_array(4,$tb)){ ?>
                                <!-- WD 3 -->

                                <li class="app-sidebar__heading">Wakil Dekan III</li>
                                <li <?php if($this->uri->segment(2) == "approval" && ($this->uri->segment(3) == "wd3")) echo 'class="mm-active"' ?>>
                                    <a href="#">
                                    <?php 
                                        $lf_wd3 = count($this->layanan_model->get_approval_struktural_fakultas($this->session->userdata('userId'),4));

                                    ?>
                                        <i class="metismenu-icon pe-7s-notebook"></i>
                                        Layanan Fakultas <span class="badge badge-danger"><?php echo $lf_wd3 > 0 ? $lf_wd3 : "" ?></span>
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                        <a href="<?php echo site_url("dosen/approval/wd3") ?>" <?php if($this->uri->segment(2) == "approval" && $this->uri->segment(3) == "wd3") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Form Pegajuan Layanan <span class="badge badge-danger"><?php echo $lf_wd3 > 0 ? $lf_wd3 : "" ?></span>
                                            </a>
                                        </li>

                                    </ul>
                                </li>

                                <li <?php if($this->uri->segment(2) == "prestasi") echo 'class="mm-active"' ?>>
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-star"></i>
                                        Prestasi
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                        <a href="<?php echo site_url("dosen/prestasi") ?>" <?php if($this->uri->segment(2) == "prestasi") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Prestasi
                                            </a>
                                        </li>                                       
                                    </ul>
                                </li>

                                <li <?php if($this->uri->segment(2) == "beasiswa" || $this->uri->segment(2) == "beasiswa-detail" || $this->uri->segment(2) == "beasiswa-mhs") echo 'class="mm-active"' ?>>
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-cash"></i>
                                        Beasiswa
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="<?php echo site_url("dosen/beasiswa") ?>" <?php if($this->uri->segment(2) == "beasiswa") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Tambah Beasiswa
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url("dosen/beasiswa-detail") ?>" <?php if($this->uri->segment(2) == "beasiswa-detail") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Setujui Beasiswa
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url("dosen/beasiswa-mhs") ?>" <?php if($this->uri->segment(2) == "beasiswa-mhs") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Rekap Beasiswa
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li <?php if($this->uri->segment(2) == "lk") echo 'class="mm-active"' ?>>
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-notebook"></i>
                                        Lmbg Kemahasiswaan
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="<?php echo site_url("dosen/lk/daftar-lk") ?>" <?php if($this->uri->segment(3) == "daftar-lk" || $this->uri->segment(3) == "detail-lk") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Daftar LK
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url("dosen/lk/progja") ?>" <?php if($this->uri->segment(3) == "progja") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Persetujuan Progja
                                            </a>
                                        </li>
                                        <li>
                                        <a href="<?php echo site_url("dosen/lk/rekap-progja") ?>" <?php if($this->uri->segment(3) == "rekap-progja") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Rekap Progja
                                            </a>
                                        </li>
                                        
                                       
                                    </ul>
                                </li>
                                <?php } ?>

                                <?php if(in_array(12,$tb)){ ?>
                                <!-- Menu Kajur/Sekjur -->

                                <li class="app-sidebar__heading">Ketua Jurusan</li>
                                <li <?php if($this->uri->segment(2) == "approval" && ($this->uri->segment(3) == "ketua-jurusan")) echo 'class="mm-active"' ?>>
                                    <a href="#">
                                        <?php 
                                            $lf_kajur = count($this->layanan_model->get_approval_kajur_fakultas($this->session->userdata('userId')));
                                        ?>
                                        <i class="metismenu-icon pe-7s-angle-down-circle"></i>
                                        Layanan Fakultas <span class="badge badge-danger"><?php echo $lf_kajur > 0 ? $lf_kajur : "" ?></span>
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="<?php echo site_url("dosen/approval/ketua-jurusan") ?>"  <?php if($this->uri->segment(2) == "approval" && $this->uri->segment(3) == "ketua-jurusan") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Form Pengajuan Layanan <span class="badge badge-danger"><?php echo $lf_kajur > 0 ? $lf_kajur : "" ?></span>
                                            </a>
                                        </li>
                                      
                                    </ul>
                                </li>
                                <li <?php if($this->uri->segment(2) == "struktural" && $this->uri->segment(3) != "bidang-nilai" && $this->uri->segment(3) != "kaprodi" && $this->uri->segment(3) != "komposisi-nilai" && $this->uri->segment(3) != "pkl"  && $this->uri->segment(3) != "rekap-pkl" && $this->uri->segment(3) != "rekap" ) echo 'class="mm-active"' ?>>
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

                                <li <?php if($this->uri->segment(3) == "pkl" && $this->uri->segment(2) == "struktural" ) echo 'class="mm-active"' ?> >
                                    <a href="#">
                                        <?php 
                                          $pkl_smr_kajur = count($this->pkl_model->get_mahasiswa_pkl_seminar_kajur($this->session->userdata('userId')));
                                          $pkl_kajur = count($this->pkl_model->get_pkl_mahasiswa_approval_kajur($this->session->userdata('userId')));
                                          $pkl_smr_kajur_nilai = count($this->pkl_model->get_mahasiswa_seminar_nilai_kajur($this->session->userdata('userId')));
                                            $kp_kajur = $pkl_smr_kajur+$pkl_kajur+$pkl_smr_kajur_nilai;
                                        
                                        ?>
                                        <i class="metismenu-icon pe-7s-note"></i>
                                        KP/PKL <span class="badge badge-danger"><?php echo $kp_kajur > 0 ? $kp_kajur : "" ?></span>
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="<?php echo site_url("dosen/struktural/pkl/approve-pkl") ?>" <?php if($this->uri->segment(2) == "struktural" && $this->uri->segment(3) == "pkl" && $this->uri->segment(4) == "approve-pkl") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Persetujuan <span class="badge badge-danger"><?php echo $pkl_kajur > 0 ? $pkl_kajur : "" ?></span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url("dosen/struktural/pkl/approve-seminar-pkl") ?>" <?php if($this->uri->segment(2) == "struktural" && $this->uri->segment(3) == "pkl" && $this->uri->segment(4) == "approve-seminar-pkl") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Seminar <span class="badge badge-danger"><?php echo $pkl_smr_kajur > 0 ? $pkl_smr_kajur : "" ?></span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url("dosen/struktural/pkl/approve-seminar-nilai-pkl") ?>" <?php if($this->uri->segment(2) == "struktural" && $this->uri->segment(3) == "pkl" && $this->uri->segment(4) == "approve-seminar-nilai-pkl") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Nilai Seminar <span class="badge badge-danger"><?php echo $pkl_smr_kajur_nilai > 0 ? $pkl_smr_kajur_nilai : "" ?></span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url("dosen/struktural/pkl/add-pkl") ?>" <?php if($this->uri->segment(2) == "struktural" && $this->uri->segment(3) == "pkl" && $this->uri->segment(4) == "add-pkl") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Tambah KP/PKL
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url("dosen/struktural/pkl/add-lokasi-pkl") ?>" <?php if($this->uri->segment(2) == "struktural" && $this->uri->segment(3) == "pkl" && $this->uri->segment(4) == "add-lokasi-pkl") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Tambah Lokasi
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url("dosen/struktural/pkl/komponen-nilai-pkl") ?>" <?php if($this->uri->segment(2) == "struktural"  && $this->uri->segment(3) == "pkl" && $this->uri->segment(4) == "komponen-nilai-pkl") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Komponen Penilaian
                                            </a>
                                        </li>
                                       
                                        
                                       
                                    </ul>
                                </li>

                                <li>
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

                               <li <?php if($this->uri->segment(2) == "struktural" && $this->uri->segment(3) == "rekap") echo 'class="mm-active"' ?>>
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-copy-file"></i>
                                        <!-- <?php 
                                            $rekap = count($this->ta_model->get_ta_rekap($this->session->userdata('userId')));
                                        ?> -->
                                        Monitor Tugas Akhir
                                        <!-- <span class="badge badge-danger"><?php echo $jml?></span> -->
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                       
                                        <li>
                                            <a href="<?php echo site_url("dosen/struktural/rekap/tugas-akhir") ?>" <?php if($this->uri->segment(2) == "struktural" && $this->uri->segment(3) == "rekap" && $this->uri->segment(4) == "tugas-akhir") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                 </i>Tugas Akhir <!--<span class="badge badge-danger"><?php echo $rekap > 0 ? $rekap : "" ?></span> -->
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url("dosen/struktural/rekap/seminar") ?>" <?php if($this->uri->segment(2) == "struktural" && $this->uri->segment(3) == "rekap" && $this->uri->segment(4) == "seminar") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Seminar
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url("dosen/struktural/rekap/mahasiswa-ta") ?>" <?php if($this->uri->segment(2) == "struktural" && $this->uri->segment(3) == "rekap" && $this->uri->segment(4) == "mahasiswa-ta") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Mahasiswa Tugas Akhir
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url("dosen/struktural/rekap/bimbingan-dosen") ?>" <?php if($this->uri->segment(2) == "struktural" && $this->uri->segment(3) == "rekap" && $this->uri->segment(4) == "bimbingan-dosen") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Bimbingan Dosen
                                            </a>
                                        </li>
                                        <!-- <li>
                                            <a href="<?php echo site_url("dosen/struktural/rekap/ganti-ta-pbb") ?>" <?php if($this->uri->segment(2) == "struktural" && $this->uri->segment(3) == "rekap" && $this->uri->segment(4) == "ganti-ta-pbb") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Ganti Tema/Pembimbing
                                            </a>
                                        </li> -->
                                       
                                    </ul>
                                </li>

                                <li <?php if($this->uri->segment(2) == "rekap-pkl" || ($this->uri->segment(3) == "rekap-pkl" && $this->uri->segment(2) == "struktural")) echo 'class="mm-active"' ?>>
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-copy-file"></i>
                                        <?php 
                                          
                                        ?>
                                        Rekap KP/PKL 
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="<?php echo site_url("dosen/struktural/rekap-pkl/mahasiswa") ?>" <?php if(($this->uri->segment(2) == "rekap-pkl" && $this->uri->segment(3) == "mahasiswa") || ($this->uri->segment(3) == "rekap-pkl" && $this->uri->segment(2) == "struktural" && $this->uri->segment(4) == "mahasiswa")) echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Angkatan
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url("dosen/struktural/rekap-pkl/periode") ?>" <?php if(($this->uri->segment(2) == "rekap-pkl" && $this->uri->segment(3) == "periode") || ($this->uri->segment(3) == "rekap-pkl" && $this->uri->segment(2) == "struktural" && $this->uri->segment(4) == "periode")) echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Periode
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url("dosen/struktural/rekap-pkl/pembimbing") ?>" <?php if(($this->uri->segment(2) == "rekap-pkl" && $this->uri->segment(3) == "pembimbing") || ($this->uri->segment(3) == "rekap-pkl" && $this->uri->segment(2) == "struktural" && $this->uri->segment(4) == "pembimbing")) echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Pembimbing
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

                                <?php if(in_array(13,$tb)){ ?>
                                <!-- menu sekjur -->

                                <li class="app-sidebar__heading">Sekretaris Jurusan</li>
                                <li <?php if($this->uri->segment(2) == "approval" && ($this->uri->segment(3) == "sekjur")) echo 'class="mm-active"' ?>>
                                    <a href="#">
                                    <?php 
                                        $lf_sekjur = count($this->layanan_model->get_approval_sekjur_fakultas($this->session->userdata('userId')));

                                    ?>
                                        <i class="metismenu-icon pe-7s-notebook"></i>
                                        Layanan Fakultas <span class="badge badge-danger"><?php echo $lf_sekjur > 0 ? $lf_sekjur : "" ?></span>
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                        <a href="<?php echo site_url("dosen/approval/sekjur") ?>" <?php if($this->uri->segment(2) == "approval" && $this->uri->segment(3) == "sekjur") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Form Pegajuan Layanan <span class="badge badge-danger"><?php echo $lf_sekjur > 0 ? $lf_sekjur : "" ?></span>
                                            </a>
                                        </li>

                                    </ul>
                                </li>
                                <?php } ?>

                                <?php if(in_array(14,$tb)){ ?>
                                <!-- Menu Kaprodi -->

                                <li class="app-sidebar__heading">Ketua Program Studi</li>
                                <li <?php if($this->uri->segment(2) == "struktural" && $this->uri->segment(3) == "kaprodi" && $this->uri->segment(4) != "pkl") echo 'class="mm-active"' ?>>
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
                                    <?php if($tb_kaprodi->prodi == "11"){ ?>    
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

                                <?php if($tb_kaprodi->prodi == "11"){ ?>
                                <li <?php if($this->uri->segment(2) == "struktural" && $this->uri->segment(3) == "kaprodi" && $this->uri->segment(4) == "pkl") echo 'class="mm-active"' ?>>
                                    <a href="#">
                                        <?php 
                                            $pkl_kaprodi = count($this->pkl_model->get_pkl_mahasiswa_approval_kaprodi($this->session->userdata('userId')));
                                            $pkl_smr_kaprodi = count($this->pkl_model->get_mahasiswa_pkl_seminar_kaprodi($this->session->userdata('userId')));

                                            $kp_kaprodi = $pkl_kaprodi = $pkl_smr_kaprodi;
                                        ?>
                                        <i class="metismenu-icon pe-7s-note"></i>
                                        KP/PKL <span class="badge badge-danger"><?php echo $kp_kaprodi > 0 ? $kp_kaprodi : "" ?></span>
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="<?php echo site_url("dosen/struktural/kaprodi/pkl") ?>" <?php if($this->uri->segment(3) == "kaprodi" && $this->uri->segment(4) == "pkl") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Persetujuan KP/PKL <span class="badge badge-danger"><?php echo $pkl_kaprodi > 0 ? $pkl_kaprodi : "" ?></span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url("dosen/struktural/kaprodi/seminar-pkl") ?>" <?php if($this->uri->segment(3) == "kaprodi" && $this->uri->segment(4) == "seminar-pkl") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Seminar KP/PKL <span class="badge badge-danger"><?php echo $pkl_smr_kaprodi > 0 ? $pkl_smr_kaprodi : "" ?></span>
                                            </a>
                                        </li>

                                    </ul>

                                </li>
                                <?php } ?>
                                
                                <?php if($tb_kaprodi->prodi == "12"){ ?>
                                <li <?php if($this->uri->segment(2) == "approval") echo 'class="mm-active"' ?>>
                                    <a href="#">
                                    <?php 
                                     
                                    ?>
                                        <i class="metismenu-icon pe-7s-notebook"></i>
                                        Layanan Fakultas
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                        <a href="<?php echo site_url("dosen/approval/kaprodi-s3") ?>" <?php if($this->uri->segment(2) == "approval" && $this->uri->segment(3) == "kaprodi-s3") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Form Pengajuan Layanan
                                            </a>
                                        </li>

                                    </ul>
                                </li>
                                <?php } ?>

                                <li <?php if($this->uri->segment(2) == "approval" && ($this->uri->segment(3) == "kaprodi")) echo 'class="mm-active"' ?>>
                                    <a href="#">
                                    <?php 
                                        $lf_kaprodi = count($this->layanan_model->get_approval_sekjur_fakultas($this->session->userdata('userId')));

                                    ?>
                                        <i class="metismenu-icon pe-7s-notebook"></i>
                                        Layanan Fakultas <span class="badge badge-danger"><?php echo $lf_kaprodi > 0 ? $lf_kaprodi : "" ?></span>
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                        <a href="<?php echo site_url("dosen/approval/kaprodi") ?>" <?php if($this->uri->segment(2) == "approval" && $this->uri->segment(3) == "kaprodi") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Form Pegajuan Layanan <span class="badge badge-danger"><?php echo $lf_kaprodi > 0 ? $lf_kaprodi : "" ?></span>
                                            </a>
                                        </li>

                                    </ul>
                                </li>

                                <!--<li <?php if($this->uri->segment(2) == "kaprodi" && $this->uri->segment(3) == "rekap") echo 'class="mm-active"' ?>>-->
                                <!--    <a href="#">-->
                                <!--        <i class="metismenu-icon pe-7s-copy-file"></i>-->
                                         <?php 
                                            // $rekap = count($this->ta_model->get_ta_rekap($this->session->userdata('userId')));
                                        ?> 
                                <!--        Rekap -->
                                <!--         <span class="badge badge-danger"><?php echo $jml?></span> -->
                                <!--        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>-->
                                <!--    </a>-->
                                <!--    <ul>-->
                                <!--        <li>-->
                                <!--            <a href="#">-->
                                <!--                <i class="metismenu-icon">-->
                                <!--                </i>KP/PKL-->
                                <!--            </a>-->
                                <!--        </li>-->
                                <!--        <li>-->
                                <!--            <a href="<?php echo site_url("dosen/kaprodi/rekap/tugas-akhir") ?>" <?php if($this->uri->segment(2) == "kaprodi" && $this->uri->segment(3) == "rekap" && $this->uri->segment(4) == "tugas-akhir") echo 'class="mm-active"' ?>>-->
                                <!--                <i class="metismenu-icon">-->
                                <!--                 </i>Tugas Akhir -->
                                <!--            </a>-->
                                <!--        </li>-->
                                <!--        <li>-->
                                <!--            <a href="<?php echo site_url("dosen/kaprodi/rekap/seminar") ?>" <?php if($this->uri->segment(2) == "kaprodi" && $this->uri->segment(3) == "rekap" && $this->uri->segment(4) == "seminar") echo 'class="mm-active"' ?>>-->
                                <!--                <i class="metismenu-icon">-->
                                <!--                </i>Seminar-->
                                <!--            </a>-->
                                <!--        </li>-->
                                <!--        <li>-->
                                <!--            <a href="<?php echo site_url("dosen/kaprodi/rekap/mahasiswa-ta") ?>" <?php if($this->uri->segment(2) == "kaprodi" && $this->uri->segment(3) == "rekap" && $this->uri->segment(4) == "mahasiswa-ta") echo 'class="mm-active"' ?>>-->
                                <!--                <i class="metismenu-icon">-->
                                <!--                </i>Mahasiswa Tugas Akhir-->
                                <!--            </a>-->
                                <!--        </li>-->
                                <!--        <li>-->
                                <!--            <a href="<?php echo site_url("dosen/kaprodi/rekap/bimbingan-dosen") ?>" <?php if($this->uri->segment(2) == "kaprodi" && $this->uri->segment(3) == "rekap" && $this->uri->segment(4) == "bimbingan-dosen") echo 'class="mm-active"' ?>>-->
                                <!--                <i class="metismenu-icon">-->
                                <!--                </i>Bimbingan Dosen-->
                                <!--            </a>-->
                                <!--        </li>-->
                                <!--         <li>-->
                                <!--            <a href="<?php echo site_url("dosen/kaprodi/rekap/ganti-ta-pbb") ?>" <?php if($this->uri->segment(2) == "kaprodi" && $this->uri->segment(3) == "rekap" && $this->uri->segment(4) == "ganti-ta-pbb") echo 'class="mm-active"' ?>>-->
                                <!--                <i class="metismenu-icon">-->
                                <!--                </i>Ganti Tema/Pembimbing-->
                                <!--            </a>-->
                                <!--        </li> -->
                                       
                                <!--    </ul>-->
                                <!--</li>-->
                                <?php } ?>



                                <?php if(in_array(17,$tb)){ ?>
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
                                        Monitor
                                        <!-- <span class="badge badge-danger"><?php echo $jml?></span> -->
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <!-- <li>
                                            <a href="#">
                                                <i class="metismenu-icon">
                                                </i>KP/PKL
                                            </a>
                                        </li> -->
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
                                        <li>
                                            <a href="<?php echo site_url("dosen/koordinator/rekap/bimbingan-dosen") ?>" <?php if($this->uri->segment(2) == "koordinator" && $this->uri->segment(3) == "rekap" && $this->uri->segment(4) == "bimbingan-dosen") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Bimbingan Dosen
                                            </a>
                                        </li>
                                        <!-- <li>
                                            <a href="<?php echo site_url("dosen/koordinator/rekap/ganti-ta-pbb") ?>" <?php if($this->uri->segment(2) == "koordinator" && $this->uri->segment(3) == "rekap" && $this->uri->segment(4) == "ganti-ta-pbb") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Ganti Tema/Pembimbing
                                            </a>
                                        </li> -->
                                       
                                    </ul>
                                </li>
                                <?php } ?>

                                <?php if(in_array(19,$tb)){ ?>
                                <!-- Menu Koordinator kp-->

                                <li class="app-sidebar__heading">Koordinator KP/PKL</li>
                                <li <?php if($this->uri->segment(2) == "pkl" && $this->uri->segment(4) == "koordinator") echo 'class="mm-active"' ?>>
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-pen"></i>
                                        <?php 
                                            $seminar_kp_koor = count($this->pkl_model->get_seminar_mahasiswa_koor($this->session->userdata('userId')));
                                            $seminar_kp_koor_nilai = count($this->pkl_model->get_mahasiswa_seminar_nilai_koor($this->session->userdata('userId')));
                                            $mng_kp_koor = $seminar_kp_koor + $seminar_kp_koor_nilai;
                                        ?>
                                        Manajemen KP/PKL <span class="badge badge-danger"><?php echo $mng_kp_koor > 0 ? $mng_kp_koor : "" ?></span>
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="<?php echo site_url("dosen/pkl/pengajuan/koordinator") ?>" <?php if($this->uri->segment(2) == "pkl" && $this->uri->segment(3) == "pengajuan" && $this->uri->segment(4) == "koordinator") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Pendaftaran KP/PKL
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url("dosen/pkl/seminar/koordinator") ?>" <?php if($this->uri->segment(2) == "pkl" && $this->uri->segment(3) == "seminar" && $this->uri->segment(4) == "koordinator") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Seminar <span class="badge badge-danger"><?php echo $seminar_kp_koor > 0 ? $seminar_kp_koor : "" ?></span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url("dosen/pkl/seminar-nilai/koordinator") ?>" <?php if($this->uri->segment(2) == "pkl" && $this->uri->segment(3) == "seminar-nilai" && $this->uri->segment(4) == "koordinator") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Nilai Seminar <span class="badge badge-danger"><?php echo $seminar_kp_koor_nilai > 0 ? $seminar_kp_koor_nilai : "" ?></span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li <?php if($this->uri->segment(2) == "rekap-pkl") echo 'class="mm-active"' ?>>
                                    <a href="#">
                                        <i class="metismenu-icon pe-7s-copy-file"></i>
                                        <?php 
                                          
                                        ?>
                                        Rekap KP/PKL 
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="<?php echo site_url("dosen/rekap-pkl/mahasiswa") ?>" <?php if($this->uri->segment(2) == "rekap-pkl" && $this->uri->segment(3) == "mahasiswa") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Angkatan
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url("dosen/rekap-pkl/periode") ?>" <?php if($this->uri->segment(2) == "rekap-pkl" && $this->uri->segment(3) == "periode") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Periode
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo site_url("dosen/rekap-pkl/pembimbing") ?>" <?php if($this->uri->segment(2) == "rekap-pkl" && $this->uri->segment(3) == "pembimbing") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Pembimbing
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                               
                                <?php } ?>

                                <?php if(in_array(15,$tb)){ ?>
                                <!-- kepala laboratorium -->

                                <li class="app-sidebar__heading">Kepala Laboratorium</li>
                                <li <?php if($this->uri->segment(2) == "bebas-lab") echo 'class="mm-active"' ?>>
                                    <a href="#">
                                    <?php 
                                        $cek_lab = $this->layanan_model->get_lab_kalab_user($this->session->userdata('userId'))->jurusan_unit;
                                        $lab_approve = count($this->layanan_model->get_lab_kalab_form($cek_lab));
                                    
                                        $verif_lab = $lab_approve;
                                    ?>
                                        <i class="metismenu-icon pe-7s-notebook"></i>
                                        Verifikasi Bebas Lab <span class="badge badge-danger"><?php echo $verif_lab > 0 ? $verif_lab : "" ?></span>
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                            <a href="<?php echo site_url("dosen/bebas-lab/pengajuan") ?>" <?php if($this->uri->segment(2) == "bebas-lab" && $this->uri->segment(3) == "pengajuan") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon pe-7s-note2"></i>
                                                Pengajuan Bebas Lab <span class="badge badge-danger"><?php echo $lab_approve > 0 ? $lab_approve : "" ?></span>
                                            </a>
                                        </li>    
                                    </ul>
                                </li>

                                <li <?php if($this->uri->segment(2) == "approval" && $this->uri->segment(3) == "kalab") echo 'class="mm-active"' ?>>
                                    <a href="#">
                                    <?php 
                                        $lf_lab = count($this->layanan_model->get_approval_kalab_fakultas($this->session->userdata('userId')));
                                    ?>
                                        <i class="metismenu-icon pe-7s-notebook"></i>
                                        Layanan Fakultas <span class="badge badge-danger"><?php echo $lf_lab > 0 ? $lf_lab : "" ?></span>
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <li>
                                        <a href="<?php echo site_url("dosen/approval/kalab") ?>" <?php if($this->uri->segment(2) == "approval" && $this->uri->segment(3) == "kalab") echo 'class="mm-active"' ?>>
                                                <i class="metismenu-icon">
                                                </i>Form Pengajuan Layanan  <span class="badge badge-danger"><?php echo $lf_lab > 0 ? $lf_lab : "" ?></span>
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