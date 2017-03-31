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

function ispis() {
    $.ajax({
        async: false,
        url: "ispisi_ocitavanje.php",
        method: "GET",
        dataType: "json",
        success: function(tablica) {
            $("#poruka").html("");
            $("#poruka").append(tablica);
        }
    });
}

$(document).ready(function() {
    puni_ustanove();
    
    $("#spremi").click(function() {
        if($("#ustanove").val() > 0) {
            $.ajax({
                async: false,
                url: "definiraj_ocitavanje.php",
                method: "GET",
                data: {"kategorija": $("#kategorija").val(),
                    "cijena": $("#cijena").val(),
                    "ustanova": $("#ustanove option:selected").text()},
                dataType: "json",
                success: function(status) {
                    if(status === "uspjeh") {
                        $("#poruka").html("");
                        $("#poruka").append("Uspješno ste definirali kategoriju očitavanja i cijenu usluge očitavanja.");
                    }
                }
            });
            $("#ustanove").html("");
            $("#ustanove").append('<option value="-1">Ustanove</option>');
            $("#ustanove").append('<option value="0">===========</option>');
            puni_ustanove();
        }
        else {
            alert("Odaberite ustanovu za koju želite definirati kategorije očitavanja i cijenu usluge očitavanja!");
        }
    });
    
    $("#ispis").click(function() {
        ispis();
        pokreniStranicenje();
        pokreniPretrazivanje();
    });
});