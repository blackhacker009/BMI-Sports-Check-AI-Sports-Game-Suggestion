<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

$servername = "localhost"; // Change if using a different host
$username = "root";        // Your database username
$password = "";            // Your database password
$dbname = "bmisports_db";  // Your database name

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle delete action
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM bmi_results WHERE id = $id");
}

// Handle edit action
if (isset($_POST['edit'])) {
    $id = (int)$_POST['id'];
    $gender = $_POST['gender'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $index_value = $_POST['index_value'];
    $game = $_POST['game'];

    $conn->query("UPDATE bmi_results SET gender='$gender', height=$height, weight=$weight, index_value=$index_value, game='$game' WHERE id=$id");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
</head>
<body>
    <h1>Welcome to Admin Panel</h1>
    <a href="logout.php">Logout</a>
    <h2>Stored Data in Database</h2>
    <table border="1">
        <tr>
            <th>Gender</th>
            <th>Height (cm)</th>
            <th>Weight (kg)</th>
            <th>Index</th>
            <th>Game</th>
            <th>Actions</th>
        </tr>
        <?php
        // Fetch and display data from the database
        $result = $conn->query("SELECT * FROM bmi_results");

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['gender']) . "</td>
                        <td>" . htmlspecialchars($row['height']) . "</td>
                        <td>" . htmlspecialchars($row['weight']) . "</td>
                        <td>" . htmlspecialchars($row['index_value']) . "</td>
                        <td>" . htmlspecialchars($row['game']) . "</td>
                        <td>
                            <a href='?edit=" . $row['id'] . "'>Edit</a> | 
                            <a href='?delete=" . $row['id'] . "'>Delete</a>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No data found</td></tr>";
        }
        ?>
    </table>

    <?php
    // Edit form
    if (isset($_GET['edit'])) {
        $id = (int)$_GET['edit'];
        $result = $conn->query("SELECT * FROM bmi_results WHERE id = $id");
        $row = $result->fetch_assoc();
    ?>

    <h3>Edit Record</h3>
    <form method="POST" action="">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

        <label for="gender">Gender:</label><br>
        <input type="text" id="gender" name="gender" value="<?php echo htmlspecialchars($row['gender']); ?>" required><br><br>

        <label for="height">Height (cm):</label><br>
        <input type="number" id="height" name="height" value="<?php echo htmlspecialchars($row['height']); ?>" required><br><br>

        <label for="weight">Weight (kg):</label><br>
        <input type="number" id="weight" name="weight" value="<?php echo htmlspecialchars($row['weight']); ?>" required><br><br>

        <label for="index_value">Index:</label><br>
        <input type="number" id="index_value" name="index_value" value="<?php echo htmlspecialchars($row['index_value']); ?>" required><br><br>

        <label for="game">Game:</label><br>
        <input type="text" id="game" name="game" value="<?php echo htmlspecialchars($row['game']); ?>" required><br><br>

        <input type="submit" name="edit" value="Save Changes">
    </form>

    <?php } ?>

</body>
</html>

<?php
$conn->close();
?>
