<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

$ispis = "<table><caption>Dnevnik sustava</caption><thead><tr>";
$ispis .= "<th>Korisničko ime</th><th>Radnja</th><th>Opis</th><th>Datum i vrijeme</th>";
$ispis .= "</tr></thead><tbody>";

require_once("baza_class.php");
$bp = new Baza();
$sql = "SELECT radnja, opis, datum_vrijeme, korisnici_korisnicko_ime FROM dnevnik";
$bp->spojiDB();
$rs = $bp->selectDB($sql);
if ($bp->pogreskaDB()) {
    exit;
}
while (list($radnja, $opis, $datum_vrijeme, $korime) = $rs->fetch_array()) {
    $dat_vrij = strtotime($datum_vrijeme);
    $dv = date("d.m.Y H:i:s", $dat_vrij);
    $ispis .= "<tr><td>$korime</td><td>$radnja</td><td>$opis</td><td>$dv</td></tr>";
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