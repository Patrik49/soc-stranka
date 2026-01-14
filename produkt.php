<?php
// Initialize the session
session_start();
require_once "config.php";

$add_to_cart_msg = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])){
    
    $product_id = trim($_GET["id"]);
    $quantity = trim($_POST["quantity"]);

    // Check if user is logged in
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
        $user_id = $_SESSION["id"];

        // Check if item is already in cart
        $sql = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "ii", $user_id, $product_id);
            
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
                if(mysqli_num_rows($result) == 1){
                    // Update quantity
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    $new_quantity = $row['quantity'] + $quantity;
                    $update_sql = "UPDATE cart SET quantity = ? WHERE id = ?";
                    if($update_stmt = mysqli_prepare($link, $update_sql)){
                        mysqli_stmt_bind_param($update_stmt, "ii", $new_quantity, $row['id']);
                        if(mysqli_stmt_execute($update_stmt)){
                            $add_to_cart_msg = "Produkt bol pridaný do košíka.";
                        } else {
                            $add_to_cart_msg = "Chyba pri aktualizácii košíka.";
                        }
                        mysqli_stmt_close($update_stmt);
                    }
                } else {
                    // Insert new item
                    $insert_sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
                    if($insert_stmt = mysqli_prepare($link, $insert_sql)){
                        mysqli_stmt_bind_param($insert_stmt, "iii", $user_id, $product_id, $quantity);
                        if(mysqli_stmt_execute($insert_stmt)){
                            $add_to_cart_msg = "Produkt bol pridaný do košíka.";
                        } else {
                            $add_to_cart_msg = "Chyba pri pridávaní do košíka.";
                        }
                        mysqli_stmt_close($insert_stmt);
                    }
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    } else {
        // Guest user - use session
        if(!isset($_SESSION['cart'])){
            $_SESSION['cart'] = [];
        }
        
        if(isset($_SESSION['cart'][$product_id])){
            $_SESSION['cart'][$product_id] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }
        $add_to_cart_msg = "Produkt bol pridaný do košíka.";
    }
}
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footshop - Produkt</title>
    <link rel="stylesheet" href="css/style_index.css">
    <link rel="stylesheet" href="css/style_kosik.css">
    <link rel="stylesheet" href="css/style_produkt.css">
