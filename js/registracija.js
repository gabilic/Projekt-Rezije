function provjeriIme(f) {
    var ime = f.ime.value;
    if(ime === "") {
        document.getElementById("pogreska").innerHTML += "Ime nije uneseno!<br>";
        return false;
    }
    return true;
}

function provjeriPrezime(f) {
    var prezime = f.prezime.value;
    if(prezime === "") {
        document.getElementById("pogreska").innerHTML += "Prezime nije uneseno!<br>";
        return false;
    }
    return true;
}

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

function provjeriKorisnickoIme(f) {
    var korIme = f.korime.value;
    var tip = f.korime.type;
    var provjera = true;
    if(tip !== "text") {
        document.getElementById("pogreska").innerHTML += "Tip polja za unos korisničkog imena mora biti jednak tipu text!<br>";
        provjera = false;
    }
    if(korIme === "") {
        document.getElementById("pogreska").innerHTML += "Korisničko ime nije uneseno!<br>";
        provjera = false;
    }
    else {
        if(korIme.length < 8 || korIme.length > 16) {
            document.getElementById("pogreska").innerHTML += "Korisničko ime mora sadržavati minimalno 8, a maksimalno 16 znakova!<br>";
            provjera = false;
        }
        var znak = korIme.charCodeAt(0);
        if(znak < 97 || znak > 122) {
            document.getElementById("pogreska").innerHTML += "Korisničko ime mora započeti malim slovom!<br>";
            provjera = false;
        }
        var velikoSlovo = kVeliko(korIme);
        if(velikoSlovo < 1) {
            document.getElementById("pogreska").innerHTML += "Korisničko ime mora sadržavati barem jedno veliko slovo!<br>";
            provjera = false;
        }
        var posebanZnak = kPoseban(korIme);
        if(posebanZnak > 0) {
            document.getElementById("pogreska").innerHTML += 'Korisničko ime ne smije sadržavati znakove "!", "#", "$" i "?"!<br>';
            provjera = false;
        }
    }
    return provjera;
}

function zauzetoKorisnickoIme() {
    var provjera = true;
    $.ajax({
        async: false,
        url: "provjeri_korisnicko_ime.php",
        method: "POST",
        data: $("#forma").serialize(),
        dataType: "json",
        success: function(status) {
            if(status === "zauzeto") {
                $("#pogreska").html($("#pogreska").html() + "Korisničko ime je zauzeto!<br>");
                provjera = false;
            }
        }
    });
    return provjera;
}

function provjeriLozinku(f) {
    var lozinka1 = f.lozinka.value;
    var lozinka2 = f.potvloz.value;
    var tip1 = f.lozinka.type;
    var tip2 = f.potvloz.type;
    var provjera = true;
    if(tip1 !== "password") {
        document.getElementById("pogreska").innerHTML += "Tip polja za unos lozinke mora biti jednak tipu password!<br>";
        f.lozinka.className = "polje_netocno";
        provjera = false;
    }
    if(tip2 !== "password") {
        document.getElementById("pogreska").innerHTML += "Tip polja za potvrdu lozinke mora biti jednak tipu password!<br>";
        f.potvloz.className = "polje_netocno";
        provjera = false;
    }
    if(lozinka1 === "") {
        document.getElementById("pogreska").innerHTML += "Lozinka nije unesena!<br>";
        f.lozinka.className = "polje_netocno";
        provjera = false;
    }
    else {
        if(lozinka1.length < 8 || lozinka1.length > 16) {
            document.getElementById("pogreska").innerHTML += "Lozinka mora sadržavati minimalno 8, a maksimalno 16 znakova!<br>";
            f.lozinka.className = "polje_netocno";
            provjera = false;
        }
        var velikoSlovo = lVeliko(lozinka1);
        if(velikoSlovo < 1) {
            document.getElementById("pogreska").innerHTML += "Lozinka mora sadržavati barem jedno veliko slovo!<br>";
            f.lozinka.className = "polje_netocno";
            provjera = false;
        }
        var maloSlovo = lMalo(lozinka1);
        if(maloSlovo < 1) {
            document.getElementById("pogreska").innerHTML += "Lozinka mora sadržavati barem jedno malo slovo!<br>";
            f.lozinka.className = "polje_netocno";
            provjera = false;
        }
        var broj = lBroj(lozinka1);
        if(broj < 1) {
            document.getElementById("pogreska").innerHTML += "Lozinka mora sadržavati barem jedan broj!<br>";
            f.lozinka.className = "polje_netocno";
            provjera = false;
        }
    }
    if(lozinka2 === "") {
        document.getElementById("pogreska").innerHTML += "Potvrda lozinke nije unesena!<br>";
        f.potvloz.className = "polje_netocno";
        provjera = false;
    }
    if(provjera) {
        if(lozinka1 !== lozinka2) {
            document.getElementById("pogreska").innerHTML += "Lozinka i potvrda lozinke se ne poklapaju!<br>";
            f.lozinka.className = "polje_netocno";
            f.potvloz.className = "polje_netocno";
            provjera = false;
        }
    }
    return provjera;
}

