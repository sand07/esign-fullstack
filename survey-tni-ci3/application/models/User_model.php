<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    private $table = 'users';

    public function get_by_username($username) {
        return $this->db->get_where($this->table, array('username' => $username))->row();
    }

    public function get_by_id($id) {
        return $this->db->get_where($this->table, array('id' => $id))->row();
    }

    public function get_all($limit = null, $offset = null) {
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        return $this->db->order_by('created_at', 'DESC')->get($this->table)->result();
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

    public function update_last_login($id) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, array('updated_at' => date('Y-m-d H:i:s')));
    }

    public function count_all() {
        return $this->db->count_all($this->table);
    }
}
