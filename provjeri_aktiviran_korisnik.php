<?php

$korIme = $_POST["korime"];
$aktiviran = "";
require_once("dohvati_virt_vrijeme.php");
require_once("baza_class.php");
$bp = new Baza();
$sql1 = "SELECT aktiviran FROM korisnici " .
        "WHERE korisnicko_ime = '" . $korIme . "'";
$sql2 = "INSERT INTO dnevnik VALUES (DEFAULT, " .
        "'prijava', 'neuspješna', '" . date("Y-m-d H:i:s", strval(intval(time()) + pomak())) .
        "', '" . $korIme . "')";
$bp->spojiDB();
$rs = $bp->selectDB($sql1);
if ($bp->pogreskaDB()) {
    exit;
}
while (list($aktiviran_korisnik) = $rs->fetch_array()) {
    if($aktiviran_korisnik === "DA") {
        $aktiviran = "aktiviran";
    }
}
$rs->close();
if($aktiviran === "") {
    $bp->updateDB($sql2);
    if ($bp->pogreskaDB()) {
        exit;
    }
}
$bp->zatvoriDB();
echo json_encode($aktiviran);

?>