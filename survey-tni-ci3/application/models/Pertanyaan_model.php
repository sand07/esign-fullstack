<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pertanyaan_model extends CI_Model {

    private $table = 'pertanyaan';

    public function get_all($limit = null, $offset = null, $filter = array()) {
        $this->db->select('p.*, k.nama_kategori');
        $this->db->from($this->table . ' p');
        $this->db->join('kategori_pertanyaan k', 'p.kategori_id = k.id', 'left');
        
        if (isset($filter['kategori_id'])) {
            $this->db->where('p.kategori_id', $filter['kategori_id']);
        }
        
        $this->db->order_by('p.urutan', 'ASC');
        
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result();
    }

    public function get_all_active_ordered() {
        $this->db->select('p.*, k.nama_kategori');
        $this->db->from($this->table . ' p');
        $this->db->join('kategori_pertanyaan k', 'p.kategori_id = k.id', 'left');
        $this->db->where('p.is_active', 1);
        $this->db->order_by('p.urutan', 'ASC');
        return $this->db->get()->result();
    }

    public function get_all_with_kategori() {
        $this->db->select('p.*, k.nama_kategori');
        $this->db->from($this->table . ' p');
        $this->db->join('kategori_pertanyaan k', 'p.kategori_id = k.id', 'left');
        $this->db->order_by('k.urutan', 'ASC');
        $this->db->order_by('p.urutan', 'ASC');
        return $this->db->get()->result();
    }

    public function get_by_id($id) {
        return $this->db->get_where($this->table, array('id' => $id))->row();
    }

    public function insert($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete($id) {
        return $this->db->delete($this->table, array('id' => $id));
    }

    public function count_all($filter = array()) {
        if (isset($filter['kategori_id'])) {
            $this->db->where('kategori_id', $filter['kategori_id']);
        }
        return $this->db->count_all_results($this->table);
    }
}
