<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

function pomak() {
    require_once("baza_class.php");
    $bp = new Baza();
    $sql = "SELECT trajanje FROM virtualno_vrijeme";
    $bp->spojiDB();
    $rs = $bp->selectDB($sql);
    if ($bp->pogreskaDB()) {
        exit;
    }
    while (list($pomak_vremena) = $rs->fetch_array()) {
        $pomak = intval($pomak_vremena) * 3600;
    }
    $rs->close();
    if(isset($_SESSION["prijava"])) {
        $korIme = $_SESSION["prijava"][0];
        $sql2 = "INSERT INTO dnevnik VALUES (DEFAULT, " .
                "'upit', '" . $sql . "', '" .
                date("Y-m-d H:i:s", strval(intval(time()) + intval($pomak))) .
                "', '" . $korIme . "')";
        $bp->updateDB($sql2);
        if ($bp->pogreskaDB()) {
            exit;
        }
    }
    $bp->zatvoriDB();
    return $pomak;
}

?>