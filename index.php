<?php

$base = '/quiz-game/';

require 'server-side/api.php';
require 'server-side/db.php';
require 'server-side/session.php';

// request to join or host
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
       // get data from page
       $json = file_get_contents('php://input');
       $data = json_decode($json, true);
       $username = $data['username'];

       // join button
       if ($data['action'] == 'join') {
           $room_id = $data['room_id'];
           joinRoom($username, $room_id);

       // host button
       } else if ($data['action'] == 'host') {
          $room_id = hostRoom($username);
       }

       // succeeded to join or host
       session_regenerate_id();
       $_SESSION['username'] = $username;
       $_SESSION['room_id'] = $room_id;
       $_SESSION['active'] = TRUE;
       $_SESSION['score'] = 0;
       sendJSResponse(['status' => 'transfer', 'url' => $base . 'pages/lobby.php']);
       exit;

   // error message
    } catch (Exception $ex) {
       sendJSResponse(['status' => 'error', 'message' => $ex->getMessage()]);
       exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <base href="/quiz-game/">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Quiz Quest - Start Your Adventure</title>
        <link rel="stylesheet" href="CSS/style.css">
        <link rel="stylesheet" href="CSS/start.css">
        <script src="javascript/send_data.js" defer> </script>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <h1>Quiz Quest</h1>
                 <div class="btn-container">
                    <input placeholder="ENTER USERNAME" id="input-user" name="username">
                    <div class="btn submit" style="width: 300px; margin-bottom: 20px;">
                        HOST
                    </div>
                    <input placeholder="ENTER ROOM ID" id="input-user" name="room_id">
                    <div class="btn submit" style="width: 300px;">
                        JOIN
                    </div>
                    <div class="error-message" id="error-message"></div>
                </div>   
            </div>
        </div>
    <script>
        async function host () {
            const userName = document.getElementsByName("username")[0].value;
            if (!userName) {
                displayError("Username must be set to host.");
                return;
            }
            const data = {
                'username': userName,
                'action': 'host',
            };
            const res = await sendData(data, '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>');
            if (res.status == 'error') {
                console.log("helloooo");
                displayError(res.message);
            } else if (res.status == 'transfer') {
                console.log("helloooo");
                window.location.href = res.url;
            }
        }

        async function join () {
            const userName = document.getElementsByName("username")[0].value;
            const roomID = document.getElementsByName("room_id")[0].value;
            if (!userName) {
                displayError("Username must be set to join.");
                return;
            }

            if (!roomID) {
                displayError("Room ID must be set to join.");
                return;
            }
            const data = {
                'username': userName,
                'room_id': roomID,
                'action': 'join',
            };
            const res = await sendData(data, '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>');
            if (res.status === 'error') {
                displayError(res.message);
            } else if (res.status === 'transfer') {
                window.location.href = res.url;
            }
        }
        window.onload = function() {
            document.getElementsByClassName("submit")[0].addEventListener("click", () => { host(); });
            document.getElementsByClassName("submit")[1].addEventListener("click", () => { join(); });
        }
    </script>
    </body>
</html>
