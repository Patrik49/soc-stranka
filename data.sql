
-- Truncate the table to start with fresh data
TRUNCATE TABLE `products`;

-- Insert new products with categories, colors, and sizes
INSERT INTO `products` (`name`, `price`, `description`, `image_url`, `category`, `color`, `size`) VALUES
('Urban Runner', 120.00, 'Štýlové a pohodlné tenisky do mesta.', 'https://images.unsplash.com/photo-1549298916-b41d501d3772?auto=format&fit=crop&w=500&q=80', 'Tenisky', 'Čierna', '40,41,42,43'),
('Classic White', 90.00, 'Klasické biele tenisky, ktoré sa hodia ku všetkému.', 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?auto=format&fit=crop&w=500&q=80', 'Tenisky', 'Biela', '38,39,40,41'),
('Orange Blaze', 150.00, 'Výrazné oranžové tenisky pre odvážnych.', 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=500&q=80', 'Tenisky', 'Oranžová', '42,43,44,45'),
('Winter Boot', 200.00, 'Teplé a odolné topánky do zimného počasia.', 'https://images.unsplash.com/photo-1608231387042-66d1773070a5?auto=format&fit=crop&w=500&q=80', 'Zimná obuv', 'Čierna', '40,41,42,43,44'),
('Snow Hiker', 220.00, 'Vysoké zimné topánky pre náročné podmienky.', 'https://images.unsplash.com/photo-1516478177764-9fe5bd7e9717?auto=format&fit=crop&w=500&q=80', 'Zimná obuv', 'Biela', '41,42,45'),
('Summer Slides', 40.00, 'Pohodlné šľapky na leto.', 'https://images.unsplash.com/photo-1603487742131-4160d6e1843e?auto=format&fit=crop&w=500&q=80', 'Šľapky', 'Čierna', '38,39,40,41,42,43'),
('Beach Sandals', 35.00, 'Ľahké sandále na pláž.', 'https://images.unsplash.com/photo-1606890658317-7d14490b76fd?auto=format&fit=crop&w=500&q=80', 'Šľapky', 'Oranžová', '39,40,41'),
('Pro Athlete', 180.00, 'Profesionálne bežecké topánky.', 'https://images.unsplash.com/photo-1600185365926-3a2ce3cdb9eb?auto=format&fit=crop&w=500&q=80', 'Tenisky', 'Biela', '42,43,44'),
('Trekker Boot', 190.00, 'Pevné topánky na turistiku.', 'https://images.unsplash.com/photo-1520639888713-7851133b1ed0?auto=format&fit=crop&w=500&q=80', 'Zimná obuv', 'Čierna', '43,44,45'),
('Pool Sliders', 45.00, 'Protišmykové šľapky k bazénu.', 'https://images.unsplash.com/photo-1605425183426-8779779633e6?auto=format&fit=crop&w=500&q=80', 'Šľapky', 'Biela', '40,41,42');