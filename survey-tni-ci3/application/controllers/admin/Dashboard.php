<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Dashboard Controller
 * Tampilkan statistik dan grafik
 */
class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('survey_model');
        $this->load->model('pertanyaan_model');

        // Check if logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('admin/login');
        }
    }

    /**
     * Dashboard index
     */
    public function index() {
        $data['title'] = 'Dashboard';

        // Get statistik
        $data['total_survey_hari_ini'] = $this->survey_model->count_survey_today();
        $data['total_survey_bulan_ini'] = $this->survey_model->count_survey_this_month();
        $data['total_responden'] = $this->survey_model->count_total_responden();
        $data['rata_rata_kepuasan'] = $this->survey_model->get_average_satisfaction();

        // Kategori kepuasan
        $data['kategori_kepuasan'] = $this->survey_model->get_satisfaction_distribution();

        // Tren kepuasan 7 hari terakhir
        $data['tren_7hari'] = $this->survey_model->get_satisfaction_trend(7);

        // Top 5 pertanyaan dengan nilai tertinggi
        $data['top_questions'] = $this->survey_model->get_top_questions(5, 'DESC');

        // Bottom 5 pertanyaan dengan nilai terendah
        $data['bottom_questions'] = $this->survey_model->get_top_questions(5, 'ASC');

        // Survey per poli
        $data['survey_per_poli'] = $this->survey_model->get_survey_by_poli();

        // Load views
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/dashboard', $data);
        $this->load->view('templates/admin_footer');
    }

    /**
     * Get chart data via AJAX
     */
    public function chart_data() {
        $type = $this->input->get('type');

        $result = array();

        switch ($type) {
            case 'tren':
                $days = $this->input->get('days', 7);
                $result = $this->survey_model->get_satisfaction_trend($days);
                break;

            case 'kategori':
                $result = $this->survey_model->get_satisfaction_distribution();
                break;

            case 'poli':
                $result = $this->survey_model->get_survey_by_poli();
                break;

            default:
                $result = array('error' => 'Invalid type');
        }

        echo json_encode($result);
    }
}
