<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->library('global_lib');
    $this->datauser = $this->global_lib->userCheck();
    if ($this->datauser == false) {
      $result['status'] = false;
      $result['message'] = 'access denied (authorization)';
      $result['data'] = null;

      echo json_encode($result); exit();
    }
  }

  public function list()
  {
    $require = array('user_id', 'token');
    $this->global_lib->input($require);

    $getData = $this->Db_select->query_all('select a.id as id_user, a.email, a.username, a.name as name_user, a.email, a.is_online, a.in_game, b.id as id_company, b.name as company_name from user a join company b on a.id_company = b.id where a.status = 1 and a.id != '.$this->input->post('user_id').' order by a.is_online desc');

    if ($getData) {
      $result['status'] = true;
      $result['message'] = 'Success';
      $result['data'] = $getData;
    } else {
      $result['status'] = true;
      $result['message'] = 'User data not found';
      $result['data'] = null;
    }

    echo json_encode($result);
  }

  public function idle()
  {
    $require = array('user_id', 'token');
    $this->global_lib->input($require);

    $getData = $this->Db_select->query_all('select a.id as id_user, a.email, a.username, a.name as name_user, a.email, a.is_online, a.in_game, b.id as id_company, b.name as company_name from user a join company b on a.id_company = b.id where a.status = 1 and a.is_online = 1 and a.id != '.$this->input->post('user_id').' order by a.is_online desc');

    if ($getData) {
      $result['status'] = true;
      $result['message'] = 'Success';
      $result['data'] = $getData;
    } else {
      $result['status'] = true;
      $result['message'] = 'User data not found';
      $result['data'] = null;
    }

    echo json_encode($result);
  }

  public function in_game()
  {
    $require = array('user_id', 'token');
    $this->global_lib->input($require);

    $getData = $this->Db_select->query_all('select a.id as id_user, a.email, a.username, a.name as name_user, a.email, a.is_online, a.in_game, b.id as id_company, b.name as company_name from user a join company b on a.id_company = b.id where a.status = 1 and a.in_game = 1 and a.id != '.$this->input->post('user_id').' order by a.is_online desc');

    if ($getData) {
      $result['status'] = true;
      $result['message'] = 'Success';
      $result['data'] = $getData;
    } else {
      $result['status'] = true;
      $result['message'] = 'User data not found';
      $result['data'] = null;
    }

    echo json_encode($result);
  }

  public function profile()
  {
    $require = array('user_id', 'token');
    $this->global_lib->input($require);

    $getUser = $this->Db_select->query('select a.*, b.id id_company, b.name company_name from user a join company b on a.id_company = b.id where a.id = '.$this->input->post('user_id'));

    if ($getUser) {
      $result['status'] = true;
      $result['message'] = 'Success';
      $result['data'] = $getUser;
    } else {
      $result['status'] = true;
      $result['message'] = 'User data not found';
      $result['data'] = null;
    }

    echo json_encode($result);
  }
}
