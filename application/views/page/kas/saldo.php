<div class="app-content content">
   <div class="content-overlay"></div>
   <div class="header-navbar-shadow"></div>
   <div class="content-wrapper">
      <div class="content-body">
         <section id="cek-saldo">
            <div class="row">
               <div class="col-md-12">
                  <div class="card">
                     <div class="card-header">
                        <h4 class="card-title">Saldo</h4>
                     </div>
                     
                     <form method="GET">
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
                                       $tahun = "---";
                                    } else {
                                       $tahun_pilih = $tahun_pilih;
                                       $tahun = $tahun_pilih;
                                    }
                                    ?>
                                    <option <?= isset($tahun_pilih)  ? 'selected' : '' ?> value="<?= isset($tahun_pilih) ? $tahun : '' ?>"><?= isset($tahun_pilih) ? $tahun : '---' ?></option>
                                    <!-- <option value="" disabled>---</option> -->
                                    <option value="<?= $years ?>"><?= $years ?></option>
                                    <option value="<?= $years+1 ?>"><?= $years+1 ?></option>
                                    <option value="<?= $years+2 ?>"><?= $years+2 ?></option>

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
                              <div class="col-md-2">
                                 <a href="<?= base_url('kas/saldo')?>" class="btn btn-primary">Reset</a>
                              </div>

                           </div>
                           <?php

                           if(isset($_GET["months_saldo"])) {
                              $tampil_bulan = "sekarang";
                           } else {
                              $tampil_bulan = $bulan;
                           }
                           if ($saldo_keluar["biaya"] == "") {
                              $saldo_keluar = 0;
                           } else {
                              $saldo_keluar = $saldo_keluar["biaya"];
                           }
                           if ($saldo_masuk["total"] == "") {
                              $saldo_masuk = 0;
                           } else {
                              $saldo_masuk = $saldo_masuk["total"];
                           }

                           $saldo_sekarang = (int) $saldo_masuk - (int) $saldo_keluar;                         
                           ?>
                           <div class="col-md-6">
                              <div class="row">
                                 Saldo:
                              </div>
                              <div class="row">
                                 Total Kas Masuk : Rp <?= number_format($saldo_masuk)?>
                              </div>
                              <div class="row">
                                 Total Kas Keluar : Rp <?= number_format($saldo_keluar)?>
                              </div>
                              <div class="row">
                                 Saldo <?=$bulan?> : Rp <?=number_format($saldo_sekarang)?>
                              </div>
                           </div>
                           <br>
                           <!-- <div class="table-responsive">
                              <table class="table" id="table-list-cek_saldo">
                                 <thead>
                                    <tr>
                                       <th>Kas Masuk</th>
                                       <th>Kas Keluar</th>
                                       <th>Saldo</th>
                                    </tr>
                                 </thead>
                              </table>
                           </div> -->
                        </div>
                     </div>
                     </form>

                  </div>
               </div>
            </div>
         </section>
      </div>
   </div>
</div>