<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

$ustanova = $_GET["ustanova"];
$ispis = "<table><caption>Ispis kategorija i cijena usluga očitavanja</caption><thead><tr>";
$ispis .= "<th>Kategorija očitavanja</th><th>Cijena usluge očitavanja</th><th>Ustanova</th>";
$ispis .= "</tr></thead><tbody>";

require_once("baza_class.php");
$bp = new Baza();
$sql = "SELECT * FROM (SELECT ocitavanje.id, kategorija, cijena_usluge, naziv FROM ocitavanje, ustanova " .
        "WHERE ustanova_id = ustanova.id AND naziv = '" . $ustanova .
        "' ORDER BY ocitavanje.id DESC LIMIT 3) t ORDER BY 1";
$bp->spojiDB();
$rs = $bp->selectDB($sql);
if ($bp->pogreskaDB()) {
    exit;
}
while (list($id, $kategorija, $cijena_usluge, $naziv) = $rs->fetch_array()) {
    $ispis .= "<tr><td>$kategorija</td><td>$cijena_usluge</td><td>$naziv</td></tr>";
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

$ispis .= "</tbody></table>";
echo json_encode($ispis);

?>