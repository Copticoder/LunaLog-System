<?php

  namespace App\Models;

  class Logs extends DBTable {

    public function __construct($conn)
    {
      $this->conn = $conn;
      $this->table = 'logs';
      $this->table_keys = array('log_id', 'log_data', 'meta_data', 'tags', 'posted_at', 'user_id');
      if (!$this->table_exists()){
        exit("table " . $this->table . " doesn't exist!");
      }
    }

    // ADD LOG TO THE DB
    public function add_log($log_data)
    {

      /*** Data Format ::

        $log_data = [
          'log_data' => 'sample log',
          'meta_data' => 'location: Moon, etc.',
          'tags' => 'moon, announcement',
          'user_id' => '1'
        ];

      ***/

      $log_add_query = "INSERT INTO `$this->table` (log_data, meta_data, tags, user_id) VALUES (:log_data, :meta_data, :tags, :user_id)";
      $stmt = $this->conn->prepare($log_add_query);
      $stmt->execute($log_data);
    }

    // REMOVE LOG FROM THE DB
    public function remove_log($log_id)
    {
      $log_remove_query = "DELETE FROM `$this->table` WHERE log_id = ?" ;
      $stmt = $this->conn->prepare($log_remove_query);
      $stmt->execute(array($log_id));
    }

  }
