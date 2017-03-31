function ispis_za_ustanove() {
    $.ajax({
        async: false,
        url: "apl_stat_ustanove.php",
        method: "GET",
        dataType: "json",
        success: function(tablica) {
            $("#poruka").html("");
            $("#poruka").append(tablica);
        }
    });
}

function ispis_za_ocitavanje() {
    $.ajax({
        async: false,
        url: "apl_stat_ocitavanje.php",
        method: "GET",
        dataType: "json",
        success: function(tablica) {
            $("#poruka").html("");
            $("#poruka").append(tablica);
        }
    });
}

$(document).ready(function() {
    $("#ustanove").click(function() {
        ispis_za_ustanove();
        pokreniStranicenje();
        pokreniPretrazivanje();
    });
    
    $("#ocitavanje").click(function() {
        ispis_za_ocitavanje();
        pokreniStranicenje();
        pokreniPretrazivanje();
    });
});