</head>
<body>
    <header>
        <div class="logo_wrapper">
            <a href="index.php" class="logo_text">Footshop</a>
        </div>
        
        <div class="header_login">
            <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
                <?php if(isset($_SESSION["is_admin"]) && $_SESSION["is_admin"] == 1): ?>
                    <a href="admin.php">Admin Panel</a>
                <?php endif; ?>
                <a href="logout.php">Odhlásiť sa</a>
                <span>Vitaj, <?php echo htmlspecialchars($_SESSION["username"]); ?></span>
            <?php else: ?>
                <a href="login.php">Prihlásiť sa</a>
                <a href="register.php">Registrovať sa</a>
            <?php endif; ?>
            <a href="kosik.php" class="cart_area"><svg class="cart" viewBox="0 -0.5 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>shopping_cart_round [#1137]</title> <desc>Created with Sketch.</desc> <defs> </defs> <g class="cart" id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g class="cart" id="Dribbble-Light-Preview" transform="translate(-140.000000, -3120.000000)" fill="#000000"> <g id="icons" transform="translate(56.000000, 160.000000)"> <path d="M98.477,2976.95566 L89.541,2976.95566 C89.052,2976.95566 88.635,2976.59484 88.555,2976.10113 L87.361,2968.77831 L100.819,2968.77831 L99.46,2976.12362 C99.37,2976.60608 98.958,2976.95566 98.477,2976.95566 L98.477,2976.95566 Z M101,2966.73398 L97.473,2960.51101 C97.198,2960.02651 96.592,2959.85887 96.116,2960.1369 L96.116,2960.1369 C95.635,2960.41697 95.47,2961.04356 95.747,2961.53216 L98.69,2966.73398 L89.309,2966.73398 L92.257,2961.53625 C92.532,2961.0497 92.371,2960.42822 91.897,2960.14405 L91.888,2960.13894 C91.411,2959.85478 90.798,2960.02037 90.522,2960.50897 L87,2966.73398 L85,2966.73398 C84.447,2966.73398 84,2967.19191 84,2967.75614 C84,2968.32038 84.447,2968.77831 85,2968.77831 L85.333,2968.77831 L86.721,2977.29196 C86.882,2978.27733 87.716,2979 88.694,2979 L99.305,2979 C100.283,2979 101.118,2978.27733 101.278,2977.29196 L102.666,2968.77831 L103,2968.77831 C103.552,2968.77831 104,2968.32038 104,2967.75614 C104,2967.19191 103.552,2966.73398 L103,2966.73398 L101,2966.73398 Z" id="shopping_cart_round-[#1137]"> </path> </g> </g> </g> </g></svg></a>
        </div>
    </header>

    <main>
        <?php if(!empty($add_to_cart_msg)) echo '<div class="alert alert-success">' . $add_to_cart_msg . '</div>'; ?>
        
        <?php
        // Check if product id is provided
        if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
            // Prepare a select statement
            $sql = "SELECT * FROM products WHERE id = ?";
            
            if($stmt = mysqli_prepare($link, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "i", $param_id);
                
                // Set parameters
                $param_id = trim($_GET["id"]);
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    $result = mysqli_stmt_get_result($stmt);
            
                    if(mysqli_num_rows($result) == 1){
                        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                        
                        $name = $row["name"];
                        $price = $row["price"];
                        $description = $row["description"];
                        $image_url = $row["image_url"];
                        $category = isset($row["category"]) ? $row["category"] : 'Tenisky'; // Default if null
                        
                        // Break out of PHP to write clean HTML
                        ?>
                        
                        <div class="product_page_container">
                            <!-- Breadcrumbs -->
                            <div class="breadcrumbs">
                                <a href="index.php">Domov</a> / 
                                <a href="index.php?category[]=<?php echo htmlspecialchars($category); ?>"><?php echo htmlspecialchars($category); ?></a> / 
                                <span><?php echo htmlspecialchars($name); ?></span>
                            </div>

                            <!-- Main Product Details -->
                            <div class="product_detail_wrapper">
                                <!-- Left: Image -->
                                <div class="detail_img_wrapper">
                                    <img src="<?php echo htmlspecialchars($image_url); ?>" alt="<?php echo htmlspecialchars($name); ?>" class="detail_img">
                                </div>

                                <!-- Right: Info & Form -->
                                <div class="detail_info">
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?id=<?php echo trim($_GET["id"]); ?>" method="post">
                                        <h1 class="detail_title"><?php echo htmlspecialchars($name); ?></h1>
                                        <div class="detail_price"><?php echo htmlspecialchars($price); ?> €</div>
                                        
                                        <p class="detail_desc"><?php echo htmlspecialchars($description); ?></p>

                                        <!-- Size Selector -->
                                        <div class="selection_group">
                                            <span class="selection_label">Vyberte veľkosť</span>
                                            <div class="size_grid">
                                                <?php 
                                                $sizes = [38, 39, 40, 41, 42, 43, 44, 45];
                                                foreach($sizes as $s): ?>
                                                    <label class="size_option">
                                                        <input type="radio" name="velkost" value="<?php echo $s; ?>" <?php echo ($s == 40) ? 'checked' : ''; ?>>
                                                        <div class="size_box"><?php echo $s; ?></div>
                                                    </label>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>

                                        <!-- Color Selector -->
                                        <div class="selection_group">
                                            <span class="selection_label">Vyberte farbu</span>
                                            <div class="color_grid">
                                                <label class="color_option">
                                                    <input type="radio" name="farba" value="black" checked>
                                                    <span class="color_circle" style="background-color: #111;"></span>
                                                </label>
                                                <label class="color_option">
                                                    <input type="radio" name="farba" value="white">
                                                    <span class="color_circle" style="background-color: #fff;"></span>
                                                </label>
                                                <label class="color_option">
                                                    <input type="radio" name="farba" value="orange">
                                                    <span class="color_circle" style="background-color: #ff6600;"></span>
                                                </label>
                                                <label class="color_option">
                                                    <input type="radio" name="farba" value="blue">
                                                    <span class="color_circle" style="background-color: #1e90ff;"></span>
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Action Row -->
                                        <div class="action_row">
                                            <div class="qty_wrapper">
                                                <input type="number" name="quantity" value="1" min="1" max="10" class="qty_input">
                                            </div>
                                            <button type="submit" name="add_to_cart" class="add_btn pridat">Pridať do košíka</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Related Products Section -->
                            <div class="related_section">
                                <h3 class="related_title">Podobné produkty</h3>
                                <div class="gallery">
                                    <?php
                                    // Fetch 4 related products from the same category, excluding current one
                                    $related_sql = "SELECT * FROM products WHERE category = ? AND id != ? LIMIT 4";
                                    if($stmt_rel = mysqli_prepare($link, $related_sql)){
                                        mysqli_stmt_bind_param($stmt_rel, "si", $category, $param_id);
                                        mysqli_stmt_execute($stmt_rel);
                                        $res_rel = mysqli_stmt_get_result($stmt_rel);
                                        while($rel_row = mysqli_fetch_array($res_rel)){
                                            echo '<div class="item">';
                                            echo '<a href="produkt.php?id='. $rel_row['id'] .'" class="item_content">';
                                            echo '<img class="showpic" src="'. $rel_row['image_url'] .'" alt="'. $rel_row['name'] .'">';
                                            echo '<h2>'. $rel_row['name'] .'</h2>';
                                            echo '<p class="price">'. $rel_row['price'] .' €</p>';
                                            echo '</a>';
                                            echo '</div>';
                                        }
                                        mysqli_stmt_close($stmt_rel);
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <?php

                    } else{
                        echo "Product not found.";
                    }
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
            }
            // mysqli_stmt_close($stmt); // This was the issue
        } else{
            echo "No product ID specified.";
        }
        ?>
    </main>

    <footer>
        <div class="footer_container">
            <div class="footer_col">
                <h3>Footshop</h3>
                <p>Tvoj cieľ pre najnovšie tenisky a streetwear. Kvalita, štýl a originalita na jednom mieste.</p>
            </div>
            <div class="footer_col">
                <h3>Nákup</h3>
                <ul class="footer_links">
                    <li><a href="index.php">Domov</a></li>
                    <li><a href="index.php">Všetky produkty</a></li>
                    <li><a href="kosik.php">Košík</a></li>
                </ul>
            </div>
            <div class="footer_col">
                <h3>Kontakt</h3>
                <p>Email: info@footshop.sk<br>Tel: +421 900 000 000</p>
                <p>Po-Pi: 9:00 - 17:00</p>
            </div>
        </div>
        <div class="footer_bottom">
            <p>© 2025 Footshop. Všetky práva vyhradené.</p>
            <p>Dizajn a kód: Patrik Stančo</p>
        </div>
    </footer>

    <script src="script/script.js"></script>
</body>
</html>