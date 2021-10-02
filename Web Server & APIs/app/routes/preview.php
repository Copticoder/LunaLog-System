<?php

  use \App\Core\App;

  App::get('/image/{img_name}', function($args) {
    global $USERS_DATA_IMGS_;
    if (file_exists($USERS_DATA_IMGS_ . $args->img_name . '.jpg')) {
      header("content-type: image/jpg");
      echo file_get_contents($USERS_DATA_IMGS_ . $args->img_name . '.jpg');
    } else {
      App::view('404');
    }
  });

  App::get('/audio/{audio_name}', function($args) {
    global $USERS_DATA_AUDIOS_;
    $file = $USERS_DATA_AUDIOS_ . $args->audio_name . '.wav';
    if (file_exists($file)) {
      $cont = file_get_contents($file);
      echo '<audio controls src="data:audio/wav;base64,' . base64_encode($cont) . '" />';
    }
  });
