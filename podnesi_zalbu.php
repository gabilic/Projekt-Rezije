<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

if(isset($_POST["potvrdi"])) {
    if(isset($_FILES["datoteka"])) {
        if(!($_FILES["datoteka"]["error"] > 0)) {
            move_uploaded_file($_FILES["datoteka"]["tmp_name"], "img/zalbe/" . $_FILES["datoteka"]["name"]);
            $id = preg_replace("/[^0-9]/", "", $_POST["racuni"]);
            $opis = $_POST["opis"];
            $slika = $_FILES["datoteka"]["name"];
            $oznaka = $_POST["oznaka"];
            require_once("baza_class.php");
            $bp = new Baza();
            $bp->spojiDB();
            $sql1 = "INSERT INTO zalba VALUES (" . $id . ", '" .
                    $opis . "', '" . $slika . "', '" . $oznaka . "', NULL)";
            $bp->updateDB($sql1);
            if ($bp->pogreskaDB()) {
                $status = "neuspjeh";
                exit;
            }
            if(isset($_SESSION["prijava"])) {
                $korIme = $_SESSION["prijava"][0];
                require_once("dohvati_virt_vrijeme.php");
                $sql2 = "INSERT INTO dnevnik VALUES (DEFAULT, " .
                        "'upit', '" . str_replace("'", "''", $sql1) . "', '" .
                        date("Y-m-d H:i:s", strval(intval(time()) + pomak())) .
                        "', '" . $korIme . "')";
                $bp->updateDB($sql2);
                if ($bp->pogreskaDB()) {
                    exit;
                }
            }
            $bp->zatvoriDB();
        }
    }
}

header("Location: korisnik_zalba.php");

?>