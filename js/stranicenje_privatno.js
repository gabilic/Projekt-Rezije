function prikazStranica(prikaz) {
    $("#str").remove();
    $("table").after('<div id="str"></div>');
    prikaz = prikaz || 15;
    var prikazaniRedovi = prikaz;
    var ukupnoRedova = $("table tbody tr").length;
    var brojStranica = ukupnoRedova / prikazaniRedovi;
    $("#str").append('<a href="#" rel="Prva">Prva</a>');
    for(var i = 0; i < brojStranica; i++) {
        var broj = i + 1;
        $("#str").append('<a href="#" rel="' + i + '">' + broj + "</a>");
    }
    $("#str").append('<a href="#" rel="Zadnja">Zadnja</a>');
    $("#str").css("margin", "15px auto auto");
    $("#str").css("float", "left");
    $("#str a").css("background-color", "#0D2839").css("color", "#BEE3F1");
    $("#str a").css("margin", "auto 3px auto auto").css("padding", "3px");
    $("#str a").mouseover(function() {
        $(this).css("text-decoration", "none");
        $(this).css("background-color", "#080D0F");
    });
    $("#str a").mouseout(function() {
        $(this).css("background-color", "#0D2839");
    });
    $("table tbody tr").hide();
    $("table tbody tr").slice(0, prikazaniRedovi).show();
    $("#str a").click(function() {
        if($(this).attr("rel") !== "Prva" && $(this).attr("rel") !== "Zadnja") {
            var stranica = $(this).attr("rel");
            var pocetak = stranica * prikazaniRedovi;
            var kraj = pocetak + prikazaniRedovi;
            $("table tbody tr").hide();
            $("table tbody tr").slice(pocetak, kraj).show();
        }
        else if($(this).attr("rel") === "Prva") {
            $("table tbody tr").hide();
            $("table tbody tr").slice(0, prikazaniRedovi).show();
        }
        else if($(this).attr("rel") === "Zadnja") {
            var stranica = $("#str a:nth-last-child(2)").attr("rel");
            var pocetak = stranica * prikazaniRedovi;
            var kraj = pocetak + prikazaniRedovi;
            $("table tbody tr").hide();
            $("table tbody tr").slice(pocetak, kraj).show();
        }
    });
}

function brojRedaka() {
    $("#str").after('<div id="red"></div>');
    $("#red").append('<label>Broj redaka:</label><input id="unos"><button id="redak">Postavi</button>');
    $("#red").css("margin", "15px auto auto");
    $("#red label").css("margin", "auto auto auto 35px");
    $("#red input").css("margin", "auto 10px auto 5px");
    $("#red input").attr("size", "3");
}

function dohvatiStranicenje() {
    var stranicenje = 0;
    $.ajax({
        async: false,
        url: "../dohvati_stranicenje.php",
        method: "GET",
        dataType: "json",
        success: function(rezultat) {
            stranicenje = rezultat;
        }
    });
    return stranicenje;
}

$(document).ready(function() {
    prikazStranica();
    brojRedaka();
    $("#unos").val(dohvatiStranicenje());
    prikazStranica(parseInt($("#unos").val()));
    $("#redak").click(function() {
        prikazStranica(parseInt($("#unos").val()));
    });
});