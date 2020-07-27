<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class History extends CI_Controller
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

  public function index()
  {
    $require = array('user_id', 'token');
    $this->global_lib->input($require);

    $getData = $this->Db_select->query_all('select *from answer where user_id = '.$this->input->post('user_id').' group by id_invite order by id desc');

    if ($getData) {
      foreach ($getData as $key => $value) {
        $getInvite = $this->Db_select->select_where('invite_log', 'id = '.$value->id_invite);
        $idPlayer = 0;
        if ($getInvite) {
          if ($getInvite->from !== $value->user_id) {
            $idPlayer = $getInvite->from;
          } else {
            $idPlayer = $getInvite->to;
          }
        }
        $getOpponent = $this->Db_select->select_where('user', 'id = '.$idPlayer);
        $company = $this->Db_select->select_where('company', 'id = '.$getOpponent->id_company);
        $value->opponent_name = $getOpponent->name;
        $value->opponent_company = $company->name;

        $where['user_id'] = $value->user_id;
        $where['id_invite'] = $value->id_invite;
        $finalAnswer = $this->Db_select->select_where('answer', $where);
        if ($finalAnswer) {
          $value->isDone = true;
        } else {
          $value->isDone = false;
        }

        $value->finalQuestion = $this->summaryData2($value->id_invite, $value->user_id);
        $finalQuestion = $this->summaryDataNew($value->id_invite, $value->user_id);
        $finalQuestionOpponent = $this->summaryDataNew($value->id_invite, $idPlayer);
        $value->agreed = (string)$finalQuestion['agreed'];
        $value->conflicted = (string)$finalQuestion['conflicted'];
        $value->total_score = (string)($finalQuestion['total_score']+$finalQuestionOpponent['total_score']);
        $value->my_point = (string)$finalQuestion['total_score'];
        $value->opponent_point = (string)$finalQuestionOpponent['total_score'];
        unset($value->user_id);
        unset($value->final_answer);
        unset($value->is_finish);
      }

      $result['status'] = true;
      $result['message'] = 'Success';
      $result['data'] = $getData;
    } else {
      $result['status'] = true;
      $result['message'] = 'Data history not found';
      $result['data'] = null;
    }

    echo json_encode($result);
  }

  public function summaryDataNew($id_invite, $user_id)
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

        foreach ($questionMe as $key => $item) {
          foreach ($item->question as $key => $ql) {
            if ($ql->is_my_answer && $ql->is_opp_answer) {
              $totalPoint += $item->answer_point;
            }
          }
        }

        $insert['user_id'] = $user_id;
        $insert['id_invite'] = $id_invite;
        $insert['total_score'] = $totalPoint;
        $insert['agreed'] = $agreed;
        $insert['conflicted'] = $conflicted;

        return $insert;
      }

      return null;
    }
    return null;
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

        foreach ($questionMe as $key => $item) {
          foreach ($item->question as $key => $ql) {
            if ($ql->is_my_answer && $ql->is_opp_answer) {
              $totalPoint += $item->answer_point;
            }
          }
        }

        $data['questionResult'] = $questionMe;
        $data['totalPoint'] = $totalPoint;

        return $data;
      } else {
        return null;
      }
    } else {
      return null;
    }
  }

  public function summaryData2($id_invite, $user_id)
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

        return $questionMe;
      } else {
        return null;
      }
    } else {
      return null;
    }
  }

  public function scoreBoard()
  {
    $require = array('user_id', 'token');
    $this->global_lib->input($require);

    $user_id = $this->input->post('user_id');
    $getAll = $this->Db_select->query_all('select a.* from invite_log a join answer b on a.id = b.id_invite group by a.id order by a.id desc');
    
    if ($getAll) {
      $getData = [];
      foreach ($getAll as $key => $value) {
        $player_1 = $this->Db_select->select_where('user', 'id = '.$value->from);
        $player_2 = $this->Db_select->select_where('user', 'id = '.$value->to);
        $summaryPlayer1 = $this->summaryData($value->id, $player_1->id);
        $summaryPlayer2 = $this->summaryData($value->id, $player_2->id);

        if ($summaryPlayer2 && $summaryPlayer1) {
          $data['id_invite'] = $value->id;
          $data['player_1'] = $player_1->name;
          $data['company_player_1'] = $this->getCompanyData($player_1->id_company)->name;
          $data['point_player_1'] = $summaryPlayer1 ? $summaryPlayer1['totalPoint'] : 0;
          $data['player_2'] = $player_2->name;
          $data['company_player_2'] = $this->getCompanyData($player_2->id_company)->name;
          $data['point_player_2'] = $summaryPlayer2 ? $summaryPlayer2['totalPoint'] : 0;
          $data['total_point'] = $data['point_player_1'] + $data['point_player_2'];
          $data['created_at'] = $value->created_at;
          array_push($getData, $data);
        }
      }

      $totalPoint = array_column($getData, 'total_point');
      array_multisort($totalPoint, SORT_DESC, $getData);

      $result['status'] = true;
      $result['message'] = 'Success';
      $result['data'] = $getData;
    } else {
      $result['status'] = true;
      $result['message'] = 'Data history not found';
      $result['data'] = null;
    }

    echo json_encode($result);
  }

  public function getCompanyData($idCompany)
  {
    $where['id'] = $idCompany;
    return $this->Db_select->select_where('company', $where);
  }

  public function historyV2()
  {
    $require = array('user_id', 'token');
    $this->global_lib->input($require);

    $where['user_id'] = $this->input->post('user_id');
    $where['finish'] = 1;
    $getData = $this->Db_select->select_all_where('log_answer', $where);

    if ($getData) {
      foreach ($getData as $key => $value) {
        $getInvite = $this->Db_select->select_where('invite_log', 'id = '.$value->id_invite);
        $idPlayer = 0;
        if ($getInvite) {
          if ($getInvite->from !== $value->user_id) {
            $idPlayer = $getInvite->from;
          } else {
            $idPlayer = $getInvite->to;
          }
        }
        $getOpponent = $this->Db_select->select_where('user', 'id = '.$idPlayer);
        $company = $this->Db_select->select_where('company', 'id = '.$getOpponent->id_company);
        $value->opponent_name = $getOpponent->name;
        $value->opponent_company = $company->name;

        $where2['user_id'] = $value->user_id;
        $where2['id_invite'] = $value->id_invite;
        $finalAnswer = $this->Db_select->select_where('log_answer', $where2);
        if ($finalAnswer) {
          $value->isDone = true;
        } else {
          $value->isDone = false;
        }
        unset($value->user_id);
        $value->answer = json_decode($value->answer_json);
        unset($value->answer_json);
      }
      $result['status'] = true;
      $result['message'] = 'success';
      $result['data'] = $getData;
    } else {
      $result['status'] = true;
      $result['message'] = 'History gameplay empty';
      $result['data'] = null;
    }

    echo json_encode($result);
  }

  public function scoreBoardV2()
  {
    $require = array('user_id', 'token');
    $this->global_lib->input($require);

    $user_id = $this->input->post('user_id');
    $getAll = $this->Db_select->query_all('select * from invite_log a join log_answer b on a.id = b.id_invite where b.finish = 1 group by a.id order by b.point desc');

    $dataAll = array();
    foreach ($getAll as $key => $value) {
      $getPlayer1 = $this->Db_select->query('select a.*, b.name as name_company from user a join company b on a.id_company = b.id where a.id = '.$value->from);
      $getPlayer2 = $this->Db_select->query('select a.*, b.name as name_company from user a join company b on a.id_company = b.id where a.id = '.$value->to);

      /* get point */
      $pointPlayer1 = $this->Db_select->select_where('log_answer', ['id_invite' => $value->id, 'user_id' => $value->from]);
      $pointPlayer2 = $this->Db_select->select_where('log_answer', ['id_invite' => $value->id, 'user_id' => $value->to]);
      $pointPlayer1 = $pointPlayer1 ? $pointPlayer1->point : 0;
      $pointPlayer2 = $pointPlayer2 ? $pointPlayer2->point : 0;
      $data = array(
        "id_invite" => $value->id,
        "player_1" => $getPlayer1->name,
        "company_player_1" => $getPlayer1->name_company,
        "point_player_1" => $pointPlayer1,
        "player_2" => $getPlayer2->name,
        "company_player_2" => $getPlayer2->name_company,
        "point_player_2" => $pointPlayer2,
        "total_point" => $pointPlayer1 + $pointPlayer2,
        "created_at" => $value->created_at
      );

      array_push($dataAll, $data);
    }

    if (count($dataAll) > 0) {
      $company = $this->Db_select->select_all_where('company', ['type_gameplay' => 1]);
      $companyList = [];
      foreach ($company as $key => $value) {
        array_push($companyList, $value->name);
      }

      $result['status'] = true;
      $result['message'] = 'success';
      $result['company'] = $companyList;
      $result['data'] = $dataAll;
    } else {
      $result['status'] = true;
      $result['message'] = 'Scoreboard is empty';
      $result['data'] = null;
    }

    echo json_encode($result);
  }
}