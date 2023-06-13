<?php
require_once('config.php');
?>
<!DOCTYPE html>
<html> 
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="favicon.png">
  <title>Sign-up Page</title>
  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
  <style>
    body {
      background-color: lightseagreen;
      color: #000000;
      font-family: Arial, sans-serif;
    }
    .valid {
			border: 1px solid green;
		}
	.invalid {
			border: 1px solid red;
	    }
    .container {
      background: #ffffff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 5px 10px rgba(0,0,0,.3);
      max-width: 400px;
      margin: 50px auto;
    }
    h1 {
      font-size: 36px;
      font-weight: bold;
      text-align: center;
      margin-bottom: 20px;
      color: red;
    }
    hr {
      border: none;
      height: 1px;
      background-color: #e8e8e8;
      margin-top: 20px;
      margin-bottom: 30px;
    }
    label {
      font-weight: bold;
      font-size: 16px;
    }
    input[type="text"], input[type="email"], input[type="password"], input[type="id"] {
      border-radius: 5px;
      border: none;
      padding: 10px;
      width: 100%;
      margin-bottom: 20px;
      box-shadow: 0 2px 2px rgba(0,0,0,.1);
    }
    input[type="submit"] {
      background-color: #cc3e3e;
      border: none;
      color: #ffffff;
      font-size: 16px;
      font-weight: bold;
      padding: 10px 20px;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color .3s;
    }
    input[type="submit"]:hover {
      background-color: #ad3232;
    }
    .login {
      color: #0000FF;
      text-decoration: none;
    }
    .login:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <form action="signin_students.php" method="post" onsubmit="return validateForm()">
    <div class="container">
      <h1>Sign-up</h1>
      <hr>
      <label for="id">Student id</label>
      <input type="id" id="id" name="id" placeholder="Enter id" required>
      <label for="username">Student name</label>
      <input type="text" id="username" name="username" placeholder="Enter name" required>
      <label for="email">Email</label>
      <input type="email" id="email" name="email" placeholder="Enter email" required>
      <label for="password">Password</label>
      <input type="password" id="password" name="password" minlength="8" placeholder="Enter password" required>
      <label for="confirmPassword">Confirm Password</label>
      <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm password" required>
      <input type="file" id="image" name="image" accept="image/*" required>
      <hr>
      <input type="submit" id="register" name="create" value="Sign In">
                    <p>Already have an account? <a href="login_students.php" class="login">Login here</a></p>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<script type="text/javascript">
$(function() {
  $('#register').click(function(e) {
    var inputs = $('#id, #username, #email, #password, #confirmPassword');
    var incomplete = false;
    inputs.each(function() {
      if ($(this).val() === '') {
        incomplete = true;
        return false;
      }
    });
    if (incomplete) {
      e.preventDefault(); // prevent form submission
      Swal.fire({
        'title': 'Error',
        'text': 'Please fill out all fields.',
        'type': 'error'
      });
      return;
    }

    var valid = this.form.checkValidity();

    if (valid) {
      var id = $('#id').val();
      var username = $('#username').val();
      var email = $('#email').val();
      var password = $('#password').val();
      var confirmPassword = $('#confirmPassword').val();

      if (password != confirmPassword) {
        $('#confirmPassword')[0].setCustomValidity("Passwords do not match");
        return;
      } else {
        $('#confirmPassword')[0].setCustomValidity("");
      }

      e.preventDefault();

      // Create a FormData object to store the form data, including the image
      var formData = new FormData();
      formData.append('id', id);
      formData.append('username', username);
      formData.append('email', email);
      formData.append('password', password);
      formData.append('confirmPassword', confirmPassword);
      formData.append('image', $('#image')[0].files[0]);

      $.ajax({
        type: 'POST',
        url: 'process1.php',
        data: formData,
        contentType: false, // Important: prevent jQuery from automatically setting the content type
        processData: false, // Important: prevent jQuery from automatically processing the data
        success: function(data) {
          Swal.fire({
            'title': 'Successful',
            'text': data,
            'type': 'success'
          });
        },
        error: function(xhr) {
          var message;
          if (xhr.responseText.indexOf('student id') > -1) {
            message = 'This student id is already registered.';
          } else if (xhr.responseText.indexOf('email') > -1) {
            message = 'This email is already registered.';
          } else {
            message = 'There were errors.';
          }
          Swal.fire({
            'title': 'Error',
            'text': message,
            'type': 'error'
          });
        }
      });
    } else {
      e.preventDefault(); // prevent form submission
    }
  });
});



</script>
</body>
</html>