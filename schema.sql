CREATE DATABASE IF NOT EXISTS desafio_tecnico;
USE desafio_tecnico;

CREATE TABLE IF NOT EXISTS prestadores (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    telefone VARCHAR(50) UNIQUE,
    foto VARCHAR(255),
    created DATETIME,
    modified DATETIME
);

CREATE TABLE IF NOT EXISTS servicos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    prestador_id INT UNSIGNED NOT NULL,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT,
    valor DECIMAL(10,2),
    created DATETIME,
    modified DATETIME,
    FOREIGN KEY (prestador_id) REFERENCES prestadores(id) ON DELETE CASCADE
);
