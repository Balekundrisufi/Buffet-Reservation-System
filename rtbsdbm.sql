CREATE DATABASE IF NOT EXISTS login;
USE login;

CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone_number BIGINT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS admins (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    full_name VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create the booking database
CREATE DATABASE IF NOT EXISTS booking;
USE booking;

-- Table to store booking details
CREATE TABLE IF NOT EXISTS tblbookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    booking_date DATE NOT NULL,
    booking_time TIME NOT NULL,
    num_adults INT NOT NULL,
    num_children INT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    booking_status ENUM('confirmed', 'cancelled') NOT NULL,
	arrival_status ENUM('pending', 'reached', 'cancelled') DEFAULT 'pending'
);

-- Table to manage available slots
CREATE TABLE IF NOT EXISTS tblslots (
    slot_id INT AUTO_INCREMENT PRIMARY KEY,
    slot_date DATE NOT NULL,
    slot_time TIME NOT NULL,
    available_tables INT DEFAULT 20 NOT NULL,
    total_tables INT DEFAULT 20 NOT NULL,
    UNIQUE (slot_date, slot_time)
);


-- Table to manage individual tables


CREATE TABLE IF NOT EXISTS tblpricing (
    pricing_id INT AUTO_INCREMENT PRIMARY KEY,
    price_adult DECIMAL(10, 2) NOT NULL,
    price_child DECIMAL(10, 2) NOT NULL,
    effective_date DATE NOT NULL
);