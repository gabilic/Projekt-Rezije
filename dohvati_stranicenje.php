<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

require_once("dohvati_virt_vrijeme.php");
require_once("baza_class.php");
$bp = new Baza();
$sql = "SELECT stranicenje FROM konfiguracija";
$bp->spojiDB();
$rs = $bp->selectDB($sql);
if ($bp->pogreskaDB()) {
    exit;
}
while (list($broj_redaka) = $rs->fetch_array()) {
    $stranicenje = $broj_redaka;
}
$rs->close();
if(isset($_SESSION["prijava"])) {
    $korIme = $_SESSION["prijava"][0];
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

echo json_encode($stranicenje);

?>