<?php
require_once __DIR__ . '/../Repository/repository.php';

$method = $_SERVER['REQUEST_METHOD'];
$type   = $_GET['type'] ?? null;
$action = $_GET['action'] ?? null;
$id     = $_GET['id'] ?? null;

if ($method === 'POST') {

    if ($type === 'customer' && $action === 'create') {
        echo createCustomer($_POST['ref_no'], $_POST['name']) ? "Created." : "Error.";
    } else if ($type === 'customer' && $action === 'update') {
        echo updateCustomer($_POST['id'], $_POST['ref_no'], $_POST['name']) ? "Updated." : "Error.";
        header("Location: ../pages/html/tableCustomers.php");
        exit();
    } else if ($type === 'supplier' && $action === 'create') {
        echo createSupplier($_POST['ref_no'], $_POST['name']) ? "Created." : "Error.";
    } else if ($type === 'supplier' && $action === 'update') {
        echo updateSupplier($_POST['id'], $_POST['ref_no'], $_POST['name']) ? "Updated." : "Error.";
        header("Location: ../pages/html/tableSuppliers.php");
        exit();
    } else if ($type === 'item' && $action === 'create') {
        echo createItem($_POST['ref_no'], $_POST['name'], $_POST['price']) ? "Created." : "Error.";
    } else if ($type === 'item' && $action === 'update') {
        echo updateItem($_POST['id'], $_POST['ref_no'], $_POST['name'], $_POST['price']) ? "Updated." : "Error.";
        header("Location: ../pages/html/tableItems.php");
        exit();
    } else {
        echo "Invalid action.";
    }

    // Redirect
    if ($type === 'customer') {
        header("Location: ../pages/html/inputCustomers.html");
        exit();
    } else if ($type === 'item') {
        header("Location: ../pages/html/inputItems.html");
        exit();
    } else if ($type === 'supplier') {
        header("Location: ../pages/html/inputSuppliers.html");
        exit();
    }

} else if ($method === 'GET') {

    if ($type === 'customer' && $action === 'read') {
        if ($id) {
            return readCustomerById($id);
        } else {
            return readCustomers();
        }
    } else if ($type === 'customer' && $action === 'delete') {
        return deleteCustomer($id) ? "Deleted." : "Error.";
    } else if ($type === 'supplier' && $action === 'read') {
        if ($id) {
            return readSupplierById($id);
        } else {
            return readSuppliers();
        }
    } else if ($type === 'supplier' && $action === 'delete') {
        return deleteSupplier($id) ? "Deleted." : "Error.";
    } else if ($type === 'item' && $action === 'read') {
        if ($id) {
            return readItemById($id);
        } else {
            return readItems();
        }
    } else if ($type === 'item' && $action === 'delete') {
        return deleteItem($id) ? "Deleted." : "Error.";
    } else {
        return "Invalid action.";
    }

} else {
    echo "Invalid request method.";
}
