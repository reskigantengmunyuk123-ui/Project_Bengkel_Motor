CREATE DATABASE IF NOT EXISTS motortech_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE motortech_db;

CREATE TABLE IF NOT EXISTS mechanics (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL,
 username VARCHAR(50) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS services (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL,
 description VARCHAR(255) DEFAULT '',
 price DECIMAL(12,2) NOT NULL DEFAULT 0,
 is_active TINYINT(1) NOT NULL DEFAULT 1,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS bookings (
 id INT AUTO_INCREMENT PRIMARY KEY,
 booking_code VARCHAR(40) NOT NULL UNIQUE,
 customer_name VARCHAR(120) NOT NULL,
 whatsapp VARCHAR(25) NOT NULL,
 motorcycle VARCHAR(120) NOT NULL,
 plate_number VARCHAR(20) NOT NULL,
 service_id INT NOT NULL,
 complaint TEXT,
 booking_date DATE NOT NULL,
 booking_time TIME NOT NULL,
 status ENUM('Menunggu','Diproses','Selesai','Dibatalkan') NOT NULL DEFAULT 'Menunggu',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_booking_service FOREIGN KEY(service_id) REFERENCES services(id)
);
INSERT INTO services(name,description,price) VALUES
('Servis Rutin','Pemeriksaan berkala dan penyetelan umum',50000),
('Ganti Oli','Penggantian oli mesin',30000),
('Tune Up','Pemeriksaan performa mesin',75000),
('Ganti Ban','Penggantian ban luar atau dalam',100000),
('Servis Rem','Pemeriksaan kampas dan sistem rem',35000),
('Perbaikan Mesin','Pemeriksaan dan perbaikan kendala mesin',0);

CREATE TABLE IF NOT EXISTS spareparts (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(120) NOT NULL,
 sku VARCHAR(50) DEFAULT '',
 stock INT NOT NULL DEFAULT 0,
 price DECIMAL(12,2) NOT NULL DEFAULT 0,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
