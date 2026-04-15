-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 14, 2026 at 04:42 AM
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
-- Database: `neptuneplay`
--

-- --------------------------------------------------------

--
-- Table structure for table `agent_tokens`
--

CREATE TABLE `agent_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `token` text NOT NULL,
  `expiration` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `betting_histories`
--

CREATE TABLE `betting_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `history_id` int(11) DEFAULT NULL,
  `user_code` varchar(255) NOT NULL,
  `round_id` varchar(255) DEFAULT NULL,
  `game_code` varchar(255) DEFAULT NULL,
  `game_name` varchar(255) DEFAULT NULL,
  `vendor_code` varchar(255) DEFAULT NULL,
  `bet_amount` decimal(16,4) NOT NULL DEFAULT 0.0000,
  `win_amount` decimal(16,4) NOT NULL DEFAULT 0.0000,
  `before_balance` decimal(16,4) NOT NULL DEFAULT 0.0000,
  `after_balance` decimal(16,4) NOT NULL DEFAULT 0.0000,
  `detail` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '0=Unfinished, 1=Finished, 2=Canceled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-5c56ec2727103d6f24c57742f394c1cf', 'i:2;', 1776064467),
('laravel-cache-5c56ec2727103d6f24c57742f394c1cf:timer', 'i:1776064467;', 1776064467),
('laravel-cache-captcha_4IeKj1YknE8WzMIXsPA4KKgekoHGd3Gz', 's:5:\"75867\";', 1776061658),
('laravel-cache-captcha_4jdzilC2ZrHQK5hfJqQn3pd46bDQBdIk', 's:5:\"71487\";', 1776061661),
('laravel-cache-captcha_Ha0FBSrS7R6GEFl9OuHS4OdDj5dzwciR', 's:5:\"28279\";', 1776061669),
('laravel-cache-captcha_tYDWH4JmBnJpnZWiqWJuXdtCkcLlesfy', 's:5:\"87834\";', 1776059786),
('laravel-cache-neptuneplay_bearer_token', 's:632:\"eyJhbGciOiJIUzUxMiIsInR5cCI6IkpXVCJ9.eyJodHRwOi8vc2NoZW1hcy54bWxzb2FwLm9yZy93cy8yMDA1LzA1L2lkZW50aXR5L2NsYWltcy9uYW1laWRlbnRpZmllciI6Ijc2OTA4OGVkLWZhOWYtNDI0Mi1iNDNhLWE4MDFiZTNmNTEyOSIsImh0dHA6Ly9zY2hlbWFzLnhtbHNvYXAub3JnL3dzLzIwMDUvMDUvaWRlbnRpdHkvY2xhaW1zL25hbWUiOiJSb3lhbHRlY2giLCJqdGkiOiI1NmQ0ODA3MS0xMzI2LTQwOWItOTZkMi00ZDczZmM1YzIyYTQiLCJodHRwOi8vc2NoZW1hcy5taWNyb3NvZnQuY29tL3dzLzIwMDgvMDYvaWRlbnRpdHkvY2xhaW1zL3JvbGUiOiJBZ2VudCIsImV4cCI6MTc3NjEyMzE4OCwiaXNzIjoiaHR0cHM6Ly9sb2NhbGhvc3Q6NzE5My8iLCJhdWQiOiJodHRwczovL2xvY2FsaG9zdDo3MTkzLyJ9.lEJqRspKh9fnhjZ7-EkDsxvpx9TsRkemEcgcOTJd0xiJfIaeDwx-rbgtRoxnAux90AP5HFuq8SMTVCNGrUqgtA\";', 1776066691);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `call_histories`
--

CREATE TABLE `call_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `call_id` int(11) DEFAULT NULL,
  `user_code` varchar(255) NOT NULL,
  `vendor_code` varchar(255) DEFAULT NULL,
  `game_code` varchar(255) DEFAULT NULL,
  `game_name` varchar(255) DEFAULT NULL,
  `type_name` varchar(255) DEFAULT NULL COMMENT 'Free or Jackpot',
  `status_name` varchar(255) DEFAULT NULL COMMENT 'Reserve, Applied, Cancelled',
  `call_amount` decimal(16,4) NOT NULL DEFAULT 0.0000,
  `missed_amount` decimal(16,4) NOT NULL DEFAULT 0.0000,
  `applied_amount` decimal(16,4) NOT NULL DEFAULT 0.0000,
  `agent_before_balance` decimal(16,4) DEFAULT NULL,
  `agent_after_balance` decimal(16,4) DEFAULT NULL,
  `is_auto_call` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `vendor_code` varchar(255) NOT NULL,
  `game_id` varchar(255) DEFAULT NULL,
  `game_code` varchar(255) NOT NULL,
  `game_name` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `is_new` tinyint(1) NOT NULL DEFAULT 0,
  `under_maintenance` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_04_08_000001_create_players_table', 1),
(5, '2026_04_08_000002_create_transactions_table', 1),
(6, '2026_04_08_000003_create_vendors_table', 1),
(7, '2026_04_08_000004_create_games_table', 1),
(8, '2026_04_08_000005_create_betting_histories_table', 1),
(9, '2026_04_08_000006_create_user_balance_logs_table', 1),
(10, '2026_04_08_000007_create_call_histories_table', 1),
(11, '2026_04_08_000008_create_agent_tokens_table', 1),
(12, '2026_04_08_000009_create_user_rtps_table', 1),
(13, '2026_04_13_000001_add_user_code_to_users_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE `players` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_code` varchar(255) NOT NULL,
  `balance` decimal(16,4) NOT NULL DEFAULT 0.0000,
  `currency` varchar(10) NOT NULL DEFAULT 'USD',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `players`
