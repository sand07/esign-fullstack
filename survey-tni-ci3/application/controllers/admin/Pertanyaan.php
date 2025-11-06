<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Pertanyaan Controller
 * CRUD Pertanyaan + Import/Export Excel
 */
class Pertanyaan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('pertanyaan_model');
        $this->load->model('kategori_model');
        $this->load->library('upload');

        // Check if logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('admin/login');
        }
    }

    /**
     * List pertanyaan
     */
    public function index() {
        $data['title'] = 'Manajemen Pertanyaan';

        // Pagination
        $config['base_url'] = base_url('admin/pertanyaan/index');
        $config['total_rows'] = $this->pertanyaan_model->count_all();
        $config['per_page'] = 20;
        $config['uri_segment'] = 4;

        // Pagination style
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

        // Get pertanyaan with pagination
        $filter = array();
        if ($this->input->get('kategori_id')) {
            $filter['kategori_id'] = $this->input->get('kategori_id');
        }

        $data['pertanyaan'] = $this->pertanyaan_model->get_all($config['per_page'], $page, $filter);
        $data['pagination'] = $this->pagination->create_links();
        $data['kategori'] = $this->kategori_model->get_all_active();

        // Load views
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/pertanyaan/index', $data);
        $this->load->view('templates/admin_footer');
    }

    /**
     * Tambah pertanyaan
     */
    public function add() {
        $data['title'] = 'Tambah Pertanyaan';
        $data['kategori'] = $this->kategori_model->get_all_active();
        $data['action'] = 'add';

        if ($this->input->post()) {
            $this->_save();
        }

        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/pertanyaan/form', $data);
        $this->load->view('templates/admin_footer');
    }

    /**
     * Edit pertanyaan
     */
    public function edit($id) {
        $data['title'] = 'Edit Pertanyaan';
        $data['kategori'] = $this->kategori_model->get_all_active();
        $data['pertanyaan'] = $this->pertanyaan_model->get_by_id($id);
        $data['action'] = 'edit';

        if (!$data['pertanyaan']) {
            show_404();
        }

        if ($this->input->post()) {
            $this->_save($id);
        }

        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/pertanyaan/form', $data);
        $this->load->view('templates/admin_footer');
    }

    /**
     * Save pertanyaan (insert/update)
     */
    private function _save($id = null) {
        // Validation
        $this->form_validation->set_rules('kategori_id', 'Kategori', 'required|numeric');
        $this->form_validation->set_rules('pertanyaan', 'Pertanyaan', 'required');
        $this->form_validation->set_rules('tipe_jawaban', 'Tipe Jawaban', 'required');
        $this->form_validation->set_rules('urutan', 'Urutan', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            return false;
        }

        $data = array(
            'kategori_id'   => $this->input->post('kategori_id'),
            'pertanyaan'    => $this->input->post('pertanyaan'),
            'tipe_jawaban'  => $this->input->post('tipe_jawaban'),
            'is_wajib'      => $this->input->post('is_wajib') ? 1 : 0,
            'urutan'        => $this->input->post('urutan'),
            'is_active'     => $this->input->post('is_active') ? 1 : 0,
        );

        // Jika pilihan ganda, simpan pilihan
        if ($data['tipe_jawaban'] == 'pilihan_ganda') {
            $pilihan = $this->input->post('pilihan_jawaban');
            $data['pilihan_jawaban'] = json_encode(array_filter($pilihan));
        }

        if ($id) {
            // Update
            $result = $this->pertanyaan_model->update($id, $data);
            $message = 'Pertanyaan berhasil diupdate.';
        } else {
            // Insert
            $result = $this->pertanyaan_model->insert($data);
            $message = 'Pertanyaan berhasil ditambahkan.';
        }

        if ($result) {
            $this->session->set_flashdata('success', $message);
            redirect('admin/pertanyaan');
        } else {
            $this->session->set_flashdata('error', 'Gagal menyimpan pertanyaan.');
        }
    }

    /**
     * Delete pertanyaan
     */
    public function delete($id) {
        $pertanyaan = $this->pertanyaan_model->get_by_id($id);

        if (!$pertanyaan) {
            show_404();
        }

        if ($this->pertanyaan_model->delete($id)) {
            $this->session->set_flashdata('success', 'Pertanyaan berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus pertanyaan.');
        }

        redirect('admin/pertanyaan');
    }

    /**
     * Import pertanyaan dari Excel
     */
    public function import() {
        $data['title'] = 'Import Pertanyaan dari Excel';

        if ($this->input->post()) {
            $this->_do_import();
        }

        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/pertanyaan/import', $data);
        $this->load->view('templates/admin_footer');
    }

    /**
     * Process import Excel
     */
    private function _do_import() {
        // Upload file
        $config['upload_path']   = './uploads/excel/';
        $config['allowed_types'] = 'xls|xlsx';
        $config['max_size']      = 5120; // 5MB
        $config['file_name']     = 'import_' . time();

        $this->upload->initialize($config);

        if (!$this->upload->do_upload('file_excel')) {
            $this->session->set_flashdata('error', $this->upload->display_errors());
            return false;
        }

        $upload_data = $this->upload->data();
        $file_path = $upload_data['full_path'];

        // Load PHPExcel library
        require_once APPPATH . 'libraries/PHPExcel/PHPExcel.php';

        try {
            $excel = PHPExcel_IOFactory::load($file_path);
            $worksheet = $excel->getActiveSheet();
            $rows = $worksheet->toArray();

            $imported = 0;
            $failed = 0;
            $errors = array();

            // Skip header row
            for ($i = 1; $i < count($rows); $i++) {
                $row = $rows[$i];

                // Skip empty rows
                if (empty($row[0]) && empty($row[1])) {
                    continue;
                }

                // Get or create kategori
                $kategori_nama = trim($row[0]);
                $kategori = $this->kategori_model->get_by_name($kategori_nama);

                if (!$kategori) {
                    // Create new kategori
                    $kategori_id = $this->kategori_model->insert(array(
                        'nama_kategori' => $kategori_nama,
                        'urutan' => 0,
                        'is_active' => 1
                    ));
                } else {
                    $kategori_id = $kategori->id;
                }

                // Prepare data
                $data = array(
                    'kategori_id'   => $kategori_id,
                    'pertanyaan'    => trim($row[1]),
                    'tipe_jawaban'  => trim($row[2]) ?: 'skala',
                    'is_wajib'      => isset($row[3]) && $row[3] == 1 ? 1 : 0,
                    'urutan'        => isset($row[4]) ? (int)$row[4] : $i,
                    'is_active'     => 1
                );

                // Validate
                if (empty($data['pertanyaan'])) {
                    $errors[] = "Baris " . ($i + 1) . ": Pertanyaan tidak boleh kosong";
                    $failed++;
                    continue;
                }

                // Insert
                if ($this->pertanyaan_model->insert($data)) {
                    $imported++;
                } else {
                    $errors[] = "Baris " . ($i + 1) . ": Gagal menyimpan ke database";
                    $failed++;
                }
            }

            // Delete uploaded file
            @unlink($file_path);

            // Save import log
            $this->db->insert('import_log', array(
                'filename'      => $upload_data['file_name'],
                'type'          => 'pertanyaan',
                'total_rows'    => count($rows) - 1,
                'success_rows'  => $imported,
                'failed_rows'   => $failed,
                'error_log'     => json_encode($errors),
                'imported_by'   => $this->session->userdata('user_id')
            ));

            // Flash message
            if ($imported > 0) {
                $msg = "Berhasil import {$imported} pertanyaan.";
                if ($failed > 0) {
                    $msg .= " {$failed} gagal.";
                }
                $this->session->set_flashdata('success', $msg);
                $this->session->set_flashdata('import_errors', $errors);
            } else {
                $this->session->set_flashdata('error', 'Tidak ada pertanyaan yang berhasil diimport.');
            }

            redirect('admin/pertanyaan');

        } catch (Exception $e) {
            @unlink($file_path);
            $this->session->set_flashdata('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Export pertanyaan ke Excel
     */
    public function export() {
        // Load PHPExcel
        require_once APPPATH . 'libraries/PHPExcel/PHPExcel.php';

        $excel = new PHPExcel();
        $excel->setActiveSheetIndex(0);
        $sheet = $excel->getActiveSheet();

        // Set header
        $sheet->setCellValue('A1', 'Kategori');
        $sheet->setCellValue('B1', 'Pertanyaan');
        $sheet->setCellValue('C1', 'Tipe Jawaban');
        $sheet->setCellValue('D1', 'Wajib (1/0)');
        $sheet->setCellValue('E1', 'Urutan');
        $sheet->setCellValue('F1', 'Status (1/0)');

        // Style header
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
        $sheet->getStyle('A1:F1')->getFill()
              ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
              ->getStartColor()->setRGB('4CAF50');

        // Get data
        $pertanyaan = $this->pertanyaan_model->get_all_with_kategori();

        $row = 2;
        foreach ($pertanyaan as $p) {
            $sheet->setCellValue('A' . $row, $p->nama_kategori);
            $sheet->setCellValue('B' . $row, $p->pertanyaan);
            $sheet->setCellValue('C' . $row, $p->tipe_jawaban);
            $sheet->setCellValue('D' . $row, $p->is_wajib);
            $sheet->setCellValue('E' . $row, $p->urutan);
            $sheet->setCellValue('F' . $row, $p->is_active);
            $row++;
        }

        // Auto size columns
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Output
        $filename = 'pertanyaan_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $writer->save('php://output');
    }

    /**
     * Download template Excel
     */
    public function download_template() {
        require_once APPPATH . 'libraries/PHPExcel/PHPExcel.php';

        $excel = new PHPExcel();
        $excel->setActiveSheetIndex(0);
        $sheet = $excel->getActiveSheet();

        // Set header
        $sheet->setCellValue('A1', 'Kategori');
        $sheet->setCellValue('B1', 'Pertanyaan');
        $sheet->setCellValue('C1', 'Tipe Jawaban');
        $sheet->setCellValue('D1', 'Wajib (1/0)');
        $sheet->setCellValue('E1', 'Urutan');

        // Add example data
        $sheet->setCellValue('A2', 'Pendaftaran');
        $sheet->setCellValue('B2', 'Bagaimana kepuasan Anda terhadap kecepatan pendaftaran?');
        $sheet->setCellValue('C2', 'skala');
        $sheet->setCellValue('D2', '1');
        $sheet->setCellValue('E2', '1');

        $sheet->setCellValue('A3', 'Pelayanan Medis');
        $sheet->setCellValue('B3', 'Apakah dokter menjelaskan kondisi kesehatan dengan jelas?');
        $sheet->setCellValue('C3', 'skala');
        $sheet->setCellValue('D3', '1');
        $sheet->setCellValue('E3', '2');

        // Style header
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);
        $sheet->getStyle('A1:E1')->getFill()
              ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
              ->getStartColor()->setRGB('4CAF50');

        // Auto size
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Output
        $filename = 'template_import_pertanyaan.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $writer->save('php://output');
    }
}
