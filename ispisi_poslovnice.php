<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

$ispis = "<table><caption>Ispis adresa poslovnica</caption><thead><tr>";
$ispis .= "<th>Država</th><th>Grad</th><th>Ulica</th><th>Broj</th><th>Ustanova</th>";
$ispis .= "</tr></thead><tbody>";

require_once("baza_class.php");
$bp = new Baza();
$sql = "SELECT drzava, grad, ulica, broj, " .
        "(SELECT naziv FROM ustanova WHERE ustanova_id = ustanova.id) FROM poslovnica";
$bp->spojiDB();
$rs = $bp->selectDB($sql);
if ($bp->pogreskaDB()) {
    exit;
}
while (list($drzava, $grad, $ulica, $broj, $ustanova) = $rs->fetch_array()) {
    $ispis .= "<tr><td>$drzava</td><td>$grad</td><td>$ulica</td><td>$broj</td><td>$ustanova</td></tr>";
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