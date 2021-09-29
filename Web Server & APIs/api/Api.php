<?php

  namespace Api;

  class Api {

    // Return Array Result as JSON
    protected function return_json($data) {
      if (!is_array($data)) {
        return;
      }

      header('Content-Type: application/json; charset=utf-8');
      echo json_encode($data);
    }

    // GENERATE TOKEN
    protected function generate_token() {
      $token = openssl_random_pseudo_bytes(16);
      $token = bin2hex($token);
      return $token;
    }

    // VERIFY TOKEN
    protected function verify_token($token_value, $user_id, $token_model)
    {
      $res = FALSE;
      $token_ = $token_model->get_by('user_id', $user_id);
      if (!$token_) {
        return $res;
      }
      if ($token_[0]['token_data'] == $token_value) {
        $res = TRUE;
      }

      return $res;
    }

  }
