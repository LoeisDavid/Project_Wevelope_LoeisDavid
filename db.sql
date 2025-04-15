create database projectMagang1;
use projectMagang1;

-- Tabel items
CREATE TABLE items (
    ID INT NOT NULL AUTO_INCREMENT,
    REF_NO VARCHAR(100) UNIQUE,
    NAME VARCHAR(255),
    PRICE DOUBLE,
    PRIMARY KEY (ID)
);

-- Tabel customers
CREATE TABLE customers (
    ID INT NOT NULL AUTO_INCREMENT,
    REF_NO VARCHAR(100) UNIQUE,
    NAME VARCHAR(255),
    PRIMARY KEY (ID)
);

-- Tabel suppliers
CREATE TABLE suppliers (
    ID INT NOT NULL AUTO_INCREMENT,
    REF_NO VARCHAR(100) UNIQUE,
    NAME VARCHAR(255),
    PRIMARY KEY (ID)
);

CREATE TABLE items_customers (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Item INT NOT NULL,
    Customer INT NOT NULL,
    Harga INT NOT NULL,
    FOREIGN KEY (Item) REFERENCES Items(ID) ON DELETE CASCADE,
    FOREIGN KEY (Customer) REFERENCES Customers(ID) ON DELETE CASCADE
);

-- Tabel invoice
CREATE TABLE invoice (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    KODE VARCHAR(100) UNIQUE,
    DATE DATE,
    CUSTOMER_ID INT NOT NULL,
    FOREIGN KEY (ID_Customer) REFERENCES customers(ID)
);

-- Tabel itemInv
CREATE TABLE itemInv (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    INVOICE_ID INT NOT NULL,
    ITEM_ID INT NOT NULL,
    QTY INT,
    PRICE DOUBLE,
    TOTAL DOUBLE,
    FOREIGN KEY (ITEM_ID) REFERENCES items(ID),
    FOREIGN KEY (INVOICE_ID) REFERENCES invoice(ID)
);




