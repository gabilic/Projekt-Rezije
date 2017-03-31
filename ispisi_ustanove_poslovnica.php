<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

require_once("baza_class.php");
$bp = new Baza();
$sql = "SELECT naziv FROM ustanova";
if(isset($_SESSION["prijava"]) && ($_SESSION["prijava"][1] === "administrator") || ($_SESSION["prijava"][1] === "potrošač")) {
    $sql = "SELECT naziv FROM ustanova";
}
else if(isset($_SESSION["prijava"]) && $_SESSION["prijava"][1] === "zaposlenik") {
    $sql = "SELECT naziv FROM ustanova WHERE id = " .
            "(SELECT ustanova_id FROM korisnici WHERE korisnicko_ime = '" . $_SESSION["prijava"][0] . "')";
}
$bp->spojiDB();
$rs = $bp->selectDB($sql);
if ($bp->pogreskaDB()) {
    exit;
}
$rezultat = "[";
while (list($ustanova) = $rs->fetch_array()) {
    $lista[] = '{"ustanova":"' . $ustanova . '"}';
}
$rezultat .= join(",", $lista);
$rezultat .= "]";
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

echo $rezultat;

?>