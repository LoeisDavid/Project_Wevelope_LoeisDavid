<?php

class Pic{

    private $id;
    private $nama;
    private $jabatan;
    private $nomor;
    private $email;
    private $status;
    function __construct($id, $nama, $jabatan, $nomor, $email, $status){
        $this->id= $id;
        $this->nama = $nama;
        $this->jabatan = $jabatan;
        $this->nomor = $nomor;
        $this->email = $email;
        $this->status = $status;
    }

    function getStatus(){
        return $this->status;
    }
    function getId(){
        return $this->id;
    }

    function getNama(){
        return $this->nama;
    }

    function getJabatan(){
        return $this->jabatan;
    }

    function getNomor(){
        return $this->nomor;
    }

    function getEmail(){
        return $this->email;
    }

}


?>