-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 11, 2020 at 05:26 PM
-- Server version: 5.7.26
-- PHP Version: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `car`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

DROP TABLE IF EXISTS `booking`;
CREATE TABLE IF NOT EXISTS `booking` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(255) DEFAULT '',
  `transaction_datetime` datetime DEFAULT NULL,
  `refund_id` varchar(255) DEFAULT '',
  `refund_datetime` datetime DEFAULT NULL,
  `vehicle_category_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `customer_mobile_number` varchar(20) DEFAULT '',
  `hire_provider_id` int(11) DEFAULT NULL,
  `hire_vehicle_id` int(11) DEFAULT NULL,
  `hire_amount` decimal(10,2) DEFAULT '0.00',
  `booking_number` varchar(20) DEFAULT '',
  `transfer_datetime` datetime DEFAULT NULL,
  `booking_type` enum('route','hour') DEFAULT NULL,
  `booking_status` enum('pending','hired','canceled','completed') DEFAULT NULL,
  `from_location` varchar(255) DEFAULT '',
  `from_lat` decimal(10,6) DEFAULT '0.000000',
  `from_lon` decimal(10,6) DEFAULT '0.000000',
  `to_location` varchar(255) DEFAULT '',
  `to_lat` decimal(10,6) DEFAULT '0.000000',
  `to_lon` decimal(10,6) DEFAULT '0.000000',
  `is_return_way` tinyint(4) DEFAULT '0',
  `return_datetime` datetime DEFAULT NULL COMMENT 'transfer date',
  `no_of_adult` int(11) DEFAULT '0',
  `no_of_children` int(11) DEFAULT '0',
  `is_flight` tinyint(4) DEFAULT '0',
  `flight_no` varchar(255) DEFAULT '',
  `is_meeting` tinyint(4) DEFAULT '0',
  `passenger_name` varchar(255) DEFAULT '',
  `is_promo_code` tinyint(4) DEFAULT '0',
  `promo_code` varchar(255) DEFAULT '',
  `requirement` varchar(255) DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`id`, `transaction_id`, `transaction_datetime`, `refund_id`, `refund_datetime`, `vehicle_category_id`, `customer_id`, `customer_mobile_number`, `hire_provider_id`, `hire_vehicle_id`, `hire_amount`, `booking_number`, `transfer_datetime`, `booking_type`, `booking_status`, `from_location`, `from_lat`, `from_lon`, `to_location`, `to_lat`, `to_lon`, `is_return_way`, `return_datetime`, `no_of_adult`, `no_of_children`, `is_flight`, `flight_no`, `is_meeting`, `passenger_name`, `is_promo_code`, `promo_code`, `requirement`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '', NULL, '', NULL, 2, 2, '9988776655', NULL, NULL, '50000.00', '0001', '2020-11-12 12:30:00', 'route', 'hired', 'jalandhar', '75.360000', '31.380000', 'ludhiana', '74.360000', '32.380000', 1, '2020-11-12 13:00:00', 5, 7, 1, '112233', 1, 'name of passenger', 1, '1122', 'booking.created', '2020-11-12 07:14:53', '2020-11-12 07:14:53', NULL),
(2, '', NULL, '', NULL, 2, 2, '9988776655', NULL, NULL, '0.00', '0002', '2020-11-12 12:30:00', 'route', 'canceled', 'jalandhar', '75.360000', '31.380000', 'ludhiana', '74.360000', '32.380000', 0, NULL, 7, 10, 0, '', 0, '', 0, '', 'fffffffff', '2020-11-12 07:15:46', '2020-11-25 17:34:18', NULL),
(3, '', NULL, '', NULL, 2, 2, '9988776654', NULL, NULL, '0.00', '0003', '2020-11-12 15:00:00', 'hour', 'canceled', 'bus stand jalandhar', '75.360000', '31.380000', 'bus stand ludhiana', '74.360000', '32.380000', 1, '2020-11-12 15:30:00', 3, 14, 1, 'ddddddddd', 1, 'eeeeeeeeee', 1, 'ffffffffff', 'ddddddddddddd', '2020-11-12 09:04:30', '2020-11-27 15:40:35', NULL),
(4, '', NULL, '', NULL, 3, 2, '1122334455', NULL, NULL, '0.00', '0004', '2020-11-13 16:30:00', 'route', 'hired', 'jalandhar', '75.360000', '31.380000', 'ludhiana', '74.360000', '32.380000', 1, '2020-11-26 17:15:00', 10, 10, 0, '', 0, '', 0, '', 'fffffffff', '2020-11-12 10:48:43', '2020-11-12 10:48:43', NULL),
(5, '', NULL, '', NULL, 3, 2, '1122334455', NULL, NULL, '0.00', '0005', '2020-11-13 16:30:00', 'route', 'completed', 'jalandhar', '75.360000', '31.380000', 'ludhiana', '74.360000', '32.380000', 1, '2020-11-26 17:15:00', 10, 10, 0, '', 0, '', 0, '', 'fffffffff', '2020-11-12 10:48:43', '2020-11-12 10:48:43', NULL),
(6, '', NULL, '', NULL, 3, 2, '1122334455', NULL, NULL, '0.00', '0006', '2020-11-13 16:30:00', 'route', 'canceled', 'jalandhar', '75.360000', '31.380000', 'ludhiana', '74.360000', '32.380000', 1, '2020-11-26 17:15:00', 10, 10, 0, '', 0, '', 0, '', 'fffffffff', '2020-11-12 10:48:43', '2020-11-12 10:48:43', NULL),
(7, '', NULL, '', NULL, 1, 11, '9988776655', NULL, NULL, '0.00', '0007', '2020-11-18 13:45:00', 'route', 'pending', 'jalandhar', '75.360000', '31.380000', 'ludhiana', '74.360000', '32.380000', 0, NULL, 10, 10, 1, '112233', 1, 'gggggggg', 1, 'lllllll', 'eeeeeeeeee', '2020-11-18 08:28:39', '2020-11-18 08:28:39', NULL),
(14, '', NULL, '', NULL, 3, 18, '1133445566', NULL, NULL, '0.00', '0014', '2020-11-22 12:15:00', 'route', 'pending', 'jalandhar', '75.360000', '31.380000', 'ludhiana', '74.360000', '32.380000', 0, NULL, 6, 4, 0, '', 0, '', 0, '', NULL, '2020-11-22 06:40:12', '2020-11-22 06:40:12', NULL),
(13, '', NULL, '', NULL, 3, 17, '9988776655', NULL, NULL, '0.00', '0013', '2020-11-18 19:45:00', 'route', 'pending', 'jalandhar', '75.360000', '31.380000', 'ludhiana', '74.360000', '32.380000', 1, '2020-11-26 20:30:00', 10, 10, 1, 'gggggggg', 1, 'kkkkk', 1, 'llllllll', 'mmmmmmmmmm', '2020-11-18 14:15:22', '2020-11-18 14:15:22', NULL),
(12, '', NULL, '', NULL, 3, 16, '9988776655', NULL, NULL, '0.00', '0008', '2020-11-18 19:15:00', 'route', 'pending', 'jalandhar', '75.360000', '31.380000', 'ludhiana', '74.360000', '32.380000', 1, '2020-11-26 19:15:00', 10, 10, 1, 'hhhhhhh', 1, 'mmmmmmm', 1, 'ppppppp', 'dddddddddddd', '2020-11-18 13:35:25', '2020-11-18 13:35:25', NULL),
(15, '', NULL, '', NULL, 3, 2, '', NULL, NULL, '0.00', '', NULL, 'route', NULL, 'jalandhar', '75.360000', '31.380000', 'ludhiana', '74.360000', '32.380000', 0, NULL, 0, 0, 0, '', 0, '', 0, '', '', '2020-11-24 18:00:45', '2020-11-24 18:00:45', NULL),
(16, '', NULL, '', NULL, 4, 2, '9876855626', NULL, NULL, '0.00', '0016', '2020-11-24 23:45:00', 'hour', 'canceled', 'jalandhar', '75.360000', '31.380000', 'ludhiana', '74.360000', '32.380000', 1, '2020-11-24 23:45:00', 2, 2, 1, '112233', 1, 'mmmmmmm', 1, 'ppppppp', 'fffffffffff', '2020-11-24 18:14:43', '2020-11-27 15:38:29', NULL),
(17, '', NULL, '', NULL, 4, 2, '9876855626', NULL, NULL, '0.00', '0017', '2020-11-24 23:45:00', 'route', 'canceled', 'jalandhar', '75.360000', '31.380000', 'ludhiana', '74.360000', '32.380000', 1, '2020-11-24 22:45:00', 12, 4, 1, '112233', 1, 'mmmmmmm', 1, 'ppppppp', 'fffffffffff', '2020-11-24 18:16:12', '2020-11-27 15:37:43', NULL),
(18, '', NULL, '', NULL, 4, 2, '9876855626', NULL, NULL, '0.00', '0018', '2020-11-24 12:00:00', 'hour', 'canceled', 'jalandhar', '75.360000', '31.380000', 'ludhiana', '74.360000', '32.380000', 1, '2020-11-24 12:00:00', 2, 2, 0, '', 0, '', 0, '', NULL, '2020-11-24 18:20:43', '2020-11-25 17:56:10', NULL),
(19, '', NULL, '', NULL, 4, 21, '9988776655', NULL, NULL, '0.00', '0019', '2020-11-24 12:00:00', 'hour', 'pending', 'jalandhar', '75.360000', '31.380000', 'ludhiana', '74.360000', '32.380000', 1, '2020-11-24 12:00:00', 2, 2, 0, '', 0, '', 0, '', NULL, '2020-11-24 18:28:36', '2020-11-24 18:28:36', NULL),
(20, '', NULL, '', NULL, 4, 21, '9988776654', NULL, NULL, '0.00', '0020', '2020-11-24 12:00:00', 'hour', 'pending', 'jalandhar', '75.360000', '31.380000', 'ludhiana', '74.360000', '32.380000', 1, '2020-11-24 12:00:00', 2, 2, 0, '', 0, '', 0, '', NULL, '2020-11-24 18:28:58', '2020-11-24 18:28:58', NULL),
(21, '', NULL, '', NULL, 4, 2, '9876855626', NULL, NULL, '0.00', '0021', '2020-11-26 23:15:00', 'route', 'canceled', 'jalandharss', '75.360000', '31.380000', 'ludhiana', '74.360000', '32.380000', 0, NULL, 2, 2, 0, '', 0, '', 0, '', NULL, '2020-11-26 17:43:30', '2020-11-27 15:37:16', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `booking_quotation`
--

DROP TABLE IF EXISTS `booking_quotation`;
CREATE TABLE IF NOT EXISTS `booking_quotation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) DEFAULT NULL,
  `provider_id` int(11) DEFAULT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `quotation_date` datetime DEFAULT NULL,
  `quotation_amount` decimal(10,2) DEFAULT '0.00',
  `is_canceled` tinyint(4) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `booking_quotation`
