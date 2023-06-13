<!DOCTYPE html>
<html>
<head>
  <title>View Questions</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <style>
    .container {
      max-width: 800px;
      margin: 0 auto;
      padding: 20px;
    }

    h1 {
      margin-bottom: 20px;
      text-align: center;
    }

    .question {
      margin-bottom: 20px;
    }

    .question p {
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>View Questions</h1>

    <?php
    // Check if an exam ID is provided as a query parameter
    if (isset($_GET['examId'])) {
      $examId = $_GET['examId'];

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

      // Retrieve the questions for the given exam ID
      $query = "SELECT * FROM students WHERE examId = :examId";
      $stmt = $pdo->prepare($query);
      $stmt->bindParam(':examId', $examId);
      $stmt->execute();
      $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // Retrieve the questions for the given exam ID
      $query1 = "SELECT * FROM exams WHERE examId = :examId";
      $stmt = $pdo->prepare($query1);
      $stmt->bindParam(':examId', $examId);
      $stmt->execute();
      $questions1 = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // Merge the two arrays
      $mergedQuestions = array_merge($questions, $questions1);

      if (count($mergedQuestions) > 0) {
        // Display the questions
        foreach ($mergedQuestions as $question) {
            if (isset($question['QUESTION'])) {
              // This is from the 'exams' table
              $questionname = $question['QUESTION'];
              $options1 = $question['option_text1'];
              $options2 = $question['option_text2'];
              $options3 = $question['option_text3'];
              $options4 = $question['option_text4'];
          
              ?>
              <div class="question">
                <p><strong>Question:</strong> <?php echo $questionname; ?></p>
                <p><strong>a)</strong> <?php echo $options1; ?></p>
                <p><strong>b)</strong> <?php echo $options2; ?></p>
                <p><strong>c)</strong> <?php echo $options3; ?></p>
                <p><strong>d)</strong> <?php echo $options4; ?></p>
              </div>
              <?php
            }
            if (isset($question['qn_num'])) {
                // This is from the 'students' table
                $questionId = $question['qn_num'];
                $correctAnswer = $question['crt_ans'];
                $studentanswer=$question['stud_ans'];
                ?>
                <div class="question">
                  <p><strong>Question ID:</strong> <?php echo $questionId; ?></p>
                  <p><strong>Correct Answer:</strong> <?php echo $correctAnswer; ?></p>
                  <p><strong>Student's Response:</strong> <?php echo $studentanswer; ?></p>
                </div>
                <?php
              } 
          }
          
      } else {
        echo 'No questions found for the given exam ID.';
      }

      // Close the database connection
      $pdo = null;
    } else {
      echo 'No exam ID provided.';
    }
    ?>
  </div>
</body>
</html>
