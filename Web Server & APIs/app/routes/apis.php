<?php

  use App\Core\App;
  use Api\Users\Users as UsersApi;
  use Api\logs\Logs as LogsApi;
  use Api\Logs_imgs\Imgs as ImgsApi;
  use Api\Logs_audios\Audios as AudiosApi;

  $users_api = new UsersApi($conn);
  $logs_api = new LogsApi($conn);
  $imgs_api = new ImgsApi($conn);
  $audios_api = new AudiosApi($conn);

  // User Registeration & Login Endpoints

  App::post('/api/user/register', function ($args) {
    global $users_api;
    $users_api->register_user();
  });

  App::post('/api/user/login', function ($args) {
    global $users_api;
    $users_api->login_user();
  });

  // GET ALL USERS LOGs

  App::get('/api/users/logs', function($args) {
    global $users_api;
    $users_api->get_all_users_logs();
  });

  // Text Log Endpoints

  App::post('/api/log/register', function ($args) {
    global $logs_api;
    $logs_api->register_log();
  });

  App::post('/api/log/remove', function ($args) {
    global $logs_api;
    $logs_api->remove_log();
  });

  App::post('/api/log/get', function ($args) {
    global $logs_api;
    $logs_api->get_user_logs();
  });


  // Img Logs Endpoints

  App::post('/api/img/register', function($args) {
    global $imgs_api;
    $imgs_api->register_img_log();
  });

  App::post('api/img/get', function($args) {
    global $imgs_api;
    $imgs_api->get_user_logs();
  });

  App::post('api/img/remove', function($args) {
    global $imgs_api;
    $imgs_api->remove_log();
  });


  // Audio Logs Endpoints

  App::post('/api/audio/register', function($args) {
    global $audios_api;
    $audios_api->register_audio_log();
  });

  App::post('api/audio/get', function($args) {
    global $audios_api;
    $audios_api->get_user_logs();
  });

  App::post('api/audio/remove', function($args) {
    global $audios_api;
    $audios_api->remove_log();
  });
