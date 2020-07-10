<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
  public function __construct(){
    parent::__construct();
    if (!$this->session->userdata('user')) {
      redirect(base_url(''));
    }
  }

  public function index()
  {
    /* get data Company */
    $data['company'] = $this->Db_select->select_all('company');
    $history = $this->Db_select->query_all('select *from invite_log a where a.from != a.to and a.status = 1');

    $summary = [];
    foreach ($history as $key => $value) {
      $value->from = $this->Db_select->query('select a.id user_id, a.email, a.name name_user, b.id id_company, b.name company_name from user a join company b on a.id_company = b.id where a.id = '.$value->from);
      $value->to = $this->Db_select->query('select a.id user_id, a.email, a.name name_user, b.id id_company, b.name company_name from user a join company b on a.id_company = b.id where a.id = '.$value->to);

      $where_1['user_id'] = $value->from->user_id;
      $where_1['id_invite'] = $value->id;
      $gamePlay_1 = $this->Db_select->select_where('summary', $where_1);
      $where_2['user_id'] = $value->to->user_id;
      $where_2['id_invite'] = $value->id;
      $gamePlay_2 = $this->Db_select->select_where('summary', $where_2);

      $return['nama_player_1'] = $value->from->name_user;
      $return['company_1'] = $value->from->company_name;
      $return['point_1'] = $gamePlay_1 ? $gamePlay_1->total_score : 0;
      $return['nama_player_2'] = $value->to->name_user;
      $return['company_2'] = $value->to->company_name;
      $return['point_2'] = $gamePlay_2 ? $gamePlay_2->total_score : 0;

      array_push($summary, $return);
    }
    $data['list'] = $summary;
    $this->load->view('header', $data);
    $this->load->view('dashboard');
    $this->load->view('footer');
  }
}
