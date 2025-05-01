<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "beteseb_library"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<?php

