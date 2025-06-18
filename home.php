<?php
session_start(); // Start the session

// Include database connection
include("db.php");

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("location: login.php"); // Redirect to login page if not logged in
    exit;
}

// Get the user_id from the session
$user_id = $_SESSION['user_id'];

// Retrieve the username from the database
$sql = "SELECT username FROM users WHERE id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $username = $row['username']; // Fetch username
} else {
    $username = "Guest"; // Fallback if no user is found
}


?>

<!DOCTYPE html>
<html>

<head>
  <!-- Basic -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Mobile Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <!-- Site Metas -->
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <meta name="author" content="" />

  <title>Home</title>


  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

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
  <link href="css/dashboard.css" rel="stylesheet" />
  <!-- responsive style -->
  <link href="css/responsive.css" rel="stylesheet" />

  <style>
  html {
  scroll-behavior: smooth;
}


.slider_section h1 {
  font-size: 4rem;
  letter-spacing: 4px;
  background-color: #00c6a9;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  position: relative;
  display: inline-block;
  margin-bottom: 30px;
}

/* Underline */
.slider_section h1::after {
  content: "";
  position: absolute;
  top: 100%;
  left: 0;
  height: 8px;
  width: 100%;
  border-radius: 8px;
  background-color: rgba(0, 0, 0, 0.1);
}

/* Animated Dot */
.slider_section h1 span {
  position: absolute;
  top: 100%;
  left: 0;
  width: 9px;
  height: 9px;
  border-radius: 50%;
  background-color: #00c6a9;
  animation: anim 3s linear infinite;
}


.heading_container h1{
  font-size: 4rem;
  letter-spacing: 4px;
  background-color: #00c6a9;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  position: relative;
  display: inline-block;
  /*! margin-bottom: 30px; */
}

/* Underline */
.heading_container h1::after {
  content: "";
  position: absolute;
  top: 100%;
  left: 0;
  height: 8px;
  width: 100%;
  border-radius: 8px;
  background-color: rgba(0, 0, 0, 0.1);
}

/* Animated Dot */
.heading_container h1 span {
  position: absolute;
  top: 100%;
  left: 0;
  width: 9px;
  height: 9px;
  border-radius: 50%;
  background-color: #00c6a9;
  animation: anim 3s linear infinite;
}



.features-wrapper {
  padding: 5% 8%;
}

