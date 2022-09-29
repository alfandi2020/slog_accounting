
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-body">
                <section class="invoice-preview-wrapper">
                    <!---->
                    <div class="row invoice-preview">
                        <div class="col-md-8 col-xl-9 col-12">
                            <div class="card">
                                <!---->
                                <!---->
                                <div class="card-body m-2">
                                    <!---->
                                    <!---->
                                    <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0">
                                        <div>
                                            <div class="logo-wrapper">
                                                <img src="<?= base_url() ?>assets/images/logo/logo_head_report.webp" >
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <span style="font-weight: bold; font-size:17px;"> Invoice #<span class="invoice-number"><?= $data_kas_masuk['invoice']?></span></span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-5">
                                        <div>
                                            <span style="font-weight: bold;">Office :</span> Jl. Swadaya Rw.Binong No.90, Bambu Apus, <br> Kec. Cipayung, Kota Jakarta Timur, Daerah Khusus <br> Ibukota Jakarta 13890
                                        </div>
                                        <div class="mt-0 text-right">
                                            <span style="font-weight: bold;">INVOICE DATE</span>
                                            <br>
                                            <span><?= date_format(date_create($data_kas_masuk['date_in']),"d-m-Y")?></span>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <span style="font-weight:bold;">Invoice To</span><br>
                                            <span style="font-weight:bold;"><?= $data_kas_masuk['customer_name']?></span><br>
                                            <span>
                                                <?php
                                                    $address = '';
                                                    foreach (json_decode($data_kas_masuk['customer_address']) as $key=>$value) {
                                                        $address .= $value.'<br>';
                                                    }
                                                    echo $address;
                                                ?>
                                            </span>
                                        </div>
                                        <div class="col-sm-4">
                                            <span style="font-weight:bold;">Payment Details :</span>
                                            <p id="type-masuk-detail"><?= $data_kas_masuk['payment']?></p>
                                            <span style="font-weight:bold;">Status : <?= $data_kas_masuk['status_payment'] ?></span>
                                        </div>
                                    </div>
                                    <div class="row mt-5" id="table-masuk-detail" style="display:none">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Invoice</th>
                                                        <th>Tanggal Pembayaran</th>
                                                        <th>Cicilan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $cicilan = '';
                                                        $index = 1;
                                                        foreach ($data_cicilan as $item) {
                                                            $cicilan .= '
                                                            <tr>
                                                                <th scope="row">'.$index++.'</th>
                                                                <td>'.$item->invoice.'</td>
                                                                <td>'.date_format(date_create($item->date_in),"d-m-Y").'</td>
                                                                <td>Rp. '.number_format($item->cicilan).'</td>
                                                            </tr>';
                                                        }
                                                        echo $cicilan;
                                                    ?>
                                                    <tr>
                                                        <th colspan="3" class="text-center">Total Cicilan</th>
                                                        <td>Rp. <?= number_format($data_kas_masuk['total_cicilan'])?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-3">
                                        <div class="col-sm-6">&nbsp;</div>
                                        <div class="col-sm-6">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <th>Total Sementara</th>
                                                        <td>Rp. <?= number_format($data_kas_masuk['total_sementara'])?></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Potongan Bank</th>
                                                        <td>Rp. <?= number_format($data_kas_masuk['potongan_bank'])?></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Potongan Klaim</th>
                                                        <td>Rp. <?= number_format($data_kas_masuk['potongan_klaim'])?></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">PPH 23</th>
                                                        <td>Rp. <?= number_format($data_kas_masuk['pph23'])?></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">Total Kas Masuk</th>
                                                        <td>Rp. <?= number_format($data_kas_masuk['total_cicilan'])?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="text-right mt-3">
                                        Terimakasih
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="invoice-actions col-md-4 col-xl-3 col-12">
                            <div class="card">
                                <!---->
                                <!---->
                                <div class="card-body">
                                    <!---->
                                    <!---->
                                    <a href="<?= base_url() ?>report/masuk"
                                        class="btn mb-75 btn-outline-secondary btn-block" target="_self"> Kembali </a>
                                </div>
                                <!---->
                                <!---->
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <!-- END: Content-->
