<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

$opcija = $_GET["opcija"];
require_once("baza_class.php");
$bp = new Baza();
if($opcija === "otkljucavanje") {
    $sql = "SELECT korisnicko_ime FROM korisnici " .
            "WHERE br_neuspj_prijava >= 4";
}
else {
    $sql = "SELECT korisnicko_ime FROM korisnici " .
            "WHERE br_neuspj_prijava < 4";
}
$bp->spojiDB();
$rs = $bp->selectDB($sql);
if ($bp->pogreskaDB()) {
    exit;
}
$rezultat = "[";
while (list($korime) = $rs->fetch_array()) {
    $lista[] = '{"ime":"' . $korime . '"}';
}
$rezultat .= join(",", $lista);
$rezultat .= "]";
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

echo $rezultat;

?>