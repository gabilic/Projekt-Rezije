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
                $sql1 = "INSERT INTO poslovnica VALUES (DEFAULT, '" .
                        $redak[0] . "', '" . $redak[1] . "', '" . $redak[2] . "', '" .
                        $redak[3] . "', (SELECT id FROM ustanova WHERE naziv = '" . $redak[4] . "'))";
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
            }
            $bp->zatvoriDB();
        }
    }
}

header("Location: adrese_poslovnica.php");

?>