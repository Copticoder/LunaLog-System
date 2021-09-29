<?php

  namespace App\Models;

  class Users extends DBTable {

    function __construct($conn)
    {
      $this->conn = $conn;
      $this->table = 'users';
      $this->table_keys = array('id', 'name', 'username', 'email', 'password', 'created_at', 'user_role');
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

    // GET USER DATA
    public function get_user_data($email)
    {
      $query = "SELECT * FROM `$this->table` WHERE email='". $email . "'";
      $res = $this->conn->query($query);

      foreach ($res as $row) {
        $user_data = new \stdClass;
        $user_data->user_id = $row[0];
        $user_data->name = $row[1];
        $user_data->username = $row[2];
        $user_data->email = $row[3];
        $user_data->created_at = $row[5];
        $user_data->user_role = $row[6];
      }

      return $user_data;

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
