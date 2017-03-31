<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

require_once("baza_class.php");
$bp = new Baza();
$sql = "SELECT DISTINCT oznaka FROM zalba, racun WHERE zalba.id = racun.id AND " .
        "ime_korisnika = '" . $_SESSION["prijava"][0] . "'";
$bp->spojiDB();
$rs = $bp->selectDB($sql);
if ($bp->pogreskaDB()) {
    exit;
}
$rezultat = "[";
while (list($oznaka) = $rs->fetch_array()) {
    $lista[] = '{"oznaka":"' . $oznaka . '"}';
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