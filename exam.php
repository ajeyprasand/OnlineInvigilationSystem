<?php
// Connect to the database
$db = new PDO('mysql:host=localhost;dbname=project', 'root', 'viki@2002');

// Start or resume the session
session_start();

if (isset($_SESSION['exam_completed']) && $_SESSION['exam_completed'] === true) {
    header('Location: success.php'); // Redirect to the results page
    exit;
}

// Check if the user is starting a new exam or if their details need to be added to the "students" table
if (!isset($_SESSION['student_id'])) {
    // Student is not starting a new exam, redirect to the login page
    //header("Location: stud_dashboard.php");
    $_SESSION['student_id'] = true;
} 
if (!isset($_SESSION['student_added'])) {
    // Insert the student details into the "students" table
    // Set the flag to indicate that the student details have been added
    $_SESSION['student_added'] = true;
}
if (isset($_GET['examID'])) {
    $examID = $_GET['examID'];
    // Rest of the code
}

// Retrieve the last attempted question number from the database
try {
    $stmt = $db->prepare("SELECT last_attempted_question FROM students WHERE student_id = :student_id AND examID = :examID");
    $stmt->bindParam(':student_id', $_SESSION['student_id']);
    $stmt->bindParam(':examID', $examID);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $last_attempted_question = isset($row['last_attempted_question']) ? $row['last_attempted_question'] : 1;
} catch (PDOException $e) {
    // Handle database errors
    // You can display an error message or redirect to an error page
    echo "Database error: " . $e->getMessage();
    exit();
}

// Get the current question number
$current_question = isset($_GET['question']) ? $_GET['question'] : $last_attempted_question;

// Check if the user is trying to access a previous question
if ($current_question < $last_attempted_question) {
    // Redirect to the current last attempted question the user has reached
    header("Location: exam.php?question=$last_attempted_question&examID=$examID");
    exit();
}

// Check if the exam has already been submitted
if (isset($_SESSION['exam_submitted']) && $_SESSION['exam_submitted']) {
    // Exam has already been submitted, redirect to the success page or display a message
    header("Location: success.php");
    exit();
}

// Update the last_attempted_question for the user in the database
try {
    $stmt = $db->prepare("UPDATE students SET last_attempted_question = :last_attempted_question WHERE student_id = :student_id AND examID = :examID");
    $stmt->bindParam(':last_attempted_question', $current_question);
    $stmt->bindParam(':student_id', $_SESSION['student_id']);
    $stmt->bindParam(':examID', $examID);
    $stmt->execute();
} catch (PDOException $e) {
    // Handle database errors
    // You can display an error message or redirect to an error page
    echo "Database error: " . $e->getMessage();
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get the selected answer from the submitted form
        $selected_option = isset($_POST['answer']) ? $_POST['answer'] : null;

        // Check if the exam has already been submitted
        if (isset($_SESSION['exam_submitted']) && $_SESSION['exam_submitted']) {
            // Exam has already been submitted, redirect to the success page or display a message
            header("Location: success.php");
            exit();
        }

        // Retrieve the option description and correct answer for the selected question
        $stmt = $db->prepare("SELECT correct_answer FROM exams WHERE qn_no = :question_number AND examID = :examID");
        $stmt->bindParam(':question_number', $current_question);
        $stmt->bindParam(':examID', $examID);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $correct_answer = isset($row['correct_answer']) ? $row['correct_answer'] : null;

        // Update the student's answer and marks in the database
        $selected_option = trim($selected_option);
        $correct_answer = trim($correct_answer);

        if (empty($selected_option)) {
            $selected_option = null;
        }

        $marks = ($selected_option !== null && $selected_option === $correct_answer) ? 1 : 0;
        $stmt = $db->prepare("INSERT INTO students (student_id, qn_num, crt_ans, stud_ans, marks, examID) VALUES (:student_id, :question_number, :correct_answer, :selected_option, :marks, :examID)");
        $stmt->bindParam(':student_id', $_SESSION['student_id']);
        $stmt->bindParam(':question_number', $current_question);
        $stmt->bindParam(':correct_answer', $correct_answer);
        $stmt->bindParam(':selected_option', $selected_option);
        $stmt->bindParam(':marks', $marks);
        $stmt->bindParam(':examID', $examID);
        $stmt->execute();

        // Move to the next unanswered question or finish the exam
        $next_question = getNextUnansweredQuestion($current_question, $last_attempted_question, $db, $examID);

        if ($next_question == null) {
            // Set the flag to indicate exam completion
            $_SESSION['exam_completed'] = true;
            header("Location: success.php");
            exit();
        } else {
            // Move to the next unanswered question
            $current_question = $next_question;
            header("Location: exam.php?question=$current_question&examID=$examID");
            exit();
        }
    } catch (PDOException $e) {
        // Handle database errors
        // You can display an error message or redirect to an error page
        echo "Database error: " . $e->getMessage();
        exit();
    }
}

