<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require __DIR__ . "/phpmailer/vendor/autoload.php";  // Corrected the path

$mail = new PHPMailer(true);

//$mail->SMTPDebug = SMTP::DEBUG_SERVER;  // Uncomment for debugging if needed

$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';  // Use Gmail's SMTP server
$mail->SMTPAuth = true;
$mail->Username = 'jasvinthan123@gmail.com';  // Your Gmail address
$mail->Password = 'dwvw ngrv rkkz qxul';  // Your Gmail app password
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;

$mail->isHtml(true);


return $mail;

?>
