-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-06-2025 a las 21:38:06
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
-- Base de datos: `sistema_cine`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `boletas`
--

CREATE TABLE `boletas` (
  `id` int(11) NOT NULL,
  `venta_id` int(11) NOT NULL,
  `funcion_id` int(11) NOT NULL,
  `asiento` varchar(10) NOT NULL,
  `codigo_qr` varchar(255) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `boletas`
--

INSERT INTO `boletas` (`id`, `venta_id`, `funcion_id`, `asiento`, `codigo_qr`, `estado`, `created_at`) VALUES
(11, 6, 9, 'A1', 'Njg0ODZkNjVjZDlkY185X0Ex', 1, '2025-06-10 17:37:41');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `funciones`
--

CREATE TABLE `funciones` (
  `id` int(11) NOT NULL,
  `pelicula_id` int(11) NOT NULL,
  `sala_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `funciones`
--

INSERT INTO `funciones` (`id`, `pelicula_id`, `sala_id`, `fecha`, `hora`, `precio`, `estado`, `created_at`) VALUES
(7, 9, 6, '2025-06-12', '14:00:00', 10.00, 1, '2025-06-10 13:07:05'),
(8, 10, 8, '2025-06-13', '21:00:00', 300.00, 1, '2025-06-10 13:46:57'),
(9, 10, 9, '2025-06-15', '21:00:00', 100.00, 1, '2025-06-10 17:26:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `peliculas`
--

CREATE TABLE `peliculas` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `sinopsis` text DEFAULT NULL,
  `duracion` int(11) NOT NULL,
  `clasificacion` varchar(5) DEFAULT NULL,
  `genero` varchar(50) DEFAULT NULL,
  `director` varchar(100) DEFAULT NULL,
  `imagen` mediumblob DEFAULT NULL,
  `imagen_tipo` varchar(50) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `imagen_ruta` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `peliculas`
--

INSERT INTO `peliculas` (`id`, `titulo`, `sinopsis`, `duracion`, `clasificacion`, `genero`, `director`, `imagen`, `imagen_tipo`, `estado`, `created_at`, `updated_at`, `imagen_ruta`) VALUES
(9, 'Toy Story 4', 'Woody siempre ha tenido claro cuál es su labor en el mundo y cuál es su prioridad: cuidar a su dueño, ya sea Andy o Bonnie. Sin embargo, Woody descubrirá lo grande que puede ser el mundo para un juguete cuando Forky se convierta en su nuevo compañero de habitación. Los juguetes se embarcarán en una aventura de la que no se olvidarán jamás.', 100, 'G', 'Animación', 'Christopher Nolan', NULL, NULL, 1, '2025-06-10 13:05:17', '2025-06-10 13:21:15', 'uploads/Peliculas/toy_story_4.jpg'),
(10, 'Misterio (2005)', 'Percy es un joven de clase media, buen hijo, con un trabajo estable y un gran amor al club de sus amores, Universitario de Deportes. Después de una pelea en un bar, Percy y su amigo Caradura conocen a otros hinchas de la \"U\" y quedan en encontrarse otro día sin imaginar que en ese punto empezaría un gran cambio en sus vidas… sobre todo para Percy, donde las drogas, el alcohol y su amor inacabable por la \"U\" terminarían por acabar con su vida.', 200, 'PG-13', 'Drama', 'Jorge Carmona', NULL, NULL, 1, '2025-06-10 13:46:04', '2025-06-10 13:46:04', 'uploads/Peliculas/misterio__2005_.jpg'),
(11, 'Boyka: Undisputed 4', 'Boyka es un luchador de boxeo que se encuentra en mitad de una importante liga. Durante la competición se produce una muerte, lo que hace que Boyka empiece a replantearse si verdaderamente merece la pena este deporte. Cuando descubre que la mujer del fallecido se encuentra en serios problemas, decide luchar una serie de batallas para poder liberarla de la servidumbre.', 90, 'PG-13', 'Accion', 'Todor Chapkanov', NULL, NULL, 1, '2025-06-10 16:02:45', '2025-06-10 16:02:45', 'uploads/Peliculas/boyka__undisputed_4.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promociones`
--

CREATE TABLE `promociones` (
  `id` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `terminos` text DEFAULT NULL,
  `descuento` decimal(5,2) DEFAULT NULL,
  `codigo` varchar(20) DEFAULT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `estado` enum('activa','inactiva','expirada') NOT NULL DEFAULT 'activa',
  `imagen_ruta` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `promociones`
--

INSERT INTO `promociones` (`id`, `titulo`, `descripcion`, `terminos`, `descuento`, `codigo`, `fecha_inicio`, `fecha_fin`, `estado`, `imagen_ruta`, `created_at`, `updated_at`) VALUES
(1, '2x1 en Martes y Jueves', 'Disfruta de dos entradas por el precio de una todos los martes y jueves.', 'Válido solo para funciones regulares. No aplica en estrenos ni funciones especiales.', 50.00, '2X1DIAS', '2024-01-01', '2024-12-31', 'activa', NULL, '2025-06-10 15:45:22', '2025-06-10 15:45:22'),
(2, 'Combo Familiar', '4 entradas + Palomitas + Bebidas a precio especial.', 'Válido todos los días. No acumulable con otras promociones.', 30.00, 'FAMILIA', '2024-01-01', '2024-12-31', 'activa', NULL, '2025-06-10 15:45:22', '2025-06-10 15:45:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salas`
--

CREATE TABLE `salas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `capacidad` int(11) NOT NULL,
  `estado` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `salas`
--

INSERT INTO `salas` (`id`, `nombre`, `capacidad`, `estado`) VALUES
(6, 'SALA 1', 45, 1),
(7, 'SALA 2', 45, 1),
(8, 'SALA 3', 45, 1),
(9, 'SALA PRIME 8K', 12, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','cliente') DEFAULT 'cliente',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `rol`, `created_at`, `updated_at`, `telefono`, `direccion`) VALUES
(1, 'Carolina Alcántara', 'admin@cine.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2025-06-09 04:40:59', '2025-06-10 17:13:50', '+51 936936936', 'Av. New York'),
(6, 'Arch Adrian', 'depor.adrian@ig.com', '$2y$10$ilClWWea9xXRMjdk8OfeneMB3HmmIDSddZeyT3EebUah6SvK1S0Kq', 'cliente', '2025-06-10 17:17:26', '2025-06-10 17:24:21', '+51 987978987', 'Norilsk, Krasnoyarsk, Russian Federation');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `cliente_nombre` varchar(100) NOT NULL,
  `cliente_email` varchar(100) DEFAULT NULL,
  `cliente_dni` varchar(20) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `estado` enum('pendiente','completada','cancelada') DEFAULT 'pendiente',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `cliente_nombre`, `cliente_email`, `cliente_dni`, `total`, `estado`, `created_at`) VALUES
(6, 'Arch Adrian', 'depor.adrian@ig.com', '71717271', 100.00, 'completada', '2025-06-10 17:37:41');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `boletas`
--
ALTER TABLE `boletas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `venta_id` (`venta_id`),
  ADD KEY `funcion_id` (`funcion_id`),
  ADD KEY `idx_boletas_estado` (`estado`);

--
-- Indices de la tabla `funciones`
--
ALTER TABLE `funciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pelicula_id` (`pelicula_id`),
  ADD KEY `sala_id` (`sala_id`),
  ADD KEY `idx_funciones_fecha` (`fecha`),
  ADD KEY `idx_funciones_estado` (`estado`);

--
-- Indices de la tabla `peliculas`
--
ALTER TABLE `peliculas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_peliculas_estado` (`estado`);

--
-- Indices de la tabla `promociones`
--
ALTER TABLE `promociones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Indices de la tabla `salas`
--
ALTER TABLE `salas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ventas_cliente_email` (`cliente_email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `boletas`
--
ALTER TABLE `boletas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `funciones`
--
ALTER TABLE `funciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `peliculas`
--
ALTER TABLE `peliculas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `promociones`
--
ALTER TABLE `promociones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `salas`
--
ALTER TABLE `salas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `boletas`
--
ALTER TABLE `boletas`
  ADD CONSTRAINT `boletas_ibfk_1` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`),
  ADD CONSTRAINT `boletas_ibfk_2` FOREIGN KEY (`funcion_id`) REFERENCES `funciones` (`id`);

--
-- Filtros para la tabla `funciones`
--
ALTER TABLE `funciones`
  ADD CONSTRAINT `funciones_ibfk_1` FOREIGN KEY (`pelicula_id`) REFERENCES `peliculas` (`id`),
  ADD CONSTRAINT `funciones_ibfk_2` FOREIGN KEY (`sala_id`) REFERENCES `salas` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
