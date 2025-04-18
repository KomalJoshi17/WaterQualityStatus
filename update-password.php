<?php
// Turn off error display to prevent HTML errors from breaking JSON
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Create a log file for debugging
function logError($message) {
    $logFile = 'update_password_errors.log';
    file_put_contents($logFile, date('[Y-m-d H:i:s] ') . $message . PHP_EOL, FILE_APPEND);
}

// Set JSON header before any output
header('Content-Type: application/json');

// Buffer output to catch any errors
ob_start();

try {
    // Include database connection
    require 'auth_db.php';

    // Get token and password from POST request
    $token = isset($_POST['token']) ? trim($_POST['token']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (empty($token) || empty($password)) {
        throw new Exception('Token and password are required');
    }

    // Log the token being checked
    logError("Checking token: " . $token);

    // Verify token and get associated email
    $email = verifyResetToken($conn, $token);

    if ($email) {
        // Update password in both tables
        $updateSuccess = updatePassword($conn, $email, $password);
        
        if ($updateSuccess) {
            // Delete the used token
            deleteResetToken($conn, $token);
            
            echo json_encode([
                'success' => true, 
                'message' => 'Your password has been updated successfully!'
            ]);
        } else {
            throw new Exception('Failed to update password. Please try again.');
        }
    } else {
        throw new Exception('Invalid or expired reset link. Please request a new one.');
    }
} catch (Exception $e) {
    logError("Error: " . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => $e->getMessage()
    ]);
} finally {
    // Clean output buffer
    $output = ob_get_clean();
    
    // Check if output is not valid JSON
    if (!empty($output) && $output[0] !== '{') {
        logError("Invalid output: " . $output);
        echo json_encode([
            'success' => false,
            'message' => 'Server error occurred. Please try again later.'
        ]);
    } else {
        echo $output;
    }
    
    // Close database connection if it exists
    if (isset($conn)) {
        mysqli_close($conn);
    }
}

// Function to verify token and return associated email
function verifyResetToken($conn, $token) {
    $token = mysqli_real_escape_string($conn, $token);
    
    // Check if token exists and is not expired (1 hour validity)
    $query = "SELECT email, created_at FROM password_reset_tokens WHERE token = '$token'";
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        logError("Database error in verifyResetToken: " . mysqli_error($conn));
        return false;
    }
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $created_at = strtotime($row['created_at']);
        $current_time = time();
        
        // Check if token is less than 1 hour old
        if (($current_time - $created_at) < 3600) {
            return $row['email'];
        } else {
            logError("Token expired for email: " . $row['email']);
            // Delete expired token
            deleteResetToken($conn, $token);
            return false;
        }
    }
    
    return false;
}

// Function to update password in both tables
function updatePassword($conn, $email, $password) {
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);
    
    // Start transaction
    mysqli_begin_transaction($conn);
    
    try {
        // Update password in signup table
        $query1 = "UPDATE signup SET password = '$password' WHERE email = '$email'";
        $result1 = mysqli_query($conn, $query1);
        
        // Update password in login table if it exists
        $query2 = "UPDATE login SET password = '$password' WHERE email = '$email'";
        $result2 = mysqli_query($conn, $query2);
        
        if ($result1) {
            mysqli_commit($conn);
            return true;
        } else {
            throw new Exception("Failed to update password");
        }
    } catch (Exception $e) {
        mysqli_rollback($conn);
        logError("Transaction failed: " . $e->getMessage());
        return false;
    }
}

// Function to delete reset token
function deleteResetToken($conn, $token) {
    $token = mysqli_real_escape_string($conn, $token);
    $query = "DELETE FROM password_reset_tokens WHERE token = '$token'";
    mysqli_query($conn, $query);
}
?>