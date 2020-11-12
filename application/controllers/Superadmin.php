<?php

class Superadmin extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    if (!$this->session->userdata('superadmin')) {
      redirect(base_url('administrator'));
    }
  }

  public function index()
  {
    $this->load->view('superadmin/header');
    $this->load->view('superadmin/dashboard');
    $this->load->view('superadmin/footer');
  }

  public function admin()
  {
    $data['list'] = $this->Db_select->select_all_where('user', ['type' => 1]);
    $this->load->view('superadmin/header', $data);
    $this->load->view('superadmin/admin');
    $this->load->view('superadmin/footer');
  }

  public function adminEdit($id)
  {
    $where['id'] = $id;
    $update['username'] = $this->input->post('username');
    $update['role'] = $this->input->post('role');
    if ($this->input->post('password')) {
      $update['password'] = md5($this->input->post('password'));
    }

    $updateData = $this->Db_dml->update('user', $update, $where);
    redirect(base_url('superadmin/admin'));
  }

  public function negotiation()
  {
    $list = $this->Db_select->select_all('negotiation_style_history');
    foreach ($list as $key => $value) {
      $value->answer = json_decode($value->answer);
      $value->user = $this->Db_select->select_where('user', ['id' => $value->user_id]);
      $value->competing = $this->countCompeting($value->answer);
      $value->accommodating = $this->countAccommodating($value->answer);
      $value->collaborating = $this->countCollaborating($value->answer);
      $value->avoiding = $this->countAvoiding($value->answer);
      $value->compromising = $this->countCompromising($value->answer);
      $value->kelas = "-";
      if ($value->user->id_kelas) {
        $kelas = $this->Db_select->select_where('kelas', ['id' => $value->user->id_kelas]);
        if ($kelas) {
          $value->kelas = $kelas->name;
        }
      }
      $value->substantiveConcern = $value->collaborating + $value->competing - $value->accommodating - $value->avoiding;
      $value->relationalConcern = $value->accommodating + $value->collaborating - $value->avoiding - $value->competing;
    }
    $data['data'] = $list;

    $this->load->view('superadmin/header', $data);
    $this->load->view('superadmin/negotiation');
    $this->load->view('superadmin/footer');
  }

  public function decision()
  {
    $list = $this->Db_select->select_all('decision_making_style_history');
    foreach ($list as $key => $value) {
      $value->answer = json_decode($value->answer);
      $value->user = $this->Db_select->select_where('user', ['id' => $value->user_id]);
      $value->sequential = $this->countSequential($value->answer);
      $value->logical = $this->countLogical($value->answer);
      $value->global = $this->countGlobal($value->answer);
      $value->personable = $this->countPersonable($value->answer);

      $value->kelas = "-";
      if ($value->user->id_kelas) {
        $kelas = $this->Db_select->select_where('kelas', ['id' => $value->user->id_kelas]);
        if ($kelas) {
          $value->kelas = $kelas->name;
        }
      }
    }
    $data['data'] = $list;

    $this->load->view('superadmin/header', $data);
    $this->load->view('superadmin/decision');
    $this->load->view('superadmin/footer');
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

  public function countCompeting($data)
  {
    $count = 0;
    foreach ($data as $key => $value) {
      if ($value->id == 2) {
        $count += $value->question->q2;
      } else if ($value->id == 5) {
        $count += $value->question->q1;
      } else if ($value->id == 7) {
        $count += $value->question->q2;
      } else if ($value->id == 8) {
        $count += $value->question->q1;
      } else if ($value->id == 11) {
        $count += $value->question->q2;
      } else if ($value->id == 13) {
        $count += $value->question->q1;
      } else if ($value->id == 16) {
        $count += $value->question->q2;
      } else if ($value->id == 18) {
        $count += $value->question->q1;
      }
    }
    return $count;
  }

  public function countAccommodating($data)
  {
    $count = 0;
    foreach ($data as $key => $value) {
      if ($value->id == 1) {
        $count += $value->question->q1;
      } else if ($value->id == 3) {
        $count += $value->question->q2;
      } else if ($value->id == 7) {
        $count += $value->question->q1;
      } else if ($value->id == 9) {
        $count += $value->question->q2;
      } else if ($value->id == 12) {
        $count += $value->question->q2;
      } else if ($value->id == 14) {
        $count += $value->question->q1;
      } else if ($value->id == 16) {
        $count += $value->question->q1;
      } else if ($value->id == 19) {
        $count += $value->question->q2;
      }
    }
    return $count;
  }

  public function countCollaborating($data)
  {
    $count = 0;
    foreach ($data as $key => $value) {
      if ($value->id == 1) {
        $count += $value->question->q2;
      } else if ($value->id == 4) {
        $count += $value->question->q1;
      } else if ($value->id == 8) {
        $count += $value->question->q2;
      } else if ($value->id == 10) {
        $count += $value->question->q1;
      } else if ($value->id == 13) {
        $count += $value->question->q2;
      } else if ($value->id == 15) {
        $count += $value->question->q1;
      } else if ($value->id == 17) {
        $count += $value->question->q2;
      } else if ($value->id == 19) {
        $count += $value->question->q1;
      }
    }
    return $count;
  }

  public function countAvoiding($data)
  {
    $count = 0;
    foreach ($data as $key => $value) {
      if ($value->id == 3) {
        $count += $value->question->q1;
      } else if ($value->id == 5) {
        $count += $value->question->q2;
      } else if ($value->id == 6) {
        $count += $value->question->q1;
      } else if ($value->id == 10) {
        $count += $value->question->q2;
      } else if ($value->id == 12) {
        $count += $value->question->q1;
      } else if ($value->id == 15) {
        $count += $value->question->q2;
      } else if ($value->id == 18) {
        $count += $value->question->q2;
      } else if ($value->id == 20) {
        $count += $value->question->q1;
      }
    }
    return $count;
  }

  public function countCompromising($data)
  {
    $count = 0;
    foreach ($data as $key => $value) {
      if ($value->id == 2) {
        $count += $value->question->q1;
      } else if ($value->id == 4) {
        $count += $value->question->q2;
      } else if ($value->id == 6) {
        $count += $value->question->q2;
      } else if ($value->id == 9) {
        $count += $value->question->q1;
      }  else if ($value->id == 11) {
        $count += $value->question->q1;
      } else if ($value->id == 14) {
        $count += $value->question->q2;
      } else if ($value->id == 17) {
        $count += $value->question->q1;
      } else if ($value->id == 20) {
        $count += $value->question->q2;
      }
    }
    return $count;
  }
}
