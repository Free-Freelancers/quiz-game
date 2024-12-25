<?php

$base = '/quiz-game/';

require '../server-side/api.php';
require '../server-side/db.php';
require '../server-side/session.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    try {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (isset($data['fetch_scores'])) {
            $room_id = $_SESSION['room_id'];
            $query = "SELECT username, score FROM users WHERE room_id = $room_id ORDER BY score DESC";
            $result = checkQuery($query);
            $users = [];
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
            sendJSResponse(['users' => $users]);
        }
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
    <link rel="stylesheet" href="CSS/score.css">

    <title>Scoreboard - Who's Winning?</title>
</head>

<body>
    <div class="container">
        <div class="game-header">
            <div class="right-section">
                <h3><?php echo $_SESSION['username']; ?></h3>
                <div class="avatar"><img src="IMG/images.jpg"></div>
            </div>
        </div>

        <div class="player-rank">
            <h1>
                you are <span class="rank">1</span>.
            </h1>
        </div>
        <div class="container-ground">
            <div class="player" id="score-container">
                <!-- Scores will be dynamically added here -->
            </div>

            <a href="<?php echo $base; ?>pages/lobby.php"><div class="btn" style="width: fit-content; justify-self: end;">Next</div></a>
        </div>
    </div>

    <script>
        async function sendData(data, url) {
            try {
                const response = await fetch(url, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(data),
                });

                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }

                const result = await response.json();
                return result;
            } catch (error) {
                console.error("Error:", error);
                return null;
            }
        }

        window.onload = function() {
            fetchScores();
        };

        async function fetchScores() {
            const res = await sendData({'fetch_scores': true}, '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>');
            const scoreContainer = document.getElementById('score-container');
            scoreContainer.innerHTML = ''; // Clear previous scores

            let userRank = 1;
            res.users.forEach(user => {
                const scoreDiv = document.createElement('div');
                scoreDiv.className = 'score';
                scoreDiv.innerHTML = `
                    <h3>${user.username}</h3> <span class="player-score">${user.score}</span>
                `;
                scoreContainer.appendChild(scoreDiv);

                if (user.username === '<?php echo $_SESSION['username']; ?>') {
                    document.querySelector('.rank').innerText = userRank;
                }
                userRank++;
            });
        }
    </script>
</body>

</html>
