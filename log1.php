<!DOCTYPE html>
<html>
  <head>
    <title>Login Page</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <style>
      body {
        background: linear-gradient(to bottom right, #4c4c4c, #444444);
        color: #000000;
        font-family: Arial, sans-serif;
      }
      
      .login-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-color: lightseagreen;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        
      }

      .log1 {
        z-index: 1;
        width: 350px;
        padding: 20px;
        border-radius: 10px;
        background-color: whitesmoke;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
      }

      h1 {
        font-size: 36px;
        font-weight: bold;
        text-align: center;
        margin-bottom: 20px;
        color: red;
      }

      input[type="text"],
      input[type="password"] {
        border-radius: 5px;
        border: none;
        padding: 10px;
        width: 100%;
        margin-bottom: 20px;
        box-shadow: 0 2px 2px rgba(0,0,0,.1);
      }

      button[type="submit"] {
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

      button[type="submit"]:hover {
        background-color: #ad3232;
      }

      .error {
        color: red;
      }
    </style>
    </style>
  </head>
  <body>
    <div class="login-container">
      <div class="log1">
        <h1>Login</h1>
        <form method="post" action="log1_backend.php">
                <div class="form-group">
                    <label for="id"><b>Faculty ID:</b></label>
                    <input type="text" class="form-control" id="id" name="id" placeholder="Enter faculty ID">
                </div>
                <div class="form-group">
                    <label for="password"><b>Password:</b></label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter password">
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
                <p>Don't have an account? <a href="signin.php">Register here</a></p>
            </form>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
