<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$response = ['success' => false, 'message' => ''];

// Database connection
$con = mysqli_connect('localhost', 'root', '', 'project');
if (!$con) {
    $response['message'] = "Connection failed: " . mysqli_connect_error();
    echo json_encode($response);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $firstName = mysqli_real_escape_string($con, $_POST['firstName']);
    $lastName = mysqli_real_escape_string($con, $_POST['lastName']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    try {
        // Check if email already exists
        $check_email = "SELECT email FROM signup WHERE email = '$email'";
        $result = mysqli_query($con, $check_email);
        
        if (mysqli_num_rows($result) > 0) {
            $response['message'] = 'This email is already registered. Please use a different email.';
            echo json_encode($response);
            exit();
        }

        // Insert into signup table
        $sql = "INSERT INTO signup (fname, lname, email, phone, password) 
                VALUES ('$firstName', '$lastName', '$email', '$phone', '$password')";
        
        if (mysqli_query($con, $sql)) {
            // Insert into login table
            $sql = "INSERT INTO login (email, password) 
                    VALUES ('$email', '$password')";
            
            if (mysqli_query($con, $sql)) {
                $response['success'] = true;
                $response['message'] = 'Registration successful!';
            }
        } else {
            $response['message'] = 'Registration failed. Please try again.';
        }

    } catch (Exception $e) {
        $response['message'] = 'An error occurred. Please try again.';
    }
}

mysqli_close($con);
echo json_encode($response);
?>