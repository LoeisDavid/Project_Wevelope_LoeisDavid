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
    Tgl_Inv DATE NOT NULL,
    ID_Customer INT NOT NULL,
    FOREIGN KEY (ID_Customer) REFERENCES customers(ID)
);

-- Tabel itemInv
CREATE TABLE itemInv (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    ID_Item INT NOT NULL,
    ID_Invoice INT NOT NULL,
    Harga DOUBLE,
    Qty INT,
    Total DOUBLE,
    FOREIGN KEY (ID_Item) REFERENCES items(ID),
    FOREIGN KEY (ID_Invoice) REFERENCES invoice(ID)
);




