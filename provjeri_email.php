<?php

$email = $_POST["mail"];
$zauzeto = "";
require_once("baza_class.php");
$bp = new Baza();
$sql = "SELECT email FROM korisnici " .
        "WHERE email = '" . $email . "'";
$bp->spojiDB();
$rs = $bp->selectDB($sql);
if ($bp->pogreskaDB()) {
    exit;
}
while (list($mail) = $rs->fetch_array()) {
    if($mail === $email) {
        $zauzeto = "zauzeto";
    }
}
$rs->close();
$bp->zatvoriDB();
echo json_encode($zauzeto);

?>