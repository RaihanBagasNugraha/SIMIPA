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
                    <input type="hidden" name="status" id="Status" value="">
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

<?php if($this->uri->segment(1) == 'dosen' && ($this->uri->segment(3) == 'tema' || $this->uri->segment(3) == 'kaprodi')) { ?>

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

<?php if($this->uri->segment(1) == 'tendik' && $this->uri->segment(2) == 'verifikasi-berkas' && $this->uri->segment(3) != 'seminar') { ?>

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

<?php if($this->uri->segment(1) == 'tendik' && $this->uri->segment(2) == 'verifikasi-berkas' && $this->uri->segment(3) == 'seminar') { ?>

<!-- tolak verifikasi berkas -->
<div class="modal fade" id="seminar-tolak" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tolak Pengajuan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="seminartolak" method="post" action="<?php echo site_url("tendik/verifikasi-berkas/seminar/decline") ?>">
                    <input type="hidden" name="id_seminar" id="ID" value="">
                    <input type="hidden" name="status" id="status" value="admin">
                    <!-- <input type="hidden" name="id_pengajuan" id="pengajuanID" value="">
                    <input type="hidden" name="file_berkas" id="berkasFile" value=""> -->
                    <p>Tolak Verifikasi Berkas Seminar ? </p>
                    <textarea name="keterangan" cols="70" rows="3" placeholder="Keterangan Tolak"></textarea>
                </form>
                
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="seminartolak" class="btn btn-primary">
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

<!-- ajukan tema -->
<div class="modal fade" id="AjukanSeminar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Pengajuan Seminar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="ajukan" method="post" action="<?php echo site_url("mahasiswa/ajukan-data-seminar") ?>">
                    <input type="hidden" name="id_seminar" id="ID2" value="">
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

<!-- ajukan perbaikan seminar -->
<div class="modal fade" id="AjukanPerbaikan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Pengajuan Seminar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="ajukan-perbaikan" method="post" action="<?php echo site_url("mahasiswa/ajukan-perbaikan-seminar") ?>">
                    <input type="hidden" name="id_seminar" id="IDPerbaikan" value="">
                    <input type="hidden" name="status" id="Status" value="">
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
                <button type="submit" form="ajukan-perbaikan" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>

<?php } ?>

<?php if($this->uri->segment(1) == 'dosen' && ($this->uri->segment(3) == 'seminar' || $this->uri->segment(3) == 'kaprodi')) { ?>

<!-- tolak seminar -->
<div class="modal fade" id="seminarTolak" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Pengajuan Seminar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="tolak" method="post" action="<?php echo site_url("dosen/tolak-data-seminar") ?>">
                    <input type="hidden" name="id_seminar" id="ID" value="">
                    <input type="hidden" name="status" id="status" value="">
                    <!-- <input type="hidden" name="id_pengajuan" id="pengajuanID" value="">
                    <input type="hidden" name="file_berkas" id="berkasFile" value=""> -->
                    <!-- <input type="text" class="form-control" name="ID" id="ID" value=""> -->
                    <p>Tolak Pengajuan Seminar ? </p>
                    <textarea name="keterangan" cols="70" rows="3" placeholder="Keterangan Tolak"></textarea>
                </form>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="tolak" class="btn btn-primary">
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
                <h5 class="modal-title" id="exampleModalLabel">Tolak Pengajuan Seminar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="tolak-approval-koor" method="post" action="<?php echo site_url("dosen/tugas-akhir/seminar/koordinator/decline") ?>">
                    <input type="hidden" name="id_seminar" id="IDKoor" value="">
                    <input type="hidden" name="status" id="status" value="koor">
                    <!-- <input type="hidden" name="id_pengajuan" id="pengajuanID" value="">
                    <input type="hidden" name="file_berkas" id="berkasFile" value=""> -->
                    <p>Tolak Pengajuan Seminar ? </p>
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

<?php if($this->uri->segment(1) == 'dosen' && $this->uri->segment(2) == 'struktural' && $this->uri->segment(3) == 'bidang-nilai' && $this->uri->segment(4) == 'komposisi-nilai') { ?>

<div class="modal fade" id="nonaktifkan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="tolak-approval-koor" method="post" action="<?php echo site_url("dosen/struktural/komposisi-nilai/nonaktifkan") ?>">
                    <input type="hidden" name="id" id="ID" value="">
                Nonaktifkan Komposisi Nilai ?
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

<?php if($this->uri->segment(1) == 'dosen' && $this->uri->segment(2) == 'struktural' && $this->uri->segment(3) == 'bidang-nilai' && $this->uri->segment(4) == 'bidang-jurusan' && $this->uri->segment(5) == 'show') { ?>

<div class="modal fade" id="tambahbidang" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="tambah-bidang" method="post" action="<?php echo site_url("dosen/struktural/bidang-nilai/bidang-jurusan/add") ?>">
                    <input type="hidden" name="jurusan" id="Jurusan" value="">
                    <input type="hidden" name="prodi" id="Prodi" value="">
                    
                    <input type="text" name="nama" value="" class = "form-control" placeholder="Nama Bidang">
                </form>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="tambah-bidang" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Tambah</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deletebidang" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="delete-bidang" method="post" action="<?php echo site_url("dosen/struktural/bidang-nilai/bidang-jurusan/delete") ?>">
                    <input type="hidden" name="id" id="ID" value="">
                    <input type="hidden" name="jurusan" id="Jur" value="">
                    <input type="hidden" name="prodi" id="Pro" value="">
                Hapus Bidang Ilmu ?
                </form>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="delete-bidang" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>


<?php } ?>

<?php if($this->uri->segment(1) == 'dosen' && $this->uri->segment(2) == 'struktural' && $this->uri->segment(3) == 'bidang-nilai' && $this->uri->segment(4) == 'komposisi-ta') { ?>

<div class="modal fade" id="tambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="tambah-komponen" method="post" action="<?php echo site_url("dosen/struktural/bidang-nilai/komposisi-ta/save") ?>">
                    <input type="hidden" name="bidang" id="ID" value="">
                    <input type="hidden" name="ket" id="Ket" value="">
                    
                    <input type="text" name="komponen" value="" class = "form-control" placeholder="Nama Komponen">
                </form>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="tambah-komponen" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Tambah</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="hapus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="delete" method="post" action="<?php echo site_url("dosen/struktural/bidang-nilai/komposisi-ta/delete") ?>">
                    <input type="hidden" name="id" id="IDs" value="">
                    <input type="hidden" name="bidang" id="Bidang" value="">
                Hapus Komponen ?
                </form>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="delete" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>

<?php } ?>

<?php if($this->uri->segment(1) == 'mahasiswa' && $this->uri->segment(2) == 'tugas-akhir' && $this->uri->segment(3) == 'verifikasi-ta') { ?>

<div class="modal fade" id="Ajukan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="ajukan-ver" method="post" action="<?php echo site_url("mahasiswa/tugas-akhir/verifikasi-ta/ajukan") ?>">
                    <input type="hidden" name="id" id="ID" value="">
                   
                </form>
                Ajukan Verifikasi Program Tugas Akhir ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="ajukan-ver" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>

<?php } ?>

<?php if($this->uri->segment(1) == 'dosen' && $this->uri->segment(2) == 'kelola-biodata') { ?>

<div class="modal fade" id="tambahtugas" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Tugas Tambahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="tgs-tmbh" method="post" action="<?php echo site_url("dosen/biodata-tugas-tambahan") ?>">
                    <input type="hidden" name="iduser" id="IDUser" value="">
                    <label><b>Tugas Tambahan</b></label>
                        <select name="tugas_tambahan" id="tugas"  class="input-lg form-control">
                            <option value = "">-- Pilih Tugas Tambahan --</option>
                                <?php
                                $list_tugas = $this->user_model->get_tugas_tambahan_all();
                                            
                                foreach ($list_tugas as $row) {
                                    // if($biodata->pangkat_gol == $row->id_pangkat_gol) $select = "selected";
                                    // else $select = "";
                                    echo "<option ".$select." value='".$row->id_tugas_tambahan."'>".$row->nama."</option>";
                                }
                                ?>
                        </select>
                    <br>    
                    <div id="prodi" style="display: none;">
                    <label><b>Program Studi</b></label>
                        <select name="prodi" class="input-lg form-control">
                        <option value = "">-- Pilih Prodi --</option>
                                <?php
                                $list_prodi = $this->user_model->get_prodi_all();
                                            
                                foreach ($list_prodi as $row) {
                                    // if($biodata->pangkat_gol == $row->id_pangkat_gol) $select = "selected";
                                    // else $select = "";
                                    echo "<option ".$select." value='".$row->id_prodi."'>".$row->nama."</option>";
                                }
                                ?>
                        </select>
                    <br> 
                    </div>    

                    <div id="jurusan" style="display: none;">
                    <label><b>Jurusan</b></label>
                        <select name="jurusan" class="input-lg form-control">
                        <option value = "">-- Pilih Jurusan --</option>
                                <?php
                                $list_jurusan = $this->user_model->get_jurusan_all();
                                            
                                foreach ($list_jurusan as $row) {
                                    // if($biodata->pangkat_gol == $row->id_pangkat_gol) $select = "selected";
                                    // else $select = "";
                                    echo "<option ".$select." value='".$row->id_jurusan."'>".$row->nama."</option>";
                                }
                                ?>
                        </select>
                    <br> 
                    </div>   

                    <div id="lab" style="display: none;">
                    <label><b>Laboratorium</b></label>
                        <select name="lab" class="input-lg form-control">
                        <option value = "">-- Pilih Laboratorium --</option>
                                <?php
                                $list_lab = $this->user_model->get_lab_all();
                                            
                                foreach ($list_lab as $row) {
                                    // if($biodata->pangkat_gol == $row->id_pangkat_gol) $select = "selected";
                                    // else $select = "";
                                    echo "<option ".$select." value='".$row->id_lab."'>".$row->nama_lab."</option>";
                                }
                                ?>
                        </select>
                    <br> 
                    </div>     
                       
                    <label><b>Periode</b></label>    
                        <input name="periode" id="periode" value="" type="text" placeholder="2020/2024" class="form-control"> 
                    
                    <br> 
                     <label><b>Status</b></label>    
                        <select name="status_tgs" class="input-lg form-control">
                            <option>-- Status --</option>
                            <option value ="1">Aktif</option>
                            <option value ="0">Nonaktif</option>
                        </select>  
                </form>
               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="tgs-tmbh" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="nonaktiftugas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="nonaktif-tugas" method="post" action="<?php echo site_url("dosen/biodata-tugas-tambahan-hapus") ?>">
                    <input type="hidden" name="id_tugas" id="IDTugas" value="">
                    <input type="hidden" name="ket" id="Keterangan" value="">
                </form>
                Simpan Perubahan ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="nonaktif-tugas" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>

<script>
 $(document).ready(function(){   
    $('#tugas').on('change', function() {
          if (this.value == '14')
          {
            jQuery("#prodi").show();
            jQuery("#jurusan").hide();
            jQuery("#lab").hide();
          }
          else if(this.value == '19' || this.value == '12' || this.value == '13' || this.value == '17' || this.value == '18')
          {
            jQuery("#prodi").hide();
            jQuery("#jurusan").show();
            jQuery("#lab").hide();
          }
          else if(this.value == '15' || this.value == '16')
          {
            jQuery("#prodi").hide();
            jQuery("#jurusan").hide();
            jQuery("#lab").show();
          }
          else{
            jQuery("#prodi").hide();
            jQuery("#jurusan").hide();
            jQuery("#lab").hide();
          }
        });
    }); 
    
</script>

<?php } ?>

<?php if($this->uri->segment(1) == 'tendik' && $this->uri->segment(2) == 'kelola-biodata') { ?>

<div class="modal fade" id="tambahtugas" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Tugas Tambahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="tgs-tmbh" method="post" action="<?php echo site_url("tendik/biodata-tugas-tambahan") ?>">
                    <input type="hidden" name="iduser" id="IDUser" value="">
                    <label><b>Tugas Tambahan</b></label>
                        <select name="tugas_tambahan" id="tugas"  class="input-lg form-control">
                            <option value = "">-- Pilih Tugas Tambahan --</option>
                                <?php
                                $list_tugas = $this->user_model->get_tugas_tambahan_all();
                                            
                                foreach ($list_tugas as $row) {
                                    // if($biodata->pangkat_gol == $row->id_pangkat_gol) $select = "selected";
                                    // else $select = "";
                                    echo "<option ".$select." value='".$row->id_tugas_tambahan."'>".$row->nama."</option>";
                                }
                                ?>
                        </select>
                    <br>    
                    <div id="prodi" style="display: none;">
                    <label><b>Program Studi</b></label>
                        <select name="prodi" class="input-lg form-control">
                        <option value = "">-- Pilih Prodi --</option>
                                <?php
                                $list_prodi = $this->user_model->get_prodi_all();
                                            
                                foreach ($list_prodi as $row) {
                                    // if($biodata->pangkat_gol == $row->id_pangkat_gol) $select = "selected";
                                    // else $select = "";
                                    echo "<option ".$select." value='".$row->id_prodi."'>".$row->nama."</option>";
                                }
                                ?>
                        </select>
                    <br> 
                    </div>    

                    <div id="jurusan" style="display: none;">
                    <label><b>Jurusan</b></label>
                        <select name="jurusan" class="input-lg form-control">
                        <option value = "">-- Pilih Jurusan --</option>
                                <?php
                                $list_jurusan = $this->user_model->get_jurusan_all();
                                            
                                foreach ($list_jurusan as $row) {
                                    // if($biodata->pangkat_gol == $row->id_pangkat_gol) $select = "selected";
                                    // else $select = "";
                                    echo "<option ".$select." value='".$row->id_jurusan."'>".$row->nama."</option>";
                                }
                                ?>
                        </select>
                    <br> 
                    </div>    

                    <div id="lab" style="display: none;">
                    <label><b>Laboratorium</b></label>
                        <select name="lab" class="input-lg form-control">
                        <option value = "">-- Pilih Laboratorium --</option>
                                <?php
                                $list_lab = $this->user_model->get_lab_all();
                                            
                                foreach ($list_lab as $row) {
                                    // if($biodata->pangkat_gol == $row->id_pangkat_gol) $select = "selected";
                                    // else $select = "";
                                    echo "<option ".$select." value='".$row->id_lab."'>".$row->nama_lab."</option>";
                                }
                                ?>
                        </select>
                    <br> 
                    </div>     
                       
                    <label><b>Periode</b></label>    
                        <input name="periode" id="periode" value="" type="text" placeholder="2020/2024" class="form-control"> 
                    
                    <br> 
                     <label><b>Status</b></label>    
                        <select name="status_tgs" class="input-lg form-control">
                            <option>-- Status --</option>
                            <option value ="1">Aktif</option>
                            <option value ="0">Nonaktif</option>
                        </select>  
                </form>
               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="tgs-tmbh" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="nonaktiftugas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="nonaktif-tugas" method="post" action="<?php echo site_url("tendik/biodata-tugas-tambahan-hapus") ?>">
                    <input type="hidden" name="id_tugas" id="IDTugas" value="">
                    <input type="hidden" name="ket" id="Keterangan" value="">
                </form>
                Simpan Perubahan ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="nonaktif-tugas" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>

<script>
 $(document).ready(function(){   
    $('#tugas').on('change', function() {
          if (this.value == '14')
          {
            jQuery("#prodi").show();
            jQuery("#jurusan").hide();
            jQuery("#lab").hide();
          }
          else if(this.value == '19' || this.value == '12' || this.value == '13' || this.value == '17' || this.value == '18')
          {
            jQuery("#prodi").hide();
            jQuery("#jurusan").show();
            jQuery("#lab").hide();
          }
          else if(this.value == '15' || this.value == '16')
          {
            jQuery("#prodi").hide();
            jQuery("#jurusan").hide();
            jQuery("#lab").show();
          }
          else{
            jQuery("#prodi").hide();
            jQuery("#jurusan").hide();
            jQuery("#lab").hide();
          }
        });
    }); 
    
</script>

<?php } ?>

<?php if($this->uri->segment(1) == 'mahasiswa' && $this->uri->segment(2) == 'kelola-biodata') { ?>

    <div class="modal fade" id="tambahlk"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Pengisian</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="lk-tmbh" method="post" action="<?php echo site_url("mahasiswa/mahasiswa-add-lk") ?>">
                    <input type="hidden" name="iduser" id="IDUser" value="">
                    <label><b>Lembaga Kemahasiswaan</b></label>
                        <select name="id_lk" id="tugas"  class="input-lg form-control">
                            <option value = "">-- Pilih Lembaga Kemahasiswaan --</option>
                                <?php
                                $list_lk = $this->user_model->get_lk_all();
                                            
                                foreach ($list_lk as $row) {
                                    // if($biodata->pangkat_gol == $row->id_pangkat_gol) $select = "selected";
                                    // else $select = "";
                                    echo "<option ".$select." value='".$row->id_lk."'>".$row->nama_lk."</option>";
                                }
                                ?>
                        </select>
                    <br>    
                    <label><b>Jabatan</b></label>    
                        <select name="jabatan_lk" class="input-lg form-control">
                            <option>-- Jabatan --</option>
                            <option value ="1">Ketua Umum</option>
                            <option value ="2">Wakil Ketua Umum</option>
                            <option value ="3">Sekretaris Umum</option>
                            <option value ="4">Bendahara Umum</option>
                            <option value ="5">Anggota</option>
                        </select>  
                    <br>    

                    <label><b>Periode</b></label>
                    <?php $thn = date("Y"); $thn2 = date("Y")+1 ?>
                        <select name="periode_lk" id="periode" class="input-lg form-control">
                            <option value = ''>-- Periode --</option>
                            <option value ="<?php echo $thn."/".$thn2 ?>"><?php echo $thn."/".$thn2 ?></option>
                        </select>  
                        
                    <br> 

                    <input name="status_lk" id="" value="1" type="hidden" class="form-control">  
                </form>
               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="lk-tmbh" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="nonaktiflk" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="nonaktif-lk" method="post" action="<?php echo site_url("mahasiswa/mahasiswa-update-lk") ?>">
                    <input type="hidden" name="id_tugas" id="IDTugas" value="">
                    <input type="hidden" name="ket" id="Keterangan" value="">
                </form>
                Simpan Perubahan ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="nonaktif-lk" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>

<?php } ?>

<?php if($this->uri->segment(1) == 'dosen' && $this->uri->segment(2) == 'koordinator' && $this->uri->segment(3) == 'rekap' && $this->uri->segment(4) == 'tugas-akhir' && $this->uri->segment(5) == 'detail') { ?>

<div class="modal fade" id="gantitema" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Simpan Perubahan ? -->
                <b>Tema Yang Diganti Akan Menjadi Nonaktif dan Mahasiswa Ini Harus Melakukan Pengajuan Tema Ulang.</b>
                <br><br>
                <form id="ganti-tema" method="post" action="<?php echo site_url("dosen/koordinator/rekap/tugas-akhir/detail/ganti-ta") ?>">
                    <input type="hidden" name="id_ta" id="IDta" value="">
                    <textarea name="keterangan" cols="70" rows="3" placeholder="Keterangan" required></textarea>
                </form>
                <!-- <br> -->
                Simpan Perubahan ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="ganti-tema" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="gantipbb" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Simpan Perubahan ? -->
                <b>Ganti Komisi Pembahas / Penguji Tugas Akhir Mahasiswa ? Progress Tugas Akhir Yang Lama Akan Tetap Tersimpan</b>
                <br><br>
                <form id="ganti-pbb" method="post" action="<?php echo site_url("dosen/koordinator/rekap/tugas-akhir/detail/ganti-pbb") ?>">
                    <input type="hidden" name="id_pengajuan" id="IDpbb" value="">
                </form>
                <!-- <br> -->
                <!-- Simpan Perubahan ? -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="ganti-pbb" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>


<?php } ?>

<?php if($this->uri->segment(1) == 'dosen' && $this->uri->segment(2) == 'struktural' && $this->uri->segment(3) == 'pkl' && $this->uri->segment(4) == 'add-pkl') { ?>

<!-- hapus kp / pkl -->
<div class="modal fade" id="delkp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="delete-pkl" method="post" action="<?php echo site_url("dosen/struktural/pkl/add-pkl/delete") ?>">
                    <input type="hidden" name="id_pkl" id="ID" value="">
                Hapus KP/PKL ?
                </form>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="delete-pkl" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<?php if($this->uri->segment(1) == 'dosen' && $this->uri->segment(2) == 'struktural' && $this->uri->segment(3) == 'pkl' && $this->uri->segment(4) == 'add-lokasi-pkl') { ?>

<!-- hapus lokasi kp / pkl -->
<div class="modal fade" id="dellokasi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="delete-lok" method="post" action="<?php echo site_url("dosen/struktural/pkl/add-lokasi-pkl/aksi/delete") ?>">
                    <input type="hidden" name="id_lokasi" id="IDLokasi" value="">
                    <input type="hidden" name="id_aksi" id="IDAksi" value="">
                Hapus Lokasi KP/PKL ?
                </form>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="delete-lok" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>

<!-- edit lokasi kp / pkl -->
<div class="modal fade" id="editlokasi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ubah Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="edit-lok" method="post" action="<?php echo site_url("dosen/struktural/pkl/add-lokasi-pkl/aksi/edit") ?>">
                    <input type="hidden" name="id_lokasi" id="IDLokasi2" value="">
                    <input type="hidden" name="id_aksi" id="IDAksi2" value="">
                    <label><b>Lokasi KP/PKL</b></label>
                    <input type="text" name="lokasi" id="Lokasi" value="" class = 'form-control' placeholder = "Lokasi KP/PKL">
                    <br>
                    <label><b>Alamat Lokasi KP/PKL</b></label>
                    <textarea name="alamat" class="form-control" id="Alamat" placeholder="Alamat lokasi KP/PKL..."></textarea>
                </form>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="edit-lok" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>

<?php } ?>

<?php if($this->uri->segment(1) == 'mahasiswa' && $this->uri->segment(2) == 'pkl' && $this->uri->segment(3) == 'pkl-home') { ?>

<!-- del kp / pkl mahasiswa-->
<div class="modal fade" id="delPengajuanPkl" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="delete-lok" method="post" action="<?php echo site_url("mahasiswa/pkl/pkl-home/delete") ?>">
                    <input type="hidden" name="id_pkl" id="ID" value="">
                Hapus Pengajuan KP/PKL ?
                </form>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="delete-lok" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>

<!-- ajukan kp / pkl mahasiswa-->
<div class="modal fade" id="Ajukanpkl" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="ajukan-kp" method="post" action="<?php echo site_url("mahasiswa/pkl/pkl-home/ajukan") ?>">
                    <input type="hidden" name="id_pkl" id="ID2" value="">
                Ajukan KP/PKL ?
                </form>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="ajukan-kp" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>

<!-- ajukan perbaikan kp / pkl mahasiswa-->
<div class="modal fade" id="AjukanPerbaikanPkl" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="ajukan-perbaikan-kp" method="post" action="<?php echo site_url("mahasiswa/pkl/pkl-home/perbaikan") ?>">
                    <input type="hidden" name="id_pkl" id="IDPerbaikan" value="">
                    <input type="hidden" name="status" id="Status" value="">
                Ajukan Perbaikan KP/PKL ?
                </form>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="ajukan-perbaikan-kp" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>

<!-- ajukan berkas instansi-->
<div class="modal fade" id="AjukanInstansi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="ajukan-perbaikan-i" method="post" action="<?php echo site_url("mahasiswa/pkl/pkl-home/berkas-instansi") ?>">
                    <input type="hidden" name="approval_id" id="IDInstansi" value="">
                Ajukan Berkas ?
                </form>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="ajukan-perbaikan-i" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>

<!-- Pb lapangan -->
<div class="modal fade" id="PbLapangan" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Data Pembimbing Lapangan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="pb-lapangan" method="post" action="<?php echo site_url("mahasiswa/pkl/pkl-home/pb-lapangan") ?>">
                    <input type="hidden" name="pkl_id" id="ID4" value="">
                    <label><b>Nama</b></label>
                    <input type="text" required class="form-control" name="nama" placeholder="Nama Lengkap dan Gelar" value="">
                    <br>
                    <label><b>NIP / NIK</b></label>
                    <input type="text" required class="form-control" name="nip_nik" placeholder="NIP Atau NIK" value="">
                    <br>
                    <label><b>Email</b></label>
                    <input type="email" required class="form-control" name="email" placeholder="Alamat Email" value="">
                    <!-- <small><span style="color:red">* Form Penilaian Pembimbing Lapangan Akan Dikirim Melalui Email</span></small> -->
                    <br>
                    <label><b>No. Handphone</b></label>
                    <input type="text" required class="form-control" name="no_telp" placeholder="Nomor Handphone" value="">
                    
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="pb-lapangan" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Pb lapangan -->
<div class="modal fade" id="PbLapanganUbah" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Data Pembimbing Lapangan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="pb-lapangan-edit" method="post" action="<?php echo site_url("mahasiswa/pkl/pkl-home/pb-lapangan-ubah") ?>">
                    <input type="hidden" name="pkl_id" id="ID5" value="">
                    <label><b>Nama</b></label>
                    <input type="text" required class="form-control" name="nama" id="Nama" placeholder="Nama Lengkap dan Gelar" value="">
                    <br>
                    <label><b>NIP / NIK</b></label>
                    <input type="text" required class="form-control" name="nip_nik" id="nip" placeholder="NIP Atau NIK" value="">
                    <br>
                    <label><b>Email</b></label>
                    <input type="email" required class="form-control" name="email" id="email" placeholder="Alamat Email" value="">
                    <!-- <small><span style="color:red">* Form Penilaian Pembimbing Lapangan Akan Dikirim Melalui Email</span></small> -->
                    <br>
                    <label><b>No. Handphone</b></label>
                    <input type="text" required class="form-control" name="no_telp" id="telp" placeholder="Nomor Handphone" value="">
                    
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="pb-lapangan-edit" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Simpan</button>
            </div>
        </div>
    </div>
</div>

<?php } ?>

<?php if($this->uri->segment(3) == 'pkl-home' && $this->uri->segment(4) == 'lampiran') { ?>

<div class="modal fade" id="delBerkaskp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Hapus Berkas Lampiran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="delete-berkas" method="post" action="<?php echo site_url("mahasiswa/pkl/pkl-home/lampiran/delete") ?>">
                    <input type="hidden" name="id_berkas" id="berkasID" value="">
                    <input type="hidden" name="id_pkl" id="pklID" value="">
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

<div class="modal fade" id="delBerkaskps" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Hapus Berkas Lampiran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="delete-berkas2" method="post" action="<?php echo site_url("mahasiswa/pkl/pkl-home/lampiran/delete-instansi") ?>">
                    <input type="hidden" name="approval_id" id="id" value="">
                    
                </form>
                <p>Apakah Anda yakin akan menghapus berkas ?</p>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="delete-berkas2" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya, hapus</button>
            </div>
        </div>
    </div>
</div>

<?php } ?>

<?php if($this->uri->segment(1) == 'dosen' && $this->uri->segment(2) == 'pkl' && $this->uri->segment(3) == 'approve') { ?>

<div class="modal fade" id="Approvalperbaiki" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Ajukan Perbaikan ?</p>
                <form id="perbaiki-pkl" method="post" action="<?php echo site_url("dosen/pkl/approve/perbaiki") ?>">
                    <input type="hidden" name="pkl_id" id="ID4" value="">
                    <input type="hidden" name="status" id="status2" value="">
                    <textarea name="keterangan" cols="70" rows="3" placeholder="Keterangan Tolak"></textarea>
                </form>
                
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="perbaiki-pkl" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>


<?php } ?>

<?php if($this->uri->segment(1) == 'tendik' && $this->uri->segment(2) == 'verifikasi-berkas' && ($this->uri->segment(3) == 'pkl' || $this->uri->segment(3) == 'seminar-pkl')) { ?>

<div class="modal fade" id="ApprovalperbaikiAdm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
                <form id="perbaiki-pkl" method="post" action="<?php echo site_url("tendik/verifikasi-berkas/pkl/perbaiki") ?>">
                    <input type="hidden" name="pkl_id" id="ID" value="">
                    <input type="hidden" name="status" id="status" value="">
                    <input type="hidden" name="periode" id="periode" value="">
                    <input type="hidden" name="id_alamat" id="id_alamat" value="">
                    <label>Ajukan Perbaikan ?</label>
                    <textarea class = "form-control" name="keterangan" cols="70" rows="3" placeholder="Keterangan Tolak"></textarea>
                </form>
                
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="perbaiki-pkl" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="PerbaikiSeminarAdmin" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
                <form id="perbaiki-pkl-smr" method="post" action="<?php echo site_url("tendik/verifikasi-berkas/seminar-pkl/perbaiki") ?>">
                    <input type="hidden" name="seminar_id" id="IDsmr" value="">
                    <input type="hidden" name="status" id="Statussmr" value="">
                    <label>Ajukan Perbaikan ?</label>
                    <textarea class = "form-control" name="keterangan" cols="70" rows="3" placeholder="Keterangan Tolak"></textarea>
                </form>
                
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="perbaiki-pkl-smr" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>


<?php } ?>

<?php if($this->uri->segment(1) == 'dosen' && $this->uri->segment(2) == 'pkl' && $this->uri->segment(4) == 'koordinator') { ?>
<script src="<?php echo site_url("assets/scripts/jquery_3.4.1_jquery.min.js") ?>"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js"></script>
<script src="<?php echo site_url("assets/scripts/dataTables.bootstrap4.min.js") ?>"></script>
<script src="<?php echo site_url("assets/scripts/DataTables-1.10.21/jquery.dataTables.min.js") ?>"></script>
<script type="text/javascript">
$(document).ready(function() {
    $("select").select2({
        theme: "bootstrap"
    });
} );
</script>

<div class="modal fade" id="PklKoorTolak" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">    
                <form id="tolak-pkl" method="post" action="<?php echo site_url("dosen/pkl/pengajuan/koordinator-tolak") ?>">
                    <input type="hidden" name="pkl_id" id="ID" value="">
                    <input type="hidden" name="status" id="status" value="">
                    <input type="hidden" name="periode" id="periode" value="">
                    <input type="hidden" name="id_al" id="id_al" value="">
                    <label>Tolak Pengajuan KP/PKL ?</label>
                    <textarea class = "form-control" name="keterangan" cols="70" rows="3" placeholder="Keterangan Tolak"></textarea>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="tolak-pkl" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="PklKoorSetuju" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
                <form id="approve-pkl" method="post" action="<?php echo site_url("dosen/pkl/pengajuan/koordinator-save") ?>">
                    <input type="hidden" name="pkl_id" id="ID_pkl" value="">
                    <input type="hidden" name="lokasi" id="Lokasi" value="">
                    <input type="hidden" name="id_alamat" id="id_al1" value="">
                    <input type="hidden" name="periode_alamat" id="periode1" value="">
                    <input type="hidden" name="surat" id="surat" value="">
                    <input type="hidden" name="approval_id" id="approval_id" value="">
                    <label>Pilih Pembimbing KP/PKL</label>
                    <select required name="pembimbing" class=" form-control">
                        <option value="">-- Pilih Dosen Pembimbing --</option>
                            <?php
                                $list = $this->user_model->select_list_dosen();
                                    foreach ($list as $row) {
                                        $nama_dosen = "";
                                        if($row->gelar_depan != "") $nama_dosen .= $row->gelar_depan." ";
                                        $nama_dosen .= $row->name;
                                        $select = "";
                                                    
                                        echo "<option ".$select." value='".$row->id_user."'>".$nama_dosen."</option>";
                                    }
                            ?>
                    </select>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="approve-pkl" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>

<!-- status > 3 -->
<div class="modal fade" id="ApprovalKoorPkl" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
                <form id="approve-pkl2" method="post" action="<?php echo site_url("dosen/pkl/pengajuan/koordinator-setuju") ?>">
                    <input type="hidden" name="approval_id" id="approval" value="">
                    <input type="hidden" name="periode_almt" id="periode_almt" value="">
                    <input type="hidden" name="id_almt" id="id_almt" value="">
                    <label>Setujui Pengajuan KP/PKL ?</label>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="approve-pkl2" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>

<!-- seminar pkl -->
<div class="modal fade" id="TolakSeminarPKL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">    
                <form id="tolak-pkl-seminar" method="post" action="<?php echo site_url("dosen/pkl/seminar/koordinator/tolak") ?>">
                    <input type="hidden" name="seminar_id" id="IDsmr" value="">
                    <input type="hidden" name="status" id="Statussmr" value="">
                    <label>Tolak Pengajuan Seminar KP/PKL ?</label>
                    <textarea class = "form-control" name="keterangan" cols="70" rows="3" placeholder="Keterangan Tolak"></textarea>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="tolak-pkl-seminar" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<?php if($this->uri->segment(1) == 'mahasiswa' && $this->uri->segment(2) == 'pkl' && $this->uri->segment(3) == 'seminar') { ?>

<!-- delete seminar pkl -->
<div class="modal fade" id="delSeminarPkl" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
                <form id="insert-seminar-pkl" method="post" action="<?php echo site_url("mahasiswa/pkl/seminar/delete") ?>">
                    <input type="hidden" name="seminar_id" id="SeminarID" value="">
                    <label>Hapus Pengajuan Seminar KP/PKL ?</label>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="insert-seminar-pkl" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="delBerkasSmrkp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Hapus Berkas Lampiran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="delete-berkas" method="post" action="<?php echo site_url("mahasiswa/pkl/seminar/lampiran/delete") ?>">
                    <input type="hidden" name="id_berkas" id="berkasID" value="">
                    <input type="hidden" name="seminar_id" id="smrID" value="">
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

<!-- ajukan seminar pkl -->
<div class="modal fade" id="AjukanSeminarpkl" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
                <form id="ajukan-seminar-pkl" method="post" action="<?php echo site_url("mahasiswa/pkl/seminar/ajukan") ?>">
                    <input type="hidden" name="seminar_id" id="SmrID" value="">
                    <label>Ajukan Seminar KP/PKL ?</label>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="ajukan-seminar-pkl" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>

<!-- ajukan perbaikan seminar kp / pkl mahasiswa-->
<div class="modal fade" id="AjukanSeminarPerbaikanPkl" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="ajukan-perbaikan-smr-kp" method="post" action="<?php echo site_url("mahasiswa/pkl/seminar/perbaikan") ?>">
                    <input type="hidden" name="seminar_id" id="SemID" value="">
                    <input type="hidden" name="status" id="Sts" value="">
                Ajukan Perbaikan KP/PKL ?
                </form>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="ajukan-perbaikan-smr-kp" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>

<?php } ?>

<?php if($this->uri->segment(1) == 'dosen' && $this->uri->segment(2) == 'pkl' && $this->uri->segment(3) == 'approve-seminar') { ?>

<div class="modal fade" id="PerbaikiSeminar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">    
                <form id="perbaiki-pkl" method="post" action="<?php echo site_url("dosen/pkl/approve-seminar/perbaiki") ?>">
                    <input type="hidden" name="seminar_id" id="ID" value="">
                    <input type="hidden" name="status" id="Status" value="">
                    <label>Ajukan Perbaikan KP/PKL ?</label>
                    <textarea class = "form-control" name="keterangan" cols="70" rows="3" placeholder="Keterangan Perbaikan"></textarea>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="perbaiki-pkl" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>


<?php } ?>

<?php if($this->uri->segment(1) == 'dosen' && $this->uri->segment(2) == 'struktural' && $this->uri->segment(3) == 'pkl' && $this->uri->segment(4) == 'komponen-nilai-pkl' ) { ?>

<div class="modal fade" id="komponensmr" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">    
                <form id="komponen-pkl" method="post" action="<?php echo site_url("dosen/struktural/pkl/komponen-nilai-pkl/add") ?>">
                    <input type="hidden" name="jurusan" id="IDJur" value="">
                    <input type="hidden" name="jenis" id="Jenis" value="">
                    <input type="hidden" name="aksi" id="Aksi3" value="">
                    <input type="hidden" name="ida" id="Ida3" value="">
                    <label class="col-form-label" >Nama Komponen</label>
                    <input type="text" name="komponen" id="" class="form-control" placeholder = "Komponen Penilaian" value="">
                    <label class="col-form-label" >Persentase</label>
                    <input type="number" min="0" max="100" name="persentase" id="" class="form-control" placeholder = "Persentase Komponen" value="">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="komponen-pkl" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="komponensmrhapus" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
                <form id="insert-seminar-pkl" method="post" action="<?php echo site_url("dosen/struktural/pkl/komponen-nilai-pkl/delete") ?>">
                    <input type="hidden" name="komponen_id" id="IDMeta" value="">
                    <input type="hidden" name="aksi" id="Aksi" value="">
                    <input type="hidden" name="ida" id="Ida" value="">
                    <label>Hapus Komponen Nilai Seminar KP/PKL ?</label>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="insert-seminar-pkl" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="komponensmrubah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Ubah</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">    
                <form id="komponen-pkl-edit" method="post" action="<?php echo site_url("dosen/struktural/pkl/komponen-nilai-pkl/edit") ?>">
                    <input type="hidden" name="id_meta" id="IDMeta2" value="">
                    <input type="hidden" name="aksi" id="Aksi2" value="">
                    <input type="hidden" name="ida" id="Ida2" value="">
                    <label class="col-form-label" >Nama Komponen</label>
                    <input type="text" name="komponen" id="Attr" class="form-control" placeholder = "Komponen Penilaian" value="">
                    <label class="col-form-label" >Persentase</label>
                    <input type="number" min="0" max="100" name="persentase" id="Persentase" class="form-control" placeholder = "Persentase Komponen" value="">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="komponen-pkl-edit" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="nonaktifkomponenPkl" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">    
                <form id="komponennon-pkl" method="post" action="<?php echo site_url("dosen/struktural/pkl/komponen-nilai-pkl/nonaktif") ?>">
                    <input type="hidden" name="id" id="IDk" value="">
                    <label class="col-form-label" >Nonaktifkan Komponen Penilaian</label>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="komponennon-pkl" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>

<?php } ?>

<?php if($this->uri->segment(2) == 'layanan-fakultas') { ?>

<div class="modal fade" id="delFormMhs" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">    
                <form id="del-form" method="post" action="<?php echo site_url("mahasiswa/layanan-fakultas/form/delete") ?>">
                    <input type="hidden" name="id_layanan" id="ID" value="">
                    <input type="hidden" name="jenis" id="Jns" value="">
                    <label class="col-form-label" >Hapus Form Layanan ?</label>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="del-form" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>

<!-- Del Lampiran bebas lab -->
<div class="modal fade" id="delBerkaslay" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Hapus Berkas Lampiran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <?php 
            $jenis = $this->uri->segment(3); 
            $id = $this->input->get('id');
            $aksi = $this->input->get('aksi');
            ?>
                <form id="deleteberkaslay" method="post" action="<?php echo site_url("mahasiswa/layanan-fakultas/$jenis/hapus-berkas?id=$id") ?>">
                    <input type="hidden" name="id_berkas" id="berkasID2" value="">
                    <input type="hidden" name="id_layanan" id="IDBebas2" value="">
                    <input type="hidden" name="file_berkas" id="berkasFile2" value="">
                    <input type="hidden" name="aksi" value="<?php echo $aksi ?>"> 
                </form>
                <p>Apakah Anda yakin akan menghapus <span id="berkasNama2" class="text-danger"> ?</p>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="deleteberkaslay" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya, hapus</button>
            </div>
        </div>
    </div>
</div>

<?php } ?>

<?php if($this->uri->segment(2) == 'layanan-fakultas') { ?>

<!-- Del Lampiran bebas lab -->
<div class="modal fade" id="delBerkaslab" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Hapus Berkas Lampiran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="deleteberkas" method="post" action="<?php echo site_url("mahasiswa/hapus-berkas-lab") ?>">
                    <input type="hidden" name="id_berkas" id="berkasID" value="">
                    <input type="hidden" name="id_bebas" id="IDBebas" value="">
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

<!-- Del bebas lab -->
<div class="modal fade" id="dellab" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="deletelab" method="post" action="<?php echo site_url("mahasiswa/hapus-lab") ?>">
                    <input type="hidden" name="IDBebasLab" id="IDBebasLab" value="">
                </form>
                <p>Hapus Pengajuan ini ?</p>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="deletelab" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya, hapus</button>
            </div>
        </div>
    </div>
</div>

<!-- Ajukan bebas lab -->
<div class="modal fade" id="Ajukanlab" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="ajukan-lab" method="post" action="<?php echo site_url("mahasiswa/ajukan-bebas-lab") ?>">
                    <input type="hidden" name="id_meta" id="IDMeta" value="">
                </form>
                <p>Ajukan Bebas <span id="Nama" class=""></span> ?</p>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="ajukan-lab" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<?php if($this->uri->segment(2) == 'bebas-lab') { ?>

<!-- Del bebas lab -->
<div class="modal fade" id="tolaklab" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="deletelab" method="post" action="<?php echo site_url("tendik/bebas-lab/pengajuan/aksi") ?>">
                    <input type="hidden" name="id_meta" id="IDtolak" value="">
                    <input type="hidden" name="aksi" id="" value="tolak">
                
                <p>Tolak Pengajuan ?</p>
                <textarea name="keterangan" cols="70" rows="3" placeholder="Keterangan Tolak"></textarea>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="deletelab" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>

<!-- verif pranata lab -->
<div class="modal fade" id="verifikasilab" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="veriflab" method="post" action="<?php echo site_url("tendik/bebas-lab/pengajuan/aksi") ?>">
                    <input type="hidden" name="id_meta" id="IDverif" value="">
                    <input type="hidden" name="aksi" id="" value="terima">
                
                <p>Verifikasi Pengajuan ?</p>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="veriflab" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>

<?php } ?>

<?php if($this->uri->segment(2) == 'verifikasi-berkas-fakultas') { ?>

<!-- verif surat -->
<div class="modal fade" id="verifikasisurat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <?php $jenis = $this->uri->segment(3); ?>
                <form id="verifsurat" method="post" action="<?php echo site_url("tendik/verifikasi-berkas-fakultas-simpan/$jenis") ?>">
                    <input type="hidden" name="id_layanan" id="IDSurat" value="">
                
                    <label >Nomor Surat</label>
                    <input type="text" name="no_surat" placeholder="Nomor Surat Bila ada. cth: XXXX/UN26.17/DT/2020" class="form-control" value="">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="verifsurat" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<?php if($this->uri->segment(2) == 'verifikasi-berkas-masuk-fakultas') { ?>
<!-- verif surat -->
<div class="modal fade" id="verifikasimasuk" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <?php $jenis = $this->uri->segment(3); ?>
                <form id="verifmasuk" method="post" action="<?php echo site_url("tendik/verifikasi-berkas-masuk-fakultas-simpan/$jenis") ?>">
                    <input type="hidden" name="id_pengajuan" id="IDMasuk" value="">                
                    <input type="hidden" name="aksi" id="aksi" value="setuju">                
                    <label >Verifikasi Berkas Masuk ?</label>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="verifmasuk" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>

<!-- tolak verif surat -->
<div class="modal fade" id="tolakmasuk" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <?php $jenis = $this->uri->segment(3); ?>
                <form id="tolak-verif-masuk" method="post" action="<?php echo site_url("tendik/verifikasi-berkas-masuk-fakultas-simpan/$jenis") ?>">
                    <input type="hidden" name="id_pengajuan" id="IDtolak" value="">         
                    <input type="hidden" name="aksi" id="aksi" value="tolak">                
                    <label>Tolak Berkas Masuk ?</label>
                    <textarea name="keterangan" cols="70" rows="3" placeholder="Keterangan Tolak"></textarea>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="tolak-verif-masuk" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<?php if($this->uri->segment(2) == 'prestasi') { ?>

<div class="modal fade" id="delFormMhs" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">    
                <form id="del-form" method="post" action="<?php echo site_url("mahasiswa/prestasi/surat-tugas-form/delete") ?>">
                    <input type="hidden" name="id_layanan" id="ID" value="">
                    <input type="hidden" name="jenis" id="Jns" value="">
                    <label class="col-form-label" >Hapus Form Layanan ?</label>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="del-form" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>

<!-- Del Lampiran bebas lab -->
<div class="modal fade" id="delBerkaslay" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Hapus Berkas Lampiran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <?php 
            $jenis = $this->uri->segment(3); 
            $id = $this->input->get('id');
            $aksi = $this->input->get('aksi');
            ?>
                <form id="deleteberkaslay" method="post" action="<?php echo site_url("mahasiswa/prestasi/surat-tugas-form/hapus-berkas?id=$id") ?>">
                    <input type="hidden" name="id_berkas" id="berkasID2" value="">
                    <input type="hidden" name="id_layanan" id="IDBebas2" value="">
                    <input type="hidden" name="file_berkas" id="berkasFile2" value="">
                    <input type="hidden" name="aksi" value="<?php echo $aksi ?>"> 
                </form>
                <p>Apakah Anda yakin akan menghapus <span id="berkasNama2" class="text-danger"> ?</p>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="deleteberkaslay" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya, hapus</button>
            </div>
        </div>
    </div>
</div>

<?php } ?>

<?php if($this->uri->segment(2) == 'beasiswa' || $this->uri->segment(2) == 'beasiswa-detail') { ?>

<div class="modal fade" id="HapusBeasiswa" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">    
                <form id="hapus_beasiswa" method="post" action="<?php echo site_url("dosen/hapus_beasiswa") ?>">
                    <input type="hidden" name="id_beasiswa" id="id_hapus_beasiswa" value="">
                    <label class="col-form-label" >Hapus Beasiswa ?</label>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="hapus_beasiswa" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="EditBeasiswa" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Data Beasiswa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">    
                <form id="edit_beasiswa" method="post" action="<?php echo site_url("dosen/edit_beasiswa") ?>">
                    <div class="position-relative form-group">
                        <label>Nama Beasiswa</label>
                            <input type="text" placeholder ='Nama Beasiswa' class='form-control' name="nama" id='nama_edit_beasiswa' value="">
                            <input type="hidden" name="id" id='id_edit_beasiswa' value="">
                    </div>
                    <div class="position-relative form-group">
                        <label>Tahun Akademik</label>
                            <input type="text" placeholder ='Tahun Akadmeik' class='form-control' name="ta" id='ta_edit_beasiswa' value="">
                    </div>
                    <div class="position-relative form-group">
                        <label>Tahun Beasiswa</label>
                            <input type="text" placeholder ='Tahun Beasiswa' class='form-control' name="tahun" id='tahun_edit_beasiswa' value="">
                    </div>
                    <div class="position-relative form-group">
                        <label>Semester</label>
                            <input type="text" placeholder ='Semester' class='form-control' name="semester" id='smr_edit_beasiswa' value="">
                    </div>
                    <div class="position-relative form-group">
                        <label>Penyelenggara</label>
                            <input type="text" placeholder ='Penyelenggara' class='form-control' name="penyelenggara" id='png_edit_beasiswa' value="">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="edit_beasiswa" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Simpan</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="AktifBeasiswa" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">    
                <form id="aktif_beasiswa" method="post" action="<?php echo site_url("dosen/aktif_beasiswa") ?>">
                    <input type="hidden" name="id_beasiswa" id="id_aktif_beasiswa" value="">
                    <input type="hidden" name="aksi" id="status" value="">
                    <label class="col-form-label" >Ubah Status Beasiswa ?</label>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="aktif_beasiswa" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="HapusBeasiswaMhs" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">    
                <form id="hapus_beasiswa_mhs" method="post" action="<?php echo site_url("mahasiswa/hapus_beasiswa") ?>">
                    <input type="hidden" name="id_beasiswa" id="id_hapus_beasiswa_mhs" value="">
                    <label class="col-form-label" >Hapus Pendaftaran Beasiswa ?</label>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="hapus_beasiswa_mhs" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="AjukanBeasiswaMhs" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">    
                <form id="ajukan_beasiswa_mhs" method="post" action="<?php echo site_url("mahasiswa/ajukan_beasiswa") ?>">
                    <input type="hidden" name="id_beasiswa" id="id_ajukan_beasiswa_mhs" value="">
                    <label class="col-form-label" >Ajukan Pendaftaran Beasiswa ?</label>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="ajukan_beasiswa_mhs" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>

<!-- Del Lampiran beasiswa -->
<div class="modal fade" id="delBerkasBeasiswa" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Hapus Berkas Lampiran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php $id =  $this->input->get('id'); ?>
                <form id="deleteberkasbea" method="post" action="<?php echo site_url("mahasiswa/hapus_berkas_beasiswa") ?>">
                    <input type="hidden" name="id_berkas" id="berkasID" value="">
                    <input type="hidden" name="id_beasiswa" id="IDBeasiswa" value="">
                    <input type="hidden" name="file_berkas" id="berkasFile" value="">
                    <input type="hidden" name="id" id="" value="<?php echo $id; ?>">
                </form>
                <p>Apakah Anda yakin akan menghapus <span id="berkasNama" class="text-danger"> ?</p>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="deleteberkasbea" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya, hapus</button>
            </div>
        </div>
    </div>
</div>

<!-- Del Lampiran beasiswa -->
<div class="modal fade" id="delBerkaslay" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Hapus Berkas Lampiran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <?php 
            $jenis = $this->uri->segment(3); 
            $id = $this->input->get('id');
            $aksi = $this->input->get('aksi');
            ?>
                <form id="deleteberkaslay" method="post" action="<?php echo site_url("mahasiswa/beasiswa_hapus_berkas?id=$id") ?>">
                    <input type="hidden" name="id_berkas" id="berkasID2" value="">
                    <input type="hidden" name="id_layanan" id="IDBebas2" value="">
                    <input type="hidden" name="file_berkas" id="berkasFile2" value="">
                    <input type="hidden" name="aksi" value="<?php echo $aksi ?>"> 
                </form>
                <p>Apakah Anda yakin akan menghapus <span id="berkasNama2" class="text-danger"> ?</p>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="deleteberkaslay" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya, hapus</button>
            </div>
        </div>
    </div>
</div>

<!-- tolak beasiswa -->
<div class="modal fade" id="TolakBeasiswa" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php  $seg = $this->uri->segment(4); ?>
                <form id="tolakbea" method="post" action="<?php echo site_url("dosen/aksi_beasiswa") ?>">
                    <input type="hidden" name="id_beasiswa" id="id_aksi_beasiswa2" value="">
                    <input type="hidden" name="aksi" id="aksi_ket2" value="">
                    <input type="hidden" name="seg" id="" value="<?php echo $seg ?>">
                </form>
                <p>Tolak Pengajuan Beasiswa Mahasiswa Ini ?</p>
                <p><b>Mahasiswa Dinyatakan <span style='color:red'>Belum Lulus</span> Seleksi Beasiswa</b></p>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="tolakbea" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>

<!-- tolak beasiswa -->
<div class="modal fade" id="LulusBeasiswa" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <?php  $seg = $this->uri->segment(4); ?>
                <form id="lulusbea" method="post" action="<?php echo site_url("dosen/aksi_beasiswa") ?>">
                    <input type="hidden" name="id_beasiswa" id="id_aksi_beasiswa" value="">
                    <input type="hidden" name="aksi" id="aksi_ket" value="">
                    <input type="hidden" name="seg" id="" value="<?php echo $seg ?>">
                </form>
                <p>Setujui Pengajuan Beasiswa Mahasiswa Ini ?</p>
                <p><b>Mahasiswa Dinyatakan <span style='color:blue'>Lulus</span> Seleksi Beasiswa</b></p>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="lulusbea" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Ya</button>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<?php if($this->uri->segment(2) == 'lk') { ?>

<div class="modal fade" id="EditLk" tabindex="" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Data LK</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">    
                <form id="edit_lk" method="post" action="<?php echo site_url("dosen/edit_lk") ?>">
                    <div class="position-relative form-group">
                        <label>Nama LK</label>
                            <input type="text" required placeholder ='Nama LK' class='form-control' name="nama" id='nama' value="">
                            <input type="hidden" name="id" id='ID' value="">
                    </div>
                    <div class="position-relative form-group">
                        <label>Tingkat</label>
                            <select required name="tingkat" id="" class='form-control'>
                                <option value="">-- Pilih Tingkat --</option>
                                <option value="0">Fakultas</option>
                                <option value="1">Jurusan Kimia</option>
                                <option value="2">Jurusan Biologi</option>
                                <option value="3">Jurusan Matematika</option>
                                <option value="4">Jurusan Fisika</option>
                                <option value="5">Jurusan Ilmu Komputer</option>
                            </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="edit_lk" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Simpan</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="HapusLk" tabindex="" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">    
                <form id="hapus_lk" method="post" action="<?php echo site_url("dosen/hapus_lk") ?>">
                    <div class="position-relative form-group">
                        <label>Hapus Lembaga Kemahasiswaan ?</label>
                            <input type="hidden" name="id_lk" id='ID2' value="">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="hapus_lk" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Hapus</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="VerifLk" tabindex="" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">    
                <form id="verif_lk" method="post" action="<?php echo site_url("dosen/verif_lk") ?>">
                    <div class="position-relative form-group">
                        <label>Verifikasi Struktur Organisasi LK ?</label>
                            <input type="hidden" name="id_lk" id='ID3' value="">
                            <input type="hidden" name="periode" id='periode' value="">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-times fa-w-20"></i>
                                            </span>Batal</button>
                <button type="submit" form="verif_lk" class="btn btn-primary">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-check fa-w-20"></i>
                                            </span>Simpan</button>
            </div>
        </div>
    </div>
</div>

<?php } ?>