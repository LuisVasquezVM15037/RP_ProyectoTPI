-- =====================================================
-- Base de Datos Grupo3_BDAppWeb
-- =====================================================

CREATE DATABASE IF NOT EXISTS Grupo3_BDAppWeb;
USE Grupo3_BDAppWeb;

-- =====================================================
-- TABLA: Usuario
-- =====================================================
CREATE TABLE Usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(50) NOT NULL,
    apellido_usuario VARCHAR(50) NOT NULL,
    -- UNIQUE: Previene emails duplicados ya que es credencial de acceso
    email_usuario VARCHAR(50) NOT NULL UNIQUE,
    contrasenia_usuario VARCHAR(16) NOT NULL,
    direccion_usuario VARCHAR(150),
    telefono_usuario INT,
    rol_usuario INT NOT NULL DEFAULT 0,
    -- TIMESTAMP: Registra automáticamente la fecha/hora de creación
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    -- CHECK: Valida que rol_usuario solo sea 0 (usuario) o 1 (admin)
    CONSTRAINT chk_rol_usuario CHECK (rol_usuario IN (0, 1)),
    CONSTRAINT chk_telefono_length CHECK (telefono_usuario IS NULL OR telefono_usuario > 0)
);

-- =====================================================
-- TABLA: Categoria
-- =====================================================
CREATE TABLE Categoria (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    -- UNIQUE: Evita nombres de categoría duplicados
    nombre_categoria VARCHAR(50) NOT NULL UNIQUE
);

-- =====================================================
-- TABLA: Producto
-- =====================================================
CREATE TABLE Producto (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    id_categoria INT NOT NULL,
    nombre_producto VARCHAR(50) NOT NULL,
    -- UNIQUE: SKU es código único de identificación del producto
    sku VARCHAR(10) NOT NULL UNIQUE,
    descripcion VARCHAR(250) NOT NULL,
    precio_unitario FLOAT NOT NULL,
    -- DEFAULT: Inicializa stock en 0 si no se especifica valor
    stock INT NOT NULL DEFAULT 0,
    imagen_url VARCHAR(350),
    proveedor VARCHAR(100),
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    -- FOREIGN KEY: Vincula producto a su categoría
    CONSTRAINT fk_producto_categoria FOREIGN KEY (id_categoria) REFERENCES Categoria(id_categoria),
    -- CHECK: El precio debe ser positivo
    CONSTRAINT chk_precio_unitario CHECK (precio_unitario > 0),
    -- CHECK: El stock no puede ser negativo
    CONSTRAINT chk_stock CHECK (stock >= 0)
);

-- =====================================================
-- TABLA: Pedido
-- =====================================================
CREATE TABLE Pedido (
    id_pedido INT AUTO_INCREMENT PRIMARY KEY,
    -- NULLABLE: Permite compras anónimas (usuario no registrado)
    id_usuario INT,
    fecha_pedido DATE NOT NULL,
    -- DEFAULT: Estado inicial es 1 (pendiente)
    estado_pedido INT NOT NULL DEFAULT 1,
    total_pedido FLOAT NOT NULL,
    direccion_envio VARCHAR(200) NOT NULL,
    impuesto_IVA FLOAT NOT NULL,
    metodo_pago INT NOT NULL,
    -- Email del comprador anónimo (cuando id_usuario es NULL)
    email_comprador_anonimo VARCHAR(50),
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    -- FOREIGN KEY: Vincula pedido al usuario que lo realizó (opcional)
    CONSTRAINT fk_pedido_usuario FOREIGN KEY (id_usuario) REFERENCES Usuario(id_usuario),
    -- CHECK: estado_pedido puede ser 1 (pendiente) o 2 (pagado)
    CONSTRAINT chk_estado_pedido CHECK (estado_pedido IN (1, 2)),
    -- CHECK: metodo_pago debe estar entre 0 y 5 (efectivo, tarjeta, transferencia, Paypal, plazo)
    CONSTRAINT chk_metodo_pago CHECK (metodo_pago BETWEEN 0 AND 5),
    CONSTRAINT chk_total_pedido CHECK (total_pedido > 0),
    CONSTRAINT chk_impuesto_IVA CHECK (impuesto_IVA >= 0),
    -- CHECK: Valida que si es compra anónima (id_usuario NULL), debe tener email
    CONSTRAINT chk_comprador_valido CHECK (id_usuario IS NOT NULL OR email_comprador_anonimo IS NOT NULL)
);

