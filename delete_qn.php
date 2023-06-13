<!DOCTYPE html>
<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
</head>
<body>
<?php
if (isset($_GET['qn_no'])) {
    $questionNumber = $_GET['qn_no'];

    // Delete the question from the database based on the question number
    $conn = mysqli_connect("localhost", "root", "viki@2002", "project");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Get the total number of questions
    $sqlCount = "SELECT COUNT(*) AS total FROM exams";
    $resultCount = mysqli_query($conn, $sqlCount);
    $totalCount = mysqli_fetch_assoc($resultCount)['total'];

    // Delete the question
    $sqlDelete = "DELETE FROM exams WHERE qn_no = '$questionNumber'";
    $resultDelete = mysqli_query($conn, $sqlDelete);

    if ($resultDelete) {
        // Update the remaining question numbers
        $sqlRenumber = "UPDATE exams SET qn_no = qn_no - 1 WHERE qn_no > '$questionNumber'";
        mysqli_query($conn, $sqlRenumber);

        // If the deleted question was not the last one, update the auto-increment value
        if ($questionNumber < $totalCount) {
            $sqlAlterAutoIncrement = "ALTER TABLE exams AUTO_INCREMENT = $totalCount";
            mysqli_query($conn, $sqlAlterAutoIncrement);
        }

        // Question deleted successfully
        echo "<script src='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js'></script>";
        echo "<script>
            swal({
                title: 'Success!',
                text: 'Question deleted successfully.',
                icon: 'success',
                button: 'OK',
            }).then(function() {
                setTimeout(function() {
                    window.location.href = 'view_questions.php';
                }, 500);
            });
        </script>";
    } else {
        echo "<script src='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js'></script>";
        echo "<script>
            swal({
                title: 'Error!',
                text: 'Failed to delete the question: " . mysqli_error($conn) . "',
                icon: 'error',
                button: 'OK',
            }).then(function() {
                setTimeout(function() {
                    window.location.href = 'view_questions.php';
                }, 500);
            });
        </script>";
    }

    // Close the connection to the database
    mysqli_close($conn);
} else {
    echo "<script src='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js'></script>";
    echo "<script>
        swal({
            title: 'Error!',
            text: 'Question number not provided.',
            icon: 'error',
            button: 'OK',
        }).then(function() {
            setTimeout(function() {
                window.location.href = 'view_questions.php';
            }, 500);
        });
    </script>";
}
?>
</body>
</html>
