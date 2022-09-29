<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('M_Auth');
    }

    public function index(){
        $username = $this->input->post('username');
        $password = $this->input->post('password');


        $data = $this->M_Auth->login($username);
       
        if ($password == true) {
            if ($username == $data['username']) {
                    if (password_verify($password, $data['password'])) {
                        $datax = [
                            'id_user' => $data['id_user'],
                            'username' => $data['username'],
                            'nama_user' => $data['nama_user'],
                            'cabang' => $data['cabang'],
                        ];
                        $this->session->set_userdata($datax);
                        $this->session->set_flashdata("msg", "<div class='alert alert-success'>Login Berhasil</div>");
                        redirect('dashboard');
                    } else {
                        $this->session->set_flashdata("msg", "<div class='alert alert-danger'>Password salahl</div>");
                        redirect('auth');
                    }
            } else {
                $this->session->set_flashdata("msg", "<div class='alert alert-danger'>Not Found</div>");
                redirect('auth');
            }
        }
        $this->load->view('Login');
    }
  
}