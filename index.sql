create database hola_mundo;
CREATE DATABASE IF NOT EXISTS hola_mundo;
USE hola_mundo;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nomre VARCHAR(100) NOT NULL,
    correo VARCHAR(100) NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

alter table usuarios change nomre nombre varchar(100) not null;

ALTER TABLE usuarios ADD UNIQUE (correo);