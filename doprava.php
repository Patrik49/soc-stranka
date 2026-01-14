<?php
// Initialize the session
session_start();
require_once "config.php";

$order_placed_message = "";
$order_id_for_display = null;

// Fetch cart items
$cart_items = [];
$total_price = 0;

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
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
} else {
    // Guest cart fetch
    if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])){
        $ids = array_keys($_SESSION['cart']);
        $ids_safe = array_map('intval', $ids);
        $ids_string = implode(',', $ids_safe);
        
        if(!empty($ids_string)){
            $sql = "SELECT id, name, price, image_url FROM products WHERE id IN ($ids_string)";
            $result = mysqli_query($link, $sql);
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                $p_id = $row['id'];
                $qty = $_SESSION['cart'][$p_id];
                $row['quantity'] = $qty;
                $row['product_id'] = $p_id;
                $cart_items[] = $row;
                $total_price += $row['price'] * $qty;
            }
        }
    }
}


// Handle order submission
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['place_order'])){
    if(empty($cart_items)){
        $order_placed_message = "Váš košík je prázdny.";
    } else {
        // Insert into orders table
        $sql_order = "INSERT INTO orders (user_id, total_price) VALUES (?, ?)";
        
        if($stmt_order = mysqli_prepare($link, $sql_order)){
            $user_id_param = null;
            if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
                $user_id_param = $_SESSION['id'];
            }
            
            // Bind parameters. 'i' allows null if the column is nullable.
            mysqli_stmt_bind_param($stmt_order, "id", $user_id_param, $total_price);
            
            if(mysqli_stmt_execute($stmt_order)){
                $order_id = mysqli_insert_id($link);
                $order_id_for_display = $order_id;

                // Insert into order_items table
                $sql_items = "INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)";
                if($stmt_items = mysqli_prepare($link, $sql_items)){
                    foreach($cart_items as $item){
                        mysqli_stmt_bind_param($stmt_items, "iii", $order_id, $item['product_id'], $item['quantity']);
                        mysqli_stmt_execute($stmt_items);
                    }
                    mysqli_stmt_close($stmt_items);

                    // Clear the cart
                    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
                        $sql_clear_cart = "DELETE FROM cart WHERE user_id = ?";
                        if($stmt_clear = mysqli_prepare($link, $sql_clear_cart)){
                            mysqli_stmt_bind_param($stmt_clear, "i", $_SESSION['id']);
                            mysqli_stmt_execute($stmt_clear);
                            mysqli_stmt_close($stmt_clear);
                        }
                    } else {
                        // Clear session cart
                        unset($_SESSION['cart']);
                    }
                    
                    $order_placed_message = "success";
                }
            } else {
                 $order_placed_message = "Chyba pri vytváraní objednávky. Skúste sa prihlásiť.";
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
            </a>
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
        <?php if($order_placed_message == 'success'): ?>
            <div class="success_container">
                <div class="success_icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </div>
                <h2 class="success_title">Objednávka prijatá!</h2>
                <p class="order_number">Číslo objednávky: <br><span>#<?php echo $order_id_for_display; ?></span></p>
                <p style="color: #888; margin-bottom: 2rem;">Ďakujeme za váš nákup. Potvrdenie vám bolo zaslané na email.</p>
                <a href="index.php" class="btn_back">Pokračovať v nákupe</a>
            </div>
        <?php else: ?>
            <?php if(empty($cart_items) && empty($order_placed_message)): ?>
                <div class="empty_cart" style="text-align: center; padding: 3rem;">
                    <p style="font-size: 1.2rem; margin-bottom: 1.5rem;">Váš košík je prázdny.</p>
                    <a href="index.php" class="btn_back" style="display: inline-block; padding: 0.8rem 2rem; background: white; color: black; border-radius: 2rem; font-weight: bold;">Späť do obchodu</a>
                </div>
            <?php else: ?>
                <h1 class="page_title">Doprava a platba</h1>
                
                <?php if(!empty($order_placed_message)): ?>
                    <div class="alert alert-danger"><?php echo $order_placed_message; ?></div>
                <?php endif; ?>

                <div class="checkout_container">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        
                        <!-- Delivery Details -->
                        <div class="form_section">
                            <h2>Doručovacie údaje</h2>
                            <div class="input_group">
                                <input type="text" name="meno" class="form_input" placeholder="Meno" required>
                                <input type="text" name="priezvisko" class="form_input" placeholder="Priezvisko" required>
                                
                                <input type="text" name="ulica" class="form_input full_width" placeholder="Ulica a číslo domu" required>
                                
                                <input type="text" name="mesto" class="form_input" placeholder="Mesto" required>
                                <input type="text" name="psc" class="form_input" placeholder="PSČ" required>
                                
                                <input type="email" name="email" class="form_input full_width" placeholder="Email" required>
                                <input type="tel" name="tel" class="form_input full_width" placeholder="Telefónne číslo" required>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="form_section">
                            <h2>Spôsob platby</h2>
                            <div class="payment_options">
                                <label class="payment_option">
                                    <input type="radio" name="platba" value="karta" checked>
                                    <span>Platba kartou online</span>
                                </label>
                                <label class="payment_option">
                                    <input type="radio" name="platba" value="dobierka">
                                    <span>Dobierka (+1€)</span>
                                </label>
                                <label class="payment_option">
                                    <input type="radio" name="platba" value="prevod">
                                    <span>Bankový prevod</span>
                                </label>
                            </div>
                        </div>

                        <button type="submit" name="place_order" class="submit_btn">Odoslať objednávku</button>
                    </form>
                </div>
            <?php endif; ?>
        <?php endif; ?>
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
    
</body>
</html>
