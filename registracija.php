<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

include_once "koristi_http.php";

$naslov = "Registracija";
$template = "registracija";
include "smarty.php";

function provjeriIme() {
    global $ispis;
    $ime = $_POST["ime"];
    if($ime === "") {
        $ispis .= "Ime nije uneseno!<br>";
        return false;
    }
    return true;
}

function provjeriPrezime() {
    global $ispis;
    $prezime = $_POST["prezime"];
    if($prezime === "") {
        $ispis .= "Prezime nije uneseno!<br>";
        return false;
    }
    return true;
}

function kVeliko($korIme) {
    $brojac = 0;
    $znak = 0;
    for($i = 0; $i < strlen($korIme); $i++) {
        $znak = $korIme[$i];
        if($znak >= 'A' && $znak <= 'Z') {
            $brojac++;
        }
    }
    return $brojac;
}

function kPoseban($korIme) {
    $brojac = 0;
    $znak = '';
    for($i = 0; $i < strlen($korIme); $i++) {
        $znak = $korIme[$i];
        switch($znak) {
            case '!': $brojac++; break;
            case '#': $brojac++; break;
            case '$': $brojac++; break;
            case '?': $brojac++; break;
        }
    }
    return $brojac;
}

function lVeliko($lozinka) {
    $brojac = 0;
    $znak = 0;
    for($i = 0; $i < strlen($lozinka); $i++) {
        $znak = $lozinka[$i];
        if($znak >= 'A' && $znak <= 'Z') {
            $brojac++;
        }
    }
    return $brojac;
}

function lMalo($lozinka) {
    $brojac = 0;
    $znak = 0;
    for($i = 0; $i < strlen($lozinka); $i++) {
        $znak = $lozinka[$i];
        if($znak >= 'a' && $znak <= 'z') {
            $brojac++;
        }
    }
    return $brojac;
}

function lBroj($lozinka) {
    $brojac = 0;
    $znak = 0;
    for($i = 0; $i < strlen($lozinka); $i++) {
        $znak = $lozinka[$i];
        if($znak >= '0' && $znak <= '9') {
            $brojac++;
        }
    }
    return $brojac;
}

function provjeriKorisnickoIme() {
    global $ispis;
    $korIme = $_POST["korime"];
    $provjera = true;
    if($korIme === "") {
        $ispis .= "Korisničko ime nije uneseno!<br>";
        $provjera = false;
    }
    else {
        if(strlen($korIme) < 8 || strlen($korIme) > 16) {
            $ispis .= "Korisničko ime mora sadržavati minimalno 8, a maksimalno 16 znakova!<br>";
            $provjera = false;
        }
        $znak = $korIme[0];
        if($znak < 'a' || $znak > 'z') {
            $ispis .= "Korisničko ime mora započeti malim slovom!<br>";
            $provjera = false;
        }
        $velikoSlovo = kVeliko($korIme);
        if($velikoSlovo < 1) {
            $ispis .= "Korisničko ime mora sadržavati barem jedno veliko slovo!<br>";
            $provjera = false;
        }
        $posebanZnak = kPoseban($korIme);
        if($posebanZnak > 0) {
            $ispis .= 'Korisničko ime ne smije sadržavati znakove "!", "#", "$" i "?"!<br>';
            $provjera = false;
        }
    }
    return $provjera;
}

function zauzetoKorisnickoIme() {
    global $ispis;
    $korIme = $_POST["korime"];
    $zauzeto = false;
    require_once("baza_class.php");
    $bp = new Baza();
    $sql = "SELECT korisnicko_ime FROM korisnici " .
            "WHERE korisnicko_ime = '" . $korIme . "'";
    $bp->spojiDB();
    $rs = $bp->selectDB($sql);
    if ($bp->pogreskaDB()) {
        exit;
    }
    while (list($ime) = $rs->fetch_array()) {
        if($ime === $korIme) {
            $ispis .= "Korisničko ime je zauzeto!<br>";
            $zauzeto = true;
        }
    }
    $rs->close();
    $bp->zatvoriDB();
    return $zauzeto;
}

