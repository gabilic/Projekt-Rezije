<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

function koristiHttps() {
    $protokol = "http";
    if(array_key_exists("HTTPS", $_SERVER) && $_SERVER["HTTPS"] === "on") {
        $protokol = "https";
    }
    if($protokol !== "https") {
        $url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        header("Location: $url");
    }
}

koristiHttps();

if(isset($_COOKIE["korisnik"])) {
    $kolacic = $_COOKIE["korisnik"];
}
else {
    $kolacic = "";
}

$naslov = "Prijava";
$template = "prijava";
include "smarty.php";

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

function postojiKorisnickoIme() {
    global $ispis;
    $korIme = $_POST["korime"];
    $postoji = false;
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
            $postoji = true;
        }
    }
    $rs->close();
    $bp->zatvoriDB();
    if(!$postoji) {
        $ispis .= "Uneseno korisničko ime ne postoji u bazi podataka!<br>";
    }
    return $postoji;
}

function provjeriLozinku() {
    global $ispis;
    $lozinka = $_POST["lozinka"];
    $provjera = true;
    if($lozinka === "") {
        $ispis .= "Lozinka nije unesena!<br>";
        $provjera = false;
    }
    else {
        if(strlen($lozinka) < 8 || strlen($lozinka) > 16) {
            $ispis .= "Lozinka mora sadržavati minimalno 8, a maksimalno 16 znakova!<br>";
            $provjera = false;
        }
        $velikoSlovo = lVeliko($lozinka);
        if($velikoSlovo < 1) {
            $ispis .= "Lozinka mora sadržavati barem jedno veliko slovo!<br>";
            $provjera = false;
        }
        $maloSlovo = lMalo($lozinka);
        if($maloSlovo < 1) {
            $ispis .= "Lozinka mora sadržavati barem jedno malo slovo!<br>";
            $provjera = false;
        }
        $broj = lBroj($lozinka);
        if($broj < 1) {
            $ispis .= "Lozinka mora sadržavati barem jedan broj!<br>";
            $provjera = false;
        }
    }
    return $provjera;
}

function tocnaLozinka() {
    global $ispis;
    $korIme = $_POST["korime"];
    $lozinka = $_POST["lozinka"];
    $tocna = false;
    require_once("dohvati_virt_vrijeme.php");
    require_once("baza_class.php");
    $bp = new Baza();
    $sql1 = "SELECT lozinka FROM korisnici " .
            "WHERE korisnicko_ime = '" . $korIme . "'";
    $sql2 = "UPDATE korisnici SET br_neuspj_prijava = br_neuspj_prijava + 1 " .
            "WHERE korisnicko_ime = '" . $korIme . "'";
    $sql3 = "INSERT INTO dnevnik VALUES (DEFAULT, " .
            "'prijava', 'neuspješna', '" . date("Y-m-d H:i:s", strval(intval(time()) + pomak())) .
            "', '" . $korIme . "')";
    $sql4 = "SELECT br_neuspj_prijava FROM korisnici " .
            "WHERE korisnicko_ime = '" . $korIme . "'";
    $sql5 = "INSERT INTO dnevnik VALUES (DEFAULT, " .
            "'zaključavanje', 'zaključan korisnički račun', '" .
            date("Y-m-d H:i:s", strval(intval(time()) + pomak())) .
            "', '" . $korIme . "')";
    $bp->spojiDB();
    $rs1 = $bp->selectDB($sql1);
    if ($bp->pogreskaDB()) {
        exit;
    }
    while (list($lozinka_provjera) = $rs1->fetch_array()) {
        if($lozinka_provjera === $lozinka) {
            $tocna = true;
        }
    }
    $rs1->close();
    if(!$tocna) {
        $ispis .= "Unesena lozinka nije točna!<br>";
        $bp->updateDB($sql2);
        if ($bp->pogreskaDB()) {
            exit;
        }
        $bp->updateDB($sql3);
        if ($bp->pogreskaDB()) {
            exit;
        }
    }
    $zakljucan = false;
    $rs2 = $bp->selectDB($sql4);
    if ($bp->pogreskaDB()) {
        exit;
    }
    while (list($neuspj_prijave) = $rs2->fetch_array()) {
        if(intval($neuspj_prijave) === 4) {
            $zakljucan = true;
        }
    }
    $rs2->close();
    if($zakljucan) {
        $bp->updateDB($sql5);
        if ($bp->pogreskaDB()) {
            exit;
        }
    }
    $bp->zatvoriDB();
    return $tocna;
}