-- =====================================================
-- TABLA: DetallePedido
-- =====================================================
CREATE TABLE DetallePedido (
    id_detalle_pedido INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad_producto INT NOT NULL,
    total_detalle_pedido FLOAT NOT NULL,
    -- Captura el precio del producto en el momento de la compra (auditoría histórica)
    precio_unitario_capturado FLOAT NOT NULL,
    -- ON DELETE CASCADE: Si se elimina un pedido, también se eliminan sus detalles automáticamente
    CONSTRAINT fk_detalle_pedido_pedido FOREIGN KEY (id_pedido) REFERENCES Pedido(id_pedido) ON DELETE CASCADE,
    CONSTRAINT fk_detalle_pedido_producto FOREIGN KEY (id_producto) REFERENCES Producto(id_producto),
    CONSTRAINT chk_cantidad_producto CHECK (cantidad_producto > 0),
    CONSTRAINT chk_total_detalle CHECK (total_detalle_pedido > 0),
    CONSTRAINT chk_precio_capturado CHECK (precio_unitario_capturado > 0)
);

-- =====================================================
-- TABLA: Pago (Consolidada - Reemplaza a Cuota)
-- =====================================================
CREATE TABLE Pago (
    id_pago INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT NOT NULL,
    fecha_pago DATE NOT NULL,
    monto_pago FLOAT NOT NULL,
    -- Indica si el pedido se pagará a plazo
    es_pago_a_plazo BOOLEAN DEFAULT FALSE,
    -- Número de cuota actual (ej: 1 para primera cuota, 2 para segunda, etc.)
    numero_cuota INT,
    -- Total de cuotas programadas para el pedido (ej: 3 para pago en 3 cuotas)
    total_cuotas INT,
    -- Estado de la cuota: 1=pagada, 2=pendiente, 3=vencida
    estado_cuota INT DEFAULT 1,
    fecha_vencimiento_cuota DATE,
    -- Descripción opcional del pago
    descripcion_pago VARCHAR(200),
    -- FOREIGN KEY: Vincula pago a su pedido
    CONSTRAINT fk_pago_pedido FOREIGN KEY (id_pedido) REFERENCES Pedido(id_pedido),
    CONSTRAINT chk_monto_pago CHECK (monto_pago > 0),
    -- CHECK: Si es a plazo, debe tener número y total de cuotas
    CONSTRAINT chk_cuotas_validas CHECK (
        (es_pago_a_plazo = FALSE) OR 
        (es_pago_a_plazo = TRUE AND numero_cuota IS NOT NULL AND total_cuotas IS NOT NULL)
    ),
    -- CHECK: El número de cuota no puede exceder total de cuotas
    CONSTRAINT chk_numero_cuota_valido CHECK (numero_cuota IS NULL OR numero_cuota <= total_cuotas),
    -- CHECK: Estado de cuota debe ser 1, 2 o 3
    CONSTRAINT chk_estado_cuota CHECK (estado_cuota IN (1, 2, 3))
);

-- =====================================================
-- TABLA: Resenia
-- =====================================================
CREATE TABLE Resenia (
    id_resenia INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_producto INT NOT NULL,
    comentario VARCHAR(300),
    calificacion INT NOT NULL,
    fecha_resenia DATE NOT NULL,
    CONSTRAINT fk_resenia_usuario FOREIGN KEY (id_usuario) REFERENCES Usuario(id_usuario),
    CONSTRAINT fk_resenia_producto FOREIGN KEY (id_producto) REFERENCES Producto(id_producto),
    -- CHECK: Calificación solo permite valores entre 1 y 5 estrellas
    CONSTRAINT chk_calificacion CHECK (calificacion BETWEEN 1 AND 5),
    -- UNIQUE: Cada usuario puede reseñar un producto una sola vez
    CONSTRAINT unique_resenia UNIQUE (id_usuario, id_producto)
);
