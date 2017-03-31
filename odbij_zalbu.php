<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

$status = "uspjeh";
$zalba = $_GET["zalba"];
$id = preg_replace("/[^0-9]/", "", $zalba);
require_once("baza_class.php");
$bp = new Baza();
$sql1 = "UPDATE zalba SET status = 'NE' " .
        "WHERE id = " . $id;
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