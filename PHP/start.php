<?php
    include 'db.php';
    include 'api.php';
    $error = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {
            $username = $_POST['username'];
            if ($_POST['action'] == 'join') {
            $room_id = $_POST['room_id'];
                join($username, $room_id);
            } else if ($_POST['action'] == 'host') {
                host($username);
            }
        } catch (Exception $ex) {
            $error = $ex->getMessage();
        }

    }
?>
