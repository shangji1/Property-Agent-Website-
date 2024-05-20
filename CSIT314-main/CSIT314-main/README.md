# add own mysql password in konohadb.php
# create a config.php file so we don't have to keep changing the password when pulling.
# <?php
# define('DB_SERVER', 'localhost');
# define('DB_USER', 'your_own_username');
# define('DB_PASSWORD', 'your_own_password');
# define('DB_NAME', 'konohadb');
# ?>
# there is alr a gitignore file so the config file will be ignored and wont commit and push to github
# Just to test code
CREATE TABLE user_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    activeStatus BOOLEAN NOT NULL,
    description VARCHAR(100)
);

INSERT INTO user_profiles (name, activeStatus, Description)
VALUES
('Buyer', true, 'I am a buyer'),
('Seller', true, 'I am a seller'),
('Agent', true, 'I am a agent'),
('Admin', true, 'I am a admin');


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
    LIMIT 5
) t;

-- Populate user accounts for Seller profile with password 'seller_password'
INSERT INTO user_accounts (username, email, password, activeStatus, profile_id) 
SELECT CONCAT('seller_', t.n), CONCAT('seller_', t.n, '@example.com'), CONCAT('seller_pass', t.n), TRUE, '2'
FROM (
    SELECT ROW_NUMBER() OVER() AS n
    FROM INFORMATION_SCHEMA.TABLES
    LIMIT 5
) t;

-- Populate user accounts for Agent profile with password 'agent_password'
INSERT INTO user_accounts (username, email, password, activeStatus, profile_id) 
SELECT CONCAT('agent_', t.n), CONCAT('agent_', t.n, '@example.com'), CONCAT('agent_pass', t.n), TRUE, '3'
FROM (
    SELECT ROW_NUMBER() OVER() AS n
    FROM INFORMATION_SCHEMA.TABLES
    LIMIT 5
) t;

-- Populate user accounts for Admin profile with password 'admin_password'
INSERT INTO user_accounts (username, email, password, activeStatus, profile_id) 
SELECT CONCAT('admin_', t.n), CONCAT('admin_', t.n, '@example.com'), CONCAT('admin_pass', t.n), TRUE, '4'
FROM (
    SELECT ROW_NUMBER() OVER() AS n
    FROM INFORMATION_SCHEMA.TABLES
    LIMIT 5
) t;

-- Create the ratings table
CREATE TABLE ratings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rating INT NOT NULL,
    customer_id VARCHAR(50) NOT NULL,
    agent_id VARCHAR(50) NOT NULL,
    FOREIGN KEY (customer_id) REFERENCES user_accounts(username),
    FOREIGN KEY (agent_id) REFERENCES user_accounts(username)
);

-- Create the reviews table
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    review TEXT NOT NULL,
    customer_id VARCHAR(50) NOT NULL,
    agent_id VARCHAR(50) NOT NULL,
    FOREIGN KEY (customer_id) REFERENCES user_accounts(username),
    FOREIGN KEY (agent_id) REFERENCES user_accounts(username)
);

-- Insert 10 rows into the ratings table
INSERT INTO ratings (rating, customer_id, agent_id)
VALUES
    (5, 'seller_2', 'agent_1'), 
    (4, 'seller_1', 'agent_1'), 
    (5, 'buyer_5', 'agent_1'),
    (3, 'buyer_4', 'agent_1'),
    (4, 'seller_3', 'agent_2'),
    (1, 'seller_2', 'agent_2'),
    (4, 'buyer_4', 'agent_2'),
    (3, 'buyer_5', 'agent_2'),
    (5, 'seller_4', 'agent_3'),
    (4, 'seller_5', 'agent_3'),
    (4, 'buyer_1', 'agent_3'), 
    (5, 'buyer_3', 'agent_3'), 
    (3, 'seller_5', 'agent_4'),
    (4, 'seller_1', 'agent_4'),
    (5, 'buyer_3', 'agent_4'),
    (2, 'buyer_2', 'agent_4'),
    (3, 'seller_2', 'agent_5'),
    (4, 'seller_4', 'agent_5'),
    (5, 'buyer_5', 'agent_5'),
    (3, 'buyer_1', 'agent_5');

-- Insert 10 rows into the reviews table
INSERT INTO reviews (review, customer_id, agent_id)
VALUES
    ('Great experience, highly recommended!', 'buyer_4', 'agent_1'), 
    ('Good service overall, could improve communication.', 'seller_1', 'agent_1'), 
    ('Excellent professionalism and timely delivery.', 'buyer_2', 'agent_1'),
    ('Average service, needs improvement in quality.', 'seller_3', 'agent_1'),
    ('Satisfactory performance, would use again.', 'buyer_5', 'agent_2'),
    ('Terrible experience, very poor communication.', 'seller_1', 'agent_2'),
    ('Impressed with the quality of work.', 'buyer_3', 'agent_2'),
    ('Fair service, met expectations.', 'seller_2', 'agent_2'),
    ('Outstanding service, exceeded expectations!', 'buyer_2', 'agent_3'),
    ('Good job, but room for improvement.', 'seller_4', 'agent_3'),
    ('Excellent service, highly recommended!', 'buyer_5', 'agent_3'), 
    ('Good experience overall, satisfied.', 'seller_1', 'agent_3'), 
    ('Could improve communication, but good otherwise.', 'buyer_4', 'agent_4'),
    ('Satisfactory performance, met expectations.', 'seller_5', 'agent_4'),
    ('Outstanding service, exceeded expectations!', 'buyer_1', 'agent_4'),
    ('Poor experience, needs improvement.', 'seller_3', 'agent_4'),
    ('Impressed with the quality of work.', 'buyer_3', 'agent_5'),
    ('Fair service, could improve.', 'seller_2', 'agent_5'),
    ('Exceptional service, highly recommended!', 'buyer_4', 'agent_5'),
    ('Average service, nothing special.', 'seller_5', 'agent_5');

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

