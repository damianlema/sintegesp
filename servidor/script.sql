-- phpMyAdmin SQL Dump
-- version 2.10.2
-- http://www.phpmyadmin.net
-- 
-- Servidor: localhost
-- Tiempo de generación: 12-12-2011 a las 14:09:23
-- Versión del servidor: 5.0.45
-- Versión de PHP: 5.2.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Base de datos: `gestion_barrancas`
-- 

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `accion`
-- 

CREATE TABLE `accion` (
  `id_accion` int(10) unsigned NOT NULL auto_increment,
  `nombre_accion` varchar(45) character set utf8 NOT NULL default '',
  `id_modulo` int(10) unsigned NOT NULL default '0',
  `url` text character set utf8 NOT NULL,
  `mostrar` int(10) unsigned NOT NULL default '0',
  `accion_padre` int(10) unsigned NOT NULL default '0',
  `posicion` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id_accion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `actividad`
-- 

CREATE TABLE `actividad` (
  `idActividad` int(10) unsigned NOT NULL auto_increment,
  `codigo` varchar(2) NOT NULL default '',
  `denominacion` varchar(255) NOT NULL default '',
  `idproyecto` int(10) unsigned NOT NULL default '0',
  `status` varchar(1) default NULL,
  `usuario` varchar(20) default NULL,
  `fechayhora` datetime default NULL,
  PRIMARY KEY  (`idActividad`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `actividades_comerciales`
-- 

CREATE TABLE `actividades_comerciales` (
  `idactividades_comerciales` int(10) unsigned NOT NULL auto_increment,
  `denominacion` varchar(100) NOT NULL default '',
  `alicuota` decimal(16,2) NOT NULL default '0.00',
  PRIMARY KEY  (`idactividades_comerciales`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `actividades_contribuyente`
-- 

CREATE TABLE `actividades_contribuyente` (
  `idactividades_contribuyente` int(10) unsigned NOT NULL auto_increment,
  `idcontribuyente` int(10) unsigned NOT NULL default '0',
  `idactividad_comercial` int(10) unsigned NOT NULL default '0',
  `status` varchar(45) NOT NULL default '',
  `usuario` varchar(45) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idactividades_contribuyente`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `adjuntos_mensajes_correo`
-- 

CREATE TABLE `adjuntos_mensajes_correo` (
  `idadjuntos_mensajes_correo` int(10) unsigned NOT NULL auto_increment,
  `idmensajes_correo` int(10) unsigned NOT NULL default '0',
  `archivo` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`idadjuntos_mensajes_correo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `agrupacion_modulos`
-- 

CREATE TABLE `agrupacion_modulos` (
  `idagrupacion_modulos` int(10) unsigned NOT NULL auto_increment,
  `grupo` int(10) unsigned NOT NULL,
  `modulo` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`idagrupacion_modulos`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `articulos_compra_servicio`
-- 

CREATE TABLE `articulos_compra_servicio` (
  `idarticulos_compra_servicio` int(10) unsigned NOT NULL auto_increment,
  `idorden_compra_servicio` int(10) unsigned NOT NULL default '0',
  `idarticulos_servicios` int(10) unsigned NOT NULL,
  `idcategoria_programatica` int(11) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL default '0.00',
  `precio_unitario` decimal(16,2) default NULL,
  `porcentaje_impuesto` decimal(6,2) default NULL,
  `impuesto` decimal(16,2) default NULL,
  `total` decimal(16,2) default NULL,
  `exento` decimal(16,2) NOT NULL default '0.00',
  `idsolicitud_cotizacion` int(10) default '0',
  `estado` varchar(20) default NULL,
  `duplicado` int(1) NOT NULL default '0' COMMENT 'CERO - no duplicado / UNO - duplicado',
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  `idpartida_impuesto` int(10) unsigned NOT NULL default '0' COMMENT '0: El impuesto se suma a la partida del articulo; OTRO:El impuesto tiene partida propia',
  `registrado` varchar(45) NOT NULL default 'no',
  `fecha_registro` datetime NOT NULL default '0000-00-00 00:00:00',
  `usuario_registro` varchar(45) NOT NULL default '',
  PRIMARY KEY  (`idarticulos_compra_servicio`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `articulos_rendicion_caja_chica`
-- 

CREATE TABLE `articulos_rendicion_caja_chica` (
  `idarticulos_rendicion_caja_chica` int(10) unsigned NOT NULL auto_increment,
  `idorden_compra_servicio` int(10) unsigned NOT NULL default '0',
  `idarticulos_servicios` int(10) unsigned NOT NULL,
  `idcategoria_programatica` int(11) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL default '0.00',
  `precio_unitario` decimal(16,2) default NULL,
  `porcentaje_impuesto` decimal(6,2) default NULL,
  `impuesto` decimal(16,2) default NULL,
  `total` decimal(16,2) default NULL,
  `exento` decimal(16,2) NOT NULL default '0.00',
  `idsolicitud_cotizacion` int(10) default '0',
  `estado` varchar(20) default NULL,
  `duplicado` int(1) NOT NULL default '0' COMMENT 'CERO - no duplicado / UNO - duplicado',
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  `idpartida_impuesto` int(10) unsigned NOT NULL default '0' COMMENT '0: El impuesto se suma a la partida del articulo; OTRO:El impuesto tiene partida propia',
  `registrado` varchar(45) NOT NULL default 'no',
  `fecha_registro` datetime NOT NULL default '0000-00-00 00:00:00',
  `usuario_registro` varchar(45) NOT NULL default '',
  `idfactura_rendicion_caja_chica` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`idarticulos_rendicion_caja_chica`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `articulos_requisicion`
-- 

CREATE TABLE `articulos_requisicion` (
  `idarticulos_requisicion` int(10) unsigned NOT NULL auto_increment,
  `idrequisicion` int(10) unsigned NOT NULL default '0',
  `idarticulos_servicios` int(10) unsigned NOT NULL,
  `cantidad` decimal(10,2) NOT NULL default '0.00',
  `precio_unitario` decimal(16,2) default NULL,
  `porcentaje_impuesto` decimal(6,2) default NULL,
  `impuesto` decimal(16,2) default NULL,
  `total` decimal(16,2) default NULL,
  `exento` decimal(16,2) NOT NULL default '0.00',
  `idsolicitud_cotizacion` int(10) default '0',
  `estado` varchar(20) default NULL,
  `duplicado` int(1) NOT NULL default '0' COMMENT 'CERO - no duplicado / UNO - duplicado',
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  `idpartida_impuesto` int(10) unsigned NOT NULL default '0' COMMENT '0: El impuesto se suma a la partida del articulo; OTRO:El impuesto tiene partida propia',
  PRIMARY KEY  (`idarticulos_requisicion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `articulos_servicios`
-- 

CREATE TABLE `articulos_servicios` (
  `idarticulos_servicios` int(10) unsigned NOT NULL auto_increment,
  `codigo` varchar(20) NOT NULL,
  `tipo` varchar(45) NOT NULL,
  `descripcion` varchar(500) NOT NULL default '',
  `idunidad_medida` int(10) unsigned NOT NULL default '0',
  `idramo_articulo` int(10) unsigned NOT NULL default '0',
  `activo` varchar(1) default NULL,
  `idclasificador_presupuestario` int(10) unsigned NOT NULL default '0',
  `idsnc_detalle_grupo` int(10) unsigned NOT NULL default '0',
  `idimpuestos` int(10) unsigned NOT NULL,
  `exento` varchar(1) NOT NULL default '0',
  `ultimo_costo` decimal(10,0) default NULL,
  `costo_promedio` decimal(10,0) default NULL,
  `fecha_ultima_compra` date default NULL,
  `status` varchar(1) default NULL,
  `tipo_concepto` varchar(1) NOT NULL,
  `usuario` varchar(20) default NULL,
  `fechayhora` datetime default NULL,
  `idordinal` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`idarticulos_servicios`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `articulos_servicios_simulacion`
-- 

CREATE TABLE `articulos_servicios_simulacion` (
  `idarticulos_servicios_simulacion` int(10) unsigned NOT NULL auto_increment,
  `codigo` varchar(20) NOT NULL,
  `tipo` varchar(45) NOT NULL,
  `descripcion` varchar(500) NOT NULL default '',
  `idunidad_medida` int(10) unsigned NOT NULL default '0',
  `idramo_articulo` int(10) unsigned NOT NULL default '0',
  `activo` varchar(1) default NULL,
  `idclasificador_presupuestario` int(10) unsigned NOT NULL default '0',
  `idsnc_detalle_grupo` int(10) unsigned NOT NULL default '0',
  `idimpuestos` int(10) unsigned NOT NULL,
  `exento` varchar(1) NOT NULL default '0',
  `ultimo_costo` decimal(10,0) default NULL,
  `costo_promedio` decimal(10,0) default NULL,
  `fecha_ultima_compra` date default NULL,
  `status` varchar(1) default NULL,
  `tipo_concepto` varchar(1) NOT NULL,
  `usuario` varchar(20) default NULL,
  `fechayhora` datetime default NULL,
  `idordinal` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`idarticulos_servicios_simulacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `articulos_solicitud_cotizacion`
-- 

CREATE TABLE `articulos_solicitud_cotizacion` (
  `idarticulos_solicitud_cotizacion` int(10) unsigned NOT NULL auto_increment,
  `idsolicitud_cotizacion` int(10) unsigned NOT NULL default '0',
  `idarticulos_servicios` int(10) unsigned NOT NULL,
  `cantidad` decimal(10,2) NOT NULL default '0.00',
  `precio_unitario` decimal(16,2) default '0.00',
  `porcentaje_impuesto` decimal(6,2) default NULL,
  `impuesto` decimal(16,2) default NULL,
  `total` decimal(16,2) default NULL,
  `exento` decimal(16,2) NOT NULL default '0.00',
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idarticulos_solicitud_cotizacion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `asociar_concepto_actividad_comercial`
-- 

CREATE TABLE `asociar_concepto_actividad_comercial` (
  `idasociar_concepto_actividad_comercial` int(10) unsigned NOT NULL auto_increment,
  `idconcepto_tributario` int(10) unsigned NOT NULL default '0',
  `idactividad_comercial` int(10) unsigned NOT NULL default '0',
  `valor` decimal(16,2) NOT NULL default '0.00',
  `tipo_valor` varchar(45) NOT NULL default '',
  PRIMARY KEY  (`idasociar_concepto_actividad_comercial`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `asociar_requisitos_actividad_comercial`
-- 

CREATE TABLE `asociar_requisitos_actividad_comercial` (
  `idasociar_requisitos_actividad_comercial` int(10) unsigned NOT NULL auto_increment,
  `idactividad_comercial` int(10) unsigned NOT NULL default '0',
  `idrequisitos` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`idasociar_requisitos_actividad_comercial`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `asociar_requisitos_tipo_solicitud`
-- 

CREATE TABLE `asociar_requisitos_tipo_solicitud` (
  `idasociar_requisitos_tipo_solicitud` int(10) unsigned NOT NULL auto_increment,
  `idtipo_solicitud` int(10) unsigned NOT NULL default '0',
  `idrequisitos` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`idasociar_requisitos_tipo_solicitud`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `asociar_tipo_solicitud_concepto`
-- 

CREATE TABLE `asociar_tipo_solicitud_concepto` (
  `idasociar_tipo_solicitud_concepto` int(10) unsigned NOT NULL auto_increment,
  `idtipo_solicitud` int(10) unsigned NOT NULL default '0',
  `idconcepto_tributario` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`idasociar_tipo_solicitud_concepto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `banco`
-- 

CREATE TABLE `banco` (
  `idbanco` int(10) unsigned NOT NULL auto_increment,
  `denominacion` varchar(45) NOT NULL default '',
  `gerente` varchar(60) NOT NULL,
  `ci_gerente` varchar(12) NOT NULL,
  `cargo_gerente` varchar(200) NOT NULL,
  `fideicomiso` varchar(60) NOT NULL,
  `ci_fideicomiso` varchar(12) NOT NULL,
  `cargo_fideicomiso` varchar(200) NOT NULL,
  `atencion` varchar(100) NOT NULL,
  `usuario` varchar(20) NOT NULL default '',
  `status` varchar(1) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idbanco`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `beneficiarios`
-- 

CREATE TABLE `beneficiarios` (
  `idbeneficiarios` int(10) unsigned NOT NULL auto_increment,
  `nombre` varchar(250) NOT NULL default '',
  `rif` varchar(12) default NULL,
  `nro_expediente` varchar(20) NOT NULL,
  `direccion` varchar(300) default NULL,
  `telefonos` varchar(100) default NULL,
  `url` varchar(200) default NULL,
  `email` varchar(60) default NULL,
  `objeto` varchar(400) default NULL,
  `datos_registro` varchar(200) default NULL,
  `representante_legal` varchar(60) default NULL,
  `cedula_representante` varchar(12) default NULL,
  `telefono_representante` varchar(12) default NULL,
  `persona_autorizada` varchar(60) default NULL,
  `cedula_persona_autorizada` varchar(12) default NULL,
  `telefono_persona_autorizada` varchar(30) default NULL,
  `contribuyente_ordinario` varchar(2) NOT NULL,
  `idtipo_beneficiario` int(10) unsigned default NULL,
  `idtipo_sociedad` int(10) unsigned default NULL,
  `idestado_beneficiario` int(10) unsigned default NULL,
  `idtipos_persona` int(10) unsigned default NULL,
  `idtipo_empresa` int(10) unsigned default NULL,
  `pre_requisitos` varchar(1) default NULL,
  `status` varchar(1) default NULL,
  `usuario` varchar(20) default NULL,
  `fechayhora` datetime default NULL,
  `idestado` int(11) NOT NULL,
  PRIMARY KEY  (`idbeneficiarios`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `bienes_seleccionados_lotes`
-- 

CREATE TABLE `bienes_seleccionados_lotes` (
  `idbienes_seleccionados_lotes` int(10) unsigned NOT NULL auto_increment,
  `idbien` int(10) unsigned NOT NULL default '0',
  `codigo_bien` varchar(45) NOT NULL default '',
  `tipo_bien` varchar(45) NOT NULL default '',
  `idmovimiento` int(10) unsigned NOT NULL default '0',
  `idorganizacion_actual` int(10) unsigned NOT NULL default '0',
  `idnivel_organizacional_actual` int(10) unsigned NOT NULL default '0',
  `idorganizacion_destino` int(10) unsigned NOT NULL default '0',
  `idnivel_organizacional_destino` int(10) unsigned NOT NULL default '0',
  `estado` varchar(45) NOT NULL default '',
  PRIMARY KEY  (`idbienes_seleccionados_lotes`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `calle`
-- 

CREATE TABLE `calle` (
  `idcalle` int(10) unsigned NOT NULL auto_increment,
  `idurbanizacion` int(10) unsigned NOT NULL default '0',
  `denominacion` varchar(45) NOT NULL default '',
  `usuario` varchar(45) NOT NULL default '',
  `status` varchar(45) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idcalle`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `carga_familiar`
-- 

CREATE TABLE `carga_familiar` (
  `idcarga_familiar` int(10) unsigned NOT NULL auto_increment,
  `idtrabajador` int(10) unsigned NOT NULL default '0',
  `idparentezco` int(10) unsigned NOT NULL default '0',
  `cedula` varchar(12) default NULL,
  `apellidos` varchar(45) NOT NULL default '',
  `nombres` varchar(45) NOT NULL default '',
  `fecha_nacimiento` date default NULL,
  `flag_constancia` varchar(1) default NULL,
  `usuario` varchar(20) NOT NULL default '',
  `status` varchar(1) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  `sexo` varchar(1) default NULL,
  `direccion` text,
  `telefono` varchar(25) default NULL,
  `ocupacion` varchar(200) NOT NULL,
  `idnacionalidad` int(10) unsigned default NULL,
  `estacion` varchar(45) default NULL,
  `foto` text NOT NULL,
  PRIMARY KEY  (`idcarga_familiar`,`idtrabajador`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `cargos`
-- 

CREATE TABLE `cargos` (
  `denominacion` varchar(65) NOT NULL default '',
  `grado` varchar(5) NOT NULL default '',
  `idcargo` varchar(5) NOT NULL default '',
  `idserie` varchar(5) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `status` varchar(1) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  `paso` varchar(2) NOT NULL default '',
  PRIMARY KEY  (`idcargo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `carrera`
-- 

CREATE TABLE `carrera` (
  `idcarrera` int(10) unsigned NOT NULL auto_increment,
  `idcalle` int(10) unsigned NOT NULL default '0',
  `denominacion` varchar(45) NOT NULL default '',
  `usuario` varchar(45) NOT NULL default '',
  `status` varchar(45) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idcarrera`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `categoria_programatica`
-- 

CREATE TABLE `categoria_programatica` (
  `idcategoria_programatica` int(10) unsigned NOT NULL auto_increment,
  `codigo` varchar(45) NOT NULL default '',
  `anio` varchar(4) NOT NULL,
  `idsector` int(10) unsigned NOT NULL default '0',
  `idprograma` int(10) unsigned NOT NULL default '0',
  `idsub_programa` int(10) unsigned NOT NULL default '0',
  `idproyecto` int(10) unsigned NOT NULL default '0',
  `idActividad` int(10) unsigned NOT NULL default '0',
  `idunidad_ejecutora` int(10) unsigned NOT NULL default '0',
  `transferencia` varchar(1) default NULL,
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) default NULL,
  `fechayhora` datetime default NULL,
  PRIMARY KEY  (`idcategoria_programatica`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `certificacion_simular_nomina`
-- 

CREATE TABLE `certificacion_simular_nomina` (
  `idcertificacion_simular_nomina` int(10) unsigned NOT NULL auto_increment,
  `numero_orden` varchar(20) NOT NULL,
  `fecha_orden` date NOT NULL,
  `tipo` int(10) unsigned NOT NULL default '0',
  `fecha_elaboracion` date NOT NULL,
  `proceso` varchar(20) NOT NULL,
  `numero_documento` varchar(12) default NULL,
  `idbeneficiarios` int(10) unsigned NOT NULL default '0',
  `idcategoria_programatica` int(10) unsigned NOT NULL default '0',
  `anio` varchar(4) NOT NULL default '0000',
  `idfuente_financiamiento` int(10) unsigned NOT NULL,
  `idtipo_presupuesto` int(10) unsigned NOT NULL,
  `idordinal` int(10) unsigned NOT NULL,
  `justificacion` text,
  `observaciones` longtext,
  `ordenado_por` varchar(40) default NULL,
  `cedula_ordenado` varchar(12) default NULL,
  `numero_requisicion` varchar(12) default NULL,
  `fecha_requisicion` date NOT NULL,
  `nro_items` int(10) default '0',
  `exento` decimal(16,2) NOT NULL default '0.00',
  `sub_total` decimal(16,2) default '0.00',
  `exento_original` decimal(16,2) NOT NULL,
  `sub_total_original` decimal(16,2) NOT NULL,
  `impuesto` decimal(16,2) default '0.00',
  `descuento` decimal(16,2) NOT NULL,
  `total` decimal(16,2) default '0.00',
  `estado` varchar(20) default NULL COMMENT 'En elaboración / Procesado / Anulado / Cancelado / Devuelto',
  `idrazones_devolucion` int(10) unsigned NOT NULL default '0',
  `observaciones_devolucion` longtext,
  `numero_remision` varchar(12) default NULL COMMENT 'Numero generado cuando se emita el memorandum de remisiones',
  `fecha_remision` date NOT NULL COMMENT 'fecha del memorandum de remision',
  `recibido_por` varchar(40) default NULL COMMENT 'Identificacion de quien recibe el memorandum de remision',
  `cedula_recibido` varchar(12) default NULL COMMENT 'Cedula de quien recibe el memroandum de remision',
  `fecha_recibido` date NOT NULL COMMENT 'Fecha de recibido el memorandum de remision',
  `ubicacion` varchar(20) default '0' COMMENT 'Presupuesto / Administracion / Contabilidad / Tesoreria / Bienes / Cancelado',
  `status` varchar(1) default NULL,
  `usuario` varchar(20) default NULL,
  `fechayhora` datetime default NULL,
  `duplicados` int(10) unsigned NOT NULL default '0',
  `nro_factura` varchar(45) NOT NULL,
  `fecha_factura` varchar(45) NOT NULL,
  `nro_control` varchar(45) NOT NULL,
  `codigo_referencia` varchar(12) NOT NULL,
  `tipo_carga_orden` varchar(45) NOT NULL,
  `contabilizado` varchar(45) NOT NULL default 'no',
  `idtipo_caja_chica` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`idcertificacion_simular_nomina`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `cheques_cuentas_bancarias`
-- 

CREATE TABLE `cheques_cuentas_bancarias` (
  `idcheques_cuentas_bancarias` int(10) unsigned NOT NULL auto_increment,
  `idcuentas_bancarias` int(10) unsigned NOT NULL default '0',
  `chequera_numero` varchar(10) NOT NULL COMMENT 'numero de chequera',
  `cantidad_cheques` int(10) unsigned NOT NULL default '0' COMMENT 'cantidad de cheques que tiene la chequera',
  `digitos_consecutivos` varchar(45) NOT NULL,
  `cantidad_digitos` int(11) NOT NULL,
  `numero_inicial` int(10) unsigned NOT NULL default '0' COMMENT 'numero de cheque inicial',
  `numero_final` int(10) unsigned NOT NULL default '0' COMMENT 'numero de cheque final',
  `fecha_inicio_uso` date NOT NULL COMMENT 'fecha en se comenzo a usar la chequera',
  `fecha_final_uso` date NOT NULL COMMENT 'fecha en que se emitio el ultimo cheque de la chequera',
  `estado` varchar(10) NOT NULL COMMENT 'SELECT activa / inactiva',
  `ultimo_cheque` int(10) NOT NULL,
  `usuario` varchar(20) NOT NULL default '',
  `status` varchar(1) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idcheques_cuentas_bancarias`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `ciudad`
-- 

CREATE TABLE `ciudad` (
  `idciudad` int(10) unsigned NOT NULL auto_increment,
  `idmunicipios` int(10) unsigned NOT NULL default '0',
  `denominacion` varchar(45) NOT NULL default '',
  `codigo_postal` varchar(4) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `status` varchar(1) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idciudad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `clasificador_presupuestario`
-- 

CREATE TABLE `clasificador_presupuestario` (
  `idclasificador_presupuestario` int(10) unsigned NOT NULL auto_increment,
  `denominacion` varchar(255) NOT NULL,
  `partida` varchar(3) NOT NULL default '',
  `generica` varchar(2) NOT NULL default '',
  `especifica` varchar(2) NOT NULL default '',
  `sub_especifica` varchar(2) NOT NULL default '',
  `codigo_cuenta` varchar(9) NOT NULL,
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) default NULL,
  `fechayhora` datetime default NULL,
  PRIMARY KEY  (`idclasificador_presupuestario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `cofinanciamiento`
-- 

CREATE TABLE `cofinanciamiento` (
  `idcofinanciamiento` int(10) unsigned NOT NULL auto_increment,
  `denominacion` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`idcofinanciamiento`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `comprobantes_retenciones`
-- 

CREATE TABLE `comprobantes_retenciones` (
  `idcomprobantes_retenciones` int(10) unsigned NOT NULL auto_increment,
  `idorden_pago` int(10) unsigned NOT NULL default '0',
  `idretenciones` int(10) unsigned NOT NULL default '0',
  `idtipo_retencion` int(10) unsigned NOT NULL default '0',
  `numero_retencion` varchar(45) NOT NULL default '',
  `fecha_retencion` date NOT NULL default '0000-00-00',
  `periodo` varchar(45) NOT NULL default '',
  `estado` varchar(45) NOT NULL default '',
  PRIMARY KEY  (`idcomprobantes_retenciones`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `conceptos_desagregados`
-- 

CREATE TABLE `conceptos_desagregados` (
  `idconceptos_desagregados` int(10) unsigned NOT NULL auto_increment,
  `idgenerar_nomina` int(10) unsigned NOT NULL default '0',
  `idconcepto` int(10) unsigned NOT NULL default '0',
  `idtrabajador` int(10) unsigned NOT NULL default '0',
  `valor` decimal(16,2) NOT NULL default '0.00',
  PRIMARY KEY  (`idconceptos_desagregados`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `conceptos_nomina`
-- 

CREATE TABLE `conceptos_nomina` (
  `idconceptos_nomina` int(10) unsigned NOT NULL auto_increment,
  `codigo` varchar(45) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `status` varchar(45) NOT NULL,
  `fechayhora` datetime NOT NULL,
  `usuario` varchar(45) NOT NULL,
  `idclasificador_presupuestario` int(10) unsigned NOT NULL,
  `idordinal` int(10) unsigned NOT NULL,
  `idarticulos_servicios` int(10) unsigned NOT NULL,
  `tipo_concepto` int(11) NOT NULL,
  `orden` int(10) unsigned NOT NULL default '0',
  `desagregar_concepto` varchar(2) NOT NULL default '',
  `factor_desagregacion` varchar(45) NOT NULL default '',
  `posicion` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`idconceptos_nomina`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `conceptos_simular_nomina`
-- 

CREATE TABLE `conceptos_simular_nomina` (
  `idconceptos_simular_nomina` int(10) unsigned NOT NULL auto_increment,
  `idcertificacion_simular_nomina` int(10) unsigned NOT NULL default '0',
  `idarticulos_servicios` int(10) unsigned NOT NULL default '0',
  `idcategoria_programatica` int(11) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL default '0.00',
  `precio_unitario` decimal(16,2) default NULL,
  `porcentaje_impuesto` decimal(6,2) default NULL,
  `impuesto` decimal(16,2) default NULL,
  `total` decimal(16,2) default NULL,
  `exento` decimal(16,2) NOT NULL default '0.00',
  `idsolicitud_cotizacion` int(10) default '0',
  `estado` varchar(20) default NULL,
  `duplicado` int(1) NOT NULL default '0' COMMENT 'CERO - no duplicado / UNO - duplicado',
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  `idpartida_impuesto` int(10) unsigned NOT NULL default '0' COMMENT '0: El impuesto se suma a la partida del articulo; OTRO:El impuesto tiene partida propia',
  `registrado` varchar(45) NOT NULL default 'no',
  `fecha_registro` datetime NOT NULL default '0000-00-00 00:00:00',
  `usuario_registro` varchar(45) NOT NULL default '',
  PRIMARY KEY  (`idconceptos_simular_nomina`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `conceptos_solicitud_calculo`
-- 

CREATE TABLE `conceptos_solicitud_calculo` (
  `idconceptos_solicitud_calculo` int(10) unsigned NOT NULL auto_increment,
  `idsolicitud_calculo` int(10) unsigned NOT NULL default '0',
  `idconcepto` int(10) unsigned NOT NULL default '0',
  `tipo_cobro` varchar(45) NOT NULL default '',
  `tipo_calculo` varchar(45) NOT NULL default '',
  `total_pagar` decimal(16,2) NOT NULL default '0.00',
  `valor` decimal(16,2) NOT NULL default '0.00',
  `fraccion_pago` varchar(45) NOT NULL default '',
  PRIMARY KEY  (`idconceptos_solicitud_calculo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `concepto_tributario`
-- 

CREATE TABLE `concepto_tributario` (
  `idconcepto_tributario` int(10) unsigned NOT NULL auto_increment,
  `codigo` varchar(45) NOT NULL default '',
  `anio` int(10) unsigned NOT NULL default '0',
  `denominacion` varchar(100) NOT NULL default '',
  `tipo_base_aforo` varchar(45) NOT NULL default '',
  `tipo_calculo_aforo` varchar(45) NOT NULL default '',
  `valor_aforo` decimal(16,2) NOT NULL default '0.00',
  `divisor_aforo` decimal(16,2) NOT NULL default '0.00',
  `tipo_base_minimo` varchar(45) NOT NULL default '',
  `tipo_calculo_minimo` varchar(45) NOT NULL default '',
  `valor_minimo` decimal(16,2) NOT NULL default '0.00',
  `divisor_minimo` decimal(16,2) NOT NULL default '0.00',
  `idclasificador_presupuestario` int(10) unsigned NOT NULL default '0',
  `status` varchar(45) NOT NULL default '',
  `usuario` varchar(45) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  `idtabla_constantes_recaudacion` int(10) unsigned NOT NULL default '0',
  `monto_variable` decimal(16,2) NOT NULL default '0.00',
  PRIMARY KEY  (`idconcepto_tributario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `configuracion`
-- 

CREATE TABLE `configuracion` (
  `idconfiguracion` int(10) unsigned NOT NULL auto_increment,
  `nombre_institucion` varchar(200) NOT NULL default '',
  `rif` varchar(12) NOT NULL,
  `domicilio_legal` longtext,
  `ciudad` varchar(45) default NULL,
  `estado` varchar(45) default NULL,
  `telefonos` varchar(45) default NULL,
  `pagina_web` varchar(45) default NULL,
  `fax` varchar(45) default NULL,
  `correo_electronico` varchar(45) default NULL,
  `codigo_postal` varchar(4) default NULL,
  `ordena_requisicion` varchar(60) NOT NULL,
  `ordena_compras` varchar(60) NOT NULL,
  `ordena_nomina` varchar(60) NOT NULL,
  `ordena_administracion` varchar(60) NOT NULL,
  `ordena_presupuesto` varchar(60) NOT NULL,
  `ordena_certificacion_administracion` varchar(60) NOT NULL,
  `ordena_despacho` varchar(45) NOT NULL,
  `ordena_rrhh` varchar(45) NOT NULL,
  `ci_ordena_requisicion` varchar(30) NOT NULL,
  `ci_ordena_compras` varchar(30) NOT NULL,
  `ci_ordena_nomina` varchar(30) NOT NULL,
  `ci_ordena_administracion` varchar(30) NOT NULL,
  `ci_ordena_presupuesto` varchar(30) NOT NULL,
  `ci_ordena_certificacion_administracion` varchar(30) NOT NULL,
  `ci_ordena_despacho` varchar(45) NOT NULL,
  `ci_ordena_rrhh` varchar(45) NOT NULL,
  `gobernador` varchar(65) default NULL,
  `ci_gobernador` varchar(12) NOT NULL,
  `contralor` varchar(65) default NULL,
  `ci_contralor` varchar(12) NOT NULL,
  `presidente_consejo_legislativo` varchar(65) default NULL,
  `ci_presidente_consejo_legislativo` varchar(12) NOT NULL,
  `director_presupuesto` varchar(155) default NULL,
  `ci_director_presupuesto` varchar(12) NOT NULL,
  `anio_fiscal` varchar(4) default NULL,
  `idtipo_presupuesto` int(10) unsigned default NULL,
  `idfuente_financiamiento` int(10) unsigned default NULL,
  `ruta_reportes` varchar(45) NOT NULL,
  `ordenpago_cheque` varchar(1) NOT NULL default '0',
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  `fecha_cierre` date NOT NULL default '0000-00-00',
  `idcategoria_programatica` int(10) unsigned NOT NULL default '0',
  `version` varchar(45) NOT NULL,
  `disponibilidad` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`idconfiguracion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `configuracion_administracion`
-- 

CREATE TABLE `configuracion_administracion` (
  `idconfiguracion_administracion` int(10) unsigned NOT NULL auto_increment,
  `iddependencia` int(10) NOT NULL,
  `primero_administracion` varchar(100) NOT NULL default '',
  `ci_primero_administracion` varchar(12) NOT NULL default '',
  `cargo_primero_administracion` varchar(100) NOT NULL default '',
  `segundo_administracion` varchar(100) NOT NULL default '',
  `ci_segundo_administracion` varchar(12) NOT NULL default '',
  `cargo_segundo_administracion` varchar(100) NOT NULL default '',
  `tercero_administracion` varchar(100) NOT NULL default '',
  `ci_tercero_administracion` varchar(12) NOT NULL default '',
  `cargo_tercero_administracion` varchar(100) NOT NULL default '',
  `nro_remision` int(10) NOT NULL,
  `nro_solicitud_cotizacion` int(10) NOT NULL,
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idconfiguracion_administracion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `configuracion_bienes`
-- 

CREATE TABLE `configuracion_bienes` (
  `idconfiguracion_bienes` int(10) unsigned NOT NULL auto_increment,
  `nro_movimiento_lote` int(10) unsigned NOT NULL default '0',
  `nro_desincorporacion` int(10) unsigned NOT NULL default '0',
  `nro_movimiento` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`idconfiguracion_bienes`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `configuracion_caja_chica`
-- 

CREATE TABLE `configuracion_caja_chica` (
  `idconfiguracion_caja_chica` int(10) unsigned NOT NULL auto_increment,
  `iddependencia` int(10) NOT NULL,
  `primero_caja_chica` varchar(100) NOT NULL default '',
  `ci_primero_caja_chica` varchar(12) NOT NULL default '',
  `cargo_primero_caja_chica` varchar(100) NOT NULL default '',
  `segundo_caja_chica` varchar(100) NOT NULL default '',
  `ci_segundo_caja_chica` varchar(12) NOT NULL default '',
  `cargo_segundo_caja_chica` varchar(100) NOT NULL default '',
  `tercero_caja_chica` varchar(100) NOT NULL default '',
  `ci_tercero_caja_chica` varchar(12) NOT NULL default '',
  `cargo_tercero_caja_chica` varchar(100) NOT NULL default '',
  `nro_remision` int(10) NOT NULL,
  `nro_solicitud_cotizacion` int(10) NOT NULL,
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  `costo_ut` decimal(16,2) NOT NULL,
  PRIMARY KEY  (`idconfiguracion_caja_chica`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `configuracion_cheques`
-- 

CREATE TABLE `configuracion_cheques` (
  `idconfiguracion_cheques` int(10) unsigned NOT NULL auto_increment,
  `idbanco` int(10) unsigned NOT NULL default '0',
  `alto_monto_numeros` decimal(16,2) NOT NULL default '0.00',
  `izquierda_monto_numeros` decimal(16,2) NOT NULL default '0.00',
  `alto_beneficiario` decimal(16,2) NOT NULL default '0.00',
  `izquierda_beneficiario` decimal(16,2) NOT NULL default '0.00',
  `alto_monto_letras` decimal(16,2) NOT NULL default '0.00',
  `izquierda_monto_letras` decimal(16,2) NOT NULL default '0.00',
  `alto_fecha` decimal(16,2) NOT NULL default '0.00',
  `izquierda_fecha` decimal(16,2) NOT NULL default '0.00',
  `alto_ano` decimal(16,2) NOT NULL default '0.00',
  `izquierda_ano` decimal(16,2) NOT NULL default '0.00',
  PRIMARY KEY  (`idconfiguracion_cheques`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `configuracion_compras`
-- 

CREATE TABLE `configuracion_compras` (
  `idconfiguracion_compras` int(10) unsigned NOT NULL auto_increment,
  `iddependencia` int(10) NOT NULL,
  `primero_compras` varchar(100) NOT NULL default '',
  `ci_primero_compras` varchar(12) NOT NULL default '',
  `cargo_primero_compras` varchar(100) NOT NULL,
  `segundo_compras` varchar(100) NOT NULL default '',
  `ci_segundo_compras` varchar(12) NOT NULL default '',
  `cargo_segundo_compras` varchar(100) NOT NULL default '',
  `tercero_compras` varchar(100) NOT NULL default '',
  `ci_tercero_compras` varchar(12) NOT NULL default '',
  `cargo_tercero_compras` varchar(100) NOT NULL default '',
  `nro_solicitud_cotizacion` int(10) NOT NULL,
  `nro_remision` int(10) NOT NULL,
  `nro_requisicion` int(11) NOT NULL,
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  `organo_responsable` text NOT NULL,
  `organo_ejecutor` text NOT NULL,
  `rif_ejecutor` varchar(45) NOT NULL default '',
  `funcionario_responsable` varchar(45) NOT NULL default '',
  `funcionario_contacto` varchar(45) NOT NULL default '',
  `telefono` varchar(45) NOT NULL default '',
  `email` varchar(45) NOT NULL default '',
  PRIMARY KEY  (`idconfiguracion_compras`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `configuracion_contabilidad`
-- 

CREATE TABLE `configuracion_contabilidad` (
  `idconfiguracion_contabilidad` int(10) unsigned NOT NULL auto_increment,
  `iddependencia` int(10) NOT NULL,
  `primero_contabilidad` varchar(100) NOT NULL default '',
  `ci_primero_contabilidad` varchar(12) NOT NULL default '',
  `cargo_primero_contabilidad` varchar(100) NOT NULL default '',
  `segundo_contabilidad` varchar(100) NOT NULL default '',
  `ci_segundo_contabilidad` varchar(12) NOT NULL default '',
  `cargo_segundo_contabilidad` varchar(100) NOT NULL default '',
  `tercero_contabilidad` varchar(100) NOT NULL default '',
  `ci_tercero_contabilidad` varchar(12) NOT NULL default '',
  `cargo_tercero_contabilidad` varchar(100) NOT NULL default '',
  `nro_remision` int(10) NOT NULL,
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idconfiguracion_contabilidad`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `configuracion_despacho`
-- 

CREATE TABLE `configuracion_despacho` (
  `idconfiguracion_despacho` int(10) unsigned NOT NULL auto_increment,
  `iddependencia` int(10) NOT NULL,
  `primero_despacho` varchar(100) NOT NULL default '',
  `ci_primero_despacho` varchar(12) NOT NULL default '',
  `cargo_primero_despacho` varchar(100) NOT NULL default '',
  `segundo_despacho` varchar(100) NOT NULL default '',
  `ci_segundo_despacho` varchar(12) NOT NULL default '',
  `cargo_segundo_despacho` varchar(100) NOT NULL default '',
  `tercero_despacho` varchar(100) NOT NULL default '',
  `ci_tercero_despacho` varchar(12) NOT NULL default '',
  `cargo_tercero_despacho` varchar(100) NOT NULL default '',
  `nro_remision` int(10) NOT NULL,
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idconfiguracion_despacho`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `configuracion_logos`
-- 

CREATE TABLE `configuracion_logos` (
  `idconfiguracion_logos` int(10) unsigned NOT NULL auto_increment,
  `logo` varchar(100) NOT NULL default '',
  `segundo_logo` varchar(100) NOT NULL default '',
  `alto_primero` int(10) unsigned NOT NULL default '0',
  `ancho_primero` int(10) unsigned NOT NULL default '0',
  `alto_segundo` int(10) unsigned NOT NULL default '0',
  `ancho_segundo` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`idconfiguracion_logos`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `configuracion_nomina`
-- 

CREATE TABLE `configuracion_nomina` (
  `idconfiguracion_nomina` int(10) unsigned NOT NULL auto_increment,
  `iddependencia` int(10) NOT NULL,
  `primero_nomina` varchar(100) NOT NULL default '',
  `ci_primero_nomina` varchar(12) NOT NULL default '',
  `cargo_primero_nomina` varchar(100) NOT NULL default '',
  `segundo_nomina` varchar(100) NOT NULL default '',
  `ci_segundo_nomina` varchar(12) NOT NULL default '',
  `cargo_segundo_nomina` varchar(100) NOT NULL default '',
  `tercero_nomina` varchar(100) NOT NULL default '',
  `ci_tercero_nomina` varchar(12) NOT NULL default '',
  `cargo_tercero_nomina` varchar(100) NOT NULL default '',
  `nro_remision` int(10) NOT NULL,
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idconfiguracion_nomina`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `configuracion_presupuesto`
-- 

CREATE TABLE `configuracion_presupuesto` (
  `idconfiguracion_presupuesto` int(10) unsigned NOT NULL auto_increment,
  `iddependencia` int(10) NOT NULL,
  `primero_presupuesto` varchar(100) NOT NULL default '',
  `ci_primero_presupuesto` varchar(12) NOT NULL default '',
  `cargo_primero_presupuesto` varchar(100) NOT NULL default '',
  `segundo_presupuesto` varchar(100) NOT NULL default '',
  `ci_segundo_presupuesto` varchar(12) NOT NULL default '',
  `cargo_segundo_presupuesto` varchar(100) NOT NULL default '',
  `tercero_presupuesto` varchar(100) NOT NULL default '',
  `ci_tercero_presupuesto` varchar(12) NOT NULL default '',
  `cargo_tercero_presupuesto` varchar(100) NOT NULL default '',
  `nro_remision` int(10) NOT NULL,
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idconfiguracion_presupuesto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `configuracion_recaudacion`
-- 

CREATE TABLE `configuracion_recaudacion` (
  `idconfiguracion_recaudacion` int(10) unsigned NOT NULL auto_increment,
  `primero_recaudacion` varchar(45) NOT NULL default '',
  `ci_primero_recaudacion` varchar(45) NOT NULL default '',
  `cargo_primero_recaudacion` varchar(45) NOT NULL default '',
  `segundo_recaudacion` varchar(45) NOT NULL default '',
  `ci_segundo_recaudacion` varchar(45) NOT NULL default '',
  `cargo_segundo_recaudacion` varchar(45) NOT NULL default '',
  `tercero_recaudacion` varchar(45) NOT NULL default '',
  `ci_tercero_recaudacion` varchar(45) NOT NULL default '',
  `cargo_tercero_recaudacion` varchar(45) NOT NULL default '',
  `iddependencia` int(10) unsigned NOT NULL default '0',
  `idestado` int(10) unsigned NOT NULL default '0',
  `idmunicipio` int(10) unsigned NOT NULL default '0',
  `costo_unidad_tributaria` decimal(16,2) NOT NULL default '0.00',
  `numero_solicitud_calculo` int(10) unsigned NOT NULL default '0',
  `numero_recaudacion` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`idconfiguracion_recaudacion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `configuracion_reportes`
-- 

CREATE TABLE `configuracion_reportes` (
  `idconfiguracion_reportes` int(10) unsigned NOT NULL auto_increment,
  `idtipo_documento` int(10) unsigned NOT NULL default '0',
  `idtipo_reporte` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`idconfiguracion_reportes`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `configuracion_rrhh`
-- 

CREATE TABLE `configuracion_rrhh` (
  `idconfiguracion_rrhh` int(10) unsigned NOT NULL auto_increment,
  `iddependencia` int(10) NOT NULL,
  `primero_rrhh` varchar(100) NOT NULL default '',
  `ci_primero_rrhh` varchar(12) NOT NULL default '',
  `cargo_primero_rrhh` varchar(100) NOT NULL default '',
  `segundo_rrhh` varchar(100) NOT NULL default '',
  `ci_segundo_rrhh` varchar(12) NOT NULL default '',
  `cargo_segundo_rrhh` varchar(100) NOT NULL default '',
  `tercero_rrhh` varchar(100) NOT NULL default '',
  `ci_tercero_rrhh` varchar(12) NOT NULL default '',
  `cargo_tercero_rrhh` varchar(100) NOT NULL default '',
  `nro_remision` int(10) NOT NULL,
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  `meses_inicio_pago_prestaciones` int(10) unsigned NOT NULL,
  `dias_prestaciones_mes` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`idconfiguracion_rrhh`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `configuracion_secretaria`
-- 

CREATE TABLE `configuracion_secretaria` (
  `idconfiguracion_secretaria` int(10) unsigned NOT NULL auto_increment,
  `iddependencia` int(10) NOT NULL,
  `primero_secretaria` varchar(100) NOT NULL default '',
  `ci_primero_secretaria` varchar(12) NOT NULL default '',
  `cargo_primero_secretaria` varchar(100) NOT NULL default '',
  `segundo_secretaria` varchar(100) NOT NULL default '',
  `ci_segundo_secretaria` varchar(12) NOT NULL default '',
  `cargo_segundo_secretaria` varchar(100) NOT NULL default '',
  `tercero_secretaria` varchar(100) NOT NULL default '',
  `ci_tercero_secretaria` varchar(12) NOT NULL default '',
  `cargo_tercero_secretaria` varchar(100) NOT NULL default '',
  `nro_remision` int(10) NOT NULL,
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idconfiguracion_secretaria`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `configuracion_tesoreria`
-- 

CREATE TABLE `configuracion_tesoreria` (
  `idconfiguracion_tesoreria` int(10) unsigned NOT NULL auto_increment,
  `iddependencia` int(10) NOT NULL,
  `primero_tesoreria` varchar(100) NOT NULL default '',
  `ci_primero_tesoreria` varchar(12) NOT NULL default '',
  `cargo_primero_tesoreria` varchar(100) NOT NULL default '',
  `segundo_tesoreria` varchar(100) NOT NULL default '',
  `ci_segundo_tesoreria` varchar(12) NOT NULL default '',
  `cargo_segundo_tesoreria` varchar(100) NOT NULL default '',
  `tercero_tesoreria` varchar(100) NOT NULL default '',
  `ci_tercero_tesoreria` varchar(12) NOT NULL default '',
  `cargo_tercero_tesoreria` varchar(100) NOT NULL default '',
  `nro_remision` int(10) NOT NULL,
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idconfiguracion_tesoreria`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `configuracion_tributos`
-- 

CREATE TABLE `configuracion_tributos` (
  `idconfiguracion_tributos` int(10) unsigned NOT NULL auto_increment,
  `iddependencia` int(10) NOT NULL,
  `primero_tributos` varchar(100) NOT NULL default '',
  `ci_primero_tributos` varchar(12) NOT NULL default '',
  `cargo_primero_tributos` varchar(100) NOT NULL default '',
  `segundo_tributos` varchar(100) NOT NULL default '',
  `ci_segundo_tributos` varchar(12) NOT NULL default '',
  `cargo_segundo_tributos` varchar(100) NOT NULL default '',
  `tercero_tributos` varchar(100) NOT NULL default '',
  `ci_tercero_tributos` varchar(12) NOT NULL default '',
  `cargo_tercero_tributos` varchar(100) NOT NULL default '',
  `nro_remision` int(10) NOT NULL,
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  `nro_retencion_externa` int(10) unsigned NOT NULL default '0',
  `nro_linea_comprobante` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`idconfiguracion_tributos`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `conformar_documentos`
-- 

CREATE TABLE `conformar_documentos` (
  `idconformar_documentos` int(10) unsigned NOT NULL auto_increment,
  `iddocumento` int(10) unsigned NOT NULL default '0',
  `idrecibido` int(10) NOT NULL,
  `tipo` varchar(45) NOT NULL,
  `estado` varchar(45) NOT NULL default '',
  `idrazones_devolucion` int(10) unsigned NOT NULL default '0',
  `conformado_por` varchar(45) NOT NULL default '',
  `ci_conformador` varchar(12) NOT NULL default '',
  `fecha_conformado` date default NULL,
  `observaciones` longtext,
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idconformar_documentos`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `constantes_nomina`
-- 

CREATE TABLE `constantes_nomina` (
  `idconstantes_nomina` int(10) unsigned NOT NULL auto_increment,
  `codigo` varchar(45) NOT NULL default '',
  `descripcion` varchar(200) NOT NULL default '',
  `abreviatura` varchar(45) NOT NULL default '',
  `unidad` varchar(45) NOT NULL default '',
  `equivalencia` varchar(45) NOT NULL default '',
  `maximo` varchar(45) NOT NULL default '',
  `valor` varchar(45) character set utf8 NOT NULL default '0',
  `tipo` varchar(45) NOT NULL default '',
  `idclasificador_presupuestario` int(10) unsigned NOT NULL,
  `idordinal` int(10) unsigned NOT NULL,
  `idarticulos_servicios` int(10) unsigned NOT NULL,
  `mostrar` varchar(45) NOT NULL,
  `afecta` varchar(45) NOT NULL,
  `orden` int(10) unsigned NOT NULL default '0',
  `posicion` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`idconstantes_nomina`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `contribuyente`
-- 

CREATE TABLE `contribuyente` (
  `idcontribuyente` int(10) unsigned NOT NULL auto_increment,
  `razon_social` varchar(45) NOT NULL default '',
  `rif` varchar(45) NOT NULL default '',
  `telefono` varchar(45) NOT NULL default '',
  `email` varchar(45) NOT NULL default '',
  `estado` int(10) unsigned NOT NULL default '0',
  `municipio` int(10) unsigned NOT NULL default '0',
  `parroquia` int(10) unsigned NOT NULL default '0',
  `sectores` int(10) unsigned NOT NULL default '0',
  `urbanizacion` int(10) unsigned NOT NULL default '0',
  `calle` int(10) unsigned NOT NULL default '0',
  `carrera` int(10) unsigned NOT NULL default '0',
  `nro_casa` varchar(45) NOT NULL default '',
  `punto_referencia` varchar(45) NOT NULL default '',
  `tipo_persona` int(10) unsigned NOT NULL default '0',
  `tipo_empresa` int(10) unsigned NOT NULL default '0',
  `tipo_sociedad` int(10) unsigned NOT NULL default '0',
  `objeto` text NOT NULL,
  `libro` varchar(45) NOT NULL default '',
  `folio` varchar(45) NOT NULL default '',
  `capital_social` decimal(16,2) NOT NULL default '0.00',
  `capital_suscrito` decimal(16,2) NOT NULL default '0.00',
  `status` varchar(45) NOT NULL default '',
  `usuario` varchar(45) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idcontribuyente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `conversaciones_chat`
-- 

CREATE TABLE `conversaciones_chat` (
  `idconversaciones_chat` int(10) unsigned NOT NULL auto_increment,
  `quien_escribe` varchar(45) NOT NULL default '0',
  `usuario_apertura` varchar(45) NOT NULL default '',
  `usuario_recepcion` varchar(45) NOT NULL default '',
  `mensaje` text NOT NULL,
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  `pc_apertura` varchar(45) NOT NULL default '',
  `mensaje_nuevo` varchar(45) NOT NULL default '',
  PRIMARY KEY  (`idconversaciones_chat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `creditos_adicionales`
-- 

CREATE TABLE `creditos_adicionales` (
  `idcreditos_adicionales` int(10) unsigned NOT NULL auto_increment,
  `nro_solicitud` varchar(12) default NULL,
  `fecha_solicitud` date default NULL,
  `nro_resolucion` varchar(25) default NULL,
  `fecha_resolucion` date default NULL,
  `fecha_ingreso` date default NULL,
  `justificacion` text,
  `anio` varchar(4) default NULL,
  `idfuente_financiamiento` int(10) unsigned NOT NULL default '0',
  `total_credito` decimal(16,2) NOT NULL default '0.00',
  `estado` varchar(20) default NULL,
  `ubicacion` varchar(45) default '0',
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idcreditos_adicionales`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `cuentas_bancarias`
-- 

CREATE TABLE `cuentas_bancarias` (
  `idcuentas_bancarias` int(10) unsigned NOT NULL auto_increment,
  `numero_cuenta` varchar(20) NOT NULL,
  `idbanco` int(10) unsigned NOT NULL default '0',
  `idtipo_cuenta` int(10) unsigned NOT NULL default '0',
  `validez_documento` varchar(10) NOT NULL COMMENT 'Cuantos dias para caducar el documento',
  `fecha_inicio_periodo` date NOT NULL,
  `fecha_final_periodo` date NOT NULL,
  `fecha_apertura` date NOT NULL,
  `monto_apertura` decimal(16,2) default '0.00',
  `saldo_disponible` decimal(16,2) NOT NULL,
  `uso_cuenta` varchar(30) NOT NULL default '',
  `saldo` decimal(16,2) default '0.00',
  `estado` varchar(10) NOT NULL default '',
  `firma_autorizada1` varchar(50) default NULL COMMENT 'quien es la primera firma en esa cuenta',
  `ci_firma_autorizada1` varchar(12) default '' COMMENT 'cedula de identidad de la primera firma',
  `cargo_firma_autorizada1` varchar(100) default '' COMMENT 'cargo de la persona que es la primera firma',
  `firma_autorizada2` varchar(50) default NULL COMMENT 'quien es la segunda firma en esa cuenta',
  `ci_firma_autorizada2` varchar(12) default '' COMMENT 'cedula de identidad de la segunda firma',
  `cargo_firma_autorizada2` varchar(100) default '' COMMENT 'cargo de la persona que es la segunda firma',
  `firma_autorizada3` varchar(12) default '' COMMENT 'Tercera firma autorizada',
  `ci_firma_autorizada3` varchar(12) default '' COMMENT 'cedula de identidad de la tercera firma',
  `cargo_firma_autorizada3` varchar(100) default '' COMMENT 'cargo de la persona que es la tercera firma',
  `conjuntas` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `status` varchar(1) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idcuentas_bancarias`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `cuentas_bancarias_trabajador`
-- 

CREATE TABLE `cuentas_bancarias_trabajador` (
  `idcuentas_bancarias_trabajador` int(10) unsigned NOT NULL auto_increment,
  `idtrabajador` int(10) unsigned NOT NULL,
  `nro_cuenta` varchar(20) NOT NULL,
  `tipo` varchar(45) NOT NULL,
  `motivo` varchar(45) NOT NULL,
  `banco` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`idcuentas_bancarias_trabajador`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `cuentas_t`
-- 

CREATE TABLE `cuentas_t` (
  `idcuentas_t` int(10) unsigned NOT NULL auto_increment,
  `idorden_pago` int(10) unsigned NOT NULL default '0',
  `estado` varchar(45) NOT NULL default '',
  `status` varchar(45) NOT NULL default '',
  `usuario` varchar(45) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  `tipo_asiento` varchar(45) NOT NULL default '',
  `fecha_contabilizacion` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`idcuentas_t`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `cuentas_t_seleccionadas`
-- 

CREATE TABLE `cuentas_t_seleccionadas` (
  `idcuentas_t_seleccionadas` int(10) unsigned NOT NULL auto_increment,
  `idcuenta` int(10) unsigned NOT NULL default '0',
  `nivel` varchar(45) NOT NULL default '',
  `monto` decimal(16,2) NOT NULL default '0.00',
  `tipo` varchar(45) NOT NULL default '',
  `idcuenta_t` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`idcuentas_t_seleccionadas`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `cuenta_cuentas_contables`
-- 

CREATE TABLE `cuenta_cuentas_contables` (
  `idcuenta_cuentas_contables` int(10) unsigned NOT NULL auto_increment,
  `idrubro` int(11) NOT NULL default '0',
  `codigo` varchar(45) NOT NULL default '',
  `denominacion` varchar(45) NOT NULL default '',
  PRIMARY KEY  (`idcuenta_cuentas_contables`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `cursos`
-- 

CREATE TABLE `cursos` (
  `secuencia` int(10) unsigned NOT NULL auto_increment,
  `idtrabajador` int(10) unsigned NOT NULL default '0',
  `nombre_curso` varchar(150) NOT NULL default '',
  `institucion` varchar(150) NOT NULL default '',
  `anio` varchar(4) NOT NULL default '',
  `flag_constancia` varchar(1) NOT NULL default '',
  `observaciones` varchar(200) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `status` varchar(1) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`secuencia`,`idtrabajador`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `cursos_otros_estudios`
-- 

CREATE TABLE `cursos_otros_estudios` (
  `idcurso` int(10) NOT NULL auto_increment,
  `idtrabajador` int(10) NOT NULL,
  `denominacion` varchar(250) NOT NULL,
  `detalle_contenido` text NOT NULL,
  `duracion` varchar(10) NOT NULL,
  `dias_horas` varchar(45) NOT NULL default '',
  `desde` date NOT NULL,
  `hasta` date NOT NULL,
  `institucion` varchar(250) NOT NULL,
  `telefonos` varchar(45) NOT NULL,
  `realizado_por` varchar(45) NOT NULL default '',
  `constancia_anexa` varchar(2) NOT NULL,
  `nombre_imagen` varchar(200) NOT NULL,
  `usuario` varchar(20) NOT NULL,
  `fechayhora` datetime NOT NULL,
  PRIMARY KEY  (`idcurso`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `declaracion_jurada`
-- 

CREATE TABLE `declaracion_jurada` (
  `iddeclaracion` int(10) NOT NULL auto_increment,
  `idtrabajador` int(10) NOT NULL,
  `tipo` varchar(45) NOT NULL COMMENT 'select con tres opciones: Ingreso, Actualización y Egreso',
  `fecha_declaracion` date NOT NULL,
  `fecha_entrega` date NOT NULL,
  `constancia_anexa` varchar(2) NOT NULL,
  `nombre_imagen` varchar(200) NOT NULL,
  `observaciones` varchar(250) NOT NULL,
  `usuario` varchar(20) NOT NULL,
  `fechayhora` datetime NOT NULL,
  PRIMARY KEY  (`iddeclaracion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `dependencias`
-- 

CREATE TABLE `dependencias` (
  `iddependencia` int(10) unsigned NOT NULL auto_increment,
  `denominacion` varchar(80) NOT NULL default '',
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  `siglas` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`iddependencia`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `desagregacion_cuentas_contables`
-- 

CREATE TABLE `desagregacion_cuentas_contables` (
  `iddesagregacion_cuentas_contables` int(10) unsigned NOT NULL auto_increment,
  `idsubcuenta_segundo` int(11) NOT NULL default '0',
  `codigo` varchar(45) NOT NULL default '',
  `denominacion` varchar(45) NOT NULL default '',
  PRIMARY KEY  (`iddesagregacion_cuentas_contables`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `desincorporacion_bienes`
-- 

CREATE TABLE `desincorporacion_bienes` (
  `iddesincorporacion_bienes` int(10) unsigned NOT NULL auto_increment,
  `numero_planilla` varchar(45) NOT NULL default '',
  `fecha_proceso` date NOT NULL default '0000-00-00',
  `justificacion` varchar(200) NOT NULL default '',
  `estado` varchar(45) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  `usuario` varchar(45) NOT NULL default '',
  PRIMARY KEY  (`iddesincorporacion_bienes`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `detalle_catalogo_bienes`
-- 

CREATE TABLE `detalle_catalogo_bienes` (
  `iddetalle_catalogo_bienes` int(10) unsigned NOT NULL auto_increment,
  `idsecciones_catalogo_bienes` int(10) NOT NULL,
  `codigo` varchar(10) NOT NULL default '',
  `denominacion` varchar(80) NOT NULL default '',
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`iddetalle_catalogo_bienes`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `dias_feriados`
-- 

CREATE TABLE `dias_feriados` (
  `iddia_feriado` int(10) unsigned NOT NULL auto_increment,
  `nombre` varchar(45) NOT NULL,
  `dia` int(10) unsigned NOT NULL,
  `mes` int(10) unsigned NOT NULL,
  `caracter` varchar(45) NOT NULL,
  `condicion` varchar(45) NOT NULL,
  PRIMARY KEY  (`iddia_feriado`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `disminucion_presupuesto`
-- 

CREATE TABLE `disminucion_presupuesto` (
  `iddisminucion_presupuesto` int(10) unsigned NOT NULL auto_increment,
  `nro_solicitud` varchar(12) default NULL,
  `fecha_solicitud` date default NULL,
  `nro_resolucion` varchar(25) default NULL,
  `fecha_resolucion` date default NULL,
  `fecha_ingreso` date default NULL,
  `justificacion` text,
  `anio` varchar(4) default NULL,
  `idfuente_financiamiento` int(10) unsigned NOT NULL default '0',
  `total_disminucion` decimal(16,2) NOT NULL default '0.00',
  `estado` varchar(20) default NULL,
  `ubicacion` varchar(45) default '0',
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`iddisminucion_presupuesto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `documentos_requeridos`
-- 

CREATE TABLE `documentos_requeridos` (
  `iddocumentos_requeridos` int(10) unsigned NOT NULL auto_increment,
  `descripcion` varchar(300) NOT NULL default '',
  `status` varchar(1) default NULL,
  `usuario` varchar(20) default NULL,
  `fechayhora` datetime default NULL,
  PRIMARY KEY  (`iddocumentos_requeridos`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `documento_entregado_beneficiario`
-- 

CREATE TABLE `documento_entregado_beneficiario` (
  `iddocumento_consignado_beneficiario` int(10) unsigned NOT NULL auto_increment,
  `idbeneficiarios` int(10) unsigned NOT NULL default '0',
  `iddocumentos_requeridos` int(10) unsigned NOT NULL default '0',
  `nro_comprobante` varchar(20) NOT NULL,
  `fecha_emision` date default NULL,
  `fecha_vencimiento` date default NULL,
  `verificador_por` varchar(60) default NULL,
  `status` varchar(1) default NULL,
  `usuario` varchar(20) default NULL,
  `fechayhora` datetime default NULL,
  PRIMARY KEY  (`iddocumento_consignado_beneficiario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `edificios`
-- 

CREATE TABLE `edificios` (
  `idedificios` int(11) unsigned NOT NULL auto_increment,
  `idtipo_movimiento` int(11) NOT NULL,
  `iddetalle_catalogo_bienes` int(11) NOT NULL,
  `estado_municipio_propietario` varchar(100) NOT NULL,
  `denominacion_inmueble` text NOT NULL,
  `clasificacion_funcional_inmueble` text NOT NULL,
  `ubicacion_geografica_estado` varchar(60) NOT NULL,
  `ubicacion_geografica_municipio` varchar(60) NOT NULL,
  `ubicacion_geografica_direccion` text NOT NULL,
  `area_terreno` text NOT NULL,
  `area_construccion` text NOT NULL,
  `numero_pisos` int(11) NOT NULL,
  `area_total_construccion` text NOT NULL,
  `area_anexidades` text NOT NULL,
  `tipo_estructura` text NOT NULL,
  `pisos` text NOT NULL,
  `paredes` text NOT NULL,
  `techos` text NOT NULL,
  `puertas_ventanas` text NOT NULL,
  `servicios` text NOT NULL,
  `otras_anexidades` text NOT NULL,
  `linderos` text NOT NULL,
  `estado_legal` text NOT NULL,
  `valor_contabilidad_fecha` date NOT NULL,
  `valor_contabilidad_monto` decimal(16,2) NOT NULL,
  `mejoras_fecha` date NOT NULL,
  `mejoras_valor` decimal(16,2) NOT NULL,
  `mejoras_fecha2` date NOT NULL,
  `mejoras_valor2` decimal(16,2) NOT NULL,
  `mejoras_fecha3` date NOT NULL,
  `mejoras_valor3` decimal(16,2) NOT NULL,
  `mejoras_fecha4` date NOT NULL,
  `mejoras_valor4` decimal(16,2) NOT NULL,
  `mejoras_fecha5` date NOT NULL,
  `mejoras_valor5` decimal(16,2) NOT NULL,
  `avaluo_provicional` text NOT NULL,
  `planos_esquemas_fotocopias` text NOT NULL,
  `preparado_por` varchar(100) NOT NULL,
  `lugar` varchar(100) NOT NULL,
  `cargo` varchar(100) NOT NULL,
  `fecha` date NOT NULL,
  `status` varchar(1) NOT NULL,
  `usuario` varchar(45) NOT NULL,
  `fechayhora` datetime NOT NULL,
  `organizacion` int(10) unsigned NOT NULL default '0',
  `codigo_bien` varchar(45) NOT NULL default '',
  PRIMARY KEY  (`idedificios`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `edo_civil`
-- 

CREATE TABLE `edo_civil` (
  `idedo_civil` int(10) unsigned NOT NULL auto_increment,
  `denominacion` varchar(20) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `status` varchar(1) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idedo_civil`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `entes_gubernamentales`
-- 

CREATE TABLE `entes_gubernamentales` (
  `identes_gubernamentales` int(10) unsigned NOT NULL auto_increment,
  `nombre` text NOT NULL,
  PRIMARY KEY  (`identes_gubernamentales`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `estado`
-- 

CREATE TABLE `estado` (
  `idestado` int(10) unsigned NOT NULL auto_increment,
  `idpais` int(10) unsigned NOT NULL default '0',
  `denominacion` varchar(45) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `status` varchar(1) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  `codigo` varchar(45) NOT NULL default '',
  PRIMARY KEY  (`idestado`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `estado_beneficiario`
-- 

CREATE TABLE `estado_beneficiario` (
  `idestado_beneficiario` int(10) unsigned NOT NULL auto_increment,
  `descripcion` varchar(45) NOT NULL default '',
  `bloquea` varchar(1) default NULL,
  `status` varchar(1) default NULL,
  `usuario` varchar(20) default NULL,
  `fechayhora` datetime default NULL,
  PRIMARY KEY  (`idestado_beneficiario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `experiencia_laboral`
-- 

CREATE TABLE `experiencia_laboral` (
  `secuencia` int(10) unsigned NOT NULL auto_increment,
  `idtrabajador` int(10) unsigned NOT NULL default '0',
  `empresa` varchar(150) NOT NULL default '',
  `desde` datetime NOT NULL,
  `hasta` datetime NOT NULL,
  `tiempo_servicio` varchar(20) NOT NULL default '',
  `motivo_salida` varchar(100) NOT NULL,
  `ultimo_cargo` varchar(100) default NULL,
  `direccion_empresa` varchar(200) default NULL,
  `telefono_empresa` varchar(45) default NULL,
  `observaciones` varchar(200) default NULL,
  `flag_constancia` varchar(1) default NULL,
  `usuario` varchar(20) NOT NULL default '',
  `status` varchar(1) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  `foto` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`secuencia`,`motivo_salida`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `facturas_rendicion_caja_chica`
-- 

CREATE TABLE `facturas_rendicion_caja_chica` (
  `idfactura_rendicion_caja_chica` int(10) unsigned NOT NULL auto_increment,
  `idorden_compra_servicio` int(10) unsigned NOT NULL,
  `nro_factura` varchar(45) NOT NULL,
  `fecha_factura` date NOT NULL,
  `nro_control` varchar(45) NOT NULL,
  `idbeneficiarios` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`idfactura_rendicion_caja_chica`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `fracciones_conceptos_canceladas`
-- 

CREATE TABLE `fracciones_conceptos_canceladas` (
  `idfracciones_conceptos_canceladas` int(10) unsigned NOT NULL auto_increment,
  `idrango_fecha_vencimiento_conceptos` int(10) unsigned NOT NULL default '0',
  `monto_cancelado` decimal(16,2) NOT NULL default '0.00',
  `idpagos_recaudacion` int(10) unsigned NOT NULL default '0',
  `monto_mora` decimal(16,2) NOT NULL default '0.00',
  PRIMARY KEY  (`idfracciones_conceptos_canceladas`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `fuentes_cofinanciamiento`
-- 

CREATE TABLE `fuentes_cofinanciamiento` (
  `idfuentes_cofinanciamiento` int(10) unsigned NOT NULL auto_increment,
  `idfuente_financiamiento` int(10) unsigned NOT NULL default '0',
  `porcentaje` decimal(16,2) NOT NULL default '0.00',
  `idcofinanciamiento` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`idfuentes_cofinanciamiento`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `fuente_financiamiento`
-- 

CREATE TABLE `fuente_financiamiento` (
  `idfuente_financiamiento` int(10) unsigned NOT NULL auto_increment,
  `denominacion` varchar(255) NOT NULL default '',
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idfuente_financiamiento`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `generar_nomina`
-- 

CREATE TABLE `generar_nomina` (
  `idgenerar_nomina` int(10) unsigned NOT NULL auto_increment,
  `descripcion` text NOT NULL,
  `idtipo_nomina` int(10) unsigned NOT NULL,
  `idperiodo` int(10) unsigned NOT NULL,
  `estado` varchar(45) NOT NULL,
  `fecha_elaboracion` date NOT NULL,
  `status` varchar(45) NOT NULL,
  `usuario` varchar(45) NOT NULL,
  `fechayhora` datetime NOT NULL,
  `fecha_procesado` date NOT NULL,
  `idordinal` int(11) NOT NULL,
  `idcategoria_programatica` int(11) NOT NULL,
  `idbeneficiarios` int(11) NOT NULL,
  `idorden_compra_servicio` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`idgenerar_nomina`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `grupos`
-- 

CREATE TABLE `grupos` (
  `idgrupo` varchar(5) NOT NULL default '',
  `denominacion` varchar(80) NOT NULL default '',
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idgrupo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `grupo_catalogo_bienes`
-- 

CREATE TABLE `grupo_catalogo_bienes` (
  `idgrupo_catalogo_bienes` int(10) unsigned NOT NULL auto_increment,
  `codigo` varchar(1) NOT NULL default '',
  `denominacion` varchar(80) NOT NULL default '',
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idgrupo_catalogo_bienes`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `grupo_cuentas_contables`
-- 

CREATE TABLE `grupo_cuentas_contables` (
  `idgrupos_cuentas_contables` int(10) unsigned NOT NULL auto_increment,
  `codigo` varchar(45) NOT NULL default '',
  `denominacion` varchar(45) NOT NULL default '',
  PRIMARY KEY  (`idgrupos_cuentas_contables`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `grupo_sanguineo`
-- 

CREATE TABLE `grupo_sanguineo` (
  `idgrupo_sanguineo` int(10) unsigned NOT NULL auto_increment,
  `denominacion` varchar(4) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `status` varchar(1) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idgrupo_sanguineo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `historico_disfrute_vacaciones`
-- 

CREATE TABLE `historico_disfrute_vacaciones` (
  `idhistorio_disfrute_vacaciones` int(10) NOT NULL auto_increment,
  `idhistorico_vacaciones` int(10) NOT NULL,
  `fecha_inicio_disfrute` datetime NOT NULL,
  `fecha_culminacion_disfrute` datetime NOT NULL,
  `tiempo_disfrutado` varchar(4) NOT NULL,
  `numero_notificacion` varchar(10) NOT NULL,
  `fecha_notificacion` datetime NOT NULL,
  PRIMARY KEY  (`idhistorio_disfrute_vacaciones`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `historico_movimiento_personal`
-- 

CREATE TABLE `historico_movimiento_personal` (
  `idhistorico_movimiento_personal` int(10) NOT NULL auto_increment,
  `idtrabajador` int(10) NOT NULL,
  `idtipo_movimiento` int(10) NOT NULL,
  `fecha_inicio_aplicacion` datetime NOT NULL,
  `fecha_culminacion_aplicacion` datetime NOT NULL,
  `motivo` text NOT NULL,
  `numero_resolucion` varchar(20) NOT NULL,
  `fecha_resolucion` datetime NOT NULL,
  `numero_gaceta` varchar(20) NOT NULL,
  `fecha_gaceta` datetime NOT NULL,
  `idcargo_anterior` int(10) NOT NULL,
  `idnuevo_cargo` int(10) NOT NULL,
  PRIMARY KEY  (`idhistorico_movimiento_personal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `historico_permisos`
-- 

CREATE TABLE `historico_permisos` (
  `idhistorico_permisos` int(10) NOT NULL auto_increment,
  `idtrabajador` int(10) NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `hora_inicio` datetime NOT NULL,
  `fecha_culminacion` datetime NOT NULL,
  `hora_culminacion` datetime NOT NULL,
  `fecha_solicitud` datetime NOT NULL,
  `tiempo_total` varchar(10) NOT NULL,
  `descuenta_bono_alimentacion` varchar(1) NOT NULL,
  `motivo` text NOT NULL,
  `justificado` varchar(1) NOT NULL,
  `remunerado` varchar(1) NOT NULL,
  `aprobado_por` varchar(60) NOT NULL,
  `ci_aprobado` varchar(12) NOT NULL,
  `usuario` varchar(20) NOT NULL,
  `fechayhora` datetime NOT NULL,
  PRIMARY KEY  (`idhistorico_permisos`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `historico_usuarios`
-- 

CREATE TABLE `historico_usuarios` (
  `idregistro` int(10) unsigned NOT NULL auto_increment,
  `estacion` varchar(45) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  `usuario` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`idregistro`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `historico_vacaciones`
-- 

CREATE TABLE `historico_vacaciones` (
  `idhistorico_vacaciones` int(10) NOT NULL auto_increment,
  `idtrabajador` int(10) NOT NULL,
  `periodo` varchar(10) NOT NULL,
  `numero_memorandum` varchar(10) NOT NULL,
  `fecha_memorandum` date NOT NULL,
  `fecha_inicio_vacacion` date NOT NULL,
  `fecha_culminacion_vacacion` date NOT NULL,
  `cantidad_feriados` int(11) NOT NULL,
  `tiempo_disfrute` varchar(4) NOT NULL,
  `fecha_inicio_disfrute` date NOT NULL,
  `fecha_reincorporacion` date NOT NULL,
  `fecha_reincorporacion_ajustada` date NOT NULL,
  `dias_pendiente_disfrute` int(11) NOT NULL,
  `dias_bono` decimal(16,0) NOT NULL,
  `monto_bono` decimal(16,2) NOT NULL,
  `numero_orden_pago` varchar(16) NOT NULL,
  `fecha_cancelacion` date NOT NULL,
  `elaborado_por` varchar(35) NOT NULL,
  `ci_elaborado_por` varchar(12) NOT NULL,
  `aprobada_por` varchar(60) NOT NULL,
  `ci_aprobado` varchar(12) NOT NULL,
  `usuario` varchar(20) NOT NULL,
  `fechayhora` datetime NOT NULL,
  `oculto_dias` int(10) unsigned NOT NULL,
  `oculto_disfrutados` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`idhistorico_vacaciones`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `hoja_tiempo`
-- 

CREATE TABLE `hoja_tiempo` (
  `idhoja_tiempo` int(10) unsigned NOT NULL auto_increment,
  `idtipo_hoja_tiempo` int(10) unsigned NOT NULL,
  `idtipo_nomina` int(10) unsigned NOT NULL,
  `centro_costo` int(10) unsigned NOT NULL,
  `periodo` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`idhoja_tiempo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `imagenes_contribuyente`
-- 

CREATE TABLE `imagenes_contribuyente` (
  `idimagenes_contribuyente` int(10) unsigned NOT NULL auto_increment,
  `idcontribuyente` int(11) NOT NULL,
  `ruta` varchar(500) NOT NULL default '',
  `descripcion` text NOT NULL,
  `status` varchar(45) NOT NULL default '',
  `usuario` varchar(45) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idimagenes_contribuyente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `impuestos`
-- 

CREATE TABLE `impuestos` (
  `idimpuestos` int(10) unsigned NOT NULL auto_increment,
  `descripcion` varchar(100) NOT NULL default '',
  `siglas` varchar(15) NOT NULL default '',
  `porcentaje` decimal(10,2) NOT NULL default '0.00',
  `destino_partida` int(1) NOT NULL default '1' COMMENT '0: no tiene partida; 1 tiene partida',
  `idclasificador_presupuestario` int(10) unsigned NOT NULL,
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idimpuestos`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `ingresos_egresos_financieros`
-- 

CREATE TABLE `ingresos_egresos_financieros` (
  `idingresos_financieros` int(10) unsigned NOT NULL auto_increment,
  `idtipo_movimiento` int(10) unsigned NOT NULL default '0',
  `tipo` varchar(45) NOT NULL default '',
  `numero_documento` varchar(45) NOT NULL default '',
  `fecha` date NOT NULL default '0000-00-00',
  `idbanco` int(10) unsigned NOT NULL default '0',
  `idcuentas_bancarias` varchar(45) NOT NULL default '',
  `monto` decimal(16,2) NOT NULL default '0.00',
  `emitido_por` varchar(45) default NULL,
  `ci_emitido` varchar(12) default NULL,
  `concepto` varchar(200) default NULL,
  `status` varchar(1) default NULL,
  `usuario` varchar(20) default NULL,
  `fechayhora` datetime default NULL,
  `contabilizado` varchar(45) NOT NULL default 'no',
  PRIMARY KEY  (`idingresos_financieros`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `instalaciones_fijas`
-- 

CREATE TABLE `instalaciones_fijas` (
  `idinstalaciones_fijas` int(10) unsigned NOT NULL auto_increment,
  `idinmueble` int(10) unsigned NOT NULL,
  `tipo_inmueble` varchar(45) NOT NULL,
  PRIMARY KEY  (`idinstalaciones_fijas`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `instruccion_academica`
-- 

CREATE TABLE `instruccion_academica` (
  `idinstruccion_academica` int(10) unsigned NOT NULL auto_increment,
  `idtrabajador` int(10) unsigned NOT NULL default '0',
  `idnivel_estudio` int(10) unsigned NOT NULL default '0',
  `idprofesion` int(10) unsigned NOT NULL,
  `idmension` int(10) unsigned NOT NULL,
  `institucion` varchar(150) default NULL,
  `anio_egreso` varchar(4) default NULL,
  `observaciones` varchar(200) default NULL,
  `flag_constancia` varchar(1) default NULL,
  `flag_actual` varchar(1) default NULL,
  `usuario` varchar(20) NOT NULL default '',
  `status` varchar(1) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  `foto` text NOT NULL,
  PRIMARY KEY  (`idinstruccion_academica`,`idtrabajador`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `jornada_tipo_nomina`
-- 

CREATE TABLE `jornada_tipo_nomina` (
  `idjornada_tipo_nomina` int(11) NOT NULL auto_increment,
  `idtipo_nomina` int(11) NOT NULL,
  `lunes` varchar(1) NOT NULL,
  `martes` varchar(1) NOT NULL,
  `miercoles` varchar(1) NOT NULL,
  `jueves` varchar(1) NOT NULL,
  `viernes` varchar(1) NOT NULL,
  `sabado` varchar(1) NOT NULL,
  `domingo` varchar(1) NOT NULL,
  PRIMARY KEY  (`idjornada_tipo_nomina`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `lista_usuarios_mensajes_correo`
-- 

CREATE TABLE `lista_usuarios_mensajes_correo` (
  `idlista_usuarios_mensajes_correo` int(10) unsigned NOT NULL auto_increment,
  `idmensajes_correo` int(10) unsigned NOT NULL default '0',
  `idusuario` varchar(45) NOT NULL default '0',
  PRIMARY KEY  (`idlista_usuarios_mensajes_correo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `maestro_presupuesto`
-- 

CREATE TABLE `maestro_presupuesto` (
  `idRegistro` int(10) unsigned NOT NULL auto_increment,
  `anio` varchar(4) NOT NULL default '',
  `idcategoria_programatica` int(10) unsigned NOT NULL default '0',
  `idtipo_presupuesto` int(10) unsigned NOT NULL default '0',
  `idfuente_financiamiento` int(10) unsigned NOT NULL default '0',
  `idclasificador_presupuestario` int(10) unsigned NOT NULL default '0',
  `idordinal` int(10) unsigned NOT NULL default '0',
  `monto_original` decimal(16,2) NOT NULL default '0.00',
  `total_disminucion` decimal(16,2) NOT NULL default '0.00',
  `total_aumento` decimal(16,2) NOT NULL default '0.00',
  `reservado_disminuir` decimal(16,2) NOT NULL default '0.00',
  `solicitud_aumento` decimal(16,2) NOT NULL default '0.00',
  `monto_actual` decimal(16,2) NOT NULL default '0.00',
  `pre_compromiso` decimal(16,2) NOT NULL default '0.00',
  `total_compromisos` decimal(16,2) NOT NULL default '0.00',
  `total_causados` decimal(16,2) NOT NULL default '0.00',
  `total_pagados` decimal(16,2) NOT NULL default '0.00',
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idRegistro`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `mensajes_correo`
-- 

CREATE TABLE `mensajes_correo` (
  `idmensajes_correo` int(10) unsigned NOT NULL auto_increment,
  `idusuario_envia` varchar(45) NOT NULL default '0',
  `asunto` varchar(200) NOT NULL default '',
  `mensaje` text NOT NULL,
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  `pc` varchar(45) NOT NULL default '',
  `estado` varchar(45) NOT NULL default '',
  PRIMARY KEY  (`idmensajes_correo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 75776 kB';

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `mension`
-- 

CREATE TABLE `mension` (
  `idmension` int(10) unsigned NOT NULL auto_increment,
  `denominacion` varchar(45) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `status` varchar(1) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idmension`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `modalidad_contratacion`
-- 

CREATE TABLE `modalidad_contratacion` (
  `idmodalidad_contratacion` int(10) unsigned NOT NULL auto_increment,
  `descripcion` varchar(45) NOT NULL default '',
  `status` varchar(45) NOT NULL default '',
  `usuario` varchar(45) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idmodalidad_contratacion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `modulo`
-- 

CREATE TABLE `modulo` (
  `id_modulo` int(10) unsigned NOT NULL auto_increment,
  `nombre_modulo` varchar(45) NOT NULL default '',
  `orden` varchar(2) NOT NULL,
  `mostrar` varchar(45) NOT NULL,
  PRIMARY KEY  (`id_modulo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `moras_conceptos_tributarios`
-- 

CREATE TABLE `moras_conceptos_tributarios` (
  `idmoras_conceptos_tributarios` int(10) unsigned NOT NULL auto_increment,
  `idconcepto_tributario` int(10) unsigned NOT NULL default '0',
  `denominacion` varchar(100) NOT NULL default '',
  `sobre` varchar(45) NOT NULL default '',
  `frecuencia_calculo` varchar(45) NOT NULL default '',
  `condicion_tipo` varchar(45) NOT NULL default '',
  `condicion_operador` varchar(45) NOT NULL default '',
  `condicion_factor` varchar(45) NOT NULL default '',
  `condicion_valor` int(10) unsigned NOT NULL default '0',
  `tipo_mora` varchar(45) NOT NULL default '',
  `valor_calculo` decimal(16,2) NOT NULL default '0.00',
  `tipo_valor_mora` varchar(45) NOT NULL default '',
  PRIMARY KEY  (`idmoras_conceptos_tributarios`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `motivos_cuentas`
-- 

CREATE TABLE `motivos_cuentas` (
  `idmotivos_cuentas` int(10) unsigned NOT NULL auto_increment,
  `denominacion` varchar(45) NOT NULL,
  PRIMARY KEY  (`idmotivos_cuentas`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `movimientos_bienes`
-- 

CREATE TABLE `movimientos_bienes` (
  `idmovimientos_bienes` int(10) unsigned NOT NULL auto_increment,
  `nro_movimiento` varchar(45) NOT NULL default '',
  `fecha_movimiento` date NOT NULL default '0000-00-00',
  `afecta` varchar(45) NOT NULL default '',
  `tipo` varchar(45) NOT NULL default '',
  `idtipo_movimiento` int(10) unsigned NOT NULL default '0',
  `nro_documento` varchar(45) NOT NULL default '',
  `fecha_documento` date NOT NULL default '0000-00-00',
  `justificacion` text NOT NULL,
  `estado` varchar(45) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  `pc` varchar(45) NOT NULL default '',
  `usuario` varchar(45) NOT NULL default '',
  `nro_documento_bien_nuevo` varchar(45) NOT NULL,
  `fecha_documento_bien_nuevo` date NOT NULL,
  `proveedor_bien_nuevo` varchar(200) NOT NULL,
  `nro_factura_bien_nuevo` varchar(200) NOT NULL,
  `fecha_factura_bien_nuevo` date NOT NULL,
  PRIMARY KEY  (`idmovimientos_bienes`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `movimientos_bienes_individuales`
-- 

CREATE TABLE `movimientos_bienes_individuales` (
  `idmovimientos_bienes_individuales` int(10) unsigned NOT NULL auto_increment,
  `tipo` varchar(45) NOT NULL default '',
  `codigo_bien` varchar(45) NOT NULL default '',
  `idcatalogo_bienes` int(10) unsigned NOT NULL default '0',
  `especificaciones` text NOT NULL,
  `idorganizacion_actual` int(10) unsigned NOT NULL default '0',
  `idnivel_organizacional_actual` int(10) unsigned NOT NULL default '0',
  `nro_orden` varchar(45) NOT NULL default '',
  `fecha_orden` date NOT NULL default '0000-00-00',
  `idtipo_movimiento` int(10) unsigned NOT NULL default '0',
  `idorganizacion_destino` int(10) unsigned NOT NULL default '0',
  `idnivel_organizacional_destino` int(10) unsigned NOT NULL default '0',
  `fecha_movimiento` date NOT NULL default '0000-00-00',
  `fecha_regreso` date NOT NULL default '0000-00-00',
  `retorno_automatico` varchar(2) NOT NULL default '',
  `justificacion_movimiento` text NOT NULL,
  `status` varchar(45) NOT NULL default '',
  `usuario` varchar(45) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  `idbien` int(10) unsigned NOT NULL default '0',
  `tipo_bien` varchar(45) NOT NULL default '',
  `estado` varchar(45) NOT NULL default '',
  PRIMARY KEY  (`idmovimientos_bienes_individuales`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `movimientos_bienes_nuevos`
-- 

CREATE TABLE `movimientos_bienes_nuevos` (
  `idmovimientos_bienes_nuevos` int(10) unsigned NOT NULL auto_increment,
  `idmovimientos_bienes` int(10) unsigned NOT NULL default '0',
  `nro_adquisicion` varchar(45) NOT NULL default '',
  `fecha_documento` date NOT NULL default '0000-00-00',
  `idbeneficiario` int(10) unsigned NOT NULL default '0',
  `nro_factura` varchar(45) NOT NULL default '',
  `fecha_factura` date NOT NULL default '0000-00-00',
  `idorganizacion` int(10) unsigned NOT NULL default '0',
  `idnivel_organizacional` int(10) unsigned NOT NULL default '0',
  `codigo_catalogo` varchar(45) NOT NULL default '',
  `codigo_bien` varchar(45) NOT NULL default '',
  `idubicacion` int(10) unsigned NOT NULL default '0',
  `especificaciones` text NOT NULL,
  `marca` varchar(45) NOT NULL default '',
  `modelo` varchar(45) NOT NULL default '',
  `tipo` varchar(45) NOT NULL default '',
  `seriales` varchar(45) NOT NULL default '',
  `accesorios` varchar(300) NOT NULL default '',
  `costo` decimal(16,2) NOT NULL default '0.00',
  `valor_residual` decimal(16,2) NOT NULL default '0.00',
  `vida_util` int(10) unsigned NOT NULL default '0',
  `mejoras` decimal(16,2) NOT NULL default '0.00',
  `costo_ajustado` decimal(16,2) NOT NULL default '0.00',
  `depresiacion_anual` decimal(16,2) NOT NULL default '0.00',
  `depresiacion_acumulada` decimal(16,2) NOT NULL default '0.00',
  `asegurado` varchar(2) NOT NULL default '',
  PRIMARY KEY  (`idmovimientos_bienes_nuevos`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `movimientos_existentes_desincorporacion`
-- 

CREATE TABLE `movimientos_existentes_desincorporacion` (
  `idmovimientos_existentes_desincorporacion` int(10) unsigned NOT NULL auto_increment,
  `idmovimientos_bienes` int(10) unsigned NOT NULL default '0',
  `codigo_bien` varchar(45) NOT NULL default '',
  `codigo_catalogo` varchar(45) NOT NULL default '',
  `denominacion` varchar(100) NOT NULL default '',
  `especificadores` varchar(200) NOT NULL default '',
  `motivos` text NOT NULL,
  PRIMARY KEY  (`idmovimientos_existentes_desincorporacion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `movimientos_existentes_incorporacion`
-- 

CREATE TABLE `movimientos_existentes_incorporacion` (
  `idmovimientos_existentes_incorporacion` int(10) unsigned NOT NULL auto_increment,
  `idmovimientos_bienes` int(10) unsigned NOT NULL default '0',
  `idorganizacion` int(10) unsigned NOT NULL default '0',
  `idnivel_organizacional` int(10) unsigned NOT NULL default '0',
  `idorganizacion_destino` int(10) unsigned NOT NULL default '0',
  `idnivel_organizacional_destino` int(10) unsigned NOT NULL default '0',
  `codigo_bien` varchar(45) NOT NULL default '',
  `codigo_catalogo` varchar(45) NOT NULL default '',
  `denominacion` varchar(100) NOT NULL default '',
  `mejoras` int(10) unsigned NOT NULL default '0',
  `descripcion` varchar(200) NOT NULL default '',
  `retorno_automatico` varchar(2) NOT NULL default '',
  `fecha_retorno` date NOT NULL default '0000-00-00',
  `especificaciones` varchar(200) NOT NULL default '',
  `usuario_retorno` varchar(45) NOT NULL,
  `fecha_retornado` datetime NOT NULL,
  `usuario_cambio_fecha` varchar(45) NOT NULL,
  `fecha_cambio_fecha` datetime NOT NULL,
  PRIMARY KEY  (`idmovimientos_existentes_incorporacion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `movimientos_lotes`
-- 

CREATE TABLE `movimientos_lotes` (
  `idmovimientos_lotes` int(10) unsigned NOT NULL auto_increment,
  `nro_movimiento` varchar(45) NOT NULL,
  `tipo` varchar(45) NOT NULL default '',
  `nro_orden` varchar(45) NOT NULL default '',
  `fecha_orden` date NOT NULL default '0000-00-00',
  `tipo_movimiento` int(10) unsigned NOT NULL default '0',
  `fecha_movimiento` date NOT NULL default '0000-00-00',
  `fecha_regreso` date NOT NULL default '0000-00-00',
  `regreso_automatico` varchar(45) NOT NULL default '',
  `justificacion_movimiento` text NOT NULL,
  `status` varchar(45) NOT NULL default '',
  `usuario` varchar(45) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  `estado` varchar(45) NOT NULL default 'elaboracion',
  PRIMARY KEY  (`idmovimientos_lotes`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `movimientos_personal`
-- 

CREATE TABLE `movimientos_personal` (
  `idmovimientos_personal` int(10) unsigned NOT NULL auto_increment,
  `idtrabajador` int(10) unsigned NOT NULL default '0',
  `fecha_movimiento` date NOT NULL default '0000-00-00',
  `idtipo_movimiento` int(10) unsigned NOT NULL default '0',
  `justificacion` text NOT NULL,
  `fecha_ingreso` date NOT NULL default '0000-00-00',
  `idcargo` varchar(45) NOT NULL default '0',
  `idubicacion_funcional` int(10) unsigned NOT NULL default '0',
  `fecha_egreso` date NOT NULL default '0000-00-00',
  `causal` varchar(45) NOT NULL default '',
  `usuario` varchar(45) NOT NULL default '',
  `status` varchar(45) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  `centro_costo` int(10) unsigned NOT NULL default '0',
  `fecha_reingreso` date NOT NULL default '0000-00-00',
  `desde` date NOT NULL default '0000-00-00',
  `hasta` date NOT NULL default '0000-00-00',
  `idtipo_nomina` int(10) unsigned NOT NULL,
  `idconstante` int(10) unsigned NOT NULL default '0',
  `valor` decimal(16,2) NOT NULL default '0.00',
  `nro_ficha` varchar(45) NOT NULL default '',
  PRIMARY KEY  (`idmovimientos_personal`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `muebles`
-- 

CREATE TABLE `muebles` (
  `idmuebles` int(10) unsigned NOT NULL auto_increment,
  `idorganizacion` int(10) unsigned NOT NULL default '0',
  `idnivel_organizacion` int(10) unsigned NOT NULL default '0',
  `idtipo_movimiento` int(10) unsigned NOT NULL default '0',
  `idcatalogo_bienes` int(10) unsigned NOT NULL default '0',
  `codigo_bien` varchar(20) NOT NULL default '',
  `especificaciones` text NOT NULL,
  `marca` varchar(45) NOT NULL default '',
  `modelo` varchar(45) NOT NULL default '',
  `idtipo` int(10) unsigned NOT NULL default '0',
  `serial` varchar(100) NOT NULL default '',
  `accesorios` text NOT NULL,
  `numero_documento_compra` varchar(45) NOT NULL default '',
  `proveedor` varchar(100) NOT NULL default '0',
  `nro_factura` varchar(10) NOT NULL,
  `fecha_factura` date NOT NULL,
  `fecha_compra` date NOT NULL default '0000-00-00',
  `costo` decimal(16,2) NOT NULL default '0.00',
  `valor_residual` decimal(16,2) NOT NULL default '0.00',
  `vida_util` int(10) unsigned NOT NULL default '0',
  `depreciacion_anual` decimal(16,2) NOT NULL default '0.00',
  `depreciacion_acumulada` decimal(16,2) NOT NULL default '0.00',
  `asegurado` varchar(2) NOT NULL default '',
  `aseguradora` varchar(100) NOT NULL default '',
  `nro_poliza` varchar(45) NOT NULL default '',
  `fecha_vencimiento` date NOT NULL default '0000-00-00',
  `monto_poliza` decimal(16,2) NOT NULL default '0.00',
  `monto_asegurado` decimal(16,2) NOT NULL default '0.00',
  `status` varchar(45) NOT NULL default '',
  `usuario` varchar(45) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  `idubicacion` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`idmuebles`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `muebles_desincorporacion`
-- 

CREATE TABLE `muebles_desincorporacion` (
  `idmuebles_desincorporacion` int(10) unsigned NOT NULL auto_increment,
  `idmueble` int(10) unsigned NOT NULL default '0',
  `iddesincorporacion` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`idmuebles_desincorporacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `municipios`
-- 

CREATE TABLE `municipios` (
  `idmunicipios` int(10) unsigned NOT NULL auto_increment,
  `idestado` int(10) unsigned NOT NULL default '0',
  `denominacion` varchar(45) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `status` varchar(1) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idmunicipios`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `nacionalidad`
-- 

CREATE TABLE `nacionalidad` (
  `idnacionalidad` int(10) unsigned NOT NULL auto_increment,
  `denominacion` varchar(45) NOT NULL default '',
  `indicador` varchar(1) NOT NULL,
  `usuario` varchar(20) NOT NULL default '',
  `status` varchar(1) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idnacionalidad`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `niveles_organizacionales`
-- 

CREATE TABLE `niveles_organizacionales` (
  `idniveles_organizacionales` int(10) unsigned NOT NULL auto_increment,
  `organizacion` int(10) unsigned NOT NULL default '0',
  `sub_nivel` int(10) unsigned NOT NULL default '0',
  `codigo` varchar(45) NOT NULL default '',
  `denominacion` varchar(150) NOT NULL,
  `responsable` varchar(80) NOT NULL,
  `ci_responsable` varchar(12) NOT NULL,
  `telefono` varchar(45) NOT NULL default '',
  `email` varchar(45) NOT NULL default '',
  `status` varchar(45) NOT NULL default '',
  `usuario` varchar(45) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  `modulo` int(10) unsigned NOT NULL default '0',
  `idcategoria_programatica` int(10) unsigned NOT NULL default '0',
  `inventario_cerrado` varchar(45) NOT NULL default '',
  PRIMARY KEY  (`idniveles_organizacionales`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `nivel_estudio`
-- 

CREATE TABLE `nivel_estudio` (
  `idnivel_estudio` int(10) unsigned NOT NULL auto_increment,
  `denominacion` varchar(30) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `status` varchar(1) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idnivel_estudio`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `nombres_firmas`
-- 

CREATE TABLE `nombres_firmas` (
  `idnombres_firmas` int(10) unsigned NOT NULL auto_increment,
  `idtipo_reporte` int(10) unsigned NOT NULL default '0',
  `idmodulo` int(10) unsigned NOT NULL default '0',
  `iddependencia` int(10) unsigned NOT NULL default '0',
  `titulo` varchar(100) NOT NULL default '',
  `nombre_campo` varchar(45) NOT NULL default '',
  `tabla` varchar(45) NOT NULL default '',
  `posicion` int(10) unsigned NOT NULL default '0',
  `campo_completo` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`idnombres_firmas`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `nomenclatura_fichas`
-- 

CREATE TABLE `nomenclatura_fichas` (
  `idnomenclatura_fichas` int(10) unsigned NOT NULL auto_increment,
  `descripcion` varchar(45) NOT NULL default '',
  `numero` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`idnomenclatura_fichas`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `orden_compra_servicio`
-- 

CREATE TABLE `orden_compra_servicio` (
  `idorden_compra_servicio` int(10) unsigned NOT NULL auto_increment,
  `numero_orden` varchar(20) NOT NULL,
  `fecha_orden` date NOT NULL,
  `tipo` int(10) unsigned NOT NULL default '0',
  `fecha_elaboracion` date NOT NULL,
  `proceso` varchar(20) NOT NULL,
  `numero_documento` varchar(12) default NULL,
  `idbeneficiarios` int(10) unsigned NOT NULL default '0',
  `idcategoria_programatica` int(10) unsigned NOT NULL default '0',
  `anio` varchar(4) NOT NULL default '0000',
  `idfuente_financiamiento` int(10) unsigned NOT NULL,
  `idtipo_presupuesto` int(10) unsigned NOT NULL,
  `idordinal` int(10) unsigned NOT NULL,
  `justificacion` text,
  `observaciones` longtext,
  `ordenado_por` varchar(40) default NULL,
  `cedula_ordenado` varchar(12) default NULL,
  `numero_requisicion` varchar(12) default NULL,
  `fecha_requisicion` date NOT NULL,
  `nro_items` int(10) default '0',
  `exento` decimal(16,2) NOT NULL default '0.00',
  `sub_total` decimal(16,2) default '0.00',
  `exento_original` decimal(16,2) NOT NULL,
  `sub_total_original` decimal(16,2) NOT NULL,
  `impuesto` decimal(16,2) default '0.00',
  `descuento` decimal(16,2) NOT NULL,
  `total` decimal(16,2) default '0.00',
  `estado` varchar(20) default NULL COMMENT 'En elaboración / Procesado / Anulado / Cancelado / Devuelto',
  `idrazones_devolucion` int(10) unsigned NOT NULL default '0',
  `observaciones_devolucion` longtext,
  `numero_remision` varchar(12) default NULL COMMENT 'Numero generado cuando se emita el memorandum de remisiones',
  `fecha_remision` date NOT NULL COMMENT 'fecha del memorandum de remision',
  `recibido_por` varchar(40) default NULL COMMENT 'Identificacion de quien recibe el memorandum de remision',
  `cedula_recibido` varchar(12) default NULL COMMENT 'Cedula de quien recibe el memroandum de remision',
  `fecha_recibido` date NOT NULL COMMENT 'Fecha de recibido el memorandum de remision',
  `ubicacion` varchar(20) default '0' COMMENT 'Presupuesto / Administracion / Contabilidad / Tesoreria / Bienes / Cancelado',
  `status` varchar(1) default NULL,
  `usuario` varchar(20) default NULL,
  `fechayhora` datetime default NULL,
  `duplicados` int(10) unsigned NOT NULL default '0',
  `nro_factura` varchar(45) NOT NULL,
  `fecha_factura` varchar(45) NOT NULL,
  `nro_control` varchar(45) NOT NULL,
  `codigo_referencia` varchar(12) NOT NULL,
  `tipo_carga_orden` varchar(45) NOT NULL,
  `contabilizado` varchar(45) NOT NULL default 'no',
  `idtipo_caja_chica` int(10) unsigned NOT NULL,
  `idtipo_nomina` int(10) unsigned NOT NULL default '0',
  `idperiodo` int(10) unsigned NOT NULL default '0',
  `idconcepto` int(10) unsigned NOT NULL default '0',
  `fecha_anulacion` date NOT NULL default '0000-00-00',
  `iddependencia` int(10) unsigned NOT NULL default '0',
  `cofinanciamiento` varchar(2) NOT NULL,
  PRIMARY KEY  (`idorden_compra_servicio`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `orden_pago`
-- 

CREATE TABLE `orden_pago` (
  `idorden_pago` int(10) unsigned NOT NULL auto_increment,
  `numero_orden` varchar(20) NOT NULL,
  `fecha_orden` date NOT NULL,
  `tipo` int(10) unsigned NOT NULL default '0',
  `fecha_elaboracion` date NOT NULL,
  `proceso` varchar(20) NOT NULL,
  `numero_documento` varchar(12) default NULL,
  `idbeneficiarios` int(10) unsigned NOT NULL default '0',
  `idcategoria_programatica` int(10) unsigned NOT NULL default '0',
  `anio` varchar(4) NOT NULL,
  `idfuente_financiamiento` int(11) NOT NULL,
  `idtipo_presupuesto` int(11) NOT NULL,
  `idordinal` int(11) NOT NULL,
  `justificacion` text,
  `observaciones` longtext,
  `nro_documento` varchar(25) default NULL,
  `fecha_documento` date NOT NULL,
  `ordenado_por` varchar(40) default NULL,
  `cedula_ordenado` varchar(12) default NULL,
  `numero_proyecto` varchar(50) NOT NULL,
  `numero_contrato` varchar(50) NOT NULL,
  `exento` decimal(16,2) default '0.00',
  `sub_total` decimal(16,2) default '0.00',
  `impuesto` decimal(16,2) default '0.00',
  `total` decimal(16,2) default '0.00',
  `total_retenido` decimal(16,2) default NULL,
  `total_a_pagar` decimal(16,2) default NULL,
  `estado` varchar(20) default NULL COMMENT 'En elaboración / Procesado / Anulado / Cancelado / Devuelto',
  `idrazones_devolucion` int(10) unsigned NOT NULL default '0',
  `observaciones_devolucion` varchar(200) default NULL,
  `numero_remision` varchar(12) default NULL COMMENT 'Numero generado cuando se emita el memorandum de remisiones',
  `fecha_remision` date NOT NULL COMMENT 'fecha del memorandum de remision',
  `recibido_por` varchar(40) default NULL COMMENT 'Identificacion de quien recibe el memorandum de remision',
  `cedula_recibido` varchar(12) default NULL COMMENT 'Cedula de quien recibe el memroandum de remision',
  `fecha_recibido` date NOT NULL COMMENT 'Fecha de recibido el memorandum de remision',
  `ubicacion` varchar(20) default '0' COMMENT 'Presupuesto / Administracion / Contabilidad / Tesoreria / Bienes / Cancelado',
  `status` varchar(1) default NULL,
  `usuario` varchar(20) default NULL,
  `fechayhora` datetime default NULL,
  `duplicados` int(10) unsigned NOT NULL default '0',
  `forma_pago` varchar(45) NOT NULL,
  `codigo_referencia` varchar(12) NOT NULL,
  `contabilizado` varchar(45) NOT NULL default 'no',
  `porcentaje_anticipo` varchar(45) NOT NULL default '',
  `anticipo` varchar(2) NOT NULL default '',
  `fecha_anulacion` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`idorden_pago`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `ordinal`
-- 

CREATE TABLE `ordinal` (
  `idordinal` int(10) unsigned NOT NULL auto_increment,
  `codigo` varchar(4) default NULL,
  `denominacion` varchar(255) default NULL,
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) default NULL,
  `fechayhora` datetime default NULL,
  PRIMARY KEY  (`idordinal`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `organizacion`
-- 

CREATE TABLE `organizacion` (
  `idorganizacion` int(10) unsigned NOT NULL auto_increment,
  `codigo` varchar(45) NOT NULL default '0',
  `denominacion` varchar(45) NOT NULL default '',
  `responsable` varchar(45) NOT NULL default '',
  `idestado` int(10) unsigned NOT NULL default '0',
  `idmunicipio` int(10) unsigned NOT NULL default '0',
  `direccion` text NOT NULL,
  `telefonos` varchar(45) NOT NULL default '',
  `email` varchar(45) NOT NULL default '',
  `status` varchar(45) NOT NULL default '',
  `usuario` varchar(45) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idorganizacion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `pagos_financieros`
-- 

CREATE TABLE `pagos_financieros` (
  `idpagos_financieros` int(10) unsigned NOT NULL auto_increment,
  `idtipo_documento` int(10) unsigned NOT NULL default '0' COMMENT 'Tipo de documento: cheque, fideicomiso, transferencia, valuacion, anticipo (tabla tipo_documentos)',
  `numero_documento` varchar(45) NOT NULL,
  `forma_pago` varchar(10) NOT NULL default '' COMMENT 'select: Total o parcial',
  `modo_cancelacion` varchar(45) NOT NULL,
  `porcentaje_pago` decimal(6,2) NOT NULL default '0.00' COMMENT 'Si es parcial preguntar que porcentaje se pagara (el monto_cheque se recalcula y se deja para modificarlo)',
  `numero_parte_pago` int(10) NOT NULL default '0' COMMENT 'Numero de parte del pago (select desde 0 hasta 20)',
  `idtipo_movimiento_bancario` int(10) NOT NULL default '0' COMMENT 'Tipo de movimiento: pago, trasferencia, fideicomiso, (debita)',
  `idorden_pago` int(10) unsigned NOT NULL default '0' COMMENT 'Id orden de pago a emitir pago (vacio si es un cheque directo)',
  `idcuenta_bancaria` int(10) unsigned NOT NULL default '0' COMMENT 'Cuenta bancaria a debitar el pago',
  `idcheques_cuentas_bancarias` int(10) unsigned NOT NULL default '0' COMMENT 'Numerod e chequera a usar para pagar (vacio si es transferencia o fideicomiso)',
  `numero_cheque` varchar(12) NOT NULL default '' COMMENT 'numero del cheque',
  `fecha_cheque` date default NULL COMMENT 'fecha del pago',
  `monto_cheque` decimal(16,2) NOT NULL default '0.00' COMMENT 'monto',
  `beneficiario` varchar(80) character set utf8 collate utf8_unicode_ci default NULL COMMENT 'beneficiario (traerlo de la orden de pago o llenarlo si es pago directo',
  `ci_beneficiario` varchar(15) NOT NULL,
  `formato_imprimir` varchar(20) NOT NULL default '' COMMENT 'formato a imprimir (cheque, memo, transferencia)',
  `recibido_por` varchar(60) NOT NULL,
  `ci_recibe` varchar(60) NOT NULL,
  `fecha_recibe` date NOT NULL,
  `estado` varchar(20) NOT NULL default '' COMMENT 'firma, caja, entregado',
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(45) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  `codigo_referencia` varchar(12) NOT NULL,
  `contabilizado` varchar(45) NOT NULL default 'no',
  PRIMARY KEY  (`idpagos_financieros`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `pagos_recaudacion`
-- 

CREATE TABLE `pagos_recaudacion` (
  `idpagos_recaudacion` int(10) unsigned NOT NULL auto_increment,
  `idcontribuyente` int(10) unsigned NOT NULL default '0',
  `numero_planilla` varchar(45) NOT NULL default '',
  `fecha_pago` date NOT NULL default '0000-00-00',
  `total` decimal(16,2) NOT NULL default '0.00',
  `descuento` decimal(16,2) NOT NULL default '0.00',
  `mora` decimal(16,2) NOT NULL default '0.00',
  `total_cancelar` decimal(16,2) NOT NULL default '0.00',
  `idbanco` int(10) unsigned NOT NULL default '0',
  `idcuenta` int(10) unsigned NOT NULL default '0',
  `especie` varchar(45) NOT NULL default '',
  `banco` int(10) unsigned NOT NULL default '0',
  `nro_cuenta` int(10) unsigned NOT NULL default '0',
  `nro_documento` varchar(45) NOT NULL default '',
  `nro_cheque_tarjeta` varchar(45) NOT NULL default '',
  `tipo_tarjeta` varchar(45) NOT NULL default '',
  `fecha` date NOT NULL default '0000-00-00',
  `ci_titular` int(10) unsigned NOT NULL default '0',
  `recibido_por` varchar(45) NOT NULL default '',
  `ci_recibido` varchar(45) NOT NULL default '',
  `status` varchar(45) NOT NULL default '',
  `usuario` varchar(45) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  `estado` varchar(45) NOT NULL default '',
  PRIMARY KEY  (`idpagos_recaudacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `pais`
-- 

CREATE TABLE `pais` (
  `idpais` int(10) unsigned NOT NULL auto_increment,
  `denominacion` varchar(40) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `status` varchar(1) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idpais`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `parentezco`
-- 

CREATE TABLE `parentezco` (
  `idparentezco` int(10) unsigned NOT NULL auto_increment,
  `denominacion` varchar(45) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `status` varchar(1) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idparentezco`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `parroquia`
-- 

CREATE TABLE `parroquia` (
  `idparroquia` int(10) unsigned NOT NULL auto_increment,
  `idmunicipios` int(10) unsigned NOT NULL default '0',
  `denominacion` varchar(45) NOT NULL default '',
  `usuario` varchar(45) NOT NULL default '',
  `status` varchar(45) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idparroquia`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `partidas_cedentes_traslado`
-- 

CREATE TABLE `partidas_cedentes_traslado` (
  `idpartida_cedentes_traslado` bigint(20) unsigned NOT NULL auto_increment,
  `idtraslados_presupuestarios` int(10) unsigned NOT NULL default '0',
  `idmaestro_presupuesto` int(10) unsigned NOT NULL default '0',
  `monto_debitar` decimal(16,2) default NULL,
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idpartida_cedentes_traslado`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `partidas_credito_adicional`
-- 

CREATE TABLE `partidas_credito_adicional` (
  `idpartida_credito_adicional` bigint(20) unsigned NOT NULL auto_increment,
  `idcredito_adicional` int(10) unsigned NOT NULL default '0',
  `idmaestro_presupuesto` int(10) unsigned NOT NULL default '0',
  `monto_acreditar` decimal(16,2) default NULL,
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idpartida_credito_adicional`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `partidas_disminucion_presupuesto`
-- 

CREATE TABLE `partidas_disminucion_presupuesto` (
  `idpartida_disminucion_presupuesto` bigint(20) unsigned NOT NULL auto_increment,
  `iddisminucion_presupuesto` int(10) unsigned NOT NULL default '0',
  `idmaestro_presupuesto` int(10) unsigned NOT NULL default '0',
  `monto_debitar` decimal(16,2) default NULL,
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idpartida_disminucion_presupuesto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `partidas_orden_compra_servicio`
-- 

CREATE TABLE `partidas_orden_compra_servicio` (
  `idpartidas_orden_compra_servicio` int(10) unsigned NOT NULL auto_increment,
  `idorden_compra_servicio` int(10) unsigned NOT NULL,
  `idmaestro_presupuesto` int(10) unsigned NOT NULL,
  `monto` decimal(16,2) NOT NULL default '0.00',
  `monto_original` decimal(16,2) NOT NULL,
  `estado` varchar(45) NOT NULL,
  `status` varchar(1) NOT NULL,
  `usuario` varchar(45) NOT NULL,
  `fechayhora` datetime NOT NULL,
  PRIMARY KEY  (`idpartidas_orden_compra_servicio`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `partidas_orden_pago`
-- 

CREATE TABLE `partidas_orden_pago` (
  `idpartidas_orden_pago` int(10) unsigned NOT NULL auto_increment,
  `idorden_pago` int(10) unsigned NOT NULL,
  `idmaestro_presupuesto` int(10) NOT NULL COMMENT 'grabar el idRegistro de la tabla maestro_presupuesto',
  `monto` decimal(16,2) NOT NULL default '0.00',
  `estado` varchar(45) NOT NULL,
  `status` varchar(1) NOT NULL,
  `usuario` varchar(45) NOT NULL,
  `fechayhora` datetime NOT NULL,
  PRIMARY KEY  (`idpartidas_orden_pago`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `partidas_receptoras_rectificacion`
-- 

CREATE TABLE `partidas_receptoras_rectificacion` (
  `idpartida_receptoras_rectificacion` bigint(20) unsigned NOT NULL auto_increment,
  `idrectificacion_presupuesto` int(10) unsigned NOT NULL default '0',
  `idmaestro_presupuesto` int(10) unsigned NOT NULL default '0',
  `monto_acreditar` decimal(16,2) default NULL,
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idpartida_receptoras_rectificacion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `partidas_receptoras_traslado`
-- 

CREATE TABLE `partidas_receptoras_traslado` (
  `idpartida_receptoras_traslado` bigint(20) unsigned NOT NULL auto_increment,
  `idtraslados_presupuestarios` int(10) unsigned NOT NULL default '0',
  `idmaestro_presupuesto` int(10) unsigned NOT NULL default '0',
  `monto_acreditar` decimal(16,2) default NULL,
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idpartida_receptoras_traslado`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `partidas_rectificadoras`
-- 

CREATE TABLE `partidas_rectificadoras` (
  `idpartida_rectificadora` bigint(20) unsigned NOT NULL auto_increment,
  `idrectificacion_presupuesto` int(10) unsigned NOT NULL default '0',
  `idmaestro_presupuesto` int(10) unsigned NOT NULL default '0',
  `monto_debitar` decimal(16,2) default NULL,
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idpartida_rectificadora`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `partidas_requisiciones`
-- 

CREATE TABLE `partidas_requisiciones` (
  `idpartidas_requisiciones` int(10) unsigned NOT NULL auto_increment,
  `idrequisicion` int(10) unsigned NOT NULL,
  `idmaestro_presupuesto` int(10) unsigned NOT NULL,
  `monto` decimal(16,2) NOT NULL default '0.00',
  `monto_original` decimal(16,2) NOT NULL,
  `estado` varchar(45) NOT NULL,
  `status` varchar(1) NOT NULL,
  `usuario` varchar(45) NOT NULL,
  `fechayhora` datetime NOT NULL,
  PRIMARY KEY  (`idpartidas_requisiciones`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `partidas_simular_nomina`
-- 

CREATE TABLE `partidas_simular_nomina` (
  `idpartidas_simular_nomina` int(10) unsigned NOT NULL auto_increment,
  `idcertificacion_simular_nomina` int(10) unsigned NOT NULL,
  `idmaestro_presupuesto` int(10) unsigned NOT NULL,
  `monto` decimal(16,2) NOT NULL default '0.00',
  `monto_original` decimal(16,2) NOT NULL,
  `estado` varchar(45) NOT NULL,
  `status` varchar(1) NOT NULL,
  `usuario` varchar(45) NOT NULL,
  `fechayhora` datetime NOT NULL,
  PRIMARY KEY  (`idpartidas_simular_nomina`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `periodos_cedentes_trabajadores`
-- 

CREATE TABLE `periodos_cedentes_trabajadores` (
  `idperiodos_cedentes_trabajadores` int(10) unsigned NOT NULL auto_increment,
  `desde` date NOT NULL,
  `hasta` date NOT NULL,
  `tiempo` varchar(200) NOT NULL,
  `idtrabajador` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`idperiodos_cedentes_trabajadores`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `periodos_nomina`
-- 

CREATE TABLE `periodos_nomina` (
  `idperiodos_nomina` int(10) unsigned NOT NULL auto_increment,
  `idtipo_nomina` int(10) unsigned NOT NULL,
  `descripcion_periodo` varchar(100) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `anio` int(10) unsigned NOT NULL,
  `numero_periodos` int(10) unsigned NOT NULL,
  `dia_semana_comienza` int(10) unsigned NOT NULL,
  `periodo_activo` varchar(2) NOT NULL,
  `cierre_mes` int(11) NOT NULL,
  PRIMARY KEY  (`idperiodos_nomina`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `privilegios_acciones`
-- 

CREATE TABLE `privilegios_acciones` (
  `id_privilegio` int(10) unsigned NOT NULL auto_increment,
  `id_accion` int(10) unsigned NOT NULL,
  `id_usuario` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id_privilegio`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `privilegios_modulo`
-- 

CREATE TABLE `privilegios_modulo` (
  `id_privilegio` int(10) unsigned NOT NULL auto_increment,
  `id_modulo` int(10) unsigned NOT NULL,
  `id_usuario` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id_privilegio`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `profesion`
-- 

CREATE TABLE `profesion` (
  `idprofesion` int(10) unsigned NOT NULL auto_increment,
  `denominacion` varchar(85) NOT NULL default '',
  `abreviatura` varchar(10) default NULL,
  `usuario` varchar(20) NOT NULL default '',
  `status` varchar(1) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idprofesion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `programa`
-- 

CREATE TABLE `programa` (
  `idprograma` int(10) unsigned NOT NULL auto_increment,
  `codigo` varchar(2) NOT NULL default '',
  `denominacion` varchar(255) NOT NULL default '',
  `idsector` int(10) unsigned NOT NULL default '0',
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idprograma`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `proveedores_solicitud_cotizacion`
-- 

CREATE TABLE `proveedores_solicitud_cotizacion` (
  `idproveedores_solicitud_cotizacion` int(10) unsigned NOT NULL auto_increment,
  `idsolicitud_cotizacion` int(10) unsigned NOT NULL default '0',
  `idbeneficiarios` int(10) unsigned NOT NULL default '0',
  `ganador` varchar(1) default NULL COMMENT 'Si no esta chequeado es participante de lo contrario es GANADOR',
  `tipo_procedimiento` varchar(40) default NULL COMMENT 'Licitación General / Licitación selectiva / Adjudicación directa',
  `justificacion` varchar(200) default NULL,
  `nro_cotizacion` varchar(10) default NULL,
  `fecha_cotizacion` date default NULL,
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  `formato` varchar(45) NOT NULL default '',
  `articulo` varchar(45) NOT NULL default '',
  `numeral` varchar(45) NOT NULL default '',
  `descuento` varchar(2) NOT NULL default '',
  `garantia_servicio` varchar(2) NOT NULL default '',
  `calidad` varchar(2) NOT NULL default '',
  `gtos_flt` varchar(2) NOT NULL default '',
  `emp_local` varchar(2) NOT NULL default '',
  `experiencia` varchar(2) NOT NULL default '',
  `monto_total` decimal(16,2) NOT NULL default '0.00',
  PRIMARY KEY  (`idproveedores_solicitud_cotizacion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `proyecto`
-- 

CREATE TABLE `proyecto` (
  `idproyecto` int(10) unsigned NOT NULL auto_increment,
  `codigo` varchar(8) NOT NULL default '',
  `denominacion` varchar(255) NOT NULL default '',
  `idsub_programa` int(10) unsigned NOT NULL default '0',
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idproyecto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `ramos_articulos`
-- 

CREATE TABLE `ramos_articulos` (
  `idramo_articulo` int(10) unsigned NOT NULL auto_increment,
  `descripcion` varchar(45) NOT NULL default '',
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idramo_articulo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `rango_fecha_vencimiento_conceptos`
-- 

CREATE TABLE `rango_fecha_vencimiento_conceptos` (
  `idrango_fecha_vencimiento_conceptos` int(10) unsigned NOT NULL auto_increment,
  `idconcepto_solicitud_calculo` int(10) unsigned NOT NULL default '0',
  `fecha_vencimiento` date NOT NULL default '0000-00-00',
  `estado` varchar(45) NOT NULL default '',
  `monto_total` decimal(16,2) NOT NULL default '0.00',
  `monto_mora` decimal(16,2) NOT NULL default '0.00',
  PRIMARY KEY  (`idrango_fecha_vencimiento_conceptos`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `rango_fraccion_nomina`
-- 

CREATE TABLE `rango_fraccion_nomina` (
  `idrango_fraccion_nomina` int(11) NOT NULL auto_increment,
  `idtipo_nomina` int(11) NOT NULL,
  `numero` varchar(3) NOT NULL,
  `valor` decimal(16,2) NOT NULL,
  PRIMARY KEY  (`idrango_fraccion_nomina`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `rango_periodo_nomina`
-- 

CREATE TABLE `rango_periodo_nomina` (
  `idrango_periodo_nomina` int(11) NOT NULL auto_increment,
  `idperiodo_nomina` int(11) NOT NULL,
  `numero` varchar(3) NOT NULL,
  `desde` date NOT NULL,
  `hasta` date NOT NULL,
  `sugiere_pago` date NOT NULL,
  `semana` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`idrango_periodo_nomina`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `rango_tabla_constantes`
-- 

CREATE TABLE `rango_tabla_constantes` (
  `idrango_tabla_constantes` int(10) unsigned NOT NULL auto_increment,
  `idtabla_constantes` int(10) unsigned NOT NULL,
  `desde` varchar(45) NOT NULL,
  `hasta` varchar(45) NOT NULL,
  `valor` decimal(12,2) NOT NULL,
  PRIMARY KEY  (`idrango_tabla_constantes`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `rango_tabla_constantes_recaudacion`
-- 

CREATE TABLE `rango_tabla_constantes_recaudacion` (
  `idrango_tabla_constantes` int(10) unsigned NOT NULL auto_increment,
  `idtabla_constantes` int(10) unsigned NOT NULL,
  `desde` varchar(45) NOT NULL,
  `hasta` varchar(45) NOT NULL,
  `valor` decimal(12,2) NOT NULL,
  PRIMARY KEY  (`idrango_tabla_constantes`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `razones_devolucion`
-- 

CREATE TABLE `razones_devolucion` (
  `idrazones_devolucion` int(10) unsigned NOT NULL auto_increment,
  `descripcion` varchar(300) NOT NULL default '',
  `status` varchar(1) default NULL,
  `usuario` varchar(20) default NULL,
  `fechayhora` datetime default NULL,
  PRIMARY KEY  (`idrazones_devolucion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `recibir_documentos`
-- 

CREATE TABLE `recibir_documentos` (
  `idrecibir_documentos` int(10) unsigned NOT NULL auto_increment,
  `idremision_documentos` int(10) unsigned default NULL COMMENT 'Numero del documento recibido',
  `iddependencia_recibe` int(11) NOT NULL,
  `fecha_recibido` date default NULL,
  `recibido_por` varchar(50) NOT NULL COMMENT 'Quien recibe el momurandum',
  `ci_recibe` varchar(12) NOT NULL COMMENT 'Cedula de Identidad de quien recibe',
  `observaciones` longtext NOT NULL COMMENT 'Observaciones que amerite',
  `numero_documentos_recibidos` int(10) unsigned NOT NULL default '0' COMMENT 'Señalar el numero de documentos Recibidos anexos al memorandum',
  `estado` varchar(45) NOT NULL COMMENT 'Recibido',
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idrecibir_documentos`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `reconocimientos`
-- 

CREATE TABLE `reconocimientos` (
  `idreconocimientos` int(10) NOT NULL auto_increment,
  `idtrabajador` int(10) NOT NULL,
  `tipo` varchar(45) NOT NULL COMMENT 'select con tres opciones: Años de Servicio, Al Merito, Especial',
  `motivo` varchar(250) NOT NULL,
  `fecha` date NOT NULL,
  `numero_documento` varchar(50) NOT NULL,
  `fecha_entrega` date NOT NULL,
  `constancia_anexa` varchar(2) NOT NULL default '',
  `nombre_imagen` varchar(200) NOT NULL,
  `observaciones` varchar(250) NOT NULL,
  `usuario` varchar(20) NOT NULL,
  `fechayhora` datetime NOT NULL,
  PRIMARY KEY  (`idreconocimientos`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `rectificacion_presupuesto`
-- 

CREATE TABLE `rectificacion_presupuesto` (
  `idrectificacion_presupuesto` int(10) unsigned NOT NULL auto_increment,
  `nro_solicitud` varchar(12) default NULL,
  `fecha_solicitud` date default NULL,
  `nro_resolucion` varchar(25) default NULL,
  `fecha_resolucion` date default NULL,
  `fecha_ingreso` date default NULL,
  `justificacion` text,
  `anio` varchar(4) default NULL,
  `total_credito` decimal(16,2) NOT NULL default '0.00',
  `total_debito` decimal(16,2) NOT NULL default '0.00',
  `estado` varchar(20) default NULL,
  `ubicacion` varchar(45) default '0',
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idrectificacion_presupuesto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `registro_fotografico_bienes_nuevos`
-- 

CREATE TABLE `registro_fotografico_bienes_nuevos` (
  `idregistro_fotografico_bienes_nuevos` int(10) unsigned NOT NULL auto_increment,
  `idmovimientos_bienes_nuevos` int(10) unsigned NOT NULL default '0',
  `imagen` varchar(45) NOT NULL default '',
  `descripcion` text NOT NULL,
  PRIMARY KEY  (`idregistro_fotografico_bienes_nuevos`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `registro_fotografico_existentes_desincorporacion`
-- 

CREATE TABLE `registro_fotografico_existentes_desincorporacion` (
  `idregistro_fotografico_existentes_desincorporacion` int(10) unsigned NOT NULL auto_increment,
  `idmovimientos_existentes_desincorporacion` int(10) unsigned NOT NULL default '0',
  `imagen` varchar(45) NOT NULL default '',
  `descripcion` text NOT NULL,
  PRIMARY KEY  (`idregistro_fotografico_existentes_desincorporacion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `registro_fotografico_existentes_incorporacion`
-- 

CREATE TABLE `registro_fotografico_existentes_incorporacion` (
  `idregistro_fotografico_existentes_incorporacion` int(10) unsigned NOT NULL auto_increment,
  `idmovimientos_existentes_incorporacion` int(10) unsigned NOT NULL default '0',
  `imagen` varchar(45) NOT NULL default '',
  `descripcion` text NOT NULL,
  PRIMARY KEY  (`idregistro_fotografico_existentes_incorporacion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `registro_fotografico_trabajador`
-- 

CREATE TABLE `registro_fotografico_trabajador` (
  `idregistro_fotografico_trabajador` int(10) unsigned NOT NULL auto_increment,
  `idtrabajador` int(10) unsigned NOT NULL default '0',
  `nombre_imagen` varchar(200) NOT NULL default '',
  `principal` int(10) unsigned NOT NULL default '0',
  `descripcion` text NOT NULL,
  PRIMARY KEY  (`idregistro_fotografico_trabajador`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `registro_mercantil_contribuyente`
-- 

CREATE TABLE `registro_mercantil_contribuyente` (
  `idregistro_mercantil_contribuyente` int(10) unsigned NOT NULL auto_increment,
  `idcontribuyente` int(10) unsigned NOT NULL default '0',
  `tipo_persona` int(10) unsigned NOT NULL default '0',
  `tipo_empresa` int(10) unsigned NOT NULL default '0',
  `tipo_sociedad` int(10) unsigned NOT NULL default '0',
  `fecha_registro` date NOT NULL default '0000-00-00',
  `objeto` varchar(300) NOT NULL default '',
  `libro` varchar(45) NOT NULL default '',
  `folio` varchar(45) NOT NULL default '',
  `capital_social` decimal(16,2) NOT NULL default '0.00',
  `capital_suscrito` decimal(16,2) NOT NULL default '0.00',
  PRIMARY KEY  (`idregistro_mercantil_contribuyente`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `registro_transacciones`
-- 

CREATE TABLE `registro_transacciones` (
  `idregistro_transacciones` int(10) unsigned NOT NULL auto_increment,
  `tipo` text NOT NULL,
  `tabla` varchar(45) NOT NULL,
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  `estacion` varchar(45) NOT NULL default '',
  PRIMARY KEY  (`idregistro_transacciones`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `relacion_compra_requisicion`
-- 

CREATE TABLE `relacion_compra_requisicion` (
  `idrelacion_compra_requisicion` int(10) unsigned NOT NULL auto_increment,
  `idrequisicion` int(10) unsigned NOT NULL,
  `idorden_compra` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`idrelacion_compra_requisicion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `relacion_compra_solicitud_cotizacion`
-- 

CREATE TABLE `relacion_compra_solicitud_cotizacion` (
  `idrelacion_compra_solicitud_cotizacion` int(10) unsigned NOT NULL auto_increment,
  `idorden_compra` int(10) unsigned NOT NULL,
  `idsolicitud_cotizacion` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`idrelacion_compra_solicitud_cotizacion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `relacion_conceptos_periodos`
-- 

CREATE TABLE `relacion_conceptos_periodos` (
  `idrelacion_conceptos_periodos` int(10) unsigned NOT NULL auto_increment,
  `idtipo_nomina` int(10) unsigned NOT NULL default '0',
  `idconcepto` int(10) unsigned NOT NULL default '0',
  `idperiodo` int(10) unsigned NOT NULL default '0',
  `valor` decimal(16,2) NOT NULL default '0.00',
  PRIMARY KEY  (`idrelacion_conceptos_periodos`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `relacion_concepto_trabajador`
-- 

CREATE TABLE `relacion_concepto_trabajador` (
  `idrelacion_concepto_trabajador` int(10) unsigned NOT NULL auto_increment,
  `tabla` varchar(45) NOT NULL,
  `idconcepto` int(10) unsigned NOT NULL,
  `idtrabajador` int(10) unsigned NOT NULL,
  `valor` varchar(45) NOT NULL,
  `idtipo_nomina` int(10) unsigned NOT NULL,
  `fecha_limite_ejecucion` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`idrelacion_concepto_trabajador`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `relacion_documentos_recibidos`
-- 

CREATE TABLE `relacion_documentos_recibidos` (
  `idrelacion_documentos_recibidos` int(10) unsigned NOT NULL auto_increment,
  `idrecibir_documentos` int(10) unsigned NOT NULL default '0',
  `iddependencia_origen` varchar(45) NOT NULL COMMENT 'Dependencia de Origen del documento anexo al memorandum recibido',
  `idtipos_documentos` varchar(200) NOT NULL COMMENT 'Segun el Origen colocar el tipo: Orden de Compra / Servicio / Pago / Solicitud de Traslado...',
  `id_documento` varchar(45) NOT NULL COMMENT 'Numero del Documento individual anexo al memorandum',
  `tabla` varchar(45) default NULL,
  `estado` varchar(45) NOT NULL COMMENT 'Estado en que es recibido el Documento - Procesado / Conformado / Devuelto',
  `idrazones_devolucion` int(10) unsigned default NULL COMMENT 'Conexion a tabla de Razones de Devolucion para saber la razon por la que fue devuelto - si aplica',
  `observaciones` varchar(200) default NULL,
  `conformado_por` varchar(50) default NULL COMMENT 'Quien conformo el documento',
  `ci_conformador` varchar(12) default '' COMMENT 'Cedula de Identidad de quien conformo',
  `status` varchar(1) default NULL,
  `usuario` varchar(20) default NULL,
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idrelacion_documentos_recibidos`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `relacion_documentos_remision`
-- 

CREATE TABLE `relacion_documentos_remision` (
  `idrelacion_documentos_remision` int(10) unsigned NOT NULL auto_increment,
  `idremision_documentos` int(10) unsigned NOT NULL default '0',
  `iddependencia_origen` int(10) NOT NULL COMMENT 'Dependencia de origen del documento',
  `tabla` varchar(45) NOT NULL,
  `idtipos_documentos` varchar(45) NOT NULL COMMENT 'Tipo de documento - id del tipo de documento creado en tipos de documentos',
  `id_documento` int(10) NOT NULL COMMENT 'Id del documento que se esta remitiendo',
  `estado` varchar(45) NOT NULL COMMENT 'Enviado / Recibido / Conformado / Devuelto',
  `idrazones_devolucion` int(10) unsigned default NULL,
  `observaciones` varchar(200) default NULL,
  `conformado_por` varchar(60) default NULL COMMENT 'Indicar quien conformo el documento',
  `ci_conformador` varchar(12) default NULL COMMENT 'Cedula de Identidad de quien conformo el documento',
  `status` varchar(1) default NULL,
  `usuario` varchar(20) default NULL,
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idrelacion_documentos_remision`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `relacion_formula_conceptos_nomina`
-- 

CREATE TABLE `relacion_formula_conceptos_nomina` (
  `idrelacion_formula_conceptos_nomina` int(10) unsigned NOT NULL auto_increment,
  `idconcepto_nomina` int(10) unsigned NOT NULL,
  `valor` varchar(45) NOT NULL,
  `valor_oculto` varchar(45) NOT NULL,
  `orden` int(10) unsigned NOT NULL,
  `destino` varchar(45) NOT NULL,
  PRIMARY KEY  (`idrelacion_formula_conceptos_nomina`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `relacion_generar_nomina`
-- 

CREATE TABLE `relacion_generar_nomina` (
  `idrelacion_generar_nomina` int(10) unsigned NOT NULL auto_increment,
  `idgenerar_nomina` int(10) unsigned NOT NULL,
  `idtrabajador` int(10) unsigned NOT NULL,
  `idconcepto` int(10) unsigned NOT NULL,
  `tabla` varchar(45) NOT NULL,
  `total` decimal(16,2) NOT NULL,
  `cantidad` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`idrelacion_generar_nomina`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `relacion_hoja_tiempo_trabajador`
-- 

CREATE TABLE `relacion_hoja_tiempo_trabajador` (
  `idrelacion_hoja_tiempo_trabajador` int(10) unsigned NOT NULL auto_increment,
  `idtrabajador` int(10) unsigned NOT NULL,
  `idhoja_tiempo` int(10) unsigned NOT NULL,
  `horas` decimal(16,2) NOT NULL,
  PRIMARY KEY  (`idrelacion_hoja_tiempo_trabajador`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `relacion_impuestos_ordenes_compras`
-- 

CREATE TABLE `relacion_impuestos_ordenes_compras` (
  `idrelacion_impuestos_ordenes_compras` int(10) unsigned NOT NULL auto_increment,
  `idorden_compra_servicio` int(10) unsigned NOT NULL,
  `idimpuestos` int(10) unsigned NOT NULL,
  `base_calculo` int(11) NOT NULL,
  `base_calculo_original` decimal(16,2) NOT NULL,
  `porcentaje` decimal(6,2) NOT NULL,
  `total` decimal(16,2) NOT NULL default '0.00',
  `estado` varchar(45) NOT NULL,
  PRIMARY KEY  (`idrelacion_impuestos_ordenes_compras`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `relacion_impuestos_requisiciones`
-- 

CREATE TABLE `relacion_impuestos_requisiciones` (
  `idrelacion_impuestos_requisiciones` int(10) unsigned NOT NULL auto_increment,
  `idrequisicion` int(10) unsigned NOT NULL,
  `idimpuestos` int(10) unsigned NOT NULL,
  `base_calculo` int(11) NOT NULL,
  `base_calculo_original` decimal(16,2) NOT NULL,
  `porcentaje` decimal(6,2) NOT NULL,
  `total` decimal(16,2) NOT NULL default '0.00',
  `estado` varchar(45) NOT NULL,
  PRIMARY KEY  (`idrelacion_impuestos_requisiciones`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `relacion_instalaciones_fijas`
-- 

CREATE TABLE `relacion_instalaciones_fijas` (
  `idrelacion_instalaciones_fijas` int(10) unsigned NOT NULL auto_increment,
  `idinstalaciones_fijas` int(10) unsigned NOT NULL,
  `descripcion` text NOT NULL,
  `valor` decimal(10,0) NOT NULL,
  `fecha` date NOT NULL,
  PRIMARY KEY  (`idrelacion_instalaciones_fijas`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `relacion_orden_pago_retencion`
-- 

CREATE TABLE `relacion_orden_pago_retencion` (
  `idrelacion_orden_pago_retencion` int(10) unsigned NOT NULL auto_increment,
  `idretencion` int(10) unsigned NOT NULL,
  `idorden_pago` int(10) unsigned NOT NULL,
  `status` varchar(45) NOT NULL,
  `fechayhora` datetime NOT NULL,
  `usuario` varchar(45) NOT NULL,
  PRIMARY KEY  (`idrelacion_orden_pago_retencion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `relacion_pago_compromisos`
-- 

CREATE TABLE `relacion_pago_compromisos` (
  `idrelacion_pago_compromisos` int(10) unsigned NOT NULL auto_increment,
  `idorden_pago` int(10) unsigned NOT NULL,
  `idorden_compra_servicio` int(10) unsigned NOT NULL,
  `nro_factura` varchar(45) NOT NULL,
  `fecha_factura` varchar(45) NOT NULL,
  `nro_control` varchar(45) NOT NULL,
  PRIMARY KEY  (`idrelacion_pago_compromisos`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `relacion_requisicion_solicitud_cotizacion`
-- 

CREATE TABLE `relacion_requisicion_solicitud_cotizacion` (
  `idrelacion_requisicion_solicitud_cotizacion` int(10) unsigned NOT NULL auto_increment,
  `idrequisicion` int(10) unsigned NOT NULL,
  `idsolicitud_cotizacion` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`idrelacion_requisicion_solicitud_cotizacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `relacion_requisitos_contribuyente`
-- 

CREATE TABLE `relacion_requisitos_contribuyente` (
  `idrelacion_requisitos_contribuyente` int(10) unsigned NOT NULL auto_increment,
  `idrequisitos` int(10) unsigned NOT NULL default '0',
  `idcontribuyente` int(10) unsigned NOT NULL default '0',
  `fecha_vencimiento` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`idrelacion_requisitos_contribuyente`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `relacion_retenciones`
-- 

CREATE TABLE `relacion_retenciones` (
  `idrelacion_retenciones` int(10) unsigned NOT NULL auto_increment,
  `idretenciones` int(10) unsigned NOT NULL default '0' COMMENT 'Tabla retenciones con el documento de retenciones que se esta elaborando',
  `idtipo_retencion` int(10) unsigned NOT NULL default '0' COMMENT 'Tabla tipo_retencion con la retencion aplicada',
  `porcentaje_aplicado` decimal(6,2) NOT NULL default '0.00' COMMENT 'porcentaje de retencion aplicado (si aplica)',
  `base_calculo` decimal(16,2) NOT NULL default '0.00' COMMENT 'Base del calculo de la retencion',
  `sustraendo` decimal(16,2) NOT NULL default '0.00' COMMENT 'monto a restar a la retencion ingresado por el usuario',
  `factor` decimal(16,2) NOT NULL default '0.00' COMMENT 'factor de calculo a restar a la retencion ingresado por el usuario',
  `monto_retenido` decimal(16,2) NOT NULL default '0.00' COMMENT 'monto de la retencion aplicada',
  `numero_retencion` int(10) unsigned default NULL COMMENT 'numero de retencion generado por el contador de la tabla tipo_retencion',
  `periodo` varchar(6) NOT NULL,
  `codigo_concepto` varchar(45) NOT NULL,
  `porcentaje_impuesto` decimal(6,2) default NULL COMMENT 'Porcentaje de calculo del impuesto traido desde Orden de Compra',
  `status` varchar(1) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  `usuario` varchar(45) NOT NULL default '',
  `fecha_comprobante` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`idrelacion_retenciones`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `relacion_retenciones_externas`
-- 

CREATE TABLE `relacion_retenciones_externas` (
  `idrelacion_retenciones_externas` int(10) unsigned NOT NULL auto_increment,
  `numero_orden` varchar(45) NOT NULL default '',
  `numero_factura` varchar(45) NOT NULL default '',
  `numero_control` varchar(45) NOT NULL default '',
  `fecha_factura` date NOT NULL default '0000-00-00',
  `idtipo_retencion` int(10) unsigned NOT NULL default '0',
  `codigo_islr` varchar(45) NOT NULL default '',
  `base_calculo` decimal(16,2) NOT NULL default '0.00',
  `alicuota` decimal(16,2) NOT NULL default '0.00',
  `factor` decimal(16,2) NOT NULL default '0.00',
  `monto_retenido` decimal(16,2) NOT NULL default '0.00',
  `porcentaje` decimal(16,2) NOT NULL default '0.00',
  `status` varchar(45) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  `usuario` varchar(45) NOT NULL default '',
  `idretencion` int(11) NOT NULL,
  `exento` decimal(16,2) NOT NULL default '0.00',
  `sub_total` decimal(16,2) NOT NULL default '0.00',
  `impuesto` decimal(16,2) NOT NULL default '0.00',
  `total` decimal(16,2) NOT NULL default '0.00',
  `divisor` decimal(16,2) NOT NULL default '0.00',
  `periodo` int(10) unsigned NOT NULL default '0',
  `numero_retencion` int(10) unsigned NOT NULL default '0',
  `fecha_orden` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`idrelacion_retenciones_externas`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `relacion_simular_nomina`
-- 

CREATE TABLE `relacion_simular_nomina` (
  `idrelacion_simular_nomina` int(10) unsigned NOT NULL auto_increment,
  `idsimular_nomina` int(10) unsigned NOT NULL,
  `idtrabajador` int(10) unsigned NOT NULL,
  `idconcepto` int(10) unsigned NOT NULL,
  `tabla` varchar(45) NOT NULL,
  `total` decimal(16,2) NOT NULL,
  PRIMARY KEY  (`idrelacion_simular_nomina`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `relacion_tipo_nomina_trabajador`
-- 

CREATE TABLE `relacion_tipo_nomina_trabajador` (
  `idrelacion_tipo_nomina_trabajador` int(10) unsigned NOT NULL auto_increment,
  `idtrabajador` int(10) unsigned NOT NULL,
  `idtipo_nomina` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`idrelacion_tipo_nomina_trabajador`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `remision_documentos`
-- 

CREATE TABLE `remision_documentos` (
  `idremision_documentos` int(10) unsigned NOT NULL auto_increment,
  `iddependencia_origen` int(11) NOT NULL,
  `numero_documento` varchar(45) default NULL,
  `fecha_elaboracion` date default NULL,
  `fecha_envio` date default NULL,
  `iddependencia_destino` int(10) NOT NULL default '0' COMMENT 'para quien va dirigido el documento',
  `asunto` varchar(200) NOT NULL default '',
  `justificacion` longtext NOT NULL,
  `numero_documentos_enviados` int(10) unsigned NOT NULL default '0',
  `estado` varchar(45) NOT NULL COMMENT 'Enviado / cambia al ser recibido a Recibido',
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idremision_documentos`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `rendicion_cuentas`
-- 

CREATE TABLE `rendicion_cuentas` (
  `idrendicion_cuentas` int(10) unsigned NOT NULL auto_increment,
  `anio` int(11) NOT NULL,
  `mes` varchar(45) NOT NULL,
  `concepto` text NOT NULL,
  `idcategoria_programatica` int(11) NOT NULL,
  `idtipo_presupuesto` int(11) NOT NULL,
  `idfuente_financiamiento` int(11) NOT NULL,
  `idordinal` int(11) NOT NULL,
  PRIMARY KEY  (`idrendicion_cuentas`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `rendicion_cuentas_partidas`
-- 

CREATE TABLE `rendicion_cuentas_partidas` (
  `idrendiciones_cuentas_partidas` int(10) unsigned NOT NULL auto_increment,
  `idrendicion_cuentas` int(11) NOT NULL,
  `idmaestro_presupuesto` int(11) NOT NULL,
  `monto_original_periodo` decimal(16,2) NOT NULL,
  `disminucion_periodo` decimal(16,2) NOT NULL default '0.00',
  `aumento_periodo` decimal(16,2) NOT NULL,
  `monto_actual_periodo` decimal(16,2) NOT NULL,
  `total_compromisos_periodo` decimal(16,2) NOT NULL,
  `total_causados_periodo` decimal(16,2) NOT NULL,
  `total_pagados_periodo` decimal(16,2) NOT NULL,
  `aumento_original` decimal(16,2) NOT NULL default '0.00',
  `disminucion_original` decimal(16,2) NOT NULL default '0.00',
  `compromisos_original` decimal(16,2) NOT NULL default '0.00',
  `pagados_original` decimal(16,2) NOT NULL default '0.00',
  `causados_original` decimal(16,2) NOT NULL default '0.00',
  `reversa` decimal(16,2) NOT NULL,
  `disponible_periodo` decimal(16,2) NOT NULL,
  PRIMARY KEY  (`idrendiciones_cuentas_partidas`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `requisicion`
-- 

CREATE TABLE `requisicion` (
  `idrequisicion` int(10) unsigned NOT NULL auto_increment,
  `numero_requisicion` varchar(12) NOT NULL default '',
  `fecha_orden` date NOT NULL,
  `tipo` int(10) unsigned NOT NULL default '0',
  `fecha_elaboracion` date NOT NULL,
  `proceso` varchar(20) NOT NULL,
  `numero_documento` varchar(12) default NULL,
  `idbeneficiarios` int(10) unsigned NOT NULL default '0',
  `idcategoria_programatica` int(10) unsigned NOT NULL default '0',
  `anio` varchar(4) NOT NULL default '0000',
  `idfuente_financiamiento` int(10) unsigned NOT NULL,
  `idtipo_presupuesto` int(10) unsigned NOT NULL,
  `idordinal` int(10) unsigned NOT NULL,
  `justificacion` text,
  `observaciones` longtext,
  `ordenado_por` varchar(40) default NULL,
  `cedula_ordenado` varchar(12) default NULL,
  `nro_items` int(10) default '0',
  `exento` decimal(16,2) NOT NULL default '0.00',
  `sub_total` decimal(16,2) default '0.00',
  `descuento` decimal(16,2) NOT NULL,
  `exento_original` decimal(16,2) NOT NULL,
  `sub_total_original` decimal(16,2) NOT NULL,
  `impuesto` decimal(16,2) default '0.00',
  `total` decimal(16,2) default '0.00',
  `estado` varchar(20) default NULL COMMENT 'En elaboraciÃƒÂ³n / Procesado / Anulado / Cancelado / Devuelto',
  `idrazones_devolucion` int(10) unsigned NOT NULL default '0',
  `observaciones_devolucion` longtext,
  `numero_remision` varchar(12) default NULL COMMENT 'Numero generado cuando se emita el memorandum de remisiones',
  `fecha_remision` date NOT NULL COMMENT 'fecha del memorandum de remision',
  `recibido_por` varchar(40) default NULL COMMENT 'Identificacion de quien recibe el memorandum de remision',
  `cedula_recibido` varchar(12) default NULL COMMENT 'Cedula de quien recibe el memroandum de remision',
  `fecha_recibido` date NOT NULL COMMENT 'Fecha de recibido el memorandum de remision',
  `ubicacion` varchar(20) default '0' COMMENT 'Presupuesto / Administracion / Contabilidad / Tesoreria / Bienes / Cancelado',
  `status` varchar(1) default NULL,
  `usuario` varchar(20) default NULL,
  `fechayhora` datetime default NULL,
  `duplicados` int(10) unsigned NOT NULL default '0',
  `nro_factura` varchar(45) NOT NULL,
  `fecha_factura` varchar(45) NOT NULL,
  `nro_control` varchar(45) NOT NULL,
  `codigo_referencia` varchar(12) NOT NULL,
  `cofinanciamiento` varchar(2) NOT NULL default '',
  PRIMARY KEY  (`idrequisicion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `requisitos`
-- 

CREATE TABLE `requisitos` (
  `idrequisitos` int(10) unsigned NOT NULL auto_increment,
  `denominacion` text NOT NULL,
  `vencimiento` varchar(2) NOT NULL default '',
  `bloquea_proceso` varchar(2) NOT NULL default '',
  PRIMARY KEY  (`idrequisitos`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `retenciones`
-- 

CREATE TABLE `retenciones` (
  `idretenciones` int(10) unsigned NOT NULL auto_increment,
  `contador_retencion` int(10) unsigned NOT NULL default '0' COMMENT 'Numero para saber qeu numero de retencion se esta aplicando al compromiso (pago total=1, pago_parcial = n retenciones)',
  `fecha_retencion` date default NULL COMMENT 'Fecha de elaboracion de la retencion',
  `fecha_aplicacion_retencion` date NOT NULL,
  `iddocumento` int(10) unsigned NOT NULL default '0' COMMENT 'Id del documento a retener (idorden_compra_servicio)',
  `numero_documento` varchar(45) NOT NULL default '' COMMENT 'Numero del documento (numero de orden)',
  `total_retenido` decimal(16,2) NOT NULL default '0.00' COMMENT 'monto de la retencion aplicada',
  `numero_factura` varchar(45) default NULL COMMENT 'Numero de factura a la que se retubo',
  `numero_control` varchar(45) default NULL COMMENT 'Numero de control',
  `fecha_factura` date default NULL COMMENT 'Fecha de la factura',
  `exento` decimal(16,2) default NULL COMMENT 'Monto exento de la factura',
  `base` decimal(16,2) default NULL COMMENT 'Base imponible ',
  `impuesto` decimal(16,2) default NULL COMMENT 'Impuesto ',
  `porcentaje_impuesto` decimal(6,2) default NULL COMMENT 'Porcentaje de calculo del impuesto',
  `total` decimal(16,2) default NULL COMMENT 'Total',
  `status` varchar(1) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  `usuario` varchar(45) NOT NULL default '',
  `tipo_pago` varchar(45) NOT NULL,
  `estado` varchar(45) NOT NULL,
  `idbeneficiarios` int(10) unsigned NOT NULL default '0',
  `idente_gubernamental` int(10) unsigned NOT NULL default '0',
  `tipo_retencion` int(10) unsigned NOT NULL default '0',
  `nro_retencion` varchar(45) NOT NULL default '',
  PRIMARY KEY  (`idretenciones`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `rubro_cuentas_contables`
-- 

CREATE TABLE `rubro_cuentas_contables` (
  `idrubro_cuentas_contables` int(10) unsigned NOT NULL auto_increment,
  `idsubgrupo` int(11) NOT NULL default '0',
  `codigo` varchar(45) NOT NULL default '',
  `denominacion` varchar(45) NOT NULL default '',
  PRIMARY KEY  (`idrubro_cuentas_contables`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `sanciones`
-- 

CREATE TABLE `sanciones` (
  `idsanciones` int(10) NOT NULL auto_increment,
  `idtrabajador` int(10) NOT NULL,
  `tipo` varchar(45) NOT NULL COMMENT 'select con cuatro opciones: Exorto, Incumplimiento, Falta a la Moral, Irrespeto',
  `motivo` varchar(250) NOT NULL,
  `fecha` date NOT NULL,
  `numero_documento` varchar(50) NOT NULL,
  `fecha_entrega` date NOT NULL,
  `constancia_anexa` varchar(2) NOT NULL default '',
  `nombre_imagen` varchar(200) NOT NULL,
  `observaciones` varchar(250) NOT NULL,
  `usuario` varchar(20) NOT NULL,
  `fechayhora` datetime NOT NULL,
  PRIMARY KEY  (`idsanciones`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `secciones_catalogo_bienes`
-- 

CREATE TABLE `secciones_catalogo_bienes` (
  `idsecciones_catalogo_bienes` int(10) unsigned NOT NULL auto_increment,
  `idsubgrupo_catalogo_bienes` int(10) NOT NULL,
  `codigo` varchar(6) NOT NULL default '',
  `denominacion` varchar(80) NOT NULL default '',
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idsecciones_catalogo_bienes`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `sector`
-- 

CREATE TABLE `sector` (
  `idSector` int(10) unsigned NOT NULL auto_increment,
  `codigo` varchar(2) NOT NULL default '',
  `denominacion` varchar(255) NOT NULL default '',
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) default NULL,
  `fechayhora` datetime default NULL,
  PRIMARY KEY  (`idSector`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `sectores`
-- 

CREATE TABLE `sectores` (
  `idsectores` int(10) unsigned NOT NULL auto_increment,
  `idparroquia` int(10) unsigned NOT NULL default '0',
  `denominacion` varchar(45) NOT NULL default '',
  `usuario` varchar(45) NOT NULL default '',
  `status` varchar(45) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idsectores`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `series`
-- 

CREATE TABLE `series` (
  `idserie` varchar(5) NOT NULL default '',
  `idgrupo` varchar(5) NOT NULL,
  `denominacion` varchar(80) NOT NULL default '',
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idserie`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 10240 kB; (`idgrupo`) REFER `rrhh/grupos`(`idgr';

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `simular_nomina`
-- 

CREATE TABLE `simular_nomina` (
  `idsimular_nomina` int(10) unsigned NOT NULL auto_increment,
  `descripcion` text NOT NULL,
  `idtipo_nomina` int(10) unsigned NOT NULL,
  `idperiodo` int(10) unsigned NOT NULL,
  `estado` varchar(45) NOT NULL,
  `fecha_elaboracion` date NOT NULL,
  `status` varchar(45) NOT NULL,
  `usuario` varchar(45) NOT NULL,
  `fechayhora` datetime NOT NULL,
  `fecha_procesado` date NOT NULL,
  `idbeneficiarios` int(10) unsigned NOT NULL,
  `idcertificacion_simular_nomina` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`idsimular_nomina`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `snc_actividades`
-- 

CREATE TABLE `snc_actividades` (
  `idsnc_actividades` int(10) unsigned NOT NULL auto_increment,
  `descripcion` varchar(45) NOT NULL default '',
  `sigla` varchar(22) NOT NULL default '',
  `status` varchar(1) default NULL,
  `usuario` varchar(20) default NULL,
  `fechayhora` datetime default NULL,
  PRIMARY KEY  (`idsnc_actividades`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `snc_detalle_grupo`
-- 

CREATE TABLE `snc_detalle_grupo` (
  `idsnc_detalle_grupo` int(10) unsigned NOT NULL auto_increment,
  `descripcion` varchar(150) NOT NULL,
  `idsnc_grupo_actividad` int(10) unsigned NOT NULL default '0',
  `codigo` varchar(9) NOT NULL default '',
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idsnc_detalle_grupo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `snc_familia_actividad`
-- 

CREATE TABLE `snc_familia_actividad` (
  `idsnc_familia_actividad` int(10) unsigned NOT NULL auto_increment,
  `idsnc_actividades` int(10) unsigned default NULL,
  `codigo` varchar(4) NOT NULL default '',
  `descripcion` varchar(100) NOT NULL default '',
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idsnc_familia_actividad`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `snc_grupo_actividad`
-- 

CREATE TABLE `snc_grupo_actividad` (
  `idsnc_grupo_actividad` int(10) unsigned NOT NULL auto_increment,
  `idsnc_familia_actividad` int(10) unsigned NOT NULL default '0',
  `codigo` varchar(6) NOT NULL default '',
  `descripcion` varchar(150) NOT NULL default '',
  `status` varchar(1) default NULL,
  `usuario` varchar(20) default NULL,
  `fechayhora` datetime default NULL,
  PRIMARY KEY  (`idsnc_grupo_actividad`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `socios_contribuyente`
-- 

CREATE TABLE `socios_contribuyente` (
  `idsocios_contribuyente` int(10) unsigned NOT NULL auto_increment,
  `idcontribuyente` int(10) unsigned NOT NULL default '0',
  `nombre_socio` varchar(45) NOT NULL default '',
  `ci_socio` varchar(45) NOT NULL default '',
  `cargo_socio` varchar(45) NOT NULL default '',
  `acciones_socio` varchar(45) NOT NULL default '',
  `status` varchar(45) NOT NULL default '',
  `usuario` varchar(45) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idsocios_contribuyente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `solicitud_calculo`
-- 

CREATE TABLE `solicitud_calculo` (
  `idsolicitud_calculo` int(10) unsigned NOT NULL auto_increment,
  `idcontribuyente` int(10) unsigned NOT NULL default '0',
  `idtipo_solicitud` int(10) unsigned NOT NULL default '0',
  `anio` int(10) unsigned NOT NULL default '0',
  `desde` date NOT NULL default '0000-00-00',
  `hasta` date NOT NULL default '0000-00-00',
  `vence` date NOT NULL default '0000-00-00',
  `descripcion` text NOT NULL,
  `fecha_solicitud` date NOT NULL default '0000-00-00',
  `numero_solicitud` varchar(45) NOT NULL default '0',
  `estado` varchar(45) NOT NULL default '',
  PRIMARY KEY  (`idsolicitud_calculo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `solicitud_cotizacion`
-- 

CREATE TABLE `solicitud_cotizacion` (
  `idsolicitud_cotizacion` int(10) unsigned NOT NULL auto_increment,
  `numero` varchar(12) NOT NULL default '',
  `fecha_solicitud` date NOT NULL,
  `tipo` int(10) unsigned NOT NULL,
  `justificacion` varchar(100) default NULL,
  `observaciones` longtext,
  `ordenado_por` varchar(40) default NULL,
  `cedula_ordenado` varchar(12) default NULL,
  `nro_items` int(10) default '0',
  `exento` decimal(16,2) NOT NULL default '0.00',
  `sub_total` decimal(16,2) default NULL,
  `impuesto` decimal(16,2) default NULL,
  `total` decimal(16,2) default NULL,
  `estado` varchar(20) default NULL COMMENT 'En elaboración / Procesado / Anulado /  ',
  `nro_orden` varchar(20) default '',
  `status` varchar(1) default NULL,
  `usuario` varchar(20) default NULL,
  `fechayhora` datetime default NULL,
  PRIMARY KEY  (`idsolicitud_cotizacion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `subcuenta_primer_cuentas_contables`
-- 

CREATE TABLE `subcuenta_primer_cuentas_contables` (
  `idsubcuenta_primer_cuentas_contables` int(10) unsigned NOT NULL auto_increment,
  `idcuenta` int(11) NOT NULL default '0',
  `codigo` varchar(45) NOT NULL default '',
  `denominacion` varchar(45) NOT NULL default '',
  PRIMARY KEY  (`idsubcuenta_primer_cuentas_contables`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `subcuenta_segundo_cuentas_contables`
-- 

CREATE TABLE `subcuenta_segundo_cuentas_contables` (
  `idsubcuenta_segundo_cuentas_contables` int(10) unsigned NOT NULL auto_increment,
  `idsubcuenta_primer` int(11) NOT NULL default '0',
  `codigo` varchar(45) NOT NULL default '',
  `denominacion` varchar(45) NOT NULL default '',
  PRIMARY KEY  (`idsubcuenta_segundo_cuentas_contables`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `subgrupo_catalogo_bienes`
-- 

CREATE TABLE `subgrupo_catalogo_bienes` (
  `idsubgrupo_catalogo_bienes` int(10) unsigned NOT NULL auto_increment,
  `idgrupo_catalogo_bienes` int(10) NOT NULL,
  `codigo` varchar(4) NOT NULL default '',
  `denominacion` varchar(200) NOT NULL,
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idsubgrupo_catalogo_bienes`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `subgrupo_cuentas_contables`
-- 

CREATE TABLE `subgrupo_cuentas_contables` (
  `idsubgrupo_cuentas_contables` int(10) unsigned NOT NULL auto_increment,
  `idgrupo` int(11) NOT NULL default '0',
  `codigo` varchar(45) NOT NULL default '',
  `denominacion` varchar(45) NOT NULL default '',
  PRIMARY KEY  (`idsubgrupo_cuentas_contables`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `sub_programa`
-- 

CREATE TABLE `sub_programa` (
  `idsub_programa` int(10) unsigned NOT NULL auto_increment,
  `codigo` varchar(2) NOT NULL default '',
  `denominacion` varchar(255) NOT NULL default '',
  `idPrograma` int(10) unsigned NOT NULL default '0',
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idsub_programa`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `tabla_aguinaldos`
-- 

CREATE TABLE `tabla_aguinaldos` (
  `idtabla_aguinaldos` int(10) unsigned NOT NULL auto_increment,
  `idtrabajador` int(10) unsigned NOT NULL,
  `tipo` varchar(45) NOT NULL,
  `periodo` varchar(45) NOT NULL,
  `dias` int(10) unsigned NOT NULL,
  `sueldo` decimal(16,2) NOT NULL,
  `usuario` varchar(45) NOT NULL,
  `fechayhora` datetime NOT NULL,
  `pc` varchar(45) NOT NULL,
  PRIMARY KEY  (`idtabla_aguinaldos`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `tabla_constantes`
-- 

CREATE TABLE `tabla_constantes` (
  `idtabla_constantes` int(10) unsigned NOT NULL auto_increment,
  `codigo` varchar(45) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `desde` date NOT NULL,
  `hasta` date NOT NULL,
  `unidad` varchar(45) NOT NULL,
  `usuario` varchar(45) NOT NULL,
  `fechayhora` datetime NOT NULL,
  `status` varchar(45) NOT NULL,
  PRIMARY KEY  (`idtabla_constantes`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `tabla_constantes_recaudacion`
-- 

CREATE TABLE `tabla_constantes_recaudacion` (
  `idtabla_constantes` int(10) unsigned NOT NULL auto_increment,
  `codigo` varchar(45) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `desde` date NOT NULL,
  `hasta` date NOT NULL,
  `unidad` varchar(45) NOT NULL,
  `usuario` varchar(45) NOT NULL,
  `fechayhora` datetime NOT NULL,
  `status` varchar(45) NOT NULL,
  PRIMARY KEY  (`idtabla_constantes`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `tabla_deducciones`
-- 

CREATE TABLE `tabla_deducciones` (
  `idtabla_deducciones` int(10) unsigned NOT NULL auto_increment,
  `idtrabajador` int(10) unsigned NOT NULL,
  `tipo` varchar(45) NOT NULL,
  `periodo` varchar(45) NOT NULL,
  `monto` decimal(16,2) NOT NULL,
  `usuario` varchar(45) NOT NULL,
  `fechayhora` datetime NOT NULL,
  `pc` varchar(45) NOT NULL,
  PRIMARY KEY  (`idtabla_deducciones`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `tabla_intereses`
-- 

CREATE TABLE `tabla_intereses` (
  `idtabla_intereses` int(10) unsigned NOT NULL auto_increment,
  `anio` int(11) NOT NULL,
  `mes` varchar(45) NOT NULL,
  `interes` decimal(16,2) NOT NULL,
  `usuario` varchar(45) NOT NULL,
  `fechayhora` datetime NOT NULL,
  `pc` varchar(45) NOT NULL,
  PRIMARY KEY  (`idtabla_intereses`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `tabla_prestaciones`
-- 

CREATE TABLE `tabla_prestaciones` (
  `idtabla_prestaciones` int(10) unsigned NOT NULL auto_increment,
  `anio` int(10) unsigned NOT NULL,
  `mes` varchar(45) NOT NULL,
  `sueldo` decimal(16,2) NOT NULL,
  `usuario` varchar(45) NOT NULL,
  `fechayhora` datetime NOT NULL,
  `pc` varchar(45) NOT NULL,
  `idtrabajador` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`idtabla_prestaciones`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `tabla_vacaciones`
-- 

CREATE TABLE `tabla_vacaciones` (
  `idtabla_vacaciones` int(10) unsigned NOT NULL auto_increment,
  `tipo` varchar(45) NOT NULL,
  `periodo` varchar(45) NOT NULL,
  `dias` int(10) unsigned NOT NULL,
  `sueldo` decimal(16,2) NOT NULL,
  `idtrabajador` int(10) unsigned NOT NULL,
  `usuario` varchar(45) NOT NULL,
  `fechayhora` datetime NOT NULL,
  `pc` varchar(45) NOT NULL,
  PRIMARY KEY  (`idtabla_vacaciones`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `temporal_conceptos`
-- 

CREATE TABLE `temporal_conceptos` (
  `idtemporal_conceptos` int(10) unsigned NOT NULL auto_increment,
  `idsession` varchar(45) NOT NULL,
  `valor` text NOT NULL,
  `valor_oculto` varchar(45) NOT NULL,
  `destino` varchar(45) NOT NULL,
  `orden` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`idtemporal_conceptos`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `terrenos`
-- 

CREATE TABLE `terrenos` (
  `idterrenos` int(10) unsigned NOT NULL auto_increment,
  `idtipo_movimiento` int(11) NOT NULL,
  `iddetalle_catalogo_bienes` int(11) NOT NULL,
  `estado_municipio` varchar(100) NOT NULL,
  `denominacion_inmueble` text NOT NULL,
  `clasificacion_agricultura` varchar(45) NOT NULL,
  `clasificacion_ganaderia` varchar(45) NOT NULL,
  `clasificacion_mixto_agropecuario` varchar(45) NOT NULL,
  `clasificacion_otros` text NOT NULL,
  `ubicacion_municipio` varchar(100) NOT NULL,
  `ubicacion_territorio` varchar(100) NOT NULL,
  `area_total_terreno_hectarias` varchar(45) NOT NULL,
  `area_total_terreno_metros` varchar(45) NOT NULL,
  `area_construccion_metros` varchar(45) NOT NULL,
  `tipografia_plana` int(11) NOT NULL,
  `tipografia_semiplana` int(11) NOT NULL,
  `tipografia_pendiente` int(11) NOT NULL,
  `tipografia_muypendiente` int(11) NOT NULL,
  `cultivos_permanentes` int(11) NOT NULL,
  `cultivos_deforestados` int(11) NOT NULL,
  `otros_bosques` int(11) NOT NULL,
  `otros_tierras_incultas` int(11) NOT NULL,
  `otros_noaprovechables` int(11) NOT NULL,
  `potreros_naturales` int(11) NOT NULL,
  `potreros_cultivados` int(11) NOT NULL,
  `recursos_cursos` varchar(100) NOT NULL,
  `recursos_manantiales` varchar(100) NOT NULL,
  `recursos_canales` varchar(100) NOT NULL,
  `recursos_embalses` varchar(100) NOT NULL,
  `recursos_pozos` varchar(100) NOT NULL,
  `recursos_acuaductos` varchar(100) NOT NULL,
  `recursos_otros` varchar(100) NOT NULL,
  `cercas_longitud` varchar(100) NOT NULL,
  `cercas_estantes` varchar(100) NOT NULL,
  `cercas_material` varchar(100) NOT NULL,
  `vias_interiores` text NOT NULL,
  `otras_bienhechurias` text NOT NULL,
  `linceros` text NOT NULL,
  `estudio_legal` text NOT NULL,
  `contabilidad_fecha` date NOT NULL,
  `contabilidad_valor` decimal(16,2) NOT NULL,
  `adicionales_fecha` date NOT NULL,
  `adicionales_valor` decimal(16,2) NOT NULL,
  `adicionales_fecha2` date NOT NULL,
  `adicionales_valor2` decimal(16,2) NOT NULL,
  `adicionales_fecha3` date NOT NULL,
  `adicionales_valor3` decimal(16,2) NOT NULL,
  `adicionales_fecha4` date NOT NULL,
  `adicionales_valor4` decimal(16,2) NOT NULL,
  `adicionales_fecha5` date NOT NULL,
  `adicionales_valor5` decimal(16,2) NOT NULL,
  `avaluo_hectarias` varchar(45) NOT NULL,
  `avaluo_bs` decimal(16,2) NOT NULL,
  `avaluo_hectarias2` varchar(45) NOT NULL,
  `avaluo_bs2` decimal(16,2) NOT NULL,
  `avaluo_hectarias3` varchar(45) NOT NULL,
  `avaluo_bs3` decimal(16,2) NOT NULL,
  `planos_esquemas_fotografias` text NOT NULL,
  `preparado_por` varchar(100) NOT NULL,
  `lugar` varchar(100) NOT NULL,
  `fecha` date NOT NULL,
  `cargo` varchar(100) NOT NULL,
  `status` varchar(1) NOT NULL,
  `usuario` varchar(45) NOT NULL,
  `fechayhora` datetime NOT NULL,
  `codigo_bien` varchar(45) NOT NULL default '',
  `organizacion` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`idterrenos`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `tipos_documentos`
-- 

CREATE TABLE `tipos_documentos` (
  `idtipos_documentos` int(10) unsigned NOT NULL auto_increment,
  `descripcion` varchar(45) NOT NULL,
  `siglas` varchar(45) NOT NULL,
  `compromete` varchar(45) NOT NULL,
  `causa` varchar(45) NOT NULL,
  `paga` varchar(45) NOT NULL,
  `nro_contador` int(10) unsigned NOT NULL,
  `documento_asociado` int(10) unsigned NOT NULL,
  `forma_preimpresa` varchar(2) NOT NULL,
  `multi_categoria` varchar(2) NOT NULL,
  `status` varchar(45) NOT NULL,
  `fechayhora` datetime NOT NULL,
  `usuario` varchar(45) NOT NULL,
  `modulo` varchar(45) NOT NULL,
  `documento_compromete` int(10) unsigned NOT NULL,
  `fondos_terceros` varchar(2) NOT NULL default '',
  PRIMARY KEY  (`idtipos_documentos`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `tipos_persona`
-- 

CREATE TABLE `tipos_persona` (
  `idtipos_persona` int(10) unsigned NOT NULL auto_increment,
  `descripcion` varchar(45) NOT NULL default '',
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idtipos_persona`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `tipos_reconocimientos`
-- 

CREATE TABLE `tipos_reconocimientos` (
  `idtipo_reconocimientos` int(11) NOT NULL auto_increment,
  `denominacion` varchar(200) NOT NULL,
  `usuario` varchar(20) NOT NULL,
  `fechayhora` datetime NOT NULL,
  PRIMARY KEY  (`idtipo_reconocimientos`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `tipos_reportes`
-- 

CREATE TABLE `tipos_reportes` (
  `idtipos_reportes` int(10) unsigned NOT NULL auto_increment,
  `nombre_tipo` varchar(45) NOT NULL default '',
  `cant_firmas` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`idtipos_reportes`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `tipo_beneficiario`
-- 

CREATE TABLE `tipo_beneficiario` (
  `idtipo_beneficiario` int(10) unsigned NOT NULL auto_increment,
  `descripcion` varchar(45) NOT NULL default '',
  `status` varchar(1) default NULL,
  `usuario` varchar(20) default NULL,
  `fechayhora` datetime default NULL,
  PRIMARY KEY  (`idtipo_beneficiario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `tipo_caja_chica`
-- 

CREATE TABLE `tipo_caja_chica` (
  `idtipo_caja_chica` int(10) unsigned NOT NULL auto_increment,
  `denominacion` varchar(45) NOT NULL,
  `unidades_tributarias_aprobadas` decimal(16,2) NOT NULL,
  `resolucion_nro` varchar(45) NOT NULL,
  `fecha_resolucion` date NOT NULL,
  `gaceta_nro` varchar(45) NOT NULL,
  `fecha_gaceta` date NOT NULL,
  `minimo_reponer` decimal(16,2) NOT NULL,
  `maximo_reponer` decimal(16,2) NOT NULL,
  `ut_maxima_factura` decimal(16,2) NOT NULL,
  PRIMARY KEY  (`idtipo_caja_chica`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `tipo_conceptos_nomina`
-- 

CREATE TABLE `tipo_conceptos_nomina` (
  `idconceptos_nomina` int(10) unsigned NOT NULL auto_increment,
  `codigo` varchar(45) NOT NULL default '',
  `descripcion` varchar(200) NOT NULL default '',
  `afecta` varchar(45) NOT NULL,
  PRIMARY KEY  (`idconceptos_nomina`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `tipo_cuenta_bancaria`
-- 

CREATE TABLE `tipo_cuenta_bancaria` (
  `idtipo_cuenta` int(10) unsigned NOT NULL auto_increment,
  `denominacion` varchar(45) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `status` varchar(1) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idtipo_cuenta`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `tipo_detalle`
-- 

CREATE TABLE `tipo_detalle` (
  `idtipo_detalle` int(10) unsigned NOT NULL auto_increment,
  `iddetalle` int(10) unsigned NOT NULL default '0',
  `tipo` varchar(100) NOT NULL default '',
  `usuario` varchar(45) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  `status` varchar(45) NOT NULL default '',
  PRIMARY KEY  (`idtipo_detalle`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `tipo_empresa`
-- 

CREATE TABLE `tipo_empresa` (
  `idtipo_empresa` int(10) unsigned NOT NULL auto_increment,
  `descripcion` varchar(45) NOT NULL default '',
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  `codigo` varchar(45) NOT NULL default '',
  PRIMARY KEY  (`idtipo_empresa`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `tipo_hoja_tiempo`
-- 

CREATE TABLE `tipo_hoja_tiempo` (
  `idtipo_hoja_tiempo` int(10) unsigned NOT NULL auto_increment,
  `descripcion` varchar(45) NOT NULL,
  `unidad` varchar(45) NOT NULL,
  PRIMARY KEY  (`idtipo_hoja_tiempo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `tipo_movimiento_bancario`
-- 

CREATE TABLE `tipo_movimiento_bancario` (
  `idtipo_movimiento_bancario` int(10) unsigned NOT NULL auto_increment,
  `denominacion` varchar(45) NOT NULL default '',
  `siglas` varchar(3) NOT NULL,
  `afecta` varchar(1) NOT NULL COMMENT 'OPTION debita / acredita',
  `usuario` varchar(20) NOT NULL default '',
  `status` varchar(1) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idtipo_movimiento_bancario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `tipo_movimiento_bienes`
-- 

CREATE TABLE `tipo_movimiento_bienes` (
  `idtipo_movimiento_bienes` int(10) unsigned NOT NULL auto_increment,
  `denominacion` varchar(150) NOT NULL,
  `codigo` varchar(3) NOT NULL,
  `afecta` varchar(1) NOT NULL COMMENT 'OPTION incorporacion / desincorporacion',
  `usuario` varchar(20) NOT NULL default '',
  `status` varchar(1) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  `tipo_mueble` varchar(45) NOT NULL default '',
  `origen_bien` varchar(45) NOT NULL default '',
  `describir_motivo` varchar(45) NOT NULL default '',
  `memoria_fotografica` varchar(45) NOT NULL default '',
  `cambia_ubicacion` varchar(45) NOT NULL default '',
  `tipo_inmueble` varchar(45) NOT NULL default '',
  PRIMARY KEY  (`idtipo_movimiento_bienes`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 33792 kB';

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `tipo_movimiento_personal`
-- 

CREATE TABLE `tipo_movimiento_personal` (
  `idtipo_movimiento` int(10) unsigned NOT NULL auto_increment,
  `denominacion` varchar(45) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `status` varchar(1) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  `relacion_laboral` varchar(45) NOT NULL default '',
  `goce_sueldo` varchar(45) NOT NULL default '',
  `afecta_cargo` varchar(45) NOT NULL default '',
  `afecta_ubicacion` varchar(45) NOT NULL default '',
  `afecta_tiempo` varchar(45) NOT NULL default '',
  `afecta_centro_costo` varchar(45) NOT NULL default '',
  `afecta_ficha` varchar(45) NOT NULL default '',
  PRIMARY KEY  (`idtipo_movimiento`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `tipo_nomina`
-- 

CREATE TABLE `tipo_nomina` (
  `idtipo_nomina` int(11) NOT NULL auto_increment,
  `titulo_nomina` varchar(200) NOT NULL,
  `activa` varchar(1) NOT NULL,
  `tipo_fraccion` varchar(1) NOT NULL,
  `numero_fracciones` int(11) NOT NULL,
  `usuario` varchar(20) NOT NULL,
  `estacion` varchar(60) NOT NULL,
  `fechayhora` datetime NOT NULL,
  `idtipo_documento` int(10) unsigned NOT NULL,
  `motivo_cuenta` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`idtipo_nomina`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `tipo_presupuesto`
-- 

CREATE TABLE `tipo_presupuesto` (
  `idtipo_presupuesto` int(10) unsigned NOT NULL auto_increment,
  `denominacion` varchar(255) NOT NULL default '',
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idtipo_presupuesto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `tipo_retencion`
-- 

CREATE TABLE `tipo_retencion` (
  `idtipo_retencion` int(10) unsigned NOT NULL auto_increment,
  `codigo` varchar(5) NOT NULL default '' COMMENT 'Codigo de la retencion: Ejemplo IVA - ISLR - UNO',
  `descripcion` varchar(100) NOT NULL default '' COMMENT 'Descripcion de la retencion',
  `monto_fijo` decimal(16,2) NOT NULL default '0.00' COMMENT 'Si es monto fijo a retener',
  `porcentaje` decimal(5,2) NOT NULL default '0.00' COMMENT 'Si es porcentaje',
  `divisor` int(10) unsigned NOT NULL default '0' COMMENT 'Indicar el divisor del porcentaje (si es porcentaje)',
  `base_calculo` varchar(45) NOT NULL default '' COMMENT 'Indicar la base del calculo de la retencion(SELECT) EXENTO, BASE IMPONIBLE, IVA, TOTAL sobre la que se aplica la retencion fija o procentual',
  `unidad_tributaria` decimal(16,2) NOT NULL default '0.00' COMMENT 'Monto de la unidad tributaria para calculos especiales del ISLR',
  `factor_calculo` decimal(10,4) NOT NULL default '0.0000' COMMENT 'Factor de calculo sobre la unidad tributaria',
  `numero_documento` int(10) unsigned NOT NULL default '0' COMMENT 'Contador con el numero de documento de la retencion',
  `asociado` int(10) unsigned NOT NULL default '0' COMMENT 'Id del tipo_retencion del cual se tomara el numero de documento',
  `articulo` varchar(45) NOT NULL default '' COMMENT 'Articulo de la ley aplicado ',
  `numeral` varchar(45) NOT NULL default '' COMMENT 'Numeral del articulo aplicado',
  `literal` varchar(45) NOT NULL default '' COMMENT 'Literal del articulo aplicado',
  `nombre_comprobante` varchar(15) NOT NULL,
  `status` varchar(45) NOT NULL,
  `usuario` varchar(45) NOT NULL,
  `fechayhora` datetime NOT NULL,
  PRIMARY KEY  (`idtipo_retencion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `tipo_sanciones`
-- 

CREATE TABLE `tipo_sanciones` (
  `idtipo_sanciones` int(11) NOT NULL auto_increment,
  `denominacion` varchar(200) NOT NULL,
  `usuario` varchar(20) character set ucs2 NOT NULL,
  `fechayhora` datetime NOT NULL,
  PRIMARY KEY  (`idtipo_sanciones`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `tipo_sociedad`
-- 

CREATE TABLE `tipo_sociedad` (
  `idtipo_sociedad` int(10) unsigned NOT NULL auto_increment,
  `descripcion` varchar(45) NOT NULL default '',
  `siglas` varchar(6) NOT NULL default '',
  `status` varchar(1) default NULL,
  `usuario` varchar(20) default NULL,
  `fechayhora` datetime default NULL,
  PRIMARY KEY  (`idtipo_sociedad`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `tipo_solicitud`
-- 

CREATE TABLE `tipo_solicitud` (
  `idtipo_solicitud` int(10) unsigned NOT NULL auto_increment,
  `descripcion` varchar(45) NOT NULL default '',
  PRIMARY KEY  (`idtipo_solicitud`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `trabajador`
-- 

CREATE TABLE `trabajador` (
  `idtrabajador` int(10) unsigned NOT NULL auto_increment,
  `apellidos` varchar(45) default NULL,
  `nombres` varchar(45) default NULL,
  `idnacionalidad` int(10) unsigned default NULL,
  `cedula` int(10) default NULL,
  `rif` varchar(12) default NULL,
  `nro_pasaporte` varchar(20) default NULL,
  `sexo` varchar(1) default '',
  `fecha_nacimiento` date default '0000-00-00',
  `lugar_nacimiento` varchar(150) default NULL,
  `idedo_civil` int(10) unsigned default '0',
  `idgrupo_sanguineo` int(10) unsigned default '0',
  `flag_donante` varchar(1) default NULL,
  `peso` double default '0',
  `talla` double default '0',
  `direccion` varchar(200) default NULL,
  `telefono_habitacion` varchar(25) default NULL,
  `telefono_movil` varchar(25) default NULL,
  `correo_electronico` varchar(45) default NULL,
  `flag_vehiculo` varchar(1) default NULL,
  `flag_licencia` varchar(1) default NULL,
  `nombre_emergencia` varchar(45) default NULL,
  `telefono_emergencia` varchar(25) default NULL,
  `direccion_emergencia` varchar(200) default NULL,
  `foto` varchar(60) default NULL,
  `talla_camisa` varchar(10) default NULL,
  `talla_pantalon` varchar(10) default NULL,
  `talla_zapatos` varchar(10) default NULL,
  `otras_actividades` varchar(200) default NULL,
  `status` varchar(1) default '',
  `fechayhora` datetime default NULL,
  `usuario` varchar(20) default NULL,
  `estacion` varchar(45) default NULL,
  `fecha_ingreso` date NOT NULL,
  `idtipo_nomina` int(10) unsigned NOT NULL,
  `centro_costo` int(10) unsigned NOT NULL,
  `idunidad_funcional` int(10) unsigned NOT NULL,
  `nro_ficha` varchar(45) NOT NULL,
  `idcargo` varchar(45) NOT NULL,
  `activo_nomina` varchar(2) NOT NULL default '',
  `vacaciones` varchar(2) NOT NULL default '',
  `nombre_imagen` varchar(200) NOT NULL default '',
  `fecha_inicio_continuidad` date NOT NULL,
  PRIMARY KEY  (`idtrabajador`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 11264 kB; (`iddependencia`) REFER `rrhh/depende';

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `traslados_presupuestarios`
-- 

CREATE TABLE `traslados_presupuestarios` (
  `idtraslados_presupuestarios` int(10) unsigned NOT NULL auto_increment,
  `nro_solicitud` varchar(12) default NULL,
  `fecha_solicitud` date default NULL,
  `nro_resolucion` varchar(25) default NULL,
  `fecha_resolucion` date default NULL,
  `fecha_ingreso` date default NULL,
  `justificacion` text,
  `anio` varchar(4) default NULL,
  `total_credito` decimal(16,2) NOT NULL default '0.00',
  `total_debito` decimal(16,2) NOT NULL default '0.00',
  `estado` varchar(20) default NULL,
  `ubicacion` varchar(45) default '0',
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idtraslados_presupuestarios`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `ubicacion`
-- 

CREATE TABLE `ubicacion` (
  `idubicacion` int(10) unsigned NOT NULL auto_increment,
  `codigo` varchar(45) NOT NULL default '',
  `denominacion` varchar(100) NOT NULL default '',
  `status` varchar(45) NOT NULL default '',
  `usuario` varchar(45) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idubicacion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `unidad_ejecutora`
-- 

CREATE TABLE `unidad_ejecutora` (
  `idunidad_ejecutora` int(10) unsigned NOT NULL auto_increment,
  `codigo` varchar(2) NOT NULL default '',
  `denominacion` varchar(255) NOT NULL default '',
  `responsable` varchar(155) NOT NULL default '',
  `cargo` varchar(95) NOT NULL default '',
  `status` varchar(1) NOT NULL default '',
  `usuario` varchar(20) default NULL,
  `fechayhora` datetime default NULL,
  PRIMARY KEY  (`idunidad_ejecutora`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `unidad_medida`
-- 

CREATE TABLE `unidad_medida` (
  `idunidad_medida` int(10) unsigned NOT NULL auto_increment,
  `descripcion` varchar(45) NOT NULL,
  `abreviado` varchar(3) NOT NULL,
  `status` varchar(1) default NULL,
  `usuario` varchar(20) default NULL,
  `fechayhora` datetime default NULL,
  PRIMARY KEY  (`idunidad_medida`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `unidad_tributaria`
-- 

CREATE TABLE `unidad_tributaria` (
  `idunidad_tributaria` int(10) unsigned NOT NULL auto_increment,
  `anio` int(10) unsigned NOT NULL default '0',
  `desde` date NOT NULL default '0000-00-00',
  `hasta` date NOT NULL default '0000-00-00',
  `costo` decimal(16,2) NOT NULL default '0.00',
  `gaceta` varchar(10) NOT NULL,
  `fecha_gaceta` date NOT NULL,
  PRIMARY KEY  (`idunidad_tributaria`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `urbanizacion`
-- 

CREATE TABLE `urbanizacion` (
  `idurbanizacion` int(10) unsigned NOT NULL auto_increment,
  `idsectores` int(10) unsigned NOT NULL default '0',
  `denominacion` varchar(45) NOT NULL default '',
  `usuario` varchar(45) NOT NULL default '',
  `status` varchar(45) NOT NULL default '',
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`idurbanizacion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `usuarios`
-- 

CREATE TABLE `usuarios` (
  `cedula` varchar(10) NOT NULL default '0',
  `apellidos` varchar(45) NOT NULL default '',
  `nombres` varchar(45) NOT NULL default '',
  `login` varchar(20) character set utf8 collate utf8_bin NOT NULL default '',
  `clave` varchar(45) NOT NULL default '',
  `nivel` varchar(5) NOT NULL default '',
  `status` varchar(1) NOT NULL default '',
  `preguntasecreta` varchar(45) NOT NULL default '',
  `respuestasecreta` varchar(45) NOT NULL default '',
  `estado` varchar(1) NOT NULL default '',
  `usuario` varchar(20) NOT NULL default '',
  `area` varchar(10) NOT NULL default '',
  `iddependencia` int(10) NOT NULL,
  `fechayhora` datetime NOT NULL default '0000-00-00 00:00:00',
  `estacion` varchar(45) NOT NULL default '',
  PRIMARY KEY  (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `usuarios_categoria`
-- 

CREATE TABLE `usuarios_categoria` (
  `idusuarios_categoria` int(10) unsigned NOT NULL auto_increment,
  `idcategoria_programatica` int(10) unsigned NOT NULL default '0',
  `cedula` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`idusuarios_categoria`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `vacaciones`
-- 

CREATE TABLE `vacaciones` (
  `idvacaciones` int(10) unsigned NOT NULL auto_increment,
  `fecha_salida` date NOT NULL default '0000-00-00',
  `fecha_reintegro` date NOT NULL default '0000-00-00',
  `comentarios` text NOT NULL,
  `idtrabajador` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`idvacaciones`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

