<?php
// auth.php
session_start();

function check_login() {
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        header("Location: login.php"); // Redirect to login page
        exit();
    }
}
?>
