-- If haven't already, drop the schema and create the schema again
-- DROP SCHEMA `konohadb`;
-- CREATE SCHEMA `konohadb`;
-- USE `konohadb`;

-- Create user_profile table
CREATE TABLE user_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    activeStatus BOOLEAN NOT NULL,
    description VARCHAR(100)
);

-- Populate user_profile with profile descriptions
INSERT INTO user_profiles (name, activeStatus, Description)
VALUES
('Buyer', true, 'I am a buyer'), ('Seller', true, 'I am a seller'), ('Agent', true, 'I am a agent'), ('Admin', true, 'I am a admin');

-- Create user_accounts table
CREATE TABLE user_accounts (
    username VARCHAR(50) PRIMARY KEY,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    activeStatus BOOLEAN NOT NULL,
    profile_id INT,
    FOREIGN KEY (profile_id) REFERENCES user_profiles(id)
);

-- Populate user accounts for Buyer profile with password 'buyer_password'
INSERT INTO user_accounts (username, email, password, activeStatus, profile_id) 
SELECT CONCAT('buyer_', t.n), CONCAT('buyer_', t.n, '@example.com'), CONCAT('buyer_pass', t.n), TRUE, '1'
FROM (
    SELECT ROW_NUMBER() OVER() AS n
    FROM INFORMATION_SCHEMA.TABLES
    LIMIT 25
) t;

-- Populate user accounts for Seller profile with password 'seller_password'
INSERT INTO user_accounts (username, email, password, activeStatus, profile_id) 
SELECT CONCAT('seller_', t.n), CONCAT('seller_', t.n, '@example.com'), CONCAT('seller_pass', t.n), TRUE, '2'
FROM (
    SELECT ROW_NUMBER() OVER() AS n
    FROM INFORMATION_SCHEMA.TABLES
    LIMIT 25
) t;

-- Populate user accounts for Agent profile with password 'agent_password'
INSERT INTO user_accounts (username, email, password, activeStatus, profile_id) 
SELECT CONCAT('agent_', t.n), CONCAT('agent_', t.n, '@example.com'), CONCAT('agent_pass', t.n), TRUE, '3'
FROM (
    SELECT ROW_NUMBER() OVER() AS n
    FROM INFORMATION_SCHEMA.TABLES
    LIMIT 25
) t;

-- Populate user accounts for Admin profile with password 'admin_password'
INSERT INTO user_accounts (username, email, password, activeStatus, profile_id) 
SELECT CONCAT('admin_', t.n), CONCAT('admin_', t.n, '@example.com'), CONCAT('admin_pass', t.n), TRUE, '4'
FROM (
    SELECT ROW_NUMBER() OVER() AS n
    FROM INFORMATION_SCHEMA.TABLES
    LIMIT 25
) t;

-- Create ratings table
CREATE TABLE ratings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rating INT NOT NULL,
    customer_id VARCHAR(50) NOT NULL,
    agent_id VARCHAR(50) NOT NULL,
    FOREIGN KEY (customer_id) REFERENCES user_accounts(username),
    FOREIGN KEY (agent_id) REFERENCES user_accounts(username)
);

-- Populate ratings table with 100 rows 
INSERT INTO ratings (rating, customer_id, agent_id)
SELECT
    ROUND(1 + (RAND() * 4)), -- Generates random ratings from 1 to 5
    CASE WHEN n <= 5 THEN CONCAT('buyer_', CEIL(RAND() * 10)) ELSE CONCAT('seller_', CEIL(RAND() * 10)) END, -- Randomly selects a buyer or seller
    CONCAT('agent_', CEIL(n / 10.0)) -- Assigns an agent ID based on n, ensures each agent gets 10 entries
FROM (
    SELECT ROW_NUMBER() OVER() AS n
    FROM information_schema.tables -- Generates rows; ensure this query provides at least 100 rows
    LIMIT 100
) t;


-- Create reviews table
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    review TEXT NOT NULL,
    customer_id VARCHAR(50) NOT NULL,
    agent_id VARCHAR(50) NOT NULL,
    FOREIGN KEY (customer_id) REFERENCES user_accounts(username),
    FOREIGN KEY (agent_id) REFERENCES user_accounts(username)
);

