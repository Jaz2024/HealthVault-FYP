<?php
session_start(); // Start session to store error messages

$token = $_POST["token"];
$token_hash = hash("sha256", $token);

$mysqli = require __DIR__ . "/db.php";

$sql = "SELECT * FROM users WHERE reset_token_hash = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $token_hash);
$stmt->execute();
$result = $stmt->get_result();

$user = $result->fetch_assoc();

if ($user == null) {
    $_SESSION['error_message'] = "Token not found";
    header("Location: reset-password.php");
    exit;
}

if (strtotime($user["reset_token_expires_at"]) <= time()) {
    $_SESSION['error_message'] = "Token has expired";
    header("Location: reset-password.php");
    exit;
}

if (strlen($_POST["password"]) < 8) {
    $_SESSION['error_message'] = "Password must be at least 8 characters";
    header("Location: reset-password.php");
    exit;
}

if (!preg_match("/[a-z]/i", $_POST["password"])) {
    $_SESSION['error_message'] = "Password must contain at least one letter";
    header("Location: reset-password.php");
    exit;
}

if (!preg_match("/[0-9]/i", $_POST["password"])) {
    $_SESSION['error_message'] = "Password must contain at least one number";
    header("Location: reset-password.php");
    exit;
}

if ($_POST["password"] !== $_POST["password_confirmation"]) {
    $_SESSION['error_message'] = "Passwords must match";
    header("Location: reset-password.php");
    exit;
}

$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

$sql = "UPDATE users SET password_hash = ?, reset_token_hash = NULL, reset_token_expires_at = NULL WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ss", $password_hash, $user["id"]);
$stmt->execute();

header("Location: /HealthVault/login.php");
exit;
?>
