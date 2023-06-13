<?php
// Assuming you have already established a database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve the image data and student ID from the POST request
  $imageData = $_POST['image'];
  $id = $_POST['id']; // Assuming you have a form input named 'id' for the student ID

  // Remove the data URL prefix
  $imageData = str_replace('data:image/png;base64,', '', $imageData);
  $imageData = str_replace(' ', '+', $imageData);

  // Decode the base64-encoded image data
  $imageData = base64_decode($imageData);

  // Generate a unique filename for the image
  $filename = uniqid() . '.png';

  // Specify the directory where you want to store the images
  $imageDirectory = 'C:/wamp64/www/seproject/';

  // Write the image data to a file
  $filePath = $imageDirectory . $filename;
  file_put_contents($filePath, $imageData);

  // Read the image file as binary data
  $imageBinary = file_get_contents($filePath);

  // Prepare and execute the SQL query to store the image data in the "signup_students" table
  $sql = "UPDATE signup_students SET image = :image WHERE id = :id";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':image', $imageBinary, PDO::PARAM_LOB);
  $stmt->bindParam(':id', $id, PDO::PARAM_INT);
  $stmt->execute();

  // Respond with a success message or any other desired response
  echo 'Image stored successfully.';
} else {
  // Invalid request method
  echo 'Invalid request.';
}
?>
