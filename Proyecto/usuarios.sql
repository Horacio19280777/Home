CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(50) NOT NULL,
    apellidos VARCHAR(50) NOT NULL,
    login VARCHAR(50) NOT NULL UNIQUE,
    pwd VARCHAR(255) NOT NULL,
    rol VARCHAR(20) NOT NULL
);
INSERT INTO usuarios (nombres, apellidos, login, pwd, rol) VALUES
('Horacio', 'Sanchez', 'horacio', '123', 'admin'),
('Maria', 'Gonzalez', 'maria', '123', 'revisor'),
('Carlos', 'Sanchez', 'carlos', '123', 'alumno'),
('Ana', 'Martinez', 'ana', '123', 'alumno'),
('Luis', 'Rodriguez', 'luis', '123', 'alumno');