function pokreniPretrazivanje() {
    $("table").before('<div id="pretr"></div>');
    $("#pretr").append('<label>Pretraživanje tablice:</label><input id="vrijednost"><button id="trazi">Pretraži</button>');
    $("#pretr input").css("margin", "auto 15px 15px 15px");
    var tablica = $("table").html();
    var tablica_temp = "<table>" + tablica + "</table";
    $("#trazi").click(function() {
        $("table").remove();
        $("#pretr").after(tablica_temp);
        var temp = "<table><caption>" + $("table caption").html() + "</caption>";
        temp += "<thead>" + $("table thead").html() + "</thead><tbody>";
        if($("#vrijednost").val() !== "") {
            var ukupnoRedova = $("table tbody tr").length;
            var ukupnoStupaca = $("table tbody tr td").length / ukupnoRedova;
            for(var i = 0; i < ukupnoRedova; i++) {
                for(var j = 0; j < ukupnoStupaca; j++) {
                    var vrijednost = $("table tbody tr").slice(i, i + 1).find("td").slice(j, j + 1).html();
                    if(vrijednost.indexOf($("#vrijednost").val()) >= 0) {
                        temp += "<tr>";
                        for(var j = 0; j < ukupnoStupaca; j++) {
                            temp += "<td>" + $("table tbody tr").slice(i, i + 1).find("td").slice(j, j + 1).html(); + "</td>";
                        }
                        temp += "</tr>";
                    }
                }
            }
            temp += "</tbody></table>";
            $("table").remove();
            $("#pretr").after(temp);
        }
        prikazStranica(parseInt($("#unos").val()));
        if($("#unos").val() !== "") {
            prikazStranica(parseInt($("#unos").val()));
        }
    });
}

$(document).ready(function() {
    pokreniPretrazivanje();
});