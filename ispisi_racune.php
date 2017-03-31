<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

$ispis = "<table><caption>Ispis računa</caption><thead><tr>";
$ispis .= "<th>Broj računa</th><th>Datum i vrijeme</th><th>Naziv usluge</th><th>Cijena usluge</th><th>Očitano stanje</th><th>Potrošnja</th><th>Ime djelatnika</th><th>Ime korisnika</th><th>Broj rezervacije</th>";
$ispis .= "</tr></thead><tbody>";

$opcija = $_GET["opcija"];
$racun = $_GET["racun"];
$id = preg_replace("/[^0-9]/", "", $racun);
require_once("baza_class.php");
$bp = new Baza();
$bp->spojiDB();
if($opcija === "zaposlenik") {
    $sql = "SELECT id, datum_vrijeme, naziv_usluge, cijena_usluge, ocitano_stanje, potrosnja, " .
            "ime_djelatnika, (SELECT ime FROM korisnici WHERE korisnicko_ime = ime_korisnika), " .
            "(SELECT prezime FROM korisnici WHERE korisnicko_ime = ime_korisnika), " .
            "rezervacija_id FROM racun";
}
else if($opcija === "potrošač") {
    $sql = "SELECT id, datum_vrijeme, naziv_usluge, cijena_usluge, ocitano_stanje, potrosnja, " .
            "ime_djelatnika, (SELECT ime FROM korisnici WHERE korisnicko_ime = ime_korisnika), " .
            "(SELECT prezime FROM korisnici WHERE korisnicko_ime = ime_korisnika), " .
            "rezervacija_id FROM racun WHERE ime_korisnika = '" . $_SESSION["prijava"][0] . "'";
}
else {
    $sql = "SELECT id, datum_vrijeme, naziv_usluge, cijena_usluge, ocitano_stanje, potrosnja, " .
            "ime_djelatnika, (SELECT ime FROM korisnici WHERE korisnicko_ime = ime_korisnika), " .
            "(SELECT prezime FROM korisnici WHERE korisnicko_ime = ime_korisnika), " .
            "rezervacija_id FROM racun WHERE ime_korisnika = '" . $_SESSION["prijava"][0] .
            "' AND id = " . $id;
}
$rs = $bp->selectDB($sql);
if ($bp->pogreskaDB()) {
    exit;
}
while (list($id, $datum_vrijeme, $naziv_usluge, $cijena_usluge, $ocitano_stanje, $potrosnja, $ime_djelatnika, $ime, $prezime, $rezervacija_id) = $rs->fetch_array()) {
    $datum_i_vrijeme = date("d.m.Y H:i:s", strtotime($datum_vrijeme));
    $ispis .= "<tr><td>Račun br. $id</td><td>$datum_i_vrijeme</td><td>$naziv_usluge</td><td>$cijena_usluge</td><td>$ocitano_stanje</td><td>$potrosnja</td><td>$ime_djelatnika</td><td>$ime $prezime</td><td>Rezervacija br. $rezervacija_id</td></tr>";
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