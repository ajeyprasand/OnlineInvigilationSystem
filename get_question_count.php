<!DOCTYPE html>
<html>
<head>
  <title>Exam Taking Interface Dashboard</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  <style>
    /* CSS styles here */
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
          $dbname = 'project';
          $username = 'root';
          $password = 'viki@2002';

          try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Prepare the SQL query
            $query = "SELECT COUNT(*) AS question_count FROM EXAMS";

            // Execute the query
            $statement = $pdo->query($query);
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $questionCount = $result['question_count'];
          } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
          }

          // Check if the maximum question limit has been reached
          $maxQuestionLimit = 10;
          $isQuestionLimitReached = $questionCount >= $maxQuestionLimit;
        ?>
        <script>
          var isQuestionLimitReached = <?php echo $isQuestionLimitReached ? 'true' : 'false'; ?>;
          var maxQuestionLimit = <?php echo $maxQuestionLimit; ?>;
          var form = document.querySelector('form');

          function validateForm() {
            var question = document.getElementById("question").value;

            if (question.trim() !== "") {
              if (isQuestionLimitReached) {
                Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: 'You have exceeded the question limit. You can\'t create further questions.',
                });
                return false; // Prevent form submission
              }
            }

            return true; // Allow form submission
          }

          form.addEventListener('submit', validateForm);
        </script>
        <form action="submit_exam.php" method="post" onsubmit="return validateForm()">
          <div class="form-group">
            <label for="question">Question:</label>
            <input type="text" class="form-control" id="question" name="question" placeholder="Enter question">
          </div>
          <!-- Remaining form fields -->
          <button type="submit" class="btn btn-primary submit-btn">Submit</button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
