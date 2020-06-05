</div> <!-- app-main__inner -->
                    <div class="app-wrapper-footer">
                    <div class="app-footer">
                        <div class="app-footer__inner"><small>
                            Copyright &copy; 2020 Fakultas Matematika dan Ilmu Pengetahuan Alam - Universitas Lampung | All Rights Reserved.<br>
                            UI Template by <a href="https://architectui.com/" target="_blank">ArchitectUI</a>.
                            </small>
                        </div>
                    </div>
                </div>
                </div> <!-- app-main__outer -->
            </div> <!-- app-main -->
        </div> <!-- app-container -->
        <script type="text/javascript" src="<?php echo base_url('assets/scripts/main.87c0748b313a1dda75f5.js') ?>"></script>
        

        
    </body>
</html>


<?php if($this->uri->segment(1) == 'kelola-pengguna') { ?>

<!-- Small modal -->

<div id="delUser" class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Hapus Pengguna</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="delete-akun" method="post" action="<?php echo site_url("hapus-pengguna") ?>">
                    <input type="hidden" name="userID" id="userID" value="">
                </form>
                <p>Apakah Anda yakin akan menghapus pengguna <span id="userNama" class="badge badge-pill badge-focus">Ardiansyah</span> ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="delete-akun" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya, hapus</button>
            </div>
        </div>
    </div>
</div>

<?php } ?>

<?php if($this->uri->segment(3) == 'tema' && $this->uri->segment(4) == 'lampiran') { ?>

<!-- Normal modal -->

<div class="modal fade" id="delBerkas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Hapus Berkas Lampiran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="delete-berkas" method="post" action="<?php echo site_url("mahasiswa/hapus-berkas-ta") ?>">
                    <input type="hidden" name="id_berkas" id="berkasID" value="">
                    <input type="hidden" name="id_pengajuan" id="pengajuanID" value="">
                    <input type="hidden" name="file_berkas" id="berkasFile" value="">
                </form>
                <p>Apakah Anda yakin akan menghapus berkas <span id="berkasNama" class="text-danger">?</p>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="delete-berkas" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya, hapus</button>
            </div>
        </div>
    </div>
</div>



<?php } ?>

<?php if($this->uri->segment(3) == 'tema') { ?>
<!-- delete pengajuan -->

<div class="modal fade" id="delPengajuan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Hapus Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="delete-berkas" method="post" action="<?php echo site_url("mahasiswa/hapus-data-ta") ?>">
                    <input type="hidden" name="id_ta" id="ID" value="">
                    <!-- <input type="hidden" name="id_pengajuan" id="pengajuanID" value="">
                    <input type="hidden" name="file_berkas" id="berkasFile" value=""> -->
                    <!-- <input type="text" class="form-control" name="ID" id="ID" value=""> -->
                </form>
                <p>Apakah Anda yakin akan menghapus data ? </p>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="delete-berkas" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya, hapus</button>
            </div>
        </div>
    </div>
</div>

<!-- ajukan tema -->
<div class="modal fade" id="Ajukan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Pengajuan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="ajukan" method="post" action="<?php echo site_url("mahasiswa/tugas-akhir/tema/ajukan") ?>">
                    <input type="hidden" name="id_ta" id="ID2" value="">
                    <!-- <input type="hidden" name="id_pengajuan" id="pengajuanID" value="">
                    <input type="hidden" name="file_berkas" id="berkasFile" value=""> -->
                    <!-- <input type="text" class="form-control" name="ID" id="ID" value=""> -->
                </form>
                <p>Konfimasi Pengajuan ? </p>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="ajukan" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>

<!-- ajukan perbaikan tema -->
<div class="modal fade" id="AjukanPerbaikan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Pengajuan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="ajukan-perbaikan" method="post" action="<?php echo site_url("mahasiswa/tugas-akhir/tema/ajukan-perbaikan") ?>">
                    <input type="hidden" name="id_perbaikan" id="IDPerbaikan" value="">
                    <!-- <input type="hidden" name="id_pengajuan" id="pengajuanID" value="">
                    <input type="hidden" name="file_berkas" id="berkasFile" value=""> -->
                    <!-- <input type="text" class="form-control" name="ID" id="ID" value=""> -->
                </form>
                <p>Konfimasi Pengajuan ?</p>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="ajukan-perbaikan" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>

<?php } ?>

