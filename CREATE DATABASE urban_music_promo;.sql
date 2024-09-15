CREATE DATABASE urban_music_promo;

USE urban_music_promo;

CREATE TABLE admins (
id INT PRIMARY KEY AUTO_INCREMENT,
username VARCHAR(50) NOT NULL,
password VARCHAR(255) NOT NULL
);

INSERT INTO admins (username, password) VALUES ('URBANMUSIC24', '2024URBAN');


