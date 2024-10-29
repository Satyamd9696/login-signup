<?php
session_start();

 
$conn = new mysqli('localhost', 'root', '', 'user_system', '3307');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Register
if (isset($_POST['register'])) {
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (fullname, username, email, password) VALUES ('$fullname', '$username', '$email', '$password')";
     
    if ($conn->query($sql) === TRUE) {
        echo "New user registered successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Login
if (isset($_POST['login'])) {
    $email_username = $conn->real_escape_string($_POST['email_username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email_username' OR username='$email_username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user['username'];
            echo "Login successful! Welcome, " . $user['username'];
        } else {
            echo "Incorrect password!";
        }
    } else {
        echo "No user found with that email/username.";
    }
}

// Display Users
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User List</title>
</head>
<body>

  <h2>Registered Users</h2>

  <table border="1">
    <tr>
      <th>ID</th>
      <th>Full Name</th>
      <th>Username</th>
      <th>Email</th>
    </tr>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['fullname'] . "</td>";
            echo "<td>" . $row['username'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No users found</td></tr>";
    }
    ?>

  </table>

</body>
</html>
