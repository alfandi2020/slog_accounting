<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Auth extends CI_Model {
    public function login($username){
        return $this->db->query("SELECT
        a.username,a.name AS nama_user,b.name AS cabang,a.id AS id_user ,a.password
    FROM
        users AS a
        JOIN networks AS b ON a.network_id = b.id where a.username='$username'")->row_array();
    }
}