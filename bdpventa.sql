SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `bdpventa` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `bdpventa`;

CREATE TABLE `faccab` (
  `numero_factura` int(11) NOT NULL,
  `fecha_de_factura` datetime DEFAULT NULL,
  `monto_total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `facdet` (
  `numero_factura` int(11) NOT NULL,
  `codigoQR` bigint(20) NOT NULL,
  `descripcion` varchar(50) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `productos` (
  `codigoQR` bigint(11) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


ALTER TABLE `faccab`
  ADD PRIMARY KEY (`numero_factura`);

ALTER TABLE `facdet`
  ADD PRIMARY KEY (`numero_factura`,`codigoQR`);

ALTER TABLE `productos`
  ADD PRIMARY KEY (`codigoQR`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
