-- Database Schema for R&D Dashboard

CREATE DATABASE IF NOT EXISTS rd_dashboard;
USE rd_dashboard;

-- Users Table (for future authentication)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Research Links Table
CREATE TABLE IF NOT EXISTS research_links (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    url VARCHAR(2048) NOT NULL,
    category ENUM('Article', 'Platform', 'Study') NOT NULL,
    source VARCHAR(100),
    publication_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Patents Table
CREATE TABLE IF NOT EXISTS patents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    patent_number VARCHAR(50) UNIQUE,
    assignee VARCHAR(100),
    status VARCHAR(50),
    filing_date DATE,
    summary TEXT,
    link VARCHAR(2048),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Documents Table (FISPQ / FDS)
CREATE TABLE IF NOT EXISTS documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type ENUM('FISPQ', 'FDS', 'Manual') NOT NULL,
    description TEXT,
    file_path VARCHAR(2048), -- Path to local file or external URL
    supplier VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- AI Predictions Log (Mock data storage)
CREATE TABLE IF NOT EXISTS ai_predictions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(100),
    prediction_type VARCHAR(50),
    confidence_score DECIMAL(5,2),
    prediction_result TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products Table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type ENUM('Biológico', 'Agrotóxico', 'Fertilizante', 'Outro') NOT NULL,
    active_ingredient VARCHAR(255),
    manufacturer VARCHAR(100),
    registration_number VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Articles Table (Full articles with content)
CREATE TABLE IF NOT EXISTS articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    summary TEXT,
    abstract TEXT,
    full_text LONGTEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Initial Seed Data
INSERT INTO research_links (title, url, category, source, publication_date) VALUES
('Google Acadêmico', 'https://scholar.google.com.br/', 'Platform', 'Google', NULL),
('SciELO', 'https://scielo.org/', 'Platform', 'SciELO', NULL),
('Redape', 'https://www.redape.org/', 'Platform', 'Redape', NULL),
('Inovações em Biológicos 2024', '#', 'Article', 'Embrapa', '2024-01-15');

INSERT INTO documents (name, type, description, supplier) VALUES
('Glifosato - FISPQ', 'FISPQ', 'Ficha de segurança para Glifosato', 'Monsanto'),
('Manual de Manuseio de Sementes', 'FDS', 'Diretrizes para armazenamento seguro', 'Syngenta');

INSERT INTO products (name, type, active_ingredient, manufacturer, registration_number) VALUES
('Boveril', 'Biológico', 'Beauveria bassiana', 'Koppert', '1234/20'),
('Roundup', 'Agrotóxico', 'Glifosato', 'Bayer', '5678/15'),
('Ubyfol', 'Fertilizante', 'NPK + Micronutrientes', 'Ubyfol', '9012/18');
