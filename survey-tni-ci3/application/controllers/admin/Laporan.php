<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Laporan Controller
 * Laporan survey + Export Excel
 */
class Laporan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('survey_model');
        $this->load->model('laporan_model');
        $this->load->model('pertanyaan_model');

        if (!$this->session->userdata('logged_in')) {
            redirect('admin/login');
        }
    }

    /**
     * List laporan survey
     */
    public function index() {
        $data['title'] = 'Laporan Survey';

        // Filter
        $filter = array();
        if ($this->input->get('tanggal_dari')) {
            $filter['tanggal_dari'] = $this->input->get('tanggal_dari');
        }
        if ($this->input->get('tanggal_sampai')) {
            $filter['tanggal_sampai'] = $this->input->get('tanggal_sampai');
        }
        if ($this->input->get('poli')) {
            $filter['poli'] = $this->input->get('poli');
        }
        if ($this->input->get('kategori_kepuasan')) {
            $filter['kategori_kepuasan'] = $this->input->get('kategori_kepuasan');
        }

        // Pagination
        $config['base_url'] = base_url('admin/laporan/index');
        $config['total_rows'] = $this->laporan_model->count_all($filter);
        $config['per_page'] = 20;
        $config['uri_segment'] = 4;

        // Pagination style Bootstrap
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = 'First';
        $config['last_link'] = 'Last';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['attributes'] = array('class' => 'page-link');

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

        // Get laporan
        $data['laporan'] = $this->laporan_model->get_all($config['per_page'], $page, $filter);
        $data['pagination'] = $this->pagination->create_links();

        // Get poli list untuk filter
        $data['poli_list'] = $this->laporan_model->get_poli_list();

        // Summary
        $data['summary'] = $this->laporan_model->get_summary($filter);

        // Load views
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/laporan/index', $data);
        $this->load->view('templates/admin_footer');
    }

    /**
     * Detail survey
     */
    public function detail($survey_id) {
        $data['title'] = 'Detail Survey';
        $data['survey'] = $this->survey_model->get_detail($survey_id);

        if (!$data['survey']) {
            show_404();
        }

        // Get jawaban
        $data['jawaban'] = $this->survey_model->get_jawaban_by_survey($survey_id);

        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/laporan/detail', $data);
        $this->load->view('templates/admin_footer');
    }

    /**
     * Statistik pertanyaan
     */
    public function statistik() {
        $data['title'] = 'Statistik Pertanyaan';

        // Filter
        $filter = array();
        if ($this->input->get('tanggal_dari')) {
            $filter['tanggal_dari'] = $this->input->get('tanggal_dari');
        }
        if ($this->input->get('tanggal_sampai')) {
            $filter['tanggal_sampai'] = $this->input->get('tanggal_sampai');
        }

        // Get statistik per pertanyaan
        $data['statistik'] = $this->laporan_model->get_statistik_pertanyaan($filter);

        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/laporan/statistik', $data);
        $this->load->view('templates/admin_footer');
    }

    /**
     * Export laporan ke Excel
     */
    public function export() {
        // Load PHPExcel
        require_once APPPATH . 'libraries/PHPExcel/PHPExcel.php';

        $excel = new PHPExcel();
        $excel->setActiveSheetIndex(0);
        $sheet = $excel->getActiveSheet();

        // Set properties
        $excel->getProperties()
              ->setCreator("Survey TNI")
              ->setTitle("Laporan Survey Kepuasan Pasien")
              ->setSubject("Laporan Survey")
              ->setDescription("Laporan hasil survey kepuasan pasien TNI");

        // Filter
        $filter = array();
        if ($this->input->get('tanggal_dari')) {
            $filter['tanggal_dari'] = $this->input->get('tanggal_dari');
        }
        if ($this->input->get('tanggal_sampai')) {
            $filter['tanggal_sampai'] = $this->input->get('tanggal_sampai');
        }
        if ($this->input->get('poli')) {
            $filter['poli'] = $this->input->get('poli');
        }
        if ($this->input->get('kategori_kepuasan')) {
            $filter['kategori_kepuasan'] = $this->input->get('kategori_kepuasan');
        }

        // Get data
        $data = $this->laporan_model->get_all_for_export($filter);
        $pertanyaan = $this->pertanyaan_model->get_all_active_ordered();

        // Header
        $col = 0;
        $headers = array(
            'No', 'Tanggal Survey', 'Nama', 'Pangkat', 'NRP', 'Kesatuan',
            'Jenis Kelamin', 'Umur', 'Poli', 'Total Nilai', 'Rata-rata',
            'Kategori Kepuasan'
        );

        // Add pertanyaan headers
        foreach ($pertanyaan as $p) {
            $headers[] = $p->pertanyaan;
        }

        $headers[] = 'Saran/Kritik';

        // Write headers
        foreach ($headers as $header) {
            $sheet->setCellValueByColumnAndRow($col, 1, $header);
            $col++;
        }

        // Style header
        $lastCol = PHPExcel_Cell::stringFromColumnIndex(count($headers) - 1);
        $sheet->getStyle('A1:' . $lastCol . '1')->getFont()->setBold(true);
        $sheet->getStyle('A1:' . $lastCol . '1')->getFill()
              ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
              ->getStartColor()->setRGB('4CAF50');
        $sheet->getStyle('A1:' . $lastCol . '1')->getFont()->getColor()->setRGB('FFFFFF');

        // Write data
        $row = 2;
        $no = 1;
        foreach ($data as $survey) {
            $col = 0;

            // Get jawaban untuk survey ini
            $jawaban_data = $this->survey_model->get_jawaban_by_survey($survey->survey_id);
            $jawaban_map = array();
            foreach ($jawaban_data as $j) {
                $jawaban_map[$j->pertanyaan_id] = $j->jawaban_value;
            }

            // Basic data
            $sheet->setCellValueByColumnAndRow($col++, $row, $no++);
            $sheet->setCellValueByColumnAndRow($col++, $row, $survey->tanggal_survey);
            $sheet->setCellValueByColumnAndRow($col++, $row, $survey->nama);
            $sheet->setCellValueByColumnAndRow($col++, $row, $survey->pangkat);
            $sheet->setCellValueByColumnAndRow($col++, $row, $survey->nrp);
            $sheet->setCellValueByColumnAndRow($col++, $row, $survey->kesatuan);
            $sheet->setCellValueByColumnAndRow($col++, $row, $survey->jenis_kelamin);
            $sheet->setCellValueByColumnAndRow($col++, $row, $survey->umur);
            $sheet->setCellValueByColumnAndRow($col++, $row, $survey->poli);
            $sheet->setCellValueByColumnAndRow($col++, $row, $survey->total_nilai);
            $sheet->setCellValueByColumnAndRow($col++, $row, $survey->rata_rata);
            $sheet->setCellValueByColumnAndRow($col++, $row, $survey->kategori_kepuasan);

            // Jawaban per pertanyaan
            foreach ($pertanyaan as $p) {
                $nilai = isset($jawaban_map[$p->id]) ? $jawaban_map[$p->id] : '';
                $sheet->setCellValueByColumnAndRow($col++, $row, $nilai);
            }

            // Saran
            $sheet->setCellValueByColumnAndRow($col++, $row, $survey->saran);

            $row++;
        }

        // Auto size columns
        foreach (range(0, count($headers) - 1) as $columnID) {
            $sheet->getColumnDimensionByColumn($columnID)->setAutoSize(true);
        }

        // Output
        $filename = 'laporan_survey_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $writer->save('php://output');
    }

    /**
     * Export statistik pertanyaan ke Excel
     */
    public function export_statistik() {
        require_once APPPATH . 'libraries/PHPExcel/PHPExcel.php';

        $excel = new PHPExcel();
        $excel->setActiveSheetIndex(0);
        $sheet = $excel->getActiveSheet();

        // Filter
        $filter = array();
        if ($this->input->get('tanggal_dari')) {
            $filter['tanggal_dari'] = $this->input->get('tanggal_dari');
        }
        if ($this->input->get('tanggal_sampai')) {
            $filter['tanggal_sampai'] = $this->input->get('tanggal_sampai');
        }

        // Get data
        $data = $this->laporan_model->get_statistik_pertanyaan($filter);

        // Header
        $headers = array(
            'No', 'Kategori', 'Pertanyaan', 'Total Jawaban', 'Rata-rata',
            'Nilai Min', 'Nilai Max', 'Sangat Puas (5)', 'Puas (4)',
            'Cukup (3)', 'Tidak Puas (2)', 'Sangat Tidak Puas (1)'
        );

        $col = 0;
        foreach ($headers as $header) {
            $sheet->setCellValueByColumnAndRow($col, 1, $header);
            $col++;
        }

        // Style header
        $sheet->getStyle('A1:L1')->getFont()->setBold(true);
        $sheet->getStyle('A1:L1')->getFill()
              ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
              ->getStartColor()->setRGB('4CAF50');

        // Write data
        $row = 2;
        $no = 1;
        foreach ($data as $stat) {
            $sheet->setCellValueByColumnAndRow(0, $row, $no++);
            $sheet->setCellValueByColumnAndRow(1, $row, $stat->nama_kategori);
            $sheet->setCellValueByColumnAndRow(2, $row, $stat->pertanyaan);
            $sheet->setCellValueByColumnAndRow(3, $row, $stat->total_jawaban);
            $sheet->setCellValueByColumnAndRow(4, $row, $stat->rata_rata);
            $sheet->setCellValueByColumnAndRow(5, $row, $stat->nilai_min);
            $sheet->setCellValueByColumnAndRow(6, $row, $stat->nilai_max);
            $sheet->setCellValueByColumnAndRow(7, $row, $stat->jawaban_5);
            $sheet->setCellValueByColumnAndRow(8, $row, $stat->jawaban_4);
            $sheet->setCellValueByColumnAndRow(9, $row, $stat->jawaban_3);
            $sheet->setCellValueByColumnAndRow(10, $row, $stat->jawaban_2);
            $sheet->setCellValueByColumnAndRow(11, $row, $stat->jawaban_1);
            $row++;
        }

        // Auto size
        foreach (range('A', 'L') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Output
        $filename = 'statistik_pertanyaan_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $writer->save('php://output');
    }
}
