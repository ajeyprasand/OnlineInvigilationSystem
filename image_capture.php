<!DOCTYPE html>
<html>
<head>
  <title>Exam List</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <style>
    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
    }
    
    .container {
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
    
    .frame.top {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      height: 85px;
      border-bottom: 1px solid #ccc;
      background-color: #007bff;
      z-index: 998;
    }
    
    .frame.bottom {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      height: 50px;
      background-color: #007bff;
      border-top: 1px solid #ccc;
      z-index: 998;
    }
    
    .frame.middle {
      background-color: #ffffff;
      padding-top: 100px; /* Added padding to move it down */
    }
    
    .exam-box {
      width: 100%;
      max-width: 300px;
      background-color: #f8f9fa;
      border: 1px solid #ced4da;
      border-radius: 5px;
      padding: 10px;
      margin-bottom: 20px;
    }
    
    .exam-box h3, .exam-box p {
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
      margin: 0;
    }

    .start-button {
      margin-top: 10px;
    }
    
    .capture-button {
      margin-top: 10px;
    }
    
    #video-preview {
      width: 100%;
      max-width: 300px;
      margin-top: 20px;
    }
    
    #captured-image {
      width: 100%;
      max-width: 300px;
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="frame top">
      <h1>Exams Scheduled</h1>
    </div>
    <div class="frame middle">
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

      $sql = "SELECT * FROM multipleexam";

      try {
        $stmt = $pdo->query($sql);

        if ($stmt->rowCount() > 0) {
          // Display each exam in a rectangular box
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $examID = $row['ExamID'];
            $examName = $row['examName'];
            $examFormat = $row['examFormat'];
            $totalQuestions = $row['totalQuestions'];
            $examTotalMarks = $row['examTotalMarks'];
            $examDate = $row['examDate'];
            $examMode = $row['examMode'];
            ?>
            <div class="exam-box">
              <h3><?php echo $examName; ?></h3>
              <p><strong>Exam ID:</strong> <?php echo $examID; ?></p>
              <p><strong>Format:</strong> <?php echo $examFormat; ?></p>
              <p><strong>Total Questions:</strong> <?php echo $totalQuestions; ?></p>
              <p><strong>Total Marks:</strong> <?php echo $examTotalMarks; ?></p>
              <p><strong>Date:</strong> <?php echo $examDate; ?></p>
              <p><strong>Mode:</strong> <?php echo $examMode; ?></p>
              <button class="start-button btn btn-primary" onclick="startExam(<?php echo $examID; ?>)">Start</button> <!-- Modified Start button with onclick event -->
              <button class="capture-button btn btn-primary" onclick="captureImage()">Capture Image</button> <!-- Added Capture Image button -->
              <video id="video-preview" width="640" height="480" autoplay></video> <!-- Added video element -->
              <img id="captured-image" width="640" height="480" style="display: none;"> <!-- Added image element to display captured image -->
            </div>
            <?php
          }
        } else {
          echo 'No exams found.';
        }
      } catch (PDOException $e) {
        die("Error executing the query: " . $e->getMessage());
      }

      // Close the database connection
      $pdo = null;
      ?>
    </div>
    <div class="frame bottom">
      <!-- Bottom frame content goes here -->
    </div>
  </div>

  <!-- JavaScript code to redirect to exam.php with examID parameter and capture image from webcam -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script>
    function startExam(examID) {
      window.location.href = "exam.php?examID=" + encodeURIComponent(examID);
    }

    function captureImage() {
      const video = document.getElementById('video-preview');
      const canvas = document.createElement('canvas');
      const context = canvas.getContext('2d');
      const imageElement = document.getElementById('captured-image');

      // Set canvas dimensions equal to video dimensions
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;

      // Draw the current video frame onto the canvas
      context.drawImage(video, 0, 0, canvas.width, canvas.height);

      // Convert canvas to base64 encoded image
      const capturedImage = canvas.toDataURL('image/png');

      // Display the captured image
      imageElement.src = capturedImage;
      imageElement.style.display = 'block';

      // Stop the video stream
      video.srcObject.getTracks().forEach(function(track) {
        track.stop();
      });

      // Optional: Disable the capture button
      const captureButton = document.querySelector('.capture-button');
      captureButton.disabled = true;
    }

    // Access the webcam and display video preview
    navigator.mediaDevices.getUserMedia({ video: true })
      .then(function(stream) {
        const videoElements = document.querySelectorAll('#video-preview');

        videoElements.forEach(function(videoElement) {
          videoElement.srcObject = stream;
        });
      })
      .catch(function(error) {
        console.log('Error accessing the webcam: ', error);
      });
  </script>
</body>
</html>
