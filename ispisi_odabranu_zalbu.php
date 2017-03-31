<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

$ispis = "<table><caption>Podaci o odabranoj žalbi</caption><thead><tr>";
$ispis .= "<th>Opis</th><th>Slika</th><th>Oznaka</th>";
$ispis .= "</tr></thead><tbody>";
$zalba = $_GET["zalba"];
$id = preg_replace("/[^0-9]/", "", $zalba);
require_once("baza_class.php");
$bp = new Baza();
$sql = "SELECT opis, slika, oznaka FROM zalba WHERE id = " . $id;
$bp->spojiDB();
$rs = $bp->selectDB($sql);
if ($bp->pogreskaDB()) {
    exit;
}
while (list($opis, $slika, $oznaka) = $rs->fetch_array()) {
    $ispis .= '<tr><td>' . $opis . '</td><td><img src="img/zalbe/' . $slika . '" alt="Slika" width="500" height="500"></td><td>' . $oznaka . '</td></tr>';
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