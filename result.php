<?php
session_start();

// Check if the user has submitted a quiz
if (!isset($_SESSION['correct_answers']) || !isset($_SESSION['time_taken'])) {
    // Redirect to quiz page if there are no results
    header("Location: quiz.php"); // Change to the actual quiz page filename if needed
    exit;
}

// Get the results from session
$correct_answers = $_SESSION['correct_answers'];
$time_taken = $_SESSION['time_taken'];
$name = $_SESSION['name'];
$phone = $_SESSION['phone'];

// Clear session data for the next quiz
unset($_SESSION['correct_answers']);
unset($_SESSION['time_taken']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Result</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #eaeaea;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .result-container {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 600px;
            text-align: center;
        }
        h1 {
            color: #3498db;
            margin-bottom: 20px;
        }
        .result {
            margin-bottom: 20px;
            font-size: 18px;
            color: #333;
        }
        .button-container {
            margin-top: 20px;
        }
        .back-btn, .leaderboard-btn {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
            margin: 5px; /* Margin for spacing between buttons */
        }
        .back-btn:hover, .leaderboard-btn:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="result-container">
        <h1>Quiz Results</h1>
        <div class="result">Name: <?php echo htmlspecialchars($name); ?></div>
        <div class="result">Phone: <?php echo htmlspecialchars($phone); ?></div>
        <div class="result">Correct Answers: <?php echo $correct_answers; ?></div>
        <div class="result">Time Taken: <?php echo round($time_taken / 60, 2); ?> minutes</div>
        
        <div class="button-container">
            <a href="quiz.php" class="back-btn">Back to Quiz</a>
            <a href="leaderboard.php" class="leaderboard-btn">View Leaderboard</a>
        </div>
    </div>
</body>
</html>
