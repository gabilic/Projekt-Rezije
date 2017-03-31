$("#dohvati").click(function() {
    window.open("http://barka.foi.hr/WebDiP/pomak_vremena/vrijeme.html", "_blank");
});

$("#pohrani").click(function() {
    $("#poruka").empty();
    var vrijeme = 0;
    $.ajax({
        async: false,
        url: "http://barka.foi.hr/WebDiP/pomak_vremena/pomak.php",
        method: "GET",
        data: {"format": "json"},
        dataType: "json",
        success: function(status) {
            vrijeme = parseInt(status.WebDiP.vrijeme.pomak.brojSati);
        }
    });
    $.ajax({
        async: false,
        url: "pohrani_virt_vrijeme.php",
        method: "GET",
        data: {"vrijeme": vrijeme},
        dataType: "json",
        success: function(status) {
            if(status === "uspjeh") {
                $("#poruka").html($("#poruka").html() + "Uspješno ste pohranili pomak virtualnog vremena.<br>");
            }
            else {
                $("#poruka").html($("#poruka").html() + "Pojavila se pogreška prilikom pohrane pomaka virtualnog vremena!<br>");
            }
        }
    });
});