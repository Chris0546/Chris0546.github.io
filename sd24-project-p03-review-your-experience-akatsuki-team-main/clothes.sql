-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 04, 2025 at 12:04 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `clothes`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `category` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `category`) VALUES
(1, 'Hoodies'),
(2, 'T-Shirts'),
(3, 'Sweatpants'),
(4, 'Pants');

-- --------------------------------------------------------

--
-- Table structure for table `form`
--

CREATE TABLE `form` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `time_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `form`
--

INSERT INTO `form` (`id`, `username`, `password`, `email`, `time_created`, `role`, `created_at`) VALUES
(1, 'test2', '$2y$10$HM3INQxIXgUzHD/WNZbWqeutN2.AEsY1n1XKqEP2fBQDblGKBwy1q', 'chris@outlook.com', '2025-05-22 09:24:20', '[\"ROLE_USER\"]', '2025-05-25 15:57:29'),
(2, 'ghost546', '$2y$10$BYqyFmRAHDVnSBQY0VPW5uAfYBOL7aD2m3XQHrDAoUxO.5C6m02p.', 'tim@gmail.com', '2025-05-22 09:49:44', '', '2025-05-25 15:57:29'),
(4, 'ahmed', '$2y$10$3jablgyRmgwkoBOtkTQ.HeSAlakGo.Qoe.gzdYcq61pGby3i0J28e', 'ahmed@outlook.nl', '2025-05-23 10:39:14', 'ROLE_USER', '2025-05-25 15:57:29'),
(5, 'goku', '$2y$10$ge2SK3ult1iAk/LTDAdjQOixf8f.nyYIQ0Qv4c1dX00pe91D/MVEm', 'goku@gmail.nl', '2025-05-23 10:44:20', 'ROLE_USER', '2025-05-25 15:57:29'),
(6, 'chris', '$2y$10$B3qu3IW0uMAxecN4JsKeS.KVH6ySIxkzNFbtlID/SAzU0RnRNTFZK', 'lisa@lisa.nl', '2025-05-25 13:52:57', 'ROLE_USER', '2025-05-25 15:57:29'),
(7, 'chris546', '$2y$10$v1yIfejaIrAIqtsWnuLdx.8iN/XvVYmVHhCfZmIE/YOK6T/O1J0AO', 'outlook@outlook.nl', '2025-05-25 13:53:47', 'ROLE_USER', '2025-05-25 15:57:29'),
(8, 'kris', '$2y$10$/LsP5rEnWCAuQi6wGZzECekPnVJOfn2vPy1tWTjRpMNm.zos.MqwO', 'ben@ben.nl', '2025-05-25 13:58:17', 'ROLE_USER', '2025-05-25 15:58:17'),
(9, 'kris546', '$2y$10$Vpg2.KpDHKiN/VWe616eYOR6nhu0Mpwf7myFKZbljRK1oOlJzZcVW', 'ben@gmail.nl', '2025-05-25 13:59:25', 'ROLE_USER', '2025-05-25 15:59:25'),
(10, 'cyber', '$2y$10$zEHOKyLOWUc9xzQFjVYnT.yNjdadCDLpRSBImXLdFKRd94gYiGcey', 'cyber@cyber.cw', '2025-05-25 14:24:17', 'ROLE_USER', '2025-05-25 16:24:17'),
(11, 'test0', '$2y$10$JXLPOGzdpWuEwtb0DGteUOeEfdTzgDXCxOmrAelnwxwHiM/ePJeOy', 'test@gmail.nl', '2025-05-25 15:15:57', 'ROLE_USER', '2025-05-25 17:15:57'),
(12, 'gleb', '$2y$10$DGBbwN5oLTRWTm3SUJyps.H873pAucV3UpSiuswTpRupMLygojPva', 'gleb@gleb.nl', '2025-05-26 11:49:49', 'ROLE_USER', '2025-05-26 13:49:49'),
(13, 'christopher', '$2y$10$mTo1FThPFOPwYihYW.7Yje3Li0fV1NOL6Ql2pnkBXiBNWHfnCY2Iy', 'chris@chris.nl', '2025-05-26 12:08:21', '[\"ROLE_ADMIN\"]', '2025-05-26 14:08:21'),
(14, 'admin', '$2y$10$AK2lyauElOEMjPOn02BgOO33c17fmMHMbDghM7B8HeLTgDLCeZzlu', 'admin@admin.nl', '2025-05-31 13:34:52', '[\"ROLE_ADMIN\"]', '2025-05-31 15:34:52'),
(15, 'ahmed1', '$2y$10$HXpQLmXsO4LdmHKGqzdTfOqOqadMd.dnI9bNLZG1GtRxI/NfYC9EW', 'ahmed@ahmed.nl', '2025-06-04 09:36:53', 'ROLE_USER', '2025-06-04 11:36:53'),
(16, 'mustafa', '$2y$10$JhbwGnCvXR6ThS/ozn8XB.QSPwuFKaZAzQidNCbBhDE2iQtuwsuNG', 'mustafa@mustafa.nl', '2025-06-04 09:48:56', '[\"ROLE_ADMIN\"]', '2025-06-04 11:48:56');

-- --------------------------------------------------------

--
-- Table structure for table `hoodies`
--

CREATE TABLE `hoodies` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `size` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `made_in` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hoodies`
--

