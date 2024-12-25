-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 21, 2024 at 08:48 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `quiz_game`
--

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `answer_id` int(11) NOT NULL,
  `question_id` int(11) DEFAULT NULL,
  `answer_text` text NOT NULL,
  `is_correct` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `answers`
--

INSERT INTO `answers` (`answer_id`, `question_id`, `answer_text`, `is_correct`) VALUES
(1, 1, 'Berlin', 0),
(2, 1, 'Paris', 1),
(3, 1, 'Madrid', 0),
(4, 1, 'Rome', 0),
(5, 2, 'Earth', 0),
(6, 2, 'Jupiter', 1),
(7, 2, 'Mars', 0),
(8, 2, 'Venus', 0),
(9, 3, 'Charles Dickens', 0),
(10, 3, 'William Shakespeare', 1),
(11, 3, 'Jane Austen', 0),
(12, 3, 'Mark Twain', 0),
(13, 4, 'Amazon River', 0),
(14, 4, 'Nile River', 1),
(15, 4, 'Yangtze River', 0),
(16, 4, 'Mississippi River', 0),
(17, 5, 'Asia', 0),
(18, 5, 'Africa', 1),
(19, 5, 'South America', 0),
(20, 5, 'Europe', 0),
(21, 6, 'Monaco', 0),
(22, 6, 'Vatican City', 1),
(23, 6, 'Malta', 0),
(24, 6, 'San Marino', 0),
(25, 7, 'O2', 0),
(26, 7, 'H2O', 1),
(27, 7, 'CO2', 0),
(28, 7, 'NaCl', 0),
(29, 8, 'Oxygen', 0),
(30, 8, 'Nitrogen', 1),
(31, 8, 'Carbon Dioxide', 0),
(32, 8, 'Hydrogen', 0),
(33, 9, '300,000 km/s', 1),
(34, 9, '150,000 km/s', 0),
(35, 9, '400,000 km/s', 0),
(36, 9, '250,000 km/s', 0),
(37, 10, 'Abraham Lincoln', 0),
(38, 10, 'George Washington', 1),
(39, 10, 'Thomas Jefferson', 0),
(40, 10, 'John Adams', 0),
(41, 11, '1945', 1),
(42, 11, '1939', 0),
(43, 11, '1942', 0),
(44, 11, '1950', 0),
(45, 12, 'Margaret Thatcher', 1),
(46, 12, 'Angela Merkel', 0),
(47, 12, 'Queen Elizabeth II', 0),
(48, 12, 'Hillary Clinton', 0),
(49, 13, 'Germany', 0),
(50, 13, 'France', 1),
(51, 13, 'Brazil', 0),
(52, 13, 'Spain', 0),
(53, 14, '5', 1),
(54, 14, '6', 0),
(55, 14, '7', 0),
(56, 14, '8', 0),
(57, 15, 'Michael Phelps', 1),
(58, 15, 'Usain Bolt', 0),
(59, 15, 'Carl Lewis', 0),
(60, 15, 'Mark Spitz', 0),
(61, 16, 'Steven Spielberg', 0),
(62, 16, 'James Cameron', 1),
(63, 16, 'Christopher Nolan', 0),
(64, 16, 'Peter Jackson', 0),
(65, 17, 'Chris Evans', 0),
(66, 17, 'Robert Downey Jr.', 1),
(67, 17, 'Chris Hemsworth', 0),
(68, 17, 'Mark Ruffalo', 0),
(69, 18, 'Avatar', 1),
(70, 18, 'Avengers: Endgame', 0),
(71, 18, 'Titanic', 0),
(72, 18, 'Star Wars: The Force Awakens', 0),
(73, 19, 'الإسكندرية', 0),
(74, 19, 'القاهرة', 1),
(75, 19, 'الأقصر', 0),
(76, 19, 'أسوان', 0),
(77, 20, '8', 1),
(78, 20, '7', 0),
(79, 20, '9', 0),
(80, 20, '10', 0),
(81, 21, 'تولستوي', 0),
(82, 21, 'فيكتور هوجو', 1),
(83, 21, 'نجيب محفوظ', 0),
(84, 21, 'أحمد شوقي', 0),
(85, 22, 'المحيط الهندي', 0),
(86, 22, 'المحيط الهادئ', 1),
(87, 22, 'المحيط الأطلسي', 0),
(88, 22, 'المحيط المتجمد الشمالي', 0),
(89, 23, 'نهر الأمازون', 0),
(90, 23, 'نهر النيل', 1),
(91, 23, 'نهر الدانوب', 0),
(92, 23, 'نهر المسيسيبي', 0),
(93, 24, 'آسيا', 0),
(94, 24, 'أوروبا', 1),
(95, 24, 'أفريقيا', 0),
(96, 24, 'أمريكا الجنوبية', 0),
(97, 25, 'H2O', 1),
(98, 25, 'O2', 0),
(99, 25, 'CO2', 0),
(100, 25, 'NaCl', 0),
(101, 26, 'الأمبير', 1),
(102, 26, 'الفولت', 0),
(103, 26, 'الواط', 0),
(104, 26, 'الأوم', 0),
(105, 27, '46', 1),
(106, 27, '48', 0),
(107, 27, '23', 0),
(108, 27, '24', 0),
(109, 28, 'محمد نجيب', 1),
(110, 28, 'جمال عبد الناصر', 0),
(111, 28, 'أنور السادات', 0),
(112, 28, 'حسني مبارك', 0),
(113, 29, '1798', 1),
(114, 29, '1801', 0),
(115, 29, '1799', 0),
(116, 29, '1800', 0),
(117, 30, 'فاتح الأندلس', 0),
(118, 30, 'قائد الحروب الصليبية', 1),
(119, 30, 'أمير المؤمنين', 0),
(120, 30, 'أول خليفة عباسي', 0),
(121, 31, '10', 0),
(122, 31, '11', 1),
(123, 31, '12', 0),
(124, 31, '13', 0),
(125, 32, 'الأرجنتين', 0),
(126, 32, 'أوروجواي', 1),
(127, 32, 'البرازيل', 0),
(128, 32, 'إيطاليا', 0),
(129, 33, 'كريستيانو رونالدو', 1),
(130, 33, 'بيليه', 0),
(131, 33, 'مارادونا', 0),
(132, 33, 'ليونيل ميسي', 0),
(133, 34, 'ستيفن سبيلبرغ', 0),
(134, 34, 'فرانسيس فورد كوبولا', 1),
(135, 34, 'جيمس كاميرون', 0),
(136, 34, 'كريستوفر نولان', 0),
(137, 35, 'Iron Man', 1),
(138, 35, 'Captain America', 0),
(139, 35, 'Thor', 0),
(140, 35, 'The Avengers', 0),
(141, 36, 'Avatar', 1),
(142, 36, 'Titanic', 0),
(143, 36, 'Avengers: Endgame', 0),
(144, 36, 'Star Wars', 0);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`) VALUES
(1, 'General Knowledge'),
(2, 'Geography'),
(3, 'Science'),
(4, 'History'),
(5, 'Sports'),
(6, 'Entertainment'),
(7, 'المعرفة العامة'),
(8, 'الجغرافيا'),
(9, 'العلوم'),
(10, 'التاريخ'),
(11, 'الرياضة'),
(12, 'الترفيه');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `question_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `question_text` text NOT NULL,
  `points` int(11) DEFAULT 10,
  `difficulty` enum('Easy','Medium','Hard') DEFAULT NULL,
  `time_limit` int(11) DEFAULT 30,
  `hint` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`question_id`, `category_id`, `question_text`, `points`, `difficulty`, `time_limit`, `hint`) VALUES
