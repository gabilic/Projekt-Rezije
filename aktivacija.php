<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

function provjeriKod() {
    $aktivacijski_kod = $_GET["kod"];
    $postoji = false;
    require_once("baza_class.php");
    $bp = new Baza();
    $sql = "SELECT aktivacijski_kod FROM korisnici " .
            "WHERE aktivacijski_kod = '" . $aktivacijski_kod . "'";
    $bp->spojiDB();
    $rs = $bp->selectDB($sql);
    if ($bp->pogreskaDB()) {
        exit;
    }
    while (list($kod) = $rs->fetch_array()) {
        if($kod === $aktivacijski_kod) {
            $postoji = true;
        }
    }
    $rs->close();
    if(isset($_SESSION["prijava"])) {
        $korIme = $_SESSION["prijava"][0];
        require_once("dohvati_virt_vrijeme.php");
        $sql2 = "INSERT INTO dnevnik VALUES (DEFAULT, " .
                "'upit', '" . str_replace("'", "''", $sql) . "', '" .
                date("Y-m-d H:i:s", strval(intval(time()) + pomak())) .
                "', '" . $korIme . "')";
        $bp->updateDB($sql2);
        if ($bp->pogreskaDB()) {
            exit;
        }
    }
    $bp->zatvoriDB();
    return $postoji;
}

function provjeriVrijeme() {
    $aktivacijski_kod = $_GET["kod"];
    $validan = false;
    require_once("baza_class.php");
    $bp = new Baza();
    $sql = "SELECT dat_i_vrij_registracije FROM korisnici " .
            "WHERE aktivacijski_kod = '" . $aktivacijski_kod . "'";
    $bp->spojiDB();
    $rs = $bp->selectDB($sql);
    if ($bp->pogreskaDB()) {
        exit;
    }
    require_once("dohvati_virt_vrijeme.php");
    while (list($datum_i_vrijeme) = $rs->fetch_array()) {
        if(((intval(time()) + pomak()) - intval(strtotime($datum_i_vrijeme))) <= 43200) {
            $validan = true;
        }
    }
    $rs->close();
    if(isset($_SESSION["prijava"])) {
        $korIme = $_SESSION["prijava"][0];
        require_once("dohvati_virt_vrijeme.php");
        $sql2 = "INSERT INTO dnevnik VALUES (DEFAULT, " .
                "'upit', '" . str_replace("'", "''", $sql) . "', '" .
                date("Y-m-d H:i:s", strval(intval(time()) + pomak())) .
                "', '" . $korIme . "')";
        $bp->updateDB($sql2);
        if ($bp->pogreskaDB()) {
            exit;
        }
    }
    $bp->zatvoriDB();
    return $validan;
}

function aktivirajKorisnika() {
    $aktivacijski_kod = $_GET["kod"];
    require_once("baza_class.php");
    $bp = new Baza();
    $sql = "UPDATE korisnici SET aktiviran = 'DA' " .
            "WHERE aktivacijski_kod = '" . $aktivacijski_kod . "'";
    $bp->spojiDB();
    $bp->updateDB($sql);
    if ($bp->pogreskaDB()) {
        exit;
    }
    if(isset($_SESSION["prijava"])) {
        $korIme = $_SESSION["prijava"][0];
        require_once("dohvati_virt_vrijeme.php");
        $sql2 = "INSERT INTO dnevnik VALUES (DEFAULT, " .
                "'upit', '" . str_replace("'", "''", $sql) . "', '" .
                date("Y-m-d H:i:s", strval(intval(time()) + pomak())) .
                "', '" . $korIme . "')";
        $bp->updateDB($sql2);
        if ($bp->pogreskaDB()) {
            exit;
        }
    }
    $bp->zatvoriDB();
}

$provjera = true;
$poruka = "";

if(!provjeriKod()) {
    $provjera = false;
}
else if(!provjeriVrijeme()) {
    $provjera = false;
}
else {
    aktivirajKorisnika();
}
if($provjera) {
    $poruka = "Uspješno ste aktivirali svoj korisnički račun.";
}
else {
    $poruka = "Pojavio se problem prilikom aktivacije. Korisnički račun nije aktiviran!";
}

$naslov = "Aktivacija";
$template = "aktivacija";
include "smarty.php";

?>