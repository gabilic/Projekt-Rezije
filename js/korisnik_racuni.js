function ispis() {
    $.ajax({
        async: false,
        url: "ispisi_racune.php",
        method: "GET",
        data: {"opcija": "potrošač",
            "racun": "0"},
        dataType: "json",
        success: function(tablica) {
            $("#ispis").append(tablica);
        }
    });
}

$(document).ready(function() {
    ispis();
});