<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

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
require_once 'sessionController.php';
require_once 'dompdf.php';

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
$suppliers = sessionGetObjectSuppliers();
// var_dump($suppliers[count($suppliers)-1]);die;

if (!defined('BASE_URL')) {
    // $script_name = $_SERVER['SCRIPT_NAME'];
    // $project_folder = explode("/", trim($script_name, "/"))[0];

    define("BASE_URL", "/".$base_url);
}

$method = $_SERVER['REQUEST_METHOD'] ?? $_GET['method'] ?? sessionGetPass('METHOD');
$type   = $_GET['type'] ?? null;
$action = $_GET['action'] ?? null;
$id     = $_GET['id'] ?? null;

//------------------- CSV ----------------

 
    $csv = $_GET['csv'] ?? null;

    if(isset($csv)){
        $aksi = $_GET['action'] ?? null;
        if($csv == 'customer'){

            if($aksi == 'export'){
                exportSessionCustomersToCsv();
                header('Location: ?');
                exit();
            }

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

    
    //------------------- CSV ----------------

 
    $csv = $_POST['csv'] ?? null;
    $redirect = $_GET['redirect'] ?? false;
    $keyword = $_GET['keyword'] ?? null;
    $success = false;
    $url = null;
    $url2 = null;
    if(isset($redirect)){
        $url = sessionGetRedirectUrl();
        $url2 = '?';
    } else {
        $url = sessionGetRedirectUrl();
        $url2 = sessionGetRedirectUrl2();
    }

    $name = $_POST['name'] ?? NULL;
    $price = $_POST['price'] ?? NULL;
    $id = $_POST['id'] ?? NULL;
    $index = sessionGetPass('INDEX') ?? null;
    if(sessionGetPass('FROM')){
        $from = sessionGetPass('FROM') ?? null;
    }
    if($action == 'status'){

    } else {
        if($id){
        $action = 'update';
    } else {
        $action = 'create';
    }
    }
    
    if(isset($csv)){
        $aksi = $_POST['action'] ?? null;
        if($csv == 'customer'){

            if($aksi == 'import'){
                $file = $_FILES['file']['tmp_name'];

                $kondisi = importCsvToCustomers($file);
                
                if($kondisi){
                    setAlert('success', 'CSV Customer berhasil ditambahkan!'); 
                    $success = true;
                    sessionSetObjectCustomers(readCustomers());
                } else {
                    setAlert('danger', 'Gagal menambahkan CSV Customer customer.');
                    $success = false;
                }
            }

        }
    } else if ($type === 'itemcustomer') {
        
        if ($action === 'create') {
            
                createItemCustomer($_POST['item_id'], $_POST['customer_id'], $_POST['price']);
                setAlert('success', 'Item Customer berhasil ditambahkan!'); 
                $itemCustomers = sessionGetObjectItemCustomers();
                $container = 1;
                if(count($itemCustomers)==0){   
                    $container=0;
                }
                if(is_object($itemCustomers[count($itemCustomers)-$container])){
                    $itemCustomer = $itemCustomers[count($itemCustomers)-$container]->getId()+1;
                } else {
                    $itemCustomer = $itemCustomers[count($itemCustomers)-$container]['ID']+1;
                }
                
                $itemCustomers[] = new ItemCustomer($itemCustomer, $_POST['item_id'],$_POST['customer_id'], $_POST['price']);
                sessionSetObjectItemCustomers($itemCustomers);
                $success = true;
            
        } else if ($action === 'update') {
            if (updateItemCustomer($_POST['id'], $_POST['item_id'], $_POST['customer_id'], $_POST['price'])) {
                setAlert('success', 'Item Customer berhasil diperbarui!');

                if(isset($keyword)){
                    sessionSetObjectItemCustomers(readItemCustomers());
                } else {
                    $data = sessionGetObjectItemCustomers();
                $data[$index-1] = new ItemCustomer($_POST['id'], $_POST['item_id'], $_POST['customer_id'], $_POST['price']);
                sessionSetObjectItemCustomers($data);
                }
                
               $success = true;
            } else {
                setAlert('danger', 'Gagal memperbarui item customer.');
                $success = false;
            }
        }
    }
    // CUSTOMER
    else if ($type === 'customer') {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $telepon = $_POST['telepon'] ?? '';
        $alamat = $_POST['alamat'] ?? '';
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
                createCustomer($ref_no, $_POST['name'], $alamat, $email, $telepon);
                setAlert('success', 'Customer berhasil ditambahkan!');
                $customer = sessionGetObjectCustomers();
                $container = 1;
                if(count($customer)==0){
                    $container=0;
                }

                if(is_object($customer[count($customer)-$container]->getId())){
                    $cust = $customer[count($customer)-$container]->getId()+1;
                } else {
                    $cust = $customer[count($customer)-$container]['ID']+1;
                }
                $customer[] = new Customer($cust, $name, $ref_no, $email, $alamat, $telepon);
                sessionSetObjectCustomers($customer);
                $success = true;
            }
        } else if ($action === 'update') {

            if(isset($from)){
                if(readCustomerById(intval($_POST['id']))->getRefNo() == $ref_no) {
                updateCustomer($_POST['id'], $ref_no, $_POST['name'], $alamat, $email, $telepon);
                setAlert('success', 'Customer berhasil diperbarui!');
                sessionSetObjectCustomers(readCustomers());
                $success = true;
            } else {
                if (readCustomerByRefNo($ref_no)) {
                    setAlert('danger', 'Gagal memperbarui customer. Ref No sudah digunakan');
                $success = false;
                } else {
                    updateCustomer($_POST['id'], $ref_no, $_POST['name'], $alamat, $email, $telepon);
                setAlert('success', 'Customer berhasil diperbarui!');
                sessionSetObjectCustomers(readCustomers());
                $success = true;
                }
                
            }
            } else {
            if(readCustomerById(intval($_POST['id']))->getRefNo() == $ref_no) {
                updateCustomer($_POST['id'], $ref_no, $_POST['name'], $alamat, $email, $telepon);
                setAlert('success', 'Customer berhasil diperbarui!');
                if(isset($keyword)){
                    sessionSetObjectCustomers(readCustomers());
                } else {
                    $data = sessionGetObjectCustomers();
                    $data[$index-1] = new Customer($_POST['id'], $_POST['name'],$ref_no, $email, $alamat,  $telepon);
                    sessionSetObjectCustomers($data);
                }
                $success = true;
            } else {
                if (readCustomerByRefNo($ref_no)) {
                    setAlert('danger', 'Gagal memperbarui customer. Ref No sudah digunakan');
                $success = false;
                } else {
                    updateCustomer($_POST['id'], $ref_no, $_POST['name'], $alamat, $email, $telepon);
                setAlert('success', 'Customer berhasil diperbarui!');
                if(isset($keyword)){
                    sessionSetObjectCustomers(readCustomers());
                } else {
                    $data = sessionGetObjectCustomers();
                    $data[$index-1] = new Customer($_POST['id'], $_POST['name'],$ref_no, $email, $alamat,  $telepon);
                    sessionSetObjectCustomers($data);
                }
                $success = true;
                }
                
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
                $suppliers = sessionGetObjectSuppliers();
                $container = 1;
                if(count($suppliers)==0){
                    $container=0;
                }
                $supp = $suppliers[count($suppliers)-$container];
                if(is_object($supp)){
                    $supp = $supp->getId();
                } else 
            {
                $supp = $supp['ID'];
            }
                
                $suppliers[] = new Supplier($supp+1, $_POST['name'], $ref_no);
                sessionSetObjectSuppliers($suppliers);
                $success = true;
            }
        } else if ($action === 'update') {
            if(readSupplierById(intval($_POST['id']))->getRefNo() == $ref_no) {
                updateSupplier($_POST['id'], $ref_no, $_POST['name']);
                setAlert('success', 'Supplier berhasil diperbarui!');
                if(isset($keyword)){
                    sessionSetObjectSuppliers(readSuppliers());
                } else {
                    $data = sessionGetObjectSuppliers();
                $data[$index-1] = new Supplier($_POST['id'], $_POST['name'],$ref_no );
                sessionSetObjectSuppliers($data);
                }
                
                $success = true;
            } else {
                if (!readSupplierByRefNo($ref_no)) {
                    updateSupplier($_POST['id'], $ref_no, $_POST['name']);
                    setAlert('success', 'Supplier berhasil diperbarui!');
                    if(isset($keyword)){
                    sessionSetObjectSuppliers(readSuppliers());
                } else {
                    $data = sessionGetObjectSuppliers();
                $data[$index-1] = new Supplier($_POST['id'], $_POST['name'],$ref_no );
                sessionSetObjectSuppliers($data);
                }
                } else {
                    setAlert('danger', 'Gagal memperbarui supplier. Ref No sudah digunakan.');
                $success = false;
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
                header("Location: $url2");
                exit();
            } else {
                createItem($ref_no, $_POST['name'], $_POST['price']);
                setAlert('success', 'Item berhasil ditambahkan!');
                $item = sessionGetObjectItems();
                $container = 1;
                if(count($item)==0){
                    $container=0;
                }

                if(is_object($item[count($item)-$container])){
                    $it = $item[count($item)-$container]->getId()+1;
                } else {
                    $it = $item[count($item)-$container]['ID']+1;
                }
                
                $item[] = new Item($it,$_POST['name'], $ref_no, $_POST['price']);
                sessionSetObjectItems($item);
                $success = true;
            }
        } else if ($action === 'update') {

            if(readItemById(intval($_POST['id']))->getRefNo() == $ref_no) {
                updateItem($_POST['id'], $ref_no, $_POST['name'], $_POST['price']);
                    setAlert('success', 'Item berhasil diperbarui!');
                    if(isset($keyword)){
                        sessionSetObjectItems(readItems());
                    } else {
                        $data = sessionGetObjectItems();
                $data[$index-1] = new Item($_POST['id'],  $_POST['name'],$ref_no, $_POST['price']);
                sessionSetObjectItems($data);
                    }
                    
                $success = true;
            } else {
                if (!readItemByRefNo($ref_no)) {
                    updateItem($_POST['id'], $ref_no, $_POST['name'], $_POST['price']);
                    setAlert('success', 'Item berhasil diperbarui!');
                    if(isset($keyword)){
                        sessionSetObjectItems(readItems());
                    } else {
                        $data = sessionGetObjectItems();
                $data[$index-1] = new Item($_POST['id'],  $_POST['name'],$ref_no, $_POST['price']);
                sessionSetObjectItems($data);
                    }
                } else {
                    setAlert('danger', 'Gagal memperbarui item. Ref No sudah digunakan.');
                $success = false;
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
            $invoices = sessionGetObjectInvoices();
            $container = 1;
                if(count($invoices)==0){
                    $container=0;
                }

                if($invoices[count($invoices)-$container]){
                    $inv = $invoices[count($invoices)-$container]->getId()+1;
                } else {
                    $inv = $invoices[count($invoices)-$container]['ID']+1;
                }
            
            $invoices[] = new Invoice($inv, $kode, $date,$customer_id , $deadline,$notes);
            sessionSetObjectInvoices($invoices);
            $success = true;
            
        } else {
            setAlert('danger', 'Gagal menambahkan invoice.');
            $success = false;
        }

        
    } else if ($action === 'update') {
        $id= $_POST['id'];
        if(isset($from)){
            if(!readInvoiceByKode($kode) || readInvoiceByKode($kode)->getId() == $id) {
            updateInvoice($id, $customer_id, $date, $kode, $deadline,$notes);
            setAlert('success', 'Invoice berhasil diperbarui!');
            sessionSetObjectInvoices(readInvoices());
            $success = true;  
        } else {
            setAlert('danger', 'Gagal memperbarui invoice.');
            $success = false;
        }
        } else {
            if(!readInvoiceByKode($kode) || readInvoiceByKode($kode)->getId() == $id) {
            updateInvoice($id, $customer_id, $date, $kode, $deadline,$notes);
            setAlert('success', 'Invoice berhasil diperbarui!');

            if(isset($keyword)){
                sessionSetObjectInvoices(readInvoices());
            } else {
                $data = sessionGetObjectInvoices();
                $data[$index-1] = new Invoice($id, $kode, $date, $customer_id, $deadline, $notes);
                sessionSetObjectInvoices(array_values($data));
            }

            $success = true;  
        } else {
            setAlert('danger', 'Gagal memperbarui invoice.');
            $success = false;
        }
        }
        // var_dump(readInvoiceByKode($kode), $id);die();
        
    }
    } 
    
    

// ITEMINV
 else if ($type === 'iteminv') {
    $item_id = $_POST['item_id'] ?? NULL;
    $invoice_id = $_POST['invoice_id'] ?? NULL;
    $invoice = sessionGetPass('invoice');
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
        $itemInv = sessionGetObjectItemInv();
        $iv = readNewItemInv()['ID']+1;
        $itemInv[] = new ItemInv($iv, $invoice_id, $item_id , (int)$qty, $price, $qty*$price);
        // var_dump($itemInv);die;
        sessionSetObjectItemInv($itemInv);
        $invoice+=1;
        $success = true;
    } else if ($action === 'update') {  
        if(!($id && $invoice_id && $item_id && $price && $qty)){
            setAlert('danger', 'tidak ada yang diperbarui!');
           $success = false;
        } else {
            if (updateItemInv($id,$invoice_id, $item_id, $qty, $price)) {
            setAlert('success', 'Item dalam Invoice berhasil diperbarui!');
            $itemInv = sessionGetObjectItemInv();
        $itemInv[$index-1] = new ItemInv($iv, $invoice_id, $item_id , (int)$qty, $price, $qty*$price);
        // var_dump($itemInv);die;
        sessionSetObjectItemInv($itemInv);
        $invoice+=1;
        $success = true;
            } else {
                setAlert('danger', 'Gagal memperbarui item invoice.');
                $success = false;
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
    $kode = $_POST['kode'] ?? null;

    if(!$kode){
        $lastPayment = readLastPayment();
        $digit=0;
        if($lastPayment){
            $digit = ($lastPayment->getID() + 1);
        } else {
            $digit = 1;
        }

        if($digit%100 != $digit){

        } else if ($digit%10 != $digit){
            $digit = 0 . $digit;
        } else {
            $digit = 00 . $digit;
        }

        $formatted = date("Y/md/", strtotime($tanggal));
        $kode = 'KW/' . $formatted . $digit ;   
        
    }

    if($nominal <= 0){
        setAlert('danger', 'Nominal tidak boleh 0 atau minus');
                $success = false;
    }

    if($tanggal===""){
        $tanggal=null;
    }

    // var_dump($_GET['id'],$invoice_id, $item_id, $qty, $price);die();

    if ($action === 'create') {
        $countainer= invoiceTersisa($invoice); 
        if($nominal+$countainer['total_payment']<=$countainer['grand_total']){
            createPayment($kode, $nominal, $invoice , $tanggal, $notes);
        setAlert('success', 'Berhasil melakukan pembayaran!');
        $payments = sessionGetObjectPayments();
        $container = 1;
                if(count($payments)==0){
                    $container=0;
                } else {
                    
                }
        $pay = $payments[count($payments)-$container]->getId()+1;
        $payments[] = new Payment($pay, $tanggal, $nominal, $invoice , $notes,$kode);
        sessionSetObjectPayments($payments);
        $success = true;
        } else {
            setAlert('danger', 'Gagal melakukan payment!');
                $success = false;
        }
        
    } else if ($action === 'update') {  
        $countainer= invoiceTersisa($invoice);
        $payment= readPaymentById($id);

        if($countainer['total_payment']-$payment->getNomial()+$nominal<=$countainer['grand_total']){
            if(!($id && $nominal && $invoice)){
            setAlert('danger', 'Payment tidak ditemukan!');
            $success = false;
        } else {
            if (updatePayment($id,$kode,$nominal, $invoice, $date, $notes)) {
            setAlert('success', 'Payment berhasil diperbarui!');
            if(isset($keyword)){
                sessionSetObjectPayments(readPayments());
            } else {
                 $data = sessionGetObjectPayments();
                $data[$index-1] = new Payment($id,$date,$nominal, $invoice,  $notes, $kode);
                sessionSetObjectPayments($data);
            }
            
        $success = true;
            } else {
                setAlert('danger', 'Gagal memperbarui payment!');
                $success = false;
            }
        }
        } else {
            setAlert('danger', 'Gagal memperbarui payment!');
                $success = false;
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
    $telepon= $_POST['telepon'] ?? '';
    $email= $_POST['email'] ?? '';
    $target_dir = "../pages/html/img/";
    $filename = basename($_FILES['gambar']['name']) ?? null;
    $target_file = null;
    if(isset($filename)){
        $target_file = $target_dir . $filename;
    } else {
        $target_file = readCompanyById($id)->getUrlLogo();
    }
    // var_dump($_GET['id'],$invoice_id, $item_id, $qty, $price);die();

    if (updateCompany($id, $namaPerusahaan, $alamat, $kota, $provinsi, $kodePos, $negara, $telepon, $email, $target_file)) {
        move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file);
        setAlert('success', 'Company berhasil diperbarui!');
        $success = true;     
    } else {
       setAlert('danger', 'Gagal memperbarui Company.');
        $success = false;     
    }
        
    } else if ($type === 'pic') {
    $nama= $_POST['nama'] ?? '';
$jabatan= $_POST['jabatan'] ?? '';
$nomor= $_POST['nomor'] ?? '';
$email= $_POST['email'] ?? '';
$id= $_POST['id'] ?? $_GET['id'] ?? null;
$use =  $_GET['use'] ?? null;


    // var_dump($_GET['id'],$invoice_id, $item_id, $qty, $price);die();
    

    if ($action === 'create') {

        if(createPic($nama, $jabatan, $nomor, $email)){
            setAlert('success', 'Berhasil menambahkan PIC!');
            $pics = sessionGetObjectPices();
            $container = 1;
                if(count($pics)==0){
                    $container=0;
                }

                if(is_object($pics[count($pics)-$container])){
                    $pic = $pics[count($pics)-$container]->getId()+1;
                } else {
                    $pic = $pics[count($pics)-$container]['ID']+1;
                }
            
            $pics[] = new Pic($pic, $nama, $jabatan, $nomor, $email, false);
            sessionSetObjectPices($pics);
            $success = true;
        } else {
                setAlert('danger', 'Gagal menambahkan PIC!');
                $success = false;
        }
        
    } else if ($action === 'update') {  

        
        $pic = sessionGetObjectPices()[$index-1];
        if($pic->getEmail()==$email && $pic->getJabatan() == $jabatan && $pic->getNama() == $nama && $pic->getNomor() == $nomor){
            setAlert('danger', 'Tidak ada data yang berubah pada PIC!');
                $success = false;
        }
        if(updatePic($id, $nama, $jabatan, $nomor, $email)){
            
            setAlert('success', 'PIC berhasil diperbarui!');

            if(isset($keyword)){
                sessionSetObjectPices(readPics());
            } else {
                $data = sessionGetObjectPices();
            $status = $data[$index-1]->getStatus();
                $data[$index-1] = new Pic($id, $nama, $jabatan, $nomor, $email, $status);
                sessionSetObjectPices($data);
            }
            
            $success = true;
        } else {
                setAlert('danger', 'Gagal memperbarui PIC!');
                $success = false;
            }
        
    }    
    }

    if(isset($_POST['aksi'])){

    } else 

    if($success){
        sessionSetPass(null, 'ID');
        sessionSetPass(null, 'INDEX');
        header("Location: $url");
        exit();
    } else{
        header("Location: $url2");
        exit();
    }
} else if ($method === 'GET') {

    $index = $_GET['index'] ?? null;
    // DELETE & READ ACTIONS
    if ($action === 'status'){
        $pic = sessionGetObjectTruePices();
        ubahStatus($pic->getId(), !$pic->getStatus());
        ubahStatus($id, 1);
        $pic = sessionGetObjectPices()[$index-1];
        $pic = new Pic($pic->getId(), $pic->getNama(),$pic->getJabatan(),$pic->getNomor(), $pic->getEmail(), !$pic->getStatus());
        sessionSetObjectTruePices($pic);
        setAlert('success', 'Berhasil');
    }
    if ($action === 'delete') {
        $success = false;
        $redirectUrl = '';

        if ($type === 'customer') {
            $success = deleteCustomer($id);
            $redirectUrl = '../html/tableCustomers.php';
            if ($success) {
            $data = sessionGetObjectCustomers();
            unset($data[$index-1]);
            $data = array_values($data);
            sessionSetObjectCustomers($data);
            }
            
        } else if ($type === 'supplier') {
            $success = deleteSupplier($id);
            $redirectUrl = '../html/tableSuppliers.php';
            if ($success) {
           $data = sessionGetObjectSuppliers();
            unset($data[$index-1]);
            $data = array_values($data);
            // var_dump($index);
            sessionSetObjectSuppliers($data);
            }
            
        } else if ($type === 'item') {
            $success = deleteItem($id);
            $redirectUrl = '../html/tableItems.php';
            if ($success) {
            $data = sessionGetObjectItems();
            unset($data[$index-1]);
            $data = array_values($data);
            sessionSetObjectItems($data);
            }
            
        } else if ($type === 'itemcustomer') {
            $success = deleteItemCustomer($id);
            $redirectUrl = '../html/tableItemCustomers.php';
            if ($success) {
$data = sessionGetObjectItemCustomers();
            unset($data[$index-1]);
            $data = array_values($data);
            sessionSetObjectItemCustomers($data);
            }
            
        } else if ($type === 'invoice') {
            $success = deleteInvoice($id);
            $redirectUrl = '../html/tableInvoice.php';
            if ($success) {
            $data = sessionGetObjectInvoices();
            unset($data[$index-1]);
            $data = array_values($data);
            sessionSetObjectInvoices($data);
            }
            
        } else if ($type === 'iteminv') {
            $type = 'item dalam invoice';
            $success = deleteItemInv($id);
            $redirectUrl = '../pages/html/tableItemInv.php?invoice='. $_GET['invoice'];   
            if ($success) {
            $data = sessionGetObjectItemInv();
            unset($data[$index-1]);
            $data = array_values($data);
            sessionSetObjectItemInv($data);
            }
            
        } else if ($type === 'payment') {
            $success = deletePayment(id: $id);
            $redirectUrl = '../html/tablePayments.php';
            if ($success) {
            $data = sessionGetObjectPayments();
            unset($data[$index-1]);
            $data = array_values($data);
            sessionSetObjectPayments($data);
            }
        
        } else if ($type === 'pic') {
            $success = deletePic(id: $id);
            $redirectUrl = '../html/settingPic.php';
            if ($success) {
            $data = sessionGetObjectPices();
            unset($data[$index-1]);
            $data = array_values($data);
            sessionSetObjectPices($data);
            }
            
        
        }
        if ($success) {
            setAlert('success', ucfirst($type) . ' berhasil dihapus!');
        } else {
            setAlert('danger', 'Gagal menghapus ' . $type . '.');
        }

        // readAllObject();
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



    



    