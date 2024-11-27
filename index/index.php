<?php
// Function to read the CSV file and return the data as an array
function readCSV($filename) {
    $data = array();
    if (($handle = fopen($filename, "r")) !== FALSE) {
        while (($row = fgetcsv($handle)) !== FALSE) {
            $data[] = $row;
        }
        fclose($handle);
    }
    return $data;
}

// Function to check if user input matches any entry in the CSV
function checkBMI($gender, $height, $weight) {
    // Path to your CSV file
    $filename = 'bmisports.csv';

    // Read the CSV data
    $csvData = readCSV($filename);

    // Loop through each row in the CSV to find a match
    foreach ($csvData as $row) {
        // Check if Gender, Height, and Weight match
        if ($row[0] == $gender && $row[1] == $height && $row[2] == $weight) {
            return "Match found: Gender is {$row[0]}, Height is {$row[1]}, Weight is {$row[2]}, Game: {$row[4]}";
        }
    }

    return "No match found.";
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user input
    $gender = $_POST['gender'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];

    // Check for a match
    $result = checkBMI($gender, $height, $weight);
    // Check if it's a match or not
    $isMatch = strpos($result, 'Match found') !== false;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BMI Sports Check</title>
    <!-- Link to the external CSS file -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <h1>BMI Sports Check</h1>

        <form method="POST">
            <label for="gender">Gender:</label>
            <select name="gender" id="gender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select><br>

            <label for="height">Height (cm):</label>
            <input type="number" name="height" id="height" required><br>

            <label for="weight">Weight (kg):</label>
            <input type="number" name="weight" id="weight" required><br>

            <input type="submit" value="Check">
        </form>

        <?php
        // Display the result if available
        if (isset($result)) {
            // Display success or error box based on the result
            if ($isMatch) {
                echo "<div class='result-box'>$result</div>";
            } else {
                echo "<div class='error-box'>$result</div>";
            }
        }
        ?>
    </div>

</body>
</html>
