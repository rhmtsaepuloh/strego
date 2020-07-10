<?php
class Global_lib {
  public $status = false;
	public $message = 'Mohon login kembali';
  public $data = NULL;
  
  function __construct()
  {
    $this->CI =& get_instance();
  }

  public function userCheck()
  {
    if ($this->CI->input->post('user_id')) {
      $where['id'] = $this->CI->input->post('user_id');
      $where['token'] = $this->CI->input->post('token');
      $where['type'] = 2;
      $user = $this->CI->Db_select->select_where('user', $where);
      if ($user) {
        return $user;
      }
    }
  }

  public function input($arr) {
    if (!is_array($this->CI->input->post())) {
      $message = implode(', ', $arr) . ' tidak boleh kosong';
      $this->status = false;
      $this->message = $message;
      $this->data = null;
      $this->get_error();
    }

    $post = array_keys(array_filter($this->CI->input->post()));
    $combine = array_intersect($arr, $post);
    if ($combine !== $arr) {
      $message = implode(', ', array_diff($arr, $combine)) . ' tidak boleh kosong';
      $this->status = false;
      $this->message = $message;
      $this->data = null;
      $this->get_error();
    }
    return true;
  }

  public function get_error()
  {
    $result = array(
      'status' => $this->status,
      'message' => $this->message,
      'data' => $this->data
    );
    echo json_encode($result);exit();
  }

  public function fcm($user, $msg)
  {
    $url = 'https://fcm.googleapis.com/fcm/send';
    $server_key = 'AAAA4rWVceQ:APA91bE_na8HU8Dg8je8opRYw4JrxctRnCT8kGpl_Ez-We8Ws8RJDSXYD2USm8Bz2OHfHGjyEhLb_6BuzEyoNoZCWUrQD__VVzXz4ZiXeU8WxL514BerO4Gwg99KheK6-cGvj8TNyvxp';

    $headers = array(
        'Content-Type:application/json',
        'Authorization:key='.$server_key
    );

    $msg = $msg;

    $fields = array(
        'to'        => $user->reg_id,
        'notification'  => $msg,
        'data' => $msg
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $fields ));
    $hasil = json_decode(curl_exec($ch));
    $error = curl_error($ch);
    curl_close($ch);

    if ($hasil) {
      if ($hasil->success) {
        $result['status'] = true;
        $result['message'] = 'Success';
        $result['data'] = null;
      } else {
        $result['status'] = false;
        $result['message'] = 'Request failed to send';
        $result['data'] = $hasil;
      }
    } else {
      $result['status'] = false;
      $result['message'] = $error;
      $result['data'] = null;
    }

    $insert['request'] = json_encode($fields);
    $insert['respon'] = json_encode($result);
    $this->CI->Db_dml->insert('log', $insert);

    return $result;
  }
}