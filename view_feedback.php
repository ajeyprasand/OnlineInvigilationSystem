<!DOCTYPE html>
<html>

<head>
  <title>View Results</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <style>
    body {
      background-color: #f8f9fa;
      padding: 20px;
    }

    h1 {
      margin-bottom: 20px;
      text-align: center;
    }

    .container {
      max-width: 800px;
      margin: 0 auto;
    }

    .result-box {
      background-color: #f8f9fa;
      border: 1px solid #ced4da;
      border-radius: 5px;
      padding: 10px;
      margin-bottom: 20px;
    }

    .result-box h3,
    .result-box p {
      margin: 0;
    }

    .homepage-link {
      position: absolute;
      top: 20px;
      left: 20px;
      color: #007bff;
      font-size: 18px;
      font-weight: bold;
      text-decoration: none;
    }

    .homepage-link:hover {
      text-decoration: none;
    }

    /* Additional Styles */
    .container {
      background-color: #fff;
      border-radius: 5px;
      padding: 20px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .result-table {
      width: 100%;
      border-collapse: collapse;
    }

    .result-table thead th {
      background-color: #f8f9fa;
      border-bottom: 1px solid #dee2e6;
      padding: 10px;
      text-align: left;
    }

    .result-table tbody td {
      border-bottom: 1px solid #dee2e6;
      padding: 10px;
    }
  </style>
</head>

<body>
  <a href="invig_dashboard.php" class="homepage-link">Home</a>
  <h1>Students Responses</h1>
  <div class="container">
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
    $sql = "SELECT DISTINCT s.ExamID, s.student_id, su.username 
            FROM students s
            INNER JOIN signup_students su ON s.student_id = su.id";

    try {
      $stmt = $pdo->query($sql);

      if ($stmt->rowCount() > 0) {
        // Display the results for each exam ID
        echo '<table class="result-table">';
        echo '<thead class="thead-dark">';
        echo '<tr>';
        echo '<th>Exam Name</th>';
        echo '<th>Exam ID</th>';
        echo '<th>Student ID</th>';
        echo '<th>Name of the Student</th>';
        echo '<th>Marks Obtained</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $examID = $row['ExamID'];
          $studentID = $row['student_id'];
          $username = $row['username'];

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
            echo '<tr>';
            echo '<td>' . $examName . '</td>';
            echo '<td>' . $examID . '</td>';
            echo '<td>' . $studentID . '</td>';
            echo '<td>' . $username . '</td>';
            echo '<td>' . $totalMarks . ' / ' . $examTotalMarks . '</td>';
            echo '</tr>';
          }
        }

        echo '</tbody>';
        echo '</table>';
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
