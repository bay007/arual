-- phpMyAdmin SQL Dump
-- version 4.1.8
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generaci贸n: 09-08-2014 a las 23:38:56
-- Versi贸n del servidor: 5.1.73-cll
-- Versi贸n de PHP: 5.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de datos: 'arualmrx_arual'
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla 'admin'
--

CREATE TABLE IF NOT EXISTS admin (
  correo_responsable varchar(50) COLLATE ucs2_spanish2_ci NOT NULL,
  fkid int(11) NOT NULL,
  nombre_completo varchar(80) COLLATE ucs2_spanish2_ci DEFAULT NULL,
  tipo tinyint(4) DEFAULT NULL,
  celular varchar(25) COLLATE ucs2_spanish2_ci DEFAULT NULL,
  pphase varchar(160) COLLATE ucs2_spanish2_ci DEFAULT NULL,
  PRIMARY KEY (correo_responsable,fkid),
  KEY fkid (fkid)
) ENGINE=InnoDB DEFAULT CHARSET=ucs2 COLLATE=ucs2_spanish2_ci;

--
-- Volcado de datos para la tabla 'admin'
--

INSERT INTO admin (correo_responsable, fkid, nombre_completo, tipo, celular, pphase) VALUES
('angeleslomas@angeles.com', 110, 'Este es el Responsable 1 de los Angeles', 0, '33 44 55 66 77', 'a4f35e966d8c2a28e53257a80df3d36ef928844b1e19f2c8951c8aa63534f2e518d950f0f25f883c651650552a5a613d4fadec8c97eec3e5512574d2b9c009acaf3a3219c0a05af5b7f37de69f43fd6b');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla 'alumno'
--

CREATE TABLE IF NOT EXISTS alumno (
  email varchar(50) COLLATE ucs2_spanish2_ci NOT NULL,
  pphase varchar(160) COLLATE ucs2_spanish2_ci NOT NULL,
  nombre varchar(20) COLLATE ucs2_spanish2_ci NOT NULL,
  tipo tinyint(4) DEFAULT NULL,
  ap varchar(20) COLLATE ucs2_spanish2_ci DEFAULT NULL,
  am varchar(20) COLLATE ucs2_spanish2_ci DEFAULT NULL,
  sexo bit(1) DEFAULT NULL,
  edad tinyint(4) DEFAULT NULL,
  celular varchar(25) COLLATE ucs2_spanish2_ci DEFAULT NULL,
  pais varchar(13) COLLATE ucs2_spanish2_ci DEFAULT NULL,
  etapa tinyint(4) NOT NULL DEFAULT '0',
  imagenes_path varchar(100) COLLATE ucs2_spanish2_ci DEFAULT NULL,
  PRIMARY KEY (email)
) ENGINE=InnoDB DEFAULT CHARSET=ucs2 COLLATE=ucs2_spanish2_ci;

--
-- Volcado de datos para la tabla 'alumno'
--

