<?php
// CRUD functions refactored to use Medoo
// Assumes $database (instance of Medoo) is already initialized and in scope

require_once __DIR__ . '/connection.php'; // atau path sesuai struktur folder kamu


// ---------------------- ItemInv ----------------------
function createItemInv($invoiceId, $itemId, $qty, $price) {
    global $database;
    $total = $qty * $price;
    $data = [
        'INVOICE_ID' => $invoiceId,
        'ITEM_ID'    => $itemId,
        'QTY'        => $qty,
        'PRICE'      => $price,
        'TOTAL'      => $total
    ];
    return (bool) $database->insert('iteminv', $data);
}

function readItemInvs() {
    global $database;
    $rows = $database->select('iteminv', '*');
    $entries = [];
    foreach ($rows as $row) {
        $entries[] = new ItemInv(
            $row['ID'],
            $row['INVOICE_ID'],
            $row['ITEM_ID'],
            $row['QTY'],
            $row['PRICE'],
            $row['TOTAL']
        );
    }
    return $entries;
}

function readItemInvById($id) {
    global $database;
    $row = $database->get('iteminv', '*', ['ID' => $id]);
    if ($row) {
        return new ItemInv(
            $row['ID'],
            $row['INVOICE_ID'],
            $row['ITEM_ID'],
            $row['QTY'],
            $row['PRICE'],
            $row['TOTAL']
        );
    }
    return null;
}

function readItemInvByItemId($itemId) {
    global $database;
    $rows = $database->select('iteminv', '*', ['ITEM_ID' => $itemId]);
    $entries = [];
    foreach ($rows as $row) {
        $entries[] = new ItemInv(
            $row['ID'],
            $row['INVOICE_ID'],
            $row['ITEM_ID'],
            $row['QTY'],
            $row['PRICE'],
            $row['TOTAL']
        );
    }
    return $entries;
}

function readItemInvByInvoice($invoiceId) {
    global $database;
    $rows = $database->select('iteminv', '*', ['INVOICE_ID' => $invoiceId]);
    $entries = [];
    foreach ($rows as $row) {
        $entries[] = new ItemInv(
            $row['ID'],
            $row['INVOICE_ID'],
            $row['ITEM_ID'],
            $row['QTY'],
            $row['PRICE'],
            $row['TOTAL']
        );
    }
    return $entries;
}

function updateItemInv($id, $invoiceId, $itemId, $qty, $price) {
    global $database;
    $total = $qty * $price;
    $data = [
        'INVOICE_ID' => $invoiceId,
        'ITEM_ID'    => $itemId,
        'QTY'        => $qty,
        'PRICE'      => $price,
        'TOTAL'      => $total
    ];
    return (bool) $database->update('iteminv', $data, ['ID' => $id])->rowCount();
}

function deleteItemInv($id) {
    global $database;
    return (bool) $database->delete('iteminv', ['ID' => $id]);
}

function deleteItemInvByInvId($invoiceId) {
    global $database;
    return (bool) $database->delete('iteminv', ['INVOICE_ID' => $invoiceId]);
}

function deleteItemInvByItemId($itemId) {
    global $database;
    return (bool) $database->delete('iteminv', ['ITEM_ID' => $itemId]);
}

function searchItemInvs($query) {
    global $database;
    $queryStr = "%$query%";
    $rows = $database->select(
        'iteminv',
        '*',
        [
            'OR' => [
                'INVOICE_ID[~]' => $queryStr,
                'ITEM_ID[~]'    => $queryStr,
                'QTY[~]'        => $queryStr,
                'PRICE[~]'      => $queryStr
            ]
        ]
    );
    $entries = [];
    foreach ($rows as $row) {
        $entries[] = new ItemInv(
            $row['ID'],
            $row['INVOICE_ID'],
            $row['ITEM_ID'],
            $row['QTY'],
            $row['PRICE'],
            $row['TOTAL']
        );
    }
    return $entries;
}

// ---------------------- Invoice ----------------------
function createInvoice($customerId, $tanggal, $kode) {
    global $database;
    $data = [
        'CUSTOMER_ID' => $customerId,
        'DATE'        => $tanggal,
        'KODE'        => $kode
    ];
    return (bool) $database->insert('invoice', $data);
}

function readInvoices() {
    global $database;
    $rows = $database->select('invoice', '*');
    $invoices = [];
    foreach ($rows as $row) {
        $invoices[] = new Invoice(
            $row['ID'],
            $row['KODE'],
            $row['DATE'],
            $row['CUSTOMER_ID']
        );
    }
    return $invoices;
}

