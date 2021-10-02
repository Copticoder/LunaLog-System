<?php

  namespace App\Models;

  class ImageLogs extends DBTable {

    public function __construct($conn)
    {
      $this->conn = $conn;
      $this->table = 'imgs';
      $this->table_keys = array('img_id', 'img_link', 'meta_data', 'tags', 'posted_at', 'user_id');
      if (!$this->table_exists()){
        exit("table " . $this->table . " doesn't exist!");
      }
    }

    // ADD LOG TO THE DB
    public function add_img_log($log_data)
    {

      /*** Data Format ::

        $log_data = [
          'img_link' => 'users_data/imgs/1.jpg',
          'meta_data' => 'location: Moon, etc.',
          'tags' => 'moon, announcement',
          'user_id' => '1'
        ];

      ***/

      $log_add_query = "INSERT INTO `$this->table` (img_link, meta_data, tags, user_id) VALUES (:img_link, :meta_data, :tags, :user_id)";
      $stmt = $this->conn->prepare($log_add_query);
      $stmt->execute($log_data);
    }

    // REMOVE LOG FROM THE DB
    public function remove_img_log($img_id)
    {
      $log_remove_query = "DELETE FROM `$this->table` WHERE img_id = ?" ;
      $stmt = $this->conn->prepare($log_remove_query);
      $stmt->execute(array($img_id));
    }

  }
