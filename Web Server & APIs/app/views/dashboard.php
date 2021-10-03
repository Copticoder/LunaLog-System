<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Dashboard</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <link rel="stylesheet" href="/css/dashboard.css">
  </head>
  <body>

    <div class="header">
      <div class="logo">
        <img src="/imgs/logo.svg" alt="">
      </div>
      <div class="welcome-msg">
        <p>Hello, Astronaut</p>
      </div>
      <div class="logout welcome-msg">
        <a href="/logout">Logout</a>
      </div>
    </div>

    <div class="tabs">

      <input type="radio" name="tabs" id="tab-other-users" checked='checked'>
      <label for="tab-other-users">Other Users</label>
      <div class="tab">
        <p>User1</p>
        <p>User1</p>
        <p>User1</p>
        <p>User1</p>
        <p>User1</p>
        <p>User1</p>
        <p>User1</p>
        <p>User1</p>
        <p>User1</p>
        <p>User1</p>
        <p>User1</p>
        <p>User1</p>
      </div>

      <input type="radio" name="tabs" id="tab-mylogs">
      <label for="tab-mylogs">My Logs</label>
      <div class="tab" id="myLogsTab">

      </div>

    </div>

    <script type="text/javascript">

    var userId = "<?php echo $_SESSION['user_id'] ?>";
    const myLogsTab = document.getElementById('myLogsTab');

    function addLog(log) {

      if (Object.keys(log).includes('log_id')) {
        var para = document.createElement('p');
        para.innerHTML = log['posted_at'] + ', ' + log['meta_data'] + ': ' + log['log_data'];
        para.classList.add('text_log');
        myLogsTab.appendChild(para);
      }
      if (Object.keys(log).includes('img_id')) {
        var img = document.createElement('img');
        img.src = 'http://nasaspaceapps.pp/image/' + log['img_link'];
        img.classList.add('img_log');
        img.style.height = '300px';
        img.style.width = '300px';
        myLogsTabNodes = myLogsTab.childNodes;
        var para = document.createElement('p');
        para.innerHTML = log['posted_at'] + ', ' + log['meta_data'] + ": ";
        myLogsTab.appendChild(para);
        myLogsTab.appendChild(img);
      }
      if (Object.keys(log).includes('audio_id')) {
        var audioLink = 'http://nasaspaceapps.pp/audio/' + log['audio_link'];
        $.get(audioLink, null, function(text){
            let audio = $(text).find('audio')['prevObject'][0];
            audio.classList.add('audio_log');
            var para = document.createElement('p');
            para.innerHTML = log['posted_at'] + ', ' + log['meta_data'] + ": ";
            myLogsTab.appendChild(para);
            myLogsTab.appendChild(audio);
        });
      }
    }

    function addUserData(data) {
      let userLogs = [];
      if (Object.keys(data).includes(userId)) {
        if (Object.keys(data[userId]).includes('text_logs')) {
          data[userId]['text_logs'].forEach(element => userLogs.push(element));
        }
        if (Object.keys(data[userId]).includes('img_logs')) {
          data[userId]['img_logs'].forEach(element => userLogs.push(element));
        }
        if (Object.keys(data[userId]).includes('audio_logs')) {
          data[userId]['audio_logs'].forEach(element => userLogs.push(element));
        }
      }
      userLogs.sort(function(a,b){
        return -Math.round(new Date(a['posted_at']).getTime()/1000) + Math.round(new Date(b['posted_at']).getTime()/1000);
      });
      myLogsTab.innerHTML = '';
      userLogs.forEach(elment => addLog(elment));
    }

    function startLiveUpdate() {

      setInterval(function () {
        fetch('/api/users/logs').then(function (response) {
          return response.json();
        }).then(function (data) {
          addUserData(data);
        }).catch(function (err) {
          console.log(err);
        });
      }, 3000);
    }

    document.addEventListener('DOMContentLoaded', function () {
      startLiveUpdate();
    });

    </script>

  </body>
</html>
