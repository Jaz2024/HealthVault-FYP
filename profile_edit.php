<?php
session_start(); // Start the session to store user data

include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID
$username = $_SESSION['username'];

// Use prepared statements to prevent SQL injection
$query = "SELECT * FROM users WHERE username = ? LIMIT 1";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, 's', $username); // Bind the username parameter
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && mysqli_num_rows($result) > 0) {
    $user_data = mysqli_fetch_assoc($result);
    $current_username = $user_data['username'];
} else {
    echo "<script type='text/javascript'> alert('User not found')</script>";
    header("Location: login.php");
    exit;
}

// Handle the form submission for updating the username
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $new_username = $_POST['username'] ?? '';

    if (!empty($new_username) && !is_numeric($new_username)) {
        // Use prepared statements to prevent SQL injection
        $update_query = "UPDATE users SET username = ? WHERE username = ?";
        $stmt = mysqli_prepare($con, $update_query);
        mysqli_stmt_bind_param($stmt, 'ss', $new_username, $username); // Bind both current and new username
        $update_result = mysqli_stmt_execute($stmt);

        if ($update_result) {
            $_SESSION['username'] = $new_username; // Update session username
            header("Location: dashboard.php"); // Redirect to dashboard
            exit;
        } else {
            echo "<script type='text/javascript'> alert('Failed to update profile')</script>";
        }
    } else {
        echo "<script type='text/javascript'> alert('Please enter a valid username')</script>";
    }
}

// Close statement
mysqli_stmt_close($stmt);
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

  <title>Edit Profile</title>

  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

  <!-- fonts style -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">

  <!-- owl slider stylesheet -->
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />

  <!-- font awesome style -->
  <link href="css/font-awesome.min.css" rel="stylesheet" />
  <!-- nice select -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css" integrity="sha256-mLBIhmBvigTFWPSCtvdu6a76T+3Xyt+K571hupeFLg4=" crossorigin="anonymous" />
  <!-- datepicker -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css">
  <!-- Custom styles for this template -->
  <link href="css/responsive.css" rel="stylesheet" />
  <link href="css/profile_edit.css" rel="stylesheet" />

</head>

<body>
  
  <div class="Signup_Container" id="SignUp">
    <h2 class="form-title" style="font-weight: bold;">Edit Profile </h2>
    <form method="POST">
      <div class="Field_Input">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" value="<?php echo $current_username; ?>" required>
      </div>

      <div class="field">
        <input type="submit" name="update" value="Update" required>
      </div>

    </form>
  </div>

</body>

</html>