function provjeriDatum(f) {
    var dan = f.roddan.value;
    var mjesec = f.rodmjesec.value;
    var godina = f.rodgodina.value;
    var tip1 = f.roddan.type;
    var tip2 = f.rodmjesec;
    var tip3 = f.rodgodina.type;
    var provjera = true;
    if(tip1 !== "number") {
        document.getElementById("pogreska").innerHTML += "Tip polja za unos dana rođenja mora biti jednak tipu number!<br>";
        f.roddan.className = "polje_netocno";
        provjera = false;
    }
    if(!tip2.hasAttribute("list")) {
        document.getElementById("pogreska").innerHTML += "Tip polja za unos mjeseca rođenja mora biti jednak tipu datalist!<br>";
        f.rodmjesec.className = "polje_netocno";
        provjera = false;
    }
    if(tip3 !== "number") {
        document.getElementById("pogreska").innerHTML += "Tip polja za unos godine rođenja mora biti jednak tipu number!<br>";
        f.rodgodina.className = "polje_netocno";
        provjera = false;
    }
    if(dan === "") {
        document.getElementById("pogreska").innerHTML += "Dan rođenja nije unesen!<br>";
        f.roddan.className = "polje_netocno";
        provjera = false;
    }
    else if(dan < 1 || dan > 31) {
        document.getElementById("pogreska").innerHTML += "Dan rođenja mora biti u rasponu od 1 do 31!<br>";
        f.roddan.className = "polje_netocno";
        provjera = false;
    }
    if(mjesec === "") {
        document.getElementById("pogreska").innerHTML += "Mjesec rođenja nije unesen!<br>";
        f.rodmjesec.className = "polje_netocno";
        provjera = false;
    }
    if(godina === "") {
        document.getElementById("pogreska").innerHTML += "Godina rođenja nije unesena!<br>";
        f.rodgodina.className = "polje_netocno";
        provjera = false;
    }
    else if(godina < 1930 || godina > 2015) {
        document.getElementById("pogreska").innerHTML += "Godina rođenja mora biti u rasponu od 1930 do 2015!<br>";
        f.rodgodina.className = "polje_netocno";
        provjera = false;
    }
    if(dan < 0 || godina < 0) {
        document.getElementById("pogreska").innerHTML += "Datum rođenja ne smije sadržavati negativne vrijednosti!<br>";
        f.roddan.className = "polje_netocno";
        f.rodgodina.className = "polje_netocno";
        provjera = false;
    }
    return provjera;
}

function provjeriTelefon() {
    var re = RegExp(/.+/);
    var ok = re.test($("#broj").val());
    var provjera = true;
    if($("#broj").attr("type") !== "tel") {
        $("#pogreska").html($("#pogreska").html() + "Tip polja za unos telefona mora biti jednak tipu tel!<br>");
        provjera = false;
    }
    if(!ok) {
        $("#pogreska").html($("#pogreska").html() + "Telefon nije unesen!<br>");
        provjera = false;
    }
    else {
        re = RegExp(/^\d{3} \d{7}$/);
        ok = re.test($("#broj").val());
        if(!ok) {
            $("#pogreska").html($("#pogreska").html() + "Telefon nije ispravno unesen!<br>");
            provjera = false;
        }
    }
    return provjera;
}

