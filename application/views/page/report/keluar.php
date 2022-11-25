
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <!-- <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">DataTables</h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active">Datatable
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
            <div class="content-body">
                <!-- Zero configuration table -->
                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Laporan Keluar</h4>
                                </div>
                                
                                <form method="GET" action="<?php echo base_url('report/excel_keluar')?>">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <div class="row mb-4">
                                                <div class="col-md-1">Tahun</div>
                                                <div class="col-md-2">
                                                    <select class="form-control" name="years_saldo" id="years_saldo">
                                                        <!-- <option value="" disabled>-- select --</option> -->
                                                        <?php 
                                                        $years = date('Y'); 
                                                        $tahun_pilih = $_GET['years_saldo'];
                                                        if (!$tahun_pilih) {
                                                        $tahun_pilih = "";
                                                        $tahun = "-- select --";
                                                        } else {
                                                        $tahun_pilih = $tahun_pilih;
                                                        $tahun = $tahun_pilih;
                                                        }
                                                        ?>
                                                        <option <?= isset($tahun_pilih)  ? 'selected' : '' ?> value="<?= $tahun_pilih ?>"><?= $tahun ?></option>
                                                        <option value="" disabled>---</option>
                                                        <option value="<?= $years ?>"><?= $years ?></option>
                                                        <option value="<?= $years-1 ?>"><?= $years-1 ?></option>
                                                        <option value="<?= $years-2 ?>"><?= $years-2 ?></option>
                                                    </select>
                                                </div>
                                                <div class="col-md-1">Bulan</div>
                                                <div class="col-md-2">
                                                    <select class="form-control" name="months_saldo" id="months_saldo" onchange="this.form.submit()">
                                                        <!-- <option value="" disabled>-- select --</option> -->
                                                        <?php 
                                                        $bulan_pilih = $_GET['months_saldo'];
                                                        if($bulan_pilih == "1") {
                                                        $bulan = "Jan";
                                                        } else if($bulan_pilih == "2") {
                                                        $bulan = "Feb";
                                                        } else if($bulan_pilih == "3") {
                                                        $bulan = "Mar";
                                                        } else if($bulan_pilih == "4") {
                                                        $bulan = "Apr";
                                                        } else if($bulan_pilih == "5") {
                                                        $bulan = "Mei";
                                                        } else if($bulan_pilih == "6") {
                                                        $bulan = "Jun";
                                                        } else if($bulan_pilih == "7") {
                                                        $bulan = "Jul";
                                                        } else if($bulan_pilih == "8") {
                                                        $bulan = "Aug";
                                                        } else if($bulan_pilih == "9") {
                                                        $bulan = "Sep";
                                                        } else if($bulan_pilih == "10") {
                                                        $bulan = "Okt";
                                                        } else if($bulan_pilih == "11") {
                                                        $bulan = "Nov";
                                                        } else if($bulan_pilih == "12") {
                                                        $bulan = "Des";
                                                        } else {
                                                        $bulan = "--select--";
                                                        }
                                                        ?>
                                                        <option <?= isset($bulan_pilih)  ? 'selected' : '' ?> value="<?= isset($bulan_pilih) ? $bulan : '' ?>"><?= isset($bulan_pilih) ? $bulan : '---' ?></option>
                                                        <!-- <option value="" disabled>---</option> -->
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
                                                <!-- <div class="col-md-3">
                                                    <a href="<?php echo base_url('report/excel_masuk')?>" class="btn btn-primary btn-xs">
                                                    <i class="fa fa-download"></i>Export Excel</a>
                                                </div> -->
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="card-content">
                                    <div class="card-body card-dashboard">
                                        <!-- <p class="card-text">adwa</p> -->
                                        <div class="table-responsive">
                                            <table id="table-report" class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Cabang</th>
                                                        <th>Nama Customer</th>
                                                        <th>Invoice</th>
                                                        <th>Payment</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                               
                                                <!-- <tfoot>
                                                    <tr>
                                                        <th>Cabang</th>
                                                        <th>Nama Customer</th>
                                                        <th>Invoice</th>
                                                        <th>Payment</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </tfoot> -->
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <!-- END: Content-->
