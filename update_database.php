<?php
require_once 'config.php';

$error_msgs = [];

// Check if 'is_admin' column exists in 'users' table
$result = mysqli_query($link, "SHOW COLUMNS FROM `users` LIKE 'is_admin'");
if (mysqli_num_rows($result) == 0) {
    $sql_users = "ALTER TABLE users ADD COLUMN is_admin TINYINT(1) NOT NULL DEFAULT 0";
    if (!mysqli_query($link, $sql_users)) {
        $error_msgs[] = "Error altering users table: " . mysqli_error($link);
    }
}

// Check if 'category' column exists in 'products' table
$result = mysqli_query($link, "SHOW COLUMNS FROM `products` LIKE 'category'");
if (mysqli_num_rows($result) == 0) {
    $sql_products_category = "ALTER TABLE products ADD COLUMN category VARCHAR(50) DEFAULT NULL";
    if (!mysqli_query($link, $sql_products_category)) {
        $error_msgs[] = "Error adding category column to products table: " . mysqli_error($link);
    }
}

// Check if 'color' column exists in 'products' table
$result = mysqli_query($link, "SHOW COLUMNS FROM `products` LIKE 'color'");
if (mysqli_num_rows($result) == 0) {
    $sql_products_color = "ALTER TABLE products ADD COLUMN color VARCHAR(50) DEFAULT NULL";
    if (!mysqli_query($link, $sql_products_color)) {
        $error_msgs[] = "Error adding color column to products table: " . mysqli_error($link);
    }
}

// Check if 'size' column exists in 'products' table
$result = mysqli_query($link, "SHOW COLUMNS FROM `products` LIKE 'size'");
if (mysqli_num_rows($result) == 0) {
    $sql_products_size = "ALTER TABLE products ADD COLUMN size VARCHAR(50) DEFAULT NULL";
    if (!mysqli_query($link, $sql_products_size)) {
        $error_msgs[] = "Error adding size column to products table: " . mysqli_error($link);
    }
}

if (empty($error_msgs)) {
    echo "Database schema updated successfully (or was already up-to-date).";
} else {
    echo "There were errors updating the database schema: <br>" . implode("<br>", $error_msgs);
}

mysqli_close($link);
?>
