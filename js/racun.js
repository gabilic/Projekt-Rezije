function puni_rezervacije() {
    $.ajax({
        async: false,
        url: "ispisi_rezervacije_ocitavanja.php",
        method: "GET",
        data: {"opcija": "prihvacene_rezervacije"},
        dataType: "json",
        success: function(rezultat) {
            for(var i = 0; i < rezultat.length; i++) {
                $("#rezervacije").append('<option value="' + (i + 1) + '">' + rezultat[i].rezervacija + "</option>");
            }
        }
    });
}

function ispis() {
    $.ajax({
        async: false,
        url: "ispisi_racune.php",
        method: "GET",
        data: {"opcija": "zaposlenik",
            "racun": "0"},
        dataType: "json",
        success: function(tablica) {
            $("#poruka").html("");
            $("#poruka").append(tablica);
        }
    });
}

function dohvati_datum_i_vrijeme() {
    $.ajax({
        async: false,
        url: "datum_i_vrijeme.php",
        method: "GET",
        dataType: "json",
        success: function(datum_i_vrijeme) {
            $("#datum_i_vrijeme").val(datum_i_vrijeme);
        }
    });
}

function unesi_uslugu() {
    $.ajax({
        async: false,
        url: "usluga_ocitavanja.php",
        method: "GET",
        data: {"rezervacija": $("#rezervacije option:selected").text()},
        dataType: "json",
        success: function(usluga) {
            $("#usluga").val(usluga);
        }
    });
}

function unesi_cijenu_usluge() {
    $.ajax({
        async: false,
        url: "cijena_usluge_ocitavanja.php",
        method: "GET",
        data: {"rezervacija": $("#rezervacije option:selected").text()},
        dataType: "json",
        success: function(cijena_usluge) {
            $("#cijena").val(cijena_usluge);
        }
    });
}

function unesi_ocitano_stanje() {
    $.ajax({
        async: false,
        url: "ocitano_stanje.php",
        method: "GET",
        data: {"rezervacija": $("#rezervacije option:selected").text()},
        dataType: "json",
        success: function(ocitano_stanje) {
            $("#stanje").val(ocitano_stanje);
        }
    });
}

function unesi_potrosnju() {
    $.ajax({
        async: false,
        url: "potrosnja.php",
        method: "GET",
        data: {"rezervacija": $("#rezervacije option:selected").text()},
        dataType: "json",
        success: function(potrosnja) {
            $("#potrosnja").val(potrosnja);
        }
    });
}

function unesi_ime_djelatnika() {
    $.ajax({
        async: false,
        url: "ocitano_stanje_djelatnik.php",
        method: "GET",
        dataType: "json",
        success: function(ime_djelatnika) {
            $("#djelatnik").val(ime_djelatnika);
        }
    });
}

function unesi_ime_korisnika() {
    $.ajax({
        async: false,
        url: "ocitano_stanje_korisnik.php",
        method: "GET",
        data: {"rezervacija": $("#rezervacije option:selected").text()},
        dataType: "json",
        success: function(ime_korisnika) {
            $("#korisnik").val(ime_korisnika);
        }
    });
}

$(document).ready(function() {
    puni_rezervacije();
    dohvati_datum_i_vrijeme();
    unesi_uslugu();
    unesi_cijenu_usluge();
    unesi_ocitano_stanje();
    unesi_potrosnju();
    unesi_ime_djelatnika();
    unesi_ime_korisnika();
    
    $("#rezervacije").change(function() {
        dohvati_datum_i_vrijeme();
        unesi_uslugu();
        unesi_cijenu_usluge();
        unesi_ocitano_stanje();
        unesi_potrosnju();
        unesi_ime_korisnika();
    });
    
    $("#spremi").click(function() {
        if($("#rezervacije").val() > 0) {
            $.ajax({
                async: false,
                url: "izdaj_racun.php",
                method: "GET",
                data: {"datum_i_vrijeme": $("#datum_i_vrijeme").val(),
                    "usluga": $("#usluga").val(),
                    "cijena": $("#cijena").val(),
                    "stanje": $("#stanje").val(),
                    "potrosnja": $("#potrosnja").val(),
                    "djelatnik": $("#djelatnik").val(),
                    "rezervacija": $("#rezervacije option:selected").text()},
                dataType: "json",
                success: function(status) {
                    if(status === "uspjeh") {
                        $("#poruka").html("");
                        $("#poruka").append("Uspješno ste izdali račun.");
                    }
                }
            });
            $("#rezervacije").html("");
            $("#rezervacije").append('<option value="-1">Rezervacija</option>');
            $("#rezervacije").append('<option value="0">===========</option>');
            puni_rezervacije();
        }
        else {
            alert("Odaberite rezervaciju kako biste izdali račun!");
        }
    });
    
    $("#ispis").click(function() {
        ispis();
        pokreniStranicenje();
        pokreniPretrazivanje();
    });
});