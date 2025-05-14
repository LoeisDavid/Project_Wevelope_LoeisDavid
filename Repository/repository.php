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
    // $entries = [];
    // foreach ($rows as $row) {
    //     $entries[] = new ItemInv(
    //         $row['ID'],
    //         $row['INVOICE_ID'],
    //         $row['ITEM_ID'],
    //         $row['QTY'],
    //         $row['PRICE'],
    //         $row['TOTAL']
    //     );
    // }
    // return $entries;

    return $rows;
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
    // $entries = [];
    // foreach ($rows as $row) {
    //     $entries[] = new ItemInv(
    //         $row['ID'],
    //         $row['INVOICE_ID'],
    //         $row['ITEM_ID'],
    //         $row['QTY'],
    //         $row['PRICE'],
    //         $row['TOTAL']
    //     );
    // }
    // return $entries;

    return $rows;
}

function readItemInvByInvoice($invoiceId) {
    global $database;
    $rows = $database->select('itemInv', '*', ['INVOICE_ID' => $invoiceId]);
    // $entries = [];
    // foreach ($rows as $row) {
    //     $entries[] = new ItemInv(
    //         $row['ID'],
    //         $row['INVOICE_ID'],
    //         $row['ITEM_ID'],
    //         $row['QTY'],
    //         $row['PRICE'],
    //         $row['TOTAL']
    //     );
    // }
    // return $entries;

    return $rows;
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
    // $invoices = [];
    // foreach ($rows as $row) {
    //     $invoices[] = new Invoice(
    //         $row['ID'],
    //         $row['KODE'],
    //         $row['DATE'],
    //         $row['CUSTOMER_ID']
    //     );
    // }
    // return $invoices;

    return $rows;
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
    // $invoices = [];
    // foreach ($rows as $row) {
    //     $invoices[] = new Invoice(
    //         $row['ID'],
    //         $row['KODE'],
    //         $row['DATE'],
    //         $row['CUSTOMER_ID']
    //     );
    // }
    // return $invoices;

    return $rows;
}

function readInvoiceByRangeDate($startDate, $endDate) {
    global $database;
    $rows = $database->select("invoice", "*", [
        "DATE[<>]" => [$startDate, $endDate]
    ]);
    // $invoices = [];
    // foreach ($rows as $row) {
    //     $invoices[] = new Invoice(
    //         $row['ID'],
    //         $row['KODE'],
    //         $row['DATE'],
    //         $row['CUSTOMER_ID']
    //     );
    // }
    // return $invoices;

    return $rows;
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
    
    try{
        return (bool) $database->delete('invoice', ['ID' => $id]);
    } catch (Exception $e){
        return false;
    }
    
}

function searchInvoices(
    $kode          = null,
    $startDate     = null,
    $endDate       = null,
    $customerId    = null,
    $customerName  = null
) {
    global $database;
    $invoices = [];
    $addedIds = [];

    // Normalize empty strings to null
    $kode         = $kode === '' ? null : $kode;
    $startDate    = $startDate === '' ? null : $startDate;
    $endDate      = $endDate === '' ? null : $endDate;
    $customerId   = $customerId === '' ? null : $customerId;
    $customerName = $customerName === '' ? null : $customerName;

    // 1) Derive customer IDs if filtering by customerName (only if customerId not provided)
    $derivedCustomerIds = [];
    if ($customerName !== null && $customerId === null) {
        $custRows = $database->select('customers', 'ID', [
            'NAME[~]' => "%{$customerName}%"
        ]);
        if (!empty($custRows)) {
            $derivedCustomerIds = $custRows;
        }
    }

    // 2) Build AND conditions for invoice table
    $conds = ['AND' => []];

    if ($kode !== null) {
        $conds['AND']['KODE[~]'] = "%{$kode}%";
    }

    // Date condition: range or single
    if ($startDate !== null && $endDate !== null) {
        $conds['AND']['DATE[<>]'] = [$startDate, $endDate];
    } elseif ($startDate !== null) {
        $conds['AND']['DATE'] = $startDate;
    } elseif ($endDate !== null) {
        $conds['AND']['DATE'] = $endDate;
    }

    // 3) Customer filter: prefer customerId; else use derivedCustomerIds
    if ($customerId !== null) {
        $conds['AND']['CUSTOMER_ID'] = (int)$customerId;
    } elseif (!empty($derivedCustomerIds)) {
        $conds['AND']['CUSTOMER_ID'] = $derivedCustomerIds;
    }

    // 4) Fetch invoices
    $rows = $database->select('invoice', '*', $conds);

    // 5) Wrap into objects and return
    // foreach ($rows as $r) {
    //     if (!in_array($r['ID'], $addedIds, true)) {
    //         $invoices[] = new Invoice(
    //             $r['ID'],
    //             $r['KODE'],
    //             $r['DATE'],
    //             $r['CUSTOMER_ID']
    //         );
    //         $addedIds[] = $r['ID'];
    //     }
    // }

    // return $invoices;

    return $rows;
}

