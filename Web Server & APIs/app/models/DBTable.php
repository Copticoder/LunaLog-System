<?php

  namespace App\Models;
  use \PDO;

  class DBTable {
    // DB Vars
    protected $conn;
    protected $table;

    protected function table_exists()
    {
      $tables = $this->conn->query("SHOW TABLES")->fetchAll(PDO::FETCH_GROUP);
      if (!in_array($this->table, array_keys($tables))) {
        return FALSE;
      }

      return TRUE;
    }

  }