INSERT INTO `hoodies` (`id`, `name`, `size`, `price`, `made_in`, `img`) VALUES
(1, 'Hoodies Akatsuki', 'L', '50.00', 'Japan', 'hoodies_1.jpg'),
(2, 'Hoodies Black', 'M', '78.99', 'China', 'hoodies_2.jpg'),
(3, 'Hoodies Itachi', 'L', '99.00', 'China', 'hoodies_3.jpg'),
(4, 'Hoodies White Cloud', 'S', '34.00', 'Japan', 'hoodies_4.webp'),
(5, 'White Hoodies', 'M', '45.00', 'China', 'hoodies_5.jpg'),
(6, 'Hoodies White Itachi', 'L', '65.00', 'Japan', 'hoodies_6.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `pants`
--

CREATE TABLE `pants` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `size` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `made_in` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pants`
--

INSERT INTO `pants` (`id`, `name`, `size`, `price`, `made_in`, `img`) VALUES
(1, 'Minato Pants', 'S', '60.00', 'Japan', 'pants_1.JPG'),
(2, 'Uchiha Pants', 'M', '98.00', 'Japan', 'pants_2.JPG'),
(3, 'Akatsuki Pants', 'M', '89.00', 'China', 'pants_3.JPG'),
(4, 'Akatuski Red Cloud Pants', 'L', '47.00', 'China', 'pants_5.JPG'),
(5, 'Naruto Pants', 'S', '67.00', 'Japan', 'pants_5.WEBP'),
(6, 'Akatuski Group Pants', 'S', '54.00', 'Usa', 'pants_6.JPG');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `size` varchar(255) DEFAULT NULL,
  `price` varchar(255) NOT NULL,
  `made_in` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `size`, `price`, `made_in`, `img`, `category`, `category_id`) VALUES
(1, 'Naruto T-shirt', 'L', '80.00', 'Japan', 't-shirt_1.jpg', 't-shirt', 2),
(2, 'T-shirt Leaf', 'M', '60.00', 'China', 't-shirt_2.WEBP', 't-shirt', 2),
(3, 'T-shirt Sage Mode', 'M', '89.00', 'Usa', 't-shirt_3.jpg', 't-shirt', 2),
(4, 'Naruto Vs Susake', 'M', '75.00', 'Japan', 't-shirt_4.AVIF', 't-shirt', 2),
(5, 'Naruto', 'L', '50.00', 'China', 't-shirt_5.jpg', 't-shirt', 2),
(6, 'Naruto And Susake', 'L', '32.00', 'China', 't-shirt_6.jpg', 't-shirt', 2),
(7, 'Minato Pants', 'S', '60.00', 'Japan', 'pants_1.JPG', 'pants', 4),
(8, 'Uchiha Pants', 'M', '98.00', 'Japan', 'pants_2.JPG', 'pants', 4),
(9, 'Akatsuki Pants', 'M', '89.00', 'China', 'pants_3.JPG', 'pants', 4),
(10, 'Hoodies White Itachi', 'L', '65.00', 'Japan', 'hoodies_6.jpg', 'hoodie', 1),
(11, 'Akatuski Red Cloud Pants', 'L', '47.00', 'China', 'pants_6.JPG', 'pants', 4),
(12, 'Naruto Pants', 'S', '67.00', 'Japan', 'pants_5.WEBP', 'pants', 4),
(13, 'Akatuski Group Pants', 'S', '54.00', 'Usa', 'pants_6.JPG', 'pants', 4),
(14, 'Uchiha Sweatpants', 'M', '50.00', 'Japan', 'sweatpants_1.WEBP', 'sweatpants', 3),
(15, 'Akatsuki Sweatpants', 'L', '80.00', 'Japan', 'sweatpants_2.WEBP', 'sweatpants', 3),
(16, 'Naruto Sweatpants', 'S', '95.00', 'China', 'sweatpants_3.WEBP', 'sweatpants', 3),
(17, 'Susake Sweatpants', 'L', '55.00', 'Japan', 'sweatpants_4.jpg', 'sweatpants', 3),
(18, 'Minato & Naruto', 'L', '78.50', 'Japan', 'sweatpants_5.JPEG', 'sweatpants', 3),
(19, 'kakashi Sweatpants', 'M', '70.00', 'China', 'sweatpants_6.JPEG', 'sweatpants', 3),
(20, 'Hoodies Akatsuki', 'L', '50.00', 'Japan', 'hoodies_1.jpg', 'hoodie', 1),
(21, 'Hoodies Black', 'M', '78.99', 'China', 'hoodies_2.jpg', 'hoodie', 1),
(22, 'Hoodies Itachi', 'L', '99.00', 'China', 'hoodies_3.jpg', 'hoodie', 1),
(23, 'Hoodies White Cloud', 'S', '34.00', 'Japan', 'hoodies_4.webp', 'hoodie', 1),
(24, 'White Hoodies', 'M', '45.00', 'China', 'hoodies_5.jpg', 'hoodie', 1),
(25, 'image1', 'L', '30.00', 'Usa', 'image1.jpg', 'hoodie', 1),
(26, 'naruto t-shirt', 'M', '25.00', 'canada', 'naruto_tshirt.png', 't-shirt', 2),
(27, 'Itachi T-shirt', 'M', '36.00', 'China', 'itachi_tshirt.jpeg', 't-shirt', 2),
(28, 'Uchiha Pants', 'L', '45.00', 'Japan', 'uchiha_pants.jpg', 'sweatpants', 3),
(29, 'Uchiha', 'M', '78.00', 'China', 'image3.jpg', 'hoodie', 1),
(30, 'Naruto Pants', 'L', '20.00', 'Japan', 'naruto_pants.webp', 'sweatpants', 3);

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `image` varchar(255) DEFAULT NULL,
  `rating` int(11) NOT NULL DEFAULT 5
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`id`, `user_id`, `product_id`, `name`, `comment`, `created_at`, `image`, `rating`) VALUES
(1, 6, 10, 'chris', 'this is nice', '2025-05-30 22:26:53', NULL, 5),
(2, 6, 10, 'chris', 'nice', '2025-05-30 22:27:17', NULL, 5),
(3, 6, 20, 'chris', 'test', '2025-05-30 22:34:53', NULL, 5),
(4, 14, 7, '', 'nice ', '2025-05-31 15:39:11', NULL, 5),
(5, 14, 10, 'admin', 'admin is here', '2025-05-31 15:49:16', NULL, 5),
(6, 14, 2, 'admin', 'test 5', '2025-05-31 15:52:15', NULL, 5),
(7, 6, 10, 'chris', 'nice test', '2025-06-01 10:59:12', NULL, 3),
(8, 6, 22, 'chris', 'good', '2025-06-01 11:18:49', NULL, 4),
(9, 6, 10, 'chris', 'nice ', '2025-06-01 11:25:11', NULL, 4),
(10, 6, 10, 'chris', 'amazing', '2025-06-01 11:26:00', NULL, 3),
(11, 6, 8, 'chris', 'nice', '2025-06-01 11:26:55', NULL, 2),
(12, 6, 14, 'chris', 'nicer', '2025-06-01 11:35:50', NULL, 3),
(13, 6, 15, 'chris', 'amazing', '2025-06-01 11:36:11', NULL, 4),
(14, 6, 21, 'chris', 'beautiful', '2025-06-01 11:51:19', NULL, 3),
(15, 14, 1, 'admin', 'admin was here', '2025-06-01 11:57:30', NULL, 3),
(16, 14, 31, 'admin', 'bad', '2025-06-01 17:49:39', NULL, 3),
(17, 14, 10, '', 'bla', '2025-06-02 14:12:42', NULL, 2),
(18, 14, 34, 'admin', 'bcoudsj ;:ZJXOBCv\'sdv ve', '2025-06-02 14:39:25', NULL, 2),
(19, 14, 19, 'admin', 'admin was here', '2025-06-04 11:29:02', NULL, 3),
(20, 14, 13, 'admin', 'admin was here', '2025-06-04 11:29:32', NULL, 2);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `product_id` int(11) NOT NULL,
  `hoodies_id` int(11) DEFAULT NULL,
  `pants_id` int(11) DEFAULT NULL,
  `sweatpants_id` int(11) DEFAULT NULL,
  `tshirt_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `name`, `comment`, `product_id`, `hoodies_id`, `pants_id`, `sweatpants_id`, `tshirt_id`, `user_id`, `image`) VALUES
