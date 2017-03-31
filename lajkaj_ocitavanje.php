<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

$status = "uspjeh";
$ustanova = $_GET["ustanova"];
$ocitavanje = $_GET["ocitavanje"];
$lajk = $_GET["lajk"];
require_once("baza_class.php");
$bp = new Baza();
if($lajk === "Sviđa mi se") {
    $sql1 = "INSERT INTO lajkovi_ocitavanje VALUES ('" . $_SESSION["prijava"][0] .
            "', (SELECT id FROM ocitavanje WHERE kategorija = '" . $ocitavanje .
            "' AND ustanova_id = (SELECT id FROM ustanova WHERE naziv = '" .
            $ustanova . "')))";
    $sql2 = "UPDATE ocitavanje SET broj_lajkova = broj_lajkova + 1 WHERE kategorija = '" .
            $ocitavanje . "' AND ustanova_id = (SELECT id FROM ustanova WHERE naziv = '" .
            $ustanova . "')";
}
else {
    $sql1 = "DELETE FROM lajkovi_ocitavanje WHERE korisnici_korisnicko_ime = '" .
            $_SESSION["prijava"][0] . "' AND ocitavanje_id = " .
            "(SELECT id FROM ocitavanje WHERE kategorija = '" . $ocitavanje .
            "' AND ustanova_id = (SELECT id FROM ustanova WHERE naziv = '" .
            $ustanova . "'))";
    $sql2 = "UPDATE ocitavanje SET broj_lajkova = broj_lajkova - 1 WHERE kategorija = '" .
            $ocitavanje . "' AND ustanova_id = (SELECT id FROM ustanova WHERE naziv = '" .
            $ustanova . "')";
}
$bp->spojiDB();
$bp->selectDB($sql1);
if ($bp->pogreskaDB()) {
    $status = "neuspjeh";
    exit;
}
$bp->selectDB($sql2);
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