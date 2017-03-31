function puni_ustanove() {
    $.ajax({
        async: false,
        url: "ispisi_ustanove_poslovnica.php",
        method: "GET",
        dataType: "json",
        success: function(rezultat) {
            for(var i = 0; i < rezultat.length; i++) {
                $("#ustanove").append('<option value="' + (i + 1) + '">' + rezultat[i].ustanova + "</option>");
            }
        }
    });
}

function puni_ocitavanje() {
    $("#ocitavanje").html("");
    $("#ocitavanje").append('<option value="-1">Kategorija</option>');
    $("#ocitavanje").append('<option value="0">===========</option>');
    $.ajax({
        async: false,
        url: "ispisi_ocitavanja_ustanove.php",
        method: "GET",
        data: {"ustanova": $("#ustanove option:selected").text()},
        dataType: "json",
        success: function(rezultat) {
            for(var i = 0; i < rezultat.length; i++) {
                $("#ocitavanje").append('<option value="' + (i + 1) + '">' + rezultat[i].ocitavanje + "</option>");
            }
        }
    });
}

function unesi_cijenu_usluge() {
    $.ajax({
        async: false,
        url: "cijena_po_kategoriji.php",
        method: "GET",
        data: {"ustanova": $("#ustanove option:selected").text(),
            "ocitavanje": $("#ocitavanje option:selected").text()},
        dataType: "json",
        success: function(cijena_usluge) {
            $("#cijena").val(cijena_usluge);
        }
    });
}

function prikazi_lajkove(osvjezi) {
    osvjezi = osvjezi || 0;
    $.ajax({
        async: false,
        url: "lajk_prikaz.php",
        method: "GET",
        data: {"ustanova": $("#ustanove option:selected").text(),
            "ocitavanje": $("#ocitavanje option:selected").text()},
        dataType: "json",
        success: function(rezultat) {
            if(rezultat[0].lajk === "0") {
                if(osvjezi === 0) {
                    $("#lajk_ustanova").html("");
                    $("#lajk_ustanova").append('<a href="#" rel="ustanova">Sviđa mi se</a>');
                }
                else {
                    $("#lajk_ustanova a").html("");
                    $("#lajk_ustanova a").append("Sviđa mi se");
                }
            }
            else {
                if(osvjezi === 0) {
                    $("#lajk_ustanova").html("");
                    $("#lajk_ustanova").append('<a href="#" rel="ustanova">Ne sviđa mi se</a>');
                }
                else {
                    $("#lajk_ustanova a").html("");
                    $("#lajk_ustanova a").append("Ne sviđa mi se");
                }
            }
            if(rezultat[1].lajk === "0") {
                if(osvjezi === 0) {
                    $("#lajk_ocitavanje").html("");
                    $("#lajk_ocitavanje").append('<a href="#" rel="ocitavanje">Sviđa mi se</a>');
                }
                else {
                    $("#lajk_ocitavanje a").html("");
                    $("#lajk_ocitavanje a").append("Sviđa mi se");
                }
            }
            else {
                if(osvjezi === 0) {
                    $("#lajk_ocitavanje").html("");
                    $("#lajk_ocitavanje").append('<a href="#" rel="ocitavanje">Ne sviđa mi se</a>');
                }
                else {
                    $("#lajk_ocitavanje a").html("");
                    $("#lajk_ocitavanje a").append("Ne sviđa mi se");
                }
            }
            if($("#ustanove").val() > 0) {
                $("#lajk_ustanova a").show();
                $("#prijelom1").hide();
            }
            else {
                $("#lajk_ustanova a").hide();
                $("#prijelom1").show();
            }
            if($("#ocitavanje").val() > 0) {
                $("#lajk_ocitavanje a").show();
                $("#prijelom2").hide();
            }
            else {
                $("#lajk_ocitavanje a").hide();
                $("#prijelom2").show();
            }
        }
    });
}

function lajkaj_ustanovu(element) {
    var lajk = element.html();
    $.ajax({
        async: false,
        url: "lajkaj_ustanovu.php",
        method: "GET",
        data: {"ustanova": $("#ustanove option:selected").text(),
            "lajk": lajk},
        dataType: "json",
        success: function(status) {
            if(status === "uspjeh") {
                if(lajk === "Sviđa mi se") {
                    element.html("");
                    element.append("Ne sviđa mi se");
                }
                else {
                    element.html("");
                    element.append("Sviđa mi se");
                }
            }
        }
    });
}

function lajkaj_ocitavanje(element) {
    var lajk = element.html();
    $.ajax({
        async: false,
        url: "lajkaj_ocitavanje.php",
        method: "GET",
        data: {"ustanova": $("#ustanove option:selected").text(),
            "ocitavanje": $("#ocitavanje option:selected").text(),
            "lajk": lajk},
        dataType: "json",
        success: function(status) {
            if(status === "uspjeh") {
                if(lajk === "Sviđa mi se") {
                    element.html("");
                    element.append("Ne sviđa mi se");
                }
                else {
                    element.html("");
                    element.append("Sviđa mi se");
                }
            }
        }
    });
}

function ispis() {
    $.ajax({
        async: false,
        url: "ispisi_rezervacije_korisnika.php",
        method: "GET",
        dataType: "json",
        success: function(tablica) {
            $("#poruka").html("");
            $("#poruka").append(tablica);
        }
    });
}

function initMap() {
    var mapDiv = document.getElementById("poruka");
    var map = new google.maps.Map(mapDiv, {
        center: {lat: 44.540, lng: -78.546},
        zoom: 8
    });
}

$(document).ready(function() {
    puni_ustanove();
    puni_ocitavanje();
    unesi_cijenu_usluge();
    prikazi_lajkove();
    
    $("#ustanove").change(function() {
        puni_ocitavanje();
        unesi_cijenu_usluge();
        prikazi_lajkove(1);
    });
    
    $("#ocitavanje").change(function() {
        unesi_cijenu_usluge();
        prikazi_lajkove(1);
    });
    
    $("#potvrdi").click(function() {
        if($("#ustanove").val() > 0 && $("#ocitavanje").val() > 0) {
            $.ajax({
                async: false,
                url: "rezerviraj_ocitavanje_potrosnje.php",
                method: "GET",
                data: {"ustanova": $("#ustanove option:selected").text(),
                    "ocitavanje": $("#ocitavanje option:selected").text(),
                    "stanje": $("#stanje").val()},
                dataType: "json",
                success: function(status) {
                    if(status === "uspjeh") {
                        $("#poruka").html("");
                        $("#poruka").append("Uspješno ste rezervirali očitavanje potrošnje.");
                    }
                }
            });
        }
        else {
            alert("Odaberite ustanovu i/ili očitavanje kako biste izvršili rezervaciju!");
        }
    });
    
    $("#ispis").click(function() {
        ispis();
        pokreniStranicenje();
        pokreniPretrazivanje();
    });
    
    $("a").click(function() {
        if($(this).attr("rel") === "ustanova") {
            lajkaj_ustanovu($(this));
        }
        else if($(this).attr("rel") === "ocitavanje") {
            lajkaj_ocitavanje($(this));
        }
    });
});