(3, 'chris', 'molina', 0, 1, NULL, NULL, NULL, NULL, NULL),
(4, 'cyber', 'nice', 0, 1, NULL, NULL, NULL, 10, NULL),
(5, 'cyber', 'good', 0, 1, NULL, NULL, NULL, 10, NULL),
(6, 'test0', 'nice', 0, 1, NULL, NULL, NULL, 11, NULL),
(7, 'chris', 'nice', 0, 6, NULL, NULL, NULL, 6, NULL),
(8, 'chris', 'chris', 0, NULL, NULL, 1, NULL, 13, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sweatpants`
--

CREATE TABLE `sweatpants` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `size` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `made_in` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sweatpants`
--

INSERT INTO `sweatpants` (`id`, `name`, `size`, `price`, `made_in`, `img`) VALUES
(1, 'Uchiha Sweatpants', 'M', '50.00', 'Japan', 'sweatpants_1.WEBP'),
(2, 'Akatsuki Sweatpants', 'L', '80.00', 'Japan', 'sweatpants_2.WEBP'),
(3, 'Naruto Sweatpants', 'S', '95.00', 'China', 'sweatpants_3.WEBP'),
(4, 'Susake Sweatpants', 'L', '55.00', 'Japan', 'sweatpants_4.jpg'),
(5, 'Minato & Naruto', 'L', '78.50', 'Japan', 'sweatpants_5.JPEG'),
(6, 'kakashi Sweatpants', 'M', '70.00', 'China', 'sweatpants_6.JPEG');

-- --------------------------------------------------------

--
-- Table structure for table `t_shirt`
--

CREATE TABLE `t_shirt` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `size` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `made_in` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_shirt`
--

INSERT INTO `t_shirt` (`id`, `name`, `size`, `price`, `made_in`, `img`) VALUES
(1, 'Naruto T-shirt', 'L', '80.00', 'Japan', 't-shirt_1.jpg'),
(2, 'T-shirt Leaf', 'M', '60.00', 'China', 't-shirt_2.WEBP'),
(3, 'T-shirt Sage Mode', 'M', '89.00', 'Usa', 't-shirt_3.jpg'),
(4, 'Naruto Vs Susake', 'M', '75.00', 'Japan', 't-shirt_4.AVIF'),
(5, 'Naruto ', 'L', '50.00', 'China', 't-shirt_5.jpg'),
(6, 'Naruto And Susake', 'L', '32.00', 'China', 't-shirt_6.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `form`
--
ALTER TABLE `form`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hoodies`
--
ALTER TABLE `hoodies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pants`
--
ALTER TABLE `pants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `reviews_ibfk_1` (`hoodies_id`),
  ADD KEY `pants_id` (`pants_id`),
  ADD KEY `sweatpants_id` (`sweatpants_id`),
  ADD KEY `tshirt_id` (`tshirt_id`),
  ADD KEY `fk_user` (`user_id`);

--
-- Indexes for table `sweatpants`
--
ALTER TABLE `sweatpants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_shirt`
--
ALTER TABLE `t_shirt`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `form`
--
ALTER TABLE `form`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `hoodies`
--
ALTER TABLE `hoodies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pants`
--
ALTER TABLE `pants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `sweatpants`
--
ALTER TABLE `sweatpants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `t_shirt`
--
ALTER TABLE `t_shirt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `form` (`id`),
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`hoodies_id`) REFERENCES `hoodies` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`pants_id`) REFERENCES `pants` (`id`),
  ADD CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`sweatpants_id`) REFERENCES `sweatpants` (`id`),
  ADD CONSTRAINT `reviews_ibfk_4` FOREIGN KEY (`tshirt_id`) REFERENCES `t_shirt` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
