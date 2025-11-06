<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Auth Controller
 * Handle login/logout admin
 */
class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->library('session');
    }

    /**
     * Login page
     */
    public function login() {
        // Jika sudah login, redirect ke dashboard
        if ($this->session->userdata('user_id')) {
            redirect('admin/dashboard');
        }

        // Handle form submit
        if ($this->input->post()) {
            $this->_do_login();
        }

        $data['title'] = 'Login Admin';
        $this->load->view('admin/login', $data);
    }

    /**
     * Process login
     */
    private function _do_login() {
        // Validation rules
        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            return false;
        }

        $username = $this->input->post('username', true);
        $password = $this->input->post('password');

        // Get user from database
        $user = $this->user_model->get_by_username($username);

        if ($user && password_verify($password, $user->password)) {
            // Check if active
            if ($user->is_active != 1) {
                $this->session->set_flashdata('error', 'Akun Anda tidak aktif. Hubungi administrator.');
                return false;
            }

            // Set session
            $session_data = array(
                'user_id'   => $user->id,
                'username'  => $user->username,
                'nama'      => $user->nama,
                'email'     => $user->email,
                'role'      => $user->role,
                'logged_in' => TRUE
            );

            $this->session->set_userdata($session_data);

            // Update last login
            $this->user_model->update_last_login($user->id);

            // Redirect to dashboard
            redirect('admin/dashboard');
        } else {
            $this->session->set_flashdata('error', 'Username atau password salah!');
        }
    }

    /**
     * Logout
     */
    public function logout() {
        // Destroy session
        $this->session->unset_userdata(array('user_id', 'username', 'nama', 'email', 'role', 'logged_in'));
        $this->session->set_flashdata('success', 'Anda berhasil logout.');
        redirect('admin/login');
    }
}
