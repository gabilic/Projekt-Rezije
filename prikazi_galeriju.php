<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

$galerija = "";
$oznaka = $_GET["oznaka"];
require_once("baza_class.php");
$bp = new Baza();
$sql = "SELECT slika FROM zalba WHERE oznaka = '" . $oznaka . "'";
$bp->spojiDB();
$rs = $bp->selectDB($sql);
if ($bp->pogreskaDB()) {
    exit;
}
while (list($slika) = $rs->fetch_array()) {
    $galerija .= '<img src="img/zalbe/' . $slika . '" alt="Slika" width="500" height="500">';
}
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

echo json_encode($galerija);

?>