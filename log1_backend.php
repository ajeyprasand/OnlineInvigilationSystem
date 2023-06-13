<!DOCTYPE html>
<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

</head>
<body>
<?php
require_once('config.php');
$error = "";
session_start(); // Start or resume the session
if (isset($_SESSION['logged_in'])) {
        header("Location: invig_dashboard.php");
        exit;
}
if (!empty($_POST)) {
    if (isset($_POST['id']) && isset($_POST['password'])) {
        $id = $_POST['id'];
        $password = $_POST['password'];
        $conn = mysqli_connect("localhost", "root", "viki@2002", "project");
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $stmt = $conn->prepare("SELECT * FROM signup WHERE id=?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            // User found, check password
            $user = $result->fetch_assoc();
            if ($password == $user['password']) {
                // Password correct, login successful
                $_SESSION['logged_in'] = true;
                $_SESSION['faculty_id'] = $user['id'];
            } else {
                // Password incorrect, display SweetAlert error message
                echo "<script>
                        $(document).ready(function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Invalid login credentials',
                                text: 'Faculty ID/Password is incorrect. Please fill in the correct details to log in.',
                            }).then(function() {
                                window.location.href = 'log1.php';
                            });
                        });
                      </script>";
            }
        } 
        else 
        {
            // User not found, display SweetAlert error message
            echo "<script>
                    $(document).ready(function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'User not found',
                            text: 'Faculty ID/Password is incorrect. Please fill in the correct details to log in.',
                        }).then(function() {
                            window.location.href = 'log1.php';
                        });
                    });
                  </script>";
        }

        $stmt->close();
        $conn->close();
    } 
    else 
    {
        // Invalid form data, display SweetAlert error message
        echo "<script>
                $(document).ready(function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Please fill out fields',
                        text: 'Please fill out the ID and password fields to log in to the exam.',
                    }).then(function() {
                        window.location.href = 'log1.php';
                    });
                });
              </script>";
    }
}
?>
</body>
</html>