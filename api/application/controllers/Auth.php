<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class auth extends CI_Controller {
  public function __construct() {
    parent::__construct();
    $this->load->library('global_lib');
  }

  public function index()
  {
    $require = array('username', 'password', 'reg_id');
    $this->global_lib->input($require);

    $where['username'] = $this->input->post('username');
    $where['type'] = 2;

    $getUser = $this->Db_select->select_where('user', $where);

    if ($getUser) {
      $password = md5($this->input->post('password'));
      if ($getUser->password === $password) {
        if ($getUser->status == 1) {
          $whereCompany['id'] = $getUser->id_company;
          $company = $this->Db_select->select_where('company', $whereCompany);
          $token = md5(time());
  
          $where['id'] = $getUser->id;
          $update['token'] = $token;
          $update['reg_id'] = $this->input->post('reg_id');
          $update['is_online'] = 1;
          $update['in_game'] = 0;
          $this->Db_dml->update('user', $update, $where);
  
          $data = array(
            'id' => $getUser->id,
            'username' => $getUser->username,
            'name' => $getUser->name,
            'email' => $getUser->email,
            'token' => $token,
            'reg_id' => $this->input->post('reg_id'),
            'is_online' => (int)$getUser->is_online,
            'in_game' => (int)$getUser->in_game,
            'company' => $company->name,
            'id_company' => $company->id,
          );
  
          $result['status'] = true;
          $result['message'] = 'Success';
          $result['data'] = $data;
        } else {
          $result['status'] = false;
          $result['message'] = 'Your account has been deactivated';
          $result['data'] = null;
        }
      } else {
        $result['status'] = false;
        $result['message'] = 'The password you entered is incorrect';
        $result['data'] = null;
      }
    } else {
      $result['status'] = false;
      $result['message'] = 'Data user not found';
      $result['data'] = null;
    }
    echo json_encode($result);
  }

  public function registration()
  {
    $require = array('username', 'password', 'name', 'email', 'id_company', 'reg_id');
    $this->global_lib->input($require);

    $insert['username'] = $this->input->post('username');
    $insert['name'] = $this->input->post('name');
    $insert['email'] = $this->input->post('email');
    $insert['id_company'] = $this->input->post('id_company');
    $insert['password'] = md5($this->input->post('password'));
    $insert['reg_id'] = $this->input->post('reg_id');
    $insert['type'] = 2;
    $insert['status'] = 1;
    $insert['token'] = md5(time());
    $insert['is_online'] = 1;

    $insertData = $this->Db_dml->insert('user', $insert);

    if ($insertData) {
      $where['id'] = $insertData;
      $whereCompany['id'] = $this->input->post('id_company');
      $getData = $this->Db_select->select_where('user', $where);
      $company = $this->Db_select->select_where('company', $whereCompany);

      $data = array(
        'username' => $getData->username,
        'name' => $getData->name,
        'email' => $getData->email,
        'token' => $getData->token,
        'reg_id' => $getData->reg_id,
        'is_online' => (int)$getData->is_online,
        'in_game' => (int)$getData->in_game,
        'id_company' => $company->id,
        'company' => $company->name,
      );

      $result['status'] = true;
      $result['message'] = 'Success';
      $result['data'] = $data;
    } else {
      $result['status'] = true;
      $result['message'] = 'Data user not found';
      $result['data'] = null;
    }

    echo json_encode($result);
  }

  public function logout()
  {
    $require = array('user_id');
    $this->global_lib->input($require);

    $where['id'] = $this->input->post('user_id');
    $update['is_online'] = 0;

    $updateData = $this->Db_dml->update('user', $update, $where);

    if ($updateData) {
      $result['status'] = true;
      $result['message'] = 'Success';
      $result['data'] = null;
    } else {
      $result['status'] = true;
      $result['message'] = 'Failed';
      $result['data'] = null;
    }

    echo json_encode($result);
  }
}