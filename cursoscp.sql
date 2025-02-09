-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3307
-- Tiempo de generación: 04-02-2025 a las 18:16:25
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `cursoscp`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--

CREATE TABLE `administradores` (
  `usuario` varchar(20) NOT NULL,
  `dni` varchar(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administradores`
--

INSERT INTO `administradores` (`usuario`, `dni`) VALUES
('admin', 'B1234567S'),
('profesor', 'A1111111C'),
('profesor', 'X7654321Y'),
('profesor', 'x5564343T');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `codigo` decimal(6,0) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `abierto` tinyint(1) NOT NULL,
  `numeroplazas` int(2) NOT NULL,
  `plazoinscripcion` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`codigo`, `nombre`, `abierto`, `numeroplazas`, `plazoinscripcion`) VALUES
(9, 'Como usar bien el ChatGPT', 1, 3, '2025-02-12'),
(12, 'Mindfulness como mejora la atención en el aula', 1, 2, '2025-02-16'),
(15, 'Ingles avanzado técnico', 0, 2, '2025-01-07'),
(34, 'Modelo Flipped Classroom y Entorno Google Suite en', 0, 4, '2025-01-07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitantes`
--

CREATE TABLE `solicitantes` (
  `dni` varchar(9) NOT NULL,
  `apellidos` varchar(50) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `telefono` varchar(12) NOT NULL,
  `correo` varchar(50) NOT NULL,
  `codigocentro` varchar(8) NOT NULL,
  `coordinadortic` tinyint(1) NOT NULL,
  `grupotic` tinyint(1) NOT NULL,
  `nombregrupo` varchar(25) NOT NULL,
  `pbilin` tinyint(1) NOT NULL,
  `cargo` tinyint(1) NOT NULL,
  `nombrecargo` varchar(50) NOT NULL,
  `situacion` set('activo','inactivo') NOT NULL,
  `antiguedad` int(2) NOT NULL,
  `especialidad` varchar(50) NOT NULL,
  `puntos` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `solicitantes`
--

INSERT INTO `solicitantes` (`dni`, `apellidos`, `nombre`, `telefono`, `correo`, `codigocentro`, `coordinadortic`, `grupotic`, `nombregrupo`, `pbilin`, `cargo`, `nombrecargo`, `situacion`, `antiguedad`, `especialidad`, `puntos`) VALUES
('A1324234S', 'Gutierrez', 'Eleonor', '693214578', 'auroris@auramorosan.com', '12', 0, 1, 'Talis Group', 0, 1, 'jefe de estudios', 'activo', 14, 'profesora historia', 0),
('C1234567L', 'Candenas', 'David', '627952917', 'auroris@auramorosan.com', '55', 0, 1, 'Samar', 0, 1, 'jefe de departamento', 'activo', 16, 'coordinador', 0),
('J1234567X', 'Manzanares', 'Julia', '663987124', 'mariaperez@domenico.es', '36', 0, 0, '', 1, 1, 'secretario', 'inactivo', 20, 'ingles', 0),
('x5564343T', 'Felix', 'Mariano', '699854712', 'mariaperez@domenico.es', '21', 0, 1, 'LeriGroup', 1, 1, 'secretario', 'inactivo', 6, 'profesor de matematicas', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes`
--

CREATE TABLE `solicitudes` (
  `dni` varchar(9) NOT NULL,
  `codigocurso` decimal(6,0) NOT NULL,
  `fechasolicitud` date NOT NULL,
  `admitido` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `solicitudes`
--

INSERT INTO `solicitudes` (`dni`, `codigocurso`, `fechasolicitud`, `admitido`) VALUES
('A1324234S', 9, '2025-02-04', 0),
('J1234567X', 9, '2025-02-04', 0),
('J1234567X', 12, '2025-02-04', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `solicitantes`
--
ALTER TABLE `solicitantes`
  ADD PRIMARY KEY (`dni`);

--
-- Indices de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD PRIMARY KEY (`dni`,`codigocurso`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
