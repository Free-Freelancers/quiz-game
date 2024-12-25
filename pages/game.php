<?php

$base = '/quiz-game/';

require '../server-side/api.php';
require '../server-side/db.php';
require '../server-side/session.php';

$_SESSION["score"] = 0;
/*
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
*/
// request to leave or to get ready
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    try {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if(isset($data['updateReady'])){

            $username = $_SESSION['username']; 

            $query = "UPDATE users SET Ready =0 WHERE username = '$username'";
           checkQuery($query);
          return;
        }
        else if (isset($data['update_score'])) {
            try{
            $username = $_SESSION['username']; 
            // تحديث النقاط في قاعدة البيانات
            $query = "UPDATE users SET score = score + 1 WHERE username = '$username'";
           checkQuery($query);

        

//error_log($query);
sendJSResponse(['success' => true]);
            }catch(Exception $e){
                sendJSResponse(['success' => false]);
            }
        }

       else if (isset($data['leave'])) { 
             leaveRoom($_SESSION['username']);
             
             // check if it is empty to delete it
             if (isEmptyRoom($_SESSION['room_id'])) {
                 deleteRoom($_SESSION['room_id']);
             }

            // back to home
            sendJSResponse(['go_back' => true, 'url' => $base . 'server-side/logout.php']);


        // timer and players checker
        } else if (isset($data['fetch'])) {
            if (!isset($_SESSION['questions'])) {
                $query = "
                    SELECT q.question_id, q.question_text, q.category_id, q.points, c.name as category_name, q.time_limit
                    FROM questions q
                    JOIN categories c ON q.category_id = c.category_id
                    ORDER BY RAND()
                    LIMIT 10
                ";
                $result = checkQuery($query);

                $questions = [];
                while ($row = $result->fetch_assoc()) {
                    $questionId = $row['question_id'];

                    $answersQuery = "
                        SELECT answer_id, answer_text, is_correct
                        FROM answers
                        WHERE question_id = $questionId
                    ";
                    $answersResult = checkQuery($answersQuery);

                    $answers = [];
                    while ($answerRow = $answersResult->fetch_assoc()) {
                        $answers[] = [
                            'answer_id' => $answerRow['answer_id'],
                            'answer_text' => $answerRow['answer_text'],
                            'is_correct' => $answerRow['is_correct']
                        ];
                    }

                    $questions[] = [
                        'question_id' => $questionId,
                        'question_text' => $row['question_text'],
                        'category_id' => $row['category_id'],
                        'points' => $row['points'],
                        'category_name' => $row['category_name'],
                        'time_limit' => $row['time_limit'], // Include time_limit
                        'answers' => $answers
                    ];
                }
                $_SESSION['questions'] = $questions;
            } else {
                $questions = $_SESSION['questions'];
            }
    
            $users = getUsersInRoom($_SESSION['room_id']);
            sendJSResponse([
                'users' => $users,
                'questions' => $questions,
                'question_timer' => "",
                'question_count' => count($questions)
            ]);
        // timer and players checker
        }else if (isset($data['update'])) { 
            $users = getUsersInRoom($_SESSION['room_id']);
            sendJSResponse(['users' => $users]);
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
function getUsersInRoom($roomId) {
    global $conn;
    $query = "SELECT username, score FROM users WHERE room_id = $roomId";
    $result = checkQuery($query);
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    return $users;
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

            <div class="players-section" id="player-container">
                <!-- Players will be dynamically added here -->
            </div>

            <div class="question-section">
                <div class="question-title-section">
                    <h1 class="question-number">Q.1</h4>
                    <h1 class=question-type>GENERAL</h1>
                </div>
                <div class="time-line">
                    <div class="time-bar"></div>
                </div>

                <div class="question">
                    <h1></h1>
                </div>
                <div class="MCQ-section">
                    <div>
                        <div class="btn">A.<span></span> </div>
                        <div class="btn">B. <span></span></div>
                    </div>
                    <div>
                        <div class="btn">C. <span></span></div>
                        <div class="btn">D. <span></span></div>
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
                    const playerContainer = document.getElementById('player-container');
                    playerContainer.innerHTML = '';
                    res.users.forEach((user, index) => {
                        let playerDiv = document.createElement('div');
                        playerDiv.className = 'player-score';
                        playerDiv.innerHTML = `
                            <div class="avatar">
                                <img src="IMG/images.jpg">
                            </div>
                            <div class="player-info">
                                <h4>${user.username}</h4>
                                <span class="player-score">Score: ${user.score}</span>
                                <div class="player-bar">
                                    <div class="player-health" style="width: ${user.score * 10}%; background-color: ${colors[index]};"></div>
                                </div>
                            </div>
                        `;
                        playerContainer.appendChild(playerDiv);
                    });
                }

                setInterval(updateWithServer, 1500); // Update every 1.5 seconds
               


            window.onload = function() {
                updateWithServer();
                const interval = setInterval(updateWithServer, 5000);
                startGame();
            };



let currentQuestionIndex = 0; // مؤشر السؤال الحالي
let score = 0; // النقاط
let questions = []; // مصفوفة لتخزين الأسئلة

async function startGame() {
    const res = await sendData({'fetch': true}, '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>');

    if (res.questions && res.questions.length > 0) {
        // تخزين الأسئلة في متغير عالمي
        questions = res.questions;
        displayQuestion(currentQuestionIndex);
        displayPlayerInfo(res.users); // عرض معلومات اللاعب
    } else {
        console.error("لا توجد أسئلة متاحة.");
    }
}

function displayPlayerInfo(users) {
    // افترض أن لديك متغير currentPlayerIndex لتحديد اللاعب الحالي
    let currentPlayerIndex = 0; // يمكنك تغيير هذا إلى أي فهرس تريده

    // تأكد من أن فهرس اللاعب الحالي ضمن نطاق المستخدمين
    if (currentPlayerIndex < users.length) {
        let user = users[currentPlayerIndex];
        let innerHTML = `
            <div class="player-score">
                <div class="avatar">
                    <img src="IMG/images.jpg" alt="${user.username}">
                </div>
                <div class="player-info">
                    <h4>${user.username}</h4>
                    <div class="player-bar">
                        <div class="player-health" style="width: ${user.score / maxScore * 100}%; background-color: ${colors[currentPlayerIndex]};"></div>
                    </div>
                </div>
            </div>`;
        
        // أضف innerHTML إلى عنصر معين في الصفحة
        document.getElementById('player-container').innerHTML = innerHTML; // تأكد من أن لديك عنصر بهذا المعرف
    }
}

function displayQuestion(index) {
    const questionContainer = document.getElementsByClassName('question-section')[0];
    const question = questions[index];

    // تحديث عنوان السؤال
    questionContainer.querySelector('.question-number').innerText = `Q.${index + 1}`;
    questionContainer.querySelector('.question-type').innerText = question.category_name;
    questionContainer.querySelector('.question h1').innerText = question.question_text;

    // تحديث خيارات الإجابة
    const mcqSection = questionContainer.querySelector('.MCQ-section');
    mcqSection.innerHTML = ''; // مسح الخيارات السابقة

    question.answers.forEach(answer => {
        const btn = document.createElement('div');
        btn.className = 'btn';
        btn.innerHTML = `${answer.answer_text}`;
        
        // تمرير القيمة is_correct إلى checkAnswer
        btn.onclick = () => checkAnswer(answer);
        mcqSection.appendChild(btn);
    });
}

function checkAnswer(ans) {
    if (ans.is_correct === '1') {
        score++; // زيادة النقاط في الذاكرة
        updateUserScore(); // تحديث النقاط في قاعدة البيانات
    } else {
        console.log("إجابة خاطئة:", ans);
    }

    currentQuestionIndex++; // الانتقال إلى السؤال التالي

    if (currentQuestionIndex < questions.length) {
        displayQuestion(currentQuestionIndex); // عرض السؤال التالي
    } else {
        endGame(); // إنهاء اللعبة
    }
}

async function updateUserScore() {
    const res = await sendData({'update_score': true}, '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>');
    if (res.success) {
        console.log("تم تحديث النقاط بنجاح.");
    } else {
        console.error("فشل تحديث النقاط.");
    }
}

function endGame() {
    sendData({'updateReady': true}, '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>').then(() => {
        window.location.href = '<?php echo $base; ?>pages/score.php';
    });
}

// استدعاء دالة بدء اللعبة عند تحميل الصفحة
window.onload = function() {
    startGame();
    
};


        </script>
    </body>

</html>
