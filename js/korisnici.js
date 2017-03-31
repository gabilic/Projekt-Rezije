function ispis() {
    $.ajax({
        async: false,
        url: "../ispisi_korisnike.php",
        method: "GET",
        dataType: "json",
        success: function(tablica) {
            $("#ispis").append(tablica);
        }
    });
}

$(document).ready(function() {
    ispis();
});