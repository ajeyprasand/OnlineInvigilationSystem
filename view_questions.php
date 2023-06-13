<!DOCTYPE html>
<html>

<head>
  <title>Exam Taking Interface Dashboard</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <style>
    body {
      background-color: #f8f9fa;
      padding: 20px;
    }

    h1 {
      margin-bottom: 30px;
      text-align: center;
      color: #4e545c;
    }

    .container {
      max-width: 800px;
      margin: 0 auto;
    }

    .question-list {
      list-style-type: none;
      padding-left: 0;
    }

    .question-list li {
      margin-bottom: 30px;
      background-color: #fff;
      border-radius: 8px;
      padding: 20px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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

    .question-list h4 {
      margin-bottom: 15px;
      color: #4e545c;
    }

    .question-list span {
      font-weight: bold;
      color: #6c757d;
    }

    .btn-create-question {
      margin-top: 30px;
    }

    .search-bar {
      margin-bottom: 20px;
      position: relative;
    }

    .search-bar input[type="text"] {
      border: none;
      border-bottom: 1px solid #ced4da;
      border-radius: 0;
      box-shadow: none;
      padding-right: 30px;
      transition: width 0.3s ease-in-out;
    }

    .search-bar input[type="text"]:focus {
      border-color: #007bff;
      box-shadow: none;
      width: 200px;
    }

    .search-bar .search-image {
      position: absolute;
      top: 50%;
      left: 5px;
      transform: translateY(-50%);
      width: 20px;
      height: 20px;
      cursor: pointer;
      transition: all 0.3s ease-in-out;
    }

    .search-bar:hover .search-image {
      transform: translateY(-50%) translateX(-100%);
      opacity: 0;
    }

    .search-bar:hover input[type="text"] {
      width: 200px;
    }
  </style>
</head>

<body>
  <a href="invig_dashboard.php" class="homepage-link">Home</a>
  <h1>Exam Taking Interface Dashboard</h1>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h2>View Questions</h2>
        <div class="search-bar">
          <input type="text" id="question-search" placeholder="Enter question number" onkeyup="filterQuestions()">
          <img src="search (1).png" class="search-image" alt="Search">
        </div>
        <ul class="question-list">
          <?php
          // Connect to the database
          $conn = mysqli_connect("localhost", "root", "viki@2002", "project");
          if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
          }
          // Get the selected exam ID from the $_POST request
          if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['examId'])) {
            $selectedExamId = $_POST['examId'];

          // Get the questions from the database
          $sql = "SELECT qn_no, question, option_text1, option_text2, option_text3, option_text4, correct_answer
          FROM exams WHERE ExamID=?";
             $stmt = $conn->prepare($sql);
             $stmt->bind_param("s", $selectedExamId);
             $stmt->execute();
             $result = $stmt->get_result();

          // Check if the query was successful
          if ($result) {
            if (mysqli_num_rows($result) > 0) {
              // The query was successful, fetch the questions
              while ($row = mysqli_fetch_assoc($result)) {
                echo "<li class='question-item'>";
                echo "<h4>Question " . $row["qn_no"] . ": " . $row["question"] . "</h4>";
                echo "<input type='radio' name='question_" . $row["qn_no"] . "' value='" . $row["option_text1"] . "' disabled>" . $row["option_text1"] . "<br>";
                echo "<input type='radio' name='question_" . $row["qn_no"] . "' value='" . $row["option_text2"] . "' disabled>" . $row["option_text2"] . "<br>";
                echo "<input type='radio' name='question_" . $row["qn_no"] . "' value='" . $row["option_text3"] . "' disabled>" . $row["option_text3"] . "<br>";
                echo "<input type='radio' name='question_" . $row["qn_no"] . "' value='" . $row["option_text4"] . "' disabled>" . $row["option_text4"] . "<br>";
                echo "<span><b>Correct answer:</b> " . $row["correct_answer"] . "</span>";
                echo "<div>";
                echo "<button class='btn btn-primary' onclick='editQuestion(" . $row["qn_no"] . ")'>Edit</button>";
                echo "<button class='btn btn-danger' onclick='deleteQuestion(" . $row["qn_no"] . ")'>Delete</button>";
                echo "</div>";
                echo "</li>";
              }
            } else {
              // No questions were found
              echo "No questions found.";
            }
          } else {
            // The query failed
            echo "Query failed: " . mysqli_error($conn);
          }
          $stmt->close();
        }
          // Close the connection to the database
          mysqli_close($conn);
          ?>
        </ul>
        <button type="button" class="btn btn-secondary btn-create-question" onclick="createQuestion()">Create question</button>
      </div>
    </div>
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <script>
    function filterQuestions() {
      var input, filter, questionList, questionItems, i, txtValue;
      input = document.getElementById("question-search");
      filter = input.value.toUpperCase();
      questionList = document.getElementsByClassName("question-list")[0];
      questionItems = questionList.getElementsByClassName("question-item");

      for (i = 0; i < questionItems.length; i++) {
        txtValue = questionItems[i].getElementsByTagName("h4")[0].textContent || questionItems[i].getElementsByTagName("h4")[0].innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
          questionItems[i].style.display = "";
        } else {
          questionItems[i].style.display = "none";
        }
      }
    }

    function createQuestion() {
      window.location = "create_qn.php";
    }

    function editQuestion(questionNumber) {
      // Redirect to the edit question page with the question number as a parameter
      window.location = "edit_qn.php?qn_no=" + questionNumber;
    }

    function deleteQuestion(questionNumber) {
      // Confirm deletion with the user
      if (confirm("Are you sure you want to delete this question?")) {
        // Send an AJAX request to delete the question from the database
        $.ajax({
          url: "delete_qn.php",
          type: "POST",
          data: {
            qn_no: questionNumber
          },
          success: function(response) {
            // Reload the page to update the question list
            window.location="delete_qn.php?qn_no=" + questionNumber;
          },
          error: function(xhr, status, error) {
            console.error(xhr.responseText);
          }
        });
      }
    }
  </script>
</body>

</html>