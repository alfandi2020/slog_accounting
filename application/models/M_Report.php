<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Report extends CI_Model {
   var $invoices = 'invoices';
   var $customers = 'customers';
   var $networks = 'networks';
   var $acc_kas_masuks = 'acc_kas_masuks';
   var $cicilan_kas_masuks = 'cicilan_kas_masuks';

    public function ListMasuk($postData){
    $response = array();

        //value
     $draw = $postData['draw'];
     $start = $postData['start'];
     $rowperpage = $postData['length']; 
     $columnIndex = $postData['order'][0]['column']; 
     $columnName = $postData['columns'][$columnIndex]['data'];
     $columnSortOrder = $postData['order'][0]['dir'];
     $searchValue = $postData['search']['value']; 

     //search
     $searchQuery = "";
     if($searchValue != ''){
        $searchQuery = " (".$this->customers.".name like '%".$searchValue."%' or ".$this->customers.".name like '%".$searchValue."%' or ".$this->customers.".name like'%".$searchValue."%' ) ";
     }

     $this->db->select('count(*) as allcount');
     $this->db->join($this->customers, $this->customers.'.id = '.$this->acc_kas_masuks.'.customer_id');
     $this->db->join($this->networks, $this->networks.'.id = '.$this->customers.'.network_id');
     $records = $this->db->get($this->acc_kas_masuks)->result();
     $totalRecords = $records[0]->allcount;

     $this->db->select('count(*) as allcount');
     $this->db->join($this->customers, $this->customers.'.id = '.$this->acc_kas_masuks.'.customer_id');
     $this->db->join($this->networks, $this->networks.'.id = '.$this->customers.'.network_id');
     if($searchQuery != '')
        $this->db->where($searchQuery);
     $records = $this->db->get($this->acc_kas_masuks)->result();
     $totalRecordwithFilter = $records[0]->allcount;

      $this->db->select([
         $this->acc_kas_masuks.'.*',
         $this->customers.'.name AS customer_name',
         $this->networks.'.name AS network_name',
         '(CASE
         WHEN '.$this->cicilan_kas_masuks.'.id IS NULL AND '.$this->acc_kas_masuks.'.id IS NOT NULL THEN
            "Cash"
         ELSE 
            "Cicilan" 
      END) AS payment,
      (CASE 
         WHEN '.$this->acc_kas_masuks.'.id IS NOT NULL AND '.$this->cicilan_kas_masuks.'.id IS NULL THEN 
            "Paid" 
         WHEN '.$this->acc_kas_masuks.'.id IS NOT NULL AND '.$this->cicilan_kas_masuks.'.id IS NOT NULL AND '.$this->acc_kas_masuks.'.total_sementara - '.$this->acc_kas_masuks.'.potongan_bank - '.$this->acc_kas_masuks.'.potongan_klaim - '.$this->acc_kas_masuks.'.pph23 - (SUM('.$this->cicilan_kas_masuks.'.cicilan)) = 0 THEN
            "Paid" 
         WHEN  '.$this->acc_kas_masuks.'.id IS NULL AND '.$this->cicilan_kas_masuks.'.id IS NULL THEN
            "Unpaid" 
         ELSE
            "Unpaid"
      END) AS status'
      ]);
      $this->db->from($this->acc_kas_masuks);
      $this->db->join($this->customers, $this->customers.'.id = '.$this->acc_kas_masuks.'.customer_id','left');
      $this->db->join($this->networks, $this->networks.'.id = '.$this->customers.'.network_id','left');
      $this->db->join($this->cicilan_kas_masuks, $this->cicilan_kas_masuks.'.invoice = '.$this->acc_kas_masuks.'.invoice','left');

      $this->db->like($this->customers.'.name',$searchValue);
      if($searchQuery != '')
         $this->db->where($searchQuery);
      //   $this->db->order_by($columnName, $columnSortOrder);
      $this->db->group_by($this->acc_kas_masuks.'.invoice');
      $this->db->order_by($this->acc_kas_masuks.'.invoice', $columnSortOrder);
      $this->db->limit($rowperpage, $start);
      $records = $this->db->get()->result();

     $data = array();

     foreach($records as $record ){

        $data[] = array( 
           "network_name"=>$record->network_name,
           "customer_name"=>$record->customer_name,
           "invoice_number"=>$record->invoice,
           "payment"=>$record->payment,
           "status"=>$record->status === 'Paid' ? '<span class="btn btn-success">Paid</span>' : '<span class="btn btn-danger">Unpaid</span>',
           "action"=>'<a href="'.base_url().'report/view/'.$record->id.'"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg></a>',
        ); 
     }

     //response
     $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
     );

     return $response; 
    }
    
}