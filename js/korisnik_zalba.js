function puni_racune() {
    $.ajax({
        async: false,
        url: "ispisi_racune_korisnika.php",
        method: "GET",
        dataType: "json",
        success: function(rezultat) {
            for(var i = 0; i < rezultat.length; i++) {
                $("#racuni").append('<option value="' + rezultat[i].racun + '">' + rezultat[i].racun + "</option>");
            }
        }
    });
}

function puni_oznake() {
    $.ajax({
        async: false,
        url: "ispisi_oznake.php",
        method: "GET",
        dataType: "json",
        success: function(rezultat) {
            for(var i = 0; i < rezultat.length; i++) {
                $("#oznake").append('<option value="' + (i + 1) + '">' + rezultat[i].oznaka + "</option>");
            }
        }
    });
}

function ispis() {
    $.ajax({
        async: false,
        url: "ispisi_racune.php",
        method: "GET",
        data: {"opcija": "potrošač_trenutni_račun",
            "racun": $("#racuni option:selected").text()},
        dataType: "json",
        success: function(tablica) {
            $("#poruka").html("");
            $("#poruka").append(tablica);
        }
    });
}

function prikazi_galeriju() {
    $.ajax({
        async: false,
        url: "prikazi_galeriju.php",
        method: "GET",
        data: {"oznaka": $("#oznake option:selected").text()},
        dataType: "json",
        success: function(galerija) {
            $("#poruka").html("");
            $("#poruka").append(galerija);
        }
    });
}

$(document).ready(function() {
    puni_racune();
    puni_oznake();
    
    $("#ispis").click(function() {
        ispis();
    });
    
    $("#galerija").click(function() {
        prikazi_galeriju();
    });
});