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
    
    .result-box {
      background-color: #f8f9fa;
      border: 1px solid #ced4da;
      border-radius: 5px;
      padding: 10px;
      margin-bottom: 20px;
    }
    
    .result-box h3, .result-box p {
      margin: 0;
    }

    .feedback-link {
      float: right;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Exam Results</h1>

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

    // Retrieve the unique exam IDs from the students table
    $sql = "SELECT DISTINCT ExamID FROM students";

    try {
      $stmt = $pdo->query($sql);

      if ($stmt->rowCount() > 0) {
        // Display the results for each exam ID
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $examID = $row['ExamID'];

          // Retrieve the exam details and total marks for the current exam ID
          $query = "SELECT * FROM multipleexam WHERE ExamID = :examID";
          $stmt2 = $pdo->prepare($query);
          $stmt2->bindParam(':examID', $examID);
          $stmt2->execute();
          $examRow = $stmt2->fetch(PDO::FETCH_ASSOC);

          if ($examRow !== false) {
            $examName = $examRow['examName'];
            $examTotalMarks = $examRow['examTotalMarks'];

            // Calculate the total marks for the current exam ID
            $query = "SELECT SUM(marks) AS totalMarks FROM students WHERE ExamID = :examID";
            $stmt3 = $pdo->prepare($query);
            $stmt3->bindParam(':examID', $examID);
            $stmt3->execute();
            $marksRow = $stmt3->fetch(PDO::FETCH_ASSOC);

            $totalMarks = $marksRow['totalMarks'] ?? 0;
            ?>
            <div class="result-box">
              <h3><?php echo $examName; ?></h3>
              <p><strong>Exam ID:</strong> <?php echo $examID; ?></p>
              <p><strong>Marks Obtained:</strong> <?php echo $totalMarks; ?> / <?php echo $examTotalMarks; ?></p>
              <a href="view_qns.php?examId=<?php echo $examID; ?>" class="feedback-link">Provide Feedback</a>
            </div>
            <?php
          }
        }
      } else {
        echo 'No exam results found.';
      }
    } catch (PDOException $e) {
      die("Error executing the query: " . $e->getMessage());
    }

    // Close the database connection
    $pdo = null;
    ?>
  </div>
</body>
</html>



