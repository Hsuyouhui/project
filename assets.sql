-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2025-11-24 18:04:05
-- 伺服器版本： 8.0.43
-- PHP 版本： 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `assets`
--

-- --------------------------------------------------------

--
-- 資料表結構 `borrow_records`
--

CREATE TABLE `borrow_records` (
  `record_id` int NOT NULL,
  `item_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `user_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `borrow_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expected_return` date NOT NULL,
  `return_time` datetime DEFAULT NULL,
  `status` enum('借出','已歸還') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '借出'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `borrow_records`
--

INSERT INTO `borrow_records` (`record_id`, `item_id`, `user_id`, `user_name`, `email`, `borrow_time`, `expected_return`, `return_time`, `status`) VALUES
(1, 1, NULL, '王小明', '22635656@gmail.com', '2025-11-24 01:37:14', '2025-11-28', NULL, '借出'),
(3, 1, NULL, '王小明', '22635656@gmail.com', '2025-11-24 01:55:40', '2025-11-28', NULL, '借出'),
(4, 1, NULL, '王小明', '22635656@gmail.com', '2025-11-25 00:07:44', '2025-12-04', NULL, '借出'),
(9, 2, NULL, '王小明', '22635656@gmail.com', '2025-11-25 00:51:33', '2025-11-29', NULL, '借出'),
(10, 7, NULL, '王小明', '22635656@gmail.com', '2025-11-25 00:52:11', '2025-11-29', NULL, '借出');

-- --------------------------------------------------------

--
-- 資料表結構 `items`
--

CREATE TABLE `items` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total` int NOT NULL,
  `available` int NOT NULL,
  `status` enum('在庫','借出') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '在庫'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `items`
--

INSERT INTO `items` (`id`, `name`, `total`, `available`, `status`) VALUES
(1, '吹風機', 5, 3, '在庫'),
(2, '延長線', 3, 2, '在庫'),
(3, '掃把', 4, 4, '在庫'),
(4, '拖把', 4, 0, '借出'),
(7, '安全帽', 3, 2, '在庫'),
(10, '冰箱', 3, 3, '在庫');

-- --------------------------------------------------------

--
-- 資料表結構 `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `user_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','user') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `role`, `password`, `account`) VALUES
(1, '管理員', 'admin', 'password', 'root'),
(2, '小明', 'user', 'pw1', 'user1');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `borrow_records`
--
ALTER TABLE `borrow_records`
  ADD PRIMARY KEY (`record_id`),
  ADD KEY `fk_item` (`item_id`),
  ADD KEY `fk_user` (`user_id`);

--
-- 資料表索引 `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `borrow_records`
--
ALTER TABLE `borrow_records`
  MODIFY `record_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `items`
--
ALTER TABLE `items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `borrow_records`
--
ALTER TABLE `borrow_records`
  ADD CONSTRAINT `borrow_records_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `borrow_records_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
