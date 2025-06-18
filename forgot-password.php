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

  <title>Reset Your Password</title>

  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

  <!-- fonts style -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">

  <!-- font awesome style -->
  <link href="css/font-awesome.min.css" rel="stylesheet" />
  <!-- nice select -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css" integrity="sha256-mLBIhmBvigTFWPSCtvdu6a76T+3Xyt+K571hupeFLg4=" crossorigin="anonymous" />
  <!-- Custom styles for this template -->
  <!-- responsive style -->
  <link href="css/responsive.css" rel="stylesheet" />
  <link href="css/forgot-password.css" rel="stylesheet" />

  <style>
    #error-message {
      color: red;
      font-size: 14px;
      display: none; /* Initially hidden */
    }
  </style>

  <script>
    // JavaScript to validate the form and show the error message if email is empty or invalid
    function validateForm(event) {
      var email = document.getElementById('email').value;
      var errorMessage = document.getElementById('error-message');

      // Simple email validation pattern (checks for @ and .)
      var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

      // Check if email is empty or invalid
      if (email === "") {
        errorMessage.textContent = "Please enter an email address.";
        errorMessage.style.display = "inline"; // Show the error message
        event.preventDefault(); // Prevent form submission
      } else if (!emailPattern.test(email)) {
        errorMessage.textContent = "Please enter a valid email address.";
        errorMessage.style.display = "inline"; // Show the error message
        event.preventDefault(); // Prevent form submission
      } else {
        // Clear the error message if email is valid
        errorMessage.textContent = "";
        errorMessage.style.display = "none"; // Hide the error message
      }
    }
  </script>
</head>

<body>
  
  <div class="Signup_Container" id="SignUp">
    <h2 class="form-title" style="font-weight: bold;">Forgot Password </h2>
    <form method="POST" action="send-password-reset.php" onsubmit="validateForm(event)">
      <div class="Field_Input">
        <label for="email">Email</label>
        <!-- Change input type to text to prevent browser email validation -->
        <input type="text" name="email" id="email">
        <!-- Error message displayed in a span -->
        <span id="error-message"></span>
      </div>

      <div class="field">
        <input type="submit" name="send" value="Send">
      </div>

    </form>

  </div>

</body>
</html>
