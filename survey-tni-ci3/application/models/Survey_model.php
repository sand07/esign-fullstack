<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Survey_model extends CI_Model {

    public function insert_responden($data) {
        $this->db->insert('responden', $data);
        return $this->db->insert_id();
    }

    public function insert_survey($data) {
        $this->db->insert('survey', $data);
        return $this->db->insert_id();
    }

    public function insert_jawaban($data) {
        $this->db->insert('jawaban', $data);
        return $this->db->insert_id();
    }

    public function update_survey($survey_id, $data) {
        $this->db->where('id', $survey_id);
        return $this->db->update('survey', $data);
    }

    public function get_detail($survey_id) {
        $this->db->select('s.*, r.*');
        $this->db->from('survey s');
        $this->db->join('responden r', 's.responden_id = r.id');
        $this->db->where('s.id', $survey_id);
        return $this->db->get()->row();
    }

    public function get_jawaban_by_survey($survey_id) {
        $this->db->select('j.*, p.pertanyaan, p.tipe_jawaban, k.nama_kategori');
        $this->db->from('jawaban j');
        $this->db->join('pertanyaan p', 'j.pertanyaan_id = p.id');
        $this->db->join('kategori_pertanyaan k', 'p.kategori_id = k.id', 'left');
        $this->db->where('j.survey_id', $survey_id);
        $this->db->order_by('p.urutan', 'ASC');
        return $this->db->get()->result();
    }

    public function count_survey_today() {
        $this->db->where('DATE(tanggal_survey)', date('Y-m-d'));
        return $this->db->count_all_results('survey');
    }

    public function count_survey_this_month() {
        $this->db->where('YEAR(tanggal_survey)', date('Y'));
        $this->db->where('MONTH(tanggal_survey)', date('m'));
        return $this->db->count_all_results('survey');
    }

    public function count_total_responden() {
        return $this->db->count_all('responden');
    }

    public function get_average_satisfaction() {
        $this->db->select_avg('rata_rata');
        $result = $this->db->get('survey')->row();
        return $result ? round($result->rata_rata, 2) : 0;
    }

    public function get_satisfaction_distribution() {
        $this->db->select('kategori_kepuasan, COUNT(*) as jumlah');
        $this->db->group_by('kategori_kepuasan');
        return $this->db->get('survey')->result();
    }

    public function get_satisfaction_trend($days = 7) {
        $this->db->select('DATE(tanggal_survey) as tanggal, COUNT(*) as jumlah, AVG(rata_rata) as rata_rata');
        $this->db->where('tanggal_survey >=', date('Y-m-d', strtotime("-{$days} days")));
        $this->db->group_by('DATE(tanggal_survey)');
        $this->db->order_by('tanggal', 'ASC');
        return $this->db->get('survey')->result();
    }

    public function get_top_questions($limit = 5, $order = 'DESC') {
        $this->db->select('p.pertanyaan, AVG(CAST(j.jawaban_value AS DECIMAL(10,2))) as rata_rata, COUNT(j.id) as total');
        $this->db->from('pertanyaan p');
        $this->db->join('jawaban j', 'p.id = j.pertanyaan_id');
        $this->db->where('p.tipe_jawaban', 'skala');
        $this->db->group_by('p.id');
        $this->db->order_by('rata_rata', $order);
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    public function get_survey_by_poli() {
        $this->db->select('r.poli, COUNT(s.id) as jumlah, AVG(s.rata_rata) as rata_rata');
        $this->db->from('survey s');
        $this->db->join('responden r', 's.responden_id = r.id');
        $this->db->where('r.poli IS NOT NULL');
        $this->db->group_by('r.poli');
        $this->db->order_by('jumlah', 'DESC');
        return $this->db->get()->result();
    }
}
