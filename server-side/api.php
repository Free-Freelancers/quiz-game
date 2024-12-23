<?php
function sendJSResponse($data) {
    header('Content-Type: application/json');
    $response = json_encode($data);
    error_log($response);
    echo $response;
    exit;
}

function checkQuery ($qur) {
    global $conn;
    global $query_result;
    $query_result = $conn->query($qur);
    if (!$query_result) {
        die("Error: " . $qur . "<br>" . $conn->error);
    }
    return $query_result;
}

function createRoom () {
    global $query_result;
    global $conn;

    checkQuery("SELECT * FROM rooms");
    if ($query_result->num_rows >= 10) {
        echo "maximum rooms created.";
        return;
    }
    
    checkQuery("INSERT INTO `rooms` (`room_id`) VALUES (NULL)");

    return $conn->insert_id;
}

function deleteRoom ($room_id) {
    global $query_result;

    validateRoom($room_id);

    checkQuery("SELECT user_count FROM rooms WHERE room_id = '$room_id'");

    if ($query_result->num_rows != 0 && $query_result->fetch_assoc()['user_count'] > 0)
        throw new Exception ("can't delete room until it's empty");

    checkQuery("DELETE FROM rooms WHERE room_id = '$room_id'");
}

function validateNewUser ($username) {
    global $query_result;

    if (strlen($username) <= 0) {
        throw new Exception("username can't be empty string.");
    }
    checkQuery("SELECT * FROM users WHERE username = '$username'");
    if ($query_result->num_rows > 0) {
        throw new Exception("username already exist.");
    }
}

function validateUser ($username) {
    global $query_result;

    if (strlen($username) <= 0) {
        throw new Exception("username can't be empty string.");
    }
    checkQuery("SELECT * FROM users WHERE username = '$username'");
    if ($query_result->num_rows == 0) {
        throw new Exception("username does not exist.");
    }
}

function validateRoom ($room_id) {
    global $query_result;

    checkQuery("SELECT * FROM rooms WHERE room_id = '$room_id'");

    if ($query_result->num_rows <= 0) {
        throw new Exception("no room with that ID.");
    }
}

function joinRoom ($username, $room_id) {
    global $query_result;

    validateNewUser($username);
    validateRoom($room_id);

    checkQuery("SELECT user_count FROM rooms WHERE room_id = '$room_id'");

    if ($query_result->num_rows != 0) {
        if($query_result->fetch_assoc()['user_count'] > 3)
            throw new Exception("room fully occupied.");
    }
    
    checkQuery("INSERT INTO users (username, room_id) VALUES ('$username', '$room_id')");
    checkQuery("UPDATE rooms SET user_count = user_count + 1, start_time = NULL WHERE room_id = '$room_id'");
}

function hostRoom ($username) {
    global $query_result;

    validateNewUser($username);
    $room_id = createRoom();
    
    checkQuery("INSERT INTO users (username, room_id) VALUES ('$username', '$room_id')");
    checkQuery("UPDATE rooms SET user_count = user_count + 1 WHERE room_id = '$room_id'");
    return $room_id;
}

function leaveRoom ($username) {
    global $query_result;
    
    validateUser($username);
    
    checkQuery("SELECT * FROM users WHERE username = '$username'");
    $room_id = $query_result->fetch_assoc()['room_id'];

    if ($query_result->num_rows == 0) {
        throw new Exception("no user with that username");
    }

    checkQuery("DELETE FROM users WHERE username = '$username'");
    checkQuery("UPDATE rooms SET user_count = user_count - 1 WHERE room_id = '$room_id'");
}

function setReadyUser ($username) {
    global $query_result;

    validateUser($username);

    error_log("naay");
    checkQuery("UPDATE users SET ready = true WHERE username = '$username'");
    checkQuery("SELECT * FROM users WHERE username = '$username'");
    $user = $query_result->fetch_assoc();
}

function readyRoom ($room_id) {
    global $query_result;

    validateRoom($room_id);

    checkQuery("SELECT * FROM users WHERE room_id = '$room_id' AND ready = false");
    if ($query_result->num_rows == 0) {
        error_log("yeay");
        checkQuery("SELECT * FROM rooms WHERE room_id = '$room_id'");
        /*if ($query_result->fetch_assoc()['start_time'] == null) {*/
        /*    checkQuery("UPDATE rooms SET start_time = CURRENT_TIMESTAMP() + INTERVAL 30 SECOND WHERE room_id = '$room_id'");*/
        /*}*/
        return true;
    } else {
        checkQuery("UPDATE rooms SET start_time = NULL WHERE room_id = '$room_id'");
        return false;
    }
}

function inRoom ($username, $room_id) {
    global $query_result;

    validateUser($username);
    validateRoom($room_id);

    checkQuery("SELECT * FROM users WHERE room_id = '$room_id' AND username = '$username'");
    if ($query_result->num_rows == 0) {
        return false;
    }
    return true;
}

function emptyRoom ($room_id) {
    global $query_result;

    validateRoom($room_id);

    checkQuery("DELETE FROM users WHERE room_id = '$room_id'");
    checkQuery("UPDATE rooms SET user_count = 0 WHERE room_id = '$room_id'");
}

function isEmptyRoom ($room_id) {
    global $query_result;

    validateRoom($room_id);

    checkQuery("SELECT * FROM users WHERE room_id = '$room_id'");
    if ($query_result->num_rows == 0)
        return true;
    return false;
}
?>

