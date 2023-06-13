<!DOCTYPE html>
<html>
<head>
  <title>View Exam</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <style>
    body {
      background-color: #f8f9fa;
      padding: 20px;
    }

    .container {
      max-width: 800px;
      margin: 0 auto;
    }

    h1 {
      margin-bottom: 20px;
      text-align: center;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .exam-details {
      margin-top: 40px;
    }

    .exam-details p {
      margin-bottom: 10px;
    }

    .create-questions-btn {
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h1>View Exam</h1>
        <form action="view_questions.php" method="post">
          <div class="form-group">
            <label for="examId">Select an Exam:</label>
            <select class="form-control" id="examId" name="examId" required>
              <option value="">Select</option>
              <!-- Add PHP code to dynamically generate options based on the available exams -->
              <?php
              // Assuming you are using MySQL as the database

              // Connect to the database (replace the placeholders with your actual database credentials)
              $servername = "localhost";
              $username = "root";
              $password = "viki@2002";
              $dbname = "project";

              $conn = new mysqli($servername, $username, $password, $dbname);
              if ($conn->connect_error) {
                  die("Connection failed: " . $conn->connect_error);
              }

              // Retrieve the exams from the database
              $sql = "SELECT examId, examName FROM multipleexam";
              $result = $conn->query($sql);

              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  echo "<option value='" . $row["examId"] . "'>" . $row["examName"] . "</option>";
                }
              }

              $conn->close();
              ?>
            </select>
          </div>
          <button type="submit" class="btn btn-primary">View Exam</button>
        </form>
        <div class="exam-details">
          <?php
          // Check if a specific exam is selected
          if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['examId'])) {
            $selectedExamId = $_POST['examId'];

            // Connect to the database and retrieve the details of the selected exam
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT examId FROM multipleexam WHERE examId = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $selectedExamId);
            $stmt->execute();
            $stmt->bind_result($examId);
            $stmt->fetch();

            if ($examId) {
              echo "<p><strong>Exam ID:</strong> " . $examId . "</p>";
            } else {
              echo "<p>No exam found.</p>";
            }

            $stmt->close();
            $conn->close();
          }
          ?>
        </div>
        <?php
        // Check if a specific exam is selected
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['examId'])) {
          echo "<div class='create-questions-btn'>";
          echo "<a class='btn btn-primary' href='create_questions.php?examId=$selectedExamId'>Create Questions</a>";
          echo "</div>";
        }
        ?>
      </div>
    </div>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
