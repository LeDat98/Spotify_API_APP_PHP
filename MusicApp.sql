-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost
-- Thời gian đã tạo: Th1 17, 2024 lúc 05:40 AM
-- Phiên bản máy phục vụ: 10.4.28-MariaDB
-- Phiên bản PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `MusicApp`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `musicapp`
--

CREATE TABLE `musicapp` (
  `id` int(11) NOT NULL,
  `user_id` varchar(250) DEFAULT NULL,
  `songName` varchar(255) DEFAULT NULL,
  `artistName` varchar(255) DEFAULT NULL,
  `imageUrl` varchar(255) DEFAULT NULL,
  `uri` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `musicapp`
--

INSERT INTO `musicapp` (`id`, `user_id`, `songName`, `artistName`, `imageUrl`, `uri`) VALUES
(6, '1', 'Cảm ơn', 'Đen, Biên', 'https://i.scdn.co/image/ab67616d0000b27303a985dab5f8aa18b041d3d4', 'spotify:track:5ETwIvPHsVW569wYCZo7vw'),
(7, '1', 'Bài Này Chill Phết', 'Đen, MIN', 'https://i.scdn.co/image/ab67616d0000b273d00c33fac5712e26dbef6199', 'spotify:track:2nR51wakN5K3AJENqGaNg9'),
(8, '1', 'Lemon', 'Kenshi Yonezu', 'https://i.scdn.co/image/ab67616d0000b273775e8184725e0fb89337dd9a', 'spotify:track:04TshWXkhV1qkqHzf31Hn6'),
(10, '1', 'Treat You Better', 'Shawn Mendes', 'https://i.scdn.co/image/ab67616d0000b2731376b4b16f4bfcba02dc571b', 'spotify:track:3QGsuHI8jO1Rx4JWLUh9jd'),
(17, '1', 'Mùa Xuân Đầu Tiên (Lofi)', 'One Music, H2K, Quốc Lượng, Athena Music', 'https://i.scdn.co/image/ab67616d0000b27342ca01e24a0ed961ad579534', 'spotify:track:5eG6cystJ6fE2i6NGEQzhi');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `UserID` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `reset_token` varchar(64) DEFAULT NULL,
  `token_expiry` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`UserID`, `Password`, `Email`, `reset_token`, `token_expiry`) VALUES
('juhyeong', '$2y$10$0mDEgxTRfWcNF8usmnywZuMK7Bif0hWr.fl.zVquxXXwDsoz7mkDK', 'jpark922@naver.com', '1e9b88390d8db5a720ce81964952fb879f335c7b7b3034600a27208801271e9d', 1704936471),
('LeDucDat', '$2y$10$uhN.N/rgUTfzfiOqIF6ZrutTyc08EkY0Zad0tA1rA9zgYUFiTuWhq', 'leducdat971123@gmail.com', '9e2ce1e4a9bf3b77deaab9e1e3b1958742ab944637eccbfa7359c8416260a5ca', 1705458471);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `musicapp`
--
ALTER TABLE `musicapp`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `musicapp`
--
ALTER TABLE `musicapp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