function provjeriEmail() {
    var re = RegExp(/.+/);
    var ok = re.test($("#mail").val());
    var provjera = true;
    if($("#mail").attr("type") !== "email") {
        $("#pogreska").html($("#pogreska").html() + "Tip polja za unos e-mail adrese mora biti jednak tipu email!<br>");
        provjera = false;
    }
    if(!ok) {
        $("#pogreska").html($("#pogreska").html() + "E-mail adresa nije unesena!<br>");
        provjera = false;
    }
    else {
        re = RegExp(/^\w[\w\.]*@\w+\.\w+$/);
        ok = re.test($("#mail").val());
        if(!ok) {
            $("#pogreska").html($("#pogreska").html() + "E-mail adresa nije ispravno unesena!<br>");
            provjera = false;
        }
    }
    return provjera;
}

function zauzetEmail() {
    var provjera = true;
    $.ajax({
        async: false,
        url: "provjeri_email.php",
        method: "POST",
        data: $("#forma").serialize(),
        dataType: "json",
        success: function(status) {
            if(status === "zauzeto") {
                $("#pogreska").html($("#pogreska").html() + "E-mail je zauzet!<br>");
                provjera = false;
            }
        }
    });
    return provjera;
}

function provjeriLokaciju() {
    var re = RegExp(/.+/);
    var ok = re.test($("#lokacija").val());
    var provjera = true;
    if($("#lokacija").prop("tagName").toLowerCase() !== "textarea") {
        $("#pogreska").html($("#pogreska").html() + "Tip polja za unos lokacije mora biti jednak tipu textarea!<br>");
        provjera = false;
    }
    if(!ok) {
        $("#pogreska").html($("#pogreska").html() + "Lokacija nije unesena!<br>");
        provjera = false;
    }
    else {
        re = RegExp(/^\d+\.\d+; \d+\.\d+$/);
        ok = re.test($("#lokacija").val());
        if(!ok) {
            $("#pogreska").html($("#pogreska").html() + "Lokacija nije ispravno unesena!<br>");
            provjera = false;
        }
    }
    return provjera;
}

function provjeriObavijesti() {
    if(!$("#obavijesti")[0].checked && !$("#obavijesti2")[0].checked) {
        $("#pogreska").html($("#pogreska").html() + "Niste odabrali da li želite primati obavijesti!<br>");
        return false;
    }
    return true;
}

function provjeriRobot() {
    var provjera = true;
    var captcha = grecaptcha.getResponse();
    if(captcha === "") {
        $("#pogreska").html($("#pogreska").html() + "Dokažite da niste robot!<br>");
        provjera = false;
    }
    return provjera;
}

function poljeUFokusu() {
    $("input").blur(function() {
        $('#' + this.id).removeClass("fokus");
    });
    $("input").focus(function() {
        $('#' + this.id).addClass("fokus");
    });
    $("textarea").blur(function() {
        $('#' + this.id).removeClass("fokus");
    });
    $("textarea").focus(function() {
        $('#' + this.id).addClass("fokus");
    });
}

function korimeHighlight() {
    $("#ime_zauzeto").hide();
    $("#ime_zauzeto").html("Korisničko ime je zauzeto!");
    $("#korime").focusout(function () {
        $.ajax({
            url: "provjeri_korisnicko_ime.php",
            type: "POST",
            data: $("#forma").serialize(),
            dataType: "json",
            success: function(status) {
                if(status === "zauzeto") {
                    if(!$("#ime_zauzeto").is(":visible")) {
                        $("#ime_zauzeto").toggle("highlight", {color: "#0D2839"});
                        $("#korime").removeClass("polje");
                        $("#korime").addClass("polje_netocno");
                    }
                }
                else {
                    if($("#ime_zauzeto").is(":visible")) {
                        $("#ime_zauzeto").toggle("highlight", {color: "#0D2839"});
                        $("#korime").removeClass("polje_netocno");
                        $("#korime").addClass("polje");
                    }
                }
            }
        });
    });
}

