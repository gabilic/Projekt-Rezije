function puni_ustanove() {
    $.ajax({
        async: false,
        url: "ispisi_ustanove_nereg.php",
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
        url: "nereg_ocitavanje.php",
        method: "GET",
        data: {"ustanova": $("#ustanove option:selected").text()},
        dataType: "json",
        success: function(tablica) {
            $("#poruka").html("");
            $("#poruka").append(tablica);
        }
    });
}

$(document).ready(function() {
    puni_ustanove();
    
    $("#ispis").click(function() {
        ispis();
        pokreniStranicenje();
        pokreniPretrazivanje();
    });
});