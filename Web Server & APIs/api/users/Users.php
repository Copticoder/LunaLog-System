<?php

  namespace Api\Users;

  class Users extends \Api\Api{
    private $user_model = NULL;
    private $token_model = NULL;
    private $register_user_data_keys = array('name', 'email', 'password', 'username', 'user_role');
    private $login_user_data_keys = array('email', 'password');

    public function __construct($conn)
    {
      $this->token_model = new \App\Models\Tokens($conn);
      $this->user_model = new \App\Models\Users($conn);
    }

    // REGISTERATION :::::

    // GET POSTED REGISTERED DATA
    private function get_posted_data() {
      $post_register_data_keys = array('name', 'email', 'username', 'password', 'user_role');
      $user_data = array();
      foreach ($post_register_data_keys as $key) {
        if (isset($_POST[$key])) {
          $user_data[$key] = $_POST[$key];
        }
      }
      return $user_data;
    }

    // REGISTER USER
    public function register_user()
    {

      $user_data = $this->get_posted_data();

      if (!in_array('username', array_keys($user_data))) {
        $user_data['username'] = NULL;
      }
      if (!$this->register_data_valid($user_data)) {
        echo 'user data is not valid!';
        return;
      }
      if ($this->user_exists($user_data)) {
        echo 'user already exists!';
        return;
      }

      $this->user_model->add_user($user_data);
      echo 'user registered!';
    }

    // CHECK USER EXISTENCE
    private function user_exists($user_data)
    {
      return $this->user_model->check_user_existence($user_data);
    }

    // CHECK REGISTER USER_DATA VALIDITY
    private function register_data_valid($user_data)
    {
      if (is_array($user_data)) {
        if (count(array_keys($user_data)) >= 4) {
          return TRUE;
        }
      }

      return FALSE;
    }


    // LOGIN :::

    // LOGIN USER
    public function login_user()
    {
      $data = array(
        'logged_in' => FALSE
      );

      $user_data = $this->get_posted_data();

      if (!$this->login_data_valid($user_data)) {
        $data['message'] = 'user data is not valid!';
        return;
      }

      $res = $this->user_model->check_user_credentials($user_data);
      if ($res) {
        $data['logged_in'] = TRUE;
        $db_user_data = $this->user_model->get_user_data($user_data['email']);
        $data['user_data'] = $db_user_data;
        // get the user token_data
        $this->generate_user_token($db_user_data->user_id);
        $user_token_data = $this->get_user_token($db_user_data->user_id);
        $data['user_token'] = $user_token_data;
      } else {
        $data['message'] = 'invalid credentials!';
      }

      $this->return_json($data);
    }

    // CHECK LOGIN USER_DATA VALIDITY
    private function login_data_valid($user_data)
    {
      if (is_array($user_data)) {
        if (count(array_intersect(array_keys($user_data), $this->login_user_data_keys)) == count($user_data)) {
          return TRUE;
        }
      }

      return FALSE;
    }

    // TOKENIZATION ::

    // GET USER TOKEN VALUE
    public function get_user_token($user_id)
    {
      $res = $this->token_model->get_by('user_id', $user_id);
      if (count($res) == 0) {
        $res = null;
      }

      return $res;
    }

    // GENERATE USER TOKEN AND ADD IT TO THE DB
    private function generate_user_token($user_id)
    {
      $res = $this->get_user_token($user_id);
      if ($res != null) {
        return;
      }

      $token_value = $this->generate_token();
      $current_timestamp = time();
      $expiry_date = date('Y-m-d H:i:s', strtotime('+1 day', time()));
      $token_data = array(
        'token_data' => $token_value,
        'expiry_date' => $expiry_date,
        'user_id' => $user_id
      );
      $this->token_model->add_token($token_data);
    }

  }
