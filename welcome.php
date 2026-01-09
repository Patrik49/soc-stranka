<?php
session_start();
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footshop - Vitajte</title>
    <link rel="stylesheet" href="css/style_index.css">
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

    <!-- Hero / Welcome Section -->
    <section class="hero_section">
        <div class="hero_bg"></div>
        <div class="hero_content">
            <h1>Tvoj štýl<br>Na prvom mieste</h1>
            <p>Objav najnovšie trendy a exkluzívne kúsky, ktoré ťa odlíšia od davu.</p>
            <a href="index.php" class="hero_btn">Nakupovať</a>
        </div>
        <div class="scroll_indicator">
            <span></span>
        </div>
    </section>

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