function provjeriLozinku() {
    global $ispis;
    $lozinka1 = $_POST["lozinka"];
    $lozinka2 = $_POST["potvloz"];
    $provjera = true;
    if($lozinka1 === "") {
        $ispis .= "Lozinka nije unesena!<br>";
        $provjera = false;
    }
    else {
        if(strlen($lozinka1) < 8 || strlen($lozinka1) > 16) {
            $ispis .= "Lozinka mora sadržavati minimalno 8, a maksimalno 16 znakova!<br>";
            $provjera = false;
        }
        $velikoSlovo = lVeliko($lozinka1);
        if($velikoSlovo < 1) {
            $ispis .= "Lozinka mora sadržavati barem jedno veliko slovo!<br>";
            $provjera = false;
        }
        $maloSlovo = lMalo($lozinka1);
        if($maloSlovo < 1) {
            $ispis .= "Lozinka mora sadržavati barem jedno malo slovo!<br>";
            $provjera = false;
        }
        $broj = lBroj($lozinka1);
        if($broj < 1) {
            $ispis .= "Lozinka mora sadržavati barem jedan broj!<br>";
            $provjera = false;
        }
    }
    if($lozinka2 === "") {
        $ispis .= "Potvrda lozinke nije unesena!<br>";
        $provjera = false;
    }
    if($provjera) {
        if($lozinka1 !== $lozinka2) {
            $ispis .= "Lozinka i potvrda lozinke se ne poklapaju!<br>";
            $provjera = false;
        }
    }
    return $provjera;
}

function provjeriDatum() {
    global $ispis;
    $dan = $_POST["roddan"];
    $mjesec = $_POST["rodmjesec"];
    $godina = $_POST["rodgodina"];
    $provjera = true;
    if($dan === "") {
        $ispis .= "Dan rođenja nije unesen!<br>";
        $provjera = false;
    }
    else if($dan < 1 || $dan > 31) {
        $ispis .= "Dan rođenja mora biti u rasponu od 1 do 31!<br>";
        $provjera = false;
    }
    if($mjesec === "") {
        $ispis .= "Mjesec rođenja nije unesen!<br>";
        $provjera = false;
    }
    if($godina === "") {
        $ispis .= "Godina rođenja nije unesena!<br>";
        $provjera = false;
    }
    else if($godina < 1930 || $godina > 2015) {
        $ispis .= "Godina rođenja mora biti u rasponu od 1930 do 2015!<br>";
        $provjera = false;
    }
    if($dan < 0 || $godina < 0) {
        $ispis .= "Datum rođenja ne smije sadržavati negativne vrijednosti!<br>";
        $provjera = false;
    }
    return $provjera;
}

function provjeriTelefon() {
    global $ispis;
    $broj = $_POST["broj"];
    $provjera = true;
    if($broj === "") {
        $ispis .= "Telefon nije unesen!<br>";
        $provjera = false;
    }
    else {
        if(!preg_match("/^\d{3} \d{7}$/", $broj)) {
            $ispis .= "Telefon nije ispravno unesen!<br>";
            $provjera = false;
        }
    }
    return $provjera;
}

function provjeriEmail() {
    global $ispis;
    $mail = $_POST["mail"];
    $provjera = true;
    if($mail === "") {
        $ispis .= "E-mail adresa nije unesena!<br>";
        $provjera = false;
    }
    else {
        if(!preg_match("/^\w[\w\.]*@\w+\.\w+$/", $mail)) {
            $ispis .= "E-mail adresa nije ispravno unesena!<br>";
            $provjera = false;
        }
    }
    return $provjera;
}

function zauzetEmail() {
    global $ispis;
    $mail = $_POST["mail"];
    $zauzeto = false;
    require_once("baza_class.php");
    $bp = new Baza();
    $sql = "SELECT email FROM korisnici " .
            "WHERE email = '" . $mail . "'";
    $bp->spojiDB();
    $rs = $bp->selectDB($sql);
    if ($bp->pogreskaDB()) {
        exit;
    }
    while (list($email) = $rs->fetch_array()) {
        if($email === $mail) {
            $ispis .= "E-mail je zauzet!<br>";
            $zauzeto = true;
        }
    }
    $rs->close();
    $bp->zatvoriDB();
    return $zauzeto;
}

function provjeriLokaciju() {
    global $ispis;
    $lokacija = $_POST["lokacija"];
    $provjera = true;
    if($lokacija === "") {
        $ispis .= "Lokacija nije unesena!<br>";
        $provjera = false;
    }
    else {
        if(!preg_match("/^\d+\.\d+; \d+\.\d+$/", $lokacija)) {
            $ispis .= "Lokacija nije ispravno unesena!<br>";
            $provjera = false;
        }
    }
    return $provjera;
}

