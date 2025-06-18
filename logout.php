<?php
session_start(); // Start the session

// Destroy all session data (log out the user)
session_unset();
session_destroy();

// Check if a 'redirect' parameter is passed in the URL
if (isset($_GET['redirect'])) {
    // Sanitize the redirect value to prevent directory traversal (security measure)
    $redirect_page = basename($_GET['redirect']);
    header("Location: $redirect_page"); // Redirect to the specified page
} else {
    // Default redirect to home.html if no 'redirect' is specified
    header("Location: login.php");
}

exit(); // Ensure no further code is executed after the redirect
?>
