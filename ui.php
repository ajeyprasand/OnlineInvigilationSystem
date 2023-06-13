<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Invigilation Management System</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.20/tailwind.min.css">
  <style>
    body {
      background-color: lightsalmon;
      color: #000000;
      font-family: Arial, sans-serif;
    }
    h1 {
      font-size: 36px;
      font-weight: bold;
      text-align: center;
      margin-bottom: 20px;
      text-shadow: 0 2px 2px rgba(0,0,0,.3);
    }
    .login {
      background-color: #ffffff;
      color: #0000FF;
      padding: 10px 20px;
      border-radius: 5px;
      cursor: pointer;
    }
    .container {
      max-width: 500px;
      margin: 0 auto;
    }
    .login-form {
      margin-top: 20px;
    }
    .login-form input {
      width: 100%;
      padding: 10px;
      border-radius: 5px;
      border: 1px solid #cccccc;
    }
    .login-form button {
      background-color: #ffffff;
      color: #0000FF;
      padding: 10px 20px;
      border-radius: 5px;
      cursor: pointer;
    }
    .login-form h4 {
      font-size: 24px;
      font-weight: bold;
      text-align: center;
    }
    .login-form p {
      font-size: 16px;
      text-align: center;
    }
    .login-form img {
      width: 100%;
      height: 100%;
      display: block;
      margin: 20px auto;
      max-width: 300px;
      border-radius: 5px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    @keyframes invigilate {
  0% {
    opacity: 0;
  }
  100% {
    opacity: 1;
  }
}
</style>
</head>
<body>
  <div class="container">
    <h1 class="text-center">Invigilation Management System</h1>
    <div class="login-form">
      <img src="onlinetest.jpg" alt="Invigilation Management System" class="mx-auto d-block img-fluid">
      <h4 class="text-center">Login</h4>
      <div class="d-flex flex-column align-items-center">
        <a href="login_students.php" class="btn btn-secondary mb-3">Login as a student</a>
        <a href="log1.php" class="btn btn-primary">Login as an invigilator</a>
      </div>
    </div>
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
</body>
</html>



