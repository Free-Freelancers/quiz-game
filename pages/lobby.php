<?php

$base = '/quiz-game/';

include '../server-side/api.php';
include '../server-side/db.php';
include '../server-side/session.php';

$error = '';
$_SESSION["score"] = 0;
$_SESSION["ready"] = false;

checkQuery("SELECT * FROM rooms WHERE start_time IS NOT NULL AND room_id = " . $_SESSION['room_id']);
if ($row = $query_result->fetch_assoc()) {
    $_SESSION['start_time'] = $row['start_time'];
}

// if timer started
if (isset($_SESSION['start_time'])) {

        $currentTime = date('H:i:s');
        error_log($currentTime);
        error_log($_SESSION['start_time']);
    if ($currentTime > $_SESSION['start_time']) {
        // game already started (too late)
        /*header('Location: ' . $base . 'server-side/logout.php');*/
        /*die();*/
    }
}


// request to leave or to get ready
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    try {
       $json = file_get_contents('php://input');
       $data = json_decode($json, true);

       // ready button
       if (isset($data['ready'])) { 

            error_log("SELECT * FROM users WHERE username = " . $_SESSION['username']);
            checkQuery("SELECT * FROM users WHERE username = \"" . $_SESSION['username'] . "\"");
            $row = $query_result->fetch_assoc();

            error_log("yay");
            if ($row['ready'] == true) {
                // user is ready already
               sendJSResponse(['needed' => false]);
            } else {
                
               setReadyUser($_SESSION['username']);

               // check if all players in the room are ready
               if (readyRoom($_SESSION['room_id'])) {
                   startTimer();
                   sendJSResponse(['needed' => true]);
               }
            }

       // leave button
       } else if (isset($data['leave'])) { 
            leaveRoom($_SESSION['username']);
            
            // check if it is empty to delete it
            if (isEmptyRoom($_SESSION['room_id'])) {
                deleteRoom($_SESSION['room_id']);
            }

           // check if all players in the room are ready
           else if (readyRoom($_SESSION['room_id'])) {
               startTimer();
           }

           // back to home
           sendJSResponse(['goback' => true, 'url' => $base . 'server-side/logout.php']);


       // timer and players checker
       } else if (isset($data['check_ready'])) { 
            // players
            checkQuery("SELECT * FROM users WHERE room_id = " . $_SESSION['room_id']);
            $users = array();
            if ($query_result->num_rows > 0)
                while ($row = $query_result->fetch_assoc()) 
                    array_push($users, $row['username']);

            // timer
            $timer_started = false;
            checkQuery("SELECT * FROM rooms WHERE start_time IS NOT NULL AND room_id = " . $_SESSION['room_id']);
            if ($query_result->num_rows > 0) {
                $row = $query_result->fetch_assoc();
                $timer_started = true;
                $_SESSION['start_time'] = $row['start_time'];
            } else $_SESSION['start_time'] = '';

           sendJSResponse(['timerStarted' => $timer_started, 'startTime' => $_SESSION['start_time'],
                            'url' => $base . 'pages/game.php', 'users' => $users]);
       }

   // error
    } catch (Exception $ex) {
       $error = $ex->getMessage();
       error_log($error);
       exit;
    }
}

function startTimer () {
    global $query_result;
   checkQuery("SELECT * FROM rooms WHERE start_time IS NOT NULL AND Room_id = " . $_SESSION['room_id']);
   $_SESSION['start_time'] = $query_result->fetch_assoc()['start_time'];
}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
	  <base href="/quiz-game/">
	  <meta charset="UTF-8">
	  <meta name="viewport" content="width=device-width, initial-scale=1.0">
	  <link rel="stylesheet" href="CSS/style.css">
	  <link rel="stylesheet" href="CSS/lobby.css">
		<script src="javascript/script.js" defer></script>
		<script src="javascript/send_data.js" defer></script>
	  <title>Quiz Quest - Ready Up!</title>
	</head>

	<body>
		<div class="container">
			<div class="header">
            <h3><?php echo $_SESSION['username']; ?></h3>
			  <div class="avatar"><img src="IMG/images.jpg"></div>
			</div>
			<div class="room-info">
			<h1>ROOM ID <span>#<?php echo $_SESSION['room_id']; ?></span></h1>
			  <div class="copy-icon"><img src="IMG/copy.png" style="width: 20px; margin-left: 10px;"></div>
			</div>
			<div class="player-list">
			</div>
			<div class="start-timer">
			  <p>TIME TO START</p>
			  <p class="timer">--:--</p>
			</div>
			<div class="buttons">
			  <div class="btn" onclick="setReady()">READY</div>
			  <a id="openPopup">
				<div class="btn">EXIT</div>
			  </a>
			</div>

			</div>

			<div id="popup" class="popup">
			<div class="popup-content">
			  <span class="close" id="closePopup">&times;</span>
			  <h2>Are you sure you want to leave the game?</h2>

			  <div class="confirm-btns">
				  <div class="btn" style="width: 150px;  background-color: rgb(211, 63, 63);" onclick="removeUser()">
					<h3>YES</h3>
				  </div>
				<span class="close" id="closePopup">
				  <div class="btn" style="width: 150px;">
					<h3>NO</h3>
				  </div>
				</span>
			  </div>
			</div>
		</div>
        <script>
            async function setReady() {
                const data = {
                    'ready': true,
                };
                await sendData(data, '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>');
                return;
            }

            async function removeUser() {
                const data = {
                    'leave': true,
                };
                const res = await sendData(data, '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>');
                if (res.goback == true) {
                    window.location.href = res.url;
                }
            }

            function startCounting (startTime, url) {
                const interval = setInterval(function () {
                    monitorTime(startTime, url);
                }, 1000);
            }

            function monitorTime(deadline, go_to) {
                const futureTime = new Date(deadline).getTime();
                const now = new Date().getTime();
                const remainingTime = futureTime - now;
                if (remainingTime > 5 * 1000) {
                    seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);
                    minutes = Math.floor(seconds / 60);
                    seconds = seconds % 60;
                    console.log(`Time remaining: ${seconds} seconds`);
                    document.getElementsByClassName("timer")[0].innerText = (minutes < 10 ? '0' : '') + minutes + ":" + 
                                                                          (seconds < 10 ? '0' : '') + seconds;
                } else if (remainingTime > 2 * 1000) {
                    document.getElementsByClassName("timer")[0].innerText = "Get Ready";
                } else if (remainingTime > 0) {
                    document.getElementsByClassName("timer")[0].innerText = "Good Luck!";
                } else {
                    window.location.href = go_to;
                    return;
                }
            }

            window.onload = function() {
                document.getElementsByClassName("timer")[0].innerText = "--:--";

                const data = {
                    'check_ready': true,
                };

                const interval = setInterval(async function () {
                    const res = await sendData(data, '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>');

                    const playerList = document.getElementsByClassName('player-list')[0];
                    playerList.innerHTML = '';
                    for (let i = 1; i <= res.users.length; i++) {
                        let n = document.createElement('div') ;
                        n.className.concat("player") ;
                        n.innerHTML = '<div class="avatar red"><img src="IMG/images.jpg"></div><span class="player-name' + i + '">' + res.users[i - 1] + '</span>' ;
                        playerList.appendChild(n);
                    }

                    if (res.timerStarted == true) {
                        startCounting (res.startTime, res.url);
                    }

                }, 5000);
            };
        </script>
	</body>
</html>
