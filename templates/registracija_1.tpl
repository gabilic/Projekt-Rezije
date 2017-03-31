<!DOCTYPE html>
<html>
    <head>
        <title>{$naslov}</title>
        <meta charset="UTF-8">
        <meta name="author" content="Gabriel Ilić">
        <meta name="keywords" content="WebDiP, projekt, režije, HTML, CSS, Javascript, PHP">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Sustav koji omogućava korisnicima pregled računa svojih režija">
        <link href="css/dizajn.css" rel="stylesheet" type="text/css" media="screen">
    </head>
    <body onload="kreirajDogadajRegistracija();">
        <header>
            <h1>{$naslov}</h1>
            <p>Projekt režije</p>
            <div id="button_container">
                {if isset($smarty.session.prijava)}
                    {"<a href='odjava.php'><span class='prij_reg_odj'>Odjava</span></a>"}
                {else}
                    {"<a href='prijava.php'><span class='prij_reg_odj'>Prijava</span></a>"}
                    {"<a href='registracija.php'><span class='prij_reg_odj'>Registracija</span></a>"}
                {/if}
            </div>
        </header>
        <div id="container">
            <nav>
                <div id="nav">
                    <ul>
                        <li><a href="index.php">Početna</a></li>
                        <li><a href="privatno/korisnici.php">Ispis korisnika</a></li>
                        {if isset($smarty.session.prijava)}
                            {if $smarty.session.prijava.1 === "administrator"}
                                {"<li><a href='virtualno_vrijeme.php'>Virtualno vrijeme</a></li>"}
                            {/if}
                        {/if}
                        {if isset($smarty.session.prijava)}
                            {if $smarty.session.prijava.1 === "administrator"}
                                {"<li><a href='dnevnik.php'>Dnevnik</a></li>"}
                            {/if}
                        {/if}
                        {if isset($smarty.session.prijava)}
                            {if $smarty.session.prijava.1 === "administrator"}
                                {"<li><a href='statistika.php'>Statistika</a></li>"}
                            {/if}
                        {/if}
                        {if isset($smarty.session.prijava)}
                            {if $smarty.session.prijava.1 === "administrator"}
                                {"<li><a href='rad_s_korisnicima.php'>Rad s korisnicima</a></li>"}
                            {/if}
                        {/if}
                        {if isset($smarty.session.prijava)}
                            {if $smarty.session.prijava.1 === "administrator"}
                                {"<li><a href='konfiguracija.php'>Konfiguracija sustava</a></li>"}
                            {/if}
                        {/if}
                        {if isset($smarty.session.prijava)}
                            {if $smarty.session.prijava.1 === "administrator"}
                                {"<li><a href='ustanove.php'>Kreiranje ustanova</a></li>"}
                            {/if}
                        {/if}
                        {if isset($smarty.session.prijava)}
                            {if $smarty.session.prijava.1 === "administrator"}
                                {"<li><a href='adrese_poslovnica.php'>Definiranje adresa poslovnica</a></li>"}
                            {/if}
                        {/if}
                        {if isset($smarty.session.prijava)}
                            {if $smarty.session.prijava.1 === "administrator" || $smarty.session.prijava.1 === "zaposlenik"}
                                {"<li><a href='ocitavanje.php'>Kategorije i cijena usluge očitavanja</a></li>"}
                            {/if}
                        {/if}
                        {if isset($smarty.session.prijava)}
                            {if $smarty.session.prijava.1 === "administrator" || $smarty.session.prijava.1 === "zaposlenik"}
                                {"<li><a href='rezervacija.php'>Rezervacije očitavanja</a></li>"}
                            {/if}
                        {/if}
                        {if isset($smarty.session.prijava)}
                            {if $smarty.session.prijava.1 === "administrator" || $smarty.session.prijava.1 === "zaposlenik"}
                                {"<li><a href='racun.php'>Izdavanje računa</a></li>"}
                            {/if}
                        {/if}
                        {if isset($smarty.session.prijava)}
                            {if $smarty.session.prijava.1 === "administrator" || $smarty.session.prijava.1 === "zaposlenik"}
                                {"<li><a href='zalba.php'>Popis žalbi</a></li>"}
                            {/if}
                        {/if}
                        {if isset($smarty.session.prijava)}
                            {if $smarty.session.prijava.1 === "administrator" || $smarty.session.prijava.1 === "zaposlenik"}
                                {"<li><a href='aplikativna_statistika.php'>Aplikativna statistika</a></li>"}
                            {/if}
                        {/if}
                        {if isset($smarty.session.prijava)}
                            {if $smarty.session.prijava.1 === "administrator" || $smarty.session.prijava.1 === "zaposlenik" || $smarty.session.prijava.1 === "potrošač"}
                                {"<li><a href='korisnik_rezervacija.php'>Rezervacija očitavanja potrošnje</a></li>"}
                            {/if}
                        {/if}
                        {if isset($smarty.session.prijava)}
                            {if $smarty.session.prijava.1 === "administrator" || $smarty.session.prijava.1 === "zaposlenik" || $smarty.session.prijava.1 === "potrošač"}
                                {"<li><a href='korisnik_racuni.php'>Prikaz računa</a></li>"}
                            {/if}
                        {/if}
                        {if isset($smarty.session.prijava)}
                            {if $smarty.session.prijava.1 === "administrator" || $smarty.session.prijava.1 === "zaposlenik" || $smarty.session.prijava.1 === "potrošač"}
                                {"<li><a href='korisnik_zalba.php'>Podnošenje žalbe</a></li>"}
                            {/if}
                        {/if}
                        <li><a href="neregistrirani_korisnik.php">Popis ustanova</a></li>
                        <li><a href="dokumentacija.html">Dokumentacija</a></li>
                        <li><a href="o_autoru.html">O autoru</a></li>
                    </ul>
                </div>
            </nav>
            <section>
                <div class="blok1">
                    <h2>Registracija korisnika</h2>
                    <p>Molimo ispunite sljedeće podatke za registraciju:</p>
                    <div id="f">
                        <form id="forma" method="post" name="forma" action="registracija.php">
                            <label for="ime" class="l mob2">Ime: </label>
                            <input type="text" id="ime" class="polje" name="ime" placeholder="ime"><br>
                            <label for="prezime" class="l">Prezime: </label>
                            <input type="text" id="prezime" class="polje" name="prezime" placeholder="prezime"><br>
                            <div id="ime_reg"><label for="korime" class="l mob2">Korisničko ime: </label>
                            <input type="text" id="korime" class="polje" name="korime"
                                   maxlength="20" placeholder="kor. ime"></div><div id="ime_zauzeto"></div><br>
                            <label for="lozinka" class="l">Lozinka: </label>
                            <input type="password" id="lozinka" class="polje" name="lozinka" placeholder="lozinka"><br>
                            <label for="potvloz" class="l">Potvrda lozinke: </label>
                            <input type="password" id="potvloz" class="polje" name="potvloz" placeholder="lozinka"><br>
                            <label for="enkripcija" class="l mob2">Enkripcija: </label>
                            <keygen id="enkripcija" class="polje" name="enkripcija"><br>
                            <fieldset>
                                <legend class="l">Rođendan:</legend>
                                <label for="roddan" class="l mob2">Dan: </label>
                                <input type="number" id="roddan" class="polje" name="roddan" placeholder="dan"><br>
                                <label for="rodmjesec" class="l">Mjesec: </label>
                                <input type="text" id="rodmjesec" class="polje" name="rodmjesec" list="mjesec" placeholder="mjesec">
                                <datalist id="mjesec" class="polje">
                                    <option value="1">siječanj</option>
                                    <option value="2">veljača</option>
                                    <option value="3">ožujak</option>
                                    <option value="4">travanj</option>
                                    <option value="5">svibanj</option>
                                    <option value="6">lipanj</option>
                                    <option value="7">srpanj</option>
                                    <option value="8">kolovoz</option>
                                    <option value="9">rujan</option>
                                    <option value="10">listopad</option>
                                    <option value="11">studeni</option>
                                    <option value="12">prosinac</option>
                                </datalist><br>
                                <label for="rodgodina" class="l">Godina: </label>
                                <input type="number" id="rodgodina" class="polje" name="rodgodina" placeholder="godina">
                            </fieldset>
                            <label for="spol" class="l mob2">Spol: </label>
                            <select id="spol" class="polje" name="spol">
                                <option value="0">muški</option>
                                <option value="1">ženski</option>
                            </select><span class="razmak">&nbsp;</span><br>
                            <fieldset>
                                <legend class="l">Mobilni telefon:</legend>
                                <label for="drzava" class="l">Država: </label>
                                <select id="drzava" class="polje" name="drzava">
                                    <option value="Slovenija">Slovenija</option>
                                    <option value="Hrvatska">Hrvatska</option>
                                    <option value="Bosna i Hercegovina">Bosna i Hercegovina</option>
                                    <option value="Srbija">Srbija</option>
                                </select><br>
                                <label for="broj" class="l">Broj: </label>
                                <input type="tel" id="broj" class="polje" name="broj" placeholder="xxx xxxxxxx">
                            </fieldset>
                            <div id="email_reg"><label for="mail" class="l">Vaša trenutna e-adresa: </label>
                                <input type="email" id="mail" class="polje" name="mail" placeholder="e-mail"></div>
                            <div id="email_zauzet"></div><span class="razmak">&nbsp;</span><br>
                            <label for="lokacija" class="l">Lokacija: </label><br>
                            <textarea id="lokacija" class="polje" name="lokacija" rows="40" cols="100" placeholder="širina; dužina"></textarea><br>
                            <div id="radio1"><label for="obavijesti" class="l">Obavijesti: </label></div>
                            <div id="radio2">
                            <input type="radio" id="obavijesti" name="obavijesti" value="DA"> DA<br>
                            <input type="radio" id="obavijesti2" name="obavijesti" value="NE"> NE<br></div>
                            <div class="g-recaptcha" data-sitekey="6LeB6x4TAAAAANB6mxV1zLCqx9DiqmWQsyPTP9em"></div>
                            <input type="submit" value="Registriraj se">
                            <div id="pogreska"></div>
                        </form>
                    </div>