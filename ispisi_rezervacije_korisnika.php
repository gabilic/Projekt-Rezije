<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

$ispis = "<table><caption>Rezervacije korisnika " . $_SESSION["prijava"][0] . "</caption><thead><tr>";
$ispis .= "<th>Broj rezervacije</th><th>Očitano stanje</th><th>Kategorija očitavanja</th><th>Status</th>";
$ispis .= "</tr></thead><tbody>";
require_once("baza_class.php");
$bp = new Baza();
$sql = "SELECT id, ocitano_stanje, status, " .
        "(SELECT kategorija FROM ocitavanje WHERE ocitavanje_id = ocitavanje.id) FROM rezervacija " .
        "WHERE korisnici_korisnicko_ime = '" . $_SESSION["prijava"][0] . "'";
$bp->spojiDB();
$rs = $bp->selectDB($sql);
if ($bp->pogreskaDB()) {
    exit;
}
while (list($id, $ocitano_stanje, $status, $ocitavanje) = $rs->fetch_array()) {
    if($status === NULL) {
        $stanje = "Na razmatranju";
    }
    else if($status === "DA") {
        $stanje = "Potvrđena";
    }
    else {
        $stanje = "Odbijena";
    }
    $ispis .= "<tr><td>Rezervacija br. $id</td><td>$ocitano_stanje</td><td>$ocitavanje</td><td>$stanje</td></tr>";
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