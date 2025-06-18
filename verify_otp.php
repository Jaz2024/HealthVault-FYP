<?php
session_start();
include("db.php");

// Check if the session is set
if (!isset($_SESSION['email'])) {
    echo "<script type='text/javascript'>alert('Session expired or invalid. Please sign up again.');</script>";
    header("Location: signup.php");
    exit();
}

$email = $_SESSION['email']; // Use the session email
$otp_error_message = '';  // Variable to hold error message

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $entered_otp = $_POST['otp'];

    // Validate OTP input
    if (!empty($entered_otp)) {
        // Sanitize OTP input
        $entered_otp = mysqli_real_escape_string($con, $entered_otp);

        // Fetch stored OTP and expiration time from the database
        $query = "SELECT otp_code, otp_expiration FROM users WHERE email = ?";
        $stmt = mysqli_prepare($con, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 's', $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $stored_otp, $otp_expiration);
            mysqli_stmt_fetch($stmt);

            // Check if OTP exists
            if ($stored_otp) {
                // Check if the OTP is valid and not expired
                $current_time = date("Y-m-d H:i:s");

                if ($stored_otp === $entered_otp && $current_time <= $otp_expiration) {
                    // OTP is valid and not expired
                    $_SESSION['otp_verified'] = true;  // Optional: You can use this flag to check later
                    echo "<script type='text/javascript'>alert('OTP verified successfully! Redirecting to login page.');</script>";
                    header("Location: login.php");
                    exit();
                } else {
                    // OTP is incorrect or expired
                    $otp_error_message = "Invalid OTP or OTP has expired. Please try again.";
                }
            } else {
                // No OTP found for the user
                $otp_error_message = "No OTP found for this email. Please request a new OTP.";
            }

            mysqli_stmt_close($stmt);
        } else {
            // Handle query preparation error
            echo "<script type='text/javascript'>alert('Database error. Please try again later.');</script>";
        }
    } else {
        // Handle empty OTP input
        $otp_error_message = "Please enter the OTP.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css">
    <link href="css/verify_otp.css" rel="stylesheet" />
    <title>OTP Verification</title>
    <style>
        .error-message {
            color: red;
            font-size: 13px;
            display: none; /* Initially hidden */
        }

        .error-message.show {
            display: block; /* Show the error message */
        }
    </style>
</head>
<body>

    <div class="Signup_Container" id="SignUp">
        <form method="POST" action="verify_otp.php" onsubmit="return validateOTP()">
            <div class="Field_Input">
                <label for="otp" style="font-size: 24px; margin-left: 0px; text-align: center;">OTP Verification</label>
            </div>

            <div class="Field_Input">
                <input type="text" name="otp" id="otp" required>
            </div>
            
            <span id="error-message" class="error-message">
                <?php echo $otp_error_message; ?>
            </span>
            
            <div class="field">
                <input type="submit" name="submit" value="Verify OTP">
            </div>
        </form>
    </div>

    <script>
        // Function to validate OTP format
        function validateOTP() {
            var otp = document.getElementById("otp").value;
            var errorMessage = document.getElementById("error-message");

            // Check if OTP is 6 digits
            if (!/^\d{6}$/.test(otp)) {
                errorMessage.textContent = "Invalid OTP format. OTP must be 6 digits."; // Set error message
                errorMessage.style.display = "block"; // Show the error message
                return false; // Prevent form submission
            } else {
                errorMessage.style.display = "none"; // Hide the error message
                return true; // Allow form submission
            }
        }

        // Display error message passed from PHP
        <?php if ($otp_error_message != ''): ?>
            document.addEventListener("DOMContentLoaded", function() {
                var errorMessage = document.getElementById("error-message");
                errorMessage.textContent = "<?php echo $otp_error_message; ?>"; // Set the error message
                errorMessage.style.display = "block"; // Show the error message
            });
        <?php endif; ?>
    </script>

</body>
</html>
