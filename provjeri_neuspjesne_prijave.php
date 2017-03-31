<?php

$korIme = $_POST["korime"];
$status = "";
require_once("dohvati_virt_vrijeme.php");
require_once("baza_class.php");
$bp = new Baza();
$sql1 = "SELECT br_neuspj_prijava FROM korisnici " .
        "WHERE korisnicko_ime = '" . $korIme . "'";
$sql2 = "INSERT INTO dnevnik VALUES (DEFAULT, " .
        "'prijava', 'neuspješna', '" . date("Y-m-d H:i:s", strval(intval(time()) + pomak())) .
        "', '" . $korIme . "')";
$bp->spojiDB();
$rs = $bp->selectDB($sql1);
if ($bp->pogreskaDB()) {
    exit;
}
while (list($neuspj_prijave) = $rs->fetch_array()) {
    if($neuspj_prijave <= 3) {
        $status = "status";
    }
}
$rs->close();
if($status === "") {
    $bp->updateDB($sql2);
    if ($bp->pogreskaDB()) {
        exit;
    }
}
$bp->zatvoriDB();
echo json_encode($status);

?>