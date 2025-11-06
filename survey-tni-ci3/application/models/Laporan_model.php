<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_model extends CI_Model {

    public function get_all($limit = null, $offset = null, $filter = array()) {
        $this->db->select('s.*, r.nama, r.pangkat, r.nrp, r.kesatuan, r.jenis_kelamin, r.umur, r.poli');
        $this->db->from('survey s');
        $this->db->join('responden r', 's.responden_id = r.id');
        
        $this->_apply_filter($filter);
        
        $this->db->order_by('s.tanggal_survey', 'DESC');
        
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result();
    }

    public function get_all_for_export($filter = array()) {
        $this->db->select('s.id as survey_id, s.tanggal_survey, s.total_nilai, s.rata_rata, s.kategori_kepuasan, s.saran, 
                          r.nama, r.pangkat, r.nrp, r.kesatuan, r.jenis_kelamin, r.umur, r.poli');
        $this->db->from('survey s');
        $this->db->join('responden r', 's.responden_id = r.id');
        
        $this->_apply_filter($filter);
        
        $this->db->order_by('s.tanggal_survey', 'DESC');
        
        return $this->db->get()->result();
    }

    public function count_all($filter = array()) {
        $this->db->from('survey s');
        $this->db->join('responden r', 's.responden_id = r.id');
        
        $this->_apply_filter($filter);
        
        return $this->db->count_all_results();
    }

    private function _apply_filter($filter) {
        if (isset($filter['tanggal_dari'])) {
            $this->db->where('DATE(s.tanggal_survey) >=', $filter['tanggal_dari']);
        }
        
        if (isset($filter['tanggal_sampai'])) {
            $this->db->where('DATE(s.tanggal_survey) <=', $filter['tanggal_sampai']);
        }
        
        if (isset($filter['poli']) && $filter['poli'] != '') {
            $this->db->where('r.poli', $filter['poli']);
        }
        
        if (isset($filter['kategori_kepuasan']) && $filter['kategori_kepuasan'] != '') {
            $this->db->where('s.kategori_kepuasan', $filter['kategori_kepuasan']);
        }
    }

    public function get_poli_list() {
        $this->db->select('poli');
        $this->db->distinct();
        $this->db->where('poli IS NOT NULL');
        $this->db->order_by('poli', 'ASC');
        return $this->db->get('responden')->result();
    }

    public function get_summary($filter = array()) {
        $this->db->select('COUNT(*) as total_survey, AVG(rata_rata) as rata_rata_keseluruhan');
        $this->db->from('survey s');
        $this->db->join('responden r', 's.responden_id = r.id');
        
        $this->_apply_filter($filter);
        
        return $this->db->get()->row();
    }

    public function get_statistik_pertanyaan($filter = array()) {
        $sql = "SELECT 
                    p.id as pertanyaan_id,
                    k.nama_kategori,
                    p.pertanyaan,
                    COUNT(j.id) as total_jawaban,
                    ROUND(AVG(CAST(j.jawaban_value AS DECIMAL(10,2))), 2) as rata_rata,
                    MIN(CAST(j.jawaban_value AS DECIMAL(10,2))) as nilai_min,
                    MAX(CAST(j.jawaban_value AS DECIMAL(10,2))) as nilai_max,
                    SUM(CASE WHEN j.jawaban_value = '5' THEN 1 ELSE 0 END) as jawaban_5,
                    SUM(CASE WHEN j.jawaban_value = '4' THEN 1 ELSE 0 END) as jawaban_4,
                    SUM(CASE WHEN j.jawaban_value = '3' THEN 1 ELSE 0 END) as jawaban_3,
                    SUM(CASE WHEN j.jawaban_value = '2' THEN 1 ELSE 0 END) as jawaban_2,
                    SUM(CASE WHEN j.jawaban_value = '1' THEN 1 ELSE 0 END) as jawaban_1
                FROM pertanyaan p
                LEFT JOIN kategori_pertanyaan k ON p.kategori_id = k.id
                LEFT JOIN jawaban j ON p.id = j.pertanyaan_id";
        
        if (!empty($filter)) {
            $sql .= " LEFT JOIN survey s ON j.survey_id = s.id";
            $where = array();
            
            if (isset($filter['tanggal_dari'])) {
                $where[] = "DATE(s.tanggal_survey) >= '{$filter['tanggal_dari']}'";
            }
            
            if (isset($filter['tanggal_sampai'])) {
                $where[] = "DATE(s.tanggal_survey) <= '{$filter['tanggal_sampai']}'";
            }
            
            if (!empty($where)) {
                $sql .= " WHERE " . implode(' AND ', $where);
            }
        }
        
        $sql .= " WHERE p.tipe_jawaban = 'skala' GROUP BY p.id ORDER BY k.urutan, p.urutan";
        
        return $this->db->query($sql)->result();
    }
}
