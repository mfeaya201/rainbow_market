-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql301.infinityfree.com
-- Generation Time: Jul 03, 2025 at 05:29 AM
-- Server version: 11.4.7-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_39355525_rainbow_market_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `message`, `created_at`) VALUES
(1, 5, 4, 'Hello Ayesha I would like to know if you have any thriller novel\'s available', '2025-07-02 00:34:17');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `status` enum('pending','out for delivery','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `amount` decimal(10,2) NOT NULL,
  `payment_status` enum('pending','held','released','refunded') DEFAULT 'pending',
  `delivery_confirmed` tinyint(1) DEFAULT 0,
  `delivery_status` enum('processing','shipped','delivered') DEFAULT 'processing',
  `confirmed_delivery` tinyint(1) DEFAULT 0,
  `payment_released` tinyint(1) DEFAULT 0,
  `payment_method` varchar(20) NOT NULL DEFAULT 'wallet'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `buyer_id`, `product_id`, `quantity`, `status`, `created_at`, `amount`, `payment_status`, `delivery_confirmed`, `delivery_status`, `confirmed_delivery`, `payment_released`, `payment_method`) VALUES
(1, 7, 5, 2, 'out for delivery', '2025-07-02 14:19:55', '0.00', 'pending', 0, 'processing', 0, 0, 'paid'),
(2, 7, 8, 1, 'out for delivery', '2025-07-02 14:19:55', '0.00', 'pending', 0, 'processing', 0, 0, 'paid'),
(3, 8, 4, 1, 'pending', '2025-07-03 11:56:20', '0.00', 'pending', 0, 'processing', 0, 0, 'paid');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `images` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `condition` varchar(50) DEFAULT NULL,
  `delivery` varchar(50) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `status` enum('active','out of stock') DEFAULT 'active',
  `stock` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `seller_id`, `title`, `description`, `category`, `price`, `images`, `created_at`, `condition`, `delivery`, `province`, `city`, `status`, `stock`) VALUES
(3, 1, 'Simplicity Jacket', 'Cropped cargo jacket that comes in three different styles / colors.', 'fashion', '550.00', '[\"uploads\\/1751450986_WhatsApp Image 2025-06-30 at 11.40.00_0684e0c3.jpg\"]', '2025-07-02 10:09:45', 'new', 'paxi', 'KwaZulu-Natal', 'Durban', 'active', 10),
(4, 1, 'Cargo Pants', 'Cargo Pants from Vintage looks that comes in two colors black and brown', 'fashion', '450.00', '[\"uploads\\/1751451109_WhatsApp Image 2025-06-30 at 11.40.00_f1e31e7f.jpg\"]', '2025-07-02 10:11:49', 'new', 'paxi', 'KwaZulu-Natal', 'Durban', 'active', 7),
(5, 1, 'Vintage looks Hoodies', 'Street wear style hoodies that come in various colors', 'fashion', '450.00', '[\"uploads\\/1751451243_WhatsApp Image 2025-06-30 at 11.40.01_973bdf5c.jpg\"]', '2025-07-02 10:14:02', 'new', 'paxi', 'KwaZulu-Natal', 'Durban', 'active', 16),
(6, 1, 'Virgin Hair - Vintage', '26 inch, 4x4 closure unit in the color black. Only worn a few times with minimal shedding but it is still full in density.', 'beauty', '2500.00', '[\"uploads\\/1751451455_WhatsApp Image 2025-06-30 at 11.40.02_339dacec.jpg\"]', '2025-07-02 10:17:34', 'good', 'paxi', 'KwaZulu-Natal', 'Durban', 'active', 1),
(7, 3, 'iPhone 12 Black 64GB (Unlocked)', 'Screen Size\r\n\r\n6.1 Inch\r\n\r\nScreen Type\r\n\r\nOLED\r\n\r\nStorage Size\r\n\r\n64GB\r\n\r\nColor\r\n\r\nBlack\r\n\r\nRelease Date\r\n\r\nOctober 2020\r\n\r\n5G Compatible\r\n\r\nYes\r\n\r\nConnector\r\n\r\nLightning\r\n\r\nUnlocked or Tied to Carrier?\r\n\r\nFully unlocked (GSM & CDMA)', 'tech', '5000.00', '[\"uploads\\/1751452228_i12black3.avif\"]', '2025-07-02 10:30:28', 'good', 'paxi', 'Western Cape', 'Cape Town', 'active', 1),
(8, 3, 'TCL True Wireless Earbuds MOVEAUDIO S150 - White', 'Owned for about 4 months battery health went do to lasting 4 hours. Still works well and wireless connection is good even with calls.', 'tech', '130.00', '[\"uploads\\/1751452739_TCLMoveAudioS150_Info1.avif\"]', '2025-07-02 10:38:59', 'fair', 'paxi', '', '', 'out of stock', 0),
(9, 4, 'The Diary Of a CEO', 'From \'never disagree\' to \'don\'t attack beliefs, inspire new ones,\' this book contains surprising wisdom that will move you forward personally and professionally. I highly recommend., It\'s about time that we read about success in our modern world as seen by one who\'s navigated the path to success like no other. Intelligent, insightful, and real. I am humbled by how much I learned from Steven\'s work.', 'books', '300.00', '[\"uploads\\/img_686646c10837b0.97535744.avif\"]', '2025-07-03 09:00:49', 'like-new', 'paxi', '', '', 'active', 2),
(10, 4, 'Atomic Habits - James Clear', 'Second hand book by James Clear - Atomic Habits. Cover and everything is still like new with only minimal wear and tear.', 'books', '350.00', '[\"uploads\\/img_6866479941e002.34389014.avif\"]', '2025-07-03 09:04:25', 'like-new', 'paxi', '', '', 'active', 1);

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `reporter_id` int(11) NOT NULL,
  `reported_user_id` int(11) NOT NULL,
  `reason` text NOT NULL,
  `status` enum('pending','resolved') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sellers`
--

CREATE TABLE `sellers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `store_name` varchar(100) NOT NULL,
  `status` enum('active','banned','pending') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sellers`
--

INSERT INTO `sellers` (`id`, `user_id`, `store_name`, `status`, `created_at`) VALUES
(1, 1, 'Thando Maseko\'s Store', 'active', '2025-07-02 06:36:38'),
(2, 2, 'Zanele Mthembu\'s Store', 'active', '2025-07-02 06:36:38'),
(3, 3, 'Sipho Dlamini\'s Store', 'active', '2025-07-02 06:36:38'),
(4, 4, 'Ayesha Khan\'s Store', 'active', '2025-07-02 06:36:38');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` enum('escrow','release','refund') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `status` enum('pending','completed','cancelled') DEFAULT 'pending',
  `reference` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('buyer','seller','banned') DEFAULT 'buyer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Thando Maseko', 'thando.styles@gmail.com', '$2y$10$.o/uzDVsno9OYPWQtkPYUeFd0oKQ3l2mwG5LSSzXNbbfn5VflfbCe', 'seller', '2025-06-30 09:54:50'),
