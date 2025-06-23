/**
 * Display a notification message on the page.
 * @param {string} message - The message to display.
 * @param {string} color - The text color of the message.
 */
function showNotification(message, color = 'black') {
    // Check if notification container exists, if not, create it
    let notification = document.getElementById("notification");
    
    if (!notification) {
        notification = document.createElement("div");
        notification.id = "notification";
        notification.style.position = "fixed";
        notification.style.top = "20px";
        notification.style.right = "20px";
        notification.style.backgroundColor = "#f8f9fa";
        notification.style.padding = "10px 20px";
        notification.style.border = "1px solid #ccc";
        notification.style.borderRadius = "5px";
        notification.style.boxShadow = "0 2px 5px rgba(0,0,0,0.2)";
        notification.style.zIndex = "9999";
        notification.style.display = "none";
        document.body.appendChild(notification);
    }

    // Display the message
    notification.textContent = message;
    notification.style.color = color;
    notification.style.display = "block";

    // Hide after 5 seconds
    setTimeout(() => {
        notification.style.display = "none";
    }, 5000);
}
