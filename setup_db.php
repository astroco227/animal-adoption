<?php
$host = "localhost";
$username = "root";
$password = "";

// Connect to MySQL server (without specifying a database yet)
$conn = new mysqli($host, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Read the SQL file
$sql = file_get_contents('database.sql');

if ($conn->multi_query($sql)) {
    echo "<div style='font-family: sans-serif; text-align: center; margin-top: 50px;'>";
    echo "<h1 style='color: #2a9d8f;'>🎉 Database Setup Complete!</h1>";
    echo "<p>The animal adoption database and tables have been successfully created.</p>";
    echo "<a href='index.php' style='display: inline-block; padding: 10px 20px; background: #f26419; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px;'>Go to Homepage</a>";
    echo "<a href='admin/login.php' style='display: inline-block; padding: 10px 20px; background: #33658a; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px; margin-left: 10px;'>Go to Admin Login</a>";
    echo "</div>";
} else {
    echo "Error setting up database: " . $conn->error;
}

$conn->close(); 
?>
