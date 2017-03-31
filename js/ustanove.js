function puni_korisnike() {
    $.ajax({
        async: false,
        url: "ispisi_registrirane_korisnike.php",
        method: "GET",
        dataType: "json",
        success: function(rezultat) {
            for(var i = 0; i < rezultat.length; i++) {
                $("#korisnici").append('<option value="' + (i + 1) + '">' + rezultat[i].ime + "</option>");
            }
        }
    });
}

function ispis() {
    $.ajax({
        async: false,
        url: "ispisi_ustanove.php",
        method: "GET",
        dataType: "json",
        success: function(tablica) {
            $("#poruka").html("");
            $("#poruka").append(tablica);
        }
    });
}

$(document).ready(function() {
    puni_korisnike();
    
    $("#spremi").click(function() {
        if($("#korisnici").val() > 0) {
            $.ajax({
                async: false,
                url: "dodijeli_moderatora.php",
                method: "GET",
                data: {"ustanova": $("#naziv").val(),
                    "korisnik": $("#korisnici option:selected").text()},
                dataType: "json",
                success: function(status) {
                    if(status === "uspjeh") {
                        $("#poruka").html("");
                        $("#poruka").append("Uspješno ste kreirali ustanovu i dodijelili joj moderatora.");
                    }
                }
            });
            $("#korisnici").html("");
            $("#korisnici").append('<option value="-1">Korisnici</option>');
            $("#korisnici").append('<option value="0">===========</option>');
            puni_korisnike();
        }
        else {
            alert("Odaberite korisnika kojeg želite postaviti kao moderatora!");
        }
    });
    
    $("#ispis").click(function() {
        ispis();
        pokreniStranicenje();
        pokreniPretrazivanje();
    });
});