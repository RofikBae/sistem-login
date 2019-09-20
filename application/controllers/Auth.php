<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('authModel');
    }
    public function index()
    {
        if ($this->session->userdata('email')) {
            redirect('member');
        }
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|trim');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Sign in';
            $this->load->view('templates/auth-header.php', $data);
            $this->load->view('auth/signin.php');
            $this->load->view('templates/auth-footer.php');
        } else {
            $this->_signin();
        }
    }

    private function _signin()
    {

        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $user = $this->authModel->signin($email);

        if ($user) {
            if ($user['is_active'] == 1) {
                if (password_verify($password, $user['password'])) {
                    $data = [
                        'email' => $user['email'],
                        'role_id' => $user['role_id']
                    ];
                    $this->session->set_userdata($data);

                    if ($user['role_id'] == 1) {
                        redirect('admin');
                    } elseif ($user['role_id'] == 2) {
                        redirect('member');
                    } else {
                        # code...
                    }
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Wrong password!</div>');
                    redirect('auth');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">User not active!</div>');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Email not registered!</div>');
            redirect('auth');
        }
    }

    public function signup()
    {
        if ($this->session->userdata('email')) {
            redirect('member');
        }
        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]', [
            'is_unique' => 'This email has already registered!'
        ]);
        $this->form_validation->set_rules('inputPassword', 'password', 'required|trim|matches[repeatPassword]|min_length[3]');
        $this->form_validation->set_rules('repeatPassword', 'password', 'required|trim|matches[inputPassword]');


        if ($this->form_validation->run() == false) {
            $data['title'] = 'Sign up';
            $this->load->view('templates/auth-header.php', $data);
            $this->load->view('auth/signup.php');
            $this->load->view('templates/auth-footer.php');
        } else {
            // $email = $this->input->post('email', true);
            $data = [
                'name' => htmlspecialchars($this->input->post('name', true)),
                'email' => htmlspecialchars($this->input->post('email', true)),
                'image' => 'default.jpg',
                'password' => password_hash($this->input->post('inputPassword'), PASSWORD_DEFAULT),
                'role_id' => 2,
                'is_active' => 1,
                'date_created' => time()
            ];
            $this->authModel->signup($data);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Registration success. Sign in please!</div>');
            redirect('auth');
        }
    }

    public function signout()
    {
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('role_id');
        redirect('auth');
    }

    public function blocked()
    {
        $data['title'] = 'ERROR 404!';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $this->load->view('templates/header.php', $data);
        $this->load->view('templates/sidebar.php', $data);
        $this->load->view('templates/topbar.php', $data);
        $this->load->view('auth/blocked.php');
        $this->load->view('templates/footer.php');
    }
}
