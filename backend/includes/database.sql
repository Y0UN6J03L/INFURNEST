-- INFURNEST Database Schema
-- Run this SQL to create all necessary tables

-- Create database if not exists
CREATE DATABASE IF NOT EXISTS infurnest CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE infurnest;

-- ============================================
-- USERS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20) DEFAULT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('customer', 'admin') DEFAULT 'customer',
    is_verified TINYINT(1) DEFAULT 0,
    verification_token VARCHAR(255) DEFAULT NULL,
    reset_token VARCHAR(255) DEFAULT NULL,
    reset_expires DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    INDEX idx_email (email),
    INDEX idx_verification_token (verification_token),
    INDEX idx_reset_token (reset_token)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- PRODUCTS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    slug VARCHAR(250) NOT NULL UNIQUE,
    description TEXT,
    details TEXT,
    category ENUM('food', 'toys', 'beds', 'accessories', 'grooming') NOT NULL,
    pet_type ENUM('dog', 'cat', 'both') DEFAULT 'both',
    brand VARCHAR(100) DEFAULT NULL,
    price DECIMAL(10,2) NOT NULL,
    old_price DECIMAL(10,2) DEFAULT NULL,
    weight VARCHAR(50) DEFAULT NULL,
    age_group VARCHAR(50) DEFAULT NULL,
    in_stock TINYINT(1) DEFAULT 1,
    stock_quantity INT DEFAULT 0,
    rating DECIMAL(3,2) DEFAULT 0,
    review_count INT DEFAULT 0,
    badge VARCHAR(50) DEFAULT NULL,
    emoji VARCHAR(10) DEFAULT '📦',
    image_url VARCHAR(500) DEFAULT NULL,
    is_featured TINYINT(1) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_category (category),
    INDEX idx_pet_type (pet_type),
    INDEX idx_price (price),
    INDEX idx_slug (slug),
    FULLTEXT INDEX idx_search (name, description, brand)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- CARTS TABLE (Persistent Cart)
