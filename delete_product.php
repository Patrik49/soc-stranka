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
 
// Check if id parameter is provided
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    $product_id = trim($_GET["id"]);

    // Start transaction
    mysqli_begin_transaction($link);

    try {
        // Delete from cart
        $sql_cart = "DELETE FROM cart WHERE product_id = ?";
        if($stmt_cart = mysqli_prepare($link, $sql_cart)){
            mysqli_stmt_bind_param($stmt_cart, "i", $product_id);
            mysqli_stmt_execute($stmt_cart);
            mysqli_stmt_close($stmt_cart);
        }

        // Delete from order_items
        $sql_order_items = "DELETE FROM order_items WHERE product_id = ?";
        if($stmt_order_items = mysqli_prepare($link, $sql_order_items)){
            mysqli_stmt_bind_param($stmt_order_items, "i", $product_id);
            mysqli_stmt_execute($stmt_order_items);
            mysqli_stmt_close($stmt_order_items);
        }

        // Prepare a delete statement for the product
        $sql_product = "DELETE FROM products WHERE id = ?";
        if($stmt_product = mysqli_prepare($link, $sql_product)){
            mysqli_stmt_bind_param($stmt_product, "i", $product_id);
            mysqli_stmt_execute($stmt_product);
            mysqli_stmt_close($stmt_product);
        }

        // Commit transaction
        mysqli_commit($link);

        // Redirect to landing page
        header("location: manage_products.php");
        exit();

    } catch (mysqli_sql_exception $exception) {
        mysqli_rollback($link);
        echo "Oops! Something went wrong. Please try again later.";
        // You might want to log the error for debugging
        // error_log($exception->getMessage());
    }
    
    // Close connection
    mysqli_close($link);
} else{
    // URL doesn't contain id parameter. Redirect to error page or manage_products
    header("location: manage_products.php");
    exit();
}
?>
