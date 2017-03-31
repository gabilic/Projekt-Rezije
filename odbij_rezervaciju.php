<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

$status = "uspjeh";
$rezervacija = $_GET["rezervacija"];
$id = preg_replace("/[^0-9]/", "", $rezervacija);
require_once("baza_class.php");
$bp = new Baza();
$sql1 = "UPDATE rezervacija SET status = 'NE' " .
        "WHERE id = " . $id;
$sql2 = "SELECT email FROM korisnici WHERE korisnicko_ime = " .
        "(SELECT korisnici_korisnicko_ime FROM rezervacija WHERE id = " . $id . ")";
$bp->spojiDB();
$bp->updateDB($sql1);
if ($bp->pogreskaDB()) {
    $status = "neuspjeh";
    exit;
}
$rs = $bp->selectDB($sql2);
if ($bp->pogreskaDB()) {
    exit;
}
while (list($email) = $rs->fetch_array()) {
    $mail = $email;
}
$rs->close();
mail($mail, "Odbijena rezervacija", 'Vaša rezervacija "' . $rezervacija . '" je odbijena.');
if(isset($_SESSION["prijava"])) {
    $korIme = $_SESSION["prijava"][0];
    require_once("dohvati_virt_vrijeme.php");
    $sql3 = "INSERT INTO dnevnik VALUES (DEFAULT, " .
            "'upit', '" . str_replace("'", "''", $sql1) . "', '" .
            date("Y-m-d H:i:s", strval(intval(time()) + pomak())) .
            "', '" . $korIme . "')";
    $sql4 = "INSERT INTO dnevnik VALUES (DEFAULT, " .
            "'upit', '" . $sql2 . "', '" .
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