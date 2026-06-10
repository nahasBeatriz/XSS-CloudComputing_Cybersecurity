CREATE DATABASE IF NOT EXISTS blogdb;
USE blogdb;

CREATE TABLE comentarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    autor VARCHAR(100) NOT NULL,
    conteudo TEXT NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO comentarios (autor, conteudo) VALUES
('Maria', 'Adorei o conteudo deste blog!'),
('Joao', 'Muito bom, continuem assim.');
