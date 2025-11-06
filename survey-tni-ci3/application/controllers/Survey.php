<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Survey Controller
 * Public form untuk pasien mengisi survey
 */
class Survey extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('pertanyaan_model');
        $this->load->model('kategori_model');
        $this->load->model('survey_model');
    }

    /**
     * Landing page survey
     */
    public function index() {
        $data['title'] = 'Survey Kepuasan Pasien TNI';

        $this->load->view('templates/public_header', $data);
        $this->load->view('survey/index', $data);
        $this->load->view('templates/public_footer');
    }

    /**
     * Form survey
     */
    public function mulai() {
        $data['title'] = 'Form Survey Kepuasan';

        // Get pertanyaan dikelompokkan per kategori
        $data['kategori'] = $this->kategori_model->get_all_with_pertanyaan();

        $this->load->view('templates/public_header', $data);
        $this->load->view('survey/form', $data);
        $this->load->view('templates/public_footer');
    }

    /**
     * Proses submit survey
     */
    public function proses() {
        if (!$this->input->post()) {
            redirect('survey');
        }

        // Validation rules
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('pangkat', 'Pangkat', 'required');
        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required');
        $this->form_validation->set_rules('umur', 'Umur', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('survey/mulai');
        }

        // Begin transaction
        $this->db->trans_start();

        try {
            // 1. Insert responden
            $responden_data = array(
                'no_registrasi' => $this->input->post('no_registrasi'),
                'nama' => $this->input->post('nama'),
                'pangkat' => $this->input->post('pangkat'),
                'nrp' => $this->input->post('nrp'),
                'kesatuan' => $this->input->post('kesatuan'),
                'jenis_kelamin' => $this->input->post('jenis_kelamin'),
                'umur' => $this->input->post('umur'),
                'no_hp' => $this->input->post('no_hp'),
                'tanggal_berobat' => $this->input->post('tanggal_berobat'),
                'poli' => $this->input->post('poli'),
            );

            $responden_id = $this->survey_model->insert_responden($responden_data);

            // 2. Insert survey header
            $survey_data = array(
                'responden_id' => $responden_id,
                'tanggal_survey' => date('Y-m-d H:i:s'),
                'ip_address' => $this->input->ip_address(),
                'user_agent' => $this->input->user_agent(),
                'status' => 'selesai',
                'saran' => $this->input->post('saran')
            );

            $survey_id = $this->survey_model->insert_survey($survey_data);

            // 3. Insert jawaban & hitung nilai
            $jawaban = $this->input->post('jawaban');
            $total_nilai = 0;
            $jumlah_pertanyaan = 0;

            if ($jawaban && is_array($jawaban)) {
                foreach ($jawaban as $pertanyaan_id => $nilai) {
                    $jawaban_data = array(
                        'survey_id' => $survey_id,
                        'pertanyaan_id' => $pertanyaan_id,
                        'jawaban_value' => $nilai,
                        'jawaban_text' => $this->input->post('jawaban_text_' . $pertanyaan_id)
                    );

                    $this->survey_model->insert_jawaban($jawaban_data);

                    // Hitung total (hanya untuk skala)
                    if (is_numeric($nilai)) {
                        $total_nilai += (float)$nilai;
                        $jumlah_pertanyaan++;
                    }
                }
            }

            // 4. Update survey dengan hasil perhitungan
            $rata_rata = $jumlah_pertanyaan > 0 ? $total_nilai / $jumlah_pertanyaan : 0;

            // Tentukan kategori kepuasan
            if ($rata_rata >= 4.5) {
                $kategori = 'Sangat Puas';
            } elseif ($rata_rata >= 3.5) {
                $kategori = 'Puas';
            } elseif ($rata_rata >= 2.5) {
                $kategori = 'Cukup';
            } else {
                $kategori = 'Kurang Puas';
            }

            $update_data = array(
                'total_nilai' => $total_nilai,
                'rata_rata' => $rata_rata,
                'kategori_kepuasan' => $kategori
            );

            $this->survey_model->update_survey($survey_id, $update_data);

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Transaction failed');
            }

            // Set session untuk halaman terima kasih
            $this->session->set_flashdata('survey_result', array(
                'nama' => $responden_data['nama'],
                'rata_rata' => $rata_rata,
                'kategori' => $kategori,
                'total_pertanyaan' => $jumlah_pertanyaan
            ));

            redirect('survey/terima-kasih');

        } catch (Exception $e) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Terjadi kesalahan saat menyimpan survey. Silakan coba lagi.');
            redirect('survey/mulai');
        }
    }

    /**
     * Halaman terima kasih
     */
    public function terima_kasih() {
        $data['title'] = 'Terima Kasih';
        $data['result'] = $this->session->flashdata('survey_result');

        if (!$data['result']) {
            redirect('survey');
        }

        $this->load->view('templates/public_header', $data);
        $this->load->view('survey/terima_kasih', $data);
        $this->load->view('templates/public_footer');
    }
}
