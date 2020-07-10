<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Issue extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
  }

  public function index()
  {
    $list = $this->Db_select->select_all('issue');
    $data['company'] = $this->Db_select->select_all('company');

    foreach ($list as $key => $value) {
      $getWeight = $this->Db_select->query_all('select a.*, b.name from weight a join company b on a.id_company = b.id where a.id_issue = '.$value->id);
      $value->weight = $getWeight;
    }
    $data['list'] = $list;

    $this->load->view('header', $data);
    $this->load->view('issue');
    $this->load->view('footer');
  }

  public function insert()
  {
    $company = $this->Db_select->select_all('company');
    $insert['name'] = $this->input->post('name');
    
    $insertData = $this->Db_dml->insert('issue', $insert);
    if ($insertData) {
      foreach ($company as $key => $value) {
        $insertWeight['id_company'] = $value->id;
        $insertWeight['id_issue'] = $insertData;
        $insertWeight['weight'] = $this->input->post('weight'.$value->id);

        $this->Db_dml->insert('weight', $insertWeight);
      }
    }
    redirect(base_url('issue'));
  }

  public function edit($id)
  {
    // echo json_encode($id); exit();
    $where['id'] = $id;
    $update['name'] = $this->input->post('name');
    $this->Db_dml->update('issue', $update, $where);
    
    $getWeight = $this->Db_select->query_all('select a.* from weight a join company b on a.id_company = b.id where a.id_issue = '.$id);
    foreach ($getWeight as $key => $value) {
      $whereWeight['id'] = $value->id;
      $updateWeight['weight'] = $this->input->post('weight'.$value->id);
      $this->Db_dml->update('weight', $updateWeight, $whereWeight);
    }

    redirect(base_url('issue'));
  }

  public function delete($id)
  {
    $where['id'] = $id;

    $this->Db_dml->delete('issue', $where);
    redirect(base_url('issue'));
  }

  public function options($id)
  {
    $where['id'] = $id;
    $where2['id_issue'] = $id;
    $data['data'] = $this->Db_select->select_where('issue', $where);
    $list = $this->Db_select->select_all_where('question', $where2);
    $data['company'] = $this->Db_select->select_all('company');

    foreach ($list as $key => $value) {
      $getPoint = $this->Db_select->query_all('select a.*, b.name from point a join company b on a.id_company = b.id where a.id_question = '.$value->id);
      $value->point = $getPoint;
    }
    $data['list'] = $list;

    $this->load->view('header', $data);
    $this->load->view('options');
    $this->load->view('footer');
  }

  public function insert_options($id)
  {
    $company = $this->Db_select->select_all('company');
    $insert['id_issue'] = $id;
    $insert['question'] = $this->input->post('question');
    $insertData = $this->Db_dml->insert('question', $insert);
    
    if ($insertData) {
      foreach ($company as $key => $value) {
        $insertPoint['id_company'] = $value->id;
        $insertPoint['id_question'] = $insertData;
        $insertPoint['point'] = $this->input->post('point'.$value->id);

        $this->Db_dml->insert('point', $insertPoint);
      }
    }
    redirect(base_url('issue/options/'.$id));
  }

  public function edit_options($id)
  {
    $where['id'] = $id;
    $data = $this->Db_select->select_where('question', $where);
    $update['question'] = $this->input->post('question');
    $this->Db_dml->update('question', $update, $where);

    $getPoint = $this->Db_select->query_all('select a.* from point a join company b on a.id_company = b.id where a.id_question = '.$id);
    foreach ($getPoint as $key => $value) {
      $wherePoint['id'] = $value->id;
      $updatePoint['point'] = $this->input->post('point'.$value->id);

      $this->Db_dml->update('point', $updatePoint, $wherePoint);
    }
    redirect(base_url('issue/options/'.$data->id_issue));
  }

  public function delete_options($id)
  {
    $where['id'] = $id;
    $data = $this->Db_select->select_where('question', $where);

    $this->Db_dml->delete('question', $where);
    redirect(base_url('issue/options/'.$data->id_issue));
  }
}
