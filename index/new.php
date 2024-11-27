<?php
// Database connection settings
$servername = "localhost"; // Replace with your server name
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$dbname = "bmi_sports"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables for displaying results
$matchFound = false;
$index = "?";  // Default value
$game = "?";   // Default value

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gender = trim($_POST['gender']);
    $height = (int)trim($_POST['height']);
    $weight = (int)trim($_POST['weight']);

    // SQL query to find the matching data
    $sql = "SELECT * FROM players WHERE gender = ? AND height = ? AND weight = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $gender, $height, $weight);
    $stmt->execute();
    $result = $stmt->get_result();

    // If a match is found, fetch the data
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $index = $row['index_value'];
        $game = $row['game'];
        $matchFound = true;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BMI Sports Matching</title>
</head>
<body>
    <h1>Enter your details:</h1>
    <form method="POST" action="">
        Gender: <input type="text" name="gender" required><br>
        Height: <input type="number" name="height" required><br>
        Weight: <input type="number" name="weight" required><br>
        <input type="submit" value="Submit">
    </form>

    <h2>Results 1:</h2>
    <p>no.1 : Index: <?php echo $index; ?></p>
    <p>no.2 : Game: <?php echo $game; ?></p>

    <h2>Results 2:</h2>
    <p>no.1 : Index: <?php echo $index; ?></p>
    <p>no.2 : Game: <?php echo $game; ?></p>
</body>
</html>
