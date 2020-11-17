<?php

class Import extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
  }

  public function index()
  {
    $input_data = json_decode(trim(file_get_contents('php://input')), true);

    $checkUser = $this->Db_select->select_where('user', ['nim' => $input_data['nim']]);
    if ($checkUser) {
      /* user sudah ada */
      $id = $checkUser->id;
      $checkData = $this->Db_select->select_where('communication_history', ['id_user' => $id]);
      if ($checkData) {
        $update['answer'] = json_encode($input_data['answer']);
        $this->Db_dml->update('communication_history', $update, ['id_user' => $id]);
      } else {
        $insert2['id_user'] = $id;
        $insert2['answer'] = json_encode($input_data['answer']);

        $this->Db_dml->insert('communication_history', $insert2);
      }
    } else {
      /* insert user */
      $insert['nim'] = $input_data['nim'];
      $insert['type'] = 2;
      $insert['role'] = 0;
      $insert['email'] = "manual@gmail.com";
      $insert['username'] = $input_data['name'];
      $insert['password'] = md5('12345');
      $insert['name'] = $input_data['name'];
      $insert['status'] = 1;
      $insert['id_kelas'] = 1;

      $id = $this->Db_dml->insert('user', $insert);
      $insert_answer['id_user'] = $id;
      $insert_answer['answer'] = json_encode($input_data['answer']);
  
      $this->Db_dml->insert('communication_history', $insert_answer);
    }
  }

  public function import2()
  {
    $input_data = json_decode(trim(file_get_contents('php://input')), true);

    $checkUser = $this->Db_select->select_where('user', ['nim' => $input_data['nim']]);
    $tmp = array();
    foreach ($input_data['answer'] as $key => $value) {
      $data['id'] = $key+1;
      $data['question'] = $value['question'];
      array_push($tmp, $data);
    }

    if ($checkUser) {
      /* user sudah ada */
      $id = $checkUser->id;
      $checkData = $this->Db_select->select_where('negotiation_style_history', ['user_id' => $id]);
      if ($checkData) {
        $update['answer'] = json_encode($input_data['answer']);
        $this->Db_dml->update('negotiation_style_history', $update, ['user_id' => $id]);
      } else {
        $insert2['user_id'] = $id;
        $insert2['answer'] = json_encode($input_data['answer']);

        $this->Db_dml->insert('negotiation_style_history', $insert2);
      }
    } else {
      /* insert user */
      $insert['nim'] = $input_data['nim'];
      $insert['type'] = 2;
      $insert['role'] = 0;
      $insert['email'] = $input_data['email'];
      $insert['username'] = $input_data['email'];
      $insert['password'] = md5('12345');
      $insert['name'] = $input_data['email'];
      $insert['status'] = 1;
      $insert['id_kelas'] = 1;

      $id = $this->Db_dml->insert('user', $insert);
      $insert_answer['user_id'] = $id;
      $insert_answer['answer'] = json_encode($tmp);
  
      $this->Db_dml->insert('negotiation_style_history', $insert_answer);
    }
  }
}
