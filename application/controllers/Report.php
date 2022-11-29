<?php
// ob_start();
defined('BASEPATH') OR exit('No direct script access allowed');

//Memanggil file autoload
require 'vendor/autoload.php';

// Include librari PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Report extends CI_Controller {
    
    var $invoices = 'invoices';
    var $customers = 'customers';
    var $networks = 'networks';
    var $acc_kas_masuks = 'acc_kas_masuks';
    var $cicilan_kas_masuks = 'cicilan_kas_masuks';
	public function __construct(){
        parent::__construct();
		$this->load->helper(array('form', 'url'));
        $this->load->model(array('M_Report'));
		$this->load->library('form_validation');
		if ($this->session->userdata('username') == false) {
			$this->session->set_flashdata("msg", "<div class='alert alert-danger'>Opss anda blm login</div>");
            redirect('auth');
		}
    }
    function masuk(){
        $data['masuk'] = $this->db->get_where('invoices')->result();
        $this->load->view('body/header');
		$this->load->view('page/report/masuk',$data);
		$this->load->view('body/footer');
    }
    function view(){
        $data = [];
        if (!empty($this->uri->segment(3))) {
            $id_acc_kas_masuk = $this->uri->segment(3);
            $this->db->select('
                a.*,
                b.name AS customer_name,
                b.address AS customer_address,
                (CASE 
                    WHEN c.id IS NOT NULL THEN SUM(c.cicilan)
                    ELSE 0 
                END) AS total_cicilan,
                (CASE 
                    WHEN c.id IS NULL THEN "Cash"
                    ELSE "Cicilan"
                END) AS payment,
                (CASE 
                WHEN a.id IS NOT NULL AND c.id IS NULL THEN 
                   "Paid" 
                WHEN a.id IS NOT NULL AND c.id IS NOT NULL AND a.total_sementara - a.potongan_bank - a.potongan_klaim - a.pph23 - (SUM(c.cicilan)) = 0 THEN
                   "Paid" 
                WHEN  a.id IS NULL AND c.id IS NULL THEN
                   "Unpaid" 
                ELSE
                   "Unpaid"
             END) AS status_payment
               
            ');
			$this->db->from('acc_kas_masuks a');
            $this->db->join('customers b','b.id = a.customer_id','left');
            $this->db->join('cicilan_kas_masuks c','c.invoice = a.invoice','left');
            $this->db->where('a.id',$id_acc_kas_masuk);
            $data_kas_masuk = $this->db->get()->row_array();
            $data['data_kas_masuk'] = $data_kas_masuk;

            $this->db->select('
                b.invoice,
                b.cicilan,
                b.date_in
            ');
            $this->db->from('acc_kas_masuks a');
            $this->db->join('cicilan_kas_masuks b','b.invoice = a.invoice','left');
            $this->db->where('a.id',$id_acc_kas_masuk);
            $this->db->order_by('b.date_in',"asc");
            $data_cicilan = $this->db->get()->result();
            $data['data_cicilan'] = $data_cicilan;
        }
        $this->load->view('body/header');
        $this->load->view('page/report/view',$data);
        $this->load->view('body/footer');
    }

    function getReportMasuk(){
        $postData = $this->input->post();
        $data = $this->M_Report->ListMasuk($postData);
        echo json_encode($data);
    }
    
    function getReportMasukdetail(){
        $postData = $this->input->post();
        $this->db->select('
            ROW_NUMBER() OVER (ORDER BY b.id) AS row_id,
            b.invoice,
            b.cicilan,
            b.date_in,
           ');
        $this->db->from('acc_kas_masuks a');
        $this->db->join('cicilan_kas_masuks b','b.invoice = a.invoice','left');
        $this->db->where('a.invoice',$postData['invoice_number']);
        $this->db->order_by('b.date_in',"asc");
        $data_cicilan = $this->db->get()->result();
        $response = array(
            "aaData" => $data_cicilan
        );
        echo json_encode($response);
    }
    function keluar(){
        $data['keluar'] = $this->db->get_where('invoices')->result();
        $this->load->view('body/header');
		$this->load->view('page/report/keluar',$data);
		$this->load->view('body/footer');
    }

    function excel_masuk() {
        $spreadsheet = new SpreadSheet();
        $sheet = $spreadsheet->getActiveSheet();

        $style_col = [
            'font' => ['bold' => true], // Set font nya jadi bold
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ],
            'borders' => [
                'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
                'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
                'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
                'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
            ]
        ];
            // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row = [
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ],
            'borders' => [
                'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
                'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
                'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
                'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
            ]
        ];

		$tahun = $this->input->get('years_saldo');
		$bulan = $this->input->get('months_saldo');

        $sheet->setCellValue('A1', "REPORT KAS MASUK"); // Set kolom A1 dengan tulisan "REPORT KAS MASUK"
        $sheet->mergeCells('A1:G1'); // Set Merge Cell pada kolom A1 sampai E1
        $sheet->getStyle('A1')->getFont()->setBold(true); // Set bold kolom A1
        // Buat header tabel nya pada baris ke 3
        $sheet->setCellValue('A3', "No"); // Set kolom A3 dengan tulisan "No"
        $sheet->setCellValue('B3', "Cabang"); // Set kolom B3 dengan tulisan "Cabang"
        $sheet->setCellValue('C3', "Nama Customer"); // Set kolom C3 dengan tulisan "Nama Customer"
        $sheet->setCellValue('D3', "No Invoice"); // Set kolom D3 dengan tulisan "No Invoice"
        $sheet->setCellValue('E3', "Total"); // Set kolom D3 dengan tulisan "Total"
        $sheet->setCellValue('F3', "Payment"); // Set kolom E3 dengan tulisan "Payment"
        $sheet->setCellValue('G3', "Status"); // Set kolom E3 dengan tulisan "Status"
        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
        $sheet->getStyle('A3')->applyFromArray($style_col);
        $sheet->getStyle('B3')->applyFromArray($style_col);
        $sheet->getStyle('C3')->applyFromArray($style_col);
        $sheet->getStyle('D3')->applyFromArray($style_col);
        $sheet->getStyle('E3')->applyFromArray($style_col);
        $sheet->getStyle('F3')->applyFromArray($style_col);
        $sheet->getStyle('G3')->applyFromArray($style_col);

        // $report_masuk = $this->M_Report->ListMasuk();

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
        

        if ($bulan) {
            if ($tahun) {
                $array = array(
                    'MONTH('.$this->acc_kas_masuks.'.date_in)' => $bulan, 
                    'YEAR('.$this->acc_kas_masuks.'.date_in)' => $tahun);
                $this->db->where($array);
            } else {
                // $this->db->where("DATE_FORMAT(".$this->acc_kas_masuks.".date_in,'%m')", $bulan);
                $this->db->where('MONTH('.$this->acc_kas_masuks.'.date_in)', $bulan);
            }
        } 
        // var_dump($bulan, $tahun, $array);exit;
        $this->db->group_by($this->acc_kas_masuks.'.invoice');
        $this->db->order_by($this->acc_kas_masuks.'.invoice');
        // $this->db->limit($rowperpage, $start);
        $records = $this->db->get()->result();
        $no = 1;
        $numrow = 4;

        // var_dump($this->db->where("DATE_FORMAT(".$this->acc_kas_masuks.".date_in,'%m')", $bulan_sekarang), $bulan);exit;

        foreach($records as $data) {
            
            $no_invoice = "'".$data->invoice;
            $sheet->setCellValue('A'.$numrow, $no);
            $sheet->setCellValue('B'.$numrow, $data->network_name);
            $sheet->setCellValue('C'.$numrow, $data->customer_name);
            $sheet->setCellValue('D'.$numrow, $no_invoice);
            $sheet->setCellValue('E'.$numrow, $data->total);
            $sheet->setCellValue('F'.$numrow, $data->payment);
            $sheet->setCellValue('G'.$numrow, $data->status);
            
            // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
            $sheet->getStyle('A'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('B'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('D'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('E'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('F'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('G'.$numrow)->applyFromArray($style_row);
            
            $no++; // Tambah 1 setiap kali looping
            $numrow++; // Tambah 1 setiap kali looping
        }
        $baris = $numrow;
        $fistRows = 2;
        $toRows = $baris - 1;
        $sheet->mergeCells('A'.$baris.':D'.$baris);

        for ($i = 'A'; $i !=  $spreadsheet->getActiveSheet()->getHighestColumn(); $i++) {
            $spreadsheet->getActiveSheet()->getColumnDimension($i)->setAutoSize(TRUE);
        }        

        // Set isi row TOTAL
        $sheet->setCellValue('A'.$baris , "TOTAL");
        $sheet->getStyle('A'.$baris)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getCell('E'.$baris)->setValue('=SUM(E'.$fistRows.':E'.$toRows.')');

        // Set border baris TOTAL
        $sheet->getStyle('A'.$baris)->applyFromArray($style_col);
        $sheet->getStyle('B'.$baris)->applyFromArray($style_col);
        $sheet->getStyle('C'.$baris)->applyFromArray($style_col);
        $sheet->getStyle('D'.$baris)->applyFromArray($style_col);
        $sheet->getStyle('E'.$baris)->applyFromArray($style_col);
        $sheet->getStyle('F'.$baris)->applyFromArray($style_col);
        $sheet->getStyle('G'.$baris)->applyFromArray($style_col);

        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $sheet->getDefaultRowDimension()->setRowHeight(-1);
        // Set orientasi kertas jadi LANDSCAPE
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file excel nya
        $sheet->setTitle("Laporan Kas Masuk");
        // Proses file excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Laporan Kas Masuk.xls"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    function excel_keluar() {
        $spreadsheet = new SpreadSheet();
        $sheet = $spreadsheet->getActiveSheet();

        $style_col = [
            'font' => ['bold' => true], // Set font nya jadi bold
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ],
            'borders' => [
                'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
                'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
                'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
                'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
            ]
        ];
            // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
        $style_row = [
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
            ],
            'borders' => [
                'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
                'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
                'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
                'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
            ]
        ];

		$tahun = $this->input->get('years_saldo');
		$bulan = $this->input->get('months_saldo');

        $sheet->setCellValue('A1', "REPORT KAS KELUAR"); // Set kolom A1 dengan tulisan "REPORT KAS MASUK"
        $sheet->mergeCells('A1:G1'); // Set Merge Cell pada kolom A1 sampai E1
        $sheet->getStyle('A1')->getFont()->setBold(true); // Set bold kolom A1
        // Buat header tabel nya pada baris ke 3
        $sheet->setCellValue('A3', "No"); // Set kolom A3 dengan tulisan "No"
        $sheet->setCellValue('B3', "Cabang"); // Set kolom B3 dengan tulisan "Cabang"
        $sheet->setCellValue('C3', "Nama Customer"); // Set kolom C3 dengan tulisan "Nama Customer"
        $sheet->setCellValue('D3', "No Invoice"); // Set kolom D3 dengan tulisan "No Invoice"
        $sheet->setCellValue('E3', "Total"); // Set kolom D3 dengan tulisan "Total"
        $sheet->setCellValue('F3', "Payment"); // Set kolom E3 dengan tulisan "Payment"
        $sheet->setCellValue('G3', "Status"); // Set kolom E3 dengan tulisan "Status"
        // Apply style header yang telah kita buat tadi ke masing-masing kolom header
        $sheet->getStyle('A3')->applyFromArray($style_col);
        $sheet->getStyle('B3')->applyFromArray($style_col);
        $sheet->getStyle('C3')->applyFromArray($style_col);
        $sheet->getStyle('D3')->applyFromArray($style_col);
        $sheet->getStyle('E3')->applyFromArray($style_col);
        $sheet->getStyle('F3')->applyFromArray($style_col);
        $sheet->getStyle('G3')->applyFromArray($style_col);

        // $report_masuk = $this->M_Report->ListMasuk();

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
        

        if ($bulan) {
            if ($tahun) {
                $array = array(
                    'MONTH('.$this->acc_kas_masuks.'.date_in)' => $bulan, 
                    'YEAR('.$this->acc_kas_masuks.'.date_in)' => $tahun);
                $this->db->where($array);
            } else {
                // $this->db->where("DATE_FORMAT(".$this->acc_kas_masuks.".date_in,'%m')", $bulan);
                $this->db->where('MONTH('.$this->acc_kas_masuks.'.date_in)', $bulan);
            }
        } 
        // var_dump($bulan, $tahun, $array);exit;
        $this->db->group_by($this->acc_kas_masuks.'.invoice');
        $this->db->order_by($this->acc_kas_masuks.'.invoice');
        // $this->db->limit($rowperpage, $start);
        $records = $this->db->get()->result();
        $no = 1;
        $numrow = 4;

        // var_dump($this->db->where("DATE_FORMAT(".$this->acc_kas_masuks.".date_in,'%m')", $bulan_sekarang), $bulan);exit;

        foreach($records as $data) {
            
            $no_invoice = "'".$data->invoice;
            $sheet->setCellValue('A'.$numrow, $no);
            $sheet->setCellValue('B'.$numrow, $data->network_name);
            $sheet->setCellValue('C'.$numrow, $data->customer_name);
            $sheet->setCellValue('D'.$numrow, $no_invoice);
            $sheet->setCellValue('E'.$numrow, $data->total);
            $sheet->setCellValue('F'.$numrow, $data->payment);
            $sheet->setCellValue('G'.$numrow, $data->status);
            
            // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
            $sheet->getStyle('A'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('B'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('D'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('E'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('F'.$numrow)->applyFromArray($style_row);
            $sheet->getStyle('G'.$numrow)->applyFromArray($style_row);
            
            $no++; // Tambah 1 setiap kali looping
            $numrow++; // Tambah 1 setiap kali looping
        }
        $baris = $numrow;
        $fistRows = 2;
        $toRows = $baris - 1;
        $sheet->mergeCells('A'.$baris.':D'.$baris);

        for ($i = 'A'; $i !=  $spreadsheet->getActiveSheet()->getHighestColumn(); $i++) {
            $spreadsheet->getActiveSheet()->getColumnDimension($i)->setAutoSize(TRUE);
        }        

        // Set isi row TOTAL
        $sheet->setCellValue('A'.$baris , "TOTAL");
        $sheet->getStyle('A'.$baris)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getCell('E'.$baris)->setValue('=SUM(E'.$fistRows.':E'.$toRows.')');

        // Set border baris TOTAL
        $sheet->getStyle('A'.$baris)->applyFromArray($style_col);
        $sheet->getStyle('B'.$baris)->applyFromArray($style_col);
        $sheet->getStyle('C'.$baris)->applyFromArray($style_col);
        $sheet->getStyle('D'.$baris)->applyFromArray($style_col);
        $sheet->getStyle('E'.$baris)->applyFromArray($style_col);
        $sheet->getStyle('F'.$baris)->applyFromArray($style_col);
        $sheet->getStyle('G'.$baris)->applyFromArray($style_col);

        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $sheet->getDefaultRowDimension()->setRowHeight(-1);
        // Set orientasi kertas jadi LANDSCAPE
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        // Set judul file excel nya
        $sheet->setTitle("Laporan Kas Keluar");
        // Proses file excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Laporan Kas Keluar.xls"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
}