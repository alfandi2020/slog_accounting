<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_KasMasuk extends CI_Model {
    public function insert($data){
        return $this->db->insert('acc_kas_masuks',$data);
    }
    public function insert_cicilan($data){
        return $this->db->insert('cicilan_kas_masuks',$data);
    }
}