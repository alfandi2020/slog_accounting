<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {
	public function __construct(){
        parent::__construct();
		$this->load->helper(array('form', 'url'));
        $this->load->model(array('M_Report'));
		$this->load->library('form_validation');
		if ($this->session->userdata('username') == false) {
			$this->session->set_flashdata("msg", "<div class='alert alert-danger'>Opss anda blm login</div>");
            redirect('auth');
		}
    }
    function masuk(){
        $data['masuk'] = $this->db->get_where('invoices')->result();
        $this->load->view('body/header');
		$this->load->view('page/report/masuk',$data);
		$this->load->view('body/footer');
    }
    function view(){
        $data = [];
        if (!empty($this->uri->segment(3))) {
            $id_acc_kas_masuk = $this->uri->segment(3);
            $this->db->select('
                a.*,
                b.name AS customer_name,
                b.address AS customer_address,
                (CASE 
                    WHEN c.id IS NOT NULL THEN SUM(c.cicilan)
                    ELSE 0 
                END) AS total_cicilan,
                (CASE 
                    WHEN c.id IS NULL THEN "Cash"
                    ELSE "Cicilan"
                END) AS payment,
                (CASE 
                WHEN a.id IS NOT NULL AND c.id IS NULL THEN 
                   "Paid" 
                WHEN a.id IS NOT NULL AND c.id IS NOT NULL AND a.total_sementara - a.potongan_bank - a.potongan_klaim - a.pph23 - (SUM(c.cicilan)) = 0 THEN
                   "Paid" 
                WHEN  a.id IS NULL AND c.id IS NULL THEN
                   "Unpaid" 
                ELSE
                   "Unpaid"
             END) AS status_payment
               
            ');
			$this->db->from('acc_kas_masuks a');
            $this->db->join('customers b','b.id = a.customer_id','left');
            $this->db->join('cicilan_kas_masuks c','c.invoice = a.invoice','left');
            $this->db->where('a.id',$id_acc_kas_masuk);
            $data_kas_masuk = $this->db->get()->row_array();
            $data['data_kas_masuk'] = $data_kas_masuk;

            $this->db->select('
                b.invoice,
                b.cicilan,
                b.date_in
            ');
            $this->db->from('acc_kas_masuks a');
            $this->db->join('cicilan_kas_masuks b','b.invoice = a.invoice','left');
            $this->db->where('a.id',$id_acc_kas_masuk);
            $this->db->order_by('b.date_in',"asc");
            $data_cicilan = $this->db->get()->result();
            $data['data_cicilan'] = $data_cicilan;
        }
        $this->load->view('body/header');
        $this->load->view('page/report/view',$data);
        $this->load->view('body/footer');
    }

    function getReportMasuk(){
        $postData = $this->input->post();
        $data = $this->M_Report->ListMasuk($postData);
        echo json_encode($data);
    }
    
    function getReportMasukdetail(){
        $postData = $this->input->post();
        $this->db->select('
            ROW_NUMBER() OVER (ORDER BY b.id) AS row_id,
            b.invoice,
            b.cicilan,
            b.date_in,
           ');
        $this->db->from('acc_kas_masuks a');
        $this->db->join('cicilan_kas_masuks b','b.invoice = a.invoice','left');
        $this->db->where('a.invoice',$postData['invoice_number']);
        $this->db->order_by('b.date_in',"asc");
        $data_cicilan = $this->db->get()->result();
        $response = array(
            "aaData" => $data_cicilan
        );
        echo json_encode($response);
    }
    function keluar(){
        $data['keluar'] = $this->db->get_where('invoices')->result();
        $this->load->view('body/header');
		$this->load->view('page/report/keluar',$data);
		$this->load->view('body/footer');
    }

}