<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

include_once "koristi_http.php";

if(isset($_SESSION["prijava"])) {
    $korIme = $_SESSION["prijava"][0];
    require_once("dohvati_virt_vrijeme.php");
    require_once("baza_class.php");
    $bp = new Baza();
    $sql = "INSERT INTO dnevnik VALUES (DEFAULT, " .
            "'posjećenost', 'Rad s korisnicima', '" .
            date("Y-m-d H:i:s", strval(intval(time()) + pomak())) .
            "', '" . $korIme . "')";
    $bp->spojiDB();
    $bp->updateDB($sql);
    if ($bp->pogreskaDB()) {
        exit;
    }
    $bp->zatvoriDB();
}

$naslov = "Rad s korisnicima";
$template = "rad_s_korisnicima";
include "smarty.php";

?>