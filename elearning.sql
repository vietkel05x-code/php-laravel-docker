-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 16, 2025 at 04:03 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `elearning`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-youtube_duration_8i4HE0dMSXU', 'a:4:{s:8:\"duration\";i:15;s:7:\"seconds\";i:896;s:9:\"formatted\";s:5:\"14:56\";s:8:\"video_id\";s:11:\"8i4HE0dMSXU\";}', 1763386819),
('laravel-cache-youtube_duration_8Pqg0IGN02g', 'a:4:{s:8:\"duration\";i:11;s:7:\"seconds\";i:639;s:9:\"formatted\";s:5:\"10:39\";s:8:\"video_id\";s:11:\"8Pqg0IGN02g\";}', 1763384560),
('laravel-cache-youtube_duration_B7sKfmKlNU8', 'a:4:{s:8:\"duration\";i:26;s:7:\"seconds\";i:1515;s:9:\"formatted\";s:5:\"25:15\";s:8:\"video_id\";s:11:\"B7sKfmKlNU8\";}', 1763312319),
('laravel-cache-youtube_duration_clJJXlBzYhk', 'a:4:{s:8:\"duration\";i:30;s:7:\"seconds\";i:1790;s:9:\"formatted\";s:5:\"29:50\";s:8:\"video_id\";s:11:\"clJJXlBzYhk\";}', 1763386820),
('laravel-cache-youtube_duration_dQw4w9WgXcQ', 'a:4:{s:8:\"duration\";i:4;s:7:\"seconds\";i:214;s:9:\"formatted\";s:4:\"3:34\";s:8:\"video_id\";s:11:\"dQw4w9WgXcQ\";}', 1763387257),
('laravel-cache-youtube_duration_Hc5edo4R5Oc', 'a:4:{s:8:\"duration\";i:6;s:7:\"seconds\";i:312;s:9:\"formatted\";s:4:\"5:12\";s:8:\"video_id\";s:11:\"Hc5edo4R5Oc\";}', 1763386818),
('laravel-cache-youtube_duration_Hqmbo0ROBQw', 'a:4:{s:8:\"duration\";i:6;s:7:\"seconds\";i:315;s:9:\"formatted\";s:4:\"5:15\";s:8:\"video_id\";s:11:\"Hqmbo0ROBQw\";}', 1763317269),
('laravel-cache-youtube_duration_IADhKnmQMtk', 'a:4:{s:8:\"duration\";i:6;s:7:\"seconds\";i:345;s:9:\"formatted\";s:4:\"5:45\";s:8:\"video_id\";s:11:\"IADhKnmQMtk\";}', 1763312132),
('laravel-cache-youtube_duration_lMKfZwnHzig', 'a:4:{s:8:\"duration\";i:5;s:7:\"seconds\";i:272;s:9:\"formatted\";s:4:\"4:32\";s:8:\"video_id\";s:11:\"lMKfZwnHzig\";}', 1763312170),
('laravel-cache-youtube_duration_nyw-cXXwk1s', 'a:4:{s:8:\"duration\";i:76;s:7:\"seconds\";i:4547;s:9:\"formatted\";s:7:\"1:15:47\";s:8:\"video_id\";s:11:\"nyw-cXXwk1s\";}', 1763317874),
('laravel-cache-youtube_duration_OuNo8Tkb3lI', 'a:4:{s:8:\"duration\";i:5;s:7:\"seconds\";i:266;s:9:\"formatted\";s:4:\"4:26\";s:8:\"video_id\";s:11:\"OuNo8Tkb3lI\";}', 1763386820),
('laravel-cache-youtube_duration_RiPLeXEwIXE', 'a:4:{s:8:\"duration\";i:44;s:7:\"seconds\";i:2636;s:9:\"formatted\";s:5:\"43:56\";s:8:\"video_id\";s:11:\"RiPLeXEwIXE\";}', 1763386818),
('laravel-cache-youtube_duration_U0nGqfj3UDA', 'a:4:{s:8:\"duration\";i:5;s:7:\"seconds\";i:272;s:9:\"formatted\";s:4:\"4:32\";s:8:\"video_id\";s:11:\"U0nGqfj3UDA\";}', 1763312212),
('laravel-cache-youtube_duration_uksJq562_9g', 'a:4:{s:8:\"duration\";i:16;s:7:\"seconds\";i:941;s:9:\"formatted\";s:5:\"15:41\";s:8:\"video_id\";s:11:\"uksJq562_9g\";}', 1763315958),
('laravel-cache-youtube_duration_vVhKA9Av6vA', 'a:4:{s:8:\"duration\";i:5;s:7:\"seconds\";i:300;s:9:\"formatted\";s:4:\"5:00\";s:8:\"video_id\";s:11:\"vVhKA9Av6vA\";}', 1763312253),
('laravel-cache-youtube_duration_XobgVozuf3I', 'a:4:{s:8:\"duration\";i:49;s:7:\"seconds\";i:2892;s:9:\"formatted\";s:5:\"48:12\";s:8:\"video_id\";s:11:\"XobgVozuf3I\";}', 1763386819),
('laravel-cache-youtube_duration_YLikaBePUqk', 'a:4:{s:8:\"duration\";i:79;s:7:\"seconds\";i:4728;s:9:\"formatted\";s:7:\"1:18:48\";s:8:\"video_id\";s:11:\"YLikaBePUqk\";}', 1763317908);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `slug` varchar(160) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image`, `parent_id`, `created_at`, `updated_at`) VALUES
(1, 'Lập trình', 'lap-trinh', NULL, NULL, NULL, '2025-10-15 13:08:10', '2025-10-15 13:08:10'),
(2, 'Thiết kế', 'thiet-ke', NULL, NULL, NULL, '2025-10-15 13:08:10', '2025-10-15 13:08:10'),
(3, 'Khoa học dữ liệu', 'khoa-hoc-du-lieu', NULL, NULL, NULL, '2025-10-29 16:46:37', '2025-10-29 16:46:37'),
(4, 'Trí tuệ nhân tạo', 'tri-tue-nhan-tao', NULL, NULL, NULL, '2025-10-29 16:46:37', '2025-10-29 16:46:37'),
(5, 'Phát triển web', 'phat-trien-web', NULL, NULL, NULL, '2025-10-29 16:46:37', '2025-10-29 16:46:37'),
(6, 'Phát triển di động', 'phat-trien-di-dong', NULL, NULL, NULL, '2025-10-29 16:46:37', '2025-10-29 16:46:37'),
(7, 'Marketing số', 'marketing-so', NULL, NULL, NULL, '2025-10-29 16:46:37', '2025-10-29 16:46:37'),
(8, 'Kinh doanh', 'kinh-doanh', NULL, NULL, NULL, '2025-10-29 16:46:37', '2025-10-29 16:46:37'),
(9, 'An ninh mạng', 'an-ninh-mang', NULL, NULL, NULL, '2025-10-29 16:46:37', '2025-10-29 16:46:37');

-- --------------------------------------------------------

--
-- Table structure for table `category_media`
--

CREATE TABLE `category_media` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'thumbnail',
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category_media`
--

INSERT INTO `category_media` (`id`, `category_id`, `type`, `path`, `created_at`, `updated_at`) VALUES
(1, 1, 'thumbnail', 'lap-trinh.jpg', '2025-10-29 09:48:10', '2025-10-29 09:48:10'),
(2, 2, 'thumbnail', 'thiet-ke.jpg', '2025-10-29 09:48:10', '2025-10-29 09:48:10'),
(3, 3, 'thumbnail', 'khoa-hoc-du-lieu.jpg', '2025-10-29 09:48:10', '2025-10-29 09:48:10'),
(4, 4, 'thumbnail', 'tri-tue-nhan-tao.jpg', '2025-10-29 09:48:10', '2025-10-29 09:48:10'),
(5, 5, 'thumbnail', 'phat-trien-web.jpg', '2025-10-29 09:48:10', '2025-10-29 09:48:10'),
(6, 6, 'thumbnail', 'phat-trien-di-dong.jpg', '2025-10-29 09:48:10', '2025-10-29 09:48:10'),
(7, 7, 'thumbnail', 'marketing-so.jpg', '2025-10-29 09:48:10', '2025-10-29 09:48:10'),
(8, 8, 'thumbnail', 'kinh-doanh.jpg', '2025-10-29 09:48:10', '2025-10-29 09:48:10'),
(9, 9, 'thumbnail', 'an-ninh-mang.jpg', '2025-10-29 09:48:10', '2025-10-29 09:48:10');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(40) COLLATE utf8mb4_general_ci NOT NULL,
  `type` enum('percent','fixed') COLLATE utf8mb4_general_ci NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `starts_at` datetime DEFAULT NULL,
  `ends_at` datetime DEFAULT NULL,
  `max_uses` int UNSIGNED DEFAULT NULL,
  `uses` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `type`, `value`, `starts_at`, `ends_at`, `max_uses`, `uses`, `created_at`, `updated_at`) VALUES
