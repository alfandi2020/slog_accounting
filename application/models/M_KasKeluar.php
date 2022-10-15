<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_KasKeluar extends CI_Model {
    var $kas_keluar = 'kas_keluar';
    public function insert($data){
        return $this->db->insert('kas_keluar',$data);
    }
    public function desc_kas_keluar($data){
        return $this->db->insert('mt_kas_keluar_desc',$data);
    }
    public function ListKeluar($postData){

            
        //search
        $searchValue = $postData['search']['value']; 
        $searchQuery = "";
        if($searchValue != ''){
            $searchQuery = " (".$this->kas_keluar.".deskripsi like '%".$searchValue."%' ) ";
        }
        
        $this->db->select('count(*) as allcount');
        if($searchQuery != '')
            $this->db->where($searchQuery);
        $this->db->where('MONTH(date_out)',$postData['month']);
        $this->db->where('YEAR(date_out)',$postData['year']);
        $records = $this->db->get($this->kas_keluar)->result();
        $totalRecords = $records[0]->allcount;
        
        $this->db->select('count(*) as allcount');
        $this->db->where('MONTH(date_out)',$postData['month']);
        $this->db->where('YEAR(date_out)',$postData['year']);
        if($searchQuery != '')
            $this->db->where($searchQuery);
        $records = $this->db->get($this->kas_keluar)->result();
        $totalRecordwithFilter = $records[0]->allcount;

        $this->db->select(['*']);
        $this->db->from($this->kas_keluar);
        $this->db->join('mt_kas_keluar_desc', 'kas_keluar.deskripsi = mt_kas_keluar_desc.id');
        if($searchQuery != '')
            $this->db->where($searchQuery);
        $this->db->where('MONTH(date_out)',$postData['month']);
        $this->db->where('YEAR(date_out)',$postData['year']);
        $records = $this->db->get()->result();

        $data = array();
   
        foreach($records as $record ){
   
           $data[] = array( 
                "date_out"=>$record->date_out,
                "deskripsi"=>$record->deskripsi,
                "biaya"=>"Rp.".number_format($record->biaya),
           ); 
        }

        $this->db->select(['"<strong>Total Valiable Cost</strong>" as deskripsi', 'SUM(biaya) as biaya', '" " as date_out']);
        $this->db->from($this->kas_keluar);
        if($searchQuery != '')
            $this->db->where($searchQuery);
        $this->db->where('MONTH(date_out)',$postData['month']);
        $this->db->where('YEAR(date_out)',$postData['year']);
        $records_total = $this->db->get()->row_array();

        $data[] = array( 
            "date_out"=>$records_total['date_out'],
            "deskripsi"=>$records_total['deskripsi'],
            "biaya"=>"<strong>Rp.".number_format($records_total['biaya'])."</strong>",
         ); 
   
        //response
        $response = array(
           "draw" => 0,
           "iTotalRecords" => $totalRecords,
           "iTotalDisplayRecords" => $totalRecordwithFilter,
           "aaData" => $data
        );
   
        return $response; 
    }
}