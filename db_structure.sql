-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 21-12-2023 a las 08:27:39
-- Versión del servidor: 10.3.39-MariaDB-cll-lve
-- Versión de PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `urlcompc_uad`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_enlace_us`
--

CREATE TABLE `t_enlace_us` (
  `kEnlaceUS` int(11) NOT NULL,
  `fkSujeto` int(11) NOT NULL,
  `fkUrl` int(11) NOT NULL,
  `bDisponible` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_sujeto`
--

CREATE TABLE `t_sujeto` (
  `kSujeto` int(11) NOT NULL,
  `fkUsuario` int(11) NOT NULL,
  `sNombreSolicita` varchar(25) NOT NULL,
  `dtFechaAvaluo` date NOT NULL,
  `nObjetoAvaluo` tinyint(4) NOT NULL COMMENT ' 0=Conocer el valor comercial, 1=Originación de crédito, 2=Recuperación, 3=Adjudicación, 4=Dación de pago, 5=Otro',
  `sDomicilio` varchar(75) NOT NULL,
  `nCP` int(5) NOT NULL DEFAULT 0,
  `nMTerreno` float(6,2) NOT NULL,
  `nMConstruccion` float(6,2) NOT NULL,
  `nAntiguedad` int(3) NOT NULL,
  `nTipoPropiedad` tinyint(1) NOT NULL COMMENT '0=Casa habitación, 1=Terreno, 2=local comercial, 3=Otro',
  `nTipoVivienda` tinyint(1) NOT NULL COMMENT '0=no aplica, 1=económica, 2=popular, 3=tradicional, 4=media, 5=residencial, 6=residencial plus',
  `nEstadoConservacion` tinyint(1) NOT NULL COMMENT '0=No Aplica, 1=Remodelado, 2=Nuevo, 3=Muy Bueno, 4=Bueno, 5=Regular, 6=Malo, 7=Ruinoso',
  `nNotaGeneral` varchar(255) NOT NULL,
  `dtFechaRegistro` datetime NOT NULL,
  `bDisponible` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_sujeto_img`
--

CREATE TABLE `t_sujeto_img` (
  `kSujetoImg` int(11) NOT NULL,
  `fkSujeto` int(11) NOT NULL,
  `sNameImg` varchar(64) NOT NULL,
  `bDisponible` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_sujeto_info`
--

CREATE TABLE `t_sujeto_info` (
  `kSujetoInfo` int(11) NOT NULL,
  `fkSujeto` int(11) NOT NULL,
  `sTitulo` varchar(50) NOT NULL,
  `sInfo` varchar(240) NOT NULL,
  `bDisponible` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_url`
--

CREATE TABLE `t_url` (
  `kUrl` int(11) NOT NULL,
  `fkUsuario` int(11) NOT NULL,
  `sUrl` varchar(8192) NOT NULL,
  `sShortUrl` varchar(16) NOT NULL,
  `sNombreCliente` varchar(64) NOT NULL,
  `sNota` varchar(240) NOT NULL,
  `dtFecha` datetime NOT NULL DEFAULT current_timestamp(),
  `bImg` int(11) NOT NULL DEFAULT 1 COMMENT '1=mostrar, 0=ocultar',
  `bDisponible` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_url_img`
--

CREATE TABLE `t_url_img` (
  `kUrlImg` int(11) NOT NULL,
  `fkUrl` int(11) NOT NULL,
  `sNameImg` varchar(64) NOT NULL,
  `bDisponible` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_usuario`
--

CREATE TABLE `t_usuario` (
  `kUsuario` int(11) NOT NULL,
  `sUsuario` varchar(50) NOT NULL,
  `sNombre` varchar(50) NOT NULL,
  `nRol` tinyint(1) NOT NULL COMMENT '0=Master, 1=superuser, 2=user, 3=cobro, 4=operacion',
  `sPassword` varchar(50) NOT NULL,
  `sEmail` varchar(60) NOT NULL,
  `dateRegistered` date NOT NULL,
  `bDisponible` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0=no disp, 1=disponible'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `t_enlace_us`
--
ALTER TABLE `t_enlace_us`
  ADD PRIMARY KEY (`kEnlaceUS`);

--
-- Indices de la tabla `t_sujeto`
--
ALTER TABLE `t_sujeto`
  ADD PRIMARY KEY (`kSujeto`);

--
-- Indices de la tabla `t_sujeto_img`
--
ALTER TABLE `t_sujeto_img`
  ADD PRIMARY KEY (`kSujetoImg`);

--
-- Indices de la tabla `t_sujeto_info`
--
ALTER TABLE `t_sujeto_info`
  ADD PRIMARY KEY (`kSujetoInfo`);

--
-- Indices de la tabla `t_url`
--
ALTER TABLE `t_url`
  ADD PRIMARY KEY (`kUrl`);

--
-- Indices de la tabla `t_url_img`
--
ALTER TABLE `t_url_img`
  ADD PRIMARY KEY (`kUrlImg`);

--
-- Indices de la tabla `t_usuario`
--
ALTER TABLE `t_usuario`
  ADD PRIMARY KEY (`kUsuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `t_enlace_us`
--
ALTER TABLE `t_enlace_us`
  MODIFY `kEnlaceUS` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `t_sujeto`
--
ALTER TABLE `t_sujeto`
  MODIFY `kSujeto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `t_sujeto_img`
--
ALTER TABLE `t_sujeto_img`
  MODIFY `kSujetoImg` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `t_sujeto_info`
--
ALTER TABLE `t_sujeto_info`
  MODIFY `kSujetoInfo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `t_url`
--
ALTER TABLE `t_url`
  MODIFY `kUrl` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `t_url_img`
--
ALTER TABLE `t_url_img`
  MODIFY `kUrlImg` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `t_usuario`
--
ALTER TABLE `t_usuario`
  MODIFY `kUsuario` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