(1, 'BLACKFRIDAY', 'percent', 40.00, '2025-11-12 02:42:00', '2025-11-28 02:42:00', 1, 0, '2025-11-11 19:42:25', '2025-11-11 19:42:25');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` bigint UNSIGNED NOT NULL,
  `instructor_id` bigint UNSIGNED DEFAULT NULL,
  `category_id` bigint DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `slug` varchar(260) COLLATE utf8mb4_general_ci NOT NULL,
  `short_description` text COLLATE utf8mb4_general_ci,
  `description` longtext COLLATE utf8mb4_general_ci,
  `description_html` longtext COLLATE utf8mb4_general_ci,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `compare_at_price` decimal(10,2) DEFAULT NULL,
  `thumbnail_path` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('draft','published','hidden','archived') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'draft',
  `total_duration` int UNSIGNED NOT NULL DEFAULT '0',
  `level` enum('beginner','intermediate','advanced') COLLATE utf8mb4_general_ci DEFAULT 'beginner',
  `language` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'Vietnamese',
  `enrolled_students` int DEFAULT '0',
  `rating` decimal(2,1) DEFAULT '0.0',
  `rating_count` int DEFAULT '0',
  `video_count` int DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `instructor_id`, `category_id`, `title`, `slug`, `short_description`, `description`, `description_html`, `price`, `compare_at_price`, `thumbnail_path`, `status`, `total_duration`, `level`, `language`, `enrolled_students`, `rating`, `rating_count`, `video_count`, `created_at`, `updated_at`) VALUES
(10, 1, 1, 'Laravel Cơ bản', 'laravel-co-ban', 'Khóa học Laravel dành cho người mới bắt đầu', 'Tìm hiểu cách xây dựng ứng dụng web bằng Laravel Framework. Bao gồm routing, controller, view, Eloquent ORM và Blade Template.', NULL, 500000.00, 900000.00, 'laravel-basic.jpg', 'published', 19, 'beginner', 'Vietnamese', 1200, 4.7, 86, 42, '2025-10-29 18:46:13', '2025-10-29 18:46:13'),
(11, 1, 1, 'PHP OOP nâng cao', 'php-oop-nang-cao', 'Làm chủ Lập trình Hướng đối tượng trong PHP', 'Khóa học giúp bạn hiểu sâu về OOP, SOLID, Dependency Injection, Design Patterns và thực hành với PHP 8.', NULL, 450000.00, 850000.00, 'php-oop.jpg', 'published', 15, 'intermediate', 'Vietnamese', 980, 4.5, 65, 35, '2025-10-29 18:46:13', '2025-10-29 18:46:13'),
(12, 1, 1, 'NodeJS từ cơ bản đến nâng cao', 'nodejs-co-ban-den-nang-cao', 'Xây dựng ứng dụng backend với NodeJS và Express', 'Khóa học hướng dẫn cách tạo RESTful API, xử lý JWT, upload file, và kết nối với MongoDB.', NULL, 600000.00, 1100000.00, 'nodejs-advanced.jpg', 'published', 22, 'intermediate', 'Vietnamese', 2100, 4.8, 124, 58, '2025-10-29 18:46:13', '2025-10-29 18:46:13'),
(13, 1, 1, 'ReactJS hiện đại', 'reactjs-hien-dai', 'Xây dựng ứng dụng Frontend với React Hooks & Router', 'Khóa học hướng dẫn cách sử dụng React Hooks, Context API, Redux Toolkit và tạo ứng dụng SPA hoàn chỉnh.', NULL, 550000.00, 990000.00, 'reactjs-modern.jpg', 'published', 20, 'intermediate', 'Vietnamese', 1801, 5.0, 1, 47, '2025-10-29 18:46:13', '2025-11-11 19:20:17'),
(14, 1, 1, 'Java Spring Boot Cơ bản', 'java-spring-boot-co-ban', 'Lập trình backend với Java Spring Boot', 'Khóa học giúp bạn làm chủ Spring Boot, JPA, REST API, và triển khai ứng dụng trên server thực tế.', NULL, 650000.00, 1200000.00, 'spring-boot.jpg', 'published', 26, 'intermediate', 'Vietnamese', 1500, 4.7, 78, 62, '2025-10-29 18:46:13', '2025-10-29 18:46:13'),
(15, 1, 1, 'Python Web với Django', 'python-django', 'Học Django Framework để tạo web nhanh chóng và mạnh mẽ', 'Khóa học giúp bạn hiểu rõ về MVT, ORM, Authentication, Form và triển khai ứng dụng Django thực tế.', NULL, 500000.00, 950000.00, 'django-web.jpg', 'published', 19, 'intermediate', 'Vietnamese', 1750, 4.8, 103, 51, '2025-10-29 18:46:13', '2025-10-29 18:46:13'),
(16, 1, 1, 'C# .NET Core Web API', 'csharp-dotnetcore-api', 'Phát triển ứng dụng API mạnh mẽ với .NET Core', 'Khóa học giúp bạn làm chủ C#, Entity Framework Core, Identity, Authentication và API Documentation (Swagger).', NULL, 700000.00, 1300000.00, 'dotnet-core.jpg', 'published', 24, 'intermediate', 'Vietnamese', 950, 4.5, 52, 40, '2025-10-29 18:46:13', '2025-10-29 18:46:13'),
(17, 1, 1, 'Fullstack MERN Project', 'fullstack-mern-project', 'Dự án thực tế Fullstack với MongoDB, Express, React, Node', 'Khóa học giúp bạn tự tay xây dựng ứng dụng thực tế, kết nối frontend – backend – database, deploy lên server.', NULL, 800000.00, 1500000.00, 'mern-project.jpg', 'published', 30, 'advanced', 'Vietnamese', 2100, 4.9, 128, 70, '2025-10-29 18:46:13', '2025-10-29 18:46:13'),
(19, NULL, 2, 'Nền tảng Thiết kế đồ hoạ với Canva', 'canva-thiet-ke-do-hoa', 'Bắt đầu với Canva: bố cục, phông chữ, màu sắc, template.', '<p>Khoá học giúp bạn làm chủ Canva từ con số 0, tạo banner, poster, social post chuyên nghiệp.</p>', NULL, 399000.00, 790000.00, 'canva-basics.jpg', 'published', 10, 'beginner', 'Vietnamese', 1200, 4.6, 320, 85, '2025-10-30 00:16:19', '2025-10-30 00:16:19'),
(20, NULL, 2, 'Figma UI/UX từ cơ bản đến prototype', 'figma-ui-ux-prototype', 'Thiết kế giao diện, auto layout, component, prototype trong Figma.', '<p>Làm chủ Figma với workflow thực chiến: grid, component, variants, prototype & handoff.</p>', NULL, 549000.00, 990000.00, 'figma-uiux.jpg', 'published', 18, 'beginner', 'Vietnamese', 2100, 4.7, 540, 140, '2025-10-30 00:16:19', '2025-10-30 00:16:19'),
(21, NULL, 2, 'Adobe Illustrator Chuyên sâu', 'adobe-illustrator-chuyen-sau', 'Vector, pen tool, logo, icon, bộ nhận diện thương hiệu.', '<p>Đi sâu kỹ thuật vector: pathfinder, blend, pattern, gradient mesh, appearance…</p>', NULL, 589000.00, 1090000.00, 'ai-illustrator.jpg', 'published', 22, 'intermediate', 'Vietnamese', 1600, 4.5, 410, 120, '2025-10-30 00:16:19', '2025-10-30 00:16:19'),
(22, NULL, 2, 'Photoshop Thực chiến Retouch & Social', 'photoshop-retouch-social', 'Retouch chân dung, blend màu, thiết kế social banner.', '<p>Thực hành retouch chuyên nghiệp, action, LUT, smart object & workflow social.</p>', NULL, 519000.00, 990000.00, 'photoshop-retouch.jpg', 'published', 16, 'intermediate', 'Vietnamese', 1350, 4.6, 360, 96, '2025-10-30 00:16:19', '2025-10-30 00:16:19'),
(23, NULL, 3, 'Python cho Phân tích dữ liệu', 'python-phan-tich-du-lieu', 'Numpy, Pandas, Matplotlib, làm sạch & trực quan hoá dữ liệu.', '<p>Pipeline xử lý dữ liệu thực tế: EDA, thống kê mô tả, vẽ biểu đồ & report.</p>', NULL, 599000.00, 1190000.00, 'python-data-analysis.jpg', 'published', 20, 'beginner', 'Vietnamese', 3200, 4.7, 890, 155, '2025-10-30 00:16:19', '2025-10-30 00:16:19'),
(24, NULL, 3, 'SQL từ cơ bản đến nâng cao', 'sql-tu-co-ban-den-nang-cao', 'Truy vấn dữ liệu, join, window function, tối ưu & bài tập thực tế.', '<p>Luyện SQL trên MySQL/PostgreSQL với hàng chục case: sales, e-commerce, marketing.</p>', NULL, 449000.00, 890000.00, 'sql-advanced.jpg', 'published', 14, 'beginner', 'Vietnamese', 4101, 4.6, 1100, 120, '2025-10-30 00:16:19', '2025-11-11 19:43:22'),
(25, NULL, 3, 'Machine Learning cơ bản với Scikit-learn', 'machine-learning-scikit-learn', 'Linear/Logistic Regression, Tree, SVM, model metrics & tuning.', '<p>Xây dựng mô hình ML đầu tay, đánh giá & cải thiện bằng cross-validation, grid search.</p>', NULL, 649000.00, 1290000.00, 'ml-scikit.jpg', 'published', 24, 'intermediate', 'Vietnamese', 2600, 4.6, 670, 170, '2025-10-30 00:16:19', '2025-10-30 00:16:19'),
(26, NULL, 3, 'Power BI: Dashboard & DAX thực chiến', 'power-bi-dashboard-dax', 'Kết nối dữ liệu, làm sạch, DAX, dashboard theo KPI.', '<p>Làm báo cáo động theo KPI Sales/Finance, chia sẻ & publish lên Power BI Service.</p>', NULL, 579000.00, 1090000.00, 'powerbi.jpg', 'published', 18, 'intermediate', 'Vietnamese', 2200, 4.7, 590, 130, '2025-10-30 00:16:19', '2025-10-30 00:16:19'),
(27, NULL, 4, 'Deep Learning cơ bản với Keras', 'deep-learning-keras', 'ANN, CNN, RNN, overfitting, augmentation, callback.', '<p>Xây dựng mạng nơ-ron cho ảnh & chuỗi, thực hành trên TensorFlow/Keras.</p>', NULL, 689000.00, 1390000.00, 'dl-keras.jpg', 'published', 22, 'intermediate', 'Vietnamese', 1800, 4.6, 520, 160, '2025-10-30 00:16:19', '2025-10-30 00:16:19'),
(28, NULL, 4, 'Xử lý ngôn ngữ tự nhiên (NLP) cơ bản', 'nlp-co-ban', 'Tiền xử lý văn bản, TF-IDF, Word2Vec, phân loại & sentiment.', '<p>Pipeline NLP end-to-end, đánh giá & triển khai mô hình phân loại văn bản.</p>', NULL, 629000.00, 1190000.00, 'nlp-basics.jpg', 'published', 16, 'intermediate', 'Vietnamese', 1500, 4.5, 410, 110, '2025-10-30 00:16:19', '2025-10-30 00:16:19'),
(29, NULL, 4, 'Computer Vision với OpenCV', 'computer-vision-opencv', 'Tiền xử lý ảnh, phát hiện đối tượng, contour, tracking.', '<p>Thực hành OpenCV + Python cho các bài toán thị giác máy tính nền tảng.</p>', NULL, 569000.00, 1090000.00, 'opencv.jpg', 'published', 14, 'intermediate', 'Vietnamese', 1400, 4.5, 380, 95, '2025-10-30 00:16:19', '2025-10-30 00:16:19'),
(30, NULL, 4, 'LLM & Prompt Engineering thực hành', 'llm-prompt-engineering', 'Hiểu LLM, token, prompt pattern, RAG cơ bản.', 'Thực hành prompt hiệu quả & tích hợp LLM vào ứng dụng bằng API.ádddddddd', '<p>Khóa học “LLM &amp; Prompt Engineering Thực Hành” được thiết kế dành cho những ai muốn khai thác tối đa sức mạnh của các mô hình ngôn ngữ lớn (Large Language Models – LLMs) như GPT, Claude, Gemini, hay Mistral. Học viên không chỉ được trang bị nền tảng lý thuyết vững chắc về cách LLM hoạt động, mà còn trực tiếp <strong>thực hành xây dựng, tối ưu và triển khai prompt</strong> trong các tình huống thực tế như tạo nội dung, xử lý dữ liệu, lập trình, hay phân tích ngữ nghĩa.</p><p>Khóa học tập trung vào <strong>tư duy kỹ sư prompt (Prompt Engineering Mindset)</strong> — hiểu cách mô hình “nghĩ”, học cách đặt câu hỏi thông minh, kiểm thử và tinh chỉnh để đạt kết quả tốt nhất. Học viên cũng được hướng dẫn cách sử dụng các công cụ và framework hiện đại như LangChain, OpenAI API, hay LlamaIndex để <strong>xây dựng ứng dụng AI end-to-end</strong>.</p><p><strong>Bạn sẽ học được:</strong></p><ul><li>Nguyên lý hoạt động của các mô hình LLM hiện đại.</li><li>Kỹ thuật thiết kế prompt hiệu quả: từ zero-shot, few-shot đến chain-of-thought.</li><li>Thực hành với các bài tập mô phỏng dự án thực tế.</li><li>Cách kết hợp LLM với dữ liệu riêng (Retrieval-Augmented Generation - RAG).</li><li>Triển khai ứng dụng AI với Python và API thực tế.</li></ul><p><strong>Đối tượng học viên:</strong></p><ul><li>Lập trình viên, nhà phân tích dữ liệu, marketer, chuyên viên nội dung, hoặc bất kỳ ai muốn ứng dụng AI vào công việc.</li><li>Không yêu cầu nền tảng học thuật phức tạp — chỉ cần tư duy logic và tinh thần học hỏi.</li></ul><p><strong>Kết quả đạt được:</strong><br>Sau khóa học, bạn sẽ có khả năng <strong>hiểu – thiết kế – triển khai</strong> prompt và ứng dụng AI tùy chỉnh, giúp tăng tốc hiệu suất làm việc, sáng tạo và ra quyết định.</p>', 699000.00, 1490000.00, 'llm-prompt.jpg', 'published', 8237, 'intermediate', 'Vietnamese', 901, 0.0, 0, 75, '2025-10-30 00:16:20', '2025-11-16 13:41:55'),
(31, NULL, 5, 'HTML/CSS/JS Cơ bản qua dự án', 'html-css-js-co-ban', 'Xây dựng landing page responsive từ A → Z.', '<p>Nắm vững cấu trúc HTML, Flex/Grid, DOM & best-practices responsive.</p>', NULL, 379000.00, 790000.00, 'html-css-js.jpg', 'published', 776, 'beginner', 'Vietnamese', 4804, 4.7, 1400, 95, '2025-10-30 00:16:20', '2025-11-16 13:41:55'),
(32, NULL, 5, 'ReactJS từ cơ bản đến nâng cao', 'reactjs-tu-co-ban-den-nang-cao', 'Component, hook, router, state management, tối ưu hiệu năng.', '<p>Xây SPA hoàn chỉnh, tách module & tối ưu hoá production build.</p>', '<h2>Requirements</h2><ul><li>Có tư duy lập trình</li><li>Có hiểu biết về lập trình webiste là một lợi thế</li><li>Có kiến thức về HTML, CSS, Javascript cơ bản là một lợi thế</li></ul><h2>Description</h2><p><strong>1. Công nghệ sử dụng</strong></p><p>React version 18 &amp; 19</p><p>React là thư viện với cơ chế CSR - client side rendering</p><p><strong>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Các kiến thức trọng tâm:</strong></p><ul><li>Phân biệt các phong cách code/sử dụng React trong thực tế</li><li><p>Học React với đúng tư duy ban đầu của React - React là library UI</p><p>&nbsp;</p><p><strong>Các kiến thức về React (cốt lõi nhất):</strong></p></li><li>Tư duy thiết kế UI với React (sử dụng Component)</li><li>Render/Re-render giao diện với Props và State (useState hook)</li><li>Điều hướng trang với React-router-dom</li><li>Sử dụng useEffect để gọi API backend</li><li>Sử dụng Context API để sharing data giữa các component</li><li>Sử dụng mô hình Stateless (với access_token)</li><li>Sử dụng Ant Design (antd) để làm giao diện chuyên nghiệp (UI - UX)</li><li>Tối ưu hóa re-render với Uncontrolled Component</li></ul><p>&nbsp;</p><p><strong>Backend (Nestjs)</strong> được mình cung cấp sẵn. Chỉ sử dụng và không sửa đổi. (<strong>không học code backend trong khóa học này</strong>).</p><p><strong>Database MongoDB</strong> sử dụng online (miễn phí) với MongoDB Atlas</p><p>&nbsp;</p><p><strong>2. Học viên nào có thể học ?</strong></p><p>Học viên cần trang bị các kiến thức sau trước khi theo học: <strong>HTML, CSS và cú pháp của Javascript</strong></p><p>&nbsp;</p><p><strong>3. Triển khai dự án</strong></p><p>Đến cuối khóa học, dự án được triển khai:</p><ul><li>Frontend triển khai với Vercel</li><li>Backend triển khai với Render</li><li>Database triển khai với MongoDB Atlas</li></ul>', 649000.00, 1290000.00, 'reactjs.jpg', 'published', 617, 'intermediate', 'Vietnamese', 5201, 4.7, 1600, 174, '2025-10-30 00:16:20', '2025-11-16 13:41:55'),
(33, NULL, 5, 'NodeJS & Express APIs thực chiến', 'nodejs-express-api-thuc-chien', 'RESTful API, auth (JWT), middleware, upload, logging.', '<p>Xây dựng API chuẩn production: cấu trúc, test, bảo mật & deploy.</p>', NULL, 629000.00, 1190000.00, 'node-express.jpg', 'published', 20, 'intermediate', 'Vietnamese', 3900, 4.6, 980, 140, '2025-10-30 00:16:20', '2025-10-30 00:16:20'),
(34, NULL, 5, 'Laravel Cơ bản đến CRUD & Auth', 'laravel-co-ban-crud-auth', 'Routing, Blade, Eloquent, migration, CRUD, auth, middleware.', '<p>Hoàn thiện mini-project chuẩn MVC & thực hành best-practice Laravel.</p>', NULL, 599000.00, 1190000.00, 'laravel-crud-auth.jpg', 'published', 18, 'beginner', 'Vietnamese', 4500, 4.7, 1200, 125, '2025-10-30 00:16:20', '2025-10-30 00:16:20'),
(35, NULL, NULL, 'Lập trình C++ cơ bản, nâng cao', 'C++_co_ban', NULL, NULL, NULL, 0.00, NULL, NULL, 'published', 10438, 'beginner', 'Vietnamese', 3, 5.0, 1, 19, '2025-11-16 06:08:08', '2025-11-16 16:00:36');

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `course_id` bigint UNSIGNED NOT NULL,
  `status` enum('active','revoked') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'active',
  `enrolled_at` datetime NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`id`, `user_id`, `course_id`, `status`, `enrolled_at`, `created_at`, `updated_at`) VALUES
(1, 2, 13, 'active', '2025-11-11 19:13:45', '2025-11-11 19:13:45', '2025-11-11 19:13:45'),
(2, 1, 31, 'active', '2025-11-11 19:30:40', '2025-11-11 19:30:40', '2025-11-11 19:30:40'),
(3, 1, 24, 'active', '2025-11-11 19:43:22', '2025-11-11 19:43:22', '2025-11-11 19:43:22'),
(4, 2, 30, 'active', '2025-11-12 10:14:42', '2025-11-12 10:14:42', '2025-11-12 10:14:42'),
(5, 2, 31, 'active', '2025-11-12 10:53:25', '2025-11-12 10:53:25', '2025-11-12 10:53:25'),
(6, 5, 31, 'active', '2025-11-12 17:58:24', '2025-11-12 17:58:24', '2025-11-12 17:58:24'),
(7, 2, 32, 'active', '2025-11-15 04:05:18', '2025-11-15 04:05:18', '2025-11-15 04:05:18'),
(8, 3, 31, 'active', '2025-11-15 18:19:40', '2025-11-15 18:19:40', '2025-11-15 18:19:40'),
(9, 1, 35, 'active', '2025-11-16 07:40:16', '2025-11-16 07:40:16', '2025-11-16 07:40:16'),
(10, 2, 35, 'active', '2025-11-16 15:40:35', '2025-11-16 15:40:35', '2025-11-16 15:40:35'),
(11, 5, 35, 'active', '2025-11-16 15:41:34', '2025-11-16 15:41:34', '2025-11-16 15:41:34');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lessons`
--

