$(document).ready(function() {
    $.ajax({
        async: false,
        url: "dohvati_stranicenje.php",
        method: "GET",
        dataType: "json",
        success: function(rezultat) {
            $("#stranicenje_redovi").val(rezultat);
        }
    });
    
    $("#spremi").click(function() {
        $("#poruka").empty();
        var stranicenje = $("#stranicenje_redovi").val();
        
        $.ajax({
            async: false,
            url: "pohrani_stranicenje.php",
            method: "GET",
            data: {"stranicenje": stranicenje},
            dataType: "json",
            success: function(status) {
                if(status === "uspjeh") {
                    $("#poruka").html($("#poruka").html() + "Uspješno ste pohranili broj redaka kod straničenja.<br>");
                }
                else {
                    $("#poruka").html($("#poruka").html() + "Pojavila se pogreška prilikom pohrane broja redaka kod straničenja!<br>");
                }
            }
        });
    });
});