// Retrieve the current question from the database for the specific exam ID
try {
    $stmt = $db->prepare("SELECT * FROM exams WHERE qn_no = :question_number AND examID = :examID");
    $stmt->bindParam(':question_number', $current_question);
    $stmt->bindParam(':examID', $examID);
    $stmt->execute();
    $question = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $db->prepare("SELECT COUNT(*) FROM exams WHERE examID = :examID");
    $stmt->bindParam(':examID', $examID);
    $stmt->execute();
    $total_questions = $stmt->fetchColumn();
    $_SESSION['total_questions'] = $total_questions;

    // Retrieve the student's answer for the current question, if any
    $stmt = $db->prepare("SELECT stud_ans, marks FROM students WHERE student_id = :student_id AND qn_num = :question_number AND examID = :examID");
    $stmt->bindParam(':student_id', $_SESSION['student_id']);
    $stmt->bindParam(':question_number', $current_question);
    $stmt->bindParam(':examID', $examID);
    $stmt->execute();
    $student_answer = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($student_answer && isset($student_answer['stud_ans']) && isset($student_answer['marks'])) {
        $selected_option = $student_answer['stud_ans'];
        $marks = $student_answer['marks'];
    } else {
        $selected_option = null;
        $marks = null;
    }
} catch (PDOException $e) {
    // Handle database errors
    // You can display an error message or redirect to an error page
    echo "Database error: " . $e->getMessage();
    exit();
}

// Check if the user is trying to access a previous question without completing all the questions
if ($current_question > $last_attempted_question && $last_attempted_question != 1 && $selected_option === null) {
    // Redirect to the current last attempted question the user has reached
    header("Location: exam.php?question=$last_attempted_question&examID=$examID");
    exit();
}

// Reset the "student_added" flag when the user logs out
if (isset($_GET['logout'])) {
    unset($_SESSION['student_added']);

    // Get the next unanswered question
    $next_question = getNextUnansweredQuestion($current_question, $last_attempted_question, $db, $examID);

    if ($next_question) {
        header("Location: exam.php?question=$next_question&examID=$examID");
        exit();
    } else {
        // No unanswered questions, redirect to a suitable page
        header("Location: no_unanswered_questions.php");
        exit();
    }
}

// Handle tab-switching detection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['tab_switch'])) {
        // Increment the tab_switch_counter for the current student
        try {
            $stmt = $db->prepare("UPDATE students SET tab_switch_counter = tab_switch_counter + 1 WHERE student_id = :student_id");
            $stmt->bindParam(':student_id', $_SESSION['student_id']);
            $stmt->execute();
        } catch (PDOException $e) {
            // Handle database errors
            // You can display an error message or redirect to an error page
            echo "Database error: " . $e->getMessage();
            exit();
        }
    }
}
// Check if the student has switched tabs three times
try {
    $stmt = $db->prepare("SELECT tab_switch_counter FROM students WHERE student_id = :student_id");
    $stmt->bindParam(':student_id', $_SESSION['student_id']);
    $stmt->execute();
    $tab_switch_counter = $stmt->fetchColumn();
} catch (PDOException $e) {
    // Handle database errors
    // You can display an error message or redirect to an error page
    echo "Database error: " . $e->getMessage();
    exit();
}

if ($tab_switch_counter >= 3) {
    // Set the marks field to 0 for the student
    try {
        $stmt = $db->prepare("UPDATE students SET marks = 0 WHERE student_id = :student_id");
        $stmt->bindParam(':student_id', $_SESSION['student_id']);
        $stmt->execute();
    } catch (PDOException $e) {
        // Handle database errors
        // You can display an error message or redirect to an error page
        echo "Database error: " . $e->getMessage();
        exit();
    }

    // Display a SweetAlert notification
    echo "
    <script>
      Swal.fire({
        title: 'Auto Submission',
        text: 'You have switched tabs more than three times. Your exam has been auto-submitted.',
        icon: 'warning',
        confirmButtonText: 'OK'
      });
    </script>
  ";
}
?>

