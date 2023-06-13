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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $examID = $_POST['examID'];
  $capturedImage = $_POST['capturedImage'];

  // Save the captured image in the student's table under the corresponding exam ID
  $sql = "UPDATE students SET image = :capturedImage WHERE ExamID = :examID";

  try {
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':capturedImage', $capturedImage);
    $stmt->bindValue(':examID', $examID);
    $stmt->execute();

    echo 'Captured image saved successfully.';
  } catch (PDOException $e) {
    echo 'Error saving captured image: ' . $e->getMessage();
  }
}

// Close the database connection
$pdo = null;
?>
