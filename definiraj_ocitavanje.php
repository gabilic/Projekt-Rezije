<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

$status = "uspjeh";
$kategorija = $_GET["kategorija"];
$cijena = $_GET["cijena"];
$ustanova = $_GET["ustanova"];
require_once("baza_class.php");
$bp = new Baza();
$sql1 = "INSERT INTO ocitavanje VALUES (DEFAULT, '" .
        $kategorija . "', '" . $cijena . "', 0, " .
        "(SELECT id FROM ustanova WHERE naziv = '" . $ustanova . "'))";
$bp->spojiDB();
$bp->updateDB($sql1);
if ($bp->pogreskaDB()) {
    $status = "neuspjeh";
    exit;
}
if(isset($_SESSION["prijava"])) {
    $korIme = $_SESSION["prijava"][0];
    require_once("dohvati_virt_vrijeme.php");
    $sql2 = "INSERT INTO dnevnik VALUES (DEFAULT, " .
            "'upit', '" . str_replace("'", "''", $sql1) . "', '" .
            date("Y-m-d H:i:s", strval(intval(time()) + pomak())) .
            "', '" . $korIme . "')";
    $bp->updateDB($sql2);
    if ($bp->pogreskaDB()) {
        exit;
    }
}
$bp->zatvoriDB();

echo json_encode($status);

?>