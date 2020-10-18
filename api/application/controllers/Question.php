<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class question extends CI_Controller {
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

  public function issue()
  {
    $require = array('user_id', 'token', 'id_invite');
    $this->global_lib->input($require);

    $getDataInvite = $this->Db_select->select_where('invite_log', 'id = '.$this->input->post('id_invite'));
    if ($getDataInvite) {
      $getIssue = $this->Db_select->select_all('issue');
      if ($getIssue) {
        foreach ($getIssue as $key => $value) {
          unset($value->created_at);
          
          /* GET DATA WEIGHT */
          $getWeight = $this->Db_select->query('select *from weight where id_company = '.$this->datauser->id_company);
          $value->weight = (int)$getWeight->weight;
          $value->answer_point = 0;

          /* GET DATA QUESTION */
          $getQuestion = $this->Db_select->query_all('select id, question as answer from question where id_issue = '.$value->id);
          foreach ($getQuestion as $keys => $item) {
            /* GET DATA POINT FOR EVERY QUESTION */
            $getPoint = $this->Db_select->query('select *from point where id_company = '.$this->datauser->id_company.' and id_question = '.$item->id);
            $item->point = (int)$getPoint->point;
            $item->is_my_answer = false;
            $item->is_opp_answer = false;
          }
          $value->question = $getQuestion;
        }

        $result['status'] = true;
        $result['message'] = 'Success';
        $result['data'] = $getIssue;
      } else {
        $result['status'] = true;
        $result['message'] = 'Issue not found';
        $result['data'] = null;
      }
    } else {
      $result['status'] = true;
      $result['message'] = 'Access denied';
      $result['data'] = null;
    }

    $this->insert_log($result);
    echo json_encode($result);
  }

  public function submit()
  {
    $require = array('user_id', 'token', 'id_invite', 'final_answer');
    $this->global_lib->input($require);

    $check = $this->Db_select->select_where('invite_log', 'id = '.$this->input->post('id_invite'));

    if ($check) {
      $answer = json_decode($this->input->post('final_answer'));
      $insert['user_id'] = $this->input->post('user_id');
      $insert['id_invite'] = $this->input->post('id_invite');
      $insert['final_answer'] = json_encode($answer);
      $insertData = $this->Db_dml->insert('answer', $insert);

      if ($insertData) {
        /* check user waiting opponent */
        $getOpponent = $this->Db_select->query('select *from answer where id_invite = '.$this->input->post('id_invite').' and user_id != '.$this->input->post('user_id'));

        if (!$getOpponent) {
          $data['is_waiting'] = true;
        } else {
          $data['is_waiting'] = false;
          /* KIRIM NOTIFIKASI KE USER YANG MENUNGGU */
          $getFirstUser = $this->Db_select->query('select *from answer where id_invite = '.$this->input->post('id_invite').' order by id asc');

          $user = $this->Db_select->select_where('user', 'id = '.$getFirstUser->user_id);
          $company = $this->Db_select->select_where('company', 'id = '.$user->id_company);
          $msg = array(
            'title' => 'Waiting finished', 
            'body' => 'Waiting finished', 
            'id_invite' => $this->input->post('id_invite'),
            'company' => $company->name,
            'android_channel_id' => 'waiting_channel',
          );
          $this->global_lib->fcm($user, $msg);

          // $updateUser['in_game'] = 1;
          // $whereUser['id'] = $check->from;
          // $this->Db_dml->update('user', $updateUser, $whereUser);

          // $updateUser2['in_game'] = 1;
          // $whereUser2['id'] = $check->to;
          // $this->Db_dml->update('user', $updateUser2, $whereUser2);

          /* SET SUMMARY DATA */
          $this->summaryData($this->input->post('id_invite'), $this->input->post('user_id'));
        }

        $result['status'] = true;
        $result['message'] = 'Success';
        $result['data'] = $data;
      } else {
        $result['status'] = true;
        $result['message'] = 'Failed to save, Please try again.';
        $result['data'] = null;
      }
    } else {
      $result['status'] = true;
      $result['message'] = 'Game history not found';
      $result['data'] = null;
    }

    echo json_encode($result);
  }

  public function lastSubmit()
  {
    $require = array('user_id', 'token', 'id_invite', 'final_answer', 'type');
    $this->global_lib->input($require);
    $type = $this->input->post('type');

    $check = $this->Db_select->select_where('invite_log', 'id = '.$this->input->post('id_invite'));

    if ($check) {
      $answer = json_decode($this->input->post('final_answer'));
      $insert['user_id'] = $this->input->post('user_id');
      $insert['id_invite'] = $this->input->post('id_invite');
      $insert['final_answer'] = json_encode($answer);
      if ($type == 2) {
        $insert['is_finish'] = 1;

        $updateUser['in_game'] = 0;
        $whereUser['id'] = $check->from;
        $this->Db_dml->update('user', $updateUser, $whereUser);

        $updateUser2['in_game'] = 0;
        $whereUser2['id'] = $check->to;
        $this->Db_dml->update('user', $updateUser2, $whereUser2);

        $user_id = $this->input->post('user_id');
        if ($user_id == $check->from) {
          $opponent = $check->to;
        } else {
          $opponent = $check->from;
        }
        $user = $this->Db_select->select_where('user', 'id = '.$opponent);
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
      } else if ($type == 1) {
        $update['is_change'] = 0;
        $where['id'] = $this->input->post('id_invite');
        $this->Db_dml->update('invite_log', $update, $where);
        
        $user_id = $this->input->post('user_id');
        if ($user_id == $check->from) {
          $opponent = $check->to;
        } else {
          $opponent = $check->from;
        }
        $user = $this->Db_select->select_where('user', 'id = '.$opponent);
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
      }
      $insertData = $this->Db_dml->insert('answer', $insert);

      if ($insertData) {
        $result['status'] = true;
        $result['message'] = 'Success';
        $result['data'] = null;
      } else {
        $result['status'] = true;
        $result['message'] = 'Failed to save, Please try again.';
        $result['data'] = null;
      }
    } else {
      $result['status'] = true;
      $result['message'] = 'Game history not found';
      $result['data'] = null;
    }

    echo json_encode($result);
  }

  public function summaryData($id_invite, $user_id)
  {
    $getLogGame = $this->Db_select->select_where('invite_log', 'id = '.$id_invite);

    if ($getLogGame) {
      $wherePlayer1['id_invite'] = $getLogGame->id;
      $wherePlayer1['user_id'] = $getLogGame->from;
      $wherePlayer2['id_invite'] = $getLogGame->id;
      $wherePlayer2['user_id'] = $getLogGame->to;

      if ($getLogGame->from === $user_id) {
        $where['id_invite'] = $getLogGame->id;
        $where['user_id'] = $getLogGame->from;
        $player = $this->Db_select->select_where('answer', $where);
        $whereOpponent['id_invite'] = $getLogGame->id;
        $whereOpponent['user_id'] = $getLogGame->to;
        $opponent = $this->Db_select->select_where('answer', $whereOpponent);
      } else {
        $where['id_invite'] = $getLogGame->id;
        $where['user_id'] = $getLogGame->to;
        $player = $this->Db_select->select_where('answer', $where);
        $whereOpponent['id_invite'] = $getLogGame->id;
        $whereOpponent['user_id'] = $getLogGame->from;
        $opponent = $this->Db_select->select_where('answer', $whereOpponent);
      }

      if ($player && $opponent) {
        $questionMe = json_decode($player->final_answer);
        $questionYou = json_decode($opponent->final_answer);

        $totalPoint = 0;
        $agreed = 0;
        foreach ($questionMe as $key => $value) {
          $totalPoint += $value->answer_point;
          foreach ($questionYou as $keys => $values) {
            if ($value->id === $values->id) {
              foreach ($value->question as $list) {
                if ($list->is_my_answer) {
                  foreach ($values->question as $list2) {
                    if ($list->id === $list2->id) {
                      if ($list->is_my_answer === $list2->is_my_answer) {
                        $agreed++;
                      }
                    }
                  }
                }
              }
            }
          }
        }

        foreach ($questionMe as $key => $value) {
          foreach ($questionYou as $keys => $values) {
            if ($value->id === $values->id) {
              foreach ($value->question as $list) {
                foreach ($values->question as $list2) {
                  if ($list->id === $list2->id) {
                    if ($list2->is_my_answer) {
                      $list->is_opp_answer = true;
                    }
                  }
                }
              }
            }
          }
        }
        $conflicted = count($questionMe)-$agreed;

        $insert['user_id'] = $user_id;
        $insert['id_invite'] = $id_invite;
        $insert['total_score'] = $totalPoint;
        $insert['agreed'] = $agreed;
        $insert['conflicted'] = $conflicted;

        $this->Db_dml->insert('summary', $insert);
      }
    }
  }

  public function getSummery()
  {
    $require = array('user_id', 'token', 'id_invite');
    $this->global_lib->input($require);

    $getLogGame = $this->Db_select->select_where('invite_log', 'id = '.$this->input->post('id_invite'));

    if ($getLogGame) {
      $wherePlayer1['id_invite'] = $getLogGame->id;
      $wherePlayer1['user_id'] = $getLogGame->from;
      $wherePlayer2['id_invite'] = $getLogGame->id;
      $wherePlayer2['user_id'] = $getLogGame->to;

      if ($getLogGame->from === $this->input->post('user_id')) {
        $player = $this->Db_select->query('select *from answer where id_invite = "'.$getLogGame->id.'" and user_id = "'.$getLogGame->from.'" order by id desc');
        $opponent = $this->Db_select->query('select *from answer where id_invite = "'.$getLogGame->id.'" and user_id = "'.$getLogGame->to.'" order by id desc');
      } else {
        $player = $this->Db_select->query('select *from answer where id_invite = "'.$getLogGame->id.'" and user_id = "'.$getLogGame->to.'" order by id desc');
        $opponent = $this->Db_select->query('select *from answer where id_invite = "'.$getLogGame->id.'" and user_id = "'.$getLogGame->from.'" order by id desc');
      }

      if ($player && $opponent) {
        $questionMe = json_decode($player->final_answer);
        $questionYou = json_decode($opponent->final_answer);

        if ($questionMe && $questionYou) {
          $totalPoint = 0;
          $agreed = 0;
          foreach ($questionMe as $key => $value) {
            foreach ($questionYou as $keys => $values) {
              if ($value->id === $values->id) {
                foreach ($value->question as $list) {
                  if ($list->is_my_answer) {
                    foreach ($values->question as $list2) {
                      if ($list->id === $list2->id) {
                        if ($list->is_my_answer === $list2->is_my_answer) {
                          $agreed++;
                        }
                      }
                    }
                  }
                }
              }
            }
          }
  
          foreach ($questionMe as $key => $value) {
            foreach ($questionYou as $keys => $values) {
              // if ($value->is_my_answer == $values->is_opp_answer) {
              //   $totalPoint += $value->answer_point;
              // }
              if ($value->id === $values->id) {
                foreach ($value->question as $list) {
                  foreach ($values->question as $list2) {
                    if ($list->id === $list2->id) {
                      if ($list2->is_my_answer) {
                        $list->is_opp_answer = true;
                      }
                    }
                  }
                }
              }
            }
          }
          $conflicted = count($questionMe)-$agreed;
  
          foreach ($questionMe as $key => $item) {
            foreach ($item->question as $key => $ql) {
              if ($ql->is_my_answer && $ql->is_opp_answer) {
                $totalPoint += $item->answer_point;
              }
            }
          }
  
          $data['total_point'] = $totalPoint;
          $data['agreed'] = $agreed;
          $data['conflicted'] = $conflicted;
          $data['question'] = $questionMe;
  
          $result['status'] = true;
          $result['message'] = "Success";
          $result['data'] = $data;
        } else{
          $result['status'] = true;
          $result['message'] = "Game invalid, please contact developer";
          $result['data'] = null;
        }
      } else {
        $result['status'] = true;
        $result['message'] = "the game isn't over yet";
        $result['data'] = null;
      }
    } else {
      $result['status'] = true;
      $result['message'] = 'Game history not found';
      $result['data'] = null;
    }

    echo json_encode($result);
  }
  
  public function insert_log($data)
  {
    $insert['respon'] = json_encode($data);
    $this->Db_dml->insert('log', $insert);
  }

  public function changeQuestion()
  {
    $require = array('user_id', 'token', 'id_invite');
    $this->global_lib->input($require);

    $inviteLog = $this->Db_select->select_where('invite_log', 'id = '.$this->input->post('id_invite'));
    $finishGame = $this->Db_select->query('select *from answer where id_invite = '.$this->input->post('id_invite').' and is_finish = 1');
    
    if ($inviteLog) {
      if ($inviteLog->is_change == 0 && !$finishGame) {
        $user_id = $this->input->post('user_id');
        if ($user_id == $inviteLog->from) {
          $opponent = $inviteLog->to;
        } else {
          $opponent = $inviteLog->from;
        }
        $user = $this->Db_select->select_where('user', 'id = '.$opponent);
        $company = $this->Db_select->select_where('company', 'id = '.$user->id_company);
        /* KIRIM FCM WAITING CHANGE */
        $update['is_change'] = $user_id;
        $where['id'] = $this->input->post('id_invite');
        $this->Db_dml->update('invite_log', $update, $where);

        $msg = array(
          'title' => 'Waiting '.$this->datauser->name.' change answer', 
          'body' => 'Waiting Opponent Change Answer', 
          'nama_pengirim' => $this->datauser->name,
          'id_invite' => $this->input->post('id_invite'),
          'company' => $company->name,
          'android_channel_id' => 'waiting_change',
        );
        $result = $this->global_lib->fcm($user, $msg);
      } else {
        /* RESPON BAHWA OPPONENT HARUS MENUNGGU LAWAN MERUBAH JAWABANNYA */
        $result['status'] = true;
        $result['message'] = 'Please waiting opponent change answer';
        $result['data'] = null;
      }
    } else {
      $result['status'] = true;
      $result['message'] = 'Game history not found';
      $result['data'] = null;
    }

    echo json_encode($result);
  }
}