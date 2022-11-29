<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kas extends CI_Controller {

	public function __construct(){
        parent::__construct();
		$this->load->helper(array('form', 'url'));
        $this->load->model(array('M_KasMasuk','M_KasKeluar','M_Saldo'));
		$this->load->library('form_validation');
		if ($this->session->userdata('username') == false) {
			$this->session->set_flashdata("msg", "<div class='alert alert-danger'>Opss anda blm login</div>");
            redirect('auth');
		}
    }

	public function saldo() {

		$tahun = $this->input->get('years_saldo');
		$bulan = $this->input->get('months_saldo');

		$bulan_sekarang = date('m');
		$tahun_sekarang = date('Y');
		
		if ($bulan) {
			if ($tahun) {
				$sql_keluar = "SELECT SUM(biaya) as biaya FROM kas_keluar WHERE MONTH(date_out) = '$bulan' AND YEAR(date_out) = '$tahun'";
				$sql_masuk = "SELECT SUM(total) as total FROM acc_kas_masuks WHERE MONTH(date_in) = '$bulan' AND YEAR(date_in) = '$tahun'";
				// $this->db->where('MONTH(date_out)', $bulan);
				// $this->db->where('YEAR(date_out)', $tahun);
			} else {
				$sql_keluar = "SELECT SUM(biaya) as biaya FROM kas_keluar WHERE MONTH(date_out) = '$bulan' AND YEAR(date_out) = '$tahun_sekarang'";
				$sql_masuk = "SELECT SUM(total) as total FROM acc_kas_masuks WHERE MONTH(date_in) = '$bulan' AND YEAR(date_in) = '$tahun_sekarang'";
				// $this->db->where('MONTH(date_out)', $bulan);
			}
		} else {
			$sql_keluar = "SELECT SUM(biaya) as biaya FROM kas_keluar WHERE MONTH(date_out) = '$bulan_sekarang' AND YEAR(date_out) = '$tahun_sekarang'";
			$sql_masuk = "SELECT SUM(total) as total FROM acc_kas_masuks WHERE MONTH(date_in) = '$bulan_sekarang' AND YEAR(date_in) = '$tahun_sekarang'";
			// $this->db->where('MONTH(date_out)', $bulan_sekarang);
			// $this->db->where('YEAR(date_out)', $tahun_sekarang);
		}

		$q = $this->db->query($sql_keluar);
		$row = $q->row_array();

		$q_masuk = $this->db->query($sql_masuk);
		$row_masuk = $q_masuk->row_array();

		$saldo_sekarang = (int) $row - (int) $row_masuk;

		$data =[
			"saldo_keluar" => $row,
			"saldo_masuk" => $row_masuk,
			"saldo_sekarang" => $saldo_sekarang
		];

		$this->load->view('body/header');
		$this->load->view('page/kas/saldo', $data);
		$this->load->view('body/footer');
	}

	public function hutang() {
		$this->load->view('body/header');
		$this->load->view('page/kas/hutang');
		$this->load->view('body/footer');
	}

	public function piutang() {
		$this->load->view('body/header');
		$this->load->view('page/kas/piutang');
		$this->load->view('body/footer');
	}

	public function masuk()
	{
		$data = [
			"customer" => $this->db->get_where('customers')->result(),
			
		];
		$this->load->view('body/header');
		$this->load->view('page/kas/masuk',$data);
		$this->load->view('body/footer');
	}
	function keluar(){
		$data = [
			"customer" => $this->db->get_where('customers')->result(),
			
		];
		$this->load->view('body/header');
		$this->load->view('page/kas/keluar',$data);
		$this->load->view('body/footer');
	}
	function masuk_action(){
		if($this->input->is_ajax_request()) {
			$this->form_validation->set_rules('invoice', 'Invoice', 'required');
			
			if($this->form_validation->run() == FALSE) 
			{
				$data = [
					'response' => 'error', 
					'message' => validation_errors()
				];
			}else{
				$ajax_data = $this->input->post();
				unset($ajax_data['cicilan']);
				$invoice = $this->input->post('invoice');
				$cicilan = $this->input->post('cicilan');
				$customer_id = $this->input->post('customer_id');
				$date_in = date('Y-m-d',strtotime($this->input->post('date_in')));
				$cek = $this->db->query("SELECT * from acc_kas_masuks where invoice='$invoice'")->num_rows();
				if ($cek == 0) {
					if($this->M_KasMasuk->insert($ajax_data))
					{
						if ($cicilan == true) {
							$data_cicilan = [
								"cicilan" => $cicilan,
								"invoice" => $invoice,
								"customer_id" => $customer_id,
								"date_in" => $date_in
							];
							$this->M_KasMasuk->insert_cicilan($data_cicilan);
						}
						$data = [
							'responce' => 'success',
							'message' => 'Data Berhasil di input'
						];
					
					}else{
						$data = [
							'responce' => 'error',
							'message' => 'failed'
						];
					}
				}else {
					$data_cicilan = [
						"cicilan" => $cicilan,
						"invoice" => $invoice,
						"customer_id" => $customer_id,
						"date_in" => $date_in
					];
					if($this->M_KasMasuk->insert_cicilan($data_cicilan))
					{
						$data = [
							'responce' => 'success',
							'message' => 'Data cicilan berhasil di input'
						];
					
					}else{
						$data = [
							'responce' => 'error',
							'message' => 'failed'
						];
					}
					// $data = [
					// 		'responce' => 'double',
					// 		'message' => 'Invoice Sudah di input !'
					// 	];

				}
			}
			echo json_encode($data);
		}else{
			echo "no";		
		}
	}
	function keluar_action(){
		if($this->input->is_ajax_request()) {
			$deskripsi = $this->input->post('deskripsi');
			$biaya = $this->input->post('biaya');
			$date_out = date('Y-m-d',strtotime($this->input->post('date_out')));
			$data = [
				"deskripsi" => $deskripsi,
				"biaya" => $biaya,
				"date_out" => $date_out,
				"date_created" => date("Y-m-d H:i:s")
			];
			if($this->M_KasKeluar->insert($data))
			{
				$data = [
					'responce' => 'success',
					'message' => 'Data Kas Keluar berhasil di input'
				];
			
			}else{
				$data = [
					'responce' => 'error',
					'message' => 'failed'
				];
			}
			echo json_encode($data);
		}else{
			echo "no";		
		}
	}
	
    function getDataKasKeluar(){
        $postData = $this->input->post();
        $data = $this->M_KasKeluar->ListKeluar($postData);
        echo json_encode($data);
    }

	function getDataSaldo() {
		$postData = $this->input->post();
		$data = $this->M_Saldo->CekSaldo($postData);
		// var_dump($postData);exit;
		// $this->error($postData);
		echo json_encode($data);
	}

	function ListInvoiceCustomer(){
		$id = $this->input->post('id');
		$data = $this->db->get_where('invoices',['customer_id' => $id])->result();
		echo json_encode($data);
	}
	function RincianInvoice(){
		$id = $this->input->post('invoice');
		$cek_acc_kas_masuk = $this->db->get_where('acc_kas_masuks',['invoice' => $id])->num_rows();
		// echo json_encode($cek_cicilan);
		if ($cek_acc_kas_masuk > 0) {
			$this->db->select('
			(CASE 
				WHEN b.id IS NULL THEN
					0
				ELSE
					a.total_sementara - sum( b.cicilan ) - a.pph23 - a.potongan_bank - a.potongan_klaim
			END) AS amount,
			(CASE 
				WHEN b.id IS NULL THEN
					"Cash"
				ELSE
					"Cicilan"
			END) AS payment,
			a.invoice,
			a.periode,
			a.pph23 AS is_taxed,
			c.is_active AS customer_active,
			
			');
			
			$this->db->from('acc_kas_masuks as a');
			$this->db->join('cicilan_kas_masuks as b','a.invoice = b.invoice','left');
			$this->db->join('customers as c','c.id = a.customer_id','left');
			$this->db->where('a.invoice ='. $id);
			$data = $this->db->get();
			echo json_encode($data->result());
		}else{
			$this->db->select('a.number as invoice,a.periode,a.amount,b.is_taxed,b.is_active as customer_active');
			$this->db->from('invoices as a');
			$this->db->join('customers as b','a.customer_id = b.id');
			$this->db->join('receipts as c','a.id = c.invoice_id');
			$this->db->where('a.number ='. $id);
			$data = $this->db->get();
			echo json_encode($data->result());
		}
	}
	function logout(){
		$this->session->sess_destroy();
		redirect('');
	}
}
