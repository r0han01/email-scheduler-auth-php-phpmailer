<?php
$servername = "sql212.infinityfree.com";  // Use the provided MySQL Hostname
$username = "if0_38090693";              // Use the provided MySQL Username
$password = "punIoz3SFaKbKwp";           // Use the provided MySQL Password
$dbname = "if0_38090693_users";          // Use the provided MySQL Database Name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// // Check connection
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }
// echo "Connected successfully";  // This line will confirm the successful connection
// ?>
