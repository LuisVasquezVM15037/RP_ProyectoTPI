-- Insertar categoría de Plantas
INSERT INTO categoria (nombre_categoria) VALUES ('Plantas');

-- Insertar productos de plantas
INSERT INTO producto (id_categoria, nombre_producto, sku, descripcion, precio_unitario, stock, imagen_url, proveedor) VALUES
((SELECT id_categoria FROM categoria WHERE nombre_categoria = 'Plantas'), 'Petunias', 'PLT001', 'Hermosas petunias para decorar tu jardín', 4.99, 50, '../Images/plantas/petunias.jpg', 'Vivero El Paraíso'),
((SELECT id_categoria FROM categoria WHERE nombre_categoria = 'Plantas'), 'Romero', 'PLT002', 'Planta aromática ideal para cocina', 3.99, 40, '../Images/plantas/romero.jpg', 'Vivero El Paraíso'),
((SELECT id_categoria FROM categoria WHERE nombre_categoria = 'Plantas'), 'Diente de Leon', 'PLT003', 'Planta medicinal tradicional', 2.99, 30, '../Images/plantas/diente_de_leon.jpg', 'Vivero El Paraíso'),
((SELECT id_categoria FROM categoria WHERE nombre_categoria = 'Plantas'), 'Albahaca', 'PLT004', 'Hierba aromática fresca', 3.50, 45, '../Images/plantas/albahaca.jpg', 'Vivero El Paraíso'),
((SELECT id_categoria FROM categoria WHERE nombre_categoria = 'Plantas'), 'Aloe Vera', 'PLT005', 'Planta medicinal y decorativa', 5.99, 35, '../Images/plantas/aloe_vera.jpg', 'Vivero El Paraíso'),
((SELECT id_categoria FROM categoria WHERE nombre_categoria = 'Plantas'), 'Aloe Vera con Flor', 'PLT006', 'Aloe vera decorativo con flores', 7.99, 20, '../Images/plantas/Aloe_Vera_Flores.jpg', 'Vivero El Paraíso'),
((SELECT id_categoria FROM categoria WHERE nombre_categoria = 'Plantas'), 'Cinta', 'PLT007', 'Planta decorativa de interior', 4.50, 25, '../Images/plantas/cinta.jpg', 'Vivero El Paraíso'),
((SELECT id_categoria FROM categoria WHERE nombre_categoria = 'Plantas'), 'Azalea', 'PLT008', 'Arbusto con hermosas flores', 8.99, 15, '../Images/plantas/azalea.jpg', 'Vivero El Paraíso'),
((SELECT id_categoria FROM categoria WHERE nombre_categoria = 'Plantas'), 'Clavel', 'PLT009', 'Flores coloridas y fragantes', 3.99, 40, '../Images/plantas/clavel.jpg', 'Vivero El Paraíso'),
((SELECT id_categoria FROM categoria WHERE nombre_categoria = 'Plantas'), 'Hortensia', 'PLT010', 'Arbusto con flores abundantes', 9.99, 20, '../Images/plantas/hortensia.jpg', 'Vivero El Paraíso'),
((SELECT id_categoria FROM categoria WHERE nombre_categoria = 'Plantas'), 'Margarita', 'PLT011', 'Flores blancas y amarillas', 3.99, 35, '../Images/plantas/margarita.jpg', 'Vivero El Paraíso'),
((SELECT id_categoria FROM categoria WHERE nombre_categoria = 'Plantas'), 'Orquídea', 'PLT012', 'Orquídea elegante y delicada', 12.99, 10, '../Images/plantas/orquidea.jpg', 'Vivero El Paraíso');