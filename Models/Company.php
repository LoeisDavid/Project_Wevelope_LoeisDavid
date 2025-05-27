<?php

class Company {

    private $nama;
    private $id;
    private $pic;
    private $alamat;
    private $kota;
    private $provinsi;
    private $kodePos;
    private $negara;
    
    function __construct($id, $nama, $pic, $alamat, $kodePos, $kota, $provinsi, $negara){
        $this->id = $id;
        $this->nama = $nama;
        $this->pic = $pic;
        $this->alamat = $alamat;
        $this->kodePos = $kodePos;
        $this->kota = $kota;
        $this->provinsi = $provinsi;
        $this->negara = $negara;
}

function getId(){
    return $this->id;
}

function getNama(){
    return $this->nama;
}

function getPic(){
    return $this->pic;
}

function getAlamat(){
    return $this->alamat;
}

function getKodePos(){
    return $this->kodePos;
}

function getKota(){
    return $this->kota;
}

function getProvinsi(){
    return $this->provinsi;
}

function getNegara(){
    return $this->negara;
}

}


?>