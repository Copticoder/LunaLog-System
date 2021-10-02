<?php

  use App\Core\App;

  App::get('/session/start/{user_id}/{token_data}', function($args) {
    $_SESSION['user_id'] = $args->user_id;
    $_SESSION['token_data'] = $args->token_data;

    header("Location: /home");
  });
