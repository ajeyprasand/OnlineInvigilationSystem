<!DOCTYPE html>
<html>
<head>
  <title>Exam Taking Interface Dashboard</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
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
        <h1 class="card-title text-center">Create Exam</h1>
        <form action="submit_exam1.php" method="post" onsubmit="return validateForm()">
          <div class="form-group">
            <label for="examName">Name of the Exam:</label>
            <input type="text" class="form-control" id="examName" name="examName" placeholder="Enter the name of the exam" required>
          </div>
          <div class="form-group">
            <label for="examFormat">Exam Format:</label>
            <input type="text" class="form-control" id="examFormat" name="examFormat" placeholder="Enter the exam format" required>
          </div>
          <div class="form-group">
            <label for="totalQuestions">Total Questions in Exam:</label>
            <input type="number" class="form-control" id="totalQuestions" name="totalQuestions" placeholder="Enter the total number of questions" required>
          </div>
          <div class="form-group">
            <label for="examTotalMarks">Exam Total Marks:</label>
            <input type="number" class="form-control" id="examTotalMarks" name="examTotalMarks" placeholder="Enter the total marks of the exam" required>
          </div>
          <div class="form-group">
            <label for="examDate">Date of Exam:</label>
            <input type="date" class="form-control" id="examDate" name="examDate" required>
          </div>
          <div class="form-group">
            <label for="examMode">Mode of Exam:</label>
            <input type="text" class="form-control" id="examMode" name="examMode" placeholder="Enter the mode of the exam" required>
          </div>
          <button type="submit" class="btn btn-primary submit-btn">Create</button>
        </form>
      </div>
    </div>
  </div>
  
</body>
</html>
