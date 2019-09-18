<?php
defined('BASEPATH') or exit('No direct script access allowed');

class authModel extends CI_Model
{
    public function signin($email)
    {
        return $this->db->get_where('user', ['email' => $email])->row_array();
    }

    public function signup($data)
    {
        $this->db->insert('user', $data);
    }
}
