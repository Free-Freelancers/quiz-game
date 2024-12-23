<?php

$base = '/quiz-game/';

session_start();
error_reporting(E_ALL); // Report all types of errors
ini_set('display_errors', 1); // Display errors in the browser

// lost in the time and space
if ((isset($_SESSION['username']) && isset($_SESSION['room_id'])) ^ basename($_SERVER['SCRIPT_NAME']) != 'index.php') {
    error_log("logout");
    header ("Location: {$base}server-side/logout.php");
    die();
}

try {
    checkUsers();

    // confirming the user is not just a lost soul in this website
    if (isset($_SESSION['username'])) {
        if (!inRoom($_SESSION['username'], $_SESSION['room_id'])) { // DUCK OFF
            error_log("logout");
           header ("Location: {$base}server-side/logout.php");
           die();
        }
    }
} catch (Exception $ex) { }

function checkUsers () {
    try {
        if (isset($_SESSION['username'])) {
           if (inRoom($_SESSION['username'], $_SESSION['room_id'])) { 
               try {
                   validateUser($_SESSION['username']);
                    checkQuery("UPDATE users SET last_active = CURRENT_TIMESTAMP() WHERE room_id = {$_SESSION['room_id']} AND username = \"{$_SESSION['username']}\"");
               } catch (Exception $ex) {
                   session_unset();
               }
           }
        }
        $inactiveLimit = 1800; // 30 minutes
        $res = checkQuery("SELECT * FROM users");
        if ($res->num_rows > 0) {
            while ($user = $res->fetch_assoc()) {
                $last_activity = $user['last_active'];
                if (isset($last_activity) && (time() - strtotime($last_activity)) > $inactiveLimit) {
                    leaveRoom($user['username']);
                }
            }
        }


        // cleanup rooms
        $res = checkQuery("SELECT * FROM rooms");
        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                if ($row['user_count'] == 0)
                    deleteRoom($row['room_id']);
            }
        }
    } catch (Exception $ex) {
        die("Connection failed: " . $ex->getMessage());
    }
}

?>
