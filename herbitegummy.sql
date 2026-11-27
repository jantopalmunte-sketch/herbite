-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 25 Nov 2026 pada 02.27
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `herbitegummy`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(120) NOT NULL,
  `contact` varchar(190) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `user_id`, `name`, `contact`, `message`, `is_read`, `created_at`) VALUES
(1, 2, 'Supri Adi', 'sadi658512@gmail.com', 'Hy', 1, '2026-11-24 17:31:24');

-- --------------------------------------------------------

--
-- Struktur dari tabel `daily_stocks`
--

CREATE TABLE `daily_stocks` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `stock_date` date NOT NULL,
  `stock_total` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `stock_sold` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `daily_stocks`
--

INSERT INTO `daily_stocks` (`id`, `product_id`, `stock_date`, `stock_total`, `stock_sold`, `updated_at`) VALUES
(1, 1, '2026-11-24', 33, 2, '2026-11-24 16:51:18'),
(2, 1, '2026-11-25', 31, 2, '2026-11-24 17:51:47'),
(3, 3, '2026-11-25', 0, 0, '2026-11-25 01:06:14'),
(4, 2, '2026-11-25', 0, 0, '2026-11-25 01:06:29');

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_code` varchar(30) NOT NULL,
  `user_id` int(11) NOT NULL,
  `customer_name` varchar(120) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `address` text NOT NULL,
  `delivery_method` enum('delivery','pickup') NOT NULL,
  `distance_km` decimal(8,2) NOT NULL DEFAULT 0.00,
  `shipping_cost` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `subtotal` int(10) UNSIGNED NOT NULL,
  `total` int(10) UNSIGNED NOT NULL,
  `status` enum('waiting_payment','paid','packing','ready_pickup','shipping','completed','cancelled') NOT NULL DEFAULT 'waiting_payment',
  `customer_received` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `orders`
--

INSERT INTO `orders` (`id`, `order_code`, `user_id`, `customer_name`, `phone`, `address`, `delivery_method`, `distance_km`, `shipping_cost`, `subtotal`, `total`, `status`, `customer_received`, `created_at`) VALUES
(1, 'HG2611249305A9', 2, 'Supri Adi', '08334267843', 'sdgfgfd', 'delivery', 8.63, 4500, 10000, 14500, 'completed', 1, '2026-11-24 14:13:13'),
(2, 'HG2611243DA7A8', 2, 'Supri Adi', '08334267843', 'fdsfdsgfdg', 'delivery', 118.37, 59500, 10000, 69500, 'completed', 1, '2026-11-24 16:51:18'),
(3, 'HG261124D6F739', 3, 'Candra', '08334267843', 'dcsasf', 'delivery', 118.37, 59500, 10000, 69500, 'completed', 1, '2026-11-24 17:42:21'),
(4, 'HG261124C5FAE3', 3, 'Candra', '08334267843', 'fdfdgf', 'delivery', 118.37, 59500, 10000, 69500, 'waiting_payment', 0, '2026-11-24 17:51:47');

-- --------------------------------------------------------

--
-- Struktur dari tabel `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(120) NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `price` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `quantity`, `price`) VALUES
(1, 1, 1, 'Herbite Gummy - Rasa Mangga', 1, 10000),
(2, 2, 1, 'Herbite Gummy - Rasa Mangga', 1, 10000),
(3, 3, 1, 'Herbite Gummy - Rasa Mangga', 1, 10000),
(4, 4, 1, 'Herbite Gummy - Rasa Mangga', 1, 10000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `proof_file` varchar(255) DEFAULT NULL,
  `status` enum('waiting','verified','rejected') NOT NULL DEFAULT 'waiting',
  `note` varchar(255) DEFAULT NULL,
  `uploaded_at` timestamp NULL DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `proof_file`, `status`, `note`, `uploaded_at`, `verified_at`) VALUES
