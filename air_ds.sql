-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Εξυπηρετητής: 127.0.0.1
-- Χρόνος δημιουργίας: 20 Απρ 2025 στις 19:44:32
-- Έκδοση διακομιστή: 10.4.32-MariaDB
-- Έκδοση PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Βάση δεδομένων: `air_ds`
--

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `airports`
--

CREATE TABLE `airports` (
  `name` varchar(60) NOT NULL,
  `code` varchar(3) NOT NULL,
  `latitude` float NOT NULL,
  `longitude` float NOT NULL,
  `tax` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `airports`
--

INSERT INTO `airports` (`name`, `code`, `latitude`, `longitude`, `tax`) VALUES
('Athens International Airport \"Eleftherios Venizelos\"', 'ATH', 37.9372, 23.9452, 150),
('Brussels Airport', 'BRU', 50.9002, 4.4859, 200),
('Paris Charles de Gaulle Airport', 'CDG', 49.0097, 2.54778, 200),
('Leonardo da Vinci Rome Fiumicino Airport', 'FCO', 41.8108, 12.2509, 150),
('Larnaka International Airport', 'LCA', 34.8715, 33.6077, 150),
('Adolfo Suárez Madrid–Barajas Airport', 'MAD', 40.4895, 3.5643, 250);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `departure` varchar(60) NOT NULL,
  `arrival` varchar(60) NOT NULL,
  `date` varchar(20) NOT NULL,
  `passengers` int(11) NOT NULL,
  `seats` varchar(66) NOT NULL,
  `names` varchar(189) NOT NULL,
  `surnames` varchar(189) NOT NULL,
  `taxes` int(11) NOT NULL,
  `costs` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `users`
--

CREATE TABLE `users` (
  `name` varchar(20) NOT NULL,
  `surname` varchar(20) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(10) NOT NULL,
  `email` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Ευρετήρια για άχρηστους πίνακες
--

--
-- Ευρετήρια για πίνακα `airports`
--
ALTER TABLE `airports`
  ADD UNIQUE KEY `code` (`code`);

--
-- Ευρετήρια για πίνακα `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`);

--
-- Ευρετήρια για πίνακα `users`
--
ALTER TABLE `users`
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT για άχρηστους πίνακες
--

--
-- AUTO_INCREMENT για πίνακα `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
