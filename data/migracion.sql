-- codigo para cargar la base de datos correctamente --

CREATE DATABASE db_mainbase;

use db_mainbase;

CREATE TABLE tickets (
  id INT(100) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombrecompleto VARCHAR(100) NOT NULL,
  correo  VARCHAR(100) NOT NULL,
  ubicacion VARCHAR(100) NOT NULL,
  descripcion VARCHAR(250) NOT NULL,
  urgencia VARCHAR(100) NOT NULL,
  respuesta VARCHAR(250) NOT NULL,
  estado VARCHAR(250) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tickets_m (
  id INT(100) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombrecompleto VARCHAR(100) NOT NULL,
  correo  VARCHAR(100) NOT NULL,
  ubicacion VARCHAR(100) NOT NULL,
  descripcion VARCHAR(250) NOT NULL,
  urgencia VARCHAR(100) NOT NULL,
  respuesta VARCHAR(250) NOT NULL,
  estado VARCHAR(250) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- tabla de datos para el daly plan--

CREATE DATABASE daily_plan;

use daily_plan;

CREATE TABLE export (
    id INT(100) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    aid_oid VARCHAR(255) NOT NULL,
    cliente VARCHAR(255) NOT NULL,
    vehiculo VARCHAR(255) NOT NULL,
    t_vehiculo VARCHAR(255),
    bl VARCHAR(255),
    destino VARCHAR(255),
    paletas INT,
    cajas INT,
    unidades INT,
    pedidos_en_proceso INT NOT NULL,
    pedidos_despachados INT NOT NULL,
    M_cambio VARCHAR(250) NOT NULL,
    grafica_dp INT GENERATED ALWAYS AS (pedidos_en_proceso - pedidos_despachados) STORED,
    division_dp DECIMAL(10,2) GENERATED ALWAYS AS (pedidos_despachados / pedidos_en_proceso) STORED,
    fecha_objetivo DATE NOT NULL,
    eta_etd DATE GENERATED ALWAYS AS (fecha_objetivo) STORED,
    comentario_oficina VARCHAR(300),
    comentario_bodega VARCHAR(300),
    vacio_lleno VARCHAR(300),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE export_r (
    id INT(100) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    aid_oid VARCHAR(255) NOT NULL,
    cliente VARCHAR(255) NOT NULL,
    vehiculo VARCHAR(255) NOT NULL,
    t_vehiculo VARCHAR(255),
    bl VARCHAR(255),
    destino VARCHAR(255),
    paletas INT,
    cajas INT,
    unidades INT,
    pedidos_en_proceso INT NOT NULL,
    pedidos_despachados INT NOT NULL,
    M_cambio VARCHAR(250) NOT NULL,
    grafica_dp INT GENERATED ALWAYS AS (pedidos_en_proceso - pedidos_despachados) STORED,
    division_dp DECIMAL(10,2) GENERATED ALWAYS AS (pedidos_despachados / pedidos_en_proceso) STORED,
    fecha_objetivo DATE NOT NULL,
    eta_etd DATE GENERATED ALWAYS AS (fecha_objetivo) STORED,
    comentario_oficina VARCHAR(300),
    comentario_bodega VARCHAR(300),
    vacio_lleno VARCHAR(300),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE import (
    id INT(100) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    aid_oid VARCHAR(255) NOT NULL,
    cliente VARCHAR(255) NOT NULL,
    vehiculo VARCHAR(255) NOT NULL,
    t_vehiculo VARCHAR(255),
    bl VARCHAR(255),
    destino VARCHAR(255),
    paletas INT,
    cajas INT,
    unidades INT,
    contenedor_recibido INT NOT NULL,
    contenedor_cerrado INT NOT NULL,
    M_cambio VARCHAR(250) NOT NULL,
    grafica_dp INT GENERATED ALWAYS AS (contenedor_recibido - contenedor_cerrado) STORED,
    division_dp DECIMAL(10,2) GENERATED ALWAYS AS (contenedor_cerrado / contenedor_recibido) STORED,
    fecha_objetivo DATE NOT NULL,
    eta_etd DATE GENERATED ALWAYS AS (fecha_objetivo) STORED,
    comentario_oficina VARCHAR(300),
    comentario_bodega VARCHAR(300),
    vacio_lleno VARCHAR(300),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE import_r (
    id INT(100) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    aid_oid VARCHAR(255) NOT NULL,
    cliente VARCHAR(255) NOT NULL,
    vehiculo VARCHAR(255) NOT NULL,
    t_vehiculo VARCHAR(255),
    bl VARCHAR(255),
    destino VARCHAR(255),
    paletas INT,
    cajas INT,
    unidades INT,
    contenedor_recibido INT NOT NULL,
    contenedor_cerrado INT NOT NULL,
    M_cambio VARCHAR(250) NOT NULL,
    grafica_dp INT GENERATED ALWAYS AS (contenedor_recibido - contenedor_cerrado) STORED,
    division_dp DECIMAL(10,2) GENERATED ALWAYS AS (contenedor_cerrado / contenedor_recibido) STORED,
    fecha_objetivo DATE NOT NULL,
    eta_etd DATE GENERATED ALWAYS AS (fecha_objetivo) STORED,
    comentario_oficina VARCHAR(300),
    comentario_bodega VARCHAR(300),
    vacio_lleno VARCHAR(300),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE picking (
    id INT(100) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    aid_oid VARCHAR(255) NOT NULL,
    cliente VARCHAR(255) NOT NULL,
    vehiculo VARCHAR(255) NOT NULL,
    t_vehiculo VARCHAR(255),
    bl VARCHAR(255),
    destino VARCHAR(255),
    paletas INT,
    cajas INT,
    unidades INT,
    pedidos_en_proceso INT NOT NULL,
    pedidos_despachados INT NOT NULL,
    M_cambio VARCHAR(250) NOT NULL,
    grafica_dp INT GENERATED ALWAYS AS (pedidos_en_proceso - pedidos_despachados) STORED,
    division_dp DECIMAL(10,2) GENERATED ALWAYS AS (pedidos_despachados / pedidos_en_proceso) STORED,
    fecha_objetivo DATE NOT NULL,
    eta_etd DATE GENERATED ALWAYS AS (fecha_objetivo) STORED,
    comentario_oficina VARCHAR(300),
    comentario_bodega VARCHAR(300),
    vacio_lleno VARCHAR(300),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE picking_r (
    id INT(100) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    aid_oid VARCHAR(255) NOT NULL,
    cliente VARCHAR(255) NOT NULL,
    vehiculo VARCHAR(255) NOT NULL,
    t_vehiculo VARCHAR(255),
    bl VARCHAR(255),
    destino VARCHAR(255),
    paletas INT,
    cajas INT,
    unidades INT,
    pedidos_en_proceso INT NOT NULL,
    pedidos_despachados INT NOT NULL,
    M_cambio VARCHAR(250) NOT NULL,
    grafica_dp INT GENERATED ALWAYS AS (pedidos_en_proceso - pedidos_despachados) STORED,
    division_dp DECIMAL(10,2) GENERATED ALWAYS AS (pedidos_despachados / pedidos_en_proceso) STORED,
    fecha_objetivo DATE NOT NULL,
    eta_etd DATE GENERATED ALWAYS AS (fecha_objetivo) STORED,
    comentario_oficina VARCHAR(300),
    comentario_bodega VARCHAR(300),
    vacio_lleno VARCHAR(300),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE datos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(255) NOT NULL,
  valor INT NOT NULL
);

CREATE TABLE `db_dashboards`.`imports` 
(`id` INT NOT NULL AUTO_INCREMENT ,
`fecha_descarga` DATE NOT NULL ,
`bl` VARCHAR(30) NOT NULL ,
`aid` INT(10) NOT NULL ,
`origen` VARCHAR(20) NOT NULL ,
`referencia` VARCHAR(20) NOT NULL ,
`puerto_llegada` VARCHAR(20) NOT NULL ,
`contenedor` VARCHAR(20) NOT NULL ,
`tamaño` VARCHAR(10) NOT NULL ,
`dmc` VARCHAR(15) NOT NULL ,
`total_paletas` INT(255) NOT NULL ,
`paletas_1_sku` INT(255) NOT NULL ,
`paletas_mix_sku` INT(255) NOT NULL ,
`paletas_generadas` INT(255) NOT NULL ,
`cajas` INT(255) NOT NULL ,
`piezas` INT(255) NOT NULL ,
`peso` DECIMAL NOT NULL ,
`cbm` DECIMAL NOT NULL ,
`acarreo` INT(255) NOT NULL ,
`gastos_portuarios` DECIMAL NOT NULL ,
`otros_flete` DECIMAL NOT NULL ,
`fob` DECIMAL NOT NULL ,
`asn_ok` BOOLEAN NOT NULL ,
`excedente` BOOLEAN NOT NULL ,
`faltante` BOOLEAN NOT NULL ,
`abolladura` BOOLEAN NOT NULL ,
`producto_afectado` BOOLEAN NOT NULL ,
PRIMARY KEY (`id`)) ENGINE = InnoDB;



CREATE TABLE `db_dashboards`.`imports` 
(`id` INT NOT NULL AUTO_INCREMENT ,
`fecha_descarga` DATE NOT NULL ,
`bl` VARCHAR(30) NOT NULL ,
`aid` INT(10) NOT NULL ,
`origen` VARCHAR(20) NOT NULL ,
`referencia` VARCHAR(20) NOT NULL ,
`puerto_llegada` VARCHAR(20) NOT NULL ,
`contenedor` VARCHAR(20) NOT NULL ,
`tamaño` VARCHAR(10) NOT NULL ,
`dmc` VARCHAR(15) NOT NULL ,
`total_paletas` INT(255) NOT NULL ,
`paletas_1_sku` INT(255) NOT NULL ,
`paletas_mix_sku` INT(255) NOT NULL ,
`paletas_generadas` INT(255) NOT NULL ,
`cajas` INT(255) NOT NULL ,
`piezas` INT(255) NOT NULL ,
`peso` DECIMAL NOT NULL ,
`cbm` DECIMAL NOT NULL ,
`acarreo` INT(255) NOT NULL ,
`gastos_portuarios` DECIMAL NOT NULL ,
`otros_flete` DECIMAL NOT NULL ,
`fob` DECIMAL NOT NULL ,
`asn_ok` BOOLEAN NOT NULL ,
`excedente` BOOLEAN NOT NULL ,
`faltante` BOOLEAN NOT NULL ,
`abolladura` BOOLEAN NOT NULL ,
`producto_afectado` BOOLEAN NOT NULL ,
PRIMARY KEY (`id`)) ENGINE = InnoDB;


CREATE TABLE `db_dashboards`.`Exports` 
(`id` INT NOT NULL AUTO_INCREMENT ,
`#` INT(10) NOT NULL ,
`oid` INT(10) NOT NULL ,
`tipo_orden` VARCHAR(20) NOT NULL ,	
`referencia` VARCHAR(20) NOT NULL ,	
`cliente` VARCHAR(20) NOT NULL ,
`consignatario` VARCHAR(20) NOT NULL ,
`pais` VARCHAR(5) NOT NULL ,
`via` VARCHAR(20) NOT NULL ,
`fecha_orden` DATE NOT NULL ,
`fecha_requerida` DATE NOT NULL ,	
`fecha_confirmada` DATE NOT NULL ,
`fecha_liberado` DATE NOT NULL ,	
`fecha_picking` DATE NOT NULL ,
`fecha_packing` DATE NOT NULL ,
`fecha_cargado` DATE NOT NULL ,	
`unidades` INT(255) NOT NULL ,
`unidades_pick` INT(255) NOT NULL ,
`progress` INT(255) NOT NULL ,
`amount` DECIMAL NOT NULL ,
`cbm` DECIMAL NOT NULL ,
`peso` DECIMAL NOT NULL ,
`cajas` INT(255) NOT NULL ,	
`paletas` INT(255) NOT NULL ,
PRIMARY KEY (`id`)) ENGINE = InnoDB;