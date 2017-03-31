function kVeliko(korIme) {
    var brojac = 0;
    var znak = 0;
    for(var i = 0; i < korIme.length; i++) {
        znak = korIme.charCodeAt(i);
        if(znak >= 65 && znak <= 90) brojac++;
    }
    return brojac;
}

function kPoseban(korIme) {
    var brojac = 0;
    var znak = "";
    for(var i = 0; i < korIme.length; i++) {
        znak = korIme.charAt(i);
        switch(znak) {
            case "!": brojac++; break;
            case "#": brojac++; break;
            case "$": brojac++; break;
            case "?": brojac++; break;
        }
    }
    return brojac;
}

function lVeliko(lozinka) {
    var brojac = 0;
    var znak = 0;
    for(var i = 0; i < lozinka.length; i++) {
        znak = lozinka.charCodeAt(i);
        if(znak >= 65 && znak <= 90) brojac++;
    }
    return brojac;
}

function lMalo(lozinka) {
    var brojac = 0;
    var znak = 0;
    for(var i = 0; i < lozinka.length; i++) {
        znak = lozinka.charCodeAt(i);
        if(znak >= 97 && znak <= 122) brojac++;
    }
    return brojac;
}

function lBroj(lozinka) {
    var brojac = 0;
    var znak = 0;
    for(var i = 0; i < lozinka.length; i++) {
        znak = lozinka.charCodeAt(i);
        if(znak >= 48 && znak <= 57) brojac++;
    }
    return brojac;
}

function provjeriKorisnickoIme() {
    var korIme = $("#korime").val();
    var provjera = true;
    if($("#korime").attr("type") !== "text") {
        $("#pogreska").html($("#pogreska").html() + "Tip polja za unos korisničkog imena mora biti jednak tipu text!<br>");
        provjera = false;
    }
    if(korIme === "") {
        $("#pogreska").html($("#pogreska").html() + "Korisničko ime nije uneseno!<br>");
        provjera = false;
    }
    else {
        if(korIme.length < 8 || korIme.length > 16) {
            $("#pogreska").html($("#pogreska").html() + "Korisničko ime mora sadržavati minimalno 8, a maksimalno 16 znakova!<br>");
            provjera = false;
        }
        var znak = korIme.charCodeAt(0);
        if(znak < 97 || znak > 122) {
            $("#pogreska").html($("#pogreska").html() + "Korisničko ime mora započeti malim slovom!<br>");
            provjera = false;
        }
        var velikoSlovo = kVeliko(korIme);
        if(velikoSlovo < 1) {
            $("#pogreska").html($("#pogreska").html() + "Korisničko ime mora sadržavati barem jedno veliko slovo!<br>");
            provjera = false;
        }
        var posebanZnak = kPoseban(korIme);
        if(posebanZnak > 0) {
            $("#pogreska").html($("#pogreska").html() + 'Korisničko ime ne smije sadržavati znakove "!", "#", "$" i "?"!<br>');
            provjera = false;
        }
    }
    return provjera;
}

function postojiKorisnickoIme() {
    var provjera = true;
    $.ajax({
        async: false,
        url: "provjeri_korisnicko_ime.php",
        method: "POST",
        data: $("#forma").serialize(),
        dataType: "json",
        success: function(status) {
            if(status !== "zauzeto") {
                $("#pogreska").html($("#pogreska").html() + "Uneseno korisničko ime ne postoji u bazi podataka!<br>");
                provjera = false;
            }
        }
    });
    return provjera;
}

function provjeriLozinku() {
    var lozinka = $("#lozinka").val();
    var provjera = true;
    if($("#lozinka").attr("type") !== "password") {
        $("#pogreska").html($("#pogreska").html() + "Tip polja za unos lozinke mora biti jednak tipu password!<br>");
        provjera = false;
    }
    if(lozinka === "") {
        $("#pogreska").html($("#pogreska").html() + "Lozinka nije unesena!<br>");
        provjera = false;
    }
    else {
        if(lozinka.length < 8 || lozinka.length > 16) {
            $("#pogreska").html($("#pogreska").html() + "Lozinka mora sadržavati minimalno 8, a maksimalno 16 znakova!<br>");
            provjera = false;
        }
        var velikoSlovo = lVeliko(lozinka);
        if(velikoSlovo < 1) {
            $("#pogreska").html($("#pogreska").html() + "Lozinka mora sadržavati barem jedno veliko slovo!<br>");
            provjera = false;
        }
        var maloSlovo = lMalo(lozinka);
        if(maloSlovo < 1) {
            $("#pogreska").html($("#pogreska").html() + "Lozinka mora sadržavati barem jedno malo slovo!<br>");
            provjera = false;
        }
        var broj = lBroj(lozinka);
        if(broj < 1) {
            $("#pogreska").html($("#pogreska").html() + "Lozinka mora sadržavati barem jedan broj!<br>");
            provjera = false;
        }
    }
    return provjera;
}

