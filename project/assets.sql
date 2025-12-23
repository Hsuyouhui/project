-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2025-12-23 00:44:32
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
  `status` enum('已歸還','借出') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '借出'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `borrow_records`
--

INSERT INTO `borrow_records` (`record_id`, `item_id`, `user_id`, `user_name`, `email`, `borrow_time`, `expected_return`, `return_time`, `status`) VALUES
(21, 12, 2, '李燦', '413401443@m365.fju.edu.tw', '2025-12-20 15:38:54', '2025-12-31', '2025-12-21 12:14:33', '已歸還'),
(23, 3, 2, '李燦', '413401443@m365.fju.edu.tw', '2025-12-17 12:15:52', '2025-12-20', NULL, '借出'),
(24, 7, 2, '李燦', '413401443@m365.fju.edu.tw', '2025-12-21 12:16:22', '2025-12-25', '2025-12-22 13:24:17', '已歸還'),
(25, 1, 8, '李碩珉', '11035053@st1.ymsh.edu.tw', '2025-12-21 12:29:05', '2025-12-23', NULL, '借出'),
(26, 1, 2, '李燦', '413401443@m365.fju.edu.tw', '2025-12-21 14:26:44', '2025-12-27', NULL, '借出');

-- --------------------------------------------------------

--
-- 資料表結構 `items`
--

CREATE TABLE `items` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total` int NOT NULL,
  `available` int NOT NULL,
  `status` enum('在庫','借出') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '在庫',
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `items`
--

INSERT INTO `items` (`id`, `name`, `total`, `available`, `status`, `image_path`) VALUES
(1, '吹風機', 5, 3, '在庫', 'uploads/69457db747c9e.jpg'),
(2, '延長線', 3, 3, '在庫', 'uploads/69457dfa04f16.jpg'),
(3, '掃把', 4, 3, '在庫', 'uploads/6947783b9cb88_12593_487318_eg4myt90.jpg'),
(4, '拖把', 4, 3, '借出', 'uploads/694778071a84f_A1008--300x300.jpg'),
(7, '安全帽', 3, 3, '在庫', 'uploads/6947785fad5a7_800x.png'),
(12, '冰箱', 3, 2, '在庫', 'uploads/694778ac16a94_8422EA226C-SP-18288481.jpg');

-- --------------------------------------------------------

--
-- 資料表結構 `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `user_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','user') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `role`, `password`, `account`, `email`) VALUES
(1, '管理員', 'admin', '$2y$10$8s0xfhXRikL9LWvvn66CyOGYc6bgXW6e.hoG4jlfPtJbRtEFAJbEW', 'root', '22635656@gmail.com'),
(2, '李燦', 'user', '$2y$10$BJRHw71vfZaCyRRBiAKl9uc.D786wAjiRr5/8ss2s0BYvkcyMNXF.', '413401467', '413401443@m365.fju.edu.tw'),
(8, '李碩珉', 'user', '$2y$10$h6JCHkDVEJnMnl6xD4IoUepVwaN.oLADihqgcqJK9G9Zf4LAsMGwC', '413401443', '11035053@st1.ymsh.edu.tw');

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
  MODIFY `record_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `items`
--
ALTER TABLE `items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
