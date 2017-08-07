-- phpMyAdmin SQL Dump
-- version 4.4.15.7
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Час створення: Сер 07 2017 р., 11:16
-- Версія сервера: 5.6.31
-- Версія PHP: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База даних: `inform_ato_web`
--

-- --------------------------------------------------------

--
-- Структура таблиці `informs`
--

CREATE TABLE IF NOT EXISTS `informs` (
  `id` int(11) NOT NULL,
  `name` tinytext NOT NULL,
  `inform` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Дамп даних таблиці `informs`
--

INSERT INTO `informs` (`id`, `name`, `inform`) VALUES
(1, 'dfdf', 'dfdfd'),
(2, 'dfdf', 'dfdfd'),
(3, 'dfdf', 'dfdfd'),
(4, 'qwqwqwqw', 'qwqwqwqw'),
(5, 'sfdfdfd', 'dfdfdfd'),
(6, 'dfdfdfd', 'dfdfdf');

-- --------------------------------------------------------

--
-- Структура таблиці `informs_users`
--

CREATE TABLE IF NOT EXISTS `informs_users` (
  `id` int(11) NOT NULL,
  `inform_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `readinform` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Дамп даних таблиці `informs_users`
--

INSERT INTO `informs_users` (`id`, `inform_id`, `user_id`, `readinform`) VALUES
(1, 5, 2, 1),
(2, 6, 3, 1);

-- --------------------------------------------------------

--
-- Структура таблиці `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `admin_is` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Дамп даних таблиці `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `pass`, `admin_is`) VALUES
(1, 'alex', 'alex@gmail.com', '$2y$12$JqBW/RYdxQ62f4GmYPgPdO/cr0URpTL2aWmvszHcC9kFPU601DYcy', 1),
(2, 'ivan', 'ivan@gmail.com', '$2y$12$o6v.O0f79pabBaOua4aEWesUQKSxguyQQIapubu9ttpIROilyF.lm', NULL),
(3, 'petro', 'petro@i.ua', '$2y$12$oTxt3Ey24skZz3j8oHGqNOSPxCpbLVzihFS5grE2JVvQEV/itS.FW', NULL),
(4, 'stepan', 'stepan@i.ua', '$2y$12$mvBxtvivOqgQR6KncPhHuuAUBiu/op2SsNNSwvY0u0Hq8.TFcdEvu', NULL);

--
-- Індекси збережених таблиць
--

--
-- Індекси таблиці `informs`
--
ALTER TABLE `informs`
  ADD PRIMARY KEY (`id`);

--
-- Індекси таблиці `informs_users`
--
ALTER TABLE `informs_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inform_id` (`inform_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Індекси таблиці `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для збережених таблиць
--

--
-- AUTO_INCREMENT для таблиці `informs`
--
ALTER TABLE `informs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT для таблиці `informs_users`
--
ALTER TABLE `informs_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблиці `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- Обмеження зовнішнього ключа збережених таблиць
--

--
-- Обмеження зовнішнього ключа таблиці `informs_users`
--
ALTER TABLE `informs_users`
  ADD CONSTRAINT `informs_users_ibfk_1` FOREIGN KEY (`inform_id`) REFERENCES `informs` (`id`),
  ADD CONSTRAINT `informs_users_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
