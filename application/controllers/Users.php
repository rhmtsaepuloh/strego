<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller
{
  public function __construct( ){
    parent::__construct();
    if (!$this->session->userdata('user')) {
      redirect(base_url(''));
    }
  }

  public function index()
  {
    $where['type'] = 2;
    $data['list'] = $this->Db_select->query_all('select a.*, b.name name_company from user a join company b on a.id_company = b.id where a.type = 2');

    $this->load->view('header', $data);
    $this->load->view('users');
    $this->load->view('footer');
  }

  public function edit($id)
  {
    $where['id'] = $id;
    $update['status'] = $this->input->post('status');

    $this->Db_dml->update('user', $update, $where);
    redirect(base_url('users'));
  }
}