<?php
session_start();

include("db.php");

if (!isset($_SESSION['user_id'])) {
  echo "User not logged in.";
  exit;
}

$user_id = $_SESSION['user_id'];
$form_session_id = uniqid();
$diet = $_POST['diet'];
$exercise = $_POST['exercise'];
$tobacco_use = $_POST['tobaccoUse'];
$year_quit = isset($_POST['yearQuit']) ? $_POST['yearQuit'] : null;
$alcohol_use = $_POST['alcohol'];
$sleep = $_POST['sleep'];

if ($tobacco_use === 'quit' && $year_quit) {
  $tobacco_use = "Quit on " . $year_quit;
}

// INSERT data into health_data table
$sql = "INSERT INTO obesity_data (diet, exercise, tobacco_use, alcohol_use, sleep, user_id, form_session_id)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_stmt_init($con);

if (!mysqli_stmt_prepare($stmt, $sql)) {
  die(mysqli_error($con));
}

mysqli_stmt_bind_param($stmt, "sssssis",
                       $diet,
                       $exercise,
                       $tobacco_use,
                       $alcohol_use,
                       $sleep,
                       $user_id,
                       $form_session_id);

if (mysqli_stmt_execute($stmt)) {
    $health_data_id = mysqli_insert_id($con);
    $_SESSION['health_data_id'] = $health_data_id;
} else {
    die("Execution Error: " . mysqli_stmt_error($stmt));
}

// ------------------------
// 🧠 SIMPLE HEALTH RISK ANALYSIS
// ------------------------

$obesity_risk = "Low";

// Sleep conversion
$sleep_hours = (int)$sleep;

// Logic based on submitted data
if ($diet === "Unhealthy" || $diet === "High Sugar") $obesity_risk = "Moderate";
if ($exercise === "never" || strpos($tobacco_use, "daily") !== false || $alcohol_use === "weekly") $obesity_risk = "High";
if ($sleep_hours < 6) $obesity_risk = "High";

// You can store this in session or database if needed
$_SESSION['obesity_risk'] = $obesity_risk;

// Optional: Store prediction in a new table or update existing row
// Example:
$update_sql = "UPDATE health_data SET obesity_risk = ? WHERE id = ?";
$update_stmt = mysqli_prepare($con, $update_sql);
mysqli_stmt_bind_param($update_stmt, "si", $obesity_risk, $health_data_id);
mysqli_stmt_execute($update_stmt);

// ------------------------
// ✅ Redirect to dashboard or result page
// ------------------------

header("Location: dashboard.php");
exit();
?>
