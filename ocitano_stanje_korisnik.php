<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

$rezervacija = $_GET["rezervacija"];
$id = preg_replace("/[^0-9]/", "", $rezervacija);
require_once("baza_class.php");
$bp = new Baza();
$sql = "SELECT ime, prezime FROM korisnici WHERE korisnicko_ime = " .
        "(SELECT korisnici_korisnicko_ime FROM rezervacija WHERE id = " . $id . ")";
$bp->spojiDB();
$rs = $bp->selectDB($sql);
if ($bp->pogreskaDB()) {
    exit;
}
while (list($ime, $prezime) = $rs->fetch_array()) {
    $ime_prezime = $ime . " " . $prezime;
}
$rs->close();
if(isset($_SESSION["prijava"])) {
    $korIme = $_SESSION["prijava"][0];
    require_once("dohvati_virt_vrijeme.php");
    $sql2 = "INSERT INTO dnevnik VALUES (DEFAULT, " .
            "'upit', '" . $sql . "', '" .
            date("Y-m-d H:i:s", strval(intval(time()) + pomak())) .
            "', '" . $korIme . "')";
    $bp->updateDB($sql2);
    if ($bp->pogreskaDB()) {
        exit;
    }
}
$bp->zatvoriDB();

echo json_encode($ime_prezime);

?>