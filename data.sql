-- This script updates the database schema and populates the 'products' table.
-- 1. It adds 'category', 'color', and 'size' columns to the 'products' table.
-- 2. It clears any existing data in the 'products' table.
-- 3. It inserts a new set of products with diverse attributes.

-- Add new columns to the products table
ALTER TABLE `products`
ADD `category` VARCHAR(255) NOT NULL,
ADD `color` VARCHAR(255) NOT NULL,
ADD `size` VARCHAR(255) NOT NULL;

-- Truncate the table to start with fresh data
TRUNCATE TABLE `products`;

-- Insert new products with categories, colors, and sizes
INSERT INTO `products` (`name`, `price`, `description`, `image_url`, `category`, `color`, `size`) VALUES
('Urban Runner', 120.00, 'Štýlové a pohodlné tenisky do mesta.', 'https://i.imgur.com/3u11S1w.png', 'Tenisky', 'Čierna', '40,41,42,43'),
('Classic White', 90.00, 'Klasické biele tenisky, ktoré sa hodia ku všetkému.', 'https://i.imgur.com/3u11S1w.png', 'Tenisky', 'Biela', '38,39,40,41'),
('Orange Blaze', 150.00, 'Výrazné oranžové tenisky pre odvážnych.', 'https://i.imgur.com/3u11S1w.png', 'Tenisky', 'Oranžová', '42,43,44,45'),
('Winter Boot', 200.00, 'Teplé a odolné topánky do zimného počasia.', 'https://i.imgur.com/Jz83L7A.png', 'Zimná obuv', 'Čierna', '40,41,42,43,44'),
('Snow Hiker', 220.00, 'Vysoké zimné topánky pre náročné podmienky.', 'https://i.imgur.com/Jz83L7A.png', 'Zimná obuv', 'Biela', '41,42,45'),
('Summer Slides', 40.00, 'Pohodlné šľapky na leto.', 'https://i.imgur.com/N5254m8.png', 'Šľapky', 'Čierna', '38,39,40,41,42,43'),
('Beach Sandals', 35.00, 'Ľahké sandále na pláž.', 'https://i.imgur.com/N5254m8.png', 'Šľapky', 'Oranžová', '39,40,41'),
('Pro Athlete', 180.00, 'Profesionálne bežecké topánky.', 'https://i.imgur.com/3u11S1w.png', 'Tenisky', 'Biela', '42,43,44'),
('Trekker Boot', 190.00, 'Pevné topánky na turistiku.', 'https://i.imgur.com/Jz83L7A.png', 'Zimná obuv', 'Čierna', '43,44,45'),
('Pool Sliders', 45.00, 'Protišmykové šľapky k bazénu.', 'https://i.imgur.com/N5254m8.png', 'Šľapky', 'Biela', '40,41,42');