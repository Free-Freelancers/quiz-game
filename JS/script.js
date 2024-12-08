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