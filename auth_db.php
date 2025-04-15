<?php
$conn = mysqli_connect('localhost', 'root', '', 'project');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to check if email exists in the database
function emailExists($conn, $email) {
    $email = mysqli_real_escape_string($conn, $email);
    $query = "SELECT * FROM signup WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        error_log("Database error in emailExists: " . mysqli_error($conn));
        return false;
    }
    
    return mysqli_num_rows($result) > 0;
}

// Function to store reset token in the database
function storeResetToken($conn, $email, $token) {
    $email = mysqli_real_escape_string($conn, $email);
    $token = mysqli_real_escape_string($conn, $token);
    $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
    
    // Check if a reset_tokens table exists, if not create it
    $checkTable = mysqli_query($conn, "SHOW TABLES LIKE 'reset_tokens'");
    if (mysqli_num_rows($checkTable) == 0) {
        $createTable = "CREATE TABLE reset_tokens (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) NOT NULL,
            token VARCHAR(255) NOT NULL,
            expires DATETIME NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        if (!mysqli_query($conn, $createTable)) {
            error_log("Error creating reset_tokens table: " . mysqli_error($conn));
            return false;
        }
    }
    
    // Delete any existing tokens for this email
    $deleteQuery = "DELETE FROM reset_tokens WHERE email = '$email'";
    mysqli_query($conn, $deleteQuery);
    
    // Insert new token
    $insertQuery = "INSERT INTO reset_tokens (email, token, expires) VALUES ('$email', '$token', '$expires')";
    return mysqli_query($conn, $insertQuery);
}
?>