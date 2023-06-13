<?php

// Connect to the database
$conn = mysqli_connect("localhost", "root", "viki@2002","project");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the questions from the database
$sql = "SELECT *
FROM exams
WHERE updated_at = (
    SELECT MAX(updated_at)
    FROM exams
)";
$result = mysqli_query($conn, $sql);

// Check if the query was successful
if ($result) {
    if (mysqli_num_rows($result) > 0) {
        // The query was successful, fetch the questions
        while ($row = mysqli_fetch_assoc($result)) {
            echo $row["question"] . " " . $row["option1"] . " " . $row["option2"] . " " . $row["option3"] . " " . $row["option4"] . " " . $row["correct_answer"] . "<br>";
        }
    } else {
        // No questions were found
        echo "No questions found.";
    }
} else {
    // The query failed
    echo "Query failed: " . mysqli_error($conn);
}

// Close the connection to the database
mysqli_close($conn);

?>
