# User Management System

This project is a simple PHP-based user management system that allows you to add users, retrieve user information, and wipe user data from a MySQL database. It consists of two main pages:

1. `add_user.php` - For adding new users to the database.
2. `get_user.php` - For retrieving user information and wiping user data.

## Prerequisites

- PHP installed on your server.
- MySQL database set up with the `user_data` database and `users` table.
- Basic understanding of PHP and SQL.

## Database Setup

Create a database named `user_data` and a table named `users` with the following SQL script:

```sql
CREATE DATABASE user_data;

USE user_data;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    address VARCHAR(100) NOT NULL
);
```

## Files

### 1. `add_user.php`

This file allows you to add a new user to the `users` table.

```php
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
```

### 2. `get_user.php`

This file allows you to retrieve user information and wipe user data.

```php
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
```

## Usage

1. **Add User:**
    - Navigate to `add_user.php`.
    - Fill in the form with `Username`, `Email`, and `Address`.
    - Click the "Add User" button to add the user to the database.
    - Use the "Go to Get User" button to navigate to the `get_user.php` page.

2. **Get User Information:**
    - Navigate to `get_user.php`.
    - Enter a `Username` in the input field and click "Get User".
    - The user's email and address will be displayed below the input field.

3. **Wipe User Data:**
    - In `get_user.php`, enter a `Username` to delete a specific user or leave the field blank to delete all users.
    - Click the "Wipe Database" button and confirm the action.

## Security Considerations

- **Access Control:** Ensure only authorized users can access the pages, especially `get_user.php` with the wipe functionality.
- **Data Backup:** Always maintain proper backups of your database before allowing destructive operations.
- **Validation:** Consider adding more robust input validation and error handling.

## License

This project is open source and available under the MIT License.
