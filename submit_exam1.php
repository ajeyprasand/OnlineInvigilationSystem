<!DOCTYPE html>
<html>
<head>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>
<body>

<script>
    function generateExamId() {
      // Generate the Exam ID dynamically
      var examId = Date.now().toString();
      
      // Add the Exam ID as a hidden field in the form
      var examIdField = document.createElement("input");
      examIdField.type = "hidden";
      examIdField.name = "examId";
      examIdField.value = examId;
      document.querySelector("form").appendChild(examIdField);
      
      // Display a success message with the generated Exam ID
      Swal.fire({
        icon: 'success',
        title: 'Exam Created Successfully',
        text: 'Exam ID: ' + examId,
        confirmButtonText: 'OK'
      });
    }
    
    function validateForm() {
      generateExamId();
      // You can add any form validation logic here before submitting the form
      return true;
    }
  </script>
<?php
// Assuming you are using MySQL as the database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve the form data
  $examName = $_POST['examName'];
  $examFormat = $_POST['examFormat'];
  $totalQuestions = $_POST['totalQuestions'];
  $examTotalMarks = $_POST['examTotalMarks'];
  $examDate = $_POST['examDate'];
  $examMode = $_POST['examMode'];

  // Connect to the database (replace the placeholders with your actual database credentials)
  $servername = "localhost";
  $username = "root";
  $password = "viki@2002";
  $dbname = "project";

  $conn = new mysqli($servername, $username, $password, $dbname);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Check if there are any existing records
  $countQuery = "SELECT COUNT(*) AS count FROM multipleexam";
  $countResult = $conn->query($countQuery)->fetch_assoc()['count'];

  if ($countResult > 0) {
    // Fetch the maximum Exam ID from the table
    $maxIdQuery = "SELECT MAX(examID) AS maxId FROM multipleexam";
    $maxIdResult = $conn->query($maxIdQuery)->fetch_assoc()['maxId'];
  
    // Increment the maximum Exam ID by 1 to get the new Exam ID
    $newExamId = $maxIdResult + 1;
  } else {
    // Set the Exam ID to 1 since there are no existing records
    $newExamId = 1;
  }

  // Prepare the SQL statement
  $sql = "INSERT INTO multipleexam (examID, examName, examFormat, totalQuestions, examTotalMarks, examDate, examMode)
          VALUES ('$newExamId', '$examName', '$examFormat', $totalQuestions, $examTotalMarks, '$examDate', '$examMode')";

  // Execute the SQL statement
  if ($conn->query($sql) === TRUE) {
    // Display SweetAlert success notification
    echo "<script>
      $(document).ready(function() {
        Swal.fire({
          icon: 'success',
          title: 'Exam created successfully',
          timer: 1500
        }).then(function() {
          window.location.href = 'create_qn.php'; // Redirect to create questions page
        });
      });
    </script>";
  } else {
    // Display SweetAlert error notification
    echo "<script>
      $(document).ready(function() {
        Swal.fire({
          icon: 'error',
          title: 'Error creating exam',
          text: '" . $conn->error . "',
        }).then(function() {
          window.location.href = 'create_qn.php'; // Redirect to create questions page
        });
      });
    </script>";
  }

  $conn->close();
}
?>
</body>
</html>
