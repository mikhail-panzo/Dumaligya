var beforeUnloadEvent = function(event) {
    event.preventDefault();
    var confirmationMessage = "Are you sure you want to leave? Your unsaved data may be lost.";
    (event || window.event).returnValue = confirmationMessage;
    return confirmationMessage;
}

// add the warning alert on before unload
window.addEventListener("beforeunload", beforeUnloadEvent);

// remove the alert on the form with the id "form" when submitting
document.getElementById("form").addEventListener("submit", function() {
    window.removeEventListener("beforeunload", beforeUnloadEvent);
});