function emailHighlight() {
    $("#email_zauzet").hide();
    $("#email_zauzet").html("E-mail je zauzet!");
    $("#mail").focusout(function () {
        $.ajax({
            url: "provjeri_email.php",
            type: "POST",
            data: $("#forma").serialize(),
            dataType: "json",
            success: function(status) {
                if(status === "zauzeto") {
                    if(!$("#email_zauzet").is(":visible")) {
                        $("#email_zauzet").toggle("highlight", {color: "#0D2839"});
                        $("#mail").removeClass("polje");
                        $("#mail").addClass("polje_netocno");
                    }
                }
                else {
                    if($("#email_zauzet").is(":visible")) {
                        $("#email_zauzet").toggle("highlight", {color: "#0D2839"});
                        $("#mail").removeClass("polje_netocno");
                        $("#mail").addClass("polje");
                    }
                }
            }
        });
    });
}

function kreirajDogadajRegistracija() {
    poljeUFokusu();
    korimeHighlight();
    emailHighlight();
    var formular = document.getElementById("forma");
    formular.addEventListener("submit", function(event) {
        document.getElementById("pogreska").innerHTML = "";
        var pogreska = false;
        if(!provjeriIme(this)) {
            this.ime.className = "polje_netocno";
            pogreska = true;
        }
        else this.ime.className = "polje_tocno";
        if(!provjeriPrezime(this)) {
            this.prezime.className = "polje_netocno";
            pogreska = true;
        }
        else this.prezime.className = "polje_tocno";
        if(!provjeriKorisnickoIme(this)) {
            this.korime.className = "polje_netocno";
            pogreska = true;
        }
        else if(!zauzetoKorisnickoIme()) {
            this.korime.className = "polje_netocno";
            pogreska = true;
        }
        else this.korime.className = "polje_tocno";
        if(!provjeriLozinku(this)) pogreska = true;
        else {
            this.lozinka.className = "polje_tocno";
            this.potvloz.className = "polje_tocno";
        }
        if(!provjeriDatum(this)) pogreska = true;
        else {
            this.roddan.className = "polje_tocno";
            this.rodmjesec.className = "polje_tocno";
            this.rodgodina.className = "polje_tocno";
        }
        if(!provjeriTelefon()) {
            $("#broj").removeClass("polje");
            $("#broj").removeClass("polje_tocno");
            $("#broj").addClass("polje_netocno");
            pogreska = true;
        }
        else {
            $("#broj").removeClass("polje");
            $("#broj").removeClass("polje_netocno");
            $("#broj").addClass("polje_tocno");
        }
        if(!provjeriEmail()) {
            $("#mail").removeClass("polje");
            $("#mail").removeClass("polje_tocno");
            $("#mail").addClass("polje_netocno");
            pogreska = true;
        }
        else if(!zauzetEmail()) {
            $("#mail").removeClass("polje");
            $("#mail").removeClass("polje_tocno");
            $("#mail").addClass("polje_netocno");
            pogreska = true;
        }
        else {
            $("#mail").removeClass("polje");
            $("#mail").removeClass("polje_netocno");
            $("#mail").addClass("polje_tocno");
        }
        if(!provjeriLokaciju()) {
            $("#lokacija").removeClass("polje");
            $("#lokacija").removeClass("polje_tocno");
            $("#lokacija").addClass("polje_netocno");
            pogreska = true;
        }
        else {
            $("#lokacija").removeClass("polje");
            $("#lokacija").removeClass("polje_netocno");
            $("#lokacija").addClass("polje_tocno");
        }
        if(!provjeriObavijesti()) pogreska = true;
        if(!provjeriRobot()) pogreska = true;
        if(pogreska) event.preventDefault();
    });
}