INSERT INTO alumno (email, pphase, nombre, tipo, ap, am, sexo, edad, celular, pais, etapa, imagenes_path) VALUES
('alumno1@alumno1.com', 'a4f35e966d8c2a28e53257a80df3d36ef928844b1e19f2c8951c8aa63534f2e518d950f0f25f883c651650552a5a613d4fadec8c97eec3e5512574d2b9c009acaf3a3219c0a05af5b7f37de69f43fd6b', 'Esteban GALICIA', 1, 'John called Mike. ', 'I called Rolph. ', b'1', 70, 'Cindy knows Mike. ', NULL, 1, NULL),
('alumno2@alumno2.com', 'a4f35e966d8c2a28e53257a80df3d36ef928844b1e19f2c8951c8aa63534f2e518d950f0f25f883c651650552a5a613d4fadec8c97eec3e5512574d2b9c009acaf3a3219c0a05af5b7f37de69f43fd6b', 'Alumno LEGO', 2, 'ZanPHP', NULL, NULL, NULL, NULL, NULL, 2, 'tnt_code+outlook+com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla 'curso'
--

CREATE TABLE IF NOT EXISTS curso (
  id_curso int(11) NOT NULL AUTO_INCREMENT,
  fkid int(11) NOT NULL,
  dirigido tinyint(4) DEFAULT NULL,
  video_path varchar(100) COLLATE ucs2_spanish2_ci DEFAULT NULL,
  pdf_path varchar(100) COLLATE ucs2_spanish2_ci DEFAULT NULL,
  tipo int(11) NOT NULL,
  f_alta datetime DEFAULT NULL,
  PRIMARY KEY (id_curso,fkid),
  KEY fkid (fkid)
) ENGINE=InnoDB  DEFAULT CHARSET=ucs2 COLLATE=ucs2_spanish2_ci AUTO_INCREMENT=111 ;

--
-- Volcado de datos para la tabla 'curso'
--

INSERT INTO curso (id_curso, fkid, dirigido, video_path, pdf_path, tipo, f_alta) VALUES
(110, 110, NULL, NULL, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla 'edicion'
--

CREATE TABLE IF NOT EXISTS edicion (
  id_edicion int(11) NOT NULL AUTO_INCREMENT,
  fkid_curso int(11) NOT NULL,
  fkid int(11) NOT NULL,
  cupo smallint(6) DEFAULT NULL,
  f_lanzamiento timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  contenido varchar(300) COLLATE ucs2_spanish2_ci DEFAULT NULL,
  duracion varchar(150) COLLATE ucs2_spanish2_ci DEFAULT NULL,
  fecha_aplicacion date DEFAULT NULL,
  hora time DEFAULT NULL,
  PRIMARY KEY (id_edicion,fkid_curso,fkid),
  KEY fkid_curso (fkid_curso,fkid)
) ENGINE=InnoDB  DEFAULT CHARSET=ucs2 COLLATE=ucs2_spanish2_ci AUTO_INCREMENT=151 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla 'inscrito'
--

CREATE TABLE IF NOT EXISTS inscrito (
  fkid_edicion int(11) NOT NULL,
  fkfkid_curso int(11) NOT NULL,
  fkemail varchar(50) COLLATE ucs2_spanish2_ci NOT NULL,
  fkid int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (fkid_edicion,fkfkid_curso,fkemail,fkid),
  KEY fkalumno (fkemail),
  KEY fkalumno2 (fkfkid_curso,fkid,fkid_edicion)
) ENGINE=InnoDB DEFAULT CHARSET=ucs2 COLLATE=ucs2_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla 'markers'
--

CREATE TABLE IF NOT EXISTS markers (
  id int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) COLLATE ucs2_spanish2_ci NOT NULL,
  address varchar(80) COLLATE ucs2_spanish2_ci NOT NULL,
  lat float(10,6) NOT NULL,
  lng float(10,6) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=ucs2 COLLATE=ucs2_spanish2_ci AUTO_INCREMENT=111 ;

--
-- Volcado de datos para la tabla 'markers'
--

INSERT INTO markers (id, name, address, lat, lng) VALUES
(110, 'Angeles Lomas', 'Huixquiluca', 34.523422, 37.346889);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla admin
--
ALTER TABLE admin
  ADD CONSTRAINT admin_ibfk_1 FOREIGN KEY (fkid) REFERENCES markers (id);

--
-- Filtros para la tabla curso
--
ALTER TABLE curso
  ADD CONSTRAINT curso_ibfk_1 FOREIGN KEY (fkid) REFERENCES markers (id);

--
-- Filtros para la tabla edicion
--
ALTER TABLE edicion
  ADD CONSTRAINT edicion_ibfk_1 FOREIGN KEY (fkid_curso, fkid) REFERENCES curso (id_curso, fkid);

--
-- Filtros para la tabla inscrito
--
ALTER TABLE inscrito
  ADD CONSTRAINT fkalumno2 FOREIGN KEY (fkfkid_curso, fkid, fkid_edicion) REFERENCES edicion (fkid_curso, fkid, id_edicion) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT fkalumno FOREIGN KEY (fkemail) REFERENCES alumno (email) ON DELETE CASCADE ON UPDATE CASCADE;







--------------------------------------------------------------------------------------


----NUEVA VERSION






















-- phpMyAdmin SQL Dump
-- version 4.1.8
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 12-08-2014 a las 00:17:32
-- Versión del servidor: 5.1.73-cll
-- Versión de PHP: 5.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de datos: `arualmrx_arualmr2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `correo_responsable` varchar(50) COLLATE ucs2_spanish2_ci NOT NULL,
  `fkid` int(11) NOT NULL,
  `nombre_completo` varchar(80) COLLATE ucs2_spanish2_ci DEFAULT NULL,
  `tipo` tinyint(4) DEFAULT NULL,
  `celular` varchar(25) COLLATE ucs2_spanish2_ci DEFAULT NULL,
  `pphase` varchar(160) COLLATE ucs2_spanish2_ci DEFAULT NULL,
  PRIMARY KEY (`correo_responsable`),
  KEY `fkid` (`fkid`)
) ENGINE=InnoDB DEFAULT CHARSET=ucs2 COLLATE=ucs2_spanish2_ci;

--
-- Volcado de datos para la tabla `admin`
--

INSERT INTO `admin` (`correo_responsable`, `fkid`, `nombre_completo`, `tipo`, `celular`, `pphase`) VALUES
('angeleslomas@angeles.com', 110, 'Este es el Responsable 1 de los Angeles', 0, '33 44 55 66 77', 'a4f35e966d8c2a28e53257a80df3d36ef928844b1e19f2c8951c8aa63534f2e518d950f0f25f883c651650552a5a613d4fadec8c97eec3e5512574d2b9c009acaf3a3219c0a05af5b7f37de69f43fd6b'),
('angeleslomas2@angeles.com', 110, 'Responsable Numero 2', 0, '55 77 88 99 22', 'a4f35e966d8c2a28e53257a80df3d36ef928844b1e19f2c8951c8aa63534f2e518d950f0f25f883c651650552a5a613d4fadec8c97eec3e5512574d2b9c009acaf3a3219c0a05af5b7f37de69f43fd6b'),
('he1@he.com', 111, 'Usuario del Hospital español', 0, '66 99 66 33 66', 'a4f35e966d8c2a28e53257a80df3d36ef928844b1e19f2c8951c8aa63534f2e518d950f0f25f883c651650552a5a613d4fadec8c97eec3e5512574d2b9c009acaf3a3219c0a05af5b7f37de69f43fd6b'),
('he2@he.com', 111, 'Administrador del español numero 2', 0, '88 55 33 99 00', 'a4f35e966d8c2a28e53257a80df3d36ef928844b1e19f2c8951c8aa63534f2e518d950f0f25f883c651650552a5a613d4fadec8c97eec3e5512574d2b9c009acaf3a3219c0a05af5b7f37de69f43fd6b');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumno`
--

DROP TABLE IF EXISTS `alumno`;
CREATE TABLE IF NOT EXISTS `alumno` (
  `email` varchar(50) COLLATE ucs2_spanish2_ci NOT NULL,
  `pphase` varchar(160) COLLATE ucs2_spanish2_ci NOT NULL,
  `nombre` varchar(20) COLLATE ucs2_spanish2_ci NOT NULL,
  `tipo` tinyint(4) DEFAULT NULL,
  `ap` varchar(20) COLLATE ucs2_spanish2_ci DEFAULT NULL,
  `am` varchar(20) COLLATE ucs2_spanish2_ci DEFAULT NULL,
  `sexo` bit(1) DEFAULT NULL,
  `edad` tinyint(4) DEFAULT NULL,
  `celular` varchar(25) COLLATE ucs2_spanish2_ci DEFAULT NULL,
  `pais` varchar(13) COLLATE ucs2_spanish2_ci DEFAULT NULL,
  `etapa` tinyint(4) NOT NULL DEFAULT '0',
  `imagenes_path` varchar(100) COLLATE ucs2_spanish2_ci DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=ucs2 COLLATE=ucs2_spanish2_ci;

--
-- Volcado de datos para la tabla `alumno`
--

INSERT INTO `alumno` (`email`, `pphase`, `nombre`, `tipo`, `ap`, `am`, `sexo`, `edad`, `celular`, `pais`, `etapa`, `imagenes_path`) VALUES
('alumno1@alumno1.com', 'a4f35e966d8c2a28e53257a80df3d36ef928844b1e19f2c8951c8aa63534f2e518d950f0f25f883c651650552a5a613d4fadec8c97eec3e5512574d2b9c009acaf3a3219c0a05af5b7f37de69f43fd6b', 'Esteban GALICIA', 1, 'John called Mike. ', 'I called Rolph. ', b'1', 70, 'Cindy knows Mike. ', NULL, 1, NULL),
('alumno2@alumno2.com', 'a4f35e966d8c2a28e53257a80df3d36ef928844b1e19f2c8951c8aa63534f2e518d950f0f25f883c651650552a5a613d4fadec8c97eec3e5512574d2b9c009acaf3a3219c0a05af5b7f37de69f43fd6b', 'Alumno LEGO', 2, 'ZanPHP', NULL, NULL, NULL, NULL, NULL, 2, 'tnt_code+outlook+com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `curso`
--

DROP TABLE IF EXISTS `curso`;
CREATE TABLE IF NOT EXISTS `curso` (
  `id_curso` int(11) NOT NULL AUTO_INCREMENT,
  `fkid` int(11) NOT NULL,
  `fkdirigido` int(11) DEFAULT NULL COMMENT '1 lEGOS, 0 MEDICOS',
  `video_path` varchar(100) COLLATE ucs2_spanish2_ci DEFAULT NULL,
  `pdf_path` varchar(100) COLLATE ucs2_spanish2_ci DEFAULT NULL,
  `fktipo` int(11) DEFAULT NULL,
  `f_alta` datetime DEFAULT NULL,
  `duracionCurso` varchar(40) COLLATE ucs2_spanish2_ci NOT NULL,
  `contenidoCurso` varchar(1500) COLLATE ucs2_spanish2_ci NOT NULL,
  `requisitos` varchar(1500) COLLATE ucs2_spanish2_ci NOT NULL,
  PRIMARY KEY (`id_curso`),
  KEY `fkid` (`fkid`),
  KEY `fkdirigido` (`fkdirigido`),
  KEY `fktipo` (`fktipo`)
) ENGINE=InnoDB  DEFAULT CHARSET=ucs2 COLLATE=ucs2_spanish2_ci AUTO_INCREMENT=133 ;

--
-- Volcado de datos para la tabla `curso`
--

INSERT INTO `curso` (`id_curso`, `fkid`, `fkdirigido`, `video_path`, `pdf_path`, `fktipo`, `f_alta`, `duracionCurso`, `contenidoCurso`, `requisitos`) VALUES
(129, 110, 1, NULL, NULL, 2, '2014-08-10 07:13:20', '6 horas con recesos.', 'Su contenido es teórico práctico, practicando habilidades de RCP en adultos, lactantes y manejo de obstrucción de la vía aérea por cuerpo extraño. \r\n\r\nEn el transcurso del curso se realizan evaluaciones de habilidades que son requisito indispensable para la aprobación del mismo, además de una evaluación escrita en donde la calificación mínima aprobatoria es de 84%. \r\n\r\nAl final del curso, se entregan tarjetas avaladas por la American Heart Association a los alumnos \r\nque hayan completado en forma satisfactoria el curso.', 'Ninguno'),
(130, 110, 0, NULL, NULL, 1, NULL, '9 horas con recesos.', 'Su contenido es teórico práctico dividido en dos partes. La primera parte revisa aspectos de RCP en adultos y lactantes. La segunda parte abarca varios aspectos de primeros auxilios que incluye lo siguiente:\r\n- Conceptos básicos\r\n- Emergencias médicas \r\n- Emergencias por lesiones\r\n- Emergencias medioambientales\r\nEn el transcurso del curso se realizan evaluaciones de habilidades que son requisito indispensable para la aprobación del curso. No hay evaluación escrita. \r\nAl final del curso, se entregan tarjetas avaladas por la American Heart Association a los alumnos\r\nque hayan completado en forma satisfactoria el curso.', 'Ninguno'),
(131, 111, 1, NULL, NULL, 3, NULL, '2 días (15 horas aproximadamente)', 'curso: El curso es teórico práctico y da un repaso de RCP con DEA en adultos y manejo de paro respiratorio, revisión de casos de Síndrome coronario agudo y Stroke. También \r\nse realizan estaciones de destreza en donde se enseña la integración del equipo de reanimación \r\nen el manejo del paro cardiorespiratorio repasando los ritmos letales de Fibrilación ventricular/Taquicardia ventricular sin pulso, Actividad eléctrica sin pulso, Asistolia además de manejo de taquicardias y bradicardias con pulso estables e inestables.\r\nEn el transcurso del curso se realizan evaluaciones de habilidades que son requisito indispensable para la aprobación del mismo, además de una evaluación escrita en donde la calificación mínima aprobatoria es de 84%. Al final del curso, se entregan tarjetas avaladas por la American Heart Association a los alumnos \r\nque hayan completado en forma satisfactoria el curso.', '1.- Tener curso de Apoyo Vital Básico de la AHA vigente y aprobado.\r\n\r\n2.- Contestar autoevaluación en línea previamente al día del curso'),
(132, 111, 1, NULL, NULL, 4, NULL, '9.5 horas incluyendo recesos.', 'El curso es teórico práctico y requiere evaluación práctica del manejo de paro con RCP y DEA en adultos, Manejo de paro respiratorio, evaluación de código mega.\r\nPosteriormente se realiza revisión de casos especiales que incluyen: \r\n1.- Cuidados post paro cardiaco\r\n2.- Emergencias cardiovasculares\r\n3.- Farmacología y toxicología\r\n4.- Emergencias respiratorias y metabólicas. \r\nAl final del curso, se entregan tarjetas avaladas por la American Heart Association a los alumnos que hayan completado en forma satisfactoria y completa todos los requisitos del curso que incluyen evaluaciones escritas en línea con calificación mínima aprobatoria y evaluación de\r\nhabilidades en RCP con DEA, manejo de paro respiratorio y código mega.', '1.- Tener curso de Apoyo Vital Cardiovascular Avanzado proveedor o instructor AHA vigente.\r\n2.- Contestar dos autoevaluaciones en línea previamente al día del curso');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `edicion`
--

DROP TABLE IF EXISTS `edicion`;
CREATE TABLE IF NOT EXISTS `edicion` (
  `id_edicion` int(11) NOT NULL AUTO_INCREMENT,
  `fkid_curso` int(11) NOT NULL,
  `cupo` smallint(6) DEFAULT NULL,
  `f_lanzamiento` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_aplicacion` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  PRIMARY KEY (`id_edicion`),
  KEY `fkid_curso` (`fkid_curso`)
) ENGINE=InnoDB  DEFAULT CHARSET=ucs2 COLLATE=ucs2_spanish2_ci AUTO_INCREMENT=163 ;

--
-- Volcado de datos para la tabla `edicion`
--

INSERT INTO `edicion` (`id_edicion`, `fkid_curso`, `cupo`, `f_lanzamiento`, `fecha_aplicacion`, `hora`) VALUES
(157, 129, 16, '2014-08-10 11:24:15', '2014-08-31', '18:00:00'),
(158, 130, 19, '2014-08-10 11:26:14', '2014-11-11', '09:00:00'),
(159, 131, 23, '2014-08-10 11:46:55', '2014-10-09', '14:00:00'),
(162, 132, 34, '2014-08-11 10:54:05', '2015-08-11', '08:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscrito`
--

DROP TABLE IF EXISTS `inscrito`;
CREATE TABLE IF NOT EXISTS `inscrito` (
  `fkid_edicion` int(11) NOT NULL,
  `fkemail` varchar(50) COLLATE ucs2_spanish2_ci NOT NULL,
  PRIMARY KEY (`fkid_edicion`,`fkemail`),
  KEY `fkemail` (`fkemail`)
) ENGINE=InnoDB DEFAULT CHARSET=ucs2 COLLATE=ucs2_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `markers`
--

DROP TABLE IF EXISTS `markers`;
CREATE TABLE IF NOT EXISTS `markers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) COLLATE ucs2_spanish2_ci NOT NULL,
  `address` varchar(200) COLLATE ucs2_spanish2_ci NOT NULL,
  `lat` float(10,6) NOT NULL,
  `lng` float(10,6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=ucs2 COLLATE=ucs2_spanish2_ci AUTO_INCREMENT=112 ;

--
-- Volcado de datos para la tabla `markers`
--

INSERT INTO `markers` (`id`, `name`, `address`, `lat`, `lng`) VALUES
(110, 'Angeles Lomas', 'Av. Vialidad de la Barranca s/n Col. Valle de las Palmas Huixquilucan, Edo. de Mexico C.P. 52763', 34.523422, 37.346889),
(111, 'Hospital Español', 'Ejercito Nacional 613, Granada, 11520 Ciudad Mexico, Distrito Federal, México', 74.982880, 52.948570);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `NombreCursos`
--

DROP TABLE IF EXISTS `NombreCursos`;
CREATE TABLE IF NOT EXISTS `NombreCursos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(100) COLLATE utf8_spanish2_ci DEFAULT NULL COMMENT 'BSL. ACLS. ACL EXPERoTS, DIplomado',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `NombreCursos`
--

INSERT INTO `NombreCursos` (`id`, `Nombre`) VALUES
(1, 'SALVACORAZONES PRIMEROS AUXILIOS CON RCP Y DEA'),
(2, 'SOPORTE VITAL BASICO (BLS)'),
(3, 'REANIMACION CARDIOPULMONAR AVANZADA (ACLS)'),
(4, 'REANIMACION CARDIOPULMONAR AVANZADA PARA PROFESIONALES EXPERTOS (ACLS PE)');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publico`
--

DROP TABLE IF EXISTS `publico`;
CREATE TABLE IF NOT EXISTS `publico` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `publico` varchar(100) COLLATE utf8_spanish2_ci DEFAULT NULL COMMENT 'BSL. ACLS. ACL EXPERoTS, DIplomado',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `publico`
--

INSERT INTO `publico` (`id`, `publico`) VALUES
(0, 'Profesionales del area de la salud, medicos, enfermeras, paramedicos, etc.'),
(1, 'A todo publico mayor de 15 años.');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`fkid`) REFERENCES `markers` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `curso`
--
ALTER TABLE `curso`
  ADD CONSTRAINT `curso_ibfk_1` FOREIGN KEY (`fkid`) REFERENCES `markers` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `curso_ibfk_2` FOREIGN KEY (`fkdirigido`) REFERENCES `publico` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `curso_ibfk_3` FOREIGN KEY (`fktipo`) REFERENCES `NombreCursos` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `edicion`
--
ALTER TABLE `edicion`
  ADD CONSTRAINT `edicion_ibfk_1` FOREIGN KEY (`fkid_curso`) REFERENCES `curso` (`id_curso`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `inscrito`
--
ALTER TABLE `inscrito`
  ADD CONSTRAINT `inscrito_ibfk_1` FOREIGN KEY (`fkid_edicion`) REFERENCES `edicion` (`id_edicion`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inscrito_ibfk_2` FOREIGN KEY (`fkemail`) REFERENCES `alumno` (`email`) ON UPDATE CASCADE;
