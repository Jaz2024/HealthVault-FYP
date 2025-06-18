<?php
session_start(); // Start the session to store user data

include("db.php");

$emailError = "";
$passwordError = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? ''; // This is the plain text password entered by the user

    // Validate that email and password are not empty and email is not numeric
    if (!empty($email) && !empty($password) && !is_numeric($email)) {
        
        // Sanitize the email to prevent SQL injection
        $email = mysqli_real_escape_string($con, $email);

        // Use a prepared statement to avoid SQL injection
        $query = "SELECT * FROM users WHERE email = ? LIMIT 1";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);

            // Verify password using password_verify() (assuming the stored password is hashed)
            if (password_verify($password, $user_data['password_hash'])) {
                // Store user data in session variables
                $_SESSION['user_id'] = $user_data['id'];  // Store user ID
                $_SESSION['username'] = $user_data['username'];  // Store username

                // Redirect to the dashboard
                header("Location: dashboard.php");
                exit();
              } else {
                $passwordError = "The password you entered is incorrect. Please try again.";
            }
          } else {
            $emailError = "No account found with that email.<br>Please check the email address or sign up for a new account.";
        }
        
        

        // Close the prepared statement
        mysqli_stmt_close($stmt);
    } else {
        if (empty($email) || is_numeric($email)) {
            $emailError = "Please enter a valid email.";
        }
        if (empty($password)) {
            $passwordError = "Please enter your password.";
        }
    }
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

  <title>Login</title>

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
  <!-- responsive style -->
  <link href="css/responsive.css" rel="stylesheet" />
  <link href="css/login.css" rel="stylesheet" />

  <script>
    function validateForm() {
      var email = document.getElementById('email').value;
      var password = document.getElementById('password').value;
      var isValid = true;

      // Clear previous error messages
      document.getElementById('emailError').textContent = "";
      document.getElementById('passwordError').textContent = "";

      // Validate email
      var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
      if (email === "" || !emailPattern.test(email)) {
        document.getElementById('emailError').textContent = "Please enter a valid email.";
        isValid = false;
      }

      // Validate password
      if (password === "") {
        document.getElementById('passwordError').textContent = "Please enter your password.";
        isValid = false;
      }

      // Check if the email is numeric
      if (!isNaN(email)) {
        document.getElementById('emailError').textContent = "Email cannot be a number.";
        isValid = false;
      }

      return isValid;
    }
  </script>

  <style>
    body {
    background: linear-gradient(to bottom, #00C6A9, white);
    height: 96vh; /* Ensure it covers the full viewport height */
    margin: 0; /* Remove default margins */
    padding: 0;
    box-sizing: border-box;
    font-family: 'Roboto', sans-serif;
  }
    /* Style for error messages */
  span#emailError, span#passwordError {
    color: red;              /* Red text color for errors */
      font-size: 13px;         /* Smaller text size for the error message */
      margin-top: 5px;         /* Space above the error message */
      font-family: Arial, sans-serif; /* Font style */
      display: block;          /* Ensure it takes up space below the input */
  }

  </style>

</head>

<body>
  
  <div class="Login_Container" id="Login">
    <h2 class="form-title" style="font-weight: bold;">Login to Your Account</h2>
    <div class="Links">
      Don't have an account? <a href="Signup.php" class="signup_link">Sign Up</a>
    </div>
    <form method="POST" onsubmit="return validateForm()">
      <div class="Field_Input">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" required value="<?php echo htmlspecialchars($email ?? ''); ?>">
        <!-- Error message for email -->
        <span id="emailError" style="color: red;"><?php echo $emailError; ?></span>
      </div>

      <div class="Field_Input">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>
        <!-- Error message for password -->
        <span id="passwordError" style="color: red;"><?php echo $passwordError; ?></span>
        <p class="forgot-password">
          <a href="forgot-password.php" class="forgot-password-link">Forgot password?</a>
        </p>
        
      </div>

      <div class="field">
        <input type="submit" name="submit" value="Login" required>
      </div>
    
      <div class="term_container">
        By logging in, you acknowledge that you agree to  <a href="" class="terms-link">Terms and Conditions.</a>
      </div>      
      
    </form>

  </div>

</body>