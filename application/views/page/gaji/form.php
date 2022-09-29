<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-body">
            <!-- Basic Inputs start -->
            <section id="basic-input">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Form Gaji</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-xl-4 col-md-6 col-12 mb-1">
                                            <fieldset class="form-group">
                                            <label class="form-label" for="validationCustom01">Deskripsi</label>
                                                <input type="text" class="form-control" name="deskripsi" placeholder="Deskripsi">
                                            </fieldset>
                                        </div>
                                        <div class="col-xl-4 col-md-6 col-12 mb-1">
                                            <fieldset class="form-group">
                                            <label class="form-label" for="validationCustom01">Biaya</label>
                                                <input type="text" class="form-control" name="biaya" id="rupiah4" placeholder="Rp.0">
                                            </fieldset>
                                        </div>
                                        <div class="col-xl-4 col-md-6 col-12 mb-1">
                                            <fieldset class="form-group">
                                            <label class="form-label" for="validationCustom01">Date out</label>
                                            <input require type="date" class="form-control" name="date_created" >
                                            </fieldset>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-xl-4">
                                                <button type="button" id="submit_kas_keluar" class="btn btn-primary">Submit</button>
                                                <a href="<?= base_url('kas/keluar') ?>" class="btn btn-warning">Reset</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Basic Inputs end -->


        </div>
    </div>
</div>
<!-- END: Content-->