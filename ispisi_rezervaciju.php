<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

$ispis = "<table><caption>Podaci o odabranoj rezervaciji</caption><thead><tr>";
$ispis .= "<th>Očitano stanje</th><th>Korisnik</th><th>Kategorija očitavanja</th>";
$ispis .= "</tr></thead><tbody>";
$rezervacija = $_GET["rezervacija"];
$id = preg_replace("/[^0-9]/", "", $rezervacija);
require_once("baza_class.php");
$bp = new Baza();
$sql = "SELECT ocitano_stanje, korisnici_korisnicko_ime, " .
        "(SELECT kategorija FROM ocitavanje WHERE ocitavanje_id = ocitavanje.id) FROM rezervacija " .
        "WHERE id = " . $id;
$bp->spojiDB();
$rs = $bp->selectDB($sql);
if ($bp->pogreskaDB()) {
    exit;
}
while (list($ocitano_stanje, $korisnik, $ocitavanje) = $rs->fetch_array()) {
    $ispis .= "<tr><td>$ocitano_stanje</td><td>$korisnik</td><td>$ocitavanje</td></tr>";
}
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

$ispis .= "</tbody></table>";
echo json_encode($ispis);

?>