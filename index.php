<?php
session_start();
require_once "config.php";

// These are still needed for the form to remember the state, but they can be empty.
$selected_categories = isset($_GET['category']) ? $_GET['category'] : [];
$selected_sizes = isset($_GET['size']) ? $_GET['size'] : [];
$selected_colors = isset($_GET['color']) ? $_GET['color'] : [];

?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footshop - Domov</title>
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

    <div class="main_container">
        <aside class="filter_sidebar">
            <form action="index.php" method="GET">
                <div class="filter_group">
                    <p class="filter_title">Kategória</p>
                    <?php 
                        $categories = ['Tenisky', 'Zimná obuv', 'Šľapky'];
                        foreach ($categories as $cat) {
                            $checked = in_array($cat, $selected_categories) ? 'checked' : '';
                            echo "<label class='filter_chip'><input type='checkbox' name='category[]' value='$cat' $checked><span>$cat</span></label>";
                        }
                    ?>
                </div>

                <div class="filter_group">
                    <p class="filter_title">Veľkosť</p>
                    <div class="filter_sizes">
                        <?php 
                        for ($i=38; $i <= 45; $i++) {
                            $checked = in_array($i, $selected_sizes) ? 'checked' : '';
                             echo "<label class='filter_chip'><input type='checkbox' name='size[]' value='$i' $checked><span>$i</span></label>";
                        }
                        ?>
                    </div>
                </div>

                <div class="filter_group">
                    <p class="filter_title">Farba</p>
                    <?php 
                        $colors = ['Čierna', 'Biela', 'Oranžová'];
                        foreach ($colors as $col) {
                            $checked = in_array($col, $selected_colors) ? 'checked' : '';
                            echo "<label class='filter_chip'><input type='checkbox' name='color[]' value='$col' $checked><span>$col</span></label>";
                        }
                    ?>
                </div>
                
                <?php if(!empty($selected_categories) || !empty($selected_sizes) || !empty($selected_colors)): ?>
                    <a href="index.php" class="clear_filters">Vymazať filtre</a>
                <?php endif; ?>

                <button type="submit" class="filter_button">Filtrovať</button>
            </form>
        </aside>

        <main class="content_area">
            <section id="all_products" class="category_section">
                <h2 class="section_header">Všetky produkty</h2>
                <div class="gallery">
                    <?php
                    // Build the query dynamically based on filters
                    $where_clauses = [];
                    $params = [];
                    $param_types = '';

                    if (!empty($selected_categories)) {
                        $cat_placeholders = implode(',', array_fill(0, count($selected_categories), '?'));
                        $where_clauses[] = "category IN ($cat_placeholders)";
                        $param_types .= str_repeat('s', count($selected_categories));
                        $params = array_merge($params, $selected_categories);
                    }
                    if (!empty($selected_sizes)) {
                        $size_conditions = [];
                        foreach ($selected_sizes as $size) {
                            $size_conditions[] = "FIND_IN_SET(?, size)";
                            $param_types .= 's';
                            $params[] = $size;
                        }
                        $where_clauses[] = "(" . implode(' OR ', $size_conditions) . ")";
                    }
                    if (!empty($selected_colors)) {
                        $col_placeholders = implode(',', array_fill(0, count($selected_colors), '?'));
                        $where_clauses[] = "color IN ($col_placeholders)";
                        $param_types .= str_repeat('s', count($selected_colors));
                        $params = array_merge($params, $selected_colors);
                    }

                    $sql = "SELECT * FROM products";
                    if (!empty($where_clauses)) {
                        $sql .= " WHERE " . implode(' AND ', $where_clauses);
                    }

                    if($stmt = mysqli_prepare($link, $sql)){
                        if (!empty($params)) {
                            mysqli_stmt_bind_param($stmt, $param_types, ...$params);
                        }
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);

                        if(mysqli_num_rows($result) > 0){
                            while($row = mysqli_fetch_array($result)){
                                echo '<div class="item">';
                                echo '<a href="produkt.php?id='. $row['id'] .'" class="item_content">';
                                echo '<img class="showpic" src="'. $row['image_url'] .'" alt="'. $row['name'] .'">';
                                echo '<h2>'. $row['name'] .'</h2>';
                                echo '<p class="price">'. $row['price'] .' €</p>';
                                echo '</a>';
                                echo '</div>';
                            }
                        } else {
                            echo "<p>Nenašli sa žiadne produkty zodpovedajúce vášmu výberu.</p>";
                        }
                        mysqli_stmt_close($stmt);
                    }
                    mysqli_close($link);
                    ?>
                </div>
            </section>
        </main>
    </div>

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
