<!DOCTYPE html>
<html>

<head>
  <title>Student List</title>
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

    table {
      width: 100%;
      border-collapse: collapse;
    }

    table thead th {
      background-color: #f8f9fa;
      border-bottom: 1px solid #dee2e6;
      padding: 10px;
      text-align: left;
    }

    table tbody td {
      border-bottom: 1px solid #dee2e6;
      padding: 10px;
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

    /* Additional Styles */
    .container {
      background-color: #fff;
      border-radius: 5px;
      padding: 20px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .table {
      margin-bottom: 0;
    }

    .image-cell {
      width: 100px;
    }

    .image-cell img {
      width: 100%;
      height: auto;
    }
  </style>
</head>

<body>
  <a href="invig_dashboard.php" class="homepage-link">Home</a>
  <h1>Student List</h1>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <table class="table table-striped table-bordered">
          <thead class="thead-dark">
            <tr>
              <th>Student ID</th>
              <th>Name</th>
              <th>Image</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // Connect to the database
            $conn = mysqli_connect("localhost", "root", "viki@2002", "project");

            // Check connection
            if (!$conn) {
              die("Connection failed: " . mysqli_connect_error());
            }

            // Fetch the total number of questions
            $questionsQuery = "SELECT COUNT(*) AS total_questions FROM exams";
            $questionsResult = mysqli_query($conn, $questionsQuery);
            $totalQuestions = mysqli_fetch_assoc($questionsResult)['total_questions'];

            // Fetch student ID, name, and image from the database
            $query = "SELECT students.student_id, signup_students.username, signup_students.image
                      FROM students
                      INNER JOIN signup_students ON students.student_id = signup_students.id
                      GROUP BY students.student_id
                      HAVING COUNT(*) = $totalQuestions";

            $result = mysqli_query($conn, $query);

            if ($result) {
              // Check if any rows were returned
              if (mysqli_num_rows($result) > 0) {
                // Output data for each student
                while ($row = mysqli_fetch_assoc($result)) {
                  echo "<tr>";
                  echo "<td>" . $row['student_id'] . "</td>";
                  echo "<td>" . $row['username'] . "</td>";
                  echo "<td class='image-cell'><img src='" . $row['image'] . "' alt='Student Image'></td>";
                  echo "</tr>";
                }
              } else {
                echo "<tr><td colspan='3'>No students found</td></tr>";
              }
            } else {
              // Display the error message
              echo "Error: " . mysqli_error($conn);
            }

            // Close the database connection
            mysqli_close($conn);
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>
