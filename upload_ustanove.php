<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

if(isset($_POST["csv"])) {
    if(isset($_FILES["datoteka"])) {
        if(!($_FILES["datoteka"]["error"] > 0)) {
            move_uploaded_file($_FILES["datoteka"]["tmp_name"], "csv/" . $_FILES["datoteka"]["name"]);
            $csv = array_map(function($a) { return str_getcsv($a, ";"); }, file("csv/" . $_FILES["datoteka"]["name"]));
            require_once("baza_class.php");
            $bp = new Baza();
            $bp->spojiDB();
            foreach($csv as $redak) {
                $sql1 = "INSERT INTO ustanova VALUES (DEFAULT, '" .
                        $redak[0] . "', 0)";
                $sql2 = "UPDATE korisnici SET ustanova_id = (SELECT " .
                        "id FROM ustanova WHERE naziv = '" . $redak[0] . "'), " .
                        "tip_korisnika_id = 2 WHERE korisnicko_ime = '" . $redak[1] . "'";
                $bp->updateDB($sql1);
                if ($bp->pogreskaDB()) {
                    $status = "neuspjeh";
                    exit;
                }
                $bp->updateDB($sql2);
                if ($bp->pogreskaDB()) {
                    $status = "neuspjeh";
                    exit;
                }
                if(isset($_SESSION["prijava"])) {
                    $korIme = $_SESSION["prijava"][0];
                    require_once("dohvati_virt_vrijeme.php");
                    $sql3 = "INSERT INTO dnevnik VALUES (DEFAULT, " .
                            "'upit', '" . str_replace("'", "''", $sql1) . "', '" .
                            date("Y-m-d H:i:s", strval(intval(time()) + pomak())) .
                            "', '" . $korIme . "')";
                    $sql4 = "INSERT INTO dnevnik VALUES (DEFAULT, " .
                            "'upit', '" . str_replace("'", "''", $sql2) . "', '" .
                            date("Y-m-d H:i:s", strval(intval(time()) + pomak())) .
                            "', '" . $korIme . "')";
                    $bp->updateDB($sql3);
                    if ($bp->pogreskaDB()) {
                        exit;
                    }
                    $bp->updateDB($sql4);
                    if ($bp->pogreskaDB()) {
                        exit;
                    }
                }
            }
            $bp->zatvoriDB();
        }
    }
}

header("Location: ustanove.php");

?>