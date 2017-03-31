<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

$ispis = "<table><caption>Ispis korisnika</caption><thead><tr>";
$ispis .= "<th>Korisničko ime</th><th>Prezime</th><th>Ime</th><th>E-mail</th><th>Lozinka</th>";
$ispis .= "</tr></thead><tbody>";

require_once("baza_class.php");
$bp = new Baza();
$sql = "SELECT korisnicko_ime, prezime, ime, email, lozinka FROM korisnici";
$bp->spojiDB();
$rs = $bp->selectDB($sql);
if ($bp->pogreskaDB()) {
    exit;
}
while (list($korime, $prezime, $ime, $email, $lozinka) = $rs->fetch_array()) {
    $ispis .= "<tr><td>$korime</td><td>$prezime</td><td>$ime</td><td>$email</td><td>$lozinka</td></tr>";
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