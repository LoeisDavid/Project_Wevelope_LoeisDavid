<?php

class Payment {

private $id;
private $date;
private $nominal;
private $invoice;
private $notes;

public function __construct($id, $date, $nominal, $invoice, $notes){
    $this->id = $id;
$this->date = $date;
$this->nominal = $nominal;
$this->invoice = $invoice;
$this->notes = $notes;
}

public function getNotes(){
    return $this->notes;
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