--

INSERT INTO `booking_quotation` (`id`, `booking_id`, `provider_id`, `vehicle_id`, `quotation_date`, `quotation_amount`, `is_canceled`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 8, 1, '2020-11-14 11:40:00', '50000.00', 0, NULL, NULL, NULL),
(2, 1, 8, 1, '2020-11-14 11:40:00', '50000000.00', 0, NULL, NULL, NULL),
(3, 2, 8, 1, '2020-11-14 11:40:00', '50000.00', 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT '0',
  `category_number` varchar(255) DEFAULT '',
  `category_name` varchar(255) DEFAULT '',
  `description` text,
  `category_image` varchar(255) DEFAULT '',
  `status` tinyint(4) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `parent_id`, `category_number`, `category_name`, `description`, `category_image`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 0, '0001', 'MINI', 'MINI', 'GdtSBU33_mini.png', 1, '2020-11-14 09:45:43', '2020-11-14 09:50:20', NULL),
(2, 0, '0002', 'SEDAN', 'SEDAN', 'J28KHzLF_sedan.png', 1, '2020-11-14 09:46:32', '2020-11-14 09:48:57', NULL),
(3, 0, '0003', 'LUXURY', 'LUXURY', 'Ukzz6qv7_luxury.png', 1, '2020-11-14 09:49:21', '2020-11-14 09:49:21', NULL),
(4, 0, '0004', 'MINI BUS', 'MINI BUS', 'BllViXpU_minibus.png', 1, '2020-11-14 09:49:56', '2020-11-14 09:49:56', NULL),
(5, 0, '0005', 'BUS', 'BUS', 'Q1Q9yDWl_bus.png', 1, '2020-11-14 09:50:12', '2020-11-14 09:50:12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_type` enum('admin','customer','provider') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rozarpay_customer_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile_number` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fcm_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `status` tinyint(4) DEFAULT '0',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `forgot_otp` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `otp_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `device_type` enum('android','ios') COLLATE utf8mb4_unicode_ci DEFAULT 'android',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_type`, `rozarpay_customer_id`, `name`, `mobile_number`, `email`, `fcm_token`, `status`, `email_verified_at`, `password`, `forgot_otp`, `address`, `remember_token`, `created_at`, `updated_at`, `otp_code`, `device_type`) VALUES
(1, 'admin', '', 'Admin', '1122334455', 'admin@gmail.com', '', 1, NULL, '$2y$10$u8.quz1P0gf90Z7Q5Jp0v./SQxrg.znenT/pOrp3.h9LQvIvVRFAe', '', '', NULL, '2020-06-09 10:22:23', '2020-09-10 03:52:18', '5627', 'android'),
(2, 'customer', 'cust_FpUZjUvCd1zWMs', 'Sumeet', '9876855626', 'sumeet@gmail.com', 'eASAazgOADo:APA91bE2FvsWdlbCCLrjpqmb5zmr9SxHYg1jvyaQF39knJRHpXOmFVud6CcAZthwj2EalCdqDxNWWcurzn5vfA7j1TuU7hixZfAwdkddlV_9WexIAqElPggnHjV_7hXAxLPJLwblkYWn', 1, NULL, '$2y$10$A9GIRg56OixnM02UZYwmP.XLJTyX4qQyvOG5Cdi3/PyUGp3BK8TGG', '1631', '', NULL, '2020-08-09 04:44:05', '2020-12-11 13:53:28', '2180', 'android'),
(7, 'customer', '', 'Sukhdeep', '9855719603', 'sukh2@gmail.com', 'd3qVcipo-WQ:APA91bGx6kyQSl-cV68YBesjDyEp_OXfTZ91ggB9MgjXPUkIdI9zUPeXCgbrgplirKbnOSmqh_UocD8CYS0rG7WKugMuQrr5YvuJURRx27_0xkfW-pZisvqE4rCC39rYneHZd049Yydb', 1, NULL, '$2y$10$qR754ArvdT7PI2oNa6tDJOCq5xN9Rz3Vy0/C/thaPiUkA93SuDORC', '', '', NULL, '2020-08-09 18:37:58', '2020-10-06 02:30:47', '3678', 'android'),
(8, 'provider', '', 'Provider 1', '8054500805', 'manavmujral@gmail.com', 'fXrBHTd9v2w:APA91bEmcnoujtkpKbgkKriyoDIurJ3zzdDICwcioGg9V4EmnEAP3-1SbQACe5Iul6oHZsbeE64PlIjnM4LyQwHqtAX-Mxd3MJMItpItNHoKomaCcUSLBF8cbTQVAhfYY_LOPj2a6kdb', 1, NULL, '$2y$10$GIgcxT7UUw7vdlRXlC/RMO8c6q/GMdOmXe390dnOfeYiAom7E04Am', '', '', NULL, '2020-08-14 21:31:25', '2020-10-05 21:07:23', '9564', 'android'),
(9, 'provider', '', 'Provider 2', '8054500806', 'provider2@gmail.com', 'fXrBHTd9v2w:APA91bEmcnoujtkpKbgkKriyoDIurJ3zzdDICwcioGg9V4EmnEAP3-1SbQACe5Iul6oHZsbeE64PlIjnM4LyQwHqtAX-Mxd3MJMItpItNHoKomaCcUSLBF8cbTQVAhfYY_LOPj2a6kdb', 1, NULL, '$2y$10$GIgcxT7UUw7vdlRXlC/RMO8c6q/GMdOmXe390dnOfeYiAom7E04Am', '', '', NULL, '2020-08-14 21:31:25', '2020-10-05 21:07:23', '9564', 'android'),
(10, 'customer', '', 'Customer 1', '1122334455', 'customer1@gmail.com', '', 1, NULL, '$2y$10$XFBON2yWx91UYCl/49qpYOyVHk7iGpjfktarHdYfy50fQmER.xh7u', '', '', NULL, '2020-11-17 19:20:01', '2020-11-17 19:20:01', '', 'android'),
(18, 'customer', '', 'fffffff', '1133445566', 'fffff@gmail.com', '', 1, NULL, '$2y$10$p2l1UzmcdpfqbL/R.C0r4ukNCMVyXTbBtLe8CnC.3IYdskkSsY3fK', '', '', NULL, '2020-11-22 06:39:48', '2020-11-22 06:39:48', '', 'android'),
(19, 'customer', '', '', '', 'admin@gmail.com', '', 1, NULL, '$2y$10$qfQVTRGLJZf2zvnrFyj6HuSViwnpdPotgbV4hpuyVpDcm28N7Qo6e', '', '', NULL, '2020-11-24 18:12:22', '2020-11-24 18:12:22', '', 'android'),
(17, 'customer', '', '', '9988776655', 'sumeetnarula1@gmail.com', '', 1, NULL, '$2y$10$nL0DJStJQnyQZikKElIaxuYmfGwWCCuV2ChXqVqj1FP0ni7KbC68a', '', '', NULL, '2020-11-18 14:15:16', '2020-11-18 14:15:16', '', 'android'),
(21, 'customer', '', '', '9988776655', 'newtest@gmail.com', '', 1, NULL, '$2y$10$y3O5CPwqxrQOFUZ9z6az9uOXW/MEz2zJRYCjioHpaQPGeSCGzAse6', '', '', NULL, '2020-11-24 18:27:01', '2020-11-24 18:28:36', '', 'android');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
