<?php
include 'connection.php';
include '../../Models/Customer.php';
include '../../Models/Item.php';
include '../../Models/Supplier.php';

// ---------------------- CUSTOMERS ----------------------
function createCustomer($ref_no, $name) {
    global $conn;
    $conn->query("INSERT INTO Customers (REF_NO, NAME) VALUES ('$ref_no', '$name')");
    $id = $conn->insert_id;
    return new Customer($id, $ref_no, $name);
}

function readCustomers() {
    global $conn;
    $result = $conn->query("SELECT * FROM Customers");
    $customers = [];

    while ($row = $result->fetch_assoc()) {
        $customers[] = new Customer($row['ID'], $row['REF_NO'], $row['NAME']);
    }

    return $customers;
}

function readCustomerById($id) {
    global $conn;
    $result = $conn->query("SELECT * FROM Customers WHERE ID = $id");
    if ($row = $result->fetch_assoc()) {
        return new Customer($row['ID'], $row['REF_NO'], $row['NAME']);
    }
    return null;
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
    $conn->query("INSERT INTO Suppliers (REF_NO, NAME) VALUES ('$ref_no', '$name')");
    $id = $conn->insert_id;
    return new Supplier($id, $ref_no, $name);
}

function readSuppliers() {
    global $conn;
    $result = $conn->query("SELECT * FROM Suppliers");
    $suppliers = [];

    while ($row = $result->fetch_assoc()) {
        $suppliers[] = new Supplier($row['ID'], $row['REF_NO'], $row['NAME']);
    }

    return $suppliers;
}

function readSupplierById($id) {
    global $conn;
    $result = $conn->query("SELECT * FROM Suppliers WHERE ID = $id");
    if ($row = $result->fetch_assoc()) {
        return new Supplier($row['ID'], $row['REF_NO'], $row['NAME']);
    }
    return null;
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
    $conn->query("INSERT INTO Items (REF_NO, NAME, PRICE) VALUES ('$ref_no', '$name', $price)");
    $id = $conn->insert_id;
    return new Item($id, $ref_no, $name, $price);
}

function readItems() {
    global $conn;
    $result = $conn->query("SELECT * FROM Items");
    $items = [];

    while ($row = $result->fetch_assoc()) {
        $items[] = new Item($row['ID'], $row['REF_NO'], $row['NAME'], $row['PRICE']);
    }

    return $items;
}

function readItemById($id) {
    global $conn;
    $result = $conn->query("SELECT * FROM Items WHERE ID = $id");
    if ($row = $result->fetch_assoc()) {
        return new Item($row['ID'], $row['REF_NO'], $row['NAME'], $row['PRICE']);
    }
    return null;
}

function updateItem($id, $ref_no, $name, $price) {
    global $conn;
    return $conn->query("UPDATE Items SET REF_NO = '$ref_no', NAME = '$name', PRICE = $price WHERE ID = $id");
}

function deleteItem($id) {
    global $conn;
    return $conn->query("DELETE FROM Items WHERE ID = $id");
}
