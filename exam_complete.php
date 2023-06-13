<!DOCTYPE html>
<html>
<head>
  <title>View Results</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <style>
    .container {
      max-width: 600px;
      margin: 0 auto;
      padding: 20px;
    }
    
    .exam-box {
      background-color: #f8f9fa;
      border: 1px solid #ced4da;
      border-radius: 5px;
      padding: 10px;
      margin-bottom: 20px;
    }
    
    .exam-box h3, .exam-box p {
      margin: 0;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Exams Completed</h1>
<?php
// Database connection details
$host = 'localhost';
$dbname = 'project';
$username = 'root';
$password = 'viki@2002';

try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Database connection failed: " . $e->getMessage());
}

// Retrieve the student ID from the students table
$studentIDQuery = "SELECT DISTINCT student_id FROM students";
$studentIDStmt = $pdo->query($studentIDQuery);
$studentIDRow = $studentIDStmt->fetch(PDO::FETCH_ASSOC);

if ($studentIDRow !== false) {
  $studentID = $studentIDRow['student_id'];

  // Retrieve the completed exams for the student
  $sql = "SELECT DISTINCT ExamID FROM students WHERE student_id = :studentID";

  try {
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':studentID', $studentID);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      // Display the completed exams for the student
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $examID = $row['ExamID'];

        // Retrieve the exam details for the current exam ID
        $query = "SELECT * FROM multipleexam WHERE ExamID = :examID";
        $stmt2 = $pdo->prepare($query);
        $stmt2->bindParam(':examID', $examID);
        $stmt2->execute();
        $examRow = $stmt2->fetch(PDO::FETCH_ASSOC);

        if ($examRow !== false) {
          $examName = $examRow['examName'];
          $totalMarks = $examRow['examTotalMarks'];
          ?>
          <div class="exam-box">
            <h3><?php echo $examName; ?></h3>
            <p><strong>Exam ID:</strong> <?php echo $examID; ?></p>
            <p><strong>Total Marks:</strong> <?php echo $totalMarks; ?></p>

          </div>
          <?php
        }
      }
    } else {
      echo 'No completed exams found for the student.';
    }
  } catch (PDOException $e) {
    die("Error executing the query: " . $e->getMessage());
  }
} else {
  echo 'No student records found.';
}

// Close the database connection
$pdo = null;
?>