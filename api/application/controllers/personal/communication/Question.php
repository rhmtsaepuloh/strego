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

  public function getListQuestion()
  {
    $getData = $this->Db_select->select_all_row('communication_question');

    if ($getData) {
      $getData->question = json_decode($getData->question);
      unset($getData->id);
      unset($getData->updated_at);
      unset($getData->created_at);

      $result['status'] = true;
      $result['message'] = 'Success';
      $result['data'] = $getData;
    } else {
      $result['status'] = false;
      $result['message'] = 'Failed';
      $result['data'] = null;
    }

    echo json_encode($result);
  }

  public function getStatus()
  {
    $getData = $this->Db_select->select_where('communication_history', ['id_user' => $this->input->post('user_id')]);

    if ($getData) {
      $result['status'] = true;
      $result['message'] = 'Success';
      $result['data']['is_existed'] = true;
    } else {
      $result['status'] = true;
      $result['message'] = 'Success';
      $result['data']['is_existed'] = false;
    }

    echo json_encode($result);
  }

  public function answer()
  {
    $require = array('answer');
    $this->global_lib->input($require);

    $getData = $this->Db_select->select_where('communication_history', ['id_user' => $this->input->post('user_id')]);

    if (!$getData) {
      $answer = json_decode($this->input->post('answer'));
      
      $insert['id_user'] = $this->input->post('user_id');
      $insert['answer'] = json_encode($answer);

      $insertData = $this->Db_dml->insert('communication_history', $insert);

      if ($insertData) {
        $result['status'] = true;
        $result['message'] = 'Success';
        $result['data'] = null;
      } else {
        $result['status'] = false;
        $result['message'] = 'Failed';
        $result['data'] = null;
      }
    } else {
      $result['status'] = false;
      $result['message'] = 'You have filled this test.';
      $result['data'] = null;
    }

    echo json_encode($result);
  }

  public function getSummary()
  {
    $getData = $this->Db_select->select_where('communication_history', ['id_user' => $this->input->post('user_id')]);
    
    if ($getData) {
      $answer = json_decode($getData->answer);
    
      $style1 = [1,8,9,13,17,24,26,31,33,40,41,48,50,53,57,63,65,70,74,79];
      $style2 = [2,7,10,14,18,23,25,30,34,37,42,47,51,55,58,62,66,69,75,78];
      $style3 = [3,6,11,15,19,22,27,29,35,38,43,46,49,56,59,64,67,71,76,80];
      $style4 = [4,5,12,16,20,21,28,32,36,39,44,45,52,54,60,61,68,72,73,77];

      $style1Count = 0;
      $style2Count = 0;
      $style3Count = 0;
      $style4Count = 0;

      foreach ($answer as $value) {
        if (array_search($value->id,$style1,true)) {
          $style1Count++;
        }else if (array_search($value->id,$style2,true)) {
          $style2Count++;
        }else if (array_search($value->id,$style3,true)) {
          $style3Count++;
        } else {
          $style4Count++;
        }
      }

      $data['ACTION'] = $style1Count;
      $data['PROCESS'] = $style2Count;
      $data['PEOPLE'] = $style3Count;
      $data['IDEA'] = $style4Count;

      $result['status'] = true;
      $result['message'] = 'Success';
      $result['data'] = $data;
    } else {
      $result['status'] = false;
      $result['message'] = 'Data not found.';
      $result['data'] = null;
    }

    echo json_encode($result);
  }
}
