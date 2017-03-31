<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

$ispis = "<table><caption>Ustanove</caption><thead><tr>";
$ispis .= "<th>Ustanova</th><th>Broj lajkova</th>";
$ispis .= "</tr></thead><tbody>";

require_once("baza_class.php");
$bp = new Baza();
$sql = "SELECT naziv, broj_lajkova FROM ustanova";
$bp->spojiDB();
$rs = $bp->selectDB($sql);
if ($bp->pogreskaDB()) {
    exit;
}
while (list($naziv, $broj_lajkova) = $rs->fetch_array()) {
    $ispis .= "<tr><td>$naziv</td><td>$broj_lajkova</td></tr>";
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