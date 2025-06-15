-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-06-2025 a las 05:04:32
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
-- Base de datos: `tecnored`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asesores`
--

CREATE TABLE `asesores` (
  `cedula` varchar(20) NOT NULL,
  `especialidad` varchar(100) NOT NULL,
  `tiempo_en_plataforma` int(11) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `chat`
--

CREATE TABLE `chat` (
  `id_mensaje` int(11) NOT NULL,
  `cedula_remitente` varchar(20) DEFAULT NULL,
  `cedula_destinatario` varchar(20) DEFAULT NULL,
  `mensaje` text NOT NULL,
  `fecha_hora` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `datos_comprador`
--

CREATE TABLE `datos_comprador` (
  `id_factura` int(11) NOT NULL,
  `cedula` varchar(20) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `fecha_compra` datetime DEFAULT current_timestamp(),
  `id_producto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipos`
--

CREATE TABLE `equipos` (
  `id_equipo` int(11) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `estado` enum('Disponible','Ocupado','En reparación') DEFAULT 'Disponible',
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
  `cedula` varchar(20) NOT NULL,
  `id_profesor` varchar(20) DEFAULT NULL,
  `nivel_acceso` int(11) DEFAULT NULL CHECK (`nivel_acceso` in (2,3)),
  `membresia_pagada` tinyint(1) DEFAULT 0,
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `finanzas`
--

CREATE TABLE `finanzas` (
  `id_finanza` int(11) NOT NULL,
  `tipo` enum('Ingreso','Gasto','Pérdida') NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `descripcion` text DEFAULT NULL,
  `cedula_administrador` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario_venta`
--

