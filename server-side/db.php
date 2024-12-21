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

?>