CREATE TABLE `lessons` (
  `id` bigint UNSIGNED NOT NULL,
  `section_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `video_path` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hls_path` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cloudinary_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `video_url` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `attachment_path` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `duration` int UNSIGNED NOT NULL DEFAULT '0',
  `is_preview` tinyint(1) NOT NULL DEFAULT '0',
  `position` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lessons`
--

INSERT INTO `lessons` (`id`, `section_id`, `title`, `video_path`, `hls_path`, `cloudinary_id`, `video_url`, `attachment_path`, `duration`, `is_preview`, `position`, `created_at`, `updated_at`) VALUES
(2, 2, 'Bài học vỡ lòng', NULL, NULL, NULL, 'https://www.youtube.com/watch?v=Hc5edo4R5Oc&list=RDHc5edo4R5Oc&start_radio=1', 'attachments/lessons/5MLB77ReeyPUsznjgEp10eNwa4qVWi73IBy4nElR.pdf', 312, 1, 3, '2025-11-11 19:30:07', '2025-11-15 04:01:16'),
(4, 2, 'mikasa', 'videos/lessons/FgTXR7FJOKytyRnROjSDy6qXGYlG8yDMbeuU6YHm.mp4', NULL, NULL, NULL, NULL, 0, 0, 1, '2025-11-12 17:44:23', '2025-11-12 17:44:23'),
(5, 2, 'ẻ3f', NULL, NULL, 'videos/lessons/syzbozme9wg0u4wjvnm1', 'https://res.cloudinary.com/dpwjfxi8i/video/upload/sp_hd/videos/lessons/syzbozme9wg0u4wjvnm1.m3u8', NULL, 22, 0, 4, '2025-11-12 17:57:22', '2025-11-15 19:45:58'),
(6, 5, 'try', NULL, NULL, NULL, 'https://www.youtube.com/watch?v=Sk5r_SxGeS0', NULL, 0, 0, 2, '2025-11-12 18:11:51', '2025-11-12 18:12:34'),
(7, 5, 'acx', NULL, NULL, 'videos/lessons/jnfwtbdabgls8dn1ghg2', 'https://res.cloudinary.com/dpwjfxi8i/video/upload/v1763271776/videos/lessons/jnfwtbdabgls8dn1ghg2.mp4', NULL, 442, 0, 1, '2025-11-12 18:12:21', '2025-11-16 05:43:03'),
(8, 6, 'Prompt AI: Hướng Dẫn Viết Prompt Hiệu Quả - Kinh nghiệm đúc kết gần 1000 giờ dùng AI', NULL, NULL, NULL, 'https://www.youtube.com/watch?v=RiPLeXEwIXE&list=PLyCvV8IiFZXGA0D0dFtVrDJSpguOaxbTM&index=1', NULL, 2636, 0, 1, '2025-11-12 18:25:59', '2025-11-16 13:41:54'),
(9, 6, 'AI Model là gì? 5 Điều bạn cần biết để tạo PROMPT AI HIỆU QUẢ HƠN', NULL, NULL, NULL, 'https://www.youtube.com/watch?v=8i4HE0dMSXU&list=PLyCvV8IiFZXGA0D0dFtVrDJSpguOaxbTM&index=2', NULL, 896, 0, 2, '2025-11-12 18:26:30', '2025-11-16 13:41:55'),
(10, 6, '5 Bí kíp Prompt giúp Prompt AI trở nên vượt trội', NULL, NULL, NULL, 'https://drive.google.com/file/d/12-DQz06q516o1REMmuLw-l3qQ0BAkdD6/view', NULL, 23, 0, 3, '2025-11-12 18:27:08', '2025-11-12 18:46:39'),
(11, 6, 'Custom Prompt: Cách x2 thu nhập & tối ưu 30% chi phí với AI', NULL, NULL, NULL, 'https://www.youtube.com/watch?v=XobgVozuf3I&list=PLyCvV8IiFZXGA0D0dFtVrDJSpguOaxbTM&index=4', NULL, 2892, 0, 4, '2025-11-12 18:27:39', '2025-11-16 13:41:55'),
(12, 7, '7 Bí Kíp Sử Dụng ChatGPT Cực Xịn mà 90% Người Dùng Không Biết!', NULL, NULL, NULL, 'https://www.youtube.com/watch?v=clJJXlBzYhk&list=PLyCvV8IiFZXGA0D0dFtVrDJSpguOaxbTM&index=5', NULL, 1790, 0, 1, '2025-11-12 18:29:07', '2025-11-16 13:41:55'),
(13, 3, 'fsgfdg', NULL, NULL, NULL, 'https://www.youtube.com/watch?v=Hc5edo4R5Oc&list=RDHc5edo4R5Oc&start_radio=1', NULL, 312, 0, 1, '2025-11-15 04:03:40', '2025-11-15 04:03:40'),
(14, 3, 'dfgdfg', NULL, NULL, NULL, 'https://www.youtube.com/watch?v=OuNo8Tkb3lI&list=RDHc5edo4R5Oc&index=2', NULL, 266, 0, 2, '2025-11-15 04:03:59', '2025-11-16 13:41:55'),
(15, 3, 'ụad', 'videos/lessons/TtdTcu36SNQmHx3ScgBjHKxeU2GOsHKLGrhPIuFi.mp4', NULL, NULL, NULL, NULL, 22, 0, 3, '2025-11-15 04:07:52', '2025-11-15 15:08:00'),
(16, 3, 'aggh', 'videos/lessons/0CHh823Cx2TP37Sl0J3GEI8mTLCSLa2njIuU2XWM.mp4', NULL, NULL, NULL, NULL, 17, 0, 4, '2025-11-15 04:08:18', '2025-11-15 15:06:39'),
(17, 10, 'Giới thiệu khóa học', NULL, NULL, 'videos/Lap_trinh_C_co_ban_nang_cao/ztxkobdzohk6k926onqt', 'https://res.cloudinary.com/dpwjfxi8i/video/upload/v1763273445/videos/Lap_trinh_C_co_ban_nang_cao/ztxkobdzohk6k926onqt.mp4', NULL, 62, 0, 1, '2025-11-16 06:11:18', '2025-11-16 06:11:18'),
(18, 10, 'Cài đặt Dev C++', NULL, NULL, 'videos/Lap_trinh_C_co_ban_nang_cao/ed5cm5e5kbh7nhqzjyz1', 'https://res.cloudinary.com/dpwjfxi8i/video/upload/v1763273540/videos/Lap_trinh_C_co_ban_nang_cao/ed5cm5e5kbh7nhqzjyz1.mp4', NULL, 151, 0, 2, '2025-11-16 06:13:39', '2025-11-16 06:13:39'),
(19, 10, 'Hướng dẫn sử dụng Dev C++', NULL, NULL, 'videos/Lap_trinh_C_co_ban_nang_cao/os2rbwgmfhca2t4sax2r', 'https://res.cloudinary.com/dpwjfxi8i/video/upload/v1763273721/videos/Lap_trinh_C_co_ban_nang_cao/os2rbwgmfhca2t4sax2r.mp4', NULL, 213, 0, 3, '2025-11-16 06:16:41', '2025-11-16 06:16:41'),
(20, 11, 'Biến và nhập xuất dữ liệu', NULL, NULL, 'videos/Lap_trinh_C_co_ban_nang_cao/hdsxgeqjr05qcij8hc72', 'https://res.cloudinary.com/dpwjfxi8i/video/upload/v1763273947/videos/Lap_trinh_C_co_ban_nang_cao/hdsxgeqjr05qcij8hc72.mp4', NULL, 454, 0, 1, '2025-11-16 06:21:03', '2025-11-16 06:21:03'),
(21, 11, 'Kiểu dữ liệu thường gặp', NULL, NULL, 'videos/Lap_trinh_C_co_ban_nang_cao/ww6f3b49qg4ruplivabq', 'https://res.cloudinary.com/dpwjfxi8i/video/upload/v1763274123/videos/Lap_trinh_C_co_ban_nang_cao/ww6f3b49qg4ruplivabq.mp4', NULL, 343, 0, 2, '2025-11-16 06:23:31', '2025-11-16 06:32:20'),
(22, 11, 'Biến cục bộ và biến toàn cục', NULL, NULL, 'videos/Lap_trinh_C_co_ban_nang_cao/ggwx101uys5qkwhip4dt', 'https://res.cloudinary.com/dpwjfxi8i/video/upload/v1763274786/videos/Lap_trinh_C_co_ban_nang_cao/ggwx101uys5qkwhip4dt.mp4', NULL, 401, 0, 3, '2025-11-16 06:35:28', '2025-11-16 06:35:28'),
(23, 11, 'Hằng số', NULL, NULL, 'videos/Lap_trinh_C_co_ban_nang_cao/eyurxgbf0oe4cqcegmzb', 'https://res.cloudinary.com/dpwjfxi8i/video/upload/v1763275005/videos/Lap_trinh_C_co_ban_nang_cao/eyurxgbf0oe4cqcegmzb.mp4', NULL, 217, 0, 4, '2025-11-16 06:38:12', '2025-11-16 06:38:12'),
(24, 11, 'Toán tử gán và toán tử số học', NULL, NULL, 'videos/Lap_trinh_C_co_ban_nang_cao/sinblqafjvipyrxk46jc', 'https://res.cloudinary.com/dpwjfxi8i/video/upload/v1763275144/videos/Lap_trinh_C_co_ban_nang_cao/sinblqafjvipyrxk46jc.mp4', NULL, 653, 0, 5, '2025-11-16 06:42:45', '2025-11-16 06:42:45'),
(25, 11, 'Toán tử quan hệ và toán tử logic', NULL, NULL, 'videos/Lap_trinh_C_co_ban_nang_cao/fstnhzwuy5jrhldqbokd', 'https://res.cloudinary.com/dpwjfxi8i/video/upload/v1763275442/videos/Lap_trinh_C_co_ban_nang_cao/fstnhzwuy5jrhldqbokd.mp4', NULL, 746, 0, 6, '2025-11-16 06:47:49', '2025-11-16 06:47:49'),
(26, 11, 'Ép kiểu dữ liệu và bảng mã ASCII', NULL, NULL, 'videos/Lap_trinh_C_co_ban_nang_cao/qdq9uvo6zh7johhjilga', 'https://res.cloudinary.com/dpwjfxi8i/video/upload/v1763275728/videos/Lap_trinh_C_co_ban_nang_cao/qdq9uvo6zh7johhjilga.mp4', NULL, 532, 0, 7, '2025-11-16 06:52:19', '2025-11-16 06:52:19'),
(27, 11, 'Thực hành làm bài tập chương 02', NULL, NULL, 'videos/Lap_trinh_C_co_ban_nang_cao/nnbcbxywi8iyuz7htoip', 'https://res.cloudinary.com/dpwjfxi8i/video/upload/v1763276035/videos/Lap_trinh_C_co_ban_nang_cao/nnbcbxywi8iyuz7htoip.mp4', NULL, 931, 0, 8, '2025-11-16 06:54:01', '2025-11-16 06:54:01'),
(28, 12, 'Cấu trúc if else', NULL, NULL, 'videos/Lap_trinh_C_co_ban_nang_cao/tfuqe190vd2cbdwqm5mv', 'https://res.cloudinary.com/dpwjfxi8i/video/upload/v1763276106/videos/Lap_trinh_C_co_ban_nang_cao/tfuqe190vd2cbdwqm5mv.mp4', NULL, 562, 0, 1, '2025-11-16 06:57:39', '2025-11-16 06:57:39'),
(29, 12, 'Cấu trúc switch case', NULL, NULL, 'videos/Lap_trinh_C_co_ban_nang_cao/eqakkw18fqumgkcl6oru', 'https://res.cloudinary.com/dpwjfxi8i/video/upload/v1763276310/videos/Lap_trinh_C_co_ban_nang_cao/eqakkw18fqumgkcl6oru.mp4', NULL, 672, 0, 2, '2025-11-16 07:03:38', '2025-11-16 07:03:38'),
(30, 12, 'Toán tử 3 ngôi trong C++', NULL, NULL, 'videos/Lap_trinh_C_co_ban_nang_cao/gf4cr6blggsarmisw3xo', 'https://res.cloudinary.com/dpwjfxi8i/video/upload/v1763276928/videos/Lap_trinh_C_co_ban_nang_cao/gf4cr6blggsarmisw3xo.mp4', NULL, 332, 0, 3, '2025-11-16 07:10:17', '2025-11-16 07:10:17'),
(31, 12, 'Vòng lặp trong C++', NULL, NULL, 'videos/Lap_trinh_C_co_ban_nang_cao/rdveeemuetnyyosjtxae', 'https://res.cloudinary.com/dpwjfxi8i/video/upload/v1763277065/videos/Lap_trinh_C_co_ban_nang_cao/rdveeemuetnyyosjtxae.mp4', NULL, 767, 0, 4, '2025-11-16 07:15:37', '2025-11-16 07:15:37'),
(32, 13, 'Mảng một chiều', NULL, NULL, 'videos/Lap_trinh_C_co_ban_nang_cao/ufxlbfqeerblsol0rnir', 'https://res.cloudinary.com/dpwjfxi8i/video/upload/v1763277439/videos/Lap_trinh_C_co_ban_nang_cao/ufxlbfqeerblsol0rnir.mp4', NULL, 560, 0, 1, '2025-11-16 07:21:02', '2025-11-16 07:21:02'),
(33, 13, 'Thực hành sử dụng mảng', NULL, NULL, 'videos/Lap_trinh_C_co_ban_nang_cao/j2lmjept88mvte1xiyba', 'https://res.cloudinary.com/dpwjfxi8i/video/upload/v1763277800/videos/Lap_trinh_C_co_ban_nang_cao/j2lmjept88mvte1xiyba.mp4', NULL, 1131, 0, 2, '2025-11-16 07:23:25', '2025-11-16 07:23:25'),
(34, 14, 'Hàm trong C++', NULL, NULL, 'videos/Lap_trinh_C_co_ban_nang_cao/p8jxxrjpwwbb2yimjrzt', 'https://res.cloudinary.com/dpwjfxi8i/video/upload/v1763277979/videos/Lap_trinh_C_co_ban_nang_cao/p8jxxrjpwwbb2yimjrzt.mp4', NULL, 1072, 0, 1, '2025-11-16 07:26:24', '2025-11-16 07:26:24'),
(35, 15, 'Review THE CONJURING 4: PHIM KINH DỊ nhưng KHÔNG ĐÁNG SỢ?', NULL, NULL, NULL, 'https://www.youtube.com/watch?v=8Pqg0IGN02g', NULL, 639, 0, 1, '2025-11-16 13:02:55', '2025-11-16 13:41:55');

-- --------------------------------------------------------

--
-- Table structure for table `lesson_progress`
--

CREATE TABLE `lesson_progress` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `lesson_id` bigint UNSIGNED NOT NULL,
  `completed_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lesson_progress`
--

INSERT INTO `lesson_progress` (`id`, `user_id`, `lesson_id`, `completed_at`, `created_at`, `updated_at`) VALUES
(1, 1, 2, '2025-11-11 19:38:48', '2025-11-11 19:38:48', '2025-11-11 19:38:48'),
(2, 5, 4, '2025-11-12 17:59:16', '2025-11-12 17:59:16', '2025-11-12 17:59:16'),
(3, 5, 2, '2025-11-12 17:59:59', '2025-11-12 17:59:59', '2025-11-12 17:59:59'),
(4, 5, 5, '2025-11-12 18:17:32', '2025-11-12 18:17:32', '2025-11-12 18:17:32'),
(5, 5, 7, '2025-11-12 18:18:43', '2025-11-12 18:18:43', '2025-11-12 18:18:43'),
(6, 2, 8, '2025-11-12 18:35:32', '2025-11-12 18:35:32', '2025-11-12 18:35:32'),
(7, 2, 9, '2025-11-12 18:35:58', '2025-11-12 18:35:58', '2025-11-12 18:35:58'),
(8, 1, 4, '2025-11-14 05:57:17', '2025-11-14 05:57:17', '2025-11-14 05:57:17'),
(9, 1, 5, '2025-11-14 05:57:26', '2025-11-14 05:57:26', '2025-11-14 05:57:26'),
(10, 1, 7, '2025-11-14 05:58:21', '2025-11-14 05:58:21', '2025-11-14 05:58:21'),
(11, 2, 10, '2025-11-14 17:22:25', '2025-11-14 17:22:25', '2025-11-14 17:22:25'),
(12, 2, 4, '2025-11-15 03:14:32', '2025-11-15 03:14:32', '2025-11-15 03:14:32'),
(13, 2, 2, '2025-11-15 03:52:19', '2025-11-15 03:52:19', '2025-11-15 03:52:19'),
(14, 2, 11, '2025-11-15 03:56:31', '2025-11-15 03:56:31', '2025-11-15 03:56:31'),
(15, 2, 12, '2025-11-15 03:56:50', '2025-11-15 03:56:50', '2025-11-15 03:56:50'),
(16, 2, 13, '2025-11-15 04:05:32', '2025-11-15 04:05:32', '2025-11-15 04:05:32'),
(17, 2, 14, '2025-11-15 04:06:36', '2025-11-15 04:06:36', '2025-11-15 04:06:36'),
(18, 2, 15, '2025-11-15 04:18:01', '2025-11-15 04:18:01', '2025-11-15 04:18:01'),
(19, 2, 16, '2025-11-15 14:36:29', '2025-11-15 14:36:29', '2025-11-15 14:36:29'),
(20, 2, 5, '2025-11-15 15:24:01', '2025-11-15 15:24:01', '2025-11-15 15:24:01'),
(21, 3, 2, '2025-11-15 18:20:12', '2025-11-15 18:20:12', '2025-11-15 18:20:12'),
(22, 3, 5, '2025-11-15 18:35:39', '2025-11-15 18:35:39', '2025-11-15 18:35:39'),
(23, 2, 7, '2025-11-16 05:43:25', '2025-11-16 05:43:25', '2025-11-16 05:43:25'),
(24, 1, 17, '2025-11-16 07:42:54', '2025-11-16 07:42:54', '2025-11-16 07:42:54'),
(25, 1, 18, '2025-11-16 07:43:39', '2025-11-16 07:43:39', '2025-11-16 07:43:39'),
(26, 1, 19, '2025-11-16 07:52:42', '2025-11-16 07:52:42', '2025-11-16 07:52:42');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2025_10_29_025747_create_sessions_table', 1),
(2, '2025_10_29_093627_add_image_to_categories_table', 2),
(3, '2025_10_29_093959_create_category_media_table', 3),
(4, '2025_10_29_144425_add_description_html_to_courses_table', 4),
(5, '2025_10_29_151234_make_instructor_id_nullable_on_courses_table', 5),
(6, '2025_11_11_190214_add_avatar_to_users_table', 6),
(7, '2025_11_11_192338_fix_admin_password', 6),
(8, '2025_11_11_193310_add_video_url_to_lessons_table', 7),
(9, '2025_11_15_034330_create_cache_table', 8),
(10, '2025_11_15_144331_add_hls_path_to_lessons_table', 9),
(11, '2025_11_15_153045_create_jobs_table', 10),
(12, '2025_11_15_164724_add_cloudinary_id_to_lessons_table', 11);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `body` text COLLATE utf8mb4_general_ci NOT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `code` varchar(40) COLLATE utf8mb4_general_ci NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total` decimal(10,2) NOT NULL,
  `status` enum('pending','paid','failed','cancelled','refunded') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `code`, `subtotal`, `discount`, `total`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 'ORD-CSZN5QIX', 550000.00, 0.00, 550000.00, 'paid', '2025-11-11 19:13:45', '2025-11-11 19:13:45'),