function provjeriObavijesti() {
    global $ispis;
    if(!isset($_POST["obavijesti"])) {
        $ispis .= "Niste odabrali da li želite primati obavijesti!<br>";
        return false;
    }
    return true;
}

function provjeriRobot() {
    global $ispis;
    $provjera = true;
    if(isset($_POST["g-recaptcha-response"])) {
        $captcha = $_POST['g-recaptcha-response'];
    }
    else {
        $ispis .= "Dokažite da niste robot!<br>";
        $provjera = false;
    }
    $privatekey = "6LeB6x4TAAAAAFqsvz9tvSww9JJ3kVvNzSgY4_hP";
    $resp = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $privatekey . "&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']);
    $decode = json_decode($resp, true);
    if($decode["success"] === false) {
        $ispis .= "Dokažite da niste robot!<br>";
        $provjera = false;
    }
    return $provjera;
}

function unosKorisnika() {
    $ime = $_POST["ime"];
    $prezime = $_POST["prezime"];
    $korime = $_POST["korime"];
    $lozinka = $_POST["lozinka"];
    $roddan = $_POST["roddan"];
    $rodmjesec = $_POST["rodmjesec"];
    $rodgodina = $_POST["rodgodina"];
    $rodjendan = $rodgodina . "-" . $rodmjesec . "-" . $roddan;
    $spol = $_POST["spol"];
    if($spol === "0") {
        $sp = "M";
    }
    else {
        $sp = "Ž";
    }
    $drzava = $_POST["drzava"];
    $broj = $_POST["broj"];
    $mail = $_POST["mail"];
    $lokacija = $_POST["lokacija"];
    $obavijesti = $_POST["obavijesti"];
    $ob = strtoupper($obavijesti);
    require_once("dohvati_virt_vrijeme.php");
    $datum_i_vrijeme = date("d.m.Y H:i:s", strval(intval(time()) + pomak()));
    $aktivacijski_kod = hash("sha256", $datum_i_vrijeme . $korime . $mail);
    require_once("baza_class.php");
    $bp = new Baza();
    $sql1 = "INSERT INTO korisnici VALUES ('" .
            $korime . "', '" . $ime . "', '" .
            $prezime . "', '" . $lozinka . "', '" .
            $rodjendan . "', '" . $sp . "', '" .
            $drzava . "', '" . $broj . "', '" .
            $mail . "', '" . $lokacija . "', '" .
            $ob . "', '" . $aktivacijski_kod . "', '" .
            date("Y-m-d H:i:s", strval(intval(time()) + pomak())) .
            "', 'NE', 0, 3, NULL)";
    $sql2 = "INSERT INTO dnevnik VALUES (DEFAULT, " .
            "'registracija', 'uspješna', '" . date("Y-m-d H:i:s", strval(intval(time()) + pomak())) .
            "', '" . $korime . "')";
    $bp->spojiDB();
    $bp->updateDB($sql1);
    if ($bp->pogreskaDB()) {
        exit;
    }
    $bp->updateDB($sql2);
    if ($bp->pogreskaDB()) {
        exit;
    }
    $bp->zatvoriDB();
    mail($mail, "Aktivacijski link", "Aktivirajte svoj korisnički račun klikom na sljedeću poveznicu: https://barka.foi.hr/WebDiP/2015_projekti/WebDiP2015x031/aktivacija.php?kod=" . $aktivacijski_kod);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ispis = "";
    $pogreska = false;
    if(!provjeriIme()) {
        $pogreska = true;
    }
    if(!provjeriPrezime()) {
        $pogreska = true;
    }
    if(!provjeriKorisnickoIme()) {
        $pogreska = true;
    }
    else if(zauzetoKorisnickoIme()) {
        $pogreska = true;
    }
    if(!provjeriLozinku()) {
        $pogreska = true;
    }
    if(!provjeriDatum()) {
        $pogreska = true;
    }
    if(!provjeriTelefon()) {
        $pogreska = true;
    }
    if(!provjeriEmail()) {
        $pogreska = true;
    }
    else if(zauzetEmail()) {
        $pogreska = true;
    }
    if(!provjeriLokaciju()) {
        $pogreska = true;
    }
    if(!provjeriObavijesti()) {
        $pogreska = true;
    }
    if(!provjeriRobot()) {
        $pogreska = true;
    }
    if($pogreska) {
        echo $ispis;
    }
    else {
        unosKorisnika();
        echo '<script type="text/javascript">window.location = "prijava.php";</script>';
        exit;
    }
}

$smarty->display('./templates/registracija_2.tpl');

?>