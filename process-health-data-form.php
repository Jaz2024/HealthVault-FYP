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

$user_id = $_SESSION['user_id']; // Retrieve user_id from session
$form_session_id = uniqid(); // Generate a unique session ID
$fullname =  $_POST['fullname'];
$dob = $_POST['dob'];
$gender = $_POST['gender'];
$bloodType = $_POST['blood-type'];
$status = $_POST['status'];
$tel = $_POST['tel'];
$emergencyName = $_POST['emergency-name'];
$emergencyTel = $_POST['emergency-tel'];
$streetaddress = $_POST['street-address'];
$city = $_POST['city'];
$state = $_POST['state'];
$postalcode = $_POST['postal-code'];
$chroniccondition = $_POST['cc'];
$surgeries = $_POST['surgeries'];

$vaccine = trim($_POST['vaccine']);
if ($vaccine === '-') {
  $dash1 = "Not Vaccine";
} else {
  $dash1 = $vaccine;
}
$newvaccine = $dash1; 


$dateadministered = $_POST['date-administered'];
if ($dateadministered === '-') {
    $dash2 = "Not Administered";
} else {
    $dash2 = $dateadministered;
}
$newdateadministered = $dash2;  

$medication = $_POST['medication'];
if ($medication === '-') {
  $dash3 = "Not Medication";
} else {
  $dash3 = $medication;
}
$newmedication = $dash3; 

$dosage = $_POST['dosage'];
if ($dosage === '-') {
  $dash4 = "Not Dosage";
} else {
  $dash4 = $dosage;
}
$newdosage = $dash4; 

$frequency = $_POST['frequency'];
if ($frequency === '-') {
  $dash5 = "Not Frequency";
} else {
  $dash5 = $frequency;
}
$newfrequency = $dash5; 

$bloodpressure = $_POST['blood-pressure'];
$heartrate = $_POST['heart-rate'];
$height = $_POST['height'];
$weight = $_POST['weight'];
$bmiResult = $_POST['bmiResult'];
$test = $_POST['test'];

$testresult = $_POST['result'];
if ($testresult === '-') {
  $dash6 = "Not result";
} else {
  $dash6 = $testresult;
}
$newtestresult = $dash6; 

$dob2 = $_POST['dob2'];
if ($dob2 === '00/00/0000') {
  $dash7 = "No Date";
} else {
  $dash7 = $dob2;
}
$newdob2 = $dash7; 

$diet = $_POST['diet'];
$exercise = $_POST['exercise'];
$tobacco_use = $_POST['tobaccoUse'];
$alcohol_use = $_POST['alcohol'];
$sleep = $_POST['sleep'];
$provider_name = $_POST['provider-name'];
$provider_tel = $_POST['provider-tel'];
$facility_name = $_POST['facility-name'];
$facility_address = $_POST['facility-address'];
$recordtitle = $_POST['title'];


/* Chronic Condition Health Tips */
$chroniccondition = $_POST['cc'];
$userInput = [
  "Chronic_Condition" => $chroniccondition,
];

$jsonData = json_encode($userInput);

$command = "C:\\xampp\\htdocs\\HealthVault\\myenv\\Scripts\\python.exe chronic_condition_tips.py";
$process = proc_open($command, [
  0 => ["pipe", "r"],  // stdin
  1 => ["pipe", "w"],  // stdout
  2 => ["pipe", "w"]   // stderr
], $pipes);

fwrite($pipes[0], $jsonData);
fclose($pipes[0]);

$output5 = stream_get_contents($pipes[1]);
fclose($pipes[1]);

$error = stream_get_contents($pipes[2]);
fclose($pipes[2]);

proc_close($process);

if (!empty($error)) {
    die("Python error: " . $error);
}

$result = json_decode($output5, true);
$chronic_condition_tips = $result['tips'];




/* Tobacco Risk Assessment */
$tobacco_use = $_POST['tobaccoUse'];
$displayUse = $tobacco_use == "Never" ? "Never Smoked" : $tobacco_use;
$userInput = [
  'Tobacco_Use' => $displayUse,
];

$jsonData = json_encode($userInput);