function aktiviranKorisnik() {
    global $ispis;
    $korIme = $_POST["korime"];
    $aktiviran = false;
    require_once("dohvati_virt_vrijeme.php");
    require_once("baza_class.php");
    $bp = new Baza();
    $sql1 = "SELECT aktiviran FROM korisnici " .
            "WHERE korisnicko_ime = '" . $korIme . "'";
    $sql2 = "INSERT INTO dnevnik VALUES (DEFAULT, " .
            "'prijava', 'neuspješna', '" . date("Y-m-d H:i:s", strval(intval(time()) + pomak())) .
            "', '" . $korIme . "')";
    $bp->spojiDB();
    $rs = $bp->selectDB($sql1);
    if ($bp->pogreskaDB()) {
        exit;
    }
    while (list($aktiviran_korisnik) = $rs->fetch_array()) {
        if($aktiviran_korisnik === "DA") {
            $aktiviran = true;
        }
    }
    $rs->close();
    if(!$aktiviran) {
        $ispis .= "Vaš korisnički račun nije aktiviran! Morate aktivirati svoj " .
                "korisnički račun kako biste se mogli prijaviti u sustav.<br>";
        $bp->updateDB($sql2);
        if ($bp->pogreskaDB()) {
            exit;
        }
    }
    $bp->zatvoriDB();
    return $aktiviran;
}

function neuspjesnePrijave() {
    global $ispis;
    $korIme = $_POST["korime"];
    $status = false;
    require_once("dohvati_virt_vrijeme.php");
    require_once("baza_class.php");
    $bp = new Baza();
    $sql1 = "SELECT br_neuspj_prijava FROM korisnici " .
            "WHERE korisnicko_ime = '" . $korIme . "'";
    $sql2 = "INSERT INTO dnevnik VALUES (DEFAULT, " .
            "'prijava', 'neuspješna', '" . date("Y-m-d H:i:s", strval(intval(time()) + pomak())) .
            "', '" . $korIme . "')";
    $bp->spojiDB();
    $rs = $bp->selectDB($sql1);
    if ($bp->pogreskaDB()) {
        exit;
    }
    while (list($neuspj_prijave) = $rs->fetch_array()) {
        if($neuspj_prijave <= 3) {
            $status = true;
        }
    }
    $rs->close();
    if(!$status) {
        $ispis .= "Vaš korisnički račun je zaključan! Kontaktirajte administratora " .
                "kako biste otključali korisnički račun.<br>";
        $bp->updateDB($sql2);
        if ($bp->pogreskaDB()) {
            exit;
        }
    }
    $bp->zatvoriDB();
    return $status;
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $ispis = "";
    $pogreska = false;
    if(!provjeriKorisnickoIme()) {
        $pogreska = true;
    }
    else if(!postojiKorisnickoIme()) {
        $pogreska = true;
    }
    if(!provjeriLozinku()) {
        $pogreska = true;
    }
    if(!$pogreska) {
        if(!neuspjesnePrijave()) {
            $pogreska = true;
        }
        else if(!tocnaLozinka()) {
            $pogreska = true;
        }
        else if(!aktiviranKorisnik()) {
            $pogreska = true;
        }
    }
    if($pogreska) {
        echo $ispis;
    }
    else {
        $korIme = $_POST["korime"];
        require_once("dohvati_virt_vrijeme.php");
        require_once("baza_class.php");
        $bp = new Baza();
        $sql1 = "SELECT tip FROM tip_korisnika, korisnici " .
                "WHERE tip_korisnika.id = korisnici.tip_korisnika_id " .
                "AND korisnici.korisnicko_ime = '" . $korIme . "'";
        $sql2 = "UPDATE korisnici SET br_neuspj_prijava = 0 " .
                "WHERE korisnicko_ime = '" . $korIme . "'";
        $sql3 = "INSERT INTO dnevnik VALUES (DEFAULT, " .
                "'prijava', 'uspješna', '" . date("Y-m-d H:i:s", strval(intval(time()) + pomak())) .
                "', '" . $korIme . "')";
        $bp->spojiDB();
        $rs = $bp->selectDB($sql1);
        if ($bp->pogreskaDB()) {
            exit;
        }
        while (list($tip) = $rs->fetch_array()) {
            $tip_korisnika = $tip;
        }
        $rs->close();
        $bp->updateDB($sql2);
        if ($bp->pogreskaDB()) {
            exit;
        }
        $bp->updateDB($sql3);
        if ($bp->pogreskaDB()) {
            exit;
        }
        $bp->zatvoriDB();
        $sesija_polje = array($korIme, $tip_korisnika);
        $_SESSION["prijava"] = $sesija_polje;
        if(isset($_POST["zapamti"])) {
            setcookie("korisnik", $korIme);
        }
        else {
            setcookie("korisnik", "", time() - 3600);
        }
        header("Location: index.php");
        exit;
    }
}

$smarty->display('./templates/prijava_2.tpl');

?>