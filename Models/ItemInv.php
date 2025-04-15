<?php

class ItemInv {
    private $id;
    private $invoiceId;
    private $itemId;
    private $qty;
    private $price;
    private $total;

    // Constructor
    public function __construct($id, $invoiceId, $itemId, $qty, $price, $total) {
        $this->id = $id;
        $this->invoiceId = $invoiceId;
        $this->itemId = $itemId;
        $this->qty = $qty;
        $this->price = $price;
        $this->total = $total;
    }

    // Getter and Setter for ID
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    // Getter and Setter for Invoice ID
    public function getInvoiceId() {
        return $this->invoiceId;
    }

    public function setInvoiceId($invoiceId) {
        $this->invoiceId = $invoiceId;
    }

    // Getter and Setter for Item ID
    public function getItemId() {
        return $this->itemId;
    }

    public function setItemId($itemId) {
        $this->itemId = $itemId;
    }

    // Getter and Setter for Quantity
    public function getQty() {
        return $this->qty;
    }

    public function setQty($qty) {
        $this->qty = $qty;
    }

    // Getter and Setter for Price
    public function getPrice() {
        return $this->price;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    // Getter and Setter for Total
    public function getTotal() {
        return $this->total;
    }

    public function setTotal($total) {
        $this->total = $total;
    }
}

?>
