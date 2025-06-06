<?php
// CRUD functions refactored to use Medoo
// Assumes $database (instance of Medoo) is already initialized and in scope

require_once __DIR__ . '/connection.php'; // atau path sesuai struktur folder kamu

// ---------------------- PIC ----------------------
function createPic($nama, $jabatan, $nomor, $email) {
    global $database;
    return (bool) $database->insert('PIC', [
        'NAMA'    => $nama,
        'JABATAN' => $jabatan,
        'NOMOR'   => $nomor,
        'EMAIL'   => $email
    ]);
}

function readPics() {
    global $database;
    return $database->select('PIC', '*');
}

function readNewPic() {
    global $database;
    return $$database->get('pic', '*', [
    "ORDER" => ["id" => "DESC"]
]);
}
function readPicById($id) {
    global $database;
    $row = $database->get('PIC', '*', ['ID' => $id]);
    if($row){
        return new Pic($row['ID'], $row['NAMA'], $row['JABATAN'], $row['NOMOR'], $row['EMAIL'], $row['STATUS']);
    } else {
        return null;
    }
}

function searchPics($query) {
    global $database;
    return $database->select('PIC', '*', [
        'OR' => [
            'NAMA[~]'    => $query,
            'JABATAN[~]' => $query,
            'EMAIL[~]'   => $query
        ]
    ]);
}

function updatePic($id, $nama, $jabatan, $nomor, $email) {
    global $database;
    return (bool) $database->update('PIC', [
        'NAMA'    => $nama,
        'JABATAN' => $jabatan,
        'NOMOR'   => $nomor,
        'EMAIL'   => $email
    ], ['ID' => $id])->rowCount();
}

function deletePic($id) {
    global $database;
    try {
        return (bool) $database->delete('PIC', ['ID' => $id]);
    } catch (Exception $e) {
        return false;
    }
}

function ubahStatus($id, $statusBaru) {
    global $database;
    $database->update("PIC", [
        "STATUS" => $statusBaru
    ], [
        "ID" => $id
    ]);
}

function getDataStatusTruePic() {
    global $database;
    $rows = $database->select("PIC", "*", [
        "STATUS" => 1
    ]);

    $result = [];
    if ($rows) {
        foreach ($rows as $row) {
            $result[] = new Pic($row['ID'], $row['NAMA'], $row['JABATAN'], $row['NOMOR'], $row['EMAIL'], $row['STATUS']);
        }
    }

    return $result;
}




// ---------------------- COMPANY ----------------------

function readCompanies() {
    global $database;
    return $database->get('COMPANY', '*');
}

function readNewCompany() {
    global $database;
    return $database->get('company', '*', [
    "ORDER" => ["id" => "DESC"]
]);
}

function readCompanyById($id) {
    global $database;
    $row = $database->get('COMPANY', '*', ['ID' => $id]);

    if($row){
        return new Company(
        $row['ID'], 
        $row['NAMA_PERUSAHAAN'],
        null, 
        $row['ALAMAT'],
        $row['KODE_POS'],
        $row['KOTA'],
        $row['PROVINSI'],
        $row['NEGARA'],
        $row['TELEPON'],
        $row['EMAIL']);
    } else {
        return null;
    }
}

function searchCompanies($query) {
    global $database;
    return $database->select('COMPANY', '*', [
        'OR' => [
            'NAMA_PERUSAHAAN[~]' => $query,
            'KOTA[~]'            => $query,
            'PROVINSI[~]'        => $query,
            'NEGARA[~]'          => $query
        ]
    ]);
}

function updateCompany($id, $nama_perusahaan, $alamat, $kota, $provinsi, $kode_pos, $negara, $telepon, $email) {
    global $database;
    return (bool) $database->update('COMPANY', [
        'NAMA_PERUSAHAAN' => $nama_perusahaan,
        'ALAMAT'          => $alamat,
        'KOTA'            => $kota,
        'PROVINSI'        => $provinsi,
        'KODE_POS'        => $kode_pos,
        'NEGARA'          => $negara,
        'TELEPON'         => $telepon,
        'EMAIL'         => $email
    ], ['ID' => $id])->rowCount();
}

function deleteCompany($id) {
    global $database;
    try {
        return (bool) $database->delete('COMPANY', ['ID' => $id]);
    } catch (Exception $e) {
        return false;
    }
}


// ---------------------- Payment ----------------------

