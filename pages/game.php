<!DOCTYPE html>
<html lang="en">

<head>
    <base href="/quiz-game/">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="CSS/game.css">

    <title>Quiz Quest - Answer Now!</title>
</head>

<body>
    <div class="container ">


        <div class="game-header">
            <div class="left-section">
                <a id="openPopup"><img src="IMG/back.png" alt="Back-Icon" style="width: 20px;"></a>
            </div>
            <div class="right-section">
                <h3>USERNAME</h3>
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
                <a href="index.php">
                    <div class="btn" style="width: 150px;  background-color: rgb(211, 63, 63);"><h3>YES</h3></div>
                </a>
                <span class="close" id="closePopup"><div class="btn" style="width: 150px;"><h3>NO</h3></div></span>
            </div>

        </div>
    </div>


    <script src="/JS/script.js"></script>
</body>

</html>
