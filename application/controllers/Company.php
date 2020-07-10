<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Company extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
  }

  public function index()
  {
    $where['status'] = 1;
    $data['list'] = $this->Db_select->select_all_where('company', $where);

    $this->load->view('header', $data);
    $this->load->view('company');
    $this->load->view('footer');
  }

  public function insert()
  {
    $insert['name'] = $this->input->post('name');
    $insert['status'] = 1;

    $this->Db_dml->insert('company', $insert);
    redirect(base_url('company'));
  }

  public function edit($id)
  {
    $where['id'] = $id;
    $update['name'] = $this->input->post('name');

    $this->Db_dml->update('company', $update, $where);
    redirect(base_url('company'));
  }

  public function delete($id)
  {
    $where['id'] = $id;

    $this->Db_dml->delete('company', $where);
    redirect(base_url('company'));
  }
}
