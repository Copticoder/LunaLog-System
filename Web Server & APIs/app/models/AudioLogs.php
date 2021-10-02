<?php

  namespace App\Models;

  class AudioLogs extends DBTable {

    public function __construct($conn)
    {
      $this->conn = $conn;
      $this->table = 'audios';
      $this->table_keys = array('audio_id', 'audio_link', 'meta_data', 'tags', 'posted_at', 'user_id');
      if (!$this->table_exists()){
        exit("table " . $this->table . " doesn't exist!");
      }
    }

    // ADD LOG TO THE DB
    public function add_audio_log($log_data)
    {

      /*** Data Format ::

        $log_data = [
          'audio_link' => 'users_data/imgs/1.aac',
          'meta_data' => 'location: Moon, etc.',
          'tags' => 'moon, announcement',
          'user_id' => '1'
        ];

      ***/

      $log_add_query = "INSERT INTO `$this->table` (audio_link, meta_data, tags, user_id) VALUES (:audio_link, :meta_data, :tags, :user_id)";
      $stmt = $this->conn->prepare($log_add_query);
      $stmt->execute($log_data);
    }

    // REMOVE LOG FROM THE DB
    public function remove_audio_log($audio_id)
    {
      $log_remove_query = "DELETE FROM `$this->table` WHERE audio_id = ?" ;
      $stmt = $this->conn->prepare($log_remove_query);
      $stmt->execute(array($audio_id));
    }

  }
