<?php
include('includes/config.php');  // Make sure to include your database configuration

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';
// Initialize the variables or check if they are set before usage
$id = isset($_POST['id']) ? $_POST['id'] : null;
$email = isset($_POST['email']) ? $_POST['email'] : null;
if (isset($_POST['id']) && isset($_POST['email'])) {
  $id = $_POST['id'];
  $email = $_POST['email'];

  // Update the user's verification status
  $sql = "UPDATE tblusers SET is_verified = 1 WHERE id = :id AND EmailId = :email AND is_verified = 0";
  $query = $dbh->prepare($sql);
  $query->bindParam(':id', $id, PDO::PARAM_INT);
  $query->bindParam(':email', $email, PDO::PARAM_STR);
  $result = $query->execute();

  if ($query->rowCount() == 0) {
    echo "No user found with the specified ID and email that is not already verified.";
    exit;
  }
  $mail = new PHPMailer(true);
  try {
    //Server settings
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'keyanandydelgado@gmail.com';             // SMTP username
    $mail->Password   = 'gcrcajkxfpcidqpe';                         // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;                                  // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    //Recipients
    $mail->setFrom('keyanandydelgado@gmail.com', 'Mailer');
    $mail->addAddress($email);     // Add a recipient

    // Content
    $mail->isHTML(true);  // Set email format to HTML

    $mail->Subject = 'Account Verification Successful';

    // HTML body
    $mail->Body = <<<EOT
<html>
<head>
  <title>Account Verification Successful</title>
</head>
<body>
  <h1>Hello!</h1>
  <p>Your account has been successfully verified. Thank you for completing the verification process. You can now log in and start using all the features available to registered users.</p>
  <p><a href="https://www.yourdomain.com/login" style="padding: 10px 20px; color: white; background-color: #007BFF; text-decoration: none; border-radius: 5px;">Log In Now</a></p>
  <p>If you have any questions, feel free to contact our support team.</p>
  <p>Best Regards,<br>Your Company Name</p>
</body>
</html>
EOT;

    // Plain text body (for email clients that do not support HTML)
    $mail->AltBody = "Hello! Your account has been successfully verified. Thank you for completing the verification process. You can now log in and start using all the features available to registered users. Log in here: https://www.yourdomain.com/login. If you have any questions, feel free to contact our support team.";

    $mail->send();
    echo "User verified and email sent successfully.";
  } catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
  }
}
