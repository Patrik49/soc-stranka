<?php
require_once "config.php";

// --- Filter Logic ---
$where_clauses = [];
$selected_categories = isset($_GET['category']) ? $_GET['category'] : [];
$selected_sizes = isset($_GET['size']) ? $_GET['size'] : [];
$selected_colors = isset($_GET['color']) ? $_GET['color'] : [];

if (!empty($selected_categories)) {
    $cat_placeholders = implode(',', array_fill(0, count($selected_categories), '?'));
    $where_clauses[] = "category IN ($cat_placeholders)";
}
if (!empty($selected_sizes)) {
    $size_conditions = [];
    foreach ($selected_sizes as $size) {
        $size_conditions[] = "FIND_IN_SET(?, size)";
    }
    $where_clauses[] = "(" . implode(' OR ', $size_conditions) . ")";
}
if (!empty($selected_colors)) {
    $col_placeholders = implode(',', array_fill(0, count($selected_colors), '?'));
    $where_clauses[] = "color IN ($col_placeholders)";
}

$sql = "SELECT * FROM products";
if (!empty($where_clauses)) {
    $sql .= " WHERE " . implode(' AND ', $where_clauses);
}

$stmt = mysqli_prepare($link, $sql);

if ($stmt) {
    $param_types = '';
    $params = [];
    if (!empty($selected_categories)) {
        $param_types .= str_repeat('s', count($selected_categories));
        $params = array_merge($params, $selected_categories);
    }
    if (!empty($selected_sizes)) {
        $param_types .= str_repeat('s', count($selected_sizes));
        $params = array_merge($params, $selected_sizes);
    }
    if (!empty($selected_colors)) {
        $param_types .= str_repeat('s', count($selected_colors));
        $params = array_merge($params, $selected_colors);
    }
    
    if (!empty($param_types)) {
        mysqli_stmt_bind_param($stmt, $param_types, ...$params);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    $result = false;
}

// --- Display Products ---
if($result && mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_array($result)){
        echo '<div class="item">';
        echo '<a href="produkt.php?id='. $row['id'] .'" class="item_content">';
        echo '<img class="showpic" src="'. $row['image_url'] .'" alt="'. $row['name'] .'">';
        echo '<h2>'. $row['name'] .'</h2>';
        echo '<p class="price">'. $row['price'] .' €</p>';
        echo '</a>';
        echo '</div>';
    }
    mysqli_free_result($result);
} else{
    echo "<p>Nenašli sa žiadne produkty zodpovedajúce vášmu výberu.</p>";
}
mysqli_close($link);
?>