function createPayment($kode, $nominal, $idInvoice, $tanggal = null, $notes = null) {
    global $database;

    $data = [
        'KODE'        => $kode,
        'NOMINAL'     => $nominal,
        'ID_INVOICE'  => $idInvoice,
        'DATE'        => $tanggal ?? date('Y-m-d'),  // pakai tanggal sekarang jika null
        'NOTES'       => $notes
    ];
    
    return (bool) $database->insert('payment', $data);
}

function readLastPayment() {
    global $database;
    $query = "SELECT * FROM payment ORDER BY ID DESC LIMIT 1";
    $row = $database->query($query)->fetch();
    
    if($row){
        return new Payment(
            $row['ID'],
            $row['DATE'],
            $row['NOMINAL'],
            $row['ID_INVOICE'],
            $row['NOTES'],
            $row['KODE']
        );
    } else {
        return null;
    }
}

function readPayments() {
    global $database;
    return $database->select('payment', '*');
}

function readNewPayment() {
    global $database;
    return $database->get('payment', '*', [
    "ORDER" => ["id" => "DESC"]
]);
}

function readPaymentById($id) {
    global $database;
    $row = $database->get('payment', '*', ['ID' => $id]);
    if ($row) {
        return new Payment(
            $row['ID'],
            $row['DATE'],
            $row['NOMINAL'],
            $row['ID_INVOICE'],
            $row['NOTES'],
            $row['KODE']
        );
    }
    return null;
}

function readPaymentByInvoice($idInvoice) {
    global $database;
    return $database->select('payment', '*', ['ID_INVOICE' => $idInvoice]);
}

function readPaymentByRangeDate($startDate, $endDate) {
    global $database;
    return $database->select('payment', '*', [
        'DATE[<>]' => [$startDate, $endDate]
    ]);
}

function updatePayment($id,$kode, $nominal, $idInvoice, $tanggal = null, $notes = null) {
    global $database;
    $data = [
        'KODE'        => $kode,
        'NOMINAL'     => $nominal,
        'ID_INVOICE'  => $idInvoice,
        'DATE'        => $tanggal ?? date('Y-m-d'),
        'NOTES'       => $notes
    ];
    return (bool) $database->update('payment', $data, ['ID' => $id])->rowCount();
}

function deletePayment($id) {
    global $database;
    try {
        return (bool) $database->delete('payment', ['ID' => $id]);
    } catch (Exception $e) {
        return false;
    }
}

function searchPayments($startDate = null, $endDate = null, $keyword = '') {
    global $database;

    // Base SQL
    $sql = "
        SELECT payment.*
        FROM payment
        LEFT JOIN invoice ON payment.ID_INVOICE = invoice.ID
        WHERE 1 = 1
    ";

    $params = [];

    // Filter tanggal
    if (!empty($startDate) && !empty($endDate)) {
        $sql .= " AND payment.DATE BETWEEN :start AND :end";
        $params[':start'] = $startDate;
        $params[':end'] = $endDate;
    } elseif (!empty($startDate)) {
        $sql .= " AND payment.DATE >= :start";
        $params[':start'] = $startDate;
    } elseif (!empty($endDate)) {
        $sql .= " AND payment.DATE <= :end";
        $params[':end'] = $endDate;
    }

    // Filter keyword (pada NOMINAL atau invoice.KODE)
    if (!empty($keyword)) {
        $sql .= " AND (
            payment.NOMINAL LIKE :kw OR
            invoice.KODE LIKE :kw
        )";
        $params[':kw'] = "%$keyword%";
    }

    // Eksekusi query
    $stmt = $database->query($sql, $params);
    return $stmt->fetchAll();
}



// --------------------- Omzet ---------------------------

function omzetDay() {
    global $database;
    
    
    $tahun = (int)2025; // pastikan integer

$query = "
    SELECT 
    DATE(i.DATE) AS tgl,
    SUM(iv.TOTAL) AS total_omzet,
    GROUP_CONCAT(DISTINCT i.KODE ORDER BY i.KODE SEPARATOR ', ') AS daftar_kode_invoice
FROM 
    invoice i
JOIN 
    iteminv iv ON i.ID = iv.INVOICE_ID
WHERE 
    YEAR(i.DATE) = $tahun
GROUP BY 
    tgl
ORDER BY 
    tgl;";


$results = $database->query($query)->fetchAll();


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

    return $results;
}

