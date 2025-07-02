CREATE DATABASE IF NOT EXISTS database_usuarios;
USE database_usuarios;

CREATE TABLE ejecutivo (
    id_eje INT AUTO_INCREMENT PRIMARY KEY,
    nom_eje VARCHAR(100) NOT NULL,
    tel_eje VARCHAR(20) NOT NULL
);

CREATE TABLE cita (
    id_cit INT AUTO_INCREMENT PRIMARY KEY,
    nom_cit VARCHAR(100) NOT NULL,
    id_eje2 INT,
    FOREIGN KEY (id_eje2) REFERENCES ejecutivo(id_eje)
);