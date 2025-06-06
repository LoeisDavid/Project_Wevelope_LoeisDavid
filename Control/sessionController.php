<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
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
        return $_SESSION['ID'];
    }

    function sessionSetMethod($method){
        $_SESSION['METHOD'] = $method;
    }

    function sessionGetMethod(){
        return $_SESSION['METHOD'];
    }

    function sessionSetObjectSuppliers($suppliers){
        $_SESSION['SUPPLIERS'] = $suppliers;
    }

    function sessionGetObjectSuppliers(){
        return $_SESSION['SUPPLIERS'];
    }
    
    function sessionSetObjectCustomers($customers){
        $_SESSION['CUSTOMERS'] = $customers;
    }

    function sessionGetObjectCustomers(){
        return $_SESSION['CUSTOMERS'];
    }

    function sessionSetObjectItems($items){
        $_SESSION['ITEMS'] = $items;
    }

    function sessionGetObjectItems(){
        return $_SESSION['ITEMS'];
    }

    function sessionSetObjectInvoices($invoices){
        $_SESSION['INVOICES'] = $invoices;
    }

    function sessionGetObjectInvoices(){
        return $_SESSION['INVOICES'];
    }

    function sessionSetObjectPayments($payments){
        $_SESSION['PAYMENTS'] = $payments;
    }

    function sessionGetObjectPayments(){
        return $_SESSION['PAYMENTS'];
    }

    function sessionSetObjectPices($pic){
        $_SESSION['PIC'] = $pic;
    }

    function sessionGetObjectPices(){
        return $_SESSION['PIC'];
    }

    function sessionSetObjectItemCustomers($itemCustomers){
        $_SESSION['ITEM_CUSTOMERS'] = $itemCustomers;
    }

    function sessionGetObjectItemCustomers(){
        return $_SESSION['ITEM_CUSTOMERS'];
    }

    function sessionSetObjectItemInv($itemInv){
        $_SESSION['ITEM_INV'] = $itemInv;
    }

    function sessionGetObjectItemInv(){
        return $_SESSION['ITEM_INV'];
    }
?>