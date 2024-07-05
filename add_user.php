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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {
    $user = $_POST['username'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    $sql = "INSERT INTO users (username, email, address) VALUES ('$user', '$email', '$address')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
    Username: <input type="text" name="username" required>
    Email: <input type="email" name="email" required>
    Address: <input type="text" name="address" required>
    <input type="submit" name="add_user" value="Add User">
</form>

<!-- Button to navigate to get_user.php -->
<form action="get_user.php" method="get">
    <input type="submit" value="Go to Get User">
</form>
