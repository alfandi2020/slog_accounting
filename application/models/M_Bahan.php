<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Bahan extends CI_Model {
    public function insert($data){
        return $this->db->insert('bahan',$data);
    }
    public function insert_history($data){
        return $this->db->insert('bahan_history',$data);
    }
    public function getKdbahan($kd_bahan){
        return $this->db->query("SELECT * FROM bahan where kd_bahan='$kd_bahan'")->result();
    }
}