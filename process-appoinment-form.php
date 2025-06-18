<?php
session_start();

include("db.php");

// Check if user_id is set in session
if (isset($_SESSION['user_id'])) {
  // Get the user_id from the session
  $user_id = $_SESSION['user_id'];
} else {
  echo "User not logged in.";
  exit;  // Exit if user is not logged in
}

// Personal details
$user_id = $_SESSION['user_id']; // Retrieve user_id from session
$fullname = $_POST['fullname'];
$dob = $_POST['dob'];
$email = $_POST['email'];
$tel = $_POST['tel'];
$gender = $_POST['gender'];
$address = $_POST['address'];

//Doctor Appoinment Details
$hospital = $_POST['hospital'];
$doctor = $_POST['doctor'];
$speciality = $_POST['speciality'];
$medical_concern = $_POST['medical_concern'];
$preferred_date = $_POST['preferred_date'];
$preferred_time = $_POST['preferred_time'];

// Storing the data in the database
$sql = "INSERT INTO appoinment_data (fullname, dob, email, tel, gender, address, hospital, doctor, speciality, medical_concern, preferred_date, preferred_time, user_id)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";

$stmt = mysqli_stmt_init($con);

if (! mysqli_stmt_prepare($stmt, $sql)) {
  die(mysqli_error($con));
}
mysqli_stmt_bind_param($stmt,"ssssssssssssi", $fullname, $dob, $email, $tel, $gender, $address, $hospital, $doctor, $speciality, $medical_concern, $preferred_date, $preferred_time, $user_id);

// Execute the statement
if (mysqli_stmt_execute($stmt)) {
  // Retrieve the auto-incremented ID
  $appoinment_data_id = mysqli_insert_id($con);

  // Store the ID in the session for later use
  $_SESSION['appoinment_data_id'] = $appoinment_data_id;
} else {
  die("Execution Error: " . mysqli_stmt_error($stmt));
}


// Ensure $health_data_id is set
if (!isset($_SESSION['appoinment_data_id'])) {
die("Appoinment Data ID not set in session.");
}


echo "Appoinment has been booked.";
header("Location: appoinment_dashboard.php");
exit();

