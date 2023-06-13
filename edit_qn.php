<?php
// Check if the question number is provided as a parameter
if (isset($_GET['qn_no'])) {
    $questionNumber = $_GET['qn_no'];

    // Retrieve the question from the database based on the question number
    $conn = mysqli_connect("localhost", "root", "viki@2002", "project");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "SELECT * FROM exams WHERE qn_no = '$questionNumber'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        // Display the question details and provide the ability to modify them
?>
        <!DOCTYPE html>
        <html>

        <head>
            <title>Edit Question</title>
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
                        <h1>Edit Question <?php echo $questionNumber; ?></h1>
                        <form method="POST" action="update_qn.php">
                            <input type="hidden" name="qn_no" value="<?php echo $questionNumber; ?>">
                            <div class="form-group">
                                <label for="question">Question:</label>
                                <input type="text" class="form-control" id="question" name="question" placeholder="Enter question" value="<?php echo $row['QUESTION']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="option1">Option 1:</label>
                                <input type="text" class="form-control" id="option1" name="option_text1" placeholder="Enter option 1" value="<?php echo $row['option_text1']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="option2">Option 2:</label>
                                <input type="text" class="form-control" id="option2" name="option_text2" placeholder="Enter option 2" value="<?php echo $row['option_text2']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="option3">Option 3:</label>
                                <input type="text" class="form-control" id="option3" name="option_text3" placeholder="Enter option 3" value="<?php echo $row['option_text3']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="option4">Option 4:</label>
                                <input type="text" class="form-control" id="option4" name="option_text4" placeholder="Enter option 4" value="<?php echo $row['option_text4']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="correct_answer">Correct Answer:</label>
                                <input type="text" class="form-control" id="correct_answer" name="correct_answer" placeholder="Enter correct answer" value="<?php echo $row['CORRECT_ANSWER']; ?>">
                            </div>
                            <button type="submit" class="btn btn-primary submit-btn">Update Question</button>
                        </form>
                    </div>
                </div>
            </div>
        </body>

        </html>
<?php
    } else {
        echo "Question not found.";
    }

    // Close the connection to the database
    mysqli_close($conn);
} else {
    echo "Question number not provided.";
}
?>
