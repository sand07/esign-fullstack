<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori_model extends CI_Model {

    private $table = 'kategori_pertanyaan';

    public function get_all() {
        return $this->db->order_by('urutan', 'ASC')->get($this->table)->result();
    }

    public function get_all_active() {
        return $this->db->where('is_active', 1)->order_by('urutan', 'ASC')->get($this->table)->result();
    }

    public function get_by_id($id) {
        return $this->db->get_where($this->table, array('id' => $id))->row();
    }

    public function get_by_name($nama) {
        return $this->db->get_where($this->table, array('nama_kategori' => $nama))->row();
    }

    public function get_all_with_pertanyaan() {
        $this->db->select('k.*, COUNT(p.id) as jumlah_pertanyaan');
        $this->db->from($this->table . ' k');
        $this->db->join('pertanyaan p', 'k.id = p.kategori_id AND p.is_active = 1', 'left');
        $this->db->where('k.is_active', 1);
        $this->db->group_by('k.id');
        $this->db->order_by('k.urutan', 'ASC');
        
        $kategori = $this->db->get()->result();
        
        // Get pertanyaan untuk setiap kategori
        foreach ($kategori as $k) {
            $k->pertanyaan = $this->db->where('kategori_id', $k->id)
                                      ->where('is_active', 1)
                                      ->order_by('urutan', 'ASC')
                                      ->get('pertanyaan')->result();
        }
        
        return $kategori;
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

    public function count_all() {
        return $this->db->count_all($this->table);
    }
}
