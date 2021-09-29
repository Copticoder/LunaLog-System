<?php

  require '..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'general-config.php';
  use App\Core\App;
  use Api\Users\Users as UsersApi;
  use Api\logs\Logs as LogsApi;

  $db = new Config\Database();
  $conn = $db->connect();

  // USERS Api
  global $conn;
  // $token_model = new \App\Models\Tokens($conn);
  // $user_model = new \App\Models\Users($conn);
  $users_api = new UsersApi($conn);
  $logs_api = new LogsApi($conn);

  App::post('/api/user/register', function ($args) {
    global $users_api;
    $users_api->register_user();
  });

  App::post('/api/user/login', function ($args) {
    global $users_api;
    $users_api->login_user();
  });

  App::post('/api/log/register', function ($args) {
    global $logs_api;
    $logs_api->register_log();
  });

  App::post('/api/log/remove', function ($args) {
    global $logs_api;
    $logs_api->remove_log();
  });

  App::get('/api/token/add', function ($args) {
    global $token_model;
    $token_model->add_token(array(
      'token_data' => '113943d1f78df16806d5ccfa8367ccf2',
      'expiry_date' => '2021-09-28 21:58:44',
      'user_id' => 1
    ));
  });

  App::get('/api/token/remove', function ($args) {
    global $token_model;
    $token_model->remove_token('2');
  });

  App::get('/api/token/get', function ($args) {
    global $users_api;
    print_r($users_api->get_user_token('1'));
  });
