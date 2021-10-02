<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/solid.css">
    <script src="https://use.fontawesome.com/releases/v5.0.7/js/all.js"></script>
    <link rel="stylesheet" type="text/css" href="/css/login.css">
    <title>Registeration</title>
  </head>
  <body>

    <div class="modal-dialog text-center">
      <div class="col-sm-8 main-section">
        <div class="modal-content">

          <div class="col-12 user-img">
            <img src="/imgs/login-user-img.svg" alt="User Img">
          </div>

          <form class="col-12" onsubmit="submission_check()">
            <div class="form-group name-input">
              <input type="text" name="name" placeholder="Enter Name" required>
            </div>
            <div class="form-group">
              <input type="email" name="email" placeholder="Enter Email" required>
            </div>
            <div class="form-group">
              <input type="password" name="email" placeholder="Enter Password" required>
            </div>
            <button type="submit" class="btn" id="submit-btn"><i class="fas fa-sign-in-alt"></i>Register</button>
          </form>

          <div class="col-12 ref-link">
            <a href="/login">Login to your account</a>
          </div>

        </div> <!--- End of Modal Content --->
      </div>
    </div>

    <script type="text/javascript">
      function submission_check() {
        event.preventDefault();
        let name = document.getElementsByTagName('input')[0].value;
        let email = document.getElementsByTagName('input')[1].value;
        let password = document.getElementsByTagName('input')[2].value;
        console.log('here');
        $.post("/api/user/register",
          {
            name: name,
            email: email,
            password: password,
            user_role: '1'
          },
          function(data, status){
            console.log(data);
        });
      }
    </script>

  </body>
</html>
