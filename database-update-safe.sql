-- ========================================
-- DARIABEAUTY DATABASE UPDATE - SAFE MODE
-- ========================================
-- INSTRUCȚIUNI: Rulează fiecare query INDIVIDUAL în phpMyAdmin
-- Dacă un query dă eroare "Duplicate column", e OK - înseamnă că există deja
-- ========================================

-- PASUL 1: Verifică ce coloane există deja
-- Rulează întâi asta pentru a vedea structura actuală:
SHOW COLUMNS FROM `users`;

-- ========================================
-- PASUL 2: Actualizează enum-ul pentru role (dacă nu include 'client')
-- ========================================
ALTER TABLE `users` 
MODIFY COLUMN `role` ENUM('client', 'specialist', 'superadmin') DEFAULT 'client';

-- ========================================
-- PASUL 3: Adaugă coloane în users 
-- IMPORTANT: Rulează doar coloanele care LIPSESC din rezultatul de la PASUL 1
-- ========================================

-- Dacă oferă eroare "Duplicate", sari peste ea - coloana există deja
ALTER TABLE `users` ADD COLUMN `offers_at_salon` TINYINT(1) DEFAULT 1 AFTER `is_active`;

ALTER TABLE `users` ADD COLUMN `offers_at_home` TINYINT(1) DEFAULT 0 AFTER `offers_at_salon`;

ALTER TABLE `users` ADD COLUMN `salon_address` VARCHAR(255) NULL AFTER `offers_at_home`;

ALTER TABLE `users` ADD COLUMN `salon_lat` DECIMAL(10,8) NULL AFTER `salon_address`;

ALTER TABLE `users` ADD COLUMN `salon_lng` DECIMAL(11,8) NULL AFTER `salon_lat`;

-- ========================================
-- PASUL 4: Verifică ce coloane există în services
-- ========================================
SHOW COLUMNS FROM `services`;

-- ========================================
-- PASUL 5: Adaugă coloane în services
-- Rulează doar coloanele care LIPSESC
-- ========================================

ALTER TABLE `services` ADD COLUMN `available_at_salon` TINYINT(1) DEFAULT 1 AFTER `category`;

ALTER TABLE `services` ADD COLUMN `available_at_home` TINYINT(1) DEFAULT 0 AFTER `available_at_salon`;

ALTER TABLE `services` ADD COLUMN `home_service_fee` DECIMAL(8,2) DEFAULT 0 AFTER `available_at_home` COMMENT 'Taxa suplimentara pentru serviciu la domiciliu';

-- ========================================
-- PASUL 6: Înregistrează migrările (INSERT IGNORE nu dă eroare dacă există deja)
-- ========================================
INSERT IGNORE INTO `migrations` (`migration`, `batch`) VALUES 
('2025_12_04_154135_add_client_role_to_users_table', 999);

INSERT IGNORE INTO `migrations` (`migration`, `batch`) VALUES 
('2025_12_04_175042_add_service_location_options_to_users_and_services', 999);

-- ========================================
-- PASUL 7: VERIFICARE FINALĂ
-- ========================================
-- Verifică că toate coloanele au fost adăugate:
SELECT 
    'users' as tabel,
    COUNT(*) as total_coloane_noi
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'users'
AND COLUMN_NAME IN ('offers_at_salon', 'offers_at_home', 'salon_address', 'salon_lat', 'salon_lng');
-- Ar trebui să returneze 5

SELECT 
    'services' as tabel,
    COUNT(*) as total_coloane_noi
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'services'
AND COLUMN_NAME IN ('available_at_salon', 'available_at_home', 'home_service_fee');
-- Ar trebui să returneze 3
