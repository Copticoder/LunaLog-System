<?php

  require '..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'general-config.php';
  use App\Core\App;
  use Api\Users\Users as UsersApi;

  $db = new Config\Database();
  $conn = $db->connect();

  // USERS Api
  global $conn;
  $token_model = new \App\Models\Tokens($conn);
  $user_model = new \App\Models\Users($conn);
  $users_api = new UsersApi($token_model, $user_model);

  App::post('/api/user/register', function ($args) {
    global $users_api;
    $users_api->register_user();
  });

  App::post('/api/user/login', function ($args) {
    global $users_api;
    $users_api->login_user();
  });
