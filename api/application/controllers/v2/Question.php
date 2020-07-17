<?php

class Question extends CI_Controller
{
  public function __construct()
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

  public function issue()
  {
    $require = array('user_id', 'token', 'id_invite');
    $this->global_lib->input($require);

    $getDataInvite = $this->Db_select->select_where('invite_log', 'id = '.$this->input->post('id_invite'));
    if ($getDataInvite) {
      $getIssue = $this->Db_select->select_all('question_list');

      foreach ($getIssue as $key => $value) {
        $value->type = $this->Db_select->query('select id, name from type_form where id = '.$value->type);
      }

      $result['status'] = true;
      $result['message'] = 'Success';
      $result['data'] = $getIssue;
    } else {
      $result['status'] = true;
      $result['message'] = 'Access denied';
      $result['data'] = null;
    }

    echo json_encode($result);
  }
}
