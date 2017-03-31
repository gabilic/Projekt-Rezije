function puni_zalbe() {
    $.ajax({
        async: false,
        url: "ispisi_zalbe.php",
        method: "GET",
        dataType: "json",
        success: function(rezultat) {
            for(var i = 0; i < rezultat.length; i++) {
                $("#zalbe").append('<option value="' + (i + 1) + '">' + rezultat[i].zalba + "</option>");
            }
        }
    });
}

function puni_rezervacije() {
    $("#rezervacije").append('<option value="-1">Rezervacija</option><option value="0">===========</option>');
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
        url: "potrosnja_update.php",
        method: "GET",
        data: {"rezervacija": $("#rezervacije option:selected").text(),
            "zalba": $("#zalbe option:selected").text()},
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

function ispis() {
    $.ajax({
        async: false,
        url: "ispisi_odabranu_zalbu.php",
        method: "GET",
        data: {"zalba": $("#zalbe option:selected").text()},
        dataType: "json",
        success: function(tablica) {
            $("#poruka").html("");
            $("#poruka").append(tablica);
        }
    });
}

$(document).ready(function() {
    puni_zalbe();
    
    $("#potvrdi").click(function() {
        if($("#zalbe").val() > 0) {
            $("#poruka").html("");
            $("#poruka").append('<form><label>Datum i vrijeme: </label><input id="datum_i_vrijeme" disabled><br><br>');
            $("#poruka").append('<label>Naziv usluge očitavanja: </label><input id="usluga" disabled><br><br>');
            $("#poruka").append('<label>Cijena usluge očitavanja: </label><input id="cijena" disabled><br><br>');
            $("#poruka").append('<label>Očitano stanje: </label><input id="stanje" disabled><br><br>');
            $("#poruka").append('<label>Potrošnja: </label><input id="potrosnja" disabled><br><br><label>Ime djelatnika: </label>');
            $("#poruka").append('<input id="djelatnik" disabled><br><br><label>Ime korisnika: </label>');
            $("#poruka").append('<input id="korisnik" disabled><br><br><label>Broj rezervacije: </label><select id="rezervacije">');
            $("#poruka").append('<option value="-1">Rezervacija</option><option value="0">===========</option></select><br><br>');
            $("#poruka").append('<button type="button" id="spremi">Izdaj račun</button><br><br></form><br>');
            
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
                        url: "izdaj_racun_update.php",
                        method: "GET",
                        data: {"datum_i_vrijeme": $("#datum_i_vrijeme").val(),
                            "usluga": $("#usluga").val(),
                            "cijena": $("#cijena").val(),
                            "stanje": $("#stanje").val(),
                            "potrosnja": $("#potrosnja").val(),
                            "djelatnik": $("#djelatnik").val(),
                            "rezervacija": $("#rezervacije option:selected").text(),
                            "zalba": $("#zalbe option:selected").text()},
                        dataType: "json",
                        success: function(status) {
                            if(status === "uspjeh") {
                                $("#poruka").html("");
                                $("#poruka").append("Uspješno ste izdali račun.");
                                $.ajax({
                                    async: false,
                                    url: "potvrdi_zalbu.php",
                                    method: "GET",
                                    data: {"zalba": $("#zalbe option:selected").text()},
                                    dataType: "json"
                                });
                                $("#zalbe").html("");
                                $("#zalbe").append('<option value="-1">Žalbe</option>');
                                $("#zalbe").append('<option value="0">===========</option>');
                                puni_zalbe();
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
        }
        else {
            alert("Odaberite žalbu za koju želite ispostaviti novi račun!");
        }
    });
    
    $("#odbij").click(function() {
        if($("#zalbe").val() > 0) {
            $.ajax({
                async: false,
                url: "odbij_zalbu.php",
                method: "GET",
                data: {"zalba": $("#zalbe option:selected").text()},
                dataType: "json",
                success: function(status) {
                    if(status === "uspjeh") {
                        $("#poruka").html("");
                        $("#poruka").append("Žalba je odbijena.");
                    }
                }
            });
            $("#zalbe").html("");
            $("#zalbe").append('<option value="-1">Žalbe</option>');
            $("#zalbe").append('<option value="0">===========</option>');
            puni_zalbe();
        }
        else {
            alert("Odaberite žalbu koju želite odbiti!");
        }
    });
    
    $("#ispis").click(function() {
        if($("#zalbe").val() > 0) {
            ispis();
        }
        else {
            alert("Odaberite žalbu iz popisa žalbi!");
        }
    });
});