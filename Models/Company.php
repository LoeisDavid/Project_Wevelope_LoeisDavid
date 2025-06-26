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
    private $telepon;
    private $email;
    private $urlLogo;
    
    function __construct($id, $nama, $pic, $alamat, $kodePos, $kota, $provinsi, $negara, $telepon, $email, $urlLogo){
        $this->id = $id;
        $this->nama = $nama;
        $this->pic = $pic;
        $this->alamat = $alamat;
        $this->kodePos = $kodePos;
        $this->kota = $kota;
        $this->provinsi = $provinsi;
        $this->negara = $negara;
        $this->telepon = $telepon;
        $this->email = $email;
        $this->urlLogo = $urlLogo;
}

function getUrlLogo(){
    return $this->urlLogo;
}
function setUrlLogo($urlLogo){
    $this->urlLogo = $urlLogo;
}
function getEmail(){
    return $this->email;
}
function getTelepon(){
    return $this->telepon;
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