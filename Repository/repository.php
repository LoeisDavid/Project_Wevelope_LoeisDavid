<?php
require_once __DIR__ . '/connection.php';
require_once __DIR__ . '/../Models/Customer.php';
require_once __DIR__ . '/../Models/Item.php';
require_once __DIR__ . '/../Models/Supplier.php';

// ---------------------- CUSTOMERS ----------------------
function createCustomer($ref_no, $name) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO Customers (REF_NO, NAME) VALUES (?, ?)");
    $stmt->bind_param("ss", $ref_no, $name);
    return $stmt->execute(); // boolean
}


function readCustomers() {
    global $conn;
    $result = $conn->query("SELECT * FROM Customers");
    $customers = [];

    while ($row = $result->fetch_assoc()) {
        $customers[] = new Customer($row['ID'], $row['NAME'], $row['REF_NO']);
    }

    return $customers;
}

function readCustomerById($id) {
    global $conn;
    $result = $conn->query("SELECT * FROM Customers WHERE ID = $id");
    if ($row = $result->fetch_assoc()) {
        return new Customer($row['ID'], $row['NAME'], $row['REF_NO']);
    }
    return null;
}

function updateCustomer($id, $ref_no, $name) {
    global $conn;
    $stmt = $conn->prepare("UPDATE Customers SET REF_NO = ?, NAME = ? WHERE ID = ?");
    $stmt->bind_param("ssi", $ref_no, $name, $id);
    return $stmt->execute(); // boolean
}

function deleteCustomer($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM Customers WHERE ID = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute(); // boolean
}


// ---------------------- SUPPLIERS ----------------------
function createSupplier($ref_no, $name) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO Suppliers (REF_NO, NAME) VALUES (?, ?)");
    $stmt->bind_param("ss", $ref_no, $name);
    return $stmt->execute();
}


function readSuppliers() {
    global $conn;
    $result = $conn->query("SELECT * FROM Suppliers");
    $suppliers = [];

    while ($row = $result->fetch_assoc()) {
        $suppliers[] = new Supplier($row['ID'], $row['NAME'], $row['REF_NO']);
    }

    return $suppliers;
}

function readSupplierById($id) {
    global $conn;
    $result = $conn->query("SELECT * FROM Suppliers WHERE ID = $id");
    if ($row = $result->fetch_assoc()) {
        return new Supplier($row['ID'], $row['NAME'], $row['REF_NO']);
    }
    return null;
}

function updateSupplier($id, $ref_no, $name) {
    global $conn;
    $stmt = $conn->prepare("UPDATE Suppliers SET REF_NO = ?, NAME = ? WHERE ID = ?");
    $stmt->bind_param("ssi", $ref_no, $name, $id);
    return $stmt->execute();
}


function deleteSupplier($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM Suppliers WHERE ID = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

// ---------------------- ITEMS ----------------------
function createItem($ref_no, $name, $price) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO Items (REF_NO, NAME, PRICE) VALUES (?, ?, ?)");
    $stmt->bind_param("ssd", $ref_no, $name, $price);
    return $stmt->execute();
}


function readItems() {
    global $conn;
    $result = $conn->query("SELECT * FROM Items");
    $items = [];

    while ($row = $result->fetch_assoc()) {
        $items[] = new Item($row['ID'], $row['NAME'], $row['REF_NO'], $row['PRICE']);
    }

    return $items;
}

function readItemById($id) {
    global $conn;
    $result = $conn->query("SELECT * FROM Items WHERE ID = $id");
    if ($row = $result->fetch_assoc()) {
        return new Item($row['ID'], $row['NAME'], $row['REF_NO'], $row['PRICE']);
    }
    return null;
}

function updateItem($id, $ref_no, $name, $price) {
    global $conn;
    $stmt = $conn->prepare("UPDATE Items SET REF_NO = ?, NAME = ?, PRICE = ? WHERE ID = ?");
    $stmt->bind_param("ssdi", $ref_no, $name, $price, $id);
    return $stmt->execute();
}


function deleteItem($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM Items WHERE ID = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