-- Insert properties for Agent 1
INSERT INTO property (name, type, size, rooms, price, location, status, image, views, seller_id, agent_id) 
VALUES
    ('Property 3', 'GCB', 150, 2, 12000000.00, 'Location C', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_2', 'agent_1'),
    ('Property 1', 'HDB', 100, 3, 150000.00, 'Location A', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_1', 'agent_1'),
    ('Property 5', 'Condo', 150, 2, 180000.00, 'Location C', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_3', 'agent_1'),
    ('Property 2', 'HDB', 100, 3, 170000.00, 'Location A', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_5', 'agent_1'),
    ('Property 4', 'Condo', 150, 2, 180000.00, 'Location C', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_3', 'agent_1');

-- Insert properties for Agent 2
INSERT INTO property (name, type, size, rooms, price, location, status, image, views, seller_id, agent_id) 
VALUES
    ('Property 1', 'HDB', 100, 3, 150000.00, 'Location A', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_1', 'agent_2'),
    ('Property 3', 'Condo', 150, 2, 180000.00, 'Location C', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_3', 'agent_2'),
    ('Property 2', 'HDB', 200, 4, 250000.00, 'Location B', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_2', 'agent_2');

-- Insert properties for Agent 3
INSERT INTO property (name, type, size, rooms, price, location, status, image, views, seller_id, agent_id) 
VALUES
    ('Property 1', 'Condo', 150, 2, 180000.00, 'Location C', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_4', 'agent_3'),
    ('Property 2', 'GCB', 150, 2, 50000000.00, 'Location C', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_2', 'agent_3');

-- Insert properties for Agent 4
INSERT INTO property (name, type, size, rooms, price, location, status, image, views, seller_id, agent_id) 
VALUES
    ('Property 1', 'HDB', 100, 3, 150000.00, 'Location A', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_4', 'agent_4'),
    ('Property 2', 'HDB', 200, 4, 250000.00, 'Location B', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_4', 'agent_4'),
    ('Property 3', 'HDB', 150, 2, 180000.00, 'Location C', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_3', 'agent_4');

-- Insert properties for Agent 5
INSERT INTO property (name, type, size, rooms, price, location, status, image, views, seller_id, agent_id) 
VALUES
    ('Property 1', 'HDB', 100, 3, 150000.00, 'Location A', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_3', 'agent_5'),    
    ('Property 2', 'HDB', 300, 5, 550000.00, 'Location A', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_5', 'agent_5'),
    ('Property 3', 'HDB', 100, 3, 200000.00, 'Location B', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_1', 'agent_5'),
    ('Property 4', 'Condo', 200, 4, 250000.00, 'Location B', 'available', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_2', 'agent_5'),
    ('Property 5', 'Condo', 150, 2, 180000.00, 'Location C', 'sold', 'https://i.insider.com/655582f84ca513d8242a5725?width=700', 100, 'seller_1', 'agent_5');
    
CREATE TABLE shortlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    property_id INT NOT NULL,
    buyer_id VARCHAR(50) NOT NULL,
    FOREIGN KEY (property_id) REFERENCES property(id),
    FOREIGN KEY (buyer_id) REFERENCES user_accounts(username),
    UNIQUE KEY(property_id, buyer_id) -- Ensures each property is shortlisted only once by each buyer
);

-- Insert into shortlist table
INSERT INTO shortlist (property_id, buyer_id)
VALUES
    (1, 'buyer_1'),
    (1, 'buyer_4'),
    (2, 'buyer_2'),
    (2, 'buyer_4'),
    (3, 'buyer_5'),
    (3, 'buyer_1'),
    (4, 'buyer_3'),
    (4, 'buyer_2'),
    (5, 'buyer_3'),
    (5, 'buyer_5'),
    (6, 'buyer_1'),
    (6, 'buyer_4'),
    (7, 'buyer_2'),
    (7, 'buyer_4'),
    (8, 'buyer_5'),
    (8, 'buyer_1'),
    (9, 'buyer_3'),
    (9, 'buyer_2'),
    (10, 'buyer_3'),
    (10, 'buyer_5'),
    (12, 'buyer_4'),
    (18, 'buyer_1'),
    (16, 'buyer_3'),
    (13, 'buyer_2'),
    (14, 'buyer_5');
