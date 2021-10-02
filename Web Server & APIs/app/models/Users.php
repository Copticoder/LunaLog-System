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

    // GET ALL USERS LOGs
    public function get_all_users_logs()
    {
      $query = "SELECT users.id, users.name, logs.log_id, logs.log_data, logs.posted_at, logs.meta_data, logs.tags FROM users INNER JOIN logs ON users.id = logs.user_id";
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      $res1 = $stmt->fetchAll(\PDO::FETCH_ASSOC);
      $query = "SELECT users.id, users.name, imgs.img_id, imgs.img_link, imgs.posted_at, imgs.meta_data, imgs.tags FROM users INNER JOIN imgs ON users.id = imgs.user_id";
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      $res2 = $stmt->fetchAll(\PDO::FETCH_ASSOC);
      $query = "SELECT users.id, users.name, audios.audio_id, audios.audio_link, audios.posted_at, audios.meta_data, audios.tags FROM users INNER JOIN audios ON users.id = audios.user_id";
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      $res3 = $stmt->fetchAll(\PDO::FETCH_ASSOC);
      $combined_results = array(
        'text_logs' => $res1,
        'img_logs' => $res2,
        'audio_logs' => $res3
      );
      $final_res = array();
      foreach ($combined_results as $key => $value) {
        for ($i=0; $i < count($value); $i++) {
          if (!in_array($value[$i]['id'], array_keys($final_res))) {
            $final_res[$value[$i]['id']] = array();
          }
          if (!in_array('user_name', array_keys($final_res[$value[$i]['id']]))) {
            $final_res[$value[$i]['id']]['user_name'] = $value[$i]['name'];
          }
          if (!in_array($key, array_keys($final_res[$value[$i]['id']]))) {
            $final_res[$value[$i]['id']][$key] = array();
          }
          $log_arr = $value[$i];
          unset($log_arr['id']);
          unset($log_arr['name']);
          array_push($final_res[$value[$i]['id']][$key], $log_arr);
        }
      }

      return $final_res;
    }

  }
