-- Active: 1684386115979@@127.0.0.1@3306@dumaligya_database
CREATE TABLE SELLER_USER (  
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    profile_pic_url VARCHAR(255) NOT NULL,
    pickup_location VARCHAR(255) NOT NULL,
    seller_description TEXT(4000),
    seller_schedule VARCHAR(255)
);

CREATE TABLE MEMBER_USER (  
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    profile_pic_url VARCHAR(255) NOT NULL,
    user_address VARCHAR(255) NOT NULL,
    bio VARCHAR(255)
);

CREATE TABLE USER (  
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_name VARCHAR(100) UNIQUE NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    contact_number CHAR(11) NOT NULL,
    user_password VARCHAR(100) NOT NULL,
    government_pic_url VARCHAR(255) NOT NULL,
    birth_date DATE NOT NULL,
    sex ENUM('Male', 'Female') NOT NULL,
    seller_id int UNIQUE,
    member_id int UNIQUE,
    FOREIGN KEY (seller_id) REFERENCES SELLER_USER(id) ON UPDATE CASCADE ON DELETE SET NULL,
    FOREIGN KEY (member_id) REFERENCES MEMBER_USER(id) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE CATEGORY (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(100) NOT NULL,
    seller_id int,
    FOREIGN KEY (seller_id) REFERENCES SELLER_USER(id) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE PRODUCT (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    product_type ENUM('Food and Beverages', 'Apparel', 'Others') NOT NULL,
    collection_mode ENUM('Delivery', 'Pickup') NOT NULL,
    price DECIMAL(8,2) NOT NULL,
    quantity smallint unsigned NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    product_description TEXT(4000),
    product_pic_url VARCHAR(255) NOT NULL,
    category_id int,
    seller_id int,
    FOREIGN KEY (seller_id) REFERENCES SELLER_USER(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES CATEGORY(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE MEMBER_ORDER (
    id bigint unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
    order_status ENUM('Cart', 'Ongoing', 'Completed') NOT NULL,
    pickup_location VARCHAR(255) NOT NULL,
    pickup_date_time DATETIME,
    total_amount DECIMAL(10,2),
    payment_mode VARCHAR(50),  
    collection_mode ENUM('Delivery', 'Pickup') NOT NULL,
    end_date_time DATETIME,
    seller_id int,
    member_id int,
    FOREIGN KEY (seller_id) REFERENCES SELLER_USER(id) ON UPDATE CASCADE ON DELETE SET NULL,
    FOREIGN KEY (member_id) REFERENCES MEMBER_USER(id) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE CHECKOUT_ITEM (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    quantity smallint unsigned NOT NULL,
    product_id int,
    order_id bigint unsigned,
    FOREIGN KEY (product_id) REFERENCES PRODUCT(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (order_id) REFERENCES MEMBER_ORDER(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE MESSAGE (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    source ENUM('Seller', 'Member') NOT NULL,
    mes_text TEXT(4000) NOT NULL,
    mes_timestamp DATETIME,
    seller_id int,
    member_id int,
    order_id bigint unsigned,
    FOREIGN KEY (seller_id) REFERENCES SELLER_USER(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (member_id) REFERENCES MEMBER_USER(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (order_id) REFERENCES MEMBER_ORDER(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE REVIEW (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    score smallint UNSIGNED NOT NULL,
    review_message TEXT(4000),
    review_date_time DATETIME NOT NULL,
    product_id int,
    member_id int,
    FOREIGN KEY (member_id) REFERENCES MEMBER_USER(id) ON UPDATE CASCADE ON DELETE SET NULL,
    FOREIGN KEY (product_id) REFERENCES PRODUCT(id) ON UPDATE CASCADE ON DELETE SET NULL
)