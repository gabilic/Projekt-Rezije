<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

$opcija = $_GET["opcija"];
require_once("baza_class.php");
$bp = new Baza();
if($opcija === "nove_rezervacije") {
    $sql = "SELECT id FROM rezervacija WHERE status IS NULL";
}
else {
    $sql = "SELECT id FROM rezervacija WHERE status = 'DA'";
}
$bp->spojiDB();
$rs = $bp->selectDB($sql);
if ($bp->pogreskaDB()) {
    exit;
}
$rezultat = "[";
while (list($rezervacija) = $rs->fetch_array()) {
    $lista[] = '{"rezervacija":"Rezervacija br. ' . $rezervacija . '"}';
}
$rezultat .= join(",", $lista);
$rezultat .= "]";
$rs->close();
if(isset($_SESSION["prijava"])) {
    $korIme = $_SESSION["prijava"][0];
    require_once("dohvati_virt_vrijeme.php");
    $sql2 = "INSERT INTO dnevnik VALUES (DEFAULT, " .
            "'upit', '" . str_replace("'", "''", $sql) . "', '" .
            date("Y-m-d H:i:s", strval(intval(time()) + pomak())) .
            "', '" . $korIme . "')";
    $bp->updateDB($sql2);
    if ($bp->pogreskaDB()) {
        exit;
    }
}
$bp->zatvoriDB();

echo $rezultat;

?>