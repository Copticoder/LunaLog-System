<?php

  namespace App\Models;

  class Users extends DBTable {

    function __construct($conn)
    {
      $this->conn = $conn;
      $this->table = 'users';
      if (!$this->table_exists()){
        exit("table " . $this->table . " doesn't exist!");
      }
    }

    // CREATE USER ACCOUNT
    public function add_user($user_data)
    {
      /*** Data Format ::
        $user_data = [
          'name' => 'example',
          'email' => 'example@example.com',
          'username' => NULL,
          'user_role' => 0,
          'password' => 'example123'
        ]; ***/
      $user_add_query = "INSERT INTO `users` (name, username, email, password, user_role) VALUES (:name, :username, :email, :password, :user_role)";
      $stmt = $this->conn->prepare($user_add_query);
      $stmt->execute($user_data);

      return TRUE;
    }

    // GET ALL USERS' CREDENTIALS
    public function get_users()
    {
      $query = "SELECT * FROM `$this->table`";
      $stmt = $this->conn->prepare($query);
      $stmt->execute();

      return $stmt;
    }

    // CHECK SOME USER'S CREDENTIALS
    public function check_user_credentials($user_data)
    {
      $query = "SELECT * FROM `$this->table` WHERE email='" .
        $user_data['email'] .
        "' AND password='" .
        $user_data['password'] . "'";

      $stmt = $this->conn->prepare($query);
      $stmt->execute();

      if ($stmt->rowCount() > 0) {
        return TRUE;
      }

      return FALSE;
    }

    // CHECK IF A USER EXISTS
    public function check_user_existence($user_data)
    {
      $query = "SELECT * FROM `" . $this->table . "` WHERE email='" . $user_data['email'] . "'";
      $stmt = $this->conn->prepare($query);
      $stmt->execute();

      if ($stmt->rowCount() > 0) {
        return TRUE;
      }

      return FALSE;
    }

  }