<html>
<head>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Exam Interface</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script>
        // Disable caching and prevent going back to previous pages
        window.onload = function () {
            history.replaceState(null, null, location.href);
            window.onpopstate = function () {
                history.go(1);
            };
        };
        var tabSwitchCount = 0;
    var isFormSubmitted = false;

    // Listen for visibility change event
    document.addEventListener('visibilitychange', function () {
      if (document.visibilityState === 'hidden') {
        tabSwitchCount++;
        if (tabSwitchCount === 3 && !isFormSubmitted) {
          showSweetAlert();
        }
        else if(tabSwitchCount <=2  && tabSwitchCount != 3 && !isFormSubmitted)
        {
            showAlert();
        }
      }
    });

    // Show SweetAlert
    function showSweetAlert() {
      Swal.fire({
        title: 'Switched Tabs',
        text: 'Please submit the form',
        icon: 'info',
        showCancelButton: false,
        confirmButtonText: 'OK',
      }).then((result) => {
        if (result.isConfirmed) {
          redirectToSuccessPage();
        }
      });
    }
    function showAlert() {
        Swal.fire({
        title: 'Switched Tabs',
        text: 'Dont exceed more than 2 times',
        icon: 'info',
        showCancelButton: false,
        confirmButtonText: 'OK',
      }).then((result) => {
        if (result.isConfirmed) {
      // Reset the tabSwitchCount to 0
      tabSwitchCount = 0;
        }
      });
    }
    function redirectToExamPage() {
      isFormSubmitted = true;
      document.location.href = "exam.php";
    }

    // Redirect to success page
    function redirectToSuccessPage() {
      isFormSubmitted = true;
      document.location.href = "success.php";
    }
    </script>
  <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }

        h1 {
            margin-bottom: 30px;
        }

        .question-container {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .question-number {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .question-text {
            margin-bottom: 20px;
        }

        .options-list {
            padding-left: 20px;
        }

        .submit-btn {
            margin-top: 20px;
        }

        .logout-link {
            margin-top: 20px;
        }
    </style>
       <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>
<body>
<div class="container">
    <h1>Exam Interface</h1>
    <?php 
    // Set the total_questions value from the session
$total_questions = $_SESSION['total_questions'];

// ...

// Next, in the HTML section, display the values:
echo "Current Question: " . $current_question . "<br>";
echo "Total Questions: " . $total_questions;
    ?>
       <div class="question-container">
            <?php if (isset($_SESSION['exam_completed']) && $_SESSION['exam_completed'] === true) { ?>
                <p>You have already completed the exam. Please <a href="success.php">view your results</a>.</p>
            <?php } else { ?>
                <form method="POST" action="">
                    <div class="question-number">Question <?php echo $current_question; ?>:</div>
                    <div class="question-text"><?php if (is_array($question)) echo $question['QUESTION']; ?></div>
<ul class="options-list">
    <?php
    if (is_array($question)) {
        for ($i = 1; $i <= 4; $i++) {
            ?>
            <li>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="answer" id="option<?php echo $i; ?>" value="<?php echo $question['option_text' . $i]; ?>" <?php if ($selected_option === $question['option_text' . $i]) echo 'checked'; ?>>
                    <label class="form-check-label" for="option<?php echo $i; ?>">
                        <?php echo $question['option_text' . $i]; ?>
                    </label>
                </div>
            </li>
            <?php
        }
    }
    ?>
</ul>

                    <script>
                    // Disable right-click context menu
                    document.addEventListener('contextmenu', event => {
                        event.preventDefault();
                        // Display Sweet Alert notification
                        Swal.fire({
                            title: 'Oops!',
                            text: 'Right-clicking is not allowed in this exam.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    });
                    </script>

                    <?php if ($current_question != $total_questions) { ?>
                        <button class="btn btn-primary submit-btn" type="submit">Next</button>
                    <?php } else { ?>
                        <button class="btn btn-primary submit-btn" type="submit">Submit</button>
                    <?php } ?>
                </form>
            <?php } ?>
        </div>

        <p class="logout-link"><a href="logout.php">Logout</a></p>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
<?php
// Function to retrieve the next unanswered question// Function to get the next unanswered question
function getNextUnansweredQuestion($current_question, $last_attempted_question, $db, $examID) {
    $next_question = null;

    try {
        $stmt = $db->prepare("SELECT qn_no FROM exams WHERE qn_no > :current_question AND examID = :examID");
        $stmt->bindParam(':current_question', $current_question);
        $stmt->bindParam(':examID', $examID);
        $stmt->execute();
        $unanswered_questions = $stmt->fetchAll(PDO::FETCH_COLUMN);

        foreach ($unanswered_questions as $unanswered_question) {
            $stmt = $db->prepare("SELECT COUNT(*) FROM students WHERE student_id = :student_id AND qn_num = :question_number AND examID = :examID");
            $stmt->bindParam(':student_id', $_SESSION['student_id']);
            $stmt->bindParam(':question_number', $unanswered_question);
            $stmt->bindParam(':examID', $examID);
            $stmt->execute();
            $answered = $stmt->fetchColumn();

            if (!$answered) {
                $next_question = $unanswered_question;
                break;
            }
        }
    } catch (PDOException $e) {
        // Handle database errors
        // You can display an error message or redirect to an error page
        echo "Database error: " . $e->getMessage();
        exit();
    }

    return $next_question;
}
?>