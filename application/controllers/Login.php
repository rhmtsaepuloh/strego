<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
  public function __construct(){
    parent::__construct();
  }

  public function index()
  {
    if ($this->session->userdata('user')) {
      redirect(base_url('dashboard'));
    } else {
      $this->load->view('login');
    }
  }

  public function superadmin()
  {
    if ($this->session->userdata('superadmin')) {
      redirect(base_url('superadmin'));
    } else {
      $this->load->view('superadmin/login');
    }
  }

  public function superadminAuth()
  {
    $where['username'] = $this->input->post('username');
    $where['type'] = 3;

    $user = $this->Db_select->select_where('user', $where);
    
    if ($user) {
      $password = md5($this->input->post('password'));
      if ($user->password === $password) {
        $sess['email'] = $user->email;
        $sess['name'] = $user->name;
        $sess['type'] = $user->type;
        $this->session->set_userdata('superadmin',$sess);
        
        $result['status'] = true;
        $result['message'] = 'Success';
        $result['data'] = '';
      } else {
        $result['status'] = false;
        $result['message'] = 'Password salah';
        $result['data'] = '';
      }
    } else {
      $result['status'] = false;
      $result['message'] = 'Data user tidak ditemukan';
      $result['data'] = '';
    }

    echo json_encode($result);
  }

  public function auth()
  {
    $where['username'] = $this->input->post('username');
    $where['type'] = 1;

    $user = $this->Db_select->select_where('user', $where);
    
    if ($user) {
      $password = md5($this->input->post('password'));
      if ($user->password === $password) {
        $sess['email'] = $user->email;
        $sess['name'] = $user->name;
        $sess['type'] = $user->type;
        $sess['role'] = $user->role;
        $this->session->set_userdata('user',$sess);
        
        $result['status'] = true;
        $result['message'] = 'Success';
        $result['data'] = '';
      } else {
        $result['status'] = false;
        $result['message'] = 'Password salah';
        $result['data'] = '';
      }
    } else {
      $result['status'] = false;
      $result['message'] = 'Data user tidak ditemukan';
      $result['data'] = '';
    }

    echo json_encode($result);
  }

  public function logout()
  {
    $this->session->unset_userdata('user');
    redirect(base_url(''));
  }

  public function superadminLogout()
  {
    $this->session->unset_userdata('superadmin');
    redirect(base_url('administrator'));
  }
}