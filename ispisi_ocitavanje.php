<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

$ispis = "<table><caption>Ispis kategorija i cijena usluga očitavanja</caption><thead><tr>";
$ispis .= "<th>Kategorija očitavanja</th><th>Cijena usluge očitavanja</th><th>Ustanova</th>";
$ispis .= "</tr></thead><tbody>";

require_once("baza_class.php");
$bp = new Baza();
$sql = "SELECT kategorija, cijena_usluge, " .
        "(SELECT naziv FROM ustanova WHERE ustanova_id = ustanova.id) FROM ocitavanje";
$bp->spojiDB();
$rs = $bp->selectDB($sql);
if ($bp->pogreskaDB()) {
    exit;
}
while (list($kategorija, $cijena_usluge, $ustanova) = $rs->fetch_array()) {
    $ispis .= "<tr><td>$kategorija</td><td>$cijena_usluge</td><td>$ustanova</td></tr>";
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