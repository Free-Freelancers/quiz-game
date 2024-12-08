<?php
    function checkQuery ($qur) {
        global $conn;
        if ($conn->query($qur) === FALSE) {
            throw new Exception("Error: " . $sql . "<br>" . $conn->error");
        }
    }

    function createRoom () {
        global $conn;

        checkQuery("SELECT * FROM rooms");
        if ($conn->num_rows >= 10) {
            echo "maximum rooms created.";
            return;
        }
        
        checkQuery("INSERT INTO `rooms` (`room_id`) VALUES (NULL)");

        return $conn->insert_id;
    }

    function deleteRoom ($room_id) {
        global $conn;

        validateRoom($room_id);

        checkQuery("SELECT * FROM users WHERE room_id = \"'$room_id'\"");

        if (conn->num_rows > 0)
            throw new Exception ("can't delete room until it's empty");

        checkQuery("DELETE FROM rooms WHERE room_id = \"'$room_id'\"");
    }

    function validateNewUser ($username) {
        global $conn;
        checkQuery("SELECT * FROM users WHERE username = \"'$username'\"");
        if ($conn->num_rows > 0) {
            throw new Exception("username already exist.");
        }
    }

    function validateUser ($username) {
        global $conn;
        checkQuery("SELECT * FROM users WHERE username = \"'$username'\"");
        if ($conn->num_rows == 0) {
            throw new Exception("username does not exist.");
        }
    }

    function validateRoom ($room_id) {
        global $conn;

        checkQuery("SELECT * FROM rooms WHERE room_id = \"'$room_id'\"");

        if ($conn->num_rows <= 0) {
            throw new Exception("no room with that ID.");
        }
    }

    function join ($username, $room_id) {
        global $conn;

        validateNewUser($username);
        validateRoom($room_id);

        checkQuery("SELECT * FROM users WHERE room_id = \"'$room_id'\"");

        if ($conn->num_rows > 3) {
            throw new Exception("room fully occupied.");
        }
        
        checkQuery("INSERT INTO users (username, room_id) VALUES ('$username', '$room_id')");
    }

    function host ($username) {
        global $conn;
        validateNewUser($username);
        $room_id = createRoom();
        
        checkQuery("INSERT INTO users (username, room_id) VALUES ('$username', '$room_id')");
    }

    function leaveRoom ($username, $room_id) {
        global $conn;
        
        validateUser($username);
        validateRoom($room_id);
        
        checkQuery("SELECT * FROM users WHERE room_id = \"'$room_id'\"");

        if ($conn->num_rows == 0) {
            throw new Exception("no user with that username in this room");
        }

        checkQuery("DELETE FROM users WHERE room_id = \"'$room_id'\"");
    }

    function leaveRoom ($username, $room_id) {
        global $conn;
        
        validateUser($username);
        validateRoom($room_id);
        
        checkQuery("SELECT * FROM users WHERE room_id = \"'$room_id'\"");

        if ($conn->num_rows == 0) {
            throw new Exception("no user with that username in this room");
        }

        checkQuery("DELETE FROM users WHERE room_id = \"'$room_id'\"");
    }
?>

