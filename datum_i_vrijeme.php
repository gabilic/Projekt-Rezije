<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

require_once("dohvati_virt_vrijeme.php");
$datum_i_vrijeme = date("d.m.Y H:i:s", strval(intval(time()) + pomak()));

echo json_encode($datum_i_vrijeme);

?>