(1, 1, 'What is the capital of France?', 10, 'Easy', 10, NULL),
(2, 1, 'Which is the largest planet in our solar system?', 10, 'Medium', 10, NULL),
(3, 1, 'Who wrote \"Romeo and Juliet\"?', 10, 'Medium', 10, NULL),
(4, 2, 'What is the longest river in the world?', 10, 'Medium', 10, NULL),
(5, 2, 'Which continent is the Sahara Desert located in?', 10, 'Easy', 10, NULL),
(6, 2, 'What is the smallest country in the world?', 10, 'Hard', 10, NULL),
(7, 3, 'What is the chemical symbol for water?', 10, 'Easy', 10, NULL),
(8, 3, 'Which gas is most abundant in Earth’s atmosphere?', 10, 'Medium', 10, NULL),
(9, 3, 'What is the speed of light?', 20, 'Hard', 10, NULL),
(10, 4, 'Who was the first President of the United States?', 10, 'Easy', 10, NULL),
(11, 4, 'What year did World War II end?', 10, 'Medium', 10, NULL),
(12, 4, 'Who was known as the Iron Lady?', 10, 'Medium', 10, NULL),
(13, 5, 'Which country won the FIFA World Cup in 2018?', 10, 'Medium', 10, NULL),
(14, 5, 'How many players are there in a basketball team?', 10, 'Easy', 10, NULL),
(15, 5, 'Who holds the record for the most Olympic gold medals?', 20, 'Hard', 10, NULL),
(16, 6, 'Who directed \"Titanic\"?', 10, 'Easy', 10, NULL),
(17, 6, 'Which actor played Iron Man in the Marvel movies?', 10, 'Easy', 10, NULL),
(18, 6, 'What is the highest-grossing film of all time?', 20, 'Hard', 10, NULL),
(19, 7, 'ما هي عاصمة مصر؟', 10, 'Easy', 10, NULL),
(20, 7, 'كم عدد الكواكب في النظام الشمسي؟', 10, 'Medium', 10, NULL),
(21, 7, 'من هو مؤلف رواية \"البؤساء\"؟', 10, 'Hard', 10, NULL),
(22, 8, 'ما هو أكبر محيط في العالم؟', 10, 'Easy', 10, NULL),
(23, 8, 'ما هو أطول نهر في العالم؟', 10, 'Medium', 10, NULL),
(24, 8, 'في أي قارة تقع جبال الألب؟', 10, 'Easy', 10, NULL),
(25, 9, 'ما هو رمز الماء الكيميائي؟', 10, 'Easy', 10, NULL),
(26, 9, 'ما هي وحدة قياس التيار الكهربائي؟', 10, 'Medium', 10, NULL),
(27, 9, 'كم عدد الكروموسومات في جسم الإنسان؟', 10, 'Medium', 10, NULL),
(28, 10, 'من كان أول رئيس لجمهورية مصر العربية؟', 10, 'Easy', 10, NULL),
(29, 10, 'في أي عام بدأ الحملة الفرنسية على مصر؟', 10, 'Medium', 10, NULL),
(30, 10, 'من هو صلاح الدين الأيوبي؟', 10, 'Medium', 10, NULL),
(31, 11, 'كم عدد لاعبي فريق كرة القدم؟', 10, 'Easy', 10, NULL),
(32, 11, 'ما هي أول دولة استضافت كأس العالم؟', 10, 'Medium', 10, NULL),
(33, 11, 'من هو اللاعب الأكثر تسجيلاً للأهداف في التاريخ؟', 10, 'Hard', 10, NULL),
(34, 12, 'من هو مخرج فيلم \"الأب الروحي\"؟', 10, 'Hard', 10, NULL),
(35, 12, 'ما هو أول فيلم في عالم مارفل السينمائي؟', 10, 'Medium', 10, NULL),
(36, 12, 'ما هو أعلى فيلم تحقيقاً للإيرادات؟', 10, 'Hard', 10, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `room_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL DEFAULT 1,
  `question_count` int(11) NOT NULL DEFAULT 10,
  `question_time` int(11) NOT NULL DEFAULT 30,
  `user_count` int(11) NOT NULL DEFAULT 0,
  `start_time` timestamp NULL DEFAULT NULL
) ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `room_id` int(11) NOT NULL,
  `score` int(11) NOT NULL DEFAULT 0,
  `ready` tinyint(1) NOT NULL DEFAULT 0,
  `current_question_id` int(11) DEFAULT NULL,
  `question_timeout` timestamp NULL DEFAULT NULL,
  `finished` tinyint(1) NOT NULL DEFAULT 0,
  `last_active` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`answer_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_id`),
  ADD KEY `fk_question_id` (`question_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `current_question_id` (`current_question_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `answer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=252;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`question_id`);

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `fk_question_id` FOREIGN KEY (`question_id`) REFERENCES `questions` (`question_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`current_question_id`) REFERENCES `questions` (`question_id`);
COMMIT;
