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

if ($_SERVER['REQUEST_METHOD'] == "post") {
  // Retrieve and sanitize input
  $fullname = trim($_POST['fullname']);
  $day = trim($_POST['day']);
  $month = trim($_POST['month']);
  $year = trim($_POST['year']);  
  $gender = trim($_POST['gender']);  
  $bloodType = trim($_POST['blood-type']);  
  $status = trim($_POST['status']);  
  $tel = trim($_POST['tel']);  
  $emergencyName = trim($_POST['emergency-name']);  
  $emergencyTel = trim($_POST['emergency-tel']);  
  $streetAddress = trim($_POST['street-addresss']);  
  $city = trim($_POST['city']);  
  $state = trim($_POST['state']);  
  $postalCode = trim($_POST['postal-code']);  
  $country = trim($_POST['country']); 
  $chronicCondition = trim($_POST['cc']);  
  $surgeries = trim($_POST['surgeries']);  
  $vaccine = trim($_POST['vaccine']);  
  $dateAdministered = trim($_POST['date-administered']);  
  $medication = trim($_POST['medication']);  
  $dosage = trim($_POST['dosage']);  
  $frequency = trim($_POST['frequency']);  
  $bloodPressure = trim($_POST['blood-pressure']);  
  $heartRate = trim($_POST['heart-rate']);
  $height = trim($_POST['height']);
  $weight = trim($_POST['blood-pressure']);
  $bmi = trim($_POST['bmiResult']);
  $test = trim($_POST['test']);
  $result = trim($_POST['result']);
  $testDateMonth = trim($_POST['months']);
  $testDateYear = trim($_POST['years']);
  $diet = trim($_POST['diet']);
  $exercise = trim($_POST['exercise']);
  $tobaccoUse = trim($_POST['tobaccoUse']);
  $yearQuit = trim($_POST['yearQuit']);
  $alcohol = trim($_POST['alcohol']);
  $sleep = trim($_POST['sleep']);
  $providerType = trim($_POST['provider-type']);
  $providerName = trim($_POST['provider-name']);
  $providerTel = trim($_POST['provider-tel']);
  $facilityName = trim($_POST['facility-name']);
  $facilityAddress = trim($_POST['facility-address']);
  $recordTitle = trim($_POST['record-title']);

  // Format the date as YYYY-MM-DD for database storage
  $date_of_birthForDB = "$year/$month/$day"; 

  // Optionally, format the date as DD/MM/YYYY for display
  $date_of_birthForDisplay = "$day/$month/$year";
  // Format the date for database storage
  $test_dateForDB = "$testDateYear/$testDateMonth"; 
  // Optionally, format the date as MM/YYYY for display
  $test_dateForDisplay = "$testDateMonth/$testDateYear";

  // Insert user health data into the database
  $query = "INSERT INTO health_data (user_id, fullname, date_of_birth, gender, blood_type, marital_status, phone_number, emergency_contact_name, emergency_contact_number, street_address, city, state, postal_code, country, chronic_condition, surgeries, vaccine, date_administered, medication, dosage, frequency, blood_pressure, heart_rate, height, weight, bmi, medical_test, test_result, test_date, diet, physical_activity, tobacco_use, year_quit, alcohol, sleep, provider_type, provider_name, provider_contact, facility_name, facility_address, record_title) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

  // Prepare the SQL statement
  $stmt = mysqli_prepare($con, $query);
  if ($stmt === false) {
      echo "Error preparing statement: " . mysqli_error($con);
      exit;
  }

  // Bind parameters
  mysqli_stmt_bind_param($stmt, 'issssssssssssssssssssssdddssssssssssssss', 
      $user_id, $fullname, $date_of_birthForDB, $gender, $bloodType, $status, $tel, 
      $emergencyName, $emergencyTel, $streetAddress, $city, $state, $postalCode, 
      $country, $chronicCondition, $surgeries, $vaccine, $dateAdministered, $medication, 
      $dosage, $frequency, $bloodPressure, $heartRate, $height, $weight, $bmi, 
      $test, $result, $test_dateForDB, $diet, $exercise, $tobaccoUse, $yearQuit, 
      $alcohol, $sleep, $providerType, $providerName, $providerTel, $facilityName, 
      $facilityAddress, $recordTitle);

  // Execute the statement
  if ($stmt->execute()) {
      echo "Health data stored successfully.";
  } else {
      echo "Error: " . $stmt->error;
  }

  // Close the statement
  mysqli_stmt_close($stmt);

  // Close the database connection
  mysqli_close($con);
}
?>