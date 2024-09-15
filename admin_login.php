<?php
// Configuration
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'urban_music_promo';

// Create connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}

// Login functionality
if (_POST['username'];
_POST['password'];

$sql = "SELECT * FROM admins WHERE username = '$username' AND password = '$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
// Login successful, redirect to admin dashboard
header("Location: admin_dashboard.php");
exit;
} else {
echo "Invalid username or password";
}
}

$conn->close();
?>
