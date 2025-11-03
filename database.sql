-- JobFinder DB (ready for import)
CREATE DATABASE IF NOT EXISTS jobfinder_db DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE jobfinder_db;

CREATE TABLE admin (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL
);

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE jobs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  company VARCHAR(150) NOT NULL,
  location VARCHAR(120) NOT NULL,
  category VARCHAR(100) NOT NULL,
  type VARCHAR(50) DEFAULT 'Full-time',
  salary VARCHAR(80) DEFAULT '',
  description TEXT,
  posted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE applications (
  id INT AUTO_INCREMENT PRIMARY KEY,
  job_id INT NOT NULL,
  user_id INT NOT NULL,
  cv_file VARCHAR(255) NOT NULL,
  applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Default admin (username: admin, password: admin123)
INSERT INTO admin (username, password) VALUES ('admin', '$2y$10$e0NRJYc1Z1p8eWm3Gx3Y7eBz0h3m2u1KcO7ZkQ9vQmY8LPqFZ5K6a');

-- Sample jobs
INSERT INTO jobs (title,company,location,category,type,salary,description) VALUES
('Frontend Developer','Bintang Digital','Jakarta','IT','Full-time','IDR 10.000.000 - 18.000.000','Membangun antarmuka menggunakan React/Tailwind.'),
('Akuntan Junior','Solusi Keuangan','Bandung','Akuntansi','Full-time','IDR 5.000.000 - 8.000.000','Menangani pembukuan dan pelaporan pajak.'),
('Marketing Officer','Pemasaran Nusantara','Bali','Marketing','Contract','IDR 4.000.000 - 7.000.000','Menyusun strategi pemasaran digital.'),
('UI/UX Designer','Studio Desain','Yogyakarta','Desain','Part-time','IDR 6.000.000 - 10.000.000','Mendesain antarmuka aplikasi mobile.'),
('Admin Office','Perusahaan ABC','Surabaya','Administrasi','Full-time','IDR 3.500.000 - 5.000.000','Mengelola administrasi kantor dan dokumen.');
