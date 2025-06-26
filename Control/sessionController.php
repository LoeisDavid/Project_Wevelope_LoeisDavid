<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!(sessionGetObjectCustomers() || sessionGetObjectItems() || sessionGetObjectSuppliers() || sessionGetObjectInvoices() || sessionGetObjectPayments() | sessionGetObjectItemCustomers() ||sessionGetObjectPices())){
    readAllObject();
}

function readAllObject(){
    sessionSetObjectCustomers(readCustomers());
sessionSetObjectItems(readItems());
sessionSetObjectSuppliers(readSuppliers());
sessionSetObjectInvoices(readInvoices());
sessionSetObjectPayments(readPayments());
sessionSetObjectItemCustomers(readItemCustomers());
sessionSetObjectPices(readPics());
sessionSetObjectTruePices(getDataStatusTruePic());
$company = readCompanies();
$pic = getDataStatusTruePic();

// var_dump($company);die();
$id = $company['ID'] ?? null;
$nama = $company['NAMA_PERUSAHAAN'] ?? "";
$pic = $pic->getNama() ?? "";
$alamat = $company['ALAMAT'] ?? "";
$kota = $company['KOTA'] ?? '';
$provinsi = $company['PROVINSI'] ?? '';
$kodePos = $company['KODE_POS'] ?? '';
$negara = $company['NEGARA'] ?? '';
$telepon = $company['TELEPON'] ?? '';
$email = $company['EMAIL'] ?? '';
$logo = $company['URLOGO'] ?? '';

$company = new Company($id, $nama, $pic, $alamat, $kodePos,$kota, $provinsi, $negara, $telepon, $email, $logo);
sessionSetObjectCompany($company);
}

if (!function_exists('setAlert')) {
    function setAlert($type, $message) {
        $_SESSION['alert'] = [
            'type' => $type,
            'message' => $message
        ];
    }
}


    function sessionSetId($id){
        $_SESSION['ID'] = $id;
    }

    function sessionGetId(){
        return $_SESSION['ID'] ?? null;
    }

    function sessionSetMethod($method){
        $_SESSION['METHOD'] = $method;
    }

    function sessionGetMethod(){
        return $_SESSION['METHOD'] ?? null;
    }

    function sessionSetObjectSuppliers($suppliers){
        $_SESSION['SUPPLIERS'] = $suppliers;
    }

    function sessionGetObjectSuppliers(){
        return $_SESSION['SUPPLIERS'] ?? null;
    }
    
    function sessionSetObjectCustomers($customers){
        $_SESSION['CUSTOMERS'] = $customers;
    }

    function sessionGetObjectCustomers(){
        return $_SESSION['CUSTOMERS'] ?? null;
    }

    function sessionSetObjectItems($items){
        $_SESSION['ITEMS'] = $items;
    }

    function sessionGetObjectItems(){
        return $_SESSION['ITEMS'] ?? null;
    }

    function sessionSetObjectInvoices($invoices){
        $_SESSION['INVOICES'] = $invoices;
    }

    function sessionGetObjectInvoices(){
        return $_SESSION['INVOICES'] ?? null;
    }

    function sessionSetObjectPayments($payments){
        $_SESSION['PAYMENTS'] = $payments;
    }

    function sessionGetObjectPayments(){
        return $_SESSION['PAYMENTS'] ?? null;
    }

    function sessionSetObjectPices($pic){
        $_SESSION['PIC'] = $pic;
    }

    function sessionGetObjectPices(){
        return $_SESSION['PIC'] ?? null;
    }

    function sessionSetObjectCompany($company){
        $_SESSION['COMPANY'] = $company;
    }

    function sessionGetObjectCompany(){
        return $_SESSION['COMPANY'] ?? null;
    }

    function sessionSetObjectTruePices($pic){
        $_SESSION['TRUE_PIC'] = $pic;
    }

    function sessionGetObjectTruePices(){
        return $_SESSION['TRUE_PIC'] ?? null;
    }

    function sessionSetObjectItemCustomers($itemCustomers){
        $_SESSION['ITEM_CUSTOMERS'] = $itemCustomers;
    }

    function sessionGetObjectItemCustomers(){
        return $_SESSION['ITEM_CUSTOMERS'] ?? null;
    }

    function sessionSetObjectItemInv($itemInv){
        $_SESSION['ITEM_INV'] = $itemInv;
    }

    function sessionGetObjectItemInv(){
        return $_SESSION['ITEM_INV'] ?? null;
    }

    function sessionSetRedirectUrl($url){
        $_SESSION['REDIRECTURL'] = $url;
    }

    function sessionGetRedirectUrl(){
        return $_SESSION['REDIRECTURL'] ?? null;
    }

    function sessionSetRedirectUrl2($url){
        $_SESSION['REDIRECTURL2'] = $url;
    }

    function sessionGetRedirectUrl2(){
        return $_SESSION['REDIRECTURL2'] ?? null;
    }

    //================ pass variabel ================//

    function sessionSetPass($var, $nameSession){
        $_SESSION[$nameSession] = $var;
    }

    function sessionGetPass($nameSession){
        return $_SESSION[$nameSession] ?? null;
    }

    //================ Boolean Call Object ====================//
    function sessionSetCall($name, $var){
        $_SESSION['CALL'. $name] = $var;
    }

    function sessionGetCall($name){
        return $_SESSION['CALL'. $name] ?? null;
    }

    //----------------- CSV --------------------

function exportSessionCustomersToCsv()
{
    global $database;

    // Cegah warning muncul sebagai output ke CSV
    error_reporting(0); // Nonaktifkan warning, atau gunakan ini di awal script
    ini_set('display_errors', 0);

    $customers = sessionGetObjectCustomers();

    if (!$customers || !is_array($customers)) {
        return false;
    }

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="customers_export.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');

    $output = fopen('php://output', 'w');

    // Header kolom
    fputcsv($output, ['REF_NO', 'NAME', 'EMAIL', 'TELEPON', 'ALAMAT'], ',', '"', "\\");

    foreach ($customers as $cust) {
        if (is_object($cust)) {
            fputcsv($output, [
                $cust->getRefNo(),
                $cust->getName(),
                $cust->getEmail(),
                $cust->getTelepon(),
                $cust->getAlamat()
            ], ',', '"', "\\");
        } elseif (is_array($cust)) {
            fputcsv($output, [
                $cust['REF_NO'] ?? '',
                $cust['NAME'] ?? '',
                $cust['EMAIL'] ?? '',
                $cust['TELEPON'] ?? '',
                $cust['ALAMAT'] ?? ''
            ], ',', '"', "\\");
        }
    }

    fclose($output);
    exit;
}


?>