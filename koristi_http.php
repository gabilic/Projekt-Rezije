<?php

function koristiHttp() {
    $protokol = "https";
    if(!array_key_exists("HTTPS", $_SERVER) || $_SERVER["HTTPS"] === "off") {
        $protokol = "http";
    }
    if($protokol !== "http") {
        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        header("Location: $url");
    }
}

koristiHttp();

?>