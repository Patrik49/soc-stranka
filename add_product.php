<?php
// Initialize the session
session_start();
 
// Check if the user is logged in and is an admin
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] != 1){
    header("location: login.php");
    exit;
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$name = $price = $description = $image_url = $category = $color = $size = "";
$name_err = $price_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    if(empty(trim($_POST["name"]))){
        $name_err = "Please enter a name.";
    } else{
        $name = trim($_POST["name"]);
    }
    
    // Validate price
    if(empty(trim($_POST["price"]))){
        $price_err = "Please enter a price.";
    } else{
        $price = trim($_POST["price"]);
    }
    
    $description = trim($_POST["description"]);
    $image_url = trim($_POST["image_url"]);
    $category = trim($_POST["category"]);
    $color = trim($_POST["color"]);
    $size = trim($_POST["size"]);
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($price_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO products (name, price, description, image_url, category, color, size) VALUES (?, ?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sdsssss", $param_name, $param_price, $param_description, $param_image_url, $param_category, $param_color, $param_size);
            
            // Set parameters
            $param_name = $name;
            $param_price = $price;
            $param_description = $description;
            $param_image_url = $image_url;
            $param_category = $category;
            $param_color = $color;
            $param_size = $size;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to manage products page
                header("location: manage_products.php");
            } else{
                echo "Something went wrong. Please try again later.";
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
    <title>Add Product</title>
    <link rel="stylesheet" href="css/style_index.css">
    <link rel="stylesheet" href="css/style_auth.css">
    <link rel="stylesheet" href="css/style_admin.css">
</head>
<body>
    <header>
        <div class="logo_wrapper">
            <a href="index.php"><svg class="icon" viewBox="0 0 512 512" fill="#000000"><g><path class="st0" d="M503.817,197.695c-2.064-9.755-4.14-18.334-5.731-25.465c-1.602-7.103-2.668-12.864-2.862-16.389 c-0.838-14.137-4.21-26.33-9.476-36.533c-3.942-7.646-8.932-14.152-14.547-19.526c-8.421-8.063-18.203-13.578-28.048-17.074 c-9.864-3.488-19.813-4.993-28.961-4.993c-8.548,0-41.347,0.194-72.089,0.388c-30.731,0.194-59.346,0.38-59.346,0.38h0.067 c-12.248,0.015-24.053,4.344-33.056,12.319c-4.49,3.987-8.262,8.921-10.892,14.614c-2.627,5.686-4.087,12.14-4.084,18.974 c0,0.559,0.015,1.133,0.034,1.707c0.04,1.147,0.052,2.31,0.052,3.465c0,13.138-2.56,25.525-7.028,37.174 c-6.689,17.461-17.737,33.291-30.716,46.839c-12.967,13.549-27.836,24.78-41.765,33.075c-21.601,12.878-39.804,20.122-54.4,24.176 c-14.606,4.047-25.604,4.904-32.966,4.904c-2.373,0-4.368-0.089-6.022-0.194c-1.661-0.112-2.94-0.246-4.084-0.35l0.018,0.008 c-1.632-0.15-3.246-0.224-4.841-0.224c-8.269-0.015-16.034,2.012-22.738,5.634c-5.038,2.705-9.483,6.275-13.273,10.419 c-5.682,6.23-9.923,13.743-12.767,21.925C1.454,321.14,0,330.023,0,339.145c0,9.934,1.729,20.167,5.404,30.056 c3.67,9.882,9.297,19.422,17.062,27.828c7.2,7.796,16.12,13.772,25.935,18.497l11.544,5.545l4.316-17.715l7.873,1.014 l-0.354,19.966l8.168,1.908c12.737,2.966,26.155,4.852,39.641,6.06l10.106,0.894l1.855-16.702h7.937l1.922,17.275l9.029,0.246 c7.352,0.201,14.54,0.268,21.464,0.268c4.971,0,9.804-0.036,14.447-0.082l9.182-0.089l1.703-15.316h7.937l1.692,15.211l9.413-0.134 c12.7-0.171,22.972-2.191,31.327-5.321c6.268-2.347,11.421-5.314,15.628-8.369c6.298-4.576,10.531-9.353,13.363-12.163 c1.152-1.17,2.031-1.93,2.5-2.273c8.228,0,26.223,0,37.338,0c0.063,0.343,0.112,0.678,0.182,1.029 c0.649,3.055,1.621,6.215,3.13,9.346c1.132,2.34,2.567,4.665,4.394,6.841c2.724,3.25,6.364,6.126,10.739,8.049 c4.364,1.938,9.365,2.899,14.79,2.892c3.204,0,8.086,0,14.06,0h9.29l1.677-15.106h7.937l1.677,15.106h9.286 c10.612,0,21.817,0,32.363,0h9.278l1.68-15.106h7.941l1.681,15.106h9.278c6.335,0,11.29... [truncated]
            </a>
            <a href="index.php" class="logo_text">Footshop</a>
        </div>
        <div class="header_login">
            <a href="admin.php" class="nav_item">Admin Dashboard</a>
            <a href="logout.php" class="nav_item">Odhlásiť sa</a>
        </div>
    </header>
    <main>
        <div class="admin-container">
            <h2>Pridať produkt</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="admin-form">
                <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                    <input type="text" name="name" placeholder="Názov" required value="<?php echo $name; ?>">
                    <span class="help-block"><?php echo $name_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($price_err)) ? 'has-error' : ''; ?>">
                    <input type="text" name="price" placeholder="Cena" required value="<?php echo $price; ?>">
                    <span class="help-block"><?php echo $price_err; ?></span>
                </div>
                <div class="form-group">
                    <textarea name="description" placeholder="Popis"><?php echo $description; ?></textarea>
                </div>
                <div class="form-group">
                    <input type="text" name="image_url" placeholder="URL obrázka" value="<?php echo $image_url; ?>">
                </div>
                <div class="form-group">
                    <input type="text" name="category" placeholder="Kategória" value="<?php echo $category; ?>">
                </div>
                <div class="form-group">
                    <input type="text" name="color" placeholder="Farba" value="<?php echo $color; ?>">
                </div>
                <div class="form-group">
                    <input type="text" name="size" placeholder="Veľkosť (oddelené čiarkou)" value="<?php echo $size; ?>">
                </div>
                <button type="submit" class="admin-btn">Pridať produkt</button>
                <a href="manage_products.php" class="admin-btn">Zrušiť</a>
            </form>
        </div>
    </main>
</body>
</html>
