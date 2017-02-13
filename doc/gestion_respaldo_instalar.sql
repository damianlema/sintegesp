-- phpMyAdmin SQL Dump
-- version 2.10.2
-- http://www.phpmyadmin.net
-- 
-- Servidor: localhost
-- Tiempo de generación: 01-06-2012 a las 00:39:01
-- Versión del servidor: 5.0.45
-- Versión de PHP: 5.2.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Base de datos: `gestion_respaldo_2012`
-- 
CREATE DATABASE `gestion_respaldo_2013` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `abril`
-- 

CREATE TABLE `abril` (
  `idabril` int(11) NOT NULL auto_increment,
  `tipo` varchar(45) NOT NULL,
  `tabla` varchar(45) NOT NULL,
  `usuario` varchar(45) NOT NULL,
  `fechayhora` datetime NOT NULL,
  `estacion` varchar(45) NOT NULL,
  PRIMARY KEY  (`idabril`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `abril`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `agosto`
-- 

CREATE TABLE `agosto` (
  `idagosto` int(11) NOT NULL auto_increment,
  `tipo` varchar(45) NOT NULL,
  `tabla` varchar(45) NOT NULL,
  `usuario` varchar(45) NOT NULL,
  `fechayhora` datetime NOT NULL,
  `estacion` varchar(45) NOT NULL,
  PRIMARY KEY  (`idagosto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `agosto`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `diciembre`
-- 

CREATE TABLE `diciembre` (
  `iddiciembre` int(11) NOT NULL auto_increment,
  `tipo` varchar(45) NOT NULL,
  `tabla` varchar(45) NOT NULL,
  `usuario` varchar(45) NOT NULL,
  `fechayhora` datetime NOT NULL,
  `estacion` varchar(45) NOT NULL,
  PRIMARY KEY  (`iddiciembre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `diciembre`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `enero`
-- 

CREATE TABLE `enero` (
  `idenero` int(11) NOT NULL auto_increment,
  `tipo` varchar(45) NOT NULL,
  `tabla` varchar(45) NOT NULL,
  `usuario` varchar(45) NOT NULL,
  `fechayhora` datetime NOT NULL,
  `estacion` varchar(45) NOT NULL,
  PRIMARY KEY  (`idenero`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `enero`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `febrero`
-- 

CREATE TABLE `febrero` (
  `idfebrero` int(11) NOT NULL auto_increment,
  `tipo` varchar(45) NOT NULL,
  `tabla` varchar(45) NOT NULL,
  `usuario` varchar(45) NOT NULL,
  `fechayhora` datetime NOT NULL,
  `estacion` varchar(45) NOT NULL,
  PRIMARY KEY  (`idfebrero`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `febrero`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `julio`
-- 

CREATE TABLE `julio` (
  `idjulio` int(11) NOT NULL auto_increment,
  `tipo` varchar(45) NOT NULL,
  `tabla` varchar(45) NOT NULL,
  `usuario` varchar(45) NOT NULL,
  `fechayhora` datetime NOT NULL,
  `estacion` varchar(45) NOT NULL,
  PRIMARY KEY  (`idjulio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `julio`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `junio`
-- 

CREATE TABLE `junio` (
  `idjunio` int(11) NOT NULL auto_increment,
  `tipo` varchar(45) NOT NULL,
  `tabla` varchar(45) NOT NULL,
  `usuario` varchar(45) NOT NULL,
  `fechayhora` datetime NOT NULL,
  `estacion` varchar(45) NOT NULL,
  PRIMARY KEY  (`idjunio`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=115 ;

-- 
-- Volcar la base de datos para la tabla `junio`
-- 

INSERT INTO `junio` VALUES 
(1, 'Listar Documentos Recibidos en  ', 'documentos_recibidos', 'hectorlema', '2012-05-30 15:20:37', 'Edsgar-PC'),
(2, 'Listar Documentos Recibidos en  ', 'documentos_recibidos', 'hectorlema', '2012-05-30 15:21:49', 'Edsgar-PC'),
(3, 'Inicio de Session (hectorlema)', 'inicio_sesion_exitoso', 'hectorlema', '2012-05-30 15:26:29', 'Edsgar-PC'),
(4, 'Listar Documentos Recibidos en  ', 'documentos_recibidos', 'hectorlema', '2012-05-30 15:26:38', 'Edsgar-PC'),
(5, 'Inicio de Session (hectorlema)', 'inicio_sesion_exitoso', 'hectorlema', '2012-05-30 15:27:16', 'Edsgar-PC'),
(6, 'Listar Documentos Recibidos en  ', 'documentos_recibidos', 'hectorlema', '2012-05-30 15:27:20', 'Edsgar-PC'),
(7, 'Listar Documentos Recibidos en  ', 'documentos_recibidos', 'hectorlema', '2012-05-30 15:27:25', 'Edsgar-PC'),
(8, 'Ingresar Datos Basicos de la Orden de Pago (9', 'orden_pago', 'hectorlema', '2012-05-30 15:36:17', 'Edsgar-PC'),
(9, 'Actualizar Datos Basicos con Afectacion Presu', 'orden_pago', 'hectorlema', '2012-05-30 15:36:17', 'Edsgar-PC'),
(10, 'Ingresar Partidas Individuales (ID Orden: 99,', 'orden_pago', 'hectorlema', '2012-05-30 15:36:17', 'Edsgar-PC'),
(11, 'Ingresar Partidas Individuales (ID Orden: 99,', 'orden_pago', 'hectorlema', '2012-05-30 15:36:17', 'Edsgar-PC'),
(12, 'Actualizar Datos Basicos con Afectacion Presu', 'orden_pago', 'hectorlema', '2012-05-30 15:36:17', 'Edsgar-PC'),
(13, 'Actualizar Datos Basicos con Afectacion Presu', 'orden_pago', 'hectorlema', '2012-05-30 15:36:17', 'Edsgar-PC'),
(14, 'Actualizar Datos Basicos con Afectacion Presu', 'orden_pago', 'hectorlema', '2012-05-30 15:36:17', 'Edsgar-PC'),
(15, 'Procesar Orden de Pago (OPFCI-2012-36)', 'orden_pago', 'hectorlema', '2012-05-30 15:36:17', 'Edsgar-PC'),
(16, 'Ingresar Datos Basicos de la Orden de Pago (1', 'orden_pago', 'hectorlema', '2012-05-30 15:45:13', 'Edsgar-PC'),
(17, 'Actualizar Datos Basicos con Afectacion Presu', 'orden_pago', 'hectorlema', '2012-05-30 15:45:13', 'Edsgar-PC'),
(18, 'Ingresar Partidas Individuales (ID Orden: 100', 'orden_pago', 'hectorlema', '2012-05-30 15:45:13', 'Edsgar-PC'),
(19, 'Ingresar Partidas Individuales (ID Orden: 100', 'orden_pago', 'hectorlema', '2012-05-30 15:45:13', 'Edsgar-PC'),
(20, 'Registrar Emision de Pago (OFCI-2012-35)', 'emision_pagos', 'hectorlema', '2012-05-30 15:46:10', 'Edsgar-PC'),
(21, 'Ingresar Datos Basicos de la Orden de Pago (1', 'orden_pago', 'hectorlema', '2012-05-30 21:41:31', 'Edsgar-PC'),
(22, 'Ingresar Partidas Individuales (ID Orden: 101', 'orden_pago', 'hectorlema', '2012-05-30 21:41:31', 'Edsgar-PC'),
(23, 'Ingresar Partidas Individuales (ID Orden: 101', 'orden_pago', 'hectorlema', '2012-05-30 21:41:31', 'Edsgar-PC'),
(24, 'Actualizar Datos Basicos con Afectacion Presu', 'orden_pago', 'hectorlema', '2012-05-30 21:41:31', 'Edsgar-PC'),
(25, 'Actualizar Datos Basicos con Afectacion Presu', 'orden_pago', 'hectorlema', '2012-05-30 21:41:31', 'Edsgar-PC'),
(26, 'Inicio de Session (hectorlema)', 'inicio_sesion_exitoso', 'hectorlema', '2012-06-01 00:27:38', 'Edsgar-PC'),
(27, 'Listar Documentos Recibidos en  ', 'documentos_recibidos', 'hectorlema', '2012-05-30 15:20:37', 'Edsgar-PC'),
(28, 'Listar Documentos Recibidos en  ', 'documentos_recibidos', 'hectorlema', '2012-05-30 15:21:49', 'Edsgar-PC'),
(29, 'Inicio de Session (hectorlema)', 'inicio_sesion_exitoso', 'hectorlema', '2012-05-30 15:26:29', 'Edsgar-PC'),
(30, 'Listar Documentos Recibidos en  ', 'documentos_recibidos', 'hectorlema', '2012-05-30 15:26:38', 'Edsgar-PC'),
(31, 'Inicio de Session (hectorlema)', 'inicio_sesion_exitoso', 'hectorlema', '2012-05-30 15:27:16', 'Edsgar-PC'),
(32, 'Listar Documentos Recibidos en  ', 'documentos_recibidos', 'hectorlema', '2012-05-30 15:27:20', 'Edsgar-PC'),
(33, 'Listar Documentos Recibidos en  ', 'documentos_recibidos', 'hectorlema', '2012-05-30 15:27:25', 'Edsgar-PC'),
(34, 'Ingresar Datos Basicos de la Orden de Pago (9', 'orden_pago', 'hectorlema', '2012-05-30 15:36:17', 'Edsgar-PC'),
(35, 'Actualizar Datos Basicos con Afectacion Presu', 'orden_pago', 'hectorlema', '2012-05-30 15:36:17', 'Edsgar-PC'),
(36, 'Ingresar Partidas Individuales (ID Orden: 99,', 'orden_pago', 'hectorlema', '2012-05-30 15:36:17', 'Edsgar-PC'),
(37, 'Ingresar Partidas Individuales (ID Orden: 99,', 'orden_pago', 'hectorlema', '2012-05-30 15:36:17', 'Edsgar-PC'),
(38, 'Actualizar Datos Basicos con Afectacion Presu', 'orden_pago', 'hectorlema', '2012-05-30 15:36:17', 'Edsgar-PC'),
(39, 'Actualizar Datos Basicos con Afectacion Presu', 'orden_pago', 'hectorlema', '2012-05-30 15:36:17', 'Edsgar-PC'),
(40, 'Actualizar Datos Basicos con Afectacion Presu', 'orden_pago', 'hectorlema', '2012-05-30 15:36:17', 'Edsgar-PC'),
(41, 'Procesar Orden de Pago (OPFCI-2012-36)', 'orden_pago', 'hectorlema', '2012-05-30 15:36:17', 'Edsgar-PC'),
(42, 'Ingresar Datos Basicos de la Orden de Pago (1', 'orden_pago', 'hectorlema', '2012-05-30 15:45:13', 'Edsgar-PC'),
(43, 'Actualizar Datos Basicos con Afectacion Presu', 'orden_pago', 'hectorlema', '2012-05-30 15:45:13', 'Edsgar-PC'),
(44, 'Ingresar Partidas Individuales (ID Orden: 100', 'orden_pago', 'hectorlema', '2012-05-30 15:45:13', 'Edsgar-PC'),
(45, 'Ingresar Partidas Individuales (ID Orden: 100', 'orden_pago', 'hectorlema', '2012-05-30 15:45:13', 'Edsgar-PC'),
(46, 'Registrar Emision de Pago (OFCI-2012-35)', 'emision_pagos', 'hectorlema', '2012-05-30 15:46:10', 'Edsgar-PC'),
(47, 'Ingresar Datos Basicos de la Orden de Pago (1', 'orden_pago', 'hectorlema', '2012-05-30 21:41:31', 'Edsgar-PC'),
(48, 'Ingresar Partidas Individuales (ID Orden: 101', 'orden_pago', 'hectorlema', '2012-05-30 21:41:31', 'Edsgar-PC'),
(49, 'Ingresar Partidas Individuales (ID Orden: 101', 'orden_pago', 'hectorlema', '2012-05-30 21:41:31', 'Edsgar-PC'),
(50, 'Actualizar Datos Basicos con Afectacion Presu', 'orden_pago', 'hectorlema', '2012-05-30 21:41:31', 'Edsgar-PC'),
(51, 'Actualizar Datos Basicos con Afectacion Presu', 'orden_pago', 'hectorlema', '2012-05-30 21:41:31', 'Edsgar-PC'),
(52, 'Inicio de Session (hectorlema)', 'inicio_sesion_exitoso', 'hectorlema', '2012-06-01 00:27:38', 'Edsgar-PC'),
(53, 'Inicio de Session (hectorlema)', 'inicio_sesion_exitoso', 'hectorlema', '2012-06-01 00:28:29', 'Edsgar-PC'),
(54, 'Listar Documentos Recibidos en  ', 'documentos_recibidos', 'hectorlema', '2012-05-30 15:20:37', 'Edsgar-PC'),
(55, 'Listar Documentos Recibidos en  ', 'documentos_recibidos', 'hectorlema', '2012-05-30 15:21:49', 'Edsgar-PC'),
(56, 'Inicio de Session (hectorlema)', 'inicio_sesion_exitoso', 'hectorlema', '2012-05-30 15:26:29', 'Edsgar-PC'),
(57, 'Listar Documentos Recibidos en  ', 'documentos_recibidos', 'hectorlema', '2012-05-30 15:26:38', 'Edsgar-PC'),
(58, 'Inicio de Session (hectorlema)', 'inicio_sesion_exitoso', 'hectorlema', '2012-05-30 15:27:16', 'Edsgar-PC'),
(59, 'Listar Documentos Recibidos en  ', 'documentos_recibidos', 'hectorlema', '2012-05-30 15:27:20', 'Edsgar-PC'),
(60, 'Listar Documentos Recibidos en  ', 'documentos_recibidos', 'hectorlema', '2012-05-30 15:27:25', 'Edsgar-PC'),
(61, 'Ingresar Datos Basicos de la Orden de Pago (9', 'orden_pago', 'hectorlema', '2012-05-30 15:36:17', 'Edsgar-PC'),
(62, 'Actualizar Datos Basicos con Afectacion Presu', 'orden_pago', 'hectorlema', '2012-05-30 15:36:17', 'Edsgar-PC'),
(63, 'Ingresar Partidas Individuales (ID Orden: 99,', 'orden_pago', 'hectorlema', '2012-05-30 15:36:17', 'Edsgar-PC'),
(64, 'Ingresar Partidas Individuales (ID Orden: 99,', 'orden_pago', 'hectorlema', '2012-05-30 15:36:17', 'Edsgar-PC'),
(65, 'Actualizar Datos Basicos con Afectacion Presu', 'orden_pago', 'hectorlema', '2012-05-30 15:36:17', 'Edsgar-PC'),
(66, 'Actualizar Datos Basicos con Afectacion Presu', 'orden_pago', 'hectorlema', '2012-05-30 15:36:17', 'Edsgar-PC'),
(67, 'Actualizar Datos Basicos con Afectacion Presu', 'orden_pago', 'hectorlema', '2012-05-30 15:36:17', 'Edsgar-PC'),
(68, 'Procesar Orden de Pago (OPFCI-2012-36)', 'orden_pago', 'hectorlema', '2012-05-30 15:36:17', 'Edsgar-PC'),
(69, 'Ingresar Datos Basicos de la Orden de Pago (1', 'orden_pago', 'hectorlema', '2012-05-30 15:45:13', 'Edsgar-PC'),
(70, 'Actualizar Datos Basicos con Afectacion Presu', 'orden_pago', 'hectorlema', '2012-05-30 15:45:13', 'Edsgar-PC'),
(71, 'Ingresar Partidas Individuales (ID Orden: 100', 'orden_pago', 'hectorlema', '2012-05-30 15:45:13', 'Edsgar-PC'),
(72, 'Ingresar Partidas Individuales (ID Orden: 100', 'orden_pago', 'hectorlema', '2012-05-30 15:45:13', 'Edsgar-PC'),
(73, 'Registrar Emision de Pago (OFCI-2012-35)', 'emision_pagos', 'hectorlema', '2012-05-30 15:46:10', 'Edsgar-PC'),
(74, 'Ingresar Datos Basicos de la Orden de Pago (1', 'orden_pago', 'hectorlema', '2012-05-30 21:41:31', 'Edsgar-PC'),
(75, 'Ingresar Partidas Individuales (ID Orden: 101', 'orden_pago', 'hectorlema', '2012-05-30 21:41:31', 'Edsgar-PC'),
(76, 'Ingresar Partidas Individuales (ID Orden: 101', 'orden_pago', 'hectorlema', '2012-05-30 21:41:31', 'Edsgar-PC'),
(77, 'Actualizar Datos Basicos con Afectacion Presu', 'orden_pago', 'hectorlema', '2012-05-30 21:41:31', 'Edsgar-PC'),
(78, 'Actualizar Datos Basicos con Afectacion Presu', 'orden_pago', 'hectorlema', '2012-05-30 21:41:31', 'Edsgar-PC'),
(79, 'Inicio de Session (hectorlema)', 'inicio_sesion_exitoso', 'hectorlema', '2012-06-01 00:27:38', 'Edsgar-PC'),
(80, 'Inicio de Session (hectorlema)', 'inicio_sesion_exitoso', 'hectorlema', '2012-06-01 00:28:29', 'Edsgar-PC'),
(81, 'Ingresar Usuarios (1234)', 'usuarios', 'hectorlema', '2012-06-01 00:34:02', 'Edsgar-PC'),
(82, 'Listar Documentos Recibidos en  ', 'documentos_recibidos', 'hectorlema', '2012-05-30 15:20:37', 'Edsgar-PC'),
(83, 'Listar Documentos Recibidos en  ', 'documentos_recibidos', 'hectorlema', '2012-05-30 15:21:49', 'Edsgar-PC'),
(84, 'Inicio de Session (hectorlema)', 'inicio_sesion_exitoso', 'hectorlema', '2012-05-30 15:26:29', 'Edsgar-PC'),
(85, 'Listar Documentos Recibidos en  ', 'documentos_recibidos', 'hectorlema', '2012-05-30 15:26:38', 'Edsgar-PC'),
(86, 'Inicio de Session (hectorlema)', 'inicio_sesion_exitoso', 'hectorlema', '2012-05-30 15:27:16', 'Edsgar-PC'),
(87, 'Listar Documentos Recibidos en  ', 'documentos_recibidos', 'hectorlema', '2012-05-30 15:27:20', 'Edsgar-PC'),
(88, 'Listar Documentos Recibidos en  ', 'documentos_recibidos', 'hectorlema', '2012-05-30 15:27:25', 'Edsgar-PC'),
(89, 'Ingresar Datos Basicos de la Orden de Pago (9', 'orden_pago', 'hectorlema', '2012-05-30 15:36:17', 'Edsgar-PC'),
(90, 'Actualizar Datos Basicos con Afectacion Presu', 'orden_pago', 'hectorlema', '2012-05-30 15:36:17', 'Edsgar-PC'),
(91, 'Ingresar Partidas Individuales (ID Orden: 99,', 'orden_pago', 'hectorlema', '2012-05-30 15:36:17', 'Edsgar-PC'),
(92, 'Ingresar Partidas Individuales (ID Orden: 99,', 'orden_pago', 'hectorlema', '2012-05-30 15:36:17', 'Edsgar-PC'),
(93, 'Actualizar Datos Basicos con Afectacion Presu', 'orden_pago', 'hectorlema', '2012-05-30 15:36:17', 'Edsgar-PC'),
(94, 'Actualizar Datos Basicos con Afectacion Presu', 'orden_pago', 'hectorlema', '2012-05-30 15:36:17', 'Edsgar-PC'),
(95, 'Actualizar Datos Basicos con Afectacion Presu', 'orden_pago', 'hectorlema', '2012-05-30 15:36:17', 'Edsgar-PC'),
(96, 'Procesar Orden de Pago (OPFCI-2012-36)', 'orden_pago', 'hectorlema', '2012-05-30 15:36:17', 'Edsgar-PC'),
(97, 'Ingresar Datos Basicos de la Orden de Pago (1', 'orden_pago', 'hectorlema', '2012-05-30 15:45:13', 'Edsgar-PC'),
(98, 'Actualizar Datos Basicos con Afectacion Presu', 'orden_pago', 'hectorlema', '2012-05-30 15:45:13', 'Edsgar-PC'),
(99, 'Ingresar Partidas Individuales (ID Orden: 100', 'orden_pago', 'hectorlema', '2012-05-30 15:45:13', 'Edsgar-PC'),
(100, 'Ingresar Partidas Individuales (ID Orden: 100', 'orden_pago', 'hectorlema', '2012-05-30 15:45:13', 'Edsgar-PC'),
(101, 'Registrar Emision de Pago (OFCI-2012-35)', 'emision_pagos', 'hectorlema', '2012-05-30 15:46:10', 'Edsgar-PC'),
(102, 'Ingresar Datos Basicos de la Orden de Pago (1', 'orden_pago', 'hectorlema', '2012-05-30 21:41:31', 'Edsgar-PC'),
(103, 'Ingresar Partidas Individuales (ID Orden: 101', 'orden_pago', 'hectorlema', '2012-05-30 21:41:31', 'Edsgar-PC'),
(104, 'Ingresar Partidas Individuales (ID Orden: 101', 'orden_pago', 'hectorlema', '2012-05-30 21:41:31', 'Edsgar-PC'),
(105, 'Actualizar Datos Basicos con Afectacion Presu', 'orden_pago', 'hectorlema', '2012-05-30 21:41:31', 'Edsgar-PC'),
(106, 'Actualizar Datos Basicos con Afectacion Presu', 'orden_pago', 'hectorlema', '2012-05-30 21:41:31', 'Edsgar-PC'),
(107, 'Inicio de Session (hectorlema)', 'inicio_sesion_exitoso', 'hectorlema', '2012-06-01 00:27:38', 'Edsgar-PC'),
(108, 'Inicio de Session (hectorlema)', 'inicio_sesion_exitoso', 'hectorlema', '2012-06-01 00:28:29', 'Edsgar-PC'),
(109, 'Ingresar Usuarios (1234)', 'usuarios', 'hectorlema', '2012-06-01 00:34:02', 'Edsgar-PC'),
(110, 'Inicio de Session (usuario)', 'inicio_sesion_exitoso', 'usuario', '2012-06-01 00:34:30', 'Edsgar-PC'),
(111, 'Inicio de Session (usuario)', 'inicio_sesion_exitoso', 'usuario', '2012-06-01 00:34:30', 'Edsgar-PC'),
(112, 'Inicio de Session (usuario)', 'inicio_sesion_exitoso', 'usuario', '2012-06-01 00:36:01', 'Edsgar-PC'),
(113, 'Inicio de Session (usuario)', 'inicio_sesion_exitoso', 'usuario', '2012-06-01 00:34:30', 'Edsgar-PC'),
(114, 'Inicio de Session (usuario)', 'inicio_sesion_exitoso', 'usuario', '2012-06-01 00:36:01', 'Edsgar-PC');

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `marzo`
-- 

CREATE TABLE `marzo` (
  `idmarzo` int(11) NOT NULL auto_increment,
  `tipo` varchar(45) NOT NULL,
  `tabla` varchar(45) NOT NULL,
  `usuario` varchar(45) NOT NULL,
  `fechayhora` datetime NOT NULL,
  `estacion` varchar(45) NOT NULL,
  PRIMARY KEY  (`idmarzo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `marzo`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `mayo`
-- 

CREATE TABLE `mayo` (
  `idmayo` int(11) NOT NULL auto_increment,
  `tipo` varchar(45) NOT NULL,
  `tabla` varchar(45) NOT NULL,
  `usuario` varchar(45) NOT NULL,
  `fechayhora` datetime NOT NULL,
  `estacion` varchar(45) NOT NULL,
  PRIMARY KEY  (`idmayo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `mayo`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `noviembre`
-- 

CREATE TABLE `noviembre` (
  `idnoviembre` int(11) NOT NULL auto_increment,
  `tipo` varchar(45) NOT NULL,
  `tabla` varchar(45) NOT NULL,
  `usuario` varchar(45) NOT NULL,
  `fechayhora` datetime NOT NULL,
  `estacion` varchar(45) NOT NULL,
  PRIMARY KEY  (`idnoviembre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `noviembre`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `octubre`
-- 

CREATE TABLE `octubre` (
  `idoctubre` int(11) NOT NULL auto_increment,
  `tipo` varchar(45) NOT NULL,
  `tabla` varchar(45) NOT NULL,
  `usuario` varchar(45) NOT NULL,
  `fechayhora` datetime NOT NULL,
  `estacion` varchar(45) NOT NULL,
  PRIMARY KEY  (`idoctubre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `octubre`
-- 


-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `septiembre`
-- 

CREATE TABLE `septiembre` (
  `idseptiembre` int(11) NOT NULL auto_increment,
  `tipo` varchar(45) NOT NULL,
  `tabla` varchar(45) NOT NULL,
  `usuario` varchar(45) NOT NULL,
  `fechayhora` datetime NOT NULL,
  `estacion` varchar(45) NOT NULL,
  PRIMARY KEY  (`idseptiembre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Volcar la base de datos para la tabla `septiembre`
-- 

