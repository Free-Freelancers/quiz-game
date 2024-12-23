<?php

$base = '/quiz-game/';

require '../server-side/api.php';
require '../server-side/db.php';
require '../server-side/session.php';

$_SESSION["score"] = 0;
$_SESSION["playing"] = false;
$_SESSION["start_time"] = '';

checkQuery("SELECT * FROM rooms WHERE start_time IS NOT NULL AND room_id = " . $_SESSION['room_id']);
if ($row = $query_result->fetch_assoc()) {
    $_SESSION['start_time'] = $row['start_time'];
}

// if timer started
if (!empty($_SESSION['start_time'])) {
    if (time() > strtotime($_SESSION['start_time'])) {
        // game already started (too late)
            header('Location: ' . $base . 'server-side/logout.php');
        die();
    }
}

// request to leave or to get ready
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    try {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        // ready button
        if (isset($data['ready'])) { 

            checkQuery("SELECT * FROM users WHERE username = \"{$_SESSION['username']}\"");
             $row = $query_result->fetch_assoc();

             if ($row['ready'] == true) {
                 // user is ready already
                 
                sendJSResponse(['needed' => false]);
             } else {
                 // set user to be ready and check if room is ready
                 
                 setReadyUser($_SESSION['username']);

                 if (readyRoom($_SESSION['room_id'])) {
                    checkAllReady();
                    sendJSResponse(['needed' => true, 'startTime' => $_SESSION['start_time'],
                        'url' => $base . 'pages/game.php']);
                 }
             }

        // leave button
        } else if (isset($data['leave'])) { 

            // back to home
            sendJSResponse(['go_back' => true, 'url' => $base . 'server-side/logout.php']);


        // timer and players checker
        } else if (isset($data['update'])) { 

            // fill users array with player names
            checkQuery("SELECT * FROM users WHERE room_id = {$_SESSION['room_id']}");
            $users = array();
            if ($query_result->num_rows > 0)
               while ($row = $query_result->fetch_assoc()) 
                   array_push($users, $row['username']);

            // check if room start time is set and put it in start_time session variable
            $timer_started = checkAllReady();

            sendJSResponse(['timerStarted' => $timer_started, 'startTime' => $_SESSION['start_time'],
                             'url' => $base . 'pages/game.php', 'users' => $users]);

        // setting session as playing session
        } else if (isset($data['play'])) {
             $_SESSION['playing'] = true;
             sendJSResponse(['done' => true]);
        }

    // error
    } catch (Exception $ex) {
       error_log($ex->getMessage());
       exit;
    }
}

function checkAllReady() {
    $ready = readyRoom($_SESSION['room_id']);
    if ($ready) {
        $res = checkQuery("SELECT * FROM rooms WHERE room_id = {$_SESSION['room_id']}");
        if (is_null($res->fetch_assoc()['start_time'])) {
            $start_time = gmdate('Y-m-d H:i:s', time() + 30);
            checkQuery("UPDATE rooms SET start_time = '$start_time' WHERE room_id = {$_SESSION['room_id']}");
            $_SESSION['start_time'] = $start_time;
        }
    }
    return $ready;
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
            let monitorTimeCalled = false;
            const colors = ['#D33F3F', '#5F3FD3', '#D3983F', '#61D33F'];

            async function setReady() {
                const res = await sendData({'ready': true}, '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>');
                if (res.needed)
                    updateWithServer();
            }

            async function removeUser() {
                const res = await sendData({'leave': true}, '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>');
                if (res.go_back == true) {
                    window.location.href = res.url;
                }
            }

            async function monitorTime(deadline, go_to) {
                if (monitorTimeCalled) return;
                monitorTimeCalled = true;
                console.log(deadline);
                console.log(new Date().getTime());
                console.log(new Date(deadline + 'Z').getTime());
                const futureTime = new Date(deadline + 'Z').getTime();
                const interval = setInterval(async function () {
                    if (!monitorTimeCalled) {
                        clearInterval(interval);
                        return;
                    }

                    const now = Date.now();
                    const remainingTime = futureTime - now;
                    let seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);
                    let minutes = Math.floor(seconds / 60);
                    seconds = seconds % 60;
                    console.log(seconds);

                    if (remainingTime >= 5 * 1000) {
                        console.log(`Time remaining: ${seconds} seconds`);
                        document.getElementsByClassName("timer")[0].innerText = (minutes < 10 ? '0' : '') + minutes + ":" + 
                                                                              (seconds < 10 ? '0' : '') + seconds;
                    } else if (remainingTime > 2 * 1000) {
                        document.getElementsByClassName("timer")[0].innerText = "Get Ready" + '.'.repeat(5 - seconds);
                    } else if (remainingTime > 0) {
                        document.getElementsByClassName("timer")[0].innerText = "Good Luck!";
                    } else {
                        clearInterval(interval);
                        const res = await sendData({'play':true}, '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>');
                        console.log(res.done);
                        window.location.href = go_to;
                        return;
                    }
                }, 1000);
            }

            async function updateWithServer() {

                const res = await sendData({'update': true}, '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>');

                const playerList = document.getElementsByClassName('player-list')[0];
                playerList.innerHTML = '';
                for (let i = 0; i < res.users.length; i++) {
                    let n = document.createElement('div');
                    n.className.concat("player") ;
                    n.innerHTML = '<div class="avatar red" style="color: ' + colors[i] + '"><img src="IMG/images.jpg"></div><span class="player-name' + (i + 1) + '">' + res.users[i] + '</span>';
                    playerList.appendChild(n);
                }

                if (res.timerStarted == true) 
                    monitorTime(res.startTime, res.url);
                else {
                    monitorTimeCalled = false;
                    document.getElementsByClassName("timer")[0].innerText = "--:--";
                }
            }

            window.onload = function() {
                document.getElementsByClassName("timer")[0].innerText = "--:--";

                updateWithServer();

                const interval = setInterval(updateWithServer, 2000);
            };
        </script>
	</body>
</html>
