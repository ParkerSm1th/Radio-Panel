-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 26, 2026 at 03:48 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `panel_layout`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `name` varchar(1000) NOT NULL,
  `age` varchar(100) NOT NULL,
  `discord` varchar(100) NOT NULL,
  `region` varchar(100) NOT NULL,
  `role` int(11) NOT NULL,
  `mic` int(11) NOT NULL,
  `work` int(11) NOT NULL,
  `why` varchar(5000) NOT NULL,
  `ip` varchar(1000) NOT NULL,
  `times` varchar(1000) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `assigned` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blocked_ips`
--

CREATE TABLE `blocked_ips` (
  `id` int(11) NOT NULL,
  `ip` varchar(1000) NOT NULL,
  `reason` varchar(1000) NOT NULL,
  `times` varchar(1000) NOT NULL,
  `user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `connection_info`
--

CREATE TABLE `connection_info` (
  `id` int(11) NOT NULL,
  `ip` varchar(1000) NOT NULL,
  `port` varchar(1000) NOT NULL,
  `password` varchar(1000) NOT NULL,
  `mountpoint` varchar(1000) NOT NULL,
  `server` varchar(1000) NOT NULL,
  `url` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `name` varchar(1000) NOT NULL,
  `topic` varchar(1000) NOT NULL,
  `responseMethod` int(11) NOT NULL,
  `responseDetails` varchar(1000) NOT NULL,
  `message` varchar(5000) NOT NULL,
  `times` varchar(1000) NOT NULL,
  `ip` varchar(1000) NOT NULL,
  `assigned` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `global`
--

CREATE TABLE `global` (
  `id` int(11) NOT NULL,
  `setting` varchar(100) NOT NULL,
  `value` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `dj` int(11) NOT NULL,
  `times` varchar(1000) NOT NULL,
  `ip` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `listeners_logs`
--

CREATE TABLE `listeners_logs` (
  `id` int(11) NOT NULL,
  `times` varchar(1000) NOT NULL,
  `count` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `action` varchar(2000) NOT NULL,
  `user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nav_ranks`
--

CREATE TABLE `nav_ranks` (
  `id` int(11) NOT NULL,
  `name` varchar(1000) NOT NULL,
  `prefix` varchar(1000) NOT NULL,
  `permRole` varchar(1000) NOT NULL,
  `icon` varchar(1000) NOT NULL,
  `dev` int(11) NOT NULL,
  `radio` int(11) NOT NULL,
  `media` int(11) NOT NULL,
  `social` int(11) NOT NULL DEFAULT 0,
  `class` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `userID` varchar(5000) NOT NULL,
  `type` varchar(5000) NOT NULL,
  `header` varchar(5000) NOT NULL,
  `content` varchar(5000) NOT NULL,
  `icon` varchar(5000) NOT NULL,
  `active` varchar(5000) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `panel_log`
--

CREATE TABLE `panel_log` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `times` varchar(5000) NOT NULL,
  `action` varchar(5000) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `panel_pages`
--

CREATE TABLE `panel_pages` (
  `id` int(11) NOT NULL,
  `name` varchar(1000) NOT NULL,
  `nav_rank` varchar(1000) NOT NULL,
  `url` varchar(1000) NOT NULL,
  `position` int(11) NOT NULL,
  `dev` int(11) NOT NULL DEFAULT 1,
  `pending` int(11) NOT NULL DEFAULT 0,
  `redirect` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `points`
--

CREATE TABLE `points` (
  `id` int(11) NOT NULL,
  `value` int(11) NOT NULL,
  `user` varchar(1000) NOT NULL,
  `issued` varchar(1000) NOT NULL,
  `message` varchar(1000) NOT NULL,
  `title` varchar(1000) NOT NULL,
  `times` varchar(1000) NOT NULL,
  `type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `point_types`
--

CREATE TABLE `point_types` (
  `id` int(11) NOT NULL,
  `points` varchar(1000) NOT NULL,
  `type` varchar(1000) NOT NULL,
  `title` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post_away`
--

CREATE TABLE `post_away` (
  `id` int(11) NOT NULL,
  `user` varchar(1000) NOT NULL,
  `reason` varchar(1000) NOT NULL,
  `length` varchar(1000) NOT NULL,
  `status` varchar(1000) NOT NULL,
  `times` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `redirect`
--

CREATE TABLE `redirect` (
  `id` int(11) NOT NULL,
  `slug` varchar(14) NOT NULL,
  `url` varchar(620) NOT NULL,
  `date` datetime NOT NULL,
  `hits` bigint(20) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Used for the URL shortener';

-- --------------------------------------------------------

--
-- Table structure for table `reported_requests`
--

CREATE TABLE `reported_requests` (
  `id` int(11) NOT NULL,
  `dj` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `name` varchar(1000) NOT NULL,
  `artist` varchar(1000) NOT NULL,
  `song` varchar(1000) NOT NULL,
  `message` varchar(1000) NOT NULL,
  `ip` varchar(1000) NOT NULL,
  `times` varchar(1000) NOT NULL,
  `reported` int(11) NOT NULL,
  `timesReported` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `id` int(11) NOT NULL,
  `dj` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `name` varchar(1000) NOT NULL,
  `artist` varchar(1000) NOT NULL,
  `song` varchar(1000) NOT NULL,
  `message` varchar(1000) NOT NULL,
  `ip` varchar(1000) NOT NULL,
  `times` varchar(1000) NOT NULL,
  `deleted` int(11) NOT NULL,
  `reported` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `admin` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `content` varchar(1000) NOT NULL,
  `impro` varchar(1000) NOT NULL,
  `published` int(11) NOT NULL DEFAULT 0,
  `times` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `review_assignments`
--

CREATE TABLE `review_assignments` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `assigned` int(11) NOT NULL,
  `completed` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `searches`
--

CREATE TABLE `searches` (
  `id` int(11) NOT NULL,
  `query` varchar(1000) NOT NULL,
  `title` varchar(1000) NOT NULL,
  `url` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` int(11) NOT NULL,
  `user` varchar(1000) NOT NULL,
  `session` varchar(1000) NOT NULL,
  `page` varchar(1000) NOT NULL,
  `times` varchar(1000) NOT NULL,
  `logout` int(11) NOT NULL DEFAULT 0,
  `refresh` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `song_log`
--

CREATE TABLE `song_log` (
  `id` int(11) NOT NULL,
  `title` varchar(5000) NOT NULL,
  `artist` varchar(5000) NOT NULL,
  `dj` varchar(5000) NOT NULL,
  `dj_name` varchar(5000) NOT NULL,
  `times` varchar(5000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `timetable`
--

CREATE TABLE `timetable` (
  `id` int(11) NOT NULL,
  `timestart` varchar(500) NOT NULL,
  `timeend` varchar(500) NOT NULL,
  `booked` varchar(500) NOT NULL,
  `booked_type` varchar(500) NOT NULL,
  `day` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tweets`
--

CREATE TABLE `tweets` (
  `id` int(11) NOT NULL,
  `content` varchar(2000) NOT NULL,
  `user` varchar(1000) NOT NULL,
  `times` varchar(1000) NOT NULL,
  `twitter_id` varchar(1000) NOT NULL,
  `deleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(1000) NOT NULL,
  `pass` varchar(5000) NOT NULL,
  `avatarURL` varchar(5000) NOT NULL,
  `permRole` varchar(5000) NOT NULL,
  `displayRole` varchar(5000) NOT NULL,
  `radio` varchar(5000) NOT NULL,
  `media` varchar(5000) NOT NULL,
  `social` int(11) NOT NULL DEFAULT 0,
  `developer` varchar(5000) NOT NULL,
  `pending` int(11) NOT NULL DEFAULT 0,
  `guest` varchar(10) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT 0,
  `lastLogin` varchar(5000) NOT NULL,
  `lastLoginIP` varchar(100) NOT NULL,
  `newIP` varchar(100) NOT NULL,
  `inactive` varchar(5000) NOT NULL,
  `hired` varchar(5000) NOT NULL,
  `region` varchar(5000) NOT NULL,
  `discord` varchar(5000) NOT NULL,
  `discord_id` varchar(100) NOT NULL,
  `trial` varchar(2) NOT NULL,
  `djSays` varchar(100) NOT NULL,
  `bio` varchar(3000) NOT NULL,
  `viewed_info` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

CREATE TABLE `perm_shows` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(1000) NOT NULL,
  `time` varchar(1000) NOT NULL,
  `hosts` varchar(5000) NOT NULL,
  `bio` varchar(5000) NOT NULL,
  `coverURL` varchar(5000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `warnings`
--

CREATE TABLE `warnings` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `reason` varchar(5000) NOT NULL,
  `points` int(11) NOT NULL,
  `op` int(11) NOT NULL,
  `times` varchar(5000) NOT NULL,
  `deleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blocked_ips`
--
ALTER TABLE `blocked_ips`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `connection_info`
--
ALTER TABLE `connection_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `global`
--
ALTER TABLE `global`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `listeners_logs`
--
ALTER TABLE `listeners_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nav_ranks`
--
ALTER TABLE `nav_ranks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `panel_log`
--
ALTER TABLE `panel_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `panel_pages`
--
ALTER TABLE `panel_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `points`
--
ALTER TABLE `points`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `point_types`
--
ALTER TABLE `point_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `post_away`
--
ALTER TABLE `post_away`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `redirect`
--
ALTER TABLE `redirect`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reported_requests`
--
ALTER TABLE `reported_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `review_assignments`
--
ALTER TABLE `review_assignments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `searches`
--
ALTER TABLE `searches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `song_log`
--
ALTER TABLE `song_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timetable`
--
ALTER TABLE `timetable`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tweets`
--
ALTER TABLE `tweets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `perm_shows`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `warnings`
--
ALTER TABLE `warnings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blocked_ips`
--
ALTER TABLE `blocked_ips`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `connection_info`
--
ALTER TABLE `connection_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `global`
--
ALTER TABLE `global`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `listeners_logs`
--
ALTER TABLE `listeners_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nav_ranks`
--
ALTER TABLE `nav_ranks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `panel_log`
--
ALTER TABLE `panel_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `panel_pages`
--
ALTER TABLE `panel_pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `points`
--
ALTER TABLE `points`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `point_types`
--
ALTER TABLE `point_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `post_away`
--
ALTER TABLE `post_away`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `redirect`
--
ALTER TABLE `redirect`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reported_requests`
--
ALTER TABLE `reported_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `review_assignments`
--
ALTER TABLE `review_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `searches`
--
ALTER TABLE `searches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `song_log`
--
ALTER TABLE `song_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `timetable`
--
ALTER TABLE `timetable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tweets`
--
ALTER TABLE `tweets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `perm_shows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `warnings`
--
ALTER TABLE `warnings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
