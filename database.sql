-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-05-2026 a las 21:11:21
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `crud_psicogest`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `patients`
--

CREATE TABLE `patients` (
  `uuid` varchar(36) NOT NULL,
  `doc_type` enum('cc','ce','ti','pa') NOT NULL,
  `doc_number` varchar(30) NOT NULL,
  `names` varchar(100) NOT NULL,
  `surnames` varchar(100) NOT NULL,
  `birthdate` date NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `location` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `patients`
--

INSERT INTO `patients` (`uuid`, `doc_type`, `doc_number`, `names`, `surnames`, `birthdate`, `phone`, `email`, `password`, `location`) VALUES
('32e2214c53138d1607700339c2afd497', 'cc', '1616941618', 'Rodrigo Juan', 'López Vélez', '1989-08-16', '+573256216624', 'rjuan@gmail.com', '$2y$10$EvRYNNNzo6D/ajmrv9CnHuPih1Lklh73WBnL7mEPUghG692YfUoOm', 'Colombia'),
('4ae0ae6ede9a48fb1d44f10161dbf51c', 'ti', '5161616', 'Lucia', 'Guerrero Suarez', '2017-04-14', '+573125698745', 'marianela@gmail.com', '$2y$10$wAQiaYct5wncI.JngSzD2OKEp/ujpwcIBRnQ0bcvxPqIbquRM.EX6', 'Colombia'),
('7096eb80134cb7faa73a75044f71e141', 'cc', '49419641941', 'Carolina', 'Zapata Díaz', '1990-02-20', '+573215697849', 'caro@gmail.com', '$2y$10$azTV1id74KxAKprNvT1LhO566.jkG658K3U8DELGBrHtReSDhvB4.', 'Colombia'),
('9da3c962d408eeaeea3b068e636bf663', 'ce', '1511961', 'Andres', 'Molina Molina', '2021-05-04', '+120156256325', 'andres@gmail.com', '$2y$10$xHGjGX/X86i1ZRbiVhopHODvk2cA2uVz4zFkl4UhjO3Er1X8sxP7y', 'Argentina'),
('a699220ce95c654724988a4321a6f192', 'pa', 'ABC156441', 'Magnolia Maria', 'Rivas Smith', '1976-12-15', '+55315624631', 'magnolia@gmail.com', '$2y$10$CBxGC0buWoxhzue9OMsaVu54vbDIw0XJZKjBsHFGAANMz4cw5dUiW', 'Brasil');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`uuid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
