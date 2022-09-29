<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gaji extends CI_Controller {

	public function __construct(){
        parent::__construct();
		$this->load->helper(array('form', 'url'));
        $this->load->model(array('M_KasMasuk','M_KasKeluar'));
		$this->load->library('form_validation');
		if ($this->session->userdata('username') == false) {
			$this->session->set_flashdata("msg", "<div class='alert alert-danger'>Opss anda blm login</div>");
            redirect('auth');
		}
    }
    function index(){

    }
    function karyawan(){
        $data = [
			"customer" => $this->db->get_where('customers')->result(),
			
		];
        $this->load->view('body/header');
		$this->load->view('page/gaji/karyawan',$data);
		$this->load->view('body/footer');
    }
    function out(){
        $data = [
			"customer" => $this->db->get_where('customers')->result(),
			
		];
        $this->load->view('body/header');
		$this->load->view('page/gaji/form',$data);
		$this->load->view('body/footer');
    }
}