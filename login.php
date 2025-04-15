<?php
session_start();
$con=mysqli_connect('localhost','root','','project');

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// User inputs
$email = $_POST['email'];
$password = $_POST['password'];
$remember = isset($_POST['remember']) ? $_POST['remember'] : '';

// Secure query
$sql = "SELECT * FROM login WHERE email = '$email' AND password = '$password'";
$result = $con->query($sql);

// Check if a row is found
if ($result->num_rows > 0) {
    // Get user information from signup table to display name
    $user_query = "SELECT fname FROM signup WHERE email = '$email'";
    $user_result = $con->query($user_query);
    
    if ($user_result->num_rows > 0) {
        $user_data = $user_result->fetch_assoc();
        // Set session variables
        $_SESSION['user_email'] = $email;
        $_SESSION['user_name'] = $user_data['fname'];
    }
    
    // Set cookies if remember me is checked
    if($remember == 'on') {
        setcookie('email', $email, time() + (30 * 24 * 60 * 60), '/'); // 30 days
        setcookie('password', $password, time() + (30 * 24 * 60 * 60), '/');
    } else {
        // If not checked, clear cookies
        setcookie('email', '', time() - 3600, '/');
        setcookie('password', '', time() - 3600, '/');
    }
    
    echo "<script>
        alert('Logged in successfully!');
        window.location.href = 'water_quality.php';
    </script>";
} else {
    echo "<script>
        alert('Invalid email or password!');
        window.location.href = 'login.html';
    </script>";
}

$con->close();
?>