-- FIXED SCHEMA: schema.sql with foreign keys and without problematic DEFAULT

DROP TABLE IF EXISTS items_customers, iteminv, invoice, payment, suppliers, items, customers, company, pic;

CREATE TABLE company (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    NAMA_PERUSAHAAN VARCHAR(255) NOT NULL,
    ALAMAT VARCHAR(255),
    KOTA VARCHAR(100),
    PROVINSI VARCHAR(100),
    KODE_POS VARCHAR(20),
    NEGARA VARCHAR(100),
    TELEPON VARCHAR(50),
    EMAIL VARCHAR(100),
    URLLOGO VARCHAR(255)
);

CREATE TABLE customers (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    REF_NO VARCHAR(100) UNIQUE,
    NAME VARCHAR(255),
    EMAIL VARCHAR(100),
    TELEPON VARCHAR(50),
    ALAMAT VARCHAR(255)
);

CREATE TABLE suppliers (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    REF_NO VARCHAR(100) UNIQUE,
    NAME VARCHAR(255)
);

CREATE TABLE items (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    REF_NO VARCHAR(100) UNIQUE,
    NAME VARCHAR(255),
    PRICE DOUBLE
);

CREATE TABLE invoice (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    KODE VARCHAR(100) UNIQUE,
    DATE DATE,
    CUSTOMER_ID INT,
    NOTES TEXT,
    DEADLINE DATE,
    FOREIGN KEY (CUSTOMER_ID) REFERENCES customers(ID)
);

CREATE TABLE iteminv (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    INVOICE_ID INT,
    ITEM_ID INT,
    QTY INT,
    PRICE DOUBLE,
    TOTAL DOUBLE,
    FOREIGN KEY (INVOICE_ID) REFERENCES invoice(ID),
    FOREIGN KEY (ITEM_ID) REFERENCES items(ID)
);

CREATE TABLE items_customers (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Item INT,
    Customer INT,
    Harga INT,
    FOREIGN KEY (Item) REFERENCES items(ID),
    FOREIGN KEY (Customer) REFERENCES customers(ID)
);

CREATE TABLE payment (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    KODE VARCHAR(50) UNIQUE,
    DATE DATE,
    NOMINAL DOUBLE,
    ID_INVOICE INT,
    NOTES TEXT,
    FOREIGN KEY (ID_INVOICE) REFERENCES invoice(ID)
);

CREATE TABLE pic (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    NAMA VARCHAR(255) NOT NULL,
    JABATAN VARCHAR(100),
    NOMOR VARCHAR(20),
    EMAIL VARCHAR(255),
    STATUS TINYINT(1) DEFAULT 0
);