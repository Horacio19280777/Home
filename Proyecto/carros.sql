CREATE TABLE carros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    marca VARCHAR(50) NOT NULL,
    modelo VARCHAR(50) NOT NULL,
    año INT NOT NULL,
    kilometraje INT,
    precio DECIMAL(10, 2),
    imagen VARCHAR(1000)
);

INSERT INTO carros (marca, modelo, año, kilometraje, precio, imagen) VALUES
('Toyota', 'Corolla', 2018, 35000, 15000.00,"IMG1.jpg"),
('Honda', 'Civic', 2020, 15000, 18000.00,"IMG2.jpg"),
('Ford', 'Mustang', 2016, 45000, 25000.00,"IMG3.jpg"),
('Chevrolet', 'Malibu', 2019, 30000, 20000.00,"IMG4.jpg"),
('Nissan', 'Sentra', 2021, 10000, 17000.00,"IMG5.jpg");