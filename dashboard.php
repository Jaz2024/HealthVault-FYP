<?php
session_start(); // Start the session to store user data

include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

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


// Fetch health records for the logged-in user
$sql = "SELECT id, recordtitle, created_at FROM health_data WHERE user_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();


// Pagination setup
$limit = 5;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Fetch appointments with limit
$sql = "SELECT id, recordtitle, created_at FROM health_data WHERE user_id = ?  LIMIT ?, ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("iii", $user_id, $start, $limit);
$stmt->execute();
$result = $stmt->get_result();

// Count total rows for pagination
$countQuery = $con->prepare("SELECT COUNT(*) as total FROM health_data WHERE user_id = ?");
$countQuery->bind_param("i", $user_id);
$countQuery->execute();
$countResult = $countQuery->get_result();
$totalRows = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);


// Get total count of user appointments
$countSql = "SELECT COUNT(*) AS total FROM health_data WHERE user_id = ?";
$countStmt = $con->prepare($countSql);
$countStmt->bind_param("i", $user_id);
$countStmt->execute();
$countResult = $countStmt->get_result();
$totalHealthDatas = $countResult->fetch_assoc()['total'];
$countStmt->close();

$totalPages = ceil($totalHealthDatas / $limit);

mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html>

<head>
  <!-- Basic -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Mobile Metas -->
  
  <!-- Site Metas -->
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <meta name="author" content="" />

  <title>Dashboard</title>

  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


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


  

</head>

<body class="sub_page">
  <div class="hero_area">
    <!-- header section starts -->
    <header class="header_section">
      <div class="header_bottom">
        <div class="container-fluid">
          <nav class="navbar navbar-expand-lg custom_nav-container ">
            <a class="navbar-brand" href="home.php">
              <img src="images/logo.png" alt="">
            </a>
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
                  <a href="#" class="submenu-item" id="logoutItem">LOGOUT</a>
                </div>
              </div>

              <!-- Logout Confirmation Popup -->
              <div class="cd-popup" id="popup" role="alert">
                <div class="cd-popup-container">
                  <p>Are you sure you want to log out?</p>
                  <ul class="cd-buttons">
                    <li><a href="logout.php?redirect=home.html" id="confirmLogout">Yes</a></li>
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

                .table {
      width: 77%; /* Adjust the width to decrease left and right margins */
      margin: 0 auto; /* Center the table horizontally */
  }

  /* Optional: Adjust cell padding for a more compact table */
  .table th, .table td {
      padding: 8px; /* Decrease padding for compact rows */
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


          </nav>
        </div>
      </div>
    </header>
  </div>

     
  <body class="sub_page">
    <div class="container mt-4 manage-record-section"> <!-- Add the class here -->
      <div class="row justify-content-between align-items-center mb-2">
        <div class="col-auto" style="left: -16px;">
          <h2 class="row-header-title">Health Records</h2>
        </div>
        <div class="col-auto">
        <a href="health-data-form.html" class="btn btn-primary">Add New Record</a>
        </div>
        </div>
      </div>
    </div>

            <!-- Table to display health records -->
            <table class="table table-bordered mt-2">
    <thead class="text-center">
        <tr>
            <th>#</th>
            <th class="title-column">Title</th> <!-- Add class to header -->
            <th>Created At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody class="text-center"> <!-- This centers the content of all table cells -->
        <?php
        $counter = $start + 1;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $counter++ . "</td>";
                echo "<td class='title-column'>" . htmlspecialchars($row['recordtitle']) . "</td>"; 
                echo "<td>" . date("Y-m-d H:i:s", strtotime($row['created_at'])) . "</td>";
                echo "<td class='actions-column text-end'>                              
                                    <a href='viewRecord.php?id=" . htmlspecialchars($row['id']) . "' class='btn btn-info btn-sm'>
                        <i class='fas fa-eye'></i> View
                    </a>
                    <a href='editRecord.php?id=" . htmlspecialchars($row['id']) . "' class='btn btn-warning btn-sm'>
                        <i class='fas fa-edit'></i> Edit
                    </a>
                    <a href='#' class='btn btn-danger btn-sm deleteBtn' data-id='" . htmlspecialchars($row['id']) . "'>
                        <i class='fas fa-trash-alt'></i> Delete
                    </a>
                </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No records found.</td></tr>";
        }
        ?>
    </tbody>
</table>

<!-- Inline CSS for shifting the second column to the right by setting its width -->
<style>
    .title-column {
        width: 300px;  /* Adjust the width to move the right edge of the second column */
        padding-right: 20px; /* Optional: Adds padding to the right side */
    }

    .actions-column {
        padding-left: 20px;  /* Adds padding to the left side of the Actions column */
        width: 300px;  /* Adjust the width to move the right edge of the second column */
    }

    .table th, .table td {
        vertical-align: middle;  /* Vertically centers the content in each cell */
    }

    .table, th, td {
        text-align: center; /* Centers the text in both headers and cells */
    }

    /* Add margin to the action buttons for spacing */
    .actions-column a {
        margin-right: 10px; /* Add spacing between the buttons */
    }
</style>


                </table>
                </div>
                <div class="d-flex justify-content-center align-items-center mt-3">
  <!-- Previous Button -->
  <div class="me-2">
    <?php if ($page > 1): ?>
      <a class="btn btn-outline-primary btn-sm" href="?page=<?php echo $page - 1; ?>">Previous</a>
    <?php else: ?>
      <button class="btn btn-outline-secondary btn-sm" disabled>Previous</button>
    <?php endif; ?>
  </div>

  <!-- Summary Info -->
  <div class="mx-2 text-muted small">
    <?php
    $showStart = $start + 1;
    $showEnd = min($start + $limit, $totalHealthDatas);
    ?>
    Showing <?php echo $showStart; ?>–<?php echo $showEnd; ?> of <?php echo $totalHealthDatas; ?> health records
  </div>

  <!-- Next Button -->
  <div class="ms-2">
    <?php if ($page < $totalPages): ?>
      <a class="btn btn-outline-primary btn-sm" href="?page=<?php echo $page + 1; ?>">Next</a>
    <?php else: ?>
      <button class="btn btn-outline-secondary btn-sm" disabled>Next</button>
    <?php endif; ?>
  </div>
</div>


<?php $con->close(); ?>

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

  </body>
  
  <!-- info section -->
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
                  <a  href=" home.php #home">Home</a>
                  <a href="home.php #about">About</a>
                  <a href="home.php #features">Features</a>
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
