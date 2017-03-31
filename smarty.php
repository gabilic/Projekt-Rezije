<?php

require "smarty/libs/Smarty.class.php";

$smarty = new Smarty();
$smarty->assign("naslov", $naslov);
if($template === "početna") {
    $smarty->display("./templates/index.tpl");
}
else if($template === "registracija") {
    $smarty->display("./templates/registracija_1.tpl");
}
else if($template === "prijava") {
    $smarty->assign("kolacic", $kolacic);
    $smarty->display("./templates/prijava_1.tpl");
}
else if($template === "aktivacija") {
    $smarty->assign("poruka", $poruka);
    $smarty->display("./templates/aktivacija.tpl");
}
else if($template === "zaboravljena lozinka") {
    $smarty->assign("poruka", $poruka);
    $smarty->display("./templates/zaboravljena_lozinka.tpl");
}
else if($template === "korisnici") {
    $smarty->display("../templates/korisnici.tpl");
}
else if($template === "vrijeme") {
    $smarty->display("./templates/virtualno_vrijeme.tpl");
}
else if($template === "dnevnik") {
    $smarty->display("./templates/dnevnik.tpl");
}
else if($template === "statistika") {
    $smarty->display("./templates/statistika.tpl");
}
else if($template === "rad_s_korisnicima") {
    $smarty->display("./templates/rad_s_korisnicima.tpl");
}
else if($template === "konfiguracija") {
    $smarty->display("./templates/konfiguracija.tpl");
}
else if($template === "ustanove") {
    $smarty->display("./templates/ustanove.tpl");
}
else if($template === "adrese_poslovnica") {
    $smarty->display("./templates/adrese_poslovnica.tpl");
}
else if($template === "ocitavanje") {
    $smarty->display("./templates/ocitavanje.tpl");
}
else if($template === "rezervacija") {
    $smarty->display("./templates/rezervacija.tpl");
}
else if($template === "racun") {
    $smarty->display("./templates/racun.tpl");
}
else if($template === "zalba") {
    $smarty->display("./templates/zalba.tpl");
}
else if($template === "aplikativna_statistika") {
    $smarty->display("./templates/aplikativna_statistika.tpl");
}
else if($template === "korisnik_rezervacija") {
    $smarty->display("./templates/korisnik_rezervacija.tpl");
}
else if($template === "korisnik_racuni") {
    $smarty->display("./templates/korisnik_racuni.tpl");
}
else if($template === "korisnik_zalba") {
    $smarty->display("./templates/korisnik_zalba.tpl");
}
else if($template === "neregistrirani_korisnik") {
    $smarty->display("./templates/neregistrirani_korisnik.tpl");
}

?>