use Medoo\Raw;
function SearchInvoicesWeek($mingguKe) {
    global $database;
    // Validasi input
    if ($mingguKe < 1 || $mingguKe > 5) {
        return ['error' => 'Minggu ke harus antara 1 dan 5'];
    }

    // Inisialisasi koneksi database (ganti sesuai konfigurasi kamu)

    // Hitung tanggal awal dan akhir untuk minggu ke-n
    $tanggalMulai = ($mingguKe - 1) * 7 + 1;
    $tanggalAkhir = min($mingguKe * 7, 31); // Hindari lebih dari 31

    // Ambil data invoice berdasarkan rentang hari dalam bulan
    $data = $database->select('invoice', '*', [
        'AND' => [
            new Raw("DAY(`DATE`) >= $tanggalMulai"),
            new Raw("DAY(`DATE`) <= $tanggalAkhir")
        ]
    ]);

    // $invoices = [];

    // foreach ($data as $r) {
        
    //         $invoices[] = new Invoice(
    //             $r['ID'],
    //             $r['KODE'],
    //             $r['DATE'],
    //             $r['CUSTOMER_ID']
    //         );       
    // }

    // return $invoices;

    return $data;
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
    // $customers = [];
    // foreach ($rows as $row) {
    //     $customers[] = new Customer(
    //         $row['ID'],
    //         $row['NAME'],
    //         $row['REF_NO']
    //     );
    // }
    // return $customers;

    return $rows;
}

function searchCustomersByName($query) {
    global $database;
    $queryStr = "%$query%";
    $rows = $database->select('customers', '*', ['NAME[~]' => $queryStr]);
    // $customers = [];
    // foreach ($rows as $row) {
    //     $customers[] = new Customer(
    //         $row['ID'],
    //         $row['NAME'],
    //         $row['REF_NO']
    //     );
    // }
    // return $customers;

    return $rows;
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
        return new Customer($row['ID'], $row['NAME'], $row['REF_NO']);
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
    // foreach ($rows as $row) {
    //     $customers[] = new Customer($row['ID'], $row['NAME'], $row['REF_NO']);
    // }
    // return $customers;

    return $rows;
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
    // $suppliers = [];
    // foreach ($rows as $row) {
    //     $suppliers[] = new Supplier(
    //         $row['ID'],
    //         $row['NAME'],
    //         $row['REF_NO']
    //     );
    // }
    // return $suppliers;

    return $rows;
}

function searchSuppliersByName($query) {
    global $database;
    $queryStr = "%$query%";
    $rows = $database->select('suppliers', '*', ['NAME[~]' => $queryStr]);
    // $suppliers = [];
    // foreach ($rows as $row) {
    //     $suppliers[] = new Supplier(
    //         $row['ID'],
    //         $row['NAME'],
    //         $row['REF_NO']
    //     );
    // }
    // return $suppliers;

    return $rows;
}


function readSupplierByRefNo($refNo) {
    global $database;
    $row = $database->get('suppliers', '*', ['REF_NO' => $refNo]);
    if ($row) {
        return new Supplier($row['ID'], $row['NAME'], $row['REF_NO']);
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

    // $suppliers = [];
    // foreach ($rows as $row) {
    //     $suppliers[] = new Supplier($row['ID'], $row['NAME'], $row['REF_NO']);
    // }
    // return $suppliers;

    return $rows;
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
    // $items = [];
    // foreach ($rows as $row) {
    //     $items[] = new Item(
    //         $row['ID'],
    //         $row['NAME'],
    //         $row['REF_NO'],
    //         $row['PRICE']
    //     );
    // }
    // return $items;

    return $rows;
}

function searchItemsByName($query) {
    global $database;
    $queryStr = "%$query%";
    $rows = $database->select('items', '*', ['NAME[~]' => $queryStr]);
    // $items = [];
    // foreach ($rows as $row) {
    //     $items[] = new Item(
    //         $row['ID'],
    //         $row['NAME'],
    //         $row['REF_NO'],
    //         $row['PRICE']
    //     );
    // }
    // return $items;

    return $rows;
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
    // $entries = [];
    // foreach ($rows as $row) {
    //     $entries[] = new ItemCustomer(
    //         $row['ID'],
    //         $row['Item'],
    //         $row['Customer'],
    //         $row['Harga']
    //     );
    // }
    // return $entries;

    return $rows;
}

function readItemCustomerById($id) {
    global $database;
    $row = $database->get('items_customers', '*', ['ID' => $id]);
    return $row ? new ItemCustomer($row['ID'], $row['Item'], $row['Customer'], $row['Harga']) : null;
}

function readItemCustomerByCustomerId($customerId) {
    global $database;
    $rows = $database->select('items_customers', '*', ['Customer' => $customerId]);
    // $entries = [];
    // foreach ($rows as $row) {
    //     $entries[] = new ItemCustomer(
    //         $row['ID'],
    //         $row['Item'],
    //         $row['Customer'],
    //         $row['Harga']
    //     );
    // }
    // return $entries;

    return $rows;
}

function readItemCustomerByItemId($itemId) {
    global $database;
    $rows = $database->select('items_customers', '*', ['Item' => $itemId]);
    // $entries = [];
    // foreach ($rows as $row) {
    //     $entries[] = new ItemCustomer(
    //         $row['ID'],
    //         $row['Item'],
    //         $row['Customer'],
    //         $row['Harga']
    //     );
    // }
    // return $entries;

    return $rows;
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
    // foreach ($rows as $row) {
    //     if (! in_array($row['ID'], $addedIds, true)) {
    //         $entries[]  = new ItemCustomer(
    //             $row['ID'],
    //             $row['Item'],
    //             $row['Customer'],
    //             $row['Harga']
    //         );
    //         $addedIds[] = $row['ID'];
    //     }
    // }

    // return $entries;

    return $rows;
}
