function startTimer(duration, onTimeUp) {
    const timeBar = document.querySelector('.time-bar');
    const startTime = Date.now();
    const endTime = startTime + duration * 1000; // Calculate the end time

    const interval = setInterval(() => {
        const currentTime = Date.now();
        const timeLeft = Math.ceil((endTime - currentTime) / 1000); // Calculate remaining time in seconds
        const percentage = Math.max(0, (timeLeft / duration) * 100); // Ensure percentage is not negative
        timeBar.style.width = percentage + '%';

        if (timeLeft <= 0) {
            clearInterval(interval);
            timeBar.style.width = '0%';
            onTimeUp();
        }
    }, 100); // Update more frequently for smoother animation
}

var popup = document.getElementById("popup");
var btn = document.getElementById("openPopup");
var span = document.getElementById("closePopup");

btn.onclick = function() {
    popup.style.display = "block";
}

span.onclick = function() {
    popup.style.display = "none";
}

document.querySelector('.confirm-btns .close').onclick = function() {
    popup.style.display = "none";
}

window.onclick = function(event) {
    if (event.target == popup) {
        popup.style.display = "none";
    }
}

function displayQuestion(index) {
    const question = questions[index];
    const questionContainer = document.querySelector('.question-section');
    const timeBar = document.querySelector('.time-bar');
    if (timeBar) {
        timeBar.style.width = '100%';
    }

    questionContainer.querySelector('.question-number').innerText = `Q.${index + 1}`;
    questionContainer.querySelector('.question-type').innerText = question.category_name;
    questionContainer.querySelector('.question h1').innerText = question.question_text;

    const mcqSection = questionContainer.querySelector('.MCQ-section');
    mcqSection.innerHTML = '';
    question.answers.forEach(answer => {
        const btn = document.createElement('div');
        btn.className = 'btn';
        btn.innerHTML = answer.answer_text;
        btn.onclick = () => checkAnswer(answer);
        mcqSection.appendChild(btn);
    });

    startTimer(10, () => {
        console.log("انتهى الوقت!");
        moveToNextQuestion();
    });
}

function moveToNextQuestion() {
    currentQuestionIndex++;
    if (currentQuestionIndex < questions.length) {
        displayQuestion(currentQuestionIndex);
    } else {
        endGame();
    }
}