(2, 1, 'ORD-YUGQ5ICC', 379000.00, 0.00, 379000.00, 'paid', '2025-11-11 19:30:40', '2025-11-11 19:30:40'),
(3, 1, 'ORD-EY3AIHXR', 449000.00, 0.00, 449000.00, 'paid', '2025-11-11 19:43:22', '2025-11-11 19:43:22'),
(4, 2, 'ORD-CDUVMUXR', 699000.00, 0.00, 699000.00, 'pending', '2025-11-12 09:46:10', '2025-11-12 09:46:10'),
(5, 2, 'ORD-LUVNNZFH', 699000.00, 0.00, 699000.00, 'pending', '2025-11-12 10:13:53', '2025-11-12 10:13:53'),
(6, 2, 'ORD-Q81OTWUL', 699000.00, 0.00, 699000.00, 'failed', '2025-11-12 10:14:06', '2025-11-12 10:14:11'),
(7, 2, 'ORD-QUQ23FMM', 699000.00, 0.00, 699000.00, 'paid', '2025-11-12 10:14:36', '2025-11-12 10:14:42'),
(8, 2, 'ORD-ERO6QXXB', 379000.00, 0.00, 379000.00, 'pending', '2025-11-12 10:19:51', '2025-11-12 10:19:51'),
(9, 2, 'ORD-QNPGICZH', 379000.00, 0.00, 379000.00, 'failed', '2025-11-12 10:24:02', '2025-11-12 10:27:17'),
(10, 2, 'ORD-YCENTADD', 379000.00, 0.00, 379000.00, 'failed', '2025-11-12 10:29:37', '2025-11-12 10:39:44'),
(11, 2, 'ORD-QAPDIF2D', 379000.00, 0.00, 379000.00, 'failed', '2025-11-12 10:42:41', '2025-11-12 10:52:57'),
(12, 2, 'ORD-G4WO92WG', 379000.00, 0.00, 379000.00, 'paid', '2025-11-12 10:53:04', '2025-11-12 10:53:25'),
(13, 2, 'ORD-RWIW2UDC', 579000.00, 0.00, 579000.00, 'failed', '2025-11-12 11:50:02', '2025-11-12 11:50:11'),
(14, 2, 'ORD-GJ1ZIL3W', 579000.00, 0.00, 579000.00, 'failed', '2025-11-12 11:50:28', '2025-11-12 11:50:56'),
(15, 2, 'ORD-JG9K7ORS', 978000.00, 0.00, 978000.00, 'failed', '2025-11-12 12:20:34', '2025-11-12 12:20:57'),
(16, 5, 'ORD-TV8QEDFZ', 379000.00, 0.00, 379000.00, 'paid', '2025-11-12 17:57:50', '2025-11-12 17:58:24'),
(17, 2, 'ORD-LHJAPSA0', 649000.00, 259600.00, 389400.00, 'failed', '2025-11-14 17:21:15', '2025-11-14 17:21:27'),
(18, 2, 'ORD-VEUSLD7I', 649000.00, 0.00, 649000.00, 'paid', '2025-11-15 04:04:42', '2025-11-15 04:05:18'),
(19, 3, 'ORD-A99ERDRL', 379000.00, 0.00, 379000.00, 'paid', '2025-11-15 18:19:38', '2025-11-15 18:19:40'),
(20, 1, 'ORD-1SS2AGDK', 0.00, 0.00, 0.00, 'paid', '2025-11-16 07:40:16', '2025-11-16 07:40:16'),
(21, 2, 'ORD-OLDJ8H3V', 0.00, 0.00, 0.00, 'paid', '2025-11-16 15:40:35', '2025-11-16 15:40:35'),
(22, 5, 'ORD-KSRYFYZN', 0.00, 0.00, 0.00, 'paid', '2025-11-16 15:41:34', '2025-11-16 15:41:34');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `course_id` bigint UNSIGNED NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `course_id`, `price`, `created_at`, `updated_at`) VALUES
(1, 1, 13, 550000.00, '2025-11-11 19:13:45', '2025-11-11 19:13:45'),
(2, 2, 31, 379000.00, '2025-11-11 19:30:40', '2025-11-11 19:30:40'),
(3, 3, 24, 449000.00, '2025-11-11 19:43:22', '2025-11-11 19:43:22'),
(4, 4, 30, 699000.00, '2025-11-12 09:46:10', '2025-11-12 09:46:10'),
(5, 5, 30, 699000.00, '2025-11-12 10:13:53', '2025-11-12 10:13:53'),
(6, 6, 30, 699000.00, '2025-11-12 10:14:06', '2025-11-12 10:14:06'),
(7, 7, 30, 699000.00, '2025-11-12 10:14:36', '2025-11-12 10:14:36'),
(8, 8, 31, 379000.00, '2025-11-12 10:19:51', '2025-11-12 10:19:51'),
(9, 9, 31, 379000.00, '2025-11-12 10:24:02', '2025-11-12 10:24:02'),
(10, 10, 31, 379000.00, '2025-11-12 10:29:37', '2025-11-12 10:29:37'),
(11, 11, 31, 379000.00, '2025-11-12 10:42:41', '2025-11-12 10:42:41'),
(12, 12, 31, 379000.00, '2025-11-12 10:53:04', '2025-11-12 10:53:04'),
(13, 13, 26, 579000.00, '2025-11-12 11:50:02', '2025-11-12 11:50:02'),
(14, 14, 26, 579000.00, '2025-11-12 11:50:28', '2025-11-12 11:50:28'),
(15, 15, 26, 579000.00, '2025-11-12 12:20:34', '2025-11-12 12:20:34'),
(16, 15, 19, 399000.00, '2025-11-12 12:20:34', '2025-11-12 12:20:34'),
(17, 16, 31, 379000.00, '2025-11-12 17:57:50', '2025-11-12 17:57:50'),
(18, 17, 32, 649000.00, '2025-11-14 17:21:15', '2025-11-14 17:21:15'),
(19, 18, 32, 649000.00, '2025-11-15 04:04:42', '2025-11-15 04:04:42'),
(20, 19, 31, 379000.00, '2025-11-15 18:19:38', '2025-11-15 18:19:38'),
(21, 20, 35, 0.00, '2025-11-16 07:40:16', '2025-11-16 07:40:16'),
(22, 21, 35, 0.00, '2025-11-16 15:40:35', '2025-11-16 15:40:35'),
(23, 22, 35, 0.00, '2025-11-16 15:41:34', '2025-11-16 15:41:34');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `provider` enum('bank_transfer','vnpay','momo','paypal') COLLATE utf8mb4_general_ci NOT NULL,
  `transaction_id` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('initiated','succeeded','failed','refunded') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'initiated',
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `provider`, `transaction_id`, `amount`, `status`, `meta`, `created_at`, `updated_at`) VALUES
(1, 6, 'momo', 'TXN17629424511711', 699000.00, 'failed', '{\"payment_time\":\"2025-11-12 10:14:11\",\"method\":\"momo\",\"reason\":\"User cancelled\"}', '2025-11-12 10:14:11', '2025-11-12 10:14:11'),
(2, 7, 'momo', 'TXN17629424823552', 699000.00, 'succeeded', '{\"payment_time\":\"2025-11-12 10:14:42\",\"method\":\"momo\"}', '2025-11-12 10:14:42', '2025-11-12 10:14:42'),
(3, 9, 'momo', 'TXN17629432377204', 379000.00, 'failed', '{\"payment_time\":\"2025-11-12 10:27:17\",\"method\":\"momo\",\"reason\":\"User cancelled\"}', '2025-11-12 10:27:17', '2025-11-12 10:27:17'),
(4, 10, 'momo', '1762943975608', 379000.00, 'failed', '{\"order\":\"10_1762943377\",\"partnerCode\":\"MOMOFOEL20251112_TEST\",\"orderId\":\"10_1762943377\",\"requestId\":\"1762943377\",\"amount\":\"379000\",\"orderInfo\":\"Thanh toan don hang #ORD-YCENTADD\",\"orderType\":\"momo_wallet\",\"transId\":\"1762943975608\",\"resultCode\":\"1005\",\"message\":\"Giao d\\u1ecbch \\u0111\\u00e3 h\\u1ebft h\\u1ea1n ho\\u1eb7c kh\\u00f4ng t\\u1ed3n t\\u1ea1i.\",\"payType\":null,\"responseTime\":\"1762943975634\",\"extraData\":null,\"signature\":\"110c5db86ea4553c9fc9f625375246b6a92800329ca0d0e097e905bbbca2f825\"}', '2025-11-12 10:39:44', '2025-11-12 10:39:44'),
(5, 11, 'momo', '1762944771481', 379000.00, 'failed', '{\"order\":\"11_1762944161\",\"partnerCode\":\"MOMOFOEL20251112_TEST\",\"orderId\":\"11_1762944161\",\"requestId\":\"1762944161\",\"amount\":\"379000\",\"orderInfo\":\"Thanh toan don hang #ORD-QAPDIF2D\",\"orderType\":\"momo_wallet\",\"transId\":\"1762944771481\",\"resultCode\":\"1005\",\"message\":\"Giao d\\u1ecbch \\u0111\\u00e3 h\\u1ebft h\\u1ea1n ho\\u1eb7c kh\\u00f4ng t\\u1ed3n t\\u1ea1i.\",\"payType\":null,\"responseTime\":\"1762944771483\",\"extraData\":null,\"signature\":\"15a7b3ca23b0ba5a99791dde7d10102a12314754de281d7d9b4434e05a66c42a\"}', '2025-11-12 10:52:57', '2025-11-12 10:52:57'),
(6, 12, 'momo', '4611181379', 379000.00, 'succeeded', '{\"order\":\"12_1762944784\",\"partnerCode\":\"MOMOFOEL20251112_TEST\",\"orderId\":\"12_1762944784\",\"requestId\":\"1762944784\",\"amount\":\"379000\",\"orderInfo\":\"Thanh toan don hang #ORD-G4WO92WG\",\"orderType\":\"momo_wallet\",\"transId\":\"4611181379\",\"resultCode\":\"0\",\"message\":\"Th\\u00e0nh c\\u00f4ng.\",\"payType\":\"qr\",\"responseTime\":\"1762944799107\",\"extraData\":null,\"signature\":\"feb6bd439eeb28beee56ef3ec54330599046534e2a4c037557a9c574e3c75d19\"}', '2025-11-12 10:53:25', '2025-11-12 10:53:25'),
(7, 13, 'momo', '1762948204780', 579000.00, 'failed', '{\"order\":\"13_1762948202\",\"partnerCode\":\"MOMOFOEL20251112_TEST\",\"orderId\":\"13_1762948202\",\"requestId\":\"1762948202\",\"amount\":\"579000\",\"orderInfo\":\"Thanh toan don hang #ORD-RWIW2UDC\",\"orderType\":\"momo_wallet\",\"transId\":\"1762948204780\",\"resultCode\":\"1006\",\"message\":\"Giao d\\u1ecbch b\\u1ecb t\\u1eeb ch\\u1ed1i b\\u1edfi ng\\u01b0\\u1eddi d\\u00f9ng.\",\"payType\":null,\"responseTime\":\"1762948204783\",\"extraData\":null,\"signature\":\"f0f1af40f9217e57324fa2f05ddad27c6521793b7ce6fec945f15510f64febb8\"}', '2025-11-12 11:50:11', '2025-11-12 11:50:11'),
(8, 14, 'vnpay', 'TXN17629482561694', 579000.00, 'failed', '{\"payment_time\":\"2025-11-12 11:50:56\",\"method\":\"vnpay\",\"reason\":\"User cancelled\"}', '2025-11-12 11:50:56', '2025-11-12 11:50:56'),
(9, 15, 'momo', '1762950051181', 978000.00, 'failed', '{\"order\":\"15_1762950034\",\"partnerCode\":\"MOMOFOEL20251112_TEST\",\"orderId\":\"15_1762950034\",\"requestId\":\"1762950034\",\"amount\":\"978000\",\"orderInfo\":\"Thanh toan don hang #ORD-JG9K7ORS\",\"orderType\":\"momo_wallet\",\"transId\":\"1762950051181\",\"resultCode\":\"1006\",\"message\":\"Giao d\\u1ecbch b\\u1ecb t\\u1eeb ch\\u1ed1i b\\u1edfi ng\\u01b0\\u1eddi d\\u00f9ng.\",\"payType\":null,\"responseTime\":\"1762950051183\",\"extraData\":null,\"signature\":\"54e938a76c0f8d31136a27e4fc31f54166ae7d6713da4b25cd53d25308ea8550\"}', '2025-11-12 12:20:57', '2025-11-12 12:20:57'),
(10, 15, 'momo', '1762950051181', 978000.00, 'failed', '{\"order\":\"15_1762950034\",\"partnerCode\":\"MOMOFOEL20251112_TEST\",\"orderId\":\"15_1762950034\",\"requestId\":\"1762950034\",\"amount\":\"978000\",\"orderInfo\":\"Thanh toan don hang #ORD-JG9K7ORS\",\"orderType\":\"momo_wallet\",\"transId\":\"1762950051181\",\"resultCode\":\"1006\",\"message\":\"Giao d\\u1ecbch b\\u1ecb t\\u1eeb ch\\u1ed1i b\\u1edfi ng\\u01b0\\u1eddi d\\u00f9ng.\",\"payType\":null,\"responseTime\":\"1762950051183\",\"extraData\":null,\"signature\":\"54e938a76c0f8d31136a27e4fc31f54166ae7d6713da4b25cd53d25308ea8550\"}', '2025-11-12 12:20:57', '2025-11-12 12:20:57'),
(11, 16, 'momo', '4611400441', 379000.00, 'succeeded', '{\"order\":\"16_1762970271\",\"partnerCode\":\"MOMOFOEL20251112_TEST\",\"orderId\":\"16_1762970271\",\"requestId\":\"1762970271\",\"amount\":\"379000\",\"orderInfo\":\"Thanh toan don hang #ORD-TV8QEDFZ\",\"orderType\":\"momo_wallet\",\"transId\":\"4611400441\",\"resultCode\":\"0\",\"message\":\"Th\\u00e0nh c\\u00f4ng.\",\"payType\":\"qr\",\"responseTime\":\"1762970297812\",\"extraData\":null,\"signature\":\"91ee16f232320173ac391f2ce2ef8f70829c51aad99473de593dac530686a3bf\"}', '2025-11-12 17:58:24', '2025-11-12 17:58:24'),
(12, 17, 'momo', '1763140880341', 389400.00, 'failed', '{\"order\":\"17_1763140876\",\"partnerCode\":\"MOMOFOEL20251112_TEST\",\"orderId\":\"17_1763140876\",\"requestId\":\"1763140876\",\"amount\":\"389400\",\"orderInfo\":\"Thanh toan don hang #ORD-LHJAPSA0\",\"orderType\":\"momo_wallet\",\"transId\":\"1763140880341\",\"resultCode\":\"1006\",\"message\":\"Giao d\\u1ecbch b\\u1ecb t\\u1eeb ch\\u1ed1i b\\u1edfi ng\\u01b0\\u1eddi d\\u00f9ng.\",\"payType\":null,\"responseTime\":\"1763140880343\",\"extraData\":null,\"signature\":\"da54fc237e858587070c471bb62c13b80e81b3a3db6b23dd4323c143146a67fd\"}', '2025-11-14 17:21:27', '2025-11-14 17:21:27'),
(13, 18, 'bank_transfer', 'BANK17631794884040', 649000.00, 'initiated', '{\"payment_time\":\"2025-11-15 04:04:48\",\"method\":\"bank_transfer\",\"bank_name\":\"tju\",\"transaction_code\":\"try\",\"note\":null}', '2025-11-15 04:04:48', '2025-11-15 04:04:48'),
(14, 18, 'vnpay', 'TXN17631795186177', 649000.00, 'succeeded', '{\"payment_time\":\"2025-11-15 04:05:18\",\"method\":\"vnpay\"}', '2025-11-15 04:05:18', '2025-11-15 04:05:18'),
(15, 19, 'vnpay', 'TXN17632307806415', 379000.00, 'succeeded', '{\"payment_time\":\"2025-11-15 18:19:40\",\"method\":\"vnpay\"}', '2025-11-15 18:19:40', '2025-11-15 18:19:40');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `course_id` bigint UNSIGNED NOT NULL,
  `rating` tinyint UNSIGNED NOT NULL,
  `content` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `course_id`, `rating`, `content`, `created_at`, `updated_at`) VALUES
