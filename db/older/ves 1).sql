-- phpMyAdmin SQL Dump
-- version 4.3.10
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 28-Fev-2015 às 00:53
-- Versão do servidor: 5.6.20-log
-- PHP Version: 5.4.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ves`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `all_users`
--

CREATE TABLE IF NOT EXISTS `all_users` (
  `user_id` int(11) NOT NULL,
  `users_nick` varchar(64) NOT NULL,
  `user_first_name` varchar(128) NOT NULL,
  `user_last_name` varchar(256) NOT NULL,
  `user_img` varchar(128) NOT NULL,
  `user_type` char(1) NOT NULL,
  `user_birth` int(11) NOT NULL,
  `user_email` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `cad_users`
--

CREATE TABLE IF NOT EXISTS `cad_users` (
  `cad_id` int(11) NOT NULL COMMENT 'auto incrementing user_id of each user, unique index',
  `cad_nick` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s name, unique',
  `cad_first_name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `cad_last_name` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `cad_password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s password in salted and hashed format',
  `cad_email` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s email, unique',
  `cad_active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'user''s activation status',
  `cad_activation_hash` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'user''s email verification hash string',
  `cad_password_reset_hash` char(40) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'user''s password reset code',
  `cad_password_reset_timestamp` bigint(20) DEFAULT NULL COMMENT 'timestamp of the password reset request',
  `cad_rememberme_token` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'user''s remember-me cookie token',
  `cad_failed_logins` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'user''s failed login attemps',
  `cad_last_failed_login` int(10) DEFAULT NULL COMMENT 'unix timestamp of last failed login attempt',
  `cad_registration_datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `cad_registration_ip` varchar(39) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0.0.0.0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='user data';

-- --------------------------------------------------------

--
-- Estrutura da tabela `c_sub`
--

CREATE TABLE IF NOT EXISTS `c_sub` (
  `c_id` int(3) NOT NULL,
  `c_name` varchar(64) NOT NULL,
  `c_p_id` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `fb_users`
--

CREATE TABLE IF NOT EXISTS `fb_users` (
  `fb_token` varchar(128) NOT NULL,
  `fb_name` varchar(128) NOT NULL,
  `fb_img` varchar(512) NOT NULL,
  `fb_email` varchar(512) NOT NULL,
  `fb_birth` char(1) NOT NULL,
  `fb_nick` date NOT NULL,
  `fb_all_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Facebook users';

-- --------------------------------------------------------

--
-- Estrutura da tabela `perf`
--

CREATE TABLE IF NOT EXISTS `perf` (
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dif` int(1) DEFAULT NULL,
  `sub_id` int(3) DEFAULT NULL,
  `usr_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='User''s performace';

-- --------------------------------------------------------

--
-- Estrutura da tabela `p_sub`
--

CREATE TABLE IF NOT EXISTS `p_sub` (
  `p_id` int(2) NOT NULL,
  `p_name` varchar(64) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='Parent Subject';

--
-- Extraindo dados da tabela `p_sub`
--

INSERT INTO `p_sub` (`p_id`, `p_name`) VALUES
(1, 'Português'),
(2, 'Matemática'),
(3, 'História'),
(4, 'Geografia'),
(5, 'Química'),
(6, 'Física'),
(7, 'Biologia');

-- --------------------------------------------------------

--
-- Estrutura da tabela `questions`
--

CREATE TABLE IF NOT EXISTS `questions` (
  `q_id` int(6) NOT NULL,
  `q_vestibular` varchar(16) NOT NULL,
  `q_year` year(4) NOT NULL,
  `q_num` int(3) NOT NULL,
  `q_content` varchar(4096) NOT NULL,
  `q_alt_1` varchar(1024) NOT NULL,
  `q_alt_2` varchar(1024) NOT NULL,
  `q_alt_3` varchar(1024) NOT NULL,
  `q_alt_4` varchar(1024) NOT NULL,
  `q_alt_5` varchar(1024) NOT NULL,
  `q_correct_alt` char(1) NOT NULL,
  `q_sub_2_id` int(3) NOT NULL,
  `q_comment` varchar(1024) NOT NULL,
  `q_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cad_users`
--
ALTER TABLE `cad_users`
  ADD PRIMARY KEY (`cad_id`), ADD UNIQUE KEY `user_name` (`cad_nick`), ADD UNIQUE KEY `user_email` (`cad_email`);

--
-- Indexes for table `c_sub`
--
ALTER TABLE `c_sub`
  ADD PRIMARY KEY (`c_id`);

--
-- Indexes for table `fb_users`
--
ALTER TABLE `fb_users`
  ADD PRIMARY KEY (`fb_token`), ADD UNIQUE KEY `fb_all_id` (`fb_all_id`);

--
-- Indexes for table `perf`
--
ALTER TABLE `perf`
  ADD PRIMARY KEY (`data`);

--
-- Indexes for table `p_sub`
--
ALTER TABLE `p_sub`
  ADD PRIMARY KEY (`p_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`q_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cad_users`
--
ALTER TABLE `cad_users`
  MODIFY `cad_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'auto incrementing user_id of each user, unique index';
--
-- AUTO_INCREMENT for table `c_sub`
--
ALTER TABLE `c_sub`
  MODIFY `c_id` int(3) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `p_sub`
--
ALTER TABLE `p_sub`
  MODIFY `p_id` int(2) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `q_id` int(6) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
