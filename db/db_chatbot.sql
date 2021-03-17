-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th3 17, 2021 lúc 02:00 AM
-- Phiên bản máy phục vụ: 10.4.14-MariaDB
-- Phiên bản PHP: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `db_chatbot`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_chat_group`
--

CREATE TABLE `tbl_chat_group` (
  `id` int(11) NOT NULL,
  `id_group` int(11) NOT NULL,
  `id_member` int(11) NOT NULL,
  `message` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_chat_group`
--

INSERT INTO `tbl_chat_group` (`id`, `id_group`, `id_member`, `message`, `created_on`) VALUES
(1, 12, 37, 'hello em', '2021-03-07 03:32:25'),
(2, 12, 38, 'hello anh', '2021-03-07 03:32:25'),
(3, 12, 37, 'hihi', '2021-03-07 04:53:37'),
(4, 12, 38, 'cười gì bạn', '2021-03-07 05:51:24'),
(5, 12, 37, 'thích thì cười nhé', '2021-03-07 05:51:33'),
(9, 13, 37, 'ádasdasda', '2021-03-07 06:46:13'),
(10, 12, 37, 'hi nè', '2021-03-08 14:22:49'),
(11, 12, 37, 'ádasdasdsada', '2021-03-08 14:29:48');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_chat_member`
--

CREATE TABLE `tbl_chat_member` (
  `id` int(11) NOT NULL,
  `id_member_one` int(11) NOT NULL,
  `id_member_two` int(11) NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_chat_member`
--

INSERT INTO `tbl_chat_member` (`id`, `id_member_one`, `id_member_two`, `message`, `created_on`) VALUES
(14, 37, 38, 'hello em yêu', '2021-03-06 01:30:04'),
(15, 38, 37, 'Chào anh yêu', '2021-03-06 01:30:13'),
(16, 38, 37, '<3', '2021-03-06 01:30:31'),
(17, 37, 38, 'hi em', '2021-03-06 01:36:48'),
(20, 38, 37, 'hi anh lý', '2021-03-08 14:22:26'),
(21, 37, 38, 'hi em nhi', '2021-03-08 14:22:32'),
(22, 37, 38, 'ádadasd', '2021-03-08 14:29:38');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_groups`
--

CREATE TABLE `tbl_groups` (
  `id` int(11) NOT NULL,
  `id_member_create` int(11) NOT NULL,
  `name_group` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `rule_group` enum('open','close') COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_groups`
--

INSERT INTO `tbl_groups` (`id`, `id_member_create`, `name_group`, `rule_group`) VALUES
(12, 37, 'Nhóm Tào Lao', 'open'),
(13, 37, 'xàm', 'open');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_members`
--

CREATE TABLE `tbl_members` (
  `id_member` int(11) NOT NULL,
  `name_member` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `email_member` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password_member` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `profile_member` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `status_member` enum('Disabled','Enable') COLLATE utf8_unicode_ci NOT NULL,
  `verification_code_member` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `login_status_member` enum('Logout','Login') COLLATE utf8_unicode_ci NOT NULL,
  `created_on_member` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_members`
--

INSERT INTO `tbl_members` (`id_member`, `name_member`, `email_member`, `password_member`, `profile_member`, `status_member`, `verification_code_member`, `login_status_member`, `created_on_member`) VALUES
(37, 'Trần Ngọc Lý', 'tnduy.16.06.1998@gmail.com', '6bf26647b70c39a7e748fc52a0a14efc', 'not found', 'Enable', '68bbf7aad99ac2d3475a434d322ee0dc', 'Login', '21-02-2021'),
(38, 'Nguyễn Thị Tuyết Nhi', 'lytran.work@gmail.com', '6bf26647b70c39a7e748fc52a0a14efc', 'not found', 'Enable', '3ead3044fe77210103510a3929727a68', 'Login', '21-02-2021'),
(43, 'Nguyễn Hoàng Phúc', 'tnly.16.06.1998@gmail.com', '6bf26647b70c39a7e748fc52a0a14efc', 'not found', 'Enable', '1a3c2dca188279ae1344b8798848f522', 'Login', '06-03-2021');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_room`
--

CREATE TABLE `tbl_room` (
  `id_room` int(11) NOT NULL,
  `id_member` int(11) NOT NULL,
  `message` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_room`
--

INSERT INTO `tbl_room` (`id_room`, `id_member`, `message`, `created_on`) VALUES
(24, 37, 'hi', '2021-03-08 14:22:12'),
(25, 37, 'hi em', '2021-03-08 14:27:58'),
(26, 37, 'hi', '2021-03-08 14:28:39'),
(27, 37, 'ádasdas', '2021-03-08 14:29:31');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `tbl_chat_group`
--
ALTER TABLE `tbl_chat_group`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_group` (`id_group`),
  ADD KEY `id_member` (`id_member`);

--
-- Chỉ mục cho bảng `tbl_chat_member`
--
ALTER TABLE `tbl_chat_member`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_member_one` (`id_member_one`),
  ADD KEY `id_member_two` (`id_member_two`);

--
-- Chỉ mục cho bảng `tbl_groups`
--
ALTER TABLE `tbl_groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_member_create` (`id_member_create`);

--
-- Chỉ mục cho bảng `tbl_members`
--
ALTER TABLE `tbl_members`
  ADD PRIMARY KEY (`id_member`);

--
-- Chỉ mục cho bảng `tbl_room`
--
ALTER TABLE `tbl_room`
  ADD PRIMARY KEY (`id_room`),
  ADD KEY `id_member` (`id_member`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `tbl_chat_group`
--
ALTER TABLE `tbl_chat_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `tbl_chat_member`
--
ALTER TABLE `tbl_chat_member`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT cho bảng `tbl_groups`
--
ALTER TABLE `tbl_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `tbl_members`
--
ALTER TABLE `tbl_members`
  MODIFY `id_member` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT cho bảng `tbl_room`
--
ALTER TABLE `tbl_room`
  MODIFY `id_room` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `tbl_chat_group`
--
ALTER TABLE `tbl_chat_group`
  ADD CONSTRAINT `tbl_chat_group_ibfk_1` FOREIGN KEY (`id_group`) REFERENCES `tbl_groups` (`id`),
  ADD CONSTRAINT `tbl_chat_group_ibfk_2` FOREIGN KEY (`id_member`) REFERENCES `tbl_members` (`id_member`);

--
-- Các ràng buộc cho bảng `tbl_chat_member`
--
ALTER TABLE `tbl_chat_member`
  ADD CONSTRAINT `tbl_chat_member_ibfk_1` FOREIGN KEY (`id_member_one`) REFERENCES `tbl_members` (`id_member`),
  ADD CONSTRAINT `tbl_chat_member_ibfk_2` FOREIGN KEY (`id_member_two`) REFERENCES `tbl_members` (`id_member`);

--
-- Các ràng buộc cho bảng `tbl_groups`
--
ALTER TABLE `tbl_groups`
  ADD CONSTRAINT `tbl_groups_ibfk_1` FOREIGN KEY (`id_member_create`) REFERENCES `tbl_members` (`id_member`);

--
-- Các ràng buộc cho bảng `tbl_room`
--
ALTER TABLE `tbl_room`
  ADD CONSTRAINT `tbl_room_ibfk_1` FOREIGN KEY (`id_member`) REFERENCES `tbl_members` (`id_member`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
