// update_status.js

document.addEventListener("DOMContentLoaded", function () {
    // Check and update status on page load
    updateStatus();

    // Check and update status every minute
    setInterval(updateStatus, 60000);

    function updateStatus() {
        // Perform an AJAX request to update status
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Reload the page if statuses are updated
                    var response = JSON.parse(xhr.responseText);
                    if (response.updated) {
                        location.reload();
                    }
                }
            }
        };
        xhr.open("GET", "update_status.php", true);
        xhr.send();
    }
});