(2, 'Zanele Mthembu', 'zanele.beauty@gmail.com', '$2y$10$X7NKcH1ZIjf41cnabGH5U.9e2zJOs57/6TdYhkhB/z5LZ9h98gxkK', 'seller', '2025-06-30 10:02:29'),
(3, 'Sipho Dlamini', 'sipho.techie@gmail.com', '$2y$10$jRV.3Inm5au6SHjT3pGkFeBX1Mh0Pp3H1R5TwSNrY9W8RoaOKw3TC', 'seller', '2025-06-30 10:15:47'),
(4, 'Ayesha Khan', 'ayesha.books@gmail.com', '$2y$10$AAfEgw3wGvxPOg9RmXtmcuKN87RpkgPsHC2EnLz3dfDYEikOnQ8Vi', 'seller', '2025-06-30 10:19:46'),
(5, 'Jason Mokoena', 'jason.buyer01@gmail.com', '$2y$10$uXCOVt/6FZ27hX9qs.H18ucOD3H3sxiioIEgQOZYZofTgaiT6cTEO', 'buyer', '2025-06-30 11:49:00'),
(6, 'Siphokazi Genu', 'siphoM.buyer.@gmail', '$2y$10$y2zFdI3/uCMp4h/tUx/FOesKkOuCXK083meipXdGkT9ZiheceOPm2', 'buyer', '2025-06-30 12:57:07'),
(7, 'Lerato Van Wyk', 'lerato.vw@gmail.com', '$2y$10$lguQcxe6EG.dbZvisOSPquymKULxhAezcuvqz6slv17gV5c6vtho2', 'buyer', '2025-07-02 10:40:41'),
(8, 'Kabelo Smith', 'kabelo.shopper@gmail.com', '$2y$10$ddRy3yrCa1l453cJIj4fjOrvvvzh.2uhI/QpUT4qNSdOcjQAu.vJy', 'buyer', '2025-07-03 08:54:57');

-- --------------------------------------------------------

--
-- Table structure for table `wallet`
--

CREATE TABLE `wallet` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `balance` decimal(10,2) DEFAULT 0.00,
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wallet`
--

INSERT INTO `wallet` (`id`, `user_id`, `balance`, `last_updated`) VALUES
(1, 4, '0.00', '2025-07-01 23:54:00'),
(2, 5, '500.00', '2025-07-02 00:52:17'),
(3, 3, '0.00', '2025-07-01 23:54:00'),
(4, 6, '0.00', '2025-07-01 23:54:00'),
(5, 1, '0.00', '2025-07-01 23:54:00'),
(6, 2, '0.00', '2025-07-01 23:54:00'),
(7, 7, '270.00', '2025-07-02 04:19:55'),
(8, 8, '0.00', '2025-07-03 01:56:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `buyer_id` (`buyer_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seller_id` (`seller_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reporter_id` (`reporter_id`),
  ADD KEY `reported_user_id` (`reported_user_id`);

--
-- Indexes for table `sellers`
--
ALTER TABLE `sellers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wallet`
--
ALTER TABLE `wallet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sellers`
--
ALTER TABLE `sellers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `wallet`
--
ALTER TABLE `wallet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `sellers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`reporter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reports_ibfk_2` FOREIGN KEY (`reported_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sellers`
--
ALTER TABLE `sellers`
  ADD CONSTRAINT `sellers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `wallet`
--
ALTER TABLE `wallet`
  ADD CONSTRAINT `wallet_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
