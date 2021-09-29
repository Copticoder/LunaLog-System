<?php

  namespace Api\Logs;

  class Logs extends \Api\Api {

    private $user_model = NULL;
    private $token_model = NULL;
    private $logs_model = NULL;

    public function __construct($conn)
    {
      $this->token_model = new \App\Models\Tokens($conn);
      $this->user_model = new \App\Models\Users($conn);
      $this->logs_model = new \App\Models\Logs($conn);
    }

    // GET REGISTER LOGs POSTED DATA
    private function get_posted_data() {
      $post_log_data_keys = array('user_id', 'log_data', 'meta_data', 'tags', 'token');
      $log_data = array();
      foreach ($post_log_data_keys as $key) {
        if (isset($_POST[$key])) {
          $log_data[$key] = $_POST[$key];
        } else {
          return null;
        }
      }
      return $log_data;
    }

    // REGISTER LOGs
    public function register_log() {
      $res = $this->get_posted_data();
      if (!$res) {
        $data = array(
          'message' => 'invalid data!'
        );
        return $this->return_json($data);;
      }
      if (!$this->verify_token($res['token'], $res['user_id'], $this->token_model)) {
        $data = array(
          'message' => 'invalid token!'
        );
        return $this->return_json($data);
      }
      unset($res['token']);
      $this->logs_model->add_log($res);
      $data = array(
        'registered' => TRUE
      );
      return $this->return_json($data);
    }

    // GET REMOVE LOGs POSTED DATA
    private function get_remove_log_posted_data() {
      $post_remove_data_keys = array('log_id', 'user_id', 'token');
      $log_remove_data = array();
      foreach ($post_remove_data_keys as $key) {
        if (isset($_POST[$key])) {
          $log_remove_data[$key] = $_POST[$key];
        } else {
          return null;
        }
      }
      return $log_remove_data;
    }

    // REMOVE LOGs
    public function remove_log() {
      $res = $this->get_remove_log_posted_data();
      if (!$res) {
        $data = array(
          'message' => 'invalid data!'
        );
        return $this->return_json($data);;
      }
      if (!$this->verify_token($res['token'], $res['user_id'], $this->token_model)) {
        $data = array(
          'message' => 'invalid token!'
        );
        return $this->return_json($data);
      }
      $log_ = $this->logs_model->get_by('log_id', $res['log_id']);
      if (count($log_) == 0){
        $data = array(
          'message' => 'unauthoraized user!'
        );
        return $this->return_json($data);
      }
      if ($log_[0]['user_id'] != $res['user_id']) {
        $data = array(
          'message' => 'unauthoraized user!'
        );
        return $this->return_json($data);
      }
      $this->logs_model->remove_log($res['log_id']);
      $data = array(
        'log_removed' => TRUE
      );
      return $this->return_json($data);
    }

  }
