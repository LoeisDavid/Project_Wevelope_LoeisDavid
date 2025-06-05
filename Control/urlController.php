<?php
include_once 'Control.php';
//--------------------------------------------------
    function getUrlControl($var = ''){
        global $base_url;
        return $base_url . 'Control/Control.php?' . $var;
    }
    function getUrlInputCustomer($var = ''){
        global $base_url;
        return $base_url . 'pages/html/inputCustomers.php?' . $var;
    }

    function getUrlInputInvoices($var = ''){
        global $base_url;
        return $base_url . 'pages/html/inputInvoices.php?' . $var;
    }

    function getUrlInputItemCustomers($var = ''){
        global $base_url;
        return $base_url . 'pages/html/inputItemCustomers.php?' . $var;
    }

    function getUrlInputItemInv($var = ''){
        global $base_url;
        return $base_url . 'pages/html/inputItemInv.php?' . $var;
    }

    function getUrlInputItems($var = ''){
        global $base_url;
        return $base_url . 'pages/html/inputItems.php?' . $var;
    }

    function getUrlInputPayment($var = ''){
        global $base_url;
        return $base_url . 'pages/html/inputPayment.php?' . $var;
    }

    function getUrlInputSettingCompany($var = ''){
        global $base_url;
        return $base_url . 'pages/html/inputSettingPayment.php?' . $var;
    }

    function getUrlInputSettingPic($var = ''){
        global $base_url;
        return $base_url . 'pages/html/inputSettingPic.php?' . $var;
    }

    function getUrlOmzet($var = ''){
        global $base_url;
        return $base_url . 'pages/html/omzet.php?' . $var;
    }

        function getUrlPrintKwitansi($var = ''){
        global $base_url;
        return $base_url . 'pages/html/printKwitansi.php?' . $var;
    }

    function getUrlPrintInvoice($var = ''){
        global $base_url;
        return $base_url . 'pages/html/printInvoice.php?' . $var;
    }

    function getUrlSettingCompany($var = ''){
        global $base_url;
        return $base_url . 'pages/html/settingCompany.php?' . $var;
    }

    function getUrlSettingPic($var = ''){
        global $base_url;
        return $base_url . 'pages/html/settingPic.php?' . $var;
    }

    function getUrlTableBestSeller($var = ''){
        global $base_url;
        return $base_url . 'pages/html/tableBestSeller.php?' . $var;
    }

    function getUrlTableCustomers($var = ''){
        global $base_url;
        return $base_url . 'pages/html/tableCustomers.php?' . $var;
    }

    function getUrlTableDeadline($var = ''){
        global $base_url;
        return $base_url . 'pages/html/tableDeadline.php?' . $var;
    }

    function getUrlDetailCustomer($var = ''){
        global $base_url;
        return $base_url . 'pages/html/tableDetailCustomer.php?' . $var;
    }

    function getUrlTableInvoice($var = ''){
        global $base_url;
        return $base_url . 'pages/html/tableInvoice.php?' . $var;
    }
    function getUrlTableItemCustomers($var = ''){
        global $base_url;
        return $base_url . 'pages/html/tableItemCustomers.php?' . $var;
    }

    function getUrlTableItemInv($var = ''){
        global $base_url;
        return $base_url . 'pages/html/tableItemInv.php?' . $var;
    }

    function getUrlTableItems($var = ''){
        global $base_url;
        return $base_url . 'pages/html/tableItems.php?' . $var;
    }

    function getUrlTablePayments($var = ''){
        global $base_url;
        return $base_url . 'pages/html/tablePayments.php?' . $var;
    }

    function getUrlTableSuppliers($var = ''){
        global $base_url;
        return $base_url . 'pages/html/tableSuppliers.php?' . $var;
    }

    function getUrlInputSuppliers($var = ''){
        global $base_url;
        return $base_url . 'pages/html/inputSuppliers.php?' . $var;
    }

?>