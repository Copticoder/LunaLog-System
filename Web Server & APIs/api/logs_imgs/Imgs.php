<?php

  namespace Api\Logs_imgs;

  class Imgs extends \Api\Api {

    private $user_model = NULL;
    private $token_model = NULL;
    private $imgs_log_model = NULL;

    public function __construct($conn)
    {
      $this->token_model = new \App\Models\Tokens($conn);
      $this->user_model = new \App\Models\Users($conn);
      $this->imgs_log_model = new \App\Models\ImageLogs($conn);
    }


    // IMG REGISTERATION  :::

    // GET REGISTER IMAGE LOG POSTED DATA
    private function get_img_register_posted_data() {
      $post_img_log_data_keys = array('user_id', 'img_data', 'token', 'tags', 'meta_data');
      $img_log_data = array();
      foreach ($post_img_log_data_keys as $key) {
        if (isset($_POST[$key])) {
          $img_log_data[$key] = $_POST[$key];
        } else {
          return null;
        }
      }
      return $img_log_data;
    }

    // REGISTER IMGs
    public function register_img_log()
    {
      $res = $this->get_img_register_posted_data();
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
      $decoded_img_link = $this->decode_save_img($res['img_data'], $res['user_id']);
      unset($res['img_data']);
      $res['img_link'] = $decoded_img_link;
      $this->imgs_log_model->add_img_log($res);
      $data = array(
        'registered' => TRUE
      );
      return $this->return_json($data);
    }

    // DECODE IMG DATA
    private function decode_save_img($img_data, $user_id) {
      global $USERS_DATA_IMGS_;
      $decoded_img = base64_decode($img_data);
      $img_name = time() . "_" . $user_id;
      $img_path = $img_name. '.jpg';
      file_put_contents(
        $USERS_DATA_IMGS_ . $img_path,
        $decoded_img
      );

      return $img_name;
    }


    // IMG REMOVAL :::

    // GET REMOVE IMG LOGs POSTED DATA
    private function get_remove_log_posted_data() {
      $post_remove_data_keys = array('img_id', 'user_id', 'token');
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
      $log_ = $this->imgs_log_model->get_by('img_id', $res['img_id']);
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
      $this->imgs_log_model->remove_img_log($res['img_id']);
      $data = array(
        'log_removed' => TRUE
      );
      return $this->return_json($data);
    }


    // GET USER IMG LOGs :::

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
      $user_logs = $this->imgs_log_model->get_by('user_id', $res['user_id']);
      return $this->return_json($user_logs);
    }


  }