function tocnaLozinka() {
    var provjera = true;
    $.ajax({
        async: false,
        url: "provjeri_lozinku.php",
        method: "POST",
        data: $("#forma").serialize(),
        dataType: "json",
        success: function(status) {
            if(status !== "točna") {
                $("#pogreska").html($("#pogreska").html() + "Unesena lozinka nije točna!<br>");
                provjera = false;
            }
        }
    });
    return provjera;
}

function aktiviranKorisnik() {
    var provjera = true;
    $.ajax({
        async: false,
        url: "provjeri_aktiviran_korisnik.php",
        method: "POST",
        data: $("#forma").serialize(),
        dataType: "json",
        success: function(status) {
            if(status !== "aktiviran") {
                $("#pogreska").html($("#pogreska").html() + "Vaš korisnički račun nije aktiviran! Morate aktivirati svoj korisnički račun kako biste se mogli prijaviti u sustav.<br>");
                provjera = false;
            }
        }
    });
    return provjera;
}

function neuspjesnePrijave() {
    var provjera = true;
    $.ajax({
        async: false,
        url: "provjeri_neuspjesne_prijave.php",
        method: "POST",
        data: $("#forma").serialize(),
        dataType: "json",
        success: function(status) {
            if(status !== "status") {
                $("#pogreska").html($("#pogreska").html() + "Vaš korisnički račun je zaključan! Kontaktirajte administratora kako biste otključali korisnički račun.<br>");
                provjera = false;
            }
        }
    });
    return provjera;
}

function poljeUFokusu() {
    $("input").blur(function() {
        $('#' + this.id).removeClass("fokus");
    });
    $("input").focus(function() {
        $('#' + this.id).addClass("fokus");
    });
}

function kreirajDogadajPrijava() {
    poljeUFokusu();
    $("#forma").submit(function(event) {
        $("#pogreska").html("");
        var pogreska = false;
        if(!provjeriKorisnickoIme()) {
            $("#korime").removeClass("polje");
            $("#korime").removeClass("polje_tocno");
            $("#korime").addClass("polje_netocno");
            pogreska = true;
        }
        else if(!postojiKorisnickoIme()) {
            $("#korime").removeClass("polje");
            $("#korime").removeClass("polje_tocno");
            $("#korime").addClass("polje_netocno");
            pogreska = true;
        }
        else {
            $("#korime").removeClass("polje");
            $("#korime").removeClass("polje_netocno");
            $("#korime").addClass("polje_tocno");
        }
        if(!provjeriLozinku()) {
            $("#lozinka").removeClass("polje");
            $("#lozinka").removeClass("polje_tocno");
            $("#lozinka").addClass("polje_netocno");
            pogreska = true;
        }
        else {
            $("#lozinka").removeClass("polje");
            $("#lozinka").removeClass("polje_netocno");
            $("#lozinka").addClass("polje_tocno");
        }
        if(!pogreska) {
            if(!neuspjesnePrijave()) {
                $("#korime").removeClass("polje");
                $("#korime").removeClass("polje_tocno");
                $("#korime").addClass("polje_netocno");
                pogreska = true;
            }
            else if(!tocnaLozinka()) {
                $("#lozinka").removeClass("polje");
                $("#lozinka").removeClass("polje_tocno");
                $("#lozinka").addClass("polje_netocno");
                pogreska = true;
            }
            else if(!aktiviranKorisnik()) {
                $("#korime").removeClass("polje");
                $("#korime").removeClass("polje_tocno");
                $("#korime").addClass("polje_netocno");
                pogreska = true;
            }
        }
        if(pogreska) event.preventDefault();
    });
}

function zaboravljenaLozinka() {
    $("#pogreska").html("");
    if(!provjeriKorisnickoIme()) {
        $("#korime").removeClass("polje");
        $("#korime").removeClass("polje_tocno");
        $("#korime").addClass("polje_netocno");
    }
    else if(!postojiKorisnickoIme()) {
        $("#korime").removeClass("polje");
        $("#korime").removeClass("polje_tocno");
        $("#korime").addClass("polje_netocno");
    }
    else {
        $("#korime").removeClass("polje_netocno");
        window.location = "zaboravljena_lozinka.php?korime=" + $("#korime").val();
    }
}