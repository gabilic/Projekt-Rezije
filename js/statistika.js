function ispis(parametar) {
    parametar = parametar || 0;
    $.ajax({
        async: false,
        url: "ispisi_dnevnik.php",
        method: "GET",
        dataType: "json",
        success: function(tablica) {
            if(parametar === 0) {
                $("#ispis").append(tablica);
            }
            else {
                $("#pretr").after(tablica);
            }
        }
    });
}

$(document).ready(function() {
    ispis();
});

$("#posjecenost").click(function() {
    $("#platno").remove();
    $("#ispis").append('<canvas id="platno" width="600" height="400"></canvas>');
    var ctx = document.getElementById("platno").getContext("2d");
    ctx.fillStyle = "rgb(255, 255, 255)";
    ctx.strokeRect(40, 0, 320, 400);
    for(var i = 0; i < 7; i++) {
        var d = Math.round(Math.random() * 400);
        var c = Math.round(Math.random() * 255);
        var z = Math.round(Math.random() * 255);
        var p = Math.round(Math.random() * 255);
        var boja = "rgb(" + c + ", " + z + ", " + p + ")";
        ctx.fillStyle = boja;
        ctx.fillRect(100 + 40 * (i - 1), 400 - d, 38, 400);
    }
});

$("#upiti").click(function() {
    window.open("http://barka.foi.hr/WebDiP/pomak_vremena/vrijeme.html", "_blank");
});