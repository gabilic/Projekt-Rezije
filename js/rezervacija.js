function puni_rezervacije() {
    $.ajax({
        async: false,
        url: "ispisi_rezervacije_ocitavanja.php",
        method: "GET",
        data: {"opcija": "nove_rezervacije"},
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
        url: "ispisi_rezervaciju.php",
        method: "GET",
        data: {"rezervacija": $("#rezervacije option:selected").text()},
        dataType: "json",
        success: function(tablica) {
            $("#poruka").html("");
            $("#poruka").append(tablica);
        }
    });
}

$(document).ready(function() {
    puni_rezervacije();
    
    $("#potvrdi").click(function() {
        if($("#rezervacije").val() > 0) {
            $.ajax({
                async: false,
                url: "potvrdi_rezervaciju.php",
                method: "GET",
                data: {"rezervacija": $("#rezervacije option:selected").text()},
                dataType: "json",
                success: function(status) {
                    if(status === "uspjeh") {
                        $("#poruka").html("");
                        $("#poruka").append("Rezervacija je potvrđena.");
                    }
                }
            });
            $("#rezervacije").html("");
            $("#rezervacije").append('<option value="-1">Rezervacije</option>');
            $("#rezervacije").append('<option value="0">===========</option>');
            puni_rezervacije();
        }
        else {
            alert("Odaberite rezervaciju koju želite potvrditi!");
        }
    });
    
    $("#odbij").click(function() {
        if($("#rezervacije").val() > 0) {
            $.ajax({
                async: false,
                url: "odbij_rezervaciju.php",
                method: "GET",
                data: {"rezervacija": $("#rezervacije option:selected").text()},
                dataType: "json",
                success: function(status) {
                    if(status === "uspjeh") {
                        $("#poruka").html("");
                        $("#poruka").append("Rezervacija je odbijena.");
                    }
                }
            });
            $("#rezervacije").html("");
            $("#rezervacije").append('<option value="-1">Rezervacije</option>');
            $("#rezervacije").append('<option value="0">===========</option>');
            puni_rezervacije();
        }
        else {
            alert("Odaberite rezervaciju koju želite odbiti!");
        }
    });
    
    $("#ispis").click(function() {
        if($("#rezervacije").val() > 0) {
            ispis();
        }
        else {
            alert("Morate odabrati rezervaciju iz liste rezervacija!");
        }
    });
});