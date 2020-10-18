<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Company extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->library('global_lib');
  }

  public function list()
  {
    $getData = $this->Db_select->query_all('select id, name, status, type_gameplay from company where status = 1');

    if ($getData) {
      $result['status'] = true;
      $result['message'] = 'Success';
      $result['data'] = $getData;
    } else {
      $result['status'] = true;
      $result['message'] = 'Company data not found';
      $result['data'] = null;
    }

    echo json_encode($result);
  }
}
