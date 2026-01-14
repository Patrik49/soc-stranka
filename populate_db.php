<?php
// Include config file
require_once "config.php";

// 1. Temporarily disable foreign key checks to allow truncation
mysqli_query($link, "SET FOREIGN_KEY_CHECKS=0");

// 2. Truncate tables that depend on products, and the products table itself
$tables_to_truncate = ['cart', 'order_items', 'products'];
foreach ($tables_to_truncate as $table) {
    $sql = "TRUNCATE TABLE `$table`";
    if(!mysqli_query($link, $sql)){
        mysqli_query($link, "SET FOREIGN_KEY_CHECKS=1"); // Re-enable checks on failure
        die("Error clearing table `$table`: " . mysqli_error($link));
    }
}

// 3. Re-enable foreign key checks
mysqli_query($link, "SET FOREIGN_KEY_CHECKS=1");

// A curated list of high-quality, predefined products to ensure consistency
$predefined_products = [
    // Tenisky
    ['name' => 'Nike Air Force 1 \'07', 'category' => 'Tenisky', 'image_url' => 'https://images.unsplash.com/photo-1597045566677-8cf032ed6634?q=80&w=800&auto=format&fit=crop'],
    ['name' => 'Converse Chuck 70 Classic', 'category' => 'Tenisky', 'image_url' => 'https://images.unsplash.com/photo-1607522370275-f14206abe5d3?q=80&w=800&auto=format&fit=crop'],
    ['name' => 'Vans Old Skool', 'category' => 'Tenisky', 'image_url' => 'https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?q=80&w=800&auto=format&fit=crop'],
    ['name' => 'Adidas Superstar', 'category' => 'Tenisky', 'image_url' => 'https://images.unsplash.com/photo-1570464197285-994981477f41?q=80&w=800&auto=format&fit=crop'],
    ['name' => 'Air Jordan 1 Retro High', 'category' => 'Tenisky', 'image_url' => 'https://images.unsplash.com/photo-1511556532299-8f662fc26c06?q=80&w=800&auto=format&fit=crop'],
    
    // Bežecká obuv
    ['name' => 'Nike ZoomX Vaporfly', 'category' => 'Bežecká obuv', 'image_url' => 'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?q=80&w=800&auto=format&fit=crop'],
    ['name' => 'Adidas Ultraboost 22', 'category' => 'Bežecká obuv', 'image_url' => 'https://images.unsplash.com/photo-1518002171953-a080ee817e1f?q=80&w=800&auto=format&fit=crop'],
    ['name' => 'New Balance Fresh Foam', 'category' => 'Bežecká obuv', 'image_url' => 'https://images.unsplash.com/photo-1603808033192-082d6919d3e1?q=80&w=800&auto=format&fit=crop'],

    // Zimná obuv
    ['name' => 'Timberland Premium Boot', 'category' => 'Zimná obuv', 'image_url' => 'https://images.unsplash.com/photo-1608256246200-53e635b5b65f?q=80&w=800&auto=format&fit=crop'],
    ['name' => 'Dr. Martens 1460', 'category' => 'Zimná obuv', 'image_url' => 'https://images.unsplash.com/photo-1605034313761-73ea4a0cfbf3?q=80&w=800&auto=format&fit=crop'],

    // Šľapky
    ['name' => 'Adidas Adilette Slides', 'category' => 'Šľapky', 'image_url' => 'https://images.unsplash.com/photo-1603487742131-4160d6e18d13?q=80&w=800&auto=format&fit=crop'],
    ['name' => 'Birkenstock Arizona', 'category' => 'Šľapky', 'image_url' => 'https://images.unsplash.com/photo-1562183241-b937e95585b6?q=80&w=800&auto=format&fit=crop'],

    // Turistická obuv
    ['name' => 'Salomon Quest 4', 'category' => 'Turistická obuv', 'image_url' => 'https://images.unsplash.com/photo-1520639888713-7851133b1ed0?q=80&w=800&auto=format&fit=crop'],
    ['name' => 'Merrell Moab 3', 'category' => 'Turistická obuv', 'image_url' => 'https://images.unsplash.com/photo-1599121096203-147a2a7a3e36?q=80&w=800&auto=format&fit=crop'],
];

$colors = ['Čierna', 'Biela', 'Červená', 'Modrá', 'Sivá', 'Zelená'];

// Prepare insert statement
$sql = "INSERT INTO products (name, price, description, image_url, category, color, size) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($link, $sql);

echo "<h1>Generating 50 Products...</h1>";
echo "<p>Please wait, this might take a few seconds as we generate image URLs.</p>";

for ($i = 0; $i < 50; $i++) {
    // 1. Pick a product template from the curated list
    $product_template = $predefined_products[$i % count($predefined_products)];

    // 2. Assign data from the template
    $name = $product_template['name'];
    $category = $product_template['category'];
    $image_url = $product_template['image_url'];

    // 3. Randomize other details
    $price = rand(80, 250) + 0.99;
    $description = "Objavte štýl a pohodlie s $name. Tieto topánky sú navrhnuté pre maximálny komfort a trvácnosť. Ideálne na každodenné nosenie alebo športové aktivity.";
    $color = $colors[array_rand($colors)];
    $size = "38,39,40,41,42,43,44,45";

    mysqli_stmt_bind_param($stmt, "sdsssss", $name, $price, $description, $image_url, $category, $color, $size);
    mysqli_stmt_execute($stmt);
}

mysqli_stmt_close($stmt);
mysqli_close($link);

echo "<h2 style='color: green;'>Success! Database populated with 50 realistic products.</h2>";
echo "<a href='index.php'>Go to Shop</a>";
?>