<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../Repository/repository.php';
require_once __DIR__ . '/../Models/Customer.php';
require_once __DIR__ . '/../Models/Item.php';
require_once __DIR__ . '/../Models/Supplier.php';
require_once __DIR__ . '/../Models/ItemCustomer.php';
require_once __DIR__ . '/../Models/Invoice.php';
require_once __DIR__ . '/../Models/ItemInv.php';
require_once __DIR__ . '/../env.php';


if (!defined('BASE_URL')) {
    // $script_name = $_SERVER['SCRIPT_NAME'];
    // $project_folder = explode("/", trim($script_name, "/"))[0];

    define("BASE_URL", $project_folder);
}

$method = $_SERVER['REQUEST_METHOD'];
$type   = $_GET['type'] ?? null;
$action = $_GET['action'] ?? null;
$id     = $_GET['id'] ?? null;

if (!function_exists('setAlert')) {
    function setAlert($type, $message, $isDelete = false) {
        $_SESSION[$isDelete ? 'alert_delete' : 'alert'] = [
            'type' => $type,
            'message' => $message
        ];
    }
}

if (!function_exists('createRefNo')) {
    function createRefNo($name, $type): string {
        $prefix = strtoupper($name[0]); // Ambil huruf pertama nama
        $angka = 1; // Mulai angka dari 1
    
        while (true) {
            // Tentukan ref_no berdasarkan angka yang sedang diproses
            if ($angka < 10) {
                $ref_no = $prefix . "00" . $angka;
            } elseif ($angka < 100) {   
                $ref_no = $prefix . "0" . $angka;
            } else {
                $ref_no = $prefix . $angka;
            }
    
            // Cek apakah ref_no ini sudah dipakai customer
            if ($type === 'customer') {
                $data = readCustomerByRef_No($ref_no);
            } elseif ($type === 'supplier') {
                $data = readSupplierByRef_No($ref_no);
            } elseif ($type === 'item') {
                $data = readItemByRef_No($ref_no);
            }

            if (!$data) {
                break; // Jika ref_no belum digunakan, keluar dari loop
            }

            $angka++; // Jika sudah digunakan, coba angka berikutnya
        }
    
        return $ref_no;
    }
}

