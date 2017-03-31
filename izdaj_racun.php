<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

$status = "uspjeh";
$datum_i_vrijeme = $_GET["datum_i_vrijeme"];
$datum_vrijeme = date("Y-m-d H:i:s", strtotime($datum_i_vrijeme));
$usluga = $_GET["usluga"];
$cijena = $_GET["cijena"];
$stanje = $_GET["stanje"];
$potrosnja = $_GET["potrosnja"];
$djelatnik = $_GET["djelatnik"];
$rezervacija = $_GET["rezervacija"];
$id = preg_replace("/[^0-9]/", "", $rezervacija);
require_once("baza_class.php");
$bp = new Baza();
$bp->spojiDB();
$sql1 = "SELECT korisnici_korisnicko_ime FROM rezervacija WHERE id = " . $id;
$rs = $bp->selectDB($sql1);
if ($bp->pogreskaDB()) {
    exit;
}
while (list($korime) = $rs->fetch_array()) {
    $korisnik = $korime;
}
$rs->close();
$sql2 = "INSERT INTO racun VALUES (DEFAULT, '" .
        $datum_vrijeme . "', '" . $usluga . "', " . $cijena . ", " .
        $stanje . ", " . $potrosnja . ", '" . $djelatnik . "', '" .
        $korisnik . "', " . $id . ")";
$bp->updateDB($sql2);
if ($bp->pogreskaDB()) {
    $status = "neuspjeh";
    exit;
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
    $sql4 = "INSERT INTO dnevnik VALUES (DEFAULT, " .
            "'upit', '" . str_replace("'", "''", $sql2) . "', '" .
            date("Y-m-d H:i:s", strval(intval(time()) + pomak())) .
            "', '" . $korIme . "')";
    $bp->updateDB($sql4);
    if ($bp->pogreskaDB()) {
        exit;
    }
}
$bp->zatvoriDB();

echo json_encode($status);

?>