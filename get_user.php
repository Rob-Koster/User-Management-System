<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_data";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_info = "";
$wipe_message = "";

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['username'])) {
    $user = $_GET['username'];

    $sql = "SELECT email, address FROM users WHERE username='$user'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $user_info .= "Email: " . $row["email"] . " - Address: " . $row["address"] . "<br>";
        }
    } else {
        $user_info = "No results found for the username: $user";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['username'])) {
        $user = $_POST['username'];
        $sql = "DELETE FROM users WHERE username='$user'";
        $message = "User '$user' deleted successfully";
    } else {
        $sql = "DELETE FROM users";
        $message = "All users deleted successfully";
    }

    if ($conn->query($sql) === TRUE) {
        $wipe_message = $message;
    } else {
        $wipe_message = "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<form method="get" action="<?php echo $_SERVER['PHP_SELF'];?>">
    Username: <input type="text" name="username" required>
    <input type="submit" value="Get User">
</form>

<div>
    <?php echo $user_info; ?>
</div>

<!-- Form to wipe the database or a specific user -->
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
    Username (leave blank to wipe all): <input type="text" name="username">
    <input type="submit" value="Wipe Database" onclick="return confirm('Are you sure you want to delete the specified user or all records?');">
</form>

<div>
    <?php echo $wipe_message; ?>
</div>
