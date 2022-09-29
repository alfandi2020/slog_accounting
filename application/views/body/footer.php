       <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light">
        <p class="clearfix blue-grey lighten-2 mb-0"><span class="float-md-left d-block d-md-inline-block mt-25">COPYRIGHT &copy; <?= date('Y') ?> All rights Reserved</span>
            <button class="btn btn-primary btn-icon scroll-top" type="button"><i class="feather icon-arrow-up"></i></button>
        </p>
    </footer>
    <!-- END: Footer-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" ></script>


    <!-- BEGIN: Vendor JS-->
    <script src="<?= base_url() ?>assets/vendors/js/vendors.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="<?= base_url() ?>assets/vendors/js/charts/apexcharts.min.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="<?= base_url() ?>assets/js/core/app-menu.js"></script>
    <script src="<?= base_url() ?>assets/js/core/app.js"></script>
    <script src="<?= base_url() ?>assets/js/scripts/components.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="<?= base_url() ?>assets/js/scripts/pages/dashboard-ecommerce.js"></script>
    <!-- END: Page JS-->
    <script src="<?= base_url() ?>assets/vendors/js/simple.money.format.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="<?= base_url() ?>assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url()?>assets/vendors/js/tables/datatable/pdfmake.min.js"></script>
    <script src="<?= base_url()?>assets/vendors/js/tables/datatable/vfs_fonts.js"></script>
    <script src="<?= base_url()?>assets/vendors/js/tables/datatable/datatables.min.js"></script>
    <script src="<?= base_url()?>assets/vendors/js/tables/datatable/datatables.buttons.min.js"></script>
    <script src="<?= base_url()?>assets/vendors/js/tables/datatable/buttons.html5.min.js"></script>
    <script src="<?= base_url()?>assets/vendors/js/tables/datatable/buttons.print.min.js"></script>
    <script src="<?= base_url()?>assets/vendors/js/tables/datatable/buttons.bootstrap.min.js"></script>
    <script src="<?= base_url()?>assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js"></script>
<script src="<?= base_url() ?>assets/js/scripts/datatables/datatable.js"></script>

</body>
<!-- END: Body-->

</html>


    <script>
      	$(function() {
			$('a').filter(function() {
				return this.href == location.href
			}).parent().addClass('active').siblings().removeClass('active')
		})

		// for sidebar menu entirely but not cover treeview
		$('ul.navigation .nav-item a').filter(function() {
			return this.href == location.href
		}).parent().addClass('active');

		// for treeview
		$('ul.nav-collapse li a').filter(function() {
			return this.href == location.href
		}).parentsUntil(".nav > .nav.nav-collapse li a").addClass('active');

		$('div.collapse li a').filter(function() {
			return this.href == location.href
		}).parentsUntil(".nav > collapse li a").addClass('show');

        //report masuk
        $(document).ready(function(){
            $('#table-report').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'getReportMasuk'
                },
                'columns': [
                    { data: 'network_name' },
                    { data: 'customer_name' },
                    { data: 'invoice_number' },
                    { data: 'payment' },
                    { data: 'status' },
                    { data: 'action' },
                ]
            });
        });

        $(document).ready(function(){
            if ( $('#type-masuk-detail').text() != '') {
                if ($('#type-masuk-detail').text() === 'Cicilan') {
                    $('#table-masuk-detail').show()
                }else if($('#type-masuk-detail').text() === 'Cash'){
                    $('#table-masuk-detail').hide()
                }
            }
        });
        //end

