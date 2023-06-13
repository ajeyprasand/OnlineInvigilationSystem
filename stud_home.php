<!DOCTYPE html>
<html>
<head>
    <title>Student Homepage</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <style>
        body {
            background-color: lightseagreen; /* Change the background color here */
            color: #ffffff;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 600px;
            margin: 100px auto;
            padding: 20px;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 36px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            text-shadow: 0 2px 2px rgba(0, 0, 0, .3);
            color: black;
        }

        ul {
            list-style-type: disc;
            margin-left: 20px;
            margin-bottom: 20px;
        }

        button[type="button"] {
            background-color: #cc3e3e;
            border: none;
            color: #ffffff;
            font-size: 16px;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color .3s;
            display: block;
            margin: 0 auto;
        }
        li {
            color: black;
        }
        button[type="button"]:hover {
            background-color: #ad3232;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 style="color:black">Welcome to GK Quiz</h1>
        <ul>
            <li>Exam has a total 6 Questions.</li>
            <li>No Negative marking in the exam.</li>
            <li>After clicking start, login the exam with your credentials.</li>
        </ul>
        <button type="button" onclick="window.location.href='exam.php'">Start</button>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
</body>
</html>
