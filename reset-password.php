<?php
require 'auth_db.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create a log file for debugging
function logError($message) {
    $logFile = 'reset_password_errors.log';
    file_put_contents($logFile, date('[Y-m-d H:i:s] ') . $message . PHP_EOL, FILE_APPEND);
}

header('Content-Type: application/json');

// Get email from POST request
$email = isset($_POST['email']) ? trim($_POST['email']) : '';

if (empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Email is required']);
    exit;
}

// Log the email being checked
logError("Checking email: " . $email);

// Check if emailExists function exists
if (!function_exists('emailExists')) {
    logError("emailExists function not found in auth_db.php");
    echo json_encode(['success' => false, 'message' => 'System error: Email verification function not available']);
    exit;
}

// Check if email exists in the database
if (emailExists($conn, $email)) {
    // Log that email was found
    logError("Email found in database: " . $email);
    
    // Generate a unique token
    $token = bin2hex(random_bytes(32));
    
    if (storeResetToken($conn, $email, $token)) {
        try {
            $mail = new PHPMailer(true);
            
            // Server settings with more detailed debugging
            $mail->SMTPDebug = 3; // Enable verbose debug output
            $mail->Debugoutput = function($str, $level) {
                logError("PHPMailer Debug: $str");
            };
            
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'moudgillkomal6@gmail.com';
            $mail->Password = 'ahdb ebuz zyzr shhu';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            // Recipients
            $mail->setFrom('moudgillkomal6@gmail.com', 'Water Quality Analysis');
            $mail->addAddress($email);

            // Content
            $resetLink = "http://localhost/php/Komal/update-password.html?token=" . $token;
            
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                    <h2>Password Reset Request</h2>
                    <p>Click the button below to reset your password:</p>
                    <p style='margin: 25px 0;'>
                        <a href='$resetLink' 
                           style='background-color: #1e4976; 
                                  color: white; 
                                  padding: 12px 25px; 
                                  text-decoration: none; 
                                  border-radius: 5px; 
                                  display: inline-block;'>
                            Reset Password
                        </a>
                    </p>
                    <p>If the button doesn't work, copy and paste this link in your browser:</p>
                    <p>$resetLink</p>
                    <p>This link will expire in 1 hour.</p>
                </div>
            ";
            $mail->AltBody = "Reset your password using this link: $resetLink";

            $mail->send();
            logError("Email sent successfully to: " . $email);
            echo json_encode([
                'success' => true, 
                'message' => 'Reset instructions have been sent to your email.'
            ]);
        } catch (Exception $e) {
            logError("Mail Error: " . $e->getMessage());
            echo json_encode([
                'success' => false, 
                'message' => 'Could not send reset email: ' . $e->getMessage()
            ]);
        }
    } else {
        logError("Failed to store reset token for email: " . $email);
        echo json_encode([
            'success' => false, 
            'message' => 'Failed to process your request. Please try again.'
        ]);
    }
} else {
    // Email doesn't exist in the database
    logError("Email not found in database: " . $email);
    echo json_encode([
        'success' => false, 
        'message' => 'Email not found. Please check your email or register a new account.'
    ]);
}
?>