function omzetMonth() {
    global $database;
    
    
    $tahun = (int)2025; // pastikan integer

$query = "
    SELECT 
    MONTH(i.DATE) AS tgl,
    SUM(iv.TOTAL) AS total_omzet,
    GROUP_CONCAT(DISTINCT i.KODE ORDER BY i.KODE SEPARATOR ', ') AS daftar_kode_invoice
FROM 
    invoice i
JOIN 
    iteminv iv ON i.ID = iv.INVOICE_ID
WHERE 
    YEAR(i.DATE) = $tahun
GROUP BY 
    tgl
ORDER BY 
    tgl";


$results = $database->query($query)->fetchAll();


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

    return $results;
}

use Medoo\Medoo; 
function omzetWeek() {
    global $database;
    
    
    $tahun = (int)2025; // pastikan integer

$query = "
    SELECT 
    WEEK(i.DATE, 1) AS tgl,
    SUM(iv.TOTAL) AS total_omzet,
    GROUP_CONCAT(DISTINCT i.KODE ORDER BY i.KODE SEPARATOR ', ') AS kode_invoice
FROM 
    invoice i
JOIN 
    iteminv iv ON i.ID = iv.INVOICE_ID
WHERE 
    YEAR(i.DATE) = $tahun
GROUP BY 
    tgl
ORDER BY 
    tgl;

";

$results = $database->query($query)->fetchAll();


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

    return $results;
}


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

function readNewItemInv() {
    global $database;
    $rows = $database->get('iteminv', '*', [
    "ORDER" => ["id" => "DESC"]
]);

    return $rows;
}

