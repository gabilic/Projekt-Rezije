<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

require_once("baza_class.php");
$bp = new Baza();
$sql = "SELECT naziv FROM ustanova";
$bp->spojiDB();
$rs = $bp->selectDB($sql);
if ($bp->pogreskaDB()) {
    exit;
}
$rezultat = "[";
while (list($ustanova) = $rs->fetch_array()) {
    $lista[] = '{"ustanova":"' . $ustanova . '"}';
}
$rezultat .= join(",", $lista);
$rezultat .= "]";
$rs->close();
if(isset($_SESSION["prijava"])) {
    $korIme = $_SESSION["prijava"][0];
    require_once("dohvati_virt_vrijeme.php");
    $sql2 = "INSERT INTO dnevnik VALUES (DEFAULT, " .
            "'upit', '" . $sql . "', '" .
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