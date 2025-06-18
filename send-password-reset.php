<?php

$email = $_POST['email'];

$token = bin2hex(random_bytes(16));
$token_hash = hash("sha256", $token);
$expiry = date("Y-m-d H:i:s", time() + 60 * 30);

$mysqli = require __DIR__ . "/db.php";

$sql = "UPDATE users
        SET reset_token_hash = ?,
            reset_token_expires_at = ?
        WHERE email = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("sss", $token_hash, $expiry, $email);
$stmt->execute();

if ($stmt->affected_rows > 0) {

  require __DIR__ . "/mailer.php";

  $mail = new PHPMailer\PHPMailer\PHPMailer();
  $mail->isSMTP();
  $mail->Host = 'smtp.gmail.com';
  $mail->SMTPAuth = true;
  $mail->Username = 'jasvinthan123@gmail.com';
  $mail->Password = 'dwvw ngrv rkkz qxul';
  $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
  $mail->Port = 587;

  $mail->setFrom("noreply@example.com");
  $mail->addAddress($email);
  $mail->Subject = "Password Reset";

  $encodedToken = urlencode($token);

  $mail->IsHTML(true);
  $mail->Body = <<<END
    Click <a href="http://localhost:8080/HealthVault//reset-password.php?token=$token">here</a> to reset your password.
  END;

  try {
    $mail->send();
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8">
      <title>Email Sent Successfully</title>
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
      <style>
        body {
          background-color: #f4f6f8;
          display: flex;
          justify-content: center;
          align-items: center;
          height: 100vh;
        }
        .message-box {
  background: white;
  padding: 30px;
  border-radius: 12px;
  border: 2px solid #00C6A9;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
  text-align: center;
  max-width: 400px;
  width: 90%;
  transition: box-shadow 0.3s ease;
}

.message-box:hover {
  box-shadow: 0 12px 28px rgba(0, 198, 169, 0.25);
}

.message-box h3 {
  color: #00C6A9;
  font-weight: 600;
  margin-bottom: 15px;
}

.message-box p {
  font-size: 16px;
  color: #333;
}

.message-box .btn-primary {
  background-color: #00C6A9;
  border: none;
  padding: 10px 20px;
  font-weight: 500;
  transition: background-color 0.3s ease;
}

.message-box .btn-primary:hover {
  background-color: #009e89;
}

      </style>
    </head>
    <body>
      <div class="message-box">
        <h3>📩 Email Sent</h3>
        <p>Message sent, please check your inbox for a password reset link.</p>
      </div>
    </body>
    </html>
<?php
  } catch (Exception $e) {
    echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
  }

} else {
  echo "No user found with that email address.";
}
?>
