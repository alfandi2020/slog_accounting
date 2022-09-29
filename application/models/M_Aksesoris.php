<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Aksesoris extends CI_Model {
    public function insert($data){
        return $this->db->insert('aksesoris',$data);
    }
    public function insert_history($data){
        return $this->db->insert('aksesoris_history',$data);
    }
    public function getKdAksesoris($kd_aksesoris){
        return $this->db->query("SELECT * FROM aksesoris where kd_aksesoris='$kd_aksesoris'")->result();
    }
}