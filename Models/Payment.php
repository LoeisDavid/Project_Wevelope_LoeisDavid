<?php

class Payment {

private $id;
private $date;
private $nominal;
private $invoice;

public function __construct($id, $date, $nominal, $invoice){
    $this->id = $id;
$this->date = $date;
$this->nominal = $nominal;
$this->invoice = $invoice;
}

public function getId(){
    return $this->id;
}

public function getDate(){
    return $this->date;
}

public function getNomial(){
    return $this->nominal;
}

public function getInvoice(){
    return $this->invoice;
}


    
}

?>