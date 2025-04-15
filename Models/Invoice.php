<?php

class Invoice {
    private $id;
    private $kode;  // Mengganti refNo dengan kode
    private $date;
    private $customerId;

    // Constructor
    public function __construct($id, $kode, $date, $customerId) {
        $this->id = $id;
        $this->kode = $kode;  // Menginisialisasi kode
        $this->date = $date;
        $this->customerId = $customerId;
    }

    // Getter and Setter for ID
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    // Getter and Setter for Kode (mengganti refNo)
    public function getKode() {
        return $this->kode;
    }

    public function setKode($kode) {
        $this->kode = $kode;
    }

    // Getter and Setter for Date
    public function getDate() {
        return $this->date;
    }

    public function setDate($date) {
        $this->date = $date;
    }

    // Getter and Setter for Customer ID
    public function getCustomerId() {
        return $this->customerId;
    }

    public function setCustomerId($customerId) {
        $this->customerId = $customerId;
    }
}

?>
