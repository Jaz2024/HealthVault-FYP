<?php
session_start();
include("db.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Check if the ID is provided in the URL
if (isset($_GET['id'])) {
    $record_id = $_GET['id'];

    // Prepare SQL query to fetch the health record
    $query = "SELECT * FROM health_data WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'ii', $record_id, $_SESSION['user_id']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $record = mysqli_fetch_assoc($result);
    } else {
        echo "<script>alert('Record not found or you do not have permission to view this record.'); window.location.href = 'dashboard.php';</script>";
        exit;
    }

    // Process update if form is submitted
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (!empty($_POST['fullname']) && !empty($_POST['gender']) && !empty($_POST['dob']) && !empty($_POST['blood-type']) && !empty($_POST['status']) 
        && !empty($_POST['tel']) && !empty($_POST['emergency-name']) && !empty($_POST['emergency-tel']) && !empty($_POST['street-address']) && !empty($_POST['city'])
        && !empty($_POST['state']) && !empty($_POST['postal-code']) && !empty($_POST['cc']) && !empty($_POST['surgeries']) && !empty($_POST['vaccine']) 
        && !empty($_POST['date-administered']) && !empty($_POST['medication']) && !empty($_POST['dosage']) && !empty($_POST['frequency'])
        && !empty($_POST['blood-pressure']) && !empty($_POST['heart-rate']) && !empty($_POST['height']) && !empty($_POST['weight']) && !empty($_POST['bmiResult'])
        && !empty($_POST['test']) && !empty($_POST['result']) && !empty($_POST['dob2']) && !empty($_POST['diet']) && !empty($_POST['exercise'])
        && !empty($_POST['tobaccoUse']) && !empty($_POST['alcohol']) && !empty($_POST['sleep']) && !empty($_POST['provider-name']) && !empty($_POST['provider-tel'])
        && !empty($_POST['facility-name']) && !empty($_POST['facility-address']) && !empty($_POST['title'])) {  
          
            $fullname = trim($_POST['fullname']);
            $dob = trim($_POST['dob']);
            $gender = trim($_POST['gender']);
            $bloodType = trim($_POST['blood-type']);
            $status = trim($_POST['status']);
            $tel = trim($_POST['tel']);
            $emergencyName = trim($_POST['emergency-name']);
            $emergencyTel = trim($_POST['emergency-tel']);
            $streetaddress = trim($_POST['street-address']);
            $city = trim($_POST['city']);
            $state = trim($_POST['state']);
            $postalcode = trim($_POST['postal-code']);
            $chroniccondition = trim($_POST['cc']);
            $surgeries = trim($_POST['surgeries']);
            
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

            $medication = trim($_POST['medication']);
            $dosage = trim($_POST['dosage']);
            $frequency = trim($_POST['frequency']);
            $bloodpressure = trim($_POST['blood-pressure']);
            $heartrate = trim($_POST['heart-rate']);
            $height = trim($_POST['height']);
            $weight = trim($_POST['weight']);
            $bmiResult = trim($_POST['bmiResult']);
            $test = trim($_POST['test']);

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

            $diet = trim($_POST['diet']);
            $exercise = trim($_POST['exercise']);
            $tobacco_use = trim($_POST['tobaccoUse']);
            $alcohol_use = trim($_POST['alcohol']);
            $sleep = trim($_POST['sleep']);
            $provider_name = trim($_POST['provider-name']);
            $provider_tel = trim($_POST['provider-tel']);
            $facility_name = trim($_POST['facility-name']);
            $facility_address = trim($_POST['facility-address']);
            $recordtitle = trim($_POST['title']);

            /* Chronic Condition Health Tips */
            $chroniccondition = trim($_POST['cc']);
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
            $tobacco_use = trim($_POST['tobaccoUse']);
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
            $dob = trim($_POST['dob']);
            $tobacco_use = trim($_POST['tobaccoUse']);
            $alcohol_use = trim($_POST['alcohol']);

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
            $bloodpressure = trim($_POST['blood-pressure']);
            $heartrate = trim($_POST['heart-rate']);
            $bmiResult = trim($_POST['bmiResult']);

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

            $diet = trim($_POST['diet']);
            $exercise = trim($_POST['exercise']);
            $tobacco_use = trim($_POST['tobaccoUse']);
            $alcohol_use = trim($_POST['alcohol']);
            $sleep = trim($_POST['sleep']);

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
            $dob = trim($_POST['dob']);
            $height = trim($_POST['height']);
            $weight = trim($_POST['weight']);
            $bmiResult = trim($_POST['bmiResult']);
            $diet = trim($_POST['diet']);

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
            
            // Prepare the SQL query for updating fullname
            $sql = "UPDATE health_data SET fullname = ?, dob = ?, gender = ?, bloodtype = ?, status = ?, tel = ?,
            emergencyname = ?, emergencytel = ?, streetaddress = ?, city = ?, state = ?, postalcode = ?,
            chroniccondition = ?, surgeries = ?, vaccine = ?, dateadministered = ?, medication = ?, dosage = ?, frequency = ?,
            blood_pressure = ?, heart_rate = ?, height = ?, weight = ?, bmi = ?, test = ?, result = ?, testdate = ?, diet = ?,
            exercise = ?, tobacco_use = ?, alcohol_use = ?, sleep = ?, providername = ?, providertel = ?, facilityname = ?,
            facilityaddress = ?, recordtitle = ?, diabetes_risk = ?, diabetes_tips = ?, heart_disease_risk = ?, heart_disease_tips = ?, 
            liver_risk = ?, liver_tips = ?, tobacco_risk = ?, tobacco_tips = ?, obesity_risk = ?, obesity_tips = ?, chronic_condition_tips = ? WHERE id = ? AND user_id = ?";
            $stmt = mysqli_prepare($con, $sql);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "sssssssssssisssssssssiidssssssssssssssssssssssssii", $fullname, $dob, $gender, $bloodType, $status, $tel, $emergencyName, $emergencyTel, $streetaddress,
                $city, $state, $postalcode, $chroniccondition, $surgeries, $newvaccine, $newdateadministered, $newmedication, $newdosage, $newfrequency, $bloodpressure, $heartrate, 
                $height, $weight, $bmiResult, $test, $newtestresult, $newdob2, $diet, $exercise, $tobacco_use, $alcohol_use, $sleep, $provider_name, $provider_tel, $facility_name,
                $facility_address, $recordtitle, $diabetes_risk, $diabetes_tips, $heart_disease_risk, $heart_disease_tips, $liver_risk, $liver_tips, $tobacco_risk,$tobacco_tips, 
                $obesity_risk, $obesity_tips, $chronic_condition_tips, $record_id, $_SESSION['user_id']);

                if (mysqli_stmt_execute($stmt)) {
                    echo "<script>window.location.href = 'dashboard.php';</script>";
                    exit;
                }
            }

            mysqli_stmt_close($stmt);
        }
    }
} else {
    echo "<script>alert('No record ID specified.'); window.location.href = 'dashboard.php';</script>";
    exit;
}
?>


<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">

<title>Update Health Record</title>
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600' rel='stylesheet' type='text/css'>
<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
<style>
html, body {
min-height: 100%;
}
body, div, form, input, select, p { 
padding: 0;
margin: 0;
outline: none;
font-family: Roboto, Arial, sans-serif;
font-size: 14px;
color: #666;
}
h1 {
margin: 0;
font-weight: 400;
}
h3 {
margin: 12px 0;
color: #00c6a9;
} 
.main-block {
display: flex;
justify-content: center;
align-items: center;
background: #fff;
}
form {
width: 100%;
padding: 20px;
}
fieldset {
border: none;
border-top: 1px solid #00c6a9;;
}
.account-details, .personal-details {
display: flex;
flex-wrap: wrap;
justify-content: space-between;
}
.account-details >div, .personal-details >div >div {
display: flex;
align-items: center;
margin-bottom: 10px;
}
.account-details >div, .personal-details >div, input, label {
width: 100%;
}
label {
padding: 0 5px;
text-align: right;
vertical-align: middle;
}
input {
padding: 5px;
vertical-align: middle;
}
.checkbox {
margin-bottom: 10px;
}
select, .children, .gender, .bdate-block {
width: calc(100% + 26px);
padding: 5px 0;
}
select {
background: transparent;
}
.gender input {
width: auto;
} 
.gender label {
padding: 0 5px 0 0;
} 
.bdate-block {
display: flex;
justify-content: space-between;
}
.birthdate select.day {
width: 35px;
}
.birthdate select.mounth {
width: calc(100% - 94px);
}
.birthdate input {
width: 38px;
vertical-align: unset;
}
.checkbox input, .children input {
width: auto;
margin: -2px 10px 0 0;
}
.checkbox a {
color: #00c6a9;;
}
.checkbox a:hover {
color: #00c6a9;;
}
button {
width: 100%;
padding: 10px 0;
margin: 10px auto;
border-radius: 5px; 
border: none;
background: #00c6a9;; 
font-size: 14px;
font-weight: 600;
color: #fff;
}
button:hover {
background: #00c6a9;;
}
@media (min-width: 568px) {
.account-details >div, .personal-details >div {
width: 50%;
}
label {
width: 40%;
}
input {
width: 60%;
}
select, .children, .gender, .bdate-block {
width: calc(60% + 16px);
padding: 5px;
}
}

.account-details2 {
display: flex;
flex-wrap: wrap;
justify-content: space-between;
}
.account-details2 >div >div {
display: flex;
align-items: center;
margin-bottom: 10px;
}
.account-details2 >div input, label {
width: 100%;
}

input.dob-field::-webkit-datetime-edit-day-field {
    display: none;
}
input.dob-field::-webkit-datetime-edit-text {
    display: none;
}