-- Populate reviews table with 10o rows 
INSERT INTO reviews (review, customer_id, agent_id)
SELECT
    -- Randomly selects a review from the given set
    CASE FLOOR(RAND() * 10)
        WHEN 0 THEN 'Great experience, highly recommended!'
        WHEN 1 THEN 'Good service overall, could improve communication.'
        WHEN 2 THEN 'Excellent professionalism and timely delivery.'
        WHEN 3 THEN 'Average service, needs improvement in quality.'
        WHEN 4 THEN 'Satisfactory performance, would use again.'
        WHEN 5 THEN 'Terrible experience, very poor communication.'
        WHEN 6 THEN 'Impressed with the quality of work.'
        WHEN 7 THEN 'Fair service, met expectations.'
        WHEN 8 THEN 'Outstanding service, exceeded expectations!'
        WHEN 9 THEN 'Good job, but room for improvement.'
    END AS review,
    
    -- Randomly selects a buyer or seller
    CASE
        WHEN n % 2 = 0 THEN CONCAT('buyer_', FLOOR(1 + (RAND() * 10)))
        ELSE CONCAT('seller_', FLOOR(1 + (RAND() * 10)))
    END AS customer_id,
    
    -- Assigns an agent ID based on n, ensures each agent gets 10 entries
    CONCAT('agent_', FLOOR((n - 1) / 10) + 1) AS agent_id
    
FROM (
    SELECT ROW_NUMBER() OVER() AS n
    FROM information_schema.tables
    LIMIT 100 -- Ensures only 100 rows are generated
) t;


-- Create property table
CREATE TABLE property (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    type VARCHAR(50) NOT NULL,
    size INT NOT NULL,
    rooms INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    location VARCHAR(100) NOT NULL,
    status ENUM('sold', 'available') NOT NULL,
    image VARCHAR(255) NOT NULL,
    views INT NOT NULL,
    seller_id VARCHAR(50),
    agent_id VARCHAR(50),
    FOREIGN KEY (seller_id) REFERENCES user_accounts(username),
    FOREIGN KEY (agent_id) REFERENCES user_accounts(username)
);

