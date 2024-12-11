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
        displayError(error);
    }
}

function displayError(message) {
    const errorMessageElement = document.getElementById("error-message");
    if (!errorMessageElement) {
        console.log(message);
        return;
    }
    errorMessageElement.textContent = message;
    errorMessageElement.style.display = 'block';
}
