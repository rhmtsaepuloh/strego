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
    $getData = $this->Db_select->select_all('decision_making_style');

    if ($getData) {
      foreach ($getData as $key => $value) {
        $value->question = json_decode($value->question);
        unset($value->created_at);
        unset($value->updated_at);
      }

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
    $getData = $this->Db_select->select_where('decision_making_style_history', ['user_id' => $this->input->post('user_id')]);

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

    $getData = $this->Db_select->select_where('decision_making_style_history', ['user_id' => $this->input->post('user_id')]);

    if (!$getData) {
      $answer = json_decode($this->input->post('answer'));
      
      $insert['user_id'] = $this->input->post('user_id');
      $insert['answer'] = json_encode($answer);

      $insertData = $this->Db_dml->insert('decision_making_style_history', $insert);

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
    $getData = $this->Db_select->select_where('decision_making_style_history', ['user_id' => $this->input->post('user_id')]);
    
    if ($getData) {
      $answer = json_decode($getData->answer);
      $data['sequential'] = $this->countSequential($answer);
      $data['logical'] = $this->countLogical($answer);
      $data['global'] = $this->countGlobal($answer);
      $data['personable'] = $this->countPersonable($answer);

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

  public function countSequential($data)
  {
    $count = 0;
    foreach ($data as $key => $value) {
      if ($value->id == 1) {
        $count += $value->question->q4;
      } else if ($value->id == 2) {
        $count += $value->question->q4;
      } else if ($value->id == 3) {
        $count += $value->question->q1;
      } else if ($value->id == 4) {
        $count += $value->question->q2;
      } else if ($value->id == 5) {
        $count += $value->question->q4;
      } else if ($value->id == 6) {
        $count += $value->question->q1;
      }
    }
    return $count;
  }

  public function countLogical($data)
  {
    $count = 0;
    foreach ($data as $key => $value) {
      if ($value->id == 1) {
        $count += $value->question->q1;
      } else if ($value->id == 2) {
        $count += $value->question->q2;
      } else if ($value->id == 3) {
        $count += $value->question->q3;
      } else if ($value->id == 4) {
        $count += $value->question->q3;
      } else if ($value->id == 5) {
        $count += $value->question->q1;
      } else if ($value->id == 6) {
        $count += $value->question->q2;
      }
    }
    return $count;
  }

  public function countGlobal($data)
  {
    $count = 0;
    foreach ($data as $key => $value) {
      if ($value->id == 1) {
        $count += $value->question->q2;
      } else if ($value->id == 2) {
        $count += $value->question->q1;
      } else if ($value->id == 3) {
        $count += $value->question->q2;
      } else if ($value->id == 4) {
        $count += $value->question->q1;
      } else if ($value->id == 5) {
        $count += $value->question->q3;
      } else if ($value->id == 6) {
        $count += $value->question->q3;
      }
    }
    return $count;
  }

  public function countPersonable($data)
  {
    $count = 0;
    foreach ($data as $key => $value) {
      if ($value->id == 1) {
        $count += $value->question->q3;
      } else if ($value->id == 2) {
        $count += $value->question->q3;
      } else if ($value->id == 3) {
        $count += $value->question->q4;
      } else if ($value->id == 4) {
        $count += $value->question->q4;
      } else if ($value->id == 5) {
        $count += $value->question->q2;
      } else if ($value->id == 6) {
        $count += $value->question->q4;
      }
    }
    return $count;
  }
}
