function resetTimer() {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        alert("You have been inactive for 10 minutes. You will be logged out.");
        // Optionally, you can send an AJAX request to the server to destroy the session
        fetch('room_countdown.php')
            .then(response => {
                if (response.redirected) {
                    window.location.href = response.url; // Redirect to index.php
                }
            });
    }, 10000); // 10 seconds in milliseconds
}
