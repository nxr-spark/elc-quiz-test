<?php
session_start();

// Check if user is logged in (name and phone must be set in session)
if (!isset($_SESSION['name']) || !isset($_SESSION['phone'])) {
    header("Location: index.php"); // Redirect to index page
    exit;
}

// Continue with the rest of your quiz code...

// Your existing quiz.php code follows
error_reporting(E_ALL);
ini_set('display_errors', 1);

// File paths for quiz data
$quiz_file = 'data/quiz.json';

// Check if quiz data file exists
if (!file_exists($quiz_file)) {
    die("Quiz data file does not exist.");
}

// Function to get quiz data
function get_quiz_data() {
    global $quiz_file;
    $data = json_decode(file_get_contents($quiz_file), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        die("Error reading quiz data: " . json_last_error_msg());
    }
    return $data;
}

// Function to save leaderboard data
function save_leaderboard_data($name, $phone, $time_taken, $correct_answers) {
    $leaderboard_file = 'data/leaderboard.json';
    $data = [];

    // Check if the file exists and read existing data
    if (file_exists($leaderboard_file)) {
        $data = json_decode(file_get_contents($leaderboard_file), true);
    }

    // Add new data
    $data[] = [
        'name' => $name,
        'phone' => $phone,
        'time_taken' => $time_taken,
        'correct_answers' => $correct_answers
    ];

    // Save updated data back to the file
    file_put_contents($leaderboard_file, json_encode($data));
}

// Function to save user data
function save_user_data($name, $phone, $correct_answers, $time_taken) {
    $user_data_file = 'data/user_data.json';
    $data = [];

    // Check if the file exists and read existing data
    if (file_exists($user_data_file)) {
        $data = json_decode(file_get_contents($user_data_file), true);
    }

    // Add new data
    $data[] = [
        'name' => $name,
        'phone' => $phone,
        'correct_answers' => $correct_answers,
        'time_taken' => $time_taken
    ];

    // Save updated data back to the file
    file_put_contents($user_data_file, json_encode($data));
}

// Handle quiz submission
if (isset($_POST['submit_quiz'])) {
    $quiz_data = get_quiz_data();
    $correct_answers = 0;

    // Check if quiz data is not empty
    if (empty($quiz_data)) {
        echo "<script>alert('No quiz data found.');</script>";
        exit;
    }

    // Loop through quiz data and check answers
    foreach ($quiz_data as $index => $quiz) {
        if (isset($_POST["q$index"]) && $_POST["q$index"] === $quiz['answer']) {
            $correct_answers++;
        }
    }

    $time_taken = time() - $_SESSION['start_time'];
    $name = $_SESSION['name'];
    $phone = $_SESSION['phone'];

    // Save leaderboard and user data
    save_leaderboard_data($name, $phone, $time_taken, $correct_answers);
    save_user_data($name, $phone, $correct_answers, $time_taken);

    // Store result in session
    $_SESSION['time_taken'] = $time_taken;
    $_SESSION['correct_answers'] = $correct_answers;

    header("Location: result.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz</title>
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
        .quiz-container {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 600px;
        }
        h1 {
            text-align: center;
            color: #3498db;
            margin-bottom: 20px;
        }
        .question {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #f9f9f9;
            transition: background-color 0.3s;
        }
        .question:hover {
            background-color: #eaeaea;
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: #333;
        }
        input[type="radio"] {
            margin-right: 10px;
        }
        .submit-btn {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
            width: 100%;
            transition: background-color 0.3s;
        }
        .submit-btn:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="quiz-container">
        <h1>Quiz Time!</h1>
        <form method="POST">
            <?php
            $quiz_data = get_quiz_data();
            if (empty($quiz_data)) {
                echo "<p>No quiz questions available.</p>";
            } else {
                foreach ($quiz_data as $index => $quiz): ?>
                    <div class="question">
                        <p><?php echo ($index + 1) . '. ' . htmlspecialchars($quiz['question']); ?></p>
                        <?php foreach ($quiz['answers'] as $answer): ?>
                            <label>
                                <input type="radio" name="q<?php echo $index; ?>" value="<?php echo htmlspecialchars($answer); ?>" required>
                                <?php echo htmlspecialchars($answer); ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            <?php } ?>
            <input type="submit" name="submit_quiz" class="submit-btn" value="Submit Quiz">
        </form>
    </div>
</body>
</html>
