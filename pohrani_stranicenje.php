<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

$stranicenje = $_GET["stranicenje"];
$status = "uspjeh";
require_once("baza_class.php");
$bp = new Baza();
$sql1 = "SELECT COUNT(*) FROM konfiguracija";
$sql2 = "INSERT INTO konfiguracija VALUES (" . $stranicenje . ")";
$sql3 = "UPDATE konfiguracija SET stranicenje = " . $stranicenje;
$bp->spojiDB();
$rs = $bp->selectDB($sql1);
if ($bp->pogreskaDB()) {
    $status = "neuspjeh";
    exit;
}
while (list($count) = $rs->fetch_array()) {
    $postoji = $count;
}
$rs->close();
if($postoji === "0") {
    $bp->updateDB($sql2);
    if ($bp->pogreskaDB()) {
        $status = "neuspjeh";
        exit;
    }
}
else {
    $bp->updateDB($sql3);
    if ($bp->pogreskaDB()) {
        $status = "neuspjeh";
        exit;
    }
}
$bp->zatvoriDB();
$bp1 = new Baza();
$bp1->spojiDB();
if(isset($_SESSION["prijava"])) {
    $korIme = $_SESSION["prijava"][0];
    require_once("dohvati_virt_vrijeme.php");
    $sql4 = "INSERT INTO dnevnik VALUES (DEFAULT, " .
            "'upit', '" . $sql1 . "', '" .
            date("Y-m-d H:i:s", strval(intval(time()) + pomak())) .
            "', '" . $korIme . "')";
    if($postoji === "0") {
        $sql5 = "INSERT INTO dnevnik VALUES (DEFAULT, " .
                "'upit', '" . $sql2 . "', '" .
                date("Y-m-d H:i:s", strval(intval(time()) + pomak())) .
                "', '" . $korIme . "')";
    }
    else {
        $sql5 = "INSERT INTO dnevnik VALUES (DEFAULT, " .
                "'upit', '" . $sql3 . "', '" .
                date("Y-m-d H:i:s", strval(intval(time()) + pomak())) .
                "', '" . $korIme . "')";
    }
    $bp1->updateDB($sql4);
    if ($bp1->pogreskaDB()) {
        exit;
    }
    $bp1->updateDB($sql5);
    if ($bp1->pogreskaDB()) {
        exit;
    }
}
$bp1->zatvoriDB();
echo json_encode($status);

?>