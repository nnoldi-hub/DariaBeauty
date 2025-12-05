-- DARIABEAUTY DATABASE UPDATE QUERIES
-- Rulează aceste query-uri în phpMyAdmin în ordinea dată

-- 1. Actualizează enum-ul pentru role să includă 'client'
ALTER TABLE `users` 
MODIFY COLUMN `role` ENUM('client', 'specialist', 'superadmin') DEFAULT 'client';

-- 2. Adaugă coloane pentru locația serviciilor în users (specialiști)
ALTER TABLE `users` 
ADD COLUMN `offers_at_salon` TINYINT(1) DEFAULT 1 AFTER `is_active`,
ADD COLUMN `offers_at_home` TINYINT(1) DEFAULT 0 AFTER `offers_at_salon`,
ADD COLUMN `salon_address` VARCHAR(255) NULL AFTER `offers_at_home`,
ADD COLUMN `salon_lat` DECIMAL(10,8) NULL AFTER `salon_address`,
ADD COLUMN `salon_lng` DECIMAL(11,8) NULL AFTER `salon_lat`;

-- 3. Adaugă coloane pentru locația serviciilor în services
ALTER TABLE `services` 
ADD COLUMN `available_at_salon` TINYINT(1) DEFAULT 1 AFTER `category`,
ADD COLUMN `available_at_home` TINYINT(1) DEFAULT 0 AFTER `available_at_salon`,
ADD COLUMN `home_service_fee` DECIMAL(8,2) DEFAULT 0 AFTER `available_at_home` COMMENT 'Taxa suplimentara pentru serviciu la domiciliu';

-- 4. Înregistrează migrările în tabelul migrations
INSERT INTO `migrations` (`migration`, `batch`) VALUES 
('2025_12_04_154135_add_client_role_to_users_table', 999),
('2025_12_04_175042_add_service_location_options_to_users_and_services', 999);

-- VERIFICARE: Verifică că toate coloanele au fost adăugate
SHOW COLUMNS FROM `users`;
SHOW COLUMNS FROM `services`;