/*  Window Resize END */
		function formatRupiah(angka, prefix){
			var number_string = angka.replace(/[^,\d]/g, '').toString(),
			split   		= number_string.split(','),
			sisa     		= split[0].length % 3,
			rupiah     		= split[0].substr(0, sisa),
			ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
 
			if(ribuan){
				separator = sisa ? '.' : '';
				rupiah += separator + ribuan.join('.');
			}
 
			rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
			return prefix == undefined ? rupiah : (rupiah ? 'Rp.' + rupiah : '');
        }
    $(document).ready(function() {
        $('.select2').select2();
    });
      

  

    //kas masuk
    $(document).ready(function(){
        $('.calculate').prop('disabled',true);
        $('#submit_kas_masuk').prop('disabled',true);
        $('input[name="tanggal_masuk"]').prop('disabled',true);
        // $('#sub-kas-masuk').hide();
        // $('#sub-kas-potongan').hide();
        $('#cicilan_x').hide();
        $('.sisa_tagihan_sementara').html('Tagihan Customer');
        $('.total_sisa').html('Total');
        
        var rupiah = document.getElementById('rupiah');
		rupiah.addEventListener('keyup', function(e){
			
			rupiah.value = formatRupiah(this.value, 'Rp.');
		});
        var rupiah2 = document.getElementById('rupiah2');
		rupiah2.addEventListener('keyup', function(e){
			
			rupiah2.value = formatRupiah(this.value, 'Rp.');
		});
        var rupiah3 = document.getElementById('rupiah3');
		rupiah3.addEventListener('keyup', function(e){
			rupiah3.value = formatRupiah(this.value, 'Rp.');
		});
 
        $('select[name="customer"]').change(function() {
				var id = $(this).val();
				$.ajax({
					url: "<?php echo base_url(); ?>/kas/ListInvoiceCustomer",
					method: "POST",
					data: {
						id: id
					},
					async: true,
					dataType: 'json',
					success: function(data) {
                        console.log(data)
						var html,html2 = '';
						var i;
                        html += '<option disabled selected>Pilih Invoice</option>'
						for (i = 0; i < data.length; i++) {
							html +=  '<option value='+ data[i].number+'> '+ data[i].number+' </option>';
                            html2 += '<option>Invoice Kosong </option>';
						}
                        if(data.length == 0){
						    $('select[name="invoice_masuk"]').html(html2)
                            $('select[name="invoice_masuk"]').prop('disabled',true);
                            $('input[name="tanggal_masuk"]').prop('disabled',true);
                            $('.calculate').prop('disabled',true);
                        }else{
						    $('select[name="invoice_masuk"]').html(html)
                            $('select[name="invoice_masuk"]').prop('disabled',false);
                            $('.calculate').prop('disabled',false);
                            $('input[name="potongan_bank"]').val('');
                            $('input[name="potongan_klaim"]').val('');
                            $('input[name="pph23_rincian"]').val('');
                            $('input[name="tanggal_masuk"]').val('');
                            $('input[name="tanggal_masuk"]').val('');
                            $('input[name="total_sementara"]').val('');
                            $('input[name="total"]').val('');
                            $('input[name="cicilan"]').val('');



                        }
                       
                        Toastify({
                            text: "Data Invoice : " + data.length,
                            style: {
                                background: "linear-gradient(to right, #00b09b, #96c93d)",
                            },
                            gravity : "bottom",
                            position: "right",
                            duration : 2000
                            }).showToast();
					}
				});
		});
        $('select[name="invoice_masuk"]').change(function() {
				var invoice = $(this).val();
				$.ajax({
					url: "<?php echo base_url(); ?>/kas/RincianInvoice",
					method: "POST",
					data: {
						invoice: invoice
					},
					async: true,
					dataType: 'json',
					success: function(data) {
                       
						var invoice = '';
                        var periode = '';
                        var total_sementara = '';
                        var pph23 ='';
                        var cek_tax = '';
                        var payment ='';
                        var cek_total='';
                        var tagihan_customer= '';
						var i;
                        var x;
						for (i = 0; i < 1; i++) {
                                invoice +=  data[i].invoice;
                                periode += data[i].periode;
                                cek_tax += data[i].is_taxed;
                                payment += data[i].payment;
                                total_sementara += cek_tax == 1 ? parseInt(data[i].amount / 1.011).toLocaleString() : parseInt(data[i].amount).toLocaleString();
                                tagihan_customer += cek_tax == 1 ? parseInt(data[i].amount).toLocaleString() : parseInt(data[i].amount).toLocaleString();
                                cek_total += cek_tax == 1 ? parseInt(data[i].amount / 1.01) : parseInt(data[i].amount); 
                                pph23 += data[i].payment != "Cicilan" ? parseInt(cek_total * 2 / 100).toLocaleString() : 0;
						}
                        payment == "Cicilan" ? $('.sisa_tagihan_sementara').html('Sisa Cicilan') : $('.sisa_tagihan_sementara').html('Tagihan Customer');
                        payment == "Cicilan" ? $('.total_sisa').html('Sisa Tagihan') : $('.total_sisa').html('Total');

                        $('.periode_rincian').val(periode);
                        $('.invoice_rincian').val(invoice); 
                        $('.resi_count_rincian').val(data.length);
                        $('.total_sementara').html(total_sementara);
                        $('.tagihan_customer').html(tagihan_customer);
                        $('input[name="total_sementara"]').val(cek_total);
                        $('input[name="potongan_bank"]').val('');
                        $('input[name="potongan_klaim"]').val('');
                        $('input[name="pph23_rincian"]').val('');
                        $('input[name="cicilan"]').val('');
                        if (payment == "Cicilan") {
                            $('#sub-kas-potongan').hide();
                        }else{
                            $('#sub-kas-potongan').show();
                        }
                        if (payment == "Cash" || total_sementara == 0) {
                            Swal.fire({
                                type: 'warning',
                                title: "Opss",
                                text: "Tagihan invoice #"+invoice+" Sudah dibayar",
                            
                            })
                            $('.paid').hide();
                            // $('#sub-kas-potongan').hide();
                        }else{
                            $('.paid').show();

                        }
                        // console.log(cicilan);
                        // cek_tax == 1 ? $('.pph23_rincian').html(pph23)  : $('.pph23_rincian').html(cek_tax);
                        // cek_tax == 1 ? $('input[name="pph23_rincian"]').val(pph23.replace(/[^\w\s]/gi, '')) : $('input[name="pph23_rincian"]').val(cek_tax);
                        $('.pph23_rincian').html(pph23);
                        $('input[name="pph23_rincian"]').val(pph23.replace(/[^\w\s]/gi, ''));
                        
                        $('input[name="tanggal_masuk"]').prop('disabled',false);
                        calculate()

                        
                        Toastify({
                            text: "Total Resi : " + data.length,
                            style: {
                                background: "linear-gradient(to right, #00b09b, #96c93d)",
                            },
                            gravity : "bottom",
                            position: "right",
                            duration : 2000
                            }).showToast();
                            console.log(data)
					}
				});

		});
        $('input[name="potongan_bank"]').keyup(function(){
            calculate();
        });
        $('input[name="potongan_klaim"]').keyup(function(){
            calculate();
        });
        $('input[name="cicilan"]').keyup(function(){

        var sisa = $('input[name="total_sementara"]').val();
        var cicilan2 = $('input[name="cicilan"]').val().slice(2).replace(/[^\w\s]/gi, '');
        console.log(sisa)
        console.log(cicilan2)
         if (parseInt(cicilan2) > parseInt(sisa)) {
            $('#submit_kas_masuk').prop('disabled',true);
            Swal.fire({
                        // position: 'top-end',
                        type: 'warning',
                        title: "Opss",
                        text: "Jumlah Cicilan tidak boleh lebih dari sisa tagihan",
                        // showConfirmButton: false,
                        
                    })
         }else{
            $('#submit_kas_masuk').prop('disabled',false);
         }
        $('#cicilan_x').show();
            calculate();
        });

        $('input[name="tanggal_masuk"]').change(function() {
            $('#submit_kas_masuk').prop('disabled',false);
            $('#sub-kas-masuk').show();
            // $('#sub-kas-potongan').show();
        })

        function calculate(){
            var pph23 = parseInt( $('.pph23_rincian').text().replace(/[^\w\s]/gi, '') )
            var cicilan = $('input[name="cicilan"]').val().slice(2).replace(/[^\w\s]/gi, '') == "" ? 0 : parseInt( $('input[name="cicilan"]').val().slice(2).replace(/[^\w\s]/gi, '') )
            var bank = $('input[name="potongan_bank"]').val().slice(2).replace(/[^\w\s]/gi, '') == "" ? 0 : parseInt( $('input[name="potongan_bank"]').val().slice(2).replace(/[^\w\s]/gi, '') )
            var klaim = $('input[name="potongan_klaim"]').val().slice(2).replace(/[^\w\s]/gi, '') == "" ? 0 : parseInt( $('input[name="potongan_klaim"]').val().slice(2).replace(/[^\w\s]/gi, '') )
            var total_potongan = pph23 + bank + klaim;
            var total_rincian = parseInt( $('.total_sementara').text().replace(/[^\w\s]/gi, '') ) - cicilan - total_potongan;

            $('.bank_rincian').html(bank.toLocaleString())
            $('.klaim_rincian').html(klaim.toLocaleString())
            $('.total_potongan_rincian').html(total_potongan.toLocaleString())

            $('.potongan_cicilan').html(cicilan.toLocaleString())
            $('.total_rincian').html(total_rincian.toLocaleString())
            $('input[name="total"]').val(total_rincian)

        }
    })
        $(document).on("click", "#submit_kas_masuk", function(e){
            e.preventDefault();
            var id_customer = $('select[name="customer"]').val();
            var tgl = $('input[name="tanggal_masuk"]').val();
            var potongan_bank = $('input[name="potongan_bank"]').val().replace(/[^\w\s]/gi, '').slice(2);
            var potongan_klaim = $('input[name="potongan_klaim"]').val().replace(/[^\w\s]/gi, '').slice(2);
            var cicilan = $('input[name="cicilan"]').val().replace(/[^\w\s]/gi, '').slice(2);
            var invoice = $('select[name="invoice_masuk"]').val();
            var periode = $('.periode_rincian').val();
            var resi_total = $('.resi_count_rincian').val();
            var pph23 = $('input[name="pph23_rincian"]').val();
            var total_sementara = $('input[name="total_sementara"]').val();
            var total = $('input[name="total"]').val();

            $.ajax({
                url: "<?= base_url();?>kas/masuk_action",
                type: "post",
                dataType: 'json',
                data: {
                customer_id: id_customer,
                date_in: tgl,
                potongan_bank: potongan_bank,
                potongan_klaim: potongan_klaim,
                invoice : invoice,
                periode : periode,
                total_resi : resi_total,
                pph23 : pph23,
                total_sementara : total_sementara,
                total : total,
                cicilan: cicilan
                },
                success: function(data){
                if(data.responce == "success") {
                    // $('#barang_masuk_table').DataTable().destroy();
                    // fetch_aksesoris();  
                    // $('#add_modal_aksesoris').modal('hide')
                    // toastr["success"](data.message);
                    Swal.fire({
                        // position: 'top-end',
                        type: 'success',
                        title: data.message,
                        // text: data.message,
                        showConfirmButton: false,
                        timerProgressBar: true,
                        timer: 2000,
                        onBeforeOpen: function () {
                            Swal.showLoading()
                            timerInterval = setInterval(function () {
                            Swal.getContent().querySelector('strong')
                                .textContent = Swal.getTimerLeft()
                            }, 100)
                        },
                        onClose: function () {
                            clearInterval(timerInterval)
                        }
                    }).then((result) => {
                        if (result.dismiss === Swal.DismissReason.timer) {
                        window.location = "<?= base_url('kas/masuk');?>";
                        }
                    })
                }else if(data.responce == "double"){
                   Swal.fire({
                        // position: 'top-end',
                        type: 'warning',
                        title: data.message,
                        // text: data.message,
                        // showConfirmButton: false,

                        
                    })
                }else{
                    console.log(data.message)
                }
                }
            });

        });
    //end kas masuk
 
    //start kas keluar
    $(document).ready(function(){
        var rupiah4 = document.getElementById('rupiah4');
		rupiah4.addEventListener('keyup', function(e){
			
			rupiah4.value = formatRupiah(this.value, 'Rp.');
		});
        $(document).on("click", "#submit_kas_keluar", function(e){
            e.preventDefault();
            var data = {
                deskripsi:$('input[name="deskripsi"]').val(),
                biaya:$('input[name="biaya"]').val().replace(/[^\w\s]/gi, '').slice(2),
                date_out:$('input[name="date_out"]').val()
            }
            console.log(data)
            $.ajax({
                url: "<?= base_url();?>kas/keluar_action",
                type: "post",
                dataType: 'json',
                data: data,
                success: function(data){
                    if(data.responce == "success") {
                        Swal.fire({
                            type: 'success',
                            title: data.message,
                            showConfirmButton: false,
                            timerProgressBar: true,
                            timer: 2000,
                            onBeforeOpen: function () {
                                Swal.showLoading()
                                timerInterval = setInterval(function () {
                                Swal.getContent().querySelector('strong')
                                    .textContent = Swal.getTimerLeft()
                                }, 100)
                            },
                            onClose: function () {
                                clearInterval(timerInterval)
                            }
                        }).then((result) => {
                            if (result.dismiss === Swal.DismissReason.timer) {
                            window.location = "<?= base_url('kas/keluar');?>";
                            }
                        })
                    }else if(data.responce == "double"){
                    Swal.fire({
                            type: 'warning',
                            title: data.message,                        
                        })
                    }else{
                        console.log(data.message)
                    }
                }
            });

        });
        $('select[name="years_kas_keluar"]').change(function() {
            $('select[name="months_kas_keluar"]').val("");
            $('#table-list-kas-keluar').DataTable().clear().destroy();
        });
        $('select[name="months_kas_keluar"]').change(function() {
            $('#table-list-kas-keluar').DataTable().clear().destroy();
            $('#table-list-kas-keluar').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'getDataKasKeluar',
                    'data':{
                        'month':$('select[name="months_kas_keluar"]').val(),
                        'year':$('select[name="years_kas_keluar"]').val(),
                    }
                },
                'columns': [
                    { data: 'date_out' },
                    { data: 'deskripsi' },
                    { data: 'biaya' },
                ]
            });
        });
        
    });
    //end kas keluar
    $(document).ready(function(){
        $(document).on("click", "#submit_desc_kas_keluar", function(e){
            e.preventDefault();
            var data = {
                deskripsi:$('input[name="deskripsi2"]').val(),
            }
            console.log(data)
            $.ajax({
                url: "<?= base_url();?>master/list_desc_action",
                type: "post",
                dataType: 'json',
                data: data,
                success: function(data){
                    if(data.responce == "success") {
                        Swal.fire({
                            type: 'success',
                            title: data.message,
                            showConfirmButton: false,
                            timerProgressBar: true,
                            timer: 2000,
                            onBeforeOpen: function () {
                                Swal.showLoading()
                                timerInterval = setInterval(function () {
                                Swal.getContent().querySelector('strong')
                                    .textContent = Swal.getTimerLeft()
                                }, 100)
                            },
                            onClose: function () {
                                clearInterval(timerInterval)
                            }
                        }).then((result) => {
                            if (result.dismiss === Swal.DismissReason.timer) {
                            window.location = "<?= base_url('master/list_desc');?>";
                            }
                        })
                    }else{
                        console.log(data.message)
                    }
                }
            });

        });
    });
        function remove_special(str) {
            var lower = str.toLowerCase();
            var upper = str.toUpperCase();

            var res = "";
            for(var i=0; i<lower.length; ++i) {
                if(lower[i] != upper[i] || lower[i].trim() === '')
                    res += str[i];
            }
            return res;
        }

  
    </script>
  </body>
</html>