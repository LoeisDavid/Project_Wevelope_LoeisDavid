<?php

class ItemCustomer {
    private $id;
    private $item;
    private $customer;
    private $harga;

    // Constructor
    public function __construct($id, $item, $customer, $harga) {
        $this->id = $id;
        $this->item = $item;
        $this->customer = $customer;
        $this->harga = $harga;
    }

    // Getter and Setter for $id
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    // Getter and Setter for $item
    public function getItem() {
        return $this->item;
    }

    public function setItem($item) {
        $this->item = $item;
    }

    // Getter and Setter for $customer
    public function getCustomer() {
        return $this->customer;
    }

    public function setCustomer($customer) {
        $this->customer = $customer;
    }

    // Getter and Setter for $harga
    public function getHarga() {
        return $this->harga;
    }

    public function setHarga($harga) {
        $this->harga = $harga;
    }
}

?>