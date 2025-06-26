<?php
include_once '../../Control/urlController.php';

$aksi = $_POST['aksi'];

if($aksi == 'download'){
generatePDF(__DIR__ . '/printInvoice.php', 'Invoice_L001'); 
} else{
    generatePDF(__DIR__ . '/printInvoice.php', 'Invoice_L001', false); 
}

?>