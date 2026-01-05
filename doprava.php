<?php
// Initialize the session
session_start();
require_once "config.php";

// If the user is not logged in, redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

$order_placed_message = "";

// Fetch cart items for the user to calculate total price
$cart_items = [];
$total_price = 0;
$sql_cart = "SELECT c.id, p.name, p.price, c.quantity, p.image_url, c.product_id FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?";
if($stmt_cart = mysqli_prepare($link, $sql_cart)){
    mysqli_stmt_bind_param($stmt_cart, "i", $_SESSION['id']);
    if(mysqli_stmt_execute($stmt_cart)){
        $result_cart = mysqli_stmt_get_result($stmt_cart);
        while($row = mysqli_fetch_array($result_cart, MYSQLI_ASSOC)){
            $cart_items[] = $row;
            $total_price += $row['price'] * $row['quantity'];
        }
    }
    mysqli_stmt_close($stmt_cart);
}


// Handle order submission
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['place_order'])){
    if(empty($cart_items)){
        $order_placed_message = "Váš košík je prázdny.";
    } else {
        // Insert into orders table
        $sql_order = "INSERT INTO orders (user_id, total_price) VALUES (?, ?)";
        if($stmt_order = mysqli_prepare($link, $sql_order)){
            mysqli_stmt_bind_param($stmt_order, "id", $_SESSION['id'], $total_price);
            if(mysqli_stmt_execute($stmt_order)){
                $order_id = mysqli_insert_id($link);

                // Insert into order_items table
                $sql_items = "INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)";
                if($stmt_items = mysqli_prepare($link, $sql_items)){
                    foreach($cart_items as $item){
                        mysqli_stmt_bind_param($stmt_items, "iii", $order_id, $item['product_id'], $item['quantity']);
                        mysqli_stmt_execute($stmt_items);
                    }
                    mysqli_stmt_close($stmt_items);

                    // Clear the cart
                    $sql_clear_cart = "DELETE FROM cart WHERE user_id = ?";
                    if($stmt_clear = mysqli_prepare($link, $sql_clear_cart)){
                        mysqli_stmt_bind_param($stmt_clear, "i", $_SESSION['id']);
                        mysqli_stmt_execute($stmt_clear);
                        mysqli_stmt_close($stmt_clear);
                    }
                    
                    $order_placed_message = "Vaša objednávka bola úspešne odoslaná. Číslo objednávky: " . $order_id;
                }
            }
            mysqli_stmt_close($stmt_order);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footshop - Doprava a platba</title>
    <link rel="stylesheet" href="css/style_index.css">
    <link rel="stylesheet" href="css/style_kosik.css">
    <link rel="stylesheet" href="css/style_doprava.css">
</head>
<body>
    <header>
        <div class="logo_wrapper">
            <a href="index.php"><svg class="icon" viewBox="0 0 512 512" fill="#000000"><g><path class="st0" d="M503.817,197.695c-2.064-9.755-4.14-18.334-5.731-25.465c-1.602-7.103-2.668-12.864-2.862-16.389 c-0.838-14.137-4.21-26.33-9.476-36.533c-3.942-7.646-8.932-14.152-14.547-19.526c-8.421-8.063-18.203-13.578-28.048-17.074 c-9.864-3.488-19.813-4.993-28.961-4.993c-8.548,0-41.347,0.194-72.089,0.388c-30.731,0.194-59.346,0.38-59.346,0.38h0.067 c-12.248,0.015-24.053,4.344-33.056,12.319c-4.49,3.987-8.262,8.921-10.892,14.614c-2.627,5.686-4.087,12.14-4.084,18.974 c0,0.559,0.015,1.133,0.034,1.707c0.04,1.147,0.052,2.31,0.052,3.465c0,13.138-2.56,25.525-7.028,37.174 c-6.689,17.461-17.737,33.291-30.716,46.839c-12.967,13.549-27.836,24.78-41.765,33.075c-21.601,12.878-39.804,20.122-54.4,24.176 c-14.606,4.047-25.604,4.904-32.966,4.904c-2.373,0-4.368-0.089-6.022-0.194c-1.661-0.112-2.94-0.246-4.084-0.35l0.018,0.008 c-1.632-0.15-3.246-0.224-4.841-0.224c-8.269-0.015-16.034,2.012-22.738,5.634c-5.038,2.705-9.483,6.275-13.273,10.419 c-5.682,6.23-9.923,13.743-12.767,21.925C1.454,321.14,0,330.023,0,339.145c0,9.934,1.729,20.167,5.404,30.056 c3.67,9.882,9.297,19.422,17.062,27.828c7.2,7.796,16.12,13.772,25.935,18.497l11.544,5.545l4.316-17.715l7.873,1.014 l-0.354,19.966l8.168,1.908c12.737,2.966,26.155,4.852,39.641,6.06l10.106,0.894l1.855-16.702h7.937l1.922,17.275l9.029,0.246 c7.352,0.201,14.54,0.268,21.464,0.268c4.971,0,9.804-0.036,14.447-0.082l9.182-0.089l1.703-15.316h7.937l1.692,15.211l9.413-0.134 c12.7-0.171,22.972-2.191,31.327-5.321c6.268-2.347,11.421-5.314,15.628-8.369c6.298-4.576,10.531-9.353,13.363-12.163 c1.152-1.17,2.031-1.93,2.5-2.273c8.228,0,26.223,0,37.338,0c0.063,0.343,0.112,0.678,0.182,1.029 c0.649,3.055,1.621,6.215,3.13,9.346c1.132,2.34,2.567,4.665,4.394,6.841c2.724,3.25,6.364,6.126,10.739,8.049 c4.364,1.938,9.365,2.899,14.79,2.892c3.204,0,8.086,0,14.06,0h9.29l1.677-15.106h7.937l1.677,15.106h9.286 c10.612,0,21.817,0,32.363,0h9.278l1.68-15.106h7.941l1.681,15.106h9.278c6.335,0,11.294,0,14.078,0 c4.744,0,9.42-0.543,13.914-1.96c3.365-1.065,6.641-2.646,9.584-4.837c4.445-3.264,7.997-7.929,10.184-13.348 c2.206-5.433,3.186-11.559,3.193-18.386c0-10.374,0-28.812,0-44.649c0-7.922,0-15.204,0-20.502c0-3.63,0-6.29,0-7.624 c3.76-18.855,5.232-36.75,5.232-53.369C512,241.479,507.942,217.213,503.817,197.695z M486.236,319.553l-0.212,1.028v1.043 c0,0,0,3.309,0,8.601c0,2.534,0,5.559,0,8.854H87.841c0,0,0-20.8,0-44.217c5.612-0.872,11.824-2.146,18.669-4.054 c10.21-2.825,21.751-6.984,34.536-13.02l55.671,32.24l10.396-17.953l-44.388-25.689c1.084-0.633,2.169-1.267,3.268-1.923 c8.191-4.889,16.63-10.687,24.914-17.282l53.819,31.152l10.393-17.953l-47.82-27.687c6.555-6.402,12.841-13.31,18.58-20.83 c0.98-1.281,1.934-2.593,2.884-3.912l50.917,29.49l10.392-17.953l-50.305-29.132c2.791-5.195,5.306-10.598,7.453-16.187 c3.48-9.062,5.995-18.662,7.326-28.678H358.61c10.824,0,19.6,8.779,19.6,19.6v24.176c0,21.642,17.551,39.193,39.198,39.193h70.856 c1.777,12.513,2.992,26.152,2.992,40.773C491.255,284.958,489.858,301.785,486.236,319.553z"></path></g></svg>
            </a>
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
        <h1 class="kosik_text">Platba a doprava</h1>
        
        <?php if(!empty($order_placed_message)): ?>
            <div class="alert alert-success"><?php echo $order_placed_message; ?></div>
        <?php else: ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form_wrapper">
                <div class="sekcia_formulara">
                    <h2>Doručovacie údaje</h2>
                    <div class="input_row">
                        <input type="text" name="meno" placeholder="Meno" required>
                        <input type="text" name="priezvisko" placeholder="Priezvisko" required>
                    </div>
                    <input type="text" name="ulica" placeholder="Ulica a číslo domu" required>
                    <div class="input_row">
                        <input type="text" name="mesto" placeholder="Mesto" required>
                        <input type="text" name="psc" placeholder="PSČ" required>
                    </div>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="tel" name="tel" placeholder="Telefónne číslo" required>
                </div>

                <div class="sekcia_formulara">
                    <h2>Spôsob platby</h2>
                    <label class="moznost_platby">
                        <input type="radio" name="platba" value="karta" checked>
                        <span>Platba kartou online</span>
                    </label>
                    <label class="moznost_platby">
                        <input type="radio" name="platba" value="dobierka">
                        <span>Dobierka (+1€)</span>
                    </label>
                    <label class="moznost_platby">
                        <input type="radio" name="platba" value="prevod">
                        <span>Bankový prevod</span>
                    </label>
                </div>

                <div class="pokracovat_wrapper">
                    <button type="submit" name="place_order" class="pokracovat">
                        <p class="pokracovat_txt">Odoslať objednávku</p>
                        <svg class="sipka" viewBox="9 6 8 12" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path class="sipka_path" d="M10 7L15 12L10 17" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </main>

    <footer>
        <p class="footer_txt">©Footshop 2025</p>
        <p class="footer_txt">Stránku vytvoril Patrik Stančo</p>
    </footer>
    
</body>
</html>
