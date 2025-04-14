<?php
require_once __DIR__ . '/connection.php';
require_once __DIR__ . '/../Models/Customer.php';
require_once __DIR__ . '/../Models/Item.php';
require_once __DIR__ . '/../Models/Supplier.php';
require_once __DIR__ . '/../Models/ItemCustomer.php';

// ---------------------- CUSTOMERS ----------------------
function createCustomer($ref_no, $name) {
    global $conn;
    try {
        $stmt = $conn->prepare("INSERT INTO Customers (REF_NO, NAME) VALUES (?, ?)");
        $stmt->bind_param("ss", $ref_no, $name);
        return $stmt->execute();
    } catch (Exception $e) {
        return false;
    }
}

function readCustomers() {
    global $conn;
    try {
        $result = $conn->query("SELECT * FROM Customers");
        $customers = [];
        while ($row = $result->fetch_assoc()) {
            $customers[] = new Customer($row['ID'], $row['NAME'], $row['REF_NO']);
        }
        return $customers;
    } catch (Exception $e) {
        return [];
    }
}

function readCustomerById($id) {
    global $conn;
    try {
        $result = $conn->query("SELECT * FROM Customers WHERE ID = $id");
        if ($row = $result->fetch_assoc()) {
            return new Customer($row['ID'], $row['NAME'], $row['REF_NO']);
        }
    } catch (Exception $e) {}
    return null;
}

function readCustomerByRef_No($ref_no) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM Customers WHERE REF_NO = ?");
        $stmt->bind_param("s", $ref_no);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return new Customer($row['ID'], $row['NAME'], $row['REF_NO']);
        }
    } catch (Exception $e) {}
    return null;
}

function updateCustomer($id, $ref_no, $name) {
    global $conn;
    try {
        $stmt = $conn->prepare("UPDATE Customers SET REF_NO = ?, NAME = ? WHERE ID = ?");
        $stmt->bind_param("ssi", $ref_no, $name, $id);
        return $stmt->execute();
    } catch (Exception $e) {
        return false;
    }
}

function deleteCustomer($id) {
    global $conn;
    try {
        $stmt = $conn->prepare("DELETE FROM Customers WHERE ID = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    } catch (Exception $e) {
        return false;
    }
}

// ---------------------- SUPPLIERS ----------------------
function createSupplier($ref_no, $name) {
    global $conn;
    try {
        $stmt = $conn->prepare("INSERT INTO Suppliers (REF_NO, NAME) VALUES (?, ?)");
        $stmt->bind_param("ss", $ref_no, $name);
        return $stmt->execute();
    } catch (Exception $e) {
        return false;
    }
}

function readSuppliers() {
    global $conn;
    try {
        $result = $conn->query("SELECT * FROM Suppliers");
        $suppliers = [];
        while ($row = $result->fetch_assoc()) {
            $suppliers[] = new Supplier($row['ID'], $row['NAME'], $row['REF_NO']);
        }
        return $suppliers;
    } catch (Exception $e) {
        return [];
    }
}

function readSupplierById($id) {
    global $conn;
    try {
        $result = $conn->query("SELECT * FROM Suppliers WHERE ID = $id");
        if ($row = $result->fetch_assoc()) {
            return new Supplier($row['ID'], $row['NAME'], $row['REF_NO']);
        }
    } catch (Exception $e) {}
    return null;
}

function readSupplierByRef_No($ref_no) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM Suppliers WHERE REF_NO = ?");
        $stmt->bind_param("s", $ref_no);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return new Supplier($row['ID'], $row['NAME'], $row['REF_NO']);
        }
    } catch (Exception $e) {}
    return null;
}

function updateSupplier($id, $ref_no, $name) {
    global $conn;
    try {
        $stmt = $conn->prepare("UPDATE Suppliers SET REF_NO = ?, NAME = ? WHERE ID = ?");
        $stmt->bind_param("ssi", $ref_no, $name, $id);
        return $stmt->execute();
    } catch (Exception $e) {
        return false;
    }
}

function deleteSupplier($id) {
    global $conn;
    try {
        $stmt = $conn->prepare("DELETE FROM Suppliers WHERE ID = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    } catch (Exception $e) {
        return false;
    }
}

// ---------------------- ITEMS ----------------------
function createItem($ref_no, $name, $price) {
    global $conn;
    try {
        $stmt = $conn->prepare("INSERT INTO Items (REF_NO, NAME, PRICE) VALUES (?, ?, ?)");
        $stmt->bind_param("ssd", $ref_no, $name, $price);
        return $stmt->execute();
    } catch (Exception $e) {
        return false;
    }
}

function readItems() {
    global $conn;
    try {
        $result = $conn->query("SELECT * FROM Items");
        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = new Item($row['ID'], $row['NAME'], $row['REF_NO'], $row['PRICE']);
        }
        return $items;
    } catch (Exception $e) {
        return [];
    }
}

function readItemById($id) {
    global $conn;
    try {
        $result = $conn->query("SELECT * FROM Items WHERE ID = $id");
        if ($row = $result->fetch_assoc()) {
            return new Item($row['ID'], $row['NAME'], $row['REF_NO'], $row['PRICE']);
        }
    } catch (Exception $e) {}
    return null;
}

function readItemByRef_No($ref_no) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM Items WHERE REF_NO = ?");
        $stmt->bind_param("s", $ref_no);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return new Item($row['ID'], $row['NAME'], $row['REF_NO'], $row['PRICE']);
        }
    } catch (Exception $e) {}
    return null;
}

