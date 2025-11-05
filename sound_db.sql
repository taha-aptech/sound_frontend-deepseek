-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 05, 2025 at 05:03 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sound_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'huma', 'huma@gmail.com', '$2y$10$uQ8.W94Jhvvq8vjP/83xYO47fzgRQ15XLnpRuSZILFNrPy8pLilCe', '2025-10-19 18:44:46'),
(2, 'ali ahmed', 'ali@gmail.com', '$2y$10$zw3pNJwFqHeHPBZvZZRhE.4XpX4RegwdqwzGlOTVG.Zy0Tl.QWMY2', '2025-10-19 19:33:11'),
(4, 'ayesha', 'ayesha@gmail.com', '$2y$10$J7MVADex8A5ZbSxaF1O6v.yhqozDeXA1yyizqxD69PR9PH.yzF0oG', '2025-10-19 21:48:20'),
(12, 'admin1', 'admin1@gmail.com', '$2y$10$RLyyGexRY8yLvlTeRJmsK.hZwXwvEfcIBzZio7Yw3oTQ0yrw6Fqgq', '2025-11-02 03:23:34');

-- --------------------------------------------------------

--
-- Table structure for table `album`
--

CREATE TABLE `album` (
  `album_id` int(11) NOT NULL,
  `album_name` varchar(100) NOT NULL,
  `release_year` year(4) NOT NULL,
  `description` text DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `album`
--

INSERT INTO `album` (`album_id`, `album_name`, `release_year`, `description`, `cover_image`) VALUES
(1, 'Hits of 2025', '2025', 'A collection of the most popular and trending songs of 2025 — featuring top chart hits, viral music, and unforgettable melodies from this year’s biggest artists.', 'images\\album\\01.jpeg'),
(2, 'Romantic Vibes 2025', '2025', 'Fall in love with the sweetest tunes of 2025. This album brings together soulful romantic tracks that touch the heart and set the perfect mood for every moment.', 'images\\album\\song.jpg'),
(3, 'Dance Revolution 2025', '2025', 'Get ready to move! Dance Revolution 2025 is packed with high-energy beats, club anthems, and party hits that will make you groove all night long.', 'images\\album\\02.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `artist`
--

CREATE TABLE `artist` (
  `artist_id` int(11) NOT NULL,
  `artist_name` varchar(100) NOT NULL,
  `artist_image` varchar(255) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `artist`
--

INSERT INTO `artist` (`artist_id`, `artist_name`, `artist_image`, `country`, `description`) VALUES
(1, 'Arijit Singh', 'images\\artist\\8.jpeg', 'India', 'Famous Bollywood playback singer known for romantic songs'),
(2, 'Ed Sheeran', 'images\\artist\\1.jpeg', 'UK', 'World-renowned English pop singer and songwriter'),
(3, 'Ali Sethi', 'images\\artist\\5.jpeg', 'Pakistan', 'Pakistani singer famous for the hit song Pasoori'),
(4, 'The Weeknd', 'images\\artist\\6.jpeg', 'Canada', 'Canadian pop and R&B artist'),
(5, 'Atif Aslam', 'images\\artist\\4.jpeg', 'Pakistan', 'Popular Pakistani singer and actor known for soulful vocals'),
(9, 'Ali Zafar', 'images\\artist\\3.jpeg', 'Pakistan', 'Pop and rock artist famous'),
(10, 'Momina Mustehsan', 'images\\artist\\7.jpeg', 'Pakistan', 'Singer and songwriter'),
(11, 'Rahat Fateh Ali Khan', 'images\\artist\\2.jpeg', 'Pakistan', 'Legendary Pakistani singer known for Qawwali, ghazals, and soulful playback songs.');

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `interested_in_production` tinyint(1) DEFAULT 0,
  `updates_subscription` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`id`, `name`, `email`, `subject`, `message`, `interested_in_production`, `updates_subscription`, `created_at`) VALUES
(1, 'huma', 'huma@gmail.com', 'good', 'improve', 1, 1, '2025-11-04 12:25:27'),
(2, 'afifa fatima', 'afifa@gmail.com', 'good', 'goood', 1, 1, '2025-11-04 12:31:41');

-- --------------------------------------------------------

--
-- Table structure for table `genre`
--

CREATE TABLE `genre` (
  `genre_id` int(11) NOT NULL,
  `genre_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `genre`
--

INSERT INTO `genre` (`genre_id`, `genre_name`) VALUES
(4, 'Hip-Hop'),
(1, 'Pop'),
(2, 'Rock'),
(3, 'Romantic');

-- --------------------------------------------------------

--
-- Table structure for table `language`
--

CREATE TABLE `language` (
  `language_id` int(11) NOT NULL,
  `language_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `language`
--

INSERT INTO `language` (`language_id`, `language_name`) VALUES
(2, 'English'),
(3, 'Punjabi'),
(1, 'Urdu');

-- --------------------------------------------------------

--
-- Table structure for table `music`
--

CREATE TABLE `music` (
  `music_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `artist_id` int(11) NOT NULL,
  `album_id` int(11) DEFAULT NULL,
  `genre_id` int(11) DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL,
  `year` year(4) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `thumbnail_img` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_new` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `music`
--

INSERT INTO `music` (`music_id`, `title`, `artist_id`, `album_id`, `genre_id`, `language_id`, `year`, `file_path`, `thumbnail_img`, `description`, `is_new`, `created_at`) VALUES
(14, 'ankhy', 11, 1, 1, 1, '2025', 'music\\ankhy.mp3', 'images\\thumbnail_img\\9.jpg', 'New romantic hit of 2025 by Rahat Fateh Ali Khan', 1, '2025-10-16 19:57:54'),
(15, 'Dil Ki Baat', 5, 2, 1, 1, '2025', 'music\\tere bin.mp3', 'images\\thumbnail_img\\10.jpg', 'Heart-touching romantic track with soft vocals by Atif Aslam', 1, '2025-10-16 19:57:54'),
(16, 'Raat bhar', 1, 3, 1, 1, '2025', 'music\\raat bhar.mp3', NULL, 'romantic song about waiting for love by Arijit Singh', 1, '2025-10-16 19:57:54'),
(17, 'Ranjish Hi Sahi|', 3, NULL, 1, 1, '2025', 'music\\ali sithi.mp3', NULL, 'Motivational romantic melody by Ali Sethi', 1, '2025-10-16 19:57:54'),
(18, 'pahli si mohbat', 9, NULL, 1, 1, '2025', 'music\\Ali Zafar  Pehli Si Muhabbat  Unplugged  Official Video.mp3', NULL, 'Soft romantic song under moonlight by Ali Zafar', 1, '2025-10-16 19:57:54'),
(19, 'shape of you', 2, NULL, 1, 1, '2025', 'music\\shape of you.mp3', NULL, 'Beautiful duet filled with love and emotions by Ed Sheeran', 1, '2025-10-16 19:57:54');

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

CREATE TABLE `rating` (
  `rating_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content_type` enum('music','video') NOT NULL,
  `content_id` int(11) NOT NULL,
  `rating_value` tinyint(4) NOT NULL CHECK (`rating_value` between 1 and 5),
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rating`
--

INSERT INTO `rating` (`rating_id`, `user_id`, `content_type`, `content_id`, `rating_value`, `created_at`) VALUES
(7, 1, 'video', 39, 2, '2025-10-30 11:24:38'),
(18, 14, 'music', 14, 3, '2025-11-01 04:05:00'),
(19, 14, 'music', 15, 2, '2025-11-01 04:04:47'),
(20, 14, 'music', 17, 3, '2025-11-01 04:11:19'),
(21, 14, 'video', 40, 2, '2025-11-01 04:13:04'),
(22, 14, 'video', 41, 2, '2025-11-01 04:13:09'),
(23, 14, 'video', 42, 5, '2025-11-01 04:13:16'),
(24, 14, 'video', 45, 3, '2025-11-01 04:13:29'),
(25, 14, 'video', 44, 4, '2025-11-01 04:13:32'),
(26, 14, 'video', 15, 5, '2025-11-01 04:13:36'),
(27, 15, 'music', 16, 3, '2025-11-04 19:15:55'),
(28, 15, 'music', 19, 2, '2025-11-01 22:22:35'),
(29, 15, 'music', 14, 3, '2025-11-02 23:44:20'),
(30, 15, 'music', 18, 2, '2025-11-04 19:16:33');

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content_type` enum('music','video') NOT NULL,
  `content_id` int(11) NOT NULL,
  `review_text` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`review_id`, `user_id`, `content_type`, `content_id`, `review_text`, `created_at`, `updated_at`) VALUES
(27, 15, 'music', 14, 'good', '2025-11-04 19:13:26', '2025-11-04 19:13:26'),
(29, 15, 'music', 14, 'good', '2025-11-04 19:13:26', '2025-11-04 19:13:26'),
(38, 15, 'music', 14, 'excellent', '2025-11-04 19:15:30', '2025-11-04 19:15:30'),
(39, 15, 'music', 18, 'nice', '2025-11-04 19:18:00', '2025-11-04 19:18:00'),
(40, 15, 'video', 39, 'tttt', '2025-11-04 19:25:14', '2025-11-04 19:25:14'),
(41, 15, 'video', 26, 'kkk', '2025-11-04 19:30:06', '2025-11-04 19:30:06'),
(42, 15, 'video', 27, 'forever your', '2025-11-04 19:30:58', '2025-11-04 19:30:58'),
(43, 15, 'video', 20, 'uu', '2025-11-04 19:37:15', '2025-11-04 19:37:15'),
(44, 15, 'video', 20, 'uu', '2025-11-04 19:37:19', '2025-11-04 19:37:19'),
(45, 15, 'video', 39, 'ttttttkkk', '2025-11-04 19:41:49', '2025-11-04 19:41:49'),
(46, 15, 'video', 39, 'llllljjfjgirgir', '2025-11-04 19:42:08', '2025-11-04 19:42:08'),
(47, 15, 'video', 39, 'llllljjfjgirgir', '2025-11-04 19:42:10', '2025-11-04 19:42:10');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `address` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `name`, `email`, `password`, `phone`, `address`, `created_at`) VALUES
(1, 'music_lover', '', 'lover@example.com', 'password123', '', '', '2025-01-01 10:00:00'),
(2, 'video_fan', '', 'fan@example.com', 'password123', '', '', '2025-01-02 11:00:00'),
(3, 'melody_master', '', 'master@example.com', 'password123', '', '', '2025-01-03 12:00:00'),
(4, 'beat_dropper', '', 'beat@example.com', 'password123', '', '', '2025-01-04 13:00:00'),
(5, 'rhythm_king', '', 'rhythm@example.com', 'password123', '', '', '2025-01-05 14:00:00'),
(6, 'song_bird', '', 'bird@example.com', 'password123', '', '', '2025-01-06 15:00:00'),
(7, 'dance_freak', '', 'dance@example.com', 'password123', '', '', '2025-01-07 16:00:00'),
(8, 'rock_star', '', 'rock@example.com', 'password123', '', '', '2025-01-08 17:00:00'),
(9, 'pop_queen', '', 'pop@example.com', 'password123', '', '', '2025-01-09 18:00:00'),
(10, 'jazz_night', '', 'jazz@example.com', 'password123', '', '', '2025-01-10 19:00:00'),
(12, 'ali@gmail.com', 'ali ahmed', 'ali@gmail.com', '$2y$10$Qpc6HenRCxEtWhM2AKn8vOPio86aYG.n49PhzhjQwnTT3QUOpHqJy', '0312456456', 'karachi', '2025-10-25 11:28:54'),
(13, 'ayesha@gmail.com', 'ayesha', 'ayesha@gmail.com', '$2y$10$OPEWp9l6xdn5EibzYHuiUeyxV68TxEcldAPSG.XOENMd/vUTaI1Xq', '0312456456', 'karachi', '2025-10-25 15:21:03'),
(14, 'aliza@gmail.com', 'aliza123', 'aliza@gmail.com', '$2y$10$JWIW9eysC3mr92.pFH51lO5g1j9qIZ1QVlIpy1wP0aVbKGRT5U4qO', '0312456456', 'karachi', '2025-10-25 15:28:11'),
(15, 'afifa@gmail.com', 'afifa fatima', 'afifa@gmail.com', '$2y$10$pdWObXaf/VEcl6oNAIMjEuK761ZBxhaZUTVbS9Vefb6ZI/cx.2Eq6', '0312456456', 'karachi', '2025-10-27 15:17:34'),
(16, 'fahad@gmail.com', 'fahad', 'fahad@gmail.com', '$2y$10$upPPTqOHFXrehTa8VXGibedL9ikzCMcx48mVAak4lOLp0jn3c9tXW', '0312456456', 'lahore', '2025-10-27 16:41:44'),
(17, 'fatima@gmail.com', 'fatima', 'fatima@gmail.com', '$2y$10$0nNbUs6xyadpcV477fq.jODeNjPWyQZ.mleRzzCnieYR21ZcsnuyO', '1234567890', 'karachi', '2025-10-27 18:14:34');

-- --------------------------------------------------------

--
-- Table structure for table `video`
--

CREATE TABLE `video` (
  `video_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `artist_id` int(11) NOT NULL,
  `album_id` int(11) DEFAULT NULL,
  `genre_id` int(11) DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL,
  `year` year(4) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `thumbnail_img` varchar(255) DEFAULT NULL,
  `is_new` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `video`
--

INSERT INTO `video` (`video_id`, `title`, `artist_id`, `album_id`, `genre_id`, `language_id`, `year`, `file_path`, `description`, `thumbnail_img`, `is_new`, `created_at`) VALUES
(1, 'Raat Bhar 2025', 1, 1, 1, 1, '2025', 'https://youtu.be/CXzkSIHmbpE?si=J8k4WwSAbU70iByb', 'New romantic hit of 2025 by Atif Aslam', 'images\\thumbnail_img\\2.jpg', 1, '2025-10-16 16:49:36'),
(2, 'Sajna Ve 2025', 2, 1, 1, 1, '2025', 'https://youtu.be/t16DWYwlg7Q?si=r8sbn0K7PetqbO2u', 'Ali Zafar latest pop track 2025', 'images\\thumbnail_img\\15.jpg', 1, '2025-10-16 16:49:36'),
(4, 'In My Zone 2025', 2, 1, 4, 2, '2025', 'https://youtu.be/5NeYFhZJHrA?si=UfvNURSKjrsRYmZE', 'English-Pop fusion by Ali Zafar', 'images\\thumbnail_img\\16.jpg', 1, '2025-10-16 16:49:36'),
(5, 'Tera Nasha 2025', 1, 1, 3, 1, '2025', 'https://youtu.be/_-f_IXNrRFE?si=5AyEZ-TZUmahpcng', 'Romantic Urdu single 2023', 'images\\thumbnail_img\\3.jpg', 1, '2025-10-16 16:49:36'),
(7, 'Tere Bin 2025', 1, 2, 1, 1, '2025', 'https://youtu.be/68e9h8_HLvE?si=Oa0-twX-4xyHt_ZN', 'Heart-touching romantic song by Atif Aslam', 'images\\thumbnail_img\\4.jpg', 1, '2025-10-18 08:14:08'),
(8, 'Rockstar Life 2025', 2, 2, 4, 2, '2025', 'https://youtu.be/LLKbtcwS6Ys?si=kuBz27HX-I482jtO', 'Ali Zafar drops an energetic English-Pop track', 'images\\thumbnail_img\\17.jpg', 1, '2025-10-18 08:14:08'),
(10, 'Shape of you', 2, 3, 2, 2, '2025', 'https://youtu.be/liTfD88dbCo?si=4HP3TdscQArPUQjM', 'English EDM track for party lovers', 'images\\thumbnail_img\\15.jpg', 1, '2025-10-18 08:14:08'),
(11, 'Khamoshi 2025', 1, 3, 1, 1, '2025', 'https://youtu.be/9_7l1V0nsIc?si=example5', 'A soulful Urdu melody with deep lyrics', 'images\\thumbnail_img\\5.jpg', 1, '2025-10-18 08:14:08'),
(12, 'Desi Beats 2025', 3, 3, 2, 1, '2025', 'https://youtu.be/jDZTJ3ZZvWk?si=example6', 'Fusion of Punjabi and EDM beats 2025', 'images\\thumbnail_img\\9.jpg', 1, '2025-10-18 08:14:08'),
(13, 'kabhi jo badal brasay', 1, 1, 3, 1, '2025', 'https://youtu.be/YWmKdKig_Pc?si=F3LoiAAZ2o3FbPWV', 'Romantic Punjabi hit 2025', 'images\\thumbnail_img\\6.jpg', 1, '2025-10-18 17:24:23'),
(15, 'O MaHiiii', 1, 1, 3, 1, '2025', 'https://youtu.be/Etkd-07gnxM?si=vddxm-DVBQX907p9', 'Soulful romantic song remake', 'images\\thumbnail_img\\7.jpg', 1, '2025-10-18 17:24:23'),
(16, 'Pyar Ka Ehsaas 2025', 3, 2, 3, 1, '2025', 'https://youtu.be/pyarkaehsaas', 'Beautiful love melody', 'images\\thumbnail_img\\1.jpg', 1, '2025-10-18 17:24:23'),
(18, 'Tere Hawaale ', 1, 2, 3, 1, '2025', 'https://youtu.be/FiENDQapd4g?si=CFEQDkLZWs2xx9k5', 'Soft romantic vibe', 'images\\thumbnail_img\\8.jpg', 1, '2025-10-18 17:24:23'),
(19, 'Janam Janam 2025', 3, 3, 3, 1, '2025', 'https://youtu.be/janamjanam', 'Bollywood-style romantic duet', 'images\\thumbnail_img\\17.jpg', 1, '2025-10-18 17:24:23'),
(20, 'Mere Dil Mein 2025', 1, 1, 3, 1, '2025', 'https://youtu.be/meredilmein', 'Touching romantic ballad', 'images\\thumbnail_img\\9.jpg', 1, '2025-10-18 17:24:23'),
(23, 'Heartbeat 2025', 4, 2, 1, 2, '2025', 'https://youtu.be/56PzWcO0D1g?si=example1', 'English pop single by The Weeknd', 'images\\thumbnail_img\\14.jpg', 1, '2025-10-18 17:15:00'),
(24, 'Love Story Reloaded', 5, 2, 1, 1, '2025', 'https://youtu.be/1uE1eI2-ksc?si=example2', 'Atif Aslam romantic-pop fusion 2025', 'images\\thumbnail_img\\18.webp', 1, '2025-10-18 17:15:00'),
(26, 'Sugar Rush', 4, 1, 1, 2, '2025', 'https://youtu.be/G2VJZkzN93g?si=example4', 'The Weeknd pop club track 2025', 'images\\thumbnail_img\\13.jpg', 1, '2025-10-18 17:15:00'),
(27, 'Forever Yours', 1, 2, 1, 1, '2025', 'https://youtu.be/3DqE-l-qp28?si=example5', 'Arijit Singh pop ballad with emotional lyrics', 'images\\thumbnail_img\\10.jpg', 1, '2025-10-18 17:15:00'),
(28, 'Echoes of Love', 9, 3, 1, 1, '2025', 'https://youtu.be/mA-4nCkA2fg?si=example6', 'Ali Zafar soft pop melody 2025', 'https://i.ytimg.com/vi/mA-4nCkA2fg/maxresdefault.jpg', 1, '2025-10-18 17:15:00'),
(29, 'Lost in You', 3, 1, 1, 1, '2025', 'https://youtu.be/kdRfE3E8LmA?si=example7', 'Ali Sethi contemporary Urdu pop track 2025', 'images\\thumbnail_img\\15.jpg', 1, '2025-10-18 17:15:00'),
(39, 'Dance Floor 2025', 3, NULL, 2, 2, '2025', 'https://youtu.be/cNGjD0VG4R8?si=zlVb95C1kMeuG5sI', 'English EDM/Rock track for party lovers', 'images\\thumbnail_img\\20.jpg', 1, '2025-10-18 17:30:00'),
(40, 'poshpoori', 1, NULL, 2, 1, '2025', 'https://youtu.be/fX41N940bMU?si=WgHZisNpmUKXWe7V', 'Arijit Singh Rock fusion single 2025', 'images\\thumbnail_img\\11.jpg', 1, '2025-10-18 17:30:00'),
(41, 'startboy', 4, NULL, 2, 2, '2025', 'https://youtu.be/xizN47Box_Y?si=0vvbvZv0nvxe212h', 'The Weeknd pop-rock energy track', 'images\\thumbnail_img\\1.jpg', 1, '2025-10-18 17:30:00'),
(42, 'Fire in the Sky', 5, NULL, 2, 1, '2025', 'https://youtu.be/XF2pL6w4kYw', 'Atif Aslam rock anthem 2025', 'https://i.ytimg.com/vi/XF2pL6w4kYw/maxresdefault.jpg', 1, '2025-10-18 17:30:00'),
(44, 'Guitar Dreams', 3, NULL, 2, 1, '2025', 'https://youtu.be/H1qE3W8pL9s', 'Ali Sethi rock ballad 2025', 'images\\thumbnail_img\\12.jpg', 1, '2025-10-18 17:30:00'),
(45, 'mashup', 1, NULL, 2, 1, '2025', 'https://youtu.be/c92tUshrZ9g?si=NuadsHpa26a3W8fz', 'Arijit Singh electric rock single 2025', 'images\\thumbnail_img\\14.jpg', 1, '2025-10-18 17:30:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `album`
--
ALTER TABLE `album`
  ADD PRIMARY KEY (`album_id`),
  ADD UNIQUE KEY `album_name` (`album_name`);

--
-- Indexes for table `artist`
--
ALTER TABLE `artist`
  ADD PRIMARY KEY (`artist_id`),
  ADD UNIQUE KEY `artist_name` (`artist_name`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `genre`
--
ALTER TABLE `genre`
  ADD PRIMARY KEY (`genre_id`),
  ADD UNIQUE KEY `genre_name` (`genre_name`);

--
-- Indexes for table `language`
--
ALTER TABLE `language`
  ADD PRIMARY KEY (`language_id`),
  ADD UNIQUE KEY `language_name` (`language_name`);

--
-- Indexes for table `music`
--
ALTER TABLE `music`
  ADD PRIMARY KEY (`music_id`),
  ADD KEY `artist_id` (`artist_id`),
  ADD KEY `album_id` (`album_id`),
  ADD KEY `genre_id` (`genre_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `rating`
--
ALTER TABLE `rating`
  ADD PRIMARY KEY (`rating_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `video`
--
ALTER TABLE `video`
  ADD PRIMARY KEY (`video_id`),
  ADD KEY `artist_id` (`artist_id`),
  ADD KEY `album_id` (`album_id`),
  ADD KEY `genre_id` (`genre_id`),
  ADD KEY `language_id` (`language_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `album`
--
ALTER TABLE `album`
  MODIFY `album_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `artist`
--
ALTER TABLE `artist`
  MODIFY `artist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `genre`
--
ALTER TABLE `genre`
  MODIFY `genre_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `language`
--
ALTER TABLE `language`
  MODIFY `language_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `music`
--
ALTER TABLE `music`
  MODIFY `music_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `rating`
--
ALTER TABLE `rating`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `video`
--
ALTER TABLE `video`
  MODIFY `video_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `music`
--
ALTER TABLE `music`
  ADD CONSTRAINT `music_ibfk_1` FOREIGN KEY (`artist_id`) REFERENCES `artist` (`artist_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `music_ibfk_2` FOREIGN KEY (`album_id`) REFERENCES `album` (`album_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `music_ibfk_3` FOREIGN KEY (`genre_id`) REFERENCES `genre` (`genre_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `music_ibfk_4` FOREIGN KEY (`language_id`) REFERENCES `language` (`language_id`) ON DELETE SET NULL;

--
-- Constraints for table `rating`
--
ALTER TABLE `rating`
  ADD CONSTRAINT `rating_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `video`
--
ALTER TABLE `video`
  ADD CONSTRAINT `video_ibfk_1` FOREIGN KEY (`artist_id`) REFERENCES `artist` (`artist_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `video_ibfk_2` FOREIGN KEY (`album_id`) REFERENCES `album` (`album_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `video_ibfk_3` FOREIGN KEY (`genre_id`) REFERENCES `genre` (`genre_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `video_ibfk_4` FOREIGN KEY (`language_id`) REFERENCES `language` (`language_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
