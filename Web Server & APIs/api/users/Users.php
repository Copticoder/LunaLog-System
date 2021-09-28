<?php

  namespace Api\Users;

  class Users {

    private $token_model = NULL;
    private $user_model = NULL;
    private $token_data_keys = array('token_data', 'expiry_date', 'user_id');
    private $register_user_data_keys = array('name', 'email', 'password', 'username', 'user_role');
    private $login_user_data_keys = array('email', 'password');

    public function __construct($token_model, $user_model)
    {
      $this->token_model = $token_model;
      $this->user_model = $user_model;
    }

    public function index($value='')
    {
      echo 'users api';
    }

    // Token GENERATION
    private static function generate_token($user_id)
    {
      $current_timestamp = time();
      $expiry_timestamp = strtotime('+2 days', $current_timestamp);
      $expiry_timestamp = date("Y-m-d H:i:s", $expiry_timestamp);

      $token = hash('md5', $user_id . $current_timestamp . $expiry_timestamp);

      $token_data = array(
        'token_data' => $token,
        'expiry_date' => $expiry_timestamp,
        'user_id' => $user_id
      );

      return $token_data;
    }

    // ADDING TOKEN TO THE DB
    private function add_token($token_data)
    {
      if (!($this->check_token_data($token_data) && $this->token_model)) {
        return;
      }

      $this->token_model->add_token($token_data);
    }

    // CHECKING IF TOKEN DATA ARRAY IS IN THE SUITABLE FORMAT
    private function check_token_data($token_data)
    {
      if (is_array($token_data)) {
        if (count(array_intersect($token_data, $this->token_data_keys)) == count($token_data)) {
          return TRUE;
        }
      }

      return FALSE;
    }

    // VERIFYING A TOKEN
    private function verify_token($token_value, $user_id) {
      if (!$this->token_model) {
        return;
      }

      $verification_data = array(
        'token_data' => $token_value,
        'user_id' => $user_id
      );
      $res = $this->token_model->verify_token($verification_data);

      if ($res) {
        return TRUE;
      }

      return FALSE;
    }

    // IS TOKEN EXPIRED!
    private function token_expired($token_value, $user_id) {
      $verification_data = array(
        'token_data' => $token_value,
        'user_id' => $user_id
      );

      $res = $this->token_model->get_token($verification_data);
      $expiry_date_stamp = strtotime($res->expiry_date);
      if (time() > $expiry_date_stamp) {
        return TRUE;
      }

      return FALSE;
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
      if ($this->register_data_valid($user_data)) {
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
        if (count(array_intersect(array_keys($user_data), $this->register_user_data_keys)) == count($user_data)) {
          return TRUE;
        }
      }

      return FALSE;
    }


    // LOGIN :::

    // LOGIN USER
    public function login_user()
    {
      $user_data = $this->get_posted_data();

      if (!$this->login_data_valid($user_data)) {
        echo 'user data is not valid!';
        return;
      }

      $res = $this->user_model->check_user_credentials($user_data);
      if ($res) {
        echo 'user logged in!';
      } else {
        echo 'invalid credentials!';
      }
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

  }