// === POST METHOD ===
if ($method === 'POST') {

    $name = $_POST['name'] ?? NULL;
    $price = $_POST['price'] ?? NULL;
    $id = $_POST['id'] ?? NULL;

    if ($type === 'itemcustomer') {
        if (!$_POST['ref_no']) {
            $ref_no = createRefNo($_POST['name'], $type);
        } else {
            $ref_no = $_POST['ref_no'];
        }
        if ($action === 'create') {
            
                createItemCustomer($_POST['item_id'], $_POST['customer_id'], $_POST['price']);
                setAlert('success', 'Item Customer berhasil ditambahkan!');
                header("Location: ../pages/html/tableItemCustomers.php");
                exit();
            
        } else if ($action === 'update') {
            if (updateItemCustomer($_POST['id'], $_POST['item_id'], $_POST['customer_id'], $_POST['price'])) {
                setAlert('success', 'Item Customer berhasil diperbarui!');
                header("Location: ../pages/html/tableItemCustomers.php");
                exit();
            } else {
                setAlert('danger', 'Gagal memperbarui item customer.');
                header("Location: ../pages/html/editItemCustomers.php?name=$name&ref_no=$ref_no&price=$price&id=$id");
                exit();
            }
        }
    }
    // CUSTOMER
    else if ($type === 'customer') {
        if (!$_POST['ref_no']) {
            $ref_no = createRefNo($_POST['name'], $type);
        } else {
            $ref_no = $_POST['ref_no'];
        }
        if ($action === 'create') {
            if (readCustomerByRef_No($ref_no)) {
                setAlert('danger', 'Gagal menambahkan customer. Ref No sudah digunakan.');
                header("Location: ../pages/html/inputCustomers.php?name=$name&ref_no=$ref_no");
                exit();
            } else {
                createCustomer($ref_no, $_POST['name']);
                setAlert('success', 'Customer berhasil ditambahkan!');
                header("Location: ../pages/html/tableCustomers.php");
                exit();
            }
        } else if ($action === 'update') {
            if (updateCustomer($_POST['id'], $ref_no, $_POST['name'])) {
                setAlert('success', 'Customer berhasil diperbarui!');
                header("Location: ../pages/html/tableCustomers.php");
                exit();
            } else {
                setAlert('danger', 'Gagal memperbarui customer.');
                header("Location: ../pages/html/editCustomers.php?name=$name&ref_no=$ref_no&id=$id");
                exit();
            }
        }
    // SUPPLIER
    } else if ($type === 'supplier') {
        if (!$_POST['ref_no']) {
            $ref_no = createRefNo($_POST['name'], $type);
        } else {
            $ref_no = $_POST['ref_no'];
        }
        if ($action === 'create') {
            if (readSupplierByRef_No($ref_no)) {
                setAlert('danger', 'Gagal menambahkan supplier. Ref No sudah digunakan.');
                header("Location: ../pages/html/inputSuppliers.php?name=$name&ref_no=$ref_no");
                exit();
            } else {
                createSupplier($ref_no, $_POST['name']);
                setAlert('success', 'Supplier berhasil ditambahkan!');
                header("Location: ../pages/html/tableSuppliers.php");
                exit();
            }
        } else if ($action === 'update') {
            if (updateSupplier($_POST['id'], $ref_no, $_POST['name'])) {
                setAlert('success', 'Supplier berhasil diperbarui!');
                header("Location: ../pages/html/tableSuppliers.php");
                exit();
            } else {
                setAlert('danger', 'Gagal memperbarui supplier.');
                header("Location: ../pages/html/editSuppliers.php?name=$name&ref_no=$ref_no&id=$id");
                exit();
            }
        }
    // ITEM
    } else if ($type === 'item') {
        if (!$_POST['ref_no']) {
            $ref_no = createRefNo($_POST['name'], $type);
        } else {
            $ref_no = $_POST['ref_no'];
        }
        if ($action === 'create') {
            if (readItemByRef_No($ref_no)) {
                setAlert('danger', 'Gagal menambahkan item. Ref No sudah digunakan.');
                header("Location: ../pages/html/inputItems.php?name=$name&ref_no=$ref_no&price=$price");
                exit();
            } else {
                createItem($ref_no, $_POST['name'], $_POST['price']);
                setAlert('success', 'Item berhasil ditambahkan!');
                header("Location: ../pages/html/tableItems.php");
                exit();
            }
        } else if ($action === 'update') {
            if (updateItem($_POST['id'], $ref_no, $_POST['name'], $_POST['price'])) {
                setAlert('success', 'Item berhasil diperbarui!');
                header("Location: ../pages/html/tableItems.php");
                exit();
            } else {
                setAlert('danger', 'Gagal memperbarui item.');
                header("Location: ../pages/html/editItems.php?name=$name&ref_no=$ref_no&price=$price&id=$id");
                exit();
            }
        }
    } // INVOICE
 else if ($type === 'invoice') {
    $date = $_POST['date'] ?? NULL;
    $customer_id = $_POST['customer_id'] ?? NULL;
    $kode = $_POST['kode'] ?? 0;

    if ($action === 'create') {
        if(!readInvoiceByKode($kode)) {
            createInvoice($customer_id, $date, $kode);setAlert('success', 'invoice berhasil ditambahkan!');
            header("Location: ../pages/html/tableInvoice.php");
        exit();
            
        } else {
            setAlert('danger', 'Gagal menambahkan invoice.');
            header("Location: ../pages/html/inputInvoices.php?date=$date&customer_id=$customer_id&kode=$kode");
            exit();
        }

        
    } else if ($action === 'update') {
        $id= $_GET['id'];
        // var_dump(readInvoiceByKode($kode), $id);die();
        if(!readInvoiceByKode($kode) || readInvoiceByKode($kode)->getId() == $id) {
            updateInvoice($_GET['id'], $customer_id, $date, $kode);
            setAlert('success', 'Invoice berhasil diperbarui!');
            header("Location: ../pages/html/tableInvoice.php");
            exit();
        } else {
            setAlert('danger', 'Gagal memperbarui invoice.');
            header("Location: ../pages/html/editInvoices.php?date=$date&customer_id=$customer_id&kode=$kode&id=$id");
            exit();
        }
    }
    } 
    
    

// ITEMINV
 else if ($type === 'iteminv') {
    $item_id = $_POST['item_id'] ?? NULL;
    $invoice_id = $_POST['invoice_id'] ?? NULL;
    $qty = $_POST['qty'] ?? 0;
    $price = $_POST['price'] ?? null;

    // var_dump($_GET['id'],$invoice_id, $item_id, $qty, $price);die();

    if ($action === 'create') {
        if(!$price){
            $price = readItemById($item_id)->getPrice();
        }
        createItemInv($invoiceId, $item_id , $qty, $price);
        setAlert('success', 'Item dalam Invoice berhasil ditambahkan!');
        header("Location: ../pages/html/tableItemInv.php?invoice=$invoice_id");
        exit();
    } else if ($action === 'update') {
        if (updateItemInv($_GET['id'],$invoice_id, $item_id, $qty, $price)) {
            setAlert('success', 'Item dalam Invoice berhasil diperbarui!');
            header("Location: ../pages/html/tableItemInv.php?invoice=$invoice_id");
            exit();
        } else {
            setAlert('danger', 'Gagal memperbarui item invoice.');
            header("Location: ../pages/html/editItemInvs.php?item_id=$item_id&invoice_id=$invoice_id&qty=$qty&price=$price&id=$id");
            exit();
        }
    }

    else {
        echo "Invalid action.";
    }
}

} else if ($method === 'GET') {

    // DELETE & READ ACTIONS
    if ($action === 'delete') {
        $success = false;
        $redirectUrl = '';

        if ($type === 'customer') {
            $success = deleteCustomer($id);
            $redirectUrl = '../html/tableCustomers.php';
        } else if ($type === 'supplier') {
            $success = deleteSupplier($id);
            $redirectUrl = '../html/tableSuppliers.php';
        } else if ($type === 'item') {
            $success = deleteItem($id);
            $redirectUrl = '../html/tableItems.php';
        } else if ($type === 'itemcustomer') {
            $success = deleteItemCustomer($id);
            $redirectUrl = '../html/tableItemCustomers.php';
        } } else if ($type === 'invoice') {
            $success = deleteInvoice($id);
            $redirectUrl = '../html/tableInvoices.php';
        } else if ($type === 'iteminv') {
            $success = deleteItemInv($id);
            $redirectUrl = '../html/tableItemInvs.php';
        

        if ($success) {
            setAlert('success', ucfirst($type) . ' berhasil dihapus!', true);
        } else {
            setAlert('danger', 'Gagal menghapus ' . $type . '.', true);
        }

        header("Location: $redirectUrl");
        exit();

    } else if ($action === 'read') {
        if ($type === 'customer') {
            return $id ? readCustomerById($id) : readCustomers();
        } else if ($type === 'supplier') {
            return $id ? readSupplierById($id) : readSuppliers();
        } else if ($type === 'item') {
            return $id ? readItemById($id) : readItems();
        } else if ($type === 'itemcustomer') {
            return $id ? readItemCustomerById($id) : readItemCustomers();
        } } else if ($type === 'invoice') {
            return $id ? readInvoiceById($id) : readInvoices();
        } else if ($type === 'iteminv') {
            return $id ? readItemInvById($id) : readItemInvs();
        } else {
            return "Invalid type.";
        }

    } else if ($action === 'search') {
        $query = $_GET['query'] ?? '';
        $results = [];

        if ($type === 'customer') {
            $redirectUrl = '../html/tableCustomers.php';
            $results = searchCustomers($query);  // Menambahkan fungsi search untuk customers
        } else if ($type === 'supplier') {
            $redirectUrl = '../html/tableSuppliers.php';
            $results = searchSuppliers($query);  // Menambahkan fungsi search untuk suppliers
        } else if ($type === 'item') {
            $redirectUrl = '../html/tableItems.php';
            $results = searchItems($query);  // Menambahkan fungsi search untuk items
        } else if ($type === 'itemcustomer') {
            $redirectUrl = '../html/tableItemCustomers.php';
            $results = searchItemCustomers($query);  // Fungsi search untuk item customer
        } else if ($type === 'invoice') {
            $redirectUrl = '../html/tableInvoices.php';
            $results = searchInvoices($query);
        } else if ($type === 'iteminv') {
            $redirectUrl = '../html/tableItemInvs.php';
            $results = searchItemInvs($query);
        

        header("Location: $redirectUrl");
        exit();

    } else {
        return "Invalid action.";
    }

} else {
    echo "Invalid request method.";
}