function readInvoiceById($id) {
    global $database;
    $row = $database->get('invoice', '*', ['ID' => $id]);
    if ($row) {
        return new Invoice(
            $row['ID'],
            $row['KODE'],
            $row['DATE'],
            $row['CUSTOMER_ID']
        );
    }
    return null;
}

function readInvoiceByKode($kode) {
    global $database;
    $row = $database->get('invoice', '*', ['KODE' => $kode]);
    if ($row) {
        return new Invoice(
            $row['ID'],
            $row['KODE'],
            $row['DATE'],
            $row['CUSTOMER_ID']
        );
    }
    return null;
}

function readInvoiceByCustomer($customerId) {
    global $database;
    $rows = $database->select('invoice', '*', ['CUSTOMER_ID' => $customerId]);
    $invoices = [];
    foreach ($rows as $row) {
        $invoices[] = new Invoice(
            $row['ID'],
            $row['KODE'],
            $row['DATE'],
            $row['CUSTOMER_ID']
        );
    }
    return $invoices;
}

function updateInvoice($id, $customerId, $tanggal, $kode) {
    global $database;
    $data = [
        'CUSTOMER_ID' => $customerId,
        'DATE'        => $tanggal,
        'KODE'        => $kode
    ];
    return (bool) $database->update('invoice', $data, ['ID' => $id])->rowCount();
}

function deleteInvoice($id) {
    global $database;
    // delete related iteminv records
    deleteItemInvByInvId($id);
    return (bool) $database->delete('invoice', ['ID' => $id]);
}

function searchInvoices($query) {
    global $database;
    $queryStr = "%$query%";
    $idQuery  = is_numeric($query) ? (int)$query : 0;
    $rows = $database->select(
        'invoice', '*',
        [
            'OR' => [
                'ID'            => $idQuery,
                'CUSTOMER_ID[~]' => $queryStr,
                'DATE[~]'        => $queryStr
            ]
        ]
    );
    $invoices = [];
    foreach ($rows as $row) {
        $invoices[] = new Invoice(
            $row['ID'],
            $row['KODE'],
            $row['DATE'],
            $row['CUSTOMER_ID']
        );
    }
    return $invoices;
}

// ---------------------- Customers ----------------------
function createCustomer($ref_no, $name) {
    global $database;
    return (bool) $database->insert('Customers', [
        'REF_NO' => $ref_no,
        'NAME'   => $name
    ]);
}

function readCustomers() {
    global $database;
    $rows = $database->select('Customers', '*');
    $customers = [];
    foreach ($rows as $row) {
        $customers[] = new Customer(
            $row['ID'],
            $row['NAME'],
            $row['REF_NO']
        );
    }
    return $customers;
}

function readCustomerById($id) {
    global $database;
    $row = $database->get('Customers', '*', ['ID' => $id]);
    if ($row) {
        return new Customer(
            $row['ID'],
            $row['NAME'],
            $row['REF_NO']
        );
    }
    return null;
}

function updateCustomer($id, $ref_no, $name) {
    global $database;
    return (bool) $database->update('Customers', [
        'REF_NO' => $ref_no,
        'NAME'   => $name
    ], ['ID' => $id])->rowCount();
}

function deleteCustomer($id) {
    global $database;
    // delete related item_customers and invoices
    $itemCustomers = readItemCustomerByCustomerId($id);
    foreach ($itemCustomers as $ic) {
        deleteItemCustomer($ic->getId());
    }
    $invoices = readInvoiceByCustomer($id);
    foreach ($invoices as $inv) {
        deleteInvoice($inv->getId());
    }
    return (bool) $database->delete('Customers', ['ID' => $id]);
}

// ---------------------- Suppliers ----------------------
function createSupplier($ref_no, $name) {
    global $database;
    return (bool) $database->insert('Suppliers', [
        'REF_NO' => $ref_no,
        'NAME'   => $name
    ]);
}

function readSuppliers() {
    global $database;
    $rows = $database->select('Suppliers', '*');
    $suppliers = [];
    foreach ($rows as $row) {
        $suppliers[] = new Supplier(
            $row['ID'],
            $row['NAME'],
            $row['REF_NO']
        );
    }
    return $suppliers;
}

function readSupplierById($id) {
    global $database;
    $row = $database->get('Suppliers', '*', ['ID' => $id]);
    return $row ? new Supplier($row['ID'], $row['NAME'], $row['REF_NO']) : null;
}