--

INSERT INTO `players` (`id`, `user_code`, `balance`, `currency`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'i9Hs6X', 1000.0000, 'USD', 1, '2026-04-12 22:27:43', '2026-04-12 22:27:43');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('DjLudJZIhDcAaFLDR97J5lde53O2tgYljyPqXDvO', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMVB5Sjc0MVg0MmhzbmVLUGVzMVRBZkhmZ0NZYmV2VnJ5dlRVMVNsNSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776042296),
('l3QxNRwKG4mozHbp7OQDE0hj5jLta9FCmOZyFsCH', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidnlwYXBGRjV0SzZMcmlJYXZPMWppTXNza0lneWxyTThSRVJ0Q29qRyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1775631633),
('pW9DAyZbaa6IVoXzUVizmbI5YDEr7X7Du9WHuoSo', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYnh4S1puQ2thcDZnalhPZW5ySEFyVlpmMld2UDRVS3A0ZHpWT0pKdiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776042295),
('V4yYMK7dTXsUMoLvTB7oKgnU8Ul30QyuUN0iQ1NO', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidmwydlducXpIY1BWc05kYnBESGk0aU1sa0hXN3dTQXFkdTFDdDVaViI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776042295);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_code` varchar(255) NOT NULL,
  `player_id` bigint(20) UNSIGNED NOT NULL,
  `user_code` varchar(255) NOT NULL,
  `amount` decimal(16,4) NOT NULL,
  `balance_before` decimal(16,4) NOT NULL,
  `balance_after` decimal(16,4) NOT NULL,
  `vendor_code` varchar(255) DEFAULT NULL,
  `game_code` varchar(255) DEFAULT NULL,
  `round_id` varchar(255) DEFAULT NULL,
  `history_id` int(11) DEFAULT NULL,
  `game_type` int(11) DEFAULT NULL,
  `is_finished` tinyint(1) NOT NULL DEFAULT 0,
  `is_canceled` tinyint(1) NOT NULL DEFAULT 0,
  `detail` text DEFAULT NULL,
  `batch_id` varchar(255) DEFAULT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `user_code` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `user_code`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Player01', 'i9Hs6X', 'jhon@mybusybee.net', NULL, '$2y$12$ItU/xOoo/72wS8t0DjoOCeyheAC.c2SP4Sqj/nkLOtaLZmoidyPQK', NULL, '2026-04-12 22:27:41', '2026-04-12 22:27:41');

-- --------------------------------------------------------

--
-- Table structure for table `user_balance_logs`
--

CREATE TABLE `user_balance_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_no` varchar(255) DEFAULT NULL,
  `user_code` varchar(255) NOT NULL,
  `amount` decimal(16,4) NOT NULL,
  `type` int(11) NOT NULL COMMENT '1=Deposit, 2=Withdraw',
  `agent_before_balance` decimal(16,4) DEFAULT NULL,
  `user_before_balance` decimal(16,4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_rtps`
--

CREATE TABLE `user_rtps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_code` varchar(255) NOT NULL,
  `vendor_code` varchar(255) NOT NULL,
  `rtp` int(11) NOT NULL DEFAULT 85,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vendor_code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` int(11) NOT NULL COMMENT '1=Live Casino, 2=Slot, 3=Mini-game',
  `url` varchar(255) DEFAULT NULL,
  `under_maintenance` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agent_tokens`
--
ALTER TABLE `agent_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `betting_histories`
--
ALTER TABLE `betting_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `betting_histories_user_code_created_at_index` (`user_code`,`created_at`),
  ADD KEY `betting_histories_history_id_index` (`history_id`),
  ADD KEY `betting_histories_user_code_index` (`user_code`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `call_histories`
--
ALTER TABLE `call_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `call_histories_call_id_index` (`call_id`),
  ADD KEY `call_histories_user_code_index` (`user_code`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `games_vendor_code_game_code_unique` (`vendor_code`,`game_code`),
  ADD KEY `games_vendor_code_index` (`vendor_code`),
  ADD KEY `games_game_code_index` (`game_code`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `players`
--
ALTER TABLE `players`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `players_user_code_unique` (`user_code`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transactions_transaction_code_unique` (`transaction_code`),
  ADD KEY `transactions_player_id_foreign` (`player_id`),
  ADD KEY `transactions_user_code_round_id_index` (`user_code`,`round_id`),
  ADD KEY `transactions_user_code_index` (`user_code`),
  ADD KEY `transactions_batch_id_index` (`batch_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_user_code_unique` (`user_code`);

--
-- Indexes for table `user_balance_logs`
--
ALTER TABLE `user_balance_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_balance_logs_order_no_index` (`order_no`),
  ADD KEY `user_balance_logs_user_code_index` (`user_code`);

--
-- Indexes for table `user_rtps`
--
ALTER TABLE `user_rtps`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_rtps_user_code_vendor_code_unique` (`user_code`,`vendor_code`),
  ADD KEY `user_rtps_user_code_index` (`user_code`),
  ADD KEY `user_rtps_vendor_code_index` (`vendor_code`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vendors_vendor_code_unique` (`vendor_code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agent_tokens`
--
ALTER TABLE `agent_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `betting_histories`
--
ALTER TABLE `betting_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `call_histories`
--
ALTER TABLE `call_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `players`
--
ALTER TABLE `players`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_balance_logs`
--
ALTER TABLE `user_balance_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_rtps`
--
ALTER TABLE `user_rtps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_player_id_foreign` FOREIGN KEY (`player_id`) REFERENCES `players` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