function itemBestSeller(){
    global $database;
    $query = "SELECT 
    ITEM_ID AS item_id, 
    SUM(QTY) AS total_qty
FROM 
    iteminv
GROUP BY 
    ITEM_ID
ORDER BY 
    total_qty DESC
LIMIT 10;
";

$row = $database->query($query)->fetchAll();
return $row;
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
function createInvoice($customerId, $tanggal, $kode, $deadline,$notes= '') {
    global $database;
    $data = [
        'CUSTOMER_ID' => $customerId,
        'DATE'        => $tanggal,
        'KODE'        => $kode,
        'NOTES'       => $notes,
        'DEADLINE'       => $deadline
    ];
    return (bool) $database->insert('invoice', $data);
}

function invoiceGrandTotalById($id){
    global $database;
    $query = "SELECT SUM(TOTAL) AS grand_total
FROM iteminv
WHERE INVOICE_ID = $id;
";

$row = $database->query($query)->fetch();
return $row['grand_total'];
}

function invoiceGrandTotal(){
    global $database;
    $query = "SELECT INVOICE_ID, SUM(TOTAL) AS grand_total
FROM iteminv
GROUP BY INVOICE_ID;
";

$row = $database->query($query)->fetchAll();
if($row){
return $row;
} else {
    return null;
}
}

function invoiceStatusById($id){
    global $database;
    $query = "SELECT 
    i.ID AS invoice_id,
    IFNULL(SUM(ii.TOTAL), 0) AS grand_total,
    IFNULL(SUM(p.NOMINAL), 0) AS total_payment,
    CASE 
        WHEN IFNULL(SUM(p.NOMINAL), 0) >= IFNULL(SUM(ii.TOTAL), 0) THEN 'Lunas'
        ELSE 'Belum Lunas'
    END AS status
FROM invoice i
LEFT JOIN iteminv ii ON ii.INVOICE_ID = i.ID
LEFT JOIN payment p ON p.ID_INVOICE = i.ID
WHERE i.ID = $id
GROUP BY i.ID;

";

$row = $database->query($query)->fetch();
if($row){
return $row;
} else {
    return null;
}
}

function invoiceTersisa($id){
    global $database;
    $query = "SELECT 
    IFNULL(SUM(ii.TOTAL), 0) AS grand_total,
    IFNULL(SUM(p.NOMINAL), 0) AS total_payment
FROM invoice i
LEFT JOIN iteminv ii ON ii.INVOICE_ID = i.ID
LEFT JOIN payment p ON p.ID_INVOICE = i.ID
WHERE i.ID = $id
GROUP BY i.ID;

";

$row = $database->query($query)->fetch();
if($row){
return $row;
} else {
    return null;
}
}

function invoiceTersisaByCustomer($customer){
    global $database;
    $query = "SELECT
  IFNULL((
    SELECT SUM(ii.TOTAL)
    FROM invoice i2
    JOIN iteminv ii ON ii.INVOICE_ID = i2.ID
    WHERE i2.CUSTOMER_ID = $customer AND i2.DEADLINE < CURDATE()
  ), 0) AS grand_total,

  IFNULL((
    SELECT SUM(p.NOMINAL)
    FROM invoice i3
    JOIN payment p ON p.ID_INVOICE = i3.ID
    WHERE i3.CUSTOMER_ID = $customer AND i3.DEADLINE < CURDATE()
  ), 0) AS total_payment;

";

$row = $database->query($query)->fetch();
return $row;

}

function readInvoices() {
    global $database;
    $row= [];
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
    // var_dump($rows[101]);die();
    return $rows;
}

function readNewInvoice() {
    global $database;
    $rows = $database->get('invoice', '*', [
    "ORDER" => ["id" => "DESC"]
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
    // var_dump($rows[101]);die();
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
            $row['CUSTOMER_ID'],
            $row['DEADLINE'],
            $row['NOTES'],
            
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
            $row['CUSTOMER_ID'],
            $row['DEADLINE'],
            $row['NOTES'],
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

function invoiceGetIdCustomerDeadline() {    
    global $database;
    $query = "SELECT DISTINCT i.CUSTOMER_ID
FROM invoice i
LEFT JOIN iteminv ii ON ii.INVOICE_ID = i.ID
LEFT JOIN payment p ON p.ID_INVOICE = i.ID
WHERE i.DEADLINE < CURRENT_DATE()
GROUP BY i.ID
HAVING (IFNULL(SUM(ii.TOTAL), 0) - IFNULL(SUM(p.NOMINAL), 0)) > 0;

";

$row = $database->query($query)->fetchAll();
return $row;
}

function invoiceDeadlineByCustomer($customer) {    
    global $database;
    $query = "SELECT *
FROM invoice
WHERE DEADLINE < CURDATE()
AND CUSTOMER_ID = $customer;
";

$row = $database->query($query)->fetchAll();
return $row;
}
function invoiceDeadline() {    
    global $database;
    $query = "SELECT * FROM invoice
WHERE DEADLINE < CURDATE();
";

$row = $database->query($query)->fetchAll();
return $row;
}

function updateInvoice($id, $customerId, $tanggal, $kode,$deadline, $notes='') {
    global $database;
    $data = [
        'CUSTOMER_ID' => $customerId,
        'DATE'        => $tanggal,
        'KODE'        => $kode,
        'NOTES'       => $notes,
        'DEADLINE'       => $deadline
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

// ---------------------- Customers ----------------------
function createCustomer($ref_no, $name, $alamat, $email, $telepon) {
    global $database;
    return (bool) $database->insert('customers', [
        'REF_NO' => $ref_no,
        'NAME'   => $name,
        'EMAIL' => $email,
        'TELEPON'   => $telepon,
        'ALAMAT'   => $alamat
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

function readNewCustomer() {
    global $database;
    $rows = $database->get('customers', '*', [
    "ORDER" => ["id" => "DESC"]
]);
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
            $row['REF_NO'],
            $row['EMAIL'],
            $row['ALAMAT'],
            $row['TELEPON']
        );
    }
    return null;
}

function updateCustomer($id, $ref_no, $name, $alamat, $email, $telepon) {
    global $database;
    return (bool) $database->update('customers', [
        'REF_NO' => $ref_no,
        'NAME'   => $name,
        'EMAIL' => $email,
        'TELEPON'   => $telepon,
        'ALAMAT'   => $alamat
    ], ['ID' => $id])->rowCount();
}

function readCustomerByRefNo($refNo) {
    global $database;
    $row = $database->get('customers', '*', ['REF_NO' => $refNo]);
    if ($row) {
        return new Customer($row['ID'], $row['NAME'], $row['REF_NO'],$row['EMAIL'],
            $row['ALAMAT'],
            $row['TELEPON']);
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

function readNewSupplier() {
    global $database;
    $rows = $database->get('suppliers', '*', [
    "ORDER" => ["id" => "DESC"]
]);

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
function readNewItem() {
    global $database;
    $rows = $database->get('items', '*', [
    "ORDER" => ["id" => "DESC"]
]);
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

function readNewItemCustomer() {
    global $database;
    $rows = $database->get('items_customers', '*', [
    "ORDER" => ["id" => "DESC"]
]);
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
