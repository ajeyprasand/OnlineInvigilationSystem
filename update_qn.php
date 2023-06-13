<!DOCTYPE html>
<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
</head>
<body>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if all necessary fields are provided
    if (isset($_POST['qn_no']) && isset($_POST['question']) && isset($_POST['option_text1']) && isset($_POST['option_text2']) && isset($_POST['option_text3']) && isset($_POST['option_text4']) && isset($_POST['correct_answer'])) {
        $questionNumber = $_POST['qn_no'];
        $question = $_POST['question'];
        $option1 = $_POST['option_text1'];
        $option2 = $_POST['option_text2'];
        $option3 = $_POST['option_text3'];
        $option4 = $_POST['option_text4'];
        $correctAnswer = $_POST['correct_answer'];

        // Update the question in the database
        $conn = mysqli_connect("localhost", "root", "viki@2002", "project");
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        // Validate the data
        if (empty($question)) {
            echo "<script>
                swal({
                    title: 'Error!',
                    text: 'Please enter a question.',
                    icon: 'error',
                    button: 'OK',
                }).then(function() {
                    window.location.href = 'view_questions.php';
                });
            </script>";
            exit;
        }

        if (empty($option1) || empty($option2) || empty($option3) || empty($option4)) {
            echo "<script>
                swal({
                    title: 'Error!',
                    text: 'Please enter all options.',
                    icon: 'error',
                    button: 'OK',
                }).then(function() {
                    window.location.href = 'view_questions.php';
                });
            </script>";
            exit;
        }

        if (empty($correctAnswer)) {
            echo "<script>
                swal({
                    title: 'Error!',
                    text: 'Please enter a correct answer.',
                    icon: 'error',
                    button: 'OK',
                }).then(function() {
                    window.location.href = 'view_questions.php';
                });
            </script>";
            exit;
        }
        $sql = "UPDATE exams SET question='$question', option_text1='$option1', option_text2='$option2', option_text3='$option3', option_text4='$option4', correct_answer='$correctAnswer' WHERE qn_no='$questionNumber'";
        // Close the connection to the database
        mysqli_close($conn);

}
    // Display the success message
    echo "<script>
        swal({
            title: 'Success!',
            text: 'Question has been updated successfully!',
            icon: 'success',
            button: 'OK',
        }).then(function() {
            window.location.href = 'view_questions.php';
        });
    </script>";
}
?>
<script>
$(document).ready(function() {
    $("form").submit(function() {
        var question = $("input[name=question]").val();
        var option1 = $("input[name=option_text1]").val();
        var option2 = $("input[name=option_text2]").val();
        var option3 = $("input[name=option_text3]").val();
        var option4 = $("input[name=option_text4]").val();
        var correct_answer = $("input[name=correct_answer]").val();

        if (question == "" || option_text1 == "" || option_text2 == "" || option_text3 == "" || option_text4 == "" || correct_answer == "") {
            swal({
                title: 'Error!',
                text: 'Please fill all the fields.',
                icon: 'error',
                button: 'OK',
            });
            return false;
        }

        // Check if all of the textboxes are filled out
        if (question != "" && option_text1 != "" && option_text2 != "" && option_text3 != "" && option_text4 != "" && correct_answer != "") {
            swal({
                title: 'Success!',
                text: 'Question has been updated successfully!',
                icon: 'success',
                button: 'OK',
            }).then(function() {
                // Redirect the user to the create_qn.php page
                window.location.href = 'view_questions.php';
            });
        }

        return true;
    });
});
</script>

