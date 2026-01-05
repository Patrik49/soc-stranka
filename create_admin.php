<?php
require_once "config.php";

$username = "admin";
$email = "admin@example.com";
$password = "adminpassword";
$is_admin = 1;

// Check if admin already exists
$sql_check = "SELECT id FROM users WHERE username = ? OR email = ?";
if ($stmt_check = mysqli_prepare($link, $sql_check)) {
    mysqli_stmt_bind_param($stmt_check, "ss", $username, $email);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);
    
    if (mysqli_stmt_num_rows($stmt_check) > 0) {
        echo "Admin user already exists.";
    } else {
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, email, password, is_admin) VALUES (?, ?, ?, ?)";
        
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssi", $param_username, $param_email, $param_password, $param_is_admin);
            
            // Set parameters
            $param_username = $username;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_is_admin = $is_admin;
            
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                echo "Admin user created successfully.";
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close statement
    mysqli_stmt_close($stmt_check);
}

// Close connection
mysqli_close($link);
?>
