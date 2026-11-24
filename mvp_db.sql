CREATE DATABASE IF NOT EXISTS mvp_db;
USE mvp_db;

DROP TABLE IF EXISTS kandidat;
CREATE TABLE kandidat (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  posisi VARCHAR(100) NOT NULL,
  domisili VARCHAR(100) NOT NULL,
  skorTes INT NOT NULL DEFAULT 0,
  pengalamanTahun INT NOT NULL DEFAULT 0
);

DROP TABLE IF EXISTS lowongan;
CREATE TABLE lowongan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  judul VARCHAR(100) NOT NULL,
  departemen VARCHAR(100) NOT NULL,
  lokasi VARCHAR(100) NOT NULL,
  kuota INT NOT NULL DEFAULT 1,
  status ENUM('Buka','Tutup') NOT NULL DEFAULT 'Buka'
);

INSERT INTO kandidat (nama, posisi, domisili, skorTes, pengalamanTahun) VALUES
('Raka Pratama','Backend Developer','Bandung',92,3),
('Nadia Kusuma','Frontend Developer','Surabaya',90,2),
('Sinta Maharani','UI/UX Designer','Jakarta',88,2),
('Dimas Nugroho','Data Analyst','Yogyakarta',85,1),
('Bagas Setiawan','QA Engineer','Bandung',81,1);

INSERT INTO lowongan (judul, departemen, lokasi, kuota, status) VALUES
('Backend Developer','Engineering','Jakarta',2,'Buka'),
('UI/UX Designer','Product','Bandung',1,'Buka'),
('Data Analyst','Business Intelligence','Yogyakarta',1,'Tutup');
