-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Дек 18 2023 г., 13:49
-- Версия сервера: 8.0.30
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `test`
--

-- --------------------------------------------------------

--
-- Структура таблицы `currency`
--

CREATE TABLE `currency` (
  `id` int NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `currency`
--

INSERT INTO `currency` (`id`, `name`) VALUES
(1, 'USD'),
(2, 'AFN');

-- --------------------------------------------------------

--
-- Структура таблицы `history`
--

CREATE TABLE `history` (
  `id` int NOT NULL,
  `data_status` datetime NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `history`
--

INSERT INTO `history` (`id`, `data_status`, `message`) VALUES
(1, '2023-12-18 12:17:39', 'Добавлена новая подушка sdfdsfsdfs'),
(2, '2023-12-18 12:19:24', 'Подушка была удалена'),
(3, '2023-12-18 12:19:40', 'Добавлена новая подушка '),
(4, '2023-12-18 12:23:00', 'Подушка sdfs была удалена'),
(5, '2023-12-18 12:23:09', 'Добавлена новая подушка dsffsfs'),
(6, '2023-12-18 12:29:16', 'В подушку \'dsffsfs\' добавлена Наличныев валюте USD с суммой 150'),
(8, '2023-12-18 13:11:04', 'Накопление ID 7 были изменены на тип Карта'),
(9, '2023-12-18 13:13:13', 'Баланс изменился, остаток 130'),
(10, '2023-12-18 13:13:21', 'Накопление ID 7 были изменены на тип Наличные ,Баланс изменился, остаток 160'),
(12, '2023-12-18 13:17:19', 'Накопления ID 7 были удалены'),
(13, '2023-12-18 13:21:39', 'Подушка dsffsfs была удалена'),
(14, '2023-12-18 13:21:43', 'Добавлена новая подушка sdfs'),
(15, '2023-12-18 13:21:51', 'В подушку \'sdfs\' добавлен тип денег ID 8,Карта в валюте USD с суммой 1000'),
(16, '2023-12-18 13:22:31', 'В подушку \'sdfs\' добавлен тип денег ID 9,Вклад в валюте AFN с суммой 400'),
(17, '2023-12-18 13:22:41', 'Баланс изменился, остаток 600'),
(18, '2023-12-18 13:22:45', 'Баланс изменился, остаток 800'),
(19, '2023-12-18 13:22:55', 'Баланс изменился, остаток 900'),
(20, '2023-12-18 13:22:59', 'Баланс изменился, остаток 700'),
(21, '2023-12-18 13:23:02', 'Баланс изменился, остаток 750'),
(22, '2023-12-18 13:23:18', 'Баланс изменился, остаток 750');

-- --------------------------------------------------------

--
-- Структура таблицы `pillow`
--

CREATE TABLE `pillow` (
  `id` int NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_create` datetime NOT NULL,
  `data_update` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `pillow`
--

INSERT INTO `pillow` (`id`, `name`, `data_create`, `data_update`) VALUES
(13, 'sdfs', '2023-12-18 13:21:43', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `savings`
--

CREATE TABLE `savings` (
  `id` int NOT NULL,
  `pillow` int NOT NULL,
  `type` int NOT NULL,
  `currency` int NOT NULL,
  `data_create` datetime DEFAULT NULL,
  `data_update` datetime DEFAULT NULL,
  `value` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `savings`
--

INSERT INTO `savings` (`id`, `pillow`, `type`, `currency`, `data_create`, `data_update`, `value`) VALUES
(8, 13, 1, 1, '2023-12-18 13:21:51', '2023-12-18 13:23:02', '750'),
(9, 13, 3, 2, '2023-12-18 13:22:31', '2023-12-18 13:23:18', '750');

-- --------------------------------------------------------

--
-- Структура таблицы `savings_history`
--

CREATE TABLE `savings_history` (
  `id` int NOT NULL,
  `savings_id` int NOT NULL,
  `value` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_update` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `savings_history`
--

INSERT INTO `savings_history` (`id`, `savings_id`, `value`, `data_update`) VALUES
(1, 1, '1000', '2023-12-18 11:07:18'),
(2, 1, '800', '2023-12-18 11:07:54'),
(3, 1, '900', '2023-12-18 11:07:58'),
(4, 2, '2323', '2023-12-18 11:08:34'),
(5, 2, '1500', '2023-12-18 11:08:46'),
(6, 3, '1000', '2023-12-18 11:53:50'),
(17, 8, '1000', '2023-12-18 13:21:51'),
(18, 9, '400', '2023-12-18 13:22:31'),
(19, 9, '600', '2023-12-18 13:22:41'),
(20, 9, '800', '2023-12-18 13:22:45'),
(21, 8, '900', '2023-12-18 13:22:55'),
(22, 8, '700', '2023-12-18 13:22:59'),
(23, 8, '750', '2023-12-18 13:23:02'),
(24, 9, '750', '2023-12-18 13:23:18');

-- --------------------------------------------------------

--
-- Структура таблицы `savings_type`
--

CREATE TABLE `savings_type` (
  `id` int NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `savings_type`
--

INSERT INTO `savings_type` (`id`, `name`) VALUES
(1, 'Карта'),
(2, 'Наличные'),
(3, 'Вклад'),
(4, 'Счёт');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `currency`
--
ALTER TABLE `currency`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `pillow`
--
ALTER TABLE `pillow`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `savings`
--
ALTER TABLE `savings`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `savings_history`
--
ALTER TABLE `savings_history`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `savings_type`
--
ALTER TABLE `savings_type`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `currency`
--
ALTER TABLE `currency`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `history`
--
ALTER TABLE `history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT для таблицы `pillow`
--
ALTER TABLE `pillow`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `savings`
--
ALTER TABLE `savings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `savings_history`
--
ALTER TABLE `savings_history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT для таблицы `savings_type`
--
ALTER TABLE `savings_type`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
