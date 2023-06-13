<?php
// Perform necessary database connection and session handling
$db = new PDO('mysql:host=localhost;dbname=project', 'root', 'viki@2002');
// Retrieve the remaining time from the database for the current student
try {
  $stmt = $db->prepare("SELECT stop_timer FROM students WHERE student_id = :student_id");
  $stmt->bindParam(':student_id', $_SESSION['student_id']);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $remaining_time = isset($row['stop_timer']) ? $row['stop_timer'] : null;
  echo $remaining_time;
} catch (PDOException $e) {
  // Handle database errors
  // You can display an error message or redirect to an error page
  echo "Database error: " . $e->getMessage();
  exit();
}
?>