$command = "C:\\xampp\\htdocs\\HealthVault\\myenv\\Scripts\\python.exe tobacco_info.py";
$process = proc_open($command, [
  0 => ["pipe", "r"],  // stdin
  1 => ["pipe", "w"],  // stdout
  2 => ["pipe", "w"]   // stderr
], $pipes);

fwrite($pipes[0], $jsonData);
fclose($pipes[0]);

$output3 = stream_get_contents($pipes[1]);
fclose($pipes[1]);

$error = stream_get_contents($pipes[2]);
fclose($pipes[2]);

proc_close($process);

if (!empty($error)) {
    die("Python tobacco info error: " . $error);
}

$result = json_decode($output3, true);
$tobacco_risk = $result['risk'];
$tobacco_tips = $result['tips'];



/* Liver Disease Risk Assessment */
$dob = $_POST['dob'];
$tobacco_use = $_POST['tobaccoUse'];  // must match HTML form field name
$alcohol_use = $_POST['alcohol'];     // must match HTML form field name

$userInput = [
  'DateofBirth' => $dob, 
  'Tobacco_Use' => $tobacco_use,
  'Alcohol_Consumption' => $alcohol_use,
];

$jsonData = json_encode($userInput);

$command = "C:\\xampp\\htdocs\\HealthVault\\myenv\\Scripts\\python.exe liver_risk_prediction.py";
$process = proc_open($command, [
  0 => ["pipe", "r"],  // stdin
  1 => ["pipe", "w"],  // stdout
  2 => ["pipe", "w"]   // stderr
], $pipes);

fwrite($pipes[0], $jsonData);
fclose($pipes[0]);

$output2 = stream_get_contents($pipes[1]);
fclose($pipes[1]);

$error = stream_get_contents($pipes[2]);
fclose($pipes[2]);

proc_close($process);

if (!empty($error)) {
    die("Python heart prediction error: " . $error);
}

$result = json_decode($output2, true); // use $output2 or whatever your variable is

$liver_risk = $result['Risk_Message'];
$liver_tips = $result['Health_Tip'];



/* Heart Disease Risk Assessment */
$bloodpressure = $_POST['blood-pressure'];
$heartrate = $_POST['heart-rate'];
$bmiResult = $_POST['bmiResult'];

$userInput = [
  'BloodPressure' => $bloodpressure, 
  'HeartRate' => $heartrate,        
  'BMI' => $bmiResult                     
];

$jsonData = json_encode($userInput);

$command = "C:\\xampp\\htdocs\\HealthVault\\myenv\\Scripts\\python.exe heart_disease_prediction.py";
$process = proc_open($command, [
  0 => ["pipe", "r"],  // stdin
  1 => ["pipe", "w"],  // stdout
  2 => ["pipe", "w"]   // stderr
], $pipes);

fwrite($pipes[0], $jsonData);
fclose($pipes[0]);

$output1 = stream_get_contents($pipes[1]);
fclose($pipes[1]);

$error = stream_get_contents($pipes[2]);
fclose($pipes[2]);

proc_close($process);

if (!empty($error)) {
    die("Python heart prediction error: " . $error);
}

$result = json_decode($output1, true); // use $output2 or whatever your variable is

$heart_disease_risk = $result['Risk_Message'];
$heart_disease_tips = $result['Health_Tip'];


/* Diabetes Prediction */

$diet = $_POST['diet'];
$exercise = $_POST['exercise'];
$tobacco_use = $_POST['tobaccoUse'];
$alcohol_use = $_POST['alcohol'];
$sleep = $_POST['sleep'];

$userInput = [
  'Diet' => $diet,
  'Exercise' => $exercise,
  'Tobacco_Use' => $tobacco_use,
  'Alcohol_Consumption' => $alcohol_use,
  'Sleep_Pattern' => (int)$sleep
];
$jsonData = json_encode($userInput);

$command = "C:\\xampp\\htdocs\\HealthVault\\myenv\\Scripts\\python.exe diabetes_prediction.py";
$process = proc_open($command, [
  0 => ["pipe", "r"],
  1 => ["pipe", "w"],
  2 => ["pipe", "w"]
], $pipes);

if (is_resource($process)) {
  fwrite($pipes[0], $jsonData);
  fclose($pipes[0]);

  $output = trim(stream_get_contents($pipes[1]));
  fclose($pipes[1]);

  $error = stream_get_contents($pipes[2]);
  fclose($pipes[2]);

  proc_close($process);

  if (!empty($error)) {
    die("Python error: " . $error);
  }
} else {
  die("Failed to start Python script.");
}