<?php if($this->uri->segment(1) == 'dosen' && $this->uri->segment(3) == 'tema') { ?>

<!-- approval tema -->
<div class="modal fade" id="Approval" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Setujui Pengajuan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="approval" method="post" action="<?php echo site_url("dosen/tugas-akhir/tema/approve") ?>">
                    <input type="hidden" name="id_ta" id="ID3" value="">
                    <input type="hidden" name="status" id="status" value="">
                    <!-- <input type="hidden" name="id_pengajuan" id="pengajuanID" value="">
                    <input type="hidden" name="file_berkas" id="berkasFile" value=""> -->
                    <!-- <input type="text" class="form-control" name="ID3" id="status" value=""> -->
                </form>
                <p>Setujui Pengajuan ? </p>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="approval" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>

<!-- tolak approval tema -->
<div class="modal fade" id="ApprovalTolak" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tolak Pengajuan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="tolakapproval" method="post" action="<?php echo site_url("dosen/tugas-akhir/tema/decline") ?>">
                    <input type="hidden" name="id_ta" id="ID4" value="">
                    <input type="hidden" name="status" id="status2" value="">
                    <!-- <input type="hidden" name="id_pengajuan" id="pengajuanID" value="">
                    <input type="hidden" name="file_berkas" id="berkasFile" value=""> -->
                    <p>Tolak Pengajuan ? </p>
                    <textarea name="keterangan" cols="70" rows="3" placeholder="Keterangan Tolak"></textarea>
                </form>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="tolakapproval" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>

<!-- tolak approval tema Koor -->
<div class="modal fade" id="ApprovalTolakKoor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tolak Pengajuan Tema</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="tolak-approval-koor" method="post" action="<?php echo site_url("dosen/tugas-akhir/tema/koordinator/decline") ?>">
                    <input type="hidden" name="id_ta" id="IDKoor" value="">
                    <!-- <input type="hidden" name="status" id="status2" value=""> -->
                    <!-- <input type="hidden" name="id_pengajuan" id="pengajuanID" value="">
                    <input type="hidden" name="file_berkas" id="berkasFile" value=""> -->
                    <p>Tolak Pengajuan Tema ? </p>
                    <textarea name="keterangan" cols="70" rows="3" placeholder="Keterangan Tolak"></textarea>
                </form>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="tolak-approval-koor" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>

<?php } ?>

<?php if($this->uri->segment(1) == 'tendik' && $this->uri->segment(2) == 'verifikasi-berkas') { ?>

<!-- verifikasi Berkas -->
<div class="modal fade" id="Approval" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Setujui Pengajuan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="verif-berkas" method="post" action="<?php echo site_url("tendik/verifikasi-berkas/approve") ?>">
                    <input type="hidden" name="id_ta" id="ID5" value="">
                    <!-- <input type="hidden" name="id_pengajuan" id="pengajuanID" value="">
                    <input type="hidden" name="file_berkas" id="berkasFile" value=""> -->
                    <!-- <input type="text" class="form-control" name="ID3" id="status" value=""> -->
                </form>
                <p>Verifikasi Berkas ? </p>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="verif-berkas" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>

<!-- tolak verifikasi berkas -->
<div class="modal fade" id="ApprovalTolak" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tolak Pengajuan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="tolak-verif-berkas" method="post" action="<?php echo site_url("tendik/verifikasi-berkas/decline") ?>">
                    <input type="hidden" name="id_ta" id="ID6" value="">
                    <!-- <input type="hidden" name="id_pengajuan" id="pengajuanID" value="">
                    <input type="hidden" name="file_berkas" id="berkasFile" value=""> -->
                    <p>Tolak Verifikasi ? </p>
                    <textarea name="keterangan" cols="70" rows="3" placeholder="Keterangan Tolak"></textarea>
                </form>
                
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="tolak-verif-berkas" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>

<?php } ?>

<?php if($this->uri->segment(3) == 'seminar' && $this->uri->segment(4) == 'lampiran') { ?>

<!-- Del Lampiran Seminar -->
<div class="modal fade" id="delBerkasSeminar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Hapus Berkas Lampiran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="deleteberkas" method="post" action="<?php echo site_url("mahasiswa/hapus-berkas-seminar") ?>">
                    <input type="hidden" name="id_berkas" id="berkasID" value="">
                    <input type="hidden" name="id_seminar" id="IDSeminar" value="">
                    <input type="hidden" name="file_berkas" id="berkasFile" value="">
                </form>
                <p>Apakah Anda yakin akan menghapus <span id="berkasNama" class="text-danger"> ?</p>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="deleteberkas" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya, hapus</button>
            </div>
        </div>
    </div>
</div>



<?php } ?>

<?php if($this->uri->segment(1) == 'mahasiswa' && $this->uri->segment(3) == 'seminar' && $this->uri->segment(4) != 'lampiran') { ?>

<!-- Delete pengajuan seminar -->
<div class="modal fade" id="delPengajuan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Hapus Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="delete-berkas" method="post" action="<?php echo site_url("mahasiswa/hapus-data-seminar") ?>">
                    <input type="hidden" name="id_seminar" id="ID" value="">
                    <!-- <input type="hidden" name="id_pengajuan" id="pengajuanID" value="">
                    <input type="hidden" name="file_berkas" id="berkasFile" value=""> -->
                    <!-- <input type="text" class="form-control" name="ID" id="ID" value=""> -->
                </form>
                <p>Apakah Anda yakin akan menghapus data seminar ? </p>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="delete-berkas" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya, hapus</button>
            </div>
        </div>
    </div>
</div>

<?php } ?>
