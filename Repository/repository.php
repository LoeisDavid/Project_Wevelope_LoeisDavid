<?php
include 'connection.php';

// ---------------------- CUSTOMERS ----------------------
function createCustomer($ref_no, $name) {
    global $conn;
    return $conn->query("INSERT INTO Customers (REF_NO, NAME) VALUES ('$ref_no', '$name')");
}

function readCustomers() {
    global $conn;
    $result = $conn->query("SELECT * FROM Customers");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function readCustomerById($id) {
    global $conn;
    $result = $conn->query("SELECT * FROM Customers WHERE ID = $id");
    return $result->fetch_assoc();
}

function updateCustomer($id, $ref_no, $name) {
    global $conn;
    return $conn->query("UPDATE Customers SET REF_NO = '$ref_no', NAME = '$name' WHERE ID = $id");
}

function deleteCustomer($id) {
    global $conn;
    return $conn->query("DELETE FROM Customers WHERE ID = $id");
}


// ---------------------- SUPPLIERS ----------------------
function createSupplier($ref_no, $name) {
    global $conn;
    return $conn->query("INSERT INTO Suppliers (REF_NO, NAME) VALUES ('$ref_no', '$name')");
}

function readSuppliers() {
    global $conn;
    $result = $conn->query("SELECT * FROM Suppliers");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function readSupplierById($id) {
    global $conn;
    $result = $conn->query("SELECT * FROM Suppliers WHERE ID = $id");
    return $result->fetch_assoc();
}

function updateSupplier($id, $ref_no, $name) {
    global $conn;
    return $conn->query("UPDATE Suppliers SET REF_NO = '$ref_no', NAME = '$name' WHERE ID = $id");
}

function deleteSupplier($id) {
    global $conn;
    return $conn->query("DELETE FROM Suppliers WHERE ID = $id");
}


// ---------------------- ITEMS ----------------------
function createItem($ref_no, $name, $price) {
    global $conn;
    return $conn->query("INSERT INTO Items (REF_NO, NAME, PRICE) VALUES ('$ref_no', '$name', $price)");
}

function readItems() {
    global $conn;
    $result = $conn->query("SELECT * FROM Items");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function readItemById($id) {
    global $conn;
    $result = $conn->query("SELECT * FROM Items WHERE ID = $id");
    return $result->fetch_assoc();
}

function updateItem($id, $ref_no, $name, $price) {
    global $conn;
    return $conn->query("UPDATE Items SET REF_NO = '$ref_no', NAME = '$name', PRICE = $price WHERE ID = $id");
}

function deleteItem($id) {
    global $conn;
    return $conn->query("DELETE FROM Items WHERE ID = $id");
}
