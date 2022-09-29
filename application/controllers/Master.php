<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->model(array('M_KasKeluar'));
        $this->load->library('form_validation');
        if ($this->session->userdata('username') == false) {
            $this->session->set_flashdata("msg", "<div class='alert alert-danger'>Opss anda blm login</div>");
            redirect('auth');
        }
    }
    public function list_desc(){
        $data = [
			"list" => $this->db->get_where('mt_kas_keluar_desc')->result(),
			
		];
		$this->load->view('body/header');
		$this->load->view('page/master/desc_kas_keluar',$data);
		$this->load->view('body/footer');
    }
    function list_desc_action(){
        $deskripsi = $this->input->post('deskripsi');
        if($this->input->is_ajax_request()) {
			$data = [
				"deskripsi" => $deskripsi,
			];
			if($this->M_KasKeluar->desc_kas_keluar($data))
			{
				$data = [
					'responce' => 'success',
					'message' => 'Data list Deskripsi berhasil di input..!'
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
}