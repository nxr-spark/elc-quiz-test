<?php
session_start();

// File paths for quiz data and user data
$quiz_file = 'data/quiz.json';
$leaderboard_file = 'data/leaderboard.json';
$user_data_file = 'data/user_data.json';

// Function to get quiz data
function get_quiz_data() {
    global $quiz_file;
    if (file_exists($quiz_file)) {
        return json_decode(file_get_contents($quiz_file), true);
    }
    return [];
}

// Function to check if a phone number has already taken the quiz
function has_taken_quiz($phone) {
    global $user_data_file;
    $user_data = file_exists($user_data_file) ? json_decode(file_get_contents($user_data_file), true) : [];
    foreach ($user_data as $user) {
        if ($user['phone'] === $phone) {
            return true;
        }
    }
    return false;
}

// User Info Form Submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['start_quiz'])) {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    
    if (!empty($name) && !empty($phone)) {
        if (!has_taken_quiz($phone)) {
            $_SESSION['name'] = htmlspecialchars($name);
            $_SESSION['phone'] = htmlspecialchars($phone);
            $_SESSION['start_time'] = time();
            header("Location: quiz.php");
            exit;
        } else {
            $error = "This phone number has already taken the quiz.";
        }
    } else {
        $error = "Name and phone number are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Application</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        h1 {
            text-align: center;
            color: #3498db;
            margin-bottom: 20px;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .start-btn {
            width: 100%;
            padding: 10px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .start-btn:hover {
            background: #2980b9;
        }
        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Start Quiz</h1>
        <?php if (isset($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="name" placeholder="Enter your name" required>
            <input type="text" name="phone" placeholder="Enter your phone number" required>
            <button type="submit" name="start_quiz" class="start-btn">Start Quiz</button>
        </form>
    </div>
</body>
</html>
