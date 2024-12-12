<?php 
session_start();

$base = '/quiz-game/';

require 'api.php';
require 'db.php';

if (isset($_SESSION['username']) && isset($_SESSION['room_id'])) {
    try {
        leaveRoom($_SESSION['username']);
        deleteRoom($_SESSION['room_id']);
    } catch (Exception $ex) {}
}

session_unset();
session_destroy();
header('Location: ' . $base . 'index.php');
?>
