CREATE DATABASE IF NOT EXISTS myapp;
USE myapp;

CREATE TABLE members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    jina VARCHAR(100) NOT NULL,
    namba_sim VARCHAR(20) NOT NULL UNIQUE,
    kiasi DECIMAL(10,2) NOT NULL,
    tarehe TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending','paid') DEFAULT 'pending'
);

-- Sample data
INSERT INTO members (jina, namba_sim, kiasi, status) VALUES
('Juma Mwinyi', '255712345678', 5000.00, 'paid'),
('Asha Said', '255713987654', 3000.00, 'pending'),
('Hassan Ali', '255714222333', 2500.00, 'paid');