<?php

$korIme = $_POST["korime"];
$lozinka = $_POST["lozinka"];
$tocna = "";
require_once("dohvati_virt_vrijeme.php");
require_once("baza_class.php");
$bp = new Baza();
$sql1 = "SELECT lozinka FROM korisnici " .
        "WHERE korisnicko_ime = '" . $korIme . "'";
$sql2 = "UPDATE korisnici SET br_neuspj_prijava = br_neuspj_prijava + 1 " .
        "WHERE korisnicko_ime = '" . $korIme . "'";
$sql3 = "INSERT INTO dnevnik VALUES (DEFAULT, " .
        "'prijava', 'neuspješna', '" . date("Y-m-d H:i:s", strval(intval(time()) + pomak())) .
        "', '" . $korIme . "')";
$sql4 = "SELECT br_neuspj_prijava FROM korisnici " .
        "WHERE korisnicko_ime = '" . $korIme . "'";
$sql5 = "INSERT INTO dnevnik VALUES (DEFAULT, " .
        "'zaključavanje', 'zaključan korisnički račun', '" .
        date("Y-m-d H:i:s", strval(intval(time()) + pomak())) .
        "', '" . $korIme . "')";
$bp->spojiDB();
$rs1 = $bp->selectDB($sql1);
if ($bp->pogreskaDB()) {
    exit;
}
while (list($lozinka_provjera) = $rs1->fetch_array()) {
    if($lozinka_provjera === $lozinka) {
        $tocna = "točna";
    }
}
$rs1->close();
if($tocna === "") {
    $bp->updateDB($sql2);
    if ($bp->pogreskaDB()) {
        exit;
    }
    $bp->updateDB($sql3);
    if ($bp->pogreskaDB()) {
        exit;
    }
}
$zakljucan = false;
$rs2 = $bp->selectDB($sql4);
if ($bp->pogreskaDB()) {
    exit;
}
while (list($neuspj_prijave) = $rs2->fetch_array()) {
    if(intval($neuspj_prijave) === 4) {
        $zakljucan = true;
    }
}
$rs2->close();
if($zakljucan) {
    $bp->updateDB($sql5);
    if ($bp->pogreskaDB()) {
        exit;
    }
}
$bp->zatvoriDB();
echo json_encode($tocna);

?>