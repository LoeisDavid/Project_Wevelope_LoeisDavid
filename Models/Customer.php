<?php

class Customer {
    private int $id;
    private string $name;
    private string $ref_no;
    private $email;
    private $alamat;
    private $telepon;

    public function __construct(int $id = 0, string $name = "", string $ref_no = "", $email ="", $alamat = "", $telepon = "") {
        $this->id = $id;
        $this->name = $name;
        $this->ref_no = $ref_no;
        $this->email = $email;
        $this->alamat = $alamat;
         $this->telepon = $telepon;
    }

    // Getters
    function getEmail(){
        return $this->email;
    }
    function getTelepon(){
        return $this->telepon;
    }

    function getAlamat(){
        return $this->alamat;
    }
    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getRefNo(): string { return $this->ref_no; }

    // Setters
    public function setId(int $id): void { $this->id = $id; }
    public function setName(string $name): void { $this->name = $name; }
    public function setRefNo(string $ref_no): void { $this->ref_no = $ref_no; }

}