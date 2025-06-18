<?php
session_start();

include("db.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/vendor/autoload.php'; // Autoload PHPMailer

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Retrieve and sanitize input
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password_hash = trim($_POST['password']);
    $password_confirmation = trim($_POST['password_confirmation']);  // For confirming the password

    // Validate inputs
    if (filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($username) && !empty($password_hash) && !empty($password_confirmation)) {
        
        // Password validation
        if (strlen($password_hash) < 8) {
            die("Password must be at least 8 characters");
        }

        if (!preg_match("/[a-z]/i", $password_hash)) {
            die("Password must contain at least one letter");
        }

        if (!preg_match("/[0-9]/i", $password_hash)) {
            die("Password must contain at least one number");
        }

        if ($password_hash !== $password_confirmation) {
            die("Passwords must match");
        }

        // Escape inputs to prevent SQL injection
        $email = mysqli_real_escape_string($con, $email);
        $username = mysqli_real_escape_string($con, $username);
        $password_hash = mysqli_real_escape_string($con, $password_hash);

        // Check if the email already exists
        $check_query = "SELECT * FROM users WHERE email = ?";
        $stmt_check = mysqli_prepare($con, $check_query);
        mysqli_stmt_bind_param($stmt_check, 's', $email);
        mysqli_stmt_execute($stmt_check);
        $result_check = mysqli_stmt_get_result($stmt_check);

        if (mysqli_num_rows($result_check) > 0) {
            echo "<script type='text/javascript'>alert('Email is already taken, please use another one.');</script>";
        } else {
            // Hash password securely
            $hashed_password = password_hash($password_hash, PASSWORD_DEFAULT);

            // Insert user into the database
            $query = "INSERT INTO users (email, username, password_hash) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt, 'sss', $email, $username, $hashed_password);

            if (mysqli_stmt_execute($stmt)) {
                // Generate OTP
                $otp_code = random_int(100000, 999999); // Secure OTP generation
                $otp_expiration = date("Y-m-d H:i:s", strtotime("+10 minutes"));

                // Update OTP in the database
                $update_query = "UPDATE users SET otp_code = ?, otp_expiration = ? WHERE email = ?";
                $stmt_update = mysqli_prepare($con, $update_query);
                mysqli_stmt_bind_param($stmt_update, 'sss', $otp_code, $otp_expiration, $email);
                mysqli_stmt_execute($stmt_update);

                // Send OTP Email
                $mail = new PHPMailer(true);
                try {
                    //Server settings
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'jasvinthan123@gmail.com'; // Replace with your email
                    $mail->Password = 'dwvw ngrv rkkz qxul';     // App password for Gmail
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    //Recipients
                    $mail->setFrom('jasvinthan123@gmail.com', 'HealthVault');
                    $mail->addAddress($email, $username);

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Your OTP Code';
                    $mail->Body    = 'Your OTP code is: <b>' . $otp_code . '</b><br>This code is valid for 10 minutes.';

                    $mail->send();

                    // Redirect after successful sign-up
                    $_SESSION['email'] = $email; // Store email in session for verification
                    header("Location: verify_otp.php");
                    exit();
                } catch (Exception $e) {
                    // Log error for debugging
                    error_log("Mailer Error: " . $mail->ErrorInfo);
                    echo "<script type='text/javascript'>alert('Failed to send OTP email. Please try again later.');</script>";
                }
            } else {
                echo "<script type='text/javascript'>alert('Signup failed, please try again.');</script>";
            }

            // Close the statement
            mysqli_stmt_close($stmt);
        }

        // Close the check statement
        mysqli_stmt_close($stmt_check);
    } else {
        echo "<script type='text/javascript'>alert('Please enter valid information.');</script>";
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

  <title>Sign Up</title>

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
  <!-- responsive style -->
  <link href="css/responsive.css" rel="stylesheet" />
  <link href="css/Signup.css" rel="stylesheet" />

  <style>
    body {
    background: linear-gradient(to bottom, #00C6A9, white);
    height: 96vh;
    margin: 0; /* Remove default margins */
    padding: 0;
    box-sizing: border-box;
    font-family: 'Roboto', sans-serif;
    }

    .error-message {
      color: red;              /* Red text color for errors */
      font-size: 13px;         /* Smaller text size for the error message */
      margin-top: 5px;         /* Space above the error message */
      font-family: Arial, sans-serif; /* Font style */
      display: block;          /* Ensure it takes up space below the input */
    }
  </style>
</head>

<body>

<div class="Signup_Container" id="SignUp">
    <h2 class="form-title" style="font-weight: bold;">Create your HealthVault Account</h2>
    <div class="Links">
      Already have a HealthVault account? <a href="login.php" class="login-link">Log in</a>
    </div>
    <form method="POST" id="signup-form">
        <div class="Field_Input">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>
            <span id="email-error" class="error-message"></span>
        </div>


        <div class="Field_Input">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" required>
            <span id="username-error" class="error-message"></span>
        </div>

        <div class="Field_Input">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
            <span id="password-error" class="error-message"></span>
        </div>

        <div class="Field_Input">
            <label for="password_confirmation">Re-enter Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required>
            <span id="password-confirmation-error" class="error-message"></span>
        </div>

        <div class="field">
            <input type="submit" name="submit" value="Create Account" required>
        </div>

      <div class="term_container">
        By creating an account, you acknowledge that you agree to the <a href="" class="terms-link">terms and conditions</a>
      </div>

    </form>



  </div>
  <script>
    document.getElementById('signup-form').addEventListener('submit', function(event) {
        let valid = true;

        // Clear previous errors
        document.querySelectorAll('.error-message').forEach(function(error) {
            error.textContent = '';
        });

        // Email validation
        const email = document.getElementById('email').value;
        const emailError = document.getElementById('email-error');
        if (!email || !/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(email)) {
            emailError.textContent = 'Please enter a valid email address.';
            valid = false;
        } else {
            // Asynchronous email availability check (replace with real API call)
            checkEmailAvailability(email, function(emailTaken) {
                if (emailTaken) {
                    emailError.textContent = 'Email is already taken, please use another one.';
                    valid = false;
                }

                // If any validation fails, prevent form submission
                if (!valid) {
                    event.preventDefault();
                }
            });
        }

        // Username validation
        const username = document.getElementById('username').value;
        const usernameError = document.getElementById('username-error');
        if (!username) {
            usernameError.textContent = 'Please fill out the username field.';
            valid = false;
        } else if (username.length < 3) {
            usernameError.textContent = 'Username must be at least 3 characters long.';
            valid = false;
        }

        // Password validation
        const password = document.getElementById('password').value;
        const passwordError = document.getElementById('password-error');
        if (password.length < 8) {
            passwordError.textContent = 'Password must be at least 8 characters.';
            valid = false;
        } else if (!/[a-z]/i.test(password)) {
            passwordError.textContent = 'Password must contain at least one letter.';
            valid = false;
        } else if (!/[0-9]/.test(password)) {
            passwordError.textContent = 'Password must contain at least one number.';
            valid = false;
        }

        // Password confirmation validation
        const passwordConfirmation = document.getElementById('password_confirmation').value;
        const passwordConfirmationError = document.getElementById('password-confirmation-error');
        if (password !== passwordConfirmation) {
            passwordConfirmationError.textContent = 'Passwords do not match.';
            valid = false;
        }

        // If any validation fails, prevent form submission
        if (!valid) {
            event.preventDefault();
        }
    });

    // Simulated API call for email availability check
    function checkEmailAvailability(email, callback) {
        // Simulate an API response with a delay
        setTimeout(function() {
            // Here, you can simulate an API response, or use a real API
            const emailTaken = false; // Replace this with your real check, e.g., an API call
            callback(emailTaken);
        }, 1000);
    }
    </script>




</body>
</html>
