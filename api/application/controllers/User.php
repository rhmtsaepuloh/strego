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

    $gamePlay = $this->global_lib->getGamePlay($this->input->post('user_id'));
    $user = $this->Db_select->select_where('user', ['id' => $this->input->post('user_id')]);

    $getData = $this->Db_select->query_all('select a.id as id_user, a.email, a.username, a.name as name_user, a.email, a.is_online, a.in_game, b.id as id_company, b.name as company_name from user a join company b on a.id_company = b.id where a.status = 1 and b.type_gameplay = '.$gamePlay.' and a.id_company != '.$user->id_company.' and a.id != '.$this->input->post('user_id').' order by a.is_online desc');

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

    $gamePlay = $this->global_lib->getGamePlay($this->input->post('user_id'));
    $user = $this->Db_select->select_where('user', ['id' => $this->input->post('user_id')]);

    $getData = $this->Db_select->query_all('select a.id as id_user, a.email, a.username, a.name as name_user, a.email, a.is_online, a.in_game, b.id as id_company, b.name as company_name from user a join company b on a.id_company = b.id where a.status = 1 and b.type_gameplay = '.$gamePlay.' and a.id_company != '.$user->id_company.' and a.is_online = 1 and a.id != '.$this->input->post('user_id').' order by a.is_online desc');

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

    $gamePlay = $this->global_lib->getGamePlay($this->input->post('user_id'));
    $user = $this->Db_select->select_where('user', ['id' => $this->input->post('user_id')]);

    $getData = $this->Db_select->query_all('select a.id as id_user, a.email, a.username, a.name as name_user, a.email, a.is_online, a.in_game, b.id as id_company, b.name as company_name from user a join company b on a.id_company = b.id where a.status = 1 and b.type_gameplay = '.$gamePlay.' and a.id_company != '.$user->id_company.' and a.in_game = 1 and a.id != '.$this->input->post('user_id').' order by a.is_online desc');

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

  public function changeProfile()
  {
    $require = array('user_id', 'token', 'nama', 'id_kelas');
    $this->global_lib->input($require);

    $getUser = $this->Db_select->query('select a.*, b.id id_company, b.name company_name from user a join company b on a.id_company = b.id where a.id = '.$this->input->post('user_id'));

    if ($getUser) {
      $where['id'] = $this->input->post('user_id');
      $update['name'] = $this->input->post('name');
      $update['id_kelas'] = $this->input->post('id_kelas');

      $updateData = $this->Db_dml->update('user', $update, $where);

      if ($updateData) {
        $result['status'] = true;
        $result['message'] = 'Success';
        $result['data'] = $getUser;
      } else {
        $result['status'] = false;
        $result['message'] = 'Data failed to save';
        $result['data'] = $getUser;
      }
    } else {
      $result['status'] = true;
      $result['message'] = 'User data not found';
      $result['data'] = null;
    }

    echo json_encode($result);
  }

  public function changePassword()
  {
    $require = array('user_id', 'token', 'old_password', 'new_password', 'confirm_password');
    $this->global_lib->input($require);

    $old_password = $this->input->post('old_password');
    $new_password = $this->input->post('new_password');
    $confirm_password = $this->input->post('confirm_password');

    $getUser = $this->Db_select->query('select a.*, b.id id_company, b.name company_name from user a join company b on a.id_company = b.id where a.id = '.$this->input->post('user_id'));

    if ($getUser) {
      if ($getUser->password == md5($old_password)) {
        if ($new_password == $confirm_password) {
          $where['id'] = $this->input->post('user_id');
          $update['password'] = md5($new_password);
  
          $updateData = $this->Db_dml->update('user', $update, $where);
  
          if ($updateData) {
            $result['status'] = true;
            $result['message'] = 'Success';
            $result['data'] = $getUser;
          } else {
            $result['status'] = false;
            $result['message'] = 'Data failed to save';
            $result['data'] = $getUser;
          }
        } else {
          $result['status'] = false;
          $result['message'] = 'Your new password doesn`t match';
          $result['data'] = null;
        }
      } else {
        $result['status'] = false;
        $result['message'] = 'Your old password doesn`t match';
        $result['data'] = null;
      }
    } else {
      $result['status'] = true;
      $result['message'] = 'User data not found';
      $result['data'] = null;
    }

    echo json_encode($result);
  }
}