</style>
</head>
<body>
<div class="main-block">
  <form method= "POST">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
      <h1 style="margin: 0; font-size: 28px; font-weight: 600;">Create Your Health Data</h1>
      <a href="dashboard.php" style="
          padding: 8px 16px;
          font-size: 15px;
          background-color: #f44336;
          color: white;
          border: none;
          border-radius: 5px;
          text-decoration: none;
          transition: background-color 0.2s ease, transform 0.2s ease;
      ">
        ← Back to Dashboard
      </a>
    </div>

    <style>
      a:hover {
        background-color: #d32f2f;
        transform: scale(1.05);
      }
    </style>

    <fieldset>
    <legend>
        <h3>Personal Information</h3>
    </legend>
        <div class="account-details">
            <div>
                <label for="fullname">Full Name</label>
                <input type="text" name="fullname" value="<?php echo htmlspecialchars($record['fullname'] ?? ''); ?>" required>       
              </div>
            <div>
                <label for="dob">Date Of Birth</label>
                <input type="date" name="dob" id="dob" min="1940-01-01" max="2025-12-31" required value="<?php echo htmlspecialchars($record['dob']?? ''); ?>">
            </div>
            <div>
                <label for="gender">Gender</label>
                <select name="gender" id="gender" required aria-label="Select your gender">
                    <option value="" disabled>Select your gender</option>
                    <option value="Man" <?php echo ($record['gender'] == 'Man') ? 'selected' : ''; ?>>Man</option>
                    <option value="Woman" <?php echo ($record['gender'] == 'Woman') ? 'selected' : ''; ?>>Woman</option>
                    <option value="Non-Binary" <?php echo ($record['gender'] == 'Non-Binary') ? 'selected' : ''; ?>>Non-Binary</option>
                    <option value="Prefer not to say" <?php echo ($record['gender'] == 'Prefer not to say') ? 'selected' : ''; ?>>Prefer not to say</option>
                </select>
            </div>
            <div>
                <label for="blood-type">Blood Type</label>
                <select name="blood-type" id="blood-type" required>
                    <option value="" disabled>Select Blood Group</option>
                    <option value="A-Positive" <?php echo ($record['bloodtype'] == 'A-Positive') ? 'selected' : ''; ?>>A Positive</option>
                    <option value="A-Negative" <?php echo ($record['bloodtype'] == 'A-Negative') ? 'selected' : ''; ?>>A Negative</option>
                    <option value="B-Positive" <?php echo ($record['bloodtype'] == 'B-Positive') ? 'selected' : ''; ?>>B Positive</option>
                    <option value="B-Negative" <?php echo ($record['bloodtype'] == 'B-Negative') ? 'selected' : ''; ?>>B Negative</option>
                    <option value="AB-Positive" <?php echo ($record['bloodtype'] == 'AB-Positive') ? 'selected' : ''; ?>>AB Positive</option>
                    <option value="AB-Negative" <?php echo ($record['bloodtype'] == 'AB-Negative') ? 'selected' : ''; ?>>AB Negative</option>
                    <option value="O-Positive" <?php echo ($record['bloodtype'] == 'O-Positive') ? 'selected' : ''; ?>>O Positive</option>
                    <option value="O-Negative" <?php echo ($record['bloodtype'] == 'O-Negative') ? 'selected' : ''; ?>>O Negative</option>
                    <option value="Unknown" <?php echo ($record['bloodtype'] == 'Unknown') ? 'selected' : ''; ?>>Unknown</option>
                </select>
            </div>
            <div>
                <label for="status">Marital Status</label>
                <select name="status" id="status" required>
                    <option value="" disabled>Select Marital Status</option>
                    <option value="single" <?php echo ($record['status'] == 'single') ? 'selected' : ''; ?>>Single</option>
                    <option value="married" <?php echo ($record['status'] == 'married') ? 'selected' : ''; ?>>Married</option>
                    <option value="divorced" <?php echo ($record['status'] == 'divorced') ? 'selected' : ''; ?>>Divorced</option>
                    <option value="widowed" <?php echo ($record['status'] == 'widowed') ? 'selected' : ''; ?>>Widowed</option>
                </select>
            </div>
            <div>
                <label for="tel">Phone Number</label>
                <input id="tel" name="tel" type="tel" placeholder="123-456-8901" pattern="\d{3}-\d{3}-\d{4}" required aria-label="Enter your phone number in the format 123-4567-8901" value="<?php echo htmlspecialchars($record['tel']?? ''); ?>">
            </div>
            <div>
                <label for="emergency-name">Emergency Contact Name</label>
                <input type="text" placeholder="e.g. John" name="emergency-name" id="emergency-name" required value="<?php echo htmlspecialchars($record['emergencyname']?? ''); ?>">
            </div>
            <div>
                <label for="emergency-tel">Emergency Contact Number</label>
                <input id="emergency-tel" name="emergency-tel" type="tel" placeholder="012-345-6789" pattern="\d{3}-\d{3}-\d{4}" required value="<?php echo htmlspecialchars($record['emergencytel']?? ''); ?>">
            </div>
          </div>
        </fieldset>

          <fieldset>
            <legend>
              <h3>Address Information</h3>
            </legend>
            <div class="account-details">
              <div>
                <label for="street-address">Street Address</label>
                <textarea class="street-address" placeholder="Street Address" name="street-address" id="street-address" required style="width: 450px; height: 77px; resize: none;"><?php echo htmlspecialchars($record['streetaddress']?? ''); ?></textarea>        
              </div>

              <div><label for="postal-code">Postal Code</label><input type="text"  placeholder="e.g. 12345" pattern="\d{5}" name="postal-code" id="postal-code" required value="<?php echo htmlspecialchars($record['postalcode']?? ''); ?>"></div>
              <div><label for="city">City</label><input type="text" placeholder="City" name="city" id="city" required value="<?php echo htmlspecialchars($record['city']?? ''); ?>"></div>
              <div><label for="state">State</label><input type="text" placeholder="State/Province" name="state" id="state" required  value="<?php echo htmlspecialchars($record['state'] ?? ''); ?>"  ></div>
          </div>
       </fieldset>

      <fieldset>
        <legend>
          <h3>Medical History</h3>
        </legend>
        <div class="account-details">
          <div>
            <label for="cc">Chronic Condition</label>
            <select name="cc" id="cc" required>
              <option value="" disabled selected>Please select</option>
              <option value="Addictions – substance and alcohol, etc." <?php echo ($record['chroniccondition'] == 'Addictions – substance and alcohol, etc.') ? 'selected' : ''; ?>>Addictions – substance and alcohol, etc.</option>
              <option value="ADHD" <?php echo ($record['chroniccondition'] == 'ADHD') ? 'selected' : ''; ?>>ADHD</option>
              <option value="Alcohol addiction" <?php echo ($record['chroniccondition'] == 'Alcohol addiction') ? 'selected' : ''; ?>>Alcohol addiction</option>
              <option value="Allergic rhinitis/rhinitis/sinusitis/rhinosinusitis" <?php echo ($record['chroniccondition'] == 'Allergic rhinitis/rhinitis/sinusitis/rhinosinusitis') ? 'selected' : ''; ?>>Allergic rhinitis/rhinitis/sinusitis/rhinosinusitis</option>
              <option value="Allergy/anaphylaxis" <?php echo ($record['chroniccondition'] == 'Allergy/anaphylaxis') ? 'selected' : ''; ?>>Allergy/anaphylaxis</option>
              <option value="Amputations" <?php echo ($record['chroniccondition'] == 'Amputations') ? 'selected' : ''; ?>>Amputations</option>
              <option value="Amnesia" <?php echo ($record['chroniccondition'] == 'Amnesia') ? 'selected' : ''; ?>>Amnesia</option>
              <option value="Anaemia" <?php echo ($record['chroniccondition'] == 'Anaemia') ? 'selected' : ''; ?>>Anaemia</option>
              <option value="Angina" <?php echo ($record['chroniccondition'] == 'Angina') ? 'selected' : ''; ?>>Angina</option>
              <option value="Angiooedema" <?php echo ($record['chroniccondition'] == 'Angiooedema') ? 'selected' : ''; ?>>Angiooedema</option>
              <option value="Anklosing spondylitis (and other arthritic conditions)" <?php echo ($record['chroniccondition'] == 'Anklosing spondylitis (and other arthritic conditions)') ? 'selected' : ''; ?>>Anklosing spondylitis (and other arthritic conditions)</option>
              <option value="Antenatal screening for haemoglobinoapthies – sickle cell and thalassemia, Downs" <?php echo ($record['chroniccondition'] == 'Antenatal screening for haemoglobinoapthies – sickle cell and thalassemia, Downs') ? 'selected' : ''; ?>>Antenatal screening for haemoglobinoapthies – sickle cell and thalassemia, Downs</option>
              <option value="Anxiety and stress disorders (including complex and post-traumatic stress disorders)" <?php echo ($record['chroniccondition'] == 'Anxiety and stress disorders (including complex and post-traumatic stress disorders)') ? 'selected' : ''; ?>>Anxiety and stress disorders (including complex and post-traumatic stress disorders)</option>
              <option value="Aphasia" <?php echo ($record['chroniccondition'] == 'Aphasia') ? 'selected' : ''; ?>>Aphasia</option>
              <option value="Ataxia’s" <?php echo ($record['chroniccondition'] == 'Ataxia’s') ? 'selected' : ''; ?>>Ataxia’s</option>
              <option value="Autism" <?php echo ($record['chroniccondition'] == 'Autism') ? 'selected' : ''; ?>>Autism</option>
              <option value="Autoimmune disorders (e.g. lupus, Sjögrens syndrome)" <?php echo ($record['chroniccondition'] == 'Autoimmune disorders (e.g. lupus, Sjögrens syndrome)') ? 'selected' : ''; ?>>Autoimmune disorders (e.g. lupus, Sjögrens syndrome)</option>
              <option value="Blood disorders" <?php echo ($record['chroniccondition'] == 'Blood disorders') ? 'selected' : ''; ?>>Blood disorders</option>
              <option value="Brain injuries (including stroke and TIAs)" <?php echo ($record['chroniccondition'] == 'Brain injuries (including stroke and TIAs)') ? 'selected' : ''; ?>>Brain injuries (including stroke and TIAs)</option>
              <option value="Bronchopulmonary dysplasia (chronic lung disease of infancy)" <?php echo ($record['chroniccondition'] == 'Bronchopulmonary dysplasia (chronic lung disease of infancy)') ? 'selected' : ''; ?>>Bronchopulmonary dysplasia (chronic lung disease of infancy)</option>
              <option value="Burn injuries" <?php echo ($record['chroniccondition'] == 'Burn injuries') ? 'selected' : ''; ?>>Burn injuries</option>
              <option value="Cancer" <?php echo ($record['chroniccondition'] == 'Cancer') ? 'selected' : ''; ?>>Cancer</option>
              <option value="Cardiac arrhythmias" <?php echo ($record['chroniccondition'] == 'Cardiac arrhythmias') ? 'selected' : ''; ?>>Cardiac arrhythmias</option>
              <option value="Cerebral palsy" <?php echo ($record['chroniccondition'] == 'Cerebral palsy') ? 'selected' : ''; ?>>Cerebral palsy</option>
              <option value="Chronic fatigue syndrome/ME" <?php echo ($record['chroniccondition'] == 'Chronic fatigue syndrome/ME') ? 'selected' : ''; ?>>Chronic fatigue syndrome/ME</option>
              <option value="Chronic kidney disease" <?php echo ($record['chroniccondition'] == 'Chronic kidney disease') ? 'selected' : ''; ?>>Chronic kidney disease</option>
              <option value="Chronic obstructive pulmonary disease" <?php echo ($record['chroniccondition'] == 'Chronic obstructive pulmonary disease') ? 'selected' : ''; ?>>Chronic obstructive pulmonary disease</option>
              <option value="Chronic pain" <?php echo ($record['chroniccondition'] == 'Chronic pain') ? 'selected' : ''; ?>>Chronic pain</option>
              <option value="Chronic sinusitis" <?php echo ($record['chroniccondition'] == 'Chronic sinusitis') ? 'selected' : ''; ?>>Chronic sinusitis</option>
              <option value="Coeliac disease" <?php echo ($record['chroniccondition'] == 'Coeliac disease') ? 'selected' : ''; ?>>Coeliac disease</option>
              <option value="Connective tissue diseases" <?php echo ($record['chroniccondition'] == 'Connective tissue diseases') ? 'selected' : ''; ?>>Connective tissue diseases</option>
              <option value="Congestive heart failure" <?php echo ($record['chroniccondition'] == 'Congestive heart failure') ? 'selected' : ''; ?>>Congestive heart failure</option>
              <option value="Coronary heart disease" <?php echo ($record['chroniccondition'] == 'Coronary heart disease') ? 'selected' : ''; ?>>Coronary heart disease</option>
              <option value="Crohn’s disease" <?php echo ($record['chroniccondition'] == 'Crohn’s disease') ? 'selected' : ''; ?>>Crohn’s disease</option>
              <option value="Cystic Fibrosis" <?php echo ($record['chroniccondition'] == 'Cystic Fibrosis') ? 'selected' : ''; ?>>Cystic Fibrosis</option>
              <option value="Dementia" <?php echo ($record['chroniccondition'] == 'Dementia') ? 'selected' : ''; ?>>Dementia</option>
              <option value="Depression" <?php echo ($record['chroniccondition'] == 'Depression') ? 'selected' : ''; ?>>Depression</option>
              <option value="Digestive conditions, stomach ulcers, oesophagus, reflux" <?php echo ($record['chroniccondition'] == 'Digestive conditions, stomach ulcers, oesophagus, reflux') ? 'selected' : ''; ?>>Digestive conditions, stomach ulcers, oesophagus, reflux</option>
              <option value="Dizziness" <?php echo ($record['chroniccondition'] == 'Dizziness') ? 'selected' : ''; ?>>Dizziness</option>
              <option value="Diabetes: Type I" <?php echo ($record['chroniccondition'] == 'Diabetes: Type I') ? 'selected' : ''; ?>>Diabetes: Type I</option>
              <option value="Diabetes: Type II" <?php echo ($record['chroniccondition'] == 'Diabetes: Type II') ? 'selected' : ''; ?>>Diabetes: Type II</option>
              <option value="Dyslexia or dyspraxia" <?php echo ($record['chroniccondition'] == 'Dyslexia or dyspraxia') ? 'selected' : ''; ?>>Dyslexia or dyspraxia</option>
              <option value="Eating disorders (anorexia/bulimia)" <?php echo ($record['chroniccondition'] == 'Eating disorders (anorexia/bulimia)') ? 'selected' : ''; ?>>Eating disorders (anorexia/bulimia)</option>
              <option value="Eczema" <?php echo ($record['chroniccondition'] == 'Eczema') ? 'selected' : ''; ?>>Eczema</option>
              <option value="Endocrine disorders (thyrotoxicosis, hypothyroidism, hypogonadism, Cushing syndrome, Addison’s disease)" <?php echo ($record['chroniccondition'] == 'Endocrine disorders (thyrotoxicosis, hypothyroidism, hypogonadism, Cushing syndrome, Addison’s disease)') ? 'selected' : ''; ?>>Endocrine disorders (thyrotoxicosis, hypothyroidism, hypogonadism, Cushing syndrome, Addison’s disease)</option>
              <option value="Epilepsy" <?php echo ($record['chroniccondition'] == 'Epilepsy') ? 'selected' : ''; ?>>Epilepsy</option>
              <option value="Fibromyalgia/chronic widespread pain" <?php echo ($record['chroniccondition'] == 'Fibromyalgia/chronic widespread pain') ? 'selected' : ''; ?>>Fibromyalgia/chronic widespread pain</option>
              <option value="Gout" <?php echo ($record['chroniccondition'] == 'Gout') ? 'selected' : ''; ?>>Gout</option>
              <option value="Gynaecological problems, chronic pelvic pain" <?php echo ($record['chroniccondition'] == 'Gynaecological problems, chronic pelvic pain') ? 'selected' : ''; ?>>Gynaecological problems, chronic pelvic pain</option>
              <option value="Haemophilia and other coagulation disorders" <?php echo ($record['chroniccondition'] == 'Haemophilia and other coagulation disorders') ? 'selected' : ''; ?>>Haemophilia and other coagulation disorders</option>
              <option value="Heart failure" <?php echo ($record['chroniccondition'] == 'Heart failure') ? 'selected' : ''; ?>>Heart failure</option>
              <option value="Hepatitis B" <?php echo ($record['chroniccondition'] == 'Hepatitis B') ? 'selected' : ''; ?>>Hepatitis B</option>
              <option value="Hepatitis C" <?php echo ($record['chroniccondition'] == 'Hepatitis C') ? 'selected' : ''; ?>>Hepatitis C</option>
              <option value="Hypertension" <?php echo ($record['chroniccondition'] == 'Hypertension') ? 'selected' : ''; ?>>Hypertension</option>
              <option value="Learning disabilities" <?php echo ($record['chroniccondition'] == 'Learning disabilities') ? 'selected' : ''; ?>>Learning disabilities</option>
              <option value="Lung fibrosis" <?php echo ($record['chroniccondition'] == 'Lung fibrosis') ? 'selected' : ''; ?>>Lung fibrosis</option>
              <option value="Lupus" <?php echo ($record['chroniccondition'] == 'Lupus') ? 'selected' : ''; ?>>Lupus</option>
              <option value="Malaria" <?php echo ($record['chroniccondition'] == 'Malaria') ? 'selected' : ''; ?>>Malaria</option>
              <option value="Medically unexplained symptoms" <?php echo ($record['chroniccondition'] == 'Medically unexplained symptoms') ? 'selected' : ''; ?>>Medically unexplained symptoms</option>
              <option value="Migraine" <?php echo ($record['chroniccondition'] == 'Migraine') ? 'selected' : ''; ?>>Migraine</option>
              <option value="Mood disorders (not only depression, but mania and bipolar disorders)" <?php echo ($record['chroniccondition'] == 'Mood disorders (not only depression, but mania and bipolar disorders)') ? 'selected' : ''; ?>>Mood disorders (not only depression, but mania and bipolar disorders)</option>
              <option value="Motor neurone disease" <?php echo ($record['chroniccondition'] == 'Motor neurone disease') ? 'selected' : ''; ?>>Motor neurone disease</option>
              <option value="Multimorbidity" <?php echo ($record['chroniccondition'] == 'Multimorbidity') ? 'selected' : ''; ?>>Multimorbidity</option>
              <option value="Multisystem autoimmune diseases (MSAIDs, including lupus)" <?php echo ($record['chroniccondition'] == 'Multisystem autoimmune diseases (MSAIDs, including lupus)') ? 'selected' : ''; ?>>Multisystem autoimmune diseases (MSAIDs, including lupus)</option>
              <option value="Muscular dystrophy(ies)" <?php echo ($record['chroniccondition'] == 'Muscular dystrophy(ies') ? 'selected' : ''; ?>>Muscular dystrophy(ies)</option>
              <option value="Neuralgias (including, head and back pain)" <?php echo ($record['chroniccondition'] == 'Neuralgias (including, head and back pain)') ? 'selected' : ''; ?>>Neuralgias (including, head and back pain)</option>
              <option value="Newborn screening programme diseases, including thyroid disease, hearing loss" <?php echo ($record['chroniccondition'] == 'Newborn screening programme diseases, including thyroid disease, hearing loss') ? 'selected' : ''; ?>>Newborn screening programme diseases, including thyroid disease, hearing loss</option>
              <option value="Obesity" <?php echo ($record['chroniccondition'] == 'Obesity') ? 'selected' : ''; ?>>Obesity</option>
              <option value="Obstructive sleep apnoea" <?php echo ($record['chroniccondition'] == 'Obstructive sleep apnoea') ? 'selected' : ''; ?>>Obstructive sleep apnoea</option>
              <option value="Occupational lung disease (various)" <?php echo ($record['chroniccondition'] == 'Occupational lung disease (various)') ? 'selected' : ''; ?>>Occupational lung disease (various)</option>
              <option value="Osteoarthritis" <?php echo ($record['chroniccondition'] == 'Osteoarthritis') ? 'selected' : ''; ?>>Osteoarthritis</option>
              <option value="Osteoporosis" <?php echo ($record['chroniccondition'] == 'Osteoporosis') ? 'selected' : ''; ?>>Osteoporosis</option>
              <option value="Other slowly degenerative neurological conditions" <?php echo ($record['chroniccondition'] == 'Other slowly degenerative neurological conditions') ? 'selected' : ''; ?>>Other slowly degenerative neurological conditions</option>
              <option value="Peripheral vascular disease" <?php echo ($record['chroniccondition'] == 'Peripheral vascular disease') ? 'selected' : ''; ?>>Peripheral vascular disease</option>
              <option value="Personality disorders" <?php echo ($record['chroniccondition'] == 'Personality disorders') ? 'selected' : ''; ?>>Personality disorders</option>
              <option value="Phobias" <?php echo ($record['chroniccondition'] == 'Phobias') ? 'selected' : ''; ?>>Phobias</option>
              <option value="Physical disabilities" <?php echo ($record['chroniccondition'] == 'Physical disabilities') ? 'selected' : ''; ?>>Physical disabilities</option>
              <option value="Polycystic ovary disease" <?php echo ($record['chroniccondition'] == 'Polycystic ovary disease') ? 'selected' : ''; ?>>Polycystic ovary disease</option>
              <option value="Post-traumatic stress" <?php echo ($record['chroniccondition'] == 'Post-traumatic stress') ? 'selected' : ''; ?>>Post-traumatic stress</option>
              <option value="Progressive supranuclear palsy" <?php echo ($record['chroniccondition'] == 'Progressive supranuclear palsy') ? 'selected' : ''; ?>>Progressive supranuclear palsy</option>
              <option value="Psoriasis" <?php echo ($record['chroniccondition'] == 'Psoriasis') ? 'selected' : ''; ?>>Psoriasis</option>
              <option value="Rare disease, genetic disorders" <?php echo ($record['chroniccondition'] == 'Rare disease, genetic disorders') ? 'selected' : ''; ?>>Rare disease, genetic disorders</option>
              <option value="Sarcoidosis" <?php echo ($record['chroniccondition'] == 'Sarcoidosis') ? 'selected' : ''; ?>>Sarcoidosis</option>
              <option value="Sensory problems/disabilities (deafness/blindness)" <?php echo ($record['chroniccondition'] == 'Sensory problems/disabilities (deafness/blindness)') ? 'selected' : ''; ?>>Sensory problems/disabilities (deafness/blindness)</option>
              <option value="Severe skin conditions" <?php echo ($record['chroniccondition'] == 'Severe skin conditions') ? 'selected' : ''; ?>>Severe skin conditions</option>
              <option value="Sickle cell disease" <?php echo ($record['chroniccondition'] == 'Sickle cell disease') ? 'selected' : ''; ?>>Sickle cell disease</option>
              <option value="Skin conditions" <?php echo ($record['chroniccondition'] == 'Skin conditions') ? 'selected' : ''; ?>>Skin conditions</option>
              <option value="Sleep disorders" <?php echo ($record['chroniccondition'] == 'Sleep disorders') ? 'selected' : ''; ?>>Sleep disorders</option>
              <option value="Speech deficits" <?php echo ($record['chroniccondition'] == 'Speech deficits') ? 'selected' : ''; ?>>Speech deficits</option>
              <option value="Spina bifida" <?php echo ($record['chroniccondition'] == 'Spina bifida') ? 'selected' : ''; ?>>Spina bifida</option>
              <option value="Spinal injuries" <?php echo ($record['chroniccondition'] == 'Spinal injuries') ? 'selected' : ''; ?>>Spinal injuries</option>
              <option value="Stroke/transient ischaemic attacks" <?php echo ($record['chroniccondition'] == 'Stroke/transient ischaemic attacks') ? 'selected' : ''; ?>>Stroke/transient ischaemic attacks</option>
              <option value="Tuberculosis" <?php echo ($record['chroniccondition'] == 'Tuberculosis') ? 'selected' : ''; ?>>Tuberculosis</option>
              <option value="Urinary Incontinence" <?php echo ($record['chroniccondition'] == 'Urinary Incontinence') ? 'selected' : ''; ?>>Urinary Incontinence</option>
              <option value="Urticarial" <?php echo ($record['chroniccondition'] == 'Urticarial') ? 'selected' : ''; ?>>Urticarial</option>
            </select>
          </div>
          <div>
            <label for="surgeries">Surgeries</label>
            <select name="surgeries" id="surgeries" required>
              <option value="" disabled selected>Please select</option>
              <option value="Appendectomy" <?php echo ($record['surgeries'] == 'Appendectomy') ? 'selected' : ''; ?>>Appendectomy</option>
              <option value="Ankle fusion" <?php echo ($record['surgeries'] == 'Ankle fusion') ? 'selected' : ''; ?>>Ankle fusion</option>
              <option value="Ankle ligament repair" <?php echo ($record['surgeries'] == 'Ankle ligament repair') ? 'selected' : ''; ?>>Ankle ligament repair</option>
              <option value="Ankle/foot surgery" <?php echo ($record['surgeries'] == 'Ankle/foot surgery') ? 'selected' : ''; ?>>Ankle/foot surgery</option>
              <option value="Knee Arthroscopy" <?php echo ($record['surgeries'] == 'Knee Arthroscopy') ? 'selected' : ''; ?>>Knee arthroscopy</option>
              <option value="Blepharoplasty" <?php echo ($record['surgeries'] == 'Blepharoplasty') ? 'selected' : ''; ?>>Blepharoplasty (eyelid repair)</option>
              <option value="Breast enhancement procedures" <?php echo ($record['surgeries'] == 'Breast enhancement procedures') ? 'selected' : ''; ?>>Cosmetic breast enhancement procedures</option>
              <option value="Breast biopsies" <?php echo ($record['surgeries'] == 'Breast biopsies') ? 'selected' : ''; ?>>Breast biopsies</option>
              <option value="Bronchoscopy" <?php echo ($record['surgeries'] == 'Bronchoscopy') ? 'selected' : ''; ?>>Bronchoscopy</option>
              <option value="Bunionectomy" <?php echo ($record['surgeries'] == 'Bunionectomy') ? 'selected' : ''; ?>>Bunionectomy</option>
              <option value="Carpal tunnel release" <?php echo ($record['surgeries'] == 'Carpal tunnel release') ? 'selected' : ''; ?>>Nerve releases (carpal tunnel/cubital tunnel)</option>
              <option value="Cesarean section" <?php echo ($record['surgeries'] == 'Cesarean section') ? 'selected' : ''; ?>>Cesarean section</option>
              <option value="Chest procedures" <?php echo ($record['surgeries'] == 'Chest procedures') ? 'selected' : ''; ?>>Chest procedures</option>
              <option value="Cystoscopy" <?php echo ($record['surgeries'] == 'Cystoscopy') ? 'selected' : ''; ?>>Cystoscopy</option>
              <option value="Diagnostic pelvic laparoscopy" <?php echo ($record['surgeries'] == 'Diagnostic pelvic laparoscopy') ? 'selected' : ''; ?>>Diagnostic pelvic laparoscopy</option>
              <option value="Elbow/wrist/hand surgery" <?php echo ($record['surgeries'] == 'Elbow/wrist/hand surgery') ? 'selected' : ''; ?>>Elbow/wrist/hand surgery</option>
              <option value="Gallbladder procedures" <?php echo ($record['surgeries'] == 'Gallbladder procedures') ? 'selected' : ''; ?>>Gallbladder procedures</option>
              <option value="Hammer toe" <?php echo ($record['surgeries'] == 'Hammer toe') ? 'selected' : ''; ?>>Hammer toe</option>
              <option value="Hernia repair" <?php echo ($record['surgeries'] == 'Hernia repair') ? 'selected' : ''; ?>>Hernia repair</option>
              <option value="Kidney stone extraction" <?php echo ($record['surgeries'] == 'Kidney stone extraction') ? 'selected' : ''; ?>>Kidney stone extraction (with holmium laser or stone basket)</option>
              <option value="Laparoscopic assisted colon surgery" <?php echo ($record['surgeries'] == 'Laparoscopic assisted colon surgery') ? 'selected' : ''; ?>>Laparoscopic assisted colon surgery</option>
              <option value="Laparoscopic assisted radical prostatectomy with lymph node dissection" <?php echo ($record['surgeries'] == 'Laparoscopic assisted radical prostatectomy with lymph node dissection') ? 'selected' : ''; ?>>Laparoscopic assisted radical prostatectomy with lymph node dissection</option>
              <option value="Laparoscopic procedures" <?php echo ($record['surgeries'] == 'Laparoscopic procedures') ? 'selected' : ''; ?>>Laparoscopic procedures</option>
              <option value="Lateral epicodylitis debridement/repair" <?php echo ($record['surgeries'] == 'Lateral epicodylitis debridement/repair') ? 'selected' : ''; ?>>Lateral epicodylitis debridement/repair</option>
              <option value="Lipossuction" <?php echo ($record['surgeries'] == 'Lipossuction') ? 'selected' : ''; ?>>Liposuction</option>
              <option value="Lung procedures" <?php echo ($record['surgeries'] == 'Lung procedures') ? 'selected' : ''; ?>>Lung procedures</option>
              <option value="Microwave endometrial ablation" <?php echo ($record['surgeries'] == 'Microwave endometrial ablation') ? 'selected' : ''; ?>>Microwave endometrial ablation</option>
              <option value="Mastectomy with reconstruction" <?php echo ($record['surgeries'] == 'Mastectomy with reconstruction') ? 'selected' : ''; ?>>Mastectomy with reconstruction</option>
              <option value="Nephrectomy" <?php echo ($record['surgeries'] == 'Nephrectomy') ? 'selected' : ''; ?>>Nephrectomy (kidney removal)</option>
              <option value="Nissen fundoplication" <?php echo ($record['surgeries'] == 'Nissen fundoplication') ? 'selected' : ''; ?>>Nissen fundoplication</option>
              <option value="Prostatectomy" <?php echo ($record['surgeries'] == 'Prostatectomy') ? 'selected' : ''; ?>>Prostatectomy</option>
              <option value="Radical retropubic (open) prostatectomy with lymph node dissection" <?php echo ($record['surgeries'] == 'Radical retropubic (open) prostatectomy with lymph node dissection') ? 'selected' : ''; ?>>Radical retropubic (open) prostatectomy with lymph node dissection</option>
              <option value="Rotator cuff repair/shoulder surgery" <?php echo ($record['surgeries'] == 'Rotator cuff repair/shoulder surgery') ? 'selected' : ''; ?>>Rotator cuff repair/shoulder surgery</option>
              <option value="Tendon/ligament repair" <?php echo ($record['surgeries'] == 'Tendon/ligament repair') ? 'selected' : ''; ?>>Tendon/ligament repair</option>
              <option value="Thyroidectomy" <?php echo ($record['surgeries'] == 'Thyroidectomy') ? 'selected' : ''; ?>>Thyroidectomy</option>
              <option value="Tonsillectomy and adenoidectomy" <?php echo ($record['surgeries'] == 'Tonsillectomy and adenoidectomy') ? 'selected' : ''; ?>>Tonsillectomy and adenoidectomy</option>
              <option value="Total abdominal hysterectomy" <?php echo ($record['surgeries'] == 'Total abdominal hysterectomy') ? 'selected' : ''; ?>>Total abdominal hysterectomy</option>
              <option value="Total laparoscopic hysterectomy" <?php echo ($record['surgeries'] == 'Total laparoscopic hysterectomy') ? 'selected' : ''; ?>>Total laparoscopic hysterectomy</option>
              <option value="Trigger finger release" <?php echo ($record['surgeries'] == 'Trigger finger release') ? 'selected' : ''; ?>>Trigger finger release</option>
              <option value="Transurethral resection of the prostate (TURP)" <?php echo ($record['surgeries'] == 'Transurethral resection of the prostate (TURP)') ? 'selected' : ''; ?>>Transurethral resection of the prostate (TURP)</option>
              <option value="Tubal ligation" <?php echo ($record['surgeries'] == 'Tubal ligation') ? 'selected' : ''; ?>>Tubal ligation</option>
              <option value="Tubes in the eardrums (Myringotomy)" <?php echo ($record['surgeries'] == 'Tubes in the eardrums (Myringotomy)') ? 'selected' : ''; ?>>Tubes in the eardrums (Myringotomy)</option>
              <option value="Ureteroscopy" <?php echo ($record['surgeries'] == 'Ureteroscopy') ? 'selected' : ''; ?>>Ureteroscopy</option>
              <option value="Urticarial" <?php echo ($record['surgeries'] == 'Urticarial') ? 'selected' : ''; ?>>Urticarial</option>
              <option value="Vaginal hysterectomy" <?php echo ($record['surgeries'] == 'Vaginal hysterectomy') ? 'selected' : ''; ?>>Vaginal hysterectomy</option>
              <option value="Vein stripping" <?php echo ($record['surgeries'] == 'Vein stripping') ? 'selected' : ''; ?>>Vein stripping</option>
            </select>
          </div>
       </div>
      </fieldset>

      <fieldset>
        <legend>
          <h3>Immunization</h3>
        </legend>
        <div id="immunization-fields">
        <div class="immunization-fields">
          <div class="account-details">
              <div>
                <label for="vaccine">Vaccine</label>
                <input type="text" placeholder="Vaccine Name" name="vaccine" id="vaccine" value="<?php echo htmlspecialchars($record['vaccine'] ?? ''); ?>" >
                <span id="vaccine-error" class="error-message"></span>
              </div>
              <div>
                <label for="date-administered">Date Administered</label>
                <input type="text" placeholder="eg. 2020"  name="date-administered" id="date-administered" value="<?php echo htmlspecialchars($record['dateadministered'] ?? ''); ?>" >
              </div>
            </div>
          </div>
       </div>
      </fieldset>

      <fieldset>
        <legend>
          <h3>Medication</h3>
        </legend>
        <div id="medications-fields">
          <div class="medications-fields">
          <div class="account-details2">
            <div>
              <label style="padding: 0%;padding-bottom: 4%;">Medication</label>              
              <input type="text" placeholder="" name="medication" id="medication" required value="<?php echo htmlspecialchars($record['medication'] ?? ''); ?>">
            </div>
            <div>
              <label style="padding: 0%;padding-bottom: 4%;">Dosage</label>
              <input type="text" placeholder="eg.10mg" name="dosage" id="dosage" required value="<?php echo htmlspecialchars($record['dosage'] ?? ''); ?>">
            </div>
            <div>
              <label style="padding: 0%;padding-bottom: 4%;">Frequency</label>
              <input type="text" placeholder="e.g Twice daily" name="frequency" id="frequency" required value="<?php echo htmlspecialchars($record['frequency'] ?? ''); ?>">
            </div>
            </div>
          </div>
       </div>
      </fieldset>

      <fieldset>
        <legend>
          <h3>Health Metrics</h3>
        </legend>
        <div class="account-details">
          <div>
            <label for="blood-pressure">Blood Pressure</label>
            <select name="blood-pressure" id="blood-pressure" required>
              <option value="" disabled selected>Please select</option>
              <option value="unknown" <?php echo ($record['blood_pressure'] == 'unknown') ? 'selected' : ''; ?>>Unknown</option>
              <option value="120/80" <?php echo ($record['blood_pressure'] == '120/80') ? 'selected' : ''; ?>>120/80</option>
              <option value="121/80" <?php echo ($record['blood_pressure'] == '121/80') ? 'selected' : ''; ?>>121/80</option>
              <option value="122/80" <?php echo ($record['blood_pressure'] == '122/80') ? 'selected' : ''; ?>>122/80</option>
              <option value="123/81" <?php echo ($record['blood_pressure'] == '123/81') ? 'selected' : ''; ?>>123/81</option>
              <option value="124/81" <?php echo ($record['blood_pressure'] == '124/81') ? 'selected' : ''; ?>>124/81</option>
              <option value="125/82" <?php echo ($record['blood_pressure'] == '125/82') ? 'selected' : ''; ?>>125/82</option>
              <option value="126/82" <?php echo ($record['blood_pressure'] == '126/82') ? 'selected' : ''; ?>>126/82</option>
              <option value="127/83" <?php echo ($record['blood_pressure'] == '127/83') ? 'selected' : ''; ?>>127/83</option>
              <option value="128/83" <?php echo ($record['blood_pressure'] == '128/83') ? 'selected' : ''; ?>>128/83</option>
              <option value="129/84" <?php echo ($record['blood_pressure'] == '129/84') ? 'selected' : ''; ?>>129/84</option>
              <option value="130/85" <?php echo ($record['blood_pressure'] == '130/85') ? 'selected' : ''; ?>>130/85</option>
              <option value="131/85" <?php echo ($record['blood_pressure'] == '131/85') ? 'selected' : ''; ?>>131/85</option>
              <option value="132/86" <?php echo ($record['blood_pressure'] == '132/86') ? 'selected' : ''; ?>>132/86</option>
              <option value="133/87" <?php echo ($record['blood_pressure'] == '133/87') ? 'selected' : ''; ?>>133/87</option>
              <option value="134/87" <?php echo ($record['blood_pressure'] == '134/87') ? 'selected' : ''; ?>>134/87</option>
              <option value="135/88" <?php echo ($record['blood_pressure'] == '135/88') ? 'selected' : ''; ?>>135/88</option>
              <option value="136/88" <?php echo ($record['blood_pressure'] == '136/88') ? 'selected' : ''; ?>>136/88</option>
              <option value="137/89" <?php echo ($record['blood_pressure'] == '137/89') ? 'selected' : ''; ?>>137/89</option>
              <option value="138/89" <?php echo ($record['blood_pressure'] == '138/89') ? 'selected' : ''; ?>>138/89</option>
              <option value="139/90" <?php echo ($record['blood_pressure'] == '139/90') ? 'selected' : ''; ?>>139/90</option>
              <option value="140/90" <?php echo ($record['blood_pressure'] == '140/90') ? 'selected' : ''; ?>>140/90</option>
              <option value="141/91" <?php echo ($record['blood_pressure'] == '141/91') ? 'selected' : ''; ?>>141/91</option>
              <option value="142/92" <?php echo ($record['blood_pressure'] == '142/92') ? 'selected' : ''; ?>>142/92</option>
              <option value="143/92" <?php echo ($record['blood_pressure'] == '143/92') ? 'selected' : ''; ?>>143/92</option>
              <option value="144/93" <?php echo ($record['blood_pressure'] == '144/93') ? 'selected' : ''; ?>>144/93</option>
              <option value="145/94" <?php echo ($record['blood_pressure'] == '145/94') ? 'selected' : ''; ?>>145/94</option>
              <option value="146/95" <?php echo ($record['blood_pressure'] == '146/95') ? 'selected' : ''; ?>>146/95</option>
              <option value="147/96" <?php echo ($record['blood_pressure'] == '147/96') ? 'selected' : ''; ?>>147/96</option>
              <option value="148/96" <?php echo ($record['blood_pressure'] == '148/96') ? 'selected' : ''; ?>>148/96</option>
              <option value="149/97" <?php echo ($record['blood_pressure'] == '149/97') ? 'selected' : ''; ?>>149/97</option>
              <option value="150/98" <?php echo ($record['blood_pressure'] == '150/98') ? 'selected' : ''; ?>>150/98</option>
              <option value="151/99" <?php echo ($record['blood_pressure'] == '151/99') ? 'selected' : ''; ?>>151/99</option>
              <option value="152/100" <?php echo ($record['blood_pressure'] == '152/100') ? 'selected' : ''; ?>>152/100</option>
              <option value="153/100" <?php echo ($record['blood_pressure'] == '153/100') ? 'selected' : ''; ?>>153/100</option>
              <option value="154/101" <?php echo ($record['blood_pressure'] == '154/101') ? 'selected' : ''; ?>>154/101</option>
              <option value="155/102" <?php echo ($record['blood_pressure'] == '155/102') ? 'selected' : ''; ?>>155/102</option>
              <option value="156/103" <?php echo ($record['blood_pressure'] == '156/103') ? 'selected' : ''; ?>>156/103</option>
              <option value="157/104" <?php echo ($record['blood_pressure'] == '157/104') ? 'selected' : ''; ?>>157/104</option>
              <option value="158/105" <?php echo ($record['blood_pressure'] == '158/105') ? 'selected' : ''; ?>>158/105</option>
              <option value="159/106" <?php echo ($record['blood_pressure'] == '159/106') ? 'selected' : ''; ?>>159/106</option>
              <option value="160/107" <?php echo ($record['blood_pressure'] == '160/107') ? 'selected' : ''; ?>>160/107</option>
              <option value="161/108" <?php echo ($record['blood_pressure'] == '161/108') ? 'selected' : ''; ?>>161/108</option>
              <option value="162/109" <?php echo ($record['blood_pressure'] == '162/109') ? 'selected' : ''; ?>>162/109</option>
              <option value="163/110" <?php echo ($record['blood_pressure'] == '163/110') ? 'selected' : ''; ?>>163/110</option>
              <option value="164/111" <?php echo ($record['blood_pressure'] == '164/111') ? 'selected' : ''; ?>>164/111</option>
              <option value="165/112" <?php echo ($record['blood_pressure'] == '165/112') ? 'selected' : ''; ?>>165/112</option>
              <option value="166/113" <?php echo ($record['blood_pressure'] == '166/113') ? 'selected' : ''; ?>>166/113</option>
              <option value="167/114" <?php echo ($record['blood_pressure'] == '167/114') ? 'selected' : ''; ?>>167/114</option>
              <option value="168/115" <?php echo ($record['blood_pressure'] == '168/115') ? 'selected' : ''; ?>>168/115</option>
              <option value="169/116" <?php echo ($record['blood_pressure'] == '169/116') ? 'selected' : ''; ?>>169/116</option>
              <option value="170/117" <?php echo ($record['blood_pressure'] == '170/117') ? 'selected' : ''; ?>>170/117</option>
              <option value="171/118" <?php echo ($record['blood_pressure'] == '171/118') ? 'selected' : ''; ?>>171/118</option>
              <option value="172/119" <?php echo ($record['blood_pressure'] == '172/119') ? 'selected' : ''; ?>>172/119</option>
              <option value="180/120" <?php echo ($record['blood_pressure'] == '180/120') ? 'selected' : ''; ?>>180/120</option>
            </select>
          </div>
          <div>
            <label for="weight">Weight</label>
            <select name="weight" id="weight" onchange="calculateBMI()" required>
              <option value="" disabled selected>Please select</option>
              <option value="30" <?php echo ($record['weight'] == '30') ? 'selected' : ''; ?>>30 kg</option>
              <option value="31" <?php echo ($record['weight'] == '31') ? 'selected' : ''; ?>>31 kg</option>
              <option value="32" <?php echo ($record['weight'] == '32') ? 'selected' : ''; ?>>32 kg</option>
              <option value="33" <?php echo ($record['weight'] == '33') ? 'selected' : ''; ?>>33 kg</option>
              <option value="34" <?php echo ($record['weight'] == '34') ? 'selected' : ''; ?>>34 kg</option>
              <option value="35" <?php echo ($record['weight'] == '35') ? 'selected' : ''; ?>>35 kg</option>
              <option value="36" <?php echo ($record['weight'] == '36') ? 'selected' : ''; ?>>36 kg</option>
              <option value="37" <?php echo ($record['weight'] == '37') ? 'selected' : ''; ?>>37 kg</option>
              <option value="38" <?php echo ($record['weight'] == '38') ? 'selected' : ''; ?>>38 kg</option>
              <option value="39" <?php echo ($record['weight'] == '39') ? 'selected' : ''; ?>>39 kg</option>
              <option value="40" <?php echo ($record['weight'] == '40') ? 'selected' : ''; ?>>40 kg</option>
              <option value="41" <?php echo ($record['weight'] == '41') ? 'selected' : ''; ?>>41 kg</option>
              <option value="42" <?php echo ($record['weight'] == '42') ? 'selected' : ''; ?>>42 kg</option>
              <option value="43" <?php echo ($record['weight'] == '43') ? 'selected' : ''; ?>>43 kg</option>
              <option value="44" <?php echo ($record['weight'] == '44') ? 'selected' : ''; ?>>44 kg</option>
              <option value="45" <?php echo ($record['weight'] == '45') ? 'selected' : ''; ?>>45 kg</option>
              <option value="46" <?php echo ($record['weight'] == '46') ? 'selected' : ''; ?>>46 kg</option>
              <option value="47" <?php echo ($record['weight'] == '47') ? 'selected' : ''; ?>>47 kg</option>
              <option value="48" <?php echo ($record['weight'] == '48') ? 'selected' : ''; ?>>48 kg</option>
              <option value="49" <?php echo ($record['weight'] == '49') ? 'selected' : ''; ?>>49 kg</option>
              <option value="50" <?php echo ($record['weight'] == '50') ? 'selected' : ''; ?>>50 kg</option>
              <option value="51" <?php echo ($record['weight'] == '51') ? 'selected' : ''; ?>>51 kg</option>
              <option value="52" <?php echo ($record['weight'] == '52') ? 'selected' : ''; ?>>52 kg</option>
              <option value="53" <?php echo ($record['weight'] == '53') ? 'selected' : ''; ?>>53 kg</option>
              <option value="54" <?php echo ($record['weight'] == '54') ? 'selected' : ''; ?>>54 kg</option>
              <option value="55" <?php echo ($record['weight'] == '55') ? 'selected' : ''; ?>>55 kg</option>
              <option value="56" <?php echo ($record['weight'] == '56') ? 'selected' : ''; ?>>56 kg</option>
              <option value="57" <?php echo ($record['weight'] == '57') ? 'selected' : ''; ?>>57 kg</option>
              <option value="58" <?php echo ($record['weight'] == '58') ? 'selected' : ''; ?>>58 kg</option>
              <option value="59" <?php echo ($record['weight'] == '59') ? 'selected' : ''; ?>>59 kg</option>
              <option value="60" <?php echo ($record['weight'] == '60') ? 'selected' : ''; ?>>60 kg</option>
              <option value="61" <?php echo ($record['weight'] == '61') ? 'selected' : ''; ?>>61 kg</option>
              <option value="62" <?php echo ($record['weight'] == '62') ? 'selected' : ''; ?>>62 kg</option>
              <option value="63" <?php echo ($record['weight'] == '63') ? 'selected' : ''; ?>>63 kg</option>
              <option value="64" <?php echo ($record['weight'] == '64') ? 'selected' : ''; ?>>64 kg</option>
              <option value="65" <?php echo ($record['weight'] == '65') ? 'selected' : ''; ?>>65 kg</option>
              <option value="66" <?php echo ($record['weight'] == '66') ? 'selected' : ''; ?>>66 kg</option>
              <option value="67" <?php echo ($record['weight'] == '67') ? 'selected' : ''; ?>>67 kg</option>
              <option value="68" <?php echo ($record['weight'] == '68') ? 'selected' : ''; ?>>68 kg</option>
              <option value="69" <?php echo ($record['weight'] == '69') ? 'selected' : ''; ?>>69 kg</option>
              <option value="70" <?php echo ($record['weight'] == '70') ? 'selected' : ''; ?>>70 kg</option>
              <option value="71" <?php echo ($record['weight'] == '71') ? 'selected' : ''; ?>>71 kg</option>
              <option value="72" <?php echo ($record['weight'] == '72') ? 'selected' : ''; ?>>72 kg</option>
              <option value="73" <?php echo ($record['weight'] == '73') ? 'selected' : ''; ?>>73 kg</option>
              <option value="74" <?php echo ($record['weight'] == '74') ? 'selected' : ''; ?>>74 kg</option>
              <option value="75" <?php echo ($record['weight'] == '75') ? 'selected' : ''; ?>>75 kg</option>
              <option value="76" <?php echo ($record['weight'] == '76') ? 'selected' : ''; ?>>76 kg</option>
              <option value="77" <?php echo ($record['weight'] == '77') ? 'selected' : ''; ?>>77 kg</option>
              <option value="78" <?php echo ($record['weight'] == '78') ? 'selected' : ''; ?>>78 kg</option>
              <option value="79" <?php echo ($record['weight'] == '79') ? 'selected' : ''; ?>>79 kg</option>
              <option value="80" <?php echo ($record['weight'] == '80') ? 'selected' : ''; ?>>80 kg</option>
              <option value="81" <?php echo ($record['weight'] == '81') ? 'selected' : ''; ?>>81 kg</option>
              <option value="82" <?php echo ($record['weight'] == '82') ? 'selected' : ''; ?>>82 kg</option>
              <option value="83" <?php echo ($record['weight'] == '83') ? 'selected' : ''; ?>>83 kg</option>
              <option value="84" <?php echo ($record['weight'] == '84') ? 'selected' : ''; ?>>84 kg</option>
              <option value="85" <?php echo ($record['weight'] == '85') ? 'selected' : ''; ?>>85 kg</option>
              <option value="86" <?php echo ($record['weight'] == '86') ? 'selected' : ''; ?>>86 kg</option>
              <option value="87" <?php echo ($record['weight'] == '87') ? 'selected' : ''; ?>>87 kg</option>
              <option value="88" <?php echo ($record['weight'] == '88') ? 'selected' : ''; ?>>88 kg</option>
              <option value="89" <?php echo ($record['weight'] == '89') ? 'selected' : ''; ?>>89 kg</option>
              <option value="90" <?php echo ($record['weight'] == '90') ? 'selected' : ''; ?>>90 kg</option>
              <option value="91" <?php echo ($record['weight'] == '91') ? 'selected' : ''; ?>>91 kg</option>
              <option value="92" <?php echo ($record['weight'] == '92') ? 'selected' : ''; ?>>92 kg</option>
              <option value="93" <?php echo ($record['weight'] == '93') ? 'selected' : ''; ?>>93 kg</option>
              <option value="94" <?php echo ($record['weight'] == '94') ? 'selected' : ''; ?>>94 kg</option>
              <option value="95" <?php echo ($record['weight'] == '95') ? 'selected' : ''; ?>>95 kg</option>
              <option value="96" <?php echo ($record['weight'] == '96') ? 'selected' : ''; ?>>96 kg</option>
              <option value="97" <?php echo ($record['weight'] == '97') ? 'selected' : ''; ?>>97 kg</option>
              <option value="98" <?php echo ($record['weight'] == '98') ? 'selected' : ''; ?>>98 kg</option>
              <option value="99" <?php echo ($record['weight'] == '99') ? 'selected' : ''; ?>>99 kg</option>
              <option value="100" <?php echo ($record['weight'] == '100') ? 'selected' : ''; ?>>100 kg</option>
              <option value="101" <?php echo ($record['weight'] == '101') ? 'selected' : ''; ?>>101 kg</option>
              <option value="102" <?php echo ($record['weight'] == '102') ? 'selected' : ''; ?>>102 kg</option>
              <option value="103" <?php echo ($record['weight'] == '103') ? 'selected' : ''; ?>>103 kg</option>
              <option value="104" <?php echo ($record['weight'] == '104') ? 'selected' : ''; ?>>104 kg</option>
              <option value="105" <?php echo ($record['weight'] == '105') ? 'selected' : ''; ?>>105 kg</option>
              <option value="106" <?php echo ($record['weight'] == '106') ? 'selected' : ''; ?>>106 kg</option>
              <option value="107" <?php echo ($record['weight'] == '107') ? 'selected' : ''; ?>>107 kg</option>
              <option value="108" <?php echo ($record['weight'] == '108') ? 'selected' : ''; ?>>108 kg</option>
              <option value="109" <?php echo ($record['weight'] == '109') ? 'selected' : ''; ?>>109 kg</option>
              <option value="110" <?php echo ($record['weight'] == '110') ? 'selected' : ''; ?>>110 kg</option>
              <option value="111" <?php echo ($record['weight'] == '111') ? 'selected' : ''; ?>>111 kg</option>
              <option value="112" <?php echo ($record['weight'] == '112') ? 'selected' : ''; ?>>112 kg</option>
              <option value="113" <?php echo ($record['weight'] == '113') ? 'selected' : ''; ?>>113 kg</option>
              <option value="114" <?php echo ($record['weight'] == '114') ? 'selected' : ''; ?>>114 kg</option>
              <option value="115" <?php echo ($record['weight'] == '115') ? 'selected' : ''; ?>>115 kg</option>
              <option value="116" <?php echo ($record['weight'] == '116') ? 'selected' : ''; ?>>116 kg</option>
              <option value="117" <?php echo ($record['weight'] == '117') ? 'selected' : ''; ?>>117 kg</option>
              <option value="118" <?php echo ($record['weight'] == '118') ? 'selected' : ''; ?>>118 kg</option>
              <option value="119" <?php echo ($record['weight'] == '119') ? 'selected' : ''; ?>>119 kg</option>
              <option value="120" <?php echo ($record['weight'] == '120') ? 'selected' : ''; ?>>120 kg</option>
              <option value="121" <?php echo ($record['weight'] == '121') ? 'selected' : ''; ?>>121 kg</option>
              <option value="122" <?php echo ($record['weight'] == '122') ? 'selected' : ''; ?>>122 kg</option>
              <option value="123" <?php echo ($record['weight'] == '123') ? 'selected' : ''; ?>>123 kg</option>
              <option value="124" <?php echo ($record['weight'] == '124') ? 'selected' : ''; ?>>124 kg</option>
              <option value="125" <?php echo ($record['weight'] == '125') ? 'selected' : ''; ?>>125 kg</option>
              <option value="126" <?php echo ($record['weight'] == '126') ? 'selected' : ''; ?>>126 kg</option>
              <option value="127" <?php echo ($record['weight'] == '127') ? 'selected' : ''; ?>>127 kg</option>
              <option value="128" <?php echo ($record['weight'] == '128') ? 'selected' : ''; ?>>128 kg</option>
              <option value="129" <?php echo ($record['weight'] == '129') ? 'selected' : ''; ?>>129 kg</option>
              <option value="130" <?php echo ($record['weight'] == '130') ? 'selected' : ''; ?>>130 kg</option>
              <option value="131" <?php echo ($record['weight'] == '131') ? 'selected' : ''; ?>>131 kg</option>
              <option value="132" <?php echo ($record['weight'] == '132') ? 'selected' : ''; ?>>132 kg</option>
              <option value="133" <?php echo ($record['weight'] == '133') ? 'selected' : ''; ?>>133 kg</option>
              <option value="134" <?php echo ($record['weight'] == '134') ? 'selected' : ''; ?>>134 kg</option>
              <option value="135" <?php echo ($record['weight'] == '135') ? 'selected' : ''; ?>>135 kg</option>
              <option value="136" <?php echo ($record['weight'] == '136') ? 'selected' : ''; ?>>136 kg</option>
              <option value="137" <?php echo ($record['weight'] == '137') ? 'selected' : ''; ?>>137 kg</option>
              <option value="138" <?php echo ($record['weight'] == '138') ? 'selected' : ''; ?>>138 kg</option>
              <option value="139" <?php echo ($record['weight'] == '139') ? 'selected' : ''; ?>>139 kg</option>
              <option value="140" <?php echo ($record['weight'] == '140') ? 'selected' : ''; ?>>140 kg</option>
              <option value="141" <?php echo ($record['weight'] == '141') ? 'selected' : ''; ?>>141 kg</option>
              <option value="142" <?php echo ($record['weight'] == '142') ? 'selected' : ''; ?>>142 kg</option>
              <option value="143" <?php echo ($record['weight'] == '143') ? 'selected' : ''; ?>>143 kg</option>
              <option value="144" <?php echo ($record['weight'] == '144') ? 'selected' : ''; ?>>144 kg</option>
              <option value="145" <?php echo ($record['weight'] == '145') ? 'selected' : ''; ?>>145 kg</option>
              <option value="146" <?php echo ($record['weight'] == '146') ? 'selected' : ''; ?>>146 kg</option>
              <option value="147" <?php echo ($record['weight'] == '147') ? 'selected' : ''; ?>>147 kg</option>
              <option value="148" <?php echo ($record['weight'] == '148') ? 'selected' : ''; ?>>148 kg</option>
              <option value="149" <?php echo ($record['weight'] == '149') ? 'selected' : ''; ?>>149 kg</option>
              <option value="150" <?php echo ($record['weight'] == '150') ? 'selected' : ''; ?>>150 kg</option>
              <option value="151" <?php echo ($record['weight'] == '151') ? 'selected' : ''; ?>>151 kg</option>
              <option value="152" <?php echo ($record['weight'] == '152') ? 'selected' : ''; ?>>152 kg</option>
              <option value="153" <?php echo ($record['weight'] == '153') ? 'selected' : ''; ?>>153 kg</option>
              <option value="154" <?php echo ($record['weight'] == '154') ? 'selected' : ''; ?>>154 kg</option>
              <option value="155" <?php echo ($record['weight'] == '155') ? 'selected' : ''; ?>>155 kg</option>
              <option value="156" <?php echo ($record['weight'] == '156') ? 'selected' : ''; ?>>156 kg</option>
              <option value="157" <?php echo ($record['weight'] == '157') ? 'selected' : ''; ?>>157 kg</option>
              <option value="158" <?php echo ($record['weight'] == '158') ? 'selected' : ''; ?>>158 kg</option>
              <option value="159" <?php echo ($record['weight'] == '159') ? 'selected' : ''; ?>>159 kg</option>
              <option value="160" <?php echo ($record['weight'] == '160') ? 'selected' : ''; ?>>160 kg</option>
              <option value="161" <?php echo ($record['weight'] == '161') ? 'selected' : ''; ?>>161 kg</option>
              <option value="162" <?php echo ($record['weight'] == '162') ? 'selected' : ''; ?>>162 kg</option>
              <option value="163" <?php echo ($record['weight'] == '163') ? 'selected' : ''; ?>>163 kg</option>
              <option value="164" <?php echo ($record['weight'] == '164') ? 'selected' : ''; ?>>164 kg</option>
              <option value="165" <?php echo ($record['weight'] == '165') ? 'selected' : ''; ?>>165 kg</option>
              <option value="166" <?php echo ($record['weight'] == '166') ? 'selected' : ''; ?>>166 kg</option>
              <option value="167" <?php echo ($record['weight'] == '167') ? 'selected' : ''; ?>>167 kg</option>
              <option value="168" <?php echo ($record['weight'] == '168') ? 'selected' : ''; ?>>168 kg</option>
              <option value="169" <?php echo ($record['weight'] == '169') ? 'selected' : ''; ?>>169 kg</option>
              <option value="170" <?php echo ($record['weight'] == '170') ? 'selected' : ''; ?>>170 kg</option>
              <option value="171" <?php echo ($record['weight'] == '171') ? 'selected' : ''; ?>>171 kg</option>
              <option value="172" <?php echo ($record['weight'] == '172') ? 'selected' : ''; ?>>172 kg</option>
              <option value="173" <?php echo ($record['weight'] == '173') ? 'selected' : ''; ?>>173 kg</option>
              <option value="174" <?php echo ($record['weight'] == '174') ? 'selected' : ''; ?>>174 kg</option>
              <option value="175" <?php echo ($record['weight'] == '175') ? 'selected' : ''; ?>>175 kg</option>
              <option value="176" <?php echo ($record['weight'] == '176') ? 'selected' : ''; ?>>176 kg</option>
              <option value="177" <?php echo ($record['weight'] == '177') ? 'selected' : ''; ?>>177 kg</option>
              <option value="178" <?php echo ($record['weight'] == '178') ? 'selected' : ''; ?>>178 kg</option>
              <option value="179" <?php echo ($record['weight'] == '179') ? 'selected' : ''; ?>>179 kg</option>
              <option value="180" <?php echo ($record['weight'] == '180') ? 'selected' : ''; ?>>180 kg</option>
              <option value="181" <?php echo ($record['weight'] == '181') ? 'selected' : ''; ?>>181 kg</option>
              <option value="182" <?php echo ($record['weight'] == '182') ? 'selected' : ''; ?>>182 kg</option>
              <option value="183" <?php echo ($record['weight'] == '183') ? 'selected' : ''; ?>>183 kg</option>
              <option value="184" <?php echo ($record['weight'] == '184') ? 'selected' : ''; ?>>184 kg</option>
              <option value="185" <?php echo ($record['weight'] == '185') ? 'selected' : ''; ?>>185 kg</option>
              <option value="186" <?php echo ($record['weight'] == '186') ? 'selected' : ''; ?>>186 kg</option>
              <option value="187" <?php echo ($record['weight'] == '187') ? 'selected' : ''; ?>>187 kg</option>
              <option value="188" <?php echo ($record['weight'] == '188') ? 'selected' : ''; ?>>188 kg</option>
              <option value="189" <?php echo ($record['weight'] == '189') ? 'selected' : ''; ?>>189 kg</option>
              <option value="190" <?php echo ($record['weight'] == '190') ? 'selected' : ''; ?>>190 kg</option>
              <option value="191" <?php echo ($record['weight'] == '191') ? 'selected' : ''; ?>>191 kg</option>
              <option value="192" <?php echo ($record['weight'] == '192') ? 'selected' : ''; ?>>192 kg</option>
              <option value="193" <?php echo ($record['weight'] == '193') ? 'selected' : ''; ?>>193 kg</option>
              <option value="194" <?php echo ($record['weight'] == '194') ? 'selected' : ''; ?>>194 kg</option>
              <option value="195" <?php echo ($record['weight'] == '195') ? 'selected' : ''; ?>>195 kg</option>
              <option value="196" <?php echo ($record['weight'] == '196') ? 'selected' : ''; ?>>196 kg</option>
              <option value="197" <?php echo ($record['weight'] == '197') ? 'selected' : ''; ?>>197 kg</option>
              <option value="198" <?php echo ($record['weight'] == '198') ? 'selected' : ''; ?>>198 kg</option>
              <option value="199" <?php echo ($record['weight'] == '199') ? 'selected' : ''; ?>>199 kg</option>
              <option value="200" <?php echo ($record['weight'] == '200') ? 'selected' : ''; ?>>200 kg</option>
            </select>
          </div>
          <div>
            <label for="heart-rate">Heart Rate</label>
            <select name="heart-rate" id="heart-rate" required>
              <option value="" disabled selected>Please select</option>
              <option value="Unknown" <?php echo ($record['heart_rate'] == 'Unknown') ? 'selected' : ''; ?>>Unknown</option>
              <option value="90" <?php echo ($record['heart_rate'] == '90') ? 'selected' : ''; ?>>90 </option>
              <option value="91" <?php echo ($record['heart_rate'] == '91') ? 'selected' : ''; ?>>91 </option>
              <option value="92" <?php echo ($record['heart_rate'] == '92') ? 'selected' : ''; ?>>92 </option>
              <option value="93" <?php echo ($record['heart_rate'] == '93') ? 'selected' : ''; ?>>93 </option>
              <option value="94" <?php echo ($record['heart_rate'] == '94') ? 'selected' : ''; ?>>94 </option>
              <option value="95" <?php echo ($record['heart_rate'] == '95') ? 'selected' : ''; ?>>95 </option>
              <option value="96" <?php echo ($record['heart_rate'] == '96') ? 'selected' : ''; ?>>96 </option>
              <option value="97" <?php echo ($record['heart_rate'] == '97') ? 'selected' : ''; ?>>97 </option>
              <option value="98" <?php echo ($record['heart_rate'] == '98') ? 'selected' : ''; ?>>98 </option>
              <option value="99" <?php echo ($record['heart_rate'] == '99') ? 'selected' : ''; ?>>99 </option>
              <option value="100" <?php echo ($record['heart_rate'] == '100') ? 'selected' : ''; ?>>100 </option>
              <option value="101" <?php echo ($record['heart_rate'] == '101') ? 'selected' : ''; ?>>101 </option>
              <option value="102" <?php echo ($record['heart_rate'] == '102') ? 'selected' : ''; ?>>102 </option>
              <option value="103" <?php echo ($record['heart_rate'] == '103') ? 'selected' : ''; ?>>103 </option>
              <option value="104" <?php echo ($record['heart_rate'] == '104') ? 'selected' : ''; ?>>104 </option>
              <option value="105" <?php echo ($record['heart_rate'] == '105') ? 'selected' : ''; ?>>105 </option>
              <option value="106" <?php echo ($record['heart_rate'] == '106') ? 'selected' : ''; ?>>106 </option>
              <option value="107" <?php echo ($record['heart_rate'] == '107') ? 'selected' : ''; ?>>107 </option>
              <option value="108" <?php echo ($record['heart_rate'] == '108') ? 'selected' : ''; ?>>108 </option>
              <option value="109" <?php echo ($record['heart_rate'] == '109') ? 'selected' : ''; ?>>109 </option>
              <option value="110" <?php echo ($record['heart_rate'] == '110') ? 'selected' : ''; ?>>110 </option>
              <option value="111" <?php echo ($record['heart_rate'] == '111') ? 'selected' : ''; ?>>111 </option>
              <option value="112" <?php echo ($record['heart_rate'] == '112') ? 'selected' : ''; ?>>112 </option>
              <option value="113" <?php echo ($record['heart_rate'] == '113') ? 'selected' : ''; ?>>113 </option>
              <option value="114" <?php echo ($record['heart_rate'] == '114') ? 'selected' : ''; ?>>114 </option>
              <option value="115" <?php echo ($record['heart_rate'] == '115') ? 'selected' : ''; ?>>115 </option>
              <option value="116" <?php echo ($record['heart_rate'] == '116') ? 'selected' : ''; ?>>116 </option>
              <option value="117" <?php echo ($record['heart_rate'] == '117') ? 'selected' : ''; ?>>117 </option>
              <option value="118" <?php echo ($record['heart_rate'] == '118') ? 'selected' : ''; ?>>118 </option>
              <option value="119" <?php echo ($record['heart_rate'] == '119') ? 'selected' : ''; ?>>119 </option>
              <option value="120" <?php echo ($record['heart_rate'] == '120') ? 'selected' : ''; ?>>120 </option>
              <option value="121" <?php echo ($record['heart_rate'] == '121') ? 'selected' : ''; ?>>121 </option>
              <option value="122" <?php echo ($record['heart_rate'] == '122') ? 'selected' : ''; ?>>122 </option>
              <option value="123" <?php echo ($record['heart_rate'] == '123') ? 'selected' : ''; ?>>123 </option>
              <option value="124" <?php echo ($record['heart_rate'] == '124') ? 'selected' : ''; ?>>124 </option>
              <option value="125" <?php echo ($record['heart_rate'] == '125') ? 'selected' : ''; ?>>125 </option>
              <option value="126" <?php echo ($record['heart_rate'] == '126') ? 'selected' : ''; ?>>126 </option>
              <option value="127" <?php echo ($record['heart_rate'] == '127') ? 'selected' : ''; ?>>127 </option>
              <option value="128" <?php echo ($record['heart_rate'] == '128') ? 'selected' : ''; ?>>128 </option>
              <option value="129" <?php echo ($record['heart_rate'] == '129') ? 'selected' : ''; ?>>129 </option>
              <option value="130" <?php echo ($record['heart_rate'] == '130') ? 'selected' : ''; ?>>130 </option>
              <option value="131" <?php echo ($record['heart_rate'] == '131') ? 'selected' : ''; ?>>131 </option>
              <option value="132" <?php echo ($record['heart_rate'] == '132') ? 'selected' : ''; ?>>132 </option>
              <option value="133" <?php echo ($record['heart_rate'] == '133') ? 'selected' : ''; ?>>133 </option>
              <option value="134" <?php echo ($record['heart_rate'] == '134') ? 'selected' : ''; ?>>134 </option>
              <option value="135" <?php echo ($record['heart_rate'] == '135') ? 'selected' : ''; ?>>135 </option>
              <option value="136" <?php echo ($record['heart_rate'] == '136') ? 'selected' : ''; ?>>136 </option>
              <option value="137" <?php echo ($record['heart_rate'] == '137') ? 'selected' : ''; ?>>137 </option>
              <option value="138" <?php echo ($record['heart_rate'] == '138') ? 'selected' : ''; ?>>138 </option>
              <option value="139" <?php echo ($record['heart_rate'] == '139') ? 'selected' : ''; ?>>139 </option>
              <option value="140" <?php echo ($record['heart_rate'] == '140') ? 'selected' : ''; ?>>140 </option>
              <option value="141" <?php echo ($record['heart_rate'] == '141') ? 'selected' : ''; ?>>141 </option>
              <option value="142" <?php echo ($record['heart_rate'] == '142') ? 'selected' : ''; ?>>142 </option>
              <option value="143" <?php echo ($record['heart_rate'] == '143') ? 'selected' : ''; ?>>143 </option>
              <option value="144" <?php echo ($record['heart_rate'] == '144') ? 'selected' : ''; ?>>144 </option>
              <option value="145" <?php echo ($record['heart_rate'] == '145') ? 'selected' : ''; ?>>145 </option>
              <option value="146" <?php echo ($record['heart_rate'] == '146') ? 'selected' : ''; ?>>146 </option>
              <option value="147" <?php echo ($record['heart_rate'] == '147') ? 'selected' : ''; ?>>147 </option>
              <option value="148" <?php echo ($record['heart_rate'] == '148') ? 'selected' : ''; ?>>148 </option>
              <option value="149" <?php echo ($record['heart_rate'] == '149') ? 'selected' : ''; ?>>149 </option>
              <option value="150" <?php echo ($record['heart_rate'] == '150') ? 'selected' : ''; ?>>150 </option>
              <option value="151" <?php echo ($record['heart_rate'] == '151') ? 'selected' : ''; ?>>151 </option>
              <option value="152" <?php echo ($record['heart_rate'] == '152') ? 'selected' : ''; ?>>152 </option>
              <option value="153" <?php echo ($record['heart_rate'] == '153') ? 'selected' : ''; ?>>153 </option>
              <option value="154" <?php echo ($record['heart_rate'] == '154') ? 'selected' : ''; ?>>154 </option>
              <option value="155" <?php echo ($record['heart_rate'] == '155') ? 'selected' : ''; ?>>155 </option>
              <option value="156" <?php echo ($record['heart_rate'] == '156') ? 'selected' : ''; ?>>156 </option>
              <option value="157" <?php echo ($record['heart_rate'] == '157') ? 'selected' : ''; ?>>157 </option>
              <option value="158" <?php echo ($record['heart_rate'] == '158') ? 'selected' : ''; ?>>158 </option>
              <option value="159" <?php echo ($record['heart_rate'] == '159') ? 'selected' : ''; ?>>159 </option>
              <option value="160" <?php echo ($record['heart_rate'] == '160') ? 'selected' : ''; ?>>160 </option>
              <option value="161" <?php echo ($record['heart_rate'] == '161') ? 'selected' : ''; ?>>161 </option>
              <option value="162" <?php echo ($record['heart_rate'] == '162') ? 'selected' : ''; ?>>162 </option>
              <option value="163" <?php echo ($record['heart_rate'] == '163') ? 'selected' : ''; ?>>163 </option>
              <option value="164" <?php echo ($record['heart_rate'] == '164') ? 'selected' : ''; ?>>164 </option>
              <option value="165" <?php echo ($record['heart_rate'] == '165') ? 'selected' : ''; ?>>165 </option>
              <option value="166" <?php echo ($record['heart_rate'] == '166') ? 'selected' : ''; ?>>166 </option>
              <option value="167" <?php echo ($record['heart_rate'] == '167') ? 'selected' : ''; ?>>167 </option>
              <option value="168" <?php echo ($record['heart_rate'] == '168') ? 'selected' : ''; ?>>168 </option>
              <option value="169" <?php echo ($record['heart_rate'] == '169') ? 'selected' : ''; ?>>169 </option>
              <option value="170" <?php echo ($record['heart_rate'] == '170') ? 'selected' : ''; ?>>170 </option>
              <option value="171" <?php echo ($record['heart_rate'] == '171') ? 'selected' : ''; ?>>171 </option>
              <option value="172" <?php echo ($record['heart_rate'] == '172') ? 'selected' : ''; ?>>172 </option>
              <option value="173" <?php echo ($record['heart_rate'] == '173') ? 'selected' : ''; ?>>173 </option>
              <option value="174" <?php echo ($record['heart_rate'] == '174') ? 'selected' : ''; ?>>174 </option>
              <option value="175" <?php echo ($record['heart_rate'] == '175') ? 'selected' : ''; ?>>175 </option>
              <option value="176" <?php echo ($record['heart_rate'] == '176') ? 'selected' : ''; ?>>176 </option>
              <option value="177" <?php echo ($record['heart_rate'] == '177') ? 'selected' : ''; ?>>177 </option>
              <option value="178" <?php echo ($record['heart_rate'] == '178') ? 'selected' : ''; ?>>178 </option>
              <option value="179" <?php echo ($record['heart_rate'] == '179') ? 'selected' : ''; ?>>179 </option>
              <option value="180" <?php echo ($record['heart_rate'] == '180') ? 'selected' : ''; ?>>180 </option>
              <option value="181" <?php echo ($record['heart_rate'] == '181') ? 'selected' : ''; ?>>181 </option>
              <option value="182" <?php echo ($record['heart_rate'] == '182') ? 'selected' : ''; ?>>182 </option>
              <option value="183" <?php echo ($record['heart_rate'] == '183') ? 'selected' : ''; ?>>183 </option>
              <option value="184" <?php echo ($record['heart_rate'] == '184') ? 'selected' : ''; ?>>184 </option>
              <option value="185" <?php echo ($record['heart_rate'] == '185') ? 'selected' : ''; ?>>185 </option>
              <option value="186" <?php echo ($record['heart_rate'] == '186') ? 'selected' : ''; ?>>186 </option>
              <option value="187" <?php echo ($record['heart_rate'] == '187') ? 'selected' : ''; ?>>187 </option>
              <option value="188" <?php echo ($record['heart_rate'] == '188') ? 'selected' : ''; ?>>188 </option>
              <option value="189" <?php echo ($record['heart_rate'] == '189') ? 'selected' : ''; ?>>189 </option>
              <option value="190" <?php echo ($record['heart_rate'] == '190') ? 'selected' : ''; ?>>190 </option>
              <option value="191" <?php echo ($record['heart_rate'] == '191') ? 'selected' : ''; ?>>191 </option>
              <option value="192" <?php echo ($record['heart_rate'] == '192') ? 'selected' : ''; ?>>192 </option>
              <option value="193" <?php echo ($record['heart_rate'] == '193') ? 'selected' : ''; ?>>193 </option>
              <option value="194" <?php echo ($record['heart_rate'] == '194') ? 'selected' : ''; ?>>194 </option>
              <option value="195" <?php echo ($record['heart_rate'] == '195') ? 'selected' : ''; ?>>195 </option>
              <option value="196" <?php echo ($record['heart_rate'] == '196') ? 'selected' : ''; ?>>196 </option>
              <option value="197" <?php echo ($record['heart_rate'] == '197') ? 'selected' : ''; ?>>197 </option>
              <option value="198" <?php echo ($record['heart_rate'] == '198') ? 'selected' : ''; ?>>198 </option>
              <option value="199" <?php echo ($record['heart_rate'] == '199') ? 'selected' : ''; ?>>199 </option>
              <option value="200" <?php echo ($record['heart_rate'] == '200') ? 'selected' : ''; ?>>200 </option>
            </select>
          </div>
          <div>
          <label for="bmiResult">Your BMI: </label>
          <input name="bmiResult" type="text" id="bmiResult" value="<?php echo htmlspecialchars($record['bmi'] ?? ''); ?>" readonly>
          </div>

          <div>
            <label for="height">Height</label>
            <select name="height" id="height" onchange="calculateBMI()" required>
              <option value="" disabled selected>Please select</option>
              <option value="100" <?php echo ($record['height'] == '100') ? 'selected' : ''; ?>>100 cm</option>
              <option value="101" <?php echo ($record['height'] == '101') ? 'selected' : ''; ?>>101 cm</option>
              <option value="102" <?php echo ($record['height'] == '102') ? 'selected' : ''; ?>>102 cm</option>
              <option value="103" <?php echo ($record['height'] == '103') ? 'selected' : ''; ?>>103 cm</option>
              <option value="104" <?php echo ($record['height'] == '104') ? 'selected' : ''; ?>>104 cm</option>
              <option value="105" <?php echo ($record['height'] == '105') ? 'selected' : ''; ?>>105 cm</option>
              <option value="106" <?php echo ($record['height'] == '106') ? 'selected' : ''; ?>>106 cm</option>
              <option value="107" <?php echo ($record['height'] == '107') ? 'selected' : ''; ?>>107 cm</option>
              <option value="108" <?php echo ($record['height'] == '108') ? 'selected' : ''; ?>>108 cm</option>
              <option value="109" <?php echo ($record['height'] == '109') ? 'selected' : ''; ?>>109 cm</option>
              <option value="110" <?php echo ($record['height'] == '110') ? 'selected' : ''; ?>>110 cm</option>
              <option value="111" <?php echo ($record['height'] == '111') ? 'selected' : ''; ?>>111 cm</option>
              <option value="112" <?php echo ($record['height'] == '112') ? 'selected' : ''; ?>>112 cm</option>
              <option value="113" <?php echo ($record['height'] == '113') ? 'selected' : ''; ?>>113 cm</option>
              <option value="114" <?php echo ($record['height'] == '114') ? 'selected' : ''; ?>>114 cm</option>
              <option value="115" <?php echo ($record['height'] == '115') ? 'selected' : ''; ?>>115 cm</option>
              <option value="116" <?php echo ($record['height'] == '116') ? 'selected' : ''; ?>>116 cm</option>
              <option value="117" <?php echo ($record['height'] == '117') ? 'selected' : ''; ?>>117 cm</option>
              <option value="118" <?php echo ($record['height'] == '118') ? 'selected' : ''; ?>>118 cm</option>
              <option value="119" <?php echo ($record['height'] == '119') ? 'selected' : ''; ?>>119 cm</option>
              <option value="120" <?php echo ($record['height'] == '120') ? 'selected' : ''; ?>>120 cm</option>
              <option value="121" <?php echo ($record['height'] == '121') ? 'selected' : ''; ?>>121 cm</option>
              <option value="122" <?php echo ($record['height'] == '122') ? 'selected' : ''; ?>>122 cm</option>
              <option value="123" <?php echo ($record['height'] == '123') ? 'selected' : ''; ?>>123 cm</option>
              <option value="124" <?php echo ($record['height'] == '124') ? 'selected' : ''; ?>>124 cm</option>
              <option value="125" <?php echo ($record['height'] == '125') ? 'selected' : ''; ?>>125 cm</option>
              <option value="126" <?php echo ($record['height'] == '126') ? 'selected' : ''; ?>>126 cm</option>
              <option value="127" <?php echo ($record['height'] == '127') ? 'selected' : ''; ?>>127 cm</option>
              <option value="128" <?php echo ($record['height'] == '128') ? 'selected' : ''; ?>>128 cm</option>
              <option value="129" <?php echo ($record['height'] == '129') ? 'selected' : ''; ?>>129 cm</option>
              <option value="130" <?php echo ($record['height'] == '130') ? 'selected' : ''; ?>>130 cm</option>
              <option value="131" <?php echo ($record['height'] == '131') ? 'selected' : ''; ?>>131 cm</option>
              <option value="132" <?php echo ($record['height'] == '132') ? 'selected' : ''; ?>>132 cm</option>
              <option value="133" <?php echo ($record['height'] == '133') ? 'selected' : ''; ?>>133 cm</option>
              <option value="134" <?php echo ($record['height'] == '134') ? 'selected' : ''; ?>>134 cm</option>
              <option value="135" <?php echo ($record['height'] == '135') ? 'selected' : ''; ?>>135 cm</option>
              <option value="136" <?php echo ($record['height'] == '136') ? 'selected' : ''; ?>>136 cm</option>
              <option value="137" <?php echo ($record['height'] == '137') ? 'selected' : ''; ?>>137 cm</option>
              <option value="138" <?php echo ($record['height'] == '138') ? 'selected' : ''; ?>>138 cm</option>
              <option value="139" <?php echo ($record['height'] == '139') ? 'selected' : ''; ?>>139 cm</option>
              <option value="140" <?php echo ($record['height'] == '140') ? 'selected' : ''; ?>>140 cm</option>
              <option value="141" <?php echo ($record['height'] == '141') ? 'selected' : ''; ?>>141 cm</option>
              <option value="142" <?php echo ($record['height'] == '142') ? 'selected' : ''; ?>>142 cm</option>
              <option value="143" <?php echo ($record['height'] == '143') ? 'selected' : ''; ?>>143 cm</option>
              <option value="144" <?php echo ($record['height'] == '144') ? 'selected' : ''; ?>>144 cm</option>
              <option value="145" <?php echo ($record['height'] == '145') ? 'selected' : ''; ?>>145 cm</option>
              <option value="146" <?php echo ($record['height'] == '146') ? 'selected' : ''; ?>>146 cm</option>
              <option value="147" <?php echo ($record['height'] == '147') ? 'selected' : ''; ?>>147 cm</option>
              <option value="148" <?php echo ($record['height'] == '148') ? 'selected' : ''; ?>>148 cm</option>
              <option value="149" <?php echo ($record['height'] == '149') ? 'selected' : ''; ?>>149 cm</option>
              <option value="150" <?php echo ($record['height'] == '150') ? 'selected' : ''; ?>>150 cm</option>
              <option value="151" <?php echo ($record['height'] == '151') ? 'selected' : ''; ?>>151 cm</option>
              <option value="152" <?php echo ($record['height'] == '152') ? 'selected' : ''; ?>>152 cm</option>
              <option value="153" <?php echo ($record['height'] == '153') ? 'selected' : ''; ?>>153 cm</option>
              <option value="154" <?php echo ($record['height'] == '154') ? 'selected' : ''; ?>>154 cm</option>
              <option value="155" <?php echo ($record['height'] == '155') ? 'selected' : ''; ?>>155 cm</option>
              <option value="156" <?php echo ($record['height'] == '156') ? 'selected' : ''; ?>>156 cm</option>
              <option value="157" <?php echo ($record['height'] == '157') ? 'selected' : ''; ?>>157 cm</option>
              <option value="158" <?php echo ($record['height'] == '158') ? 'selected' : ''; ?>>158 cm</option>
              <option value="159" <?php echo ($record['height'] == '159') ? 'selected' : ''; ?>>159 cm</option>
              <option value="160" <?php echo ($record['height'] == '160') ? 'selected' : ''; ?>>160 cm</option>
              <option value="161" <?php echo ($record['height'] == '161') ? 'selected' : ''; ?>>161 cm</option>
              <option value="162" <?php echo ($record['height'] == '162') ? 'selected' : ''; ?>>162 cm</option>
              <option value="163" <?php echo ($record['height'] == '163') ? 'selected' : ''; ?>>163 cm</option>
              <option value="164" <?php echo ($record['height'] == '164') ? 'selected' : ''; ?>>164 cm</option>
              <option value="165" <?php echo ($record['height'] == '165') ? 'selected' : ''; ?>>165 cm</option>
              <option value="166" <?php echo ($record['height'] == '166') ? 'selected' : ''; ?>>166 cm</option>
              <option value="167" <?php echo ($record['height'] == '167') ? 'selected' : ''; ?>>167 cm</option>
              <option value="168" <?php echo ($record['height'] == '168') ? 'selected' : ''; ?>>168 cm</option>
              <option value="169" <?php echo ($record['height'] == '169') ? 'selected' : ''; ?>>169 cm</option>
              <option value="170" <?php echo ($record['height'] == '170') ? 'selected' : ''; ?>>170 cm</option>
              <option value="171" <?php echo ($record['height'] == '171') ? 'selected' : ''; ?>>171 cm</option>
              <option value="172" <?php echo ($record['height'] == '172') ? 'selected' : ''; ?>>172 cm</option>
              <option value="173" <?php echo ($record['height'] == '173') ? 'selected' : ''; ?>>173 cm</option>
              <option value="174" <?php echo ($record['height'] == '174') ? 'selected' : ''; ?>>174 cm</option>
              <option value="175" <?php echo ($record['height'] == '175') ? 'selected' : ''; ?>>175 cm</option>
              <option value="176" <?php echo ($record['height'] == '176') ? 'selected' : ''; ?>>176 cm</option>
              <option value="177" <?php echo ($record['height'] == '177') ? 'selected' : ''; ?>>177 cm</option>
              <option value="178" <?php echo ($record['height'] == '178') ? 'selected' : ''; ?>>178 cm</option>
              <option value="179" <?php echo ($record['height'] == '179') ? 'selected' : ''; ?>>179 cm</option>
              <option value="180" <?php echo ($record['height'] == '180') ? 'selected' : ''; ?>>180 cm</option>
              <option value="181" <?php echo ($record['height'] == '181') ? 'selected' : ''; ?>>181 cm</option>
              <option value="182" <?php echo ($record['height'] == '182') ? 'selected' : ''; ?>>182 cm</option>
              <option value="183" <?php echo ($record['height'] == '183') ? 'selected' : ''; ?>>183 cm</option>
              <option value="184" <?php echo ($record['height'] == '184') ? 'selected' : ''; ?>>184 cm</option>
              <option value="185" <?php echo ($record['height'] == '185') ? 'selected' : ''; ?>>185 cm</option>
              <option value="186" <?php echo ($record['height'] == '186') ? 'selected' : ''; ?>>186 cm</option>
              <option value="187" <?php echo ($record['height'] == '187') ? 'selected' : ''; ?>>187 cm</option>
              <option value="188" <?php echo ($record['height'] == '188') ? 'selected' : ''; ?>>188 cm</option>
              <option value="189" <?php echo ($record['height'] == '189') ? 'selected' : ''; ?>>189 cm</option>
              <option value="190" <?php echo ($record['height'] == '190') ? 'selected' : ''; ?>>190 cm</option>
              <option value="191" <?php echo ($record['height'] == '191') ? 'selected' : ''; ?>>191 cm</option>
              <option value="192" <?php echo ($record['height'] == '192') ? 'selected' : ''; ?>>192 cm</option>
              <option value="193" <?php echo ($record['height'] == '193') ? 'selected' : ''; ?>>193 cm</option>
              <option value="194" <?php echo ($record['height'] == '194') ? 'selected' : ''; ?>>194 cm</option>
              <option value="195" <?php echo ($record['height'] == '195') ? 'selected' : ''; ?>>195 cm</option>
              <option value="196" <?php echo ($record['height'] == '196') ? 'selected' : ''; ?>>196 cm</option>
              <option value="197" <?php echo ($record['height'] == '197') ? 'selected' : ''; ?>>197 cm</option>
              <option value="198" <?php echo ($record['height'] == '198') ? 'selected' : ''; ?>>198 cm</option>
              <option value="199" <?php echo ($record['height'] == '199') ? 'selected' : ''; ?>>199 cm</option>
              <option value="200" <?php echo ($record['height'] == '200') ? 'selected' : ''; ?>>200 cm</option>
            </select>
            <span id="height-error" class="error-message"></span>
          </div>
       </div>
      </fieldset>

      <fieldset>
        <legend>
          <h3>Medical Test & Results</h3>
        </legend>
        <div id="test-fields">
          <div class="test-fields">
            <div class="account-details2">
              <div>
                <label for="test" style="padding: 0%;padding-bottom: 4%;">Medical Test</label>
                <select style="width: 100%;" name="test" id="test" required onchange="handleTestChange()">
                <option value="" disabled selected>Please select</option>
                <option value="No Test" <?php echo ($record['test'] == 'No Test') ? 'selected' : ''; ?>>No Test</option>
                <option value="blood-test" <?php echo ($record['test'] == 'blood-test') ? 'selected' : ''; ?>>Blood Test</option>
                <option value="urine-test" <?php echo ($record['test'] == 'urine-test') ? 'selected' : ''; ?>>Urine Test</option>
                <option value="x-ray" <?php echo ($record['test'] == 'x-ray') ? 'selected' : ''; ?>>X-ray</option>
                <option value="mri" <?php echo ($record['test'] == 'mri') ? 'selected' : ''; ?>>MRI</option>
                <option value="ct-scan" <?php echo ($record['test'] == 'ct-scan') ? 'selected' : ''; ?>>CT Scan</option>
                <option value="ecg" <?php echo ($record['test'] == 'ecg') ? 'selected' : ''; ?>>ECG</option>
                <option value="ultrasound" <?php echo ($record['test'] == 'ultrasound') ? 'selected' : ''; ?>>Ultrasound</option>
                <option value="blood-pressure" <?php echo ($record['test'] == 'blood-pressure') ? 'selected' : ''; ?>>Blood Pressure Test</option>
                <option value="glucose-test" <?php echo ($record['test'] == 'glucose-test') ? 'selected' : ''; ?>>Glucose Test</option>
                <option value="cholesterol-test" <?php echo ($record['test'] == 'cholesterol-test') ? 'selected' : ''; ?>>Cholesterol Test</option>
                <option value="liver-function-test" <?php echo ($record['test'] == 'liver-function-test') ? 'selected' : ''; ?>>Liver Function Test</option>
                <option value="kidney-function-test" <?php echo ($record['test'] == 'kidney-function-test') ? 'selected' : ''; ?>>Kidney Function Test</option>
                <option value="pregnancy-test" <?php echo ($record['test'] == 'pregnancy-test') ? 'selected' : ''; ?>>Pregnancy Test</option>
                <option value="mammogram" <?php echo ($record['test'] == 'mammogram') ? 'selected' : ''; ?>>Mammogram</option>
                <option value="pap-smear" <?php echo ($record['test'] == 'pap-smear') ? 'selected' : ''; ?>>Pap Smear</option>
                <option value="prostate-test" <?php echo ($record['test'] == 'prostate-test') ? 'selected' : ''; ?>>Prostate Test</option>
                <option value="hiv-test" <?php echo ($record['test'] == 'hiv-test') ? 'selected' : ''; ?>>HIV Test</option>
                <option value="allergy-test" <?php echo ($record['test'] == 'allergy-test') ? 'selected' : ''; ?>>Allergy Test</option>
                <option value="skin-biopsy" <?php echo ($record['test'] == 'skin-biopsy') ? 'selected' : ''; ?>>Skin Biopsy</option>
                <option value="bone-density-test" <?php echo ($record['test'] == 'bone-density-test') ? 'selected' : ''; ?>>Bone Density Test</option>
                </select>
              </div>
              <div style="text-align: right;">
                <label for="result" style="display: block; margin-bottom: 0%; width: 38px;margin-bottom: 2px;">Result</label>
                <textarea class="result" placeholder="" name="result" id="result" required style="width: 307px; height: 77px; resize: none;"><?php echo htmlspecialchars($record['result'] ?? ''); ?></textarea>
              </div>
              <div>
                <label for="dob2" style="padding: 0%;padding-bottom: 4%;">Date Of Birth</label>
                <input type="text" name="dob2" id="dob2" placeholder="DD/MM/YYYY" pattern="\d{2}/\d{2}/\d{4}" required value="<?php echo htmlspecialchars($record['testdate'] ?? ''); ?>">
              </div>
            </div>
          </div>
        </div>
      </fieldset>
      
      <script>
      function handleTestChange() {
        var testSelect = document.getElementById('test');
        var resultField = document.getElementById('result');
        var dobField = document.getElementById('dob2');
        
        if (testSelect.value === "No Test") {
          resultField.value = "-";
          dobField.value = "00/00/0000";
        } else {
          resultField.value = "";
          dobField.value = "";
        }
      }
      </script>

      <fieldset>
        <legend>
          <h3>Lifestyle & Habits</h3>
        </legend>
        <div class="account-details">
        <div>
            <label>What type of diet do you follow?</label>
            <select name="diet" id="diet" required>
              <option value="" disabled selected>Please select</option>
              <option value="Balanced" <?php echo ($record['diet'] == 'Balanced') ? 'selected' : ''; ?>>Balanced</option>
              <option value="High Sugar" <?php echo ($record['diet'] == 'High Sugar') ? 'selected' : ''; ?>>High Sugar</option>
              <option value="Unhealthy" <?php echo ($record['diet'] == 'Unhealthy') ? 'selected' : ''; ?>>Unhealthy</option>
            </select>         
          </div>
          <div>
            <label>How often do you consume alcohol? </label>
              <select name="alcohol" id="alcohol" required>
                <option value="" disabled selected>Please select</option>
                <option value="Weekly" <?php echo ($record['alcohol_use'] == 'Weekly') ? 'selected' : ''; ?>>Weekly</option>
                <option value="Occasionally" <?php echo ($record['alcohol_use'] == 'Occasionally') ? 'selected' : ''; ?>>Occasionally</option>
                <option value="Never consumed alcohol" <?php echo ($record['alcohol_use'] == 'Never consumed alcohol') ? 'selected' : ''; ?>>Never consumed alcohol</option>
              </select>
          </div>
          <div>
            <label>How often do you engage in physical activity?</label>
            <select name="exercise" id="exercise" required>
              <option value="" disabled selected>Please select</option>
              <option value="Daily" <?php echo ($record['exercise'] == 'Daily') ? 'selected' : ''; ?>>Daily</option>
              <option value="Weekly" <?php echo ($record['exercise'] == 'Weekly') ? 'selected' : ''; ?>>Weekly</option>
              <option value="Occasionally" <?php echo ($record['exercise'] == 'Occasionally') ? 'selected' : ''; ?>>Occasionally</option>
              <option value="Never" <?php echo ($record['exercise'] == 'Never') ? 'selected' : ''; ?>>Never</option>
            </select>
          </div>
          <div>
            <label>How many hours of sleep do you get on average each night?</label>
            <select name="sleep" id="sleep" required>
              <option value="" disabled selected>Please select</option>
              <option value="5" <?php echo ($record['sleep'] == '5') ? 'selected' : ''; ?>>5</option>
              <option value="6" <?php echo ($record['sleep'] == '6') ? 'selected' : ''; ?>>6</option>
              <option value="7" <?php echo ($record['sleep'] == '7') ? 'selected' : ''; ?>>7</option>
              <option value="8" <?php echo ($record['sleep'] == '8') ? 'selected' : ''; ?>>8</option>
              <option value="9" <?php echo ($record['sleep'] == '9') ? 'selected' : ''; ?>>9</option>
              <option value="10" <?php echo ($record['sleep'] == '10') ? 'selected' : ''; ?>>10</option>
              <option value="11" <?php echo ($record['sleep'] == '11') ? 'selected' : ''; ?>>11</option>
              <option value="12" <?php echo ($record['sleep'] == '12') ? 'selected' : ''; ?>>12</option>
            </select>
          </div>
          <div>
            <label>Do you use tobacco? If yes, what is the frequency?</label>
            <select id="tobaccoUse" name="tobaccoUse" required>
              <option value="" disabled selected>Please select</option>
              <option value="Daily" <?php echo ($record['tobacco_use'] == 'Daily') ? 'selected' : ''; ?>>Daily</option>
              <option value="Occasionally" <?php echo ($record['tobacco_use'] == 'Occasionally') ? 'selected' : ''; ?>>Occasionally</option>
              <option value="Never Smoked" <?php echo ($record['tobacco_use'] == 'Never Smoked') ? 'selected' : ''; ?>>Never Smoked</option>
            </select>
      </fieldset>

      <fieldset>
        <legend>
          <h3>Physicians and Healthcare Providers</h3>
        </legend>
        <div id="facility-fields">
          <div class="facility-fields">
          <div class="account-details2">
            <div>
              <label style="padding: 0%;padding-bottom: 4%;">Name</label>
              <input type="text" placeholder="" name="provider-name" id="provider-name" required value="<?php echo htmlspecialchars($record['providername'] ?? ''); ?>">
            </div>
            <div>
              <label style="padding: 0%;padding-bottom: 4%;">Contact Info</label>
              <input type="text" placeholder="" name="provider-tel" id="provider-tel" required value="<?php echo htmlspecialchars($record['providertel'] ?? ''); ?>">
            </div>
            <div>
              <label style="padding: 0%;padding-bottom: 4%;">Facility Name</label>
              <input type="text" placeholder="" name="facility-name" id="facility-name" required value="<?php echo htmlspecialchars($record['facilityname'] ?? ''); ?>">
            </div>
            <div style="text-align: left;">
              <label for="facility-address" style="display: block; margin-bottom: 0%; width: 99px;margin-bottom: 2px;">Facility Address</label>
              <textarea class="facility-address" placeholder="facility-address" name="facility-address" id="facility-address" required="" style="width: 307px; height: 77px; resize: none;"><?php echo htmlspecialchars($record['facilityaddress'] ?? ''); ?></textarea>
            </div>
            </div>
          </div>
      </fieldset>

      <fieldset>
        <legend>
          <h3>Name your record title</h3>
        </legend>
          <div class="account-details2">
            <div>
              <label for="title" style="padding: 0%;padding-bottom: 4%;">Record Title</label>
              <input type="text" placeholder="" name="title" id="title" required value="<?php echo htmlspecialchars($record['recordtitle'] ?? ''); ?>">
          </div>
       </div>
      </fieldset>

      



        <button type="submit" href="/">Submit</button>
    </form>
</div> 


<script>
        // Function to calculate BMI
    function calculateBMI() {
      var weight = document.getElementById('weight').value;
      var height = document.getElementById('height').value;

      if (weight && height) {
          // Convert height from cm to meters
          height = height / 100;
          
          // Calculate BMI
          var bmi = weight / (height * height);
          
          // Display the BMI result in the text input
          document.getElementById('bmiResult').value = bmi.toFixed(2); // Rounded to 2 decimal places
      } else {
          document.getElementById('bmiResult').value = '0';
      }
    }





</script>
</body>
</html>



