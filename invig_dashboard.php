<!DOCTYPE html>
<html>
<head>
  <title>Invigilator Dashboard</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <style>
    body {
      background-color: #f8f9fa;
      margin: 0;
      padding: 0;
    }

    h1 {
      margin-bottom: 0;
      text-align: center;
      color: #333;
    }

    .container {
      max-width: 800px;
      margin: 0 auto;
    }

    .dashboard-links {
      position: absolute;
      top: 150px;
      left: 0;
      display: flex;
      flex-direction: column;
      margin-top: 0;
    }

    .dashboard-links a {
      display: flex;
      align-items: center;
      padding: 10px;
      background-color: #f8f9fa;
      border: 1px solid #ccc;
      text-decoration: none;
      color: #333;
      transition: background-color 0.3s ease;
      margin-bottom: 0;
    }

    .dashboard-links a span {
      opacity: 1; /* Update opacity to 1 to make the text contents visible */
    }

    #left-corner-link {
      position: absolute;
      top: 10px;
      left: 10px;
      z-index: 999;
    }

    #left-corner-link a {
      display: inline-block;
      padding: 10px;
      background-color: #f8f9fa;
      border: 1px solid #ccc;
      text-decoration: none;
      color: #333;
      transition: background-color 0.3s ease;
    }

    #left-corner-link a:hover {
      background-color: #e9ecef;
    }

    #menu-button {
      position: absolute;
      top: 120px;
      left: 10px;
      z-index: 999;
    }

    #menu-button img {
      width: 20px;
      margin-right: 10px;
    }

    .dashboard-links img {
      width: 20px;
      height: 20px;
      margin-right: 10px;
    }

    /* New CSS for top and bottom frames */
    .top-frame {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      height: 85px;
      background-color: #007bff;
      border-bottom: 1px solid #ccc;
      z-index: 998;
    }

    .bottom-frame {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      height: 50px;
      background-color: #007bff;
      border-top: 1px solid #ccc;
      z-index: 998;
    }

    /* New CSS for responsive layout */
    @media (max-width: 767px) {
      .dashboard-links {
        position: relative;
        top: 0;
        left: 0;
        margin-top: 20px;
      }

      .dashboard-links a {
        margin-bottom: 5px;
      }

      #menu-button {
        top: 10px;
      }
    }

    .timer {
      color: #fff;
      font-size: 18px;
      margin-left: auto;
    }

    .header-right {
      display: flex;
      align-items: center;
      justify-content: flex-end;
      flex: 1;
      padding-right: 20px;
    }

    /* Show the text contents when the menu is open */
    .menu-open .dashboard-links a span {
      opacity: 1;
    }

    /* Style for the frame */
    .right-frame {
      position: fixed;
      top: 85px;
      right: 0;
      bottom: 50px;
      width: 300px;
      border: none;
    }
  </style>
</head>

<body>
  <div class="top-frame">
    <div class="container">
      <div class="row">
        <div class="col-md-12 text-center">
          <h1 style="color: white; margin: 0; white-space: nowrap;">Invigilator Dashboard</h1>
        </div>
        <div class="col-md-12 text-right header-right">
          <div class="timer" id="current-date-time"></div>
        </div>
      </div>
    </div>
  </div><!-- Add top frame -->

  <div id="left-corner-link">
    <a href="ui.php">Log out</a>
  </div>

  <div class="container">
    <!-- Embed Frontend Frameworks -->

    <div id="menu-button">
      <img src="Hamburger_icon.png" alt="Menu" onclick="toggleMenu()">
    </div>

    <div class="dashboard-links">
      <a href="create_exam.php" id="create-exam-link">
        <img src="cre_ex.png" alt="Create Exam">
        <span>Create Exam</span>
      </a>
      <a href="view_exam.php" id="view-exam-link">
        <img src="exam-icon.png" alt="View Exam">
        <span>View Exam</span>
      </a>
      <a href="view_students.php" id="view-students-link">
        <img src="students.png" alt="View Students">
        <span>View Students</span>
      <a href="view_feedback.php" id="view-students-link">
        <img src="students.png" alt="View Students">
        <span>View Results</span>
      </a>
    </div>
  </div>

  <div class="bottom-frame"></div> <!-- Add bottom frame -->

  <!-- Add the frame on the right side -->
  <iframe src="https://calendar.google.com/calendar/embed?height=600&wkst=1&bgcolor=%239E69AF&ctz=Asia%2FKolkata&src=OWQ2YjdjYzdiNjUwOWZjZmM3YzQ5NjEwZDE3YmFhNWYzZmQ3NGE5ZDYzYmJiNGE2Y2M4MzRjMDU4NzUwZjNlM0Bncm91cC5jYWxlbmRhci5nb29nbGUuY29t&color=%23EF6C00" style="border:solid 1px #777" width="500" height="400" frameborder="0" scrolling="no"class="right-frame"></iframe>


  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <script>
    var menuState = false; // Set the menu state to "closed" by default

    setInterval(updateDateTime, 1000);

    function toggleMenu() {
      var links = document.querySelector('.dashboard-links');

      menuState = !menuState; // Toggle the menu state

      if (menuState) {
        links.classList.add('menu-open'); // Add the "menu-open" class to show text contents
      } else {
        links.classList.remove('menu-open'); // Remove the "menu-open" class to hide text contents
      }
    }

    function hideTextContents() {
      var spanElements = document.querySelectorAll('.dashboard-links a span');
      spanElements.forEach(function (span) {
        span.style.opacity = '1';
      });
    }

    // Function to update date and time
    function updateDateTime() {
      var currentDate = new Date();
      var timerElement = document.getElementById('current-date-time');

      // Check if timer state is stored in local storage
      var timerState = localStorage.getItem('timerState');
      if (timerState) {
        var timerValues = timerState.split(':');
        hours = parseInt(timerValues[0]);
        minutes = parseInt(timerValues[1]);
        seconds = parseInt(timerValues[2]);
      }
      // Call the hideTextContents() function before updating the date and time
      hideTextContents();

      var options = {
        weekday: 'short',
        month: 'short',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        timeZone: 'Asia/Kolkata' // Set the desired timezone (GMT+0530)
      };
      var formattedDateTime = currentDate.toLocaleString('en-US', options);
      timerElement.textContent = formattedDateTime;
      // Increment the timer values
      seconds++;
      if (seconds === 60) {
        minutes++;
        seconds = 0;
      }
      if (minutes === 60) {
        hours++;
        minutes = 0;
      }

      // Store the current timer state in local storage
      localStorage.setItem('timerState', hours + ':' + minutes + ':' + seconds);

      // Call the updateDateTime() function after 1 second
      setTimeout(updateDateTime, 1000);
    }

    // Call the updateDateTime() function on page load
    document.addEventListener('DOMContentLoaded', function () {
      updateDateTime();
    });
  </script>
</body>
</html>
