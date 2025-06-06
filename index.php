<?php
include_once 'Control/urlController.php';
sessionSetObjectCustomers(readCustomers());
sessionSetObjectItems(readItems());
sessionSetObjectSuppliers(readSuppliers());
sessionSetObjectInvoices(readInvoices());
sessionSetObjectPayments(readPayments());
sessionSetObjectItemCustomers(readItemCustomers());
sessionSetObjectPices(readPics());
header("Location: home.php");
?>