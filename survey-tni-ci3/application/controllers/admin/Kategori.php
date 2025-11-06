<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('kategori_model');

        if (!$this->session->userdata('logged_in')) {
            redirect('admin/login');
        }
    }

    public function index() {
        $data['title'] = 'Manajemen Kategori';
        $data['kategori'] = $this->kategori_model->get_all();

        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/kategori/index', $data);
        $this->load->view('templates/admin_footer');
    }

    public function add() {
        $data['title'] = 'Tambah Kategori';
        $data['action'] = 'add';

        if ($this->input->post()) {
            $this->_save();
        }

        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/kategori/form', $data);
        $this->load->view('templates/admin_footer');
    }

    public function edit($id) {
        $data['title'] = 'Edit Kategori';
        $data['kategori'] = $this->kategori_model->get_by_id($id);
        $data['action'] = 'edit';

        if (!$data['kategori']) {
            show_404();
        }

        if ($this->input->post()) {
            $this->_save($id);
        }

        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/kategori/form', $data);
        $this->load->view('templates/admin_footer');
    }

    private function _save($id = null) {
        $this->form_validation->set_rules('nama_kategori', 'Nama Kategori', 'required');
        $this->form_validation->set_rules('urutan', 'Urutan', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            return false;
        }

        $data = array(
            'nama_kategori' => $this->input->post('nama_kategori'),
            'deskripsi' => $this->input->post('deskripsi'),
            'urutan' => $this->input->post('urutan'),
            'is_active' => $this->input->post('is_active') ? 1 : 0,
        );

        if ($id) {
            $result = $this->kategori_model->update($id, $data);
            $message = 'Kategori berhasil diupdate.';
        } else {
            $result = $this->kategori_model->insert($data);
            $message = 'Kategori berhasil ditambahkan.';
        }

        if ($result) {
            $this->session->set_flashdata('success', $message);
            redirect('admin/kategori');
        } else {
            $this->session->set_flashdata('error', 'Gagal menyimpan kategori.');
        }
    }

    public function delete($id) {
        $kategori = $this->kategori_model->get_by_id($id);

        if (!$kategori) {
            show_404();
        }

        if ($this->kategori_model->delete($id)) {
            $this->session->set_flashdata('success', 'Kategori berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus kategori.');
        }

        redirect('admin/kategori');
    }
}
