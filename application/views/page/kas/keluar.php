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
                                <h4 class="card-title">Kas Keluar</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-xl-4 col-md-6 col-12 mb-1">
                                            <fieldset class="form-group">
                                            <label class="form-label" for="validationCustom01">Deskripsi</label>
                                                <select class="form-control select2" name="deskripsi" id="deskripsi">
                                                    <?php $db = $this->db->get('mt_kas_keluar_desc')->result();
                                                        foreach ($db as $x) {?>
                                                        <option value="<?= $x->id?>"><?= $x->deskripsi ?></option>
                                                        <?php } ?>
                                                </select>
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
                                            <input require type="date" class="form-control" name="date_out" >
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
            <!-- List kas keluar start -->
            <section id="list-kas-keluar">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">List Kas Keluar</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="row mb-4">
                                        <div class="col-md-1">Tahun</div>
                                        <div class="col-md-2">
                                            <?php
                                                $years = '';
                                                $year = date('Y');
                                                for ($i=0; $i < 10; $i++) { 
                                                    $years .= '<option value="'.$year.'">'.$year.'</option>';
                                                    $year--;
                                                }
                                            ?>
                                            <select class="form-control" name="years_kas_keluar" id="years_kas_keluar">
                                                <option value="" selected disabled>-- select --</option>
                                                <?=$years?>
                                            </select>
                                        </div>
                                        <div class="col-md-1">Bulan</div>
                                        <div class="col-md-2">
                                            <select class="form-control" name="months_kas_keluar" id="months_kas_keluar">
                                                <option value="" selected disabled>-- select --</option>
                                                <option value="1">Jan</option>
                                                <option value="2">Feb</option>
                                                <option value="3">Mar</option>
                                                <option value="4">Apr</option>
                                                <option value="5">Mei</option>
                                                <option value="6">Jun</option>
                                                <option value="7">Jul</option>
                                                <option value="8">Aug</option>
                                                <option value="9">Sep</option>
                                                <option value="10">Okt</option>
                                                <option value="11">Nov</option>
                                                <option value="12">Dec</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table id="table-list-kas-keluar" class="table">
                                            <thead>
                                                <tr>
                                                    <th>Date Out</th>
                                                    <th>Deskripsi</th>
                                                    <th>Biaya</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: Content-->