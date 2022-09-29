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
                                <h4 class="card-title">Kas Masuk</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-xl-4 col-md-6 col-12 mb-1">
                                            <fieldset class="form-group">
                                            <label class="form-label" for="validationCustom01">Nama Customer</label>
                                            <select require name="customer" id="" class="select2 form-control">
                                                <option>Pilih Customer</option>
                                                <?php foreach ($customer as $x) {?>
                                                    <option value="<?= $x->id ?>"><?= $x->name ?></option>
                                                <?php } ?>
                                            </select> 
                                            </fieldset>
                                        </div>
                                        <div class="col-xl-4 col-md-6 col-12 mb-1">
                                            <fieldset class="form-group">
                                            <label class="form-label" for="validationCustom01">Invoice</label>
                                            <select require disabled name="invoice_masuk" id="xx" class="select2 form-control">
                                            <option value="">Invoice</option>
                                            </select> 
                                            </fieldset>
                                        </div>
                                        <div class="col-xl-4 col-md-6 col-12 mb-1">
                                            <fieldset class="form-group">
                                            <label class="form-label" for="validationCustom01">Date In</label>
                                            <input require disabled type="date" required name="tanggal_masuk" class="form-control">
                                            </fieldset>
                                        </div>
                                    </div>
                                    <!-- <div class="row">
                                        <div class="col-xl-4">
                                        <fieldset>
                                            <div class="vs-checkbox-con vs-checkbox-primary">
                                                <input type="checkbox" value="false">
                                                <span class="vs-checkbox">
                                                       <span class="vs-checkbox--check">
                                                           <i class="vs-icon feather icon-check"></i>
                                                            </span>
                                                </span>
                                                <span class="">Bayar Cicilan</span>
                                            </div>
                                        </fieldset>
                                        </div>
                                    </div> -->
                                  <div class="paid">  
                                    <div class="accordion" id="sub-kas-masuk">
                                            <div class="collapse-margin">
                                                <div class="card-header" id="headingOne" data-toggle="collapse" role="button" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                                    <span class="lead collapse-title">
                                                        Bayar Cicilan
                                                    </span>
                                                </div>

                                                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#sub-kas-masuk">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-xl-6">
                                                                <label for="">Jumlah Cicilan</label>
                                                                <input type="text" id="rupiah" placeholder="Rp.0" name="cicilan" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- <div class="collapse-margin">
                                                <div class="card-header" id="headingTwo" data-toggle="collapse" role="button" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseOne">
                                                    <span class="lead collapse-title">
                                                        Potongan
                                                    </span>
                                                </div>

                                                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#sub-kas-masuk">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-xl-6">
                                                                <label for="">Potongan Bank</label>
                                                                <input type="text" id="rupiah2" placeholder="Rp.0" name="potongan_bank" class="form-control">
                                                            </div>
                                                            <div class="col-xl-6">
                                                                <label for="">Potongan Klaim</label>
                                                                <input type="text" id="rupiah3" placeholder="Rp.0" name="potongan_klaim" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> -->
                                            <!-- <div class="row mt-2">
                                                    <div class="col-xl-4">
                                                        <button onclick="calculate()" class="btn btn-primary calculate">Calculate</button>
                                                    </div>
                                            </div> -->
                                    </div>
                                    <div id="sub-kas-potongan">
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <label for="">Potongan Bank</label>
                                                <input type="text" id="rupiah2" placeholder="Rp.0" name="potongan_bank" class="form-control">
                                            </div>
                                            <div class="col-xl-6">
                                                <label for="">Potongan Klaim</label>
                                                <input type="text" id="rupiah3" placeholder="Rp.0" name="potongan_klaim" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-xl-3">
                                            <label>Invoice</label>
                                            <input type="text" class="form-control invoice_rincian"  disabled>
                                        </div>
                                        <div class="col-xl-3">
                                            <label>Periode</label>
                                            <input type="text" class="form-control periode_rincian" disabled>
                                        </div>
                                        <div class="col-xl-2">
                                            <label>Jumlah Resi</label>
                                            <input type="text" class="form-control resi_count_rincian" disabled>
                                        </div>
                                        <div class="col-xl-4">
                                            <input type="hidden" name="pph23_rincian">
                                            <input type="hidden" name="total_sementara">
                                            <input type="hidden" name="total">
                                            <span class="sisa_tagihan_sementara"> </span>
                                            <span style="display: none;"> : Rp.<span class="total_sementara"></span></span>
                                            <span> : Rp.<span class="tagihan_customer"></span></span>
                                            <hr>
                                            <p>POTONGAN</p>
                                            <div class="row" id="">
                                                    <div class="col-xl-5">
                                                        <span>PPh23 (2%)</span>
                                                    </div>
                                                    <div class="col-xl-7">
                                                        <span>: Rp.<span class="pph23_rincian"></span></span>
                                                    </div>
                                            </div>
                                            <div class="row">
                                                    <div class="col-xl-5">
                                                        <span>Bank</span>
                                                    </div>
                                                    <div class="col-xl-5">
                                                    <span>: Rp.<span class="bank_rincian"></span></span>
                                                    </div>
                                            </div>
                                            <div class="row">
                                                    <div class="col-xl-5">
                                                        <span>Klaim</span>
                                                    </div>
                                                    <div class="col-xl-5">
                                                    <span>: Rp.<span class="klaim_rincian"></span></span>
                                                    </div>
                                            </div>
                                            <div class="row">
                                                    <div class="col-xl-5">
                                                        <span>Total Potongan</span>
                                                    </div>
                                                    <div class="col-xl-5">
                                                        <span>: Rp.<span class="total_potongan_rincian"></span></span>
                                                    </div>
                                            </div>
                                            <hr>
                                            <div class="row" id="cicilan_x">
                                                <div class="col-xl-5">
                                                    Cicilan
                                                </div>
                                                <div class="col-xl-5">
                                                    <span>: Rp.<span class="potongan_cicilan"></span></span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-5">
                                                    <span class="total_sisa"></span>
                                                </div>
                                                <div class="col-xl-5">
                                                    <span>: Rp.<span class="total_rincian"></span></span>
                                                </div>
                                            </div>
                                            <hr>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-2">
                                        <div class="col-xl-4">
                                                <button type="button" id="submit_kas_masuk" class="btn btn-primary">Submit</button>
                                                <a href="<?= base_url('kas/masuk') ?>" class="btn btn-warning">Reset</a>
                                        </div>
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