-- ============================================
CREATE TABLE IF NOT EXISTS carts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(100) NOT NULL,
    user_id INT DEFAULT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_session_id (session_id),
    INDEX idx_user_id (user_id),
    INDEX idx_product_id (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- WISHLISTS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS wishlists (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_product (user_id, product_id),
    INDEX idx_user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- ORDERS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(50) NOT NULL UNIQUE,
    user_id INT NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    shipping_fee DECIMAL(10,2) DEFAULT 0,
    total DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    shipping_name VARCHAR(100) NOT NULL,
    shipping_phone VARCHAR(20) NOT NULL,
    shipping_address TEXT NOT NULL,
    shipping_city VARCHAR(100) NOT NULL,
    shipping_zip VARCHAR(20) NOT NULL,
    payment_method VARCHAR(50) DEFAULT 'cod',
    payment_status ENUM('pending', 'paid', 'failed') DEFAULT 'pending',
    notes TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    INDEX idx_order_number (order_number),
    INDEX idx_user_id (user_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- ORDER ITEMS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(200) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id),
    INDEX idx_order_id (order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- REVIEWS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    review_text TEXT,
    is_verified_purchase TINYINT(1) DEFAULT 0,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id),
    UNIQUE KEY unique_user_product_review (user_id, product_id),
    INDEX idx_product_id (product_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- ACTIVITY LOG TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS activity_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    action VARCHAR(100) NOT NULL,
    details TEXT,
    ip_address VARCHAR(45) DEFAULT NULL,
    user_agent VARCHAR(500) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    INDEX idx_action (action),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- INSERT SAMPLE PRODUCTS
-- ============================================
INSERT INTO products (name, slug, description, details, category, pet_type, brand, price, old_price, weight, age_group, in_stock, stock_quantity, rating, review_count, badge, emoji, image_url, is_featured) VALUES
('Royal Canin Adult Dog Food', 'royal-canin-adult-dog-food', 'Complete balanced nutrition for adult dogs. Scientifically formulated for optimal health.', 'Supports healthy digestion, a shiny coat, and strong muscles. Suitable for all breeds.', 'food', 'dog', 'Royal Canin', 1299, 1599, '2kg', 'Adult', 1, 50, 5.0, 124, 'bestseller', '🥘', 'imgs/royal_canin.png', 1),

('Interactive Feather Cat Toy', 'interactive-feather-cat-toy', 'Retractable feather wand toy. Keeps cats mentally stimulated and physically active.', 'Telescopic rod extends up to 90cm. Feather attachments are replaceable.', 'toys', 'cat', 'PetPlay', 349, NULL, '150g', 'All Ages', 1, 100, 5.0, 87, 'new', '🪶', 'imgs/feather_cat_toy.png', 0),

('Cozy Donut Dog Bed', 'cozy-donut-dog-bed', 'Ultra-plush donut-shaped dog bed. Self-warming design for deep, restful sleep.', 'Machine washable cover. Available in brown, grey, and cream. Supports joints.', 'beds', 'dog', 'PawDreams', 2199, 2799, '1.2kg', 'All Sizes', 1, 30, 4.0, 56, 'sale', '🛏', NULL, 1),

('Cat Dental Treats', 'cat-dental-treats', 'Chicken-flavored dental chews that clean teeth and freshen breath naturally.', 'Vet-recommended formula. 60 treats per bag. Reduces tartar buildup.', 'food', 'cat', 'Whiskas Care', 299, NULL, '200g', 'Adult', 1, 80, 4.0, 43, 'new', '🦷', 0),
('GPS Dog Collar Tracker', 'gps-dog-collar-tracker', 'Real-time GPS tracking collar. Monitor your dog''s location via smartphone app.', 'Waterproof, rechargeable, works nationwide. Compatible with iOS & Android.', 'accessories', 'dog', 'PetTrack', 3499, 3999, '80g', 'All Breeds', 1, 20, 5.0, 92, 'bestseller', '📡', 1),
('Cat Grooming Brush Set', 'cat-grooming-brush-set', 'Professional 3-piece grooming set. Deshedding brush, comb, and nail clipper.', 'Ergonomic handles. Stainless steel blades. Suitable for short and long coats.', 'grooming', 'cat', 'GloomGroom', 649, NULL, '300g', 'All Ages', 1, 60, 4.0, 31, NULL, '✂️', 0),
('Orthopedic Cat Bed', 'orthopedic-cat-bed', 'Memory foam orthopedic bed with removable, washable cover. Perfect for senior cats.', 'High-density memory foam relieves pressure points. Waterproof inner lining.', 'beds', 'cat', 'PawDreams', 1799, 2299, '900g', 'All Ages', 1, 25, 5.0, 68, 'sale', '😴', 1),
('Natural Dog Shampoo', 'natural-dog-shampoo', 'Oatmeal & aloe vera formula. Gentle on sensitive skin. Dermatologist-tested.', 'pH-balanced, sulfate-free, no artificial fragrances. 500ml bottle.', 'grooming', 'dog', 'PurePaws', 459, NULL, '550g', 'All Ages', 1, 70, 4.0, 29, NULL, '🛁', 0),
('Dog Rope Chew Toy Set', 'dog-rope-chew-toy-set', 'Set of 5 durable cotton rope toys. Promotes dental health and satisfies chewing instincts.', '100% natural cotton, no synthetic dyes. Machine washable. Great for tug-of-war.', 'toys', 'dog', 'RopePlay', 399, NULL, '500g', 'All Ages', 1, 90, 4.0, 47, 'new', '🪢', 0),
('Premium Cat Wet Food Pack', 'premium-cat-wet-food-pack', '12-pack of gourmet wet food in tuna, chicken, and salmon flavors. No preservatives.', 'Real meat first ingredient. High protein, low carb. Suitable for all life stages.', 'food', 'cat', 'Felix Gourmet', 799, 999, '1.4kg', 'Adult', 1, 45, 5.0, 108, 'sale', '🍖', 1),
('Cat Window Perch', 'cat-window-perch', 'Suction-cup mounted window seat. Lets cats enjoy a birds-eye view in comfort.', 'Holds up to 15kg. UV-resistant fabric. Easy installation, no tools needed.', 'accessories', 'cat', 'CatView', 1149, NULL, '700g', 'All Ages', 1, 35, 4.0, 38, NULL, '🪟', 0),
('Dog Training Clicker Kit', 'dog-training-clicker-kit', 'Professional training clicker with wrist strap + 50-page training guide included.', 'Loud, clear click sound. Ergonomic design. Works for basic and advanced training.', 'accessories', 'dog', 'SmartPet', 299, NULL, '100g', 'All Ages', 1, 120, 5.0, 64, 'new', '🔔', 0),
('Cat Scratching Tower', 'cat-scratching-tower', '5-level cat tower with scratching posts, hammock, and dangling toys. Saves your furniture.', 'Sisal rope scratching posts. Stable base. Easy assembly. Accommodates multiple cats.', 'accessories', 'cat', 'CatKingdom', 2899, 3499, '5.2kg', 'All Ages', 1, 15, 5.0, 91, 'bestseller', '🏰', 1),
('Puppy Starter Pack', 'puppy-starter-pack', 'Complete starter kit for new puppies. Includes food, toys, collar, and care guide.', 'Age-appropriate nutrition. Soft puppy toys. Adjustable collar. PDF care guide.', 'food', 'dog', 'INFURNEST', 1599, 1999, '2.5kg', 'Puppy', 1, 40, 5.0, 55, 'sale', '🎁', 1),
('Automatic Pet Water Fountain', 'automatic-pet-water-fountain', 'Circulating water fountain keeps water fresh and oxygenated. 2.5L capacity.', 'Ultra-quiet pump. 3-stage filtration. LED water level indicator. BPA-free.', 'accessories', 'dog', 'HydraPet', 1899, NULL, '800g', 'All Ages', 1, 30, 4.0, 73, NULL, '⛲', 0),
('Cat Litter Premium Clay', 'cat-litter-premium-clay', 'Clumping clay litter with activated charcoal odor control. 7kg bag.', '99% dust-free. Fast clumping formula. 4-week odor protection. Unscented.', 'accessories', 'cat', 'CleanPaws', 549, NULL, '7kg', 'All Ages', 1, 100, 4.0, 36, NULL, '🪣', 0);

-- ============================================
-- INSERT SAMPLE ADMIN USER (password: admin123)
-- ============================================
INSERT INTO users (first_name, last_name, email, phone, password_hash, role, is_verified) VALUES
('Admin', 'User', 'admin@infurnest.com', '+639123456789', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oUoEa9Qo9TlT0hT1d.n3oTmMFmCPGSq', 'admin', 1);

-- ============================================
-- CREATE PROCEDURES FOR CART MANAGEMENT
-- ============================================
DELIMITER //
CREATE PROCEDURE IF NOT EXISTS save_cart_item(
    IN p_session_id VARCHAR(100),
    IN p_user_id INT,
    IN p_product_id INT,
    IN p_quantity INT
)
BEGIN
    DECLARE v_price DECIMAL(10,2);
    
    SELECT price INTO v_price FROM products WHERE id = p_product_id;
    
    INSERT INTO carts (session_id, user_id, product_id, quantity, price)
    VALUES (p_session_id, p_user_id, p_product_id, p_quantity, v_price)
    ON DUPLICATE KEY UPDATE 
        quantity = quantity + p_quantity,
        updated_at = CURRENT_TIMESTAMP;
END //

CREATE PROCEDURE IF NOT EXISTS get_cart_items(
    IN p_session_id VARCHAR(100),
    IN p_user_id INT
)
BEGIN
    SELECT c.id, c.product_id, c.quantity, c.price, p.name, p.emoji, p.category, p.pet_type, p.stock_quantity
    FROM carts c
    JOIN products p ON c.product_id = p.id
    WHERE COALESCE(c.session_id, CAST(c.user_id AS CHAR)) = COALESCE(p_session_id, CAST(p_user_id AS CHAR))
    AND c.user_id = p_user_id;
END //

DELIMITER ;

-- All set! The database is ready.