(2, 2, 13, 5, 'ád', '2025-11-11 19:20:17', '2025-11-11 19:20:17'),
(3, 1, 35, 5, 'Quá tốt rồi', '2025-11-16 16:00:36', '2025-11-16 16:00:36');

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` bigint UNSIGNED NOT NULL,
  `course_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `position` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `course_id`, `title`, `position`, `created_at`, `updated_at`) VALUES
(2, 31, 'Phần 1', 1, '2025-11-11 19:26:53', '2025-11-11 19:26:53'),
(3, 32, 'gf', 1, '2025-11-12 05:35:46', '2025-11-12 05:35:46'),
(5, 31, 'phần 2', 2, '2025-11-12 18:11:22', '2025-11-12 18:11:22'),
(6, 30, 'Phần 1: Hướng Dẫn Viết Prompt Hiệu Quả', 1, '2025-11-12 18:24:52', '2025-11-12 18:24:52'),
(7, 30, 'Phần 2: Thực Hành Với Các Công Cụ', 2, '2025-11-12 18:28:33', '2025-11-12 18:28:33'),
(8, 15, 'y', 1, '2025-11-14 18:52:23', '2025-11-14 18:52:23'),
(9, 31, 'df', 3, '2025-11-15 17:55:42', '2025-11-15 17:55:42'),
(10, 35, 'Phần 1: Giới thiệu', 1, '2025-11-16 06:08:29', '2025-11-16 06:08:29'),
(11, 35, 'Phần 2: Biến và kiểu dữ liệu', 2, '2025-11-16 06:08:54', '2025-11-16 06:08:54'),
(12, 35, 'Phần 3: Cấu trúc điều khiển và vòng lặp', 3, '2025-11-16 06:09:17', '2025-11-16 06:09:17'),
(13, 35, 'Phần 4: Mảng', 4, '2025-11-16 06:09:32', '2025-11-16 06:09:32'),
(14, 35, 'Phần 5: Hàm', 5, '2025-11-16 06:09:41', '2025-11-16 06:09:41'),
(15, 35, 'test', 6, '2025-11-16 13:02:15', '2025-11-16 13:02:15');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('0UOFCGoLXrMxEbovfaqJ5bj5M2fwarwsZiVuNno4', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoiYkd5MFphWWE3MjY2YUdQODJlYUpWYnJJakhYbUFCaE5zbjc5blBDdiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jb3Vyc2VzL0MrK19jb19iYW4iO3M6NToicm91dGUiO3M6MTI6ImNvdXJzZXMuc2hvdyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MTU6ImFkbWluX2xvZ2dlZF9pbiI7YjoxO3M6MTM6ImFkbWluX3VzZXJfaWQiO2k6MTtzOjM6InVybCI7YTowOnt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NTt9', 1763308481),
('KX6txOIbpkk3eZmyx0gzt56JeTheclOYEYIyXOhm', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoid0VwNnRnM1BwUEJCVG4wMzA5cTNnUXJBSEFRRDFaenZLR3c5b2YySyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7czo1OiJyb3V0ZSI7czoxMToiYWRtaW4ubG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1763307535),
('OhVRZw6iTy5cVF1paCTqQFjLEYqeQqUIyi0ZQRZc', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoidTlWZkJLNmRDT01JWm9PQnUwRUFwdkp0ekY0MU9CdGN1SG5lWWozZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1763308870);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('student','admin') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'student',
  `email_verified_at` datetime DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `avatar`, `password`, `role`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@example.com', NULL, '$2y$12$1K9GoUy/CSDezbSJADsfJ.RcBOd5QeC5B/3pEYnle.LMo2u7W6m7C', 'admin', NULL, NULL, '2025-10-15 13:08:10', '2025-10-15 13:08:10'),
(2, 'Trinh Nguyen', 'vietkel05x@gmail.com', NULL, '$2y$12$ah1AEiHtbUYhbv.dPPLtp.9R4xftJ51eINKdIbwbA4Ff3ck/NiF.O', 'student', NULL, NULL, '2025-10-29 14:10:21', '2025-10-29 14:10:21'),
(3, 'Việt', 'vietkel05@gmail.com', NULL, '$2y$12$./5p0/8ef9QBWd031HW9iuxRNQB3E2teC16KtY79dvSDOqkts4OlC', 'student', NULL, NULL, '2025-10-29 17:49:54', '2025-10-29 17:49:54'),
(4, 'Trinh Nguyen', 'vietkel05a@gmail.com', NULL, '$2y$12$bLzQUVg/Bn2hEHzc4JF1SuytuZaOLsMCq5a0Eph/7c32os579cEg.', 'student', NULL, NULL, '2025-11-11 19:16:02', '2025-11-11 19:16:02'),
(5, 'Việt', 'vietkel@gmail.com', NULL, '$2y$12$q.PuFOKd4LJpD2CbuAWaDuCeVjXg39u2GPg63rd4s0xhk2bw9comS', 'student', NULL, NULL, '2025-11-12 17:32:35', '2025-11-12 17:32:35');

-- --------------------------------------------------------

--
-- Table structure for table `user_notifications`
--

CREATE TABLE `user_notifications` (
  `notification_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `read_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `fk_categories_parent` (`parent_id`);

--
-- Indexes for table `category_media`
--
ALTER TABLE `category_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_media_category_id_foreign` (`category_id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `idx_coupons_active_window` (`starts_at`,`ends_at`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `fk_courses_instructor` (`instructor_id`),
  ADD KEY `idx_courses_status` (`status`);
ALTER TABLE `courses` ADD FULLTEXT KEY `ftx_courses` (`title`,`short_description`,`description`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_enroll_user_course` (`user_id`,`course_id`),
  ADD KEY `idx_enroll_course` (`course_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_lessons_section_pos` (`section_id`,`position`);

--
-- Indexes for table `lesson_progress`
--
ALTER TABLE `lesson_progress`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_progress_user_lesson` (`user_id`,`lesson_id`),
  ADD KEY `fk_progress_lesson` (`lesson_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_notifications_admin` (`created_by`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `fk_orders_user` (`user_id`),
  ADD KEY `idx_orders_status_created` (`status`,`created_at`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_item_order_course` (`order_id`,`course_id`),
  ADD KEY `fk_items_course` (`course_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_payments_order` (`order_id`),
  ADD KEY `idx_payments_provider_status` (`provider`,`status`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_review_user_course` (`user_id`,`course_id`),
  ADD KEY `idx_reviews_course` (`course_id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sections_course_pos` (`course_id`,`position`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_users_role` (`role`);

--
-- Indexes for table `user_notifications`
--
ALTER TABLE `user_notifications`
  ADD PRIMARY KEY (`notification_id`,`user_id`),
  ADD KEY `fk_un_user` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `category_media`
--
ALTER TABLE `category_media`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `lessons`
--
ALTER TABLE `lessons`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `lesson_progress`
--
ALTER TABLE `lesson_progress`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `fk_categories_parent` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `category_media`
--
ALTER TABLE `category_media`
  ADD CONSTRAINT `category_media_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `fk_courses_instructor` FOREIGN KEY (`instructor_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `fk_enroll_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_enroll_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lessons`
--
ALTER TABLE `lessons`
  ADD CONSTRAINT `fk_lessons_section` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lesson_progress`
--
ALTER TABLE `lesson_progress`
  ADD CONSTRAINT `fk_progress_lesson` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_progress_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_notifications_admin` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_items_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`),
  ADD CONSTRAINT `fk_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payments_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reviews_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sections`
--
ALTER TABLE `sections`
  ADD CONSTRAINT `fk_sections_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_notifications`
--
ALTER TABLE `user_notifications`
  ADD CONSTRAINT `fk_un_notification` FOREIGN KEY (`notification_id`) REFERENCES `notifications` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_un_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
