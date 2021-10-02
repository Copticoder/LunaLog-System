<?php

  namespace Api\Logs_audios;

  class Audios extends \Api\Api {

    private $user_model = NULL;
    private $token_model = NULL;
    private $audios_log_model = NULL;

    public function __construct($conn)
    {
      $this->token_model = new \App\Models\Tokens($conn);
      $this->user_model = new \App\Models\Users($conn);
      $this->audios_log_model = new \App\Models\AudioLogs($conn);
    }


    // LOG REGISTERATION  :::

    // GET REGISTER AUDIO LOG POSTED DATA
    private function get_log_register_posted_data() {
      $post_audio_log_data_keys = array('user_id', 'audio_data', 'token', 'tags', 'meta_data');
      $audio_log_data = array();
      foreach ($post_audio_log_data_keys as $key) {
        if (isset($_POST[$key])) {
          $audio_log_data[$key] = $_POST[$key];
        } else {
          return null;
        }
      }
      return $audio_log_data;
    }

    // REGISTER AUDIOs
    public function register_audio_log()
    {
      $res = $this->get_log_register_posted_data();
      if (!$res) {
        $data = array(
          'message' => 'invalid data!'
        );
        return $this->return_json($data);
      }
      if (!$this->verify_token($res['token'], $res['user_id'], $this->token_model)) {
        $data = array(
          'message' => 'invalid token!'
        );
        return $this->return_json($data);
      }
      unset($res['token']);
      $decoded_audio_link = $this->decode_save_log($res['audio_data'], $res['user_id']);
      unset($res['audio_data']);
      $res['audio_link'] = $decoded_audio_link;
      $this->audios_log_model->add_audio_log($res);
      $data = array(
        'registered' => TRUE
      );
      return $this->return_json($data);
    }

    // DECODE LOG DATA
    private function decode_save_log($audio_data, $user_id) {
      global $USERS_DATA_AUDIOS_;
      $decoded_audio = base64_decode($audio_data);
      $audio_name = time() . "_" . $user_id;
      $audio_path = $audio_name . '.wav';
      file_put_contents(
        $USERS_DATA_AUDIOS_ . $audio_path,
        $decoded_audio
      );

      return $audio_name;
    }


    // AUDIO REMOVAL :::

    // GET REMOVE AUDIO LOGs POSTED DATA
    private function get_remove_log_posted_data() {
      $post_remove_data_keys = array('audio_id', 'user_id', 'token');
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
        return $this->return_json($data);
      }
      if (!$this->verify_token($res['token'], $res['user_id'], $this->token_model)) {
        $data = array(
          'message' => 'invalid token!'
        );
        return $this->return_json($data);
      }
      $log_ = $this->audios_log_model->get_by('audio_id', $res['audio_id']);
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
      $this->audios_log_model->remove_audio_log($res['audio_id']);
      $data = array(
        'log_removed' => TRUE
      );
      return $this->return_json($data);
    }


    // GET USER AUDIO LOGs :::

    // GET USER LOGs POSTED DATA
    private function get_user_logs_posted_data() {
      $post_get_data_keys = array('user_id', 'token');
      $log_get_data = array();
      foreach ($post_get_data_keys as $key) {
        if (isset($_POST[$key])) {
          $log_get_data[$key] = $_POST[$key];
        } else {
          return null;
        }
      }
      return $log_get_data;
    }

    public function get_user_logs() {
      $res = $this->get_user_logs_posted_data();
      if (!$res) {
        $data = array(
          'message' => 'invalid data!'
        );
        return $this->return_json($data);
      }
      if (!$this->verify_token($res['token'], $res['user_id'], $this->token_model)) {
        $data = array(
          'message' => 'invalid token!'
        );
        return $this->return_json($data);
      }
      $user_logs = $this->audios_log_model->get_by('user_id', $res['user_id']);
      return $this->return_json($user_logs);
    }


  }