CREATE TABLE `inventario_venta` (
  `id_producto` int(11) NOT NULL,
  `tipo_producto` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `disponibilidad` enum('Disponible','No disponible') DEFAULT 'Disponible',
  `url_imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inventario_venta`
--

INSERT INTO `inventario_venta` (`id_producto`, `tipo_producto`, `descripcion`, `precio`, `imagen`, `disponibilidad`, `url_imagen`) VALUES
(1, 'Laptop Gaming G15', 'Potente laptop para juegos con procesador Intel Core i7, 16GB RAM y NVIDIA RTX 4060.', 1250.00, 'https://m.media-amazon.com/images/I/71hpBZsbTOL._AC_UF894,1000_QL80_.jpg', 'Disponible', NULL),
(2, 'Monitor Curvo 27\" Ultra HD', 'Monitor de 27 pulgadas con resolución 4K, ideal para diseño y entretenimiento.', 450.75, 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcTuA9QWj-Ti1rRTELoEyleTH9lYNgBSUMD0KFKcY3ubnoRvlubFl83fPwP6cOu6xtGfcxCAuGOxZT2soxqi6N62XejeECn07qwnR9SJIUhLm893HzJ0aglZ', 'Disponible', NULL),
(3, 'Teclado Mecánico RGB', 'Teclado mecánico retroiluminado RGB con switches azules, perfecto para gamers y programadores.', 85.50, 'https://cdn.hobbyconsolas.com/sites/navi.axelspringer.es/public/media/image/2019/06/hp-omen-sequencer-teclado-mecanico-gaming-rgb.png?tf=3840x', 'Disponible', NULL),
(4, 'Mouse Inalámbrico Ergonómico', 'Mouse óptico inalámbrico con diseño ergonómico para largas horas de uso sin fatiga.', 25.99, 'https://exitocol.vtexassets.com/arquivos/ids/24287006/mouse-ergonomico-vertical-inalambrico-24g-recargable-usb.jpg?v=638604580097700000', 'Disponible', NULL),
(5, 'Audífonos Bluetooth Noise Cancelling', 'Audífonos con cancelación de ruido activa, sonido de alta fidelidad y batería de larga duración.', 120.00, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTb4yqerKoLTH1KaKKMlgCxrtwzPnNpg4gWIw&s', 'Disponible', NULL),
(6, 'Tarjeta Gráfica RTX 4070', 'Tarjeta de video NVIDIA GeForce RTX 4070 con 12GB GDDR6, para el máximo rendimiento en juegos.', 680.00, '', 'No disponible', NULL),
(7, 'Disco Duro SSD 1TB NVMe', 'Unidad de estado sólido de 1TB NVMe, ultra rápida para sistemas operativos y aplicaciones exigentes.', 95.00, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR8MKFhhqaz_FsH0WTb_3qp72SyY4Sgw00yHg&s', 'Disponible', NULL),
(8, 'Webcam Full HD 1080p', 'Webcam con resolución 1080p y micrófono integrado, ideal para videollamadas y streaming.', 35.00, 'https://redtech.lk/wp-content/uploads/2020/12/B1-1080P-WEBCAM-%E2%80%93-WEB-Camera.png', 'Disponible', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `juegos`
--

CREATE TABLE `juegos` (
  `id_juego` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `consola` varchar(50) NOT NULL,
  `id_equipo` int(11) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `disponibilidad` enum('Disponible','No disponible') DEFAULT 'Disponible'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `uso_equipos`
--

CREATE TABLE `uso_equipos` (
  `id_uso` int(11) NOT NULL,
  `id_equipo` int(11) DEFAULT NULL,
  `cedula_usuario` varchar(20) DEFAULT NULL,
  `fecha_hora_inicio` datetime DEFAULT NULL,
  `fecha_hora_fin` datetime DEFAULT NULL,
  `estado_equipo` enum('Bueno','Malo') DEFAULT 'Bueno',
  `costo` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `cedula` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `nivel` int(11) NOT NULL CHECK (`nivel` in (1,2,3,4,5)),
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `imagen` varchar(255) DEFAULT NULL,
  `clave` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asesores`
--
ALTER TABLE `asesores`
  ADD PRIMARY KEY (`cedula`);

--
-- Indices de la tabla `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id_mensaje`),
  ADD KEY `cedula_remitente` (`cedula_remitente`),
  ADD KEY `cedula_destinatario` (`cedula_destinatario`);

--
-- Indices de la tabla `datos_comprador`
--
ALTER TABLE `datos_comprador`
  ADD PRIMARY KEY (`id_factura`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `equipos`
--
ALTER TABLE `equipos`
  ADD PRIMARY KEY (`id_equipo`);

--
-- Indices de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD PRIMARY KEY (`cedula`),
  ADD KEY `id_profesor` (`id_profesor`);

--
-- Indices de la tabla `finanzas`
--
ALTER TABLE `finanzas`
  ADD PRIMARY KEY (`id_finanza`),
  ADD KEY `cedula_administrador` (`cedula_administrador`);

--
-- Indices de la tabla `inventario_venta`
--
ALTER TABLE `inventario_venta`
  ADD PRIMARY KEY (`id_producto`);

--
-- Indices de la tabla `juegos`
--
ALTER TABLE `juegos`
  ADD PRIMARY KEY (`id_juego`),
  ADD KEY `id_equipo` (`id_equipo`);

--
-- Indices de la tabla `uso_equipos`
--
ALTER TABLE `uso_equipos`
  ADD PRIMARY KEY (`id_uso`),
  ADD KEY `id_equipo` (`id_equipo`),
  ADD KEY `cedula_usuario` (`cedula_usuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`cedula`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `chat`
--
ALTER TABLE `chat`
  MODIFY `id_mensaje` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `datos_comprador`
--
ALTER TABLE `datos_comprador`
  MODIFY `id_factura` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `equipos`
--
ALTER TABLE `equipos`
  MODIFY `id_equipo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `finanzas`
--
ALTER TABLE `finanzas`
  MODIFY `id_finanza` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inventario_venta`
--
ALTER TABLE `inventario_venta`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `juegos`
--
ALTER TABLE `juegos`
  MODIFY `id_juego` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `uso_equipos`
--
ALTER TABLE `uso_equipos`
  MODIFY `id_uso` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asesores`
--
ALTER TABLE `asesores`
  ADD CONSTRAINT `asesores_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `usuarios` (`cedula`) ON DELETE CASCADE;

--
-- Filtros para la tabla `chat`
--
ALTER TABLE `chat`
  ADD CONSTRAINT `chat_ibfk_1` FOREIGN KEY (`cedula_remitente`) REFERENCES `usuarios` (`cedula`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_ibfk_2` FOREIGN KEY (`cedula_destinatario`) REFERENCES `usuarios` (`cedula`) ON DELETE CASCADE;

--
-- Filtros para la tabla `datos_comprador`
--
ALTER TABLE `datos_comprador`
  ADD CONSTRAINT `datos_comprador_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `inventario_venta` (`id_producto`) ON DELETE CASCADE;

--
-- Filtros para la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD CONSTRAINT `estudiantes_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `usuarios` (`cedula`) ON DELETE CASCADE,
  ADD CONSTRAINT `estudiantes_ibfk_2` FOREIGN KEY (`id_profesor`) REFERENCES `asesores` (`cedula`) ON DELETE SET NULL;

--
-- Filtros para la tabla `finanzas`
--
ALTER TABLE `finanzas`
  ADD CONSTRAINT `finanzas_ibfk_1` FOREIGN KEY (`cedula_administrador`) REFERENCES `usuarios` (`cedula`) ON DELETE CASCADE;

--
-- Filtros para la tabla `juegos`
--
ALTER TABLE `juegos`
  ADD CONSTRAINT `juegos_ibfk_1` FOREIGN KEY (`id_equipo`) REFERENCES `equipos` (`id_equipo`) ON DELETE SET NULL;

--
-- Filtros para la tabla `uso_equipos`
--
ALTER TABLE `uso_equipos`
  ADD CONSTRAINT `uso_equipos_ibfk_1` FOREIGN KEY (`id_equipo`) REFERENCES `equipos` (`id_equipo`),
  ADD CONSTRAINT `uso_equipos_ibfk_2` FOREIGN KEY (`cedula_usuario`) REFERENCES `usuarios` (`cedula`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
