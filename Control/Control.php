<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

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
require_once __DIR__ . '/../Models/Payment.php';
require_once __DIR__ . '/../Models/Company.php';
require_once __DIR__ . '/../Models/Pic.php';
require_once __DIR__ . '/../env.php';

// if (!isset($_SESSION['executed'])) {
//     $_SESSION['executed'] = true; // Tandai bahwa kode sudah dijalankan

//     $base_url = ""
// }
// if ($base_url == ""){
//     $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
//         $host = $_SERVER['HTTP_HOST'];
//         $scriptName = $_SERVER['SCRIPT_NAME']; // /nsms/folder lain/nama_project/index.php
//         $pathParts = explode('/', trim($scriptName, '/'));
//         $base_url= "";
//         global $name_project;
    
//         $kondisi=false;
//         for( $i = count($pathParts)-1; $i >= 0; $i-- ) {
            
//             if( $pathParts[$i] == 'index.php' && $i !=0) {
//                 $i--;
//                 $kondisi=true;
//             }
    
//             if($kondisi) {
//                 $base_url = $pathParts[$i] . "/" .$base_url;
//             }
//         }

//         $base_url = '/../'.$base_url;
// }

if (!defined('BASE_URL')) {
    // $script_name = $_SERVER['SCRIPT_NAME'];
    // $project_folder = explode("/", trim($script_name, "/"))[0];

    define("BASE_URL", "/".$base_url);
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
                $data = readCustomerByRefNo($ref_no);
            } elseif ($type === 'supplier') {
                $data = readSupplierByRefNo($ref_no);
            } elseif ($type === 'item') {
                $data = readItemByRefNo($ref_no);
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
    if($id){
        $action = 'update';
    } else {
        $action = 'create';
    }

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
                header("Location: ../pages/html/InputItemCustomers.php?id=$id");
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
            if (readCustomerByRefNo($ref_no)) {
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
            if(readCustomerById(intval($_POST['id']))->getRefNo() == $ref_no) {
                updateCustomer($_POST['id'], $ref_no, $_POST['name']);
                setAlert('success', 'Customer berhasil diperbarui!');
                header("Location: ../pages/html/tableCustomers.php");
                exit();
            } else {
                if (readCustomerByRefNo($ref_no)) {
                    setAlert('danger', 'Gagal memperbarui customer. Ref No sudah digunakan');
                header("Location: ../pages/html/InputCustomers.php?id=$id");
                exit();
                } else {
                    updateCustomer($_POST['id'], $ref_no, $_POST['name']);
                setAlert('success', 'Customer berhasil diperbarui!');
                header("Location: ../pages/html/tableCustomers.php");
                exit();
                }
                
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
            if (readSupplierByRefNo($ref_no)) {
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
            if(readSupplierById(intval($_POST['id']))->getRefNo() == $ref_no) {
                updateSupplier($_POST['id'], $ref_no, $_POST['name']);
                setAlert('success', 'Supplier berhasil diperbarui!');
                header("Location: ../pages/html/tableSuppliers.php");
                exit();
            } else {
                if (!readSupplierByRefNo($ref_no)) {
                    updateSupplier($_POST['id'], $ref_no, $_POST['name']);
                    setAlert('success', 'Supplier berhasil diperbarui!');
                    header("Location: ../pages/html/tableSuppliers.php");
                    exit();
                } else {
                    setAlert('danger', 'Gagal memperbarui supplier. Ref No sudah digunakan.');
                header("Location: ../pages/html/InputSuppliers.php?id=$id");
                exit();
                }
                
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
            if (readItemByRefNo($ref_no)) {
                setAlert('danger', 'Gagal menambahkan item. Ref No sudah digunakan.');
                
            } else {
                createItem($ref_no, $_POST['name'], $_POST['price']);
                setAlert('success', 'Item berhasil ditambahkan!');
                header("Location: ../html/tableItems.php");
                exit();
            }
        } else if ($action === 'update') {

            if(readItemById(intval($_POST['id']))->getRefNo() == $ref_no) {
                updateItem($_POST['id'], $ref_no, $_POST['name'], $_POST['price']);
                setAlert('success', 'Item berhasil diperbarui!');
                header("Location: ../html/tableItems.php");
                exit();
            } else {
                if (!readItemByRefNo($ref_no)) {
                    updateItem($_POST['id'], $ref_no, $_POST['name'], $_POST['price']);
                    setAlert('success', 'Item berhasil diperbarui!');
                header("Location: ../html/tableItems.php");
                exit();
                } else {
                    setAlert('danger', 'Gagal memperbarui item. Ref No sudah digunakan.');
                header("Location: ../html/inputItems.php?id=$id");
                exit();
                }
                
            }
        }
    } // INVOICE
 else if ($type === 'invoice') {
    $date = $_POST['tanggal'] ?? NULL;
    $customer_id = $_POST['customer_id'] ?? NULL;
    $kode = $_POST['kode'] ?? 0;
    $notes = $_POST['notes'] ?? '';
    $deadline = $_POST['deadline'] ?? NULL;

    $kondisi = $_GET['kondisi'] ?? NULL;
    $id = $_POST['id'];

    if ($action === 'create') {
        if(!readInvoiceByKode($kode)) {
            createInvoice($customer_id, $date, $kode, $deadline,$notes);setAlert('success', 'invoice berhasil ditambahkan!');
            header("Location: ../pages/html/tableInvoice.php?invoice=$id");
        exit();
            
        } else {
            setAlert('danger', 'Gagal menambahkan invoice.');
            header("Location: ../pages/html/inputInvoices.php?id=$id");
            exit();
        }

        
    } else if ($action === 'update') {
        $id= $_POST['id'];
        // var_dump(readInvoiceByKode($kode), $id);die();
        if(!readInvoiceByKode($kode) || readInvoiceByKode($kode)->getId() == $id) {
            updateInvoice($id, $customer_id, $date, $kode, $deadline,$notes);
            setAlert('success', 'Invoice berhasil diperbarui!');

            if($kondisi){
                header("Location: ../pages/html/tableItemInv.php?invoice=$id");
            exit();
            } else {
                header("Location: ../pages/html/tableItemInv.php?invoice=$id");
            exit();
            }

            
        } else {
            setAlert('danger', 'Gagal memperbarui invoice.');
            header("Location: ../pages/html/InputInvoices.php?id=$id");
            exit();
        }
    }
    } 
    
    

// ITEMINV
 else if ($type === 'iteminv') {
    $item_id = $_POST['item_id'] ?? NULL;
    $invoice_id = $_POST['invoice_id'] ?? NULL;
    $qty = $_POST['qty'] ?? 0;
    $price = $_POST['price'] ?? 0;

    if(!$price){
        $price = readItemById($item_id)->getPrice();
    }

    // var_dump($_GET['id'],$invoice_id, $item_id, $qty, $price);die();

    if ($action === 'create') {
        if(!$price){
            $price = readItemById($item_id)->getPrice();
        }
        createItemInv($invoice_id, $item_id , $qty, $price);
        setAlert('success', 'Item dalam Invoice berhasil ditambahkan!');
        header("Location: ../pages/html/tableItemInv.php?invoice=$invoice_id");
        exit();
    } else if ($action === 'update') {  
        if(!($id && $invoice_id && $item_id && $price && $qty)){
            setAlert('danger', 'tidak ada yang diperbarui!');
            header("Location: ../pages/html/tableItemInv.php?invoice=$invoice_id");
            exit();
        } else {
            if (updateItemInv($id,$invoice_id, $item_id, $qty, $price)) {
            setAlert('success', 'Item dalam Invoice berhasil diperbarui!');
        header("Location: ../pages/html/tableItemInv.php?invoice=$invoice_id");
        exit();
            } else {
                setAlert('danger', 'Gagal memperbarui item invoice.');
                header("Location: ../pages/html/inputItemInv.php?id=$id");
                exit();
            }
        }
    }

    else {
        echo "Invalid action.";
    }
} else if ($type === 'payment') {
    $id = $_POST['id'] ?? null;
    $nominal = $_POST['nominal'] ?? null;
    $invoice = $_POST['invoice_id'] ?? null;
    $tanggal = $_POST['date'] ?? null;
    $notes = $_POST['notes'] ?? '';

    if($nominal <= 0){
        setAlert('danger', 'Nominal tidak boleh 0 atau minus');
                header("Location: ../pages/html/inputPayment.php?invoice=$invoice");
                exit();
    }

    if($tanggal===""){
        $tanggal=null;
    }

    // var_dump($_GET['id'],$invoice_id, $item_id, $qty, $price);die();

    if ($action === 'create') {
        $countainer= invoiceTersisa($invoice); 
        if($nominal+$countainer['total_payment']<=$countainer['grand_total']){
            createPayment($nominal, $invoice , $tanggal, $notes);
        setAlert('success', 'Berhasil melakukan pembayaran!');
        header("Location: ../pages/html/tablePayments.php");
        exit();
        } else {
            setAlert('danger', 'Gagal melakukan payment!');
                header("Location: ../pages/html/inputPayment.php?invoice=$invoice");
                exit();
        }
        
    } else if ($action === 'update') {  
        $countainer= invoiceTersisa($invoice);
        $payment= readPaymentById($id);

        if($countainer['total_payment']-$payment->getNomial()+$nominal<=$countainer['grand_total']){
            if(!($id && $nominal && $invoice)){
            setAlert('danger', 'Payment tidak ditemukan!');
            header("Location: ../pages/html/tablePayments.php");
            exit();
        } else {
            if (updatePayment($id,$nominal, $invoice, $date, $notes)) {
            setAlert('success', 'Payment berhasil diperbarui!');
        header("Location: ../pages/html/tablePayments.php");
        exit();
            } else {
                setAlert('danger', 'Gagal memperbarui payment!');
                header("Location: ../pages/html/inputPayment.php?id=$id");
                exit();
            }
        }
        } else {
            setAlert('danger', 'Gagal memperbarui payment!');
                header("Location: ../pages/html/inputPayment.php?id=$id");
                exit();
        }
        
    }

    else {
        echo "Invalid action.";
    }
} else if ($type === 'company') {
    $id= $_POST['id'] ?? '';
    $namaPerusahaan= $_POST['nama'] ?? '';
    $pic = '';
    $alamat= $_POST['alamat'] ?? '';
    $kota= $_POST['kota'] ?? '';
    $provinsi= $_POST['provinsi'] ?? '';
    $kodePos= $_POST['kodePos'] ?? '';
    $negara= $_POST['negara'] ?? '';

    // var_dump($_GET['id'],$invoice_id, $item_id, $qty, $price);die();

    if (updateCompany($id, $namaPerusahaan, $alamat, $kota, $provinsi, $kodePos, $negara)) {
        setAlert('success', 'Company berhasil diperbarui!');
        header("Location: ../pages/html/settingCompany.php");
        exit();      
    } else {
       setAlert('danger', 'Gagal memperbarui Company.');
        header("Location: ../pages/html/inputSettingCompany.php");
        exit();        
    }
        
    } else if ($type === 'pic') {
    $nama= $_POST['nama'] ?? '';
$jabatan= $_POST['jabatan'] ?? '';
$nomor= $_POST['nomor'] ?? '';
$email= $_POST['email'] ?? '';
$id= $_POST['id'] ?? null;

    // var_dump($_GET['id'],$invoice_id, $item_id, $qty, $price);die();

    if ($action === 'create') {

        if(createPic($nama, $jabatan, $nomor, $email)){
            setAlert('success', 'Berhasil menambahkan PIC!');
            header("Location: ../pages/html/settingPic.php");
            exit();
        } else {
                setAlert('danger', 'Gagal menambahkan PIC!');
                header("Location: ../pages/html/inputSettingPic.php");
                exit();
        }
        
    } else if ($action === 'update') {  
        $pic = readPicById($id);
        if($pic->getEmail()==$email && $pic->getJabatan() == $jabatan && $pic->getNama() == $nama && $pic->getNomor() == $nomor){
            setAlert('danger', 'Tidak ada data yang berubah pada PIC!');
                header("Location: ../pages/html/inputSettingPic.php");
                exit();
        }
        if(updatePic($id, $nama, $jabatan, $nomor, $email)){
            setAlert('success', 'PIC berhasil diperbarui!');
            header("Location: ../pages/html/settingPic.php");
        } else {
                setAlert('danger', 'Gagal memperbarui PIC!');
                header("Location: ../pages/html/inputSettingPic.php");
                exit();
            }
        
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
        } else if ($type === 'invoice') {
            $success = deleteInvoice($id);
            $redirectUrl = '../html/tableInvoice.php';
        } else if ($type === 'iteminv') {
            $type = 'item dalam invoice';
            $success = deleteItemInv($id);
            $redirectUrl = '../pages/html/tableItemInv.php?invoice='. $_GET['invoice'];   
        
        } else if ($type === 'payment') {
            $success = deletePayment(id: $id);
            $redirectUrl = '../html/tablePayments.php';
        
        } else if ($type === 'pic') {
            $success = deletePic(id: $id);
            $redirectUrl = '../html/settingPic.php';
        
        }
        if ($success) {
            setAlert('success', ucfirst($type) . ' berhasil dihapus!', true);
        } else {
            setAlert('danger', 'Gagal menghapus ' . $type . '.', true);
        }

        header("Location: $redirectUrl");
        exit();
    }

     else if ($action === 'read') {
        if ($type === 'customer') {
            return $id ? readCustomerById($id) : readCustomers();
        } else if ($type === 'supplier') {
            return $id ? readSupplierById($id) : readSuppliers();
        } else if ($type === 'item') {
            return $id ? readItemById($id) : readItems();
        } else if ($type === 'itemcustomer') {
            return $id ? readItemCustomerById($id) : readItemCustomers();
        } else if ($type === 'invoice') {
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
            $redirectUrl = '../html/tableInvoice.php';
            $results = searchInvoices($query);
        } else if ($type === 'iteminv') {
            $redirectUrl = '../html/tableItemInv.php';
            $results = searchItemInvsInInvoice($query);
        
        }
        header("Location: $redirectUrl");
        exit();

    } else {
        return "Invalid action.";
    } 
    
    
}
else {
        echo "Invalid request method.";
    }


 
