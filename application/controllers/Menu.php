<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
    }
    public function index()
    {
        $data['title'] = 'Menu Management';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['menu'] = $this->db->get('user_menu')->result_array();
        $this->load->view('templates/header.php', $data);
        $this->load->view('templates/sidebar.php');
        $this->load->view('templates/topbar.php', $data);
        $this->load->view('menu/index.php', $data);
        $this->load->view('templates/footer.php');
    }

    public function addMenu()
    {
        $this->form_validation->set_rules('menu', 'Menu', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Menu name must be filled!</div>');
            redirect('menu');
        } else {
            $menu = $this->input->post('menu',);
            $result = $this->db->get_where('user_menu', ['menu' => $menu])->row_array();
            if ($result) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Menu name is already exist!</div>');
                redirect('menu');
            } else {
                $this->db->insert('user_menu', ['menu' => ucfirst($menu)]);
                redirect('menu');
            }
        }
    }

    public function submenu()
    {
        $this->form_validation->set_rules('');

        $data['title'] = 'Submenu Management';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['menu'] = $this->db->get('user_menu')->result_array();
        $data['submenu'] = $this->Menu_model->getSubmenu();
        $this->load->view('templates/header.php', $data);
        $this->load->view('templates/sidebar.php');
        $this->load->view('templates/topbar.php', $data);
        $this->load->view('menu/submenu.php', $data);
        $this->load->view('templates/footer.php');
    }

    public function addSubmenu()
    {
        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('menu_id', 'Menu_id', 'required');
        $this->form_validation->set_rules('url', 'Url', 'required|trim');
        $this->form_validation->set_rules('icon', 'Icon', 'required');

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">All field must be filled!</div>');
            redirect('menu/submenu');
        } else {
            $data = [
                'title' => $this->input->post('title'),
                'menu_id' => $this->input->post('menu_id'),
                'url' => $this->input->post('url'),
                'icon' => $this->input->post('icon'),
                'is_active' => $this->input->post('is_active')
            ];
            $this->db->insert('user_sub_menu', $data);
            redirect('menu/submenu');
        }
    }
}
