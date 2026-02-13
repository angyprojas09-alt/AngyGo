-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-02-2026 a las 23:05:33
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
-- Base de datos: `angygo`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `email_confirmations`
--

CREATE TABLE `email_confirmations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(128) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `email_confirmations`
--

INSERT INTO `email_confirmations` (`id`, `user_id`, `token`, `expires_at`, `used`, `created_at`) VALUES
(1, 8, '969d702b4276be653c5cc611bf65c6eb', '2026-02-13 22:28:15', 0, '2026-02-12 21:28:15'),
(2, 9, 'dadc6fa6c2bf55cf7da7a4f3654bc3b5', '2026-02-13 22:49:19', 0, '2026-02-12 21:49:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(128) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `password_resets`
--

INSERT INTO `password_resets` (`id`, `user_id`, `token`, `expires_at`, `used`, `created_at`) VALUES
(1, 3, '4575c4fe5e981ddc69e5de1807bf29d0', '2026-02-12 19:30:01', 0, '2026-02-12 17:30:01'),
(2, 3, '5864a6d1cdd3a69efe4a598aab9afcbd1f093c61c13fa62cb46788af21cff68f', '2026-02-12 19:56:53', 0, '2026-02-12 17:56:53');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `direccion` varchar(150) NOT NULL,
  `producto` varchar(100) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `comentarios` text DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `nombre`, `telefono`, `direccion`, `producto`, `cantidad`, `comentarios`, `fecha`) VALUES
(33, 'Angy paola Rojas Loza', '3112149936', 'cll 13 #4-32 apt 301', 'aguardiente amarillo', 1, 'prueba final \r\n32', '2026-02-12 02:44:45'),
(34, 'laura rojas ', '3214566578', 'cll 13 #4-32 apt 301', 'jabón en polvo', 1, 'del d1', '2026-02-12 02:45:59'),
(35, 'laura rojas ', '3214566578', 'cll 13 #4-32 apt 301', 'jabón en polvo', 1, 'del d1', '2026-02-12 02:46:52'),
(36, 'Angy paola Rojas Loza', '3214566578', 'cll 13 #4-32 apt 301', 'arroz', 1, 'que sea roa', '2026-02-12 17:11:19'),
(37, 'Angy paola Rojas Loza', '3112149936', 'cll 15# 4-43', 'jabón en polvo', 2, 'prueba del 12/02', '2026-02-12 17:17:01'),
(38, 'Angy paola Rojas Loza', '3112149936', 'guapotá - Santander', 'aguardiente', 1, 'prueba final de whatsApp', '2026-02-12 17:18:28'),
(39, 'Angy paola Rojas Loza', '3112149936', 'cll 13 #4-32 apt 301', 'papas risadas', 1, '12:38pm', '2026-02-12 17:38:40'),
(40, 'Angy paola Rojas Loza', '3045257674', 'guapotá - Santander', 'aguardiente', 500, 'sin comentario', '2026-02-12 18:05:19'),
(41, 'Erick pupo villalobos', '3208425362', 'calle 57-45', 'salsa bbq', 1, 'prueba emojis', '2026-02-12 18:32:14'),
(42, 'Erick pupo villalobos', '3208425362', 'calle 57-45', 'salsa bbq', 1, 'prueba emojis', '2026-02-12 18:43:18'),
(43, 'Erick pupo villalobos', '3208425362', 'guapotá - Santander', 'salsa bbq', 1, 'mmmm', '2026-02-12 18:43:43'),
(44, 'Erick pupo villalobos', '3208425362', 'guapotá - Santander', 'salsa bbq', 1, 'mmmm', '2026-02-12 18:55:58'),
(45, 'Erick pupo villalobos', '3208425362', 'guapotá - Santander', 'salsa bbq', 1, 'mmmm', '2026-02-12 18:58:28'),
(46, 'Erick pupo villalobos', '3208425362', 'guapotá - Santander', 'salsa bbq', 1, 'mmmm', '2026-02-12 18:58:35'),
(47, 'Erick pupo villalobos', '3208425362', 'guapotá - Santander', 'salsa bbq', 1, 'mmmm', '2026-02-12 19:00:10'),
(48, 'Erick pupo villalobos', '3208425362', 'guapotá - Santander', 'salsa bbq', 1, 'mmmm', '2026-02-12 19:04:59'),
(49, 'Erick pupo villalobos', '3208425362', 'cll 13 #4-32 apt 301', 'aceite de oliva', 1, '', '2026-02-12 19:06:13'),
(50, 'Erick pupo villalobos', '3112149936', 'carre 14 #2-57', 'aceite de oliva', 1, '', '2026-02-12 19:11:14'),
(51, 'Angy paola Rojas Loza', '3112149936', 'carre 14 #2-57', 'salsa bbq', 1, 'jkjj', '2026-02-12 19:12:27'),
(52, 'Angy paola Rojas Loza', '3112149936', 'carre 14 #2-57', 'papas risadas', 1, 'kkk', '2026-02-12 19:59:22'),
(53, 'Andrea juliana rojas', '3208425362', 'guapotá - Santander', 'salsa bbq', 1, '12/02', '2026-02-12 21:50:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `correo`, `password`, `fecha_registro`) VALUES
(2, 'Erick pupo villalobos ', 'shibucai93@gmail.com', '$2y$10$YXhutXGX23bEKamY70tET./5FDfze1x8.LDsue34PNBe5HCXmw.Um', '2026-02-10 18:20:04'),
(3, 'Angy paola Rojas Loza', 'angyprojas09@gmail.com', '$2y$10$SbmjkQ2okwUDcl5ZS/oJsuN5/SsseOOroEQ8dpBK0doXRrfOBlRFm', '2026-02-10 18:26:56'),
(5, 'Camilo Rojas Santos ', 'camilorojas@gmail.com', '$2y$10$KsPO06gWKsiqjWeSRjyf7OS5E6beeGXWoEBQob9zOgBJG2f2tRT6C', '2026-02-10 23:06:15'),
(6, 'CArlos andres rojas', 'carlos.rojas@franjait.com', '$2y$10$NE7Dv2r4BGV9AtKf0ltN6eGKIew6ac8AY2NbXP3PVOnu1GK3A3InW', '2026-02-11 22:32:23'),
(9, 'Andrea juliana rojas', 'andrea09@gmail.com', '$2y$10$QdaIICrsgTMUAb3bw6S9keyii/Ds2b5iRg7kqvM9zTJsXWy.8QGcG', '2026-02-12 21:49:18');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `email_confirmations`
--
ALTER TABLE `email_confirmations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `email_confirmations`
--
ALTER TABLE `email_confirmations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
