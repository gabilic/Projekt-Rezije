<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

$ispis = "<table><caption>Ispis ustanova</caption><thead><tr>";
$ispis .= "<th>Naziv ustanove</th><th>Moderator</th>";
$ispis .= "</tr></thead><tbody>";

require_once("baza_class.php");
$bp = new Baza();
$sql = "SELECT naziv, " .
        "(SELECT korisnicko_ime FROM korisnici WHERE tip_korisnika_id = 2 AND ustanova_id = ustanova.id) " .
        "FROM ustanova";
$bp->spojiDB();
$rs = $bp->selectDB($sql);
if ($bp->pogreskaDB()) {
    exit;
}
while (list($ustanova, $moderator) = $rs->fetch_array()) {
    $ispis .= "<tr><td>$ustanova</td><td>$moderator</td></tr>";
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