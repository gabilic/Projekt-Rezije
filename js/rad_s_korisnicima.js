function puni_otkljucavanje() {
    $.ajax({
        async: false,
        url: "ispisi_rad_s_korisnicima.php",
        method: "GET",
        data: {"opcija": "otkljucavanje"},
        dataType: "json",
        success: function(rezultat) {
            for(var i = 0; i < rezultat.length; i++) {
                $("#otkljucavanje").append('<option value="' + (i + 1) + '">' + rezultat[i].ime + "</option>");
            }
        }
    });
}

function puni_blokiranje() {
    $.ajax({
        async: false,
        url: "ispisi_rad_s_korisnicima.php",
        method: "GET",
        data: {"opcija": "blokiranje"},
        dataType: "json",
        success: function(rezultat) {
            for(var i = 0; i < rezultat.length; i++) {
                $("#blokiranje").append('<option value="' + (i + 1) + '">' + rezultat[i].ime + "</option>");
            }
        }
    });
}

$(document).ready(function() {
    puni_otkljucavanje();
    puni_blokiranje();
    
    $("#otkljucaj").click(function() {
        if($("#otkljucavanje").val() > 0) {
            $.ajax({
                async: false,
                url: "otkljucaj_korisnika.php",
                method: "GET",
                data: {"korisnik": $("#otkljucavanje option:selected").text()},
                dataType: "json",
                success: function(status) {
                    if(status === "uspjeh") {
                        $("#poruka").html("");
                        $("#poruka").append("Korisnički račun je uspješno otključan.");
                    }
                }
            });
            $("#otkljucavanje").html("");
            $("#otkljucavanje").append('<option value="-1">Korisnici</option>');
            $("#otkljucavanje").append('<option value="0">===========</option>');
            $("#blokiranje").html("");
            $("#blokiranje").append('<option value="-1">Korisnici</option>');
            $("#blokiranje").append('<option value="0">===========</option>');
            puni_otkljucavanje();
            puni_blokiranje();
        }
    });

    $("#blokiraj").click(function() {
        if($("#blokiranje").val() > 0) {
            $.ajax({
                async: false,
                url: "blokiraj_korisnika.php",
                method: "GET",
                data: {"korisnik": $("#blokiranje option:selected").text()},
                dataType: "json",
                success: function(status) {
                    if(status === "uspjeh") {
                        $("#poruka").html("");
                        $("#poruka").append("Korisnički račun je uspješno blokiran.");
                    }
                }
            });
            $("#otkljucavanje").html("");
            $("#otkljucavanje").append('<option value="-1">Korisnici</option>');
            $("#otkljucavanje").append('<option value="0">===========</option>');
            $("#blokiranje").html("");
            $("#blokiranje").append('<option value="-1">Korisnici</option>');
            $("#blokiranje").append('<option value="0">===========</option>');
            puni_otkljucavanje();
            puni_blokiranje();
        }
    });
});