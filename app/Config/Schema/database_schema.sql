SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

CREATE TABLE IF NOT EXISTS providers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    photo VARCHAR(255),
    created DATETIME DEFAULT NULL,
    modified DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS services (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    created DATETIME DEFAULT NULL,
    modified DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS provider_services (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    provider_id INT UNSIGNED NOT NULL,
    service_id INT UNSIGNED NOT NULL,
    value DECIMAL(10,2) NOT NULL,
    created DATETIME DEFAULT NULL,
    modified DATETIME DEFAULT NULL,
    FOREIGN KEY (provider_id) REFERENCES providers(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO services (name, description, created, modified) VALUES
('Diagnóstico e Consultoria Inicial', 'Análise inicial do projeto e consultoria especializada', NOW(), NOW()),
('Definição de Arquitetura', 'Planejamento e definição da arquitetura técnica do projeto', NOW(), NOW()),
('Design e Implementação do Frontend', 'Desenvolvimento da interface do usuário e experiência visual', NOW(), NOW()),
('Desenvolvimento Backend', 'Implementação da lógica de negócio e APIs', NOW(), NOW()),
('Manutenção e Suporte', 'Serviços de manutenção contínua e suporte técnico', NOW(), NOW()),
('Treinamento e Capacitação', 'Treinamento de equipes e transferência de conhecimento', NOW(), NOW());
