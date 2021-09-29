<?php

  namespace App\Models;

  class Tokens extends DBTable {

    public function __construct($conn)
    {
      $this->conn = $conn;
      $this->table = 'tokens';
      $this->table_keys = array('token_id', 'token_data', 'expiry_date', 'created_at', 'user_id');
      if (!$this->table_exists()){
        exit("table " . $this->table . " doesn't exist!");
      }
    }

    // ADD TOKEN TO THE DB
    public function add_token($token_data)
    {

      /*** Data Format ::

        $token_data = [
          'token_data' => '113943d1f78df16806d5ccfa8367ccf2',
          'expiry_date' => '2021-09-28 21:58:44',
          'user_id' => 0
        ];

      ***/

      $token_add_query = "INSERT INTO `$this->table` (token_data, expiry_date, user_id) VALUES (:token_data, :expiry_date, :user_id)";
      $stmt = $this->conn->prepare($token_add_query);
      $stmt->execute($token_data);
    }

    // REMOVE TOKEN FROM THE DB
    public function remove_token($token_id)
    {
      $token_remove_query = "DELETE FROM `$this->table` WHERE token_id = ?" ;
      $stmt = $this->conn->prepare($token_remove_query);
      $stmt->execute(array($token_id));
    }

  }
