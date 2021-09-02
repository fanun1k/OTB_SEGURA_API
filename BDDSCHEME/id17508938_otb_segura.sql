-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 01-09-2021 a las 19:51:35
-- Versión del servidor: 10.3.16-MariaDB
-- Versión de PHP: 7.3.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `id17508938_otb_segura`
--
USE otb_segura_db
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alarm`
--

CREATE TABLE `alarm` (
  `Alarm_ID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Otb_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alert`
--

CREATE TABLE `alert` (
  `Alert_ID` int(11) NOT NULL,
  `Date` datetime NOT NULL DEFAULT current_timestamp(),
  `Longitude` float NOT NULL,
  `Latitude` float NOT NULL,
  `State` tinyint(4) NOT NULL DEFAULT 1,
  `Otb_ID` int(11) NOT NULL,
  `Alert_type_ID` int(11) NOT NULL,
  `User_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alert_type`
--

CREATE TABLE `alert_type` (
  `Alert_type_ID` int(11) NOT NULL,
  `Name` varchar(60) NOT NULL,
  `State` tinyint(4) NOT NULL DEFAULT 1,
  `Otb_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `alert_type`
--

INSERT INTO `alert_type` (`Alert_type_ID`, `Name`, `State`, `Otb_ID`) VALUES
(6, 'otb Villa Pagador', 1, 7),
(7, 'Incendio', 1, 7),
(8, 'Robo', 1, 7),
(9, 'Desastre', 1, 7),
(10, 'Desastre 2', 1, 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `camera`
--

CREATE TABLE `camera` (
  `Camera_ID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `State` tinyint(4) NOT NULL DEFAULT 1,
  `Otb_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `otb`
--

CREATE TABLE `otb` (
  `Otb_ID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `State` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `otb`
--

INSERT INTO `otb` (`Otb_ID`, `Name`, `State`) VALUES
(4, 'OTB San Francisco', 1),
(5, 'OTB San Pablo', 1),
(6, 'OTB San Marcos', 1),
(7, 'Pablo Daniel Rodriguez Solis', 1),
(8, 'OTB San Marcos', 1),
(9, 'Desastre 2', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `User_ID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Email` varchar(35) NOT NULL,
  `Password` varchar(60) NOT NULL,
  `Cell_phone` varchar(8) NOT NULL,
  `Ci` varchar(15) NOT NULL,
  `State` tinyint(4) NOT NULL DEFAULT 1,
  `Type` tinyint(4) NOT NULL DEFAULT 0,
  `Otb_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`User_ID`, `Name`, `Email`, `Password`, `Cell_phone`, `Ci`, `State`, `Type`, `Otb_ID`) VALUES
(1, 'Pablo Rodriguez Solis', 'pablors0598@gmail.com', 'pablo123', '77407529', '13623834', 1, 1, 5),
(2, 'Juan Perez', 'asdas@gmail.com', '123123123', '77407529', '13623834', 0, 1, 4),
(3, 'Pedro', 'sad@gmail.com', '123123123', '45561234', '234234234234', 1, 0, 5);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alarm`
--
ALTER TABLE `alarm`
  ADD PRIMARY KEY (`Alarm_ID`),
  ADD KEY `fk_alarm_otb1_idx` (`Otb_ID`);

--
-- Indices de la tabla `alert`
--
ALTER TABLE `alert`
  ADD PRIMARY KEY (`Alert_ID`),
  ADD KEY `fk_activity_otb1_idx` (`Otb_ID`),
  ADD KEY `fk_alert_alert_type1_idx` (`Alert_type_ID`),
  ADD KEY `fk_alert_user1_idx` (`User_ID`);

--
-- Indices de la tabla `alert_type`
--
ALTER TABLE `alert_type`
  ADD PRIMARY KEY (`Alert_type_ID`),
  ADD KEY `fk_alert_type_otb1_idx` (`Otb_ID`);

--
-- Indices de la tabla `camera`
--
ALTER TABLE `camera`
  ADD PRIMARY KEY (`Camera_ID`),
  ADD KEY `fk_camera_otb1_idx` (`Otb_ID`);

--
-- Indices de la tabla `otb`
--
ALTER TABLE `otb`
  ADD PRIMARY KEY (`Otb_ID`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`User_ID`),
  ADD KEY `fk_user_otb1_idx` (`Otb_ID`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alarm`
--
ALTER TABLE `alarm`
  MODIFY `Alarm_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `alert_type`
--
ALTER TABLE `alert_type`
  MODIFY `Alert_type_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `camera`
--
ALTER TABLE `camera`
  MODIFY `Camera_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `otb`
--
ALTER TABLE `otb`
  MODIFY `Otb_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `User_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alarm`
--
ALTER TABLE `alarm`
  ADD CONSTRAINT `fk_alarm_otb1` FOREIGN KEY (`otb_ID`) REFERENCES `otb` (`otb_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `alert`
--
ALTER TABLE `alert`
  ADD CONSTRAINT `fk_activity_otb1` FOREIGN KEY (`otb_ID`) REFERENCES `otb` (`otb_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_alert_alert_type1` FOREIGN KEY (`alert_type_ID`) REFERENCES `alert_type` (`alert_type_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_alert_user1` FOREIGN KEY (`user_ID`) REFERENCES `user` (`user_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `alert_type`
--
ALTER TABLE `alert_type`
  ADD CONSTRAINT `fk_alert_type_otb1` FOREIGN KEY (`otb_ID`) REFERENCES `otb` (`otb_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `camera`
--
ALTER TABLE `camera`
  ADD CONSTRAINT `fk_camera_otb1` FOREIGN KEY (`otb_ID`) REFERENCES `otb` (`otb_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_otb1` FOREIGN KEY (`otb_ID`) REFERENCES `otb` (`otb_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
