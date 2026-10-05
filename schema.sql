-- ============================================================
-- schema.sql — CRUD Inventaris (Tugas Rutin 8)
-- Import: mysql -u root < schema.sql   (atau lewat phpMyAdmin)
-- ============================================================
DROP DATABASE IF EXISTS inventaris_db;
CREATE DATABASE inventaris_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE inventaris_db;

-- Normalisasi 3NF: kategori & supplier dipisah dari produk
CREATE TABLE categories (
    id   INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE suppliers (
    id    INT AUTO_INCREMENT PRIMARY KEY,
    name  VARCHAR(100) NOT NULL,
    phone VARCHAR(20)
) ENGINE=InnoDB;

CREATE TABLE products (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(150)  NOT NULL,
    category_id INT           NOT NULL,
    supplier_id INT           NOT NULL,
    price       DECIMAL(12,2) NOT NULL,
    stock       INT           NOT NULL DEFAULT 0,
    created_at  TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_products_category FOREIGN KEY (category_id)
        REFERENCES categories(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_products_supplier FOREIGN KEY (supplier_id)
        REFERENCES suppliers(id)  ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- BONUS: log aktivitas (dipakai oleh transaction pada delete.php).
-- Sengaja tanpa FK ke products supaya log tetap ada setelah produk dihapus.
CREATE TABLE activity_logs (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    action       VARCHAR(20)  NOT NULL,
    product_id   INT          NOT NULL,
    product_name VARCHAR(150) NOT NULL,
    created_at   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ===================== SEED DATA (min. 5 per tabel) =====================
INSERT INTO categories (name) VALUES
('Aksesoris'), ('Display'), ('Komputer'), ('Jaringan'), ('Penyimpanan');

INSERT INTO suppliers (name, phone) VALUES
('PT Medan Teknologi', '061-4511234'),
('CV Sumut Komputer',  '081260001111'),
('Toko Digital Jaya',  '082170002222'),
('PT Nusantara Net',   '081370003333'),
('UD Berkah Elektronik','085270004444');

INSERT INTO products (name, category_id, supplier_id, price, stock) VALUES
('Laptop ASUS Vivobook 14', 3, 1, 8500000, 10),
('Mouse Wireless Logitech', 1, 2,  150000, 25),
('Keyboard Mechanical',     1, 2,  450000, 15),
('Monitor LED 24 inci',     2, 3, 1750000,  8),
('Router TP-Link AX1800',   4, 4,  650000, 12),
('SSD NVMe 512GB',          5, 1,  780000, 20),
('Flashdisk 64GB',          5, 5,   85000, 50),
('Kabel LAN Cat6 10m',      4, 5,   55000, 40);