$result = json_decode($output, true);
$diabetes_risk = $result['risk'];
$diabetes_tips = $result['tips']; 


/* Obesity Prediction */
$dob = $_POST['dob'];
$height = $_POST['height'];
$weight = $_POST['weight'];
$bmiResult = $_POST['bmiResult'];
$diet = $_POST['diet'];

$userInput = [
  'DateofBirth' => $dob, 
  'Height' => $height,
  'Weight' => $weight,
  'BMI' => $bmiResult,
  'Diet' => $diet

  
];

$jsonData = json_encode($userInput);

$command = "C:\\xampp\\htdocs\\HealthVault\\myenv\\Scripts\\python.exe obesity_prediction.py";
$process = proc_open($command, [
  0 => ["pipe", "r"],  // stdin
  1 => ["pipe", "w"],  // stdout
  2 => ["pipe", "w"]   // stderr
], $pipes);

fwrite($pipes[0], $jsonData);
fclose($pipes[0]);

$output4 = stream_get_contents($pipes[1]);
fclose($pipes[1]);

$error = stream_get_contents($pipes[2]);
fclose($pipes[2]);

proc_close($process);

if (!empty($error)) {
    die("Python tobacco info error: " . $error);
}

$result = json_decode($output4, true);
$obesity_risk = $result['risk'];
$obesity_tips = $result['tips'];


/* Storing inputs into database*/

include("db.php");

$sql = "INSERT INTO health_data (fullname, dob, gender, bloodtype, status, tel, emergencyname, emergencytel, streetaddress, city, state, postalcode, chroniccondition, surgeries, vaccine, dateadministered, medication, dosage, frequency, blood_pressure, heart_rate, height, weight, bmi, test, result, testdate, diet, exercise, tobacco_use, alcohol_use, sleep, providername, providertel, facilityname, facilityaddress, recordtitle, diabetes_risk, diabetes_tips, heart_disease_risk, heart_disease_tips, liver_risk, liver_tips, tobacco_risk, tobacco_tips, obesity_risk, obesity_tips, chronic_condition_tips, user_id, form_session_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

$stmt = mysqli_stmt_init($con);

if (! mysqli_stmt_prepare($stmt, $sql)) {
  die(mysqli_error($con));
}

mysqli_stmt_bind_param($stmt, "sssssssssssisssssssssiidssssssssssssssssssssssssis",
                       $fullname,
                       $dob,
                       $gender,
                       $bloodType,
                       $status,
                       $tel,
                       $emergencyName,
                       $emergencyTel,
                       $streetaddress,
                       $city,
                       $state,
                       $postalcode,
                       $chroniccondition,
                       $surgeries,
                       $newvaccine,
                       $newdateadministered,
                       $newmedication,
                       $newdosage,
                       $newfrequency,
                       $bloodpressure,
                       $heartrate,
                       $height,
                       $weight,
                       $bmiResult,
                       $test,
                       $newtestresult,
                       $newdob2,
                       $diet,
                       $exercise,
                       $tobacco_use,
                       $alcohol_use,
                       $sleep,
                       $provider_name,
                       $provider_tel,
                       $facility_name,
                       $facility_address,
                       $recordtitle,
                       $diabetes_risk,
                       $diabetes_tips,
                       $heart_disease_risk,
                       $heart_disease_tips,
                       $liver_risk,
                       $liver_tips,
                       $tobacco_risk,
                       $tobacco_tips,
                       $obesity_risk,
                       $obesity_tips,
                       $chronic_condition_tips,
                       $user_id,
                       $form_session_id);

// Execute the statement
if (mysqli_stmt_execute($stmt)) {
    // Retrieve the auto-incremented ID
    $health_data_id = mysqli_insert_id($con);

    // Store the ID in the session for later use
    $_SESSION['health_data_id'] = $health_data_id;
} else {
    die("Execution Error: " . mysqli_stmt_error($stmt));
}


// Ensure $health_data_id is set
if (!isset($_SESSION['health_data_id'])) {
  die("Health data ID not set in session.");
}


echo "Data inserted successfully.";
header("Location: dashboard.php");
exit();
