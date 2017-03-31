<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

$rezervacija = $_GET["rezervacija"];
$id = preg_replace("/[^0-9]/", "", $rezervacija);
require_once("baza_class.php");
$bp = new Baza();
$sql1 = "SELECT COUNT(*) FROM racun WHERE ime_korisnika = " .
        "(SELECT korisnici_korisnicko_ime FROM rezervacija WHERE id = " . $id . ")";
$bp->spojiDB();
$rs = $bp->selectDB($sql1);
if ($bp->pogreskaDB()) {
    exit;
}
while (list($count) = $rs->fetch_array()) {
    $postoji = $count;
}
$rs->close();
if($postoji === "0") {
    $potrosnja = 0;
}
else {
    $sql2 = "SELECT (SELECT ocitano_stanje FROM rezervacija WHERE id = " . $id .
            ") - (SELECT ocitano_stanje FROM racun WHERE ime_korisnika = " .
            "(SELECT korisnici_korisnicko_ime FROM rezervacija WHERE id = " . $id .
            ") ORDER BY datum_vrijeme DESC LIMIT 1)";
    $rs = $bp->selectDB($sql2);
    if ($bp->pogreskaDB()) {
        exit;
    }
    while (list($vrijednost) = $rs->fetch_array()) {
        $potrosnja = $vrijednost;
    }
    $rs->close();
}
if(isset($_SESSION["prijava"])) {
    $korIme = $_SESSION["prijava"][0];
    require_once("dohvati_virt_vrijeme.php");
    $sql3 = "INSERT INTO dnevnik VALUES (DEFAULT, " .
            "'upit', '" . $sql1 . "', '" .
            date("Y-m-d H:i:s", strval(intval(time()) + pomak())) .
            "', '" . $korIme . "')";
    $bp->updateDB($sql3);
    if ($bp->pogreskaDB()) {
        exit;
    }
    if($postoji !== "0") {
        $sql4 = "INSERT INTO dnevnik VALUES (DEFAULT, " .
                "'upit', '" . $sql2 . "', '" .
                date("Y-m-d H:i:s", strval(intval(time()) + pomak())) .
                "', '" . $korIme . "')";
        $bp->updateDB($sql4);
        if ($bp->pogreskaDB()) {
            exit;
        }
    }
}
$bp->zatvoriDB();

echo json_encode($potrosnja);

?>