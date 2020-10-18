<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class notification extends CI_Controller {
  public function __construct() {
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

  public function invite()
  {
    $require = array('user_id', 'token', 'id_player');
    $this->global_lib->input($require);

    $user = $this->Db_select->select_where('user', 'id = '.$this->input->post('id_player'));
    if ($user) {
      $company = $this->Db_select->select_where('company', 'id = '.$user->id_company);
      $insert['from'] = $this->input->post('user_id');
      $insert['to'] = $this->input->post('id_player');

      $insertLog = $this->Db_dml->insert('invite_log', $insert);

      if ($insertLog) {
        $msg = array(
          'title' => 'Invite from '.$this->datauser->name, 
          'body' => 'Invites you to play', 
          'nama_pengirim' => $this->datauser->name,
          'id_invite' => $insertLog,
          'company' => $company->name,
          'android_channel_id' => 'invite_channel',
        );
        $result = $this->global_lib->fcm($user, $msg);
      } else {
        $result['status'] = false;
        $result['message'] = 'Request invite failed';
        $result['data'] = null;
      }
    } else {
      $result['status'] = false;
      $result['message'] = 'Player not found';
      $result['data'] = null;
    }
    
    // $this->insert_log($result);
    echo json_encode($result);
  }

  public function accept()
  {
    $require = array('user_id', 'token', 'id_invite');
    $this->global_lib->input($require);

    $id = $this->input->post('id_invite');
    $invite_log = $this->Db_select->select_where('invite_log', 'id = '.$id);

    $user = $this->Db_select->select_where('user', 'id = '.$invite_log->from);
    if ($invite_log) {
      $company = $this->Db_select->select_where('company', 'id = '.$user->id_company);
      $where['id'] = $id;
      $update['status'] = 1;
      $this->Db_dml->update('invite_log', $update, $where);

      $updateUser['in_game'] = 1;
      $whereUser['id'] = $invite_log->from;
      $this->Db_dml->update('user', $updateUser, $whereUser);

      $updateUser2['in_game'] = 1;
      $whereUser2['id'] = $invite_log->to;
      $this->Db_dml->update('user', $updateUser2, $whereUser2);

      $msg = array(
        'title' => 'Accept from '.$this->datauser->name, 
        'body' => 'Play the Game', 
        'nama_pengirim' => $this->datauser->name,
        'id_invite' => $id,
        'company' => $company->name,
        'android_channel_id' => 'accept_channel',
      );
      $result = $this->global_lib->fcm($user, $msg);
    } else {
      $result['status'] = false;
      $result['message'] = 'Accept invitation failed';
      $result['data'] = null;
    }

    // $this->insert_log($result);
    echo json_encode($result);
  }

  public function reject()
  {
    $require = array('user_id', 'token', 'id_invite');
    $this->global_lib->input($require);

    $id = $this->input->post('id_invite');
    $invite_log = $this->Db_select->select_where('invite_log', 'id = '.$id);

    $user = $this->Db_select->select_where('user', 'id = '.$invite_log->from);
    if ($invite_log) {
      $company = $this->Db_select->select_where('company', 'id = '.$user->id_company);
      $where['id'] = $id;
      $update['status'] = 2;
      $this->Db_dml->update('invite_log', $update, $where);

      $msg = array(
        'title' => 'Reject from '.$this->datauser->name, 
        'body' => 'Next time play the Game', 
        'nama_pengirim' => $this->datauser->name,
        'id_invite' => $id,
        'company' => $company->name,
        'android_channel_id' => 'reject_channel',
      );
      $result = $this->global_lib->fcm($user, $msg);
    } else {
      $result['status'] = false;
      $result['message'] = 'Accept invitation failed';
      $result['data'] = null;
    }

    // $this->insert_log($result);
    echo json_encode($result);
  }

  public function insert_log($data)
  {
    $insert['respon'] = json_encode($data);
    $this->Db_dml->insert('log', $insert);
  }
}