function updateItem($id, $ref_no, $name, $price) {
    global $conn;
    try {
        $stmt = $conn->prepare("UPDATE Items SET REF_NO = ?, NAME = ?, PRICE = ? WHERE ID = ?");
        $stmt->bind_param("ssdi", $ref_no, $name, $price, $id);
        return $stmt->execute();
    } catch (Exception $e) {
        return false;
    }
}

function deleteItem($id) {
    global $conn;
    try {
        $stmt = $conn->prepare("DELETE FROM Items WHERE ID = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    } catch (Exception $e) {
        return false;
    }
}

// ---------------------- ITEMS_CUSTOMERS ----------------------
function createItemCustomer($item_id, $customer_id, $harga) {
    global $conn;
    try {
        $stmt = $conn->prepare("INSERT INTO items_customers (Item, Customer, Harga) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $item_id, $customer_id, $harga);
        return $stmt->execute();
    } catch (Exception $e) {
        return false;
    }
}

function readItemCustomers() {
    global $conn;
    try {
        $result = $conn->query("SELECT * FROM items_customers");
        $entries = [];
        while ($row = $result->fetch_assoc()) {
            // Menggunakan data dari $row untuk membuat objek ItemCustomer
            $itemCustomer = new ItemCustomer($row['ID'], $row['Item'], $row['Customer'], $row['Harga']);
            $entries[] = $itemCustomer; // Menyimpan objek ke array
        }
        return $entries; // Mengembalikan array berisi objek ItemCustomer
    } catch (Exception $e) {
        return [];
    }
}

function readItemCustomerById($id) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM items_customers WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            // Membuat objek ItemCustomer dan mengisinya dengan data dari query
            return new ItemCustomer($row['ID'], $row['Item'], $row['Customer'], $row['Harga']);
        }
    } catch (Exception $e) {
        return null; // Jika terjadi error atau data tidak ditemukan, return null
    }
    return null;
}


function updateItemCustomer($id, $item_id, $customer_id, $harga) {
    global $conn;
    try {
        $stmt = $conn->prepare("UPDATE items_customers SET Item = ?, Customer = ?, Harga = ? WHERE ID = ?");
        $stmt->bind_param("iiii", $item_id, $customer_id, $harga, $id);
        return $stmt->execute();
    } catch (Exception $e) {
        return false;
    }
}

function deleteItemCustomer($id) {
    global $conn;
    try {
        $stmt = $conn->prepare("DELETE FROM items_customers WHERE ID = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    } catch (Exception $e) {
        return false;
    }
}


// Function to search for customers based on ID, REF_NO, or NAME
function searchCustomers($query) {
    global $conn;
    try {
        $query = "%" . $query . "%"; // Menambahkan wildcard % untuk LIKE
        $stmt = $conn->prepare("SELECT * FROM Customers WHERE ID = ? OR REF_NO LIKE ? OR NAME LIKE ?");
        
        // Bind parameter: ID (integer), REF_NO and NAME (string)
        $idQuery = is_numeric($query) ? (int)$query : 0;
        $stmt->bind_param("iss", $idQuery, $query, $query);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $customers = [];
        while ($row = $result->fetch_assoc()) {
            $customers[] = new Customer($row['ID'], $row['NAME'], $row['REF_NO']);
        }
        return $customers;
    } catch (Exception $e) {
        return [];
    }
}

// Function to search for suppliers based on ID, REF_NO, or NAME
function searchSuppliers($query) {
    global $conn;
    try {
        $query = "%" . $query . "%"; // Menambahkan wildcard % untuk LIKE
        $stmt = $conn->prepare("SELECT * FROM Suppliers WHERE ID = ? OR REF_NO LIKE ? OR NAME LIKE ?");
        
        // Bind parameter: ID (integer), REF_NO and NAME (string)
        $idQuery = is_numeric($query) ? (int)$query : 0;
        $stmt->bind_param("iss", $idQuery, $query, $query);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $suppliers = [];
        while ($row = $result->fetch_assoc()) {
            $suppliers[] = new Supplier($row['ID'], $row['NAME'], $row['REF_NO']);
        }
        return $suppliers;
    } catch (Exception $e) {
        return [];
    }
}

// Function to search for items based on ID, REF_NO, or NAME
function searchItems($query) {
    global $conn;
    try {
        $query = "%" . $query . "%"; // Menambahkan wildcard % untuk LIKE
        $stmt = $conn->prepare("SELECT * FROM Items WHERE ID = ? OR REF_NO LIKE ? OR NAME LIKE ?");
        
        // Bind parameter: ID (integer), REF_NO and NAME (string)
        $idQuery = is_numeric($query) ? (int)$query : 0;
        $stmt->bind_param("iss", $idQuery, $query, $query);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = new Item($row['ID'], $row['NAME'], $row['REF_NO'], $row['PRICE']);
        }
        return $items;
    } catch (Exception $e) {
        return [];
    }
}

// Function to search for items_customers based on Item ID, Customer ID, or Harga// Function to search for items_customers based on Item ID, Customer ID, or Harga
function searchItemCustomers($query) {
    global $conn;
    try {
        $query = "%" . $query . "%"; // Menambahkan wildcard % untuk LIKE
        $stmt = $conn->prepare("SELECT * FROM items_customers WHERE Item LIKE ? OR Customer LIKE ? OR Harga LIKE ?");
        
        // Bind parameter: Item, Customer, and Harga (semuanya sebagai string)
        $stmt->bind_param("sss", $query, $query, $query);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $entries = [];
        while ($row = $result->fetch_assoc()) {
            // Membuat objek ItemCustomer dan menyimpannya ke array entries
            $itemCustomer = new ItemCustomer($row['ID'], $row['Item'], $row['Customer'], $row['Harga']);
            $entries[] = $itemCustomer;
        }
        return $entries;
    } catch (Exception $e) {
        return [];
    }
}