function updateSupplier($id, $ref_no, $name) {
    global $database;
    return (bool) $database->update('Suppliers', [
        'REF_NO' => $ref_no,
        'NAME'   => $name
    ], ['ID' => $id])->rowCount();
}

function deleteSupplier($id) {
    global $database;
    return (bool) $database->delete('Suppliers', ['ID' => $id]);
}

// ---------------------- Items ----------------------
function createItem($ref_no, $name, $price) {
    global $database;
    return (bool) $database->insert('Items', [
        'REF_NO' => $ref_no,
        'NAME'   => $name,
        'PRICE'  => $price
    ]);
}

function readItems() {
    global $database;
    $rows = $database->select('Items', '*');
    $items = [];
    foreach ($rows as $row) {
        $items[] = new Item(
            $row['ID'],
            $row['NAME'],
            $row['REF_NO'],
            $row['PRICE']
        );
    }
    return $items;
}

function readItemById($id) {
    global $database;
    $row = $database->get('Items', '*', ['ID' => $id]);
    return $row ? new Item($row['ID'], $row['NAME'], $row['REF_NO'], $row['PRICE']) : null;
}

function updateItem($id, $ref_no, $name, $price) {
    global $database;
    return (bool) $database->update('Items', [
        'REF_NO' => $ref_no,
        'NAME'   => $name,
        'PRICE'  => $price
    ], ['ID' => $id])->rowCount();
}

function deleteItem($id) {
    global $database;
    // delete related item_customers, iteminv and invoices
    $itemCustomers = readItemCustomerByItemId($id);
    foreach ($itemCustomers as $ic) {
        deleteItemCustomer($ic->getId());
    }
    $itemInvs = readItemInvByItemId($id);
    foreach ($itemInvs as $ii) {
        deleteItemInv($ii->getId());
    }
    return (bool) $database->delete('Items', ['ID' => $id]);
}

// ---------------------- Items_Customers ----------------------
function createItemCustomer($item_id, $customer_id, $harga) {
    global $database;
    return (bool) $database->insert('items_customers', [
        'Item'     => $item_id,
        'Customer' => $customer_id,
        'Harga'    => $harga
    ]);
}

function readItemCustomers() {
    global $database;
    $rows = $database->select('items_customers', '*');
    $entries = [];
    foreach ($rows as $row) {
        $entries[] = new ItemCustomer(
            $row['ID'],
            $row['Item'],
            $row['Customer'],
            $row['Harga']
        );
    }
    return $entries;
}

function readItemCustomerById($id) {
    global $database;
    $row = $database->get('items_customers', '*', ['ID' => $id]);
    return $row ? new ItemCustomer($row['ID'], $row['Item'], $row['Customer'], $row['Harga']) : null;
}

function readItemCustomerByCustomerId($customerId) {
    global $database;
    $rows = $database->select('items_customers', '*', ['Customer' => $customerId]);
    $entries = [];
    foreach ($rows as $row) {
        $entries[] = new ItemCustomer(
            $row['ID'],
            $row['Item'],
            $row['Customer'],
            $row['Harga']
        );
    }
    return $entries;
}

function readItemCustomerByItemId($itemId) {
    global $database;
    $rows = $database->select('items_customers', '*', ['Item' => $itemId]);
    $entries = [];
    foreach ($rows as $row) {
        $entries[] = new ItemCustomer(
            $row['ID'],
            $row['Item'],
            $row['Customer'],
            $row['Harga']
        );
    }
    return $entries;
}

function updateItemCustomer($id, $item_id, $customer_id, $harga) {
    global $database;
    return (bool) $database->update('items_customers', [
        'Item'     => $item_id,
        'Customer' => $customer_id,
        'Harga'    => $harga
    ], ['ID' => $id])->rowCount();
}

function deleteItemCustomer($id) {
    global $database;
    return (bool) $database->delete('items_customers', ['ID' => $id]);
}

function searchItemCustomers($query) {
    global $database;
    $queryStr = "%$query%";
    $rows = $database->select(
        'items_customers', '*',
        ['OR' => [
            'Item[~]'     => $queryStr,
            'Customer[~]' => $queryStr,
            'Harga[~]'    => $queryStr
        ]]
    );
    $entries = [];
    foreach ($rows as $row) {
        $entries[] = new ItemCustomer(
            $row['ID'], $row['Item'], $row['Customer'], $row['Harga']
        );
    }
    return $entries;
}
