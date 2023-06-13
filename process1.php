<?php
require_once('config.php');

if(isset($_POST)){
    $id  = $_POST['id'];
    $username   = $_POST['username'];
    $email   = $_POST['email'];
    $password   = $_POST['password'];
    $confirmPassword  = $_POST['confirmPassword'];

    // Handle image upload
    $image = $_FILES['image'];

    // Check for duplicate id
    $conn = mysqli_connect("localhost", "root", "viki@2002", "project");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $stmt = $conn->prepare("SELECT * FROM signup_students WHERE id=?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Duplicate id, return HTTP 409 Conflict
        http_response_code(409);
        echo "Duplicate entry detected for faculty id.";
    } else {
        // Check for duplicate email
        $stmt = $conn->prepare("SELECT * FROM signup_students WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Duplicate email, return HTTP 409 Conflict
            http_response_code(409);
            echo "Duplicate entry detected for email.";
        } else {
            // No duplicate entry, proceed with inserting new user
            // check if password and confirm password match
            if ($password !== $confirmPassword) {
                echo 'Password and Confirm Password do not match';
                return;
            }

            // Insert user data including the image file name
            $stmt = $conn->prepare("INSERT INTO signup_students (id, username, email, password, confirmPassword, image) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $id, $username, $email, $password, $confirmPassword, $image['name']);
            if (!$stmt->execute()) {
                echo "Error creating account: " . mysqli_error($conn);
            } else {
                // Move the uploaded image to a desired location
                $targetDir = "C:/wamp64/www/seproject/";
                $targetPath = $targetDir . $image['name'];
                move_uploaded_file($image['tmp_name'], $targetPath);

                echo "Account created successfully.";
            }
        }
    }

    $stmt->close();
    $conn->close();
} else {
    echo 'No data';
}
?>
