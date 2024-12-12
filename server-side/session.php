<?php

$base = '/quiz-game/';

session_start();
error_reporting(E_ALL); // Report all types of errors
ini_set('display_errors', 1); // Display errors in the browser

// start or resume the session
if (!isset($_SESSION['last_activity'])) {
    $_SESSION['last_activity'] = time();
}

// extreme double triple checking 
$good_to_go = false;
if (isset($_SESSION['username']) && isset($_SESSION['room_id'])) {
    try {
        // confirming the user is not just a lost soul in this website
        if (inRoom($_SESSION['username'], $_SESSION['room_id']))
            $good_to_go = true;
    } catch (Exception $ex) { }
    if (!$good_to_go) // DUCK OFF
    {
           header ('Location: ' . $base . 'server-side/logout.php');
           die();
    }
} else if (basename($_SERVER['SCRIPT_NAME']) == 'index.php') { // if user is homeless and is left on the index.php
    // do nothing :)
} else { 
    // send them flying
    header ('Location: ' . $base . 'server-side/logout.php');
    die();
}

$inactiveLimit = 1200; // 20 minutes
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $inactiveLimit) {
    header('Location: ' . $base . 'server-side/logout.php');
    die();
}

?>
