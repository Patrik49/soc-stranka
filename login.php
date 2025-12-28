<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            // Redirect user to welcome page
                            header("location: index.php");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = "No account found with that username.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footshop - Prihlásenie</title>
    <link rel="stylesheet" href="css/style_index.css">
    <link rel="stylesheet" href="css/style_doprava.css">
    <link rel="stylesheet" href="css/style_auth.css">
</head>
<body>
    <header>
        <div class="logo_wrapper">
            <a href="index.php"><svg class="icon" viewBox="0 0 512 512" fill="#000000"><g><path class="st0" d="M503.817,197.695c-2.064-9.755-4.14-18.334-5.731-25.465c-1.602-7.103-2.668-12.864-2.862-16.389 c-0.838-14.137-4.21-26.33-9.476-36.533c-3.942-7.646-8.932-14.152-14.547-19.526c-8.421-8.063-18.203-13.578-28.048-17.074 c-9.864-3.488-19.813-4.993-28.961-4.993c-8.548,0-41.347,0.194-72.089,0.388c-30.731,0.194-59.346,0.38-59.346,0.38h0.067 c-12.248,0.015-24.053,4.344-33.056,12.319c-4.49,3.987-8.262,8.921-10.892,14.614c-2.627,5.686-4.087,12.14-4.084,18.974 c0,0.559,0.015,1.133,0.034,1.707c0.04,1.147,0.052,2.31,0.052,3.465c0,13.138-2.56,25.525-7.028,37.174 c-6.689,17.461-17.737,33.291-30.716,46.839c-12.967,13.549-27.836,24.78-41.765,33.075c-21.601,12.878-39.804,20.122-54.4,24.176 c-14.606,4.047-25.604,4.904-32.966,4.904c-2.373,0-4.368-0.089-6.022-0.194c-1.661-0.112-2.94-0.246-4.084-0.35l0.018,0.008 c-1.632-0.15-3.246-0.224-4.841-0.224c-8.269-0.015-16.034,2.012-22.738,5.634c-5.038,2.705-9.483,6.275-13.273,10.419 c-5.682,6.23-9.923,13.743-12.767,21.925C1.454,321.14,0,330.023,0,339.145c0,9.934,1.729,20.167,5.404,30.056 c3.67,9.882,9.297,19.422,17.062,27.828c7.2,7.796,16.12,13.772,25.935,18.497l11.544,5.545l4.316-17.715l7.873,1.014 l-0.354,19.966l8.168,1.908c12.737,2.966,26.155,4.852,39.641,6.06l10.106,0.894l1.855-16.702h7.937l1.922,17.275l9.029,0.246 c7.352,0.201,14.54,0.268,21.464,0.268c4.971,0,9.804-0.036,14.447-0.082l9.182-0.089l1.703-15.316h7.937l1.692,15.211l9.413-0.134 c12.7-0.171,22.972-2.191,31.327-5.321c6.268-2.347,11.421-5.314,15.628-8.369c6.298-4.576,10.531-9.353,13.363-12.163 c1.152-1.17,2.031-1.93,2.5-2.273c8.228,0,26.223,0,37.338,0c0.063,0.343,0.112,0.678,0.182,1.029 c0.649,3.055,1.621,6.215,3.13,9.346c1.132,2.34,2.567,4.665,4.394,6.841c2.724,3.25,6.364,6.126,10.739,8.049 c4.364,1.938,9.365,2.899,14.79,2.892c3.204,0,8.086,0,14.06,0h9.29l1.677-15.106h7.937l1.677,15.106h9.286 c10.612,0,21.817,0,32.363,0h9.278l1.68-15.106h7.941l1.681,15.106h9.278c6.335,0,11.2... [truncated]
            <a href="index.php" class="logo_text">Footshop</a>
        </div>
    </header>

    <main class="auth_main">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="sekcia_formulara auth_form">
            <h2>Prihlásenie</h2>
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <input type="text" name="username" placeholder="Meno" required value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <input type="password" name="password" placeholder="Heslo" required>
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            
            <button type="submit" class="pridat auth_btn">Prihlásiť sa</button>
            
            <p class="auth_link">Nemáš účet? <a href="register.php">Zaregistruj sa</a></p>
        </form>
    </main>

    <footer>
        <p class="footer_txt">©Footshop 2025</p>
        <p class="footer_txt">Stránku vytvoril Patrik Stančo</p>
    </footer>
</body>
</html>