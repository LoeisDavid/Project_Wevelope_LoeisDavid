<?php
include '../Repository/repository.php';

$method = $_SERVER['REQUEST_METHOD'];
$type   = $_GET['type'] ?? null;
$action = $_GET['action'] ?? null;
$id     = $_GET['id'] ?? null;

if ($method === 'POST') {

    if ($type === 'customer' && $action === 'create') {
        echo createCustomer($_POST['ref_no'], $_POST['name']) ? "Created." : "Error.";
    } else if ($type === 'customer' && $action === 'update') {
        echo updateCustomer($id, $_POST['ref_no'], $_POST['name']) ? "Updated." : "Error.";
    } else if ($type === 'supplier' && $action === 'create') {
        echo createSupplier($_POST['ref_no'], $_POST['name']) ? "Created." : "Error.";
    } else if ($type === 'supplier' && $action === 'update') {
        echo updateSupplier($id, $_POST['ref_no'], $_POST['name']) ? "Updated." : "Error.";
    } else if ($type === 'item' && $action === 'create') {
        echo createItem($_POST['ref_no'], $_POST['name'], $_POST['price']) ? "Created." : "Error.";
    } else if ($type === 'item' && $action === 'update') {
        echo updateItem($id, $_POST['ref_no'], $_POST['name'], $_POST['price']) ? "Updated." : "Error.";
    } else {
        echo "Invalid action.";
    }

} else if ($method === 'GET') {

    if ($type === 'customer' && $action === 'read') {
        echo $id ? json_encode(readCustomerById($id)) : json_encode(readCustomers());
    } else if ($type === 'customer' && $action === 'delete') {
        echo deleteCustomer($id) ? "Deleted." : "Error.";
    } else if ($type === 'supplier' && $action === 'read') {
        echo $id ? json_encode(readSupplierById($id)) : json_encode(readSuppliers());
    } else if ($type === 'supplier' && $action === 'delete') {
        echo deleteSupplier($id) ? "Deleted." : "Error.";
    } else if ($type === 'item' && $action === 'read') {
        echo $id ? json_encode(readItemById($id)) : json_encode(readItems());
    } else if ($type === 'item' && $action === 'delete') {
        echo deleteItem($id) ? "Deleted." : "Error.";
    } else {
        echo "Invalid action.";
    }

} else {
    echo "Invalid request method.";
}

if($type === 'customer'){
    header("Location: ../pages/html/inputCustomers.html");
} else if($type === 'item'){
    header("Location: ../pages/html/inputItems.html");
} else if($type === 'supplier'){
    header("Location: ../pages/html/inputSuppliers.html");
}
?>
