<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

$vrijeme = $_GET["vrijeme"];
$status = "uspjeh";
require_once("baza_class.php");
$bp = new Baza();
$sql1 = "SELECT COUNT(*) FROM virtualno_vrijeme";
$sql2 = "INSERT INTO virtualno_vrijeme VALUES (" . $vrijeme . ")";
$sql3 = "UPDATE virtualno_vrijeme SET trajanje = " . $vrijeme;
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
$sql4 = "SELECT trajanje FROM virtualno_vrijeme";
$rs2 = $bp1->selectDB($sql4);
if ($bp1->pogreskaDB()) {
    exit;
}
while (list($pomak_vremena) = $rs2->fetch_array()) {
    $pomak = $pomak_vremena;
}
$rs2->close();
if(isset($_SESSION["prijava"])) {
    $korIme = $_SESSION["prijava"][0];
    require_once("dohvati_virt_vrijeme.php");
    $sql5 = "INSERT INTO dnevnik VALUES (DEFAULT, " .
            "'upit', '" . $sql1 . "', '" .
            date("Y-m-d H:i:s", strval(intval(time()) + intval($pomak))) .
            "', '" . $korIme . "')";
    if($postoji === "0") {
        $sql6 = "INSERT INTO dnevnik VALUES (DEFAULT, " .
                "'upit', '" . $sql2 . "', '" .
                date("Y-m-d H:i:s", strval(intval(time()) + intval($pomak))) .
                "', '" . $korIme . "')";
    }
    else {
        $sql6 = "INSERT INTO dnevnik VALUES (DEFAULT, " .
                "'upit', '" . $sql3 . "', '" .
                date("Y-m-d H:i:s", strval(intval(time()) + intval($pomak))) .
                "', '" . $korIme . "')";
    }
    $bp1->updateDB($sql5);
    if ($bp1->pogreskaDB()) {
        exit;
    }
    $bp1->updateDB($sql6);
    if ($bp1->pogreskaDB()) {
        exit;
    }
}
$bp1->zatvoriDB();
echo json_encode($status);

?>