-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 12, 2025 at 11:54 AM
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
-- Database: `church_site`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `description`, `image`) VALUES
(1, 'Research on the Book of Revelation \"The Revelation of Jesus Christ\"', 'Bishop. Shinji Amari Ph.D', 'The Book of Revelation requires a broad range of understanding regarding the Geography, History both ancient and Later, and prophecies throughout the entire Bible. \r\n\r\nThis Book \"The Revelation of Jesus Christ\" will help us to understand correctly the Book of Revelation based on proper biblical doctrine, especially on sound Missionary Baptist Faith.', '1765520056_books.jfif');

-- --------------------------------------------------------

--
-- Table structure for table `churches`
--

CREATE TABLE `churches` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `location` varchar(150) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `contact` varchar(100) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `website` varchar(255) NOT NULL,
  `instagram` varchar(100) NOT NULL,
  `pastor` varchar(150) NOT NULL,
  `facebook` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `churches`
--

INSERT INTO `churches` (`id`, `name`, `location`, `image`, `description`, `contact`, `email`, `website`, `instagram`, `pastor`, `facebook`) VALUES
(1, 'Faith Baptist Church', '123 Main Street, Quezon City', 'images/church1.png\" \r\n', 'Faith Baptist Church is a welcoming community...', NULL, '', '', '', '', ''),
(2, 'Grace Community Church', '45 Baybay Road, Cebu City', 'images/church2.png', 'Grace Community Church brings people together...', '09458738852', '', '', '', '', ''),
(3, 'Hope Christian Church', '89 Sunrise Ave, Davao City', 'images/church3.png', 'Hope Christian Church provides a place of renewal...', '09458738852', '', '', '', 'Jamaine Junio', 'https://www.facebook.com/saltandlightmbm'),
(6, 'Sanctuary Missionary Baptist Mission', 'Missionary Baptist Theological School', 'images/download__1_.jfif', '', '09123471392', '', '', '', 'Yolito de Gracia', 'https://www.facebook.com/search/top/?q=sanctuary%20missionary%20baptist%20mission'),
(7, 'Solid Rock Missionary Baptist Church', 'Golden Horizon, Trece Martirez, Cavite', '', 'Mother Church:\\r\\nFirst Abiko Missionary Baptist Church', '09684023571', '', '', '', 'Ptr. Ricky N. Acuyong', ''),
(8, 'Christ\\\'s Flock Missionary Baptist Mission', '#32 Catalino Cruz Street Malasakit, Pinagbuhatan Pasig City', '', 'Missionary Pastor\\r\\nMother Church:\\r\\nAbiko Japan', '09105674866', '', '', '', 'Ptr. Ryan Jay D. Almonte', ''),
(9, 'First Landmark Missionary Baptist Church', 'Blk. 6 Lot 29 Sampaguita St. Elysian Homes Phase 2 ', '', 'Mother Church:\\r\\nLandmark Missionary Baptist Church ', '09178655902', '', '', '', 'Ptr. Edwin S. Edep', ''),
(10, 'Perpetual Light Missionary Baptist Church', 'Lot 08 Blk. 14 A Cavisgado Palmera', '', 'Mother Church:\\r\\nLight MBM', '09058325961', '', '', '', 'Ptr. Mar Bynner M. Celin', '');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `message`, `created_at`) VALUES
(1, 'lance', 'lancepatrickdeleon2004@gmail.com', 'Need to find nearest church ', '2025-11-06 17:38:08');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `date`, `description`, `image`) VALUES
(1, 'Sportfest ', '2025-12-20', 'Kampi Sportfest', 'events/image.png'),
(2, 'MBTS Graduation', '2026-03-27', 'Seminarian Graduation 2025-2026\r\nTheme: \r\nSpeaker: ', 'events/family.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `pastors`
--

CREATE TABLE `pastors` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `bio` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `sermon` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `church_id` int(11) DEFAULT NULL,
  `sermon_link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pastors`
--

INSERT INTO `pastors` (`id`, `name`, `title`, `bio`, `image`, `sermon`, `email`, `facebook`, `church_id`, `sermon_link`) VALUES
(2, 'Yolito de Gracia', 'Pastor Of Sanctuary MBC', 'Missionary Pastor In Sanctuary MBM\r\n', 'images/pastor.jfif', NULL, '', 'https://www.facebook.com/ptr.tingdegracia?_rdc=1&_rdr#', 1, 'https://sermoncentral.com/contributors/pastor-yolito-de-gracia-profile-22411'),
(3, 'Andrew Dado', 'Pastor Of Salt & Light MBC', '', 'images/download.jfif', NULL, '', 'https://www.facebook.com/andrew.dado.3', 1, ''),
(5, 'Bishop Shinji Amari Ph. D', 'Senior Pastor of Abiko Baptist Church Japan', 'President of MBTS \r\nDoctor of Missions \r\nAuthor \r\n', 'images/pastor1.jpg', NULL, '', '', 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sermons`
--

CREATE TABLE `sermons` (
  `id` int(11) NOT NULL,
  `pastor_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `link` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sermons`
--

INSERT INTO `sermons` (`id`, `pastor_id`, `title`, `file`, `uploaded_at`, `link`) VALUES
(1, 1, 'God\'s Grace and Protection', 'sermons/10.26.25.pptx', '2025-11-06 16:35:07', ''),
(2, 2, 'God\'s Grace and Protection', '', '2025-12-11 16:35:09', 'https://sermoncentral.com/contributors/pastor-yolito-de-gracia-profile-22411');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `churches`
--
ALTER TABLE `churches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pastors`
--
ALTER TABLE `pastors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sermons`
--
ALTER TABLE `sermons`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `churches`
--
ALTER TABLE `churches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pastors`
--
ALTER TABLE `pastors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sermons`
--
ALTER TABLE `sermons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
