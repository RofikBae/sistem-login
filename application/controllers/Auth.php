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
            $email = $this->input->post('email', true);
            $data = [
                'name' => htmlspecialchars($this->input->post('name', true)),
                'email' => htmlspecialchars($email),
                'image' => 'default.jpg',
                'password' => password_hash($this->input->post('inputPassword'), PASSWORD_DEFAULT),
                'role_id' => 2,
                'is_active' => 0,
                'date_created' => time()
            ];

            $token = base64_encode(random_bytes(32));
            $user_token = [
                'email' => $email,
                'token' => $token,
                'date_created' => time()
            ];

            $this->authModel->signup($data);
            $this->db->insert('user_token', $user_token);

            $this->_sendEmail($token, 'verify');
        }
    }

    private function _sendEmail($token, $type)
    {
        $email = $this->input->post('email');
        $config = [
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_port' => 465,
            'smtp_user' => 'rofikmail98@gmail.com',
            'smtp_pass' => 'taHajud3',
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'wordwrap' => TRUE,
            'newline' => "\r\n"

        ];
        $this->load->library('email');
        $this->email->initialize($config);

        if ($type == 'verify') {
            $this->email->from('rofikmail98@gmail.com', 'MyApp');
            $this->email->to($email);
            $this->email->subject('Reset Password');
            $message = '<!DOCTYPE html><html lang="en"><head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <meta http-equiv="X-UA-Compatible" content="ie=edge">
            </head>
            <body>';
            $message .= '<p>Thanks for signing up!<?p>';
            $message .= '<p>To get started, click the link below to confirm your account.<?p>';
            $message .= '<p><strong><a href="' . base_url() . '/auth/verify?email=' . $email . '&token=' . urlencode($token) . '">Confirm your account</a></strong><?p>';
            $message .= '</body></html>';
            $this->email->message($message);
        } elseif ($type == 'forgot') {
            $this->email->from('rofikmail98@gmail.com', 'MyApp');
            $this->email->to($email);
            $this->email->subject('User Activation');
            $message = '<!DOCTYPE html><html lang="en"><head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <meta http-equiv="X-UA-Compatible" content="ie=edge">
            </head>
            <body><center>';
            $message .= '<p>Click the link below to reset your password.<?p>';
            $message .= '<p><strong><a href="' . base_url() . '/auth/resetpassword?email=' . $email . '&token=' . urlencode($token) . '">Reset password</a></strong><?p>';
            $message .= '<?center></body></html>';
            $this->email->message($message);

            if ($this->email->send()) {
                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Check your email to reset your password</div>');
                redirect('auth/forgotpassword');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Make sure the email that you use is active!</div>');
                redirect('auth/forgotpassword');
            }
        } else {
            # code...
        }
    }


    public function verify()
    {
        $email = $this->input->get('email');
        $token = $this->input->get('token');

        $user = $this->db->get('user', ['email' => $email])->row_array();
        if ($user) {
            $user_token = $this->db->get('user_token', ['token' => $token])->row_array();
            if ($user_token) {
                if (time() - $user_token['date_created'] < (60 * 60 * 24)) {
                    $this->db->delete('user_token', ['email' => $email]);
                    $this->db->set('is_active', 1);
                    $this->db->where('email', $email);
                    $this->db->update('user');
                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Your email has been successfully activated. Login please!</div>');
                    redirect('auth');
                } else {
                    $this->db->delete('user', ['email' => $email]);
                    $this->db->delete('user_token', ['email' => $email]);
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Activation failed. Your activation code expired!</div>');
                    redirect('auth');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Activation failed. Wrong activation code!</div>');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Your email is not registered. Register your email!</div>');
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

    public function forgotPassword()
    {
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Forgot password';
            $this->load->view('templates/auth-header.php', $data);
            $this->load->view('auth/forgot.php');
            $this->load->view('templates/auth-footer.php');
        } else {
            $email = $this->input->post('email');
            $user = $this->db->get_where('user', ['email' => $email])->row_array();
            if ($user) {
                if ($user['is_active'] == 0) {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Your email is not activate yet. Activate your email!</div>');
                    redirect('auth/forgotpassword');
                } else {
                    $token = base64_encode(random_bytes(32));
                    $user_token = [
                        'email' => $email,
                        'token' => $token,
                        'date_created' => time()
                    ];

                    $this->db->insert('user_token', $user_token);
                    $this->_sendEmail($token, 'forgot');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Your email is not registered. Register your email!</div>');
                redirect('auth/forgotpassword');
            }
        }
    }

    public function resetPassword()
    {
        $token = $this->input->get('token');
        $email = $this->input->get('email');
        $user = $this->db->get_where('user', ['email' => $email]);

        if ($user) {
            $user_token = $this->db->get_where('user_token', ['token' => $token]);

            if ($user_token) {
                $this->session->set_userdata('email_reset', $email);
                $this->form_validation->set_rules('password', 'Password', 'required|trim|matches[repeatPassword]|min_length[3]');
                $this->form_validation->set_rules('repeatPassword', 'Repeat Password', 'required|trim|matches[password]');

                if ($this->form_validation->run() == false) {
                    $data['title'] = 'Reset Password';
                    $this->load->view('templates/auth-header.php', $data);
                    $this->load->view('auth/reset.php');
                    $this->load->view('templates/auth-footer.php');
                } else {
                    $password = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
                    $this->db->set('password', $password);
                    $this->db->where('email', $this->session->userdata('email_reset'));
                    $this->db->update('user');
                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Your password has been reseted. Please login!</div>');
                    redirect('auth');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Invalid token!</div>');
                redirect('auth/forgotpassword');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Invalid email. Register your email!</div>');
            redirect('auth/forgotpassword');
        }
    }
}
