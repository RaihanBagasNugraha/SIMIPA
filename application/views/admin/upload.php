                        <div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-upload icon-gradient bg-sunny-morning">
                                        </i>
                                    </div>
                                    <div>Unggah Nilai
                                        <div class="page-title-subheading">Sebelum mengunggah, pastikan FORMAT file EXCEL sesuai template.
                                        </div>
                                    </div>
                                </div>
                                <div class="page-title-actions">
                                    <a href="<?php echo base_url("assets/TemplateNilai.xlsx") ?>" class="btn-shadow btn btn-success">
                                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-file-excel fa-w-20"></i>
                                            </span>
                                            FORMAT EXCEL
                                    </a>
                                </div>
                            </div>
                        </div>
                        

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="main-card mb-3 card">
                                    <div class="card-body">
                                        <form class="" method="post" action="<?php echo site_url('excel_import/import') ?>" enctype="multipart/form-data">
                                            <input type="hidden" name="agent" value="<?php echo $_SERVER['HTTP_USER_AGENT'] ?>"/>
                                            <div class="position-relative row form-group"><label for="exampleFile" class="col-sm-2 col-form-label">Tanggal Tes</label>
                                                <div class="col-sm-3">
                                                    <input type="text" name="tes_tgl" required class="form-control" data-toggle="datepicker" data-format="yyyy-mm-dd"/>
                                                </div>
                                            </div>
                                            <div class="position-relative row form-group"><label for="exampleFile" class="col-sm-2 col-form-label">Kategori</label>
                                                <div class="col-sm-10">
                                                    <div class="custom-radio custom-control">
                                                        <input required type="radio" id="exampleCustomRadio" name="kategori" class="custom-control-input" value="regular">
                                                        <label class="custom-control-label" for="exampleCustomRadio">Regular</label>
                                                    </div>
                                                    <div class="custom-radio custom-control">
                                                        <input required type="radio" id="exampleCustomRadio2" name="kategori" class="custom-control-input" value="kursus">
                                                        <label class="custom-control-label" for="exampleCustomRadio2">Kursus</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="position-relative row form-group"><label for="exampleFile" class="col-sm-2 col-form-label">Jenjang</label>
                                                <div class="col-sm-10">
                                                    <div class="custom-radio custom-control">
                                                        <input required type="radio" id="exampleCustomRadio3" name="jenjang" value="d3/s1" class="custom-control-input">
                                                        <label class="custom-control-label" for="exampleCustomRadio3">D3/S1</label>
                                                    </div>
                                                    <div class="custom-radio custom-control">
                                                        <input required type="radio" id="exampleCustomRadio4" name="jenjang" value="pasca/umum" class="custom-control-input">
                                                        <label class="custom-control-label" for="exampleCustomRadio4">Pascasarjana/Umum</label>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="position-relative row form-group"><label for="exampleFile" class="col-sm-2 col-form-label">Pilih Berkas</label>
                                                <div class="col-sm-8">
                                                    <input oninvalid="this.setCustomValidity('Anda belum memilih berkas!')" oninput="this.setCustomValidity('')" required accept=".xls,.xlsx, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel" name="file" id="exampleFile" type="file" class="form-control-file">
                                                    <small class="form-text text-muted">Pastikan, data yang diisikan telah benar (EXCEL) dan file EXCEL yang diunggah telah sesuai format.</small>
                                                </div>
                                            </div>
                                            
                                            <div class="position-relative row form-group">
                                                <div class="col-sm-10 offset-sm-2">
                                                    <button class="btn btn-primary">
                                                        <span class="btn-icon-wrapper pr-2 opacity-7">
                                                <i class="fas fa-paper-plane fa-w-20"></i>
                                            </span>UNGGAH KE SERVER</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        