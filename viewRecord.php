<?php
session_start();
include("db.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Check if 'username' is set in session
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    // Redirect to login page if 'username' is not set in session
    header("Location: login.php");
    exit;
}

// Fetch additional user data from the database
$query = "SELECT * FROM users WHERE username = ? LIMIT 1";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, 's', $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && mysqli_num_rows($result) > 0) {
    $user_data = mysqli_fetch_assoc($result);
    $email = $user_data['email'];
    $created_at = $user_data['created_at']; // Example field: account creation date
} else {
    echo "<script type='text/javascript'> alert('User not found')</script>";
    header("Location: login.php");
    exit;
}

// Check if the ID is provided in the URL
if (isset($_GET['id'])) {
    $record_id = $_GET['id'];

    // Prepare SQL query to fetch the health record
    $query = "SELECT * FROM health_data 
    WHERE health_data.id = ? AND health_data.user_id = ?";


    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'ii', $record_id, $_SESSION['user_id']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $record = mysqli_fetch_assoc($result);
    } else {
        echo "<script>alert('Record not found or you do not have permission to view this record.');</script>";
        header("Location: dashboard.php");
        exit;
    }



    // Close the statement
    mysqli_stmt_close($stmt);
} else {
    echo "<script>alert('No record ID specified.');</script>";
    header("Location: dashboard.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Basic -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Mobile Metas -->
  
  <!-- Site Metas -->
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <meta name="author" content="" />

  <title>Health Data Overview</title>

  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


<!-- fonts style -->
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">

  <!-- fonts style -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">

  <!--owl slider stylesheet -->
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />

  <!-- font awesome style -->
  <link href="css/font-awesome.min.css" rel="stylesheet" />
  <!-- nice select -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css" integrity="sha256-mLBIhmBvigTFWPSCtvdu6a76T+3Xyt+K571hupeFLg4=" crossorigin="anonymous" />
  <!-- datepicker -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css">
  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet" />
  <!-- responsive style -->
  <link href="css/responsive.css" rel="stylesheet" />
  <link href="css/dashboard.css" rel="stylesheet" />

    <style>
        .table {
            margin-bottom: 50px;
        }
        
    </style>
    
</head>

<body>
<div class="hero_area">
    <!-- header section strats -->
    <header class="header_section">
      <div class="header_bottom">
        <div class="container-fluid">
          <nav class="navbar navbar-expand-lg custom_nav-container ">
            <a class="navbar-brand" href="home.php">
              <img src="images/logo.png" alt="">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class=""> </span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <div class="d-flex mr-auto flex-column flex-lg-row align-items-center">
                <ul class="navbar-nav  ">
                  <li class="nav-item active">
                    <a class="nav-link" href="home.php #home">Home <span class="sr-only">(current)</span></a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="home.php #about"> About</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="home.php #features">Features</a>
                  </li>
                </ul>
              </div>
              <div class="quote_btn-container">
                <!-- Dashboard Button -->
                <a href="dashboard.php" class="dashboard-btn">
                  Dashboard
                </a>
              </div>
              <div class="dropdown">
                <!-- Use PHP to insert the username dynamically into the button -->
                <a href="#" class="button-75"><?php echo htmlspecialchars($username); ?></a>
                <div class="dropdown-content">
                  <a href="profile_edit.php" class="submenu-item">EDIT PROFILE</a>
                  <a href="appoinment_dashboard.php" class="submenu-item">APPOINMENT</a>
                  <a href="logout.php?logout=true" class="submenu-item" id="logoutItem">LOGOUT</a>
                </div>
              </div>

              <!-- Logout Confirmation Popup -->
              <div class="cd-popup" id="popup" role="alert">
                <div class="cd-popup-container">
                  <p>Are you sure you want to log out?</p>
                  <ul class="cd-buttons">
                    <li><a href="logout.php?redirect=home.html"id="confirmLogout">Yes</a></li>
                    <li><a href="#0" id="cancelLogout">No</a></li>
                  </ul>
                </div> <!-- cd-popup-container -->
              </div>

              <style>
                /* Styling for Button 75 */
                .button-75 {
                  background-color: #e0f7fa; /* Light cyan background */
                  border: 2px solid #00796b; /* Dark cyan border */
                  border-radius: 25px; /* Rounded corners */
                  box-shadow: #00796b 4px 4px 0 0; /* Box shadow with dark cyan color */
                  color: #00796b; /* Dark cyan text color */
                  cursor: pointer; /* Pointer cursor on hover */
                  display: inline-block;
                  font-weight: 700; /* Bold text */
                  font-size: 16px; /* Smaller font size */
                  padding: 0 20px; /* Horizontal padding */
                  line-height: 40px; /* Vertical padding */
                  text-align: center; /* Center align the text */
                  text-decoration: none;
                  user-select: none; /* Disable text selection */
                  -webkit-user-select: none; /* Disable text selection for WebKit browsers */
                  touch-action: manipulation; /* Prevents default touch action */
                  width: 140px; /* Fixed width */
                }

                .button-75:hover {
                  background-color: #ffffff; /* Change to white when hovered */
                  color: black; /* Keep dark cyan text color */

                }

                .button-75:active {
                  box-shadow: #00796b 2px 2px 0 0; /* Box shadow on active state */
                  transform: translate(2px, 2px); /* Slight movement when clicked */
                  color: black;
                }

                /* Styling for Dropdown Content */
                .dropdown-content {
                  display: none; /* Hidden by default */
                  position: absolute;
                  top: 48px; /* Adjust to position below the button */
                  left: 0; /* Align to the left edge */
                  background-color: #ffffff; /* Match button background */
                  border: 2px solid #00796b; /* Match button border */
                  border-radius: 25px; /* Match button shape */
                  box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); /* Subtle shadow */
                  padding: 5px 0; /* Padding for spacing */
                  z-index: 10; /* Ensure visibility */
                  width: 140px; /* Same width as the button */
                }

                .dropdown:hover .,
                .dropdown-content:hover {
                  display: none; /* Keep menu visible on hover */
                }

                /* Styling for Submenu Items */
                .submenu-item {
                  display: block; /* Stack menu items */
                  color: #00796b; /* Dark cyan text color */
                  text-decoration: none; /* Remove underline */
                  padding: 10px 0; /* Spacing inside items */
                  border-radius: 25px; /* Match the button shape */
                  font-size: 14px; /* Adjust font size */
                  font-weight: 600; /* Semi-bold text */
                  text-align: center; /* Center align text */
                }

                .submenu-item:hover {
                  background-color: #e0f7fa; /* Light cyan background on hover */
                  color: #00796b; /* Keep dark cyan text color */
                }

                /* Styling for Popup */
                .cd-popup {
                  display: none; /* Hidden by default */
                  position: fixed;
                  top: 50%; /* Center vertically */
                  left: 50%; /* Center horizontally */
                  transform: translate(-50%, -50%); /* Adjust for exact center */
                  background-color: #ffffff;
                  border: 2px solid #00796b;
                  border-radius: 25px;
                  box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
                  z-index: 9999; /* Ensure it overlays content */
                  width: 300px; /* Fixed width for the popup */
                  padding: 20px;
                }

                .cd-popup-container {
                  padding: 10px;
                  text-align: center;
                }

                .cd-buttons {
                  list-style-type: none;
                  padding: 0;
                  margin: 20px 0;
                }

                .cd-buttons li {
                  display: inline-block;
                  margin: 0 10px;
                }

                .cd-buttons a {
                  background-color: #00796b;
                  color: #ffffff;
                  padding: 10px 20px;
                  text-decoration: none;
                  border-radius: 25px;
                  font-weight: 600;
                }

                .cd-buttons a:hover {
                  background-color: #004d40;
                }
              </style>

              <script>
                // Toggle the visibility of the dropdown when the button is clicked
                document.querySelector('.button-75').addEventListener('click', function(e) {
                  e.preventDefault(); // Prevent default link behavior
                  var dropdownContent = document.querySelector('.dropdown-content');
                  
                  // Toggle visibility of the dropdown
                  if (dropdownContent.style.display === 'block') {
                    dropdownContent.style.display = 'none'; // Hide dropdown
                  } else {
                    dropdownContent.style.display = 'block'; // Show dropdown
                  }
                });

                // Close the dropdown if clicked outside of the button or dropdown
                document.addEventListener('click', function(e) {
                  var dropdownContent = document.querySelector('.dropdown-content');
                  var button = document.querySelector('.button-75');
                  
                  // If the click is outside the dropdown and button, hide the dropdown
                  if (!button.contains(e.target) && !dropdownContent.contains(e.target)) {
                    dropdownContent.style.display = 'none'; // Hide dropdown
                  }
                });

                // Show the popup when LOGOUT is clicked
                document.getElementById('logoutItem').addEventListener('click', function(e) {
                  e.preventDefault(); // Prevent default link behavior
                  document.getElementById('popup').style.display = 'block'; // Show the popup
                });

                // Close the popup when "No" is clicked
                document.getElementById('cancelLogout').addEventListener('click', function() {
                  document.getElementById('popup').style.display = 'none'; // Hide the popup
                });

                // Confirm logout action and redirect to home page
                document.getElementById('confirmLogout').addEventListener('click', function() {
                  window.location.href = 'home.html'; // Redirect to home page or logout action
                });

              </script>

            </div>
          </nav>
        </div>
      </div>
    </header>

    <div class="container-fluid mt-4 ms-2">

    <h2 style="padding-bottom: 0px; padding-left: 74px;"><?php echo htmlspecialchars($record['recordtitle']); ?></h2>
    <hr class="line"></hr>
        <style>
            .line {
                width: 90%;
                height: 1px;
                background-color: #00C6A9;
                border: none;
                top:12px;
                
            }


        </style>


<!-- Table one -->
 
<div style="width: 40%; float: left; margin-left: 80px; margin-bottom: -40px;">
    <h3 style="width: 100%; padding-bottom: 10px;font-size: 24px;">Personal Information</h3>
    <table class="table1 table table-bordered"style="width: 80%; font-size: 14px;">
    <thead>
        <tr>
            <th style="width: 33%; background-color: #00C6A970; padding: 4px 8px; border: 1px solid rgba(0, 198, 169, 0);">Fields</th>
            <th style="width: 60%; background-color: #00C6A970; padding: 4px 8px; border: 1px solid rgba(0, 198, 169, 0);">Details</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th style="padding: 4px 8px; border: 1px solid rgba(0, 198, 169, 0);">Full Name</th>
            <td style="padding: 4px 8px; border: 1px solid rgba(0, 198, 169, 0);"><?php echo htmlspecialchars($record['fullname']); ?></td>
        </tr>
        <tr>
            <th style="padding: 4px 8px; border: 1px solid rgba(0, 198, 169, 0);">Date of Birth</th>
            <td style="padding: 4px 8px; border: 1px solid rgba(0, 198, 169, 0);"><?php echo htmlspecialchars($record['dob']); ?></td>
        </tr>
        <tr>
            <th style="padding: 4px 8px; border: 1px solid rgba(0, 198, 169, 0);">Gender</th>
            <td style="padding: 4px 8px; border: 1px solid rgba(0, 198, 169, 0);"><?php echo htmlspecialchars($record['gender']); ?></td>
        </tr>
        <tr>
            <th style="padding: 4px 8px; border: 1px solid rgba(0, 198, 169, 0);">Blood Type</th>
            <td style="padding: 4px 8px; border: 1px solid rgba(0, 198, 169, 0);"><?php echo htmlspecialchars($record['bloodtype']); ?></td>
        </tr>
        <tr>
            <th style="padding: 4px 8px; border: 1px solid rgba(0, 198, 169, 0);">Status</th>
            <td style="padding: 4px 8px; border: 1px solid rgba(0, 198, 169, 0);"><?php echo htmlspecialchars($record['status']); ?></td>
        </tr>
        <tr>
            <th style="padding: 4px 8px; border: 1px solid rgba(0, 198, 169, 0);">Phone Number</th>
            <td style="padding: 4px 8px; border: 1px solid rgba(0, 198, 169, 0);"><?php echo htmlspecialchars($record['tel']); ?></td>
        </tr>
        <tr>
            <th style="padding: 4px 8px; border: 1px solid rgba(0, 198, 169, 0);">Emergency Contact</th>
            <td style="padding: 4px 8px; border: 1px solid rgba(0, 198, 169, 0);">
                <p>Name: <?php echo htmlspecialchars($record['emergencyname']); ?></p> 
                <p>Phone Number: <?php echo htmlspecialchars($record['emergencytel']); ?></p>
            </td>
        </tr>
    </tbody>
</table>
</div>

<!-- Table two -->
<div style="width: 50%; float: left; margin-right: 10px;">
    <h3 style="width: 100%; padding-bottom: 10px; font-size: 24px;">Address Information</h3>
    <table class="table2 table table-bordered"style="width: 80%; font-size: 14px;">
    <thead>
        <tr>
            <th style="width: 20%; background-color: #00C6A970; padding: 4px 8px; border: 1px solid rgba(0, 198, 169, 0);">Fields</th>
            <th style="width: 60%; background-color: #00C6A970; padding: 4px 8px; border: 1px solid rgba(0, 198, 169, 0);">Details</th>
        </tr>
    </thead>
    <tr>
        <th style="padding: 4px 8px; border: 1px solid rgba(0, 198, 169, 0);">Street Address</th>
        <td style="padding: 4px 8px; border: 1px solid rgba(0, 198, 169, 0);"><?php echo htmlspecialchars($record['streetaddress']); ?></td>
    </tr>
    <tr>
        <th style="padding: 4px 8px; border: 1px solid rgba(0, 198, 169, 0);">City</th>
        <td style="padding: 4px 8px; border: 1px solid rgba(0, 198, 169, 0);"><?php echo htmlspecialchars($record['city']); ?></td>
    </tr>
    <tr>
        <th style="padding: 4px 8px; border: 1px solid rgba(0, 198, 169, 0);">State</th>
        <td style="padding: 4px 8px; border: 1px solid rgba(0, 198, 169, 0);"><?php echo htmlspecialchars($record['state']); ?></td>
    </tr>
    <tr>
        <th style="padding: 4px 8px; border: 1px solid rgba(0, 198, 169, 0);">Postal Code</th>
        <td style="padding: 4px 8px; border: 1px solid rgba(0, 198, 169, 0);"><?php echo htmlspecialchars($record['postalcode']); ?></td>
    </tr>
</table>
        </div>

<!-- Clearfix for the first set of tables -->
<div class="clearfix"></div>

<!-- Table three -->
<div style="width: 30%; float: left; margin-left: 80px; margin-bottom: -24px;" >
    <h3 style="width: 100%; padding-bottom: 10px; font-size: 24px;">Medical History</h3>
    <table class="table3 table table-bordered"style="width: 80%; font-size: 14px;">
    <thead>
        <tr>
            <th style="width: 40%; background-color: #00C6A970; padding: 4px 8px; border: 1px solid rgba(0, 198, 169, 0);">Fields</th>
            <th style="width: 60%; background-color: #00C6A970; padding: 4px 8px; border: 1px solid rgba(0, 198, 169, 0);">Details</th>
        </tr>
    </thead>
    <tr>
        <th>Chronic Condition</th>
        <td><?php echo htmlspecialchars($record['chroniccondition']); ?></td>
    </tr>
    <tr>
        <th>Surgery</th>
        <td><?php echo htmlspecialchars($record['surgeries']); ?></td>
    </tr>
</table>
</div>

<!-- Table four -->
<div style="width: 30%; float: left; margin-right: 10px;">
    <h3 style="width: 100%; padding-bottom: 10px;font-size: 24px;">Immunizations</h3>
    <table class="table4 table table-bordered"style="width: 80%; font-size: 14px;">
    <thead>
        <tr>
            <th style="width: 40%; background-color: #00C6A970; padding: 4px 8px; border: 1px solid rgba(0, 198, 169, 0);">Vaccine </th>
            <th style="width: 60%; background-color: #00C6A970; padding: 4px 8px; border: 1px solid rgba(0, 198, 169, 0);">Date Administered</th>
        </tr>
    </thead>
    <tbody>
    <td><?php echo htmlspecialchars($record['vaccine']); ?></td>
    <td><?php echo htmlspecialchars($record['dateadministered']); ?></td>
    </tbody>
</table>
    </div>

<!-- Table five -->
<div style="width: 30%; float: left; margin-right: 10px;">
    <h3 style="width: 100%; padding-bottom: 10px;font-size: 24px;">Medications</h3>
    <table class="table5 table table-bordered"style="width: 80%; font-size: 14px;">
    <thead>
        <tr>
            <th style="width: 30%; background-color: #00C6A970; padding: 4px 8px; border: 1px solid rgba(0, 198, 169, 0);">Medication</th>
            <th style="width: 40%; background-color: #00C6A970; padding: 4px 8px; border: 1px solid rgba(0, 198, 169, 0);">Dosage</th>
            <th style="width: 60%; background-color: #00C6A970; padding: 4px 8px; border: 1px solid rgba(0, 198, 169, 0);">Frequency</th>
        </tr>
    </thead>
    <tbody>
    <tbody>
    <td><?php echo htmlspecialchars($record['medication']); ?></td>
    <td><?php echo htmlspecialchars($record['dosage']); ?></td>
    <td><?php echo htmlspecialchars($record['frequency']); ?></td>
    </tbody>
    </tbody>
</table>
    </div>

<!-- Clearfix for the second set of tables -->
<div class="clearfix"></div>

<!-- Table six -->
<div style="width: 30%; float: left; margin-left: 80px; margin-bottom: -24px;">
    <h3 style="width: 100%; padding-bottom: 10px;font-size: 24px;">Health Metrics</h3>
    <table class="table6 table table-bordered" style="width: 80%; font-size: 14px;">
    <thead>
        <tr>
            <th style="width: 40%; background-color: #00C6A970; padding: 4px 8px; border: 1px solid rgba(0, 198, 169, 0);">Fields </th>
            <th style="width: 60%; background-color: #00C6A970; padding: 4px 8px; border: 1px solid rgba(0, 198, 169, 0);">Details</th>
        </tr>
    </thead>
    <tr>
        <th>Blood Pressure</th>
        <td><?php echo htmlspecialchars($record['blood_pressure']); ?></td>
    </tr>
    <tr>
        <th>Heart Rate</th>
        <td><?php echo htmlspecialchars($record['heart_rate']); ?></td>
    </tr>
    <tr>
        <th>Height</th>
        <td><?php echo htmlspecialchars($record['height']); ?></td>
    </tr>
    <tr>
        <th>Weight</th>
        <td><?php echo htmlspecialchars($record['weight']); ?></td>
    </tr>
    <tr>
        <th>BMI</th>
        <td><?php echo htmlspecialchars($record['bmi']); ?></td>
    </tr>
</table>
    </div>

<!-- Table seven -->
<div style="width: 30%; float: left; margin-right: 10px;">
    <h3 style="width: 100%; padding-bottom: 10px; font-size: 24px;">Lab Results and Test Results</h3>
    <table class="table7 table table-bordered" style="width: 80%; font-size: 14px;">
    <thead>
        <tr>
            <th style="width: 40%; background-color: #00C6A970; padding: 4px 8px; border: 1px solid rgba(0, 198, 169, 0);">Test </th>
            <th style="width: 30%; background-color: #00C6A970; padding: 4px 8px; border: 1px solid rgba(0, 198, 169, 0);">Result</th>
            <th style="width: 60%; background-color: #00C6A970; padding: 4px 8px; border: 1px solid rgba(0, 198, 169, 0);">Date</th>
        </tr>
    </thead>
    <tbody>
    <td><?php echo htmlspecialchars($record['test']); ?></td>
    <td><?php echo htmlspecialchars($record['result']); ?></td>
    <td><?php echo htmlspecialchars($record['testdate']); ?></td>
    </tbody>
</table>
    </div>

<!-- Table eight -->
<div style="width: 30%; float: left; margin-right: 10px;">
    <h3 style="width: 100%; padding-bottom: 10px; font-size: 24px;">Lifestyles and Habits</h3>
    <table class="table8 table table-bordered" style="width: 80%; font-size: 14px;">
    <thead>
        <tr>
            <th style="width: 40%; background-color: #00C6A970; padding: 4px 8px; border: 1px solid rgba(0, 198, 169, 0);">Fields</th>
            <th style="width: 60%; background-color: #00C6A970; padding: 4px 8px; border: 1px solid rgba(0, 198, 169, 0);">Details</th>
        </tr>
    </thead>
        
    <tr>
        <th>Diet</th>
        <td><?php echo htmlspecialchars($record['diet']); ?></td>
    </tr>
    <tr>
        <th>Exercise</th>
        <td><?php echo htmlspecialchars($record['exercise']); ?></td>
    </tr>
    <tr>
        <th>Tobacco Use</th>
        <td><?php echo htmlspecialchars($record['tobacco_use']); ?></td>
    </tr>
    <tr>
        <th>Alcohol Consumption</th>
        <td><?php echo htmlspecialchars($record['alcohol_use']); ?></td>
    </tr>
    <tr>
        <th>Sleep Pattern</th>
        <td><?php echo htmlspecialchars($record['sleep']); ?></td>
    </tr>
</table>
    </div>

<!-- Clearfix for the third set of tables -->
<div class="clearfix"></div>

<!-- Table nine -->
<div style="margin-left: 80px;">
<table class="table9 table table-bordered" style="width: 80%; font-size: 14px;">
    <h3 style="font-size: 24px;">Physicians and Healthcare Providers</h3>
    <thead>
        <tr>
            <th style="width: 10%; background-color: #00C6A970; padding: 4px 8px; border: 1px solid rgba(0, 198, 169, 0);">Name</th>
            <th style="width: 10%; background-color: #00C6A970; padding: 4px 8px; border: 1px solid rgba(0, 198, 169, 0);">Contact Info</th>
            <th style="width: 20%; background-color: #00C6A970; padding: 4px 8px; border: 1px solid rgba(0, 198, 169, 0);">Facility Name</th>
            <th style="width: 40%; background-color: #00C6A970; padding: 4px 8px; border: 1px solid rgba(0, 198, 169, 0);">Facility Address</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?php echo htmlspecialchars($record['providername']); ?></td>
            <td><?php echo htmlspecialchars($record['providertel']); ?></td>
            <td><?php echo htmlspecialchars($record['facilityname']); ?></td>
            <td><?php echo htmlspecialchars($record['facilityaddress']); ?></td>
        </tr>
    </tbody>
</table>
          </div>



</style>



</table>

<!-- Action Buttons (Edit & Delete) -->
<div style="padding-left: 1070px; margin-top: 10px;">
    <a href='editRecord.php?id=<?php echo htmlspecialchars($record["id"]); ?>' 
       class='btn btn-warning btn-sm' 
       style="margin-right: 10px; padding: 0.4rem 0.75rem; font-size: 15px;">
        <i class='fas fa-edit'></i> Edit
    </a>
    <a href='#' 
       class='btn btn-danger btn-sm deleteBtn' 
       data-id='<?php echo htmlspecialchars($record["id"]); ?>' 
       style="margin-right: 10px; padding: 0.4rem 0.75rem; font-size: 15px;">
        <i class='fas fa-trash-alt'></i> Delete
    </a>
    <a href='dashboard.php' 
       class='btn btn-secondary btn-sm' 
       style="padding: 0.4rem 0.75rem; font-size: 15px;">
        <i class='fas fa-arrow-left'></i> Back to Dashboard
    </a>
</div>



                <!-- Delete Confirmation Popup -->
                <div class="cd-popup" id="deletePopup" role="alert" style="display: none;">
                  <div class="cd-popup-container">
                    <p>Are you sure you want to delete the selected record?</p>
                    <ul class="cd-buttons">
                      <li><a href="#" id="confirmDelete">Yes</a></li>
                      <li><a href="#0" id="cancelDelete">No</a></li>
                    </ul>
                  </div>
                </div>

                <!-- JavaScript for the Popup and Deletion Handling -->
                <script>
                document.querySelectorAll('.deleteBtn').forEach(function(button) {
                    button.addEventListener('click', function(e) {
                        e.preventDefault(); // Prevent immediate navigation
                        const recordId = this.getAttribute('data-id'); // Get the record ID from data attribute
                        const confirmLink = document.getElementById('confirmDelete');
                        confirmLink.href = 'deleteRecord.php?id=' + recordId; // Set the correct delete URL
                        document.getElementById('deletePopup').style.display = 'block'; // Show the popup
                    });
                });

                document.getElementById('cancelDelete').addEventListener('click', function() {
                    document.getElementById('deletePopup').style.display = 'none'; // Hide the popup
                });
                </script>
  
  <?php if (!empty($record['diabetes_risk']) || !empty($record['heart_disease_risk']) || !empty($record['liver_risk']) || !empty($record['tobacco_risk']) || !empty($record['obesity_risk'])): ?>
    <div style="padding-left: 74px; padding-right: 12px;margin-top: 30px;"> 
    <h2 style="margin-bottom: 5px;">Your Personalized Health Overview</h2>
    <p style="color: grey; font-size: 14px;">Personalized Guidance for Improving Your Health and Preventing Risks</p> </div>
    <hr class="line"></hr>


<!-- Separate div for health risks -->
<div style="padding-left: 74px; margin-top: 30px;"> 
    <h2 style="margin-bottom: 5px; font-size: 24px;">Health Risks:</h2>
    <div style="margin-top: 10px; padding: 10px; background-color: rgb(248, 250, 249); border: 2px solid #FF5733; border-radius: 5px; font-size: 16px; text-align: left; width: 95%; margin-right: 10px;">
    
    <?php if (!empty($record['diabetes_risk'])): ?>
      <p><i class="fas fa-heartbeat" style="color: #FF5733; margin-right: 6px;"></i><?php echo htmlspecialchars($record['diabetes_risk']); ?></p>
    <?php endif; ?>
    
    <?php if (!empty($record['heart_disease_risk'])): ?>
      <p><i class="fas fa-heartbeat" style="color: #FF5733; margin-right: 6px;"></i><?php echo htmlspecialchars($record['heart_disease_risk']); ?></p>
    <?php endif; ?>

    <?php if (!empty($record['liver_risk'])): ?>
      <p><i class="fas fa-heartbeat" style="color: #FF5733; margin-right: 6px;"></i><?php echo htmlspecialchars($record['liver_risk']); ?></p>
    <?php endif; ?>

    <?php if (!empty($record['tobacco_risk'])): ?>
      <p><i class="fas fa-heartbeat" style="color: #FF5733; margin-right: 6px;"></i><?php echo htmlspecialchars($record['tobacco_risk']); ?></p>
    <?php endif; ?>

    <?php if (!empty($record['obesity_risk'])): ?>
      <p><i class="fas fa-heartbeat" style="color: #FF5733; margin-right: 6px;"></i><?php echo htmlspecialchars($record['obesity_risk']); ?></p>
    <?php endif; ?>

    </div>
</div>
<div style="padding-left: 74px; margin-top: 30px;"> 

    <h2 style="margin-top: 35px; font-size: 24px;">Health Tips:</h2>
    <div style="margin-top: 10px; padding: 10px; background-color: rgb(248, 250, 249); border: 2px solid #4CAF50; border-radius: 5px; font-size: 16px; text-align: left; width: 95%; margin-right: 10px;">
    
    <?php if (!empty($record['diabetes_tips'])): ?>
    <p><i class="fas fa-heartbeat" style="color: #00C6A9; margin-right: 6px;"></i><?php echo htmlspecialchars($record['diabetes_tips']); ?></p>
    <?php endif; ?>

    <?php if (!empty($record['heart_disease_tips'])): ?>
    <p><i class="fas fa-heartbeat" style="color: #00C6A9; margin-right: 6px;"></i><?php echo htmlspecialchars($record['heart_disease_tips']); ?></p>
    <?php endif; ?>

    <?php if (!empty($record['liver_tips'])): ?>
    <p><i class="fas fa-heartbeat" style="color: #00C6A9; margin-right: 6px;"></i><?php echo htmlspecialchars($record['liver_tips']); ?></p>
    <?php endif; ?>


    <?php if (!empty($record['tobacco_tips'])): ?>
    <p><i class="fas fa-heartbeat" style="color: #00C6A9; margin-right: 6px;"></i><?php echo htmlspecialchars($record['tobacco_tips']); ?></p>
    <?php endif; ?>

    
    <?php if (!empty($record['obesity_tips'])): ?>
    <p><i class="fas fa-heartbeat" style="color: #00C6A9; margin-right: 6px;"></i><?php echo htmlspecialchars($record['obesity_tips']); ?></p>
    <?php endif; ?>

    
    <?php if (!empty($record['chronic_condition_tips'])): ?>
    <p><i class="fas fa-heartbeat" style="color: #00C6A9; margin-right: 6px;"></i><?php echo htmlspecialchars($record['chronic_condition_tips']); ?></p>
    <?php endif; ?>

    </div>
<?php endif; ?>
</div>

<h2 style="margin-top: 35px;"></h2>



</body>

</html>
