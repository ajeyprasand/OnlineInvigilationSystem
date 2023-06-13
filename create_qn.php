<!DOCTYPE html>
<html>
<head>
  <title>Exam Taking Interface Dashboard</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
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

    .form-group {
      margin-bottom: 20px;
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
    
    .submit-btn {
      width: 100%;
    }

    .card {
      margin-top: 40px;
    }
  </style>
</head>
<body>
  <a href="invig_dashboard.php" class="homepage-link">Home</a>
  <div class="container">
    <div class="card">
      <div class="card-body">
        <h1 class="card-title text-center">Create Questions</h1>
        <?php
        // Replace with your database connection code
        $host = 'localhost';
        $dbname ='project';
        $username='root';
        $password = 'viki@2002';

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Prepare the SQL query
            $questionCountQuery = "SELECT COUNT(QN_NO) AS question_count FROM exams";
            $totalQuestionsQuery = "SELECT totalQuestions FROM multipleexam";

            // Execute the query to get the question count
            $statement = $pdo->query($questionCountQuery);
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $questionCount = $result['question_count'];

            // Execute the query to get the total questions
            $statement = $pdo->query($totalQuestionsQuery);
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $totalQuestions = $result['totalQuestions'];

            // Check if the maximum question limit has been reached
            $isQuestionLimitReached = $questionCount >= $totalQuestions;
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
        ?>
        <form action="submit_exam.php" method="post" onsubmit="return validateForm()">
          <div class="form-group">
            <label for="question">Question:</label>
            <input type="text" class="form-control" id="question" name="question" placeholder="Enter question">
          </div>
          <div class="form-group">
            <label for="option1">Option 1:</label>
            <input type="text" class="form-control" id="option1" name="option_text1" placeholder="Enter option 1">
          </div>
          <div class="form-group">
            <label for="option2">Option 2:</label>
            <input type="text" class="form-control" id="option2" name="option_text2" placeholder="Enter option 2">
          </div>
          <div class="form-group">
            <label for="option3">Option 3:</label>
            <input type="text" class="form-control" id="option3" name="option_text3" placeholder="Enter option 3">
          </div>
          <div class="form-group">
            <label for="option4">Option 4:</label>
            <input type="text" class="form-control" id="option4" name="option_text4" placeholder="Enter option 4">
          </div>
          <div class="form-group">
            <label for="correct_answer">Correct Answer:</label>
            <input type="text" class="form-control" id="correct_answer" name="correct_answer" placeholder="Enter correct answer">
          </div>
          <button type="submit" class="btn btn-primary submit-btn">Submit</button>
        </form>
      </div>
    </div>
  </div>
  
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</body>
</html>
