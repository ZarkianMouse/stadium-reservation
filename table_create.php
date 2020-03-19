<?php
require_once "config.php";

// sql to create table
$sql = "CREATE TABLE Habit_Users (
userID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(10) UNIQUE,
password VARCHAR(10) NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Table Habit_Users created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>