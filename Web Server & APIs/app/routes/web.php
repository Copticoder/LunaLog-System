<?php

  use App\Core\App;

  App::get('/home', function($args) {
    if (isset($_SESSION['user_id']) && isset($_SESSION['token_data'])) {
      echo 'Hello, user';
    } else {
      header("Location: /login");
    }
  });

  App::get('/login', function($args) {
    App::view('login');
  });

  App::get('/data', function($args) {
    if (isset($_SESSION['user_id'])) {
      echo $_SESSION['user_id'];
    }
  });

  App::get('/register', function($args) {
    App::view('register');
  });

  App::get('/dashboard', function($args) {
    App::view('dashboard');
  });
