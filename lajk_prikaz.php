<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

$ustanova = $_GET["ustanova"];
$ocitavanje = $_GET["ocitavanje"];
require_once("baza_class.php");
$bp = new Baza();
$sql1 = "SELECT COUNT(*) FROM lajkovi_ustanova " .
        "WHERE korisnici_korisnicko_ime = '" . $_SESSION["prijava"][0] .
        "' AND ustanova_id = (SELECT id FROM ustanova WHERE naziv = '" .
        $ustanova . "')";
$sql2 = "SELECT COUNT(*) FROM lajkovi_ocitavanje " .
        "WHERE korisnici_korisnicko_ime = '" . $_SESSION["prijava"][0] .
        "' AND ocitavanje_id = (SELECT id FROM ocitavanje WHERE kategorija = '" .
        $ocitavanje . "' AND ustanova_id = (SELECT id FROM ustanova WHERE naziv = '" .
        $ustanova . "'))";
$bp->spojiDB();
$rs1 = $bp->selectDB($sql1);
if ($bp->pogreskaDB()) {
    exit;
}
while (list($ustanova) = $rs1->fetch_array()) {
    $lajk_ustanova = $ustanova;
}
$rs2 = $bp->selectDB($sql2);
if ($bp->pogreskaDB()) {
    exit;
}
while (list($ocitavanje) = $rs2->fetch_array()) {
    $lajk_ocitavanje = $ocitavanje;
}
$rezultat = '[{"lajk":"' . $lajk_ustanova . '"},{"lajk":"' . $lajk_ocitavanje . '"}]';
$rs1->close();
$rs2->close();
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

echo $rezultat;

?>