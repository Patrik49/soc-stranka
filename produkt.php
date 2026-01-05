<?php
// Initialize the session
session_start();
require_once "config.php";

$add_to_cart_msg = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])){
    
    // Check if user is logged in
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: login.php");
        exit;
    }
    
    $product_id = trim($_GET["id"]);
    $quantity = trim($_POST["quantity"]);
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
            <a href="index.php"><svg class="icon" viewBox="0 0 512 512" fill="#000000"><g><path class="st0" d="M503.817,197.695c-2.064-9.755-4.14-18.334-5.731-25.465c-1.602-7.103-2.668-12.864-2.862-16.389 c-0.838-14.137-4.21-26.33-9.476-36.533c-3.942-7.646-8.932-14.152-14.547-19.526c-8.421-8.063-18.203-13.578-28.048-17.074 c-9.864-3.488-19.813-4.993-28.961-4.993c-8.548,0-41.347,0.194-72.089,0.388c-30.731,0.194-59.346,0.38-59.346,0.38h0.067 c-12.248,0.015-24.053,4.344-33.056,12.319c-4.49,3.987-8.262,8.921-10.892,14.614c-2.627,5.686-4.087,12.14-4.084,18.974 c0,0.559,0.015,1.133,0.034,1.707c0.04,1.147,0.052,2.31,0.052,3.465c0,13.138-2.56,25.525-7.028,37.174 c-6.689,17.461-17.737,33.291-30.716,46.839c-12.967,13.549-27.836,24.78-41.765,33.075c-21.601,12.878-39.804,20.122-54.4,24.176 c-14.606,4.047-25.604,4.904-32.966,4.904c-2.373,0-4.368-0.089-6.022-0.194c-1.661-0.112-2.94-0.246-4.084-0.35l0.018,0.008 c-1.632-0.15-3.246-0.224-4.841-0.224c-8.269-0.015-16.034,2.012-22.738,5.634c-5.038,2.705-9.483,6.275-13.273,10.419 c-5.682,6.23-9.923,13.743-12.767,21.925C1.454,321.14,0,330.023,0,339.145c0,9.934,1.729,20.167,5.404,30.056 c3.67,9.882,9.297,19.422,17.062,27.828c7.2,7.796,16.12,13.772,25.935,18.497l11.544,5.545l4.316-17.715l7.873,1.014 l-0.354,19.966l8.168,1.908c12.737,2.966,26.155,4.852,39.641,6.06l10.106,0.894l1.855-16.702h7.937l1.922,17.275l9.029,0.246 c7.352,0.201,14.54,0.268,21.464,0.268c4.971,0,9.804-0.036,14.447-0.082l9.182-0.089l1.703-15.316h7.937l1.692,15.211l9.413-0.134 c12.7-0.171,22.972-2.191,31.327-5.321c6.268-2.347,11.421-5.314,15.628-8.369c6.298-4.576,10.531-9.353,13.363-12.163 c1.152-1.17,2.031-1.93,2.5-2.273c8.228,0,26.223,0,37.338,0c0.063,0.343,0.112,0.678,0.182,1.029 c0.649,3.055,1.621,6.215,3.13,9.346c1.132,2.34,2.567,4.665,4.394,6.841c2.724,3.25,6.364,6.126,10.739,8.049 c4.364,1.938,9.365,2.899,14.79,2.892c3.204,0,8.086,0,14.06,0h9.29l1.677-15.106h7.937l1.677,15.106h9.286 c10.612,0,21.817,0,32.363,0h9.278l1.68-15.106h7.941l1.681,15.106h9.278c6.335,0,11.292,0,14.894-0.434c4.61-0.551,8.349-1.93,10.983-3.843c2.634-1.922,4.282-4.148,5.151-5.915c0.559-1.124,1.002-2.263,1.383-3.326c0.812-2.256,1.442-4.9,1.811-7.514C511.231,232.06,512,212.7,512,197.695z M353.94,206.035c-1.144,21.51-14.07,39.804-32.92,39.804c-18.842,0-32.912-18.294-31.768-39.804 c1.144-21.503,14.07-39.804,32.912-39.804C341.014,166.231,355.084,184.532,353.94,206.035z"/></g></svg></a>
            <a href="index.php" class="logo_text">Footshop</a>
        </div>
        
        <div class="header_login">
            <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
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
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?id=<?php echo trim($_GET["id"]); ?>" method="post">
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
                        
                        echo '<div class="produkt_wrapper">';
                        echo '<div class="produkt_img_wrapper">';
                        echo '<img src="'. $image_url .'" alt="'. $name .'" class="produkt_img">';
                        echo '</div>';
                        echo '<div class="produkt_info">';
                        echo '<h1 class="produkt_nadpis">'. $name .'</h1>';
                        echo '<h2 class="produkt_cena">'. $price .' €</h2>';
                        echo '<p class="produkt_popis">'. $description .'</p>';
                        echo '                <div class="vyber_sekcia">
                                                    <p class="label">Veľkosť:</p>
                                                    <div class="velkosti_wrapper">
                                                        <label class="velkost_radio">
                                                            <input type="radio" name="velkost" value="38">
                                                            <span>38</span>
                                                        </label>
                                                        <label class="velkost_radio">
                                                            <input type="radio" name="velkost" value="39">
                                                            <span>39</span>
                                                        </label>
                                                        <label class="velkost_radio">
                                                            <input type="radio" name="velkost" value="40" checked>
                                                            <span>40</span>
                                                        </label>
                                                        <label class="velkost_radio">
                                                            <input type="radio" name="velkost" value="41">
                                                            <span>41</span>
                                                        </label>
                                                        <label class="velkost_radio">
                                                            <input type="radio" name="velkost" value="42">
                                                            <span>42</span>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="vyber_sekcia">
                                                    <p class="label">Farba:</p>
                                                    <div class="farby_wrapper">
                                                        <label class="farba_radio">
                                                            <input type="radio" name="farba" value="black" checked>
                                                            <span class="farba_kruh" style="background-color: #000;"></span>
                                                        </label>
                                                        <label class="farba_radio">
                                                            <input type="radio" name="farba" value="orange">
                                                            <span class="farba_kruh" style="background-color: #ff6600;"></span>
                                                        </label>
                                                        <label class="farba_radio">
                                                            <input type="radio" name="farba" value="silver">
                                                            <span class="farba_kruh" style="background-color: #c0c0c0;"></span>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="akcia_wrapper">
                                                    <div class="pocet_wrapper">
                                                        <label for="pocet">Ks:</label>
                                                        <input type="number" name="quantity" id="pocet" value="1" min="1" max="10" class="pocet_input">
                                                    </div>
                                                    <button type="submit" name="add_to_cart" class="pridat velke_tlacidlo">Pridať do košíka</button>
                                                </div>';
                        echo '</div>';
                        echo '</div>';

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
        </form>
    </main>

    <footer>
        <p class="footer_txt">©Footshop 2025</p>
        <p class="footer_txt">Stránku vytvoril Patrik Stančo</p>
    </footer>

    <script src="script/script.js"></script>
</body>
</html>