(1, 1, 'bukti_HG2611249305A9_742d59c2.jpg', 'verified', NULL, '2026-11-24 16:25:29', '2026-11-24 16:26:33'),
(2, 2, 'bukti_HG2611243DA7A8_9d209f0a.jpg', 'verified', NULL, '2026-11-24 17:03:56', '2026-11-24 17:04:07'),
(3, 3, 'bukti_HG261124D6F739_bb3df95c.jpg', 'verified', NULL, '2026-11-24 17:42:35', '2026-11-24 17:43:59'),
(4, 4, 'bukti_HG261124C5FAE3_62476984.jpg', 'waiting', NULL, '2026-11-24 17:51:54', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `flavor` varchar(80) NOT NULL,
  `price` int(10) UNSIGNED NOT NULL DEFAULT 10000,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('ready','coming_soon') NOT NULL DEFAULT 'coming_soon',
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `name`, `flavor`, `price`, `image`, `status`, `active`) VALUES
(1, 'Herbite Gummy - Rasa Anggur', 'Anggur', 10000, 'uploads/produk/p1795568821319dce.png', 'ready', 1),
(2, 'Herbite Gummy - Rasa Stroberi', 'Stroberi', 10000, 'uploads/produk/p1795568789db119a.png', 'coming_soon', 1),
(3, 'Herbite Gummy - Rasa Soloo', 'Soloo', 10000, 'produkbaru.jpg', 'coming_soon', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `settings`
--

CREATE TABLE `settings` (
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `settings`
--

INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('delivery_cutoff', '15:00'),
('free_ship_min_qty', '5'),
('production_address', 'Jl. Bukit Tunggul Raya No.258, RT.001/RW.011,ada tulisan kost putri\r\nKayuringin Jaya, Bekasi Selatan, Kota Bekasi, Jawa Barat, Indonesia'),
('production_lat', '-6.241650997277186'),
('production_lng', '106.99220698533219'),
('qris_image', 'qris.png'),
('shipping_rate', '500'),
('whatsapp', '6285378582198');

-- --------------------------------------------------------

--
-- Struktur dari tabel `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `rating` tinyint(3) UNSIGNED DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `testimonials`
--

INSERT INTO `testimonials` (`id`, `user_id`, `order_id`, `message`, `rating`, `is_published`, `created_at`) VALUES
(1, 2, 1, 'Rasanya enak banget!', 5, 1, '2026-11-24 16:28:25'),
(2, 2, 2, 'Pengiriman cepat', 5, 1, '2026-11-24 17:06:14'),
(3, 3, 3, 'Pasti beli lagi', 5, 1, '2026-11-24 17:45:37');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(190) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('customer','admin') NOT NULL DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `role`, `created_at`) VALUES
(1, 'Admin', 'admin@gmail.com', '', '$2y$10$NksnzXV2J9rqh7sxvt71n.67/kwMTVNWLLfJivFtB.eLv9OXbGowm', 'admin', '2026-11-24 12:40:06'),
(2, 'Supri Adi', 'sadi658512@gmail.com', '08334267843', '$2y$10$TSvIjhpaMhWf4tx2wtpvUOO/vg7E5UvqBT52NBLwg4Kiez1ymtpeG', 'customer', '2026-11-24 14:04:20'),
(3, 'Candra', 'candra@gmail.com', '08334267843', '$2y$10$y5DY6OJkQ8hOm85KGKrTyOif/Y9TwHY6/HsRc91xDJR/aMWfYNPWG', 'customer', '2026-11-24 17:40:59');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `daily_stocks`
--
ALTER TABLE `daily_stocks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_product_day` (`product_id`,`stock_date`);

--
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_code` (`order_code`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indeks untuk tabel `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_id` (`order_id`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_flavor` (`flavor`);

--
-- Indeks untuk tabel `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`setting_key`);

--
-- Indeks untuk tabel `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_id` (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `daily_stocks`
--
ALTER TABLE `daily_stocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `daily_stocks`
--
ALTER TABLE `daily_stocks`
  ADD CONSTRAINT `daily_stocks_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Ketidakleluasaan untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Ketidakleluasaan untuk tabel `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `testimonials`
--
ALTER TABLE `testimonials`
  ADD CONSTRAINT `testimonials_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `testimonials_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
