<?php
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
?>

<!DOCTYPE html>
<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
</head>
<body>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the POST data
    $question = $_POST['question'];
    $option_text1 = $_POST['option_text1'];
    $option_text2 = $_POST['option_text2'];
    $option_text3 = $_POST['option_text3'];
    $option_text4 = $_POST['option_text4'];
    $correct_answer = $_POST['correct_answer'];

    // Validate the data
    if (empty($question) || empty($option_text1) || empty($option_text2) || empty($option_text3) || empty($option_text4) || empty($correct_answer)) {
        echo "<script>
            swal({
                title: 'Error!',
                text: 'Please enter all fields.',
                icon: 'error',
                button: 'OK',
            }).then(function() {
                window.location.href = 'create_qn.php';
            });
        </script>";
        exit;
    }
    // Retrieve the ExamID from the appropriate table
    $previousExamID = 0; // Initialize the variable

    $examIDQuery = "SELECT ExamID FROM multipleexam ORDER BY ExamID DESC LIMIT 1";
    $examIDResult = $pdo->query($examIDQuery);
    if ($examIDResult && $examIDResult->rowCount() > 0) {
        $examIDRow = $examIDResult->fetch(PDO::FETCH_ASSOC);
        $previousExamID = $examIDRow['ExamID'];
        $examID = $previousExamID;
        $questionCount = 0;

        if ($previousExamID != $examID) {
            // Reset the question count and update total questions for the new exam ID
            $resetQuestionCountQuery = "UPDATE multipleexam SET questionCount = 1, totalQuestions = ? WHERE ExamID = ?";
            $resetQuestionCountStmt = $pdo->prepare($resetQuestionCountQuery);
            $resetQuestionCountStmt->execute([$questionCount, $previousExamID]);
        }
    }

    // Check for duplicate questions with the same correct answer within the same exam ID
    $duplicateQuery = "SELECT COUNT(*) AS duplicate_count FROM exams WHERE ExamID = ? AND correct_answer = ?";
    $duplicateStmt = $pdo->prepare($duplicateQuery);
    $duplicateStmt->execute([$previousExamID, $correct_answer]);
    $duplicateResult = $duplicateStmt->fetch(PDO::FETCH_ASSOC);
    $duplicateCount = $duplicateResult['duplicate_count'];

    if ($duplicateCount > 0) {
        echo "<script>
            swal({
                title: 'Error!',
                text: 'A question with the same correct answer already exists within the same exam.',
                icon: 'error',
                button: 'OK',
            }).then(function() {
                window.location.href = 'create_qn.php';
            });
        </script>";
        exit;
    }

    // Retrieve the maximum sequence number (qn_no) for the given ExamID
    $maxSequenceNumber = 0;
    $maxSequenceQuery = "SELECT MAX(qn_no) AS max_sequence FROM exams WHERE ExamID = ?";
    $maxSequenceStmt = $pdo->prepare($maxSequenceQuery);
    $maxSequenceStmt->execute([$previousExamID]);
    $maxSequenceResult = $maxSequenceStmt->fetch(PDO::FETCH_ASSOC);

    if ($maxSequenceResult && $maxSequenceResult['max_sequence'] !== null) {
        $maxSequenceNumber = $maxSequenceResult['max_sequence'];
    }

    // Increment the sequence number by 1
    $sequenceNumber = $maxSequenceNumber + 1;

    // Start a transaction
    $pdo->beginTransaction();

    try {
        // Save the data to the database
        $sql = "INSERT INTO exams (ExamID, qn_no, question, option_text1, option_text2, option_text3, option_text4, correct_answer) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(1, $previousExamID);
        $stmt->bindParam(2, $sequenceNumber);
        $stmt->bindParam(3, $question);
        $stmt->bindParam(4, $option_text1);
        $stmt->bindParam(5, $option_text2);
        $stmt->bindParam(6, $option_text3);
        $stmt->bindParam(7, $option_text4);
        $stmt->bindParam(8, $correct_answer);
        $stmt->execute();

        // Prepare the SQL query
        $questionCountQuery = "SELECT COUNT(QN_NO) AS question_count FROM exams WHERE ExamID = ?";
        $totalQuestionsQuery = "SELECT totalQuestions FROM multipleexam WHERE ExamID = ?";

        // Execute the query to get the question count
        $questionCountStmt = $pdo->prepare($questionCountQuery);
        $questionCountStmt->execute([$previousExamID]);
        $result = $questionCountStmt->fetch(PDO::FETCH_ASSOC);
        $questionCount = $result['question_count'];

        // Execute the query to get the total questions
        $totalQuestionsStmt = $pdo->prepare($totalQuestionsQuery);
        $totalQuestionsStmt->execute([$previousExamID]);
        $result = $totalQuestionsStmt->fetch(PDO::FETCH_ASSOC);
        $totalQuestions = $result['totalQuestions'];

        if ($questionCount <= $totalQuestions) {
            // Commit the transaction
            $pdo->commit();

            if ($questionCount < $totalQuestions) {
                // Display the success message
                echo "<script>
                    swal({
                        title: 'Success!',
                        text: '1 question created successfully! \\nQuestion Count: $questionCount / Total Questions: $totalQuestions',
                        icon: 'success',
                        button: 'OK',
                    }).then(function() {
                        window.location.href = 'create_qn.php';
                    });
                </script>";
            } else {
                echo "<script>
                    swal({
                        title: 'Success!',
                        text: 'All questions created successfully! \\nQuestion Count: $questionCount / Total Questions: $totalQuestions',
                        icon: 'success',
                        button: 'OK',
                    }).then(function() {
                        window.location.href = 'create_qn.php';
                    });
                </script>";
            }
        } else {
            echo "<script>
                swal({
                    title: 'Error!',
                    text: 'The question count exceeds the total questions for the exam.',
                    icon: 'error',
                    button: 'OK',
                }).then(function() {
                    window.location.href = 'create_qn.php';
                });
            </script>";
        }
    } catch (PDOException $e) {
        // Roll back the transaction on error
        $pdo->rollBack();
        die("Error: " . $e->getMessage());
    }
}
?>

</body>
</html>
