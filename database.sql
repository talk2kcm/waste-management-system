-- Waste Management System Database Schema
-- Create database
CREATE DATABASE IF NOT EXISTS waste_management;
USE waste_management;

-- ============================================================
-- USERS TABLE - Stores all user information
-- ============================================================
CREATE TABLE IF NOT EXISTS users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    role ENUM('admin', 'staff', 'resident') NOT NULL DEFAULT 'resident',
    password_hash VARCHAR(255) NOT NULL,
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_status (status)
);

-- ============================================================
-- WASTE BINS TABLE - Stores waste bin/location information
-- ============================================================
CREATE TABLE IF NOT EXISTS waste_bins (
    bin_id INT PRIMARY KEY AUTO_INCREMENT,
    bin_code VARCHAR(50) UNIQUE NOT NULL,
    bin_type ENUM('residential', 'commercial', 'industrial', 'organic') NOT NULL,
    capacity_liters INT NOT NULL,
    current_fill_level INT DEFAULT 0,
    location_name VARCHAR(150) NOT NULL,
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    zone_area VARCHAR(100),
    status ENUM('active', 'maintenance', 'inactive') DEFAULT 'active',
    last_emptied DATETIME,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_zone (zone_area)
);

-- ============================================================
-- PICKUP REQUESTS TABLE - Stores pickup request information
-- ============================================================
CREATE TABLE IF NOT EXISTS pickup_requests (
    pickup_id INT PRIMARY KEY AUTO_INCREMENT,
    request_by_user_id INT NOT NULL,
    bin_id INT,
    location_description TEXT,
    pickup_date DATE,
    pickup_time TIME,
    status ENUM('pending', 'assigned', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending',
    assigned_staff_id INT,
    urgency ENUM('normal', 'high', 'emergency') DEFAULT 'normal',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (request_by_user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (bin_id) REFERENCES waste_bins(bin_id) ON DELETE SET NULL,
    FOREIGN KEY (assigned_staff_id) REFERENCES users(user_id) ON DELETE SET NULL,
    INDEX idx_status (status),
    INDEX idx_user_id (request_by_user_id),
    INDEX idx_pickup_date (pickup_date)
);

-- ============================================================
-- COMPLAINTS TABLE - Stores complaint information
-- ============================================================
CREATE TABLE IF NOT EXISTS complaints (
    complaint_id INT PRIMARY KEY AUTO_INCREMENT,
    filed_by_user_id INT NOT NULL,
    category VARCHAR(100),
    subject VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    location_description VARCHAR(255),
    bin_id INT,
    status ENUM('new', 'in_review', 'acknowledged', 'resolved', 'closed') DEFAULT 'new',
    priority ENUM('low', 'medium', 'high', 'critical') DEFAULT 'medium',
    assigned_to_user_id INT,
    resolution_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (filed_by_user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (bin_id) REFERENCES waste_bins(bin_id) ON DELETE SET NULL,
    FOREIGN KEY (assigned_to_user_id) REFERENCES users(user_id) ON DELETE SET NULL,
    INDEX idx_status (status),
    INDEX idx_priority (priority),
    INDEX idx_filed_by (filed_by_user_id)
);

-- ============================================================
-- STAFF ASSIGNMENTS TABLE - Tracks staff route/task assignments
-- ============================================================
CREATE TABLE IF NOT EXISTS staff_assignments (
    assignment_id INT PRIMARY KEY AUTO_INCREMENT,
    staff_id INT NOT NULL,
    route_name VARCHAR(150),
    zone_area VARCHAR(100),
    bins_assigned TEXT, -- comma-separated bin IDs
    assigned_date DATE,
    status ENUM('active', 'completed', 'paused') DEFAULT 'active',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (staff_id) REFERENCES users(user_id) ON DELETE CASCADE,
    INDEX idx_staff_id (staff_id),
    INDEX idx_status (status)
);

-- ============================================================
-- PAYMENTS TABLE - Stores payment/billing information
-- ============================================================
CREATE TABLE IF NOT EXISTS payments (
    payment_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    payment_type ENUM('monthly_fee', 'service_charge', 'fine', 'other') DEFAULT 'monthly_fee',
    payment_status ENUM('paid', 'unpaid', 'pending', 'cancelled') DEFAULT 'unpaid',
    payment_method ENUM('cash', 'check', 'bank_transfer', 'online') DEFAULT 'online',
    payment_date DATETIME,
    due_date DATE,
    invoice_number VARCHAR(50) UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_status (payment_status),
    INDEX idx_due_date (due_date)
);

-- ============================================================
-- AUDIT LOG TABLE - Tracks system actions for security
-- ============================================================
CREATE TABLE IF NOT EXISTS audit_logs (
    log_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    action VARCHAR(150) NOT NULL,
    table_name VARCHAR(100),
    record_id INT,
    old_values TEXT,
    new_values TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_created_at (created_at)
);

-- ============================================================
-- SAMPLE DATA - Admin and Test Users
-- ============================================================
--
-- PASSWORD RESET INSTRUCTIONS:
-- If demo accounts don't login, use the debug-login.php tool:
-- 1. Open: http://localhost/waste-management-system/debug-login.php
-- 2. Follow troubleshooting steps shown
-- 3. Run suggested SQL command in phpMyAdmin if needed
-- 4. Delete debug-login.php after testing for security
--
-- Demo Credentials:
-- Admin:    admin@waste.local / Admin@123
-- Staff:    john@waste.local / Admin@123
-- Resident: jane@waste.local / Admin@123
--
-- Password Hash: $2y$10$H0i4P6ZdmPvl2bS1d8nEPupZaOLyV.0Ie.kSHtLy7LPxFwAxqr9Tm
-- This is the bcrypt hash for "Admin@123"
--

INSERT INTO users (first_name, last_name, email, phone, address, role, password_hash, status) VALUES
('Admin', 'User', 'admin@waste.local', '1234567890', '123 Admin Street', 'admin', '$2y$10$H0i4P6ZdmPvl2bS1d8nEPupZaOLyV.0Ie.kSHtLy7LPxFwAxqr9Tm', 'active'),
('John', 'Staff', 'john@waste.local', '0987654321', '456 Staff Avenue', 'staff', '$2y$10$H0i4P6ZdmPvl2bS1d8nEPupZaOLyV.0Ie.kSHtLy7LPxFwAxqr9Tm', 'active'),
('Jane', 'Resident', 'jane@waste.local', '5555555555', '789 Resident Road', 'resident', '$2y$10$H0i4P6ZdmPvl2bS1d8nEPupZaOLyV.0Ie.kSHtLy7LPxFwAxqr9Tm', 'active');

-- ============================================================
-- SAMPLE DATA - Waste Bins
-- ============================================================
INSERT INTO waste_bins (bin_code, bin_type, capacity_liters, current_fill_level, location_name, zone_area, status) VALUES
('BIN001', 'residential', 1000, 650, 'Main Street', 'Zone A', 'active'),
('BIN002', 'commercial', 2000, 1500, 'Shopping Center', 'Zone B', 'active'),
('BIN003', 'industrial', 5000, 3200, 'Industrial Park', 'Zone C', 'active'),
('BIN004', 'organic', 500, 250, 'Market Square', 'Zone A', 'active');

-- ============================================================
-- SAMPLE DATA - Pickup Requests
-- ============================================================
INSERT INTO pickup_requests (request_by_user_id, bin_id, pickup_date, pickup_time, status, urgency) VALUES
(3, 1, CURDATE(), '09:00:00', 'pending', 'normal'),
(3, 2, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '10:30:00', 'pending', 'high');

-- ============================================================
-- SAMPLE DATA - Complaints
-- ============================================================
INSERT INTO complaints (filed_by_user_id, category, subject, description, bin_id, status, priority) VALUES
(3, 'Overflowing Bin', 'Bin is overflowing at Main Street', 'The waste bin at Main Street has not been emptied and is overflowing', 1, 'new', 'high'),
(3, 'Bad Smell', 'Foul odor from waste bin', 'There is a bad smell coming from the waste management area', 2, 'new', 'medium');

-- ============================================================
-- SAMPLE DATA - Payments
-- ============================================================
INSERT INTO payments (user_id, amount, payment_type, payment_status, due_date) VALUES
(3, 150.00, 'monthly_fee', 'unpaid', DATE_ADD(CURDATE(), INTERVAL 7 DAY)),
(3, 75.00, 'service_charge', 'paid', CURDATE());

-- Create indexes for better query performance
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_bins_status ON waste_bins(status);
CREATE INDEX idx_pickups_status ON pickup_requests(status);
CREATE INDEX idx_complaints_status ON complaints(status);
CREATE INDEX idx_payments_status ON payments(payment_status);
