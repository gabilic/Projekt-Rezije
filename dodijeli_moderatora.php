<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

$status = "uspjeh";
$ustanova = $_GET["ustanova"];
$korime = $_GET["korisnik"];
require_once("baza_class.php");
$bp = new Baza();
$sql1 = "INSERT INTO ustanova VALUES (DEFAULT, '" .
        $ustanova . "', 0)";
$sql2 = "UPDATE korisnici SET ustanova_id = (SELECT " .
        "id FROM ustanova WHERE naziv = '" . $ustanova . "'), " .
        "tip_korisnika_id = 2 WHERE korisnicko_ime = '" . $korime . "'";
$bp->spojiDB();
$bp->updateDB($sql1);
if ($bp->pogreskaDB()) {
    $status = "neuspjeh";
    exit;
}
$bp->updateDB($sql2);
if ($bp->pogreskaDB()) {
    $status = "neuspjeh";
    exit;
}
if(isset($_SESSION["prijava"])) {
    $korIme = $_SESSION["prijava"][0];
    require_once("dohvati_virt_vrijeme.php");
    $sql3 = "INSERT INTO dnevnik VALUES (DEFAULT, " .
            "'upit', '" . str_replace("'", "''", $sql1) . "', '" .
            date("Y-m-d H:i:s", strval(intval(time()) + pomak())) .
            "', '" . $korIme . "')";
    $sql4 = "INSERT INTO dnevnik VALUES (DEFAULT, " .
            "'upit', '" . str_replace("'", "''", $sql2) . "', '" .
            date("Y-m-d H:i:s", strval(intval(time()) + pomak())) .
            "', '" . $korIme . "')";
    $bp->updateDB($sql3);
    if ($bp->pogreskaDB()) {
        exit;
    }
    $bp->updateDB($sql4);
    if ($bp->pogreskaDB()) {
        exit;
    }
}
$bp->zatvoriDB();

echo json_encode($status);

?>