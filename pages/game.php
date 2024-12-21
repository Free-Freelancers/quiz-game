<?php

$base = '/quiz-game/';

require '../server-side/api.php';
require '../server-side/db.php';
require '../server-side/session.php';

$_SESSION["score"] = 0;

error_log($_SESSION['start_time']);
// if timer started
if (!empty($_SESSION['start_time'])) {
    if (time() < strtotime($_SESSION['start_time'])) {
    // to early to enter
    header('Location: ' . $base . 'server-side/logout.php');
    die();
    }
} else {
    header('Location: ' . $base . 'server-side/logout.php');
}

// request to leave or to get ready
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    try {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        // leave button
        if (isset($data['leave'])) { 
             leaveRoom($_SESSION['username']);
             
             // check if it is empty to delete it
             if (isEmptyRoom($_SESSION['room_id'])) {
                 deleteRoom($_SESSION['room_id']);
             }

            // back to home
            sendJSResponse(['go_back' => true, 'url' => $base . 'server-side/logout.php']);


        // timer and players checker
        } else if (isset($data['yanker'])) { 

        // timer and players checker
        } else if (isset($data['update'])) { 

             // fill users array with player names
             checkQuery("SELECT * FROM users WHERE room_id = " . $_SESSION['room_id']);
             $users = array();
             if ($query_result->num_rows > 0)
                 while ($row = $query_result->fetch_assoc()) 
                     array_push($users, ['username' => $row['username'], 'score' => $row['score']]);
            
            sendJSResponse(['users' => $users, 'question_timer' => "", 'question' => "", 'category' => "", 'answers' => "", 'question count' => ""]);

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

?>


<!DOCTYPE html>
<html lang="en">

    <head>
        <base href="/quiz-game/">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="CSS/style.css">
        <link rel="stylesheet" href="CSS/game.css">
        <script src="javascript/script.js" defer></script>
		<script src="javascript/send_data.js" defer></script>

        <title>Quiz Quest - Answer Now!</title>
    </head>

    <body>
        <div class="container">


            <div class="game-header">
                <div class="left-section">
                    <a id="openPopup"><img src="IMG/back.png" alt="Back-Icon" style="width: 20px;"></a>
                </div>
                <div class="right-section">
                    <h3><?php echo $_SESSION['username']; ?></h3>
                    <div class="avatar"><img src="IMG/images.jpg"></div>
                </div>
            </div>

            <div class="players-section">

                <div class="left">
                    <div class="player-score">
                        <div class="avatar"><img src="IMG/images.jpg"></div>
                        <div class="player-info">
                            <h4>Player1</h4>
                            <div class="player-bar">
                                <div class="player-health" style="background-color: rgb(211, 63, 63);"></div>
                            </div>
                        </div>
                    </div>

                    <div class="player-score">
                        <div class="avatar"><img src="IMG/images.jpg"></div>
                        <div class="player-info">
                            <h4>Player2</h4>
                            <div class="player-bar">
                                <div class="player-health" style="background-color: rgb(95, 63, 211);"></div>
                            </div>
                        </div>
                    </div>
                </div>

                

                <div class="right">
                    
                    <div class="player-score">
                        <div class="avatar"><img src="IMG/images.jpg"></div>
                        <div class="player-info">
                            <h4>Player3</h4>
                            <div class="player-bar">
                                <div class="player-health" style="background-color: rgb(211, 152, 63);"></div>
                            </div>
                        </div>
                    </div>

                    <div class="player-score">
                        <div class="avatar"><img src="IMG/images.jpg"></div>
                        <div class="player-info">
                            <h4>Player4</h4>
                            <div class="player-bar">
                                <div class="player-health" style="background-color: rgb(97, 211, 63);"></div>
                            </div>
                        </div>
                    </div>

                    
                    
                </div>
            </div>

            <div class="question-section">
                <div class="question-title-section">
                    <h1 class="question-number">Q.1</h4>
                        <h1 class=question-type>GENERAL</h1>
                </div>
                
                <div class="time-line"></div>

                <div class="question">
                    <h1>WHAT IS COLOR OF THE SKY</h1>
                </div>
                <div class="MCQ-section">
                    <div>
                        <div class="btn">A.<span>blue</span> </div>
                        <div class="btn">B. <span>red</span></div>
                    </div>
                    <div>
                        <div class="btn">C. <span>white</span></div>
                        <div class="btn">D. <span>Black</span></div>
                    </div>
                </div>

            </div>

        </div>


        <div id="popup" class="popup">
            <div class="popup-content">
                <span class="close" id="closePopup">&times;</span>
                <h2>Are you sure you want to leave the game?</h2>
                
                <div class="confirm-btns">
                    <div class="btn" style="width: 150px;  background-color: rgb(211, 63, 63);" onclick="removeUser()"><h3>YES</h3></div>
                    <span class="close" id="closePopup"><div class="btn" style="width: 150px;"><h3>NO</h3></div></span>
                </div>

            </div>
        </div>


        <script>
            const colors = ['#D33F3F', '#5F3FD3', '#D3983F', '#61D33F'];
            let monitorTimeCalled = false;
            let maxScore = 100;

            async function removeUser() {
                const res = await sendData({'leave': true}, '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>');
                if (res.go_back == true) {
                    window.location.href = res.url;
                }
            }

            async function updateWithServer() {

                const res = await sendData({'update': true}, '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>');
                console.log(res);

                const playerList = document.getElementsByClassName('players-section')[0];
                playerList.children[0].innerHTML = '';
                playerList.children[1].innerHTML = '';
                for (let i = 0; i < res.users.length; i++) {
                    let innerHTML = '<div class="player-score"> <div class="avatar"><img src="IMG/images.jpg"></div> <div class="player-info"> <h4>' + res.users[i].username + '</h4> <div class="player-bar"> <div class="player-health" style="width: ' + res.users[i].score / maxScore + '%"; background-color: ' + colors[i] + ';"></div> </div> </div> </div>';
                    if (i <= 2)
                        playerList.children[0].innerHTML += innerHTML;
                    else
                        playerList.children[1].innerHTML += innerHTML;
                }

            }

            window.onload = function() {
                updateWithServer();
                const interval = setInterval(updateWithServer, 5000);
            };
        </script>
    </body>

</html>
