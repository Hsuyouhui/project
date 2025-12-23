<?php
$servername = "localhost";
$dbname = "assets";
$dbUsername = "root";
$dbPassword = "";
  $conn = mysqli_connect($servername, $dbUsername, $dbPassword, $dbname);
  // Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
// Connection successful; avoid echoing here to prevent mixing output with page HTML/errors.

?>