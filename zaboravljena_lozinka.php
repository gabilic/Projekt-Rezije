<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

function velikoSlovo($lozinka) {
    $brojac = 0;
    $znak = 0;
    for($i = 0; $i < strlen($lozinka); $i++) {
        $znak = $lozinka[$i];
        if($znak >= 'A' && $znak <= 'Z') {
            $brojac++;
        }
    }
    return $brojac;
}

function maloSlovo($lozinka) {
    $brojac = 0;
    $znak = 0;
    for($i = 0; $i < strlen($lozinka); $i++) {
        $znak = $lozinka[$i];
        if($znak >= 'a' && $znak <= 'z') {
            $brojac++;
        }
    }
    return $brojac;
}

function broj($lozinka) {
    $brojac = 0;
    $znak = 0;
    for($i = 0; $i < strlen($lozinka); $i++) {
        $znak = $lozinka[$i];
        if($znak >= '0' && $znak <= '9') {
            $brojac++;
        }
    }
    return $brojac;
}

function generator() {
    $opcija = rand(1, 3);
    $znak = "";
    switch($opcija) {
        case 1: $znak = chr(rand(65, 90)); break;
        case 2: $znak = chr(rand(97, 122)); break;
        case 3: $znak = chr(rand(48, 57)); break;
    }
    return $znak;
}

function generirajLozinku() {
    $duljina = rand(8, 16);
    $lozinka = "";
    $pogreska = false;
    for($i = 0; $i < $duljina; $i++) {
        $lozinka .= generator();
    }
    if(velikoSlovo($lozinka) < 1 || maloSlovo($lozinka) < 1 || broj($lozinka) < 1) {
        $pogreska = true;
    }
    if($pogreska) {
        generirajLozinku();
    }
    else {
        $korime = $_GET["korime"];
        require_once("dohvati_virt_vrijeme.php");
        require_once("baza_class.php");
        $bp = new Baza();
        $sql1 = "SELECT email FROM korisnici " .
                "WHERE korisnicko_ime = '" . $korime . "'";
        $sql2 = "UPDATE korisnici SET lozinka = '" . $lozinka . "' " .
                "WHERE korisnicko_ime = '" . $korime . "'";
        $sql3 = "INSERT INTO dnevnik VALUES (DEFAULT, " .
                "'zahtjev', 'zaboravljena lozinka', '" .
                date("Y-m-d H:i:s", strval(intval(time()) + pomak())) .
                "', '" . $korime . "')";
        $bp->spojiDB();
        $rs = $bp->selectDB($sql1);
        if ($bp->pogreskaDB()) {
            exit;
        }
        while (list($mail) = $rs->fetch_array()) {
            $email = $mail;
        }
        $rs->close();
        $bp->updateDB($sql2);
        if ($bp->pogreskaDB()) {
            exit;
        }
        $bp->updateDB($sql3);
        if ($bp->pogreskaDB()) {
            exit;
        }
        $bp->zatvoriDB();
        mail($email, "Nova lozinka", "Vaša nova lozinka je: " . $lozinka);
    }
}

generirajLozinku();
$poruka = "Nova lozinka je poslana na vašu e-mail adresu.";

$naslov = "Zaboravljena lozinka";
$template = "zaboravljena lozinka";
include "smarty.php";

?>