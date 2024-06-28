CREATE DATABASE digital_signage;

USE digital_signage;

-- Tabella utenti
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('Admin', 'AdminRemote', 'Manager') NOT NULL,
    station_id INT DEFAULT NULL,
    FOREIGN KEY (station_id) REFERENCES stations(id)
);

-- Tabella file
CREATE TABLE files (
    id INT AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    filepath VARCHAR(255) NOT NULL,
    upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabella playlist
CREATE TABLE playlists (
    id INT AUTO_INCREMENT PRIMARY KEY,
    file_id INT,
    position INT,
    FOREIGN KEY (file_id) REFERENCES files(id)
);

-- Tabella postazioni
CREATE TABLE stations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    station_name VARCHAR(255) NOT NULL,
    playlist_id INT,
    FOREIGN KEY (playlist_id) REFERENCES playlists(id)
);
