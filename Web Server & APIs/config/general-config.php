<?php

  namespace Config;

  // DATABASE CONFIGs
  $HOST = 'localhost';
  $USERNAME = 'root';
  $PASSWORD = '';
  $DB_NAME = 'nasa-sapce-apps';

  // DIRECTORIES CONFIGs
  define('DS', DIRECTORY_SEPARATOR);
  define('ROOT', '..' . DS);

    // ROOT DIRs
  $API_ = ROOT . 'apis' . DS;
  $APP_ = ROOT . 'app' . DS;
  $CONFIG_ = ROOT . 'config' . DS;
    // APP SUB-DIRs
  $CONTROLLERS_ = $APP_ . 'controllers';
  $CORE_ = $APP_ . 'core';
  $MODELS_ = $APP_ . 'models';
  $VIEWS_ = $APP_ . 'views';


  // CLASSES AUTOLOADER
  spl_autoload_register(function($class) {

    $class_path = explode('\\', $class);
    $class_name = ucwords(end($class_path)) . '.php';
    $class_path = ROOT . strtolower(implode(DS, array_slice($class_path, 0, -1))) . DS . $class_name;
    if (file_exists($class_path)) {
      require_once($class_path);
    } else{
      echo $class_path;
      echo 'error';
      exit;
    }

  });
