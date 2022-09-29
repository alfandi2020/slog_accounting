<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	public function __construct(){
        parent::__construct();
        $this->load->model('M_Auth');
		if ($this->session->userdata('username') == false) {
			$this->session->set_flashdata("msg", "<div class='alert alert-danger'>Opss anda blm login</div>");
            redirect('auth');
		}
    }
	public function index()
	{
		$this->load->view('body/header');
		$this->load->view('page/dashboard');
		$this->load->view('body/footer');
	}
	public function logout(){
		$this->session->sess_destroy();
		redirect('auth');
	}
}
