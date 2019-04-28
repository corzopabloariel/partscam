-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 28-04-2019 a las 16:58:59
-- Versión del servidor: 10.2.23-MariaDB-cll-lve
-- Versión de PHP: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `osolelar_duropisos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `provincia`
--
--
-- Volcado de datos para la tabla `provincia`
--

INSERT INTO `provincia` (`id`, `nombre`, `codigo31662`, `created_at`, `updated_at`) VALUES
(1, 'Ciudad Autónoma de Buenos Aires (CABA)', 'AR-C', NULL, NULL),
(2, 'Buenos Aires', 'AR-B', NULL, NULL),
(3, 'Catamarca', 'AR-K', NULL, NULL),
(4, 'Córdoba', 'AR-X', NULL, NULL),
(5, 'Corrientes', 'AR-W', NULL, NULL),
(6, 'Entre Ríos', 'AR-E', NULL, NULL),
(7, 'Jujuy', 'AR-Y', NULL, NULL),
(8, 'Mendoza', 'AR-M', NULL, NULL),
(9, 'La Rioja', 'AR-F', NULL, NULL),
(10, 'Salta', 'AR-A', NULL, NULL),
(11, 'San Juan', 'AR-J', NULL, NULL),
(12, 'San Luis', 'AR-D', NULL, NULL),
(13, 'Santa Fe', 'AR-S', NULL, NULL),
(14, 'Santiago del Estero', 'AR-G', NULL, NULL),
(15, 'Tucumán', 'AR-T', NULL, NULL),
(16, 'Chaco', 'AR-H', NULL, NULL),
(17, 'Chubut', 'AR-U', NULL, NULL),
(18, 'Formosa', 'AR-P', NULL, NULL),
(19, 'Misiones', 'AR-N', NULL, NULL),
(20, 'Neuquén', 'AR-Q', NULL, NULL),
(21, 'La Pampa', 'AR-L', NULL, NULL),
(22, 'Río Negro', 'AR-R', NULL, NULL),
(23, 'Santa Cruz', 'AR-Z', NULL, NULL),
(24, 'Tierra del Fuego', 'AR-V', NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `provincia`
--
ALTER TABLE `provincia`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `provincia`
--
ALTER TABLE `provincia`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
