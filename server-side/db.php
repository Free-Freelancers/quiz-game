<?php

$base = '/quiz-game/';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quiz_game";

date_default_timezone_set('UTC'); // Change to your database's timezone

// create connection
$conn = new mysqli($servername, $username, $password, $dbname);
$query_result = "";

// check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// cleanup database
try {
    $result = checkQuery("SELECT * FROM rooms");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if ($row['user_count'] == 0)
                deleteRoom($row['room_id']);
        }
    }
} catch (Exception $ex) {
    $error = $ex->getMessage();
}
?>

