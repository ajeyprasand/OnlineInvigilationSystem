<?php
session_start();

// Lock the session to prevent further changes
session_write_close();

// Mark the exam as completed
$_SESSION['exam_completed'] = true;

// Disable redirect to the previous file
if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] === $_SERVER['PHP_SELF']) {
    unset($_SERVER['HTTP_REFERER']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Success</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }

        .container {
            max-width: 400px;
            margin: 0 auto;
            text-align: center;
        }

        h1 {
            margin-bottom: 20px;
        }

        p {
            margin-bottom: 20px;
        }

        button[type="submit"] {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Thank you for completing the exam!</h1>
        <p>Your results will be available shortly.</p>

        <!-- Add a logout button -->
        <form method="POST" action="logout.php">
            <button class="btn btn-primary" type="submit">Logout</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script>
        // Redirect the user away from the success page
        if (window.history && window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
            window.history.pushState(null, null, 'success.php');
            window.addEventListener('popstate', function () {
                window.location.href = 'success.php';
            });
        }
    </script>
</body>

</html>
