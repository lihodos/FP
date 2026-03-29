<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db"; // ודאי שזה שם מסד הנתונים שלך

// יצירת חיבור
$conn = new mysqli($servername, $username, $password, $dbname);

// בדיקת חיבור
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
