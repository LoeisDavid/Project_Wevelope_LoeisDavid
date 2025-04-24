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
    return (bool) $database->insert('itemInv', $data);
}

function readItemInvs() {
    global $database;
    $rows = $database->select('itemInv', '*');
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
    $row = $database->get('itemInv', '*', ['ID' => $id]);
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
    $rows = $database->select('itemInv', '*', ['ITEM_ID' => $itemId]);
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
    $rows = $database->select('itemInv', '*', ['INVOICE_ID' => $invoiceId]);
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
    return (bool) $database->update('itemInv', $data, ['ID' => $id])->rowCount();
}

function deleteItemInv($id) {
    global $database;

    try {
    return (bool) $database->delete('itemInv', ['ID' => $id]);
    } catch (Exception $e) {
        return false;
    }
}

function deleteItemInvByInvId($invoiceId) {
    global $database;

    try {
        return (bool) $database->delete('itemInv', ['INVOICE_ID' => $invoiceId]);
    } catch (Exception $e) {
        return false;
    }
    
}

function deleteItemInvByItemId($itemId) {
    global $database;

    try {
        return (bool) $database->delete('itemInv', ['ITEM_ID' => $itemId]);
    } catch (Exception $e) {
        return false;
    }
    
}

function searchItemInvsInInvoice($invoiceId, $query) {
    global $database;

    $queryStr = strtolower($query);

    // Tahap 1: Cari ID item yang cocok dari tabel items
    $itemRows = $database->select('items', [
        'ID',
        'REF_NO',
        'NAME',
        'PRICE'
    ]);

    $matchingItemIds = [];
    foreach ($itemRows as $item) {
        if (
            str_contains(strtolower($item['REF_NO']), $queryStr) ||
            str_contains(strtolower($item['NAME']), $queryStr) ||
            str_contains((string)$item['PRICE'], $queryStr)
        ) {
            $matchingItemIds[] = $item['ID'];
        }
    }

    // Kalau tidak ada item yang cocok, langsung return kosong
    if (empty($matchingItemIds)) return [];

    // Tahap 2: Ambil iteminv berdasarkan invoice ID dan item_id yang cocok
    $rows = $database->select('itemInv', '*', [
        'AND' => [
            'INVOICE_ID' => $invoiceId,
            'ITEM_ID' => $matchingItemIds
        ]
    ]);

    // Bungkus ke objek
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

function readInvoiceByRangeDate($startDate, $endDate) {
    global $database;
    $rows = $database->select("invoice", "*", [
        "DATE[<>]" => [$startDate, $endDate]
    ]);
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
    
    try{
        return (bool) $database->delete('invoice', ['ID' => $id]);
    } catch (Exception $e){
        return false;
    }
    
}

function searchInvoices($query) {
    global $database;
    $invoices  = [];
    $addedIds  = [];

    // 1) Search Customer by name → get Customer objects
    $customers = searchCustomersByName($query);
    foreach ($customers as $customer) {
        // ambil ID customer
        $custId = $customer->getId();

        // cari Invoice milik customer ini (mengembalikan array Invoice objects)
        $custInvs = readInvoiceByCustomer($custId);
        foreach ($custInvs as $inv) {
            // hindari duplikat berdasarkan invoice ID
            if (! in_array($inv->getId(), $addedIds, true)) {
                $invoices[] = $inv;
                $addedIds[] = $inv->getId();
            }
        }
    }

    // 2) Normal search (KODE, CUSTOMER_ID, DATE)  
    $queryStr = "%{$query}%";
    $idQuery  = is_numeric($query) ? (int)$query : null;

    // bangun kondisi OR-nya
    $conds = ['OR' => [
        'KODE[~]'        => $queryStr,
        'DATE[~]'        => $queryStr,
    ]];
    if ($idQuery !== null) {
        // jika numeric, juga cek CUSTOMER_ID exact match
        $conds['OR']['CUSTOMER_ID'] = $idQuery;
    }

    $rows = $database->select('invoice', '*', $conds);
    foreach ($rows as $row) {
        if (! in_array($row['ID'], $addedIds, true)) {
            $invoices[] = new Invoice(
                $row['ID'],
                $row['KODE'],
                $row['DATE'],
                $row['CUSTOMER_ID']
            );
            $addedIds[] = $row['ID'];
        }
    }

    return $invoices;
}



// ---------------------- Customers ----------------------
function createCustomer($ref_no, $name) {
    global $database;
    return (bool) $database->insert('customers', [
        'REF_NO' => $ref_no,
        'NAME'   => $name
    ]);
}

function readCustomers() {
    global $database;
    $rows = $database->select('customers', '*');
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

function searchCustomersByName($query) {
    global $database;
    $queryStr = "%$query%";
    $rows = $database->select('customers', '*', ['NAME[~]' => $queryStr]);
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
    $row = $database->get('customers', '*', ['ID' => $id]);
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
    return (bool) $database->update('customers', [
        'REF_NO' => $ref_no,
        'NAME'   => $name
    ], ['ID' => $id])->rowCount();
}

function readCustomerByRefNo($refNo) {
    global $database;
    $row = $database->get('customers', '*', ['REF_NO' => $refNo]);
    if ($row) {
        return new Customer($row['ID'], $row['NAME'], $row['REF_NO'], $row['ADDRESS']);
    }
    return null;
}

function searchCustomers($query) {
    global $database;
    $rows = $database->select('customers', '*', [
        'OR' => [
            'REF_NO[~]' => $query,
            'NAME[~]'   => $query
        ]
    ]);

    $customers = [];
    foreach ($rows as $row) {
        $customers[] = new Customer($row['ID'], $row['NAME'], $row['REF_NO']);
    }
    return $customers;
}


function deleteCustomer($id) {
    global $database;
    // delete related item_customers and invoices
    try{
        return (bool) $database->delete('customers', ['ID' => $id]);
    } catch (Exception $e) {
        return false;
    }
    
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
    $rows = $database->select('suppliers', '*');
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

function searchSuppliersByName($query) {
    global $database;
    $queryStr = "%$query%";
    $rows = $database->select('suppliers', '*', ['NAME[~]' => $queryStr]);
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


function readSupplierByRefNo($refNo) {
    global $database;
    $row = $database->get('suppliers', '*', ['REF_NO' => $refNo]);
    if ($row) {
        return new Supplier($row['ID'], $row['NAME'], $row['REF_NO'], $row['ADDRESS']);
    }
    return null;
}

function searchSuppliers($query) {
    global $database;
    $rows = $database->select('suppliers', '*', [
        'OR' => [
            'REF_NO[~]' => $query,
            'NAME[~]'   => $query
        ]
    ]);

    $suppliers = [];
    foreach ($rows as $row) {
        $suppliers[] = new Supplier($row['ID'], $row['NAME'], $row['REF_NO']);
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
    try {
        return (bool) $database->delete('Suppliers', ['ID' => $id]);
    } catch (Exception $e) {
        return false;
    }
    
}
    

// ---------------------- Items ----------------------
function createItem($ref_no, $name, $price) {
    global $database;
    return (bool) $database->insert('items', [
        'REF_NO' => $ref_no,
        'NAME'   => $name,
        'PRICE'  => $price
    ]);
}

function readItems() {
    global $database;
    $rows = $database->select('items', '*');
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

function searchItemsByName($query) {
    global $database;
    $queryStr = "%$query%";
    $rows = $database->select('items', '*', ['NAME[~]' => $queryStr]);
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


function readItemByRefNo($refNo) {
    global $database;
    $row = $database->get('items', '*', ['REF_NO' => $refNo]);
    if ($row) {
        return new Item($row['ID'], $row['NAME'], $row['REF_NO'], $row['PRICE']);
    }
    return null;
}


function readItemById($id) {
    global $database;
    $row = $database->get('items', '*', ['ID' => $id]);
    return $row ? new Item($row['ID'], $row['NAME'], $row['REF_NO'], $row['PRICE']) : null;
}

function updateItem($id, $ref_no, $name, $price) {
    global $database;
    return (bool) $database->update('items', [
        'REF_NO' => $ref_no,
        'NAME'   => $name,
        'PRICE'  => $price
    ], ['ID' => $id])->rowCount();
}

function searchItems($query) {
    global $database;
    $rows = $database->select('items', '*', [
        'OR' => [
            'REF_NO[~]' => $query,
            'NAME[~]'   => $query
        ]
    ]);

    $items = [];
    foreach ($rows as $row) {
        $items[] = new Item($row['ID'], $row['NAME'], $row['REF_NO'], $row['PRICE']);
    }
    return $items;
}


function deleteItem($id) {
    global $database;
    // delete related item_customers, iteminv and invoices
    try {
        // Terakhir hapus item utama
        return (bool) $database->delete('items', ['ID' => $id]);
    } catch (Exception $e) {
        return false; // Return false kalau delete item gagal
    }
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

    try {
        return (bool) $database->delete('items_customers', ['ID' => $id]);
    } catch (Exception $e) {
        return false;
    }
    
}

function searchItemCustomers($query) {
    global $database;
    $entries   = [];
    $addedIds  = [];                 // track ID yang sudah ditambahkan
    $queryStr  = "%{$query}%";

    // 1) Search by Item name → get ItemCustomer objects
    $items = searchItemsByName($query);
    foreach ($items as $item) {
        $ics = readItemCustomerByItemId($item->getId());
        foreach ($ics as $ic) {
            if (! in_array($ic->getId(), $addedIds, true)) {
                $entries[]  = $ic;
                $addedIds[] = $ic->getId();
            }
        }
    }

    // 2) Search by Customer name → get ItemCustomer objects
    $customers = searchCustomersByName($query);
    foreach ($customers as $customer) {
        $ics = readItemCustomerByCustomerId($customer->getId());
        foreach ($ics as $ic) {
            if (! in_array($ic->getId(), $addedIds, true)) {
                $entries[]  = $ic;
                $addedIds[] = $ic->getId();
            }
        }
    }

    // 3) Normal search on items_customers (Item ID, Customer ID, Harga)
    $conds = ['OR' => [
        'Harga[~]' => $queryStr,
    ]];
    if (is_numeric($query)) {
        $id = (int)$query;
        $conds['OR']['Item']     = $id;
        $conds['OR']['Customer'] = $id;
    }

    $rows = $database->select('items_customers', '*', $conds);
    foreach ($rows as $row) {
        if (! in_array($row['ID'], $addedIds, true)) {
            $entries[]  = new ItemCustomer(
                $row['ID'],
                $row['Item'],
                $row['Customer'],
                $row['Harga']
            );
            $addedIds[] = $row['ID'];
        }
    }

    return $entries;
}
