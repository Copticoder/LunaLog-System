<?php

  namespace App\Models;

  class Tokens extends DBTable {

    public function __construct($conn)
    {
      $this->conn = $conn;
      $this->table = 'tokens';
      if (!$this->table_exists()){
        exit("table " . $this->table . " doesn't exist!");
      }
    }

    public function add_token($token_data)
    {
      /*** Data Format ::
        $token_data = [
          'token_data' => '113943d1f78df16806d5ccfa8367ccf2',
          'expiry_date' => '2021-09-28 21:58:44',
          'user_id' => 0
        ]; ***/
      $token_add_query = "INSERT INTO `$this->table` (token_data, expiry_date, user_id) VALUES (:token_data, :expiry_date, :user_id)";
      $stmt = $this->conn->prepare($token_add_query);
      $stmt->execute($token_data);
    }

    public function get_token($verification_data) {
      /*** Data Format ::
        $verification_data = [
          'token_data' => '113943d1f78df16806d5ccfa8367ccf2',
          'user_id' => 0
        ]; ***/
      $query = "SELECT * FROM `$this->table` WHERE token_data='" .
        $verification_data['token_data'] .
        "' AND user_id='" .
        $verification_data['user_id'] . "' LIMIT 1";

      $res = $this->conn->query($query);

      foreach ($res as $row) {
        $token_data = new \stdClass;
        $token_data->token_id = $row[0];
        $token_data->token_value = $row[1];
        $token_data->expiry_date = $row[2];
        $token_data->created_at = $row[3];
        $token_data->user_id = $row[4];
      }

      return $token_data;
    }

    public function verify_token($verification_data) {
      /*** Data Format ::
        $verification_data = [
          'token_data' => '113943d1f78df16806d5ccfa8367ccf2',
          'user_id' => 0
        ]; ***/
      $query = "SELECT * FROM `$this->table` WHERE token_data='" .
        $verification_data['token_data'] .
        "' AND user_id='" .
        $verification_data['user_id'] . "'";

      $stmt = $this->conn->prepare($query);
      $stmt->execute();

      if ($stmt->rowCount() > 0) {
        return TRUE;
      }

      return FALSE;
    }

  }
