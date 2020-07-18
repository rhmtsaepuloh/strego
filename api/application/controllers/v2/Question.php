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
    $require = array('id_invite');
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

  public function submit()
  {
    $require = array('question', 'id_invite');
    $this->global_lib->input($require);

    // 3 = buyer
    // 4 = saller

    $user = $this->Db_select->select_where('user', ['id' => $this->input->post('user_id')]);

    $question = json_encode(json_decode($this->input->post('question')));

    $ch = curl_init('http://strego.yasir.asia/calculation.php');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $question);
    
    // Set HTTP Header for POST request 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($question))
    );
    
    $data = json_decode(curl_exec($ch));
    curl_close($ch);

    if ($user->id_company == 3) {
      $hasil['point'] = $data->net_value_1;
    } else {
      $hasil['point'] = $data->net_value_2;
    }

    /* insert log answer */
    /* check apakah sudah ada */
    $checkLog = $this->Db_select->select_where('log_answer', [
      'id_invite' => $this->input->post('id_invite'),
      'user_id' => $this->input->post('user_id'),
    ]);

    if ($checkLog) {
      /* UPDATE */
      $whereUpdate['id'] = $checkLog->id;
      $log['id_invite'] = $this->input->post('id_invite');
      $log['user_id'] = $this->input->post('user_id');
      $log['answer_json'] = $question;
      $log['point'] = $hasil['point'];
      $this->Db_dml->update('log_answer', $log, $whereUpdate);
    } else {
      /* INSERT */
      $log['id_invite'] = $this->input->post('id_invite');
      $log['user_id'] = $this->input->post('user_id');
      $log['answer_json'] = $question;
      $log['point'] = $hasil['point'];
      $this->Db_dml->insert('log_answer', $log);
    }
    
    $getFirstUser = $this->Db_select->query('select *from invite_log where id = '.$this->input->post('id_invite'));

    if ($getFirstUser->from == $this->input->post('user_id')) {
      $userTarget = $getFirstUser->to;
    } else {
      $userTarget = $getFirstUser->from;
    }

    $user = $this->Db_select->select_where('user', 'id = '.$userTarget);
    $company = $this->Db_select->select_where('company', 'id = '.$user->id_company);

    $msg = array(
      'title' => 'Waiting finished', 
      'body' => 'Waiting finished', 
      'id_invite' => $this->input->post('id_invite'),
      'company' => $company->name,
      'android_channel_id' => 'waiting_channel',
    );
    $this->global_lib->fcm($user, $msg);
    

    $result['status'] = true;
    $result['message'] = "Success";
    $result['data'] = $hasil;

    echo json_encode($result);
  }

  public function lastSubmit()
  {
    $require = array('id_invite', 'final_answer', 'type');
    $this->global_lib->input($require);
    $type = $this->input->post('type');

    $check = $this->Db_select->select_where('invite_log', 'id = '.$this->input->post('id_invite'));

    $getFirstUser = $this->Db_select->query('select *from invite_log where id = '.$this->input->post('id_invite'));

    if ($getFirstUser->from == $this->input->post('user_id')) {
      $userTarget = $getFirstUser->to;
    } else {
      $userTarget = $getFirstUser->from;
    }

    if ($check) {
      if ($type == 1) {
        /* change answer */
        $user = $this->Db_select->select_where('user', 'id = '.$userTarget);
        $company = $this->Db_select->select_where('company', 'id = '.$user->id_company);

        $msg = array(
          'title' => $this->datauser->name.' completed change answer', 
          'body' => 'Opponent change answer completed', 
          'nama_pengirim' => $this->datauser->name,
          'id_invite' => $this->input->post('id_invite'),
          'company' => $company->name,
          'android_channel_id' => 'waiting_completed',
        );
        $this->global_lib->fcm($user, $msg);
      } elseif ($type == 2) {
        $where['id_invite'] = $this->input->post('id_invite');
        $update['finish'] = 1;
        $this->Db_dml->update('log_answer', $update, $where);

        $user = $this->Db_select->select_where('user', 'id = '.$userTarget);
        $company = $this->Db_select->select_where('company', 'id = '.$user->id_company);

        $msg = array(
          'title' => $this->datauser->name.' finish this game', 
          'body' => 'Game play has been completed', 
          'nama_pengirim' => $this->datauser->name,
          'id_invite' => $this->input->post('id_invite'),
          'company' => $company->name,
          'android_channel_id' => 'game_finished',
        );
        $this->global_lib->fcm($user, $msg);
      }

      $result['status'] = true;
      $result['message'] = 'Success';
      $result['data'] = null;
    } else {
      $result['status'] = true;
      $result['message'] = 'Game history not found';
      $result['data'] = null;
    }
    echo json_encode($result);
  }
}