.features {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.features h1 {
  font-size: 4rem;
  letter-spacing: 4px;
  background-color: #00c6a9;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  position: relative;
  display: inline-block;
  margin-bottom: 30px;
}

/* Underline */
.features h1::after {
  content: "";
  position: absolute;
  top: 100%;
  left: 0;
  height: 8px;
  width: 100%;
  border-radius: 8px;
  background-color: rgba(0, 0, 0, 0.1);
}

/* Animated Dot */
.features h1 span {
  position: absolute;
  top: 100%;
  left: 0;
  width: 9px;
  height: 9px;
  border-radius: 50%;
  background-color: #00c6a9;
  animation: anim 3s linear infinite;
}

/* Animation */
@keyframes anim {
  0% {
    left: 0%;
    opacity: 1;
  }
  90% {
    left: 95%;
    opacity: 1;
  }
  100% {
    left: 100%;
    opacity: 0;
  }
}

.cards1 {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 40px;
  margin-top: 0px;
  justify-content: center;
  padding: 40px 0;
}

.cards1 > div {
  height: 180px;
  width: 420px;
  background: linear-gradient(145deg, #e9fdf8, #d0f5ea); /* soft card bg gradient */
  border: 2px solid rgb(153, 226, 202);
  border-radius: 20px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
  transition: all 0.4s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  position: relative;
  overflow: hidden;
  cursor: pointer;
}

/* Headings inside card */
.cards1 > div h2 {
  font-size: 20px;
  color: #035c4c;
  margin-bottom: 10px;
}

/* Text inside card */
.cards1 > div p {
  font-size: 15px;
  text-align: center;
  color: #444;
  padding: 0 20px;
}

/* Shine effect */
.cards1 > div::after {
  content: "";
  position: absolute;
  top: 150%;
  left: -200px;
  width: 150%;
  height: 100%;
  background: linear-gradient(120deg, rgba(255,255,255,0.05), rgba(0,255,255,0.3), rgba(255,255,255,0.05));
  transform: rotate(25deg);
  filter: blur(20px);
  opacity: 0.6;
  transition: all 1s ease-in-out;
}

/* Hover effects */
.cards1 > div:hover {
  transform: translateY(-8px) scale(1.02);
  box-shadow: 0 14px 35px rgba(0, 0, 0, 0.1);
}

.cards1 > div:hover::after {
  top: -100%;
  left: 200%;
}


.cards2 {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 80px;
  margin-top: 0px;
}

.cards2 > div {
  height: 220px; /* Set common height */
  width: 300px;  /* Set common width */
  background-color: rgb(1, 1, 1);
  border-radius: 8px;
  transition: .6s;
  display: flex;
  align-items: center;
  flex-direction: column;
  
}


.cards3 {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 80px;
  margin-top: 0px;
}

.cards3 > div {
  height: 220px; /* Set common height */
  width: 300px;  /* Set common width */
  background-color: rgb(1, 1, 1);
  border-radius: 8px;
  transition: .6s;
  display: flex;
  align-items: center;
  flex-direction: column;
}



.cards4 {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 80px;
  margin-top: 0px;
}

.cards4 > div {
  height: 220px; /* Set common height */
  width: 300px;  /* Set common width */
  background-color: rgb(1, 1, 1);
  border-radius: 8px;
  transition: .6s;
  display: flex;
  align-items: center;
  flex-direction: column;
}


</style>

</head>

<body>

<div class="hero_area"  id="home">
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
                    <a class="nav-link" href="#home">Home <span class="sr-only">(current)</span></a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#about"> About</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#features">Features</a>
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
    <!-- end header section -->
    <!-- slider section -->
    <section class="slider_section"> 
      <div id="customCarousel1" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <div class="container ">
              <div class="row">
                <div class="col-md-6">
                  <div class="detail-box">
                    <h1>
                      Health Vault<span></span>
                    </h1>
                    
                    <p style="margin-top: 12px; margin-bottom: 1rem;">
                      Easily manage and access your health records anytime, anywhere
                    </p>
    
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="img-box">
                    <img src="svg/27431010_7317127.svg" alt="">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>




  <!-- about section -->


  <section class="about_section" id="about">
    <div class="container  ">
      <div class="row">
        <div class="col-md-6 ">
          <div class="img-box" style="  width: 120%;">
            <img src="svg/7912547_3804514.svg" alt="">
          </div>
        </div>
        <div class="col-md-6">
          <div class="detail-box" style="margin-left: 30px; margin-top: 88px;">
            <div class="heading_container">
              <h1>About Website<span></span></h1>
            </div>
            <p>
              This Personal Health Record (PHR) website is designed to help users efficiently manage and monitor their health data in a secure and accessible manner. It provides a comprehensive platform where users can store essential health information such as medical history, medications, allergies and test results. What sets this website apart is its integration of a machine learning algorithm that analyzes the user's health data and offers personalized health tips while identifying potential health risks. The platform also includes an appointment management feature that allows users to schedule and track medical appointments seamlessly. With a focus on simplicity, privacy and user-friendly design, this website aims to empower individuals to take a proactive approach to their health by using data-driven insights to improve their overall wellbeing.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>


    <!-- features section -->
    <section class="features_section" id="features" style="padding-top: 55px;">
    <div class="container">
      <div class="features-wrapper">
        <div class="features">
          <h1>Features<span></span></h1>
          <div class="cards1">
            <div class="card1">
              <h3 style="text-align: center;">Personal Health Record</h3>
              <p style="text-align: center;">Store and view personal health data such as medical history, prescriptions, allergies, and test results securely in one place.</p>
            </div>

            <div class="card2">
              <h3 style="text-align: center;">Smart Health Tips</h3>
              <p style="text-align: center;">Receive lifestyle and wellness tips based on your health inputs such as diet, exercise, or past illnesses.</p>
            </div>

            <div class="card3">
              <h3 style="text-align: center;">Health Risk Prediction</h3>
              <p style="text-align: center;">The system evaluates user data and warns about potential health issues (e.g., risk of diabetes, heart disease, etc.).</p>
            </div>

            <div class="card4">
              <h3 style="text-align: center;">Appointment Management</h3>
              <p style="text-align: center;">Schedule and track upcoming medical appointments with doctors or clinics.</p>
            </div>
          </div>
        </div>

      </div>
    </div>

  <!-- info section -->
  <section class="info_section ">
    <div class="container">
      <div class="info_top">
        <div class="info_logo">
          <a href="">
            <img src="images/logo.png" alt="">
          </a>
        </div>
        <!-- Four sections beside the logo -->
        <div class="info_details">
          <div class="row info_main_row">
            <div class="col-md-6 col-lg-3">
              <div class="info_links">
                <h5>Useful link</h5>
                <div class="info_links_menu">
                  <a  href="#home">Home</a>
                  <a href="#about">About</a>
                  <a href="#features">Features</a>
                  <a href="dashboard.php">Dashboard</a>
                  <a href="appoinment_dashboard.php">Appoinment</a>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-3">
              <div class="info_links">
                <h5>Legal</h5>
                <div class="info_links_menu">
                  <a>Privacy Policy</a>
                  <a>Terms of Service</a>
                  <a>Disclamer</a>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-3">
              <div class="info_links">
                <h5>Support</h5>
                <div class="info_links_menu">
                  <a>Help Center</a>
                  <a>Contact Us</a>
                  <a>Feedback</a>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-3">
              <h5>Address</h5>
              <div class="info_contact">
                <a href="">
                  <i class="fa fa-envelope"></i>
                  <span>HV25@gmail.com</span>
                </a>
              </div>
              <div class="social_box">
                <a href=""><i class="fa fa-facebook" aria-hidden="true"></i></a>
                <a href=""><i class="fa fa-twitter" aria-hidden="true"></i></a>
                <a href=""><i class="fa fa-linkedin" aria-hidden="true"></i></a>
                <a href=""><i class="fa fa-instagram" aria-hidden="true"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
 
  
</body>
</html>

<script>
  const aboutSection = document.getElementById("about");
  const featuresSection = document.getElementById("features"); // Make sure your features section has id="features"
  const originalTitle = "Home";

  window.addEventListener("scroll", () => {
    const aboutRect = aboutSection.getBoundingClientRect();
    const featuresRect = featuresSection.getBoundingClientRect();

    if (featuresRect.top <= window.innerHeight / 2 && featuresRect.bottom >= window.innerHeight / 4) {
      document.title = "Features";
    } else if (aboutRect.top <= window.innerHeight / 2 && aboutRect.bottom >= window.innerHeight / 4) {
      document.title = "About";
    } else {
      document.title = originalTitle;
    }
  });
</script>

