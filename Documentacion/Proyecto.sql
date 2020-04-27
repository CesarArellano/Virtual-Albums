-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 24-04-2020 a las 13:42:12
-- Versión del servidor: 8.0.19-0ubuntu0.19.10.3
-- Versión de PHP: 7.3.11-0ubuntu0.19.10.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `Proyecto`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Albumes`
--
CREATE DATABASE Proyecto  CHARACTER SET utf8 COLLATE=utf8_spanish_ci;
CREATE TABLE `Albumes` (
  `idAlbum` int NOT NULL,
  `idTema` int NOT NULL,
  `idUsuario` int NOT NULL,
  `titulo` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
  `tipoAlbum` enum('Público','Privado') COLLATE utf8_spanish_ci NOT NULL,
  `visitas` int NOT NULL,
  `fechaAlbum` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Fotos`
--

CREATE TABLE `Fotos` (
  `idFoto` int NOT NULL,
  `idAlbum` int NOT NULL,
  `fechaFoto` date NOT NULL,
  `rutaFoto` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `HistorialBusqueda`
--

CREATE TABLE `HistorialBusqueda` (
  `idBusqueda` int NOT NULL,
  `idUsuario` int NOT NULL,
  `busqueda` varchar(300) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Notificaciones`
--

CREATE TABLE `Notificaciones` (
  `idNotificacion` int NOT NULL,
  `idAlbum` int NOT NULL,
  `contenido` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `NotificacionesLeidas`
--

CREATE TABLE `NotificacionesLeidas` (
  `idNotificacionLeida` int NOT NULL,
  `idNotificacion` int NOT NULL,
  `idUsuario` int NOT NULL,
  `estado` enum('Leída','No Leída') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `PuntuacionesComentarios`
--

CREATE TABLE `PuntuacionesComentarios` (
  `idPuntuacionComentario` int NOT NULL,
  `idUsuario` int NOT NULL,
  `idFoto` int NOT NULL,
  `puntuacion` decimal(2,1) DEFAULT NULL,
  `comentario` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Sesiones`
--

CREATE TABLE `Sesiones` (
  `idSesion` int NOT NULL,
  `idUsuario` int NOT NULL,
  `token` varchar(40) NOT NULL,
  `timestamp` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Suscripciones`
--

CREATE TABLE `Suscripciones` (
  `idSuscripcion` int NOT NULL,
  `idUsuario` int NOT NULL,
  `idAlbum` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Temas`
--

CREATE TABLE `Temas` (
  `idTema` int NOT NULL,
  `nombreTema` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Usuarios`
--

CREATE TABLE `Usuarios` (
  `idUsuario` int NOT NULL,
  `nombreUsuario` varchar(80) DEFAULT NULL,
  `apPaternoUsuario` varchar(80) DEFAULT NULL,
  `apMaternoUsuario` varchar(80) DEFAULT NULL,
  `escolaridad` enum('Sin estudios','Básica','Media','Media Superior','Superior') DEFAULT NULL,
  `direccion` varchar(300) DEFAULT NULL,
  `nacimiento` date DEFAULT NULL,
  `foto` varchar(300) DEFAULT NULL,
  `correo` varchar(150) NOT NULL,
  `password` varchar(32) NOT NULL,
  `tipoUsuario` enum('1','2') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Usuarios`
--

INSERT INTO `Usuarios` (`idUsuario`, `nombreUsuario`, `apPaternoUsuario`, `apMaternoUsuario`, `escolaridad`, `direccion`, `nacimiento`, `foto`, `correo`, `password`, `tipoUsuario`) VALUES
(1, 'cesarav', NULL, NULL, NULL, NULL, NULL, NULL, 'cesarmauricio.arellano@gmail.com', '12345678', '1'),
(2, 'raulgp', NULL, NULL, NULL, NULL, NULL, NULL, 'raulgonzalezportillo@gmail.com', '12345678', '1');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `Albumes`
--
ALTER TABLE `Albumes`
  ADD PRIMARY KEY (`idAlbum`),
  ADD KEY `idTemaAlbumes_idx` (`idTema`),
  ADD KEY `idUsuarioAlbumes_idx` (`idUsuario`);

--
-- Indices de la tabla `Fotos`
--
ALTER TABLE `Fotos`
  ADD PRIMARY KEY (`idFoto`),
  ADD KEY `idAlbumFotos_idx` (`idAlbum`);

--
-- Indices de la tabla `HistorialBusqueda`
--
ALTER TABLE `HistorialBusqueda`
  ADD PRIMARY KEY (`idBusqueda`),
  ADD KEY `idUsuarioHistorial_idx` (`idUsuario`);

--
-- Indices de la tabla `Notificaciones`
--
ALTER TABLE `Notificaciones`
  ADD PRIMARY KEY (`idNotificacion`),
  ADD KEY `idAlbumNotificaciones_idx` (`idAlbum`);

--
-- Indices de la tabla `NotificacionesLeidas`
--
ALTER TABLE `NotificacionesLeidas`
  ADD PRIMARY KEY (`idNotificacionLeida`),
  ADD KEY `idNotificacionNotificacionesLeidas_idx` (`idNotificacion`),
  ADD KEY `idUsuarioNotificacionesLeidas_idx` (`idUsuario`);

--
-- Indices de la tabla `PuntuacionesComentarios`
--
ALTER TABLE `PuntuacionesComentarios`
  ADD PRIMARY KEY (`idPuntuacionComentario`),
  ADD KEY `idUsuarioPuntuaciones_idx` (`idUsuario`),
  ADD KEY `idFotoPuntuaciones_idx` (`idFoto`);

--
-- Indices de la tabla `Sesiones`
--
ALTER TABLE `Sesiones`
  ADD PRIMARY KEY (`idSesion`),
  ADD KEY `idUsuarioSesiones_idx` (`idUsuario`);

--
-- Indices de la tabla `Suscripciones`
--
ALTER TABLE `Suscripciones`
  ADD PRIMARY KEY (`idSuscripcion`),
  ADD KEY `idUsuarioSuscripciones_idx` (`idUsuario`),
  ADD KEY `idAlbumSuscripciones_idx` (`idAlbum`);

--
-- Indices de la tabla `Temas`
--
ALTER TABLE `Temas`
  ADD PRIMARY KEY (`idTema`);

--
-- Indices de la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  ADD PRIMARY KEY (`idUsuario`),
  ADD UNIQUE KEY `correo_UNIQUE` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `Albumes`
--
ALTER TABLE `Albumes`
  MODIFY `idAlbum` int NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `Fotos`
--
ALTER TABLE `Fotos`
  MODIFY `idFoto` int NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `HistorialBusqueda`
--
ALTER TABLE `HistorialBusqueda`
  MODIFY `idBusqueda` int NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `Notificaciones`
--
ALTER TABLE `Notificaciones`
  MODIFY `idNotificacion` int NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `NotificacionesLeidas`
--
ALTER TABLE `NotificacionesLeidas`
  MODIFY `idNotificacionLeida` int NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `PuntuacionesComentarios`
--
ALTER TABLE `PuntuacionesComentarios`
  MODIFY `idPuntuacionComentario` int NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `Sesiones`
--
ALTER TABLE `Sesiones`
  MODIFY `idSesion` int NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `Suscripciones`
--
ALTER TABLE `Suscripciones`
  MODIFY `idSuscripcion` int NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  MODIFY `idUsuario` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `Albumes`
--
ALTER TABLE `Albumes`
  ADD CONSTRAINT `idTemaAlbumes` FOREIGN KEY (`idTema`) REFERENCES `Temas` (`idTema`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `idUsuarioAlbumes` FOREIGN KEY (`idUsuario`) REFERENCES `Usuarios` (`idUsuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `Fotos`
--
ALTER TABLE `Fotos`
  ADD CONSTRAINT `idAlbumFotos` FOREIGN KEY (`idAlbum`) REFERENCES `Albumes` (`idAlbum`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `HistorialBusqueda`
--
ALTER TABLE `HistorialBusqueda`
  ADD CONSTRAINT `idUsuarioHistorial` FOREIGN KEY (`idUsuario`) REFERENCES `Usuarios` (`idUsuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `Notificaciones`
--
ALTER TABLE `Notificaciones`
  ADD CONSTRAINT `idAlbumNotificaciones` FOREIGN KEY (`idAlbum`) REFERENCES `Albumes` (`idAlbum`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `NotificacionesLeidas`
--
ALTER TABLE `NotificacionesLeidas`
  ADD CONSTRAINT `idNotificacionNotificacionesLeidas` FOREIGN KEY (`idNotificacion`) REFERENCES `Notificaciones` (`idNotificacion`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `idUsuarioNotificacionesLeidas` FOREIGN KEY (`idUsuario`) REFERENCES `Usuarios` (`idUsuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `PuntuacionesComentarios`
--
ALTER TABLE `PuntuacionesComentarios`
  ADD CONSTRAINT `idFotoPuntuaciones` FOREIGN KEY (`idFoto`) REFERENCES `Fotos` (`idFoto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `idUsuarioPuntuaciones` FOREIGN KEY (`idUsuario`) REFERENCES `Usuarios` (`idUsuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `Sesiones`
--
ALTER TABLE `Sesiones`
  ADD CONSTRAINT `idUsuarioSesiones` FOREIGN KEY (`idUsuario`) REFERENCES `Usuarios` (`idUsuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `Suscripciones`
--
ALTER TABLE `Suscripciones`
  ADD CONSTRAINT `idAlbumSuscripciones` FOREIGN KEY (`idAlbum`) REFERENCES `Albumes` (`idAlbum`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `idUsuarioSuscripciones` FOREIGN KEY (`idUsuario`) REFERENCES `Usuarios` (`idUsuario`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
