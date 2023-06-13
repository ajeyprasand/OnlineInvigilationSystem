<?php
// Update the "students" table with the remaining time for the current student
try {
    // Connect to the database
    $db = new PDO('mysql:host=localhost;dbname=project', 'root', 'viki@2002');
    $stmt = $db->prepare("UPDATE students SET stop_timer = :stop_timer WHERE student_id = :student_id");
    $stmt->bindParam(':stop_timer', $_POST['remaining_time']);
    $stmt->bindParam(':student_id', $_SESSION['student_id']);
    $stmt->execute();
    echo "Timer value updated successfully";
} catch (PDOException $e) {
  // Handle database errors
  // You can display an error message or redirect to an error page
  echo "Database error: " . $e->getMessage();
  exit();
}
?>
