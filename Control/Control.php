<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../Repository/repository.php';
require_once __DIR__ . '/../Models/Customer.php';
require_once __DIR__ . '/../Models/Item.php';
require_once __DIR__ . '/../Models/Supplier.php';


if (!defined('BASE_URL')) {
    $script_name = $_SERVER['SCRIPT_NAME'];
    $project_folder = explode("/", trim($script_name, "/"))[0];
    define("BASE_URL", "/" . $project_folder);
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

            // Debugging - log ref_no yang sedang dicoba
            echo "Mencoba ref_no: $ref_no<br>";
            
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


    if(!$_POST['ref_no']){
        $ref_no = createRefNo($_POST['name'],$type);
    } else {
        $ref_no=$_POST['ref_no'];
    }

    // CUSTOMER
    if ($type === 'customer') {

        
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
        if ($action === 'create') {
            if (readItemByRef_No($ref_no)) {
                setAlert('danger', 'Gagal menambahkan item. Ref No sudah digunakan.');
                header("Location: ../pages/html/inputItems.php?name=$name&r=ref_no=$ref_no&price=$price");
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

    } else {
        echo "Invalid action.";
    }

// === GET METHOD ===
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
        }

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
        } else {
            return "Invalid type.";
        }

    } else {
        return "Invalid action.";
    }

} else {
    echo "Invalid request method.";
}

