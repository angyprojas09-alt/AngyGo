-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 31-03-2026 a las 17:36:51
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
(2, 9, 'dadc6fa6c2bf55cf7da7a4f3654bc3b5', '2026-02-13 22:49:19', 0, '2026-02-12 21:49:19'),
(3, 10, '25c7b844cba8b912cac1f6c3082bdda2', '2026-02-19 22:12:31', 0, '2026-02-18 21:12:31'),
(4, 11, '8aa5571546211555ca8ab1e544ff053e', '2026-02-20 01:24:07', 0, '2026-02-19 00:24:07'),
(5, 12, 'c717d696aca957719a4e74990615e9f8', '2026-03-14 00:10:09', 0, '2026-03-12 23:10:09'),
(6, 13, '7ce7edf568888a9feaa3543ab28d32e4', '2026-03-24 22:35:54', 0, '2026-03-23 21:35:54');

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
(3, 3, 'b6b969b4182a19e158c574a07ea963b08e019c280f3817d4370f71eedcd1b231', '2026-03-24 02:19:49', 0, '2026-03-24 00:19:49');

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
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `usuario_id` int(11) NOT NULL,
  `domiciliario_id` int(11) DEFAULT NULL,
  `estado` varchar(50) NOT NULL DEFAULT 'Pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `nombre`, `telefono`, `direccion`, `producto`, `cantidad`, `comentarios`, `fecha`, `usuario_id`, `domiciliario_id`, `estado`) VALUES
(54, 'Angy paola Rojas Loza', '3045257674', 'cll 13 #4-32 apt 301', 'aguardiente amarillo', 3, 'Prueba arreglos 18/02', '2026-03-23 21:54:28', 3, 11, 'Pendiente'),
(55, 'AngyGO', '3208425362', 'cll 15# 4-43', 'salsa bbq', 1, 'prueba 5 56', '2026-02-19 01:01:30', 10, 5, 'Pendiente'),
(56, 'Usuario', '3208425362', 'cll 15# 4-43', 'papas risadas', 1, 'pr 3', '2026-03-13 00:04:52', 2, 11, 'Entregado'),
(57, 'angy rojas', '3208425362', 'cll 13 #4-32 apt 301', 'aceite de oliva', 1, 'bbbb', '2026-03-13 00:05:00', 3, 11, 'En camino'),
(58, 'angy rojas', '3054679878', 'cll 13 #4-32 apt 301', 'papas risadas', 9, '', '2026-03-23 21:54:35', 3, 9, 'Pendiente'),
(59, 'angy rojas', '3054679878', 'cll 13 #4-32 apt 301', 'papas risadas', 9, '', '2026-03-11 00:10:46', 3, NULL, 'Pendiente'),
(60, 'angy rojas', '3208425362', 'cll 13 #4-32 apt 301', 'aceite de oliva', 4, 'prueba de evidencia', '2026-03-23 21:54:14', 10, 9, 'Pendiente'),
(61, 'angy rojas', '3208425362', 'cll 13 #4-32 apt 301', 'salsa bbq', 1, 'prueba de video', '2026-03-13 00:05:19', 3, 11, 'En camino'),
(62, 'angy rojas', '3208425362', 'cll 13 #4-32 apt 301', 'papas risadas', 3, 'de las grandes, sabor limon', '2026-03-23 21:37:26', 3, NULL, 'Pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('cliente','admin','domiciliario') NOT NULL DEFAULT 'cliente',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `correo`, `password`, `rol`, `fecha_registro`) VALUES
(2, 'Erick pupo villalobos ', 'shibucai93@gmail.com', '$2y$10$YXhutXGX23bEKamY70tET./5FDfze1x8.LDsue34PNBe5HCXmw.Um', 'cliente', '2026-02-10 18:20:04'),
(3, 'angy rojas', 'angyprojas09@gmail.com', '$2y$10$SbmjkQ2okwUDcl5ZS/oJsuN5/SsseOOroEQ8dpBK0doXRrfOBlRFm', 'cliente', '2026-02-10 18:26:56'),
(6, 'CArlos andres rojas', 'carlos.rojas@franjait.com', '$2y$10$NE7Dv2r4BGV9AtKf0ltN6eGKIew6ac8AY2NbXP3PVOnu1GK3A3InW', 'cliente', '2026-02-11 22:32:23'),
(9, 'Andrea juliana rojas', 'andrea09@gmail.com', '$2y$10$QdaIICrsgTMUAb3bw6S9keyii/Ds2b5iRg7kqvM9zTJsXWy.8QGcG', 'domiciliario', '2026-02-12 21:49:18'),
(10, 'AngyGO', 'angygo916@gmail.com', '$2y$10$yp0TV8Q5oxy85JZ54Bn2uOVjPbPdjkEM0HTuWyazfbShELeZplAQe', 'admin', '2026-02-18 21:12:31'),
(11, 'camilo antonio', 'camiloan09@gmail.com', '$2y$10$XMR28Ipo9ueGwgQe3X6ZM.5.jjX8dcjPSqHXzjIENlM8W5X4Iutk6', 'domiciliario', '2026-02-19 00:24:07');

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_usuario` (`usuario_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `fk_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
