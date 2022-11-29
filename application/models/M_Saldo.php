<?php 
defined('BASEPATH') OR exit ('No direct script access allowed');

class M_Saldo extends CI_Model {
   var $cek_saldo = 'cek_saldo';
   var $kas_keluar = 'kas_keluar';

   public function CekSaldo($postData) {
     $draw = $postData['draw'];
     $start = $postData['start'];
     $rowperpage = $postData['length']; 
     $columnIndex = $postData['order'][0]['column']; 
     $columnName = $postData['columns'][$columnIndex]['data'];
     $columnSortOrder = $postData['order'][0]['dir'];
     $searchValue = $postData['search']['value'];

      // search
      $searchValue = $postData['search']['value'];
      $searchQuery = "";

      if($searchValue != '') {
         $searchQuery = " (".$this->kas_keluar.".deskripsi like '%".$searchValue."%' ) ";
      }



   $this->db->select('count(*) as allcount');
     $records = $this->db->get('kas_keluar')->result();
     $totalRecords = $records[0]->allcount;

   
     $this->db->select('count(*) as allcount');
     if($searchQuery != '')
        $this->db->where($searchQuery);
     $records = $this->db->get('kas_keluar')->result();
     $totalRecordwithFilter = $records[0]->allcount;
   
   
     $sql_keluar = "SELECT *, sum(biaya) as total FROM kas_keluar";
      $data = $this->db->query($sql_keluar)->result();
      // return $data;

      // response
      $response = array();
      foreach ($data as $x) {
         $response[] = array(
            "saldo_keluar" => $x->biaya,
            "total" => $x->total,
            "total_x" => 1
         );
      }

      $response2 = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $response
     );

      return $response2;
   }
}