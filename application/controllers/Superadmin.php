<?php

class Superadmin extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    if (!$this->session->userdata('superadmin')) {
      redirect(base_url('administrator'));
    }
  }

  public function index()
  {
    $this->load->view('superadmin/header');
    $this->load->view('superadmin/dashboard');
    $this->load->view('superadmin/footer');
  }

  public function admin()
  {
    $data['list'] = $this->Db_select->select_all_where('user', ['type' => 1]);
    $this->load->view('superadmin/header', $data);
    $this->load->view('superadmin/admin');
    $this->load->view('superadmin/footer');
  }

  public function adminEdit($id)
  {
    $where['id'] = $id;
    $update['username'] = $this->input->post('username');
    $update['role'] = $this->input->post('role');
    if ($this->input->post('password')) {
      $update['password'] = md5($this->input->post('password'));
    }

    $updateData = $this->Db_dml->update('user', $update, $where);
    redirect(base_url('superadmin/admin'));
  }
}