-- Populate property with properties managed by agent_1
INSERT INTO property (name, type, size, rooms, price, location, status, image, views, seller_id, agent_id) 
VALUES
    ('Property 1', 'GCB', 150, 2, 12000000.00, 'Location C', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_2', 'agent_1'),
    ('Property 2', 'HDB', 100, 3, 150000.00, 'Location A', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_1', 'agent_1'),
    ('Property 3', 'Condo', 150, 2, 180000.00, 'Location C', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_3', 'agent_1'),
    ('Property 4', 'HDB', 100, 3, 170000.00, 'Location A', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_5', 'agent_1'),
    ('Property 5', 'Condo', 150, 2, 180000.00, 'Location C', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_3', 'agent_1'),
    ('Property 6', 'GCB', 150, 2, 12000000.00, 'Location F', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_4', 'agent_1'),
    ('Property 7', 'HDB', 100, 3, 150000.00, 'Location G', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_9', 'agent_1'),
    ('Property 8', 'Condo', 150, 2, 180000.00, 'Location E', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_8', 'agent_1'),
    ('Property 9', 'HDB', 100, 3, 170000.00, 'Location C', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_8', 'agent_1'),
    ('Property 10', 'Condo', 150, 2, 180000.00, 'Location A', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_4', 'agent_1');

-- Populate property with properties managed by agent_2
INSERT INTO property (name, type, size, rooms, price, location, status, image, views, seller_id, agent_id) 
VALUES
    ('Property 11', 'Condo', 120, 4, 1000000.00, 'Location B', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_7', 'agent_2'),
    ('Property 12', 'HDB', 110, 3, 500000.00, 'Location C', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_3', 'agent_2'),
    ('Property 13', 'GCB', 200, 5, 15000000.00, 'Location H', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_10', 'agent_2'),
    ('Property 14', 'Condo', 130, 4, 1500000.00, 'Location D', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_6', 'agent_2'),
    ('Property 15', 'HDB', 120, 3, 600000.00, 'Location E', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_2', 'agent_2'),
    ('Property 16', 'Condo', 140, 4, 1800000.00, 'Location F', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_8', 'agent_2'),
    ('Property 17', 'GCB', 180, 4, 12000000.00, 'Location G', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_5', 'agent_2'),
    ('Property 18', 'HDB', 110, 3, 550000.00, 'Location A', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_9', 'agent_2'),
    ('Property 19', 'Condo', 130, 4, 1600000.00, 'Location B', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_4', 'agent_2'),
    ('Property 20', 'HDB', 120, 3, 620000.00, 'Location C', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_1', 'agent_2');

-- Populate property with properties managed by agent_3
INSERT INTO property (name, type, size, rooms, price, location, status, image, views, seller_id, agent_id) 
VALUES
    ('Property 21', 'GCB', 150, 2, 12000000.00, 'Location C', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_2', 'agent_3'),
    ('Property 22', 'HDB', 100, 3, 150000.00, 'Location A', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_1', 'agent_3'),
    ('Property 23', 'Condo', 150, 2, 180000.00, 'Location C', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_3', 'agent_3'),
    ('Property 24', 'HDB', 100, 3, 170000.00, 'Location A', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_5', 'agent_3'),
    ('Property 25', 'Condo', 150, 2, 180000.00, 'Location C', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_3', 'agent_3'),
    ('Property 26', 'GCB', 150, 2, 12000000.00, 'Location F', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_4', 'agent_3'),
    ('Property 27', 'HDB', 100, 3, 150000.00, 'Location G', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_9', 'agent_3'),
    ('Property 28', 'Condo', 150, 2, 180000.00, 'Location E', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_8', 'agent_3'),
    ('Property 29', 'HDB', 100, 3, 170000.00, 'Location C', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_8', 'agent_3'),
    ('Property 30', 'Condo', 150, 2, 180000.00, 'Location A', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_4', 'agent_3');

-- Populate property with properties managed by agent_4
INSERT INTO property (name, type, size, rooms, price, location, status, image, views, seller_id, agent_id) 
VALUES
    ('Property 31', 'GCB', 150, 3, 5000000.00, 'Location B', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_5', 'agent_4'),
    ('Property 32', 'HDB', 120, 4, 800000.00, 'Location D', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_6', 'agent_4'),
    ('Property 33', 'Condo', 150, 5, 1200000.00, 'Location F', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_7', 'agent_4'),
    ('Property 34', 'GCB', 180, 2, 10000000.00, 'Location A', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_8', 'agent_4'),
    ('Property 35', 'HDB', 100, 3, 600000.00, 'Location E', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_9', 'agent_4'),
    ('Property 36', 'Condo', 130, 4, 1500000.00, 'Location G', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_10', 'agent_4'),
    ('Property 37', 'GCB', 200, 5, 15000000.00, 'Location H', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_1', 'agent_4'),
    ('Property 38', 'HDB', 110, 2, 700000.00, 'Location B', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_2', 'agent_4'),
    ('Property 39', 'Condo', 140, 3, 1700000.00, 'Location D', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_3', 'agent_4'),
    ('Property 40', 'GCB', 220, 4, 18000000.00, 'Location A', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_4', 'agent_4');

-- Populate property with properties managed by agent_5
INSERT INTO property (name, type, size, rooms, price, location, status, image, views, seller_id, agent_id) 
VALUES
    ('Property 41', 'Condo', 150, 3, 800000.00, 'Location H', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_6', 'agent_5'),
    ('Property 42', 'GCB', 150, 4, 12000000.00, 'Location A', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_3', 'agent_5'),
    ('Property 43', 'HDB', 100, 5, 170000.00, 'Location E', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_2', 'agent_5'),
    ('Property 44', 'Condo', 150, 3, 180000.00, 'Location F', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_7', 'agent_5'),
    ('Property 45', 'HDB', 100, 4, 150000.00, 'Location G', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_5', 'agent_5'),
    ('Property 46', 'Condo', 150, 5, 180000.00, 'Location B', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_1', 'agent_5'),
    ('Property 47', 'GCB', 150, 4, 20000000.00, 'Location C', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_8', 'agent_5'),
    ('Property 48', 'Condo', 150, 3, 900000.00, 'Location D', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_4', 'agent_5'),
    ('Property 49', 'HDB', 100, 4, 160000.00, 'Location A', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_9', 'agent_5'),
    ('Property 50', 'GCB', 150, 5, 15000000.00, 'Location H', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_10', 'agent_5');

-- Populate property with properties managed by agent_6
INSERT INTO property (name, type, size, rooms, price, location, status, image, views, seller_id, agent_id) 
VALUES
    ('Property 51', 'GCB', 150, 5, 16000000.00, 'Location H', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_3', 'agent_6'),
    ('Property 52', 'Condo', 200, 3, 1000000.00, 'Location B', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_10', 'agent_6'),
    ('Property 53', 'Condo', 180, 4, 7000000.00, 'Location G', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_7', 'agent_6'),
    ('Property 54', 'HDB', 120, 2, 500000.00, 'Location F', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_6', 'agent_6'),
    ('Property 55', 'Condo', 150, 3, 800000.00, 'Location D', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_4', 'agent_6'),
    ('Property 56', 'HDB', 100, 5, 400000.00, 'Location E', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_1', 'agent_6'),
    ('Property 57', 'GCB', 200, 4, 19000000.00, 'Location A', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_8', 'agent_6'),
    ('Property 58', 'HDB', 150, 3, 1200000.00, 'Location C', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_9', 'agent_6'),
    ('Property 59', 'Condo', 180, 2, 9000000.00, 'Location H', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_5', 'agent_6'),
    ('Property 60', 'GCB', 250, 4, 15000000.00, 'Location B', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_2', 'agent_6');

-- Populate property with properties managed by agent_7
INSERT INTO property (name, type, size, rooms, price, location, status, image, views, seller_id, agent_id) 
VALUES
    ('Property 61', 'GCB', 150, 3, 12000000.00, 'Location H', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_7', 'agent_7'),
    ('Property 62', 'HDB', 100, 4, 150000.00, 'Location B', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_8', 'agent_7'),
    ('Property 63', 'Condo', 150, 5, 180000.00, 'Location D', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_2', 'agent_7'),
    ('Property 64', 'HDB', 100, 2, 170000.00, 'Location F', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_5', 'agent_7'),
    ('Property 65', 'GCB', 150, 3, 180000.00, 'Location A', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_9', 'agent_7'),
    ('Property 66', 'Condo', 150, 4, 180000.00, 'Location E', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_4', 'agent_7'),
    ('Property 67', 'GCB', 150, 2, 12000000.00, 'Location C', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_6', 'agent_7'),
    ('Property 68', 'HDB', 100, 5, 150000.00, 'Location G', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_1', 'agent_7'),
    ('Property 69', 'Condo', 150, 3, 180000.00, 'Location C', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_3', 'agent_7'),
    ('Property 70', 'HDB', 100, 4, 170000.00, 'Location A', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_10', 'agent_7');

-- Populate property with properties managed by agent_8
INSERT INTO property (name, type, size, rooms, price, location, status, image, views, seller_id, agent_id) 
VALUES
    ('Property 71', 'HDB', 150, 4, 900000.00, 'Location F', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_2', 'agent_8'),
    ('Property 72', 'Condo', 100, 5, 1500000.00, 'Location G', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_1', 'agent_8'),
    ('Property 73', 'GCB', 200, 3, 1800000.00, 'Location B', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_3', 'agent_8'),
    ('Property 74', 'HDB', 120, 2, 500000.00, 'Location H', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_5', 'agent_8'),
    ('Property 75', 'Condo', 150, 4, 1200000.00, 'Location D', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_3', 'agent_8'),
    ('Property 76', 'HDB', 100, 3, 600000.00, 'Location E', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_4', 'agent_8'),
    ('Property 77', 'GCB', 250, 5, 20000000.00, 'Location A', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_9', 'agent_8'),
    ('Property 78', 'Condo', 180, 3, 1400000.00, 'Location C', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_8', 'agent_8'),
    ('Property 79', 'HDB', 130, 2, 450000.00, 'Location F', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_8', 'agent_8'),
    ('Property 80', 'GCB', 220, 4, 15000000.00, 'Location D', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_4', 'agent_8');

-- Populate property with properties managed by agent_9
INSERT INTO property (name, type, size, rooms, price, location, status, image, views, seller_id, agent_id) 
VALUES
    ('Property 81', 'Condo', 150, 3, 180000.00, 'Location A', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_6', 'agent_9'),
    ('Property 82', 'HDB', 100, 5, 150000.00, 'Location C', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_2', 'agent_9'),
    ('Property 83', 'GCB', 150, 4, 12000000.00, 'Location B', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_3', 'agent_9'),
    ('Property 84', 'Condo', 150, 2, 400000.00, 'Location H', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_8', 'agent_9'),
    ('Property 85', 'HDB', 100, 3, 170000.00, 'Location D', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_10', 'agent_9'),
    ('Property 86', 'GCB', 150, 5, 20000000.00, 'Location F', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_5', 'agent_9'),
    ('Property 87', 'HDB', 100, 4, 190000.00, 'Location E', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_1', 'agent_9'),
    ('Property 88', 'Condo', 150, 2, 450000.00, 'Location G', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_7', 'agent_9'),
    ('Property 89', 'GCB', 150, 3, 16000000.00, 'Location A', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_9', 'agent_9'),
    ('Property 90', 'HDB', 100, 2, 250000.00, 'Location H', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_4', 'agent_9');

-- Populate property with properties managed by agent_10
INSERT INTO property (name, type, size, rooms, price, location, status, image, views, seller_id, agent_id) 
VALUES
    ('Property 91', 'HDB', 100, 3, 170000.00, 'Location C', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_8', 'agent_10'),
    ('Property 92', 'HDB', 100, 4, 900000.00, 'Location D', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_2', 'agent_10'),
    ('Property 93', 'Condo', 150, 5, 1500000.00, 'Location F', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_5', 'agent_10'),
    ('Property 94', 'GCB', 200, 3, 1800000.00, 'Location G', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_7', 'agent_10'),
    ('Property 95', 'Condo', 120, 2, 600000.00, 'Location H', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_1', 'agent_10'),
    ('Property 96', 'GCB', 250, 4, 20000000.00, 'Location E', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_10', 'agent_10'),
    ('Property 97', 'HDB', 110, 2, 450000.00, 'Location B', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_3', 'agent_10'),
    ('Property 98', 'Condo', 180, 3, 1700000.00, 'Location A', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_9', 'agent_10'),
    ('Property 99', 'HDB', 130, 5, 1200000.00, 'Location H', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_6', 'agent_10'),
    ('Property 100', 'Condo', 160, 4, 800000.00, 'Location D', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_4', 'agent_10');

-- Create shortlist table
CREATE TABLE shortlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    property_id INT NOT NULL,
    buyer_id VARCHAR(50) NOT NULL,
    FOREIGN KEY (property_id) REFERENCES property(id),
    FOREIGN KEY (buyer_id) REFERENCES user_accounts(username),
    UNIQUE KEY(property_id, buyer_id) -- Ensures each property is shortlisted only once by each buyer
);

-- Populate shortlist with buyer shortlists
INSERT INTO shortlist (property_id, buyer_id)
SELECT p.id, u.username
FROM (
    SELECT id, ROW_NUMBER() OVER() AS rn
    FROM property
    ORDER BY RAND()
    LIMIT 50
) p
JOIN (
    SELECT CONCAT('buyer_', t.n) AS username
	FROM (
    SELECT ROW_NUMBER() OVER() AS n
    FROM INFORMATION_SCHEMA.TABLES
    LIMIT 10
	) t
) u 
LIMIT 100;