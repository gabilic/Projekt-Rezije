<?php

$korIme = $_POST["korime"];
$zauzeto = "";
require_once("baza_class.php");
$bp = new Baza();
$sql = "SELECT korisnicko_ime FROM korisnici " .
        "WHERE korisnicko_ime = '" . $korIme . "'";
$bp->spojiDB();
$rs = $bp->selectDB($sql);
if ($bp->pogreskaDB()) {
    exit;
}
while (list($ime) = $rs->fetch_array()) {
    if($ime === $korIme) {
        $zauzeto = "zauzeto";
    }
}
$rs->close();
$bp->zatvoriDB();
echo json_encode($zauzeto);

?>