<?php

$token = $_GET["token"];

$token_hash = hash("sha256", $token);


$mysqli = require __DIR__ . "/db.php";

$sql = "SELECT * FROM users
        WHERE reset_token_hash = ?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("s", $token_hash);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if ($user == null) {
  die("token not found");
}

if (strtotime($user["reset_token_expires_at"]) <= time()) {
  die("token has expired");
}


?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Reset Password</title>

    <!-- Include your CSS files here -->
    <link rel="stylesheet" href="css/bootstrap.css" />
    <link href="css/font-awesome.min.css" rel="stylesheet" />
    <link href="css/forgot-password.css" rel="stylesheet" />
    <style>
        .error-message {
            color: red;
            font-size: 12px;
            display: block;
        }
    </style>
</head>
<body>

    <div class="Signup_Container" id="SignUp">
        <h2 class="form-title" style="font-weight: bold;">Reset Password</h2>
        <form method="POST" action="process-reset-password.php" onsubmit="return validateForm()">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

            <div class="Field_Input">
                <label for="password">New Password</label>
                <input type="password" name="password" id="password" required>
                <span id="password-error" class="error-message">
                    <?php if (isset($_SESSION['error_message']) && strpos($_SESSION['error_message'], 'Password') !== false): ?>
                        <?= $_SESSION['error_message']; ?>
                    <?php endif; ?>
                </span>
            </div>

            <div class="Field_Input">
                <label for="password_confirmation">Re-enter New Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required>
                <span id="password-confirmation-error" class="error-message">
                    <?php if (isset($_SESSION['error_message']) && strpos($_SESSION['error_message'], 'match') !== false): ?>
                        <?= $_SESSION['error_message']; ?>
                    <?php endif; ?>
                </span>
            </div>

            <div class="field">
                <input type="submit" name="submit" value="Save New Password" required>
            </div>
        </form>

        <?php
        // Clear session error message after displaying it
        if (isset($_SESSION['error_message'])) {
            unset($_SESSION['error_message']);
        }
        ?>

    </div>

    <script>
        function validateForm() {
            let valid = true;

            // Password validation
            const password = document.getElementById('password').value;
            const passwordError = document.getElementById('password-error');

            if (password.length < 8) {
                passwordError.textContent = 'Password must be at least 8 characters.';
                passwordError.style.display = 'block';
                valid = false;
            } else if (!/[a-z]/i.test(password)) {
                passwordError.textContent = 'Password must contain at least one letter.';
                passwordError.style.display = 'block';
                valid = false;
            } else if (!/[0-9]/.test(password)) {
                passwordError.textContent = 'Password must contain at least one number.';
                passwordError.style.display = 'block';
                valid = false;
            } else {
                passwordError.style.display = 'none'; // Hide error if valid
            }

            // Password confirmation validation
            const passwordConfirmation = document.getElementById('password_confirmation').value;
            const passwordConfirmationError = document.getElementById('password-confirmation-error');

            if (password !== passwordConfirmation) {
                passwordConfirmationError.textContent = 'Passwords do not match.';
                passwordConfirmationError.style.display = 'block';
                valid = false;
            } else {
                passwordConfirmationError.style.display = 'none'; // Hide error if match
            }

            return valid; // Return false to prevent form submission if invalid
        }
    </script>

</body>
</html>
