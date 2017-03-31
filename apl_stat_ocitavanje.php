<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

$ispis = "<table><caption>Kategorije očitavanja</caption><thead><tr>";
$ispis .= "<th>Ustanova</th><th>Kategorija</th><th>Broj lajkova</th>";
$ispis .= "</tr></thead><tbody>";

require_once("baza_class.php");
$bp = new Baza();
$sql = "SELECT kategorija, ocitavanje.broj_lajkova, naziv FROM ustanova, ocitavanje " .
        "WHERE ustanova_id = ustanova.id";
$bp->spojiDB();
$rs = $bp->selectDB($sql);
if ($bp->pogreskaDB()) {
    exit;
}
while (list($kategorija, $broj_lajkova, $naziv) = $rs->fetch_array()) {
    $ispis .= "<tr><td>$naziv</td><td>$kategorija</td><td>$broj_lajkova</td></tr>";
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