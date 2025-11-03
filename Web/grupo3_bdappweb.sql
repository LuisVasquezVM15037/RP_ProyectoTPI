-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-10-2025 a las 07:35:38
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
-- Base de datos: `grupo3_bdappweb`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL,
  `nombre_categoria` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id_categoria`, `nombre_categoria`) VALUES
(4, 'Abonos y Fertilizantes'),
(3, 'Herramientas'),
(1, 'Plantas'),
(2, 'Semillas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detallepedido`
--

CREATE TABLE `detallepedido` (
  `id_detalle_pedido` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad_producto` int(11) NOT NULL,
  `total_detalle_pedido` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detallepedido`
--

INSERT INTO `detallepedido` (`id_detalle_pedido`, `id_pedido`, `id_producto`, `cantidad_producto`, `total_detalle_pedido`) VALUES
(1, 1, 68, 5, 16.25),
(2, 2, 68, 5, 16.25);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago`
--

CREATE TABLE `pago` (
  `id_pago` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `fecha_pago` date NOT NULL,
  `monto_pago` float NOT NULL,
  `es_pago_a_plazo` tinyint(1) DEFAULT 0,
  `numero_cuota` int(11) DEFAULT NULL,
  `total_cuotas` int(11) DEFAULT NULL,
  `estado_cuota` int(11) DEFAULT 1,
  `fecha_vencimiento_cuota` date DEFAULT NULL,
  `descripcion_pago` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pago`
--

INSERT INTO `pago` (`id_pago`, `id_pedido`, `fecha_pago`, `monto_pago`, `es_pago_a_plazo`, `numero_cuota`, `total_cuotas`, `estado_cuota`, `fecha_vencimiento_cuota`, `descripcion_pago`) VALUES
(1, 2, '2025-10-26', 16.25, 0, NULL, NULL, 1, NULL, 'Pago único al confirmar compra');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `id_pedido` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `fecha_pedido` date NOT NULL,
  `estado_pedido` int(11) NOT NULL DEFAULT 1,
  `total_pedido` float NOT NULL,
  `direccion_envio` varchar(200) NOT NULL,
  `impuesto_IVA` float NOT NULL,
  `metodo_pago` int(11) NOT NULL,
  `email_comprador_anonimo` varchar(50) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido`
--

INSERT INTO `pedido` (`id_pedido`, `id_usuario`, `fecha_pedido`, `estado_pedido`, `total_pedido`, `direccion_envio`, `impuesto_IVA`, `metodo_pago`, `email_comprador_anonimo`, `fecha_creacion`) VALUES
(1, NULL, '2025-10-26', 1, 16.25, 'No especificada', 0.13, 1, 'invitado@tiendaverde.com', '2025-10-27 05:17:13'),
(2, NULL, '2025-10-26', 1, 16.25, 'No especificada', 0.13, 1, 'invitado@tiendaverde.com', '2025-10-27 05:21:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id_producto` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `nombre_producto` varchar(50) NOT NULL,
  `sku` varchar(10) NOT NULL,
  `descripcion` varchar(250) NOT NULL,
  `precio_unitario` float NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `imagen_url` varchar(350) DEFAULT NULL,
  `proveedor` varchar(100) DEFAULT NULL,
  `caracteristicas` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id_producto`, `id_categoria`, `nombre_producto`, `sku`, `descripcion`, `precio_unitario`, `stock`, `imagen_url`, `proveedor`, `caracteristicas`) VALUES
(68, 1, 'Berro', 'PLT013', 'Planta comestible rica en nutrientes. Ideal para ensaladas y huertos caseros.', 3.25, 25, 'productos/Berro.jpg', 'Hierbas Naturales', ''),
(69, 1, 'Borraja', 'PLT014', 'Planta herbácea de flores azules. Muy utilizada en infusiones y huertos.', 3.75, 30, 'productos/Borraja.jpg', 'Hierbas Naturales', ''),
(70, 1, 'Cebolla de Reliquia', 'PLT015', 'Variedad antigua de cebolla con sabor intenso. Ideal para cultivo tradicional.', 2.99, 40, 'productos/Cebolla_de_Reliquia.jpg', 'Huertos Selectos', ''),
(71, 1, 'Cebolla Roja', 'PLT016', 'Cebolla de bulbo rojizo. Ideal para cocina y cultivo doméstico.', 3.25, 45, 'productos/Cebolla_Roja.jpg', 'Huertos Selectos', ''),
(72, 1, 'Lavanda', 'PLT017', 'Planta aromática con flores lilas. Muy usada en aromaterapia y jardines.', 5.25, 40, 'productos/Lavanda.jpg', 'Hierbas Naturales', ''),
(73, 1, 'Mejorana', 'PLT018', 'Hierba aromática suave. Ideal para uso culinario y ornamental.', 4.5, 35, 'productos/Mejorana.jpg', 'Hierbas Naturales', ''),
(74, 1, 'Salvia', 'PLT019', 'Hierba aromática de fácil cultivo. Usada en gastronomía y medicina natural.', 4.25, 38, 'productos/Salvia.jpg', 'Hierbas Naturales', ''),
(75, 1, 'Tomillo', 'PLT020', 'Planta aromática muy resistente. Perfecta para huertos caseros.', 4.1, 42, 'productos/Tomillo.jpg', 'Hierbas Naturales', ''),
(76, 1, 'Zanahoria', 'PLT021', 'Planta hortícola de raíz comestible. Ideal para huertos familiares.', 2.85, 50, 'productos/Zanahoria.jpg', 'Huertos Selectos', ''),
(77, 2, 'Semillas Berro', 'SEM006', 'Semillas de berro de alta germinación. Ideal para cultivo hidropónico y en macetas.', 2.75, 120, 'productos/semillas_berro.jpg', 'Semillas del Campo', ''),
(78, 2, 'Semillas Borraja', 'SEM007', 'Semillas de borraja. Germinación rápida y cultivo sencillo.', 2.99, 110, 'productos/semillas_borraja.jpg', 'Semillas del Campo', ''),
(79, 2, 'Semillas Calabaza', 'SEM008', 'Semillas de calabaza dulce. Excelente rendimiento y sabor.', 3.25, 130, 'productos/semillas_calabaza.jpg', 'Semillas Selectas', ''),
(80, 2, 'Semillas Cebolla Roja', 'SEM009', 'Semillas de cebolla roja para siembra tradicional. Germinación confiable.', 2.5, 140, 'productos/semillas_cebolla_roja.jpg', 'Semillas del Campo', ''),
(81, 2, 'Semillas Tomate', 'SEM010', 'Semillas de tomate. Frutos rojos jugosos. Ideal para huertos caseros.', 3.25, 150, 'productos/semillas_tomate.jpg', 'Semillas Selectas', ''),
(82, 2, 'Semillas Tomillo', 'SEM011', 'Semillas de tomillo aromático. Alta germinación.', 2.99, 130, 'productos/semillas_tomillo.jpg', 'Semillas del Campo', ''),
(83, 2, 'Semillas Zanahoria', 'SEM012', 'Semillas de zanahoria nantesa. Muy productiva y fácil de cultivar.', 2.85, 140, 'productos/semillas_zanahoria.jpg', 'Semillas Selectas', ''),
(84, 4, 'Compost Líquido Orgánico', 'ABO009', 'Fertilizante líquido orgánico. Mejora la estructura del suelo y promueve raíces fuertes.', 11.25, 50, 'productos/Compost_liquido_organico.jpg', 'Fertilizantes Naturales', ''),
(85, 4, 'Compost Orgánico 1 Galón', 'ABO010', 'Compost orgánico líquido en presentación de 1 galón. Aumenta la fertilidad del suelo.', 13.5, 45, 'productos/Compost_organico_1_galon.jpg', 'Fertilizantes Naturales', ''),
(86, 4, 'Compost Orgánico', 'ABO011', 'Abono orgánico sólido para enriquecer el suelo. Mejora la retención de agua.', 14.25, 55, 'productos/Compost_organico.jpg', 'Fertilizantes Naturales', ''),
(87, 4, 'Fertilizante Multiusos', 'ABO012', 'Fertilizante balanceado para distintos tipos de plantas. Aumenta el crecimiento saludable.', 9.75, 60, 'productos/Fertilizante_multiusos.jpg', 'Nutriplant', ''),
(88, 4, 'Fertilizante General', 'ABO013', 'Fertilizante universal para plantas ornamentales y huertos.', 8.5, 70, 'productos/Fertilizante_general.jpg', 'Nutriplant', ''),
(89, 3, 'Guante Acolchado', 'HER001', 'Guantes de jardinería con acolchado interno. Protección y comodidad garantizada.', 8.99, 75, 'productos/guante_acolchado.jpg', 'Herramientas Pro', NULL),
(90, 3, 'Guante Algodón', 'HER002', 'Guantes de algodón transpirable. Ideal para trabajos ligeros de jardinería.', 4.5, 100, 'productos/guante_algodon.jpg', 'Herramientas Pro', NULL),
(91, 3, 'Guante Básico', 'HER003', 'Guantes básicos de jardinería. Económicos y funcionales para uso general.', 3.25, 120, 'productos/guante_basico.jpg', 'Herramientas Pro', NULL),
(92, 3, 'Guante Cuero', 'HER004', 'Guantes de cuero resistente. Protección superior para trabajos pesados.', 15.99, 40, 'productos/guante_cuero.jpg', 'Herramientas Pro', NULL),
(93, 3, 'Guante Hombre', 'HER005', 'Guantes talla grande para hombre. Resistentes y duraderos.', 9.75, 60, 'productos/guante_hombre.jpg', 'Herramientas Pro', NULL),
(94, 3, 'Guante Largo', 'HER006', 'Guantes largos hasta el antebrazo. Protección extra contra espinas y maleza.', 11.5, 45, 'productos/guante_largo.jpg', 'Herramientas Pro', NULL),
(95, 3, 'Guante Latex', 'HER007', 'Guantes de látex impermeables. Ideales para trabajos con agua y químicos.', 6.99, 85, 'productos/guante_latex.jpg', 'Herramientas Pro', NULL),
(96, 3, 'Guante Malla', 'HER008', 'Guantes con dorso de malla transpirable. Frescura en climas cálidos.', 7.5, 70, 'productos/guante_malla.jpg', 'Herramientas Pro', NULL),
(97, 3, 'Guante Mujer', 'HER009', 'Guantes diseñados específicamente para manos de mujer. Ajuste perfecto.', 8.25, 65, 'productos/guante_mujer.jpg', 'Herramientas Pro', NULL),
(98, 3, 'Guante Multiusos', 'HER010', 'Guantes versátiles para múltiples tareas de jardinería y hogar.', 9.99, 80, 'productos/guante_multiusos.jpg', 'Herramientas Pro', NULL),
(99, 3, 'Guante Neopreno', 'HER011', 'Guantes de neopreno resistente al agua. Excelente agarre en húmedo.', 12.75, 50, 'productos/guante_neopreno.jpg', 'Herramientas Pro', NULL),
(100, 3, 'Guante Niños', 'HER012', 'Guantes pequeños para niños. Seguros y cómodos para pequeños jardineros.', 5.5, 90, 'productos/guante_niños.jpg', 'Herramientas Pro', NULL),
(101, 3, 'Guante Nitrilo', 'HER013', 'Guantes de nitrilo profesional. Resistencia química y durabilidad superior.', 10.5, 55, 'productos/guante_nitrilo.jpg', 'Herramientas Pro', NULL),
(102, 3, 'Guante Piel', 'HER014', 'Guantes de piel natural. Máxima protección y confort para trabajos intensivos.', 18.99, 30, 'productos/guante_piel.jpg', 'Herramientas Pro', NULL),
(103, 3, 'Guante Premium', 'HER015', 'Guantes premium de alta gama. Tecnología ergonómica y materiales superiores.', 22.5, 25, 'productos/guante_premium.jpg', 'Herramientas Pro', NULL),
(104, 3, 'Guante Reflectante', 'HER016', 'Guantes con franjas reflectantes. Visibilidad y seguridad en poca luz.', 13.99, 35, 'productos/guante_reflectante.jpg', 'Herramientas Pro', NULL),
(105, 3, 'Guante Reforzado', 'HER017', 'Guantes con refuerzos en palma y dedos. Extra durabilidad para uso intensivo.', 14.5, 45, 'productos/guante_reforzado.jpg', 'Herramientas Pro', NULL),
(106, 3, 'Guante Sensible', 'HER018', 'Guantes de tacto fino. Precisión para trabajos delicados como trasplantes.', 11.25, 60, 'productos/guante_sensible.jpg', 'Herramientas Pro', NULL),
(107, 3, 'Guante Tela', 'HER019', 'Guantes de tela resistente. Transpirables y cómodos para uso prolongado.', 6.5, 95, 'productos/guante_tela.jpg', 'Herramientas Pro', NULL),
(108, 3, 'Guante Thermal', 'HER020', 'Guantes térmicos para climas fríos. Aislamiento sin perder destreza.', 16.75, 40, 'productos/guante_thermal.jpg', 'Herramientas Pro', NULL),
(109, 3, 'Guante Verde', 'HER021', 'Guantes de color verde jardín. Funcionales y estéticos.', 7.99, 70, 'productos/guante_verde.jpg', 'Herramientas Pro', NULL),
(110, 3, 'Pala Acero Inoxidable', 'HER022', 'Pala de acero inoxidable anticorrosión. Durabilidad excepcional.', 24.99, 30, 'productos/pala_acero_inoxidable.jpg', 'Jardín Tools', NULL),
(111, 3, 'Pala Aluminio', 'HER023', 'Pala ligera de aluminio. Fácil manejo y resistente a oxidación.', 19.5, 35, 'productos/pala_aluminio.jpg', 'Jardín Tools', NULL),
(112, 3, 'Pala Ancha', 'HER024', 'Pala de hoja ancha para mover tierra y compost eficientemente.', 21.75, 28, 'productos/pala_ancha.jpg', 'Jardín Tools', NULL),
(113, 3, 'Pala Carbón', 'HER025', 'Pala de acero al carbón. Resistente para trabajos pesados de excavación.', 18.99, 40, 'productos/pala_carbon.jpg', 'Jardín Tools', NULL),
(114, 3, 'Pala con Dientes', 'HER026', 'Pala dentada para romper tierra compacta. Multifuncional.', 23.5, 25, 'productos/pala_con_dientes.jpg', 'Jardín Tools', NULL),
(115, 3, 'Pala con Medidor', 'HER027', 'Pala con marcas de medición. Ideal para siembra a profundidad específica.', 26.99, 20, 'productos/pala_con_medidor.jpg', 'Jardín Tools', NULL),
(116, 3, 'Pala Doble Función', 'HER028', 'Pala 2 en 1 con función de rastrillo. Versatilidad en una herramienta.', 29.5, 22, 'productos/pala_doble_funcion.jpg', 'Jardín Tools', NULL),
(117, 3, 'Pala Doble Punta', 'HER029', 'Pala con extremo de doble punta. Para cavar y aflojar raíces.', 25.75, 24, 'productos/pala_doble_punta.jpg', 'Jardín Tools', NULL),
(118, 3, 'Pala Ergonómica', 'HER030', 'Pala con mango ergonómico. Reduce fatiga en uso prolongado.', 31.99, 18, 'productos/pala_ergonomica.jpg', 'Jardín Tools', NULL),
(119, 3, 'Pala Fibra Vidrio', 'HER031', 'Pala con mango de fibra de vidrio. Ultra resistente y liviana.', 34.5, 15, 'productos/pala_fibra_vidrio.jpg', 'Jardín Tools', NULL),
(120, 3, 'Pala Jardín Profesional', 'HER032', 'Pala profesional de alta resistencia. Para uso intensivo en jardinería.', 42.99, 12, 'productos/pala_jardin_profesional.jpg', 'Jardín Tools', NULL),
(121, 3, 'Pala Mano', 'HER033', 'Pala pequeña de mano. Perfecta para macetas y trabajos de precisión.', 8.5, 80, 'productos/pala_mano.jpg', 'Jardín Tools', NULL),
(122, 3, 'Pala Mini Jardinería', 'HER034', 'Mini pala para jardinería en espacios reducidos. Compacta y eficiente.', 6.99, 95, 'productos/pala_mini_jardineria.jpg', 'Jardín Tools', NULL),
(123, 3, 'Pala Nieve Curva', 'HER035', 'Pala curva multiuso. También útil para nieve y materiales sueltos.', 28.5, 20, 'productos/pala_nieve_curva.jpg', 'Jardín Tools', NULL),
(124, 3, 'Pala Pico Combinada', 'HER036', 'Herramienta combinada pala-pico. Versatilidad para terrenos difíciles.', 38.75, 15, 'productos/pala_pico_combinada.jpg', 'Jardín Tools', NULL),
(125, 3, 'Pala Plegable Camping', 'HER037', 'Pala plegable portátil. Ideal para camping y jardinería móvil.', 22.99, 30, 'productos/pala_plegable_camping.jpg', 'Jardín Tools', NULL),
(126, 3, 'Pala Puntiaguda', 'HER038', 'Pala de punta afilada. Excelente para penetrar suelos duros.', 20.5, 35, 'productos/pala_puntiaguda.jpg', 'Jardín Tools', NULL),
(127, 3, 'Pala Raíces', 'HER039', 'Pala especializada para extraer raíces. Diseño robusto y funcional.', 33.99, 18, 'productos/pala_raices.jpg', 'Jardín Tools', NULL),
(128, 3, 'Pala Recta Profesional', 'HER040', 'Pala recta de uso profesional. Precisión en cortes y excavaciones.', 36.5, 16, 'productos/pala_recta_profesional.jpg', 'Jardín Tools', NULL),
(129, 3, 'Pala Trasplante', 'HER041', 'Pala estrecha para trasplantar. Minimiza daño a raíces durante el movimiento.', 17.99, 45, 'productos/pala_trasplante.jpg', 'Jardín Tools', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resenia`
--

CREATE TABLE `resenia` (
  `id_resenia` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `comentario` varchar(300) DEFAULT NULL,
  `calificacion` int(11) NOT NULL,
  `fecha_resenia` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `apellido_usuario` varchar(50) NOT NULL,
  `email_usuario` varchar(50) NOT NULL,
  `contrasenia_usuario` varchar(16) NOT NULL,
  `direccion_usuario` varchar(150) DEFAULT NULL,
  `telefono_usuario` int(11) DEFAULT NULL,
  `rol_usuario` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nombre_usuario`, `apellido_usuario`, `email_usuario`, `contrasenia_usuario`, `direccion_usuario`, `telefono_usuario`, `rol_usuario`) VALUES
(4, 'Luis', 'Vasquez', 'vm15037@ti.ues.edu.sv', 'Admin123', 'Mi casa', 22577777, 1),
(5, 'Luis', 'Vasquez', 'luizitoelca@hotmail.com', 'Admin123', 'Mi casa', 22577777, 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`),
  ADD UNIQUE KEY `nombre_categoria` (`nombre_categoria`);

--
-- Indices de la tabla `detallepedido`
--
ALTER TABLE `detallepedido`
  ADD PRIMARY KEY (`id_detalle_pedido`),
  ADD KEY `fk_detalle_pedido_pedido` (`id_pedido`),
  ADD KEY `fk_detalle_pedido_producto` (`id_producto`);

--
-- Indices de la tabla `pago`
--
ALTER TABLE `pago`
  ADD PRIMARY KEY (`id_pago`),
  ADD KEY `fk_pago_pedido` (`id_pedido`);

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `fk_pedido_usuario` (`id_usuario`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id_producto`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `fk_producto_categoria` (`id_categoria`);

--
-- Indices de la tabla `resenia`
--
ALTER TABLE `resenia`
  ADD PRIMARY KEY (`id_resenia`),
  ADD UNIQUE KEY `unique_resenia` (`id_usuario`,`id_producto`),
  ADD KEY `fk_resenia_producto` (`id_producto`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email_usuario` (`email_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `detallepedido`
--
ALTER TABLE `detallepedido`
  MODIFY `id_detalle_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `pago`
--
ALTER TABLE `pago`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

--
-- AUTO_INCREMENT de la tabla `resenia`
--
ALTER TABLE `resenia`
  MODIFY `id_resenia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detallepedido`
--
ALTER TABLE `detallepedido`
  ADD CONSTRAINT `fk_detalle_pedido_pedido` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_detalle_pedido_producto` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`);

--
-- Filtros para la tabla `pago`
--
ALTER TABLE `pago`
  ADD CONSTRAINT `fk_pago_pedido` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`);

--
-- Filtros para la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `fk_pedido_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `fk_producto_categoria` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`);

--
-- Filtros para la tabla `resenia`
--
ALTER TABLE `resenia`
  ADD CONSTRAINT `fk_resenia_producto` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`),
  ADD CONSTRAINT `fk_resenia_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
